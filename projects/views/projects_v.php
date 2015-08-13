<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>
<?php $this->load->module('projects'); ?>

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
					<!--<li <?php if($screen=='Client'){ echo 'class="active"';} ?> >
						<a href="<?php echo base_url(); ?>projects" class="btn-small">Projects</a>
					</li>				
					 <li>
						<a href="" class="btn-small"><i class="fa fa-magic"></i> Tour</a>
					</li> -->			
					<li>
						<a class="btn-small sb-open-right"><i class="fa fa-file-text-o"></i> Project Comments</a>
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
					<div class="col-md-9">

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
								<?php if($this->session->userdata('projects') >= 2): ?>
									<a href="<?php echo current_url(); ?>/add" class="btn btn-primary pull-right"><i class="fa fa-briefcase"></i>&nbsp; Add New</a>
								<?php endif; ?>
									<div class="clearfix"></div>
									<select class="form-control m-top-10 select-client-tbl right" style="float: right; width: 180px;">
										<option value="">Select Client</option>
										<?php $this->company->company_list('dropdown'); ?>
									</select>
									
									<select class="form-control m-top-10 select-status-tbl right"  style="float: right; width: 110px; margin-right: 5px;">
										<option value="">Status</option>
										<option value="wip">WIP</option>
										<option value="unset">Not WIP</option>
										<option value="invoiced">Invoiced</option>
										<option value="paid">Paid</option>
									</select>

									<?php if($this->session->userdata('user_role_id') == 3 || $this->session->userdata('user_role_id') == 2 || $this->session->userdata('user_role_id') == 8 || $this->session->userdata('user_role_id') == 7 ): ?>

									
										<select class="form-control m-top-10 select-personal right"  style="float: right; width: 120px; margin-right: 5px;">
											
											<option value="ORD">View All</option>

											<?php if($this->session->userdata('user_role_id') == 3 ){
													echo '<option value="PM">Personal</option>';
												}else if($this->session->userdata('user_role_id') == 2 ){
													echo '<option value="PA">Personal</option>';
												}elseif($this->session->userdata('user_role_id') == 8){
													echo '<option value="EST">Personal</option>';
											
												}
											?>

										</select>

									<?php endif; ?>


								</div>
								<label><?php echo $screen; ?> List</label><span> (<a href="#" data-placement="right" class="popover-test" title="" data-content="Hello there mate! Welcome to the clients screen." data-original-title="Welcome">?</a>)</span>
								<p>This is where the companies are listed.</p>
								<p><a href="#" class="tooltip-test" title="Tooltip">This link</a> and <a href="#" class="tooltip-test" title="Tooltip">that link</a> should have tooltips on hover.</p>								
							</div>
							<div class="box-area pad-10">
								<table id="projectTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead> <tr> <th>Number</th> <th>Project Name</th> <th>Client</th> <th>Category</th> <th>Job Date</th> <th>Total Cost</th> <th>Personal</th> <th>Status</th></tr> </thead> 
									 
									<tbody>
										<?php echo $this->projects->display_all_projects(); ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<div class="col-md-3">
						<div class="box">
							<div class="box-head pad-5">
								<label><i class="fa fa-info-circle fa-lg"></i> Information</label>
							</div>
							<div class="box-area pad-5">
								<p>
									The table can be sortable by the header. It has search feature using a sub text or keyword you are searching. Clicking the Name of the company will lead to the Company Details Screen.
								</p>
							</div>
						</div>
					</div>

					<div class="col-md-3">
						<div class="box">
							<div class="box-head pad-5">
								<label><i class="fa fa-info-circle fa-lg"></i> Color Codes: &nbsp; &nbsp;
								<strong class="wip">WIP</strong> &nbsp; &nbsp;
								<strong class="invoiced">Invoiced</strong> &nbsp; &nbsp;
								<strong class="paid">Paid</strong></label>
							</div>
						</div>
					</div>
					
				</div>				
			</div>
		</div>
	</div>
</div>
   
       


<?php $this->load->view('assets/logout-modal'); ?>