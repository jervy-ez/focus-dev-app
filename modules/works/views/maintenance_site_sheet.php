<?php
foreach ($project_t->result_array() as $row){
  $client_id = $row['company_id'];
  $compname = str_replace("&apos;", "'", $row['company_name']);
  $company_name = str_replace(' ', '', $compname);
  $company_name = str_replace('/', '', $company_name);
  $filepath = $_SERVER['DOCUMENT_ROOT']."/reports/".$client_id."_".$company_name."/".$proj_id."/MSS/";
}
//============================================================+
// File name   : example_054.php
// Begin       : 2009-09-07
// Last Update : 2013-05-14
//
// Description : Example 054 for TCPDF class
//               XHTML Forms
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: XHTML Forms
 * @author Nicola Asuni
 * @since 2009-09-07
 */

// Include the main TCPDF library (search for installation path).
//require_once('tcpdf_include.php');
require_once('tcpdf/tcpdf.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    function SetLogo($logo,$comp_name,$tel_no,$po_box,$acn,$focus_suburb,$focus_abn,$focus_email,$proj_id,$proj_name,$compname,$work_company_name,$user_name) 
    { 
      $this->logo = $logo;  
      $this->comp_name = $comp_name; 
      $this->tel_no = $tel_no; 
      $this->po_box = $po_box;
      $this->acn = $acn;
      $this->focus_abn = $focus_abn;
      //$this->abn = $abn;
      $this->focus_suburb = $focus_suburb;
      $this->focus_email = $focus_email;
      $this->proj_id = $proj_id;
      $this->proj_name = $proj_name;
      $this->compname = $compname;
      $this->work_company_name = $work_company_name;
      $this->user_name = $user_name;
      //$this->work_company_name = $work_company_name;
      //$this->cqr_notes_insurance = $cqr_notes_insurance;
      //$this->insurance_stat = $insurance_stat;
    } 
    //Page header
    public function Header() {
        $this->Image('./uploads/misc/'.$this->logo,15,11,70);
        //Arial bold 15
        $this->SetFont('helvetica', '', 10, '', false);
        //Move to the right
        
        //Title
        $this->Ln(10);
        $this->Cell(80, 20);
        $this->Cell(50,5,$this->comp_name,0,0,'');
        $this->Cell(50,5,'Tel:    '.$this->tel_no,0,0);
        $this->Ln();
        $this->Cell(80, 20);
        $this->Cell(50,5,$this->po_box ,0,0);
        $this->Cell(50,5,'ACN: '.$this->acn,0,0);
        
        $this->Ln();
        $this->Cell(80, 20);
        $this->Cell(50,5,$this->focus_suburb,0,0);
        $this->Cell(50,5,'ABN:  '.$this->focus_abn,0,0);

        $this->Ln(10);
        $this->Cell(80, 20);
        $this->Cell(50,5,'E-mail : maintenance@focusshopfit.com.au',0,0);

        $this->Ln(6);
        $this->Cell(0, 15,'',1);

        $this->Ln(-1);
        $this->SetFont('helvetica','',12);
        $this->Cell(5);
        $this->Cell(15,10,'Client:',0,0);
        $varlength = strlen($this->compname);
        if($varlength < 50){
            $this->Cell(105,10,$this->compname,0,0);
            $this->SetFont('helvetica','B',14);
            $this->Cell(50,10,'Maintenance Site Sheet',0,0);
        }else{
            $this->SetFont('helvetica','',9);
            $this->Ln(1);
            $this->Cell(20);
            $this->MultiCell(100,4,$this->compname);
            $this->Cell(125,0);
            $this->SetFont('helvetica','B',14);
            $this->Cell(50,-5,'Maintenance Site Sheet',0,0);
            $this->Cell(0,3);
        }
        
       
        $this->Ln();
        $this->SetFont('helvetica','',12);

        $this->Ln(-2);
        $this->Cell(5);
        $this->Cell(150,2,'Subcontractor: '.$this->work_company_name,0,0);
        $this->SetFont('helvetica','',10);
        $this->Cell(50,2,'Project#: '.$this->proj_id,0,0);
        $this->Ln(10);
       
        $style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));

        $this->Line(8, 53, 202, 53, $style);
        // $this->SetFillColor(139,137,137);
        // $this->Cell(0,0,'',1,0,'',true);
        // $this->Ln();
    }

    // Page footer
    public function Footer() {
        global $date; 
        $date = date('d M Y');
        //Position at 1.5 cm from bottom
        $this->SetY(-20);
        //Arial italic 8
        $style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));

        $this->Line(8, 283, 202, 283, $style);
        
        //Page number
        $this->Ln(1);

        $this->SetFont("times","",11); 
        $this->Cell(-40,5,"Issued by: ".$this->user_name, 0, 1, "R");
        $this->Ln(-1);
        //$this->SetFont('Arial','I',8);
        $this->SetTextColor(0,0,0);
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        $this->SetFont("times","",11); 
        $this->Cell(-40, 10, "Date Printed: ".$date, 0, 1, "R", 0); 
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetLogo($focus_logo,$focus_comp,$office_no,$po_box,$acn,$focus_suburb,$focus_abn,$focus_email,$proj_id,$proj_name,$compname,$work_company_name,$user_name);

