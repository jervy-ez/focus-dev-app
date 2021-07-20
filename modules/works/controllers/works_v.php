<?php $this->load->module('projects'); ?>
<script src="<?php echo base_url(); ?>js/vue.js"></script>
<script src="<?php echo base_url(); ?>js/vue-select.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>css/vue-select.css">
<script src="<?php echo base_url(); ?>js/moment.min.js"></script>
<script src="<?php echo base_url(); ?>js/jmespath.js"></script>
<script src="<?php echo base_url(); ?>js/axios.min.js"></script>

<input type="hidden" id="gst" value="<?php echo $this->session->userdata('gst_rate'); ?>">
<input type="hidden" id ="base_url" value = "<?php echo base_url(); ?>">
<input type="hidden" id ="proj_post_code" value = "<?php echo $postcode ?>">
<input type="hidden" id = "state" value = "<?php echo $state ?>">
<input type="hidden" id="hidden_work_contractor_id">
<input type="hidden" id="hidden_work_company_id">
<div id="workApp">

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
										<input type="text" class="form-control input-wd" placeholder="Looking for..." autocomplete="new-password">										
									</div>
								</div>
								


								<!--<button type = "button" id = "showaddworkmodal" class="btn btn-primary pull-right" data-toggle="modal" data-target="#frmaddworks"><i class = "fa fa-cogs"></i> Add Work</button>-->
								<!--<a href="samplemod/print_pdf"  target="_blank" class="btn btn-primary pull-right"><i class = "fa fa-print"></i> Print</a>-->
								<label><?php echo $screen ?></label><span>(<a href="#" data-placement="right" class="popover-test" title="" data-content="This is where the works of the selected Project are listed." data-original-title="Welcome">?</a>)</span>
								<!-- <p>This is where the works of the selected Project are listed. </p> -->
								<p>Description Color Code: <span style = "color: #000">Default</span> / <span style = "color: #0099cc">CQR Sent</span> / <span style = "color: #6dc066">CPO Sent</span> / <span style = "color: #ff3b3f">Is Reconciled</span></p>
								<p>Company Color Code: <span style = "color: Blue">Has insurance</span> / <span style = "color: Red">Incomplete Insurance</span></p>
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
								<div class="dropdown">
									<button class="btn btn-primary dropdown-toggle pull-right" id="btnaddcontractor" type="button" data-toggle="dropdown">Add
									  	<span class="caret"></span>
									</button>
									<ul class="dropdown-menu">
								    	<li><a href="#" data-toggle="modal" data-target="#frm_pending_cont_sup" v-on:click = "show_pending_contractor">Pending Contractors/Suppliers</a></li>
								    	<li><a href="#" data-toggle="modal" data-target="#addContractor_Modal_adv">Contractors/Suppliers</a></li>
								  	</ul>
								</div>
								<!-- <button type="button" id="btnaddcontractor" class ="btn btn-primary pull-right" data-toggle="modal" data-target="#addContractor_Modal_adv">Add</button> -->
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
<div class="modal fade" id="frm_pending_cont_sup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
	    <div class="modal-content">
	        <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		        <h4 class="modal-title">Pending Contractors</h4>
	        </div>
	        <div class="modal-body row">
	        	<div class="col-sm-12 pad-5"><input type="text" class="form-control input-sm" placeholder = "Search..."></div>
	        	<div class="col-sm-12 pad-5" style = "height: 200px; overflow: auto">
	        		<table class="table table-hover text-nowrap">
	                    <thead class="thead-dark">
	                        <tr>
	                          <th></th>
	                          <th>Company Name</th>
	                          <th>Contact Person</th>
	                        </tr>
	                    </thead>
	                      <tbody>
	                        <tr v-for = "company in company" :title = "'('+ company.contact_number + ') ' + company.email">
	                          <td><input type="checkbox" :value="company.company_details_temp_id" v-model="temp_company_id"></td>
	                          <td>{{ company.company_name }}</td>
	                          <td>{{ company.contact_person }}</td>
	                        </tr>
	                    </tbody>
	                </table>
	        	</div>
	        </div>
	        <div class="modal-footer">
	          	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	          	<button type="button" class="btn btn-success" data-dismiss="modal" v-on:click="add_temp_comp">Add</button>
	        </div>
	    </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="frm_pending_cont_sup_update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
	    <div class="modal-content">
	        <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		        <h4 class="modal-title">Pending Contractors</h4>
	        </div>
	        <div class="modal-body row">
	        	<div class="col-sm-12 pad-5">
	        		<v-select v-model = "temp_company_id_update" :options="options"></v-select>
	        	</div>
	        	
	        </div>
	        <div class="modal-footer">
	          	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	          	<button type="button" class="btn btn-danger pull-left" data-dismiss="modal" v-on:click="remove_temp_comp">Remove</button>
	          	<button type="button" class="btn btn-success" data-dismiss="modal" v-on:click="update_temp_comp">Update</button>
	        </div>
	    </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="addContractor_Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
							<script type="text/javascript">//$('select#contact_person').val('<?php echo $this->input->post('contact_person'); ?>');</script>
						</select>
					</div>
				</div>
				
				<div class="col-sm-12 m-bottom-10 clearfix" id = "contractor_notes_div">
					<label for="contact_person" class="col-md-3 col-sm-5 control-label">Notes: </label>
					<div class="col-md-9 col-sm-7 here">
						<input type="text" title = "Notes are limited to 40 Characters" class = "form-control input-sm" name = "contractor_notes" id = "contractor_notes" maxlength="40">
					</div>
				</div>

	        </div>
	        <div class="modal-footer">
	        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	          	<button type="button" class="btn btn-primary" id = "save_contractor"><i class="fa fa-save  fa-lg"></i> Save</button>
	          	<button class="btn btn-warning" id = "cont_saving_button"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Saving...</button>
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

