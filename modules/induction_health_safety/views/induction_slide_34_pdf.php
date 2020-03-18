<?php
foreach ($induction_slide_q->result_array() as $row){
    $project_id = $row['project_id'];
    $project_name = $row['project_name'];
    $client_logo = '.'.$row['logo_path'];
    $unit_level = $row['unit_level'];
    $unit_number = $row['unit_number'];
    $street = $row['street'];
    $po_box = $row['po_box'];
    $suburb = $row['suburb'];
    $name = $row['name'];
    $postcode = $row['postcode'];

    $address = $unit_level." ".$unit_number." ".$street." ".$po_box." ".$suburb." ".$name." ".$postcode;

    $date_site_commencement = $row['date_site_commencement'];
    $date_site_finish = $row['date_site_finish'];

    $pm_name = $row['pm_name'];
    $pm_mobile_number = $row['pm_mobile_number'];
    $pm_email = $row['pm_email'];

    $lh_name = $row['lh_name'];
    $lh_mobile_number = $row['lh_mobile_number'];
    $lh_email = $row['lh_email'];
    $manual_lh = $row['manual_lh'];
    $manual_lh_contact = $row['manual_lh_contact'];
    $manual_lh_email = $row['manual_lh_email'];

    $project_ouline_text = $row['project_ouline_text'];


    $acces_map_filename = "uploads/project_inductions_images/".$project_id."/".$row['acces_map_filename'];

    $amenities_map_filename = "./uploads/project_inductions_images/".$project_id."/".$row['amenities_map_filename'];

    $extension_name_arr = explode(".",$amenities_map_filename);

    $extension_name = $extension_name_arr[2];


    $epr_medical_name = $row['epr_medical_name'];
    $epr_medical_contact = $row['epr_medical_contact'];
    $epr_medical_address = $row['epr_medical_address'];
    $epr_emergency_name = $row['epr_emergency_name'];
    $epr_emergency_contacts = $row['epr_emergency_contacts'];
    $epr_emergency_address = $row['epr_emergency_address'];

    $ppe_list = $row['ppe_list'];
    $ppe_list = str_replace('"','',$ppe_list);
    $ppe_list = str_replace('[','',$ppe_list);
    $ppe_list = str_replace(']','',$ppe_list);
}
switch($slide_no){
    case '1':
        $img_path = "./uploads/project_induction_template/site_details.jpg";
        break;
    case '2':
        $img_path = "./uploads/project_induction_template/project_outline.jpg";
        break;
    case '3':
        $img_path = "./uploads/project_induction_template/site_access.jpg";
        break;
    case '4':
        $img_path = "./uploads/project_induction_template/amenities_emergency_exits.jpg";
        break;
    case '5':
        $img_path = "./uploads/project_induction_template/emergency_preparedness_response.jpg";
        break;
    case '6':
        $img_path = "./uploads/project_induction_template/personal_protective_equipment.jpg";
        break;
}

require_once('fpdf/fpdf.php');
require_once('fpdf/fpdi/fpdi.php');
class PDF extends FPDI
{
  // Simple table
    // function BasicTable($header, $data)
    // {
    //     // Header
    //     foreach($header as $col)
    //         $this->Cell(40,7,$col,1);
    //     $this->Ln();
    //     // Data
    //     foreach($data as $row)
    //     {
    //         foreach($row as $col)
    //             $this->Cell(40,6,$col,1);
    //         $this->Ln();
    //     }
    // }
    function SetLogo($img_path) 
    { 
      $this->img_path = $img_path;  
    } 
    //Page header
    function Header()
    { 
        $this->Image($this->img_path,0,0);
        
    }

