<?php

namespace AppBundle\Controller;

use AppBundle\Api\ApiProblem;
use AppBundle\Controller\BaseController;
use AppBundle\Entity\BlockIp;
use AppBundle\Entity\Company;
use AppBundle\Entity\Engineer;
use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Form\ForgotPasswordForm;
use AppBundle\Form\ForgotPasswordType;
use AppBundle\Form\LoginForm;
use AppBundle\Form\RegisterFrontEndUser;
use AppBundle\Form\ResetPasswordForm;
use AppBundle\Form\UserType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Faker\Factory;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;

class LoginController extends BaseController
{

    /**
     * @Route("/login",name="login")
     * @Method({"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function login(Request $request)
    {
        $result = false;
        $response = new Response();
        $user = new User();
        $loginForm = $this->createForm(LoginForm::class, $user, ["validation_groups" => "login"]);
        $loginForm->handleRequest($request);
        if ($loginForm->isSubmitted() && $loginForm->isValid()) {

            $result = $this->handleLoginForm($request, $response);
            if ($result == false) {
                return $this->redirect('/');
            }
        }
        $response->setContent(
            $this->container->get('twig')->render(
                'common/login.html.twig', array(
                'result' => $result,
                'loginForm' => $loginForm->createView(),
                'loginFormIsNotValid' => $loginForm->isSubmitted() && !$loginForm->isValid())));

        return $response;
    }


    /**
     * @Route("/admin/register",name="register")
     * @Method({"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function RegisterForm(Request $request)
    {
        $user = new User();
        $form = $this->createForm(
            RegisterFrontEndUser::class, $user);
        $form->handleRequest($request);
        if ($form->isValid()) {

            $data = $form->getData();

           // $user->setUsername($request->request->get('register_front_end_user')['email']);
            $encoder = $this->get('security.password_encoder');
            $user->setRole($this->getDoctrine()->getRepository('AppBundle:Role')->findOneBy(['name' => Role::ROLE_FrontendUser]));
            $user->setPassword($encoder->encodePassword($user, $request->request->get('register_front_end_user')['password']));
            $this->getDoctrine()->getManager()->persist($data);
            $this->getDoctrine()->getManager()->flush();
            $token = $this->get('lexik_jwt_authentication.encoder')->encode(['username' => $user->getUsername()]);
            $session = new Session();
            $session->set('token', $token);
            $session->save();

            return $this->redirect('/');

        }

        return $this->render('common/register.html.twig', [
            'form' => $form->createView(),
            'formIsNotValid' => $form->isSubmitted() && !$form->isValid()
        ]);
    }


    private function handleLoginForm(Request $request, Response $response)
    {
        $em = $this->getDoctrine()->getManager();

        $blockedUser = $em->getRepository('AppBundle:BlockIp')->findOneBy(
            [
                'ip' => $request->getClientIp(),
                'type' => BlockIp::LOGIN_TYPE]);
        $date = new \DateTime($this->container->getParameter('ban_minutes') . " minutes");
        if ($blockedUser && $blockedUser->getCounter() > $this->container->getParameter('ban_max_count')) {
            if ($date < $blockedUser->getUpdatedAt()) {

                return 'Your Ip is banned.';
            } else {
                $blockedUser->setCounter(0);
                $em->persist($blockedUser);
                $em->flush();
            }
        }

        $user = $em->getRepository('AppBundle:User')->findOneBy(['username' => $request->request->get('login_form')['username']]);
        if (!$user || $user->getRole()->getName() != (Role::ROLE_FrontendUser || Role::ROLE_ADMIN)) {
            $this->manageBlockIp($request, $blockedUser, $em, BlockIp::LOGIN_TYPE);

            return 'نام کاربری و یا پسورد شما صحیح نمی باشد';
        }
        $isValid = $this->get('security.password_encoder')->isPasswordValid($user, $request->request->get('login_form')['password']);
        if (!$isValid) {
            $this->manageBlockIp($request, $blockedUser, $em, BlockIp::LOGIN_TYPE);

            return 'نام کاربری و یا پسورد شما صحیح نمی باشد';
        }
//        $recaptcha = new ReCaptcha('6Lcx3yUUAAAAAC96VKZzw55xQlkxXT8L2vjHgoEP');
//        $resp = $recaptcha->verify($request->request->get('g-recaptcha-response'), $request->getClientIp());

//        if (!$resp->isSuccess()) {
//            // Do something if the submit wasn't valid ! Use the message to show something
//            return  "The reCAPTCHA wasn't entered correctly. Go back and try it again.";
//        }

        $token = $this->get('lexik_jwt_authentication.encoder')->encode(['username' => $user->getUsername()]);
        if (isset($request->request->get('login_form')['remember_me'])) {

            $request->headers->set('Authorization', $token);
            $cookie = new Cookie('token', $token, time() + 2 * 3600 * 7, '/', null, false, false);
            $response->headers->setCookie($cookie);
        } else {
            $session = new Session();
            $session->set('token', $token);
            $session->save();
        }

        return false;
    }


    /**
     * @Method("GET")
     * @Route("/account",name="account")
     * @Security("has_role('ROLE_FrontendUser')")
     */
    public function myAccount()
    {

        return $this->render('common/my-account.html.twig');

    }

