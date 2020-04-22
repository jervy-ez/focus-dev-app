<?php

require('fpdf/fpdf.php');

class PDF extends FPDF
{

	const DPI = 96;
    const MM_IN_INCH = 25.4;
    const A4_HEIGHT = 297;
    const A4_HEIGHT_HALF = 148.5;
    const A4_WIDTH = 210;
    // tweak these values (in pixels)
    const MAX_WIDTH = 400;
    const MAX_HEIGHT = 400;

    const MAX_WIDTH_centre = 530;
    const MAX_HEIGHT_centre = 400;

    const MAX_WIDTH_logo = 400;
    const MAX_HEIGHT_logo = 200;

    function pixelsToMM($val) {
        return $val * self::MM_IN_INCH / self::DPI;
    }

    function resizeToFit($imgFilename) {
        list($width, $height) = getimagesize($imgFilename);
        $widthScale = self::MAX_WIDTH / $width;
        $heightScale = self::MAX_HEIGHT / $height;
        $scale = min($widthScale, $heightScale);
        return array(
            round($this->pixelsToMM($scale * $width)),
            round($this->pixelsToMM($scale * $height))
        );
    }

    function resizeToFit_centre($imgFilename) {
        list($width, $height) = getimagesize($imgFilename);
        $widthScale = self::MAX_WIDTH_centre / $width;
        $heightScale = self::MAX_HEIGHT_centre / $height;
        $scale = min($widthScale, $heightScale);
        return array(
            round($this->pixelsToMM($scale * $width)),
            round($this->pixelsToMM($scale * $height))
        );
    }

     function resizeToFitLogo($imgFilename) {
        list($width, $height) = getimagesize($imgFilename);
        $widthScale = self::MAX_WIDTH_logo / $width;
        $heightScale = self::MAX_HEIGHT_logo / $height;
        $scale = min($widthScale, $heightScale);
        return array(
            round($this->pixelsToMM($scale * $width)),
            round($this->pixelsToMM($scale * $height))
        );
    }

    function centreImage($img) {
        list($width, $height) = $this->resizeToFit_centre($img);
        // you will probably want to swap the width/height
        // around depending on the page's orientation
        $this->SetLineWidth(0.5);
        $this->Rect((self::A4_HEIGHT - $width) / 2, (self::A4_WIDTH - $height) / 2 - 10, $width, $height, 'DF');
        $this->Image(
            $img, (self::A4_HEIGHT - $width) / 2,
            (self::A4_WIDTH - $height) / 2 - 10,
            $width,
            $height
        );
    }

    function centreImageLHalf($img) {
        list($width, $height) = $this->resizeToFit($img);
        // you will probably want to swap the width/height
        // around depending on the page's orientation
        $this->SetLineWidth(0.5);
        $this->Rect((self::A4_HEIGHT_HALF - $width) / 2 + 10, (self::A4_WIDTH - $height) / 2 - 10, $width, $height, 'DF');
        $this->Image(
            $img, (self::A4_HEIGHT_HALF - $width) / 2 + 10,
            (self::A4_WIDTH - $height) / 2 - 10,
            $width,
            $height
        );
    }

    function centreImageRHalf($img) {
        list($width, $height) = $this->resizeToFit($img);
        // you will probably want to swap the width/height
        // around depending on the page's orientation
        $this->SetLineWidth(0.5);
        $this->Rect((self::A4_HEIGHT_HALF - $width) / 2 + 130, (self::A4_WIDTH - $height) / 2 - 10, $width, $height, 'DF');
        $this->Image(
            $img, (self::A4_HEIGHT_HALF - $width) / 2 + 130,
            (self::A4_WIDTH - $height) / 2 - 10,
            $width,
            $height
        );
    }

    function setPosLogo($img) {
        list($width, $height) = $this->resizeToFitLogo($img);
        // you will probably want to swap the width/height
        // around depending on the page's orientation
        $this->Image(
            $img, (self::A4_HEIGHT_HALF - $width) / 2,
            65,
            $width,
            $height
        );
    }

	function setVar($project_id, $project_footer, $site_inspection_details, $completion_details, $po_client)
	{
		$this->project_id = $project_id;
		$this->project_footer = $project_footer;
		$this->site_inspection_details = $site_inspection_details;
		$this->completion_details = $completion_details;
        $this->po_client = $po_client;
	}

