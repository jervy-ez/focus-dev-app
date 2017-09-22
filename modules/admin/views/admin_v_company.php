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
					
						<div class="col-md-9">
							<div class="left-section-box">		




							<?php if(@$upload_error): ?>
								<div class="pad-10 no-pad-t">
									<div class="border-less-box alert alert-danger fade in">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										<h4>Houston we got a problem!</h4>
										Company Logo: <?php echo $upload_error['error'];?>
									</div>
								</div>
							<?php endif; ?>						
								
					
								
									<div class="box-head pad-top-15 pad-left-15 pad-bottom-10 clearfix">

										<div id="edit_company_name" class="btn btn-warning pull-right btn-md m-left-10"><i class="fa fa-pencil-square-o"></i> Edit</div>
										<div id="save_company_name" class="btn btn-success pull-right btn-md" style="display:none;"><i class="fa fa-floppy-o"></i> Save</div>
										<div id="delete_focus" class="btn btn-danger btn-md pull-left m-right-10 " style="display:none"><i class="fa fa-trash-o"></i> Delete Company</div>	
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

													<input type="hidden" name="physical_address_id_data" id="physical_address_id_data" value="<?php echo $address_id; ?>">

													<div class="col-sm-12 m-bottom-10 clearfix">
														<div id="edit_physical_address" class="btn btn-warning pull-right btn-sm m-right-10"><i class="fa fa-pencil-square-o"></i> Edit</div>
														<div id="save_physical_address" class="btn btn-success pull-right btn-sm m-right-10" style="display:none;"><i class="fa fa-floppy-o"></i> Save</div>

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
														<div id="edit_postal_address" class="btn btn-warning pull-right btn-sm m-right-10"><i class="fa fa-pencil-square-o"></i> Edit</div>
														<div id="save_postal_address" class="btn btn-success pull-right btn-sm m-right-10" style="display:none;"><i class="fa fa-floppy-o"></i> Save</div>

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



													<!-- Bank Account Details -->

													<input type="hidden" name="postal_address_id_data" id="bank_account_id" value="<?php echo $bank_account_id; ?>">
													<div class="col-sm-12 m-bottom-10 m-top-10 clearfix">
														<div id="edit_bank_details" class="btn btn-warning pull-right btn-sm m-right-10"><i class="fa fa-pencil-square-o"></i> Edit</div>
														<div id="save_bank_details" class="btn btn-success pull-right btn-sm m-right-10" style="display:none;"><i class="fa fa-floppy-o"></i> Save</div>
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


													<!-- Bank Account Details -->



													<!-- More Details -->

													<div class="col-sm-12 m-bottom-10 m-top-10 clearfix">
														<div id="edit_other_details" class="btn btn-warning pull-right btn-sm m-right-10"><i class="fa fa-pencil-square-o"></i> Edit</div>
														<div id="save_other_details" class="btn btn-success pull-right btn-sm m-right-10" style="display:none;"><i class="fa fa-floppy-o"></i> Save</div>
														<div class="section-header"><i class="fa fa-list-alt fa-lg"></i> <strong>More Details</strong></div>
													</div>

													
													<div class="col-sm-4 m-bottom-10 clearfix more_details_group">
														<div class="section-sub-header"><em>Business</em></div>													

															<?php if(isset($abn)){ echo '<p class="m-top-10"><strong>ABN</strong>: '.$abn.'</p>';} ?>
															<?php if(isset($acn)){ echo '<p class="m-top-10"><strong>ACN</strong>: '.$acn.'</p>';} ?>
													</div>

													<div class="col-sm-4 m-bottom-10 clearfix more_details_group">
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



													<div class="col-sm-12 m-bottom-10 m-top-10 clearfix more_details_group_data" style="display:none;">

														<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
															
															<label for="abn" class="col-sm-3 control-label">ABN*</label>
															<div class="col-sm-9 m-bottom-10">
															<input type="text" class="form-control" id="abn" placeholder="ABN" name="abn" value="<?php echo $abn; ?>">
															</div>

															<label for="acn" class="col-sm-3 control-label">ACN*</label>
															<div class="col-sm-9">
																<input type="text" class="form-control" id="acn" readonly="readonly" placeholder="ACN"    name="acn" value="<?php echo $acn; ?>">
															</div>

														</div>

														<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix>">
															<div class="col-sm-12 m-bottom-10">
																<label>Jurisdiction Sates</label>

																<select class="form-control chosen-multi col-sm-12" tabindex="24" id="jurisdiction" name="jurisdiction[]" multiple="multiple">
																	<?php foreach ($all_aud_states as $row){echo '<option value="'.$row->shortname.'|'.$row->name.'|'.$row->phone_area_code.'|'.$row->id.'">'.$row->name.'</option>';}?>
																</select>

																<script type="text/javascript">
																<?php echo  '$(".chosen-multi").val(['; ?><?php $jurisdiction = explode(',', $admin_jurisdiction_state_ids); ?>
																	<?php foreach ($jurisdiction  as $jur_key => $jur_value): ?><?php foreach ($all_aud_states  as $key => $value): ?>
																	<?php if( $jur_value == $value->id ){echo '"'.$value->shortname.'|'.$value->name.'|'.$value->phone_area_code.'|'.$value->id.'",';} ?>
																	<?php endforeach; ?>
																	<?php endforeach; echo ']).trigger("change");' ?>;
																</script>

															</div>
														</div>
													</div>

													<!-- More Details -->




													<!-- Contact Details -->

													<div class="col-sm-12 m-bottom-10 m-top-10 clearfix">
														<div id="edit_contact_details" class="btn btn-warning pull-right btn-sm m-right-10"><i class="fa fa-pencil-square-o"></i> Edit</div>
														<div id="save_contact_details" class="btn btn-success pull-right btn-sm m-right-10" style="display:none;"><i class="fa fa-floppy-o"></i> Save</div>
														<div class="section-header"><i class="fa fa-phone-square fa-lg"></i> <strong>Contact Details</strong></div>
													</div>
													
													<div class="col-sm-4 m-bottom-10 clearfix contact_details_group">
														<div class="section-sub-header"><em>Telephone</em></div>


														<?php
															$expOffnum = explode(' ',$office_number);
															$posExpOffnum = strpos($expOffnum[0],'1300');

															if($posExpOffnum === false){}else{
																$area_code = '';
															}

															$posExpOffnumB = strpos($expOffnum[0],'1800');

															if($posExpOffnumB === false){}else{
																$area_code = '';
															}
														?>


														<?php if(isset($office_number)){ echo '<p class="m-top-10"><strong>Office Contact Number</strong>: <span class="data_"office_number>'.$area_code.' '.$office_number.'</span></p>';} ?>
														<?php if(isset($mobile_number)){ echo '<p class="m-top-10"><strong>Mobile Number</strong>: <span class="data_mobile_number">'.$mobile_number.'</span></p>';} ?>

													</div>

													<div class="col-sm-4 m-bottom-10 clearfix contact_details_group">
														<div class="section-sub-header"><em>Email</em></div>
														<?php if(isset($general_email)){ echo '<p class="m-top-10"><strong>General</strong>: <span class="data_general_email">'.$general_email.'</span></p>';} ?>														
													</div>
													<input type="hidden" id="admin_contact_number_id" name="admin_contact_number_id" value="<?php echo $admin_contact_number_id; ?>">
													<input type="hidden" id="admin_email_id" name="admin_email_id" value="<?php echo $admin_email_id; ?>">


													<div class="col-sm-12 m-bottom-10 m-top-10 clearfix contact_details_group_data" style="display:none;">

														<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix>">
															<label for="account-number" class="col-sm-3 control-label">Office Number</label>
															<div class="col-sm-9 m-bottom-10">
																<div class="input-group">
																	<span class="input-group-addon" id="area_code"><?php echo $area_code; ?></span>
																	<input type="text" class="form-control" id="office_number" placeholder="Office Contact Number" onchange="contact_number_assign('office_number')" name="office_number" value="<?php echo $office_number; ?>">
																</div>
															</div>
														
															<label for="bsb-number" class="col-sm-3 control-label">Mobile Number</label>
															<div class="col-sm-9 m-bottom-10">
																<input type="text" class="form-control" id="mobile_number" name="mobile_number" placeholder="Mobile" onchange="mobile_number_assign('mobile_number')" value="<?php echo $mobile_number; ?>">
															</div>
														</div>


														<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix>">
														
															<label for="bsb-number" class="col-sm-3 control-label">General Email</label>
															<div class="col-sm-9 m-bottom-10">
																<input type="text" class="form-control" id="general_email" name="general_email" placeholder="Email" value="<?php echo $general_email; ?>">
															</div>

														</div>
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
									<label><i class="fa fa-picture-o fa-lg"></i> Company Logo</label>
								</div>
								<div class="box-area pad-5" id="container">
									<img class="img-thumbnail img-responsive" src="<?php echo site_url().'uploads/misc/'.$logo; ?>">
								</div>
							</div>


							
							<div class="box">
								<div class="box-head pad-5">
									<label><i class="fa fa-picture-o fa-lg"></i> Replace Company Logo</label>
								</div>
								<div class="box-area pad-5" id="container">
									<form class="form-horizontal clearfix form" role="form" method="post" action="" accept-charset="utf-8" enctype="multipart/form-data">
										<input type="hidden" name="company_id_data" id="company_id_data" value="<?php echo $company_id; ?>">							
										<input type="hidden" class="form-control" placeholder="Company Name*" name="company_name_data" id="company_name_data" value="<?php echo $company_name; ?>">
										<input type="file" name="focus_company_logo" class="form-control company_logo"  />
										<input type="submit" value="Submit" class="btn btn-warning btn-sm m-top-10">
									</form>
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
			</div>
		</div>
	</div>
</div>
<script>
    var base_url = '<?php echo site_url(); //you have to load the "url_helper" to use this function ?>';
</script>
<?php $this->load->view('assets/logout-modal'); ?>