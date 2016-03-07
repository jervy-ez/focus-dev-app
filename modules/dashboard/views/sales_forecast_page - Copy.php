<?php date_default_timezone_set("Australia/Perth");  // date is set to perth ?>
<?php $this->load->module('bulletin_board'); ?>
<?php $this->load->module('dashboard'); ?>
<?php $months = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"); ?>
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

<div class="container-fluid"  style="background: #ECF0F5;">
	<!-- Example row of columns -->
	<div class="row">				
		<?php $this->load->view('assets/sidebar'); ?>
		<div class="section col-sm-12 col-md-11 col-lg-11 pad-0-imp no-m-imp">
			<div class="">

				<div class="clearfix pad-10">
					<div class="widget_area row pad-0-imp no-m-imp">

						<!-- ************************ -->
							 

						<?php if($this->session->userdata('is_admin') == 1 ): ?>

							<div class="col-xs-12 box-widget pad-10">

								<?php if(@$error): ?>
									<div class="widget wid-type-d widg-head-styled pad-0-imp m-bottom-20">
										<span class="label label-default pull-right pointer" data-dismiss="alert" style="display: block; margin: 6px 9px;">x</span>									
										<div class="widg-head box-widg-head pad-5"><i class="fa fa-exclamation-triangle"></i> <strong><?php echo $error; ?></strong> </div>									
									</div> 
								<?php endif; ?>

								<?php if(@$this->session->flashdata('save_success')): ?> 
									<div class="widget wid-type-b widg-head-styled pad-0-imp m-bottom-20">
										<span class="label label-default pull-right pointer" data-dismiss="alert" style="display: block; margin: 6px 9px;">x</span>									
										<div class="widg-head box-widg-head pad-5"><i class="fa fa-exclamation-triangle"></i> <strong><?php echo $this->session->flashdata('save_success');?></strong> </div>									
									</div>
								<?php endif; ?>


								<?php if(@$this->session->flashdata('record_update')): ?> 
									<div class="widget wid-type-b widg-head-styled pad-0-imp m-bottom-20">
										<span class="label label-default pull-right pointer" data-dismiss="alert" style="display: block; margin: 6px 9px;">x</span>									
										<div class="widg-head box-widg-head pad-5"><i class="fa fa-exclamation-triangle"></i> <strong><?php echo $this->session->flashdata('record_update');?></strong> </div>									
									</div>
								<?php endif; ?>	


								<div class="widget wid-type-a widg-head-styled">
									<div class="widg-head box-widg-head pad-5"><i class="fa fa-cog"></i> 
										<strong class="pointer collapsed" data-toggle="collapse" data-target=".data_forecast">Forecast Settings</strong>
										<span class="badges pull-right m-right-10"> 
											<span class="tabs <?php if($tab_view == 'form'){ echo 'active';  } ?>" id="tab_addnew">Add New</span> 
											<span class="tabs <?php if($tab_view == 'view'){ echo 'active';  } ?>" id="tab_forecasts">Forecasts</span> 
										</span>
									</div>


								
									<div class="box-area clearfix data_forecast collapse <?php if(isset($form_toggle)){ echo 'in'; }else{ echo 'out'; } ?>">
										<div class="widg-content clearfix">
											<div class="tab_container">
												<div id="tab_addnew_area" class="tab_area active clearfix row pad-0-imp no-m-imp"  <?php if($tab_view != 'form'){ echo 'style="display:none;"';  } ?> >
													<form method="post" id="forecast_form" class="m-top-5 m-bottom-5 clearfix" action="">

													<div class="col-md-8 col-sm-12 col-xs-12" id="">
														<strong class="m-bottom-10 block data_label">Add New Data</strong> <small class="block m-bottom-10"><em>Note: The sales from calendar year <strong>"<u><?php echo $old_year; ?></u>"</strong> is being used.</em></small>
														<script type="text/javascript"> //$('select#data_year').val('<?php if(@$this->session->flashdata("data_year")){ echo $this->session->flashdata("data_year"); } ?>');</script>

															<div class="clearfix row pad-0-imp no-m-imp">

																<div class="col-lg-3 col-md-4 col-sm-6 col-xs-6 clearfix ">
																	<div class="input-group m-bottom-10">
																		<span class="input-group-addon">Forecast Year</span>
																		<select name="data_year" id="data_year" class="form-control m-bottom-10 input-sm">
																			<option selected value="" style="display:none;">Select Year</option>
																			<?php $year = date('Y'); for($i=0; $i < 2; $i++){																		
																				echo '<option value="'.($year+$i).'">'.($year+$i).'</option>';
																			}?>
																		</select>
																		<script type="text/javascript">$('select#data_year').val('<?php echo $old_year+1; ?>');</script>
																	</div>
																</div>

																<div class="col-lg-4 col-xs-12" id="">	
																	<div class="input-group m-bottom-15 <?php if(form_error('data_label')){ echo 'has-error has-feedback';} ?>">
																		<span class="input-group-addon" id="">Label</span>      		
																		<input type="text" class="form-control m-bottom-10 data_label input-sm" name="data_label" id="data_label" placeholder="Forecast Label" value="<?php echo $this->input->post('data_label'); ?>">
																	</div>
																</div>

																<div class="clearfix"></div>
																<?php $focus_comp_list = array(); ?>

																<?php foreach ($focus as $key => $value): ?>
																	<?php if($value->company_name != 'FSF Group Pty Ltd'): ?>
																		<?php $focus_comp_list[$value->company_id] = $value->company_name; ?>
																		<div class="col-lg-5 col-md-6 col-xs-6" id="">	
																			<div class="input-group m-bottom-10 <?php if(form_error('focus_id_'.$value->company_id)){ echo 'has-error has-feedback';} ?>">
																				<span class="input-group-addon" id=""><?php echo $value->company_name; ?></span> 
																				<input type="text" class="form-control m-bottom-10 data_name input-sm number_format focus_comp_split click_select focus_percent_val" id="focus_id_<?php echo $value->company_id; ?>" onKeyUp="check_split('focus_comp_split','focus_id_<?php echo $value->company_id; ?>');" name="focus_id_<?php echo $value->company_id; ?>" placeholder="%" value="<?php echo $this->input->post('focus_id_'.$value->company_id); ?>">
																				<input type="text" disabled="disabled" readonly="readonly" value="" class="form-control input-sm focus_computed"  id="focus_computed_<?php echo $value->company_id; ?>" style="display:none;" />
																				<span class="input-group-addon">
																					<strong id="focus_percent_<?php echo $value->company_id; ?>"><?php echo $this->dashboard->_get_focus_splits($old_year,$value->company_id); ?>%</strong>
																				</span>  	
																			</div>
																		</div>
																	<?php endif; ?>
																<?php endforeach; ?>

																<?php $group = 0; $group_min = 0; $add_custom_min = 0; $start = 0; $counter = 0;?>
																<?php foreach ($pm_names as $key => $names): ?>
	 

																	<?php $start = $names->user_focus_company_id; ?>

																	<?php if($counter > 0 && $start != $add_custom_min): ?>
																		<div class="col-lg-4 col-md-6 col-xs-6" id="">	
																			<div class="input-group m-bottom-10 <?php if(form_error($add_custom_min.'_focus_pmid_'.$maintenance_id)){ echo 'has-error has-feedback';} ?>">
																				<span class="input-group-addon" id="">Maintenance</span>
																				<input type="text" readonly="readonly" class="form-control m-bottom-10 data_name input-sm number_format click_select  focus_comp_id_<?php echo $add_custom_min; ?> focus_percent_val_pm" id="<?php echo $add_custom_min; ?>_focus_pmid_<?php echo $maintenance_id; ?>"  onKeyUp="check_split('focus_comp_id_<?php echo $add_custom_min; ?>','<?php echo $add_custom_min; ?>_focus_pmid_<?php echo $maintenance_id; ?>');"   name="<?php echo $add_custom_min; ?>_focus_pmid_<?php echo $maintenance_id; ?>" placeholder="%" value="<?php echo $this->input->post($add_custom_min."_focus_pmid_".$maintenance_id); ?>">
																				<input type="text" disabled="disabled" readonly="readonly" value="" class="form-control input-sm focus_computed"  id="focus_computed_<?php echo $add_custom_min; ?>" style="display:none;" />
																				<span class="input-group-addon" id=""><strong><?php echo $this->dashboard->_get_focus_pm_splits($old_year,'5','29'); ?>%</strong></span> 
																				<span class="input-group-addon pointer add_custom_pm" id="add_custom_id_<?php echo $add_custom_min; ?>" onClick="add_custom_pm('focus_comp_id_<?php echo $add_custom_min; ?>','add_custom_id_<?php echo $add_custom_min; ?>');"><strong>+</strong></span> 
																			</div>
																		</div>

																		<?php if(!empty($other)): ?>
																			<?php foreach ($other as $key => $value): ?>
																				<?php $other_data = explode('_', $value);  ?>
																				<?php if($other_data[0] == $add_custom_min): $new_id = $other_data[3].'x'; ?>

																					<div class="col-lg-4 col-md-6 col-xs-6" id="">
																						<div class="input-group m-bottom-10">
																							<input type="text" class="form-control m-bottom-10 input-sm " id="<?php echo $add_custom_min; ?>_other_nmx_<?php echo $new_id; ?>" name="<?php echo $add_custom_min; ?>_other_nm_<?php echo $new_id; ?>" placeholder="Other" value="<?php echo $this->input->post($add_custom_min.'_other_nm_'.$other_data[3]); ?>" style="width: 50%;">
																							<input type="text" class="focus_comp_id_<?php echo $add_custom_min; ?> form-control m-bottom-10 data_name input-sm click_select focus_percent_val_pm" id="<?php echo $add_custom_min; ?>_other_pmx_<?php echo $new_id; ?>" name="<?php echo $add_custom_min; ?>_other_pm_<?php echo $new_id; ?>" placeholder="%" value="<?php echo $this->input->post($add_custom_min.'_other_pm_'.$other_data[3]); ?>" style="width: 50%;" onkeyup="check_split('focus_comp_id_<?php echo $add_custom_min; ?>','<?php echo $add_custom_min; ?>_other_pmx_<?php echo $new_id; ?>');">
																							<input type="text" disabled="disabled" readonly="readonly" value="" class="form-control input-sm focus_computed" id="focus_computed_other_<?php echo $new_id; ?>" style="display:none; width: 50%;">
																							<span class="input-group-addon pointer remove_custom_pm" onclick="remove_custom_pm(this);" id="rm_bttn_otr_<?php echo $new_id; ?>"><strong><i class="fa fa-trash-o"></i></strong></span>
																						</div>
																					</div>
																				<?php endif; ?>
																			<?php endforeach; ?>
																		<?php endif; ?>


																	<?php endif; ?>

																	<?php if($group != $names->user_focus_company_id ): $group = $names->user_focus_company_id;?>
																		<div class="clearfix"></div><hr class="block m-bottom-5 m-top-5"><strong class="m-bottom-10 block data_label"><?php echo $names->company_name; ?></strong><div class="clearfix"></div>
																	<?php endif; ?>

																	<div class="col-lg-4 col-md-6 col-xs-6" id="">	
																		<div class="input-group m-bottom-10 <?php if(form_error($names->user_focus_company_id.'_focus_pmid_'.$names->user_id)){ echo 'has-error has-feedback';} ?>">
																			<span class="input-group-addon" id=""><?php echo $names->user_pm; ?></span>
																			<input type="text" class="form-control m-bottom-10 data_name input-sm number_format click_select  focus_comp_id_<?php echo $names->user_focus_company_id; ?> focus_percent_val_pm" id="<?php echo $names->user_focus_company_id; ?>_focus_pmid_<?php echo $names->user_id; ?>"  onKeyUp="check_split('focus_comp_id_<?php echo $names->user_focus_company_id; ?>','<?php echo $names->user_focus_company_id; ?>_focus_pmid_<?php echo $names->user_id; ?>');"   name="<?php echo $names->user_focus_company_id; ?>_focus_pmid_<?php echo $names->user_id; ?>" placeholder="%" value="<?php echo $this->input->post($names->user_focus_company_id.'_focus_pmid_'.$names->user_id); ?>">
																			<input type="text" disabled="disabled" readonly="readonly" value="" class="form-control input-sm focus_computed"  id="focus_computed_<?php echo $names->user_focus_company_id; ?>" style="display:none;" />
																			<span class="input-group-addon" id=""><strong><?php echo $this->dashboard->_get_focus_pm_splits($old_year,$names->user_focus_company_id,$names->user_id); ?>%</strong></span> 
																		</div>
																	</div>																	

																	<?php $counter++; ?>
																	<?php $add_custom_min = $names->user_focus_company_id; ?>
																	<?php $last_focus_id = $names->user_focus_company_id; ?>

																<?php endforeach; ?>

																<div class="col-lg-4 col-md-6 col-xs-6" id="">	
																	<div class="input-group m-bottom-10 <?php if(form_error($last_focus_id.'_focus_pmid_'.$maintenance_id)){ echo 'has-error has-feedback';} ?>">
																		<span class="input-group-addon" id="">Maintenance</span>
																		<input type="text" readonly="readonly" class="form-control m-bottom-10 data_name input-sm number_format click_select  focus_comp_id_<?php echo $last_focus_id; ?> focus_percent_val_pm" id="<?php echo $last_focus_id; ?>_focus_pmid_<?php echo $maintenance_id; ?>"  onKeyUp="check_split('focus_comp_id_<?php echo $last_focus_id; ?>','<?php echo $last_focus_id; ?>_focus_pmid_<?php echo $maintenance_id; ?>');"   name="<?php echo $last_focus_id; ?>_focus_pmid_<?php echo $maintenance_id; ?>" placeholder="%" value="<?php echo $this->input->post($last_focus_id.'_focus_pmid_'.$maintenance_id); ?>">
																		<input type="text" disabled="disabled" readonly="readonly" value="" class="form-control input-sm focus_computed"  id="focus_computed_<?php echo $last_focus_id; ?>" style="display:none;" />
																		<span class="input-group-addon" id=""><strong><?php echo $this->dashboard->_get_focus_pm_splits($old_year,'6','29'); ?>%</strong></span>
																		<span class="input-group-addon pointer add_custom_pm" id="add_custom_id_<?php echo $last_focus_id; ?>" onClick="add_custom_pm('focus_comp_id_<?php echo $last_focus_id; ?>','add_custom_id_<?php echo $last_focus_id; ?>');"><strong>+</strong></span>  
																	</div>
																</div>

																<?php if(!empty($other)): ?>
																	<?php foreach ($other as $key => $value): ?>
																		<?php $other_data = explode('_', $value);  ?>
																		<?php if($other_data[0] == $last_focus_id): $new_id = $other_data[3].'x'; ?>

																		<div class="col-lg-4 col-md-6 col-xs-6" id="">
																			<div class="input-group m-bottom-10">
																				<input type="text" class="form-control m-bottom-10 input-sm " id="<?php echo $last_focus_id; ?>_other_nmx_<?php echo $new_id; ?>" name="<?php echo $last_focus_id; ?>_other_nm_<?php echo $new_id; ?>" placeholder="Other" value="<?php echo $this->input->post($last_focus_id.'_other_nm_'.$other_data[3]); ?>" style="width: 50%;">
																				<input type="text" class="focus_comp_id_<?php echo $last_focus_id; ?> form-control m-bottom-10 data_name input-sm click_select focus_percent_val_pm" id="<?php echo $last_focus_id; ?>_other_pmx_<?php echo $new_id; ?>" name="<?php echo $last_focus_id; ?>_other_pm_<?php echo $new_id; ?>" placeholder="%" value="<?php echo $this->input->post($last_focus_id.'_other_pm_'.$other_data[3]); ?>" style="width: 50%;" onkeyup="check_split('focus_comp_id_<?php echo $last_focus_id; ?>','<?php echo $last_focus_id; ?>_other_pmx_<?php echo $new_id; ?>');">
																				<input type="text" disabled="disabled" readonly="readonly" value="" class="form-control input-sm focus_computed" id="focus_computed_other_<?php echo $new_id; ?>" style="display:none; width: 50%;">
																				<span class="input-group-addon pointer remove_custom_pm" onclick="remove_custom_pm(this);" id="rm_bttn_otr_<?php echo $new_id; ?>"><strong><i class="fa fa-trash-o"></i></strong></span>
																			</div>
																		</div>


																		<?php endif; ?>																	 

																	<?php endforeach; ?>

																<?php endif; ?>

																<div class="clearfix"></div>


															</div>


															<input type="hidden" name="form_type" id="form_type" class="form_type" value="1">

															<input type="submit" class="btn btn-primary btn-sm m-top-5 data_submit" name="data_submit" value="Save Data" />

														
													</div>

													<div class="col-md-4 col-sm-12  col-xs-12" id="">
														<div class="pad-right-5 pad-left-5">
															 
															
																<strong class="m-bottom-10 block">Monthly Breakdown</strong>
																<small class="block m-bottom-10"><em>Suggested values are provided. <?php  echo $this->session->flashdata("error_data_amount");  ?></em> <span class="pull-right">Sales: <strong>$<?php echo number_format($sales_focus_yearly,2); ?></strong> Ex-GST</span></small>

																<div class="row pad-0-imp no-m-imp monthly_breakdown">

																	<?php $months = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"); ?>

																	<?php $temp_total_past = 0; ?>
																	<?php for ($x=0; $x < 12; $x++): ?>
																		<?php if($monthly_split[$x] == 0): ?>
																			<?php $temp_total_past = $temp_total_past + $this->dashboard->_check_monthly_share_adjust($old_year,$monthly_split[$x],$months[$x]); ?>
																		<?php endif; ?>
																	<?php endfor; ?>				


																
																	
																	<div class="col-lg-6 col-xs-12" id="">	
																		<div class="input-group m-bottom-15 <?php if(form_error('data_amount')){ echo 'has-error has-feedback';} ?>">
																			<span class="input-group-addon" id="">Total</span>      		
																			<input type="text" class="form-control m-bottom-10 data_name number_format input-sm" name="data_amount" id="data_amount" placeholder="$ EX-GST" value="<?php echo $this->input->post('data_amount'); ?>">
																			<span class="input-group-addon toggle_percent_amount" id="mainForm-forecast_form"><strong><i class="fa fa-refresh"></i></strong></span> 
																		</div>
																	</div>

																	<div class="clearfix"></div>
																	

																	<?php for ($x=0; $x < 12; $x++): ?>
																		<div class="col-md-6 col-sm-6 col-xs-6 clearfix ">
																			<div class="input-group m-bottom-15 <?php if(form_error('month_'.$x)){ echo 'has-error has-feedback';} ?>">
																				<span class="input-group-addon"><?php echo $months[$x]; ?></span>      		
																				
																				<input type="text" class="form-control number_format input-sm number_format click_select focus_month_split" id="month_<?php echo $x; ?>"  onKeyUp="check_split('focus_month_split','month_<?php echo $x; ?>');" name="month_<?php echo $x; ?>" placeholder="%"  <?php if($x == 11){ echo 'readonly="readonly"'; } ?>  value="<?php echo $this->input->post('month_'.$x); ?>">
																				<input type="text" disabled="disabled" readonly="readonly" value="" class="form-control input-sm focus_computed"  id="focus_computed_month_<?php echo $x; ?>" style="display:none;" />

																				<span class="input-group-addon">
																					<strong style="<?php echo ($monthly_split[$x] == 0 ? 'color: red;' : '');  ?>">
																						<?php echo $this->dashboard->_check_monthly_share_adjust($old_year,$monthly_split[$x],$months[$x],$sales_focus_yearly,$temp_total_past); ?>%
																					</strong>
																				</span>   
																			</div>
																		</div>
																	<?php endfor; ?>

																</div>
														</div>
													</div> 

													

													</form>  

													<?php //echo "$old_year"; ?>


												</div>		

												<div id="tab_forecasts_area" class="tab_area" <?php if($tab_view != 'view'){ echo 'style="display:none;"';  } ?>>
													<div class="col-lg-5 col-sm-12 col-xs-12" id="">
														<strong class="m-bottom-10 block data_label">Saved Forecasts</strong>

														<table id="dataTable_noCustom" class="table table-striped table-bordered" cellspacing="0" width="100%">
														<thead> <tr> <th>Title</th> <th>Year</th> <th>Total</th></tr> </thead> 

															<tbody>

																<?php foreach ($saved_forecast->result_array() as $forecast): ?>
																	<tr>



