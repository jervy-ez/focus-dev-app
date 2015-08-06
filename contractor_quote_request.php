<?php
foreach ($project_t->result_array() as $row){
  $client_id = $row['company_id'];
  $compname = $row['company_name'];
  $company_name = str_replace(' ', '', $compname);
  $company_name = str_replace('/', '', $company_name);
  $filepath = "reports/".$client_id."_".$company_name."/".$proj_id."/CQR/".$work_id."/";
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
function SetLogo($logo,$comp_name,$tel_no,$po_box,$acn,$focus_suburb,$focus_abn,$focus_email,$proj_id,$proj_name,$compname,$work_company_name,$abn,$cqr_notes_insurance,$insurance_stat) 
{ 
  $this->logo = $logo;  
  $this->comp_name = $comp_name; 
  $this->tel_no = $tel_no; 
  $this->po_box = $po_box;
  $this->acn = $acn;
  $this->focus_abn = $focus_abn;
  $this->abn = $abn;
  $this->focus_suburb = $focus_suburb;
  $this->focus_email = $focus_email;
  $this->proj_id = $proj_id;
  $this->proj_name = $proj_name;
  $this->compname = $compname;
  $this->work_company_name = $work_company_name;
  $this->cqr_notes_insurance = $cqr_notes_insurance;
  $this->insurance_stat = $insurance_stat;
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
   	$this->Cell(50,10,'Contractor Quote Request',0,0);
   	$this->Ln();
   	$this->SetFont('Arial','',12);
   	$this->Cell(5);
   	$this->Cell(150,2,'Project: '.$this->proj_name,0,0);
   	$this->SetFont('Arial','',10);
   	$this->Cell(50,2,'Project#: '.$this->proj_id,0,0);
   	$this->Ln(6);
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
    $this->SetY(-60);
    //Arial italic 8
    $this->SetFillColor(139,137,137);
    $this->Cell(0,1,'',1,0,'',true);
    $this->Ln(5);
    $this->Cell(190,35,'',1,0,'C'); // border
    $this->Ln(-2);
    $this->Cell(2);
    $this->SetFont('Arial','B',10);
    $this->SetFillColor(255,255,255);
    $this->Cell(55,5,"To be completed by tenderer",0,0,"C",true);
    $this->Ln(5);
    $this->Cell(5);
    $this->Cell(55,5,"We ".$this->work_company_name.",( ABN:".$this->abn." ),",0,0);
  
    $this->Ln(5);
    $this->Cell(5);
    $this->Cell(80,5,"submit our quotation of $ ",0,0);
    $this->line(60,254,95,254);
    $this->Cell(55,5,"being for the abovementioned works including GST ",0,0);
    $this->Ln(5);
    $this->Cell(5);
    $this->Cell(55,5,"Comments: ",0,0);
    $this->line(35,259,195,259);
    $this->Ln(5);
    $this->Cell(5);
    $this->Cell(55,5,"",0,0);
    $this->line(15,264,195,264);
    $this->Ln(5);
    $this->Cell(5);
    $this->Cell(55,5,"",0,0);
    $this->line(15,269,195,269);
    $this->Ln(5);
    $this->Cell(5);
    $this->Cell(55,5,"Signed ______________________________________ for and on behalf of the tenderer",0,0);
    $this->Ln(8);
    $this->Cell(190,10,'',1,0,'C'); // border
    $this->Ln(2);
    $this->Cell(2);
    $this->SetFont('Arial','B',10);
    if($this->insurance_stat == 0){
      $this->SetTextColor(255,0,0);
    }
    $this->MultiCell(185,5,$this->cqr_notes_insurance,0,'C');
    //Page number
    $this->Ln(1);
    $this->SetFont('Arial','I',8);
    $this->SetTextColor(0,0,0);
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    $this->SetFont("times","",11); 
    $this->Cell(-40, 10, "Date Printed: ".$date, 0, 1, "R", 0); 
}

}
//Instanciation of inherited class
$pdf=new PDF();
$pdf->SetLogo($focus_logo,$focus_comp,$office_no,$po_box,$acn,$focus_suburb,$focus_abn,$focus_email,$proj_id,$proj_name,$compname,$work_company_name,$abn,$cqr_notes_insurance,$insurance_stat);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true,60);

