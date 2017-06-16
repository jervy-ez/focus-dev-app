<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>
<?php $this->load->module('site_labour'); ?>
<?php
	$curr_tab = 'admin_defaults';
	if(isset($_GET['curr_tab'])){
		$curr_tab = $_GET['curr_tab'];

	} 
?>
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
					
					<div class="col-sm-12">
						<div class="left-section-box">
							<div class="row clearfix" style="margin-bottom: -6px;">
								<div class="col-lg-6 col-md-6 col-sm-4">
									<div class="pad-left-15 clearfix">										
										<label class="h3"><?php echo $screen; ?></label>
									</div>
								</div>

								<div class="col-lg-6 col-md-6 col-sm-8">
									<div class="pad-top-15 pad-right-15 pad-bottom-5 clearfix box-tabs">	
										<ul id="myTab" class="nav nav-tabs pull-right">
											<li class="<?php echo ($curr_tab == 'admin_defaults' ? 'active' : '' ); ?>"><a href="#defaults" id="defaults_btn" role="tab" data-toggle="tab" aria-controls="defaults" aria-expanded="true"><i class="fa fa-cog fa-lg"></i> Admin Defaults</a></li>
											<li><a href="#cost_matrix" id="cost_matrix_btn" role="tab" data-toggle="tab" aria-controls="cost_matrix" aria-expanded="true"><i class="fa fa-line-chart fa-lg"></i> Cost Matrix</a></li>
											<li class="<?php echo ($curr_tab == 'users' ? 'active' : '' ); ?>"><a href="#users" id="uers_btn" role="tab" data-toggle="tab" aria-controls="users" aria-expanded="true"><i class="fa fa-users fa-lg"></i> Users</a></li>
										</ul>
									</div>
								</div>
							</div>


							<div class="box-area pad-10 border-top">

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



									<div id="myTabContent" class="tab-content">

									<div class="tab-pane  fade in" id="cost_matrix" aria-labelledby="cost_matrix">
										<!-- Cost Matrix -->

								<form class="form-horizontal"  role="form" method="post" action="<?php echo current_url(); ?>/matrix">
									<div  class=" box m-top-0" >
										<div class="box-head pad-5 m-bottom-5">
											<label><i class="fa fa-line-chart fa-lg"></i> Cost Matrix</label>
										</div>

										<div class="box-area pad-5 clearfix">
											<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('total_days')){ echo 'has-error';} ?>">
												<label for="total_days" class="col-sm-3 control-label">Total Days</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="total_days" name="total_days" placeholder="Total Days" value="<?php echo ($this->input->post('total_days') ?  $this->input->post('total_days') : $total_days ); ?>">
												</div>
											</div>

											<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('rate')){ echo 'has-error';} ?>">
												<label for="rate" class="col-sm-3 control-label">Hour Rate</label>
												<div class="col-sm-9">
													<div class="input-group">
														<span class="input-group-addon" id="">$</span>
														<input type="text" class="form-control" id="rate" name="rate" placeholder="Rate ($)" value="<?php echo ($this->input->post('rate') ?  $this->input->post('rate') : $rate ); ?>">
													</div>
												</div>
											</div>

											<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('hours')){ echo 'has-error';} ?>">
												<label for="hours" class="col-sm-3 control-label">Total Hours</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="hours" name="hours" placeholder="Hours" value="<?php echo ($this->input->post('hours') ?  $this->input->post('hours') : $hours ); ?>">
												</div>
											</div>

											<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('superannuation')){ echo 'has-error';} ?>">
												<label for="superannuation" class="col-sm-4 control-label">Superannuation</label>
												<div class="col-sm-8">
													<div class="input-group">
														<span class="input-group-addon" id="">%</span>
														<input type="text" class="form-control" id="superannuation" name="superannuation" placeholder="Superannuation" value="<?php echo ($this->input->post('superannuation') ?  $this->input->post('superannuation') : $super_annuation ); ?>">
													</div>
												</div>
											</div>

											<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('workers-comp')){ echo 'has-error';} ?>">
												<label for="workers-comp" class="col-sm-5 control-label">Workers Comp</label>
												<div class="col-sm-7">
													<div class="input-group">
														<span class="input-group-addon" id="">%</span>
														<input type="text" class="form-control" id="workers-comp" name="workers-comp" placeholder="Workers Comp" value="<?php echo ($this->input->post('workers-comp') ?  $this->input->post('workers-comp') : $worker_compensation ); ?>">
													</div>
												</div>
											</div>

											<div class="col-lg-4 col-md-6  col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('public-holidays')){ echo 'has-error';} ?>">
												<label for="public-holidays" class="col-sm-4 control-label">Public Holidays</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" id="public-holidays" name="public-holidays" placeholder="Public Holidays" value="<?php echo ($this->input->post('public-holidays') ?  $this->input->post('public-holidays') : $public_holidays ); ?>">
												</div>
											</div>

											<div class="col-lg-4 col-md-6  col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('rdos')){ echo 'has-error';} ?>">
												<label for="rdos" class="col-sm-3 control-label">RDO's</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="rdos" name="rdos" placeholder="RDO's" value="<?php echo ($this->input->post('rdos') ?  $this->input->post('rdos') : $rdos ); ?>">
												</div>
											</div>

											<div class="col-lg-4 col-md-6  col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('sick-leave')){ echo 'has-error';} ?>">
												<label for="sick-leave" class="col-sm-3 control-label">Sick Leave</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="sick-leave" name="sick-leave" placeholder="Sick Leave" value="<?php echo ($this->input->post('sick-leave') ?  $this->input->post('sick-leave') : $sick_leave ); ?>">
												</div>
											</div>

											<div class="col-lg-4 col-md-6  col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('carers-leave')){ echo 'has-error';} ?>">
												<label for="carers-leave" class="col-sm-3 control-label">Carers Leave</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="carers-leave" name="carers-leave" placeholder="Carers Leave" value="<?php echo ($this->input->post('carers-leave') ?  $this->input->post('carers-leave') : $carers_leave ); ?>">
												</div>
											</div>

											<div class="col-lg-4 col-md-6  col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('annual-leave')){ echo 'has-error';} ?>">
												<label for="annual-leave" class="col-sm-3 control-label">Annual Leave</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" id="annual-leave" name="annual-leave" placeholder="Annual Leave" value="<?php echo ($this->input->post('annual-leave') ?  $this->input->post('annual-leave') : $annual_leave ); ?>">
												</div>
											</div>

											<div class="col-lg-4 col-md-6  col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('downtime')){ echo 'has-error';} ?>">
												<label for="downtime" class="col-sm-3 control-label">Downtime</label>
												<div class="col-sm-9">
													<div class="input-group">
														<span class="input-group-addon" id="">%</span>
														<input type="text" class="form-control" id="downtime" name="downtime" placeholder="Downtime" value="<?php echo ($this->input->post('downtime') ?  $this->input->post('downtime') : $down_time ); ?>">
													</div>
												</div>
											</div>

											<div class="col-lg-4 col-md-6  col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('leave-loading')){ echo 'has-error';} ?>">
												<label for="leave-loading" class="col-sm-4 control-label">Leave Loading</label>
												<div class="col-sm-8">
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
								<!-- Cost Matrix -->



