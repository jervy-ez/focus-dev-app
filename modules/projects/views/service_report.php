<?php 
	date_default_timezone_set("Australia/Perth");  
	$user_prepared = ucfirst($this->session->userdata('user_first_name')).' '.ucfirst($this->session->userdata('user_last_name'));
 ?>	

 <style type="text/css">
 	.service_report_wrap label {
 		font-weight: normal;
 	}
 	#sidebar label {
 		font-weight: bold !important;
 	}
	.overlay i:hover {
		color: #c12e2a !important;
	}

	.select-report-image .nopad {
	padding-left: 0 !important;
	padding-right: 0 !important;
	}
	/*image gallery*/
	.select-report-image .image-checkbox {
		cursor: pointer;
		box-sizing: border-box;
		-moz-box-sizing: border-box;
		-webkit-box-sizing: border-box;
		margin-bottom: 0;
		outline: 0;
	}
	.select-report-image .image-checkbox input[type="checkbox"] {
		display: none;
	}

	.select-report-image .image-checkbox-checked {
		border-color: #4783B0;
	}
	.select-report-image .image-checkbox .fa {

	  position: absolute;
	  color:  #4A79A3;
	  background-color: #fff;
	  padding: 10px;
	  top: 0px;
	  right: 5px;
	}
	.select-report-image .image-checkbox-checked .fa {
	  display: block;
	}

	.bspHasModal{
		padding: 0 !important;
		/*margin-top: 30px;*/
		margin-bottom: 70px;
	}

	.imgWrapper{
		height: 90px !important;
		margin-right: 5px;

	}

	#imgLabel > p.pText {
		text-transform: capitalize;
	}

	#lblImage {
		text-transform: capitalize;
	}

	ul.first p.text {
		text-transform: capitalize;
		font-size: 1.3rem;
		line-height: 1.7;
	}

	#confirmModal {
	    z-index: 9999 !important;
	}

	#site_inspection_checkbox:hover, #completion_checkbox {
		cursor: pointer;
	}

	.si_char_count_wrap, .completion_char_count_wrap {
		float: right;
		font-size: 16px;
		padding: 5px;
	}

	#si_char_count, #completion_char_count {
		background: transparent;
		font-weight: bold;
		border: none;
	}

	button#btnGenerate_SR_pdf, button#btnSend_SR_pdf, form#frmSiteInspection > span.btn, form#frmCompletion > span.btn  {
		font-size: 12px;
		height: 35px;
	}

	form#frmSiteInspection > span.btn, form#frmCompletion > span.btn  {
		line-height: 22px;
	}


</style>

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
						<a href="<?php echo base_url(); ?>projects/view/<?php echo $project_id ?>"><i class="fa fa-home"></i> Project Details</a>
					</li>
				</ul>
			</div>

		</div>
	</div>
