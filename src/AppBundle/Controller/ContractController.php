<?php
/**
 * Created by PhpStorm.
 * User: hessam
 * Date: 1/14/17
 * Time: 3:03 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Contract;
use AppBundle\Entity\Job;
use AppBundle\Entity\Service;
use AppBundle\Entity\ServiceItems;
use AppBundle\Entity\Sharing;
use AppBundle\Entity\TravelDocument;
use AppBundle\Form\ContractType;
use AppBundle\Form\JobType;
use AppBundle\Form\JourneyType;
use AppBundle\Form\TravelDocumentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ContractController extends  BaseController
{

    /**
     * @Method({"GET","POST"})
     * @Route("/contract")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createContract(Request $request)
    {
       // dump($request->request->all());die;
        $objectEntity=new Contract();
        $form = $this->createForm(ContractType::class, $objectEntity, [ "method" => 'POST']);
        $form->handleRequest($request);
        if ($request->getMethod() == 'POST') {
            if ($form->isSubmitted() && $form->isValid()) {
                $firm = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($firm);
                $em->flush();
                return $this->redirect('/');
            }
        }
        return $this->render(':page:contract.html.twig', [
            'form' => $form->createView(),
            'formIsNotValid' => $form->isSubmitted() && !$form->isValid()
        ]);

    }

    /**
     * @Method({"GET","PATCH"})
     * @Route("/contract/{id}")
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function ManageContract($id,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $findObject = $this->getDoctrine()->getRepository("AppBundle:Contract")->find($id);
        $method=$request->getMethod();
        if($method=="PATCH") {
            /** @var ServiceItems $value */
            foreach ($findObject->getServiceItems()->getValues() as $value) {
                $findObject->removeServiceItem($value);
                $em->persist($findObject);
            }
            /** @var ServiceItems $item */
            foreach ($findObject->getShareItems()->getValues() as $item) {
                $findObject->removeShareItem($item);
                $em->persist($findObject);
            }
            $em->flush();
        }

        $form = $this->createForm(ContractType::class, $findObject, [ "method" => $method]);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if ($request->getMethod() == $method) {

            if ($form->isSubmitted() && $form->isValid()) {
                $firm = $form->getData();

//                $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($this->getUser());
//                $form->getData()->setOwner($user);
                $em->persist($firm);
                $em->flush();
                return $this->redirect('/');
            }
        }
        return $this->render(':page:contract.html.twig', [
            'form' => $form->createView(),
            'formIsNotValid' => $form->isSubmitted() && !$form->isValid()
        ]);

    }
    /**
     * @Route("/delete/contract/{id}")
     * @Method("GET")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteContract($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Contract')->find($id);
        if (!$entity) throw new NotFoundHttpException('Item does not found.');
        $em->remove($entity);
        $em->flush();
        return $this->redirect("/");

    }



}