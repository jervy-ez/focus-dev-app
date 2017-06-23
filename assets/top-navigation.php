<?php //if($this->session->userdata('logged_in')){ echo 'signin kna'; }else{ echo 'hindi pa';} 
	date_default_timezone_set("Australia/Perth");

	$this->load->module('users');
	$leave_type = $this->users->leave_type();
	$approval_count = $this->users->for_approval_count();
	$leave_remaining = $this->users->leave_remaining();
	$user_state = $this->users->user_state($this->session->userdata('user_id'));

	$this->load->module('admin'); 
	$notice_days_annual = $this->admin->get_notice_days('1');
?>

<div class="navbar navbar-inverse navbar-fixed-top top-nav" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand logo" href="<?php echo base_url(); ?>" ><em><i class="fa fa-tachometer"></i> Sojourn</em></a>
		</div>

		<div class="navbar-collapse collapse">
 

			<ul id="mobile_nav" class="nav navbar-nav navbar-left">
				<li>
					<a href="<?php echo base_url(); ?>company"> <i class="fa fa-users fa"></i> Company</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>projects"> <i class="fa fa-map-marker fa"></i> Projects</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>wip"> <i class="fa fa-tasks fa"></i> WIP</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>purchase_order"> <i class="fa fa-credit-card fa"></i> Purchase Orders</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>invoice"> <i class="fa fa-list-alt fa"></i> Invoice</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>contacts"> <i class="fa fa-phone-square fa"></i> Contacts</a>
				</li>		
				<?php if($this->session->userdata('users') > 0 || $this->session->userdata('is_admin') ==  1): ?>		
					<li>
						<a href="<?php echo base_url(); ?>users"> <i class="fa fa-users fa"></i> Users</a>
					</li>
				<?php endif; ?>
				 <?php if($this->session->userdata('is_admin') == 1 || $this->session->userdata('bulletin_board') >= 1 ): ?>
				<li>
					<a href="<?php echo base_url(); ?>bulletin_board"> <i class="fa fa-newspaper-o fa" id="bulletin_board_lbl_sb"></i> Bulletin Board</a>
				</li>
				<?php endif; ?>
				<li>
					<a href="#" class="currently_logged_user"> <i class="fa fa-sign-in fa" ></i> Currenlty Logged-in</a>
				</li>
				<li class="divider-menu"></li>
			</ul>

			<?php if($this->session->userdata('is_admin') == 1 ): ?>

				<ul class="nav navbar-nav navbar-left">
					<li>
						<a role="menuitem" tabindex="-1" href="<?php echo base_url(); ?>admin"><i class="fa fa-coffee"></i> Admin Defaults</a>
					</li>
				</ul>

			<?php endif; ?>




			<ul class="nav navbar-nav navbar-left">
				<li>
 
<input type="text" id="search_project_num" name="search_project_num" placeholder="seach project no" class="input-control input-xs tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="Seach by Project Number<br />Press the Enter button to submit" style="margin-top: 8px; border-radius: 4px; border: none; padding: 2px 6px;">
				 

				</li>
			</ul>






			<ul class="nav navbar-nav navbar-right">

				<?php if ($approval_count != 0):?>
					<li>
						<a href="<?php echo base_url().'users/leave_approvals/'.$this->session->userdata('user_id'); ?>" class="tooltip-test" data-placement="bottom" title="<?php echo $approval_count; ?> Pending Leave Request" style="padding: 8px 0 0;"><span class="badge btn btn-danger"> <span class="badge"><?php echo $approval_count ?></span></span></a>
					</li>
				<?php endif; ?>

<?php if($this->session->userdata('is_admin') == 1 || $this->session->userdata('user_role_id') == 16 || $this->session->userdata('is_admin') == 1 ): ?>

				<?php if (strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false): ?>
					
<?php if($this->session->userdata('is_admin') == 1 ): ?>					
					<li>
<a role="menuitem" data-toggle="modal" data-target="#view_dash" tabindex="-1" href="#"><span style="background: #fff; padding: 2px 8px; border-radius: 8px; color: #000000; text-shadow: none; margin-right: 5px;"> <span id="simulation_pm_name"></span> <i class="fa fa-eye-slash fa-lg" aria-hidden="true"></i> View</span></a>
					</li>
	<?php endif; ?>

				<?php endif; ?>
	<?php endif; ?>

				<li>
					<a role="menuitem"><i class="fa fa-quote-left" aria-hidden="true"></i> &nbsp;<em><?php echo $this->session->userdata('role_types'); ?></em>&nbsp; <i class="fa fa-quote-right" aria-hidden="true"></i></a>
				</li>


<!-- avv here -->	<li id="fat-menu" class="dropdown">
					<a href="#" id="drop3" role="button" class="dropdown-toggle ave_status_text" data-toggle="dropdown"><?php $this->users->get_user_availability($this->session->userdata('user_id')); ?> <b class="caret"></b></a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="drop3">
					
							<li role="">
								<a class="pointer set_ave_def" role="menuitem" tabindex="-1"  style="color: green;"><i class="fa fa-check-circle"></i> Available </a>
							</li>

							<li role="">
								<a class="pointer set_ave" role="menuitem" tabindex="-1" style="color: orange;" data-toggle="modal" data-target="#set_availability" tabindex="-1" href="#"><i class="fa fa-arrow-circle-left"></i> Out of Office </a>
							</li>

							<li role="">
								<a class="pointer set_ave" role="menuitem" tabindex="-1" style="color: red;" data-toggle="modal" data-target="#set_availability" tabindex="-1" href="#"><i class="fa fa-exclamation-circle"></i> Busy </a>
							</li>

							<li role="">
								<a class="pointer set_ave" role="menuitem" tabindex="-1" style="color: gray;" data-toggle="modal" data-target="#set_availability" tabindex="-1" href="#"><i class="fa fa-minus-circle"></i> Leave </a>
							</li>
							
							<li role="">
								<a class="pointer set_ave" role="menuitem" tabindex="-1" style="color: purple;" data-toggle="modal" data-target="#set_availability" tabindex="-1" href="#"><i class="fa fa-times-circle"></i> Sick </a>
							</li>
							
							
					</ul>
				</li>






				<li id="fat-menu" class="dropdown">
					<a href="#" id="drop3" role="button" class="dropdown-toggle tour-6" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo ucfirst($this->session->userdata('user_first_name')).' '.ucfirst($this->session->userdata('user_last_name')); ?> <b class="caret"></b></a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="drop3">
						<?php if($this->session->userdata('users') > 0 || $this->session->userdata('is_admin') ==  1): ?>
							<li role="presentation">
								<a role="menuitem" tabindex="-1" href="<?php echo base_url(); ?>users/account/<?php echo $this->session->userdata('user_id'); ?>"><i class="fa fa-cog"></i> My Account</a>
							</li>
						<?php endif; ?>


						<?php if( $this->session->userdata('is_admin') ==  1): ?>
							<li role="presentation">
								<a role="menuitem" tabindex="-1" href="<?php echo base_url(); ?>dev_notes"><i class="fa fa-pencil"></i> Dev Notes</a>
							</li>
                        				
                            <li>
                                <a role="menuitem" tabindex="-1" href="<?php echo base_url(); ?>dashboard/sales_forecast"><i class="fa fa-bar-chart"></i> Sales Forecast</a>
                            </li>
						<?php endif; ?>
							<li role="presentation">
								<a id="apply_for_leave" style="cursor: pointer;"><i class="fa fa-calendar-plus-o"></i> Apply for Leave</a>
							</li>
						
						<?php if($this->session->userdata('is_admin') == 1 || $this->session->userdata('user_role_id') == 16 || $this->session->userdata('is_admin') == 1 ): ?>
						<?php if (strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false): ?>

							<li><a role="menuitem" data-toggle="modal" data-target="#management_report" tabindex="-1" href="#"><i class="fa fa-file-text-o" aria-hidden="true"></i> Management Report</a></li>

						<?php endif; ?>
						<?php endif; ?>
						
						<li role="presentation" class="divider"></li>

						<li role="presentation">
							<a role="menuitem" data-toggle="modal" data-target="#logout" tabindex="-1" href="#"><i class="fa fa-sign-out"></i> Sign Out</a>
						</li>
					</ul>
				</li>
			</ul>
			
		</div><!--/.navbar-collapse -->
	</div>
