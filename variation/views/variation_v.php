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


								<div class="input-group pull-right search-work-desc">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-search"></i></span>
										<input type="text" class="form-control input-wd" placeholder="Looking for...">										
									</div>
								</div>
								


								<!--<button type = "button" id = "showaddworkmodal" class="btn btn-primary pull-right" data-toggle="modal" data-target="#frmaddworks"><i class = "fa fa-cogs"></i> Add Work</button>-->
								<!--<a href="samplemod/print_pdf"  target="_blank" class="btn btn-primary pull-right"><i class = "fa fa-print"></i> Print</a>-->
								<label><?php echo $screen ?></label><span>(<a href="#" data-placement="right" class="popover-test" title="" data-content="This is where the works of the selected Project are listed." data-original-title="Welcome">?</a>)</span>
								<p>This is where the Variations of the selected Project are listed. </p>
							</div>

							<div id="tbl_works" class="pad-right-10">
								<div class="table-header">
									<table class="table table-condensed table-bordered m-bottom-0">
										<thead>
											<tr>
												<th width="30%">Variation Description</th>
												<th width="10%">Date</th>
												<th width="10%">Installation</th>
												<th width="10%">Credit</th>
												<th width="10%">Total Works Quoted</th>
												<th width="10%">Unaccepted Total</th>
												<th width="10%">Accepted Total</th>
											</tr>
										</thead>
									</table>
								</div>
								<div id = "proj_variation_list" class="table-warp">
									
								</div>
								<div class="table-footer">
									<table class="table table-condensed table-bordered m-bottom-0">
										<tfoot>
											<tr>
												<th colspan = 3 class = "text-right">Total:</th>

												<th width="10%" class = "text-right">
													<input type="text" id = "var_unaccepted_total" class="input_text text-right number_format" style = "width: 100%" disabled>
												</th>
												<th width="10%" class = "text-right">
													<input type="text" id = "var_accepted_total" class="input_text text-right number_format" style = "width: 100%" disabled>
												</th>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="box pad-5">
							<div class="box-head pad-5">
								<label><i class="fa fa-cube fa-lg"></i> Variation Details</label>
								<label id = "cont_cpono"></label>
								<button type="button" id="add_new_var" class ="btn btn-primary pull-right">Add New Variation</button>
							</div>
							<div><strong>Variation Name:</strong></div>
							<div><input type="text" class = "input-sm form-control" id = "variation_name"></div>
							<div><strong>Variation Notes:</strong></div>
							<div><textarea class = "input-sm form-control" style = "height: 50px" id = "variation_notes"></textarea></div>
							<div><strong>Site Hours:</strong></div>
							<div><input type="text" class = "input-sm form-control text-right" id = "var_site_hrs"></div>
							<div><strong>Is Double Time:</strong></div>
							<div>
								<select class = "input-sm form-control" id = "var_is_double_time">
									<option value="0">No</option>
									<option value="1">Yes</option>
								</select>
							</div>
							<div><strong>Variation Credit:</strong></div>
							<div><input type="text" class = "input-sm form-control text-right number_format" id = "var_credit"></div>
							<div><strong>Variation Mark-up:</strong></div>
							<div><input type="text" class = "input-sm form-control text-right" id = "var_markup"></div>
							<div><strong>Acceptance Date:</strong></div>
							<div><input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="input-sm form-control datepicker" id = "var_acceptance_date"></div>
							<div class = "pad-10" align = right><button type = "button" id = "var_save" class = "btn btn-primary">Save</button><button type = "button" id = "var_update" class = "btn btn-success">Update</button><button type = "button" id = "var_delete" class = "btn btn-danger" data-toggle="modal" data-target="#var_delete_modal">Delete</button></div>
							<!--<div class="col-sm-12"><button type = "button" id = "btn_select_subcontractor" name = "btn_select_subcontractor" class = "btn btn-success col-sm-12"><i class="fa fa-hand-o-up"></i> Select Sub-Contractor</button></div>-->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Moda -->
<div class="modal fade" id="var_delete_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
	    <div class="modal-content">
	        <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		        <h4 class="modal-title">Confirmation</h4>
	        </div>
	        <div class="modal-body">
	        	Are you sure you want to delete the selected Variation?
	        </div>
	        <div class="modal-footer">
	          	<button type="button" class="btn btn-danger" id = "delete_var_yes" data-dismiss="modal">Yes</button>
	          	<button type="button" class="btn" id = "delete_var_no" data-dismiss="modal"> No</button>
	        </div>
	    </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->