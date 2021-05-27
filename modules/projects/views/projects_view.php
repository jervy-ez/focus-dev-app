<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>
<?php $this->load->module('projects'); ?>
<?php $this->load->module('attachments'); ?>
<?php $this->load->module('send_emails'); ?>
<?php $this->load->module('variation'); ?>
<?php $this->load->module('invoice'); ?>

<script src="<?php echo base_url(); ?>js/vue.js"></script>
<script src="<?php echo base_url(); ?>js/vue-select.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>css/vue-select.css">
<script src="<?php echo base_url(); ?>js/moment.min.js"></script>
<script src="<?php echo base_url(); ?>js/jmespath.js"></script>
<script src="<?php echo base_url(); ?>js/axios.min.js"></script>

<?php
$currentPageUrl = 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
//echo "Current page URL " . $currentPageUrl;
$url_arr = explode("/", $currentPageUrl);
$num = count($url_arr)-1;
if($url_arr[$num] == 'ds'){
	echo '<script>var is_ds = 1</script>';
}else{
	echo '<script>var is_ds = 0</script>';
}
echo '<script>window.history.pushState("","","'.base_url().'projects/view/'. $project_id.'");</script>';
// $url = 'http://www.example.com/news?q=string&f=true&id=1233&sort=true';
// $values = parse_url($url);
// $host = explode('.',$values['host']);
// echo $host[1];
?>


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
				echo '<script>window.history.pushState("","",'. $project_id.')</script>';
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
			if (isset($_GET['tab'])){

				switch($_GET['tab']){
					case 'invoice':
						$curr_tab = 'invoice';
						break;
					case 'works':
						$curr_tab = 'works';
						break;
					case 'variations':
						$curr_tab = 'variations';
						break;
					default:
						$curr_tab = 'project-details';	
						break;
				}
			} else {
				$curr_tab = 'works';
			}
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
					echo '<script>window.history.pushState("","",'. $project_id.')</script>';
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
			if (isset($_GET['tab'])){

				switch($_GET['tab']){
					case 'invoice':
						$curr_tab = 'invoice';
						break;
					case 'works':
						$curr_tab = 'works';
						break;
					case 'variations':
						$curr_tab = 'variations';
						break;
					default:
						$curr_tab = 'project-details';	
						break;
				}
			} else {
				$curr_tab = 'project-details';	
			}
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

<?php 
	if(isset($_GET['tab'])){
		if ($_GET['tab'] == 'variations'){
?>
	<input type="hidden" id="tab_variations_hidden" name="tab_variations_hidden" value="<?php echo $_GET['tab']; ?>">
<?php
		}
	}

?>

<?php 
	if(isset($_GET['curr_tab'])){
		if ( $_GET['curr_tab'] == 'variations'){
?>
	<input type="hidden" id="tab_variations_hidden" name="tab_variations_hidden" value="<?php echo $_GET['curr_tab']; ?>">
<?php
		}
	}

?>

<!-- title bar 
estimate-->
<?php 
$date = str_replace('/', '-', $project_date);
$project_date_created = date('Y-m-d', strtotime($date)); 
$filtered_date = $induction_commencement_date;