</div>
<!-- title bar -->

	<div class="container-fluid service_report_wrap">
		<!-- Example row of columns -->
		<div class="row">				
			<?php $this->load->view('assets/sidebar'); ?>
			<div class="section col-sm-12 col-md-11 col-lg-11">
				<form target="_blank" id="generate_sr_report" class="form-horizontal" role="form" method="post" action="<?php echo base_url(); ?>projects/service_report_pdf">
					<div class="container-fluid">
						<div class="row">
							<div class="col-md-9">
								<div class="left-section-box">			

									<input type="hidden" id="project_id" name="project_id" value="<?php echo $project_id; ?>">
									<input type="hidden" id="user_full_name" name="user_full_name" value="<?php echo $user_prepared ?>">
									<input type="hidden" id="end_user_email" name="end_user_email" value="<?php echo $end_user_email; ?>">
									
									<input type="hidden" id="project_name_footer" name="project_name_footer" value="<?php echo $project_id.' '.$client_company_name; ?>">
									<input type="hidden" id="project_suburb_footer" name="project_suburb_footer" value="<?php echo $suburb; ?>">

									<input type="hidden" id="po_client" name="po_client" value="<?php echo $po_client; ?>">
						
									<div class="box-head pad-10 clearfix">
										<label>Generate Service Report</label>
										<span> (<a href="#" data-placement="right" class="popover-test" title="" data-content="Hello there mate! Welcome to the service report screen." data-original-title="Welcome">?</a>)</span>
										<p>Fields having * is requred.</p>	
										<p><a href="#" class="tooltip-test" title="Tooltip">This link</a> and <a href="#" class="tooltip-test" title="Tooltip">that link</a> should have tooltips on hover.</p>
									</div>

									<div class="box-area pad-10 clearfix">	

										
										<div class="box m-bottom-15 clearfix">
											<div class="box-head pad-5 m-bottom-5">
												<label><i class="fa fa-gear fa-lg"></i> Service Report Details - <span class="label label-danger">Project No. <?php echo $project_id; ?></span></label>
											</div>
											
											<div class="box-area pad-5 clearfix">
												<div class="col-md-12">
													<div class="pad-15 no-pad-t">	

														<div class="col-md-6">
															<span class="text-left">Project Name:</span>
															<h3 style="margin-left: 20px;"><label for="project_name" class="text-left"><?php echo $project_name; ?></label></h3>
															<input type="hidden" id="project_name" name="project_name" value="<?php echo $project_name; ?>">
														</div>

														<div class="col-md-6">
															<span class="text-left">Client:</span>
															<h3 style="margin-left: 20px;"><label for="client_company_name" class="text-left"><?php echo $client_company_name; ?></label></h3>
															<input type="hidden" id="client_company_name" name="client_company_name" value="<?php echo $client_company_name; ?>">
															<input type="hidden" id="client_company_logo_path" name="client_company_logo_path" value="<?php echo $client_company_logo_path; ?>">
														</div>

														<div class="clearfix"></div>

														<h4><i class="fa fa-map-marker"></i> Site Address:</h4>
														<?php $shop_tenancy_numb = ($job_type != 'Shopping Center' ? '' : ''.$shopping_common_name.': '.$shop_tenancy_number); ?>
														<?php $unit_level =  ($unit_level != '' ? 'Unit/Level:'.$unit_level.',' : '' ); ?>
														<h5 style="margin-left: 20px;"><p id="site_address1"><?php echo "$shop_tenancy_numb $unit_level $unit_number $street, $suburb, $state, $postcode"; ?></p></h5>
														<input type="hidden" id="site_address1" name="site_address1" value="<?php echo "$shop_tenancy_numb $unit_level $unit_number $street, $suburb, $state, $postcode"; ?>">
														
														<div class="clearfix"><br></div>

														<h3><i class="fa fa-search fa-lg"></i> Site Inspection: <small><label id="site_inspection_checkbox"><input type="checkbox" id="site_inspection_include" name="site_inspection_include" checked> (uncheck if you want to exclude this area in the Service Report PDF.)</label></small></h3> 

														<div class="clearfix"><br></div>

														<div class="select-report-image">
															<ul class="first" style="padding: 0px;">
															    
															    <?php 
															    	if (empty($sr_images_si)){
																	  	echo '<h3 class="text-danger">No recent uploaded images...</h3>';
																	} else {

																		foreach ($sr_images_si as $row):
																?>

																	<input type="hidden" id="<?php echo preg_replace('/\\.[^.\\s]{3,4}$/', '', basename($row['image_path'])); ?>_group" name="group_selected" value="<?php echo ($row['group_id'] == NULL ? '0' : $row['group_id']); ?>">
																	
																	<li id="image_report_<?php echo $row['id']; ?>"> <!-- onclick="set_select('<?php //echo $row['id']; ?>');" -->

																		<div id="<?php echo $row['id']; ?>" class="overlay delete_image_list" style="display: block; position: absolute; left: 0; top: 95px; z-index: 500">
																			<!-- <i class="fa fa-trash" style="color: #d9534f; font-size: 1.3rem;"></i> -->
																			[<span style="color: #a94442"> Delete </span>]
																		</div>

																			<div class="nopad text-center">
						 															
						 															<div><!-- start -->

						 																<div class="<?php echo preg_replace('/\\.[^.\\s]{3,4}$/', '', basename($row['image_path'])); ?> edit_screen" style="display: block; width:100%; position: absolute;z-index: 100;height: 100%;text-align: center;font-weight: bold;color: #fff;">
						 																	<span style="margin-top: 39px;display: block;font-size: 1.5rem; <?php echo ($row['is_edited'] == 0 ? 'color: #ea6503;' : 'color: #71657f;'); ?> background: #fff;padding: 2px;">CLICK TO VIEW</span>
						 																</div>

					 																	<label class="image-checkbox <?php echo ($row['is_select'] == '1') ? 'image-checkbox-checked' : ''; ?>">

					 																		<div class="select_screen" style="display: none; width:100%; position: absolute;z-index: 100;height: 100%;text-align: center;font-weight: bold;color: #fff;">
							 																	<span style="margin-top: 39px;display: block;font-size: 1.5rem;color: #245580;background: #fff;padding: 2px;">CLICK TO SELECT</span>
							 																</div>

							 																<img class="img-responsive" src="<?php echo base_url().$row['image_path'].'?'.strtotime("now"); ?>"/>
							 																
							 																<!-- <strong><p class="<?php //echo preg_replace('/\\.[^.\\s]{3,4}$/', '', basename($row['image_path'])); ?> text"><?php //echo !empty($row['image_label']) ? $row['image_label'] : '(No Image Label)'; ?></p></strong> -->

							 																<strong><p class="<?php echo preg_replace('/\\.[^.\\s]{3,4}$/', '', basename($row['image_path'])); ?> text"><?php echo !empty($row['image_label']) ? $row['image_label'] : ''; ?></p></strong>

						 																	<input type="checkbox" name="image[]" value="" <?php echo ($row['is_select'] == '1') ? 'checked="1"' : ''; ?> />
																			      			<i class="fa fa-check <?php echo ($row['is_select'] == '1') ? '' : 'hidden'; ?>"></i>

						 																</label>

						 															</div><!-- end -->

																			</div>

																	</li>
																<?php 
																    	endforeach;
																	}
															    ?>
															   	
															</ul>
														</div>

														<div class="clearfix"><br></div>


														<!-- <div class="col-md-9"> -->
															<div class="box">
																<div class="box-head pad-5">

																	<label for="project_notes"><i class="fa fa-pencil-square-o fa-lg"></i> Site Inspection Details</label>

																	<div class="si_char_count_wrap"><input type="text" id="si_char_count" class="text-right" name="si_char_count" size="3" maxlength="3" readonly> character remaining</div>
																</div>
																
																<div class="box-area pad-5 clearfix">
																	<div class="clearfix <?php //if(form_error('generalEmail')){ echo 'has-error has-feedback';} ?>">
																		
																			<textarea class="form-control" id="site_inspection_details" rows="7" tabindex="1" name="site_inspection_details" onchange="textCounter(this.form.site_inspection_details, this.form.si_char_count);" onkeydown="textCounter(this.form.site_inspection_details, this.form.si_char_count);" onkeyup="textCounter(this.form.site_inspection_details, this.form.si_char_count);"><?php echo isset($sr_details->site_inspection_details) ? $sr_details->site_inspection_details : ''; ?></textarea>														
																		
																	</div>
																</div>
															</div>
														<!-- </div> -->

														<div class="clearfix"><br></div>

														<h3><i class="fa fa-check-circle fa-lg"></i> Completion: <small><label id="completion_checkbox"><input type="checkbox" id="completion_include" name="completion_include" checked> (uncheck if you want to exclude this area in the Service Report PDF.)</label></small></h3>

														<div class="clearfix"><br></div>

														<div class="select-report-image">
															<ul class="first" style="padding: 0px;">
															    
															    <?php 
															    	if (empty($sr_images_comp)){
																	  	echo '<h3 class="text-danger">No recent uploaded images...</h3>';
																	} else {

																		foreach ($sr_images_comp as $row):
																?>

																	<input type="hidden" id="<?php echo preg_replace('/\\.[^.\\s]{3,4}$/', '', basename($row['image_path'])); ?>_group" name="group_selected" value="<?php echo ($row['group_id'] == NULL ? '0' : $row['group_id']); ?>">
																	
																	<li id="image_report_<?php echo $row['id']; ?>"> <!-- onclick="set_select('<?php //echo $row['id']; ?>');" -->

																		<div id="<?php echo $row['id']; ?>" class="overlay delete_image_list" style="display: block; position: absolute; left: 0; top: 95px; z-index: 500">
																			<!-- <i class="fa fa-trash" style="color: #d9534f; font-size: 1.3rem;"></i> -->
																			[<span style="color: #a94442"> Delete </span>]
																		</div>

																			<div class="nopad text-center">
						 															
						 															<div><!-- start -->

						 																<div class="<?php echo preg_replace('/\\.[^.\\s]{3,4}$/', '', basename($row['image_path'])); ?> edit_screen" style="display: block; width:100%; position: absolute;z-index: 100;height: 100%;text-align: center;font-weight: bold;color: #fff;">
						 																	<span style="margin-top: 39px;display: block;font-size: 1.5rem; <?php echo ($row['is_edited'] == 0 ? 'color: #ea6503;' : 'color: #71657f;'); ?> background: #fff;padding: 2px;">CLICK TO VIEW</span>
						 																</div>

					 																	<label class="image-checkbox <?php echo ($row['is_select'] == '1') ? 'image-checkbox-checked' : ''; ?>">

					 																		<div class="select_screen" style="display: none; width:100%; position: absolute;z-index: 100;height: 100%;text-align: center;font-weight: bold;color: #fff;">
							 																	<span style="margin-top: 39px;display: block;font-size: 1.5rem;color: #245580;background: #fff;padding: 2px;">CLICK TO SELECT</span>
							 																</div>

							 																<img class="img-responsive" src="<?php echo base_url().$row['image_path'].'?'.strtotime("now"); ?>"/>
							 																
							 																<!-- <strong><p class="<?php //echo preg_replace('/\\.[^.\\s]{3,4}$/', '', basename($row['image_path'])); ?> text"><?php //echo !empty($row['image_label']) ? $row['image_label'] : '(No Image Label)'; ?></p></strong> -->

							 																<strong><p class="<?php echo preg_replace('/\\.[^.\\s]{3,4}$/', '', basename($row['image_path'])); ?> text"><?php echo !empty($row['image_label']) ? $row['image_label'] : ''; ?></p></strong>

						 																	<input type="checkbox" name="image[]" value="" <?php echo ($row['is_select'] == '1') ? 'checked="1"' : ''; ?> />
																			      			<i class="fa fa-check <?php echo ($row['is_select'] == '1') ? '' : 'hidden'; ?>"></i>

						 																</label>

						 															</div><!-- end -->

																			</div>

																	</li>
																<?php 
																    	endforeach;
																	}
															    ?>
															   	
															</ul>
														</div>

														<div class="clearfix"><br></div>

														<!-- <div class="col-md-9"> -->
															<div class="box">
																<div class="box-head pad-5">

																	<label for="project_notes"><i class="fa fa-pencil-square-o fa-lg"></i> Completion Details</label>

																	<div class="completion_char_count_wrap"><input type="text" id="completion_char_count" class="text-right" name="completion_char_count" size="3" maxlength="3" readonly> character remaining</div>
																</div>
																
																<div class="box-area pad-5 clearfix">
																	<div class="clearfix <?php //if(form_error('generalEmail')){ echo 'has-error has-feedback';} ?>">
																		<div class="">
																			<textarea class="form-control" id="completion_details" rows="7" tabindex="1" name="completion_details" onchange="textCounter(this.form.completion_details, this.form.completion_char_count);" onkeydown="textCounter(this.form.completion_details, this.form.completion_char_count);" onkeyup="textCounter(this.form.completion_details, this.form.completion_char_count);"><?php echo isset($sr_details->completion_details) ? $sr_details->completion_details : ''; ?></textarea>														
																		</div>
																	</div>
																</div>
															</div>
														<!-- </div> -->

														<div class="col-md-12 pad-10 m-top-20">
															<div class="col-md-offset-2 col-md-2 m-bottom-5">
																<button type="button" id="btnSave_SR_details" class="btn btn-success btn-block"><i class = "fa fa-save fa-lg"></i> Save</button>
															</div>

															<div class="col-md-2 m-bottom-5">
																<button type="button" id="btnGenerate_SR_pdf" class="btn btn-danger btn-block"><i class = "fa fa-file-pdf-o fa-lg"></i> Generate</button>
															</div>
					</form>
															<div class="col-md-2 m-bottom-5">
																<button type="button" id="btnSend_SR_pdf" class="btn btn-info btn-block"><i class = "fa fa-send-o fa-lg"></i> Send</button>
															</div>

															<div class="col-md-2 m-bottom-5">
																<form action="<?php echo base_url(); ?>projects/service_report_img_upload/<?php echo $project_id ?>" method="post" enctype="multipart/form-data" id="frmSiteInspection" role="form">

																	<input type="hidden" name="upload_path" value="<?php echo 'uploads/service_report_images/'.$project_id.'/' ?>">
																	<input type="hidden" name="is_inspection" value="1">
																	<input type="hidden" name="is_completion" value="0">

																	<span class="btn btn-warning btn-block btn-file">
																    	<i class="fa fa-file-photo-o"></i> Upload for Site Inspection<input type="file" id="btnSiteInspection" name="userfile[]" multiple="multiple" accept="image/*">
																	</span>
																</form>
															</div>

															<div class="col-md-2 m-bottom-5">
																<form action="<?php echo base_url(); ?>projects/service_report_img_upload/<?php echo $project_id ?>" method="post" enctype="multipart/form-data" id="frmCompletion" role="form">

																	<input type="hidden" name="upload_path" value="<?php echo 'uploads/service_report_images/'.$project_id.'/' ?>">
																	<input type="hidden" name="is_inspection" value="0">
																	<input type="hidden" name="is_completion" value="1">

																	<span class="btn btn-warning btn-block btn-file">
																    	<i class="fa fa-file-photo-o"></i> Upload for Completion<input type="file" id="btnCompletion" name="userfile[]" multiple="multiple" accept="image/*">
																	</span>
																</form>
															</div>

														</div>
															
													</div>
												</div>
											</div><!-- .box-area pad-5 clearfix -->
										</div><!-- .box m-bottom-15 clearfix -->

									</div><!-- .box-area pad-10 clearfix -->

								</div><!-- .left-section-box -->
							</div><!-- .col-md-9 -->

							<div class="col-md-3">
								<div class="box">
									<div class="box-head pad-5">
										<label><i class="fa fa-info-circle fa-lg"></i> Information</label>
									</div>
									<div class="box-area pad-5">
										<ul>
											<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
										</ul>
									</div>
								</div>

								<!-- <div class="box">
									<div class="box-head pad-5">
										<label><i class="fa fa-file-pdf-o fa-lg"></i> Service Report History</label>
									</div>
									<div class="box-area pad-5">
										
									</div>
								</div> -->

							</div><!-- .col-md-3 -->
						</div><!-- .row -->
					</div><!-- .container-fluid -->
				<!-- </form> -->
			</div><!-- .section col-sm-12 col-md-11 col-lg-11 -->
		</div><!-- .row -->
	</div><!-- .container-fluid -->

