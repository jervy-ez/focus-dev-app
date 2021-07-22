<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>
<?php $this->load->module('projects'); ?>
<?php $this->load->module('attachments'); ?>
<?php $this->load->module('send_emails'); ?>
<?php $this->load->module('variation'); ?>
<?php $this->load->module('invoice'); ?>
<?php
	if($work_estimated_total > 0){
		$curr_tab = 'works';
	}elseif($this->session->flashdata('curr_tab')){
		$curr_tab = $this->session->flashdata('curr_tab');
	}else{
		$curr_tab = 'project-details';
	}

	if($this->session->flashdata('curr_tab') == 'invoice'){
		$curr_tab = 'invoice';
	}
	
	if($this->session->flashdata('curr_tab') == 'project-details'){
		$curr_tab = 'project-details';
	}

	if($this->session->flashdata('curr_tab') == 'variations'){
		$curr_tab = 'variations';
	}

	if($this->session->flashdata('curr_tab') == 'attachments'){
		$curr_tab = 'attachments';
	}

	$variation = $this->session->flashdata('variation');

	if($this->invoice->if_has_invoice($project_id) == 0): 
		$prog_payment_stat = 0;
	else:
		$prog_payment_stat = 1;
	endif;



?>


<?php
	if($this->session->userdata('projects') < 2 ){	
		echo '<style type="text/css">.modal #create_cqr,.modal #update_contractor,.modal #delete_contractor,.modal #save_contractor,#addwork,#btnaddcontractor,.btn-file{ display: none !important;visibility: hidden !important;}
.estimate{z-index: -1 !important;pointer-events:!important;}
.quick_edit_project .quick_input{z-index: -1 !important;position: relative !important;pointer-events:!important;}
		</style>';
	}
?>





