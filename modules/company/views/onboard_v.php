<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>
<?php $this->load->module('bulletin_board'); ?>
<?php

	$onboarding_access = $this->session->userdata('onboarding');

	if($onboarding_access != 1): 		
		redirect('', 'refresh');
	endif;

	if($this->session->userdata('company') >= 2 ){

	}else{
		echo '<style type="text/css">.admin_access{ display: none !important;visibility: hidden !important;}</style>';
	}

	$user_id = $this->session->userdata('user_id');
	
?>
<!-- title bar -->
<div class="container-fluid head-control">
	<div class="container-fluid">
		<div class="row">

			<input type="hidden" name="workplace_health_safety_msg" id="workplace_health_safety_msg" value="<?php echo $workplace_health_safety_msg; ?>">
			<input type="hidden" name="swms_msg" id="swms_msg" value="<?php echo $swms_msg; ?>">
			<input type="hidden" name="jsa_msg" id="jsa_msg" value="<?php echo $jsa_msg; ?>">
			<input type="hidden" name="reviewed_swms_msg" id="reviewed_swms_msg" value="<?php echo $reviewed_swms_msg; ?>">
			<input type="hidden" name="safety_related_convictions_msg" id="safety_related_convictions_msg" value="<?php echo $safety_related_convictions_msg; ?>">
			<input type="hidden" name="confirm_licences_certifications_msg" id="confirm_licences_certifications_msg" value="<?php echo $confirm_licences_certifications_msg; ?>">

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
					<li <?php if($screen=='Client'){ echo 'class="active"';} ?> >
						<a href="<?php echo base_url(); ?>company" class="btn-small">Clients</a>
					</li>
					<li <?php if($screen=='Contractor'){ echo 'class="active"';} ?> >
						<a href="<?php echo base_url(); ?>company/contractor" class="btn-small">Contractor</a>
					</li>
					<li <?php if($screen=='Supplier'){ echo 'class="active"';} ?> >
						<a href="<?php echo base_url(); ?>company/supplier" class="btn-small">Supplier</a>
					</li>
					<?php if($this->session->userdata('shopping_centre') >= 1 ): ?>
					<li>
						<a href="<?php echo base_url(); ?>shopping_center" class="btn-small"><i class="fa fa-shopping-cart"></i> Shopping Center</a>
					</li>
					<?php endif; ?>

					<li>
						<a href="<?php echo base_url(); ?>company/onboarding" class="btn-small">Onboarding</a>
					</li>

					<?php if($this->session->userdata('company') >= 2): ?>
					<li>
						<a href="#" class="btn-small" data-toggle="modal" data-target="#filter_company"><i class="fa fa-print"></i> Reports</a>
					</li>
					<?php endif; ?> 
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
							<div class="box-head pad-left-10 clearfix">

								<div class="col-lg-4 col-md-12">
									<label><?php echo $screen; ?> List</label><span> (<a href="#" data-placement="right" class="popover-test" title="" data-content="Hello there mate! Welcome to the onboarding screen." data-original-title="Welcome">?</a>)</span>
									<p>This is where the pending new onboard are listed.</p>
								</div>

								<br>

								<div class="col-lg-8 col-md-12">
									<div class="m-right-20 pad-left-15 pad-right-10 clearfix box-tabs">	
										<ul id="myTab" class="nav nav-tabs pull-right" style="border-bottom: none;">
											<li class="active">
												<a href="#pending" data-toggle="tab"><i class="fa fa-users fa-lg"></i> Pending</a>
											</li>
											<li class="">
												<a href="#declined" data-toggle="tab"><i class="fa fa-minus-circle fa-lg"></i> Declined</a>
											</li>
										</ul>
									</div>
								</div>

							</div>

							<div class="box-area">
								
								<div class="box-tabs m-bottom-15">
									<div class="tab-content">
										<div class="tab-pane clearfix active" id="pending" style="border-left: none !important;">
											<div class="m-bottom-15 clearfix">
												<div class="box-area po-area">
													<table id="onboardTable" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" width="100%">
													  	<thead>
													  		<tr>
													  			<th>Company Name</th>
													  			<th>Company Type</th>
													  			<th>Location</th>
													  			<th>Primary Contact</th>
													  			<th>Email Address</th>
													  			<th>Action</th>
															</tr>
														</thead>
													  	<tbody>
													  		<?php
																foreach ($pending_onboard as $row):
																	echo '<tr>';
																	echo '<td><a target="_blank" href="'.base_url().'company/view_onboard/'.$row->company_id.'">'.$row->company_name.'</a></td>';
																	echo '<td>'.$row->company_type.'</td>';
																	echo '<td>'.ucwords(strtolower($row->suburb)).' '.$row->shortname.'</td>';
																	echo '<td>'.$row->contact_full_name.'</td>';
																	echo '<td><a href="#">'.$row->general_email.'</a></td>';
																	echo '<td align="center">
																			<a id="onboard_list_approve" class="badge btn btn-success tooltip-test" onclick="onboard_approved(\''.$row->company_id.'|'.$row->general_email.'|'.$row->company_type_id.'\');" data-placement="bottom" data-html="true" title="Approve"><i class="fa fa-check" style="color: #ffffff !important;"></i></a>

																			<a id="onboard_list_decline" class="badge btn btn-warning tooltip-test" onclick="onboard_declined(\''.$row->company_id.'|'.$row->general_email.'|'.$row->company_type_id.'\');" data-placement="bottom" data-html="true" title="Decline"><i class="fa fa-minus" style="color: #ffffff !important;"></i></a>';
																	echo '</tr>';
																endforeach;
															?>
													  	</tbody>
													</table>
												</div>
											</div>
										</div>
										<div class="tab-pane  clearfix" id="declined">
											<div class="m-bottom-15 clearfix">
												<div class="box-area po-area">
													<table id="declinedOnboardTable" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" width="100%">
													  	<thead>
													  		<tr>
													  			<th>Company Name</th>
													  			<th>Company Type</th>
													  			<th>Location</th>
													  			<th>Primary Contact</th>
													  			<th>Email Address</th>
													  			<th>Action</th>
															</tr>
														</thead>
													  	<tbody>
															<?php
																foreach ($declined_onboard as $row):
																	echo '<tr class="wip">';
																	echo '<td><a target="_blank" href="'.base_url().'company/view_onboard/'.$row->company_id.'" class="tooltip-test" data-placement="bottom" data-html="true" title="'.$row->declined_message.'">'.$row->company_name.'</a></td>';
																	echo '<td>'.$row->company_type.'</td>';
																	echo '<td>'.ucwords(strtolower($row->suburb)).' '.$row->shortname.'</td>';
																	echo '<td>'.$row->contact_full_name.'</td>';
																	echo '<td><a href="#">'.$row->general_email.'</a></td>';
																	echo '<td align="center">
																			<a id="onboard_list_approve" class="badge btn btn-success tooltip-test" onclick="onboard_approved(\''.$row->company_id.'|'.$row->general_email.'|'.$row->company_type_id.'\');" data-placement="bottom" data-html="true" title="Approve"><i class="fa fa-check" style="color: #ffffff !important;"></i></a>

																		  </td>'; // <a id="onboard_list_remove" class="badge btn btn-danger tooltip-test" onclick="onboard_removed(\''.$row->company_id.'|'.$row->general_email.'\');" data-placement="bottom" title="Remove"><i class="fa fa-close" style="color: #ffffff !important;"></i></a>
																	echo '</tr>';
																endforeach;
															?>
													  	</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>				
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="declinedComments" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Message to Declined Pending New Onboard</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
				    <label for="exampleInputEmail1">Write Comments Here:</label>
				    <textarea id="declinedCommentsBox" class="form-control" rows="10"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				<button type="button" id="" class="btn btn-primary" onclick="sendComments();">Send Comments</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade bs-example-modal-sm" id="confirmModal_selectBankForm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="margin-top: 120px; overflow: hidden;">
  <div class="modal-dialog modal-sm" style="width: 500px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title msgbox" id="myModalLabel_selectBankForm">Confirmation</h4>
      </div>
      <div class="modal-body">
        <p id="confirmText_selectBankForm">Are you sure you want to approve this pending new onboard?</p>

        <br>

        <label>Please select the state for the Bank Details Form attachment:</label>
        <select class="form-control" id="bank_details_form_state">
		  <option value="wa">WA</option>
		  <option value="nsw">NSW</option>
		</select>
      </div>
      <div id="confirmButtons_selectBankForm" class="modal-footer"></div>
    </div>
  </div>