<div class="modal fade" id="addContractor_Modal_adv" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
								<input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control datepicker" id="add_contractor_date_entered" name="add_contractor_date_entered" value="<?php echo $this->input->post('work_cpodate_req'); ?>">
							</div>
						</div>
					</div>
					<div class="col-sm-12 m-bottom-10 clearfix <?php //if(form_error('officeNumber')){ echo 'has-error has-feedback';} ?>">
						<label for="company_prg" class="col-sm-3 control-label">Search</label>
						<div class="col-sm-9">														
							<div class="input-group <?php //if(form_error('company_prg')){ echo 'has-error has-feedback';} ?>">
								<span class="input-group-addon"><i class="fa fa-search  fa-lg"></i></span>
								<input type="text" class = "form-control input-sm" name = "search_contractor" id = "search_contractor" placeholder = "Search Company Name">
							</div>
						</div>
					</div>
					<div class = "col-sm-12 pad_5 m-bottom-10 clearfix"><button type = "button" class = "btn btn-success btn-sm pull-right" id = "btn_search_contractor">Search</button></div>
					
					<div class="col-sm-12 m-bottom-10 clearfix <?php //if(form_error('officeNumber')){ echo 'has-error has-feedback';} ?>">
						<label for="company_prg" class="col-sm-3 control-label">Activity</label>
						<div class="col-sm-9">														
							<div class="input-group <?php //if(form_error('company_prg')){ echo 'has-error has-feedback';} ?>">
								<span class="input-group-addon"><i class="fa fa-filter  fa-lg"></i></span>
								<select name="comp_activity" id="comp_activity" class = "form-control input-sm"></select>
							</div>
						</div>
					</div>
					<div class="col-sm-12 m-bottom-10 clearfix <?php //if(form_error('officeNumber')){ echo 'has-error has-feedback';} ?>">
						<label for="company_prg" class="col-sm-3 control-label">State</label>
						<div class="col-sm-9">														
							<div class="input-group <?php //if(form_error('company_prg')){ echo 'has-error has-feedback';} ?>">
								<span class="input-group-addon"><i class="fa fa-filter  fa-lg"></i></span>
								<select class="form-control" id = "works_state">
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
								<select class="form-control" id = "works_suburb">
									
								</select>
							</div>
						</div>
					</div>
					<div class="col-sm-12 m-bottom-10 clearfix <?php //if(form_error('officeNumber')){ echo 'has-error has-feedback';} ?>">
						<label for="company_prg" class="col-sm-3 control-label">Postcode</label>
						<div class="col-sm-9">														
							<div class="input-group <?php //if(form_error('company_prg')){ echo 'has-error has-feedback';} ?>">
								<span class="input-group-addon"><i class="fa fa-filter  fa-lg"></i></span>
								<input type="text" class = "form-control input-sm" name = "post_code" id = "post_code">
							</div>
						</div>
					</div>

					<div class="col-sm-12"><button type = "button" class = "btn btn-success btn-sm pull-right" id = "filter_con_sup">Filter</button></div>
	        	</div>
	        	<div class="col-sm-9">
	        		<div class="col-sm-12">
	        			Filtered By: 
	        			<label for="" id = "lbl_filteredby"></label>
	        		</div>
	        		<div class="col-sm-12 pull-center" id = "loading_img">
	        			<center><h3>Loading Please Wait</h3></center>
				        <center><h2><i class="fa fa-circle-o-notch fa-spin fa-5x"></i></h2></center>
				        <p>&nbsp;</p>
	        		</div>
	        		<div class="col-sm-12" style = "height: 500px; overflow: auto; padding: 5px" id = "tbl_contractors_list">
	        			
	        		</div>
	        	</div>
	        </div>
	       	<div class="modal-footer">
	       		<button type="button" class="btn btn-success" id = "add_selected_con_sup">Add Selected Contractors/Suppliers</button>
	       		<button class="btn btn-warning" id = "fltr_cont_saving_button"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Saving...</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	       	</div>
	    </div>
	</div>
