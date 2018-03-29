<?php date_default_timezone_set("Australia/Perth");  // date is set to perth ?>
<?php $this->load->module('bulletin_board'); ?>
<?php $this->load->module('dashboard'); ?>
<?php $months = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"); ?>
<!-- title bar -->


<?php
date_default_timezone_set("Australia/Perth");

$current_year = date("Y");
$current_month = date("m");
$current_day = date("d");

$last_year = $current_year-1;
$start_last_year = "01/01/$last_year";
$last_year_same_date = "$current_day/$current_month/$last_year";

$start_current_year = "01/01/$current_year";
$current_date = date("d/m/Y");
?>

<!-- maps api js -->

<script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyDs1g6kHxbVrkQe7e_CmR6MsfV_3LmLSlc"></script>

<script type="text/javascript">
	var data = { "locations": <?php echo $this->dashboard->focus_get_map_locations(); ?>};	
	var emp_data = { "locations": <?php echo $this->dashboard->emp_get_locations_points(); ?>};
</script>

<script type="text/javascript" src="<?php echo base_url(); ?>js/maps/markerclusterer_packed.js"></script>

<!--[if IE]><script type="text/javascript" src="<?php echo base_url(); ?>js/excanvas.js"></script><![endif]-->
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.knob.min.js"></script>
<script type="text/javascript"> $(function() {  $(".knob").knob(); });</script>
<script type="text/javascript">$('knob').trigger('configure', {width:100}); </script>

<!-- maps api js -->