//$pdf->setHeaderData($ln='', $lw=0, $ht='', $hs='<table cellspacing="0" cellpadding="1" border="1">tr><td rowspan="3">test</td><td>test</td></tr></table>', $tc=array(0,0,0), $lc=array(0,0,0));

// create new PDF document
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);



// // set document information
// $pdf->SetCreator(PDF_CREATOR);
// $pdf->SetAuthor('Nicola Asuni');
// $pdf->SetTitle('TCPDF Example 054');
// $pdf->SetSubject('TCPDF Tutorial');
// $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// $focus_logo = $_SERVER['DOCUMENT_ROOT'].$focus_logo;
// // set default header data
// $pdf->SetHeaderData($focus_logo, '200', 'PDF_HEADER_TITLE'.' 054', PDF_HEADER_STRING);

// // set header and footer fonts
// $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
// $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// // set default monospaced font
// $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// // set margins
// $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
// $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
// $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// // set auto page breaks
// $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// // set image scale factor
// $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// // set some language-dependent strings (optional)
// if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
//     require_once(dirname(__FILE__).'/lang/eng.php');
//     $pdf->setLanguageArray($l);
// }

// ---------------------------------------------------------

// IMPORTANT: disable font subsetting to allow users editing the document
$pdf->setFontSubsetting(false);

// set font
$pdf->SetFont('helvetica', '', 10, '', false);

// add a page
$pdf->AddPage();

$pdf->Ln(48);
$pdf->Cell(190,133,'',1,0,'C'); // border

$pdf->SetFont('helvetica','B',11);

$pdf->Ln(-4);
$pdf->SetFillColor(255,255,255);
$pdf->Cell(2);
$pdf->Cell(30,8,'Site Location',0,0,'',true);
$pdf->Ln(7);
$pdf->Cell(2);
$pdf->Cell(33,5,'Contact Person: ',0,0);
$pdf->Cell(153,5,$site_contact_person,1,0);

$pdf->Ln(6);
$pdf->Cell(2);
$pdf->Cell(73,5,'Location: ',0,0);

$pdf->SetFont('helvetica','B',11);
$pdf->Cell(21,5,'Contacts: ',0,0);
$pdf->SetFont('helvetica','',11);
$pdf->Cell(35,5,$comp_office_number,1,0);

$pdf->Cell(5);
$pdf->SetFont('helvetica','B',11);
$pdf->Cell(17,5,'Mobile: ',0,0);
$pdf->SetFont('helvetica','',11);
$pdf->Cell(35,5,$mobile_number,1,0);

$pdf->Ln(6);
$pdf->Cell(3);
$pdf->SetFont('helvetica','',11);
$pdf->SetTextColor(255, 0, 0);
$pdf->Cell(185,5,$site_address_1st.', '.$site_address_2nd.', '.$site_address_3rd,1,0);

$pdf->SetTextColor(0, 0, 0);
$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
$pdf->Line(10, 79, 200, 79, $style);

$pdf->SetFont('helvetica','B',11);
$pdf->Ln(5);
$pdf->Cell(2);
$pdf->Cell(30,10,'Description of works to be carried out: ',0,0);

$pdf->SetFont('helvetica','',11);
$pdf->Ln(8);
$pdf->Cell(3);
$pdf->Cell(185,70,'',1,0,'C'); // border

$pdf->Ln(1);
$pdf->Cell(4);
$pdf->TextField('notes', 178, 67, array('multiline'=>true, 'lineWidth'=>0, 'borderStyle'=>'none', 'readonly'=>true), array('v'=>$notes));


$pdf->Ln(68);
$pdf->Cell(2);
$pdf->SetFont('helvetica','B',11);
$pdf->Cell(30,10,'Comments & Material Used: ',0,0);

$pdf->Ln(8);
$pdf->Cell(3);
$pdf->Cell(185,25,'',1,0,'C'); // border

$pdf->Ln(1);
$pdf->Cell(4);
$pdf->TextField('material_used', 183, 22, array('multiline'=>true, 'lineWidth'=>0, 'borderStyle'=>'none'));