<td><a href="<?php echo base_url(); ?>dashboard/sales_forecast/view_<?php echo $forecast['revenue_forecast_id']; ?>" id="" class="tooltip-enabled"  data-original-title="View Details"><?php echo $forecast['forecast_label']; ?></a><?php if($forecast['is_primary'] == 1): ?> <i class="fa fa-check-square tooltip-enabled"  data-original-title="Primary Forecast" style="color: #4cae4c;"></i><?php endif; ?>     <?php if($forecast['is_primary'] == 0): ?> <a href="./?delete_rfc=<?php echo $forecast['revenue_forecast_id']; ?>" class="btn btn-primary btn-xs btn_smc  btn-danger tooltip-enabled"  data-original-title="Delete"><i class="fa fa-trash"></i></a>  <a href="./?primary_rfc=<?php echo $forecast['revenue_forecast_id'].'_'.$forecast['year']; ?>" class="btn btn-primary btn-xs btn_smc tooltip-enabled"  data-original-title="Set to Primary Forecast"><i class="fa fa-check-square"></i></a><?php endif; ?>  <a href="<?php echo base_url(); ?>dashboard/sales_forecast/edit_<?php echo $forecast['revenue_forecast_id']; ?>" class="btn btn-info btn-xs btn_smc tooltip-enabled"  data-original-title="Edit"><i class="fa fa-pencil"></i></a></td>
																	

																		<td><?php echo $forecast['year']; ?></td>
																		<td>$<?php echo number_format($forecast['total'],2); ?></td>
																	</tr>
																<?php endforeach; ?>

															</tbody>
														</table>
													</div>


													<div class="col-lg-7 col-sm-12 col-xs-12" id="">

														<div class="clearfix">



															<div class="col-lg-9 col-sm-6 col-xs-12" id="">
																<div class="pad-left-5 pad-right-5">







																<?php if(isset($saved_forecast_item)): ?>
																<strong class="m-bottom-10 block data_label m-left-5">Forecast: <?php echo $saved_forecast_item['forecast_label'].' - '.$saved_forecast_item['year'].' at $'.number_format($saved_forecast_item['total'],2); ?> &nbsp;   <a href="<?php echo base_url(); ?>dashboard/sales_forecast/edit_<?php echo $saved_forecast_item['revenue_forecast_id']; ?>" class="pull-right pad-top-5 m-right-5"><i class="fa fa-pencil"></i> Update</a></strong>
													


																	<table class="table table-condensed m-botom-0">
																		<thead>
																		<tr><th>Name</th> <th>Percent</th> <th>Amount</th></tr>
																		</thead>
																		<tbody>
																			<?php $fcom = 0; $fpm = 0; $fgrp = 0; ?>

																			<?php foreach ($individual_forecast->result_array() as $indv_forecast):  $fpm = $indv_forecast['comp_id']; ?>

																				<?php if($indv_forecast['pm_id'] == 0 && $indv_forecast['other'] == ''): $fcom = 1; ?>
																					<?php 
																						$fcomPercent[$fpm] = $indv_forecast['forecast_percent'];
																						$fcomAmount[$fpm] =  $saved_forecast_item['total'] * ($indv_forecast['forecast_percent']/100);
																					?>
																				<?php else:  ?>
																					<?php if($fcom == 1){$fcom = 2;}?>
																				<?php endif; ?>

																				<?php if($fcom == 2): $fcom = 0; $fgrp = 0;?> 
																					<!-- <tr style="background-color: #f9f9f9;"><td colspan="3"></td></tr> -->
																				<?php endif; ?>

																				<?php if($fgrp != $fpm && $fcom == 0): $fgrp = $fpm;?> 
																					<tr style="background-color: rgb(239, 239, 239);">
																						<td><strong><?php echo $indv_forecast['company_name']; ?></strong></td><td><strong><?php echo $fcomPercent[$fgrp]; ?>%</strong></td><td><?php echo '<strong>'.number_format($fcomAmount[$fpm],2).'</strong>'; ?></td>
																					</tr>
																				<?php endif; ?>																				

																				<!-- for pms -->
																				<?php if($indv_forecast['pm_id'] > 0 && $indv_forecast['other'] == ''):    ?>
																					<tr> <td> <?php echo $indv_forecast['pm_name']; ?></td><td><strong><?php echo $indv_forecast['forecast_percent']; ?>%</strong></td><td><?php $amount = $fcomAmount[$fpm]*($indv_forecast['forecast_percent']/100);  echo number_format($amount,2); ?></td></tr>
																				<?php endif; ?>
																				<!-- for pms -->

																				<!-- for other -->
																				<?php if($indv_forecast['pm_id'] == 0 && $indv_forecast['other'] != ''): ?>
																					<tr> <td> <strong>Other - </strong> <?php echo $indv_forecast['other']; ?></td><td><strong><?php echo $indv_forecast['forecast_percent']; ?>%</strong></td><td><?php $amount = $fcomAmount[$fpm]*($indv_forecast['forecast_percent']/100);  echo number_format($amount,2); ?></td></tr>
																				<?php endif; ?>
																				<!-- for other -->

																			<?php endforeach; ?>
																		</tbody>
																	</table>
																<?php else: ?>



																<strong class="m-bottom-10 block data_label m-left-5">Forecast: </strong>
													


																	<table class="table table-condensed m-botom-0 table-striped">
																		<thead>
																		<tr><th>Name</th> <th>Percent</th> <th>Amount</th></tr>
																		</thead>
																		<tbody>

																		<?php for($i=0; $i < 10 ; $i++): ?>
																			<tr><td>&nbsp;</td><td></td><td></td></tr>



																		<?php endfor; ?>
																			
																		</tbody>
																	</table>

																<?php endif; ?>
																</div>

															</div>
															<div class="col-lg-3 col-sm-6 col-xs-12" id="">
																<?php if(isset($saved_forecast_item)): ?>
																<table class="table table-condensed m-botom-0">
																	<thead>
																		<tr><th>Month</th> <th>Percent</th></tr>
																	</thead>
																	<tbody>
																		<tr> <td>Jan </td> <td><strong><?php echo $saved_forecast_item['forecast_jan']; ?>%</strong></td> </tr>
																		<tr> <td>Feb </td> <td><strong><?php echo $saved_forecast_item['forecast_feb']; ?>%</strong></td> </tr> 
																		<tr> <td>Mar </td> <td><strong><?php echo $saved_forecast_item['forecast_mar']; ?>%</strong></td> </tr> 
																		<tr> <td>Apr </td> <td><strong><?php echo $saved_forecast_item['forecast_apr']; ?>%</strong></td> </tr> 
																		<tr> <td>May </td> <td><strong><?php echo $saved_forecast_item['forecast_may']; ?>%</strong></td> </tr> 
																		<tr> <td>Jun </td> <td><strong><?php echo $saved_forecast_item['forecast_jun']; ?>%</strong></td> </tr> 
																		<tr> <td>Jul </td> <td><strong><?php echo $saved_forecast_item['forecast_jul']; ?>%</strong></td> </tr> 
																		<tr> <td>Aug </td> <td><strong><?php echo $saved_forecast_item['forecast_aug']; ?>%</strong></td> </tr> 
																		<tr> <td>Sep </td> <td><strong><?php echo $saved_forecast_item['forecast_sep']; ?>%</strong></td> </tr> 
																		<tr> <td>Oct </td> <td><strong><?php echo $saved_forecast_item['forecast_oct']; ?>%</strong></td> </tr> 
																		<tr> <td>Nov </td> <td><strong><?php echo $saved_forecast_item['forecast_nov']; ?>%</strong></td> </tr> 
																		<tr> <td>Dec </td> <td><strong><?php echo $saved_forecast_item['forecast_dec']; ?>%</strong></td> </tr>
																	</tbody>
																</table>
															<?php else: ?>
																<table class="table table-condensed m-botom-0">
																	<thead>
																		<tr><th>Month</th> <th>Percent</th></tr>
																	</thead>
																	<tbody>
																		<tr> <td>Jan </td> <td><strong>%</strong></td> </tr>
																		<tr> <td>Feb </td> <td><strong>%</strong></td> </tr> 
																		<tr> <td>Mar </td> <td><strong>%</strong></td> </tr> 
																		<tr> <td>Apr </td> <td><strong>%</strong></td> </tr> 
																		<tr> <td>May </td> <td><strong>%</strong></td> </tr> 
																		<tr> <td>Jun </td> <td><strong>%</strong></td> </tr> 
																		<tr> <td>Jul </td> <td><strong>%</strong></td> </tr> 
																		<tr> <td>Aug </td> <td><strong>%</strong></td> </tr> 
																		<tr> <td>Sep </td> <td><strong>%</strong></td> </tr> 
																		<tr> <td>Oct </td> <td><strong>%</strong></td> </tr> 
																		<tr> <td>Nov </td> <td><strong>%</strong></td> </tr> 
																		<tr> <td>Dec </td> <td><strong>%</strong></td> </tr>
																	</tbody>
																</table>


															<?php endif; ?>
															</div>

														</div>
 

													</div>



												</div>
											</div>

										</div>
									</div>
								</div>



















								<?php if($tab_view == 'edit'): ?>


								<div class="widget wid-type-b widg-head-styled m-top-20">
									<div class="widg-head box-widg-head pad-5"><i class="fa fa-pencil"></i>
										<strongs>Update Forecast</strong>
									</div>


								
									<div class="box-area clearfix">
										<div class="widg-content clearfix">
											<div class="">
												<div id="" class="clearfix row pad-0-imp no-m-imp" >
													<form method="post" id="update_forecast_form" class="m-top-5 m-bottom-5 clearfix" action="">

													<div class="col-md-8 col-sm-12 col-xs-12" id="">
														<strong class="m-bottom-10 block data_label">Update Data</strong> <small class="block m-bottom-10"><em>Note: The sales from calendar year <strong>"<u><?php echo $old_year; ?></u>"</strong> is being used.</em></small>
														<script type="text/javascript"> //$('select#data_year').val('<?php if(@$this->session->flashdata("data_year")){ echo $this->session->flashdata("data_year"); } ?>');</script>

															<div class="clearfix row pad-0-imp no-m-imp">

																<div class="col-lg-4 col-xs-12" id="">	
																	<div class="input-group m-bottom-15 <?php if(form_error('data_label_edt')){ echo 'has-error has-feedback';} ?>">
																		<span class="input-group-addon" id="">Label</span>      		
																		<input type="text" class="form-control m-bottom-10 data_label input-sm" name="data_label_edt" id="data_label" placeholder="<?php echo $saved_forecast_item['forecast_label']; ?>" value="<?php if($this->input->post('data_label_edt')){ echo $this->input->post('data_label_edt'); }else{  echo $saved_forecast_item['forecast_label']; } ?>">
																	</div>
																</div>

																<div class="clearfix"></div>

																<?php $fcom = 0; $fpm = 0; $fgrp = 0; ?>


																<?php foreach ($individual_forecast->result_array() as $indv_forecast):  $fpm = $indv_forecast['comp_id']; ?>

																	<?php if($indv_forecast['pm_id'] == 0 && $indv_forecast['other'] == ''): $fcom = 1; ?>
																		<div class="col-lg-5 col-md-6 col-xs-6" id="">	
																			<div class="input-group m-bottom-10 <?php if(form_error('focus_id_'.$indv_forecast['comp_id'])){ echo 'has-error has-feedback';} ?>">
																				<span class="input-group-addon" id=""><?php echo $indv_forecast['company_name']; ?></span> 
																				<input type="text" class="form-control m-bottom-10 data_name input-sm number_format focus_comp_split_edt click_select focus_percent_val" id="focus_idedt_<?php echo $indv_forecast['comp_id']; ?>" onKeyUp="check_split('focus_comp_split_edt','focus_idedt_<?php echo $indv_forecast['comp_id']; ?>');" name="focus_idedt_<?php echo $indv_forecast['comp_id']; ?>" placeholder="%" value="<?php if($this->input->post('focus_idedt_'.$indv_forecast['comp_id'])){  echo $this->input->post('focus_idedt_'.$indv_forecast['comp_id']);    }else{ echo $indv_forecast['forecast_percent'];  }?>">
																				<input type="text" disabled="disabled" readonly="readonly" value="" class="form-control input-sm focus_computed"  id="focus_computedEdt_<?php echo $indv_forecast['comp_id']; ?>" style="display:none;" />
																				<span class="input-group-addon">
																					<strong id="focus_percentEdt_<?php echo $indv_forecast['comp_id']; ?>"><?php echo $this->dashboard->_get_focus_splits($old_year,$indv_forecast['comp_id']); ?>%</strong>
																				</span>  	
																			</div>
																		</div>
																	<?php else:  ?>
																		<?php if($fcom == 1){$fcom = 2;}?>
																	<?php endif; ?>

																	<?php if($fcom == 2): $fcom = 0; $fgrp = 0;?> 
																		<!-- <tr style="background-color: #f9f9f9;"><td colspan="3"></td></tr> -->
																	<?php endif; ?>

																	<?php if($fgrp != $fpm && $fcom == 0): $fgrp = $fpm;?> 
																		<div class="clearfix"></div>

																		<hr class="block m-bottom-5 m-top-5">

																		<strong class="m-bottom-10 block data_label"><?php echo $focus_comp_list[$fgrp]; ?></strong>
																	<?php endif; ?>



																	<?php if($indv_forecast['pm_id'] > 0 && $indv_forecast['other'] == ''):    ?>