?>
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
            <a class="btn-small prj_amndnts_bttn"><i class="fa fa-file-text-o"></i> Amendments</a>
          </li>
          
					<li>
						<a href="<?php echo base_url(); ?>projects/update_project_details/<?php echo $project_id; ?>" class="btn-small sub-nav-bttn">Project Details</a>
					</li>
					<?php if($this->session->userdata('user_role_id') == 2 || $this->session->userdata('user_role_id') == 3 || $this->session->userdata('user_role_id') == 20 || $this->session->userdata('user_role_id') == 4 || $this->session->userdata('is_admin') == 1  ): ?>
					<?php if ($project_date_created > $filtered_date): ?>
					<?php if($induction_exempted == 0): ?>
					<li>
						<a href="<?php echo base_url(); ?>induction_health_safety/induction_slide_editor_view?project_id=<?php echo $project_id ?>" class="btn-small"> Induction</a>
					</li>
					<?php endif; ?>
					<?php endif; ?>
					<?php endif; ?>
					<?php if($this->session->userdata('is_admin') == 1 ): ?>
					
					<li>
						<a href="#" class="btn-small view_applied_settings"><i class="fa fa-cog"></i> Applied Settings</a>
					</li>
					<?php endif; ?>

					<?php if($this->session->userdata('projects') >= 1): ?>
						<li>
							<a class="btn-small sb-open-right"><i class="fa fa-file-text-o"></i> Project Comments</a>
						</li>
					<?php endif; ?>

					 	<li>
							<a class="btn-small" data-toggle="modal" data-target="#doc_storage" tabindex="-1" href="#" id="showDocStorage"><em class="fa fa-cloud-upload"></em> Doc Storage</a>
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
										<label class="project_name"><?php echo $project_name; ?> 
										<span class="fa fa-film pointer play_details_vids open_help_vids_mpd" data-toggle="modal" data-target="#help_video_group"> </span>
										<p>Client: <strong><?php echo $client_company_name; ?></strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Project No.<?php echo $project_id; ?></p></label>
									</div>
								</div>

								<div class="col-lg-8 col-md-12">

									<div class="pad-top-15 pad-left-15 pad-bottom-5 clearfix box-tabs">	
										
									<ul id="myTab" class="nav nav-tabs pull-right">
										<?php if($is_pending_client == 0): ?>
										<li class="<?php echo ($curr_tab == 'invoice' ? 'active' : '' ); ?>">
											<a href="#invoices" data-toggle="tab" class="link_tab_invoice" id="<?php echo $project_id ?>"><i class="fa fa-list-alt fa-lg"></i> Invoices</a>
										</li>
										<?php endif; ?>
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
											<a href="#variations" id="tab_variation_btn" data-toggle="tab"><i class="fa fa-cube fa-lg"></i> Variations</a>  <!-- onclick = "load_variation()" -->
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
												<li><a href="<?php echo base_url(); ?>works/proj_summary_w_cost/<?php echo $project_id ?>" target="_blank" onclick = "generateReports('pswc')"><i class="fa fa-file-pdf-o"></i> Project Summary with Cost</a></li>
												<li><a href="<?php echo base_url(); ?>works/proj_summary_wo_cost/<?php echo $project_id ?>" target="_blank" onclick = "generateReports('pswoc')"><i class="fa fa-file-pdf-o"></i> Project Summary without Cost</a></li>
												<li><a href="<?php echo base_url(); ?>works/proj_joinery_summary_w_cost/<?php echo $project_id ?>" target="_blank" onclick = "generateReports('jswc')"><i class="fa fa-file-pdf-o"></i> Joinery Summary with Cost</a></li>
												<li><a href="<?php echo base_url(); ?>works/proj_joinery_summary_wo_cost/<?php echo $project_id ?>" target="_blank" onclick = "generateReports('jswoc')"><i class="fa fa-file-pdf-o"></i> Joinery Summary without Cost</a></li>
												
												<li><a href="<?php echo base_url(); ?>works/variation_summary/<?php echo $project_id ?>" target="_blank" onclick = "generateReports('var_sum')"><i class="fa fa-file-pdf-o"></i> Variations Summary</a></li>
												<li><a href="<?php echo base_url(); ?>works/proj_details/<?php echo $project_id ?>" target="_blank" onclick = "generateReports('proj_details')"><i class="fa fa-file-pdf-o"></i> Project Details</a></li>
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

												<li><a href="<?php echo base_url(); ?>reports/print_project_amendments?project_id=<?php echo $project_id ?>" id="print_project_amendments"><i class="fa fa-file-pdf-o"></i> Project Amendments</a></li>
												<?php if($job_category == "Maintenance"){ ?>
													<li><a href="<?php echo base_url(); ?>projects/service_report/<?php echo $project_id ?>"><i class="fa fa-file-pdf-o"></i> Service Report</a></li>
												<?php } ?>

												<li><a href="<?php echo base_url(); ?>works/safe_site_observation/<?php echo $project_id ?>" target="_blank"><i class="fa fa-file-pdf-o"></i> Safe Site Work Observation - checklist </a></li>

												<li><a href="#" target="_blank" id = "site_diary_pdf"><i class="fa fa-file-pdf-o"></i> Site Diary </a></li>
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

													<input type="hidden" id="current_tab" name="current_tab" value="">

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
																		<?php else: ?>
																				<?php if($job_date == '' ): ?>
																					<?php if ($project_date_created > $filtered_date): ?>
																						<?php if($induction_exempted == 0): ?>
																							<?php if($video_generated == 0): ?>
																								<input type="text" placeholder="DD/MM/YYYY" title="Induction Video is Required." class="pad-10 tooltip-enabled job-date-set form-control  text-right" autocomplete="off" readonly="true">
																							<?php else: ?>
																								<input type="text" placeholder="DD/MM/YYYY" title="Warning: Changing a value in the the Job date affects the project in the WIP section." class="tooltip-enabled job-date-set form-control  text-right"  id="job_date" name="job_date" value="<?php echo $job_date; ?>" autocomplete="off">
																							<?php endif; ?>
																						<?php else: ?>
																							<input type="text" placeholder="DD/MM/YYYY" title="Warning: Changing a value in the the Job date affects the project in the WIP section." class="tooltip-enabled job-date-set form-control  text-right"  id="job_date" name="job_date" value="<?php echo $job_date; ?>" autocomplete="off">
																						<?php endif; ?>
																					<?php else: ?>
																						<input type="text" placeholder="DD/MM/YYYY" title="Warning: Changing a value in the the Job date affects the project in the WIP section." class="tooltip-enabled job-date-set form-control  text-right"  id="job_date" name="job_date" value="<?php echo $job_date; ?>" autocomplete="off">
																					<?php endif; ?>
																				<?php elseif($this->session->userdata('is_admin') == 1 || $this->session->userdata('job_date') == 1 || ( $this->session->userdata('user_role_id') == 7 && $job_category == 'Maintenance' )  ): ?>
																					<input type="text" placeholder="DD/MM/YYYY" title="Warning: Changing a value in the the Job date affects the project in the WIP section." class="pad-10 tooltip-enabled job-date-set form-control  text-right" id="job_date" name="job_date" value="<?php echo $job_date; ?>" autocomplete="off">
																				<?php elseif( $this->session->userdata('company_project') == 1 && $job_category == 'Company' ): ?>
																					<input type="text" placeholder="DD/MM/YYYY" title="Warning: Changing a value in the the Job date affects the project in the WIP section." class="pad-10 tooltip-enabled job-date-set form-control  text-right" id="job_date" name="job_date" value="<?php echo $job_date; ?>" autocomplete="off">
																				<?php else: ?>
																					<p title="Warning: You need to request to the Project Manager to change the Job Date" class="form-control tooltip-enabled text-right" ><?php echo $job_date; ?></p>
																				<?php endif; ?>
																		<?php endif; ?>
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
																			<input type="text" placeholder="Project Estimate" class="quick_input form-control text-right number_format tooltip-test" data-original-title = "This must be an accurate estimate" id="budget_estimate_total" name="budget_estimate_total" value="<?php echo number_format($budget_estimate_total); ?>">
																			<script type="text/javascript">
																				var estimate_value = 0;
																				$("#budget_estimate_total").click(function(){
																					estimate_value = $("#budget_estimate_total").val();
																				});

																				$("#budget_estimate_total").blur(function(){
																					var proj_estimate = $("#budget_estimate_total").val();
																					if(proj_estimate < 11){
																						alert("Estimate must be an accurate estimate");
																						$("#budget_estimate_total").val(estimate_value);
																					}
																				});
																			</script>
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

	var date_start = $(this).val();
	var date_finish = $('#site_finish').val();

	if(date_finish){
		var date_s_tmsp = moment(date_start,'DD/MM/YYYY').format('x');
		var date_f_tmsp = moment(date_finish,'DD/MM/YYYY').format('x');
		if(date_s_tmsp > date_f_tmsp){
			$(this).val('');
			alert('Site Start date selected conflicts to Site Finish, please selecte another date.')
		}
	}
});


