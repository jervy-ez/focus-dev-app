<?php
foreach ($project_t->result_array() as $row){
  $client_id = $row['company_id'];
  $compname = $row['company_name'];
  $compname = str_replace('&apos;', "'", $compname);

  $leading_hand = $row['lh_fname']." ".$row['lh_sname'];

  $project_name = $row['project_name'];
  $project_name = str_replace("&apos;","'", $project_name);
  
  $company_name = str_replace(' ', '', $compname);
  $company_name = str_replace('/', '', $company_name);
 

  $filepath = "reports/".$client_id."_".$company_name."/".$proj_id."/safety_site_observation/";

  $site_address = "Unit/Level:".$site_address_1st.", ".$site_address_2nd.", ".$site_address_3rd;

  $dt = new DateTime($row['sswo_date_time']);

  $date_send = $dt->format('d/m/Y');
  $time_send = $dt->format('H:i:s');



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

function SetLogo($logo,$comp_name,$tel_no,$po_box,$acn,$focus_suburb,$abn,$focus_email,$project_total,$user_first_name,$user_last_name,$project_name,$date_send,$role_types) 
{ 
  $this->logo = $logo;  
  $this->comp_name = $comp_name; 
  $this->tel_no = $tel_no; 
  $this->po_box = $po_box;
  $this->acn = $acn;
  $this->abn = $abn;
  $this->focus_suburb = $focus_suburb;
  $this->focus_email = $focus_email;
  $this->project_total = "$ ".number_format($project_total,2);
  $this->user_first_name = $user_first_name;
  $this->user_last_name = $user_last_name;
  $this->project_name = $project_name;
  $this->date_send = $date_send;
  $this->role_types = $role_types;
} 
//Page header
function Header()
{  
    $this->Image('./uploads/misc/'.$this->logo,170,9,30);
    //Arial bold 15
    $this->SetFont('Arial','',12);
    //Title
    $this->Cell(50,5,"Safe Construction Management Plan",0,0,'');
    $this->Ln(10);
	$this->SetFillColor(139,137,137);
	$this->Cell(0,1,'',1,0,'',true);
	$this->Ln(10);
}

//Page footer
function Footer()
{
    global $date; 
    $date = date('d M Y');
    $this->SetFont('Arial','I',8);
    $this->SetY(-25);
    $this->Cell(190,10,"",1);
    $this->Cell(-190);
    $this->MultiCell(185,5,"I declare that the on-site documentation and the site work practises are adequate. If this is not the case and that action is required, it is my responsibility to see to it that it is put in place and followed up.",0,'C');
    $this->ln(0);
    $this->Cell(25,6,"Name",1);
    $this->Cell(70,6,$this->user_first_name." ".$this->user_last_name,1,0,'L',true);
    $this->Cell(25,6,"Title",1);
    $this->Cell(70,6,$this->role_types,1,0,'L',true);
}
}
//Instanciation of inherited class
$pdf=new PDF();
$pdf->SetLogo($focus_logo,$focus_comp,$office_no,$po_box,$acn,$focus_suburb,$abn,$focus_email,$project_total,$row['user_first_name'],$row['user_last_name'],$project_name,$date_send,$row['role_types']);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true,20);

$pdf->Ln(-5);
$pdf->SetFont('Arial','B',15);
$pdf->Cell(90,8,'Safe Site Work Observation Sheet',0,0,'C');

$pdf->Ln(10);
$pdf->SetFont('Arial','B',8);
$pdf->SetFillColor(22,2,100);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(25,6,'Job Number',1,0,'L',true);

$pdf->SetFont('Arial','B',8);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(40,6,$proj_id,1,0,'L',true);

$pdf->SetFont('Arial','B',8);
$pdf->SetFillColor(22,2,100);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(25,6,'Client/Job',1,0,'L',true);

$pdf->SetFont('Arial','B',8);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(100,6,$compname,1,1,'L',true);

$pdf->SetFont('Arial','B',8);
$pdf->SetFillColor(22,2,100);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(25,6,'Leading Hand',1,0,'L',true);

$pdf->SetFont('Arial','B',8);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(40,6,$leading_hand,1,0,'L',true);

