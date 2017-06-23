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
											<div class="pad-top-5" id="" >
												<p style="padding: 5px 5px 6px; text-align: center;	"><i class="fa fa-spin fa-refresh fa-lg"></i></p>
												<?php //$this->dashboard->sales_widget(); ?>
											</div>
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
											<div class="pad-top-5" id="" >
												<p style="padding: 5px 5px 6px; text-align: center;	"><i class="fa fa-spin fa-refresh fa-lg"></i></p>
												<?php //$this->dashboard->uninvoiced_widget(); ?>
											</div>
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
											<div class="pad-top-5" id="" >
												<p style="padding: 5px 5px 6px; text-align: center;	"><i class="fa fa-spin fa-refresh fa-lg"></i></p>
												<?php //$this->dashboard->outstanding_payments_widget(); ?>
											</div>
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
											<div class="pad-top-5" id="" >
												<p style="padding: 5px 5px 6px; text-align: center;	"><i class="fa fa-spin fa-refresh fa-lg"></i></p>
												<?php //$this->dashboard->wip_widget(); ?>
											</div>
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

						<div class="col-md-12 col-sm-12 col-xs-12 col-lg-6 box-widget pad-10">
							<div class="widget wid-type-0 widg-head-styled">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide hidden"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head box-widg-head pad-5">
									<strong>Sales Forecast - <?php echo date('Y'); ?></strong>
									<select class="pull-right input-control input-sm chart_data_selection" style="padding: 0;margin: -7px 0 0 0;width: 175px;height: 35px; border-radius: 0;border: 0;border-bottom: 1px solid #f4f4f4;">
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
									<div class="widg-content col-md-12 col-xs-12 clearfix">
										<div class="loading_chart" style="height: 457px; text-align: center; padding: 100px 53px; color: #ccc;"><i class="fa fa-spin fa-refresh fa-4x"></i></div>
										<div class id="job_book_area">
											<div id="chart"></div>
										</div> 	
									</div> 
								</div>

							</div>
						</div>


						<!-- ************************ -->






						<div class="col-md-6 col-sm-6 col-xs-12 col-lg-3 box-widget pad-10">
							<div class="widget wid-type-c widg-head-styled" style="height: 501px;">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head  box-widg-head pad-5"><strong>Average Final Invoice Days</strong> <span class="badges pull-right"> <span class="pull-right"><?php echo date('Y'); ?></span> </span></div>
								<div class="box-area clearfix">
									<div class="widg-content clearfix">

											<div class="loading_chart" style="height: 300px; text-align: center; padding: 100px 53px; color: #ccc; margin:0 auto;"><i class="fa fa-spin fa-refresh fa-4x"></i></div>


										<div class="pad-10" id="">

											<div class="clearfix center knob_box pad-10 small-widget">
												<?php // echo $this->dashboard->average_date_invoice(); ?>
											</div>
											<style type="text/css">.knob_box canvas{width: 100% !important;}</style>


											 
										</div>							
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

											<div class="loading_chart" style="height: 300px; text-align: center; padding: 100px 53px; color: #ccc; margin:0 auto;"><i class="fa fa-spin fa-refresh fa-4x"></i></div>
											
										<div class="" id="">
											<?php // $status_forecast = $this->dashboard->pm_sales_widget(); ?>
											<script type="text/javascript">
												// var raw_overall = '<?php //echo $status_forecast; ?>';
												// var overall_arr =  raw_overall.split('_');
												// var overall_progress = parseInt(overall_arr[0]);
												// var status_forecast = overall_arr[1];
												// $('.full_p').css('width',overall_progress+'%');
												// $('.full_p').html(overall_progress+'%');
											 //  	$('.full_p').prop('title','$'+status_forecast+' - Overall Progress');											  
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
											<div class="pad-top-5" id="" >
												<p style="padding: 5px 5px 6px; text-align: center;	"><i class="fa fa-spin fa-refresh fa-lg"></i></p>
												<?php // $this->dashboard->focus_projects_count_widget(); ?>
											</div>
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
												<p style="padding: 5px 5px 6px; text-align: center;	"><i class="fa fa-spin fa-refresh fa-lg"></i></p>												
												<?php //$this->dashboard->pm_estimates_widget(); ?>
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
											<div class="pad-top-5" id="" >
												<p style="padding: 5px 5px 6px; text-align: center;	"><i class="fa fa-spin fa-refresh fa-lg"></i></p>
												<?php //$this->dashboard->maintanance_average(); ?>
											</div>
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
											<div class="pad-top-5" id="" >
												<p style="padding: 5px 5px 6px; text-align: center;	"><i class="fa fa-spin fa-refresh fa-lg"></i></p>
												<?php //$this->dashboard->focus_get_po_widget(); ?>
											</div>
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
											<div class="pad-top-5" id="" >
												<p style="padding: 5px 5px 6px; text-align: center;	"><i class="fa fa-spin fa-refresh fa-lg"></i></p>
												<?php //$this->dashboard->wid_site_labour_hrs(); ?>
											</div>
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
											<div class="pad-top-5" id="" >
												<p style="padding: 5px 5px 6px; text-align: center;	"><i class="fa fa-spin fa-refresh fa-lg"></i></p>
												<?php // $this->dashboard->wid_quoted(); ?>
											</div>
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
												<p style="padding: 5px 5px 6px; text-align: center;	"><i class="fa fa-spin fa-refresh fa-lg"></i></p>
												
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
												<p style="padding: 5px 5px 6px; text-align: center;	"><i class="fa fa-spin fa-refresh fa-lg"></i></p>
												
											</div>
										</div>							
									</div>
								</div>
							</div>
						</div>


						<!-- ************************ -->
						
						<div class="clearfix"></div>


						<!-- ************************ -->


						<div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-0 widg-head-styled">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide hidden"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head fill box-widg-head pad-5"><i class="fa fa-tags  text-center "></i> <strong>Projects by Type </strong><span class="pull-right"> <?php echo date('Y'); ?></span></div>
								<div class="box-area clearfix" style="height:320px;">
									<div class="widg-content clearfix">

										<div id="" class="pad-5" style="">



											<div class="loading_chart" style="height: 300px; text-align: center; padding: 100px 53px; color: #ccc; margin:0 auto;"><i class="fa fa-spin fa-refresh fa-4x"></i></div>


