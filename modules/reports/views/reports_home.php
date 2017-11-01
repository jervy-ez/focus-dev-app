<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>
<?php $this->load->module('projects'); ?>
<?php $this->load->module('purchase_order'); ?>
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
					<li>
						<a href="" class="btn-small"><i class="fa fa-magic"></i> Tour</a>
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
													<li class="active">
														<a href="#outstanding" data-toggle="tab"><i class="fa fa-level-up fa-lg"></i> Outstanding</a>
													</li>
													<li class="">
														<a href="#reconciled" data-toggle="tab"><i class="fa fa-check-square-o fa-lg"></i> Reconciled</a>
													</li>
                          <li class="">
                            <a href="" data-toggle="modal" data-target="#po_date_filter_modal"><i class="fa fa-filter fa-lg"></i> Filter by Date</a>
                          </li>
												</ul>
											</div>
										</div>

								</div>

								<div class="box-area">

									<div class="box-tabs m-bottom-15">
										<div class="tab-content">
											<div class="tab-pane  clearfix active" id="outstanding">
												<div class="m-bottom-15 clearfix">


													<div class="box-area po-area">
														<table id="po_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
															<thead><tr><th>PO Number</th><th>Project Number</th><th>CPO Date</th><th>Job Description</th><th>Contractor</th><th>Project Name</th><th>Job Date</th><th>Client</th><th>Project Manager</th><th>Price</th><th>Balance</th></tr></thead>
															<tbody>

																<?php
																	foreach ($po_list->result_array() as $row){

                                    $prj_defaults = $this->projects->display_project_applied_defaults($row['project_id']);

																		echo '<tr id="'.$prj_defaults['admin_gst_rate'].'"><td><a href="#" data-toggle="modal" data-target="#invoice_po_modal" data-backdrop="static" onclick="select_po_item(\''.$row['works_id'].'-'.$row['project_id'].'\');" id="'.$row['works_id'].'-'.$row['project_id'].'" class="select_po_item">'.$row['works_id'].'</a></td><td><a href="'.base_url().'projects/view/'.$row['project_id'].'" >'.$row['project_id'].'</a></td><td>'.$row['work_cpo_date'].'</td><td><a href="'.base_url().'works/update_work_details/'.$row['project_id'].'/'.$row['works_id'].'">';

																		if($row['contractor_type']==2){

																			if($row['job_sub_cat']=='Other'){
																				echo $row['other_work_desc'];
																			}else{ 
                                        echo $row['job_sub_cat'];
																			}

																		}elseif($row['contractor_type']==3){
																			if($row['supplier_cat_name']=='Other'){
																				echo $row['other_work_desc'];
																			}else{
																				echo $row['supplier_cat_name'];
																			}
																		}else{ }

																		echo '</a></td><td>'.$row['contractor_name'].'</td><td>'.$row['project_name'].'</td><td>'.$row['job_date'].'</td><td>'.$row['client_name'].'</td><td>'.$row['user_first_name'].' '.$row['user_last_name'].'</td><td>'.number_format($row['price'],2).'</td>';
                                    echo '<td>'.number_format($this->purchase_order->check_balance_po($row['works_id']),2).'</td>';
                                    echo '</tr>';
																  }
																?>

                                <?php 
                                  foreach ($work_joinery_list->result_array() as $row_j){

                                    $j_prj_defaults = $this->projects->display_project_applied_defaults($row_j['project_id']);

                                    echo '<tr id="'.$j_prj_defaults['admin_gst_rate'].'"><td><a href="#" data-toggle="modal" data-target="#invoice_po_modal" data-backdrop="static" onclick="select_po_item(\''.$row_j['works_id'].'-'.$row_j['work_joinery_id'].'-'.$row_j['project_id'].'\');" id="'.$row_j['works_id'].'-'.$row_j['work_joinery_id'].'-'.$row_j['project_id'].'" class="select_po_item">'.$row_j['works_id'].'-'.$row_j['work_joinery_id'].'</a></td><td><a href="'.base_url().'projects/view/'.$row_j['project_id'].'" >'.$row_j['project_id'].'</a></td><td>'.$row_j['work_cpo_date'].'</td><td><a href="'.base_url().'works/update_work_details/'.$row_j['project_id'].'/'.$row_j['works_id'].'">';
                                    echo $row_j['joinery_name'];
                                    echo '</a></td><td>'.$row_j['contractor_name'].'</td><td>'.$row_j['project_name'].'</td><td>'.$row_j['job_date'].'</td><td>'.$row_j['client_name'].'</td><td>'.$row_j['user_first_name'].' '.$row_j['user_last_name'].'</td><td>'.number_format($row_j['price'],2).'</td>';
                                    echo '<td>'.number_format($this->purchase_order->check_balance_po($row_j['works_id'],$row_j['work_joinery_id']),2).'</td>';
                                    echo '</tr>';
                                  } 
                                ?>

															</tbody>
														</table>
													</div>



												</div>
											</div>
											<div class="tab-pane  clearfix" id="reconciled">

												<div class="m-bottom-15 clearfix">

												<div class="box-area po-area">
														<table id="reconciled_list_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
															<thead><tr><th>PO Number</th><th>Project Number</th><th>CPO Date</th><th>Job Description</th><th>Contractor</th><th>Project Name</th><th>Reconciled Date</th><th>Client</th><th>Project Manager</th><th>Price</th><th>Balance</th></tr></thead>
                              <tbody>

                              <?php
                                  foreach ($reconciled_list->result_array() as $row){

                                    echo '<tr><td><a href="#" data-toggle="modal" data-target="#reconciliated_po_modal" data-backdrop="static" id="'.$row['works_id'].'-'.$row['project_id'].'" onclick="return_outstanding_po_item(\''.$row['works_id'].'-'.$row['project_id'].'\');" class="return_outstanding_po_item">'.$row['works_id'].'</a></td><td><a href="'.base_url().'projects/view/'.$row['project_id'].'" >'.$row['project_id'].'</a></td><td>'.$row['work_cpo_date'].'</td><td><a href="'.base_url().'works/update_work_details/'.$row['project_id'].'/'.$row['works_id'].'">';

                                    if($row['contractor_type']==2){

                                      if($row['job_sub_cat']=='Other'){
                                        echo $row['other_work_desc'];
                                      }else{ 
                                        echo $row['job_sub_cat'];
                                      }

                                    }elseif($row['contractor_type']==3){
                                      if($row['supplier_cat_name']=='Other'){
                                        echo $row['other_work_desc'];
                                      }else{
                                        echo $row['supplier_cat_name'];
                                      }
                                    }else{ }

                                    echo '</a></td><td>'.$row['contractor_name'].'</td><td>'.$row['project_name'].'</td><td>'.$row['reconciled_date'].'</td><td>'.$row['client_name'].'</td><td>'.$row['user_first_name'].' '.$row['user_last_name'].'</td><td>'.number_format($row['price'],2).'</td>';
                                    echo '<td>'.number_format($this->purchase_order->check_balance_po($row['works_id']),2).'</td>';
                                    echo '</tr>';
                                  }
                                ?>



																<?php 
                                  foreach ($reconciled_list_joinery->result_array() as $row_j){
                                    echo '<tr><td><a href="#" data-toggle="modal" data-target="#reconciliated_po_modal" data-backdrop="static" id="'.$row_j['works_id'].'-'.$row_j['work_joinery_id'].'/'.$row_j['project_id'].'" onclick="return_outstanding_po_item(\''.$row_j['works_id'].'-'.$row_j['work_joinery_id'].'/'.$row_j['project_id'].'\');" class="select_po_item">'.$row_j['works_id'].'-'.$row_j['work_joinery_id'].'</a></td><td><a href="'.base_url().'projects/view/'.$row_j['project_id'].'" >'.$row_j['project_id'].'</a></td><td>'.$row_j['work_cpo_date'].'</td><td><a href="'.base_url().'works/update_work_details/'.$row_j['project_id'].'/'.$row_j['works_id'].'">';
                                    echo $row_j['joinery_name'];
                                    echo '</a></td><td>'.$row_j['contractor_name'].'</td><td>'.$row_j['project_name'].'</td><td>'.$row_j['reconciled_date'].'</td><td>'.$row_j['client_name'].'</td><td>'.$row_j['user_first_name'].' '.$row_j['user_last_name'].'</td><td>'.number_format($row_j['price'],2).'</td>';
                                    echo '<td>'.number_format($this->purchase_order->check_balance_po($row_j['works_id'],$row_j['work_joinery_id']),2).'</td>';
                                    echo '</tr>';
                                  } 
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
<div class="modal fade" id="invoice_po_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Purchase Order</h4>
      </div>
      <div class="modal-body">
      	<div class="container-fluid">
      		<div class="row">

      			<div class="col-sm-12 border-bottom">
      				<div class="clearfix col-sm-6">
      					<p>PO Number: <strong class="po_number_mod">0000/0000</strong></p>
      				</div>
      				<div class="clearfix col-sm-6">
      					<p>Description: <strong class="po_desc_mod">Xxxx</strong></p>
      				</div>
      				<div class="clearfix col-sm-6">
      					<p>Total: <strong class="po_total_mod">$00000</strong> ex-gst</p>
      				</div>
      				<div class="clearfix col-sm-6">
      					<p>Balance: <strong class="po_balance_mod">$0.00</strong></p>
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
            <input type="hidden" name="po_gst" id="po_gst" class="po_gst">

      			<div class="col-sm-6">
      				<div class="clearfix m-top-15">
      					<label for="po_amount_value" class="col-sm-3 control-label text-left m-top-10" style="font-weight: normal;">Amount*</label>
      					<div class="col-sm-9">
      						<div class="input-group m-bottom-10">
      							<span class="input-group-addon" id="">$</span>
      							<input type="text" placeholder="Amount" class="form-control number_format" id="po_amount_value" name="po_amount_value" value="" tabindex="2">
                    <span class="input-group-addon" id="">ex-gst</span>
      						</div>
      					</div>
      				</div>
      			</div>

      			<div class="col-sm-6">
      				<div class="clearfix">
      					<label for="po_reference_value" class="col-sm-4 control-label text-left m-top-10" style="font-weight: normal;">Invoice No*</label>
      					<div class="col-sm-8">
      						<input type="text" placeholder="Invoice Number" class="form-control" id="po_reference_value" name="po_reference_value" value="" tabindex="3">
      					</div>
      				</div>
      			</div>

      			<div class="col-sm-6">
              <div class="clearfix">
                <label for="po_amount_value_inc_gst" class="col-sm-3 control-label text-left" style="font-weight: normal;"></label>
                <div class="col-sm-9">
                  <div class="input-group m-bottom-10">
                    <span class="input-group-addon" id="">$</span>
                    <input type="text" placeholder="Amount" class="form-control number_format" id="po_amount_value_inc_gst" name="po_amount_value_inc_gst" value="" tabindex="2">
                    <span class="input-group-addon" id="">inc-gst</span>
                  </div>
                </div>
              </div>
            </div>

      			<div class="col-sm-12">
      				<div class="clearfix  m-top-10">
      					<label for="po_notes_value" class="col-sm-1 control-label text-left m-top-10" style="font-weight: normal;">Notes</label>
      					<div class="col-sm-11">
      						


                  <div class="input-group m-bottom-10">
                    <input type="text" placeholder="Notes" class="form-control" id="po_notes_value" name="po_notes_value" value="" tabindex="4">
                    <input type="text" class="hidden" disabled="disabled">
                    <span class="input-group-addon" id=""><i class="fa fa-exclamation-triangle"></i> Is Reconciled <input class="m-top-10" type="checkbox" name="is_reconciled" id="po_is_reconciled_value"></span>
                  </div>



      					</div>
      				</div>
      			</div>

      			<div class="col-sm-12 m-top-15">
      				<table class="table table-bordered">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Amount</th>
                  <th>Invoice Number</th>
                </tr>
              </thead>
              <tbody class="po_history">                
              </tbody>
            </table>
      			</div>
 
      		</div>
      	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default po_cancel_values" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success po_set_values"><i class="fa fa-floppy-o"></i> Save</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="reconciliated_po_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Purchase Order</h4>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">

            <div class="col-sm-12 border-bottom">
              <div class="clearfix col-sm-6">
                <p>PO Number: <strong class="po_number_mod">0000/0000</strong></p>
              </div>
              <div class="clearfix col-sm-6">
                <p>Description: <strong class="po_desc_mod">Xxxx</strong></p>
              </div>
              <div class="clearfix col-sm-6">
                <p>Total: <strong class="po_total_mod">$00000</strong> ex-gst</p>
              </div>
              <div class="clearfix col-sm-6">
                <p>Balance: <strong class="po_balance_mod">$0.00</strong></p>
              </div>          
            </div>

            

            <div class="col-sm-12 m-top-15">
              <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Amount</th>
                  <th>Invoice Number</th>
                </tr>
              </thead>
              <tbody class="po_history return_outstanding">                
              </tbody>
            </table>
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



