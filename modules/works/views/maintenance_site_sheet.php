<?php
foreach ($project_t->result_array() as $row){
  $client_id = $row['company_id'];
  $compname = str_replace("&apos;", "'", $row['company_name']);
  $company_name = str_replace(' ', '', $compname);
  $company_name = str_replace('/', '', $company_name);
  $filepath = $_SERVER['DOCUMENT_ROOT']."/reports/".$client_id."_".$company_name."/".$proj_id."/MSS/".$work_id."/";
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

$pdf->setFontSubsetting(false);

// set font
$pdf->SetFont('helvetica', '', 10, '', false);

// add a page
$pdf->AddPage();

$pdf->Ln(48);
$pdf->Cell(190,175,'',1,0,'C'); // border

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
//Added
$pdf->Ln(7);
$pdf->Cell(3);
$pdf->SetFont('helvetica','B',11);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(118,5,'Prestart Evaluation',0,0);
$pdf->Cell(12,5,'Y | N',0,0,'C');
$pdf->Cell(55,5,'Comments',0,0,'C');

$pdf->SetFont('helvetica','',9);
$pdf->Ln(6);
$pdf->Cell(3);
$pdf->Cell(118,5,'Have you discussed works with person on site',0,0);

$pdf->RadioButton('select_one', 5, array(), array(), 'No',false,132,87);
$pdf->RadioButton('select_one', 5, array(), array(), 'No',false,137,87);

$pdf->Ln(0);
$pdf->Cell(133);
$pdf->Cell(55,5,'',1,0,'C'); // border

$pdf->Ln(0);
$pdf->Cell(133);
$pdf->TextField('client_name', 55, 5);

$pdf->Ln(6);
$pdf->Cell(3);
$pdf->Cell(118,5,'Have you checked work area to ensure all works can be achieved in a safe manner',0,0);

$pdf->RadioButton('select_two', 5, array(), array(), 'No',false,132,93);
$pdf->RadioButton('select_two', 5, array(), array(), 'No',false,137,93);

$pdf->Ln(0);
$pdf->Cell(133);
$pdf->Cell(55,5,'',1,0,'C'); // border

$pdf->Ln(0);
$pdf->Cell(133);
$pdf->TextField('client_name', 55, 5);

$pdf->Ln(6);
$pdf->Cell(3);
$pdf->Cell(118,5,'Have you secured the area (if applicable) to prevent access by others',0,0);

$pdf->RadioButton('select_three', 5, array(), array(), 'No',false,132,99);
$pdf->RadioButton('select_three', 5, array(), array(), 'No',false,137,99);

$pdf->Ln(0);
$pdf->Cell(133);
$pdf->Cell(55,5,'',1,0,'C'); // border

$pdf->Ln(0);
$pdf->Cell(133);
$pdf->TextField('client_name', 55, 5);

$pdf->Ln(6);
$pdf->Cell(3);
$pdf->Cell(118,5,'Have you reviewed the SWMS and added/amended if required',0,0);

$pdf->RadioButton('select_four', 5, array(), array(), 'No',false,132,105);
$pdf->RadioButton('select_four', 5, array(), array(), 'No',false,137,105);

$pdf->Ln(0);
$pdf->Cell(133);
$pdf->Cell(55,5,'',1,0,'C'); // border

$pdf->Ln(0);
$pdf->Cell(133);
$pdf->TextField('client_name', 55, 5);

$pdf->Ln(6);
$pdf->Cell(3);
$pdf->Cell(118,5,'Do you have appropriate PPE (Personal Protection Equipment)',0,0);

$pdf->RadioButton('select_five', 5, array(), array(), 'No',false,132,111);
$pdf->RadioButton('select_five', 5, array(), array(), 'No',false,137,111);

$pdf->Ln(0);
$pdf->Cell(133);
$pdf->Cell(55,5,'',1,0,'C'); // border

$pdf->Ln(0);
$pdf->Cell(133);
$pdf->TextField('client_name', 55, 5);

$pdf->SetFont('helvetica','B',11);

$pdf->Ln(6);
$pdf->Cell(3);
$pdf->Cell(118,5,'Please ensure everything on this list has been considered prior to the commencement of any works',0,0);
//Added

$pdf->SetTextColor(0, 0, 0);
$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
$pdf->Line(10, 122, 200, 122, $style);

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
$pdf->Cell(190,40,'',1,0,'C'); // border

$pdf->Ln(1);
$pdf->Cell(2);
$pdf->SetFont('helvetica','B',8);
$pdf->Cell(30,10,'Hours:',0,0);

$pdf->SetFont('helvetica','',7);
$pdf->Ln(0);
$pdf->Cell(13);
$pdf->Cell(25,10,'Start Time:',0,0);

$pdf->Ln(2);
$pdf->Cell(30);
$pdf->Cell(20,5,'',1,0,'C'); // border

$pdf->Ln(0);
$pdf->Cell(30);
$pdf->TextField('start_time', 20, 5);

$pdf->Ln(-2);
$pdf->Cell(53);
$pdf->Cell(30,10,'Finish Time:',0,0);
$pdf->Ln(10);

$pdf->Ln(-8);
$pdf->Cell(75);
$pdf->Cell(20,5,'',1,0,'C'); // border

$pdf->Ln(0);
$pdf->Cell(75);
$pdf->TextField('finish_time', 20, 5);

$pdf->Ln(-2);
$pdf->Cell(98);
$pdf->Cell(30,10,'Total Hours:',0,0);

$pdf->Ln(2);
$pdf->Cell(120);
$pdf->Cell(20,5,'',1,0,'C'); // border

$pdf->Ln(0);
$pdf->Cell(120);
$pdf->TextField('total_hours', 20, 5);

$pdf->Ln(-2);
$pdf->Cell(143);
$pdf->Cell(30,10,'Travel Time:',0,0);

$pdf->Ln(2);
$pdf->Cell(165);
$pdf->Cell(20,5,'',1,0,'C'); // border

$pdf->Ln(0);
$pdf->Cell(165);
$pdf->TextField('travel_time', 20, 5);

$pdf->SetFont('helvetica','B',8);


$pdf->Ln(3);
$pdf->Cell(2);
$pdf->Cell(30,10,'Works Completed?',0,0);

$pdf->Cell(5);
$pdf->Cell(30,10,'Yes',0,0);
$pdf->RadioButton('completed', 5, array(), array(), 'No',false,60,244);

$pdf->Cell(5);
$pdf->Cell(30,10,'No',0,0);
$pdf->RadioButton('completed', 5, array(), array(), 'yes',false,100,244);

$pdf->Ln(6);
$pdf->Cell(2);
$pdf->Cell(30,10,'Focus Shopfit Representative',0,0);

$pdf->Ln(6);
$pdf->Cell(2);
$pdf->Cell(30,10,'Date:',0,0);

$pdf->Ln(2);
$pdf->Cell(20);
$pdf->Cell(25,5,'',1,0,'C'); // border

$pdf->Ln(0);
$pdf->Cell(20);
$pdf->TextField('rep_date_sign', 25, 5);

$pdf->Ln(-2);
$pdf->Cell(48);
$pdf->Cell(30,10,'Print Name:',0,0);

$pdf->Ln(2);
$pdf->Cell(75);
$pdf->Cell(40,5,'',1,0,'C'); // border

$pdf->Ln(0);
$pdf->Cell(75);
$pdf->TextField('client_name', 40, 5);


$pdf->Ln(-2);
$pdf->Cell(120);
$pdf->Cell(30,10,'Signature:',0,0);


$pdf->Ln(6);
$pdf->Cell(2);
$pdf->Cell(30,10,'Acceptance of works completion by Client',0,0);

$pdf->Ln(6);
$pdf->Cell(2);
$pdf->Cell(30,10,'Date:',0,0);

$pdf->Ln(2);
$pdf->Cell(20);
$pdf->Cell(25,5,'',1,0,'C'); // border

$pdf->Ln(0);
$pdf->Cell(20);
$pdf->TextField('rep_date_sign', 25, 5);

$pdf->Ln(-2);
$pdf->Cell(48);
$pdf->Cell(30,10,'Print Name:',0,0);

$pdf->Ln(2);
$pdf->Cell(75);
$pdf->Cell(40,5,'',1,0,'C'); // border

$pdf->Ln(0);
$pdf->Cell(75);
$pdf->TextField('client_name', 40, 5);


$pdf->Ln(-2);
$pdf->Cell(120);
$pdf->Cell(30,10,'Signature:',0,0);




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