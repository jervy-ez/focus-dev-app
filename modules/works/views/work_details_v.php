<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>
<?php $this->load->module('projects'); ?>
<!-- title bar -->
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
			<form class="form-horizontal form add_work" role="form" method ="post" action="">
				<div class="box-head pad-10 clearfix">
					<label>Add <?php echo $job_category ?></label> 


<span class="fa fa-film pointer open_help_vids_mpd" data-toggle="modal" data-target="#help_video_group"> </span>
					<p>Fields having * is requred.</p>
					<p><a href="" id = "back_to_works"><i class = "fa fa-hand-o-left"></i> Back to Works List</a></p>
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

				<div class="rows">
					<div class="col-sm-9">
						<div class="rows">
							<div class="col-sm-6">
								<div class="box m-bottom-5">
									<div class="box-head pad-5 m-bottom-5">
										<label><i class = "fa fa-list-alt form-inline fa-lg"></i> Work Description</label>
										<input type="hidden" id = "joinery_exist" value = "<?php echo $exist ?>">
										<?php if($exist == 1): ?>
											<div class = "pull-right" id = "chk_is_joinery"><label>Joinery Item</label> <input type="checkbox" id = "is_joinery" name = "is_joinery"></div>
										<?php endif; ?>
									</div>
									<div class="panel-body">
										<div id = "work_desc" class="m-bottom-10 clearfix <?php if(form_error('worktype')){ echo 'has-error has-feedback';} ?>">
											<label for="worktype" class="col-sm-3 control-label">Work*</label>
											<div class="col-sm-9">
												<select id="worktype" class="form-control chosen"  tabindex="4" name="worktype" autocomplete="false">												
													<?php $this->projects->list_job_subcategory(); ?>														
													<?php $this->works->list_supplier_category(); ?>
												</select>
												<label for="" id = "lbl_work_category" style = "display: none">Category:</label>
												<select id="other_work_category" class="form-control chosen other_work_category" tabindex="5" name="other_work_category" style = "display: none" autocomplete="false">												
													<?php $this->projects->job_cat_list_no_other(); ?>														
													<?php $this->works->list_supplier_category(); ?>
												</select>
												<script>$("#other_work_category").val("");</script>
												<br>
												<input type="text" id = "other_work_description" tabindex="6" name = "other_work_description" onblur = "blur_other_desc()" class = "form-control" style = "display: none" placeholder = "Type Work Description here..">
												<script>
													window.blur_other_desc = function(){
														var cat = $("#other_work_category").val();
														if(cat == "" || cat == null){
															//alert("Category is required!");
															$('[tabindex=5]').focus();
														}
													}
														
												</script>
											</div>
										</div>

										<div id = "work_joinery" class="m-bottom-10 clearfix <?php if(form_error('worktype')){ echo 'has-error has-feedback';} ?>">
											<label for="work_joinery_name" class="col-sm-3 control-label">Joinery Name*</label>
											<div class="col-sm-9">
										        <input type="text" id="work_joinery_name" name = "work_joinery_name" class = "form-control"/>
												<!--<input type="text" id = "work_joinery_name" name = "work_joinery_name" class = "form-control"> -->
												<!--<select id="work_joinery_name" class="form-control chosen"  tabindex="5" name="worktype">												
													<?php //$this->works->fetch_joinery(); ?>	
													<option value=0>Add New</option>													
												</select>-->
											</div>
										</div>

										<div class="back m-bottom-10 clearfix <?php if(form_error('work_markup')){ echo 'has-error has-feedback';} ?>">
											<label for="work_markup" class="col-sm-3 control-label">Markup</label>
											<div class="col-sm-9">
												<div class="input-group">
													<span class="input-group-addon">(%)</span>
													<input type="text" name="work_markup" id="work_markup" tabindex="7" class="form-control number_format" placeholder="Work Markup" value="<?php echo ($this->input->post('work_markup') ? $this->input->post('work_markup') : $projmarkup ); ?>"/>
												</div>
											</div>
										</div>

									</div>
								</div>								
							</div>
							<script type="text/javascript">$("select#worktype").val("<?php echo $this->input->post('worktype'); ?>");</script>
							<div class="col-sm-6">

								<div class="box m-bottom-5">
									<div class="box-head pad-5 m-bottom-5">

										<div class="clearfix pull-right">
											<div class="m-top-2 m-left-5 pull-right">
												<input type="checkbox" id="chkdeltooffice" name="chkdeltooffice" tabindex="8" value="1" <?php echo ($this->input->post('chkdeltooffice') ? 'checked="checked"' : '' ); ?>>
											</div>
											<label for="chkdeltooffice" class="pull-right text-right m-top-10" style="">Deliver to Office</label>												
										</div>

										<label><i class = "fa fa-calendar fa-lg"></i> Work Dates</label>
									</div>
									<div class="panel-body clearfix">

										<div class="rows clearfix">
											<div class="col-sm-12">

												<div class="clearfix m-bottom-10">
													<label for="work_replyby_date" class="col-sm-3 control-label">Reply By</label>
													<div class="col-sm-9">
														<input type="text" tabindex="9" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control datepicker" id="work_replyby_date" name="work_replyby_date" value="<?php echo $this->session->userdata('work_reply_date'); ?>">												
													</div>
												</div>
												
												<div class="clearfix m-bottom-10">
													<label for="work_replyby_date" class="col-sm-3 control-label">Remarks:</label>
													<div class="col-sm-9">
														<input type = "text" tabindex="10" name="replyby_desc" id="replyby_desc" class = "inpu-sm form-control" value = "<?php echo $this->session->userdata('comments'); ?>">											
													</div>
												</div>
											</div>
										</div>

									</div>
								</div>
							</div>
							
							<!--<div class="col-sm-6">

								<div class="box m-bottom-5">
									<div class="box-head pad-5 m-bottom-5">
										<div class="clearfix pull-right">
											<label for="chkdeltooffice" class="pull-right text-right m-top-10" style=""><i class="fa fa-line-chart fa-lg"></i> GST: <?php //echo $this->session->userdata('gst_rate'); ?>%</label>												
										</div>


										<label><i class = "fa fa-money fa-lg"></i> Estimates and Markups</label>
										

										<?php 
										//if($operation == 1){
											?>	
											<button type = "button" id = "edit_est_markup" class = "btn-xs btn btn-warning pull-right"><i class = "fa fa-pencil-square-o"></i> Edit</button>
											<button type = "button" id = "save_est_markup" class = "btn-xs btn btn-success pull-right"><i class = "fa fa-save"></i>  Save</button>
											<?php
										//} 
										?>
									</div>
									<div class="panel-body">

										<div class="m-bottom-10 clearfix <?php //if(form_error('work_estimate')){ echo 'has-error has-feedback';} ?>">
											<label for="work_estimate" class="col-sm-3 control-label green-estimate">Estimates</label>
											<div class="col-sm-9">
												<div class="input-group green-estimate">
													<span class="input-group-addon">($)</span>
													<input type="text" name="work_estimate" id="work_estimate" class="form-control number_format" placeholder="Work Estimate" value="<?php //echo $this->input->post('work_estimate'); ?>"/>
												</div>
											</div>
										</div>

										<div class="m-bottom-10 clearfix <?php // if(form_error('work_markup')){ echo 'has-error has-feedback';} ?>">
											<label for="work_markup" class="col-sm-3 control-label">Markup</label>
											<div class="col-sm-9">
												<div class="input-group">
													<span class="input-group-addon">(%)</span>
													<input type="text" name="work_markup" id="work_markup" class="form-control number_format" placeholder="Work Markup" value="<?php //echo ($this->input->post('work_markup') ? $this->input->post('work_markup') : $projmarkup ); ?>"/>
												</div>
											</div>
										</div>

										<div class="clearfix <?php //if(form_error('work_quote_val')){ echo 'has-error has-feedback';} ?>">
											<label for="work_quote_val" class="col-sm-3 control-label">Quote</label>
											<div class="col-sm-9">
												<div class="input-group">
													<span class="input-group-addon">($) Ext GST</span>
													<input type="text" name="work_quote_val" id="work_quote_val" class="form-control" placeholder="Work Markup" readonly value="<?php //echo $this->input->post('work_quote_val'); ?>"/>
												</div>
											</div>
										</div>
										<div style = "height: 10px"></div>
										<div class="clearfix <?php //if(form_error('work_quote_val')){ echo 'has-error has-feedback';} ?>">
											<label for="work_quote_val" class="col-sm-3 control-label"></label>
											<div class="col-sm-9">
												<div class="input-group">
													<span class="input-group-addon">($) Inc GST</span>
													<input type="text" class="form-control inc_gst" placeholder="Work Markup" readonly value = "0.00"/>
												</div>
											</div>
										</div>

									</div>
								</div>
							</div> -->

							<!-- <div class="col-sm-6">

								<div class="box m-bottom-5">
									<div class="box-head pad-5 m-bottom-5">
										<label><i class = "fa fa-pencil-square fa-lg"></i> Remarks</label>
									</div>
									<div class="panel-body clearfix">
										<div class="rows clearfix">
											<div class="col-sm-12">
												<textarea name="replyby_desc" id="replyby_desc" class = "col-sm-12" style = "height: 165px"></textarea>												
											</div>
										</div>

									</div>
								</div>
							</div> -->


							

							<div class="col-sm-12">
								<div class="box m-bottom-10">

									<div class="box-head pad-5 m-bottom-5">
										<label><i class = "fa fa-pencil-square-o fa-lg"></i> Notes <?php if($job_category == "Maintenance"){ echo "(Please don't exceed 14 lines!)"; } ?></label>
									</div>
									<div class="pad-10">
										<input type="hidden" id = "project_type" value = "<?php echo $job_category ?>">
										<textarea class="form-control m-bottom-10" id="work_notes" rows="6" cols="5000" tabindex="21" name="work_notes" style="resize: vertical;  overflow: auto;"  <?php if($job_category == "Maintenance"){ ?>maxlength = '1400'<?php } ?>><?php echo $this->input->post('work_notes'); ?></textarea>
										<!--<p>Lines Left: <span class="lines_left">10</span></p> -->
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
								<label><i class = "fa fa-th-list fa-lg"></i> Considerations</label>
							</div>
							<div class="panel-body m-bottom-5">
								<div class="">
									<div class="col-sm-12"><input type="checkbox" tabindex="10" value="1" <?php echo ($this->session->userdata('site_inspection_req') ? 'checked="checked"' : '' ); ?>  id = "chkcons_site_inspect" name = "chkcons_site_inspect"> <label for="chkcons_site_inspect">Site Inspection Req</label></div>
									<div class="col-sm-12"><input type="checkbox" tabindex="11" value="1" <?php echo ($this->session->userdata('week_work') ? 'checked="checked"' : '' ); ?>  id = "chckcons_week_work" name = "chckcons_week_work"> <label for="chckcons_week_work">Week Work</label></div>

									<div class="col-sm-12"><input type="checkbox" tabindex="12" value="1" <?php echo ($this->session->userdata('special_conditions') ? 'checked="checked"' : '' ); ?>  id = "chckcons_spcl_condition" name = "chckcons_spcl_condition"> <label for="chckcons_spcl_condition">Special Conditions</label></div>
									<div class="col-sm-12"><input type="checkbox" tabindex="13" value="1" <?php echo ($this->session->userdata('weekend_work') ? 'checked="checked"' : '' ); ?>  id = "chckcons_weekend_work" name = "chckcons_weekend_work"> <label for="chckcons_weekend_work">Weekend Work</label></div>

									<div class="col-sm-12"><input type="checkbox" tabindex="14" value="1" <?php echo ($this->session->userdata('additional_visit_req') ? 'checked="checked"' : '' ); ?>  id = "chckcons_addnl_visit" name = "chckcons_addnl_visit"> <label for="chckcons_addnl_visit">Additional Visits Req</label></div>
									<div class="col-sm-12"><input type="checkbox" tabindex="15" value="1" <?php echo ($this->session->userdata('after_hours_work') ? 'checked="checked"' : '' ); ?>  id = "chckcons_afterhrs_work" name = "chckcons_afterhrs_work"> <label for="chckcons_afterhrs_work">After Hours Work</label></div>

									<div class="col-sm-12"><input type="checkbox" tabindex="16" value="1" <?php echo ($this->session->userdata('operate_during_install') ? 'checked="checked"' : '' ); ?>  id = "chckcons_oprte_duringinstall" name = "chckcons_oprte_duringinstall"> <label for="chckcons_oprte_duringinstall">Operate During Install</label></div>
									<div class="col-sm-12"><input type="checkbox" tabindex="17" value="1" <?php echo ($this->session->userdata('new_premises') ? 'checked="checked"' : '' ); ?>  id = "chckcons_new_premises" name = "chckcons_new_premises"> <label for="chckcons_new_premises">New Premises</label></div>

									<div class="col-sm-12"><input type="checkbox" tabindex="18" value="1" <?php echo ($this->session->userdata('free_access') ? 'checked="checked"' : '' ); ?> id = "chckcons_free_access" name = "chckcons_free_access"> <label for="chckcons_free_access">Free Access</label></div>
									<div class="col-sm-12"><input type="checkbox" tabindex="19" value="1" <?php echo ($this->session->userdata('other') ? 'checked="checked"' : '' ); ?> id = "chckcons_others" name = "chckcons_others"> <label for="chckcons_others">Others</label> 
									<input type="text" class = "form-control m-top-5" tabindex="20" name="other_consideration" id = "other_consideration" value="<?php echo $this->session->userdata('otherdesc'); ?>"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>

					<div class="m-10">
						<?php if($operation == 1): ?>
							<button type = "button" class = "btn btn-danger"><i class = "fa fa-trash"></i>  Delete Work</button>
						<?php else: ?>
							<button type = "button" tabindex="22" class = "btn btn-success submit_form"><i class = "fa fa-save"></i>  Save</button>
						<?php endif; ?>						
					</div>
				</div>

				<div class="clearfix"></div>


				<input type="hidden" name="is_variation" value="<?php echo $variations; ?>">
				</form>
			</div>
			</div>
			</div>
		</div>
		
	</div>
