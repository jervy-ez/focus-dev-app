<?php 
	$this->load->module('projects'); 
	$this->load->module('works'); 
	$variation_id = $this->uri->segment(4);//$this->session->flashdata('variation_id');
?>
<input type="hidden" id="gst" value="<?php echo $this->session->userdata('gst_rate'); ?>">
<input type="hidden" id="var_id" value="<?php echo $variation_id; ?>">
<input type="hidden" id="variation_acceptance_date" value="<?php echo $acceptance_date; ?>"> 
<input type="hidden" id ="base_url" value = "<?php echo base_url(); ?>">
<input type="hidden" id ="proj_post_code" value = "<?php echo $postcode ?>">
<input type="hidden" id = "state" value = "<?php echo $state ?>">
<div class="">
	<div id="frm_add_works"></div>
	<div class="">
		<div class="section col-sm-12 col-md-12 col-lg-12" style = "padding: 0px;">
			<div class="">
				<div class="rows">
					<div class="col-md-9">
						<div class="">
							<div class="box-head pad-bottom-10 clearfix">

								<?php //if($acceptance_date == ""): ?>
						        <a href="<?php echo base_url(); ?>works/work_details/<?php echo $projid.'/'.$variation_id ?>" id="addwork" class="btn btn-primary pull-right m-right-10 m-top-10"><i class="fa fa-cogs"></i> Add Work</a>
								<?php //endif; ?>

								<div class="input-group pull-right search-work-desc">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-search"></i></span>
										<input type="text" class="form-control input-wd" placeholder="Looking for...">										
									</div>
								</div>
								


								<!--<button type = "button" id = "showaddworkmodal" class="btn btn-primary pull-right" data-toggle="modal" data-target="#frmaddworks"><i class = "fa fa-cogs"></i> Add Work</button>-->
								<!--<a href="samplemod/print_pdf"  target="_blank" class="btn btn-primary pull-right"><i class = "fa fa-print"></i> Print</a>-->
								<label><?php echo $screen ?></label><span>(<a href="#" data-placement="right" class="popover-test" title="" data-content="This is where the works of the selected Project are listed." data-original-title="Welcome">?</a>)</span>
								<p><a href="<?php echo base_url().'projects/view/'.$this->uri->segment(3).'?tab=variations'; ?>" id = "back_to_variation"><i class = "fa fa-hand-o-left"></i> Back to Variation List</a></p>
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
											<?php echo $this->works->display_all_variations_query($this->uri->segment(3),$acceptance_date); ?>
										</tbody>
									</table>
								</div>
								<div class="table-footer">
									<table class="table table-condensed table-bordered m-bottom-0">
										<tfoot>
											<tr>
												<th colspan = 2 class = "text-right">Total:</th>
												<th width="10%" class = "text-right">
													<input type="text" id = "var-work-total-price" class="input_text text-right number_format" style = "width: 100%" disabled>
												</th>
												<th width="10%" class = "text-right">
													<input type="text" id = "var-work-total-estimate" class="input_text text-right number_format" style = "width: 100%" disabled>
												</th>
												<th width="10%" class = "text-right">
													<input type="text" id = "var-work-total-quoted" class="input_text text-right number_format" style = "width: 100%" disabled>
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
								<label id = "var_cont_cpono"></label>
								<button type="button" id="btnadd_var_contractor" class ="btn btn-primary pull-right" data-toggle="modal" data-target="#addContractor_Modal_adv_var">Add</button>
							</div>
							<div id = "var_work_contractors" class="box-area pad-5" style = "height: 400px; overflow: auto">
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
<div class="modal fade" id="add_var_Contractor_Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
	    <div class="modal-content">
	        <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		        <h4 class="modal-title">Company Details</h4>
	        </div>
	        <div class="modal-body" style = "height: 180px">
	        	<div class="col-sm-12 m-bottom-10 clearfix <?php if(form_error('officeNumber')){ echo 'has-error has-feedback';} ?>">
					<label for="company_prg" class="col-sm-3 control-label">Date*</label>
					<div class="col-sm-9">														
						<div class="input-group <?php if(form_error('company_prg')){ echo 'has-error has-feedback';} ?>">
							<span class="input-group-addon"><i class="fa fa-calendar  fa-lg"></i></span>
							<input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control datepicker" id="contractor_date_entered_var" name="contractor_date_entered_var" value="<?php echo $this->input->post('work_cpodate_req'); ?>">
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
							<select name="var_work_contructor_name" class="form-control find_contact_person chosen" id="var_work_contructor_name" style="width: 100%;" tabindex="25">																										
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
						<select name="contact_person" class="form-control var_cont_person" id="contact_person" style="width: 100%;"  tabindex="26">		
							<option value=''>Select Contact Person*</option>													
							<?php //$this->company->contact_person_list(); ?>
							<script type="text/javascript">$('select#contact_person').val('<?php echo $this->input->post('contact_person'); ?>');</script>
						</select>
					</div>
				</div>

				<div class="col-sm-12 m-bottom-10 clearfix" id = "var_contractor_notes_div">
					<label for="contact_person" class="col-md-3 col-sm-5 control-label">Notes: </label>
					<div class="col-md-9 col-sm-7 here">
						<input type="text" title = "Notes are limited to 40 Characters" class = "form-control input-sm" name = "var_contractor_notes" id = "var_contractor_notes" maxlength="40">
					</div>
				</div>
	        </div>
	        <div class="modal-footer">
	        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	          	<button type="button" class="btn btn-primary" id = "save_var_contractor"><i class="fa fa-save  fa-lg"></i> Save</button>
	          	<button class="btn btn-warning" id = "cont_saving_var_button"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Saving...</button>
	          	<button type="button" class="btn btn-primary" id = "create_var_cqr"><i class="fa fa-file-pdf-o  fa-lg"></i> Create CQR</button>
	          	<button type="button" class="btn btn-success" id = "update_var_contractor" data-dismiss="modal"><i class="fa fa-check  fa-lg"></i> Update</button>
	          	<button type="button" class="btn btn-danger" id = "delete_var_contractor" data-toggle="modal" data-target="#var_work_contractor_del_conf"><i class="fa fa-trash-o  fa-lg"></i> Delete</button>
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
<div class="modal fade" id="var_work_contractor_del_conf" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
	        	<button type = "button" id = "btn_var_work_con_del_conf_yes" class="btn btn-danger">Yes</button>
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

