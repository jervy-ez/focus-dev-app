<?php date_default_timezone_set("Australia/Perth");  // date is set to perth ?>
<?php $this->load->module('bulletin_board'); ?>
<?php $this->load->module('dashboard'); ?>
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

								<?php if(@$this->session->flashdata('error_add_fs')): ?> 
									<div class="widget wid-type-d widg-head-styled pad-0-imp m-bottom-20">
										<span class="label label-default pull-right pointer" data-dismiss="alert" style="display: block; margin: 6px 9px;">x</span>									
										<div class="widg-head fill box-widg-head pad-5"><i class="fa fa-exclamation-triangle"></i> <strong>You got some errors, please see the following below.</strong> </div>
										<div class="box-area clearfix">
											<div class="widg-content clearfix">
												<?php echo $this->session->flashdata('error_add_fs');?>							
											</div>
										</div>
									</div>
								<?php endif; ?>

								<?php if(@$this->session->flashdata('record_update')): ?> 
									<div class="widget wid-type-b widg-head-styled pad-0-imp m-bottom-20">
										<span class="label label-default pull-right pointer" data-dismiss="alert" style="display: block; margin: 6px 9px;">x</span>									
										<div class="widg-head box-widg-head pad-5"><i class="fa fa-exclamation-triangle"></i> <strong><?php echo $this->session->flashdata('record_update');?></strong> </div>									
									</div>
								<?php endif; ?>	


