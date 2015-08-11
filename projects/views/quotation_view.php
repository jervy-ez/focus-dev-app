<form class="form-horizontal project-form quotation-view" role="form" method="post" action="" <?php if(!$hasQuote){echo 'style="display:none;"';} ?> >
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-9">
				<div class="left-section-box m-top-10">
					
					<div class="box-head pad-10 clearfix">	
						<div class="btn btn-info pull-right" data-toggle="collapse" data-target=".more-list">Add Work</div>		
						<label>Project Quotation</label><span> (<a href="#" data-placement="right" class="popover-test" title="" data-content="Hello there mate! Welcome to the clients screen." data-original-title="Welcome">?</a>)</span>
						<p>Fields having * is requred.</p>	
						<p><a href="#" class="tooltip-test" title="" data-original-title="Tooltip">This link</a> and <a href="#" class="tooltip-test" title="" data-original-title="Tooltip">that link</a> should have tooltips on hover.</p>						
					</div>
					
					<div class="m-bottom-5 clearfix">
						<div class="pad-5 no-pad-t">
							<div class="col-md-3 col-sm-3 col-xs-6 box-widget">
								<div class="box ">
									<div class="box-head pad-5"><label> Mark-Up</label></div>
									<div class="box-area pad-5 text-center pattern-sandstone pad-bottom-10">
										<p>Project Mark-Up</p>
										<h2 class="sm">10%</h2>
									</div>
								</div>
							</div>
	
							<div class="col-md-3 col-sm-3 col-xs-6 box-widget">
								<div class="box ">
									<div class="box-head pad-5"><label> Install Hours</label></div>
									<div class="box-area pad-5 text-center pattern-sandstone pad-bottom-10">
										<p>Total Install Hours</p>
										<h2 class="sm">20 Hrs</h2>
									</div>
								</div>
							</div>
					
							<div class="col-md-3 col-sm-3 col-xs-6 box-widget">
								<div class="box ">
									<div class="box-head pad-5"><label> Sub-Contractor</label></div>
									<div class="box-area pad-5 text-center pattern-sandstone pad-bottom-10">
										<p>Sub-Contractor Cost</p>
										<h2 class="sm">$50,000</h2>
									</div>
								</div>
							</div>
							
							<div class="col-md-3 col-sm-3 col-xs-6 box-widget">
								<div class="box ">
									<div class="box-head pad-5"><label> Gross Profit</label></div>
									<div class="box-area pad-5 text-center pattern-sandstone pad-bottom-10">
										<p>Total</p>
										<h2 class="sm">$75,500</h2>
									</div>
								</div>
							</div>
						
						</div>	
					</div>
														
					<div class="more-list out collapse" style="height: 0px;">

						<div class="box-area pad-10  clearfix">

							<div class="box-tabs m-bottom-15">
								<ul id="myTab" class="nav nav-tabs">
									<li class="active">
										<a href="#contractor" data-toggle="tab"><i class="fa fa-globe fa-lg"></i> Contractor</a>
									</li>
									<li class="">
										<a href="#supplier" data-toggle="tab"><i class="fa fa-inbox fa-lg"></i> Supplier</a>
									</li>											
								</ul>
								<div class="tab-content">
									<div class="tab-pane fade clearfix active in" id="contractor">													
										<div class="col-sm-6 m-bottom-10 clearfix ">
											<label for="work-type" class="col-sm-3 control-label">Work Type</label>
											<div class="col-sm-9  col-xs-12">
												<select class="form-control work-type" id="work-type" name="work-type">
													<option value="">Work Type</option>
													<?php $this->projects->job_cat_list(); ?>		
												</select>
											</div>
										</div>
									
										<div class="col-sm-6 m-bottom-10 clearfix ">
											<label for="work-desc" class="col-sm-3 control-label">Work Description</label>
											<div class="col-sm-9  col-xs-12">
												<select class="form-control work-desc" id="work-desc" name="work-desc">
													<option value="">Work Description</option>
													<?php //$this->projects->sub_job_cat_list(); ?>
												</select>
											</div>
										</div>												
									</div>

									<div class="tab-pane fade clearfix" id="supplier">
										<div class="col-sm-6 m-bottom-10 clearfix ">
											<label for="supplier-type" class="col-sm-3 control-label">Work Type</label>
											<div class="col-sm-9  col-xs-12">
												<select class="form-control work-type" id="supplier-type" name="supplier-type">
													<option value="">Work Type</option>
													<?php $this->projects->supplier_cat_list(); ?>
												</select>
											</div>
										</div>


										<div class="col-sm-6 m-bottom-10 clearfix">
											<div class="input-group col-md-10 pull-right">
												<input type="text" id="disabledInput" value="Deliver to Office" class="form-control readonly" readonly="readonly">
												<span class="input-group-addon"> <input type="checkbox" class="deliver_to_office" name="deliver_to_office"> Yes</span>
											</div>
										</div>
										

                                        <div class="col-sm-6 col-xs-12 m-bottom-10 clearfix ">
                                            <label for="delivery_notes" class="col-sm-3 control-label">Delivery Notes</label>
                                            <div class="col-sm-9 col-xs-12">
                                                <textarea style="resize: vertical;" class="form-control" id="delivery_notes" rows="3" name="delivery_notes" name="delivery_notes"></textarea>
                                            </div>
                                        </div>												
									</div>
								</div>
							</div>



							<div class="box m-bottom-15 clearfix">
								<div class="box-head pad-5 m-bottom-5">
									<label><i class="fa fa-book fa-lg"></i> Work Details</label>
								</div>
								
								<div class="box-area pad-5 clearfix">
									
									<div class="col-sm-6 m-bottom-10 clearfix">
										<label for="work-estinamte" class="col-sm-3 control-label">Estimate</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="work-estinamte" placeholder="Estimate" name="work-estinamte" value="">
										</div>
									</div>
									
									<div class="col-sm-6 m-bottom-10 clearfix ">
										<label for="work-mark-up" class="col-sm-3 control-label">Mark-Up</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="work-mark-up" placeholder="Mark-Up" name="work-mark-up">
										</div>
									</div>
									
									<div class="col-sm-6 m-bottom-10 clearfix ">
										<label for="work-contactperson" class="col-sm-3 control-label">Contact Person*</label>
										<div class="col-sm-9  col-xs-12">
											<select name="work-contactperson" class="form-control" id="work-contactperson" style="width: 100%;">
												<option value="">Select Contact Person</option>
												<option value="add">Add New</option>
												<?php $this->company->contact_person_list(); ?>
											</select>
										</div>
									</div>
										
									<div class="col-sm-6 col-xs-12 m-bottom-10 clearfix ">
										<label for="work_reply_date" class="col-sm-3 control-label">Reply By</label>
										<div class="col-sm-9 col-xs-12">
											<input type="date" class="form-control" id="work_reply_date" name="work_reply_date" value="">
										</div>
									</div>
									
									<div class="col-sm-6 col-xs-12 m-bottom-10 clearfix ">
										<label for="work_start_date" class="col-sm-3 control-label">Work Start Date</label>
										<div class="col-sm-9 col-xs-12">
											<input type="date" class="form-control" id="work_start_date" name="work_start_date" value="">
										</div>
									</div>
									
									<div class="col-sm-6 col-xs-12 m-bottom-10 clearfix ">
										<label for="work_finish_date" class="col-sm-3 control-label">Work Finish Date</label>
										<div class="col-sm-9 col-xs-12">
											<input type="date" class="form-control" id="work_finish_date" name="work_finish_date" value="">
										</div>
									</div>
									
									<div class="col-sm-6 col-xs-12 m-bottom-10 clearfix ">
										<label for="work_cpo_req" class="col-sm-3 control-label">Date CPO Req</label>
										<div class="col-sm-9 col-xs-12">
											<input type="date" class="form-control" id="work_cpo_req" name="work_cpo_req" value="">
										</div>
									</div>												
									
									<div class="col-sm-6 col-xs-12 m-bottom-10 clearfix ">
										<label for="work_cpo_date" class="col-sm-3 control-label">CPO Date</label>
										<div class="col-sm-9 col-xs-12">
											<input type="date" class="form-control" id="work_cpo_date" name="work_cpo_date" value="">
										</div>
									</div>
                                        
								</div>
							</div>
							
							
							<div class="box m-bottom-15 clearfix">
								<div class="box-head pad-5 m-bottom-5">
									<label><i class="fa fa-calendar fa-lg"></i> Considerations</label>
								</div>
								
								<div class="col-sm-3 m-bottom-10 clearfix ">
									<label for="con_instection_req" class="col-sm-9 control-label">Site Inspection Required</label>
									<div class="col-sm-3 pad-top-5">
										<input type="checkbox" class="con_instection_req" name="con_instection_req" id="con_instection_req">
									</div>
								</div>
								
								<div class="col-sm-3 m-bottom-10 clearfix ">
									<label for="con_special_condition" class="col-sm-9 control-label">Special Conditions</label>
									<div class="col-sm-3 pad-top-5">
										<input type="checkbox" class="con_special_condition" name="con_special_condition" id="con_special_condition">
									</div>
								</div>
								
								<div class="col-sm-3 m-bottom-10 clearfix ">
									<label for="con_additional_visits" class="col-sm-9 control-label">Additional Visits Required</label>
									<div class="col-sm-3 pad-top-5">
										<input type="checkbox" class="con_additional_visits" name="con_additional_visits" id="con_additional_visits">
									</div>
								</div>
								
								<div class="col-sm-3 m-bottom-10 clearfix ">
									<label for="con_operate_dur_install" class="col-sm-9 control-label">Operate During Install</label>
									<div class="col-sm-3 pad-top-5">
										<input type="checkbox" class="con_operate_dur_install" name="con_operate_dur_install" id="con_operate_dur_install">
									</div>
								</div>
								
								<div class="col-sm-3 m-bottom-10 clearfix ">
									<label for="con_week_work" class="col-sm-9 control-label">Week Work</label>
									<div class="col-sm-3 pad-top-5">
										<input type="checkbox" class="con_week_work" name="con_week_work" id="con_week_work">
									</div>
								</div>
								
								<div class="col-sm-3 m-bottom-10 clearfix ">
									<label for="con_weeked_work" class="col-sm-9 control-label">Weedend Work</label>
									<div class="col-sm-3 pad-top-5">
										<input type="checkbox" class="con_weeked_work" name="con_weeked_work" id="con_weeked_work">
									</div>
								</div>
								
								<div class="col-sm-3 m-bottom-10 clearfix ">
									<label for="con_new_premises" class="col-sm-9 control-label">New Premises</label>
									<div class="col-sm-3 pad-top-5">
										<input type="checkbox" class="con_new_premises" name="con_new_premises" id="con_new_premises">
									</div>
								</div>
								
								<div class="col-sm-3 m-bottom-10 clearfix ">
									<label for="con_after_hrs_wrk" class="col-sm-9 control-label">After Hours Work</label>
									<div class="col-sm-3 pad-top-5">
										<input type="checkbox" class="con_after_hrs_wrk" name="con_after_hrs_wrk" id="con_after_hrs_wrk">
									</div>
								</div>
								
								<div class="col-sm-3 m-bottom-10 clearfix ">
									<label for="con_free_access" class="col-sm-9 control-label">Free Access</label>
									<div class="col-sm-3 pad-top-5">
										<input type="checkbox" class="con_free_access" name="con_free_access" id="con_free_access">
									</div>
								</div>
																			
								<div class="col-sm-9 col-xs-12 m-bottom-10 clearfix ">
									<label for="con_other" class="col-sm-3 control-label">Other : </label>
									<div class="col-sm-9 col-xs-12">
										<input type="text" class="con_other form-control" id="con_other" name="con_other" value="">
									</div>
								</div>									
								
							</div>
							
							
							<div class="box m-bottom-15 clearfix">
								<div class="box-head pad-5 m-bottom-5">
									<label><i class="fa fa-calendar fa-lg"></i> Attachements</label>
								</div>
								
								<div class="col-sm-3 m-bottom-10 clearfix ">
									<label for="plans" class="col-sm-9 control-label">Plans</label>
									<div class="col-sm-3 pad-top-5">
										<input type="checkbox" class="plans" name="plans" id="plans">
									</div>
								</div>
								
								<div class="col-sm-3 m-bottom-10 clearfix ">
									<label for="elevations" class="col-sm-9 control-label">Elevations</label>
									<div class="col-sm-3 pad-top-5">
										<input type="checkbox" class="elevations" name="elevations" id="elevations">
									</div>
								</div>
								
								<div class="col-sm-3 m-bottom-10 clearfix ">
									<label for="works_schedule" class="col-sm-9 control-label">Works Schedule</label>
									<div class="col-sm-3 pad-top-5">
										<input type="checkbox" class="works_schedule" name="works_schedule" id="works_schedule">
									</div>
								</div>
								
								<div class="col-sm-3 col-xs-12 m-bottom-10 clearfix ">
									<label for="attc_other" class="col-sm-3 control-label">Other : </label>
									<div class="col-sm-9 col-xs-12">
										<input type="text" class="form-control attc_other" id="attc_other" name="attc_other" value="">
									</div>
								</div>
							</div>
							
							<div class="box m-top-15">
								<div class="box-head pad-5">
									<label for="project_notes"><i class="fa fa-pencil-square fa-lg"></i> Notes</label>
								</div>
								
								<div class="box-area pad-5 clearfix">
									<div class="clearfix ">
										
										<div class="">
											<textarea style="resize: vertical;" class="form-control" id="project_notes" rows="3" name="project_notes"></textarea>														
										</div>
									</div>
								</div>
							</div>
							
						    <div class="m-top-15 clearfix">
						    	<div>
						        	<button type="submit" class="btn btn-success save-project" name="submit_save_work" value="submit_save_work">Save Work</button>
						        </div>

						    </div>
						</div>
					</div>

					<div class="box-area pad-10 clearfix">											
						
						<div class="box m-bottom-0 m-top-15">
							<div class="box-head pad-5 border-0">
								<label><i class="fa fa-info-circle fa-lg"></i> Project Job List</label>
							</div>							
						</div>
							
							<table id="job-tbl" class="table table-striped table-bordered" cellspacing="0" width="100%">
							<thead><tr><th>Work Type</th><th>Company</th> <th>CPO Number</th><th>Price</th><th>Estimated</th><th>Quoted</th><th></th></tr></thead>									
							<tbody class="works-list-body">
								<?php echo $this->projects->display_all_works($project_number); ?>
								
							</tbody>
						</table>
					</div>
					
				</div>
			</div>
			
			<div class="col-md-3">							
				<div class="box">
					<div class="box-head pad-5 border-0">
						<label><i class="fa fa-info-circle fa-lg"></i> Project Number : <em><?php echo $project_number; ?></em></label>
					</div>							
				</div>
				
				<div class="box">
					<div class="box-head pad-5">
						<label><i class="fa fa-info-circle fa-lg"></i> Information</label>
					</div>
					<div class="box-area pad-10">
						<p>
							Completing the basic project information, proceeds to input the projects quotatoin and invoicing.
						</p>
					</div>
				</div>
				
				<div class="box select-company out collapse" style="height: 0px;">
					<div class="box-head pad-5">
						<label><i class="fa fa-info-circle fa-lg"></i> Select Company</label>
					</div>
					<div class="box-area pad-10">
						<div class="clearfix m-top-5 m-bottom-5">
							<div class="col-md-12 col-lg-12  col-sm-3">													
								
								<div class="input-group <?php if(form_error('company_prg')){ echo 'has-error has-feedback';} ?>">
									<span class="input-group-addon"><i class="fa fa-briefcase  fa-lg"></i></span>
										<select name="company_prg" class="form-control select-comp-type" id="company_prg" style="width: 100%;">
										<option value=''>Select Company</option>
										<?php if($this->input->post('company_prg')!=''): ?>
										<option selected="" value="<?php echo $this->input->post('company_prg'); ?>"><?php echo $this->input->post('company_prg'); ?></option>
										<?php endif; ?>													
										<?php //$this->company->select_contractor_supplier('2'); ?>	
									</select>
								</div>
								
							</div>
						</div>	
					</div>
				</div>
				
			</div>
		</div>				
	</div>
</form>