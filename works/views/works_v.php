<?php $this->load->module('projects'); ?>
<input type="hidden" id="gst" value="<?php echo $this->session->userdata('gst_rate'); ?>">
<div class="">
	<div id="frm_add_works"></div>
	<div class="">
		<div class="section col-sm-12 col-md-12 col-lg-12" style = "padding: 0px;">
			<div class="">
				<div class="rows">
					<div class="col-md-9">
						<div class="">
							<div class="box-head pad-bottom-10 clearfix">


						        <a href="<?php echo base_url(); ?>works/work_details/<?php echo $projid?>" id="addwork" class="btn btn-primary pull-right m-right-10 m-top-10"><i class="fa fa-cogs"></i> Add Work</a>


								<div class="input-group pull-right search-work-desc">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-search"></i></span>
										<input type="text" class="form-control input-wd" placeholder="Looking for...">										
									</div>
								</div>
								


								<!--<button type = "button" id = "showaddworkmodal" class="btn btn-primary pull-right" data-toggle="modal" data-target="#frmaddworks"><i class = "fa fa-cogs"></i> Add Work</button>-->
								<!--<a href="samplemod/print_pdf"  target="_blank" class="btn btn-primary pull-right"><i class = "fa fa-print"></i> Print</a>-->
								<label><?php echo $screen ?></label><span>(<a href="#" data-placement="right" class="popover-test" title="" data-content="This is where the works of the selected Project are listed." data-original-title="Welcome">?</a>)</span>
								<p>This is where the works of the selected Project are listed. </p>
							</div>

							<div id="tbl_works" class="pad-right-10">
								<div class="table-header">
									<table class="table table-condensed table-bordered m-bottom-0">
										<thead>
											<tr>
												<th width="20%">Work Description</th>
												<th width="50%">Company</th>
												<th width="10%">Price</th>
												<th width="10%">Estimated</th>
												<th width="10%">Quoted</th>
											</tr>
										</thead>
									</table>
								</div>
								<div class="table-warp">
									<table id="table-wd" class="table table-striped table-bordered">									
										<tbody>
											<?php echo $this->projects->display_all_works_query($this->uri->segment(3)); ?>
										</tbody>
									</table>
								</div>
								<div class="table-footer">
									<table class="table table-condensed table-bordered m-bottom-0">
										<tfoot>
											<tr>
												<th colspan = 2 class = "text-right">Total:</th>
												<th width="10%" class = "text-right">
													<input type="text" id = "work-total-price" class="input_text text-right number_format" style = "width: 100%" disabled>
												</th>
												<th width="10%" class = "text-right">
													<input type="text" id = "work-total-estimate" class="input_text text-right number_format" style = "width: 100%" disabled>
												</th>
												<th width="10%" class = "text-right">
													<input type="text" id = "work-total-quoted" class="input_text text-right number_format" style = "width: 100%" disabled>
												</th>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-3" id = "frmcontractor">
						<div class="box">
							<div class="box-head pad-5">
								<label><i class="fa fa-users fa-lg"></i> Companies of CPO No.: </label>
								<label id = "cont_cpono"></label>
								<button type="button" id="btnaddcontractor" class ="btn btn-primary pull-right" data-toggle="modal" data-target="#addContractor_Modal">Add</button>
							</div>
							<div id = "work_contractors" class="box-area pad-5" style = "height: 400px; overflow: auto">
							</div>
							<!--<div class="col-sm-12"><button type = "button" id = "btn_select_subcontractor" name = "btn_select_subcontractor" class = "btn btn-success col-sm-12"><i class="fa fa-hand-o-up"></i> Select Sub-Contractor</button></div>-->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- MODAL LIST -->
