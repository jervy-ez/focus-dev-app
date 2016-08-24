<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>
<?php $this->load->module('projects'); ?>
<?php $works_curr_tab = ($this->session->flashdata('works_curr_tab') ? $this->session->flashdata('works_curr_tab') : 'work_details' ); ?>
<?php 

	$consideration = array();
	foreach ($work_q->result_array() as $row){
		$work_con_sup_id = $row['work_con_sup_id'];
		$other_category_id = $row['other_category_id'];

		$contractor_type = $row['contractor_type'];

		$work_estimate = $row['work_estimate'];
		if($work_joinery_id == ""){
			$markup = $row['work_markup'];
			$is_deliver_office = $row['is_deliver_office'];
			$work_reply_date = $row['work_reply_date'];
			$work_cpo_date = $row['work_cpo_date'];
			$good_deliverby_date = $row['goods_deliver_by_date'];
		}else{
			$markup = $work_joinery_markup;
			$is_deliver_office = $row['wj_is_deliver_office'];
			$work_reply_date = $row['wj_work_reply_date'];
			$work_cpo_date = $row['wj_work_cpo_date'];
			$good_deliverby_date = $row['wj_goods_deliver_by_date'];
		}
		$total_work_quote = $row['total_work_quote'];

		
		
		
		$comments = $row['comments'];
		$notes = $row['notes'];

		$works_comment = $row['works_comments'];

		$site_inspection_req = $row['site_inspection_req'];
		if($site_inspection_req == 1){
			$consideration[0] = "Site Inspection Required";
		}else{
			$consideration[0] = "";
		}
		$special_conditions = $row['special_conditions'];
		if($special_conditions == 1){
			$consideration[1] = "Special Conditions";
		}else{
			$consideration[1] = "";
		}
		$additional_visit_req = $row['additional_visit_req'];
		if($additional_visit_req == 1){
			$consideration[2] = "Additional Visits Required";
		}else{
			$consideration[2] = "";
		}
		$operate_during_install = $row['operate_during_install'];
		if($operate_during_install == 1){
			$consideration[3] = "Operate During Install";
		}else{
			$consideration[3] = "";
		}
		$week_work = $row['week_work'];
		if($week_work == 1){
			$consideration[4] = "Week Work";
		}else{
			$consideration[4] = "";
		}
		$weekend_work = $row['weekend_work'];
		if($weekend_work == 1){
			$consideration[5] = "Weekend Work";
		}else{
			$consideration[5] = "";
		}
		$after_hours_work = $row['after_hours_work'];
		if($after_hours_work == 1){
			$consideration[6] = "After Hours Work";
		}else{
			$consideration[6] = "";
		}
		$new_premises = $row['new_premises'];
		if($new_premises == 1){
			$consideration[7] = "New Premises";
		}else{
			$consideration[7] = "";
		}
		$free_access = $row['free_access'];
		if($free_access == 1){
			$consideration[8] = "Free Access";
		}else{
			$consideration[8] = "";
		}
		$otherdesc = $row['otherdesc'];
		$other = $row['other'];
		if($other == 1){
			$consideration[9] = $otherdesc;
		}else{
			$consideration[9] = "";
		}
	}
	$variation_id = 0;
	if(isset($_GET['variations'])){
		$variation_id = $_GET['variations'];
	}

?>
<!-- title bar -->
<input type="hidden" id = "work_joinery_id" value = "<?php echo $work_joinery_id ?>">
<input type="hidden" id = "work_id" value = "<?php echo $work_id ?>">
<input type="hidden" id = "proj_id" value = "<?php echo $project_id ?>">
<input type="hidden" id = "variation_id" value = "<?php echo $variation_id ?>">