$pdf->SetFont('Arial','B',8);
$pdf->SetFillColor(22,2,100);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(25,6,'Site Address',1,0,'L',true);

$pdf->SetFont('Arial','B',8);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(100,6,$site_address,1,1,'L',true);

$pdf->SetFont('Arial','B',8);
$pdf->SetFillColor(22,2,100);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(25,6,'Date',1,0,'L',true);

$pdf->SetFont('Arial','B',8);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(40,6,$date_send,1,0,'L',true);

$pdf->SetFont('Arial','B',8);
$pdf->SetFillColor(22,2,100);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(25,6,'Time',1,0,'L',true);

$pdf->SetFont('Arial','B',8);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(100,6,$time_send,1,1,'L',true);

$pdf->Ln(3);

$pdf->SetFont('Arial','B',8);
$pdf->SetFillColor(22,2,100);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(70,6,'',1,0,'L',true);

$pdf->Cell(10,6,'Yes',1,0,'C',true);
$pdf->Cell(10,6,'No',1,0,'C',true);
$pdf->Cell(10,6,'N/A',1,0,'C',true);

$pdf->Cell(90,6,'Comments',1,1,'C',true);

$pdf->SetFont('Arial','B',8);
$pdf->SetFillColor(6,137,176);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(70,6,'Documentation - is there Evidence of:',1,0,'L',true);

$pdf->Cell(10,6,'',1,0,'C',true);
$pdf->Cell(10,6,'',1,0,'C',true);
$pdf->Cell(10,6,'',1,0,'C',true);

$pdf->Cell(90,6,'',1,1,'L',true);

$pdf->SetFont('Arial','B',8);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(70,10,'      Focus Site Daily Sign In/Out Register',1,0,'T',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);

$pdf->Cell(90,10,'',1,0,'L',true);
$pdf->Cell(-90,10);
$str_len = strlen($row['daily_sign_in_comments']);
$pdf->MultiCell(90,5,$row['daily_sign_in_comments'],0,'L');
if($str_len < 55){
	$pdf->ln(5);
}

$pdf->Cell(70,10,'      Focus SWMS onsite and signed',1,0,'L',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);

$pdf->Cell(90,10,'',1,0,'L',true);
$pdf->Cell(-90,10);
$str_len = strlen($row['focus_swms_onsite_signed_comments']);
$pdf->MultiCell(90,5,$row['focus_swms_onsite_signed_comments'],0,'L');
if($str_len < 55){
	$pdf->ln(5);
}

$pdf->Cell(70,10,'      Contractor SWMS onsite and signed',1,0,'L',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);

$pdf->Cell(90,10,'',1,0,'L',true);
$pdf->Cell(-90,10);
$str_len = strlen($row['swms_signed_comments']);
$pdf->MultiCell(90,5,$row['swms_signed_comments'],0,'L');
if($str_len < 55){
	$pdf->ln(5);
}

$pdf->Cell(70,10,'      Induction Toolbox Talks or site inductions',1,0,'L',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);

$pdf->Cell(90,10,'',1,0,'L',true);
$pdf->Cell(-90,10);
$str_len = strlen($row['site_inductions_comments']);
$pdf->MultiCell(90,5,$row['site_inductions_comments'],0,'L');
if($str_len < 55){
	$pdf->ln(5);
}

$pdf->Cell(70,10,'      Toolbox Talks',1,0,'L',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);

$pdf->Cell(90,10,'',1,0,'L',true);
$pdf->Cell(-90,10);
$str_len = strlen($row['toolbox_talks_comments']);
$pdf->MultiCell(90,5,$row['toolbox_talks_comments'],0,'L');
if($str_len < 55){
	$pdf->ln(5);
}

$pdf->Cell(70,10,'      Incident and Hazard Report Forms onsite',1,0,'L',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);

$pdf->Cell(90,10,'',1,0,'L',true);
$pdf->Cell(-90,10);
$str_len = strlen($row['incident_form_onsite_comments']);
$pdf->MultiCell(90,5,$row['incident_form_onsite_comments'],0,'L');
if($str_len < 55){
	$pdf->ln(5);
}

$pdf->Cell(70,10,'      Site Contact Poster Displayed',1,0,'L',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);

