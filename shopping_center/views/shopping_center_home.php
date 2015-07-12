<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>
<?php $this->load->module('projects'); ?>
<?php $this->load->module('shopping_center'); ?>
<!-- title bar -->
<div class="container-fluid head-control">
	<div class="container-fluid">
		<div class="row">

			<div class="col-md-6 col-sm-4 col-xs-12 pull-left">
				<header class="page-header">
					<h3><?php $datestring = "%l, %F %d, %Y"; $time = time(); //use time() for timestamp  ?>
						<?php echo $screen; ?><br><small><?php echo mdate($datestring, $time); #echo date("l, F d, Y"); ?></small>
					</h3>
				</header>
			</div>

			<div class="page-nav-options col-md-6 col-sm-8 col-xs-12 pull-right hidden-xs">
				<ul class="nav nav-tabs navbar-right">
					<li>
						<a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Home</a>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>company" class="btn-small">Clients</a>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>company/contractor" class="btn-small">Contractor</a>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>company/supplier" class="btn-small">Supplier</a>
					</li>
					<li class="active">
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
						<div class="<?php echo ($this->session->userdata('projects') >= 2 ? 'col-md-9' : 'col-sm-12'); ?>">
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

								<?php if(@$this->session->flashdata('success_add')): ?>
									<div class="pad-10 no-pad-t">
										<div class="border-less-box alert alert-success fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>Cheers!</h4>
											<?php echo $this->session->flashdata('success_add');?>
										</div>
									</div>
								<?php endif; ?>

								<?php if(@$this->session->flashdata('success_remove')): ?>
									<div class="pad-10 no-pad-t">
										<div class="border-less-box alert alert-danger fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>I hope you did the right thing.</h4>
											<?php echo $this->session->flashdata('success_remove');?>
										</div>
									</div>
								<?php endif; ?>								


								<div class="box-head pad-10 clearfix">
									<label><?php echo $screen; ?> List</label><span> (<a href="#" data-placement="right" class="popover-test" title="" data-content="Hello there mate! Welcome to the clients screen." data-original-title="Welcome">?</a>)</span>
									<p>This is where the shopping centers are listed.</p>						
								</div>
								<div class="box-area pad-10">
									<table id="companyTable" class="table table-striped table-bordered" cellspacing="0" width="100%"><thead><tr><th>Brand/Shopping Centre Group</th><th>Street</th><th>Suburb</th><th>State</th></tr></thead>
										<tbody>
											<?php echo $this->shopping_center->display_shopping_center(); ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						

