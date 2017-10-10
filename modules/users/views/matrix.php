<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('bulletin_board'); ?>
<?php $color_group = array('000','4DAB4D','935FA6','795548','00ADEF','F779B5','F7901E','4DAB4D','935FA6','795548'); ?>

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
					<?php if($this->session->userdata('users') > 0 || $this->session->userdata('is_admin') ==  1): ?>
						<li>
							<a href="<?php echo base_url(); ?>users/account/<?php echo $this->session->userdata('user_id'); ?>"><i class="fa fa-cog"></i> My Account</a>
						</li>
					<?php endif; ?>
					<?php if($this->session->userdata('is_admin') == 1 ): ?>
						<li>
							<a href="<?php echo base_url(); ?>admin" class="btn-small">Defaults</a>
						</li>
						<li>
							<a href="<?php echo base_url(); ?>admin/company" class="btn-small">Company</a>
						</li>
						<li>
							<a href="<?php echo base_url(); ?>users/user_logs">User Logs</a>
						</li>
					<?php endif; ?>
						<li>
							<a href="<?php echo base_url(); ?>users/leave_details/<?php echo $this->session->userdata('user_id'); ?>">My Leave Requests</a>
						</li>
					<?php if ($this->session->userdata('user_id') == 3 || $this->session->userdata('user_id') == 21 || $this->session->userdata('user_id') == 9 || $this->session->userdata('user_id') == 22 || $this->session->userdata('user_id') == 15 || $this->session->userdata('user_id') == 27 || $this->session->userdata('user_id') == 20 || $this->session->userdata('user_id') == 42 || $this->session->userdata('user_id') == 16): ?>
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
			<div class="container-fluid basic">



				<div class="row">
					
					<div class="col-md-12">
						<div class="left-section-box">
							<div class="box-head pad-10 clearfix">						

								<label><?php echo $screen; ?></label><span> (<a href="#" data-placement="right" class="popover-test" title="" data-content="Hello there mate! This is the new Focus organizational chart and resposibility matrix screen." data-original-title="Welcome">?</a>)</span>
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
 
<p><br /></p>
										 <h4>Company Chart</h4>
										 <hr style="margin: 5px 0;">


										 <style type="text/css">

										 	.box-area a,.box-area h3{
										 		color: #fff !important;

										 	}

										 	.box-area a:hover{
										 		text-transform:none !important;
										 		text-decoration: none !important;
										 	}

										 	.gray_color{
												border: 3px solid gray !important;
										 	}

										 	.gray_color{
												border: 3px solid #555 !important;
										 	}

										 	.gray_color + p + p{
												color: #555 !important;

										 	}

										 	 .gray_color + p strong{
													background: #555 !important;
													color: #fff !important;
													padding: 3px 6px;
													border-radius: 6px;
												}

										 </style>



<div class="box-area clearfix">

<?php echo $this->users->loop_compamy_group(4); ?>


										<?php foreach ($all_focus_company as $key => $value): ?>
											
										
												<style type="text/css">

												.user_<?php echo $value->company_id; ?>_comp_group{
													border: 3px solid #<?php echo $color_group[$value->company_id]; ?>;
												}

												.user_<?php echo $value->company_id; ?>_comp_group + p strong{
													background: #<?php echo $color_group[$value->company_id]; ?>;
													color: #fff;
													padding: 3px 6px;
													border-radius: 6px;
												}


												.user_<?php echo $value->company_id; ?>_comp_group + p + p{
													color: #<?php echo $color_group[$value->company_id]; ?>;
													font-weight: bold;
													    margin-top: -5px;
												}



 

			.wid-type-<?php echo $value->company_id; ?>_comp_group .widg-head{
				opacity: 0.5;
				background: #fff !important;

				color: #<?php echo $color_group[$value->company_id]; ?> !important;
				padding-left: 20px;
			}

			.wid-type-<?php echo $value->company_id; ?>_comp_group{
																	background: #<?php echo $color_group[$value->company_id]; ?> !important;
 
			}
 





												</style>

										<?php endforeach; ?>

										</div>
									

									<p><br /></p>

									<p><br /></p>

										 <h4>Responsibility Matrix</h4>
										<hr style="margin: 5px 0;">

										 <?php $this->users->loop_user_supervisor(); ?>

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