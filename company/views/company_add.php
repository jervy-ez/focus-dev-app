<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>

<!-- title bar -->
<div class="container-fluid head-control">
	<div class="container-fluid">
		<div class="row">

			<div class="col-md-6 col-sm-4 col-xs-12 pull-left">
				<header class="page-header">
					<h3><?php $datestring = "%l, %F %d, %Y"; $time = time(); //use time() for timestamp  ?>
						New Company<br><small><?php echo mdate($datestring, $time); #echo date("l, F d, Y"); ?></small>
					</h3>
				</header>
			</div>

			<div class="page-nav-options col-md-6 col-sm-8 col-xs-12 pull-right hidden-xs">
				<ul class="nav nav-tabs navbar-right">
					<li>
						<a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Home</a>
					</li>
					<li>
						<a href="" class="btn-small"><i class="fa fa-magic"></i> Tour</a>
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
					<form class="form-horizontal form add_company_form" role="form" method="post" action="">
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

									
							
								<div class="box-head pad-10 clearfix">
									<button  type="reset" class="btn btn-warning pull-right reset-form-data">Reset Form</button >			
									<label>Add New Company</label><span> (<a href="#" data-placement="right" class="popover-test" title="" data-content="Hello there mate! Welcome to the clients screen." data-original-title="Welcome">?</a>)</span>
									<p>Fields having * is requred.</p>	
									<p><a href="#" class="tooltip-test" title="Tooltip">This link</a> and <a href="#" class="tooltip-test" title="Tooltip">that link</a> should have tooltips on hover.</p>						
								</div>
								<div class="box-area pad-10">								
									
									<div class="col-sm-12">
										<div class="form-group pad-5 no-pad-b">        								
         									<div class="input-group <?php if(form_error('company_name')){ echo 'has-error has-feedback';} ?>">
												<span class="input-group-addon"><i class="fa fa-briefcase  fa-lg"></i></span>
												<input type="text" class="form-control" placeholder="Company Name*" name="company_name" id="company_name" tabindex="1" value="<?php echo $this->input->post('company_name'); ?>">
											</div>
        								</div>
      								</div>
      								
      								<div class="box-tabs m-bottom-10">
										<ul id="myTab" class="nav nav-tabs">
											<li class="active">
												<a href="#physicalAddress" data-toggle="tab"><i class="fa fa-globe fa-lg"></i> Physical Address</a>
											</li>
											<li class="">
												<a href="#postalAddress" data-toggle="tab"  tabindex="8" ><i class="fa fa-inbox fa-lg"></i> Postal Address</a>
											</li>
											<input type="hidden" name="is_form_submit" value="1">
											<li class="pull-right m-top-10">
												<strong>Same values to Postal Address? <input type="checkbox" class="sameToPost" name="issamepost"> Yes</strong>
												<?php

												if(isset($_POST['is_form_submit'])){

													if($this->input->post('issamepost') != 'on' ){
														echo '<script type="text/javascript">';
														echo '$("input.sameToPost").prop( "checked", false ); var sameToPost = 0;';	
														echo '</script>';
														$sameToPost = 0;

													}else{
														echo '<script type="text/javascript">';
														echo '$("input.sameToPost").click();';
														echo '$("input.sameToPost").prop( "checked", true ); var sameToPost = 1;';	
														echo '</script>';
														$sameToPost = 1;

													}
												}else{
													echo '<script type="text/javascript">';
													echo '$("input.sameToPost").click();';
													echo '$("input.sameToPost").prop( "checked", true ); var sameToPost = 1; ';	
													echo '</script>';
													$sameToPost = 1;
												}

												?>
											</li>										
										</ul>
										<div class="tab-content">
											<div class="tab-pane fade active in clearfix" id="physicalAddress">
												
												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="unit_level" class="col-sm-3 control-label">Unit/Level</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="unit_level" placeholder="Unit/Level" name="unit_level" tabindex="2" value="<?php echo $this->input->post('unit_level'); ?>">
													</div>
												</div>
											
												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="number" class="col-sm-3 control-label">Number</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="number" placeholder="Number" tabindex="3" name="unit_number" value="<?php echo $this->input->post('unit_number'); ?>">
													</div>
												</div>
											
												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('street')){ echo 'has-error has-feedback';} ?>">
													<label for="street" class="col-sm-3 control-label">Street*</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="street" placeholder="Street" tabindex="4" name="street" value="<?php echo $this->input->post('street'); ?>">
													</div>
												</div>

												<div class="clearfix"></div>


												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('state_a')){ echo 'has-error has-feedback';} ?>">
													<label for="state" class="col-sm-3 control-label">State*</label>													
													<div class="col-sm-9">
														<!-- <input type="text" class="form-control" id="state_a" placeholder="State" name="state_a" value="<?php echo $this->input->post('state_a'); ?>"> -->


														<select class="form-control state-option-a chosen"  tabindex="5" id="state_a" name="state_a">															
															<option value="">Choose a State</option>
															<?php
															foreach ($all_aud_states as $row){
																echo '<option value="'.$row->shortname.'|'.$row->name.'|'.$row->phone_area_code.'|'.$row->id.'">'.$row->name.'</option>';
															}?>
														</select>
														<script type="text/javascript">$("select#state_a").val("<?php echo $this->input->post('state_a'); ?>");</script>
													</div>



												</div>
												
												<div class="col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('suburb_a')){ echo 'has-error';} ?>">
													<label for="suburb" class="col-sm-3 control-label">Suburb*</label>
													<div class="col-sm-9 col-xs-12">

														<?php if($this->input->post('suburb_a')): ?>
															<select class="form-control suburb-option-a chosen" id="suburb_a" name="suburb_a">

														<?php else: ?>
															<select class="form-control suburb-option-a disabled chosen"  tabindex="6"  id="suburb_a" name="suburb_a">
																<option value="">Choose a Suburb...</option>

														<?php endif; ?>

														

														<?php if($this->input->post('suburb_a')): ?>
															<?php $this->company->get_suburb_list('dropdown|state_id|'.$this->input->post('state_a')); ?>														
															<script type="text/javascript">$("select#suburb_a").val("<?php echo $this->input->post('suburb_a'); ?>");</script>
											
														<?php endif; ?>

														</select>
													</div>
												</div>											
												
												
												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('postcode_a')){ echo 'has-error has-feedback';} ?>">
													<label for="postcode_a" class="col-sm-3 control-label">Postcode*</label> <?php //echo $this->input->post('postcode_a'); ?>													
													<div class="col-sm-9  col-xs-12">
														 


														<?php if($this->input->post('postcode_a')): ?>
															<select class="form-control postcode-option-a chosen" id="postcode_a"  tabindex="7" name="postcode_a">
														<?php else: ?>
															<select class="form-control postcode-option-a disabled chosen"   tabindex="7"  id="postcode_a" name="postcode_a">
																<option value="">Choose a Postcode...</option>
														<?php endif; ?>

														<?php if($this->input->post('postcode_a')): ?>
															<?php $suburb_a = explode('|', $this->input->post('suburb_a')); ?>
															<?php $this->company->get_post_code_list($suburb_a[0]); ?>													
														
															<script type="text/javascript">$("select#postcode_a").val("<?php echo $this->input->post('postcode_a'); ?>");</script>
																									
														<?php endif; ?>		

														</select>
													</div>
												</div>
												

											
											</div>
											<div class="tab-pane fade clearfix" id="postalAddress">
												
												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="unitlevel2" class="col-sm-3 control-label">Unit/Level</label>
													<div class="col-sm-9 <?php echo ($sameToPost == 1 ? 'disabled-input' : '' ); ?>">
														<input type="text" class="form-control" id="unitlevel2"  tabindex="9"  placeholder="Unit/Level" name="unit_level_b" value="<?php echo $this->input->post('unit_level_b'); ?>">
													</div>
												</div>
											
												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="number2" class="col-sm-3 control-label">Number</label>
													<div class="col-sm-9 <?php echo ($sameToPost == 1 ? 'disabled-input' : '' ); ?>">
														<input type="text" class="form-control" id="number2" placeholder="Number"   tabindex="10" name="number_b" value="<?php echo $this->input->post('number_b'); ?>">
													</div>
												</div>
											
												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('street_b')){ echo 'has-error has-feedback';} ?>">
													<label for="street2" class="col-sm-3 control-label">Street*</label>
													<div class="col-sm-9 <?php echo ($sameToPost == 1 ? 'disabled-input' : '' ); ?>">
														<input type="text" class="form-control" id="street2" placeholder="Street"   tabindex="11" name="street_b" value="<?php echo $this->input->post('street_b'); ?>">
													</div>
												</div>

												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="pobox" class="col-sm-3 control-label">PO Box</label>
													<div class="col-sm-9 <?php echo ($sameToPost == 1 ? 'disabled-input' : '' ); ?>">
														<input type="text" class="form-control" id="pobox" placeholder="PO Box" name="pobox"  tabindex="12"  value="<?php echo $this->input->post('pobox'); ?>">
													</div>
												</div>

												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('state_b')){ echo 'has-error has-feedback';} ?>">
													<label for="state_b" class="col-sm-3 control-label">State*</label>
													

													<div class="col-sm-9">
														<!-- <input type="text" class="form-control" id="state_a" placeholder="State" name="state_a" value="<?php echo $this->input->post('state_a'); ?>"> -->

														<select class="form-control state-option-b chosen <?php echo ($sameToPost == 1 ? 'disabled-input' : '' ); ?>"  id="state_b"   tabindex="13" name="state_b"  >															
															<option value="">Choose a State</option>
															<?php
															foreach ($all_aud_states as $row){
																echo '<option value="'.$row->shortname.'|'.$row->name.'|'.$row->phone_area_code.'|'.$row->id.'">'.$row->name.'</option>';
															}?>


														</select>
														<script type="text/javascript">$("select#state_b").val("<?php echo $this->input->post('state_b'); ?>");</script>
													</div>

												</div>												
												
												<div class="col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('suburb_b')){ echo 'has-error';} ?>">
													<label for="suburb" class="col-sm-3 control-label">Suburb*</label>
													<div class="col-sm-9 col-xs-12">

														<?php if($this->input->post('suburb_b')): ?>
															<select class="form-control suburb-option-b chosen" id="suburb_b" tabindex="14" name="suburb_b">

														<?php else: ?>
															<select class="form-control suburb-option-b chosen <?php echo ($sameToPost == 1 ? 'disabled-input' : '' ); ?>" id="suburb_b" tabindex="14" name="suburb_b">
																<option value="">Choose a Suburb...</option>
														<?php endif; ?>
														

														<?php if($this->input->post('suburb_b')): ?>
															<?php $this->company->get_suburb_list('dropdown|state_id|'.$this->input->post('state_b')); ?>														
															<script type="text/javascript">$("select#suburb_b").val("<?php echo $this->input->post('suburb_b'); ?>");</script>
											
														<?php endif; ?>

														</select>
													</div>
												</div>
												
												
												
												<!-- <div class="col-sm-6 m-bottom-10 clearfix">
													<label for="postcode2" class="col-sm-3 control-label">Postcode*</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="postcode2" placeholder="Postcode">
													</div>
												</div> -->
												
												<div class="col-sm-6 m-bottom-10 clearfix <?php if(form_error('postcode_b')){ echo 'has-error has-feedback';} ?>">
													<label for="postcode_b" class="col-sm-3 control-label">Postcode*</label>													
													<div class="col-sm-9  col-xs-12">
														
														<?php if($this->input->post('postcode_b')): ?>
															<select class="form-control postcode-option-b chosen" id="postcode_b"   tabindex="15" name="postcode_b">
														<?php else: ?>
															<select class="form-control postcode-option-b chosen <?php echo ($sameToPost == 1 ? 'disabled-input' : '' ); ?>" id="postcode_b" tabindex="15" name="postcode_b">
																<option value="">Choose a Postcode...</option>
														<?php endif; ?>

														<?php if($this->input->post('postcode_b')): ?>
															<?php $suburb_a = explode('|', $this->input->post('suburb_b')); ?>
															<?php $this->company->get_post_code_list($suburb_a[0]); ?>													
														
															<script type="text/javascript">$("select#postcode_b").val("<?php echo $this->input->post('postcode_b'); ?>");</script>
																									
														<?php endif; ?>
															

														</select>
													</div>
												</div>
												
												
											</div>
										</div>
									</div>

									<input type="hidden" class="form-control disabled" readonly="readonly" id="areacode" name="areacode" placeholder="Areacode"  value="<?php echo $this->input->post('areacode'); ?>">

									<div class="box">
										<div class="box-head pad-5 m-bottom-5">
											<label><i class="fa fa-list-alt fa-lg"></i> More Details</label>
										</div>
										
										<div class="box-area pad-5 clearfix">
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('abn')){ echo 'has-error has-feedback';} ?>">
												<label for="abn" class="col-sm-3 control-label">ABN*</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="abn" placeholder="ABN" name="abn"   tabindex="16" value="<?php echo $this->input->post('abn'); ?>">
												</div>
											</div>
											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('acn')){ echo 'has-error has-feedback';} ?>">
												<label for="acn" class="col-sm-3 control-label">ACN*</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="acn" readonly="readonly" placeholder="ACN"    name="acn" value="<?php echo $this->input->post('acn'); ?>">
												</div>
											</div>
																						
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('type')){ echo 'has-error has-feedback';} ?>">
												<label for="type" class="col-sm-3 control-label">Type*</label>
												<div class="col-sm-9">
													<select class="form-control chosen" id="type" name="type"  tabindex="18" >														
														<option value="">Choose a Type...</option>
														<?php
														foreach ($comp_type_list as $row){
															echo '<option value="'.$row->company_type.'|'.$row->company_type_id.'">'.$row->company_type.'</option>';
														}?>
													</select>
													<?php if($this->input->post('type')!=''): ?>
														<script type="text/javascript">$("select#type").val("<?php echo $this->input->post('type'); ?>");</script>
													<?php endif; ?>
												</div>
											</div>


											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('activity')){ echo 'has-error has-feedback';} ?>">
												<label for="activity" class="col-sm-3 control-label">Activity*</label>
												<div class="col-sm-9">
													<select class="form-control activity chosen" id="activity" name="activity"  tabindex="19" >
														<?php if($this->input->post('activity')!='' && $this->input->post('type')!=''): ?>
															<?php $activity_type_arr = explode('|', $this->input->post('type')); ?>
															<?php $this->company->activity($activity_type_arr[0]); ?>
														<?php elseif ($this->input->post('activity')=='' && $this->input->post('type')!=''): ?>
															
															<?php $activity_type_arr = explode('|', $this->input->post('type')); ?>
															<?php $this->company->activity($activity_type_arr[0]); ?>
														<?php else: ?>
															<option value="">Choose a Activity...</option>
														<?php endif; ?>					
													</select>

													<?php if($this->input->post('activity')!=''): ?>
														<script type="text/javascript">$("select#activity").val("<?php echo $this->input->post('activity'); ?>");</script>
													<?php endif; ?>	
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
												<label for="parent" class="col-sm-3 control-label">Parent</label>
												<div class="col-sm-9">
													<select class="form-control chosen" id="parent" name="parent" tabindex="20" >														
														<option value="None|0" >None</option>																									
													</select>
													<?php if($this->input->post('parent')!=''): ?>
														<script type="text/javascript">$("select#parent").val("<?php echo $this->input->post('parent'); ?>");</script>
													<?php endif; ?>	
												</div>
											</div>

										</div>
									</div>
									
									<div class="clearfix"></div>




									<div class="box  bank_account" style="<?php echo ($this->input->post('type') == 'Client|1'  ? 'display:none;' : '');  ?> "  >
										<div class="box-head pad-5 m-bottom-5">
											<label><i class="fa fa-university fa-lg"></i> Bank Account</label>
										</div>
										
										<div class="box-area pad-5 clearfix">
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('account-name')){ echo 'has-error has-feedback';} ?>">
												<label for="account-name" class="col-sm-3 control-label">Account Name*</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="account-name" name="account-name"  tabindex="20" placeholder="Account Name"  value="<?php echo $this->input->post('account-name'); ?>">
												</div>
											</div>
											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('bank-name')){ echo 'has-error has-feedback';} ?>">
												<label for="bank-name" class="col-sm-3 control-label">Bank Name*</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="bank-name" name="bank-name" placeholder="Bank Name"  tabindex="21"  value="<?php echo $this->input->post('bank-name'); ?>">
												</div>
											</div>
											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('account-number')){ echo 'has-error has-feedback';} ?>">
												<label for="account-number" class="col-sm-3 control-label">Account Number*</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="account-number" name="account-number" placeholder="Account Number"  tabindex="22" value="<?php echo $this->input->post('account-number'); ?>">
												</div>
											</div>
											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('bsb-number')){ echo 'has-error has-feedback';} ?>">
												<label for="bsb-number" class="col-sm-3 control-label">BSB Number*</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="bsb-number" name="bsb-number" placeholder="BSB Number"  tabindex="23" value="<?php echo $this->input->post('bsb-number'); ?>">
												</div>
											</div>
										</div>
									</div>

									<div class="box">
										<div class="box-head pad-5 m-bottom-5">
											<label><i class="fa fa-tty fa-lg"></i> Assign Contact Person</label>
										</div>
										
										<div class="box-area pad-5 clearfix">


										<div class="add-contact-area">
									

												<?php 




													if(isset($_POST['add-contact-impt'])){

														$add_contact_impt_raw = $_POST["add-contact-impt"];
														$add_contact_impt = explode(',', $add_contact_impt_raw);
														$add_contact_impt = array_slice(array_filter($add_contact_impt), 0 );

														foreach ($add_contact_impt as $key => $value) {

															echo '<div class="item-form-'.$value.'">
													<strong class="clearfix"><i class="fa fa-user"></i> '.$this->input->post('contact_type_'.$value).'</strong><div class="clearfix"></div>

													<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix '; if(form_error('contact_f_name_'.$value)){ echo 'has-error has-feedback'; }  echo '">
														<label class="col-sm-3 control-label" for="contact_f_name_'.$value.'">First Name*</label>
														<div class="col-sm-9"><input type="text" value="'.$this->input->post('contact_f_name_'.$value).'" name="contact_f_name_'.$value.'" placeholder="First Name" id="contact_f_name_'.$value.'" class="form-control"></div>
													</div>

													<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix '; if(form_error('contact_l_name_'.$value)){ echo 'has-error has-feedback'; }  echo ' ">
														<label class="col-sm-3 control-label" for="contact_l_name_'.$value.'">Last Name*</label>
														<div class="col-sm-9"><input type="text" value="'.$this->input->post('contact_l_name_'.$value).'" name="contact_l_name_'.$value.'" placeholder="Last Name" id="contact_l_name_'.$value.'" class="form-control"></div>
													</div>

													<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix '; if(form_error('contact_gender_'.$value)){ echo 'has-error has-feedback'; }  echo '">
														<label class="col-sm-3 control-label" for="gender_'.$value.'">Gender*</label>
														<div class="col-sm-9">
															<select id="gender_'.$value.'" class="form-control" name="contact_gender_'.$value.'">
																<option value="">Select</option>
																<option value="Male">Male</option>
																<option value="Female">Female</option>
															</select>
														</div>
													</div>

													<script type="text/javascript">$("select#gender_'.$value.'").val("'.$this->input->post('contact_gender_'.$value).'");</script>


													<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix '; if(form_error('contact_email_'.$value)){ echo 'has-error has-feedback'; }  echo '">
														<label class="col-sm-3 control-label" for="contact_email_'.$value.'">Email*</label>
														<div class="col-sm-9"><input type="email" value="'.$this->input->post('contact_email_'.$value).'" name="contact_email_'.$value.'" placeholder="Email" id="contact_email_'.$value.'" class="form-control"></div>
													</div>

													<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix '; if(form_error('contact_number_'.$value)){ echo 'has-error has-feedback'; }  echo '">
														<label class="col-sm-3 control-label" for="contact_number_'.$value.'">Office Contact</label>
														<div class="col-sm-9">
															<div class="input-group">
																<span class="input-group-addon" id="area-code-text-'.$value.'">'.$this->input->post('areacode').'</span>
																<input type="text" value="'.$this->input->post('contact_number_'.$value).'" name="contact_number_'.$value.'" placeholder="Office Contact Number" id="contact_number_'.$value.'" class="form-control contact_number_assign" onchange="contact_number_assign(\'contact_number_'.$value.'\')" >
															</div>
														</div>
													</div>

													<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix '; if(form_error('mobile_number_'.$value)){ echo 'has-error has-feedback'; }  echo '">
														<label class="col-sm-3 control-label" for="mobile_number_'.$value.'">Mobile</label>
														<div class="col-sm-9">
															<input type="text" value="'.$this->input->post('mobile_number_'.$value).'" name="mobile_number_'.$value.'" placeholder="Mobile Number" id="mobile_number_'.$value.'" class="form-control mobile_number_assign" onchange="mobile_number_assign(\'mobile_number_'.$value.'\')" >
														</div>
													</div>

													<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
														<label class="col-sm-3 control-label" for="after_hours_'.$value.'">After Hours</label>
														<div class="col-sm-9">
															<div class="input-group">
																<span class="input-group-addon" id="mobile-area-code-text-'.$value.'">'.$this->input->post('areacode').'</span>
																<input type="text" value="'.$this->input->post('after_hours_'.$value).'" name="after_hours_'.$value.'" placeholder="After Hours Contact Number" id="after_hours_'.$value.'" class="form-control after_hours_assign" onchange="contact_number_assign(\'after_hours_'.$value.'\')">
															</div>
														</div>
													</div>

													<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
														<label class="col-sm-3 control-label" for="contact_type_'.$value.'">Contact Type</label>
														<div class="col-sm-9">
														<select class="form-control" id="contact_type_'.$value.'" name="contact_type_'.$value.'">
															<option value="General">General</option>
															<option value="Maintenance">Maintenance</option>
															<option value="Accounts">Accounts</option>
															<option value="Other">Other</option>
														</select>
														</div>
													</div>

													<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
														<label for="set_as_primary_'.$value.'" class="col-sm-3 control-label">Set as Primary</label>
														<input type="checkbox" style="margin-top: 10px; margin-left: 5px;" onclick="contact_set_primary(\'set_as_primary_'.$value.'\')" class="set_as_primary" id="set_as_primary_'.$value.'" name="set_as_primary_'.$value.'">														
													</div>

													<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
														<div onclick="removeFormAdd(\'item-form-'.$value.'\')" id="remove-form-_'.$value.'" class="btn btn-warning pull-right">Remove Form</div>
													</div>

													
												
													<script type="text/javascript">$("input.set_as_primary_'.$value.'").prop( "checked", false ); $("select#contact_type_'.$value.'").val("'.$this->input->post('contact_type_'.$value).'");</script>
												
												

													<div class="clearfix"></div><hr />



												</div>';


												
													if($this->input->post('set_as_primary_'.$value)=='on' ){
														echo '<script type="text/javascript">$("input#set_as_primary_'.$value.'").prop( "checked", true );</script>';
													}



												

														}
													}

												?>










												

												</div>
												<div class="col-md-9 col-sm-9 col-xs-12 m-bottom-10 clearfix">
													
													<div class="col-sm-2">
														<div class=" btn btn-info set-add-contact" tabindex="24" >Add Form</div>
													</div>

	
													<input type="hidden" id="add-contact-impt" name="add-contact-impt" value="<?php if(isset($_POST['add-contact-impt'])){ echo implode(',', $add_contact_impt ); }  ?>"> 
													
												</div>											
										</div>
									</div>
									
									<div class="clearfix"></div>
									
									<div class="box">
										<div class="box-head pad-5">
											<label for="project_notes"><i class="fa fa-pencil-square fa-lg"></i> Comments</label>
										</div>
											
										<div class="box-area pad-5 clearfix">
											<div class="clearfix">												
												<div class="">
													<textarea class="form-control" id="comments" rows="3" name="comments"><?php echo $this->input->post('comments'); ?></textarea>
												</div>
											</div>
										</div>
									</div>
									
								    <div class="m-top-15 clearfix">
								    	<div>
								        	<button type="button" class="btn btn-success btn-lg submit_form" id="add_company_form" name="save_bttn" value="Save"><i class="fa fa-floppy-o"></i> Save</button>
								        </div>
								    </div>
    
   
								</div>
							
						</div>
					</div>

					<?php //echo date("n/d/Y"); ?>
						
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
					</form>
				</div>				
			</div>
		</div>
	</div>
