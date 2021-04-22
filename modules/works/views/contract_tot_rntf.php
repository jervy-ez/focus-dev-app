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

	require_once('fpdf/fpdf.php');
	require_once('fpdf/fpdi/fpdi.php');

	class PDF extends FPDI
	{
		function SetLogo($logo)//,$comp_name,$tel_no,$po_box,$acn,$focus_suburb,$focus_abn,$focus_email,$proj_id,$proj_name,$compname)//,$work_company_name,$abn) 
		{ 
		  $this->logo = $logo;  
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
		} 
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
	$pdf->AddPage();

	$pdf->Image('./uploads/misc/'.$focus_logo,130,8,70);
	$pdf->ln(20);
	$pdf->cell(10);
	$pdf->SetFont('Arial','B',14);
	$pdf->Cell(30,10,'Quotation and Contract:',0,0);

	$pdf->ln();
	$pdf->cell(10);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(20,10,'TO:',0,0);
	$pdf->cell(50,10,'_____________________________________________________________________________');
	$pdf->cell(-48);
	$pdf->cell(50,10,$compname);

	$pdf->ln(7);
	$pdf->cell(10);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(20,10,'',0,0);
	$pdf->cell(50,10,'_____________________________________________________________________________');
	$pdf->cell(-48);
	$pdf->cell(50,10,$comp_address_1st.', '.$comp_address_2nd.', '.$comp_address_3rd);

	$pdf->ln(3);
	$pdf->cell(160);
	$pdf->SetFont('Arial','',8);
	$pdf->cell(50,10,'("The Client")');

	$pdf->ln(7);
	$pdf->cell(10);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(20,10,'Project:',0,0);
	$pdf->cell(50,10,'_____________________________________________________________________________');
	$pdf->cell(-48);
	$project_name = str_replace("&apos;", "'", $project_name);
	$pdf->cell(50,10,$project_name);

	$pdf->ln(7);
	$pdf->cell(10);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(20,10,'',0,0);
	$pdf->cell(50,10,'QUOTATION & CONTRACT NO: __________________________________________________');
	$pdf->cell(5);
	$pdf->cell(10,10,'#'.$proj_id);

	// $proj_date = $project_date;
	// if($proj_date == ""){
	// 	$proj_date = date('d/m/Y');
	// }
	$pdf->ln(7);
	$pdf->cell(10);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(20,10,'',0,0);
	$pdf->cell(50,10,'Date: ________________________________________________________________________');
	$pdf->cell(-40);
	$pdf->cell(10,10,$contract_date);

	$pdf->ln(7);
	$pdf->cell(10);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(20,10,'',0,0);
	$pdf->multicell(155,4,'We thank you for your request for a quotation on the above project which we are now pleased to provide. We trust the quotation meets with your approval and that you will confirm your acceptance and conclude a contract with us by signing the acceptance and returning it to this office. Please retain the enclosed signed photocopy for your record of the contract and note by accepting this Quotation and Contract, you are accepting our "Terms of Trade".');

	$pdf->ln(1);
	$pdf->cell(10);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(30,10,'DOCUMENTATION:',0,0);

	$pdf->ln(7);
	$pdf->cell(10);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(20,10,'',0,0);
	$pdf->cell(70,10,'1.  Plans, Elevations and Drawings: 				          ___________________________________________');
	$pdf->cell(20,10,$contract_plans_elv_draw);


	$pdf->ln(7);
	$pdf->cell(10);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(20,10,'',0,0);
	$pdf->cell(70,10,'2.  Schedule of Works Include in Quotation: 	___________________________________________');
	$pdf->cell(20,10,$sched_work_quote);


	$pdf->ln(7);
	$pdf->cell(10);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(20,10,'',0,0);
	$pdf->cell(70,10,'3.  Condition of Quotation and Contract: 	      ___________________________________________');
	$pdf->cell(20,10,$cond_quote_cont);


	$pdf->ln(7);
	$pdf->cell(10);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(20,10,'',0,0);
	$pdf->cell(75,10,'CONTRACT PRICE: 	      							                       $__________________________________________');

	$pdf->cell(50,10,number_format($project_total,2)."  exGST");

	$pdf->ln(7);
	$pdf->cell(10);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(20,10,'',0,0);
	$pdf->cell(95,10,'SIGNED: _______________________________   PRINT NAME:  _______________________________');
	if($has_sign == 1){
		$pdf->Image('./uploads/misc/garbage_list/'.$project_manager_id.'.png',50,125,50);
	}
	$pdf->cell(50,10,$proj_manager);
	

	$pdf->ln(4);
	$pdf->cell(10);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(20,10,'',0,0);
	$pdf->cell(50,10,'For and on behalf of Focus Shopfit Pty Ltd or Focus Shopfit NSW Pty Ltd																																														("the contractor")');

	$pdf->ln(7);
	$pdf->cell(10);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(30,10,'ACCEPTANCE:',0,0);

	$pdf->ln(7);
	$pdf->cell(10);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(20,10,'',0,0);
	$pdf->multicell(155,4,'I/We accept the quotation for the Works and agree to the "Terms of Trade" of Quotation and Contract and acknowledge that all payments are to be made within fourteen(14) days of receipt of invoice in accordance with the following:');

	foreach ($invoice_q->result_array() as $inv_row){
		$pdf->ln(5);
		$pdf->cell(10);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(20,10,'',0,0);
		if($inv_row['label'] == ""){
			$prog_lbl = 'Progress '.$inv_row['order_invoice'];
		}else{
			$prog_lbl = $inv_row['label'];
		}
		$pdf->cell(60,10,$prog_lbl);
		$pdf->cell(15,10,round($inv_row['progress_percent']).' %');
		$pdf->cell(5,10,'$');
		$prog_pay_amnt = $project_total * ($inv_row['progress_percent']/100);
		$pdf->cell(20,10,number_format($prog_pay_amnt,2),0,0,'R');
		$pdf->SetFont('Arial','',8);
		$pdf->cell(15,10," exGST");
		$pdf->cell(20,10,'Invoice Date:');
		$pdf->cell(25,10,$inv_row['invoice_date_req']);
	}

	$pdf->ln(5);
	$pdf->cell(10);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(20,10,'',0,0);
	$pdf->cell(60,10,'');
	$pdf->cell(15,10,'');
	$pdf->cell(5,10,'$');
	$pdf->cell(20,10,number_format($project_total,2),0,0,'R');
	$pdf->SetFont('Arial','B',8);
	$pdf->cell(15,10," exGST");

	$gst = $project_total*($admin_gst_rate/100);
	$pdf->ln(5);
	$pdf->cell(10);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(20,10,'',0,0);
	$pdf->cell(60,10,'');
	$pdf->cell(15,10,'');
	$pdf->cell(5,10,'$');
	$pdf->cell(20,10,number_format($gst,2),0,0,'R');
	$pdf->SetFont('Arial','B',8);
	$pdf->cell(15,10," GST");

	$inc_gst_price = number_format($project_total+($project_total*($admin_gst_rate/100)),2);

	$pdf->ln(5);
	$pdf->cell(10);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(20,10,'',0,0);
	$pdf->cell(60,10,'');
	$pdf->cell(15,10,'');
	$pdf->cell(5,10,'$');
	$pdf->cell(20,10,$inc_gst_price,0,0,'R');
	$pdf->SetFont('Arial','B',8);
	$pdf->cell(15,10," incGST");

	

	// $pdf->ln(7);
	// $pdf->cell(10);
	// $pdf->SetFont('Arial','',9);
	// $pdf->Cell(20,10,'',0,0);
	// $pdf->cell(65,10,'Progress Claim P2');
	// $pdf->cell(15,10,'XX%');
	// $pdf->cell(5,10,'$');
	// $pdf->cell(25,10,'1,000.00',0,0,'R');
	// $pdf->cell(20,10,'Enter Date:');
	// $pdf->cell(25,10,'Sept. 2, 2015');

	// $pdf->ln(7);
	// $pdf->cell(10);
	// $pdf->SetFont('Arial','',9);
	// $pdf->Cell(20,10,'',0,0);
	// $pdf->cell(65,10,'Progress Claim P3');
	// $pdf->cell(15,10,'XX%');
	// $pdf->cell(5,10,'$');
	// $pdf->cell(25,10,'1,000.00',0,0,'R');
	// $pdf->cell(20,10,'Enter Date:');
	// $pdf->cell(25,10,'Sept. 2, 2015');

	// $pdf->ln(7);
	// $pdf->cell(10);
	// $pdf->SetFont('Arial','',9);
	// $pdf->Cell(20,10,'',0,0);
	// $pdf->cell(65,10,'PAYMENT ON PRACTICAL COMPLETION');
	// $pdf->cell(15,10,'XX%');
	// $pdf->cell(5,10,'$');
	// $pdf->cell(25,10,'1,000.00',0,0,'R');
	// $pdf->cell(20,10,'Enter Date:');
	// $pdf->cell(25,10,'Sept. 2, 2015');

	// $pdf->ln(7);
	// $pdf->cell(10);
	// $pdf->SetFont('Arial','',9);
	// $pdf->Cell(20,10,'',0,0);
	// $pdf->cell(65,10,'RETENTION');
	// $pdf->cell(15,10,'XX%');
	// $pdf->cell(5,10,'$');
	// $pdf->cell(25,10,'1,000.00',0,0,'R');
	// $pdf->cell(20,10,'Enter Date:');
	// $pdf->cell(25,10,'Sept. 2, 2015');

	// $pdf->ln(7);
	// $pdf->cell(10);
	// $pdf->SetFont('Arial','',9);
	// $pdf->Cell(20,10,'',0,0);
	// $pdf->cell(65,10,'VARIATIONS');
	// $pdf->cell(15,10,'XX%');
	// $pdf->cell(5,10,'$');
	// $pdf->cell(25,10,'1,000.00',0,0,'R');
	// $pdf->cell(20,10,'Enter Date:');
	// $pdf->cell(25,10,'Sept. 2, 2015');

	$pdf->ln(7);
	$pdf->cell(10);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(20,10,'',0,0);
	$pdf->cell(95,10,'SIGNED: _______________________________   PRINT NAME:  _______________________________');

	$pdf->cell(50,10,$client_contact_person);

	$pdf->ln(4);
	$pdf->cell(10);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(20,10,'',0,0);
	$pdf->cell(50,10,'Authorized Signatory of "The Client"');

	$pdf->ln(7);
	$pdf->cell(10);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(20,10,'',0,0);
	$pdf->cell(50,10,'DATE: _______________________________');

	$pdf->ln(7);
	$pdf->cell(10);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(30,10,'BANK DETAILS:',0,0);

	$bank_name = "";
	$bank_no = "";

	$comp_name = "";
	$comp_add_1 = "";
	$comp_add_2 = "";
	$comp_add_3 = "";
	$comp_add_4 = "";
	$comp_add_5 = "";

	$comp_name_nsw = "";
	$comp_add_nsw_1 = "";
	$comp_add_nsw_2 = "";
	$comp_add_nsw_3 = "";
	$comp_add_nsw_4 = "";
	$comp_add_nsw_5 = "";


	if($focus_comp == "Focus Shopfit Pty Ltd" || $focus_comp == "Focus Maintenance"){
		$pdf->ln(7);
		$pdf->cell(10);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(20,10,'',0,0);
		$pdf->cell(65,10,'Focus Shopfit Pty Ltd');
		
		$pdf->ln(4);
		$pdf->cell(10);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(20,10,'',0,0);
		$pdf->cell(65,10,'NAB BSB: 086-420 Account: 15-409-0029');

		$comp_name = "Focus Shopfit Pty Ltd";
		$comp_add_1 = "Unit 3/86 Inspiration Drive";
		$comp_add_2 = "Wangara WA 6065";
		$comp_add_3 = "PO Box 1326";
		$comp_add_4 = "Wangara WA 6947";
		$comp_add_5 = "ABN 16 159 087 984";

	}else{
		$bank_name = "Focus Shopfit NSW Pty Ltd";
		$bank_no = "NAB BSB: 086-420 Account: 94-656-1853";

		$comp_name_nsw = "PO Box 1172 Menai";
		$comp_add_nsw_1 = "Central NSW 2234";
		$comp_add_nsw_2 = "";
		$comp_add_nsw_3 = "ABN 17 164 759 102";
		$comp_add_nsw_4 = "";
		$comp_add_nsw_5 = "";
	}
	


	$pdf->ln(4);
	$pdf->cell(75);
	$pdf->SetFont('Arial','',9);
	$pdf->SetTextColor(160,160,160);
	$pdf->Cell(20,10,'',0,0);
	$pdf->cell(30,10,$comp_name_nsw);
	$pdf->cell(10);
	$pdf->cell(65,10,$comp_name);

	$pdf->ln(4);
	$pdf->cell(10);
	$pdf->SetFont('Arial','',9);
	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(20,10,'',0,0);
	$pdf->cell(45,10,$bank_name);

	$pdf->SetTextColor(160,160,160);
	$pdf->Cell(20,10,'',0,0);
	$pdf->cell(30,10,$comp_add_nsw_1);
	$pdf->cell(10);
	$pdf->cell(65,10,$comp_add_1);

	$pdf->ln(4);
	$pdf->cell(10);
	$pdf->SetFont('Arial','',9);
	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(20,10,'',0,0);
	$pdf->cell(45,10,$bank_no);

	$pdf->SetTextColor(160,160,160);
	$pdf->Cell(20,10,'',0,0);
	$pdf->cell(30,10,$comp_add_nsw_2);
	$pdf->cell(10);
	$pdf->cell(65,10,$comp_add_2);

	$pdf->ln(6);
	$pdf->cell(75);
	$pdf->SetFont('Arial','',9);
	$pdf->SetTextColor(160,160,160);
	$pdf->Cell(20,10,'',0,0);
	$pdf->cell(30,10,$comp_add_nsw_3);
	$pdf->cell(10);
	$pdf->cell(65,10,$comp_add_3);

	$pdf->ln(4);
	$pdf->cell(75);
	$pdf->SetFont('Arial','',9);
	$pdf->SetTextColor(160,160,160);
	$pdf->Cell(20,10,'',0,0);
	$pdf->cell(30,10,$comp_add_nsw_4);
	$pdf->cell(10);
	$pdf->cell(65,10,$comp_add_4);

	$pdf->ln(6);
	$pdf->cell(1);
	$pdf->SetFont('Arial','',9);
	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(20,10,'',0,0);
	$pdf->cell(54,10,'Please sign & initial all pages and return to sender');

	$pdf->SetTextColor(160,160,160);
	$pdf->Cell(20,10,'',0,0);
	$pdf->cell(30,10,$comp_add_nsw_5);
	$pdf->cell(10);
	$pdf->cell(65,10,$comp_add_5);

	// $pdf->ln(5);
	// $pdf->cell(1);
	// $pdf->SetFont('Arial','',9);
	// $pdf->SetTextColor(0,0,0);
	// $pdf->Cell(20,10,'',0,0);
	// $pdf->cell(54,10,'');

// Second Page
	$pdf->AddPage();
	//$pdf->ln(20);
	$pdf->Cell(20,10,'',0,0);
	$pdf->setSourceFile("img/Focus Shopfit Contract Quote & Terms of Trade Form 050515.2.pdf");

	// import page 1
	$tplIdx = $pdf->importPage(1);
	// use the imported page and place it at point 10,10 with a width of 100 mm
	$pdf->useTemplate($tplIdx, 10, 10, 195);

	$pdf->ln(-4);
	$pdf->cell(8);
	$pdf->SetFillColor(96,96,96);
	$pdf->SetTextColor(255,255,255);
	$pdf->SetFont('Arial','B',10);
    //$pdf->Cell(180,6,$focus_comp." - By accepting our Quotation you are accepting the following Terms of Trade",0,0,'',true);
//Third Page

/*	$pdffile = "img/Request for New Trade Deptor Form.pdf";
    $pagecount = $pdf->setSourceFile($pdffile);  
    for($i=0; $i<$pagecount; $i++){
        $pdf->AddPage();  
        $tplidx = $pdf->importPage($i+1, '/MediaBox');
        $pdf->useTemplate($tplidx, 10, 10, 200); 
    }
*/
	//$pdf->setSourceFile("img/Request for New Trade Deptor Form.pdf");
	// import page 1
	//$tplIdx = $pdf->importPage(1);
	// use the imported page and place it at point 10,10 with a width of 100 mm
	//$pdf->useTemplate($tplIdx, 0, 0, 210);


	if (!file_exists($filepath)){
    	mkdir($filepath, 0755, true);
	}

	$pdf->Output($filepath.'quotation_contract_and_termsoftrade.pdf','F');
	$pdf->Output($filepath.'quotation_contract_and_termsoftrade.pdf','I');
}
?>