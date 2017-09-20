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

class ItemsController extends  BaseController
{

    /**
     * @Security("is_granted(['ROLE_SECRETARY','ROLE_ADMIN'])")
     * @Route("/items",path="item_list")
     * @Method({"GET"})
     * @return Response
     */
    public function searchItems(){
        return $this->render('page/showItems.html.twig');
    }

    /**
     * @Security("is_granted(['ROLE_SECRETARY','ROLE_ADMIN'])")
     * @Route("/items/list",name="get_item_list")
     * @Method("GET")
     * @param Request $request
     * @return Response
     */
    public function getItemsList(Request $request)
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
//        $this->dumpWithHeaders($search);
        $result = $this->getDoctrine()->getRepository('AppBundle:Contract')->findItemContracts($search,$user->getId());
        $response = $this->createApiResponse($result, 200, ['items']);
        if (!$response) {
            throw $this->createNotFoundException(sprintf('Page Not Found'));
        }
        return $response;
    }


    /**
     * @Security("is_granted(['ROLE_SECRETARY','ROLE_ADMIN'])")
     * @Method("POST")
     * @Route("/listItem/{id}")
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function ManageItems($id,Request $request)
    {
        //   $date = \jDateTime::toGregorian('Y/m/d', 'Y-m-d H:i:s', $date->format('Y-m-d H:i:s'));
        // $this->dumpWithHeaders($request->request->all());
        $em = $this->getDoctrine()->getManager();
        $findObject = $this->getDoctrine()->getRepository("AppBundle:Contract")->find($id);
        $findObject->setItemDescriptionSec($request->request->get('itemDescriptionSec'));
        $em->persist($findObject);
        $em->flush();
        $response = $this->createApiResponse(['success'], 200, ['Default']);
        return $response;
    }

    /**
     * @Security("is_granted(['ROLE_SECRETARY','ROLE_ADMIN'])")
     * @Method("POST")
     * @Route("/listItem/send/{id}")
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function ManageSendItems($id,Request $request)
    {
        //   $date = \jDateTime::toGregorian('Y/m/d', 'Y-m-d H:i:s', $date->format('Y-m-d H:i:s'));
        // $this->dumpWithHeaders($request->request->all());
        $em = $this->getDoctrine()->getManager();
        $findObject = $this->getDoctrine()->getRepository("AppBundle:Contract")->find($id);
        $findObject->setItemSend($request->request->get('itemSend'));
        $em->persist($findObject);
        $em->flush();
        $response = $this->createApiResponse(['success'], 200, ['Default']);
        return $response;
    }


}