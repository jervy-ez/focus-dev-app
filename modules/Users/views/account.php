<?php $this->load->module('users'); ?>
<?php $form_error_msg = $this->session->flashdata('form_error_msg'); ?>
<?php $form_success_msg = $this->session->flashdata('form_success_msg'); ?>
<?php $form_error_login = $this->session->flashdata('form_error_login'); ?>
<?php $form_success_login = $this->session->flashdata('form_success_login'); ?>
<?php $form_success_access = $this->session->flashdata('form_success_access'); ?>
<?php $set_user_id = ( $this->uri->segment(3) != '' ? $this->uri->segment(3) : $this->session->userdata('user_id') );?>

<div class="app-main__inner">
	<div class="app-page-title">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div class="page-title-icon">
					<i class="fas fa-user-circle icon-gradient bg-orange-gradient"></i>
				</div>
				<div>
					User Profile
					<div class="page-title-subheading">Update and view your details.</div>
				</div>
			</div>
			<div class="page-title-actions">
				<div class="d-inline-block dropdown">
					<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-info">
						<span class="btn-icon-wrapper pr-2 opacity-7">
							<i class="fa fa-cog fa-w-20"></i>
						</span>
						Control					
					</button>
					<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
						<ul class="nav flex-column">
							<li class="nav-item">
								<a href="<?php echo base_url('Users/account') ?>" class="nav-link">
									&nbsp; <i class="nav-link-icon fas fa-user-circle"></i>
									<span> &nbsp; My Profile</span>
								</a>
							</li>
							<li class="nav-item">
								<a href="<?php echo base_url('Users/add') ?>" class="nav-link">
									&nbsp; <i class="nav-link-icon fas fa-user-plus"></i>
									<span> &nbsp; Add New User</span>
								</a>
							</li>
							<li class="nav-item">
								<a href="<?php echo base_url('Users') ?>" class="nav-link">
									&nbsp; <i class="nav-link-icon fas fa-users"></i>
									<span> &nbsp; Users List</span>
								</a>
							</li>
							<li class="nav-item-divider nav-item"></li>
							<li class="nav-item">
								<a href="<?php echo base_url('Users/delete_user/') ?><?php echo $set_user_id; ?>" class="nav-link text-danger">
									&nbsp; <i class="nav-link-icon fas fa-user-times text-danger"></i>
									<span> &nbsp; Delete This User</span>
								</a>
							</li>							
						</ul>


					</div>
				</div>
			</div>
		</div>
	</div>


	<!-- main content here -->




	<div class="row">

		<div class="col-md-4">
			<div class="">
				<div id="" class="main-card mb-3 card">
					<div id="" class="card-body p-0">
						<div class="card-shadow-primary card-border   card">
							<div class="dropdown-menu-header">
								<div class="dropdown-menu-header-inner bg-secondary text-center white-text ">
									<div class="menu-header-image" style="background-image: url('<?php echo base_url(); ?>temp_images/header/abstract9.jpg');"></div>
									<div class="menu-header-content p-3">
										<div class="avatar-icon-wrapper btn-hover-shine mb-2 avatar-icon-xxl">
											<div class="avatar-icon margin-auto">
												<?php if(isset($user_details['profile_photo']) && $user_details['profile_photo'] != '' ): ?>
													<img width="64"  src="<?php echo base_url(); ?>uploads/user_photo/<?php echo $user_details['profile_photo']; ?>" alt="<?php echo $user_details['first_name']; ?>">
												<?php else: ?>
													<img width="64"  src="<?php echo base_url(); ?>img/user_profile.png" alt="<?php echo $user_details['first_name']; ?>">
												<?php endif; ?>


												
											</div>
										</div>
										<div>
											<h5 class="menu-header-title"><?php echo $user_details['first_name']; ?> <?php echo $user_details['last_name']; ?></h5>
											<h6 class="menu-header-subtitle mb-3"><?php echo $user_details['role_types']; ?></h6> 
										</div>
										<div class="menu-header-btn-pane">
											<div class="btn-icon btn btn-info btn-xs uploadProfilePhoto"><em class="fas fa-upload text-white"></em> &nbsp; Upload Photo</div>
										</div>

										<form id="userPhoto" action="<?php echo base_url(); ?>Users/updateUserPhoto" method="post" enctype="multipart/form-data">
											<input type="hidden" name="userId" value="<?php echo $set_user_id; ?>">
											<input class="hide" type="file" id="userPhotoFile" name="userPhotoFile" value="" />
										</form>
									</div>
								</div>
							</div>
						</div>
						<div id="" class="p-3">


							<div class="vertical-time-icons vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
								<div class="vertical-timeline-item vertical-timeline-element">
									<div>
										<div class="vertical-timeline-element-icon bounce-in">
											<div class="timeline-icon border-success bg-success">
												<i class="fas fa-info fa-w-16 text-white"></i>
											</div>
										</div>
										<div class="vertical-timeline-element-content bounce-in">
											<h4 class="timeline-title"><?php echo $user_details['role_types']; ?></h4>
											<p>Position</p>

										</div>
									</div>
								</div>

								<div class="vertical-timeline-item vertical-timeline-element">
									<div>
										<div class="vertical-timeline-element-icon bounce-in">
											<div class="timeline-icon border-alternate bg-alternate">
												<i class="fas fa-envelope-open-text fa-w-16 text-white"></i>
											</div>
										</div>
										<div class="vertical-timeline-element-content bounce-in">
											<h4 class="timeline-title"><?php echo $user_details['general_email']; ?></h4>
											<p>Email</p>

										</div>
									</div>
								</div>

								<?php if(isset($user_details['office_number']) && $user_details['office_number']!= ''): ?>
									<div class="vertical-timeline-item vertical-timeline-element">
										<div>
											<div class="vertical-timeline-element-icon bounce-in">
												<div class="timeline-icon border-primary bg-primary">
													<i class="fas fa-headset fa-w-16 text-white"></i>
												</div>
											</div>
											<div class="vertical-timeline-element-content bounce-in">
												<h4 class="timeline-title"><?php echo $user_details['office_number']; ?></h4>
												<p>Office Phone</p>
											</div>
										</div>
									</div>
								<?php endif; ?>


								<?php if(isset($user_details['office_ext']) && $user_details['office_ext']!= ''): ?>
									<div class="vertical-timeline-item vertical-timeline-element">
										<div>
											<div class="vertical-timeline-element-icon bounce-in">
												<div class="timeline-icon border-info  bg-info ">
													<i class="fas fa-blender-phone fa-w-16 text-white"></i>
												</div>
											</div>
											<div class="vertical-timeline-element-content bounce-in">
												<h4 class="timeline-title"><?php echo $user_details['office_ext']; ?></h4>
												<p>Office Ext.</p>
											</div>
										</div>
									</div>
								<?php endif; ?>

								<?php if(isset($user_details['mobile_number']) && $user_details['mobile_number']!= ''): ?>
									<div class="vertical-timeline-item vertical-timeline-element">
										<div>
											<div class="vertical-timeline-element-icon bounce-in">
												<div class="timeline-icon border-danger bg-danger">
													<i class="fas fa-mobile-alt fa-w-16 text-white"></i>
												</div>
											</div>
											<div class="vertical-timeline-element-content bounce-in">
												<h4 class="timeline-title"><?php echo $user_details['mobile_number']; ?></h4>
												<p>Mobile</p>
											</div>
										</div>
									</div>
								<?php endif; ?>


							</div>


						</div>
					</div>
				</div>
			</div>
		</div>



		<div class="col-md-8">


			<?php if(isset($form_error_msg) && $form_error_msg != ''): ?>
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-danger fade show p-1" role="alert">
							<?php echo "$form_error_msg"; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<?php if(isset($form_success_msg) && $form_success_msg != ''): ?>
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-success fade show p-1" role="alert">
							<p class="p-0 m-0"> &nbsp;  <em class="fas fa-exclamation-circle"></em> &nbsp;<?php echo "$form_success_msg"; ?></p>
						</div>
					</div>
				</div>
			<?php endif; ?>




			<div class="mb-3 card">
				<div class="card-header-tab card-header">
					<div class="card-header-title">
						<i class="header-icon  fas fa-id-card  icon-gradient bg-orange-gradient "> </i>
						Details
					</div>
					<ul class="nav">
						<li class="nav-item"><a data-toggle="tab" href="#tab-eg5-0" class="nav-link show orange-text active">Edit Details</a></li>
						<!-- <li class="nav-item"><a data-toggle="tab" href="#tab-eg5-1" class="nav-link show">Button</a></li> -->
					</ul>
				</div>
				<div class="card-body">
					<div class="tab-content">
						<div class="tab-pane show active" id="tab-eg5-0" role="tabpanel">


							<form class="needs-validation" id="userDetailsForm" method="post" action="<?php echo base_url(); ?>Users/updateUserDetails" novalidate="novalidate">


								<div class="form-row">
									<div class="col-md-6 mb-3">
										<label for="firstName">First Name*</label>

										<input type="text" class="form-control" id="firstName" name="firstName"  placeholder="First Name" value="<?php echo $user_details['first_name']; ?>" required=""  autocomplete="off" >
									</div>
									<div class="col-md-6 mb-3">
										<label for="lastName">Last Name*</label>
										<input type="text" class="form-control" id="lastName" name="lastName"  placeholder="Last Name" value="<?php echo $user_details['last_name']; ?>" required=""  autocomplete="off" >
									</div>
								</div>

								<div class="form-row">
									<div class="col-md-6 mb-3">
										<label for="emp_position">Position</label>
										<select name="emp_position" id="emp_position" class="form-control"> 
											<?php echo $this->users->list_user_roles(); ?>
										</select>
										<script type="text/javascript"> document.getElementById('emp_position').value = <?php echo $user_details['role_id']; ?>; </script>
									</div>
									<div class="col-md-6 mb-3">
										<label for="email">Email*</label>
										<input type="email" class="form-control" autocomplete="off" id="email" name="email" placeholder="Email" value="<?php echo $user_details['general_email']; ?>" required="">
									</div>
								</div>

								<div class="form-row">

									<div class="col-md-4 mb-3">
										<label for="officeExt">Office Ext</label>
										<input type="text" class="form-control" id="officeExt" name="officeExt" autocomplete="off" placeholder="XXX"  value="<?php echo $user_details['office_ext']; ?>">
									</div>
									<div class="col-md-4 mb-3">
										<label for="officeNumber">Office Number</label>
										<input type="text" class="form-control" id="officeNumber" name="officeNumber" autocomplete="off" placeholder="0X XXXX XXXX" value="<?php echo $user_details['office_number']; ?>">
									</div>
									<div class="col-md-4 mb-3">
										<label for="mobileNumber">Mobile Number</label>
										<input type="text" class="form-control" id="mobileNumber" name="mobileNumber" autocomplete="off" placeholder="0XXX XXX XXX" value="<?php echo $user_details['mobile_number']; ?>">
									</div>
								</div>									

								<input type="hidden" name="userId" value="<?php echo $set_user_id; ?>">
								<input type="hidden" name="user_email_id" value="<?php echo $user_details['user_email_id']; ?>">
								<input type="hidden" name="user_contact_number_id" value="<?php echo $user_details['user_contact_number_id']; ?>">
								<input type="hidden" name="user_role_id" value="<?php echo $user_details['user_role_id']; ?>">

								<button class="btn btn-sm hide" type="submit" id="submitUserDetails"></button>
							</form>
						</div>							
					</div>
				</div>
				<div class="d-block text-right card-footer">
					<button class="btn btn-orange process_basic_info" onclick="document.getElementById('submitUserDetails').click();"><em class="fas fa-save"></em> Update</button>
				</div>
			</div>



			<?php if(isset($form_error_login) && $form_error_login != ''): ?>
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-danger fade show p-1" role="alert">
							<?php echo "$form_error_login"; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>


			<?php if(isset($form_success_login) && $form_success_login != ''): ?>
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-success fade show p-1" role="alert">
							<p class="p-0 m-0"> &nbsp;  <em class="fas fa-exclamation-circle"></em> &nbsp;<?php echo "$form_success_login"; ?></p>
						</div>
					</div>
				</div>
			<?php endif; ?>


			<div class="mb-3 card" id="loginDetails">
				<div class="card-header-tab card-header">
					<div class="card-header-title">
						<i class="header-icon fas fa-unlock-alt icon-gradient bg-orange-gradient"> </i>
						Log-in
					</div>
					<ul class="nav">
						<li class="nav-item"><a data-toggle="tab" href="#tab-eg5-0" class="nav-link show active orange-text">Edit Log-in</a></li>
					</ul>
				</div>
				<div class="card-body">
					<div class="tab-content">
						<div class="tab-pane show active" id="tab-eg5-1" role="tabpanel">
							<h5 class="card-title">Username</h5>
							<form class="needs-validation" id="userLoginDetailsForm" method="post" action="<?php echo base_url(); ?>Users/updateUserName" novalidate="novalidate">
								<div class="form-row">
									<div class="col-md-10 col-lg-7 col-sm-12 mb-3">
										<label for="userLoginName">Username*</label>
										<div class="input-group">
											<input type="text" class="form-control" id="userLoginName" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="At least 8 or more characters" name="userLoginName" placeholder="Password" required="" minlength="8" value="<?php echo $user_details['user_name']; ?>">
											<div class="input-group-append">
												<button type="submit" class="btn btn-orange"><em class="fas fa-save"></em> Update</button>
											</div>
										</div>
									</div>
								</div>
								<?php $set_user_id = ( $this->uri->segment(3) != '' ? $this->uri->segment(3) : $this->session->userdata('user_id') );?>
								<input type="hidden" name="userId" value="<?php echo $set_user_id; ?>">
							</form>

							<div class="divider"></div>
							<h5 class="card-title">Password</h5>

							<form class="needs-validation" id="userLoginDetailsForm" method="post" action="<?php echo base_url(); ?>Users/updateLoginDetails" novalidate="novalidate">
								<div class="form-row">
									<div class="col-md-5 col-lg-4 col-sm-6 mb-3" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Must contain at least one  number and one uppercase and lowercase letter, and at least 8 or more characters">
										<label for="userPassword">New Password*</label>
										<input type="password" class="form-control" id="userPassword" name="userPassword" placeholder="Password" required=""  minlength="8" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
									</div>
									<div class="col-md-7 col-lg-5 col-sm-6 mb-3" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Must contain at least one  number and one uppercase and lowercase letter, and at least 8 or more characters">
										<label for="userConfirmPassword">Confirm Password*</label>
										<div class="input-group">
											<input type="password" class="form-control" id="userConfirmPassword" name="userConfirmPassword" placeholder="Confirm" required="" minlength="8" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
											<div class="input-group-append">
												<button type="submit" class="btn btn-orange"><em class="fas fa-save"></em> Update</button>
											</div>
										</div>
									</div>

								</div>
								<?php $set_user_id = ( $this->uri->segment(3) != '' ? $this->uri->segment(3) : $this->session->userdata('user_id') );?>
								<input type="hidden" name="userId" value="<?php echo $set_user_id; ?>">
							</form>
						</div>
					</div>
				</div>

				<div class="d-block text-right card-footer">
					<div class="input-group pull-left w-auto">
						<div class="input-group-prepend" data-toggle="tooltip" title="" data-placement="top" data-original-title="<?php echo $user_details['password']; ?>" >
							<span class="input-group-text bg-warning border-warning "><em id="" class="fas fa-unlock-alt"></em> &nbsp; Mouse Hover to View Password</span>
						</div>
						<input type="text" name="upwrd"  style="font-size: 1px; border:none; background-color: #6c757d !important; color: #6c757d !important;" class=" p-0 m-0 w-0 border-secondary bg-secondary" value="<?php echo $user_details['password']; ?>" id="upwrdHddn">
						<div class="input-group-append">
							<button class="btn btn-secondary" id="copyBttnPwrd" onclick="copyHddnPwrdTxt('copyBttnPwrd','upwrdHddn')" onmouseout="outFuncBtnCopy('copyBttnPwrd')">Click to Copy &nbsp; <em id="" class="fas fa-copy"></em></button>
						</div>
					</div>
				</div>
			</div>
			
			<?php if(isset($form_success_access) && $form_success_access != ''): ?>
				<div class="row" id="form_success_access">
					<div class="col-md-12">
						<div class="alert alert-success fade show p-1" role="alert">
							<p class="p-0 m-0"> &nbsp;  <em class="fas fa-exclamation-circle"></em> &nbsp;<?php echo "$form_success_access"; ?></p>
						</div>
					</div>
				</div>
			<?php endif; ?> 

			<div class="mb-3 card">
				<div class="card-header-tab card-header">
					<div class="card-header-title">
						<i class="header-icon fas fa-user-lock icon-gradient bg-orange-gradient"> </i>
						Access Control
					</div>
					<ul class="nav">
						<li class="nav-item"><a data-toggle="tab" href="#tab-eg5-0" class="nav-link show active orange-text">Edit Access</a></li>
					</ul>
				</div>
				<div class="card-body">
					<div class="tab-content">
						<div class="tab-pane show active" id="tab-eg5-1" role="tabpanel">
							<form class="needs-validation" id="userAccessControlForm" method="post" action="<?php echo base_url(); ?>Users/updateAccessControl" novalidate="novalidate">
								<div class="form-row">





									<?php foreach ($access_areas->result() as $access_location): ?>

										<?php $can_access = ( isset($user_access[$access_location->local_name]['can_access']) ? $user_access[$access_location->local_name]['can_access'] : 'off');  ?>
										<?php $can_control = ( isset($user_access[$access_location->local_name]['can_control']) ? $user_access[$access_location->local_name]['can_control'] : 'off');  ?>
										<?php $access_control_id = ( isset($user_access[$access_location->local_name]['access_control_id']) ? $user_access[$access_location->local_name]['access_control_id'] : '0');  ?>

										<!-- Access Unit -->
										<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-6 mb-3">
											<label for="<?php echo $access_location->local_name; ?>_ca"><?php echo $access_location->area_name; ?></label><br />

											<div class="toggle btn btn-primary btn-success ac_ca <?php echo $can_access; ?> btn-light" id="<?php echo $access_location->local_name; ?>_ca" data-toggle="toggle" style="width: 80px; height: 38px;">
												<input type="hidden" name="<?php echo $access_location->local_name; ?>_ca" value="<?php echo $can_access; ?>" data-toggle="toggle" id="<?php echo $access_location->local_name; ?>_ca" data-onstyle="danger" >
												<div class="toggle-group">
													<label class="btn btn-success toggle-on p-2 text-center">Access</label>
													<label class="btn btn-light toggle-off p-2 text-center">Off</label>
													<span class="toggle-handle btn btn-light"></span>
												</div>
											</div>

											<div class="toggle btn btn-primary btn-success ac_cc <?php echo $can_control; ?> btn-light" id="<?php echo $access_location->local_name; ?>_cc" data-toggle="toggle" style="width: 80px; height: 38px;">
												<input type="hidden" name="<?php echo $access_location->local_name; ?>_cc" value="<?php echo $can_control; ?>" data-toggle="toggle" id="<?php echo $access_location->local_name; ?>_cc" data-onstyle="danger" >
												<div class="toggle-group">
													<label class="btn btn-success toggle-on p-2 text-center">Control</label>
													<label class="btn btn-light toggle-off p-2 text-center">Off</label>
													<span class="toggle-handle btn btn-light"></span>
												</div>
											</div>

											<input type="hidden" name="<?php echo $access_location->local_name; ?>_ac_id" value="<?php echo $access_control_id; ?>">
										</div>
										<!-- Access Unit -->

									<?php endforeach; ?>


									<!-- Access Unit -->
									<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-6 mb-3" data-toggle="tooltip" title="" data-placement="top" data-original-title="Warning: Enabling the admin allows full access to the system.">
										<label for="isAdmin">Admin Control</label><br />
										<div class="toggle btn btn-primary btn-danger <?php echo $user_details['is_admin']; ?> btn-light" id="isAdminToggle" data-toggle="toggle" style="width: 100px; height: 38px;">
											<input type="hidden" name="isAdmin" value="<?php echo $user_details['is_admin']; ?>" data-toggle="toggle" id="isAdmin" data-onstyle="danger" >
											<div class="toggle-group">
												<label class="btn btn-danger toggle-on p-2">Enabled</label>
												<label class="btn btn-light toggle-off p-2">Disabled</label>
												<span class="toggle-handle btn btn-light"></span>
											</div>
										</div>
									</div>
									<!-- Access Unit -->

								</div>
								<?php $set_user_id = ( $this->uri->segment(3) != '' ? $this->uri->segment(3) : $this->session->userdata('user_id') );?>
								<input type="hidden" name="userId" value="<?php echo $set_user_id; ?>">
								<button type="submit" class="btn btn-sm hide" id="submitAccessControl" ></button>
							</form>
						</div>
					</div>
				</div>

				<div class="d-block text-right card-footer">
					<button class="btn btn-orange process_login_info pull-right" onclick="document.getElementById('submitAccessControl').click();"><em class="fas fa-save"></em> Update</button>						
				</div>
			</div>

		</div>
	</div>
	<!-- main content here -->
</div>