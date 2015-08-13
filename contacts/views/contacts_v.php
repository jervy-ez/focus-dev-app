<?php date_default_timezone_set("Australia/Perth"); ?>
<?php $this->load->module('contacts'); ?>
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

		</div>
	</div>
</div>
<!-- title bar -->
<div class="container-fluid">
	<!-- Example row of columns -->
	<div class="row">				
		<?php $this->load->view('assets/sidebar'); ?>
		<div class="section col-sm-12 col-md-11 col-lg-11">
			<form action="" method = "POST">
				<?php 
					if(isset($_POST['sort_contact_type'])){
						$contact_type = $_POST['sort_contact_type'];
					}else{
						$contact_type = 0;
					}
				?>
			<div class="container-fluid">
				<div class="col-sm-6 pad-10">
					<label for="">Select Contact Type:</label>
					<select id = "sort_contact_type" name = "sort_contact_type" class="input-sm">
						<option value="0" <?php if($contact_type == 0): echo 'selected'; endif; ?>>All</option>
						<option value="1" <?php if($contact_type == 1): echo 'selected'; endif; ?>>Client</option>
						<option value="2" <?php if($contact_type == 2): echo 'selected'; endif; ?>>Contractor</option>
						<option value="3" <?php if($contact_type == 3): echo 'selected'; endif; ?>>Supplier</option>
						<option value="4" <?php if($contact_type == 4): echo 'selected'; endif; ?>>Focus Company</option>
					</select>
					<button type = "submit" class = "btn btn-success input-sm" >Filter</button>
				</div>
				<div class="col-sm-12">
					<div class="left-section-box">
						<div class="box-area pad-10">
							<table id="companyTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>Contact Name</th>
										<th>Company</th>
										<th>Description</th>
										<th>Suburb</th>
										<th>State</th>
										<th>Contact Number</th>
										<th>Email</th>
									</tr>
								</thead>
								<tbody>
									<?php echo $this->contacts->display_contacts($contact_type); ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="contact_information" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style = "width: 700px">
	    <div class="modal-content">
	        <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		        <h4 class="modal-title">Contact Information</h4>
	        </div>
	        <div class="modal-body" style = "height: 400px">
	        	<div class="col-sm-2 pad-5">Contact Name: </div>
	        	<div class="col-sm-10 pad-5"><label for="" id = "contact_name" style = "font-size: 18px"></label></div>

	        	<div class="col-sm-12"></div>
	        	
	        	<div class="col-sm-2 pad-5">Company: </div>
	        	<div class="col-sm-10 pad-5"><label for="" id = "company_name"></label></div>
	        	
	        	<div class="col-sm-12"></div>

	        	<div class="col-sm-2 pad-5">Location: </div>
	        	<div class="col-sm-10 pad-5"><label for="" id = "company_location"></label></div>
	        	
				<div class="col-sm-12"></div>

	        	<div class="col-sm-2 pad-5">Description: </div>
	        	<div class="col-sm-10 pad-5"><label for="" id = "company_desc"></label></div>
	        	
	        	<div class="col-sm-12"></div>

	        	<div class="col-sm-12 pad-10"><label for="">Contact Details</label></div>
	        	<hr style = "display: block; border-style: inset; border-width: 1px;">

	        	<div class="col-sm-2 pad-5">Office Number: </div>
	        	<div class="col-sm-10 pad-5"><label for="" id = "office_num"></label></div>

	        	<div class="col-sm-12"></div>

	        	<div class="col-sm-2 pad-5">Mobile: </div>
	        	<div class="col-sm-10 pad-5"><label for="" id = "mobile"></label></div>
	        	
	        	<div class="col-sm-12"></div>
	        	
	        	<div class="col-sm-2 pad-5">E-mail: </div>
	        	<div class="col-sm-10 pad-5"><label for="" id = "cont_email"></label></div>
	        </div>
	        <div class="modal-footer">
	          	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        </div>
	    </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php $this->load->view('assets/logout-modal'); ?>