</div>
<input type = "hidden" name = "hiddentextbox" id = "hiddentextbox">
<?php $this->load->view('assets/logout-modal'); ?>
<script>
	if(localStorage.getItem("local_storage_set") == "1"){
		var work_reply_date = localStorage.getItem("work_reply_date");
		$("#work_replyby_date").val(work_reply_date);

		var site_inspection_req = localStorage.getItem("site_inspection_req");
		if(site_inspection_req == 'true'){
			$("#chkcons_site_inspect").prop('checked',true);
		}
		
		var special_conditions = localStorage.getItem("special_conditions");
		if(special_conditions == 'true'){
			$("#chckcons_spcl_condition").prop('checked',true);
		}

		var additional_visit_req = localStorage.getItem("additional_visit_req");
		if(additional_visit_req == 'true'){
			$("#chckcons_addnl_visit").prop('checked',true);
		}

		var operate_during_install = localStorage.getItem("operate_during_install");
		if(operate_during_install == 'true'){
			 $("#chckcons_oprte_duringinstall").prop('checked',true);
		}

		var week_work = localStorage.getItem("week_work");
		if(week_work == 'true'){
			$("#chckcons_week_work").prop('checked',true);
		}

		var weekend_work = localStorage.getItem("weekend_work");
		if(weekend_work == 'true'){
			$("#chckcons_weekend_work").prop('checked',true);
		}

		var after_hours_work = localStorage.getItem("after_hours_work");
		if(after_hours_work == 'true'){
			$("#chckcons_afterhrs_work").prop('checked',true);
		}

		var new_premises = localStorage.getItem("new_premises");
		if(new_premises == 'true'){
			$("#chckcons_new_premises").prop('checked',true);
		}

		var free_access = localStorage.getItem("free_access");
		if(free_access == 'true'){
			$("#chckcons_free_access").prop('checked',true);
		}

		var other = localStorage.getItem("other");
		if(other == 'true'){
			 $("#chckcons_others").prop('checked',true);
		}

		var otherdesc = localStorage.getItem("otherdesc");
		$("#other_consideration").val(otherdesc);

		var comments = localStorage.getItem("comments");
		$("#replyby_desc").val(comments);
	}else{
		localStorage.setItem("local_storage_set", "");
		localStorage.setItem("work_reply_date", "");
		localStorage.setItem("site_inspection_req", "");
		localStorage.setItem("special_conditions", "");
		localStorage.setItem("additional_visit_req", "");
		localStorage.setItem("operate_during_install", "");
		localStorage.setItem("week_work", "");
		localStorage.setItem("weekend_work", "");
		localStorage.setItem("after_hours_work", "");
		localStorage.setItem("new_premises", "");
		localStorage.setItem("free_access", "");
		localStorage.setItem("other", "");
		localStorage.setItem("otherdesc", "");
		localStorage.setItem("comments", "");
	}

	$('.submit_form').click(function(){
		var work_reply_date = $("#work_replyby_date").val();
		var site_inspection_req = $("#chkcons_site_inspect").prop('checked');
		var special_conditions = $("#chckcons_spcl_condition").prop('checked');
		var additional_visit_req = $("#chckcons_addnl_visit").prop('checked');
		var operate_during_install = $("#chckcons_oprte_duringinstall").prop('checked');
		var week_work = $("#chckcons_week_work").prop('checked');
		var weekend_work = $("#chckcons_weekend_work").prop('checked');
		var after_hours_work = $("#chckcons_afterhrs_work").prop('checked');
		var new_premises = $("#chckcons_new_premises").prop('checked');
		var free_access = $("#chckcons_free_access").prop('checked');
		var other = $("#chckcons_others").prop('checked');
		var otherdesc = $("#other_consideration").val();
		var comments = $("#replyby_desc").val();

		localStorage.setItem("local_storage_set", "1");
		localStorage.setItem("work_reply_date", work_reply_date);
		localStorage.setItem("site_inspection_req", site_inspection_req);
		localStorage.setItem("special_conditions", special_conditions);
		localStorage.setItem("additional_visit_req", additional_visit_req);
		localStorage.setItem("operate_during_install", operate_during_install);
		localStorage.setItem("week_work", week_work);
		localStorage.setItem("weekend_work", weekend_work);
		localStorage.setItem("after_hours_work", after_hours_work);
		localStorage.setItem("new_premises", new_premises);
		localStorage.setItem("free_access", free_access);
		localStorage.setItem("other", other);
		localStorage.setItem("otherdesc", otherdesc);
		localStorage.setItem("comments", comments);
		
		var work_type = $("#worktype").val();
		var cat = $("#other_work_category").val();
		if(work_type == '2_82'){
			if(cat == "" || cat == null){
			alert("Category is required!");
			}else{
				$('.submit_form').attr('disabled','disabled');
				$('.add_work').submit();
			}
		}else{
			$('.submit_form').attr('disabled','disabled');
			$('.add_work').submit();
		}
   	
    });

	setTimeout(function(){$('#s2id_autogen2_search').val(''); }, 1000);
