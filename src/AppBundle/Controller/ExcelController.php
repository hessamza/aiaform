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
        $contracts=$this->getDoctrine()->getRepository("AppBundle:Contract")->findExcelContracts($search,2);
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
            ->setCellValue('C2', 'نوع خدمات')
            ->setCellValue('D2', 'مبلغ قرارداد')
            ->setCellValue('E2', 'مبلغ تخفيف')
            ->setCellValue('F2', 'نوع قرارداد')
            ->setCellValue('G2', 'تاریخ اختصاص')
            ->setCellValue('H2', 'تاریخ قرارداد');
            for($j=3;$j<count($contracts);$j++){
                $shareItem=$this->getDoctrine()->getRepository("AppBundle:ShareItems")->findAll();
                $anotherShare=$shareItem;
                $contractShareItems=$contracts[$j]->getShareItems()->getValues();
                foreach ($anotherShare as $key => $new_val)
                {
                    if (isset( $contractShareItems[$key])) // belongs to old array?
                    {
                        if ( $contractShareItems[$key]->getName() == $new_val->getName()) // has changed?
                            unset($anotherShare[$key]);
                    }
                }
                $share='';
                foreach ($contracts[$j]->getShareItems() as $shareContract) {
                    $share.='  '.$shareContract->getName();
                }
                $another='';
                foreach ($anotherShare as $another) {
                    $another.='  '.$shareContract->getName();
                }
//                $this->dumpWithHeaders($share);
                $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A'.$j,$contracts[$j]->getCompanyName() )
                ->setCellValue('B'.$j,$contracts[$j]->getuserName() )
                ->setCellValue('C'.$j,$share.$another )
                ->setCellValue('D'.$j,$contracts[$j]->getcontractPrice() )
                ->setCellValue('E'.$j,$contracts[$j]->getDiscount() )
                ->setCellValue('F'.$j,$contracts[$j]->getContractType() )
                ->setCellValue('G'.$j,$contracts[$j]->getCreatedAt() )
                ->setCellValue('G'.$j,$contracts[$j]->getcontractDate() );
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
        $phpExcelObject->getActiveSheet()->getStyle('B1:H1')->applyFromArray(
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
        $phpExcelObject->getActiveSheet()->getStyle('A2:H2')->applyFromArray(
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