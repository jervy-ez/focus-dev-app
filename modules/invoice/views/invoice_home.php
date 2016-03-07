<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('invoice'); ?>
<?php $this->load->module('company'); ?>
<?php $this->load->module('bulletin_board'); ?>

<?php //$this->invoice->reload_invoiced_amount(); ?>

<!-- title bar -->
<div class="container-fluid head-control">
	<div class="container-fluid">
		<div class="row">

			<div class="col-md-6 col-sm-4 col-xs-12 pull-left">
				<header class="page-header">
					<h3><?php $datestring = "%l, %F %d, %Y"; $time = time(); //use time() for timestamp  ?>
						<?php echo $screen; ?><br><small><?php echo mdate($datestring, $time); #echo date("l, F d, Y"); ?></small>
					</h3>
				</header>
			</div>

			<div class="page-nav-options col-md-6 col-sm-8 col-xs-12 pull-right hidden-xs">
				<ul class="nav nav-tabs navbar-right">
					<li>
						<a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Home</a>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>projects" class="btn-small">Projects</a>
					</li>
					<li class="hide">
						<a href="" class="btn-small"><i class="fa fa-magic"></i> Tour</a>
					</li>
          <li>
            <a href=""  class="report_btn" data-toggle="tab"><i class="fa fa-print"></i> Report</a>
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

<div class="test"></div>
	<!-- Example row of columns -->
	<div class="row">				
		<?php $this->load->view('assets/sidebar'); ?>
		<div class="section col-sm-12 col-md-11 col-lg-11">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<div class="left-section-box po">

								<?php if(@$error): ?>
									<div class="pad-10 no-pad-t">
										<div class="border-less-box alert alert-danger fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>Oh snap! You got an error!</h4>
											<?php echo $error;?>
										</div>
									</div>
								<?php endif; ?>

								<?php if(@$this->session->flashdata('success_add')): ?>
									<div class="pad-10 no-pad-t">
										<div class="border-less-box alert alert-success fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>Cheers!</h4>
											<?php echo $this->session->flashdata('success_add');?>
										</div>
									</div>
								<?php endif; ?>

								<?php if(@$this->session->flashdata('success_remove')): ?>
									<div class="pad-10 no-pad-t">
										<div class="border-less-box alert alert-danger fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>I hope you did the right thing.</h4>
											<?php echo $this->session->flashdata('success_remove');?>
										</div>
									</div>
								<?php endif; ?>	







								<div class="row clearfix">

										<div class="col-lg-4 col-md-12">
											<div class="box-head pad-left-15 clearfix">
												<label><?php echo $screen; ?> List</label>
												<div id="aread_test"></div>
											</div>
										</div>
										
										<div class="col-lg-8 col-md-12">
											<div class="pad-left-15 pad-right-10 clearfix box-tabs">	
												<ul id="myTab" class="nav nav-tabs pull-right">
                          <li class="hide hidden">
                            <a href="#progress_claims" data-toggle="tab"><i class="fa fa-file-text-o fa-lg"></i> Progress Claims</a>
                          </li>
													<li class="active">
														<a href="#outstanding" data-toggle="tab"><i class="fa fa-level-up fa-lg"></i> Outstanding</a>
													</li>
													<li class="">
														<a href="#paid" data-toggle="tab"><i class="fa fa-check-square-o fa-lg"></i> Paid</a>
													</li>
												</ul>
											</div>
										</div>

								</div>

								<div class="box-area">

									<div class="box-tabs m-bottom-15">
										<div class="tab-content">
                    <div class="tab-pane  clearfix" id="progress_claims">
                        <div class="m-bottom-15 clearfix">
                          <div class="box-area">
                            <table id="companyTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                              <thead><tr><th>Project Number</th><th>Project Name</th><th>Progress Claim</th><th>Client</th><th>Percent</th><th>Invoiced Date</th><th>Amount</th><th>Outstanding</th></tr></thead>
                              <tbody>
                                <?php $this->invoice->invoice_table(); ?>
                              </tbody>
                            </table>                            
                          </div>
                        </div>
                      </div>
											<div class="tab-pane  clearfix active" id="outstanding">
												<div class="m-bottom-15 clearfix">
													<div class="box-area">
														<table id="invoice_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
															<thead><tr><th>Project Number</th><th>Project Name</th><th>Progress Claim</th><th>Client</th><th>Percent</th><th>Invoiced Date</th><th>Amount</th><th>Outstanding</th></tr></thead>
															<tbody>
																<?php $this->invoice->invoice_table(); ?>
															</tbody>
														</table>														
													</div>
												</div>
											</div>
											<div class="tab-pane clearfix" id="paid">
												<div class="m-bottom-15 clearfix">
												<div class="box-area">
														<table id="invoice_paid_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
															<thead><tr><th>Project Number</th><th>Project Name</th><th>Progress Claim</th><th>Client Name</th><th>Invoiced Date</th><th>Amount</th><th>Outstanding</th></tr></thead>
															<tbody>
																<?php $this->invoice->paid_table(); ?>
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

  

