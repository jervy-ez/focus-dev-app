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
						<a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Home</a>
					</li>  
					<li>
						<a class="btn-small sb-open-right"><i class="fa fa-file-text-o"></i> Project Comments</a>
					</li>
					<li>
						<a href="#" data-toggle="collapse" data-target=".data_forecast" ><i class="fa fa-cog"></i> Forecast Settings</a>
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

								<div class="widget wid-type-a widg-head-styled">
									<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide hidden"><i class="fa fa-spin fa-refresh"></i></div>
									<div class="widg-head box-widg-head pad-5"><i class="fa fa-cog"></i> <strong class="pointer" data-toggle="collapse" data-target=".data_forecast">Forecast Settings</strong> <span class="pull-right m-top-3" data-toggle="collapse" data-target=".data_forecast"><i class="fa fa-caret-square-o-down fa-lg pointer"></i></span></div>
									<div class="box-area clearfix data_forecast collapse <?php echo (@$this->session->flashdata('error_add_fs')? 'in' : 'out'); ?>">
										<div class="widg-content clearfix">

											<div class="clearfix row pad-0-imp no-m-imp">
												<div class="col-md-7 col-sm-12 col-xs-12" id="">
													<strong class="m-bottom-10 block data_label"><?php echo (@$this->session->flashdata('is_update')? 'Update Data' : 'New Data'); ?></strong> <small><em>All of these fieds are required.</em></small>


													<form method="post" id="forecast_form" class="m-top-5 m-bottom-5" action="<?php echo base_url(); echo (@$this->session->flashdata('is_update')? 'dashboard/update_sales_forecast' : 'dashboard/add_data_sales_forecast'); ?>">

														<div class="clearfix row pad-0-imp no-m-imp">

															<div class="col-lg-4 col-md-6 col-xs-6" id="">		
																<div class="input-group m-bottom-10 <?php if(@$this->session->flashdata('error_data_type')){ echo 'has-error'; } ?> ">
																	<span class="input-group-addon" id=""><i class="fa fa-file-text-o"></i></span>
																	<select name="data_type" id="data_type" class="form-control m-bottom-10">	
																		<option selected value="" style="display:none;">Select Data Type</option>
																		<option value="Forecast">Forecast</option>
																		<option value="Record">Record</option>
																	</select>
																	<script type="text/javascript">$('select#data_type').val('<?php if(@$this->session->flashdata("data_type")){ echo $this->session->flashdata("data_type"); } ?>');</script>
																</div>
															</div>

															<div class="col-lg-4 col-md-6 col-xs-6" id="">		
																<div class="input-group m-bottom-10 <?php if(@$this->session->flashdata('error_data_year')){ echo "has-error"; } ?>">
																	<span class="input-group-addon" id=""><i class="fa fa-calendar"></i></span>      		
																	<select name="data_year" id="data_year" class="form-control m-bottom-10">
																		<option selected value="" style="display:none;">Select Year</option>
																		<?php $year = date("Y"); for($i=0; $i < 6; $i++){																		
																			echo '<option value="'.($year+$i).'">'.($year+$i).'</option>';
																		}?>
																	</select>
																	<script type="text/javascript">$('select#data_year').val('<?php if(@$this->session->flashdata("data_year")){ echo $this->session->flashdata("data_year"); } ?>');</script>
																</div>
															</div>

															<div class="col-lg-4 col-md-6 col-xs-6" id="">		
																<div class="input-group m-bottom-10 <?php if(@$this->session->flashdata('error_focus_company')){ echo "has-error"; } ?>">
																	<span class="input-group-addon" id=""><i class="fa fa-briefcase"></i></span>
																	<select name="focus_company" id="focus_company" class="form-control m-bottom-10">
																		<option selected value="" style="display:none;">Select Focus Company</option>
																		<?php foreach ($focus as $key => $value): ?>
																			<?php if($value->company_name != 'FSF Group Pty Ltd'): ?>
																				<option value="<?php echo $value->company_id; ?>"><?php echo $value->company_name; ?></option>
																			<?php endif; ?>
																		<?php endforeach; ?>
																	</select>
																	<script type="text/javascript">$('select#focus_company').val('<?php if(@$this->session->flashdata("focus_company")){ echo $this->session->flashdata("focus_company"); } ?>');</script>
																</div>
															</div>

															<div class="col-lg-4 col-md-6 col-xs-6" id="">	
																<div class="input-group m-bottom-10 <?php if(@$this->session->flashdata('error_data_name')){ echo "has-error"; } ?>">
																	<span class="input-group-addon" id=""><i class="fa fa-pencil-square-o"></i></span>  
																	<input type="text" class="form-control m-bottom-10 data_name" id="data_name" name="data_name" placeholder="Data Name" value="<?php if(@$this->session->flashdata("data_name")){ echo $this->session->flashdata("data_name"); } ?>">
																</div>
															</div>


															<div class="col-lg-4 col-xs-6" id="">	
																<div class="input-group m-bottom-10 <?php if(@$this->session->flashdata('error_data_amount')){ echo "has-error"; } ?>">
																	<span class="input-group-addon" id=""><strong>$</strong></span>      		
																	<input type="text" class="form-control m-bottom-10 data_name number_format" id="data_amount" name="data_amount" placeholder="Total Amount" value="<?php if(@$this->session->flashdata("data_amount")){ echo $this->session->flashdata("data_amount"); } ?>">
																	<span class="input-group-addon" id=""><strong>EX-GST</strong></span> 
																</div>
															</div>

															<div class="clearfix"></div>
															<hr class="block m-bottom-15 m-top-5">

															<strong class="m-bottom-10 block">Monthly Breakdown</strong>

															<div class="row pad-0-imp no-m-imp monthly_breakdown">

																<div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 clearfix ">
																	<div class="input-group m-bottom-10">
																		<span class="input-group-addon">Jan</span>      		
																		<input type="text" class="form-control number_format" id="jan" name="jan" value="<?php if(@$this->session->flashdata("jan")){ echo $this->session->flashdata("jan"); } ?>">
																	</div>
																</div>

																<div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 clearfix ">
																	<div class="input-group m-bottom-10">
																		<span class="input-group-addon">Feb</span>      		
																		<input type="text" class="form-control number_format" id="feb" name="feb" value="<?php if(@$this->session->flashdata("feb")){ echo $this->session->flashdata("feb"); } ?>">
																	</div>
																</div>

																<div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 clearfix ">
																	<div class="input-group m-bottom-10">
																		<span class="input-group-addon">Mar</span>      		
																		<input type="text" class="form-control number_format" id="mar" name="mar" value="<?php if(@$this->session->flashdata("mar")){ echo $this->session->flashdata("mar"); } ?>">
																	</div>
																</div>


																<div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 clearfix ">
																	<div class="input-group m-bottom-10">
																		<span class="input-group-addon">Apr</span>      		
																		<input type="text" class="form-control number_format" id="apr" name="apr" value="<?php if(@$this->session->flashdata("apr")){ echo $this->session->flashdata("apr"); } ?>">
																	</div>
																</div>

																<div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 clearfix ">
																	<div class="input-group m-bottom-10">
																		<span class="input-group-addon" >May</span>      		
																		<input type="text" class="form-control number_format" id="may" name="may" value="<?php if(@$this->session->flashdata("may")){ echo $this->session->flashdata("may"); } ?>">
																	</div>
																</div>

																<div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 clearfix ">
																	<div class="input-group m-bottom-10">
																		<span class="input-group-addon" >Jun</span>      		
																		<input type="text" class="form-control number_format" id="jun" name="jun" value="<?php if(@$this->session->flashdata("jun")){ echo $this->session->flashdata("jun"); } ?>">
																	</div>
																</div>
															
																<div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 clearfix ">
																	<div class="input-group m-bottom-10">
																		<span class="input-group-addon">Jul</span>      		
																		<input type="text" class="form-control number_format" id="jul" name="jul" value="<?php if(@$this->session->flashdata("jul")){ echo $this->session->flashdata("jul"); } ?>">
																	</div>
																</div>

																<div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 clearfix ">
																	<div class="input-group m-bottom-10">
																		<span class="input-group-addon">Aug</span>      		
																		<input type="text" class="form-control number_format" id="aug" name="aug" value="<?php if(@$this->session->flashdata("aug")){ echo $this->session->flashdata("aug"); } ?>">
																	</div>
																</div>

																<div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 clearfix ">
																	<div class="input-group m-bottom-10">
																		<span class="input-group-addon">Sep</span>      		
																		<input type="text" class="form-control number_format" id="sep" name="sep" value="<?php if(@$this->session->flashdata("sep")){ echo $this->session->flashdata("sep"); } ?>">
																	</div>
																</div>

																<div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 clearfix ">
																	<div class="input-group m-bottom-10">
																		<span class="input-group-addon">Oct</span>      		
																		<input type="text" class="form-control number_format" id="oct" name="oct" value="<?php if(@$this->session->flashdata("oct")){ echo $this->session->flashdata("oct"); } ?>">
																	</div>
																</div>

																<div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 clearfix ">
																	<div class="input-group m-bottom-10">
																		<span class="input-group-addon">Nov</span>      		
																		<input type="text" class="form-control number_format" id="nov" name="nov" value="<?php if(@$this->session->flashdata("nov")){ echo $this->session->flashdata("nov"); } ?>">
																	</div>
																</div>

																<div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 clearfix ">
																	<div class="input-group m-bottom-10">
																		<span class="input-group-addon">Dec</span>      		
																		<input type="text" class="form-control number_format" id="dec" name="dec" value="<?php if(@$this->session->flashdata("dec")){ echo $this->session->flashdata("dec"); } ?>">
																	</div>
																</div>

															</div>
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
														<strong class="m-bottom-10 block">Stored Data</strong>
														<table id="dataTable_noCustom" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
															<thead> <tr> <th>Data Name</th> <th>Type</th><th>Year</th>  <th>Company</th> <th>Total</th></tr> </thead>
															<tbody>
																<?php foreach ($stored_revenue_forecast as $key => $value): ?>
																	<tr id="<?php echo $value->revenue_forecast_id; ?>">
																		<td><a href="#" class="stored_rfc" id="<?php echo number_format($value->jan_breakdown,2).'-'.number_format($value->feb_breakdown,2).'-'.number_format($value->mar_breakdown,2).'-'.number_format($value->apr_breakdown,2).'-'.number_format($value->may_breakdown,2).'-'.number_format($value->jun_breakdown,2).'-'.number_format($value->jul_breakdown,2).'-'.number_format($value->aug_breakdown,2).'-'.number_format($value->sep_breakdown,2).'-'.number_format($value->oct_breakdown,2).'-'.number_format($value->nov_breakdown,2).'-'.number_format($value->dec_breakdown,2); ?>"><?php echo $value->data_name; ?></a></td>
																		<td><?php echo $value->data_type; ?></td>
																		<td><?php echo $value->year; ?></td>
																		<td><?php echo $value->company_name; ?></td>
																		<td><?php echo number_format($value->total_amount,2); ?></td>
																	</tr>
																<?php endforeach; ?>
															</tbody>
														</table>
													</div>
												</div>   


											</div>									
										</div>
									</div>
								</div>
							</div>

						<?php endif; ?>

						<!-- ************************ -->




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
						
						<div class="clearfix"></div>
						<!-- ************************ -->


						<!-- ************************ -->						
						
						<div class="col-md-12 col-sm-12 col-xs-12 box-widget pad-10">
							<div class="widget wid-type-0 widg-head-styled">
								<div class="reload-widget-icon pull-right m-top-8 m-right-10 m-left-5 hide hidden"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head box-widg-head pad-5 clearfix">
									<strong>Sales Forecast</strong>
									<div class="pull-right" style="margin: -5px -10px -8px;">
										<select class="form-control sf_chart_dateSelection" style="height: 31px;    border-radius: 0;    border: none;    font-weight: bold;">
											<option selected="" value="" style="display:none;">Select Year</option>
											<?php $year = 2015; for($i=0; $i < 6; $i++){
												echo '<option value="'.($year+$i-1).'-'.($year+$i).'">July '.($year+$i-1).' - June '.($year+$i).'</option>';
											}?>
										</select>
										<script type="text/javascript"> $('select.sf_chart_dateSelection').val('<?php echo $minYear."-".$maxYear; ?>');</script>
									</div>
								</div>

								<div class="box-area clearfix row pad-right-10 pad-left-10">									
									<div class="widg-content col-md-9 col-sm-12 col-xs-12 clearfix">
										<div class="loading_chart" style="height: 320px;    text-align: center;    padding: 100px 53px;    color: #ccc;"><i class="fa fa-spin fa-refresh fa-4x"></i></div>
										<div class id="job_book_area">
										<div id="chart"></div></div>
										<hr class="block m-bottom-10 m-top-5">
										
										<button class="btn btn-warning btn-sm" id="visible_forecast" onclick="funcc(this)" ><i class="fa fa-exchange"></i> Sales Forecast</button>	
										<button class="btn btn-primary btn-sm m-left-5" onclick="funca()" ><i class="fa fa-exchange"></i> Project Manager Shares</button>
										<button class="btn btn-sm m-left-5" onclick="funcb()" ><i class="fa fa-exchange"></i> Focus Sales</button>
										<button class="btn btn-info btn-sm pull-right m-right-10" onclick='print_job_book();'><i class="fa fa-print"></i> Print</button>				
									</div>
									<div class="widg-content col-md-3 col-sm-12 col-xs-12 clearfix">
										<div class="loading_chart" style="height: 320px;    text-align: center;    padding: 100px 53px;    color: #ccc;"><i class="fa fa-spin fa-refresh fa-4x"></i></div>								

										<div class="clearfix row pad-0-imp no-m-imp">
											<div class="col-md-12 col-sm-6 col-xs-12" id="donut_a"></div>
											<div class="col-md-12 col-sm-6 col-xs-12" id="donut_b"></div>   
										</div>  
							
										<hr class="block m-bottom-10 m-top-5">
										<p class="text-center"><strong>WA</strong>: $86,584,365.00 &nbsp;  &nbsp;  &nbsp;  &nbsp; <strong>NSW</strong>: $10,256,544.00</p>										
									</div>
								</div>



							</div>
						</div>

						
						<div class="clearfix"></div>


						<!-- ************************ -->



						<!-- ************************ -->


						<div class="col-md-7 col-sm-12 col-xs-12 box-widget pad-10">
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


						<div class="col-md-5 col-sm-12 col-xs-12 box-widget pad-10">
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
          ['x', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],

			<?php // echo $this->dashboard->_fetch_pm_data($minYear,$maxYear); ?>

			<?php foreach ($revenue_forecast as $key => $forecast): ?>


				<?php if($forecast->data_type == 'Forecast'): ?>

	          		<?php $segment_a = $forecast->total_amount*($forecast->jul_breakdown/100).','.$forecast->total_amount*($forecast->aug_breakdown/100).','.$forecast->total_amount*($forecast->sep_breakdown/100).','.$forecast->total_amount*($forecast->oct_breakdown/100).','.$forecast->total_amount*($forecast->nov_breakdown/100).','.$forecast->total_amount*($forecast->dec_breakdown/100); ?>
	          		<?php $segment_b = $forecast->total_amount*($forecast->jan_breakdown/100).','.$forecast->total_amount*($forecast->feb_breakdown/100).','.$forecast->total_amount*($forecast->mar_breakdown/100).','.$forecast->total_amount*($forecast->apr_breakdown/100).','.$forecast->total_amount*($forecast->may_breakdown/100).','.$forecast->total_amount*($forecast->jun_breakdown/100); ?>

				<?php else: ?>

	          		<?php $segment_a = $forecast->jul_breakdown.','.$forecast->aug_breakdown.','.$forecast->sep_breakdown.','.$forecast->oct_breakdown.','.$forecast->nov_breakdown.','.$forecast->dec_breakdown; ?>
	          		<?php $segment_b = $forecast->jan_breakdown.','.$forecast->feb_breakdown.','.$forecast->mar_breakdown.','.$forecast->apr_breakdown.','.$forecast->may_breakdown.','.$forecast->jun_breakdown; ?>

	          	<?php endif; ?>

	          	['<?php echo $forecast->data_name; ?>', <?php echo $segment_a.','.$segment_b; ?>],
	      	<?php endforeach; ?>

        ],
        selection: {enabled: true},
        type: 'bar',
        types: {<?php foreach ($revenue_forecast as $key => $forecast): ?><?php if($forecast->data_type == 'Forecast'){ echo "'$forecast->data_name' : 'line',"; } ?><?php endforeach; ?>
        },
        groups: [ 

        
        	[
	        	<?php $group_a = 0;  ?>
	        	<?php foreach ($revenue_forecast as $key => $forecast): ?>
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
	        	<?php endforeach; ?>
	        ],

        	[
	        	<?php $group = 0;  ?>
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
	        ],

      	// ['Alan Liddell','Trevor Gamble'],
        // ['Stuart Hubrich','Maintenance Manager NSW'],
        ],
        order: 'asc' 
      },
    tooltip: {
        grouped: true // false // Default true
    },
             bindto: "#chart",
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
// chart.hide(['Alan Liddell','Krzysztof Kiezun','Maintenance Manager WA','Pyi Paing Aye Win','Trevor Gamble','Stuart Hubrich','Maintenance Manager NSW']);