	// Page footer
	function Footer()
	{
	    // Position at 1.5 cm from bottom
	    $this->SetY(-15);
	    $this->SetFont('Helvetica','B',10);
	    $this->SetTextColor(1,176,241);
	    // $this->SetX(80);

        if ($this->po_client != ''){
            $this->Cell(0,0, $this->project_footer.' | '.$this->po_client, '0', '0', 'C');
        } else {
            $this->Cell(0,0, $this->project_footer, '0', '0', 'C');
        }
	}
}
ob_end_clean();
ob_start();
//Instanciation of inherited class
$pdf = new PDF();
$pdf->setVar($project_id, $project_footer, $site_inspection_details, $completion_details, $po_client);
$pdf->SetTextColor(59,66,68);

if ($site_inspection_include == 'on'){

    $group_count_values = array();

    foreach ($sr_images_si as $row) {
        array_push($group_count_values, $row['group_id']);
    }

    $group_count = array_count_values($group_count_values);
    $array_count = count($sr_images_si);

    $count = 0;

    foreach ($group_count as $group_id => $group_id_count) {

        $counter = 0;
        if ($count == 0){
            $pdf->AddPage('L');
            $pdf->Image('img/progress_report/resources/header_cover2.jpg',267,0,30);
            $pdf->Image('img/progress_report/resources/focus-logo-print.jpg',235,18,45);
            $pdf->Image('img/progress_report/resources/footer_cover.jpg',257,169,40);

            $pdf->Ln(12);
            $pdf->Image('img/progress_report/resources/site_inspection.JPG',10,20,15);
            $pdf->SetFont('Helvetica','U',20);
            $pdf->Cell(80,10,'SITE INSPECTION',0,0,'R');
        }

        foreach ($sr_images_si as $row){

            if ($group_id == $row['group_id']){

                $counter++;
                $count++;

                if($counter % 2){

                    if ($group_id_count <> 1){

                        if ($counter < $group_id_count){
                            $pdf->centreImageLHalf($row['image_path']);
                        } else {
                            $pdf->centreImage($row['image_path']);

                            $pdf->Ln(50);
                            $pdf->SetY(158);
                            $pdf->SetX(0);
                            $pdf->SetFont('Helvetica','',9);
                            $pdf->Multicell(300,4.5, $site_inspection_details,'0','C');
                        }

                    } else {
                        $pdf->centreImage($row['image_path']);

                        $pdf->Ln(50);
                        $pdf->SetY(158);
                        $pdf->SetX(0);
                        $pdf->SetFont('Helvetica','',9);
                        $pdf->Multicell(300,4.5, $site_inspection_details,'0','C');
                    }

                    if ($counter == $group_id_count){

                        if ($count <> $array_count){
                            $pdf->AddPage('L');
                            $pdf->Image('img/progress_report/resources/header_cover2.jpg',267,0,30);
                            $pdf->Image('img/progress_report/resources/focus-logo-print.jpg',235,18,45);
                            $pdf->Image('img/progress_report/resources/footer_cover.jpg',257,169,40);

                            $pdf->Ln(12);
                            $pdf->Image('img/progress_report/resources/site_inspection.JPG',10,20,15);
                            $pdf->SetFont('Helvetica','U',20);
                            $pdf->Cell(80,10,'SITE INSPECTION',0,0,'R');
                        }
                    }

                } else {

                    $pdf->centreImageRHalf($row['image_path']);

                    $pdf->Ln(50);
                    $pdf->SetY(158);
                    $pdf->SetX(0);
                    $pdf->SetFont('Helvetica','',9);
                    $pdf->Multicell(300,4.5, $site_inspection_details,'0','C');

                    if ($count <> $array_count){
                        $pdf->AddPage('L');
                        $pdf->Image('img/progress_report/resources/header_cover2.jpg',267,0,30);
                        $pdf->Image('img/progress_report/resources/focus-logo-print.jpg',235,18,45);
                        $pdf->Image('img/progress_report/resources/footer_cover.jpg',257,169,40);

                        $pdf->Ln(12);
                        $pdf->Image('img/progress_report/resources/site_inspection.JPG',10,20,15);
                        $pdf->SetFont('Helvetica','U',20);
                        $pdf->Cell(80,10,'SITE INSPECTION',0,0,'R');
                    }
                }
            }
        }
    }

}