$("#site_finish").on("dp.change", function (e) {
	$(this).data("DateTimePicker").minDate(e.date);
	$('#site_start').data("DateTimePicker").maxDate(e.date);
	$('#summ_end_date').text( e.date.format('DD/MM/YYYY') );

	var date_start = $('#site_start').val();
	var date_finish = $(this).val();

	if(date_start){
		var date_s_tmsp = moment(date_start,'DD/MM/YYYY').format('x');
		var date_f_tmsp = moment(date_finish,'DD/MM/YYYY').format('x');
		if(date_s_tmsp > date_f_tmsp){
			$(this).val('');
			alert('Site Finish date selected conflicts to Site Start, please selecte another date.')
		}
	}

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
												  word-wrap: break-word !important;       /* Internet Explorer 5.5+ */ word-break: break-word !important;"><?php echo htmlentities(  str_replace('&apos;', "'",   $project_comments ) ); ?></pre>

<!--
												  	<p style=" white-space: pre-wrap !important;       /* Since CSS 2.1 */
												  white-space: -moz-pre-wrap !important;  /* Mozilla, since 1999 */
												  white-space: -pre-wrap !important;      /* Opera 4-6 */
												  white-space: -o-pre-wrap !important;    /* Opera 7 */
												  word-wrap: break-word !important;       /* Internet Explorer 5.5+ */

												  display: block;
    padding: 9.5px;
    margin: 0 0 10px;
    font-size: 13px;
    line-height: 1.42857143;
    color: #333;
    background-color: #f5f5f5;
    border: 1px solid #ccc;
    border-radius: 4px;     "><?php // echo html_entity_decode($project_comments,ENT_QUOTES | ENT_HTML5); ?></p>
 -->

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

	$('#myTab a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
	    
	    var tab_id = e.target;

	    $('#current_tab').val(tab_id);
	});

	$(document).ready(function() { 





		if ($('#tab_variations_hidden').val() == 'variations'){

			setTimeout(function(){  

				$('#tab_variation_btn').trigger('click');

		  	},100);
		}
	});

	<?php if(isset($_GET['wid'])  &&  $_GET['wid'] != ''): ?>
		var wid = <?php echo $_GET['wid']; ?>;
		var toggle_bttn_val = 0;

		setTimeout(function(){

			$('table#table-wd tr').hide();
			$('table#table-wd tr#row-work-'+wid).show();

			$('#tbl_works .table-footer table th').first().append('<button class="btn btn-xs btn-info pull-left toggle_works_btn set">Toggle List - Works</button>');

			$('.toggle_works_btn.set').click(function(){
				
				if(toggle_bttn_val%2==0){
					$('table#table-wd tr').show();

				}else{
					$('table#table-wd tr').hide();
					$('table#table-wd tr#row-work-'+wid).show();
				}

				toggle_bttn_val++;
			});


	 	},1000);



	<?php endif; ?>



	<?php if(isset($_GET['vid'])  &&  $_GET['vid'] != ''): ?>
		var vid = <?php echo $_GET['vid']; ?>;
		var toggle_bttn_val = 0;

		setTimeout(function(){			
			$('#proj_variation_list table.vr_table_list tr').hide();
			$('#proj_variation_list table.vr_table_list tr#vr_id_'+vid).show();
			$('#tbl_works #proj_variation_list').next().find('table th').first().append('<button class="btn btn-xs btn-info pull-left toggle_vworks_btn set">Toggle List - Variations</button>');

			$('.toggle_vworks_btn.set').click(function(){
				
				if(toggle_bttn_val%2==0){
					$('#proj_variation_list table.vr_table_list tr').show();

				}else{
					$('#proj_variation_list table.vr_table_list tr').hide();
					$('#proj_variation_list table.vr_table_list tr#vr_id_'+vid).show();
				}

				toggle_bttn_val++;
			});

		},1300);

	<?php endif; ?>

</script>




<div id="doc_storage" class="modal fade" tabindex="-1" data-width="760" >
	<div class="modal-dialog  modal-lg">
		<div class="modal-content" style="width:120%;">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true" onClick="window.location.reload();">×</button>
				<h4 class="modal-title"><em class="fa fa-cloud-upload"></em> Document File Storage: <?php echo $project_id; ?></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">

						


						<form method="post" action="<?php echo base_url(); ?>projects/process_upload_file_storage" id="upload_doc_file_prj" enctype="multipart/form-data">
							<input type="hidden" name="will_replace_existing" id="will_replace_existing" value = "0">
							<div class="input-group m-bottom-10" >
								<span id="" class="input-group-addon"><strong>Document Type*</strong></span>
								<select id = "doc_type_name" name="doc_type_name" class="form-control doc_type_name" required="" required="" onchange = "document_type_change()">
									<option disabled="disabled" selected="selected" value="0" class="hide hidden">Select Document Type*</option>
									<?php echo $this->projects->list_doc_type_storage(); ?>
								</select>
							</div>

							<div class="clearfix file_area_box" style="position: relative; border: 1px solid #CCCCCC; border-radius: 5px; overflow: hidden; height: 100px;">				
								<input type="file" multiple="multiple" name="doc_files[]" required="" autocomplete="off" id="doc_files"  onchange="javascript:docUploadedList()" class=" form-control btn-default" style="width: 100%; position: absolute; z-index: 220; box-shadow: none;height: 100px;background: none !important;font-weight: bold;border: none !important;">
								<div id="doc_fileList" style=" border-top: 0;" class=""></div>
							</div>

							<div class=" m-bottom-10 m-top-10">
								<input type="hidden" name="doc_proj_id" id="doc_proj_id" value="<?php echo $project_id; ?>">
								<input type="hidden" name="is_prj_scrn" id="is_prj_scrn" value="1">
								
								<span class="btn btn-success doc_upload_submit"  style=" color:#fff; box-shadow: none;"><i class="fa fa-upload"></i> Upload</span>
							</div>

						</form>


					</div>
				</div>
			</div>
			<hr class="no-m" />
			<div class="modal-body row">

				<h4 class="m-top-0"><i class="fa fa-file-text-o"></i> Uploaded Files  <span style = "font-size: 12px;color: blue">Already Replaced</span>&nbsp;&nbsp;&nbsp;&nbsp;<span style = "font-size: 12px;color: red; padding-right:20px">Updated</span></h4></h4>
				<hr class="no-m" />
				<div class="col-sm-12" id = "docStorageList" ></div>
				<?php //echo $this->projects->list_uploaded_files($project_id); ?>


			</div>
		</div>
	</div>
</div>

<div id="confirmationAutoDocStore" class="modal fade" tabindex="-1" data-width="760" >
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Confirmation</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<b>Do you want the selected report to be uploaded in the Doc Storage?</b>
					</div>
				</div>
			</div>
			<hr class="no-m" />
			<div class="modal-footer row">
				<button type="button" class="btn btn-primary" data-dismiss="modal" id = "autoUploadYes">Yes</button>
	        	<button type="button" class="btn btn-success" data-dismiss="modal">No</button>
			</div>
		</div>
	</div>
</div>

<div id="doc_type_comfirmation" class="modal fade" tabindex="-1" data-width="760">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Confirmation</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<b>Do you want to replace an existing file with same doc type?</b>
					</div>
				</div>
			</div>
			<div class="modal-footer row">
				<button type="button" class="btn btn-primary" data-dismiss="modal" id = "display_existing_doc_file">Yes</button>
	        	<button type="button" class="btn btn-success" data-dismiss="modal">No</button>
			</div>
		</div>
	</div>
</div>

<div id="mdl_for_doc_approval" class="modal fade" tabindex="-1" data-width="760">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true" v-on:click="close_approve_modal">×</button>
				<h4 class="modal-title">For Approval</h4>
			</div>
			<div class="modal-body">
				<input type="hidden" id="file_is_set" value = "0">
				<div class="row">
					<div class="col-md-6">
						<div class="col-sm-12 pad-5"><b>For Approval</b></div>
						<div class="col-sm-12 pad-5" style = "height: 200px; overflow: auto">
							<table class="table table-hover text-nowrap thead-dark" style = "font-size: 12px">
				                <thead>
				                    <tr>
				                    	<th></th>
				                    	<th style = "width: 50px">File Name</th>
				                    	<th>Doc Type</th>
					                    <th style = "width: 70px"></th>
				                    </tr>
				                </thead>
			                  	<tbody>
				                    <tr v-for = "doc_for_approval in doc_for_approval">
				                    	<td>
				                    		<button type = "button" class = "btn btn-success btn-xs" v-on:click = "view_file(doc_for_approval.file_name,doc_for_approval.storage_files_id)" title = "Preview"><i class="fa fa-eye"></i></button>
				                    	</td>
				                     	<td style = "width: 200px; word-break: break-all">
				                     		{{ doc_for_approval.file_name }}
				                     		
				                     	</td>
				                    	<td>{{ doc_for_approval.doc_type_name }}</td>
					                    <td style = "width: 70px">
					                    	<button type = "button" class = "btn btn-warning btn-xs" v-on:click="approve_file(doc_for_approval.storage_files_id)" title = "Approve File">Approve</button>
					                    	<!-- <button type = "button" class = "btn btn-primary btn-xs pull-right" v-on:click="view_replace" title = "View File For Replacement"><i class="fa fa-arrow-circle-right"></i></button> -->
					                    	
					                    </td>
				                    </tr>
				                </tbody>
				            </table>
			            </div>
					</div>
					<div class="col-md-6">
						<div class="col-sm-12 pad-5"><b>For Replacement</b></div>
						<div class="col-sm-12 pad-5" style = "height: 200px; overflow: auto">
							<table class="table table-hover text-nowrap thead-dark" style = "font-size: 12px" v-show = "show_for_replacement">
				                <thead>
				                    <tr>
				                    	<th></th>
				                    	<th>File Name</th>
				                    	<th>Doc Type</th>
				                    </tr>
				                </thead>
			                  	<tbody>
				                    <tr v-for = "doc_for_replacement in doc_for_replacement" v-if="doc_for_replacement.replace_by_storage_files_id == selected_storage_id">
				                    	<td>
				                    		<button type = "button" class = "btn btn-success btn-xs" v-on:click = "view_file(doc_for_replacement.file_name,selected_storage_id)"><i class="fa fa-eye"></i></button>
				                     	</td>
				                     	<td>{{ doc_for_replacement.file_name }}</td>
				                    	<td>{{ doc_for_replacement.doc_type_name }}</td>
				                    </tr>
				                </tbody>
				            </table>
			            </div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 pad-5"><b>Preview</b></div>
					<div class="col-sm-12 pad-5">
						<iframe  :src="pdfFile" style = "position: relative; top: 0; left: 0; width: 100%; height: 300px; background: white" v-show = "show_pdf"></iframe>
					</div>
				</div>
			</div>
			<hr class="no-m"/>
			<div class="modal-footer row">
	        	<button type="button" class="btn btn-default" data-dismiss="modal" v-on:click="close_approve_modal">Close</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var file_is_set = 0;
	var app_approval = new Vue({
	  	el: '#mdl_for_doc_approval',
	  	data: {
	  		selected_storage_id: 0,
	  		doc_for_approval: [],
	  		doc_for_replacement: [],
	  		show_pdf: true,
	  		pdfFile: "",
	  		show_for_replacement: false
	  	},
		mounted: function(){
			//this.load_doc_for_approval();
			//this.load_doc_for_replacement();
		},
  		filters: {
		    getDayname: function(date){
		      return moment(date).format('ddd');
		    },
		    format_date: function(date){
		      return moment(date).format('ll');
		    },
		    ausdate: function(date) {
		      if(date == '0000-00-00'){
		        return '';
		      }else{
		        return moment(date).format('DD/MM/YYYY');
		      }
		      
		    },
		    getTime: function(date){
		      var temp_date = '2020-01-01 '+date;
		      return moment(temp_date).format('h:mm a');
		    },
  		},
  		methods: {
  			load_doc_for_approval: function(){
  				var project_id = '<?php echo $project_id ?>';
  				axios.post("<?php echo base_url() ?>projects/fetch_storage_liles_need_authorization", 
		        {
		        	'project_id': project_id,
		        }).then(response => {
		          	this.doc_for_approval = response.data;              
		        }).catch(error => {
		          console.log(error.response)
		        });
  			},
  			load_doc_for_replacement: function(){
  				var project_id = '<?php echo $project_id ?>';
  				axios.post("<?php echo base_url() ?>projects/fetch_files_for_replacement", 
		        {
		        	'project_id': project_id,
		        }).then(response => {
		          	this.doc_for_replacement = response.data;              
		        }).catch(error => {
		          console.log(error.response)
		        });
  				
  			},
  			view_file: function(filename,storage_files_id){
  				var start = Date.now();
  				this.pdfFile = '<?php echo base_url() ?>docs/stored_docs/'+filename+'?time='+start;
  				this.selected_storage_id = storage_files_id;
  				this.show_for_replacement = true;
  			},
  			approve_file : function(storage_files_id){
  				var r = confirm("Are you sure you want to APPROVE selected uploaded file to be Attached to the project?");
	      		if (r == true) {
	  				axios.post("<?php echo base_url() ?>projects/approve_file_to_be_attached", 
			        {
			        	'storage_files_id': storage_files_id,
			        }).then(response => {
			          	this.load_doc_for_approval();   
			          	this.load_doc_for_replacement();           
			        }).catch(error => {
			          console.log(error.response)
			        });
			    }
  			},
  			close_approve_modal: function(){
  				window.location.reload();
  			},
  		}
  	});
  	$(document).ready(function() { 
		if(is_ds == 1){
			app_approval.load_doc_for_approval();
			app_approval.load_doc_for_replacement();
			$("#mdl_for_doc_approval").modal('show');
		}
	});
</script>


<div id="mdl_doc_type_list" class="modal fade" tabindex="-1" data-width="760">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Confirmation</h4>
			</div>
			<div class="modal-body">
				<input type="hidden" id="file_is_set" value = "0">
				<div class="row">
					<div class="col-md-12" style = "height: 200px; overflow: auto">
						<table class="table table-hover text-nowrap thead-dark">
			                <thead>
			                    <tr>
			                    	<th></th>
			                    	<th>Date Uploaded</th>
			                    	<th>Doc Type</th>
				                    <th>File Name</th>
			                    </tr>
			                </thead>
		                  	<tbody>
			                    <tr v-for = "req_doc_type_list in req_doc_type_list">
			                    	<td>
			                    		<input type="checkbox" name="req_doc_type" v-model = "req_doc_type" :value = "req_doc_type_list.storage_files_id">
			                     	</td>
			                     	<td>{{ req_doc_type_list.date_upload }}</td>
			                    	<td>{{ req_doc_type_list.doc_type_name }}</td>
				                    <td>{{ req_doc_type_list.file_name }}</td>
			                    </tr>
			                </tbody>
			            </table>
					</div>
				</div>
			</div>
			<hr class="no-m"/>
			<div class="modal-footer row">
				<button type="button" class="btn btn-primary" data-dismiss="modal" v-on:click = "set_replace_files">Select</button>
	        	<button type="button" class="btn btn-success" data-dismiss="modal" v-on:click = "cancel_replace_files">Cancel</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var file_is_set = 0;
	var app = new Vue({
	  	el: '#mdl_doc_type_list',
	  	data: {
	  		req_doc_type_list: [],
	  		req_doc_type: [],
	  	},
		mounted: function(){
			//this.load_req_doc_type_files();
		},
  		filters: {
		    getDayname: function(date){
		      return moment(date).format('ddd');
		    },
		    format_date: function(date){
		      return moment(date).format('ll');
		    },
		    ausdate: function(date) {
		      if(date == '0000-00-00'){
		        return '';
		      }else{
		        return moment(date).format('DD/MM/YYYY');
		      }
		      
		    },
		    getTime: function(date){
		      var temp_date = '2020-01-01 '+date;
		      return moment(temp_date).format('h:mm a');
		    },
  		},
  		methods: {
  			load_req_doc_type_files: function(){
  				var doc_type_id = $("#doc_type_name").val();
  				var project_id = '<?php echo $project_id ?>';
  				axios.post("<?php echo base_url() ?>projects/fetch_project_required_doc_type_file", 
		        {
		        	'project_id': '<?php echo $project_id ?>',
					'doc_type': doc_type_id
		        }).then(response => {
		          	this.req_doc_type_list = response.data;              
		        }).catch(error => {
		          console.log(error.response)
		        });
  			},

  			set_replace_files: function(){
  				var num = this.req_doc_type.length;
  				if(num > 1){
	  				var r = confirm("Are you sure you want to replace selected "+num+" files?");
			    	if (r == true) {
		  				file_is_set = 1;
		  				$("#will_replace_existing").val(1);
		  			}else{
		  				$("#doc_type_name").val("");
		  			}
		  		}else{
		  			file_is_set = 1;
		  			$("#will_replace_existing").val(1);
		  		}
  			},
  			cancel_replace_files: function(){
  				file_is_set = 0;
  				$("#will_replace_existing").val(0);
  			},
  		}
  	});
</script>

<style type="text/css">



p.row_file_list:hover em.del_stored_file{	display: block !important;}
p.row_file_list:hover{	background-color: #efefef; }
#doc_fileList{
	background-color: #add6ad;
	border-color: #3e8f3e;
	position: absolute;
	top: 35px;
	width: 100%;
	z-index: 180;
}


#doc_storage .modal-content {
    max-height: 90%;
    overflow-y: scroll;
    overflow-x: hidden;
}

