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
		<div class="section col-sm-12 col-md-11 col-lg-11 basic">
			<div class="container-fluid">

				<div class="row hidden">
					<div class="col-xs-12">
						<div class="border-less-box alert alert-danger fade in">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h4>Oh snap! You got an error!</h4>
							<p>Change this and that and try again. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.</p>
							<p>
								<button type="button" class="btn btn-danger" id="loading-example-btn"  data-loading-text="Loading..." >Take this action</button>
								<button type="button" class="btn btn-default">Or do this</button>
							</p>
						</div>
					</div>
				</div>

				<div class="row">
					
					<div class="col-md-9">
						<div class="left-section-box">
							<div class="box-head pad-10 clearfix">
								<a href="./add" class="btn btn-primary pull-right"><i class="fa fa-briefcase"></i>&nbsp; Add New</a>
								<label><?php echo $screen; ?></label><span> (<a href="#" data-placement="right" class="popover-test" title="" data-content="Hello there mate! Welcome to the clients screen." data-original-title="Welcome">?</a>)</span>
								<p><a href="#" class="tooltip-test" title="Tooltip">This link</a> and <a href="#" class="tooltip-test" title="Tooltip">that link</a> should have tooltips on hover.</p>								
							</div>
							<div class="box-area clearfix">

								<?php if(@$matrix_errors): ?>
									<div class="no-pad-t m-bottom-10">
										<div class="border-less-box alert alert-danger fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>Oh snap! You got an error!</h4>
											<?php echo $matrix_errors;?>
										</div>
									</div>
								<?php endif; ?>


								<?php if(@$this->session->flashdata('update_matrix')): ?>
									<div class="no-pad-t m-bottom-10">
										<div class="border-less-box alert alert-success fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>Cheers!</h4>
											<?php echo $this->session->flashdata('update_matrix');?>
										</div>
									</div>
								<?php endif; ?>


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

									

										

										<?php foreach ($all_focus_company as $key => $value): ?>
											
											<div class="col-md-6 col-sm-6 col-lg-4 col-xs-12 box-widget">
													<div class="box wid-type-a"

													<?php 

														if ($value->company_name == 'FSF Group Pty Ltd'){
															echo 'style="background: #00ADEF; border: 1px solid #2D8EB3;"';
														} else if ($value->company_name == 'Focus Shopfit Pty Ltd') {
															echo 'style="background: #F779B5; border: 1px solid #B97294;"';
														} else {
															echo '';
														}

													?>>

														<div class="widg-head box-widg-head pad-5"

														<?php 

															if ($value->company_name == 'FSF Group Pty Ltd'){
																echo 'style="opacity: 0.5; background: #fff !important; color: #00ADEF !important;"';
															} else if ($value->company_name == 'Focus Shopfit Pty Ltd') {
																echo 'style="opacity: 0.5; background: #fff !important; color: #F779B5 !important;"';
															} else {
																echo 'style="opacity: 0.5; background: #fff !important; color: #F7901E !important;"';
															}
															
														?>>

														<?php echo $value->state_name; ?> <span class="sub-h pull-right"></span></div>							
																													<div class="box-area pad-5 text-center">
																<i class="fa fa-user pull-left fa-4x widg-icon-inside"></i>
																							
																<h3><?php echo $value->company_name; ?></h3>


																<?php

																$office_number = $value->office_number;
																$expOffnum = explode(' ',$office_number);
																$posExpOffnum = strpos($expOffnum[0],'1300');
																$area_code = $value->area_code;

																if($posExpOffnum === false){ }else{
																	$area_code = '';
																}

																$posExpOffnumB = strpos($expOffnum[0],'1800');

																if($posExpOffnumB === false){ }else{
																	$area_code = '';
																}
																?>


																<p><strong>Office Number:</strong> <?php echo $area_code.' '.$value->office_number; ?></p>



																<p><strong>Email:</strong> <a style="color:#fff;" href="mailto:<?php echo $value->general_email; ?>?Subject=Inquiry" target="_blank" ><?php echo $value->general_email; ?></a></p>
																<hr class="pad-5">
																<a href="<?php echo base_url(); ?>admin/admin_company/<?php echo $value->company_id; ?>""><p>view details</p></a>
															</div>
													</div>
												</div>

										<?php endforeach; ?>
								
										

									</div>
					


								

								


								

								

								<!-- <div class="box">
									<div class="box-head pad-5 m-bottom-5">
										<label><i class="fa fa-phone-square fa-lg"></i> Box</label>
									</div>

									<div class="box-area pad-5 clearfix">
										<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix ">
											<label for="input" class="col-sm-3 control-label">Input</label>
											<div class="col-sm-9">
												<input type="text" class="form-control" id="input" name="input" placeholder="Input" value="">
											</div>
										</div>

										<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix ">
											<label for="input" class="col-sm-3 control-label">Input</label>
											<div class="col-sm-9">
												<input type="text" class="form-control" id="input" name="input" placeholder="Input" value="">
											</div>
										</div>

										<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix ">
											<label for="input" class="col-sm-3 control-label">Input</label>
											<div class="col-sm-9">
												<input type="text" class="form-control" id="input" name="input" placeholder="Input" value="">
											</div>
										</div>

										<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix ">
											<label for="input" class="col-sm-3 control-label">Input</label>
											<div class="col-sm-9">
												<input type="text" class="form-control" id="input" name="input" placeholder="Input" value="">
											</div>
										</div>
									</div>
								</div>	 -->							
							</div>
						</div>
					</div>					

					<div class="col-md-3">
						
						<div class="box">
							<div class="box-head pad-5">
								<label><i class="fa fa-info-circle fa-lg"></i> Information</label>
							</div>
							<div class="box-area pad-10" id="container">
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
								<p class="pop-test"></p>
							</div>
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