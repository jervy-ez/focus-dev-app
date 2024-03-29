<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>
<!-- title bar -->
<div class="container-fluid head-control">
	<div class="container-fluid">
		<div class="row">

			<div class="col-md-6 col-sm-4 col-xs-12 pull-left">
				<header class="page-header">
					<h3><?php $datestring = "%l, %F %d, %Y"; $time = time(); //use time() for timestamp  ?>
						Project Details<br><small><?php echo mdate($datestring, $time); #echo date("l, F d, Y"); ?></small>
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
						<a href="<?php echo current_url(); ?>#quotation" class="btn-small">Quotation</a>
					</li>
					<li>
						<a href="<?php echo  current_url(); ?>#invoice" class="btn-small">Invoicing</a>
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
			<form class="form-horizontal project-form" role="form" method="post" action="">
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
								
								<?php if(@$success): ?>
								<div class="pad-10 no-pad-t">
									<div class="border-less-box alert alert-success fade in">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										<h4>Horray!</h4>
										<?php echo $success;?>
									</div>
								</div>
								<?php endif; ?>
								
								<?php if(@$this->session->flashdata('update_company_id')): ?>
								<div class="pad-10 no-pad-t">
									<div class="border-less-box alert alert-success fade in">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										<h4>Cheers!</h4>
										<?php echo $this->session->flashdata('update_message');?>
									</div>
								</div>
								<?php endif; ?>	
					
								
									<div class="box-head pad-10 clearfix">	
										<input  type="reset" class="btn btn-warning pull-right edit-project" value="Edit project" />			
										<label>Project Details</label><span> (<a href="#" data-placement="right" class="popover-test" title="" data-content="Hello there mate! Welcome to the clients screen." data-original-title="Welcome">?</a>)</span>
										<p>Fields having * is requred.</p>	
										<p><a href="#" class="tooltip-test" title="Tooltip">This link</a> and <a href="#" class="tooltip-test" title="Tooltip">that link</a> should have tooltips on hover.</p>						
									</div>
									
									<div class="box-area pad-10 clearfix">											
										
										<div class="form-group clearfix pad-5 no-pad-b">
	        								<div class="col-sm-12">
	         									<div class="input-group <?php if(form_error('project_name')){ echo 'has-error has-feedback';} ?>">
													<span class="input-group-addon"><i class="fa fa-briefcase  fa-lg"></i></span>
													<input type="text" class="form-control" placeholder="Project Name*" name="project_name" id="project_name" readonly="readonly" value="<?php echo $this->input->post('project_name'); if(isset($project_name)){ echo $project_name;}?>">
												</div>
	        								</div>
	      								</div>	      								
	      								
	      								<div class="box m-bottom-15 clearfix">
											<div class="box-head pad-5 m-bottom-5">
												<label><i class="fa fa-book fa-lg"></i> General</label>
											</div>
											
											<div class="box-area pad-5 clearfix">																																
												<div class="col-sm-6 m-bottom-10 clearfix" >
													<label for="job_date" class="col-sm-3 control-label">Job Date (<strong style="cursor: pointer;" class="popover_form_job_date_tri">?</strong>)</label>
													<div class="col-sm-9">
														<input type="date" class="form-control" readonly="readonly" id="job_date" placeholder="Job Date" name="job_date" value="<?php if($job_date!=''){echo $job_date;}elseif($this->input->post('job_date')!=''){	echo $this->input->post('job_date');}else{echo date("Y-m-d");} ?>">
														<a class="popover_form_job_date" data-placement="bottom" title="Job Date" data-content="Hi, place a value for the Job Date if the project is accepted."></a>
													</div>
												</div>
												
												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="client_po" class="col-sm-3 control-label">Client PO</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="client_po" readonly="readonly" placeholder="Client PO" name="client_po" value="<?php  if(isset($client_po)){ echo $client_po;}else{echo $this->input->post('client_po'); }?>">
													</div>
												</div>
												
												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('site_start')){ echo 'has-error has-feedback';} ?>">
													<label for="site_start" class="col-sm-3 control-label">Site Start*</label>
													<div class="col-sm-9">
														<input type="date" class="form-control" readonly="readonly" id="site_start" placeholder="Site Start" name="site_start" value="<?php echo $this->input->post('site_start');  if(isset($date_site_commencement)){ echo $date_site_commencement;}?>">
													</div>
												</div>
													
													<div class="col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('site_finish')){ echo 'has-error';} ?>">
														<label for="site_finish" class="col-sm-3 control-label">Site Finish*</label>
														<div class="col-sm-9 col-xs-12">
															<input type="date" class="form-control" readonly="readonly" id="site_finish" placeholder="Site Finish" name="site_finish" value="<?php echo $this->input->post('site_finish'); if(isset($date_site_finish)){ echo $date_site_finish;}?>">
														</div>
													</div>
													
													<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('job_category')){ echo 'has-error has-feedback';} ?>">
														<label for="job_category" class="col-sm-3 control-label">Job Category*</label>													
														<div class="col-sm-9  col-xs-12">
															<select class="form-control postcode-option" readonly="readonly" id="job_category" name="job_category">
																<?php if(isset($job_category)): ?>
																<option value="<?php echo $job_category; ?>"><?php echo $job_category; ?></option>
																<?php elseif($this->input->post('job_category')!=''): ?>
																<option value="<?php echo $this->input->post('job_category'); ?>"><?php echo $this->input->post('job_category'); ?></option>
																<?php else: ?>
																<option value="">Choose a Job Category...</option>
																<option value="Full Fitout">Full Fitout</option>
																<option value="Maintenance">Maintenance</option>
																<option value="Under $20K">Under $20K</option>
																<option value="Defects">Defects</option>
																<option value="Variations">Variations</option>
																<option value="Office Projects">Office Projects</option>
																<option value="Others">Others</option>
																<?php endif; ?>
															</select>
														</div>
													</div>
													
													<div class="col-sm-6 m-bottom-10 clearfix">
														<div class="input-group col-md-10 pull-right">
															<input type="text" id="disabledInput" value="Is WIP?" class="form-control disabled" disabled="">
															<span class="input-group-addon"> <input type="checkbox" class="is_wip" name="is_wip"> Yes</span>
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
														<input id="project_date" readonly="readonly" class="project_date form-control" name="project_date" readonly="readonly" type="date" value="<?php if(isset($project_date)){ echo $project_date;}else{echo date("Y-m-d");}?>">
													</div>	
												</div>											
			      								<div class="col-sm-6 m-bottom-5 clearfix <?php if(form_error('acn')){ echo 'has-error has-feedback';} ?>">
			      									<label for="quote_complated" class="col-sm-4 control-label">Quote Completed</label>
													<div class="col-sm-8">
														<input type="date" readonly="readonly" class="form-control" id="quote_complated" placeholder="quote_completed_date" name="quote_completed_date" value="<?php echo $this->input->post('quote_completed_date');?>">
													</div>												
												</div>											
											</div>
										</div>
	      								      								
	      								<div class="box m-bottom-15 clearfix">
											<div class="box-head pad-5 m-bottom-5">
												<label><i class="fa fa-globe fa-lg"></i> Project Address</label>
											</div>
											
											<div class="box-area pad-5 clearfix">																						
												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="unit_level" class="col-sm-3 control-label">Unit/Level</label>
													<div class="col-sm-9">
														<input type="text" readonly="readonly" class="form-control" id="unit_level" placeholder="Unit/Level" name="unit_level" value="<?php if(isset($unit_level)){ echo $unit_level; } else { echo $this->input->post('unit_level'); }?>">
													</div>
												</div>
												
												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="number" class="col-sm-3 control-label">Number</label>
													<div class="col-sm-9">
														<input type="text" readonly="readonly" class="form-control" id="number" placeholder="Number" name="unit_number" value="<?php if(isset($unit_number)){ echo $unit_number; } else { echo $this->input->post('unit_number'); } ?>">
													</div>
												</div>
												
												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('street')){ echo 'has-error has-feedback';} ?>">
													<label for="street" class="col-sm-3 control-label">Street*</label>
													<div class="col-sm-9">
														<input type="text" readonly="readonly" class="form-control" id="street" placeholder="Street" name="street" value="<?php if(isset($street)){ echo $street; } else { echo $this->input->post('street'); }?>">
													</div>
												</div>
													
													<div class="col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('suburb')){ echo 'has-error';} ?>">
														<label for="suburb" class="col-sm-3 control-label">Suburb*</label>
														<div class="col-sm-9 col-xs-12">
															<select class="form-control suburb-option-a" disabled="disabled" id="suburb" name="suburb">
																<?php if($this->input->post('suburb')): ?>
																<option value="<?php echo $this->input->post('suburb'); ?>"><?php echo $this->company->set_suburb($this->input->post('suburb')); ?></option>
																<?php else: ?>
																<option value="<?php echo $suburb.'|'.$state_name.'|'.$area_code; ?>"><?php echo ucwords(strtolower($suburb)); ?></option>
																<?php endif; ?>
																<option value="">Choose a Suburb...</option>
																<option value="add">Add New</option>
																<?php $this->company->suburb_list('dropdown'); ?>												
															</select>
														</div>
													</div>
													
													<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('state')){ echo 'has-error has-feedback';} ?>">
														<label for="state_a" class="col-sm-3 control-label">State*</label>
														<div class="col-sm-9">
															<input type="text" class="form-control disabled" readonly="readonly" id="state_a" placeholder="State" name="state" value="<?php if(isset($state_name)){ echo $state_name; }else{ echo $this->input->post('state'); } ?>">
														</div>
													</div>
													
													<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('postcode_a')){ echo 'has-error has-feedback';} ?>">
														<label for="postcode_a" class="col-sm-3 control-label">Postcode*</label> <?php //echo $this->input->post('postcode_a'); ?>													
														<div class="col-sm-9  col-xs-12">
															<select class="form-control postcode-option"  disabled="disabled" id="postcode_a" name="postcode_a">
																<?php if($postcode!=''): ?>
																<option value="<?php echo $postcode; ?>"><?php echo $postcode; ?></option>
																<?php endif; ?>
																<?php if($this->input->post('postcode_a')!=''): ?>
																<option value="<?php echo $this->input->post('postcode_a'); ?>"><?php echo $this->input->post('postcode_a'); ?></option>
																<?php else: ?>
																<option value="">Choose a Postcode...</option>
																<?php endif; ?>
															</select>
														</div>
													</div>
											</div>
										</div>
										
										      								
	      								
										
										<div class="box m-bottom-15 clearfix">
											<div class="box-head pad-5 m-bottom-5">
												<label><i class="fa fa-users fa-lg"></i> Personel</label>
											</div>
											
											<div class="box-area pad-5 clearfix">
												<div class="col-sm-4 m-bottom-5 clearfix <?php if(form_error('project_manager')){ echo 'has-error has-feedback';} ?>">
													<div class="col-sm-12">
														<label for="project_manager" class="control-label">Project Manager*</label><br />
														<select name="project_manager" readonly="readonly" class="form-control presonel_add" id="project_manager" style="width: 100%;">
															<?php if($this->input->post('project_manager')!=''): ?>
															<option selected="selected" value="<?php echo $this->input->post('project_manager'); ?>"><?php echo $this->input->post('project_manager'); ?></option>
															<?php endif; ?>
															<option value=''>Select Project Manager</option>
															<option value="add">Add New</option>
															<option value="Alton Vallo">Alton Vallo</option>
															<option value="Ami Armbruster">Ami Armbruster</option>
															<option value="Amina Joo">Amina Joo</option>
															<option value="Elijah Alberti">Elijah Alberti</option>
															<option value="Elise Molter">Elise Molter</option>
															<option value="Ellis Duppstadt">Ellis Duppstadt</option>
															<option value="Emmitt Riojas">Emmitt Riojas</option>
															<option value="Zachary Hardrick">Zachary Hardrick</option>
														</select>												
													</div>
												</div>
	   								
			      								<div class="col-sm-4 m-bottom-5 clearfix <?php if(form_error('project_admin')){ echo 'has-error has-feedback';} ?>">											
													<div class="col-sm-12">
														<label for="project_admin" class="control-label">Project Admin*</label>
														
														<select name="project_admin" readonly="readonly" class="form-control presonel_add" id="project_admin" style="width: 100%;">
															<?php if($this->input->post('project_admin')!=''): ?>
															<option selected="selected" value="<?php echo $this->input->post('project_admin'); ?>"><?php echo $this->input->post('project_admin'); ?></option>
															<?php endif; ?>
															<option value=''>Select Project Admin</option>
															<option value="add">Add New</option>
															<option value="Alton Vallo">Alton Vallo</option>
															<option value="Ami Armbruster">Ami Armbruster</option>
															<option value="Amina Joo">Amina Joo</option>
															<option value="Elijah Alberti">Elijah Alberti</option>
															<option value="Elise Molter">Elise Molter</option>
															<option value="Ellis Duppstadt">Ellis Duppstadt</option>
															<option value="Emmitt Riojas">Emmitt Riojas</option>
															<option value="Zachary Hardrick">Zachary Hardrick</option>
														</select>
														
													</div>
												</div>
												
			      								<div class="col-sm-4 m-bottom-5 clearfix <?php if(form_error('estimator')){ echo 'has-error has-feedback';} ?>">						
													<div class="col-sm-12">
														<label for="estimator" class="control-label">Estimator*</label>													
														<select name="estimator" readonly="readonly" class="form-control presonel_add" id="estimator" style="width: 100%;">
															<?php if($this->input->post('estimator')!=''): ?>
															<option selected="selected" value="<?php echo $this->input->post('estimator'); ?>"><?php echo $this->input->post('estimator'); ?></option>
															<?php endif; ?>
															<option value=''>Select Estimator</option>
															<option value="add">Add New</option>
															<option value="Alton Vallo">Alton Vallo</option>
															<option value="Ami Armbruster">Ami Armbruster</option>
															<option value="Amina Joo">Amina Joo</option>
															<option value="Elijah Alberti">Elijah Alberti</option>
															<option value="Elise Molter">Elise Molter</option>
															<option value="Ellis Duppstadt">Ellis Duppstadt</option>
															<option value="Emmitt Riojas">Emmitt Riojas</option>
															<option value="Zachary Hardrick">Zachary Hardrick</option>
														</select>
													</div>
												</div>
												
											</div>
										</div>
	 									
										<div class="box">
											<div class="box-head pad-5 m-bottom-5">
												<label><i class="fa fa-phone-square fa-lg"></i> Contact</label>
											</div>
											
											<div class="box-area pad-5 clearfix">
													
												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('areacode')){ echo 'has-error has-feedback';} ?>">
													<label for="areacode" class="col-sm-3 control-label">Areacode*</label>
													<div class="col-sm-9">
														<input type="text" class="form-control disabled" readonly="readonly" id="areacode" name="areacode" placeholder="Areacode"  value="<?php if($area_code!=''){echo $area_code;}else{ echo $this->input->post('areacode'); } ?>">
													</div>
												</div>
												
												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('officeNumber')){ echo 'has-error has-feedback';} ?>">
													<label for="officeNumber" class="col-sm-3 control-label">Phone*</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" readonly="readonly" id="officeNumber" name="officeNumber" placeholder="Phone Number"  value="<?php if($office_number !=''){echo $office_number; }else{ echo $this->input->post('officeNumber'); } ?>">
													</div>
												</div>
												
												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('generalEmail')){ echo 'has-error has-feedback';} ?>">
													<label for="generalEmail" class="col-sm-3 control-label">Email</label>
													<div class="col-sm-9">
														<input type="email" class="form-control" readonly="readonly" id="generalEmail" name="generalEmail" placeholder="Email" value="<?php if($general_email !=''){echo $general_email; }else{ echo $this->input->post('generalEmail');} ?>" />
													</div>
												</div>
												
												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('contact_person')){ echo 'has-error has-feedback';} ?>">
													<label for="contact_person" class="col-md-4 col-sm-5 control-label">Contact Person*</label>
													<div class="col-md-8 col-sm-7">
														<select name="contact_person" readonly="readonly" class="form-control" id="contactperson" style="width: 100%;">
															<option value=''>Select Contact Person</option>
															<?php if($this->input->post('contact_person')!=''): ?>
															<?php $con_name = explode('|',$this->input->post('contact_person')); ?>
															<option selected="selected" value="<?php echo $this->input->post('contactperson'); ?>"><?php echo $con_name[0].' '.$con_name[1]; ?></option>
															<?php else: ?>
															<option selected="selected" value="<?php echo $contact_first_name.'|'.$contact_last_name; ?>"><?php echo $contact_first_name.' '.$contact_last_name; ?></option>	
															<?php endif; ?>	
															<option value="add">Add New</option>
															<?php $this->company->contact_person_list(); ?>
														</select>
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
														<textarea style="resize: vertical;" class="form-control" readonly="readonly" id="project_notes" rows="3" name="comments"><?php if($notes!=''){echo $notes;}else{ echo $this->input->post('comments'); } ?></textarea>														
													</div>
												</div>
											</div>
										</div>
										
									    <div class="m-top-15 clearfix">
									    	<div>
									        	<button type="submit" class="btn btn-success save-project hidden">Save</button>
									        </div>
									    </div>
									</div>
								
							</div>
						</div>
						
						<div class="col-md-3">
							
							<div class="box">
								<div class="box-head pad-5 border-0">
									<label><i class="fa fa-info-circle fa-lg"></i> Project Number : <em><?php echo $project_number; ?></em></label>
								</div>							
							</div>

							<div class="box">
								<div class="box-head pad-5">
									<label><i class="fa fa-info-circle fa-lg"></i> Others</label>
								</div>
								<div class="box-area pad-5">
									<div class="clearfix m-top-5 m-bottom-5">
										<div class="col-md-12 col-lg-12  col-sm-3">
											<div class="input-group <?php if(form_error('company_prg')){ echo 'has-error has-feedback';} ?>">
												<span class="input-group-addon"><i class="fa fa-briefcase  fa-lg"></i></span>
													<select name="company_prg" readonly="readonly" class="form-control" id="company_prg" style="width: 100%;">
													<option value=''>Select Client Name*</option>
													<?php if($this->input->post('company_prg')!=''): ?>
													<option selected="" value="<?php echo $this->input->post('company_prg'); ?>"><?php echo $this->input->post('company_prg'); ?></option>
													<?php else: ?>
													<option selected="selected" value="<?php echo $company_name.'|'.$client_company_id; ?>"><?php echo $company_name; ?></option>
													<?php endif; ?>			
													<?php $this->company->company_list('dropdown'); ?>
												</select>
											</div>
										</div>
									</div>
									<div class="clearfix m-top-5 m-bottom-5">
										<label for="project_area" class="col-sm-6 control-label" style="font-weight: normal;">Project Area (sqm)</label>
										<div class="col-sm-6">
											<input type="text" name="project_area" readonly="readonly" id="project_area" class="form-control" placeholder="Area" value="<?php if($project_area!=''){echo $project_area;}else{echo $this->input->post('project_area'); } ?>"/>
										</div>
									</div>
								</div>
							</div>
							
							<div class="box">
								<div class="box-head pad-5">
									<label><i class="fa fa-info-circle fa-lg"></i> Budget Estimate</label>
								</div>
								<div class="clearfix m-top-10 m-bottom-10">
									<label for="project_total" class="col-sm-6 control-label" style="font-weight: normal;">Project Total ($)</label>
									<div class="col-sm-6">													
										<input type="text" name="project_total" readonly="readonly" id="project_total" class="form-control" placeholder="Total" value="<?php if($this->input->post('project_total')!=''){echo $this->input->post('project_total');}else{echo $budget_estimate_total; }?>"/>
									</div>
								</div>
								<div class="clearfix m-top-10 m-bottom-10">
									<label for="install_hrs" class="col-sm-6 control-label" style="font-weight: normal;">Install Time (Hrs)</label>
									<div class="col-sm-6">													
										<input type="text" name="install_hrs" readonly="readonly" id="install_hrs" class="form-control" placeholder="Install Hrs" value="<?php echo $this->input->post('install_hrs'); ?>"/>
									</div>
								</div>
								<div class="clearfix m-top-10 m-bottom-5">
									<label for="project_markup" class="col-sm-6 control-label" style="font-weight: normal;">Project Markup (%)</label>
									<div class="col-sm-6">													
										<input type="text" name="project_markup" readonly="readonly" id="project_markup" class="form-control" placeholder="Markup %" value="<?php echo $this->input->post('project_markup'); ?>"/>
									</div>
								</div>
								<p></p>
							</div>
							
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