<div class="col-lg-4 col-md-6 col-xs-6" id="">	
<div class="input-group m-bottom-10 <?php if(form_error($indv_forecast['comp_id'].'_comp_idEdt_'.$indv_forecast['revenue_forecast_individual_id'])){ echo 'has-error has-feedback';} ?>">
<span class="input-group-addon" id=""><?php echo $indv_forecast['pm_name']; ?></span>
<input type="text" <?php if($indv_forecast['pm_id'] == 29 ){ echo 'readonly="readonly"'; } ?> class="form-control m-bottom-10 data_name input-sm number_format click_select  focus_comp_idEdt_<?php echo $indv_forecast['comp_id']; ?> focus_percent_val_pm_edt" id="<?php echo $indv_forecast['comp_id']; ?>_comp_idEdt_<?php echo $indv_forecast['revenue_forecast_individual_id']; ?>"  onKeyUp="check_split('focus_comp_idEdt_<?php echo $indv_forecast['comp_id']; ?>','<?php echo $indv_forecast['comp_id']; ?>_comp_idEdt_<?php echo $indv_forecast['revenue_forecast_individual_id']; ?>');"   name="<?php echo $indv_forecast['comp_id']; ?>_comp_idEdt_<?php echo $indv_forecast['revenue_forecast_individual_id']; ?>" placeholder="<?php echo $indv_forecast['forecast_percent']; ?>" value="<?php if($this->input->post($indv_forecast['comp_id'].'_comp_idEdt_'.$indv_forecast['revenue_forecast_individual_id'])){ echo $this->input->post($indv_forecast['comp_id'].'_comp_idEdt_'.$indv_forecast['revenue_forecast_individual_id']); }else{ echo $indv_forecast['forecast_percent'];}   ?>">
<input type="text" disabled="disabled" readonly="readonly" value="" class="form-control input-sm focus_computed"  id="focus_computed_<?php echo $indv_forecast['comp_id']; ?>" style="display:none;" />
<span class="input-group-addon" id=""><strong><?php echo $this->dashboard->_get_focus_pm_splits($old_year,$indv_forecast['comp_id'],$indv_forecast['pm_id']); ?>%</strong></span> 
</div>
</div>