<div class="container-fluid head-control">
	<div class="container-fluid">
		<div class="row">

			<div class="col-md-6 col-sm-4 col-xs-12 pull-left">
				<header class="page-header">
					<h3><?php $datestring = "%l, %F %d, %Y"; $time = time(); //use time() for timestamp  ?>
						<?php echo $screen; ?> Setup<br><small><?php echo mdate($datestring, $time); #echo date("l, F d, Y"); ?></small>
					</h3>
				</header>
			</div>

			<div class="page-nav-options col-md-6 col-sm-8 col-xs-12 pull-right hidden-xs">
				<ul class="nav nav-tabs navbar-right">
					<li>
						<a href="<?php echo base_url(); ?>" ><i class="fa fa-home"></i> Home</a>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>projects" >Projects</a>
					</li>
					<li>
						<a class="btn-small sub-nav-bttn" id="project-details-update">Project Details</a>
					</li>
					<li>
						<a class="btn-small sub-nav-bttn" id="quotation-view">Quotation</a>
					</li>
					<li>
						<a href="<?php echo  current_url(); ?>#invoice" class="btn-small">Invoicing</a>
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
			<div class="m-left-5 m-right-5">
			<div class="left-section-box">
			<form class="form-horizontal form" role="form" method ="post" action="">
				<div class="box-head pad-10 clearfix">
					<input type="hidden" id = "hid_work_con_sup_id" value = "<?php echo $work_con_sup_id ?>">
					<?php if($job_date == ""){ ?><?php if($this->session->userdata('projects') >= 2): ?><button type = "button" class = "btn btn-warning pull-right" id = "btn_edit_work_desc"><i class = "fa fa-save"></i> Edit</button><?php endif; ?><?php } ?>
					<label id = "lbl_work_desc"><?php echo $work_desc ?></label>
					<p><a href="" id = "back_to_works"><i class = "fa fa-hand-o-left"></i> Back to Works List</a></p>
					<div class="clearfix" id = "edit_work_desc">
						<button type = "button" class = "btn btn-danger col-sm-1" id = "btn_delete_work_desc" data-toggle="modal" data-target="#work_del_conf"><i class = "fa fa-trash" ></i> Delete</button>
						<div class="col-sm-8">
							<?php 
								if($work_joinery_id == ""){
									if($work_con_sup_id == 82){
							?>
							<div class="input-group">
								<span class="input-group-addon">Work Description</span>
								<select id="worktype" class="form-control chosen"  tabindex="5" name="worktype">												
									<?php $this->projects->list_job_subcategory(); ?>														
									<?php $this->works->list_supplier_category(); ?>
								</select>
								<script type="text/javascript">$("select#worktype").val("<?php echo $contractor_type."_".$work_con_sup_id; ?>");</script>
							</div>

							<div class="input-group">
								<span class="input-group-addon other_work_category">Category&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span>
								<select id="other_work_category" class="form-control chosen other_work_category" style = "font-size: 14px" tabindex="5" name="other_work_category">													
									<?php $this->projects->job_cat_list_no_other(); ?>														
									<?php $this->works->list_supplier_category(); ?>
								</select>
								<script type="text/javascript">$("select#other_work_category").val("<?php echo $other_category_id; ?>");</script>
							</div>
							<div class="input-group">
								<span class="input-group-addon other_work_category">Work Description</span>
								<input type="text" id = "other_work_description" name = "other_work_description" class = "form-control other_work_category" value = "<?php echo $work_desc ?>" placeholder = "Type Work Description here..">
							</div>
							<?php
									}else{
							?>
							<div class="input-group">
								<span class="input-group-addon">Work Description</span>
								<select id="worktype" class="form-control chosen"  tabindex="5" name="worktype">												
									<?php $this->projects->list_job_subcategory(); ?>														
									<?php $this->works->list_supplier_category(); ?>
								</select>
								<script type="text/javascript">$("select#worktype").val("<?php echo $contractor_type."_".$work_con_sup_id; ?>");</script>
							</div>
							<div class="input-group">
								<span class="input-group-addon other_work_category">Category&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span>
								<select id="other_work_category" class="form-control chosen other_work_category" style = "font-size: 14px" tabindex="5" name="other_work_category">													
									<?php $this->projects->job_cat_list_no_other(); ?>														
									<?php $this->works->list_supplier_category(); ?>
								</select>
								<script type="text/javascript">$("select#other_work_category").val("<?php echo $other_category_id; ?>");</script>
							</div>
							<div class="input-group">
								<span class="input-group-addon other_work_category">Work Description</span>
								<input type="text" id = "other_work_description" name = "other_work_description" class = "form-control other_work_category" value = "<?php echo $work_desc ?>" placeholder = "Type Work Description here..">
								
								<script type="text/javascript">
									$('.other_work_category').hide();
								</script>
							</div>
							<?php
									}
							
								}else{
							?>
							<div class="input-group">
								<span class="input-group-addon">Joinery Name</span>
								<input type="text" id="work_joinery_name" name = "work_joinery_name" class = "form-control" value = "<?php echo $work_desc ?>" />
							</div>
							
							<?php
								}
							?>
							
						</div>
						<div class = "col-sm-2"><button type = "button" class = "btn btn-success" id = "btn_save_work_desc"><i class = "fa fa-save"></i> Save</button></div>
					</div>
				</div>

			<input type="hidden" id="projmarkup" value="<?php echo $projmarkup ?>">
			<input type="hidden" id="minmarkup" value="<?php echo $min_markup ?>">
			<input type="hidden" id="gst" value="<?php echo $this->session->userdata('gst_rate'); ?>">
			<div class="box-area pad-top-5">
				
				<?php if(@$error): ?>
					<div class="rows">
						<div class="col-sm-12">
							<div class="pad-10 no-pad-t">
								<div class="border-less-box alert alert-danger fade in">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<h4>Oh snap! You got an error!</h4>
									<?php echo $error;?>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
						<!-- ================================================= -->
						<div class="rows">
							<div class="col-sm-9">
								<div class="rows">
									<div class="col-sm-6">
										<div class="col-sm-12">
											<div class="box m-bottom-5">
												<div class="box-head pad-5 m-bottom-5">
													<?php if($job_date == ""){ ?><?php if($this->session->userdata('projects') >= 2): ?><button type = "button" id = "btn_edit_est_markup" class = "btn-xs btn btn-warning pull-right"><i class = "fa fa-pencil-square-o"></i> Edit</button><?php endif; ?><?php } ?>
													<button type = "button" id = "save_est_markup" class = "btn-xs btn btn-success pull-right"><i class = "fa fa-save"></i>  Save</button>
													<div class="clearfix pull-right">
														<label for="chkdeltooffice" class="pull-right text-right m-top-10" style=""><i class="fa fa-line-chart fa-lg"></i> GST: <?php echo $this->session->userdata('gst_rate'); ?>%</label>												
													</div>
													<label><i class = "fa fa-money fa-lg"></i> Estimates and Markups</label>
												</div>
												<div class="panel-body" style = "height: 70px">
													<div id="est_markup">
														<p class="m-top-10"><strong>Markup</strong>: <span class="data-unit_number"><?php echo $markup." % "; ?></span></p>
													</div>
													<div id = "edit_est_markups">
														<div class="m-bottom-10 clearfix <?php if(form_error('work_markup')){ echo 'has-error has-feedback';} ?>">
															<label for="work_markup" class="col-sm-3 control-label">Markup*</label>
															<div class="col-sm-9">
																<div class="input-group">
																	<span class="input-group-addon">(%)</span>
																	<input type="text" name="work_markup" id="work_markup" class="form-control number_format" placeholder="Work Markup" value="<?php echo $markup ?>"/>
																</div>
															</div>
														</div>
													</div>	
												</div>
											</div>
										</div>
										<div class="col-sm-12">
											<div class="box m-bottom-5">
												<div class="box-head pad-5 m-bottom-5">
													<?php if($this->session->userdata('projects') >= 2): ?><button type = "button" id = "edit_work_dates" class = "btn-xs btn btn-warning pull-right"><i class = "fa fa-pencil-square-o"></i> Edit</button><?php endif; ?>
													<button type = "button" id = "save_work_dates" class = "btn-xs btn btn-success pull-right"><i class = "fa fa-save"></i>  Save</button>
													<div class="clearfix pull-right">
														<div class="m-top-2 m-left-5 pull-right">
															<input type="checkbox" id="chkdeltooffice" name="chkdeltooffice" value="1" <?php echo ($is_deliver_office ? 'checked="checked"' : '' ); ?>>
														</div>
														<label for="chkdeltooffice" class="pull-right text-right m-top-10" style="">Deliver to Office</label>												
													</div>
													<label><i class = "fa fa-calendar fa-lg"></i> Work Dates</label>
												</div>
												<div class="panel-body clearfix" style = "height: 213px">
													<div id="work_date">
														<p class="col-sm-6"><strong>Reply By</strong>: <span class="data-unit_level"><?php echo $work_reply_date; ?></span></p>
														<p class="col-sm-6"><strong>CPO Date</strong>: <span class="data-street"><?php echo $work_cpo_date; ?></span></p>
														<p class="col-sm-12"><strong>Goods Delivered by:</strong>: <span class="data-street"><?php echo $good_deliverby_date; ?></span></p>
													</div>
													<div class = "col-sm-12" id = "edit_work_date">
														<div class="col-sm-5">
															<div class="clearfix m-bottom-10">
																<label for="work_replyby_date" class="col-sm-5 control-label" style = "font-size: 12px">Reply By:</label>
																<div class="col-sm-7">
																	<input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control datepicker" id="work_replyby_date" name="work_replyby_date" value="<?php echo $work_reply_date ?>">												
																</div>
															</div>
														</div>
														<div class="col-sm-7">
															<div class="clearfix m-bottom-10">
																<label for="work_replyby_date" class="col-sm-5 control-label" style = "font-size: 12px">Goods Deliver by:</label>
																<div class="col-sm-7">
																	<input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control datepicker" id="goods_deliver_by_date" name="goods_deliver_by_date" value="<?php echo $good_deliverby_date ?>">												
																</div>
															</div>
														</div>
													</div>
													<p><strong>Remarks</strong>:</p>
													<input type = "text" class="form-control col-sm-12" id="update_replyby_desc" value = "<?php echo $comments ?>" name="update_replyby_desc">
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="box m-bottom-10">
											<div class="box-head pad-5 m-bottom-5">
												<label><i class = "fa fa-th-list fa-lg"></i> Considerations</label>
												<?php if($this->session->userdata('projects') >= 2): ?><button type = "button" id = "edit_considerations" class = "btn-xs btn btn-warning pull-right"><i class = "fa fa-pencil-square-o"></i> Edit</button><?php endif; ?>
												<button type = "button" id = "save_considerations" class = "btn-xs btn btn-success pull-right"><i class = "fa fa-save"></i>  Save</button>
											</div>
											<div class="panel-body m-bottom-5" style = "height: 338px">
												<div id = "considerations">
													<?php 
														$x = 0;
														while($x < 10){
															if($consideration[$x] !== ""){
													?>
														<p class="m-top-10"><strong>* </strong><span class="data-unit_level"><?php echo $consideration[$x]; ?></span></p>
													<?php
															}
														$x++;			
														}
													?>
												</div>
												<div id="edit_considerations_list">
													<div class="col-sm-12"><input type="checkbox" value="1" <?php if ($site_inspection_req == 1) echo "checked='checked'"; ?>  id = "chkcons_site_inspect" name = "chkcons_site_inspect"> <label for="chkcons_site_inspect">Site Inspection Req</label></div>
													<div class="col-sm-12"><input type="checkbox" value="1" <?php if ($week_work == 1) echo "checked='checked'"; ?>  id = "chckcons_week_work" name = "chckcons_week_work"> <label for="chckcons_week_work">Week Work</label></div>

													<div class="col-sm-12"><input type="checkbox" value="1" <?php if ($special_conditions == 1) echo "checked='checked'"; ?>  id = "chckcons_spcl_condition" name = "chckcons_spcl_condition"> <label for="chckcons_spcl_condition">Special Conditions</label></div>
													<div class="col-sm-12"><input type="checkbox" value="1" <?php if ($weekend_work == 1) echo "checked='checked'"; ?>  id = "chckcons_weekend_work" name = "chckcons_weekend_work"> <label for="chckcons_weekend_work">Weekend Work</label></div>

													<div class="col-sm-12"><input type="checkbox" value="1" <?php if ($additional_visit_req == 1) echo "checked='checked'"; ?>  id = "chckcons_addnl_visit" name = "chckcons_addnl_visit"> <label for="chckcons_addnl_visit">Additional Visits Req</label></div>
													<div class="col-sm-12"><input type="checkbox" value="1" <?php if ($after_hours_work == 1) echo "checked='checked'"; ?>  id = "chckcons_afterhrs_work" name = "chckcons_afterhrs_work"> <label for="chckcons_afterhrs_work">After Hours Work</label></div>

													<div class="col-sm-12"><input type="checkbox" value="1" <?php if ($operate_during_install == 1) echo "checked='checked'"; ?>  id = "chckcons_oprte_duringinstall" name = "chckcons_oprte_duringinstall"> <label for="chckcons_oprte_duringinstall">Operate During Install</label></div>
													<div class="col-sm-12"><input type="checkbox" value="1" <?php if ($new_premises == 1) echo "checked='checked'"; ?>  id = "chckcons_new_premises" name = "chckcons_new_premises"> <label for="chckcons_new_premises">New Premises</label></div>

													<div class="col-sm-12"><input type="checkbox" value="1" <?php if ($free_access == 1) echo "checked='checked'"; ?> id = "chckcons_free_access" name = "chckcons_free_access"> <label for="chckcons_free_access">Free Access</label></div>
													<div class="col-sm-12"><input type="checkbox" value="1" <?php if ($other == 1) echo "checked='checked'"; ?> id = "chckcons_others" name = "chckcons_others"> <label for="chckcons_others">Others</label> 
													<input type="text" class = "form-control m-top-5" name="other_consideration" id = "other_consideration" value="<?php echo $otherdesc; ?>"></div>
												</div>
											</div>
										</div>
									</div>
									
									<div class="col-sm-12">
										<div class="box m-bottom-10">
											<div class="box-head pad-5 m-bottom-5">
												<input type="hidden" id = "project_type" value = "<?php echo $job_category ?>">
												<label><i class = "fa fa-pencil-square-o fa-lg"></i> Notes</label>
												<?php if($this->session->userdata('projects') >= 2): ?><button type = "button" id = "edit_notes" class = "btn-xs btn btn-warning pull-right"><i class = "fa fa-pencil-square-o"></i> Edit</button><?php endif; ?>
												<button type = "button" id = "save_notes" class = "btn-xs btn btn-success pull-right"><i class = "fa fa-save"></i>  Save</button>
											</div>
											<div class="pad-5">
												<textarea class="form-control m-bottom-10" id="update_work_notes" rows="6" tabindex="32" name="work_notes" style="resize: vertical;  overflow: auto;" <?php if($job_category == "Maintenance"){ ?>maxlength = '1470'<?php } ?>><?php echo $notes ?></textarea>
												<!-- <p>Lines Left: <span class="lines_left">10</span></p> -->
											</div>
										</div>
									</div>
									<div class="clearfix"></div>
								</div>
									<div class="clearfix"></div>
							</div>
							
							<div class="col-sm-3">
								<div class="box m-bottom-10">
									<div class="box-head pad-5 m-bottom-5">
										<label><i class = "fa fa-pencil-square-o fa-lg"></i> Costing Details</label>
										<?php if($this->session->userdata('projects') >= 2): ?><button type = "button" id = "edit_costing_details" class = "btn-xs btn btn-warning pull-right"><i class = "fa fa-pencil-square-o"></i> Edit</button><?php endif; ?>
										<button type = "button" id = "save_costing_details" class = "btn-xs btn btn-success pull-right"><i class = "fa fa-save"></i>  Save</button>
										<script>
											$("#edit_costing_details").show();
											$("#save_costing_details").hide();
										</script>
									</div>
									<div class="panel-body m-bottom-5" style = "height: 338px">
										<textarea class="form-control m-bottom-10" id="work_comments" tabindex="32" name="work_comments" style="resize: vertical;  overflow: auto; height: 310px"><?php echo $works_comment ?></textarea>
									</div>
									<script>
										$("#work_comments").attr('disabled','disabled');
										$("#edit_costing_details").click(function(){
											$("#work_comments").removeAttr('disabled');
											$("#edit_costing_details").hide();
											$("#save_costing_details").show();
										});
										$("#save_costing_details").click(function(){
											var works_id = $("#work_id").val();
											var work_comments = $('#work_comments').val();
											$.post(baseurl+"works/update_work_comments", 
										    { 
										      works_id: works_id,
										      work_comments: work_comments
										    }, 
										    function(result){
												$("#work_comments").attr('disabled','disabled');
												$("#edit_costing_details").show();
												$("#save_costing_details").hide();
											});
										});
									</script>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
						<!-- ================================================= -->
				</form>
				</div>
				<!-- +++++++ +++++ -->
				
				<div class="clearfix"></div>
				<input type="hidden" name="is_variation" value="<?php echo $variations; ?>">
			
			</div>
			</div>
			</div>
		</div>
	</div>