<!-- 
								<div class="widg-head fill box-widg-head pad-5"><strong>Sales Standing</strong> <span class="badges pull-right m-right-10"> <span class="tabs active" id="tab_wa">Focus WA</span>  <span class="tabs" id="tab_nsw">Focus NSW</span> </span></div>
								<div class="box-area clearfix">
									<div class="row pad-10">

										<div class="tab_container">
											<div id="tab_wa_area" class="tab_area active">

  -->


								<div class="widget wid-type-a widg-head-styled">
									<div class="widg-head box-widg-head pad-5"><i class="fa fa-cog"></i> 
										<strong class="pointer collapsed" data-toggle="collapse" data-target=".data_forecast">Forecast Settings</strong>
										<span class="badges pull-right m-right-10"> 
											<span class="tabs active" id="tab_addnew">Add New</span> 
											<span class="tabs" id="tab_forecasts">Forecasts</span> 
										</span>
									</div>
								
									<div class="box-area clearfix data_forecast out collapse" style="height: 0px;">
										<div class="widg-content clearfix">
											<div class="tab_container">
												<div id="tab_addnew_area" class="tab_area active clearfix row pad-0-imp no-m-imp">
													<div class="col-md-7 col-sm-12 col-xs-12" id="">
														<strong class="m-bottom-10 block data_label"><?php echo (@$this->session->flashdata('is_update')? 'Update Data' : 'Add New Data'); ?></strong> <small class="block m-bottom-10"><em>All of these fieds are required.</em></small>
														<script type="text/javascript"> //$('select#data_year').val('<?php if(@$this->session->flashdata("data_year")){ echo $this->session->flashdata("data_year"); } ?>');</script>
														<form method="post" id="forecast_form" class="m-top-5 m-bottom-5" action="<?php echo base_url(); echo (@$this->session->flashdata('is_update')? 'dashboard/update_sales_forecast' : 'dashboard/add_data_sales_forecast'); ?>">

															<div class="clearfix row pad-0-imp no-m-imp">

																<div class="col-lg-4 col-xs-6" id="">	
																	<div class="input-group m-bottom-10 <?php if(@$this->session->flashdata('error_data_amount')){ echo "has-error"; } ?>">
																		<span class="input-group-addon" id="">Total</span>      		
																		<input type="text" class="form-control m-bottom-10 data_name number_format input-sm" id="data_amount" name="data_amount" placeholder="$" value="<?php if(@$this->session->flashdata("data_amount")){ echo $this->session->flashdata("data_amount"); } ?>">
																		<span class="input-group-addon" id="">EX-GST</span> 
																	</div>
																</div>

																<?php foreach ($focus as $key => $value): ?>
																	<?php if($value->company_name != 'FSF Group Pty Ltd'): ?>
																		<div class="col-lg-4 col-md-6 col-xs-6" id="">	
																			<div class="input-group m-bottom-10 <?php if(@$this->session->flashdata('error_data_name')){ echo "has-error"; } ?>">
																				<span class="input-group-addon" id=""><?php echo $value->company_name; ?></span> 
																				<input type="text" class="form-control m-bottom-10 data_name input-sm" id="data_name_<?php echo $value->company_id; ?>" name="data_name" placeholder="%" value="<?php if(@$this->session->flashdata("data_name")){ echo $this->session->flashdata("data_name"); } ?>">
																				<span class="input-group-addon" id=""><strong>%</strong></span>  	
																			</div>
																		</div>
																	<?php endif; ?>
																<?php endforeach; ?>

																<?php $group = 0; $group_min = 0; $add_custom_min = 0; $start = 0; $counter = 0;?>
																<?php foreach ($pm_names as $key => $names): ?>
	 

																	<?php $start = $names->user_focus_company_id; ?>

																	<?php if($counter > 0 && $start != $add_custom_min): ?>
																		<div class="col-lg-4 col-md-6 col-xs-6" id="">	
																			<div class="input-group m-bottom-10 <?php if(@$this->session->flashdata('error_data_name')){ echo "has-error"; } ?>">
																				<span class="input-group-addon" id="">Maintenance</span>  
																				<input type="text" class="form-control m-bottom-10 data_name input-sm" id="data_name_<?php echo $names->user_id; ?>" name="data_name" placeholder="%" value="<?php if(@$this->session->flashdata("data_name")){ echo $this->session->flashdata("data_name"); } ?>">
																				<span class="input-group-addon" id=""><strong>%</strong></span> 
																			</div>
																		</div>

																		<div class="col-lg-4 col-md-6 col-xs-6" id="">	
																			<div class="input-group m-bottom-10">
																				<input type="text" class="form-control m-bottom-10 data_name input-sm " id="data_name_" name="data_name" placeholder="Other" value="" style="width: 70%;"> 
																				<input type="text" class="form-control m-bottom-10 data_name input-sm " id="data_name_" name="data_name" placeholder="%" value="" style="width: 30%;">
																				<span class="input-group-addon" id="">%</span>  
																				<span class="input-group-addon pointer add_custom_pm" id=""><strong>+</strong></span>  
																			</div>
																		</div>

																	<?php endif; ?>

																	<?php if($group != $names->user_focus_company_id ): $group = $names->user_focus_company_id;?>
																		<div class="clearfix"></div><hr class="block m-bottom-5 m-top-5"><strong class="m-bottom-10 block data_label"><?php echo $names->company_name; ?></strong><div class="clearfix"></div>
																	<?php endif; ?>

																	<div class="col-lg-4 col-md-6 col-xs-6" id="">	
																		<div class="input-group m-bottom-10 <?php if(@$this->session->flashdata('error_data_name')){ echo "has-error"; } ?>">
																			<span class="input-group-addon" id=""><?php echo $names->user_pm; ?></span>  
																			<input type="text" class="form-control m-bottom-10 data_name input-sm" id="data_name_<?php echo $names->user_id; ?>" name="data_name" placeholder="%" value="<?php if(@$this->session->flashdata("data_name")){ echo $this->session->flashdata("data_name"); } ?>">
																			<span class="input-group-addon" id=""><strong>%</strong></span> 
																		</div>
																	</div>


															
																	




														 


																	

																	<?php $counter++; ?>
																	<?php $add_custom_min = $names->user_focus_company_id; ?>

																<?php endforeach; ?>

																<div class="col-lg-4 col-md-6 col-xs-6" id="">	
																			<div class="input-group m-bottom-10 <?php if(@$this->session->flashdata('error_data_name')){ echo "has-error"; } ?>">
																				<span class="input-group-addon" id="">Maintenance</span>  
																				<input type="text" class="form-control m-bottom-10 data_name input-sm" id="data_name_<?php echo $names->user_id; ?>" name="data_name" placeholder="%" value="<?php if(@$this->session->flashdata("data_name")){ echo $this->session->flashdata("data_name"); } ?>">
																				<span class="input-group-addon" id=""><strong>%</strong></span>
																			</div>
																		</div>

																		<div class="col-lg-4 col-md-6 col-xs-6" id="">	
																			<div class="input-group m-bottom-10">
																				<input type="text" class="form-control m-bottom-10 data_name input-sm " id="data_name_" name="data_name" placeholder="Other" value="" style="width: 70%;"> 
																				<input type="text" class="form-control m-bottom-10 data_name input-sm " id="data_name_" name="data_name" placeholder="%" value="" style="width: 30%;">
																				<span class="input-group-addon" id="">%</span>  
																				<span class="input-group-addon pointer add_custom_pm" id=""><strong>+</strong></span>  
																			</div>
																		</div>

																<div class="clearfix"></div>

															</div>
															<input type="hidden" name="rfc_token" id="rfc_token" class="rfc_token" value="<?php if(@$this->session->flashdata("rfc_token")){ echo $this->session->flashdata("rfc_token"); } ?>">

															<?php if(@$this->session->flashdata('is_update')): ?> 
																<input type="submit" class="btn btn-sm m-top-5 data_submit btn-info pull-left" value="Update Data">
															<?php else: ?>	
																<input type="submit" class="btn btn-success btn-sm m-top-5 data_submit" value="Save Data" />
															<?php endif; ?>

															<div class="form_forecast_update_tools pull-right" style="<?php echo (@$this->session->flashdata('is_update')? '' : 'display:none;'); ?>">
																<a href="#" class="btn btn-danger btn-sm m-top-5 m-right-15 delete_cancel">Delete Data</a>
																<a href="#" class="btn btn-warning btn-sm m-top-5 m-right-5 data_cancel">Cancel Update</a>
															</div>

														</form>
													</div>

													<div class="col-md-5 col-sm-12  col-xs-12" id="">
														<div class="pad-right-5 pad-left-5">
															 
															
																<strong class="m-bottom-10 block">Monthly Breakdown</strong>
																<small class="block m-bottom-10"><em>Suggested values are provided.</em></small>

																<div class="row pad-0-imp no-m-imp monthly_breakdown">

																	<div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 clearfix ">
																		<div class="input-group m-bottom-20">
																			<span class="input-group-addon">Year</span>      		
																			<select class="form-control input-sm">
																				<option>2015</option>
																			</select>
																		</div>
																	</div>
																	<div class="clearfix"></div>


																	<div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 clearfix ">
																		<div class="input-group m-bottom-20">
																			<span class="input-group-addon">Jan</span>      		
																			<input type="text" class="form-control number_format input-sm" id="jan" name="jan" placeholder="%" value="<?php if(@$this->session->flashdata("jan")){ echo $this->session->flashdata("jan"); } ?>">
																			<span class="input-group-addon"><strong>%</strong></span>   
																		</div>
																	</div>

																	<div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 clearfix ">
																		<div class="input-group m-bottom-20">
																			<span class="input-group-addon">Feb</span>      		
																			<input type="text" class="form-control number_format input-sm" id="feb" name="feb" placeholder="%" value="<?php if(@$this->session->flashdata("feb")){ echo $this->session->flashdata("feb"); } ?>">
																			<span class="input-group-addon"><strong>%</strong></span>  
																		</div>
																	</div>

																	<div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 clearfix ">
																		<div class="input-group m-bottom-20">
																			<span class="input-group-addon">Mar</span>      		
																			<input type="text" class="form-control number_format input-sm" id="mar" name="mar" placeholder="%" value="<?php if(@$this->session->flashdata("mar")){ echo $this->session->flashdata("mar"); } ?>">
																			<span class="input-group-addon"><strong>%</strong></span>  
																		</div>
																	</div>


																	<div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 clearfix ">
																		<div class="input-group m-bottom-20">
																			<span class="input-group-addon">Apr</span>      		
																			<input type="text" class="form-control number_format input-sm" id="apr" name="apr" placeholder="%" value="<?php if(@$this->session->flashdata("apr")){ echo $this->session->flashdata("apr"); } ?>">
																			<span class="input-group-addon"><strong>%</strong></span>  
																		</div>
																	</div>

																	<div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 clearfix ">
																		<div class="input-group m-bottom-20">
																			<span class="input-group-addon" >May</span>      		
																			<input type="text" class="form-control number_format input-sm" id="may" name="may" placeholder="%" value="<?php if(@$this->session->flashdata("may")){ echo $this->session->flashdata("may"); } ?>">
																			<span class="input-group-addon"><strong>%</strong></span>  
																		</div>
																	</div>

																	<div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 clearfix ">
																		<div class="input-group m-bottom-20">
																			<span class="input-group-addon" >Jun</span>      		
																			<input type="text" class="form-control number_format input-sm" id="jun" name="jun" placeholder="%" value="<?php if(@$this->session->flashdata("jun")){ echo $this->session->flashdata("jun"); } ?>">
																			<span class="input-group-addon"><strong>%</strong></span>  
																		</div>
																	</div>
																
																	<div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 clearfix ">
																		<div class="input-group m-bottom-20">
																			<span class="input-group-addon">Jul</span>      		
																			<input type="text" class="form-control number_format input-sm" id="jul" name="jul" placeholder="%" value="<?php if(@$this->session->flashdata("jul")){ echo $this->session->flashdata("jul"); } ?>">
																			<span class="input-group-addon"><strong>%</strong></span>  
																		</div>
																	</div>

																	<div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 clearfix ">
																		<div class="input-group m-bottom-20">
																			<span class="input-group-addon">Aug</span>      		
																			<input type="text" class="form-control number_format input-sm" id="aug" name="aug" placeholder="%" value="<?php if(@$this->session->flashdata("aug")){ echo $this->session->flashdata("aug"); } ?>">
																			<span class="input-group-addon"><strong>%</strong></span>  
																		</div>
																	</div>

																	<div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 clearfix ">
																		<div class="input-group m-bottom-20">
																			<span class="input-group-addon">Sep</span>      		
																			<input type="text" class="form-control number_format input-sm" id="sep" name="sep" placeholder="%" value="<?php if(@$this->session->flashdata("sep")){ echo $this->session->flashdata("sep"); } ?>">
																			<span class="input-group-addon"><strong>%</strong></span>  
																		</div>
																	</div>

																	<div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 clearfix ">
																		<div class="input-group m-bottom-20">
																			<span class="input-group-addon">Oct</span>      		
																			<input type="text" class="form-control number_format input-sm" id="oct" name="oct" placeholder="%" value="<?php if(@$this->session->flashdata("oct")){ echo $this->session->flashdata("oct"); } ?>">
																			<span class="input-group-addon"><strong>%</strong></span>  
																		</div>
																	</div>

																	<div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 clearfix ">
																		<div class="input-group m-bottom-20">
																			<span class="input-group-addon">Nov</span>      		
																			<input type="text" class="form-control number_format input-sm" id="nov" name="nov" placeholder="%" value="<?php if(@$this->session->flashdata("nov")){ echo $this->session->flashdata("nov"); } ?>">
																			<span class="input-group-addon"><strong>%</strong></span> 
																		</div>
																	</div>

																	<div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 clearfix ">
																		<div class="input-group m-bottom-20">
																			<span class="input-group-addon">Dec</span>      		
																			<input type="text" class="form-control number_format input-sm" id="dec" name="dec" placeholder="%" value="<?php if(@$this->session->flashdata("dec")){ echo $this->session->flashdata("dec"); } ?>">
																			<span class="input-group-addon"><strong>%</strong></span> 
																		</div>
																	</div>

																</div>
														</div>
													</div>   


												</div>		

												<div id="tab_forecasts_area" class="tab_area" style="display:none;">
													<div class="col-md-7 col-sm-12 col-xs-12" id="">
														<strong class="m-bottom-10 block data_label">Saved Forecasts</strong> <small class="block m-bottom-10"><em>All of these fieds are required.</em></small>
													</div>
												</div>
											</div>

										</div>
									</div>
								</div>
							</div>

						<?php endif; ?>

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




<style type="text/css">
	
	#dataTable_noCustom_length{
		/*margin-left: 5px;*/
	}
	
</style>
