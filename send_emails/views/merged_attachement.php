<?php 
	$company_name = str_replace(' ', '', $compname);
	$filepath = "reports/".$client_id."_".$company_name."/".$proj_id."/";
	define('FPDF_FONTPATH','font/');

	require_once('fpdf/fpdf.php');
	require_once('fpdf/fpdi/fpdi.php');

	class PDF extends FPDI
	{
		//function SetLogo($logo)//,$comp_name,$tel_no,$po_box,$acn,$focus_suburb,$focus_abn,$focus_email,$proj_id,$proj_name,$compname)//,$work_company_name,$abn) 
		//{ 
		//  $this->logo = $logo;  
		  /*$this->comp_name = $comp_name; 
		  $this->tel_no = $tel_no; 
		  $this->po_box = $po_box;
		  $this->acn = $acn;
		  $this->focus_abn = $focus_abn;
		  //$this->abn = $abn;
		  $this->focus_suburb = $focus_suburb;
		  $this->focus_email = $focus_email;
		  $this->proj_id = $proj_id;
		  $this->proj_name = $proj_name;
		  $this->compname = $compname;*/
		  //$this->work_company_name = $work_company_name;
		//}
		function Header()
		{ 
		    //$this->Image('./uploads/misc/'.$this->logo,130,8,70);
		    //Arial bold 15
		    $this->Ln();
		}
	}
	$pdf=new PDF();
	
	//$pdf->SetLogo($focus_logo);
	$pdf->AliasNbPages();

	if($proj_sum_w_cost !== ""){
		$pdffile = "reports/".$client_id."_".$company_name."/".$proj_id."/".$proj_sum_w_cost;
	    $pagecount = $pdf->setSourceFile($pdffile);  
	    for($i=0; $i<$pagecount; $i++){
	        $pdf->AddPage();  
	        $tplidx = $pdf->importPage($i+1, '/MediaBox');
	        $pdf->useTemplate($tplidx, 10, 10, 200); 
	    }
	}

	if($proj_sum_wo_cost !== ""){
		$pdffile = "reports/".$client_id."_".$company_name."/".$proj_id."/".$proj_sum_wo_cost;
	    $pagecount = $pdf->setSourceFile($pdffile);  
	    for($i=0; $i<$pagecount; $i++){
	        $pdf->AddPage();  
	        $tplidx = $pdf->importPage($i+1, '/MediaBox');
	        $pdf->useTemplate($tplidx, 10, 10, 200); 
	    }
	}

	if($joinery_sum_w_cost !== ""){
		$pdffile = "reports/".$client_id."_".$company_name."/".$proj_id."/".$joinery_sum_w_cost;
	    $pagecount = $pdf->setSourceFile($pdffile);  
	    for($i=0; $i<$pagecount; $i++){
	        $pdf->AddPage();  
	        $tplidx = $pdf->importPage($i+1, '/MediaBox');
	        $pdf->useTemplate($tplidx, 10, 10, 200); 
	    }
	}

	if($joinery_sum_wo_cost !== ""){
		$pdffile = "reports/".$client_id."_".$company_name."/".$proj_id."/".$joinery_sum_wo_cost;
	    $pagecount = $pdf->setSourceFile($pdffile);  
	    for($i=0; $i<$pagecount; $i++){
	        $pdf->AddPage();  
	        $tplidx = $pdf->importPage($i+1, '/MediaBox');
	        $pdf->useTemplate($tplidx, 10, 10, 200); 
	    }
	}



	//$pdf->setSourceFile("img/Request for New Trade Deptor Form.pdf");
	// import page 1
	//$tplIdx = $pdf->importPage(1);
	// use the imported page and place it at point 10,10 with a width of 100 mm
	//$pdf->useTemplate($tplIdx, 0, 0, 210);


	if (!file_exists($filepath)){
    	mkdir($filepath, 0755, true);
	}

	$pdf->Output($filepath.$proj_id.'_attachement.pdf','F');
	$pdf->Output($filepath.$proj_id.'_attachement.pdf','I');
?>