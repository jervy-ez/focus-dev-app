<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>
<?php $this->load->module('projects'); ?>
<?php $this->load->module('bulletin_board'); ?>
<?php $this->load->model('admin_m'); ?>

<?php 

  $user_responsible = 0;
  $user_query = $this->admin_m->fetch_admin_default_email_message();
  foreach ($user_query->result_array() as $row){
    $user_id = $row['user_id'];
    $message_content = $row['message_content'];
    $sender_name = $row['sender_name'];
    $sender_email = $row['sender_email'];
    $bcc_email = $row['bcc_email'].",insurance@focusshopfit.com.au";

    $newdata = array(
      'message_content'  => $message_content,
      'sender_name'  => $sender_name,
      'sender_email'  => $sender_email,
      'bcc_email'  => $bcc_email,
    );

    $this->session->set_userdata($newdata);
  }

  $focus_user_id = $this->session->userdata('user_id'); 
  if($user_id == $focus_user_id){
    $send_email = $this->session->userdata('auto_send_email'); 
    
    if($send_email == "" || $send_email == 0){
      $newdata = array(
                     'auto_send_email'  => 1
                 );

      $this->session->set_userdata($newdata);

      $expired_insurance = $this->company->check_for_expired_insurance(); 

      if($expired_insurance > 0){
?>

      <script type="text/javascript">
        $(document).ready(function() {
          $("#cont_sending_button").hide();
          $("#lbl_expired").text(<?php echo $expired_insurance ?>);
          $('#auto_send_confirmation').modal({
            backdrop: 'static',
            keyboard: true,
            show: true
          })

          //$("#auto_send_confirmation").modal('show');
          $("#auto_send_email").click(function(){
            $.post(baseurl+"company/autosend_email_expired_insurance",
            {
            },
            function(result){
              alert(result);
              $("#auto_send_confirmation").modal('hide');
            });
            
          });

          $("#view_list").click(function(){
            $("#auto_send_confirmation").modal('hide');
            $('#list_of_contractors').modal({
              backdrop: 'static',
              keyboard: true,
              show: true
            });

            $.post(baseurl+"company/contractors_list",
            {
            },
            function(result){
              $('#contractors_list').html(result);
            });

            
          });

          $("#contlist_send_email").click(function(){
            var checkboxValues = [];
            $('input[name=chk_contractor_list]:checked').map(function() {
              checkboxValues.push($(this).val());
            });
            $("#cont_sending_button").show();
            $("#contlist_send_email").hide();
            $.post(baseurl+"company/selected_contractor_send_email",
            {
              checkboxValues: checkboxValues
            },
            function(result){
              alert(result);
              $("#cont_sending_button").hide();
              $("#contlist_send_email").show();
              $('#list_of_contractors').modal('hide');
            });
          });
        });
      </script>
<?php
      }
    }
  }
?>

<div class="modal fade" id="auto_send_confirmation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-sm">
    <div class="modal-content" style = "width:400px">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Auto Send Confirmation</h4>
      </div>
      <div class="modal-body">
        <p><label for="" id = "lbl_expired"></label> of the Contractor/s Insurances is about to expire. Would you like to Auto send Email to the contractor/s?</p>

      </div>
      <div class="modal-footer">
        <button type = "button" class="btn btn-warning" id = "view_list">View Contractors List</button>
        <button type = "button" class="btn btn-success pull-right" data-dismiss="modal">No</button>
        <button type = "button" class="btn btn-primary pull-right" id = "auto_send_email">Yes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="list_of_contractors" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-sm">
    <div class="modal-content" style = "width:400px">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">List of Contractors with Expired Insurance</h4>
      </div>
      <div class="modal-body">
        <div id="contractors_list" style = "height: 300px; overflow: auto">
        </div>
      </div>
      <div class="modal-footer">
        <button type = "button" class="btn btn-success pull-right" data-dismiss="modal">Close</button>
        <button type = "button" class="btn btn-primary pull-right" id = "contlist_send_email">Send</button>
        <button class="btn btn-warning" id = "cont_sending_button"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Sending...</button>
      </div>
    </div>
  </div>