if ($completion_include == 'on'){

    $group_count_values2 = array();

    foreach ($sr_images_comp as $row) {
        array_push($group_count_values2, $row['group_id']);
    }

    $group_count2 = array_count_values($group_count_values2);
    $array_count2 = count($sr_images_comp);

    $count2 = 0;

    foreach ($group_count2 as $group_id => $group_id_count) {

        $counter = 0;
        if ($count2 == 0){
            $pdf->AddPage('L');
            $pdf->Image('img/progress_report/resources/header_cover2.jpg',267,0,30);
            $pdf->Image('img/progress_report/resources/focus-logo-print.jpg',235,18,45);
            $pdf->Image('img/progress_report/resources/footer_cover.jpg',257,169,40);

            $pdf->Ln(12);
            $pdf->Image('img/progress_report/resources/completion.JPG',10,20,15);
            $pdf->SetFont('Helvetica','U',20);
            $pdf->Cell(65,10,'COMPLETION',0,0,'R');
        }

        foreach ($sr_images_comp as $row){

            if ($group_id == $row['group_id']){

                $counter++;
                $count2++;

                if($counter % 2){

                    if ($group_id_count <> 1){

                        if ($counter < $group_id_count){
                            $pdf->centreImageLHalf($row['image_path']);
                        } else {
                            $pdf->centreImage($row['image_path']);

                            $pdf->Ln(50);
                            $pdf->SetY(158);
                            $pdf->SetX(40);
                            $pdf->SetFont('Helvetica','',9);
                            $pdf->Multicell(210,4.5, $completion_details,'0','C');
                        }

                    } else {
                        $pdf->centreImage($row['image_path']);

                       $pdf->Ln(50);
                        $pdf->SetY(158);
                        $pdf->SetX(40);
                        $pdf->SetFont('Helvetica','',9);
                        $pdf->Multicell(210,4.5, $completion_details,'0','C');
                    }

                    if ($counter == $group_id_count){

                        if ($count2 <> $array_count2){
                            $pdf->AddPage('L');
                            $pdf->Image('img/progress_report/resources/header_cover2.jpg',267,0,30);
                            $pdf->Image('img/progress_report/resources/focus-logo-print.jpg',235,18,45);
                            $pdf->Image('img/progress_report/resources/footer_cover.jpg',257,169,40);

                            $pdf->Ln(12);
                            $pdf->Image('img/progress_report/resources/completion.JPG',10,20,15);
                            $pdf->SetFont('Helvetica','U',20);
                            $pdf->Cell(65,10,'COMPLETION',0,0,'R');
                        }
                    }

                } else {

                    $pdf->centreImageRHalf($row['image_path']);

                    $pdf->Ln(50);
                    $pdf->SetY(158);
                    $pdf->SetX(40);
                    $pdf->SetFont('Helvetica','',9);
                    $pdf->Multicell(210,4.5, $completion_details,'0','C');

                    if ($count2 <> $array_count2){
                        $pdf->AddPage('L');
                        $pdf->Image('img/progress_report/resources/header_cover2.jpg',267,0,30);
                        $pdf->Image('img/progress_report/resources/focus-logo-print.jpg',235,18,45);
                        $pdf->Image('img/progress_report/resources/footer_cover.jpg',257,169,40);

                        $pdf->Ln(12);
                        $pdf->Image('img/progress_report/resources/completion.JPG',10,20,15);
                        $pdf->SetFont('Helvetica','U',20);
                        $pdf->Cell(65,10,'COMPLETION',0,0,'R');
                    }
                }
            }
        }
    }
}

$pdf->Output();

$proj_dir = getcwd().DIRECTORY_SEPARATOR.'reports'.DIRECTORY_SEPARATOR.'service_reports'.DIRECTORY_SEPARATOR.$project_id;

if (file_exists($proj_dir)){
    $pdf->Output($proj_dir.DIRECTORY_SEPARATOR.$project_footer.'.pdf', 'F');
} else {
    mkdir($proj_dir, 0777);
    $pdf->Output($proj_dir.DIRECTORY_SEPARATOR.$project_footer.'.pdf', 'F');
}

ob_end_flush();

?>