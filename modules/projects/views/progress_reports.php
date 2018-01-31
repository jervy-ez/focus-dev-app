<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>

<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"> -->

<?php 
	if($this->session->userdata('is_admin') != 1 ): 

	redirect('/projects/', 'refresh');

	endif; 
 ?>

<style type="text/css">
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
	  top: 0;
	  right: 0;
	}
	.select-report-image .image-checkbox-checked .fa {
	  display: block !important;
	}

	.bspHasModal{
		padding: 0 !important;
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
		text-transform: capitalize;;
	}

	#confirmModal {
	    z-index: 9999 !important;
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
						<a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Home</a>
					</li>    
          <?php if($this->session->userdata('projects') >= 2): ?>
					<li class="">
						<a href="#" class="btn-small btn-primary" data-toggle="modal" data-target="#wip_filter_modal"><i class="fa fa-print"></i> Report</a>
					</li>  		  
          <?php endif; ?>
          
					<li>
						<a class="btn-small sb-open-right"><i class="fa fa-file-text-o"></i> Project Comments</a>
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
			<form target="_blank" id="generate_pr_report" class="form-horizontal" role="form" method="post" action="<?php echo base_url(); ?>projects/progress_report_pdf">
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

								<div class="box-head pad-10 clearfix">
									<label>Generate Progress Report</label>
									<span> (<a href="#" data-placement="right" class="popover-test" title="" data-content="Hello there mate! Welcome to the progress report screen." data-original-title="Welcome">?</a>)</span>
									<p>Fields having * is requred.</p>	
									<p><a href="#" class="tooltip-test" title="Tooltip">This link</a> and <a href="#" class="tooltip-test" title="Tooltip">that link</a> should have tooltips on hover.</p>
								</div>

								<div class="box-area pad-10 clearfix">	

									<div class="box m-bottom-15 clearfix">
										<div class="box-head pad-5 m-bottom-5">
											<label><i class="fa fa-book fa-lg"></i> General - <span class="label label-danger">Progress Report <?php echo $pr_details->pr_version?></span></label> <!-- display here the version number of PR -->
										</div>
										
										<div class="box-area pad-5 clearfix">
											
											<div class="col-md-8">
												<div class="pad-15 no-pad-t">	
													
													<input type="hidden" id="project_id" name="project_id" value="<?php echo $project_id; ?>">

													<h4 class="clearfix">
														<span class="text-left">Project Name:</span>
														<h3 style="margin-left: 20px;"><label id="project_name" class="text-left"><?php echo $project_name; ?></label></h3>
														<input type="hidden" id="project_name" name="project_name" value="<?php echo $project_name; ?>">
													</h4>

													<h4 class="clearfix">
														<span class="text-left">Client:</span>
														<h3 style="margin-left: 20px;"><label id="client_company_name" class="text-left"><?php echo $client_company_name; ?></label></h3>
														<input type="hidden" id="client_company_name" name="client_company_name" value="<?php echo $client_company_name; ?>">
													</h4>

													<div class="clearfix"></div>

													<h4><i class="fa fa-map-marker"></i> Site Address:</h4>
													<?php $shop_tenancy_numb = ($job_type != 'Shopping Center' ? '' : ''.$shopping_common_name.': '.$shop_tenancy_number); ?>
													<?php $unit_level =  ($unit_level != '' ? 'Unit/Level:'.$unit_level.',' : '' ); ?>
													<h5 style="margin-left: 20px;"><p id="site_address1"><?php echo "$shop_tenancy_numb $unit_level"; ?></p></h5>
													<h5 style="margin-left: 20px;"><p id="site_address2"><?php echo "$unit_number $street, $suburb, $state, $postcode"; ?><br /><br /></p></h5>
													
													<input type="hidden" id="site_address1" name="site_address1" value="<?php echo "$shop_tenancy_numb $unit_level"; ?>">
													<input type="hidden" id="site_address2" name="site_address2" value="<?php echo "$unit_number $street, $suburb, $state, $postcode"; ?>">

													<div class="box">
														<div class="box-head pad-5">

															<label for="project_notes"><i class="fa fa-pencil-square fa-lg"></i> Works Progress Details</label>
														</div>
														
														<div class="box-area pad-5 clearfix">
															<div class="clearfix <?php if(form_error('generalEmail')){ echo 'has-error has-feedback';} ?>">
																<div class="">
																	<textarea class="form-control" id="scope_of_work" rows="15"  tabindex="1" name="scope_of_work"><?php echo $this->input->post('scope_of_work'); ?><?php echo $pr_details->scope_of_work; ?></textarea>														
																</div>
															</div>
														</div>
													</div>

													<div class="clearfix"><br></div>

													<!--<div class="form-group">
														<h4><i class="fa fa-file-image-o"></i> Upload Photos here:</h4>
													    <input type="file" id="exampleInputFile">
													</div>-->
												</div>
											</div>

											<div class="col-md-4">	
												<h4><i class="fa fa-users"></i> Contact Person:</h4>
												<div class="pad-15 no-pad-t">
													<p class="clearfix">
														<span class="text-left">Leading Hand:</span>
														<?php if (!empty($lead_hand_user_id)): ?>
															<strong><h5 id="leading_hand" style="margin-left: 20px;"><?php echo "$lead_hand_user_first_name $lead_hand_user_last_name - $lead_hand_mobile_number"; ?></h5></strong>
															<input type="hidden" id="leading_hand_input" name="leading_hand" value="<?php echo "$lead_hand_user_first_name $lead_hand_user_last_name - $lead_hand_mobile_number"; ?>">
															<input type="hidden" id="leading_hand_id" name="leading_hand_id" value="<?php echo $lead_hand_user_id; ?>">

														<?php else: ?>
															<div class="row">
																<div class="col-md-9" style="margin-left: 20px;">	
																	<select class="form-control chosen" id="leading_hand" name="leading_hand" tabindex="2">														
																		<option value="">Choose a Leading Hand...</option>

																		<?php foreach ($lead_hand_option as $row){ 
																				if ($lead_hand_user_id == $row->user_id){
																					echo '<option value="'.$row->user_id.'" selected>'.$row->user_first_name.' '.$row->user_last_name.' - '.$row->mobile_number.'</option>';
																				} else {
																					echo '<option value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.' - '.$row->mobile_number.'</option>';
																				} 

																			  } ?>
																	</select>

																	<!-- <input type="hidden" id="leading_hand_input_hidden" name="leading_hand_full" value=""> -->
																	<input type="hidden" id="leading_hand_id" name="leading_hand_id" value="">
																</div>
															</div>
														<?php endif; ?>
													</p>

													<p class="clearfix"></p>

													<p class="clearfix">
														<span class="text-left">Construction Manager:</span>

														<?php
															// $const_mngr_count = 0;

															// foreach ($const_mngr_option as $row){
															// 	if ($row->user_id == $proj_sched_details->contruction_manager_id){
															// 		$const_mngr_count++;
															// 	}
															// }
														?>

														<?php if (!empty($const_mngr_id)): ?>
															
															<strong><h5 id="const_mngr" style="margin-left: 20px;"><?php echo "$cons_mngr_user_first_name $cons_mngr_user_last_name - $cons_mngr_mobile_number"; ?></h5></strong>
															<input type="hidden" id="const_mngr_idt" name="const_mngr_input" value="<?php echo $cons_mngr_user_id; ?>">

														<?php else: ?>		

															<div class="row">
																<div class="col-md-9" style="margin-left: 20px;">	
																	<select class="form-control chosen" id="const_mngr" name="const_mngr" tabindex="3" >

																		<?php

																			if ($proj_sched_details->contruction_manager_id == 0 && empty($manual_const_details)){
																				echo '<option value="" selected>Choose a Construction Manager...</option>';
																				foreach ($const_mngr_option as $row){
																					echo '<option value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.' - '.$row->mobile_number .'</option>';	
																				}
																				echo '<option value="other-offsite-superv">Other</option>';

																			} elseif (!empty($manual_const_details) && $proj_sched_details->contruction_manager_id == 0) {

																				echo '<option value="">Choose a Construction Manager...</option>';
																				foreach ($const_mngr_option as $row){
																					echo '<option value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.' - '.$row->mobile_number .'</option>';	
																				}
																				echo '<option value="other-offsite-superv" selected>Other</option>';
																			} else {
																				echo '<option value="">Choose a Construction Manager...</option>';
																				foreach ($const_mngr_option as $row){
																					if ($row->user_id == $row->user_id){
																						echo '<option value="'.$row->user_id.'" selected>'.$row->user_first_name.' '.$row->user_last_name.' - '.$row->mobile_number.'</option>';
																					} else {
																						echo '<option value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.' - '.$row->mobile_number .'</option>';
																					}
																				}
																				echo '<option value="other-offsite-superv">Other</option>';
																			}
																				
																		?>

																		</select>
																		<?php if($this->input->post('const_mngr')!=''): ?>
																			<script type="text/javascript">$("select#const_mngr").val("<?php echo $this->input->post('const_mngr'); ?>");</script>
																		<?php endif; ?>
																</div>
															</div>

															<div id="display_const_mngr" style="<?php echo ($proj_sched_details->contruction_manager_id != '0' || empty($manual_const_details)) ? 'display: none;' : ''; ?>">

																<div  class="row">
																	<div class="col-sm-9" style="margin-left: 20px;">
																		<h5 id="const_mngr_label" style="margin-left: 20px; text-transform: capitalize;"><strong><?php echo (!empty($manual_const_details)) ? $manual_const_details->cm_name.' - '.$manual_const_details->cm_contact : ''; ?></strong></h5>
																		
																		<input type="hidden" id="name_const_mngr_hidden" name="name_const_mngr_hidden" value="<?php echo (!empty($manual_const_details)) ? $manual_const_details->cm_name : ''; ?>">
																		<input type="hidden" id="mobile_no_const_mngr_hidden" name="mobile_no_const_mngr_hidden" value="<?php echo (!empty($manual_const_details)) ? $manual_const_details->cm_contact : ''; ?>">
																		<input type="hidden" id="const_mngr_label_hidden" name="const_mngr_label_hidden" value="<?php echo (!empty($manual_const_details)) ? $manual_const_details->cm_name .' - '.$manual_const_details->cm_contact : ''; ?>">
																	</div>	
																</div>

																<div class="row">
																	<div class="col-sm-9" style="margin-left: 20px;">
																	  	<button type="button" class="edit_const_mngr btn btn-warning pull-right">Edit</button>
																	</div>
																</div>
															</div>

															<br>

															<div id="manual_input_const_mngr" style="display: none;">

																<div class="row">
																	<div class="col-sm-9" style="margin-left: 20px; margin-bottom: 10px;">
																		<input type="text" class="form-control" id="name_const_mngr" placeholder="Name*" name="name_const_mngr" value="" style="text-transform: capitalize;">
																	</div>
																</div>

																<div class="row">
																	<div class="col-sm-9" style="margin-left: 20px;">
																		<input type="text" class="form-control" id="mobile_no_const_mngr" placeholder="Mobile Number*" name="mobile_no_const_mngr" value="">
																	</div>
																</div>

																<br>

																<div class="row">
																	<div class="col-sm-9" style="margin-left: 20px;">
																		<!-- <button type="button" class="btn btn-warning pull-right" style="margin-left: 5px;">Edit</button> -->
																		<button type="button" id="add_const_mngr" class="btn btn-warning pull-right">Insert</button>
																	</div>
																</div>
															</div>


														<?php endif; ?>
													</p>

													<p class="clearfix">
														<span class="text-left">Project Manager:</span>
														<strong><h5 id="project_manager" style="margin-left: 20px;"><?php echo "$pm_user_first_name $pm_user_last_name - $pm_mobile_number"; ?></h5></strong>
														<input type="hidden" id="project_manager" name="project_manager" value="<?php echo "$pm_user_first_name $pm_user_last_name - $pm_mobile_number"; ?>">
													</p>

													<p class="clearfix"></p>

													<p class="clearfix">
														<span class="text-left">FOCUS - Client Contact:</span>
														<strong><h5 id="project_cc_pm" style="margin-left: 20px;"><?php echo "$cc_pm_user_first_name $cc_pm_user_last_name - $cc_pm_mobile_number"; ?></h5></strong>
														<input type="hidden" id="project_cc_pm" name="project_cc_pm" value="<?php echo "$cc_pm_user_first_name $cc_pm_user_last_name - $cc_pm_mobile_number"; ?>">
													</p>
												</div>
											</div>
											
											<div class="col-md-12 pad-10" style="margin: 20px 0;">
												<h4 style="margin-bottom: 40px;"><i class="fa fa-file-image-o"></i> Uploaded Images here: <small><button type="button" id="saveImageGallery" class="btn btn-link" style="margin: 0;padding: 0;">[Save]</button></small> <small><button type="button" id="editImageGallery" class="btn btn-link" style="margin: 0;padding: 0;">[Edit]</button></small> <small><button type="button" id="cancelEditImage" class="btn btn-link" style="margin: 0;padding: 0;">[Done]</button></small></h4>
											

												<div class="select-report-image">
													<ul class="first" style="padding: 0px;">
													    
													    <?php 
													    	if (empty($pr_images)){
															  	echo '<h3 class="text-danger">No recent uploaded images...</h3>';
															} else {

																foreach ($pr_images as $row): 
														?>
															<li id="image_report_<?php echo $row['progress_report_images_id']; ?>" onclick="set_select('<?php echo $row['progress_report_images_id']; ?>');">
																<div id="<?php echo $row['progress_report_images_id']; ?>" class="overlay delete_image_list" style="display: none; position: absolute; left: 0px; top: 95px; z-index: 500">
																	<i class="fa fa-trash" style="color: #d9534f; font-size: 20px;"></i>
																</div>
																<div class="nopad text-center">
																    <label class="image-checkbox <?php echo ($row['is_select'] == '1') ? 'image-checkbox-checked' : ''; ?>">
			 															<div>
			 																<div class="edit_screen" style="display: none; width:100%; position: absolute;/*border: 4px solid #ea6503;*/z-index: 100;height: 100%;text-align: center;font-weight: bold;color: #fff;"><span style="margin-top: 39px;display: block;font-size: 17px;color: #ea6503;background: #fff;padding: 2px calc;">CLICK TO EDIT</span></div>
			 																<img class="img-responsive" src="<?php echo base_url().$row['image_path']; ?>"/>
			 																<strong><p class="text"><?php echo $row['image_label']; ?></p></strong>
			 															</div>
																      <input type="checkbox" name="image[]" value="" <?php echo ($row['is_select'] == '1') ? 'checked="1"' : ''; ?> />
																      <i class="fa fa-check <?php echo ($row['is_select'] == '1') ? '' : 'hidden'; ?>"></i>
																    </label>
																</div>
															</li>
														<?php 
														    	endforeach;
															}
													    ?>
													   	<!-- <input type="text" class="form-control text" value="<?php //echo $row['image_label']; ?>" /> -->
													</ul>
												</div>

											</div>

											<div class="col-md-11 pad-10 m-top-20">												
												<!-- <div class="col-md-offset-6 col-md-2 m-bottom-5">
													<button type="button" id="btnBack" class="btn btn-danger btn-block">Back</button>
												</div> -->
												<div class="col-md-offset-6 col-md-2 m-bottom-5">
													<!-- <a target="_blank" href="<?php //echo base_url(); ?>projects/progress_report_pdf/<?php //echo $project_id; ?>" class="btn btn-success btn-lg btn-block">Generate PDF</a> -->
													<button type="button" id="btnSaveDetails" class="btn btn-success btn-block">Save Details</button>
												</div>
												<div class="col-md-2 m-bottom-5">
													<!-- <a target="_blank" href="<?php //echo base_url(); ?>projects/progress_report_pdf/<?php //echo $project_id; ?>" class="btn btn-success btn-lg btn-block">Generate PDF</a> -->
													<button type="button" id="btnGeneratePDF" class="btn btn-danger btn-block">Generate PDF</button>
												</div>
												<div class="col-md-2 m-bottom-5">
													<!-- <a target="_blank" href="<?php //echo base_url(); ?>projects/progress_report_pdf/<?php //echo $project_id; ?>" class="btn btn-success btn-lg btn-block">Generate PDF</a> -->
													<button type="button" id="btnAttachSend" class="btn btn-info btn-block" data-toggle="modal" data-target="#attachSendModal">Send PDF</button>
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
									<p>
										Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis et odio ac sem suscipit luctus id in leo. Nunc consequat lorem lectus, sit amet scelerisque leo accumsan ut. Mauris rutrum lorem sed eros sagittis aliquet.
									</p>
								</div>
							</div>
						</div><!-- .col-md-3 -->
					</div><!-- .row -->
				</div><!-- .container-fluid -->
			</form>
		</div><!-- .section col-sm-12 col-md-11 col-lg-11 -->
	</div><!-- .row -->