.doc_droup_set p.doc_type_text{
	cursor: pointer;
}

 

</style>

<?php  //if($this->session->userdata('is_admin') == 1 || $this->session->userdata('user_id') == 6  ):?>

<script type="text/javascript">




$('input#doc_files').text("Your Text to Choose a File Here!");

$('input#doc_files').on('dragover', function(e) {
	$(this).css('border-color','#3e8f3e');
	$(this).css('color','#449a44');
});

	docUploadedList = function() {
		$('#doc_fileList ul').remove();


		var input = document.getElementById('doc_files');
		var output = document.getElementById('doc_fileList');
		var children = "";
		for (var i = 0; i < input.files.length; ++i) {
			children += '<li>' + input.files.item(i).name + '</li>';
		}
		$('#doc_fileList').append('<ul class="" style="    padding: 10px 30px;">'+children+'</ul>');
		var children = '';


		var list_hieght = $('#doc_fileList').outerHeight();

		var added_hight_inputBox = list_hieght+30;

		$('input#doc_files').css('height',added_hight_inputBox+'px');

		$('.file_area_box').css('height',added_hight_inputBox+'px');
	}

	$('.doc_upload_submit').click(function(){



		var doc_type = $('select.doc_type_name').val();

		if(doc_type > 0){

			$(this).hide();
			$('#doc_storage').modal('hide');
			$('#loading_modal').modal({'backdrop': 'static', 'show' : true} );

			setTimeout(function(){ 
				$('form#upload_doc_file_prj').submit(); 
			}, 3000);

			if(file_is_set == 1){
				$('input[name="req_doc_type"]:checked').each(function() {
				   	$.post(baseurl+"projects/set_file_for_replacement", 
					{
						storage_files_id: this.value
					}, 
					function(result){
					});
				});
			}



		}else{
			alert('Please select Document Type.')
		}

	});





	$('.del_stored_file').click(function(){
		$('#loading_modal').modal({'backdrop': 'static', 'show' : true} );


		setTimeout(function(){
			$('#loading_modal').modal('hide');
		},1000);


		var file_id = $(this).attr('id');
		// alert(file_id);
		$.post(baseurl+"projects/remove_uploaded_file",{file_id: file_id});
		$(this).parent().remove();

	});

	window.del_stored_file = function(file_id){
		var r = confirm("Are you sure you want to remove the selected file?");
	    if (r == true) {
			$.post(baseurl+"projects/remove_uploaded_file",{file_id: file_id});
			$.post(baseurl+"projects/list_uploaded_files", 
			{ 
				proj_id: '<?php echo $project_id ?>',
				job_date: '<?php echo $job_date ?>'
			}, 
			function(result){
				$("#docStorageList").html(result);
			});
		}
	}

	$("#showDocStorage").click(function(){
		//$this->projects->list_uploaded_files($project_id)
		
		$.post(baseurl+"projects/list_uploaded_files", 
		{ 
			proj_id: '<?php echo $project_id ?>',
			job_date: '<?php echo $job_date ?>'
		}, 
		function(result){
			$("#docStorageList").html(result);
		});

	});

	var report_type = "";
	window.generateReports = function(reportType){
		report_type = reportType;
		$("#confirmationAutoDocStore").modal("show");
	}

	$("#autoUploadYes").click(function(){
		$.post(baseurl+"projects/copy_report_to_docstroge", 
		{ 
			report_type: report_type,
			project_id: '<?php echo $project_id ?>'
		}, 
		function(result){
			$("#docStorageList").html(result);
		});
	});

	$("#site_diary_pdf").click(function(){
		var project_id = '<?php echo $project_id ?>'
		$.post(baseurl+"induction_health_safety/generate_site_diary_qrcode",
	    {
	        project_id: project_id
	    },
	    function(result){
	        window.open(baseurl+'induction_health_safety/induction_site_diary_blank_pdf?project_id='+project_id);
	    });
		return false;
	});


	window.attach_to_project = function(storage_files_id){
	    if(jQuery('input[id=attach_'+storage_files_id+']').is(':checked')){
	      	var r = confirm("Are you sure you want to Attach file to the project?");
	      	if (r == true) {
		        $.post(baseurl+"projects/attach_storage_file_to_project", 
		        { 
		          storage_files_id:storage_files_id
		        }, 
		        function(result){ 
		        });       
	      	}else{
	        	$( "#attach_"+storage_files_id).prop('checked', false);
	      	}
	    }else{
	      	var r = confirm("Are you sure you want to Remove Attached file to the project?");
	      	if (r == true) {
		        $.post(baseurl+"projects/unattach_storage_file_to_project", 
		        { 
		          storage_files_id:storage_files_id
		        }, 
		        function(result){
		        });       
	      	}else{
	        	$( "#attach_"+storage_files_id).prop('checked', true);
	      	}
	    }
	   
	    
	}

	window.approve_doc_type = function(storage_files_id){
		$.post(baseurl+"projects/check_file_for_replacement", 
        { 
          project_id: '<?php echo $project_id ?>'
        }, 
        function(result){
        	if(result == ""){
        		var r = confirm("Are you sure you want to Approved Attached file to the project?");
		      	if (r == true) {
			        $.post(baseurl+"projects/approve_doc_file", 
			        { 
			          storage_files_id:storage_files_id
			        }, 
			        function(result){
			        	window.location.reload();
			        });       
		      	}
        	}else{
        		var r = confirm(result);
        		if (r == true) {
        			$.post(baseurl+"projects/unselect_doc_file", 
			        { 
			          project_id: '<?php echo $project_id ?>'
			        }, 
			        function(result){
			        	$.post(baseurl+"projects/approve_doc_file_selected", 
				        { 
				          storage_files_id:storage_files_id
				        }, 
				        function(result){
				        	window.location.reload();
				        });     
			        });   

        			
        		}
        	}
      
        });   


		// var r = confirm("Are you sure you want to Approved Attached file to the project?");
  //     	if (r == true) {
	 //        $.post(baseurl+"projects/approve_doc_file", 
	 //        { 
	 //          storage_files_id:storage_files_id
	 //        }, 
	 //        function(result){
	 //        	window.location.reload();
	 //        });       
  //     	}
	}

	window.document_type_change = function(){
		var job_date = '<?php echo $job_date ?>';
		if(job_date !== ""){
			var doc_type_id = $("#doc_type_name").val();
			this.app.load_req_doc_type_files();
			$.post(baseurl+"projects/check_doc_type_is_required", 
	        { 
	          doc_type_id: doc_type_id,
	          project_id: '<?php echo $project_id ?>'
	        }, 
	        function(result){
	        	if(result == 1){
	        		$("#doc_type_comfirmation").modal('show');
	        	}
	        }); 
	    }  
	}

	$("#display_existing_doc_file").click(function(){
		app.load_req_doc_type_files();
		$("#doc_type_comfirmation").modal('hide');
		$("#mdl_doc_type_list").modal('show');
	});