$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'Reply Deadline: '.$work_reply_date.'  '.$remarks,0,0);
$pdf->Ln(10);


$pdf->Cell(90,35,'',1,0,'C'); // border
$pdf->cell(1);
$pdf->Cell(100,170,'',1,0,'C'); // border

$pdf->Ln(-3);
$pdf->Cell(3);
$pdf->SetFont('Arial','',11);
$pdf->SetFillColor(255,255,255);
$pdf->Cell(25,5,"Contractor",0,0,"C",true);
$pdf->Cell(65);
$pdf->Cell(30,5,"Scope of Works",0,0,"C",true);

$pdf->Ln(5);
$pdf->Cell(2);
$pdf->Cell(25,5,$work_company_name,0,0);
$pdf->Cell(65);
//$pdf->MultiCell(95,200,'Notessdfsfsd sdflksjf lkjs lkjsdflkj slkdfjklsjdkl flksjdfkl lkjsdfl lksdjf lkdjsf lkjsdf lkjsd flksjdf lksjdf lksdfj fdf sdf sdfsdfsd sdf sdf sdf sdf sdf sdfsf sdf sdf sdf sdf sdf sdf sdf sdf  ',0,"T"); // border
//$pdf->drawTextBox('Notessdfsfsd sdflksjf lkjs lkjsdflkj slkd df df df df 4tdsgfdfgdfg  sdg fg fg fg fg fjklsjdkl flksjdfkl lkjsdfl lksdjf lkdjsf lkjsdf lkjsd flksjdf lksjdf lksdfj fdf sdf sdfsdfsd sdf sdf sdf sdf sdf sdfsf sdf sdf sdf sdf sdf sdf sdf sdf  ', 95, 165, 'L', 'T',false);
$pdf->Ln(5);
$pdf->Cell(2);
$pdf->Cell(25,5,$comp_address_1st,0,0);

$pdf->Ln(5);
$pdf->Cell(2);
$pdf->Cell(25,5,$comp_address_2nd,0,0);

$pdf->Ln(5);
$pdf->Cell(2);
$pdf->Cell(25,5,$comp_address_3rd,0,0);

$pdf->Ln(5);
$pdf->Cell(2);
$pdf->Cell(25,5,'Tel: '.$office_number,0,0);

$pdf->Ln(5);
$pdf->Cell(2);
$pdf->Cell(25,5,'E-mail: '.$cont_email,0,0);

$pdf->Ln(9);
$pdf->Cell(90,30,'',1,0,'C'); // border

$pdf->Ln(1);
$pdf->Cell(2);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(25,5,'Attention: '.$attention,0,0);

