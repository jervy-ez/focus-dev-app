<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>
<?php $this->load->module('projects'); ?>
<?php $this->load->module('users'); ?>
<?php $this->users->_check_user_access('projects',2); ?>
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
											<script type="text/javascript">$('.focus').val('<?php echo ($this->input->post('focus') ? $this->input->post('focus') : $this->session->userdata('user_focus_company_id')); ?>');</script>
											

										</div>	
										<label>Add New Project</label><span> (<a href="#" data-placement="right" class="popover-test" title="" data-content="Hello there mate! Welcome to the clients screen." data-original-title="Welcome">?</a>)</span>
										<p>Fields having * is requred.</p>	
										<p><a href="#" class="tooltip-test" title="Tooltip">This link</a> and <a href="#" class="tooltip-test" title="Tooltip">that link</a> should have tooltips on hover.</p>						
									</div>
									
									<div class="box-area pad-10 clearfix">											
										
										<div class="form-group clearfix pad-5 no-pad-b">
	        								<div class="col-sm-6">
	         									<div class="input-group <?php if(form_error('project_name')){ echo 'has-error has-feedback';} ?>">
													<span class="input-group-addon"><i class="fa fa-briefcase  fa-lg"></i></span>
													<input type="text" class="form-control" placeholder="Project Name*" name="project_name" id="project_name" maxlength="35" tabindex='1' value="<?php echo $this->input->post('project_name'); ?>">
													<span class="input-group-addon char-counter-remains"> </span>
												</div>
	        								</div>

	        								<div class="col-sm-6 clearfix <?php if(form_error('project_date')){ echo 'has-error has-feedback';} ?>">
												<label for="project_date" class="col-sm-3 control-label">Project Date*</label>
												<div class="col-sm-9">
													<input id="project_date" class="project_date form-control" name="project_date" readonly="readonly" type="text" value="<?php echo date("d/m/Y"); ?>">
												</div>	
											</div>
	      								</div>	      								
	      								
	      								<div class="box m-bottom-15 clearfix">
											<div class="box-head pad-5 m-bottom-5">
												<label><i class="fa fa-book fa-lg"></i> General</label>
											</div>
											
											<div class="box-area pad-5 clearfix">																																
												
												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('company_prg')){ echo 'has-error has-feedback';} ?>">
													<label for="company_prg" class="col-sm-3 control-label">Client*</label>
													<div class="col-sm-9">														
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-briefcase  fa-lg"></i></span>
															<select name="company_prg" class="form-control chosen find_contact_person get_address_invoice" id="company_prg" style="width: 100%;" tabindex="2">
																<option value=''>Select Client Name*</option>																												
																<?php $this->company->company_list('dropdown'); ?>	
															</select>
															<script type="text/javascript">$('select#company_prg').val('<?php echo $this->input->post('company_prg'); ?>');</script>
														</div>
													</div>
												</div>
												
												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('contact_person')){ echo 'has-error has-feedback';} ?>">
													<label for="contact_person" class="col-md-3 col-sm-5 control-label">Contact Person*</label>
													<div class="col-md-9 col-sm-7 here">
														<select name="contact_person" class="form-control" id="contact_person" style="width: 100%;"  tabindex="26">		
															<option value=''>Select Contact Person*</option>
															<?php if($this->input->post('company_prg')): ?>
																<?php $comp_arr = explode('|', $this->input->post('company_prg')); ?>		
																<?php $this->projects->find_contact_person($comp_arr[1]); ?>
															<?php endif; ?>
														</select>
														<script type="text/javascript">$('select#contact_person').val('<?php echo $this->input->post('contact_person'); ?>');</script>
													</div>
												</div>

												<div class="clearfix"></div>
												
												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="client_po" class="col-sm-3 control-label">Client PO</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="client_po" placeholder="Client PO" tabindex='3' name="client_po" value="<?php echo $this->input->post('client_po'); ?>">
													</div>
												</div>
												
												<div class="col-sm-6 m-bottom-10 clearfix" >
													<label for="job_date" class="col-sm-3 control-label"><i class="fa fa-calendar"></i> Job Date</label>
													<div class="col-sm-9">
														<input disabled="disabled" type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" title="Warning: Changing a value in the the Job date affects the project in the WIP section." class="tooltip-enabled job-date-set form-control datepicker" id="job_date" name="job_date" value="<?php echo $this->input->post('job_date'); ?>">
													</div>													
												</div>

												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('job_category')){ echo 'has-error has-feedback';} ?>">
													<label for="job_category" class="col-sm-3 control-label">Job Type*</label>													
													<div class="col-sm-9  col-xs-12">
														<select class="form-control" id="job_type" name="job_type" tabindex='4'>
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

														<select class="form-control postcode-option tooltip-test" id="job_category" name="job_category" tabindex='4' data-original-title="On selecting the Job Category, Project Area is only optional if Job Category is Strip Out, Minor Works, Kiosk or Maintenance.">															
															<option value="">Choose a Job Category</option>
															<option value="Kiosk">Kiosk</option>
															<option value="Full Fitout">Full Fitout</option>
															<option value="Refurbishment">Refurbishment</option>
															<option value="Strip Out">Strip Out</option>
															<option value="Minor Works">Minor Works (Under $20,000.00)</option>
															<option value="Maintenance">Maintenance</option>
															<script type="text/javascript">$("select#job_category").val("<?php echo $this->input->post('job_category'); ?>");</script>
														</select>
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

										<div class="box">
											<div class="box-head pad-5 m-bottom-5">
												<label><i class="fa fa-bars fa-lg"></i> Project Details</label>
											</div>
											
											<div class="box-area pad-5 clearfix">

												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="install_hrs" class="col-sm-3 control-label">Site Hours</label>
													<div class="input-group ">
														<span class="input-group-addon">(Hrs)</span>
														<input type="text" name="install_hrs" id="install_hrs" class="form-control" tabindex="8" placeholder="Install Hrs" value="<?php echo ($this->input->post('install_hrs') ?  $this->input->post('install_hrs') : '0' ); ?>"/>
													</div>
												</div>

												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="is_double_time" class="col-sm-3 control-label">Is Double Time?</label>
													<div class="col-sm-9  col-xs-12">
														<select name="is_double_time" class="form-control" id="is_double_time" style="width: 100%;" tabindex="9">
															<option value="0">No</option>
															<option value="1">Yes</option>
														</select>
														<script type="text/javascript">$('select#is_double_time').val('<?php echo ($this->input->post('is_double_time') ?  $this->input->post('is_double_time') : '0' ); ?>');</script>
													</div>
												</div>
													
												<div class="clearfix"></div>

												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('project_total')){ echo 'has-error has-feedback';}else{ echo 'green-estimate';} ?>">
													<label for="project_total" class="col-sm-3 control-label ">Project Estimate* </label>
													<div class="input-group ">
														<span class="input-group-addon">($)</span>
														<?php $est_amt = str_replace (',','', $this->input->post('project_total') ); ?>
														<input type="text" name="project_total" id="project_total" class="form-control number_format"  tabindex="10" placeholder="Project Estimate" value="<?php echo ($this->input->post('project_total') && $this->input->post('project_total')!=0 ?  number_format($est_amt) : '' ); ?>"/>
													</div>
												</div>

												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('labour_hrs_estimate')){ echo 'has-error has-feedback';}else{ echo 'green-estimate';} ?>">
													<label for="labour_hrs_estimate" class="col-sm-4 control-label text-center">Site Labour Estimate* </label>
													<div class="input-group ">
														<span class="input-group-addon">(Hrs)</span>
														<input type="text" name="labour_hrs_estimate" id="labour_hrs_estimate" class="form-control" tabindex="11" placeholder="Site Labour Estimate" value="<?php echo ($this->input->post('labour_hrs_estimate') ?  $this->input->post('labour_hrs_estimate') : '' ); ?>"/>
													</div>
												</div>


												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('project_markup')){ echo 'has-error has-feedback';} ?>">
													<label for="project_markup" class="col-sm-3 control-label">Project Markup* </label>
													<div class="input-group ">
														<span class="input-group-addon">(%)</span>
														<input type="text" name="project_markup" id="project_markup" class="form-control" tabindex="12" placeholder="Markup %" value="<?php echo $this->input->post('project_markup'); ?>" />
														<p class="min_mark_up hidden"></p>
													</div>

												</div>

												<input type="hidden" name="min_mark_up" value="" class="min_mark_up">											
													
												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('project_area')){ echo 'has-error has-feedback';} ?>">
													<label for="project_area" class="col-sm-3 control-label text-center">Project Area*</label>
													<div class="col-sm-9">												

														<div class="input-group ">
															<span class="input-group-addon">SQM</span>
															<input type="text" name="project_area" id="project_area" class="form-control tooltip-test" data-original-title="Project Area is only optional if Job Category is Strip Out, Minor Works, Kiosk or Maintenance." tabindex="13" placeholder="Project Area" value="<?php echo $this->input->post('project_area'); ?>"/>
														</div>
													</div>
												</div>


											</div>
										</div>
					
										<div class="clearfix" style = "height: 20px"></div>

										<div class="box-tabs m-bottom-10">
											<ul id="myTab" class="nav nav-tabs">
												<li class="active">
													<a href="#physicalAddress" data-toggle="tab"><i class="fa fa-globe fa-lg"></i> Site Address</a>
												</li>
												<li class="">
													<a href="#postalAddress" data-toggle="tab"  tabindex="20" ><i class="fa fa-inbox fa-lg"></i> Invoice Address</a>
												</li>
												<input type="hidden" name="is_form_submit" value="1">																					
											</ul>
											<div class="tab-content">
												<div class="tab-pane fade active in clearfix" id="physicalAddress">
													<div class="site_address" <?php echo ($this->input->post('is_shopping_center')==1 ?  'style="display:none;"' : '' ); ?>>

													<div class="col-sm-6 m-bottom-10 clearfix">
														<label for="unit_level" class="col-sm-3 control-label">Unit/Level</label>
														<div class="col-sm-9">
															<input type="text" class="form-control" id="unit_level" placeholder="Unit/Level" name="unit_level" tabindex="14" value="<?php echo $this->input->post('unit_level'); ?>">
														</div>
													</div>

													<div class="col-sm-6 m-bottom-10 clearfix">
														<label for="number" class="col-sm-3 control-label">Number</label>
														<div class="col-sm-9">
															<input type="text" class="form-control" id="number" placeholder="Number" tabindex="15" name="unit_number" value="<?php echo $this->input->post('unit_number'); ?>">
														</div>
													</div>

													<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('street')){ echo 'has-error has-feedback';} ?>">
														<label for="street" class="col-sm-3 control-label">Street*</label>
														<div class="col-sm-9">
															<input type="text" class="form-control" id="street" placeholder="Street" tabindex="16" name="street" value="<?php echo $this->input->post('street'); ?>">
														</div>
													</div>

													<div class="clearfix"></div>

													<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('state_a')){ echo 'has-error has-feedback';} ?>">
														<label for="state" class="col-sm-3 control-label">State*</label>													
														<div class="col-sm-9">
															<select class="form-control state-option-a chosen"  tabindex="17" id="state_a" name="state_a">
																<?php if($this->input->post('focus')): ?>
																	<?php echo $this->projects->set_jurisdiction($this->input->post('focus')); ?>
																<?php else: ?>
																	<?php echo $this->projects->set_jurisdiction($this->session->userdata('user_focus_company_id')); ?>
																	<?php //foreach ($all_aud_states as $row){ echo '<option value="'.$row->shortname.'|'.$row->name.'|'.$row->phone_area_code.'|'.$row->id.'">'.$row->name.'</option>'; }?>
																<?php endif;  ?>
															</select>
															<script type="text/javascript">$("select#state_a").val("<?php echo $this->input->post('state_a'); ?>");</script>
														</div>
													</div>

													<div class="col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('suburb_a')){ echo 'has-error';} ?>">
														<label for="suburb" class="col-sm-3 control-label">Suburb*</label>
														<div class="col-sm-9 col-xs-12">

															<?php if($this->input->post('suburb_a')): ?>
																<select class="form-control suburb-option-a chosen" id="suburb_a"  tabindex='18' name="suburb_a">

																<?php else: ?>
																	<select class="form-control suburb-option-a disabled chosen"  tabindex="18"  id="suburb_a" name="suburb_a">
																		<option value="">Choose a Suburb...</option>

																	<?php endif; ?>



																	<?php if($this->input->post('suburb_a')): ?>
																		<?php $this->company->get_suburb_list('dropdown|state_id|'.$this->input->post('state_a')); ?>														
																		<script type="text/javascript">$("select#suburb_a").val("<?php echo $this->input->post('suburb_a'); ?>");</script>

																	<?php endif; ?>

																</select>
															</div>
														</div>

														<div class="clearfix"></div>

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
														<select class="form-control postcode-option-a chosen" id="postcode_a"  tabindex="19" name="postcode_a">
														<?php else: ?>
															<select class="form-control postcode-option-a disabled chosen"   tabindex="19"  id="postcode_a" name="postcode_a">
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


												<div class="shopping_center" <?php echo ($this->input->post('is_shopping_center')==1 ?  '' : 'style="display:none;"' ); ?>>
													<input type="hidden" name="is_shopping_center" class="is_shopping_center" value="<?php echo ($this->input->post('is_shopping_center')==1 ?  1 : 0 ); ?>">

													<div class="col-sm-3 m-bottom-5 clearfix <?php if(form_error('shopping_center_state')){ echo 'has-error has-feedback';} ?>">											
														<div class="col-sm-12">
															<label for="shopping_center_state" class="control-label">State*</label>
															<select class="form-control state-option-a shopping_center_state"  tabindex="14" id="shopping_center_state" name="shopping_center_state">
																<?php if($this->input->post('focus')): ?>
																	<?php echo $this->projects->set_jurisdiction($this->input->post('focus')); ?>
																<?php else: ?>
																	<?php echo $this->projects->set_jurisdiction($this->session->userdata('user_focus_company_id')); ?>
																<?php endif;  ?>
															</select>
															<script type="text/javascript">$("select#shopping_center_state").val("<?php echo $this->input->post('shopping_center_state'); ?>");</script>
														</div>
													</div>

													<div class="col-sm-3 m-bottom-5 clearfix <?php if(form_error('shopping_center_suburb')){ echo 'has-error has-feedback';} ?>">											
														<div class="col-sm-12">
															<label for="shopping_center_suburb" class="control-label">Suburb*</label>
															<select class="form-control shopping_center_suburb" id="shopping_center_suburb"  tabindex='15' name="shopping_center_suburb">
																<?php if($this->input->post('shopping_center_state')): ?>
																	<?php $this->company->get_suburb_list('dropdown|state_id|'.$this->input->post('shopping_center_state')); ?>
																<?php else: ?>
																	<option value="" disabled="disabled">Choose a Suburb...</option>
																<?php endif; ?>
															</select>
															<script type="text/javascript">$("select#shopping_center_suburb").val("<?php echo $this->input->post('shopping_center_suburb'); ?>");</script>
														</div>
													</div>

													<div class="col-sm-3 m-bottom-5 clearfix <?php if(form_error('shopping_center')){ echo 'has-error has-feedback';} ?>">											
														<div class="col-sm-12">
															<label for="shopping_center" class="control-label">Select Brand/Shopping Center*</label>
															<select class="form-control brand_shopping_center"  tabindex="16" id="brand_shopping_center" name="brand_shopping_center" style="width: 100%;">
																<?php if($this->input->post('brand_shopping_center')): ?>

																	<?php $post_suburb = $this->input->post('shopping_center_suburb'); $post_state = $this->input->post('shopping_center_state');  ?>
																	<?php $this->projects->fetch_shopping_center_state_sub($post_suburb,$post_state,1); ?>
																<?php else: ?>
																	<option value="" disabled="disabled">Choose a Shopping Center</option>
																<?php endif; ?>
															</select>
															<script type="text/javascript">$("select#brand_shopping_center").val("<?php echo $this->input->post('brand_shopping_center'); ?>");</script>
														</div>
													</div>

													<div class="col-sm-3 m-bottom-10 clearfix">
														<div class="col-sm-12">
															<label for="shop_tenancy_number" class="control-label">Shop/Tenancy Number</label><br />
															<input type="text" class="form-control" id="shop_tenancy_number" placeholder="Shop/Tenancy Number" tabindex="17" name="shop_tenancy_number" value="<?php echo $this->input->post('shop_tenancy_number'); ?>">
														</div>
													</div>

												</div>											

											</div>
											<div class="tab-pane fade clearfix" id="postalAddress">
												
												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="unitlevel2" class="col-sm-3 control-label">Unit/Level</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="unitlevel2"  tabindex="20"  placeholder="Unit/Level" name="unit_level_b" value="<?php echo $this->input->post('unit_level_b'); ?>">
													</div>
												</div>

												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="number2" class="col-sm-3 control-label">Number</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="number2" placeholder="Number"   tabindex="21" name="number_b" value="<?php echo $this->input->post('number_b'); ?>">
													</div>
												</div>

												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('street_b')){ echo 'has-error has-feedback';} ?>">
													<label for="street2" class="col-sm-3 control-label">Street*</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="street2" placeholder="Street"   tabindex="22" name="street_b" value="<?php echo $this->input->post('street_b'); ?>">
													</div>
												</div>

												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="pobox" class="col-sm-3 control-label">PO Box</label>
													<div class="col-sm-9" style="z-index:0;">
														<input type="text" class="form-control" id="pobox" placeholder="PO Box" name="pobox"  tabindex="23"  style="z-index:0; background:#fff;"  value="<?php echo $this->input->post('pobox'); ?>">
													</div>
												</div>

												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('state_b')){ echo 'has-error has-feedback';} ?>">
													<label for="state_b" class="col-sm-3 control-label">State*</label>
													

													<div class="col-sm-9">
														<!-- <input type="text" class="form-control" id="state_a" placeholder="State" name="state_a" value="<?php echo $this->input->post('state_a'); ?>"> -->

														<select class="form-control state-option-b chosen"  id="state_b"   tabindex="24" name="state_b"  >															
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
															<select class="form-control suburb-option-b chosen" id="suburb_b" tabindex="25" name="suburb_b">

															<?php else: ?>
																<select class="form-control suburb-option-b chosen" id="suburb_b" tabindex="25" name="suburb_b">
																	<option value="">Choose a Suburb...</option>
																<?php endif; ?>


																<?php if($this->input->post('suburb_b')): ?>
																	<?php $this->company->get_suburb_list('dropdown|state_id|'.$this->input->post('state_b')); ?>														
																	<script type="text/javascript">$("select#suburb_b").val("<?php echo $this->input->post('suburb_b'); ?>");</script>

																<?php endif; ?>

															</select>
														</div>
													</div>

													<div class="clearfix"></div>



												<!-- <div class="col-sm-6 m-bottom-10 clearfix">
													<label for="postcode2" class="col-sm-3 control-label">Postcode*</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="postcode2" placeholder="Postcode">
													</div>
												</div> -->


												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('postcode_b')){ echo 'has-error has-feedback';} ?>">
												<label for="postcode_b" class="col-sm-3 control-label">Postcode*</label> <?php //echo $this->input->post('postcode_a'); ?>													
												<div class="col-sm-9  col-xs-12">



													
														<select class="form-control postcode-option-b chosen" id="postcode_b"  tabindex="26" name="postcode_b">
														<?php if($this->input->post('postcode_b')): ?>
														<?php else: ?>
															<option value="">Choose a Postcode...</option>
														<?php endif; ?>

														<?php if($this->input->post('postcode_b')): ?>
															<?php $suburb_b = explode('|', $this->input->post('suburb_b')); ?>
															<?php $this->company->get_post_code_list($suburb_b[0]); ?>													

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
														<select name="project_manager" class="form-control presonel_add" id="project_manager" style="width: 100%;" tabindex="27">
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
														<select name="project_admin" class="form-control presonel_add" id="project_administrator" style="width: 100%;" tabindex="28">
															<option value=''>Select Project Admin</option>
															<?php
															foreach ($project_administrator as $row){
																echo '<option value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.'</option>';
															}?>

															<?php
															foreach ($maintenance_administrator as $row){
																echo '<option value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.'</option>';
															}?>
														</select>
														<script type="text/javascript">$('select#project_administrator').val('<?php echo $this->input->post('project_admin'); ?>');</script>														
													</div>
												</div>
												
			      								<div class="col-sm-4 m-bottom-5 clearfix <?php if(form_error('estimator')){ echo 'has-error has-feedback';} ?>">						
													<div class="col-sm-12">
														<label for="estimator" class="control-label">Estimator*</label>													
														<select name="estimator" class="form-control presonel_add" id="estimator" style="width: 100%;" tabindex="29">
															<option value=''>Select Estimator</option>
															<option value='0'>None</option>
															<?php
															foreach ($estimator as $row){
																echo '<option value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.'</option>';
															}?>

															<?php
															foreach ($maintenance_administrator as $row){
																echo '<option value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.'</option>';
															}?>
														</select>
														<script type="text/javascript">$('select#estimator').val('<?php echo $this->input->post('estimator'); ?>');</script>
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
														<textarea class="form-control" id="project_notes" rows="5"  tabindex="30" name="comments"><?php echo $this->input->post('comments'); ?></textarea>														
													</div>
												</div>
											</div>
										</div>
										
									    <div class="m-top-15 clearfix">
									    	<div>

									        	<div class="btn-group">
									        		<button type="submit" class="btn btn-success pull-left"><i class="fa fa-floppy-o"></i> Save</button>
									        		<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
									        			<span class="caret"></span>
									        			<span class="sr-only">Toggle Dropdown</span>
									        		</button>
									        		<ul class="dropdown-menu" role="menu">
									        			<li><a href="#" class="set_copy_work"><i class="fa fa-files-o"></i> Copy Work</a></li>
									        		</ul>
									        	</div>

									        </div>
									    </div>
									</div>
								
							</div>
						</div>



