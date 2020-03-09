<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>
<?php $this->load->module('bulletin_board'); ?>
<?php
	if($this->session->userdata('company') >= 2 ){

	}else{
		echo '<style type="text/css">.admin_access{ display: none !important;visibility: hidden !important;}</style>';
	}

	$user_id = $this->session->userdata('user_id');
	$onboarding_access = $this->session->userdata('onboarding');
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
					<li <?php if($screen=='Client'){ echo 'class="active"';} ?> >
						<a href="<?php echo base_url(); ?>company" class="btn-small">Clients</a>
					</li>
					<li <?php if($screen=='Contractor'){ echo 'class="active"';} ?> >
						<a href="<?php echo base_url(); ?>company/contractor" class="btn-small">Contractor</a>
					</li>
					<li <?php if($screen=='Supplier'){ echo 'class="active"';} ?> >
						<a href="<?php echo base_url(); ?>company/supplier" class="btn-small">Supplier</a>
					</li>
					<?php if($this->session->userdata('shopping_centre') >= 1 ): ?>
					<li>
						<a href="<?php echo base_url(); ?>shopping_center" class="btn-small"><i class="fa fa-shopping-cart"></i> Shopping Center</a>
					</li>
					<?php endif; ?>

					<?php //if( $this->session->userdata('users') > 1 || $onboarding_access == 1 || $this->session->userdata('is_admin') ==  1): ?>
					<?php if ($onboarding_access == 1): ?>
						<li>
							<a href="<?php echo base_url(); ?>company/onboarding" class="btn-small">Onboarding</a>
						</li>
					<?php endif; ?>

					<?php if($this->session->userdata('company') >= 2): ?>
					<li>
						<a href="#" class="btn-small" data-toggle="modal" data-target="#filter_company"><i class="fa fa-print"></i> Reports</a>
					</li>
					<?php endif; ?> 
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

						<?php //var_dump($this->session->flashdata('upload_logo_success')); ?>

						<?php if(@$this->session->flashdata('upload_logo_success')): ?>

							<div class="">
								<div class="border-less-box alert alert-success fade in">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<h4>Congratulations!</h4>
									<?php echo $this->session->flashdata('upload_logo_success');?>
								</div>
							</div>

						<?php endif; ?>

						<?php if(@$this->session->flashdata('error_logo')): ?>

							<div class="">
								<div class="border-less-box alert alert-danger fade in">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<h4>Uploading Logo Failed!</h4>
									<?php echo $this->session->flashdata('error_logo'); ?>
								</div>
							</div>

						<?php endif; ?>

						<?php if(@$this->session->flashdata('new_company_msg')): ?>

							<div class="">
								<div class="border-less-box alert alert-success fade in">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<h4>Congratulations!</h4>
									<?php echo $this->session->flashdata('new_company_msg');?>
								</div>
							</div>

						<?php endif; ?>

						<?php if(@$this->session->flashdata('duplicate_company_msg')): ?>

						<div class="">
							<div class="border-less-box alert alert-danger fade in">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<h4>Add Company Failed!</h4>
								<?php echo $this->session->flashdata('duplicate_company_msg');?>
							</div>
						</div>

						<?php endif; ?>

						<div class="left-section-box">
							<div class="box-head pad-10 clearfix">

								<?php 

									if ($onboarding_access == 1): 
											if($screen=='Client' || $screen=='Contractor' || $screen=='Supplier'): ?>
												
												<a href="#" class="btn btn-success pull-right" data-toggle="modal" data-target="#sendLink" style="margin-left: 2px;"><i class="fa fa-send"></i>&nbsp; Send Link</a>

											<?php endif;
									endif; 

								?>

								<a href="./company/add" class="btn btn-primary pull-right admin_access"><i class="fa fa-briefcase"></i>&nbsp; Add New</a>
								<label><?php echo $screen; ?> List</label><span> (<a href="#" data-placement="right" class="popover-test" title="" data-content="Hello there mate! Welcome to the clients screen." data-original-title="Welcome">?</a>)</span>
								<p>This is where the companies are listed.</p>
								<p><a href="#" class="tooltip-test" title="Tooltip">This link</a> and <a href="#" class="tooltip-test" title="Tooltip">that link</a> should have tooltips on hover.</p>								
							</div>
							<div class="box-area pad-10">
								<table id="companyTable" class="table table-striped table-bordered" cellspacing="0" width="100%"><thead><tr><th>Company Name</th><th>Location</th><th>Contact Number</th><th>Email</th><th>Insurance Stat</th>

									<?php echo ($comp_type == 1) ? '<th>Logo</th>': ''; ?>

								</tr></thead>
									<tbody>
										<?php echo $this->company->display_company_by_type($comp_type); ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<div class="col-md-3">
						<div class="box">
							<div class="box-head pad-5">
								<label><i class="fa fa-info-circle fa-lg"></i> Information</label>
							</div>
							<div class="box-area pad-5">
								<p>
									The table can be sortable by the header. It has search feature using a sub text or keyword you are searching. Clicking the Name of the company will lead to the Company Details Screen.
								</p>
							</div>
						</div>
					</div>

					<div class="col-md-3">
						<div class="box">
							<div class="box-head pad-5 clearfix">
								<label><i class="fa fa-bar-chart-o fa-lg"></i> Total Companies</label>
								<a href="./company/add" class="btn btn-primary pull-right btn-xs admin_access"><i class="fa fa-briefcase"></i>&nbsp; Add New</a>
							</div>
							<div class="box-area pattern-sandstone pad-5">
								<div id="company"></div>
								<?php // echo $this->company->donut_cart_companies(); ?>
							</div>
						</div>
					</div>
					<!-- Insurance information -->
					<?php if($comp_type == 2): ?>
					<div class="col-md-3">
						<div class="box">
							<div class="box-head pad-5 clearfix">
								<label><i class="fa fa-bar-chart-o fa-lg"></i> Contractors Insurance Status</label>
							</div>
							<div class="box-area pattern-sandstone pad-5">
								<label for="" style = "color: Blue">Complete Insurance: <span id = "ins_complete"><?php echo $complete ?></span></label>
								<button type = "button" class = "btn btn-primary btn-xs pull-right" id = "complete_print">Print List</button>
								<button type = "button" class = "btn btn-success btn-xs pull-right" id = "complete_sort">Sort List</button>
								<br>
								<label for="" style = "color: Red">Incomplete Insurance: <span id = "ins_incomplete"><?php echo $incomplete ?></span></label>
								<button type = "button" class = "btn btn-primary btn-xs pull-right" id = "incomplete_print">Print List</button>
								<button type = "button" class = "btn btn-success btn-xs pull-right" id = "incomplete_sort">Sort List</button>
							</div>
						</div>
					</div>
					<?php endif; ?>
					<!-- Insurance information -->
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
					
				</div>				
			</div>
		</div>
	</div>
