<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('users'); ?>
<?php $user_id_page = $this->uri->segment(3); ?>
<?php
	  $is_admin = $this->session->userdata('is_admin');
	  $leave_alloc = $this->users->leave_alloc($user_id_page);	
	  $get_sched_of_work = $this->users->get_sched($user_id_page);
	  $leave_requests = $this->session->userdata('leave_requests');
	  if (!empty($get_sched_of_work)){
		$get_sched_val = explode(',', $get_sched_of_work->sched_of_work);
	  }
?>

<script src="<?php echo base_url(); ?>js/vue.js"></script>
<script src="<?php echo base_url(); ?>js/moment.min.js"></script>
<script src="<?php echo base_url(); ?>js/vue-select.js"></script>

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
					<?php if($this->session->userdata('is_admin') == 1 ): ?>
						<li>
							<a href="<?php echo base_url(); ?>admin/company" class="btn-small">Company</a>
						</li>
					<?php endif; ?>
					<li>
						<a href="<?php echo base_url(); ?>users" class="btn-small">Users</a>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>users/leave_details/<?php echo $this->session->userdata('user_id'); ?>">My Leave Requests</a>
					</li>
					<?php if ($leave_requests == 1): ?>
						<li>
							<a href="<?php echo base_url(); ?>users/leave_approvals/<?php echo $this->session->userdata('user_id'); ?>">Leave Approvals</a>
						</li>
					<?php endif; ?>
					<!-- <li>
						<a href="" class="btn-small"><i class="fa fa-magic"></i> Tour</a>
					</li> -->
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

			<input class="hide" type="hidden" id="user_id_page" value="<?php echo $user_id_page; ?>">



				<div class="row">
					
					<div class="col-md-9">
						<div class="left-section-box">
							<div class="box-head pad-10 clearfix">
							<?php if($this->session->userdata('is_admin') ==  1): ?>
								<a href="../delete_user/<?php echo $user_id_page; ?>" class="btn btn-danger submit_form pull-right" id="focus_add_company" name="save_bttn" >Delete User</a>
							<?php endif; ?>

							<input type="hidden" name="user_id_access" value="<?php echo $this->session->userdata('user_id'); ?>">

							<?php 
								if ($leave_requests == 1):
									if ($this->session->userdata('user_id') != $user_id_page):
							?>	
										<a target="_blank" href="<?php echo base_url(); ?>users/leave_details/<?php echo $user_id_page; ?>" class="btn btn-info submit_form pull-right" id="apply_other_user" name="apply_other_user" style="margin-right: 5px;">View Leave Requests of this User</a>

										<a class="btn btn-warning submit_form pull-right" id="apply_other_user" name="apply_other_user" style="margin-right: 5px; cursor: pointer;" onclick="apply_for_leave(<?php echo $user_id_page; ?>);">Apply Leave for this User</a>
							<?php 
									endif; 
								endif;

							?>
							
							
								<label><?php echo $screen; ?></label><span> (<a href="#" data-placement="right" class="popover-test" title="" data-content="Welcome, this is  a profile page." data-original-title="Welcome">?</a>)</span>

															
							</div>
							<div class="box-area clearfix">


								<?php if(@$this->session->flashdata('new_pass_msg')): ?>
									<div class="no-pad-t m-bottom-10 pad-left-10">
										<div class="border-less-box alert alert-success fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>The new password is been set!</h4>
											<?php echo $this->session->flashdata('new_pass_msg');?>
										</div>
									</div>
								<?php endif; ?>


								<?php if(@$this->session->flashdata('account_update_msg')): ?>
									<div class="no-pad-t m-bottom-10 pad-left-10">
										<div class="border-less-box alert alert-success fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>Cheers!</h4>
											<?php echo $this->session->flashdata('account_update_msg');?>
										</div>
									</div>
								<?php endif; ?>


							<?php if(@$error): ?>
								<div class="pad-10 no-pad-t">
									<div class="border-less-box alert alert-danger fade in">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										<h4>Oh snap! You got an error!</h4>
										<?php echo $error;?>
									</div>
								</div>
							<?php endif; ?>




							<?php if(@$this->session->flashdata('user_access')): ?>
								<div class="m-15">
								<div class="border-less-box alert alert-success fade in">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										<h4>Cheers!</h4>
										<?php echo $this->session->flashdata('user_access');?>
									</div>
								</div>
							<?php endif; ?>

							<?php if(@$this->session->flashdata('total_leave_error')): ?>
								<div class="pad-10 no-pad-t">
									<div class="border-less-box alert alert-danger fade in">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										<h4>Oh snap! You got an error!</h4>
										<?php //echo $error;?>

										<?php echo $this->session->flashdata('total_leave_error');?>
									</div>
								</div>
							<?php endif; ?>

							<?php if(@$this->session->flashdata('total_leave')): ?>
								<div class="m-15">
								<div class="border-less-box alert alert-success fade in">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										<h4>Cheers!</h4>
										<?php echo $this->session->flashdata('total_leave');?>
									</div>
								</div>
							<?php endif; ?>
								
									<div class="row clearfix pad-left-15  pad-right-15 pad-bottom-10">

									

										
										<?php foreach($user as $key => $user): ?>


												<?php $user_id = $user->user_id; ?>
												<?php $is_user_admin = $user->if_admin; ?>

												<?php if( $this->session->userdata('users') > 1 || $this->session->userdata('user_id') == $user_id  || $this->session->userdata('is_admin') ==  1  ): ?>
													<form class="form-horizontal clearfix form" role="form" method="post" action="" accept-charset="utf-8" enctype="multipart/form-data">
												<?php endif; ?>

												<div class="col-md-3 col-sm-2 col-xs-12">

										<div class="box bank_account m-top-10 clearfix" >

											<div class="col-xs-12 m-bottom-10 clearfix ">
												<div class="primary_photo_wraper pad-top-5" >
													<?php if($user->user_profile_photo!= ''):  ?>
														<img src="<?php echo base_url(); ?>uploads/users/<?php echo $user->user_profile_photo; ?>" class="user_avatar img-responsive">	
													<?php else: ?>
														<img src="<?php echo base_url(); ?>uploads/users/no-avatar.jpg" class="user_avatar img-responsive">
													<?php endif; ?>												
												</div>	
												<script type="text/javascript">$('.primary_photo_wraper').css('height', $('img.user_avatar').innerWidth() );  </script>											
											</div>


											<?php if( $this->session->userdata('users') > 1 || $this->session->userdata('user_id') == $user_id  || $this->session->userdata('is_admin') ==  1  ): ?>
												<div class="col-xs-12 m-bottom-10 clearfix  <?php if(@$upload_error){ echo 'has-error has-feedback';} ?>  ">
													<label for="profile_photo" class="col-sm-12 control-label text-center center">Profile Photo</label>
													<div class="col-sm-12">
														<input type="file" id="profile_photo" name="profile_photo" class="form-control profile_photo"  />
													</div>
												</div>

											<?php endif; ?>

										</div>
										<div class="clearfix"></div>


										<div class="box bank_account m-top-10" >

											<div class="box-head pad-5 m-bottom-5">
												<label><i class="fa fa-user fa-lg"></i> Personal Info</label>
											</div>

											<div class="box-area pad-5 clearfix">

												<?php if( $this->session->userdata('users') > 1 || $this->session->userdata('user_id') == $user_id || $this->session->userdata('is_admin') ==  1  ): ?>



													<div class="col-xs-12 m-bottom-10 clearfix <?php if(form_error('first_name')){ echo 'has-error has-feedback';} ?>">
														<input type="text" class="form-control" id="first_name" name="first_name"  tabindex="1" placeholder="First Name*"  value="<?php echo $user->user_first_name; ?>">
													</div>

													<div class="col-xs-12 m-bottom-10 clearfix <?php if(form_error('last_name')){ echo 'has-error has-feedback';} ?>">													
														<input type="text" class="form-control" id="last_name" name="last_name"  tabindex="2" placeholder="Last Name*"  value="<?php echo $user->user_last_name; ?>">
													</div>

													<div class="col-xs-12 m-bottom-10 clearfix <?php if(form_error('gender')){ echo 'has-error has-feedback';} ?>">													
														<select name="gender" class="form-control gender" tabindex="3" id="gender"><option value="Male">Male</option><option value="Female">Female</option></select>
														<script type="text/javascript">$('.gender').val('<?php echo $user->user_gender; ?>');</script>
													</div>

													<?php if( $this->session->userdata('users') > 1 || $this->session->userdata('is_admin') ==  1  ): ?>

														<div class="col-xs-12 m-bottom-10 clearfix <?php if(form_error('dob')){ echo 'has-error has-feedback';} ?>">													
															<input type="text" data-date-format="dd/mm/yyyy" placeholder="Date of Birth* DD/MM/YY" class="form-control datepicker" id="dob" name="dob" tabindex="4" value="<?php echo $user->user_date_of_birth; ?>">											
														</div>

													<?php else: ?>

														<div class="col-xs-12 m-bottom-10 clearfix">
															<label class="col-md-8 col-sm-8 m-top-5"><strong>Birthday</strong></label>
															<div class="col-md-12 col-sm-12 m-top-5"><?php echo $user->user_date_of_birth; ?></div>

															<input type="hidden" name="dob" value="<?php echo $user->user_date_of_birth; ?>">
														</div>

													<?php endif; ?>

												<?php else: ?>

													<div class="clearfix">
														<label class="col-sm-3 control-label m-bottom-10">Name</label>
														<div class="col-sm-9"><?php echo $user->user_first_name; ?> <?php echo $user->user_last_name; ?></div>
													</div>

													<div class="clearfix">
														<label class="col-sm-3 control-label m-bottom-10">Gender</label>
														<div class="col-sm-9"><?php echo $user->user_gender; ?></div>
													</div>

													<div class="clearfix">
														<label class="col-sm-3 control-label m-bottom-10">Age</label>
														<div class="col-sm-9"><?php echo $age; ?></div>
													</div>

													<div class="clearfix">
														<label class="col-sm-3 control-label m-bottom-10">Birthday</label>
														<div class="col-sm-9"><?php echo $user->user_date_of_birth; ?></div>
													</div>

												<?php endif; ?>
											</div>
										</div>
										<div class="clearfix"></div>




									</div>

												<div class="col-md-9 col-sm-10 col-xs-12">


												<input type="hidden" name="user_id" value="<?php echo $user->user_id; ?>">
												<input type="hidden" name="is_form_submit" value="1">


      								<div class="box bank_account" >
										<div class="box-head pad-5 m-bottom-5">
											<label><i class="fa fa-suitcase fa-lg"></i> Account Details</label>
										</div>
										
										<div class="box-area pad-5 clearfix">
											 



									<?php if( $this->session->userdata('users') > 1 || $this->session->userdata('user_id') == $user_id || $this->session->userdata('is_admin') ==  1  ): ?>
											

											<?php if( $this->session->userdata('users') > 1 || $this->session->userdata('is_admin') ==  1  ): ?>

												<div class="col-md-6 col-sm-4 col-xs-12 m-bottom-10 clearfix <?php if(form_error('department')){ echo 'has-error has-feedback';} ?>">
													<label for="department" class="col-sm-3 control-label">Department</label>
													<div class="col-sm-9">
														<select name="department" class="form-control department" id="department"  tabindex="7">
															<option value="">Select Department</option>
															<?php foreach ($departments as $key => $value): ?>
																<option value="<?php echo $value->department_id.'|'.$value->department_name; ?>"><?php echo $value->department_name; ?></option>
															<?php endforeach; ?>
														</select>

														<?php $department = ($this->input->post('department') != '' ? $this->input->post('department') : ''); ?>
														<script type="text/javascript">$('.department').val('<?php echo $user->department_id.'|'.$user->department_name; ?>');</script>
													</div>
												</div>


												<div class="col-md-6 col-sm-4 col-xs-12 m-bottom-10 clearfix <?php if(form_error('focus')){ echo 'has-error has-feedback';} ?>">
													<label for="focus" class="col-sm-3 control-label">Focus</label>
													<div class="col-sm-9">
														<select name="focus" class="form-control focus" id="focus" tabindex="10">
															<option value="">Select Focus Company</option>
															<?php foreach ($focus as $key => $value): ?>
																<option value="<?php echo $value->company_id.'|'.$value->company_name; ?>"><?php echo $value->company_name; ?></option>
															<?php endforeach; ?>
														</select>

														<?php //$focus = ($this->input->post('focus') != '' ? $this->input->post('focus') : ''); ?>

														<?php if($user->company_id != ''): ?>
															<script type="text/javascript">$('.focus').val('<?php echo $user->company_id.'|'.$user->company_name; ?>');</script>
														<?php else: ?>
															<script type="text/javascript">$('.focus').val('');</script>
														<?php endif; ?>
													</div>
												</div>

												<div class="col-md-6 col-sm-4 col-xs-12 m-bottom-10 clearfix ">
													<label for="is_offshore" class="col-sm-4 control-label">Offshore Employee</label>
													<div class="col-sm-8">
														<select name="is_offshore" class="form-control is_offshore" tabindex="3" id="is_offshore">
															<option value="1">Yes</option>
															<option value="0">No</option>
														</select>
														<script type="text/javascript">$('.is_offshore').val('<?php echo $user->is_offshore; ?>');</script>
													</div>
												</div>


												<div class="col-md-6 col-sm-4 col-xs-12 m-bottom-10 clearfix ">
													<label for="contractor_employee" class="col-sm-4 control-label">Contractor Employee</label>
													<div class="col-sm-8">
														<select name="contractor_employee" class="form-control contractor_employee"   id="contractor_employee">
															<option value="1">Yes</option>
															<option value="0">No</option>
														</select>
														<script type="text/javascript">$('.contractor_employee').val('<?php echo $user->is_third_party; ?>');</script>
													</div>
												</div>

												<div class="col-md-6 col-sm-4 col-xs-12 m-bottom-10 clearfix <?php if(form_error('focus')){ echo 'has-error has-feedback';} ?>">
													<label for="focus" class="col-sm-3 control-label">Direct Reports</label>
													<div class="col-sm-9">

														<select name="supervisor" class="form-control supervisor" id="supervisor" tabindex="10">

															<option value="0">Select Personel</option>
															<?php foreach ($user_list as $key => $value): ?>
																<?php if($value->primary_user_id !== $user_id_page || $user_id_page === '3'): ?>
																<option value="<?php echo $value->primary_user_id; ?>" ><?php echo $value->user_first_name." ".$value->user_last_name; ?></option>
																<?php endif; ?>
															<?php endforeach; ?>
														</select>

														<?php //$focus = ($this->input->post('focus') != '' ? $this->input->post('focus') : ''); ?>

														<?php if($user->supervisor_id !== 0): ?>
															<script type="text/javascript">$('.supervisor').val('<?php echo $user->supervisor_id; ?>');</script>
														<?php else: ?>
															<script type="text/javascript">$('.supervisor').val("0");</script>
														<?php endif; ?>
													</div>
												</div>

											<?php else: ?>

												<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
													<label class="col-sm-4 text-right">Focus</label>
													<div class="col-sm-8">
														<strong><?php echo $user->company_name; ?></strong>
													</div>
												</div>

												<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
													<label class="col-sm-4  text-right">Department</label>
													<div class="col-sm-8">
														<strong><?php echo $user->department_name; ?></strong>
													</div>
												</div>

												<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
													<label class="col-sm-4  text-right">Role</label>
													<div class="col-sm-8">
														<strong><?php echo $user->role_types; ?></strong>
													</div>
												</div>

												<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
													<label class="col-sm-4  text-right">Offshore Employee</label>
													<div class="col-sm-8">
														<strong><?php echo ($user->is_offshore == 1) ? 'Yes' : 'No'; ?></strong>
													</div>
												</div>

												<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
													<label class="col-sm-4  text-right">Contractor Employee</label>
													<div class="col-sm-8">
														<strong><?php echo ($user->is_third_party == 1) ? 'Yes' : 'No'; ?></strong>
													</div>
												</div>

												<input type="hidden" name="department" class="hide" value="<?php echo $user->department_id.'|'.$user->department_name; ?>">
												<input type="hidden" name="focus" class="hide" value="<?php echo $user->company_id.'|'.$user->company_name; ?>">

												<input type="hidden" name="supervisor" class="hide" value="<?php echo $user->supervisor_id; ?>">
												<input type="hidden" name="is_offshore" class="hide" value="<?php echo $user->is_offshore; ?>">
												
											<?php endif; ?>

											<div class="col-md-6 col-sm-4 col-xs-12 m-bottom-10 clearfix ">
												<label for="contractor_employee" class="col-sm-4 control-label">Site Access</label>
												<div class="col-sm-8">
													<select name="site_staff" class="form-control contractor_employee" id="site_staff">
														<option value="1" <?php if($user->is_site_staff == '1'){ ?> selected <?php } ?>>Yes</option>
														<option value="0" <?php if($user->is_site_staff == '0'){ ?> selected <?php } ?>>No</option>
													</select>
												</div>
											</div>

											<div class="col-md-6 col-sm-4 col-xs-12 m-bottom-10 clearfix ">
												<label for="login_name" class="col-sm-3 control-label">Login Name</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="login_name" name="login_name" tabindex="5" placeholder="Login Name" value="<?php echo $user->login_name; ?>" style="text-transform: none;">
												</div>
											</div>

											<?php if($user->is_site_staff == '1'): ?>
											<div class="col-md-6 col-sm-4 col-xs-12 m-bottom-10 clearfix ">
												<label for="contractor_employee" class="col-sm-4 control-label">General Induction Date</label>
												<div class="col-sm-8">
													<input type="date" class = "form-control" name = "gi_date" value = "<?php echo $user->general_induction_date ?>">
												</div>
											</div>
											<?php endif; ?>

											<?php if($this->session->userdata('is_admin') ==  1): ?>

												<div class="col-md-6 col-sm-4 col-xs-12 m-bottom-10 clearfix ">
													<label for="is_dummy" class="col-sm-4 control-label">Dummy Account</label>
													<div class="col-sm-8">
														<select name="is_dummy" class="form-control is_dummy" id="is_dummy">
															<option value="1" <?php if($user->is_dummy == '1'){ ?> selected <?php } ?>>Yes</option>
															<option value="0" <?php if($user->is_dummy == '0'){ ?> selected <?php } ?>>No</option>
														</select>
													</div>
												</div>

											<?php endif; ?>


										<?php else: ?>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
												<label class="col-sm-4 control-label">Focus</label>
												<div class="col-sm-8">
													<?php echo $user->company_name; ?>
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
												<label class="col-sm-4 control-label">Department</label>
												<div class="col-sm-8">
													<?php echo $user->department_name; ?>
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
												<label class="col-sm-4 control-label">Role</label>
												<div class="col-sm-8">
													<?php echo $user->role_types; ?>
												</div>
											</div>								

											<?php endif; ?>
										</div>
									</div>

      								<div class="clearfix"></div>


      								<div class="box  bank_account" >
										<div class="box-head pad-5 m-bottom-5">
											<label><i class="fa fa-phone-square fa-lg"></i> Contact Details</label>
										</div>

										<input type="hidden" name="contact_number_id" value="<?php echo $user->contact_number_id; ?>">
										
										<div class="box-area pad-5 clearfix">

										<?php if( $this->session->userdata('users') > 1 || $this->session->userdata('user_id') == $user_id  || $this->session->userdata('is_admin') ==  1  ): ?>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('direct_landline')){ echo 'has-error has-feedback';} ?>">
												<label for="direct_landline" class="col-sm-5 control-label">Direct Landline</label>
												<div class="col-sm-7">

													<div class="input-group ">
														<span class="input-group-addon">+</span>
													<input type="text" class="form-control direct_landline" id="direct_landline" name="direct_landline" onchange="contact_number_assign2('direct_landline')" tabindex="11" placeholder="Direct Landline"  value="<?php echo $user->direct_number; ?>">																										
												</div>
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('after_hours')){ echo 'has-error has-feedback';} ?>">
												<label for="after_hours" class="col-sm-4 control-label">After Hours</label>
												<div class="col-sm-8">

													<div class="input-group ">
														<span class="input-group-addon">+</span>
													<input type="text" class="form-control after_hours" id="after_hours" name="after_hours" onchange="contact_number_assign2('after_hours')" tabindex="12" placeholder="After Hours"  value="<?php echo $user->after_hours; ?>">																										
												</div>
												</div>
											</div>
											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
												<label for="mobile_number" class="col-sm-5 control-label">Mobile Number</label>
												<div class="col-sm-7">

													<div class="input-group ">
														<span class="input-group-addon">+</span>
														<input type="text" class="form-control mobile_number" id="mobile_number" name="mobile_number" placeholder="Mobile Number" onchange="mobile_number_assign_user2('mobile_number')"  tabindex="13" value="<?php echo $user->mobile_number; ?>">
													</div>
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
												<label for="personal_mobile_number" class="col-sm-5 control-label">Personal Mobile Number</label>
												<div class="col-sm-7">

													<div class="input-group ">
														<span class="input-group-addon">+</span>
														<input type="text" class="form-control personal_mobile_number" id="personal_mobile_number" name="personal_mobile_number" placeholder="Personal Mobile Number" onchange="//mobile_number_assign('mobile_number')"  tabindex="13" value="<?php echo $user->personal_mobile_number; ?>">
													</div>
												</div>
											</div>

											<?php if($this->session->userdata('is_admin') ==  1): ?>


												<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('email')){ echo 'has-error has-feedback';} ?>">
													<label for="email" class="col-sm-4 control-label">Email</label>
													<div class="col-sm-8">
														<input type="email" class="form-control" id="email" name="email"  tabindex="14" placeholder="Email"  value="<?php echo $user->general_email; ?>">
													</div>
													<input type="hidden" name="email_id" value="<?php echo $user->email_id; ?>">
												</div>

												<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('personal_email')){ echo 'has-error has-feedback';} ?>">
													<label for="personal_email" class="col-sm-4 control-label">Personal Email</label>
													<div class="col-sm-8">
														<input type="email" class="form-control" id="personal_email" name="personal_email"  tabindex="14" placeholder="Personal Email"  value="<?php echo $user->personal_email; ?>">
													</div>
													<input type="hidden" name="email_id" value="<?php echo $user->email_id; ?>">
												</div>

												<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('skype_id')){ echo 'has-error has-feedback';} ?>">
													<label for="skype_id" class="col-sm-4 control-label">Skype ID</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" id="skype_id" name="skype_id"  tabindex="15" placeholder="Skype ID"  value="<?php echo $user->user_skype; ?>" style="text-transform: none;">
													</div>
												</div>




											<?php else: ?>												
											
												<?php if( trim($user->general_email) != '' ): ?>											
													<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-5 m-top-10 clearfix">
														<label class="col-sm-4 text-right">Email <i class="fa fa-envelope"></i> </label>
														<div class="col-sm-8">
															<strong> <?php echo $user->general_email; ?></strong>
														</div>
													</div>
												<?php endif; ?>

												<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('personal_email')){ echo 'has-error has-feedback';} ?>">
													<label for="personal_email" class="col-sm-4 control-label">Personal Email</label>
													<div class="col-sm-8">
														<input type="email" class="form-control" id="personal_email" name="personal_email"  tabindex="14" placeholder="personal email"  value="<?php echo $user->personal_email; ?>">
													</div>
													<input type="hidden" name="email_id" value="<?php echo $user->email_id; ?>">
												</div>

												<div class="clearfix"></div>

												<?php if( trim($user->user_skype) != '' ): ?>											
													<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
														<label for="skype_id" class="col-sm-5 text-right">Skype ID <i class="fa fa-skype fa-lg"></i> </label>
														<div class="col-sm-7">
															<strong> <?php echo $user->user_skype; ?></strong>
														</div>
													</div>
												<?php endif; ?>

												<input type="hidden" class="form-control hide" readonly="true" id="email" name="email"  tabindex="14" placeholder="Email"  value="<?php echo $user->general_email; ?>">
												<input type="hidden" class="form-control hidden" readonly="true" id="skype_id" name="skype_id"  tabindex="15" placeholder="Skype ID"  value="<?php echo $user->user_skype; ?>" style="text-transform: none;">	

											<?php endif; ?>

											<input type="hidden" name="email_id" value="<?php echo $user->email_id; ?>">

										<?php else: ?>

											<?php if( trim($user->direct_number) != '' ): ?>
												<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
													<label class="col-sm-4 control-label">Direct Landline</label>
													<div class="col-sm-8">
														<?php echo $user->direct_number; ?>																										
													</div>
												</div>
											<?php endif; ?>

											<?php if( trim($user->after_hours) != '' ): ?>
												<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
													<label class="col-sm-4 control-label">After Hours</label>
													<div class="col-sm-8">
														<?php echo $user->after_hours; ?>																									
													</div>
												</div>
											<?php endif; ?>

											<?php if( trim($user->mobile_number) != '' ): ?>
												<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
													<label class="col-sm-4 control-label">Mobile Number</label>
													<div class="col-sm-8">
														<?php echo $user->mobile_number; ?>
													</div>
												</div>
											<?php endif; ?>

											<?php if( trim($user->general_email) != '' ): ?>											
												<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
													<label class="col-sm-4 control-label">Email</label>
													<div class="col-sm-8">
														<?php echo $user->general_email; ?>
													</div>
												</div>
											<?php endif; ?>

											<?php if( trim($user->user_skype) != '' ): ?>											
												<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
													<label for="skype_id" class="col-sm-4 control-label">Skype ID <i class="fa fa-skype fa-lg"></i></label>
													<div class="col-sm-8">
														<?php echo $user->user_skype; ?>
													</div>
												</div>
											<?php endif; ?>

										<?php endif; ?>



											<?php if( $this->session->userdata('users') > 1 || $this->session->userdata('user_id') == $user_id  || $this->session->userdata('is_admin') ==  1  ): ?>
											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('skype_password')){ echo 'has-error has-feedback';} ?>">
												<label for="skype_password" class="col-sm-5 control-label">Skype Password</label>
												<div class="col-sm-7">
													
													<div class="input-group ">
														<span class="input-group-addon"><i class="fa fa-skype fa-lg"></i></span>
														<input type="text" class="form-control" id="skype_password" name="skype_password"  tabindex="15" placeholder="Skype Password"  value="<?php echo $user->user_skype_password; ?>" style="text-transform: none;">
													</div>



												</div>
											</div>

										<?php endif; ?>

											<input type="hidden" name="profile_raw" value="<?php echo $user->user_profile_photo; ?>">

										</div>
									</div>      					

      								<div class="clearfix"></div>


      								<div class="box">
										<div class="box-head pad-5">
											<label for="project_notes"><i class="fa fa-pencil-square fa-lg"></i> About</label>
										</div>

										<input type="hidden" name="user_comments_id" value="<?php echo $user->user_comments_id; ?>">
											
										<div class="box-area pad-5 clearfix">
											<div class="clearfix">												
												<div class="pad-10">
												<?php if( $this->session->userdata('users') > 1 || $this->session->userdata('user_id') == $user_id  || $this->session->userdata('is_admin') ==  1  ): ?>
													<textarea class="form-control" id="comments" rows="12"  tabindex="16" name="comments"><?php echo $user->comments; ?></textarea>
												<?php else: ?>
													<?php if( trim($user->comments) != '' ): ?>
														<i class="fa fa-quote-left"></i> <?php echo $user->comments; ?> <i class="fa fa-quote-right"></i>

													<?php else: ?>

														<i class="fa fa-quote-left"></i> No about posted. <i class="fa fa-quote-right"></i>

													<?php endif; ?>

												<?php endif; ?>

												</div>
											</div>
										</div>
									</div>
									<div class="clearfix"></div>

									<input type="hidden" name="is_form_submit" value="1">

									<?php if( $this->session->userdata('users') > 1 || $this->session->userdata('user_id') == $user_id  || $this->session->userdata('is_admin') ==  1  ): ?>
										<div class="m-top-15 clearfix">
											<div>
												<button type="submit" class="btn btn-success btn-lg submit_form" id="focus_add_company" name="save_bttn" value="Save"><i class="fa fa-floppy-o"></i> Update</button>
											</div>
										</div>
									<?php endif; ?>
								<?php if( $this->session->userdata('users') > 1 || $this->session->userdata('user_id') == $user_id  || $this->session->userdata('is_admin') ==  1  ): ?>
									</form>
								<?php endif; ?>

								<input type="hidden" name="user_id_access" value="<?php echo $this->session->userdata('user_id'); ?>">

								<?php if( $this->session->userdata('users') > 1 || $this->session->userdata('user_id') == $user_id || $this->session->userdata('is_admin') ==  1 || $leave_requests == 1 ): ?>
									<div class="box">
										<div class="box-head pad-5">
											<label for="project_notes"><i class="fa fa-calendar fa-lg"></i> Schedule of Work and Total Leaves</label>
										</div>

										<div class="box-area pad-5 clearfix">
											
											<div class="clearfix">												
												<div class="col-sm-2" style="padding-left: 20px;">
													<?php 
														if (!$leave_alloc): 
															if( $this->session->userdata('users') > 1 || $this->session->userdata('is_admin') ==  1  ):
																echo '<form id="add_leave_total" method="post" action="../add_leave_alloc/'.$user_id_page.'">';
															endif; 
													 	else: 
															echo '<form id="update_leave_total" method="post" action="../update_leave_alloc/'.$user_id_page.'">';
														endif; 
													 	
													?>

														<?php if(@$error): ?>
															<div class="pad-10 no-pad-t">
																<div class="border-less-box alert alert-danger fade in">
																	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
																	<h4>Oh snap! You got an error!</h4>
																	<?php echo $error;?>
																</div>
															</div>
														<?php endif; ?>


														<input type="hidden" name="is_offshore_update" value="<?php echo $user->is_offshore; ?>">

														<?php if( $this->session->userdata('users') > 1 || $this->session->userdata('is_admin') ==  1  ): ?>

															<div class="clearfix"></div><br>

															<div class="form-group m-left-10">
																<label for="leave_rate_type">Leave Rate Type</label>
																<select name="leave_rate_type" class="form-control leave_rate_type" id="leave_rate_type" tabindex="">
																	<option value="">Select Type</option>

																	<?php if (!empty($leave_alloc)){ ?>

																	<option value="1" <?php if($leave_alloc->leave_rate_type == '1'){ echo 'selected'; } ?>>AU - Salaried</option>
																	<option value="2" <?php if($leave_alloc->leave_rate_type == '2'){ echo 'selected'; } ?>>AU - Wages</option>
																	<option value="3" <?php if($leave_alloc->leave_rate_type == '3'){ echo 'selected'; } ?>>Manila Staff</option>

																	<?php } else { ?>

																	<option value="1">AU - Salaried</option>
																	<option value="2">AU - Wages</option>
																	<option value="3">Manila Staff</option>

																	<?php } ?>

																</select>

																<?php $leave_rate_type = ($this->input->post('leave_rate_type') != '' ? $this->input->post('leave_rate_type') : ''); ?>
																<script type="text/javascript">$('.leave_rate_type').val('<?php echo $leave_credits->leave_rate_type; ?>');</script>
															</div>
														<?php else: ?>

															<!-- <div class="form-group m-left-10">
																<label for="leave_rate_type">Leave Rate Type</label>

																<?php //if (!empty($leave_alloc)){ ?>

																	<select name="leave_rate_type" class="form-control leave_rate_type" id="leave_rate_type"  tabindex="" disabled="TRUE">
																		<option value="1" <?php //if($leave_alloc->leave_rate_type == '1'){ echo 'selected'; } ?>>AU - Salaried</option>
																		<option value="2" <?php //if($leave_alloc->leave_rate_type == '2'){ echo 'selected'; } ?>>AU - Wages</option>
																		<option value="3" <?php //if($leave_alloc->leave_rate_type == '3'){ echo 'selected'; } ?>>Manila Staff</option>
																	</select>

																<?php //} else { ?>

																	<select name="leave_rate_type" class="form-control leave_rate_type" id="leave_rate_type" tabindex="" disabled="TRUE">
																		<option value="" selected>Select Type</option>
																	</select>

																<?php //} ?>
															</div> -->													

														<?php endif; ?>


														<!-- <div class="form-group m-left-10">
														    &nbsp;&nbsp;<label for="no_hrs_of_work">No. Hrs of Work:</label><br>
														    <div class="input-group ">
																<span class="input-group-addon"><i class="fa fa-clock-o fa-lg"></i></span>
																<input type="text" id="no_hrs_of_work" class="form-control text-center" name="no_hrs_of_work" value="<?php //echo (!empty($leave_alloc->no_hrs_of_work) ? $leave_alloc->no_hrs_of_work : '' ); ?>">
															</div>
														</div> -->

														<!-- <div class="checkbox">
														    <label>
														      <input type="checkbox" name="sched[]" value="0" <?php //if (!empty($get_sched_of_work)){echo (in_array("0", $get_sched_val) ? 'checked="checked"' : '');} echo ($this->session->userdata('users') > 1 || $this->session->userdata('is_admin') ==  1) ? '' : 'disabled="TRUE"'; ?>>&nbsp;&nbsp;Sunday
														    </label>
														</div>  -->

														
														
														<?php if( $this->session->userdata('users') > 1 || $this->session->userdata('is_admin') ==  1  ): ?>
														<?php else: ?>
																<div class="clearfix"></div><br>
														<?php endif; ?>

														<div class="checkbox">
														    <label>
														      <input type="checkbox" name="sched[]" value="1" <?php if (!empty($get_sched_of_work)){echo (in_array("1", $get_sched_val) ? 'checked="checked"' : '');} echo ($this->session->userdata('users') > 1 || $this->session->userdata('is_admin') ==  1) ? '' : 'disabled="TRUE"'; ?> <?php if (empty($get_sched_of_work)){echo 'checked';} ?>>&nbsp;&nbsp;Monday
														    </label>
														</div>
														<div class="checkbox">
														    <label>
														      <input type="checkbox" name="sched[]" value="2" <?php if (!empty($get_sched_of_work)){echo (in_array("2", $get_sched_val) ? 'checked="checked"' : '');} echo ($this->session->userdata('users') > 1 || $this->session->userdata('is_admin') ==  1) ? '' : 'disabled="TRUE"'; ?> <?php if (empty($get_sched_of_work)){echo 'checked';} ?>>&nbsp;&nbsp;Tuesday
														    </label>
														</div>
														<div class="checkbox">
														    <label>
														      <input type="checkbox" name="sched[]" value="3" <?php if (!empty($get_sched_of_work)){echo (in_array("3", $get_sched_val) ? 'checked="checked"' : '');} echo ($this->session->userdata('users') > 1 || $this->session->userdata('is_admin') ==  1) ? '' : 'disabled="TRUE"'; ?> <?php if (empty($get_sched_of_work)){echo 'checked';} ?>>&nbsp;&nbsp;Wednesday
														    </label>
														</div>
														<div class="checkbox">
														    <label>
														      <input type="checkbox" name="sched[]" value="4" <?php if (!empty($get_sched_of_work)){echo (in_array("4", $get_sched_val) ? 'checked="checked"' : '');} echo ($this->session->userdata('users') > 1 || $this->session->userdata('is_admin') ==  1) ? '' : 'disabled="TRUE"'; ?> <?php if (empty($get_sched_of_work)){echo 'checked';} ?>>&nbsp;&nbsp;Thursday	
														    </label>
														</div>
														<div class="checkbox">
														    <label>
														      <input type="checkbox" name="sched[]" value="5" <?php if (!empty($get_sched_of_work)){echo (in_array("5", $get_sched_val) ? 'checked="checked"' : '');} echo ($this->session->userdata('users') > 1 || $this->session->userdata('is_admin') ==  1) ? '' : 'disabled="TRUE"'; ?> <?php if (empty($get_sched_of_work)){echo 'checked';} ?>>&nbsp;&nbsp;Friday
														    </label>
														</div>
														<!-- <div class="checkbox">
														    <label>
														      <input type="checkbox" name="sched[]" value="6" <?php //if (!empty($get_sched_of_work)){echo (in_array("6", $get_sched_val) ? 'checked="checked"' : '');} echo ($this->session->userdata('users') > 1 || $this->session->userdata('is_admin') ==  1) ? '' : 'disabled="TRUE"'; ?>>&nbsp;&nbsp;Saturday
														    </label>
														</div> -->
														<br>
													</div>

													<br>

													<div class="col-sm-5">

														<?php if( $this->session->userdata('users') > 1 || $this->session->userdata('is_admin') ==  1  ): ?>
															<label for="annual_manual_entry" class="col-sm-6 control-label">Starting Annual Leave*:</label>
															<div class="col-sm-6">		
																<div class="input-group ">
																	<input type="text" class="form-control text-center" id="annual_manual_entry" name="annual_manual_entry" placeholder="Days" value="<?php echo (!empty($leave_alloc->annual_manual_entry) ? round($leave_alloc->annual_manual_entry, '2') : '0' ); ?>" onkeypress="return isNumberKey(event)" onkeyup="startingAnnualCheck();">
																	<span class="input-group-addon">days</span>
																</div>
															</div>

															<div class="clearfix"></div><br>

														<?php else: ?>
															<!-- <input type="hidden" name="annual_manual_entry" value="<?php //echo (!empty($leave_alloc->annual_manual_entry) ? $leave_alloc->annual_manual_entry : '0' ); ?>">
															<label name="annual_manual_entry" id="annual_manual_entry" class="control-label" style="color: #555; font-weight: bold;"><?php //echo (!empty($leave_alloc->annual_manual_entry) ? round($leave_alloc->annual_manual_entry, '2').' days' : '0' ); ?></label> -->
														<?php endif; ?>
														
														<?php 
															if (!empty($leave_alloc)){
																if ($user->is_offshore == 1) {
																	$earned_annual_points = $leave_alloc->annual_earned_offshore;	
																} else {
																	$annual_manual_entry = $leave_alloc->annual_manual_entry;
																	$annual_accumulated = $leave_alloc->annual_accumulated;
																	$last_annual_accumulated = $leave_alloc->last_annual_accumulated;
																	$total_annual_points = $annual_accumulated + $last_annual_accumulated;
																}

																$converted_annual_holidays = $annual_holidays->holidays * $leave_alloc->no_hrs_of_work;
															}
														?>

														<label for="annual_day_earned" class="col-sm-6 control-label">Earned Annual Leave:</label>
														<div class="col-sm-6">
														<?php if ($user->is_offshore == 1) {?>
															<label name="annual_day_earned" id="annual_day_earned" class="control-label" style="color: #F7901E; font-weight: bold;"><?php echo (!empty($earned_annual_points) ? round($earned_annual_points, 2).' days' : '0 days' ); ?></label>
														<?php } else { ?>
															<label name="annual_day_earned" id="annual_day_earned" class="control-label" style="color: #F7901E; font-weight: bold;"><?php echo (!empty($leave_alloc->total_annual) ? round($total_annual_points, 6).' days' : '0 days' ); ?></label>
														<?php } ?>
														</div>

														<div class="clearfix"></div><br>

														<label for="used_annual" class="col-sm-6 control-label">Used Annual Leave:</label>
														<div class="col-sm-6">
															<label name="used_annual" id="used_annual" class="control-label" style="color: red; font-weight: bold;"><?php echo (!empty($used_annual_total->used_annual) ? number_format($used_annual_total->used_annual - $annual_holidays->holidays, '2', '.', ''). ' days' : '0 days' ); ?></label> 
														</div>

														<div class="clearfix"></div><br>

														<label for="total_annual" class="col-sm-6 control-label">Total Annual Leave:</label>
														<div class="col-sm-6">
															<label name="total_annual" id="total_annual" class="control-label" style="color: green; font-weight: bold;"><?php echo (!empty($leave_alloc->total_annual) ? round($leave_alloc->total_annual, 2).' days' : '0 days' ); ?></label>
														</div>
													</div>

													<div class="col-sm-5">

														<?php if( $this->session->userdata('users') > 1 || $this->session->userdata('is_admin') ==  1  ): ?>

															<label for="personal_manual_entry" class="col-sm-6 control-label">Starting Personal Leave*:</label>
															<div class="col-sm-6">
																<div class="input-group ">
																	<input type="text" class="form-control text-center" id="personal_manual_entry" name="personal_manual_entry" placeholder="Days" value="<?php echo (!empty($leave_alloc->personal_manual_entry) ? round($leave_alloc->personal_manual_entry, '2') : '0' ); ?>" onkeypress="return isNumberKey(event)" onkeyup="startingAnnualCheck();">
																	<span class="input-group-addon">days</span>
																</div>
															</div>

															<div class="clearfix"></div><br>

														<?php else: ?>
																<!-- <input type="hidden" name="personal_manual_entry" value="<?php //echo (!empty($leave_alloc->personal_manual_entry) ? $leave_alloc->personal_manual_entry : '0' ); ?>">
																<label name="personal_manual_entry" id="personal_manual_entry" class="control-label" style="color: #555; font-weight: bold;"><?php //echo (!empty($leave_alloc->personal_manual_entry) ? round($leave_alloc->personal_manual_entry, '2').' days' : '0' ); ?></label> -->
														<?php endif; ?>
														
														<?php 
															if (!empty($leave_alloc)){
																if ($user->is_offshore == 1) {
																	$earned_personal_points = $leave_alloc->personal_earned_offshore;
																} else {
																	$personal_manual_entry = $leave_alloc->personal_manual_entry;
																	$personal_accumulated = $leave_alloc->personal_accumulated;
																	$last_personal_accumulated = $leave_alloc->last_personal_accumulated;
																	$total_personal_points = $personal_accumulated + $last_personal_accumulated;
																}

																$converted_sick_holidays = $sick_holidays->holidays * $leave_alloc->no_hrs_of_work;
															}
														?>

														<label for="personal_day_earned" class="col-sm-6 control-label">Earned Personal Leave:</label>
														<div class="col-sm-6">
														<?php if ($user->is_offshore == 1) {?>
															<label name="personal_day_earned" id="personal_day_earned" class="control-label" style="color: #F7901E; font-weight: bold;"><?php echo (!empty($earned_annual_points) ? round($earned_personal_points, 2).' days' : '0 days' ); ?></label>
														<?php } else { ?>
															<label name="personal_day_earned" id="personal_day_earned" class="control-label" style="color: #F7901E; font-weight: bold;"><?php echo (!empty($leave_alloc->total_annual) ? round($total_personal_points, 6).' days' : '0 days' ); ?></label>
														<?php } ?>
														</div>

														<div class="clearfix"></div><br>

														<label for="used_personal" class="col-sm-6 control-label">Used Personal Leave:</label>
														<div class="col-sm-6">
																<label name="used_personal" id="used_personal" class="control-label" style="color: red; font-weight: bold;"><?php echo (!empty($used_personal_total->used_personal) ? number_format($used_personal_total->used_personal - $sick_holidays->holidays, '2', '.', ''). ' days' : '0 days' ); ?></label>
														</div>

														<div class="clearfix"></div><br>
														
														<label for="total_personal" class="col-sm-6 control-label">Total Personal Leave:</label>
														<div class="col-sm-6">
															<label name="total_personal" id="total_personal" class="control-label" style="color: green; font-weight: bold;"><?php echo (!empty($leave_alloc->total_personal) ? round($leave_alloc->total_personal, 2).' days' : '0 days' ); ?></label>
														</div>

													</div>

													<div class="pad-top-10 m-top-10 row">
														<?php 
															if( $this->session->userdata('users') > 1 || $this->session->userdata('is_admin') ==  1  ): ?>
															<?php if (!$leave_alloc): ?>
																	<div class="pad-top-10 m-top-10 col-sm-4 col-sm-offset-3">
																		<button type="submit" class="btn btn-warning" name="insert_leave_alloc" id="insert_leave_alloc">Insert Leave Allocations and Work Schedule</button>
																	</div>
															<?php else: ?>
																	<div class="pad-top-10 m-top-10 col-sm-4 col-sm-offset-3">
																		<button type="submit" class="btn btn-primary" name="update_leave_alloc">Update Leave Allocations and Work Schedule</button>
																	</div>
															<?php 
																endif;
															else: ?>
																<?php if ($leave_alloc && $user_id_page == $this->session->userdata('user_id')): ?>
																	<!--<div class="pad-top-10 m-top-10 col-sm-4 col-sm-offset-3">
																		<button type="submit" class="btn btn-primary" name="update_leave_alloc">Update Work Schedule</button>
																	</div>-->

																<?php endif;
														
															endif; 
														?>
													</form>
												</div>
												<div class="clearfix"></div><br>
											</div>
										</div>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
















										<?php endforeach; ?>						
										

									


					</div>					

					<div class="col-md-3">



					<div class="m-top-10">

					<div class="panel-group" role="tablist">
							<div class="panel panel-default">
								<div class="panel-heading" role="tab" id="collapseListGroupHeading1">
									<h4 class="panel-title">
										<a class="collapsed" role="button" data-toggle="collapse" href="#collapseListGroup2" aria-expanded="false" aria-controls="collapseListGroup2">
											<i class="fa fa-tags fa-lg"></i> Availability <label> <?php $this->users->get_user_availability($user_id_page); ?> </label>
										</a>
									</h4>
								</div> 
								<div id="collapseListGroup2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="collapseListGroupHeading1" aria-expanded="false" style="height: 0px;">


									<div class="pad-10">
										<div class="clearfix m-top-0 m-bottom-10">


											<label class="control-label m-top-5 col-xs-12 pointer set_ave_def" style="color: green;"><i class="fa fa-check-circle"></i> Available </label>
											<label class="control-label m-top-5 col-xs-12 pointer set_ave" style="color: orange;" data-toggle="modal" data-target="#set_availability" tabindex="-1"><i class="fa fa-arrow-circle-left"></i> Out of Office </label>
											<label class="control-label m-top-5 col-xs-12 pointer set_ave" style="color: red;" data-toggle="modal" data-target="#set_availability" tabindex="-1"><i class="fa fa-exclamation-circle"></i> Busy </label>
											<label class="control-label m-top-5 col-xs-12 pointer set_ave" style="color: gray;" data-toggle="modal" data-target="#set_availability" tabindex="-1"><i class="fa fa-minus-circle"></i> Leave </label>
											<label class="control-label m-top-5 col-xs-12 pointer set_ave" style="color: purple;" data-toggle="modal" data-target="#set_availability" tabindex="-1"><i class="fa fa-times-circle"></i> Sick</label>

											<p>&nbsp;</p>
											<?php $f_availability = $this->users->fetch_user_future_availability($user_id_page); ?>
											<?php if($f_availability->num_rows > 0): ?>
												<p><strong><i class="fa fa-calendar-o" aria-hidden="true"></i> Future Availability</strong></p>
												<ul>
													<?php foreach ($f_availability->result_array() as $avail_data): ?>
														<li>
															<span> 
																<strong class="pointer edit_f_ava" data-toggle="modal" data-target="#update_availability" id="<?php echo $avail_data['user_availability_id'].'_'.$avail_data['notes'].'_'.date('d/m/Y h:i A',$avail_data['date_time_stamp_a']).'_'.date('d/m/Y h:i A',$avail_data['date_time_stamp_b']); ?>">
																	<?php echo $avail_data['status']; ?>
																</strong> - <?php echo date("D jS \of M Y h:i A",$avail_data['date_time_stamp_a']); ?>
															</span>
															<span class="pull-right pointer delete_f_ava" id="<?php echo $avail_data['user_availability_id']; ?>" style="color:red">
																<i class="fa fa-times" aria-hidden="true"></i>
															</span>
														</li>
													<?php endforeach; ?>
												</ul>
											<?php endif; ?>


											<?php $rec_availability = $this->users->fetch_user_future_reocc_ava($user_id_page); ?>

											<?php if($rec_availability->num_rows > 0): ?>
										
												<p><strong><i class="fa fa-calendar-o" aria-hidden="true"></i> Reoccuring Availability</strong></p>
												<ul>
													<?php foreach ($rec_availability->result_array() as $avail_data): ?>


														


														<li >
															<span> 
																<strong class="  tooltip-enabled" title="" data-html="true" data-placement="left" data-original-title="<?php echo ucfirst($avail_data['notes']); ?>" id="">
																	<?php echo $avail_data['status']; ?>
																</strong> -


																<?php 

																$pattern_type = $avail_data['pattern_type'];

																switch ($pattern_type) {																	
																	case "daily": 
																		echo "Daily every <strong>".strtoupper($avail_data['range_reoccur']).'</strong>';
																	break;

																	case "weekly": 
																		echo "Weekly every <strong>".$avail_data['limits']."</strong> week(s)<br />during <strong>".strtoupper($avail_data['range_reoccur']).'</strong>';
																	break;

																	case "monthly":
																		echo "Monthly every <strong>".$avail_data['limits']."</strong> month(s)<br />during <strong>".$avail_data['range_reoccur']."".$this->users->ordinalSuffix($avail_data['range_reoccur']).' day of the month.</strong>';
																	break;

																	case "yearly":

																		$arr_months = array("","January","February","March","April","May","June","July","August","September","October","November","December");
																		echo "Yearly every <strong>".$avail_data['range_reoccur']."".$this->users->ordinalSuffix($avail_data['range_reoccur'])." of ".$arr_months[abs($avail_data['limits'])]."</strong>";
																	break;
																}


																 ?>


															</span>
															<span class="pull-right pointer delete_rec_ava" id="<?php echo $avail_data['reoccur_id']; ?>" style="color:red">
																<i class="fa fa-times" aria-hidden="true"></i>
															</span>
														</li>
													<?php endforeach; ?>
												</ul>
											<?php endif; ?>



										</div>
									</div>

								</div>
								</div>
							</div> 



							
						<?php if( $this->session->userdata('users') > 1 || $this->session->userdata('user_id') == $user_id  || $this->session->userdata('is_admin') ==  1  ): ?>
						<div class="panel-group" role="tablist">
							<div class="panel panel-default">
								<div class="panel-heading" role="tab" id="collapseListGroupHeading1">
									<h4 class="panel-title">
										<a class="collapsed" role="button" data-toggle="collapse" href="#collapseListGroup1" aria-expanded="false" aria-controls="collapseListGroup1">
											<i class="fa fa-unlock fa-lg"></i> Change User Password
										</a>
									</h4>
								</div>
								<?php if($this->session->userdata('is_admin') == 1 ): ?>
									<div id="collapseListGroup1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="collapseListGroupHeading1" aria-expanded="false" style="height: 0px;">
									<?php else: ?>
										<div id="collapseListGroup1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="collapseListGroupHeading1" aria-expanded="false" style="height: auto;">
										<?php endif; ?>

										<div class="pad-10">
										<?php /*	<form method="post" class="change_password_form" onkeypress="return event.keyCode != 13;">
												<?php if($this->session->userdata('is_admin') == 1 ): ?>
													<p>Current Password : <strong><?php echo $current_password; ?></strong></p>
												<?php endif; ?>

												<div id="passstrength" class="pad-5 border-less-box alert alert-info m-bottom-10"><strong>Note</strong>: The new password must contain a minimum of 8 characters, a number, a symbol and a capital letter.<br /><strong>Update</strong>: Space is not allowed.</div>

												<div class="clearfix m-top-0 m-bottom-10">
													<label for="new_password" class="col-sm-5 control-label m-top-5" style="font-weight: normal;">New Password</label>
													<div class="col-sm-7">
														<input type="password" class="form-control tooltip-enabled" id="new_password" name="new_password" placeholder="New Password" value="" data-original-title="Note: The new password must contain a minimum of 8 characters, a number, a symbol and a capital letter. *Update: Space is not allowed.">
													</div>
												</div>


												<div class="clearfix m-top-10 m-bottom-10">
													<label for="confirm_password" class="col-sm-5 control-label m-top-5" style="font-weight: normal;">Confirm Password</label>
													<div class="col-sm-7">
														<input type="password" class="form-control" disabled="true" id="confirm_password" name="confirm_password" placeholder="Confirm Password" value="">
													</div>
												</div>
												<div class="clearfix"></div>

												
												

											</form> */ ?>


											<form method="post" class="change_password_form" onkeypress="return event.keyCode != 13;">

												<?php if($this->session->userdata('is_admin') == 1 ): ?>
													<p>Current Password : <strong><?php echo $current_password; ?></strong></p>
												<?php endif; ?>				

												<div id="passstrength" class="pad-5 border-less-box alert alert-info m-bottom-10"><strong>Note</strong>: The new password must contain a minimum of 8 characters, a number, a symbol and a capital letter.<br /><strong>Update</strong>: Space is not allowed.</div>

												<div id="" class="pad-5 border-less-box alert alert-warning m-bottom-10"><p><strong>Note</strong>: Your new password will take effect on your next login.</p></div>




												<div class="clearfix m-top-0 m-bottom-10">
													<label for="new_password" class="col-sm-5 control-label m-top-5" style="font-weight: normal;">New Password</label>
													<div class="col-sm-7">
														<input type="password" class="form-control tooltip-enabled" id="new_password" name="new_password" placeholder="New Password" value="" data-original-title="Note: The new password must contain a minimum of 8 characters, a number, a symbol and a capital letter. *Update: Space is not allowed.">
													</div>
												</div>


												<div class="clearfix m-top-10 m-bottom-10">
													<label for="confirm_password" class="col-sm-5 control-label m-top-5" style="font-weight: normal;">Confirm Password</label>
													<div class="col-sm-7">
														<input type="password" class="form-control" disabled="true" id="confirm_password" name="confirm_password" placeholder="Confirm Password" value="">
													</div>
												</div>
												<div class="clearfix"></div>

												<input type="submit" name="update_password" value="Update Password" class="pull-right btn btn-danger m-right-5 m-bottom-10 change_passwprd_button">

											</form>
										</div>

									</div>
								</div>
							</div>
						</div>

						<!-- <form method="post" action="../update_user_site/<?php //echo $user_id ?>">
						<div class="box clearfix">
							<div class="box-head pad-5 m-bottom-10">
								<label><i class="fa fa-unlock-alt fa-lg"></i> Select Site</label>
							</div>						

							<?php //$is_admin_set = $is_user_admin;  ?>
							

							<div class="col-xs-12 m-bottom-10 clearfix">
								<div class="col-xs-12 m-bottom-10 clearfix" style = "padding-left: 50px">
									<select class="segment-select" id = "site_select" name = "site_select">
  										<option value="1" <?php //if($user->site_access == '1'){ ?> selected = "Selected" <?php //} ?>>Sojourn</option>
  										<option value="2" <?php //if($user->site_access == '2'){ ?> selected = "Selected" <?php //} ?>>Site Labour App</option>
									</select>
									<script>$(".segment-select").Segment();</script>
								</div>

								<div class="col-xs-12 m-bottom-10 clearfix">
									<button type = "submit" class = "btn btn-primary btn-sm pull-right">Update</button>
								</div>
							</div>
						</div>
						</form> -->

						
 


					<?php endif; ?>

 						<?php $user_access_arr = explode(',',  $this->users->get_user_access($user_id) ); ?>
						<?php $company_project_set = $user_access_arr['15'];  ?>

						
						<?php if( ($this->session->userdata('users') > 1 ) || $this->session->userdata('is_admin') ==  1  ): ?>




