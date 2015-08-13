<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>
<?php $this->load->module('users'); ?>
<?php 
	if($this->session->userdata('is_admin') != 1 ){
		redirect('', 'refresh');
	}
?>


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
						<a href="<?php echo base_url(); ?>admin" class="btn-small">Defaults</a>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>admin/company" class="btn-small">Company</a>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>users" class="btn-small">Users</a>
					</li>
					<li>
						<a href="#" class="btn-small btn-primary" data-toggle="modal" data-target="#userlog_filter_modal"><i class="fa fa-print"></i> Report</a>
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
						<div class="left-section-box">
							 
 
  


							<?php if(@$msg): ?>
								<div class="pad-10 no-pad-t">
									<div class="border-less-box alert alert-info fade in">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										<h4>Coming Next!</h4>
										<?php echo $msg;?>
									</div>
								</div>
							<?php endif; ?>



							<div class="box-head pad-10 clearfix">
								<label><i class="fa fa-users  fa-lg"></i> <?php echo $screen; ?></label>
						
							</div>
							<div class="box-area pad-10">


								<table id="userLogsTble" class="table table-striped table-bordered" cellspacing="0" width="100%">
								<thead><tr><th>Project Number</th><th>Action Type</th><th>Action</th><th>Date</th><th>Time</th><th>Full Name</th><th>th_raw</th><th>Project Name</th><th>Client</th></tr></thead>
									<tbody>
										<?php foreach ($user_logs->result() as $logs): ?>
											<?php $date_raw = strtotime(str_replace('/', '-',$logs->date)); ?>
											<?php echo '<tr><td><a href="'.base_url().'projects/view/'.$logs->project_id.'">'.$logs->project_id.'</a></td><td>'.$logs->type.'</td><td>'.$logs->actions.'</td><td>'.$logs->date.'</td><td>'.$logs->time.'</td><td>'.$logs->user_first_name.' '.$logs->user_last_name.'</td><td>'.$date_raw.'</td><td>'.$logs->project_name.'</td><td>'.$logs->company_name.'</td></tr>'; ?>
										<?php endforeach;  ?>
									</tbody>
								</table> 
							
      							<div class="clearfix"></div>

								<p>&nbsp;</p>
							</div>
						</div>
					</div>					

					
					
				</div>				
			</div>
		</div>
	</div>
</div>


<?php $this->load->view('assets/logout-modal'); ?>


<!-- Modal -->
<div class="modal fade" id="userlog_filter_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">User-Log Filters</h4>        
      </div>
      <div class="modal-body clearfix pad-10">

      	<div class="error_area"></div>

      	<div class="col-xs-12 m-bottom-10 clearfix ">
      		<label for="account-name" class="col-sm-5 m-top-5 control-label">Project Number</label>
      		<div class="col-sm-7">
      			<input type="text" class="form-control" id="project_number" name="project_number" placeholder="Project Number" value="">
      		</div>
      	</div>

      	<div class="col-xs-12 m-bottom-10 clearfix ">
      		<label for="account-name" class="col-sm-5 m-top-5 control-label">Action Type</label>
      		<div class="col-sm-7">
      			<select class="form-control type chosen" name="type" id="type">
      				<option value="">Select Type</option>
      				<option value="Added">Added</option>
      				<option value="Update">Update</option>
      				<option value="Remove">Remove</option>
      			</select>
      		</div>
      	</div>

      	<div class="col-xs-12 m-bottom-10 clearfix ">
      		<label for="account-name" class="col-sm-5 m-top-5 control-label">Full Name</label>
      		<div class="col-sm-7">
      			<select class="form-control user chosen" name="user" id="user">
      				<option value="">Select Name</option>
      				<?php foreach ($users_q->result_array() as $user){
      					echo '<option value="'.$user['user_first_name'].' '.$user['user_last_name'].'">'.$user['user_first_name'].' '.$user['user_last_name'].'</option>';
      				}?>
      			</select>											
      		</div>
      	</div>

      	<div class="col-xs-12 m-bottom-10 clearfix ">
      		<label for="account-name" class="col-sm-5 m-top-5 control-label">Date Segment A</label>
      		<div class="col-sm-7">
      			<input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control datepicker filter_date_a" id="filter_date_a" name="filter_date_a" value="">
      		</div>
      	</div>

      	<div class="col-xs-12 m-bottom-10 clearfix ">
      		<label for="account-name" class="col-sm-5 m-top-5 control-label">Date Segment B</label>
      		<div class="col-sm-7">
      			<input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control datepicker filter_date_b" id="filter_date_b" name="filter_date_b" value="">
      		</div>
      	</div>
      	<div class="clearfix"></div>
      	<p>&nbsp;</p>

      	<a href="?reload=true" class="btn btn-warning pull-left reload_btn">Reset Filter</a>

        <div class="pull-right">

          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary userLogsBtn" data-dismiss="modal">Submit</button>
        </div>

      </div>
    </div>
  </div>
</div>



<?php if(!isset($_POST['is_form_submit'])){	echo '<script type="text/javascript">$("#first_name").focus();</script>';}?>