<!-- wip_filter_modal -->
<!--
<div class="modal fade" id="invoiceModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Invoice Filter screen</h4>
      </div>
      <div class="modal-body">

      	<div class="input-group m-bottom-10">
      		<span class="input-group-addon" id="">
      			<i class="fa fa-sort-numeric-asc"></i>
      		</span>
      		<input type="text" placeholder="Project Number" class="form-control" id="invoice_project_number" name="invoice_project_number" value="" >
      	</div>

      	<div class="input-group m-bottom-10">
      		<span class="input-group-addon" id="">
      			<i class="fa fa-sort-amount-asc"></i>
      		</span>
      		<input type="text" placeholder="Project Name" class="form-control" id="invoice_project_name" name="invoice_project_name" value="" >
      	</div>

      	<div class="input-group m-bottom-10">
      		<span class="input-group-addon" id="">
      			<i class="fa fa-credit-card"></i>
      		</span>
      		<input type="text" placeholder="Progress Claim" class="form-control" id="invoice_progress_claim" name="invoice_progress_claim" value="" >
      	</div>

      	<div class="input-group m-bottom-10">
      		<span class="input-group-addon" id="">
      			<i class="fa fa-briefcase"></i>
      		</span>
      		<input type="text" placeholder="Client Name" class="form-control" id="invoice_client_name" name="invoice_client_name" value="" >
      	</div>




      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="filter_invoice_table" data-dismiss="modal"><i class="fa fa-filter"></i> Filter</button>
      </div>
    </div>
  </div>
</div>
-->
<!-- wip_filter_modal -->




<?php if($this->session->userdata('is_admin') == 1 || $this->session->userdata('user_role_id') == 5 || $this->session->userdata('user_role_id') == 6 ): ?>

