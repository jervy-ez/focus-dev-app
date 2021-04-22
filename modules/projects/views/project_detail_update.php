<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>
<?php $this->load->module('projects'); ?>
<?php $this->load->module('invoice'); ?>
<?php $this->load->module('shopping_center'); ?>

<script src="<?php echo base_url(); ?>js/vue.js"></script>
<script src="<?php echo base_url(); ?>js/vue-select.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>css/vue-select.css">
<script src="<?php echo base_url(); ?>js/moment.min.js"></script>
<script src="<?php echo base_url(); ?>js/jmespath.js"></script>
<script src="<?php echo base_url(); ?>js/axios.min.js"></script>
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
						<a href="<?php echo base_url(); ?>projects/view/<?php echo $project_id; ?>" >Project Brief Details</a>
					</li>
					<!-- <li>
						<a href="" class="btn-small"><i class="fa fa-magic"></i> Tour</a>
					</li> -->
					  <?php if($this->session->userdata('projects') >= 2): ?>
					<li>
						<a class="btn-small sb-open-right"><i class="fa fa-file-text-o"></i> Project Comments</a>
					</li>
					  <?php endif; ?>
				</ul>
			</div>

		</div>
	</div>
</div>
<!-- title bar -->
<div id="proj_app">
<div class="container-fluid">
	<!-- Example row of columns -->
	<div class="row">				
		<?php $this->load->view('assets/sidebar'); ?>
		<div class="section col-sm-12 col-md-11 col-lg-11">
			<form class="form-horizontal" role="form" method="post" action="">
				<input type="hidden" name="pending_comp_id" id="pending_comp_id">
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
										<div class="pull-right <?php echo ( $this->session->userdata('is_admin') == 1 ? ' ' : 'hide'); ?>">
											<button  type="reset" class="btn btn-warning pull-right">Reset Form</button ><br />
											<select name="focus" class="form-control focus m-top-10" id="focus">
												<option value="">Select Focus Company</option>
												<?php foreach ($focus as $key => $value): ?>
													<option value="<?php echo $value->company_id; ?>"><?php echo $value->company_name; ?></option>
												<?php endforeach; ?>
											</select>
											
										</div>

										<?php if($this->session->userdata('is_admin') != 1):  ?>
											<div id="" class="pull-right">												
												<p class="text-right" style="color:red;     font-size: 14px;"><i class="fa fa-info-circle fa-lg"></i> <strong>You need to request the sojourn admin<br />to change the focus company.</strong></p>
											</div>
										<?php endif;  ?>
										
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
												<?php if($job_date == ""): ?>
												<select class= "input-sm pull-right" v-model = "sel_client_type" v-on:change ="select_client_type" name = "client_type">
													<option value = "0">Existing Client</option>
													<option value = "1">Pending Client</option>
												</select>
												<?php endif; ?>
											</div>
											
											<div class="box-area pad-5 clearfix">																												<div v-show="client_type">					
												
													<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('company_prg')){ echo 'has-error has-feedback';} ?>">
														<label for="company_prg" class="col-sm-3 control-label">Client*</label>
														<div class="col-sm-9">														
															<div class="input-group">
																<span class="input-group-addon"><i class="fa fa-briefcase  fa-lg"></i></span>
																<select name="company_prg" class="form-control chosen find_contact_person get_address_invoice" id="company_prg" style="width: 100%;" tabindex="2">
																	<!-- <option value=''>Select Client Name*</option>																												
																	<?php //$this->company->company_list('dropdown'); ?> -->	

																	<?php

																		if($this->session->userdata('company_project') == 1){
																			echo '
																			<option value="Fsf Group Pty Ltd|75">Fsf Group Pty Ltd</option>
																			<option value="Focus Shopfit Nsw Pty Ltd|72">Focus Shopfit Nsw Pty Ltd</option>
																			<option value="Focus Shopfit Pty Ltd|726">Focus Shopfit Pty Ltd</option>';

																		}else{

																			foreach ($all_company_list as $row){
																				echo '<option value="'.$row->company_name.'|'.$row->company_id.'">'.ucwords(strtolower($row->company_name)).'</option>';
																			}
																		}

																	?>


																</select>



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
																 <?php /*	<option readonly value="<?php echo $contact_person_id; ?>"  ><?php echo "$contact_person_fname $contact_person_lname"; ?> (Selected)</option> */ ?>
																	<?php $this->projects->find_contact_person($client_company_id); ?>
																<?php endif; ?>
															</select>

															<?php if($this->input->post('contact_person')): ?>
																<script type="text/javascript">$('select#contact_person').val('<?php echo $this->input->post('contact_person'); ?>');</script>
															<?php else: ?>
																<script type="text/javascript">$('select#contact_person').val('<?php echo $contact_person_id; ?>');</script>

															<?php endif; ?>


														</div>
													</div>
													<script type="text/javascript">

														$('select#company_prg').on("change", function(e) {
															var sub_client = $(this).val();

															$('select#sub_client_id').val(sub_client);

														});

													</script>
												</div>

												<div v-show="!client_type">
													<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('company_prg')){ echo 'has-error has-feedback';} ?>">
														<label for="company_prg" class="col-sm-3 control-label">Pending Client* </label>
														<div class="col-sm-9">														
															<v-select name = "company_prg_pending" v-model = "temp_company_id" :options="options" id="company_prg_pending" @input="select_pending_client"></v-select>
														</div>
													</div>
													<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('company_prg')){ echo 'has-error has-feedback';} ?>">
														<label for="company_prg" class="col-sm-3 control-label">Contact Person* </label>
														<div class="col-sm-9">														
															<input type="text" class="form-control input-sm" disabled="" v-model = "cont_person">
														</div>
													</div>
												</div>

												<div class="clearfix"></div>

												