</div>
<!-- =============== Modal ================ -->
<div class="modal fade" id="work_del_conf" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
	    <div class="modal-content">
	        <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		        <h4 class="modal-title">Confirmation</h4>
	        </div>
	        <div class="modal-body">
	        	<p><strong>Are you sure you want to delete selected work?</strong></p>
	        </div>
	        <div class="modal-footer">
	        	<button type = "button" id = "btn_work_del_conf_yes" class="btn btn-danger">Yes</button>
	          	<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
	        </div>
	    </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="attachment_modal_img" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
	    <div class="modal-content">
	        <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		        <h5 id = "attach_img_name"></h5>
	        </div>
	        <div class="modal-body text-center" style = "background: #000">
	        	<img src="" alt="" id = "attach_image" style = "height: 400px">
	        </div>
	        <div class="modal-footer">
	        	<button type = "button" id = "btn_delete_attachment" class="btn btn-danger"><i class = "fa fa-trash-o"></i> Delete</button>
	          	<button type="button" id = "btn_download_file" class="btn btn-success"><i class = "fa fa-download"></i> Download</button>
	        </div>
	    </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- 
<!--<div class="modal fade" id="attach_file_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    	<form action="" method="post" enctype="multipart/form-data">
	    <div class="modal-content">
	        <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		        <h4 class="modal-title">Attach File</h4>
	        </div>
	        <div class="modal-body" style = "height: 500">
	        	<span class="btn btn-primary btn-sm btn-file pull-right">
				    <i class = "fa fa-plus-circle"></i> Browse<input type="file" multiple="multiple">
				</span>
				<div class="col-sm-12" style="height: 5px"></div>
				<div class = "col-sm-12" id = "upload_file_list" style = "height: 400px; overflow: auto; border: 1px ridge #E8E8E8"></div>
	        </div>
	        <div class="modal-footer">
	        	<button type = "button" id = "btn_work_del_conf_yes" class="btn btn-success"><i class = "fa fa-cloud-upload"></i> Upload</button>
	          	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	        </div>
	    </div>
	    </form>
	</div>
</div> -->
<div class="modal fade" id="change_attach_type_conf" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
	    <div class="modal-content">
	        <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		        <h4 class="modal-title">Confirmation</h4>
	        </div>
	        <div class="modal-body">
	        	<p><strong>Are you sure you want to Change Attachment Type of the selected Work Attachment?</strong></p>
	        </div>
	        <div class="modal-footer">
	        	<button type = "button" id = "change_attach_type_yes" class="btn btn-danger" data-dismiss="modal">Yes</button>
	          	<button type="button" id = "change_attach_type_no" class="btn btn-default" data-dismiss="modal">No</button>
	        </div>
	    </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php $this->load->view('assets/logout-modal'); ?>
