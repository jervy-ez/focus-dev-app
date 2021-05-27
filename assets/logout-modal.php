<div id="logout" class="modal fade" tabindex="-1" data-width="760" style="display: none; overflow: hidden;">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">We are starting to miss you</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<h4>Are you sure to sign out????</h4>
						<p>Signing out will clear your session and drops you back to the login screen.</p>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<form method="post" role="form" action="<?php echo base_url().'users/logout' ?>">
					<input type="hidden" value="1" name="logout" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button type="submit" name="logout-submit" class="btn btn-danger">Sign me out now</button>
				</form>


			</div>
		</div>
	</div>
</div>




<div id="wip_project_review" class="modal fade" tabindex="-1" data-width="760" style="display: none; overflow: hidden;">
	<div class="modal-dialog">
		
<div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">Projects Report Filters</h4>
      </div>
      <div class="modal-body">
      	

      	

      	<div class="input-group m-bottom-10">
      		<span class="input-group-addon" id="">
      			<i class="fa fa-user"></i>
      		</span>
      		<select class="form-control select_pm_prj_rvw m-bottom-10">
      			<option value="" disabled="true" selected="true">Select Project Manager</option>

      			<?php
      			$project_manager_q = $this->user_model->fetch_user_by_role(3);
      			$project_manager = $project_manager_q->result();     foreach ($project_manager as $row){    
      				echo '<option value="'.$row->user_first_name.' '.$row->user_last_name.'|'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.'</option>';
      			}?>

      			<?php
      			$project_manager_q = $this->user_model->fetch_user_by_role(20);
      			$project_manager = $project_manager_q->result();     foreach ($project_manager as $row){    
      				echo '<option value="'.$row->user_first_name.' '.$row->user_last_name.'|'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.'</option>';
      			}?>

      		</select>
      	</div>



      	
 
        

      	<div class="clearfix">
      		<button type="button" class="btn btn-primary process_prj_wip_revw pull-right" id="">Submit</button> <!-- data-dismiss="modal" -->
      		<button type="button" class="btn btn-default pull-right m-right-10" data-dismiss="modal">Cancel</button> 
      	</div>

      	<script type="text/javascript">


      		$('.process_prj_wip_revw').click(function(){

      			var pm_selected = $('select.select_pm_prj_rvw').val();
 

      			if (pm_selected === undefined || pm_selected === null) {
      					$('select.select_pm_prj_rvw').parent().addClass('has-error');

      			}else{
  					$('select.select_pm_prj_rvw').parent().removeClass('has-error');
  					$('#loading_modal').modal({"backdrop": "static", "show" : true} );

  					setTimeout(function(){ 
  						$('#loading_modal').modal('hide');




  						var url = '<?php echo base_url(); ?>';
  						var myWindow = window.open(url+'reports/process_wip_review?pm_id='+pm_selected, '_blank');
  						myWindow.blur();
  						//document.getElementById("txturl").value = '';



  					},1000);

      			}


      		});

      	</script>


      </div>
    </div>


	</div>
</div>