</div>
</div>

<script>
	window.select_contractor = function(a,b){
		$("#hidden_work_company_id").val(b);
		$("#hidden_work_contractor_id").val(a);
		this.app.select_temp_company();
	}
	Vue.component('v-select', VueSelect.VueSelect);
	var app = new Vue({
	  	el: '#workApp',
	  	data: {
	  		works_id: 0,
		    company: [],
		    temp_company_id: [],
		  	options: [],
		    temp_company_id_update: '',
	  	},
		mounted: function(){
			this.load_contractor_supplier();
		},
  		filters: {
		    getDayname: function(date){
		      return moment(date).format('ddd');
		    },
		    format_date: function(date){
		      return moment(date).format('ll');
		    },
		    ausdate: function(date) {
		      if(date == '0000-00-00'){
		        return '';
		      }else{
		        return moment(date).format('DD/MM/YYYY');
		      }
		      
		    },
		    getTime: function(date){
		      var temp_date = '2020-01-01 '+date;
		      return moment(temp_date).format('h:mm a');
		    },
  		},
  		methods: {
  			select_temp_company: function(){
  				//this.temp_company_id = $("#hidden_work_company_id").val();
  				var temp_company_id_update = $("#hidden_work_company_id").val();
  				for (var key in this.company) {
  					if(this.company[key].company_details_temp_id == temp_company_id_update){
  						this.temp_company_id_update = this.company[key].company_name;
  					}
  				}
  			},
		    load_contractor_supplier: function(){
		    	axios.post("<?php echo base_url() ?>company/fetch_temporary_cont_sup", 
		      	{
		      	}).then(response => {	
		      		this.options = [];
		          	this.company = response.data;    
		          	for (var key in this.company) {
		          		this.options.push({'value': this.company[key].company_details_temp_id, 'label': this.company[key].company_name });
			        }     
	  				     
		      	}).catch(error => {
		        	console.log(error.response)
		      	});
		    },
		    show_pending_contractor: function(){
		    	this.temp_company_id = [];
		    },
		    add_temp_comp: function(){
		    	this.works_id = $("#cont_cpono").html();
		    	axios.post("<?php echo base_url() ?>works/insert_work_pending_company", 
		      	{
		      		'works_id': this.works_id,
					'company_id': this.temp_company_id
		      	}).then(response => {	
		      		window.selwork_badge(this.works_id);
		      	}).catch(error => {
		        	console.log(error.response)
		      	});
		    },
		    update_temp_comp: function(){
		    	var work_contractor_id = $("#hidden_work_contractor_id").val();
		    	this.works_id = $("#cont_cpono").html();
		    	axios.post("<?php echo base_url() ?>works/update_temporary_cont_sup", 
		      	{
		      		'work_contractor_id': work_contractor_id,
		      		'temp_comp_id': this.temp_company_id_update.value
		      	}).then(response => {	
		          	window.selwork(this.works_id);
		      	}).catch(error => {
		        	console.log(error.response)
		      	});
		   
		    },
		    remove_temp_comp: function(){
		    	var work_contractor_id = $("#hidden_work_contractor_id").val();
		    	this.works_id = $("#cont_cpono").html();
		    	var r = confirm("Are you sure you want to remove selected Pending Company?");
      			if (r == true) {
			    	axios.post("<?php echo base_url() ?>works/remove_temporary_cont_sup", 
			      	{
			      		'work_contractor_id': work_contractor_id
			      	}).then(response => {	
			          	window.selwork(this.works_id);
			      	}).catch(error => {
			        	console.log(error.response)
			      	});
			    }
		    },
		}
	});
	
	var state = $('#state').val();
	var baseurl = $("#base_url").val(); 
	var proj_post_code = $("#proj_post_code").val();

	$("#btnaddcontractor").click(function(){
		$("#add_selected_con_sup").show();
        $("#fltr_cont_saving_button").hide();
		$("#tbl_contractors_list").hide();
		$("#loading_img").show();
	    $("#search_contractor").val("");
	    $("#works_state").val("");
	    var formattedDate = new Date();
	    var d = formattedDate.getDate();
	    var m =  formattedDate.getMonth();
	    m += 1;  // JavaScript months are 0-11
	    var y = formattedDate.getFullYear();
	   
	    $("#add_contractor_date_entered").val(d+"/"+m+"/"+y);
	 
	    // $("#save_contractor").show();

	    $.post(baseurl+"works/fetch_comp_suburb",
	    {},
	    function(result){

		    $('#works_suburb').empty()
		    var opts = $.parseJSON(result);
		    $('#works_suburb').append('<option value="">Select Suburb</option>');
            $.each(opts, function(i, d) {
                $('#works_suburb').append('<option value="' + d.suburb + '">' + d.suburb + '</option>');
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
		    	$('#comp_activity').empty()
		    	 var opts = $.parseJSON(result);
                $.each(opts, function(i, d) {
                	if(activity_id == d.id ){
                		sel_activity = d.name;
                		$('#comp_activity').append('<option value="' + d.id + '" selected = "Selected">' + d.name + '</option>');
                	}else{
                		$('#comp_activity').append('<option value="' + d.id + '">' + d.name + '</option>');
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
			    	$("#lbl_filteredby").html(label);
			        $("#tbl_contractors_list").html(result);
			        $("#tbl_contractors_list").show();
					$("#loading_img").hide();
			    });
		    });
	        
	    });	    
	   
	});
	
	
    $("#works_state").change(function(){
    	var works_state = $("#works_state").val();
    	var works_state_arr = works_state.split("|");
    	var state_name = works_state_arr[1];
    	
    	$.post(baseurl+"works/fetch_comp_suburb",
	    {
	    	state_name : state_name
	    },
	    function(result){
		    $('#works_suburb').empty()
		    var opts = $.parseJSON(result);
		    $('#works_suburb').append('<option value="">Select Suburb</option>');
            $.each(opts, function(i, d) {
                $('#works_suburb').append('<option value="' + d.suburb + '">' + d.suburb + '</option>');
            });
	   	});
    });

    $("#btn_search_contractor").click(function(){
    	$("#tbl_contractors_list").hide();
		$("#loading_img").show();
    	var text_search = $("#search_contractor").val();
    	$.post(baseurl+"works/search_add_contractors_list",
		{
		    text_search: text_search
		},
		function(result){
		   	// alert(result);
		  	
		    var label = "All Contractors / Suppliers Search Regardless of Activity and Location";
		    $("#lbl_filteredby").html(label);
		    $("#tbl_contractors_list").html(result);
		    $("#tbl_contractors_list").show();
			$("#loading_img").hide();
		});
    });

    $("#filter_con_sup").click(function(){
    	$("#tbl_contractors_list").hide();
		$("#loading_img").show();
    	var activity_id = $("#comp_activity").val();
    	var works_state = $("#works_state").val();
    	var suburb = $("#works_suburb").val();
    	var post_code = $("#post_code").val();
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
			$("#lbl_filteredby").html(label);
			$("#tbl_contractors_list").html(result);
			$("#tbl_contractors_list").show();
			$("#loading_img").hide();
		});
    });

	$("#add_selected_con_sup").click(function(){
		$("#add_selected_con_sup").hide();
        $("#fltr_cont_saving_button").show();
		var checkboxValues = [];
		var date_entered = $("#add_contractor_date_entered").val();
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
              		$("#add_selected_con_sup").show();
              		$("#fltr_cont_saving_button").hide();
              		$("#work_contractors").html(result);
            	}, 5000);  // on 5 second
          	});

  		});

	})
 
</script>