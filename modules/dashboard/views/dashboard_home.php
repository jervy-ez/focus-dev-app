<?php date_default_timezone_set("Australia/Perth");  // date is set to perth ?>
<?php $this->load->module('bulletin_board'); ?>
<?php $this->load->module('dashboard'); ?>
<?php $months = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"); ?>
<!-- title bar -->




 <!-- maps api js -->

<script src="https://maps.googleapis.com/maps/api/js?v=3"></script>

<script type="text/javascript">
var data = { "locations": <?php echo $this->dashboard->focus_get_map_locations(); ?>};	


</script>

<script type="text/javascript" src="<?php echo base_url(); ?>js/maps/markerclusterer_packed.js"></script>

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
									<div class="box-area clearfix row">
										<div class="widg-icon-inside col-xs-3"><i class="fa fa-list-alt text-center fa-3x"></i></div>
										<div class="widg-content fill col-xs-9 clearfix">
											<div class="pad-right-15">
												<div class="pad-left-5 pad-top-3" id=""><p>Invoiced <span class="pull-right"><?php echo date('Y'); ?></span></p></div>
												<hr class="" style="margin: 5px 0px 1px;">
												<div class="pad-top-3" id=""><?php $this->dashboard->sales_widget(); ?></div>
											</div>							
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-6 col-lg-3 col-sm-6 col-xs-12 box-widget pad-10">
								<div class="widget wid-type-a small-widget">
									<div class="box-area clearfix row">
										<div class="widg-icon-inside col-xs-3"><i class="fa fa-list  text-center fa-3x"></i></div>
										<div class="widg-content fill col-xs-9 clearfix">
											<div class="pad-right-15">
												<div class="pad-left-5 pad-top-3" id=""><p>Uninvoiced <span class="pull-right"><?php echo date('Y'); ?></span></p></div>
												<hr class="" style="margin: 5px 0px 1px;">
												<div class="pad-top-3" id=""><?php $this->dashboard->uninvoiced_widget(); ?></div>
											</div>							
										</div>
									</div>
								</div>
							</div>  

							<div class="col-md-6 col-lg-3 col-sm-6 col-xs-12 box-widget pad-10">
								<div class="widget wid-type-f small-widget">
									<div class="box-area clearfix row">
										<div class="widg-icon-inside col-xs-3"><i class="fa fa-server text-center fa-3x"></i></div>
										<div class="widg-content fill col-xs-9 clearfix">
											<div class="pad-right-15">
												<div class="pad-left-5 pad-top-3" id=""><p>Outstanding <span class="pull-right"><?php echo date('Y'); ?></span></p></div>
												<hr class="" style="margin: 5px 0px 1px;">
												<div class="pad-top-3" id=""><?php $this->dashboard->outstanding_payments_widget(); ?></div>
											</div>						
										</div>
									</div>
								</div>
							</div>


							<div class="col-md-6 col-lg-3 col-sm-6 col-xs-12 box-widget pad-10">
								<div class="widget wid-type-b small-widget">
									<div class="box-area clearfix row">
										<div class="widg-icon-inside col-xs-3"><i class="fa fa-tasks  text-center fa-3x"></i></div>
										<div class="widg-content fill col-xs-9 clearfix">
											<div class="pad-right-15">
												<div class="pad-left-5 pad-top-3" id=""><p>WIP  <span class="pull-right"><?php echo date('Y'); ?></span></p></div>
												<hr class="" style="margin: 5px 0px 1px;">
												<div class="pad-top-3 hide" id=""><?php // $this->dashboard->pm_estimates_widget(); ?></div>
												<div class="pad-top-3" id="">
													<?php echo $this->dashboard->wip_widget(); ?>
												</div>
											</div>						
										</div>
									</div>
								</div>
							</div>



						<div class="col-md-12 col-sm-12 col-xs-12 col-lg-9 box-widget pad-10">
							<div class="widget wid-type-0 widg-head-styled">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide hidden"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head box-widg-head pad-5"><strong>Sales Forecast <span class="pull-right"><?php echo date('Y'); ?></span></strong></div>

								<div class="box-area clearfix row pad-right-10 pad-left-10">									
									<div class="widg-content col-md-9 col-xs-12 clearfix">
										<div class="loading_chart" style="height: 412px;    text-align: center;    padding: 100px 53px;    color: #ccc;"><i class="fa fa-spin fa-refresh fa-4x"></i></div>
										<div class id="job_book_area">
										<div id="chart"></div></div>
										<hr class="block m-bottom-10 m-top-5">
										

										<button class="btn btn-info btn-sm" id="reset_chart" onclick="bttnB(this)" ><i class="fa fa-exchange"></i> Reset Chart</button>
										<button class="btn btn-primary btn-sm" id="visible_overall" onclick="bttnA(this)" ><i class="fa fa-exchange"></i> Outstanding</button>

										<button class="btn btn-warning btn-sm" id="visible_forecast" onclick="bttnC(this)" ><i class="fa fa-exchange"></i> Focus WA</button>
										<button class="btn btn-success btn-sm" id="visible_forecast" onclick="bttnD(this)" ><i class="fa fa-exchange"></i> Focus NSW</button>
										<button class="btn btn-sm" id="visible_forecast" onclick="bttnE(this)" ><i class="fa fa-exchange"></i> PM Actual Sales</button>						
									</div>
									<div class="widg-content col-md-3 col-xs-12 clearfix">
										<div class="loading_chart" style="height: 400px;    text-align: center;    padding: 100px 53px;    color: #ccc;"><i class="fa fa-spin fa-refresh fa-4x"></i></div>
										<div class="" id="donut_a"></div>

							
										<hr class="block m-bottom-10 m-top-5">
										<?php $this->dashboard->donut_sales(1); ?>										
									</div>
								</div>



							</div>
						</div>

 
















 