<div id="set_availability" class="modal fade" tabindex="-1" data-width="760" style="display: none; overflow: hidden;">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Set Availability Details : <span class="ave_type"></span></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					 
					<div class='col-xs-4'>
						<div class="form-group">
							<div class='input-group date' id='datetimepicker5'>
								<input type='text' class="form-control day_set_non_rec" placeholder="Date" value="<?php echo date("d/m/Y"); ?>" />
								<span class="input-group-addon">
									Day <span class="fa fa-calendar fa-lg"></span>
								</span>
							</div>
						</div>
					</div>
					 
					<div class='col-xs-4'>
						<div class="form-group">
							<div class='input-group date' id='datetimepicker6'>
								<input type='text' class="form-control time_ave_a" placeholder="Start"/>
								<span class="input-group-addon">
									Start <span class="fa fa-clock-o fa-lg"></span>
								</span>
							</div>
						</div>
					</div>

					<div class='col-xs-4'>
						<div class="form-group">
							<div class='input-group date' id='datetimepicker7'>
								<input type='text' class="form-control time_ave_b" placeholder="End"/>
								<span class="input-group-addon">
									End <span class="fa fa-clock-o fa-lg"></span>
								</span>
							</div>
						</div>
					</div>


					<div class='col-xs-12'>
						<div class="">
							<div>
								<textarea class="form-control ave_notes" id="ave_notes" name="ave_notes" placeholder="Comments"></textarea>
							</div>
						</div>
					</div> 

					
					<div id="" class="clearfix"></div>
					<div class='col-xs-8 hide'>
						<div class="form-group m-top-15">
							<div class='input-group' id=''>
								<select class="form-control aus_timezone tz_set_a">
									<option value="1" selected >Aus Western Standard Time</option>
									<option value="2">Aus Eastern Standard Time</option> 
								</select>
								<span class="input-group-addon">Time Zone <span class="fa fa-globe fa-lg"></span></span>
							</div>
						</div>
					</div>
					<div id="" class="clearfix"></div>


				</div>
			</div>
			<div class="modal-footer">


						<button class="btn btn-info set_full_day pull-left">Full Day</button>
					


				<button type="button" class="btn btn-default m-right-10" data-dismiss="modal">Cancel</button>

				<div class="btn-group pull-right">
				<button type="button" name="submit_ave" class="btn btn-primary submit_ave">Submit</button>


					<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<span class="caret"></span>
						<span class="sr-only">Toggle Dropdown</span>
					</button>
					<ul class="dropdown-menu" role="menu" style="display: none;">
						<li><a class="set_reoccurrence pointer"><i class="fa fa-refresh" aria-hidden="true"></i> Set Reoccurrence</a></li>
					</ul>
				</div>


			</div>
		</div>
	</div>
</div>



<div id="update_availability" class="modal fade" tabindex="-1" data-width="760" style="display: none; overflow: hidden;">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Set Availability Details : <span class="ave_type_up"></span></h4>
			</div>
			 

			<div class="modal-body">
				<div class="row">
					 
					<div class='col-xs-4'>
						<div class="form-group">
							<div class='input-group date' id='datetimepicker10'>
								<input type='text' class="form-control time_day_set_non_rec" placeholder="Date" value="<?php echo date("d/m/Y"); ?>" />
								<span class="input-group-addon">
									Day <span class="fa fa-calendar fa-lg"></span>
								</span>
							</div>
						</div>
					</div>
					 
					<div class='col-xs-4'>
						<div class="form-group">
							<div class='input-group date' id='datetimepicker8'>
								<input type='text' class="form-control time_ave_a_up" placeholder="Start"/>
								<span class="input-group-addon">
									Start <span class="fa fa-clock-o fa-lg"></span>
								</span>
							</div>
						</div>
					</div>

					<div class='col-xs-4'>
						<div class="form-group">
							<div class='input-group date' id='datetimepicker9'>
								<input type='text' class="form-control time_ave_b_up" placeholder="End"/>
								<span class="input-group-addon">
									End <span class="fa fa-clock-o fa-lg"></span>
								</span>
							</div>
						</div>
					</div>


					<div class='col-xs-12'>
						<div class="">
							<div>
								<textarea class="form-control ave_notes_up" id="ave_notes_up" name="ave_notes" placeholder="Comments"></textarea>
							</div>
						</div>
					</div> 

				</div>
			</div>



			<input type="hidden" id="ava_id_data">
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" name="update_ave" class="btn btn-success update_ave">Update</button>
			</div>
		</div>
	</div>
</div>








