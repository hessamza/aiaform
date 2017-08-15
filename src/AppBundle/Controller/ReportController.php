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

class ReportController extends  BaseController
{
    /**
     * @Route("/api/preFactor")
     * @Method("GET")
     * @return string
     */
    public function returnPDFResponseFromHTML()
    {
        $contract=$this->getDoctrine()->getRepository("AppBundle:Contract")->find(20);
        $html=$this->renderView("report/preFactor.html.twig",[
            'contract'=>$contract
        ]);
        $user=$this->getDoctrine()->getRepository("AppBundle:User")->find($this->getUser());
        $MPDF = new \mPDF('fa');
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
//        $this->add_custom_font_to_mpdf($MPDF, $custom_fontdata);
        //        $MPDF->shrink_tables_to_fit=0;
        $MPDF->WriteHTML($html);
        $this->get('kernel')->getRootDir();
        $MPDF->debug = true;
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
    public function add_custom_font_to_mpdf($mpdf, $fonts_list)
    {
        // Logic from line 1146 mpdf.pdf - $this->available_unifonts = array()...
        foreach ($fonts_list as $f => $fs) {
            // add to fontdata array
            $mpdf->fontdata[$f] = $fs;

            // add to available fonts array
            if (isset($fs['R']) && $fs['R']) {
                $mpdf->available_unifonts[] = $f;
            }
            if (isset($fs['B']) && $fs['B']) {
                $mpdf->available_unifonts[] = $f . 'B';
            }
            if (isset($fs['I']) && $fs['I']) {
                $mpdf->available_unifonts[] = $f . 'I';
            }
            if (isset($fs['BI']) && $fs['BI']) {
                $mpdf->available_unifonts[] = $f . 'BI';
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


}