</div>

<!-- Mike Coros #leave_modal start -->
<div class="modal fade bs-example-modal-md" id="leave_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-md" style="width: 630px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><label>Leave Request Form</label><span> (<a href="#" data-placement="right" class="popover-test" title="" data-content="Hello there mate! Welcome to the clients screen." data-original-title="Welcome">?</a>)</span><p>Fields having * is requred.</p>	</h4>
      </div>
	     <form class="form-horizontal" role="form">
	      <div class="modal-body pad-5">
	        <div class="box-area pad-5 clearfix">

				<div class="col-md-12 col-sm-12 col-xs-12 m-bottom-10 clearfix <?php if(form_error('leave_type')){ echo 'has-error has-feedback';} ?>">
					<label for="leave_type" class="col-sm-5 control-label">Leave Type*</label>
					<div class="col-sm-5">
						<select class="form-control chosen" id="leave_type" name="leave_type"  tabindex="4" >														
							<option value="">Choose a Type...</option>
							<?php
							foreach ($leave_type as $row){
								echo '<option value="'.$row->leave_type_id.'|'.$row->leave_type.'">'.$row->leave_type.'</option>';
							}?>
						</select>
						<?php if($this->input->post('leave_type')!=''): ?>
							<script type="text/javascript">$("select#leave_type").val("<?php echo $this->input->post('leave_type'); ?>");</script>
						<?php endif; ?>
					</div>					
				</div>

					<div class="col-sm-12 m-bottom-10 clearfix">
						<label for="total_leave" class="col-sm-5 control-label">Total Leave Remaining:</label>
						<label id="total_leave" class="col-sm-3 control-label text-left" name="total_leave" style="color: red; font-weight: bolder;"></label>
					</div>

					<div class="clearfix"></div>

					<div class="clearfix"></div>

					<div class="col-sm-12 m-bottom-10 clearfix">
						<label for="start_day_of_leave" class="col-sm-5 control-label">Start Day of Leave*:</label>
						<div class="col-sm-5">
							<div class="input-group date" id="start_day_datepicker">
								<input type="text" id="start_day_of_leave" class="form-control" onkeydown="return false;" name="start_day_of_leave" placeholder="Start Date" value="">
								<span class="input-group-addon">
									Day <span class="fa fa-calendar fa-lg"></span>
								</span>
							</div>
						</div>
					</div>

					<div class="clearfix"></div>

					<div class="col-sm-12 m-bottom-10 clearfix">
						<label for="end_day_of_leave" class="col-sm-5 control-label">End Day of Leave*:</label>
						<div class="col-sm-5">
							<div class="input-group date" id="end_day_datepicker">
								<input type="text" id="end_day_of_leave" class="form-control" onkeydown="return false;" name="end_day_of_leave" placeholder="End Date" value="">
								<span class="input-group-addon">
									Day <span class="fa fa-calendar fa-lg"></span>
								</span>
							</div>
						</div>
					</div>

					<div class="clearfix"></div>

					<div class="col-sm-12 m-bottom-10 clearfix">
						<label for="date_return" class="col-sm-5 control-label">Date Returning to Work:</label>
						<label id="date_return" class="col-sm-3 control-label text-left" name="date_return" style="color: green; font-weight: bolder;"></label>
					</div>
					
					<div class="col-sm-12 m-bottom-10 clearfix">
						<label for="leave_details" class="col-sm-5 control-label">Purpose*:</label>
						<div class="col-sm-5">
							<textarea class="form-control" id="leave_details" placeholder="Details" name="leave_details" rows="5" style="resize: vertical;"></textarea>
						</div>
					</div>

					<div class="col-sm-12 m-bottom-10 clearfix">
						<label for="total_days_away" class="col-sm-5 control-label">Total of Days Away:</label>
						<label id="total_days_away" class="col-sm-3 control-label text-left" name="total_days_away" style="color: red; font-weight: bolder;">0</label>
					</div>

					<div class="clearfix"></div>

					<div class="clearfix col-xs-12 text-center">
						<strong><p><i class="fa fa-quote-left"></i> This leave application is subject for approval<br> by your Direct Report &amp; the General Manager <i class="fa fa-quote-right"></i></p></strong>
					</div>

			</div><!-- ./box-area pad-10 clearfix -->
	      </div>
	      <div id="leave_modal_button" class="modal-footer m-top-10">
	      </div>
      	</form>
    </div>
  </div>
</div>
<!-- #leave_modal end -->

<!-- Modal -->
<div class="modal fade bs-example-modal-sm" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="margin-top: 120px; overflow: hidden;">
  <div class="modal-dialog modal-sm" style="width: 450px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Message Box</h4>
      </div>
      <div class="modal-body">
        <p id="confirmText">Are you sure you want to apply leave?</p>
      </div>
      <div id="confirmButtons" class="modal-footer"></div>
    </div>
  </div>
