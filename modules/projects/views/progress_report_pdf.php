<?php

require('fpdf/fpdf.php');

class PDF extends FPDF
{
	function setVar($project_name, $site_address1, $site_address2)
	{
		$this->project_name = $project_name;
		$this->site_address1 = $site_address1;
		$this->site_address2 = $site_address2;
		// $this->progress_reports_images = $progress_reports_images;
	}
	// Page footer
	function Footer()
	{
	    // Position at 1.5 cm from bottom
	    $this->SetY(-22);
	    // Page number
	    $this->Image('img/progress_report/resources/location.PNG',10,190,15);
	    $this->SetFont('Arial','B',12);
	    $this->SetX(26);
	    $this->Cell(100,10, $this->project_name);
	    $this->Ln(6);
	    $this->SetFont('Arial','',10);
	    $this->SetX(26);
	    $this->Cell(100,10, $this->site_address1);
	    $this->Ln(6);
	    $this->SetX(26);
	    $this->Cell(100,10, $this->site_address2);
	}
}
ob_end_clean();
ob_start();
//Instanciation of inherited class
$pdf = new PDF();
$pdf->setVar($project_name, $site_address1, $site_address2);
$pdf->SetTextColor(59,66,68);
$pdf->AddPage('L');
$pdf->Image('img/progress_report/resources/header_cover.jpg',122,0,176);
$pdf->Image('img/progress_report/resources/cover_logo.PNG',180,70,80);
$pdf->Image('img/progress_report/resources/footer_cover.jpg',217,128,80);
$pdf->Ln(100);
$pdf->SetFont('Arial','B',28);
$pdf->Cell(40,10, $project_name);
$pdf->Ln(10);
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'PROGRESS REPORT');
$pdf->Ln(10);
$pdf->Cell(40,10,date('d F Y'));

$pdf->AddPage('L');
$pdf->Image('img/progress_report/resources/header_cover.jpg',203,0,100);
$pdf->Image('img/progress_report/resources/footer_cover.jpg',230,149,70);
$pdf->Ln(0);
$pdf->SetFont('Arial','',20);
$pdf->Cell(40,10,'PROGRESS REPORT');
$pdf->Image('img/progress_report/resources/scope_of_work.PNG',10,20,15);
$pdf->Ln(8);
$pdf->SetFont('Arial','BU',12);
$pdf->Cell(80,18,'WORKS PROGRESS DETAILS:',0,0,'R');
$pdf->Ln(13);
$pdf->SetX(27);
$pdf->SetFont('Arial','',10);
$pdf->Multicell(200,8, $scope_of_work);

$pdf->Image('img/progress_report/resources/contact_person.PNG',10,145,15);

$pdf->SetY(143);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(55,10,'Contact Person:',0,0,'R');
$pdf->Ln(7);
$pdf->SetX(27);
$pdf->SetFont('Arial','',10);
$pdf->Cell(40,10, 'Leading Hand:');
$pdf->Ln(5);
$pdf->SetX(40);
$pdf->SetFont('Arial','',10);
$pdf->Cell(40,10, $leading_hand);

$pdf->Ln(7);
$pdf->SetX(27);
$pdf->SetFont('Arial','',10);
$pdf->Cell(40,10, 'Construction Manager:');
$pdf->Ln(5);
$pdf->SetX(40);
$pdf->SetFont('Arial','',10);
$pdf->Cell(40,10, $const_mngr);

$pdf->Ln(7);
$pdf->SetX(27);
$pdf->SetFont('Arial','',10);
$pdf->Cell(40,10, 'Project Manager:');
$pdf->Ln(5);
$pdf->SetX(40);
$pdf->SetFont('Arial','',10);
$pdf->Cell(40,10, $project_manager);


/* ============= DON'T DELETE -LOGIC FOR ODD EVEN AND CENTERING LONE IMAGES: by Mike 12-08-17 ============= */

// $group_array = array('1','1', '2', '2', '2', '2', '2', '3', '3', '3');
// $group_count = array_count_values($group_array);
// $array_count = count($group_array);

// $count = 0;
// foreach ($group_count as $group_id => $group_id_count) {

// 	$counter = 0;
// 	if($count == 0){
// 		echo '---<br>';
// 	}
	
// 	foreach ($group_array as $value) {

// 		if ($group_id == $value){

// 			$counter++;
// 			$count++;

// 			if ($counter % 2){

// 				if ($group_id_count <> 1){

// 					if ($counter < $group_id_count){
// 						echo 'odd'.$value.'<br>';
// 					} else {
// 						echo 'odd*'.$value.'<br>';
// 					}

// 				} else {
// 					echo 'odd*'.$value.'<br>';
// 				}

// 				if ($counter == $group_id_count){

// 					if ($count != $array_count){
// 						echo '---<br>';
// 					}
					
// 				}

// 			} else {
// 				echo 'even'.$value.'<br>';

// 				if ($count != $array_count){
// 					echo '---<br>';
// 				}
// 			}
// 		}
// 	}
// }

$group_count_values = array();