<?php $this->load->view('assets/logout-modal'); ?>

<!-- Modal -->
<div class="modal fade" id="attachSendModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width: 800px;">
	    <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Sending Service Report</h4>

			</div>
			<div class="modal-body">
				
				<div class="row">
				    <label for="send_to" class="col-md-2 control-label">Send To:</label>
				    <div class="col-md-10">

				    	<select class="form-control chosen" id="sendpdf_main_to" name="leading_hand" tabindex="1">	
				    		<option value="">Choose Email Address...</option>
				    		<option value="alt_emails">Input Alternate Email Address...</option>
				    		<!-- <option value="0|<?php //echo $pm_email; ?>"><?php //echo $pm_user_first_name.' '.$pm_user_last_name.' (PM) - '.$pm_email; ?><strong></option> -->
				    		<option value="1|<?php echo $contact_person_email_pr; ?>"><?php echo $contact_person_fname.' '.$contact_person_lname.' (Client) - '.$contact_person_email_pr; ?></option> <!-- $contact_person_email_pr; ****CLIENT EMAIL**** -->
				    		<!-- <option value="<?php //echo $pm_email.', '.$contact_person_email_pr; ?>">Both <?php //echo $pm_user_first_name.' '.$pm_user_last_name.' (PM)'; ?> and <?php //echo $contact_person_fname.' '.$contact_person_lname.' (Client)'; ?></option> -->
						</select>

				    </div>

				    <div class="clearfix"><br></div>

				</div>

				<div id="other_emails_wrap" class="row" style="display: none;">

					<div class="clearfix"><br></div>

					<div class="col-md-12">
						<div class="input-group">
						  <span id="other_email_name" class="input-group-addon">Alternate Emails:</span>
						  <input type="text" id="sendpdf_other_emails" class="form-control" placeholder="email1@yahoo.com, email2@gmail.com">
						  <span class="input-group-addon"><i class="fa fa-info-circle fa-lg tooltip-test" title="Use comma(,) to separate the email"></i></span>
						</div>
					</div>
				</div>

				<!-- <div id="check_client_wrap" class="row" style="display: none;">
					<div class="col-md-10 col-md-offset-2">
						<div class="checkbox">
							    <label>
							     	<input type="checkbox" id="is_client" name="is_client"> Is this the client? <i class="fa fa-info-circle fa-lg tooltip-test" title="If this is checked, raw photos will be deleted and this progress report will be archived."></i></small>
							    </label>
						</div>
					</div>
				</div> -->

				<div class="clearfix"><br></div>

				<div class="row">
					<div class="col-md-12">
						<div class="input-group">
						  <span id="cc_email_name" class="input-group-addon">CC:</span>
						  <input type="text" id="sendpdf_cc" class="form-control" placeholder="cc1@yahoo.com, cc2@gmail.com">
						  <span class="input-group-addon"><i class="fa fa-info-circle fa-lg tooltip-test" title="Use comma(,) to separate the email"></i></span>
						</div>
					</div>
				</div>

				<div class="clearfix"><br></div>

				<div class="row">
					<div class="col-md-12">
						<div class="input-group">
						  <span id="bcc_email_name" class="input-group-addon">BCC:</span>
						  <input type="text" id="sendpdf_bcc" class="form-control" placeholder="bcc1@yahoo.com, bcc2@gmail.com">
						  <span class="input-group-addon"><i class="fa fa-info-circle fa-lg tooltip-test" title="Use comma(,) to separate the email"></i></span>
						</div>
					</div>
				</div>

				<div class="clearfix"><br></div>

				<div class="row">
					<div class="col-md-12">
						<div class="input-group">
						  <span class="input-group-addon">Subject:</span>
						  <input type="text" id="sendpdf_subject" class="form-control" value="<?php echo $project_id.' '.$client_company_name.' '.$project_name; ?>">
						</div>
					</div>
				</div>

				<div class="clearfix"><br></div>

				<div class="row">
					<div class="col-md-12">
						<span id="noPDF" class="label label-danger" style="display: none;">The current Service Report is yet not generated. Please save and generate first before sending.</span>
						<span id="yesPDF" class="label label-success" style="display: none;">The current Service Report Report is generated and attached. Ready for sending.</span>
						<span id="lastPDF" class="label label-info" style="display: none;">Last generated Service Report PDF is attached. Ready for sending.</span>
					</div>
				</div>

				<div class="clearfix"><br></div>

				<div class="row">
					<div class="col-md-12">
						<textarea id="sendpdf_body" class="form-control" rows="10"></textarea>
					</div>
				</div>

				<!-- <div class="clearfix"><br></div>

				<div class="clearfix col-xs-12 text-center"><strong> <p><i class="fa fa-quote-left"></i> You can download the recent Progress Report PDF in the 'Progress Report History' on the right side of the Page or send it again if the current Progress Report is not generated.. <i class="fa fa-quote-right"></i></p></strong></div>

				<br> -->
			</div>

			<div class="modal-footer">
				<button type="button" id="sendpdf_close" class="btn btn-danger" data-dismiss="modal">Close</button>
				<button type="button" id="sendpdf_btn" class="btn btn-primary">Send</button>
			</div>
	    </div>
	</div>