<!-- 
																		<tr> <td> <?php echo $indv_forecast['pm_name']; ?></td><td><strong><?php echo $indv_forecast['forecast_percent']; ?>%</strong></td><td><?php $amount = $saved_forecast_item['total']*($indv_forecast['forecast_percent']/100);  echo number_format($amount,2); ?></td></tr>
																	 -->


																	<?php endif; ?>





																				<?php if($indv_forecast['pm_id'] == 0 && $indv_forecast['other'] != ''): ?>


<div class="col-lg-4 col-md-6 col-xs-6" id="">	
<div class="input-group m-bottom-10 <?php if(form_error($indv_forecast['comp_id'].'_comp_idEdt_'.$indv_forecast['revenue_forecast_individual_id'])){ echo 'has-error has-feedback';} ?>">
<span class="input-group-addon" id=""><?php echo $indv_forecast['other']; ?></span>
<input type="text" class="form-control m-bottom-10 data_name input-sm number_format click_select  focus_comp_idEdt_<?php echo $indv_forecast['comp_id']; ?> focus_percent_val_pm_edt" id="<?php echo $indv_forecast['comp_id']; ?>_comp_idEdt_<?php echo $indv_forecast['revenue_forecast_individual_id']; ?>"  onKeyUp="check_split('focus_comp_idEdt_<?php echo $indv_forecast['comp_id']; ?>','<?php echo $indv_forecast['comp_id']; ?>_comp_idEdt_<?php echo $indv_forecast['revenue_forecast_individual_id']; ?>');"   name="<?php echo $indv_forecast['comp_id']; ?>_comp_idEdt_<?php echo $indv_forecast['revenue_forecast_individual_id']; ?>" placeholder="<?php echo $indv_forecast['forecast_percent']; ?>" value="<?php if($this->input->post($indv_forecast['comp_id'].'_comp_idEdt_'.$indv_forecast['revenue_forecast_individual_id'])){ echo $this->input->post($indv_forecast['comp_id'].'_comp_idEdt_'.$indv_forecast['revenue_forecast_individual_id']); }else{ echo $indv_forecast['forecast_percent'];}   ?>">
<input type="text" disabled="disabled" readonly="readonly" value="" class="form-control input-sm focus_computed"  id="focus_computed_<?php echo $indv_forecast['comp_id']; ?>" style="display:none;" /> 
</div>
</div>

																					<!-- 
																					<tr> <td> <strong>Other - </strong> <?php echo $indv_forecast['other']; ?></td><td><strong><?php echo $indv_forecast['forecast_percent']; ?>%</strong></td><td><?php $amount = $saved_forecast_item['total']*($indv_forecast['forecast_percent']/100);  echo number_format($amount,2); ?></td></tr>
																				 -->


																				<?php endif; ?>




																<?php endforeach; ?>


																<!-- focus pms and other -->

																<div class="clearfix"></div>


															</div>


															<input type="hidden" name="form_type" id="form_type" class="form_type" value="2">
															<input type="hidden" name="forecast_id" id="forecast_id" class="forecast_id" value="<?php echo $forecast_id; ?>">





															<input type="submit" class="btn btn-success btn-sm m-top-5 data_update" name="data_update" value="Update Data" />

														
													</div>

													<div class="col-md-4 col-sm-12  col-xs-12" id="">
														<div class="pad-right-5 pad-left-5">
															 
															
																<strong class="m-bottom-10 block">Monthly Breakdown</strong>
																<small class="block m-bottom-10"><em>Suggested values are provided. <?php  echo $this->session->flashdata("error_data_amount");  ?></em> <span class="pull-right">Sales: <strong>$<?php echo number_format($sales_focus_yearly,2); ?></strong> Ex-GST</span></small>

																<div class="row pad-0-imp no-m-imp monthly_breakdown">


																	<?php $temp_total_past = 0; ?>
																	<?php for ($x=0; $x < 12; $x++): ?>
																		<?php if($monthly_split[$x] == 0): ?>
																			<?php $temp_total_past = $temp_total_past + $this->dashboard->_check_monthly_share_adjust($old_year,$monthly_split[$x],$months[$x]); ?>
																		<?php endif; ?>
																	<?php endfor; ?>				


																
																	
																	<div class="col-lg-6 col-xs-12" id="">	
																		<div class="input-group m-bottom-15 <?php if(form_error('data_amount_edt')){ echo 'has-error has-feedback';} ?>">
																			<span class="input-group-addon" id="">Total</span>      		
																			<input type="text" class="form-control m-bottom-10 data_name number_format input-sm" name="data_amount_edt" id="data_amount" placeholder="<?php echo number_format($saved_forecast_item['total'],2); ?>" value="<?php if($this->input->post('data_amount_edt')){ echo $this->input->post('data_amount_edt'); }else{ echo number_format($saved_forecast_item['total'],2); } ?>">
																			<span class="input-group-addon toggle_percent_amount" id="mainForm-update_forecast_form"><strong><i class="fa fa-refresh"></i></strong></span> 
																		</div>
																	</div>

																	<div class="clearfix"></div>
																	

																	<?php for ($x=0; $x < 12; $x++): ?>
																		<div class="col-md-6 col-sm-6 col-xs-6 clearfix ">
																			<div class="input-group m-bottom-15 <?php if(form_error('month_edt_'.$x)){ echo 'has-error has-feedback';} ?>">
																				<span class="input-group-addon"><?php echo $months[$x]; ?></span>
																				<?php $mnth_lbl = strtolower($months[$x]);  ?>																				
																				<input type="text" class="form-control number_format input-sm number_format click_select focus_month_split_edt" id="month_edt_<?php echo $x; ?>"  onKeyUp="check_split('focus_month_split_edt','month_edt_<?php echo $x; ?>');" name="month_edt_<?php echo $x; ?>" placeholder="<?php echo $saved_forecast_item['forecast_'.$mnth_lbl]; ?>%"  <?php if($x == 11){ echo 'readonly="readonly"'; } ?>  value="<?php if($this->input->post('month_edt_'.$x)){ echo $this->input->post('month_edt_'.$x); }else{ echo $saved_forecast_item['forecast_'.$mnth_lbl]; } ; ?>">
																				<input type="text" disabled="disabled" readonly="readonly" value="" class="form-control input-sm focus_computed"  id="focus_computed_month_edt_<?php echo $x; ?>" style="display:none;" />
																				<span class="input-group-addon">
																					<strong style="<?php echo ($monthly_split[$x] == 0 ? 'color: red;' : '');  ?>">
																						<?php echo $this->dashboard->_check_monthly_share_adjust($old_year,$monthly_split[$x],$months[$x],$sales_focus_yearly,$temp_total_past); ?>%
																					</strong>
																				</span>
																			</div>
																		</div>
																	<?php endfor; ?>

																</div>
														</div>
													</div> 

													

													</form>  

													<?php //echo "$old_year"; ?>


												</div>		

											</div>

										</div>
									</div>
								</div>


							<?php endif; ?>
















								
							</div>

						<?php endif; ?>

						<!-- ************************ -->