    /**
     * @param Request $request
     * @param BlockIp $blockedUser
     * @param ObjectManager $em
     * @param $type
     */
    private function manageBlockIp(Request $request, $blockedUser, $em, $type)
    {
        if ($blockedUser) {
            $blockedUser->setCounter($blockedUser->getCounter() + 1);
            $em->persist($blockedUser);
            $em->flush();
        } else {
            $newBlockIP = new BlockIp();
            $newBlockIP->setIp($request->getClientIp());
            $newBlockIP->setCounter(1);
            $newBlockIP->setType($type);
            $em->persist($newBlockIP);
            $em->flush();
        }
    }

    private function resetPassword(Request $request)
    {
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneBy(['email' => $request->request->get('email')]);
        if (!$user) {
            return false;
        }

        return true;

    }

    /**
     * @Route("/profile")
     * @Method({"GET","PUT"})
     * @Security("is_granted(['ROLE_ADMIN','ROLE_FrontendUser'])")
     * @param Request $request
     * @return Response
     */
    public function updateUser(Request $request)
    {
        //
        $engineerStatus = null;
        $companyStatus = null;
        $em = $this->getDoctrine()->getEntityManager();
        $validationGroups = 'register';
        if ($this->getUser()->getRole()->getName() == Role::ROLE_ADMIN) {
            $user = $this->getDoctrine()->getRepository("AppBundle:User")->find($this->getUser());
            $validationGroups = UserType::VALIDATION_CHANGE_Admin;
        } else {
            $user = $this->getDoctrine()->getRepository("AppBundle:User")->find($this->getUser());
            if ($user->getEngineer()) {
                $engineerStatus = $user->getEngineer()->getId();
            }
            if ($user->getCompany()) {
                $companyStatus = $user->getCompany()->getId();
            }
        }
        $form = $this->createForm(UserType::class, $user, array("validation_groups" => $validationGroups, 'method' => 'PUT'));
        $form->handleRequest($request);
        if ($request->getMethod() == 'PUT') {
            if ($form->isSubmitted() && $form->isValid()) {
                if ($request->request->get('mainImage')) {
                    $mainImage = $request->request->get('mainImage');
                    $fileManager = $this->getDoctrine()->getRepository("AppBundle:FileManager")->find($mainImage);
                    $form->getData()->setAvatar($fileManager);
                }
                $em->persist($user);
                $em->flush();
                return $this->render('frontEnd/page/profile.html.twig', [
                    'form' => $form->createView(),
                    'formIsNotValid' => $form->isSubmitted() && !$form->isValid(),
                    'avatar' => '/' . $user->getAvatar()->getPath() . '/' . $user->getAvatar()->getName(),
                ]);
            }
        }

        return $this->render('frontEnd/page/profile.html.twig', [
            'form' => $form->createView(),
            'formIsNotValid' => $form->isSubmitted() && !$form->isValid(),
            'avatar' => '/' . $user->getAvatar()->getPath() . '/' . $user->getAvatar()->getName(),
        ]);
    }

