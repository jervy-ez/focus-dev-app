<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>
<?php
	if($this->session->userdata('company') >= 2 ){

	}else{
		echo '<style type="text/css">.admin_access{ display: none !important;visibility: hidden !important;}</style>';
	}
?>

<script src="<?php echo base_url(); ?>js/vue.js"></script>
<script src="<?php echo base_url(); ?>js/moment.min.js"></script>
<script src="<?php echo base_url(); ?>js/vue-select.js"></script>

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
					
								<?php if(isset($error)): ?>
								<div class="pad-10 no-pad-t">
									<div class="border-less-box alert alert-danger fade in">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										<h4>Oh snap! You got an error!</h4>
										<?php echo $error;?>
									</div>
								</div>
								<?php endif; ?>
								
								<?php if(isset($success)): ?>
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

								<input type="hidden" name="is_admin" id="is_admin" value="<?php echo $this->session->userdata('is_admin'); ?>">
								<input type="hidden" name="user_id" id="user_id" value="<?php echo $this->session->userdata('user_id'); ?>">

								<div class="box-head pad-top-15 pad-left-15 pad-bottom-10 clearfix">
									<div id="edit_company_name" class="btn btn-warning pull-right btn-md m-left-10 admin_access"><i class="fa fa-pencil-square-o"></i> Edit</div>
									<div id="save_company_name" class="btn btn-success pull-right btn-md admin_access" style="display:none;"><i class="fa fa-floppy-o"></i> Save</div>

									<?php if($this->session->userdata('is_admin') == 1): ?>	
										<?php if($has_project != 1): ?>									
									<div id="delete_company" class="btn btn-danger btn-md pull-left m-right-10 admin_access" style="display:none"><i class="fa fa-trash-o"></i> Delete Company</div>
										<?php endif; ?>
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
											<li class="hide">
												<a href="#projects" data-toggle="tab"><i class="fa fa-map-marker fa-lg"></i> Projects</a>
											</li>
											<li class="hide">
												<a href="#invoices" data-toggle="tab"><i class="fa fa-list-alt fa-lg"></i> Invoices</a>
											</li>
											<li class="hide">
												<a href="#reports" data-toggle="tab"><i class="fa fa-bar-chart-o fa-lg"></i> Reports</a>
											</li>
											<li class="active">
												<a href="#profile" data-toggle="tab"><i class="fa fa-briefcase fa-lg"></i> Profile</a>
											</li>
											<li class="">
												<a href="#contact-person" data-toggle="tab"><i class="fa fa-tty fa-lg"></i> Contact Person</a>
											</li>	
											<?php if($company_type_id == 2): ?>
												<li class="">
													<a href="#insurance" data-toggle="tab"><i class="fa fa-paperclip fa-lg"></i> Insurances</a>
												</li>	
												<li class="">
													<a href="#site_staff" data-toggle="tab"><i class="fa fa-users fa-lg"></i> Site Staff</a>
												</li>

												<li class="">
													<a href="#ohs" data-toggle="tab"><i class="fa fa-plus-square fa-lg"></i> OH&S</a>
												</li>
											<?php endif; ?>
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
													<div id="save_more_details" class="btn btn-success pull-right btn-sm m-right-10 admin_access" style="display:none;" onclick = "comp_abn_blur()"><i class="fa fa-floppy-o"></i> Save</div>
													<div class="section-header"><i class="fa fa-list-alt fa-lg"></i> <strong>More Details</strong></div>
												</div>

												<div class="col-sm-4 m-bottom-10 clearfix more_details_group">
													<p class="m-top-10"><strong>ABN</strong>: <span class="data-abn"><?php echo $abn; ?></span></p>
													<p class="m-top-10"><strong>ACN</strong>: <span class="data-acn"><?php echo $acn; ?></span></p>
												</div>

												<div class="col-sm-4 m-bottom-10 clearfix more_details_group">
													<p class="m-top-10"><strong>Type</strong>: <span class="data-company_type"><?php echo $company_type; ?></span></p>
													<p class="m-top-10" style="<?php echo ($company_type == 'Client'  ? 'display:none;' : '');  ?>"><strong>Parent</strong>: <span class="data-parent_company_name"><?php echo $parent_company_name; ?></span></p>
													<div id="" class="sub_client_select" style="<?php echo ($company_type != 'Client'  ? 'display:none;' : '');  ?>">
														<p class="m-top-10"><strong><i class="fa fa-users"></i> Sub-Client Of </strong>: <span class="data-sub_client"><?php echo $sub_client_company_name; ?></span></p>
													</div>
													<p class="m-top-10"><strong>Activity</strong>: <span class="data-company_activity"><?php echo $company_activity; ?></span></p>
												</div>

												<div class="col-sm-12 m-bottom-10 m-top-10 clearfix more_details_group_data" style="display:none;">
													<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
														<label for="bank-name" class="col-sm-3 control-label">ABN</label>
														<div class="col-sm-9 m-bottom-10">
															<input type="text" class="form-control" id="abn" placeholder="ABN" name="abn" tabindex="16" value="<?php echo $abn; ?>"> <!-- onblur = "comp_abn_blur()" -->
														</div>

														<label for="account-name" class="col-sm-3 control-label">ACN</label>
														<div class="col-sm-9 m-bottom-10">
															<input type="text" class="form-control" id="acn" readonly="readonly" placeholder="ACN"    name="acn" value="<?php echo $acn; ?>">
														</div>
													</div>

													<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix>">
														<label for="type" class="col-sm-3 control-label">Type*</label>
														<div class="col-sm-9 m-bottom-10">
															<select class="form-control" id="type" name="type"  tabindex="18">														
																<option value="">Choose a Type...</option>
																<?php
																foreach ($comp_type_list as $row){
																	echo '<option value="'.$row->company_type.'|'.$row->company_type_id.'">'.$row->company_type.'</option>';
																}?>
															</select>
															<script type="text/javascript">$("select#type").val("<?php echo $company_type.'|'.$company_type_id; ?>");</script>
														</div>
														<div id="" class="parent_comp_section" style="<?php echo ($company_type == 'Client'  ? 'display:none;' : '');  ?>">
															<label for="parent" class="col-sm-3 control-label">Parent</label>
															<div class="col-sm-9 m-bottom-10">
																<select class="form-control chosen" id="parent" name="parent" tabindex="20" >										
																<?php $this->company->company_by_type($company_type_id); ?>										
																</select>
																<?php if($parent_company_name != '' && $parent_company_id != ''): ?>																	
																<script type="text/javascript">$("select#parent").val("<?php echo $parent_company_name.'|'.$parent_company_id; ?>");</script>
																<?php endif; ?>																	
															</div>
														</div>

														<div id="" class="sub_client_select" style="<?php echo ($company_type != 'Client'  ? 'display:none;' : '');  ?>">
															<label for="sub_client" class="  col-sm-3 control-label"><i class="fa fa-users"></i> Sub-Client Of </label>
															<div class="col-sm-9 m-bottom-10  ">
																<select class="form-control" id="sub_client" name="sub_client" tabindex="20" >										
																	<option selected value="None|0" >None</option>									
																	<?php echo $this->company->company_list('dropdown'); ?>	
																</select>
																<?php if($sub_client_company_name != '' && $sub_client_id != ''): ?>																	
																	<script type="text/javascript">$("select#sub_client").val("<?php echo $sub_client_company_name.'|'.$sub_client_id; ?>");</script>
																<?php endif; ?>																	
															</div>
														</div>

														<label for="activity" class="col-sm-3 control-label">Activity*</label>
														<div class="col-sm-9">
															<select class="form-control activity chosen" id="activity" name="activity"  tabindex="19" >
																<?php $this->company->activity($company_type); ?>
															</select>
															<script type="text/javascript">$("select#activity").val("<?php echo htmlspecialchars_decode($company_activity).'|'.$company_activity_id; ?>");</script>																
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
													<p class="m-top-10"><strong>About</strong>: <span  class="comments-data" style="white-space: pre-wrap;"><?php echo preg_replace( "/\r|\n/", "",nl2br($comments) ); ?></span></p>
													<textarea class="form-control col-sm-12 comments" name="comments" id="comments" style="display:none;" rows="6"><?php echo $comments; ?></textarea>
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
														</div><div class="clearfix"></div>
														<label for="account-name" class="col-sm-3 control-label">Last Name</label>
														<div class="col-sm-9 m-bottom-10">
															<input type="text" class="form-control" id="primary_last_name" name="primary_last_name"  placeholder="Last Name"  value="<?php echo $value['last_name']; ?>">
														</div><div class="clearfix"></div>
														<label for="account-name" class="col-sm-3 control-label">Gender</label>
														<div class="col-sm-9 m-bottom-10">
															<select id="primary_contact_gender" class="form-control" name="primary_contact_gender">
																<option value="">Select</option>
																<option value="Male">Male</option>
																<option value="Female">Female</option>
															</select>
															<script type="text/javascript">$("select#primary_contact_gender").val("<?php echo $value['gender']; ?>");</script>
														</div><div class="clearfix"></div>
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
														</div><div class="clearfix"></div>
													
														<label for="bsb-number" class="col-sm-3 control-label">After Hours</label>
														<div class="col-sm-9 m-bottom-10">
															<div class="input-group">
																<span class="input-group-addon" id="primary_area_code"><?php echo $contact_phone['area_code']; ?></span>
																<input type="text" class="form-control" id="primary_after_hours" placeholder="After Hours" name="primary_after_hours" value="<?php echo $contact_phone['after_hours']; ?>">
															</div>
														</div><div class="clearfix"></div>
														
														<label for="bsb-number" class="col-sm-3 control-label">Mobile</label>
														<div class="col-sm-9 m-bottom-10">
															<input type="text" class="form-control" id="primary_mobile_number" name="primary_mobile_number" placeholder="Mobile" value="<?php echo $contact_phone['mobile_number']; ?>">
														</div><div class="clearfix"></div>
													
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
														</div><div class="clearfix"></div>

														<label for="account-name" class="col-sm-3 control-label">Last Name</label>
														<div class="col-sm-9 m-bottom-10">
															<input type="text" class="form-control" id="other_last_name_<?php echo $key; ?>" name="other_last_name"  placeholder="Last Name"  value="<?php echo $other_value['last_name']; ?>">
														</div><div class="clearfix"></div>
														<label for="account-name" class="col-sm-3 control-label">Gender</label>
														<div class="col-sm-9 m-bottom-10">
															<select id="other_contact_gender_<?php echo $key; ?>" class="form-control" name="other_contact_gender">
																<option value="">Select</option>
																<option value="Male">Male</option>
																<option value="Female">Female</option>
															</select>
															<script type="text/javascript">$("select#other_contact_gender_<?php echo $key; ?>").val("<?php echo $other_value['gender']; ?>");</script>
														</div><div class="clearfix"></div>

														<label for="account-name" class="col-sm-3 control-label">Type</label>
														<div class="col-sm-9 m-bottom-10">
															<select class="form-control" id="other_contact_type_<?php echo $key; ?>" name="other_contact_type">
																<option value="General">General</option>
																<option value="Maintenance">Maintenance</option>
																<option value="Accounts">Accounts</option>
																<option value="Other">Other</option>
															</select>
															<script type="text/javascript">$("select#other_contact_type_<?php echo $key; ?>").val("<?php echo $other_value['type']; ?>");</script>
														</div><div class="clearfix"></div>
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
														</div><div class="clearfix"></div>
														
														<label for="bsb-number" class="col-sm-3 control-label">After Hours</label>
														<div class="col-sm-9 m-bottom-10">
															<div class="input-group">
																<span class="input-group-addon" id="other_area_code_<?php echo $key; ?>"><?php echo $other_contact_phone['area_code']; ?></span>
																<input type="text" class="form-control" id="other_after_hours_<?php echo $key; ?>" onchange="contact_number_assign('other_after_hours_<?php echo $key; ?>')" placeholder="After Hours" name="other_after_hours" value="<?php echo $other_contact_phone['after_hours']; ?>">
															</div>
														</div><div class="clearfix"></div>
														
														<label for="bsb-number" class="col-sm-3 control-label">Mobile</label>
														<div class="col-sm-9 m-bottom-10">
															<input type="text" class="form-control" id="other_mobile_number_<?php echo $key; ?>" onchange="mobile_number_assign('other_mobile_number_<?php echo $key; ?>')" name="other_mobile_number" placeholder="Mobile" value="<?php echo $other_contact_phone['mobile_number']; ?>">
														</div><div class="clearfix"></div>
														
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
														</div><div class="clearfix"></div>
														<label for="account-name" class="col-sm-3 control-label">Last Name*</label>
														<div class="col-sm-9 m-bottom-10">
															<input type="text" class="form-control" id="other_last_name" name="other_last_name"  placeholder="Last Name"  value="">
														</div><div class="clearfix"></div>

														<label for="account-name" class="col-sm-3 control-label">Gender*</label>
														<div class="col-sm-9 m-bottom-10">
															<select id="other_contact_gender" class="form-control" name="other_contact_gender">																	
																<option value="Male">Male</option>
																<option value="Female">Female</option>
															</select>
															<script type="text/javascript">$('select#other_contact_gender').val('Male')</script>
														</div><div class="clearfix"></div>

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
														<label for="account-number" class="col-sm-3 control-label">Office Contact*</label>
														<div class="col-sm-9 m-bottom-10">
															<div class="input-group">
																<span class="input-group-addon" id="other_area_code"><?php echo $area_code; ?></span>
																<input type="text" class="form-control" id="other_office_number" placeholder="Office Contact Number" onchange="contact_number_assign('other_office_number')" name="primary_office_number" value="">
															</div>
														</div><div class="clearfix"></div>
														<label for="bsb-number" class="col-sm-3 control-label">After Hours</label>
														<div class="col-sm-9 m-bottom-10">
															<div class="input-group">
																<span class="input-group-addon" id="other_area_code"><?php echo $area_code; ?></span>
																<input type="text" class="form-control" id="other_after_hours" onchange="contact_number_assign('other_after_hours')" placeholder="After Hours" name="other_after_hours" value="">
															</div>
														</div><div class="clearfix"></div>
														
														<label for="bsb-number" class="col-sm-3 control-label">Mobile*</label>
														<div class="col-sm-9 m-bottom-10">
															<input type="text" class="form-control" id="other_mobile_number" onchange="mobile_number_assign('other_mobile_number')" name="other_mobile_number" placeholder="Mobile" value="">
														</div><div class="clearfix"></div>
														
														<label for="bsb-number" class="col-sm-3 control-label">Email*</label>
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
											<!-- Insurance -->
											<div class="tab-pane fade in  clearfix" id="insurance">
												<div class="col-sm-12 section-header"><label for="" ><b>Public Liability:</b></label></div>
												<div class="col-sm-12 m-bottom-10 clearfix" style="padding: 10px">
													<div class="col-sm-2">
														<?php if($public_liability == 1): ?>
															<a href="#" onclick = "clk_pl_insurance(<?php echo $company_id ?>)" class = "pl_insurance_<?php echo $company_id ?>">View File</a>
														<?php endif; ?>
															<button type = "button" class = "btn btn-primary" id = "attach_pl" data-toggle="modal" data-target="#attach_insurance_modal">Attach File</button>
													</div>
													<div class = "col-sm-2 text-right">Start Date:</div>
													<div class = "col-sm-2"><input type="text" class = "form-control pl_sdate" value = "<?php echo $pl_start_date ?>" disabled></div>
													<div class = "col-sm-2 text-right">Expiration Date:</div>
													<div class = "col-sm-2">
														<input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control datepicker pl_expdate" id="pl_expiration" name="pl_expiration" value="<?php echo $pl_expiration ?>">												
													</div>
													<div class = "col-sm-2"><button type = "button" class = "btn btn-success" id = "update_insurance_pl">Update</button></div>
												</div>

												<div class="col-sm-12 section-header"><label for=""><b>Workers Compensation:</b></label></div>
												<div class="col-sm-12 m-bottom-10 clearfix" style="padding: 10px">
													<div class="col-sm-12"></div>
													<div class="col-sm-2">
														<?php if($workers_compensation == 1): ?>
														<a href="#" onclick = "clk_wc_insurance(<?php echo $company_id ?>)" class = "wc_insurance_<?php echo $company_id ?>">View File</a>
														<?php endif; ?>
														<button type = "button" class = "btn btn-primary" id = "attach_wc" data-toggle="modal" data-target="#attach_insurance_modal">Attach File</button>
													</div>
													<div class = "col-sm-2 text-right">Start Date:</div>
													<div class = "col-sm-2"><input type="text" class = "form-control wc_sdate" value = "<?php echo $wc_start_date ?>" disabled></div>
													<div class = "col-sm-2 text-right">Expiration Date:</div>
													<div class = "col-sm-2">
														<input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control datepicker wc_expdate" id="wc_expiration" name="wc_expiration" value="<?php echo $wc_expiration ?>">
													</div>
													<div class = "col-sm-2"><button type = "button" class = "btn btn-success" id = "update_insurance_wc">Update</button></div>
												</div>

												<div class="col-sm-12 section-header"><label for=""><b>Income Protection:</b></label></div>
												<div class="col-sm-12 m-bottom-10 clearfix" style="padding: 10px">
													<div class="col-sm-12"></div>
													<div class="col-sm-2">
														<?php if($income_protection == 1): ?>
														<a href="#" onclick = "clk_ip_insurance(<?php echo $company_id ?>)" class = "ip_insurance_<?php echo $company_id ?>">View File</a>
														<?php endif; ?>
														<button type = "button" class = "btn btn-primary" id = "attach_ip" data-toggle="modal" data-target="#attach_insurance_modal">Attach File</button>
													</div>
													<div class = "col-sm-2 text-right">Start Date:</div>
													<div class = "col-sm-2"><input type="text" class = "form-control ip_sdate" value = "<?php echo $ip_start_date ?>" disabled></div>
													<div class = "col-sm-2 text-right">Expiration Date:</div>
													<div class = "col-sm-2">
														<input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control datepicker ip_expdate" id="ip_expiration" name="ip_expiration" value="<?php echo $ip_expiration ?>">
													</div>
													<div class = "col-sm-2"><button type = "button" class = "btn btn-success" id = "update_insurance_ip">Update</button></div>
												</div>
											</div>
											<!-- Insurance -->
											<!-- Site Staff -->
											<div class="tab-pane fade in  clearfix" id="site_staff">
												<div id="app">
													<div class="box-head pad-5 row">
														<div class="col-sm-10"><label> Contractors Site Staff</label></div>
														<div class="col-sm-1 pad_5"><button type = "button" class = "btn btn-success btn-block btn-sm pull-right"  v-on:click="showAddSiteStaff"> Add</button></div>
														<div class="col-sm-1 pad_5"><button type = "button" class = "btn btn-primary btn-block btn-sm pull-right"  v-on:click="sendLink"> Send Link</button></div>
													</div>
													<div class="col-sm-12 pad-5" style = "height: 500px; overflow:auto">
										                <table id="myTable" class = "table table-condensed table-striped table-bordered m-bottom-0 fancyTable"  style = "font-size: 12px">
										                 	<thead>
											                    <tr>
												                    <th>Site Staff Name</th>
												                    <th>Position</th>
												                    <th>Mobile Number</th>
												                    <th>E-mail</th>
												                    <th>General Induction Date</th>
												                    <th>Emergency Contact Details</th>
												                    <th>License and Certificates</th>
												                    <th>Training Records</th>
											                    </tr>  
										                    </thead>
										                    <tbody>
										                      	<tr v-for="contractor_site_staff in contractor_site_staff">
										                      		<td><a href="" onclick="return false" v-on:click = "showUpdateContractorSiteStaff(contractor_site_staff.contractor_site_staff_id)">{{ contractor_site_staff.site_staff_fname +" "+ contractor_site_staff.site_staff_sname}}</a></td>
										                       		<td>{{ contractor_site_staff.position }}</td>
										                      		<td>{{ contractor_site_staff.mobile_number }}</td>
										                       		<td>{{ contractor_site_staff.general_email }}</td>
										                      		<td v-if = "contractor_site_staff.general_induction_date == '0000-00-00'">Not Taken</td>
										                       		<td v-if = "contractor_site_staff.general_induction_date !== '0000-00-00'">{{ contractor_site_staff.general_induction_date }}</td>
										                       		<td>
										                          		<span class="badge alert-success pointer pull-right" title = "Add Emergency Contact Details" v-on:click="showAddCSSEmergenceContacts(contractor_site_staff.contractor_site_staff_id)"><i class="fa fa-plus-circle"></i></span>
										                           		<ul  v-for="cont_sitestaff_emergency_contatacts in cont_sitestaff_emergency_contatacts" v-if="cont_sitestaff_emergency_contatacts.user_id == contractor_site_staff.contractor_site_staff_id ">
										                           			<li><a href="" onclick="return false" v-on:click = "showUpdateCSSEmergencyContacts(cont_sitestaff_emergency_contatacts.sitestaff_emergency_contacts_id)" style = "font-size: 12px">{{ cont_sitestaff_emergency_contatacts.contact_fname+" "+cont_sitestaff_emergency_contatacts.contact_sname+" ("+cont_sitestaff_emergency_contatacts.relation +") - "+ cont_sitestaff_emergency_contatacts.contacts }}</a></li>
										                           		</ul>
										                       		</td>
										                       		<td>
										                          		<table class = "table table-condensed" style = "width: 100%; padding: 0px; margin: 0px">
										                           			<tr>
										                              			<td style = "border: none">
										                               				<table class = "table table-condensed" style = "width: 100%; padding: 0px; margin: 0px"> 
										                                  				<tr v-for="cont_sitestaff_license_certificates in cont_sitestaff_license_certificates" v-if="cont_sitestaff_license_certificates.user_id == contractor_site_staff.contractor_site_staff_id">
										                                   					<td style = "border: none"><a href="" onclick="return false" v-on:click = "showUpdateCSSLicenceCert(cont_sitestaff_license_certificates.user_license_certificates_id)" style = "font-size: 12px" v-bind:class="{ expired: isExpired(cont_sitestaff_license_certificates.expiration_date) }">{{ cont_sitestaff_license_certificates.type+" ("+cont_sitestaff_license_certificates.number +") "}}</a></td>
										                                   					<td v-bind:class="{ expired: isExpired(cont_sitestaff_license_certificates.expiration_date) }" class = "text-right" style = "border: none"> Expiration: {{ cont_sitestaff_license_certificates.expiration_date | ausdate }}</td>
										                                   				</tr>
									                                  				</table>
									                                			</td>
									                                			<td style = "border: none"><span class="badge alert-warning pointer pull-right" title = "Add Licenses and Certificates" v-on:click="showAddCSSLicencesCert(contractor_site_staff.contractor_site_staff_id)"><i class="fa fa-plus-circle"></i></span></td>
									                              			</tr>
									                            		</table>
										                          	</td>
									                          		<td>
									                            		<span class="badge alert-info pointer pull-right" title = "Add Training Records" v-on:click="showAddCSSTraining(contractor_site_staff.contractor_site_staff_id)"><i class="fa fa-plus-circle"></i></span>
									                            		<ul v-for="cont_sitestaff_training_records in cont_sitestaff_training_records" v-if="cont_sitestaff_training_records.user_id == contractor_site_staff.contractor_site_staff_id " style = "font-size: 12px" >
									                                		<li><a href="" onclick="return false" v-on:click = "showUpdateCSSTraining(cont_sitestaff_training_records.training_records_id)" style = "font-size: 12px">{{ cont_sitestaff_training_records.training_type+" ( " }} {{ cont_sitestaff_training_records.date_undertaken | ausdate }}{{" ) -"+ cont_sitestaff_training_records.taken_with }}</a></li>
									                            		</ul>
									                          		</td>
									                        	</tr>
									                      	</tbody>
									                    </table>
									                </div>
									                <div class="modal fade" id="add_cont_site_staff" role="dialog">
												    	<div class="modal-dialog">
												      		<div class="modal-content">
												        		<div class="modal-header">
												          			<button type="button" class="close" data-dismiss="modal">&times;</button>
												          			<h4 class="modal-title">Contractor Site Staff</h4>
												        		</div>
												        
												        		<div class="modal-body row">	
														          	<div class="col-sm-4 pad-5">First Name:</div>
														          	<div class="col-sm-8 pad-5"><input type="text" class = "form-control" name = "siteStaffFName" v-model = "siteStaffFName"></div>
														          	<div class="clearfix"></div>
														          	<div class="col-sm-4 pad-5">Last Name: </div>
														          	<div class="col-sm-8 pad-5"><input type = "text" class = "form-control" name = "siteStaffLName"  v-model = "siteStaffLName"></div>
														          	<div class="col-sm-4 pad-5">Position</div>
														          	<div class="col-sm-8 pad-5"><input type = "text" class = "form-control" v-model = "siteStaffPosition"></div>
														          	<div class="col-sm-4 pad-5">Mobile Number: </div>
														          	<div class="col-sm-8 pad-5"><input type = "text" class = "form-control" name = "siteStaffMobile"  v-model = "siteStaffMobile" v-on:keyup="formatMobileNumber"></div>
														          	<div class="col-sm-4 pad-5">E-mail: </div>
														          	<div class="col-sm-8 pad-5"><input type = "text" class = "form-control" name = "siteStaffEmail"  v-model = "siteStaffEmail"></div>
														          	<div class="col-sm-4 pad-5">Is Apprentice: </div>
														          	<div class="col-sm-8 pad-5">
														          		<SELECT class = "form-control" name = "is_apprentice"  v-model = "is_apprentice">
																			<option value="0">No</option>
																			<option value="1">Yes</option>
																		</SELECT>
														          	</div>
												        		</div>
														        <div class="modal-footer">
														          	<button type="button" class="btn btn-default btn-success" v-on:click= "addContractorSiteStaff" data-dismiss="modal">Add</button>
														          	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
														        </div>
												      		</div>
												        
												    	</div>
													</div>

													<div class="modal fade" id="update_cont_site_staff" role="dialog">
													    <div class="modal-dialog">
													     <!-- Modal content-->
													     	<div class="modal-content">
														        <div class="modal-header">
															        <button type="button" class="close" data-dismiss="modal">&times;</button>
															        <h4 class="modal-title">Contractor Site Staff</h4>
														        </div>
														        <div class="modal-body row">       
															        <div class="clearfix"></div>
															        <div class="col-sm-4 pad-5">First Name:</div>
															        <div class="col-sm-8 pad-5"><input type="text" class = "form-control" name = "siteStaffFName" v-model = "siteStaffFName"></div>
															        <div class="clearfix"></div>
															        <div class="col-sm-4 pad-5">Last Name: </div>
															        <div class="col-sm-8 pad-5"><input type = "text" class = "form-control" name = "siteStaffLName"  v-model = "siteStaffLName"></div>
															        <div class="col-sm-4 pad-5">Position</div>
															        <div class="col-sm-8 pad-5"><input type = "text" class = "form-control" v-model = "siteStaffPosition"></div>
															        <div class="col-sm-4 pad-5">Mobile Number: </div>
															        <div class="col-sm-8 pad-5"><input type = "text" class = "form-control" name = "siteStaffMobile"  v-model = "siteStaffMobile" v-on:keyup="formatMobileNumber"></div>
															        <div class="col-sm-4 pad-5">E-mail: </div>
														          	<div class="col-sm-8 pad-5"><input type = "text" class = "form-control" name = "siteStaffEmail"  v-model = "siteStaffEmail"></div>
															        <div class="col-sm-4 pad-5">Is Apprentice: </div>
														          	<div class="col-sm-8 pad-5">
														          		<SELECT class = "form-control" name = "is_apprentice"  v-model = "is_apprentice">
																			<option value="0">No</option>
																			<option value="1">Yes</option>
																		</SELECT>
														          	</div>
															        <div class="col-sm-4 pad-5">General Induction Date: </div>
															        <div class="col-sm-8 pad-5"><input type = "date" class = "form-control" name = "gi_date" id = "gi_date"></div>
														        	
														        </div>
														        <div class="modal-footer">
															        <button type="button" class="btn btn-default btn-danger pull-left" v-on:click= "removeContractorSiteStaff" data-dismiss="modal">Remove</button>
															        <button type="button" class="btn btn-default btn-success" v-on:click= "updateContractorSiteStaff" data-dismiss="modal">Update</button>
															        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
														        </div>
													      	</div>
														</div>
													</div>

													<div class="modal fade" id="add_emergency_contact" role="dialog">
													    <div class="modal-dialog">
													      	<div class="modal-content">
														        <div class="modal-header">
														          	<button type="button" class="close" data-dismiss="modal">&times;</button>
														          	<h4 class="modal-title">Emergency Contacts</h4>
														        </div>
														        <div class="modal-body row">
														          	<div class="col-sm-3 pad-5">First Name</div>
														          	<div class="col-sm-9 pad-5"><input type = "text" class = "form-control input-sm" name = "ecFName" name = "ecFName" v-model = "ecFName"></div>
														          	<div class="col-sm-3 pad-5">Last Name</div>
														          	<div class="col-sm-9 pad-5"><input type = "text" class = "form-control input-sm" name = "ecSName" v-model = "ecSName"></div>
														          	<div class="col-sm-3 pad-5">Relation</div>
														          	<div class="col-sm-9 pad-5"><input type = "text" class = "form-control input-sm" name = "ecRelation" v-model = "ecRelation"></div>
														          	<div class="col-sm-3 pad-5">Contact Numbers</div>
														          	<div class="col-sm-9 pad-5"><input type = "text" class = "form-control input-sm" name = "ecContacts" v-model = "ecContacts" v-on:keyup="formatPhoneNumber"></div>
														        </div>
														        <div class="modal-footer">
														          <button type="button" class="btn btn-default btn-success" v-on:click="addEmergencyContacts" data-dismiss="modal">Add</button>
														          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
														        </div>
													      	</div> 
													    </div>
													</div>
														
													<div class="modal fade" id="update_emergency_contact" role="dialog">
													    <div class="modal-dialog">
													      	<div class="modal-content">
														        <div class="modal-header">
														          	<button type="button" class="close" data-dismiss="modal">&times;</button>
														          	<h4 class="modal-title">Emergency Contacts</h4>
														        </div>
														        
														        <div class="modal-body row">
														          	<div class="col-sm-3 pad-5">First Name</div>
														          	<div class="col-sm-9 pad-5"><input type = "text" class = "form-control input-sm" name = "ecFName" v-model = "ecFName"></div>
														          	<div class="col-sm-3 pad-5">Last Name</div>
														          	<div class="col-sm-9 pad-5"><input type = "text" class = "form-control input-sm" name = "ecSName" v-model = "ecSName"></div>
														          	<div class="col-sm-3 pad-5">Relation</div>
														          	<div class="col-sm-9 pad-5"><input type = "text" class = "form-control input-sm" name = "ecRelation" v-model = "ecRelation"></div>
														          	<div class="col-sm-3 pad-5">Contact Numbers</div>
														          	<div class="col-sm-9 pad-5"><input type = "text" class = "form-control input-sm" name = "ecContacts" v-model = "ecContacts" v-on:keyup="formatPhoneNumber"></div>
														        </div>
														        <div class="modal-footer">
														          	<input type="button" class="btn btn-default btn-success" v-on:click = "updateEmergencyContacts" name = "update" value = "Update" data-dismiss="modal">
														          	<input type="button" class="btn btn-default btn-danger pull-left" v-on:click = "deleteEmergencyContacts" name = "remove" value = "Remove" data-dismiss="modal">
														          	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
														        </div>
													      	</div>
													        
													    </div>
													</div>

													<div class="modal fade" id="add_license_cert" role="dialog">
													    <div class="modal-dialog">
													      <!-- Modal content-->
													      	<div class="modal-content">
														        <div class="modal-header">
														          	<button type="button" class="close" data-dismiss="modal">&times;</button>
														          	<h4 class="modal-title">Licences / Certificates</h4>
														        </div>
													        
														        <div class="modal-body row">
														                    
														          	<div class="col-sm-4 pad-5">Select*: </div>
														          	<div class="col-sm-8 pad-5">
															            <SELECT class = "form-control input-sm" name = "LCtype" name = "LCtype" v-model = "LCtype">
															              	<option value="1">Licence</option>
															              	<option value="0">Certificates </option>
															            </SELECT>
														          	</div>
														          	<div class="clearfix"></div>
														          	<div class="col-sm-4 pad-5">Type*:</div>
														          	<div class="col-sm-8 pad-5">
															            <SELECT class = "form-control input-sm" name = "LCName" name = "LCName" v-model = "LCName" v-on:change = "typeChange">
															              	<option v-for = "licenseCertTypes in licenseCertTypes" :value="licenseCertTypes.lc_type_name">{{ licenseCertTypes.lc_type_name }}</option>
															              	<option value = "0">Other</option>
															            </SELECT>
														          	</div>
														          
														          	<div class="clearfix" v-if = "showLCTypeName"></div>
														          	<div class="col-sm-4 pad-5" v-if = "showLCTypeName">Enter Type Name*:</div>
														          	<div class="col-sm-8 pad-5" v-if = "showLCTypeName">
														            	<input type = "text" class = "form-control input-sm" v-model = "LCTypeName">
														          	</div>
															          	<div class="clearfix"></div>
														          	<div class="col-sm-4 pad-5">Licences/Certificate Number*: </div>
														          	<div class="col-sm-8 pad-5"><input type = "text" class = "form-control input-sm" name = "lcNumber" v-model = "lcNumber"></div>
														          	<div class="clearfix"></div>
														          	<div class="col-sm-4 pad-5">Expiration Date (if applicable)</div>
														          	<div class="col-sm-8 pad-5"><input type = "date" class = "form-control input-sm" name = "expirationDate" v-model = "expirationDate"></div>
														        </div>
														        <div class="modal-footer">
														          	<button type="button" class="btn btn-default btn-success" v-on:click= "addLC" data-dismiss="modal">Add</button>
														          	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
														        </div>
													      	</div>
													        
													    </div>
													</div>

													<div class="modal fade" id="update_license_cert" role="dialog">
													    <div class="modal-dialog">
													      <!-- Modal content-->
													      	<div class="modal-content">
														        <div class="modal-header">
														          	<button type="button" class="close" data-dismiss="modal">&times;</button>
														          	<h4 class="modal-title">Licences / Certificates</h4>
														        </div>
													        
													        	<div class="modal-body row">
													                    
														          	<div class="col-sm-4 pad-5">Select*: </div>
														          	<div class="col-sm-8 pad-5">
															            <SELECT class = "form-control input-sm" name = "LCtype" name = "LCtype" v-model = "LCtype">
															              	<option value="1">Licence</option>
															             	<option value="0">Certificates </option>
															            </SELECT>
														          	</div>
														          	<div class="clearfix"></div>
														          	<div class="col-sm-4 pad-5">Type*:</div>
														          	<div class="col-sm-8 pad-5">
															            <SELECT class = "form-control input-sm" name = "LCName" name = "LCName" v-model = "LCName">
															             	<option v-for = "licenseCertTypes in licenseCertTypes" :value="licenseCertTypes.lc_type_name">{{ licenseCertTypes.lc_type_name }}</option>
															              	<option value = "0">Other</option>
															            </SELECT>
														          	</div>
														          	<div class="clearfix" v-if = "showLCTypeName"></div>
														          	<div class="col-sm-4 pad-5" v-if = "showLCTypeName">Type*:</div>
														          	<div class="col-sm-8 pad-5" v-if = "showLCTypeName">
														            	<input type = "text" class = "form-control input-sm" v-model = "LCTypeName">
														          	</div>
														          
														          	<div class="clearfix"></div>
														          	<div class="col-sm-4 pad-5">Licences/Certificate Number*: </div>
														          	<div class="col-sm-8 pad-5"><input type = "text" class = "form-control input-sm" name = "lcNumber"  v-model = "lcNumber"></div>
														          	<div class="clearfix"></div>
														          	<div class="col-sm-4 pad-5">Expiration Date (if applicable)</div>
														          	<div class="col-sm-8 pad-5"><input type = "date" class = "form-control input-sm" name = "expirationDate" v-model = "expirationDate"></div>
														        </div>
														        <div class="modal-footer">
														          	<button type="button" class="btn btn-default btn-success" v-on:click= "updateLC" data-dismiss="modal">Update</button>
														          	<button type="button" class="btn btn-default btn-danger pull-left" v-on:click= "removeLC" data-dismiss="modal">Remove</button>
															          	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
														        </div>
													    	</div>    
														</div>
													</div>

													<div class="modal fade" id="add_training_cert" role="dialog">
														<div class="modal-dialog">
														      
														    <div class="modal-content">
															    <div class="modal-header">
															        <button type="button" class="close" data-dismiss="modal">&times;</button>
															        <h4 class="modal-title">Training Records</h4>
															    </div>
															        
															    <div class="modal-body row">          
															        <div class="col-sm-4 pad-5">Training: </div>
															        <div class="col-sm-8 pad-5"><input type = "text" class = "form-control input-sm" name = "trainingName" v-model = "trainingName"></div>
															        <div class="clearfix"></div>
															        <div class="col-sm-4 pad-5">Training Date:</div>
															        <div class="col-sm-8 pad-5"><input type="date" class = "form-control input-sm" name = "trainingDate" v-model = "trainingDate"></div>
															        <div class="clearfix"></div>
															        <div class="col-sm-4 pad-5">Training location: </div>
															        <div class="col-sm-8 pad-5"><input type = "text" class = "form-control input-sm" name = "trainingLoc"  v-model = "trainingLoc"></div>
															    </div>
															    <div class="modal-footer">
															        <button type="button" class="btn btn-default btn-success" v-on:click= "addTraining" data-dismiss="modal">Add</button>
															        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
															    </div>
														    </div>
														        
														</div>
													</div>

													<div class="modal fade" id="update_training_cert" role="dialog">
													    <div class="modal-dialog">
													      	<div class="modal-content">
														        <div class="modal-header">
														          	<button type="button" class="close" data-dismiss="modal">&times;</button>
														          	<h4 class="modal-title">Training Records</h4>
														        </div>
														        
														        <div class="modal-body row">
														                    
														          	<div class="col-sm-4 pad-5">Training: </div>
														          	<div class="col-sm-8 pad-5"><input type = "text" class = "form-control input-sm" name = "trainingName" v-model = "trainingName"></div>
														          	<div class="clearfix"></div>
														          	<div class="col-sm-4 pad-5">Training Date:</div>
														          	<div class="col-sm-8 pad-5"><input type="date" class = "form-control input-sm" name = "trainingDate" v-model = "trainingDate"></div>
														          	<div class="clearfix"></div>
														          	<div class="col-sm-4 pad-5">Training location: </div>
														          	<div class="col-sm-8 pad-5"><input type = "text" class = "form-control input-sm" name = "trainingLoc"  v-model = "trainingLoc"></div>
														        </div>
														        <div class="modal-footer">
														          	<button type="button" class="btn btn-default btn-success" v-on:click= "updateTraining" data-dismiss="modal">Update</button>
														          	<button type="button" class="btn btn-default btn-danger pull-left" v-on:click= "removeTraining" data-dismiss="modal">Remove</button>
														          	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
														        </div>
													      	</div>
													        
													    </div>
													</div>
												</div>
											</div>
											<!-- Site Staff -->

											<!-- OH&S start -->
											<div class="tab-pane fade in  clearfix" id="ohs">

												<?php if($company_ohs_count != 0): ?>

													<div class="col-sm-12 m-bottom-10 clearfix">
														
														<div id="workplace_health_safety_edit_btn" class="btn btn-warning pull-right btn-sm m-right-10"><i class="fa fa-pencil-square-o"></i> Edit</div>
														<div id="workplace_health_safety_save_btn" class="btn btn-success pull-right btn-sm m-right-10" style="display:none;"><i class="fa fa-floppy-o"></i> Save</div>
														
														<div class="section-header"><strong>1. Do you and / or your company have a Workplace Health & Safety system in place?</strong></div>

														<div class="row m-bottom-10 m-top-10 clearfix workplace_health_safety_group"> 

															<div class="col-sm-4 m-bottom-10 clearfix">
																<p class="m-top-10"><span class="workplace_health_safety-yes-icon"><?php echo ($workplace_health_safety == 1) ? '<i class="fa fa-check fa-lg" style="color: #5cb85c;"></i>' : '<i class="fa fa-times fa-lg" style="color: #d9534f;"></i>'; ?></span> <span>Yes</span></p>
															</div>

															<div class="col-sm-4 m-bottom-10 clearfix">
																<p class="m-top-10"><span class="workplace_health_safety-no-icon"><?php echo ($workplace_health_safety == 0) ? '<i class="fa fa-check fa-lg" style="color: #5cb85c;"></i>' : '<i class="fa fa-times fa-lg" style="color: #d9534f;"></i>'; ?></span> <span>No</span></p>
															</div>

															<div class="col-sm-12 m-bottom-10 clearfix">
																<p class="m-top-10" style="color: #d9534f"><strong>Notes: <span class="workplace_health_safety_notes-data" style="white-space: pre-wrap;"><?php echo $workplace_health_safety_notes; ?></span></strong></p>
															</div>

														</div>

														<div class="col-sm-12 m-bottom-10 m-top-10 clearfix workplace_health_safety_edit" style="display:none;"> 
															
															<div class="row">
																<div class="col-md-12">
																	<div class="radio-inline">
																		<label>
																			<input type="radio" name="workplace_health_safety" id="workplace_health_safety1" value="1" <?php echo ($workplace_health_safety == 1) ? 'checked' : ''; ?>> Yes
																		</label>
																	</div>
																
																	<div class="radio-inline">
																		<label>
																			<input type="radio" name="workplace_health_safety" id="workplace_health_safety2" value="0" <?php echo ($workplace_health_safety == 0) ? 'checked' : ''; ?>> No
																		</label>
																	</div>
																</div>
															</div>

															<div class="box">
																<div class="box-head pad-5">
																	<label for="workplace_health_safety_notes"><i class="fa fa-pencil-square fa-lg"></i> Notes:</label>
																</div>
																	
																<div class="box-area pad-5 clearfix">
																	<div class="clearfix">												
																		<div class="">
																			<textarea class="form-control" id="workplace_health_safety_notes" rows="2" name="workplace_health_safety_notes"><?php echo $workplace_health_safety_notes; ?></textarea>
																		</div>
																	</div>
																</div>
															</div>
														</div>

													</div>

													<div class="col-sm-12 m-bottom-10 clearfix">

														<div id="swms_edit_btn" class="btn btn-warning pull-right btn-sm m-right-10"><i class="fa fa-pencil-square-o"></i> Edit</div>
														<div id="swms_save_btn" class="btn btn-success pull-right btn-sm m-right-10" style="display:none;"><i class="fa fa-floppy-o"></i> Save</div>

														<div class="section-header"><strong>2. Are you familiar with Safe Work Method Statements (SWMS)?</strong></div>

														<div class="row m-bottom-10 m-top-10 clearfix swms_group"> 

															<div class="col-sm-4 m-bottom-10 clearfix">
																<p class="m-top-10"><span class="swms-yes-icon"><?php echo ($swms == 1) ? '<i class="fa fa-check fa-lg" style="color: #5cb85c;"></i>' : '<i class="fa fa-times fa-lg" style="color: #d9534f;"></i>'; ?></span> <span>Yes</span></p>
															</div>

															<div class="col-sm-4 m-bottom-10 clearfix">
																<p class="m-top-10"><span class="swms-no-icon"><?php echo ($swms == 0) ? '<i class="fa fa-check fa-lg" style="color: #5cb85c;"></i>' : '<i class="fa fa-times fa-lg" style="color: #d9534f;"></i>'; ?></span> <span>No</span></p>
															</div>

															<div class="col-sm-12 m-bottom-10 clearfix">
																<p class="m-top-10" style="color: #d9534f"><strong>Notes: <span class="swms_notes-data" style="white-space: pre-wrap;"><?php echo $swms_notes; ?></span></strong></p>
															</div>

														</div>

														<div class="col-sm-12 m-bottom-10 m-top-10 clearfix swms_edit" style="display:none;">
															
															<div class="row">
																<div class="col-md-12">
																	<div class="radio-inline">
																		<label>
																			<input type="radio" name="swms" id="swms1" value="1" <?php echo ($swms == 1) ? 'checked' : ''; ?>> Yes
																		</label>
																	</div>
																
																	<div class="radio-inline">
																		<label>
																			<input type="radio" name="swms" id="swms2" value="0" <?php echo ($swms == 0) ? 'checked' : ''; ?>> No
																		</label>
																	</div>
																</div>
															</div>

															<div class="box">
																<div class="box-head pad-5">
																	<label for="swms_notes"><i class="fa fa-pencil-square fa-lg"></i> Notes:</label>
																</div>
																	
																<div class="box-area pad-5 clearfix">
																	<div class="clearfix">												
																		<div class="">
																			<textarea class="form-control" id="swms_notes" rows="2" name="swms_notes"><?php echo $swms_notes; ?></textarea>
																		</div>
																	</div>
																</div>
															</div>
														</div>

													</div>

													<div class="col-sm-12 m-bottom-10 clearfix">

														<div id="jsa_edit_btn" class="btn btn-warning pull-right btn-sm m-right-10"><i class="fa fa-pencil-square-o"></i> Edit</div>
														<div id="jsa_save_btn" class="btn btn-success pull-right btn-sm m-right-10" style="display:none;"><i class="fa fa-floppy-o"></i> Save</div>

														<div class="section-header"><strong>3. Are you familiar with Job Safety Analysis (JSA)?</strong></div>

														<div class="row m-bottom-10 m-top-10 clearfix jsa_group"> 

															<div class="col-sm-4 m-bottom-10 clearfix">
																<p class="m-top-10"><span class="jsa-yes-icon"><?php echo ($jsa == 1) ? '<i class="fa fa-check fa-lg" style="color: #5cb85c;"></i>' : '<i class="fa fa-times fa-lg" style="color: #d9534f;"></i>'; ?></span> <span>Yes</span></p>
															</div>

															<div class="col-sm-4 m-bottom-10 clearfix">
																<p class="m-top-10"><span class="jsa-no-icon"><?php echo ($jsa == 0) ? '<i class="fa fa-check fa-lg" style="color: #5cb85c;"></i>' : '<i class="fa fa-times fa-lg" style="color: #d9534f;"></i>'; ?></span> <span>No</span></p>
															</div>

															<div class="col-sm-12 m-bottom-10 clearfix">
																<p class="m-top-10" style="color: #d9534f"><strong>Notes: <span class="jsa_notes-data" style="white-space: pre-wrap;"><?php echo $jsa_notes; ?></span></strong></p>
															</div>

														</div>

														<div class="col-sm-12 m-bottom-10 m-top-10 clearfix jsa_edit" style="display:none;">
															
															<div class="row">
																<div class="col-md-12">
																	<div class="radio-inline">
																		<label>
																			<input type="radio" name="jsa" id="jsa1" value="1" <?php echo ($jsa == 1) ? 'checked' : '' ; ?>> Yes
																		</label>
																	</div>
																
																	<div class="radio-inline">
																		<label>
																			<input type="radio" name="jsa" id="jsa2" value="0" <?php echo ($jsa == 0) ? 'checked' : '' ; ?>> No
																		</label>
																	</div>
																</div>
															</div>

															<div class="box">
																<div class="box-head pad-5">
																	<label for="jsa_notes"><i class="fa fa-pencil-square fa-lg"></i> Notes:</label>
																</div>
																	
																<div class="box-area pad-5 clearfix">
																	<div class="clearfix">												
																		<div class="">
																			<textarea class="form-control" id="jsa_notes" rows="2" name="jsa_notes"><?php echo $jsa_notes; ?></textarea>
																		</div>
																	</div>
																</div>
															</div>
														</div>

													</div>

													<div class="col-sm-12 m-bottom-10 clearfix">

														<div id="reviewed_swms_edit_btn" class="btn btn-warning pull-right btn-sm m-right-10"><i class="fa fa-pencil-square-o"></i> Edit</div>
														<div id="reviewed_swms_save_btn" class="btn btn-success pull-right btn-sm m-right-10" style="display:none;"><i class="fa fa-floppy-o"></i> Save</div>
														
														<div class="section-header"><strong>4. Will you ensure that any person attending site has reviewed the SWMS for the tasks to be undertaken and if required amend them to suit any specific requirements or considerations and sign these off prior to undertaking any works?</strong></div>

														<div class="row m-bottom-10 m-top-10 clearfix reviewed_swms_group"> 

															<div class="col-sm-4 m-bottom-10 clearfix">
																<p class="m-top-10"><span class="reviewed_swms-yes-icon"><?php echo ($reviewed_swms == 1) ? '<i class="fa fa-check fa-lg" style="color: #5cb85c;"></i>' : '<i class="fa fa-times fa-lg" style="color: #d9534f;"></i>'; ?></span> <span>Yes</span></p>
															</div>

															<div class="col-sm-4 m-bottom-10 clearfix">
																<p class="m-top-10"><span class="reviewed_swms-no-icon"><?php echo ($reviewed_swms == 0) ? '<i class="fa fa-check fa-lg" style="color: #5cb85c;"></i>' : '<i class="fa fa-times fa-lg" style="color: #d9534f;"></i>'; ?></span> <span>No</span></p>
															</div>

															<div class="col-sm-12 m-bottom-10 clearfix">
																<p class="m-top-10" style="color: #d9534f"><strong>Notes: <span class="reviewed_swms_notes-data" style="white-space: pre-wrap;"><?php echo $reviewed_swms_notes; ?></span></strong></p>
															</div>

														</div>

														<div class="col-sm-12 m-bottom-10 m-top-10 clearfix reviewed_swms_edit" style="display:none;">
															
															<div class="row">
																<div class="col-md-12">
																	<div class="radio-inline">
																		<label>
																			<input type="radio" name="reviewed_swms" id="reviewed_swms1" value="1" <?php echo ($reviewed_swms == 1) ? 'checked' : '' ; ?>> Yes
																		</label>
																	</div>
																
																	<div class="radio-inline">
																		<label>
																			<input type="radio" name="reviewed_swms" id="reviewed_swms2" value="0" <?php echo ($reviewed_swms == 0) ? 'checked' : '' ; ?>> No
																		</label>
																	</div>
																</div>
															</div>

															<div class="box">
																<div class="box-head pad-5">
																	<label for="reviewed_swms_notes"><i class="fa fa-pencil-square fa-lg"></i> Notes:</label>
																</div>
																	
																<div class="box-area pad-5 clearfix">
																	<div class="clearfix">												
																		<div class="">
																			<textarea class="form-control" id="reviewed_swms_notes" rows="2" name="reviewed_swms_notes"><?php echo $reviewed_swms_notes; ?></textarea>
																		</div>
																	</div>
																</div>
															</div>
														</div>

													</div>

													<div class="col-sm-12 m-bottom-10 clearfix">

														<div id="safety_related_convictions_edit_btn" class="btn btn-warning pull-right btn-sm m-right-10"><i class="fa fa-pencil-square-o"></i> Edit</div>
														<div id="safety_related_convictions_save_btn" class="btn btn-success pull-right btn-sm m-right-10" style="display:none;"><i class="fa fa-floppy-o"></i> Save</div>

														<div class="section-header"><strong>5. Have you or your company had any safety related convictions?</strong></div>

														<div class="row m-bottom-10 m-top-10 clearfix safety_related_convictions_group"> 

															<div class="col-sm-4 m-bottom-10 clearfix">
																<p class="m-top-10"><span class="safety_related_convictions-yes-icon"><?php echo ($safety_related_convictions == 1) ? '<i class="fa fa-check fa-lg" style="color: #5cb85c;"></i>' : '<i class="fa fa-times fa-lg" style="color: #d9534f;"></i>'; ?></span> <span>Yes</span></p>
															</div>

															<div class="col-sm-4 m-bottom-10 clearfix">
																<p class="m-top-10"><span class="safety_related_convictions-no-icon"><?php echo ($safety_related_convictions == 0) ? '<i class="fa fa-check fa-lg" style="color: #5cb85c;"></i>' : '<i class="fa fa-times fa-lg" style="color: #d9534f;"></i>'; ?></span> <span>No</span></p>
															</div>

															<div class="safety_related_convictions_details_wrap col-sm-12 m-bottom-10 clearfix" <?php echo ($safety_related_convictions == 1) ? 'style="display: show;"' : 'style="display: none;"'; ?>>
																<p class="m-top-10"><strong>Details</strong>: <span  class="comments-data" style="white-space: pre-wrap;"><?php echo $safety_related_convictions_details; ?></span></p>
															</div>

															<div class="col-sm-12 m-bottom-10 clearfix">
																<p class="m-top-10" style="color: #d9534f"><strong>Notes: <span class="safety_related_convictions_notes-data" style="white-space: pre-wrap;"><?php echo $safety_related_convictions_notes; ?></span></strong></p>
															</div>

														</div>

														<div class="col-sm-12 m-bottom-10 m-top-10 clearfix safety_related_convictions_edit" style="display:none;">
															
															<div class="row">
																<div class="col-md-12">
																	<div class="radio-inline">
																		<label>
																			<input type="radio" name="safety_related_convictions" id="safety_related_convictions1" value="1" <?php echo ($safety_related_convictions == 1) ? 'checked' : '' ; ?>> Yes
																		</label>
																	</div>
																
																	<div class="radio-inline">
																		<label>
																			<input type="radio" name="safety_related_convictions" id="safety_related_convictions2" value="0" <?php echo ($safety_related_convictions == 0) ? 'checked' : '' ; ?>> No
																		</label>
																	</div>
																</div>
															</div>

															<div class="box">
																<div class="box-head pad-5">
																	<label for="safety_related_convictions_notes"><i class="fa fa-pencil-square fa-lg"></i> Notes:</label>
																</div>
																	
																<div class="box-area pad-5 clearfix">
																	<div class="clearfix">												
																		<div class="">
																			<textarea class="form-control" id="safety_related_convictions_notes" rows="2" name="safety_related_convictions_notes"><?php echo $safety_related_convictions_notes; ?></textarea>
																		</div>
																	</div>
																</div>
															</div>
														</div>

													</div>

													<div class="col-sm-12 m-bottom-10 clearfix">

														<div id="confirm_licences_certifications_edit_btn" class="btn btn-warning pull-right btn-sm m-right-10"><i class="fa fa-pencil-square-o"></i> Edit</div>
														<div id="confirm_licences_certifications_save_btn" class="btn btn-success pull-right btn-sm m-right-10" style="display:none;"><i class="fa fa-floppy-o"></i> Save</div>

														<div class="section-header"><strong>6. Please confirm you understand that all staff and site personnel must have the appropriate licences and certifications to undertake the works and provision of these details is required prior to commencement.</strong></div>

														<div class="row m-bottom-10 m-top-10 clearfix confirm_licences_certifications_group"> 

															<div class="col-sm-4 m-bottom-10 clearfix">
																<p class="m-top-10"><span class="confirm_licences_certifications-yes-icon"><?php echo ($confirm_licences_certifications == 1) ? '<i class="fa fa-check fa-lg" style="color: #5cb85c;"></i>' : '<i class="fa fa-times fa-lg" style="color: #d9534f;"></i>'; ?></span> <span>Yes</span></p>
															</div>

															<div class="col-sm-4 m-bottom-10 clearfix">
																<p class="m-top-10"><span class="confirm_licences_certifications-no-icon"><?php echo ($confirm_licences_certifications == 0) ? '<i class="fa fa-check fa-lg" style="color: #5cb85c;"></i>' : '<i class="fa fa-times fa-lg" style="color: #d9534f;"></i>'; ?></span> <span>No</span></p>
															</div>

															<div class="col-sm-12 m-bottom-10 clearfix">
																<p class="m-top-10" style="color: #d9534f"><strong>Notes: <span class="confirm_licences_certifications_notes-data" style="white-space: pre-wrap;"><?php echo $confirm_licences_certifications_notes; ?></span></strong></p>
															</div>

														</div>

														<div class="col-sm-12 m-bottom-10 m-top-10 clearfix confirm_licences_certifications_edit" style="display:none;">
															
															<div class="row">
																<div class="col-md-12">
																	<div class="radio-inline">
																		<label>
																			<input type="radio" name="confirm_licences_certifications" id="confirm_licences_certifications1" value="1" <?php echo ($confirm_licences_certifications == 1) ? 'checked' : '' ; ?>> Yes
																		</label>
																	</div>
																
																	<div class="radio-inline">
																		<label>
																			<input type="radio" name="confirm_licences_certifications" id="confirm_licences_certifications2" value="0" <?php echo ($confirm_licences_certifications == 0) ? 'checked' : '' ; ?>> No
																		</label>
																	</div>
																</div>
															</div>

															<div class="box">
																<div class="box-head pad-5">
																	<label for="confirm_licences_certifications_notes"><i class="fa fa-pencil-square fa-lg"></i> Notes:</label>
																</div>
																	
																<div class="box-area pad-5 clearfix">
																	<div class="clearfix">												
																		<div class="">
																			<textarea class="form-control" id="confirm_licences_certifications_notes" rows="2" name="confirm_licences_certifications_notes"><?php echo $confirm_licences_certifications_notes; ?></textarea>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												
												<?php else: ?>

													<div class="col-sm-12 m-bottom-10 clearfix">

														<center>
															<h1>No Occupational Health and Safety records yet.</h1>
															<div id="add_OHS" class="btn btn-success pull-center btn-sm m-right-10">Enable OH&S questions</div>
														</center>

													</div>

												<?php endif; ?>

											</div>
											<!-- OH&S end -->

										</div>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
						
						<div class="col-md-3">
							<?php if($has_project == 1): ?>
								<div class="box danger-box">
									<div class="box-head pad-5">
										<label><i class="fa fa-info-circle fa-lg"></i> Alert</label>
									</div>
									<div class="box-area pad-5" id="container">
										<p>This client has a project.</p>
									</div>
								</div>
							<?php endif; ?>


							

							</form>


