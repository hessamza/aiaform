<?php
/**
 * Created by PhpStorm.
 * User: hessam
 * Date: 1/14/17
 * Time: 3:03 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Contract;
use AppBundle\Entity\ServiceItems;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Form\ContractType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ContractController extends  BaseController
{

    /**
     * @Security("is_granted(['ROLE_ADMIN','ROLE_FrontendUser'])")
     * @Method({"GET","POST"})
     * @Route("/contract")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createContract(Request $request)
    {
        $objectEntity=new Contract();
        $form = $this->createForm(ContractType::class, $objectEntity, [ "method" => 'POST']);
        $form->handleRequest($request);
        if ($request->getMethod() == 'POST') {
            if ($form->isSubmitted() && $form->isValid()) {
                $firm = $form->getData();
                $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($this->getUser());
                $form->getData()->setOwner($user);
                $em = $this->getDoctrine()->getManager();
                $em->persist($firm);
                $em->flush();
                return $this->redirect('/contracts');
            }
        }
        return $this->render(':page:contract.html.twig', [
            'form' => $form->createView(),
            'formIsNotValid' => $form->isSubmitted() && !$form->isValid()
        ]);

    }

    /**
     * @Security("is_granted(['ROLE_ADMIN','ROLE_FrontendUser'])")
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

                $em->persist($firm);
                $em->flush();
                return $this->redirect('/contracts');
            }
        }
        return $this->render(':page:contract.html.twig', [
            'form' => $form->createView(),
            'formIsNotValid' => $form->isSubmitted() && !$form->isValid()
        ]);

    }
    /**
     * @Security("is_granted(['ROLE_ADMIN','ROLE_FrontendUser'])")
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

    /**
     * @Route("/contracts",path="contract_list")
     * @Method({"GET"})
     * @return Response
     */
    public function searchContracts(){
        return $this->render('page/showContract.html.twig');
    }

    /**
     * @Route("/contracts/items",name="contract_list")
     * @Method("GET")
     * @param Request $request
     * @return Response
     */
    public function getContractsList(Request $request)
    {


        $extraFields = ['contractTimeTo','contractTimeFrom'];
        $searchEntity = $this->getClassMetaDataProperties('AppBundle:Contract', $request->query->all(), $extraFields);
        $search = $searchEntity;
        if((isset($search['contractTimeFrom']) && $search['contractTimeFrom'] !='')&& isset($search['contractTimeTo']) && $search['contractTimeTo'] !='' ){
            $search['contractDate']=[
                'from'=>$search['contractTimeFrom'],
                'to'=>$search['contractTimeTo']
            ];
        }
        if((isset($search['contractTimeFrom']) && $search['contractTimeFrom'] !='')&& isset($search['contractTimeTo']) && $search['contractTimeTo'] =='' ){
            $search['contractDate']=[
                'from'=>$search['contractTimeFrom']
            ];
        }
        if((isset($search['contractTimeFrom']) && $search['contractTimeFrom'] =='')&& isset($search['contractTimeTo']) && $search['contractTimeTo'] !='' ){
        $search['contractDate']=[
            'to'=>$search['contractTimeTo']
        ];
    }
        unset($search['contractTimeTo']);
        unset($search['contractTimeFrom']);
        $user=$this->getDoctrine()->getRepository("AppBundle:User")->find($this->getUser());
//        $this->dumpWithHeaders($user);
        $result = $this->getDoctrine()->getRepository('AppBundle:Contract')->findContracts($search,$user->getId());
        $response = $this->createApiResponse($result, 200, ['Default']);
        if (!$response) {
            throw $this->createNotFoundException(sprintf('Page Not Found'));
        }
        return $response;
    }



}