    /**
     * /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Method({"GET","POST"})
     * @Route("/forgetPassword")
     */
    public function postForgotPassword(Request $request)
    {
        $em = $this->get('doctrine')->getManager();
        $blockedUser = $this->getDoctrine()->getRepository('AppBundle:BlockIp')->findOneBy(['ip' => $request->getClientIp(), 'type' => BlockIp::FORGOT_PASSWORD_TYPE]);
        $date = new \DateTime($this->container->getParameter('ban_minutes') . " minutes");
        $forgotPassword = new User();
        $form = $this->createForm(ForgotPasswordForm::class, $forgotPassword);
        $form->handleRequest($request);
        $errors = [];
        $userFound = 1;
        if ($request->getMethod() == 'POST') {
            $email = $request->request->get('forgot_password_form')['email'];
            $emailConstraint = new EmailConstraint();
            $emailConstraint->message = 'Your customized error message';

            $errors = $this->validateEmails($email);
            $user = $this->getDoctrine()
                ->getRepository('AppBundle:User')
                ->findOneBy(['email' => $email]);
            if (!$user) {
                $userFound = 0;
            }
            if ($form->isSubmitted() && count($errors) < 1 && $userFound == 1) {
                if ($blockedUser && $blockedUser->getCounter() > $this->container->getParameter('ban_max_count')) {
                    if ($date < $blockedUser->getUpdatedAt()) {
                        $this->throwApiProblem(403, ApiProblem::TYPE_YOUR_IP_IS_BANNED);
                    } else {
                        $blockedUser->setCounter(0);
                        $em->persist($blockedUser);
                        $em->flush();
                    }
                }

                $pwdResetHash = hash('sha256', $user->getId() . $this->container->getParameter('forgot_password_hook'));
                $user->setForgetPasswordKey($pwdResetHash);
                $em->persist($user);
                $em->flush();
                $body = $this->render(
                    ":emails:forgot-password.html.twig", [
                    "user" => $user,
                    "resetPasswordHash" => $pwdResetHash
                ]);
                $this->get("app.emails.email_service")->send($email, 'forget password', $body->getContent(), false, '');
                return $this->render('common/forgetPassWord.html.twig', [
                    'form' => $form->createView(),
                    'formIsNotValid' => $form->isSubmitted() && !$form->isValid(),
                    'errors' => $errors,
                    'userFound' => $userFound,
                    'success' => true
                ]);
            }
        }
        return $this->render('common/forgetPassWord.html.twig', [
            'form' => $form->createView(),
            'formIsNotValid' => $form->isSubmitted() && !$form->isValid(),
            'errors' => $errors,
            'userFound' => $userFound,
            'success' => false
        ]);
    }


    /**
     * @Method({"GET","POST"})
     * @Route("/reset-password/{key}")
     * @param $key
     * @param Request $request
     * @return Response
     */
    public function postResetPassword($key, Request $request)
    {
      //  dump($request->request->all());
        $resetPasswordMetaData = [];
        $em = $this->getDoctrine()->getManager();
        $resetPasswordMetaData["token"] = $request->request->get("token");
        $resetPasswordMetaData["password"] = $request->request->get("password");
        $resetPasswordMetaData["confirmation"] = $request->request->get("confirmation");

        $blank = new NotBlank();
        $blank->message = "Field should not be blank.";

        $repeatPassword = new EqualTo();
        $repeatPassword->value = $resetPasswordMetaData["password"];
        $repeatPassword->message = "The password and confirmation does not match.";

        $notNull = new NotNull();
        $notNull->message = 'Token can not be null.';

        $resetPasswordMetaForm = $this->createFormBuilder($resetPasswordMetaData, [
            'allow_extra_fields' => true,
            'csrf_protection' => false
        ])->add('password', TextType::class, ["required" => true, "constraints" => [
            $blank
        ]])->add("confirmation", TextType::class, ["required" => true, "constraints" => [
            $blank,
            $repeatPassword
        ]])->add("token", TextType::class, ["required" => true, "constraints" => [
            $blank,
            $notNull
        ]])->getForm();

        if ($request->getMethod() == 'POST' ) {
            $this->processForm($request, $resetPasswordMetaForm);
            $resetPasswordMetaForm ->handleRequest($request);
            if ($resetPasswordMetaForm->isValid()  && $resetPasswordMetaForm->isSubmitted()) {
                $user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneBy(['forget_password_key' => $request->request->get('token')]);
                if (!$user) return $this->createApiResponse([
                    'title' => 'Invalid Token',
                    'errors' =>
                        [
                            'Invalid Token' => ['Your token is not valid,The user does not found with this token!']
                        ]
                ], 400);
                $password = $this->get('security.password_encoder')->encodePassword($user, $request->request->get('password'));
                $user->setPassword($password);
                $user->setForgetPasswordKey(null);
                $em->persist($user);
                $em->flush();
                return $this->redirect("/login");
            }
        }
        return $this->render(':common:resetPassWord.html.twig', [
            'key' => $key,
            'form' => $resetPasswordMetaForm->createView(),
            'formIsNotValid' =>  !$resetPasswordMetaForm->isValid(),
        ]);
    }

    /**
     * Validates single email (or an array of email addresses
     *
     * @param array|string $emails
     *
     * @return array
     */
    public function validateEmails($emails)
    {

        $errors = array();
        $emails = is_array($emails) ? $emails : array($emails);

        $validator = $this->get('validator');

        $constraints = array(
            new Email(),
            new NotBlank()
        );

        foreach ($emails as $email) {

            $error = $validator->validate($email, $constraints);

            if (count($error) > 0) {
                $errors[] = $error;
            }
        }

        return $errors;
    }
}