<?php


// $pdf=new PDF_TextBox();
// $pdf->AddPage();
// $pdf->SetFont('Arial','',15);
// $pdf->SetXY(0,0);
// $pdf->drawTextBox('This sentence is centered in the middle of the box.', 100, 50, 'C', 'M');
// $pdf->Output();

// drawTextBox(string strText, float w, float h [, string align [, string valign [, boolean border]]])
// strText: the string to print
// w: width of the box
// h: height of the box
// align: horizontal alignment (L, C, R or J). Default value: L
// valign: vertical alignment (T, M or B). Default value: T
// border: whether to draw the border of the box. Default value: true

?>
<?php 

require_once('fpdf/fpdf.php');
require_once('fpdf/fpdi/fpdi.php');
//require('fpdf/textbox/textbox.php');

foreach ($induction_slide_q->result_array() as $row){
	$project_id = $row['project_id'];
	$project_name = $row['project_name'];
	$brand_id = $row['brand_id'];
	$has_brand_logo = $row['has_brand_logo'];
	$brand_logo = "./uploads/brand_logo/".$brand_id.".jpg";
	$unit_level = $row['unit_level'];
	$unit_number = $row['unit_number'];
	$street = $row['street'];
	$po_box = $row['po_box'];
	$suburb = $row['suburb'];
	$name = $row['name'];
	$postcode = $row['postcode'];

	
	if($row['job_type']=='Shopping Center'){
		$tenancy_number = $row['shop_tenancy_number'];
		$common_name = $row['common_name'];
		// $address = $tenancy_number." ".$common_name." ".$street." ".$po_box." ".$suburb." ".$name." ".$postcode;
		if($unit_number == ''){
			$address = $tenancy_number." ".$common_name." ".$street." ".$po_box." ".$suburb." ".$name." ".$postcode;

		}else{
			$address = $tenancy_number." ".$common_name." ".$unit_number." ".$street." ".$po_box." ".$suburb." ".$name." ".$postcode;
		}
	}else{
		$address = $unit_level." ".$unit_number." ".$street." ".$po_box." ".$suburb." ".$name." ".$postcode;
	}
	

	$date_site_commencement = $row['date_site_commencement'];
	$date_site_finish = $row['date_site_finish'];

	$pm_name = $row['pm_name'];
	$pm_mobile_number = $row['pm_mobile_number'];
	$pm_email = $row['pm_email'];

	
	if($row['lh_name'] == null){
		$lh_name = "";
	}else{
		$lh_name = $row['lh_name'];
	}
	$lh_mobile_number = $row['lh_mobile_number'];
	$lh_email = $row['lh_email'];

	if($row['manual_lh'] == null){
		$manual_lh = "";
	}else{
		$manual_lh = $row['manual_lh'];
	}
	
	$manual_lh_contact = $row['manual_lh_contact'];
	$manual_lh_email = $row['manual_lh_email'];

	$project_ouline_text = $row['project_ouline_text'];


	if($slide_no == '3'){
		$acces_map_filename = "./uploads/project_inductions_images/".$project_id."/".$row['acces_map_filename'];
		$access_ext_arr =  explode('.', $acces_map_filename); 
		$access_ext = $access_ext_arr[2];
	}
	

	$general_site_hours = $row['general_site_hours'];
	$noisy_site_hours = $row['noisy_site_hours'];
	$other_site_hours = $row['other_site_hours'];

	if($slide_no == '4'){

		$amenities_map_filename = "./uploads/project_inductions_images/".$project_id."/".$row['amenities_map_filename'];

		$amenities_ext_arr = explode(".",$amenities_map_filename);

		$amenities_ext = $amenities_ext_arr[2];
	}

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
		$file_name = "slide1";
		$img_path = "./uploads/project_induction_template/site_details.jpg";
		break;
	case '2':
		$file_name = "slide2";
		$img_path = "./uploads/project_induction_template/project_outline.jpg";
		break;
	case '3':
		$file_name = "slide3";
		$img_path = "./uploads/project_induction_template/site_access.jpg";
		break;
	case '4':
		$file_name = "slide4";
		$img_path = "./uploads/project_induction_template/amenities_emergency_exits.jpg";
		break;
	case '5':
		$file_name = "slide5";
		$img_path = "./uploads/project_induction_template/emergency_preparedness_response.jpg";
		break;
	case '6':
		$file_name = "slide6";
		$img_path = "./uploads/project_induction_template/personal_protective_equipment.jpg";
		break;
}