foreach ($progress_reports_images as $row) {
	array_push($group_count_values, $row['group_id']);
}

$group_count = array_count_values($group_count_values);
$array_count = count($progress_reports_images);

$count = 0;

foreach ($group_count as $group_id => $group_id_count) {

	$counter = 0;
	if ($count == 0){
		$pdf->AddPage('L');
		$pdf->Image('img/progress_report/resources/header_cover.jpg',251,0,50);
		$pdf->Image('img/progress_report/resources/focus-logo-print.jpg',230,20,50);
		$pdf->Image('img/progress_report/resources/footer_cover.jpg',258,169,40);
		$pdf->Image('img/progress_report/resources/progress_photos.PNG',10,5,15);
		$pdf->Ln(3);
		$pdf->SetFont('Arial','U',21);
		$pdf->Cell(72,0,'Progress Photos',0,0,'R');
	}
	
	foreach ($progress_reports_images as $row) {

		if ($group_id == $row['group_id']){

			$counter++;
			$count++;

			if ($counter % 2){

				if ($group_id_count <> 1){

					if ($counter < $group_id_count){
						// echo 'odd'.$row['image_path'].'<br>';

						// $pdf->Image($row['image_path'], 15,30,120,80); // left side
						// $pdf->Cell(72,10,$row['image_label'],0,0,'R');

						if ($row['image_orientation'] == 1){ // portrait (rotated)
							$pdf->Image($row['image_path'],40,45,100,120); // left side
							$pdf->SetFont('Arial','B','14');
							$pdf->Cell(20,55,$row['image_label'],0,0,'C');
						} else {
							$pdf->Image($row['image_path'],25,25,120,70); // left side
							$pdf->SetFont('Arial','B','14');
							$pdf->Cell(2,176,$row['image_label'],0,0,'C');
						}

					} else {
						// echo 'odd*'.$row['image_path'].'<br>';

						if ($row['image_orientation'] == 1){ // portrait (rotated)
							$pdf->Image($row['image_path'],100,40,100,120); // center
							$pdf->SetFont('Arial','B','14');
							$pdf->Cell(10,150,$row['image_label'],0,0,'R');
						} else {
							$pdf->Image($row['image_path'],56,50,170,100); // center
							$pdf->SetFont('Arial','B','14');
							$pdf->Cell(120,60,$row['image_label'],0,0,'C');
						}
					}

				} else {
					// echo 'odd*'.$row['image_path'].'<br>';

					if ($row['image_orientation'] == 1){ // portrait (rotated)
						$pdf->Image($row['image_path'],100,40,100,120); // center
						$pdf->SetFont('Arial','B','14');
						$pdf->Cell(10,150,$row['image_label'],0,0,'R');
					} else {
						$pdf->Image($row['image_path'],56,50,170,100); // center
						$pdf->SetFont('Arial','B','14');
						$pdf->Cell(120,60,$row['image_label'],0,0,'C');
					}
				}

				if ($counter == $group_id_count){
					// echo '---<br>';

					if ($count <> $array_count){
						$pdf->AddPage('L');
						$pdf->Image('img/progress_report/resources/header_cover.jpg',251,0,50);
						$pdf->Image('img/progress_report/resources/focus-logo-print.jpg',230,20,50);
						$pdf->Image('img/progress_report/resources/footer_cover.jpg',258,169,40);
						$pdf->Image('img/progress_report/resources/progress_photos.PNG',10,10,15);
						$pdf->Ln(3);
						$pdf->SetFont('Arial','U',21);
						$pdf->Cell(72,0,'Progress Photos',0,0,'R');
					}
				}

			} else {
				// echo 'even'.$row['image_path'].'<br>';

				// $pdf->Image($row['image_path'],160,85,120,80); // right side
				// // $pdf->Cell(72,10,'Progress Photos',0,0,'R');

				if ($row['image_orientation'] == 1){ // portrait (rotated)
					$pdf->Image($row['image_path'],150,45,100,120); // right side
					$pdf->SetFont('Arial','B','14');
					$pdf->Cell(200,55,$row['image_label'],0,0,'C');
				} else {
					$pdf->Image($row['image_path'],150,90,120,80); // right side
					$pdf->SetFont('Arial','B','14');
					$pdf->Cell(255,144,$row['image_label'],0,0,'C');
					
				}

				// echo '---<br>';

				if ($count <> $array_count){
					$pdf->AddPage('L');
					$pdf->Image('img/progress_report/resources/header_cover.jpg',251,0,50);
					$pdf->Image('img/progress_report/resources/focus-logo-print.jpg',230,20,50);
					$pdf->Image('img/progress_report/resources/footer_cover.jpg',258,169,40);
					$pdf->Image('img/progress_report/resources/progress_photos.PNG',10,10,15);
					$pdf->Ln(3);
					$pdf->SetFont('Arial','U',21);
					$pdf->Cell(72,0,'Progress Photos',0,0,'R');
				}

			}
		}
	}
}

$pdf->Output();
ob_end_flush();

?>