<p><hr /></p>

								<!-- Site Labour On Cost Matrix -->

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

									<div class="box m-top-0">
										<div class="box-head pad-5 m-bottom-5">
											<label><i class="fa fa-ticket fa-lg"></i> Site Labour On Cost Matrix</label>
										</div>

										<div class="box-area pad-5 clearfix">

											<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('superannuation')){ echo 'has-error';} ?>">
												<label for="superannuation" class="col-sm-4 control-label">Superannuation</label>
												<div class="col-sm-8">
													<div class="input-group">
														<span class="input-group-addon">%</span>
														<input type="text" class="form-control" placeholder="Superannuation (%)" id="superannuation" name="superannuation" value="<?php echo ($this->input->post('superannuation') ?  $this->input->post('superannuation') : $superannuation ); ?>">
													</div>
												</div>	
											</div>

											<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('workers_compensation')){ echo 'has-error';} ?>">
												<label for="workers_compensation" class="col-sm-5 control-label">Workers Compensation</label>
												<div class="col-sm-7">
													<div class="input-group">
														<span class="input-group-addon">%</span>
														<input type="text" class="form-control" placeholder="Workers Compensation (%)" id="workers_compensation" name="workers_compensation" value="<?php echo ($this->input->post('workers_compensation') ?  $this->input->post('workers_compensation') : $workers_compensation ); ?>">
													</div>
												</div>	
											</div>

											<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('payroll_tax')){ echo 'has-error';} ?>">
												<label for="payroll_tax" class="col-sm-4 control-label">Payroll Tax</label>
												<div class="col-sm-8">
													<div class="input-group">
														<span class="input-group-addon">%</span>
														<input type="text" class="form-control" placeholder="Payroll Tax (%)" id="payroll_tax" name="payroll_tax" value="<?php echo ($this->input->post('payroll_tax') ?  $this->input->post('payroll_tax') : $payroll_tax ); ?>">
													</div>
												</div>	
											</div>

											<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('leave_loading')){ echo 'has-error';} ?>">
												<label for="leave_loading" class="col-sm-4 control-label">Leave Loading</label>
												<div class="col-sm-8">
													<div class="input-group">
														<span class="input-group-addon">%</span>
														<input type="text" class="form-control" placeholder="Leave Loading (%)" id="leave_loading" name="leave_loading" value="<?php echo ($this->input->post('leave_loading') ?  $this->input->post('leave_loading') : $leave_loading ); ?>">
													</div>
												</div>	
											</div>

											<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('other')){ echo 'has-error';} ?>">
												<label for="other" class="col-sm-4 control-label">Other</label>
												<div class="col-sm-8">
													<div class="input-group">
														<span class="input-group-addon">%</span>
														<input type="text" class="form-control" placeholder="Other (%)" id="other" name="other" value="<?php echo ($this->input->post('other') ?  $this->input->post('other') : $other ); ?>">
													</div>
												</div>	
											</div>

											<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('total_leave_days')){ echo 'has-error';} ?>">
												<label for="total_leave_days" class="col-sm-4 control-label">Total Leave Days</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" placeholder="Total Leave Days" id="total_leave_days" name="total_leave_days" value="<?php echo ($this->input->post('total_leave_days') ?  $this->input->post('total_leave_days') : $total_leave_days ); ?>">
													
												</div>	
											</div>

											<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('total_work_days')){ echo 'has-error';} ?>">
												<label for="total_work_days" class="col-sm-4 control-label">Total Work Days</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" placeholder="Total Work Days" id="total_work_days" name="total_work_days" value="<?php echo ($this->input->post('total_work_days') ?  $this->input->post('total_work_days') : $total_work_days ); ?>">
													
												</div>	
											</div>

											<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('total_work_days')){ echo 'has-error';} ?>">
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

								<!-- Site Labour On Cost Matrix -->



<p><hr /></p>




<!-- Category Mark-Up -->

								<?php if(@$this->session->flashdata('update_prj_mrk')): ?>
									<div class="no-pad-t">
										<div class="border-less-box alert alert-success fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>Cheers!</h4>
											<?php echo $this->session->flashdata('update_prj_mrk');?>
										</div>
									</div>
								<?php endif; ?>

									<form method="post" action="<?php echo current_url(); ?>/project_mark_up" class="form-horizontal">								

									<div class="box m-top-0">

									<div class="box-head pad-5 m-bottom-5">
										<label><i class="fa fa-calculator fa-lg"></i> Category Mark-Up</label>
									</div>

									<div class="box-area pad-5 clearfix">

										<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix ">
											<label for="design_works" class="col-sm-4 control-label">Design Works (%)</label>

											<div class="col-sm-4">
												<div class="input-group">
													<span class="input-group-addon" id="design_works">Default</span>
													<input type="text" class="form-control" placeholder="Default" id="design_works" name="design_works" value="<?php echo ($this->input->post('design_works') ?  $this->input->post('design_works') : $design_works ); ?>">
												</div>
											</div>

											<div class="col-sm-4">
												<div class="input-group">
													<span class="input-group-addon" id="min_design_works">Min</span>
													<input type="text" class="form-control" placeholder="Min" id="min_design_works" name="min_design_works" value="<?php echo ($this->input->post('min_design_works') ?  $this->input->post('min_design_works') : $min_design_works ); ?>">
												</div>
											</div>
										</div>



										<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix ">
											<label for="kiosk" class="col-sm-4 control-label">Kiosk (%)</label>

											<div class="col-sm-4">
												<div class="input-group">
													<span class="input-group-addon" id="kiosk">Default</span>
													<input type="text" class="form-control" placeholder="Default" id="kiosk" name="kiosk" value="<?php echo ($this->input->post('kiosk') ?  $this->input->post('kiosk') : $kiosk ); ?>">
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
													<input type="text" class="form-control" placeholder="Default" id="full-fitout" name="full-fitout" value="<?php echo ($this->input->post('full-fitout') ?  $this->input->post('full-fitout') : $full_fitout ); ?>">
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
													<input type="text" class="form-control" placeholder="Default" id="refurbishment" name="refurbishment" value="<?php echo ($this->input->post('refurbishment') ?  $this->input->post('refurbishment') : $refurbishment ); ?>">
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
													<input type="text" class="form-control" placeholder="Default" id="stripout" name="stripout" value="<?php echo ($this->input->post('stripout') ?  $this->input->post('stripout') : $stripout ); ?>">
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
													<input type="text" class="form-control" placeholder="Default" id="maintenance" name="maintenance" value="<?php echo ($this->input->post('maintenance') ?  $this->input->post('maintenance') : $maintenance ); ?>">
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
													<input type="text" class="form-control" placeholder="Default" id="minor-works" name="minor-works" value="<?php echo ($this->input->post('minor-works') ?  $this->input->post('minor-works') : $minor_works ); ?>">
												</div>
											</div>

											<div class="col-sm-4">
												<div class="input-group">
													<span class="input-group-addon" id="min_minor_works">Min</span>
													<input type="text" class="form-control" placeholder="Min" id="min_minor_works" name="min_minor_works" value="<?php echo ($this->input->post('min_minor_works') ?  $this->input->post('min_minor_works') : $min_minor_works ); ?>">
												</div>
											</div>
										</div>




										<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix ">
											<label for="input" class="col-sm-4 control-label">Joinery Only (%)</label>
																						
											<div class="col-sm-4">
												<div class="input-group">
													<span class="input-group-addon" id="joinery-only">Default</span>
													<input type="text" class="form-control" placeholder="Default" id="joinery-only" name="joinery-only" value="<?php echo ($this->input->post('joinery-only') ?  $this->input->post('joinery-only') : $joinery_only ); ?>">
												</div>
											</div>

											<div class="col-sm-4">
												<div class="input-group">
													<span class="input-group-addon" id="min_joinery_only">Min</span>
													<input type="text" class="form-control" placeholder="Min" id="min_joinery_only" name="min_joinery_only" value="<?php echo ($this->input->post('min_joinery_only') ?  $this->input->post('min_joinery_only') : $min_joinery_only ); ?>">
												</div>
											</div>
										</div>


									</div>
								</div>	

								        <button type="submit" class="btn btn-success m-top-15"><i class="fa fa-floppy-o"></i> Save Mark-Up</button>
								    </form>


<!-- Category Mark-Up -->
								  
<p><hr /></p>  


