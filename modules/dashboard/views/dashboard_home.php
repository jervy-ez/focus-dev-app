<?php date_default_timezone_set("Australia/Perth");  // date is set to perth ?>
<?php $this->load->module('bulletin_board'); ?>
<!-- title bar -->
<div class="container-fluid head-control">
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

<div class="container-fluid"  style="background: #ECF0F5;">
	<!-- Example row of columns -->
	<div class="row">				
		<?php $this->load->view('assets/sidebar'); ?>
		<div class="section col-sm-12 col-md-11 col-lg-11 pad-0-imp no-m-imp">
			<div class="">




				<div class="clearfix pad-10">
					<div class="widget_area row pad-0-imp no-m-imp">

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
											</div>

											<p>November 2014: $<strong>250,200.00</strong></p>	
										</div>								
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-3 col-sm-6 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-a small-widget">
								<div class="box-area clearfix row">
									<div class="widg-icon-inside col-xs-3"><i class="fa fa-briefcase  text-center fa-3x"></i></div>
									<div class="widg-content col-xs-9 clearfix">
										<p>Completed Projects<span class="badges pull-right m-right-10"> <span class="badge">4</span> <span class="label">Default</span></span> </p>
										<p class="value">45</p>
										<div class="value-bar"><div class="value pull-left" style="width:45%"></div></div>
										<p>Total Projects: 100</p>										
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-3 col-sm-6 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-b small-widget">
								<div class="box-area clearfix row">
									<div class="widg-icon-inside col-xs-3"><i class="fa fa-usd text-center fa-3x"></i></div>
									<div class="widg-content col-xs-9 clearfix">
										<p>Current Sales <span class="badges pull-right m-right-10"> <span class="badge">4</span> <span class="label">Default</span></span> </p>
										<p class="value">45</p>
										<div class="value-bar"><div class="value pull-left" style="width:45%"></div></div>
										<p>Total Sales: 100</p>										
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-3 col-sm-6 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-c small-widget">
								<div class="box-area clearfix row">
									<div class="widg-icon-inside col-xs-3"><i class="fa fa-puzzle-piece text-center fa-3x"></i></div>
									<div class="widg-content col-xs-9 clearfix">
										<p>Current WIP <span class="badges pull-right m-right-10"> <span class="badge">4</span> <span class="label">Default</span></span> </p>
										<p class="value">25</p>
										<div class="value-bar"><div class="value pull-left" style="width:25%"></div></div>
										<p>All Projects: 100</p>										
									</div>
								</div>
							</div>
						</div>


						<!-- ************************ -->

						
						
						<div class="col-md-9 col-sm-12 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-0 widg-head-styled">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide hidden"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head box-widg-head pad-5"><strong>Sales Forecast</strong></div>

								<div class="box-area clearfix row pad-right-10 pad-left-10">									
									<div class="widg-content col-md-9 col-xs-12 clearfix">
										<div class="loading_chart" style="height: 320px;    text-align: center;    padding: 100px 53px;    color: #ccc;"><i class="fa fa-spin fa-refresh fa-4x"></i></div>
										<div class id="job_book_area">
										<div id="chart"></div></div>
										<hr class="block m-bottom-10 m-top-5">
										
										<button class="btn btn-primary btn-sm" onclick="funca()" >Show Project Manager Shares</button>
										<button class="btn btn-success btn-sm m-left-5" onclick="funcb()" >Show Focus Sales</button>				
										<button class="btn btn-info btn-sm pull-right" data-toggle="modal" data-target="#add_data_chart" >Add Data</button>	
										<button class="btn btn-info btn-sm pull-right m-right-10" onclick='print_job_book();'>Print</button>				
									</div>
									<div class="widg-content col-md-3 col-xs-12 clearfix">
										<div class="loading_chart" style="height: 320px;    text-align: center;    padding: 100px 53px;    color: #ccc;"><i class="fa fa-spin fa-refresh fa-4x"></i></div>
										<div class="" id="donut_a"></div>

										<div class="clearfix row pad-0-imp no-m-imp hide hidden">
											<div class="col-md-12 col-sm-6 col-xl-12" id="donut_a_"></div>
											<div class="col-md-12 col-sm-6 col-xl-12" id="donut_b_"></div>   
										</div>  
							
										<hr class="block m-bottom-10 m-top-5">
										<p><strong>WA</strong>: $86,584,365.00 &nbsp; <strong>NSW</strong>: $10,256,544.00</p>										
									</div>
								</div>



							</div>
						</div>

						<div class="col-md-3 col-sm-12 col-xs-12 box-widget pad-10">

							<div class="widget wid-type-0 small-widget m-bottom-20">
								<div class="box-area clearfix row">
									<div class="widg-icon-inside col-xs-3"><i class="fa fa-credit-card text-center fa-3x"></i></div>
									<div class="widg-content fill col-xs-9 clearfix">
										<p>Uninvoiced <span class="badges pull-right m-right-10"> <span class="badge">4</span> <span class="label">Default</span></span> </p>
										<p class="value">30</p>
										<div class="value-bar"><div class="value pull-left" style="width:30%"></div></div>
										<p>Invoices: 100</p>										
									</div>
								</div>
							</div>


							<div class="widget wid-type-a small-widget m-bottom-20">
								<div class="box-area clearfix row">
									<div class="widg-icon-inside col-xs-3"><i class="fa fa-credit-card text-center fa-3x"></i></div>
									<div class="widg-content col-xs-9 clearfix">
										<p>Uninvoiced <span class="badges pull-right m-right-10"> <span class="badge">4</span> <span class="label">Default</span></span> </p>
										<p class="value">30</p>
										<div class="value-bar"><div class="value pull-left" style="width:30%"></div></div>
										<p>Invoices: 100</p>										
									</div>
								</div>
							</div>

							<div class="widget wid-type-b small-widget m-bottom-20">
								<div class="box-area clearfix row">
									<div class="widg-icon-inside col-xs-3"><i class="fa fa-credit-card text-center fa-3x"></i></div>
									<div class="widg-content fill col-xs-9 clearfix">
										<p>Uninvoiced <span class="badges pull-right m-right-10"> <span class="badge">4</span> <span class="label">Default</span></span> </p>
										<p class="value">30</p>
										<div class="value-bar"><div class="value pull-left" style="width:30%"></div></div>
										<p>Invoices: 100</p>										
									</div>
								</div>
							</div>

							<div class="widget wid-type-c small-widget">
								<div class="box-area clearfix row">
									<div class="widg-icon-inside col-xs-3"><i class="fa fa-credit-card text-center fa-3x"></i></div>
									<div class="widg-content col-xs-9 clearfix">
										<p>Uninvoiced <span class="badges pull-right m-right-10"> <span class="badge">4</span> <span class="label">Default</span></span> </p>
										<p class="value">30</p>
										<div class="value-bar"><div class="value pull-left" style="width:30%"></div></div>
										<p>Invoices: 100</p>										
									</div>
								</div>
							</div>




						</div>
						
						<div class="clearfix"></div>



						<!-- ************************ -->


						<div class="col-md-8 col-sm-12 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-a widg-head-styled  mid-widget">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide hidden"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head fill box-widg-head pad-5"><i class="fa fa-map-marker fa-lg"></i> <strong>Projects in Australia</strong></div>
								<div class="box-area clearfix">
									<div class="widg-content clearfix pad-0-imp">
										<div id="map"></div>									
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 col-sm-6 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-0 widg-head-styled">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide hidden"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head fill box-widg-head pad-5"><strong>Lorem Ipsum</strong> <span class="badges pull-right"> <span class="badge">4</span> <span class="label label-default">Default</span></span></div>
								<div class="box-area clearfix">
									<div class="widg-content clearfix">
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
										tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
										quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
										consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
										cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
										proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Excepteur sint occaecat cupidatat non
										proident, sunt in culpa qui officia des.</p>
										<hr class="block m-bottom-5 m-top-5">
										<p><strong>Excepteur sint occaecat</strong></p>										
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 col-sm-6 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-b widg-head-styled">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide hidden"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head fill box-widg-head pad-5"><strong>Lorem Ipsum</strong> <span class="badges pull-right"> <span class="badge">4</span> <span class="label label-default">Default</span></span></div>
								<div class="box-area clearfix">
									<div class="widg-content clearfix">
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
										tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
										quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
										consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
										cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
										proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Excepteur sint occaecat cupidatat non
										proident, sunt in culpa qui offi.</p>
										<hr class="block m-bottom-5 m-top-5">
										<p><strong>Excepteur sint occaecat</strong></p>										
									</div>
								</div>
							</div>
						</div>



						<div class="clearfix"></div>


					

						<!-- ************************ -->

 
						<div class="col-md-3 col-sm-6 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-0 small-widget">
								<div class="box-area clearfix row">
									<div class="widg-icon-inside col-xs-3"><i class="fa fa-credit-card text-center fa-3x"></i></div>
									<div class="widg-content fill col-xs-9 clearfix">
										<p>Uninvoiced <span class="badges pull-right m-right-10"> <span class="badge">4</span> <span class="label">Default</span></span> </p>
										<p class="value">30</p>
										<div class="value-bar"><div class="value pull-left" style="width:30%"></div></div>
										<p>Invoices: 100</p>										
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-3 col-sm-6 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-a small-widget">
								<div class="box-area clearfix row">
									<div class="widg-icon-inside col-xs-3"><i class="fa fa-briefcase text-center fa-3x"></i></div>
									<div class="widg-content fill col-xs-9 clearfix">
										<p>Completed Projects<span class="badges pull-right m-right-10"> <span class="badge">4</span> <span class="label">Default</span></span> </p>
										<p class="value">45</p>
										<div class="value-bar"><div class="value pull-left" style="width:45%"></div></div>
										<p>Total Projects: 100</p>										
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-3 col-sm-6 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-b small-widget">
								<div class="box-area clearfix row">
									<div class="widg-icon-inside col-xs-3"><i class="fa fa-usd  text-center fa-3x"></i></div>
									<div class="widg-content fill col-xs-9 clearfix">
										<p>Current Sales <span class="badges pull-right m-right-10"> <span class="badge">4</span> <span class="label">Default</span></span> </p>
										<p class="value">500</p>
										<div class="value-bar"><div class="value pull-left" style="width:75%"></div></div>
										<p>Total Sales: 700</p>										
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-3 col-sm-6 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-c small-widget">
								<div class="box-area clearfix row">
									<div class="widg-icon-inside col-xs-3"><i class="fa fa-puzzle-piece text-center fa-3x"></i></div>
									<div class="widg-content fill col-xs-9 clearfix">
										<p>Current WIP <span class="badges pull-right m-right-10"> <span class="badge">4</span> <span class="label">Default</span></span> </p>
										<p class="value">25</p>
										<div class="value-bar"><div class="value pull-left" style="width:25%"></div></div>
										<p>All Projects: 100</p>										
									</div>
								</div>
							</div>
						</div>


						<!-- ************************ -->



						<div class="col-md-3 col-sm-6 col-xs-12 box-widget pad-10">
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

						<div class="col-md-3 col-sm-6 col-xs-12 box-widget pad-10">
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

						<div class="col-md-3 col-sm-6 col-xs-12 box-widget pad-10">
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

						<div class="col-md-3 col-sm-6 col-xs-12 box-widget pad-10">
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

