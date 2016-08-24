<?php date_default_timezone_set("Australia/Perth");  // date is set to perth ?>
<?php $this->load->module('bulletin_board'); ?>
<?php $this->load->module('dashboard'); ?>
<?php $this->load->module('users'); ?>
<?php $this->load->model('user_model'); ?>
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

<?php 
	$pm_type = $this->dashboard->pm_type();
	$user_id = $this->session->userdata('user_id');
	$fetch_user = $this->user_model->fetch_user($user_id);
	$user_details = array_shift($fetch_user->result_array());

	if($pm_type == 1){ // for director/pm
		$direct_company = explode(',',$user_details['direct_company'] );
	}

	if($pm_type == 2){ // for pm only
		$direct_company = explode(',',$user_details['user_focus_company_id'] );
	}
?>


<?php 


$fetch_user = $this->user_model->fetch_user(29);
$user_details = array_shift($fetch_user->result_array());

		 ?>

<?php $pm_name = $user_details['user_first_name'].' '.$user_details['user_last_name']; ?>

 <!-- maps api js -->

<script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyDs1g6kHxbVrkQe7e_CmR6MsfV_3LmLSlc"></script>

<script type="text/javascript">
var data = { "locations": <?php echo $this->dashboard->focus_get_map_locations_mn(); ?>};
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
												<div class="pad-top-5" id="" ><?php $this->dashboard->invoiced_mn(); ?></div>
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
												<div class=" " id=""><p>Uninvoiced <span class="pull-right"><?php echo date('Y'); ?></span></p></div>
												<hr class="" style="margin: 5px 0px 0px;">
												<div class="pad-top-5" id="" ><?php $this->dashboard->uninvoiced_widget_mn(); ?></div>
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
												<div class=" " id=""><p>Outstanding <span class="pull-right"><?php echo date('Y'); ?></span></p></div>
												<hr class="" style="margin: 5px 0px 0px;">
												<div class="pad-top-5" id="" ><?php $this->dashboard->outstanding_payments_widget_mn(); ?></div>
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
												<div class="pad-top-5" id="" ><?php $this->dashboard->wip_widget_mn(); ?></div>
											</div>							
										</div>
									</div>
								</div>
							</div>

							<div class=" col-xs-12 box-widget pad-10">
								<div class="progress no-m progress-termometer">
									<div class="progress-bar progress-bar-danger active progress-bar-striped full_p tooltip-enabled" title="" data-original-title="Overall Forecast Progress" style="background-color: rgb(251, 25, 38); border-radius: 0px 10px 10px 0px;"></div> 
								</div>
							</div>	

						<div class="col-md-12 col-sm-12 col-xs-12 col-lg-9 box-widget pad-10">
							<div class="widget wid-type-0 widg-head-styled">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide hidden"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head box-widg-head pad-5">
									<strong>Sales Forecast - <?php echo date('Y'); ?></strong>
									<select class="pull-right input-control input-sm chart_data_selection" style="padding: 0;margin: -7px 0 0 0;width: 175px;height: 35px; border-radius: 0;border: 0;border-bottom: 1px solid #f4f4f4;">
									
										<?php echo '<option value="'.$pm_name.'">'.$pm_name.'</option>'; ?>
										

									</select>
									<script type="text/javascript"> $('select.chart_data_selection').val('<?php echo $pm_name; ?>');</script>

								</div>


								<?php //var_dump($project_manager); ?>

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
									<div class="widg-content col-md-3 col-xs-12 clearfix" style="position: inherit;">
										<div class="loading_chart" style="height: 457px; text-align: center; padding: 100px 53px; color: #ccc;"><i class="fa fa-spin fa-refresh fa-4x"></i></div>
										<!-- <div class="" id="donut_a"></div> -->

										<div class="clearfix center knob_box pad-10 small-widget ">
											<p><br /></p>
											<p class="text-center m-bottom-10"><strong style="font-size: 16px;">Average Final Invoice Days</strong></p>
											<p><br /></p>

											<?php echo $this->dashboard->average_date_invoice_mn(); ?>
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
											<?php $status_forecast = $this->dashboard->pm_sales_widget_mn(); ?>
											<script type="text/javascript">
												var overall_progress = parseInt(<?php echo $status_forecast; ?>);
												$('.full_p').css('width',overall_progress+'%');
												$('.full_p').html(overall_progress+'%');
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
											<div class="pad-top-5" id="" ><?php $this->dashboard->focus_projects_count_widget_mn(); ?></div>
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
												<?php $this->dashboard->pm_estimates_widget_mn(); ?>
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
											<div class="pad-top-5" id="" ><?php $this->dashboard->focus_get_po_widget_mn(); ?></div>
										</div>							
									</div>
								</div>
							</div>
						</div>

						<!-- ************************ -->
						
					 
						
						<div class="clearfix"></div>


						<!-- ************************ -->

						<div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-a widg-head-styled ">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide hidden"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head fill box-widg-head pad-5"><i class="fa fa-map-marker fa-lg"></i> <strong>On-Going Projects in Australia</strong></div>
								<div class="box-area clearfix  pad-0-imp" style="height:300px;">
									<div class="widg-content clearfix pad-0-imp">
										<div id="map"></div>									
									</div>
								</div>
							</div>
						</div>

						<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-0 widg-head-styled">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide hidden"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head fill box-widg-head pad-5"><i class="fa fa-tags  text-center "></i> <strong>Projects by Type </strong><span class="pull-right"> <?php echo date('Y'); ?></span></div>
								<div class="box-area clearfix">
									<div class="widg-content clearfix">
										<div id="" class="pad-5" style="height: 290px; overflow: auto;">
											<?php echo $this->dashboard->focus_projects_by_type_widget_mn(); ?>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-lg-5 col-md-6 col-sm-12 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-e widg-head-styled">
								<div class="  fill box-widg-head pad-right-10 pull-right pad-top-3 m-3">
									<strong>
										<?php echo date('Y'); ?> <span  data-placement="left" class="pointer" ><i class="fa fa-info-circle tooltip-enabled" title="" data-html="true" data-placement="left" data-original-title="Top 20 Clients/Contractors/Suppliers having the highest job cost for the year <?php echo date('Y'); ?>."></i></span>
									</strong>
								</div>
								<div class="tabs_widget" >
									<ul  class="nav nav-tabs" role="tablist" style="height: 32px;">
										<li role="presentation" class="active"><a href="#clients" role="tab" id="clients-tab" data-toggle="tab" >Clients</a></li>
										<li role="presentation" class=""><a href="#contractors" role="tab" id="contractors-tab" data-toggle="tab" >Contractors</a></li>
										<li role="presentation" class=""><a href="#suppliers" role="tab" id="suppliers-tab" data-toggle="tab" >Suppliers</a></li>

									</ul>
									<div id="myTabContent" class="tab-content pad-10" style="height: 300px; overflow: auto;"> 
										<div role="tabpanel" class="tab-pane fade active in" id="clients">
											<?php echo $this->dashboard->focus_top_ten_clients_mn(); ?>
										</div>
										<div role="tabpanel" class="tab-pane fade in" id="contractors">
											<?php echo $this->dashboard->focus_top_ten_con_sup_mn('2'); ?>
										</div>
										<div role="tabpanel" class="tab-pane fade" id="suppliers" aria-labelledby="profile-tab"> 
											<?php echo $this->dashboard->focus_top_ten_con_sup_mn('3'); ?>
										</div>
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