$pdf->Cell(90,10,'',1,0,'L',true);
$pdf->Cell(-90,10);
$str_len = strlen($row['site_contact_displayed_comments']);
$pdf->MultiCell(90,5,$row['site_contact_displayed_comments'],0,'L');
if($str_len < 55){
	$pdf->ln(5);
}

$pdf->Cell(70,10,'      Nearest Medical Centre, location & hours',1,0,'L',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);

$pdf->Cell(90,10,'',1,0,'L',true);
$pdf->Cell(-90,10);
$str_len = strlen($row['nearest_medical_loc_comments']);
$pdf->MultiCell(90,5,$row['nearest_medical_loc_comments'],0,'L');
if($str_len < 55){
	$pdf->ln(5);
}

$pdf->Cell(70,10,'      A First Aid Kit oniste',1,0,'L',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);

$pdf->Cell(90,10,'',1,0,'L',true);
$pdf->Cell(-90,10);
$str_len = strlen($row['nearest_medical_loc_comments']);
$pdf->MultiCell(90,5,$row['firstaid_kit_onsite_comments'],0,'L');
if($str_len < 55){
	$pdf->ln(5);
}

$pdf->Cell(70,10,'      Evacuation Area Identified',1,0,'L',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);

$pdf->Cell(90,10,'',1,0,'L',true);
$pdf->Cell(-90,10);
$str_len = strlen($row['evac_area_identified_comments']);
$pdf->MultiCell(90,5,$row['evac_area_identified_comments'],0,'L');
if($str_len < 55){
	$pdf->ln(5);
}


$pdf->Cell(70,10,'      Site specific hazards Being Identified',1,0,'L',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);

$pdf->Cell(90,10,'',1,0,'L',true);
$pdf->Cell(-90,10);
$str_len = strlen($row['hazards_identified_comments']);
$pdf->MultiCell(90,5,$row['hazards_identified_comments'],0,'L');
if($str_len < 55){
	$pdf->ln(5);
}

$pdf->SetFillColor(6,137,176);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(70,6,'Site Safety - Work Practices:',1,0,'L',true);

$pdf->Cell(10,6,'',1,0,'C',true);
$pdf->Cell(10,6,'',1,0,'C',true);
$pdf->Cell(10,6,'',1,0,'C',true);

$pdf->Cell(90,6,'',1,1,'L',true);

$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(70,10,'      Is the site clean and tidy?',1,0,'L',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);

$pdf->Cell(90,10,'',1,0,'L',true);
$pdf->Cell(-90,10);
$str_len = strlen($row['clean_tidy_comments']);
$pdf->MultiCell(90,5,$row['clean_tidy_comments'],0,'L');
if($str_len < 55){
	$pdf->ln(5);
}

$pdf->Cell(70,10,'      Is there adequate lighting in the work place?',1,0,'L',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);

$pdf->Cell(90,10,'',1,0,'L',true);
$pdf->Cell(-90,10);
$str_len = strlen($row['adequate_lighting_comments']);
$pdf->MultiCell(90,5,$row['adequate_lighting_comments'],0,'L');
if($str_len < 55){
	$pdf->ln(5);
}

$pdf->Cell(70,10,'',1,0,'L',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);

$pdf->Cell(90,10,'',1,0,'L',true);
$pdf->Cell(-90,10);
$str_len = strlen($row['electric_equipment_tagged_comments']);
$pdf->MultiCell(90,5,$row['electric_equipment_tagged_comments'],0,'L');
if($str_len < 55){
	$pdf->ln(5);
}

$pdf->ln(-9);
$pdf->Cell(70,4,'      Is Portable Electrical Equipment being used',0,1,'L',true);
$pdf->Cell(70,4,'      carrying a current electrical tag?',0,1,'L',true);

$pdf->Rect(10, 204, 70, 9, 'D');

$pdf->Cell(70,10,'      Are ladders being used correctly?',1,0,'L',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);

$pdf->Cell(90,10,'',1,0,'L',true);
$pdf->Cell(-90,10);
$str_len = strlen($row['ladders_used_correctly_comments']);
$pdf->MultiCell(90,5,$row['ladders_used_correctly_comments'],0,'L');
if($str_len < 55){
	$pdf->ln(5);
}