</script>
<?php //endif; ?>



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

<div id="" class="details_video hp_vids_tmbs clearfix" style="display:none;">
<p id="" class="m-left-5 p-bottom-10 m-top-5 clearfix" style="font-weight: bold;    font-size: 16px;    border-bottom: 1px solid #ccc;">Details Videos</p>
<?php $cat_keyword = 'projects'; $sub_cat_keyword = 'projects_details'; ?>
<?php $this->help_videos->get_help_videos($cat_keyword,$sub_cat_keyword); ?>
</div>

<div id="" class="invoice_video hp_vids_tmbs clearfix" style="display:none;">
<p id="" class="m-left-5 p-bottom-10 m-top-5 clearfix" style="font-weight: bold;    font-size: 16px;    border-bottom: 1px solid #ccc;">Invoice Videos</p>
<?php $cat_keyword = 'projects'; $sub_cat_keyword = 'invoice'; ?>
<?php $this->help_videos->get_help_videos($cat_keyword,$sub_cat_keyword); ?>
</div>

<div id="" class="works_video hp_vids_tmbs clearfix" style="display:none;">
<p id="" class="m-left-5 p-bottom-10 m-top-5 clearfix" style="font-weight: bold;    font-size: 16px;    border-bottom: 1px solid #ccc;">Works Videos</p>
<?php $cat_keyword = 'projects'; $sub_cat_keyword = 'works'; ?>
<?php $this->help_videos->get_help_videos($cat_keyword,$sub_cat_keyword); ?>
</div>

