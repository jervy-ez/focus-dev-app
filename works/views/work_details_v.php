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
			<form class="form-horizontal form" role="form" method ="post" action="">
				<div class="box-head pad-10 clearfix">
					<label>Add <?php echo $job_category ?></label> <span>(<a href="#" data-placement="right" class="popover-test" title="<?php echo $screen; ?>" data-content="Fields having * is requred." data-original-title="Works">?</a>)</span>
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
												<select id="worktype" class="form-control chosen"  tabindex="5" name="worktype">												
													<?php $this->projects->list_job_subcategory(); ?>														
													<?php $this->works->list_supplier_category(); ?>
												</select>
												<input type="text" id = "other_work_description" tabindex="6" name = "other_work_description" class = "form-control" style = "display: none" placeholder = "Type Work Description here..">
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


							<?php if($this->session->userdata('is_admin') == 1 ): ?>
							<div class="col-sm-12">
								<div class="box m-bottom-10">
									<div class="box-head pad-5 m-bottom-5">
										<label><i class = "fa fa-paperclip fa-lg"></i> Attachments</label>
										<a href = "" id = "show_attachment_type_modal" class = "pull-right" style = "font-size: 12px">[Add New]</a>
									</div>
									<div class="pad-5">
										<div id = "add_work_attachment_types" class = "pad-10"></div>
									</div>
									
								</div>
							</div>
							<?php endif; ?>

							<div class="col-sm-12">
								<div class="box m-bottom-10">
									<div class="box-head pad-5 m-bottom-5">
										<label><i class = "fa fa-pencil-square-o fa-lg"></i> Notes</label>
									</div>
									<div class="pad-10">
										<textarea class="form-control m-bottom-10" id="work_notes" rows="6" tabindex="21" name="work_notes" style="resize: vertical;  overflow: auto;"><?php echo $this->input->post('work_notes'); ?></textarea>
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
							<button type = "submit" tabindex="22" class = "btn btn-success"><i class = "fa fa-save"></i>  Save</button>
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
		<div class="modal fade" id="attachment_type_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
			   	<div class="modal-content">
				    <div class="modal-header">
					    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					    <h4 class="modal-title">Attachment Types</h4>
				    </div>
				    <div class="modal-body" style = "height: 300px">
				    	<div class="col-sm-12">
					    	<input type="text" id = "txt_attachment_type" class = "input-sm" placeholder = "Attachment Type">
					    	<button type = "button" class = "btn btn-primary" id = "btn_add_attachment_type">Add</button>
					    	<button type = "button" class = "btn btn-success" id = "btn_update_attachment_type">Update</button>
					    	<button type = "button" class = "btn btn-danger" id = "btn_delete_attachment_type">Delete</button>
				    	</div>
				    	<div class = "col-sm-12" style = "height: 10px"></div>
				      	<div id = "table_attachment_type" style = " height: 200px; overflow:auto" class = "col-sm-12">
				      	</div>
				    </div>
				    <!--<div class="modal-footer">
				      	<button type = "button" id = "change_attach_type_yes" class="btn btn-danger" data-dismiss="modal">Add New</button>
				       	<button type="button" id = "change_attach_type_no" class="btn btn-default" data-dismiss="modal">No</button>
				    </div>-->
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
	</div>
</div>
<?php $this->load->view('assets/logout-modal'); ?>