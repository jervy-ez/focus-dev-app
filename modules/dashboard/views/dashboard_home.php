<?php date_default_timezone_set("Australia/Perth");  // date is set to perth ?>
<!-- title bar -->
<div class="container-fluid head-control">
	<div class="container-fluid">
		<div class="row clearfix">

			<div class="col-md-4 col-sm-4 col-xs-12 pull-left">
				<header class="page-header">
					<h3 class="tour-1"><?php $time = time(); //use time() for timestamp  ?>
						Focus Shopfit<br><small><?php echo mdate("%l, %F %d, %Y", time()); //echo date("l, F d, Y"); ?></small>
					</h3>
				</header>
			</div>

			<div class="page-nav-options col-md-8 col-sm-8 col-xs-12 pull-right hidden-xs">
				<ul class="nav nav-tabs navbar-right tour-3">
					<li class="active">
						<a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Home</a>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>company" class="btn-small">Clients</a>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>projects" class="btn-small">Projects</a>
					</li>
					<li>
						<a href="#" class="btn-small">WIP</a>
					</li>
					<li>
						<a href="#" class="btn-small start-tour"><i class="fa fa-magic"></i> Tour</a>
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
					<div class="col-xs-12">
						<div class="border-less-box alert alert-success fade in tour-4 tour-7">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">
								×
							</button>
							<h4>Welcome!</h4>
							<p>
								Change this and that and try again. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.
							</p>
							<p>
								<button type="button" class="btn btn-success" id="loading-example-btn"  data-loading-text="Loading..." >
									Take this action
								</button>
								<button type="button" class="btn btn-default" onclick="$('.alert').alert('close')" >
									Or do this
								</button>
							</p>
						</div>
					</div>
				</div>

				<div class="row tour-5">
					<div class="" >
						<div class="col-md-3 col-sm-6 col-xs-12 box-widget">
							<div class="box wid-type-a">
								<div class="pull-right pad-5"><i class="fa fa-spin fa-refresh"></i></div>
								<div class="widg-head box-widg-head pad-5">Invoice <span <span class="sub-h"> <?php //echo mdate("%F, %Y", time());?></span></div>
								<div class="box-area pad-5 text-center">
									<i class="fa fa-credit-card pull-left fa-3x widg-icon-inside"></i>	
									<p>Uninvoiced Projects</p>
									<h2>20</h2>
									<p>Invoiced: 10</p>
									<hr class="pad-5" />
									<a href="#"><p>view invoices</p></a>
								</div>
							</div>
						</div>

						<div class="col-md-3 col-sm-6 col-xs-12 box-widget">
							<div class="box wid-type-b">
								<div class="pull-right pad-5"><i class="fa fa-refresh"></i></div>
								<div class="widg-head box-widg-head pad-5">Projects <span <span class="sub-h"> <?php //echo mdate("%F, %Y", time());?></span></div>
								<div class="box-area pad-5 text-center">
									<i class="fa fa-briefcase pull-left fa-3x widg-icon-inside"></i>
									<p>Total Projects</p>
									<h2>11</h2>
									<p>New Projects: 9</p>
									<hr class="pad-5" />
									<a href="#"><p>view projects</p></a>
								</div>
							</div>
						</div>
				
						<div class="col-md-3 col-sm-6 col-xs-12 box-widget">
							<div class="box wid-type-c">
								<div class="pull-left pad-5"></div>
								<div class="pull-right pad-10"><i class="fa fa-refresh"></i></div>
								<div class="widg-head box-widg-head pad-5">Costs <span <span class="sub-h"> <?php //echo mdate("%F, %Y", time());?></span></div>							
								<div class="box-area pad-5 text-center">
									<i class="fa fa-usd pull-left fa-3x widg-icon-inside"></i>								
									<p>Over-all Project Cost</p>
									<h2>5,123,562</h2>
									<p>Budgeted: 105,201</p>
									<hr class="pad-5" />
									<a href="#"><p>view details</p></a>
								</div>
							</div>
						</div>

						<div class="col-md-3 col-sm-6 col-xs-12 box-widget">
							<div class="box wid-type-d">
								<div class="pull-right pad-5"><i class="fa fa-refresh"></i></div>
								<div class="widg-head box-widg-head pad-5">WIP<span <span class="sub-h"> <?php //echo mdate("%F, %Y", time());?></span></div>
								<div class="box-area pad-5 text-center">
									<i class="fa fa-puzzle-piece pull-left fa-3x widg-icon-inside"></i>		
									<p>Work in Progress</p>
									<h2>20</h2>
									<p>Finished Projects: 3</p>
									<hr class="pad-5" />
									<a href="#"><p>view wip</p></a>
								</div>
							</div>
						</div>
					
					</div>	
				</div>

				<div class="row">

					<div class="col-md-6">
						<div class="box">
							<div class="box-head pad-5">
								<label><i class="fa fa-table fa-lg"></i> Projects</label>
							</div>
							<div class="box-area pad-5">
								<div id="chart1"></div>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="box" style="border:1px solid #95BCFD;">
							<div class="box-head pad-5" style="background: #C6DCFF !important; border-bottom:1px solid #95BCFD; ">
								<label><i class="fa fa-map-marker fa-lg"></i> Projects in Australia</label>
							</div>
							<div class="box-area">
								<div id="map-container">
									<div id="map"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-3">
						<div class="box">
							<div class="box-head pad-5">
								<label><i class="fa fa-bar-chart-o fa-lg"></i> Donut Chart</label>
							</div>
							<div class="box-area pattern-sandstone pad-5">
								<div id="chart2"></div>
							</div>
						</div>
					</div>
					
					<div class="col-md-4">
						<div class="box">
							<div class="box-head pad-5">
								<label><i class="fa fa-bookmark"></i> Shortcuts</label>
							</div>
							<div class="box-area pad-5 no-pad-b">
								<div class="btn-group-box">
									<a href="./company"><button class="btn">
										<i class="fa fa-users fa-2x"></i>
										<br>
										Company
									</button></a>
									<button class="btn">
										<i class="fa fa-user fa-2x"></i>
										<br>
										Account
									</button>
									<button class="btn">
										<i class="fa fa-search fa-2x"></i>
										<br>
										Search
									</button>
									<button class="btn">
										<i class="fa fa-list-alt fa-2x"></i>
										<br>
										Reports
									</button>
									<button class="btn">
										<i class="fa fa-bar-chart-o fa-2x"></i>
										<br>
										Charts
									</button>
									<button class="btn">
										<i class="fa fa-list-alt fa-2x"></i>
										<br>
										Reports
									</button>
									<button class="btn start-tour">
										<i class="fa fa-magic fa-2x"></i>
										<br>
										Start Tour
									</button>
									<button class="btn" data-toggle="modal" data-target="#myModal">
										<i class="fa fa-caret-square-o-up fa-2x"></i>
										<br>
										Pop Modal
									</button>
								</div>
							</div>
						</div>
						
						<div class="box">
							<div class="box-head pad-5">
								<label><i class="fa fa-tags fa-lg"></i> Title</label>
							</div>
							<div class="box-area pad-5">
								<p>
									Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
								</p>
							</div>
						</div>
					</div>
				
					
					<div class="col-md-5">
						<div class="left-section-box">
							<div class="box-head pad-10">
								<label>Welcome</label>
							</div>
							<div class="box-area pad-10">
								<p>
									Welcome message goes here. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
								</p>
							</div>
						</div>
					
						<div class="box">
							<div class="box-head pad-10">
								<label><i class="fa fa-th-list fa-lg"></i> Lists</label>
							</div>
							<div class="box-area pattern-sandstone pad-5">

								<div class="box-content box-list collapse in">
									<ul>
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