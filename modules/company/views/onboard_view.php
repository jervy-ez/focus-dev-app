<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>
<?php

	$onboarding_access = $this->session->userdata('onboarding');

	if($onboarding_access != 1): 		
		redirect('', 'refresh');
	endif;

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
					<li>
						<a href="<?php echo base_url(); ?>company/onboarding" class="btn-small">Onboarding</a>
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
					<div class="col-md-9">
						<div class="left-section-box">

							<input type="hidden" name="is_admin" id="is_admin" value="<?php echo $this->session->userdata('is_admin'); ?>">
							<input type="hidden" name="user_id" id="user_id" value="<?php echo $this->session->userdata('user_id'); ?>">
							<input type="hidden" name="workplace_health_safety_msg" id="workplace_health_safety_msg" value="<?php echo $workplace_health_safety_msg; ?>">
							<input type="hidden" name="swms_msg" id="swms_msg" value="<?php echo $swms_msg; ?>">
							<input type="hidden" name="jsa_msg" id="jsa_msg" value="<?php echo $jsa_msg; ?>">
							<input type="hidden" name="reviewed_swms_msg" id="reviewed_swms_msg" value="<?php echo $reviewed_swms_msg; ?>">
							<input type="hidden" name="safety_related_convictions_msg" id="safety_related_convictions_msg" value="<?php echo $safety_related_convictions_msg; ?>">
							<input type="hidden" name="confirm_licences_certifications_msg" id="confirm_licences_certifications_msg" value="<?php echo $confirm_licences_certifications_msg; ?>">

							<div class="box-head pad-top-15 pad-left-15 pad-bottom-10 clearfix">
								<div id="edit_company_name" class="btn btn-warning pull-right btn-md m-left-10 admin_access"><i class="fa fa-pencil-square-o"></i> Edit</div>
								<div id="save_company_name" class="btn btn-success pull-right btn-md admin_access" style="display:none;"><i class="fa fa-floppy-o"></i> Save</div>

								<label class="company_name" ><?php echo $company_name; ?> <span class="badge">(Pending for Approval)</span></label>

								<div class="input-group company_name_data pad-right-10" style="display:none;">
									<span class="input-group-addon"><i class="fa fa-briefcase fa-lg"></i></span>
									<input type="text" class="form-control" placeholder="Company Name*" name="company_name_data" id="company_name_data" value="<?php echo $company_name; ?>">
								</div>

								<input type="hidden" name="company_id_data" id="company_id_data" value="<?php echo $company_id; ?>">
							</div>
							
							<div class="box-area pad-10">
      							<div class="box-tabs m-bottom-15">

									<ul id="myTab" class="nav nav-tabs">
										<!-- <li class="hide">
											<a href="#projects" data-toggle="tab"><i class="fa fa-map-marker fa-lg"></i> Projects</a>
										</li>
										<li class="hide">
											<a href="#invoices" data-toggle="tab"><i class="fa fa-list-alt fa-lg"></i> Invoices</a>
										</li>
										<li class="hide">
											<a href="#reports" data-toggle="tab"><i class="fa fa-bar-chart-o fa-lg"></i> Reports</a>
										</li> -->
										<li class="active">
											<a href="#profile" data-toggle="tab"><i class="fa fa-briefcase fa-lg"></i> Profile</a>
										</li>
										<li class="">
											<a href="#contact-person" data-toggle="tab"><i class="fa fa-tty fa-lg"></i> Contact Person</a>
										</li>
										<?php //if($this->session->userdata('user_id') == '48' || $this->session->userdata('user_id') == '3'): ?>	

											<?php if($company_type_id == 2): ?>	
												<li class="">
													<a href="#ohs" data-toggle="tab"><i class="fa fa-plus-square fa-lg"></i> OH&S</a>
												</li>
											<?php endif; ?>
										<?php //endif; ?>	
										<?php //if($company_type_id == 2): ?>	
										<!-- <li class="hide">
											<a href="#insurance" data-toggle="tab"><i class="fa fa-paperclip fa-lg"></i> Insurances</a>
										</li>	
										<li class="hide">
											<a href="#site_staff" data-toggle="tab"><i class="fa fa-users fa-lg"></i> Site Staff</a>
										</li> -->
										<?php //endif; ?>									
									</ul>

									<div class="tab-content">

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
												<p class="m-top-10"><strong>State</strong>: <span class="data-p_state"><?php echo $p_state; ?></span></p>
												<p class="m-top-10"><strong>Suburb</strong>: <span class="data-p_suburb"><?php echo ucwords(strtolower($p_suburb)); ?></span></p>
												<p class="m-top-10"><strong>Postcode</strong>: <span class="data-p_postcode"><?php echo $p_postcode; ?></span></p>
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

											<!-- Bank Account Details -->
											<?php if($bank_name != 'None'): ?>
												<input type="hidden" name="bank_account_id" id="bank_account_id" value="<?php echo $bank_account_id; ?>">
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
											<!-- Bank Account Details -->

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
												<p class="m-top-10"><strong>ABN</strong>: <span class="data-abn"><?php echo $abn; ?></span>
													<?php if ($ABNexist_count != 0): ?>
														&nbsp;&nbsp;<span class="label label-danger" data-animation="true" data-toggle="tooltip" title="Company Name: <?php echo $ABNexistcompany_name; ?>">ABN already exist!</span>
													<?php endif; ?>
												</p> 


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

											<!-- Comments -->
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
											<!-- Comments-->
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

												</div>
												<!-- <div class="clearfix col-sm-12"><hr /></div> -->
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

										<!-- OH&S start -->
										<div class="tab-pane fade in  clearfix" id="ohs">

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
											
										</div>
										<!-- OH&S end -->

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
								<p>This new onboard is on pending status, please check the all the details before approval.</p>
							</div>
						</div>

						<div class="box">
							<div class="box-head pad-5">
								<label><i class="fa fa-exclamation-circle fa-lg"></i> Action</label>
							</div>
							<div class="box-area pad-20" id="container">
								<button type="button" id="onboard_approve" class="btn btn-success btn-lg btn-block" onclick="onboard_approved('<?php echo $company_id.'|'.$contact_email['general_email'].'|'.$company_type_id; ?>');">Approve</button>
								<button type="button" id="onboard_decline" class="btn btn-warning btn-lg btn-block" onclick="onboard_declined('<?php echo $company_id.'|'.$contact_email['general_email'].'|'.$company_type_id; ?>');">Decline</button>
							</div>
						</div>
					</div>
				</div>	
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="declinedComments" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Message to Declined Pending New Onboard</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
				    <label for="exampleInputEmail1">Write Comments Here:</label>
				    <textarea id="declinedCommentsBox" class="form-control" rows="10" ></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				<button type="button" id="" class="btn btn-primary" onclick="sendComments();">Send Comments</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade bs-example-modal-sm" id="confirmModal_selectBankForm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="margin-top: 120px; overflow: hidden;">
  <div class="modal-dialog modal-sm" style="width: 500px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title msgbox" id="myModalLabel_selectBankForm">Confirmation</h4>
      </div>
      <div class="modal-body">
        <p id="confirmText_selectBankForm">Are you sure you want to approve this pending new onboard?</p>

        <br>

        <label>Please select the state for the Bank Details Form attachment:</label>
        <select class="form-control" id="bank_details_form_state">
		  <option value="wa">WA</option>
		  <option value="nsw">NSW</option>
		</select>
      </div>
      <div id="confirmButtons_selectBankForm" class="modal-footer"></div>
    </div>
  </div>
