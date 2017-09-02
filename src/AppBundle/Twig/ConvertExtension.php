<?php
namespace AppBundle\Twig;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Intl\DateFormatter\IntlDateFormatter;
use Symfony\Component\Translation\IdentityTranslator;


class ConvertExtension extends \Twig_Extension
{
    /**
     * @var KernelInterface
     */
    private $kernel;
    /**
     * @var IdentityTranslator
     */
    private $translator;

    /**
     * ConvertExtension constructor.
     * @param KernelInterface $kernel
     * @param $translator
     */
    public function __construct(KernelInterface $kernel,$translator)
    {
        $this->kernel = $kernel;
        $this->translator=$translator;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('binary', array($this, 'binaryFilter')),
            new \Twig_SimpleFilter('dutchDate', array($this, 'dutchDateFilter')),
            new \Twig_SimpleFilter('jDate', array($this, 'jDate')),
            new \Twig_SimpleFilter('jDatePdf', array($this, 'jDatePdf')),
            new \Twig_SimpleFilter('alphabeticOrder', array($this, 'alphabeticOrderFilter')),
            new \Twig_SimpleFilter('splitString', array($this, 'splitStringFilter')),
            new \Twig_SimpleFilter('changeToPersian', array($this, 'changeToPersian')),
            new \Twig_SimpleFilter('numberFormat', array($this, 'numberFormat')),
        );
    }

    public function changeToPersian($string){
        $western_arabic = array('0','1','2','3','4','5','6','7','8','9');
        $eastern_arabic = array('٠','١','٢','٣','٤','٥','٦','٧','٨','٩');
        $str = str_replace($western_arabic, $eastern_arabic, $string);
        return $str;
    }
    public function numberFormat($string){
        $number = number_format((float) $string,0,null,'/');
        $western_arabic = array('0','1','2','3','4','5','6','7','8','9');
        $eastern_arabic = array('٠','١','٢','٣','٤','٥','٦','٧','٨','٩');
        $str = str_replace($western_arabic, $eastern_arabic,(string) $number);
        return $str;
    }

    public function binaryFilter($file)
    {

        $fileName = realpath($this->kernel->getRootDir() . "/../web" . $file);
        $contents = file_get_contents($fileName);
        $base64 = base64_encode($contents);
        return ('data:' . mime_content_type($fileName) . ';base64,' . $base64);
    } 
    public function dutchDateFilter($date)
    {
        $fmt = datefmt_create(
            'nl_NL',
            IntlDateFormatter::GREGORIAN,
            IntlDateFormatter::NONE
        );
        return datefmt_format($fmt,time());
    }
    public function jDate($date)
    {
        $date=new  \jDateTime(true, true, 'Asia/Tehran');
        return $date->date("l j F Y");
    }
    public function jDatePDF($date)
    {

        $date = \jDateTime::convertFormatToFormat('Y/m/d', 'Y-m-d H:i:s', $date->format('Y-m-d H:i:s'));

        return $date;
    }
    public function alphabeticOrderFilter($array)
    {

        $arrayTrans=[];
        foreach ($array as $item) {

            $arrayTrans[]=$this->translator->trans($item);
        }
        sort($arrayTrans);
        return $arrayTrans;
    }
    public function splitStringFilter($string,$value){
        $string = wordwrap($string, $value, ";;", true);
        return explode(";;", $string);
        $array=[];
        $stringArray=preg_split("/\\r\\n|\\r|\\n/", $string);
        foreach ($stringArray as $item) {
            if(strlen($item)>$value){
                $string = wordwrap($item, $value, ";;", true);
                $ssArray=explode(";;", $string);
                foreach ($ssArray as $item2) {
                    $array[]=$item2;
                }
            }
            else{
                if($item!=''){
                    $array[]=$item;
                }
            }
        }
        return $array;
    }

    public function getName()
    {
        return 'convert_extension';
    }
}