<?php if($company_type != 'Client' ): ?>
							<div class="box">
								<div class="box-head pad-5">
									<label><i class="fa fa-info-circle fa-lg"></i> Update MYOB Name</label>
								</div>
								<div class="box-area pad-5" id="container">
									<p>Currenlty Using: <strong><?php echo ($myob_name != '' ? $myob_name  : 'UN-NAMED'); ?></strong></p>

 
									<form class="form-horizontal company-form" role="form" method="post" action="<?php echo base_url(); ?>company/update_myob_name">
				 

										<div class="  row m-3">
											<div class="col-sm-10 col-md-9">	<input type="text" class="form-control myob_name" id="myob_name" name="myob_name"  placeholder="MYOB Name" value="<?php echo $myob_name; ?>"></div>
											<input type="hidden" name="company_id" value="<?php echo $company_id; ?>">
											<div class="col-sm-2  col-md-3">	<input type="submit" class="  btn btn-success" name="submit_myob_name" value="Save"> </div>																					
										</div>


										



									</form>
									<p class="clearfix"> </p>

								</div>
							</div>
<?php endif; ?>

							
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
					





				</div>	
				<!-- Insurance Modal -->
				<div class="modal fade" id="attach_insurance_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				    <div class="modal-dialog">
					    <div class="modal-content">
					    	<form action="<?php echo base_url(); ?>company/upload_insurance" method="post" enctype="multipart/form-data">
						        <div class="modal-header">
							        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							        <h4 class="modal-title insurance_title"></h4>
							        <input type="hidden" id = "insurance_type" name = "insurance_type">
							        <input type="hidden" id = "company_id" name = "company_id" value = "<?php echo $company_id ?>">
						        </div>
						        <div class="modal-body" style = "height: 50px">
						        	<div class="col-sm-4">
						        		<input type="file" name="userfile[]" multiple="multiple" accept="application/pdf">
									</div>
							       	<div class="col-sm-4 text-right">Expiration Date</div>
								   	<div class="col-sm-4"><input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control datepicker" id="attach_expiration" name="attach_expiration"></div>
								</div>
								<div class="modal-footer">
								   	<button type = "submit" class="btn btn-primary">Attach</button>
								   	<button type="button" class="btn btn-default" id = "update_estimate_no" data-dismiss="modal">Cancel</button>
								</div>
							</form>
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
				<!-- Insurance Modal -->			
			</div>
		</div>
	</div>
