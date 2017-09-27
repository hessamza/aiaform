<?php
/**
 * Created by PhpStorm.
 * User: hessam
 * Date: 1/14/17
 * Time: 3:03 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\FileManager;
use AppBundle\Entity\Statistics;
use AppBundle\Form\StatisticsType;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Fill;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use TCPDF_FONTS;
if (!defined('_MPDF_TTFONTPATH')) {
    // an absolute path is preferred, trailing slash required:
//    $directoryPath = $this->container->getParameter('kernel.root_dir') . '/../web/bundles/mybundle/myfiles';
   define('_MPDF_TTFONTPATH', __DIR__.'/../../../app/Resources/views/report/font/');
    // example using Laravel's resource_path function:
    // define('_MPDF_TTFONTPATH', resource_path('fonts/'));
}
class ExcelController extends  BaseController
{


    /**
     * @Security("is_granted(['ROLE_ADMIN','ROLE_FrontendUser','ROLE_SECRETARY'])")
     * @Method("GET")
     * @Route("/report-excel")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function exceclContract()
    {
        return $this->render(':page:reportExcel.html.twig');

    }

    /**
     * @Route("/api/excel")
     * @Method("POST")
     * @param Request $request
     * @return string
     */
    public function returnPDFResponseFromHTML(Request $request)
    {

        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $search = $request->request->all();
        //$this->dumpWithHeaders($search);
        if((isset($search['contractTimeFrom']) && $search['contractTimeFrom'] !='')&& isset($search['contractTimeTo']) && $search['contractTimeTo'] !='' ){
            $search['createdAt']=[
                'from'=>$search['contractTimeFrom'],
                'to'=>$search['contractTimeTo']
            ];
        }
        if((isset($search['contractTimeFrom']) && $search['contractTimeFrom'] !='')&& isset($search['contractTimeTo']) && $search['contractTimeTo'] =='' ){
            $search['createdAt']=[
                'from'=>$search['contractTimeFrom']
            ];
        }
        if((isset($search['contractTimeFrom']) && $search['contractTimeFrom'] =='')&& isset($search['contractTimeTo']) && $search['contractTimeTo'] !='' ){
            $search['createdAt']=[
                'to'=>$search['contractTimeTo']
            ];
        }
        unset($search['contractTimeFrom']);
        unset($search['contractTimeTo']);
//        $this->dumpWithHeaders($search);
        $contracts=$this->getDoctrine()->getRepository("AppBundle:Contract")
                                                                                               ->findExcelContracts($search,1);
       // $this->dumpWithHeaders($contracts);
        $phpExcelObject->getProperties()->setCreator("liuggio")
            ->setLastModifiedBy("Giulio De Donato")
            ->setTitle("Office 2005 XLSX Test Document")
            ->setSubject("Office 2005 XLSX Test Document")
            ->setDescription("Test document for Office 2005 XLSX, generated using PHP classes.")
            ->setKeywords("office 2005 openxml php")
            ->setCategory("Test result file");
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1', ' نام کارشناس :رستگار')
            ->setCellValue('D1', 'گزارش واحد فروش')
            ->setCellValue('A2', 'نام شرکت ')
            ->setCellValue('B2', 'نام کاربری')
            ->setCellValue('C2', 'مدت زمان اشتراک')
            ->setCellValue('D2', 'نوع آگهی')
            ->setCellValue('E2', 'نوع خدمات')
            ->setCellValue('F2', 'سایر ابزارهای اطلاع رسانی')
            ->setCellValue('G2', 'محدوده اطلاع رسانی')
            ->setCellValue('H2', 'مبلغ قرارداد')
            ->setCellValue('I2', 'مبلغ تخفيف')
            ->setCellValue('J2', 'نوع قرارداد')
            ->setCellValue('K2', 'تاریخ اختصاص')
            ->setCellValue('L2', 'تاریخ قرارداد');
        if(count($contracts)>1) {

            for ($j = 0; $j < count($contracts); $j++) {
                $shareItem          = $this->getDoctrine()->getRepository(
                    "AppBundle:ShareItems"
                )->findAll();
                $anotherShare       = $shareItem;
                $contractShareItems = $contracts[$j]->getShareItems()
                                                    ->getValues();
                foreach ($anotherShare as $key => $new_val) {
                    if (isset($contractShareItems[$key])) // belongs to old array?
                    {
                        if ($contractShareItems[$key]->getName(
                            ) == $new_val->getName()) // has changed?
                        {
                            unset($anotherShare[$key]);
                        }
                    }
                }
                $share = '';
                foreach ($contracts[$j]->getShareItems() as $shareContract) {
                    $share .= '  '.$shareContract->getName();
                }
                $another = '';
                foreach ($anotherShare as $anotherIt) {
                    $another .= '  '.$anotherIt->getName();
                }

/*                advi Item*/

                $advItem=$this->getDoctrine()->getRepository("AppBundle:AdvItems")->findAll();
                $anotherAdv=$advItem;
                $contractAdvItems=$contracts[$j]->getAdvItems()->getValues();

                foreach ($anotherAdv as $key4 => $new_val4)
                {
                    foreach ($contractAdvItems as $contractAdvItem) {
                        if ( $contractAdvItem->getName() == $new_val4->getName()) // has changed?
                            unset($anotherAdv[$key4]);
                    }
                }
                $adv='';
                foreach ($contracts[$j]->getAdvItems() as $advContract) {
                    $adv .= '  '.$advContract->getName();
                }
                $anotheradvItem = '';
                foreach ($anotherAdv as $anotherad) {
                    $anotheradvItem .= '  '.$anotherad->getName();
                }

/*                    end adv Item           */


/*service Item*/


                $serviceItem=$this->getDoctrine()->getRepository("AppBundle:ServiceItems")->findAll();
                $anotherService=$serviceItem;
                $contractSerivecItems=$contracts[$j]->getServiceItems()->getValues();
                foreach ($anotherService as $key2 => $new_val2)
                {
                    foreach ($contractSerivecItems as $item) {
                        if ( $item->getName() == $new_val2->getName()) // has changed?
                            unset($anotherService[$key2]);
                    }

                }

                $service='';
                foreach ($contracts[$j]->getServiceItems() as $serviceItem) {
                    $service .= '  '.$serviceItem->getName();
                }
                $anotherSerivceItem = '';
                foreach ($anotherService as $anotherSer) {
                    $anotherSerivceItem .= '  '.$anotherSer->getName();
                }


/*end service Item*/
                $seprate='';
                if($contracts[$j]->getSeparate()){
                    switch ($contracts[$j]->getSeparate()){
                        case 'global':
                            $seprate='سراسری';
                            break;
                        case 'local':
                            $seprate='استانی';
                            break;
                        case 'professional':
                            $seprate='تخصصی';
                            break;
                        case 'local-professional':
                            $seprate='استانی تخصصی';
                            break;
                    }
                }
                if($contracts[$j]->getContractType()){
                    switch ($contracts[$j]->getContractType()){
                        case 'recharge':
                            $contractType='نمدید';
                            break;
                        case 'register':
                            $contractType='ثبت نام';
                            break;
                        case 'phone':
                            $contractType='تلفنی';
                            break;
                        case 'telegram':
                            $contractType='تلگرام';
                            break;
                        case 'direct':
                            $contractType='مستقیم';
                            break;
                        case 'exhibition':
                            $contractType='نمایشگاهی';
                            break;
                        case 'adv':
                            $contractType='تبلیغات';
                            break;
                    }
                }
                $contractTime=($contracts[$j]->getContractTime()=='6month')?'۶ ماه':'۱۲ ماه';

                $arrcreate=explode('-',$contracts[$j]->getCreatedAt()->format
                ('Y-m-d'));
                $arrdateCreate = \jDateTime::toJalali($arrcreate[0],
                    $arrcreate[1],
                    $arrcreate[2]);
                $dateCreate=$arrdateCreate[0].'-'.$arrdateCreate[1].'-'
                                                  .$arrdateCreate[2];
                if($contracts[$j]->getcontractDate()){
                    $arrcontract=explode('-',$contracts[$j]->getcontractDate()->format
                    ('Y-m-d'));
                    $arrdateContract = \jDateTime::toJalali($arrcontract[0],
                        $arrcontract[1],
                        $arrcontract[2]);
                    $dateContract=$arrdateContract[0].'-'.$arrdateContract[1].'-'
                                .$arrdateContract[2];
                }else{
                    $dateContract='';
                }

                $phpExcelObject->setActiveSheetIndex(0)
                               ->setCellValue(
                                   'A'.($j+3),
                                   $contracts[$j]->getCompanyName()
                               )
                               ->setCellValue(
                                   'B'.($j+3),
                                   $contracts[$j]->getuserName()
                               )
                               ->setCellValue('C'.($j+3), $contractTime)
                               ->setCellValue('D'.($j+3), $adv)
                               ->setCellValue('E'.($j+3), $share)
                               ->setCellValue('F'.($j+3), $service)
                               ->setCellValue('G'.($j+3), $seprate)
                               ->setCellValue(
                                   'H'.($j+3),
                                   $contracts[$j]->getcontractPrice()
                               )
                               ->setCellValue(
                                   'I'.($j+3),
                                   $contracts[$j]->getDiscount()
                               )
                               ->setCellValue(
                                   'J'.($j+3),
                                   $contractType
                               )
                               ->setCellValue(
                                   'K'.($j+3),
                                   $dateCreate
                               )
                               ->setCellValue(
                                   'L'.($j+3),
                                   $dateContract
                               );
            }
        }else if(count($contracts)==1){
            $shareItem          = $this->getDoctrine()->getRepository(
                "AppBundle:ShareItems"
            )->findAll();
            $anotherShare       = $shareItem;
            $contractShareItems = $contracts->getShareItems()
                                                ->getValues();
         //   $this->dumpWithHeaders($contracts->getShareString());
            $shareBase='';
            if($contractShareItems){
                foreach ($anotherShare as $key => $new_val) {
                    if (isset($contractShareItems[$key])) // belongs to old array?
                    {
                        if ($contractShareItems[$key]->getName(
                            ) == $new_val->getName()) // has changed?
                        {
                            unset($anotherShare[$key]);
                        }
                    }
                }
                $share = '';
                foreach ($contracts->getShareItems() as $shareContract) {
                    $share .= '  '.$shareContract->getName();
                }
                $another = '';
                foreach ($anotherShare as $anotherItem) {
                    $another .= '  '.$anotherItem->getName();
                }
                $shareBase=$share.'  '.$share;
            }
            else{
                $shareBase=$contracts->getShareString();
            }
            $seprate='';


            $advItem=$this->getDoctrine()->getRepository("AppBundle:AdvItems")->findAll();
            $anotherAdv=$advItem;
            $contractAdvItems=$contracts->getAdvItems()->getValues();

            foreach ($anotherAdv as $key4 => $new_val4)
            {
                foreach ($contractAdvItems as $contractAdvItem) {
                    if ( $contractAdvItem->getName() == $new_val4->getName()) // has changed?
                        unset($anotherAdv[$key4]);
                }
            }
            $adv='';
            foreach ($contracts->getAdvItems() as $advContract) {
                $adv .= '  '.$advContract->getName();
            }
            $anotheradvItem = '';
            foreach ($anotherAdv as $anotherad) {
                $anotheradvItem .= '  '.$anotherad->getName();
            }


            $serviceItem=$this->getDoctrine()->getRepository("AppBundle:ServiceItems")->findAll();
            $anotherService=$serviceItem;
            $contractSerivecItems=$contracts->getServiceItems()->getValues();
            foreach ($anotherService as $key2 => $new_val2)
            {
                foreach ($contractSerivecItems as $item) {
                    if ( $item->getName() == $new_val2->getName()) // has changed?
                        unset($anotherService[$key2]);
                }

            }

            $service='';
            foreach ($contracts->getServiceItems() as $serviceItem) {
                $service .= '  '.$serviceItem->getName();
            }
            $anotherSerivceItem = '';
            foreach ($anotherService as $anotherSer) {
                $anotherSerivceItem .= '  '.$anotherSer->getName();
            }




            if($contracts->getSeparate()){
                switch ($contracts->getSeparate()){
                    case 'global':
                        $seprate='سراسری';
                        break;
                    case 'local':
                        $seprate='استانی';
                        break;
                    case 'professional':
                        $seprate='تخصصی';
                        break;
                    case 'local-professional':
                        $seprate='استانی تخصصی';
                        break;
                }
            }
            if($contracts->getContractType()){
                switch ($contracts->getContractType()){
                    case 'recharge':
                        $contractType='نمدید';
                        break;
                    case 'register':
                        $contractType='ثبت نام';
                        break;
                    case 'phone':
                        $contractType='تلفنی';
                        break;
                    case 'telegram':
                        $contractType='تلگرام';
                        break;
                    case 'direct':
                        $contractType='مستقیم';
                        break;
                    case 'exhibition':
                        $contractType='نمایشگاهی';
                        break;
                    case 'adv':
                        $contractType='تبلیغات';
                        break;
                }
            }
            $contractTime=($contracts->getContractTime()=='6month')?'۶ ماه':'۱۲ ماه';

            $arrcreate=explode('-',$contracts->getCreatedAt()->format
            ('Y-m-d'));
            $arrdateCreate = \jDateTime::toJalali($arrcreate[0],
                $arrcreate[1],
                $arrcreate[2]);
            $dateCreate=$arrdateCreate[0].'-'.$arrdateCreate[1].'-'
                        .$arrdateCreate[2];
            if($contracts->getcontractDate()){
                $arrcontract=explode('-',$contracts->getcontractDate()->format
                ('Y-m-d'));
                $arrdateContract = \jDateTime::toJalali($arrcontract[0],
                    $arrcontract[1],
                    $arrcontract[2]);
                $dateContract=$arrdateContract[0].'-'.$arrdateContract[1].'-'
                              .$arrdateContract[2];
            }else{
                $dateContract='';
            }

            //           $this->dumpWithHeaders($contracts);
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue(
                    'A'.(3),
                    $contracts->getCompanyName()
                )
                ->setCellValue(
                    'B'.(3),
                    $contracts->getuserName()
                )
                ->setCellValue('C'.(3), $contractTime)
                ->setCellValue('D'.(3), $adv)
                ->setCellValue('E'.(3), $share)
                ->setCellValue('F'.(3), $service)
                ->setCellValue('G'.(3), $seprate)
                ->setCellValue(
                    'H'.(3),
                    $contracts->getcontractPrice()
                )
                ->setCellValue(
                    'I'.(3),
                    $contracts->getDiscount()
                )
                ->setCellValue(
                    'J'.(3),
                    $contractType
                )
                ->setCellValue(
                    'K'.(3),
                    $dateCreate
                )
                ->setCellValue(
                    'L'.(3),
                    $dateContract
                );
        }
        $phpExcelObject->getActiveSheet()->setRightToLeft(true);
        $phpExcelObject->getActiveSheet()->getColumnDimension('A')->setWidth("44");
        $phpExcelObject->getActiveSheet()->getColumnDimension('B')->setWidth("35");
        $phpExcelObject->getActiveSheet()->getColumnDimension('C')->setWidth("35");
        $phpExcelObject->getActiveSheet()->getColumnDimension('D')->setWidth("15");
        $phpExcelObject->getActiveSheet()->getColumnDimension('E')->setWidth("15");
        $phpExcelObject->getActiveSheet()->getColumnDimension('F')->setWidth("35");
        $phpExcelObject->getActiveSheet()->getColumnDimension('G')->setWidth("35");
        $phpExcelObject->getActiveSheet()->getColumnDimension('H')->setWidth("35");
        $phpExcelObject->getActiveSheet()->getStyle('B1:L1')->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'e7e6e6')
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                ),
                'font' => array(
                    'color' => array('rgb' => '000000'),
                    'size' => 48,
                    'name' => 'B Nazanin'
                )
            )
        );
        $phpExcelObject->getActiveSheet()->getStyle('A2:L2')->applyFromArray(
            array(
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                ),
                'font' => array(
                    'color' => array('rgb' => '000000'),
                    'size' => 16,
                    'name' => 'B Nazanin'
                )
            )
        );
        $phpExcelObject->getActiveSheet()->getStyle('A1')->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'e7e6e6')
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                ),
                'font' => array(
                    'color' => array('rgb' => '000000'),
                    'size' => 11,
                    'name' => 'B Nazanin'
                )
            )
        );
        for($i=0;$i<3;$i++){
            $phpExcelObject->getActiveSheet()->getRowDimension($i)->setRowHeight(70);
            if($i==2){
                $phpExcelObject->getActiveSheet()->getRowDimension($i)->setRowHeight(25);
            }
        }

        $phpExcelObject->getActiveSheet()->setTitle('Simple');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);
        $folderName = md5($this->generateRandomString() . '_' . $this->getUser()->getId() . '_excel' );
        !is_dir($this->get('kernel')->getRootDir() . "/../web/uploads/excel") ? mkdir($this->get('kernel')->getRootDir() . "/../web/uploads/excel/candidate") : null;
        !is_dir($this->get('kernel')->getRootDir() . "/../web/uploads/excel/$folderName") ? mkdir($this->get('kernel')->getRootDir() . "/../web/uploads/excel/$folderName") : null;
        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $file=$this->get('kernel')->getRootDir() . "/../web/uploads/excel/$folderName/" . 'excel.xls';
        $writer->save($file);
        $em = $this->getDoctrine()->getManager();
        $report = new FileManager();
        $report->setOwner($this->getUser());
        $report->setPath("/uploads/excel/$folderName");
        $report->setStatus(1);
        $report->setName('excel.xls');
        $em->persist($report);
        $em->flush();
        return $this->createApiResponse($report->getPath() . '/' . $report->getName());
    }
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}