<!-- Defaults Labour Split -->


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

									<div class="box m-top-0">
										<div class="box-head pad-5 m-bottom-5">
											<label><i class="fa fa-cog fa-lg"></i> Defaults Labour Split</label>
										</div>

										<div class="box-area pad-5 clearfix">

											<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('time-half')){ echo 'has-error';} ?>">
												<label for="time-half" class="col-sm-4 control-label">Time &amp; Half Labour</label>
												<div class="col-sm-8">
													<div class="input-group">
														<span class="input-group-addon">%</span>
														<input type="text" class="form-control" placeholder="Time &amp; Half (%)" id="time-half" name="time-half" value="<?php echo ($this->input->post('time-half') ?  $this->input->post('time-half') : $labor_split_time_and_half ); ?>">
													</div>
												</div>
											</div>

											<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('double-time')){ echo 'has-error';} ?>">
												<label for="double-time" class="col-sm-4 control-label">Double Time</label>
												<div class="col-sm-8">
													<div class="input-group">
														<span class="input-group-addon">%</span>
														<input type="text" class="form-control" id="double-time" name="double-time" placeholder="Double Time (%)"  value="<?php echo ($this->input->post('double-time') ?  $this->input->post('double-time') : $labor_split_double_time ); ?>">
													</div>
												</div>
											</div>

											<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('standard-labour')){ echo 'has-error';} ?>">
												<label for="standard-labour" class="col-sm-4 control-label">Standard Labour</label>
												<div class="col-sm-8">
													<div class="input-group">
														<span class="input-group-addon standard-labour text-left" style="padding: 9px;">% <?php echo ($this->input->post('standard-labour') ?  $this->input->post('standard-labour') : $labor_split_standard ); ?></span>
														<input type="hidden" class="form-control" id="standard-labour" readonly="readonly" name="standard-labour" placeholder="Standard Labour (%)"  value="<?php echo ($this->input->post('standard-labour') ?  $this->input->post('standard-labour') : $labor_split_standard ); ?>">
														<span class="input-group-addon" style="padding: 9px;"></span>
													</div>
												</div>
											</div>

											<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('installation-labour')){ echo 'has-error';} ?>">
												<label for="installation-labour" class="col-sm-4 control-label">Installation Labour</label>
												<div class="col-sm-8">
													<div class="input-group">
														<span class="input-group-addon">Markup %</span>
														<input type="text" class="form-control" id="installation-labour" name="installation-labour" placeholder="Installation Labour (%)"  value="<?php echo ($this->input->post('installation-labour') ?  $this->input->post('installation-labour') : $installation_labour_mark_up ); ?>">
													</div>
												</div>
											</div>

											<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('gst-rate')){ echo 'has-error';} ?>">
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

<!-- Defaults Labour Split -->


<p><hr /></p>


<!--  Total Hour Rate -->

	<div class="box m-bottom-10">
							<div class="box-head pad-5"><label><i class="fa fa-cube fa-lg"></i> Total Hour Rate</label></div>

							<div class="box-area clearfix pad-5 m-top-10">								


								<div class="col-lg-3 col-md-6  col-sm-6 col-xs-12 m-bottom-10 clearfix ">
									<label for="total-hour" class="col-sm-6 control-label m-top-5 text-right" style="font-weight: normal;">Total Hour Rate</label>
									<div class="col-sm-6">
										<div class="input-group">
											<span class="input-group-addon" id="">$</span>
											<input type="text" class="form-control" readonly="readonly" id="total-hour" name="total-hour" placeholder="Total Hour"  value="<?php echo ($this->input->post('total-hour') ?  $this->input->post('total-hour') : $total_hour ); ?>">
										</div>
									</div>
								</div>

								<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix ">
									<label for="time-half" class="col-sm-6 control-label m-top-5 text-right" style="font-weight: normal;">Time Half Rate</label>
									<div class="col-sm-6">
										<div class="input-group">
											<span class="input-group-addon" id="">$</span>
											<input type="text" class="form-control" readonly="readonly" id="time-half" name="time-half" placeholder="Total Time Half" value="<?php echo ($this->input->post('time-half') ?  $this->input->post('time-half') : $total_time_half ); ?>">
										</div>

									</div>
								</div>

								<div class="col-lg-3 col-md-6  col-sm-6 col-xs-12 m-bottom-10 clearfix ">
									<label for="double-time" class="col-sm-6 control-label m-top-5 text-right" style="font-weight: normal;">Double Time Rate</label>
									<div class="col-sm-6">
										<div class="input-group">
											<span class="input-group-addon" id="">$</span>
											<input type="text" class="form-control" readonly="readonly" id="double-time" name="double-time" placeholder="Total Double Time" value="<?php echo ($this->input->post('double-time') ?  $this->input->post('double-time') : $total_double_time ); ?>">
										</div>

									</div>
								</div>

								<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix ">
									<label for="amalgamated-rate" class="col-sm-6 control-label m-top-5 text-right" style="font-weight: normal;">Amalgamated Rate</label>
									<div class="col-sm-6">
										<div class="input-group">
											<span class="input-group-addon" id="">$</span>
											<input type="text" class="form-control" readonly="readonly" id="amalgamated-rate" name="amalgamated-rate" placeholder="Amalgamated Rate" value="<?php echo ($this->input->post('amalgamated-rate') ?  $this->input->post('amalgamated-rate') : $total_amalgamated_rate ); ?>">
										</div>

									</div>
								</div>
							</div>
						</div>    

<!--  Total Hour Rate -->


<!-- Hour Rate Cost Inc On Costs -->

						<div class="box">
							<div class="box-head pad-5">
								<label><i class="fa fa-cube fa-lg"></i> Hour Rate Cost Inc On Costs</label>
							</div>

							<div class="box-area clearfix pad-5 m-top-10">
								<div class="col-lg-3 col-md-6  col-sm-6 col-xs-12 m-bottom-10 clearfix">
									<label for="total-hour" class="col-sm-5  control-label m-top-5 text-right" style="font-weight: normal;">Total Hour </label>
									<div class="col-sm-7">
										<div class="input-group">
											<span class="input-group-addon" id="">$</span>
											<input type="text" class="form-control" readonly="readonly" value="<?php echo $gp_on_cost_total_hr; ?>">
										</div>
									</div>						
								</div>

								<div class="col-lg-3 col-md-6  col-sm-6 col-xs-12 m-bottom-10 clearfix">
									<label for="total-hour" class="col-sm-5  control-label m-top-5 text-right" style="font-weight: normal;">Time Half </label>
									<div class="col-sm-7">
										<div class="input-group">
											<span class="input-group-addon" id="">$</span>
											<input type="text" class="form-control" readonly="readonly" value="<?php echo $gp_on_cost_time_half_hr; ?>">
										</div>
									</div>						
								</div>

								<div class="col-lg-3 col-md-6  col-sm-6 col-xs-12 m-bottom-10 clearfix">
									<label for="total-hour" class="col-sm-5  control-label m-top-5 text-right" style="font-weight: normal;">Double Time </label>
									<div class="col-sm-7">
										<div class="input-group">
											<span class="input-group-addon" id="">$</span>
											<input type="text" class="form-control" readonly="readonly" value="<?php echo $gp_on_cost_time_double_hr; ?>">
										</div>
									</div>						
								</div>

								<div class="col-lg-3 col-md-6  col-sm-6 col-xs-12 m-bottom-10 clearfix">
									<label for="total-hour" class="col-sm-6  control-label m-top-5 text-right" style="font-weight: normal;">Amalgamated Rate </label>
									<div class="col-sm-6">
										<div class="input-group">
											<span class="input-group-addon" id="">$</span>
											<input type="text" class="form-control" readonly="readonly" value="<?php echo $gp_amalgamated_rate;?>">
										</div>
									</div>						
								</div>
								
							</div>
						</div> 

