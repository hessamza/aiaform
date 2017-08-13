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
     * @Security("is_granted(['ROLE_SECRETARY'])")
     * @Route("/items",path="item_list")
     * @Method({"GET"})
     * @return Response
     */
    public function searchItems(){
        return $this->render('page/showItems.html.twig');
    }

    /**
     * @Security("is_granted(['ROLE_SECRETARY'])")
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



}