<!--


						<div class="col-md-3 col-sm-6 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-d small-widget">
								<div class="box-area clearfix row">
									<div class="widg-icon-inside col-xs-3"><i class="fa fa-angle-double-down fa-3x text-center"></i></div>
									<div class="widg-content col-xs-9 clearfix">
										<div class="pad-right-15 m-right-5">
											<p>This Month's Sales</p>
											<p class="value">$<strong>150,000.00</strong></p>
											<div class="progress m-bottom-3 m-top-3 slim tooltip-enabled" title="15.95% Sales Drop">
												<div class="progress-bar progress-bar-danger" style="width: 15.95%"></div>
											</div>
											<p>Forecast Nov 2014: $<strong>250,200.00</strong></p>	
										</div>	

									</div>
								</div>
							</div>
						</div>

						<div class="col-md-3 col-sm-6 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-b small-widget">
								<div class="box-area clearfix row">
									<div class="widg-icon-inside col-xs-3"><i class="fa fa-angle-double-up text-center fa-3x"></i></div>
									<div class="widg-content col-xs-9 clearfix">
										<div class="pad-right-15 m-right-5">
											<p>Last Month's Sales</p>
											<p class="value">$<strong>150,000.00</strong></p>
											<div class="progress m-bottom-3 m-top-3 slim tooltip-enabled" title="25% Sales Increase">
												<div class="progress-bar progress-bar-success" style="width: 25%"></div>
											</div>
											<p>Forecast Oct 2014: $<strong>250,200.00</strong></p>	
										</div>									
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-3 col-sm-6 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-f small-widget">
								<div class="box-area clearfix row">
									<div class="widg-icon-inside col-xs-3"><i class="fa fa-map-marker text-center fa-3x"></i></div>
									<div class="widg-content col-xs-9 clearfix">
										<div class="pad-right-15 m-right-5">
											<p>Fully Invoiced Projects<span class="badges pull-right m-right-10"></span> </p>
											<p class="value">45</p>
											<div class="progress m-bottom-3 m-top-3 slim tooltip-enabled" title="45% Fully Invoiced Projects">
												<div class="progress-bar" style="width: 45%"></div>
											</div>
											<p>All Projects: 100</p>	
										</div>									
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-3 col-sm-6 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-0 small-widget">
								<div class="box-area clearfix row">
									<div class="widg-icon-inside col-xs-3"><i class="fa fa-tasks text-center fa-3x"></i></div>
									<div class="widg-content col-xs-9 clearfix">
										<div class="pad-right-15 m-right-5">
											<p>Un-Invoiced Projects<span class="badges pull-right m-right-10"></span> </p>
											<p class="value">15</p>
											<div class="progress m-bottom-3 m-top-3 slim tooltip-enabled" title="15% Un-Invoiced Projects">
												<div class="progress-bar progress-bar-warning" style="width: 15%"></div>
											</div>
											<p>All Projects: 100</p>										
										</div>
									</div>
								</div>
							</div>
						</div>
						

				-->
						<div class="clearfix"></div>
						<!-- ************************ -->


						<!-- ************************ -->						
						
						<div class="col-md-12 col-sm-12 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-0 widg-head-styled">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide hidden"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head box-widg-head pad-5 clearfix">
									<strong class="pointer collapsed pull-left " data-toggle="collapse" data-target=".data_sales_chart">Sales</strong>
									<div class="pull-left" style="margin: -5px 0px -8px;">
										<select class="form-control sf_chart_dateSelection" id="calendar_year" style="height: 31px;    border-radius: 0;    border: none;    font-weight: bold;">
											
											<?php $year=2015; for($i=0; $i < 10; $i++){	
												if( $year+$i < date('Y')+1  ){
													echo '<option value="'.($year+$i).'">'.($year+$i).'</option>';
												}
											}?>

											<script type="text/javascript">$('select#calendar_year').val(<?php echo $old_year+1; ?>);</script>											
										</select>
									</div>

									<div class="pull-right" style="margin: -5px -10px -8px;">
										<select class="form-control sf_chart_dateSelection" id="calendar_view" style="height: 31px;    border-radius: 0;    border: none;    font-weight: bold;">
											<option value="1" selected="selected" >Financial Year</option>
											<option value="2" >Calendar Year</option>
											<script type="text/javascript">$('select#calendar_view').val(<?php echo $calendar_view; ?>);</script>											
										</select>
									</div>
								</div>

								<div class="box-area clearfix row pad-right-10 pad-left-10 data_sales_chart collapse in">
										<div class="loading_chart" style="height: 320px;    text-align: center;    padding: 100px 53px;    color: #ccc;"><i class="fa fa-spin fa-refresh fa-4x"></i></div>
										<div class id="job_book_area">
											<div id="chart_a"></div>
										</div>
										<hr class="block m-bottom-10 m-top-5">

										<div class="m-left-10 m-bottom-10">
										<button class="btn btn-warning btn-sm" id="visible_forecast" onclick="funcc(this)" ><i class="fa fa-exchange"></i> Outstanding</button>	
										<button class="btn btn-primary btn-sm m-left-5" onclick="funca()" ><i class="fa fa-exchange"></i> Project Manager Shares</button>
										<button class="btn btn-sm m-left-5" onclick="funcb()" ><i class="fa fa-exchange"></i> Focus Sales</button>
										<button class="btn btn-info btn-sm pull-right m-right-10" onclick='print_job_book();'><i class="fa fa-print"></i> Print</button>
											

										</div>
										
								</div>
							</div>
						</div>

						
						<div class="clearfix"></div>


						<!-- ************************ -->







						<!-- ************************ -->						
						
						<div class="col-md-12 col-sm-12 col-xs-12 box-widget pad-10 hide">
							<div class="widget wid-type-0 widg-head-styled">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide hidden"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head box-widg-head pad-5 clearfix">
									<strong>Donut</strong>
								</div>

								<div class="box-area clearfix row pad-right-10 pad-left-10">									
									<div class="widg-content col-md-6 col-sm-12 col-xs-12 clearfix">
										<div class="loading_chart" style="height: 320px;    text-align: center;    padding: 100px 53px;    color: #ccc;"><i class="fa fa-spin fa-refresh fa-4x"></i></div>								

										<div class="clearfix row pad-0-imp no-m-imp">
											<div class="col-md-12 col-sm-6 col-xs-12" id="donut_a"></div> 
										</div>  
							
										<hr class="block m-bottom-10 m-top-5">
										<p class="text-center"><strong>WA</strong>: $86,584,365.00</p>
									</div>
									<div class="widg-content col-md-6 col-sm-12 col-xs-12 clearfix">
										<div class="loading_chart" style="height: 320px;    text-align: center;    padding: 100px 53px;    color: #ccc;"><i class="fa fa-spin fa-refresh fa-4x"></i></div>								

										<div class="clearfix row pad-0-imp no-m-imp">
											<div class="col-md-12 col-sm-6 col-xs-12" id="donut_b"></div>   
										</div>  
							
										<hr class="block m-bottom-10 m-top-5">
										<p class="text-center"><strong>NSW</strong>: $10,256,544.00</p>										
									</div>
								</div>



							</div>
						</div>

						
						<div class="clearfix"></div>


						<!-- ************************ -->









						<!-- ************************ -->


						<div class="col-md-6 col-sm-12 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-a widg-head-styled">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide hidden"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head fill box-widg-head pad-5"><strong>Focus WA Invoices and Sales</strong></div>
								<div class="box-area clearfix">
									<div class="widg-content clearfix">
										<div class="loading_chart" style="height: 400px;    text-align: center;    padding: 100px 53px;    color: #ccc;"><i class="fa fa-spin fa-refresh fa-4x"></i></div>
										<div id="chart_b"></div>	
										<hr class="hide block m-bottom-10 m-top-5">
										<button class="hide btn btn-warning btn-sm" id="visible_forecast_b" onclick="funcd(this)" ><i class="fa fa-exchange"></i> Show Individual</button>	

										<button class="hide btn btn-info btn-sm pull-right" onclick='print_job_book();'><i class="fa fa-print"></i> Print</button>										
									</div>
								</div>
							</div>
						</div>


						<!-- ************************ -->


						<div class="col-md-6 col-sm-12 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-d widg-head-styled">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide hidden"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head fill box-widg-head pad-5"><strong>Focus NSW Invoices and Sales</strong></div>
								<div class="box-area clearfix">
									<div class="widg-content clearfix">
										<div class="loading_chart" style="height: 400px;    text-align: center;    padding: 100px 53px;    color: #ccc;"><i class="fa fa-spin fa-refresh fa-4x"></i></div>
										<div id="chart_c"></div>	
										<hr class="hide block m-bottom-10 m-top-5">
										<button class="hide btn btn-warning btn-sm" id="visible_forecast_b" onclick="funcd(this)" ><i class="fa fa-exchange"></i> Show Individual</button>	

										<button class="hide btn btn-info btn-sm pull-right" onclick='print_job_book();'><i class="fa fa-print"></i> Print</button>										
									</div>
								</div>
							</div>
						</div>











						<!-- ************************ -->


						<div class="col-md-7 col-sm-12 col-xs-12 box-widget pad-10 hide">
							<div class="widget wid-type-c widg-head-styled">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide hidden"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head fill box-widg-head pad-5"><strong>Invoices and Sales</strong></div>
								<div class="box-area clearfix">
									<div class="widg-content clearfix">
										<div class="loading_chart" style="height: 400px;    text-align: center;    padding: 100px 53px;    color: #ccc;"><i class="fa fa-spin fa-refresh fa-4x"></i></div>
										<div id="chart_b"></div>	
										<hr class="block m-bottom-10 m-top-5">
										<button class="btn btn-warning btn-sm" id="visible_forecast_b" onclick="funcd(this)" ><i class="fa fa-exchange"></i> Show Individual</button>	

										<button class="btn btn-info btn-sm pull-right" onclick='print_job_book();'><i class="fa fa-print"></i> Print</button>										
									</div>
								</div>
							</div>
						</div>


						<div class="col-md-5 col-sm-12 col-xs-12 box-widget pad-10 hide">
							<div class="widget wid-type-b widg-head-styled">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide hidden"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head fill box-widg-head pad-5"><strong>Sales Standing</strong> <span class="badges pull-right m-right-10"> <span class="tabs active" id="tab_wa">Focus WA</span>  <span class="tabs" id="tab_nsw">Focus NSW</span> </span></div>
								<div class="box-area clearfix">
									<div class="row pad-10">

										<div class="tab_container">
											<div id="tab_wa_area" class="tab_area active">
												
												<div class="wid-type-b col-md-4 col-sm-4 col-xs-4 text-center">
													<div class="widg-content pad-left-5  pad-right-5 clearfix pad-15">
														<p class="inc_val"><strong><i class="fa fa-caret-up"></i> 15%</strong></p>
														<p class=""><strong>$35,210.43</strong></p>
														<div class="progress m-bottom-5 m-top-5">Test
															<div class="progress-bar progress-bar-success" style="width: 15%"></div> 
														</div>
														<p>January</p>
													</div>
												</div>


												<div class="wid-type-b col-md-4 col-sm-4 col-xs-4 text-center">
													<div class="widg-content pad-left-5  pad-right-5 clearfix pad-15">
														<p class="inc_val"><strong><i class="fa fa-caret-up"></i> 5%</strong></p>
														<p><strong>$35,210.43</strong></p>
														<div class="progress m-bottom-5 m-top-5">
															<div class="progress-bar progress-bar-success" style="width: 5%"></div>
														</div>
														<p>February</p>
													</div>
												</div>


												<div class="wid-type-d col-md-4 col-sm-4 col-xs-4 text-center">
													<div class="widg-content pad-left-5  pad-right-5 clearfix pad-15">
														<p class="inc_val"><strong><i class="fa fa-caret-down"></i> 10%</strong></p>
														<p><strong>$35,210.43</strong></p>
														<div class="progress m-bottom-5 m-top-5">
															<div class="progress-bar progress-bar-danger" style="width: 10%"></div>
														</div>
														<p>March</p>
													</div>
												</div>


												<div class="wid-type-d col-md-4 col-sm-4 col-xs-4 text-center">
													<div class="widg-content pad-left-5  pad-right-5 clearfix pad-15">
														<p class="inc_val"><strong><i class="fa fa-caret-down"></i> 5%</strong></p>
														<p><strong>$35,210.43</strong></p>
														<div class="progress m-bottom-5 m-top-5">
															<div class="progress-bar progress-bar-danger" style="width: 5%"></div>
														</div>
														<p>April</p>
													</div>
												</div>


												<div class="wid-type-d col-md-4 col-sm-4 col-xs-4 text-center">
													<div class="widg-content pad-left-5  pad-right-5 clearfix pad-15">
														<p class="inc_val"><strong><i class="fa fa-caret-down"></i> 12%</strong></p>
														<p><strong>$35,210.43</strong></p>
														<div class="progress m-bottom-5 m-top-5">
															<div class="progress-bar progress-bar-danger" style="width: 12%"></div>
														</div>
														<p>May</p>
													</div>
												</div>


												<div class="wid-type-d col-md-4 col-sm-4 col-xs-4 text-center">
													<div class="widg-content pad-left-5  pad-right-5 clearfix pad-15">
														<p class="inc_val"><strong><i class="fa fa-caret-down"></i> 7%</strong></p>
														<p><strong>$35,210.43</strong></p>
														<div class="progress m-bottom-5 m-top-5">
															<div class="progress-bar progress-bar-danger" style="width: 7%"></div>
														</div>
														<p>June</p>
													</div>
												</div>


												<div class="wid-type-b col-md-4 col-sm-4 col-xs-4 text-center">
													<div class="widg-content pad-left-5  pad-right-5 clearfix pad-15">
														<p class="inc_val"><strong><i class="fa fa-caret-up"></i> 17%</strong></p>
														<p><strong>$35,210.43</strong></p>
														<div class="progress m-bottom-5 m-top-5">
															<div class="progress-bar progress-bar-success" style="width: 17%"></div>
														</div>
														<p>July</p>
													</div>
												</div>


												<div class="wid-type-b col-md-4 col-sm-4 col-xs-4 text-center">
													<div class="widg-content pad-left-5  pad-right-5 clearfix pad-15">
														<p class="inc_val"><strong><i class="fa fa-caret-up"></i> 10%</strong></p>
														<p><strong>$35,210.43</strong></p>
														<div class="progress m-bottom-5 m-top-5">
															<div class="progress-bar progress-bar-success" style="width: 10%"></div>
														</div>
														<p>August</p>
													</div>
												</div>


												<div class="wid-type-d col-md-4 col-sm-4 col-xs-4 text-center">
													<div class="widg-content pad-left-5  pad-right-5 clearfix pad-15">
														<p class="inc_val"><strong><i class="fa fa-caret-down"></i> 10%</strong></p>
														<p><strong>$35,210.43</strong></p>
														<div class="progress m-bottom-5 m-top-5">
															<div class="progress-bar progress-bar-danger" style="width: 10%"></div>
														</div>
														<p>September</p>
													</div>
												</div>



												<div class="wid-type-d col-md-4 col-sm-4 col-xs-4 text-center">
													<div class="widg-content pad-left-5  pad-right-5 clearfix pad-15">
														<p class="inc_val"><strong><i class="fa fa-caret-down"></i> 10%</strong></p>
														<p><strong>$35,210.43</strong></p>
														<div class="progress m-bottom-5 m-top-5">
															<div class="progress-bar progress-bar-danger" style="width: 10%"></div>
														</div>
														<p>October</p>
													</div>
												</div>



												<div class="wid-type-b col-md-4 col-sm-4 col-xs-4 text-center">
													<div class="widg-content pad-left-5  pad-right-5 clearfix pad-15">
														<p class="inc_val"><strong><i class="fa fa-caret-up"></i> 10%</strong></p>
														<p><strong>$35,210.43</strong></p>
														<div class="progress m-bottom-5 m-top-5">
															<div class="progress-bar progress-bar-success" style="width: 10%"></div>
														</div>
														<p>November</p>
													</div>
												</div>



												<div class="wid-type-b col-md-4 col-sm-4 col-xs-4 text-center">
													<div class="widg-content pad-left-5  pad-right-5 clearfix pad-15">
														<p class="inc_val"><strong><i class="fa fa-caret-up"></i> 10%</strong></p>
														<p><strong>$35,210.43</strong></p>
														<div class="progress m-bottom-5 m-top-5">
															<div class="progress-bar progress-bar-success" style="width: 10%"></div>
														</div>
														<p>December</p>
													</div>
												</div>

											</div>

											<div id="tab_nsw_area" class="tab_area" style="display:none;">
												

												<div class="wid-type-b col-md-4 col-sm-4 col-xs-4 text-center">
													<div class="widg-content pad-left-5  pad-right-5 clearfix pad-15">
														<p class="inc_val"><strong><i class="fa fa-caret-up"></i> 15%</strong></p>
														<p class=""><strong>$35,210.43</strong></p>
														<div class="progress m-bottom-5 m-top-5">Test
															<div class="progress-bar progress-bar-success" style="width: 15%"></div> 
														</div>
														<p>January</p>
													</div>
												</div>


												<div class="wid-type-b col-md-4 col-sm-4 col-xs-4 text-center">
													<div class="widg-content pad-left-5  pad-right-5 clearfix pad-15">
														<p class="inc_val"><strong><i class="fa fa-caret-up"></i> 5%</strong></p>
														<p><strong>$35,210.43</strong></p>
														<div class="progress m-bottom-5 m-top-5">
															<div class="progress-bar progress-bar-success" style="width: 5%"></div>
														</div>
														<p>February</p>
													</div>
												</div>


												<div class="wid-type-d col-md-4 col-sm-4 col-xs-4 text-center">
													<div class="widg-content pad-left-5  pad-right-5 clearfix pad-15">
														<p class="inc_val"><strong><i class="fa fa-caret-down"></i> 10%</strong></p>
														<p><strong>$35,210.43</strong></p>
														<div class="progress m-bottom-5 m-top-5">
															<div class="progress-bar progress-bar-danger" style="width: 10%"></div>
														</div>
														<p>March</p>
													</div>
												</div>


												<div class="wid-type-d col-md-4 col-sm-4 col-xs-4 text-center">
													<div class="widg-content pad-left-5  pad-right-5 clearfix pad-15">
														<p class="inc_val"><strong><i class="fa fa-caret-down"></i> 5%</strong></p>
														<p><strong>$35,210.43</strong></p>
														<div class="progress m-bottom-5 m-top-5">
															<div class="progress-bar progress-bar-danger" style="width: 5%"></div>
														</div>
														<p>April</p>
													</div>
												</div>


												<div class="wid-type-d col-md-4 col-sm-4 col-xs-4 text-center">
													<div class="widg-content pad-left-5  pad-right-5 clearfix pad-15">
														<p class="inc_val"><strong><i class="fa fa-caret-down"></i> 12%</strong></p>
														<p><strong>$35,210.43</strong></p>
														<div class="progress m-bottom-5 m-top-5">
															<div class="progress-bar progress-bar-danger" style="width: 12%"></div>
														</div>
														<p>May</p>
													</div>
												</div>


											</div>
											

										</div>

										



									</div>
								</div>
							</div>
						</div>

						<div class="clearfix"></div>

						<!-- ************************ -->
						 
					</div>

				</div>				
			</div>
		</div>
	</div>
