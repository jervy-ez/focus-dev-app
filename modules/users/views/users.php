<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('bulletin_board'); ?>
<?php $leave_requests = $this->session->userdata('leave_requests'); ?>
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
						<a href="<?php echo base_url(); ?>users/company_matrix"><i class="fa fa-university"></i> Org. Chart</a>
					</li>


					<?php if($this->session->userdata('users') > 0 || $this->session->userdata('is_admin') ==  1): ?>
						<li>
							<a href="<?php echo base_url(); ?>users/account/<?php echo $this->session->userdata('user_id'); ?>"><i class="fa fa-cog"></i> My Account</a>
						</li>
					<?php endif; ?>
					<?php if($this->session->userdata('is_admin') == 1 ): ?>
						<li>
							<a href="<?php echo base_url(); ?>users/user_logs">User Logs</a>
						</li>
					<?php endif; ?>
						<li>
							<a href="<?php echo base_url(); ?>users/leave_details/<?php echo $this->session->userdata('user_id'); ?>">My Leave Requests</a>
						</li>
					<?php if ($leave_requests == 1): ?>
						<li>
							<a href="<?php echo base_url(); ?>users/leave_approvals/<?php echo $this->session->userdata('user_id'); ?>">Leave Approvals</a>
						</li>
					<?php endif; ?>
				</ul>
			</div>

		</div>
	</div>
</div>
<!-- title bar -->


												<style type="text/css">
													.gray{
														background: #555 !important;
														border-color: #000 !important;
													}

													.gray .box-widg-head{
														background: #9e9e9e !important;

													}

												</style>


<div class="container-fluid">
	<!-- Example row of columns -->
	<div class="row">				
		<?php $this->load->view('assets/sidebar'); ?>
		<div class="section col-sm-12 col-md-11 col-lg-11">
			<div class="container-fluid basic">



				<div class="row">
					
					<div class="col-md-12">
						<div class="left-section-box">
							<div class="box-head pad-10 clearfix">						


								<?php if($this->session->userdata('is_admin') == 1 || $this->session->userdata('users') > 1): ?><a href="./users/add" class="btn btn-primary pull-right"><i class="fa fa-briefcase"></i>&nbsp; Add New</a><?php endif; ?>
								<label><?php echo $screen; ?></label><span> (<a href="#" data-placement="right" class="popover-test" title="" data-content="Hello there mate! Welcome to the users screen." data-original-title="Welcome">?</a>)</span>
								<p class="hide"><a href="#" class="tooltip-test" title="Tooltip">This link</a> and <a href="#" class="tooltip-test" title="Tooltip">that link</a> should have tooltips on hover.</p>								
							</div>
							<div class="box-area clearfix">


								<?php if(@$this->session->flashdata('new_focus_company')): ?>
									<div class="no-pad-t m-bottom-10 pad-left-10">
										<div class="border-less-box alert alert-success fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>Congratulations!</h4>
											<?php echo $this->session->flashdata('new_focus_company');?>
										</div>
									</div>
								<?php endif; ?>
								
									<div class="row clearfix pad-left-15  pad-right-15 pad-bottom-10">

										<?php $focus_comp_group_checker = ''; $counter = 0; ?>	
										<?php $focus_comp_group = ''; ?>

										
										<?php $wid_type = ''; ?>

										
										<?php foreach($users as $key => $user): ?>
											<?php $focus_comp_group = $user->company_name; $gray_color = '';?>






 


												<?php if($focus_comp_group_checker == ''): ?>
													<?php $focus_comp_group_checker = $user->company_name; $wid_type = 'c'; ?>
													<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12"><div class="text-center pad-top-10"><h4>FSF Group Pty Ltd</h4></div> </div>
												<?php endif; ?>



												<?php if($focus_comp_group != $focus_comp_group_checker  ): ?>
													<?php $focus_comp_group_checker = $user->company_name; $wid_type = 'b'; ?>

													<?php if($counter > 1): ?>
													<div class="clearfix"></div>
													<p>&nbsp;<br /></p>

													<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12"><div class="text-center"><h4><?php echo $user->company_name; ?></h4></div> </div>
													<?php endif; ?>
												<?php endif; ?>

												<?php if( $user->company_name == 'Focus Shopfit NSW Pty Ltd'){ $wid_type = 'a'; } ?>

 																
											<?php if($user->is_third_party == 1 ){ $gray_color = 'gray';	} ?>
 

												<div class="col-md-6 col-sm-6 col-lg-4 col-xs-12 box-widget">
													<div class="box wid-type-<?php echo $wid_type.' '.$gray_color; ?>">
														<div class="widg-head box-widg-head pad-5"><?php echo $user->department_name; ?> <span class="sub-h pull-right"><?php echo $user->company_name; ?></span></div>							
														<?php if($user->user_profile_photo == ''): ?>
															<div class="box-area pad-5 text-center">
																<i class="fa <?php echo ($user->role_types == 'Administrator' ? 'fa-user-secret' : 'fa-user'); ?> pull-left fa-4x widg-icon-inside"></i>
																							
																<p>Role: <?php echo $user->role_types; ?></p>
																<h2><?php echo $user->user_first_name; ?></h2>
																<p><?php echo $user->user_last_name; ?></p>
																<hr class="pad-5">
																<a href="<?php echo base_url('users/account/'.$user->user_id); ?>"><p>view details</p></a>
															</div>

														<?php else: ?>


															<div class="box-area pad-5 text-left">
																<div style="float: left; overflow: hidden; height: 90px; ">
																	<img src="<?php echo base_url(); ?>uploads/users/<?php echo $user->user_profile_photo; ?>" style="margin: 5px 5px; width: 85px;">
																</div>
																							
																<p>Role: <?php echo $user->role_types; ?></p>
																<h2><?php echo $user->user_first_name; ?></h2>
																<p><?php echo $user->user_last_name; ?></p>
																<hr class="pad-5">
																<a class="text-center" href="<?php echo base_url('users/account/'.$user->user_id); ?>"><p>view details</p></a>
															</div>

														<?php endif; ?>


													</div>
												</div>

												<?php $counter++; ?>


					

										<?php endforeach; ?>						
										

									</div>
					
							



							</div>
						</div>
					</div>					

					<!--<div class="col-md-3">
						
						<div class="box">
							<div class="box-head pad-5">
								<label><i class="fa fa-info-circle fa-lg"></i> Users Currently Logged-In</label>
								<button type = "button" class = "btn btn-primary btn-sm pull-right" id = "btn_logout_user">Log-out User</button>
							</div>
							<div class="box-area pad-10" id="login_user_list" style = "height: 200px; overflow: auto">								
							</div>
						</div>
					</div>-->
					
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

<?php $this->bulletin_board->list_latest_post(); ?>
<?php $this->load->view('assets/logout-modal'); ?>