<script type='text/javascript' src='http://d3js.org/d3.v3.min.js'></script>
<script type='text/javascript' src="http://rawgit.com/masayuki0812/c3/master/c3.js"></script>
<link rel="stylesheet" type="text/css" href="http://rawgit.com/masayuki0812/c3/master/c3.css">



 <script>

     var chart = c3.generate({
      size: {
        height: 320
      },data: {
        x : 'x',
        columns: [
          ['x', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
          ['Focus WA', 650, 470, 400, 710, 540, 760, 650, 470, 400, 710, 540, 760],   
          ['Focus NSW',  130, 120, 150, 140, 160, 150, 130, 120, 150, 140, 160, 150],    
          ['Alan Liddell', 30, 20, 50, 40, 60, 50, 30, 20, 50, 40, 60, 50],
          ['Krzysztof Kiezun', 200, 130, 90, 240, 130, 220, 200, 130, 90, 240, 130, 220],
          ['Maintenance Manager', 90, 70, 20, 50, 60, 120, 90, 70, 20, 50, 60, 120],
          ['Pyi Paing Aye Win', 130, 120, 150, 140, 160, 150, 130, 120, 150, 140, 160, 150],
          ['Trevor Gamble', 200, 130, 90, 240, 130, 220, 200, 130, 90, 240, 130, 220],         
        //  ['data4', 200, 130, 90, 240, 130, 220, 200, 130, 90, 240, 130, 220],
          ['Stuart Hubrich', 130, 120, 150, 140, 160, 150, 130, 120, 150, 140, 160, 150],
          ['Forecast WA', 300, 200, 160, 400, 250, 250, 300, 200, 160, 400, 250, 250],
          ['Forecast NSW', 90, 70, 20, 50, 60, 120, 90, 70, 20, 50, 60, 120],



          <?php // echo "['TEST', 200, 130, 90, 240, 130, 220, 200, 130, 90, 240, 130, 220],";      ?>
        //  ['data10', 150, 180, 50, 190, 60, 210, 150, 180, 50, 190, 60, 210],
        ],
        selection: {
            enabled: true
        },
        type: 'bar',
/*
        colors: {
            'TEST': '#555',
        },
        color: function (color, d) {
            // d will be 'id' when called for legends
            return d.id && d.id === 'data3' ? d3.rgb(color).darker(d.value / 150) : color;
        },

*/

        types: {
          'Forecast WA': 'spline',
          'Forecast NSW': 'line',
          <?php //echo "'".$_POST['data_name']."': '".$_POST['display_type']."',"; ?>
        //  data4: 'area',
        },
        groups: [
          ['Alan Liddell','Krzysztof Kiezun','Maintenance Manager','Pyi Paing Aye Win','Trevor Gamble'],
        //  ['data5','data10'],
        ],
        order: 'asc' // stack order by sum of values descendantly.
//      order: 'desc'  // stack order by sum of values ascendantly.
//      order: null   // stack order by data definition.
      },
    tooltip: {
        grouped: false // Default true
    },
             bindto: "#chart",
bar: {
  width: { ratio: 0.5 }
},
point: {
  select: {
    r: 6
  }
},
onrendered: function () { 
 

 
	$('.loading_chart').remove();
 

	 },
      zoom: {
        enabled: true
      },
      axis: {
        x: {
          type: 'category',
          tick: {
            rotate: 0,
            multiline: false
          },
          height: 50
        }
      }
    });

chart.select(['Forecast NSW']);
chart.select(['Forecast WA']);
chart.hide(['Alan Liddell','Krzysztof Kiezun','Maintenance Manager','Pyi Paing Aye Win','Trevor Gamble','Stuart Hubrich']);




function funca(){
  chart.hide(['Focus WA', 'Focus NSW', 'Alan Liddell','Krzysztof Kiezun','Maintenance Manager','Pyi Paing Aye Win','Trevor Gamble','Stuart Hubrich']);

  setTimeout(function () {
    chart.show(['Alan Liddell','Krzysztof Kiezun','Maintenance Manager','Pyi Paing Aye Win','Trevor Gamble','Stuart Hubrich']);
  }, 500);
}


function funcb(){
  chart.hide(['Focus WA', 'Focus NSW', 'Alan Liddell','Krzysztof Kiezun','Maintenance Manager','Pyi Paing Aye Win','Trevor Gamble','Stuart Hubrich']);

  setTimeout(function () {
   chart.show(['Focus WA', 'Focus NSW']);
 }, 500);
}


<?php if(isset($_POST['data_name'])): ?>
setTimeout(function () {
    chart.load({
        columns:<?php echo "[ ['".$_POST['data_name']."', ".$_POST['value_items']."] ]";?>,
        type: 'line',
        colors: { <?php echo "'".$_POST['data_name']."'"; ?> : <?php echo "'".$_POST['data_color']."'"; ?> }
    });
chart.select([<?php echo "'".$_POST['data_name']."'"; ?> ]);
}, 2000);

<?php endif; ?>

setTimeout(function () {
//  chart.hide(['data2', 'data3']);
 // chart.show(['data2', 'data5']);
//chart.hide(['data2', 'data5']);

}, 3000);

/*
setTimeout(function () {
 chart.load({
  columns: [
          ['data11', 200, 130, 90, 240, 130, 220, 200, 130, 90, 240, 130, 220],
          ['data12', 150, 180, 50, 190, 60, 210, 150, 180, 50, 190, 60, 210],
  ]
});
}, 2000);

setTimeout(function () {
  chart.unload({
    ids: ['data9', 'data2', 'data8', 'data7', 'data1']
  });
},4000);



columns:[ ['ADASDAS',  ] ],
columns:[ [data_name, 660, 630,620, 650, 640, 660, 650] ],


setTimeout(function () {
  chart.unload({
    ids: ['data11', 'data12']
  });
},4000);


200, 130, 90, 240, 130, 220, 200, 130, 90, 240, 130, 220
*/



var donuta = c3.generate({
     size: {
        height: 320
      },data: {
        columns: [
            ['Focus NSW', 10256544.00],
            ['Focus WA', 86584365.00],
        ],
        type : 'donut',
        colors: {
            'Focus NSW': '#FF7F0E',
            'Focus WA': '#1F77B4'
        }, 
        onclick: function (d, i) { console.log("onclick", d, i); },
        onmouseover: function (d, i) { console.log("onmouseover", d, i); },
        onmouseout: function (d, i) { console.log("onmouseout", d, i); }
    },
             bindto: "#donut_a",
    donut: {
        title: '2014 Sales Share'
    }
});


var donutb = c3.generate({
     size: {
        height: 200
      },data: {
        columns: [
            ['Project Manager Name', 490],
            ['Focus WA', 820],
        ],
        type : 'donut',
        colors: {
            'Project Manager Name': '#9467BD',
            'Focus WA': '#FF7F0E'
        },
        color: function (color, d) {
            // d will be 'id' when called for legends
            return d.id && d.id === 'data3' ? d3.rgb(color).darker(d.value / 150) : color;
        },
        onclick: function (d, i) { console.log("onclick", d, i); },
        onmouseover: function (d, i) { console.log("onmouseover", d, i); },
        onmouseout: function (d, i) { console.log("onmouseout", d, i); }
    },
             bindto: "#donut_b",
    donut: {
        title: 'TOP PM Share'
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