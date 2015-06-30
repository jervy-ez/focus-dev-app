<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>
<?php $this->load->module('projects'); ?>
<?php $this->load->module('invoice'); ?>
<!-- title bar -->
<div class="container-fluid head-control">
	<div class="container-fluid">
		<div class="row">

			<div class="col-md-6 col-sm-4 col-xs-12 pull-left">
				<header class="page-header">
					<h3><?php $datestring = "%l, %F %d, %Y"; $time = time(); //use time() for timestamp  ?>
						<?php echo $screen; ?> Screen<br><small><?php echo mdate($datestring, $time); #echo date("l, F d, Y"); ?></small>
					</h3>
				</header>
			</div>

			<div class="page-nav-options col-md-6 col-sm-8 col-xs-12 pull-right hidden-xs">
				<ul class="nav nav-tabs navbar-right">
					<li>
						<a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Home</a>
					</li>
					<li>
						<a href="#" class="btn-small btn-primary filter-wip" data-toggle="modal" data-target="#wip_filter_modal"><i class="fa fa-filter fa-lg"></i> Filter WIP</a>
					</li>

					<li>
						<a href="#" class="btn-small btn-primary print-wip"><i class="fa fa-print fa-lg"></i> Setup WIP Report</a>
					</li>

					<li>
						<a href="<?php echo base_url(); ?>wip" class="btn btn-small btn-warning"><i class="fa fa-refresh fa-lg"></i> Reset Table</a>
					</li>

					<li>
						<a href="" class="btn-small"><i class="fa fa-magic"></i> Tour</a>
					</li>
				</ul>
			</div>

		</div>
	</div>
</div>
<!-- title bar -->

<div class="container-fluid">
	<!-- Example row of columns -->
	<div class="row">				
		<?php $this->load->view('assets/sidebar'); ?>
		<div class="section col-sm-12 col-md-11 col-lg-11">
			<div class="container-fluid">

				<div class="row">
					<div class="col-md-12">

						<div class="left-section-box clearfix">


						<?php if(@$this->session->flashdata('project_deleted')): ?>
							<div class="m-15">
								<div class="border-less-box alert alert-danger fade in">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<h4>Opps! No turning back now!</h4>
									<?php echo $this->session->flashdata('project_deleted');?>
								</div>
							</div>
						<?php endif; ?>

						<div class="clearfix"></div>

							<div class="box-head pad-10 clearfix">
								<div class="pull-right" style="margin-top: -15px;">
									<div class="clearfix m-top-20">
										<div class="box-content box-list collapse in prj_cmmnt_area pull-right" style="display:none; visibility: hidden;;"><ul><li><p>No posted comments yet!</p></li></ul></div>
										<div class="box-area totals_wip pull-right"></div>
									</div>
								</div>
								<label><?php echo $screen; ?> List</label>							
							</div>
							<div class="box-area pad-10">
								<div class="wip-area-print">
									<table id="wipTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
										<thead> <tr> <th>Number</th> <th>Project Name</th> <th>Client</th> <th>Project Manager</th><th>Job Date</th><th>Date Start</th><th>Date Finish</th> <th >job_category</th><th>Project Total</th>  </tr> </thead> 

										<tbody>

											<?php
											foreach ($proj_t->result_array() as $row){
												echo '<tr><td><a onmouseover="showProjectCmmnts('.$row['project_id'].')" href="'.base_url().'projects/view/'.$row['project_id'].'" >'.$row['project_id'].'</a></td><td>'.$row['project_name'].'</td><td>'.$row['company_name'].'</td><td>'.$row['user_first_name'].' '.$row['user_last_name'].'</td><td>'.$row['job_date'].'</td><td>'.$row['date_site_commencement'].'</td><td>'.$row['date_site_finish'].'</td><td>'.$row['job_category'].'</td>';

												if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 ){
													echo '<td>'.number_format($row['project_total']).'</td></tr>';

												}else{
													echo '<td class="green-estimate">'.number_format($row['budget_estimate_total']).'</td></tr>';
												}

											}
											?>

										</tbody>
									</table>									
								</div>
							</div>
						</div>
					</div>
				</div>				
			</div>
		</div>
	</div>
</div>