<?php /*

												<div class="col-sm-6 m-bottom-10 clearfix hide <?php if(form_error('sub_client_id')){ echo 'has-error has-feedback';} ?>">
													<label for="sub_client_id" class="col-sm-3 control-label">Sub-Client</label>
													<div class="col-sm-9">														
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-briefcase  fa-lg"></i></span>
															<select name="sub_client_id" class="form-control " id="sub_client_id" style="width: 100%;" tabindex="2">
																<!-- <option value=''>Select Client Name*</option>																												
																<?php //$this->company->company_list('dropdown'); ?> -->	

																<?php

																	if($this->session->userdata('company_project') == 1){
																		echo '
																		<option value="Fsf Group Pty Ltd|75">Fsf Group Pty Ltd</option>
																		<option value="Focus Shopfit Nsw Pty Ltd|72">Focus Shopfit Nsw Pty Ltd</option>
																		<option value="Focus Shopfit Pty Ltd|726">Focus Shopfit Pty Ltd</option>';

																	}else{

																		foreach ($all_company_list as $row){
																			echo '<option value="'.$row->company_name.'|'.$row->company_id.'">'.ucwords(strtolower($row->company_name)).'</option>';
																		}
																	}

																?>


															</select>

															<?php if($this->input->post('sub_client_id')): ?>
																<script type="text/javascript">$('select#sub_client_id').val('<?php echo $this->input->post('sub_client_id'); ?>');</script>
															<?php else: ?>
																<script type="text/javascript">$('select#sub_client_id').val('<?php echo $sub_client_company_name."|".$sub_client_company_id; ?>');</script>
															<?php endif; ?>


														</div>
													</div>
												</div>



*/ ?>





												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('brand_name')){ echo 'has-error has-feedback';} ?>">
													<label for="brand_name" class="col-sm-3 control-label">Brand*</label>													
													<div class="col-sm-9  col-xs-12">
														<select class="form-control" id="brand_name" name="brand_name" tabindex='4'>
																<?php echo $this->projects->list_all_brands('select'); ?>	

														</select>

														
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
													<label for="job_date" class="col-sm-3 control-label">

														<?php if($job_date_history!=''): ?>
															<span class="pointer strong"><i class="fa fa-calendar strong tooltip-enabled" title="" data-html="true" data-placement="top" data-original-title="<?php echo $job_date_history; ?>"></i></span> 
														<?php endif; ?>

																	 Job Date</label>
													<div class="col-sm-9">
														
														<?php if($this->invoice->if_has_invoice($project_id) == 0 || $this->invoice->if_completed_invoice($project_id) == 0): ?>
															<div  title="Warning: You need to set up the Project Payments." class="tooltip-enabled">
																<p class="job-date-set form-control text-right" id="job_date" ><?php if($job_date == ''){echo "DD/MM/YYYY";}else{echo $job_date;}  ?></p>
															</div>
														<?php   else: ?>
															<?php if($job_date == '' ): ?>
																<input type="text" placeholder="DD/MM/YYYY" title="Warning: Changing a value in the the Job date affects the project in the WIP section." class="tooltip-enabled job-date-set form-control text-right" name="job_date" value="<?php echo $job_date; ?>" autocomplete="off">
															<?php elseif($this->session->userdata('is_admin') == 1 || $this->session->userdata('job_date') == 1 || ( $this->session->userdata('user_role_id') == 7 && $job_category == 'Maintenance' )  ): ?>
																<input type="text" placeholder="DD/MM/YYYY" title="Warning: Changing a value in the the Job date affects the project in the WIP section." class="pad-10 tooltip-enabled job-date-set form-control text-right" id="job_date" name="job_date" value="<?php echo $job_date; ?>" autocomplete="off">
															<?php else: ?>
																<p title="Warning: You need to request to the Project Manager to change the Job Date" class="form-control tooltip-enabled job-date-set text-right" ><?php echo $job_date; ?></p>
																
															<?php endif; ?>
														<?php  endif; ?>

													</div>													
												</div>

												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('job_category')){ echo 'has-error has-feedback';} ?>">
													<label for="job_category" class="col-sm-3 control-label">Job Type*</label>													
													<div class="col-sm-9  col-xs-12">
														<select class="form-control" id="job_type" name="job_type" tabindex='4'>


															<?php if($this->session->userdata('company_project') != 1 ): ?>
																<option value="Shopping Center">Shopping Center</option>
																<option value="Street Site">Street Site</option>
																<option value="Office">Office</option>
															<?php endif; ?>

															<?php if($this->session->userdata('company_project') == 1 || $job_type == 'Company'): ?>
																<option value="Company">Company</option>
															<?php endif; ?>





														</select>

														
													</div>
												</div>
												
												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('job_category')){ echo 'has-error has-feedback';} ?>">
													<label for="job_category" class="col-sm-3 control-label">Job Category*</label>													
													<div class="col-sm-9  col-xs-12">
														<!-- <select class="form-control postcode-option" id="job_category" name="job_category" tabindex='4'>															
															<option value="">Choose a Job Category</option>
															<option value="Design Works">Design Works</option>
															<option value="Kiosk">Kiosk</option>
															<option value="Full Fitout">Full Fitout</option>
															<option value="Refurbishment">Refurbishment</option>
															<option value="Strip Out">Strip Out</option>
															<option value="Minor Works">Minor Works (Under $20,000.00)</option>
															<option value="Maintenance">Maintenance</option>
														</select> -->

														<select class="form-control postcode-option tooltip-test" id="job_category" name="job_category" tabindex='4' data-original-title="On selecting the Job Category, Project Area is only optional if Job Category is Strip Out, Minor Works or Maintenance.">
															<?php if($this->session->userdata('company_project') != 1): ?>
															<option class="def_slct_optn"  value="Design Works">Design Works</option>
															<option class="def_slct_optn"  value="Joinery Only">Joinery Only</option>
															<option class="def_slct_optn"  value="Kiosk">Kiosk</option>
															<option  class="def_slct_optn" value="Full Fitout">Full Fitout</option>
															<option  class="def_slct_optn" value="Refurbishment">Refurbishment</option>
															<option  class="def_slct_optn" value="Strip Out">Strip Out</option>
															<option  class="def_slct_optn" value="Minor Works">Minor Works (Under $20,000.00)</option>
															<option  class="main_slct_optn" value="Maintenance">Maintenance</option>
															<?php endif; ?>

															<?php if($this->session->userdata('company_project') == 1 || $job_category == 'Company'): ?>
																<option value="Company">Company</option>
															<?php endif; ?>
															
														</select>

														
													</div>
												</div>
												<input type="hidden" id = "update_proj_type" value = "<?php echo $job_category ?>">
												<?php //if($job_category == "Maintenance"){ ?>
												<div class = "maintenance_site_cont_form">
													<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('site_start')){ echo 'has-error has-feedback';} ?>">
														<label for="site_start" class="col-sm-3 control-label" style ="font-size: 12px">Site Contact Person</label>
														<div class="col-sm-9">
															<input tabindex='6' type="text" placeholder="Contact Person Name" class="form-control" id="site_cont_person" name="site_cont_person" value = "<?php echo $contact_person_name ?>">
														</div>
													</div>

													<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('site_start')){ echo 'has-error has-feedback';} ?>">
														<label for="site_start" class="col-sm-3 control-label" style ="font-size: 12px">Site Contact Number</label>
														<div class="col-sm-9">
															<input tabindex='6' type="text" placeholder="Contact Number" class="form-control" id="site_cont_number" name="site_cont_number" value = "<?php echo $contact_person_number ?>">
														</div>
													</div>

													<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('site_start')){ echo 'has-error has-feedback';} ?>">
														<label for="site_start" class="col-sm-3 control-label" style ="font-size: 12px">Site Contact Mobile</label>
														<div class="col-sm-9">
															<input tabindex='6' type="text" placeholder="Contact Person Mobile" class="form-control" id="site_cont_mobile" name="site_cont_mobile" value = "<?php echo $contact_person_mobile ?>">
														</div>
													</div>

													<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('site_start')){ echo 'has-error has-feedback';} ?>">
														<label for="site_start" class="col-sm-3 control-label" style ="font-size: 12px">Site Contact E-mail</label>
														<div class="col-sm-9">
															<input tabindex='6' type="text" placeholder="Contact E-mail" class="form-control" id="site_cont_email" name="site_cont_email" value = "<?php echo $contact_person_email ?>">
														</div>
													</div>
												</div>
												<?php
													//}
												 ?>
							
												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('site_start')){ echo 'has-error has-feedback';} ?>">
													<label for="site_start" class="col-sm-3 control-label">Site Start*</label>
													<div class="col-sm-9">