// new FPDF('L','mm',array(508,285.75));

// $pdf = new PDF_TextBox();
$pdf = new Fpdi('L','mm',array(677.33,381));
	// const DPI = 96;
 //    const MM_IN_INCH = 25.4;
 //    const A4_HEIGHT = 297;
 //    const A4_HEIGHT_HALF = 148.5;
 //    const A4_WIDTH = 210;
 //    // tweak these values (in pixels)
 //    const MAX_WIDTH = 400;
 //    const MAX_HEIGHT = 400;

 //    const MAX_WIDTH_centre = 530;
 //    const MAX_HEIGHT_centre = 400;

 //    const MAX_WIDTH_logo = 400;
 //    const MAX_HEIGHT_logo = 200;

 //    function pixelsToMM($val) {
 //        return $val * self::MM_IN_INCH / self::DPI;
 //    }

 //    function resizeToFit($imgFilename) {
 //        list($width, $height) = getimagesize($imgFilename);
 //        $widthScale = self::MAX_WIDTH / $width;
 //        $heightScale = self::MAX_HEIGHT / $height;
 //        $scale = min($widthScale, $heightScale);
 //        return array(
 //            round($this->pixelsToMM($scale * $width)),
 //            round($this->pixelsToMM($scale * $height))
 //        );
 //    }

// $pagecount = $pdf->setSourceFile($amenities_map_filename);  
// for($i=0; $i<$pagecount; $i++){
// 	$pdf->AddPage();  
// 	$tplidx = $pdf->importPage($i+1, '/MediaBox');
// 	$pdf->useTemplate($tplidx, 10, 10, 200); 
// }