<div id="copy_works" class="modal fade" tabindex="-1" data-width="760" style="display: none; overflow: hidden;">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Copy Works</h4>
			</div>

			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<h4>How to copy</h4>
						<em>To copy the works from an existing project, you can select the client to narrow the list of projects and then select the project below.</em><p></p>
						<div class="col-sm-12 m-bottom-10 clearfix">
							<label for="contact_f_name" class="col-sm-3 control-label m-top-5 text-left"><strong>Select Client</strong></label>
							<div class="col-sm-9">


								<select name="all_company_project" class="form-control all_company_project chosen" id="all_company_project" style="width: 100%;">
									<option value=''>Select Client</option>
									<?php
									foreach ($all_company_project as $row_id => $value){
										echo '<option value="'.$row_id.'|'.$value.'">'.$value.'</option>';
									}?>
								</select>
								<?php if($this->input->post('all_company_project')): ?>
									<script type="text/javascript">
										$('select#all_company_project').val('<?php echo $this->input->post('all_company_project'); ?>');
										<?php $all_company_project_arr = explode('|', $this->input->post('all_company_project')); ?>
										$('.all_company_project a.select2-choice .select2-chosen').text('<?php echo $all_company_project_arr[1]; ?>');
									</script>
								<?php endif; ?>
							</div>
						</div>
						<div class="clearfix"></div>


						<em>You can select the project directly if you desired.</em><p></p>

						<div class="col-sm-12 m-bottom-10 clearfix">
							<label for="contact_l_name" class="col-sm-4 control-label m-top-5 text-left"><strong>Select Existing Project</strong></label>
							<div class="col-sm-8">
								<select name="copy_work_project_id" class="form-control copy_work_project_id chosen_opt_a" id="copy_work_project_id" style="width: 100%;">
									<option value=''>Select Existing Project</option>
									<?php
									foreach ($all_projects->result_array() as $row){
										echo '<option class="pg_id_'.$row['company_id'].'" value="'.$row['project_id'].'">'.$row['project_id'].' '.$row['project_name'].'</option>';
									}?>
								</select>
								<?php if($this->input->post('copy_work_project_id')): ?>
									<script type="text/javascript">
										$('select#copy_work_project_id').val('<?php echo $this->input->post('copy_work_project_id'); ?>');
										$('.copy_work_project_id a.select2-choice .select2-chosen').text('<?php echo $this->input->post('copy_work_project_id'); ?>');
									</script>
								<?php endif; ?>

								<script type="text/javascript">var options = $('.chosen_opt_a option');</script>

							</div>
						</div>
						<div class="clearfix"></div>

						<p><hr /></p>


						<div class="col-sm-12 m-bottom-10 clearfix">
							<label for="contact_l_name" class="col-sm-5 control-label m-top-5 text-left"><strong>Include the work estimates?</strong></label>
							<div class="col-sm-7">
								<select name="include_work_estimate" class="form-control include_work_estimate" id="include_work_estimate" style="width: 100%;">
									<option value="1">Yes</option>
									<option value="0">No</option>
								</select>
								<?php if($this->input->post('include_work_estimate')): ?>
									<script type="text/javascript">$('select#include_work_estimate').val('<?php echo $this->input->post('include_work_estimate'); ?>');</script>
								<?php else: ?>
									<script type="text/javascript">$('select#include_work_estimate').val('1');</script>
								<?php endif; ?>

							</div>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default cancel-contact" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-success add-contact-submit"><i class="fa fa-floppy-o"></i> Save</button>
			</div>
		</div>
	</div>