<div class="modal fade" id="addContractor_Modal_adv_var" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style = "width: 1500px">
	    <div class="modal-content">
	        <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		        <h4 class="modal-title">Company Details</h4>
	        </div>
	        <div class="modal-body" style = "height: 580px">
	        	<div class="col-sm-3" style = "background-color: #f2f2f2; padding: 5px">
	        		<div class="col-sm-12 m-bottom-10 clearfix <?php //if(form_error('officeNumber')){ echo 'has-error has-feedback';} ?>">
						<label for="company_prg" class="col-sm-3 control-label">Date*</label>
						<div class="col-sm-9">														
							<div class="input-group <?php //if(form_error('company_prg')){ echo 'has-error has-feedback';} ?>">
								<span class="input-group-addon"><i class="fa fa-calendar  fa-lg"></i></span>
								<input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control datepicker" id="add_contractor_date_entered_var" name="add_contractor_date_entered_var" value="<?php echo $this->input->post('work_cpodate_req'); ?>">
							</div>
						</div>
					</div>
					<div class="col-sm-12 m-bottom-10 clearfix <?php //if(form_error('officeNumber')){ echo 'has-error has-feedback';} ?>">
						<label for="company_prg" class="col-sm-3 control-label">Search</label>
						<div class="col-sm-9">														
							<div class="input-group <?php //if(form_error('company_prg')){ echo 'has-error has-feedback';} ?>">
								<span class="input-group-addon"><i class="fa fa-search  fa-lg"></i></span>
								<input type="text" class = "form-control input-sm" name = "search_contractor_var" id = "search_contractor_var" placeholder = "Search Company Name">
							</div>
						</div>
					</div>
					<div class = "col-sm-12 pad_5 m-bottom-10 clearfix"><button type = "button" class = "btn btn-success btn-sm pull-right" id = "btn_search_contractor_var">Search</button></div>
					
					<div class="col-sm-12 m-bottom-10 clearfix <?php //if(form_error('officeNumber')){ echo 'has-error has-feedback';} ?>">
						<label for="company_prg" class="col-sm-3 control-label">Activity</label>
						<div class="col-sm-9">														
							<div class="input-group <?php //if(form_error('company_prg')){ echo 'has-error has-feedback';} ?>">
								<span class="input-group-addon"><i class="fa fa-filter  fa-lg"></i></span>
								<select name="comp_activity_var" id="comp_activity_var" class = "form-control input-sm"></select>
							</div>
						</div>
					</div>
					<div class="col-sm-12 m-bottom-10 clearfix <?php //if(form_error('officeNumber')){ echo 'has-error has-feedback';} ?>">
						<label for="company_prg" class="col-sm-3 control-label">State</label>
						<div class="col-sm-9">														
							<div class="input-group <?php //if(form_error('company_prg')){ echo 'has-error has-feedback';} ?>">
								<span class="input-group-addon"><i class="fa fa-filter  fa-lg"></i></span>
								<select class="form-control" id = "works_state_var">
									<?php echo $this->projects->set_jurisdiction(); ?>
								</select>
							</div>
						</div>
					</div>
					<div class="col-sm-12 m-bottom-10 clearfix <?php //if(form_error('officeNumber')){ echo 'has-error has-feedback';} ?>">
						<label for="company_prg" class="col-sm-3 control-label">Suburb</label>
						<div class="col-sm-9">														
							<div class="input-group <?php //if(form_error('company_prg')){ echo 'has-error has-feedback';} ?>">
								<span class="input-group-addon"><i class="fa fa-filter  fa-lg"></i></span>
								<select class="form-control" id = "works_suburb_var">	
								</select>
							</div>
						</div>
					</div>
					<div class="col-sm-12 m-bottom-10 clearfix <?php //if(form_error('officeNumber')){ echo 'has-error has-feedback';} ?>">
						<label for="company_prg" class="col-sm-3 control-label">Postcode</label>
						<div class="col-sm-9">														
							<div class="input-group <?php //if(form_error('company_prg')){ echo 'has-error has-feedback';} ?>">
								<span class="input-group-addon"><i class="fa fa-filter  fa-lg"></i></span>
								<input type="text" class = "form-control input-sm" name = "post_code_var" id = "post_code_var">
							</div>
						</div>
					</div>

					<div class="col-sm-12"><button type = "button" class = "btn btn-success btn-sm pull-right" id = "filter_con_sup_var">Filter</button></div>
	        	</div>
	        	<div class="col-sm-9">
	        		<div class="col-sm-12">
	        			Filtered By: 
	        			<label for="" id = "lbl_filteredby_var"></label>
	        		</div>
	        		<div class="col-sm-12 pull-center" id = "loading_img_var">
	        			<center><h3>Loading Please Wait</h3></center>
				        <center><h2><i class="fa fa-circle-o-notch fa-spin fa-5x"></i></h2></center>
				        <p>&nbsp;</p>
	        		</div>
	        		<div class="col-sm-12" style = "height: 500px; overflow: auto; padding: 5px" id = "tbl_contractors_list_var">
	        			
	        		</div>
	        	</div>
	        </div>
	       	<div class="modal-footer">
	       		<button type="button" class="btn btn-success" id = "add_selected_con_sup_var">Add Selected Contractors/Suppliers</button>
	       		<button class="btn btn-warning" id = "fltr_cont_saving_button_var"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Saving...</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	       	</div>
	    </div>
	</div>