$pdf->Ln(28);
$pdf->Cell(190,60,'',1,0,'C'); // border

$pdf->Ln(-1);
$pdf->Cell(2);
$pdf->Cell(30,10,'Hours:',0,0);

$pdf->SetFont('helvetica','',10);
$pdf->Ln(5);
$pdf->Cell(10);
$pdf->Cell(25,10,'Start Time:',0,0);

$pdf->Ln(2);
$pdf->Cell(35);
$pdf->Cell(50,5,'',1,0,'C'); // border

$pdf->Ln(0);
$pdf->Cell(35);
$pdf->TextField('start_time', 50, 5);

$pdf->Ln(-2);
$pdf->Cell(90);
$pdf->Cell(30,10,'Finish Time:',0,0);
$pdf->Ln(10);

$pdf->Ln(-8);
$pdf->Cell(115);
$pdf->Cell(50,5,'',1,0,'C'); // border

$pdf->Ln(0);
$pdf->Cell(115);
$pdf->TextField('finish_time', 50, 5);

$pdf->Ln(5);
$pdf->Cell(10);
$pdf->Cell(30,10,'Total Hours:',0,0);

$pdf->Ln(2);
$pdf->Cell(35);
$pdf->Cell(50,5,'',1,0,'C'); // border

$pdf->Ln(0);
$pdf->Cell(35);
$pdf->TextField('total_hours', 50, 5);

$pdf->Ln(-2);
$pdf->Cell(90);
$pdf->Cell(30,10,'Travel Time:',0,0);

$pdf->Ln(2);
$pdf->Cell(115);
$pdf->Cell(50,5,'',1,0,'C'); // border

$pdf->Ln(0);
$pdf->Cell(115);
$pdf->TextField('travel_time', 50, 5);

$pdf->SetFont('helvetica','B',11);

$pdf->Ln(10);
$pdf->Cell(190,60,'',1,0,'C'); // border

$pdf->Ln(1);
$pdf->Cell(2);
$pdf->Cell(30,10,'Works Completed?',0,0);

$pdf->Cell(30);
$pdf->Cell(30,10,'Yes',0,0);
$pdf->RadioButton('completed', 5, array(), array(), 'No',false,100,218);

$pdf->Cell(30);
$pdf->Cell(30,10,'No',0,0);
$pdf->RadioButton('completed', 5, array(), array(), 'yes',false,165,218);

$pdf->Ln(10);
$pdf->Cell(2);
$pdf->Cell(30,10,'Focus Shopfit Representative',0,0);

$pdf->Ln(6);
$pdf->Cell(2);
$pdf->Cell(30,10,'Date:',0,0);

$pdf->Ln(2);
$pdf->Cell(35);
$pdf->Cell(50,5,'',1,0,'C'); // border

$pdf->Ln(0);
$pdf->Cell(35);
$pdf->TextField('rep_date_sign', 50, 5);

$pdf->Ln(-2);
$pdf->Cell(100);
$pdf->Cell(30,10,'Signature:',0,0);


$pdf->Ln(7);
$pdf->Cell(2);
$pdf->Cell(30,10,'Print Name:',0,0);

$pdf->Ln(2);
$pdf->Cell(35);
$pdf->Cell(150,5,'',1,0,'C'); // border

$pdf->Ln(0);
$pdf->Cell(35);
$pdf->TextField('rep_name', 150, 5);


$pdf->Ln(10);
$pdf->Cell(2);
$pdf->Cell(30,10,'Acceptance of works completion by Client',0,0);

$pdf->Ln(6);
$pdf->Cell(2);
$pdf->Cell(30,10,'Date:',0,0);

$pdf->Ln(2);
$pdf->Cell(35);
$pdf->Cell(50,5,'',1,0,'C'); // border

$pdf->Ln(0);
$pdf->Cell(35);
$pdf->TextField('rep_date_sign', 50, 5);

$pdf->Ln(-2);
$pdf->Cell(100);
$pdf->Cell(30,10,'Signature:',0,0);


$pdf->Ln(7);
$pdf->Cell(2);
$pdf->Cell(30,10,'Print Name:',0,0);

$pdf->Ln(2);
$pdf->Cell(35);
$pdf->Cell(150,5,'',1,0,'C'); // border

$pdf->Ln(0);
$pdf->Cell(35);
$pdf->TextField('client_name', 150, 5);

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document

if (!file_exists($filepath)) {
    mkdir($filepath, 0755, true);
}

$pdf->Output($filepath.'maintenance_site_sheet.pdf','FI');
//$pdf->Output($filepath.'maintenance_site_sheet.pdf','I');

//$pdf->Output('example_054.pdf', 'FI');

//============================================================+
// END OF FILE
//============================================================+