<!-- Hour Rate Cost Inc On Costs -->


									</div>
										
								


								<div class="tab-pane fade in <?php echo ($curr_tab == 'admin_defaults' ? 'active' : '' ); ?>" id="defaults" aria-labelledby="defaults">
								<?php if(@$invoice_email_errors): ?>
									<div class="no-pad-t">
										<div class="border-less-box alert alert-danger fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>Oh snap! You got an error!</h4>
											<?php echo $invoice_email_errors;?>
										</div>
									</div>
								<?php endif; ?>

								<?php if(@$this->session->flashdata('invoice_default_email')): ?>
									<div class="no-pad-t">
										<div class="border-less-box alert alert-success fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>Cheers!</h4>
											<?php echo $this->session->flashdata('invoice_default_email');?>
										</div>
									</div>
								<?php endif; ?>



								<form class="form-horizontal" role="form" method="post" action="<?php echo current_url(); ?>/invoice_email">

									<div class="box m-top-0">
										<div class="box-head pad-5 m-bottom-5">
											<label><i class="fa fa-envelope fa-lg"></i> Default Job Book Emails</label>
										</div>

										<div class="box-area pad-5 clearfix">

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('recipient_email')){ echo 'has-error';} ?>">
												<label for="recipient_email" class="col-sm-4 control-label">Recipient Email</label>
												<div class="col-sm-8">
													<div class="input-group">
														<span class="input-group-addon" id=""><i class="fa fa-envelope-o"></i></span>
														<input type="email" class="form-control" id="recipient_email" name="recipient_email" placeholder="Recipient Email" value="<?php echo $static_defaults[0]->invoice_to; ?>">
													</div>
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('optional_cc_email')){ echo 'has-error';} ?>">
												<label for="optional_cc_email" class="col-sm-4 control-label">Optional CC Email</label>
												<div class="col-sm-8">
													<div class="input-group">
														<span class="input-group-addon" id=""><i class="fa fa-envelope-o"></i></span>
														<input type="email" class="form-control" id="optional_cc_email" name="optional_cc_email" placeholder="Optional CC Email" value="<?php echo $static_defaults[0]->invoice_cc; ?>">
													</div>
												</div>
											</div>

											<div class="clearfix col-xs-12 text-center"><strong> <p><i class="fa fa-quote-left"></i> Copies are sent to the Project Manager &amp; the Sender (creator) PLUS the above <i class="fa fa-quote-right"></i></p></strong></div>
											
										</div>
									</div>

									<div class="m-top-15 clearfix">
								    	<div>
								        	<button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Save Emails</button>
								        </div>
								    </div>	
								    
							    </form>	
	

								    <!-- Notes Defaults -->
								   

								    <p><hr /></p>



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


								    <p><hr /></p>


								 <form class="form-horizontal" role="form" method="post" action="<?php echo current_url(); ?>/default_email_message">

									<div class="box">
										<div class="box-head pad-5 m-bottom-5">
											<label><i class="fa fa-pencil-square-o fa-lg"></i> Default Insurance Email Messages</label>
										</div>

										<div class="box-area pad-5 clearfix">
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('time-half')){ echo 'has-error';} ?>">
												<label for="time-half" class="col-sm-4 control-label">Default Sender Name:</label>
												<div class="col-sm-8">
													<input type = "text" class="form-control" id="sender_name_no_insurance" name="sender_name_no_insurance" value = "<?php echo ($this->input->post('sender_name_no_insurance') ?  $this->input->post('sender_name_no_insurance') : $sender_name ); ?>">
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('double-time')){ echo 'has-error';} ?>">
												<label for="double-time" class="col-sm-4 control-label">Default Senders Email:</label>
												<div class="col-sm-8">
													<input type = "text" class="form-control" id="sender_email_no_insurnace" name="sender_email_no_insurnace" value = "<?php echo ($this->input->post('sender_email_no_insurnace') ?  $this->input->post('sender_email_no_insurnace') : $sender_email ); ?>">
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('time-half')){ echo 'has-error';} ?>">
												<label for="time-half" class="col-sm-4 control-label">Subject:</label>
												<div class="col-sm-8">
													<input type = "text" class="form-control" id="subject_no_insurnace" name="subject_no_insurnace" value = "<?php echo ($this->input->post('subject_no_insurnace') ?  $this->input->post('subject_no_insurnace') : $subject ); ?>">
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('double-time')){ echo 'has-error';} ?>">
												<label for="double-time" class="col-sm-4 control-label">BCC:</label>
												<div class="col-sm-8">
													<input type = "text" class="form-control" id="bcc_email_no_insurnace" name="bcc_email_no_insurnace" value = "<?php echo ($this->input->post('bcc_email_no_insurnace') ?  $this->input->post('bcc_email_no_insurnace') : $bcc_email ); ?>">
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('double-time')){ echo 'has-error';} ?>">
												<label for="double-time" class="col-sm-4 control-label">User Responsible: </label>
												<div class="col-sm-8">
													<select name="user_assigned_forinsurance" class="form-control find_contact_person chosen" id="user_assigned_forinsurance" style="width: 100%;" tabindex="25">																										
														<?php //$this->company->company_list('dropdown'); ?>
														<option value=''>Select User Name*</option>													
														<?php $this->admin->fetch_users_list(); ?>														
													</select>
													<?php echo $this->input->post('user_id'); ?>
													<script type="text/javascript">$('select#user_assigned_forinsurance').val('<?php echo $user_id; ?>');</script>
													
												</div>
											</div>

											<div class="clearfix col-xs-12 text-center"><strong> <p><i class="fa fa-quote-left"></i> Automatically sent to any CONTRACTOR who has no insurance documents loaded OR the insurance documents are expired, WHEN a purchase order is reconciled. <i class="fa fa-quote-right"></i></p></strong></div>
											

											<!-- <div class="col-md-12 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('time-half')){ echo 'has-error';} ?>">
												<label for="time-half" class="col-sm-2 control-label">Subject:</label>
												<div class="col-sm-10">
													<input type = "text" class="form-control" id="subject_no_insurnace" name="subject_no_insurnace" value = "<?php echo ($this->input->post('subject_no_insurnace') ?  $this->input->post('subject_no_insurnace') : $subject ); ?>">
												</div>
											</div> -->
											
										</div>
									</div>


									<div class="box m-top-15 <?php if(form_error('time-half')){ echo 'has-error';} ?>">
											<div class="box-head pad-5">
												<label for="email_msg_no_insurance"><i class="fa fa-pencil-square-o fa-lg"></i> Message to Contactor for Expires or no Insurance</label>
											</div>
											
											<div class="box-area pad-5 clearfix ">
												<div class="clearfix ">
													<div class="">
														<textarea class="form-control" id="email_msg_no_insurance" name="email_msg_no_insurance" style="height: 100px "><?php echo ($this->input->post('email_msg_no_insurance') ?  $this->input->post('email_msg_no_insurance') : $message_content ); ?></textarea>												
													</div>
												</div>
											</div>
										</div>

									<div class="m-top-15 clearfix">
								    	<div>
								        	<button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Save Email Default</button>
								        </div>
								    </div>	
								    
							    </form>	

								 <p><hr /></p>
							    <!-- Unaccepted Projects Defaults -->

								<form class="form-horizontal" role="form" method="post" action="<?php echo current_url(); ?>/default_unaccepted_projects">

									<div class="box">
										<div class="box-head pad-5 m-bottom-5">
											<label><i class="fa fa-pencil-square-o fa-lg"></i> Unaccepted Projects Defaults</label>
										</div>

										<div class="box-area pad-5 clearfix">
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('time-half')){ echo 'has-error';} ?>">
												<label for="time-half" class="col-sm-4 control-label">Default Number of Days Before Commencement Date:</label>
												<div class="col-sm-8">
													<input type = "text" class="form-control" id="unaccepted_num_days" name="unaccepted_num_days" value = "<?php echo ($this->input->post('unaccepted_num_days') ?  $this->input->post('unaccepted_num_days') : $unaccepted_no_days ); ?>">
												</div>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('time-half')){ echo 'has-error';} ?>">
												<label for="time-half" class="col-sm-4 control-label">Select Job Categories:</label>
												<div class="col-sm-8">
													<?php 
														$job_category_arr = explode(",",$unaccepted_date_categories);
														$design_works = 0;
														$kiosk = 0;
														$full_fitout = 0;
														$refurbishment = 0;
														$strip_out = 0;
														$ninor_works = 0;
														$maintenance = 0;

														foreach ($job_category_arr as &$value) {
															if($value == "Design Works"){
																$design_works = 1;
															}

															if($value == "Kiosk"){
																$kiosk = 1;
															}

															if($value == "Full Fitout"){
																$full_fitout = 1;
															}

															if($value == "Refurbishment"){
																$refurbishment = 1;
															}
															if($value == "Strip Out"){
																$strip_out = 1;
															}
															if($value == "Minor Works"){
																$ninor_works = 1;
															}

															if($value == "Maintenance"){
																$maintenance = 1;
															}
														}
													?>
													<table width = 100%>
														<tr>
															<td><input type="checkbox" name = "unaccepted_proj_categories[]" value = "Design Works" <?php if($design_works == 1){ echo 'checked'; } ?>></td>
															<td>Design Works</td>
															<td><input type="checkbox" name = "unaccepted_proj_categories[]" value = "Kiosk" <?php if($kiosk == 1){ echo 'checked'; } ?>></td>
															<td>Kiosk</td>
														</tr>
														<tr>
															<td><input type="checkbox" name = "unaccepted_proj_categories[]" value = "Full Fitout" <?php if($full_fitout == 1){ echo 'checked'; } ?>></td>
															<td>Full Fitout</td>
															<td><input type="checkbox" name = "unaccepted_proj_categories[]" value = "Refurbishment" <?php if($refurbishment == 1){ echo 'checked'; } ?>></td>
															<td>Refurbishment</td>
														</tr>
														<tr>
															<td><input type="checkbox" name = "unaccepted_proj_categories[]" value = "Strip Out" <?php if($strip_out == 1){ echo 'checked'; } ?>></td>
															<td>Strip Out</td>
															<td><input type="checkbox" name = "unaccepted_proj_categories[]" value = "Minor Works" <?php if($ninor_works == 1){ echo 'checked'; } ?>></td>
															<td>Minor Works</td>
														</tr>
														<tr>
															<td><input type="checkbox" name = "unaccepted_proj_categories[]" value = "Maintenance" <?php if($maintenance == 1){ echo 'checked'; } ?>></td>
															<td>Maintenance</td>
														</tr>
													</table>
												</div>
											</div>

											
										</div>
									</div>

									<div class="m-top-15 clearfix">
								    	<div>
								        	<button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Save Unaccepted Projects Defaults</button>
								        </div>
								    </div>	
								    
							    </form>	
									
								 <p><hr /></p>
							      <!-- Projects Labour Sched Defaults -->

								<form class="form-horizontal" role="form" method="post" action="<?php echo current_url(); ?>/default_labour_schedule">

									<div class="box">
										<div class="box-head pad-5 m-bottom-5">
											<label><i class="fa fa-pencil-square-o fa-lg"></i> Projects Labour Schedule Defaults</label>
										</div>

										<div class="box-area pad-5 clearfix">

											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix <?php if(form_error('time-half')){ echo 'has-error';} ?>">
												<label for="time-half" class="col-sm-4 control-label">Select Excluded Job Categories:</label>
												<div class="col-sm-8">
													<?php 
														$ls_job_category_arr = explode(",",$labour_sched_categories);
														$ls_design_works = 0;
														$ls_kiosk = 0;
														$ls_full_fitout = 0;
														$ls_refurbishment = 0;
														$ls_strip_out = 0;
														$ls_minor_works = 0;
														$ls_maintenance = 0;

														foreach ($ls_job_category_arr as &$value) {
															if($value == "Design Works"){
																$ls_design_works = 1;
															}

															if($value == "Kiosk"){
																$ls_kiosk = 1;
															}

															if($value == "Full Fitout"){
																$ls_full_fitout = 1;
															}

															if($value == "Refurbishment"){
																$ls_refurbishment = 1;
															}
															if($value == "Strip Out"){
																$ls_strip_out = 1;
															}
															if($value == "Minor Works"){
																$ls_minor_works = 1;
															}

															if($value == "Maintenance"){
																$ls_maintenance = 1;
															}
														}
													?>
													<table width = 100%>
														<tr>
															<td><input type="checkbox" name = "labour_sched_categories[]" value = "Design Works" <?php if($ls_design_works == 1){ echo 'checked'; } ?>></td>
															<td>Design Works</td>
															<td><input type="checkbox" name = "labour_sched_categories[]" value = "Kiosk" <?php if($ls_kiosk == 1){ echo 'checked'; } ?>></td>
															<td>Kiosk</td>
														</tr>
														<tr>
															<td><input type="checkbox" name = "labour_sched_categories[]" value = "Full Fitout" <?php if($ls_full_fitout == 1){ echo 'checked'; } ?>></td>
															<td>Full Fitout</td>
															<td><input type="checkbox" name = "labour_sched_categories[]" value = "Refurbishment" <?php if($ls_refurbishment == 1){ echo 'checked'; } ?>></td>
															<td>Refurbishment</td>
														</tr>
														<tr>
															<td><input type="checkbox" name = "labour_sched_categories[]" value = "Strip Out" <?php if($ls_strip_out == 1){ echo 'checked'; } ?>></td>
															<td>Strip Out</td>
															<td><input type="checkbox" name = "labour_sched_categories[]" value = "Minor Works" <?php if($ls_minor_works == 1){ echo 'checked'; } ?>></td>
															<td>Minor Works</td>
														</tr>
														<tr>
															<td><input type="checkbox" name = "labour_sched_categories[]" value = "Maintenance" <?php if($ls_maintenance == 1){ echo 'checked'; } ?>></td>
															<td>Maintenance</td>
														</tr>
													</table>
												</div>
											</div>

											
										</div>
									</div>

									<div class="m-top-15 clearfix">
								    	<div>
								        	<button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Save Projects Labour Sched Defaults</button>
								        </div>
								    </div>	
								    
							    </form>	


