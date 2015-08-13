<?php

foreach ($project_t->result_array() as $row){
  $client_id = $row['company_id'];
  $compname = $row['company_name'];
  $project_name = $row['project_name'];
  $company_name = str_replace(' ', '', $compname);
  $company_name = str_replace('/', '', $company_name);

  $filepath = "reports/".$client_id."_".$company_name."/".$proj_id."/CPO/".$work_id."/";
  foreach ($works_t->result_array() as $works_row){
    $work_cpo_date = $works_row['work_cpo_date'];
    if($goods_deliver_by_date == ""){
       $goods_deliver_by_date = "";
    }else{
      $cpo_date = explode('/', $goods_deliver_by_date);
      $cpo_date_m = $cpo_date[1];
      $cpo_date_d = $cpo_date[0];
      $cpo_date_y = $cpo_date[2];
      $monthName = date("F", mktime(null, null, null, $cpo_date_m));
      $goods_deliver_by_date = "Order Required by: ".$cpo_date_d." ".$monthName." ".$cpo_date_y;
    }
  }
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

function SetLogo($logo,$comp_name,$tel_no,$po_box,$acn,$focus_suburb,$abn,$focus_email,$inc_gst,$project_total,$price,$cpo_notes_insurance,$insurance_stat) 
{ 
  $this->logo = $logo;  
  $this->comp_name = $comp_name; 
  $this->tel_no = $tel_no; 
  $this->po_box = $po_box;
  $this->acn = $acn;
  $this->abn = $abn;
  $this->focus_suburb = $focus_suburb;
  $this->focus_email = $focus_email;
  $this->inc_gst = $inc_gst;
  $this->project_total = "$ ".number_format($project_total,2);
  $this->price = "$ ".number_format($price,2);
  $this->cpo_notes_insurance = $cpo_notes_insurance;
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
    $this->Cell(50,5,'ABN:  '.$this->abn,0,0);

    $this->Ln(10);
    $this->Cell(80, 20);
    $this->Cell(50,5,'E-mail : '.$this->focus_email,0,0);

    $this->Ln(6);
    $this->SetFillColor(139,137,137);
    $this->Cell(0,0,'',1,0,'',true);
    $this->Ln();
}

//Page footer
function Footer()
{
    global $date; 
    $date = date('d M Y');
    //Position at 1.5 cm from bottom
    $this->SetY(-50);
    //Arial italic 8
    $this->SetFillColor(139,137,137);
    $this->Cell(0,1,'',1,0,'',true);
    $this->Ln(1);
    
    if($this->insurance_stat == 0){
      $this->SetTextColor(255,0,0);
    }
    $this->MultiCell(185,5,$this->cpo_notes_insurance,0,'C');

    $this->SetTextColor(0,0,0);
    $this->SetFillColor(139,137,137);
    $this->Cell(0,1,'',1,0,'',true);
    $this->Ln(2);

    $this->Cell(90,25,'',1,0,'C'); // border
    $this->Cell(-90, 20);
    $this->SetFont('Arial','B',10);
    $this->Cell(50,5,'General:',0,0);// line 1

    $this->Cell(45, 20);
    $this->Cell(87,8,'',1,0,'C'); // border
    $this->Cell(-85, 20);
    $this->SetFont('Arial','B',16);
    $this->Cell(50,8,'Price:',0,0,'L');
    $this->Cell(35,8,$this->price,0,0,'R');
    $this->SetFont('Arial','',9);
    $this->Cell(33,8,'ex GST',0,0);

    $this->Ln(5);
    $this->Cell(-2, 20);
    $this->SetFont('Arial','',10);
    $this->MultiCell(80,5,'',0,0); // line 2

    $this->Cell(95, 20);
    $this->SetFont('Arial','',9);
    $this->Cell(100,2,'$ '.number_format($this->inc_gst, 2).' inc GST',0,0,'R');

    $this->Ln(5);
    $this->Cell(-21, 20);
    $this->SetFont('Arial','',10);
    $this->MultiCell(80,-5,'',0,0); // line 3

    $this->Cell(95, 20);
    $this->SetFont('Arial','B',10);
    $this->Cell(50,10,'Purchase Order number MUST be shown on all Invoices',0,0);

    $this->Ln(8);
    $this->Cell(95, 20);
    $this->SetFont('Arial','',10);
    $this->Cell(50,5,'Standard terms 45 days EOM unless otherwise agreed',0,0);

    $this->Ln(10);
    $this->SetFont('Arial','I',8);
    //Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    $this->SetFont("times","",11); 
    $this->Cell(-40, 10, "Date Printed: ".$date, 0, 1, "R", 0); 
}
}
//Instanciation of inherited class
$pdf=new PDF();
$pdf->SetLogo($focus_logo,$focus_comp,$office_no,$po_box,$acn,$focus_suburb,$abn,$focus_email,$inc_gst,$project_total,$price,$cpo_notes_insurance,$insurance_stat);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true,50);