switch($slide_no){
	case '1':
		$pdf->AddPage();
		$pdf->Image($img_path,0,0,675);
		
		$pdf->Image($brand_logo,450,20, 200);

		$pdf->Ln(20);

		$pdf->SetFont('Arial','B',60);
		$pdf->Cell(20,10,'');
		$pdf->MultiCell(400,20,$project_id.'-'.$project_name);
		
		$pdf->Ln(20);
		$pdf->SetFont('Arial','B',40);
		$pdf->Cell(20,10,'');
		$pdf->Cell(160,20,'Site Address:');

		$pdf->SetFont('Arial','',40);
		$pdf->MultiCell(200, 20, $address,0,'L');

		$pdf->Ln(1);

		$pdf->SetFont('Arial','B',40);
		$pdf->Cell(20,10,'');
		$pdf->Cell(160,20,'Commencement Date:');

		$pdf->SetFont('Arial','',40);
		$pdf->Cell( 200, 20, $date_site_commencement);

		$pdf->Ln(20);

		$pdf->SetFont('Arial','B',40);
		$pdf->Cell(20,10,'');
		$pdf->Cell(160,20,'Completion Date:');

		$pdf->SetFont('Arial','',40);
		$pdf->Cell( 200, 20, $date_site_finish);

		$pdf->Ln(20);

		$pdf->SetFont('Arial','B',40);
		$pdf->Cell(20,10,'');
		$pdf->Cell(160,20,'Project Manager:');

		$pdf->SetFont('Arial','',40);
		$pdf->Cell( 200, 20, $pm_name);

		$pdf->Ln(20);

		$pdf->Cell(20,10,'');
		$pdf->Cell(160,20,'');

		$pdf->SetFont('Arial','',40);
		$pdf->Cell( 100, 20, $pm_mobile_number,0,2);
		$pdf->Cell( 200, 20, $pm_email);

		if($lh_name !== ""){
			$pdf->Ln(20);

			$pdf->SetFont('Arial','B',40);
			$pdf->Cell(20,10,'');
			$pdf->Cell(160,20,'Leading Hand:');

			$pdf->SetFont('Arial','',40);
			$pdf->Cell( 200, 20, $lh_name);

			$pdf->Ln(20);

			$pdf->Cell(20,10,'');
			$pdf->Cell(160,20,'');

			$pdf->SetFont('Arial','',40);
			$pdf->Cell( 100, 20, $lh_mobile_number,0,2);
			$pdf->SetFont('Arial','',40);
			$pdf->Cell( 200, 20, $lh_email);

		}else{
			if($manual_lh !== ""){
				$pdf->Ln(20);

				$pdf->SetFont('Arial','B',40);
				$pdf->Cell(20,10,'');
				$pdf->Cell(160,20,'Leading Hand:');

				$pdf->SetFont('Arial','',40);
				$pdf->Cell( 200, 20, $manual_lh);

				$pdf->Ln(20);

				$pdf->Cell(20,10,'');
				$pdf->Cell(160,20,'');

				$pdf->SetFont('Arial','',40);
				$pdf->Cell( 100, 20, $manual_lh_contact,0,2);
				$pdf->Cell( 200, 20, $manual_lh_email);
			}
		}


		break;
	case '2':
		$pdf->AddPage();
		$pdf->SetMargins(30, 30, 30);
		$pdf->Image($img_path,0,0,675);
		$pdf->Ln(40);

		$pdf->SetFont('Arial','',45);
		$pdf->Cell(20,10,'');
		$pdf->SetXY(100,20);
		$pdf->WriteHTML($project_ouline_text, 630, 200, 'J', 'M',false);
		break;
	case '3':
		
		if($access_ext == 'pdf'){
			$pdffile = $acces_map_filename;
			$pagecount = $pdf->setSourceFile($pdffile);  
			for($i=0; $i<$pagecount; $i++){
			    $pdf->AddPage();  
			    $pdf->Image($img_path,0,0,675);
			    $tplidx = $pdf->importPage($i+1, '/MediaBox');
//==========
			    $size = $pdf->getTemplateSize($tplidx);
				$w = round($size['w']);
				$h = round($size['h']);


				if($h > $w){
					$height = 300;
					$width = $height * ($h / $w);

					if($width > 480){
						$width = 480;
						$pdf->useTemplate($tplidx, 180, 5, $width); 
					}else{
						//$margdiff = 480 - $width;
						$margdiff = 700 - $width;
						$margin = $margdiff / 2;
						$margin = 180 + $margin;
						$pdf->useTemplate($tplidx, $margin, 5, 0, $height); 
					}
					
				}else{
					$width = 480;
					$height = $width * ($h / $w);

					if($height > 300){
						$height = 300;
						$width = $height * ($w / $h);

						$margdiff = 480 - $width;
						$margin = $margdiff / 2;
						$margin = 180 + $margin;
							
						$pdf->useTemplate($tplidx, $margin, 5, 0, $height); 
						
					}else{
						$pdf->useTemplate($tplidx, 180, 5, $width); 
					}
					
				}
//==========			    


			    //$pdf->useTemplate($tplidx, 180, 5, 480,300,true); 

			    $pdf->Ln(220);
				$pdf->SetFont('Arial','B',30);
				$pdf->Cell(5,10,'');
				$pdf->Cell(80,20,'General:');

				$pdf->Ln(15);
				$pdf->Cell(20,10,'');
				$pdf->SetFont('Arial','',30);
				$pdf->Cell( 200, 20, $general_site_hours);

				$pdf->Ln(15);
				$pdf->SetFont('Arial','B',30);
				$pdf->Cell(5,10,'');
				$pdf->Cell(80,20,'Noisy Works:');

				$pdf->Ln(15);
				$pdf->Cell(20,10,'');
				$pdf->SetFont('Arial','',30);
				$pdf->Cell( 200, 20, $noisy_site_hours );

				$pdf->Ln(15);
				$pdf->SetFont('Arial','B',30);
				$pdf->Cell(5,10,'');
				$pdf->Cell(80,20,'Others:');

				$pdf->Ln(15);
				$pdf->Cell(20,10,'');
				$pdf->SetFont('Arial','',30);
				$pdf->Cell( 200, 20, $other_site_hours);
			}
		}else{
			$pdf->AddPage();
			$pdf->Image($img_path,0,0,675);
			$pdf->Image($acces_map_filename,170,10, 500, 300);

			$pdf->Ln(220);
			$pdf->SetFont('Arial','B',30);
			$pdf->Cell(5,10,'');
			$pdf->Cell(80,20,'General:');

			$pdf->Ln(15);
			$pdf->Cell(20,10,'');
			$pdf->SetFont('Arial','',30);
			$pdf->Cell( 200, 20, $general_site_hours);

			$pdf->Ln(15);
			$pdf->SetFont('Arial','B',30);
			$pdf->Cell(5,10,'');
			$pdf->Cell(80,20,'Noisy Works:');

			$pdf->Ln(15);
			$pdf->Cell(20,10,'');
			$pdf->SetFont('Arial','',30);
			$pdf->Cell( 200, 20, $noisy_site_hours );

			$pdf->Ln(15);
			$pdf->SetFont('Arial','B',30);
			$pdf->Cell(5,10,'');
			$pdf->Cell(80,20,'Others:');

			$pdf->Ln(15);
			$pdf->Cell(20,10,'');
			$pdf->SetFont('Arial','',30);
			$pdf->Cell( 200, 20, $other_site_hours);
		}
		
		
		break;
	case '4':

		if($amenities_ext == 'pdf'){
			$pdffile = $amenities_map_filename;
			$pagecount = $pdf->setSourceFile($pdffile);  
			for($i=0; $i<$pagecount; $i++){
			    $pdf->AddPage(); 
			    $pdf->Image($img_path,0,0,675);
			    $tplidx = $pdf->importPage($i+1, '/MediaBox');
			   
//==========
			    $size = $pdf->getTemplateSize($tplidx);
				$w = round($size['w']);
				$h = round($size['h']);


				if($h > $w){
					$height = 240;
					$width = $height * ($h / $w);

					if($width > 530){
						$width = 530;
						$pdf->useTemplate($tplidx, 140, 50, $width); 
					}else{
						//$margdiff = 530 - $width;
						$margdiff = 700 - $width;
						$margin = $margdiff / 2;
						$margin = 140 + $margin;
						$pdf->useTemplate($tplidx, $margin, 50, 0, $height); 
					}
					
				}else{
					$width = 530;
					$height = $width * ($h / $w);

					if($height > 240){
						$height = 240;
						$width = $height * ($w / $h);

						$margdiff = 530 - $width;
						$margin = $margdiff / 2;
						$margin = 140 + $margin;
							
						$pdf->useTemplate($tplidx, $margin, 50, 0, $height); 
						
					}else{
						$pdf->useTemplate($tplidx, 140, 50, $width); 
					}
				}
			    
//==========
			    //$pdf->useTemplate($tplidx, 140, 50, 530,240); 
			    $pdf->Ln(286);

				$pdf->SetFont('Arial','B',30);
				$pdf->Cell(200,10,'');
				$pdf->SetTextColor(255,0,0);
				$pdf->Cell(150,20,'Please note: No food is to be consumed on-site other than in designated areas');
				$pdf->SetTextColor(0,0,0);
			}
		}else{
			$pdf->AddPage();
			$pdf->Image($img_path,0,0,675);
			$pdf->Image($amenities_map_filename,140,50, 540, 250);
			$pdf->Ln(286);

			$pdf->SetFont('Arial','B',30);
			$pdf->Cell(200,10,'');
			$pdf->SetTextColor(255,0,0);
			$pdf->Cell(150,20,'Please note: No food is to be consumed on-site other than in designated areas');
			$pdf->SetTextColor(0,0,0);
		}
		
		break;
	case '5':
		$pdf->AddPage();
		$pdf->Image($img_path,0,0,675);
		$pdf->Ln(50);

		$pdf->SetFont('Arial','B',40);
		$pdf->Cell(20,10,'');
		$pdf->Cell(150,20,'Closest Medical');

		$pdf->Ln(25);

		$pdf->SetFont('Arial','',40);
		$pdf->Cell(20,10,'');
		$pdf->Cell(150,20,'Name:');

		$pdf->Cell( 200, 20, $epr_medical_name);

		$pdf->Ln(25);

		$pdf->SetFont('Arial','',40);
		$pdf->Cell(20,10,'');
		$pdf->Cell(150,20,'Contact Number/s:');

		$pdf->Cell( 200, 20, $epr_medical_contact);

		$pdf->Ln(25);

		$pdf->SetFont('Arial','',40);
		$pdf->Cell(20,10,'');
		$pdf->Cell(150,20,'Address:');

		//$pdf->MultiCell( 200, 20, $address);
		$pdf->MultiCell( 400, 20, $epr_medical_address);

		$pdf->Ln(20);

		$pdf->SetFont('Arial','B',40);
		$pdf->Cell(20,10,'');
		$pdf->Cell(150,20,'Closest Emergency Hospital');

		$pdf->Ln(25);

		$pdf->SetFont('Arial','',40);
		$pdf->Cell(20,10,'');
		$pdf->Cell(150,20,'Name:');

		$pdf->Cell( 200, 20, $epr_emergency_name);

		$pdf->Ln(25);

		$pdf->SetFont('Arial','',40);
		$pdf->Cell(20,10,'');
		$pdf->Cell(150,20,'Contact Number/s:');

		$pdf->Cell( 200, 20, $epr_emergency_contacts);

		$pdf->Ln(25);

		$pdf->SetFont('Arial','',40);
		$pdf->Cell(20,10,'');
		$pdf->Cell(150,20,'Address:');

		$pdf->MultiCell( 400, 20, $epr_emergency_address);



		break;
	case '6':
		$pdf->AddPage();
		$pdf->Image($img_path,0,0,675);
		$pdf->SetFont('Arial','',30);
		
		$ppe_list_arr = explode(",",$ppe_list);
		$ppe_count = count($ppe_list_arr);

		if($ppe_count < 3 ){
			$pdf->Ln(100);
			$y = 110;
		}else{
			if($ppe_count < 5 && $ppe_count > 2){
				$pdf->Ln(90);
				$y = 100;
			}else{
				if($ppe_count < 7 && $ppe_count > 5){
					$pdf->Ln(80);
					$y = 90;
				}else{
					if($ppe_count < 9 && $ppe_count > 7){
						$pdf->Ln(60);
						$y = 70;
					}else{
						$pdf->Ln(70);
						$y = 75;
					}
				}
			}	
		}
		
		

		
		$loop_no = 0;
		$is_first_loop = 1;
		$x = 160;
		// $y = 110;
		//$y = 50;

		
		foreach ($ppe_list_arr as &$value) {

			switch($value){
				case 'Coveralls':
					$ico_path = './uploads/project_induction_template/ppe/coveralls.png';
					break;
				case 'Steel Capped Boots':
					$ico_path = './uploads/project_induction_template/ppe/steel_capped_boots.png';
					break;
				case 'Ear Muffs':
					$ico_path = './uploads/project_induction_template/ppe/ear_muffs.png';
					break;
				case 'Fall Arrest Harness':
					$ico_path = './uploads/project_induction_template/ppe/fall_arrest_harness.png';
					break;
				case 'Gloves':
					$ico_path = './uploads/project_induction_template/ppe/gloves.png';
					break;
				case 'Hard Hat':
					$ico_path = './uploads/project_induction_template/ppe/hard_hat.png';
					break;
				case 'High Vis Vest':
					$ico_path = './uploads/project_induction_template/ppe/high_vis_vest.png';
					break;
				case 'Protective Glasses':
					$ico_path = './uploads/project_induction_template/ppe/protective_glasses.png';
					break;
				case 'Respiratory':
					$ico_path = './uploads/project_induction_template/ppe/respiratory.png';
					break;

					
			}

			if($loop_no == 0){
				if($is_first_loop == 1){
					$is_first_loop = 0;
				}else{
					$x = 160;
					$y = $y + 30;
					$pdf->Ln(30);
				}
				
				$pdf->Cell(200,10,'');
				$pdf->Cell( 20, 20, $value);
				$loop_no++;
			}else{
				$x = 380;
				$pdf->Cell(200,10,'');
				$pdf->Cell( 20, 20, $value);
				$loop_no=0;
			}

			$pdf->Image($ico_path,$x,$y,'',30);
		}

		if($ppe_count < 3 ){
			$pdf->Ln(70);
		}else{
			if($ppe_count < 5 && $ppe_count > 2){
				$pdf->Ln(65);
			}else{
				if($ppe_count < 7 && $ppe_count > 5){
					$pdf->Ln(60);
				}else{
					if($ppe_count < 9 && $ppe_count > 7){
						$pdf->Ln(55);
					}else{
						$pdf->Ln(50);
					}
				}
			}	
		}
		//
		
		$pdf->SetFont('Arial','B',30);
		$pdf->Cell(150,10,'');
		$pdf->MultiCell(500,10,'Whilst Personal Protective Equipment & Clothing (PPE&C) is the lowest form of control it is accepted as a necessity.  PPE&C must be worn whenever required in order to reduce the possibility of injury.  It is an individual responsibility to ensure no work is undertaken without wearing the appropriate PPE&C.');
		

		break;
}

$filepath = "uploads/project_induction_slides/".$project_id."/";

if (!file_exists($filepath)) {
    mkdir($filepath, 0755, true);
}


$pdf->Output($filepath.$file_name.'.pdf','F');
$pdf->Output($filepath.$file_name.'.pdf','I');

$pdf->Output();
?>