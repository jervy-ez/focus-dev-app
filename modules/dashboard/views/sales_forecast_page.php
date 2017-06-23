<?php date_default_timezone_set("Australia/Perth");  // date is set to perth ?>
<?php $this->load->module('bulletin_board'); ?>
<?php $this->load->module('dashboard'); ?>
<?php $months = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"); ?>
<!-- title bar -->
<style type="text/css">body{background: #ECF0F5 !important;}</style>

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
						<a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Dashboard</a>
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

<div class="container-fluid adv"  style="background: #ECF0F5;">
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
											<span class="tabs active" id="tab_addnew">Add New</span> 
											<span class="tabs <?php if($tab_view == 'view'){ echo 'active';  } ?>" id="tab_forecasts">Forecasts</span> 
										</span>
									</div>


								
									<div class="box-area clearfix data_forecast collapse in <?php if(isset($form_toggle)){ echo ''; }else{ echo 'out'; } ?>">
										<div class="widg-content clearfix">
											<div class="tab_container">
												<div id="tab_addnew_area" class="tab_area active clearfix row pad-right-15  pad-left-15 no-pad-t"  >
													<form method="post" id="forecast_form" class="m-top-0 m-bottom-5 clearfix" action="">

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


													<p><hr /></p>
												</div>		


												<div id="tab_forecasts_area" class="tab_area" >
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
											<div class="pad-right-15  pad-left-15 no-pad-t">
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
																				<input type="text" class="form-control m-bottom-10 data_name input-sm number_format focus_comp_split_edt click_select focus_percent_val" id="focus_idedt_<?php echo $indv_forecast['comp_id']; ?>" onKeyUp="check_split('focus_comp_split_edt','focus_idedt_<?php echo $indv_forecast['comp_id']; ?>');" name="<?php echo $indv_forecast['revenue_forecast_individual_id']; ?>_focus_idedt_<?php echo $indv_forecast['comp_id']; ?>" placeholder="%" value="<?php if($this->input->post('focus_idedt_'.$indv_forecast['comp_id'])){  echo $this->input->post('focus_idedt_'.$indv_forecast['comp_id']);    }else{ echo $indv_forecast['forecast_percent'];  }?>">
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

						<div class="clearfix"></div>
						<!-- ************************ -->


		  







						<!-- ************************ -->





						<!-- ************************ -->



						<!-- ************************ -->
						<!-- ************************ -->						
						
		 




<?php #var_dump($inv_fcs_overall_sales); ?>




						<!-- ************************ -->





						<!-- ************************ -->


		

						<!-- ************************ -->
 

 










						<!-- ************************ -->

 

					
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
<!-- 
<script type='text/javascript' src='http://d3js.org/d3.v3.min.js'></script>
<script type='text/javascript' src="http://rawgit.com/masayuki0812/c3/master/c3.js"></script>
<link rel="stylesheet" type="text/css" href="http://rawgit.com/masayuki0812/c3/master/c3.css">
 -->
<script type='text/javascript'>


/*
var chart_b = c3.generate({
      size: { height: 395 },
      data: {
        x : 'x',
        columns: [
          ['x', 'Current Month`s Sales Per Project Manager'],
          <?php
	          foreach ($focus_indv_pm_month_sales->result_array() as $topPm) {
	          	echo "['".$topPm['pm_name']."',".$topPm['sales_month']." ],";
	          }
          ?>
        ],
        selection: {
            enabled: true
        },
        type: 'bar',
      },
	  tooltip: { grouped: true    },
	  bindto: "#chart_b",
	  bar: {  width: { ratio: 0.4 } },
	  point: {   select: {    r: 6  }},
	  onrendered: function () {	$('.loading_chart').remove(); },
	  zoom: { enabled: true },
	  axis: {x: {type: 'category',tick: {rotate: 0,multiline: false},height: 0} },
tooltip: {
        format: {
           // title: function (d) { return 'Data ' + d; },
            value: function (value, ratio, id) {
               // var format = id === 'data1' ? d3.format(',') : d3.format('$');
                var format = d3.format(',');
                
             	var mod_value = Math.round(value)
                return format(mod_value);
            }
        } 

    }
});
*/
/*
var chart_c = c3.generate({
      size: { height: 395 },
      data: {
        x : 'x',
        columns: [
          ['x', 'This Month`s Focus Sales'],
          <?php
	          foreach ($focus_indv_focu_month_sales->result_array() as $comp) {
	          	echo "['".$comp['company_name']."',".$comp['total_sales']." ],";
	          }
          ?>
          <?php
	          foreach ($focus_indv_focu_month_outs->result_array() as $comp) {
	          	echo "['".$comp['company_name']." Outstanding',".$comp['total_outstanding']." ],";
	          }
          ?>

        ],
        selection: {
            enabled: true
        },
        type: 'bar',
        colors: {
         //   "Focus Shopfit Pty Ltd": '#2A7F40',
         //   'Focus Shopfit Pty Ltd Outstanding': '#7FBF90',
         //   'Focus Shopfit NSW Pty Ltd': '#224EA5',
        },

        groups: [ ['Focus Shopfit Pty Ltd','Focus Shopfit Pty Ltd Outstanding'],['Focus Shopfit NSW Pty Ltd','Focus Shopfit NSW Pty Ltd Outstanding']  ],
       
      },
	  tooltip: { grouped: true    },
	  bindto: "#chart_c",
	  bar: {  width: { ratio: 0.4 } },
	  point: {   select: {    r: 6  }},
	  onrendered: function () {	$('.loading_chart').remove(); },
	  zoom: { enabled: true },
	  axis: {x: {type: 'category',tick: {rotate: 0,multiline: false},height: 0} },
tooltip: {
        format: {
          //  title: function (d) { return 'Data ' + d; },
            value: function (value, ratio, id) {
               // var format = id === 'data1' ? d3.format(',') : d3.format('$');
                var format = d3.format(',');
             	var mod_value = Math.round(value)
                return format(mod_value);
            }
        }, 

    }
});

*/

 

</script>


<style type="text/css">
	#dataTable_noCustom_length{
		margin-left: 5px;
	}
</style>
