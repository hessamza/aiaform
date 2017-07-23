<?php
/**
 * Created by PhpStorm.
 * User: hessam
 * Date: 1/14/17
 * Time: 3:03 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Statistics;
use AppBundle\Form\StatisticsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StatisticsController extends  BaseController
{

    /**
     * @Security("is_granted(['ROLE_ADMIN','ROLE_FrontendUser'])")
     * @Method({"GET","POST"})
     * @Route("/statistics")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createStatistics(Request $request)
    {
        $objectEntity=new Statistics();
        $form = $this->createForm(StatisticsType::class, $objectEntity, [ "method" => 'POST']);
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
        return $this->render('page/statistics.html.twig', [
            'form' => $form->createView(),
            'formIsNotValid' => $form->isSubmitted() && !$form->isValid()
        ]);

    }

    /**
     * @Method({"GET","PATCH"})
     * @Route("/statistics/{id}")
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function ManageStatistics($id,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $findObject = $this->getDoctrine()->getRepository("AppBundle:Statistics")->find($id);
        $method=$request->getMethod();
        $form = $this->createForm(StatisticsType::class, $findObject,['method'=>'PATCH']);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if ($method=='PATCH') {
            if ($form->isSubmitted() && $form->isValid()) {
                $firm = $form->getData();
                $em->persist($firm);
                $em->flush();
                return $this->redirect('/');
            }
        }
        return $this->render('page/statistics.html.twig', [
            'form' => $form->createView(),
            'formIsNotValid' => $form->isSubmitted() && !$form->isValid()
        ]);

    }
    /**
     * @Route("/delete/statistics/{id}")
     * @Method("GET")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteContract($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Statistics')->find($id);
        if (!$entity) throw new NotFoundHttpException('Item does not found.');
        $em->remove($entity);
        $em->flush();
        return $this->redirect("/");

    }



}