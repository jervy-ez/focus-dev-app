<?php 
	date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts
	$this->load->module('users');
 	$this->load->module('bulletin_board'); 

 	$user_id = $this->uri->segment(3);
 	
 	if ($user_id != $this->session->userdata('user_id')) {
		redirect(base_url().'users', 'refresh');
	} 

 	/*foreach($user as $key => $user):

		if($this->session->userdata('company') >= 2 ){

		}else{
			echo '<style type="text/css">.admin_access{ display: block !important;visibility: hidden !important;}</style>';
		}*/
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
						<li>
							<a href="<?php echo base_url(); ?>users/leave_details/<?php echo $this->session->userdata('user_id'); ?>">My Leave Requests</a>
						</li>
					<?php if ($this->session->userdata('user_id') == 3 || $this->session->userdata('user_id') == 21 || $this->session->userdata('user_id') == 9 || $this->session->userdata('user_id') == 22 || $this->session->userdata('user_id') == 15 || $this->session->userdata('user_id') == 27 || $this->session->userdata('user_id') == 20 || $this->session->userdata('user_id') == 42 || $this->session->userdata('user_id') == 16): ?>
						<li class="active">
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
							<div class="left-section-box">	

								<div class="row clearfix">

									<div class="col-lg-4 col-md-12">
										<div class="box-head pad-left-15 clearfix"  style="border: none;">
											<label><?php echo $screen; ?> List</label><span> (<a href="#" data-placement="right" class="popover-test" title="" data-content="Hello there mate! Welcome to the Approval Page of Leave screen." data-original-title="Welcome">?</a>)</span>
										<p>This is where the applications of leave that are needed to be approved are listed.</p>
										</div>
									</div>

									<br>

									<div class="col-lg-8 col-md-12">
										<div class="pad-left-15 pad-right-10 clearfix box-tabs" style="margin-bottom: -1px;">	
											<ul id="myTab" class="nav nav-tabs pull-right" style="border-bottom: none;">
												<li class="active">
													<a href="#approval" data-toggle="tab"><i class="fa fa-address-book-o fa-lg"></i> For Approvals</a>
												</li>
												<li class="">
													<a href="#approved" data-toggle="tab"><i class="fa fa-calendar-check-o fa-lg"></i> Approved / Unapproved Leaves</a>
												</li>
											</ul>
										</div>
									</div>

								</div>

								<div class="box-area">
									<div class="box-tabs m-bottom-15">
										<div class="tab-content">
											<div class="tab-pane clearfix active" id="approval" style="border: 1px solid #DDDDDD; border-left: 0 !important;">
												<div class="m-bottom-15 clearfix">
													<div class="box-area po-area">
									
														<table style="width: 100%;" id="pending_by_superv_tbl" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" width="100%">
														  	<thead>
														  		<!--<th>ID</th>-->
														  		<th>Date Submit</th>
														  		<th>Employee Name</th>
														  		<th>Leave Type</th>
														  		<th>Start Date - End Date</th>
														  		<th>Partial Day (Time In/Out)*</th>
														  		<th>Date Return</th>
														  		<th>Purpose</th>
														  		<th>Total Leave</th>
														  		<?php if ($this->session->userdata('user_id') == 3){ ?>
														  			<th>Approved First by</th>
														  		<?php } ?>
														  		<th>Need Approval of</th>
														  		<th>Status</th>
														  		<th>Action</th>
														  	</thead>
														  	<tbody>
														  	<?php  
																foreach ($pending_by_superv as $row):
																	
																	$leave_alloc = $this->users->leave_remaining($row->leave_user_id);

																	$approval = '';

																	if($row->is_approve=="1"){
																		$approval = "<span style='color: green;'>Approved</span>";
																	}
																	elseif($row->is_disapproved=="1"){
																		$approval = "<span style='color: red;'>Unapproved</span>";
																	}else{
																		$approval = "<span style='color: orange;'>Pending</span>";
																	}

																	$details = strip_tags($row->details);

																	if (strlen($details) > 45) {
																	    $stringCut = substr($details, 0, 45);
																	    $details = substr($stringCut, 0, strrpos($stringCut, ' ')).'... '; 
																	}

																	$partial_part = '';

																	if ($row->partial_part == 1){
																		$partial_part = 'Arrived Late ('.$row->partial_time.')';
																	} elseif ($row->partial_part == 2){
																		$partial_part = 'Depart Early ('.$row->partial_time.')';
																	} else {
																		$partial_part = 'N/A';
																	}

																	if (strpos($row->total_days_away, '.') == TRUE && $row->partial_day == 1){
																		$get_hrs = substr($row->total_days_away, 0, 1);
																		$get_mins = substr($row->total_days_away, 2, 2);

																		switch ($get_mins) {
																		    case '25':
																		        $get_mins = ' & 15 mins';
																		        break;
																		    case '50':
																		        $get_mins = ' & 30 mins';
																		        break;
																		    case '75':
																		        $get_mins = ' & 45 mins';
																		        break;
																		    default:
																		    	$get_mins = '';
																		    	break;
																		}
																	} else {
																		$total_days = floor($row->total_days_away / $leave_alloc->no_hrs_of_work);
																	}

																	echo '<tr>';
																	echo '<td>'.date('d/m/Y', $row->date).'</td>';
																	echo '<td>'.$row->first_name.' '.$row->last_name.'</td>';
																	
																	echo '<td>'.$row->leave_type.'</td>';
																	echo '<td>'.date('d/m/Y', $row->start_day_of_leave).' - '.date('d/m/Y', $row->end_day_of_leave).'</td>';
																	echo ($row->partial_day == 1) ? '<td>Yes, '.$partial_part.'</td>' : '<td>No</td>';
																	echo '<td>'.date('d/m/Y', $row->date_return).'</td>';
																	echo '<td><a class="tooltip-test" data-placement="bottom" title="'.$row->details.'" style="cursor:pointer">'.$details.'</a></td>';
																	echo ($row->partial_day == 1) ? '<td> '.$get_hrs.' hrs'.$get_mins.'</td>' : '<td> '.$total_days .' days</td>';

																	if ($this->session->userdata('user_id') == 3){
																		if ($row->supervisor_id == 3){
																			echo '<td>Reports Directly to MD</td>';
																		} else {
																			echo '<td>'.$row->superv_first_name.' '.$row->superv_last_name.'';
																			echo '&nbsp;&nbsp;&nbsp;<a href="#" class="tooltip-test" data-placement="bottom" title="'.$row->action_comments.'"><span class="badge btn btn-success"><i class="fa fa-comment"></i></span></a>';
																			echo '</td>';
																		}
																	}

																	echo '<td>'.$row->user_first_name.' '.$row->user_last_name.'</td>';
																	echo '<td>'.$approval.'</td>';
																	echo '<td align="center"><a onclick="addCommentApproved('.$row->leave_request_id.','.$row->leave_user_id.')" class="btn btn-success pull-left"><i class="fa fa-check"></i></a>|<a onclick="addCommentDisapproved('.$row->leave_request_id.');" class="btn btn-danger pull-right"><i class="fa fa-close"></a></td>';
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
														<table style="width: 100%;" id="approved_by_superv_tbl" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" width="100%">
															<thead>
														  		<!--<th>ID</th>-->
														  		<th>Date Applied</th>
														  		<th>Employee Name</th>
														  		<th>Leave Type</th>
														  		<th>Start Date</th>
														  		<th>End Date</th>
														  		<th>Partial Day (Time In/Out)*</th>
														  		<th>Date Return</th>
														  		<th>Purpose</th>
														  		<th>Total Leave</th>
														  		<th>Action by</th>
														  		<th>Status</th>
														  	</thead>
														  	<tbody>
														  	<?php  
																foreach ($approved_and_disapproved as $row):
																
																	$leave_alloc = $this->users->leave_remaining($row->user_id);

																	$action = '';

																	if($row->action=="1"){
																		$action = "<span style='color: green;'>Approved</span>";
																	}else{
																		$action = "<span style='color: red;'>Unapproved</span>";
																	}

																	$details = strip_tags($row->details);

																	if (strlen($details) > 45) {
																	    $stringCut = substr($details, 0, 45);
																	    $details = substr($stringCut, 0, strrpos($stringCut, ' ')).'... '; 
																	}

																	$partial_part = '';

																	if ($row->partial_part == 1){
																		$partial_part = 'Arrived Late ('.$row->partial_time.')';
																	} elseif ($row->partial_part == 2){
																		$partial_part = 'Depart Early ('.$row->partial_time.')';
																	} else {
																		$partial_part = 'N/A';
																	}
																	
																	if (strpos($row->total_days_away, '.') == TRUE && $row->partial_day == 1){
																		$get_hrs = substr($row->total_days_away, 0, 1);
																		$get_mins = substr($row->total_days_away, 2, 2);

																		switch ($get_mins) {
																		    case '25':
																		        $get_mins = ' & 15 mins';
																		        break;
																		    case '50':
																		        $get_mins = ' & 30 mins';
																		        break;
																		    case '75':
																		        $get_mins = ' & 45 mins';
																		        break;
																		    default:
																		    	$get_mins = '';
																		    	break;
																		}
																	} else {
																		$total_days = floor($row->total_days_away / $leave_alloc->no_hrs_of_work);
																	}

																	echo '<tr>';
																	//echo '<td>'.$row->leave_request_id.'</td>';
																	echo '<td>'.date('d/m/Y', $row->date).'</td>';
																	echo '<td>'.$row->user_first_name.' '.$row->user_last_name.'</td>';

																	/*if ($row->action=="1"){
																		echo '<td width="200">'.$row->leave_type;
																			if ($user_id == 3){
																				echo '<a class="tooltip-test pull-right" data-placement="bottom" title="'.$row->action_comments.'"><span class="badge btn btn-success"><i class="fa fa-comment"></i></span></a>';
																			}
																		echo '</td>';
																	} else {*/
																		echo '<td>'.$row->leave_type;
																			/*if ($user_id == 3){
																				echo '<a href="#" class="tooltip-test pull-right" data-placement="bottom" title="'.$row->action_comments.'"><span class="badge btn btn-danger"><i class="fa fa-comment"></i></span></a>';
																			}*/
																		echo '</td>';
																	//}

																	echo '<td>'.date('d/m/Y', $row->start_day_of_leave).'</td>';
																	echo '<td>'.date('d/m/Y', $row->end_day_of_leave).'</td>';
																	echo ($row->partial_day == 1) ? '<td>Yes, '.$partial_part.'</td>' : '<td>No</td>';
																	echo '<td>'.date('d/m/Y', $row->date_return).'</td>';
																	echo '<td><a href="#" class="tooltip-test" data-placement="bottom" title="'.$row->details.'">'.$details.'</a></td>';
																	echo ($row->partial_day == 1) ? '<td> '.$get_hrs.' hrs'.$get_mins.'</td>' : '<td> '.$total_days .' days</td>';
																	
																	$a = 0;

																	foreach ($approved_and_disapproved_by_md as $row2):

																		$leave_alloc = $this->users->leave_remaining($row->user_id);
																		
																		$action2 = '';

																		if($row2->action=="1"){
																			$action2 = "<span style='color: green;'>Approved</span>";
																		}else{
																			$action2 = "<span style='color: red;'>Unapproved</span>";
																		}

																		$md_full_name = $row2->md_fname." ".$row2->md_lname;
																		$superv_full_name = $row->superv_first_name." ".$row->superv_last_name;

																		if($row->leave_request_id == $row2->leave_request_id){
																			if($row2->action != 0 && $md_full_name != $superv_full_name){

																				echo '<td><strong>'.$md_full_name.'</strong>, '.$superv_full_name.'';

																				if ($row->action == '1'){
																					echo '&nbsp;&nbsp<a class="tooltip-test" data-placement="bottom" title="'.$row->action_comments.'"><span class="badge btn btn-success"><i class="fa fa-comment"></i></span></a>';
																				} else {
																					echo '&nbsp;&nbsp<a class="tooltip-test" data-placement="bottom" title="'.$row->action_comments.'"><span class="badge btn btn-danger"><i class="fa fa-comment"></i></span></a>';
																				}

																				echo '</td>';
																				echo '<td><strong>'.$action2.'</strong>, '.$action.'</td>';

																			} else if ($md_full_name != $superv_full_name) {
																				
																				echo '<td><strong>'.$md_full_name.'</strong>, '.$superv_full_name.'';

																				if ($row->action == '1'){
																					echo '&nbsp;&nbsp<a class="tooltip-test" data-placement="bottom" title="'.$row->action_comments.'"><span class="badge btn btn-success"><i class="fa fa-comment"></i></span></a>';
																				} else {
																					echo '&nbsp;&nbsp<a class="tooltip-test" data-placement="bottom" title="'.$row->action_comments.'"><span class="badge btn btn-danger"><i class="fa fa-comment"></i></span></a>';
																				}
																				
																				echo'</td>';
																				echo '<td><strong>'.$action2.'</strong>, '.$action.'</td>';

																			} else {
																				
																				echo '<td><strong>'.$md_full_name.'</strong></td>';
																				echo '<td><strong>'.$action2.'</strong></td>';

																			}

																			$a = 1;
																		}

																	endforeach;
																	
																	if ($a == 0){
																		if (($row->is_approve == 0 && $row->is_disapproved == 0) || $row->is_disapproved == 1){
																			echo '<td>'.$row->superv_first_name." ".$row->superv_last_name.'';

																			if ($row->action == '1'){
																				echo '&nbsp;&nbsp<a class="tooltip-test" data-placement="bottom" title="'.$row->action_comments.'"><span class="badge btn btn-success"><i class="fa fa-comment"></i></span></a>';
																			} else {
																				echo '&nbsp;&nbsp<a class="tooltip-test" data-placement="bottom" title="'.$row->action_comments.'"><span class="badge btn btn-danger"><i class="fa fa-comment"></i></span></a>';
																			}

																			echo '</td>';
																			echo '<td>'.$action.'</td>';
																		}
																	}

																	echo "</tr>";

																endforeach; ?>

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