$pdf->Cell(70,10,'      Are scaffolds being used correctly?',1,0,'L',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);

$pdf->Cell(90,10,'',1,0,'L',true);
$pdf->Cell(-90,10);
$str_len = strlen($row['scaffolds_used_correctly_comments']);
$pdf->MultiCell(90,5,$row['scaffolds_used_correctly_comments'],0,'L');
if($str_len < 55){
	$pdf->ln(5);
}

$pdf->Cell(70,10,'',1,0,'L',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);

$pdf->Cell(90,10,'',1,0,'L',true);
$pdf->Cell(-90,10);
$str_len = strlen($row['ppe_being_worn_comments']);
$pdf->MultiCell(90,5,$row['ppe_being_worn_comments'],0,'L');
if($str_len < 55){
	$pdf->ln(5);
}

$pdf->ln(-9);
$pdf->Cell(70,4,'      Is the appropriate Personal Protective',0,1,'L',true);
$pdf->Cell(70,4,'      Equipment (PPE) being worn?',0,1,'L',true);

$pdf->Rect(10, 233, 70, 9, 'D');

$pdf->Cell(70,10,'      Is Plant and Equipment bing used correctly?',1,0,'L',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);
$pdf->Cell(10,10,'',1,0,'C',true);

$pdf->Cell(90,10,'',1,0,'L',true);
$pdf->Cell(-90,10);
$str_len = strlen($row['equip_correctly_used_comments']);
$pdf->MultiCell(90,5,$row['equip_correctly_used_comments'],0,'L');
if($str_len < 55){
	$pdf->ln(5);
}






$pdf->SetFillColor(6,137,176);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(70,6,'Comments / Action Required:',1,0,'L',true);

$pdf->Cell(10,6,'',1,0,'C',true);
$pdf->Cell(10,6,'',1,0,'C',true);
$pdf->Cell(10,6,'',1,0,'C',true);

$pdf->Cell(90,6,'',1,1,'L',true);

$pdf->SetFont('Arial','B',8);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(190,12,'',1,0,'L',true);
$pdf->Cell(-185);
$pdf->MultiCell(185,5,$row['comments_actions_required']);


//Checkboxes
switch($row['daily_signin_out_reg']){
	case 0:
		$pdf->Image('./img/check-red.png',93,71,4); // No
		break;
	case 1:
		$pdf->Image('./img/check.png',83,71,4); // yes
		break;
	case 2:
		$pdf->Image('./img/check.png',103,71,4); // N/A
		break;
}

switch($row['focus_swms_onsite_signed']){
	case 0:
		$pdf->Image('./img/check-red.png',93,81,4); // No
		break;
	case 1:
		$pdf->Image('./img/check.png',83,81,4); // yes
		break;
	case 2:
		$pdf->Image('./img/check.png',103,81,4); // N/A
		break;
}

switch($row['swms_onsite_signed']){
	case 0:
		$pdf->Image('./img/check-red.png',93,91,4); // No
		break;
	case 1:
		$pdf->Image('./img/check.png',83,91,4); // yes
		break;
	case 2:
		$pdf->Image('./img/check.png',103,91,4); // N/A
		break;
}

switch($row['site_inductions']){
	case 0:
		$pdf->Image('./img/check-red.png',93,101,4); // No
		break;
	case 1:
		$pdf->Image('./img/check.png',83,101,4); // yes
		break;
	case 2:
		$pdf->Image('./img/check.png',103,101,4); // N/A
		break;
}

switch($row['toolbox_talks']){
	case 0:
		$pdf->Image('./img/check-red.png',93,111,4); // No
		break;
	case 1:
		$pdf->Image('./img/check.png',83,111,4); // yes
		break;
	case 2:
		$pdf->Image('./img/check.png',103,111,4); // N/A
		break;
}

switch($row['incident_form_onsite']){
	case 0:
		$pdf->Image('./img/check-red.png',93,121,4); // No
		break;
	case 1:
		$pdf->Image('./img/check.png',83,121,4); // yes
		break;
	case 2:
		$pdf->Image('./img/check.png',103,121,4); // N/A
		break;
}