$pdf->Ln(3);
$pdf->Cell(5);
$pdf->Cell(90,16,'',1,0,'C'); // border
$pdf->SetFont('Arial','B',16);
$pdf->Cell(-90,8,'Contractor Purchase Order',0,0,'C');

$pdf->SetFont('Arial','B',11);
$pdf->Cell(93,5,'',0,0);
$pdf->Cell(15,5,'Client: ',0,0);
$pdf->Cell(10);
$pdf->SetFont('Arial','',11);
$pdf->Cell(0,5,$compname,0,0);

$pdf->Ln(5);

$pdf->Cell(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(93,5,'',0,0);
$pdf->Cell(18,5,'Project:',0,0);
$pdf->Cell(7);
$pdf->SetFont('Arial','',11);
$pdf->Cell(0,5,$project_name,0,0);

$pdf->Ln(3);

$pdf->Cell(13);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(15,5,'Date:',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(30,5,$work_cpo_date,0,0);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(15,5,$work_id." / ".$proj_id,0,0);

$pdf->SetFont('Arial','B',11);
$pdf->Cell(25,5,'',0,0);
$pdf->Cell(25,9,'Our Contact:',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(0,9,$contact_person,0,0);

$pdf->Ln(3);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(98,9,'',0,0);
$pdf->Cell(15,12,'Email:',0,0);
$pdf->Cell(10);
$pdf->SetFont('Arial','',11);
$pdf->Cell(0,12,$email,0,0);

$pdf->Ln(10);

$pdf->Cell(5);
$pdf->Cell(90,35,'',1,0,'C'); // border
$pdf->Ln(-3);
$pdf->Cell(8);
$pdf->SetFont('Arial','',11);
$pdf->SetFillColor(255,255,255);
$pdf->Cell(25,5,"Contractor",0,0,"C",true);

$pdf->Ln(8);
$pdf->Cell(8);
$pdf->SetFont('Arial','',10);
$pdf->Cell(25,5,$work_company_name,0,0);

$pdf->Cell(80);
$pdf->SetFont('Arial','B',12);
//$pdf->Cell(25,-7,"Order Required by: ",0,0);
$pdf->Cell(25,-7,$goods_deliver_by_date,0,0);
$pdf->Cell(-40);
$pdf->Cell(90,30,'',1,0,'C'); // border
$pdf->Cell(-88);
$pdf->SetFont('Arial','B',11);
$pdf->SetFillColor(255,255,255);
$pdf->Ln(3);
$pdf->cell(100);
$pdf->Cell(20,0,"Site",0,0);

$pdf->Ln(2);
$pdf->Cell(8);
$pdf->SetFont('Arial','',10);
$pdf->Cell(45,5,$comp_address_1st,0,0); // Contractor
/*$pdf->SetFont('Arial','B',10);
$pdf->Cell(10,5,'ABN:',0,0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(20,5,$abn,0,0);
*/
$pdf->Cell(48);
$pdf->SetFont('Arial','',10);
$pdf->Cell(45,5,$site_address_1st,0,0);

$pdf->Ln(5);
$pdf->Cell(8);
$pdf->SetFont('Arial','',10);
$pdf->Cell(45,5,$comp_address_2nd,0,0);
/*$pdf->SetFont('Arial','B',10);
$pdf->Cell(10,5,'Office No.:',0,0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(20,5,"",0,0);
*/
$pdf->Cell(48);
$pdf->SetFont('Arial','',10);
$pdf->Cell(45,5,$site_address_2nd,0,0);

$pdf->Ln(5);
$pdf->Cell(8);
$pdf->SetFont('Arial','',10);
$pdf->Cell(45,5,$comp_address_3rd,0,0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(5,5,"",0,0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(20,5,"",0,0);

$pdf->Cell(23);
$pdf->SetFont('Arial','',10);
$pdf->Cell(45,5,$site_address_3rd,0,0);

$pdf->Ln(5);
$pdf->Cell(8);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(45,5,"Attention: ". $attention,0,0);

$pdf->Cell(48);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(20,5,'Office No.:',0,0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(45,5,$comp_office_number,0,0);

$pdf->Ln(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(25);
$pdf->Cell(45,5,$office_number,0,0);

$pdf->Ln(7);
$pdf->Cell(3);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(20,5,'Work Description:',0,0);

$pdf->Cell(12);
$pdf->SetFont('Arial','',10);
$pdf->Cell(50,5,$work_desc,0,0);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(30,5,'Estimated Project Start:',0,0);

$pdf->Cell(12);
$pdf->SetFont('Arial','',10);
$pdf->Cell(23,5,$start_date,0,0);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(13,5,'Finish:',0,0);

$pdf->SetFont('Arial','',10);
$pdf->Cell(50,5,$end_date,0,0);

$pdf->Ln(8);
$pdf->SetFillColor(139,137,137);
$pdf->Cell(0,1,'',1,0,'',true);

$pdf->Ln(8);
$pdf->MultiCell(0,5,$notes);
/*$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'21 Lake Street',0,0);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'Site address:',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'Shop T118 Lakeside Joondalup',0,0);
$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'Varsity Lakes',0,0);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'420 Joondalup Drive',0,0);
$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'QLD 4228',0,0);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'Joondalup WA 6027',0,0);
$pdf->Ln();
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'Telephone:',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'07 5655 0328',0,0);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'Telephone:',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'',0,0);
$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'Facsimile:',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'07 5655 0329',0,0);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'Facsimile:',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'',0,0);
$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'E-mail:',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'adam@apiretail.com.au',0,0);
$pdf->Ln(8);
$pdf->SetFillColor(139,137,137);
$pdf->Cell(0,0,'',1,0,'',true);

$pdf->Ln(1);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(30,10,'Manufacture:',0,0);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(160,10,'Contract',0,0,'R');
$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'Design & Draft:',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(160,10,'$0',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'Installation:',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(160,10,'$26,438',0,0,'R');

$pdf->Ln(10);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(160,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->SetFillColor(139,137,137);
$pdf->Cell(30,0,'',1,0,'R',true);

$pdf->Ln(1);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'Material & Labour:',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(160,10,'$0',0,0,'R');

$pdf->Ln(10);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(190,10,'Manufactured Total:',1,0);
$pdf->Cell(-1,10,'$26,438',0,0,'R');

$pdf->Ln(10);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(190,10,'Contractor Management',0,0);

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'After Hours Security',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$0',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Bin Hire & Waste Mgt.',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$1,063',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Building License',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$0',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Cat 1 - Aircond',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$0',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Cat 1 - Electrical',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$0',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Cat 1 - EWIS',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$0',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Cat 1 - Exit & Emerg',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$0',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Cat 1 - Hoarding',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$0',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Cat 1 - Smoke Det.',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$0',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Cat 1 - Terrazzo',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$0',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Cat 1 - Firesprinkler',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$0',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Cat 1 - Floor Chasing',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$0',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Cat 1 - Hydraulics',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$0',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Cat 1 - UnderslapPlumb',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$0',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Client Supplied Item',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$1,875',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Delivery',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$3,563',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Distribution Board',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$0',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Doors & Frames',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$3,000',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Electrical / Data',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$21,094',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Engineers Certificate',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$750',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Exit/Emergency Light',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$0',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Expansion Joint',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$0',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Fire Extinguisher',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$350',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Glass / Glazing',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$26,006',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Gyprock Ceiling/Wall',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$11,981',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Gyprock Cladding',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$0',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Hoarding',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$0',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Inital Clean',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$1,750',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Insurance',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$563',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Joinery',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$123,185',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Jumper Phone Lines',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$360',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Lighting Supply',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$0',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Painting',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$17,063',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Patch / Makegood',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$0',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Plumbing',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$2,651',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Ramp Entry',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$1,2500',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Scabble Entry',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$0',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Scaffold/Genie Lift',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$1,500',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Set Out/Site Survey',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$2,323',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Shopfront Steelwork',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$19,056',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Signage',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$8,049',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Speakers',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$0',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Supervision',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$1,875',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Terrazo',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$0',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Tiles-Laying',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$31,519',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(110,10,'Tiles Supply',0,0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(50,10,'$0',0,0,'R');

$pdf->Ln(10);
$pdf->SetFillColor(139,137,137);
$pdf->Cell(0,0,'',1,0,'',true);

$pdf->Ln(0);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(150,10,'Contract Management Total:',0,0,'R');
$pdf->SetFont('Arial','',11);
$pdf->Cell(40,10,'$280,824',0,0,'R');

$pdf->Ln(10);
$pdf->SetFillColor(139,137,137);
$pdf->Cell(0,0,'',1,0,'',true);

$pdf->Ln(0);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(150,10,'Contingency:',0,0,'R');
$pdf->SetFont('Arial','',11);
$pdf->Cell(40,10,'$0',0,0,'R');

$pdf->Ln(10);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(150,10,'',0,0,'R');
$pdf->SetFont('Arial','',11);
$pdf->SetFillColor(139,137,137);
$pdf->Cell(0,0,'',1,0,'',true);

$pdf->Ln(0);
$pdf->SetFont('Arial','',11);
$pdf->Cell(150,10,'Sub Total:',0,0,'R');
$pdf->SetFont('Arial','',11);
$pdf->Cell(40,10,'$307,264',0,0,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','',11);
$pdf->Cell(150,10,'10.00% GST:',0,0,'R');
$pdf->SetFont('Arial','',11);
$pdf->Cell(40,10,'$307,726.40',0,0,'R');

$pdf->Ln(10);
$pdf->Cell(190,10,'',1,0,'R');
$pdf->SetFont('Arial','B',14);
$pdf->Cell(-40,10,'Project Total:',0,0,'R');
$pdf->SetFont('Arial','B',14);
$pdf->Cell(40,10,'$337,990.40',0,0,'R');

$pdf->Ln(10);
$pdf->SetFont('Arial','',11);
$pdf->Cell(30,10,'Var Subtotal:',0,0);
$pdf->Cell(10,10,'$0',0,0);
$pdf->Cell(50,10,'10.00% VAR GST:',0,0);
$pdf->Cell(10,10,'$0.00',0,0);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(50,10,'Variation total (inc GST, Less credits):',0,0);
$pdf->Cell(40,10,'$0.00',0,0,'R');*/

//for($i=1;$i<=40;$i++)
//    $pdf->Cell(0,10,'Printing line number '.$i,0,1);

if (!file_exists($filepath)) {
    mkdir($filepath, 0755, true);
}
$work_company_name = str_replace(' ', '_', $work_company_name);
$work_company_name = str_replace('/', '-', $work_company_name);
$pdf->Output($filepath.$work_company_name.'-cpo.pdf','F');
$pdf->Output($filepath.$work_company_name.'-cpo.pdf','I');
}
?>