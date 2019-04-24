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
		<div class="section col-sm-12 col-md-11 col-lg-11">
			<div class="container-fluid">

				<div class="row">
					
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


							<?php if(@$upload_error): ?>
								<div class="pad-10 no-pad-t">
									<div class="border-less-box alert alert-danger fade in">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										<h4>Houston we got a problem!</h4>
										Company Logo: <?php echo $upload_error['error'];?>
									</div>
								</div>
							<?php endif; ?>

							<div class="box-head pad-10 clearfix">
								<label><?php echo $screen; ?></label><span> (<a href="#" data-placement="right" class="popover-test" title="" data-content="Hello there mate! Welcome to the clients screen." data-original-title="Welcome">?</a>)</span>
								<p><a href="#" class="tooltip-test" title="Tooltip">This link</a> and <a href="#" class="tooltip-test" title="Tooltip">that link</a> should have tooltips on hover.</p>								
							</div>
							<div class="box-area pad-10">

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







								<form class="form-horizontal clearfix form focus_add_company" role="form" method="post" action="" accept-charset="utf-8" enctype="multipart/form-data">


									<div class="col-sm-12 clearfix">
										<div class="form-group pad-5 no-pad-b">        								
         									<div class="input-group <?php if(form_error('company_name')){ echo 'has-error has-feedback';} ?>">
												<span class="input-group-addon"><i class="fa fa-briefcase  fa-lg"></i></span>
												<input type="text" class="form-control" placeholder="Company Name*" name="company_name" id="company_name" tabindex="1" value="<?php echo $this->input->post('company_name'); ?>">
											</div>
        								</div>
      								</div>


      								<div class="clearfix"></div>


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
												
												<!--
												<div id="datepicker" class="input-prepend date">
													<span class="add-on"><i class="icon-th"></i></span>
													<input class="span2" type="text" value="02-16-2012">
												</div>
												-->
												
												
												
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

									<div class="clearfix"></div>





      								<div class="box  bank_account" >
										<div class="box-head pad-5 m-bottom-5">
											<label><i class="fa fa-tty fa-lg"></i> More Details</label>
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


											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('contact_number')){ echo 'has-error has-feedback';} ?>">
												<label for="account-name" class="col-sm-3 control-label">Contact Number*</label>
												<div class="col-sm-9">
													<div class="input-group">
														<span class="input-group-addon area-code-text"><?php echo ($this->input->post('areacode') != '' ? $this->input->post('areacode') : '00'); ?></span>
														<input type="text" class="form-control contact_number" id="contact_number" name="contact_number"  onchange="contact_number_assign('contact_number')" tabindex="17" placeholder="Contact Number"  value="<?php echo $this->input->post('contact_number'); ?>">
													</div>													
												</div>
											</div>
											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('email')){ echo 'has-error has-feedback';} ?>">
												<label for="bank-name" class="col-sm-3 control-label">Email*</label>
												<div class="col-sm-9">
													<input type="email" class="form-control" id="email" name="email" placeholder="Email"  tabindex="18"  value="<?php echo $this->input->post('email'); ?>">
												</div>
											</div>
											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
												<label for="account-number" class="col-sm-3 control-label">Mobile Number</label>
												<div class="col-sm-9">
													<input type="text" class="form-control mobile_number" id="mobile_number" name="mobile_number" placeholder="Mobile Number" onchange="mobile_number_assign('mobile_number')"  tabindex="19" value="<?php echo $this->input->post('account-number'); ?>">
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix  <?php if(@$upload_error){ echo 'has-error has-feedback';} ?>  ">
												<label for="account-number" class="col-sm-3 control-label">Company Logo*</label>
												<div class="col-sm-9">
													<input type="file" id="company_logo" name="company_logo" class="form-control company_logo"  />
												</div>
											</div>

										</div>
									</div>

      								<div class="clearfix"></div>


      								<div class="box  bank_account" >
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
									<div class="clearfix"></div>

									<input type="hidden" class="form-control disabled" readonly="readonly" id="areacode" name="areacode" placeholder="Areacode"  value="<?php echo $this->input->post('areacode'); ?>">




									<div class="box">
										<div class="box-head pad-5">
											<label><i class="fa fa-life-ring fa-lg"></i> Jurisdiction</label>
										</div>

										<div class="box-area clearfix m-top-10">
											<div class="col-xs-12 m-bottom-10 clearfix <?php if(form_error('jurisdiction')){ echo 'has-error has-feedback';} ?>">
											<label for="time-half" class="col-sm-2 col-xs-12 control-label" style="font-weight: normal;">Select Multiple States*</label>
											<div class="col-sm-10 col-xs-12">

												<select class="form-control chosen-multi col-sm-12" tabindex="24" id="jurisdiction" name="jurisdiction[]" multiple="multiple">

													<?php

														foreach ($all_aud_states as $row){
															echo '<option value="'.$row->shortname.'|'.$row->name.'|'.$row->phone_area_code.'|'.$row->id.'">'.$row->name.'</option>';
														}

													?>
												
												</select>												
											</div>
											</div>


											<?php
												if($this->input->post('jurisdiction')){
													$jurisdiction = $this->input->post('jurisdiction'); 
													echo '<script type="text/javascript">$(".chosen-multi").val([';
													foreach ($jurisdiction as $key => $value) {														
														echo '"'.$value.'",';
													}
													echo ']).trigger("change");</script>';

												}
											?>


										</div>
									</div>
									



									
								


									<div class="m-top-15 clearfix">
								    	<div>
								        	<button type="button" class="btn btn-success btn-lg submit_form" id="focus_add_company" name="save_bttn" value="Save"><i class="fa fa-floppy-o"></i> Save</button>
								        </div>
								    </div>
								

								<p>&nbsp;</p>


								<?php if(@$default_errors): ?>
									<div class="no-pad-t">
										<div class="border-less-box alert alert-danger fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>Oh snap! You got an error!</h4>
											<?php echo $default_errors;?>
										</div>
									</div>
								<?php endif; ?>


								<?php if(@$this->session->flashdata('update_default')): ?>
									<div class="no-pad-t">
										<div class="border-less-box alert alert-success fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>Cheers!</h4>
											<?php echo $this->session->flashdata('update_default');?>
										</div>
									</div>
								<?php endif; ?>

								

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

<?php $this->load->view('assets/logout-modal'); ?>
<script type="text/javascript">
	$('.submit_form').click(function(){

    	var target = $(this).attr('id');


    	if($('select#jurisdiction').val()){
    		// do something
    		$('.'+target).submit();

		} else {
			$('.dynamic_error_msg').text('Please Complete Form.');
    		$('.dynamic_error').modal('show');
		    // do something else
		}

    });
</script>