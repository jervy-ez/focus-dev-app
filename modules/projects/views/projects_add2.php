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
						New Project<br><small><?php echo mdate($datestring, $time); #echo date("l, F d, Y"); ?></small>
					</h3>
				</header>
			</div>

			<div class="page-nav-options col-md-6 col-sm-8 col-xs-12 pull-right hidden-xs">
				<ul class="nav nav-tabs navbar-right">
					<li>
						<a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Home</a>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>projects" class="btn-small">Projects</a>
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
			<form class="form-horizontal" role="form" method="post" action="">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-9">
							<div class="left-section-box">				
					
								<?php if(@$error): ?>
								<div class="pad-10 no-pad-t">
									<div class="border-less-box alert alert-danger fade in">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										<h4>Oh snap! You got an error!</h4>
										<?php echo $error;?>
									</div>
								</div>
								<?php endif; ?>
					
								
									<div class="box-head pad-10 clearfix">
										<div class="pull-right">

											<button  type="reset" class="btn btn-warning pull-right">Reset Form</button ><br />
											<select name="focus" class="form-control focus m-top-10 select-focus" id="focus">
												<option value="">Select Focus Company</option>
												<?php foreach ($focus as $key => $value): ?>
													<option value="<?php echo $value->company_id; ?>"><?php echo $value->company_name; ?></option>
												<?php endforeach; ?>
											</select>

											<script type="text/javascript">$('.focus').val('<?php echo $this->session->userdata('user_focus_company_id'); ?>');</script>
											

										</div>	
										<label>Add New Project</label><span> (<a href="#" data-placement="right" class="popover-test" title="" data-content="Hello there mate! Welcome to the clients screen." data-original-title="Welcome">?</a>)</span>
										<p>Fields having * is requred.</p>	
										<p><a href="#" class="tooltip-test" title="Tooltip">This link</a> and <a href="#" class="tooltip-test" title="Tooltip">that link</a> should have tooltips on hover.</p>						
									</div>
									
									<div class="box-area pad-10 clearfix">											
										
										<div class="form-group clearfix pad-5 no-pad-b">
	        								<div class="col-sm-12">
	         									<div class="input-group <?php if(form_error('project_name')){ echo 'has-error has-feedback';} ?>">
													<span class="input-group-addon"><i class="fa fa-briefcase  fa-lg"></i></span>
													<input type="text" class="form-control" placeholder="Project Name*" name="project_name" id="project_name" tabindex='1' value="<?php echo $this->input->post('project_name'); ?>">
												</div>
	        								</div>
	      								</div>

	      								<div class="juris"></div>
	      								
	      								<div class="box m-bottom-15 clearfix">
											<div class="box-head pad-5 m-bottom-5">
												<label><i class="fa fa-book fa-lg"></i> General</label>
											</div>
											
											<div class="box-area pad-5 clearfix">																																
												
												
												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="client_po" class="col-sm-3 control-label">Client PO</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="client_po" placeholder="Client PO" tabindex='2' name="client_po" value="<?php echo $this->input->post('client_po'); ?>">
													</div>
												</div>
												
												
												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('job_category')){ echo 'has-error has-feedback';} ?>">
													<label for="job_category" class="col-sm-3 control-label">Job Type*</label>													
													<div class="col-sm-9  col-xs-12">
														<select class="form-control postcode-option" id="job_type" name="job_type" tabindex='3'>
															<option value="">Choose a Job Type...</option>
															<option value="Shopping Center">Shopping Center</option>
															<option value="Street Site">Street Site</option>
															<option value="Office">Office</option>															
														</select>
														<script type="text/javascript">$("select#job_type").val("<?php echo $this->input->post('job_type'); ?>");</script>
													</div>
												</div>

												
												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('job_category')){ echo 'has-error has-feedback';} ?>">
													<label for="job_category" class="col-sm-3 control-label">Job Category*</label>													
													<div class="col-sm-9  col-xs-12">
														<select class="form-control postcode-option" id="job_category" name="job_category" tabindex='4'>															
															<option value="">Choose a Job Category...</option>
															<option value="Kiosk">Kiosk</option>
															<option value="Full Fitout">Full Fitout</option>
															<option value="Refurbishment">Refurbishment</option>
															<option value="Strip Out">Strip Out</option>
															<option value="Minor Works">Minor Works (below $XXXX)</option>															
															<option value="Maintenance">Maintenance</option>
															<script type="text/javascript">$("select#job_category").val("<?php echo $this->input->post('job_category'); ?>");</script>
														</select>
													</div>
												</div>
											</div>
										</div>
	      								
	      								<div class="box m-bottom-15 clearfix">
											<div class="box-head pad-5 m-bottom-5">
												<label><i class="fa fa-calendar fa-lg"></i> Dates</label>
											</div>
											
											<div class="box-area pad-5 clearfix">												
												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('project_date')){ echo 'has-error has-feedback';} ?>">
													<label for="project_date" class="col-sm-3 control-label">Project Date*</label>
													<div class="col-sm-9">
														<input id="project_date" class="project_date form-control" name="project_date" readonly="readonly" type="text" value="<?php echo date("d/m/Y"); ?>">
													</div>	
												</div>
												
												<div class="col-sm-6 m-bottom-10 clearfix" >
													<label for="job_date" class="col-sm-3 control-label">Job Date (<strong style="cursor: pointer;" class="popover_form_job_date_tri">?</strong>)</label>
													<div class="col-sm-9">
														<input  tabindex='5' type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" title="Warning: Changing a value in the the Job date affects the project in the WIP section." class="tooltip-enabled job-date-set form-control datepicker" id="job_date" name="job_date" tabindex="4" value="<?php echo $this->input->post('job_date'); ?>">
														<a class="popover_form_job_date" data-placement="bottom" title="Job Date" data-content="Hi, place a value for the Job Date if the project is accepted."></a>
													</div>													
												</div>
												
												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('site_start')){ echo 'has-error has-feedback';} ?>">
													<label for="site_start" class="col-sm-3 control-label">Site Start*</label>
													<div class="col-sm-9">
														<input tabindex='6' type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control datepicker" id="site_start" name="site_start" tabindex="4" value="<?php echo $this->input->post('site_start'); ?>">
													</div>
												</div>
													
												<div class="col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('site_finish')){ echo 'has-error';} ?>">
													<label for="site_finish" class="col-sm-3 control-label">Site Finish*</label>
													<div class="col-sm-9 col-xs-12">
														<input tabindex='7' type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control datepicker" id="site_finish" name="site_finish" tabindex="4" value="<?php echo $this->input->post('site_finish'); ?>">
													</div>
												</div>
																					
											</div>
										</div>

										<div class="box-tabs m-bottom-10">
											<ul id="myTab" class="nav nav-tabs">
												<li class="active">
													<a href="#physicalAddress" data-toggle="tab"><i class="fa fa-globe fa-lg"></i> Site Address</a>
												</li>
												<li class="">
													<a href="#postalAddress" data-toggle="tab"  tabindex="14" ><i class="fa fa-inbox fa-lg"></i> Invoice Address</a>
												</li>
												<input type="hidden" name="is_form_submit" value="1">
												<li class="pull-right m-top-10">
													<strong>Same values to Postal Address? <input type="checkbox" class="sameToPost" name="issamepost"> Yes</strong>
													<?php

													if(isset($_POST['is_form_submit'])){

														if($this->input->post('issamepost') != 'on' ){
															echo '<script type="text/javascript">';
															echo '$("input.sameToPost").prop( "checked", false ); var sameToPost = 0;';	
															echo '</script>';
															$sameToPost = 0;

														}else{
															echo '<script type="text/javascript">';
															echo '$("input.sameToPost").click();';
															echo '$("input.sameToPost").prop( "checked", true ); var sameToPost = 1;';	
															echo '</script>';
															$sameToPost = 1;

														}
													}else{
														echo '<script type="text/javascript">';
														echo '$("input.sameToPost").click();';
														echo '$("input.sameToPost").prop( "checked", true ); var sameToPost = 1; ';	
														echo '</script>';
														$sameToPost = 1;
													}

													?>
												</li>										
											</ul>
											<div class="tab-content">
												<div class="tab-pane fade active in clearfix" id="physicalAddress">

													<div class="col-sm-6 m-bottom-10 clearfix">
														<label for="unit_level" class="col-sm-3 control-label">Unit/Level</label>
														<div class="col-sm-9">
															<input type="text" class="form-control" id="unit_level" placeholder="Unit/Level" name="unit_level" tabindex="8" value="<?php echo $this->input->post('unit_level'); ?>">
														</div>
													</div>

													<div class="col-sm-6 m-bottom-10 clearfix">
														<label for="number" class="col-sm-3 control-label">Number</label>
														<div class="col-sm-9">
															<input type="text" class="form-control" id="number" placeholder="Number" tabindex="9" name="unit_number" value="<?php echo $this->input->post('unit_number'); ?>">
														</div>
													</div>

													<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('street')){ echo 'has-error has-feedback';} ?>">
														<label for="street" class="col-sm-3 control-label">Street*</label>
														<div class="col-sm-9">
															<input type="text" class="form-control" id="street" placeholder="Street" tabindex="10" name="street" value="<?php echo $this->input->post('street'); ?>">
														</div>
													</div>

													<div class="clearfix"></div>


													<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('state_a')){ echo 'has-error has-feedback';} ?>">
														<label for="state" class="col-sm-3 control-label">State*</label>													
														<div class="col-sm-9">

															<select class="form-control state-option-a chosen"  tabindex="11" id="state_a" name="state_a">															
																<option value="">Choose a State</option>
																<?php
																foreach ($all_aud_states as $row){
																	echo '<option value="'.$row->shortname.'|'.$row->name.'|'.$row->phone_area_code.'|'.$row->id.'">'.$row->name.'</option>';
																}?>
															</select>
															<script type="text/javascript">$("select#state_a").val("<?php echo $this->input->post('state_a'); ?>");</script>
														</div>



													</div>

													<div class="col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('suburb_a')){ echo 'has-error';} ?>">
														<label for="suburb" class="col-sm-3 control-label">Suburb*</label>
														<div class="col-sm-9 col-xs-12">

															<?php if($this->input->post('suburb_a')): ?>
																<select class="form-control suburb-option-a chosen" id="suburb_a"  tabindex='12' name="suburb_a">

																<?php else: ?>
																	<select class="form-control suburb-option-a disabled chosen"  tabindex="12"  id="suburb_a" name="suburb_a">
																		<option value="">Choose a Suburb...</option>

																	<?php endif; ?>



																	<?php if($this->input->post('suburb_a')): ?>
																		<?php $this->company->get_suburb_list('dropdown|state_id|'.$this->input->post('state_a')); ?>														
																		<script type="text/javascript">$("select#suburb_a").val("<?php echo $this->input->post('suburb_a'); ?>");</script>

																	<?php endif; ?>

																</select>
															</div>
														</div>

												<!--
												<div id="datepicker" class="input-prepend date">
													<span class="add-on"><i class="icon-th"></i></span>
													<input class="span2" type="text" value="02-16-2012">
												</div>
											-->



											<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('postcode_a')){ echo 'has-error has-feedback';} ?>">
												<label for="postcode_a" class="col-sm-3 control-label">Postcode*</label> <?php //echo $this->input->post('postcode_a'); ?>													
												<div class="col-sm-9  col-xs-12">



													<?php if($this->input->post('postcode_a')): ?>
														<select class="form-control postcode-option-a chosen" id="postcode_a"  tabindex="13" name="postcode_a">
														<?php else: ?>
															<select class="form-control postcode-option-a disabled chosen"   tabindex="13"  id="postcode_a" name="postcode_a">
																<option value="">Choose a Postcode...</option>
															<?php endif; ?>

															<?php if($this->input->post('postcode_a')): ?>
																<?php $suburb_a = explode('|', $this->input->post('suburb_a')); ?>
																<?php $this->company->get_post_code_list($suburb_a[0]); ?>													

																<script type="text/javascript">$("select#postcode_a").val("<?php echo $this->input->post('postcode_a'); ?>");</script>

															<?php endif; ?>		

														</select>
													</div>
												</div>
												


											</div>
											<div class="tab-pane fade clearfix" id="postalAddress">
												
												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="unitlevel2" class="col-sm-3 control-label">Unit/Level</label>
													<div class="col-sm-9 <?php echo ($sameToPost == 1 ? 'disabled-input' : '' ); ?>">
														<input type="text" class="form-control" id="unitlevel2"  tabindex="15"  placeholder="Unit/Level" name="unit_level_b" value="<?php echo $this->input->post('unit_level_b'); ?>">
													</div>
												</div>

												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="number2" class="col-sm-3 control-label">Number</label>
													<div class="col-sm-9 <?php echo ($sameToPost == 1 ? 'disabled-input' : '' ); ?>">
														<input type="text" class="form-control" id="number2" placeholder="Number"   tabindex="16" name="number_b" value="<?php echo $this->input->post('number_b'); ?>">
													</div>
												</div>

												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('street_b')){ echo 'has-error has-feedback';} ?>">
													<label for="street2" class="col-sm-3 control-label">Street*</label>
													<div class="col-sm-9 <?php echo ($sameToPost == 1 ? 'disabled-input' : '' ); ?>">
														<input type="text" class="form-control" id="street2" placeholder="Street"   tabindex="17" name="street_b" value="<?php echo $this->input->post('street_b'); ?>">
													</div>
												</div>

												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="pobox" class="col-sm-3 control-label">PO Box</label>
													<div class="col-sm-9 <?php echo ($sameToPost == 1 ? 'disabled-input' : '' ); ?>">
														<input type="text" class="form-control" id="pobox" placeholder="PO Box" name="pobox"  tabindex="18"  value="<?php echo $this->input->post('pobox'); ?>">
													</div>
												</div>

												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('state_b')){ echo 'has-error has-feedback';} ?>">
													<label for="state_b" class="col-sm-3 control-label">State*</label>
													

													<div class="col-sm-9">
														<!-- <input type="text" class="form-control" id="state_a" placeholder="State" name="state_a" value="<?php echo $this->input->post('state_a'); ?>"> -->

														<select class="form-control state-option-b chosen <?php echo ($sameToPost == 1 ? 'disabled-input' : '' ); ?>"  id="state_b"   tabindex="19" name="state_b"  >															
															<option value="">Choose a State</option>
															<?php
															foreach ($all_aud_states as $row){
																echo '<option value="'.$row->shortname.'|'.$row->name.'|'.$row->phone_area_code.'|'.$row->id.'">'.$row->name.'</option>';
															}?>


														</select>
														<script type="text/javascript">$("select#state_b").val("<?php echo $this->input->post('state_b'); ?>");</script>
													</div>

												</div>												
												
												<div class="col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('suburb_b')){ echo 'has-error';} ?>">
													<label for="suburb" class="col-sm-3 control-label">Suburb*</label>
													<div class="col-sm-9 col-xs-12">

														<?php if($this->input->post('suburb_b')): ?>
															<select class="form-control suburb-option-b chosen" id="suburb_b" tabindex="20" name="suburb_b">

															<?php else: ?>
																<select class="form-control suburb-option-b chosen <?php echo ($sameToPost == 1 ? 'disabled-input' : '' ); ?>" id="suburb_b" tabindex="20" name="suburb_b">
																	<option value="">Choose a Suburb...</option>
																<?php endif; ?>


																<?php if($this->input->post('suburb_b')): ?>
																	<?php $this->company->get_suburb_list('dropdown|state_id|'.$this->input->post('state_b')); ?>														
																	<script type="text/javascript">$("select#suburb_b").val("<?php echo $this->input->post('suburb_b'); ?>");</script>

																<?php endif; ?>

															</select>
														</div>
													</div>



												<!-- <div class="col-sm-6 m-bottom-10 clearfix">
													<label for="postcode2" class="col-sm-3 control-label">Postcode*</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="postcode2" placeholder="Postcode">
													</div>
												</div> -->
												
												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('postcode_b')){ echo 'has-error has-feedback';} ?>">
													<label for="postcode_b" class="col-sm-3 control-label">Postcode*</label>													
													<div class="col-sm-9  col-xs-12">
														
														<?php if($this->input->post('postcode_b')): ?>
															<select class="form-control postcode-option-b chosen" id="postcode_b"   tabindex="21" name="postcode_b">
															<?php else: ?>
																<select class="form-control postcode-option-b chosen <?php echo ($sameToPost == 1 ? 'disabled-input' : '' ); ?>" id="postcode_b" tabindex="21" name="postcode_b">
																	<option value="">Choose a Postcode...</option>
																<?php endif; ?>

																<?php if($this->input->post('postcode_b')): ?>
																	<?php $suburb_a = explode('|', $this->input->post('suburb_b')); ?>
																	<?php $this->company->get_post_code_list($suburb_a[0]); ?>													

																	<script type="text/javascript">$("select#postcode_b").val("<?php echo $this->input->post('postcode_b'); ?>");</script>

																<?php endif; ?>


															</select>
														</div>
													</div>


												</div>
											</div>
										</div>

										<div class="clearfix"></div>													
	      								
										
										<div class="box m-bottom-15 clearfix">
											<div class="box-head pad-5 m-bottom-5">
												<label><i class="fa fa-users fa-lg"></i> Personel</label>
											</div>
											
											<div class="box-area pad-5 clearfix">
												<div class="col-sm-4 m-bottom-5 clearfix <?php if(form_error('project_manager')){ echo 'has-error has-feedback';} ?>">
													<div class="col-sm-12">
														<label for="project_manager" class="control-label">Project Manager*</label><br />
														<select name="project_manager" class="form-control presonel_add" id="project_manager" style="width: 100%;" tabindex="22">
															<option value=''>Select Project Manager</option>
															<?php
															foreach ($project_manager as $row){
																echo '<option value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.'</option>';
															}?>
														</select>
														<script type="text/javascript">$('select#project_manager').val('<?php echo $this->input->post('project_manager'); ?>');</script>	
																							
													</div>
												</div>
	   								
			      								<div class="col-sm-4 m-bottom-5 clearfix <?php if(form_error('project_admin')){ echo 'has-error has-feedback';} ?>">											
													<div class="col-sm-12">
														<label for="project_administrator" class="control-label">Project Admin*</label>
														<select name="project_admin" class="form-control presonel_add" id="project_administrator" style="width: 100%;" tabindex="23">
															<option value=''>Select Project Admin</option>
															<?php
															foreach ($project_administrator as $row){
																echo '<option value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.'</option>';
															}?>
														</select>
														<script type="text/javascript">$('select#project_administrator').val('<?php echo $this->input->post('project_admin'); ?>');</script>														
													</div>
												</div>
												
			      								<div class="col-sm-4 m-bottom-5 clearfix <?php if(form_error('estimator')){ echo 'has-error has-feedback';} ?>">						
													<div class="col-sm-12">
														<label for="estimator" class="control-label">Estimator*</label>													
														<select name="estimator" class="form-control presonel_add" id="estimator" style="width: 100%;" tabindex="24">
															<option value=''>Select Estimator</option>
															<?php
															foreach ($estimator as $row){
																echo '<option value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.'</option>';
															}?>
														</select>
														<script type="text/javascript">$('select#estimator').val('<?php echo $this->input->post('estimator'); ?>');</script>
													</div>
												</div>
												
											</div>
										</div>
	 									
										<div class="box">
											<div class="box-head pad-5 m-bottom-5">
												<label><i class="fa fa-phone-square fa-lg"></i> Client and Costs</label>
											</div>
											
											<div class="box-area pad-5 clearfix">
												
												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('officeNumber')){ echo 'has-error has-feedback';} ?>">
													<label for="company_prg" class="col-sm-3 control-label">Client*</label>
													<div class="col-sm-9">														
														<div class="input-group <?php if(form_error('company_prg')){ echo 'has-error has-feedback';} ?>">
															<span class="input-group-addon"><i class="fa fa-briefcase  fa-lg"></i></span>
															<select name="company_prg" class="form-control chosen find_contact_person" id="company_prg" style="width: 100%;" tabindex="25">
																<option value=''>Select Client Name*</option>																												
																<?php $this->company->company_list('dropdown'); ?>	
															</select>
															<script type="text/javascript">$('select#company_prg').val('<?php echo $this->input->post('company_prg'); ?>');</script>
														</div>
													</div>
												</div>
												
												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('contact_person')){ echo 'has-error has-feedback';} ?>">
													<label for="contact_person" class="col-md-4 col-sm-5 control-label">Contact Person*</label>
													<div class="col-md-8 col-sm-7 here">
														<select name="contact_person" class="form-control" id="contact_person" style="width: 100%;"  tabindex="26">		
															<option value=''>Select Contact Person*</option>													
															<?php //$this->company->contact_person_list(); ?>
															<script type="text/javascript">$('select#contact_person').val('<?php echo $this->input->post('contact_person'); ?>');</script>
														</select>
													</div>
												</div>

												<div class="clearfix"></div>

												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="install_hrs" class="col-sm-3 control-label">Site Hours </label>
													<div class="input-group ">
														<span class="input-group-addon">(Hrs)</span>
														<input type="text" name="install_hrs" id="install_hrs" class="form-control" tabindex="27" placeholder="Install Hrs" value="<?php echo ($this->input->post('install_hrs') ?  $this->input->post('install_hrs') : '250' ); ?>"/>
													</div>
												</div>

												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="is_double_time" class="col-sm-3 control-label">Is Double Time?</label>


													<div class="col-sm-9  col-xs-12">
														<select name="is_double_time" class="form-control" id="is_double_time" style="width: 100%;" tabindex="28">
															<option value="0">No</option>
															<option value="1">Yes</option>
														</select>
														<script type="text/javascript">$('select#is_double_time').val('<?php echo ($this->input->post('is_double_time') ?  $this->input->post('is_double_time') : '0' ); ?>');</script>
													</div>
												</div>

												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="project_total" class="col-sm-3 control-label">Project Estimate</label>
													<div class="input-group ">
														<span class="input-group-addon">($)</span>
														<input type="text" name="project_total" id="project_total" class="form-control"  tabindex="29" placeholder="Total" value="<?php echo $this->input->post('project_total'); ?>"/>
													</div>
												</div>


												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="project_markup" class="col-sm-3 control-label">Project Markup</label>
													<div class="input-group ">
														<span class="input-group-addon">(%)</span>
														<input type="text" name="project_markup" id="project_markup" class="form-control tooltip-enabled" tabindex="30" placeholder="Markup %" value="<?php echo $this->input->post('project_markup'); ?>" title="Warning: You cannot set a mark up lower than the minimum."/>
														<span class="input-group-addon">Min: <span class="min_mark_up">00</span>%</span>
													</div>

												</div>												
													
												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('areacode')){ echo 'has-error has-feedback';} ?>">
													<label for="project_area" class="col-sm-3 control-label">Project Area</label>
													<div class="col-sm-9">												

														<div class="input-group ">
															<span class="input-group-addon">SQM</span>
															<input type="text" name="project_area" id="project_area" class="form-control" tabindex="31" placeholder="Project Area" value="<?php echo $this->input->post('project_area'); ?>"/>
														</div>
													</div>
												</div>


											</div>
										</div>
										
										<div class="clearfix"></div>
									    
	      								<div class="box m-top-15">
											<div class="box-head pad-5">
												<label for="project_notes"><i class="fa fa-pencil-square fa-lg"></i> Project Notes</label>
											</div>
											
											<div class="box-area pad-5 clearfix">
												<div class="clearfix <?php if(form_error('generalEmail')){ echo 'has-error has-feedback';} ?>">
													<div class="">
														<textarea class="form-control" id="project_notes" rows="3"  tabindex="32" name="comments"><?php echo $this->input->post('comments'); ?></textarea>														
													</div>
												</div>
											</div>
										</div>
										
									    <div class="m-top-15 clearfix">
									    	<div>
									        	<button type="submit" tabindex="33" class="btn btn-success">Save</button>
									        </div>
									    </div>
									</div>
								
							</div>
						</div>
						
						<div class="col-md-3">
							
							<div class="box">
								<div class="box-head pad-5">
									<label><i class="fa fa-info-circle fa-lg"></i> Information</label>
								</div>
								<div class="box-area pad-10">
									<p>
										Completing the basic project information, proceeds to input the projects quotatoin and invoicing.
									</p>
								</div>
							</div>
							
							<div class="box">
								<div class="box-head pad-10">
									<label><i class="fa fa-history fa-lg"></i> History</label>
								</div>
								<div class="box-area pattern-sandstone pad-5">
	
									<div class="box-content box-list collapse in">
										<ul>
											<li>
												<div>
													<a href="#" class="news-item-title">You added a new company</a>
													<p class="news-item-preview">May 25, 2014</p>
												</div>
											</li>
											<li>
												<div>
													<a href="#" class="news-item-title">Updated the company details and contact information for James Tiling Co.</a>
													<p class="news-item-preview">May 20, 2014</p>
												</div>
											</li>
										</ul>
										<div class="box-collapse">
											<a style="cursor: pointer;" data-toggle="collapse" data-target=".more-list"> Show More </a>
										</div>
										<ul class="more-list collapse out">
											<li>
												<div>
													<a href="#" class="news-item-title">Duis aute irure dolor in reprehenderit</a>
													<p class="news-item-preview">
														Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore.
													</p>
												</div>
											</li>
											<li>
												<div>
													<a href="#" class="news-item-title">Duis aute irure dolor in reprehenderit</a>
													<p class="news-item-preview">
														Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore.
													</p>
												</div>
											</li>
											<li>
												<div>
													<a href="#" class="news-item-title">Duis aute irure dolor in reprehenderit</a>
													<p class="news-item-preview">
														Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore.
													</p>
												</div>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
						
					</div>				
				</div>
			</form>			
			
		</div>
	</div>