</script>



<!-- _________________________________ HELP VIDEO SETUP _________________________________ -->
<?php $this->load->module('help_videos'); ?>
<div id="help_video_group" class="modal fade" tabindex="-1" data-width="760" >
  <div class="modal-dialog" style="width:85%;">
    <div class="modal-content">
      <div class="modal-header">
        <ul id="myTab" class="nav nav-tabs pull-right" style="border-bottom: 0;">
          <li class="now_playing_tab_btn"><a href="#now_playing" style="color:#555; display:none;" data-toggle="tab"><i class="fa fa-globe fa-lg"></i> Now Playing</a></li>
          <li class="help_videos_tab_btn active"><a href="#help_videos" style="color:#555;" data-toggle="tab" tabindex="20"><i class="fa fa-inbox fa-lg"></i> Videos</a></li> 
        </ul>
        <h4 class="modal-title"><em id="" class="fa fa-film"></em> Help Videos </h4>
      </div>
      <div class="modal-body" style="margin:0 !important; padding:0 !important;">
        <div class="tab-content">
          <div id="now_playing" class="tab-pane fade clearfix  in">
            <iframe style="width: 100%;height: 70%;background-repeat: no-repeat;background-color:#000;background-image: url('<?php echo base_url(); ?>uploads/misc/loading_bub.gif');background-position: center;background-size: 50px;" class="group_video_frame" ></iframe>
          </div>
          <div id="help_videos" class="tab-pane fade clearfix active in">
            <div id="" class="m-10 p-bottom-10 clearfix">
            <p id="" class="m-left-5 p-bottom-10 m-top-5 clearfix" style="font-weight: bold;    font-size: 16px;    border-bottom: 1px solid #ccc;">Works Screen Videos</p>
              <?php $this->help_videos->get_help_selected_video('23'); ?>
            </div>
          </div>
        </div>
      </div> 
    </div>
  </div>
</div>

<script type="text/javascript">
$('.mod_video_toggle').click(function(){
 $('.group_video_frame').attr('src','');
  var video_details_arr = $(this).find('.video_details').text().split('`');
  setTimeout(function(){
    $('.group_video_frame').attr('src',video_details_arr[1]);
  },2000);
  $('li.now_playing_tab_btn a').show().trigger('click');
});

$('.open_help_vids_mpd').click(function(){
  $('li.help_videos_tab_btn a').trigger('click');
    $('li.now_playing_tab_btn a').hide();
});
</script>
<!-- _________________________________ HELP VIDEO SETUP _________________________________ -->