<!-- Mike Coros #add_comment start -->
<div class="modal fade bs-example-modal-md" id="add_comment_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><label id="add_comment_title"></label><p style="font-size: 14px;">Fields having (*) is requred.</p></h4>
      </div>
	     <form class="form-horizontal" role="form">
	      <div class="modal-body pad-5">
	        <div class="box-area pad-5 clearfix">

					<div class="col-sm-12 m-bottom-10 clearfix <?php //if(form_error('site_start')){ echo 'has-error has-feedback';} ?>">
						<label for="add_comment_textarea" class="col-sm-5 control-label">Notes Here*:</label>
						<div class="col-sm-5">
							<textarea class="form-control" id="add_comment_textarea" placeholder="Comment" name="add_comment_textarea" rows="5" style="resize: vertical;"></textarea>
						</div>
					</div>

					<div class="clearfix"></div>
			</div><!-- ./box-area pad-10 clearfix -->
	      </div>
	      <div class="modal-footer m-top-10">
	      	
	      	<div id="add_comment_buttons" class="pull-right">
		        
	       	</div>
	      </div>
      	</form>
    </div>
  </div>
</div>
<!-- #add_comment end -->

<?php //endforeach; ?>

<div class="report_result hide hidden"></div>

<?php $this->bulletin_board->list_latest_post(); ?>
<?php $this->load->view('assets/logout-modal'); ?>