</div>
<div id="add_contact" class="modal fade" tabindex="-1" data-width="760" style="display: none; overflow: hidden;">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Add New Contact Person</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<h4>Personal Details</h4>
						<em>Fill the fields in this window and will add a new record on the contact list. (* is requred)</em><p></p>
						<div class="col-sm-12 m-bottom-10 clearfix">
							<label for="contact_f_name" class="col-sm-3 control-label m-top-5">First Name*</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="contact_f_name" placeholder="First Name" name="contact_f_name" value="">
							</div>
						</div>
						
						<div class="col-sm-12 m-bottom-10 clearfix">
							<label for="contact_l_name" class="col-sm-3 control-label m-top-5">Last Name*</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="contact_l_name" placeholder="Last Name" name="contact_l_name" value="">
							</div>
						</div>
						<p><hr style="display: block; border: 1px solid #E5E5E5; border-bottom:0;" class="m-top-15"  /></p>
						<div class="col-sm-5 m-bottom-10 clearfix">
							<label for="number" class="col-sm-4 control-label m-top-5">Gender*</label>
							<div class="col-sm-8">
								<select name="contact_gender"  class="form-control" id="gender">
									<option value="">Select</option>
									<option value="Male">Male</option>
									<option value="Female">Female</option>
								</select>
							</div>
						</div>
						
						<div class="col-sm-7 m-bottom-10 clearfix">
							<label for="cotact_email" class="col-sm-2 control-label m-top-5">Email*</label>
							<div class="col-sm-10">
								<input type="email" class="form-control" id="cotact_email" placeholder="Email" name="cotact_email" value="">
							</div>
						</div>
						
						<div class="col-sm-12 m-bottom-10 clearfix">
							<label for="contact_number" class="col-sm-3 control-label m-top-5">Contact*</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="contact_number" placeholder="Number" name="contact_number" value="">
							</div>
						</div>
						
						<div class="col-sm-12 m-bottom-10 clearfix">
							<label for="contact_company" class="col-sm-3 control-label m-top-5">Company*</label>
							<div class="col-sm-9">
								<select name="contact_company" class="form-control" id="contactperson">
									<option value="">Select Company</option>
									<?php
										foreach ($all_company_list as $row){
										echo '<option value="'.$row->company_name.'">'.$row->company_name.'</option>';
									}?>	
								</select>
							</div>
						</div>
						
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default cancel-contact" data-dismiss="modal">Cancel</button>
				<button type="submit" name="add-contact-submit" class="btn btn-success add-contact-submit">Submit</button>
			</div>
		</div>
	</div>
