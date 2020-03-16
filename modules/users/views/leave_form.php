<?php foreach ($for_pdf_content as $row): 

	$leave_req_id = $row->leave_request_id;
	$date_applied = $row->date;
	$first_name = $row->user_first_name;
	$last_name = $row->user_last_name;
	$role = $row->role_types;
	$start_day = $row->start_day_of_leave;
	$end_day = $row->end_day_of_leave;
	$date_return = $row->date_return;
	$leave_type = $row->leave_type;
	$total_days_away = $row->total_days_away;
	$superv_first_name = $row->superv_first_name;
	$superv_last_name = $row->superv_last_name;

	$filepath = "reports/".$first_name."_".$last_name."/".$date_applied."/".$leave_req_id."/";
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

	$pdf->setSourceFile("img/leave_form.pdf");

	/*echo date('d/m/Y', $date_applied).'<br>';
	echo $first_name.'<br>';
	echo $last_name.'<br>';
	echo $role.'<br>';
	echo date('d/m/Y', $start_day).'<br>';
	echo date('d/m/Y', $end_day).'<br>';
	echo date('d/m/Y', $date_return).'<br>';
	echo $leave_type.'<br>';
	echo $total_days_away.'<br>';
	echo $superv_first_name.'<br>';
	echo $superv_last_name.'<br>';*/
?>
	







<?php endforeach ?>