</div><!-- .container-fluid -->

<!-- Modal -->
<div class="modal fade" id="attachSendModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
	    <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Sending Progress Report</h4>
			</div>
			<div class="modal-body">
				
				<div class="row">
					<div class="col-md-12">
						<div class="input-group">
						  <span class="input-group-addon">To:</span>
						  <input type="text" class="form-control" placeholder="Email Address 1; Email Address 2">
						</div>
					</div>
				</div>

				<div class="clearfix"><br></div>

				<div class="row">
					<div class="col-md-12">
						<span class="label label-default">The Progress Report is yet not generated. Please save and generate first before sending.</span>
						<!-- <span class="label label-success">The Progress Report is generated and attached. Ready for sending.</span> -->
					</div>
				</div>

				<div class="clearfix"><br></div>

				<div class="row">
					<div class="col-md-12">
						<textarea class="form-control" rows="8"></textarea>
					</div>
				</div>

				

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Send</button>
			</div>
	    </div>
	</div>
</div>

<!-- <script src="<?php //echo base_url(); ?>js/Jcrop/jquery-1.10.2.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script> -->
<script src="<?php echo base_url(); ?>js/bsPhotoGallery/jquery.bsPhotoGallery.js"></script>
<script src="<?php echo base_url(); ?>js/Jcrop/Jcrop.js"></script>