</div>






<div class="tab-pane fade in <?php echo ($curr_tab == 'users' ? 'active' : '' ); ?>" id="users" aria-labelledby="users">
	
	<form class="form-horizontal" role="form" method="post" action="<?php echo current_url(); ?>/user_settings">


		<div class="box m-top-0">
			<div class="box-head pad-5">
				<label><i class="fa fa-user-times fa-lg"></i> User Accounts Setting</label>
			</div>


		<?php if(@$this->session->flashdata('update_user_settings')): ?>
			<div class="col-sm-12 ">							
				<div class="no-pad-t">
					<div class="border-less-box alert alert-success fade in m-top-5">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<?php echo $this->session->flashdata('update_user_settings');?>
					</div>
				</div>
			</div>
		<?php endif; ?>


			<div class="box-area clearfix pad-5">
				
				<div class="col-md-3 col-sm-6 col-xs-12 clearfix m-top-10 m-bottom-10">
					<label for="total-hour" class="col-sm-6  control-label m-top-5 text-right" style="font-weight: normal;">Days Password Expiration </label>
					<div class="col-sm-6">
						<div class="input-group">
							<span class="input-group-addon" id=""><i class="fa fa-calendar"></i></span>
							<input type="text" class="form-control" value="<?php echo $static_defaults[0]->days_psswrd_exp; ?>" name="days_exp">
							<span class="input-group-addon" id="">Days</span>
						</div>
					</div>						
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12 clearfix m-top-10 m-bottom-10">
					<label for="total-hour" class="col-sm-6  control-label m-top-5 text-right" style="font-weight: normal;">Temporary User Password </label>
					<div class="col-sm-6">
						<div class="input-group">
							<span class="input-group-addon" id=""><i class="fa fa-lock"></i></span>
							<input type="text" class="form-control" name="temp_password" value="<?php echo $static_defaults[0]->temp_user_psswrd; ?>">
						</div>
					</div>						
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12 clearfix m-top-10 m-bottom-10">
					<button type="submit" class="btn btn-success pull-right m-bottom-10 m-right-5"><i class="fa fa-floppy-o"></i> Save User Settings</button>				
				</div>




				<div class="col-md-3 col-sm-6 col-xs-12 clearfix m-top-10 m-bottom-10">
					<label for="annual_leave_daily_rate" class="col-sm-6  control-label m-top-5 text-right" style="font-weight: normal;">Annual Leave Daily Rate </label>
					<div class="col-sm-6">
						<div class="input-group">
							<span class="input-group-addon" id=""><i class="fa fa-calendar"></i></span>
							<input type="text" class="form-control" value="<?php echo floatval($static_defaults[0]->annual_leave_daily_rate); ?>" name="annual_leave_daily_rate" id="annual_leave_daily_rate">
						</div>
					</div>						
				</div>





				<div class="col-md-3 col-sm-6 col-xs-12 clearfix m-top-10 m-bottom-10">
					<label for="sl_days" class="col-sm-6  control-label m-top-5 text-right" style="font-weight: normal;">Sick Leave Days </label>
					<div class="col-sm-6">
						<div class="input-group">
							<span class="input-group-addon" id=""><i class="fa fa-calendar"></i></span>
							<input type="text" class="form-control" value="<?php echo $static_defaults[0]->sl_days; ?>" name="sl_days" id="sl_days">
							<span class="input-group-addon" id="">Days</span>
						</div>
					</div>						
				</div>




				<div class="col-md-3 col-sm-6 col-xs-12 clearfix m-top-10 m-bottom-10">
					<label for="pl_carers_days" class="col-sm-6  control-label m-top-5 text-right" style="font-weight: normal;">Personal Leave - Carers </label>
					<div class="col-sm-6">
						<div class="input-group">
							<span class="input-group-addon" id=""><i class="fa fa-calendar"></i></span>
							<input type="text" class="form-control" value="<?php echo $static_defaults[0]->pl_carers_days; ?>" name="pl_carers_days" id="pl_carers_days">
							<span class="input-group-addon" id="">Days</span>
						</div>
					</div>						
				</div>


				<div class="col-md-3 col-sm-6 col-xs-12 clearfix m-top-10 m-bottom-10">
					<label for="pl_compasionate_days" class="col-sm-6  control-label m-top-5 text-right" style="font-weight: normal;">Compasionate </label>
					<div class="col-sm-6">
						<div class="input-group">
							<span class="input-group-addon" id=""><i class="fa fa-calendar"></i></span>
							<input type="text" class="form-control" value="<?php echo $static_defaults[0]->pl_compasionate_days; ?>" name="pl_compasionate_days" id="pl_compasionate_days">
							<span class="input-group-addon" id="">Days</span>
						</div>
					</div>						
				</div>



			</div>
		</div>
	</form>


	<p><hr /></p>

	<?php if(@$this->session->flashdata('update_assignment')): ?>
		<div class="m-bottom-15">							
			<div class="no-pad-t">
				<div class="border-less-box alert alert-success fade in">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4>Cheers!</h4>
					<?php echo $this->session->flashdata('update_assignment');?>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<form class="form-horizontal" role="form" method="post" action="<?php echo current_url(); ?>/primay_pa_pm">
		<div class="box m-top-0">
			<div class="box-head pad-5">
				<label><i class="fa fa-university fa-lg" aria-hidden="true"></i> Primary Project Manager Assignments</label>
			</div>
			<div class="box-area clearfix pad-5">

 
 



				<?php 
					$project_admin = $this->user_model->fetch_user_by_role(2);
					$project_admin_list = $project_admin->result();

					$project_manager = $this->user_model->fetch_user_by_role(3);
					$project_manager_list = $project_manager->result();

					foreach ($project_admin_list as $pa ) {

						$assignment =   $this->admin->list_pa_assignment($pa->user_id);

						$pa_assignment = explode(',',$assignment['project_manager_ids']   );
						$pa_primary =  $assignment['project_manager_primary_id'];

						echo '<div class="col-md-3 col-sm-3 col-xs-12 clearfix m-top-10 m-bottom-10">
						<p><strong>'.$pa->user_first_name.'  '.$pa->user_last_name.'</strong></p>';
						foreach ($project_manager_list as $pm ) { 


							$fetch_user = $this->user_model->fetch_user($pm->user_id);
							$pm_data = array_shift( $fetch_user->result() ); 

							echo '<p class="m-bottom-15">';

								if($pa_primary == $pm->user_id){
									echo '<input type="radio" name="pm_primary'.$pa->user_id.'" value="'.$pm->user_id.'" id="prime'.$pa->user_id.''.$pm->user_id.'" checked="checked">';
								}else{
									echo '<input type="radio" name="pm_primary'.$pa->user_id.'" value="'.$pm->user_id.'" id="prime'.$pa->user_id.''.$pm->user_id.'">';
								}

								echo '<label class="m-right-15 m-left-5"  for="prime'.$pa->user_id.''.$pm->user_id.'">Prime</label>';
								 
								if( in_array($pm->user_id, $pa_assignment) ){
									echo '<input  type="checkbox" id="'.$pa->user_id.''.$pm->user_id.'" name="pm_set_'.$pa->user_id.'[]" value="'.$pm->user_id.'" checked="checked">';
								}else{
									echo '<input  type="checkbox" id="'.$pa->user_id.''.$pm->user_id.'" name="pm_set_'.$pa->user_id.'[]" value="'.$pm->user_id.'">';
								}

								echo '<label  class="m-left-5" for="'.$pa->user_id.''.$pm->user_id.'">'.$pm->user_first_name.'  '.$pm->user_last_name.' <strong><em><sub>'.$pm_data->company_name.'</sub></em></strong> </label>';

							echo '</p>';
						}	
						echo '</div>';
					}
				?>
				<div class="col-md-3 col-sm-3 col-xs-12 clearfix m-top-10 m-bottom-10 pad-10">
					<button type="submit" class="btn btn-success pull-right m-bottom-10 m-right-5"><i class="fa fa-floppy-o"></i> Save Assignment</button>				
				</div>
			</div>
		</div>
	</form>


	<p><hr /></p>