<?php if($this->session->userdata('projects') >= 2 ): ?>

						<div class="col-md-3">

							<div class="box add-shopping-center">
								<div class="box-head pad-5">
									<label><i class="fa fa-cart-plus fa-lg"></i> Add Shopping Center</label>
								</div>

								<div class="box-area clearfix pad-5">
									<form class="form-horizontal add-shopping" role="form" method="post"  action="">



										<div class="clearfix  m-bottom-10 <?php if(form_error('brand')){ echo 'has-error has-feedback';} ?>">
											<label for="brand" class="col-sm-12 control-label text-left" style="font-weight: normal;">Brand/Shopping Center*</label>
											<div class="col-sm-12">
												<input type="text" class="form-control m-top-10" id="brand" name="brand" tabindex="1" placeholder="Brand/Shopping center" value="<?php echo $this->input->post('brand'); ?>">
											</div>
										</div>

										<div class="clearfix m-top-10 m-bottom-10">
											<label for="street-number" class="col-sm-4 control-label text-left" style="font-weight: normal;">Street Number</label>
											<div class="col-sm-8">
												<input type="text" class="form-control" id="street-number" name="street_number" tabindex="2" placeholder="Street Number" value="<?php echo $this->input->post('street_number'); ?>">
											</div>
										</div>

										<div class="clearfix m-top-10 m-bottom-10 <?php if(form_error('street')){ echo 'has-error has-feedback';} ?>">
											<label for="street" class="col-sm-4 control-label text-left" style="font-weight: normal;">Street*</label>
											<div class="col-sm-8">
												<input type="text" class="form-control" id="street" name="street" tabindex="3" placeholder="Street" value="<?php echo $this->input->post('street'); ?>">
											</div>
										</div>

										<input type="hidden" name="is_submit" value="1">

										<div class="clearfix m-top-10 m-bottom-10 <?php if(form_error('state_a')){ echo 'has-error has-feedback';} ?>">
											<label for="state_a" class="col-sm-4 control-label text-left" style="font-weight: normal;">State*</label>
											<div class="col-sm-8">
												
												<select class="form-control state-option-a chosen"  tabindex="4" id="state_a" name="state_a">															
													<option value="">Choose a State</option>
													<?php
													foreach ($all_aud_states as $row){
														echo '<option value="'.$row->shortname.'|'.$row->name.'|'.$row->phone_area_code.'|'.$row->id.'">'.$row->name.'</option>';
													}?>
												</select>
												<script type="text/javascript">$("select#state_a").val("<?php echo $this->input->post('state_a'); ?>");</script>



											</div>
										</div>

										<div class="clearfix m-top-10 m-bottom-10 <?php if(form_error('suburb_a')){ echo 'has-error has-feedback';} ?>">
											<label for="suburb_a" class="col-sm-4 control-label text-left" style="font-weight: normal;">Suburb*</label>
											<div class="col-sm-8">
												
												<?php if($this->input->post('suburb_a')): ?>
													<select class="form-control suburb-option-a chosen" id="suburb_a" name="suburb_a">

													<?php else: ?>
														<select class="form-control suburb-option-a disabled chosen"  tabindex="5"  id="suburb_a" name="suburb_a">
															<option value="">Choose a Suburb...</option>

														<?php endif; ?>

														

														<?php if($this->input->post('suburb_a')): ?>
															<?php $this->company->get_suburb_list('dropdown|state_id|'.$this->input->post('state_a')); ?>														
															<script type="text/javascript">$("select#suburb_a").val("<?php echo $this->input->post('suburb_a'); ?>");</script>

														<?php endif; ?>

													</select>


											</div>
										</div>

										<div class="clearfix m-top-10 m-bottom-10 <?php if(form_error('postcode_a')){ echo 'has-error has-feedback';} ?>">
											<label for="postcode_a" class="col-sm-4 control-label text-left" style="font-weight: normal;">Postcode*</label>
											<div class="col-sm-8">
												
												<?php if($this->input->post('postcode_a')): ?>
													<select class="form-control postcode-option-a chosen" id="postcode_a"  tabindex="6" name="postcode_a">
													<?php else: ?>
														<select class="form-control postcode-option-a disabled chosen"   tabindex="6"  id="postcode_a" name="postcode_a">
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

										<div onclick="$('form.add-shopping').submit();" class="btn btn-success m-10 pull-right"><i class="fa fa-cart-plus fa-lg"></i> Save</div>
									</form>
								</div>
							</div>


							<div class="box edit-shopping-center" style="display:none;">
								<div class="box-head pad-5">
									<label><i class="fa fa-cart-plus fa-lg"></i> Edit Shopping Center</label>
								</div>

								<div class="box-area clearfix pad-5">
									<form class="form-horizontal update-shopping" role="form" method="post"  action="shopping_center/update">
										<input type="hidden" name="shopping_center_id" id="shopping_center_id" value="">

										<div class="clearfix  m-bottom-10">
											<label for="edit_brand" class="col-sm-12 control-label text-left" style="font-weight: normal;">Brand/Shopping Center*</label>
											<div class="col-sm-12">
												<input type="text" class="form-control m-top-10" id="edit_brand" name="edit_brand" tabindex="7" placeholder="Brand/Shopping center" value="">
											</div>
										</div>

										<div class="clearfix m-top-10 m-bottom-10">
											<label for="edit_street_number" class="col-sm-4 control-label text-left" style="font-weight: normal;">Street Number*</label>
											<div class="col-sm-8">
												<input type="text" class="form-control" id="edit_street_number" name="edit_street_number" tabindex="8" placeholder="Street Number" value="">
											</div>
										</div>

										<div class="clearfix m-top-10 m-bottom-10 ">
											<label for="edit_street" class="col-sm-4 control-label text-left" style="font-weight: normal;">Street*</label>
											<div class="col-sm-8">
												<input type="text" class="form-control" id="edit_street" name="edit_street" placeholder="Street" tabindex="9" value="<?php echo $this->input->post('street'); ?>">
											</div>
										</div>

										<input type="hidden" name="is_submit" value="1">

										<div class="clearfix m-top-10 m-bottom-10">
											<label for="edit_state_a" class="col-sm-4 control-label text-left" style="font-weight: normal;">State*</label>
											<div class="col-sm-8">
												<select class="form-control state-option-b chosen edit_state_b"  id="state_b" tabindex="10" name="edit_state_a">															
													<option value="">Choose a State</option>
													<?php
													foreach ($all_aud_states as $row){
														echo '<option value="'.$row->shortname.'|'.$row->name.'|'.$row->phone_area_code.'|'.$row->id.'">'.$row->name.'</option>';
													}?>
												</select>
											</div>
										</div>

										<div class="clearfix m-top-10 m-bottom-10">
											<label for="edit_suburb_a" class="col-sm-4 control-label text-left" style="font-weight: normal;">Suburb*</label>
											<div class="col-sm-8">
												<select class="form-control suburb-option-b chosen edit_suburb_a" id="suburb_b" tabindex="11" name="edit_suburb_a">
													<?php //$this->company->get_suburb_list('dropdown|state_id|'.$suburb.'|'.$name.'|'.$phone_area_code.'|'.$state_id); ?>
												</select>
											</div>
										</div>

										<div class="clearfix m-top-10 m-bottom-10">
											<label for="edit_postcode_b" class="col-sm-4 control-label text-left" style="font-weight: normal;">Postcode*</label>
											<div class="col-sm-8">												
												<select class="form-control postcode-option-b chosen" id="postcode_b" tabindex="12" name="edit_postcode_b">
													<?php //$this->company->get_post_code_list($suburb); ?>																	
												</select>
											</div>
										</div>
										<div onclick="$('form.update-shopping').submit();" class="btn btn-warning m-10 pull-left"><i class="fa fa-cart-plus fa-lg"></i> Update</div>

										<a href="shopping_center/delete/" id="" class="btn btn-danger m-10 pull-left delete-shopping"><i class="fa fa-trash fa-lg"></i> Delete</a>

										<div onclick="$('.edit-shopping-center').hide(); $('.add-shopping-center').show();" class="btn btn-info m-10 pull-right"><i class="fa fa-times fa-lg"></i> Cancel</div>
									</form>
								</div>
							</div>



						</div>