<?php if($user->role_id == 2): ?>
							<div class="panel-group" role="tablist">
							<div class="panel panel-default">
								<div class="panel-heading" role="tab" id="collapseListGroupHeading1">
									<h4 class="panel-title">
										<a class="collapsed" role="button" data-toggle="collapse" href="#collapseListGroup_pm" aria-expanded="false" aria-controls="collapseListGroup_pm">
											<i class="fa fa-users  fa-lg"></i> Update PM Assignments
										</a>
									</h4>
								</div>
																	<div id="collapseListGroup_pm" class="panel-collapse collapse" role="tabpanel" aria-labelledby="collapseListGroupHeading1" aria-expanded="false" style="height: 0px;">
									
										<div class="pad-10">
											 

											 


												<form class="form-horizontal" role="form" method="post" action="<?php echo base_url(); ?>admin/primay_pa_pm">

												<?php
	  

						$assignment =   $this->admin->list_pa_assignment($user_id);

						$pa_assignment = explode(',',$assignment['project_manager_ids']   );
						$pa_primary =  $assignment['project_manager_primary_id'];

						echo '<div class="">';

						foreach ($user_list as $pm){

							 

						if($pm->user_role_id == 3 || $pm->user_role_id == 20):


						 


							$fetch_user = $this->user_model->fetch_user($pm->primary_user_id);
							$pm_data = array_shift( $fetch_user->result() ); 

							echo '<p class="m-bottom-15">';

								if($pa_primary == $pm->primary_user_id){
									echo '<input type="radio" name="pm_primary'.$user_id.'" value="'.$pm->primary_user_id.'" id="prime'.$user_id.''.$pm->primary_user_id.'" checked="checked">';
								}else{
									echo '<input type="radio" name="pm_primary'.$user_id.'" value="'.$pm->primary_user_id.'" id="prime'.$user_id.''.$pm->primary_user_id.'">';
								}

								echo '<label class="m-right-15 m-left-5"  for="prime'.$user_id.''.$pm->primary_user_id.'">Prime</label>';
								 
								if( in_array($pm->primary_user_id, $pa_assignment) ){
									echo '<input  type="checkbox" id="'.$user_id.''.$pm->primary_user_id.'" name="pm_set_'.$user_id.'[]" value="'.$pm->primary_user_id.'" checked="checked">';
								}else{
									echo '<input  type="checkbox" id="'.$user_id.''.$pm->primary_user_id.'" name="pm_set_'.$user_id.'[]" value="'.$pm->primary_user_id.'">';
								}

								echo '<label  class="m-left-5" for="'.$user_id.''.$pm->primary_user_id.'">'.$pm->user_first_name.'  '.$pm->user_last_name.' &nbsp; <strong> <em> <sub>'.$pm_data->company_name.'</sub></em></strong> </label>';

							echo '</p>';
						

						endif;
					}
						echo '</div>';
					 

					?>

					<input type="hidden" name="pa_user_id" value="<?php echo "$user_id"; ?>" >
