<?php 
	date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts
	$this->load->module('users');
 	$this->load->module('bulletin_board'); 

 	$user_id = $this->uri->segment(3);

 	$user_access_arr = explode(',',  $this->users->get_user_access($this->session->userdata('user_id')) );
 	$leave_requests = $user_access_arr['19'];

 	if ($leave_requests != 1 && $user_id != $this->session->userdata('user_id')) {
		redirect(base_url().'users', 'refresh');
	}

 	foreach($user as $key => $user):

		if($this->session->userdata('company') >= 2 ){

		}else{
			echo '<style type="text/css">.admin_access{ display: block !important;visibility: hidden !important;}</style>';
		}
?>
<!-- title bar -->
<div class="container-fluid head-control">
	<div class="container-fluid">
		<div class="row">

			<div class="col-md-6 col-sm-4 col-xs-12 pull-left">
				<header class="page-header">
					<h3><?php $datestring = "%l, %F %d, %Y"; $time = time(); //use time() for timestamp  ?>
						<?php echo $screen; ?> Screen<br><small><?php echo mdate($datestring, $time); #echo date("l, F d, Y"); ?></small>
					</h3>
				</header>
			</div>

			<div class="page-nav-options col-md-6 col-sm-8 col-xs-12 pull-right hidden-xs">
				<ul class="nav nav-tabs navbar-right">
					<li>
						<a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Home</a>
					</li>
					<?php if($this->session->userdata('users') > 0 || $this->session->userdata('is_admin') ==  1): ?>
						<li>
							<a href="<?php echo base_url(); ?>users/account/<?php echo $this->session->userdata('user_id'); ?>"><i class="fa fa-cog"></i> My Account</a>
						</li>
					<?php endif; ?>
					<?php if($this->session->userdata('is_admin') == 1 ): ?>
						<li>
							<a href="<?php echo base_url(); ?>admin" class="btn-small">Defaults</a>
						</li>
						<li>
							<a href="<?php echo base_url(); ?>admin/company" class="btn-small">Company</a>
						</li>
						<li>
							<a href="<?php echo base_url(); ?>users/user_logs">User Logs</a>
						</li>
					<?php endif; ?>
						<li class="active">
							<a href="<?php echo base_url(); ?>users/leave_details/<?php echo $this->session->userdata('user_id'); ?>">My Leave Requests</a>
						</li>
					<?php if ($this->session->userdata('user_id') == 3 || $this->session->userdata('user_id') == 21 || $this->session->userdata('user_id') == 9 || $this->session->userdata('user_id') == 22 || $this->session->userdata('user_id') == 15 || $this->session->userdata('user_id') == 27 || $this->session->userdata('user_id') == 20 || $this->session->userdata('user_id') == 42 || $this->session->userdata('user_id') == 16 || $this->session->userdata('user_id') == 48): ?>
						<li>
							<a href="<?php echo base_url(); ?>users/leave_approvals/<?php echo $this->session->userdata('user_id'); ?>">Leave Approvals</a>
						</li>
					<?php endif; ?>
					<!-- <li>
						<a href="" class="btn-small"><i class="fa fa-magic"></i> Tour</a>
					</li> -->
				</ul>
			</div>

		</div>
	</div>
</div>
<!-- title bar -->

