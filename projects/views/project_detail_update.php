<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>
<?php $this->load->module('projects'); ?>
<?php $this->load->module('invoice'); ?>
<?php $this->load->module('shopping_center'); ?>
<!-- title bar -->
<div class="container-fluid head-control">
	<div class="container-fluid">
		<div class="row">

			<div class="col-md-6 col-sm-4 col-xs-12 pull-left">
				<header class="page-header">
					<h3><?php $datestring = "%l, %F %d, %Y"; $time = time(); //use time() for timestamp  ?>
						<?php echo $screen; ?><br><small><?php echo mdate($datestring, $time); #echo date("l, F d, Y"); ?></small>
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
											<script type="text/javascript">$('.focus').val('<?php echo ($this->input->post('focus') ? $this->input->post('focus') : $focus_company_id ); ?>');</script>
											

										</div>	
										<label>Updating Project: <?php echo $project_name; ?></label>
										<p>Client: <strong><?php echo $client_company_name; ?></strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Project No. <?php echo $project_id; ?></p>	
										<p>This screen displays the full details of the project, these information are sensitive.</p>						
									</div>
									
									<div class="box-area pad-10 clearfix">											
										
										<div class="form-group clearfix pad-5 no-pad-b">
	        								<div class="col-sm-6">
	         									<div class="input-group <?php if(form_error('project_name')){ echo 'has-error has-feedback';} ?>">
													<span class="input-group-addon"><i class="fa fa-briefcase  fa-lg"></i></span>
													<input type="text" class="form-control" placeholder="Project Name*" name="project_name" id="project_name" tabindex='1' value="<?php echo ($this->input->post('project_name') ?  $this->input->post('project_name') : $project_name ); ?>">
												</div>
	        								</div>

	        								<div class="col-sm-6 clearfix <?php if(form_error('project_date')){ echo 'has-error has-feedback';} ?>">
												<label for="project_date" class="col-sm-3 control-label">Project Date*</label>
												<div class="col-sm-9">
													<input id="project_date" class="project_date form-control" name="project_date" readonly="readonly" type="text" value="<?php echo $project_date; ?>">
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

															<?php if($this->input->post('company_prg')): ?>
																<script type="text/javascript">$('select#company_prg').val('<?php echo $this->input->post('company_prg'); ?>');</script>
															<?php else: ?>
																<script type="text/javascript">$('select#company_prg').val('<?php echo $client_company_name."|".$client_company_id; ?>');</script>
															<?php endif; ?>


														</div>
													</div>
												</div>
												
												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('contact_person')){ echo 'has-error has-feedback';} ?>">
													<label for="contact_person" class="col-md-3 col-sm-5 control-label">Contact Person*</label>
													<div class="col-md-9 col-sm-7 here">
														<select name="contact_person" class="form-control" id="contact_person" style="width: 100%;"  tabindex="26">
															<?php if($this->input->post('company_prg')): ?>
																<?php $comp_arr = explode('|', $this->input->post('company_prg')); ?>		
																<?php $this->projects->find_contact_person($comp_arr[1]); ?>
															<?php else: ?>
																<option disabled="disabled" value="<?php echo $contact_person_id; ?>" selected=""><?php echo "$contact_person_fname $contact_person_lname"; ?> (Selected)</option>
																<?php $this->projects->find_contact_person($client_company_id); ?>
															<?php endif; ?>
														</select>

														<?php if($this->input->post('contact_person')): ?>
															<script type="text/javascript">$('select#contact_person').val('<?php echo $this->input->post('contact_person'); ?>');</script>
														<?php endif; ?>


													</div>
												</div>

												<div class="clearfix"></div>
												
												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="client_po" class="col-sm-3 control-label">Client PO</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="client_po" placeholder="Client PO" tabindex='3' name="client_po" value="<?php echo ($this->input->post('client_po') ?  $this->input->post('client_po') : $client_po ); ?>">
													</div>
												</div>
												
												<div class="col-sm-6 m-bottom-10 clearfix" >
													<label for="job_date" class="col-sm-3 control-label"><i class="fa fa-calendar"></i> Job Date</label>
													<div class="col-sm-9">
														
														<?php  if($this->invoice->if_has_invoice($project_id) == 0): ?>
																			
																				 <div  title="Warning: You need to set up the Project Payments." class="tooltip-enabled">
																					<input type="text" disabled="disabled" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" title="Warning: Please fill the progress payment for project contract. Can be found at the Invoices tab." class="tooltip-enabled job-date-set form-control datepicker text-right" id="job_date" name="job_date" value="<?php echo $job_date; ?>">
																				</div>


																		<?php   else: ?>

																			<?php if($job_date == '' ): ?>
																				<input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" title="Warning: Changing a value in the the Job date affects the project in the WIP section." class="tooltip-enabled job-date-set form-control datepicker text-right" id="job_date" name="job_date" value="<?php echo $job_date; ?>">
																			
																				

																			<?php elseif($this->session->userdata('is_admin') == 1 || $this->session->userdata('user_role_id') == 3 || $this->session->userdata('user_id') == 8  ): ?>
																				<input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" title="Warning: Changing a value in the the Job date affects the project in the WIP section." class="tooltip-enabled job-date-set form-control datepicker text-right" id="job_date" name="job_date" value="<?php echo $job_date; ?>">
																			


																			<?php else: ?>
																				<input type="text" disabled="disabled" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" title="Warning: You need to request to the Project Manager to change the Job Date" class="tooltip-enabled job-date-set form-control datepicker text-right" id="job_date" name="job_date" value="<?php echo $job_date; ?>">
																			<?php endif; ?>

																		<?php  endif; ?>





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

														<?php if($this->input->post('job_type')): ?>
															<script type="text/javascript">$("select#job_type").val("<?php echo $this->input->post('job_type'); ?>");</script>
														<?php else: ?>
															<script type="text/javascript">$("select#job_type").val("<?php echo $job_type; ?>");</script>
														<?php endif; ?>
													</div>
												</div>
												
												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('job_category')){ echo 'has-error has-feedback';} ?>">
													<label for="job_category" class="col-sm-3 control-label">Job Category*</label>													
													<div class="col-sm-9  col-xs-12">
														<select class="form-control postcode-option" id="job_category" name="job_category" tabindex='4'>															
															<option value="">Choose a Job Category</option>
															<option value="Kiosk">Kiosk</option>
															<option value="Full Fitout">Full Fitout</option>
															<option value="Refurbishment">Refurbishment</option>
															<option value="Strip Out">Strip Out</option>
															<option value="Minor Works">Minor Works (Under $20,000.00)</option>
															<option value="Maintenance">Maintenance</option>
														</select>

														<?php if($this->input->post('job_category')): ?>
															<script type="text/javascript">$("select#job_category").val("<?php echo $this->input->post('job_category'); ?>");</script>
														<?php else: ?>
															<script type="text/javascript">$("select#job_category").val("<?php echo $job_category; ?>");</script>
														<?php endif; ?>
													</div>
												</div>

												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('site_start')){ echo 'has-error has-feedback';} ?>">
													<label for="site_start" class="col-sm-3 control-label">Site Start*</label>
													<div class="col-sm-9">
														<input tabindex='6' type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control datepicker" id="site_start" name="site_start" tabindex="4" value="<?php echo ($this->input->post('site_start') ?  $this->input->post('site_start') : $date_site_commencement ); ?>">
													</div>
												</div>
													
												<div class="col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('site_finish')){ echo 'has-error';} ?>">
													<label for="site_finish" class="col-sm-3 control-label">Site Finish*</label>
													<div class="col-sm-9 col-xs-12">
														<input tabindex='7' type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control datepicker" id="site_finish" name="site_finish" tabindex="4" value="<?php echo ($this->input->post('site_finish') ?  $this->input->post('site_finish') : $date_site_finish ); ?>">
													</div>
												</div>
											</div>
										</div>

										<div class="box">
											<div class="box-head pad-5 m-bottom-5">
												<label><i class="fa fa-bars fa-lg"></i> Project Details</label>
											</div>
											
											<div class="box-area pad-5 clearfix">

												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('install_hrs')){ echo 'has-error has-feedback';} ?>">
													<label for="install_hrs" class="col-sm-3 control-label">Site Hours* </label>
													<div class="input-group ">
														<span class="input-group-addon">(Hrs)</span>
														<input type="text" name="install_hrs" id="install_hrs" class="form-control" tabindex="8" placeholder="Install Hrs" value="<?php echo ($this->input->post('install_hrs') ?  $this->input->post('install_hrs') : $install_time_hrs ); ?>"/>
													</div>
												</div>

												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="is_double_time" class="col-sm-3 control-label">Is Double Time?</label>
													<div class="col-sm-9  col-xs-12">
														<select name="is_double_time" class="form-control" id="is_double_time" style="width: 100%;" tabindex="9">
															<option value="0">No</option>
															<option value="1">Yes</option>
														</select>

														<?php if($this->input->post('is_double_time')): ?>
															<script type="text/javascript">$('select#is_double_time').val('<?php echo ($this->input->post('is_double_time') ?  $this->input->post('is_double_time') : '0' ); ?>');</script>
														<?php else: ?>
															<script type="text/javascript">$('select#is_double_time').val('<?php echo $is_double_time; ?>');</script>
														<?php endif; ?>
													</div>
												</div>
													
												<div class="clearfix"></div>

												<div class="col-sm-6 m-bottom-10 clearfix green-estimate">
													<label for="project_total" class="col-sm-3 control-label ">Project Estimate</label>
													<div class="input-group ">
														<span class="input-group-addon">($)</span>
														<?php $est_amt = str_replace (',','', $this->input->post('project_total') ); ?>
														<input type="text" name="project_total" id="project_total" class="form-control number_format"  tabindex="10" placeholder="Total" value="<?php echo ($this->input->post('project_total') && $this->input->post('project_total')!=0 ?  number_format($est_amt) : number_format($budget_estimate_total) ); ?>"/>
													</div>
												</div>



												<div class="col-sm-6 m-bottom-10 clearfix green-estimate">
													<label for="labour_hrs_estimate" class="col-sm-4 control-label text-center">Site Labour Estimate</label>
													<div class="input-group ">
														<span class="input-group-addon">(Hrs)</span>
														<input type="text" name="labour_hrs_estimate" id="labour_hrs_estimate" class="form-control" tabindex="11" placeholder="Site Labour Estimate" value="<?php echo ($this->input->post('labour_hrs_estimate') ?  $this->input->post('labour_hrs_estimate') : $labour_hrs_estimate ); ?>"/>
													</div>
												</div>


												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="project_markup" class="col-sm-3 control-label">Project Markup</label>
													<div class="input-group ">
														<span class="input-group-addon">(%)</span>
														<input type="text" name="project_markup" id="project_markup" class="form-control" tabindex="12" placeholder="Markup %" value="<?php echo ($this->input->post('project_markup') ?  $this->input->post('project_markup') : $markup ); ?>" />
														<p class="min_mark_up hidden"><?php echo $min_markup; ?></p>
													</div>

												</div>												
													
												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('areacode')){ echo 'has-error has-feedback';} ?>">
													<label for="project_area" class="col-sm-3 control-label text-center">Project Area</label>
													<div class="col-sm-9">												

														<div class="input-group ">
															<span class="input-group-addon">SQM</span>
															<input type="text" name="project_area" id="project_area" class="form-control" tabindex="13" placeholder="Project Area" value="<?php echo ($this->input->post('project_area') ?  $this->input->post('project_area') : $project_area ); ?>"/>
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
													<div class="site_address" <?php echo ($this->input->post('is_shopping_center')==1 || $job_type == 'Shopping Center' ?  'style="display:none;"' : '' ); ?>   >

													<div class="col-sm-6 m-bottom-10 clearfix">
														<label for="unit_level" class="col-sm-3 control-label">Unit/Level</label>
														<div class="col-sm-9">
															<input type="text" class="form-control" id="unit_level" placeholder="Unit/Level" name="unit_level" tabindex="14" value="<?php echo ($this->input->post('unit_level') ?  $this->input->post('unit_level') : $unit_level ); ?>">
														</div>
													</div>

													<div class="col-sm-6 m-bottom-10 clearfix">
														<label for="number" class="col-sm-3 control-label">Number</label>
														<div class="col-sm-9">
															<input type="text" class="form-control" id="number" placeholder="Number" tabindex="15" name="unit_number" value="<?php echo ($this->input->post('unit_number') ?  $this->input->post('unit_number') : $unit_number ); ?>">
														</div>
													</div>

													<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('street')){ echo 'has-error has-feedback';} ?>">
														<label for="street" class="col-sm-3 control-label">Street*</label>
														<div class="col-sm-9">
															<input type="text" class="form-control" id="street" placeholder="Street" tabindex="16" name="street" value="<?php echo ($this->input->post('street') ?  $this->input->post('street') : $street ); ?>">
														</div>
													</div>

													<div class="clearfix"></div>


													<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('state_a')){ echo 'has-error has-feedback';} ?>">
														<label for="state" class="col-sm-3 control-label">State*</label>													
														<div class="col-sm-9">

															<select class="form-control state-option-a chosen"  tabindex="17" id="state_a" name="state_a">
																<?php echo $this->projects->set_jurisdiction($focus_company_id); ?>
																<option selected="selected" value="<?php echo $shortname.'|'.$state.'|'.$phone_area_code.'|'.$state_id; ?>"><?php echo $state; ?></option>
															</select>

															<?php if($this->input->post('state_a')): ?>
																<script type="text/javascript">$("select#state_a").val("<?php echo $this->input->post('state_a'); ?>");</script>
															<?php else: ?>
																<script type="text/javascript">$("select#state_a").val("<?php echo $shortname.'|'.$state.'|'.$phone_area_code.'|'.$state_id; ?>");</script>
															<?php endif;  ?>
														</div>
													</div>

													<div class="col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('suburb_a')){ echo 'has-error';} ?>">
														<label for="suburb" class="col-sm-3 control-label">Suburb*</label>
														<div class="col-sm-9 col-xs-12">

															<select class="form-control suburb-option-a chosen" id="suburb_a" name="suburb_a">
																<?php $this->company->get_suburb_list('dropdown|state_id|'.$suburb.'|'.$state.'|'.$phone_area_code.'|'.$state_id); ?>
															</select>
															<?php if($this->input->post('suburb_a')): ?>								
																<script type="text/javascript">$("select#suburb_a").val("<?php echo $this->input->post('suburb_a'); ?>");</script>
															<?php else: ?>
																<script type="text/javascript">$("select#suburb_a").val("<?php echo $suburb.'|'.$state.'|'.$phone_area_code; ?>");</script>
															<?php endif; ?>

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

													<select class="form-control postcode-option-a chosen" id="postcode_a" name="postcode_a">
														<?php $this->company->get_post_code_list($suburb); ?>																	
													</select>
													<?php if($this->input->post('postcode_a')): ?>
														<script type="text/javascript">$("select#postcode_a").val("<?php echo $this->input->post('postcode_a'); ?>");</script>
													<?php else: ?>
														<script type="text/javascript">$("select#postcode_a").val("<?php echo $postcode; ?>");</script>	
													<?php endif; ?>	

													</div>
												</div>

												</div>


												<div class="shopping_center" <?php echo ($this->input->post('is_shopping_center')==1 || $job_type == 'Shopping Center' ?  '' : 'style="display:none;"' ); ?>>
													<input type="hidden" name="is_shopping_center" class="is_shopping_center" value="<?php echo ($this->input->post('is_shopping_center')==1 || $job_type == 'Shopping Center' ?  1 : 0 ); ?>">

													<?php if($this->input->post('brand_shopping_center')): ?>
														<input type="hidden" name="brand_shopping_center" class="brand_shopping_center" id="brand_shopping_center" value="<?php echo $this->input->post('brand_shopping_center'); ?>">
													<?php else: ?>
														<input type="hidden" name="brand_shopping_center" class="brand_shopping_center" id="brand_shopping_center" value="<?php echo $address_id; ?>">
													<?php endif; ?>

													

													<?php if($this->input->post('selected_shopping_center_detail')): ?>
														<input type="hidden" name="selected_shopping_center_detail" class="selected_shopping_center_detail" id="selected_shopping_center_detail" value="<?php echo $this->input->post('selected_shopping_center_detail'); ?>">
													<?php else: ?>
														<input type="hidden" name="selected_shopping_center_detail" class="selected_shopping_center_detail" id="selected_shopping_center_detail" value="<?php echo $shopping_center_brand_name.', '.$unit_number.' '.$street.', '.ucwords(strtolower($suburb)).', '.$state.', '.$postcode; ?>">
													<?php endif; ?>

													<?php #echo $shortname.'|'.$state.'|'.$phone_area_code.'|'.$state_id.'|'.$shopping_center_brand_name; ?>
													<?php #echo $suburb.'|'.$state.'|'.$phone_area_code.'|'.$shop_tenancy_number; ?>
													<?php #echo $shop_tenancy_number.'|'.$unit_level.'|'.$unit_number.'|'.$street.'|'.$suburb.'|'.$state.'|'.$postcode; ?>

													<div class="col-sm-3 m-bottom-5 clearfix">											
														<a href="#" data-toggle="modal" data-target="#select_shopping_center_modal" data-backdrop="static" class="btn btn-primary">Select Shopping Center</a>
													</div>

													<div class="col-sm-4 m-bottom-5 clearfix <?php if(form_error('brand_shopping_center')){ echo 'has-error has-feedback';} ?>">											
														<p class="m-top-10">Shopping Center: 
															<?php if($this->input->post('selected_shopping_center_detail')): ?>
																<strong class="selected_shopping_center_text" id="selected_shopping_center_text"><?php echo $this->input->post('selected_shopping_center_detail'); ?></strong>
															<?php else: ?>
																<strong class="selected_shopping_center_text" id="selected_shopping_center_text"><?php echo $shopping_center_brand_name.', '.$unit_number.' '.$street.', '.ucwords(strtolower($suburb)).', '.$state.', '.$postcode; ?></strong>
															<?php endif; ?>
														</p>
													</div>

													<div class="col-sm-5 m-bottom-10 clearfix">
														<label for="client_po" class="col-sm-6 control-label">Shop/Tenancy Number</label>
														<div class="col-sm-6">
															<input type="text" class="form-control" id="shop_tenancy_number" placeholder="Shop/Tenancy Number" tabindex="14" name="shop_tenancy_number" value="<?php echo ($this->input->post('shop_tenancy_number') ?  $this->input->post('shop_tenancy_number') : $shop_tenancy_number ); ?>">
														</div>
													</div>

												</div>



																							

											</div>
											<div class="tab-pane fade clearfix" id="postalAddress">
												
												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="unitlevel2" class="col-sm-3 control-label">Unit/Level</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="unitlevel2"  tabindex="20"  placeholder="Unit/Level" name="unit_level_b" value="<?php echo ($this->input->post('unit_level_b') ?  $this->input->post('unit_level_b') : $i_unit_level ); ?>">
													</div>
												</div>

												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="number2" class="col-sm-3 control-label">Number</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="number2" placeholder="Number"   tabindex="21" name="number_b" value="<?php echo ($this->input->post('number_b') ?  $this->input->post('number_b') : $i_unit_number ); ?>">
													</div>
												</div>

												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('street_b')){ echo 'has-error has-feedback';} ?>">
													<label for="street2" class="col-sm-3 control-label">Street*</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="street2" placeholder="Street"   tabindex="22" name="street_b" value="<?php echo ($this->input->post('street_b') ?  $this->input->post('street_b') : $i_street ); ?>">
													</div>
												</div>

												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="pobox" class="col-sm-3 control-label">PO Box</label>
													<div class="col-sm-9" style="z-index:0;">
														<input type="text" class="form-control" id="pobox" placeholder="PO Box" name="pobox"  tabindex="23"  style="z-index:0; background:#fff;"  value="<?php echo ($this->input->post('pobox') ?  $this->input->post('pobox') : $i_po_box ); ?>">
													</div>
												</div>

												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('state_b')){ echo 'has-error has-feedback';} ?>">
													<label for="state_b" class="col-sm-3 control-label">State*</label>

													<div class="col-sm-9">
														<select class="form-control state-option-b chosen"  id="state_b"   tabindex="24" name="state_b"  >															
															<option value="">Choose a State</option>
															<?php
															foreach ($all_aud_states as $row){
																echo '<option value="'.$row->shortname.'|'.$row->name.'|'.$row->phone_area_code.'|'.$row->id.'">'.$row->name.'</option>';
															}?>
														</select>

														<?php if($this->input->post('state_b')): ?>
															<script type="text/javascript">$("select#state_b").val("<?php echo $this->input->post('state_b'); ?>");</script>
														<?php else: ?>
															<script type="text/javascript">$("select#state_b").val("<?php echo $i_shortname.'|'.$i_state.'|'.$i_phone_area_code.'|'.$i_state_id; ?>");</script>
														<?php endif; ?>

													</div>

												</div>												
												
												<div class="col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('suburb_b')){ echo 'has-error';} ?>">
													<label for="suburb" class="col-sm-3 control-label">Suburb*</label>
													<div class="col-sm-9 col-xs-12">

														<select class="form-control suburb-option-b chosen" id="suburb_b" name="suburb_b">
															<?php $this->company->get_suburb_list('dropdown|state_id|'.$i_suburb.'|'.$i_state.'|'.$i_phone_area_code.'|'.$i_state_id); ?>
														</select>
														<?php if($this->input->post('state_b')): ?>								
															<script type="text/javascript">$("select#suburb_b").val("<?php echo $this->input->post('suburb_b'); ?>");</script>
														<?php else: ?>
															<script type="text/javascript">$("select#suburb_b").val("<?php echo $i_suburb.'|'.$i_state.'|'.$i_phone_area_code; ?>");</script>
														<?php endif; ?> 
														</div>
													</div>

													<div class="clearfix"></div>

													<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('postcode_b')){ echo 'has-error has-feedback';} ?>">
														<label for="postcode_b" class="col-sm-3 control-label">Postcode*</label> <?php //echo $this->input->post('postcode_a'); ?>													
														<div class="col-sm-9  col-xs-12">
															<select class="form-control postcode-option-b chosen" id="postcode_b" name="postcode_b">
																<?php $this->company->get_post_code_list($i_suburb); ?>																	
															</select>
															<?php if($this->input->post('state_b')): ?>	
																<script type="text/javascript">$("select#postcode_b").val("<?php echo $this->input->post('postcode_b'); ?>");</script>
															<?php else: ?>
																<script type="text/javascript">$("select#postcode_b").val("<?php echo $i_postcode; ?>");</script>
															<?php endif; ?> 
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
															<option value='' disabled="disabled">Select Project Manager</option>
															<?php
															foreach ($project_manager as $row){
																echo '<option value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.'</option>';
															}?>
														</select>
														<?php if($this->input->post('project_manager')): ?>
															<script type="text/javascript">$('select#project_manager').val('<?php echo $this->input->post('project_manager'); ?>');</script>
														<?php else: ?>	
															<script type="text/javascript">$('select#project_manager').val('<?php echo $pm_user_id; ?>');</script>
														<?php endif; ?>
																							
													</div>
												</div>
	   								
			      								<div class="col-sm-4 m-bottom-5 clearfix <?php if(form_error('project_admin')){ echo 'has-error has-feedback';} ?>">											
													<div class="col-sm-12">
														<label for="project_administrator" class="control-label">Project Admin*</label>
														<select name="project_admin" class="form-control presonel_add" id="project_administrator" style="width: 100%;" tabindex="28">
															<option value='' disabled="disabled">Select Project Admin</option>
															<?php
															foreach ($project_administrator as $row){
																echo '<option value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.'</option>';
															}?>

															<?php
															foreach ($maintenance_administrator as $row){
																echo '<option value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.'</option>';
															}?>
														</select>
														<?php if($this->input->post('project_admin')): ?>
															<script type="text/javascript">$('select#project_administrator').val('<?php echo $this->input->post('project_admin'); ?>');</script>
														<?php else: ?>		
															<script type="text/javascript">$('select#project_administrator').val('<?php echo $pa_user_id; ?>');</script>	
														<?php endif; ?>												
													</div>
												</div>
												
			      								<div class="col-sm-4 m-bottom-5 clearfix <?php if(form_error('estimator')){ echo 'has-error has-feedback';} ?>">						
													<div class="col-sm-12">
														<label for="estimator" class="control-label">Estimator*</label>													
														<select name="estimator" class="form-control presonel_add" id="estimator" style="width: 100%;" tabindex="29">
															<option value='' disabled="disabled">Select Estimator</option>
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
														<?php if($this->input->post('estimator')): ?>
															<script type="text/javascript">$('select#estimator').val('<?php echo $this->input->post('estimator'); ?>');</script>
														<?php else: ?>		
															<script type="text/javascript">$('select#estimator').val('<?php echo $pe_user_id; ?>');</script>	
														<?php endif; ?>												
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
														<textarea class="form-control" id="project_notes" rows="5"  tabindex="30" name="comments" placeholder="Project Notes"><?php echo ($this->input->post('comments') ?  $this->input->post('comments') : $project_comments ); ?></textarea>														
													</div>
												</div>
											</div>
										</div>
										
									    <div class="m-top-15 clearfix">
									    	<div>
									        	<button type="submit" tabindex="33" class="btn btn-success"><i class="fa fa-floppy-o"></i> Save</button>
									        </div>
									    </div>
									</div>
								
							</div>
						</div>
						
						<div class="col-md-3">
							
							<div class="box danger-box delete-project-box clearfix" <?php echo ( $job_date=='' ? '' : 'style="display:none;"' ); ?>>
								<div class="box-head pad-5">
									<label><i class="fa fa-exclamation-triangle fa-lg"></i> Warning</label>
								</div>
								<div class="box-area pad-10 m-bottom-5 clearfix">
									<p class="text-center">
										I`m fully aware of what I am about to do.
									</p>
									<a class="btn btn-danger" style="width: 150px;display: block;margin: 0 auto;" href="<?php echo base_url(); ?>projects/delete_project/<?php echo $project_id; ?>"><i class="fa fa-exclamation-triangle"></i> Delete Project</a>
								</div>
							</div>
							
							<div class="box clearfix">
								<div class="box-head pad-5">
									<label><i class="fa fa-quote-left fa-lg"></i> Add Comment</label>
								</div>
								<div class="box-area pad-10 m-bottom-5 clearfix">
									<input type="hidden" class="prjc_user_id" value="<?php echo $this->session->userdata('user_id'); ?>" />
									<input type="hidden" class="prjc_user_first_name" value="<?php echo $this->session->userdata('user_first_name'); ?>" />
									<input type="hidden" class="prjc_user_last_name" value="<?php echo $this->session->userdata('user_last_name'); ?>" />
									<input type="hidden" class="prjc_project_id" value="<?php echo $project_id; ?>" />
									<textarea class="project_comment form-control m-bottom-10" placeholder="Add Comment" style="height: 100px;"></textarea>
									<div class="btn btn-info project_comment_btn">Submit</div>
								</div>
							</div>
							
							<div class="box">
								<div class="box-head pad-10">
									<label><i class="fa fa-history fa-lg"></i> Project Comments</label>
								</div>
								<div class="box-area pattern-sandstone pad-5">
	
									<div class="box-content box-list collapse in">
										<?php echo $this->projects->list_project_comments($project_id); ?>
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
<!-- 
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
										#foreach ($all_company_list as $row){
										#echo '<option value="'.$row->company_name.'">'.$row->company_name.'</option>'; }
									?>	
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
 -->

<div class="modal fade" id="select_shopping_center_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Shopping Center Selection</h4>
        <span>Note: Please select a state to view the shopping centers.</span>
      </div>
      <div class="modal-body clearfix pad-10">

        <div class="m-bottom-10">
        	<table id="shoppingCenterTable_prj" class="table table-striped table-bordered" cellspacing="0" width="100%"><thead><tr><th>Brand</th><th>Common</th><th>Street</th><th>Suburb</th><th>State</th></tr></thead>
        		<tbody>
        			<?php echo $this->shopping_center->display_shopping_center_prj(); ?>
        		</tbody>
        	</table>
        </div>
        <div class="pull-right">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary set_invoice_modal_submit">Submit</button>
        </div>

      </div>
    </div>
  </div>
</div>


<?php $focus_id = ($this->input->post('focus') ? $this->input->post('focus') : $focus_company_id );  ?>

<div style="display:none;" class="state_select_list">
	<div class="input-group m-bottom-10 pull-right m-left-10" style="width: 210px;">
		<span class="input-group-addon" id=""><i class="fa fa-map-marker"></i></span>
		<select class="form-control select_state_shopping_center m-bottom-10 input-sm">
			<?php echo $this->projects->set_jurisdiction($focus_id); ?>
			<?php /*
				foreach ($all_aud_states as $row):
					echo '<option value="'.$row->name.'">'.$row->name.'</option>';
				endforeach;*/
			?>
		</select>
	</div>
</div>

<?php $this->load->view('assets/logout-modal'); ?>
<script type="text/javascript">
	<?php echo ($this->input->post('is_form_submit') ? "" : "$('#project_name').focus();" ); ?>
</script>