<button type="submit" class="btn btn-success m-bottom-10 m-right-5"><i class="fa fa-floppy-o"></i> Save Assignment</button>	



												<div class="clearfix"></div>

												
												

											</form>
										</div>

									</div>
								</div>
							</div>

<?php endif; ?>



						<form method="post" action="../update_user_access">
			

 						<input type="hidden" name="user_id_access" value="<?php echo $user_id; ?>">




						<div class="box clearfix">
							<div class="box-head pad-5">
								<label>
 

								<a style="font-weight:normal; color: #333; font-size:16px; margin-left:10px;" class="collapsed" role="button" data-toggle="collapse" href="#collapseListGroup3" aria-expanded="false" aria-controls="collapseListGroup3"> 


								<i class="fa fa-unlock-alt fa-lg"></i> Select Access</label>

								</a>
							</div>		



							<?php $is_admin_set = $is_user_admin;  ?>
							
 

							<div id="collapseListGroup3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="" aria-expanded="false" style="height: 0px;">


							<div class="col-xs-12 m-bottom-10 clearfix m-top-10">



								<label for="is_admin" class="col-sm-3 control-label m-top-5">Role</label>

								<div class="col-sm-9">
									<select name="role" class="form-control role user-role-selection" id="role"  tabindex="9">
										<?php foreach ($roles as $key => $value): ?>
											<option value="<?php echo $value->role_id.'|'.$value->role_types; ?>"><?php echo $value->role_types; ?></option>
										<?php endforeach; ?>
									</select>

									<?php $role = ($this->input->post('role') != '' ? $this->input->post('role') : ''); ?>
									<script type="text/javascript">$('.role').val('<?php echo $user->role_id.'|'.$user->role_types; ?>');</script>

								</div>
							</div>


							<?php if( $this->session->userdata('is_admin') ==  1  ): ?>
								<div class="col-xs-12 m-bottom-10 clearfix">
									<label for="is_admin" class="col-sm-3 control-label m-top-5">Is Admin</label>
									<div class="col-sm-9">
										<input type="checkbox" name="" class="check-swtich is_admin" id="is_admin" data-label-text="Admin" <?php echo ($is_admin_set == 1 ? 'checked="true"' : ''); ?> >
										<input type="hidden" class="" id="chk_is_peon" name="chk_is_peon" value="<?php echo $is_admin_set; ?>">
									</div>
								</div>
							<?php else: ?>
								<input type="hidden" class="hide" readonly="true" id="chk_is_peon" name="chk_is_peon" value="<?php echo $is_admin_set; ?>">

							<?php endif; ?>


							<?php $bulletin_board_set = $user_access_arr['11'];  ?>

							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-3 control-label m-top-5">Bulletin Board</label>											 
								<div class="col-sm-9">										
									<div class="bulletin_board">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="View" <?php echo ($bulletin_board_set >= 1 ? 'checked="true"' : ''); ?>>
										<input type="checkbox" class="check-swtich check-b" data-checkbox="2" data-label-text="+Edit" <?php echo ($bulletin_board_set == 2 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="bulletin_board" name="bulletin_board" value="<?php echo $bulletin_board_set; ?>">
								</div>
							</div>




							<?php $client_supply_set = $user_access_arr['25'];  ?>

							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-3 control-label m-top-5">Client Supply</label>											 
								<div class="col-sm-9">										
									<div class="client_supply">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="View" <?php echo ($client_supply_set >= 1 ? 'checked="true"' : ''); ?>>
										<input type="checkbox" class="check-swtich check-b" data-checkbox="2" data-label-text="+Edit" <?php echo ($client_supply_set == 2 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="client_supply" name="client_supply" value="<?php echo $client_supply_set; ?>">
								</div>
							</div>





							<?php $company_access_set = $user_access_arr['4'];  ?>

							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-3 control-label m-top-5">Company</label>											 
								<div class="col-sm-9">										
									<div class="company_access">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="View" <?php echo ($company_access_set >= 1 ? 'checked="true"' : ''); ?>>
										<input type="checkbox" class="check-swtich check-b" data-checkbox="2" data-label-text="+Edit" <?php echo ($company_access_set == 2 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="company_access" name="company_access" value="<?php echo $company_access_set; ?>">
								</div>
							</div>

<!-- For Company Project -->

							<?php $company_project_set = $user_access_arr['15'];  ?>
							
							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-4 control-label m-top-5">Company Project</label>											 
								<div class="col-sm-8">										
									<div class="company_project">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="Enabled" <?php echo ($company_project_set >= 1 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="company_project" name="company_project" value="<?php echo $company_project_set; ?>">
								</div>
							</div>

<!-- For Company Project -->

							<?php $dashboard_access_set = $user_access_arr['3'];  ?>

							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-3 control-label m-top-5">Dashboard</label>											 
								<div class="col-sm-9">										
									<div class="dashboard_access">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="View" <?php echo ($dashboard_access_set >= 1 ? 'checked="true"' : ''); ?>>
										<input type="checkbox" class="check-swtich check-b" data-checkbox="2" data-label-text="+Edit" <?php echo ($dashboard_access_set == 2 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="dashboard_access" name="dashboard_access" value="<?php echo $dashboard_access_set; ?>">
								</div>
							</div>

							<?php $incident_report_access_set = $user_access_arr['26'];  ?>

							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-3 control-label m-top-5">Incident Report</label>											 
								<div class="col-sm-9">										
									<div class="incident_report_access">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="View" <?php echo ($incident_report_access_set >= 1 ? 'checked="true"' : ''); ?>>
										<input type="checkbox" class="check-swtich check-b" data-checkbox="2" data-label-text="+Edit" <?php echo ($incident_report_access_set == 2 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="incident_report_access" name="incident_report_access" value="<?php echo $incident_report_access_set; ?>">
								</div>
							</div>

							<?php $invoice_access_set = $user_access_arr['8'];  ?>

							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-3 control-label m-top-5">Invoice</label>											 
								<div class="col-sm-9">										
									<div class="invoice_access">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="View" <?php echo ($invoice_access_set >= 1 ? 'checked="true"' : ''); ?>>
										<input type="checkbox" class="check-swtich check-b" data-checkbox="2" data-label-text="+Edit" <?php echo ($invoice_access_set == 2 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="invoice_access" name="invoice_access" value="<?php echo $invoice_access_set; ?>">
								</div>
							</div>

							<?php $job_date_access = $user_access_arr['20'];  ?> 
							
							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-4 control-label m-top-5">Job Date</label>											 
								<div class="col-sm-8">										
									<div class="job_date_access">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="Have Control" <?php echo ($job_date_access == 1 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="job_date_access" name="job_date_access" value="<?php echo $job_date_access; ?>">
								</div>
							</div>

							<?php $labour_schedule_set = $user_access_arr['14'];  ?>
							
							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-3 control-label m-top-5">Labour Schedule</label>											 
								<div class="col-sm-9">										
									<div class="labour_schedule">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="View" <?php echo ($labour_schedule_set >= 1 ? 'checked="true"' : ''); ?>>
										<input type="checkbox" class="check-swtich check-b" data-checkbox="2" data-label-text="+Edit" <?php echo ($labour_schedule_set == 2 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="labour_schedule" name="labour_schedule" value="<?php echo $labour_schedule_set; ?>">
								</div>
							</div>

							<?php $leave_requests = $user_access_arr['19'];  ?> 
							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-4 control-label m-top-5">Leave Requests</label>											 
								<div class="col-sm-8">										
									<div class="leave_requests">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="Have Access" <?php echo ($leave_requests == 1? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="leave_requests" name="leave_requests" value="<?php echo $leave_requests; ?>">
								</div>
							</div>

							<?php $onboarding_set = $user_access_arr['24'];  ?> 
							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-4 control-label m-top-5">Onboarding</label>											 
								<div class="col-sm-8">										
									<div class="onboarding_set">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="Have Access" <?php echo ($onboarding_set == 1? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="onboarding_set" name="onboarding_set" value="<?php echo $onboarding_set; ?>">
								</div>
							</div>

							<?php $progress_report_set = $user_access_arr['22'];  ?> 
							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-4 control-label m-top-5">Progress Report</label>											 
								<div class="col-sm-8">										
									<div class="progress_report_set">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="Have Access" <?php echo ($progress_report_set == 1? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="progress_report_set" name="progress_report_set" value="<?php echo $progress_report_set; ?>">
								</div>
							</div>

							<?php $projects_access_set = $user_access_arr['5'];  ?>

							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-3 control-label m-top-5">Projects</label>											 
								<div class="col-sm-9">										
									<div class="projects_access">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="View" <?php echo ($projects_access_set >= 1 ? 'checked="true"' : ''); ?>>
										<input type="checkbox" class="check-swtich check-b" data-checkbox="2" data-label-text="+Edit" <?php echo ($projects_access_set == 2 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="projects_access" name="projects_access" value="<?php echo $projects_access_set; ?>">
								</div>
							</div>

							<?php $project_schedule_set = $user_access_arr['12'];  ?>

							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-3 control-label m-top-5">Project Schedule</label>											 
								<div class="col-sm-9">										
									<div class="project_schedule">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="View" <?php echo ($project_schedule_set >= 1 ? 'checked="true"' : ''); ?>>
										<input type="checkbox" class="check-swtich check-b" data-checkbox="2" data-label-text="+Edit" <?php echo ($project_schedule_set == 2 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="project_schedule" name="project_schedule" value="<?php echo $project_schedule_set; ?>">
								</div>
							</div>

							<?php $purchase_order_access = $user_access_arr['21'];  ?> 
							
							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-4 control-label m-top-5">Purchase Order</label>											 
								<div class="col-sm-8  tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="Enables user to Reconcile a PO to a Contractor with incomplete insurance.">										
									<div class="purchase_order_access">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="Have Control" <?php echo ($purchase_order_access == 1 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="purchase_order_access" name="purchase_order_access" value="<?php echo $purchase_order_access; ?>">
								</div>
							</div>

							<?php $purchase_orders_access_set = $user_access_arr['7'];  ?>

							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-3 control-label m-top-5">Purchase Orders</label>											 
								<div class="col-sm-9">										
									<div class="purchase_orders_access">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="View" <?php echo ($purchase_orders_access_set >= 1 ? 'checked="true"' : ''); ?>>
										<input type="checkbox" class="check-swtich check-b" data-checkbox="2" data-label-text="+Edit" <?php echo ($purchase_orders_access_set == 2 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="purchase_orders_access" name="purchase_orders_access" value="<?php echo $purchase_orders_access_set; ?>">
								</div>
							</div>

							<?php $quick_quote_set = $user_access_arr['17'];  ?> 
							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-4 control-label m-top-5">Quick Quote</label>											 
								<div class="col-sm-8">										
									<div class="quick_quote">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="Have Access" <?php echo ($quick_quote_set == 1? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="quick_quote" name="quick_quote" value="<?php echo $quick_quote_set; ?>">
								</div>
							</div>

							<?php $quote_deadline = $user_access_arr['18'];  ?> 
							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-4 control-label m-top-5">Quote Deadline</label>											 
								<div class="col-sm-8">										
									<div class="quote_deadline">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="Have Access" <?php echo ($quote_deadline == 1? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="quote_deadline" name="quote_deadline" value="<?php echo $quote_deadline; ?>">
								</div>
							</div>

							<?php $shopping_center_set = $user_access_arr['13'];  ?>
							
							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-3 control-label m-top-5">Shopping Centre</label>											 
								<div class="col-sm-9">										
									<div class="shopping_center">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="View" <?php echo ($shopping_center_set >= 1 ? 'checked="true"' : ''); ?>>
										<input type="checkbox" class="check-swtich check-b" data-checkbox="2" data-label-text="+Edit" <?php echo ($shopping_center_set == 2 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="shopping_center" name="shopping_center" value="<?php echo $shopping_center_set; ?>">
								</div>
							</div>
<!-- For Site Labour -->

							<?php $site_labour_set = $user_access_arr['16'];  ?>
							
							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-3 control-label m-top-5">Site Labour</label>											 
								<div class="col-sm-9">										
									<div class="site_labour">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="View" <?php echo ($site_labour_set >= 1 ? 'checked="true"' : ''); ?>>
										<input type="checkbox" class="check-swtich check-b" data-checkbox="2" data-label-text="+Edit" <?php echo ($site_labour_set == 2 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="site_labour" name="site_labour" value="<?php echo $site_labour_set; ?>">
								</div>
							</div>
	

							<?php $site_labour_app_set = $user->site_access;?>
							
							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-4 control-label m-top-5">Sojourn App</label>											 
								<div class="col-sm-8">										
									<div class="site_labour_app">
										<input type="checkbox" class="check-swtich check-a" onchange="myFunction()" id = "sojourn_app" data-checkbox="2" data-label-text="Have Access" <?php echo ($site_labour_app_set == 2 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="site_labour_app" name="site_labour_app" value="<?php echo $site_labour_app_set; ?>">
								</div>
								<script type="text/javascript">
									function myFunction(){
										var x = document.getElementById("sojourn_app").checked;
										if(x == true){
											$("#sojourn_app_access").modal("show");
										}
									}

									function checkall(){
										var x = document.getElementById("checkAll").checked;
										if(x == true){
											$(".checkSingle").each(function() {
								                this.checked=true;
								            });

								            $('#chk_travel_access').prop('checked', true);
								            $("#show_plate_no").show();
										}else{
											$(".checkSingle").each(function() {
								                this.checked=false;
								            });

								             $('#chk_travel_access').prop('checked', false);
								            $("#show_plate_no").hide();
								            $("#plate_no").val("");
										}
									}

									$(document).ready(function() {
										var travel_access = '<?php echo $travel_access ?>';
										if(travel_access == 1){
											$("#show_plate_no").show();
										}else{
											$("#show_plate_no").hide();
										}

										var baseurl = '<?php echo base_url() ?>';
										$("#save_app_access").click(function(){
											var x = document.getElementById("checkAll").checked;
											var access = "";
											var sl = "0";
											var pr = "0";
											var ind = "0";
											if(x == true){
												access = '0';
											}else{
												var opt_sl = document.getElementById("sl").checked;
												if(opt_sl == true){
													sl = "1";
												}
												var opt_pr = document.getElementById("pr").checked;
												if(opt_pr == true){
													pr = "1";
												}
												var opt_ind = document.getElementById("ind").checked;
												if(opt_ind == true){
													ind = "1";
												}
											}
											
											var user_id = "<?php echo $user_id; ?>";
											$.post(baseurl+"users/save_app_access",
									        {
									        	access: access,
									        	user_id: user_id,
									        	sl: sl,
												pr: pr,
												ind: ind
									        },
									        function(result){
									        	alert("App Access Updated");
									        }); 

									        var has_travel_access = 0;
											var plate_no = "";
											if($("#chk_travel_access"). prop("checked") == true){
												has_travel_access = 1;
									        	plate_no = $("#plate_no").val();
											}

									        $.post(baseurl+"users/save_app_travel_access",
									        {
									        	user_id: user_id,
									        	has_travel_access: has_travel_access,
												plate_no: plate_no
									        },
									        function(result){
									        }); 

									        $("#sojourn_app_access").modal("hide");
										});

										$("#chk_travel_access").click(function(){
											if($("#chk_travel_access"). prop("checked") == true){
												$("#show_plate_no").show();
											}else{
												$("#show_plate_no").hide();
												$("#plate_no").val("");
											}

										});

										$("a.view_access_link").click(function(){
											$("#sojourn_app_access").modal("show");
											return false;
										});
									});
								</script>
								<?php if($site_labour_app_set == 2 ): ?>
								<label class="col-sm-4 control-label m-top-5 view_access_link"></label>	
								<div class="col-sm-8"><a href="#" class = "view_access_link">View App Access</a></div>
								<?php endif; ?>
								<!-- Modal -->
								<div class="modal fade" id="sojourn_app_access" role="dialog">
								    <div class="modal-dialog">
								      <!-- Modal content-->
								     	<div class="modal-content">
									        <div class="modal-header">
									          	<button type="button" class="close" data-dismiss="modal">&times;</button>
									          	<h4 class="modal-title">Sojourn App Access</h4>
									        </div>
								       		<div class="modal-body row" style = "padding: 10px">
								       			<div class="col-sm-2"></div>
								       			<div class="col-sm-8">
								       				<?php 
								       					$all_access = 0;
								       					$sl=0;
								       					$pr=0;
								       					$ind=0;
								       					$access_arr = explode("/", $access);
								       					$count = count($access_arr);
								       					$x = 1;
								       					while($x < $count){
								       						$access = $access_arr[$x];
								       						switch($access){
								       							case 1:
								       								$sl=1;
								       								break;
								       							case 2:
								       								$pr=1;
								       								break;
								       							case 3:
								       								$ind=1;
								       								break;
								       							case 0:
								       								$all_access = 1;
								       								break;
								       						}
								       						$x++;
								       					}
								       				?>
								       				<table class="table" style = "border: 1px solid #888">
													    <thead class="thead-dark">
													      	<tr>
														        <th>
														        	<?php if($all_access == 1): ?>
														        	<input type = "checkbox" id = "checkAll" onchange="checkall()" checked="">
														        	<?php else: ?>
														        	<input type = "checkbox" id = "checkAll" onchange="checkall()">
														        	<?php endif; ?>
														        </th>
									       						<th>All Access</th>
													      	</tr>
													    </thead>
													    <tbody>
													      	<tr>
														        <td>
														        <?php if($sl == 1 || $all_access == 1): ?>
														        	<input type = "checkbox" name = "checkAll" class = "checkSingle" id = "sl" checked="">
														        <?php else: ?>
														        	<input type = "checkbox" name = "checkAll" class = "checkSingle" id = "sl">
														        <?php endif; ?>
														        </td>
										       					<td>Site Labour</td>
													      	</tr>
													     	<tr>
														        <td>
														        <?php if($pr == 1 || $all_access == 1): ?>
														        	<input type = "checkbox" name = "checkAll" class = "checkSingle" id = "pr" checked="">
														        <?php else: ?>
														        	<input type = "checkbox" name = "checkAll" class = "checkSingle" id = "pr">
														        <?php endif; ?>
														        </td>
									       						<td>Progress Report</td>
													      	</tr>
													      	<tr>
														        <td>
														        <?php if($ind == 1 || $all_access == 1): ?>
														        	<input type = "checkbox" name = "checkAll" class = "checkSingle" id = "ind" checked="">
														        <?php else: ?>
														        	<input type = "checkbox" name = "checkAll" class = "checkSingle" id = "ind">
														        <?php endif; ?>
														        </td>
									       						<td>Induction</td>
													      	</tr>
													      	<tr>
													      		<td><input type = "checkbox" name = "chk_travel_access" id = "chk_travel_access" <?php if($travel_access == 1): ?> checked="" <?php endif; ?>></td>
													      		<td>Has Travel Access</td>
													      	</tr>
													      	<tr id = "show_plate_no">
													      		<td colspan = 2>
													      			<label style = "font-size: 10px">Plate No.:</label>
													      			<input type = "text" id = "plate_no" class = "form-control input-sm" placeholder = "Plate No." value = "<?php echo $plate_no ?>">
													      		</td>
													      	</tr>
													    </tbody>
													</table>
								       			</div>
								            </div>  
								            <div class="modal-footer">
								            	<button type = "button" class = "btn btn-success" id = "save_app_access">Save</button>
									        </div>   
								      	</div>
								    </div>
								</div>
								<!-- Modal -->
							</div>

<!-- For Site Labour -->
							<?php $users_access_set = $user_access_arr['9'];  ?>

							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-3 control-label m-top-5">Users</label>											 
								<div class="col-sm-9">										
									<div class="users_access">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="View" <?php echo ($users_access_set >= 1 ? 'checked="true"' : ''); ?>>
										<input type="checkbox" class="check-swtich check-b" data-checkbox="2" data-label-text="+Edit" <?php echo ($users_access_set == 2 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="users_access" name="users_access" value="<?php echo $users_access_set; ?>">
								</div>
							</div>

							<?php $wip_access_set = $user_access_arr['6'];  ?>

							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-3 control-label m-top-5">WIP</label>											 
								<div class="col-sm-9">										
									<div class="wip_access">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="View" <?php echo ($wip_access_set >= 1 ? 'checked="true"' : ''); ?>>
										<input type="checkbox" class="check-swtich check-b" data-checkbox="2" data-label-text="+Edit" <?php echo ($wip_access_set == 2 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="wip_access" name="wip_access" value="<?php echo $wip_access_set; ?>">
								</div>
							</div>

							<div class="clearfix"></div>
							<input type="submit" class="btn btn-success m-left-10  m-top-10 m-bottom-10" name="update_user_access" value="Update User Access">
						</div>
</div>


						<div class="clearfix"></div>
					 


					
						</form>


























						<?php if($user->department_id == 1): ?>
						<div class="box">
							<div class="box-head pad-5">
								<label><i class="fa fa-users fa-lg"></i> Company Director</label>
							</div>
							<div class="box-area   pad-10">

								<div class="box-content box-list ">
 
									<form method="post" action="../update_company_director" class="clearfix">

										<?php $comp = explode(',', $direct_company); ?>

 										<input type="hidden" name="user_id" class="user_data_id" value="<?php echo $user_id; ?>">

										<select name="fcompd[]" multiple="" style="width: 100%; margin-bottom: 10px;">
											 

 
											<?php foreach ($focus as $key => $value): ?>

												 

													<option <?php echo (in_array($value->company_id, $comp) ? 'selected' : ''); ?> value="<?php echo $value->company_id; ?>"> <?php echo $value->company_name; ?></option>

												 
											<?php endforeach; ?>

										</select>

										<input type="reset" class="btn btn-warning pull-left"  value="Reset">

										<input type="submit" class="btn btn-primary pull-right" name="set_company_director" value="Set Company">
								 
									</form>

								</div>
							</div>
						</div>
						<?php endif; ?>



						<?php endif; ?>



						<div class="box m-bottom-15">
							<div class="box-head pad-5"> 
								<label style="margin: 10px 0 0 10px;"><i class="fa fa-map-marker fa-lg"></i> Projects View</label>


								<form method="post" action="../update_projects_default_view" class="clearfix pull-right" style="margin: 0;">
									
									<input type="submit" value="Set"  class="m-left-5 pull-right btn btn-success">
									<select name="projects_load_view" class="form-control input-sx select_table_status"  style=" float: right;    width: 173px;    margin: 0;">                      
										<option value="0">View All</option>
										<option value="1" >WIP</option>
										<option value="2">Quotes</option>
										<option value="3">Unaccepted Quotes</option>
										<option value="4">Invoiced</option>
										<option value="5">Paid</option>
									</select>
 									<input type="hidden" name="user_id" class="user_data_id " value="<?php echo $user_id; ?>">

								</form>

							</div>
						</div>
						<?php echo '<script type="text/javascript">$("select.select_table_status").val("'.$user->projects_load_view.'"); </script>'; ?>

						<!-- Site Staff start -->
						<?php if($user->is_site_staff == '1'): ?>
						<div id="lctApp">
							<div class="panel-group" role="tablist">
								<div class="panel panel-default">
									<div class="panel-heading" role="tab" id="collapseListGroupHeading1">
										<h4 class="panel-title">
											<a class="collapsed" role="button" data-toggle="collapse" href="#collapseListSiteStaff" aria-expanded="false" aria-controls="collapseListSiteStaff">
												<i class="fa fa-certificate fa-lg"></i> Licenses / Certificates / Training
											</a>
										</h4>
									</div>
									<div id="collapseListSiteStaff" class="panel-collapse collapse" role="tabpanel" aria-labelledby="collapseListGroupHeading1" aria-expanded="false" style="height: 0px;">
										<div class="col-sm-12 pad-10">
											<div class="col-sm-12 pad-5">
												<div class="col-sm-6"><b style = "font-size: 18px">Licenses </b></div><div class = "col-sm-6"><span class="badge alert-info pointer pull-right" title = "Add Licenses" v-on:click="showAddLicenses()"><i class="fa fa-plus-circle"></i></span></div>
												<div class="col-sm-12">
													<ul   v-for="license_certificates in license_certificates" v-if="license_certificates.user_id == <?php echo $user->user_id ?>&& license_certificates.is_license == '1' ">
		                        						<li>
		                        							<p><a href="" onclick="return false" v-on:click = "showUpdateLicenceCert(license_certificates.user_license_certificates_id)">{{ license_certificates.type}}</a></p>
		                        							<p>{{"( "+license_certificates.number +" )"}}</p>
		                        							<p>Expiration: {{ license_certificates.expiration_date | ausdate }}</p>
		                        						</li>
		                      						</ul>
												</div>
												
											</div>
											<div class="clearfix"></div>
											<div class="col-sm-12 pad-5">
												<div class="col-sm-6"><b style = "font-size: 18px">Certificates</b></div><div class = "col-sm-6"><span class="badge alert-info pointer pull-right" title = "Add Certificates" v-on:click="showAddLicenses()"><i class="fa fa-plus-circle"></i></span></div>
												<div class="col-sm-12">
													<ul   v-for="license_certificates in license_certificates" v-if="license_certificates.user_id == <?php echo $user->user_id ?>&& license_certificates.is_license == '0' ">
		                        						<li>
		                        							<p><a href="" onclick="return false" v-on:click = "showUpdateLicenceCert(license_certificates.user_license_certificates_id)">{{ license_certificates.type}}</a></p>
		                        							<p>{{"( "+license_certificates.number +" )"}}</p>
		                        							<p>Expiration: {{ license_certificates.expiration_date | ausdate }}</p>
		                        						</li>
		                      						</ul>
		                      					</div>
											</div>
											<div class="clearfix"></div>
											<div class="col-sm-12 pad-5">
												<div class="col-sm-6"><b style = "font-size: 18px">Training</b></div><div class = "col-sm-6"><span class="badge alert-info pointer pull-right" title = "Add Training" v-on:click="showAddTraining()"><i class="fa fa-plus-circle"></i></span></div>
												<div class="col-sm-12">
													<ul v-for="training_records in training_records" v-if="training_records.user_id == <?php echo $user->user_id ?> " style = "font-size: 12px" >
		                          						<li><p style = "font-size: 12px"><a href="" onclick="return false" v-on:click = "showUpdateTraining(training_records.training_records_id)">{{ training_records.training_type}}</a></p>
		                          							<p>{{ training_records.date_undertaken | ausdate }}</p>
		                          							<p>{{ training_records.taken_with }}</p>
		                          						</li>
		                      						</ul>	
		                      					</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="panel-group" role="tablist">
								<div class="panel panel-default">
									<div class="panel-heading" role="tab" id="collapseListGroupHeading1">
										<h4 class="panel-title">
											<a class="collapsed" role="button" data-toggle="collapse" href="#collapseListEmergency" aria-expanded="false" aria-controls="collapseListEmergency">
												<i class="fa fa-phone fa-lg"></i> Emergency Contacts
												<span class="badge alert-success pointer pull-right" title = "Add Emergency Contact Details" v-on:click="showAddEmergenceContacts()"><i class="fa fa-plus-circle"></i></span>
											</a>
										</h4>
									</div>
									<div id="collapseListEmergency" class="panel-collapse collapse" role="tabpanel" aria-labelledby="collapseListGroupHeading1" aria-expanded="false" style="height: 0px;">
										<div class="col-sm-12 pad-10">
											<div class="col-sm-12 pad-5">
												<ul  v-for="emergency_contatacts in emergency_contatacts" v-if="emergency_contatacts.user_id == <?php echo $user->user_id ?>">
								                    <li>
								                     	<p><b style = "font-size: 12px"><a href="" onclick="return false" v-on:click = "showUpdateEmergencyContacts(emergency_contatacts.sitestaff_emergency_contacts_id)">{{ emergency_contatacts.contact_fname+" "+emergency_contatacts.contact_sname }}</a></b></p>
								                       	<p>Relation: {{ emergency_contatacts.relation }}</p>
								                       	<p>Contact Numbers: {{ emergency_contatacts.contacts }}</p>
								                    </li>
								                </ul>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- Modal -->
							<div class="modal fade" id="add_license_cert" role="dialog">
							    <div class="modal-dialog">
							      <!-- Modal content-->
							     	<div class="modal-content">
								        <div class="modal-header">
								          	<button type="button" class="close" data-dismiss="modal">&times;</button>
								          	<h4 class="modal-title">Licences / Certificates</h4>
								        </div>
							        
							        <div class="modal-body row">
							                    
							          	<div class="col-sm-4 pad-5">Select*: </div>
							          		<div class="col-sm-8 pad-5">
									            <SELECT class = "form-control input-sm" name = "LCtype" name = "LCtype" v-model = "LCtype">
									              	<option value="1">Licence</option>
									              	<option value="0">Certificates </option>
									            </SELECT>
							          		</div>
							          		<div class="clearfix"></div>
							          		<div class="col-sm-4 pad-5">Type*:</div>
							          		<div class="col-sm-8 pad-5">
									            <SELECT class = "form-control input-sm" name = "LCName" name = "LCName" v-model = "LCName" v-on:change = "typeChange">
									              <option v-for = "licenseCertTypes in licenseCertTypes" :value="licenseCertTypes.lc_type_name">{{ licenseCertTypes.lc_type_name }}</option>
									              <option value = "0">Other</option>
									            </SELECT>
							          		</div>
							          
							          		<div class="clearfix" v-if = "showLCTypeName"></div>
							          		<div class="col-sm-4 pad-5" v-if = "showLCTypeName">Enter Type Name*:</div>
							          		<div class="col-sm-8 pad-5" v-if = "showLCTypeName">
							            		<input type = "text" class = "form-control input-sm" v-model = "LCTypeName">
							          		</div>

							          		<div class="clearfix"></div>
							          		<div class="col-sm-4 pad-5">Licences/Certificate Number*: </div>
							          		<div class="col-sm-8 pad-5"><input type = "text" class = "form-control input-sm" name = "lcNumber" v-model = "lcNumber"></div>
							          		<div class="clearfix"></div>
							          		<div class="col-sm-4 pad-5">Expiration Date (if applicable)</div>
							          		<div class="col-sm-8 pad-5"><input type = "date" class = "form-control input-sm" name = "expirationDate" v-model = "expirationDate"></div>
							        	</div>
								        <div class="modal-footer">
								          	<button type="button" class="btn btn-default btn-success" v-on:click= "addLC" data-dismiss="modal">Add</button>
								          	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								        </div>
							      	</div>
							    </div>
							</div>

							<div class="modal fade" id="update_license_cert" role="dialog">
							    <div class="modal-dialog">
							      	<!-- Modal content-->
							      	<div class="modal-content">
								        <div class="modal-header">
								          	<button type="button" class="close" data-dismiss="modal">&times;</button>
								          	<h4 class="modal-title">Licences / Certificates</h4>
								        </div>
							        
							        	<div class="modal-body row">
							                    
							          		<div class="col-sm-4 pad-5">Select*: </div>
							          		<div class="col-sm-8 pad-5">
									            <SELECT class = "form-control input-sm" name = "LCtype" name = "LCtype" v-model = "LCtype">
									              	<option value="1">Licence</option>
									              	<option value="0">Certificates </option>
									            </SELECT>
							          		</div>
							          		<div class="clearfix"></div>
							          		<div class="col-sm-4 pad-5">Type*:</div>
							          		<div class="col-sm-8 pad-5">
									            <SELECT class = "form-control input-sm" name = "LCName" name = "LCName" v-model = "LCName">
									              	<option v-for = "licenseCertTypes in licenseCertTypes" :value="licenseCertTypes.lc_type_name">{{ licenseCertTypes.lc_type_name }}</option>
									              	<option value = "0">Other</option>
									            </SELECT>
							          		</div>
							          		<div class="clearfix" v-if = "showLCTypeName"></div>
							          		<div class="col-sm-4 pad-5" v-if = "showLCTypeName">Type*:</div>
							          		<div class="col-sm-8 pad-5" v-if = "showLCTypeName">
							            		<input type = "text" class = "form-control input-sm" v-model = "LCTypeName">
							          		</div>
							          
							          		<div class="clearfix"></div>
							          		<div class="col-sm-4 pad-5">Licences/Certificate Number*: </div>
							          		<div class="col-sm-8 pad-5"><input type = "text" class = "form-control input-sm" name = "lcNumber"  v-model = "lcNumber"></div>
							          		<div class="clearfix"></div>
							          		<div class="col-sm-4 pad-5">Expiration Date (if applicable)</div>
							          		<div class="col-sm-8 pad-5"><input type = "date" class = "form-control input-sm" name = "expirationDate" v-model = "expirationDate"></div>
							        	</div>
								        <div class="modal-footer">
								          	<button type="button" class="btn btn-default btn-success" v-on:click= "updateLC" data-dismiss="modal">Update</button>
								          	<button type="button" class="btn btn-default btn-danger pull-left" v-on:click= "removeLC" data-dismiss="modal">Remove</button>
								          	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								        </div>
							      	</div>
							    </div>
						  	</div>

						  	<div class="modal fade" id="add_training_cert" role="dialog">
							    <div class="modal-dialog">
							      <!-- Modal content-->
							      	<div class="modal-content">
								        <div class="modal-header">
								          	<button type="button" class="close" data-dismiss="modal">&times;</button>
								          	<h4 class="modal-title">Training Records</h4>
								        </div>
								        
								        <div class="modal-body row">
								          	<div class="col-sm-4 pad-5">Training: </div>
								          	<div class="col-sm-8 pad-5"><input type = "text" class = "form-control input-sm" name = "trainingName" v-model = "trainingName"></div>
								          	<div class="clearfix"></div>
								          	<div class="col-sm-4 pad-5">Training Date:</div>
								          	<div class="col-sm-8 pad-5"><input type="date" class = "form-control input-sm" name = "trainingDate" v-model = "trainingDate"></div>
								          	<div class="clearfix"></div>
								          	<div class="col-sm-4 pad-5">Training location: </div>
								          	<div class="col-sm-8 pad-5"><input type = "text" class = "form-control input-sm" name = "trainingLoc"  v-model = "trainingLoc"></div>
								        </div>
								        <div class="modal-footer">
								          	<button type="button" class="btn btn-default btn-success" v-on:click= "addTraining" data-dismiss="modal">Add</button>
								          	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								        </div>
							      	</div>
							        
							    </div>
						  	</div>

						  	<div class="modal fade" id="update_training_cert" role="dialog">
							    <div class="modal-dialog">
							      <!-- Modal content-->
							      	<div class="modal-content">
								        <div class="modal-header">
								          	<button type="button" class="close" data-dismiss="modal">&times;</button>
								          	<h4 class="modal-title">Training Records</h4>
								        </div>
								        
								        <div class="modal-body row">
								                    
								          	<div class="col-sm-4 pad-5">Training: </div>
								          	<div class="col-sm-8 pad-5"><input type = "text" class = "form-control input-sm" name = "trainingName" v-model = "trainingName"></div>
								          	<div class="clearfix"></div>
								          	<div class="col-sm-4 pad-5">Training Date:</div>
								          	<div class="col-sm-8 pad-5"><input type="date" class = "form-control input-sm" name = "trainingDate" v-model = "trainingDate"></div>
								          	<div class="clearfix"></div>
								          	<div class="col-sm-4 pad-5">Training location: </div>
								          	<div class="col-sm-8 pad-5"><input type = "text" class = "form-control input-sm" name = "trainingLoc"  v-model = "trainingLoc"></div>
								        </div>
								        <div class="modal-footer">
								          	<button type="button" class="btn btn-default btn-success" v-on:click= "updateTraining" data-dismiss="modal">Update</button>
								          	<button type="button" class="btn btn-default btn-danger pull-left" v-on:click= "removeTraining" data-dismiss="modal">Remove</button>
								          	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								        </div>
							      	</div>
							        
							    </div>
						  	</div>

						  	<div class="modal fade" id="add_emergency_contact" role="dialog">
							    <div class="modal-dialog">
							      <!-- Modal content-->
							      	<div class="modal-content">
								        <div class="modal-header">
								          	<button type="button" class="close" data-dismiss="modal">&times;</button>
								          	<h4 class="modal-title">Emergency Contacts</h4>
								        </div>
								        <!-- <form @submit="checkAddECForm" action="<?php echo base_url() ?>induction_health_safety/add_emergency_contact" method="post"> -->
								        <div class="modal-body row">
								          	<div class="col-sm-3 pad-5">First Name</div>
								          	<div class="col-sm-9 pad-5"><input type = "text" class = "form-control input-sm" name = "ecFName" name = "ecFName" v-model = "ecFName"></div>
								          	<div class="col-sm-3 pad-5">Last Name</div>
								          	<div class="col-sm-9 pad-5"><input type = "text" class = "form-control input-sm" name = "ecSName" v-model = "ecSName"></div>
								          	<div class="col-sm-3 pad-5">Relation</div>
								          	<div class="col-sm-9 pad-5"><input type = "text" class = "form-control input-sm" name = "ecRelation" v-model = "ecRelation"></div>
								          	<div class="col-sm-3 pad-5">Contact Numbers</div>
								          	<div class="col-sm-9 pad-5"><input type = "text" class = "form-control input-sm" name = "ecContacts" v-model = "ecContacts" v-on:keyup="formatPhoneNumber"></div>
								        </div>
								        <div class="modal-footer">
								          	<button type="button" class="btn btn-default btn-success" v-on:click="addEmergencyContacts" data-dismiss="modal">Add</button>
								          	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								        </div>
								        <!-- </form> -->
							      	</div>
							        
							    </div>
						  	</div>

						  	<div class="modal fade" id="update_emergency_contact" role="dialog">
							    <div class="modal-dialog">
							      <!-- Modal content-->
							     	<div class="modal-content">
								        <div class="modal-header">
								          	<button type="button" class="close" data-dismiss="modal">&times;</button>
								          	<h4 class="modal-title">Emergency Contacts</h4>
								        </div>
								        <!-- <form @submit="checkAddECForm" action="<?php echo base_url() ?>induction_health_safety/update_emergency_contact" method="post"> -->
								        <div class="modal-body row">
								    
								          <!-- <input type="hidden" name = "sitestaff_emergency_contacts_id" id = "sitestaff_emergency_contacts_id" v-model = "sitestaff_emergency_contacts_id" > -->
								          	<div class="col-sm-3 pad-5">First Name</div>
								          	<div class="col-sm-9 pad-5"><input type = "text" class = "form-control input-sm" name = "ecFName" v-model = "ecFName"></div>
								          	<div class="col-sm-3 pad-5">Last Name</div>
								          	<div class="col-sm-9 pad-5"><input type = "text" class = "form-control input-sm" name = "ecSName" v-model = "ecSName"></div>
								          	<div class="col-sm-3 pad-5">Relation</div>
								          	<div class="col-sm-9 pad-5"><input type = "text" class = "form-control input-sm" name = "ecRelation" v-model = "ecRelation"></div>
								          	<div class="col-sm-3 pad-5">Contact Numbers</div>
								          	<div class="col-sm-9 pad-5"><input type = "text" class = "form-control input-sm" name = "ecContacts" v-model = "ecContacts" v-on:keyup="formatPhoneNumber"></div>
								        </div>
								        <div class="modal-footer">
								          	<input type="button" class="btn btn-default btn-success" v-on:click = "updateEmergencyContacts" name = "update" value = "Update" data-dismiss="modal">
								          	<input type="button" class="btn btn-default btn-danger pull-left" v-on:click = "deleteEmergencyContacts" name = "remove" value = "Remove" data-dismiss="modal">
								          	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								        </div>
								        <!-- </form> -->
							      	</div>
							        
							    </div>
							</div>
						</div>
						<?php endif; ?>
						<!-- Site Staff end-->


						<?php if($user->role_id == 8 || $user->role_id == 3 || $user->role_id == 2 || $user->role_id == 20 || $user->role_id == 7 || $user->role_id ==  16 || $company_project_set ==  1 ): ?>
						<div class="box m-bottom-15">
							<div class="box-head pad-5"> 
								<label style="margin: 10px 0 0 10px;"><i class="fa fa-map-marker fa-lg"></i> See Personal Project First</label>


								<form method="post" action="../update_projects_pv" class="clearfix pull-right" style="margin: 0;">
									
									<input type="submit" value="Set"  class="m-left-5 pull-right btn btn-success">
									<select name="projects_load_view_personal" class="form-control input-sx select_table_vpp"  style=" float: right;    width: 100px;    margin: 0;">                      
										<option value="0">No</option>
										<option value="1" >Yes</option>
									</select>
									<input type="hidden" name="user_id" class="user_data_id " value="<?php echo $user_id; ?>">

								</form>

							</div>
						</div>

						<?php echo '<script type="text/javascript">$("select.select_table_vpp").val("'.$user->projects_load_view_personal.'"); </script>'; ?>
					<?php endif; ?>
</div>

						
						
					</div>
					
					
					
				</div>				
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('assets/logout-modal'); ?>

<script type="text/javascript">
	var baseurl = '<?php echo base_url() ?>'; //$("#base_url").val(); 
	

	var app = new Vue({
      	el: '#lctApp',
      	data: {       
        	emergency_contatacts: [],
        	license_certificates: [],
        	training_records: [],
        	licenseCertTypes: [],

        	showLCTypeName: false,
        	is_contractors: null,
			LCtype: null,
			LCName: null,
			LCTypeName: null,
			lcNumber: null,
			expirationDate: null,

			trainingName: null,
			trainingDate: null,
			trainingLoc: null,

			ecFName: null,
			ecSName: null,
			ecRelation: null,
			ecContacts: null,
      	},

      	filters: {
        	getDayname: function(date){
            	return moment(date).format('ddd');
          	},
          	ausdate: function(date) {
            	return moment(date).format('DD/MM/YYYY');
          	},
          	getTime: function(date){
            	return moment(date).format('h:m a');
          	}
      	},

      	mounted: function(){
        	this.fetchUserEmergencyContacts();
        	this.fetchUserLicencesCertificates();
        	this.fetchTrainingRecords();
        	this.fetchLicenseCertType();
      	},

      	methods: {
      		formatPhoneNumber: function() {
	          //alert(this.ecContacts.length);
	          	if(this.ecContacts.length == 2){
	            	this.ecContacts = this.ecContacts + " ";
	          	}
	          	if(this.ecContacts.length == 7){
	            	this.ecContacts = this.ecContacts + " "; 
	          	}
	        },

	        fetchUserEmergencyContacts: function(){
	          	$.post(baseurl+"induction_health_safety/fetch_user_emergency_contacts",
	          	{},
	          	function(result){
	            	app.emergency_contatacts = JSON.parse(result);
	          	}); 
	        },

	        fetchUserLicencesCertificates: function(){
	          $.post(baseurl+"induction_health_safety/fetch_user_licences_certificates",
	          {},
	          function(result){
	            app.license_certificates = JSON.parse(result);
	          }); 
	        },

	        fetchTrainingRecords: function(){
	          $.post(baseurl+"induction_health_safety/fetch_user_training_records",
	          {},
	          function(result){
	            app.training_records = JSON.parse(result);
	          }); 
	        },

	        fetchLicenseCertType: function(){
          		$.post(baseurl+"induction_health_safety/fetch_license_cert_type",
          		{},
          		function(result){
            		app.licenseCertTypes = JSON.parse(result);
          		});
        	},

	        typeChange: function(){
          		if(this.LCName == '0'){
            		this.showLCTypeName = true;
          		}else{
            		this.showLCTypeName = false;
          		}
          
        	},

	        showAddLicenses: function(){
	          	this.is_contractors = 0;
	          	this.LCtype = null;
	          	this.LCName = null;
	          	this.LCTypeName = null;
	          	this.lcNumber = null;
	          	this.expirationDate = null;
	          	$("#add_license_cert").modal("show");
	        },

	        addLC: function(){
	        	var user_id = '<?php echo $user_id ?>';
	          	if(this.showLCTypeName == true){
	            	this.LCName = this.LCTypeName;
	            	$.post(baseurl+"induction_health_safety/insert_lc_type",
	            	{
	              		lctypename: this.LCTypeName
	            	},
	            	function(result){
	              		app.licenseCertTypes = JSON.parse(result);
	              		this.showLCTypeName = false;
	            	}); 
	          	}
	      
	          	var is_contractors = this.is_contractors;
	          	$.post(baseurl+"induction_health_safety/add_licence_cert",
	          	{
	            	is_contractors: this.is_contractors,
	            	user_id: user_id,
	           		LCtype: this.LCtype,
	            	LCName: this.LCName,
	            	lcNumber: this.lcNumber,
	            	expirationDate: this.expirationDate
	          	},
	          	function(result){
	            	app.license_certificates = JSON.parse(result);
	          	}); 
	        },

	        showUpdateLicenceCert: function(user_license_certificates_id){
          		this.showLCTypeName = false;
          		this.user_license_certificates_id = user_license_certificates_id;
          		this.is_contractors = 0;
          		for (var key in app.license_certificates) {
              		if(app.license_certificates[key].user_license_certificates_id == user_license_certificates_id){
	                	this.LCtype = app.license_certificates[key].is_license;
		                this.LCName = app.license_certificates[key].type;
		                this.lcNumber = app.license_certificates[key].number;
		                this.expirationDate = app.license_certificates[key].expiration_date;
              		}
          		}
          		$("#update_license_cert").modal("show");
        	},

        	updateLC: function(){
	          	var is_contractors = this.is_contractors;
	         	 $.post(baseurl+"induction_health_safety/update_licence_cert",
	          	{
		            is_contractors: this.is_contractors,
		            user_license_certificates_id: this.user_license_certificates_id,
		            LCtype: this.LCtype,
		            LCName: this.LCName,
		            lcNumber: this.lcNumber,
		            expirationDate: this.expirationDate
	          	},
	          	function(result){
		            app.license_certificates = JSON.parse(result);
	          	}); 
	        },

	        removeLC: function(){
	          	var is_contractors = this.is_contractors;
	          	$.post(baseurl+"induction_health_safety/remove_licence_cert",
	          	{
		            is_contractors: this.is_contractors,
		            user_license_certificates_id: this.user_license_certificates_id
	          	},
	          	function(result){
		             app.license_certificates = JSON.parse(result);
	          	}); 
	        },

	        showAddTraining: function(user_id){
          		this.is_contractors = 0;
          		this.trainingName = null;
          		this.trainingDate = null;
          		this.trainingLoc = null;
          		$("#add_training_cert").modal('show');
        	},

        	addTraining: function(){
        		var user_id = '<?php echo $user_id ?>';
	          	var is_contractors = this.is_contractors;
	          	$.post(baseurl+"induction_health_safety/add_training",
	          	{
		            is_contractors: this.is_contractors,
		            user_id: user_id,
		            trainingName: this.trainingName,
		            trainingDate: this.trainingDate,
		            trainingLoc: this.trainingLoc
	          	},
	          	function(result){
		            app.training_records = JSON.parse(result);
	          	}); 
	        },

	        showUpdateTraining: function(training_records_id){
	          	this.training_records_id = training_records_id;
	          	this.is_contractors = 0;
	          	for (var key in app.training_records) {
	              	if(app.training_records[key].training_records_id == training_records_id){
	                	this.trainingName = app.training_records[key].training_type;
	                	this.trainingDate = app.training_records[key].date_undertaken;
	                	this.trainingLoc = app.training_records[key].taken_with;
	              	}
	          	}

	          	$("#update_training_cert").modal('show');
	        },

	        updateTraining: function(){
	          	var is_contractors = this.is_contractors;
	          	$.post(baseurl+"induction_health_safety/update_training",
	          	{
		            is_contractors: this.is_contractors,
		            training_records_id: this.training_records_id,
		            trainingName: this.trainingName,
		            trainingDate: this.trainingDate,
		            trainingLoc: this.trainingLoc
	          	},
	          	function(result){
		            app.training_records = JSON.parse(result);
	          	}); 
	        },

	        removeTraining: function(){
	          	var is_contractors = this.is_contractors;

	          	$.post(baseurl+"induction_health_safety/remove_training",
	          	{
		            is_contractors: this.is_contractors,
		            training_records_id: this.training_records_id
	          	},
	          	function(result){
	              	app.training_records = JSON.parse(result);
	          	}); 
	        },

	        showAddEmergenceContacts: function(user_id){
	          	this.is_contractors = 0;
	          	this.user_id = user_id;
	          	this.ecFName = null;
	          	this.ecSName = null;
	          	this.ecRelation = null;
	          	this.ecContacts = null;
	          	$("#add_emergency_contact").modal('show');
	        },

	        addEmergencyContacts: function(){
        		var user_id = '<?php echo $user_id ?>';
	          	var is_contractors = this.is_contractors;
	          	$.post(baseurl+"induction_health_safety/add_emergency_contact",
	          	{
		            is_contractors: this.is_contractors,
		            user_id: user_id,
		            ecFName: this.ecFName,
		            ecSName: this.ecSName,
		            ecRelation: this.ecRelation,
		            ecContacts: this.ecContacts
	          	},
	          	function(result){
		            app.emergency_contatacts = JSON.parse(result);
	          	}); 
	        },

	        showUpdateEmergencyContacts: function(sitestaff_emergency_contacts_id){
	          	this.sitestaff_emergency_contacts_id = sitestaff_emergency_contacts_id;
	          	for (var key in app.emergency_contatacts) {
	              	if(app.emergency_contatacts[key].sitestaff_emergency_contacts_id == sitestaff_emergency_contacts_id){
		                this.user_id = app.emergency_contatacts[key].user_id;
		                this.ecFName = app.emergency_contatacts[key].contact_fname;
		                this.ecSName = app.emergency_contatacts[key].contact_sname;
		                this.ecRelation = app.emergency_contatacts[key].relation;
		                this.ecContacts = app.emergency_contatacts[key].contacts;
		                this.is_contractors = 0;
	              	}
	          	}
		        $("#update_emergency_contact").modal('show');
		        return false;
	        },

	        updateEmergencyContacts: function(){
	          	var is_contractors = this.is_contractors;
	          	var user_id = '<?php echo $user_id ?>';
	          	$.post(baseurl+"induction_health_safety/update_emergency_contact",
	          	{
		            sitestaff_emergency_contacts_id: this.sitestaff_emergency_contacts_id,
		            user_id: user_id,
		            ecFName: this.ecFName,
		            ecSName: this.ecSName,
		            ecRelation: this.ecRelation,
		            ecContacts: this.ecContacts,
		            is_contractors: this.is_contractors
	          	},
	          	function(result){
	            	app.emergency_contatacts = JSON.parse(result);
	          	}); 
	        },
	        
	        deleteEmergencyContacts: function(){
	          	var is_contractors = this.is_contractors;

	          	$.post(baseurl+"induction_health_safety/remove_emergency_contacts",
	          	{
		            sitestaff_emergency_contacts_id: this.sitestaff_emergency_contacts_id,
		            is_contractors: this.is_contractors
	          	},
	          	function(result){
	              	app.emergency_contatacts = JSON.parse(result);
	          	});
	        },

	    }
    });

	function startingAnnualCheck(){
		var annual_manual_entry = $("#annual_manual_entry").val();
		var annual_day_earned = $("#annual_day_earned").text();
		var used_annual = $("#used_annual").text();

		var total_annual = +(annual_manual_entry) + +(annual_day_earned) - used_annual;

		if(total_annual < 0){
			$("#confirmText").html("Error: If you change the Starting Annual Leave to "+annual_manual_entry+", the Total Annual Leave is less than zero. Please change your Starting Annual Leave.");
			$("#confirmButtons").html('<button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>');
			$("#confirmModal").modal('show');

			annual_manual_entry = $("#annual_manual_entry").val("<?php echo (!empty($leave_alloc->annual_manual_entry) ? $leave_alloc->annual_manual_entry : '' ); ?>");

			return false;
		}
	}

	function startingPersonalCheck(){
		var personal_manual_entry = $("#personal_manual_entry").val();
		var personal_day_earned = $("#personal_day_earned").text();
		var used_personal = $("#used_personal").text();

		var total_personal = +(personal_manual_entry) + +(personal_day_earned) - used_personal;

		if(total_personal < 0){
			$("#confirmText").html("Error: If you change the Starting Personal Leave to "+personal_manual_entry+", the Total Personal Leave is less than zero. Please change your Starting Personal Leave.");
			$("#confirmButtons").html('<button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>');
			$("#confirmModal").modal('show');
			
			annual_manual_entry = $("#personal_manual_entry").val("<?php echo (!empty($leave_alloc->personal_manual_entry) ? $leave_alloc->personal_manual_entry : '' ); ?>");

			return false;
		}
	}

	function isNumberKey(evt) {
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode != 46 && charCode > 31 
        && (charCode < 48 || charCode > 57))
         return false;

      return true;
    }

    $('form#add_leave_total').submit( function(e) {
	     e.preventDefault();

		var annual_manual_entry = $("#annual_manual_entry").val();
		var personal_manual_entry = $("#personal_manual_entry").val();

		if (annual_manual_entry != "" && personal_manual_entry != ""){
			//later you decide you want to submit
	     	$(this).unbind('submit').submit()
		} else {
			
	     	$("#confirmText").html('Please fill all the required (*) fields.');
			$("#confirmButtons").html('<button type="button" class="btn btn-warning" data-dismiss="modal">Ok</button>');
			$("#confirmModal").modal('show');

			return false;
		}
	});

	$('form#update_leave_total').submit( function(e) {
		e.preventDefault();

		var annual_manual_entry = $("#annual_manual_entry").val();
		var personal_manual_entry = $("#personal_manual_entry").val();
		var is_admin = "<?php echo $is_admin; ?>";

		if (is_admin == 1){
			if (annual_manual_entry != "" && personal_manual_entry != ""){
				//later you decide you want to submit
		     	$(this).unbind('submit').submit();
			} else {
				
		     	$("#confirmText").html('Please fill all the required (*) fields.');
				$("#confirmButtons").html('<button type="button" class="btn btn-warning" data-dismiss="modal">Ok</button>');
				$("#confirmModal").modal('show');

				return false;
			}
		} else {
			$(this).unbind('submit').submit();
		}
	});

</script>