<!-- Site labour User Settings -->	
		<div class="box m-top-0">
			<div class="box-head pad-5">
				<label><i class="fa fa-tasks fa-lg"></i> Site Labour Setting</label>
			</div>
			<div class="box-area clearfix pad-5">
				<div class="box m-top-0">
					<div class="col-sm-8">
						<div class="box-head pad-5">
							<label><i class="fa fa-user fa-lg"></i> Site Labour Employee Rate Set Setting</label>
							<button type = "button" class = "btn btn-xs btn-success pull-right" id = "add_rate_form"> Add Rate Set</button>
						</div>
						<div class="box-area clearfix pad-5">
							<div class="col-sm-12">
								<table class="table table-condensed table-striped" style = "font-size: 12px">
									<thead>
										<tr>
											<th>Rate Set Name</th>
											<th>Normal Rate</th>
											<th>Time and a Half Rate</th>
											<th>Double Time Rate</th>
											<th>Double Time and a Half Rate</th>
											<th>Travel</th>
											<th>Meal</th>
											<th>Leaving away from Home</th>
										</tr>
									</thead>
									<tbody>
										<?php $this->admin->fetch_emp_rate_set(); ?>
									</tbody>
									<?php //$this->admin->fetch_app_users_list(); ?>
								</table>

							</div>
						</div>	
					</div>

					<div class="col-sm-4">
						<div class="box m-top-0">
							<div class="box-head pad-5">
								<label><i class="fa fa-user fa-lg"></i> Site Labour Employee Rate Setting</label>
								<button type = "button" class = "btn btn-xs btn-success pull-right" id = "assign_rate"> Add Employee</button>
							</div>
							<div class="box-area clearfix pad-5">
								<div class="col-sm-12">
									<table class="table table-condensed table-striped" style = "font-size: 12px">
										<thead>
											<tr>
												<th>Employee Name</th>
												<th>Rate Set</th>
											</tr>
										</thead>
										<tbody>
											<?php $this->admin->fetch_employee_rate(); ?>
										</tbody>
									</table>

								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>

		<div class="modal fade" id="add_rate_set_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  	<div class="modal-dialog  modal-sm" style = "width:600px">
		    	<div class="modal-content">
		      		<div class="modal-header">
		        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        		<h4 class="modal-title" id="myModalLabel">Rate Set</h4>
		      		</div>
		      		<div class="modal-body" style = "height: 400px">
		      			<div class="col-sm-4 col-sm-offset-2 text-right pad-5">Rate Set Name: </div>
		      			<div class="col-sm-4 pad-5"><input type="text" class = "form-control input-sm" id = "rate_set_name"></div>
		      			<div class="clearfix"></div>
		      			<div class="col-sm-4 col-sm-offset-2 text-right pad-5">Normal Rate: </div>
		      			<div class="col-sm-4 pad-5"><input type="text" class = "form-control input-sm" id = "normal_rate"></div>
		      			<div class="clearfix"></div>
		      			<div class="col-sm-4 col-sm-offset-2 text-right pad-5">Time and a Half Rate: </div>
		      			<div class="col-sm-4 pad-5"><input type="text" class = "form-control input-sm" id = "time_half_rate"></div>
		      			<div class="clearfix"></div>
		      			<div class="col-sm-4 col-sm-offset-2 text-right pad-5">Double Time Rate: </div>
		      			<div class="col-sm-4 pad-5"><input type="text" class = "form-control input-sm" id = "double_time_rate"></div>
		      			<div class="clearfix"></div>
		      			<div class="col-sm-4 col-sm-offset-2 text-right pad-5">Double Time and a Half Rate: </div>
		      			<div class="col-sm-4 pad-5"><input type="text" class = "form-control input-sm" id = "double_time_half_rate"></div>
		      			<div class="clearfix"></div>
		      			<div class="col-sm-4 col-sm-offset-2 text-right pad-5">Travel Allowance: </div>
		      			<div class="col-sm-4 pad-5"><input type="text" class = "form-control input-sm" id = "travel_allowance"></div>
		      			<div class="clearfix"></div>
		      			<div class="col-sm-4 col-sm-offset-2 text-right pad-5">Meal Allowance: </div>
		      			<div class="col-sm-4 pad-5"><input type="text" class = "form-control input-sm" id = "meal_allowance"></div>
		      			<div class="clearfix"></div>
		      			<div class="col-sm-4 col-sm-offset-2 text-right pad-5">Leave away from home Allowance: </div>
		      			<div class="col-sm-4 pad-5"><input type="text" class = "form-control input-sm" id = "lafh_allowance"></div>
		      		</div>
		      		<div class="modal-footer">
		        		<button type = "button" class="btn pull-right" data-dismiss="modal">close</button>
		        		<button type = "button" class="btn pull-right btn-success" data-dismiss="modal" id = "add_rate_set">Add</button>
		        		<button type = "button" class="btn pull-right btn-success" data-dismiss="modal" id = "update_rate_set">Update</button>
		        		<button type = "button" class="btn pull-left btn-danger" data-dismiss="modal" id = "remove_rate_set">Remove</button>
		      		</div>
		    	</div>
		  	</div>
		</div>

		<div class="modal fade" id="from_assign_rate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  	<div class="modal-dialog  modal-sm" style = "width:500px">
		    	<div class="modal-content">
		      		<div class="modal-header">
		        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        		<h4 class="modal-title" id="myModalLabel">Assign Rate</h4>
		      		</div>
		      		<div class="modal-body">
		      			<div class="col-sm-4 text-right pad-5">Select Employee: </div>
		      			<div class="col-sm-8 pad-5">
		      				<select name="txt_add_user" id="txt_add_user" class = "input-sm form-control">
								<?php echo $this->site_labour->display_app_users_for_form(); ?>
							</select>
						</div>
		      			<div class="clearfix"></div>
		      			<div class="col-sm-4 text-right pad-5">Select Rate: </div>
		      			<div class="col-sm-8 pad-5">
		      				<select name="txt_add_rate" id="txt_add_rate" class = "input-sm form-control">
								<?php echo $this->admin->display_rate_set_for_form(); ?>
							</select></div>
		      			<div class="clearfix"></div>
		      		</div>
		      		<div class="modal-footer">
		        		<button type = "button" class="btn pull-right" data-dismiss="modal">close</button>
		        		<button type = "button" class="btn pull-right btn-success" data-dismiss="modal" id = "add_rate_assignment">Add</button>
		        		<button type = "button" class="btn pull-right btn-success" data-dismiss="modal" id = "update_rate_assignment">Update</button>
		        		<button type = "button" class="btn pull-left btn-danger" data-dismiss="modal" id = "remove_rate_assignment">Remove</button>
		      		</div>
		    	</div>
		  	</div>
		</div>

		<script>
			$("#add_rate_form").click(function(){
				$('#rate_set_name').val("");
				$('#normal_rate').val(0);
				$('#time_half_rate').val(0);
				$('#double_time_rate').val(0);
				$('#double_time_half_rate').val(0);
				$('#travel_allowance').val(0);
				$('#meal_allowance').val(0);
				$('#lafh_allowance').val(0);
				$("#add_rate_set").show();
				$("#update_rate_set").hide();
				$("#remove_rate_set").hide();
				$("#add_rate_set_form").modal('show');
			});

			$("#add_rate_set").click(function(){
				var rate_set_name = $('#rate_set_name').val();
				var normal_rate = $('#normal_rate').val();
				var time_half_rate = $('#time_half_rate').val();
				var double_time_rate = $('#double_time_rate').val();
				var double_time_half_rate = $('#double_time_half_rate').val();
				var travel_allowance = $('#travel_allowance').val();
				var meal_allowance = $('#meal_allowance').val();
				var lafh_allowance = $('#lafh_allowance').val();

				if(rate_set_name == ""){
					alert("Rate Set Name is required");
				}else{
					$.post(baseurl+"admin/insert_rate_set",
				    {
				      	rate_set_name: rate_set_name,
						normal_rate: normal_rate,
						time_half_rate: time_half_rate, 
						double_time_rate: double_time_rate, 
						double_time_half_rate: double_time_half_rate,
						travel_allowance: travel_allowance,
						meal_allowance: meal_allowance,
						lafh_allowance: lafh_allowance
				    },
				    function(result){
				    	window.open(baseurl+'admin?curr_tab=users', '_self', true);
				    });
				}
			});
			var employee_rate_set_id = 0;
			window.edit_rate_set = function(a){
				employee_rate_set_id = a;
				$.post(baseurl+"admin/fetch_selected_rate_set",
				{
				   	employee_rate_set_id: employee_rate_set_id
			    },
				function(result){
					var result_arr = result.split('/');
				    var rate_set_name = result_arr[0];
					var normal_rate = result_arr[1];
					var time_half_rate = result_arr[2];
					var double_time_rate = result_arr[3];
					var double_time_half_rate = result_arr[4];
					var travel_allowance = result_arr[5];
					var meal_allowance = result_arr[6];
					var lafh_allowance = result_arr[7];
					$('#rate_set_name').val(rate_set_name);
					$('#normal_rate').val(normal_rate);
					$('#time_half_rate').val(time_half_rate);
					$('#double_time_rate').val(double_time_rate);
					$('#double_time_half_rate').val(double_time_half_rate);
					$('#travel_allowance').val(travel_allowance);
					$('#meal_allowance').val(meal_allowance);
					$('#lafh_allowance').val(lafh_allowance);
					$("#add_rate_set").hide();
					$("#update_rate_set").show();
					$("#remove_rate_set").show();
					$("#add_rate_set_form").modal('show');


				});
			};

			$("#update_rate_set").click(function(){
				var rate_set_name = $('#rate_set_name').val();
				var normal_rate = $('#normal_rate').val();
				var time_half_rate = $('#time_half_rate').val();
				var double_time_rate = $('#double_time_rate').val();
				var double_time_half_rate = $('#double_time_half_rate').val();
				var travel_allowance = $('#travel_allowance').val();
				var meal_allowance = $('#meal_allowance').val();
				var lafh_allowance = $('#lafh_allowance').val();
				$.post(baseurl+"admin/update_selected_rate_set",
				{
				   	employee_rate_set_id: employee_rate_set_id,
				   	rate_set_name: rate_set_name,
					normal_rate: normal_rate,
					time_half_rate: time_half_rate, 
					double_time_rate: double_time_rate, 
					double_time_half_rate: double_time_half_rate,
					travel_allowance: travel_allowance,
					meal_allowance: meal_allowance,
					lafh_allowance: lafh_allowance
			    },
				function(result){
				    window.open(baseurl+'admin?curr_tab=users', '_self', true);
				});
			})

			$("#remove_rate_set").click(function(){
				$.post(baseurl+"admin/remove_selected_rate_set",
				{
				   	employee_rate_set_id: employee_rate_set_id
			    },
				function(result){
					window.open(baseurl+'admin', '_self', true);
				});
			})

			$("#assign_rate").click(function(){
				$("#add_rate_assignment").show();
				$("#update_rate_assignment").hide();
				$("#remove_rate_assignment").hide();
				$("#from_assign_rate").modal('show');
			});

			$("#add_rate_assignment").click(function(){
				var user_id = $("#txt_add_user").val();
				var rate_id = $("#txt_add_rate").val();

				$.post(baseurl+"admin/insert_employee_rate",
				{
				   	user_id: user_id,
				   	rate_id: rate_id
			    },
				function(result){
					window.open(baseurl+'admin?curr_tab=users', '_self', true);
				});
			});

			var employee_rate_id = 0;
			window.edit_emp_rate = function(a){
				employee_rate_id = a;
				$.post(baseurl+"admin/fetch_assigned_employee_rate",
				{
				   	employee_rate_id: employee_rate_id
			    },
				function(result){
					var result_arr = result.split('/');
					var user_id = result_arr[0];
					var rate_id = result_arr[1];
					$("#txt_add_user").val(user_id);
					$("#txt_add_rate").val(rate_id);

					$("#add_rate_assignment").hide();
					$("#update_rate_assignment").show();
					$("#remove_rate_assignment").show();
					$("#from_assign_rate").modal('show');
				});
			}

			$("#update_rate_assignment").click(function(){
				var user_id = $("#txt_add_user").val();
				var rate_id = $("#txt_add_rate").val();

				$.post(baseurl+"admin/update_employee_rate",
				{
					employee_rate_id: employee_rate_id,
				   	user_id: user_id,
				   	rate_id: rate_id
			    },
				function(result){
					window.open(baseurl+'admin?curr_tab=users', '_self', true);
				});
			});

			$("#remove_rate_assignment").click(function(){
				$.post(baseurl+"admin/remove_employee_rate",
				{
					employee_rate_id: employee_rate_id
			    },
				function(result){
					window.open(baseurl+'admin?curr_tab=users', '_self', true);
				});
			});
		</script>