<!-- 

						<div class="col-md-3 col-sm-6 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-d small-widget">
								<div class="box-area clearfix row">
									<div class="widg-icon-inside col-xs-3"><i class="fa fa-credit-card text-center fa-3x"></i></div>
									<div class="widg-content fill col-xs-9 clearfix">
										
										<div class="pad-right-15">


											<p>This Month's Sales for WA</p>
											<p class="value">$<strong>150,000.00</strong></p>

											<div class="progress m-bottom-5 m-top-5 slim tooltip-enabled" title="15.95% Sales Drop">
												<div class="progress-bar progress-bar-danger" style="width: 15.95%"></div>
											</div>f

											<p>November 2014: $<strong>250,200.00</strong></p>	
										</div>								
									</div>
								</div>
							</div>
						</div>
 -->


  




 
						<!-- ************************ -->

						

						<div class="col-md-6 col-sm-6 col-xs-12 col-lg-3 box-widget pad-10">


					 
							<div class="widget wid-type-0 widg-head-styled" style="height: 501px;">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head fill box-widg-head pad-5"><strong>Project Manager Sales</strong> <span class="badges pull-right"> <span class="pull-right"><?php echo date('Y'); ?></span> </span></div>
								<div class="box-area clearfix">
									<div class="widg-content clearfix">
										<div class="pad-5" id="">
											<?php $this->dashboard->pm_sales_widget(); ?>
										</div>							
									</div>
								</div>
							</div>
					 <!-- 
							<div class="widget wid-type-c widg-head-styled hide" style="height: 179px;">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head fill box-widg-head pad-5"><strong>Lorem Ipsum</strong> <span class="badges pull-right"> <span class="badge">4</span> <span class="label label-default">Default</span></span></div>
								<div class="box-area clearfix">
									<div class="widg-content clearfix">
										<div class="pad-3" id="">
											<p><strong>Lorem ipsum dolor siicia des.</strong></p>
											<p>$10001000101010</p>
											<hr class="block m-bottom-3 m-top-3">
											<p><strong>Lorem ipsum dolor siicia des.</strong></p>
											<p>$10001000101010</p>											
										</div>							
									</div>
								</div>
							</div>
						  -->

 




						</div>


 

						<!-- ************************ -->


						<div class="col-md-6 col-lg-3 col-sm-6 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-b">
								<div class="widg-icon-inside col-xs-3" ><div id="" class=""><i class="fa fa-cube  text-center fa-3x"></i></div></div>
								<div class="widg-content fill col-xs-9 clearfix pad-0-imp">
									<div class="widg-head box-widg-head pad-5 fill pad-0-imp" style="background-color: #73B573;">
										<div id="" class="clearfix row pad-3">
											<strong class="text-center col-xs-4"><?php echo date('Y'); ?></strong> 
											<strong class="text-center col-xs-4">Invoiced</strong> 
											<strong class="text-center col-xs-4">WIP</strong>
										</div>
									</div>
									<div class="box-area clearfix pad-top-3">
										<div class="widg-content clearfix fill">
											<?php echo $this->dashboard->focus_projects_count_widget(); ?>
										</div>
									</div>
								</div>
							</div>
						</div> 
						

						
							<div class="col-md-6 col-lg-3 col-sm-6 col-xs-12 box-widget pad-10">
								<div class="widget wid-type-0 small-widget">
									<div class="box-area clearfix row">
										<div class="widg-icon-inside col-xs-3"><i class="fa fa-user-times text-center fa-3x"></i></div>
										<div class="widg-content fill col-xs-9 clearfix">
											<div class="pad-right-15">
												<div class="pad-left-5 pad-top-3" id=""><p>Quotes Un-Acepted <span class="pull-right"><?php echo date('Y'); ?></span></p></div>
												<hr class="" style="margin: 5px 0px 1px;">
												<div class="pad-top-3" id=""><?php $this->dashboard->pm_estimates_widget(); ?></div>
											</div>						
										</div>
									</div>
								</div>
							</div>


						<div class="col-md-6 col-lg-3 col-sm-6 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-c small-widget" >
								<div class="box-area clearfix row">
									<div class="widg-icon-inside col-xs-3" ><div id="" class=""><i class="fa fa-calendar  text-center fa-3x"></i></div></div>
									<div class="widg-content fill col-xs-9 clearfix">
										<div id="" class="pad-5">
											<p>Maintenance Average Days <span class="pull-right m-right-10"><?php echo date('Y'); ?></span></p>
											<hr class=" m-bottom-5 m-top-3">
											<?php echo $this->dashboard->maintanance_average(); ?>
										</div>								
									</div>
								</div>
							</div>
						</div>

						
						<div class="col-md-6 col-lg-3 col-sm-6 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-d small-widget">
								<div class="box-area clearfix row">
									<div class="widg-icon-inside col-xs-3" ><div id="" class=""><i class="fa fa-credit-card  text-center fa-3x"></i></div></div>
									<div class="widg-content fill col-xs-9 clearfix">
											<div class="pad-right-15">
												<div class="pad-left-5 pad-top-3" id=""><p>Purchase Orders - <small>Total Balances</small> <span class="pull-right"><?php echo date('Y'); ?></span></p></div>
												
											<hr class=" m-bottom-5 m-top-3">
												<div class="" id="">
													<?php echo $this->dashboard->focus_get_po_widget(); ?>
												</div>
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
								<div class="box-area clearfix  pad-0-imp" style="height:290px;">
									<div class="widg-content clearfix pad-0-imp">
										<div id="map"></div>									
									</div>
								</div>
							</div>
						</div>

						<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-0 widg-head-styled">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide hidden"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head fill box-widg-head pad-5"><i class="fa fa-tags  text-center "></i> <strong>Projects by Type </strong><span class="pull-right"> <?php echo date('Y'); ?></span></div>
								<div class="box-area clearfix">
									<div class="widg-content clearfix">
										<div id="" class="pad-5" style="height: 282px; overflow: auto;">
											<div id="" class="clearfix"><p> <strong class="col-md-2">#</strong><span class="col-md-6 col-sm-4">Category</span> <strong class="col-md-4 col-sm-4"><i class="fa fa-usd" aria-hidden="true"></i> Cost Total</strong></p></div>
											<div class="col-md-12"><hr class="block m-bottom-5 m-top-5"></div>
											<?php echo $this->dashboard->focus_projects_by_type_widget(); ?>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-e widg-head-styled">

								<div class="  fill box-widg-head pad-right-10 pull-right pad-top-3 m-3">
									<strong>
										<?php echo date('Y'); ?> Top 20 <span  data-placement="left" class="popover-test pointer " title="" data-content="Top 20 Clients/Contractors/Suppliers having the highest job cost for the year <?php echo date('Y'); ?>." ><i class="fa fa-info-circle "></i></span>
									</strong>
								</div>

								<div class="tabs_widget" >
									<ul  class="nav nav-tabs" role="tablist" style="height: 32px;">
										<li role="presentation" class="active"><a href="#clients" role="tab" id="clients-tab" data-toggle="tab" >Clients</a></li>
										<li role="presentation" class=""><a href="#contractors" role="tab" id="contractors-tab" data-toggle="tab" >Contractors</a></li>
										<li role="presentation" class=""><a href="#suppliers" role="tab" id="suppliers-tab" data-toggle="tab" >Suppliers</a></li>

									</ul>
									<div id="myTabContent" class="tab-content pad-10" style="height: 290px; overflow: auto;"> 
										<div role="tabpanel" class="tab-pane fade active in" id="clients">
											<?php echo $this->dashboard->focus_top_ten_clients(); ?>
										</div>
										<div role="tabpanel" class="tab-pane fade in" id="contractors">
											<?php echo $this->dashboard->focus_top_ten_con_sup('1'); ?>
										</div>
										<div role="tabpanel" class="tab-pane fade" id="suppliers" aria-labelledby="profile-tab"> 
											<?php echo $this->dashboard->focus_top_ten_con_sup('2'); ?>
										</div>
									</div>
								</div>


							</div>

							
						</div>
 


						<div class="clearfix"></div>


						<div class="col-md-3 col-sm-6 col-xs-12 box-widget pad-10 hide ">
							<div class="widget wid-type-0 widg-head-styled">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide hidden"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head box-widg-head pad-5"><strong>Lorem Ipsum</strong> <span class="badges pull-right"> <span class="badge">4</span> <span class="label label-default">Default</span></span></div>
								<div class="box-area clearfix">
									<div class="widg-content clearfix">
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
										tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
										quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
										consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
										cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
										proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
										<hr class="block m-bottom-5 m-top-5">
										<p><strong>Excepteur sint occaecat</strong></p>										
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-3 col-sm-6 col-xs-12 box-widget pad-1 hide 0">
							<div class="widget wid-type-a widg-head-styled">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide hidden"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head box-widg-head pad-5"><strong>Lorem Ipsum</strong> <span class="badges pull-right"> <span class="badge">4</span> <span class="label label-default">Default</span></span></div>
								<div class="box-area clearfix">
									<div class="widg-content clearfix">
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
										tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
										quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
										consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
										cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
										proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
										<hr class="block m-bottom-5 m-top-5">
										<p><strong>Excepteur sint occaecat</strong></p>										
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-3 col-sm-6 col-xs-12 box-widget pad-10 hide ">
							<div class="widget wid-type-b widg-head-styled">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide hidden"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head box-widg-head pad-5"><strong>Lorem Ipsum</strong> <span class="badges pull-right"> <span class="badge">4</span> <span class="label label-default">Default</span></span></div>
								<div class="box-area clearfix">
									<div class="widg-content clearfix">
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
										tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
										quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
										consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
										cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
										proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
										<hr class="block m-bottom-5 m-top-5">
										<p><strong>Excepteur sint occaecat</strong></p>										
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-3 col-sm-6 col-xs-12 box-widget pad-10 hide ">
							<div class="widget wid-type-c widg-head-styled">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide hidden"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head box-widg-head pad-5"><strong>Lorem Ipsum</strong> <span class="badges pull-right"> <span class="badge">4</span> <span class="label label-default">Default</span></span></div>
								<div class="box-area clearfix">
									<div class="widg-content clearfix">
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
										tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
										quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
										consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
										cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
										proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
										<hr class="block m-bottom-5 m-top-5">
										<p><strong>Excepteur sint occaecat</strong></p>										
									</div>
								</div>
							</div>
						</div>



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
        height: 412
      },data: {
        x : 'x',
        columns: [
          ['x',

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





          ?> ],



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

?>],

<?php

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

?>],

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