<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 box-widget">
											<!-- <div class="" id="donut_prj_bt" style="text-align: center;"></div> -->



</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 box-widget m-top-10">
											<?php //echo $this->dashboard->focus_projects_by_type_widget(); ?>
</div>
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
													<?php //echo $this->dashboard->focus_top_ten_clients(); ?>
												</div>
											</div>
										</div>

										<div role="tabpanel" class="tab-pane fade in" id="contractors">
											<div id="" class="center col-lg-5 clearfix">
												<div class="" id="donut_b" style="text-align: center;"></div>
											</div>
											<div id="" class="col-lg-7 clearfix">
												<div id="" class="clearfix" style="height: 300px; overflow: auto;">
													<?php // echo $this->dashboard->focus_top_ten_con_sup('2'); ?>
												</div>
											</div>
										</div>

										<div role="tabpanel" class="tab-pane fade in" id="suppliers">
											<div id="" class="center col-lg-5 clearfix">
												<div class="" id="donut_c" style="text-align: center;"></div>
											</div>
											<div id="" class="col-lg-7 clearfix">
												<div id="" class="clearfix" style="height: 300px; overflow: auto;">
													<?php //echo $this->dashboard->focus_top_ten_con_sup('3'); ?>
												</div>
											</div>
										</div>

										<div role="tabpanel" class="tab-pane fade in" id="clients_mtnc">
											<div id="" class="center col-lg-5 clearfix">
												<div class="" id="donut_d" style="text-align: center;"></div>
											</div>
											<div id="" class="col-lg-7 clearfix">
												<div id="" class="clearfix" style="height: 300px; overflow: auto;">
													<?php // echo $this->dashboard->focus_top_ten_clients_mn(); ?>
												</div>
											</div>
										</div>
										
										<div role="tabpanel" class="tab-pane fade in" id="contractors_mtnc">
											<div id="" class="center col-lg-5 clearfix">
												<div class="" id="donut_e" style="text-align: center;"></div>
											</div>
											<div id="" class="col-lg-7 clearfix">
												<div id="" class="clearfix" style="height: 300px; overflow: auto;">
													<?php // echo $this->dashboard->focus_top_ten_con_sup_mn('2'); ?>
												</div>
											</div>
										</div>
										
										<div role="tabpanel" class="tab-pane fade in" id="suppliers_mtnc">
											<div id="" class="center col-lg-5 clearfix">
												<div class="" id="donut_f" style="text-align: center;"></div>
											</div>
											<div id="" class="col-lg-7 clearfix">
												<div id="" class="clearfix" style="height: 300px; overflow: auto;">
													<?php // echo $this->dashboard->focus_top_ten_con_sup_mn('3'); ?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="clearfix"></div>

						<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 box-widget pad-10">
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

<script type="text/javascript" src="<?php echo base_url(); ?>js/maps/maps.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/maps/employee_map.js"></script>