</div>

<?php $this->load->view('assets/logout-modal'); ?>

<script type="text/javascript">

	var current_params = '';

	function onboard_approved(params){

		var params_split = params.split('|');
		var company_id = params_split[0];
		var company_type_id = params_split[2];

		if (company_type_id == 1){

			$('h4#myModalLabel.modal-title.msgbox').html("Confirmation");
			$('#confirmText').text('Are you sure you want to approve this pending new onboard?');
			$('#confirmButtons').html('<button type="button" class="btn btn-success" onclick="approved_company(\''+params+'\')">Yes</button>' +
								      '<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>');
		    $('#confirmModal').modal({
		      keyboard: false,
		      backdrop: 'static',
		      show: true
		    });
		}

		if (company_type_id == 2){

			$.ajax({
		        'url' : '<?php echo base_url(); ?>company/fetch_ohs_updated',
		        'type' : 'POST',
		        'data' : {'ajax_var' : company_id },
		        'success' : function(data){

		        	var ohs = data;
					var ohsArr = ohs.split('|');

					var workplace_health_safety_data = ohsArr[0];
					var swms_data = ohsArr[1];
					var jsa_data = ohsArr[2];
					var reviewed_swms_data = ohsArr[3];
					var safety_related_convictions_data = ohsArr[4];
					var confirm_licences_certifications_data = ohsArr[5];
					var ohs_validation_count = 0;
					var alert_message = 'This is the following errors:';

					if (workplace_health_safety_data == 0 || swms_data == 0 || jsa_data == 0 || reviewed_swms_data == 0 || safety_related_convictions_data == 1 || confirm_licences_certifications_data == 0){

						if (workplace_health_safety_data == 0) {

							alert_message += '<br><br>- Newly Registered Contractor is not having a Workplace Health & Safety system in their place.';
							ohs_validation_count = +(ohs_validation_count) + +(1);
						}

						if (swms_data == 0) {

							alert_message += '<br><br>- Newly Registered Contractor is not familiar with the Safe Work Method Statements (SWMS).';

							ohs_validation_count = +(ohs_validation_count) + +(1);
						}

						if (jsa_data == 0) {

							alert_message += '<br><br>- Newly Registered Contractor is not familiar with the Job Safety Analysis (JSA).';
							ohs_validation_count = +(ohs_validation_count) + +(1);
						}

						if (reviewed_swms_data == 0) {

							alert_message += '<br><br>- Safe Work Method Statements (SWMS) is not reviewed by the Newly Registered Contractor.';
							ohs_validation_count = +(ohs_validation_count) + +(1);
						}

						if (safety_related_convictions_data == 1) {

							alert_message += '<br><br>- Newly Registered Contractor has a past Safety Related Convictions. Please check with the General Manager.';
							ohs_validation_count = +(ohs_validation_count) + +(1);
						}

						if (confirm_licences_certifications_data == 0) {

							alert_message += '<br><br>- Newly Registered Contractor did not confirmed the appropriate licences and certification for all the staff and site personnel.';
							ohs_validation_count = +(ohs_validation_count) + +(1);
						}

						$('h4#myModalLabel.modal-title.msgbox').html("Message Alert!");
						$('#confirmText').html(alert_message);
						$('#confirmButtons').html('<button type="button" class="btn btn-warning" data-dismiss="modal">Go Back</button>');
					    $('#confirmModal').modal({
					      keyboard: false,
					      backdrop: 'static',
					      show: true
					    });

					} else {

						$('#confirmButtons_selectBankForm').html('<button type="button" class="btn btn-success" onclick="approved_company(\''+params+'\')">Yes</button>' +
											      '<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>');
					    $('#confirmModal_selectBankForm').modal({
					      keyboard: false,
					      backdrop: 'static',
					      show: true
					    });

					}
		        }
		    });

		} 

		if (company_type_id == 3){

			$('#confirmButtons_selectBankForm').html('<button type="button" class="btn btn-success" onclick="approved_company(\''+params+'\')">Yes</button>' +
											      	 '<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>');
		    $('#confirmModal_selectBankForm').modal({
		      keyboard: false,
		      backdrop: 'static',
		      show: true
		    });
		}
	}

	// function onboard_approved(params){

	// 	$('h4#myModalLabel.modal-title.msgbox').html("Confirmation");
	// 	$('#confirmText').text('Are you sure you want to approve this pending new onboard?');
	// 	$('#confirmButtons').html('<button type="button" class="btn btn-success" onclick="approved_company(\''+params+'\')">Yes</button>' +
	// 						      '<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>');
	//     $('#confirmModal').modal({
	//       keyboard: false,
	//       backdrop: 'static',
	//       show: true
	//     });
	// }

	function approved_company(params){

		var params_split = params.split('|');
		var company_type_id = params_split[2];
		var bank_details_form_state = $('#bank_details_form_state').val();

		// if (company_type_id == 1){
		// 	alert('Please select the new onboard and edit activity first!');
		// 	$('#confirmModal').modal('hide');
		// 	return;
		// } else {
			$('#confirmModal').modal('hide');

			var data = params;
			data += '|'+bank_details_form_state;

			$.ajax({
		        'url' : 'company/onboard_approved',
		        'type' : 'POST',
		        'data' : {'ajax_var' : data },
		        'success' : function(data){
		        	if (data == '1'){
		        		alert('New Onboard is successfully approved');
		        		location.reload();
		        	} else {
		        		alert('Some errors found. Please contact administrator.');
		        	}
		        }
		    });
	}

	function onboard_declined(params){
		$('h4#myModalLabel.modal-title.msgbox').html("Confirmation");
		$('#confirmText').text('Are you sure you want to decline this pending new onboard?');
		$('#confirmButtons').html('<button type="button" class="btn btn-success" onclick="declined_company(\''+params+'\')">Yes</button>' +
							      '<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>');
	    $('#confirmModal').modal({
	      keyboard: false,
	      backdrop: 'static',
	      show: true
	    });
	}

	function declined_company(params){

		var params_split = params.split('|');
		var company_id = params_split[0];
		var company_type_id = params_split[2];

		var workplace_health_safety_msg = $('#workplace_health_safety_msg').val();
		var swms_msg = $('#swms_msg').val();
		var jsa_msg = $('#jsa_msg').val();
		var reviewed_swms_msg = $('#reviewed_swms_msg').val();
		var safety_related_convictions_msg = $('#safety_related_convictions_msg').val();
		var confirm_licences_certifications_msg = $('#confirm_licences_certifications_msg').val();

		if (company_type_id == 2){

			$.ajax({
		        'url' : '<?php echo base_url(); ?>company/fetch_ohs_updated',
		        'type' : 'POST',
		        'data' : {'ajax_var' : company_id },
		        'success' : function(data){

		        	var ohs = data;
					var ohsArr = ohs.split('|');

					var workplace_health_safety_data = ohsArr[0];
					var swms_data = ohsArr[1];
					var jsa_data = ohsArr[2];
					var reviewed_swms_data = ohsArr[3];
					var safety_related_convictions_data = ohsArr[4];
					var confirm_licences_certifications_data = ohsArr[5];
					var ohs_validation_count = 0;
					var alert_message = 'This is the following errors:';

					$(tinymce.get('declinedCommentsBox').getBody()).html('');

					if (workplace_health_safety_data == 0){
						$(tinymce.get('declinedCommentsBox').getBody()).html(workplace_health_safety_msg);
					}

					if (swms_data == 0){
						if (tinymce.get('declinedCommentsBox').getContent() != ''){
							$(tinymce.get('declinedCommentsBox').getBody()).html(tinymce.get('declinedCommentsBox').getContent() + '<br><br>' + swms_msg);
						} else {
							$(tinymce.get('declinedCommentsBox').getBody()).html(swms_msg);
						}
					}

					if (jsa_data == 0){
						if (tinymce.get('declinedCommentsBox').getContent() != ''){
							$(tinymce.get('declinedCommentsBox').getBody()).html(tinymce.get('declinedCommentsBox').getContent() + '<br><br>' + jsa_msg);
						} else {
							$(tinymce.get('declinedCommentsBox').getBody()).html(jsa_msg);
						}
					}

					if (reviewed_swms_data == 0){
						if (tinymce.get('declinedCommentsBox').getContent() != ''){
							$(tinymce.get('declinedCommentsBox').getBody()).html(tinymce.get('declinedCommentsBox').getContent() + '<br><br>' + reviewed_swms_msg);
						} else {
							$(tinymce.get('declinedCommentsBox').getBody()).html(reviewed_swms_msg);
						}
					}

					if (safety_related_convictions_data == 1){
						if (tinymce.get('declinedCommentsBox').getContent() != ''){
							$(tinymce.get('declinedCommentsBox').getBody()).html(tinymce.get('declinedCommentsBox').getContent() + '<br><br>' + safety_related_convictions_msg);
						} else {
							$(tinymce.get('declinedCommentsBox').getBody()).html(safety_related_convictions_msg);
						}
					}

					if (confirm_licences_certifications_data == 0){
						if (tinymce.get('declinedCommentsBox').getContent() != ''){
							$(tinymce.get('declinedCommentsBox').getBody()).html(tinymce.get('declinedCommentsBox').getContent() + '<br><br>' + confirm_licences_certifications_msg);
						} else {
							$(tinymce.get('declinedCommentsBox').getBody()).html(confirm_licences_certifications_msg);
						}
					}

					$('#confirmModal').modal('hide');
					$('#declinedComments').modal('show');
					
					current_params = params;
		        }
		    });

		} else {

			var params_split = params.split('|');
			var company_type_id = params_split[2];

			$(tinymce.get('declinedCommentsBox').getBody()).html('');

			// if (company_type_id == 1){
			// 	alert('Please select the new onboard and edit activity first!');
			// 	$('#confirmModal').modal('hide');
			// 	return;
			// } else {
				$('#confirmModal').modal('hide');
				$('#declinedComments').modal('show');
				
				current_params = params;
			// }
		}
	}

	// function declined_company(params){

	// 	var params_split = params.split('|');
	// 	var company_type_id = params_split[2];

	// 	if (company_type_id == 1){
	// 		alert('Please select the new onboard and edit activity first!');
	// 		$('#confirmModal').modal('hide');
	// 		return;
	// 	} else {
	// 		$('#confirmModal').modal('hide');
	// 		$('#declinedComments').modal('show');
			
	// 		current_params = params;
	// 	}
		
	// }

	function sendComments(){

		var getDeclinedComments = tinymce.get('declinedCommentsBox').getContent();
		// var getDeclinedComments = $('#declinedCommentsBox').val();
		var data = current_params+'|'+getDeclinedComments;
		// var current_email = current_params.split('|').pop();

		$.ajax({
	        'url' : 'company/onboard_declined',
	        'type' : 'POST',
	        'data' : {'ajax_var' : data },
	        'success' : function(result){
		        if (result == '1'){
	        		alert('New Onboard is successfully declined. Comments are sent also via email.');
	        		location.reload();
	        	} else {
	        		alert('Some errors found. Please contact administrator.');
	        	}
	        }
	    });
	}

	function onboard_removed(params){
		$('h4#myModalLabel.modal-title.msgbox').html("Confirmation");
		$('#confirmText').text('Are you sure you want to remove this pending new onboard?');
		$('#confirmButtons').html('<button type="button" class="btn btn-success" onclick="removed_company(\''+params+'\')">Yes</button>' +
							      '<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>');
	    $('#confirmModal').modal({
	      keyboard: false,
	      backdrop: 'static',
	      show: true
	    });
	}

	function removed_company(params){

		$('#confirmModal').modal('hide');

		var data = params;

		$.ajax({
	        'url' : 'company/onboard_removed',
	        'type' : 'POST',
	        'data' : {'ajax_var' : data },
	        'success' : function(data){

	        	if (data == '1'){
	        		alert('New Onboard is successfully removed');
	        		location.reload();
	        	} else {
	        		alert('Some errors found. Please contact administrator.');
	        	}
	        }
	    });
	}

</script>