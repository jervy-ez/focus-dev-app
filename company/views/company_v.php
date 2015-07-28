<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>
<?php
	if($this->session->userdata('company') >= 2 ){

	}else{
		echo '<style type="text/css">.admin_access{ display: none !important;visibility: hidden !important;}</style>';
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
					<li <?php if($screen=='Client'){ echo 'class="active"';} ?> >
						<a href="<?php echo base_url(); ?>company" class="btn-small">Clients</a>
					</li>
					<li <?php if($screen=='Contractor'){ echo 'class="active"';} ?> >
						<a href="<?php echo base_url(); ?>company/contractor" class="btn-small">Contractor</a>
					</li>
					<li <?php if($screen=='Supplier'){ echo 'class="active"';} ?> >
						<a href="<?php echo base_url(); ?>company/supplier" class="btn-small">Supplier</a>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>shopping_center" class="btn-small"><i class="fa fa-shopping-cart"></i> Shopping Center</a>
					</li>
					<li>
						<a href="#" class="btn-small" data-toggle="modal" data-target="#filter_company"><i class="fa fa-print"></i> Reports</a>
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
					

					<div class="col-md-9">

						<?php if(@$this->session->flashdata('new_company_msg')): ?>

						<div class="">
							<div class="border-less-box alert alert-success fade in">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<h4>Congratulations!</h4>
								<?php echo $this->session->flashdata('new_company_msg');?>
							</div>
						</div>

						<?php endif; ?>

						<div class="left-section-box">
							<div class="box-head pad-10 clearfix">
								<a href="./company/add" class="btn btn-primary pull-right admin_access"><i class="fa fa-briefcase"></i>&nbsp; Add New</a>
								<label><?php echo $screen; ?> List</label><span> (<a href="#" data-placement="right" class="popover-test" title="" data-content="Hello there mate! Welcome to the clients screen." data-original-title="Welcome">?</a>)</span>
								<p>This is where the companies are listed.</p>
								<p><a href="#" class="tooltip-test" title="Tooltip">This link</a> and <a href="#" class="tooltip-test" title="Tooltip">that link</a> should have tooltips on hover.</p>								
							</div>
							<div class="box-area pad-10">
								<table id="companyTable" class="table table-striped table-bordered" cellspacing="0" width="100%"><thead><tr><th>Company Name</th><th>Location</th><th>Contact Number</th><th>Email</th></tr></thead>
									<tbody>
										<?php echo $this->company->display_company_by_type($comp_type); ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<div class="col-md-3">
						<div class="box">
							<div class="box-head pad-5">
								<label><i class="fa fa-info-circle fa-lg"></i> Information</label>
							</div>
							<div class="box-area pad-5">
								<p>
									The table can be sortable by the header. It has search feature using a sub text or keyword you are searching. Clicking the Name of the company will lead to the Company Details Screen.
								</p>
							</div>
						</div>
					</div>

					<div class="col-md-3">
						<div class="box">
							<div class="box-head pad-5 clearfix">
								<label><i class="fa fa-bar-chart-o fa-lg"></i> Total Companies</label>
								<a href="./company/add" class="btn btn-primary pull-right btn-xs admin_access"><i class="fa fa-briefcase"></i>&nbsp; Add New</a>
							</div>
							<div class="box-area pattern-sandstone pad-5">
								<div id="company"></div>
								<?php // echo $this->company->donut_cart_companies(); ?>
							</div>
						</div>
					</div>
					
					<div class="col-md-3">						
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





<!-- Modal -->
<div class="modal fade" id="filter_company" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Company Filter</h4>
        <span> Note: <strong>State is required</strong>. The rest, if blank it selects all.</span>
      </div>
      <div class="modal-body clearfix pad-10">

      	<div class="error_area"></div>


      	
      	<div class="">
	      	<div class="input-group m-bottom-10">
	      		<span class="input-group-addon" id="">
	      			Select State *
	      		</span>
	      		<select class="form-control chosen-multi company_state" multiple="multiple"  id="state_b" tabindex="24" name="company_state"  >
	      			<?php
	      			foreach ($all_aud_states as $row){
	      				echo '<option value="'.$row->shortname.'|'.$row->name.'|'.$row->phone_area_code.'|'.$row->id.'">'.$row->name.'</option>';
	      			}?>
	      		</select>
	      	</div>
      	</div>

      	<div class="input-group m-bottom-10">
      		<span class="input-group-addon" id="">
      			Type
      		</span>      		
      		<select class="chosen chosen_type company_type" id="type" name="company_type" tabindex="-1" title="Type*">														
      			<option value="">Choose a Type...</option>
      			<option value="Client|1">Client</option>
      			<option value="Contractor|2">Contractor</option>
      			<option value="Supplier|3">Supplier</option>
      		</select>
      	</div>

      	<div class="input-group m-bottom-10">
      		<span class="input-group-addon" id="">
      			Activity
      		</span>      		
      		<select class="activity chosen-multi company_activity" multiple="multiple" id="activity"  name="company_activity"  title="Activity*">
      			<option value="" disabled="disabled">Choose a Activity...</option>																			
      		</select>     	
      	</div>

      	<div class="col-md-6 col-sm-6 col-xs-12 clearfix ">
      		<div class="input-group m-bottom-10">
      			<span class="input-group-addon" id="">
      				<i class="fa fa-sort-alpha-asc"></i> A
      			</span>      		
      			<input type="text" class="form-control letter_segment" id="starting_letter_segment" placeholder="Starting Letter Segment" name="starting_letter_segment" value="">
      		</div>
      	</div>


      	<div class="col-md-6 col-sm-6 col-xs-12 clearfix ">
      		<div class="input-group m-bottom-10">
      			<span class="input-group-addon" id="">
      				<i class="fa fa-sort-alpha-asc"></i> Z
      			</span>      		
      			<input type="text" class="form-control letter_segment" id="end_letter_segment" placeholder="End Letter Segment" name="end_letter_segment" value="">
      		</div>
      	</div>

      	<div class="input-group m-bottom-10">
      		<span class="input-group-addon" id="">
      			<i class="fa fa-sort-amount-asc"></i> Sort
      		</span>      		
      		<select class="company_sort form-control" id="company_sort"  name="company_sort"  title="company_sort*">
      			<option value="cm_asc">Company Name A-Z</option>	
      			<option value="cm_desc">Company Name Z-A</option>
      			<option value="act_asc">Activity A-Z</option>
      			<option value="act_desc">Activity Z-A</option>
      			<option value="sub_asc">Suburb A-Z</option>	
      			<option value="sub_desc">Suburb Z-A</option>		
      			<option value="state_asc">State A-Z</option>	
      			<option value="state_desc">State Z-A</option>																			
      		</select>     	
      	</div>

        <div class="pull-right">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary company_filter_submit">Submit</button>
        </div>

      </div>
    </div>
  </div>
</div>



<!-- Modal -->
<div class="modal fade" id="loading_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-sm">
    <div class="modal-content">
       
      <div class="modal-body clearfix pad-10">

      	<center><h3>Loading Please Wait</h3></center>
      	<center><h2><i class="fa fa-circle-o-notch fa-spin fa-5x"></i></h2></center>
      	<p>&nbsp;</p>
  
  

      </div>
    </div>
  </div>
</div>

<div class="report_result hide hidden"></div>

<?php $this->load->view('assets/logout-modal'); ?>