</div>





<!-- Modal -->
<div class="modal fade" id="filter_company" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Company Filter</h4>
        <span> Note: <strong>State is required</strong>. The rest, if blank it selects all.</span>
      </div>
      <div class="modal-body clearfix pad-10">

      	<div class="error_area"></div>


      	
      	<div class="">
	      	<div class="input-group m-bottom-10">
	      		<span class="input-group-addon" id="">
	      			Select State *
	      		</span>
	      		<select class="form-control chosen-multi company_state" multiple="multiple"  id="state_b" tabindex="24" name="company_state"  >
	      			<?php
	      			foreach ($all_aud_states as $row){
	      				echo '<option value="'.$row->shortname.'|'.$row->name.'|'.$row->phone_area_code.'|'.$row->id.'">'.$row->name.'</option>';
	      			}?>
	      		</select>
	      	</div>
      	</div>

      	<div class="input-group m-bottom-10">
      		<span class="input-group-addon" id="">
      			Type
      		</span>

      		<select class="chosen chosen_type company_type" id="type" name="company_type" tabindex="-1" title="Type*">														
      			<option value="">Choose a Type...</option>
      			<option value="Client|1" <?php echo ($screen=='Client' ? 'selected="selected"' : false); ?> >Client</option>
      			<option value="Contractor|2" <?php echo ($screen=='Contractor' ? 'selected="selected"' : false); ?> >Contractor</option>
      			<option value="Supplier|3" <?php echo ($screen=='Supplier' ? 'selected="selected"' : false); ?> >Supplier</option>
      		</select>
      	</div>

      	<div class="input-group m-bottom-10">
      		<span class="input-group-addon" id="">
      			Activity
      		</span>      		
      		<select class="activity chosen-multi company_activity" multiple="multiple" id="activity"  name="company_activity"  title="Activity*">
      			<option value="" disabled="disabled">Choose a Activity...</option>																			
      		</select>     	
      	</div>

      	<div class="col-md-6 col-sm-6 col-xs-12 clearfix ">
      		<div class="input-group m-bottom-10">
      			<span class="input-group-addon" id="">
      				<i class="fa fa-sort-alpha-asc"></i> A
      			</span>      		
      			<input type="text" class="form-control letter_segment" id="starting_letter_segment" placeholder="Starting Letter Segment" name="starting_letter_segment" value="">
      		</div>
      	</div>


      	<div class="col-md-6 col-sm-6 col-xs-12 clearfix ">
      		<div class="input-group m-bottom-10">
      			<span class="input-group-addon" id="">
      				<i class="fa fa-sort-alpha-asc"></i> Z
      			</span>      		
      			<input type="text" class="form-control letter_segment" id="end_letter_segment" placeholder="End Letter Segment" name="end_letter_segment" value="">
      		</div>
      	</div>

      	<div class="input-group m-bottom-10">
      		<span class="input-group-addon" id="">
      			<i class="fa fa-sort-amount-asc"></i> Sort
      		</span>      		
      		<select class="company_sort form-control" id="company_sort"  name="company_sort"  title="company_sort*">
      			<option value="cm_asc">Company Name A-Z</option>	
      			<option value="cm_desc">Company Name Z-A</option>
      			<option value="act_asc">Activity A-Z</option>
      			<option value="act_desc">Activity Z-A</option>
      			<option value="sub_asc">Suburb A-Z</option>	
      			<option value="sub_desc">Suburb Z-A</option>		
      			<option value="state_asc">State A-Z</option>	
      			<option value="state_desc">State Z-A</option>																			
      		</select>     	
      	</div>

        <div class="pull-right">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary company_filter_submit">Submit</button>
        </div>

      </div>
    </div>
  </div>