<div style="display:none;" class="outstading_pm">
	<select id="outstading_pm" class="form-control  pull-right input-sm m-left-10"  style="width:200px;">
		<option value="">Select Project Manager</option>
		<?php
		foreach ($users->result_array() as $row){
			if($row['user_role_id']==3):
				echo '<option value="'.$row['user_first_name'].' '.$row['user_last_name'].'" >'.$row['user_first_name'].' '.$row['user_last_name'].'</option>';
			endif;
		}
		?>
	</select>
</div>

<!-- Purchase Order Filter modal -->
<div class="modal fade" id="po_date_filter_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Purchase Order Filter</h4>
      </div>
      <div class="modal-body">
        <div class="input-group m-bottom-10">
          <span class="input-group-addon" id="">
            <i class="fa fa-calendar"></i>
          </span>
          <input type="text" data-date-format="dd/mm/yyyy" placeholder="From" class="form-control datepicker" id="po_start_date" name="po_start_date" value="" >
        </div>

        <div class="input-group m-bottom-10">
          <span class="input-group-addon" id="">
            <i class="fa fa-calendar"></i>
          </span>
          <input type="text" data-date-format="dd/mm/yyyy" placeholder="To" class="form-control datepicker" id="po_end_date" name="po_end_date" value="" >
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="filter_po_bydate" data-dismiss="modal"><i class="fa fa-filter"></i> Filter</button>
      </div>
    </div>
  </div>
</div>


<style type="text/css">
	/*.po-area #companyTable_length, .po-area #companyTable_filter{
		display: none;
		visibility: hidden;
	}*/
</style>
<?php $this->load->view('assets/logout-modal'); ?>
<script type="text/javascript">
	
</script>