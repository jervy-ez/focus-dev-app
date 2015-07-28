<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>
<?php
	if($this->session->userdata('company') >= 2 ){

	}else{
		echo '<style type="text/css">.admin_access{ display: none !important;visibility: hidden !important;}</style>';
	}
?>
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
					<li class="active">
						<a href="<?php echo base_url(); ?>company" class="btn-small">Clients</a>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>company/contractor" class="btn-small">Contractor</a>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>company/supplier" class="btn-small">Supplier</a>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>shopping_center" class="btn-small"><i class="fa fa-shopping-cart"></i> Shopping Center</a>
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
					<form class="form-horizontal company-form" role="form" method="post" action="">
						<div class="col-md-9">
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
								
								<?php if(@$success): ?>
								<div class="pad-10 no-pad-t">
									<div class="border-less-box alert alert-success fade in">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										<h4>Congratulations!</h4>
										<?php echo $success;?>
									</div>
								</div>
								<?php endif; ?>	
								
								<?php if(@$this->session->flashdata('update_company_id')): ?>
								<div class="pad-10 no-pad-t">
									<div class="border-less-box alert alert-success fade in">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										<h4>Cheers!</h4>
										<?php echo $this->session->flashdata('update_message');?>
									</div>
								</div>
								<?php endif; ?>	
								
													
								
								
					
								
									<div class="box-head pad-top-15 pad-left-15 pad-bottom-10 clearfix">
										<div id="edit_company_name" class="btn btn-warning pull-right btn-md m-left-10 admin_access"><i class="fa fa-pencil-square-o"></i> Edit</div>
										<div id="save_company_name" class="btn btn-success pull-right btn-md admin_access" style="display:none;"><i class="fa fa-floppy-o"></i> Save</div>

										<?php if($this->session->userdata('is_admin') == 1): ?>										
											<div id="delete_company" class="btn btn-danger btn-md pull-left m-right-10 admin_access" style="display:none"><i class="fa fa-trash-o"></i> Delete Company</div>
										<?php endif; ?>
										
										<label class="company_name" ><?php echo $company_name; ?></label>

										<div class="input-group company_name_data pad-right-10" style="display:none;">
											<span class="input-group-addon"><i class="fa fa-briefcase fa-lg"></i></span>
											<input type="text" class="form-control" placeholder="Company Name*" name="company_name_data" id="company_name_data" value="<?php echo $company_name; ?>">
										</div>


										<input type="hidden" name="company_id_data" id="company_id_data" value="<?php echo $company_id; ?>">
									</div>
									<div class="box-area pad-10">
	      								
	      								<div class="box-tabs m-bottom-15">
											<ul id="myTab" class="nav nav-tabs">
												<li class="">
													<a href="#projects" data-toggle="tab"><i class="fa fa-map-marker fa-lg"></i> Projects</a>
												</li>
												<li class="">
													<a href="#invoices" data-toggle="tab"><i class="fa fa-list-alt fa-lg"></i> Invoices</a>
												</li>
												<li class="">
													<a href="#reports" data-toggle="tab"><i class="fa fa-bar-chart-o fa-lg"></i> Reports</a>
												</li>
												<li class="active">
													<a href="#profile" data-toggle="tab"><i class="fa fa-briefcase fa-lg"></i> Profile</a>
												</li>
												<li class="">
													<a href="#contact-person" data-toggle="tab"><i class="fa fa-tty fa-lg"></i> Contact Person</a>
												</li>											
											</ul>
											<div class="tab-content">
												<div class="tab-pane fade in  clearfix" id="projects">
													<div class="col-sm-6 m-bottom-10 clearfix">
														<p>No Projects Yet</p>
														<a href="<?php echo base_url(); ?>projects/add">Add New Project</a>
													</div>
												</div>
												<div class="tab-pane fade in  clearfix" id="invoices">
													<div class="col-sm-6 m-bottom-10 clearfix">
														<p>No Invoices Yet, we must have a project first.</p>
														<a href="<?php echo base_url(); ?>projects/add">Add New Project</a>
													</div>
												</div>
												<div class="tab-pane fade in  clearfix" id="reports">
													<div class="col-sm-6 m-bottom-10 clearfix">
														<p>No Reports Yet, we must have a project first.</p>
														<a href="<?php echo base_url(); ?>projects/add">Add New Project</a>
													</div>
												</div>
												
												<div class="tab-pane fade active in clearfix pad-10" id="profile">

													<!-- Physical Address -->

													<input type="hidden" name="physical_address_id_data" id="physical_address_id_data" value="<?php echo $address_id; ?>">

													<div class="col-sm-12 m-bottom-10 clearfix">
														<div id="edit_physical_address" class="btn btn-warning pull-right btn-sm m-right-10 admin_access"><i class="fa fa-pencil-square-o"></i> Edit</div>
														<div id="save_physical_address" class="btn btn-success pull-right btn-sm m-right-10 admin_access" style="display:none;"><i class="fa fa-floppy-o"></i> Save</div>

														<div class="section-header"><i class="fa fa-globe fa-lg"></i> <strong>Physical Address</strong></div>
													</div>
													
													<div class="col-sm-4 m-bottom-10 clearfix physical_address_group">
														<p class="m-top-10"><strong>Unit/Level</strong>: <span class="data-unit_level"><?php echo $unit_level; ?></span></p>
														<p class="m-top-10"><strong>Number</strong>: <span class="data-unit_number"><?php echo $unit_number; ?></span></p>
														<p class="m-top-10"><strong>Street</strong>: <span class="data-street"><?php echo $street; ?></span></p>
													</div>

													<div class="col-sm-4 m-bottom-10 clearfix physical_address_group">
														<p class="m-top-10"><strong>State</strong>: <span class="data-state"><?php echo $state; ?></span></p>
														<p class="m-top-10"><strong>Suburb</strong>: <span class="data-suburb" style="text-transform: capitalize;"><?php echo ucwords(strtolower($suburb)); ?></span></p>
														<p class="m-top-10"><strong>Postcode</strong>: <span class="data-postcode"><?php echo $postcode; ?></span></p>
													</div>

													<div class="col-sm-12 m-bottom-10 m-top-10 clearfix physical_address_group_data" style="display:none;">
														<div class="col-sm-6 m-bottom-10 clearfix">
															<label for="unit_level" class="col-sm-3 control-label">Unit/Level</label>
															<div class="col-sm-9 m-bottom-10">
																<input type="text" class="form-control" id="unit_level" placeholder="Unit/Level" name="unit_level" value="<?php echo $unit_level; ?>">
															</div>
														
															<label for="number" class="col-sm-3 control-label">Number</label>
															<div class="col-sm-9 m-bottom-10">
																<input type="text" class="form-control" id="number" placeholder="Number" name="unit_number" value="<?php echo $unit_number; ?>">
															</div>
														
															<label for="street" class="col-sm-3 control-label">Street*</label>
															<div class="col-sm-9 m-bottom-10">
																<input type="text" class="form-control" id="street" placeholder="Street" name="street" value="<?php echo $street; ?>">
															</div>
														</div>

														<div class="col-sm-6 m-bottom-10 clearfix">

															<label for="state" class="col-sm-3 control-label">State*</label>													
															<div class="col-sm-9  m-bottom-10">
																<select class="form-control state-option-a chosen"  id="state_a" name="state_a">															
																	<option value="">Choose a State</option>
																	<?php
																	foreach ($all_aud_states as $row){
																		echo '<option value="'.$row->shortname.'|'.$row->name.'|'.$row->phone_area_code.'|'.$row->id.'">'.$row->name.'</option>';
																	}?>
																</select>
																<script type="text/javascript">$("select#state_a").val("<?php echo $shortname.'|'.$state.'|'.$phone_area_code.'|'.$state_id; ?>");</script>
															</div>

															<label for="suburb" class="col-sm-3 control-label">Suburb*</label>
															<div class="col-sm-9 col-xs-12  m-bottom-10">
																<select class="form-control suburb-option-a chosen" id="suburb_a" name="suburb_a">
																	<?php $this->company->get_suburb_list('dropdown|state_id|'.$suburb.'|'.$state.'|'.$phone_area_code.'|'.$state_id); ?>
																</select>
																<script type="text/javascript">$("select#suburb_a").val("<?php echo $suburb.'|'.$state.'|'.$phone_area_code; ?>");</script>

															</div>


															<label for="postcode_a" class="col-sm-3 control-label">Postcode*</label> 											
															<div class="col-sm-9  col-xs-12">
																<select class="form-control postcode-option-a chosen" id="postcode_a" name="postcode_a">
																	<?php $this->company->get_post_code_list($suburb); ?>																	
																</select>
																<script type="text/javascript">$("select#postcode_a").val("<?php echo $postcode; ?>");</script>																					
															</div>

														</div>
													</div>												


													<!-- Physical Address -->

													<!-- Postal Address -->

													<input type="hidden" name="postal_address_id_data" id="postal_address_id_data" value="<?php echo $postal_address_id; ?>">

													<div class="col-sm-12 m-bottom-10 clearfix">
														<div id="edit_postal_address" class="btn btn-warning pull-right btn-sm m-right-10 admin_access"><i class="fa fa-pencil-square-o"></i> Edit</div>
														<div id="save_postal_address" class="btn btn-success pull-right btn-sm m-right-10 admin_access" style="display:none;"><i class="fa fa-floppy-o"></i> Save</div>

														<div class="section-header"><i class="fa fa-inbox fa-lg"></i> <strong>Postal Address</strong></div>
													</div>

													<div class="col-sm-4 m-bottom-10 clearfix postal_address_group">
														<p class="m-top-10"><strong>PO Box</strong>: <span class="data-po_box"><?php echo $p_po_box; ?></span></p>
														<p class="m-top-10"><strong>Unit/Level</strong>: <span class="data-p_unit_level"><?php echo $p_unit_level; ?></span></p>
														<p class="m-top-10"><strong>Number</strong>: <span class="data-p_number"><?php echo $p_unit_number; ?></span></p>
														<p class="m-top-10"><strong>Street</strong>: <span class="data-p_street"><?php echo $p_street; ?></span></p>
													</div>

													<div class="col-sm-4 m-bottom-10 clearfix postal_address_group">
														<p class="m-top-10"><strong>State</strong>: <span class="data-state"><?php echo $p_state; ?></span></p>
														<p class="m-top-10"><strong>Suburb</strong>: <span class="data-suburb"><?php echo ucwords(strtolower($p_suburb)); ?></span></p>
														<p class="m-top-10"><strong>Postcode</strong>: <span class="data-postcode"><?php echo $p_postcode; ?></span></p>
													</div>

													<div class="col-sm-12 m-bottom-10 m-top-10 clearfix postal_address_group_data" style="display:none;">
														<div class="col-sm-6 m-bottom-10 clearfix">
															<label for="po_box" class="col-sm-3 control-label">PO Box</label>
															<div class="col-sm-9 m-bottom-10">
																<input type="text" class="form-control" id="po_box" placeholder="Unit/Level" name="po_box" value="<?php echo $p_po_box; ?>">
															</div>

															<label for="unit_level" class="col-sm-3 control-label">Unit/Level</label>
															<div class="col-sm-9 m-bottom-10">
																<input type="text" class="form-control" id="p_unit_level" placeholder="Unit/Level" name="unit_level" value="<?php echo $p_unit_level; ?>">
															</div>
														
															<label for="number" class="col-sm-3 control-label">Number</label>
															<div class="col-sm-9 m-bottom-10">
																<input type="text" class="form-control" id="p_number" placeholder="Number" name="unit_number" value="<?php echo $p_unit_number; ?>">
															</div>
														
															<label for="street" class="col-sm-3 control-label">Street*</label>
															<div class="col-sm-9 m-bottom-10">
																<input type="text" class="form-control" id="p_street" placeholder="Street" name="street" value="<?php echo $p_street; ?>">
															</div>
														</div>

														<div class="col-sm-6 m-bottom-10 clearfix">
															<label for="state" class="col-sm-3 control-label">State*</label>													
															<div class="col-sm-9  m-bottom-10">
																<select class="form-control state-option-b chosen"  id="state_b" name="state_b">															
																	<option value="">Choose a State</option>
																	<?php
																	foreach ($all_aud_states as $row){
																		echo '<option value="'.$row->shortname.'|'.$row->name.'|'.$row->phone_area_code.'|'.$row->id.'">'.$row->name.'</option>';
																	}?>
																</select>
																<script type="text/javascript">$("select#state_b").val("<?php echo $p_shortname.'|'.$p_state.'|'.$p_phone_area_code.'|'.$p_state_id; ?>");</script>
															</div>





															<label for="suburb" class="col-sm-3 control-label">Suburb*</label>
															<div class="col-sm-9 col-xs-12  m-bottom-10">
																<select class="form-control suburb-option-b chosen" id="suburb_b" name="suburb_b">
																	<?php $this->company->get_suburb_list('dropdown|state_id|'.$p_suburb.'|'.$p_state.'|'.$p_phone_area_code.'|'.$p_state_id); ?>
																</select>
																<script type="text/javascript">$("select#suburb_b").val("<?php echo $p_suburb.'|'.$p_state.'|'.$p_phone_area_code; ?>");</script>

															</div>


															<label for="postcode_b" class="col-sm-3 control-label">Postcode*</label> 											
															<div class="col-sm-9  col-xs-12">
																<select class="form-control postcode-option-b chosen" id="postcode_b" name="postcode_b">
																	<?php $this->company->get_post_code_list($p_suburb); ?>																	
																</select>
																<script type="text/javascript">$("select#postcode_b").val("<?php echo $p_postcode; ?>");</script>																					
															</div>

														</div>
													</div>
													<!-- Postal Address -->



													<?php if(isset($bank_name)): ?>

													<input type="hidden" name="postal_address_id_data" id="bank_account_id" value="<?php echo $bank_account_id; ?>">
													<div class="col-sm-12 m-bottom-10 m-top-10 clearfix">
														<div id="edit_bank_details" class="btn btn-warning pull-right btn-sm m-right-10 admin_access"><i class="fa fa-pencil-square-o"></i> Edit</div>
														<div id="save_bank_details" class="btn btn-success pull-right btn-sm m-right-10 admin_access" style="display:none;"><i class="fa fa-floppy-o"></i> Save</div>
														<div class="section-header"><i class="fa fa-university fa-lg"></i> <strong>Bank Account</strong></div>
													</div>

													<div class="col-sm-4 m-bottom-10 clearfix bank_details_group">
														<div id="">
															<p class="m-top-10"><strong>Bank Name</strong>: <span class="data-bank-name"><?php echo $bank_name; ?></span></p>
															<p class="m-top-10"><strong>Account Name</strong>: <span class="data-account-name"><?php echo $bank_account_name; ?></span></p>												
														</div>																												
													</div>

													<div class="col-sm-4 m-bottom-10 clearfix bank_details_group">
														<div id="">
															<p class="m-top-10"><strong>Account Number</strong>: <span class="data-account-number"><?php echo $bank_account_number; ?></span></p>
															<p class="m-top-10"><strong>BSB Number</strong>: <span class="data-bsb-number"><?php echo $bank_bsb_number; ?></span></p>													
														</div>																												
													</div>

													<div class="col-sm-12 m-bottom-10 m-top-10 clearfix bank_details_group_data" style="display:none;">

														<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
															<label for="bank-name" class="col-sm-3 control-label">Bank Name</label>
															<div class="col-sm-9 m-bottom-10">
																<input type="text" class="form-control" id="bank-name" name="bank-name" placeholder="Bank Name" value="<?php echo $bank_name; ?>">
															</div>

															<label for="account-name" class="col-sm-3 control-label">Account Name</label>
															<div class="col-sm-9 m-bottom-10">
																<input type="text" class="form-control" id="account-name" name="account-name"  placeholder="Account Name"  value="<?php echo $bank_account_name; ?>">
															</div>
														</div>

														<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix>">
															<label for="account-number" class="col-sm-3 control-label">Account Number</label>
															<div class="col-sm-9 m-bottom-10">
																<input type="text" class="form-control" id="account-number" name="account-number" placeholder="Account Number" value="<?php echo $bank_account_number; ?>">
															</div>
														
															<label for="bsb-number" class="col-sm-3 control-label">BSB Number</label>
															<div class="col-sm-9 m-bottom-10">
																<input type="text" class="form-control" id="bsb-number" name="bsb-number" placeholder="BSB Number" value="<?php echo $bank_bsb_number; ?>">
															</div>
														</div>
													</div>

													<?php endif; ?>


													<!-- More Details -->

													<input type="hidden" name="company_activity_id" id="company_activity_id" value="<?php echo $company_activity_id; ?>">
													<input type="hidden" name="parent_company_id" id="parent_company_id" value="<?php echo $parent_company_id; ?>">
													<input type="hidden" name="company_type_id" id="company_type_id" value="<?php echo $company_type_id; ?>">

													<div class="col-sm-12 m-bottom-10 m-top-10 clearfix">
														<div id="edit_more_details" class="btn btn-warning pull-right btn-sm m-right-10 admin_access"><i class="fa fa-pencil-square-o"></i> Edit</div>
														<div id="save_more_details" class="btn btn-success pull-right btn-sm m-right-10 admin_access" style="display:none;"><i class="fa fa-floppy-o"></i> Save</div>
														<div class="section-header"><i class="fa fa-list-alt fa-lg"></i> <strong>More Details</strong></div>
													</div>

													<div class="col-sm-4 m-bottom-10 clearfix more_details_group">
														<p class="m-top-10"><strong>ABN</strong>: <span class="data-abn"><?php echo $abn; ?></span></p>
														<p class="m-top-10"><strong>ACN</strong>: <span class="data-acn"><?php echo $acn; ?></span></p>
													</div>

													<div class="col-sm-4 m-bottom-10 clearfix more_details_group">
														<p class="m-top-10"><strong>Type</strong>: <span class="data-company_type"><?php echo $company_type; ?></span></p>
														<p class="m-top-10"><strong>Parent</strong>: <span class="data-parent_company_name"><?php echo $parent_company_name; ?></span></p>
														<p class="m-top-10"><strong>Activity</strong>: <span class="data-company_activity"><?php echo $company_activity; ?></span></p>
													</div>


													<div class="col-sm-12 m-bottom-10 m-top-10 clearfix more_details_group_data" style="display:none;">

														<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
															<label for="bank-name" class="col-sm-3 control-label">ABN</label>
															<div class="col-sm-9 m-bottom-10">
																<input type="text" class="form-control" id="abn" placeholder="ABN" name="abn"   tabindex="16" value="<?php echo $abn; ?>">
															</div>

															<label for="account-name" class="col-sm-3 control-label">ACN</label>
															<div class="col-sm-9 m-bottom-10">
																<input type="text" class="form-control" id="acn" readonly="readonly" placeholder="ACN"    name="acn" value="<?php echo $acn; ?>">
															</div>
														</div>

														<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix>">

															<label for="type" class="col-sm-3 control-label">Type*</label>
															<div class="col-sm-9 m-bottom-10">
																<select class="form-control chosen" id="type" name="type"  tabindex="18" >														
																	<option value="">Choose a Type...</option>
																	<?php
																	foreach ($comp_type_list as $row){
																		echo '<option value="'.$row->company_type.'|'.$row->company_type_id.'">'.$row->company_type.'</option>';
																	}?>
																</select>
																<script type="text/javascript">$("select#type").val("<?php echo $company_type.'|'.$company_type_id; ?>");</script>
																
															</div>


															<label for="parent" class="col-sm-3 control-label">Parent</label>
															<div class="col-sm-9 m-bottom-10">
																<select class="form-control chosen" id="parent" name="parent" tabindex="20" >										
																	<?php $this->company->company_by_type($company_type_id); ?>										
																</select>
																<?php if($parent_company_name != '' && $parent_company_id != ''): ?>																	
																<script type="text/javascript">$("select#parent").val("<?php echo $parent_company_name.'|'.$parent_company_id; ?>");</script>
																<?php endif; ?>																	
															</div>

															<label for="activity" class="col-sm-3 control-label">Activity*</label>
															<div class="col-sm-9">
																<select class="form-control activity chosen" id="activity" name="activity"  tabindex="19" >
																	<?php $this->company->activity($company_type); ?>
																</select>
																<script type="text/javascript">$("select#activity").val("<?php echo $company_activity.'|'.$company_activity_id; ?>");</script>																
															</div>


														</div>
													</div>




													<!-- More Details -->

													<!-- More Details -->

													<div class="col-sm-12 m-bottom-10 m-top-10 clearfix">
														<div id="edit_comment_details" class="btn btn-warning pull-right btn-sm m-right-10 admin_access"><i class="fa fa-pencil-square-o"></i> Edit</div>
														<div id="save_comment_details" class="btn btn-success pull-right btn-sm m-right-10 admin_access" style="display:none;"><i class="fa fa-floppy-o"></i> Save</div>
														<div class="section-header"><i class="fa fa-pencil-square fa-lg"></i> <strong>Comments</strong></div>
													</div>
													
													
													<input type="hidden" name="notes_id" id="notes_id" value="<?php echo $notes_id; ?>">
													<div class="col-sm-12 m-bottom-10 clearfix">
														<p class="m-top-10"><strong>About</strong>: <span  class="comments-data"><?php echo $comments; ?></span></p>
														<textarea class="form-control col-sm-12 comments" name="comments" id="comments" style="display:none;"><?php echo $comments; ?></textarea>
													</div>

													<!-- More Details -->




													<div class="col-sm-4 m-bottom-10 clearfix"></div>



												
												</div>



												<div class="tab-pane fade in  clearfix" id="contact-person">
													<!-- Contact Details -->

													<div class="col-sm-12 m-bottom-10 clearfix">
														<div id="edit_primary_contact" class="btn btn-warning pull-right btn-sm m-left-5 admin_access"><i class="fa fa-pencil-square-o"></i> Edit</div>
														<div id="save_primary_contact" class="btn btn-success pull-right btn-sm m-left-5 admin_access" style="display:none;"><i class="fa fa-floppy-o"></i> Save</div>
														<div class="section-header"><i class="fa fa-phone-square fa-lg"></i> <strong>Primary Contact</strong></div>
													</div>

													<?php foreach ($contact_person_company as $key => $value): ?>

														<?php if($value['is_primary']=='1'): ?>

															<div class="primary-contact-group">

																<div class="col-sm-4 m-bottom-10 clearfix">
																	<?php if(isset($value['first_name'])){ echo '<p class="m-top-10"><strong>First Name</strong>: <span class="data-first_name">'.$value['first_name'].'</span></p>';} ?>
																	<?php if(isset($value['last_name'])){ echo '<p class="m-top-10"><strong>Last Name</strong>: <span class="data-last_name">'.$value['last_name'].'</span></p>';} ?>
																	<?php if(isset($value['gender'])){ echo '<p class="m-top-10"><strong>Gender</strong>: <span class="data-gender">'.$value['gender'].'</span></p>';} ?>
																	<?php if(isset($value['type'])){ echo '<p class="m-top-10"><strong>Type</strong>: <span class="data-type">'.$value['type'].'</span></p>';} ?>
																</div>


																<div class="col-sm-4 m-bottom-10 clearfix">

																	<?php $contact_phone_q = $this->company_m->fetch_phone($value['contact_number_id']); ?>
																	<?php $contact_phone = array_shift($contact_phone_q->result_array()); ?>

																	<?php $contact_email_q = $this->company_m->fetch_email($value['email_id']); ?>
																	<?php $contact_email = array_shift($contact_email_q->result_array()); ?>

																	<?php if($contact_phone['office_number']!=''){ echo '<p class="m-top-10"><strong>Office Contact</strong>: <span class="data-office_number">'.$contact_phone['area_code'].' '.$contact_phone['office_number'].'</span></p>';} ?>
																	<?php if($contact_phone['after_hours']!=''){ echo '<p class="m-top-10"><strong>After Hours</strong>: <span class="data-after_hours">'.$contact_phone['area_code'].' '.$contact_phone['after_hours'].'</span></p>';} ?>
																	<?php if(isset($contact_phone['mobile_number'])){ echo '<p class="m-top-10"><strong>Mobile</strong>: <span class="data-mobile_number">'.$contact_phone['mobile_number'].'</span></p>';} ?>
																	<?php if(isset($contact_email['general_email'])){ echo '<p class="m-top-10"><strong>Email</strong>: <span class="data-general_email"><a href="mailto:'.$contact_email['general_email'].'?Subject=Inquiry">'.$contact_email['general_email'].'</a></span></p>';} ?>					
																</div>
																	<?php //echo $primary_contact_id; ?>

																	<?php $primary_contact_id = $value['contact_person_id']; ?>																	
																	<?php $area_code = $contact_phone['area_code'] ?>
															</div>

													<div class="primary-contact-group-data col-sm-12 m-bottom-10 m-top-10 clearfix" style="display:none;">

														<input type="hidden" id="primary_email_id" name="primary_email_id" value="<?php echo $value['email_id']; ?>">
														<input type="hidden" id="primary_contact_number_id" name="primary_contact_number_id" value="<?php echo $value['contact_number_id']; ?>">
														<input type="hidden" id="primary_contact_person_id" name="primary_contact_person_id" value="<?php echo $value['contact_person_id']; ?>">


														<input type="hidden" id="main_primary_contact_person_company_id" name="main_primary_contact_person_company_id" value="<?php echo $value['contact_person_company_id']; ?>">

														<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
															<label for="bank-name" class="col-sm-3 control-label">First Name</label>
															<div class="col-sm-9 m-bottom-10">
																<input type="text" class="form-control" id="primary_first_name" name="primary_first_name" placeholder="First Name" value="<?php echo $value['first_name']; ?>">
															</div>

															<label for="account-name" class="col-sm-3 control-label">Last Name</label>
															<div class="col-sm-9 m-bottom-10">
																<input type="text" class="form-control" id="primary_last_name" name="primary_last_name"  placeholder="Last Name"  value="<?php echo $value['last_name']; ?>">
															</div>

															<label for="account-name" class="col-sm-3 control-label">Gender</label>
															<div class="col-sm-9 m-bottom-10">
																<select id="primary_contact_gender" class="form-control" name="primary_contact_gender">
																	<option value="">Select</option>
																	<option value="Male">Male</option>
																	<option value="Female">Female</option>
																</select>
																<script type="text/javascript">$("select#primary_contact_gender").val("<?php echo $value['gender']; ?>");</script>
															</div>

															<label for="account-name" class="col-sm-3 control-label">Type</label>
															<div class="col-sm-9 m-bottom-10">
																<select class="form-control" id="primary_contact_type" name="primary_contact_type">
																	<option value="General">General</option>
																	<option value="Maintenance">Maintenance</option>
																	<option value="Accounts">Accounts</option>
																	<option value="Other">Other</option>
																</select>
																<script type="text/javascript">$("select#primary_contact_type").val("<?php echo $value['type']; ?>");</script>
															</div>
														</div>

														
														<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix>">
															<label for="account-number" class="col-sm-3 control-label">Office Contact</label>
															<div class="col-sm-9 m-bottom-10">
																<div class="input-group">
																	<span class="input-group-addon" id="primary_area_code"><?php echo $contact_phone['area_code']; ?></span>
																	<input type="text" class="form-control" id="primary_office_number" placeholder="Office Contact Number" name="primary_office_number" value="<?php echo $contact_phone['office_number']; ?>">
																</div>
															</div>
														
															<label for="bsb-number" class="col-sm-3 control-label">After Hours</label>
															<div class="col-sm-9 m-bottom-10">
																<div class="input-group">
																	<span class="input-group-addon" id="primary_area_code"><?php echo $contact_phone['area_code']; ?></span>
																	<input type="text" class="form-control" id="primary_after_hours" placeholder="After Hours" name="primary_after_hours" value="<?php echo $contact_phone['after_hours']; ?>">
																</div>
															</div>
														
															<label for="bsb-number" class="col-sm-3 control-label">Mobile</label>
															<div class="col-sm-9 m-bottom-10">
																<input type="text" class="form-control" id="primary_mobile_number" name="primary_mobile_number" placeholder="Mobile" value="<?php echo $contact_phone['mobile_number']; ?>">
															</div>
														
															<label for="bsb-number" class="col-sm-3 control-label">Email</label>
															<div class="col-sm-9 m-bottom-10">
																<input type="text" class="form-control" id="primary_general_email" name="primary_general_email" placeholder="Email" value="<?php echo $contact_email['general_email']; ?>">
															</div>
														</div>
													</div>

													
													<?php endif; ?>
												<?php endforeach; ?>

													<div class="clearfix"></div>

													<div class="col-sm-12 m-bottom-10 m-top-10 clearfix">
														<div class="section-header"><i class="fa fa-phone-square fa-lg"></i> <strong>Other Contacts</strong></div>
													</div>

													<?php foreach ($contact_person_company as $key => $other_value): ?>

															<?php if($other_value['is_primary']!='1'): ?>


														<div id="edit_other_contact_<?php echo $key; ?>" class="btn btn-warning pull-right btn-sm m-left-5 edit_other_contact admin_access"><i class="fa fa-pencil-square-o"></i> Edit</div>
														<div id="save_other_contact_<?php echo $key; ?>" class="btn btn-success pull-right btn-sm m-left-5 save_other_contact admin_access" style="display:none;"><i class="fa fa-floppy-o"></i> Save</div>

															<div class="other-contact-group_<?php echo $key; ?>">

																<div class="col-sm-4 m-bottom-10 clearfix">
																	<?php if(isset($other_value['first_name'])){ echo '<p class="m-top-10"><strong>First Name</strong>: <span class="other_data-first_name_'.$key.'">'.$other_value['first_name'].'</span></p>';} ?>
																	<?php if(isset($other_value['last_name'])){ echo '<p class="m-top-10"><strong>Last Name</strong>: <span class="other_data-last_name_'.$key.'">'.$other_value['last_name'].'</span></p>';} ?>
																	<?php if(isset($other_value['gender'])){ echo '<p class="m-top-10"><strong>Gender</strong>: <span class="other_data-gender_'.$key.'">'.$other_value['gender'].'</span></p>';} ?>
																	<?php if(isset($other_value['type'])){ echo '<p class="m-top-10"><strong>Type</strong>: <span class="other_data-type_'.$key.'">'.$other_value['type'].'</span></p>';} ?>
																</div>

																<div class="col-sm-4 m-bottom-10 clearfix">

																	<?php $other_contact_phone_q = $this->company_m->fetch_phone($other_value['contact_number_id']); ?>
																	<?php $other_contact_phone = array_shift($other_contact_phone_q->result_array()); ?>

																	<?php $other_contact_email_q = $this->company_m->fetch_email($other_value['email_id']); ?>
																	<?php $other_contact_email = array_shift($other_contact_email_q->result_array()); ?>

																	<?php if($other_contact_phone['office_number']!=''){ echo '<p class="m-top-10"><strong>Office Contact</strong>: <span class="other_data-office_number_'.$key.'">'.$other_contact_phone['area_code'].' '.$other_contact_phone['office_number'].'</span></p>';} ?>
																	<?php if($other_contact_phone['after_hours']!=''){ echo '<p class="m-top-10"><strong>After Hours</strong>: <span class="other_data-after_hours_'.$key.'">'.$other_contact_phone['area_code'].' '.$other_contact_phone['after_hours'].'</span></p>';} ?>
																	<?php if(isset($other_contact_phone['mobile_number'])){ echo '<p class="m-top-10"><strong>Mobile</strong>: <span class="other_data-mobile_number_'.$key.'">'.$other_contact_phone['mobile_number'].'</span></p>';} ?>
																	<?php if(isset($other_contact_email['general_email'])){ echo '<p class="m-top-10"><strong>Email</strong>: <span class="other_data-general_email_'.$key.'"><a href="mailto:'.$other_contact_email['general_email'].'?Subject=Inquiry">'.$other_contact_email['general_email'].'</a></span></p>';} ?>					
																</div>
																	<?php $area_code = $other_contact_phone['area_code'] ?>
																	<?php $other_contact_id = $other_value['contact_person_id']; ?>
																	<?php //echo $primary_contact_id; ?>


															<div class="clearfix col-sm-12"><hr /></div>
															</div>

													<div class="other-contact-group-other_data_<?php echo $key; ?> col-sm-12 m-bottom-10 m-top-10 clearfix" style="display:none;">

														<input type="hidden" id="other_email_id_<?php echo $key; ?>" name="other_email_id" value="<?php echo $other_value['email_id']; ?>">
														<input type="hidden" id="other_contact_number_id_<?php echo $key; ?>" name="other_contact_number_id" value="<?php echo $other_value['contact_number_id']; ?>">
														<input type="hidden" id="other_contact_person_id_<?php echo $key; ?>" name="other_contact_person_id" value="<?php echo $other_value['contact_person_id']; ?>">

														<input type="hidden" id="other_contact_person_company_id_<?php echo $key; ?>" name="other_contact_person_company_id" value="<?php echo $other_value['contact_person_company_id']; ?>"> <!-- this is for delete -->

														<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
															<label for="bank-name" class="col-sm-3 control-label">First Name</label>
															<div class="col-sm-9 m-bottom-10">
																<input type="text" class="form-control" id="other_first_name_<?php echo $key; ?>" name="other_first_name" placeholder="First Name" value="<?php echo $other_value['first_name']; ?>">
															</div>

															<label for="account-name" class="col-sm-3 control-label">Last Name</label>
															<div class="col-sm-9 m-bottom-10">
																<input type="text" class="form-control" id="other_last_name_<?php echo $key; ?>" name="other_last_name"  placeholder="Last Name"  value="<?php echo $other_value['last_name']; ?>">
															</div>

															<label for="account-name" class="col-sm-3 control-label">Gender</label>
															<div class="col-sm-9 m-bottom-10">
																<select id="other_contact_gender_<?php echo $key; ?>" class="form-control" name="other_contact_gender">
																	<option value="">Select</option>
																	<option value="Male">Male</option>
																	<option value="Female">Female</option>
																</select>
																<script type="text/javascript">$("select#other_contact_gender_<?php echo $key; ?>").val("<?php echo $other_value['gender']; ?>");</script>
															</div>

															<label for="account-name" class="col-sm-3 control-label">Type</label>
															<div class="col-sm-9 m-bottom-10">
																<select class="form-control" id="other_contact_type_<?php echo $key; ?>" name="other_contact_type">
																	<option value="General">General</option>
																	<option value="Maintenance">Maintenance</option>
																	<option value="Accounts">Accounts</option>
																	<option value="Other">Other</option>
																</select>
																<script type="text/javascript">$("select#other_contact_type_<?php echo $key; ?>").val("<?php echo $other_value['type']; ?>");</script>
															</div>
															<label for="" class="col-sm-3 control-label">Is Primary</label>
															<div class="col-sm-9 m-bottom-10" id="other_contact_is_primary_<?php echo $key; ?>">
																<input type="checkbox" class="set_as_primary" id="set_as_primary_<?php echo $key; ?>" onclick="contact_set_primary('set_as_primary_<?php echo $key; ?>')" style="margin-top: 10px; margin-left: 5px;">
															</div>
														</div>

														<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix>">
															<label for="account-number" class="col-sm-3 control-label">Office Contact</label>
															<div class="col-sm-9 m-bottom-10">
																<div class="input-group">
																	<span class="input-group-addon" id="other_area_code_<?php echo $key; ?>"><?php echo $other_contact_phone['area_code']; ?></span>
																	<input type="text" class="form-control" id="other_office_number_<?php echo $key; ?>" placeholder="Office Contact Number" onchange="contact_number_assign('other_office_number_<?php echo $key; ?>')" name="primary_office_number" value="<?php echo $other_contact_phone['office_number']; ?>">
																</div>
															</div>
														
															<label for="bsb-number" class="col-sm-3 control-label">After Hours</label>
															<div class="col-sm-9 m-bottom-10">
																<div class="input-group">
																	<span class="input-group-addon" id="other_area_code_<?php echo $key; ?>"><?php echo $other_contact_phone['area_code']; ?></span>
																	<input type="text" class="form-control" id="other_after_hours_<?php echo $key; ?>" onchange="contact_number_assign('other_after_hours_<?php echo $key; ?>')" placeholder="After Hours" name="other_after_hours" value="<?php echo $other_contact_phone['after_hours']; ?>">
																</div>
															</div>
														
															<label for="bsb-number" class="col-sm-3 control-label">Mobile</label>
															<div class="col-sm-9 m-bottom-10">
																<input type="text" class="form-control" id="other_mobile_number_<?php echo $key; ?>" onchange="mobile_number_assign('other_mobile_number_<?php echo $key; ?>')" name="other_mobile_number" placeholder="Mobile" value="<?php echo $other_contact_phone['mobile_number']; ?>">
															</div>
														
															<label for="bsb-number" class="col-sm-3 control-label">Email</label>
															<div class="col-sm-9 m-bottom-10">
																<input type="text" class="form-control" id="other_general_email_<?php echo $key; ?>" name="other_general_email" placeholder="Email" value="<?php echo $other_contact_email['general_email']; ?>">
															</div>


															<div id="delete_other_contact_<?php echo $key; ?>" class="btn btn-danger pull-right btn-sm m-right-5 delete_other_contact admin_access" style="display:none;"><i class="fa fa-trash-o"></i> Delete Contact</div>
														</div>
														<div class="clearfix col-sm-12"><hr /></div>
													</div>



													
													<?php endif; ?>
												<?php endforeach; ?>


													<div class="new_contact_area" style="display:none;">


														<div class="col-sm-12 m-bottom-10 m-top-10 clearfix">
															<div class="section-header"><i class="fa fa-user-plus fa-lg"></i> <strong>Add Another Contact</strong></div>
														</div>



														<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
															<label for="bank-name" class="col-sm-3 control-label">First Name*</label>
															<div class="col-sm-9 m-bottom-10">
																<input type="text" class="form-control" id="other_first_name" name="other_first_name" placeholder="First Name" value="">
															</div>

															<label for="account-name" class="col-sm-3 control-label">Last Name*</label>
															<div class="col-sm-9 m-bottom-10">
																<input type="text" class="form-control" id="other_last_name" name="other_last_name"  placeholder="Last Name"  value="">
															</div>

															<label for="account-name" class="col-sm-3 control-label">Gender*</label>
															<div class="col-sm-9 m-bottom-10">
																<select id="other_contact_gender" class="form-control" name="other_contact_gender">																	
																	<option value="Male">Male</option>
																	<option value="Female">Female</option>
																</select>
																<script type="text/javascript">$('select#other_contact_gender').val('Male')</script>
															</div>

															<label for="account-name" class="col-sm-3 control-label">Type*</label>
															<div class="col-sm-9 m-bottom-10">
																<select class="form-control" id="other_contact_type" name="other_contact_type">
																	<option value="General">General</option>
																	<option value="Maintenance">Maintenance</option>
																	<option value="Accounts">Accounts</option>
																	<option value="Other">Other</option>
																</select>
																<script type="text/javascript">$('select#other_contact_type').val('General')</script>
															</div>
															</div>

															<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix>">
															<label for="account-number" class="col-sm-3 control-label">Office Contact</label>
															<div class="col-sm-9 m-bottom-10">
																<div class="input-group">
																	<span class="input-group-addon" id="other_area_code"><?php echo $area_code; ?></span>
																	<input type="text" class="form-control" id="other_office_number" placeholder="Office Contact Number" onchange="contact_number_assign('other_office_number')" name="primary_office_number" value="">
																</div>
															</div>
														
															<label for="bsb-number" class="col-sm-3 control-label">After Hours</label>
															<div class="col-sm-9 m-bottom-10">
																<div class="input-group">
																	<span class="input-group-addon" id="other_area_code"><?php echo $area_code; ?></span>
																	<input type="text" class="form-control" id="other_after_hours" onchange="contact_number_assign('other_after_hours')" placeholder="After Hours" name="other_after_hours" value="">
																</div>
															</div>
														
															<label for="bsb-number" class="col-sm-3 control-label">Mobile</label>
															<div class="col-sm-9 m-bottom-10">
																<input type="text" class="form-control" id="other_mobile_number" onchange="mobile_number_assign('other_mobile_number')" name="other_mobile_number" placeholder="Mobile" value="">
															</div>
														
															<label for="bsb-number" class="col-sm-3 control-label">Email</label>
															<div class="col-sm-9 m-bottom-10">
																<input type="text" class="form-control" id="other_general_email" name="other_general_email" placeholder="Email" value="">
															</div>
														</div>
														<div class="clearfix col-sm-12"><hr /></div>
													</div>


													




													<div id="add_new_contact" class="btn btn-primary pull-right m-left-5 add_new_contact admin_access" ><i class="fa fa-user-plus"></i> Add Contact</div>
													<div id="add_save_contact" class="btn btn-success pull-right m-left-5 add_save_contact admin_access" style="display:none;"><i class="fa fa-floppy-o"></i> Save Contact</div>
													<div id="cancel_contact" class="btn btn-info pull-left cancel_contact admin_access" style="display:none;">Cancel</div>

													<div class="clearfix col-sm-12"><br /></div>

													<!-- Contact Details -->
												</div>
											</div>
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