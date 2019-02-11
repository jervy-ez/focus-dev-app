<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>
<?php $this->load->module('projects'); ?>
<?php $this->load->module('attachments'); ?>
<?php $this->load->module('send_emails'); ?>
<?php $this->load->module('variation'); ?>
<?php $this->load->module('invoice'); ?>
<?php

	$progress_reports = $this->session->userdata('progress_report');

	if($this->session->flashdata('curr_tab')){
		$curr_tab = $this->session->flashdata('curr_tab');
		switch($curr_tab){
			case 'attachments':
				echo '<script>window.history.pushState("","",'. $project_id.')</script>';
				break;
			case 'variations':
				$variation_id = $this->uri->segment(4);
				echo '<script>window.history.pushState("","",'. $variation_id.')</script>';
				break;
			case 'project-schedule':
				$curr_tab = 'project-schedule';
				echo '<script>window.history.pushState("","",'. $project_id.')</script>';
				break;
			default:
				echo '<script>window.history.pushState("","",'. $project_id.')</script>';
				break;
		}
	}elseif($work_estimated_total > 0){
		if(isset($_GET['curr_tab'])){
			$url_curr_tab = $_GET['curr_tab'];
			switch($url_curr_tab){
				case 'attachments':
					$curr_tab = 'attachments';
					echo '<script>window.history.pushState("","",'. $project_id.')</script>';
					break;
				case 'variations':
					$curr_tab = 'variations';
					// $variation_id = $this->uri->segment(4);
					// echo '<script>window.history.pushState("","",'. $variation_id.')</script>';
					$variation_id = $this->uri->segment(4);
					?>
						<script>
							var variation_id = '<?php echo $variation_id ?>';
							var baseurl = '<?php echo base_url() ?>'
							var proj_id = '<?php echo $project_id ?>';
							var stateObj = { foo: "bar" };
							if(variation_id == ''){
								window.history.pushState(stateObj, "", proj_id);
								setTimeout(function(){
									$("#add_new_var").removeAttr('disabled');
								    $("#variation_name").val("");
								    $("#variation_name").attr('disabled','disabled');
								    $("#var_site_hrs").val("");
								    $("#var_site_hrs").attr('disabled','disabled');
								    $("#var_is_double_time").val("");
								    $("#var_is_double_time").attr('disabled','disabled');
								    $("#var_credit").val("");
								    $("#var_credit").attr('disabled','disabled');
								    $("#var_markup").val("");
								    $("#var_markup").attr('disabled','disabled');
								    //$("#variation_notes").attr('disabled','disabled');
								    $("#variation_notes").val("");

								    $("#var_acceptance_date").val("");
								    $("#var_acceptance_date").attr('disabled','disabled');
								    $("#var_save").hide();
								    $("#var_update").hide();
								    $("#var_delete").hide();
								    $.post(baseurl+"variation/variation_list", 
								    { 
								      proj_id: proj_id
								    }, 
								    function(result){
								      $("#proj_variation_list").html(result);
								      $.post(baseurl+"variation/get_variation_total",
								      {
								        proj_id: proj_id
								      },
								      function(result){
								        var var_totals = result.split( '|' );
								        var t_accepted = var_totals[0];
								        var t_unaccepted = var_totals[1];
								    
								        $("#var_unaccepted_total").val(t_unaccepted);
								        $("#var_accepted_total").val(t_accepted);
								        $(".variation_total").html(t_accepted);
								      });
								    });
							    },800);
							}else{
								window.history.pushState(stateObj, "", variation_id);
							}
							
						</script>
					<?php
					break;
				case 'works':
					$curr_tab = 'works';
					echo '<script>window.history.pushState("","",'. $project_id.')</script>';
					break;
				case 'project-schedule':
					$curr_tab = 'project-schedule';
					echo '<script>window.history.pushState("","",'. $project_id.')</script>';
					break;
				default:
					$curr_tab = 'project-details';
					break;
			}
		}else{
			$curr_tab = 'works';
		}
	}else{
		if(isset($_GET['curr_tab'])){
			$url_curr_tab = $_GET['curr_tab'];
			switch($url_curr_tab){
				case 'attachments':
					$curr_tab = 'attachments';
					echo '<script>window.history.pushState("","",'. $project_id.')</script>';
					break;
				case 'variations':
					$curr_tab = 'variations';
					$variation_id = $this->uri->segment(4);
					echo '<script>window.history.pushState("","",'. $variation_id.')</script>';
					break;
				case 'works':
					$curr_tab = 'works';
					echo '<script>window.history.pushState("","",'. $project_id.')</script>';
					break;
				case 'project-schedule':
					$curr_tab = 'project-schedule';
					echo '<script>window.history.pushState("","",'. $project_id.')</script>';
					break;
				default:
					$curr_tab = 'project-details';
					break;
			}
		}else{
			$curr_tab = 'project-details';
		}
	}
	
// 	if($work_estimated_total > 0){
// 		$curr_tab = 'works';
// 	}elseif($this->session->flashdata('curr_tab')){
// 		$curr_tab = $this->session->flashdata('curr_tab');
// 	}else{
// 		if(isset($_GET['curr_tab']) == 'attachments'){
// 			$curr_tab = 'attachments';
// 		}else{
// 			$curr_tab = 'project-details';
// 		}
// 	}

	if($this->session->flashdata('curr_tab') == 'invoice'){
		$curr_tab = 'invoice';
	}
	
	if($this->session->flashdata('curr_tab') == 'project-details'){
		$curr_tab = 'project-details';
	}

	// if($this->session->flashdata('curr_tab') == 'variations'){
	// 	$curr_tab = 'variations';
	// }

// 	if($this->session->flashdata('curr_tab') == 'attachments'){
// 		$curr_tab = 'attachments';
// 	}

	if($this->session->flashdata('curr_tab') == 'send_pdf'){
		$curr_tab = 'send_pdf';
	}
	$variation = "";
	if(isset($_GET['variation'])){
		$variation = 'variation';//$this->session->flashdata('variation');
	}
	

	if($this->invoice->if_has_invoice($project_id) == 0): 
		$prog_payment_stat = 0;
	else:
		$prog_payment_stat = 1;
	endif;

	if($shopping_center_brand_name == ''){
		$shopping_center_brand_name = $shopping_common_name;
	}


?>


<?php
	if($this->session->userdata('projects') < 2 ){	
		echo '<style type="text/css">.modal #create_cqr,.modal #update_contractor,.modal #delete_contractor,.modal #save_contractor,#addwork,#btnaddcontractor,.btn-file{ display: none !important;visibility: hidden !important;}
.estimate{z-index: -1 !important;pointer-events:!important;}
.quick_edit_project .quick_input{z-index: -1 !important;position: relative !important;pointer-events:!important;}
		</style>';
	}
?>