<?php endif; ?>
						
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
						<div class="col-sm-12 m-bottom-10 clearfix">
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
						<p><hr style="display: block; border: 1px solid #E5E5E5; border-bottom:0;" class="m-top-15"  /></p>
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
							<label for="cotact_email" class="col-sm-2 control-label m-top-5">Email*</label>
							<div class="col-sm-10">
								<input type="email" class="form-control" id="cotact_email" placeholder="Email" name="cotact_email" value="">
							</div>
						</div>
						
						<div class="col-sm-12 m-bottom-10 clearfix">
							<label for="contact_number" class="col-sm-3 control-label m-top-5">Contact*</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="contact_number" placeholder="Number" name="contact_number" value="">
							</div>
						</div>
						
						<div class="col-sm-12 m-bottom-10 clearfix">
							<label for="contact_company" class="col-sm-3 control-label m-top-5">Company*</label>
							<div class="col-sm-9">
								<select name="contact_company" class="form-control" id="contactperson">
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
				<button type="submit" name="add-contact-submit" class="btn btn-success add-contact-submit">Submit</button>
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
						<div class="col-sm-12 m-bottom-10 clearfix">
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
						<p><hr style="display: block; border: 1px solid #E5E5E5; border-bottom:0;" class="m-top-15"  /></p>
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
							<label for="cotact_email" class="col-sm-2 control-label m-top-5">Email*</label>
							<div class="col-sm-10">
								<input type="email" class="form-control" id="cotact_email" placeholder="Email" name="cotact_email" value="">
							</div>
						</div>
						
						<div class="col-sm-12 m-bottom-10 clearfix">
							<label for="contact_number" class="col-sm-3 control-label m-top-5">Contact*</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="contact_number" placeholder="Number" name="contact_number" value="">
							</div>
						</div>
						
						<div class="col-sm-12 m-bottom-10 clearfix">
							<label for="contact_company" class="col-sm-3 control-label m-top-5">Company*</label>
							<div class="col-sm-9">
								<select name="contact_company" class="form-control" id="contactperson">
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
				<button type="submit" name="add-contact-submit" class="btn btn-success add-contact-submit">Submit</button>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('assets/logout-modal'); ?>
<script type="text/javascript">
	<?php echo ($this->input->post('is_form_submit') ? "" : "$('#project_name').focus();" ); ?>
	$('#edit_brand').focus();
</script>