<input tabindex='6' type="text" placeholder="DD/MM/YYYY" title="Warning: Changing Site Start Date when project is already in WIP or Previously in WIP, will reset its labour shed, and will also change date range of of Project Schedule." class="tooltip-enabled form-control" id="site_start" name="site_start" tabindex="4" value="<?php echo ($this->input->post('site_start') ?  $this->input->post('site_start') : $date_site_commencement ); ?>" autocomplete="off">
													</div>
												</div>
													
												<div class="col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('site_finish')){ echo 'has-error';} ?>">
													<label for="site_finish" class="col-sm-3 control-label">Site Finish*</label>
													<div class="col-sm-9 col-xs-12">
<input tabindex='7' type="text" placeholder="DD/MM/YYYY" title="Warning: Changing Site Start Date when project is already in WIP or Previously in WIP, will reset its labour shed, and will also change date range of of Project Schedule." class="tooltip-enabled form-control" id="site_finish" name="site_finish" tabindex="4" value="<?php echo ($this->input->post('site_finish') ?  $this->input->post('site_finish') : $date_site_finish ); ?>" autocomplete="off">
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
														<?php if($job_date != ''): ?>
															<p class="form-control" tabindex="8" ><?php echo ($this->input->post('install_hrs') ?  $this->input->post('install_hrs') : $install_time_hrs ); ?></p>
															<input type="hidden" name="install_hrs" id="install_hrs" class="form-control hide hidden" tabindex="8" <?php echo ($job_date != '' ? 'style="z-index: -1;"' : ''); ?> placeholder="Install Hrs" value="<?php echo ($this->input->post('install_hrs') ?  $this->input->post('install_hrs') : $install_time_hrs ); ?>"/>
														<?php else: ?>
															<?php if($this->invoice->if_project_invoiced_full($project_id)): ?>
																<p class="form-control" tabindex="8" ><?php echo ($this->input->post('install_hrs') ?  $this->input->post('install_hrs') : $install_time_hrs ); ?></p>
																<input type="hidden" name="install_hrs" id="install_hrs" class="form-control hide hidden" tabindex="8" <?php echo ($job_date != '' ? 'style="z-index: -1;"' : ''); ?> placeholder="Install Hrs" value="<?php echo ($this->input->post('install_hrs') ?  $this->input->post('install_hrs') : $install_time_hrs ); ?>"/>
															<?php else: ?>
																<input type="text" name="install_hrs" id="install_hrs" class="form-control" tabindex="8" <?php echo ($job_date != '' ? 'style="z-index: -1;"' : ''); ?> placeholder="Install Hrs" value="<?php echo ($this->input->post('install_hrs') ?  $this->input->post('install_hrs') : $install_time_hrs ); ?>"/>
															<?php endif; ?>
														<?php endif; ?>
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
																<p class="min_mark_up hidden"><?php echo $min_markup; ?></p>

																<?php if($job_date != ''): ?>
																	<p class="form-control"><?php echo ($this->input->post('project_markup') ?  $this->input->post('project_markup') : $markup ); ?></p>
																	<input type="hidden" name="project_markup" id="project_markup" class="form-control hide hidden" tabindex="12" placeholder="Markup %" value="<?php echo ($this->input->post('project_markup') ?  $this->input->post('project_markup') : $markup ); ?>"  <?php echo ($job_date != '' ? 'style="z-index: -1;"' : ''); ?>/>

																<?php else: ?>
																	<?php if($this->invoice->if_project_invoiced_full($project_id) && $this->invoice->if_has_invoice($project_id)  ): ?>
																		<p class="form-control"><?php echo ($this->input->post('project_markup') ?  $this->input->post('project_markup') : $markup ); ?></p>
																		<input type="hidden" name="project_markup" id="project_markup" class="form-control hide hidden" tabindex="12" placeholder="Markup %" value="<?php echo ($this->input->post('project_markup') ?  $this->input->post('project_markup') : $markup ); ?>"  <?php echo ($job_date != '' ? 'style="z-index: -1;"' : ''); ?>/>
																	<?php else: ?>
																		<input type="text" name="project_markup" id="project_markup" class="form-control" tabindex="12" placeholder="Markup %" value="<?php echo ($this->input->post('project_markup') ?  $this->input->post('project_markup') : $markup ); ?>"  <?php echo ($job_date != '' ? 'style="z-index: -1;"' : ''); ?>/>
																	<?php endif; ?>
																<?php endif; ?>
													</div>
												</div>											
													
												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('areacode')){ echo 'has-error has-feedback';} ?>">
													<label for="project_area" class="col-sm-3 control-label text-center">Project Area*</label>
													<div class="col-sm-9">												

														<div class="input-group ">
															<span class="input-group-addon">SQM</span>
															<input type="text" name="project_area" id="project_area" class="form-control  tooltip-test" data-original-title="Project Area is only optional if Job Category is Strip Out, Minor Works, Kiosk or Maintenance." tabindex="13" placeholder="Project Area" value="<?php echo ($this->input->post('project_area') ?  $this->input->post('project_area') : $project_area ); ?>"/>
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
														<label for="" class="col-sm-3 control-label">Suburb*</label>
														<div class="col-sm-9 col-xs-12">

															<select class="form-control suburb-option-a chosen" id="suburb_a" name="suburb_a">
																<?php $this->company->get_suburb_list('dropdown|state_id|'.$suburb.'|'.$state.'|'.$phone_area_code.'|'.$state_id); ?>
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

													<select class="form-control postcode-option-a chosen" id="postcode_a" name="postcode_a">
														<?php $this->company->get_post_code_list($suburb); ?>																	
													</select>

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
														<input type="hidden" name="selected_shopping_center_detail" class="selected_shopping_center_detail" id="selected_shopping_center_detail" value="<?php echo $shop_name.', '.$unit_number.' '.$street.', '.ucwords(strtolower($suburb)).', '.$state.', '.$postcode; ?>">
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
																<strong class="selected_shopping_center_text" id="selected_shopping_center_text">
															<?php echo $shop_name; echo ', '.$unit_number.' '.$street.', '.ucwords(strtolower($suburb)).', '.$state.', '.$postcode; ?></strong>
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

														

													</div>

												</div>												
												
												<div class="col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('suburb_b')){ echo 'has-error';} ?>">
													<label for="suburb" class="col-sm-3 control-label">Suburb*</label>
													<div class="col-sm-9 col-xs-12">

														<select class="form-control suburb-option-b chosen" id="suburb_b" name="suburb_b">
															<?php $this->company->get_suburb_list('dropdown|state_id|'.$i_suburb.'|'.$i_state.'|'.$i_phone_area_code.'|'.$i_state_id); ?>
														</select>
														
														</div>
													</div>

													<div class="clearfix"></div>

													<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('postcode_b')){ echo 'has-error has-feedback';} ?>">
														<label for="postcode_b" class="col-sm-3 control-label">Postcode*</label> <?php //echo $this->input->post('postcode_a'); ?>													
														<div class="col-sm-9  col-xs-12">
															<select class="form-control postcode-option-b chosen" id="postcode_b" name="postcode_b">
																<?php $this->company->get_post_code_list($i_suburb); ?>																	
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


											<?php $comp_id_selected = ($this->input->post('focus') ? $this->input->post('focus') : $focus_company_id ); ?>
											
											<div class="box-area pad-5 clearfix">
												<div class="col-sm-3 m-bottom-5 clearfix <?php if(form_error('project_manager')){ echo 'has-error has-feedback';} ?>">
													<div class="col-sm-12">
														<label for="project_manager" class="control-label">Project Manager*</label><br />
														<!-- <select name="project_manager" class="form-control presonel_add" id="project_manager" style="width: 100%;" tabindex="27">
															<option value='' disabled="disabled">Select Project Manager</option>
															<?php /*
															foreach ($project_manager as $row){
																echo '<option value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.'</option>';
															}*/?>
														</select> -->

														<select name="project_manager" class="form-control presonel_add" id="project_manager" style="width: 100%;" tabindex="27">
															<option value=''>Select Project Manager</option>
																<?php foreach ($project_manager as $row){
																	if($job_type != 'Company'):
																		if( $row->user_id != 29 && $row->user_focus_company_id == $comp_id_selected){
																			echo '<option class="pm_comp_option pm_comp_'.$row->user_focus_company_id.'" value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.'</option>';
																		}else{
																			echo '<option style="display:none" class="pm_comp_option pm_comp_'.$row->user_focus_company_id.'" value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.'</option>';
																		}
																	endif;
																}?>
																
 

															<?php
															
																foreach ($account_manager as $row){
																	if( $row->user_focus_company_id == $comp_id_selected){
																		echo '<option class="pm_comp_option pm_comp_'.$row->user_focus_company_id.'" value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.'</option>';
																	}else{
																		echo '<option style="display:none"  class="pm_comp_option pm_comp_'.$row->user_focus_company_id.'" value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.'</option>';
																	}
																}
															?>


																<?php if($pm_user_id == 9){ echo '<option value="9">Trevor Gamble</option>'; } ?>


 


															<?php if($this->session->userdata('company_project') == 1 || $job_type == 'Company'): ?>
																<?php echo '<option value="'.$this->session->userdata('user_id').'">'.ucfirst($this->session->userdata('user_first_name')).' '.ucfirst($this->session->userdata('user_last_name')).'</option>'; ?>
															<?php else: ?>
																<option value="29">Maintenance Manager</option>
															<?php endif; ?>
 
																
														</select>


														


																							
													</div>
												</div>
	   								
			      								<div class="col-sm-3 m-bottom-5 clearfix <?php if(form_error('project_admin')){ echo 'has-error has-feedback';} ?>">											
													<div class="col-sm-12">
														<label for="project_administrator" class="control-label">Project Admin*</label>
														<!-- <select name="project_admin" class="form-control presonel_add" id="project_administrator" style="width: 100%;" tabindex="28">
															<option value='' disabled="disabled">Select Project Admin</option>
															<?php /*
															foreach ($project_administrator as $row){
																echo '<option value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.'</option>';
															}*/ ?>

															<?php /*
															foreach ($maintenance_administrator as $row){
																echo '<option value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.'</option>';
															}*/ ?>
														</select> -->
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
														<?php if($this->session->userdata('company_project') == 1|| $job_type == 'Company'): ?>
															<?php echo '<option value="'.$this->session->userdata('user_id').'">'.ucfirst($this->session->userdata('user_first_name')).' '.ucfirst($this->session->userdata('user_last_name')).'</option>'; ?> 

														<?php endif; ?>
														</select>


																									
													</div>
												</div>
												
			      								<div class="col-sm-3 m-bottom-5 clearfix <?php if(form_error('estimator')){ echo 'has-error has-feedback';} ?>">						
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
															<?php if($this->session->userdata('company_project') == 1|| $job_type == 'Company'): ?>
																<?php echo '<option value="'.$this->session->userdata('user_id').'">'.ucfirst($this->session->userdata('user_first_name')).' '.ucfirst($this->session->userdata('user_last_name')).'</option>'; ?> 
																
															<?php endif; ?>
														</select>

																									
													</div>
												</div>



												<div class="col-sm-3 m-bottom-5 clearfix <?php if(form_error('client_contact_project_manager')){ echo 'has-error has-feedback';} ?>">
													<div class="col-sm-12">
														<label for="client_contact_project_manager" class="control-label">FOCUS - Client Contact</label><br />
														<select name="client_contact_project_manager" class="form-control client_contact tooltip-enabled" id="client_contact_project_manager" style="width: 100%;" tabindex="27" data-original-title="Project Manager contact of the Client, Default value is selected Project Manager of the project.">
															
														<option value=''>Select Personnel</option>  
															<?php
																foreach ($project_manager as $row){																
																	echo '<option value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.'</option>';
															}?>


															<?php
																foreach ($account_manager as $row){
																	echo '<option value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.'</option>';
																}
															?>
																
																<?php if($pm_user_id == 9){ echo '<option value="9">Trevor Gamble</option>'; } ?>



																<?php if($this->session->userdata('company_project') == 1 || $job_type == 'Company'): ?>
																	<?php echo '<option value="'.$this->session->userdata('user_id').'">'.ucfirst($this->session->userdata('user_first_name')).' '.ucfirst($this->session->userdata('user_last_name')).'</option>'; ?>
																 
																<?php endif; ?>



															<option value='9'>Trevor Gamble</option>
															<?php
															foreach ($project_manager as $row){																
																//echo '<option value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.'</option>';
															}?>



 

															
														</select>
														

																							
													</div>
												</div>

													<div class="col-sm-3 m-bottom-5 clearfix">		
	                                                    <div class="col-sm-12">		
	                                                        <label for="joinery_personel" class="control-label">Joinery Personnel</label><br />		
	                                                        <select id="proj_joinery_user" class="form-control chosen" tabindex="4" name="proj_joinery_user">                                                    		
	                                                            <?php $this->admin->joinery_selected_user_update($joinery_selected_sender); ?>		
	                                                        </select>		
	                                                    </div>		
	                                                </div>

												<?php //if($this->session->userdata('is_admin') == 1 ): ?>

													<div class="col-sm-3 m-bottom-5 clearfix <?php if(form_error('leading_hand')){ echo 'has-error has-feedback';} ?>">						
														<div class="col-sm-12">
															<label for="leading_hand" class="control-label">Leading Hand</label>
															<select name="leading_hand" class="form-control" id="leading_hand" style="width: 100%;" tabindex="29">
																	<option value=''>Select Leading Hand</option>
																	<?php
																	foreach ($leading_hand as $row){
																		echo '<option value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.'</option>';
																	}?>

																<?php if($this->session->userdata('company_project') == 1|| $job_type == 'Company'): ?>
																	<?php echo '<option value="'.$this->session->userdata('user_id').'">'.ucfirst($this->session->userdata('user_first_name')).' '.ucfirst($this->session->userdata('user_last_name')).'</option>'; ?> 
																	
																<?php endif; ?>

																	<option value='0'>Other</option>
															</select>

															

														</div>
													</div>

													<div id="editOtherLH" style="<?php echo ($lead_hand_user_id == 0 && $manual_lh_name != '' ? '' : 'display: none;'); ?>">
														
														<div class="col-sm-3 m-bottom-5 clearfix <?php if(form_error('lh_name')){ echo 'has-error has-feedback';} ?>">
															<div class="col-sm-12">
																<label for="leading_hand" class="control-label">Leading Hand Full Name*</label><br />
																<input type="text" id="lh_name" class="form-control" name="lh_name" placeholder="Leading Hand Full Name" style="text-transform: capitalize;" value="<?php echo ($lead_hand_user_id == 0 ? $manual_lh_name : ''); ?>">				
															</div>
														</div>

														<div class="col-sm-3 m-bottom-5 clearfix <?php if(form_error('lh_mobile_no')){ echo 'has-error has-feedback';} ?>">
															<div class="col-sm-12">
																<label for="leading_hand" id="lh_mobile" class="control-label">Leading Hand Mobile No.*</label><br />
																<input type="text" class="form-control" name="lh_mobile_no" placeholder="Leading Hand Mobile No" value="<?php echo ($lead_hand_user_id == 0 ? $manual_lh_contact : ''); ?>">						
															</div>
														</div>

													</div>

												<?php //endif; ?>
											</div>
										</div>
	 									
									    
	      								<div class="box m-top-15">
											<div class="box-head pad-5">
												<label for="project_notes"><i class="fa fa-pencil-square fa-lg"></i> Project Notes</label>
											</div>
											
											<div class="box-area pad-5 clearfix">
												<div class="clearfix <?php if(form_error('generalEmail')){ echo 'has-error has-feedback';} ?>">
													<div class="">
														<textarea class="form-control" id="project_notes" rows="20"  tabindex="30" name="comments" placeholder="Project Notes" style="resize: vertical;"><?php echo ($this->input->post('comments') ?  $this->input->post('comments') : $project_comments ); ?> </textarea>														
													</div>
												</div>
											</div>
										</div>
										
									    <div class="m-top-15 clearfix">
									    	<div>

					<?php if( ($job_category == 'Company' && $this->session->userdata('company_project') == 1) || $this->session->userdata('is_admin') == 1 || $this->session->userdata('user_role_id') == 3 || $this->session->userdata('user_role_id') == 2  || $this->session->userdata('user_role_id') == 16  ):  ?>



									    			<button type="submit" tabindex="33" class="btn btn-success"><i class="fa fa-floppy-o"></i> Save Changes</button>


									    		<?php elseif($this->session->userdata('projects') >= 2 &&  ($job_category != 'Company' && $this->session->userdata('company_project') != 1 )  ): ?>


									    			<button type="submit" tabindex="33" class="btn btn-success"><i class="fa fa-floppy-o"></i> Save Changes</button>

									    		<?php endif; ?>


									        </div>
									    </div>
									</div>
								
							</div>
						</div>
						
						<div class="col-md-3">
							<?php if( $this->session->userdata('is_admin') == 1): ?>
							<div class="box danger-box delete-project-box clearfix" <?php echo ( $job_date=='' ? '' : 'style="display:none;"' ); ?>>
								<a class="collapsed" role="button" data-toggle="collapse" href="#collapseListGroup1" aria-expanded="false" aria-controls="collapseListGroup1"><div class="box-head pad-5">
									
										<label><i class="fa fa-exclamation-triangle fa-lg"></i> Warning</label>
									
								</div></a>
								<div class="box-area pad-10 m-bottom-5 clearfix collapse" id="collapseListGroup1"  style="height: 0px;">
									<p class="text-center">
										I`m fully aware of what I am about to do.
									</p>
									<a class="btn btn-danger" style="width: 150px;display: block;margin: 0 auto;" href="<?php echo base_url(); ?>projects/delete_project/<?php echo $project_id; ?>"><i class="fa fa-exclamation-triangle"></i> Delete Project</a>
								</div>
							</div>
							<?php endif; ?>
							
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
        	<table id="shoppingCenterTable_prj" class="table table-striped table-bordered" cellspacing="0" width="100%"><thead><tr><th>Common</th><th>Street</th><th>Suburb</th><th>State</th></tr></thead>
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
</div>

<?php $this->load->view('assets/logout-modal'); ?>
<script type="text/javascript">
	Vue.component('v-select', VueSelect.VueSelect);
	var app = new Vue({
	  	el: '#proj_app',
	  	data: {
	  		client_type: true,
	  		sel_client_type: '<?php echo $is_pending_client ?>',
		    company: [],
		    temp_company_id: [],
		    options: [],
			cont_person: "",
			
		  
	  	},
		mounted: function(){
			this.load_contractor_supplier();
			this.select_client_type();
		},
  		filters: {
		    getDayname: function(date){
		      return moment(date).format('ddd');
		    },
		    format_date: function(date){
		      return moment(date).format('ll');
		    },
		    ausdate: function(date) {
		      if(date == '0000-00-00'){
		        return '';
		      }else{
		        return moment(date).format('DD/MM/YYYY');
		      }
		      
		    },
		    getTime: function(date){
		      var temp_date = '2020-01-01 '+date;
		      return moment(temp_date).format('h:mm a');
		    },
  		},
  		methods: {
  			load_contractor_supplier: function(){
		    	axios.post("<?php echo base_url() ?>company/fetch_temporary_comp", 
		      	{
		      		'company_type': 0
		      	}).then(response => {	
		      		this.options = [];
		          	this.company = response.data;    
		          	for (var key in this.company) {
		          		if(this.company[key].company_details_temp_id == '<?php echo $client_id ?>'){
		          			this.temp_company_id = {
						      value: this.company[key].company_details_temp_id,
						      label: this.company[key].company_name
						    }

						    this.cont_person = this.company[key].contact_person_fname +" "+  this.company[key].contact_person_sname
		          		
		          		}

		          		this.options.push({'value': this.company[key].company_details_temp_id, 'label': this.company[key].company_name });
			        }  

			        

	  				     
		      	}).catch(error => {
		        	console.log(error.response)
		      	});
		    },
		    select_client_type : function(){
		    	if(this.sel_client_type == 0){
		    		this.client_type = true;
		    	}else{
		    		this.client_type = false;
		    	}
		    },
		    
			select_pending_client: function(){
				$("#pending_comp_id").val(this.temp_company_id.value+"/"+this.temp_company_id.label);
				for (var key in this.company) {
					if(this.company[key].company_details_temp_id == this.temp_company_id.value){
						this.cont_person = this.company[key].contact_person_fname +" "+ this.company[key].contact_person_sname;
					}
			    }     
			},
		}
	});
	// MC 02-19-18
	if ($('select#leading_hand').val() === '0'){
		$('#editOtherLH').show();
	}

	$('select#leading_hand').change(function(){

		var leading_hand = $('select#leading_hand').val();

		// alert(leading_hand);

		if (leading_hand === '0'){
			$('#editOtherLH').show();
		} else {
			$('#editOtherLH').hide();
		}
	});

	<?php echo ($this->input->post('is_form_submit') ? "" : "$('#project_name').focus();" ); ?>

	$('select#project_manager').change(function(){
		var selected_pm_of_project = $(this).val();
		$('select#client_contact_project_manager').val(selected_pm_of_project);
	});




$('#site_start').datetimepicker({ format: 'DD/MM/YYYY'});
$('#site_finish').datetimepicker({
useCurrent: false, //Important! See issue #1075
format: 'DD/MM/YYYY'
});



$("#site_start").on("dp.change", function (e) {
	$('#site_finish').data("DateTimePicker").minDate(e.date);

	$('#site_finish').datetimepicker({
	useCurrent: false, //Important! See issue #1075
	format: 'DD/MM/YYYY'
	});

	$('#summ_starting_date').text( e.date.format('DD/MM/YYYY') );

	var date_start = $(this).val();
	var date_finish = $('#site_finish').val();

	if(date_finish){
		var date_s_tmsp = moment(date_start,'DD/MM/YYYY').format('x');
		var date_f_tmsp = moment(date_finish,'DD/MM/YYYY').format('x');
		if(date_s_tmsp > date_f_tmsp){
			$(this).val('');
			alert('Site Start date selected conflicts to Site Finish, please selecte another date.')
		}
	}
});



$("#site_finish").on("dp.change", function (e) {

	//$("#site_start").data("DateTimePicker").minDate(e.date);
	//$('#site_start').data("DateTimePicker").maxDate(e.date);

	$('#summ_end_date').text( e.date.format('DD/MM/YYYY') );

	var date_start = $('#site_start').val();
	var date_finish = $(this).val();

	if(date_start){
		var date_s_tmsp = moment(date_start,'DD/MM/YYYY').format('x');
		var date_f_tmsp = moment(date_finish,'DD/MM/YYYY').format('x');
		if(date_s_tmsp > date_f_tmsp){
			$(this).val('');
			alert('Site Finish date selected conflicts to Site Start, please selecte another date.')
		}
	}

});

 


$('.job-date-set').datetimepicker({
   format: 'DD/MM/YYYY',maxDate: new Date
});

$('.job-date-set').val('<?php echo $job_date; ?>');


</script>

<?php if($this->input->post('company_prg')): ?>
	<script type="text/javascript">$('select#company_prg').val('<?php echo $this->input->post('company_prg'); ?>');</script>
<?php else: ?>
 
	<script type="text/javascript">$('select#company_prg').val("<?php  echo str_replace('&apos;','\'',$client_company_name)."|".$client_company_id; ?>");</script>
<?php endif; ?>

<?php if($this->input->post('job_type')): ?>
	<script type="text/javascript">$("select#job_type").val("<?php echo $this->input->post('job_type'); ?>");</script>
<?php else: ?>
	<script type="text/javascript">$("select#job_type").val("<?php echo $job_type; ?>");</script>
<?php endif; ?>

<?php if($this->input->post('suburb_a')): ?>								
	<script type="text/javascript">$("select#suburb_a").val("<?php echo $this->input->post('suburb_a'); ?>");</script>
<?php else: ?>
	<script type="text/javascript">$("select#suburb_a").val("<?php echo $suburb.'|'.$state.'|'.$phone_area_code; ?>");</script>
<?php endif; ?>

<?php if($this->input->post('postcode_a')): ?>
	<script type="text/javascript">$("select#postcode_a").val("<?php echo $this->input->post('postcode_a'); ?>");</script>
<?php else: ?>
	<script type="text/javascript">$("select#postcode_a").val("<?php echo $postcode; ?>");</script>	
<?php endif; ?>	

<?php if($this->input->post('state_b')): ?>
	<script type="text/javascript">$("select#state_b").val("<?php echo $this->input->post('state_b'); ?>");</script>
<?php else: ?>
	<script type="text/javascript">$("select#state_b").val("<?php echo $i_shortname.'|'.$i_state.'|'.$i_phone_area_code.'|'.$i_state_id; ?>");</script>
<?php endif; ?>

suburb
<?php if($this->input->post('state_b')): ?>								
	<script type="text/javascript">$("select#suburb_b").val("<?php echo $this->input->post('suburb_b'); ?>");</script>
<?php else: ?>
	<script type="text/javascript">$("select#suburb_b").val("<?php echo $i_suburb.'|'.$i_state.'|'.$i_phone_area_code; ?>");</script>
<?php endif; ?> 

<?php if($this->input->post('state_b')): ?>	
	<script type="text/javascript">$("select#postcode_b").val("<?php echo $this->input->post('postcode_b'); ?>");</script>
<?php else: ?>
	<script type="text/javascript">$("select#postcode_b").val("<?php echo $i_postcode; ?>");</script>
<?php endif; ?> 

<?php if($this->input->post('project_manager')): ?>
	<script type="text/javascript">$('select#project_manager').val('<?php echo $this->input->post('project_manager'); ?>');</script>
<?php else: ?>	
	<script type="text/javascript">$('select#project_manager').val('<?php echo $pm_user_id; ?>');</script>
<?php endif; ?>

<?php if($this->input->post('project_admin')): ?>
	<script type="text/javascript">$('select#project_administrator').val('<?php echo $this->input->post('project_admin'); ?>');</script>
<?php else: ?>		
	<script type="text/javascript">$('select#project_administrator').val('<?php echo $pa_user_id; ?>');</script>	
<?php endif; ?>	

<?php if($this->input->post('estimator')): ?>
	<script type="text/javascript">$('select#estimator').val('<?php echo $this->input->post('estimator'); ?>');</script>
<?php else: ?>
	<script type="text/javascript">$('select#estimator').val('<?php echo ($pe_user_first_name == "" ? 0 : $pe_user_id ); ?>');</script>	
<?php endif; ?>	

<script type="text/javascript">$('select#client_contact_project_manager').val('<?php echo $this->input->post('project_manager'); ?>');</script>	


<?php if($this->input->post('client_contact_project_manager')): ?>
	<script type="text/javascript">$('select#client_contact_project_manager').val('<?php echo $this->input->post('client_contact_project_manager'); ?>');</script>
<?php else: ?>	
	<script type="text/javascript">$('select#client_contact_project_manager').val('<?php echo $cc_pm_user_id; ?>');</script>
<?php endif; ?>

<?php if($this->input->post('leading_hand')): ?>
	<script type="text/javascript">$('select#leading_hand').val('<?php echo $this->input->post('leading_hand'); ?>');</script>
<?php elseif($manual_lh_name == ""): ?>																
	<script type="text/javascript">$('select#leading_hand').val('<?php echo ($lead_hand_user_first_name == "" ? '' : $lead_hand_user_id ); ?>');</script>	
<?php else: ?>																
	<script type="text/javascript">$('select#leading_hand').val('<?php echo ($lead_hand_user_first_name == "" ? 0 : $lead_hand_user_id ); ?>');</script>	
<?php endif; ?>

<script type="text/javascript">$('.focus').val('<?php echo ($this->input->post('focus') ? $this->input->post('focus') : $focus_company_id ); ?>');</script>

<?php if($this->input->post('brand_name')): ?>
	<script type="text/javascript">$("select#brand_name").val("<?php echo $this->input->post('brand_name'); ?>");</script>
<?php else: ?>
	<script type="text/javascript">$("select#brand_name").val("<?php echo $brand_id; ?>");</script>
<?php endif; ?>

<script type="text/javascript">$("select#job_category").val("<?php echo $this->input->post('job_category'); ?>");</script>

<script type="text/javascript">

	$("select#job_category").on("change", function(e) {
		var job_category = $(this).val();

		if(job_category == 'Maintenance'){
			var focus_comp_assnmt = 3197;
		}else{
			var focus_comp_assnmt = <?php echo $focus_company_id; ?>;
		}

		$('select#focus').val(focus_comp_assnmt);
	});
</script>




<?php if($this->input->post('job_category')): ?>
	<script type="text/javascript">$("select#job_category").val("<?php echo $this->input->post('job_category'); ?>");</script>
<?php else: ?>
	<script type="text/javascript">$("select#job_category").val("<?php echo $job_category; ?>");</script>															
<?php endif; ?>