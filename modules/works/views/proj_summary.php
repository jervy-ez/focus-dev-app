<?php
foreach ($project_t->result_array() as $row){
  $client_id = $row['company_id'];
  $compname = $row['company_name'];
  $company_name = str_replace(' ', '', $compname);
  $filepath = "upload/".$client_id."_".$company_name."/".$proj_id."/";
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
//Page header
function Header()
{
	global $pono;
	$pono = '12515';

    $this->Image('./img/focus-logo.JPG',10,8,70);

    //Arial bold 15
    $this->SetFont('Arial','',10);
    //Move to the right
    $this->Cell(80, 20);
    //Title
   	$this->Cell(50,5,'FocusShopfit',0,0,'');
   	$this->Ln();
   	$this->Cell(80, 20);
   	$this->Cell(50,5,'PO 1326',0,0);
   	$this->Cell(50,5,'Tel:    08 635 0991',0,0);
   	$this->Ln();
   	$this->Cell(80, 20);
   	$this->Cell(50,5,'Wangara WA 6947 ',0,0);
   	$this->Cell(50,5,'Fax:',0,0);
   	$this->Ln();
   	$this->Cell(130, 20);
   	$this->Cell(50,5,'ACN: 159 087 984',0,0);
   	$this->Ln();
   	$this->Cell(80, 6,'E-mail : admin@focusshopfit.com.au');
   	$this->Cell(50,5,'ABN: 16 159 087 984',0,0);
   	$this->Ln(6);
   	$this->Cell(0, 15,'',1);
   	$this->Cell(-185, 20);
   	$this->SetFont('Arial','',12);
   	$this->Cell(140,10,'Client: Australasian Retail Projects Pty Ltd',0,0);
   	$this->SetFont('Arial','B',12);
   	$this->Cell(50,10,'Project Summary',0,0);
   	$this->Ln();
   	$this->SetFont('Arial','',12);
   	$this->Cell(5);
   	$this->Cell(150,2,'Project: Universal Store Joondalup FFO',0,0);
   	$this->SetFont('Arial','',10);
   	$this->Cell(50,2,'Project#:   32819',0,0);
   	$this->Ln(6);
   	$this->Cell(50,5,'Contact:',0,0);
   	$this->Cell(50,5,'Tel:',0,0);
   	$this->Cell(50,5,'Fax:',0,0);
   	$this->Cell(50,5,'Mobi:',0,0);
   	$this->Ln();
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
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,10,'Address:',0,0);
$pdf->SetFont('Arial','',11);
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
//$pdf->Cell(190,10,'',1,0,'R');
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
$pdf->Cell(40,10,'$0.00',0,0,'R');

//for($i=1;$i<=40;$i++)
//    $pdf->Cell(0,10,'Printing line number '.$i,0,1);

if (!file_exists($filepath)) {
    mkdir($filepath, 0755, true);
}
$pdf->Output($filepath.'project_summary.pdf','F');
$pdf->Output($filepath.'project_summary.pdf','I');
}
?>