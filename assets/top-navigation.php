<?php //if($this->session->userdata('logged_in')){ echo 'signin kna'; }else{ echo 'hindi pa';} 
	date_default_timezone_set("Australia/Perth");

	$this->load->helper('url');
	$this->load->module('admin');
	$this->load->module('projects');
	$this->load->module('users');

	if (strpos(current_url(), '/users/account/') != false) {
		$user_id_page = $this->uri->segment(3);
		$leave_remaining2 = $this->users->leave_remaining($user_id_page);
		$fetch_user = $this->users->fetch_user($user_id_page);
	} else {
		$leave_remaining2 = '';
		$fetch_user = '';
	}

	if (strpos(current_url(), '/users/leave_details/') != false) {

		if ($this->uri->segment(3) != $this->session->userdata('user_id')) {
			$for_edit_user_id_page = $this->uri->segment(3);
			$for_edit_fetch_user = $this->users->fetch_user($for_edit_user_id_page);
			$leave_remaining3 = $this->users->leave_remaining($for_edit_user_id_page);
		} else {
			$for_edit_user_id_page = '';
		}
	} else {
		$for_edit_user_id_page = '';
	}

	$leave_type = $this->users->leave_type();
	$approval_count = $this->users->for_approval_count();
	$leave_remaining1 = $this->users->leave_remaining('');
	$user_state = $this->users->user_state($this->session->userdata('user_id'));

	$notice_days_annual = $this->admin->get_notice_days('1');
	$leave_requests = $this->session->userdata('leave_requests');

 	echo '<input type="hidden" name="user_id_access" value="'.$this->session->userdata('user_id').'">';
	$progress_reports = $this->session->userdata('progress_report');

 	$project_manager_q = $this->user_model->fetch_user_by_role(3);
 	$project_manager = $project_manager_q->result();

 	$account_manager_q = $this->user_model->fetch_user_by_role(20);
 	$account_manager = $account_manager_q->result();

 	$estimator_q = $this->user_model->fetch_user_by_role(8);
 	$estimator = $estimator_q->result();

 	$fetch_user_details = $this->users->fetch_user($this->session->userdata('user_id')); 
?><div class="navbar navbar-inverse navbar-fixed-top top-nav" role="navigation">
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
		<input type="hidden" class="prjc_user_id" value="<?php echo $this->session->userdata('user_id'); ?>">
 

			<ul id="mobile_nav" class="nav navbar-nav navbar-left">
				<li>
					<a href="<?php echo base_url(); ?>company"> <i class="fa fa-users fa"></i> Company</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>projects"> <i class="fa fa-map-marker fa"></i> Projects</a>
				</li>
			<?php /*<li>
					<a href="<?php echo base_url(); ?>wip"> <i class="fa fa-tasks fa"></i> WIP</a>
				</li>*/ ?>
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
 
<input type="text" id="search_project_num" name="search_project_num" placeholder="seach project no" class="input-control input-xs tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="Seach by Project Number<br />Press the Enter button to submit" style="margin-top: 8px; border-radius: 4px; border: none; padding: 2px 6px;" autocomplete="new-password">
				 

				</li>
			</ul>






			<ul class="nav navbar-nav navbar-right">

<?php if ($approval_count != 0):?>
<li>
<a href="<?php echo base_url().'users/leave_approvals/'.$this->session->userdata('user_id'); ?>" class="tooltip-test" data-placement="bottom" title="<?php echo $approval_count; ?> Pending Leave Request" style="padding: 8px 0 0;"><span class="badge btn btn-danger"> <span class="badge"><?php echo $approval_count ?></span></span></a>
</li>
<?php endif; ?>


<?php if($this->session->userdata('is_admin') == 1 || $this->session->userdata('user_role_id') == 16 || $this->session->userdata('user_id') == 15 || $this->session->userdata('user_id') == 9 || $this->session->userdata('is_admin') == 1 ): ?>
	<?php if (strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false): ?>
		<?php // if($this->session->userdata('is_admin') == 1 ): ?>					
		<li>
		<a role="menuitem" data-toggle="modal" data-target="#view_dash" tabindex="-1" href="#"><span style="background: #fff; padding: 2px 8px; border-radius: 8px; color: #000000; text-shadow: none; margin-right: 5px;"> <span id="simulation_pm_name"></span> <i class="fa fa-eye-slash fa-lg" aria-hidden="true"></i> View</span></a>
		</li>
		<?php //endif; ?>
	<?php endif; ?>
