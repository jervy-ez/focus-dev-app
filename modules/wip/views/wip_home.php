<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>
<?php $this->load->module('projects'); ?>
<?php $this->load->module('invoice'); ?>
<?php $this->load->module('wip'); ?>
<?php $this->load->module('bulletin_board'); ?>
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
					<li>
						<a href="#" class="btn-small btn-primary" data-toggle="modal" data-target="#wip_filter_modal"><i class="fa fa-print"></i> Report</a>
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

<div class="container-fluid">
	<!-- Example row of columns -->
	<div class="row">				
		<?php $this->load->view('assets/sidebar'); ?>
		<div class="section col-sm-12 col-md-11 col-lg-11">
			<div class="container-fluid">

				<div class="row">
					<div class="col-md-12">

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


            <?php $arrs = array(); 
              foreach ($proj_t->result_array() as $row){
              if($this->invoice->if_invoiced_all($row['project_id'])  && $this->invoice->if_has_invoice($row['project_id']) > 0 ){     }else{
                array_push($arrs,$row['project_id']);
              }
              $arr = implode(',', $arrs);
            }?>

						<div class="clearfix"></div>

							<div class="box-head pad-10 clearfix">
								<div class="pull-right" style="margin-top: -15px;">
									<div class="clearfix m-top-20">
										<div class="box-content box-list collapse in prj_cmmnt_area pull-right" style="display:none; visibility: hidden;"><ul><li><p>No posted comments yet!</p></li></ul></div>
										<div class="box-area  pull-right"  style="">

                      <!-- <p class="totals_wip"><?php //echo $this->wip->sum_total_wip_cost($arr); ?></p> -->


                      <p class="totals_wip">
                        <span class="wip_project_total" style="font-size: 16px;"><strong>Project Total: </strong> $<span id="wip_total_value"></span></span> &nbsp;&nbsp;&nbsp;&nbsp; 
                        <span class="wip_project_estimate green-estimate"><strong>Total Estimated:</strong> $<span id="wip_total_estimated"></span></span> &nbsp;&nbsp;&nbsp;&nbsp; 
                        <span class="wip_project_quoted"><strong>Total Quoted: </strong> $<span id="wip_total_quoted"></span></span>
                      </p>



                    </div>										
									</div>
								</div>
								<label><?php echo $screen; ?> List</label>							
							</div>
							<div class="box-area pad-10">
								<div class="wip-area-print">
									<table id="wipTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
										<thead> <tr> <th>Number</th> <th>Project Name</th> <th>Client</th> <th>Project Manager</th><th>Job Date</th><th>Date Start</th><th>Date Finish</th> <th >job_category</th><th>Project Total</th><th>date_finish_mod</th>  </tr> </thead> 

										<tbody>

											<?php
                      $estimated = 0;
                      $quoted = 0;
											foreach ($proj_t->result_array() as $row){


												if($this->invoice->if_invoiced_all($row['project_id'])  && $this->invoice->if_has_invoice($row['project_id']) > 0 ){
													
												}else{


													$date_finish_mod = strtotime(str_replace('/', '-', $row['date_site_finish']));

													echo '<tr><td><a onmouseover="showProjectCmmnts('.$row['project_id'].')" href="'.base_url().'projects/view/'.$row['project_id'].'" >'.$row['project_id'].'</a></td><td>'.$row['project_name'].'</td><td>'.$row['company_name'].'</td><td>'.$row['user_first_name'].' '.$row['user_last_name'].'</td><td>'.$row['job_date'].'</td><td>'.$row['date_site_commencement'].'</td><td>'.$row['date_site_finish'].'</td><td>'.$row['job_category'].'</td>';

													if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 || $row['variation_total'] > 0.00 ){
														echo '<td>'.number_format($row['project_total']+$row['variation_total']).'</td>';
                            $quoted = $quoted + $row['project_total']+$row['variation_total'];

													}else{
														echo '<td class="green-estimate">'.number_format($row['budget_estimate_total']).'</td>';
                            $estimated = $estimated + $row['budget_estimate_total'];
													}

													echo '<td>'.$date_finish_mod.'</td></tr>';
												}

											}
											?>

										</tbody>
									</table>


                  <script type="text/javascript">
                    $('#wip_total_value').text('<?php echo number_format($estimated+$quoted,2); ?>');
                    $('#wip_total_estimated').text('<?php echo number_format($estimated,2); ?>');
                    $('#wip_total_quoted').text('<?php echo number_format($quoted,2); ?>');
                  </script>					
								</div>
							</div>
						</div>
					</div>
				</div>				
			</div>
		</div>
	</div>
</div>

<div class="dataTables_info" id="print_wip_table_area"></div>
<style type="text/css">
 

</style>

<script type="text/javascript">$('.dataTables_filter').remove();</script>

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
        <h4 class="modal-title" id="myModalLabel">WIP Filter</h4>
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
      		<select class="form-control report_company m-bottom-10">
      			<option value="">Select Client</option>     		

      			<?php
	      			foreach ($clients as $row => $value){echo '<option value="'.$value.'" >'.$value.'</option>';}
      			?> 
      		</select>
      	</div>

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

      	<hr />

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

        <input type="hidden" id="doc_type" name="doc_type" value="WIP" >
        <input type="hidden" id="date_created_start" name="date_created_start" value="" >
        <input type="hidden" id="date_created" name="date_created" value="" >
        <input type="hidden" id="prj_status" name="prj_status" value="" >

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



      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary print-wip" id="" data-dismiss="modal">Submit</button> <!-- id="filter_wip_table" -->
      </div>
    </div>
  </div>
</div>
<!-- wip_filter_modal -->
<?php $this->bulletin_board->list_latest_post(); ?>