</div>
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
					<li class="">
						<a href="#" class="btn-small btn-primary" data-toggle="modal" data-target="#wip_filter_modal"><i class="fa fa-print"></i> Report</a>
					</li>  		
					<li>
						<a class="btn-small sb-open-right"><i class="fa fa-file-text-o"></i> Project Comments</a>
					</li>
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
					<div class="col-md-9">

						<div class="left-section-box clearfix">


						<?php if(@$this->session->flashdata('project_deleted')): ?>
							<div class="m-15">
								<div class="border-less-box alert alert-danger fade in">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<h4>Opps! No turning back now!</h4>
									<?php echo $this->session->flashdata('project_deleted');?>
								</div>
							</div>
						<?php endif; ?>

						<div class="clearfix"></div>


							<div class="box-head pad-10 clearfix">
								<div class="pull-right" style="margin-top: -15px;">
								<?php if($this->session->userdata('projects') >= 2): ?>
									<a href="<?php echo current_url(); ?>/add" class="btn btn-primary pull-right"><i class="fa fa-briefcase"></i>&nbsp; Add New</a>
								<?php endif; ?>
									<div class="clearfix"></div>
									<select class="form-control m-top-10 select-client-tbl right" style="float: right; width: 180px;">
										<option value="">Select Client</option>
										<?php $this->company->company_list('dropdown'); ?>
									</select>
									
									<select class="form-control m-top-10 select-status-tbl right"  style="float: right; width: 110px; margin-right: 5px;">
										<option value="">Status</option>
										<option value="wip">WIP</option>
										<option value="unset">Not WIP</option>
										<option value="invoiced">Invoiced</option>
										<option value="paid">Paid</option>
									</select>

									<?php if($this->session->userdata('user_role_id') == 3 || $this->session->userdata('user_role_id') == 2 || $this->session->userdata('user_role_id') == 8 || $this->session->userdata('user_role_id') == 7 ): ?>

									
										<select class="form-control m-top-10 select-personal right"  style="float: right; width: 120px; margin-right: 5px;">
											
											<option value="ORD">View All</option>

											<?php if($this->session->userdata('user_role_id') == 3 ){
													echo '<option value="PM">Personal</option>';
												}else if($this->session->userdata('user_role_id') == 2 ){
													echo '<option value="PA">Personal</option>';
												}elseif($this->session->userdata('user_role_id') == 8){
													echo '<option value="EST">Personal</option>';
											
												}
											?>

										</select>

									<?php endif; ?>


								</div>
								<label><?php echo $screen; ?> List</label><span> (<a href="#" data-placement="right" class="popover-test" title="" data-content="Hello there mate! Welcome to the clients screen." data-original-title="Welcome">?</a>)</span>
								<p>This is where the companies are listed.</p>
								<p><a href="#" class="tooltip-test" title="Tooltip">This link</a> and <a href="#" class="tooltip-test" title="Tooltip">that link</a> should have tooltips on hover.</p>								
							</div>
							<div class="box-area pad-10">
								<table id="projectTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead> <tr> <th>Number</th> <th>Project Name</th> <th>Client</th> <th>Category</th> <th>Job Date</th> <th>Total Cost</th> <th>Personal</th> <th>Status</th></tr> </thead> 
									 
									<tbody>
										<?php echo $this->projects->display_all_projects(); ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<div class="col-md-3">
						<div class="box">
							<div class="box-head pad-5">
								<label><i class="fa fa-info-circle fa-lg"></i> Information</label>
							</div>
							<div class="box-area pad-5">
								<p>
									The table can be sortable by the header. It has search feature using a sub text or keyword you are searching. Clicking the Name of the company will lead to the Company Details Screen.
								</p>
							</div>
						</div>
					</div>

					<div class="col-md-3">
						<div class="box">
							<div class="box-head pad-5">
								<label><i class="fa fa-info-circle fa-lg"></i> Color Codes: &nbsp; &nbsp;
								<strong class="wip">WIP</strong> &nbsp; &nbsp;
								<strong class="invoiced">Invoiced</strong> &nbsp; &nbsp;
								<strong class="paid">Paid</strong></label>
							</div>
						</div>
					</div>
					
				</div>				
			</div>
		</div>
	</div>