<div id="setting_reoccurrence" class="modal fade" tabindex="-1" data-width="860" style="display: none; overflow: hidden;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Reoccurrence Setup</h4>
			</div>
			<div class="modal-body">
				<div class="row">

				<div id="" class="col-md-9">

				<p><strong>Appointment Time</strong></p>
					 
					<div class='col-xs-6'>
						<div class="form-group">
							<div class='input-group date' id='time_picker_1'>
								<span class="input-group-addon">Start Time</span>
								<input type='text' class="form-control appointment_time_a" placeholder="HH:MM"/>
								<span class="input-group-addon">
									<i class="fa fa-clock-o fa-lg" aria-hidden="true"></i>
								</span>
							</div>
						</div>
					</div>
					<div class='col-xs-6'>
						<div class="form-group">
							<div class='input-group date' id='time_picker_2'>
								<span class="input-group-addon">End Time</span>
								<input type='text' class="form-control appointment_time_b" placeholder="HH:MM"/>
								<span class="input-group-addon">
									<i class="fa fa-clock-o fa-lg" aria-hidden="true"></i>
								</span>
							</div>
						</div>
					</div>



					<div id="" class="clearfix"></div>
					<div class='col-xs-8'>
						<div class="form-group m-top-5">
							<div class='input-group' id=''>
								<select class="form-control aus_timezone tz_set_b">
									<option value="0" selected>Select Timezone</option>
									<option value="1">Aus Western Standard Time</option>
									<option value="2">Aus Eastern Standard Time</option> 
								</select>
								<span class="input-group-addon">Time Zone <span class="fa fa-globe fa-lg"></span></span>
							</div>
						</div>
					</div>
					<div id="" class="clearfix"></div>


				<p><strong>Reoccurrence Pattern</strong></p>



				<div class="bs-example bs-example-tabs" data-example-id="togglable-tabs">
					<ul class="nav nav-tabs" id="myTabs" role="tablist">
						<li class="active"><a href="#daily_p" data-toggle="tab" class="pattern_reoc" id="daily" >Daily</a></li>
						<li class=""><a href="#weekly_p" data-toggle="tab" class="pattern_reoc" id="weekly" >Weekly</a></li>
						<li class=""><a href="#monthly_p" data-toggle="tab" class="pattern_reoc" id="monthly" >Monthly</a></li>
						<li class=""><a href="#yearly_p" data-toggle="tab" class="pattern_reoc" id="yearly" >Yearly</a></li>

					</ul>

					<div class="tab-content m-top-10" id="myTabContent">
						<div class="tab-pane fade active in" id="daily_p" >							
							<button class="btn btn-default rep_btn_cstm" id="sun_d">Sun</button>
							<button class="btn btn-default rep_btn_cstm" id="mon_d">Mon</button>
							<button class="btn btn-default rep_btn_cstm" id="tue_d">Tue</button>
							<button class="btn btn-default rep_btn_cstm" id="wed_d">Wed</button>
							<button class="btn btn-default rep_btn_cstm" id="thu_d">Thu</button>
							<button class="btn btn-default rep_btn_cstm" id="fri_d">Fri</button>
							<button class="btn btn-default rep_btn_cstm" id="sat_d">Sat</button>
						</div>
						<div class="tab-pane fade" id="weekly_p" >
							<p>Recur every <input type="text" style="width: 30px;" value="1" class="pad-3 recur_every_week_val"> week(s) on:</p>
							<button class="btn btn-default rep_btn_cstm" id="sun_w">Sun</button>
							<button class="btn btn-default rep_btn_cstm" id="mon_w">Mon</button>
							<button class="btn btn-default rep_btn_cstm" id="tue_w">Tue</button>
							<button class="btn btn-default rep_btn_cstm" id="wed_w">Wed</button>
							<button class="btn btn-default rep_btn_cstm" id="thu_w">Thu</button>
							<button class="btn btn-default rep_btn_cstm" id="fri_w">Fri</button>
							<button class="btn btn-default rep_btn_cstm" id="sat_w">Sat</button>
						</div>
						<div class="tab-pane fade" id="monthly_p" >							
							<p>Day <input type="text" style="width: 30px;" value="27" class="pad-3 pattern_of_monthly_day"> of every <input type="text" style="width: 30px;" value="1" class="pad-3 recur_every_month_val"> month(s)</p>
						</div>
						<div class="tab-pane fade" id="yearly_p" >
							<p>Every
							<select style="padding: 6px 3px;" class="pattern_of_yearly_month">
								<option value="01">Jan</option>
								<option value="02">Feb</option>
								<option value="03">Mar</option>
								<option value="04">Apr</option>
								<option value="05">May</option>
								<option value="06">Jun</option>
								<option value="07">Jul</option>
								<option value="08">Aug</option>
								<option value="09">Sep</option>
								<option value="10">Oct</option>
								<option value="11">Nov</option>
								<option value="12">Dec</option>
							</select>
							day of <input type="text" style="width: 30px;" value="1" class="pad-3 pattern_of_yearly_day"></p>
						</div>
						<div id="" class="clearfix"></div>
					</div>

				</div>

				<p><hr /></p>

				<div id="" class="clearfix"></div>

				<p><strong>Range of Reoccurrence</strong></p>


					<div class='col-xs-6'>
						<div class="form-group tooltip-enabled" data-html="true" data-placement="top" data-original-title="The first occurence will commence at the indicated<br />start date." >
							<div class='input-group date' id='range_datetime_picker_1'>
								<span class="input-group-addon">Start Date</span>
								<input type='text' class="form-control range_datetime_picker_1" placeholder="DD/MM/YYYY"/>
								<span class="input-group-addon">
								<i class="fa fa-calendar-o fa-lg" aria-hidden="true"></i>
								</span>
							</div>
						</div>
					</div>
					<div class='col-xs-6'>
						<div class="form-group">
							<div class='input-group date' id='range_datetime_picker_2'>
								<span class="input-group-addon">End Date</span>
								<input type='text' class="form-control range_datetime_picker_2" disabled placeholder="DD/MM/YYYY"/ value="">
								<span class="input-group-addon">
								<i class="fa fa-calendar-o fa-lg" aria-hidden="true"></i>
								</span>
							</div>
						</div>
					</div>

					<div id="" class="col-xs-6">
						<div class="checkbox">
							<label><input type="checkbox" checked class="no_end_occur">No End Date</label>
						</div>
					</div>
					</div>
					
					<div id="" class="col-md-3">
						<div id="" class="pad-left-15" style="border-left:1px solid #DDDDDD;">
							<p><strong>Summary</strong></p>
							<p>Status: <strong><span id="summ_status_text"></span></strong></p>
							<p>Starting Date: <strong><span id="summ_starting_date"></span></strong></p>
							<p>End Date: <strong><span id="summ_end_date">No End</span></strong></p>
							<p>Time: <strong><span id="summ_time"></span></strong></p>

							<hr />

							<p>Note: <strong>The availability will commence at the selected "Starting Date".</strong><br /><br /><em>To change the Start Date, located at the "Range of Reoccurrence" change the Start Date.</em></p>





							<p>&nbsp;</p>
						</div>
					</div>

				</div>
			</div>
			<input type="hidden" id="ava_id_data">
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" name="" class="btn btn-success reoccur_submit_now">Submit</button>
			</div>
		</div>
	</div>