</div>

<div id="add_contact" class="modal fade" tabindex="-1" data-width="760" style="display: none; overflow: hidden;">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Add New Contact Person</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<h4>Personal Details</h4>
						<em>Fill the fields in this window and will add a new record on the contact list. (* is requred)</em><p></p>
						<div class="col-sm-12 m-bottom-10 clearfix">
							<label for="contact_f_name" class="col-sm-3 control-label m-top-5">First Name*</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="contact_f_name" placeholder="First Name" name="contact_f_name" value="">
							</div>
						</div>
						
						<div class="col-sm-12 m-bottom-10 clearfix">
							<label for="contact_l_name" class="col-sm-3 control-label m-top-5">Last Name*</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="contact_l_name" placeholder="Last Name" name="contact_l_name" value="">
							</div>
						</div>
						<p><hr style="display: block; border: 1px solid #E5E5E5; border-bottom:0;" class="m-top-15"  /></p>
						<div class="col-sm-5 m-bottom-10 clearfix">
							<label for="number" class="col-sm-4 control-label m-top-5">Gender*</label>
							<div class="col-sm-8">
								<select name="contact_gender"  class="form-control" id="gender">
									<option value="">Select</option>
									<option value="Male">Male</option>
									<option value="Female">Female</option>
								</select>
							</div>
						</div>
						
						<div class="col-sm-7 m-bottom-10 clearfix">
							<label for="cotact_email" class="col-sm-2 control-label m-top-5">Email*</label>
							<div class="col-sm-10">
								<input type="email" class="form-control" id="cotact_email" placeholder="Email" name="cotact_email" value="">
							</div>
						</div>
						
						<div class="col-sm-12 m-bottom-10 clearfix">
							<label for="contact_number" class="col-sm-3 control-label m-top-5">Contact*</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="contact_number" placeholder="Number" name="contact_number" value="">
							</div>
						</div>
						
						<div class="col-sm-12 m-bottom-10 clearfix">
							<label for="contact_company" class="col-sm-3 control-label m-top-5">Company*</label>
							<div class="col-sm-9">
								<select name="contact_company" class="form-control" id="contactperson">
									<option value="">Select Company</option>
									<?php
										foreach ($all_company_list as $row){
										echo '<option value="'.$row->company_name.'">'.$row->company_name.'</option>';
									}?>	
								</select>
							</div>
						</div>
						
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default cancel-contact" data-dismiss="modal">Cancel</button>
				<button type="submit" name="add-contact-submit" class="btn btn-success add-contact-submit">Submit</button>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('assets/logout-modal'); ?>
<script type="text/javascript">
	<?php echo ($this->input->post('is_form_submit') ? "" : "$('#project_name').focus();" ); ?>
</script>