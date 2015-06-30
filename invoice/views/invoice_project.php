<?php $this->load->module('invoice'); ?>
<?php
/*
  $raw_date = explode('/',$date_site_finish);
  $raw_date_site_finish = $raw_date['1'].'/'.$raw_date['0'].'/'.$raw_date['2'];
  $date_end = strtotime($raw_date_site_finish);
  $date = strtotime("+14 day", $date_end);
  $invoice_date_final = date('d/m/Y',$date); 
*/
  $invoice_date_final = $date_site_finish;
  $has_invoice = $this->invoice->if_has_invoice($project_id);
  $if_been_invoiced = $this->invoice->if_invoiced($project_id);
  $show_job_book_details = $this->invoice->show_job_book($project_id);
?>

<?php if($if_been_invoiced > 0 || $job_date != ''): ?>
  <style type="text/css"> /*.progress_date,.final_payment,.progress-percent{pointer-events:none;} */</style>
<?php endif; ?>
<?php if($job_date == ''): ?>
  <style type="text/css">.progress_invoice_button{display: none; visibility: hidden;}</style>
<?php else: ?>
  <style type="text/css">
    .progress_date,.final_payment,.progress-percent{pointer-events:none;}
    .update_progress_values{display: none; visibility: hidden;}
  </style>
<?php endif; ?>

<?php if($this->session->userdata('is_admin') != 1): ?>
  <style type="text/css">.remove_link{display: none; visibility: hidden;}</style>
<?php endif; ?>

<?php if($this->session->userdata('invoice') != 2): ?>
  <style type="text/css">.progress_invoice_group,.progress_invoice{display: none; visibility: hidden;}</style>
<?php endif; ?>

<?php if($job_date != ''): ?>
  <style type="text/css">.save_progress_values,.update_progress_values_b{display: none; visibility: hidden;}</style>
<?php endif; ?>

<?php if($is_paid != 0): ?>
  <style type="text/css">.remove_recent_payment_b, .remove_recent_payment_a{display: none; visibility: hidden;}</style>
<?php endif; ?>



<div class="test"></div>

<div class="row pad-10">
  <div class="col-xs-12">

    <div class="box-head pad-bottom-10 clearfix">
      <div class="pull-right m-top-10 m-left-10">


      <?php if($if_been_invoiced == 0): ?>
        <?php  if($has_invoice > 0): ?>
          <button class="btn btn-warning update_progress_values"><i class="fa fa-floppy-o"></i> Update Progress Values</button>
        <?php else: ?>
          <button class="btn btn-success save_progress_values" ><i class="fa fa-floppy-o"></i> Save Progress Values</button>


<?php if($job_date != ''): ?>

          <?php if($is_paid == 0): ?>
            <button class="btn btn-warning update_progress_values" style="display:none;"><i class="fa fa-floppy-o"></i> Update Progress Values</button>
          <?php endif; ?>