<!-- payments modal -->
<div class="modal fade" id="invoice_payment_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Payments</h4>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">

            <div class="col-sm-12 border-bottom">

              <div class="clearfix col-sm-6">
                <p>Total Ex-GST: $<strong class="po_total_mod">0.00</strong></p>
              </div>
              <div class="clearfix col-sm-6">
                <p>Total Inc-GST: $<strong class="po_total_mod_inc_gst">0.00</strong></p>
              </div>

              <div class="clearfix col-sm-6">
                <p>Description: <strong class="po_desc_mod"></strong></p>
              </div>
              <div class="clearfix col-sm-6">
                <p>Outstanding Ex-GST: $<strong class="po_balance_mod">0.00</strong></p>
              </div>          
            </div>

            <div class="po_error"></div>


            <div class="col-sm-6">
              <div class="clearfix m-top-15">
                <label for="" class="col-sm-3 control-label text-left m-top-10" style="font-weight: normal;">Date*</label>
                <div class="col-sm-9">
                  <div class="input-group">
                    <span class="input-group-addon" id=""><i class="fa fa-calendar"></i></span>
                    <input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control datepicker" id="invoice_payment_date" tabindex="1" name="" value="<?php echo date("d/m/Y"); ?>">
                  </div>                
                  
                </div>
              </div>
            </div>

            <input type="hidden" name="invoice_project_id" id="invoice_project_id" class="invoice_project_id">
            <input type="hidden" name="invoice_id" id="invoice_id" class="invoice_id">
            <input type="hidden" name="invoice_order" id="invoice_order" class="invoice_order">
            <input type="hidden" name="invoice_gst" id="invoice_gst" class="invoice_gst" value="">
            <input type="hidden" name="invoice_current_value" id="invoice_current_value" class="invoice_current_value" value="">
            <input type="hidden" name="invoice_current_value_inc_gst" id="invoice_current_value_inc_gst" class="invoice_current_value_inc_gst" value="">

            <div class="col-sm-6">
              <div class="clearfix m-top-15">
                <label for="po_amount_value" class="col-sm-3 control-label text-left m-top-10" style="font-weight: normal;">Amount*</label>
                <div class="col-sm-9">
                  <div class="input-group m-bottom-10">
                    <span class="input-group-addon" id="">$</span>
                    <input type="text" placeholder="Amount" class="form-control amount_ext_gst" id="amount_ext_gst" name="" value="" tabindex="2">
                    <span class="input-group-addon" id="">ex-gst</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="clearfix">
                <label for="" class="col-sm-6 control-label text-left m-top-10" style="font-weight: normal;">Reference Name*</label>
                <div class="col-sm-6">
                  <input type="text" placeholder="Reference Name" class="form-control" id="invoice_payment_reference" name="" value="" tabindex="3">
                </div>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="clearfix">
                <label for="po_amount_value" class="col-sm-3 control-label text-left m-top-10" style="font-weight: normal;"></label>
                <div class="col-sm-9">
                  <div class="input-group m-bottom-10">
                    <span class="input-group-addon" id="">$</span>
                    <input type="text" placeholder="Amount" class="form-control amount_inc_gst" id="amount_inc_gst" name="" value="" tabindex="2">
                    <span class="input-group-addon" id="">inc-gst</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-sm-12">
              <div class="clearfix  m-top-10">
                <label for="" class="col-sm-1 control-label text-left m-top-10" style="font-weight: normal;">Notes</label>
                <div class="col-sm-11">
                  <div class="input-group m-bottom-10">
                    <input type="text" placeholder="Notes" class="form-control" id="invoice_payment_notes" name="" value="" tabindex="4">
                    <span class="input-group-addon" id=""><i class="fa fa-exclamation-triangle"></i> Set As Paid <input type="checkbox" name="is_invoice_paid_check" id="is_invoice_paid_check"></span>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-sm-12 m-top-15">
              <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Amount Ext-GST</th>
                  <th>Reference Name</th>
                </tr>
              </thead>
              <tbody class="payment_history"></tbody>
            </table>
              <button id="" class="btn btn-danger invoice_remove_trans">Remove Recent Transaction</button>
            </div>
 
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default po_cancel_values" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success invoice_payment_bttn"><i class="fa fa-floppy-o"></i> Save Payment</button>
      </div>
    </div>
  </div>
</div>
<!-- payments modal -->







<!-- paid modal -->
<div class="modal fade" id="invoice_paid_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Payments</h4>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">

            <div class="col-sm-12 border-bottom">

              <div class="clearfix col-sm-6">
                <p>Description: <strong class="po_desc_mod"></strong></p>
              </div>
              <div class="clearfix col-sm-6">
                <p>Outstanding: $<strong class="po_balance_mod">0.00</strong></p>
              </div>          
            </div>


            <input type="hidden" name="invoice_project_id" id="invoice_project_id" class="invoice_project_id">
            <input type="hidden" name="invoice_id" id="invoice_id" class="invoice_id">
            <input type="hidden" name="invoice_order" id="invoice_order" class="invoice_order">
            <input type="hidden" name="invoice_gst" id="invoice_gst" class="invoice_gst" value="">
            <input type="hidden" name="invoice_current_value" id="invoice_current_value" class="invoice_current_value" value="">
            <input type="hidden" name="invoice_current_value_inc_gst" id="invoice_current_value_inc_gst" class="invoice_current_value_inc_gst" value="">
 
  

            <div class="col-sm-12 m-top-15">
              <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Amount Ext-GST</th>
                  <th>Reference Number</th>
                </tr>
              </thead>
              <tbody class="payment_history"></tbody>
            </table>
              <button id="" class="btn btn-danger invoice_remove_trans">Remove Recent Transaction</button>
            </div>
 
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default po_cancel_values" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success invoice_payment_bttn"><i class="fa fa-floppy-o"></i> Save Payment</button>
      </div>
    </div>
  </div>
</div>
<!-- paid modal -->
<?php endif; ?>


<div class="po_legend hide">
  <p class="pad-top-5 m-left-10"> &nbsp;  &nbsp; <span class="ex-gst">Ex GST</span> &nbsp;  &nbsp; <span class="inc-gst">Inc GST</span></p>
</div>






