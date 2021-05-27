<?php
foreach ($project_t->result_array() as $row){
  $client_id = $row['company_id'];
  $compname = $row['company_name'];
  $company_name = str_replace(' ', '', $compname);
  $filepath = "reports/".$client_id."_".$company_name."/".$proj_id."/";
define('FPDF_FONTPATH','font/');

require('fpdf/fpdf.php');
class PDF extends FPDF
{
	// Simple table
function BasicTable($header, $data)
{
    // Header
    foreach($header as $col)
        $this->Cell(40,7,$col,1);
    $this->Ln();
    // Data
    foreach($data as $row)
    {
        foreach($row as $col)
            $this->Cell(40,6,$col,1);
        $this->Ln();
    }
}

function SetLogo($logo,$comp_name,$tel_no,$po_box,$acn,$focus_suburb,$focus_abn,$focus_email,$proj_id,$proj_name,$compname,$mobile_number,$comp_office_number,$contact_person,$client_email)//,$work_company_name,$abn) 
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
  $this->mobile_number = $mobile_number;
  $this->comp_office_number = $comp_office_number;
  $this->contact_person = $contact_person;
  $this->client_email = $client_email;
  //$this->work_company_name = $work_company_name;
} 
//Page header
function Header()
{ 
    $this->Image('./uploads/misc/'.$this->logo,15,11,70);
    //Arial bold 15
    $this->SetFont('Arial','',10);
    //Move to the right
    $this->Cell(80, 20);
    //Title
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
    $this->Cell(50,5,'E-mail : '.$this->focus_email,0,0);

    $this->Ln(6);
    $this->Cell(0, 15,'',1);
    $this->Cell(-185, 20);
    $this->SetFont('Arial','',12);
    $this->Cell(120,10,'Client: '.$this->compname,0,0);
    $this->SetFont('Arial','B',14);
    $this->Cell(60,10,'Project Summary',0,0,'R');
    $this->Ln();
    $this->SetFont('Arial','',12);
    $this->Cell(5);
    $this->Cell(150,2,'Project: '.$this->proj_name,0,0);
    $this->SetFont('Arial','',10);
    $this->Cell(50,2,'Project#: '.$this->proj_id,0,0);

    $this->Ln(8);
    $this->SetFont('Arial','B',9);
    $this->Cell(14,2,'Contact: ',0,0);
    $this->SetFont('Arial','',9);
    $this->Cell(30,2,$this->contact_person,0,0);
    $this->SetFont('Arial','B',9);
    $this->Cell(6,2,'Tel: ',0,0);
    $this->SetFont('Arial','',9);
    $this->Cell(28,2,$this->comp_office_number,0,0);
    $this->SetFont('Arial','B',9);
    $this->Cell(9,2,'Mobi: ',0,0);
    $this->SetFont('Arial','',9);
    $this->Cell(25,2,$this->mobile_number,0,0);
    $this->SetFont('Arial','B',9);
    $this->Cell(11,2,'E-mail: ',0,0);
    $this->SetFont('Arial','',9);
    $this->Cell(40,2,$this->client_email,0,0);


    $this->Ln(5);
    $this->SetFillColor(139,137,137);
    $this->Cell(0,1,'',1,0,'',true);
    $this->Ln();
}

//Page footer
function Footer()
{
    global $date; 
    $date = date('d M Y');
    //Position at 1.5 cm from bottom
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFillColor(139,137,137);
    $this->Cell(0,1,'',1,0,'',true);
    $this->Ln();
    $this->SetFont('Arial','I',8);
    //Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    $this->SetFont("times","",11); 
    $this->Cell(-40, 10, "Date Printed: ".$date, 0, 1, "R", 0); 
}
}
//Instanciation of inherited class
$pdf=new PDF();
$pdf->SetLogo($focus_logo,$focus_comp,$office_no,$po_box,$acn,$focus_suburb,$focus_abn,$focus_email,$proj_id,$proj_name,$compname,$mobile_number,$comp_office_number,$contact_person,$client_email);//,$work_company_name,$abn);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'Address:',0,0);
$pdf->SetFont('Arial','',11);
$comp_address_1st = str_replace("&apos;","'",$comp_address_1st);
$pdf->Cell(50,10,$comp_address_1st,0,0);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'Site address:',0,0);
$pdf->SetFont('Arial','',11);
$site_address_1st = str_replace("&apos;","'",$site_address_1st);
$pdf->Cell(50,10,$site_address_1st,0,0);
$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$comp_address_2nd = str_replace("&apos;","'",$comp_address_2nd);
$pdf->Cell(50,10,$comp_address_2nd,0,0);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$site_address_2nd = str_replace("&apos;","'",$site_address_2nd);
$pdf->Cell(50,10,$site_address_2nd,0,0);
$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$comp_address_3rd = str_replace("&apos;","'",$comp_address_3rd);
$pdf->Cell(50,10,$comp_address_3rd,0,0);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$site_address_3rd = str_replace("&apos;","'",$site_address_3rd);
$pdf->Cell(50,10,$site_address_3rd,0,0);
$pdf->Ln();
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'Telephone:',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,$comp_office_number,0,0);
$pdf->SetFont('Arial','B',11);
// $pdf->Cell(30,10,'Telephone:',0,0);
// $pdf->SetFont('Arial','',11);
// $pdf->Cell(50,10,$comp_office_number,0,0);
$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'E-mail:',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,$client_email,0,0);
$pdf->Ln(8);
$pdf->SetFillColor(139,137,137);
$pdf->Cell(0,0,'',1,0,'',true);

