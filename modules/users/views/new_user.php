<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>

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
					
					<div class="col-md-8 col-lg-9">
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

							<?php if(@$pword): ?>
								<div class="pad-10 no-pad-t">
									<div class="border-less-box alert alert-danger fade in">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										<h4>You missed me!</h4>
										<?php echo $pword;?>
									</div>
								</div>
							<?php endif; ?>

							<?php // if(@$access_err): ?>

<!-- 
								<div class="pad-10 no-pad-t">
									<div class="border-less-box alert alert-danger fade in">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										<h4>You shall not pass!</h4>
										<?php //echo $access_err;?>
									</div>
								</div>

 -->

							<?php // endif; ?>

							<?php if(@$upload_error): ?>
								<div class="pad-10 no-pad-t">
									<div class="border-less-box alert alert-danger fade in">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										<h4>Primary Photo</h4>
										<?php echo $upload_error;?>
									</div>
								</div>
							<?php endif; ?>


							<?php if(@$msg): ?>
								<div class="pad-10 no-pad-t">
									<div class="border-less-box alert alert-info fade in">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										<h4>Coming Next!</h4>
										<?php echo $msg;?>
									</div>
								</div>
							<?php endif; ?>



							<div class="box-head pad-10 clearfix">
								<label><i class="fa fa-user-plus  fa-lg"></i> <?php echo $screen; ?></label><span> (<a href="#" data-placement="right" class="popover-test" title="" data-content="Hello there mate! Welcome to the clients screen." data-original-title="Welcome">?</a>)</span>
								<p><a href="#" class="tooltip-test" title="Tooltip">This link</a> and <a href="#" class="tooltip-test" title="Tooltip">that link</a> should have tooltips on hover.</p>								
							</div>
							<div class="box-area pad-10">






								<form class="form-horizontal clearfix form" role="form" method="post" action="" accept-charset="utf-8" enctype="multipart/form-data">

									<div class="col-md-2 col-sm-2 col-xs-12">

										<div class="box bank_account m-top-10 clearfix" >

											<div class="col-xs-12 m-bottom-10 clearfix">
												<div class="primary_photo_wraper pad-top-5">
													<img src="<?php echo base_url(); ?>uploads/users/no-avatar.jpg" class="user_avatar img-responsive img-thumbnail">													
												</div>												
											</div>

											<div class="col-xs-12 m-bottom-10 clearfix  <?php if(@$upload_error){ echo 'has-error has-feedback';} ?>  ">
												<label for="profile_photo" class="col-sm-12 control-label text-center center">Profile Photo</label>
												<div class="col-sm-12">
													<input type="file" id="profile_photo" name="profile_photo" class="form-control profile_photo"  />
												</div>
											</div>

										</div>
										<div class="clearfix"></div>


										<div class="box bank_account m-top-10" >

											<div class="box-head pad-5 m-bottom-5">
												<label><i class="fa fa-user fa-lg"></i> Peronal Info</label>
											</div>

											<div class="box-area pad-5 clearfix">
												<div class="col-xs-12 m-bottom-10 clearfix <?php if(form_error('first_name')){ echo 'has-error has-feedback';} ?>">
													<input type="text" class="form-control" id="first_name" name="first_name"  tabindex="1" placeholder="First Name*"  value="<?php echo $this->input->post('first_name'); ?>">													
												</div>

												<div class="col-xs-12 m-bottom-10 clearfix <?php if(form_error('last_name')){ echo 'has-error has-feedback';} ?>">													
														<input type="text" class="form-control" id="last_name" name="last_name"  tabindex="2" placeholder="Last Name*"  value="<?php echo $this->input->post('last_name'); ?>">												
												</div>

												<div class="col-xs-12 m-bottom-10 clearfix <?php if(form_error('gender')){ echo 'has-error has-feedback';} ?>">													
													<select name="gender" class="form-control gender" tabindex="3" id="gender"><option value="Male">Male</option><option value="Female">Female</option></select>
													<?php $gender = ($this->input->post('gender') != '' ? $this->input->post('gender') : 'Male'); ?>
													<script type="text/javascript">$('.gender').val('<?php echo $gender; ?>');</script>													
												</div>

												<div class="col-xs-12 m-bottom-10 clearfix">													
													<input type="text" data-date-format="dd/mm/yyyy" placeholder="Date of Birth" class="form-control datepicker" id="dob" name="dob" tabindex="4" value="<?php echo $this->input->post('dob'); ?>">												
												</div>
											</div>
										</div>
										<div class="clearfix"></div>




									</div>


							<div class="col-md-10 col-sm-10 col-xs-12">

      								



      								<div class="box bank_account" >
										<div class="box-head pad-5 m-bottom-5">
											<label><i class="fa fa-suitcase fa-lg"></i> Account Details</label>

											<div class="pull-right  clearfix <?php if(form_error('password')){ echo 'has-error has-feedback';} ?>">
												 
													<div class="m-top-5 m-right-5 ">
														<p>Temporary Password: <strong><?php echo $static_defaults[0]->temp_user_psswrd; ?></strong></p>
														</div>
													</div>


										</div>
										
										<div class="box-area pad-5 clearfix">
											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('focus')){ echo 'has-error has-feedback';} ?>">
												<label for="focus" class="col-sm-3 control-label">Focus*</label>
												<div class="col-sm-9">
													<select name="focus" class="form-control focus" id="focus" tabindex="5">
														<option value="">Select Focus Company</option>
														<?php foreach ($focus as $key => $value): ?>
															<option value="<?php echo $value->company_id.'|'.$value->company_name; ?>"><?php echo $value->company_name; ?></option>
														<?php endforeach; ?>
													</select>

													<?php $focus = ($this->input->post('focus') != '' ? $this->input->post('focus') : ''); ?>
													<script type="text/javascript">$('.focus').val('<?php echo $focus; ?>');</script>
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('login_name')){ echo 'has-error has-feedback';} ?>">
												<label for="login_name" class="col-sm-4 control-label">Login Name*</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" id="login_name" name="login_name"  tabindex="8" placeholder="Login Name"  value="<?php echo $this->input->post('login_name'); ?>" style="text-transform: none;" >
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('department')){ echo 'has-error has-feedback';} ?>">
												<label for="department" class="col-sm-4 control-label">Department*</label>
												<div class="col-sm-8">
													<select name="department" class="form-control department" id="department"  tabindex="6">
														<option value="">Select Department*</option>
														<?php foreach ($departments as $key => $value): ?>
															<option value="<?php echo $value->department_id.'|'.$value->department_name; ?>"><?php echo $value->department_name; ?></option>
														<?php endforeach; ?>
													</select>

													<?php $department = ($this->input->post('department') != '' ? $this->input->post('department') : ''); ?>
													<script type="text/javascript">$('.department').val('<?php echo $department; ?>');</script>
												</div>
											</div>

											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix hide <?php if(form_error('password')){ echo 'has-error has-feedback';} ?>">
												<label for="password" class="col-sm-3 control-label">Password*</label>
												<div class="col-sm-9">
													<input type="password" class="form-control" id="password" name="password"  tabindex="9" placeholder="Password"  value="<?php echo $static_defaults[0]->temp_user_psswrd; ?>">
													<input type="text" class="form-control" id="days_exp" name="days_exp"  tabindex="9"   value="<?php echo $static_defaults[0]->days_psswrd_exp; ?>">
												</div>
											</div>
											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('role')){ echo 'has-error has-feedback';} ?>">
												<label for="role" class="col-sm-3 control-label">Role*</label>
												<div class="col-sm-9">
													<select name="role" class="form-control role user-role-selection" id="role"  tabindex="7">
														<option value="">Select Role*</option>
														<?php foreach ($roles as $key => $value): ?>
															<option value="<?php echo $value->role_id.'|'.$value->role_types; ?>"><?php echo $value->role_types; ?></option>
														<?php endforeach; ?>
													</select>

													<?php if($this->input->post('role') != ''): ?>
														<?php $role = ($this->input->post('role') != '' ? $this->input->post('role') : ''); ?>
														<script type="text/javascript">$('.role').val('<?php echo $role; ?>');</script>
													<?php endif; ?>
												</div>
											</div>
											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix hide <?php if(form_error('confirm_password')){ echo 'has-error has-feedback';} ?>">
												<label for="confirm_password" class="col-sm-5 control-label">Confirm Password*</label>
												<div class="col-sm-7">
													<input type="password" class="form-control" id="confirm_password" name="confirm_password"  tabindex="10" placeholder="Confirm Password"  value="<?php echo $static_defaults[0]->temp_user_psswrd; ?>">
												</div>
											</div>


											<div class="col-md-6 col-sm-6 col-xs-12  m-bottom-10  clearfix <?php if(form_error('super_visor')){ echo 'has-error has-feedback';} ?>">
												<label for="super_visor" class="col-sm-4 control-label">Supervisor*</label>
												<div class="col-sm-8">
													<select name="super_visor" class="form-control super_visor" id="super_visor" >
														<option value="">Select Personel*</option>
														<?php foreach($users as $key => $value): ?>
															<option value="<?php echo $value->primary_user_id; ?>"><?php echo $value->user_first_name.' '.$value->user_last_name; ?></option>
														<?php endforeach; ?>
													</select>

													<?php $super_visor = ($this->input->post('super_visor') != '' ? $this->input->post('super_visor') : ''); ?>
													<script type="text/javascript">$('.super_visor').val('<?php echo $super_visor; ?>');</script>
												</div> 

												



											</div>




											<div class="div_pm_for_pa col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('pm_for_pa')){ echo 'has-error has-feedback';} ?>" style="display:none;">
												<label for="pm_for_pa" class="col-sm-3 control-label">Primary PM*</label>
												<div class="col-sm-9">
													<select name="pm_for_pa" class="form-control pm_for_pa " id="pm_for_pa" >
														 <option value="">Select Project Manager</option>
														 <?php
														 	foreach ($project_manager as $row){
														 		echo '<option class="" value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.'</option>';
														 	}
														 ?>
													</select>


													<?php if($this->input->post('pm_for_pa') != ''): ?>														
														<?php $pm_for_pa = ($this->input->post('pm_for_pa') != '' ? $this->input->post('pm_for_pa') : ''); ?>
														<script type="text/javascript">$('select.pm_for_pa').val('<?php echo $pm_for_pa; ?>'); $('.div_pm_for_pa').show(); </script>
													<?php endif; ?>

													<?php if(form_error('pm_for_pa')): ?>
														<script type="text/javascript">  $('.div_pm_for_pa').show(); </script>
													<?php endif; ?>

												</div>
											</div>

										</div>
									</div>

      								<div class="clearfix"></div>


      								<div class="box  bank_account" >
										<div class="box-head pad-5 m-bottom-5">
											<label><i class="fa fa-phone-square fa-lg"></i> Contact Details</label>
										</div>
										
										<div class="box-area pad-5 clearfix">

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('direct_landline')){ echo 'has-error has-feedback';} ?>">
												<label for="direct_landline" class="col-sm-4 control-label">Direct Landline*</label>
												<div class="col-sm-8">
													<input type="text" class="form-control direct_landline" id="direct_landline" name="direct_landline" onchange="contact_number_assign('direct_landline')" tabindex="11" placeholder="Direct Landline"  value="<?php echo $this->input->post('direct_landline'); ?>">																										
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('after_hours')){ echo 'has-error has-feedback';} ?>">
												<label for="after_hours" class="col-sm-3 control-label">After Hours</label>
												<div class="col-sm-9">
													<input type="text" class="form-control after_hours" id="after_hours" name="after_hours" onchange="contact_number_assign('after_hours')" tabindex="12" placeholder="After Hours"  value="<?php echo $this->input->post('after_hours'); ?>">																										
												</div>
											</div>
											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
												<label for="mobile_number" class="col-sm-4 control-label">Mobile Number</label>
												<div class="col-sm-8">
													<input type="text" class="form-control mobile_number" id="mobile_number" name="mobile_number" placeholder="Mobile Number" onchange="mobile_number_assign_user('mobile_number')"  tabindex="13" value="<?php echo $this->input->post('mobile_number'); ?>">
												</div>
											</div>
											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('email')){ echo 'has-error has-feedback';} ?>">
												<label for="email" class="col-sm-3 control-label">Email*</label>
												<div class="col-sm-9">
													<input type="email" class="form-control" id="email" name="email"  tabindex="14" placeholder="Email"  value="<?php echo $this->input->post('email'); ?>">
												</div>
											</div>
											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('skype_id')){ echo 'has-error has-feedback';} ?>">
												<label for="skype_id" class="col-sm-3 control-label">Skype ID*</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="skype_id" name="skype_id"  tabindex="15" placeholder="Skype ID"  value="<?php echo $this->input->post('skype_id'); ?>" style="text-transform: none;">
												</div>
											</div>


											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('skype_password')){ echo 'has-error has-feedback';} ?>">
												<label for="skype_password" class="col-sm-4 control-label">Skype Password</label>
												<div class="col-sm-8">													
													<div class="input-group ">
														<span class="input-group-addon"><i class="fa fa-skype fa-lg"></i></span>
														<input type="text" class="form-control" id="skype_password" name="skype_password"  tabindex="15" placeholder="Skype Password"  value="<?php echo $this->input->post('skype_password'); ?>" style="text-transform: none;">
													</div>
												</div>
											</div>




										</div>
									</div>

      								<div class="clearfix"></div>

      								


      								<div class="box">
										<div class="box-head pad-5">
											<label for="project_notes"><i class="fa fa-pencil-square fa-lg"></i> About</label>
										</div>
											
										<div class="box-area pad-5 clearfix">
											<div class="clearfix">												
												<div class="">
													<textarea class="form-control" id="comments" rows="5"  tabindex="16" name="comments"><?php echo $this->input->post('comments'); ?></textarea>
												</div>
											</div>
										</div>
									</div>
									<div class="clearfix"></div>

									<input type="hidden" name="is_form_submit" value="1">


									<div class="m-top-10 clearfix">
								    	<div>
								        	<button type="submit" class="btn btn-success btn-lg submit_form" id="focus_add_company" name="save_bttn" value="Save"><i class="fa fa-floppy-o"></i> Save</button>
								        </div>
								    </div></div>
								


							</div>
						</div>
					</div>					

					
					
					<div class="col-md-4 col-lg-3">	
						<div class="box clearfix">
							<div class="box-head pad-5 m-bottom-10">
								<label><i class="fa fa-unlock-alt fa-lg"></i> Select Site</label>
							</div>						

							<?php //$is_admin_set = $is_user_admin;  ?>
							

							<div class="col-xs-12 m-bottom-10 clearfix">
								<div class="col-xs-12 m-bottom-10 clearfix" style = "padding-left: 50px">
									<select class="segment-select" id = "site_select" name = "site_select">
  										<option value="1">Sojourn</option>
  										<option value="2">Site Labour App</option>
									</select>
									<script>$(".segment-select").Segment();</script>
								</div>
							</div>
						</div>

						<div class="box clearfix">
							<div class="box-head pad-5 m-bottom-10">
								<label><i class="fa fa-unlock-alt fa-lg"></i> Select Access</label>
							</div>		

							<?php if($this->session->userdata('is_admin') ==  1): ?>				

							<?php $is_admin_set = (isset($_POST['chk_is_admin']) && $_POST['chk_is_admin'] == 1 ? 1 : 0);  ?>

							<div class="col-xs-12 m-bottom-10 clearfix">
								<label for="is_admin" class="col-sm-3 control-label m-top-5">Is Admin</label>
								<div class="col-sm-9">
									<input type="checkbox" name="" class="check-swtich is_admin" id="is_admin" data-label-text="Admin" <?php echo ($is_admin_set == 1 ? 'checked="true"' : ''); ?> >
									<input type="hidden" class="" id="chk_is_peon" name="chk_is_peon" value="<?php echo $is_admin_set; ?>">
								</div>
							</div>

						<?php else: ?>
							<input type="hidden" class="" id="chk_is_peon" name="chk_is_peon" value="0">
						<?php endif; ?>

							<?php $dashboard_access_set = (isset($_POST['dashboard_access'])  ? $_POST['dashboard_access'] : 0);  ?>

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

							<?php $company_access_set = (isset($_POST['company_access']) ? $_POST['company_access'] : 0);  ?>

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

							<?php $projects_access_set = (isset($_POST['projects_access']) ? $_POST['projects_access'] : 0);  ?>

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

							<?php $wip_access_set = (isset($_POST['wip_access']) ? $_POST['wip_access'] : 0);  ?>

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

							<?php $purchase_orders_access_set = (isset($_POST['purchase_orders_access']) ? $_POST['purchase_orders_access'] : 0);  ?>

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

							<?php $invoice_access_set = (isset($_POST['invoice_access']) ? $_POST['invoice_access'] : 0);  ?>

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

							<?php $users_access_set = (isset($_POST['users_access']) ? $_POST['users_access'] : 0);  ?>

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

							<?php $bulletin_board_set = (isset($_POST['bulletin_board']) ? $_POST['bulletin_board'] : 0);  ?>

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
	
							<?php $project_schedule_set = (isset($_POST['project_schedule']) ? $_POST['project_schedule'] : 0);  ?>

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

							<?php $labour_schedule_set = (isset($_POST['labour_schedule']) ? $_POST['labour_schedule'] : 0);  ?>

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

							<div class="clearfix"></div><br />
						</div>
						<p><br /><br /></p>
						<div class="clearfix"></div>


						</div>
					</form>
				</div>				
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	

  $('select.user-role-selection').on("change", function(e) {
  	var data = $(this).val();
  	var data_set = data.split("|");

  	if(data_set[0] == 2){
  		$('.div_pm_for_pa').show();
  		$('select.pm_for_pa').val('');  
  	}else{
  		$('.div_pm_for_pa').hide();
  		$('select.pm_for_pa').val('');  		
  	}

});

</script>

<?php $this->load->view('assets/logout-modal'); ?>
<?php if(!isset($_POST['is_form_submit'])){	echo '<script type="text/javascript">$("#first_name").focus();</script>';}?>