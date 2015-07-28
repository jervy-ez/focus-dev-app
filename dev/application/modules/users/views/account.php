<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
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



				<div class="row">
					
					<div class="col-md-9">
						<div class="left-section-box">
							<div class="box-head pad-10 clearfix">
								<label><?php echo $screen; ?></label><span> (<a href="#" data-placement="right" class="popover-test" title="" data-content="Hello there mate! Welcome to the clients screen." data-original-title="Welcome">?</a>)</span>
															
							</div>
							<div class="box-area clearfix">


								<?php if(@$user_password_updated): ?>
									<div class="no-pad-t m-bottom-10 pad-left-10">
										<div class="border-less-box alert alert-success fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>Congratulations!</h4>
										<?php echo $user_password_updated;?>
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
								
									<div class="row clearfix pad-left-15  pad-right-15 pad-bottom-10">

									

										
										<?php foreach($user as $key => $user): ?>




												<form class="form-horizontal clearfix form" role="form" method="post" action="" accept-charset="utf-8" enctype="multipart/form-data">

												<input type="hidden" name="user_id" value="<?php echo $user->user_id; ?>">
												<input type="hidden" name="is_form_submit" value="1">

      								<div class="box bank_account" style="">
										<div class="box-head pad-5 m-bottom-5">
											<label><i class="fa fa-user fa-lg"></i> Peronal Info</label>
										</div>
										
										<div class="box-area pad-5 clearfix">
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('first_name')){ echo 'has-error has-feedback';} ?>">
												<label for="first_name" class="col-sm-3 control-label">First Name</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="first_name" name="first_name"  tabindex="1" placeholder="First Name"  value="<?php echo $user->user_first_name; ?>">
												</div>
											</div>
											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('last_name')){ echo 'has-error has-feedback';} ?>">
												<label for="last_name" class="col-sm-3 control-label">Last Name</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="last_name" name="last_name"  tabindex="2" placeholder="Last Name"  value="<?php echo $user->user_last_name; ?>">
												</div>
											</div>
											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('gender')){ echo 'has-error has-feedback';} ?>">
												<label for="gender" class="col-sm-3 control-label">Gender</label>
												<div class="col-sm-9">
													<select name="gender" class="form-control gender" tabindex="3" id="gender"><option value="Male">Male</option><option value="Female">Female</option></select>

													<?php $gender = ($this->input->post('gender') != '' ? $this->input->post('gender') : 'Male'); ?>
													<script type="text/javascript">$('.gender').val('<?php echo $user->user_gender; ?>');</script>
												</div>
											</div>
											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('dob')){ echo 'has-error has-feedback';} ?>">
												<label for="dob" class="col-sm-3 control-label">Date of Birth</label>
												<div class="col-sm-9">
													<input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control datepicker" id="dob" name="dob" tabindex="4" value="<?php echo $user->user_date_of_birth; ?>">
												</div>
											</div>
										</div>
									</div>
      								<div class="clearfix"></div>



      								<div class="box bank_account" >
										<div class="box-head pad-5 m-bottom-5">
											<label><i class="fa fa-suitcase fa-lg"></i> Account Details</label>
										</div>
										
										<div class="box-area pad-5 clearfix">
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('login_name')){ echo 'has-error has-feedback';} ?>">
												<label for="login_name" class="col-sm-3 control-label">Login Name</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="login_name" name="login_name"  tabindex="5" placeholder="Login Name"  value="<?php echo $user->login_name; ?>" style="text-transform: none;" >
												</div>
											</div>
											

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('department')){ echo 'has-error has-feedback';} ?>">
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
											
											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('role')){ echo 'has-error has-feedback';} ?>">
												<label for="role" class="col-sm-3 control-label">Role</label>
												<div class="col-sm-9">
													<select name="role" class="form-control role" id="role"  tabindex="9">
														<?php foreach ($roles as $key => $value): ?>
															<option value="<?php echo $value->role_id.'|'.$value->role_types; ?>"><?php echo $value->role_types; ?></option>
														<?php endforeach; ?>
													</select>

														<?php $role = ($this->input->post('role') != '' ? $this->input->post('role') : ''); ?>
														<script type="text/javascript">$('.role').val('<?php echo $user->role_id.'|'.$user->role_types; ?>');</script>
													
												</div>
											</div>
											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('focus')){ echo 'has-error has-feedback';} ?>">
												<label for="focus" class="col-sm-3 control-label">Focus</label>
												<div class="col-sm-9">
													<select name="focus" class="form-control focus" id="focus" tabindex="10">
														<option value="">Select Focus Company</option>
														<?php foreach ($focus as $key => $value): ?>
															<option value="<?php echo $value->company_id.'|'.$value->company_name; ?>"><?php echo $value->company_name; ?></option>
														<?php endforeach; ?>
													</select>

													<?php $focus = ($this->input->post('focus') != '' ? $this->input->post('focus') : ''); ?>
													<script type="text/javascript">$('.focus').val('<?php echo $user->company_id.'|'.$user->company_name; ?>');</script>
												</div>
											</div>
										</div>
									</div>

      								<div class="clearfix"></div>


      								<div class="box  bank_account" >
										<div class="box-head pad-5 m-bottom-5">
											<label><i class="fa fa-phone-square fa-lg"></i> Contact Details</label>
										</div>

										<input type="hidden" name="contact_number_id" value="<?php echo $user->contact_number_id; ?>">
										
										<div class="box-area pad-5 clearfix">

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('direct_landline')){ echo 'has-error has-feedback';} ?>">
												<label for="direct_landline" class="col-sm-3 control-label">Direct Landline</label>
												<div class="col-sm-9">
													<input type="text" class="form-control direct_landline" id="direct_landline" name="direct_landline" onchange="contact_number_assign('direct_landline')" tabindex="11" placeholder="Direct Landline"  value="<?php echo $user->direct_number; ?>">																										
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('after_hours')){ echo 'has-error has-feedback';} ?>">
												<label for="after_hours" class="col-sm-3 control-label">After Hours</label>
												<div class="col-sm-9">
													<input type="text" class="form-control after_hours" id="after_hours" name="after_hours" onchange="contact_number_assign('after_hours')" tabindex="12" placeholder="After Hours"  value="<?php echo $user->after_hours; ?>">																										
												</div>
											</div>
											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
												<label for="mobile_number" class="col-sm-3 control-label">Mobile Number</label>
												<div class="col-sm-9">
													<input type="text" class="form-control mobile_number" id="mobile_number" name="mobile_number" placeholder="Mobile Number" onchange="mobile_number_assign('mobile_number')"  tabindex="13" value="<?php echo $user->mobile_number; ?>">
												</div>
											</div>
											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('email')){ echo 'has-error has-feedback';} ?>">
												<label for="email" class="col-sm-3 control-label">Email</label>
												<div class="col-sm-9">
													<input type="email" class="form-control" id="email" name="email"  tabindex="14" placeholder="Email"  value="<?php echo $user->general_email; ?>">
												</div>
												<input type="hidden" name="email_id" value="<?php echo $user->email_id; ?>">
											</div>
											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('skype_id')){ echo 'has-error has-feedback';} ?>">
												<label for="skype_id" class="col-sm-3 control-label">Skype ID</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="skype_id" name="skype_id"  tabindex="15" placeholder="Skype ID"  value="<?php echo $user->user_skype; ?>" style="text-transform: none;">
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix  <?php if(@$upload_error){ echo 'has-error has-feedback';} ?>  ">
												<label for="profile_photo" class="col-sm-3 control-label">Profile Photo</label>
												<div class="col-sm-9">
													<input type="file" id="profile_photo" name="profile_photo" class="form-control profile_photo"  />
												</div>
											</div>
											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('skype_password')){ echo 'has-error has-feedback';} ?>">
												<label for="skype_password" class="col-sm-3 control-label">Skype Password</label>
												<div class="col-sm-9">
													
													<div class="input-group ">
														<span class="input-group-addon"><i class="fa fa-skype fa-lg"></i></span>
														<input type="text" class="form-control" id="skype_password" name="skype_password"  tabindex="15" placeholder="Skype Password"  value="<?php echo $user->user_skype_password; ?>" style="text-transform: none;">
													</div>



												</div>
											</div>

											<input type="hidden" name="profile_raw" value="<?php echo $user->user_profile_photo; ?>">

										</div>
									</div>

      								<div class="clearfix"></div>

      								<div class="box">
										<div class="box-head pad-5">
											<label><i class="fa fa-unlock-alt fa-lg"></i> Access</label>
											<label class = "pull-right">Is Admin <input type="checkbox" name = "upt_chk_is_admin" <?php echo ($user->if_admin==1 ? 'checked' : '');?>></label>
										</div>

										<div class="box-area clearfix m-top-10">
											<div class="col-xs-12 m-bottom-10 clearfix <?php //if(form_error('access')){ echo 'has-error has-feedback';} ?>">
												<label for="time-half" class="col-sm-2 col-xs-12 control-label" style="font-weight: normal;">Choose Allowed Access*</label>
												<div class="col-sm-10 col-xs-12">

													<select class="form-control chosen-multi col-sm-12 pad-5" tabindex="24" id="access" name="access[]" multiple="multiple">
														<?php

														/*

															foreach ($all_access as $row => $value){
																echo '<option value="'.$value->access_id.'|'.$value->access_types.'">'.$value->access_types.'</option>';
															}
*/
														?>
													
													</select>												
												</div>
											</div>

											<?php
											/*
	
												if($this->input->post('access')){
													$access = $this->input->post('access'); 
													echo '<script type="text/javascript">$(".chosen-multi").val([';
													foreach ($access as $key => $value) {														
														echo '"'.$value.'",';
													}
													echo ']).trigger("change");</script>';

												}else{

												$user_access_group_id = $user->user_access_group_id;

													$user_access_group_arr = explode(',',$user_access_group_id);

													echo '<script type="text/javascript">$(".chosen-multi").val([';
													foreach ($all_access as $row => $value){

														foreach ($user_access_group_arr as $key => $user_access_group) {
															if($value->access_id == $user_access_group){
																echo '"'.$value->access_id.'|'.$value->access_types.'",';

															}
														}
													}
													echo ']).trigger("change");</script>';


												}
*/

											?>


										</div>
									</div>


      								<div class="clearfix"></div>


      								<div class="box">
										<div class="box-head pad-5">
											<label for="project_notes"><i class="fa fa-pencil-square fa-lg"></i> Comments</label>
										</div>

										<input type="hidden" name="user_comments_id" value="<?php echo $user->user_comments_id; ?>">
											
										<div class="box-area pad-5 clearfix">
											<div class="clearfix">												
												<div class="">
													<textarea class="form-control" id="comments" rows="3"  tabindex="16" name="comments"><?php echo $user->comments; ?></textarea>
												</div>
											</div>
										</div>
									</div>
									<div class="clearfix"></div>

									<input type="hidden" name="is_form_submit" value="1">


									<div class="m-top-15 clearfix">
								    	<div>
								        	<button type="submit" class="btn btn-success btn-lg submit_form" id="focus_add_company" name="save_bttn" value="Save"><i class="fa fa-floppy-o"></i> Update</button>
								        </div>
								    </div>
								

								<p>&nbsp;</p>
							</div>
						</div>
					</div>					

					</form>



											 

















										<?php endforeach; ?>						
										

									


					</div>					

					<div class="col-md-3">
						
						<div class="box">
							<form method="post">
								<div class="box-head pad-5">
									<label><i class="fa fa-unlock fa-lg"></i> Change User Password</label>
								</div>
								<div class="box-area clearfix">
									<div class="clearfix m-top-10 m-bottom-0">
										<label for="current_password" class="col-sm-5 control-label m-top-5" style="font-weight: normal;">Current Password</label>
										<div class="col-sm-7">
											<input type="password" class="form-control" id="current_password" name="current_password" placeholder="Current Password" value="">
										</div>
									</div>

									<hr />

									<div class="clearfix m-top-0 m-bottom-10">
										<label for="new_password" class="col-sm-5 control-label m-top-5" style="font-weight: normal;">New Password</label>
										<div class="col-sm-7">
											<input type="password" class="form-control" id="new_password" name="new_password" placeholder="New Password" value="">
										</div>
									</div>

									<div class="clearfix m-top-10 m-bottom-10">
										<label for="confirm_password" class="col-sm-5 control-label m-top-5" style="font-weight: normal;">Confirm Password</label>
										<div class="col-sm-7">
											<input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" value="">
										</div>
									</div>

									<input type="submit" name="update_password" value="Update Password" class="pull-right btn btn-danger m-right-10 m-top-5">
								</div>
							</form>
						</div>
						
						<div class="box">
							<div class="box-head pad-5">
								<label><i class="fa fa-history fa-lg"></i> History</label>
							</div>
							<div class="box-area pattern-sandstone pad-10">

								<div class="box-content box-list collapse in">
									<ul>
										<li>
											<div><a href="#" class="news-item-title">You added a new company</a><p class="news-item-preview">May 25, 2014</p></div>
										</li>
										<li>
											<div><a href="#" class="news-item-title">Updated the company details and contact information for James Tiling Co.</a><p class="news-item-preview">May 20, 2014</p></div>
										</li>
									</ul>
									<div class="box-collapse">
										<a style="cursor: pointer;" data-toggle="collapse" data-target=".more-list"> Show More </a>
									</div>
									<ul class="more-list collapse out">
										<li>
											<div><a href="#" class="news-item-title">Duis aute irure dolor in reprehenderit</a><p class="news-item-preview">Lorem ipsum dolor si labore et dolore.</p></div>
										</li>
										<li>
											<div><a href="#" class="news-item-title">Duis aute irure dolor in reprehenderit</a><p class="news-item-preview">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore.</p></div>
										</li>
										<li>
											<div><a href="#" class="news-item-title">Duis aute irure dolor in reprehenderit</a><p class="news-item-preview">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore.</p></div>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-md-3 hide">						
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
		</div>
	</div>
</div>
<?php $this->load->view('assets/logout-modal'); ?>
