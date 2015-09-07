<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>

<!-- title bar -->
<div class="container-fluid head-control">
	<div class="container-fluid">
		<div class="row">

			<div class="col-md-6 col-sm-4 col-xs-12 pull-left">
				<header class="page-header">
					<h3><?php $datestring = "%l, %F %d, %Y"; $time = time(); //use time() for timestamp  ?>
						<?php echo $screen; ?> Screen<br><small><?php echo mdate($datestring, $time); #echo date("l, F d, Y"); ?></small>
					</h3>
				</header>
			</div>

			<div class="page-nav-options col-md-6 col-sm-8 col-xs-12 pull-right hidden-xs">
				<ul class="nav nav-tabs navbar-right">
					<li>
						<a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Home</a>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>admin" class="btn-small">Defaults</a>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>admin/company" class="btn-small">Company</a>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>users" class="btn-small">Users</a>
					</li>
					<!-- <li>
						<a href="" class="btn-small"><i class="fa fa-magic"></i> Tour</a>
					</li> -->
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

				<div class="row hidden">
					<div class="col-xs-12">
						<div class="border-less-box alert alert-danger fade in">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h4>Oh snap! You got an error!</h4>
							<p>Change this and that and try again. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.</p>
							<p>
								<button type="button" class="btn btn-danger" id="loading-example-btn"  data-loading-text="Loading..." >Take this action</button>
								<button type="button" class="btn btn-default">Or do this</button>
							</p>
						</div>
					</div>
				</div>

				<div class="row">
					
					<div class="col-md-9">
						<div class="left-section-box">
							<div class="box-head pad-10 clearfix">
								<label><?php echo $screen; ?></label><span> (<a href="#" data-placement="right" class="popover-test" title="" data-content="Hello there mate! Welcome to the clients screen." data-original-title="Welcome">?</a>)</span>
								<p><a href="#" class="tooltip-test" title="Tooltip">This link</a> and <a href="#" class="tooltip-test" title="Tooltip">that link</a> should have tooltips on hover.</p>								
							</div>
							<div class="box-area pad-10">

								<?php if(@$matrix_errors): ?>
									<div class="no-pad-t m-bottom-10">
										<div class="border-less-box alert alert-danger fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>Oh snap! You got an error!</h4>
											<?php echo $matrix_errors;?>
										</div>
									</div>
								<?php endif; ?>


								<?php if(@$this->session->flashdata('update_matrix')): ?>
									<div class="no-pad-t m-bottom-10">
										<div class="border-less-box alert alert-success fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>Cheers!</h4>
											<?php echo $this->session->flashdata('update_matrix');?>
										</div>
									</div>
								<?php endif; ?>


								<form class="form-horizontal" role="form" method="post" action="<?php echo current_url(); ?>/matrix">
									<div class="box m-top-0">
										<div class="box-head pad-5 m-bottom-5">
											<label><i class="fa fa-line-chart fa-lg"></i> Cost Matrix</label>
										</div>

										<div class="box-area pad-5 clearfix">
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('total_days')){ echo 'has-error';} ?>">
												<label for="total_days" class="col-sm-3 control-label">Total Days</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="total_days" name="total_days" placeholder="Total Days" value="<?php echo ($this->input->post('total_days') ?  $this->input->post('total_days') : $total_days ); ?>">
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('rate')){ echo 'has-error';} ?>">
												<label for="rate" class="col-sm-3 control-label">Hour Rate</label>
												<div class="col-sm-9">
													<div class="input-group">
														<span class="input-group-addon" id="">$</span>
														<input type="text" class="form-control" id="rate" name="rate" placeholder="Rate ($)" value="<?php echo ($this->input->post('rate') ?  $this->input->post('rate') : $rate ); ?>">
													</div>
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('hours')){ echo 'has-error';} ?>">
												<label for="hours" class="col-sm-3 control-label">Total Hours</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="hours" name="hours" placeholder="Hours" value="<?php echo ($this->input->post('hours') ?  $this->input->post('hours') : $hours ); ?>">
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('superannuation')){ echo 'has-error';} ?>">
												<label for="superannuation" class="col-sm-3 control-label">Superannuation</label>
												<div class="col-sm-9">
													<div class="input-group">
														<span class="input-group-addon" id="">%</span>
														<input type="text" class="form-control" id="superannuation" name="superannuation" placeholder="Superannuation" value="<?php echo ($this->input->post('superannuation') ?  $this->input->post('superannuation') : $super_annuation ); ?>">
													</div>
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('workers-comp')){ echo 'has-error';} ?>">
												<label for="workers-comp" class="col-sm-3 control-label">Workers Comp</label>
												<div class="col-sm-9">
													<div class="input-group">
														<span class="input-group-addon" id="">%</span>
														<input type="text" class="form-control" id="workers-comp" name="workers-comp" placeholder="Workers Comp" value="<?php echo ($this->input->post('workers-comp') ?  $this->input->post('workers-comp') : $worker_compensation ); ?>">
													</div>
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('public-holidays')){ echo 'has-error';} ?>">
												<label for="public-holidays" class="col-sm-3 control-label">Public Holidays</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="public-holidays" name="public-holidays" placeholder="Public Holidays" value="<?php echo ($this->input->post('public-holidays') ?  $this->input->post('public-holidays') : $public_holidays ); ?>">
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('rdos')){ echo 'has-error';} ?>">
												<label for="rdos" class="col-sm-3 control-label">RDO's</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="rdos" name="rdos" placeholder="RDO's" value="<?php echo ($this->input->post('rdos') ?  $this->input->post('rdos') : $rdos ); ?>">
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('sick-leave')){ echo 'has-error';} ?>">
												<label for="sick-leave" class="col-sm-3 control-label">Sick Leave</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="sick-leave" name="sick-leave" placeholder="Sick Leave" value="<?php echo ($this->input->post('sick-leave') ?  $this->input->post('sick-leave') : $sick_leave ); ?>">
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('carers-leave')){ echo 'has-error';} ?>">
												<label for="carers-leave" class="col-sm-3 control-label">Carers Leave</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="carers-leave" name="carers-leave" placeholder="Carers Leave" value="<?php echo ($this->input->post('carers-leave') ?  $this->input->post('carers-leave') : $carers_leave ); ?>">
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('annual-leave')){ echo 'has-error';} ?>">
												<label for="annual-leave" class="col-sm-3 control-label">Annual Leave</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="annual-leave" name="annual-leave" placeholder="Annual Leave" value="<?php echo ($this->input->post('annual-leave') ?  $this->input->post('annual-leave') : $annual_leave ); ?>">
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('downtime')){ echo 'has-error';} ?>">
												<label for="downtime" class="col-sm-3 control-label">Downtime</label>
												<div class="col-sm-9">
													<div class="input-group">
														<span class="input-group-addon" id="">%</span>
														<input type="text" class="form-control" id="downtime" name="downtime" placeholder="Downtime" value="<?php echo ($this->input->post('downtime') ?  $this->input->post('downtime') : $down_time ); ?>">
													</div>
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('leave-loading')){ echo 'has-error';} ?>">
												<label for="leave-loading" class="col-sm-3 control-label">Leave Loading</label>
												<div class="col-sm-9">
													<div class="input-group">
														<span class="input-group-addon" id="">%</span>
														<input type="text" class="form-control" id="leave-loading" name="leave-loading" placeholder="Leave Loading" value="<?php echo ($this->input->post('leave-loading') ?  $this->input->post('leave-loading') : $leave_loading ); ?>">
													</div>
												</div>
											</div>

										</div>
									</div>
								


									<div class="m-top-15 clearfix">
								    	<div>
								        	<button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Save Cost Matrix</button>
								        </div>
								    </div>
								</form>

								<p>&nbsp;</p>


								<?php if(@$cost_labour_matrix_errors): ?>
									<div class="no-pad-t">
										<div class="border-less-box alert alert-danger fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>Oh snap! You got an error!</h4>
											<?php echo $cost_labour_matrix_errors;?>
										</div>
									</div>
								<?php endif; ?>


								<?php if(@$this->session->flashdata('update_labour_cost_matrix')): ?>
									<div class="no-pad-t">
										<div class="border-less-box alert alert-success fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>Cheers!</h4>
											<?php echo $this->session->flashdata('update_labour_cost_matrix');?>
										</div>
									</div>
								<?php endif; ?>

								<form class="form-horizontal" role="form" method="post" action="<?php echo current_url(); ?>/labour_cost_matrix">

									<div class="box">
										<div class="box-head pad-5 m-bottom-5">
											<label><i class="fa fa-ticket fa-lg"></i> Site Labour On Cost Matrix</label>
										</div>

										<div class="box-area pad-5 clearfix">

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('superannuation')){ echo 'has-error';} ?>">
												<label for="superannuation" class="col-sm-4 control-label">Superannuation</label>
												<div class="col-sm-8">
													<div class="input-group">
														<span class="input-group-addon">%</span>
														<input type="text" class="form-control" placeholder="Superannuation (%)" id="superannuation" name="superannuation" value="<?php echo ($this->input->post('superannuation') ?  $this->input->post('superannuation') : $superannuation ); ?>">
													</div>
												</div>	
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('workers_compensation')){ echo 'has-error';} ?>">
												<label for="workers_compensation" class="col-sm-4 control-label">Workers Compensation</label>
												<div class="col-sm-8">
													<div class="input-group">
														<span class="input-group-addon">%</span>
														<input type="text" class="form-control" placeholder="Workers Compensation (%)" id="workers_compensation" name="workers_compensation" value="<?php echo ($this->input->post('workers_compensation') ?  $this->input->post('workers_compensation') : $workers_compensation ); ?>">
													</div>
												</div>	
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('payroll_tax')){ echo 'has-error';} ?>">
												<label for="payroll_tax" class="col-sm-4 control-label">Payroll Tax</label>
												<div class="col-sm-8">
													<div class="input-group">
														<span class="input-group-addon">%</span>
														<input type="text" class="form-control" placeholder="Payroll Tax (%)" id="payroll_tax" name="payroll_tax" value="<?php echo ($this->input->post('payroll_tax') ?  $this->input->post('payroll_tax') : $payroll_tax ); ?>">
													</div>
												</div>	
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('leave_loading')){ echo 'has-error';} ?>">
												<label for="leave_loading" class="col-sm-4 control-label">Leave Loading</label>
												<div class="col-sm-8">
													<div class="input-group">
														<span class="input-group-addon">%</span>
														<input type="text" class="form-control" placeholder="Leave Loading (%)" id="leave_loading" name="leave_loading" value="<?php echo ($this->input->post('leave_loading') ?  $this->input->post('leave_loading') : $leave_loading ); ?>">
													</div>
												</div>	
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('other')){ echo 'has-error';} ?>">
												<label for="other" class="col-sm-4 control-label">Other</label>
												<div class="col-sm-8">
													<div class="input-group">
														<span class="input-group-addon">%</span>
														<input type="text" class="form-control" placeholder="Other (%)" id="other" name="other" value="<?php echo ($this->input->post('other') ?  $this->input->post('other') : $other ); ?>">
													</div>
												</div>	
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('total_leave_days')){ echo 'has-error';} ?>">
												<label for="total_leave_days" class="col-sm-4 control-label">Total Leave Days</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" placeholder="Total Leave Days" id="total_leave_days" name="total_leave_days" value="<?php echo ($this->input->post('total_leave_days') ?  $this->input->post('total_leave_days') : $total_leave_days ); ?>">
													
												</div>	
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('total_work_days')){ echo 'has-error';} ?>">
												<label for="total_work_days" class="col-sm-4 control-label">Total Work Days</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" placeholder="Total Work Days" id="total_work_days" name="total_work_days" value="<?php echo ($this->input->post('total_work_days') ?  $this->input->post('total_work_days') : $total_work_days ); ?>">
													
												</div>	
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('total_work_days')){ echo 'has-error';} ?>">
												<div class="col-sm-12">
													<div class="input-group">
														<span class="input-group-addon"  style="padding: 9px;">Leave Percentage: <strong class="leave_percentage"><?php echo $leave_percentage; ?>%</strong></span>
														<span class="input-group-addon"  style="padding: 9px;">Grand Total: <strong class"grand_total"><?php echo $grand_total; ?>%</strong></span>
													</div>
												</div>	
											</div>

											
										</div>
									</div>

									<div class="m-top-15 clearfix">
								    	<div>
								        	<button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Save Labour Matrix</button>
								        </div>
								    </div>	

							    </form>


								<p>&nbsp;</p>



								<?php if(@$default_errors): ?>
									<div class="no-pad-t">
										<div class="border-less-box alert alert-danger fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>Oh snap! You got an error!</h4>
											<?php echo $default_errors;?>
										</div>
									</div>
								<?php endif; ?>


								<?php if(@$this->session->flashdata('update_default')): ?>
									<div class="no-pad-t">
										<div class="border-less-box alert alert-success fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>Cheers!</h4>
											<?php echo $this->session->flashdata('update_default');?>
										</div>
									</div>
								<?php endif; ?>

								<form class="form-horizontal" role="form" method="post" action="<?php echo current_url(); ?>/defaults">

									<div class="box">
										<div class="box-head pad-5 m-bottom-5">
											<label><i class="fa fa-cog fa-lg"></i> Defaults</label>
										</div>

										<div class="box-area pad-5 clearfix">

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('time-half')){ echo 'has-error';} ?>">
												<label for="time-half" class="col-sm-4 control-label">Time &amp; Half Labour</label>
												<div class="col-sm-8">
													<div class="input-group">
														<span class="input-group-addon">%</span>
														<input type="text" class="form-control" placeholder="Time &amp; Half (%)" id="time-half" name="time-half" value="<?php echo ($this->input->post('time-half') ?  $this->input->post('time-half') : $labor_split_time_and_half ); ?>">
													</div>
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('double-time')){ echo 'has-error';} ?>">
												<label for="double-time" class="col-sm-4 control-label">Double Time</label>
												<div class="col-sm-8">
													<div class="input-group">
														<span class="input-group-addon">%</span>
														<input type="text" class="form-control" id="double-time" name="double-time" placeholder="Double Time (%)"  value="<?php echo ($this->input->post('double-time') ?  $this->input->post('double-time') : $labor_split_double_time ); ?>">
													</div>
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('standard-labour')){ echo 'has-error';} ?>">
												<label for="standard-labour" class="col-sm-4 control-label">Standard Labour</label>
												<div class="col-sm-8">
													<div class="input-group">
														<span class="input-group-addon standard-labour text-left" style="padding: 9px;">% <?php echo ($this->input->post('standard-labour') ?  $this->input->post('standard-labour') : $labor_split_standard ); ?></span>
														<input type="hidden" class="form-control" id="standard-labour" readonly="readonly" name="standard-labour" placeholder="Standard Labour (%)"  value="<?php echo ($this->input->post('standard-labour') ?  $this->input->post('standard-labour') : $labor_split_standard ); ?>">
														<span class="input-group-addon" style="padding: 9px;"></span>
													</div>
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('installation-labour')){ echo 'has-error';} ?>">
												<label for="installation-labour" class="col-sm-4 control-label">Installation Labour</label>
												<div class="col-sm-8">
													<div class="input-group">
														<span class="input-group-addon">Markup %</span>
														<input type="text" class="form-control" id="installation-labour" name="installation-labour" placeholder="Installation Labour (%)"  value="<?php echo ($this->input->post('installation-labour') ?  $this->input->post('installation-labour') : $installation_labour_mark_up ); ?>">
													</div>
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('gst-rate')){ echo 'has-error';} ?>">
												<label for="gst-rate" class="col-sm-4 control-label">GST Rate</label>
												<div class="col-sm-8">
													<div class="input-group">
														<span class="input-group-addon">%</span>
														<input type="text" class="form-control" id="gst-rate" name="gst-rate" placeholder="GST Rate (%)"  value="<?php echo ($this->input->post('gst-rate') ?  $this->input->post('gst-rate') : $gst_rate ); ?>">
													</div>
												</div>
											</div>
											
										</div>
									</div>

									<div class="m-top-15 clearfix">
								    	<div>
								        	<button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Save Defaults</button>
								        </div>
								    </div>	

							    </form>


								<p>&nbsp;</p>




								<?php if(@$this->session->flashdata('update_prj_mrk')): ?>
									<div class="no-pad-t">
										<div class="border-less-box alert alert-success fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>Cheers!</h4>
											<?php echo $this->session->flashdata('update_prj_mrk');?>
										</div>
									</div>
								<?php endif; ?>

									<form method="post" action="<?php echo current_url(); ?>/project_mark_up">
								<div class="box">

									<div class="box-head pad-5 m-bottom-5">
										<label><i class="fa fa-calculator fa-lg"></i> Category Mark-Up</label>
									</div>

									<div class="box-area pad-5 clearfix">
										<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix ">
											<label for="kiosk" class="col-sm-4 control-label">Kiosk (%)</label>

											<div class="col-sm-4">
												<div class="input-group">
													<span class="input-group-addon" id="kiosk">Default</span>
													<input type="text" class="form-control" placeholder="Kiosk" id="kiosk" name="kiosk" value="<?php echo ($this->input->post('kiosk') ?  $this->input->post('kiosk') : $kiosk ); ?>">
												</div>
											</div>

											<div class="col-sm-4">
												<div class="input-group">
													<span class="input-group-addon" id="min_kiosk">Min</span>
													<input type="text" class="form-control" placeholder="Min" id="min_kiosk" name="min_kiosk" value="<?php echo ($this->input->post('min_kiosk') ?  $this->input->post('min_kiosk') : $min_kiosk ); ?>">
												</div>
											</div>

										</div>

										<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix ">
											<label for="input" class="col-sm-4 control-label">Full fitout (%)</label>

											<div class="col-sm-4">
												<div class="input-group">
													<span class="input-group-addon" id="full-fitout">Default</span>
													<input type="text" class="form-control" placeholder="Full fitout" id="full-fitout" name="full-fitout" value="<?php echo ($this->input->post('full-fitout') ?  $this->input->post('full-fitout') : $full_fitout ); ?>">
												</div>
											</div>

											<div class="col-sm-4">
												<div class="input-group">
													<span class="input-group-addon" id="min_full_fitout">Min</span>
													<input type="text" class="form-control" placeholder="Min" id="min_full_fitout" name="min_full_fitout" value="<?php echo ($this->input->post('min_full_fitout') ?  $this->input->post('min_full_fitout') : $min_full_fitout ); ?>">
												</div>
											</div>
										</div>

										<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix ">
											<label for="input" class="col-sm-4 control-label">Refurbishment (%)</label>

											<div class="col-sm-4">
												<div class="input-group">
													<span class="input-group-addon" id="refurbishment">Default</span>
													<input type="text" class="form-control" placeholder="Refurbishment" id="refurbishment" name="refurbishment" value="<?php echo ($this->input->post('refurbishment') ?  $this->input->post('refurbishment') : $refurbishment ); ?>">
												</div>
											</div>

											<div class="col-sm-4">
												<div class="input-group">
													<span class="input-group-addon" id="min_refurbishment">Min</span>
													<input type="text" class="form-control" placeholder="Min" id="min_refurbishment" name="min_refurbishment" value="<?php echo ($this->input->post('min_refurbishment') ?  $this->input->post('min_refurbishment') : $min_refurbishment ); ?>">
												</div>
											</div>

										</div>

										<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix ">
											<label for="input" class="col-sm-4 control-label">Stripout (%)</label>
											
											<div class="col-sm-4">
												<div class="input-group">
													<span class="input-group-addon" id="stripout">Default</span>
													<input type="text" class="form-control" placeholder="Stripout" id="stripout" name="stripout" value="<?php echo ($this->input->post('stripout') ?  $this->input->post('stripout') : $stripout ); ?>">
												</div>
											</div>

											<div class="col-sm-4">
												<div class="input-group">
													<span class="input-group-addon" id="min_stripout">Min</span>
													<input type="text" class="form-control" placeholder="Min" id="min_stripout" name="min_stripout" value="<?php echo ($this->input->post('min_stripout') ?  $this->input->post('min_stripout') : $min_stripout ); ?>">
												</div>
											</div>
										</div>


										<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix ">
											<label for="input" class="col-sm-4 control-label">Maintenance (%)</label>
											
											<div class="col-sm-4">
												<div class="input-group">
													<span class="input-group-addon" id="maintenance">Default</span>
													<input type="text" class="form-control" placeholder="Stripout" id="maintenance" name="maintenance" value="<?php echo ($this->input->post('maintenance') ?  $this->input->post('maintenance') : $maintenance ); ?>">
												</div>
											</div>

											<div class="col-sm-4">
												<div class="input-group">
													<span class="input-group-addon" id="min_maintenance">Min</span>
													<input type="text" class="form-control" placeholder="Min" id="min_maintenance" name="min_maintenance" value="<?php echo ($this->input->post('min_maintenance') ?  $this->input->post('min_maintenance') : $min_maintenance ); ?>">
												</div>
											</div>
										</div>

										<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix ">
											<label for="input" class="col-sm-4 control-label">Minor Works (%)</label>
																						
											<div class="col-sm-4">
												<div class="input-group">
													<span class="input-group-addon" id="minor-works">Default</span>
													<input type="text" class="form-control" placeholder="Stripout" id="minor-works" name="minor-works" value="<?php echo ($this->input->post('minor-works') ?  $this->input->post('minor-works') : $minor_works ); ?>">
												</div>
											</div>

											<div class="col-sm-4">
												<div class="input-group">
													<span class="input-group-addon" id="min_minor_works">Min</span>
													<input type="text" class="form-control" placeholder="Min" id="min_minor_works" name="min_minor_works" value="<?php echo ($this->input->post('min_minor_works') ?  $this->input->post('min_minor_works') : $min_minor_works ); ?>">
												</div>
											</div>
										</div>
									</div>
								</div>	

								        <button type="submit" class="btn btn-success m-top-15"><i class="fa fa-floppy-o"></i> Save Mark-Up</button>
								    </form>		

								    <!-- Notes Defaults -->
								    <p>&nbsp;</p>



								<?php if(@$default_errors): ?>
									<div class="no-pad-t">
										<div class="border-less-box alert alert-danger fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>Oh snap! You got an error!</h4>
											<?php echo $default_errors;?>
										</div>
									</div>
								<?php endif; ?>


								<?php if(@$this->session->flashdata('update_default')): ?>
									<div class="no-pad-t">
										<div class="border-less-box alert alert-success fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>Cheers!</h4>
											<?php echo $this->session->flashdata('update_default');?>
										</div>
									</div>
								<?php endif; ?>

								<form class="form-horizontal" role="form" method="post" action="<?php echo current_url(); ?>/default_notes">

									<div class="box">
										<div class="box-head pad-5 m-bottom-5">
											<label><i class="fa fa-pencil-square-o fa-lg"></i> Default Notes</label>
										</div>

										<div class="box-area pad-5 clearfix">

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('time-half')){ echo 'has-error';} ?>">
												<label for="time-half" class="col-sm-4 control-label">CQR Notes with Insurance</label>
												<div class="col-sm-8">
													<textarea class="form-control" id="cqr_notes_w_ins" name="cqr_notes_w_ins" style = "height: 60px " maxlength="90"><?php echo ($this->input->post('cqr_notes_w_ins') ?  $this->input->post('cqr_notes_w_ins') : $cqr_notes_w_insurance ); ?></textarea>
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('double-time')){ echo 'has-error';} ?>">
												<label for="double-time" class="col-sm-4 control-label">CQR Notes No Insurance</label>
												<div class="col-sm-8">
													<textarea class="form-control" id="cqr_notes_no_ins" name="cqr_notes_no_ins" style = "height: 60px " maxlength="90"><?php echo ($this->input->post('cqr_notes_no_ins') ?  $this->input->post('cqr_notes_no_ins') : $cqr_notes_no_insurance ); ?></textarea>
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('time-half')){ echo 'has-error';} ?>">
												<label for="time-half" class="col-sm-4 control-label">CPO Notes with Insurance</label>
												<div class="col-sm-8">
													<textarea class="form-control" id="cpo_notes_w_ins" name="cpo_notes_w_ins" style = "height: 60px " maxlength="90"><?php echo ($this->input->post('cpo_notes_w_ins') ?  $this->input->post('cpo_notes_w_ins') : $cpo_notes_w_insurance ); ?></textarea>
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('double-time')){ echo 'has-error';} ?>">
												<label for="double-time" class="col-sm-4 control-label">CPO Notes No Insurance</label>
												<div class="col-sm-8">
													<textarea class="form-control" id="cpo_notes_no_ins" name="cpo_notes_no_ins" style = "height: 60px " maxlength="90"><?php echo ($this->input->post('cpo_notes_no_ins') ?  $this->input->post('cpo_notes_no_ins') : $cpo_notes_no_insurance ); ?></textarea>
												</div>
											</div>
											
										</div>
									</div>

									<div class="m-top-15 clearfix">
								    	<div>
								        	<button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Save Notes</button>
								        </div>
								    </div>	
								    
							    </form>
								    <!-- Notes Defaults -->				
							</div>
						</div>
					</div>


					<div class="col-md-3 m-top-20">
						<div class="box">
							<div class="box-head pad-5">
								<label><i class="fa fa-cube fa-lg"></i> Total Hour Rate</label>
							</div>

							<div class="box-area clearfix pad-5">
								<div class="clearfix m-top-10 m-bottom-10">
								<label for="total-hour" class="col-sm-5 control-label m-top-5" style="font-weight: normal;">Total Hour Rate</label>
									<div class="col-sm-7">
										<div class="input-group">
											<span class="input-group-addon" id="">$</span>
											<input type="text" class="form-control" readonly="readonly" id="total-hour" name="total-hour" placeholder="Total Hour"  value="<?php echo ($this->input->post('total-hour') ?  $this->input->post('total-hour') : $total_hour ); ?>">
										</div>
									</div>
								</div>

								<div class="clearfix m-top-10 m-bottom-10">
									<label for="time-half" class="col-sm-5 control-label m-top-5" style="font-weight: normal;">Time Half Rate</label>
									<div class="col-sm-7">
										<div class="input-group">
											<span class="input-group-addon" id="">$</span>
											<input type="text" class="form-control" readonly="readonly" id="time-half" name="time-half" placeholder="Total Time Half" value="<?php echo ($this->input->post('time-half') ?  $this->input->post('time-half') : $total_time_half ); ?>">
										</div>

									</div>
								</div>

								<hr />

								<div class="clearfix m-top-10 m-bottom-10">
									<label for="double-time" class="col-sm-5 control-label m-top-5" style="font-weight: normal;">Double Time Rate</label>
									<div class="col-sm-7">
										<div class="input-group">
											<span class="input-group-addon" id="">$</span>
											<input type="text" class="form-control" readonly="readonly" id="double-time" name="double-time" placeholder="Total Double Time" value="<?php echo ($this->input->post('double-time') ?  $this->input->post('double-time') : $total_double_time ); ?>">
										</div>

									</div>
								</div>

								<div class="clearfix m-top-10 m-bottom-10">
									<label for="amalgamated-rate" class="col-sm-5 control-label m-top-5" style="font-weight: normal;">Amalgamated Rate</label>
									<div class="col-sm-7">
										<div class="input-group">
											<span class="input-group-addon" id="">$</span>
											<input type="text" class="form-control" readonly="readonly" id="amalgamated-rate" name="amalgamated-rate" placeholder="Amalgamated Rate" value="<?php echo ($this->input->post('amalgamated-rate') ?  $this->input->post('amalgamated-rate') : $total_amalgamated_rate ); ?>">
										</div>

									</div>
								</div>
							</div>
						</div>
					</div>



					<div class="col-md-3 m-top-5">
						<div class="box">
							<div class="box-head pad-5">
								<label><i class="fa fa-cube fa-lg"></i> Hour Rate Cost Inc On Costs</label>
							</div>

							<div class="box-area clearfix pad-5">
								<div class="clearfix m-top-10 m-bottom-10">
									<label for="total-hour" class="col-sm-5  control-label m-top-5" style="font-weight: normal;">Total Hour </label>
									<div class="col-sm-7">
										<div class="input-group">
											<span class="input-group-addon" id="">$</span>
											<input type="text" class="form-control" readonly="readonly" value="<?php echo $gp_on_cost_total_hr; ?>">
										</div>
									</div>						
								</div>

								<div class="clearfix m-top-10 m-bottom-10">
									<label for="total-hour" class="col-sm-5  control-label m-top-5" style="font-weight: normal;">Time Half </label>
									<div class="col-sm-7">
										<div class="input-group">
											<span class="input-group-addon" id="">$</span>
											<input type="text" class="form-control" readonly="readonly" value="<?php echo $gp_on_cost_time_half_hr; ?>">
										</div>
									</div>						
								</div>

								<hr />

								<div class="clearfix m-top-10 m-bottom-10">
									<label for="total-hour" class="col-sm-5  control-label m-top-5" style="font-weight: normal;">Double Time </label>
									<div class="col-sm-7">
										<div class="input-group">
											<span class="input-group-addon" id="">$</span>
											<input type="text" class="form-control" readonly="readonly" value="<?php echo $gp_on_cost_time_double_hr; ?>">
										</div>
									</div>						
								</div>

								<div class="clearfix m-top-10 m-bottom-10">
									<label for="total-hour" class="col-sm-5  control-label m-top-5" style="font-weight: normal;">Amalgamated Rate </label>
									<div class="col-sm-7">
										<div class="input-group">
											<span class="input-group-addon" id="">$</span>
											<input type="text" class="form-control" readonly="readonly" value="<?php echo $gp_amalgamated_rate;?>">
										</div>
									</div>						
								</div>
								
							</div>
						</div>
					</div>					
					
					<div class="col-md-3 hide">						
						<div class="box">
							<div class="box-head pad-10">
								<label><i class="fa fa-history fa-lg"></i> History</label>
							</div>
							<div class="box-area pattern-sandstone pad-5">
								<div class="box-content box-list collapse in">
									<ul>
										<li>
											<div>
												<a href="#" class="news-item-title">You added a new company</a>
												<p class="news-item-preview">May 25, 2014</p>
											</div>
										</li>
										<li>
											<div>
												<a href="#" class="news-item-title">Updated the company details and contact information for James Tiling Co.</a>
												<p class="news-item-preview">May 20, 2014</p>
											</div>
										</li>
									</ul>
									<div class="box-collapse">
										<a style="cursor: pointer;" data-toggle="collapse" data-target=".more-list"> Show More </a>
									</div>
									<ul class="more-list collapse out">
										<li>
											<div>
												<a href="#" class="news-item-title">Duis aute irure dolor in reprehenderit</a>
												<p class="news-item-preview">
													Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore.
												</p>
											</div>
										</li>
										<li>
											<div>
												<a href="#" class="news-item-title">Duis aute irure dolor in reprehenderit</a>
												<p class="news-item-preview">
													Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore.
												</p>
											</div>
										</li>
										<li>
											<div>
												<a href="#" class="news-item-title">Duis aute irure dolor in reprehenderit</a>
												<p class="news-item-preview">
													Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore.
												</p>
											</div>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					
				</div>				
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('assets/logout-modal'); ?>