</div>



<!-- Insurance Modal -->
<div class="modal fade" id="contractor_list" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
	    <div class="modal-content">
	    	<form action="<?php echo base_url(); ?>company/upload_insurance" method="post" enctype="multipart/form-data">
		        <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			        <h4 class="modal-title insurance_title">Contractors List (<span id = "contractorlist_modal_title"></span>)</h4>
		        </div>
		        <div class="modal-body" style = "height: 500px">
		        	Search: <input type="text" class = "form-control">
		        	<br>
		        	<div id = "contractor_list_filtered" style = "height: 400px; overflow: auto"></div>
				</div>
				<div class="modal-footer">
				   	<button type = "submit" class="btn btn-primary">Print</button>
				   	<button type="button" class="btn btn-default" id = "update_estimate_no" data-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Insurance Modal -->
<div class="report_result hide hidden"></div>

<!-- Company Logo Modal -->
<div class="modal fade" id="companyLogoUpload" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
	    <div class="modal-content">
	    	<form action="<?php echo base_url(); ?>company/upload_logo" method="post" enctype="multipart/form-data">
		        <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			        <h4 class="modal-title company_logo_title"></h4>
		        </div>
		        <div class="modal-body" style = "height: 350px">
		        	<input type="hidden" id="company_id" class="company_id" name="company_id" value="">

		        	<center>
			        	<input type="file" id="logoUploader" name="userfile" />
			        	<br>
			        	<img src="<?php echo base_url(); ?>img/progress_report/resources/noimage.png" id="logoPreview" width="300" class="img-responsive">
			        </center>
				</div>
				<div class="modal-footer">
				   	<button type="button" class="btn btn-danger" data-dismiss="modal">Back</button>
				   	<button type="submit" id="logoUpload_btn" class="btn btn-primary" disabled="disabled">Upload</button>
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Company Logo Modal -->