<!-- title bar 
estimate-->
<div class="container-fluid head-control">
	<div class="container-fluid">
		<div class="row">

			<div class="col-md-6 col-sm-4 col-xs-12 pull-left">
				<header class="page-header">
					<h3 class="hidden-md visible-lg">
						<?php $datestring = "%l, %F %d, %Y"; $time = time(); //use time() for timestamp  ?>
						<?php echo $screen; ?><br><small><?php echo mdate($datestring, $time); #echo date("l, F d, Y"); ?></small>
					</h3>

					<h3 class="visible-md visible-sm visible-xs">
						<?php echo $project_name; ?><br />
						<small>&nbsp; Project No.<?php echo $project_id; ?></small>
					</h3>

				</header>
			</div>

			<div class="page-nav-options col-md-6 col-sm-8 col-xs-12 pull-right hidden-xs">
				<ul class="nav nav-tabs navbar-right">
					<li>
						<a href="<?php echo base_url(); ?>" ><i class="fa fa-home"></i> Home</a>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>projects" >Project</a>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>projects/update_project_details/<?php echo $project_id; ?>" class="btn-small sub-nav-bttn">Project Details</a>
					</li>
					<?php if($this->session->userdata('is_admin') == 1 ): ?>
					<li>
						<a href="#" class="btn-small view_applied_settings"><i class="fa fa-cog"></i> Applied Settings</a>
					</li>
					<?php endif; ?>							
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

			<div class="m-5">

				<?php if($this->session->userdata('is_admin') == 1 ): ?>

					<div class="border-less-box alert alert-info fade in pad-0 no-pad row">
						<div class="col-sm-2"><strong>Project Mark-Up:</strong> <?php echo $markup; ?>%</div>
						<div class="col-sm-2"><strong>Site Labour Total:</strong> (ex-gst) $<?php echo number_format($final_labor_cost,2); ?></div>
						<div class="col-sm-2"><strong>Variation Total:</strong> $<span class="variation_total"><?php echo number_format($variation_total,2); ?></span></div>
						<div class="col-sm-4"><strong>Project Total:</strong> (ex-gst) $<span id = "proj_ex_gst"><?php echo number_format($final_total_quoted,2); ?></span>  &nbsp;&nbsp;&nbsp;&nbsp; (inc-gst) $<span id = "proj_inc_gst"><?php echo number_format($final_total_quoted+($final_total_quoted*($admin_gst_rate/100)),2); ?></span></div>
						<div class="col-sm-2"><strong>GP:</strong> <?php echo ($gp*100); ?>%</div>

						<div class="admin_settings clearfix" style="display:none;">
							<hr style="margin:5px 0;" />
							<div class="col-sm-3">
								<strong>Total Amalgamated Rate:</strong> <?php echo $admin_total_rate_amalgated; ?>
							</div>
							<div class="col-sm-3">
								<strong>Total Double Time Rate:</strong> <?php echo $admin_total_rate_double; ?>
							</div>
							<div class="col-sm-3">
								<strong>Actual Amalgamated Rate:</strong> <?php echo $admin_actual_rate_amalgate;?>
							</div>
							<div class="col-sm-3">
								<strong>Actual Double:</strong> <?php echo $admin_actual_rate_double;?>
							</div>
							<div class="col-sm-3">
								<strong>Install Markup:</strong> <?php echo $admin_install_markup; ?>%
							</div>
							<div class="col-sm-3">
								<strong>GST Rate:</strong> <span class="project_gst_percent"><?php echo $admin_gst_rate; ?></span>%
							</div>		
							<div class="col-sm-3">
								<strong>Hourly Rate:</strong> <?php echo $admin_hourly_rate; ?>
							</div>		
							<div class="col-sm-3">
								<strong>Site Labour Cost Grad Total:</strong> <?php echo $labour_cost_grand_total; ?>%
							</div>		
						</div>
					</div>

				<?php else: ?>


					<div class="border-less-box alert alert-info fade in pad-0 no-pad row">
						<div class="col-sm-2"><strong>Project Mark-Up:</strong> <?php echo $markup; ?>%</div>
						<div class="col-sm-2"><strong>Site Labour Total:</strong> (ex-gst) $<?php echo number_format($final_labor_cost,2); ?></div>
						<div class="col-sm-2"><strong>Variation Total:</strong> $<span class="variation_total"><?php echo number_format($variation_total,2); ?></span></div>
						<div class="col-sm-4"><strong>Project Total:</strong> (ex-gst) $<?php echo number_format($final_total_quoted,2); ?>  &nbsp;&nbsp;&nbsp;&nbsp; (inc-gst) $<?php echo number_format($final_total_quoted+($final_total_quoted*($admin_gst_rate/100)),2); ?></div>
						<div class="col-sm-2"><strong>GP:</strong> <?php echo ($gp*100); ?>%</div>
					</div>

				<?php endif; ?>

			</div>

			<div class="container-fluid projects">
				<div class="row">
					<div class="col-md-12">
						<div class="left-section-box m-top-1">

							<div class="row clearfix">
								<div class="col-lg-4 col-md-12 hidden-md hidden-sm hidden-xs">
									<div class="pad-left-15 clearfix">										
										<label class="project_name"><?php echo $project_name; ?><p>Client: <strong><?php echo $client_company_name; ?></strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Project No.<?php echo $project_id; ?></p></label>
									</div>
								</div>

								<div class="col-lg-8 col-md-12">

									<div class="pad-top-15 pad-left-15 pad-bottom-5 clearfix box-tabs">	
										
									<ul id="myTab" class="nav nav-tabs pull-right">
										<li class="<?php echo ($curr_tab == 'invoice' ? 'active' : '' ); ?>">
											<a href="#invoices" data-toggle="tab" class="link_tab_invoice" id="<?php echo $project_id ?>"><i class="fa fa-list-alt fa-lg"></i> Invoices</a>
										</li>
										<li class="<?php echo ($curr_tab == 'project-details' ? 'active' : '' ); ?>">
											<a href="#project-details" data-toggle="tab"><i class="fa fa-briefcase fa-lg"></i> Project Details</a>
										</li>
										<li class="<?php echo ($curr_tab == 'works' ? 'active' : '' ); ?>">
											<a href="#works" data-toggle="tab"><i class="fa fa-cubes fa-lg"></i> Works</a>
										</li>
										
										<li class="<?php echo ($curr_tab == 'variations' ? 'active' : '' ); ?>">
											<a href="#variations" onclick = "load_variation()" data-toggle="tab"><i class="fa fa-cube fa-lg"></i> Variations</a>
										</li>
										<?php if($this->session->userdata('is_admin') == 1 ): ?>
										<li class="<?php echo ($curr_tab == 'attachments' ? 'active' : '' ); ?>">
											<a href="#attachments" onclick = "dropbox_connect(<?php echo $project_id ?>)" data-toggle="tab"><i class="fa fa-paperclip fa-lg"></i> Attachments</a>
										</li>
										<?php endif; ?>
										<li class="<?php echo ($curr_tab == 'send_pdf' ? 'active' : '' ); ?>">
											<a href="#send_pdf" data-toggle="tab" onclick = "view_send_contractor()"><i class="fa fa-file-pdf-o fa-lg"></i> Send PDF</a>
										</li>	
										<li role="presentation" class="dropdown pull-right">
											<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
												<i class="fa fa-bar-chart-o fa-lg"></i> Reports <span class="caret"></span>
											</a>
											<ul class="dropdown-menu" role="menu">
												<li><a href="<?php echo base_url(); ?>works/proj_summary_w_cost/<?php echo $project_id ?>" target="_blank"><i class="fa fa-file-pdf-o"></i> Project Summary with Cost</a></li>
												<li><a href="<?php echo base_url(); ?>works/proj_summary_wo_cost/<?php echo $project_id ?>" target="_blank"><i class="fa fa-file-pdf-o"></i> Project Summary without Cost</a></li>
												<li><a href="<?php echo base_url(); ?>works/proj_joinery_summary_w_cost/<?php echo $project_id ?>" target="_blank"><i class="fa fa-file-pdf-o"></i> Joinery Summary with Cost</a></li>
												<li><a href="<?php echo base_url(); ?>works/proj_joinery_summary_wo_cost/<?php echo $project_id ?>" target="_blank"><i class="fa fa-file-pdf-o"></i> Joinery Summary without Cost</a></li>
												
												<li><a href="<?php echo base_url(); ?>works/variation_summary/<?php echo $project_id ?>" target="_blank"><i class="fa fa-file-pdf-o"></i> Variations Summary</a></li>
												<li><a href="<?php echo base_url(); ?>works/proj_details/<?php echo $project_id ?>" target="_blank"><i class="fa fa-file-pdf-o"></i> Project Details</a></li>
												<li><a href="" id = "work_cont_quote_req"><i class="fa fa-file-pdf-o"></i> Contractor Quote Request</a></li>
												<li><a href="" id = "work_cont_po"><i class="fa fa-file-pdf-o"></i> Contractor Purchase Order</a></li>
												<?php if($this->session->userdata('is_admin') == 1 ): ?>
												<li><a href="amplemod/print_pdf"><i class="fa fa-file-pdf-o"></i> Quotation and Contract</a></li>
												<?php endif; ?>
												<li><a href="#" onclick = "create_contract(<?php echo $project_id ?>)"><i class="fa fa-file-pdf-o"></i> Contract, Terms of Trade<br />&amp; Request for New Trade Form</a></li>
												
											</ul>
										</li>									
									</ul>

									</div>

								</div>


							</div>


							



							<div class="box-area">

								<div class="box-tabs m-bottom-15">
									<div class="tab-content">
										<div class="tab-pane fade in  clearfix <?php echo ($curr_tab == 'invoice' ? 'active' : '' ); ?>" id="invoices">
											<?php // if($this->session->userdata('is_admin') == 1 ): ?>
												<?php echo $this->projects->show_project_invoice($project_id); ?>
											<?php // endif; ?>
										</div>
										
										<div class="tab-pane fade in clearfix <?php echo ($curr_tab == 'project-details' ? 'active' : '' ); ?>" id="project-details">


											<div class="m-bottom-15 clearfix">
												

												<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">		


													<?php if(@$this->session->flashdata('quick_update')): ?>
														<div class="m-top-10">
															<div class="border-less-box alert alert-success fade in">
																<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
																<?php echo $this->session->flashdata('quick_update');?>
															</div>
														</div>
													<?php endif; ?>

													<form method="post" action="<?php echo base_url(); ?>projects/quick_update" class="form-horizontal">

														<div class="box ">
															<div class="box-head pad-5"><label><i class="fa fa-share fa-lg"></i> Quick Update Details</label></div>
															<div class="box-area pad-5 text-center pad-bottom-10">

																<input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
																<input type="hidden" name="is_double_time" value="<?php echo $is_double_time; ?>">
																<input type="hidden" name="site_labour_estimate" value="<?php echo $labour_hrs_estimate; ?>">

																<div class="box-area m-top-15 clearfix quick_edit_project">
																	<div class="col-sm-12 m-bottom-10 clearfix m-top-10">
																		<label for="project_name" class="col-sm-4 control-label m-top-5 text-left">Project Name</label>
																		<div class="col-sm-8  col-xs-12">
																			<input type="text" name="project_name" id="project_name" class="quick_input form-control text-right" tabindex="10" placeholder="Project Name" value="<?php echo $project_name; ?>">
																		</div>
																	</div>

																	<div class="col-sm-12 m-bottom-10 clearfix">
																		<label for="client_po_set" class="col-sm-4 control-label m-top-5 text-left">Client PO</label>
																		<div class="col-sm-8  col-xs-12">
																			<input type="text" name="client_po" id="client_po_set" class="quick_input form-control text-right" tabindex="10" placeholder="Client PO" value="<?php echo $client_po; ?>">
																		</div>
																	</div>

																	<div class="col-sm-12 m-bottom-10 clearfix">
																		<label for="client_po" class="col-sm-4 control-label m-top-5 text-left">Job Date</label>
																		<div class="col-sm-8  col-xs-12">

																		<?php if($this->invoice->if_has_invoice($project_id) == 0 || $this->invoice->if_completed_invoice($project_id) == 0): ?>
																			<div  title="Warning: You need to set up the Project Payments." class="tooltip-enabled">
																			<p class="job-date-set form-control text-right" id="job_date" ><?php if($job_date == ''){echo "DD/MM/YYYY";}else{echo $job_date;}  ?></p>
																			</div>
																		<?php   else: ?>
																			<?php if($job_date == '' ): ?>
																				<input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" title="Warning: Changing a value in the the Job date affects the project in the WIP section." class="tooltip-enabled job-date-set form-control datepicker text-right" name="job_date" value="<?php echo $job_date; ?>">
																			<?php elseif($this->session->userdata('is_admin') == 1 || $this->session->userdata('user_role_id') == 3 || ( $this->session->userdata('user_role_id') == 7 && $job_category == 'Maintenance' )  ): ?>
																				<input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" title="Warning: Changing a value in the the Job date affects the project in the WIP section." class="pad-10 tooltip-enabled job-date-set form-control datepicker text-right" id="job_date" name="job_date" value="<?php echo $job_date; ?>">
																			<?php else: ?>
																				<p title="Warning: You need to request to the Project Manager to change the Job Date" class="form-control tooltip-enabled job-date-set text-right" ><?php echo $job_date; ?></p>
																				<input type="hidden" id="job_date" name="job_date" value="<?php echo $job_date; ?>" class="hide hidden">
																			<?php endif; ?>
																		<?php  endif; ?>
																		</div>
																	</div>

																	<div class="col-sm-12 m-bottom-10 clearfix">
																		<label for="project_markup" class="col-sm-4 control-label m-top-5 text-left">Project Markup</label>
																		<div class="input-group ">
																			<span class="input-group-addon">(%)</span>
																				<p class="min_mark_up hidden"><?php echo $min_markup; ?></p>	


																			<?php if($job_date != ''): ?>
																				<p class="form-control text-right"><?php echo $markup; ?></p>
																				<input type="hidden" name="project_markup" id="project_markup" class="quick_input form-control text-right project_markup hide hidden" tabindex="12" placeholder="Markup %" value="<?php echo $markup; ?>" <?php echo ($job_date != '' ? 'style="z-index: -1;"' : ''); ?>>	
																			<?php else: ?>

																				<?php if($this->invoice->if_project_invoiced_full($project_id)): ?>
																					<p class="form-control text-right"><?php echo $markup; ?></p>
																					<input type="hidden" name="project_markup" id="project_markup" class="quick_input form-control text-right project_markup hide hidden" tabindex="12" placeholder="Markup %" value="<?php echo $markup; ?>" <?php echo ($job_date != '' ? 'style="z-index: -1;"' : ''); ?>>	

																				<?php else: ?>
																					<input type="text" name="project_markup" id="project_markup" class="quick_input form-control text-right project_markup" tabindex="12" placeholder="Markup %" value="<?php echo $markup; ?>" >
																					

																				<?php endif; ?>
																				
																			<?php endif; ?>
																			


																		</div>
																	</div>



																	<div class="col-sm-12 m-bottom-10 clearfix">
																		<label for="install_time_hrs" class="col-sm-4 control-label m-top-5 text-left">Site Hours</label>																		
																		<div class="input-group ">
																			<span class="input-group-addon">Hrs</span>


																			<?php if($job_date != ''): ?>
																				<p class="form-control text-right"><?php echo $install_time_hrs; ?></p>
																				<input type="hidden" placeholder="Site Hours" class="quick_input form-control text-right hide hidden" id="install_time_hrs"  name="install_time_hrs" value="<?php echo $install_time_hrs; ?>" <?php echo ($job_date != '' ? 'style="z-index: -1;"' : ''); ?>>
																			<?php else: ?>

																				<?php if($this->invoice->if_project_invoiced_full($project_id)): ?>
																					<p class="form-control text-right"><?php echo $install_time_hrs; ?></p>
																					<input type="hidden" placeholder="Site Hours" class="quick_input form-control text-right hide hidden" id="install_time_hrs"  name="install_time_hrs" value="<?php echo $install_time_hrs; ?>" <?php echo ($job_date != '' ? 'style="z-index: -1;"' : ''); ?>>
																				<?php else: ?>
																					<input type="text" placeholder="Site Hours" class="quick_input form-control text-right" id="install_time_hrs"  name="install_time_hrs" value="<?php echo $install_time_hrs; ?>" >
																				<?php endif; ?>

																			<?php endif; ?>
																			


																		</div>
																	</div> 

																	<div class="col-sm-12 m-bottom-10 clearfix green-estimate">
																		<label for="budget_estimate_total" class="col-sm-4 control-label m-top-5 text-left">Project Estimate</label>
																		<div class="input-group ">
																			<span class="input-group-addon">($)</span>
																			<input type="text" placeholder="Project Estimate" class="quick_input form-control text-right number_format" id="budget_estimate_total" name="budget_estimate_total" value="<?php echo number_format($budget_estimate_total); ?>">
																		</div>
																	</div>

																	<div class="col-sm-12 m-bottom-10 clearfix">
																	<label for="site_start" class="col-sm-4 control-label m-top-5 text-left">Site Start</label>
																		<div class="col-sm-8  col-xs-12">
																			<input tabindex="6" type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="quick_input form-control text-right datepicker" id="site_start" name="site_start" value="<?php echo $date_site_commencement; ?>">
																		</div>
																	</div>

																	<div class="col-sm-12 m-bottom-10 clearfix">
																	<label for="site_finish" class="col-sm-4 control-label m-top-5 text-left">Site Finish</label>
																		<div class="col-sm-8  col-xs-12">
																			<input tabindex="7" type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="quick_input form-control text-right datepicker" id="site_finish" name="site_finish" value="<?php echo $date_site_finish; ?>">
																		</div>
																	</div>

																</div>

															</div>
														</div>
														<?php if($this->session->userdata('projects') >= 2): ?>
															<button type="submit" tabindex="33" class="btn btn-success m-top-10"><i class="fa fa-floppy-o"></i> Save Changes</button>
														<?php endif; ?>

													</form>

													

													

												</div>
												<div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
													<?php if(@$this->session->flashdata('full_update')): ?>
														<div class="m-top-10">
															<div class="border-less-box alert alert-success fade in">
																<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
																<?php echo $this->session->flashdata('full_update');?>
															</div>
														</div>
													<?php endif; ?>
													<div class="box ">
														<div class="box-head pad-5"><label><i class="fa fa-info-circle fa-lg"></i> Details</label></div>
														<div class="box-area pad-5 pad-bottom-10">
															
															<div class="row">

																<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 m-bottom-10 clearfix">
																	<div class="pad-15 no-pad-t">

																		<h4><i class="fa fa-map-marker"></i> Site Address</h4>
																		<?php $shop_tenancy_numb = ($job_type != 'Shopping Center' ? '' : ''.$shopping_center_brand_name.': '.$shop_tenancy_number.'<br />' ); ?>
																		<?php $unit_level =  ($unit_level != '' ? 'Unit/Level:'.$unit_level.',' : '' ); ?>
																		<p><?php echo "$shop_tenancy_numb $unit_level $unit_number $street, $suburb, $state, $postcode"; ?><br /><br /></p>
																		<h4><i class="fa fa-map-marker"></i> Invoice Address</h4>
																		<?php $i_po_box =  ($i_po_box != '' ? 'PO BOX:'.$i_po_box.',' : '' ); ?>
																		<?php $i_unit_level =  ($i_unit_level != '' ? 'Unit/Level:'.$i_unit_level.',' : '' ); ?>
																		<p><?php echo "$i_po_box $i_unit_level $i_unit_number $i_street, $i_suburb, $i_state, $i_postcode"; ?></p>
																		<hr />
																		<h4><i class="fa fa-users"></i> Personel</h4>
																		<p class="clearfix">
																			<span class="text-left">Project Manager:</span>
																			<span class="text-right  pull-right"><strong><?php echo "$pm_user_first_name $pm_user_last_name"; ?></strong></span>
																		</p>
																		<p class="clearfix">
																			<span class="text-left">Project Admin:</span>
																			<span class="text-right pull-right"><strong><?php echo "$pa_user_first_name $pa_user_last_name"; ?></strong></span>
																		</p>
																		<p class="clearfix">
																			<span class="text-left">Estimator:</span>
																			<span class="text-right  pull-right"><strong>

																			<?php echo ($pe_user_first_name != '' ? $pe_user_first_name.' '.$pe_user_last_name : 'None' ); ?></strong></span>
																		</p>
																				
																	</div>									
																</div>

																<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 m-bottom-10 clearfix">

																	<div class="pad-15 no-pad-t border-left">
																		<h4><i class="fa fa-book"></i> General</h4>
																		<p class="clearfix">
																			<span class="text-left">Client:</span>
																			<span class="text-right  pull-right"><strong><?php echo $client_company_name; ?></strong></span>
																		</p>
																		<p class="clearfix">
																			<span class="text-left">Contact Person:</span>
																			<span class="text-right  pull-right"><strong><?php echo "$contact_person_fname $contact_person_lname"; ?></strong></span>
																		</p>
																		<p class="clearfix">
																			<span class="text-left">Client PO:</span>
																			<span class="text-right  pull-right"><strong><?php echo $client_po; ?></strong></span>
																		</p>
																		<p class="clearfix">
																			<span class="text-left">Job Date:</span>
																			<span class="text-right  pull-right"><strong><?php echo $job_date; ?></strong></span>
																		</p>
																		<p class="clearfix">
																			<span class="text-left">Job Type:</span>
																			<span class="text-right  pull-right"><strong><?php echo $job_type; ?></strong></span>
																		</p>
																		<p class="clearfix">
																			<span class="text-left">Category:</span>
																			<span class="text-right  pull-right"><strong><?php echo $job_category; ?></strong></span>
																		</p>
																		<p class="clearfix">
																			<span class="text-left">Site Start:</span>
																			<span class="text-right  pull-right"><strong><?php echo $date_site_commencement; ?></strong></span>
																		</p>
																		<p class="clearfix">
																			<span class="text-left">Site Finish:</span>
																			<span class="text-right  pull-right"><strong><?php echo $date_site_finish; ?></strong></span>
																		</p>



																	</div>																	
																</div>

																<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 m-bottom-10 clearfix">

																	<div class="pad-15 no-pad-t border-left">
																		<h4><i class="fa fa-bars"></i> Project Details</h4>
																		<p class="clearfix">
																			<span class="text-left">Is Double Time?</span>
																			<span class="text-right  pull-right"><strong><?php echo ($is_double_time == 1 ? 'Yes' : 'No'); ?></strong></span>
																		</p>
																		<p class="clearfix">
																			<span class="text-left">Is WIP?</span>
																			<span class="text-right  pull-right"><strong><?php echo ($is_wip == 1 ? 'Yes' : 'No'); ?></strong></span>
																		</p>
																		<p class="clearfix green-estimate">
																			<span class="text-left">Project Estimate:</span>
																			<span class="text-right  pull-right"><strong>$<?php echo number_format($budget_estimate_total); ?></strong></span>
																		</p>
																		<p class="clearfix">
																			<span class="text-left">Site Hours:</span>
																			<span class="text-right  pull-right"><strong><?php echo $install_time_hrs; ?> HRS</strong></span>
																		</p>
																		<p class="clearfix green-estimate">
																			<span class="text-left">Site Labour Estimate:</span>
																			<span class="text-right  pull-right"><strong><?php echo $labour_hrs_estimate; ?> HRS</strong></span>
																		</p>
																		<p class="clearfix">
																			<span class="text-left">Project Area:</span>
																			<span class="text-right  pull-right"><strong><?php echo $project_area; ?> SQM</strong></span>
																		</p>

																		<hr />
																		<p class="clearfix">
																			<span class="text-left">Focus:</span>
																			<span class="text-right  pull-right"><strong><?php echo $focus_company_name; ?></strong></span>
																		</p>
																		<p class="clearfix">
																			<span class="text-left">Project Created by:</span>
																			<span class="text-right  pull-right"><strong><?php echo $user_first_name; ?> <?php echo $user_last_name; ?></strong></span>
																		</p>
																	</div>																	
																</div>

																<div class="clearfix text-left col-xs-12 col-md-9">

																	<div class="row">
																		<div class="clearfix text-left col-xs-12">
																			<div class="pad-15 no-pad-t">
																				<hr />
																				<h4><i class="fa fa-book"></i> Project Notes</h4>
																				<?php echo $project_comments; ?>
																			</div>
																		</div>

																	</div>
																</div>

																<div class="clearfix col-xs-12 col-md-3">
																<?php if($this->session->userdata('projects') >= 2): ?>
																	<a href="<?php echo base_url(); ?>projects/update_project_details/<?php echo $project_id; ?>" type="submit" tabindex="33" class="btn btn-success m-top-20 m-right-15 pull-right"><i class="fa fa-pencil-square-o"></i> Update Full Details</a>
																<?php endif; ?>
																</div>
															</div>
																


														</div>
													</div>
												</div>
												</div>

										</div>

										<div class="tab-pane fade in clearfix <?php echo ($curr_tab == 'works' ? 'active' : '' ); ?>" id="works">
											<div class="m-bottom-15 clearfix m-top-10">
												<?php echo $this->projects->works_view(); ?>
											</div>
										</div>
										
										<div class="tab-pane fade in  clearfix <?php echo ($curr_tab == 'variations' ? 'active' : '' ); ?>" id="variations">
											<div class="m-bottom-15 clearfix m-top-10">
												<?php 
													if($variation == 'variation'){
														echo $this->variation->variation_works_view(); 
													}else{
														echo $this->variation->variations_view(); 
													}
												?>
											</div>
										</div>
										<?php if($this->session->userdata('is_admin') == 1 ): ?>
										
										<div class="tab-pane fade in  clearfix <?php echo ($curr_tab == 'attachments' ? 'active' : '' ); ?>" id="attachments">
											<div class="m-bottom-15 clearfix m-top-10">
												<?php echo $this->attachments->attachments_view(); ?>
											</div>
										</div>
										<?php endif; ?>
										<div class="tab-pane fade in  clearfix <?php echo ($curr_tab == 'send_pdf' ? 'active' : '' ); ?>" id="send_pdf">
											<div class="m-bottom-15 clearfix m-top-10">
												<?php echo $this->send_emails->send_pdf(); ?>
											</div>
										</div>
									</div>
								</div>				
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<!-- MODAL -->
<div class="modal fade" id="contract_notes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
	    <div class="modal-content">
	        <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		        <h4 class="modal-title">Contract Notes</h4>
	        </div>
	        <div class="modal-body" style = "height: 250px">
	        	<div class="col-sm-12 m-bottom-10 clearfix <?php if(form_error('officeNumber')){ echo 'has-error has-feedback';} ?>">
					<label for="company_prg" class="col-sm-4 control-label">Contract Date*</label>
					<div class="col-sm-8">														
						<div class="input-group <?php if(form_error('company_prg')){ echo 'has-error has-feedback';} ?>">
							<span class="input-group-addon"><i class="fa fa-calendar  fa-lg"></i></span>
							<input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control datepicker" id="contract_date" name="contract_date">
						</div>
					</div>
				</div>

	          	<div class="col-sm-12 m-bottom-10 clearfix">
					<label for="company_prg" class="col-sm-4 control-label">Plans, Elevations and Drawings:</label>
					<div class="col-sm-8">														
						<textarea class = "form-control input-sm" id = "plans_elv_draw" maxlength="43"></textarea>
					</div>
				</div>
				<div class="col-sm-12 m-bottom-10 clearfix">
					<label for="company_prg" class="col-sm-4 control-label">Schedule of Works Include in Quotation:</label>
					<div class="col-sm-8">														
						<textarea class = "form-control input-sm" id = "sched_work_quotation" maxlength="43"></textarea>
					</div>
				</div>
				<div class="col-sm-12 m-bottom-10 clearfix">
					<label for="company_prg" class="col-sm-4 control-label">Condition of Quotation and Contract</label>
					<div class="col-sm-8">
						<textarea class = "form-control input-sm" id = "condition_quote_contract" maxlength="43"></textarea>
					</div>
				</div>
	        </div>
	        <div class="modal-footer">
	        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	          	<button type="button" class="btn btn-primary" id = "create_contract"><i class="fa fa-file-pdf-o  fa-lg"></i> Create Contract</button>
	        </div>
	    </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div id="job_book_area" style="display:none">
	<strong class="pull-right" style="margin-top: 48px;font-size: 16px;"><?php echo $focus_company_name; ?></strong>
	<div class="header clearfix">
	<p class="pull-left">Client: <strong><?php echo $client_company_name; ?></strong> - <strong><?php echo $job_category; ?></strong><br />Project: <strong><?php echo $project_name; ?> <?php echo $client_po; ?></strong></p>
	<p class="pull-right"><strong>Job Book</strong><br />Project No. <strong><?php echo $project_id; ?></strong></p>
	</div>
	<hr />
	<div class="full clearfix mgn-10">
		<div class="one-fourth"><p>Contact: <strong><?php echo $contact_person_fname.' '.$contact_person_lname; ?></strong></p></div>
		<div class="one-fourth"><p><?php if($contact_person_phone_office != ''): echo 'Office No: <strong>'.$contact_person_phone_office.'</strong>'; endif; ?></p></div>
		<div class="one-fourth"><p><?php if($contact_person_phone_mobile != ''): echo 'Mobile No: <strong>'.$contact_person_phone_mobile.'</strong>'; endif; ?></p></div>
		<div class="one-fourth"><p><?php if($contact_person_phone_direct != ''): echo 'Direct No: <strong>'.$contact_person_phone_direct.'</strong>'; endif; ?></p></div>
	</div>

	<fieldset class="pad-10 border-2">
		<legend class="pad-l-10 pad-r-10"><strong>Client / Company Address</strong></legend>
		<div class="full clearfix">
			<div class="one-third">
				<p class=""><strong><?php echo $client_company_name; ?></strong></p>
				<p><?php echo $query_client_address_unit_number.' '.$query_client_address_unit_level.' '.$query_client_address_street; ?></p>
				<p class=""><?php echo $query_client_address_suburb.' '.$query_client_address_state.' '.$query_client_address_postcode; ?></p>
			</div>
			<div class="one-third">
				<p class=""><?php if($company_contact_details_office_number != ''): echo 'Office No: <strong>'.$company_contact_details_area_code.' '.$company_contact_details_office_number.'</strong>'; endif; ?></p>
				<p class=""><?php if($company_contact_details_direct_number != ''): echo 'Direct No: <strong>'.$company_contact_details_area_code.' '.$company_contact_details_direct_number.'</strong>'; endif; ?></p>
				<p class=""><?php if($company_contact_details_mobile_number != ''): echo 'Mobile No: <strong>'.$company_contact_details_mobile_number.'</strong>'; endif; ?></p>
				<p class=""><?php if($company_contact_details_after_hours != ''): echo 'After Hours: <strong>'.$company_contact_details_area_code.' '.$company_contact_details_after_hours.'</strong>'; endif; ?></p>
			</div>
			<div class="one-third">
				<p class=""><?php if($company_contact_details_general_email != ''): echo 'General Email: <strong>'.$company_contact_details_general_email.'</strong>'; endif; ?></p>
				<p class=""><?php if($company_contact_details_direct != ''): echo 'Direct Email: <strong>'.$company_contact_details_direct.'</strong>'; endif; ?></p>
				<p class=""><?php if($company_contact_details_accounts != ''): echo 'Accounts Email: <strong>'.$company_contact_details_accounts.'</strong>'; endif; ?></p>
				<p class=""><?php if($company_contact_details_maintenance != ''): echo 'Maintenance Email: <strong>'.$company_contact_details_maintenance.'</strong>'; endif; ?></p>
			</div>
		</div>
	</fieldset>

	<fieldset class="pad-10 border-2 mgn-top-10">
		<legend class="pad-l-10 pad-r-10"><strong>Address</strong></legend>
		<div class="full clearfix">
			<div class="one-half">
				<div class="border-right-2 pad-r-10">
					<p class=""><strong>Site</strong></p>
					<p><?php $shop_tenancy_numb = ($job_type != 'Shopping Center' ? '' : ''.$shopping_center_brand_name.': '.$shop_tenancy_number.'<br />' ); ?>
					<p><?php echo "$shop_tenancy_numb $unit_level $unit_number $street, $suburb, $state, $postcode"; ?></p>			
				</div>
			</div>
			<div class="one-half">
				<div class="pad-l-10">
					<p class=""><strong>Invoice</strong></p>
					<p><?php echo "$i_po_box $i_unit_level $i_unit_number $i_street, $i_suburb, $i_state,  $i_postcode"; ?></p>
				</div>
			</div>
		</div>
	</fieldset>

	<fieldset class="pad-10 border-2 mgn-top-10">
		<legend class="pad-l-10 pad-r-10"><strong>Project Totals</strong></legend>
		<div class="full clearfix">
			<div class="one-half">
				<div class="pad-r-10">
					<p class="">Quotes Total : <strong class="pull-right">$<?php echo number_format($final_total_quoted,2); ?></strong></p>
					<p class=""><?php echo $admin_gst_rate; ?>% GST : <strong class="pull-right">$<?php echo number_format($final_total_quoted*($admin_gst_rate/100),2); ?></strong></p>
					<p class="">Total (inc GST) : <strong class="pull-right">$<?php echo number_format($final_total_quoted+($final_total_quoted*($admin_gst_rate/100)),2); ?></strong></p>
				</div>
			</div>
			<div class="one-half">
				<div class="pad-l-10">
					<p class="">Variations Total : <strong class="pull-right">$<?php echo number_format($variation_total,2); ?></strong></p>
					<p class=""><?php echo $admin_gst_rate; ?>% GST : <strong class="pull-right">$<?php echo number_format($variation_total*($admin_gst_rate/100),2); ?></strong></p>
					<p class="">Total (inc GST) : <strong class="pull-right">$<?php echo number_format($variation_total+($variation_total*($admin_gst_rate/100)),2); ?></strong></p>				
				</div>
			</div>
		</div>
	</fieldset>	

	<div class="full clearfix">
		<div class="one-half">
			<div class="pad-r-10">
				<fieldset class="pad-10 border-2 mgn-top-10">
					<legend class="pad-l-10 pad-r-10"><strong>Details</strong></legend>
					<div class="full clearfix">
						<p class="">Representative : <strong class="pull-right"><?php echo "$pm_user_first_name $pm_user_last_name"; ?></strong></p>
						<p class="">Job Date : <strong class="pull-right"><?php echo $job_date; ?></strong></p>
						<p class="">Start Date : <strong class="pull-right"><?php echo $date_site_commencement; ?></strong></p>
						<p class="">Expected Finish Date : <strong class="pull-right"><?php echo $date_site_finish; ?></strong></p>
						<p class="">PO Number : <strong class="pull-right" class="pull-right"><?php echo $client_po; ?></strong></p>
					</div>
				</fieldset>
				<p>&nbsp;</p>
				<p><strong>Notes</strong></p>
				<div class="print_job_book_notes"></div>
			</div>
		</div>

		<div class="one-half">
			<div class="pad-l-10">
				<fieldset class="pad-10 border-2 mgn-top-10">
					<legend class="pad-l-10 pad-r-10"><strong>Invoices</strong></legend>
					<div class="full clearfix invoices_list_item">
						<?php $this->projects->list_invoiced_items($project_id,$final_total_quoted,$variation_total); ?>
					</div>
				</fieldset>
			</div>

		</div>		
	</div>

	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<hr />
	<p><?php echo mdate($datestring, $time); ?></p>


</div>



<?php $this->load->view('assets/logout-modal'); ?>