?>],

<?php

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

?>],


<?php




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


 ?>






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
 





<?php foreach ($pms_sales_c_year->result_array() as $pm_sales_data ) {
	echo "['".$pm_sales_data['user_pm_name']."',".$pm_sales_data['rev_jan'].",".$pm_sales_data['rev_feb'].",".$pm_sales_data['rev_mar'].",".$pm_sales_data['rev_apr'].",".$pm_sales_data['rev_may'].",".$pm_sales_data['rev_jun'].",".$pm_sales_data['rev_jul'].",".$pm_sales_data['rev_aug'].",".$pm_sales_data['rev_sep'].",".$pm_sales_data['rev_oct'].",".$pm_sales_data['rev_nov'].",".$pm_sales_data['rev_dec']."],";
} ?>



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





          ?> ],

 

        ],
        selection: {enabled: true},
        type: 'bar',
        colors: {
            'Forecast': '#764B8E'
        },
        types: {   'Forecast' : 'line',  



<?php foreach ($focus_indv_comp_forecast->result_array() as $indv_comp_forec): ?>


	<?php echo "'".$indv_comp_forec['company_name']." Forecast': 'line',"; ?>


	<?php endforeach; ?>
 


        },
        groups: [ ['Outstanding','Focus Sales'],['Focus Shopfit Pty Ltd','Focus Shopfit Pty Ltd Outstanding'],['Focus Shopfit NSW Pty Ltd','Focus Shopfit NSW Pty Ltd Outstanding']  ],
        order: 'desc',
      },
    tooltip: {
        grouped: true // false // Default true
    },
             bindto: "#chart",