switch($row['site_contact_displayed']){
	case 0:
		$pdf->Image('./img/check-red.png',93,131,4); // No
		break;
	case 1:
		$pdf->Image('./img/check.png',83,131,4); // yes
		break;
	case 2:
		$pdf->Image('./img/check.png',103,131,4); // N/A
		break;
}

switch($row['nearest_medical_loc']){
	case 0:
		$pdf->Image('./img/check-red.png',93,141,4); // No
		break;
	case 1:
		$pdf->Image('./img/check.png',83,141,4); // yes
		break;
	case 2:
		$pdf->Image('./img/check.png',103,141,4); // N/A
		break;
}

switch($row['firstaid_kit_onsite']){
	case 0:
		$pdf->Image('./img/check-red.png',93,151,4); // No
		break;
	case 1:
		$pdf->Image('./img/check.png',83,151,4); // yes
		break;
	case 2:
		$pdf->Image('./img/check.png',103,151,4); // N/A
		break;
}

switch($row['evac_area_identified']){
	case 0:
		$pdf->Image('./img/check-red.png',93,161,4); // No
		break;
	case 1:
		$pdf->Image('./img/check.png',83,161,4); // yes
		break;
	case 2:
		$pdf->Image('./img/check.png',103,161,4); // N/A
		break;
}

switch($row['hazards_identified']){
	case 0:
		$pdf->Image('./img/check-red.png',93,171,4); // No
		break;
	case 1:
		$pdf->Image('./img/check.png',83,171,4); // yes
		break;
	case 2:
		$pdf->Image('./img/check.png',103,171,4); // N/A
		break;
}

switch($row['clean_tidy']){
	case 0:
		$pdf->Image('./img/check-red.png',93,187,4); // No
		break;
	case 1:
		$pdf->Image('./img/check.png',83,187,4); // yes
		break;
	case 2:
		$pdf->Image('./img/check.png',103,187,4); // N/A
		break;
}

switch($row['adequate_lighting']){
	case 0:
		$pdf->Image('./img/check-red.png',93,197,4); // No
		break;
	case 1:
		$pdf->Image('./img/check.png',83,197,4); // yes
		break;
	case 2:
		$pdf->Image('./img/check.png',103,197,4); // N/A
		break;
}

switch($row['electric_equipment_tagged']){
	case 0:
		$pdf->Image('./img/check-red.png',93,206,4); // No
		break;
	case 1:
		$pdf->Image('./img/check.png',83,206,4); // yes
		break;
	case 2:
		$pdf->Image('./img/check.png',103,206,4); // N/A
		break;
}

switch($row['ladders_used_correctly']){
	case 0:
		$pdf->Image('./img/check-red.png',93,216,4); // No
		break;
	case 1:
		$pdf->Image('./img/check.png',83,216,4); // yes
		break;
	case 2:
		$pdf->Image('./img/check.png',103,216,4); // N/A
		break;
}

switch($row['scaffolds_used_correctly']){
	case 0:
		$pdf->Image('./img/check-red.png',93,226,4); // No
		break;
	case 1:
		$pdf->Image('./img/check.png',83,226,4); // yes
		break;
	case 2:
		$pdf->Image('./img/check.png',103,226,4); // N/A
		break;
}

switch($row['ppe_being_worn']){
	case 0:
		$pdf->Image('./img/check-red.png',93,235,4); // No
		break;
	case 1:
		$pdf->Image('./img/check.png',83,235,4); // yes
		break;
	case 2:
		$pdf->Image('./img/check.png',103,235,4); // N/A
		break;
}

switch($row['equip_correctly_used']){
	case 0:
		$pdf->Image('./img/check-red.png',93,245,4); // No
		break;
	case 1:
		$pdf->Image('./img/check.png',83,245,4); // yes
		break;
	case 2:
		$pdf->Image('./img/check.png',103,245,4); // N/A
		break;
}


if (!file_exists($filepath)) {
    mkdir($filepath, 0755, true);
}

$pdf->Output($filepath.$proj_id.'_safety_site_observation.pdf','F');
$pdf->Output($proj_id.'_safety_site_observation.pdf','I');
}
?>