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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use TCPDF_FONTS;
if (!defined('_MPDF_TTFONTPATH')) {
    // an absolute path is preferred, trailing slash required:
//    $directoryPath = $this->container->getParameter('kernel.root_dir') . '/../web/bundles/mybundle/myfiles';
   define('_MPDF_TTFONTPATH', __DIR__.'/../../../app/Resources/views/report/font/');
    // example using Laravel's resource_path function:
    // define('_MPDF_TTFONTPATH', resource_path('fonts/'));
}
class ReportController extends  BaseController
{
    /**
     * @Route("/api/preFactor/{id}")
     * @Method("GET")
     * @return string
     */
    public function returnPDFResponseFromHTML($id)
    {
//echo realpath(__DIR__.'/../../../app/Resources/views/report/font/');die;


        $contract=$this->getDoctrine()->getRepository("AppBundle:Contract")->find($id);
        $advItem=$this->getDoctrine()->getRepository("AppBundle:AdvItems")->findAll();
        $anotherAdv=$advItem;
        $contractAdvItems=$contract->getAdvItems()->getValues();

        foreach ($anotherAdv as $key4 => $new_val4)
        {
            foreach ($contractAdvItems as $contractAdvItem) {
                if ( $contractAdvItem->getName() == $new_val4->getName()) // has changed?
                    unset($anotherAdv[$key4]);
            }
         }

        $shareItem=$this->getDoctrine()->getRepository("AppBundle:ShareItems")->findAll();
        $anotherShare=$shareItem;
        $contractShareItems=$contract->getShareItems()->getValues();
        foreach ($anotherShare as $key => $new_val)
        {
            if (isset( $contractShareItems[$key])) // belongs to old array?
            {
                if ( $contractShareItems[$key]->getName() == $new_val->getName()) // has changed?
                    unset($anotherShare[$key]);
            }
        }
        $serviceItem=$this->getDoctrine()->getRepository("AppBundle:ServiceItems")->findAll();
        $anotherService=$serviceItem;
        $contractSerivecItems=$contract->getServiceItems()->getValues();
        foreach ($anotherService as $key2 => $new_val2)
        {
            foreach ($contractSerivecItems as $item) {
                if ( $item->getName() == $new_val2->getName()) // has changed?
                    unset($anotherService[$key2]);
            }

        }
//$this->dumpWithHeaders($anotherService);
        $html=$this->renderView("report/preFactor.html.twig",[
            'contract'=>$contract,
            'anotherAdv'=>$anotherAdv,
            'anothershare'=>$anotherShare,
            'anotherService'=>$anotherService
        ]);
        $user=$this->getDoctrine()->getRepository("AppBundle:User")->find($this->getUser());
        $html=iconv("utf-8","UTF-8//IGNORE",$html);
        $MPDF = new \mPDF('utf-8');
//        $custom_fontdata = array(
//            'centurygothic' => array(
//                'R' => "../../../../app/Resources/views/report/font/CenturyGothic.ttf",
//                'B' => "../../../../app/Resources/views/report/font/CenturyGothic-Bold.ttf",
//                'I' => "../../../../app/Resources/views/report/font/CenturyGothic-Italic.ttf",
//                'BI' => "../../../../app/Resources/views/report/font/CenturyGothic-BoldItalic.ttf"
//                // use 'R' to support CSS font-weight: normal
//                // use 'B', 'I', 'BI' and etc. to support CSS font-weight: bold, font-style: italic, and both...
//            )
//        );
        $folderName = md5($this->generateRandomString() . '_' . $this->getUser()->getId() . '_preFactor' );

        !is_dir($this->get('kernel')->getRootDir() . "/../web/uploads/report") ? mkdir($this->get('kernel')->getRootDir() . "/../web/uploads/report/candidate") : null;
        !is_dir($this->get('kernel')->getRootDir() . "/../web/uploads/report/$folderName") ? mkdir($this->get('kernel')->getRootDir() . "/../web/uploads/report/$folderName") : null;
       //$this->add_custom_fonts_to_mpdf($MPDF);
//        $this->add_custom_font_to_mpdf($MPDF, $custom_fontdata);
        //        $MPDF->shrink_tables_to_fit=0;
        $MPDF->WriteHTML($html);
        $this->get('kernel')->getRootDir();
        $MPDF->debug = true;
        $MPDF->SetDirectionality('rtl');
        $MPDF->useSubstitutions = true;
        $MPDF->autoScriptToLang = true;
        $MPDF->autoLangToFont = true;
        $MPDF->allow_charset_conversion = false;
        $MPDF->Output($this->get('kernel')->getRootDir() . "/../web/uploads/report/$folderName/" . 'preFactor.pdf', 'F');
        $em = $this->getDoctrine()->getManager();
        $report = new FileManager();
        $report->setOwner($this->getUser());
        $report->setPath("/uploads/report/$folderName");
        $report->setStatus(1);
        $report->setName('preFactor.pdf');
        $user->setReport($report);
        $em->persist($user);
        $em->persist($report);
        $em->flush();
        return $this->createApiResponse($report->getPath() . '/' . $report->getName());
    }
    function add_custom_fonts_to_mpdf($mpdf) {

        $fontdata = [
            'sourcesanspro' => [
                'R' => 'Yekan',
            ],
        ];

        foreach ($fontdata as $f => $fs) {
            // add to fontdata array
            $mpdf->fontdata[$f] = $fs;

            // add to available fonts array
            foreach (['R', 'B', 'I', 'BI'] as $style) {
                if (isset($fs[$style]) && $fs[$style]) {
                    // warning: no suffix for regular style! hours wasted: 2
                    $mpdf->available_unifonts[] = $f . trim($style, 'R');
                }
            }

        }

        $mpdf->default_available_fonts = $mpdf->available_unifonts;
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

    public function antherModulePdf()
    {

        $typePage = 'P';
        $w = 250;
        $h = 297;
        $yHeight = 5;
        $marginTop = 5;
        $fontname = TCPDF_FONTS::addTTFfont($this->get("kernel")->getRootDir() . "/Resources/views/report/font/HiwebNazanin.ttf", 'TrueTypeUnicode', '', 12);


        $pdf = $this->get("white_october.tcpdf")->create('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      //  $pdf->addTTFfont($this->get("kernel")->getRootDir() . "/Resources/views/report/font/HiwebNazanin", 'TrueTypeUnicode', '', 12);
        $pdf->AddFont($fontname);
        //  $pdf->changeTheDefault(false);
        $pdf->SetAuthor('cvinspe.nl');
        $pdf->SetTitle(('report module'));
        $pdf->SetSubject('report module');
        $lg = Array();
        $lg['a_meta_charset'] = 'UTF-8';
        $lg['a_meta_dir'] = 'rtl';
        $lg['a_meta_language'] = 'fa';
        $lg['w_page'] = 'page';
        $pdf->SetFont('dejavusans', '', 12);
        $pdf->setRTL(true);
        $contract=$this->getDoctrine()->getRepository("AppBundle:Contract")->find(20);
        $html=$this->renderView("report/preFactor.html.twig",[
            'contract'=>$contract
        ]);
        $pdf->AddPage('P', 'A4');

        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = $yHeight, $html);
        $folderName = md5($this->generateRandomString() . '_' . $this->getUser()->getId() . '_preFactor' );

        !is_dir($this->get('kernel')->getRootDir() . "/../web/uploads/report") ? mkdir($this->get('kernel')->getRootDir() . "/../web/uploads/report/candidate") : null;
        !is_dir($this->get('kernel')->getRootDir() . "/../web/uploads/report/$folderName") ? mkdir($this->get('kernel')->getRootDir() . "/../web/uploads/report/$folderName") : null;
        $this->get('kernel')->getRootDir();

        $pdf->Output($this->get('kernel')->getRootDir() . "/../web/uploads/report/$folderName/" . 'preFactor.pdf', 'F');
        $em = $this->getDoctrine()->getManager();
        $report = new FileManager();
        $report->setOwner($this->getUser());
        $report->setPath("/uploads/report/$folderName");
        $report->setStatus(1);
        $report->setName('preFactor.pdf');
        $user=$this->getDoctrine()->getRepository("AppBundle:User")->find($this->getUser());
        $user->setReport($report);
        $em->persist($user);
        $em->persist($report);
        $em->flush();
        return $this->createApiResponse($report->getPath() . '/' . $report->getName());

    }
}