</div>

<div class="modal fade" id="attachement_loading_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-sm">
    <div class="modal-content">
      <div class="modal-body clearfix pad-10">
        <center><h3>Loading Please Wait</h3></center>
        <center><h2><i class="fa fa-circle-o-notch fa-spin fa-5x"></i></h2></center>
        <p>&nbsp;</p>
      </div>
    </div>
  </div>
</div>

<script src="<?php echo base_url(); ?>js/bsPhotoGallery/jquery.bsPhotoGallery2.js"></script>
<script src="<?php echo base_url(); ?>js/Jcrop/Jcrop.js"></script>

<script type="text/javascript">
		
	var maxAmount = 420;

	function textCounter(textField, showCountField) {
		if (textField.value.length > maxAmount) {
			textField.value = textField.value.substring(0, maxAmount);
		} else {
			showCountField.value = maxAmount - textField.value.length;
		}
	}

	$(document).ready(function(){

		$('#site_inspection_details').keydown(limitTextareaLine);
		$('#completion_details').keydown(limitTextareaLine);

		var si_details_count = maxAmount - $("#site_inspection_details").val().length;
		var comp_details_count = maxAmount - $("#completion_details").val().length;

		$('#si_char_count').val(si_details_count);
		$('#completion_char_count').val(comp_details_count);

		function limitTextareaLine(e) {

		    if(e.keyCode == 13 && $(this).val().split("\n").length >= $(this).attr('rows')) { 
		        return false;
		    }		    
		}

		var box = document.getElementById('site_inspection_details');
		var charlimit = 120; // char limit per line

		box.onkeyup = function() {
			var lines = box.value.split('\n');
			for (var i = 0; i < lines.length; i++) {
				if (lines[i].length <= charlimit) continue;
				var j = 0; space = charlimit;
				while (j++ <= charlimit) {
					if (lines[i].charAt(j) === ' ') space = j;
				}
				lines[i + 1] = lines[i].substring(space + 1) + (lines[i + 1] || "");
				lines[i] = lines[i].substring(0, space);
			}
			box.value = lines.slice(0, 10).join('\n');
		};

		var box2 = document.getElementById('completion_details');
		var charlimit2 = 120; // char limit per line

		box2.onkeyup = function() {
			var lines = box2.value.split('\n');
			for (var i = 0; i < lines.length; i++) {
				if (lines[i].length <= charlimit2) continue;
				var j = 0; space = charlimit2;
				while (j++ <= charlimit2) {
					if (lines[i].charAt(j) === ' ') space = j;
				}
				lines[i + 1] = lines[i].substring(space + 1) + (lines[i + 1] || "");
				lines[i] = lines[i].substring(0, space);
			}
			box2.value = lines.slice(0, 10).join('\n');
		};

		$("input:file#btnSiteInspection").change(function(){
			$("#frmSiteInspection").submit();
		});

		$("input:file#btnCompletion").change(function(){
			$("#frmCompletion").submit();
		});

		$('.overlay').click(function(){
		 	setTimeout(function(){
				$('#bsPhotoGalleryModal').modal('hide');
				$('#bsPhotoGalleryModal').css('width',0);
			  	$('#bsPhotoGalleryModal').hide();
			},0.1);

			$('#confirmModal').modal('show');

			var image_id = $(this).attr('id');
		    $('#confirmText').text('Are you sure you want to delete this image?');
	    	$('#confirmButtons').html('<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>' +
	                    			  '<button type="button" class="btn btn-success" onclick="confirmDelImage('+image_id+');">Yes</button>');
		});

		$('ul.first').bsPhotoGallery({
			"classes" : "col-lg-2 col-md-4 col-sm-3 col-xs-4 col-xxs-12",
			"hasModal" : true
		});

		var imgWidth;
		var imgHeight;

		var defaultX = 10;
		var defaultY = 10;
		var defaultWidth = 300;
		var defaultHeight = 300;

		$('li.bspHasModal').click(function(){
			img_id = this.id.replace('image_report_','');
		});

		$('#bsPhotoGalleryModal').on('shown.bs.modal', function (e) {

			$('#lblImage').focus();

			$('#bsPhotoGalleryModal').css('width','auto');
			$('.modal-backdrop').css('background-color', 'none');

			$('#imageGallery.modal-dialog').css({ width: "inherit", height: "inherit" });

			imgWidth = $('#target').width();
			imgHeight = $('#target').height();

			if (imgWidth <= '768'){
		   		$('#imgLabel').removeClass('col-md-4');
		   		$('#imgGroup').removeClass('col-md-4');
				$('#imgTools').removeClass('col-md-4');
		   	} else {
			   		$('#imgLabel').addClass('col-md-8');
					$('#imgTools').addClass('col-md-12');
			}

			var img_name;
			var path = $('img#target').attr('src');
			img_name = path.split("/").pop();

			image_editor_controls(img_id);

			if ($('#lblImage').val() == '(No Image Label)'){
				$('#lblImage').val('');
			}

			$('img#target').on('load', function () {

				$('#imageGallery.modal-dialog').css({ width: "inherit", height: "inherit" });

				imgWidth = $('#target').width();
				imgHeight = $('#target').height();

				if (imgWidth <= '768'){
			   		$('#imgLabel').removeClass('col-md-8');
			   		$('#imgGroup').removeClass('col-md-4');
					$('#imgTools').removeClass('col-md-4');
			   	} else {
			   		$('#imgLabel').addClass('col-md-8');
					$('#imgTools').addClass('col-md-12');
			   	}
			});

			var crop_x = 0;
			var crop_y = 0;
			var crop_width = 0;
			var crop_height = 0;

			function image_editor_controls(img_id){

				$('li#crop i').click(function(){
					$('span.bsp-close').hide();
					$('a.bsp-controls.next').hide();
					$('a.bsp-controls.previous').hide();

					$('ul.image-tool-icons > li#crop').hide();
					$('ul.image-tool-icons > li#rotate-left').hide();
					$('ul.image-tool-icons > li#saveCrop').hide();
					$('ul.image-tool-icons > li#undo').hide();

					$('ul.image-tool-icons > li#cancelCrop').show();
					$('ul.image-tool-icons > li#check').show();
					
					$('#target').Jcrop({
						setSelect: [ defaultX,defaultY,defaultWidth,defaultHeight ],
						bgColor: 'black'
					}, function(){
						$('#cropx').val(defaultX);
						$('#cropy').val(defaultY);
						$('#cropw').val(defaultWidth);
						$('#croph').val(defaultHeight);
						getCoords();
					});
				});

				$('ul.image-tool-icons > li#cancelCrop').click(function(){
					$('span.bsp-close').show();
					$('a.bsp-controls.next').show();
					$('a.bsp-controls.previous').show();

					$("#lblImage").show();
					$("#imgGroup").show();

					$('ul.image-tool-icons > li#undo').show();
					$('ul.image-tool-icons > li#crop').show();
					$('ul.image-tool-icons > li#rotate-left').show();
					
					$('ul.image-tool-icons > li#cancelCrop').hide();
					$('ul.image-tool-icons > li#check').hide();
					$('ul.image-tool-icons > li#saveCrop').hide();
					
					if ($('#target').Jcrop('api') == null){
						$('#target').width(imgWidth);
						$('#target').height(imgHeight);
						$('#targetCanvas').hide();

						if ($('#target').width() <= '768'){
					   		$('#imgLabel').removeClass('col-md-8');
					   		$('#imgGroup').removeClass('col-md-4');
							$('#imgTools').removeClass('col-md-4');
					   	} else {
					   		$('#imgLabel').addClass('col-md-8');
							$('#imgTools').addClass('col-md-12');
					   	}

					   	$('#target').show();
					} else {
						if ($('#target').data('Jcrop')) {
						   $('#target').data('Jcrop').destroy();
						}
					}
				});

				$('ul.image-tool-icons > li#check').click(function(){
					applyCrop();
				});

				$('ul.image-tool-icons > li#saveCrop').click(function(){
					saveCrop(img_name, img_id);
				});

				$('ul.image-tool-icons > li#cancelRotate').click(function(){
					$('span.bsp-close').show();
					$('a.bsp-controls.next').show();
					$('a.bsp-controls.previous').show();

					$('ul.image-tool-icons > li#undo').show();
					$('ul.image-tool-icons > li#crop').show();
					$('ul.image-tool-icons > li#rotate-left').show();
					
					$('ul.image-tool-icons > li#cancelRotate').hide();
					$('ul.image-tool-icons > li#saveRotate').hide();
					
					$('#imageGallery.modal-dialog').css({ width: "inherit", height: "inherit" });
					$('#target').css('transform','none');
				});

				$('ul.image-tool-icons > li#rotate-left').click(function(){
					$('span.bsp-close').hide();
					$('a.bsp-controls.next').hide();
					$('a.bsp-controls.previous').hide();

					$('ul.image-tool-icons > li#undo').hide();
					$('ul.image-tool-icons > li#crop').hide();

					$('ul.image-tool-icons > li#cancelRotate').css("display", "inline");
					$('ul.image-tool-icons > li#saveRotate').css("display", "inline");
					$('ul.image-tool-icons > li#rotate-left').css("display", "inline");
					rotateLeft();
				});

				$('ul.image-tool-icons > li#saveRotate').click(function(){
					saveRotate(img_name, img_id);
				});

				$('ul.image-tool-icons > li#undo').click(function(){

					var path = $('img#target').attr('src');
					var URLremoveLastPart = RemoveLastDirectoryPartOf(path);

					img_name = path.split("/").pop();

					if (img_name.lastIndexOf("?") != -1){
						img_name = img_name.substr(0, img_name.lastIndexOf("?"));	
					}

					$.ajax({
					  url: URLremoveLastPart+'/backup_img/'+img_name, //or your url
					  success: function(data){
					    deleteEditedimages(img_name);
					  },
					  error: function(data){
					    alert('There is no backup image for this! Undo is cancelled...');
					  },
					})
				});
			}

			function deleteEditedimages(img_name){

				var img_name;
			 	var project_id = $('#project_id').val();
				var rootFolder = '<?php echo $_SERVER['DOCUMENT_ROOT']; ?>';
				
				var img_path = rootFolder+'/uploads/service_report_images/'+project_id+'/'+img_name; // live
				var backup_path = rootFolder+'/uploads/service_report_images/'+project_id+'/backup_img'; // live

				// var img_path = rootFolder+'/public_html/uploads/service_report_images/'+project_id+'/'+img_name; // local
				// var backup_path = rootFolder+'/public_html/uploads/service_report_images/'+project_id+'/backup_img'; // local
				
				$.ajax({
					type: 'GET',
				  	url: '<?php echo base_url().'projects/delete_file'; ?>',
				  	data: {'file' : img_path,
				  	       'filename' : img_name,
				  	       'backup_path' : backup_path },
			      	dataType: 'json', 
			      	success: function (response) {
			         	if( response.status === true ) {
			         		// set_edited(img_name, '0');
			             	alert('Restoring backup image...');				             	
			             	$("li#image_report_"+img_id+" .imgWrapper > img").prop("src", "<?php echo base_url(); ?>uploads/service_report_images/"+project_id+"/"+img_name+"?" + +new Date());			             	
							alert('Undo is successful.');
							$('#bsPhotoGalleryModal').modal('hide');

							$('#attachement_loading_modal').modal('show');
							$('#attachement_loading_modal .modal-dialog.modal-sm').css('width', '300px');
						    $('#attachement_loading_modal .modal-dialog.modal-sm').css('height', '300px');

							setTimeout(function(){ 
								$('#attachement_loading_modal').modal('hide');
								$("li#image_report_"+img_id+"").trigger( "click" ); 
							}, 1000);

			         	} else {
			         		alert('Something Went Wrong!');
			      		}
			      	}
				});
			}

			function getCoords(){
				var container = $('#target').Jcrop('api').container;

				container.on('cropstart cropmove cropend',function(e,s,c){
					$('#cropx').val(c.x);
					$('#cropy').val(c.y);
					$('#cropw').val(c.w);
					$('#croph').val(c.h);

					crop_x = c.x;
					crop_y = c.y;
					crop_width = c.w;
					crop_height = c.h;
				});
			}

			function applyCrop(){

				if ($('#target').data('Jcrop')) {
				   $('#target').data('Jcrop').destroy();
				}

				$('ul.image-tool-icons > li#saveCrop').show();
				$('ul.image-tool-icons > li#check').hide();

				$("#lblImage").hide();
				$("#imgGroup").hide();

				$(".modal-dialog").width(crop_width).height(crop_height);
				$('#targetCanvas').attr({ width: crop_width, height: crop_height });
				var canvas = document.getElementById("targetCanvas");
			    var ctx = canvas.getContext("2d");
			    var img = document.getElementById("target");
			    ctx.drawImage(img, crop_x, crop_y, crop_width, crop_height, 0, 0, crop_width, crop_height);
				
			   	$('#target').hide();

			   	if ($('#targetCanvas').width() <= '768'){
			   		$('#imgLabel').removeClass('col-md-8');
			   		$('#imgGroup').removeClass('col-md-4');
					$('#imgTools').removeClass('col-md-4');
			   	} else {
			   		$('#imgLabel').addClass('col-md-8');
					$('#imgTools').addClass('col-md-12');
			   	}

			   	$('#targetCanvas').show();
			}

			var rotate_angle = '0';

			function rotateLeft(){
				var rotate_degrees = $('#target').css('transform'); // alert(rotate_degrees); for cross browser

			 	if (rotate_degrees == 'none'){

			 		$('#target').css({'-moz-transform':'rotate(-90deg)', '-webkit-transform':'rotate(-90deg)', '-ms-transform':'rotate(-90deg)', 'transform':'rotate(-90deg)'});
			 		$('#imageGallery.modal-dialog').width(imgWidth);
				 	$('#imageGallery.modal-dialog').height(imgWidth);

				 	rotate_angle = '90';

			 	} else if (rotate_degrees == 'matrix(0, -1, 1, 0, 0, 0)' || rotate_degrees == 'matrix(6.12323e-17, -1, 1, 6.12323e-17, 0, 0)') {

			 		$('#target').css({'-moz-transform':'rotate(-180deg)', '-webkit-transform':'rotate(-180deg)', '-ms-transform':'rotate(-180deg)', 'transform':'rotate(-180deg)'});
			 		$('#imageGallery.modal-dialog').width(imgWidth);
				 	$('#imageGallery.modal-dialog').height(imgHeight);

				 	rotate_angle = '180';

			 	} else if (rotate_degrees == 'matrix(-1, 0, 0, -1, 0, 0)' || rotate_degrees == 'matrix(-1, -1.22465e-16, 1.22465e-16, -1, 0, 0)') {

			 		$('#target').css({'-moz-transform':'rotate(-270deg)', '-webkit-transform':'rotate(-270deg)', '-ms-transform':'rotate(-270deg)', 'transform':'rotate(-270deg)'});
			 		$('#imageGallery.modal-dialog').width(imgWidth);
				 	$('#imageGallery.modal-dialog').height(imgWidth);

				 	rotate_angle = '270';

			 	} else {

			 		$('#target').css({'-moz-transform':'none', '-webkit-transform':'none', '-ms-transform':'none', 'transform':'none'});
			 		$('#imageGallery.modal-dialog').width(imgWidth);
				 	$('#imageGallery.modal-dialog').height(imgHeight);

				 	rotate_angle = '0';
			 	}
			}

			function saveCrop(img_name, img_id){

				if (img_name.lastIndexOf("?") != -1){
					img_name = img_name.substr(0, img_name.lastIndexOf("?"));	
				}

				if (crop_x == 0 && crop_y == 0 && crop_width == 0 && crop_height == 0){
					alert('Please select a cropping area.');
				} else {
					var project_id = $('#project_id').val();
					var rootFolder = '<?php echo $_SERVER['DOCUMENT_ROOT']; ?>';

					var crop_path = rootFolder+'/uploads/service_report_images/'+project_id+'/'+img_name; // live
					var backup_path = rootFolder+'/uploads/service_report_images/'+project_id+'/backup_img/'+img_name; // live
					
					// var crop_path = rootFolder+'/public_html/uploads/service_report_images/'+project_id+'/'+img_name; // local
					// var backup_path = rootFolder+'/public_html/uploads/service_report_images/'+project_id+'/backup_img/'+img_name; // local
					
					var data = project_id+'|'+crop_x+'|'+crop_y+'|'+crop_width+'|'+crop_height+'|'+crop_path+'|'+crop_path+'|'+img_name+'|'+backup_path; // alert(data);

					$.ajax({
						'url' : '<?php echo base_url().'projects/crop_img'; ?>',
						'type' : 'POST',
						'data' : {'ajax_var' : data },
						'success' : function(data){

							// set_edited(img_name, '1');
							$("li#image_report_"+img_id+" .imgWrapper > img").prop("src", "<?php echo base_url(); ?>uploads/service_report_images/"+project_id+"/"+img_name+"?" + +new Date());
							alert('Image is cropped successfully.');
							$('#bsPhotoGalleryModal').modal('hide');

							$('#attachement_loading_modal').modal('show');
							$('#attachement_loading_modal .modal-dialog.modal-sm').css('width', '300px');
						    $('#attachement_loading_modal .modal-dialog.modal-sm').css('height', '300px');

							setTimeout(function(){ 
								$('#attachement_loading_modal').modal('hide');
								$("li#image_report_"+img_id+"").trigger( "click" ); 
							}, 1000);
						}
					});
				}
			}

			function saveRotate(img_name, img_id){

				if (img_name.lastIndexOf("?") != -1){
					img_name = img_name.substr(0, img_name.lastIndexOf("?"));	
				}

				if (rotate_angle == '0'){
					alert('Please rotate the image.');
				} else {
					var project_id = $('#project_id').val();
					var rootFolder = '<?php echo $_SERVER['DOCUMENT_ROOT']; ?>';

					var rotate_path = rootFolder+'/uploads/service_report_images/'+project_id+'/'+img_name; // live
					var backup_path = rootFolder+'/uploads/service_report_images/'+project_id+'/backup_img/'+img_name; // live
					
					// var rotate_path = rootFolder+'/public_html/uploads/service_report_images/'+project_id+'/'+img_name; // local
					// var backup_path = rootFolder+'/public_html/uploads/service_report_images/'+project_id+'/backup_img/'+img_name; // local
					
					var data = project_id+'|'+rotate_path+'|'+rotate_angle+'|'+img_name+'|'+backup_path; // alert(data);

					$.ajax({
						'url' : '<?php echo base_url().'projects/rotate'; ?>',
						'type' : 'POST',
						'data' : {'ajax_var' : data },
						'success' : function(data){

							// set_edited(img_name, '1');

							$("li#image_report_"+img_id+" .imgWrapper > img").prop("src", "<?php echo base_url(); ?>uploads/service_report_images/"+project_id+"/"+img_name+"?" + +new Date());
							alert('Image is rotated successfully.');
							$('#bsPhotoGalleryModal').modal('hide');

							$('#attachement_loading_modal').modal('show');
							$('#attachement_loading_modal .modal-dialog.modal-sm').css('width', '300px');
						    $('#attachement_loading_modal .modal-dialog.modal-sm').css('height', '300px');

							setTimeout(function(){ 
								$('#attachement_loading_modal').modal('hide');
								$("li#image_report_"+img_id+"").trigger( "click" ); 
							}, 1000);
						}
					});
				}
			}

			function RemoveLastDirectoryPartOf(the_url){
			    var the_arr = the_url.split('/');
			    the_arr.pop();
			    return( the_arr.join('/') );
			}

			// editLabelcontrols(img_name);
			// getGroupImg(img_name);

			last_img_name = $('#lblImage').val();

			$('#lblImage').change(function(){
				saveImageLbl(last_img_name, img_name);			
			});

			img_name_trim = img_name.substring(0, img_name.lastIndexOf('.'));
			var hiddenGroupSelected = $('#'+img_name_trim+'_group');
			var group_selected_val = hiddenGroupSelected.val();
			$('.groupSelect').attr('id', img_name_trim+'_groupSelect');
			var groupSelect = $('#'+img_name_trim+'_groupSelect');
			groupSelect.val(group_selected_val);

			groupSelect.change(function() {
			  	updateGroupImg(img_name, img_name_trim);
				alert('Group is successfully selected.');
			  	hiddenGroupSelected.val(groupSelect.val());
			});
		});
	
		var image_name_val = '';

		function updateGroupImg(img_name, image_name_trim){

			if (img_name.lastIndexOf("?") != -1){
				var img_name_cut = img_name.substr(0, img_name.lastIndexOf("?"));	
			}

			var project_id = $('#project_id').val();
			var group_selected_id = $('#'+image_name_trim+'_groupSelect').val();

			data = img_name_cut+'|'+project_id+'|'+group_selected_id; // alert(data);

			if(group_selected_id == -1){

				$('#addGroupModal').modal('show');
				image_name_val = img_name_cut;

			} else {

				$.ajax({
					'url' : '<?php echo base_url().'projects/updateGroupImg2'; ?>',
					'type' : 'POST',
					'data' : {'ajax_var' : data },
					'success' : function(data){
						$('#'+image_name_trim+'_groupSelect').val(group_selected_id);
						// set_edited(img_name_cut, '1');
					}
				});
			}
		}

		$("#btnSend_SR_pdf").click(function(){

			var project_id = $('#project_id').val();
			var project_name_footer = $('#project_name_footer').val();
			var project_suburb_footer = $('#project_suburb_footer').val();
			var project_name_clean_spaces = $.trim(project_name_footer+' '+project_suburb_footer);
			var rootFolder = '<?php echo $_SERVER['DOCUMENT_ROOT']; ?>';

			var sr_path = rootFolder+'/reports/service_reports/'+project_id+'/'+project_name_clean_spaces.toUpperCase()+'.pdf'; // live
			// var sr_path = rootFolder+'/public_html/reports/service_reports/'+project_id+'/'+project_name_clean_spaces+'.pdf'; // local

			$.ajax({
				type: 'GET',
			  	url: '<?php echo base_url().'projects/check_pdf'; ?>',
			  	data: { 'pr_path' : sr_path },
		      	dataType: 'json', 
		      	success: function (response) {

		         	if( response.status === true ) {

		         		$('#lastPDF').css('display', 'none');
		             	$('#noPDF').css('display', 'none');
		             	$('#yesPDF').css('display', 'block');
		             	$('#attachSendModal').modal({backdrop: 'static', keyboard: false});  

		             	is_pdf_attached = 1;

		         	} else {

		         		$('#lastPDF').css('display', 'none');
	         			$('#yesPDF').css('display', 'none');
		         		$('#noPDF').css('display', 'block');
		             	$('#attachSendModal').modal({backdrop: 'static', keyboard: false});

		             	is_pdf_attached = 0;
		      		}
		      	}
			});
		});

		$('select#sendpdf_main_to').change(function(){

			if ($('select#sendpdf_main_to').val() == 'alt_emails'){
				$('#other_emails_wrap').show();
				$('#check_client_wrap').show();
				$('#other_email_name').text('Alternate Emails: ');
				$('#sendpdf_other_emails').val('');
			} else {
				$('#other_emails_wrap').show();
				$('#check_client_wrap').hide();
				$('#other_email_name').text('Other Emails: ');
				$('#sendpdf_other_emails').val('');
			}
		});

		$('#sendpdf_btn').click(function(){

			var sendpdf_main_to_complete = $('#sendpdf_main_to').val();
			var sendpdf_main_to_type = sendpdf_main_to_complete.substr(0, 1);

			if (sendpdf_main_to_type == 1) {
     			var sendpdf_main_to = sendpdf_main_to_complete.substr(2);
     		}

			var sendpdf_other_emails = $('#sendpdf_other_emails').val();
			var sendpdf_from = $('#end_user_email').val();
			var sendpdf_from_name = $('#user_full_name').val();
			var sendpdf_subject = $('#sendpdf_subject').val();
			var sendpdf_body = tinymce.get('sendpdf_body').getContent();
			// var is_client = $("#is_client").is(':checked');

			var sendpdf_cc = $('#sendpdf_cc').val();
			var sendpdf_bcc = $('#sendpdf_bcc').val();

			var rootFolder = '<?php echo $_SERVER['DOCUMENT_ROOT']; ?>';

			/* ------- THIS IS IMPORTANT ------- */

			var project_id = $('#project_id').val();
			var project_name_footer = $('#project_name_footer').val();
			var project_suburb_footer = $('#project_suburb_footer').val();
			var project_name_clean_spaces = $.trim(project_name_footer+' '+project_suburb_footer);
			var rootFolder = '<?php echo $_SERVER['DOCUMENT_ROOT']; ?>';

			var sendpdf_path  = rootFolder+'/reports/service_reports/'+project_id+'/'+project_name_clean_spaces.toUpperCase()+'.pdf'; // live
			// var sendpdf_path  = rootFolder+'/public_html/reports/service_reports/'+project_id+'/'+project_name_clean_spaces+'.pdf'; // local

			/* ------- THIS IS IMPORTANT ------- */

			var data = sendpdf_main_to+'|'+sendpdf_other_emails+'|'+sendpdf_from+'|'+sendpdf_from_name+'|'+sendpdf_subject+'|'+sendpdf_body+'|'+sendpdf_path+'|'+sendpdf_cc+'|'+sendpdf_bcc; // alert(data);

			if (is_pdf_attached == 1){
				if (sendpdf_main_to == '' && sendpdf_other_emails == ''){

					$('#confirmModal').modal('show');

			    	$('.modal-title.msgbox').text('Email address is blank.');
			    	$('#confirmModal .modal-dialog.modal-sm').css('width', '500px');
			    	$('#confirmModal .modal-dialog.modal-sm').css('height', '200px');
			    	
				    $('#confirmText').html('Please select email address.');
			    	$('#confirmButtons').html('<button type="button" class="btn btn-danger" data-dismiss="modal">Go Back</button>');

				} else {

					if (sendpdf_main_to == 'alt_emails' && sendpdf_other_emails == ''){
						$('#confirmModal').modal('show');

				    	$('.modal-title.msgbox').text('Email address is blank.');
				    	$('#confirmModal .modal-dialog.modal-sm').css('width', '500px');
				    	$('#confirmModal .modal-dialog.modal-sm').css('height', '200px');
				    	
					    $('#confirmText').html('Please select email address.');
				    	$('#confirmButtons').html('<button type="button" class="btn btn-danger" data-dismiss="modal">Go Back</button>');
					} else {
						$("button#sendpdf_close").button('loading');
						$("button#sendpdf_btn").button('loading');

						$.ajax({
							type: 'POST',
						  	url: '<?php echo base_url().'projects/send_pr_pdf'; ?>',
						  	data: { 'ajax_var' : data },
					      	dataType: 'json', 
					      	success: function (response) {
					      		$("button#sendpdf_close").button('reset');
					      		$("button#sendpdf_btn").button('reset');

					         	if( response.status === true ) {

									$('#confirmModal').modal({backdrop: 'static', keyboard: false});

							    	$('.modal-title.msgbox').text('Success Message');
							    	$('#confirmModal .modal-dialog.modal-sm').css('width', '500px');
							    	$('#confirmModal .modal-dialog.modal-sm').css('height', '200px');
							    	
								    $('#confirmText').html('Email is successfully sent...');
							    	$('#confirmButtons').html('<button type="button" class="btn btn-success" data-dismiss="modal">Okay</button>');

							    	$('#sendpdf_other_emails').val('');
									$('#sendpdf_body').val('');

							    	$('#attachSendModal').modal('hide');

					         	} else {
					         		$('#confirmModal').modal('show');

							    	$('.modal-title.msgbox').text('Warning Message');
							    	$('#confirmModal .modal-dialog.modal-sm').css('width', '500px');
							    	$('#confirmModal .modal-dialog.modal-sm').css('height', '200px');
							    	
								    $('#confirmText').html('Email is not sent successfully. Please contact administrator.');
							    	$('#confirmButtons').html('<button type="button" class="btn btn-warning" data-dismiss="modal">Go Back</button>');
					      		}
					      	},
					      	error: function (request, status, error) {
						        alert(status);
						    }
						});
					}
				}

			} else {
				$('#confirmModal').modal('show');

		    	$('.modal-title.msgbox').text('PDF attachment error');
		    	$('#confirmModal .modal-dialog.modal-sm').css('width', '500px');
		    	$('#confirmModal .modal-dialog.modal-sm').css('height', '200px');
		    	
			    $('#confirmText').html('Please click Save and Generate PDF before sending the PDF.');
		    	$('#confirmButtons').html('<button type="button" class="btn btn-danger" data-dismiss="modal">Go Back</button>');
			}
		});
	});

	function confirmDelImage(image_id){

		var project_id = $('#project_id').val();
		var data = image_id+'|'+project_id; // alert(data);

		$.ajax({
			'url' : '<?php echo base_url().'projects/delete_sr_image'; ?>',
			'type' : 'POST',
			'data' : {'ajax_var' : data },
			'success' : function(data){
				$('#confirmModal').modal('hide');
				alert('Successfully deleted the image.');
				$('#image_report_'+image_id).remove();
			}
		});
	}

	$('#btnSave_SR_details').click(function(){

		var project_id = $('#project_id').val();
		var site_inspection_details = $('#site_inspection_details').val();
		var completion_details = $('#completion_details').val();

		var data = project_id+'|'+site_inspection_details+'|'+completion_details; 

		// alert(data);

		$.ajax({
			'url' : '<?php echo base_url().'projects/save_service_report_details'; ?>',
			'type' : 'POST',
			'data' : {'ajax_var' : data },
			'success' : function(result){

				if (result == 1){
					alert('Details are now updated.');
				} else {
					alert('Some errors found. Please contact administrator.');
				}
			}
		});

	});

	$('#btnGenerate_SR_pdf').click(function(){

		if ($('#site_inspection_include').is(':checked') == false && $('#completion_include').is(':checked') == false){

			$('#confirmModal').modal('show');
			$('h4#myModalLabel.modal-title.msgbox').html("Message Alert!");
		    $('#confirmText').html('Please select an area that you want to include in the Service Report PDF.');
	    	$('#confirmButtons').html('<button type="button" class="btn btn-warning" data-dismiss="modal">Go Back</button>');

		} else {
			
			$("#generate_sr_report").submit();
		}
	});

</script>