</div>



<?php $this->load->view('assets/logout-modal'); ?>
<?php $this->bulletin_board->list_latest_post(); ?>

<script type='text/javascript' src='http://d3js.org/d3.v3.min.js'></script>
<script type='text/javascript' src="http://rawgit.com/masayuki0812/c3/master/c3.js"></script>
<link rel="stylesheet" type="text/css" href="http://rawgit.com/masayuki0812/c3/master/c3.css">



 <script>

     var chart = c3.generate({
      size: {
        height: 500
      },data: {
        x : 'x',
        columns: [
          ['x',

          <?php 

          for($i=0; $i < 12 ; $i++){	
          	$alternator = $calendar_view;
          	$counter = $i;

          	if($alternator == 1){
          		$counter = $counter + 6;
          	}

          	if($alternator == 1 && $counter > 11){
          		$counter = $counter - 12;
          	}

          	$month_index = $months[$counter];
          	echo "'".$month_index."',";
          }





          ?> ],



['Focus WA',

<?php

for($i=0; $i < 12 ; $i++){	
	$alternator = $calendar_view;
	$counter = $i;

	if($alternator == 1){
		$counter = $counter + 6;
	}

	if($alternator == 1 && $counter > 11){
		$counter = $counter - 12;
	}

	$month_index = 'rev_'.strtolower($months[$counter]);
	echo $fwa[$month_index].',';
}

?>

 ],


['Focus NSW',

<?php

for($i=0; $i < 12 ; $i++){	
	$alternator = $calendar_view;
	$counter = $i;

	if($alternator == 1){
		$counter = $counter + 6;
	}

	if($alternator == 1 && $counter > 11){
		$counter = $counter - 12;
	}

	$month_index = 'rev_'.strtolower($months[$counter]);
	echo $fnsw[$month_index].',';
}

?>

 ],


['Focus WA - Outstanding',

<?php

for($i=0; $i < 12 ; $i++){	
	$alternator = $calendar_view;
	$counter = $i;

	if($alternator == 1){
		$counter = $counter + 6;
	}

	if($alternator == 1 && $counter > 11){
		$counter = $counter - 12;
	}

	$month_index = 'rev_'.strtolower($months[$counter]);
	echo $fwaO[$month_index].',';
}

?>

 ],

['Focus NSW - Outstanding',

<?php

for($i=0; $i < 12 ; $i++){	
	$alternator = $calendar_view;
	$counter = $i;

	if($alternator == 1){
		$counter = $counter + 6;
	}

	if($alternator == 1 && $counter > 11){
		$counter = $counter - 12;
	}

	$month_index = 'rev_'.strtolower($months[$counter]);
	echo $fnswO[$month_index].',';
}

?>

 ],


        <?php if(isset($saved_forecast_pmData)): ?>
        	<?php

        		foreach($saved_forecast_pmData->result_array() as $forecast):

        		if($forecast['pm_name'] == 'Maintenance Manager'){
        			echo "['".$forecast['pm_name'].' '.$forecast['focus_comp_id']."',";
        		}else{
        			echo "['".$forecast['pm_name']."',";
        		}

	        		for($i=0; $i < 12 ; $i++){	
	        			$alternator = $calendar_view;
	        			$counter = $i;

	        			if($alternator == 1){
	        				$counter = $counter + 6;
	        			}

	        			if($alternator == 1 && $counter > 11){
	        				$counter = $counter - 12;
	        			}

	        			$month_index = 'rev_'.strtolower($months[$counter]);
	        			echo $forecast[$month_index].',';
	        		}

        			echo "],\n";
        		endforeach;
        	?>

		<?php endif; ?>



		

		<?php echo "['".$fetch_forecast['forecast_label']."',"; ?><?php 

          for($i=0; $i < 12 ; $i++){	
          	$alternator = $calendar_view;
          	$counter = $i;

          	if($alternator == 1){
          		$counter = $counter + 6;
          	}

          	if($alternator == 1 && $counter > 11){
          		$counter = $counter - 12;
          	}

          	

          	$month_index = 'forecast_'.strtolower($months[$counter]);

          	$item_forecast = $fetch_forecast['total'] * ($fetch_forecast[$month_index]/100);



          	echo $item_forecast.',';

          }





          ?> ],

 



			<?php 
/*
			 foreach ($revenue_forecast as $key => $forecast): ?>


				<?php if($forecast->data_type == 'Forecast'): ?>

	          		<?php $segment_a = $forecast->total_amount*($forecast->jul_breakdown/100).','.$forecast->total_amount*($forecast->aug_breakdown/100).','.$forecast->total_amount*($forecast->sep_breakdown/100).','.$forecast->total_amount*($forecast->oct_breakdown/100).','.$forecast->total_amount*($forecast->nov_breakdown/100).','.$forecast->total_amount*($forecast->dec_breakdown/100); ?>
	          		<?php $segment_b = $forecast->total_amount*($forecast->jan_breakdown/100).','.$forecast->total_amount*($forecast->feb_breakdown/100).','.$forecast->total_amount*($forecast->mar_breakdown/100).','.$forecast->total_amount*($forecast->apr_breakdown/100).','.$forecast->total_amount*($forecast->may_breakdown/100).','.$forecast->total_amount*($forecast->jun_breakdown/100); ?>

				<?php else: ?>

	          		<?php $segment_a = $forecast->jul_breakdown.','.$forecast->aug_breakdown.','.$forecast->sep_breakdown.','.$forecast->oct_breakdown.','.$forecast->nov_breakdown.','.$forecast->dec_breakdown; ?>
	          		<?php $segment_b = $forecast->jan_breakdown.','.$forecast->feb_breakdown.','.$forecast->mar_breakdown.','.$forecast->apr_breakdown.','.$forecast->may_breakdown.','.$forecast->jun_breakdown; ?>

	          	<?php endif; ?>

	          	['<?php echo $forecast->data_name; ?>', <?php echo $segment_a.','.$segment_b; ?>],
	      	<?php endforeach;


*/
	      	 ?>

        ],
        selection: {enabled: true},
        type: 'bar',
        types: {   '<?php echo $fetch_forecast["forecast_label"]; ?>' : 'line',  <?php /* foreach ($revenue_forecast as $key => $forecast): ?><?php if($forecast->data_type == 'Forecast'){ echo "'$forecast->data_name' : 'line',"; } ?><?php endforeach; */ ?>
        },
        groups: [ 

        
        	[
	        	<?php $group_a = 0;  ?>
/*
	        	<?php 
/*
	        	foreach ($revenue_forecast as $key => $forecast): ?>
		        	<?php
			        	if($group_a == 0 ){
			        		$group_a = $forecast->focus_company_id;
			        	}
			        	if($group_a != $forecast->focus_company_id ){ 
			        		echo '],[';
			        		echo "'$forecast->data_name',";
			        	}else{
			        		echo "'$forecast->data_name',";
			        	}
		        	?>
	        	<?php endforeach;
*/

	        	 ?>
*/
	        ],

        	[
	        	<?php $group = 0;  ?>

/*
	        	<?php foreach ($pm_names as $key => $names): ?>
		        	<?php
			        	if($group == 0 ){
			        		$group = $names->user_focus_company_id;
			        	}
			        	if($group != $names->user_focus_company_id ){ 
			        		echo '],[';
			        		echo "'$names->user_pm',";
			        	}else{
			        		echo "'$names->user_pm',";
			        	}
		        	?>
	        	<?php endforeach; ?>
*/

	        ],

      	 ['Alan Liddell','Trevor Gamble','Pyi Paing Aye Win','Krzysztof Kiezun','Maintenance Manager 5','Stuart Hubrich','Maintenance Manager 6'],
         ['Focus WA','Focus NSW'],
        ],
        //order: 'asc' 
      },
    tooltip: {
        grouped: true // false // Default true
    },
             bindto: "#chart_a",
bar: {
  width: { ratio: 0.5 }
},
point: {
  select: {
    r: 6
  }
},
onrendered: function () { 
 

 
	$('.loading_chart').remove();
 

	 },
      zoom: {
        enabled: true
      },
      axis: {
        x: {
          type: 'category',
          tick: {
            rotate: 0,
            multiline: false
          },
          height: 50
        }
      }
    });