</div>

<div class="">
	<div class="modal fade" id="projects_search_result" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-body clearfix pad-10">
					<table id="" class="table table-striped">
						<thead>
							<tr>
								<th>Number</th>
								<th>Project Title</th>
							</tr>
						</thead>
						<tbody class="search_result_projects"></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>



	<div class="modal fade" id="view_dash" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">View Other Dashboard</h4>
				</div>
				<form method="post" >
					<div class="modal-body pad-10">

						<div id="" class=""><select id="view_other_dashboard" class="form-control m-bottom-10">
							<option selected value="">Select Project Manager</option>
							<?php foreach ($project_manager as $row){	
									if($row->user_id != 29){ echo '<option value="'.$row->user_id.'-pm">'.$row->user_first_name.' '.$row->user_last_name.'</option>'; }
							}?>
							<option value="29-mn">Maintenance Manager</option>
						</select></div>

					</div>
				</form>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		$('select#view_other_dashboard').on("change", function(e) {
			var data = $(this).val();
     		$('#view_dash').modal('hide');
			$('#loading_modal').modal({"backdrop": "static", "show" : true} );

			setTimeout(function(){
     			$('#loading_modal').modal('hide');
				window.open("?dash_view="+data);
			},2000);
		});
	</script>



<?php if($this->session->userdata('is_admin') == 1 || $this->session->userdata('user_role_id') == 16 || $this->session->userdata('is_admin') == 1 ): ?>
	<div class="modal fade" id="management_report" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Management Report Setup</h4>
				</div>
				<form method="post" >
					<div class="modal-body pad-10">

						<div id="" class=""><select id="management_report_year" class="form-control m-bottom-10">
							<option selected value="">Select Year</option>
							<?php
							for ($starting_count=2015; $starting_count <= date('Y'); $starting_count++) { 
								echo '<option value="'.$starting_count.'">'.$starting_count.'</option>';
							}
							?>

						</select></div>

						<div id="" class=""><select id="management_report_pm" class="form-control m-bottom-10">
							<option selected value="">Select Project Manager</option>
							<?php foreach ($project_manager as $row){							
									echo '<option value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.'</option>';
							}?>
						</select></div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<input type="button" class="btn btn-success set_management_report" data-dismiss="modal" value="Set Report">
					</div>
				</form>
			</div>
		</div>
	</div>
<?php endif; ?>

</div>