<!-- title bar 
estimate-->
<div class="container-fluid head-control">
	<div class="container-fluid">
		<div class="row">
			<input type="hidden" id = "hidden_proj_id" value = "<?php echo $project_id; ?>">
			<div class="col-md-6 col-sm-4 col-xs-12 pull-left">
				<header class="page-header">
					<h3 class="hidden-md visible-lg">
						<?php $datestring = "%l, %F %d, %Y"; $time = time(); //use time() for timestamp  ?>
						<?php echo $screen; ?><br><small><?php echo mdate($datestring, $time); #echo date("l, F d, Y"); ?></small>
					</h3>

					<h3 class="visible-md visible-sm visible-xs">
						<?php echo $project_name; ?><br />
						<small>&nbsp; Project No.<?php echo $project_id; ?></small>
					</h3>

				</header>
			</div>

			<div class="page-nav-options col-md-6 col-sm-8 col-xs-12 pull-right hidden-xs">
				<ul class="nav nav-tabs navbar-right">
					<li>
						<a href="<?php echo base_url(); ?>" ><i class="fa fa-home"></i> Home</a>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>projects" >Project</a>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>projects/update_project_details/<?php echo $project_id; ?>" class="btn-small sub-nav-bttn">Project Details</a>
					</li>
					<?php if($this->session->userdata('is_admin') == 1 ): ?>
					<li>
						<a href="#" class="btn-small view_applied_settings"><i class="fa fa-cog"></i> Applied Settings</a>
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

			<div class="m-5">

				<?php if($this->session->userdata('is_admin') == 1 ): ?>

					<div class="border-less-box alert alert-info fade in pad-0 no-pad row">
						<div class="col-sm-2"><strong>Project Mark-Up:</strong> <?php echo $markup; ?>%</div>
						<div class="col-sm-2"><strong>Site Labour Total:</strong> (ex-gst) $<?php echo number_format($final_labor_cost,2); ?></div>
						<div class="col-sm-2"><strong>Variation Total:</strong> $<span class="variation_total"><?php echo number_format($variation_total,2); ?></span></div>
						<div class="col-sm-4"><strong>Project Total:</strong> (ex-gst) $<span id = "proj_ex_gst"><?php echo number_format($final_total_quoted,2); ?></span>  &nbsp;&nbsp;&nbsp;&nbsp; (inc-gst) $<span id = "proj_inc_gst"><?php echo number_format($final_total_quoted+($final_total_quoted*($admin_gst_rate/100)),2); ?></span></div>
						<div class="col-sm-2"><strong>GP:</strong> <?php echo ($gp*100); ?>%</div>

						<div class="admin_settings clearfix" style="display:none;">
							<hr style="margin:5px 0;" />
							<div class="col-sm-3">
								<strong>Total Amalgamated Rate:</strong> <?php echo $admin_total_rate_amalgated; ?>
							</div>
							<div class="col-sm-3">
								<strong>Total Double Time Rate:</strong> <?php echo $admin_total_rate_double; ?>
							</div>
							<div class="col-sm-3">
								<strong>Actual Amalgamated Rate:</strong> <?php echo $admin_actual_rate_amalgate;?>
							</div>
							<div class="col-sm-3">
								<strong>Actual Double:</strong> <?php echo $admin_actual_rate_double;?>
							</div>
							<div class="col-sm-3">
								<strong>Install Markup:</strong> <?php echo $admin_install_markup; ?>%
							</div>
							<div class="col-sm-3">
								<strong>GST Rate:</strong> <span class="project_gst_percent"><?php echo $admin_gst_rate; ?></span>%
							</div>		
							<div class="col-sm-3">
								<strong>Hourly Rate:</strong> <?php echo $admin_hourly_rate; ?>
							</div>		
							<div class="col-sm-3">
								<strong>Site Labour Cost Grad Total:</strong> <?php echo $labour_cost_grand_total; ?>%
							</div>		
						</div>
					</div>

				<?php else: ?>


					<div class="border-less-box alert alert-info fade in pad-0 no-pad row">
						<div class="col-sm-2"><strong>Project Mark-Up:</strong> <?php echo $markup; ?>%</div>
						<div class="col-sm-2"><strong>Site Labour Total:</strong> (ex-gst) $<?php echo number_format($final_labor_cost,2); ?></div>
						<div class="col-sm-2"><strong>Variation Total:</strong> $<span class="variation_total"><?php echo number_format($variation_total,2); ?></span></div>
						<div class="col-sm-4"><strong>Project Total:</strong> (ex-gst) $<?php echo number_format($final_total_quoted,2); ?>  &nbsp;&nbsp;&nbsp;&nbsp; (inc-gst) $<?php echo number_format($final_total_quoted+($final_total_quoted*($admin_gst_rate/100)),2); ?></div>
						<div class="col-sm-2"><strong>GP:</strong> <?php echo ($gp*100); ?>%</div>
					</div>

				<?php endif; ?>

			</div>

			<div class="container-fluid projects">
				<div class="row">
					<div class="col-md-12">
						<div class="left-section-box m-top-1">

							<div class="row clearfix">
								<div class="col-lg-4 col-md-12 hidden-md hidden-sm hidden-xs">
									<div class="pad-left-15 clearfix">		
										<span class="project_gst_percent_inv hide"><?php echo $admin_gst_rate; ?></span>								
										<label class="project_name"><?php echo $project_name; ?><p>Client: <strong><?php echo $client_company_name; ?></strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Project No.<?php echo $project_id; ?></p></label>
									</div>
								</div>

								<div class="col-lg-8 col-md-12">

									<div class="pad-top-15 pad-left-15 pad-bottom-5 clearfix box-tabs">	
										
									<ul id="myTab" class="nav nav-tabs pull-right">
										<li class="<?php echo ($curr_tab == 'invoice' ? 'active' : '' ); ?>">
											<a href="#invoices" data-toggle="tab" class="link_tab_invoice" id="<?php echo $project_id ?>"><i class="fa fa-list-alt fa-lg"></i> Invoices</a>
										</li>
										<li class="<?php echo ($curr_tab == 'project-details' ? 'active' : '' ); ?>">
											<a href="#project-details" data-toggle="tab"><i class="fa fa-briefcase fa-lg"></i> Project Details</a>
										</li>
										
										<input type="hidden" id = "ps_is_admin" name = "ps_is_admin" value = "<?php echo $this->session->userdata('is_admin') ?>">
										<input type="hidden" id = "ps_restriction" name = "ps_restriction" value = "<?php echo $this->session->userdata('project_schedule') ?>">
										<?php if($this->session->userdata('is_admin') == 1 || $this->session->userdata('project_schedule') > 0): ?>
										<li class="<?php echo ($curr_tab == 'project-schedule' ? 'active' : '' ); ?>">
											<a href="#project_schedule" onclick = "load_project_schedule()" data-toggle="tab"><i class="fa fa-calendar fa-lg"></i> Project Schedule</a>
										</li>
										<?php endif; ?>


										<li class="<?php echo ($curr_tab == 'works' ? 'active' : '' ); ?>">
											<a href="#works" data-toggle="tab" onclick="set_work_default()"><i class="fa fa-cubes fa-lg"></i> Works</a>
										</li>
										
										<li class="<?php echo ($curr_tab == 'variations' ? 'active' : '' ); ?>">
											<a href="#variations" onclick = "load_variation()" data-toggle="tab"><i class="fa fa-cube fa-lg"></i> Variations</a>
										</li>
										
										<li class="<?php echo ($curr_tab == 'attachments' ? 'active' : '' ); ?>">
											<a href="#attachments" data-toggle="tab"><i class="fa fa-paperclip fa-lg"></i> Attachments</a>
										</li>

										<li class="<?php echo ($curr_tab == 'send_pdf' ? 'active' : '' ); ?>">
											<a href="#send_pdf" data-toggle="tab" onclick = "view_send_contractor()"><i class="fa fa-file-pdf-o fa-lg"></i> Send PDF</a>
										</li>	
										<li role="presentation" class="dropdown pull-right">
											<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
												<i class="fa fa-bar-chart-o fa-lg"></i> Reports <span class="caret"></span>
											</a>
											<ul class="dropdown-menu" role="menu">
												<li><a href="<?php echo base_url(); ?>works/proj_summary_w_cost/<?php echo $project_id ?>" target="_blank"><i class="fa fa-file-pdf-o"></i> Project Summary with Cost</a></li>
												<li><a href="<?php echo base_url(); ?>works/proj_summary_wo_cost/<?php echo $project_id ?>" target="_blank"><i class="fa fa-file-pdf-o"></i> Project Summary without Cost</a></li>
												<li><a href="<?php echo base_url(); ?>works/proj_joinery_summary_w_cost/<?php echo $project_id ?>" target="_blank"><i class="fa fa-file-pdf-o"></i> Joinery Summary with Cost</a></li>
												<li><a href="<?php echo base_url(); ?>works/proj_joinery_summary_wo_cost/<?php echo $project_id ?>" target="_blank"><i class="fa fa-file-pdf-o"></i> Joinery Summary without Cost</a></li>
												
												<li><a href="<?php echo base_url(); ?>works/variation_summary/<?php echo $project_id ?>" target="_blank"><i class="fa fa-file-pdf-o"></i> Variations Summary</a></li>
												<li><a href="<?php echo base_url(); ?>works/proj_details/<?php echo $project_id ?>" target="_blank"><i class="fa fa-file-pdf-o"></i> Project Details</a></li>
												<li><a href="" id = "work_cont_quote_req"><i class="fa fa-file-pdf-o"></i> Contractor Quote Request</a></li>
												<li><a href="" id = "work_cont_po"><i class="fa fa-file-pdf-o"></i> Contractor Purchase Order</a></li>
												<?php if($job_category == "Maintenance"){
												?>
												<li><a href="#" id = "maintenance_site_sheet"><i class="fa fa-file-pdf-o"></i> Maintenance Site Sheet</a></li>
												<?php
													} 
												?>
												<?php if($this->session->userdata('is_admin') == 1 ): ?>
												<li><a href="amplemod/print_pdf"><i class="fa fa-file-pdf-o"></i> Quotation and Contract</a></li>
												<?php endif; ?>
												<li><a href="#" onclick = "create_contract(<?php echo $project_id ?>)"><i class="fa fa-file-pdf-o"></i> Contract, Terms of Trade<br />&amp; Request for New Trade Form</a></li>

												<?php if($progress_reports == 1): // $this->session->userdata('is_admin') == 1 || ?>

													<?php if( strpos(implode(",",$progress_report_defaults), $job_category) !== false && $is_wip == 1 ): ?>
														<li><a href="<?php echo base_url(); ?>projects/progress_reports/<?php echo $project_id ?>"><i class="fa fa-file-pdf-o"></i> Progress Report</a></li>
													<?php endif; ?>

												<?php endif; ?>
												<?php if($this->session->userdata('is_admin') == 1 ): ?>
												<li><a href="<?php echo base_url(); ?>induction_health_safety/induction_slide_editor_view?project_id=<?php echo $project_id ?>" id = "project_induction"><i class="fa fa-file-pdf-o"></i> Inductions Templates</a></li>
												<?php endif; ?>

											</ul>
										</li>									
									</ul>

									</div>

								</div>


							</div>


							



							<div class="box-area">

								<div class="box-tabs m-bottom-15">
									<div class="tab-content">
										<div class="tab-pane fade in  clearfix <?php echo ($curr_tab == 'invoice' ? 'active' : '' ); ?>" id="invoices">
											<?php // if($this->session->userdata('is_admin') == 1 ): ?>
												<?php echo $this->projects->show_project_invoice($project_id); ?>
											<?php // endif; ?>

												<form class="form-horizontal tooltip-enabled" role="form" method="post" action="<?php echo base_url(); ?>projects/save_invoice_comments" style="margin-bottom: 0;" data-original-title="Notice: This is for special instuctions to whom the invoice is to be send.">
											<div class="box ">
													<div class="box-head pad-5">
														<label><i class="fa fa-pencil-square-o fa-lg"></i> Invoice Comments</label> <em style="font-size: 12px;">Maximum of two (2) lines only.</em>
														
													</div>
													<div class="box-area pad-5 clearfix">
														<div class="  clearfix ">
															<input type="hidden" name="prj_id" value="<?php echo $project_id; ?>">
															<textarea class="form-control" id="invoice_comments" rows="4" name="invoice_comments" placeholder="Invoice Comments" ><?php echo "$invoice_comments"; ?></textarea>
														</div>
													</div>

													<input type="submit" name="set_invoice_comments" class="btn btn-success   pull-right m-top-10 m-right-2" value="Save Comments">

											</div>

											<div style="margin: 10px 0px; width:300px;">
												<div class="input-group ">
													<span class="input-group-addon"><i class="fa fa-flag" aria-hidden="true"></i> Include Invoice Comments</span>
													<select class="form-control" id="include_invoice_comments" name="include_invoice_comments" >
												
														<option value="0">No</option>
														<option value="1">Yes</option>
													</select>
													<script type="text/javascript"> $('select#include_invoice_comments').val(<?php echo $include_invoice_comments; ?>); </script>
												</div>
											</div>

												</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

										</div>
										
										<div class="tab-pane fade in clearfix <?php echo ($curr_tab == 'project-details' ? 'active' : '' ); ?>" id="project-details">


											<div class="m-bottom-15 clearfix">
												

												<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">		


													<?php if(@$this->session->flashdata('quick_update')): ?>
														<div class="m-top-10">
															<div class="border-less-box alert alert-success fade in">
																<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
																<?php echo $this->session->flashdata('quick_update');?>
															</div>
														</div>
													<?php endif; ?>

													<form method="post" action="<?php echo base_url(); ?>projects/quick_update" class="form-horizontal">

														<div class="box ">
															<div class="box-head pad-5"><label><i class="fa fa-share fa-lg"></i> Quick Update Details</label></div>
															<div class="box-area pad-5 text-center pad-bottom-10">

																<input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
																<input type="hidden" name="is_double_time" value="<?php echo $is_double_time; ?>">
																<input type="hidden" name="site_labour_estimate" value="<?php echo $labour_hrs_estimate; ?>">

																<div class="box-area m-top-15 clearfix quick_edit_project">
																	<div class="col-sm-12 m-bottom-10 clearfix m-top-10">
																		<label for="project_name" class="col-sm-4 control-label m-top-5 text-left">Project Name</label>
																		<div class="col-sm-8  col-xs-12">
																			<input type="text" name="project_name" id="project_name" class="quick_input form-control text-right" tabindex="10" placeholder="Project Name" value="<?php echo $project_name; ?>">
																		</div>
																	</div>

																	<div class="col-sm-12 m-bottom-10 clearfix">
																		<label for="client_po_set" class="col-sm-4 control-label m-top-5 text-left">Client PO</label>
																		<div class="col-sm-8  col-xs-12">
																			<input type="text" name="client_po" id="client_po_set" class="quick_input form-control text-right" tabindex="10" placeholder="Client PO" value="<?php echo $client_po; ?>">
																		</div>
																	</div>

																	<div class="col-sm-12 m-bottom-10 clearfix">
																		<label for="client_po" class="col-sm-4 control-label m-top-5 text-left">
																		<?php if($job_date_history!=''): ?>
																		<span class="pointer strong"><i class="fa fa-calendar strong tooltip-enabled" title="" data-html="true" data-placement="top" data-original-title="<?php echo $job_date_history; ?>"></i></span> 
																	<?php endif; ?>

																		Job Date</label>
																		<div class="col-sm-8  col-xs-12">

																		<?php if($this->invoice->if_has_invoice($project_id) == 0 || $this->invoice->if_completed_invoice($project_id) == 0): ?>
																			<div  title="Warning: You need to set up the Project Payments." class="tooltip-enabled">
																			<p class="job-date-set form-control text-right" id="job_date" ><?php if($job_date == ''){echo "DD/MM/YYYY";}else{echo $job_date;}  ?></p>
																			</div>
																		<?php   else: ?>
																			<?php if($job_date == '' ): ?>
																				<input type="text" placeholder="DD/MM/YYYY" title="Warning: Changing a value in the the Job date affects the project in the WIP section." class="tooltip-enabled job-date-set form-control  text-right"  id="job_date" name="job_date" value="<?php echo $job_date; ?>" autocomplete="off">
																			<?php elseif($this->session->userdata('is_admin') == 1 || $this->session->userdata('job_date') == 1 || ( $this->session->userdata('user_role_id') == 7 && $job_category == 'Maintenance' )  ): ?>
																				<input type="text" placeholder="DD/MM/YYYY" title="Warning: Changing a value in the the Job date affects the project in the WIP section." class="pad-10 tooltip-enabled job-date-set form-control  text-right" id="job_date" name="job_date" value="<?php echo $job_date; ?>" autocomplete="off">
																			<?php elseif( $this->session->userdata('company_project') == 1 && $job_category == 'Company' ): ?>
																				<input type="text" placeholder="DD/MM/YYYY" title="Warning: Changing a value in the the Job date affects the project in the WIP section." class="pad-10 tooltip-enabled job-date-set form-control  text-right" id="job_date" name="job_date" value="<?php echo $job_date; ?>" autocomplete="off">
																			<?php else: ?>
																				<p title="Warning: You need to request to the Project Manager to change the Job Date" class="form-control tooltip-enabled text-right" ><?php echo $job_date; ?></p>
																				
																			<?php endif; ?>
																		<?php  endif; ?>
																		</div>
																	</div>
												
																	<?php if($restricted_cat == 0): ?>
																	<div class="col-sm-12 m-bottom-10 clearfix">
																		<label for="client_po_set" class="col-sm-4 control-label m-top-5 text-left">Unaccepted Date:</label>
																		<div class="col-sm-8  col-xs-12">
																			<input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" title="" class="pad-10 tooltip-enabled form-control datepicker text-right" id="unaccepted_date" name="unaccepted_date" onblur = 'blur_unaccepted_date()' value="<?php echo $unaccepted_date; ?>" autocomplete="off">
																		</div>
																	</div>
																	<script>
																		window.blur_unaccepted_date = function(){
																			var ua_date = $("#unaccepted_date").val();
																		}
																	</script>
																	<?php endif; ?>

																	<div class="col-sm-12 m-bottom-10 clearfix">
																		<label for="project_markup" class="col-sm-4 control-label m-top-5 text-left">Project Markup</label>
																		<div class="input-group ">
																			<span class="input-group-addon">(%)</span>
																				<p class="min_mark_up hidden"><?php echo $min_markup; ?></p>	


																			<?php if($job_date != ''): ?>
																				<p class="form-control text-right"><?php echo $markup; ?></p>
																				<input type="hidden" name="project_markup" id="project_markup" class="quick_input form-control text-right project_markup hide hidden" tabindex="12" placeholder="Markup %" value="<?php echo $markup; ?>" <?php echo ($job_date != '' ? 'style="z-index: -1;"' : ''); ?>>	
																			<?php else: ?>

																				<?php //if($this->invoice->if_project_invoiced_full($project_id)): ?>
																					<!-- <p class="form-control text-right"><?php //echo $markup; ?></p>
																					<input type="hidden" name="project_markup" id="project_markup" class="quick_input form-control text-right project_markup hide hidden" tabindex="12" placeholder="Markup %" value="<?php //echo $markup; ?>" <?php //echo ($job_date != '' ? 'style="z-index: -1;"' : ''); ?>>	
 -->
																				<?php //else: ?>
																					<input type="text" name="project_markup" id="project_markup" class="quick_input form-control text-right project_markup" tabindex="12" placeholder="Markup %" value="<?php echo $markup; ?>" >
																					

																				<?php //endif; ?>
																				
																			<?php endif; ?>
																			


																		</div>
																	</div>
		

																	<div class="col-sm-12 m-bottom-10 clearfix">
																		<label for="install_time_hrs" class="col-sm-4 control-label m-top-5 text-left">Site Hours</label>																		
																		<div class="input-group ">
																			<span class="input-group-addon">Hrs</span>


																			<?php if($job_date != ''): ?>
																				<p class="form-control text-right"><?php echo $install_time_hrs; ?></p>
																				<input type="hidden" placeholder="Site Hours" class="quick_input form-control text-right hide hidden" id="install_time_hrs"  name="install_time_hrs" value="<?php echo $install_time_hrs; ?>" <?php echo ($job_date != '' ? 'style="z-index: -1;"' : ''); ?>>
																			<?php else: ?>

																				<?php //if($this->invoice->if_project_invoiced_full($project_id)): ?>
																					<!-- <p class="form-control text-right"><?php //echo $install_time_hrs; ?></p>
																					<input type="hidden" placeholder="Site Hours" class="quick_input form-control text-right hide hidden" id="install_time_hrs"  name="install_time_hrs" value="<?php //echo $install_time_hrs; ?>" <?php //echo ($job_date != '' ? 'style="z-index: -1;"' : ''); ?>> -->
																				<?php //else: ?>
																					<input type="text" placeholder="Site Hours" class="quick_input form-control text-right" id="install_time_hrs"  name="install_time_hrs" value="<?php echo $install_time_hrs; ?>" >
																				<?php //endif; ?>

																			<?php endif; ?>
																			


																		</div>
																	</div> 

																	<div class="col-sm-12 m-bottom-10 clearfix green-estimate">
																		<label for="budget_estimate_total" class="col-sm-4 control-label m-top-5 text-left">Project Estimate</label>
																		<div class="input-group ">
																			<span class="input-group-addon">($)</span>
																			<input type="text" placeholder="Project Estimate" class="quick_input form-control text-right number_format" id="budget_estimate_total" name="budget_estimate_total" value="<?php echo number_format($budget_estimate_total); ?>">
																		</div>
																	</div>

																	<div class="col-sm-12 m-bottom-10 clearfix">
																	<label for="site_start" class="col-sm-4 control-label m-top-5 text-left">Site Start</label>
																		<div class="col-sm-8  col-xs-12">
																			<input tabindex="6" type="text" title="Warning: Changing Site Start Date when project is already in WIP or Previously in WIP, will reset its labour sched, and will also change date range of of Project Schedule." placeholder="DD/MM/YYYY" class="tooltip-enabled quick_input form-control text-right" id="site_start" name="site_start" value="<?php echo $date_site_commencement; ?>" autocomplete="off">
																		</div>
																	</div>

																	<div class="col-sm-12 m-bottom-10 clearfix">
																	<label for="site_finish" class="col-sm-4 control-label m-top-5 text-left">Site Finish</label>
																		<div class="col-sm-8  col-xs-12">
																			<input tabindex="7" type="text" title="Warning: Changing Site Start Date when project is already in WIP or Previously in WIP, will reset its labour sched, and will also change date range of of Project Schedule." placeholder="DD/MM/YYYY" class="tooltip-enabled quick_input form-control text-right" id="site_finish" name="site_finish" value="<?php echo $date_site_finish; ?>" autocomplete="off">
																		</div>
																	</div>


																	<?php if(  $this->session->userdata('quote_deadline') == 1 ||  $this->session->userdata('is_admin') == 1  ): ?>
																		<div class="col-sm-12 m-bottom-10 clearfix">
																			<label for="quote_deadline" class="col-sm-4 control-label m-top-5 text-left">Client Quote Deadline</label>
																			<div class="col-sm-8  col-xs-12 tooltip-enabled" data-original-title="Estimator deadline is set at one (1) working day prior to this by default">
																				<input tabindex="8" type="text" placeholder="DD/MM/YYYY" class=" quote_deadline form-control text-right" id="quote_deadline" name="quote_deadline" value="<?php echo $quote_deadline_date; ?>" autocomplete="off">
																			</div>
																		</div>
																	<?php endif;  ?>





																</div>

															</div>
														</div>