<?php endif; ?>

        <?php endif; ?>

        <?php else: ?>
          <?php if($is_paid == 0): ?>
            <button class="btn btn-warning update_progress_values_b" ><i class="fa fa-floppy-o"></i> Update Progress Values</button>
         <?php endif; ?>


      <?php endif; ?>


      </div>

      <div class="pull-right m-right-10">
        <h4 class="m-top-20">Project Total: (ex-gst) <?php echo number_format($final_total_quoted); ?> &nbsp; &nbsp;
         Total Paid: $<?php echo number_format($this->invoice->get_total_amount_paid_project($project_id),2); ?></h4>
      </div>

      <label>Invoice</label><span>(<a href="#" data-placement="right" class="popover-test" title="" data-content="This is where the works of the selected Project are listed." data-original-title="Welcome">?</a>)</span>
      <p>This is where the invoicing of the project happens. </p>
    </div>

    <input type="hidden" class="project_total_raw" value="<?php echo $final_total_quoted; ?>">
    <input type="hidden" class="project_number" value="<?php echo $project_id; ?>">
    <input type="hidden" class="num_progress" value="<?php  echo ($has_invoice > 0 ? $has_invoice : '1' ); ?>">
    <input type="hidden" class="progress_invoice_id" value="">
    <input type="hidden" class="date_set_invoice_data" value="<?php echo date("d/m/Y"); ?>">

    <table class="table table-striped table-hover invoice-table">
      <thead>
        <tr>
          <th width="20%">Progress No.</th>
          <th width="15%">Percent</th>
          <th width="15%">Date</th>
          <th width="15%">Amount</th>
          <th width="20%">Action</th>
          <th width="15%">Outstanding</th>
        </tr>
      </thead>
      <tbody class="progress-body progress-body-list">


        <?php if($has_invoice>0): ?>
          <?php $this->invoice->list_project_invoice($project_id); ?>

        <?php else: ?>
          <tr>
            <td scope="row" class="t-head" id="progress-1">
              <div><input type="text" class="form-control final_payment" value="Final Payment" placeholder="Final Payment"></div>
            </td>
            <td>
              <div class="input-group">
                <div class="input-group-addon">%</div>
                <input type="text" class="form-control progress-percent" onclick="getHighlight('progress-0-percent')" onchange="final_progress(this)" value="100" placeholder="Percent" id="progress-0-percent" name="progress-1-percent"/>
              </div>
            </td>
            <td><div><input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control date_daily text-left progress_date" id="progress-0-date" name="progress-0-date" value="<?php echo $invoice_date_final; ?>"></div></td>
            <td><strong><div class="m-top-5">$<span class="total_cost_progress"><?php echo number_format($final_total_quoted); ?></span> ex-gst</div></strong></td>
            <td></td>
            <td><strong><div class="m-top-5"><span class="progress_outstanding"></span></div></strong></td>
          </tr>          
        <?php endif; ?>



        
      </tbody>      
      <tbody>
        <tr>
          <th scope="row" class="t-head text-right" colspan="3">
            <div class="m-top-5">Variation : </div>
          </th>
          <td><strong><div class="m-top-5">$<span class="total_cost_progress variation_total_cost"><?php echo number_format($variation_total); ?></span> ex-gst</div></strong></td>
          <td>
            <?php $variation_data = $this->invoice->fetch_vr($project_id); ?>
            <?php $outstanding = $this->invoice->get_current_balance($project_id,$variation_data['invoice_id'],$variation_total); ?>

            <?php  if($has_invoice > 0 && $variation_total > 0): ?>
              <?php if($this->invoice->if_has_vr($project_id) == 0 ): ?>
                  <?php if($this->invoice->is_all_paid($project_id) && $this->invoice->if_invoiced_all($project_id)): ?>                 
                    <div class="progress_vr_invoice_button"><button class="btn btn-primary  m-right-5 progress_invoice_variation" onclick="progress_invoice_variation(this)" id="VR_" data-toggle="modal" data-target="#set_invoice_modal"><i class="fa fa-file-text-o"></i> Set Invoice</button></div>
                  <?php endif; ?>
                 
              <?php else: ?>
                <div class="hide hidden progress-item" id="<?php echo $variation_data['invoice_id']; ?>"><?php echo $variation_data['invoice_id']; ?></div>

                <?php if($variation_data['is_paid'] == 0): ?>

                <div class="vr_bttn_group">

                  <div class="btn-group pull-left m-right-10 progress_invoice_group">
                    <button type="button" disabled="disabled" class="btn btn-primary progress_invoice"><i class="fa fa-file-text-o"></i> Invoiced</button>
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                      <span class="caret"></span>
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" role="menu" style="display: none;">
                      <li><a href="#" class="progress_invoice_resend"><i class="fa fa-files-o"></i> View Invoice</a></li>
                      <li class="remove_link"><a href="#" id="<?php echo $project_id; ?>" class="remove_vr"><i class="fa fa-exclamation-triangle"></i> Remove Invoice</a></li>
                    </ul>
                  </div>

                  <button class="btn btn-danger vr_paid" id="<?php echo $project_id; ?>_<?php echo $variation_data['invoice_id']; ?>" data-toggle="modal" data-target="#payment_modal" data-backdrop="static"><i class="fa fa-usd"></i> Payment</button>
                </div>

              <?php else: ?>
                <button class="btn btn-success progress_paid" id="<?php echo $project_id; ?>" data-toggle="modal" data-target="#payment_history_modal" data-backdrop="static"><i class="fa fa-usd"></i> Paid</button>

              <?php endif; ?>


            <?php endif; ?>
            <?php endif; ?>

          </td>
          <td><strong>$<span class="vr_outstanding"><?php echo number_format($outstanding); ?></span></strong></td>
        </tr>
        <tr>
          <td scope="row" class="t-head"></td>
          <td></td>
          <th class="text-right pad-10"><div class="m-top-5">Grand Total :</div></th>
          <td colspan="2"><strong><div class="m-top-5">$<span class="total_cost_progress"><?php echo number_format($final_total_quoted); ?></span> ex-gst &nbsp; &nbsp; $<span class="total_cost_progress"><?php echo number_format($final_total_quoted+($final_total_quoted*($admin_gst_rate/100))); ?></span> inc-gst</div></strong></td>
          <td>



          <?php if($if_been_invoiced > 0 && $has_invoice > 0): ?>
            <?php if($this->invoice->is_all_paid($project_id) && $this->invoice->if_invoiced_all($project_id)): ?>
              <?php if($is_paid == 0): ?>
                <button id="<?php echo $project_id; ?>" class="btn btn-danger set_project_as_paid"><i class="fa fa-usd"></i> Set this Project as Fully Paid</button>
              <?php endif; ?>
            <?php endif; ?>
          <?php endif; ?>



          </td>
         
        </tr>        
      </tbody>
    </table>

  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="set_invoice_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Set Job Book Notes</h4>
      </div>
      <div class="modal-body">

        <div class="input-group m-bottom-10 tooltip-test hidden hide" data-original-title="OPTIONAL: To add more recipients, put comma after each emails.">
          <span class="input-group-addon" id=""><i class="fa fa-user"></i> Optional</span>
          <input type="email" placeholder="CC Emails" class="form-control" id="cc_emails" name="cc_emails" value="">
        </div>

        <div class="hidden job_book_notes"><?php echo $show_job_book_details['notes']; ?></div>

        <input type="hidden" class="job_book_details_id" value="<?php echo $show_job_book_details['notes_id']; ?>">
        <input type="hidden" class="invoice_item_amount">

        <div class="m-bottom-10">
          <textarea class="form-control" id="invoice_notes" name="invoice_notes" placeholder="Invoice Notes" rows="10"></textarea>
        </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary set_invoice_modal_submit">Submit</button>
      </div>
    </div>
  </div>
