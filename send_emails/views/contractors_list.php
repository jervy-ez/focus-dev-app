<?php 
	$this->load->module('works');
	$this->load->model('works_m');
?>
<table class="table table-striped table-bordered" style = "font-size: 11px">
	<thead>
		<tr>
			<th width = "20px"><input type="checkbox" id = "checkall_contractor" onclick = "check_all()" title = "check all" ></th>
			<th>Contractor Name</th>
			<th>Works</th>
			<th>Status</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if($proj_works_t->num_rows == 0){
		?>
		<tr>
			<td colspan = 3>
		<?php
			echo "No Contructor Selected";
		?>
			</td>
		</tr>
		<?php
		}else{
			$joinery_exist = 0;
			$joinery_work_id = 0;
			foreach ($proj_works_t->result_array() as $row){
				//$is_selected = $row['is_selected'];
				$work_id = $row['works_id'];
				$cont_type = $row['contractor_type'];
				$work_desc = "";
				if($row['contractor_type'] == "3"): 
					$work_desc = $row['supplier_cat_name'];
				else:
					if($row['work_con_sup_id'] == "82"): 
						$work_desc = $row['other_work_desc'];
					else:
						$work_desc = $row['job_sub_cat'];
					endif; 	
				endif; 

				$works_t = $this->works_m->display_work_contructor($work_id);
				if($proj_works_t->num_rows !== 0){	
					foreach($works_t->result_array() as $works_row){
			?>
			<tr class="" <?php if($works_row['is_selected']){ ?> style = "color: #009933" <?php } ?>>
				<td class="">
					<input type="checkbox" id = "sel_contractor" name = "sel_cotnractor[]" class = "cont_checkbox" value = "<?php echo  $works_row['works_contrator_id'] ?>">
				</td>
				<td class="">
					<?php echo $works_row['company_name'] ?>
				</td>
				<td class="">
					<?php echo $work_desc ?>
				</td>
				<td class="">
				<?php 
					if($works_row['cqr_created'] == 1){
				?>
					<img src="<?php echo base_url() ?>img/send_email/pdf-check.png" alt="" onclick = "show_cqr('<?php echo $works_row['works_contrator_id'] ?>')" style = "width: 20px" title = "CQR Created">
				<?php
					}else{
				?>
					<img src="<?php echo base_url() ?>img/send_email/pdf-disabled.png" alt="" style = "width: 20px" title = "CQR Not Created">
				<?php
					}

					if($works_row['cpo_created'] == 1){
				?>
					<img src="<?php echo base_url() ?>img/send_email/pdf-check.png" alt="" onclick = "show_cpo('<?php echo $works_row['works_contrator_id'] ?>')" style = "width: 20px" title = "CPO Created">
				<?php
					}else{
				?>
					<img src="<?php echo base_url() ?>img/send_email/pdf-disabled.png" alt="" style = "width: 20px" title = "CPO Not Created">
				<?php
					}

					if($works_row['cqr_send'] == 1){
				?>
					<img src="<?php echo base_url() ?>img/send_email/cqr-send.png" alt="" style = "width: 20px" title = "CQR Send <?php echo $works_row['cqr_send_date'] ?>">
				<?php
					}else{
				?>
					<img src="<?php echo base_url() ?>img/send_email/cqr-disabled.png" alt="" style = "width: 20px" title = "CQR Not Send">
				<?php
					}

					if($works_row['cpo_send'] == 1){
				?>
					<img src="<?php echo base_url() ?>img/send_email/cpo-send.png" alt="" style = "width: 20px" title = "CPO Send <?php echo $works_row['cpo_send_date'] ?>">
				<?php
					}else{
				?>
					<img src="<?php echo base_url() ?>img/send_email/cpo-disabled.png" alt="" style = "width: 20px" title = "CPO Not Send">
				<?php
					}
				?>
				</td>
			</tr>
			<?php
						
					}
				}
				if($work_desc == "Joinery Works"){
					$joinery_exist = 1;
					$joinery_work_id = $work_id;
				}
			}
			if($joinery_exist == 1){
				$works_joinery_t = $this->works_m->display_all_works_joinery($joinery_work_id);
				foreach ($works_joinery_t->result_array() as $joinery_row){
					$work_joinery_id = $joinery_row['works_id']."-".$joinery_row['work_joinery_id'];
					$works_t = $this->works_m->display_work_contructor($work_joinery_id);
					if($proj_works_t->num_rows !== 0){	
						foreach($works_t->result_array() as $works_row){
				?>
				<tr class="" <?php if($works_row['is_selected']){ ?> style = "color: #009933" <?php } ?>>
					<td class="">
						<input type="checkbox" name = "sel_cotnractor" class = "cont_checkbox" value = "<?php echo  $works_row['works_contrator_id'] ?>">
					</td>
					<td class="">
						<?php echo $works_row['company_name'] ?>
					</td>
					<td class="">
						<?php echo $work_desc."(".$joinery_row['joinery_name'].")" ?>
					</td>
					<td class="">
					<?php 
						if($works_row['cqr_created'] == 1){
					?>
						<img src="<?php echo base_url() ?>img/send_email/pdf-check.png" alt="" onclick = "show_cqr('<?php echo $works_row['works_contrator_id'] ?>')" style = "width: 20px" title = "CQR Created">
					<?php
						}else{
					?>
						<img src="<?php echo base_url() ?>img/send_email/pdf-disabled.png" alt="" style = "width: 20px" title = "CQR Not Created">
					<?php
						}

						if($works_row['cpo_created'] == 1){
					?>
						<img src="<?php echo base_url() ?>img/send_email/pdf-check.png" alt="" onclick = "show_joinery_cpo('<?php echo $works_row['works_contrator_id'] ?>')" style = "width: 20px" title = "CPO Created">
					<?php
						}else{
					?>
						<img src="<?php echo base_url() ?>img/send_email/pdf-disabled.png" alt="" style = "width: 20px" title = "CPO Not Created">
					<?php
						}

						if($works_row['cqr_send'] == 1){
					?>
						<img src="<?php echo base_url() ?>img/send_email/cqr-send.png" alt="" style = "width: 20px" title = "CQR Send">
					<?php
						}else{
					?>
						<img src="<?php echo base_url() ?>img/send_email/cqr-disabled.png" alt="" style = "width: 20px" title = "CQR Not Send">
					<?php
						}

						if($works_row['cpo_send'] == 1){
					?>
						<img src="<?php echo base_url() ?>img/send_email/cpo-send.png" alt="" style = "width: 20px" title = "CPO Send">
					<?php
						}else{
					?>
						<img src="<?php echo base_url() ?>img/send_email/cpo-disabled.png" alt="" style = "width: 20px" title = "CPO Not Send">
					<?php
						}
					?>	
					</td>
				</tr>
				<?php
						}
					}
				}
			}
		}
		?>
	</tbody>
</table>