<!-- Modal -->
<div class="modal fade" id="filter_invoice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Invoice Filter</h4>
        <!-- <span> Note: <strong>State is required</strong>. The rest, if blank it selects all.</span> -->
      </div>
      <div class="modal-body clearfix pad-10">

        <div class="error_area"></div>



        <div class="">
          <div class="input-group m-bottom-10">
            <span class="input-group-addon">
              Clinet
            </span>
            <select class="form-control client_invoice chosen" name="client_invoice" id="client_invoice">
              <option value="">Select Client</option>
              <?php $this->company->company_list('dropdown'); ?>
            </select>
          </div>
        </div>
        
        <div class="">
          <div class="input-group m-bottom-10">
            <span class="input-group-addon">
              Project Number
            </span>
            <input type="text" class="form-control project_number" id="project_number" placeholder="Project Number" name="project_number" value="">
          </div>
        </div>

        <div class="">
          <div class="input-group m-bottom-10">
            <span class="input-group-addon">
              Progress Claim
            </span>
             

            <select class="progress_claim chosen-multi" id="progress_claim" multiple="multiple">
              <option value="1">Progress 1</option>
              <option value="2">Progress 2</option>
              <option value="3">Progress 3</option>
              <option value="4">Progress 4</option>
              <option value="5">Progress 5</option>
              <option value="F">Final Invoice</option>
              <option value="VR">Variation</option>
            </select>

          </div>
        </div>




        <div class="input-group m-bottom-10">
          <span class="input-group-addon" id="">
            Project Manager
          </span>
          <select class="form-control project_manager m-bottom-10" id="project_manager">
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

        <div class="">
          <div class="input-group m-bottom-10">
            <span class="input-group-addon">
              Invoice Status
            </span>
             

            <select class="invoice_status chosen " id="invoice_status" >
              <option value="1">Un-Invoiced</option>
              <option value="2" selected="">Invoiced</option>
              <option value="3">Paid</option>
              <option value="4">Outstanding</option>
            </select>

          </div>
        </div>




        <div class="col-md-6 col-sm-6 col-xs-12 clearfix invoice_date" style="margin-left: -5px; display:none;">
          <div class="input-group m-bottom-10">
            <span class="input-group-addon" id="">
              Invoicing Date A
            </span>         
            <input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control letter_segment datepicker invoice_date_a" id="invoice_date_a" name="invoice_date_a" value="">
          </div>
        </div>


        <div class="col-md-6 col-sm-6 col-xs-12 clearfix invoice_date" style="display:none;">
          <div class="input-group m-bottom-10">
            <span class="input-group-addon" id="">
              Invoicing Date B
            </span>         
            <input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control letter_segment datepicker invoice_date_b" id="invoice_date_b" name="invoice_date_b" value="">
          </div>
        </div>





        <div class="col-md-6 col-sm-6 col-xs-12 clearfix invoiced_date" style="margin-left: -5px;  display:block;">
          <div class="input-group m-bottom-10">
            <span class="input-group-addon" id="">
              Invoiced Date A
            </span>         
            <input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control datepicker invoiced_date_a" id="invoiced_date_a" name="invoiced_date_a" value="">
          </div>
        </div>


        <div class="col-md-6 col-sm-6 col-xs-12 clearfix invoiced_date"  style="display:block;">
          <div class="input-group m-bottom-10">
            <span class="input-group-addon" id="">
              Invoiced Date B
            </span>         
            <input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control datepicker invoiced_date_b" id="invoiced_date_b" name="invoiced_date_b" value="">
          </div>
        </div>

        <div class="input-group m-bottom-10">
          <span class="input-group-addon" id="">Sort</span>         
          <select class="invoice_sort form-control" id="invoice_sort" name="invoice_sort" title="invoice_sort*">
            <option value="clnt_asc">Client Name A-Z</option>  
            <option value="clnt_desc">Client Name Z-A</option>
            <option value="inv_d_asc">Invoiced Date Oldest First</option> 
            <option value="inv_d_desc">Invoiced Date Newest First</option>    
            <option value="prj_num_asc">Project Number Asc</option>  
            <option value="prj_num_desc">Project Number Desc</option>                                     
          </select>       
        </div>

        <div class="pull-right">
          <p>&nbsp;</p>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary invoice_filter_submit">Submit</button>
        </div>

      </div>
    </div>
  </div>
</div>



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



<style type="text/css">
  /*.po-area #companyTable_length, .po-area #companyTable_filter{
    display: none;
    visibility: hidden;
  }*/
  .ex-gst{
    color: rgb(219, 0, 255);  font-weight: bold;
  }

  .inc-gst{
    color: rgb(31, 121, 52);  font-weight: bold;
  }
</style>
<?php $this->bulletin_board->list_latest_post(); ?>
<?php $this->load->view('assets/logout-modal'); ?>