</div>









<script type="text/javascript">
    $(function () {

    	var currentdate = new Date();
  		var set_month = (currentdate.getMonth()+1) < 10 ? '0' + (currentdate.getMonth()+1) : (currentdate.getMonth()+1);
    	var current_date_set = currentdate.getDate() + "/" + set_month + "/" + currentdate.getFullYear();


    	$('#datetimepicker5').datetimepicker({ format: 'DD/MM/YYYY'});

    	$('#datetimepicker6').datetimepicker({ format: 'hh:mm A'});
    	$('#datetimepicker7').datetimepicker({
           useCurrent: false, //Important! See issue #1075
           format: 'hh:mm A'
       });
    	$("#datetimepicker6").on("dp.change", function (e) {
    		$('#datetimepicker7').data("DateTimePicker").minDate(e.date);

    		$('#datetimepicker7').datetimepicker({
	           useCurrent: false, //Important! See issue #1075
	           format: 'hh:mm A'
	       });

    	});
    	$("#datetimepicker7").on("dp.change", function (e) {
    		$('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
    	});




    	$('#time_picker_1').datetimepicker({ format: 'hh:mm A'});
    	$('#time_picker_2').datetimepicker({
           useCurrent: false, //Important! See issue #1075
           format: 'hh:mm A'
       });
    	$("#time_picker_1").on("dp.change", function (e) {
    		$('#time_picker_2').data("DateTimePicker").minDate(e.date);

    		$('#time_picker_2').datetimepicker({
	           useCurrent: false, //Important! See issue #1075
	           format: 'hh:mm A'
	       });

    		var time_a = e.date.format('hh:mm A');
    		var time_b = $('input.appointment_time_b').val();

    		$('#summ_time').text( time_a+' - '+time_b );

    	});
    	$("#time_picker_2").on("dp.change", function (e) {
    		$('#time_picker_1').data("DateTimePicker").maxDate(e.date);


    		var time_a = $('input.appointment_time_a').val();
    		var time_b = e.date.format('hh:mm A');

    		$('#summ_time').text( time_a+' - '+time_b );
    	});






    	$('#range_datetime_picker_1').datetimepicker({ format: 'DD/MM/YYYY'});
    	$('#range_datetime_picker_2').datetimepicker({
           useCurrent: false, //Important! See issue #1075
           format: 'DD/MM/YYYY'
       });
    	$("#range_datetime_picker_1").on("dp.change", function (e) {
    		$('#range_datetime_picker_2').data("DateTimePicker").minDate(e.date);

    		$('#range_datetime_picker_2').datetimepicker({
	           useCurrent: false, //Important! See issue #1075
	           format: 'DD/MM/YYYY'
	       });
 
    	$('#summ_starting_date').text( e.date.format('DD/MM/YYYY') );

    	});
    	$("#range_datetime_picker_2").on("dp.change", function (e) {
    		$(this).data("DateTimePicker").minDate(e.date);
    		$('#range_datetime_picker_1').data("DateTimePicker").maxDate(e.date);
    		$('#summ_end_date').text( e.date.format('DD/MM/YYYY') );
    	});






    	$('#datetimepicker10').datetimepicker({ format: 'DD/MM/YYYY'});

    	$('#datetimepicker8').datetimepicker({ format: 'hh:mm A'});
    	$('#datetimepicker9').datetimepicker({
           useCurrent: false, //Important! See issue #1075
           format: 'hh:mm A'
       });
    	$("#datetimepicker8").on("dp.change", function (e) {
    		$('#datetimepicker9').data("DateTimePicker").minDate(e.date);

    		$('#datetimepicker9').datetimepicker({
	           useCurrent: false, //Important! See issue #1075
	           format: 'hh:mm A'
	       });

    	});
    	$("#datetimepicker9").on("dp.change", function (e) {
    		$('#datetimepicker8').data("DateTimePicker").maxDate(e.date);
    	});


    });
</script>


<?php $curr_url_page = base_url(uri_string()); ?>



<?php if(  $this->router->fetch_class() == 'projects' ): ?>




	<?php 


	if (strpos($curr_url_page, '/add') !== false) {
		//echo 'true';
	}else{
		 $this->load->view('assets/right-sidebar-prj-commnts'); 
	}


	/*

	<?php if (strpos($curr_url_page , '/add') !== true    || strpos($curr_url_page , '/projects/view/') !== true ): ?>
		</div>

	<?php else: ?>

		<?php $this->load->view('assets/right-sidebar-prj-commnts'); ?>

	<?php endif; ?> */


 ?>

<?php endif; ?>












<style type="text/css">

.video_bg{
	background: url('<?php echo base_url(); ?>uploads/misc/screen_cast_thumb.png');
	color: #fff;
	background-position: center center;
	background-repeat: no-repeat;
	background-size: contain;
	aspect-ratio: 16/8;
	margin: 5px;
	padding: 10px;
	background-color: #000;
	cursor: pointer;
}


.video_bg:hover{
	background: url('<?php echo base_url(); ?>uploads/misc/screen_cast_thumb_hover.png');
	background-color: #000;
	background-position: center center;
	background-repeat: no-repeat;
	background-size: contain;

}

.video_box{
	padding: 0 !important;
	margin: 0 !important;
	padding: 5px;
}



  .video_bg:hover .video_title{
    opacity: 0.25;
  }



.video_title{ 

	font-size: 16px;
    bottom: 10%;
    position: absolute;
    padding: 5px 15px 5px 5px;


  }


/*
  .video_thumb{
    position: relative;
    display: block;
    width: 100%;
    height: 210px;

  }

  .video_thumb:hover .video_play{
    z-index: 100;
  }


  .video_thumb:hover .video_title{
    opacity: 0.5;
  }


.video_play, .video_title, .video_bg{
  position: absolute;
  color: #fff;
}

.video_title{
    z-index: 1;
    top:20%;
    left: 10%;
    padding: 10px;
    font-size: 16px;
}

.video_play{
  text-align: center;
  vertical-align: middle;
  font-size: 50px;
  width: 50px;
  left: 41%;
  top: 35%;
  cursor: pointer;
}
*/
</style>



<?php /*





        <div id="" class="m-10" >
        	<?php $cat_keyword = $this->uri->rsegment(1); $sub_cat_keyword = $this->uri->rsegment(2); ?>
        	<?php $this->help_videos->get_help_videos($cat_keyword,$sub_cat_keyword); ?>
        </div>


        */ ?>

<?php $this->load->view('assets/whos-logged-in'); ?>