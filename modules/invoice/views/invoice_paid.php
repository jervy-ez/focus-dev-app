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

                <?php if(isset($error)): ?>
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
                         
                          <li class="">
                            <a href="<?php echo base_url(); ?>invoice"  ><i class="fa fa-level-up fa-lg"></i> Outstanding</a>
                          </li>
                          <li class="active">
                            <a href="#paid" data-toggle="tab"><i class="fa fa-check-square-o fa-lg"></i> Paid</a>
                          </li>
                        </ul>
                      </div>
                    </div>

                </div>

                <div class="box-area">

                  <div class="box-tabs m-bottom-15">
                    <div class="tab-content">
                    
                      <div class="tab-pane clearfix active" id="paid">
                        <div class="m-bottom-15 clearfix">
                          

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