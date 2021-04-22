<?php
foreach ($project_t->result_array() as $row){
  $client_id = $row['client_id'];
  $is_pending_client = $row['is_pending_client'];
  if($is_pending_client == 0):
    $compname = $row['company_name'];
  else:
    $compname = $row['pending_comp_name'];
  endif;
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

function SetLogo($logo,$comp_name,$tel_no,$po_box,$acn,$focus_suburb,$focus_abn,$focus_email,$proj_id,$proj_name,$compname)//,$work_company_name,$abn) 
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
    $this->Cell(60,10,'Joinery Summary',0,0,'R');
    $this->Ln();
    $this->SetFont('Arial','',12);
    $this->Cell(5);
    $this->Cell(150,2,'Project: '.$this->proj_name,0,0);
    $this->SetFont('Arial','',10);
    $this->Cell(50,2,'Project#: '.$this->proj_id,0,0);
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
$pdf->SetLogo($focus_logo,$focus_comp,$office_no,$po_box,$acn,$focus_suburb,$focus_abn,$focus_email,$proj_id,$proj_name,$compname);//,$work_company_name,$abn);
$pdf->AliasNbPages();
$pdf->AddPage();


$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(20,10,'Sheet#',0,0);
$pdf->Cell(90,10,'Description',0,0);
$pdf->Cell(30,10,'Qty',0,0,'R');

$pdf->Ln(7);
$pdf->SetFillColor(0,0,0);
$pdf->Cell(0,1,'',1,0,'',true);

$x = 1;
$pdf->Ln(-3);
$quoted_total = 0;
foreach ($joinery_t->result_array() as $joinery_row){
  $joinery_name = $joinery_row['joinery_name'];
  $joinery_qty = $joinery_row['qty'];

  $work_description = html_entity_decode(str_replace("&apos;","'",$joinery_name)); 

  $pdf->Ln(5);
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(20,10,$x,0,0);
  $pdf->Cell(90,10,$work_description,0,0);
  $pdf->Cell(30,10,$joinery_qty,0,0,'R');
  $quoted_total = $quoted_total + $joinery_row['j_quote'];
  $x++;
}


$pdf->Ln(20);
$pdf->Cell(190,10,'',1,0,'R');
$pdf->SetFont('Arial','B',14);
$pdf->Cell(-40,10,'Total (ex GST):',0,0,'R');
$pdf->SetFont('Arial','B',14);
$pdf->Cell(40,10,number_format($quoted_total),0,0,'R');


//for($i=1;$i<=40;$i++)
//    $pdf->Cell(0,10,'Printing line number '.$i,0,1);

if (!file_exists($filepath)) {
    mkdir($filepath, 0755, true);
}
$pdf->Output($filepath.'joinery_summary_wo_cost.pdf','F');
$pdf->Output($filepath.'joinery_summary_wo_cost.pdf','I');
}
?>