chart.select();
chart.hide(['Focus WA', 'Focus NSW']);



function funca(){
		chart.hide();
chart.groups([
      	 ['Alan Liddell','Trevor Gamble','Pyi Paing Aye Win','Krzysztof Kiezun','Maintenance Manager 5','Stuart Hubrich','Maintenance Manager 6'],
         ['Focus WA','Focus NSW']]);


  setTimeout(function () {
    chart.show(['Alan Liddell','Krzysztof Kiezun','Pyi Paing Aye Win','Trevor Gamble','Stuart Hubrich','Maintenance Manager 5','Maintenance Manager 6']);
  }, 500);
		 setTimeout(function () {   chart.show(['Forecast 2015']); }, 1000);
}


function funcb(){
		chart.hide();
		 setTimeout(function () {


chart.groups([['Focus NSW','Focus WA']]);
   chart.show(['Focus WA', 'Focus NSW']);
 }, 500);




		 setTimeout(function () {   chart.show(['Forecast 2015']); }, 1000);


		 
}



	chart.hide(['Focus WA - Outstanding','Focus NSW - Outstanding']);	


	function funcc(element_obj){
		var forecast_display = element_obj.getAttribute("id");
		chart.hide();
		
		if(forecast_display == 'visible_forecast'){
			setTimeout(function () {
				chart.groups([['Focus NSW', 'Focus NSW - Outstanding']]);
				chart.show(['Focus NSW', 'Focus NSW - Outstanding']);
		element_obj.setAttribute("id", "hidden_forecast");
			}, 500);
		}else{
			setTimeout(function () {
				chart.groups([['Focus WA', 'Focus WA - Outstanding']]);
				chart.show(['Focus WA', 'Focus WA - Outstanding']);
		element_obj.setAttribute("id", "visible_forecast");
			}, 500);
		}
	}




<?php if(isset($_POST['data_name'])): ?>
setTimeout(function () {
    chart.load({
        columns:<?php echo "[ ['".$_POST['data_name']."', ".$_POST['value_items']."] ]";?>,
        type: 'line',
        colors: { <?php echo "'".$_POST['data_name']."'"; ?> : <?php echo "'".$_POST['data_color']."'"; ?> }
    });
chart.select([<?php echo "'".$_POST['data_name']."'"; ?> ]);
}, 2000);

<?php endif; ?>

setTimeout(function () {
//  chart.hide(['data2', 'data3']);
 // chart.show(['data2', 'data5']);
//chart.hide(['data2', 'data5']);

}, 3000);

/*
setTimeout(function () {
 chart.load({
  columns: [
          ['data11', 200, 130, 90, 240, 130, 220, 200, 130, 90, 240, 130, 220],
          ['data12', 150, 180, 50, 190, 60, 210, 150, 180, 50, 190, 60, 210],
  ]
});
}, 2000);

setTimeout(function () {
  chart.unload({
    ids: ['data9', 'data2', 'data8', 'data7', 'data1']
  });
},4000);



columns:[ ['ADASDAS',  ] ],
columns:[ [data_name, 660, 630,620, 650, 640, 660, 650] ],


setTimeout(function () {
  chart.unload({
    ids: ['data11', 'data12']
  });
},4000);


200, 130, 90, 240, 130, 220, 200, 130, 90, 240, 130, 220
*/







