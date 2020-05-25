<?php $this->load->module('users'); ?>
<?php $form_error_msg = $this->session->flashdata('form_error_msg'); ?>
<?php $form_success_msg = $this->session->flashdata('form_success_msg'); ?>
<?php $form_error_login = $this->session->flashdata('form_error_login'); ?>
<?php $form_success_login = $this->session->flashdata('form_success_login'); ?>

<div class="app-main__inner">
	<div class="app-page-title">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div class="page-title-icon">
					<i class="fas fa-user-plus icon-gradient bg-orange-gradient"></i>
				</div>
				<div>
					Add New User
					<div class="page-title-subheading">It all begins here.</div>
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
                                     &nbsp; <i class="nav-link-icon fas fa-id-card"></i>
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


						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>


	<!-- main content here -->




		<div class="row">

			
			<div class="col-md-12 col-lg-10 ">


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
							<i class="header-icon fas fa-id-card icon-gradient  bg-orange-gradient "> </i>
							Details
						</div>
						<ul class="nav">
							<li class="nav-item"><a data-toggle="tab" href="#tab-eg5-0" class="nav-link show active orange-text">New User Details</a></li>
							<!-- <li class="nav-item"><a data-toggle="tab" href="#tab-eg5-1" class="nav-link show">Button</a></li> -->
						</ul>
					</div>
					<div class="card-body">
						<div class="tab-content">
							<div class="tab-pane show active" id="tab-eg5-0" role="tabpanel">


								<form class="needs-validation" id="newUserDetails" method="post" action="<?php echo base_url(); ?>Users/newUserDetails" novalidate="novalidate">


                                    <div class="form-row">
										<div class="col-md-6 mb-3">
											<label for="firstName">First Name*</label>

											<input type="text" class="form-control" id="firstName" name="firstName"  placeholder="First Name" value="<?php echo $this->session->flashdata('firstName'); ?>" required=""  autocomplete="off" >
										</div>
										<div class="col-md-6 mb-3">
											<label for="lastName">Last Name*</label>
											<input type="text" class="form-control" id="lastName" name="lastName"  placeholder="Last Name" value="<?php echo $this->session->flashdata('lastName'); ?>" required=""  autocomplete="off" >
										</div>
									</div>

									<div class="form-row">
										<div class="col-md-6 mb-3">
											<label for="emp_position">Position</label>
											<select name="emp_position" id="emp_position" class="form-control" required="" >
												<option class="hide" value=""  >Select</option>
												<?php echo $this->users->list_user_roles(); ?>
											</select>

										</div>
										<div class="col-md-6 mb-3">
											<label for="email">Email*</label>
											<input type="email" class="form-control" autocomplete="off" id="email" name="email" placeholder="Email" value="<?php echo $this->session->flashdata('email'); ?>" required="">
										</div>
									</div>
									<script type="text/javascript"> document.getElementById('emp_position').value = <?php echo  ( $this->session->flashdata('emp_position') ? $this->session->flashdata('emp_position') : '""')   ; ?>; </script>

									<div class="form-row">
										<div class="col-md-4 mb-3">
											<label for="officeExt">Office Ext</label>
											<input type="text" class="form-control" id="officeExt" name="officeExt" autocomplete="off" placeholder="XXX"  value="<?php echo $this->session->flashdata('officeExt'); ?>">
										</div>
										<div class="col-md-4 mb-3">
											<label for="officeNumber">Office Number</label>
											<input type="text" class="form-control" id="officeNumber" name="officeNumber" autocomplete="off" placeholder="0X XXXX XXXX" value="<?php echo $this->session->flashdata('officeNumber'); ?>">
										</div>
										<div class="col-md-4 mb-3">
											<label for="mobileNumber">Mobile Number</label>
											<input type="text" class="form-control" id="mobileNumber" name="mobileNumber" autocomplete="off" placeholder="0XXX XXX XXX" value="<?php echo $this->session->flashdata('mobileNumber'); ?>">
										</div>
									</div>


									<div class="divider"></div>
									<h5 class="card-title">Log-In</h5>
									<div class="form-row">
										<div class="col-md-4 mb-3">
											<label for="userLoginName">Username*</label>
											<input type="text" class="form-control" id="userLoginName" data-toggle="tooltip" title=""  data-placement="bottom" data-original-title="At least 8 or more characters" name="userLoginName" placeholder="Username" required="" minlength="8" value="<?php echo $this->session->flashdata('userLoginName'); ?>" autocomplete="off">
										</div>

										<div class="col-md-4 mb-3">
											<label for="userLoginPwrd">Password</label>

											<div class="input-group">
												<div class="input-group-prepend"><span class="input-group-text bg-warning border-warning"><em id="" class="fas fa-unlock-alt"></em> &nbsp; Initial Password</span></div>
												<input type="text" class="form-control" id="userLoginPwrd" readonly="" value="<?php echo $this->session->admin_pwrd_default; ?>">
											</div>

										</div>



										<div class="col-md-4 mb-3">
											<label for="isAdmin">Is Admin</label><br />

											<div class="toggle btn btn-primary btn-danger off btn-light" id="isAdminToggle" data-toggle="toggle" style="width: 56.5px; height: 38px;">
												<input type="hidden" name="isAdmin" value="off" data-toggle="toggle" id="isAdmin" data-onstyle="danger" >
												<div class="toggle-group">
													<label class="btn btn-danger toggle-on p-2">On</label>
													<label class="btn btn-light toggle-off p-2">Off</label>
													<span class="toggle-handle btn btn-light"></span>
												</div>
											</div>

											<?php if($this->session->flashdata('isAdmin') !== null && $this->session->flashdata('isAdmin') != ''): ?>
												<script type="text/javascript"> 
													document.getElementById('isAdmin').value = '<?php echo $this->session->flashdata("isAdmin"); ?>';

													var element = document.getElementById("isAdminToggle");
													element.classList.remove('on');
													element.classList.remove('off');
													element.classList.add('<?php echo $this->session->flashdata("isAdmin"); ?>');

												</script>

											<?php else: ?>
												<script type="text/javascript"> 
													document.getElementById('isAdmin').value = 'off';
												</script>
											<?php endif; ?>

										</div>
									</div>

                                    <button class="btn btn-sm hide" type="submit" id="submitNewUserDetails"></button>
                                </form>
							</div>							
						</div>
					</div>
					<div class="d-block text-right card-footer">
						<button class="btn btn-orange process_Newbasic_info" onclick="document.getElementById('submitNewUserDetails').click();"><em class="fas fa-save"></em> Submit</button>
					</div>
				</div>
				
			</div>
		</div>
		<!-- main content here -->
	</div>