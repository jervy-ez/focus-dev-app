<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
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
						<a href="<?php echo base_url(); ?>projects" class="btn-small">Projects</a>
					</li>
					<li>
						<a href="" class="btn-small"><i class="fa fa-magic"></i> Tour</a>
					</li>
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
					<div class="row">
						<div class="col-md-12">
							<div class="left-section-box po">

								<?php if(@$error): ?>
									<div class="pad-10 no-pad-t">
										<div class="border-less-box alert alert-danger fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>Oh snap! You got an error!</h4>
											<?php echo $error;?>
										</div>
									</div>
								<?php endif; ?>

								<?php if(@$this->session->flashdata('success_add')): ?>
									<div class="pad-10 no-pad-t">
										<div class="border-less-box alert alert-success fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>Cheers!</h4>
											<?php echo $this->session->flashdata('success_add');?>
										</div>
									</div>
								<?php endif; ?>

								<?php if(@$this->session->flashdata('success_remove')): ?>
									<div class="pad-10 no-pad-t">
										<div class="border-less-box alert alert-danger fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>I hope you did the right thing.</h4>
											<?php echo $this->session->flashdata('success_remove');?>
										</div>
									</div>
								<?php endif; ?>	







								<div class="row clearfix">

										<div class="col-lg-4 col-md-12">
											<div class="box-head pad-left-15 clearfix">
												<label><?php echo $screen; ?> List</label>
												<div id="aread_test"></div>
											</div>
										</div>
										
										<div class="col-lg-8 col-md-12">
											<div class="pad-left-15 pad-right-10 clearfix box-tabs">	
												<ul id="myTab" class="nav nav-tabs pull-right">
													<li class="active">
														<a href="#outstanding" data-toggle="tab"><i class="fa fa-level-up fa-lg"></i> Outstanding</a>
													</li>
													<li class="">
														<a href="#reconciled" data-toggle="tab"><i class="fa fa-check-square-o fa-lg"></i> Reconciled</a>
													</li>
												</ul>
											</div>
										</div>

								</div>

								<div class="box-area">

									<div class="box-tabs m-bottom-15">
										<div class="tab-content">
											<div class="tab-pane  clearfix active" id="outstanding">
												<div class="m-bottom-15 clearfix">



													<div class="box-area po-area">
														<table id="po_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
															<thead><tr><th>Project Number</th><th>Job Date</th><th>Job Description</th><th>Contractor</th><th>Project Number</th><th>Job Date</th><th>Client</th><th>Project Manager</th><th>Price</th><th>Balance</th></tr></thead>
															<tbody>

																<?php		  ?>

															</tbody>
														</table>
													</div>



												</div>
											</div>
											<div class="tab-pane  clearfix" id="reconciled">

												<div class="m-bottom-15 clearfix">

												<div class="box-area po-area">
														<table id="reconciled_list_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
															<thead><tr><th>PO Number</th><th>CPO Date</th><th>Job Description</th><th>Contractor</th><th>Project Number</th><th>Reconciled Date</th><th>Client</th><th>Project Manager</th><th>Price</th><th>Balance</th></tr></thead>
                              <tbody>

                                <?php     ?>

															</tbody>
														</table>
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

<!-- Modal -->




<style type="text/css">
	/*.po-area #companyTable_length, .po-area #companyTable_filter{
		display: none;
		visibility: hidden;
	}*/
</style>
<?php $this->load->view('assets/logout-modal'); ?>
<script type="text/javascript">
	
</script>