<div class="container-fluid">
	<!-- Example row of columns -->
	<div class="row">				
		<?php $this->load->view('assets/sidebar'); ?>
		<div class="section col-sm-12 col-md-11 col-lg-11">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<div class="left-section-box leave">	

								<div class="row clearfix">

										<div class="col-lg-4 col-md-12">
											<div class="box-head pad-left-15 clearfix" style="border: none;">
												<label><?php echo $screen; ?> List</label><span> (<a href="#" data-placement="right" class="popover-test" title="" data-content="Hello there mate! Welcome to the Applications of Leave screen." data-original-title="Welcome">?</a>)</span>
												<p>This is where your applications of leave listed.</p>
											</div>
										</div>

										<br>

										<div class="col-lg-8 col-md-12">
											<div class="pad-left-15 pad-right-10 clearfix box-tabs" style="margin-bottom: -1px;">	
												<ul id="myTab" class="nav nav-tabs pull-right" style="border-bottom: none;">
													<li class="active">
														<a href="#pending" data-toggle="tab"><i class="fa fa-address-book-o fa-lg"></i> Pending Leaves</a>
													</li>
													<li class="">
														<a href="#approved" data-toggle="tab"><i class="fa fa-calendar-check-o fa-lg"></i> Approved Leaves</a>
													</li>
													<li class="">
														<a href="#your_leaves" data-toggle="tab"><i class="fa fa-calendar-times-o fa-lg"></i> Unapproved Leaves</a>
													</li>
												</ul>
											</div>
										</div>

								</div>

								<div class="box-area">
									<div class="box-tabs m-bottom-15">
										<div class="tab-content">
											<div class="tab-pane clearfix active" id="pending" style="border: 1px solid #DDDDDD; border-left: 0 !important;">
												<div class="m-bottom-15 clearfix">
													<div class="box-area po-area">
														<table style="width: 100%;" id="pending_leaves_tbl" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" width="100%">
														  	<thead>
														  		<th>Date Applied</th>
														  		<th>Leave Type</th>
														  		<th>Start Date</th>
														  		<th>End Date</th>
														  		<th>Halfday</th>
														  		<th>Date Return</th>
														  		<th>Purpose</th>
														  		<th>Total Days Away</th>
														  		<th>Status</th>
														  	</thead>
														  	<tbody>
														  	<?php  
																foreach ($pending_leaves as $row):
																
																	$approval = '';

																	if($row->is_approve=="1"){
																		$approval = "<p style='color: green;'>Approved</p>";
																	}
																	elseif($row->is_disapproved=="1"){
																		$approval = "<p style='color: red;'>Unapproved</p>";
																	}else{
																		$approval = "<p style='color: orange;'>Pending</p>";
																	}

																	$details = strip_tags($row->details);

																	if (strlen($details) > 45) {
																	    $stringCut = substr($details, 0, 45);
																	    $details = substr($stringCut, 0, strrpos($stringCut, ' ')).'... '; 
																	}

																	$part_halfday = '';

																	if ($row->halfday_part == 1){
																		$part_halfday = 'morning';
																	} elseif ($row->halfday_part == 2){
																		$part_halfday = 'afternoon';
																	} else {
																		$part_halfday = 'N/A';
																	}

																	echo '<tr>';
																	echo '<td><a id="update_leave_req" onclick="editLeaveRequestbyID('.$row->leave_request_id.');" title="Edit this Leave Application" class="pull-left"><span class="badge btn btn-success"><i class="fa fa-edit"></i></span></a> &nbsp;&nbsp;&nbsp;'.date('d/m/Y', $row->date).'</td>';
																	echo '<td width="250">'.$row->leave_type.'</td>';
																	echo '<td>'.date('d/m/Y', $row->start_day_of_leave).'</td>';
																	echo '<td>'.date('d/m/Y', $row->end_day_of_leave).'</td>';
																	echo ($row->with_halfday == 1) ? '<td>Yes, '.$part_halfday.'</td>' : '<td>No</td>';
																	echo '<td>'.date('d/m/Y', $row->date_return).'</td>';
																	echo '<td width="400"><a class="tooltip-test" data-placement="bottom" title="'.$row->details.'" style="cursor:pointer">'.$details.'</a></td>';
																	echo '<td>'.$row->total_days_away.'</td>';
																	echo '<td>'.$approval.'</td>';
																	echo "</tr>";

																endforeach;
															?>
														  	</tbody>
														</table>
													</div>
												</div>
											</div>
											<div class="tab-pane  clearfix" id="approved" style="border: 1px solid #DDDDDD; border-left: 0 !important;">
												<div class="m-bottom-15 clearfix">
													<div class="box-area po-area">
														<table id="approved_leaves_tbl" class="table table-striped table-bordered dataTable no-footer">
														  	<thead>
														  		<th>Date Applied</th>														  		
														  		<th>Leave Type</th> 		
														  		<th>Start Date</th>
														  		<th>End Date</th>
														  		<th>Halfday</th>
														  		<th>Date Return</th>														  		
														  		<th>Purpose</th>
														  		<th>Total Days</th>
														  		<th>Approved by</th>
														  		<th>Approved Date</th>
														  	</thead>
														  	<tbody>
															<?php  

																if ($user->supervisor_id == 3) {
																	foreach ($approved_leaves_by_md as $row):
																
																		$details = strip_tags($row->details);

																		if (strlen($details) > 45) {
																		    $stringCut = substr($details, 0, 45);
																		    $details = substr($stringCut, 0, strrpos($stringCut, ' ')).'... '; 
																		}

																		$part_halfday = '';

																		if ($row->halfday_part == 1){
																			$part_halfday = 'morning';
																		} elseif ($row->halfday_part == 2){
																			$part_halfday = 'afternoon';
																		} else {
																			$part_halfday = 'N/A';
																		}

																		echo '<tr>';
																		echo '<td>'.date('d/m/Y', $row->date_applied).'</td>';
																		echo '<td>'.$row->leave_type.'</td>';
																		echo '<td>'.date('d/m/Y', $row->start_day_of_leave).'</td>';
																		echo '<td>'.date('d/m/Y', $row->end_day_of_leave).'</td>';
																		echo ($row->with_halfday == 1) ? '<td>Yes, '.$part_halfday.'</td>' : '<td>No</td>';
																		echo '<td>'.date('d/m/Y', $row->date_return).'</td>';
																		echo '<td><a class="tooltip-test" data-placement="bottom" title="'.$row->details.'" style="cursor:pointer">'.$details.'</a></td>';
																		echo '<td>'.$row->total_days_away.'</td>';
																		echo '<td>'.$row->md_fname." ".$row->md_lname.'</td>';
																		echo '<td>'.date('d/m/Y', $row->date_approved).'</td>';
																		echo "</tr>";
																	endforeach;
																} else {
																	foreach ($approved_leaves as $row):
																
																		$details = strip_tags($row->details);

																		if (strlen($details) > 45) {
																		    $stringCut = substr($details, 0, 45);
																		    $details = substr($stringCut, 0, strrpos($stringCut, ' ')).'... '; 
																		}

																		$part_halfday = '';

																		if ($row->halfday_part == 1){
																			$part_halfday = 'morning';
																		} elseif ($row->halfday_part == 2){
																			$part_halfday = 'afternoon';
																		} else {
																			$part_halfday = 'N/A';
																		}

																		echo '<tr>';
																		echo '<td>'.date('d/m/Y', $row->date_applied).'</td>';
																		echo '<td>'.$row->leave_type.'</td>';
																		echo '<td>'.date('d/m/Y', $row->start_day_of_leave).'</td>';
																		echo '<td>'.date('d/m/Y', $row->end_day_of_leave).'</td>';
																		echo ($row->with_halfday == 1) ? '<td>Yes, '.$part_halfday.'</td>' : '<td>No</td>';
																		echo '<td>'.date('d/m/Y', $row->date_return).'</td>';
																		echo '<td><a class="tooltip-test" data-placement="bottom" title="'.$row->details.'" style="cursor:pointer">'.$details.'</a></td>';
																		echo '<td>'.$row->total_days_away.'</td>';

																		foreach ($approved_leaves_by_md as $row2):
																			if ($row->leave_request_id == $row2->leave_request_id){
																				echo '<td><strong>'.$row2->md_fname." ".$row2->md_lname.'</strong>, '.$row->approved_fname." ".$row->approved_lname.'</td>';
																				echo '<td><strong>'.date('d/m/Y', $row2->date_approved).'</strong>, '.date('d/m/Y', $row->date_approved).'</td>';
																			}
																		endforeach;

																		if ($row->is_approve == 0){
																			echo '<td>'.$row->approved_fname." ".$row->approved_lname.'</td>';
																			echo '<td>'.date('d/m/Y', $row->date_approved).'</td>';
																		}
																		echo "</tr>";
																	endforeach;
																}
															?>
														  	</tbody>
														</table>
													</div>
												</div>
											</div>
											<div class="tab-pane  clearfix" id="your_leaves" style="border: 1px solid #DDDDDD; border-left: 0 !important;">
												<div class="m-bottom-15 clearfix">
													<div class="box-area po-area">
														<table id="unapproved_leaves_tbl" class="table table-striped table-bordered dataTable no-footer">
														  	<thead>
														  		<th>Date Applied</th>														  		
														  		<th>Leave Type</th> 		
														  		<th>Start Date</th>
														  		<th>End Date</th>
														  		<th>Halfday</th>
														  		<th>Date Return</th>														  		
														  		<th>Purpose</th>
														  		<th>Total Days</th>
														  		<th>Unapproved by</th>
														  		<th>Unapproved Date</th>
														  	</thead>
														  	<tbody>
															<?php  
																foreach ($unapproved_leaves as $row):
																
																	$details = strip_tags($row->details);

																	if (strlen($details) > 45) {
																	    $stringCut = substr($details, 0, 45);
																	    $details = substr($stringCut, 0, strrpos($stringCut, ' ')).'... '; 
																	}

																	$part_halfday = '';

																	if ($row->halfday_part == 1){
																		$part_halfday = 'morning';
																	} elseif ($row->halfday_part == 2){
																		$part_halfday = 'afternoon';
																	} else {
																		$part_halfday = 'N/A';
																	}

																	echo '<tr>';
																	echo '<td>';

																	if ($row->supervisor_id != 3){
																		echo '<a id="update_leave_req" onclick="editLeaveRequestbyID('.$row->leave_request_id.');" title="Edit this Leave Application" class="pull-left"><span class="badge btn btn-success"><i class="fa fa-edit"></i></span></a> &nbsp;&nbsp;&nbsp;';
																	}
																	
																	echo date('d/m/Y', $row->date_applied).'</td>';
																	echo '<td>'.$row->leave_type.'</td>';
																	echo '<td>'.date('d/m/Y', $row->start_day_of_leave).'</td>';
																	echo '<td>'.date('d/m/Y', $row->end_day_of_leave).'</td>';
																	echo ($row->with_halfday == 1) ? '<td>Yes, '.$part_halfday.'</td>' : '<td>No</td>';
																	echo '<td>'.date('d/m/Y', $row->date_return).'</td>';
																	echo '<td><a class="tooltip-test" data-placement="bottom" title="'.$row->details.'" style="cursor:pointer">'.$details.'</a></td>';
																	echo '<td>'.$row->total_days_away.'</td>';
																	echo '<td>'.$row->approved_fname." ".$row->approved_lname.'&nbsp;&nbsp;<a class="tooltip-test" data-placement="bottom" title="'.$row->action_comments.'"><span class="badge btn btn-danger"><i class="fa fa-comment"></i></span></a></td>';
																	echo '<td>'.date('d/m/Y', $row->date_approved).'</td>';
																	echo "</tr>";

																endforeach;
															?>
														  	</tbody>
														</table>
													</div>
												</div>
											</div>
										</div><!-- /.tab-content -->
									</div><!-- /.box-tabs m-bottom-15 -->			
								</div><!-- /.box-area -->
							</div><!-- ./left-section-box -->
						</div><!-- ./col-md-12 -->
					</div><!-- ./row-->				
				</div><!-- ./container-fluid -->
		</div>
	</div>
</div>

<?php endforeach; ?>

<div class="report_result hide hidden"></div>

<?php $this->bulletin_board->list_latest_post(); ?>
<?php $this->load->view('assets/logout-modal'); ?>
