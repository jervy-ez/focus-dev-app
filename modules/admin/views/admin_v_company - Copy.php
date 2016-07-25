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
					<form class="form-horizontal company-form" role="form" method="post" action="">
						<div class="col-md-9">
							<div class="left-section-box">								
								
					
								
									<div class="box-head pad-top-15 pad-left-15 pad-bottom-10 clearfix">

										<div id="edit_company_name" class="btn btn-warning pull-right btn-md m-left-10"><i class="fa fa-pencil-square-o"></i> Edit</div>
										<div id="save_company_name" class="btn btn-success pull-right btn-md" style="display:none;"><i class="fa fa-floppy-o"></i> Save</div>
										<div id="delete_company" class="btn btn-danger btn-md pull-left m-right-10" style="display:none"><i class="fa fa-trash-o"></i> Delete Company</div>	
										<label class="company_name" ><?php echo $company_name; ?></label>

										<div class="input-group company_name_data pad-right-10" style="display:none;">
											<span class="input-group-addon"><i class="fa fa-briefcase fa-lg"></i></span>
											<input type="text" class="form-control" placeholder="Company Name*" name="company_name_data" id="company_name_data" value="<?php echo $company_name; ?>">
										</div>

										<input type="hidden" name="company_id_data" id="company_id_data" value="<?php echo $company_id; ?>"> <!-- this is the company_details_id -->

									</div>
									<div class="box-area">

									<div class="tab-pane fade active in clearfix pad-10" id="profile">

													<!-- Physical Address -->

													<div class="col-sm-12 m-bottom-10 clearfix">
														<a href="#" id="edit-address" class="btn btn-warning pull-right btn-sm"><i class="fa fa-pencil-square-o"></i> Edit</a>
														<div class="section-header"><i class="fa fa-globe fa-lg"></i> <strong>Physical Address</strong></div>
													</div>
													
													<div class="col-sm-4 m-bottom-10 clearfix">
														<p class="m-top-10"><strong>Unit/Level</strong>: <?php echo $unit_level; ?></p>
														<p class="m-top-10"><strong>Number</strong>: <?php echo $unit_number; ?></p>
														<p class="m-top-10"><strong>Street</strong>: <?php echo $street; ?></p>
													</div>

													<div class="col-sm-4 m-bottom-10 clearfix">
														<p class="m-top-10"><strong>State</strong>: <?php echo $state; ?></p>
														<p class="m-top-10"><strong>Suburb</strong>: <?php echo ucwords(strtolower($suburb)); ?></p>
														<p class="m-top-10"><strong>Postcode</strong>: <?php echo $postcode; ?></p>
													</div>

													<!-- Physical Address -->

													<!-- Postal Address -->

													<div class="col-sm-12 m-bottom-10 clearfix">
														<a href="#" id="edit-address" class="btn btn-warning pull-right btn-sm"><i class="fa fa-pencil-square-o"></i> Edit</a>
														<div class="section-header"><i class="fa fa-inbox fa-lg"></i> <strong>Postal Address</strong></div>
													</div>

													<div class="col-sm-4 m-bottom-10 clearfix">
														<p class="m-top-10"><strong>PO Box</strong>: <?php echo $p_po_box; ?></p>
														<p class="m-top-10"><strong>Unit/Level</strong>: <?php echo $p_unit_level; ?></p>
														<p class="m-top-10"><strong>Number</strong>: <?php echo $p_unit_number; ?></p>
														<p class="m-top-10"><strong>Street</strong>: <?php echo $p_street; ?></p>
													</div>

													<div class="col-sm-4 m-bottom-10 clearfix">
														<p class="m-top-10"><strong>State</strong>: <?php echo $p_state; ?></p>
														<p class="m-top-10"><strong>Suburb</strong>: <?php echo ucwords(strtolower($p_suburb)); ?></p>
														<p class="m-top-10"><strong>Postcode</strong>: <?php echo $p_postcode; ?></p>
													</div>

													<!-- Postal Address -->



													<!-- Bank Account Details -->
													<div class="col-sm-12 m-bottom-10 m-top-10 clearfix">
														<a href="#" id="edit-address" class="btn btn-warning pull-right btn-sm"><i class="fa fa-pencil-square-o"></i> Edit</a>
														<div class="section-header"><i class="fa fa-university fa-lg"></i> <strong>Bank Account</strong></div>
													</div>

													<div class="col-sm-4 m-bottom-10 clearfix data-area" id="">
														<div id="">
															<p class="m-top-10 bank_name"><strong>Bank Name</strong>: <?php echo $bank_name; ?></p>
															<p class="m-top-10 bank_account_name"><strong>Account Name</strong>: <?php echo $bank_account_name; ?></p>												
														</div>																												
													</div>

													<div class="col-sm-4 m-bottom-10 clearfix data-area" id="">
														<div id="">
															<p class="m-top-10 bank_account_number"><strong>Account Number</strong>: <?php echo $bank_account_number; ?></p>
															<p class="m-top-10 bank_bsb_number"><strong>BSB Number</strong>: <?php echo $bank_bsb_number; ?></p>													
														</div>																												
													</div>
													<!-- Bank Account Details -->



													<!-- More Details -->

													<div class="col-sm-12 m-bottom-10 m-top-10 clearfix">
														<a href="#" id="edit-address" class="btn btn-warning pull-right btn-sm"><i class="fa fa-pencil-square-o"></i> Edit</a>
														<div class="section-header"><i class="fa fa-list-alt fa-lg"></i> <strong>More Details</strong></div>
													</div>

													
													<div class="col-sm-4 m-bottom-10 clearfix">
														<div class="section-sub-header"><em>Business</em></div>														

															<?php if(isset($abn)){ echo '<p class="m-top-10"><strong>ABN</strong>: '.$abn.'</p>';} ?>
															<?php if(isset($acn)){ echo '<p class="m-top-10"><strong>ACN</strong>: '.$acn.'</p>';} ?>

													</div>

													<div class="col-sm-4 m-bottom-10 clearfix">
														<div class="section-sub-header"><em>Jurisdiction Sates</em></div>
														<?php $jurisdiction = explode(',', $admin_jurisdiction_state_ids); ?>

														<ul>															
															<?php foreach ($jurisdiction  as $jur_key => $jur_value): ?>
																
																<li>
																<?php foreach ($all_aud_states  as $key => $value): ?>
																	<?php if( $jur_value == $value->id ){ echo $value->name; } ?>

																<?php endforeach; ?>
																</li>
																
															<?php endforeach; ?>
														</ul>

															

													</div>

													<!-- More Details -->




													<!-- Contact Details -->

													<div class="col-sm-12 m-bottom-10 m-top-10 clearfix">
														<a href="#" id="edit-address" class="btn btn-warning pull-right btn-sm"><i class="fa fa-pencil-square-o"></i> Edit</a>
														<div class="section-header"><i class="fa fa-phone-square fa-lg"></i> <strong>Contact Details</strong></div>
													</div>
													
													<div class="col-sm-4 m-bottom-10 clearfix">
														<div class="section-sub-header"><em>Telephone</em></div>
														<?php if(isset($office_number)){ echo '<p class="m-top-10"><strong>Office</strong>: '.$area_code.' '.$office_number.'</p>';} ?>
														<?php if(isset($direct_number)){ echo '<p class="m-top-10"><strong>Direct</strong>: '.$direct_number.'</p>';} ?>
														<?php if(isset($mobile_number)){ echo '<p class="m-top-10"><strong>Mobile</strong>: '.$mobile_number.'</p>';} ?>

													</div>

													<div class="col-sm-4 m-bottom-10 clearfix">
														<div class="section-sub-header"><em>Email</em></div>
														<?php if(isset($general_email)){ echo '<p class="m-top-10"><strong>General</strong>: '.$general_email.'</p>';} ?>														
													</div>

													<div class="col-sm-4 m-bottom-10 clearfix"></div>


													<!-- Contact Details -->

												
												</div>
	      							
										
										<div class="clearfix"></div>


									</div>
															
							</div>
						</div>
						
						<div class="col-md-3">
							
							<div class="box">
								<div class="box-head pad-5">
									<label><i class="fa fa-info-circle fa-lg"></i> Information</label>
								</div>
								<div class="box-area pad-5" id="container">
									<p>This is the company profile screen, this where you can see all information about your selected company.</p>
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
												<div><a class="news-item-title" href="#">You added a new company</a><p class="news-item-preview">May 25, 2014</p></div>
											</li>
											<li>
												<div><a class="news-item-title" href="#">Updated the company details and contact information for James Tiling Co.</a><p class="news-item-preview">May 20, 2014</p></div>
											</li>
										</ul>
										<div class="box-collapse">
											<a data-target=".more-list" data-toggle="collapse" style="cursor: pointer;"> Show More </a>
										</div>
										<ul class="more-list collapse out">
											<li>
												<div><a class="news-item-title" href="#">Duis aute irure dolor in reprehenderit</a><p class="news-item-preview">Lorem ipsum dolor si labore et dolore.</p></div>
											</li>
											<li>
												<div><a class="news-item-title" href="#">Duis aute irure dolor in reprehenderit</a><p class="news-item-preview">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore.</p></div>
											</li>
											<li>
												<div><a class="news-item-title" href="#">Duis aute irure dolor in reprehenderit</a><p class="news-item-preview">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore.</p></div>
											</li>
										</ul>
									</div>
								</div>
							</div>

						</div>
					</form>
				</div>				
			</div>
		</div>
	</div>
</div>
<script>
    var base_url = '<?php echo site_url(); //you have to load the "url_helper" to use this function ?>';
</script>
<?php $this->load->view('assets/logout-modal'); ?>