<style type="text/css">body{background: #ECF0F5 !important;}</style>

<div class="container-fluid head-control hide">
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
						<a class="btn-small sb-open-right"><i class="fa fa-file-text-o"></i> Project Comments</a>
					</li>
					<!-- 
						<li>
							<a href="<?php echo base_url(); ?>wip" class="btn btn-small btn-warning"><i class="fa fa-refresh fa-lg"></i> Reset Table</a>
						</li>

						<li>
							<a href="" class="btn-small"><i class="fa fa-magic"></i> Tour</a>
						</li>
					-->
				</ul>
			</div>

		</div>
	</div>
</div>
<!-- title bar -->

<div class="container-fluid adv"  style="background: #ECF0F5;">


	<!-- Example row of columns -->
	<div class="row dash">				
		<?php $this->load->view('assets/sidebar'); ?>
		<div class="section col-sm-12 col-md-11 col-lg-11 pad-0-imp no-m-imp">
			<div class="">




				<div class="clearfix pad-10">
					<div class="widget_area row pad-0-imp no-m-imp">


						<div class="col-md-6 col-lg-3 col-sm-6 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-e small-widget">
								<div class="box-area clearfix">
									<div class="widg-icon-inside col-xs-3"><i class="fa fa-list-alt text-center fa-3x"></i></div>
									<div class="widg-content fill col-xs-9 clearfix">
										<div class="pad-5">
											<div class=" " id=""><p>Invoiced <span class="pull-right"><?php echo date('Y'); ?></span></p></div>
											<hr class="" style="margin: 5px 0px 0px;">
											<div class="pad-top-5" id="" ><?php $this->dashboard->sales_widget(); ?></div>
										</div>							
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-6 col-lg-3 col-sm-6 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-a small-widget">
								<div class="box-area clearfix">
									<div class="widg-icon-inside col-xs-3"><i class="fa fa-list  text-center fa-3x"></i></div>

									<div class="widg-content fill col-xs-9 clearfix">
										<div class="pad-5">
											<div class=" " id=""><p>Uninvoiced <!-- <span class="pull-right"><?php // echo date('Y'); ?></span> --></p></div>
											<hr class="" style="margin: 5px 0px 0px;">
											<div class="pad-top-5" id="" ><?php $this->dashboard->uninvoiced_widget(); ?></div>
										</div>							
									</div>
								</div>
							</div>
						</div>  

						<div class="col-md-6 col-lg-3 col-sm-6 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-f small-widget">
								<div class="box-area clearfix">
									<div class="widg-icon-inside col-xs-3"><i class="fa fa-server text-center fa-3x"></i></div>

									<div class="widg-content fill col-xs-9 clearfix">
										<div class="pad-5">
											<div class=" " id=""><p>Outstanding <!-- <span class="pull-right"><?php // echo date('Y'); ?></span> --></p></div>
											<hr class="" style="margin: 5px 0px 0px;">
											<div class="pad-top-5" id="" ><?php $this->dashboard->outstanding_payments_widget(); ?></div>
										</div>							
									</div>
								</div>
							</div>
						</div>


						<div class="col-md-6 col-lg-3 col-sm-6 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-b small-widget">
								<div class="box-area clearfix">
									<div class="widg-icon-inside col-xs-3"><i class="fa fa-tasks  text-center fa-3x"></i></div>


									<div class="widg-content fill col-xs-9 clearfix">
										<div class="pad-5">
											<div class=" " id=""><p>WIP</p></div>
											<hr class="" style="margin: 5px 0px 0px;">
											<div class="pad-top-5" id="" ><?php $this->dashboard->wip_widget(); ?></div>
										</div>							
									</div>
								</div>
							</div>
						</div>

						<div class=" col-xs-12 box-widget pad-10">
							<div class="progress no-m progress-termometer">
								<div class="progress-bar progress-bar-danger active progress-bar-striped full_p tooltip-enabled" data-original-title=""  style="background-color: rgb(251, 25, 38); border-radius: 0px 10px 10px 0px;"></div> 
							</div>
						</div>	

						<div class="col-md-12 col-sm-12 col-xs-12 col-lg-9 box-widget pad-10">
							<div class="widget wid-type-0 widg-head-styled">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide hidden"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head box-widg-head pad-5">
									<strong>Sales Forecast - <?php echo date('Y'); ?></strong>
									<select class="pull-right input-control input-sm chart_data_selection" style="padding: 0;margin: -8px 0 0 0;width: 175px;height: 35px; border-radius: 0;border: 0;border-bottom: 1px solid #999999;">
										<option value="Overall">Overall Sales Forecast</option>
										<!-- <option value="Outstanding">Focus Outstanding</option> -->
										<!-- <option value="Pm_Outstanding">PM Outstanding</option> -->
										<option value="FWA">Focus WA</option>
										<option value="FNSW">Focus NSW</option>
										<?php foreach ($project_manager as $pm) {
											echo '<option value="'.$pm->user_first_name.' '.$pm->user_last_name.'">'.$pm->user_first_name.' '.$pm->user_last_name.'</option>';
										} ?>
									</select>
								</div>

								<div class="box-area clearfix row pad-right-10 pad-left-10">									
									<div class="widg-content col-md-9 col-xs-12 clearfix">
										<div class="loading_chart" style="height: 457px; text-align: center; padding: 100px 53px; color: #ccc;"><i class="fa fa-spin fa-refresh fa-4x"></i></div>
										<div class id="job_book_area">
											<div id="chart"></div></div>
										<!-- <hr class="block m-bottom-10 m-top-5">
										

										<button class="btn btn-info btn-sm" id="reset_chart" onclick="bttnB(this)" ><i class="fa fa-exchange"></i> Reset Chart</button>
										<button class="btn btn-primary btn-sm" id="visible_overall" onclick="bttnA(this)" ><i class="fa fa-exchange"></i> Outstanding</button>

										<button class="btn btn-warning btn-sm" id="visible_forecast" onclick="bttnC(this)" ><i class="fa fa-exchange"></i> Focus WA</button>
										<button class="btn btn-success btn-sm" id="visible_forecast" onclick="bttnD(this)" ><i class="fa fa-exchange"></i> Focus NSW</button>
										<button class="btn btn-sm" id="visible_forecast" onclick="bttnE(this)" ><i class="fa fa-exchange"></i> PM Actual Sales</button>				 -->		
									</div>
<!-- 
									<div class="widg-content col-md-3 col-xs-12 clearfix">
										<div class="loading_chart" style="height: 457px; text-align: center; padding: 100px 53px; color: #ccc;"><i class="fa fa-spin fa-refresh fa-4x"></i></div>
										<div class="" id="donut_a"></div>
									</div>
								-->
								<div class="widg-content col-md-3 col-xs-12 clearfix" style="position: inherit;">
									<div class="loading_chart" style="height: 457px; text-align: center; padding: 100px 53px; color: #ccc;"><i class="fa fa-spin fa-refresh fa-4x"></i></div>
									<!-- <div class="" id="donut_a"></div> -->

									<div class="clearfix center knob_box pad-10 small-widget">
										<p><br /></p>
										<p class="text-center m-bottom-10"><strong style="font-size: 16px;">Average Final Invoice Days</strong></p>
										<p><br /></p>

										<?php echo $this->dashboard->average_date_invoice(); ?>
											<!--
												<div id="" class="tooltip-enabled" title="" data-placement="bottom" data-original-title="77% - $1,381,545 / $1,800,000 ">
													<input class="knob" data-width="100%" value="35" readonly data-fgColor="#964dd7" data-angleOffset="-180"  data-max="40">
													<div id="" class="clearfix" style=" margin-top: -20px;">
														<div id="" class="col-xs-6"><strong><p>1</p></strong></div>
														<div id="" class="col-xs-6 text-right"><strong><p>40</p></strong></div>
													</div>
												</div>
											-->
										</div>
										<style type="text/css">.knob_box canvas{width: 100% !important;}</style>

									</div>


								</div>



							</div>
						</div>


						<!-- ************************ -->


						<div class="col-md-6 col-sm-6 col-xs-12 col-lg-3 box-widget pad-10">
							<div class="widget wid-type-0 widg-head-styled" style="height: 501px;">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head fill box-widg-head pad-5"><strong>Project Manager Sales</strong> <span class="badges pull-right"> <span class="pull-right"><?php echo date('Y'); ?></span> </span></div>
								<div class="box-area clearfix">
									<div class="widg-content clearfix">
										<div class="" id="">
											<?php $status_forecast = $this->dashboard->pm_sales_widget(); ?>
											<script type="text/javascript">
												var raw_overall = '<?php echo $status_forecast; ?>';
												var overall_arr =  raw_overall.split('_');
												var overall_progress = parseInt(overall_arr[0]);
												var status_forecast = overall_arr[1];
												$('.full_p').css('width',overall_progress+'%');
												$('.full_p').html(overall_progress+'%');
											  	$('.full_p').prop('title','$'+status_forecast+' - Overall Progress');

											  
											</script>
										</div>							
									</div>
								</div>
							</div>
						</div>

						<!-- ************************ -->

						<div class="col-md-6 col-lg-3 col-sm-6 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-b small-widget">
								<div class="box-area clearfix">
									<div class="widg-icon-inside col-xs-3" ><div id="" class=""><i class="fa fa-cube  text-center fa-3x"></i></div></div>
									<div class="widg-content fill col-xs-9 clearfix">
										<div class="pad-3">
											<div class="pad-left-5 pad-top-3" id=""><p>Invoiced - WIP Count <span class="pull-right"><?php echo date('Y'); ?></span></p></div>
											<hr class="" style="margin: 5px 0px 2px;">
											<div class="pad-top-5" id="" ><?php $this->dashboard->focus_projects_count_widget(); ?></div>
										</div>							
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-md-6 col-lg-3 col-sm-6 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-0 small-widget">
								<div class="box-area clearfix">
									<div class="widg-icon-inside col-xs-3" ><div id="" class=""><i class="fa  fa-user-times text-center fa-3x"></i></div></div>
									<div class="widg-content fill col-xs-9 clearfix">
										<div class="pad-5">
											<div class=" " id=""><p>Quotes Unaccepted <span class="pull-right"><?php echo date('Y'); ?></span></p></div>
											<hr class="" style="margin: 5px 0px 0px;">
											<div class="pad-top-5" id="" >
												<?php $this->dashboard->pm_estimates_widget(); ?>
											</div>
										</div>							
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-6 col-lg-3 col-sm-6 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-c small-widget">
								<div class="box-area clearfix">
									<div class="widg-icon-inside col-xs-3" ><div id="" class=""><i class="fa fa-calendar  text-center fa-3x"></i></div></div>
									<div class="widg-content fill col-xs-9 clearfix">
										<div class="pad-5">
											<div class=" " id=""><p>Maintenance AVG Days <span class="pull-right"><?php echo date('Y'); ?></span></p></div>
											<hr class="" style="margin: 5px 0px 0px;">
											<div class="pad-top-5" id="" ><?php $this->dashboard->maintanance_average(); ?></div>
										</div>							
									</div>
								</div>
							</div>
						</div>



						<div class="col-md-6 col-lg-3 col-sm-6 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-d small-widget">
								<div class="box-area clearfix">
									<div class="widg-icon-inside col-xs-3"><i class="fa fa-list-alt text-center fa-3x"></i></div>
									<div class="widg-content fill col-xs-9 clearfix">
										<div class="pad-5">
											<div class=" " id=""><p>Purchase Orders <span class="pull-right"><?php echo date('Y'); ?></span></p></div>
											<hr class="" style="margin: 5px 0px 0px;">
											<div class="pad-top-5" id="" ><?php $this->dashboard->focus_get_po_widget(); ?></div>
										</div>							
									</div>
								</div>
							</div>
						</div>

						<!-- ************************ -->
						
						<div class="clearfix"></div>

						<!-- ************************ -->


						<div class="col-md-6 col-lg-3 col-sm-6 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-d small-widget">
								<div class="box-area clearfix">
									<div class="widg-icon-inside col-xs-3 purple"><i class="fa fa-clock-o text-center fa-3x"></i></div>
									<div class="widg-content fill col-xs-9 clearfix purple">
										<div class="pad-5">
											<div class=" " id=""><p>Site Labour Hours</p></div>
											<hr class="" style="margin: 5px 0px 0px;">
											<div class="pad-top-5" id="" ><?php $this->dashboard->wid_site_labour_hrs(); ?></div>
										</div>							
									</div>
								</div>
							</div>
						</div>

						
						<div class="col-md-6 col-lg-3 col-sm-6 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-d small-widget">
								<div class="box-area clearfix">
									<div class="widg-icon-inside col-xs-3 brown"><i class="fa fa-check-square-o text-center fa-3x"></i></div>
									<div class="widg-content fill col-xs-9 clearfix brown">
										<div class="pad-5">
											<div class=" " id=""><p>Quotes</p></div>
											<hr class="" style="margin: 5px 0px 0px;">
											<div class="pad-top-5" id="" ><?php $this->dashboard->wid_quoted(); ?></div>
										</div>							
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-md-6 col-lg-3 col-sm-6 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-0 small-widget">
								<div class="box-area clearfix">
									<div class="widg-icon-inside col-xs-3 violet" ><div id="" class=""><i class="fa  fa-code text-center fa-3x"></i></div></div>
									<div class="widg-content col-xs-9 clearfix fill violet">
										<div class="pad-5">
											<div class=" " id=""><p>Widget <span class="pull-right"><?php echo date('Y'); ?></span></p></div>
											<hr class="" style="margin: 5px 0px 0px;">
											<div class="pad-top-5" id="">
												<p class="value"> <strong> &nbsp; </strong></p>
											</div>
										</div>							
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-6 col-lg-3 col-sm-6 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-0 small-widget">
								<div class="box-area clearfix">
									<div class="widg-icon-inside col-xs-3 dblue" ><div id="" class=""><i class="fa  fa-code text-center fa-3x"></i></div></div>
									<div class="widg-content col-xs-9 clearfix dblue fill">
										<div class="pad-5">
											<div class=" " id=""><p>Widget <span class="pull-right"><?php echo date('Y'); ?></span></p></div>
											<hr class="" style="margin: 5px 0px 0px;">
											<div class="pad-top-5" id="">
												<p class="value"> <strong> &nbsp; </strong></p>
											</div>
										</div>							
									</div>
								</div>
							</div>
						</div>


						<!-- ************************ -->
						
						<div class="clearfix"></div>


						<!-- ************************ -->


						<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-0 widg-head-styled">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide hidden"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head fill box-widg-head pad-5"><i class="fa fa-tags  text-center "></i> <strong>Projects by Type </strong><span class="pull-right"> <?php echo date('Y'); ?></span></div>
								<div class="box-area clearfix" style="height:320px;">
									<div class="widg-content clearfix">

										<div id="" class="pad-5" style="height: 290px; overflow: auto;">
											<?php echo $this->dashboard->focus_projects_by_type_widget(); ?>
										</div>

									</div>
								</div>
							</div>
						</div>


						<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 box-widget pad-10 hide">
							<div class="widget wid-type-b widg-head-styled">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide hidden"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head fill box-widg-head pad-5"><i class="fa fa-tags  text-center "></i> <strong>Pie View </strong></div>
								<div class="box-area clearfix" style="height:320px;">
									<div class="widg-content clearfix">
										




									</div>
								</div>
							</div>
						</div>



						<div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-e widg-head-styled">
								<div class="  fill box-widg-head pad-right-10 pad-left-10 m-left-15 pull-right pad-top-3 m-3">
									<a href="#clients_mtnc" class="view_main_swtch btn btn-xs btn-default" role="tab" data-toggle="tab"  >View Maintenance</a> &nbsp;
									<strong>
										<?php echo date('Y'); ?> <span  data-placement="left" class="pointer" ><i class="fa fa-info-circle tooltip-enabled" title="" data-html="true" data-placement="left" data-original-title="Top 20 Clients/Contractors/Suppliers having the highest job cost for the year <?php echo date('Y'); ?>."></i></span>
									</strong>
								</div>
								<div class="tabs_widget" >
									<ul  class="nav nav-tabs" role="tablist" style="height: 32px;">
										<li role="presentation" class="active"><a href="#clients" class="tab_btn_dhb" role="tab" id="clients-tab_a" data-toggle="tab" >Clients</a></li>
										<li role="presentation" class=""><a href="#contractors" class="tab_btn_dhb" role="tab" id="contractors-tab_a" data-toggle="tab" >Contractors</a></li>
										<li role="presentation" class=""><a href="#suppliers" class="tab_btn_dhb" role="tab" id="suppliers-tab_a" data-toggle="tab" >Suppliers</a></li>


										<li role="presentation" class="active"><a href="#clients_mtnc" style="display:none;" class="tab_btn_dhb_mntnc" role="tab" id="clients-tab_b" data-toggle="tab" >Clients</a></li>
										<li role="presentation" class=""><a href="#contractors_mtnc" style="display:none;" class="tab_btn_dhb_mntnc" role="tab" id="contractors-tab_b" data-toggle="tab" >Contractors</a></li>
										<li role="presentation" class=""><a href="#suppliers_mtnc" style="display:none;" class="tab_btn_dhb_mntnc" role="tab" id="suppliers-tab_b" data-toggle="tab" >Suppliers</a></li>


									</ul>


									<div id="myTabContent" class="tab-content pad-10 clearfix"> 
										
										<div role="tabpanel" class="tab-pane active fade in" id="clients" >
											<div id="" class="col-lg-5">
												<div class="loading_chart" style="height: 300px; text-align: center; padding: 100px 53px; color: #ccc;"><i class="fa fa-spin fa-refresh fa-4x"></i></div>
												<div class="" id="donut_a" style="text-align: center;"></div>
											</div>
											<div id="" class="col-lg-7">
												<div id="" class="" style="height: 300px; overflow: auto;">
													<?php echo $this->dashboard->focus_top_ten_clients(); ?>
												</div>
											</div>
										</div>

										<div role="tabpanel" class="tab-pane fade in" id="contractors">
											<div id="" class="center col-lg-5 clearfix">
												<div class="" id="donut_b" style="text-align: center;"></div>
											</div>
											<div id="" class="col-lg-7 clearfix">
												<div id="" class="clearfix" style="height: 300px; overflow: auto;">
													<?php echo $this->dashboard->focus_top_ten_con_sup('2'); ?>
												</div>
											</div>
										</div>

										<div role="tabpanel" class="tab-pane fade in" id="suppliers">
											<div id="" class="center col-lg-5 clearfix">
												<div class="" id="donut_c" style="text-align: center;"></div>
											</div>
											<div id="" class="col-lg-7 clearfix">
												<div id="" class="clearfix" style="height: 300px; overflow: auto;">
													<?php echo $this->dashboard->focus_top_ten_con_sup('3'); ?>
												</div>
											</div>
										</div>

										<div role="tabpanel" class="tab-pane fade in" id="clients_mtnc">
											<div id="" class="center col-lg-5 clearfix">
												<div class="" id="donut_d" style="text-align: center;"></div>
											</div>
											<div id="" class="col-lg-7 clearfix">
												<div id="" class="clearfix" style="height: 300px; overflow: auto;">
													<?php echo $this->dashboard->focus_top_ten_clients_mn(); ?>
												</div>
											</div>
										</div>
										
										<div role="tabpanel" class="tab-pane fade in" id="contractors_mtnc">
											<div id="" class="center col-lg-5 clearfix">
												<div class="" id="donut_e" style="text-align: center;"></div>
											</div>
											<div id="" class="col-lg-7 clearfix">
												<div id="" class="clearfix" style="height: 300px; overflow: auto;">
													<?php echo $this->dashboard->focus_top_ten_con_sup_mn('2'); ?>
												</div>
											</div>
										</div>
										
										<div role="tabpanel" class="tab-pane fade in" id="suppliers_mtnc">
											<div id="" class="center col-lg-5 clearfix">
												<div class="" id="donut_f" style="text-align: center;"></div>
											</div>
											<div id="" class="col-lg-7 clearfix">
												<div id="" class="clearfix" style="height: 300px; overflow: auto;">
													<?php echo $this->dashboard->focus_top_ten_con_sup_mn('3'); ?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="clearfix"></div>


						<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 box-widget pad-10" >
							<div class="widget wid-type-a widg-head-styled ">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide hidden"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head fill box-widg-head pad-5">
									<i class="fa fa-map-marker fa-lg"></i> 
									<strong>On-Going Projects in Australia</strong>
								</div>
								<div class="box-area clearfix  pad-0-imp" style="height:500px;">
									<div class="widg-content clearfix pad-0-imp">
										<div id="map"></div>
									</div>
								</div>
							</div>
						</div>



						<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 box-widget pad-10" >
							<div class="widget wid-type-a widg-head-styled ">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide hidden"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head fill box-widg-head pad-5">
									<i class="fa fa-users  fa-lg"></i> 
									<strong>Focus Employee Locations</strong>
								</div>
								<div class="box-area clearfix  pad-0-imp" style="height:500px;">
									<div class="widg-content clearfix pad-0-imp">
										<div id="employee-map-canvas" class="" style="width: 100%; height: 100%;"></div>
									</div>
								</div>
							</div>
						</div>


						<div class="clearfix"></div>


						<!-- ************************ -->

					</div>
				</div>				
			</div>
		</div>
	</div>
</div>


<?php //var_dump($focus_pm_forecast); ?>


<?php $this->load->view('assets/logout-modal'); ?>
<?php $this->bulletin_board->list_latest_post(); ?>

<script>
	var bttn_click_mtc = 0;
	$('.view_main_swtch').click(function(){
		var text = $(this).text();
		//$(this).parent().removeClass('active');

		if(bttn_click_mtc > 0){

			if(text == 'View Maintenance'){


				$(this).attr("href",'#clients_mtnc');
				$(this).attr("id",'#clients-tab_b');

				$(this).text('Return');

				$('a.tab_btn_dhb').hide();
				$('a.tab_btn_dhb_mntnc').show();
				$('#clients-tab_b').parent().addClass('active');

			}else{
				

				$(this).attr("href",'#clients');
				$(this).attr("id",'#clients-tab_a');

				$(this).text('View Maintenance');

				$('a.tab_btn_dhb').show();
				$('a.tab_btn_dhb_mntnc').hide();
				$('#clients-tab_a').parent().addClass('active');

			}


		}else{

				$(this).attr("href",'#clients_mtnc');
				$(this).attr("id",'#clients-tab_b');

				$(this).text('Return');

				$('a.tab_btn_dhb').hide();
				$('a.tab_btn_dhb_mntnc').show();
				$('#clients-tab_b').parent().addClass('active');
		}

		bttn_click_mtc++;
		//$(this).parent().removeClass('active');


	//	alert(text);
});
/*
$('a.tab_btn_dhb').click(function(){
	var item = $(this).attr('id')+'_box';
	$('.ct_pie').hide();
	$('#'+item).show();
});


$('a.tab_btn_dhb_mntnc').click(function(){
	var item = $(this).attr('id')+'_box';
	$('.ct_pie').hide();
	$('#'+item).show();
});
*/
/*

function map_toggle(btn_data){
	if(btn_data == 1){
		$('#emp_map_box_toggle').show(); 
		resizeMap_emp();
	 	$('#on_going_prj_map_toggle').hide();
	}else{
 
		$('#emp_map_box_toggle').hide();
		$('#on_going_prj_map_toggle').show();
	}
}
*/
var chart = c3.generate({
	size: {
		height: 457
	},data: {
		x : 'x',
		columns: [
          ['x',  // months labels

          <?php 

          for($i=0; $i < 12 ; $i++){
          	$alternator = $calendar_view; $counter = $i;

          	if($alternator == 1){
          		$counter = $counter + 6;
          	}

          	if($alternator == 1 && $counter > 11){
          		$counter = $counter - 12;
          	}

          	$month_index = $months[$counter];
          	echo "'".$month_index."',";
          }


          ?> ], // months labels



// 	Overall Last Year Sales
<?php
echo "['".$fcsO['company_name']."',";

for($i=0; $i < 12 ; $i++){	
	$alternator = $calendar_view;
	$counter = $i;

	if($alternator == 1){
		$counter = $counter + 6;
	}

	if($alternator == 1 && $counter > 11){
		$counter = $counter - 12;
	}

	$month_index = 'rev_'.strtolower($months[$counter]);
	echo $fcsO[$month_index].',';
}

echo "],";
?>
// 	Overall Last Year Sales



// PM Project Manager Sales Last Year
<?php foreach ($pms_sales_last_year->result_array() as $pm_sales_data ) {
	if($pm_sales_data['user_pm_name'] == 'Maintenance Manager' && $pm_sales_data['focus_comp_id'] == '6'  ){

	}else{
		echo "['".$pm_sales_data['user_pm_name']." Last Year',".$pm_sales_data['rev_jn'].",".$pm_sales_data['rev_fb'].",".$pm_sales_data['rev_mr'].",".$pm_sales_data['rev_ar'].",".$pm_sales_data['rev_my'].",".$pm_sales_data['rev_jn'].",".$pm_sales_data['rev_jl'].",".$pm_sales_data['rev_ag'].",".$pm_sales_data['rev_sp'].",".$pm_sales_data['rev_ot'].",".$pm_sales_data['rev_nv'].",".$pm_sales_data['rev_dc']."],";
	}
} ?>
// PM Project Manager Sales Last Year



//	Focus Company Last Year Sales
<?php foreach ($focus_indv_comp_sales_old->result_array() as $indv_comp_sales): ?>
	<?php //var_dump($indv_comp_sales); ?>

	<?php echo "['".$indv_comp_sales['company_name']." Last Year',"; ?>

	<?php 
	for($i=0; $i < 12 ; $i++){	
		$alternator = $calendar_view;
		$counter = $i;

		if($alternator == 1){
			$counter = $counter + 6;
		}

		if($alternator == 1 && $counter > 11){
			$counter = $counter - 12;
		}

		$month_index = 'rev_'.strtolower($months[$counter]);
		$item_forecast = $indv_comp_sales[$month_index];
		echo $item_forecast.',';
	}

	?>
	<?php echo "],"; ?>
<?php endforeach; ?>
//	Focus Company Last Year Sales





// Overall Sales
<?php
echo "['".$swout['company_name']."',";
for($i=0; $i < 12 ; $i++){	
	$alternator = $calendar_view;
	$counter = $i;

	if($alternator == 1){
		$counter = $counter + 6;
	}

	if($alternator == 1 && $counter > 11){
		$counter = $counter - 12;
	}

	$month_index = 'sales_data_'.strtolower($months[$counter]);
	echo $swout[$month_index].',';
}

echo "],";

?>


// Focus Overall WIP
<?php
echo "['Focus Overall WIP',";
for($i=0; $i < 12 ; $i++){	
	echo $focus_wip_overall[$i].',';
}
echo "],";
?>
// Focus Overall WIP

// Overall Sales

// 	Outstanding Overall
<?php
/*
echo "['".$fcsOT['company_name']."',";

for($i=0; $i < 12 ; $i++){	
	$alternator = $calendar_view;
	$counter = $i;

	if($alternator == 1){
		$counter = $counter + 6;
	}

	if($alternator == 1 && $counter > 11){
		$counter = $counter - 12;
	}

	$month_index = 'out_'.strtolower($months[$counter]);
	echo $fcsOT[$month_index].',';
}

echo "],";
*/
?>

// 	Outstanding Overall






<?php
/*
echo "['".$fcsC['company_name']."',";

for($i=0; $i < 12 ; $i++){	
	$alternator = $calendar_view;
	$counter = $i;

	if($alternator == 1){
		$counter = $counter + 6;
	}

	if($alternator == 1 && $counter > 11){
		$counter = $counter - 12;
	}

	$month_index = 'rev_'.strtolower($months[$counter]);
	echo $fcsC[$month_index].',';
}

echo "],";*/
?>


<?php


/*

		foreach ($inv_fcs_overall_sales as $key => $value) {
				echo "['$key Overall',";	
				



				for($i=0; $i < 12 ; $i++){
					$counter = $i;
				$alternator = $calendar_view;	

	if($alternator == 1){
		$counter = $counter + 6;
	}

	if($alternator == 1 && $counter > 11){
		$counter = $counter - 12;
	}



					$month_index_a = 'out_'.strtolower($months[$counter]);
					$month_index_b = 'rev_'.strtolower($months[$counter]);

					$month_overall = $inv_fcs_overall_sales[$key][$month_index_a] + $inv_fcs_overall_sales[$key][$month_index_b];

					echo  $month_overall.',';


				}

				echo "],";
				
		}
*/

		?>





// Focus Company Sales
<?php foreach ($focus_indv_comp_sales->result_array() as $indv_comp_sales): ?>
	<?php //var_dump($indv_comp_sales); ?>
	<?php echo "['".$indv_comp_sales['company_name']."',"; ?>
	<?php 
	for($i=0; $i < 12 ; $i++){	
		$alternator = $calendar_view;
		$counter = $i;

		if($alternator == 1){
			$counter = $counter + 6;
		}

		if($alternator == 1 && $counter > 11){
			$counter = $counter - 12;
		}

		$month_index = 'rev_'.strtolower($months[$counter]);
		$item_forecast = $indv_comp_sales[$month_index];
		echo $item_forecast.',';
	}

	?>
	<?php echo "],"; ?>
<?php endforeach; ?>
// Focus Company Sales




// Focus Company Outstanding
<?php /*
<?php foreach ($focus_indv_comp_outstanding->result_array() as $indv_comp_sales): ?>
	<?php //var_dump($indv_comp_sales); ?>
	<?php echo "['".$indv_comp_sales['company_name']." Outstanding',"; ?>
		<?php 
          for($i=0; $i < 12 ; $i++){	
          	$alternator = $calendar_view;
          	$counter = $i;

          	if($alternator == 1){
          		$counter = $counter + 6;
          	}

          	if($alternator == 1 && $counter > 11){
          		$counter = $counter - 12;
          	}
          	$month_index = 'out_'.strtolower($months[$counter]);
          	$item_forecast = $indv_comp_sales[$month_index];
          	echo $item_forecast.',';
          }
        ?>
	<?php echo "],"; ?>
<?php endforeach; ?>
*/
?>
// Focus Company Outstanding



// PM Project Manager Sales
<?php foreach ($pms_sales_c_year->result_array() as $pm_sales_data ) {
	if($pm_sales_data['user_pm_name'] == 'Maintenance Manager' && $pm_sales_data['focus_comp_id'] == '6'  ){

	}else{
		echo "['".$pm_sales_data['user_pm_name']."',".$pm_sales_data['rev_jan'].",".$pm_sales_data['rev_feb'].",".$pm_sales_data['rev_mar'].",".$pm_sales_data['rev_apr'].",".$pm_sales_data['rev_may'].",".$pm_sales_data['rev_jun'].",".$pm_sales_data['rev_jul'].",".$pm_sales_data['rev_aug'].",".$pm_sales_data['rev_sep'].",".$pm_sales_data['rev_oct'].",".$pm_sales_data['rev_nov'].",".$pm_sales_data['rev_dec']."],";
	}
} ?>
// PM Project Manager Sales





// PM Project Manager Outstanding
/*
	<?php foreach ($pms_outstanding_c_year->result_array() as $pm_sales_data ) {
		if($pm_sales_data['user_pm_name'] == 'Maintenance Manager' && $pm_sales_data['focus_comp_id'] == '6'  ){

		}else{
			echo "['".$pm_sales_data['user_pm_name']." Outstanding',".$pm_sales_data['out_jan'].",".$pm_sales_data['out_feb'].",".$pm_sales_data['out_mar'].",".$pm_sales_data['out_apr'].",".$pm_sales_data['out_may'].",".$pm_sales_data['out_jun'].",".$pm_sales_data['out_jul'].",".$pm_sales_data['out_aug'].",".$pm_sales_data['out_sep'].",".$pm_sales_data['out_oct'].",".$pm_sales_data['out_nov'].",".$pm_sales_data['out_dec']."],";
		}
	} ?>
	*/
// PM Project Manager Outstanding



// pms_outstanding_c_year





// Focus Company WIP
<?php 
foreach ($focus_comp_wip as $key => $value) {

	if( array_sum($focus_comp_wip[$key]) > 0){
		echo "['$key WIP',";

		for($i=0; $i < 12 ; $i++){
			echo round($focus_comp_wip[$key][$i],2).',';
		}

		echo "],";
	}
}

?>
// Focus Company WIP


// Focus PM WIP
<?php 
foreach ($focus_pm_wip as $key => $value) {

	if( array_sum($focus_pm_wip[$key]) > 0){
		echo "['$key WIP',";

		for($i=0; $i < 12 ; $i++){
			echo round($focus_pm_wip[$key][$i],2).',';
		}

		echo "],";
	}
}

?>
// Focus PM WIP


//	Focus Company Forecast
<?php foreach ($focus_indv_comp_forecast->result_array() as $indv_comp_forec): ?>
	<?php echo "['".$indv_comp_forec['company_name']." Forecast',"; ?>
	<?php 
	for($i=0; $i < 12 ; $i++){	
		$alternator = $calendar_view;
		$counter = $i;

		if($alternator == 1){
			$counter = $counter + 6;
		}

		if($alternator == 1 && $counter > 11){
			$counter = $counter - 12;
		}

		$comp_total_forec = $indv_comp_forec['total'] *($indv_comp_forec['forecast_percent']/100);
		$month_index = 'forecast_'.strtolower($months[$counter]);
		$item_forecast = $comp_total_forec * ($fetch_forecast[$month_index]/100);
		echo round($item_forecast,2).',';
	}
	?>
	<?php echo "],"; ?>
<?php endforeach; ?>
//	Focus Company Forecast

//	Overall Forecast
['Forecast',
<?php 

for($i=0; $i < 12 ; $i++){	
	$alternator = $calendar_view;
	$counter = $i;

	if($alternator == 1){
		$counter = $counter + 6;
	}

	if($alternator == 1 && $counter > 11){
		$counter = $counter - 12;
	}

	$month_index = 'forecast_'.strtolower($months[$counter]);
	$item_forecast = $fetch_forecast['total'] * ($fetch_forecast[$month_index]/100);
	echo $item_forecast.',';
}

?>],
//	Overall Forecast


// PM Project Manager Forecast
<?php foreach ($focus_pm_forecast as $pm_fct){

	if($pm_fct->pm_id == 29){
		$amount = $amount_for_maintenance;
	}else{
		$amount = $focus_data_forecast_p[$pm_fct->comp_id] * ($pm_fct->forecast_percent / 100);
	}

	echo "['$pm_fct->user_pm_name Forecast',";
	echo $amount * ( $pm_fct->forecast_jan / 100 ).','.
	$amount * ( $pm_fct->forecast_feb / 100 ).','.
	$amount * ( $pm_fct->forecast_mar / 100 ).','.
	$amount * ( $pm_fct->forecast_apr / 100 ).','.
	$amount * ( $pm_fct->forecast_may / 100 ).','.
	$amount * ( $pm_fct->forecast_jun / 100 ).','.
	$amount * ( $pm_fct->forecast_jul / 100 ).','.
	$amount * ( $pm_fct->forecast_aug / 100 ).','.
	$amount * ( $pm_fct->forecast_sep / 100 ).','.
	$amount * ( $pm_fct->forecast_oct / 100 ).','.
	$amount * ( $pm_fct->forecast_nov / 100 ).','.
	$amount * ( $pm_fct->forecast_dec / 100 ).',';
	echo "],";
}?>
// PM Project Manager Forecast ***


],
selection: {enabled: true},
type: 'bar',
colors: {
	'Forecast': '#CC79A7',
	'Overall Sales': '#E69F00',
	'Focus Overall WIP': '#009E73',
	'Last Year Sales': '#AAAAAA',

	'Focus Shopfit Pty Ltd Forecast': '#CC79A7',
	'Focus Shopfit Pty Ltd Last Year': '#AAAAAA',
	'Focus Shopfit Pty Ltd WIP': '#009E73',
	'Focus Shopfit Pty Ltd': '#E69F00',

	'Focus Shopfit NSW Pty Ltd Forecast': '#CC79A7',
	'Focus Shopfit NSW Pty Ltd Last Year': '#AAAAAA',
	'Focus Shopfit NSW Pty Ltd WIP': '#009E73',
	'Focus Shopfit NSW Pty Ltd': '#E69F00',

	'Trevor Gamble Forecast': '#CC79A7',
	'Trevor Gamble Last Year': '#AAAAAA',
	'Trevor Gamble WIP': '#009E73',
	'Trevor Gamble': '#E69F00',

	'Alan Liddell Forecast': '#CC79A7',
	'Alan Liddell Last Year': '#AAAAAA',
	'Alan Liddell WIP': '#009E73',
	'Alan Liddell': '#E69F00',

	'Stuart Hubrich Forecast': '#CC79A7',
	'Stuart Hubrich Last Year': '#AAAAAA',
	'Stuart Hubrich WIP': '#009E73',
	'Stuart Hubrich': '#E69F00',

	'Pyi Paing Aye Win Forecast': '#CC79A7',
	'Pyi Paing Aye Win Last Year': '#AAAAAA',
	'Pyi Paing Aye Win WIP': '#009E73',
	'Pyi Paing Aye Win': '#E69F00',

	'Kristoff Kiezun Forecast': '#CC79A7',
	'Kristoff Kiezun Last Year': '#AAAAAA',
	'Kristoff Kiezun WIP': '#009E73',
	'Kristoff Kiezun': '#E69F00',

	'Maintenance Manager Forecast': '#CC79A7',
	'Maintenance Manager Last Year': '#AAAAAA',
	'Maintenance Manager WIP': '#009E73',
	'Maintenance Manager': '#E69F00',

	'Joshua Gamble Forecast': '#CC79A7',
	'Joshua Gamble Last Year': '#AAAAAA',
	'Joshua Gamble WIP': '#009E73',
	'Joshua Gamble': '#E69F00',


            //Focus Shopfit Pty Lt
        },
        types: {   'Forecast' : 'line',  





        <?php foreach ($focus_indv_comp_forecast->result_array() as $indv_comp_forec): ?>


        <?php echo "'".$indv_comp_forec['company_name']." Forecast': 'line',"; ?>


    <?php endforeach; ?>

    <?php foreach ($focus_pm_forecast as $pm_fct){
    	echo "'$pm_fct->user_pm_name Forecast': 'line',";
    }?>

},
groups: [ ['Focus Overall WIP','Overall Sales'],['Focus Shopfit Pty Ltd','Focus Shopfit Pty Ltd WIP'],['Focus Shopfit NSW Pty Ltd','Focus Shopfit NSW Pty Ltd WIP'],
['Trevor Gamble','Trevor Gamble WIP'],
['Alan Liddell','Alan Liddell WIP'],
['Stuart Hubrich','Stuart Hubrich WIP'],
['Pyi Paing Aye Win','Pyi Paing Aye Win WIP'],
['Kristoff Kiezun','Kristoff Kiezun WIP'],
['Maintenance Manager','Maintenance Manager WIP'],
['Joshua Gamble','Joshua Gamble WIP']],



order: null,
},
tooltip: {
        grouped: true // false // Default true
    },
    bindto: "#chart",
    bar:{ width:{ ratio: 0.5 }},
    point:{ select:{ r: 6 }},
    onrendered: function () { $('.loading_chart').remove(); },
//zoom: {enabled: true, rescale: true,extent: [1, 7]},
legend: { show: false },


axis: {x: {type: 'category', tick: {rotate: 0,multiline: false}, height: 0} },
tooltip: {
	format: {
     //     title: function (x) { return 'Data ' + x; },
     value: function (value, ratio, id) {
               // var format = id === 'data1' ? d3.format(',') : d3.format('$');
               var format = d3.format(',');

               var mod_value = Math.round(value)
               return '$ '+format(mod_value);
           }
       } 

   }
});

chart.select();
chart.hide();

setTimeout(function () {
	chart.show(['Overall Sales','Forecast','Last Year Sales','Focus Overall WIP']);
}, 1000);	


$('select.chart_data_selection').on("change", function(e) {
	var data = $(this).val();
	
	if(data == 'Overall'){
		chart.hide(); 
		setTimeout(function () {
			chart.show(['Overall Sales','Forecast','Last Year Sales','Focus Overall WIP']);
		}, 500);
	}

	if(data == 'Outstanding'){
		chart.hide();
		setTimeout(function () {
			chart.show(['Focus Shopfit Pty Ltd Outstanding','Focus Shopfit NSW Pty Ltd Outstanding']);
		}, 500);	
	}


	if(data == 'Pm_Outstanding'){
		chart.hide();
		setTimeout(function () {
			chart.show(['Trevor Gamble Outstanding','Alan Liddell Outstanding','Stuart Hubrich Outstanding','Pyi Paing Aye Win Outstanding','Kristoff Kiezun Outstanding','Maintenance Manager Outstanding']);
		}, 500);	
	}



	if(data == 'FWA'){
		chart.hide(); 
		setTimeout(function () {
			chart.show(['Focus Shopfit Pty Ltd Forecast', 'Focus Shopfit Pty Ltd Last Year', 'Focus Shopfit Pty Ltd' ,'Focus Shopfit Pty Ltd WIP']);
		}, 500);
	}

	if(data == 'FNSW'){ 
		chart.hide(); 
		setTimeout(function () {
			chart.show(['Focus Shopfit NSW Pty Ltd Forecast', 'Focus Shopfit NSW Pty Ltd Last Year', 'Focus Shopfit NSW Pty Ltd' ,'Focus Shopfit NSW Pty Ltd WIP']);
		}, 500);
	}

	if(data == 'Trevor Gamble'){ 
		chart.hide(); 
		setTimeout(function () {
			chart.show(['Trevor Gamble','Trevor Gamble WIP','Trevor Gamble Forecast','Trevor Gamble Last Year']);
		}, 500);
	}

	if(data == 'Alan Liddell'){ 
		chart.hide(); 
		setTimeout(function () {
			chart.show(['Alan Liddell','Alan Liddell WIP','Alan Liddell Forecast','Alan Liddell Last Year']);
		}, 500);
	}

	if(data == 'Stuart Hubrich'){ 
		chart.hide(); 
		setTimeout(function () {
			chart.show(['Stuart Hubrich','Stuart Hubrich WIP','Stuart Hubrich Forecast','Stuart Hubrich Last Year']);
		}, 500);
	}

	if(data == 'Pyi Paing Aye Win'){ 
		chart.hide(); 
		setTimeout(function () {
			chart.show(['Pyi Paing Aye Win','Pyi Paing Aye Win WIP','Pyi Paing Aye Win Forecast','Pyi Paing Aye Win Last Year']);
		}, 500);
	}

	if(data == 'Kristoff Kiezun'){ 
		chart.hide(); 
		setTimeout(function () {
			chart.show(['Kristoff Kiezun','Kristoff Kiezun WIP','Kristoff Kiezun Forecast','Kristoff Kiezun Last Year']);
		}, 500);
	}

	if(data == 'Maintenance Manager'){ 
		chart.hide(); 
		setTimeout(function () {
			chart.show(['Maintenance Manager','Maintenance Manager WIP','Maintenance Manager Forecast','Maintenance Manager Last Year']);
		}, 500);
	}

	if(data == 'Joshua Gamble'){ 
		chart.hide(); 
		setTimeout(function () {
			chart.show(['Joshua Gamble','Joshua Gamble WIP','Joshua Gamble Forecast','Joshua Gamble Last Year']);
		}, 500);
	}

});




function bttnA(element_obj){
	var forecast_display = element_obj.getAttribute("id");
	chart.hide();
	setTimeout(function () {
		chart.show(['Outstanding', 'Focus Sales','Forecast','Last Year Sales']);
		element_obj.setAttribute("id", "hidden_forecast");
	}, 500);	
}


function bttnB(element_obj){
	var forecast_display = element_obj.getAttribute("id");
	chart.hide(); 
	setTimeout(function () {
		chart.show(['Overall Sales','Forecast','Last Year Sales']);
	}, 500);
}



function bttnC(element_obj){
	var forecast_display = element_obj.getAttribute("id");
	chart.hide(); 
	setTimeout(function () {
		chart.show(['Focus Shopfit Pty Ltd Forecast', 'Focus Shopfit Pty Ltd Last Year', 'Focus Shopfit Pty Ltd','Focus Shopfit Pty Ltd Outstanding','Focus Shopfit Pty Ltd Overall']);
	}, 500);
}


function bttnD(element_obj){
	var forecast_display = element_obj.getAttribute("id");
	chart.hide(); 
	setTimeout(function () {
		chart.show(['Focus Shopfit NSW Pty Ltd Forecast', 'Focus Shopfit NSW Pty Ltd Last Year', 'Focus Shopfit NSW Pty Ltd','Focus Shopfit NSW Pty Ltd Outstanding','Focus Shopfit NSW Pty Ltd Overall']);
	}, 500);
}



function bttnE(element_obj){
	var forecast_display = element_obj.getAttribute("id");
	chart.hide(); 
	setTimeout(function () {
		chart.show(['Alan Liddell', 'Stuart Hubrich', 'Pyi Paing Aye Win','Kristoff Kiezun','Maintenance Manager','Trevor Gamble']);
	}, 500);
}




// use this donut

var donuta = c3.generate({
	size: {
		height: 300,
		width: 300
	},data: {
		columns: [ <?php $this->dashboard->focus_top_ten_clients_donut(); ?> ],
		type : 'pie',
		onclick: function (d, i) { console.log("onclick", d, i); },
		onmouseover: function (d, i) { console.log("onmouseover", d, i); },
		onmouseout: function (d, i) { console.log("onmouseout", d, i); }
	},
	legend: {
		show: false //hides label
	},
	bindto: "#donut_a",
	donut: {
		title: '<?php echo date('Y'); ?> Projects by Type'
	},tooltip: {
		format: {
			value: function (value, ratio, id) {
				var format = d3.format(',');
				var rounded_percent = Math.round( ratio * 1000 ) / 10;
				var mod_value = Math.round(value);
			return '$ '+format(mod_value)+' '+rounded_percent+'%';
		}
	} 
}
});


var donutb = c3.generate({
	size: {
		height: 300,
		width: 300
	},data: {
		columns: [ <?php echo $this->dashboard->focus_top_ten_con_sup_donut('2'); ?> ],
		type : 'pie',
		onclick: function (d, i) { console.log("onclick", d, i); },
		onmouseover: function (d, i) { console.log("onmouseover", d, i); },
		onmouseout: function (d, i) { console.log("onmouseout", d, i); }
	},
	legend: {
		show: false //hides label
	},
	bindto: "#donut_b",
	tooltip: {
		format: {
			value: function (value, ratio, id) {
				var format = d3.format(',');
				var rounded_percent = Math.round( ratio * 1000 ) / 10;
				var mod_value = Math.round(value);
			return '$ '+format(mod_value)+' '+rounded_percent+'%';
		}
	} 
}
});


var donutc= c3.generate({
	size: {
		height: 300,
		width: 300
	},data: {
		columns: [ <?php echo $this->dashboard->focus_top_ten_con_sup_donut('3'); ?> ],
		type : 'pie',
		onclick: function (d, i) { console.log("onclick", d, i); },
		onmouseover: function (d, i) { console.log("onmouseover", d, i); },
		onmouseout: function (d, i) { console.log("onmouseout", d, i); }
	},
	legend: {
		show: false //hides label
	},
	bindto: "#donut_c",
	tooltip: {
		format: {
			value: function (value, ratio, id) {
				var format = d3.format(',');
				var rounded_percent = Math.round( ratio * 1000 ) / 10;
				var mod_value = Math.round(value);
			return '$ '+format(mod_value)+' '+rounded_percent+'%';
		}
	} 
}
});

var donutd= c3.generate({
	size: {
		height: 300,
		width: 300
	},data: {
		columns: [ <?php echo $this->dashboard->focus_top_ten_clients_mn_donut(); ?> ],
		type : 'pie',
		onclick: function (d, i) { console.log("onclick", d, i); },
		onmouseover: function (d, i) { console.log("onmouseover", d, i); },
		onmouseout: function (d, i) { console.log("onmouseout", d, i); }
	},
	legend: {
		show: false //hides label
	},
	bindto: "#donut_d",
	tooltip: {
		format: {
			value: function (value, ratio, id) {
				var format = d3.format(',');
				var rounded_percent = Math.round( ratio * 1000 ) / 10;
				var mod_value = Math.round(value);
			return '$ '+format(mod_value)+' '+rounded_percent+'%';
		}
	} 
}
});



var donute= c3.generate({
	size: {
		height: 300,
		width: 300
	},data: {
		columns: [ <?php echo $this->dashboard->focus_top_ten_con_sup_mn_donut(2); ?> ],
		type : 'pie',
		onclick: function (d, i) { console.log("onclick", d, i); },
		onmouseover: function (d, i) { console.log("onmouseover", d, i); },
		onmouseout: function (d, i) { console.log("onmouseout", d, i); }
	},
	legend: {
		show: false //hides label
	},
	bindto: "#donut_e",
	tooltip: {
		format: {
			value: function (value, ratio, id) {
				var format = d3.format(',');
				var rounded_percent = Math.round( ratio * 1000 ) / 10;
				var mod_value = Math.round(value);
			return '$ '+format(mod_value)+' '+rounded_percent+'%';
		}
	} 
}
});



var donutf= c3.generate({
	size: {
		height: 300,
		width: 300
	},data: {
		columns: [ <?php echo $this->dashboard->focus_top_ten_con_sup_mn_donut(3); ?> ],
		type : 'pie',
		onclick: function (d, i) { console.log("onclick", d, i); },
		onmouseover: function (d, i) { console.log("onmouseover", d, i); },
		onmouseout: function (d, i) { console.log("onmouseout", d, i); }
	},
	legend: {
		show: false //hides label
	},
	bindto: "#donut_f",
	tooltip: {
		format: {
			value: function (value, ratio, id) {
				var format = d3.format(',');
				var rounded_percent = Math.round( ratio * 1000 ) / 10;
				var mod_value = Math.round(value);
			return '$ '+format(mod_value)+' '+rounded_percent+'%';
		}
	} 
}
});










/*


var donuta = c3.generate({
     size: {
        height: 457
      },data: {
        columns: [ <?php $this->dashboard->donut_sales(); ?> ],
        type : 'donut',
        onclick: function (d, i) { console.log("onclick", d, i); },
        onmouseover: function (d, i) { console.log("onmouseover", d, i); },
        onmouseout: function (d, i) { console.log("onmouseout", d, i); }
    },
    bindto: "#donut_projects",
    donut: {
        title: '<?php echo date('Y'); ?> Completed Projects'
    }
});


*/
// function printDiv(divName) {
//       var printContents = document.getElementById(divName).innerHTML;     
//    var originalContents = document.body.innerHTML;       
//    document.body.innerHTML = printContents;      
//    window.print();      
//    document.body.innerHTML = originalContents;
//    }









</script>
<?php /*

    <div class="modal fade" id="large_map_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    	<div class="modal-dialog" style="width: 90%;">
    		<div class="modal-content">
    			<div class="modal-header">
    				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-map-marker fa-lg"></i> On-Going Projects in Australia <span class="pointer pull-right m-right-10" data-toggle="modal" data-target="#employee_map" data-dismiss="modal"><i class="fa fa-users fa-lg" aria-hidden="true"></i> View Employees &nbsp; &nbsp; </span></h4>
    			</div>
    			<div class="modal-body" style="height: 80%; padding: 0; ">
    				<!-- <div id="large_map" style="width: 100%; height: 100%;"></div> -->
    				<div id="map-canvas" class="" style="width: 100%; height: 100%;"></div>
    			</div>
    		</div>
    	</div>
    </div>



    <div class="modal fade" id="employee_map" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    	<div class="modal-dialog" style="width: 90%;">
    		<div class="modal-content">
    			<div class="modal-header">
    				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-users fa-lg"></i> Focus Employees <span class="pointer pull-right m-right-10" data-toggle="modal" data-target="#large_map_modal" data-dismiss="modal"><i class="fa fa-map-marker fa-lg" aria-hidden="true"></i> View On-Going Projects &nbsp; &nbsp; </span></h4>
    			</div>
    			<div class="modal-body" style="height: 80%; padding: 0; ">
    				<!-- <div id="large_map" style="width: 100%; height: 100%;"></div> -->
    				<!-- <div id="employee-map-canvas" class="" style="width: 100%; height: 100%;"></div> -->
    			</div>
    		</div>
    	</div>
    </div>
 */ ?>

 <!-- Modal -->






<!-- 

 <div class="modal fade" id="add_data_chart" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 	<div class="modal-dialog" role="document">
 		<div class="modal-content">
 			<div class="modal-header">
 				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
 				<h4 class="modal-title" id="myModalLabel">Add Data</h4>
 			</div>
 			<form method="post" >
 				<div class="modal-body pad-10">

 					<input type="text" class="form-control m-bottom-10 data_name" id="data_name" name="data_name" placeholder="Data Name" name="" value="">
 					<input type="text" class="form-control m-bottom-10 year" id="year" name="year" placeholder="Year" name="" value="">

 					<select name="data_color" class="form-control m-bottom-10">
 						<option value="#FD0000">Red</option>
 						<option value="#00CA00">Green</option>
 						<option value="#7008A8">Violet</option>
 						<option value="#FD7300">Orange</option>
 					</select>

 					<textarea placeholder="Values" class="form-control m-bottom-10 value_items" id="value_items" name="value_items"></textarea>

 				</div>
 				<div class="modal-footer">
 					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
 					<input type="submit" class="btn btn-success add_data_chart" value="Save changes">
 				</div>
 			</form>
 		</div>
 	</div>
 </div>
 -->
 <script type="text/javascript" src="<?php echo base_url(); ?>js/maps/maps.js"></script>
 	<?php /*
<script type="text/javascript" src="<?php echo base_url(); ?>js/maps/large_map.js"></script>
 	*/ ?>
 	<script type="text/javascript" src="<?php echo base_url(); ?>js/maps/employee_map.js"></script>