<script type="text/javascript">

	var edit_mode = 0;
	var text_replaced = 0;
	var image_orientation = 0;

	$(document).ready(function(){

		$('#saveImageGallery').hide();
		$('#cancelEditImage').hide();

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

		$('#saveImageGallery').click(function(){

			location.reload(true);

		});

		$('#editImageGallery').click(function(){

			$('.overlay').show();
			$('.edit_screen').show();
			$('#editImageGallery').hide();
			$('#cancelEditImage').show();
			$('#saveImageGallery').hide();

			// $(".image-checkbox").removeClass('image-checkbox-checked'); // REMOVING THE SELECT CLASS WHEN EDITING: by Mike 12-08-17
			// $(".image-checkbox > .fa.fa-check").addClass('hidden'); // HIDE THE CHECK ICON WHEN EDITING: by Mike 12-08-17

			edit_mode = 1;
		});

		$('#cancelEditImage').click(function(){		
			edit_mode = 0;

			$('#editImageGallery').show();
			$('#cancelEditImage').hide();
			$('.edit_screen').hide();
			$('.overlay').hide();


			// $(".image-checkbox").each(function () {
			//   if ($(this).find('input[type="checkbox"]').first().attr("checked")) {
			//    $(this).find('i.fa.fa-check').removeClass('hidden');
			//   } 
			// }); 
		});

		// image gallery
		// init the state from the input
		$(".image-checkbox").each(function () {
		  if ($(this).find('input[type="checkbox"]').first().attr("checked")) {
		    $(this).addClass('image-checkbox-checked');
		  } else {
		    $(this).removeClass('image-checkbox-checked');
		  }

		});

		// sync the state to the input
		$(".image-checkbox").on("click", function (e) {
			
			// $('#editImageGallery').hide();

			if (edit_mode == 0) {

				setTimeout(function(){

					$('#bsPhotoGalleryModal').modal('hide');
					$('#bsPhotoGalleryModal').css('width',0);
				  	$('#bsPhotoGalleryModal').hide();
				},0.1);

				// $('#saveImageGallery').show();
			  	
			  	$(this).toggleClass('image-checkbox-checked');
			  	$(this).find('.fa').toggleClass('hidden');

			  	var $checkbox = $(this).find('input[type="checkbox"]');
			  	
				// $(this).parent().parent().off("click"); // BEWARE: stopping the function for editing images - by Mike 12-08-17

			  	$checkbox.prop("checked",!$checkbox.prop("checked"));
			  	e.preventDefault();
			}
		});

		$('#add_to_project').click(function(){
			var leading_hand_id = $('select#leading_hand').val();
			var project_id = $('#project_id').val();
			var data = leading_hand_id+'|'+project_id;
			
			ajax_data(data,'projects/add_leading_hand_from_pr');
			alert('Successfully added a Leading Hand for this Project!');
			
			location.reload(true);
		});

		$('select#leading_hand').change(function() {

		  var selectedLH = leading_hand.options[leading_hand.selectedIndex].innerHTML;
		  var selectedLH_id = $('#leading_hand').val();

		  $('#leading_hand_input').val(selectedLH);
		  $('#leading_hand_id').val(selectedLH_id);
		});

		$('select#const_mngr').change(function() {
		  if ($('select#const_mngr').val() == 'other-offsite-superv'){
		  	$('#manual_input_const_mngr').show();
		  	$('#name_const_mngr').focus();
		  } else {
		  	$('#display_const_mngr').hide();
		  	$('#manual_input_const_mngr').hide();
		  }
		});

		$('#add_const_mngr').click(function(){
			var name_const_mngr = $('#name_const_mngr').val();
			var mobile_no_const_mngr = $('#mobile_no_const_mngr').val();

			if (name_const_mngr == '' || mobile_no_const_mngr == ''){
				alert('Please fill the required fields. (*)');
			} else {
				$('#display_name_const_mngr').val(name_const_mngr);
				$('#display_mobile_no_const_mngr').val(mobile_no_const_mngr);
				$('#display_const_mngr').show();
				$('#const_mngr_label > strong').text(name_const_mngr+' - '+mobile_no_const_mngr);
				$('#name_const_mngr_hidden').val(name_const_mngr);
				$('#mobile_no_const_mngr_hidden').val(mobile_no_const_mngr);
				$('#const_mngr_label_hidden').val(name_const_mngr+' - '+mobile_no_const_mngr);
				$('#manual_input_const_mngr').hide();
			}
		});

		$('.edit_const_mngr').click(function(){
			// $('#select_const_mngr').show();
			$('#manual_input_const_mngr').show();
			$('#display_const_mngr').hide();

			var name_const_mngr_val = $('#name_const_mngr_hidden').val();
			var mobile_no_const_mngr_val = $('#mobile_no_const_mngr_hidden').val();

			$('select#const_mngr').val('other-offsite-superv');
			$('#name_const_mngr').val(name_const_mngr_val);
			$('#mobile_no_const_mngr').val(mobile_no_const_mngr_val);
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

		$('#bsPhotoGalleryModal').on('shown.bs.modal', function (e) {

			$('#bsPhotoGalleryModal').css('width','auto');
			$('.modal-backdrop').css('background-color', 'none');

			$('#imageGallery.modal-dialog').css({ width: "inherit", height: "inherit" });

			imgWidth = $('#target').width();
			imgHeight = $('#target').height();

			// alert(imgWidth + ' ' + imgHeight);

			if (imgWidth <= '768'){
		   		$('#imgLabel').removeClass('col-md-8');
				$('#imgTools').removeClass('col-md-4');
		   	} else {
			   		$('#imgLabel').addClass('col-md-8');
					$('#imgTools').addClass('col-md-4');
			}

			var img_name;
			var path = $('img#target').attr('src');
			img_name = path.split("/").pop();

			// var URLremoveLastPart = RemoveLastDirectoryPartOf(path);

			// alert(img_name+' | '+path+' | '+URLremoveLastPart);

			// $('ul.image-tool-icons > li#undo').show();

			// $.ajax({
			//   url: URLremoveLastPart+'/backup_'+img_name, //or your url
			//   success: function(data){
			//     $('ul.image-tool-icons > li#undo').show();
			//   },
			//   error: function(data){
			//     $('ul.image-tool-icons > li#undo').hide();
			//   },
			// })

			image_editor_controls();

			$('img#target').on('load', function () {

				$('#imageGallery.modal-dialog').css({ width: "inherit", height: "inherit" });

				imgWidth = $('#target').width();
				imgHeight = $('#target').height();

				if (imgWidth <= '768'){
			   		$('#imgLabel').removeClass('col-md-8');
					$('#imgTools').removeClass('col-md-4');
			   	} else {
			   		$('#imgLabel').addClass('col-md-8');
					$('#imgTools').addClass('col-md-4');
			   	}

				// path = $('img#target').attr('src');
				// img_name = path.split("/").pop();

				// $('ul.image-tool-icons > li#undo').show();

				// $.ajax({
				//   url: URLremoveLastPart+'/backup_'+img_name, //or your url
				//   success: function(data){
				//     $('ul.image-tool-icons > li#undo').show();
				//   },
				//   error: function(data){
				//     $('ul.image-tool-icons > li#undo').hide();
				//   },
				// })

				// image_editor_controls();
			});

			var crop_x = 0;
			var crop_y = 0;
			var crop_width = 0;
			var crop_height = 0;

			function image_editor_controls(){

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
						aspectRatio: 0,
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

					$('ul.image-tool-icons > li#undo').show();
					$('ul.image-tool-icons > li#crop').show();
					$('ul.image-tool-icons > li#rotate-left').show();
					
					// $.ajax({
					//   url: URLremoveLastPart+'/backup_'+img_name, //or your url
					//   success: function(data){
					//     $('ul.image-tool-icons > li#undo').show();
					//   },
					//   error: function(data){
					//     $('ul.image-tool-icons > li#undo').hide();
					//   },
					// })

					$('ul.image-tool-icons > li#cancelCrop').hide();
					$('ul.image-tool-icons > li#check').hide();
					$('ul.image-tool-icons > li#saveCrop').hide();
					
					if ($('#target').Jcrop('api') == null){
						$('#target').width(imgWidth);
						$('#target').height(imgHeight);
						$('#targetCanvas').hide();

						if ($('#target').width() <= '768'){
					   		$('#imgLabel').removeClass('col-md-8');
							$('#imgTools').removeClass('col-md-4');
					   	} else {
					   		$('#imgLabel').addClass('col-md-8');
							$('#imgTools').addClass('col-md-4');
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
					saveCrop(img_name);
				});

				$('ul.image-tool-icons > li#cancelRotate').click(function(){
					$('span.bsp-close').show();
					$('a.bsp-controls.next').show();
					$('a.bsp-controls.previous').show();

					$('ul.image-tool-icons > li#undo').show();
					$('ul.image-tool-icons > li#crop').show();
					$('ul.image-tool-icons > li#rotate-left').show();
					
					// $.ajax({
					//   url: URLremoveLastPart+'/backup_'+img_name, //or your url
					//   success: function(data){
					//     $('ul.image-tool-icons > li#undo').show();
					//   },
					//   error: function(data){
					//     $('ul.image-tool-icons > li#undo').hide();
					//   },
					// })

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
					saveRotate(img_name);
				});

				$('ul.image-tool-icons > li#undo').click(function(){

					var path = $('img#target').attr('src');
					var URLremoveLastPart = RemoveLastDirectoryPartOf(path);

					img_name = path.split("/").pop();

					$.ajax({
					  url: URLremoveLastPart+'/backup_img/'+img_name, //or your url
					  success: function(data){
					    // $('ul.image-tool-icons > li#undo').show();
					    deleteEditedimages(img_name);
					  },
					  error: function(data){
					    // $('ul.image-tool-icons > li#undo').hide();
					    alert('There is no backup image for this! Undo is cancelled...');
					  },
					})
				});
			}

			function deleteEditedimages(img_name){

				var img_name;
			 	var project_id = $('#project_id').val();
				var rootFolder = '<?php echo $_SERVER['DOCUMENT_ROOT']; ?>';
				
				var img_path = rootFolder+'/uploads/project_progress_report/'+project_id+'/'+img_name; // live
				var backup_path = rootFolder+'/uploads/project_progress_report/'+project_id+'/backup_img'; // live

				// var img_path = rootFolder+'/public_html_dev/uploads/project_progress_report/'+project_id+'/'+img_name; // local
				// var backup_path = rootFolder+'/public_html_dev/uploads/project_progress_report/'+project_id+'/backup_img'; // local
				
				$.ajax({
					type: 'GET',
				  	url: '<?php echo base_url().'projects/delete_file'; ?>',
				  	data: {'file' : img_path,
				  	       'filename' : img_name,
				  	       'backup_path' : backup_path },
			      	dataType: 'json', 
			      	success: function (response) {
			         	if( response.status === true ) {
			             	alert('Restoring backup image...');				             	
			             	location.reload(true);
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

				$(".modal-dialog").width(crop_width).height(crop_height);
				$('#targetCanvas').attr({ width: crop_width, height: crop_height });
				var canvas = document.getElementById("targetCanvas");
			    var ctx = canvas.getContext("2d");
			    var img = document.getElementById("target");
			    ctx.drawImage(img, crop_x, crop_y, crop_width, crop_height, 0, 0, crop_width, crop_height);
				
			   	$('#target').hide();

			   	if ($('#targetCanvas').width() <= '768'){
			   		$('#imgLabel').removeClass('col-md-8');
					$('#imgTools').removeClass('col-md-4');
			   	} else {
			   		$('#imgLabel').addClass('col-md-8');
					$('#imgTools').addClass('col-md-4');
			   	}

			   	$('#targetCanvas').show();
			}

			var rotate_angle = '0';

			function rotateLeft(){
				var rotate_degrees = $('#target').css('transform'); // alert(rotate_degrees);

			 	if (rotate_degrees == 'none'){
			 		$('#target').css('transform','rotate(-90deg)');
			 		$('#imageGallery.modal-dialog').width(imgWidth);
				 	$('#imageGallery.modal-dialog').height(imgWidth);

				 	rotate_angle = '90';

			 	} else if (rotate_degrees == 'matrix(0, -1, 1, 0, 0, 0)') {
			 		$('#target').css('transform','rotate(-180deg)');
			 		$('#imageGallery.modal-dialog').width(imgWidth);
				 	$('#imageGallery.modal-dialog').height(imgHeight);

				 	rotate_angle = '180';

			 	} else if (rotate_degrees == 'matrix(-1, 0, 0, -1, 0, 0)') {
			 		$('#target').css('transform','rotate(-270deg)');
			 		$('#imageGallery.modal-dialog').width(imgWidth);
				 	$('#imageGallery.modal-dialog').height(imgWidth);

				 	rotate_angle = '270';

			 	} else {
			 		$('#target').css('transform','none');
			 		$('#imageGallery.modal-dialog').width(imgWidth);
				 	$('#imageGallery.modal-dialog').height(imgHeight);

				 	rotate_angle = '0';
			 	}
			}

			function saveCrop(img_name){
				if (crop_x == 0 && crop_y == 0 && crop_width == 0 && crop_height == 0){
					alert('Please select a cropping area.');
				} else {
					var project_id = $('#project_id').val();
					var rootFolder = '<?php echo $_SERVER['DOCUMENT_ROOT']; ?>';

					var crop_path = rootFolder+'/uploads/project_progress_report/'+project_id+'/'+img_name; // live
					var backup_path = rootFolder+'/uploads/project_progress_report/'+project_id+'/backup_img/'+img_name; // live
					
					// var crop_path = rootFolder+'/public_html_dev/uploads/project_progress_report/'+project_id+'/'+img_name; // local
					// var backup_path = rootFolder+'/public_html_dev/uploads/project_progress_report/'+project_id+'/backup_img/'+img_name; // local
					
			    	$('#confirmModal').modal('show');

			    	$('.modal-title.msgbox').text('Photo Orientation');
			    	$('#confirmModal .modal-dialog.modal-sm').css('width', '300px');
			    	$('#confirmModal .modal-dialog.modal-sm').css('height', '300px');
			    	
				    $('#confirmText').html('Save as (Landscape or Portrait)? <br><br><strong>[For PDF view and location of image.]</strong>');
			    	$('#confirmButtons').html('<button type="button" class="confirmOrientation0 btn btn-success btn-lg"><i class="fa fa-photo fa-lg"></i></button>' +
			                    			  '<button type="button" class="confirmOrientation1 btn btn-danger btn-lg"><i class="fa fa-file-photo-o fa-lg"></i></button>');

					$('button.confirmOrientation1').click(function(){

						set_image_orientation(1);

						var data = project_id+'|'+crop_x+'|'+crop_y+'|'+crop_width+'|'+crop_height+'|'+crop_path+'|'+crop_path+'|'+img_name+'|'+image_orientation+'|'+backup_path; //alert(data);

						$.ajax({
							'url' : '<?php echo base_url().'projects/crop_img'; ?>',
							'type' : 'POST',
							'data' : {'ajax_var' : data },
							'success' : function(data){
								$('#confirmModal').modal('hide');
								alert('Successfully cropped the image, applying changes...');
								location.reload(true);
							}
						});

					});

					$('button.confirmOrientation0').click(function(){

						set_image_orientation(0);

						var data = project_id+'|'+crop_x+'|'+crop_y+'|'+crop_width+'|'+crop_height+'|'+crop_path+'|'+crop_path+'|'+img_name+'|'+image_orientation+'|'+backup_path; //alert(data);

						$.ajax({
							'url' : '<?php echo base_url().'projects/crop_img'; ?>',
							'type' : 'POST',
							'data' : {'ajax_var' : data },
							'success' : function(data){
								$('#confirmModal').modal('hide');
								alert('Successfully cropped the image, applying changes...');
								location.reload(true);
							}
						});
					});
				}
			}

			function saveRotate(img_name){

				// alert(img_name)

				if (rotate_angle == '0'){
					alert('Please rotate the image.');
				} else {
					var project_id = $('#project_id').val();
					var rootFolder = '<?php echo $_SERVER['DOCUMENT_ROOT']; ?>';

					// var rotate_path = rootFolder+'/uploads/project_progress_report/'+project_id+'/'+img_name; // live
					// var backup_path = rootFolder+'/ploads/project_progress_report/'+project_id+'/backup_img/'+img_name; // local
					
					var rotate_path = rootFolder+'/public_html_dev/uploads/project_progress_report/'+project_id+'/'+img_name; // local
					var backup_path = rootFolder+'/public_html_dev/uploads/project_progress_report/'+project_id+'/backup_img/'+img_name; // local
					
			    	$('#confirmModal').modal('show');

			    	$('.modal-title.msgbox').text('Photo Orientation');
			    	$('#confirmModal .modal-dialog.modal-sm').css('width', '300px');
			    	$('#confirmModal .modal-dialog.modal-sm').css('height', '300px');
			    	
				    $('#confirmText').html('Save as (Landscape or Portrait)? <br><br><strong>[For PDF view and location of image.]</strong>');
			    	$('#confirmButtons').html('<button type="button" class="confirmOrientation0 btn btn-success btn-lg"><i class="fa fa-photo fa-lg"></i></button>' +
			                    			  '<button type="button" class="confirmOrientation1 btn btn-danger btn-lg"><i class="fa fa-file-photo-o fa-lg"></i></button>');

					$('button.confirmOrientation1').click(function(){

						set_image_orientation(1);

						var data = project_id+'|'+rotate_path+'|'+rotate_angle+'|'+img_name+'|'+image_orientation+'|'+backup_path; //alert(data);

						$.ajax({
							'url' : '<?php echo base_url().'projects/rotate'; ?>',
							'type' : 'POST',
							'data' : {'ajax_var' : data },
							'success' : function(data){
								$('#confirmModal').modal('hide');
								alert('Successfully rotated the image, applying changes...');
								location.reload(true);
							}
						});

					});

					$('button.confirmOrientation0').click(function(){

						set_image_orientation(0);

						var data = project_id+'|'+rotate_path+'|'+rotate_angle+'|'+img_name+'|'+image_orientation+'|'+backup_path; //alert(data);

						$.ajax({
							'url' : '<?php echo base_url().'projects/rotate'; ?>',
							'type' : 'POST',
							'data' : {'ajax_var' : data },
							'success' : function(data){
								$('#confirmModal').modal('hide');
								alert('Successfully rotated the image, applying changes...');
								location.reload(true);
							}
						});
					});

					
				}
			}

			function set_image_orientation(img_orientation){

				image_orientation = img_orientation;

				// alert(image_orientation);
			}

			function RemoveLastDirectoryPartOf(the_url){
			    var the_arr = the_url.split('/');
			    the_arr.pop();
			    return( the_arr.join('/') );
			}

			editLabelcontrols(img_name);
		});

		
		$('#bsPhotoGalleryModal').on('hide.bs.modal', function (e) {
			
			if (text_replaced == 1){
				alert('Applying changes...');
				location.reload(true);				
			}
		})


		$('#btnSaveDetails').click(function(){

			var name_const_mngr = '';
			var mobile_no_const_mngr_name = '';

			var leading_hand_id = $('#leading_hand_id').val();

			if ($('#display_const_mngr').is(':visible')) {
				var const_mngr = '0';
				name_const_mngr = $('#name_const_mngr_hidden').val();
				mobile_no_const_mngr_name = $('#mobile_no_const_mngr_hidden').val();					
			} else {

				if ($('select#const_mngr').val() != 'other-offsite-superv'){
					const_mngr = $('select#const_mngr').val();	
				} else {
					alert('Please fill the required fields. (*)');
					return false;
				}				
			}

			var scope_of_work = $('#scope_of_work').val();
			var project_id = $('#project_id').val();

			if (const_mngr == 0){
				data = leading_hand_id+'|'+const_mngr+'|'+scope_of_work+'|'+project_id+'|'+name_const_mngr+'|'+mobile_no_const_mngr_name; 
			} else {
				data = leading_hand_id+'|'+const_mngr+'|'+scope_of_work+'|'+project_id; 
			}

			// alert(data);

			if (const_mngr == '0' && name_const_mngr == '' && mobile_no_const_mngr_name == ''){
				alert('Please fill the required fields. (*)');
				return false;
			} else {

				$.ajax({
					'url' : '<?php echo base_url().'projects/update_pr_details'; ?>',
					'type' : 'POST',
					'data' : {'ajax_var' : data },
					'success' : function(data){
						alert('Successfully saved the Progress Report details, refreshing data...');
						location.reload(true);
						// $('#generate_pr_report').submit();
					}
				});
			}
		});

		$('#btnGeneratePDF').click(function(){
			$('#generate_pr_report').submit();
		});

	});
	
	function saveImageLbl(last_img_name, img_name){

		var lblImage = $('#lblImage').val();

		if (last_img_name.replace(/\s/g, '') != lblImage.replace(/\s/g, '')){
			text_replaced = 1;
		}

		data = lblImage+'|'+img_name;

		editLabelcontrols(img_name);

		$.ajax({
			'url' : '<?php echo base_url().'projects/edit_image_label'; ?>',
			'type' : 'POST',
			'data' : {'ajax_var' : data },
			'success' : function(data){

				// $('span#editLabel').show();
				$('p.pText').text(lblImage);
				// $('p.pText').html(lblImage+'<span id="editLabel" style="margin-left: 20px;"><i class="fa fa-pencil fa-lg"></i></span>');
				$('#lblImage').hide();
			}
		});
	}

	function confirmDelImage(image_id){

		var project_id = $('#project_id').val();

		data = image_id+'|'+project_id; 
		// alert(data);

		$.ajax({
			'url' : '<?php echo base_url().'projects/delete_pr_image'; ?>',
			'type' : 'POST',
			'data' : {'ajax_var' : data },
			'success' : function(data){
				$('#confirmModal').modal('hide');
				alert('Successfully deleted the image.');
				$('#image_report_'+image_id).remove();

			}
		});
	}

	function set_select(image_id){

		if (edit_mode == 0){
			var project_id = $('#project_id').val();

			data = image_id+'|'+project_id; // alert(data);
			
			$.ajax({
				'url' : '<?php echo base_url().'projects/set_select_image'; ?>',
				'type' : 'POST',
				'data' : {'ajax_var' : data },
				'success' : function(data){
				}
			});
		}
	}

	function editLabelcontrols(img_name){

		$('span#editLabel > i').click(function(){
			$('p.pText').hide();
			$('p.pText.lblInput').show();
		});

		$('#lblImage').keypress(function (e) {
		 var key = e.which;
		 if(key == 13)  // the enter key code
		  {

		  	var last_img_name = $('#imgLabel > p.pText').text();

		    saveImageLbl(last_img_name, img_name);
		  }
		});  

		$('span#cancelEditlbl > i').click(function(){
			$('p.pText').show();
			$('p.pText.lblInput').hide();
		});

		$('span#saveEditlbl > i').click(function(){

			var last_img_name = $('#imgLabel > p.pText').text();

			saveImageLbl(last_img_name, img_name);
		});
	}

</script>