function funca(){
  chart.hide(['Focus WA', 'Focus NSW', 'Alan Liddell','Krzysztof Kiezun','Maintenance Manager WA','Pyi Paing Aye Win','Trevor Gamble','Stuart Hubrich','Maintenance Manager NSW']);

  setTimeout(function () {
    chart.show(['Alan Liddell','Krzysztof Kiezun','Maintenance Manager WA','Pyi Paing Aye Win','Trevor Gamble','Stuart Hubrich','Maintenance Manager NSW']);
  }, 500);
}


function funcb(){
  chart.hide(['Focus WA', 'Focus NSW', 'Alan Liddell','Krzysztof Kiezun','Maintenance Manager WA','Pyi Paing Aye Win','Trevor Gamble','Stuart Hubrich','Maintenance Manager NSW']);
  setTimeout(function () {
   chart.show(['Focus WA', 'Focus NSW']);
 }, 500);
}



function funcc(element_obj){

	var forecast_display = element_obj.getAttribute("id");

	if(forecast_display == 'visible_forecast'){
		chart.hide(['Forecast NSW','Forecast WA']);		
		element_obj.setAttribute("id", "hidden_forecast");
	}else{
		chart.show(['Forecast NSW','Forecast WA']);
		element_obj.setAttribute("id", "visible_forecast");
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