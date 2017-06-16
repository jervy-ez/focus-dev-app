<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('users'); ?>
<?php $user_id_page = $this->uri->segment(3); ?>
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
							<a href="<?php echo base_url(); ?>admin" class="btn-small">Defaults</a>
						</li>
						<li>
							<a href="<?php echo base_url(); ?>admin/company" class="btn-small">Company</a>
						</li>
					<?php endif; ?>
					<li>
						<a href="<?php echo base_url(); ?>users" class="btn-small">Users</a>
					</li>
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

													<div class="col-xs-12 m-bottom-10 clearfix <?php if(form_error('dob')){ echo 'has-error has-feedback';} ?>">													
														<input type="text" data-date-format="dd/mm/yyyy" placeholder="Date of Birth* DD/MM/YY" class="form-control datepicker" id="dob" name="dob" tabindex="4" value="<?php echo $user->user_date_of_birth; ?>">											
													</div>

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

												<div class="col-md-6 col-sm-4 col-xs-12 m-bottom-10 clearfix <?php if(form_error('focus')){ echo 'has-error has-feedback';} ?>">
													<label for="focus" class="col-sm-3 control-label">Supervisor </label>
													<div class="col-sm-9">

														<select name="supervisor" class="form-control supervisor" id="supervisor" tabindex="10">

															<option value="0">Select Supervisor</option>
															<?php foreach ($user_list as $key => $value): ?>
																<?php if($value->primary_user_id !== $user_id_page): ?>
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

												<input type="hidden" name="department" class="hide" value="<?php echo $user->department_id.'|'.$user->department_name; ?>">
												<input type="hidden" name="focus" class="hide" value="<?php echo $user->company_id.'|'.$user->company_name; ?>">

											<?php endif; ?>







											<div class="col-md-6 col-sm-4 col-xs-12 m-bottom-10 clearfix ">
												<label for="login_name" class="col-sm-3 control-label">Login Name</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="login_name" name="login_name" tabindex="5" placeholder="Login Name" value="<?php echo $user->login_name; ?>" style="text-transform: none;">
												</div>
											</div>


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
													<input type="text" class="form-control direct_landline" id="direct_landline" name="direct_landline" onchange="contact_number_assign('direct_landline')" tabindex="11" placeholder="Direct Landline"  value="<?php echo $user->direct_number; ?>">																										
												</div>
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('after_hours')){ echo 'has-error has-feedback';} ?>">
												<label for="after_hours" class="col-sm-4 control-label">After Hours</label>
												<div class="col-sm-8">

													<div class="input-group ">
														<span class="input-group-addon">+</span>
													<input type="text" class="form-control after_hours" id="after_hours" name="after_hours" onchange="contact_number_assign('after_hours')" tabindex="12" placeholder="After Hours"  value="<?php echo $user->after_hours; ?>">																										
												</div>
												</div>
											</div>
											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
												<label for="mobile_number" class="col-sm-5 control-label">Mobile Number</label>
												<div class="col-sm-7">

													<div class="input-group ">
														<span class="input-group-addon">+</span>
														<input type="text" class="form-control mobile_number" id="mobile_number" name="mobile_number" placeholder="Mobile Number" onchange="mobile_number_assign('mobile_number')"  tabindex="13" value="<?php echo $user->mobile_number; ?>">
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
								
							</div>
						</div>
					</div>				

					</div>	
					<?php if( $this->session->userdata('users') > 1 || $this->session->userdata('user_id') == $user_id  || $this->session->userdata('is_admin') ==  1  ): ?>
					</form>
				<?php endif; ?>



											 

















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
														<li>
															<span> 
																<strong class="" id="">
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
											<form method="post" class="change_password_form" onkeypress="return event.keyCode != 13;">
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
						
						<?php if( ($this->session->userdata('users') > 1 ) || $this->session->userdata('is_admin') ==  1  ): ?>

						<form method="post" action="../update_user_access">
			
 						<?php $user_access_arr = explode(',',  $this->users->get_user_access($user_id) ); ?>

 						<input type="hidden" name="user_id_access" value="<?php echo $user_id; ?>">




						<div class="box clearfix">
							<div class="box-head pad-5">
								<label><a style="font-weight:normal; color: #333; font-size:16px; margin-left:10px;" class="collapsed" role="button" data-toggle="collapse" href="#collapseListGroup3" aria-expanded="false" aria-controls="collapseListGroup3"> 
								<i class="fa fa-unlock-alt fa-lg"></i> Select Access</label>
								</a>
							</div>		



							<?php $is_admin_set = $is_user_admin;  ?>
							

							<div id="collapseListGroup3" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="" aria-expanded="false" style="height: auto;">


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


							<?php $dashboard_access_set = $user_access_arr['3'];  ?>

							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-3 control-label m-top-5">Dashboard</label>											 
								<div class="col-sm-9">										
									<div class="dashboard_access">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="View" <?php echo ($dashboard_access_set >= 1 ? 'checked="true"' : ''); ?>>
										<input type="checkbox" class="check-swtich check-b" data-checkbox="2" data-label-text="+ Edit" <?php echo ($dashboard_access_set == 2 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="dashboard_access" name="dashboard_access" value="<?php echo $dashboard_access_set; ?>">
								</div>
							</div>

							<?php $company_access_set = $user_access_arr['4'];  ?>

							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-3 control-label m-top-5">Company</label>											 
								<div class="col-sm-9">										
									<div class="company_access">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="View" <?php echo ($company_access_set >= 1 ? 'checked="true"' : ''); ?>>
										<input type="checkbox" class="check-swtich check-b" data-checkbox="2" data-label-text="+ Edit" <?php echo ($company_access_set == 2 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="company_access" name="company_access" value="<?php echo $company_access_set; ?>">
								</div>
							</div>

							<?php $projects_access_set = $user_access_arr['5'];  ?>

							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-3 control-label m-top-5">Projects</label>											 
								<div class="col-sm-9">										
									<div class="projects_access">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="View" <?php echo ($projects_access_set >= 1 ? 'checked="true"' : ''); ?>>
										<input type="checkbox" class="check-swtich check-b" data-checkbox="2" data-label-text="+ Edit" <?php echo ($projects_access_set == 2 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="projects_access" name="projects_access" value="<?php echo $projects_access_set; ?>">
								</div>
							</div>

							<?php $wip_access_set = $user_access_arr['6'];  ?>

							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-3 control-label m-top-5">WIP</label>											 
								<div class="col-sm-9">										
									<div class="wip_access">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="View" <?php echo ($wip_access_set >= 1 ? 'checked="true"' : ''); ?>>
										<input type="checkbox" class="check-swtich check-b" data-checkbox="2" data-label-text="+ Edit" <?php echo ($wip_access_set == 2 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="wip_access" name="wip_access" value="<?php echo $wip_access_set; ?>">
								</div>
							</div>

							<?php $purchase_orders_access_set = $user_access_arr['7'];  ?>

							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-3 control-label m-top-5">Purchase Orders</label>											 
								<div class="col-sm-9">										
									<div class="purchase_orders_access">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="View" <?php echo ($purchase_orders_access_set >= 1 ? 'checked="true"' : ''); ?>>
										<input type="checkbox" class="check-swtich check-b" data-checkbox="2" data-label-text="+ Edit" <?php echo ($purchase_orders_access_set == 2 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="purchase_orders_access" name="purchase_orders_access" value="<?php echo $purchase_orders_access_set; ?>">
								</div>
							</div>

							<?php $invoice_access_set = $user_access_arr['8'];  ?>

							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-3 control-label m-top-5">Invoice</label>											 
								<div class="col-sm-9">										
									<div class="invoice_access">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="View" <?php echo ($invoice_access_set >= 1 ? 'checked="true"' : ''); ?>>
										<input type="checkbox" class="check-swtich check-b" data-checkbox="2" data-label-text="+ Edit" <?php echo ($invoice_access_set == 2 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="invoice_access" name="invoice_access" value="<?php echo $invoice_access_set; ?>">
								</div>
							</div>

							<?php $users_access_set = $user_access_arr['9'];  ?>

							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-3 control-label m-top-5">Users</label>											 
								<div class="col-sm-9">										
									<div class="users_access">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="View" <?php echo ($users_access_set >= 1 ? 'checked="true"' : ''); ?>>
										<input type="checkbox" class="check-swtich check-b" data-checkbox="2" data-label-text="+ Edit" <?php echo ($users_access_set == 2 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="users_access" name="users_access" value="<?php echo $users_access_set; ?>">
								</div>
							</div>

							<?php $bulletin_board_set = $user_access_arr['11'];  ?>

							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-3 control-label m-top-5">Bulletin Board</label>											 
								<div class="col-sm-9">										
									<div class="bulletin_board">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="View" <?php echo ($bulletin_board_set >= 1 ? 'checked="true"' : ''); ?>>
										<input type="checkbox" class="check-swtich check-b" data-checkbox="2" data-label-text="+ Edit" <?php echo ($bulletin_board_set == 2 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="bulletin_board" name="bulletin_board" value="<?php echo $bulletin_board_set; ?>">
								</div>
							</div>

							<?php $project_schedule_set = $user_access_arr['12'];  ?>

							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-3 control-label m-top-5">Project Schedule</label>											 
								<div class="col-sm-9">										
									<div class="project_schedule">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="View" <?php echo ($project_schedule_set >= 1 ? 'checked="true"' : ''); ?>>
										<input type="checkbox" class="check-swtich check-b" data-checkbox="2" data-label-text="+ Edit" <?php echo ($project_schedule_set == 2 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="project_schedule" name="project_schedule" value="<?php echo $project_schedule_set; ?>">
								</div>
							</div>


							<?php $shopping_center_set = $user_access_arr['13'];  ?>
							
							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-3 control-label m-top-5">Shopping Centre</label>											 
								<div class="col-sm-9">										
									<div class="shopping_center">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="View" <?php echo ($shopping_center_set >= 1 ? 'checked="true"' : ''); ?>>
										<input type="checkbox" class="check-swtich check-b" data-checkbox="2" data-label-text="+ Edit" <?php echo ($shopping_center_set == 2 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="shopping_center" name="shopping_center" value="<?php echo $shopping_center_set; ?>">
								</div>
							</div>



							<?php $labour_schedule_set = $user_access_arr['14'];  ?>
							
							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-3 control-label m-top-5">Labour Schedule</label>											 
								<div class="col-sm-9">										
									<div class="labour_schedule">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="View" <?php echo ($labour_schedule_set >= 1 ? 'checked="true"' : ''); ?>>
										<input type="checkbox" class="check-swtich check-b" data-checkbox="2" data-label-text="+ Edit" <?php echo ($labour_schedule_set == 2 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="labour_schedule" name="labour_schedule" value="<?php echo $labour_schedule_set; ?>">
								</div>
							</div>





<!-- For Company Project -->




							<?php $company_project_set = $user_access_arr['15'];  ?>
							
							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-3 control-label m-top-5">Company Project</label>											 
								<div class="col-sm-9">										
									<div class="company_project">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="Enabled" <?php echo ($company_project_set >= 1 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="company_project" name="company_project" value="<?php echo $company_project_set; ?>">
								</div>
							</div>




<!-- For Company Project -->

<!-- For Site Labour -->

							<?php $site_labour_set = $user_access_arr['16'];  ?>
							
							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-3 control-label m-top-5">Site Labour</label>											 
								<div class="col-sm-9">										
									<div class="site_labour">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="View" <?php echo ($site_labour_set >= 1 ? 'checked="true"' : ''); ?>>
										<input type="checkbox" class="check-swtich check-b" data-checkbox="2" data-label-text="+ Edit" <?php echo ($site_labour_set == 2 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="site_labour" name="site_labour" value="<?php echo $site_labour_set; ?>">
								</div>
							</div>
	

							<?php $site_labour_app_set = $user->site_access;?>
							
							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-3 control-label m-top-5">Site Labour App</label>											 
								<div class="col-sm-9">										
									<div class="site_labour_app">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="2" data-label-text="Have Access" <?php echo ($site_labour_app_set == 2 ? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="site_labour_app" name="site_labour_app" value="<?php echo $site_labour_app_set; ?>">
								</div>
							</div>

<!-- For Site Labour -->



 
							<?php $quick_quote_set = $user_access_arr['17'];  ?> 
							
							<div class="col-xs-12 m-bottom-10 clearfix">										 
								<label class="col-sm-3 control-label m-top-5">Quick Quote</label>											 
								<div class="col-sm-9">										
									<div class="quick_quote">
										<input type="checkbox" class="check-swtich check-a" data-checkbox="1" data-label-text="Have Access" <?php echo ($quick_quote_set == 1? 'checked="true"' : ''); ?>>
									</div>
									<input type="hidden" class="" id="quick_quote" name="quick_quote" value="<?php echo $quick_quote_set; ?>">
								</div>
							</div>

							
							<div class="clearfix"></div>
							<input type="submit" class="btn btn-primary m-right-10 pull-right m-bottom-10" name="update_user_access" value="Update User Access">
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


</div>

						<?php endif; ?>
						
						
					</div>
					
					
					
				</div>				
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('assets/logout-modal'); ?>