<p><hr /></p>
<!-- Site labour User Settings -->			

	
		<div class="box m-top-0">
			<div class="box-head pad-5">
				<label><i class="fa fa-user fa-lg"></i> Employee Location Setting</label>
			</div>
			<div class="box-area clearfix pad-5">
				



					<div class="col-md-4 col-sm-6 col-xs-12 clearfix m-bottom-10">

					<?php if(@$this->session->flashdata('delete_location_assign')): ?>
						<div class="m-top-5">							
							<div class="no-pad-t">
								<div class="border-less-box alert alert-success fade in">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<h4>Cheers!</h4>
									<?php echo $this->session->flashdata('delete_location_assign');?>
								</div>
							</div>
						</div>
					<?php endif; ?>



						<div class="box">
							<form class="form-horizontal" role="form" method="post" action="<?php echo current_url(); ?>/location_assignments">
								<div class="box-head pad-5">
									<label><i class="fa fa-info-circle fa-lg"></i> Employee Location</label>
								</div>
								<div class="box-area pad-10 clearfix" id="container">

								 	<?php $location_b = ''; $counter = 0;?>
									<?php foreach($users_set_location as $key => $set_location_set): ?>
										<?php

											$location_a = $set_location_set->location;


											if($location_a != $location_b && $counter > 0){  echo "<p><br /></p>"; }

											if($location_a != $location_b){
												$location_b = $set_location_set->location;
												echo '<p><strong>'.$set_location_set->location.'</strong></p>'; 
												echo "<hr style='margin-bottom: 5px'>";
												$counter++;
											}

											echo '<a href="?rem_loc_id='.$set_location_set->user_id.'" class="btn btn-danger btn-xs m-bottom-5 m-right-5"><i class="fa fa-times"></i></a>';
											echo $set_location_set->user_first_name.' '.$set_location_set->user_last_name.'<br />'; 

										 ?>
									<?php endforeach; ?>

 
								</div>
							</form>
						</div>


					</div>







					<div class="col-md-4 col-sm-6 col-xs-12 clearfix m-bottom-10">

					<?php if(@$this->session->flashdata('user_location_assign')): ?>
						<div class="m-top-5">							
							<div class="no-pad-t">
								<div class="border-less-box alert alert-success fade in">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<h4>Cheers!</h4>
									<?php echo $this->session->flashdata('user_location_assign');?>
								</div>
							</div>
						</div>
					<?php endif; ?>



						<div class="box">
							<form class="form-horizontal" role="form" method="post" action="<?php echo current_url(); ?>/location_assignments">
								<div class="box-head pad-5">
									<label><i class="fa fa-info-circle fa-lg"></i> Location Assignments</label>
								</div>
								<div class="box-area pad-10 clearfix" id="container">

									<div class="m-bottom-10 clearfix ">
										<select class="form-control" name="location">
											<option value="" disabled="" selected="">Select Location</option>
											<?php foreach($user_location as $key => $location): ?>
												<option value="<?php echo $location->location_address_id; ?>" ><?php echo $location->location; ?></option>
											<?php endforeach; ?>
										</select>
									</div>

									


									<?php $set_user = array();  ?>
									<?php foreach($set_user_location as $key => $location_set): ?>
										<?php array_push($set_user , $location_set->user_id);  ?>
									<?php endforeach; ?>


									<div class="clearfix ">
										<select name="employee_location[]" multiple="" style="width: 100%; margin-bottom: 10px;" size="9">
											<option value="" disabled="" selected="">Select Employee</option>

											<?php foreach($users as $key => $user): ?>
												<?php if(!in_array($user->user_id, $set_user) ): ?>
													<option value="<?php echo $user->user_id; ?>" ><?php echo $user->user_first_name.' '.$user->user_last_name; ?></option>
												<?php endif; ?>
											<?php endforeach; ?>

										</select>
									</div>

									<button type="submit" class="btn btn-success pull-right m-right-5"><i class="fa fa-floppy-o"></i> Save Asignment</button>	
								</div>
							</form>
						</div>


					</div>

				<div class="col-md-4 col-sm-6 col-xs-12 clearfix m-bottom-10">


					<?php if(@$this->session->flashdata('update_user_location')): ?>
						<div class="m-top-5">							
							<div class="no-pad-t">
								<div class="border-less-box alert alert-success fade in">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<h4>Cheers!</h4>
									<?php echo $this->session->flashdata('update_user_location');?>
								</div>
							</div>
						</div>
					<?php endif; ?>


					<div class="box">
						<form class="form-horizontal" role="form" method="post" action="<?php echo current_url(); ?>/user_location">
							<div class="box-head pad-5">
								<label><i class="fa fa-info-circle fa-lg"></i> Add Location</label>
							</div>
							<div class="box-area pad-10 clearfix" id="container">

								<div class="m-bottom-10 clearfix ">
									<textarea class="form-control" id="location" name="location" placeholder="Location" style="height: 100px; z-index: auto; position: relative; line-height: 20px; font-size: 14px; transition: none; background: transparent !important;"></textarea>
								</div>

								<div class="m-bottom-10 clearfix ">
									<label for="Xcoordinate" class="col-sm-4 control-label">X Coordinate</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="Xcoordinate" name="xcoordinate" placeholder="X coordinate" value="">
									</div>
								</div>

								<div class="m-bottom-10 clearfix ">
									<label for="Ycoordinate" class="col-sm-4 control-label">Y Coordinate</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="Ycoordinate" name="ycoordinate" placeholder="Y coordinate" value="">
									</div>
								</div>

								<button type="submit" class="btn btn-success pull-right m-bottom-10 m-right-5"><i class="fa fa-floppy-o"></i> Save Location</button>	
							</div>
						</form>
					</div>


				</div>
			</div>
		</div>




</div>




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