<div class="dataTables_info" id="print_wip_table_area">
	<table class="dynamic_wip_table" cellspacing="0" width="100%" role="grid" style="width: 100%;">

		<?php




			echo '<thead><tr><th>Finish</th><th>Start</th><th>Client</th><th>Project</th><th>Total</th><th>Job Date</th><th>Install Hrs</th><th>Project Number</th><th>Invoiced $</th></tr></thead><tbody>';

			foreach ($proj_t->result_array() as $row){
				echo '<tr><td>'.$row['date_site_finish'].'</td><td>'.$row['date_site_commencement'].'</td><td>'.$row['company_name'].'</td><td>'.$row['project_name'].'</td>';

				if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 ){
					echo '<td>'.number_format($row['project_total']).'</td>';
				}else{
					echo '<td class="green-estimate">'.number_format($row['budget_estimate_total']).'</td>';
				}

				echo '<td>'.$row['job_date'].'</td>';

				if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 ){
					echo '<td>'.number_format($row['install_time_hrs']).'</td>';
				}else{
					echo '<td class="green-estimate">'.number_format($row['labour_hrs_estimate']).'</td>';
				}

				echo '<td>'.$row['project_id'].'</td><td>'.number_format($this->invoice->get_project_invoiced($row['project_id'],$row['project_total'])).'</td></tr>';

				


			}

			echo '</tbody>';

		?>
	</table>


</div>
<style type="text/css">

.dataTables_filter, .dataTables_info {
   display: none;
   visibility: hidden;
}

</style>

<script type="text/javascript">$('.dataTables_filter').remove();</script>

<?php $this->load->view('assets/logout-modal'); ?>


<!-- wip_filter_modal -->
<div class="modal fade" id="wip_filter_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">WIP Filter</h4>
      </div>
      <div class="modal-body">

      	<?php $clients = array(); ?>

      	<?php
	      	foreach ($proj_t->result_array() as $row){array_push($clients,$row['company_name']);}
	      	$clients = array_unique($clients);
      	?>

      	<div class="input-group m-bottom-10">
      		<span class="input-group-addon" id="">
      			<i class="fa fa-briefcase"></i>
      		</span>
      		<select class="form-control select-client-tbl m-bottom-10">
      			<option value="">Select Client</option>     		

      			<?php
	      			foreach ($clients as $row => $value){echo '<option value="'.$value.'" >'.$value.'</option>';}
      			?> 
      		</select>
      	</div>

      	<div class="input-group m-bottom-10">
      		<span class="input-group-addon" id="">
      			<i class="fa fa-user"></i>
      		</span>
      		<select class="form-control select-pm-tbl m-bottom-10">
      			<option value="">Select Project Manager</option>
      			<?php
      			foreach ($users->result_array() as $row){
      				if($row['user_role_id']==3):
      					echo '<option value="'.$row['user_first_name'].' '.$row['user_last_name'].'" >'.$row['user_first_name'].' '.$row['user_last_name'].'</option>';
      				endif;
      			}
      			?>
      		</select>
      	</div>


      	



      	

      	<div class="box-area clearfix  m-bottom-10">
      		<select class="form-control select-cat-tbl chosen-multi" id="select-cat-tbl" multiple="multiple">
      			<option selected="selected" value="Kiosk">Kiosk</option>
      			<option selected="selected" value="Full Fitout">Full Fitout</option>
      			<option selected="selected" value="Refurbishment">Refurbishment</option>
      			<option selected="selected" value="Strip Out">Strip Out</option>
      			<option selected="selected" value="Minor Works">Minor Works (Under $20,000.00)</option>
      			<option selected="selected" value="Maintenance">Maintenance</option>
      		</select>
      	</div>

      	<div class="input-group m-bottom-10">
      		<span class="input-group-addon" id="">
      			<i class="fa fa-calendar"></i>
      		</span>
      		<input type="text" data-date-format="dd/mm/yyyy" placeholder="From" class="form-control datepicker" id="finish_date_start" name="finish_date_start" value="" >
      	</div>



      	<div class="input-group m-bottom-10">
      		<span class="input-group-addon" id="">
      			<i class="fa fa-calendar"></i>
      		</span>
      		<input type="text" data-date-format="dd/mm/yyyy" placeholder="To" class="form-control datepicker" id="finish_date" name="finish_date" value="" >
      	</div>


      	<div class="input-group m-bottom-10">
      		<span class="input-group-addon" id="">$</span>
      		<input type="text" placeholder="Less Than Project Total Range" class="form-control number_format" id="cost_total" name="cost_total" value="">
      	</div>



      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="filter_wip_table" data-dismiss="modal"><i class="fa fa-filter"></i> Filter</button>
      </div>
    </div>
  </div>
</div>
<!-- wip_filter_modal -->