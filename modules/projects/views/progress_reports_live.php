<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>

<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"> -->

<?php 
	// if($this->session->userdata('is_admin') != 1 ): 
	// redirect('/projects/', 'refresh');
	// endif; 

	$user_access_arr = explode(',',  $this->users->get_user_access($this->session->userdata('user_id')) );
 	$progress_report = $user_access_arr['22'];

 	if ($progress_report != 1) {
		redirect(base_url(), 'refresh');
	}

	$user_prepared = ucfirst($this->session->userdata('user_first_name')).' '.ucfirst($this->session->userdata('user_last_name'));
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
          
					<li>
						<a class="btn-small sb-open-right"><i class="fa fa-file-text-o"></i> Project Comments</a>
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

									<?php if(@$this->session->flashdata('pr_images')): ?>
										<div class="no-pad-t m-bottom-10 pad-left-10">
											<div class="border-less-box alert alert-success fade in">
												<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
												<h4>Cheers!</h4>
												<?php echo $this->session->flashdata('pr_images');?>
											</div>
										</div>
									<?php endif; ?>

									<?php if(@$error): ?>
										<div class="pad-10 no-pad-t">
											<div class="border-less-box alert alert-danger fade in">
												<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
												<h4>Oh snap! You got an error!</h4>
												<?php echo $error;?>
											</div>
										</div>
									<?php endif; ?>


									<input type="hidden" id="end_user_email" name="end_user_email" value="<?php echo $end_user_email; ?>">

									<div class="box m-bottom-15 clearfix">
										<div class="box-head pad-5 m-bottom-5">
											<label><i class="fa fa-book fa-lg"></i> General - <span class="label label-danger">Progress Report <?php echo $pr_details->pr_version; ?></span></label> <!-- display here the version number of PR -->
											<input type="hidden" id="pr_version" name="pr_version" value="<?php echo $pr_details->pr_version; ?>">

										</div>
										
										<div class="box-area pad-5 clearfix">
											<div class="col-md-12">
												<div class="pad-15 no-pad-t">	

													<input type="hidden" id="user_full_name" name="user_full_name" value="<?php echo $user_prepared ?>">
													<input type="hidden" id="project_id" name="project_id" value="<?php echo $project_id; ?>">

													<div class="col-md-6">
														<span class="text-left">Project Name:</span>
														<h3 style="margin-left: 20px;"><label for="project_name" class="text-left"><?php echo $project_name; ?></label></h3>
														<input type="hidden" id="project_name" name="project_name" value="<?php echo $project_name; ?>">
													</div>

													<div class="col-md-6">
														<span class="text-left">Client:</span>
														<h3 style="margin-left: 20px;"><label for="client_company_name" class="text-left"><?php echo $client_company_name; ?></label></h3>
														<input type="hidden" id="client_company_name" name="client_company_name" value="<?php echo $client_company_name; ?>">
													</div>

													<div class="clearfix"></div>

													<h4><i class="fa fa-map-marker"></i> Site Address:</h4>
													<?php $shop_tenancy_numb = ($job_type != 'Shopping Center' ? '' : ''.$shopping_common_name.': '.$shop_tenancy_number); ?>
													<?php $unit_level =  ($unit_level != '' ? 'Unit/Level:'.$unit_level.',' : '' ); ?>
													<h5 style="margin-left: 20px;"><p id="site_address1"><?php echo "$shop_tenancy_numb $unit_level $unit_number $street, $suburb, $state, $postcode"; ?></p></h5>
													<input type="hidden" id="site_address1" name="site_address1" value="<?php echo "$shop_tenancy_numb $unit_level $unit_number $street, $suburb, $state, $postcode"; ?>">
													
													<div class="clearfix"><br></div>

													<h4><i class="fa fa-users"></i> Contact Person:</h4>
													
													<div class="clearfix"><br></div>

													<div class="col-md-6 pad-15 no-pad-t">
														<p class="clearfix">
															<span class="text-left">Project Manager:</span>
															<strong><h5 id="project_manager" style="margin-left: 20px;"><?php echo "$pm_user_first_name $pm_user_last_name - $pm_mobile_number"; ?></h5></strong>
															<input type="hidden" id="project_manager" name="project_manager" value="<?php echo "$pm_user_first_name $pm_user_last_name - $pm_mobile_number"; ?>">
														</p>
													</div>

													<div class="col-md-6 pad-15 no-pad-t">
														<p class="clearfix">
															<span class="text-left">FOCUS - Client Contact:</span>
															<strong><h5 id="project_cc_pm" style="margin-left: 20px;"><?php echo "$cc_pm_user_first_name $cc_pm_user_last_name - $cc_pm_mobile_number"; ?></h5></strong>
															<input type="hidden" id="project_cc_pm" name="project_cc_pm" value="<?php echo "$cc_pm_user_first_name $cc_pm_user_last_name - $cc_pm_mobile_number"; ?>">
														</p>
													</div>

													<div class="col-md-6 pad-15 no-pad-t">
														<p class="clearfix">
															<span class="text-left">Leading Hand:</span>

															<?php if (!empty($leading_hand_id)): ?>
																
																<strong><h5 id="leading_hand" style="margin-left: 20px;"><?php echo "$leading_hand_user_first_name $leading_hand_user_last_name - $leading_hand_mobile_number"; ?></h5></strong>
																<input type="hidden" id="leading_hand_idt" name="leading_hand_input" value="<?php echo $leading_hand_user_id; ?>">

															<?php else: ?>	

																<!-- COMBO BOX LEADING HAND -->

																<div class="row">
																	<div class="col-md-9" style="margin-left: 20px;">	
																		<select class="form-control chosen" id="leading_hand" name="leading_hand" tabindex="2">														
																			
																			<?php

																				if ($proj_sched_details->leading_hand_id == 0 && empty($manual_const_details)){
																					
																					echo '<option value="" selected>Choose a Leading Hand...</option>';

																					foreach ($lead_hand_option as $row){
																						echo '<option value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.' - '.$row->mobile_number .'</option>';	
																					}

																					echo '<option value="other_leading_hand">Other</option>';

																				} elseif (!empty($manual_const_details) && $proj_sched_details->leading_hand_id == 0) {

																					echo '<option value="">Choose a Leading Hand...</option>';

																					foreach ($lead_hand_option as $row){
																						echo '<option value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.' - '.$row->mobile_number .'</option>';	
																					}

																					echo '<option value="other_leading_hand" selected>Other</option>';
																				} else {

																					echo '<option value="">Choose a Leading Hand...</option>';

																					foreach ($lead_hand_option as $row){
																						if ($lead_hand_user_id == $row->user_id){
																							echo '<option value="'.$row->user_id.'" selected>'.$row->user_first_name.' '.$row->user_last_name.' - '.$row->mobile_number.'</option>';
																						} else {
																							echo '<option value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.' - '.$row->mobile_number .'</option>';
																						}
																					}

																					echo '<option value="other_leading_hand">Other</option>';

																					// echo '<input type="hidden" id="leading_hand_id" name="leading_hand_id" value="'.$proj_sched_details->leading_hand_id.'">';
																				}
																					
																			?>

																			<?php 
																			//	foreach ($lead_hand_option as $row){ 
																				// if ($lead_hand_user_id == $row->user_id){
																				// 	echo '<option value="'.$row->user_id.'" selected>'.$row->user_first_name.' '.$row->user_last_name.' - '.$row->mobile_number.'</option>';
																				// } else {
																				// 	echo '<option value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.' - '.$row->mobile_number.'</option>';
																				// } 

																			 //  } 
																			?>

																		</select>

																		<?php if($this->input->post('leading_hand')!=''): ?>
																			<script type="text/javascript">$("select#leading_hand").val("<?php echo $this->input->post('leading_hand'); ?>");</script>
																		<?php endif; ?>
																	</div>
																</div>

																<!-- DISPLAY OF MANUALLY INPUTTED LEADING HAND -->

																<!-- <div id="display_leading_hand" style="<?php //echo ($proj_sched_details->leading_hand_id != '0' || empty($manual_const_details)) ? 'display: none;' : ''; ?>">

																	<div  class="row">
																		<div class="col-sm-9" style="margin-left: 20px;">
																			<h5 id="leading_hand_label" style="margin-left: 20px; text-transform: capitalize;"><strong><?php //echo (!empty($manual_const_details)) ? $manual_const_details->lh_name.' - '.$manual_const_details->lh_contact : ''; ?></strong></h5>
																			
																			<input type="hidden" id="name_leading_hand_hidden" name="name_leading_hand_hidden" value="<?php //echo (!empty($manual_const_details)) ? $manual_const_details->lh_name : ''; ?>">
																			<input type="hidden" id="mobile_no_leading_hand_hidden" name="mobile_no_leading_hand_hidden" value="<?php //echo (!empty($manual_const_details)) ? $manual_const_details->lh_contact : ''; ?>">
																			<input type="hidden" id="leading_hand_label_hidden" name="leading_hand_label_hidden" value="<?php //echo (!empty($manual_const_details)) ? $manual_const_details->lh_name .' - '.$manual_const_details->lh_contact : ''; ?>">
																		</div>	
																	</div>

																	<div class="row">
																		<div class="col-sm-9" style="margin-left: 20px;">
																		  	<button type="button" class="edit_leading_hand btn btn-warning pull-right">Edit</button>
																		</div>
																	</div>
																</div> -->

																<br>

																<!-- MANUAL INPUT LEADING HAND -->

																<div id="manual_input_leading_hand" style="<?php echo ($proj_sched_details->leading_hand_id != '0' || empty($manual_const_details)) ? 'display: none;' : ''; ?>">

																	<div class="row">
																		<div class="col-sm-9" style="margin-left: 20px; margin-bottom: 10px;">
																			<input type="text" class="form-control" id="name_leading_hand" placeholder="Name*" name="name_leading_hand" value="<?php echo (!empty($manual_const_details)) ? $manual_const_details->lh_name : ''; ?>" style="text-transform: capitalize;">
																		</div>
																	</div>

																	<div class="row">
																		<div class="col-sm-9" style="margin-left: 20px;">
																			<input type="text" class="form-control" id="mobile_no_leading_hand" placeholder="Mobile Number*" name="mobile_no_leading_hand" value="<?php echo (!empty($manual_const_details)) ? $manual_const_details->lh_contact : ''; ?>">
																		</div>
																	</div>

																	<br>

																	<!-- <div class="row">
																		<div class="col-sm-9" style="margin-left: 20px;">
																			<button type="button" id="add_leading_hand" class="btn btn-warning pull-right">Insert</button>
																		</div>
																	</div> -->
																</div>

															<?php endif; ?>
														</p>

													</div>

													<div class="col-md-6 pad-15 no-pad-t">

														<p class="clearfix">
															<span class="text-left">Construction Manager:</span>

															<?php if (!empty($const_mngr_id)): ?>
																
																<strong><h5 id="const_mngr" style="margin-left: 20px;"><?php echo "$cons_mngr_user_first_name $cons_mngr_user_last_name - $cons_mngr_mobile_number"; ?></h5></strong>
																<input type="hidden" id="const_mngr_idt" name="const_mngr_input" value="<?php echo $cons_mngr_user_id; ?>">

															<?php else: ?>		

																<!-- COMBO BOX CONSTRUCTION MANAGER -->

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
																						if ($cons_mngr_user_id == $row->user_id){
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

																<!-- DISPLAY OF MANUALLY INPUTTED CONSTRUCTION MANAGER -->

																<!-- <div id="display_const_mngr" style="<?php //echo ($proj_sched_details->contruction_manager_id != '0' || empty($manual_const_details)) ? 'display: none;' : ''; ?>">

																	<div  class="row">
																		<div class="col-sm-9" style="margin-left: 20px;">
																			<h5 id="const_mngr_label" style="margin-left: 20px; text-transform: capitalize;"><strong><?php //echo (!empty($manual_const_details)) ? $manual_const_details->cm_name.' - '.$manual_const_details->cm_contact : ''; ?></strong></h5>
																			
																			<input type="hidden" id="name_const_mngr_hidden" name="name_const_mngr_hidden" value="<?php //echo (!empty($manual_const_details)) ? $manual_const_details->cm_name : ''; ?>">
																			<input type="hidden" id="mobile_no_const_mngr_hidden" name="mobile_no_const_mngr_hidden" value="<?php //echo (!empty($manual_const_details)) ? $manual_const_details->cm_contact : ''; ?>">
																			<input type="hidden" id="const_mngr_label_hidden" name="const_mngr_label_hidden" value="<?php //echo (!empty($manual_const_details)) ? $manual_const_details->cm_name .' - '.$manual_const_details->cm_contact : ''; ?>">
																		</div>	
																	</div>

																	<div class="row">
																		<div class="col-sm-9" style="margin-left: 20px;">
																		  	<button type="button" class="edit_const_mngr btn btn-warning pull-right">Edit</button>
																		</div>
																	</div>
																</div> -->

																<br>

																<!-- MANUAL INPUT CONSTRUCTION MANAGER -->

																<div id="manual_input_const_mngr" style="<?php echo ($proj_sched_details->contruction_manager_id != '0' || empty($manual_const_details)) ? 'display: none;' : ''; ?>">

																	<div class="row">
																		<div class="col-sm-9" style="margin-left: 20px; margin-bottom: 10px;">
																			<input type="text" class="form-control" id="name_const_mngr" placeholder="Name*" name="name_const_mngr" value="<?php echo (!empty($manual_const_details)) ? $manual_const_details->cm_name : ''; ?>" style="text-transform: capitalize;">
																		</div>
																	</div>

																	<div class="row">
																		<div class="col-sm-9" style="margin-left: 20px;">
																			<input type="text" class="form-control" id="mobile_no_const_mngr" placeholder="Mobile Number*" name="mobile_no_const_mngr" value="<?php echo (!empty($manual_const_details)) ? $manual_const_details->cm_contact : ''; ?>">
																		</div>
																	</div>

																	<br>

																	<!-- <div class="row">
																		<div class="col-sm-9" style="margin-left: 20px;">
																			<button type="button" id="add_const_mngr" class="btn btn-warning pull-right">Insert</button>
																		</div>
																	</div> -->
																</div>


															<?php endif; ?>
														</p>
													</div>

													<div class="clearfix"><br></div>

													<div class="box">
														<div class="box-head pad-5">

															<label for="project_notes"><i class="fa fa-pencil-square fa-lg"></i> Works Progress Details <small>(Click 'Update Details' after editing this area.)</small></label>
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

													<div class="col-md-offset-10 col-md-2 m-top-20 m-bottom-5">
														<button type="button" id="btnSaveDetails" class="btn btn-warning btn-block"><i class="fa fa-save"></i> Update Details</button>
													</div>
												</div>
											</div>

											
											
											<div class="col-md-12 pad-10" style="margin: 20px 0;">
												<h4 style="margin-bottom: 40px;"><i class="fa fa-file-image-o"></i> Uploaded Images: <small>(After done editing photos, Select Photos that you want to include in the PDF.) - </small> 


													<!-- <small><button type="button" id="saveImageGallery" class="btn btn-link" style="margin: 0;padding: 0;">[Save]</button></small>  -->

													<small><button type="button" id="selectImageGallery" class="btn btn-link tooltip-test" style="margin: 0;padding: 0;" title="Select photos that you want to include in the PDF.">[Select Photos]</button></small>

													<small><button type="button" id="editImageGallery" class="btn btn-link tooltip-test" style="margin: 0;padding: 0;" title="Crop, Rotate, Put labels and Select Group on your Photos.">[Edit Photos]</button></small> 

													<!-- <small><button type="button" id="cancelEditImage" class="btn btn-link" style="margin: 0;padding: 0;">[Done]</button></small> --> 

													<!-- <small><button type="button" id="addNewPhotos" class="btn btn-link tooltip-test" data-toggle="modal" data-target="#addMorePhotos" style="margin: 0;padding: 0;" title="Add More Photos here.">[Add New Photos]</button></small> -->

												</h4>
											

												<div class="select-report-image">
													<ul class="first" style="padding: 0px;">
													    
													    <?php 
													    	if (empty($pr_images)){
															  	echo '<h3 class="text-danger">No recent uploaded images...</h3>';
															} else {

																foreach ($pr_images as $row): 
														?>

															<input type="hidden" id="<?php echo preg_replace('/\\.[^.\\s]{3,4}$/', '', basename($row['image_path'])); ?>_group" name="group_selected" value="<?php echo ($row['group_id'] == NULL ? '0' : $row['group_id']); ?>">

															<li id="image_report_<?php echo $row['progress_report_images_id']; ?>" onclick="set_select('<?php echo $row['progress_report_images_id']; ?>');">

																

																<div id="<?php echo $row['progress_report_images_id']; ?>" class="overlay delete_image_list" style="display: block; position: absolute; left: 0px; top: 95px; z-index: 500">
																	<i class="fa fa-trash" style="color: #d9534f; font-size: 20px;"></i>
																</div>

																	<div class="nopad text-center">
				 															
				 															<div><!-- start -->

				 																<div class="<?php echo preg_replace('/\\.[^.\\s]{3,4}$/', '', basename($row['image_path'])); ?> edit_screen" style="display: block; width:100%; position: absolute;z-index: 100;height: 100%;text-align: center;font-weight: bold;color: #fff;">
				 																	<span style="margin-top: 39px;display: block;font-size: 17px; <?php echo ($row['is_edited'] == 0 ? 'color: #ea6503;' : 'color: #71657f;'); ?> background: #fff;padding: 2px;">CLICK TO VIEW</span>
				 																</div>

			 																	<label class="image-checkbox <?php echo ($row['is_select'] == '1') ? 'image-checkbox-checked' : ''; ?>">

			 																		<div class="select_screen" style="display: none; width:100%; position: absolute;z-index: 100;height: 100%;text-align: center;font-weight: bold;color: #fff;">
					 																	<span style="margin-top: 39px;display: block;font-size: 17px;color: #245580;background: #fff;padding: 2px;">CLICK TO SELECT</span>
					 																</div>

					 																<img class="img-responsive" src="<?php echo base_url().$row['image_path']; ?>"/>
					 																<strong><p class="<?php echo preg_replace('/\\.[^.\\s]{3,4}$/', '', basename($row['image_path'])); ?> text"><?php echo !empty($row['image_label']) ? $row['image_label'] : '(No Image Label)'; ?></p></strong>

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

											</div>

											<div class="col-md-11 pad-10 m-top-20">
												<div class="col-md-offset-6 col-md-2 m-bottom-5">
													<button type="button" id="btnGeneratePDF" class="btn btn-danger btn-block"><i class = "fa fa-cogs"></i> Generate PDF</button>
												</div>
			</form>
												<div class="col-md-2 m-bottom-5">
													<button type="button" id="btnAttachSend" class="btn btn-info btn-block"><i class = "fa fa-send"></i> Send PDF</button>
												</div>

												<div class="col-md-2 m-bottom-5">
													<form action="<?php echo base_url(); ?>projects/do_upload/<?php echo $project_id ?>" method="post" enctype="multipart/form-data">

														<input type="hidden" name="upload_path" value="<?php echo 'uploads/project_progress_report/'.$project_id.'/' ?>">

														<span class="btn btn-primary btn-block btn-file">
													    	<i class = "fa fa-plus-circle"></i> Add Photos<input type="file" name="userfile[]" multiple="multiple" accept="image/*" onchange="form.submit()">
														</span>
													</form>
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
										<li>After editing photos and you click the 'Save Changes', note that the images are clickable, select images you want to include in the Progress Report.</li>
										<li>'Update Details' button are for Leading Hand, Construction Manager and Works Progress Details data save.</li>
									</ul>
								</div>
							</div>

							<div class="box">
								<div class="box-head pad-5">
									<label><i class="fa fa-file-pdf-o fa-lg"></i> Progress Report History</label>
								</div>
								<div class="box-area pad-5">
									<ul>
										<?php

											if (empty($pr_versions)){
											  	echo '<h5 class="text-danger">No recent progress report generated...</h3>';
											} else {
												foreach ($pr_versions as $row) {
													echo '<li><a href="#" onclick="open_pr_pdf('.$row['pr_version'].');">Progress Report '.$row['pr_version'].'</a></li>';

												}
											}
										?>
									</ul>
								</div>
							</div>

						</div><!-- .col-md-3 -->
					</div><!-- .row -->
				</div><!-- .container-fluid -->
			<!-- </form> -->
		</div><!-- .section col-sm-12 col-md-11 col-lg-11 -->
	</div><!-- .row -->
</div><!-- .container-fluid -->

<!-- Modal -->
<div class="modal fade" id="attachSendModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width: 800px;">
	    <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Sending Progress Report <?php echo $pr_details->pr_version; ?> </h4>

			</div>
			<div class="modal-body">
				
				<div class="row">
				    <label for="send_to" class="col-md-2 control-label">Send To:</label>
				    <div class="col-md-10">

				    	<select class="form-control chosen" id="sendpdf_main_to" name="leading_hand" tabindex="1">	
				    		<option value="">Choose Email Address...</option>
				    		<option value="alt_emails">Input Alternate Email Address...</option>
				    		<option value="0|<?php echo $pm_email; ?>"><?php echo $pm_user_first_name.' '.$pm_user_last_name.' (PM) - '.$pm_email; ?><strong></option>
				    		<option value="1|<?php echo $contact_person_email_pr; ?>"><?php echo $contact_person_fname.' '.$contact_person_lname.' (Client) - '.$contact_person_email_pr; ?></option>
				    		<option value="<?php echo $pm_email.', '.$contact_person_email_pr; ?>">Both <?php echo $pm_user_first_name.' '.$pm_user_last_name.' (PM)'; ?> and <?php echo $contact_person_fname.' '.$contact_person_lname.' (Client)'; ?></option>
						</select>

				    </div>

				    <div class="clearfix"><br></div>

				</div>

				<div id="other_emails_wrap" class="row" style="display: none;">

					<div class="clearfix"><br></div>

					<div class="col-md-12">
						<div class="input-group">
						  <span id="other_email_name" class="input-group-addon">Alternate Emails:</span>
						  <input type="text" id="sendpdf_other_emails" class="form-control" placeholder="sample1@yahoo.com, sample2@gmail.com">
						  <span class="input-group-addon"><i class="fa fa-info-circle fa-lg tooltip-test" title="Use comma(,) to separate the email"></i></span>
						</div>
					</div>
				</div>

				<div id="check_client_wrap" class="row" style="display: none;">
					<div class="col-md-10 col-md-offset-2">
						<div class="checkbox">

							    <label>
							     	<input type="checkbox" id="is_client" name="is_client"> Is this the client? <i class="fa fa-info-circle fa-lg tooltip-test" title="If this is checked, raw photos will be deleted and this progress report will be archived."></i></small>
							    </label>
						</div>
					</div>
				</div>

				<div class="row">

					<div class="clearfix"><br></div>

					<div class="col-md-12">
						<div class="input-group">
						  <span class="input-group-addon">Subject:</span>
						  <input type="text" id="sendpdf_subject" class="form-control" value="Progress Report <?php echo $pr_details->pr_version.' - '. $project_name; ?>">
						</div>
					</div>
				</div>

				<div class="clearfix"><br></div>

				<div class="row">
					<div class="col-md-12">
						<span id="noPDF" class="label label-danger" style="display: none;">The current Progress Report is yet not generated. Please save and generate first before sending.</span>
						<span id="yesPDF" class="label label-success" style="display: none;">The current Progress Report is generated and attached. Ready for sending.</span>
						<span id="lastPDF" class="label label-info" style="display: none;">Last generated Progress Report PDF is attached. Ready for sending.</span>
					</div>
				</div>

				<div class="clearfix"><br></div>

				<div class="row">
					<div class="col-md-12">
						<textarea id="sendpdf_body" class="form-control" rows="10"></textarea>
					</div>
				</div>

				<div class="clearfix"><br></div>

				<div class="clearfix col-xs-12 text-center"><strong> <p><i class="fa fa-quote-left"></i> You can download the recent Progress Report PDF in the 'Progress Report History' on the right side of the Page or send it again if the current Progress Report is not generated.. <i class="fa fa-quote-right"></i></p></strong></div>

				<br>
			</div>

			<div class="modal-footer">
				<button type="button" id="sendpdf_close" class="btn btn-danger" data-dismiss="modal">Close</button>
				<button type="button" id="sendpdf_btn" class="btn btn-primary">Send</button>
			</div>
	    </div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade bs-example-modal-sm" id="addGroupModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="z-index: 9999;">
	<div class="modal-dialog modal-sm">
	    <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Add Group</h4>
			</div>
			<div class="modal-body">
				
				<div class="row">
					<div class="col-md-12">
						<div class="input-group">
						  <span class="input-group-addon">Add Group:</span>
						  <input type="text" id="addGroupTxtbox" class="form-control" placeholder="">
						</div>
					</div>
				</div>

			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				<button type="button" id="addGroupBtn" class="btn btn-primary">Add Group</button>
			</div>
	    </div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="addMorePhotos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Add Photos Here</h4>
			</div>
			<div class="modal-body">
				
				<?php //echo $error;?>

				<?php echo form_open_multipart('upload/do_upload');?>

					<input type="file" name="userfile" size="20" />

					<br /><br />

					<input type="submit" value="upload" />

				</form>

			</div>
			<div class="modal-footer">
				<!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> -->
				<button type="button" class="btn btn-success" data-dismiss="modal">Done</button>
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

<!-- <script src="<?php //echo base_url(); ?>js/Jcrop/jquery-1.10.2.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script> -->
<?php $this->load->view('assets/logout-modal'); ?>
<script src="<?php echo base_url(); ?>js/bsPhotoGallery/jquery.bsPhotoGallery.js"></script>
<script src="<?php echo base_url(); ?>js/Jcrop/Jcrop.js"></script>
<script src="<?php echo base_url(); ?>js/dropzone/dropzone.js"></script>

<script type="text/javascript">

	var select_mode = 0;
	var edit_mode = 1;
	var text_replaced = 0;
	var image_orientation = 0;
	var is_pdf_attached = 0;
	var last_img_name = '';

	$(document).ready(function(){

		if (edit_mode == 1){
			$('.select-report-image .image-checkbox-checked .fa').hide();
			$('#btnGeneratePDF').prop('disabled', true);
			// $('#btnAttachSend').prop('disabled', true);
			$('#editImageGallery').hide();
		}

		$('.groupImg').click(function(){
			setTimeout(function(){

				$('#bsPhotoGalleryModal').modal('hide');
				$('#bsPhotoGalleryModal').css('width',0);
			  	$('#bsPhotoGalleryModal').hide();
			},0.1);

		});

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

		$('#selectImageGallery').click(function(){

			$('#editImageGallery').show();
			$('#selectImageGallery').hide();
			$('#btnGeneratePDF').prop('disabled', false);
			// $('#btnAttachSend').prop('disabled', false);
			$('.overlay').hide();
			$('.select_screen').show();
			$('.edit_screen').hide();
			
			$('.select-report-image .image-checkbox-checked .fa').show();
			// $(".image-checkbox").removeClass('image-checkbox-checked'); // REMOVING THE SELECT CLASS WHEN EDITING: by Mike 12-08-17
			// $(".image-checkbox > .fa.fa-check").addClass('hidden'); // HIDE THE CHECK ICON WHEN EDITING: by Mike 12-08-17

			select_mode = 1;
		});

		$('#editImageGallery').click(function(){

			$('#selectImageGallery').show();
			$('#editImageGallery').hide();
			$('#btnGeneratePDF').prop('disabled', true);
			// $('#btnAttachSend').prop('disabled', true);
			$('.overlay').show();
			$('.edit_screen').show();
			$('.select_screen').hide();

			$('.select-report-image .image-checkbox-checked .fa').hide();
			// $(".image-checkbox").removeClass('image-checkbox-checked'); // REMOVING THE SELECT CLASS WHEN EDITING: by Mike 12-08-17
			// $(".image-checkbox > .fa.fa-check").addClass('hidden'); // HIDE THE CHECK ICON WHEN EDITING: by Mike 12-08-17

			select_mode = 0;
		});

		$('#cancelEditImage').click(function(){		
			select_mode = 0;

			$('#editImageGallery').show();
			$('#cancelEditImage').hide();
			$('.edit_screen').hide();
			$('.overlay').hide();
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
			
			if (select_mode == 1) {

				setTimeout(function(){

					$('#bsPhotoGalleryModal').modal('hide');
					$('#bsPhotoGalleryModal').css('width',0);
				  	$('#bsPhotoGalleryModal').hide();
				},0.1);

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

		/* ================ Leading Hand functions ================ */

		$('select#leading_hand').change(function() {

		  var selectedLH = leading_hand.options[leading_hand.selectedIndex].innerHTML;
		  var selectedLH_id = $('#leading_hand').val();

		  $('#leading_hand_input').val(selectedLH);
		  $('#leading_hand_id').val(selectedLH_id);

		  if ($('select#leading_hand').val() == 'other_leading_hand'){
		  	$('#manual_input_leading_hand').show();
		  	$('#name_leading_hand').focus();
		  } else {
		  	$('#display_leading_hand').hide();
		  	$('#manual_input_leading_hand').hide();
		  }

		});

		$('#add_leading_hand').click(function(){
			var name_leading_hand = $('#name_leading_hand').val();
			var mobile_no_leading_hand = $('#mobile_no_leading_hand').val();

			if (name_leading_hand == '' || mobile_no_leading_hand == ''){
				alert('Please fill the required fields. (*)');
			} else {
				$('#display_name_leading_hand').val(name_leading_hand);
				$('#display_mobile_no_leading_hand').val(mobile_no_leading_hand);
				$('#display_leading_hand').show();
				$('#leading_hand_label > strong').text(name_leading_hand+' - '+mobile_no_leading_hand);
				$('#name_leading_hand_hidden').val(name_leading_hand);
				$('#mobile_no_leading_hand_hidden').val(mobile_no_leading_hand);
				$('#leading_hand_label_hidden').val(name_leading_hand+' - '+mobile_no_leading_hand);
				$('#manual_input_leading_hand').hide();
			}
		});

		$('.edit_leading_hand').click(function(){
			$('#manual_input_leading_hand').show();
			$('#display_leading_hand').hide();

			var name_leading_hand_val = $('#name_leading_hand_hidden').val();
			var mobile_no_leading_hand_val = $('#mobile_no_leading_hand_hidden').val();

			$('select#leading_hand').val('other_leading_hand');
			$('#name_leading_hand').val(name_leading_hand_val);
			$('#mobile_no_leading_hand').val(mobile_no_leading_hand_val);
		});

		/* ================ Construction Manager functions ================ */

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
					$('#imgTools').addClass('col-md-4');
			}

			var img_name;
			var path = $('img#target').attr('src');
			img_name = path.split("/").pop();

			image_editor_controls();

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
					$('#imgTools').addClass('col-md-4');
			   	}
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

				// var img_path = rootFolder+'/public_html/uploads/project_progress_report/'+project_id+'/'+img_name; // local
				// var backup_path = rootFolder+'/public_html/uploads/project_progress_report/'+project_id+'/backup_img'; // local
				
				$.ajax({
					type: 'GET',
				  	url: '<?php echo base_url().'projects/delete_file'; ?>',
				  	data: {'file' : img_path,
				  	       'filename' : img_name,
				  	       'backup_path' : backup_path },
			      	dataType: 'json', 
			      	success: function (response) {
			         	if( response.status === true ) {
			         		set_edited(img_name, '0');
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
			   		$('#imgGroup').removeClass('col-md-4');
					$('#imgTools').removeClass('col-md-4');
			   	} else {
			   		$('#imgLabel').addClass('col-md-8');
					$('#imgTools').addClass('col-md-4');
			   	}

			   	$('#targetCanvas').show();
			}

			var rotate_angle = '0';

			function rotateLeft(){
				var rotate_degrees = $('#target').css('transform');  //alert(rotate_degrees); for cross browser

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

			function saveCrop(img_name){
				if (crop_x == 0 && crop_y == 0 && crop_width == 0 && crop_height == 0){
					alert('Please select a cropping area.');
				} else {
					var project_id = $('#project_id').val();
					var rootFolder = '<?php echo $_SERVER['DOCUMENT_ROOT']; ?>';

					var crop_path = rootFolder+'/uploads/project_progress_report/'+project_id+'/'+img_name; // live
					var backup_path = rootFolder+'/uploads/project_progress_report/'+project_id+'/backup_img/'+img_name; // live
					
					// var crop_path = rootFolder+'/public_html/uploads/project_progress_report/'+project_id+'/'+img_name; // local
					// var backup_path = rootFolder+'/public_html/uploads/project_progress_report/'+project_id+'/backup_img/'+img_name; // local
					
					var data = project_id+'|'+crop_x+'|'+crop_y+'|'+crop_width+'|'+crop_height+'|'+crop_path+'|'+crop_path+'|'+img_name+'|'+backup_path; 

					// alert(data);

					$.ajax({
						'url' : '<?php echo base_url().'projects/crop_img'; ?>',
						'type' : 'POST',
						'data' : {'ajax_var' : data },
						'success' : function(data){

							set_edited(img_name, '1');

							$('#confirmModal').modal({show: 'true', backdrop: 'static', keyboard: false});

					    	$('.modal-title.msgbox').text('Refreshing Images');
					    	$('#confirmModal .modal-dialog.modal-sm').css('width', '550px');
					    	$('#confirmModal .modal-dialog.modal-sm').css('height', '200px');
					    	
						    $('#confirmText').html('Successfully cropped the image, applying changes.. <br><br> <strong>Note: If changes does not appear, please click Ctrl + F5 to hard refresh the browser.');
					    	$('#confirmButtons').html('<button type="button" class="btn btn-info" onclick="location.reload(true);">Okay</button>');

							setTimeout(function(){ location.reload(true); }, 10000);
						}
					});
				}
			}

			function saveRotate(img_name){

				if (rotate_angle == '0'){
					alert('Please rotate the image.');
				} else {
					var project_id = $('#project_id').val();
					var rootFolder = '<?php echo $_SERVER['DOCUMENT_ROOT']; ?>';

					var rotate_path = rootFolder+'/uploads/project_progress_report/'+project_id+'/'+img_name; // live
					var backup_path = rootFolder+'/ploads/project_progress_report/'+project_id+'/backup_img/'+img_name; // live
					
					// var rotate_path = rootFolder+'/public_html/uploads/project_progress_report/'+project_id+'/'+img_name; // local
					// var backup_path = rootFolder+'/public_html/uploads/project_progress_report/'+project_id+'/backup_img/'+img_name; // local
					
					var data = project_id+'|'+rotate_path+'|'+rotate_angle+'|'+img_name+'|'+backup_path; 

					// alert(data);

					$.ajax({
						'url' : '<?php echo base_url().'projects/rotate'; ?>',
						'type' : 'POST',
						'data' : {'ajax_var' : data },
						'success' : function(data){

							set_edited(img_name, '1');

							$('#confirmModal').modal({show: 'true', backdrop: 'static', keyboard: false});

					    	$('.modal-title.msgbox').text('Refreshing Images');
					    	$('#confirmModal .modal-dialog.modal-sm').css('width', '550px');
					    	$('#confirmModal .modal-dialog.modal-sm').css('height', '200px');
					    	
						    $('#confirmText').html('Successfully rotated the image, applying changes.. <br><br> <strong>Note: If changes does not appear, please click Ctrl + F5 to hard refresh the browser.');
					    	$('#confirmButtons').html('<button type="button" class="btn btn-info" onclick="location.reload(true);">Okay</button>');

							setTimeout(function(){ location.reload(true); }, 10000);
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
		
		function saveImageLbl(last_img_name, img_name){

			var lblImage = $('#lblImage').val();

			if (last_img_name.replace(/\s/g, '') != lblImage.replace(/\s/g, '')){
				text_replaced = 1;
			}

			data = lblImage+'|'+img_name;

			$.ajax({
				'url' : '<?php echo base_url().'projects/edit_image_label'; ?>',
				'type' : 'POST',
				'data' : {'ajax_var' : data },
				'success' : function(data){
					var img_name_sliced = img_name.slice(0, -4);
					$('p.'+img_name_sliced).text(data);
					set_edited(img_name, '1');
				}
			});
		}

		// $('#bsPhotoGalleryModal').on('hide.bs.modal', function (e) {
		// 	if (text_replaced == 1){
		// 	    $('#confirmModal').modal({show: 'true', backdrop: 'static', keyboard: false});

		//     	$('.modal-title.msgbox').text('Image data has been changed');
		//     	$('#confirmModal .modal-dialog.modal-sm').css('width', '550px');
		//     	$('#confirmModal .modal-dialog.modal-sm').css('height', '200px');
		    	
		// 	    $('#confirmText').html('Successfully changed the image data, applying changes...');
		//     	$('#confirmButtons').html('<button type="button" class="btn btn-info" onclick="location.reload(true);">Okay</button>');
		// 		setTimeout(function(){ location.reload(true); }, 5000);
		// 	}
		// })

		$('#btnSaveDetails').click(function(){

			var name_leading_hand = '';
			var name_const_mngr = '';
			var mobile_no_leading_hand_name = ''
			var mobile_no_const_mngr_name = '';

			if ($('#manual_input_leading_hand').is(':visible')) {

				var leading_hand = '0';
				name_leading_hand = $('#name_leading_hand').val();
				mobile_no_leading_hand_name = $('#mobile_no_leading_hand').val();	

			} else {

				if ($('select#leading_hand').val() != 'other_leading_hand'){
					leading_hand = $('select#leading_hand').val();	
				} else {
					alert('Please fill the required fields. (*)');
					return false;
				}				
			}

			if ($('#manual_input_const_mngr').is(':visible')) {

				var const_mngr = '0';
				name_const_mngr = $('#name_const_mngr').val();
				mobile_no_const_mngr_name = $('#mobile_no_const_mngr').val();	

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
			var pr_version = $('#pr_version').val();

			data = leading_hand+'|'+const_mngr+'|'+scope_of_work+'|'+project_id+'|'+pr_version;

			if (leading_hand == 0)
				data += '|'+name_leading_hand+'|'+mobile_no_leading_hand_name; 
			if (const_mngr == 0){
				data += '|'+name_const_mngr+'|'+mobile_no_const_mngr_name; 
			}

			// alert(data);
		
			if ($('select#leading_hand').val() == '' || $('select#const_mngr').val() == '' || const_mngr == '0' && name_const_mngr == '' && mobile_no_const_mngr_name == '' && leading_hand == '0' && name_leading_hand == '' && mobile_no_leading_hand_name == ''){
				alert('Please fill the required fields. (*)');
				return false;
			} else {

				$.ajax({
					'url' : '<?php echo base_url().'projects/update_pr_details'; ?>',
					'type' : 'POST',
					'data' : {'ajax_var' : data },
					'success' : function(data){
						
						if (data == 'true'){
							$('#confirmModal').modal('show');

					    	$('.modal-title.msgbox').text('Info Message');
					    	$('#confirmModal .modal-dialog.modal-sm').css('width', '500px');
					    	$('#confirmModal .modal-dialog.modal-sm').css('height', '200px');
					    	
						    $('#confirmText').html('Progress Report Details updated.');
					    	$('#confirmButtons').html('<button type="button" class="btn btn-info" data-dismiss="modal">Okay</button>');
						}
					}
				});
			}
		});

		$('#btnGeneratePDF').click(function(){

			if ($('li.bspHasModal .image-checkbox.image-checkbox-checked').is(":visible")){

				$('#generate_pr_report').submit();

			} else {

				$('#confirmModal').modal('show');

		    	$('.modal-title.msgbox').text('Click Image to select');
		    	$('#confirmModal .modal-dialog.modal-sm').css('width', '550px');
		    	$('#confirmModal .modal-dialog.modal-sm').css('height', '200px');
		    	
			    $('#confirmText').html('Please select first the images that you want to include in the Progress Report.');
		    	$('#confirmButtons').html('<button type="button" class="btn btn-danger" data-dismiss="modal">Go Back</button>');

			}
		});

		$("#btnAttachSend").click(function(){

			var project_id = $('#project_id').val();
			var pr_version = $('#pr_version').val();
			var pr_version_minus = $('#pr_version').val() - 1;
			var project_name = $('#project_name').val();
			var rootFolder = '<?php echo $_SERVER['DOCUMENT_ROOT']; ?>';

			var pr_path = rootFolder+'/reports/project_progress_report/'+project_id+'/progress_report'+pr_version+'.pdf'; // live
			// var pr_path = rootFolder+'/public_html/reports/project_progress_report/'+project_id+'/progress_report'+pr_version+'.pdf'; // local

			$.ajax({
				type: 'GET',
			  	url: '<?php echo base_url().'projects/check_pdf'; ?>',
			  	data: { 'pr_path' : pr_path },
		      	dataType: 'json', 
		      	success: function (response) {
		         	if( response.status === true ) {
		         		$('#lastPDF').css('display', 'none');
		             	$('#noPDF').css('display', 'none');
		             	$('#yesPDF').css('display', 'block');
		             	// $('#attachSendModal').modal('show');
		             	$('#attachSendModal').modal({backdrop: 'static', keyboard: false});  

		             	is_pdf_attached = 1;
		         	} else {

		         		if (pr_version_minus == 0){

		         			$('#lastPDF').css('display', 'none');
		         			$('#yesPDF').css('display', 'none');
			         		$('#noPDF').css('display', 'block');
			             	$('#attachSendModal').modal({backdrop: 'static', keyboard: false});

			             	is_pdf_attached = 0;

		         		} else {
		         			$('#attachSendModal').modal({backdrop: 'static', keyboard: false});
							$('#attachSendModal h4#myModalLabel').text('Send Progress Report '+pr_version_minus+' (Progress Report '+pr_version+' is not yet generated.)')
							$('#sendpdf_subject').val('Progress Report '+pr_version_minus+' - '+project_name+'');

			         		$('#lastPDF').css('display', 'block');
			         		$('#noPDF').css('display', 'none');
			             	$('#yesPDF').css('display', 'none');
			         			
			         		is_pdf_attached = 2;
		         		}
		      		}
		      	}
			});
		});

		$('#sendpdf_btn').click(function(){

			var project_id = $('#project_id').val();

			if (is_pdf_attached == 2){
				var pr_version = $('#pr_version').val() - 1;	
			} else {
				var pr_version = $('#pr_version').val();
			}

			var sendpdf_main_to_complete = $('#sendpdf_main_to').val();
			var sendpdf_main_to_type = sendpdf_main_to_complete.substr(0, 1);

			if (sendpdf_main_to_type == 0){
     			var sendpdf_main_to = sendpdf_main_to_complete.substr(2);
     		} else if (sendpdf_main_to_type == 1) {
     			var sendpdf_main_to = sendpdf_main_to_complete.substr(2);
     		} else {
     			var sendpdf_main_to = sendpdf_main_to_complete;
     		}

			var sendpdf_other_emails = $('#sendpdf_other_emails').val();
			var sendpdf_from = $('#end_user_email').val();
			var sendpdf_from_name = $('#user_full_name').val();
			var sendpdf_subject = $('#sendpdf_subject').val();
			var sendpdf_body = $('#sendpdf_body').val();
			var is_client = $("#is_client").is(':checked');

			var rootFolder = '<?php echo $_SERVER['DOCUMENT_ROOT']; ?>';


			var sendpdf_path = rootFolder+'/reports/project_progress_report/'+project_id+'/progress_report'+pr_version+'.pdf'; // live
			// var sendpdf_path = rootFolder+'/public_html/reports/project_progress_report/'+project_id+'/progress_report'+pr_version+'.pdf'; // local

			var data = sendpdf_main_to+'|'+sendpdf_other_emails+'|'+sendpdf_from+'|'+sendpdf_from_name+'|'+sendpdf_subject+'|'+sendpdf_body+'|'+sendpdf_path; 

			// alert(data);

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

							    	if (sendpdf_main_to_type != 0){
							    		if (sendpdf_main_to == 'alt_emails' && is_client == false){
							    			
							    		} else {
							    			sentPRtoClient();
							    		}
							    	}

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
			} else if (is_pdf_attached == 2) {
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
		    	
			    $('#confirmText').html('Please click Update Details and Generate PDF before sending the PDF.');
		    	$('#confirmButtons').html('<button type="button" class="btn btn-danger" data-dismiss="modal">Go Back</button>');
			}
		});

	});

	function sentPRtoClient(){

		var project_id = $('#project_id').val();
		var pr_version = $('#pr_version').val();
		var rootFolder = '<?php echo $_SERVER['DOCUMENT_ROOT']; ?>';
		var upload_path = rootFolder+'/uploads/project_progress_report/'+project_id; // live
		// var upload_path = rootFolder+'/public_html/uploads/project_progress_report/'+project_id; // local

		var data = project_id+'|'+pr_version+'|'+upload_path; 

		$.ajax({
			type: 'POST',
		  	url: '<?php echo base_url().'projects/sentPRtoClient'; ?>',
		  	data: { 'ajax_var' : data },
	      	success: function (data) {
     			$('#confirmModal').modal({backdrop: 'static', keyboard: false});

		    	$('.modal-title.msgbox').text('Success Message');
		    	$('#confirmModal .modal-dialog.modal-sm').css('width', '500px');
		    	$('#confirmModal .modal-dialog.modal-sm').css('height', '200px');
		    	
			    $('#confirmText').html('Progress Report '+pr_version+' is now sent to Client, deleting stored images and redirecting to projects...');
		    	$('#confirmButtons').html('<button type="button" class="btn btn-success" data-dismiss="modal">Okay</button>');

		    	setTimeout(function(){ window.location.replace("<?php echo base_url().'projects'; ?>"); }, 5000);
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

		if (select_mode == 1){
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

	function set_edited(img_name, param){

		var project_id = $('#project_id').val();

		data = img_name+'|'+project_id+'|'+param; // alert(data);
		
		$.ajax({
			'url' : '<?php echo base_url().'projects/set_edited_image'; ?>',
			'type' : 'POST',
			'data' : {'ajax_var' : data },
			'success' : function(data){
				var img_name_sliced = img_name.slice(0, -4);
				$('div.'+img_name_sliced+'.edit_screen > span').css('color', '#71657f');
			}
		});
	}

	// function editLabelcontrols(img_name){

	// 	$('span#editLabel > i').click(function(){
	// 		$('p.pText').hide();
	// 		$('p.pText.lblInput').show();

	// 	});

	// 	$('#lblImage').keypress(function (e) {
	// 	 var key = e.which;
	// 	 if(key == 13)  // the enter key code
	// 	  {

	// 	  	var last_img_name = $('#imgLabel > p.pText').text();
	// 	    saveImageLbl(last_img_name, img_name);
	// 	    alert('Image Description is changed.');
	// 	  }
	// 	});  

	// 	$('span#cancelEditlbl > i').click(function(){
	// 		$('p.pText').show();
	// 		$('p.pText.lblInput').hide();
	// 	});

	// 	$('span#saveEditlbl > i').click(function(){

	// 		var last_img_name = $('#imgLabel > p.pText').text();
	// 		saveImageLbl(last_img_name, img_name);
	// 		alert('Image Description is changed.');
	// 	});
	// }

	function getGroupImg(img_name){
		
		var project_id = $('#project_id').val();

		data = img_name+'|'+project_id; // alert(data);

		$.ajax({
			'url' : '<?php echo base_url().'projects/groupImg'; ?>',
			'type' : 'POST',
			'data' : {'ajax_var' : data },
			'success' : function(data){
				$('#groupSelect').append(data);

				

			}
		});
	}

	var image_name_val = '';

	function updateGroupImg(img_name, image_name_trim){

		var project_id = $('#project_id').val();
		var group_selected_id = $('#'+image_name_trim+'_groupSelect').val();

		data = img_name+'|'+project_id+'|'+group_selected_id; 

		// alert(data);

		if(group_selected_id == -1){
			$('#addGroupModal').modal('show');

			image_name_val = img_name;
		} else {
			$.ajax({
				'url' : '<?php echo base_url().'projects/updateGroupImg'; ?>',
				'type' : 'POST',
				'data' : {'ajax_var' : data },
				'success' : function(data){
					$('#'+image_name_trim+'_groupSelect').val(group_selected_id);
					set_edited(img_name, '1');
				}
			});
		}
	}

	$('#addGroupBtn').click(function(){
		addGroup(image_name_val);
	});

	function addGroup(img_name){

		var group_selected_id = $('#addGroupTxtbox').val();

		data = group_selected_id; 

		// alert(data);

		$.ajax({
			'url' : '<?php echo base_url().'projects/addNewGroup'; ?>',
			'type' : 'POST',
			'data' : {'ajax_var' : data },
			'success' : function(data){
				
				$('#addGroupModal').modal('hide');

				$('#groupLabel').remove();
				$('#groupSelect').remove();
				getGroupImg(img_name);
			}
		});
		
	}

	function open_pr_pdf(pr_version){

		var project_id = $('#project_id').val();
		var file_name = 'progress_report'+pr_version+'.pdf';
		window.open(baseurl+"projects/download?file_name="+file_name+"&proj_id="+project_id);
	}

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

</script>