<!-- Mike Coros 04-06-17 -->
<script type="text/javascript">

	var leave_remaining = "";
	var current_year = new Date().getFullYear();
	var limit_year = new Date().getFullYear() + 5;
	var days = 0;
	var total_days = 0;
	var labour_day = "";
	var good_friday = "";
	var easter_monday = "";
	var start_day_store = "";
	var publicHolidays = "";
	var check_day = "";
	var count_days_approval = "";
	var minimum_date = new Date();
	var edit_leave_req_id = "";
	var user_state = "<?php echo $user_state->user_focus_company_id; ?>";
	
	$("#confirmModal").draggable({ handle: ".modal-header" });

	$("#leave_type").change(function() {
	  	var leave_type = $("#leave_type").val();
		leave_type_id = leave_type.substr(0, 1);

		switch (leave_type_id) {
		    case "1":
		        leave_remaining = "<?php echo ($leave_remaining) ? $leave_remaining->total_annual : '0'; ?>";
		        break;
		    case "2":
		    	leave_remaining = "<?php echo ($leave_remaining) ? $leave_remaining->total_personal : '0'; ?>";
		        break;
		    case "3":
		        leave_remaining = "<?php echo ($leave_remaining) ? $leave_remaining->total_personal : '0'; ?>";
		        break;
		    case "4":
		    	leave_remaining = "<?php echo ($leave_remaining) ? $leave_remaining->total_personal : '0'; ?>";
		        break;
		    case "5":
		    	leave_remaining = "-";
		        break;
		    default:
		    	leave_remaining = "0";
		        break;
		}

		$("#total_leave").text(leave_remaining);
	  	if ($("#start_day_of_leave").val() != "" || $("#end_day_of_leave").val() != ""){
	  		$("#start_day_of_leave").val("");				
			$("#end_day_of_leave").val("");
			$('#start_day_datepicker').data().DateTimePicker.date(null);
			$('#end_day_datepicker').data().DateTimePicker.date(null);
	  	}
	  	$("#date_return").text("");
	  	$("#total_days_away").text("");

	  	$('#end_day_datepicker').click(function() {
			if ($("#start_day_of_leave").val() == ""){
				//$("h4#myModalLabel.modal-title").text('Warning');
				$("#confirmText").text('Please select Start Day of Leave first.');
				$("#confirmButtons").html('<button type="button" class="btn btn-warning" data-dismiss="modal">Ok</button>');
				$("#confirmModal").modal('show');
				return false;
			}
		});
	});

	function editLeaveRequestbyID(id){
		
		$.ajax({
	        'url' : '<?php echo base_url().'users/edit_leave_req'; ?>',
	        'type' : 'POST',
	        'data' : { 'leave_req_id' : id },
	        'success' : function(data){
				result = data.split('|')

				edit_leave_req_id = result[0];
				var edit_leave_type_id = result[1];
				var edit_leave_type = result[2];
				var edit_start_day = result[3];
				var edit_end_day = result[4];
				var edit_date_return = result[5];
				var edit_purpose = result[6];
				var edit_total_days = result[7];

				var edit_leave_type_complete = edit_leave_type_id+"|"+edit_leave_type;

				switch (edit_leave_type_id) {
				    case "1":
				        leave_remaining = "<?php echo ($leave_remaining) ? $leave_remaining->total_annual : '0'; ?>";
				        break;
				    case "2":
				    	leave_remaining = "<?php echo ($leave_remaining) ? $leave_remaining->total_personal : '0'; ?>";			    	
				        break;
				    case "3":
				        leave_remaining = "<?php echo ($leave_remaining) ? $leave_remaining->total_personal : '0'; ?>";
				        break;
				    case "4":
				    	leave_remaining = "<?php echo ($leave_remaining) ? $leave_remaining->total_personal : '0'; ?>";
				        break;
				    case "5":
				    	leave_remaining = "-";
				        break;
				    default:
				    	leave_remaining = "0";
				        break;
				}

				$("#s2id_leave_type span.select2-chosen").text(edit_leave_type);
				$("#leave_type").val(edit_leave_type_complete);
				$("#total_leave").text(leave_remaining);
				$("#start_day_of_leave").val(edit_start_day);
				$("#end_day_of_leave").val(edit_end_day);
				$("#date_return").text(edit_date_return);
				$("#leave_details").val(edit_purpose);
				$("#total_days_away").text(edit_total_days);

				$("#leave_modal_button").html('<div class="pull-left">' +
								      		  '<button type="button" id="btnDeleteLeave" class="btn btn-danger">Delete</button>' +
								      		  '</div>' +
								      	      '<div class="pull-right">' +
									          '<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>' +
									          '<button type="button" id="btnUpdateLeaveReq" class="btn btn-success">Update Leave</button>' +
								       	      '</div>');
			    $("#leave_modal").modal('show');

			    if (user_state == 6){
			    	setTheCalendars("nsw");
			    } else {
			    	setTheCalendars("wa");
			    }

				$("#btnDeleteLeave").click(function(){

					//window.location = "<?php //echo base_url().'users/inactive_leave_req/'; ?>"+edit_leave_req_id;
					ajax_data('','users/inactive_leave_req/'+edit_leave_req_id,'');

					alert('You have successfully delete a leave request!');
					$("#edit_leave_modal").modal('hide');

					/*var current_url = window.location.href.split('#')[0];
					var compare_url = "<?php //echo base_url().'users/leave_details/'.$this->session->userdata('user_id'); ?>";

					if (current_url == compare_url){
						location.reload();
					}*/
					if (window.location.href.split('#')[0] == "<?php echo base_url().'users/leave_details/'.$this->session->userdata('user_id'); ?>"){
						location.reload();
					}
				});

				$("#btnUpdateLeaveReq").click(function(){

					var start_date = formatDatetoMMDDYYYY($("#start_day_of_leave").val());
					var end_date = formatDatetoMMDDYYYY($("#end_day_of_leave").val());
					var check_start_date = new Date(start_date);
					var check_end_date = new Date(end_date);

					if (check_start_date > check_end_date){
						//$("h4#myModalLabel.modal-title").text('Warning');
						$("#confirmText").text('Please select correct start date and end date.');
						$("#confirmButtons").html('<button type="button" class="btn btn-warning" data-dismiss="modal">Ok</button>');
						$("#confirmModal").modal('show');
					} else {
						$("#confirmText").text('Are you sure you want to update your leave?');
						$("#confirmButtons").html('<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>' +
												  '<button type="button" class="btn btn-success" onclick="confirmUpdate();">Yes</button>');
						$("#confirmModal").modal('show');
					}
					
				});
	        }
	    });
	}

	$('#apply_for_leave').click(function() {

		$("#confirmModal").modal('hide');
		$("#leave_type").val("");
		$("#s2id_leave_type > a.select2-choice > #select2-chosen-1").text("Choose a Type..");
		$("#total_leave").text("");

		if ($("#start_day_of_leave").val() != "" || $("#end_day_of_leave").val() != ""){
			$("#start_day_of_leave").val("");				
			$("#end_day_of_leave").val("");
			$('#start_day_datepicker').data().DateTimePicker.date(null);
			$('#end_day_datepicker').data().DateTimePicker.date(null);
	  	}

	  	$("#date_return").text("");
	  	$("#leave_details").val("");
	  	$("#total_days_away").text("");

	  	if (user_state == 6){
	    	setTheCalendars("nsw");
	    } else {
	    	setTheCalendars("wa");
	    }

	  	$("#leave_modal_button").html('<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>' +
						      		  '<button type="button" class="btn btn-primary" id="btnApply">Apply Leave</button>');
	  	$("#leave_modal").modal('show');

	  	$("#btnApply").click(function(){
			$("#confirmText").text('Are you sure you want to apply leave?');
			$("#confirmButtons").html('<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>' +
									  '<button type="button" class="btn btn-success" onclick="confirmYes();">Yes</button>');
			$("#confirmModal").modal('show');
		});
	});

	$('#start_day_datepicker').click(function() {
		if ($("#leave_type").val() == ''){
			//$("h4#myModalLabel.modal-title").text('Warning');
			$("#confirmText").text('Please select Leave Type first.');
			$("#confirmButtons").html('<button type="button" class="btn btn-warning" data-dismiss="modal">Ok</button>');
			$("#confirmModal").modal('show');
			return false;
		}
	});

	$('#end_day_datepicker').click(function() {
		if ($("#leave_type").val() == ''){
			//$("h4#myModalLabel.modal-title").text('Warning');
			$("#confirmText").text('Please select Leave Type first.');
			$("#confirmButtons").html('<button type="button" class="btn btn-warning" data-dismiss="modal">Ok</button>');
			$("#confirmModal").modal('show');
			return false;
		}
	});

	function setTheCalendars(state){

		if (state == "wa"){
			// loop for good friday and easter monday
			for (var i = current_year; i <= limit_year; i++) {

				easter_monday = new Date(generateGoodFriday(i));
				easter_monday = easter_monday.setDate(easter_monday.getDate() + 3);
				easter_monday = moment(easter_monday).format("YYYY-MM-DD");

				if (i > current_year){
					publicHolidays.push(i+'-01-01', i+'-01-02', i+'-01-26', i+'-04-25', i+'-12-25', i+'-12-26', generateFirstMonday(i, "03"), generateGoodFriday(i), easter_monday, generateFirstMonday(i, "06"), generatelastMonday(i, "09"));
				} else {
					publicHolidays = [i+'-01-01', i+'-01-02', i+'-01-26', i+'-04-25', i+'-12-25', i+'-12-26', generateFirstMonday(i, "03"), generateGoodFriday(i), easter_monday, generateFirstMonday(i, "06"), generatelastMonday(i, "09")];	
				}
			}
		}

		if (state == "nsw"){
			// loop for good friday and easter monday
			for (var i = current_year; i <= limit_year; i++) {

				easter_monday = new Date(generateGoodFriday(i));
				easter_monday = easter_monday.setDate(easter_monday.getDate() + 3);
				easter_monday = moment(easter_monday).format("YYYY-MM-DD");

				if (i > current_year){
					publicHolidays.push(i+'-01-01', i+'-01-02', i+'-01-26', i+'-04-25', i+'-12-25', i+'-12-26', generateGoodFriday(i), easter_monday, generateSecondMonday(i, "06"), generateFirstMonday(i, "10"));
				} else {
					publicHolidays = [i+'-01-01', i+'-01-02', i+'-01-26', i+'-04-25', i+'-12-25', i+'-12-26', generateGoodFriday(i), easter_monday, generateSecondMonday(i, "06"), generateFirstMonday(i, "10")];	
				}
			}
		}

		// function for generating the 1st mondays
		function generateFirstMonday(current_year, month){
			month = new Date(current_year+"-"+month+"-01");
			monthDay = month.getDay();

			if (monthDay == 1 ){
				//alert("mon to");
				first_monday = moment(month).format("YYYY-MM-DD");
				return first_monday;
			} else if (monthDay == 0) {
				//alert("sun to");
				first_monday = month.setDate(month.getDate() + 1);
				first_monday = new Date(first_monday);
				first_monday = moment(first_monday).format("YYYY-MM-DD");
				return first_monday;
			} else {
				//alert("other to");
				while (monthDay > 1){
					first_monday = month.setDate(month.getDate() + 1);
					first_monday = new Date(first_monday);
					firstMonDay = first_monday.getDay();

					if (firstMonDay == 1){
						first_monday = moment(first_monday).format("YYYY-MM-DD");
						return first_monday;
						break;
					}
				}
			}
		}

		// function for generating the 2nd monday
		function generateSecondMonday(current_year, month){
			month = new Date(current_year+"-"+month+"-01");
			monthDay = month.getDay();

			//alert(month+" "+monthDay);

			switch (monthDay) {
				case 0:
					second_monday = month.setDate(month.getDate() + 8);
					break;
				case 1:
					second_monday = month.setDate(month.getDate() + 7);
					break;
				case 2:
					second_monday = month.setDate(month.getDate() + 13);
					break;
				case 3:
					second_monday = month.setDate(month.getDate() + 12);
					break;
				case 4:
					second_monday = month.setDate(month.getDate() + 11);
					break;
				case 5:
					second_monday = month.setDate(month.getDate() + 10);
					break;
				case 6:
					second_monday = month.setDate(month.getDate() + 9);
					break;
				default:
					break;
			}

			second_monday = new Date(second_monday);
			second_monday = moment(second_monday).format("YYYY-MM-DD");

			return second_monday;
		}

		function generatelastMonday(current_year, month){
			month = new Date(current_year+"-"+month+"-30");
			monthDay = month.getDay();

			if (monthDay == 1 ){
				//alert("mon to");
				last_monday = moment(month).format("YYYY-MM-DD");
				return last_monday;
			} else if (monthDay == 0) {
				//alert("sun to");
				last_monday = month.setDate(month.getDate() - 6);
				last_monday = new Date(last_monday);
				last_monday = moment(last_monday).format("YYYY-MM-DD");
				return last_monday;
			} else {
				//alert("other to");
				while (monthDay > 1){
					last_monday = month.setDate(month.getDate() - 1);
					last_monday = new Date(last_monday);
					lastMonDay = last_monday.getDay();

					if (lastMonDay == 1){
						last_monday = moment(last_monday).format("YYYY-MM-DD");
						return last_monday;
						break;
					}
				}
			}
		}

		// function for generating the good friday
		function generateGoodFriday(current_year){

			var quo = current_year / 19;
			var prod = ~~quo * 19;
			var diff = current_year - prod;  
			var golden_no = diff + 1;

			switch (golden_no) {
		    case 0:
		        full_moon_date = current_year+"-03-27";
		        break;
		    case 1:
		        full_moon_date = current_year+"-04-14";
		        break;
		    case 2:
		        full_moon_date = current_year+"-04-03";
		        break;
		    case 3:
		        full_moon_date = current_year+"-03-23";
		        break;
		    case 4:
		        full_moon_date = current_year+"-04-11";
		        break;
		    case 5:
		        full_moon_date = current_year+"-03-31";
		        break;
		    case 6:
		        full_moon_date = current_year+"-04-18";
		        break;
		    case 7:
		        full_moon_date = current_year+"-04-08";
		        break;
		    case 8:
		        full_moon_date = current_year+"-03-28";
		        break;
		    case 9:
		        full_moon_date = current_year+"-04-16";
		        break;
		    case 10:
		        full_moon_date = current_year+"-04-05";
		        break;
		    case 11:
		        full_moon_date = current_year+"-03-25";
		        break;
		    case 12:
		        full_moon_date = current_year+"-04-13";
		        break;
		    case 13:
		        full_moon_date = current_year+"-04-02";
		        break;
		    case 14:
		        full_moon_date = current_year+"-03-22";
		        break;
		    case 15:
		        full_moon_date = current_year+"-04-10";
		        break;
		    case 16:
		        full_moon_date = current_year+"-03-30";
		        break;
		    case 17:
		        full_moon_date = current_year+"-04-17";
		        break;
		    case 18:
		        full_moon_date = current_year+"-04-07";
		        break;
		    case 19:
		        full_moon_date = current_year+"-03-27";
		        break;
			} 

			full_moon_date = new Date(full_moon_date);

			//alert(full_moon_date);

			if (full_moon_date.getDay() < 5){
				//alert("< than 5");

				while (full_moon_date.getDay() < 5){
					good_friday = full_moon_date.setDate(full_moon_date.getDate() + 1);

					good_friday = new Date(good_friday);
						
					if (good_friday.getDay() == 5){
						good_friday = moment(good_friday).format("YYYY-MM-DD");

						//alert(good_friday);
						return good_friday;
						break;
					}
				}
			} else if (full_moon_date.getDay() > 5){
				//alert("> than 5");

				while (full_moon_date.getDay() > 5){
					good_friday = full_moon_date.setDate(full_moon_date.getDate() - 1);
					good_friday = new Date(good_friday);
					
					if (good_friday.getDay() == 5){
						good_friday = moment(good_friday).format("YYYY-MM-DD");

						//alert(good_friday);
						return good_friday;
						break;
					}
				}

			} else {
				//alert("== 5");

				good_friday = new Date(good_friday);
				good_friday = moment(good_friday).format("YYYY-MM-DD");

				//alert(good_friday);
				return good_friday;
			}
		}

		$("#start_day_datepicker").datetimepicker({ 
			format: 'DD/MM/YYYY',
			useCurrent: false,
			daysOfWeekDisabled: [0, 6],
			disabledDates: publicHolidays,
			
		}).on('dp.change', function (s) {	
			$("#end_day_datepicker").data("DateTimePicker").minDate(s.date);
			$("#end_day_datepicker").datetimepicker({ 
				format: 'DD/MM/YYYY',
				useCurrent: false
			});

			var end_date = $("#end_day_of_leave").val();

			if (end_date != ""){

				var e = formatDatetoMMDDYYYY(end_date);
				var total_days = TotalDaysAway(s.date, e);
				var minuspublicHolidays = minuspublicHoliday(s.date, e);
				var total_days_away = total_days - minuspublicHolidays;
				$("#total_days_away").text(total_days_away);

				var leave_type = $("#leave_type").val();		
				var leave_type_name = leave_type.substr(2);
				var leave_type_id = leave_type.substr(0, 1);

				if (leave_type_id != '5'){
					var total_leave_remaining = leave_remaining - total_days_away;	
				} else {
					total_leave_remaining = "-";
				}

				if (+(total_leave_remaining) < 0 && leave_type_id != '5') {
					//$("h4#myModalLabel.modal-title").text('Warning');
					$("#confirmText").html('You only have '+leave_remaining+' remaining leave.<br><br>Please select again:<ul><li>Start Day of Leave</li><li>End Day of Leave</li>');
					$("#confirmButtons").html('<button type="button" class="btn btn-warning" data-dismiss="modal">Ok</button>');
					$("#confirmModal").modal('show');

					$("#start_day_of_leave").val("");				
					$("#end_day_of_leave").val("");
					$('#start_day_datepicker').data().DateTimePicker.date(null);
					$('#end_day_datepicker').data().DateTimePicker.date(null);
					$("#total_leave").text(leave_remaining);
					$("#date_return").text("");
					$("#total_days_away").text("");
				}
			}			
		});

		$("#end_day_datepicker").datetimepicker({ 
			format: 'DD/MM/YYYY',
			useCurrent: false,
			daysOfWeekDisabled: [0, 6],
			disabledDates: publicHolidays		
		}).on('dp.change', function (e) {

			start_date = $("#start_day_of_leave").val();
			var s = formatDatetoMMDDYYYY(start_date);

			DateReturn(e.date);
			var total_days = TotalDaysAway(s, e.date);
			var minuspublicHolidays = minuspublicHoliday(s, e.date);
			var total_days_away = total_days - minuspublicHolidays;
			
			var leave_type = $("#leave_type").val();		
			var leave_type_name = leave_type.substr(2);
			var leave_type_id = leave_type.substr(0, 1);

			if (leave_type_id != '5'){
				var total_leave_remaining = leave_remaining - total_days_away;	
			} else {
				total_leave_remaining = "-";
			}

			if (+(total_leave_remaining) < 0 && leave_type_id != '5') {
				//$("h4#myModalLabel.modal-title").text('Warning');
				$("#confirmText").html('You only have '+leave_remaining+' remaining leave.<br><br>Please select again:<ul><li>Start Day of Leave</li><li>End Day of Leave</li>');
				$("#confirmButtons").html('<button type="button" class="btn btn-warning" data-dismiss="modal">Ok</button>');
				$("#confirmModal").modal('show');
				
				$("#start_day_of_leave").val("");				
				$("#end_day_of_leave").val("");
				$('#start_day_datepicker').data().DateTimePicker.date(null);
				$('#end_day_datepicker').data().DateTimePicker.date(null);
				$("#total_leave").text(leave_remaining);
				$("#date_return").text("");
				$("#total_days_away").text("");
			} else {
				$("#end_day_datepicker").data("DateTimePicker").minDate(e.date);
				$('#start_day_datepicker').data("DateTimePicker").maxDate(e.date);
				$("#total_days_away").text(total_days_away);
			}
		});
	}

	function DateReturn(e){
		e = new Date(e);
	    var date_return = new Date(e.setDate(e.getDate() + 1));
	    var dr_formatted = "";
	    var new_dr = "";
	    
	    if (date_return.getDay() == 6){
	    	date_return = new Date(e.setDate(e.getDate() + 2));
	    	dr_formatted = moment(date_return).format("YYYY-MM-DD"); // for weekend
	    } else {
	    	dr_formatted = moment(date_return).format("YYYY-MM-DD"); // for not weekend
	    }

    	while(checkifHoliday(dr_formatted) == true){	
	    	new_dr = new Date(dr_formatted);
	    	new_dr = new Date(new_dr.setDate(new_dr.getDate() + 1));

	    	if (new_dr.getDay() == 6){
		    	new_dr = new Date(new_dr.setDate(new_dr.getDate() + 2));

		    	dr_formatted = moment(new_dr).format("YYYY-MM-DD");
		    	var dr_not_weekend_hol = new Date(dr_formatted);
		    	dr_not_weekend_hol = moment(dr_not_weekend_hol).format("DD/MM/YYYY");
		    	$("#date_return").text(dr_not_weekend_hol);
		    } else {
		    	dr_formatted = moment(new_dr).format("YYYY-MM-DD");
		    	var dr_weekdays_hol = moment(dr_formatted).format("DD/MM/YYYY");
		    	$("#date_return").text(dr_weekdays_hol);
		    }
	    }

	    if(checkifHoliday(dr_formatted) != true){
			new_dr = new Date(dr_formatted);
			new_dr = moment(new_dr).format("DD/MM/YYYY");
    		$("#date_return").text(new_dr);
	    }
	}

	function TotalDaysAway(s, e){

		start_date = new Date(s);
		end_date = new Date(e);

		//alert(start_date+" "+end_date);

		var start_day = moment(start_date).format("MM/DD/YYYY");
		var end_day = moment(end_date).format("MM/DD/YYYY");

	    s = new Date(start_day);
	    e = new Date(end_day);

	    // Set time to midday to avoid dalight saving and browser quirks
	    s.setHours(12,0,0,0);
	    e.setHours(12,0,0,0);

	    // Get the difference in whole days
	    var totalDays = Math.round((e - s) / 8.64e7);

	    // Get the difference in whole weeks
	    var wholeWeeks = totalDays / 7 | 0;

	    // Estimate business days as number of whole weeks * 5
	    days = wholeWeeks * 5;

	    // If not even number of weeks, calc remaining weekend days
	    if (totalDays % 7) {
	    	s.setDate(s.getDate() + wholeWeeks * 7);

    		while (s < e) {
    			
		      	s.setDate(s.getDate() + 1);

		      	// If day isn't a Sunday or Saturday, add to business days
		      	if (s.getDay() != 0 && s.getDay() != 6) {
		        	++days;
		      	}
	  		}
		}

		return days + 1;
	}

	function minuspublicHoliday(s, e){
		var minuspublicHolidays = 0;
		var start_date_holiday = new Date(s);
		var end_date_holiday = new Date(e);

		//alert(start_date_holiday+" "+end_date_holiday);

		var start_day_holiday = moment(start_date_holiday).format("MM/DD/YYYY");
		var end_day_holiday = moment(end_date_holiday).format("MM/DD/YYYY");

	    var ss = new Date(start_day_holiday);
	    var es = new Date(end_day_holiday);

	    while (ss < es) {
	    	//alert(ss+" "+es+"while1");
	    	publicHolidays.forEach(checkBetween);
  			ss.setDate(ss.getDate() + 1);
  			//alert(ss);
  		}

	    function checkBetween(item){
			if(item == moment(ss).format("YYYY-MM-DD")){
				minuspublicHolidays = minuspublicHolidays + 1;
			}
		}	   

		return minuspublicHolidays;
	}

	function checkifHoliday(date){
		var publicHolidaysLength = publicHolidays.length;

		for (var i=0; i < publicHolidaysLength; i++){
			if(publicHolidays[i] == date){
				return true;
				break;
			}
		}
	}

	function confirmYes(){
		var has_error = 0;
		var date_today = new Date();
		var leave_type = $("#leave_type").val();
		var start_day_of_leave = $("#start_day_of_leave").val();
		var end_day_of_leave = $("#end_day_of_leave").val();
		var date_return = $("#date_return").text();
		var leave_details = $("#leave_details").val();
		var total_days_away = $("#total_days_away").text();
		var possible_date = "";
		
		leave_type_name= leave_type.substr(2);
		leave_type_id = leave_type.substr(0, 1);

		if (leave_type != "" && date_return != "" && leave_details != "" && total_days_away != "0" ){

			if (leave_type_id == 1){
				if (total_days_away == 1) {
					//alert("1 day to!");
					
					var one_day_notice = "<?php echo $notice_days_annual[0]->days_advance_notice; ?>";	
					
					var start_date = $("#start_day_of_leave").val();
					
					possible_date = date_today.setDate(date_today.getDate() + +one_day_notice);
					possible_date = moment(possible_date).format("DD/MM/YYYY");

					var start_date_formatted = formatDatetoMMDDYYYY(start_date);
					var possible_date_formatted = formatDatetoMMDDYYYY(possible_date);

					start_date_final = new Date(start_date_formatted);
					possible_date_final = new Date(possible_date_formatted);

					if (start_date_final < possible_date_final){
						$("#confirmText").html(''+leave_type_name+' has advanced notice of at least '+one_day_notice+' days if you apply for <?php echo $notice_days_annual[0]->days_notice; ?>.<br><br>Please select again:<ul><li>Start Day of Leave</li><li>End Day of Leave</li>');
						$("#confirmButtons").html('<button type="button" class="btn btn-warning" data-dismiss="modal">Ok</button>');
						$("#confirmModal").modal('show');

						$("#start_day_of_leave").val("");
						$("#end_day_of_leave").val("");

						$('#start_day_datepicker').data().DateTimePicker.date(null);
					    $('#end_day_datepicker').data().DateTimePicker.date(null);

					    $("#total_leave").text(leave_remaining);
						$("#date_return").text("");
						$("#total_days_away").text("");
						has_error = 1;
					} else {
						has_error = 0;
					}
				} 

				if (total_days_away > 1 && total_days_away <= 5) {
					//alert("2-5 days to!");

					var two_to_five_days_notice = "<?php echo $notice_days_annual[1]->days_advance_notice; ?>";
					
					var start_date = $("#start_day_of_leave").val();
					
					possible_date = date_today.setDate(date_today.getDate() + +two_to_five_days_notice);
					possible_date = moment(possible_date).format("DD/MM/YYYY");

					var start_date_formatted = formatDatetoMMDDYYYY(start_date);
					var possible_date_formatted = formatDatetoMMDDYYYY(possible_date);

					start_date_final = new Date(start_date_formatted);
					possible_date_final = new Date(possible_date_formatted);

					if (start_date_final < possible_date_final){
						$("#confirmText").html(''+leave_type_name+' have notice at least '+two_to_five_days_notice+' days in advance if you apply <?php echo $notice_days_annual[1]->days_notice; ?>.<br><br>Please select again:<ul><li>Start Day of Leave</li><li>End Day of Leave</li>');
						$("#confirmButtons").html('<button type="button" class="btn btn-warning" data-dismiss="modal">Ok</button>');
						$("#confirmModal").modal('show');

						$("#start_day_of_leave").val("");
						$("#end_day_of_leave").val("");

						$('#start_day_datepicker').data().DateTimePicker.date(null);
					    $('#end_day_datepicker').data().DateTimePicker.date(null);

					    $("#total_leave").text(leave_remaining);
						$("#date_return").text("");
						$("#total_days_away").text("");
						has_error = 1;
					} else {
						has_error = 0;
					}
				}

				if (total_days_away > 5) {
					//alert("6 or more days to!");
					
					var six_or_more_notice = "<?php echo $notice_days_annual[2]->days_advance_notice; ?>";
					
					var start_date = $("#start_day_of_leave").val();
					
					possible_date = date_today.setDate(date_today.getDate() + +six_or_more_notice);
					possible_date = moment(possible_date).format("DD/MM/YYYY");

					var start_date_formatted = formatDatetoMMDDYYYY(start_date);
					var possible_date_formatted = formatDatetoMMDDYYYY(possible_date);

					start_date_final = new Date(start_date_formatted);
					possible_date_final = new Date(possible_date_formatted);

					if (start_date_final < possible_date_final){
						$("#confirmText").html(''+leave_type_name+' have notice at least '+six_or_more_notice+' days in advance if you apply <?php echo $notice_days_annual[2]->days_notice; ?>.<br><br>Please select again:<ul><li>Start Day of Leave</li><li>End Day of Leave</li>');
						$("#confirmButtons").html('<button type="button" class="btn btn-warning" data-dismiss="modal">Ok</button>');
						$("#confirmModal").modal('show');

						$("#start_day_of_leave").val("");
						$("#end_day_of_leave").val("");

						$('#start_day_datepicker').data().DateTimePicker.date(null);
					    $('#end_day_datepicker').data().DateTimePicker.date(null);

					    $("#total_leave").text(leave_remaining);
						$("#date_return").text("");
						$("#total_days_away").text("");
						has_error = 1;
					} else {
						has_error = 0;
					}
				}
			}

			if (has_error == 0){
				var data = leave_type_id+'|'+start_day_of_leave+'|'+end_day_of_leave+'|'+date_return+'|'+leave_details+'|'+total_days_away;	
				$('#confirmModal').modal('hide');
				ajax_data(data,'users/apply_leave/<?php echo $this->session->userdata('user_id'); ?>','');
				alert('You have successfully applied a leave!');
				$('#leave_modal').modal('hide');
				if (window.location.href.split('#')[0] == "<?php echo base_url().'users/leave_details/'.$this->session->userdata('user_id'); ?>"){
					location.reload();
				}
			}

		} else {
			$('#confirmModal').modal('hide');
			alert("Please filled the required (*) fields");
			return false;
		}
    }

    function confirmUpdate(){
		var has_error = 0;
		var date_today = new Date();
		var leave_type = $("#leave_type").val();
		var e_leave_type_id = leave_type.substr(0, 1);
		var e_start_day_of_leave = $("#start_day_of_leave").val();
		var e_end_day_of_leave = $("#end_day_of_leave").val();
		var e_date_return = $("#date_return").text();
		var e_leave_details = $("#leave_details").val();
		var e_total_days_away = $("#total_days_away").text();

		leave_type_name= leave_type.substr(2);

		if (e_leave_type_id != "" && e_date_return != "" && e_leave_details != "" && e_total_days_away != "0" ){

			if (e_leave_type_id == 1){
				if (e_total_days_away == 1) {
					//alert("1 day to!");
					
					var one_day_notice = "<?php echo $notice_days_annual[0]->days_advance_notice; ?>";	

					var start_date = e_start_day_of_leave;
					
					var possible_date = date_today.setDate(date_today.getDate() + +one_day_notice);
					possible_date = moment(possible_date).format("DD/MM/YYYY");

					var start_date_formatted = formatDatetoMMDDYYYY(start_date);
					var possible_date_formatted = formatDatetoMMDDYYYY(possible_date);

					start_date_final = new Date(start_date_formatted);
					possible_date_final = new Date(possible_date_formatted);

					if (start_date_final < possible_date_final){
					    $("#confirmText").html(''+leave_type_name+' have notice at least '+one_day_notice+' days in advance if you apply for <?php echo $notice_days_annual[0]->days_notice; ?>.<br><br>Please select again:<ul><li>Start Day of Leave</li><li>End Day of Leave</li>');
						$("#confirmButtons").html('<button type="button" class="btn btn-warning" data-dismiss="modal">Ok</button>');
						$("#confirmModal").modal('show');

						$("#start_day_of_leave").val("");
						$("#end_day_of_leave").val("");

						$('#start_day_datepicker').data().DateTimePicker.date(null);
					    $('#end_day_datepicker').data().DateTimePicker.date(null);

					    $("#total_leave").text(leave_remaining);
						$("#date_return").text("");
						$("#total_days_away").text("");
						has_error = 1;
					} else {
						has_error = 0;
					}
				} 

				if (e_total_days_away > 1 && e_total_days_away <= 5) {
					//alert("2-5 days to!");

					var two_to_five_days_notice = "<?php echo $notice_days_annual[1]->days_advance_notice; ?>";

					var start_date = e_start_day_of_leave;
					
					var possible_date = date_today.setDate(date_today.getDate() + +two_to_five_days_notice);
					possible_date = moment(possible_date).format("DD/MM/YYYY");

					var start_date_formatted = formatDatetoMMDDYYYY(start_date);
					var possible_date_formatted = formatDatetoMMDDYYYY(possible_date);

					start_date_final = new Date(start_date_formatted);
					possible_date_final = new Date(possible_date_formatted);

					if (start_date_final < possible_date_final){
						$("#confirmText").html(''+leave_type_name+' have notice at least '+two_to_five_days_notice+' days in advance if you apply <?php echo $notice_days_annual[1]->days_notice; ?>.<br><br>Please select again:<ul><li>Start Day of Leave</li><li>End Day of Leave</li>');
						$("#confirmButtons").html('<button type="button" class="btn btn-warning" data-dismiss="modal">Ok</button>');
						$("#confirmModal").modal('show');

						$("#start_day_of_leave").val("");
						$("#end_day_of_leave").val("");

						$('#start_day_datepicker').data().DateTimePicker.date(null);
					    $('#end_day_datepicker').data().DateTimePicker.date(null);

					    $("#total_leave").text(leave_remaining);
						$("#date_return").text("");
						$("#total_days_away").text("");
						has_error = 1;
					} else {
						
						has_error = 0;
					}
				}

				if (e_total_days_away > 5) {
					//alert("6 or more days to!");
					
					var six_or_more_notice = "<?php echo $notice_days_annual[2]->days_advance_notice; ?>";
					
					var start_date = e_start_day_of_leave;
					
					var possible_date = date_today.setDate(date_today.getDate() + +six_or_more_notice);
					possible_date = moment(possible_date).format("DD/MM/YYYY");

					var start_date_formatted = formatDatetoMMDDYYYY(start_date);
					var possible_date_formatted = formatDatetoMMDDYYYY(possible_date);

					start_date_final = new Date(start_date_formatted);
					possible_date_final = new Date(possible_date_formatted);

					if (start_date_final < possible_date_final){
						$("#confirmText").html(''+leave_type_name+' have notice at least '+six_or_more_notice+' days in advance if you apply <?php echo $notice_days_annual[2]->days_notice; ?>.<br><br>Please select again:<ul><li>Start Day of Leave</li><li>End Day of Leave</li>');
						$("#confirmButtons").html('<button type="button" class="btn btn-warning" data-dismiss="modal">Ok</button>');
						$("#confirmModal").modal('show');

						$("#start_day_of_leave").val("");
						$("#end_day_of_leave").val("");

						$('#start_day_datepicker').data().DateTimePicker.date(null);
					    $('#end_day_datepicker').data().DateTimePicker.date(null);

					    $("#total_leave").text(leave_remaining);
						$("#date_return").text("");
						$("#total_days_away").text("");
						has_error = 1;
					} else {
						has_error = 0;
					}
				}
			}
			if(has_error == 0){
				var data = edit_leave_req_id+'|'+e_leave_type_id+'|'+e_start_day_of_leave+'|'+e_end_day_of_leave+'|'+e_date_return+'|'+e_leave_details+'|'+e_total_days_away;	
				$('#confirmModal').modal('hide');
				ajax_data(data,'users/update_leave_req/<?php echo $this->session->userdata('user_id'); ?>','');
				alert('You have successfully update your leave!');
				$("#leave_modal").modal('hide');

				var current_url = window.location.href.split('#')[0];
				var compare_url = "<?php echo base_url().'users/leave_details/'.$this->session->userdata('user_id'); ?>";

				if (current_url == compare_url){
					location.reload();
				}
				return true;
			}
		} else {
			$('#confirmModal').modal('hide');
			alert("Please filled the required (*) fields");
			return false;
		}
    }

    function formatDatetoMMDDYYYY(date){
		var day = date.substr(0, 2);
		var month = date.substr(3, 2);
		var year = date.substr(6, 4);

		var date_formatted = month + '/' + day + '/' + year;

		return date_formatted;
	}
</script>
<!-- Mike Coros end -->

<div id="sb-site">
