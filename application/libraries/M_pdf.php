<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//http://www.w3school.info/2016/02/08/convert-html-to-pdf-in-codeigniter-using-mpdf/
class m_pdf {
    
    function m_pdf()
    {
        $CI = & get_instance();
        log_message('Debug', 'mPDF class is loaded.');
    }
 
    function load($param=NULL)
    {
        include_once APPPATH.'/third_party/mpdf/mpdf.php';
         
        if ($params == NULL)
        {
            $param = '"en-GB-x","A4","","",10,10,10,10,6,3';          		
        }
         
        //return new mPDF($param);
        return new mPDF();
    }
}