<?php endif; ?>

				<?php if($progress_reports == 1): ?>
					<li id="fat-menu" class="real-time-notif dropdown"></li>
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
                        				
                            <li>
                                <a role="menuitem" data-toggle="modal" data-target="#wip_project_review" href="#"><i class="fa fa-table"></i> WIP Review Report</a>
                            </li>
						<?php endif; ?>
							<li role="presentation">
								<a id="apply_for_leave" style="cursor: pointer;" onclick="apply_for_leave('');"><i class="fa fa-calendar-plus-o"></i> Apply for Leave</a>
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
        <h4 class="modal-title" id="myModalLabel"><label>Leave Request Form</label><span> (<a href="#" data-placement="right" class="popover-test" title="" data-content="Hello there mate! Welcome to the clients screen." data-original-title="Welcome">?</a>)</span><p>Fields having (*) are required.</p></h4>

        <p id="leave_for_other" class="pull-right" style="color: red; font-style: italic; position: relative; top: -10px; font-weight: bolder; display: none;">
        	<?php
        		if (!empty($fetch_user)){
        			foreach ($fetch_user as $row){
	        			echo 'Applying Leave for: '.$row->user_first_name.' '.$row->user_last_name;
	        			echo '<input type="hidden" id="user_id_page_is_offshore" name="user_id_page_is_offshore" value="'.$row->is_offshore.'">';
	        		}
        		}
        	?>
        </p>

      </div>

      <?php
	 	if (!empty($fetch_user_details)){
			foreach ($fetch_user_details as $row){
				echo '<input type="hidden" id="is_offshore" name="is_offshore" value="'.$row->is_offshore.'">';
			}
		}
	  ?>

	  <?php
	 	if (!empty($for_edit_fetch_user)){
			foreach ($for_edit_fetch_user as $row){
				echo '<input type="hidden" id="for_edit_is_offshore" name="for_edit_is_offshore" value="'.$row->is_offshore.'">';
			}
		}
	  ?>
      
      <input type="hidden" id="leave_request_access" name="leave_request_access" value="<?php echo $leave_requests; ?>">

	     <form class="form-horizontal" role="form">
	      <div class="modal-body pad-5">
	        <div class="box-area pad-5 clearfix">

	        	<input type="hidden" class="no_hrs_of_work" name="no_hrs_of_work" value="<?php echo $leave_remaining1->no_hrs_of_work; ?>">

				<input type="hidden" id="user_id_page_no_hrs_of_work" name="user_id_page_no_hrs_of_work" value="<?php echo (!empty($leave_remaining2->no_hrs_of_work)) ? $leave_remaining2->no_hrs_of_work : ''; ?>">	        	

				<input type="hidden" id="for_edit_no_hrs_of_work" name="for_edit_no_hrs_of_work" value="<?php echo (!empty($leave_remaining3->no_hrs_of_work)) ? $leave_remaining3->no_hrs_of_work : ''; ?>">

				<input type="hidden" id="for_edit_user_id_page" name="for_edit_user_id_page" value="<?php echo $for_edit_user_id_page; ?>">	        	

	        	<input type="hidden" id="leave_remaining1_annual" name="leave_remaining1_annual" value="<?php echo (!empty($leave_remaining1->total_annual)) ? round($leave_remaining1->total_annual / $leave_remaining1->no_hrs_of_work, 2) : '0'; ?>">
	        	<input type="hidden" id="leave_remaining1_personal" name="leave_remaining1_personal" value="<?php echo (!empty($leave_remaining1->total_personal)) ? round($leave_remaining1->total_personal / $leave_remaining1->no_hrs_of_work, 2) : '0'; ?>">
	        	<input type="hidden" id="leave_remaining2_annual" name="leave_remaining2_annual" value="<?php echo (!empty($leave_remaining2->total_annual)) ? round($leave_remaining2->total_annual / $leave_remaining2->no_hrs_of_work, 2) : '0'; ?>">
	        	<input type="hidden" id="leave_remaining2_personal" name="leave_remaining2_personal" value="<?php echo (!empty($leave_remaining2->total_personal)) ? round($leave_remaining2->total_personal / $leave_remaining2->no_hrs_of_work, 2) : '0'; ?>">
	        	<input type="hidden" id="leave_remaining3_annual" name="leave_remaining3_annual" value="<?php echo (!empty($leave_remaining3->total_annual)) ? round($leave_remaining3->total_annual / $leave_remaining3->no_hrs_of_work, 2) : '0'; ?>">
	        	<input type="hidden" id="leave_remaining3_personal" name="leave_remaining3_personal" value="<?php echo (!empty($leave_remaining3->total_personal)) ? round($leave_remaining3->total_personal / $leave_remaining3->no_hrs_of_work, 2) : '0'; ?>">
	        	<input type="hidden" id="one_day_notice" name="one_day_notice" value="<?php echo (!empty($notice_days_annual[0]->days_advance_notice)) ? $notice_days_annual[0]->days_advance_notice : '0'; ?>">
	        	<input type="hidden" id="one_day_notice_msg" name="one_day_notice_msg" value="<?php echo (!empty($notice_days_annual[0]->days_notice)) ? $notice_days_annual[0]->days_notice : ''; ?>">
	        	
	        	<input type="hidden" id="two_to_five_days_notice" name="two_to_five_days_notice" value="<?php echo (!empty($notice_days_annual[1]->days_advance_notice)) ? $notice_days_annual[1]->days_advance_notice : '0'; ?>">
	        	<input type="hidden" id="two_to_five_days_notice_msg" name="two_to_five_days_notice_msg" value="<?php echo (!empty($notice_days_annual[1]->days_notice)) ? $notice_days_annual[1]->days_notice : ''; ?>">
	        	
	        	<input type="hidden" id="six_or_more_days_notice" name="six_or_more_days_notice" value="<?php echo (!empty($notice_days_annual[2]->days_advance_notice)) ? $notice_days_annual[2]->days_advance_notice : '0'; ?>">
	        	<input type="hidden" id="six_or_more_days_notice_msg" name="six_or_more_days_notice_msg" value="<?php echo (!empty($notice_days_annual[2]->days_notice)) ? $notice_days_annual[2]->days_notice : ''; ?>">

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

				<input type="hidden" name="apply_for_others" value="">

					<div class="col-sm-12 m-bottom-10 clearfix">
						<label for="total_leave" class="col-sm-5 control-label">Total Leave Remaining:</label>
						<label id="total_leave" class="col-sm-3 control-label text-left" name="total_leave" style="color: red; font-weight: bolder;"></label>
					</div>

					<div class="clearfix"></div>

					<div class="col-sm-6 col-sm-offset-5 m-bottom-10 clearfix">
						<div class="checkbox">
						    <label>
						      <input type="checkbox" id="partial_day" value="1" name="partial_day"> Not a Full Day Leave?
						    </label>
						</div>
					</div>

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

					<div id="partial_day_wrap" style="display: none;">

						<div class="clearfix"></div>

						<div id="partial_day_part" class="col-sm-12 m-bottom-10 clearfix">
							<label for="partial_part" class="col-sm-5 control-label"></label>
							<div class="checkbox col-sm-3">
							    <label>
							      <input type="radio" class="partial_part" name="partial_part" value="1" checked disabled="true"> Arrived Late
							    </label>
							</div>

							<div class="checkbox col-sm-3">
							    <label>
							      <input type="radio" class="partial_part" name="partial_part" value="2" disabled="true"> Depart Early
							    </label>
							</div>
						</div>

						<div class="clearfix"></div>

						<div class="col-sm-12 m-bottom-10 clearfix">
							<label for="partial_time" class="col-sm-5 control-label time_in_out">Time <span class="toggle_time">In</span>* <i class="fa fa-question-circle fa-lg tooltip-test info_time_data" data-toggle="tooltip" data-placement="top" title="If 'Arrived Late' please input your 'Time In' here, if 'Depart Early' please input your 'Time Out' here. This input will accept only 15 minutes interval time data."></i>:</label>
							<div class="col-sm-5">
								<div class="input-group date" id="partial_day_datepicker">
									<input type="text" id="partial_time" class="form-control" onkeydown="return false;" name="partial_time" placeholder="00:00 AM/PM" value="" disabled="true">
									<span class="input-group-addon">
										Time <span class="fa fa-clock-o fa-lg"></span>
									</span>
								</div>
							</div>
						</div>

						<div class="col-sm-12 m-bottom-10 clearfix">
							<label for="no_hrs_of_leave" class="col-sm-5 control-label no_hrs_leave">No. Hrs of Leave* <i class="fa fa-question-circle fa-lg tooltip-test info_hrs_leave" data-toggle="tooltip" data-placement="top" title="Hours and minute value, this input will accept only 15 minutes interval time data."></i>:</label>
							<div class="col-sm-5">
								<div class="input-group date" id="leave_hrs_datepicker">
									<input type="text" id="no_hrs_of_leave" class="form-control" onkeydown="return false;" name="no_hrs_of_leave" placeholder="00:00 hrs" value="" disabled="true">
									<span class="input-group-addon">
										Hours <span class="fa fa-clock-o fa-lg"></span>
									</span>
								</div>
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

					<div id="total_days" class="col-sm-12 m-bottom-10 clearfix" style="display: block;">
						<label for="total_days_away" class="col-sm-5 control-label">Total of Days Away:</label>
						<label id="total_days_away" class="col-sm-3 control-label text-left" name="total_days_away" style="color: red; font-weight: bolder;">0</label>
					</div>

					<div id="total_holiday_count" class="col-sm-12 m-bottom-10 clearfix" style="display: block;">
						<label for="total_holiday" class="col-sm-5 control-label">Total Holidays:</label>
						<label id="total_holiday" class="col-sm-3 control-label text-left" name="total_holiday" style="color: red; font-weight: bolder;">0</label>
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
        <h4 class="modal-title msgbox" id="myModalLabel">Message Box</h4>
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
							<option selected value="">Select User</option>
							<?php foreach ($project_manager as $row){	
									if($row->user_id != 29){ echo '<option value="'.$row->user_id.'-pm">'.$row->user_first_name.' '.$row->user_last_name.'</option>'; }
							}?>

							<?php foreach ($account_manager as $row){	
									echo '<option value="'.$row->user_id.'-pm">'.$row->user_first_name.' '.$row->user_last_name.'</option>';
							}?>							

							<?php foreach ($estimator as $row){	
									if($row->user_id != 26){ echo '<option value="'.$row->user_id.'-es">'.$row->user_first_name.' '.$row->user_last_name.'</option>'; }
							}?>
							<option value="72-ch">Chris Hammond</option>
							<option value="21-acnt">Lesley Waller</option>
							<option value="22-jp">Marcus Dell</option>
							<option value="9-ad">Trevor Gamble</option>
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
				window.open("<?php echo base_url(); ?>dashboard?dash_view="+data);
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
							

							<?php foreach ($account_manager as $row){	
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

<!-- Modal -->
<div class="modal fade" id="PH_holiday" tabindex="-1" role="dialog" aria-labelledby="PH_holidayLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="PH_holidayLabel">Input Number</h4>
			</div>
			<div class="modal-body">
				<div class="form-group row">
					<div class="col-sm-12 text-center">
						<label for="PH_holidayInput">How many holidays?</label>
					</div>
					<div class="col-sm-offset-4 col-sm-4">
						<input type="number" class="form-control text-center" id="PH_holidayInput" name="PH_holidayInput" value="1" min="1">
					</div>
				</div>
			</div>
			<div class="modal-footer" style="margin-top: 0;">
				<button type="button" class="btn btn-danger" onclick="clearPH_holiday();">Close</button>
				<button type="button" class="btn btn-success" onclick="minusPH_holidays();">Apply</button>
			</div>
		</div>
	</div>
</div>

</div>

<!-- Mike Coros 04-06-17 -->
<script type="text/javascript">

	var leave_remaining = '';
	var current_year = new Date().getFullYear();
	var limit_year = new Date().getFullYear() + 5;
	var days = 0;
	var total_days = 0;
	var labour_day = '';
	var good_friday = '';
	var easter_monday = '';
	var start_day_store = '';
	var publicHolidays = '';
	var total_days_away = '';
	var check_day = '';
	var count_days_approval = '';
	var minimum_date = new Date();
	var edit_leave_req_id = '';
	var user_state = '<?php echo $user_state->user_focus_company_id; ?>';
	var leave_user_id = '';
	var leave_request_access = $('#leave_request_access').val();
	var no_hrs_of_work = $('.no_hrs_of_work').val();
	var leave_remaining1_annual = $('#leave_remaining1_annual').val();
	var leave_remaining1_personal = $('#leave_remaining1_personal').val();
	var leave_remaining2_annual = $('#leave_remaining2_annual').val();
	var leave_remaining2_personal = $('#leave_remaining2_personal').val();
	var leave_remaining3_annual = $('#leave_remaining3_annual').val();
	var leave_remaining3_personal = $('#leave_remaining3_personal').val();
	var is_offshore = $('#is_offshore').val();
	var user_id_page_no_hrs_of_work = $('#user_id_page_no_hrs_of_work').val();
	var user_id_page_is_offshore = $('#user_id_page_is_offshore').val();
	var for_edit_user_id_page = $('#for_edit_user_id_page').val();
	var for_edit_is_offshore = $('#for_edit_is_offshore').val();
	var for_edit_no_hrs_of_work = $('#for_edit_no_hrs_of_work').val();
	var logged_in_user_id = "<?php echo $this->session->userdata('user_id') ?>";
	var has_error = 0;
	var ph_holidays = 0;
	var pending_leave_count = '';

	$('#confirmModal').draggable({ handle: '.modal-header' });
	$('#PH_holiday').draggable({ handle: '.modal-header' });
	$('#partial_day').removeAttr('checked');

	$('#leave_type').change(function() {

		$('#leave_details').val('');

		ph_holidays = 0;

	  	var get_leave_type = $('#leave_type').val();
		leave_type_id = get_leave_type.substr(0, 1);

		if (leave_user_id != ''){
			switch (leave_type_id) {
			    case '1':
			        leave_remaining = leave_remaining2_annual;
			        break;
			    case '2':
			    	leave_remaining = leave_remaining2_personal;
			        break;
			    case '3':
			        leave_remaining = leave_remaining2_personal;
			        break;
			    case '4':
			    	leave_remaining = leave_remaining2_personal;
			        break;
			    case '5':
			    	leave_remaining = '-';
			        break;
			    case '6':
			    	leave_remaining = '-';
			        break;
			    default:
			    	leave_remaining = '0';
			        break;
			}
		} else if (for_edit_user_id_page != ''){
			switch (leave_type_id) {
			    case '1':
			        leave_remaining = leave_remaining3_annual;
			        break;
			    case '2':
			    	leave_remaining = leave_remaining3_personal;
			        break;
			    case '3':
			        leave_remaining = leave_remaining3_personal;
			        break;
			    case '4':
			    	leave_remaining = leave_remaining3_personal;
			        break;
			    case '5':
			    	leave_remaining = '-';
			        break;
			    case '6':
			    	leave_remaining = '-';
			        break;
			    default:
			    	leave_remaining = '0';
			        break;
			}
		} else {
			switch (leave_type_id) {
			    case '1':
			        leave_remaining = leave_remaining1_annual;
			        break;
			    case '2':
			    	leave_remaining = leave_remaining1_personal;
			        break;
			    case '3':
			        leave_remaining = leave_remaining1_personal;
			        break;
			    case '4':
			    	leave_remaining = leave_remaining1_personal;
			        break;
			    case '5':
			    	leave_remaining = '-';
			        break;
			    case '6':
			    	leave_remaining = '-';
			        break;
			    default:
			    	leave_remaining = '0';
			        break;
			}
		}

		$('#total_leave').text(leave_remaining);

	  	if ($('#start_day_of_leave').val() != '' || $('#end_day_of_leave').val() != ''){
	  		$('#start_day_of_leave').val('');				
			$('#end_day_of_leave').val('');
			$('#start_day_datepicker').data().DateTimePicker.date(null);
			$('#end_day_datepicker').data().DateTimePicker.date(null);
			$('input[name=partial_part]:checked').prop( 'disabled', false );
			$('input[name=partial_part]').prop( 'disabled', false );
			$('#partial_time').prop( 'disabled', false );
			$('#no_hrs_of_leave').prop( 'disabled', false );
	  	}
	  	$('#date_return').text('');
	  	$('#total_days_away').text('');

	  	$('#end_day_datepicker').click(function() {
			if ($('#start_day_of_leave').val() == ''){
				$('h4#myModalLabel.modal-title.msgbox').html("Message Box");
				$('#confirmText').text('Please select Start Day of Leave first.');
				$('#confirmButtons').html('<button type="button" class="btn btn-warning" data-dismiss="modal">Ok</button>');
				$('#confirmModal').modal('show');
				return false;
			}
		});
	  	
	  	is_offshore_disable_this(leave_type_id);
	});

	$('#start_day_datepicker').click(function() {
		if ($('#leave_type').val() == ''){
			$('h4#myModalLabel.modal-title.msgbox').html("Message Box");
			$('#confirmText').text('Please select Leave Type first.');
			$('#confirmButtons').html('<button type="button" class="btn btn-warning" data-dismiss="modal">Ok</button>');
			$('#confirmModal').modal('show');
			return false;
		}
	});

	$('#end_day_datepicker').click(function() {
		if ($('#leave_type').val() == ''){
			$('h4#myModalLabel.modal-title.msgbox').html("Message Box");
			$('#confirmText').text('Please select Leave Type first.');
			$('#confirmButtons').html('<button type="button" class="btn btn-warning" data-dismiss="modal">Ok</button>');
			$('#confirmModal').modal('show');
			return false;
		}
	});

	$('#partial_day_datepicker').datetimepicker({
		icons: {
                    close: 'fa fa-check-circle'
                },
        tooltips: {
		    close: 'Done?'
		},
		useCurrent: true,
        format: 'LT',
        stepping: 15,
        showClose: true,
        toolbarPlacement: 'bottom'
    });

	$('#leave_hrs_datepicker').datetimepicker({
		icons: {
                    close: 'fa fa-check-circle'
                },
        tooltips: {
		    close: 'Done?'
		},
        format: 'HH:mm',
        stepping: 15,
        showClose: true,
        toolbarPlacement: 'bottom',
        enabledHours: [00, 01, 02, 03, 04, 05, 06, 07]
    }).on('dp.change', function (h) {

    	if (is_offshore == 0 && leave_type_id != 5 || user_id_page_is_offshore == 0){
    		var leave_type = $('#leave_type').val();		
			var leave_type_name = leave_type.substr(2);
			var leave_type_id = leave_type.substr(0, 1);

			var format_h = moment(h.date).format('HH:mm');

			var total_leave = format_h.toString();
			var get_hrs = total_leave.substr(1, 1);
			var get_mins = total_leave.substr(3);
			var total_hrs_leave = '';

			switch (get_mins) {
			    case '15':
			        total_hrs_leave = get_hrs + '.25';
			        break;
			    case '30':
			    	total_hrs_leave = get_hrs + '.50';
			        break;
			    case '45':
			        total_hrs_leave = get_hrs + '.75';
			        break;
			    default:
			    	total_hrs_leave = get_hrs + '.0';
			        break;
			}

			leave_application_exceed_msg(leave_type_id, total_hrs_leave)	
    	}
	});

	function apply_for_leave(user_id) {

	    if (user_id != ''){
	      leave_user_id = user_id;
	      $('p#leave_for_other').show();
	    } else {
	      leave_user_id = '';
	      $('p#leave_for_other').hide();
	    }

	    $('#confirmModal').modal('hide');
	    $('#leave_type').val('');
	    $('#s2id_leave_type > a.select2-choice > #select2-chosen-1').text('Choose a Type..');

	    // if ($('#start_day_of_leave').val() != '' || $('#end_day_of_leave').val() != ''){
	      $('#start_day_of_leave').val('');       
	      $('#end_day_of_leave').val('');

	      if ($('#start_day_datepicker').data().DateTimePicker != null){
	      	$('#start_day_datepicker').data().DateTimePicker.date(null);
	      	$('#end_day_datepicker').data().DateTimePicker.date(null);
	      }
	    // }

	    $('#end_day_of_leave').prop( 'disabled', false );
	    $('#partial_day_wrap').hide();
	    $('#partial_day').prop('checked', false);
	    $('.partial_part[value="1"]').prop('checked', true);
	    $('#partial_time').val('');
	    $('#no_hrs_of_leave').val('');
	    $('#date_return').text('');
	    $('#leave_details').val('');
	    $('#total_days').show();
	    $('#total_days_away').text('');
	    $('#total_leave').text('');
	    $('#total_holiday').text('0');

	    if (user_state == 6){
	      setTheCalendars('nsw');
	    } else {
	      setTheCalendars('wa');
	    }

	    $('#leave_modal_button').html('<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>' +
	                      '<button type="button" class="btn btn-primary" id="btnApply">Apply Leave</button>');
	    $('#leave_modal').modal('show');

	    $('#btnApply').click(function(){
	      $('h4#myModalLabel.modal-title.msgbox').html("Confirmation");
	      $('#confirmText').text('Are you sure you want to apply leave?');
	      $('#confirmButtons').html('<button type="button" class="btn btn-success" onclick="confirmYes();">Yes</button>' +
	                    			'<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>');
	      $('#confirmModal').modal('show');
	    });
	}

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
				var edit_partial_day = result[8];
				var edit_partial_part = result[9];
				var edit_partial_time = result[10];
				var edit_leave_type_complete = edit_leave_type_id+'|'+edit_leave_type;

				if (edit_partial_day != '1'){	
				    $('input[name=partial_part]:checked').prop( 'disabled', false );
					$('input[name=partial_part]').prop( 'disabled', false );
				    $('#partial_time').val('');
				    $('#no_hrs_of_leave').val('');
				    $('#end_day_of_leave').prop( 'disabled', false );
				    $('#total_days').show();
				} else {
					$('input[name=partial_part]:checked').prop( 'disabled', false );
					$('input[name=partial_part]').prop( 'disabled', false );
					$('#end_day_of_leave').prop( 'disabled', true );
					$('#total_days').hide();
				}

				$('#partial_day').click(function(){

					if($('#partial_day').prop('checked') != true){

						start_date = $('#start_day_of_leave').val();
						var s = formatDatetoMMDDYYYY(start_date);

						end_date = $('#end_day_of_leave').val();
						var e = formatDatetoMMDDYYYY(end_date);

						if (start_date != '' && end_date != ''){

							DateReturn(e);
							var total_days = TotalDaysAway(s, e);
							// var minuspublicHolidays = minuspublicHoliday(s, e);
							// total_days_away = total_days - minuspublicHolidays;
							total_days_away = total_days;
						
							var total_days_away_whole = total_days_away;
							$('#total_days_away').text(total_days_away_whole);
						}
					}
				});

				if (for_edit_user_id_page != ''){
					switch (edit_leave_type_id) {
					    case '1':
					        leave_remaining = leave_remaining3_annual;
					        break;
					    case '2':
					    	leave_remaining = leave_remaining3_personal;
					        break;
					    case '3':
					        leave_remaining = leave_remaining3_personal;
					        break;
					    case '4':
					    	leave_remaining = leave_remaining3_personal;
					        break;
					    case '5':
					    	leave_remaining = '-';
					        break;
					    case '6':
					    	leave_remaining = '-';
					        break;
					    default:
					    	leave_remaining = '0';
					        break;
					}
				} else {
					switch (edit_leave_type_id) {
					    case '1':
					        leave_remaining = leave_remaining1_annual;
					        break;
					    case '2':
					    	leave_remaining = leave_remaining1_personal;
					        break;
					    case '3':
					        leave_remaining = leave_remaining1_personal;
					        break;
					    case '4':
					    	leave_remaining = leave_remaining1_personal;
					        break;
					    case '5':
					    	leave_remaining = '-';
					        break;
					    case '6':
					    	leave_remaining = '-';
					        break;
					    default:
					    	leave_remaining = '0';
					        break;
					}
				}

				$('#s2id_leave_type span.select2-chosen').text(edit_leave_type);
				$('#leave_type').val(edit_leave_type_complete);
				$('#total_leave').text(leave_remaining);
				$('#start_day_of_leave').val(edit_start_day);
				$('#end_day_of_leave').val(edit_end_day);
				$('#leave_details').val(edit_purpose);

				if (edit_partial_day == 1){

					$('#partial_day').prop('checked', true);
					is_offshore_disable_this(edit_leave_type_id);
					$('#partial_day_wrap').show();
					$('.partial_part[value="'+edit_partial_part+'"]').prop('checked', true);
					$('#partial_time').val(edit_partial_time);

					var s = formatDatetoMMDDYYYY(edit_start_day);
					
					if ($('input[name=partial_part]:checked').val() == 2) {
						DateReturn(s);
					} else {
						$('#date_return').text(edit_start_day);
					}

					$('input[name=partial_part]').click(function(){
						if ($('input[name=partial_part]:checked').val() == 2) {
							DateReturn(s);
						} else {
							$('#date_return').text(edit_start_day);
						}
					});

					var get_hrs = edit_total_days.substr(0, 1);
					var get_mins = edit_total_days.substr(2);

					switch (get_mins) {
					    case '25':
					        var edit_no_hrs_of_leave = '0'+get_hrs + ':15';
					        break;
					    case '50':
					    	var edit_no_hrs_of_leave = '0'+get_hrs + ':30';
					        break;
					    case '75':
					        var edit_no_hrs_of_leave = '0'+get_hrs + ':45';
					        break;
					    default:
					    	var edit_no_hrs_of_leave = '0'+get_hrs + ':00';
					        break;
					}

					$('#no_hrs_of_leave').val(edit_no_hrs_of_leave);
					
				} else {
					$('#date_return').text(edit_date_return);
					$('#partial_day_wrap').hide();
					$('#partial_time').prop( 'disabled', true );
					$('#no_hrs_of_leave').prop( 'disabled', true );

					$('#partial_day').prop('checked', false);
					$('#total_days_away').show();

					if (for_edit_no_hrs_of_work != ''){
						$('#total_days_away').text(+(Math.round(edit_total_days / for_edit_no_hrs_of_work + "e+2")  + "e-2"));
					} else {
						$('#total_days_away').text(+(Math.round(edit_total_days / no_hrs_of_work + "e+2")  + "e-2"));
					}
				}

				$('#leave_modal_button').html('<div class="pull-left">' +
								      		  '<button type="button" id="btnDeleteLeave" class="btn btn-danger">Delete</button>' +
								      		  '</div>' +
								      	      '<div class="pull-right">' +
									          '<button type="button" id="btnCloseUpdate" class="btn btn-warning" data-dismiss="modal">Close</button>' +
									          '<button type="button" id="btnUpdateLeaveReq" class="btn btn-success">Update Leave</button>' +
								       	      '</div>');
			    $('#leave_modal').modal('show');

			    if (user_state == 6){
			    	setTheCalendars('nsw');
			    } else {
			    	setTheCalendars('wa');
			    }

				$('#btnDeleteLeave').click(function(){

					ajax_data('','users/inactive_leave_req/'+edit_leave_req_id,'');
					alert('You have successfully deleted a leave request!');
					$('#edit_leave_modal').modal('hide');

					if (window.location.href.split('#')[0] == '<?php echo base_url().'users/leave_details/'.$this->session->userdata('user_id'); ?>'){
						location.reload();
					}
				});

				$('#btnUpdateLeaveReq').click(function(){

					if ($('#partial_day').prop('checked') == true) {
						var cur_partial_day = '1';
					} else {
						var cur_partial_day = '0';
					}

					if (edit_leave_type_complete != $('#leave_type').val() || edit_start_day != $('#start_day_of_leave').val() || edit_end_day != $('#end_day_of_leave').val() || edit_date_return != $('#date_return').text() || edit_purpose != $('#leave_details').val()){

						var start_date = formatDatetoMMDDYYYY($('#start_day_of_leave').val());
						var end_date = formatDatetoMMDDYYYY($('#end_day_of_leave').val());
						var check_start_date = new Date(start_date);
						var check_end_date = new Date(end_date);

						if (check_start_date > check_end_date){
							$('h4#myModalLabel.modal-title.msgbox').html("Message Box");
							$('#confirmText').text('Please select correct start date and end date.');
							$('#confirmButtons').html('<button type="button" class="btn btn-warning" data-dismiss="modal">Ok</button>');
							$('#confirmModal').modal('show');
						} else {
							$('h4#myModalLabel.modal-title.msgbox').html("Confirmation");
							$('#confirmText').text('Are you sure you want to update your leave?');
							$('#confirmButtons').html('<button type="button" class="btn btn-success" onclick="confirmUpdate();">Yes</button>' +
													  '<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>');
							$('#confirmModal').modal('show');
						}

					} else {

						if (edit_partial_day == 1 || cur_partial_day == 1){

							if (edit_partial_day != cur_partial_day || edit_partial_part != $('input[name=partial_part]:checked').val() || edit_partial_time != $('#partial_time').val() || edit_no_hrs_of_leave != $('#no_hrs_of_leave').val()){
								$('h4#myModalLabel.modal-title.msgbox').html("Confirmation");
								$('#confirmText').text('Are you sure you want to update your leave?');
								$('#confirmButtons').html('<button type="button" class="btn btn-success" onclick="confirmUpdate();">Yes</button>' +
														  '<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>');
								$('#confirmModal').modal('show');

							} else {
								alert('No changes has been made. 1');
								$('#leave_modal').modal('hide');
							}

						} else {
							alert('No changes has been made. 2');
							$('#leave_modal').modal('hide');
						}
					}
				});
	        }
	    });
	}

	function setTheCalendars(state){

		if (state == 'wa'){
			// loop for good friday and easter monday
			for (var i = current_year; i <= limit_year; i++) {

				easter_monday = new Date(generateGoodFriday(i));
				easter_monday = easter_monday.setDate(easter_monday.getDate() + 3);
				easter_monday = moment(easter_monday).format('YYYY-MM-DD');

				if (i > current_year){
					publicHolidays.push(i+'-01-01', i+'-01-26', i+'-04-25', i+'-12-25', i+'-12-26', generateFirstMonday(i, '03'), generateGoodFriday(i), easter_monday, generateFirstMonday(i, '06'), generatelastMonday(i, '09'));
				} else {
					publicHolidays = [i+'-01-01', i+'-01-02', i+'-01-26', i+'-04-25', i+'-12-25', i+'-12-26', generateFirstMonday(i, '03'), generateGoodFriday(i), easter_monday, generateFirstMonday(i, '06'), generatelastMonday(i, '09')];	
				}
			}
		}

		if (state == 'nsw'){
			// loop for good friday and easter monday
			for (var i = current_year; i <= limit_year; i++) {

				easter_monday = new Date(generateGoodFriday(i));
				easter_monday = easter_monday.setDate(easter_monday.getDate() + 3);
				easter_monday = moment(easter_monday).format('YYYY-MM-DD');

				if (i > current_year){
					publicHolidays.push(i+'-01-01', i+'-01-26', i+'-04-25', i+'-12-25', i+'-12-26', generateGoodFriday(i), easter_monday, generateSecondMonday(i, '06'), generateFirstMonday(i, '10'));
				} else {
					publicHolidays = [i+'-01-01', i+'-01-02', i+'-01-26', i+'-04-25', i+'-12-25', i+'-12-26', generateGoodFriday(i), easter_monday, generateSecondMonday(i, '06'), generateFirstMonday(i, '10')];	
				}
			}
		}

		// function for generating the 1st mondays
		function generateFirstMonday(current_year, month){
			month = new Date(current_year+'-'+month+'-01');
			monthDay = month.getDay();

			if (monthDay == 1 ){
				// monday
				first_monday = moment(month).format('YYYY-MM-DD');
				return first_monday;
			} else if (monthDay == 0) {
				// sunday
				first_monday = month.setDate(month.getDate() + 1);
				first_monday = new Date(first_monday);
				first_monday = moment(first_monday).format('YYYY-MM-DD');
				return first_monday;
			} else {
				// other day except monday and sunday
				while (monthDay > 1){
					first_monday = month.setDate(month.getDate() + 1);
					first_monday = new Date(first_monday);
					firstMonDay = first_monday.getDay();

					if (firstMonDay == 1){
						first_monday = moment(first_monday).format('YYYY-MM-DD');
						return first_monday;
						break;
					}
				}
			}
		}

		// function for generating the 2nd monday
		function generateSecondMonday(current_year, month){
			month = new Date(current_year+'-'+month+'-01');
			monthDay = month.getDay();

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
			second_monday = moment(second_monday).format('YYYY-MM-DD');

			return second_monday;
		}

		function generatelastMonday(current_year, month){
			month = new Date(current_year+'-'+month+'-30');
			monthDay = month.getDay();

			if (monthDay == 1 ){
				// monday
				last_monday = moment(month).format('YYYY-MM-DD');
				return last_monday;
			} else if (monthDay == 0) {
				// sunday
				last_monday = month.setDate(month.getDate() - 6);
				last_monday = new Date(last_monday);
				last_monday = moment(last_monday).format('YYYY-MM-DD');
				return last_monday;
			} else {
				// other day except monday and sunday
				while (monthDay > 1){
					last_monday = month.setDate(month.getDate() - 1);
					last_monday = new Date(last_monday);
					lastMonDay = last_monday.getDay();

					if (lastMonDay == 1){
						last_monday = moment(last_monday).format('YYYY-MM-DD');
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
		        full_moon_date = current_year+'-03-27';
		        break;
		    case 1:
		        full_moon_date = current_year+'-04-14';
		        break;
		    case 2:
		        full_moon_date = current_year+'-04-03';
		        break;
		    case 3:
		        full_moon_date = current_year+'-03-23';
		        break;
		    case 4:
		        full_moon_date = current_year+'-04-11';
		        break;
		    case 5:
		        full_moon_date = current_year+'-03-31';
		        break;
		    case 6:
		        full_moon_date = current_year+'-04-18';
		        break;
		    case 7:
		        full_moon_date = current_year+'-04-08';
		        break;
		    case 8:
		        full_moon_date = current_year+'-03-28';
		        break;
		    case 9:
		        full_moon_date = current_year+'-04-16';
		        break;
		    case 10:
		        full_moon_date = current_year+'-04-05';
		        break;
		    case 11:
		        full_moon_date = current_year+'-03-25';
		        break;
		    case 12:
		        full_moon_date = current_year+'-04-13';
		        break;
		    case 13:
		        full_moon_date = current_year+'-04-02';
		        break;
		    case 14:
		        full_moon_date = current_year+'-03-22';
		        break;
		    case 15:
		        full_moon_date = current_year+'-04-10';
		        break;
		    case 16:
		        full_moon_date = current_year+'-03-30';
		        break;
		    case 17:
		        full_moon_date = current_year+'-04-17';
		        break;
		    case 18:
		        full_moon_date = current_year+'-04-07';
		        break;
		    case 19:
		        full_moon_date = current_year+'-03-27';
		        break;
			} 

			full_moon_date = new Date(full_moon_date);

			if (full_moon_date.getDay() < 5){
				while (full_moon_date.getDay() < 5){
					good_friday = full_moon_date.setDate(full_moon_date.getDate() + 1);

					good_friday = new Date(good_friday);
						
					if (good_friday.getDay() == 5){
						good_friday = moment(good_friday).format('YYYY-MM-DD');
						return good_friday;
						break;
					}
				}
			} else if (full_moon_date.getDay() > 5){
				while (full_moon_date.getDay() > 5){
					good_friday = full_moon_date.setDate(full_moon_date.getDate() - 1);
					good_friday = new Date(good_friday);
					
					if (good_friday.getDay() == 5){
						good_friday = moment(good_friday).format('YYYY-MM-DD');
						return good_friday;
						break;
					}
				}
			} else {
				good_friday = new Date(good_friday);
				good_friday = moment(good_friday).format('YYYY-MM-DD');
				return good_friday;
			}
		}

		$('#partial_day').click(function(){

			if ($('#partial_day').is(':checked')){

				$('#partial_day_wrap').show();
				$('#total_days').hide();

				$('#end_day_of_leave').prop( 'disabled', true );
				$('.partial_part').prop( 'disabled', false );

				var leave_type = $('#leave_type').val();		
				var leave_type_name = leave_type.substr(2);
				var leave_type_id = leave_type.substr(0, 1);

				if (leave_user_id == ''){

					if (is_offshore == 1 && leave_type_id < 5){
						var diff_total_leave = leave_remaining - 0.5;
						$('#total_leave').text(diff_total_leave);
					}

				} else {

					if (user_id_page_is_offshore == 1 && leave_type_id < 5){
						var diff_total_leave = leave_remaining - 0.5;
						$('#total_leave').text(diff_total_leave);
					}

				}

				// if (leave_type_id < 5){
					
					if (leave_user_id == ''){

						if (is_offshore == 1){

							$('h4#myModalLabel.modal-title.msgbox').html("Confirmation");
							$('#confirmText').html('Is this Philippine Public Holiday?');
						    $('#confirmButtons').html('<button type="button" class="btn btn-warning" onclick="minusHolidays_half('+leave_type_id+');">Yes</button>' +
						    			              '<button type="button" class="btn btn-success" onclick="clearPH_holiday();">No</button>');
						    $('#confirmModal').modal({backdrop: true, keyboard: false, show: true});
							$('#confirmModal').data('bs.modal').options.backdrop = 'static';

						}

					} else {

						if (user_id_page_is_offshore == 1){

							$('h4#myModalLabel.modal-title.msgbox').html("Confirmation");
							$('#confirmText').html('Is this Philippine Public Holiday?');
						    $('#confirmButtons').html('<button type="button" class="btn btn-warning" onclick="minusHolidays_half('+leave_type_id+');">Yes</button>' +
						    			              '<button type="button" class="btn btn-success" onclick="clearPH_holiday();">No</button>');
						    $('#confirmModal').modal({backdrop: true, keyboard: false, show: true});
							$('#confirmModal').data('bs.modal').options.backdrop = 'static';

						}

					}

					// else {

					// 	$('h4#myModalLabel.modal-title.msgbox').html("Confirmation");
					// 	$('#confirmText').html('Is this an Australian Public Holiday?');
					//     $('#confirmButtons').html('<button type="button" class="btn btn-warning" onclick="minusHolidays_partial('+leave_type_id+');">Yes</button>' +
					//     			              '<button type="button" class="btn btn-success" onclick="clearPH_holiday();">No</button>');
					//     $('#confirmModal').modal({backdrop: true, keyboard: false, show: true});
					// 	$('#confirmModal').data('bs.modal').options.backdrop = 'static';
					// }
				// }

				set_partial_day_return();

			} else {

				$('#partial_day_wrap').hide();
				$('#total_days').show();

				$('#end_day_of_leave').val('');
				$('#end_day_of_leave').prop( 'disabled', false );
				$('#date_return').text('');
				$('#leave_details').val('');
				$('#total_leave').text(leave_remaining);
				$('#total_holiday').text('0');
			}
		});

		$('input[name=partial_part]').click(function(){
			if ($('input[name=partial_part]:checked').val() === '2'){
				$('span.toggle_time').html('Out'); 
			} else {
				$('span.toggle_time').html('In');
			}
		});

		$('#start_day_datepicker').datetimepicker({ 
			format: 'DD/MM/YYYY',
			useCurrent: false,
			daysOfWeekDisabled: [0, 6]
		}).on('dp.change', function (s) {	

			$('#end_day_datepicker').data('DateTimePicker').minDate(s.date);

			var leave_type = $('#leave_type').val();		
			var leave_type_name = leave_type.substr(2);
			var leave_type_id = leave_type.substr(0, 1);
			var format_start_date = moment(s.date).format('DD/MM/YYYY');
			var apply_date = moment(s.date).format('MM/DD/YYYY');

			if (leave_user_id == ''){
				var data = logged_in_user_id+'|'+apply_date;
			} else {
				var data = leave_user_id+'|'+apply_date;
			}

			$.ajax({
			   'url' : "<?php echo base_url().'users/fetch_all_leave_dates'; ?>",
			   'type' : 'POST',
			   'data' : { 'ajax_var' : data },
			   'dataType': 'html',
			   'success' : function(result)		 
			   {
			   	
			   	if (result == 1){
		   			$('#confirmModal').modal('show');
			   		$('h4#myModalLabel.modal-title.msgbox').html("Can't proceed with Leave Application");
					$('#confirmText').html('Chosen date is having a current leave in it. Please choose another.');
				    $('#confirmButtons').html('<button type="button" class="btn btn-warning" data-dismiss="modal">Try Again</button>'); // onclick="clearLeaveReqForm();"

				    $('#start_day_of_leave').val('');
				    $('#start_day_datepicker').data().DateTimePicker.date(null);

			   	} else {

			   		if ($('#start_day_of_leave') != ''){
			   			if ($('#partial_day').prop('checked') == true) {

							$('#end_day_of_leave').val(format_start_date);

							if ($('input[name=partial_part]:checked').val() == 2) {
								DateReturn(s.date);
							} else {
								$('#date_return').text(format_start_date);
							}

							$('input[name=partial_part]').click(function(){
								if ($('input[name=partial_part]:checked').val() == 2) {
									DateReturn(s.date);
								} else {
									$('#date_return').text(format_start_date);
								}
							});

							if (leave_type_id < 5){
								leave_application_exceed_msg(leave_type_id, '4.0');
							}

							is_offshore_disable_this(leave_type_id);

						}
			   		}
			   	}
			   }
			});
		});

		$('#end_day_datepicker').datetimepicker({ 
			format: 'DD/MM/YYYY',
			useCurrent: false,
			daysOfWeekDisabled: [0, 6]
		}).on('dp.change', function (e) {

			var leave_type = $('#leave_type').val();		
			var leave_type_name = leave_type.substr(2);
			var leave_type_id = leave_type.substr(0, 1);
			var start_date = $('#start_day_of_leave').val();
			var s = formatDatetoMMDDYYYY(start_date);

			var apply_date = moment(e.date).format('MM/DD/YYYY');

			if (leave_user_id == ''){
				var data1 = logged_in_user_id+'|'+apply_date;
			} else {
				var data1 = leave_user_id+'|'+apply_date;
			}
			
			$.ajax({
			   'url' : "<?php echo base_url().'users/fetch_all_leave_dates'; ?>",
			   'type' : 'POST',
			   'data' : { 'ajax_var' : data1 },
			   'dataType': 'html',
			   'success' : function(result)		 
			   {
			   	if (result == 1){
		   			$('#confirmModal').modal('show');
			   		$('h4#myModalLabel.modal-title.msgbox').html("Can't proceed with Leave Application");
					$('#confirmText').html('Chosen date is having a current leave in it. Please choose another.');
				    $('#confirmButtons').html('<button type="button" class="btn btn-warning" data-dismiss="modal">Try Again</button>'); // onclick="clearLeaveReqForm();"

				    $('#end_day_of_leave').val('');
				    $('#end_day_datepicker').data().DateTimePicker.date(null);
			   	} else {

			   		if ($('#end_day_of_leave').val() != ''){

						DateReturn(e.date);
						var total_days = TotalDaysAway(s, e.date);
						// var minuspublicHolidays = minuspublicHoliday(s, e.date);
						// total_days_away = total_days - minuspublicHolidays;
						total_days_away = total_days;

						if (leave_user_id == ''){

							//if (leave_type_id < 5) {
								if (is_offshore == 1){

									if (s == convertDate(e.date)){
										$('h4#myModalLabel.modal-title.msgbox').html("Confirmation");
										$('#confirmText').html('Is this Philippine Public Holiday?');
									    $('#confirmButtons').html('<button type="button" class="btn btn-warning" onclick="minusPH_holidays_one('+leave_type_id+');">Yes</button>' +
									    			              '<button type="button" class="btn btn-success" onclick="clearPH_holiday();">No</button>');
									    $('#confirmModal').modal({backdrop: true, keyboard: false, show: true});
										$('#confirmModal').data('bs.modal').options.backdrop = 'static';
									} else {
										$('h4#myModalLabel.modal-title.msgbox').html("Confirmation");
										$('#confirmText').text('Does this include any Philippine/Australian Public Holidays?');
									    $('#confirmButtons').html('<button type="button" class="btn btn-warning" onclick="PH_holiday();">Yes</button>' +
									    			              '<button type="button" class="btn btn-success" onclick="clearPH_holiday();">No</button>');
									    $('#confirmModal').modal({backdrop: true, keyboard: false, show: true});
										$('#confirmModal').data('bs.modal').options.backdrop = 'static';
									}

								} else {

									if (s != convertDate(e.date)){
										$('h4#myModalLabel.modal-title.msgbox').html("Confirmation");
										$('#confirmText').text('Does this include any Australian Public Holidays?');
									    $('#confirmButtons').html('<button type="button" class="btn btn-warning" onclick="PH_holiday();">Yes</button>' +
									    			              '<button type="button" class="btn btn-success" onclick="clearPH_holiday();">No</button>');
									    $('#confirmModal').modal({backdrop: true, keyboard: false, show: true});
										$('#confirmModal').data('bs.modal').options.backdrop = 'static';
									}
								}
							//}

							var data2 = '';
							var data2 = logged_in_user_id+'|'+leave_type_id+'|'+no_hrs_of_work;

							$.ajax({
							   'url' : "<?php echo base_url().'users/check_pending_leave_count'; ?>",
							   'type' : 'POST',
							   'data' : { 'ajax_var' : data2 },
							   'dataType': 'html',
							   'success' : function(result)		 
							   {
							   	if (result == ''){
							   		pending_leave_count = 0
							   	} else {
							   		pending_leave_count = result / no_hrs_of_work;	
							   	}

							   	var pending_leave_remaining = leave_remaining - pending_leave_count;

							   	if (leave_type_id == 1){

							   		var total_leave_remaining = leave_remaining - total_days_away;

							   		if(total_leave_remaining >= -5){
							   		} else {
							   			if (total_days_away > pending_leave_remaining){
									   		$('#confirmModal').modal('show');
									   		$('h4#myModalLabel.modal-title.msgbox').html("Can't proceed with Leave Application");
											$('#confirmText').html('Insufficient leave credit due to the pending leave request.');
										    $('#confirmButtons').html('<button type="button" class="btn btn-warning" onclick="clearLeaveReqForm();">Try Again</button>');   
									   	}
							   		}

							   	} else {
							   		if (total_days_away > pending_leave_remaining){
								   		$('#confirmModal').modal('show');
								   		$('h4#myModalLabel.modal-title.msgbox').html("Can't proceed with Leave Application");
										$('#confirmText').html('Insufficient leave credit due to the pending leave request.');
									    $('#confirmButtons').html('<button type="button" class="btn btn-warning" onclick="clearLeaveReqForm();">Try Again</button>');   
								   	}
							   	}
							   }
							});

						} else {

							if (leave_type_id < 5) {

								if (user_id_page_is_offshore == 1){

									if (s == convertDate(e.date)){
										$('h4#myModalLabel.modal-title.msgbox').html("Confirmation");
										$('#confirmText').html('Is this Philippine Public Holiday?');
									    $('#confirmButtons').html('<button type="button" class="btn btn-warning" onclick="minusPH_holidays_one('+leave_type_id+');">Yes</button>' +
									    			              '<button type="button" class="btn btn-success" onclick="clearPH_holiday();">No</button>');
									    $('#confirmModal').modal({backdrop: true, keyboard: false, show: true});
										$('#confirmModal').data('bs.modal').options.backdrop = 'static';
									} else {
										$('h4#myModalLabel.modal-title.msgbox').html("Confirmation");
										$('#confirmText').text('Does this include any Philippine/Australian Public Holidays?');
									    $('#confirmButtons').html('<button type="button" class="btn btn-warning" onclick="PH_holiday();">Yes</button>' +
									    			              '<button type="button" class="btn btn-success" onclick="clearPH_holiday();">No</button>');
									    $('#confirmModal').modal({backdrop: true, keyboard: false, show: true});
										$('#confirmModal').data('bs.modal').options.backdrop = 'static';
									}

								} else {

									if (s != convertDate(e.date)){
										$('h4#myModalLabel.modal-title.msgbox').html("Confirmation");
										$('#confirmText').text('Does this include any Australian Public Holidays?');
									    $('#confirmButtons').html('<button type="button" class="btn btn-warning" onclick="PH_holiday();">Yes</button>' +
									    			              '<button type="button" class="btn btn-success" onclick="clearPH_holiday();">No</button>');
									    $('#confirmModal').modal({backdrop: true, keyboard: false, show: true});
										$('#confirmModal').data('bs.modal').options.backdrop = 'static';
									}
								}
							}

							var data3 = '';
							data3 = leave_user_id+'|'+leave_type_id+'|'+user_id_page_no_hrs_of_work;

							$.ajax({
							   'url' : "<?php echo base_url().'users/check_pending_leave_count'; ?>",
							   'type' : 'POST',
							   'data' : { 'ajax_var' : data3 },
							   'dataType': 'html',
							   'success' : function(result)		 
							   {
							   	if (result == ''){
							   		pending_leave_count = 0
							   	} else {
							   		pending_leave_count = result / user_id_page_no_hrs_of_work;	
							   	}

							   	var pending_leave_remaining = leave_remaining - pending_leave_count;

							   	if (leave_type_id == 1){

							   		var total_leave_remaining = leave_remaining - total_days_away;

							   		if(total_leave_remaining >= -5){
							   		} else {
							   			if (total_days_away > pending_leave_remaining){
									   		$('#confirmModal').modal('show');
									   		$('h4#myModalLabel.modal-title.msgbox').html("Can't proceed with Leave Application");
											$('#confirmText').html('Insufficient leave credit due to the pending leave request. 1');
										    $('#confirmButtons').html('<button type="button" class="btn btn-warning" onclick="clearLeaveReqForm();">Try Again</button>');   
									   	}
							   		}

							   	} else {
							   		if (total_days_away > pending_leave_remaining){
								   		$('#confirmModal').modal('show');
								   		$('h4#myModalLabel.modal-title.msgbox').html("Can't proceed with Leave Application");
										$('#confirmText').html('Insufficient leave credit due to the pending leave request. 1');
									    $('#confirmButtons').html('<button type="button" class="btn btn-warning" onclick="clearLeaveReqForm();">Try Again</button>');   
								   	}
							   	}
							   }
							});

							if (user_id_page_is_offshore == 1 && e.date != false){

								if (s != convertDate(e.date)){
									$('h4#myModalLabel.modal-title.msgbox').html("Confirmation");
									$('#confirmText').text('Does this include any Philippine/Australian Public Holidays?');
								    $('#confirmButtons').html('<button type="button" class="btn btn-warning" onclick="PH_holiday();">Yes</button>' +
								    			              '<button type="button" class="btn btn-success" onclick="clearPH_holiday();">No</button>');
								    $('#confirmModal').modal({backdrop: true, keyboard: false, show: true});
									$('#confirmModal').data('bs.modal').options.backdrop = 'static';
								} else {
									$('#leave_details').val('');
								}
							}
						}			

						if (leave_type_id >= '5'){
							total_leave_remaining = '-';
						} else {
							var total_leave_remaining = leave_remaining - total_days_away;
							var rounded_total_leave = parseFloat(Math.round(total_leave_remaining * 100) / 100).toFixed(2);
							
							$('#total_leave').text(rounded_total_leave);	
						}

						if (leave_type_id == 1 && +(total_leave_remaining) >= -5){
							$('#total_days_away').text(total_days_away);
							$('#total_leave').text(rounded_total_leave);	
						} else {

							if (+(total_leave_remaining) < 0 && leave_type_id < '5') {

								$('h4#myModalLabel.modal-title.msgbox').html("Can't proceed with the leave application.");
								$('#confirmText').html('Leave Application exceed with your Total Leave Remaining. You can apply it as an Unpaid Leave.');
								$('#confirmButtons').html('<button type="button" class="btn btn-warning" data-dismiss="modal">Ok</button>');
								$('#confirmModal').modal('show');
								
								$('#start_day_of_leave').val('');				
								$('#end_day_of_leave').val('');
								$('#start_day_datepicker').data().DateTimePicker.date(null);
								$('#end_day_datepicker').data().DateTimePicker.date(null);
								$('#total_leave').text(leave_remaining);
								$('#date_return').text('');
								$('#total_days_away').text('');
							} else {
								$('#end_day_datepicker').data('DateTimePicker').minDate(e.date);
								$('#start_day_datepicker').data('DateTimePicker').maxDate(e.date);
								
								if($('#partial_day').prop('checked') != true){
									var total_days_away_whole = total_days_away;
									$('#total_days_away').text(total_days_away_whole);
								}
							}
						}
					}
			   	}
			   }
			});

			
		});
	}

	function convertDate(inputFormat) {
	  function pad(s) { return (s < 10) ? '0' + s : s; }
	  var d = new Date(inputFormat);
	  return [pad(d.getMonth()+1), pad(d.getDate()), d.getFullYear()].join('/');
	}

	function PH_holiday(){
		$('#confirmModal').modal('hide');
		$('#PH_holiday').modal('show');
	}

	function clearPH_holiday(){
		ph_holidays = 0;

		$('#confirmModal').modal('hide');
		$('#PH_holiday').modal('hide');

		$('#leave_details').val('');
		$('#leave_details').focus();
	}

	function minusHolidays_half(leave_type_id){

		ph_holidays = 0.5;
		var ph_total_days_away = $('#total_days_away').text();
		var ph_total_leave = $('#total_leave').text();

		$('#confirmModal').modal('hide');
		$('#leave_details').focus();

		if (leave_type_id >= 5) {
			$('#total_leave').text('-');
			$('#total_holiday').text(ph_holidays);
		} else {
			var ph_total_leave_sum = +ph_total_leave + +ph_holidays;
			$('#total_leave').text(ph_total_leave_sum);
			$('#total_holiday').text(ph_holidays);
		}
	}

	function minusPH_holidays_one(leave_type_id){

		ph_holidays = 1;
		var ph_total_days_away = $('#total_days_away').text();
		var ph_total_leave = $('#total_leave').text();

		$('#confirmModal').modal('hide');
		$('#leave_details').focus();

		if (leave_type_id >= 5) {
			$('#total_leave').text('-');
			$('#total_holiday').text(ph_holidays);
		} else {
			var ph_total_leave_sum = +ph_total_leave + +ph_holidays;
			$('#total_leave').text(ph_total_leave_sum);
			$('#total_holiday').text(ph_holidays);
		}
	}

	function minusPH_holidays(){
		ph_holidays = $('#PH_holidayInput').val();
		var ph_total_days_away = $('#total_days_away').text();
		var ph_total_leave = $('#total_leave').text();
		var ph_total_days_diff = ph_total_days_away - ph_holidays;

		if (leave_type_id >= 5) {
			if (ph_total_days_diff < 0){
				$('#PH_holiday').modal('hide');
				$('#confirmText').text('Numbers are exceeded with the Total Days of Leave.');
			    $('#confirmButtons').html('<button type="button" class="btn btn-warning" onclick="PH_holiday();">Try Again</button>');
			    $('#confirmModal').modal('show');
			} else {
				$('#PH_holiday').modal('hide');
				$('#total_leave').text('-');
				$('#total_holiday').text(ph_holidays);
				$('#leave_details').focus();
			}
		} else {
			if (ph_total_days_diff < 0){
				$('#PH_holiday').modal('hide');
				$('#confirmText').text('Numbers are exceeded with the Total Days of Leave.');
			    $('#confirmButtons').html('<button type="button" class="btn btn-warning" onclick="PH_holiday();">Try Again</button>');
			    $('#confirmModal').modal('show');
			} else {

				var ph_total_leave_sum = +ph_total_leave + +ph_holidays;

				$('#PH_holiday').modal('hide');
				$('#total_leave').text(ph_total_leave_sum);
				$('#total_holiday').text(ph_holidays);
				$('#leave_details').focus();
			}
		}
	}

	function DateReturn(e){
		e = new Date(e);
	    var date_return = new Date(e.setDate(e.getDate() + 1));
	    var dr_formatted = '';
	    var new_dr = '';
	    
	    if (date_return.getDay() == 6){
	    	date_return = new Date(e.setDate(e.getDate() + 2));
	    	dr_formatted = moment(date_return).format('YYYY-MM-DD'); // for weekend
	    } else {
	    	dr_formatted = moment(date_return).format('YYYY-MM-DD'); // for not weekend
	    }

    	while(checkifHoliday(dr_formatted) == true){	
	    	new_dr = new Date(dr_formatted);
	    	new_dr = new Date(new_dr.setDate(new_dr.getDate() + 1));

	    	if (new_dr.getDay() == 6){
		    	new_dr = new Date(new_dr.setDate(new_dr.getDate() + 2));

		    	dr_formatted = moment(new_dr).format('YYYY-MM-DD');
		    	var dr_not_weekend_hol = new Date(dr_formatted);
		    	dr_not_weekend_hol = moment(dr_not_weekend_hol).format('DD/MM/YYYY');
		    	$('#date_return').text(dr_not_weekend_hol);
		    } else {
		    	dr_formatted = moment(new_dr).format('YYYY-MM-DD');
		    	var dr_weekdays_hol = moment(dr_formatted).format('DD/MM/YYYY');
		    	$('#date_return').text(dr_weekdays_hol);
		    }
	    }

	    if(checkifHoliday(dr_formatted) != true){
			new_dr = new Date(dr_formatted);
			new_dr = moment(new_dr).format('DD/MM/YYYY');
    		$('#date_return').text(new_dr);
	    }
	}

	function TotalDaysAway(s, e){

		start_date = new Date(s);
		end_date = new Date(e);

		var start_day = moment(start_date).format('MM/DD/YYYY');
		var end_day = moment(end_date).format('MM/DD/YYYY');

	    s = new Date(start_day);
	    e = new Date(end_day);

	    // Set time to midday to avoid daylight saving and browser quirks
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

		var start_day_holiday = moment(start_date_holiday).format('MM/DD/YYYY');
		var end_day_holiday = moment(end_date_holiday).format('MM/DD/YYYY');

	    var ss = new Date(start_day_holiday);
	    var es = new Date(end_day_holiday);

	    while (ss < es) {
	    	publicHolidays.forEach(checkBetween);
  			ss.setDate(ss.getDate() + 1);
  		}

	    function checkBetween(item){
			if(item == moment(ss).format('YYYY-MM-DD')){
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

    function formatDatetoMMDDYYYY(date){
		var day = date.substr(0, 2);
		var month = date.substr(3, 2);
		var year = date.substr(6, 4);

		var date_formatted = month + '/' + day + '/' + year;

		return date_formatted;
	}

	function is_offshore_disable_this(leave_type_id){
		
		if (is_offshore == 1){

			if (user_id_page_is_offshore == 1 && leave_type_id < 5){

				$('#partial_time').prop( 'disabled', true );
				$('#partial_time').val('12:00 PM');
				$('#no_hrs_of_leave').prop( 'disabled', true );
				$('#no_hrs_of_leave').val('04:00');

			} else if (for_edit_is_offshore == 1 && leave_type_id < 5) {

				$('#partial_time').prop( 'disabled', true );
				$('#partial_time').val('12:00 PM');
				$('#no_hrs_of_leave').prop( 'disabled', true );
				$('#no_hrs_of_leave').val('04:00');

			} else if (leave_user_id == '' && leave_type_id < 5) {

				if (for_edit_is_offshore == 0){

					$('#partial_time').prop( 'disabled', false );
					$('#no_hrs_of_leave').prop( 'disabled', false );
					$('#total_days').hide();

				} else {

					$('#partial_time').prop( 'disabled', true );
					$('#partial_time').val('12:00 PM');
					$('#no_hrs_of_leave').prop( 'disabled', true );
					$('#no_hrs_of_leave').val('04:00');

				}

			} else {

				$('#partial_time').prop( 'disabled', false );
				$('#no_hrs_of_leave').prop( 'disabled', false );

			}

		} else {

			if (user_id_page_is_offshore == 1 && leave_type_id < 5){

				$('#partial_time').prop( 'disabled', true );
				$('#partial_time').val('12:00 PM');
				$('#no_hrs_of_leave').prop( 'disabled', true );
				$('#no_hrs_of_leave').val('04:00');

			} else if (for_edit_is_offshore == 1 && leave_type_id < 5) {

				$('#partial_time').prop( 'disabled', true );
				$('#partial_time').val('12:00 PM');
				$('#no_hrs_of_leave').prop( 'disabled', true );
				$('#no_hrs_of_leave').val('04:00');

			} else {

				$('#partial_time').prop( 'disabled', false );
				$('#no_hrs_of_leave').prop( 'disabled', false );

			}
		}
	}

	function leave_application_exceed_msg(leave_type_id, total_hrs_leave){	

		var s_total_hrs_leave = total_hrs_leave.toString();

		if (s_total_hrs_leave.indexOf('.') == 1){
			if (user_id_page_no_hrs_of_work == '' || user_id_page_no_hrs_of_work === undefined){
				var q_total_days = +(Math.round(total_hrs_leave / no_hrs_of_work + "e+2")  + "e-2");
			} else {
				var q_total_days = +(Math.round(total_hrs_leave / user_id_page_no_hrs_of_work + "e+2")  + "e-2");
			}

			var total_leave_remaining = leave_remaining - q_total_days;

			// alert(total_leave_remaining);

			if (+(total_leave_remaining) < 0 && leave_type_id != '5') {

				$('h4#myModalLabel.modal-title.msgbox').html("Can't proceed with the leave application.");
				$('#confirmText').html('Leave Application exceed with your Total Leave Remaining. You can apply it as an Unpaid Leave.');
				$('#confirmButtons').html('<button type="button" class="btn btn-warning" data-dismiss="modal">Ok</button>');
				$('#confirmModal').modal('show');

				$('#no_hrs_of_leave').val('');
				
				$('#start_day_of_leave').val('');				
				$('#end_day_of_leave').val('');
				$('#start_day_datepicker').data().DateTimePicker.date(null);
				$('#end_day_datepicker').data().DateTimePicker.date(null);
				$('#total_leave').text(leave_remaining);
				$('#date_return').text('');
				$('#total_days_away').text('');	
			}
		} else {
			if (leave_type_id != '5'){
				var total_leave_remaining = leave_remaining - total_hrs_leave;	
			} else {
				total_leave_remaining = '-';
			}

			if (+(total_leave_remaining) < 0 && leave_type_id != '5') {

				$('h4#myModalLabel.modal-title.msgbox').html("Can't proceed with the leave application.");
				$('#confirmText').html('Leave Application exceed with your Total Leave Remaining. You can apply it as an Unpaid Leave.');
				$('#confirmButtons').html('<button type="button" class="btn btn-warning" data-dismiss="modal">Ok</button>');
				$('#confirmModal').modal('show');

				$('#start_day_of_leave').val('');				
				$('#end_day_of_leave').val('');
				$('#start_day_datepicker').data().DateTimePicker.date(null);
				$('#end_day_datepicker').data().DateTimePicker.date(null);
				$('#total_leave').text(leave_remaining);
				$('#date_return').text('');
				$('#total_days_away').text('');
			}
		}
	}

	function set_partial_day_return(){
		var start_date = $('#start_day_of_leave').val();
		$('#end_day_of_leave').val(start_date);
		var end_date = $('#end_day_of_leave').val();
		var s = formatDatetoMMDDYYYY(start_date);

		if (start_date != '' && end_date != ''){
			if ($('#partial_day').prop('checked') == true){
				
				if ($('input[name=partial_part]:checked').val() == 2) {
					DateReturn(s);
				} else {
					$('#date_return').text(start_date);
				}

				$('input[name=partial_part]').click(function(){
					if ($('input[name=partial_part]:checked').val() == 2) {
						DateReturn(s);
					} else {
						$('#date_return').text(start_date);
					}
				});
				
			} else {
				DateReturn(s);
			}
		}
	}

	function advanced_notice_for_annual(set_leave_type_id, set_total_days_away){

		var date_today = new Date();
		var possible_date = '';
		var s_set_total_days_away = set_total_days_away.toString();

		if (user_id_page_no_hrs_of_work == '' || user_id_page_no_hrs_of_work === undefined){
			var get_total_days_away = set_total_days_away / no_hrs_of_work;	
		} else {
			var get_total_days_away = set_total_days_away / user_id_page_no_hrs_of_work;
		}

		if (set_leave_type_id == 1 && leave_request_access == 0){
			if (get_total_days_away == 1 || s_set_total_days_away.indexOf('.') == 1) {
				var one_day_notice = $('#one_day_notice').val();
				var one_day_notice_msg = $('#one_day_notice_msg').val();
				var start_date = $('#start_day_of_leave').val();
				
				possible_date = date_today.setDate(date_today.getDate() + +one_day_notice);
				possible_date = moment(possible_date).format('DD/MM/YYYY');

				var start_date_formatted = formatDatetoMMDDYYYY(start_date);
				var possible_date_formatted = formatDatetoMMDDYYYY(possible_date);

				start_date_final = new Date(start_date_formatted);
				possible_date_final = new Date(possible_date_formatted);

				if (start_date_final < possible_date_final){
					$('h4#myModalLabel.modal-title.msgbox').html("Message Box");
					$('#confirmText').html(''+leave_type_name+' has advanced notice of at least '+one_day_notice+' days if you apply for '+one_day_notice_msg+' or Half Day.<br><br>Please select again:<ul><li>Start Day of Leave</li><li>End Day of Leave</li>');
					$('#confirmButtons').html('<button type="button" class="btn btn-warning" data-dismiss="modal">Ok</button>');
					$('#confirmModal').modal('show');

					$('#start_day_of_leave').val('');
					$('#end_day_of_leave').val('');

					$('#start_day_datepicker').data().DateTimePicker.date(null);
				    $('#end_day_datepicker').data().DateTimePicker.date(null);

				    $('#total_leave').text(leave_remaining);
					$('#date_return').text('');
					$('#total_days_away').text('');
					
					has_error = 1;
				} else {
					has_error = 0;
				}
			} 

			if (get_total_days_away > 1 && get_total_days_away <= 5) {
				var two_to_five_days_notice = $('#two_to_five_days_notice').val();
				var two_to_five_days_notice_msg = $('#two_to_five_days_notice_msg').val();
				var start_date = $('#start_day_of_leave').val();
				
				possible_date = date_today.setDate(date_today.getDate() + +two_to_five_days_notice);
				possible_date = moment(possible_date).format('DD/MM/YYYY');

				var start_date_formatted = formatDatetoMMDDYYYY(start_date);
				var possible_date_formatted = formatDatetoMMDDYYYY(possible_date);

				start_date_final = new Date(start_date_formatted);
				possible_date_final = new Date(possible_date_formatted);

				if (start_date_final < possible_date_final){
					$('h4#myModalLabel.modal-title.msgbox').html("Message Box");
					$('#confirmText').html(''+leave_type_name+' have notice at least '+two_to_five_days_notice+' days in advance if you apply '+two_to_five_days_notice_msg+'.<br><br>Please select again:<ul><li>Start Day of Leave</li><li>End Day of Leave</li>');
					$('#confirmButtons').html('<button type="button" class="btn btn-warning" data-dismiss="modal">Ok</button>');
					$('#confirmModal').modal('show');

					$('#start_day_of_leave').val('');
					$('#end_day_of_leave').val('');

					$('#start_day_datepicker').data().DateTimePicker.date(null);
				    $('#end_day_datepicker').data().DateTimePicker.date(null);

				    $('#total_leave').text(leave_remaining);
					$('#date_return').text('');
					$('#total_days_away').text('');
					has_error = 1;
				} else {
					has_error = 0;
				}
			}

			if (get_total_days_away > 5) {
				var six_or_more_notice = $('#six_or_more_days_notice').val();
				var six_or_more_notice_msg = $('#six_or_more_days_notice_msg').val();
				var start_date = $('#start_day_of_leave').val();
				possible_date = date_today.setDate(date_today.getDate() + +six_or_more_notice);
				possible_date = moment(possible_date).format('DD/MM/YYYY');

				var start_date_formatted = formatDatetoMMDDYYYY(start_date);
				var possible_date_formatted = formatDatetoMMDDYYYY(possible_date);

				start_date_final = new Date(start_date_formatted);
				possible_date_final = new Date(possible_date_formatted);

				if (start_date_final < possible_date_final){
					$('h4#myModalLabel.modal-title.msgbox').html("Message Box");
					$('#confirmText').html(''+leave_type_name+' have notice at least '+six_or_more_notice+' days in advance if you apply '+six_or_more_notice_msg+'.<br><br>Please select again:<ul><li>Start Day of Leave</li><li>End Day of Leave</li>');
					$('#confirmButtons').html('<button type="button" class="btn btn-warning" data-dismiss="modal">Ok</button>');
					$('#confirmModal').modal('show');

					$('#start_day_of_leave').val('');
					$('#end_day_of_leave').val('');

					$('#start_day_datepicker').data().DateTimePicker.date(null);
				    $('#end_day_datepicker').data().DateTimePicker.date(null);

				    $('#total_leave').text(leave_remaining);
					$('#date_return').text('');
					$('#total_days_away').text('');
					has_error = 1;
				} else {
					has_error = 0;
				}
			}

		} else {
			has_error = 0;
		}

		return has_error;
	}

	function confirmYes(){
		var leave_type = $('#leave_type').val();
		var start_day_of_leave = $('#start_day_of_leave').val();
		var end_day_of_leave = $('#end_day_of_leave').val();
		var date_return = $('#date_return').text();
		var leave_details = $('#leave_details').val();
		var total_hrs_leave = '';

		total_days_away = $('#total_days_away').text();
		leave_type_name= leave_type.substr(2);
		leave_type_id = leave_type.substr(0, 1);

		if (leave_type != '' && start_day_of_leave != '' && end_day_of_leave != '' && date_return != '' && leave_details != ''){

			if ($('#partial_day').is(':checked')){

				var partial_day = '1';
				var partial_part = $('input[name=partial_part]:checked').val();
				var partial_time = $('#partial_time').val();
				var no_hrs_of_leave = $('#no_hrs_of_leave').val();

				
				if (partial_time != '' && no_hrs_of_leave != ''){

					var get_hrs = no_hrs_of_leave.substr(1, 1);
					var get_mins = no_hrs_of_leave.substr(3);

					switch (get_mins) {
					    case '15':
					        total_hrs_leave = get_hrs + '.25';
					        break;
					    case '30':
					    	total_hrs_leave = get_hrs + '.50';
					        break;
					    case '45':
					        total_hrs_leave = get_hrs + '.75';
					        break;
					    default:
					    	total_hrs_leave = get_hrs + '.0';
					        break;
					}

				}  else {
					$('#confirmModal').modal('hide');
					alert('Please filled the required (*) fields');
					return false;
				}
			} else {
				if (user_id_page_no_hrs_of_work == '' || user_id_page_no_hrs_of_work === undefined){
					total_hrs_leave = total_days_away * no_hrs_of_work;	
				} else {
					total_hrs_leave = total_days_away * user_id_page_no_hrs_of_work;
				}
			}

			advanced_notice_for_annual(leave_type_id, total_hrs_leave);

			if (has_error == 0){

				if ($('#partial_day').is(':checked')){
					var data = leave_type_id+'|'+start_day_of_leave+'|'+end_day_of_leave+'|'+date_return+'|'+leave_details+'|'+total_hrs_leave+'|'+partial_day+'|'+partial_part+'|'+partial_time+'|'+ph_holidays;
				} else {
					var data = leave_type_id+'|'+start_day_of_leave+'|'+end_day_of_leave+'|'+date_return+'|'+leave_details+'|'+total_hrs_leave+'|'+'0'+'|'+'0'+'|'+''+'|'+ph_holidays;
				}

				$('#confirmModal').modal('hide');

				if (leave_user_id != ''){
					var date_applied = new Date();
					var fdate_applied = moment(date_applied).format('YYYY-MM-DD HH:MM:SS');

					data += '|'+<?php echo $this->session->userdata('user_id'); ?>+','+fdate_applied;
					ajax_data(data,'users/apply_leave/'+leave_user_id,'');
				} else {
					data += '|'+'';
					ajax_data(data,'users/apply_leave/<?php echo $this->session->userdata('user_id'); ?>','');	
				}
				
				alert('You have successfully applied a leave!');
				$('#leave_modal').modal('hide');
				
				if (window.location.href.split('#')[0] == '<?php echo base_url().'users/leave_details/'.$this->session->userdata('user_id'); ?>'){
					location.reload();
				}
			}

		} else {
			$('#confirmModal').modal('hide');
			alert('Please filled the required (*) fields');
			return false;
		}
    }

    function confirmUpdate(){
		var leave_type = $('#leave_type').val();
		var e_leave_type_id = leave_type.substr(0, 1);
		var e_start_day_of_leave = $('#start_day_of_leave').val();
		var e_end_day_of_leave = $('#end_day_of_leave').val();
		var e_date_return = $('#date_return').text();
		var e_leave_details = $('#leave_details').val();
		var e_total_days_away = $('#total_days_away').text();
		var e_total_hrs_leave = '';	
		leave_type_name= leave_type.substr(2);

		if (e_leave_type_id != '' && e_start_day_of_leave != '' && e_end_day_of_leave != '' && e_date_return != '' && e_leave_details != ''){

			if ($('#partial_day').is(':checked')){

				var e_partial_day = $('#partial_day').val();
				var e_partial_part = $('input[name=partial_part]:checked').val();
				var e_partial_time = $('#partial_time').val();
				var e_no_hrs_of_leave = $('#no_hrs_of_leave').val();

				if (e_partial_time != '' && e_no_hrs_of_leave != ''){

					var e_get_hrs = e_no_hrs_of_leave.substr(1, 1);
					var e_get_mins = e_no_hrs_of_leave.substr(3);

					switch (e_get_mins) {
					    case '15':
					        e_total_hrs_leave = e_get_hrs + '.25';
					        break;
					    case '30':
					    	e_total_hrs_leave = e_get_hrs + '.50';
					        break;
					    case '45':
					        e_total_hrs_leave = e_get_hrs + '.75';
					        break;
					    default:
					    	e_total_hrs_leave = e_get_hrs + '.0';
					        break;
					}
				} else {
					$('#confirmModal').modal('hide');
					alert('Please filled the required (*) fields');
					return false;
				}

			} else {
				if (for_edit_user_id_page == '' || for_edit_user_id_page === undefined){
					e_total_hrs_leave = e_total_days_away * no_hrs_of_work;	
				} else {
					e_total_hrs_leave = e_total_days_away * for_edit_no_hrs_of_work;
				}
			}

			advanced_notice_for_annual(e_leave_type_id, e_total_hrs_leave);

			if (has_error == 0){

				if ($('#partial_day').is(':checked')){
					var data = edit_leave_req_id+'|'+e_leave_type_id+'|'+e_start_day_of_leave+'|'+e_end_day_of_leave+'|'+e_date_return+'|'+e_leave_details+'|'+e_total_hrs_leave+'|'+e_partial_day+'|'+e_partial_part+'|'+e_partial_time;
				} else {
					if (for_edit_no_hrs_of_work != ''){
						var data = edit_leave_req_id+'|'+e_leave_type_id+'|'+e_start_day_of_leave+'|'+e_end_day_of_leave+'|'+e_date_return+'|'+e_leave_details+'|'+e_total_hrs_leave+'|'+'0'+'|'+'0'+'|'+'';
					} else {
						var data = edit_leave_req_id+'|'+e_leave_type_id+'|'+e_start_day_of_leave+'|'+e_end_day_of_leave+'|'+e_date_return+'|'+e_leave_details+'|'+e_total_hrs_leave+'|'+'0'+'|'+'0'+'|'+'';	
					}
				}

				$('#confirmModal').modal('hide');

				if (for_edit_user_id_page != ''){
					var date_edited = new Date();
					var fdate_edited = moment(date_edited).format('YYYY-MM-DD HH:MM:SS');

					data += '|'+<?php echo $this->session->userdata('user_id'); ?>+','+fdate_edited;
					ajax_data(data,'users/update_leave_req/'+for_edit_user_id_page,'');
				} else {
					var date_edited = new Date();
					var fdate_edited = moment(date_edited).format('YYYY-MM-DD HH:MM:SS');

					data += '|'+<?php echo $this->session->userdata('user_id'); ?>+','+fdate_edited;
					ajax_data(data,'users/update_leave_req/<?php echo $this->session->userdata('user_id'); ?>','');
				}

				alert('You have successfully updated your leave!');
				$('#leave_modal').modal('hide');

				if (for_edit_user_id_page != ''){
					var current_url = window.location.href.split('#')[0];
					var compare_url = '<?php echo base_url().'users/leave_details/'; ?>'+for_edit_user_id_page;

					if (current_url == compare_url){
						location.reload();
					}
				} else {
					var current_url = window.location.href.split('#')[0];
					var compare_url = '<?php echo base_url().'users/leave_details/'.$this->session->userdata('user_id'); ?>';

					if (current_url == compare_url){
						location.reload();
					}
				}

				return true;
			}

		} else {
			$('#confirmModal').modal('hide');
			alert('Please filled the required (*) fields');
			return false;
		}
    }

    function setRealTimePRNotif(){

    	$.ajax({
 
		   url:"<?php echo base_url().'projects/getProject_PR_images'; ?>",
		   method:"POST",
		   success:function(process)		 
		   {

		   	if (process != ''){
		   		$('a.pr-notif').remove();
		   		$('ul.pr-notif-ul').remove();
		   		$('li.real-time-notif').append(process);
		   	} else {
		   		$('a.pr-notif').remove();
		   		$('ul.pr-notif-ul').remove();
		   	}

		   }
		});
    }

    setRealTimePRNotif();

    $('.real-time-notif').click(function(){
		$('ul.pr-notif-ul').removeAttr('style');
	});

    function clearLeaveReqForm(){
    	if (leave_user_id == ''){
			apply_for_leave('');
		} else {
			apply_for_leave('leave_user_id');
		}
	}

</script>
<!-- Mike Coros end -->

<div id="sb-site">
