<?php date_default_timezone_set("Australia/Perth");  // date is set to perth ?>
<?php $this->load->module('bulletin_board'); ?>
<?php $this->load->module('dashboard'); ?>
<?php $months = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"); ?>
<?php $this->load->module('dashboard/estimators'); ?>
<!-- title bar -->


<?php


$current_year = date("Y");
$current_month = date("m");
$current_day = date("d");

$last_year = $current_year-1;
$start_last_year = "01/01/$last_year";
$last_year_same_date = "$current_day/$current_month/$last_year";

$start_current_year = "01/01/$current_year";
$current_date = date("d/m/Y");



$set_colors = array('#D68244','#e08d73', '#57a59c', '#836396', '#B13873', '#D6AB44', '#92C53F', '#D6D544' );
$estimator_colors = array();
$counter = 0;

$estimator = $this->dashboard_m->fetch_project_estimators();
$estimator_list = $estimator->result();

foreach ($estimator_list as $est ) {
	if($est->user_first_name != ''){
		$estimator_name = strtolower(str_replace(' ','_', $est->user_first_name) ); 
		$estimator_colors[$estimator_name] = $set_colors[$counter];
		$counter++;
	}
}

if(isset($assign_id) && $assign_id != ''){
		$user_id = $assign_id;
	}else{
		$user_id = $this->session->userdata('user_id');
	}

$estimator_colors['Danikka'] = $set_colors[5];


?>

<style type="text/css">
	<?php 
		foreach ($estimator_colors as $key => $value) {
			echo '.'.strtolower($key).'{ background-color: '.$value.' !important; }';
		}
		$base_url = base_url();
	?>
	.red_deadline{       background-image: url( <?php echo $base_url; ?>img/grid-end.png);   background-repeat:no-repeat; }
</style>



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
			<div class="m-top-10">

				<script type="text/javascript">
					var base_url = "<?php echo base_url(); ?>";

					function pre_load_module(target,clsFnctn,timeDelay){

						$(window).load(function() {
							setTimeout(function() {

								$.ajax({
									'url' : base_url+clsFnctn,
									'type' : 'GET',
									'success' : function(data){

										$(target).hide().html(data).fadeIn();

										/*$(target).html(data)*/
										$('.tooltip-enabled').tooltip(); 
										$(".knob").knob();
									}
								});
  

							}, timeDelay);
						});
					}

				</script>



 
						 



		 
						<!-- ************************ -->
						
						<div class="clearfix"></div>

						<!-- ************************ -->


 



			<div class="clearfix pad-10">
				<div class="widget_area row pad-0-imp no-m-imp">

  	

					

						<div class=" col-xs-12 box-widget pad-10">
							<div class="progress no-m progress-termometer">
								<div class="progress-bar progress-bar-danger active progress-bar-striped full_p tooltip-pb" title="" style="background-color: rgb(251, 25, 38); border-radius: 0px 10px 10px 0px;"></div> 
								<script type="text/javascript">
									$(window).load(function() {
										setTimeout(function() {
											$.ajax({
												'url' : base_url+'dashboard/pm_sales_widget/1',
												'type' : 'GET',
												'success' : function(result){
													var raw_overall = result;
													var overall_arr =  raw_overall.split('_');
													var overall_progress = parseInt(overall_arr[0]);
													var status_forecast = overall_arr[1];
													$('.full_p').css('width',overall_progress+'%');
													$('.full_p').html(overall_progress+'%');
													$('.full_p').prop('title','$'+status_forecast+' - Overall Progress');
													$('.tooltip-pb').tooltip();				 
												}
											});
										}, 5000);
									});
								</script>
							</div>
						</div>	

						<div class=" col-xs-12 box-widget pad-10">
							<script type="text/javascript"> pre_load_module('#progressBar_standing_area','dashboard/progressBar',3000); </script>
							<div class="progress no-m progress-termometer" id="progressBar_standing_area">
								<?php //echo $this->dashboard->progressBar(); ?>
							</div>
						</div>	







 


 

						<!-- ************************ -->
 

						<!-- ************************ -->
						
				 