<script type="text/javascript">
	
	var leave_request_id = "";
	
	function addCommentApproved(id, leave_user_id){
		leave_request_id = id;
		leave_user_id = leave_user_id;
		var user_id = "<?php echo $this->session->userdata('user_id'); ?>";
		
		$("#add_comment_textarea").val('Leave request is approved.');
		$("#add_comment_title").text("Are you sure you want to approve this?");
		$("#add_comment_buttons").html('<button type="button" class="btn btn-warning" data-dismiss="modal">No</button> ' +
									   '<button type="button" id="btnLeaveApproved" data-loading-text="Loading..." class="btn btn-success">Yes</button>');
		
		$("#add_comment_modal").modal('show');

		$("button#btnLeaveApproved").click(function(){
			
			$("button#btnLeaveApproved").button('loading');

			var add_comment = $("#add_comment_textarea").val();
			var data = leave_request_id+'|'+add_comment+'|'+leave_user_id;	

			if (add_comment != "" && leave_request_id != ""){
				$.ajax({
					'url' : '<?php echo base_url().'users/approve_leave/'.$this->session->userdata('user_id'); ?>',
					'type' : 'POST',
					'data' : {'ajax_var' : data },
					'success' : function(data){
						
						$("button#btnLeaveApproved").button('reset');

						alert('You successfully approved this leave request!');
						$('#add_comment_modal').modal('hide');

						if (user_id == 3){			
							window.open("<?php echo base_url().'docs/leave_form/leave_form_'; ?>"+leave_request_id+".pdf");
							$(window).focus(function() {
								location.reload();
							});
						} else {
							if (window.location.href.split('#')[0] == "<?php echo base_url().'users/leave_approvals/'.$this->session->userdata('user_id'); ?>"){
								location.reload();
							}
						}

					}
				});

				// ajax_data(data,'users/approve_leave/<?php //echo $this->session->userdata('user_id') ?>',''); //alert(data);
				// alert('You successfully approved this leave request!');
				// $('#add_comment_modal').modal('hide');
	
				// if (user_id == 3){

				// 	$("h4.modal-title").text("Loading PDF Leave Request...");
				// 	$("#confirmText").html('<center><i class="fa fa-circle-o-notch fa-spin fa-5x "></i></center>');
				// 	$("#confirmModal").modal('show');

				// 	setTimeout(function(){ 
				// 		$("#confirmModal").modal('hide');						
						
				// 		window.open("<?php //echo base_url().'docs/leave_form/leave_form_'; ?>"+leave_request_id+".pdf");

				// 		//if (window.location.href.split('#')[0] == "<?php //echo base_url().'users/leave_approvals/'.$this->session->userdata('user_id'); ?>"){
				// 		$(window).focus(function() {
				// 			location.reload();
				// 		});

				// 	}, 5000);
				// } else {
				// 	if (window.location.href.split('#')[0] == "<?php //echo base_url().'users/leave_approvals/'.$this->session->userdata('user_id'); ?>"){
				// 		location.reload();
				// 	}
				// }

			} else {
				alert("Please filled the required (*) fields");
				return false;
			}
		});

	}

	function addCommentDisapproved(id){
		leave_request_id = id;
		$("#add_comment_textarea").val('Leave application denied.');
		$("#add_comment_title").text('Are you sure you want to unapprove this?');
		$("#add_comment_buttons").html('<button type="button" class="btn btn-warning" data-dismiss="modal">No</button>' +
									   '<button type="button" id="btnLeaveDisapproved" class="btn btn-success">Yes</button>');

		$("#add_comment_modal").modal('show');

		$("button#btnLeaveDisapproved").click(function(){

			var add_comment = $("#add_comment_textarea").val();

			var data = leave_request_id+'|'+add_comment;	

			if (add_comment != "" && leave_request_id != ""){
				ajax_data(data,'users/disapproved_leave/<?php echo $this->session->userdata('user_id'); ?>','');
				alert('You successfully unapproved this leave request!');
				$('#add_comment_modal').modal('hide');

				if (window.location.href.split('#')[0] == "<?php echo base_url().'users/leave_approvals/'.$this->session->userdata('user_id'); ?>"){
					location.reload();
				}

				return true;
			} else {
				alert("Please filled the required (*) fields");
				return false;
			}
		});
	}

	

	

</script>