<!-- Company Logo Modal -->
<div class="modal fade" id="companyLogoDisplay" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
	    <div class="modal-content">
	    	<form action="<?php echo base_url(); ?>company/upload_logo" method="post" enctype="multipart/form-data">
		        <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			        <h4 class="modal-title company_logo_title"></h4>
		        </div>
		        <div class="modal-body">
		        	<input type="hidden" class="company_id" name="company_id" value="">

		        	<!-- <center>
			        	<input type="file" name="userfile" size="20" />
			        </center> -->

			        <center>
				        <img src="" id="logoPath" class="img-responsive">
				    </center>

				</div>
				<div class="modal-footer">
				   	<button type="button" class="btn btn-danger" data-dismiss="modal">Back</button>
				   	<button type="button" id="showUploadLogo" class="btn btn-primary">Change</button>
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Company Logo Modal -->

<!-- Send Link Modal -->
<div class="modal fade" id="sendLink" tabindex="-1" role="dialog" aria-labelledby="sendLinkLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

				<?php if($screen=='Client'): ?>
					<h4 class="modal-title" id="sendLinkLabel">Send Registration Link for New Client</h4>
					<input type="hidden" class="form-control" id="onboarding_type" name="onboarding_type" value="1">
				<?php endif; ?>

				<?php if($screen=='Contractor'): ?>
					<h4 class="modal-title" id="sendLinkLabel">Send Registration Link for New Contractor</h4>
					<input type="hidden" class="form-control" id="onboarding_type" name="onboarding_type" value="2">
				<?php endif; ?>

				<?php if($screen=='Supplier'): ?>
					<h4 class="modal-title" id="sendLinkLabel">Send Registration Link for New Supplier</h4>						
					<input type="hidden" class="form-control" id="onboarding_type" name="onboarding_type" value="3">
				<?php endif; ?>

			</div>
			<div class="modal-body">
				
				<div class="row">
					<div class="col-sm-12">
						<div class="input-group">
						  <span class="input-group-addon">Send to*: </span>
						  <input type="text" class="form-control" id="onboarding_email_address" name="onboarding_email_address" placeholder="Email Address">
						</div>
					</div>
				</div>
				
				<div class="clearfix"><br></div>

				<div class="row">
					<div class="col-sm-6">
						<div class="input-group">
						  <span class="input-group-addon">First Name*: </span>
						  <input type="text" class="form-control" id="onboarding_first_name" name="onboarding_first_name" placeholder="First Name" style="text-transform: capitalize;">
						</div>
					</div>

					<div class="col-sm-6">
						<div class="input-group">
						  <span class="input-group-addon">Last Name*: </span>
						  <input type="text" class="form-control" id="onboarding_last_name" name="onboarding_last_name" placeholder="Last Name" style="text-transform: capitalize;">
					  	</div>
					</div>

				</div>

				<div class="clearfix"><br></div>

				<?php if($screen=='Client'): ?>

						<div class="row">
							<div class="col-sm-12">
								<div class="input-group">
								  <span class="input-group-addon">Subject*: </span>
								  <input type="text" class="form-control" id="onboarding_subject" name="onboarding_subject" value="<?php echo $onboarding_subject_clients; ?>" style="text-transform: capitalize;">
								</div>
							</div>
						</div>

				<?php else: ?>

						<div class="row">
							<div class="col-sm-12">
								<div class="input-group">
								  <span class="input-group-addon">Subject*: </span>
								  <input type="text" class="form-control" id="onboarding_subject" name="onboarding_subject" value="<?php echo $onboarding_subject; ?>" style="text-transform: capitalize;">
								</div>
							</div>
						</div>

				<?php endif; ?>

				<div class="clearfix"><br></div>

				<?php if($screen=='Client'): ?>

						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
								    <textarea class="form-control" id="onboarding_message" name="onboarding_message" rows="10"><?php echo $onboarding_default_message_clients; ?></textarea>
								</div>
							</div>
						</div>

				<?php else: ?>

						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
								    <textarea class="form-control" id="onboarding_message" name="onboarding_message" rows="10"><?php echo $onboarding_default_message; ?></textarea>
								</div>
							</div>
						</div>

				<?php endif; ?>

				<div class="clearfix"></div>

				<div class="col-sm-12 text-center">
					<strong><p><i class="fa fa-quote-left"></i> Link is automatically attached on the last line of the message. <i class="fa fa-quote-right"></i></p></strong>
				</div>

				<div class="clearfix"></div>

			</div>
			<div class="modal-footer" style="margin-top: 0;">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-success" id="onboarding_send">Send</button>
			</div>
		</div>
	</div>