<?php $this->load->view('assets/logout-modal'); ?>
<?php $this->bulletin_board->list_latest_post(); ?>

<script>
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
			echo "['".$pm_sales_data['user_pm_name']." Last Year',".$pm_sales_data['rev_jan'].",".$pm_sales_data['rev_feb'].",".$pm_sales_data['rev_mar'].",".$pm_sales_data['rev_apr'].",".$pm_sales_data['rev_may'].",".$pm_sales_data['rev_jun'].",".$pm_sales_data['rev_jul'].",".$pm_sales_data['rev_aug'].",".$pm_sales_data['rev_sep'].",".$pm_sales_data['rev_oct'].",".$pm_sales_data['rev_nov'].",".$pm_sales_data['rev_dec']."],";
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


 
 

// PM Project Manager Sales
	<?php foreach ($pms_sales_c_year->result_array() as $pm_sales_data ) {
		if($pm_sales_data['user_pm_name'] == 'Maintenance Manager' && $pm_sales_data['focus_comp_id'] == '6'  ){

		}else{
			echo "['".$pm_sales_data['user_pm_name']."',".$pm_sales_data['rev_jan'].",".$pm_sales_data['rev_feb'].",".$pm_sales_data['rev_mar'].",".$pm_sales_data['rev_apr'].",".$pm_sales_data['rev_may'].",".$pm_sales_data['rev_jun'].",".$pm_sales_data['rev_jul'].",".$pm_sales_data['rev_aug'].",".$pm_sales_data['rev_sep'].",".$pm_sales_data['rev_oct'].",".$pm_sales_data['rev_nov'].",".$pm_sales_data['rev_dec']."],";
		}
	} ?>
// PM Project Manager Sales

 



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
          	$amount = $focus_data_forecast_p[$pm_fct->comp_id] * ($pm_fct->forecast_percent / 100);
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
// PM Project Manager Forecast
 

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

            'Krzysztof Kiezun Forecast': '#CC79A7',
            'Krzysztof Kiezun Last Year': '#AAAAAA',
            'Krzysztof Kiezun WIP': '#009E73',
            'Krzysztof Kiezun': '#E69F00',

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
['Krzysztof Kiezun','Krzysztof Kiezun WIP'],
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

	

	chart.show(['<?php echo $pm_name; ?>','<?php echo $pm_name; ?> WIP','<?php echo $pm_name; ?> Forecast','<?php echo $pm_name; ?> Last Year']);


//	chart.show(['Overall Sales','Forecast','Last Year Sales','Focus Overall WIP']);
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
			chart.show(['Trevor Gamble Outstanding','Alan Liddell Outstanding','Stuart Hubrich Outstanding','Pyi Paing Aye Win Outstanding','Krzysztof Kiezun Outstanding','Maintenance Manager Outstanding']);
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

	if(data == 'Krzysztof Kiezun'){ 
		chart.hide(); 
		setTimeout(function () {
			chart.show(['Krzysztof Kiezun','Krzysztof Kiezun WIP','Krzysztof Kiezun Forecast','Krzysztof Kiezun Last Year']);
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
			chart.show(['Alan Liddell', 'Stuart Hubrich', 'Pyi Paing Aye Win','Krzysztof Kiezun','Maintenance Manager','Trevor Gamble']);
	}, 500);
}






var donuta = c3.generate({
     size: {
        height: 457
      },data: {
        columns: [ <?php $this->dashboard->donut_sales(); ?> ],
        type : 'donut',
        colors: {
            'NSW': '#FF7F0E',
            'WA': '#1F77B4'
        }, 
        onclick: function (d, i) { console.log("onclick", d, i); },
        onmouseover: function (d, i) { console.log("onmouseover", d, i); },
        onmouseout: function (d, i) { console.log("onmouseout", d, i); }
    },
             bindto: "#donut_a",
    donut: {
        title: '<?php echo date('Y'); ?> Sales Share'
    },tooltip: {
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




<!-- Modal -->
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


<script type="text/javascript" src="<?php echo base_url(); ?>js/maps/maps.js"></script>