</div>

<script>
	var state = $('#state').val();
	var baseurl = $("#base_url").val(); 
	var proj_post_code = $("#proj_post_code").val();

	$("#btnadd_var_contractor").click(function(){
		$("#add_selected_con_sup_var").show();
        $("#fltr_cont_saving_button_var").hide();
		$("#tbl_contractors_list_var").hide();
		$("#loading_img_var").show();
	    $("#search_contractor_var").val("");
	    $("#works_state_var").val("");
	    var formattedDate = new Date();
	    var d = formattedDate.getDate();
	    var m =  formattedDate.getMonth();
	    m += 1;  // JavaScript months are 0-11
	    var y = formattedDate.getFullYear();
	   
	    $("#add_contractor_date_entered_var").val(d+"/"+m+"/"+y);
	 
	    // $("#save_contractor").show();

	    $.post(baseurl+"works/fetch_comp_suburb",
	    {},
	    function(result){

		    $('#works_suburb_var').empty()
		    var opts = $.parseJSON(result);
		    $('#works_suburb_var').append('<option value="">Select Suburb</option>');
            $.each(opts, function(i, d) {
                $('#works_suburb_var').append('<option value="' + d.suburb + '">' + d.suburb + '</option>');
            });
	   	});

	   	$.post(baseurl+"works/fetch_work_type",
	    {
	        work_id: work_id,
	    },
	    function(result){
	    	var result_arr = result.split( '|' );
	    	var comp_type = result_arr[0];
	    	var activity_id = result_arr[1];
	    	var other_activity_id = result_arr[2];
	    	//$('#sel_work_comp_type').val(comp_type);
	    	$.post(baseurl+"works/fetch_comp_activity",
		    {
		        comp_type: comp_type,
		    },
		    function(result){
		    	var sel_activity = "";
		    	$('#comp_activity_var').empty()
		    	 var opts = $.parseJSON(result);
                $.each(opts, function(i, d) {
                	if(activity_id == d.id ){
                		sel_activity = d.name;
                		$('#comp_activity_var').append('<option value="' + d.id + '" selected = "Selected">' + d.name + '</option>');
                	}else{
                		$('#comp_activity_var').append('<option value="' + d.id + '">' + d.name + '</option>');
                	}
                    
                });

		       	$.post(baseurl+"works/add_contractors_list",
			    {
			    	state: state,
			        proj_post_code: proj_post_code,
			        comp_type: comp_type,
			        activity: activity_id
			    },
			    function(result){
			    	var filter_type = "All";
			    	switch(comp_type){
			    		case '0':
			    			filter_type = "All";
			    			break;
			    		case '2':
			    			filter_type = "Contractors";
			    			break;
			    		case '3':
			    			filter_type = "Suppliers";
			    			break;
			    	}
			    	var label = "10 Nearest "+filter_type+", Activity: "+sel_activity;
			    	$("#lbl_filteredby_var").html(label);
			        $("#tbl_contractors_list_var").html(result);
			        $("#tbl_contractors_list_var").show();
					$("#loading_img_var").hide();
			    });
		    });
	        
	    });	    
	});

	$("#works_state_var").change(function(){
    	var works_state = $("#works_state_var").val();
    	var works_state_arr = works_state.split("|");
    	var state_name = works_state_arr[1];
    	
    	$.post(baseurl+"works/fetch_comp_suburb",
	    {
	    	state_name : state_name
	    },
	    function(result){
		    $('#works_suburb_var').empty()
		    var opts = $.parseJSON(result);
		    $('#works_suburb_var').append('<option value="">Select Suburb</option>');
            $.each(opts, function(i, d) {
                $('#works_suburb_var').append('<option value="' + d.suburb + '">' + d.suburb + '</option>');
            });
	   	});
    });

     $("#btn_search_contractor_var").click(function(){
    	$("#tbl_contractors_list_var").hide();
		$("#loading_img_var").show();
    	var text_search = $("#search_contractor_var").val();
    	$.post(baseurl+"works/search_add_contractors_list",
		{
		    text_search: text_search
		},
		function(result){
		   	// alert(result);
		  	
		    var label = "All Contractors / Suppliers Search Regardless of Activity and Location";
		    $("#lbl_filteredby_var").html(label);
		    $("#tbl_contractors_list_var").html(result);
		    $("#tbl_contractors_list_var").show();
			$("#loading_img_var").hide();
		});
    });

    $("#filter_con_sup_var").click(function(){
    	$("#tbl_contractors_list_var").hide();
		$("#loading_img_var").show();
    	var activity_id = $("#comp_activity_var").val();
    	var works_state = $("#works_state_var").val();
    	var suburb = $("#works_suburb_var").val();
    	var post_code = $("#post_code_var").val();
    	var postcodelbl = "postcode: "+post_code;
    	var suburblbl = "Suburb: "+suburb; 
    	var state_id = 0;
    	var state_lbl = ""; 
    	
    
    	if(works_state !== ""){
    		var works_state_arr = works_state.split("|");
	    	state_id= works_state_arr[3];
	    	state_lbl = "State: "+works_state_arr[1];
    	}

    	if(suburb == ""){
    		suburb = 0;
    		suburblbl = "";
    	}

    	if(post_code == ""){
    		post_code = 0;
			postcodelbl = "";
    	}
    	
    	$.post(baseurl+"works/filter_con_sup_list",
		{
			state: state_id,
			suburb: suburb,
			postcode: post_code,
		    activity_id: activity_id
		},
		function(result){
			var label = " "+state_lbl+" "+suburblbl+" "+postcodelbl;
			$("#lbl_filteredby_var").html(label);
			$("#tbl_contractors_list_var").html(result);
			$("#tbl_contractors_list_var").show();
			$("#loading_img_var").hide();
		});
    });

	$("#add_selected_con_sup_var").click(function(){

		$("#add_selected_con_sup_var").hide();
        $("#fltr_cont_saving_button_var").show();
		var checkboxValues = [];
		var date_entered = $("#add_contractor_date_entered_var").val();
  		$('input[name=chk_work_contractors]:checked').map(function() {

  			var comp_id = $(this).val();
    		// checkboxValues.push($(this).val());
    		var id_name = comp_id+"_cont_person";
    		var contact_person_id = $("#"+id_name).val();
    	

			$.post(baseurl+"works/insert_contractor", 
          	{ 
            	proj_id: proj_id,
            	work_id: work_id,
            	date_added: date_entered,
            	comp_id: comp_id,
            	contact_person_id: contact_person_id
          	}, 
          	function(result){
            	setTimeout(function(){
              		$("#add_selected_con_sup_var").show();
              		$("#fltr_cont_saving_button_var").hide();
              		// $("#var_work_contractors").html(result);
              		var var_acceptance_date = $("#variation_acceptance_date").val();
              		$.post(baseurl+"works/display_var_work_contractor", 
			      	{ 
				        var_acceptance_date: var_acceptance_date,
				        proj_id: proj_id,
				        work_id: work_id
			      	}, 
			      	function(result){
				        $("#var_work_contractors").html(result);
				        $("#btn_select_subcontractor").show();
				        $("#var_cont_cpono").html(work_id);
				        
			      	});
            	}, 5000);  // on 5 second
          	});

  		});

	})
</script>