<script type="text/javascript">



 $('.quote_deadline').val('<?php echo $quote_deadline_date; ?>');
 $('#job_date').val('<?php echo $job_date; ?>');

$('#quote_deadline').datetimepicker({
    daysOfWeekDisabled: [0,6],format: 'DD/MM/YYYY',
useCurrent: false, //Important! See issue #1075
});

 
/*
$('.job-date-set').datetimepicker({
   format: 'DD/MM/YYYY',maxDate: new Date,
useCurrent: true, //Important! See issue #1075
});
*/


$("input.job-date-set").datetimepicker({
  useCurrent: false,  format: 'DD/MM/YYYY',maxDate: moment().startOf('day').add(1, 'days').subtract(1, 'seconds')
}).on('dp.show', function() {
  return $(this).data('DateTimePicker').defaultDate('<?php echo $job_date; ?>');
});




$('#site_start').datetimepicker({ format: 'DD/MM/YYYY' ,useCurrent: false});
$('#site_finish').datetimepicker({
useCurrent: false, //Important! See issue #1075
format: 'DD/MM/YYYY'
});
$("#site_start").on("dp.change", function (e) {
$('#site_finish').data("DateTimePicker").minDate(e.date);

$('#site_finish').datetimepicker({
useCurrent: false, //Important! See issue #1075
format: 'DD/MM/YYYY'
});

$('#summ_starting_date').text( e.date.format('DD/MM/YYYY') );

});
$("#site_finish").on("dp.change", function (e) {
$(this).data("DateTimePicker").minDate(e.date);
$('#site_start').data("DateTimePicker").maxDate(e.date);
$('#summ_end_date').text( e.date.format('DD/MM/YYYY') );
});

 