</div>
<script>
    var base_url = '<?php echo site_url(); //you have to load the "url_helper" to use this function ?>';
    var baseurl = '<?php echo base_url() ?>';
    var company_id = '<?php echo $company_id ?>';
	var app = new Vue({
	  	el: '#app',
	  	data: {
	  		contractor_site_staff: [],
	  		siteStaffFName: null,
		    siteStaffLName: null,
		    siteStaffMobile: null,
		    siteStaffPosition: null,
		    siteStaffEmail: null,
		    is_apprentice: '0',

	  		ss_fname: null,
	  		ss_sname: null,
	  		ss_position: null,
	  		contractor_site_staff_id: null,

	  		is_contractors: null,
			user_id: null,
			ecFName: null,
			ecSName: null,
			ecRelation: null,
			ecContacts: null,
			cont_sitestaff_emergency_contatacts: [],

			cont_sitestaff_license_certificates:[],
			licenseCertTypes: [],

			LCtype: null,
			LCName: null,
			LCTypeName: null,
			lcNumber: null,
			expirationDate: null,
			is_contractors: 1,
			showLCTypeName: false,

			cont_sitestaff_training_records: [],
			trainingName: null,
			trainingDate: null,
			trainingLoc: null,

	  	},

	  	mounted: function(){
	  		this.fetchContractorSiteStaff();
	  		this.fetchContSiteStaffEmergencyContacts();
	  		this.fetchContSiteStaffUserLicencesCertificates();
	  		this.fetchLicenseCertType();
	  		this.fetchContSiteStaffTrainingRecords();
	  	},

	  	filters: {
	  		getDayname: function(date){
        		return moment(date).format('ddd');
        	},
        	ausdate: function(date) {
        		return moment(date).format('DD/MM/YYYY');
        	},
        	getTime: function(date){
        		return moment(date).format('h:m a');
        	}
	  	},

	   	methods: {
	   		isExpired: function(expiration_date){
          		return moment().isAfter(expiration_date);
        	},

	   		formatMobileNumber: function(){
          		if(this.siteStaffMobile.length == 4){
            		this.siteStaffMobile = this.siteStaffMobile + " ";
          		}

          		if(this.siteStaffMobile.length == 8){
            		this.siteStaffMobile = this.siteStaffMobile + " ";
          		}
        	},

        	formatPhoneNumber: function() {
          
          		if(this.ecContacts.length == 2){
            		this.ecContacts = this.ecContacts + " ";
          		}
          		if(this.ecContacts.length == 7){
            		this.ecContacts = this.ecContacts + " "; 
          		}
        	},

	   		fetchContractorSiteStaff: function(){
	   			$.post(baseurl+"company/get_cont_site_staff",
     	 		{ company_id: company_id,},
      			function(result){
      				app.contractor_site_staff = JSON.parse(result);
      			});
	   		},


	   		showAddSiteStaff: function(){
	   			this.siteStaffFName = null;
		        this.siteStaffLName = null;
		        this.siteStaffMobile = null;
		        this.siteStaffPosition = null;
	   			$("#add_cont_site_staff").modal('show');
	   		},

	   		addContractorSiteStaff: function(){

	          if(this.siteStaffFName == null || this.siteStaffLName == null || this.siteStaffMobile == null || this.siteStaffPosition == null){
	            alert("All fields are required!");
	          }else{
	            $.post(baseurl+"company/add_cont_site_staff",
	            {
	            	is_company: 1,
	              	company_id: '<?php echo $company_id ?>',
	              	ss_fname: this.siteStaffFName,
	              	ss_sname: this.siteStaffLName,
	              	ss_position: this.siteStaffPosition,
	              	ss_mobile_no: this.siteStaffMobile,
	              	ss_email: this.siteStaffEmail,
	              	is_apprentice: this.is_apprentice
	            },
	            function(result){
	              	app.contractor_site_staff = JSON.parse(result);
	            });  
	          }
	          
	        },

	        showUpdateContractorSiteStaff: function(contractor_site_staff_id){
	          	this.contractor_site_staff_id = contractor_site_staff_id;
	          	var gi_date = "";
	          	for (var key in app.contractor_site_staff) {
		            if(app.contractor_site_staff[key].contractor_site_staff_id == contractor_site_staff_id){
		                this.siteStaffFName = app.contractor_site_staff[key].site_staff_fname;
		                this.siteStaffLName = app.contractor_site_staff[key].site_staff_sname;
		                this.siteStaffMobile = app.contractor_site_staff[key].mobile_number;
		                this.siteStaffPosition = app.contractor_site_staff[key].position;
		                gi_date = app.contractor_site_staff[key].general_induction_date;
		                this.siteStaffEmail = app.contractor_site_staff[key].email;
		                this.is_apprentice = app.contractor_site_staff[key].is_apprentice;
		            }
	          	}

	          	$('#gi_date').val(gi_date);
		       
		        $("#update_cont_site_staff").modal('show');

	        },

	        updateContractorSiteStaff: function(){
	        	var company_id = $('#updatecontractorName').val();
	        	var gi_date = $('#gi_date').val();
	          	$.post(baseurl+"company/update_cont_site_staff",
	          	{
	          		is_company: 1,
		            contractor_site_staff_id: this.contractor_site_staff_id,
		            company_id: '<?php echo $company_id ?>',
		            ss_fname: this.siteStaffFName,
		            ss_sname: this.siteStaffLName,
		            ss_position: this.siteStaffPosition,
		            ss_mobile_no: this.siteStaffMobile,
		            ss_email: this.siteStaffEmail,
		            gi_date: gi_date,
		            is_apprentice: this.is_apprentice
	          	},
	          	function(result){
	          		alert(result);
	            	app.contractor_site_staff = JSON.parse(result);
	          	});
	        },

	        removeContractorSiteStaff: function(){
	        	$.post(baseurl+"company/remove_cont_site_staff",
	          	{
	          		is_company: 1,
	          		company_id: '<?php echo $company_id ?>',
	            	contractor_site_staff_id: this.contractor_site_staff_id,
	          	},
	          	function(result){
	            	app.contractor_site_staff = JSON.parse(result);
	          	}); 
	        },

	        fetchContSiteStaffEmergencyContacts: function(){
	          $.post(baseurl+"induction_health_safety/fetch_cont_sitestaff_emergency_contacts",
	          {},
	          function(result){
	            app.cont_sitestaff_emergency_contatacts = JSON.parse(result);
	          }); 
	        },

	        showAddCSSEmergenceContacts: function(user_id){
	          	this.is_contractors = 1;
	          	this.user_id = user_id;
	          	this.ecFName = null;
	          	this.ecSName = null;
	          	this.ecRelation = null;
	          	this.ecContacts = null;
	          	$("#add_emergency_contact").modal('show');
	        },

	        addEmergencyContacts: function(){
	        	var is_contractors = this.is_contractors;
	          	$.post(baseurl+"induction_health_safety/add_emergency_contact",
	          	{
		            is_contractors: this.is_contractors,
		            user_id: this.user_id,
		            ecFName: this.ecFName,
		            ecSName: this.ecSName,
		            ecRelation: this.ecRelation,
		            ecContacts: this.ecContacts
	          	},
	          	function(result){
		            if(is_contractors == '1'){
		              	app.cont_sitestaff_emergency_contatacts = JSON.parse(result);
		            }else{
		              	app.emergency_contatacts = JSON.parse(result);
		            }
	          	}); 
	        },

	        showUpdateCSSEmergencyContacts: function(sitestaff_emergency_contacts_id){
          		this.sitestaff_emergency_contacts_id = sitestaff_emergency_contacts_id;
	          	for (var key in app.cont_sitestaff_emergency_contatacts) {
	              	if(app.cont_sitestaff_emergency_contatacts[key].sitestaff_emergency_contacts_id == sitestaff_emergency_contacts_id){
		                this.user_id = app.cont_sitestaff_emergency_contatacts[key].user_id;
		                this.ecFName = app.cont_sitestaff_emergency_contatacts[key].contact_fname;
		                this.ecSName = app.cont_sitestaff_emergency_contatacts[key].contact_sname;
		                this.ecRelation = app.cont_sitestaff_emergency_contatacts[key].relation;
		                this.ecContacts = app.cont_sitestaff_emergency_contatacts[key].contacts;
		                this.is_contractors = 1;
	              	}
	          	}
          		$("#update_emergency_contact").modal('show');
          		return false;
        	},
	   		
	   		updateEmergencyContacts: function(){
	   			var is_contractors = this.is_contractors;

		        $.post(baseurl+"induction_health_safety/update_emergency_contact",
		        {
		           	sitestaff_emergency_contacts_id: this.sitestaff_emergency_contacts_id,
		            user_id: this.user_id,
		            ecFName: this.ecFName,
		            ecSName: this.ecSName,
		            ecRelation: this.ecRelation,
		            ecContacts: this.ecContacts,
		            is_contractors: this.is_contractors
		        },
		        function(result){
		            if(is_contractors == '1'){
		              app.cont_sitestaff_emergency_contatacts = JSON.parse(result);
		            }else{
		              app.emergency_contatacts = JSON.parse(result);
		            }
		        }); 
	   		},
			
			deleteEmergencyContacts: function(){
				var is_contractors = this.is_contractors;

		        $.post(baseurl+"induction_health_safety/remove_emergency_contacts",
		        {
		            sitestaff_emergency_contacts_id: this.sitestaff_emergency_contacts_id,
		            is_contractors: this.is_contractors
		        },
		        function(result){
		            if(is_contractors == '1'){
		              app.cont_sitestaff_emergency_contatacts = JSON.parse(result);
		            }else{
		              app.emergency_contatacts = JSON.parse(result);
		            }
		        });
			},

			fetchContSiteStaffUserLicencesCertificates: function(){
		        $.post(baseurl+"induction_health_safety/fetch_cont_sitestaff_licences_certificates",
		        {},
		        function(result){
		            app.cont_sitestaff_license_certificates = JSON.parse(result);
		        }); 
	        },

	        fetchLicenseCertType: function(){
          		$.post(baseurl+"induction_health_safety/fetch_license_cert_type",
          		{},
          		function(result){
            		app.licenseCertTypes = JSON.parse(result);
          		});
        	},

			showAddCSSLicencesCert: function(user_id){
				this.user_id = user_id;
	          	this.is_contractors = 1;
	          	this.LCtype = null;
	          	this.LCName = null;
	          	this.LCTypeName = null;
	          	this.lcNumber = null;
	          	this.expirationDate = null;
	          	this.is_contractors = 1;
	          	this.showLCTypeName = false;
	          	$("#add_license_cert").modal("show");
			},
		
			typeChange: function(){
				if(this.LCName == '0'){
		            this.showLCTypeName = true;
		        }else{
		            this.showLCTypeName = false;
		        }
			},

			addLC: function(){
				if(this.showLCTypeName == true){
		            this.LCName = this.LCTypeName;
		            $.post(baseurl+"induction_health_safety/insert_lc_type",
		            {
		              	lctypename: this.LCTypeName
		            },
		            function(result){
		              	app.licenseCertTypes = JSON.parse(result);
		              	this.showLCTypeName = false;
		            }); 
		        }
		      
		        var is_contractors = this.is_contractors;
		        $.post(baseurl+"induction_health_safety/add_licence_cert",
		        {
		            is_contractors: this.is_contractors,
		            user_id: this.user_id,
		            LCtype: this.LCtype,
		            LCName: this.LCName,
		            lcNumber: this.lcNumber,
		            expirationDate: this.expirationDate
		        },
		        function(result){
		            if(is_contractors == '1'){
		              	app.cont_sitestaff_license_certificates = JSON.parse(result);
		            }else{
		              	app.license_certificates = JSON.parse(result);
		            }
		        }); 
			},

			showUpdateCSSLicenceCert: function(user_license_certificates_id){
	          	this.showLCTypeName = false;
	          	this.user_license_certificates_id = user_license_certificates_id;
	          	this.is_contractors = 1;
	          	for (var key in app.cont_sitestaff_license_certificates) {
	              	if(app.cont_sitestaff_license_certificates[key].user_license_certificates_id == user_license_certificates_id){
		                this.LCtype = app.cont_sitestaff_license_certificates[key].is_license;
		                this.LCName = app.cont_sitestaff_license_certificates[key].type;
		                this.lcNumber = app.cont_sitestaff_license_certificates[key].number;
		                this.expirationDate = app.cont_sitestaff_license_certificates[key].expiration_date;
	              	}
	          	}
	          	$("#update_license_cert").modal("show");
	        },

	        updateLC: function(){
	        	var is_contractors = this.is_contractors;
		        $.post(baseurl+"induction_health_safety/update_licence_cert",
		        {
		            is_contractors: this.is_contractors,
		            user_license_certificates_id: this.user_license_certificates_id,
		            LCtype: this.LCtype,
		            LCName: this.LCName,
		            lcNumber: this.lcNumber,
		            expirationDate: this.expirationDate
		        },
		        function(result){
		            if(is_contractors == '1'){
		              app.cont_sitestaff_license_certificates = JSON.parse(result);
		            }else{
		              app.license_certificates = JSON.parse(result);
		            }
		        }); 
	        },

			removeLC: function(){
				var is_contractors = this.is_contractors;
		        $.post(baseurl+"induction_health_safety/remove_licence_cert",
		        {
		            is_contractors: this.is_contractors,
		            user_license_certificates_id: this.user_license_certificates_id
		        },
		        function(result){
		            if(is_contractors == '1'){
		              	app.cont_sitestaff_license_certificates = JSON.parse(result);
		            }else{
		              	app.license_certificates = JSON.parse(result);
		            }
		        }); 
			},

			fetchContSiteStaffTrainingRecords: function(){
	          	$.post(baseurl+"induction_health_safety/fetch_cont_sitestaff_training_records",
	          	{},
	          	function(result){
	            	app.cont_sitestaff_training_records = JSON.parse(result);
	          	}); 
	        },

			showAddCSSTraining: function(user_id){
				this.is_contractors = 1;
		        this.user_id = user_id;
		        this.trainingName = null;
		        this.trainingDate = null;
		        this.trainingLoc = null;
		        $("#add_training_cert").modal('show');
			},

			addTraining: function(){
				var is_contractors = this.is_contractors;
		        $.post(baseurl+"induction_health_safety/add_training",
		        {
		            is_contractors: this.is_contractors,
		            user_id: this.user_id,
		            trainingName: this.trainingName,
		            trainingDate: this.trainingDate,
		            trainingLoc: this.trainingLoc
		        },
		        function(result){
		            if(is_contractors == '1'){
		              app.cont_sitestaff_training_records = JSON.parse(result);
		            }else{
		              app.training_records = JSON.parse(result);
		            }
		        }); 
			},

			showUpdateCSSTraining: function(training_records_id){
				this.training_records_id = training_records_id;
		        this.is_contractors = 1;
		        for (var key in app.cont_sitestaff_training_records) {
		            if(app.cont_sitestaff_training_records[key].training_records_id == training_records_id){
		                this.trainingName = app.cont_sitestaff_training_records[key].training_type;
		                this.trainingDate = app.cont_sitestaff_training_records[key].date_undertaken;
		                this.trainingLoc = app.cont_sitestaff_training_records[key].taken_with;
		            }
		        }

		        $("#update_training_cert").modal('show');
			},

			updateTraining: function(){
				var is_contractors = this.is_contractors;
		        $.post(baseurl+"induction_health_safety/update_training",
		        {
		            is_contractors: this.is_contractors,
		            training_records_id: this.training_records_id,
		            trainingName: this.trainingName,
		            trainingDate: this.trainingDate,
		            trainingLoc: this.trainingLoc
		        },
		        function(result){
		            if(is_contractors == '1'){
		              app.cont_sitestaff_training_records = JSON.parse(result);
		            }else{
		              app.training_records = JSON.parse(result);
		            }
		        }); 
			},

			removeTraining: function(){
				var is_contractors = this.is_contractors;

		        $.post(baseurl+"induction_health_safety/remove_training",
		        {
		            is_contractors: this.is_contractors,
		            training_records_id: this.training_records_id
		        },
		        function(result){
		            if(is_contractors == '1'){
		              app.cont_sitestaff_training_records = JSON.parse(result);
		            }else{
		              app.training_records = JSON.parse(result);
		            }
		        }); 
			},

			sendLink: function(){
				alert("sdfsd");
			}
	   	}
	});
    function setABN_blank2(){
      $("#abn").val("");
      $("#acn").val("");
      $('#confirmModal').modal('hide');
    }

    function setABN_ACN2(){

	    var abn = $("#abn").val().replace(/[^\d]/g, "");

	    var new_abn_val = abn.substring( -2,2)+' '+abn.substring( 2,5)+' '+abn.substring( 5,8)+' '+abn.substring( 8,11);
	    $("#abn").val(new_abn_val);

	    // var acn_val = abn.substring( 2,5)+' '+abn.substring( 5,8)+' '+abn.substring( 8,11);
	    // $("#acn").val(acn_val);

	    $('#confirmModal').modal('hide');

	    saving_more_details();
	}

	function saveABN_ACN(){

		// location.reload(true);

		$("#save_more_details").hide();
        $("#edit_more_details").show();
        $('.more_details_group').show();
        $('.more_details_group_data').hide();        
	}

	$("#abn").keyup(function(){

	 	var abn = $("#abn").val().replace(/[^\d]/g, "");

	    // var new_abn_val = abn.substring( -2,2)+' '+abn.substring( 2,5)+' '+abn.substring( 5,8)+' '+abn.substring( 8,11);
	    // $("#abn").val(new_abn_val);

	    var acn_val = abn.substring( 2,5)+' '+abn.substring( 5,8)+' '+abn.substring( 8,11);
	    $("#acn").val(acn_val);

	});

	$('#add_OHS').click(function(){

		var data = $('#company_id_data').val();

		$.ajax({
	        'url' : '<?php echo base_url(); ?>company/enable_ohs_form',
	        'type' : 'POST',
	        'data' : {'ajax_var' : data },
	        'success' : function(result){
		        
		        if (result != ''){
		        	alert('Successfully enabled the Occupational Health and Safety questionnaire, Please answer correctly. Thanks.')

		        	location.reload(true);
		        } else {
		        	alert('An error has occured, please contact our administrator.')
		        }
	        }
	    });
	});

</script>
<?php $this->load->view('assets/logout-modal'); ?>