<div class="modal fade" id="addContractor_Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
	    <div class="modal-content">
	        <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		        <h4 class="modal-title">Company Details</h4>
	        </div>
	        <div class="modal-body" style = "height: 150px">
	        	<div class="col-sm-12 m-bottom-10 clearfix <?php if(form_error('officeNumber')){ echo 'has-error has-feedback';} ?>">
					<label for="company_prg" class="col-sm-3 control-label">Date*</label>
					<div class="col-sm-9">														
						<div class="input-group <?php if(form_error('company_prg')){ echo 'has-error has-feedback';} ?>">
							<span class="input-group-addon"><i class="fa fa-calendar  fa-lg"></i></span>
							<input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control datepicker" id="contractor_date_entered" name="contractor_date_entered" value="<?php echo $this->input->post('work_cpodate_req'); ?>">
						</div>
					</div>
				</div>

	          	<div class="col-sm-12 m-bottom-10 clearfix <?php if(form_error('officeNumber')){ echo 'has-error has-feedback';} ?>">
					<label for="company_prg" class="col-sm-3 control-label">Company*</label>
					<div class="col-sm-9">														
						<div class="input-group <?php if(form_error('company_prg')){ echo 'has-error has-feedback';} ?>">
							<span class="input-group-addon"><i class="fa fa-briefcase  fa-lg"></i></span>
							<!-- <select id="work_contructor_name" class="form-control chosen"  tabindex="5" name="work_contructor_name">
								<optgroup label="Contractors">														
									<?php //$this->projects->list_job_subcategory(); ?>
								</optgroup>
								<optgroup label="Suppliers">														
									<?php // $this->projects->list_supplier_category(); ?>
								</optgroup>
							</select>-->
							<select name="work_contructor_name" class="form-control find_contact_person chosen" id="work_contructor_name" style="width: 100%;" tabindex="25">																										
								<?php //$this->company->company_list('dropdown'); ?>
								<option value=''>Select Company Name*</option>													
								<?php $this->company->works_company_by_type(2); ?>														
								<?php $this->company->works_company_by_type(3); ?>
							</select>
						</div>
					</div>
				</div>

				<div class="col-sm-12 m-bottom-10 clearfix <?php if(form_error('contact_person')){ echo 'has-error has-feedback';} ?>">
					<label for="contact_person" class="col-md-3 col-sm-5 control-label">Attention*</label>
					<div class="col-md-9 col-sm-7 here">
						<select name="contact_person" class="form-control" id="contact_person" style="width: 100%;"  tabindex="26">		
							<option value=''>Select Contact Person*</option>													
							<?php //$this->company->contact_person_list(); ?>
							<script type="text/javascript">$('select#contact_person').val('<?php echo $this->input->post('contact_person'); ?>');</script>
						</select>
					</div>
				</div>
	        </div>
	        <div class="modal-footer">
	        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	          	<button type="button" class="btn btn-primary" id = "save_contractor"><i class="fa fa-save  fa-lg"></i> Save</button>
	          	<button type="button" class="btn btn-primary" id = "create_cqr"><i class="fa fa-file-pdf-o  fa-lg"></i> Create CQR</button>
	          	<button type="button" class="btn btn-success" id = "update_contractor" data-dismiss="modal"><i class="fa fa-check  fa-lg"></i> Update</button>
	          	<?php 
	          		if($job_date == ""){
	          	?>
	          		<button type="button" class="btn btn-danger" id = "delete_contractor" data-toggle="modal" data-target="#work_contractor_del_conf"><i class="fa fa-trash-o  fa-lg"></i> Delete</button>
	          	<?php
	          		}
	          	 ?>
	          	
	        </div>
	    </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="work_attachment_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
	    <div class="modal-content">
	        <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		        <h4 class="modal-title">Work Attachment</h4>
	        </div>
	        <div class="modal-body" style = "height: 300px">
	        	<div class = "col-sm-2">Attach File: </div>
	        	<div class = "col-sm-5"><input type="file" class = "input-sm form-inline" id = "work_attach_file" style = "border: 1px solid #888"></div>
	        	<div class = "col-sm-5"><button id = "btn_upload" class = "form-inline btn btn-success pull-right"><i class="fa fa-upload  fa-lg"></i> Upload</button></div>
	        	<div class="clearfix" style = "height: 40px"></div>
	        	<div class="col-sm-12" style = "border: 1px solid #888; overflow:auto; height: 250px ">
		        	<table id="worksTable" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>File Name</th>
								<th>Download</th>
							</tr>
						</thead>
						<tbody>
							<?php //echo $this->projects->display_all_works_query($this->uri->segment(3)); ?>
						</tbody>
					</table>
		        </div>
	        </div>
	        <div class="modal-footer">
	          	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        </div>
	    </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Delete Contractor Confirmation -->
<div class="modal fade" id="work_contractor_del_conf" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
	    <div class="modal-content">
	        <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		        <h4 class="modal-title">Confirmation</h4>
	        </div>
	        <div class="modal-body">
	        	<p><strong>Are you sure you want to delete selected Contractor?</strong></p>
	        </div>
	        <div class="modal-footer">
	        	<button type = "button" id = "btn_work_con_del_conf_yes" class="btn btn-danger">Yes</button>
	          	<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
	        </div>
	    </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Update Table -->
<div class="modal fade" id="work_update_conf" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
	    <div class="modal-content">
	        <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		        <h4 class="modal-title">Confirmation</h4>
	        </div>
	        <div class="modal-body">
	        	<p><strong>Are you sure you want Update Price?</strong></p>
	        </div>
	        <div class="modal-footer">
	        	<button type = "button" id = "update_price_yes" class="btn btn-danger" data-dismiss="modal">Yes</button>
	          	<button type="button" class="btn btn-default" id = "update_price_no" data-dismiss="modal">No</button>
	        </div>
	    </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="work_update_estimate_conf" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
	    <div class="modal-content">
	        <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		        <h4 class="modal-title">Confirmation</h4>
	        </div>
	        <div class="modal-body">
	        	<p><strong>Are you sure you want Update Estimate?</strong></p>
	        </div>
	        <div class="modal-footer">
	        	<button type = "button" id = "update_estimate_yes" class="btn btn-danger" data-dismiss="modal">Yes</button>
	          	<button type="button" class="btn btn-default" id = "update_estimate_no" data-dismiss="modal">No</button>
	        </div>
	    </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Update Contractor Table -->