</div>
   
       


<?php $this->load->view('assets/logout-modal'); ?>

<!-- Modal -->
<div class="modal fade" id="loading_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-sm">
    <div class="modal-content">
       
      <div class="modal-body clearfix pad-10">

        <center><h3>Loading Please Wait</h3></center>
        <center><h2><i class="fa fa-circle-o-notch fa-spin fa-5x"></i></h2></center>
        <p>&nbsp;</p>
  
  

      </div>
    </div>
  </div>
</div>

<div class="report_result hide hidden"></div>

<!-- wip_filter_modal -->
<div class="modal fade" id="wip_filter_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Projects Report Filters</h4>
      </div>
      <div class="modal-body">

      	<?php $clients = array(); ?>

      	<?php
	      	foreach ($proj_t->result_array() as $row){array_push($clients,$row['company_name']);}
	      	$clients = array_unique($clients);
      	?>

      	<div class="input-group m-bottom-10">
      		<span class="input-group-addon" id="">
      			<i class="fa fa-briefcase"></i>
      		</span>
      		<?php asort($clients); ?>
      		<select class="form-control m-bottom-10 report_company">
      			<option value="">Select Client</option>     		

      			<?php
	      			foreach ($clients as $row => $value){echo '<option value="'.$value.'" >'.$value.'</option>';}
      			?> 
      		</select>
      	</div>

      	<style type="text/css">div.prj_status{ width: 100%; }</style>

      	<div class="input-group m-bottom-10">
      		<span class="input-group-addon" id="">
      			<i class="fa fa-user"></i>
      		</span>
      		<select class="form-control select-pm-tbl m-bottom-10">
      			<option value="">Select Project Manager</option>
      			<?php
      			foreach ($users->result_array() as $row){
      				if($row['user_role_id']==3):
                		echo '<option value="'.$row['user_first_name'].' '.$row['user_last_name'].'|'.$row['user_id'].'" >'.$row['user_first_name'].' '.$row['user_last_name'].'</option>';
      				endif;
      			}
      			?>
      		</select>
      	</div>


      	<div class="tooltip-enabled box-area clearfix input-group m-bottom-10" data-original-title="Notice: Selecting 'Not WIP' refers to projects that is never been accepted by the client as real project. 'WIP' are the projects currenlty on WIP. 'Invoiced' these are the projets are fully invoiced. 'Paid' are fully paid projects. ">
      		

      		<span class="input-group-addon" id="">
      			<i class="fa fa-list-alt"></i>
      		</span>