<div id="" class="variation_video hp_vids_tmbs clearfix" style="display:none;">
<p id="" class="m-left-5 p-bottom-10 m-top-5 clearfix" style="font-weight: bold;    font-size: 16px;    border-bottom: 1px solid #ccc;">Works Videos</p>
<?php $cat_keyword = 'projects'; $sub_cat_keyword = 'variation'; ?>
<?php $this->help_videos->get_help_videos($cat_keyword,$sub_cat_keyword); ?>
</div>



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

$('.play_invoice_vids').click(function(){
	$('.hp_vids_tmbs').hide();
	$('.invoice_video').show();
});

$('.play_details_vids').click(function(){
	$('.hp_vids_tmbs').hide();
	$('.details_video').show();
});

$('.play_works_vids').click(function(){
	$('.hp_vids_tmbs').hide();
	$('.works_video').show();
});

$('.play_variation_vids').click(function(){
	$('.hp_vids_tmbs').hide();
	$('.variation_video').show();
});


$('.open_help_vids_mpd').click(function(){
	$('li.help_videos_tab_btn a').trigger('click');
  	$('li.now_playing_tab_btn a').hide();
});


</script>

<?php if(  $this->session->userdata('is_admin') != 1  ): ?>
<?php if(  $this->session->userdata('user_role_id') != 5 &&  $this->session->userdata('user_role_id') != 6 &&  $this->session->userdata('user_role_id') != 4  ): ?>
	<style type="text/css">

	#vid_id_21,#vid_id_22{
		display: none;
		visibility: hidden;
	}

	</style>
<?php endif; ?>
<?php endif; ?>

<!-- _________________________________ HELP VIDEO SETUP _________________________________ -->



<?php $this->load->view('assets/logout-modal'); ?>