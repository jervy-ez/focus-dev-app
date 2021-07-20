<?php
if($type == 0){
?>
		<option value="" disabled selected = 'Selected'>Choose a Work</option>
<?php
	foreach ($job_cat->result_array() as $row){
	?>
		<option value="<?php echo $row['job_sub_cat_id'] ?>" <?php if(isset($_POST['works']) == $row['job_sub_cat_id']){ ?>selected = "Selected"<?php } ?>><?php echo $row['job_sub_cat'] ?></option>
	<?php
	}
}else{
?>
		<option value="" disabled selected = 'Selected'>Choose a Work</option>
<?php
	foreach ($sup_cat->result_array() as $row){
	?>
		<option value="<?php echo $row['supplier_cat_id'] ?>" <?php if(isset($_POST['works']) == $row['supplier_cat_id']){ ?>selected = "Selected"<?php } ?>><?php echo $row['supplier_cat_name'] ?></option>
	<?php
	}
}
?>