<select class="form-control prj_status m-bottom-10" id="prj_status">
      			<option selected="selected" value="notwip">Not WIP</option>
      			<option value="wip">Currently WIP</option>
      			<option value="invoiced">Invoiced</option>
      			<option value="paid">Paid</option>
      		</select>
      	</div>
      	

      	<div class="box-area clearfix  m-bottom-10">
      		<select class="form-control select-cat-tbl chosen-multi" id="select-cat-tbl" multiple="multiple">
      			<option selected="selected" value="Kiosk">Kiosk</option>
      			<option selected="selected" value="Full Fitout">Full Fitout</option>
      			<option selected="selected" value="Refurbishment">Refurbishment</option>
      			<option selected="selected" value="Strip Out">Strip Out</option>
      			<option selected="selected" value="Minor Works">Minor Works (Under $20,000.00)</option>
      			<option selected="selected" value="Maintenance">Maintenance</option>
      		</select>
      	</div>


      	<div class="input-group m-bottom-10">
      		<span class="input-group-addon" id="">
      			<i class="fa fa-calendar"></i> Site Start A
      		</span>
      		<input type="text" data-date-format="dd/mm/yyyy" placeholder="From" class="form-control datepicker" id="start_date_start" name="start_date_start" value="" >
      	</div>



      	<div class="input-group m-bottom-10">
      		<span class="input-group-addon" id="">
      			<i class="fa fa-calendar"></i> Site Start B
      		</span>
      		<input type="text" data-date-format="dd/mm/yyyy" placeholder="To" class="form-control datepicker" id="start_date" name="start_date" value="" >
      	</div>


      	<div class="input-group m-bottom-10">
      		<span class="input-group-addon" id="">$</span>
      		<input type="text" placeholder="Less Than Project Total Range" class="form-control number_format" id="cost_total" name="cost_total" value="">
      	</div>

      	<hr style="padding: 0;margin: 14px 0;">

      	<div class="input-group m-bottom-10">
      		<span class="input-group-addon" id="">
      			<i class="fa fa-calendar"></i> Site Finish A
      		</span>
      		<input type="text" data-date-format="dd/mm/yyyy" placeholder="From" class="form-control datepicker" id="finish_date_start" name="finish_date_start" value="" >
      	</div>


      	<div class="input-group m-bottom-10">
      		<span class="input-group-addon" id="">
      			<i class="fa fa-calendar"></i> Site Finish B
      		</span>
      		<input type="text" data-date-format="dd/mm/yyyy" placeholder="To" class="form-control datepicker" id="finish_date" name="finish_date" value="" >
      	</div>


      	<hr style="padding: 0;margin: 14px 0;">

      	<input type="hidden" id="doc_type" name="doc_type" value="Projects" >

      	<div class="input-group m-bottom-10">
      		<span class="input-group-addon" id="">
      			<i class="fa fa-calendar"></i> Project Date Created A
      		</span>
      		<input type="text" data-date-format="dd/mm/yyyy" placeholder="From" class="form-control datepicker" id="date_created_start" name="date_created_start" value="" >
      	</div>


      	<div class="input-group m-bottom-10">
      		<span class="input-group-addon" id="">
      			<i class="fa fa-calendar"></i> Project Date Created B
      		</span>
      		<input type="text" data-date-format="dd/mm/yyyy" placeholder="To" class="form-control datepicker" id="date_created" name="date_created" value="" >
      	</div>


      	<div class="input-group m-bottom-10">
      		<span class="input-group-addon" id="">Sort</span>         
      		<select class="wip_sort form-control" id="wip_sort" name="wip_sort" title="invoice_sort*">
      			<option value="clnt_asc">Client Name A-Z</option>  
      			<option value="clnt_desc">Client Name Z-A</option>
      			<option value="srtrt_d_asc">Start Date Ascending</option> 
      			<option value="srtrt_d_desc">Start Date Descending</option>
      			<option value="fin_d_asc">Finish Date Ascending</option> 
      			<option value="fin_d_desc">Finish Date Descending</option>    
      			<option value="prj_num_asc" selected="selected" >Project Number Ascending</option>  
      			<option value="prj_num_desc">Project Number Descending</option>                                     
      		</select>       
      	</div>

      	<div class="clearfix">
      		<button type="button" class="btn btn-primary print-wip pull-right" id="" data-dismiss="modal">Submit</button>
      		<button type="button" class="btn btn-default pull-right m-right-10" data-dismiss="modal">Cancel</button> 
      	</div>



      </div>
    </div>
  </div>
</div>
<!-- wip_filter_modal -->


<?php $this->bulletin_board->list_latest_post(); ?>