bar:{ width:{ ratio: 0.5 }},
point:{ select:{ r: 6 }},
onrendered: function () { $('.loading_chart').remove(); },
zoom: {enabled: true, rescale: true,extent: [1, 7]},
legend: { show: false },


axis: {x: {type: 'category', tick: {rotate: 0,multiline: false}, height: 0} },
tooltip: {
        format: {
     //     title: function (x) { return 'Data ' + x; },
            value: function (value, ratio, id) {
               // var format = id === 'data1' ? d3.format(',') : d3.format('$');
                var format = d3.format(',');
                
             	var mod_value = Math.round(value)
                return format(mod_value);
            }
        } 

    }
    });

chart.select();


chart.hide();
chart.show(['Overall Sales','Forecast','Last Year Sales']);




function funca(){
	chart.hide();
	chart.groups([
      	 ['Alan Liddell','Trevor Gamble','Pyi Paing Aye Win','Krzysztof Kiezun','Maintenance Manager 5','Stuart Hubrich','Maintenance Manager 6'],
         ['Focus WA','Focus NSW']
    ]);
  	setTimeout(function () {
    	chart.show(['Alan Liddell','Krzysztof Kiezun','Pyi Paing Aye Win','Trevor Gamble','Stuart Hubrich','Maintenance Manager 5','Maintenance Manager 6']);
	}, 500);
	setTimeout(function () {   chart.show(['Forecast 2015']); }, 1000);
}


function funcb(){
	chart.hide();
	setTimeout(function () {
		chart.groups([['Focus NSW','Focus WA']]);
		chart.show(['Focus WA', 'Focus NSW']);
	}, 500);

	setTimeout(function () {   chart.show(['Forecast 2015']); }, 1000);
}





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
        height: 400
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





function printDiv(divName) {
      var printContents = document.getElementById(divName).innerHTML;     
   var originalContents = document.body.innerHTML;       
   document.body.innerHTML = printContents;      
   window.print();      
   document.body.innerHTML = originalContents;
   }
 

 
 





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