</div>


<div class="modal fade dynamic_error" tabindex="-1" role="dialog"  aria-hidden="true" style="display: none; overflow: hidden;">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      
    	<div class="modal-header">
    		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    		<h4 class="modal-title">Notice</h4>
    	</div>

    	<div class="modal-body">
    		<div class="row">
    			<div class="col-md-12">
    			<p class="dynamic_error_msg"></p>
    			</div>
    		</div>
    	</div>

    </div>
  </div>
</div>

<div id="add_contact" class="modal fade" tabindex="-1" data-width="760" style="display: none; overflow: hidden;">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Add New Contact Person</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<h4>Personal Details</h4>
						<em>Fill the fields in this window and will add a new record on the contact list. (* is requred)</em><p></p>
						<div class="col-sm-12 m-bottom-10 m-top-15 clearfix">
							<label for="contact_f_name" class="col-sm-3 control-label m-top-5">First Name*</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="contact_f_name" placeholder="First Name" name="contact_f_name" value="">
							</div>
						</div>
						
						<div class="col-sm-12 m-bottom-10 clearfix">
							<label for="contact_l_name" class="col-sm-3 control-label m-top-5">Last Name*</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="contact_l_name" placeholder="Last Name" name="contact_l_name" value="">
							</div>
						</div>
						<div class="col-sm-5 m-bottom-10 clearfix">
							<label for="number" class="col-sm-4 control-label m-top-5">Gender*</label>
							<div class="col-sm-8">
								<select name="contact_gender"  class="form-control" id="gender">
									<option value="">Select</option>
									<option value="Male">Male</option>
									<option value="Female">Female</option>
								</select>
							</div>
						</div>
						
						<div class="col-sm-7 m-bottom-10 clearfix">
							<label for="contact_email" class="col-sm-2 control-label m-top-5">Email*</label>
							<div class="col-sm-10">
								<input type="email" class="form-control" id="contact_email" placeholder="Email" name="contact_email" value="">
							</div>
						</div>
						
						<div class="col-sm-12 m-bottom-10 clearfix">
							<label for="contact_number" class="col-sm-3 control-label m-top-5">Contact*</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="contact_number" placeholder="Number" name="contact_number" value="">
							</div>
						</div>
						
						<div class="col-sm-12 m-bottom-10 clearfix hide hidden">
							<label for="contact_company" class="col-sm-3 control-label m-top-5">Company*</label>
							<div class="col-sm-9">
								<select name="contact_company" class="form-control" id="contact_company">
										<option value="">Select Company</option>
										<?php
											foreach ($all_company_list as $row){
											echo '<option value="'.$row->company_name.'">'.$row->company_name.'</option>';
										}?>	
									</select>
							</div>
						</div>
						
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default cancel-contact" data-dismiss="modal">Cancel</button>
				<button type="button" name="add-contact-submit" class="btn btn-success add-contact-submit">Submit</button>
			</div>
		</div>
	</div>
</div>
<script>
    var base_url = '<?php echo site_url(); //you have to load the "url_helper" to use this function ?>';
 
	<?php
	    if(isset($_POST['assigned-contact-impt'])){
    		$last_val_ass = end($assigned_contact_impt) + 1;
	    	echo 'var contactSetCount = '.$last_val_ass.';';
	    }else{
	    	echo "var contactSetCount = 1;";
	    }
    ?>


    <?php
	    if(isset($_POST['add-contact-impt'])){
    		$last_val = end($add_contact_impt) + 1;
	    	echo 'var contactSetAddCount = '.$last_val.';';
	    }else{
	    	echo "var contactSetAddCount = 1;";
	    }
    ?>


    $('.submit_form').click(function(){

    	var target = $(this).attr('id');

    	var is_set_as_primary = 0;
    	$('input.set_as_primary').each(function(index, value){
    		if($(this).is(':checked')){ is_set_as_primary = 1; }    			
    	});


    	if(is_set_as_primary == 1){
    		$('.'+target).submit();
    	}else{
    		$('.dynamic_error_msg').text('Please asssign a contact person.');
    		$('.dynamic_error').modal('show');
    	}

    });

</script>
<?php $this->load->view('assets/logout-modal'); ?>