</div>



<!-- Modal -->
<div class="modal fade" id="payment_history_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Payments</h4>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">

            <input type="hidden" name="po_number_item" id="po_number_item" class="po_number_item">
            <input type="hidden" name="po_actual_balance" id="po_actual_balance" class="po_actual_balance">
            <input type="hidden" name="invoice_id_progress" id="invoice_id_progress" class="invoice_id_progress">
            <input type="hidden" name="progress_id" id="progress_id" class="progress_id">
            <input type="hidden" name="invoice_outstanding" id="invoice_outstanding" class="invoice_outstanding">

            

            <div class="col-sm-12 m-top-15">
              <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Amount</th>
                  <th>Reference Number</th>
                </tr>
              </thead>
              <tbody class="payment_history history_b">

              </tbody>
            </table>
              <button id="<?php echo $project_id; ?>" class="btn btn-danger remove_recent_payment_b">Remove Recent Transaction</button>
            </div>
 
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default po_cancel_values" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>




<!-- Modal -->
<div class="modal fade" id="payment_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Payments</h4>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">

            <div class="col-sm-12 border-bottom">
              <div class="clearfix col-sm-4">
                <p>Description: <strong class="po_desc_mod">Progress</strong></p>
              </div>
              <div class="clearfix col-sm-4">
                <p>Total: $<strong class="po_total_mod">00000</strong> ex-gst</p>
              </div>
              <div class="clearfix col-sm-4">
                <p>Outstanding: <strong class="po_balance_mod">$0.00</strong></p>
              </div>          
            </div>

            <div class="po_error"></div>


            <div class="col-sm-6">
              <div class="clearfix m-top-15">
                <label for="po_date_value" class="col-sm-3 control-label text-left m-top-10" style="font-weight: normal;">Date*</label>
                <div class="col-sm-9">
                  <div class="input-group">
                    <span class="input-group-addon" id=""><i class="fa fa-calendar"></i></span>
                    <input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control datepicker" id="po_date_value" tabindex="1" name="po_date_value" value="<?php echo date("d/m/Y"); ?>" >
                  </div>                
                  
                </div>
              </div>
            </div>

            <input type="hidden" name="po_number_item" id="po_number_item" class="po_number_item">
            <input type="hidden" name="po_actual_balance" id="po_actual_balance" class="po_actual_balance">
            <input type="hidden" name="invoice_id_progress" id="invoice_id_progress" class="invoice_id_progress">
            <input type="hidden" name="progress_id" id="progress_id" class="progress_id">
            <input type="hidden" name="invoice_outstanding" id="invoice_outstanding" class="invoice_outstanding">

            <div class="col-sm-6">
              <div class="clearfix m-top-15">
                <label for="po_amount_value" class="col-sm-3 control-label text-left m-top-10" style="font-weight: normal;">Amount*</label>
                <div class="col-sm-9">
                  <div class="input-group m-bottom-10">
                    <span class="input-group-addon" id="">$</span>
                    <input type="text" placeholder="Amount" class="form-control number_format" id="progress_payment_amount_value" name="progress_payment_amount_value" value="" tabindex="2">
                    <span class="input-group-addon" id="">ex-gst</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="clearfix">
                <label for="invoice_payment_reference_no" class="col-sm-5 control-label text-left m-top-10" style="font-weight: normal;">Reference No*</label>
                <div class="col-sm-7">
                  <input type="text" placeholder="Reference Number" class="form-control" id="invoice_payment_reference_no" name="invoice_payment_reference_no" value="" tabindex="3">
                </div>
              </div>
            </div>

            <div class="col-sm-5 pull-right">
              <div class="clearfix">
                <div class="input-group pad-right-5" style="height: 35px;">
                  <div class="input-group-addon "><i class="fa fa-exclamation-triangle"></i> Set As Paid</div>
                  <input type="text" class="hidden" disabled="disabled">
                  <div class="input-group-addon"><input type="checkbox" name="is_paid_check" id="is_paid_check"></div>
                </div>
              </div>
            </div>

            <div class="col-sm-12">
              <div class="clearfix  m-top-10">
                <label for="po_notes_value" class="col-sm-2 control-label text-left m-top-10" style="font-weight: normal;">Notes</label>
                <div class="col-sm-10">
                  <input type="text" placeholder="Notes" class="form-control" id="po_notes_value" name="po_notes_value" value="" tabindex="4">
                </div>
              </div>
            </div>

            <div class="col-sm-12 m-top-15">
              <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Amount</th>
                  <th>Reference Number</th>
                </tr>
              </thead>
              <tbody class="payment_history payment_history_a">

              </tbody>
            </table>
              <button id="<?php echo $project_id; ?>" class="btn btn-danger remove_recent_payment_a">Remove Recent Transaction</button>
            </div>
 
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default po_cancel_values" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success payment_set_values"><i class="fa fa-floppy-o"></i> Save Payment</button>
      </div>
    </div>
  </div>
</div>