</script>




					<?php if( ($job_category == 'Company' && $this->session->userdata('company_project') == 1) || $this->session->userdata('is_admin') == 1 || $this->session->userdata('user_role_id') == 3   || $this->session->userdata('user_role_id') ==2 || $this->session->userdata('user_role_id') == 16 ):  ?>



															<button type="submit" tabindex="33" class="btn btn-success m-top-10"><i class="fa fa-floppy-o"></i> Save Changes</button>


														<?php elseif($this->session->userdata('projects') >= 2 &&  ($job_category != 'Company' && $this->session->userdata('company_project') != 1 )  ): ?>


															<button type="submit" tabindex="33" class="btn btn-success m-top-10"><i class="fa fa-floppy-o"></i> Save Changes</button>

														<?php endif; ?>

													</form>

													

													

												</div>
												<div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
													<?php if(@$this->session->flashdata('full_update')): ?>
														<div class="m-top-10">
															<div class="border-less-box alert alert-success fade in">
																<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
																<?php echo $this->session->flashdata('full_update');?>
															</div>
														</div>
													<?php endif; ?>
													<div class="box ">
														<div class="box-head pad-5"><label><i class="fa fa-info-circle fa-lg"></i> Details</label></div>
														<div class="box-area pad-5 pad-bottom-10">
															
															<div class="row">

																<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 m-bottom-10 clearfix">
																	<div class="pad-15 no-pad-t">


																		<h4><i class="fa fa-map-marker"></i> Site Address</h4>
																		<?php $shop_tenancy_numb = ($job_type != 'Shopping Center' ? '' : ''.$shop_tenancy_number.': '.$shopping_common_name.'<br />' ); ?>
																		<?php $unit_level =  ($unit_level != '' ? 'Unit/Level:'.$unit_level.',' : '' ); ?>
																		<p><?php echo "$shop_tenancy_numb $unit_level $unit_number $street<br />$suburb, $state, $postcode"; ?><br /><br /></p>
																		
																		<?php $ps_shop_tenancy_numb = ($job_type != 'Shopping Center' ? '' : ''.$shop_tenancy_number.': '.$shopping_common_name ); ?>
																		<input type="hidden" id = "hidden_proj_site_address" value = "<?php echo $ps_shop_tenancy_numb.' '.$unit_level.' '.$unit_number.' '.$street.', '. $suburb.', '.$state.', '.$postcode ?>">


																		<h4><i class="fa fa-map-marker"></i> Invoice Address</h4>
																		<?php $i_po_box =  ($i_po_box != '' ? 'PO BOX:'.$i_po_box.',' : '' ); ?>
																		<?php $i_unit_level =  ($i_unit_level != '' ? 'Unit/Level:'.$i_unit_level.',' : '' ); ?>
																		<p><?php echo "$i_po_box $i_unit_level $i_unit_number $i_street, $i_suburb, $i_state, $i_postcode"; ?></p>
																		<hr />
																		<h4><i class="fa fa-users"></i> Personnel</h4>
																		<p class="clearfix">
																			<span class="text-left">Project Manager:</span>
																			<span class="text-right  pull-right"><strong><?php echo "$pm_user_first_name $pm_user_last_name"; ?></strong></span>
																		</p>
																		<p class="clearfix">
																			<span class="text-left">Project Admin:</span>
																			<span class="text-right pull-right"><strong><?php echo "$pa_user_first_name $pa_user_last_name"; ?></strong></span>
																		</p>
																		<p class="clearfix">
																			<span class="text-left">Estimator:</span>
																			<span class="text-right  pull-right"><strong>

																			<?php echo ($pe_user_first_name != '' ? $pe_user_first_name.' '.$pe_user_last_name : 'None' ); ?></strong></span>
																		</p>

																		<p class="clearfix">
																			<span class="text-left">FOCUS - Client Contact:</span>
																			<span class="text-right  pull-right"><strong>

																			<?php echo ($cc_pm_user_first_name != '' ? ucwords($cc_pm_user_first_name).' '.ucwords($cc_pm_user_last_name) : 'None' ); ?>


																			</strong></span>
																		</p>
																			<p class="clearfix">		
                                                                                <span class="text-left">Joinery Personnel:</span>		
                                                                                <span class="text-right pull-right"><strong><?php echo "$joinery_user_first_name $joinery_user_last_name"; ?></strong></span>		
                                                                            </p>
	                                                                            
																		<?php //if($this->session->userdata('is_admin') == 1 ): ?>

																			<?php if (isset($lead_hand_user_id) && $lead_hand_user_id != 0 || !empty($lead_hand_user_first_name)): ?>
																				<p class="clearfix">
																					<span class="text-left">Leading Hand:</span>
																					<span class="text-right  pull-right"><strong><?php echo (!empty($lead_hand_user_first_name) ? "$lead_hand_user_first_name" : "").' '.(!empty($lead_hand_user_last_name) ? "$lead_hand_user_last_name" : ""); ?></strong></span>
																				</p>
																			<?php endif; ?>

																		<?php //endif; ?>
																				

																	</div>
																</div>

																<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 m-bottom-10 clearfix">

																	<div class="pad-15 no-pad-t border-left">
																		<h4><i class="fa fa-book"></i> General</h4>
																		<p class="clearfix">
																			<span class="text-left">Client:</span>
																			<span class="text-right  pull-right"><strong><?php echo $client_company_name; ?></strong></span>
																		</p>
																		<p class="clearfix">
																			<span class="text-left">Contact Person:</span>
																			<span class="text-right  pull-right"><strong><?php echo "$contact_person_fname $contact_person_lname"; ?></strong></span>
																		</p>
																		<p class="clearfix">
																			<span class="text-left">Client PO:</span>
																			<span class="text-right  pull-right"><strong><?php echo $client_po; ?></strong></span>
																		</p>
																		<p class="clearfix">
																			<span class="text-left">Job Date:</span>
																			<span class="text-right  pull-right"><strong><?php echo $job_date; ?></strong></span>
																		</p>
																		<p class="clearfix">
																			<span class="text-left">Job Type:</span>
																			<span class="text-right  pull-right"><strong><?php echo $job_type; ?></strong></span>
																		</p>
																		<p class="clearfix">
																			<span class="text-left">Category:</span>
																			<span class="text-right  pull-right"><strong><?php echo $job_category; ?></strong></span>
																		</p>
																		<p class="clearfix">
																			<span class="text-left">Site Start:</span>
																			<span class="text-right  pull-right"><strong><?php echo $date_site_commencement; ?></strong></span>
																		</p>
																		<p class="clearfix">
																			<span class="text-left">Site Finish:</span>
																			<span class="text-right  pull-right"><strong><?php echo $date_site_finish; ?></strong></span>
																		</p>

																		<hr />

																		<p class="clearfix">
																			<span class="text-left">Brand:</span>
																			<span class="text-right  pull-right"><strong><?php echo $brand_name; ?></strong></span>
																		</p>

																		<p class="clearfix tooltip-enabled" data-original-title="Estimator deadline is set at one (1) working day prior to this by default">
																			<span class="text-left">Client Quote Deadline:</span>
																			<span class="text-right  pull-right"><strong><?php echo $quote_deadline_date; ?></strong></span>
																		</p>

																	</div>																	
																</div>

																<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 m-bottom-10 clearfix">

																	<div class="pad-15 no-pad-t border-left">
																		<h4><i class="fa fa-bars"></i> Project Details</h4>
																		<p class="clearfix">
																			<span class="text-left">Is Double Time?</span>
																			<span class="text-right  pull-right"><strong><?php echo ($is_double_time == 1 ? 'Yes' : 'No'); ?></strong></span>
																		</p>
																		<p class="clearfix">
																			<span class="text-left">Is WIP?</span>
																			<span class="text-right  pull-right"><strong><?php echo ($is_wip == 1 ? 'Yes' : 'No'); ?></strong></span>
																		</p>
																		<p class="clearfix green-estimate">
																			<span class="text-left">Project Estimate:</span>
																			<span class="text-right  pull-right"><strong>$<?php echo number_format($budget_estimate_total); ?></strong></span>
																		</p>
																		<p class="clearfix">
																			<span class="text-left">Site Hours:</span>
																			<span class="text-right  pull-right"><strong><?php echo $install_time_hrs; ?> HRS</strong></span>
																		</p>
																		<p class="clearfix green-estimate">
																			<span class="text-left">Site Labour Estimate:</span>
																			<span class="text-right  pull-right"><strong><?php echo $labour_hrs_estimate; ?> HRS</strong></span>
																		</p>
																		<p class="clearfix">
																			<span class="text-left">Project Area:</span>
																			<span class="text-right  pull-right"><strong><?php echo $project_area; ?> SQM</strong></span>
																		</p>

																		<hr />
																		<p class="clearfix">
																			<span class="text-left">Focus:</span>
																			<span class="text-right  pull-right"><strong><?php echo $focus_company_name; ?></strong></span>
																		</p>
																		<p class="clearfix">
																			<span class="text-left">Project Created by:</span>
																			<span class="text-right  pull-right"><strong><?php echo $user_first_name; ?> <?php echo $user_last_name; ?></strong></span>
																		</p>
																		<p class="clearfix">
																			<span class="text-left">Date Created:</span>
																			<span class="text-right  pull-right"><strong><?php echo $project_date; ?></strong></span>
																		</p>
																	</div>																	
																</div>

																<div class="clearfix text-left col-xs-12 col-md-9">

																	<div class="row">
																		<div class="clearfix text-left col-xs-12">
																			<div class="pad-15 no-pad-t">
																				<hr />
																				<h4><i class="fa fa-book"></i> Project Notes</h4>
																			
									<?php //echo nl2br($project_comments); ?>
									<pre style=" white-space: pre-wrap !important;       /* Since CSS 2.1 */
												  white-space: -moz-pre-wrap !important;  /* Mozilla, since 1999 */
												  white-space: -pre-wrap !important;      /* Opera 4-6 */
												  white-space: -o-pre-wrap !important;    /* Opera 7 */
												  word-wrap: break-word !important;       /* Internet Explorer 5.5+ */ "><?php echo $project_comments; ?></pre>
																			</div>
																		</div>

																	</div>
																</div>

																<div class="clearfix col-xs-12 col-md-3">


																<?php //if($this->session->userdata('projects') >= 2): ?>

																	<?php 