    //Page footer
    // function Footer()
    // {
    //     global $date; 
    //     $date = date('d M Y');
    //     //Position at 1.5 cm from bottom
    //     $this->SetY(-60);
    //     //Arial italic 8
    //     $this->SetFillColor(139,137,137);
    //     $this->Cell(0,1,'',1,0,'',true);
    //     $this->Ln(5);
    //     $this->Cell(190,35,'',1,0,'C'); // border
    //     $this->Ln(-2);
    //     $this->Cell(2);
    //     $this->SetFont('Arial','B',10);
    //     $this->SetFillColor(255,255,255);
    //     $this->Cell(55,5,"To be completed by tenderer",0,0,"C",true);
    //     $this->Ln(5);
    //     $this->Cell(5);
    //     $this->Cell(55,5,"We ".$this->work_company_name.",( ABN:".$this->abn." ),",0,0);
      
    //     $this->Ln(5);
    //     $this->Cell(5);
    //     $this->Cell(80,5,"submit our quotation of $ ",0,0);
    //     $this->line(60,254,95,254);
    //     $this->Cell(55,5,"being for the abovementioned works including GST ",0,0);
    //     $this->Ln(5);
    //     $this->Cell(5);
    //     $this->Cell(55,5,"Comments: ",0,0);
    //     $this->line(35,259,195,259);
    //     $this->Ln(5);
    //     $this->Cell(5);
    //     $this->Cell(55,5,"",0,0);
    //     $this->line(15,264,195,264);
    //     $this->Ln(5);
    //     $this->Cell(5);
    //     $this->Cell(55,5,"",0,0);
    //     $this->line(15,269,195,269);
    //     $this->Ln(5);
    //     $this->Cell(5);
    //     $this->Cell(55,5,"Signed ______________________________________ for and on behalf of the tenderer",0,0);
    //     $this->Ln(8);
    //     $this->Cell(190,10,'',1,0,'C'); // border
    //     $this->Ln(2);
    //     $this->Cell(2);
    //     $this->SetFont('Arial','B',10);
    //     if($this->insurance_stat == 0){
    //       $this->SetTextColor(255,0,0);
    //     }
    //     $this->MultiCell(185,5,$this->cqr_notes_insurance,0,'C');
    //     //Page number
    //     $this->Ln(1);
    //     $this->SetFont('Arial','I',8);
    //     $this->SetTextColor(0,0,0);
    //     $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    //     $this->SetFont("times","",11); 
    //     $this->Cell(-40, 10, "Date Printed: ".$date, 0, 1, "R", 0); 
    // }
}
//Instanciation of inherited class
$pdf = new PDF('L','mm',array(508,285.75));
$pdf->SetLogo($img_path);
$pdf->SetAutoPageBreak(true,60);

$pdffile = $acces_map_filename;
$pagecount = $pdf->setSourceFile($pdffile);  
for($i=0; $i<$pagecount; $i++){
    $pdf->AddPage();  
    $tplidx = $pdf->importPage($i+1, '/MediaBox');
    $pdf->useTemplate($tplidx, 130, 5, 370,280); 
}

// $pdf->Image($acces_map_filename,150,10, 345, 220);

// $pdf->Ln(160);
// $pdf->SetFont('Arial','B',25);
// $pdf->Cell(5,10,'');
// $pdf->Cell(60,20,'General:');

// $pdf->SetFont('Arial','',25);
// $pdf->Cell( 200, 20, '7am to 5pm');

// $pdf->Ln(15);
// $pdf->SetFont('Arial','B',25);
// $pdf->Cell(5,10,'');
// $pdf->Cell(60,20,'Noisy Works:');

// $pdf->SetFont('Arial','',25);
// $pdf->Cell( 200, 20, 'After Hours');

// $pdf->Ln(15);
// $pdf->SetFont('Arial','B',25);
// $pdf->Cell(5,10,'');
// $pdf->Cell(60,20,'Others:');

// $pdf->SetFont('Arial','',25);
// $pdf->Cell( 200, 20, '7pm to 9pm');

// if (!file_exists($filepath)) {
//     mkdir($filepath, 0755, true);
// }
$pdf->Output();

?>