</div>
<!-- Send Link Modal -->

<?php $this->bulletin_board->list_latest_post(); ?>
<?php $this->load->view('assets/logout-modal'); ?>

<script type="text/javascript">
	
	$("#showUploadLogo").click(function(){
		$("#companyLogoDisplay").modal('hide');
		$("#companyLogoUpload").modal('show');

		$('#logoPreview').removeAttr('style');
		$('#logoPreview').attr('src', '<?php echo base_url(); ?>img/progress_report/resources/noimage.png');
	});

	$('#companyLogoUpload').on('shown.bs.modal', function (e) {

		$("#logoUploader").change(function() {
		  readURL(this);
		});

	  	function readURL(input) {

			if (input.files && input.files[0]) {
				var reader = new FileReader();

				reader.onload = function(e) {
					$('#logoPreview').attr('src', e.target.result);
					$('#logoPreview').attr('style', 'width: auto; height: 80%;');
					$('#logoUpload_btn').removeAttr('disabled');
				}

				reader.readAsDataURL(input.files[0]);
			}
		}
	});

	$("#onboarding_send").click(function(){

		var onboarding_email_address = $('#onboarding_email_address').val();
		var onboarding_first_name = $('#onboarding_first_name').val();
		var onboarding_last_name = $('#onboarding_last_name').val();
		var onboarding_subject = $('#onboarding_subject').val();
		var onboarding_message = $('#onboarding_message').val();
		var onboarding_type = $('#onboarding_type').val();

		if (onboarding_email_address != '' && onboarding_first_name != '' && onboarding_last_name != '' && onboarding_subject != '' && onboarding_message != ''){
			var data = onboarding_email_address+'|'+onboarding_first_name+'|'+onboarding_last_name+'|'+onboarding_subject+'|'+onboarding_message+'|'+onboarding_type;

			$.ajax({
				type: 'POST',
			  	url: '<?php echo base_url().'company/onboarding_send'; ?>',
			  	data: {'ajax_var' : data  },
		      	dataType: 'json', 
		      	success: function (response) {

		      		alert(response.status);
		      	}
			});
			
		} else {
			$('#confirmModal').css('z-index', '9999');
			$('h4#myModalLabel.modal-title.msgbox').html("Information:");
			$('#confirmText').text('Please fill up all the required (*) fields');
			$('#confirmButtons').html('<button type="button" class="btn btn-success" data-dismiss="modal">Okay</button>');
		    $('#confirmModal').modal('show');
		}
	});

</script>