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
					 
					<div class='col-xs-6'>
						<div class="form-group">
							<div class='input-group date' id='datetimepicker6'>
								<input type='text' class="form-control time_ave_a" placeholder="DD-MM-YYYY HH:MM"/>
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
						</div>
					</div>
					<div class='col-xs-6'>
						<div class="form-group">
							<div class='input-group date' id='datetimepicker7'>
								<input type='text' class="form-control time_ave_b" placeholder="DD-MM-YYYY HH:MM"/>
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
						</div>
					</div>

					<div class='col-xs-12'>
						<div class="form-group">
							<div  >
								<textarea class="form-control ave_notes" id="ave_notes" name="ave_notes" placeholder="Comments"></textarea>
							</div>
						</div>
					</div> 
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" name="submit_ave" class="btn btn-primary submit_ave">Submit</button>
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
					 
					<div class='col-xs-6'>
						<div class="form-group">
							<div class='input-group date' id='datetimepicker8'>
								<input type='text' class="form-control time_ave_a_up" placeholder="DD-MM-YYYY HH:MM"/>
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
						</div>
					</div>
					<div class='col-xs-6'>
						<div class="form-group">
							<div class='input-group date' id='datetimepicker9'>
								<input type='text' class="form-control time_ave_b_up" placeholder="DD-MM-YYYY HH:MM"/>
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
						</div>
					</div>

					<div class='col-xs-12'>
						<div class="form-group">
							<div  >
								<textarea class="form-control ave_notes_up" id="ave_notes" name="ave_notes" placeholder="Comments"></textarea>
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







<script type="text/javascript">
    $(function () {
    	$('#datetimepicker6').datetimepicker({ format: 'DD/MM/YYYY hh:mm A'});
    	$('#datetimepicker7').datetimepicker({
           useCurrent: false, //Important! See issue #1075
           format: 'DD/MM/YYYY hh:mm A'
       });
    	$("#datetimepicker6").on("dp.change", function (e) {
    		$('#datetimepicker7').data("DateTimePicker").minDate(e.date);

    		$('#datetimepicker7').datetimepicker({
	           useCurrent: false, //Important! See issue #1075
	           format: 'DD/MM/YYYY hh:mm A'
	       });

    	});
    	$("#datetimepicker7").on("dp.change", function (e) {
    		$('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
    	}); 



        $('#datetimepicker8').datetimepicker({ format: 'DD/MM/YYYY hh:mm A'});
        $("#datetimepicker8").on("dp.change", function (e) {
            $('#datetimepicker9').data("DateTimePicker").minDate(e.date);

	        $('#datetimepicker9').datetimepicker({
	            useCurrent: false, //Important! See issue #1075
	             format: 'DD/MM/YYYY hh:mm A'
	        });
        });
        $("#datetimepicker9").on("dp.change", function (e) {
            $('#datetimepicker8').data("DateTimePicker").maxDate(e.date);
        });
    });
</script>






<?php $this->load->view('assets/right-sidebar-prj-commnts'); ?>
<?php $this->load->view('assets/whos-logged-in'); ?>