$pdf->Ln(1);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(190,10,'Contractor Management',1,0);
$pdf->Cell(-1,10,'',0,0,'R');

$pdf->Ln(10);
foreach ($works_t->result_array() as $works_row){
  if($works_row['contractor_type'] == "3"):
    $work_description = $works_row['supplier_cat_name'];
  else:
    if($works_row['work_con_sup_id'] == "82"):
      $work_description = $works_row['other_work_desc'];
    else:
      $work_description = $works_row['job_sub_cat'];
    endif;
  endif;
  $work_description = html_entity_decode(str_replace("&apos;","'",$work_description)); 
  $work_description = html_entity_decode(str_replace("–","-",$work_description)); 

  $pdf->SetFont('Arial','B',11);
  $pdf->Cell(30,10,'',0,0);
  $pdf->SetFont('Arial','',11);
  $pdf->Cell(110,10,$work_description,0,0);
  $pdf->SetFont('Arial','',11);
  $pdf->Cell(50,10,number_format($works_row['total_work_quote']),0,0,'R');
  $pdf->Ln(5);
}

$pdf->Ln(15);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(190,10,'Site Installation:',1,0);
$pdf->Cell(-1,10,'$ '.number_format($final_labor_cost),0,0,'R');

$pdf->Ln(10);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(150,10,'Contract Management Total:',0,0,'R');
$pdf->SetFont('Arial','',11);
$pdf->Cell(40,10,'$ '.number_format($contract_management,2),0,0,'R');

$pdf->Ln(10);
$pdf->SetFillColor(139,137,137);
$pdf->Cell(0,0,'',1,0,'',true);

$pdf->Ln(0);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(150,10,'Variation Total:',0,0,'R');
$pdf->SetFont('Arial','',11);
$pdf->Cell(40,10,'$ '.$variation,0,0,'R');

$pdf->Ln(10);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(150,10,'',0,0,'R');
$pdf->SetFont('Arial','',11);
$pdf->SetFillColor(139,137,137);
$pdf->Cell(0,0,'',1,0,'',true);

$sub_total = $contract_management + $variation;

$pdf->Ln(0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(150,10,'Sub Total:',0,0,'R');
$pdf->SetFont('Arial','',11);
$pdf->Cell(40,10,'$ '.number_format($sub_total,2),0,0,'R');

$percent_gst = $sub_total * 0.1;

$pdf->Ln(5);
$pdf->SetFont('Arial','',11);
$pdf->Cell(150,10,'10.00% GST:',0,0,'R');
$pdf->SetFont('Arial','',11);
$pdf->Cell(40,10,'$ '.number_format($percent_gst,2),0,0,'R');

$inc_proj_total = $percent_gst + $sub_total;

$pdf->Ln(10);
$pdf->Cell(190,13,'',1,0,'R');
$pdf->SetFont('Arial','B',14);
$pdf->Cell(-40,10,'Project Total:',0,0,'R');
$pdf->SetFont('Arial','B',14);
$pdf->Cell(40,10,'$ '.number_format($inc_proj_total,2),0,0,'R');

$pdf->Ln(5);
$pdf->Cell(150);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(40,10,'Inc GST',0,0,'R');


//for($i=1;$i<=40;$i++)
//    $pdf->Cell(0,10,'Printing line number '.$i,0,1);

if (!file_exists($filepath)) {
    mkdir($filepath, 0755, true);
}
$pdf->Output($filepath.'project_summary_w_cost.pdf','F');
$pdf->Output($filepath.'project_summary_w_cost.pdf','I');
}
?>