</div>




			</form>			
						
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

							<div class="box add-shopping-center">
								<div class="box-head pad-5">
									<label><i class="fa fa-cart-plus fa-lg"></i> Add Shopping Center</label>
								</div>

								<div class="box-area clearfix pad-5">



										<div class="clearfix  m-bottom-10 <?php if(form_error('brand')){ echo 'has-error has-feedback';} ?>">
											<label for="brand" class="col-sm-12 control-label text-left" style="font-weight: normal;">Brand/Shopping Center*</label>
											<div class="col-sm-12">
												<input type="text" class="form-control m-top-10" id="brand" name="brand" tabindex="1" placeholder="Brand/Shopping center" value="<?php echo $this->input->post('brand'); ?>">
											</div>
										</div>

										<div class="clearfix m-top-10 m-bottom-10">
											<label for="street-number" class="col-sm-4 control-label text-left" style="font-weight: normal;">Street Number</label>
											<div class="col-sm-8">
												<input type="text" class="form-control" id="street-number" name="street_number" tabindex="2" placeholder="Street Number" value="<?php echo $this->input->post('street_number'); ?>">
											</div>
										</div>

										<div class="clearfix m-top-10 m-bottom-10 <?php if(form_error('street')){ echo 'has-error has-feedback';} ?>">
											<label for="street-c" class="col-sm-4 control-label text-left" style="font-weight: normal;">Street*</label>
											<div class="col-sm-8">
												<input type="text" class="form-control" id="street-c" name="street-c" tabindex="3" placeholder="Street" value="<?php echo $this->input->post('street-c'); ?>">
											</div>
										</div>

										<input type="hidden" name="is_submit" value="1">

										<div class="clearfix m-top-10 m-bottom-10 <?php if(form_error('state_c')){ echo 'has-error has-feedback';} ?>">
											<label for="state_c" class="col-sm-4 control-label text-left" style="font-weight: normal;">State*</label>
											<div class="col-sm-8">
												
												<select class="form-control state-option-c chosen"  tabindex="4" id="state_c" name="state_c">															
													<option value="">Choose a State</option>
													<?php
													foreach ($all_aud_states as $row){
														echo '<option value="'.$row->shortname.'|'.$row->name.'|'.$row->phone_area_code.'|'.$row->id.'">'.$row->name.'</option>';
													}?>
												</select>
												<script type="text/javascript">$("select#state_c").val("<?php echo $this->input->post('state_c'); ?>");</script>



											</div>
										</div>

										<div class="clearfix m-top-10 m-bottom-10 <?php if(form_error('suburb_c')){ echo 'has-error has-feedback';} ?>">
											<label for="suburb_c" class="col-sm-4 control-label text-left" style="font-weight: normal;">Suburb*</label>
											<div class="col-sm-8">
												
												<?php if($this->input->post('suburb_c')): ?>
													<select class="form-control suburb-option-c chosen" id="suburb_c" name="suburb_c">

													<?php else: ?>
														<select class="form-control suburb-option-c disabled chosen"  tabindex="5"  id="suburb_c" name="suburb_c">
															<option value="">Choose a Suburb...</option>

														<?php endif; ?>

														

														<?php if($this->input->post('suburb_c')): ?>
															<?php $this->company->get_suburb_list('dropdown|state_id|'.$this->input->post('state_c')); ?>														
															<script type="text/javascript">$("select#suburb_c").val("<?php echo $this->input->post('suburb_c'); ?>");</script>

														<?php endif; ?>

													</select>


											</div>
										</div>

										<div class="clearfix m-top-10 m-bottom-10 <?php if(form_error('postcode_c')){ echo 'has-error has-feedback';} ?>">
											<label for="postcode_c" class="col-sm-4 control-label text-left" style="font-weight: normal;">Postcode*</label>
											<div class="col-sm-8">
												
												<?php if($this->input->post('postcode_c')): ?>
													<select class="form-control postcode-option-c chosen" id="postcode_c"  tabindex="6" name="postcode_c">
													<?php else: ?>
														<select class="form-control postcode-option-c disabled chosen"   tabindex="6"  id="postcode_c" name="postcode_c">
															<option value="">Choose a Postcode...</option>
														<?php endif; ?>

														<?php if($this->input->post('postcode_c')): ?>
															<?php $suburb_a = explode('|', $this->input->post('suburb_c')); ?>
															<?php $this->company->get_post_code_list($suburb_a[0]); ?>												

															<script type="text/javascript">$("select#postcode_c").val("<?php echo $this->input->post('postcode_c'); ?>");</script>
														<?php endif; ?>		

													</select>


											</div>
										</div>

										<div class="btn btn-success m-10 pull-right add_shopping_center_project"><i class="fa fa-cart-plus fa-lg"></i> Save</div>
									
								</div>
							</div>

							
							 
						</div>
						
					</div>				
				</div>




			
		</div>
	</div>
</div>





<?php $this->load->view('assets/logout-modal'); ?>
<script type="text/javascript">
	<?php echo ($this->input->post('is_form_submit') ? "" : "$('#project_name').focus();" ); ?>
</script>