/*
																<a href="<?php echo base_url(); ?>projects/update_project_details/<?php echo $project_id; ?>" type="submit" tabindex="33" class="btn btn-success m-top-20 m-right-15 pull-right"><i class="fa fa-pencil-square-o"></i> Update Full Details</a>
															*/
																?>
																<?php //endif; ?>




									    		<?php if( ($job_category == 'Company' && $this->session->userdata('company_project') == 1) || $this->session->userdata('is_admin') == 1 ):  ?>


																<a href="<?php echo base_url(); ?>projects/update_project_details/<?php echo $project_id; ?>" type="submit" tabindex="33" class="btn btn-success m-top-20 m-right-15 pull-right"><i class="fa fa-pencil-square-o"></i> Update Full Details</a>
															

									    		<?php elseif($this->session->userdata('projects') >= 2 &&  ($job_category != 'Company' && $this->session->userdata('company_project') != 1 )  ): ?>


																<a href="<?php echo base_url(); ?>projects/update_project_details/<?php echo $project_id; ?>" type="submit" tabindex="33" class="btn btn-success m-top-20 m-right-15 pull-right"><i class="fa fa-pencil-square-o"></i> Update Full Details</a>
															
									    		<?php endif; ?>




																</div>
															</div>
																


														</div>
													</div>
												</div>
												</div>

										</div>


										<div class="tab-pane fade in  clearfix <?php echo ($curr_tab == 'project-schedule' ? 'active' : '' ); ?>" id="project_schedule">
											<div class="m-bottom-15 clearfix m-top-10">
												<?php 
													echo $this->project_schedule->view_project_schedule(); 
												?>
											</div>
										</div>


										<div class="tab-pane fade in clearfix <?php echo ($curr_tab == 'works' ? 'active' : '' ); ?>" id="works">
											<div class="m-bottom-15 clearfix m-top-10">
												<?php echo $this->projects->works_view(); ?>
											</div>
										</div>
										
										<div class="tab-pane fade in  clearfix <?php echo ($curr_tab == 'variations' ? 'active' : '' ); ?>" id="variations">
											<div class="m-bottom-15 clearfix m-top-10">
												<?php 
													if($variation == 'variation'){
														echo $this->variation->variation_works_view(); 
													}else{
														echo $this->variation->variations_view(); 
													}
												?>
											</div>
										</div>
										
										<div class="tab-pane fade in  clearfix <?php echo ($curr_tab == 'attachments' ? 'active' : '' ); ?>" id="attachments">
											<div class="m-bottom-15 clearfix m-top-10">
												<?php echo $this->attachments->attachments_view(); ?>
											</div>
										</div>

										<div class="tab-pane fade in  clearfix <?php echo ($curr_tab == 'send_pdf' ? 'active' : '' ); ?>" id="send_pdf">
											<div class="m-bottom-15 clearfix m-top-10">
												<?php echo $this->send_emails->send_pdf(); ?>
											</div>
										</div>
									</div>
								</div>				
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<!-- MODAL -->
<div class="modal fade" id="contract_notes_reports_tab" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
	    <div class="modal-content">
	        <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		        <h4 class="modal-title">Contract Notes</h4>
	        </div>
	        <div class="modal-body" style = "height: 250px">
	        	<div class="col-sm-12 m-bottom-10 clearfix <?php if(form_error('officeNumber')){ echo 'has-error has-feedback';} ?>">
					<label for="company_prg" class="col-sm-4 control-label">Contract Date*</label>
					<div class="col-sm-8">														
						<div class="input-group <?php if(form_error('company_prg')){ echo 'has-error has-feedback';} ?>">
							<span class="input-group-addon"><i class="fa fa-calendar  fa-lg"></i></span>
							<input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control datepicker" id="reports_contract_date" name="contract_date">
						</div>
					</div>
				</div>
				<?php if($job_category !== 'Design Works'): ?>
	          	<div class="col-sm-12 m-bottom-10 clearfix">
					<label for="company_prg" class="col-sm-4 control-label">Plans, Elevations and Drawings:</label>
					<div class="col-sm-8">														
						<textarea class = "form-control input-sm" id = "reports_plans_elv_draw" maxlength="43"></textarea>
					</div>
				</div>
				<?php endif; ?>
				<div class="col-sm-12 m-bottom-10 clearfix">
					<label for="company_prg" class="col-sm-4 control-label">Schedule of Works Include in Quotation:</label>
					<div class="col-sm-8">														
						<textarea class = "form-control input-sm" id = "reports_sched_work_quotation" maxlength="43"></textarea>
					</div>
				</div>
				<div class="col-sm-12 m-bottom-10 clearfix">
					<label for="company_prg" class="col-sm-4 control-label">Condition of Quotation and Contract</label>
					<div class="col-sm-8">
						<textarea class = "form-control input-sm" id = "reports_condition_quote_contract" maxlength="43"></textarea>
					</div>
				</div>
	        </div>
	        <div class="modal-footer">
	        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	          	<?php if($job_category == 'Design Works'): ?>
	          	<button type="button" class="btn btn-primary" id = "create_design_contract"><i class="fa fa-file-pdf-o  fa-lg"></i> Create Design Contract</button>
	          	<?php else: ?>
	          	<button type="button" class="btn btn-primary" id = "create_contract"><i class="fa fa-file-pdf-o  fa-lg"></i> Create Contract</button>
	          	<?php endif; ?>
	        </div>
	    </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="project_sched_confirmation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static"  data-keyboard="false">
    <div class="modal-dialog">
	    <div class="modal-content">
	        <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		        <h4 class="modal-title">Confirmation</h4>
	        </div>
	        <div class="modal-body">
	        	<label for="">Project Schedule is not yet Created, Do you want to create Project Schedule Now?</label>
	        	<label for="" style = "color:red"><b>NOTE:</b> This will be done once, works not added here will be done manually.</label>
	        </div>
	        <div class="modal-footer">
	        	<button type="button" class="btn btn-primary" data-dismiss="modal" id = "yes_create_project_sched">Yes</button>
	        	<button type="button" class="btn btn-success" id = "dont_create_project_sched">No</button>
	        </div>
	    </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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