var chart_b = c3.generate({
      size: { height: 395 },
      data: {
        x : 'x',
        columns: [
          ['x', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
          ['Sales Focus WA', 650, 470, 400, 710, 540, 760, 650, 470, 400, 710, 540, 760],   
          ['Invoiced WA', 300, 200, 160, 400, 250, 250, 300, 200, 160, 400, 250, 250],
        ],
        selection: {
            enabled: true
        },
        type: 'bar',
        colors: {
            "Sales Focus WA": '#65D91A',
            'Invoiced WA': '#224EA5',
        }, 
        types: {
          'Invoiced WA': 'area',
        //  data4: 'area',
        },
        order: 'asc' 
      },
	  tooltip: { grouped: true    },
	  bindto: "#chart_b",
	  bar: {  width: { ratio: 0.4 } },
	  point: {   select: {    r: 6  }},
	  onrendered: function () {	$('.loading_chart').remove(); },
	  zoom: { enabled: true },
	  axis: {x: {type: 'category',tick: {rotate: 0,multiline: false},height: 50} }
});

chart_b.select(['Invoiced WA']);


var chart_c = c3.generate({
      size: { height: 395 },
      data: {
        x : 'x',
        columns: [
          ['x', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        
          ['Sales Focus NSW',  130, 120, 150, 140, 160, 150, 130, 120, 150, 140, 160, 150],
         
          ['Invoiced NSW', 90, 70, 20, 50, 60, 120, 90, 70, 20, 50, 60, 120],
        ],
        selection: {
            enabled: true
        },
        type: 'bar',
        colors: {
            'Sales Focus NSW': '#F7AC1E',
            'Invoiced NSW': '#DF2050'
        }, 
        types: {
          'Invoiced NSW': 'area',
        //  data4: 'area',
        },
        order: 'asc' 
      },
	  tooltip: { grouped: true    },
	  bindto: "#chart_c",
	  bar: {  width: { ratio: 0.4 } },
	  point: {   select: {    r: 6  }},
	  onrendered: function () {	$('.loading_chart').remove(); },
	  zoom: { enabled: true },
	  axis: {x: {type: 'category',tick: {rotate: 0,multiline: false},height: 50} }
});

chart_c.select(['Invoiced NSW']);

/*


var chart_b = c3.generate({
      size: { height: 395 },
      data: {
        x : 'x',
        columns: [
          ['x', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
          ['Sales Focus WA', 650, 470, 400, 710, 540, 760, 650, 470, 400, 710, 540, 760],   
          ['Sales Focus NSW',  130, 120, 150, 140, 160, 150, 130, 120, 150, 140, 160, 150],
          ['Invoiced WA', 300, 200, 160, 400, 250, 250, 300, 200, 160, 400, 250, 250],
          ['Invoiced NSW', 90, 70, 20, 50, 60, 120, 90, 70, 20, 50, 60, 120],
        ],
        selection: {
            enabled: true
        },
        type: 'bar',
        colors: {
            "Sales Focus WA": '#65D91A',
            'Sales Focus NSW': '#F7AC1E',
            'Invoiced WA': '#224EA5',
            'Invoiced NSW': '#DF2050'
        }, 
        types: {
          'Invoiced WA': 'area',
          'Invoiced NSW': 'area',
        //  data4: 'area',
        },
        order: 'asc' 
      },
	  tooltip: { grouped: true    },
	  bindto: "#chart_b",
	  bar: {  width: { ratio: 0.4 } },
	  point: {   select: {    r: 6  }},
	  onrendered: function () {	$('.loading_chart').remove(); },
	  zoom: { enabled: true },
	  axis: {x: {type: 'category',tick: {rotate: 0,multiline: false},height: 50} }
});

chart_b.select(['Invoiced WA']);
chart_b.select(['Invoiced NSW']);


 


*/
 


function funcd(element_obj){

	var forecast_display = element_obj.getAttribute("id");

	if(forecast_display == 'visible_forecast_b'){
		chart_b.hide(['Sales Focus WA','Invoiced WA']);		
		chart_b.show(['Sales Focus NSW','Invoiced NSW']);	
		element_obj.setAttribute("id", "hidden_forecast_b");
	}else{
		chart_b.show(['Sales Focus WA','Invoiced WA']);
		chart_b.hide(['Sales Focus NSW','Invoiced NSW']);	
		element_obj.setAttribute("id", "visible_forecast_b");
	}
}
























var donuta = c3.generate({
     size: {
        height: 250
      },data: {
        columns: [
            ["Sales", 86584365.00],
            ['Invoice', 10256544.00],
        ],
        type : 'donut',
        colors: {
            "Sales": '#1F77B4',
            'Invoice': '#4DAB4D'
        }, 
        onclick: function (d, i) { console.log("onclick", d, i); },
        onmouseover: function (d, i) { console.log("onmouseover", d, i); },
        onmouseout: function (d, i) { console.log("onmouseout", d, i); }
    },
             bindto: "#donut_a",
    donut: {
        title: "Focus WA"
    }
});


var donutb = c3.generate({
     size: {
        height: 250
      },data: {
        columns: [
            ["Sales", 820],
            ['Invoice', 490],
        ],
        type : 'donut',
        colors: {
            "Sales": '#FF7F0E',
            'Invoice': '#4DAB4D'
        },
        color: function (color, d) {
            // d will be 'id' when called for legends
            return d.id && d.id === 'data3' ? d3.rgb(color).darker(d.value / 150) : color;
        },
        onclick: function (d, i) { console.log("onclick", d, i); },
        onmouseover: function (d, i) { console.log("onmouseover", d, i); },
        onmouseout: function (d, i) { console.log("onmouseout", d, i); }
    },
             bindto: "#donut_b",
    donut: {
        title: "Focus NSW"
    }
});



function printDiv(divName) {
      var printContents = document.getElementById(divName).innerHTML;     
   var originalContents = document.body.innerHTML;       
   document.body.innerHTML = printContents;      
   window.print();      
   document.body.innerHTML = originalContents;
   }
 

 
 





    </script>


<style type="text/css">
	
	#dataTable_noCustom_length{
		margin-left: 5px;
	}
	
</style>

<!-- Modal -->
<!-- 
<div class="modal fade" id="add_data_chart" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Data</h4>
      </div>
      <form method="post" >
      <div class="modal-body pad-10">


      	<div class="pad-5 clearfix">
      		<input type="text" class="form-control m-bottom-10 data_name" id="data_name" name="data_name" placeholder="Data Name" name="" value="">

			<select name="data_color" class="form-control m-bottom-10" >
				<option>2015</option>
				<option>2016</option>
				<option>2017</option>
				<option>2018</option>
				<option>2019</option>
				<option>2020</option>
			</select>

      		<input type="text" class="form-control year m-bottom-5 number_format" id="year" name="year" placeholder="Total Forecast" name="" value="">
      	</div>

      	<div class="no-pad-imp clearfix">

      		<div class="col-md-6 col-sm-6 col-xs-12 clearfix ">
      			<div class="input-group m-bottom-10">
      				<span class="input-group-addon" id="">WA Share</span>      		
      				<input type="text" class="form-control" id="" placeholder="%" name="" value="">
      				<span class="input-group-addon" id="" style="padding: 0;    width: 110px;overflow: hidden;">
      					<select name="data_color" class="form-control m-bottom-15" style="margin: -1px 1px -1px 2px;">
      						<option value="#FD0000">Red</option>
      						<option value="#00CA00">Green</option>
      						<option value="#7008A8">Purple</option>
      						<option value="#FD7300">Orange</option>
      					</select>
      				</span>      			
      			</div>
      		</div>


      		<div class="col-md-6 col-sm-6 col-xs-12 clearfix ">
      			<div class="input-group m-bottom-10">
      				<span class="input-group-addon" id="">NSW Share</span>      		
      				<input type="text" class="form-control" id="" placeholder="%" name="" value="">
      				<span class="input-group-addon" id="" style="padding: 0;    width: 110px;overflow: hidden;">
      					<select name="data_color" class="form-control m-bottom-15" style="margin: -1px 1px -1px 2px;">
      						<option value="#FD0000">Blue</option>
      						<option value="#00CA00">Yellow</option>
      						<option value="#7008A8">Cyan</option>
      						<option value="#FD7300">Magenta</option>
      					</select>
      				</span>      			
      			</div>
      		</div>
      	</div>



      	<div class="no-pad-imp clearfix">

      		<p class="m-5 pad-top-10">Monthly Breakdown For WA <a class="pull-right" style="cursor: pointer;" data-toggle="collapse" data-target=".more-list"> Show More </a></p>
      		<div class="more-list collapse out">
      			<div class="col-md-4 col-sm-4 col-xs-4 clearfix ">
      				<div class="input-group m-bottom-10">
      					<span class="input-group-addon" id="">Jan</span>      		
      					<input type="text" class="form-control" id="" placeholder="%" name="" value="">
      				</div>
      			</div>

      			<div class="col-md-4 col-sm-4 col-xs-4 clearfix ">
      				<div class="input-group m-bottom-10">
      					<span class="input-group-addon" id="">Fab</span>      		
      					<input type="text" class="form-control" id="" placeholder="%" name="" value="">
      				</div>
      			</div>

      			<div class="col-md-4 col-sm-4 col-xs-4 clearfix ">
      				<div class="input-group m-bottom-10">
      					<span class="input-group-addon" id="">Mar</span>      		
      					<input type="text" class="form-control" id="" placeholder="%" name="" value="">
      				</div>
      			</div>


      			<div class="col-md-4 col-sm-4 col-xs-4 clearfix ">
      				<div class="input-group m-bottom-10">
      					<span class="input-group-addon" id="">Apr</span>      		
      					<input type="text" class="form-control" id="" placeholder="%" name="" value="">
      				</div>
      			</div>

      			<div class="col-md-4 col-sm-4 col-xs-4 clearfix ">
      				<div class="input-group m-bottom-10">
      					<span class="input-group-addon" id="">May</span>      		
      					<input type="text" class="form-control" id="" placeholder="%" name="" value="">
      				</div>
      			</div>

      			<div class="col-md-4 col-sm-4 col-xs-4 clearfix ">
      				<div class="input-group m-bottom-10">
      					<span class="input-group-addon" id="">Jun</span>      		
      					<input type="text" class="form-control" id="" placeholder="%" name="" value="">
      				</div>
      			</div>

      			<div class="col-md-4 col-sm-4 col-xs-4 clearfix ">
      				<div class="input-group m-bottom-10">
      					<span class="input-group-addon" id="">Jul</span>      		
      					<input type="text" class="form-control" id="" placeholder="%" name="" value="">
      				</div>
      			</div>

      			<div class="col-md-4 col-sm-4 col-xs-4 clearfix ">
      				<div class="input-group m-bottom-10">
      					<span class="input-group-addon" id="">Aug</span>      		
      					<input type="text" class="form-control" id="" placeholder="%" name="" value="">
      				</div>
      			</div>

      			<div class="col-md-4 col-sm-4 col-xs-4 clearfix ">
      				<div class="input-group m-bottom-10">
      					<span class="input-group-addon" id="">Sep</span>      		
      					<input type="text" class="form-control" id="" placeholder="%" name="" value="">
      				</div>
      			</div>

      			<div class="col-md-4 col-sm-4 col-xs-4 clearfix ">
      				<div class="input-group m-bottom-10">
      					<span class="input-group-addon" id="">Oct</span>      		
      					<input type="text" class="form-control" id="" placeholder="%" name="" value="">
      				</div>
      			</div>

      			<div class="col-md-4 col-sm-4 col-xs-4 clearfix ">
      				<div class="input-group m-bottom-10">
      					<span class="input-group-addon" id="">Nov</span>      		
      					<input type="text" class="form-control" id="" placeholder="%" name="" value="">
      				</div>
      			</div>

      			<div class="col-md-4 col-sm-4 col-xs-4 clearfix ">
      				<div class="input-group m-bottom-10">
      					<span class="input-group-addon" id="">Dec</span>      		
      					<input type="text" class="form-control" id="" placeholder="%" name="" value="">
      				</div>
      			</div>
      		</div>
      	</div>

      	<div class="no-pad-imp clearfix">

      		<p class="m-5 pad-top-10">Monthly Breakdown For NSW <a class="pull-right" style="cursor: pointer;" data-toggle="collapse" data-target=".more-list_b"> Show More </a></p>
      		<div class="more-list_b collapse out">
      			<div class="col-md-4 col-sm-4 col-xs-4 clearfix ">
      				<div class="input-group m-bottom-10">
      					<span class="input-group-addon" id="">Jan</span>      		
      					<input type="text" class="form-control" id="" placeholder="%" name="" value="">
      				</div>
      			</div>

      			<div class="col-md-4 col-sm-4 col-xs-4 clearfix ">
      				<div class="input-group m-bottom-10">
      					<span class="input-group-addon" id="">Fab</span>      		
      					<input type="text" class="form-control" id="" placeholder="%" name="" value="">
      				</div>
      			</div>

      			<div class="col-md-4 col-sm-4 col-xs-4 clearfix ">
      				<div class="input-group m-bottom-10">
      					<span class="input-group-addon" id="">Mar</span>      		
      					<input type="text" class="form-control" id="" placeholder="%" name="" value="">
      				</div>
      			</div>


      			<div class="col-md-4 col-sm-4 col-xs-4 clearfix ">
      				<div class="input-group m-bottom-10">
      					<span class="input-group-addon" id="">Apr</span>      		
      					<input type="text" class="form-control" id="" placeholder="%" name="" value="">
      				</div>
      			</div>

      			<div class="col-md-4 col-sm-4 col-xs-4 clearfix ">
      				<div class="input-group m-bottom-10">
      					<span class="input-group-addon" id="">May</span>      		
      					<input type="text" class="form-control" id="" placeholder="%" name="" value="">
      				</div>
      			</div>

      			<div class="col-md-4 col-sm-4 col-xs-4 clearfix ">
      				<div class="input-group m-bottom-10">
      					<span class="input-group-addon" id="">Jun</span>      		
      					<input type="text" class="form-control" id="" placeholder="%" name="" value="">
      				</div>
      			</div>

      			<div class="col-md-4 col-sm-4 col-xs-4 clearfix ">
      				<div class="input-group m-bottom-10">
      					<span class="input-group-addon" id="">Jul</span>      		
      					<input type="text" class="form-control" id="" placeholder="%" name="" value="">
      				</div>
      			</div>

      			<div class="col-md-4 col-sm-4 col-xs-4 clearfix ">
      				<div class="input-group m-bottom-10">
      					<span class="input-group-addon" id="">Aug</span>      		
      					<input type="text" class="form-control" id="" placeholder="%" name="" value="">
      				</div>
      			</div>

      			<div class="col-md-4 col-sm-4 col-xs-4 clearfix ">
      				<div class="input-group m-bottom-10">
      					<span class="input-group-addon" id="">Sep</span>      		
      					<input type="text" class="form-control" id="" placeholder="%" name="" value="">
      				</div>
      			</div>

      			<div class="col-md-4 col-sm-4 col-xs-4 clearfix ">
      				<div class="input-group m-bottom-10">
      					<span class="input-group-addon" id="">Oct</span>      		
      					<input type="text" class="form-control" id="" placeholder="%" name="" value="">
      				</div>
      			</div>

      			<div class="col-md-4 col-sm-4 col-xs-4 clearfix ">
      				<div class="input-group m-bottom-10">
      					<span class="input-group-addon" id="">Nov</span>      		
      					<input type="text" class="form-control" id="" placeholder="%" name="" value="">
      				</div>
      			</div>

      			<div class="col-md-4 col-sm-4 col-xs-4 clearfix ">
      				<div class="input-group m-bottom-10">
      					<span class="input-group-addon" id="">Dec</span>      		
      					<input type="text" class="form-control" id="" placeholder="%" name="" value="">
      				</div>
      			</div>
      		</div>
      	</div>

      </div>
      <div class="modal-footer pad-10 m-top-5 m-right-5">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-success add_data_chart" value="Save changes">
      </div>
      </form>
    </div>
  </div>
</div>
-->