<!-- 						<div class="col-md-6 col-lg-3 col-sm-6 col-xs-12 box-widget pad-10 hide">
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
						</div> -->

						<!-- ************************ -->
						
						<div class="clearfix"></div>




					 
 



						<!-- ************************ -->

 

						<!-- ************************ -->




						<!-- ************************ -->
						
						<div class="clearfix"></div>

						<!-- ************************ -->


						<!-- ************************ -->
 
 
<!-- 

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
 -->
 




						<div class="clearfix"></div>


						<!-- ************************ -->



						<!-- ************************ -->


 






						<div class="clearfix"></div>


						

						<!-- ************   LEAVE CHART   ************ -->

<?php /*

						<div id="" class="hide hidden">
							
							<?php 

							$q_leave_types = $this->user_model->fetch_leave_type();
							$leave_types = $q_leave_types->result();

							$added_data = new StdClass();
							$added_data->{"leave_type_id"} = '0';
							$added_data->{"leave_type"} = 'Philippines Public Holiday';
							$added_data->{"remarks"} = '';


							array_push($leave_types, $added_data);



							$leave_totals =  $this->dashboard->get_count_per_week(2,'',$user_id);
							$last_year_leave = $this->dashboard->get_count_per_week(2,$last_year,$user_id);
			$custom_q = " AND `users`.`user_department_id` = '10' ";

							?>
						</div>

						<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 box-widget pad-10">
							<div class="widget wid-type-0 widg-head-styled">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide hidden"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head box-widg-head fill  pad-5">
									<strong>Employee Leave Chart : <?php echo date('Y'); ?></strong>


									<select class="pull-right input-control input-sm chart_data_selection_emps" style="background:#AAAAAA; padding: 0;margin: -8px 0 0 0;width: 130px;height: 35px; border-radius: 0;border: 0;border-bottom: 1px solid #999999;">
										
										<?php 
											$user_list_q = $this->user_model->list_user_short($custom_q);
											$user_list= $user_list_q->result();
										?>

										<?php foreach ($user_list as $key => $value): ?> 
												<option class="emp_opy_select"  value="<?php echo $value->user_first_name.' '.$value->user_last_name.'|'.$value->primary_user_id; ?>" ><?php echo $value->user_first_name.' '.$value->user_last_name; ?></option>
										<?php endforeach; ?>
									</select>

							
								</div>

<?php $color_leave_type = array('#A7184B','#0092CE','#3D00A4','#FE2712','#FD5309','#0047FE','#8600AF');  ?>


<?php 
$leave_type_list = array();

$leave_type_list[1] = 'Annual Leave';
$leave_type_list[5] = 'Unpaid Leave';
$leave_type_list[2] = 'Personal (Sick Leave)';
$leave_type_list[6] = 'RDO (Rostered Day Off)';
$leave_type_list[0] = 'Philippines Public Holiday';
$leave_type_list[3] = 'Personal (Carers Leave)';
$leave_type_list[4] = 'Personal (Comp. Leave)';


?>



								<div class="box-area clearfix row pad-right-10 pad-left-10 pad-bottom-10">									
									<div class="widg-content col-md-12 col-xs-12 clearfix">
										<div class="chart_main_leave_loading_chart" style="height: 457px; text-align: center; padding: 100px 53px; color: #ccc;"><i class="fa fa-spin fa-refresh fa-4x"></i></div>
										<div class id=" pad-bottom-10 ">
											
											<div id="chart_main_leave"  ></div>

											<div id="" class="" style="margin: -6px -26px 17px 53px;    display: block;    clear: both;    font-size: 10px;">
												<?php foreach ($months as $key => $value): ?>
													<div id="" class="mos"><?php echo $value; ?></div>
												<?php endforeach; ?>
											</div>

											<script type="text/javascript"> var default_totals_c = ''; var default_totals_o = ''; </script>

											<div id="" class="clearfix" style="margin: 20px 10px 0px;">
												<?php $current_total_leaves = ''; $previou_total_leaves= ''; ?>


												<?php foreach ($leave_type_list as $leave_data_id => $leave_name_value): ?>
													<div class="" style="padding:2px; float:left; display:block; width: 14%;  background: <?php echo $color_leave_type[$leave_data_id];  ?>;">
														<p class="pointer leave_type_selection tooltip-enabled type_label_<?php echo $leave_data_id; ?>" title="" data-html="true" data-placement="top" data-original-title="Total Applied: <?php echo $leave_totals[$leave_data_id]; ?><br />Last Year:  <?php echo $last_year_leave[$leave_data_id]; ?>" id="<?php echo $leave_name_value; ?>" style="color: #fff;   font-size: 12px;  text-align: center;"><?php echo $leave_name_value; ?></p>
													</div>
												<?php endforeach; ?>
											</div>

											<style type="text/css">.mos {    float: left;    width: 8.1%;    text-align: center;}</style>
										</div> 	
									</div>
								</div>

							</div>
						</div>


						<script type="text/javascript">


var chart_emply = c3.generate({
	size: {
		height: 340

	},data: {
		x : 'x',
		columns: [
          ['x',  // months labels

          <?php 

          for($i=1; $i <53 ; $i++){


					if($i > 2){

          				echo "'".$i."',";
					}
          }


          ?> ], // months labels


          <?php 

          echo $this->dashboard->get_count_per_week(1,'', $user_id);

           ?>


		<?php 
 
		 
	


		$user_list_q = $this->user_model->list_user_short($custom_q);
		$user_list= $user_list_q->result();
 
	?>


],
selection: {enabled: true},
type: 'bar', 
 
colors: {
	//*'Average': '#FF7F0E',
	//'Current': '#2CA02C',
	//'Last Year': '#9467BD',

	<?php foreach ($leave_types as $leave_data): ?>
		'<?php echo $this->session->userdata("user_first_name")." ".$this->session->userdata("user_last_name")." ".$leave_data->leave_type; ?>': '<?php echo $color_leave_type[$leave_data->leave_type_id];  ?>',
	<?php endforeach; ?>

	
	<?php foreach ($user_list as $key => $value): ?> 
		<?php foreach ($leave_types as $leave_data): ?>
		'<?php echo $value->user_first_name.' '.$value->user_last_name." ".$leave_data->leave_type; ?>': '<?php echo $color_leave_type[$leave_data->leave_type_id]; ?>',
	<?php endforeach; ?>
<?php endforeach; ?>


        },

groups: [



[
	<?php foreach ($leave_types as $leave_data): ?>
		'<?php echo $this->session->userdata("user_first_name")." ".$this->session->userdata("user_last_name")." ".$leave_data->leave_type; ?>',
	<?php endforeach; ?>


	 
<?php foreach ($user_list as $key => $value): ?> 
	<?php foreach ($leave_types as $leave_data): ?>
		'<?php echo $value->user_first_name.' '.$value->user_last_name.' '.$leave_data->leave_type; ?>',
	<?php endforeach; ?>
<?php endforeach; ?>



],


],
order: null,
}, 
    
tooltip: {
        grouped: true // false // Default true
    },
    bindto: "#chart_main_leave",
    bar:{ width:{ ratio: 0.5 }},
    point:{ select:{ r: 6 }},
 onrendered: function () {

  $('.chart_main_leave_loading_chart').remove();



   },
//zoom: {enabled: true, rescale: true,extent: [1, 7]},
legend: { show: false },

axis: {x: {type: 'category', tick: {rotate: 0,multiline: false}, height: 0}, y: {       tick: {          format: d3.format('.2f')        }      } }, 

tooltip: {
	format: {
     title: function (x) { return 'Week '+(x+3); },
     value: function (value, ratio, id) {
               // var format = id === 'data1' ? d3.format(',') : d3.format('$');
               var format = d3.format('.2f');

               var mod_value =  value.toFixed(2); //Math.ceil(value,2)  // need to get 2 decimal places

           //    var mod_value_x = parseFloat(Math.round(mod_value * Math.pow(10, 2)) /Math.pow(10,2)).toFixed(2);

            // var mod_value_y =   d3.format('.2f')

            //   var mod_value = parseFloat(Math.round(value * 100) / 100).toFixed(2);
               //return '$ '+format(mod_value);
               return format(mod_value);
           }//
       } 

   }
});

chart_emply.hide();



$('select.chart_data_selection_emps').on("change", function(e) {

	$('#loading_modal').modal({"backdrop": "static", "show" : true} );

	var data = $(this).val();

	var current_total_leaves = $('p.current_total_leaves').text().split('|');
	var previou_total_leaves = $('p.previou_total_leaves').text().split('|');



	setTimeout(function(){


		chart_emply.hide(); 
		chart_emply.unselect();

		if(data == 'all'){
			setTimeout(function () { 
				chart_emply.show(['Overall Annual Leave','Overall Personal (Sick Leave)','Overall Personal (Carers Leave)','Overall Personal (Compassionate Leave)','Overall Unpaid Leave','Overall Philippines Public Holiday','Overall RDO (Rostered Day Off)']);
			}, 500);



			for (var i = 0; i < current_total_leaves.length; i++) {
				var data_thisYear = current_total_leaves[i].split('-');
				var data_lastYear = previou_total_leaves[i].split('-');
				$("p.type_label_"+data_thisYear[0]).attr('data-original-title', "Total Applied: "+data_thisYear[1]+"<br />Last Year: "+data_lastYear[1]);
			}




		}else if(data == 'grouped'){
			setTimeout(function () {
				chart_emply.show();
				chart_emply.hide(['Overall Annual Leave','Overall Personal (Sick Leave)','Overall Personal (Carers Leave)','Overall Personal (Compassionate Leave)','Overall Unpaid Leave','Overall Philippines Public Holiday','Overall RDO (Rostered Day Off)']);
			}, 500);


			for (var i = 0; i < current_total_leaves.length; i++) {
				var data_thisYear = current_total_leaves[i].split('-');
				var data_lastYear = previou_total_leaves[i].split('-');
				$("p.type_label_"+data_thisYear[0]).attr('data-original-title', "Total Applied: "+data_thisYear[1]+"<br />Last Year: "+data_lastYear[1]);
			}



		}else{

			var user_data_selected = data.split('|');

//alert(user_data_selected[1]);
			setTimeout(function () {
//get_count_per_week($return_total = 0, $set_year = '', $set_emp_id = '' )

				$.ajax({
					'url' : base_url+'dashboard/get_count_per_week/2/<?php echo $current_year; ?>/'+user_data_selected[1],
					'type' : 'GET',
					'success' : function(dataValCurr){
						var data_arr_curr_dataVal = dataValCurr.split('|');
						
						$.ajax({
							'url' : base_url+'dashboard/get_count_per_week/2/<?php echo $last_year; ?>/'+user_data_selected[1],
							'type' : 'GET',
							'success' : function(dataVal){

								var data_arr_dataVal = dataVal.split('|');

								for (var i = 0; i < data_arr_curr_dataVal.length; i++) {

									var data_thisYear = data_arr_curr_dataVal[i].split('-');
									var data_lastYear = data_arr_dataVal[i].split('-');

									//alert(data_thisYear[0]+' **** '+data_thisYear[1]);
									//alert(data_lastYear[0]+' **** '+data_lastYear[1]);

									$("p.type_label_"+data_thisYear[0]).attr('data-original-title', "Total Applied: "+data_thisYear[1]+"<br />Last Year: "+data_lastYear[1]);
								}

							}
						});
					}
				});



				chart_emply.show([user_data_selected[0]+' Annual Leave',user_data_selected[0]+' Personal (Sick Leave)',user_data_selected[0]+' Personal (Carers Leave)',user_data_selected[0]+' Personal (Compassionate Leave)',user_data_selected[0]+' Unpaid Leave',user_data_selected[0]+' Philippines Public Holiday',user_data_selected[0]+' RDO (Rostered Day Off)']);
			}, 500);
		}

	//	chart_emply.select();

	 
	},500);	

	setTimeout(function(){
		$('#loading_modal').modal('hide');
	},5000);	

	});


$('.leave_type_selection').click(function(){
	$('#loading_modal').modal({"backdrop": "static", "show" : true} );
	var leave_type = $(this).attr('id');

	//$('select.chart_data_selection_emps').val('grouped');

	setTimeout(function () {
		chart_emply.hide(); 
		chart_emply.unselect();
	}, 500);
 
	setTimeout(function () {
		chart_emply.show([

			<?php foreach ($user_list as $key => $value): ?>  
				'<?php echo $value->user_first_name.' '.$value->user_last_name.' '; ?>'+leave_type,
			<?php endforeach; ?> 

		]);
	}, 1000);

	setTimeout(function(){
		//chart_emply.select();
		$('#loading_modal').modal('hide');
	},3000);

});


</script>

*/ ?>

						<!-- ************************ -->

						<!-- ************   LEAVE CHART   ************ -->


						<!-- ************************ -->
						
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