</div>

<script type="text/javascript">

	var current_params = '';

	$( document ).ready(function() {
        $('[data-toggle="tooltip"]').tooltip({'placement': 'top'});
    });

	function onboard_approved(params){

		var params_split = params.split('|');
		var company_id = params_split[0];
		var company_type_id = params_split[2];

		if (company_type_id == 1){

			$('h4#myModalLabel.modal-title.msgbox').html("Confirmation");
			$('#confirmText').text('Are you sure you want to approve this pending new onboard?');
			$('#confirmButtons').html('<button type="button" class="btn btn-success" onclick="approved_company(\''+params+'\')">Yes</button>' +
								      '<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>');
		    $('#confirmModal').modal({
		      keyboard: false,
		      backdrop: 'static',
		      show: true
		    });
		}

		if (company_type_id == 2){

			$.ajax({
		        'url' : '<?php echo base_url(); ?>company/fetch_ohs_updated',
		        'type' : 'POST',
		        'data' : {'ajax_var' : company_id },
		        'success' : function(data){

		        	var ohs = data;
					var ohsArr = ohs.split('|');

					var workplace_health_safety_data = ohsArr[0];
					var swms_data = ohsArr[1];
					var jsa_data = ohsArr[2];
					var reviewed_swms_data = ohsArr[3];
					var safety_related_convictions_data = ohsArr[4];
					var confirm_licences_certifications_data = ohsArr[5];
					var ohs_validation_count = 0;
					var alert_message = 'This is the following errors:';

					if (workplace_health_safety_data == 0 ||swms_data == 0 || jsa_data == 0 || reviewed_swms_data == 0 ||safety_related_convictions_data == 1 || confirm_licences_certifications_data == 0){

						if (workplace_health_safety_data == 0) {

							alert_message += '<br><br>- Newly Registered Contractor is not having a Workplace Health & Safety system in their place.';
							ohs_validation_count = +(ohs_validation_count) + +(1);
						}

						if (swms_data == 0) {

							alert_message += '<br><br>- Newly Registered Contractor is not familiar with the Safe Work Method Statements (SWMS).';

							ohs_validation_count = +(ohs_validation_count) + +(1);
						}

						if (jsa_data == 0) {

							alert_message += '<br><br>- Newly Registered Contractor is not familiar with the Job Safety Analysis (JSA).';
							ohs_validation_count = +(ohs_validation_count) + +(1);
						}

						if (reviewed_swms_data == 0) {

							alert_message += '<br><br>- Safe Work Method Statements (SWMS) is not reviewed by the Newly Registered Contractor.';
							ohs_validation_count = +(ohs_validation_count) + +(1);
						}

						if (safety_related_convictions_data == 1) {

							alert_message += '<br><br>- Newly Registered Contractor has a past Safety Related Convictions. Please check with the General Manager.';
							ohs_validation_count = +(ohs_validation_count) + +(1);
						}

						if (confirm_licences_certifications_data == 0) {

							alert_message += '<br><br>- Newly Registered Contractor did not confirmed the appropriate licences and certification for all the staff and site personnel.';
							ohs_validation_count = +(ohs_validation_count) + +(1);
						}

						$('h4#myModalLabel.modal-title.msgbox').html("Message Alert!");
						$('#confirmText').html(alert_message);
						$('#confirmButtons').html('<button type="button" class="btn btn-warning" data-dismiss="modal">Go Back</button>');
					    $('#confirmModal').modal({
					      keyboard: false,
					      backdrop: 'static',
					      show: true
					    });

					} else {

						$('#confirmButtons_selectBankForm').html('<button type="button" class="btn btn-success" onclick="approved_company(\''+params+'\')">Yes</button>' +
											      '<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>');
					    $('#confirmModal_selectBankForm').modal({
					      keyboard: false,
					      backdrop: 'static',
					      show: true
					    });

					}
		        }
		    });

		}

		if (company_type_id == 3){

			$('#confirmButtons_selectBankForm').html('<button type="button" class="btn btn-success" onclick="approved_company(\''+params+'\')">Yes</button>' +
											      	 '<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>');
		    $('#confirmModal_selectBankForm').modal({
		      keyboard: false,
		      backdrop: 'static',
		      show: true
		    });
		}
	}

	// function onboard_approved(params){

	// 	$('h4#myModalLabel.modal-title.msgbox').html("Confirmation");
	// 	$('#confirmText').text('Are you sure you want to approve this pending new onboard?');
	// 	$('#confirmButtons').html('<button type="button" class="btn btn-success" onclick="approved_company(\''+params+'\')">Yes</button>' +
	// 						      '<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>');
	//     $('#confirmModal').modal({
	//       keyboard: false,
	//       backdrop: 'static',
	//       show: true
	//     });
	// }

	function approved_company(params){

		var company_activity_id = $('#company_activity_id').val();
		var bank_details_form_state = $('#bank_details_form_state').val();

		if (company_activity_id == 0){

			alert('Please edit and select activity first!');
			$('#confirmModal').modal('hide');
			return;

		} else {

			$('#confirmModal').modal('hide');

			var data = params;
			data += '|'+bank_details_form_state;

			$.ajax({
		        'url' : '<?php echo base_url(); ?>company/onboard_approved',
		        'type' : 'POST',
		        'data' : {'ajax_var' : data },
		        'success' : function(data){
			        if (data == '1'){
		        		alert('New Onboard is successfully approved');
		        		location.reload();
		        	} else {
		        		alert('Some errors found. Please contact administrator.');
		        	}
		        }
		    });
		}
	}

	function onboard_declined(params){
		$('h4#myModalLabel.modal-title.msgbox').html("Confirmation");
		$('#confirmText').text('Are you sure you want to decline this pending new onboard?');
		$('#confirmButtons').html('<button type="button" class="btn btn-success" onclick="declined_company(\''+params+'\')">Yes</button>' +
							      '<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>');
	    $('#confirmModal').modal({
	      keyboard: false,
	      backdrop: 'static',
	      show: true
	    });
	}

	function declined_company(params){

		var params_split = params.split('|');
		var company_id = params_split[0];
		var company_type_id = params_split[2];

		var workplace_health_safety_msg = $('#workplace_health_safety_msg').val();
		var swms_msg = $('#swms_msg').val();
		var jsa_msg = $('#jsa_msg').val();
		var reviewed_swms_msg = $('#reviewed_swms_msg').val();
		var safety_related_convictions_msg = $('#safety_related_convictions_msg').val();
		var confirm_licences_certifications_msg = $('#confirm_licences_certifications_msg').val();

		if(company_type_id == 2){

			$.ajax({
		        'url' : '<?php echo base_url(); ?>company/fetch_ohs_updated',
		        'type' : 'POST',
		        'data' : {'ajax_var' : company_id },
		        'success' : function(data){

		        	var ohs = data;
					var ohsArr = ohs.split('|');

					var workplace_health_safety_data = ohsArr[0];
					var swms_data = ohsArr[1];
					var jsa_data = ohsArr[2];
					var reviewed_swms_data = ohsArr[3];
					var safety_related_convictions_data = ohsArr[4];
					var confirm_licences_certifications_data = ohsArr[5];
					var alert_message = 'This is the following errors:';

					$(tinymce.get('declinedCommentsBox').getBody()).html('');

					if (workplace_health_safety_data == 0){
						$(tinymce.get('declinedCommentsBox').getBody()).html(workplace_health_safety_msg);
					}

					if (swms_data == 0){
						if (tinymce.get('declinedCommentsBox').getContent() != ''){
							$(tinymce.get('declinedCommentsBox').getBody()).html(tinymce.get('declinedCommentsBox').getContent() + '<br><br>' + swms_msg);
						} else {
							$(tinymce.get('declinedCommentsBox').getBody()).html(swms_msg);
						}
					}

					if (jsa_data == 0){
						if (tinymce.get('declinedCommentsBox').getContent() != ''){
							$(tinymce.get('declinedCommentsBox').getBody()).html(tinymce.get('declinedCommentsBox').getContent() + '<br><br>' + jsa_msg);
						} else {
							$(tinymce.get('declinedCommentsBox').getBody()).html(jsa_msg);
						}
					}

					if (reviewed_swms_data == 0){
						if (tinymce.get('declinedCommentsBox').getContent() != ''){
							$(tinymce.get('declinedCommentsBox').getBody()).html(tinymce.get('declinedCommentsBox').getContent() + '<br><br>' + reviewed_swms_msg);
						} else {
							$(tinymce.get('declinedCommentsBox').getBody()).html(reviewed_swms_msg);
						}
					}

					if (safety_related_convictions_data == 1){
						if (tinymce.get('declinedCommentsBox').getContent() != ''){
							$(tinymce.get('declinedCommentsBox').getBody()).html(tinymce.get('declinedCommentsBox').getContent() + '<br><br>' + safety_related_convictions_msg);
						} else {
							$(tinymce.get('declinedCommentsBox').getBody()).html(safety_related_convictions_msg);
						}
					}

					if (confirm_licences_certifications_data == 0){
						if (tinymce.get('declinedCommentsBox').getContent() != ''){
							$(tinymce.get('declinedCommentsBox').getBody()).html(tinymce.get('declinedCommentsBox').getContent() + '<br><br>' + confirm_licences_certifications_msg);
						} else {
							$(tinymce.get('declinedCommentsBox').getBody()).html(confirm_licences_certifications_msg);
						}
					}

					$('#confirmModal').modal('hide');
					$('#declinedComments').modal('show');
					
					current_params = params;
		        }
		    });

		} else {

			var company_activity_id = $('#company_activity_id').val();

			if (company_activity_id == 0){

				alert('Please edit and select activity first!');
				$('#confirmModal').modal('hide');
				return;

			} else {

				$(tinymce.get('declinedCommentsBox').getBody()).html('');

				$('#confirmModal').modal('hide');
				$('#declinedComments').modal('show');
				
				current_params = params;
			}
		}
	}

	// function declined_company(params){

	// 	var company_activity_id = $('#company_activity_id').val();

	// 	if (company_activity_id == 0){

	// 		alert('Please edit and select activity first!');
	// 		$('#confirmModal').modal('hide');
	// 		return;

	// 	} else {
	// 		$('#confirmModal').modal('hide');
	// 		$('#declinedComments').modal('show');
			
	// 		current_params = params;
	// 	}
	// }

	function sendComments(){

		var getDeclinedComments = tinymce.get('declinedCommentsBox').getContent();
		// var getDeclinedComments = $('#declinedCommentsBox').val();
		var data = current_params+'|'+getDeclinedComments;
		// var current_email = current_params.split('|').pop();

		$.ajax({
	        'url' : '<?php echo base_url(); ?>company/onboard_declined',
	        'type' : 'POST',
	        'data' : {'ajax_var' : data },
	        'success' : function(result){
		        if (result == '1'){
	        		alert('New Onboard is successfully declined. Comments are sent also via email.');
	        		location.reload();
	        	} else {
	        		alert('Some errors found. Please contact administrator.');
	        	}
	        }
	    });
	}

	function onboard_removed(params){
		$('h4#myModalLabel.modal-title.msgbox').html("Confirmation");
		$('#confirmText').text('Are you sure you want to remove this pending new onboard?');
		$('#confirmButtons').html('<button type="button" class="btn btn-success" onclick="removed_company(\''+params+'\')">Yes</button>' +
							      '<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>');
	    $('#confirmModal').modal({
	      keyboard: false,
	      backdrop: 'static',
	      show: true
	    });
	}

	function removed_company(params){

		$('#confirmModal').modal('hide');

		var data = params;

		alert(data);

		$.ajax({
	        'url' : '<?php echo base_url(); ?>company/onboard_removed',
	        'type' : 'POST',
	        'data' : {'ajax_var' : data },
	        'success' : function(data){

	        	if (data == '1'){
	        		alert('New Onboard is successfully removed');
	        		location.reload();
	        	} else {
	        		alert('Some errors found. Please contact administrator.');
	        	}
	        }
	    });
	}

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

</script>

<?php $this->load->view('assets/logout-modal'); ?>