<div id="job_book_area" style="display:none">
	<img src="<?php echo base_url(); ?>img/focus-logo-print.png" width="206" height="66" />
	<strong class="pull-right" style="margin-top: 48px;font-size: 16px;"><?php echo $focus_company_name; ?></strong>
	<div class="header clearfix border-1">
		<p class="pull-left">Client: <strong><?php echo $client_company_name; ?></strong> - <strong><?php echo $job_category; ?></strong><br />Project: <strong><?php echo $project_name; ?> <?php echo $client_po; ?></strong></p>
		<p class="pull-right"><strong>Job Book</strong><br />Project No. <strong><?php echo $project_id; ?></strong></p>
	</div>
	<hr />
	<div class="full clearfix mgn-10">
		<div class="one-fourth"><p>Contact: <strong><?php echo $contact_person_fname.' '.$contact_person_lname; ?></strong></p></div>
		<div class="one-fourth"><p><?php if($contact_person_phone_office != ''): echo 'Office No: <strong>'.$contact_person_phone_office.'</strong>'; endif; ?></p></div>
		<div class="one-fourth"><p><?php if($contact_person_phone_mobile != ''): echo 'Mobile No: <strong>'.$contact_person_phone_mobile.'</strong>'; endif; ?></p></div>
		<div class="one-fourth"><p><?php if($contact_person_phone_direct != ''): echo 'Direct No: <strong>'.$contact_person_phone_direct.'</strong>'; endif; ?></p></div>
	</div>

	<fieldset class="pad-10 border-1">
		<legend class="pad-l-10 pad-r-10"><strong>Client / Company Address</strong></legend>
		<div class="full clearfix">
			<div class="one-third">
				<p class=""><strong><?php echo $client_company_name; ?></strong></p>
				<p><?php echo $query_client_address_unit_number.' '.$query_client_address_unit_level.' '.$query_client_address_street; ?></p>
				<p class=""><?php echo $query_client_address_suburb.' '.$query_client_address_state.' '.$query_client_address_postcode; ?></p>
			</div>
			<div class="one-third">
				<p class=""><?php if($company_contact_details_office_number != ''): echo 'Office No: <strong>'.$company_contact_details_area_code.' '.$company_contact_details_office_number.'</strong>'; endif; ?></p>
				<p class=""><?php if($company_contact_details_direct_number != ''): echo 'Direct No: <strong>'.$company_contact_details_area_code.' '.$company_contact_details_direct_number.'</strong>'; endif; ?></p>
				<p class=""><?php if($company_contact_details_mobile_number != ''): echo 'Mobile No: <strong>'.$company_contact_details_mobile_number.'</strong>'; endif; ?></p>
				<p class=""><?php if($company_contact_details_after_hours != ''): echo 'After Hours: <strong>'.$company_contact_details_area_code.' '.$company_contact_details_after_hours.'</strong>'; endif; ?></p>
			</div>
			<div class="one-third">
				<p class=""><?php if($company_contact_details_general_email != ''): echo 'General Email: <strong>'.$company_contact_details_general_email.'</strong>'; endif; ?></p>
				<p class=""><?php if($company_contact_details_direct != ''): echo 'Direct Email: <strong>'.$company_contact_details_direct.'</strong>'; endif; ?></p>
				<p class=""><?php if($company_contact_details_accounts != ''): echo 'Accounts Email: <strong>'.$company_contact_details_accounts.'</strong>'; endif; ?></p>
				<p class=""><?php if($company_contact_details_maintenance != ''): echo 'Maintenance Email: <strong>'.$company_contact_details_maintenance.'</strong>'; endif; ?></p>
			</div>
		</div>
	</fieldset>

	<fieldset class="pad-10 border-1 mgn-top-10">
		<legend class="pad-l-10 pad-r-10"><strong>Address</strong></legend>
		<div class="full clearfix">
			<div class="one-half">
				<div class="border-right-2 pad-r-10">
					<p class=""><strong>Site</strong></p>
					<p><?php $shop_tenancy_numb = ($job_type != 'Shopping Center' ? '' : ''.$shop_tenancy_number.', '.$shopping_common_name.'<br />' ); ?>
					<p><?php echo "$shop_tenancy_numb $unit_level $unit_number $street<br />$suburb, $state, $postcode"; ?></p>			
				</div>
			</div>
			<div class="one-half">
				<div class="pad-l-10">
					<p class=""><strong>Invoice</strong></p>
					<p><?php echo "$i_po_box $i_unit_level $i_unit_number $i_street<br />$i_suburb, $i_state,  $i_postcode"; ?></p>
				</div>
			</div>
		</div>
	</fieldset>

	<fieldset class="pad-10 border-1 mgn-top-10">
		<legend class="pad-l-10 pad-r-10"><strong>Project Totals</strong></legend>
		<div class="full clearfix">
			<div class="one-half">
				<div class="pad-r-10">
					<p class="">Quotes Total : <strong class="pull-right">$<?php echo number_format($final_total_quoted,2); ?></strong></p>
					<p class=""><?php echo $admin_gst_rate; ?>% GST : <strong class="pull-right">$<?php echo number_format($final_total_quoted*($admin_gst_rate/100),2); ?></strong></p>
					<p class="">Total (inc GST) : <strong class="pull-right">$<?php echo number_format($final_total_quoted+($final_total_quoted*($admin_gst_rate/100)),2); ?></strong></p>
				</div>
			</div>
			<div class="one-half">
				<div class="pad-l-10">
					<p class="">Variations Total : <strong class="pull-right">$<?php echo number_format($variation_total,2); ?></strong></p>
					<p class=""><?php echo $admin_gst_rate; ?>% GST : <strong class="pull-right">$<?php echo number_format($variation_total*($admin_gst_rate/100),2); ?></strong></p>
					<p class="">Total (inc GST) : <strong class="pull-right">$<?php echo number_format($variation_total+($variation_total*($admin_gst_rate/100)),2); ?></strong></p>				
				</div>
			</div>
		</div>
	</fieldset>	

	<div class="full clearfix">
		<div class="one-half">
			<div class="pad-r-10">
				<fieldset class="pad-10 border-1 mgn-top-10">
					<legend class="pad-l-10 pad-r-10"><strong>Details</strong></legend>
					<div class="full clearfix">
						<p class="">Representative : <strong class="pull-right"><?php echo "$pm_user_first_name $pm_user_last_name"; ?></strong></p>
						<p class="">Job Date : <strong class="pull-right"><?php echo $job_date; ?></strong></p>
						<p class="">Start Date : <strong class="pull-right"><?php echo $date_site_commencement; ?></strong></p>
						<p class="">Expected Finish Date : <strong class="pull-right"><?php echo $date_site_finish; ?></strong></p>
						<p class="">PO Number : <strong class="pull-right" class="pull-right"><?php echo $client_po; ?></strong></p>
					</div>
				</fieldset>
				<p>&nbsp;</p>
				
				<p><strong>Notes</strong></p>
				<div class="print_job_book_notes"></div>
			</div>
		</div>

		<div class="one-half">
			<div class="pad-l-10">
				<fieldset class="pad-10 border-1 mgn-top-10">
					<legend class="pad-l-10 pad-r-10"><strong>Invoices</strong></legend>
					<div class="full clearfix invoices_list_item">
						<?php $this->projects->list_invoiced_items($project_id,$final_total_quoted,$variation_total); ?>
					</div>
				</fieldset>



				<?php if($include_invoice_comments == 1): ?>
					<p>&nbsp;</p>
					<p><strong style="color: red;">Invoice Comments<br /><?php echo nl2br(htmlspecialchars($invoice_comments)); ?></strong></p>
					
				<?php endif; ?>


			</div>

		</div>		
	</div>

	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<hr />
	<p><?php echo mdate($datestring, $time); ?></p>


</div>


<script type="text/javascript">
	var set_job_date_from_projects = '<?php echo $job_date; ?>';
	var set_if_fully_invoiced =  <?php echo (  $this->invoice->is_vr_invoiced($project_id)  && $this->invoice->if_has_vr($project_id) > 0   ? '1' : '0' ); ?>;

	window.set_work_default = function(){
		localStorage.setItem("local_storage_set", "0");
		
	}
</script>


<?php $this->load->view('assets/logout-modal'); ?>