// dashboard->sales_widget()
 	
    	




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









var donutg = c3.generate({
	size: {
		height: 250,
		width: 250
	},data: {
		columns: [ <?php echo $this->dashboard->focus_projects_by_type_widget(1); ?> ],
		type : 'pie',
		onclick: function (d, i) { console.log("onclick", d, i); },
		onmouseover: function (d, i) { console.log("onmouseover", d, i); },
		onmouseout: function (d, i) { console.log("onmouseout", d, i); }
	},
	legend: {
		show: false //hides label
	},
	bindto: "#donut_prj_bt",
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


</script>
 
<style type="text/css">.progress-termometer .tooltip{ display: none !important; visibility: hidden !important; }</style>

<?php 

if($pm_setter != '' && isset($pm_setter)){
	$pm_name = $pm_setter;
	echo '<script type="text/javascript">$("span#simulation_pm_name").text("'.$pm_name.'");</script>';
}else{
	$pm_name = $this->session->userdata('user_first_name').' '.$this->session->userdata('user_last_name'); 
}

?>


<script type="text/javascript">


	setTimeout(function() {
		$("select.chart_data_selection_emps").val('<?php echo $pm_name; ?>|<?php echo $user_id; ?>');


		chart_emply.show(['<?php echo $pm_name; ?> Annual Leave','<?php echo $pm_name; ?> Personal (Sick Leave)','<?php echo $pm_name; ?> Personal (Carers Leave)','<?php echo $pm_name; ?> Personal (Compassionate Leave)','<?php echo $pm_name; ?> Unpaid Leave','<?php echo $pm_name; ?> Philippines Public Holiday','<?php echo $pm_name; ?> RDO (Rostered Day Off)']);


	}, 10000);





</script>




<!-- maps api js -->

<script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyDs1g6kHxbVrkQe7e_CmR6MsfV_3LmLSlc"></script>

<script type="text/javascript">
	var data = { "locations": <?php echo $this->dashboard->focus_get_map_locations(); ?>};	
	var emp_data = { "locations": <?php echo $this->dashboard->emp_get_locations_points(); ?>};
</script>

<script type="text/javascript" src="<?php echo base_url(); ?>js/maps/markerclusterer_packed.js"></script>

<!--[if IE]><script type="text/javascript" src="<?php echo base_url(); ?>js/excanvas.js"></script><![endif]-->
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.knob.min.js"></script>
<script type="text/javascript">$('knob').trigger('configure', {width:100}); </script>

<!-- maps api js -->



<script type="text/javascript" src="<?php echo base_url(); ?>js/maps/maps.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/maps/employee_map.js"></script>