$pdf->Ln(5);
$pdf->Cell(2);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(25,5,'Description: ',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(25,5,$work_desc,0,0);

$pdf->Ln(5);
$pdf->Cell(2);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(33,5,'Expected Start: ',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(23,5,$start_date,0,0);

$pdf->SetFont('Arial','B',11);
$pdf->Cell(10,5,'End: ',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(25,5,$end_date,0,0);

$pdf->Ln();
$pdf->Cell(2);
$pdf->Cell(85,0,'',1,0,'C'); // border

$pdf->Ln(2);
$pdf->Cell(2);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(25,5,'Estimator: ',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(25,5,$contact_person,0,0);

$pdf->Ln(5);
$pdf->Cell(2);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(25,5,'E-mail: ',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(25,5,$email,0,0);

$pdf->Ln(10);
$pdf->Cell(90,23,'',1,0,'C'); // border

$pdf->Ln(-2);
$pdf->Cell(3);
$pdf->SetFont('Arial','',11);
$pdf->SetFillColor(255,255,255);
$pdf->Cell(25,5,"Site Address",0,0,"C",true);

$pdf->Ln(5);
$pdf->Cell(2);
$pdf->Cell(25,5,$site_address_1st,0,0);

$pdf->Ln(5);
$pdf->Cell(2);
$pdf->Cell(25,5,$site_address_2nd,0,0);

$pdf->Ln(5);
$pdf->Cell(2);
$pdf->Cell(25,5,$site_address_3rd,0,0);

$pdf->Ln(13);
$pdf->Cell(90,35,'',1,0,'C'); // border

$pdf->Ln(-2);
$pdf->Cell(4);
$pdf->SetFont('Arial','',11);
$pdf->SetFillColor(255,255,255);
$pdf->Cell(40,5,"Other Considerations",0,0,"C",true);

$pdf->Ln(5);
$pdf->Cell(5);
$pdf->Image('./img/'.$site_inspection_req,12,162,3);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,5,'Site Inspection Required',0,0);
$pdf->Image('./img/'.$week_work,61,162,3);
$pdf->Cell(25,5,'Week Work',0,0);

$pdf->Ln(5);
$pdf->Cell(5);
$pdf->Image('./img/'.$special_conditions,12,167,3);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,5,'Special Conditions',0,0);
$pdf->Image('./img/'.$weekend_work,61,167,3);
$pdf->Cell(25,5,'Weekend Work',0,0);

$pdf->Ln(5);
$pdf->Cell(5);
$pdf->Image('./img/'.$additional_visit_req,12,172,3);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,5,'Additional Visit Required',0,0);
$pdf->Image('./img/'.$after_hours_work,61,172,3);
$pdf->Cell(25,5,'After Hours Work',0,0);

$pdf->Ln(5);
$pdf->Cell(5);
$pdf->Image('./img/'.$operate_during_install,12,177,3);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,5,'Operate During Install',0,0);
$pdf->Image('./img/'.$new_premises,61,177,3);
$pdf->Cell(25,5,'New Premises',0,0);

$pdf->Ln(5);
$pdf->Cell(5);
$pdf->Image('./img/'.$other,12,182,3);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,5,'Others',0,0);
$pdf->Image('./img/'.$free_access,61,182,3);
$pdf->Cell(25,5,'Free Access',0,0);

$pdf->Ln(5);
$pdf->Cell(5);
$pdf->SetFont('Arial','',10);
$pdf->Cell(50,5,$otherdesc,0,0);

$pdf->Ln(10);
$pdf->Cell(90,35,'',1,0,'C'); // border

$pdf->Ln(-2);
$pdf->Cell(3);
$pdf->SetFont('Arial','',11);
$pdf->SetFillColor(255,255,255);
$pdf->Cell(25,5,"Attachments",0,0,"C",true);
// $x = 0;
// foreach ($work_attachement_t->result_array() as $row){
//   if(($x%2)==0){
//     $pdf->Ln(5);
//   }
//   $pdf->Cell(5);
//   $pdf->SetFont('Arial','',11);
//   $pdf->Cell(39,5,$row['attachment_type'],0,0);
//   $x++;
// }

$pdf->Ln(-127);
$pdf->Cell(95);
$pdf->MultiCell(95,5,$notes,0,"T"); // border
//for($i=1;$i<=40;$i++)
//    $pdf->Cell(0,10,'Printing line number '.$i,0,1);

if (!file_exists($filepath)) {
    mkdir($filepath, 0755, true);
}
$work_company_name = str_replace(' ', '_', $work_company_name);
$work_company_name = str_replace('/', '-', $work_company_name);
$pdf->Output($filepath.$work_company_name.'-cqr.pdf','F');
$pdf->Output($filepath.$work_company_name.'-cqr.pdf','I');
}
?>