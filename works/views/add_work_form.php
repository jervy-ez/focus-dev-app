<?php 
	foreach ($works->result_array() as $row){
		$job_cat_id = $row['job_sub_cat_id'];
		$estimate = $row['work_estimate'];
		$markup = $row['markup'];
		$totalquote = $row['total_work_quote'];
		$sdate = $row['work_start_date'];
		$fdate = $row['work_finish_date'];
		$rbdate = $row['work_reply_date'];
		$work_cpo_date = $row['work_cpo_date'];
		$required_cpo_date = $row['required_cpo_date'];
		$replyby_desc = $row['comments'];
		$work_notes = $row['notes'];
		$cpono = $row['cpo_number'];
//considerations ---------
		$site_inspection_req = $row['site_inspection_req'];
		if($site_inspection_req == 1){
?>
	<script>
		document.getElementById("chkcons_site_inspect").checked = true;
	</script>
<?php
		}else{
?>
	<script>
		document.getElementById("chkcons_site_inspect").checked = false;
	</script>
<?php
		}
		$special_conditions = $row['special_conditions'];
if($special_conditions == 1){
?>
	<script>
		document.getElementById("chckcons_spcl_condition").checked = true;
	</script>
<?php
		}else{
?>
	<script>
		document.getElementById("chckcons_spcl_condition").checked = false;
	</script>
<?php
		}
		$additional_visit_req = $row['additional_visit_req'];
if($additional_visit_req == 1){
?>
	<script>
		document.getElementById("chckcons_addnl_visit").checked = true;
	</script>
<?php
		}else{
?>
	<script>
		document.getElementById("chckcons_addnl_visit").checked = false;
	</script>
<?php
		}
		$operate_during_install = $row['operate_during_install'];
if($operate_during_install == 1){
?>
	<script>
		document.getElementById("chckcons_oprte_duringinstall").checked = true;
	</script>
<?php
		}else{
?>
	<script>
		document.getElementById("chckcons_oprte_duringinstall").checked = false;
	</script>
<?php
		}
		$week_work = $row['week_work'];
if($week_work == 1){
?>
	<script>
		document.getElementById("chckcons_week_work").checked = true;
	</script>
<?php
		}else{
?>
	<script>
		document.getElementById("chckcons_week_work").checked = false;
	</script>
<?php
		}
		$weekend_work = $row['weekend_work'];
if($weekend_work == 1){
?>
	<script>
		document.getElementById("chckcons_weekend_work").checked = true;
	</script>
<?php
		}else{
?>
	<script>
		document.getElementById("chckcons_weekend_work").checked = false;
	</script>
<?php
		}
		$after_hours_work = $row['after_hours_work'];
if($after_hours_work == 1){
?>
	<script>
		document.getElementById("chckcons_afterhrs_work").checked = true;
	</script>
<?php
		}else{
?>
	<script>
		document.getElementById("chckcons_afterhrs_work").checked = false;
	</script>
<?php
		}
		$new_premises = $row['new_premises'];
if($new_premises == 1){
?>
	<script>
		document.getElementById("chckcons_new_premises").checked = true;
	</script>
<?php
		}else{
?>
	<script>
		document.getElementById("chckcons_new_premises").checked = false;
	</script>
<?php
		}
		$free_access = $row['free_access'];
if($free_access == 1){
?>
	<script>
		document.getElementById("chckcons_free_access").checked = true;
	</script>
<?php
		}else{
?>
	<script>
		document.getElementById("chckcons_free_access").checked = false;
	</script>
<?php
		}
		$other = $row['other'];
		$otherdesc = $row['otherdesc'];
if($other == 1){
?>
	<script>
		document.getElementById("chckcons_others").checked = true;
		document.getElementById("other_consideration").value = "<?php echo $otherdesc ?>";
	</script>
<?php
		}else{
?>
	<script>
		document.getElementById("chckcons_others").checked = false;
		document.getElementById("other_consideration").value = "";
	</script>
<?php
		}
		$is_deliver_office = $row['other'];
if($is_deliver_office == 1){
?>
	<script>
		document.getElementById("chkdeltooffice").checked = true;
	</script>
<?php
		}else{
?>
	<script>
		document.getElementById("chkdeltooffice").checked = false;
	</script>
<?php
		}

?>
	<script>
		document.getElementById("works").value = "<?php echo $job_cat_id ?>";
		document.getElementById("work_estimate").value = "<?php echo $estimate ?>";
		document.getElementById("work_sdate").value = "<?php echo $sdate ?>";
		document.getElementById("work_markup").value = "<?php echo $markup ?>";
		document.getElementById("work_fdate").value = "<?php echo $fdate ?>";
		document.getElementById("work_quote_val").value = "<?php echo $totalquote ?>";
		document.getElementById("work_replyby_date").value = "<?php echo $rbdate ?>";
		document.getElementById("replyby_desc").value = "<?php echo $replyby_desc ?>";
		document.getElementById("work_notes").value = "<?php echo $work_notes ?>";
		document.getElementById("work_cpodate_req").value = "<?php echo $required_cpo_date ?>";
		document.getElementById("work_cpo_date").value = "<?php echo $work_cpo_date ?>";
		document.getElementById("cpono").innerHTML = "<?php echo 'CPO#: '.$cpono ?>";
		
	</script>
<?php
	}
?>