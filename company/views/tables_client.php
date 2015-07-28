<?php
foreach ($com_c->result_array() as $row){
	$complete = 0;
	$incomplete = 0;
	$no_insurance = 0;
	$font_color = "";
	if($row['company_type_id'] == '2'){
		if($row['has_insurance_public_liability'] == 1){
			if($row['has_insurance_workers_compensation'] == 1){
				$complete = 1;
			}else{
				if($row['has_insurance_income_protection'] == 1){
					$complete = 1;
				}else{
					$incomplete = 1;
				}
			}
		}else{
			if($row['has_insurance_workers_compensation'] == 1){
				$incomplete = 1;
			}else{
				if($row['has_insurance_income_protection'] == 1){
					$incomplete = 1;
				}else{
					$no_insurance = 1;
				}
			}
		}
	}

	$expired = 0;
	$today = date('d/m/Y');
	if($row['public_liability_expiration'] !== ""){
		if($row['public_liability_expiration'] > $today){
			$expired = 1;
		}
	}
	if($row['workers_compensation_expiration'] !== ""){
		if($row['workers_compensation_expiration'] <= $today){
			$expired = 1;
		}
	}
	if($row['income_protection_expiration'] !== ""){
		if($row['income_protection_expiration'] <= $today){
			$expired = 1;
		}
	}

	$font_color = "";
	if($row['company_type_id'] == '2'){
		if($complete == 1){
			$font_color = "Blue";
		}else{
			if($incomplete == 1){
				$font_color = "Green";
			}else{
				$font_color = "Red";
			}
		}
		if($expired == 1){
			$font_color = "Orange";
		}
	}
	//echo '<tr><td><a href="'.base_url().'company/view/'.$row['company_id'].'" >'.$row['company_name'].'</a></td><td>'.ucwords(strtolower($row['suburb'])).' '.$row['shortname'].'</td><td>'.$row['area_code'].' '.$row['office_number'].'</td><td>'. strtolower($row['general_email']).'</td></tr>';
	echo '<tr><td><a href="'.base_url().'company/view/'.$row['company_id'].'" style = "color:'.$font_color.'">'.$row['company_name'].'</a></td><td>'.ucwords(strtolower($row['suburb'])).' '.$row['shortname'].'</td><td>';
	echo ($row['office_number'] != ''? $row['area_code'].' '.$row['office_number'] : '');
	echo '</td><td><a href="mailto:'.strtolower($row['general_email']).'?Subject=Inquiry" >'.strtolower($row['general_email']).'</a></td></tr>';
}


?>