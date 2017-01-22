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






<?php $this->load->view('assets/right-sidebar-prj-commnts'); ?>
<?php $this->load->view('assets/whos-logged-in'); ?>