<div class="modal fade" id="work_cont_update_conf" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
	    <div class="modal-content">
	        <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		        <h4 class="modal-title">Confirmation</h4>
	        </div>
	        <div class="modal-body">
	        	<p><strong>Are you sure you want Update Ex-GST?</strong></p>
	        </div>
	        <div class="modal-footer">
	        	<button type = "button" id = "update_exgst_yes" class="btn btn-danger" data-dismiss="modal">Yes</button>
	          	<button type="button" class="btn btn-default" id = "update_exgst_no" data-dismiss="modal">No</button>
	        </div>
	    </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="work_cont_inc_update_conf" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
	    <div class="modal-content">
	        <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		        <h4 class="modal-title">Confirmation</h4>
	        </div>
	        <div class="modal-body">
	        	<p><strong>Are you sure you want Update Inc-GST?</strong></p>
	        </div>
	        <div class="modal-footer">
	        	<button type = "button" id = "update_incgst_yes" class="btn btn-danger" data-dismiss="modal">Yes</button>
	          	<button type="button" class="btn btn-default" id = "update_incgst_no" data-dismiss="modal">No</button>
	        </div>
	    </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="work_joinery_update_estimate_conf" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
	    <div class="modal-content">
	        <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		        <h4 class="modal-title">Confirmation</h4>
	        </div>
	        <div class="modal-body">
	        	<p><strong>Are you sure you want Update Joinery Estimate?</strong></p>
	        </div>
	        <div class="modal-footer">
	        	<button type = "button" id = "update_joinery_estimate_yes" class="btn btn-danger" data-dismiss="modal">Yes</button>
	          	<button type="button" class="btn btn-default" id = "update_joinery_estimate_no" data-dismiss="modal">No</button>
	        </div>
	    </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="set_work_joinery_contractor_conf" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
	    <div class="modal-content">
	        <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		        <h4 class="modal-title">Confirmation</h4>
	        </div>
	        <div class="modal-body">
	        	<p><strong>Do you want to set the selected contractor for all the joinery sub-items?</strong></p>
	        </div>
	        <div class="modal-footer">
	        	<button type = "button" id = "set_work_joinery_contractor_yes" class="btn btn-danger" data-dismiss="modal">Yes</button>
	          	<button type="button" class="btn btn-default" id = "set_work_joinery_contractor_no" data-dismiss="modal">No</button>
	        </div>
	    </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="update_work_joinery_unit_price_conf" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
	    <div class="modal-content">
	        <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		        <h4 class="modal-title">Confirmation</h4>
	        </div>
	        <div class="modal-body">
	        	<p><strong>Do you want to save changes from the selected joinerys' unit price?</strong></p>
	        </div>
	        <div class="modal-footer">
	        	<button type = "button" id = "update_work_joinery_unitprice_yes" class="btn btn-danger" data-dismiss="modal">Yes</button>
	          	<button type="button" class="btn btn-default" id = "update_work_joinery_unitprice_no" data-dismiss="modal">No</button>
	        </div>
	    </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="update_work_joinery_unit_estimate_conf" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
	    <div class="modal-content">
	        <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		        <h4 class="modal-title">Confirmation</h4>
	        </div>
	        <div class="modal-body">
	        	<p><strong>Do you want to save changes from the selected joinerys' Unit Estimate?</strong></p>
	        </div>
	        <div class="modal-footer">
	        	<button type = "button" id = "update_work_joinery_unitestimate_yes" class="btn btn-danger" data-dismiss="modal">Yes</button>
	          	<button type="button" class="btn btn-default" id = "update_work_joinery_unitestimate_no" data-dismiss="modal">No</button>
	        </div>
	    </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="update_work_joinery_qty_conf" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
	    <div class="modal-content">
	        <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		        <h4 class="modal-title">Confirmation</h4>
	        </div>
	        <div class="modal-body">
	        	<p><strong>Do you want to save changes from the selected joinerys' QTY?</strong></p>
	        </div>
	        <div class="modal-footer">
	        	<button type = "button" id = "update_work_joinery_qty_yes" class="btn btn-danger" data-dismiss="modal">Yes</button>
	          	<button type="button" class="btn btn-default" id = "update_work_joinery_qty_no" data-dismiss="modal">No</button>
	        </div>
	    </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->