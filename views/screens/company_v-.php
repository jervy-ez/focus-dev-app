<?php $this->load->module('logout'); ?>
<?php $this->load->module('parts'); ?>
<?php $this->load->module('validation'); ?>
<?php $this->logout->userLogout(); ?>
<?php $this->logout->checkUserSession($this->session->userdata('logged_in_session')); ?>
<?php $this->parts->head(); ?>
<?php $this->parts->top_navigation(); ?>
<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<!-- title bar -->
<div class="container-fluid head-control">
	<div class="container-fluid">
		<div class="row">

			<div class="col-md-6 col-sm-4 col-xs-12 pull-left">
				<header class="page-header">
					<h3><?php $datestring = "%l, %F %d, %Y"; $time = time(); //use time() for timestamp  ?>
						Company<br><small><?php echo mdate($datestring, $time); #echo date("l, F d, Y"); ?></small>
					</h3>
				</header>
			</div>

			<div class="page-nav-options col-md-6 col-sm-8 col-xs-12 pull-right hidden-xs">
				<ul class="nav nav-tabs navbar-right">
					<li>
						<a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Home</a>
					</li>
					<li class="active">
						<a href="#" class="btn-small">Clients</a>
					</li>
					<li>
						<a href="#" class="btn-small">Sub Contractor</a>
					</li>
					<li>
						<a href="#" class="btn-small">Supplier</a>
					</li>
					<li>
						<a href="#" class="btn-small"><i class="fa fa-magic"></i> Tour</a>
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
		<?php $this->parts->sidebar(); ?>
		<div class="section col-sm-12 col-md-11 col-lg-11">
			<div class="container-fluid">				
				
				<div class="row">				
					<div class="col-md-9">
						<?php if(isset($add) && $formError): ?>
						<div class="border-less-box alert alert-danger fade in">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h4>Oh snap! You got an error!</h4>
							<?php echo validation_errors('<p>', '</p>'); ?>
							<p>
								<button type="button" class="btn btn-danger" id="loading-example-btn"  data-loading-text="Loading..." >Take this action</button>
								<button type="button" class="btn btn-default">Or do this</button>
							</p>
						</div>
						<?php endif; ?>
						
						
					
						<div class="left-section-box">
							<?php if(isset($client)): ?>
							<div class="box-head pad-10 clearfix">
								<a href="./company/add" class="btn btn-primary pull-right">Add New</a>
								<label>Client List</label><span> (<a href="#" data-placement="right" class="popover-test" title="" data-content="Hello there mate! Welcome to the clients screen." data-original-title="Welcome">?</a>)</span>
								<p>
									This is where the companies are listed.
								</p>
								<p>
									<a href="#" class="tooltip-test" title="Tooltip">This link</a> and <a href="#" class="tooltip-test" title="Tooltip">that link</a> should have tooltips on hover.
								</p>								
							</div>
							<div class="box-area pad-10">
								<table id="companyTable" class="table table-striped table-bordered" cellspacing="0" width="100%"><thead><tr><th>Company Name</th><th>Contact Person</th><th>Contact Number</th><th>Email</th></tr></thead><tbody><tr><td><a href="#" class="tooltip-test" title="Tooltip">Tiger Nixon</a></td><td>System Architect</td><td>Edinburgh</td><td>$320,800</td></tr><tr><td>Garrett Winters</td><td>Accountant</td><td>Tokyo</td><td>$170,750</td></tr><tr><td>Ashton Cox</td><td>Junior Technical Author</td><td>San Francisco</td><td>$86,000</td></tr><tr><td>Cedric Kelly</td><td>Senior Javascript Developer</td><td>Edinburgh</td><td>$433,060</td></tr><tr><td>Brielle Williamson</td><td>Integration Specialist</td><td>New York</td><td>$372,000</td></tr><tr><td>Herrod Chandler</td><td>Sales Assistant</td><td>San Francisco</td><td>$137,500</td></tr><tr><td>Rhona Davidson</td><td>Integration Specialist</td><td>Tokyo</td><td>$327,900</td></tr><tr><td>Colleen Hurst</td><td>Javascript Developer</td><td>San Francisco</td><td>$205,500</td></tr><tr><td>Sonya Frost</td><td>Software Engineer</td><td>Edinburgh</td><td>$103,600</td></tr><tr><td>Jena Gaines</td><td>Office Manager</td><td>London</td><td>$90,560</td></tr><tr><td>Quinn Flynn</td><td>Support Lead</td><td>Edinburgh</td><td>$342,000</td></tr><tr><td>Vivian Harrell</td><td>Financial Controller</td><td>San Francisco</td><td>$452,500</td></tr><tr><td>Timothy Mooney</td><td>Office Manager</td><td>London</td><td>$136,200</td></tr><tr><td>Jackson Bradshaw</td><td>Director</td><td>New York</td><td>$645,750</td></tr><tr><td>Olivia Liang</td><td>Support Engineer</td><td>Singapore</td><td>$234,500</td></tr><tr><td>Bruno Nash</td><td>Software Engineer</td><td>London</td><td>$163,500</td></tr><tr><td>Sakura Yamamoto</td><td>Support Engineer</td><td>Tokyo</td><td>$139,575</td></tr><tr><td>Thor Walton</td><td>Developer</td><td>New York</td><td>$98,540</td></tr><tr><td>Finn Camacho</td><td>Support Engineer</td><td>San Francisco</td><td>$87,500</td></tr><tr><td>Serge Baldwin</td><td>Data Coordinator</td><td>Singapore</td><td>$138,575</td></tr><tr><td>Zenaida Frank</td><td>Software Engineer</td><td>New York</td><td>$125,250</td></tr><tr><td>Zorita Serrano</td><td>Software Engineer</td><td>San Francisco</td><td>$115,000</td></tr><tr><td>Jennifer Acosta</td><td>Junior Javascript Developer</td><td>Edinburgh</td><td>$75,650</td></tr><tr><td>Cara Stevens</td><td>Sales Assistant</td><td>New York</td><td>$145,600</td></tr><tr><td>Hermione Butler</td><td>Regional Director</td><td>London</td><td>$356,250</td></tr><tr><td>Lael Greer</td><td>Systems Administrator</td><td>London</td><td>$103,500</td></tr><tr><td>Jonas Alexander</td><td>Developer</td><td>San Francisco</td><td>$86,500</td></tr><tr><td>Shad Decker</td><td>Regional Director</td><td>Edinburgh</td><td>$183,000</td></tr><tr><td>Michael Bruce</td><td>Javascript Developer</td><td>Singapore</td><td>$183,000</td></tr><tr><td>Donna Snider</td><td>Customer Support</td><td>New York</td><td>$112,000</td></tr></tbody></table>
							</div>
							<?php endif; ?>
														
							<?php if(isset($add)): ?>
							<form class="form-horizontal" role="form" action="" method="post">
								<div class="box-head pad-10 clearfix">
									<button  type="reset" class="btn btn-warning pull-right">Reset Form</button >			
									<label>Add New Client</label><span> (<a href="#" data-placement="right" class="popover-test" title="" data-content="Hello there mate! Welcome to the clients screen." data-original-title="Welcome">?</a>)</span>
									<p>This is where the companies are listed.</p>
									<p>
										<a href="#" class="tooltip-test" title="Tooltip">This link</a> and <a href="#" class="tooltip-test" title="Tooltip">that link</a> should have tooltips on hover.
									</p>								
								</div>
								<div class="box-area pad-10">
									<div class="form-group">
        								<div class="col-sm-12">
         									<div class="input-group <?php if($formError && !$this->validation->set_val_input('company_name')){ echo 'has-error has-feedback';} ?>">
												<span class="input-group-addon"><i class="fa fa-briefcase  fa-lg"></i></span>
												<input type="text" name="company_name" value="<?php echo $this->validation->set_val_input('company_name'); ?>" class="form-control" placeholder="Company Name">
											</div>
        								</div>
      								</div>
      								
      								<div class="box-tabs m-bottom-15">
										<ul id="myTab" class="nav nav-tabs">
											<li class="active">
												<a href="#physicalAddress" data-toggle="tab"><i class="fa fa-globe fa-lg"></i> Physical Address</a>
											</li>
											<li class="">
												<a href="#postalAddress" data-toggle="tab"><i class="fa fa-inbox fa-lg"></i> Postal Address</a>
											</li>											
										</ul>
										<div class="tab-content">
											<div class="tab-pane fade active in clearfix" id="physicalAddress">
												
												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="unitlevel" class="col-sm-3 control-label">Unit/Level</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="unitlevel" name="unitlevel" placeholder="Unit/Level" value="<?php echo set_value('unitlevel',$this->input->post('unitlevel') ); ?>">
													</div>
												</div>
											
												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="number" class="col-sm-3 control-label">Number</label>
													<div class="col-sm-9">
														<input type="email" class="form-control" id="number" placeholder="Number">
													</div>
												</div>
											
												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="street" class="col-sm-3 control-label">Street*</label>
													<div class="col-sm-9">
														<input type="email" class="form-control" id="street" placeholder="Street">
													</div>
												</div>
												
												<div class="col-sm-6 col-xs-12 m-bottom-10 clearfix">
													<label for="suburb" class="col-sm-3 control-label">Suburb*</label>
													<div class="col-sm-9 col-xs-12">
														<select class="form-control" id="suburb">
															<option>Choose a Suburb...</option>
															<option value="add">Add New</option>
															<option>1</option>
															<option>2</option>
															<option>3</option>
															<option>4</option>
															<option>5</option>
														</select>
													</div>
												</div>
												
												<!--
												<div id="datepicker" class="input-prepend date">
													<span class="add-on"><i class="icon-th"></i></span>
													<input class="span2" type="text" value="02-16-2012">
												</div>
												-->
												
												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="state" class="col-sm-3 control-label">State*</label>
													<div class="col-sm-9">
														<input type="email" class="form-control" id="state" placeholder="State">
													</div>
												</div>
												
												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="postcode" class="col-sm-3 control-label">Postcode*</label>
													<div class="col-sm-9">
														<input type="email" class="form-control" id="postcode" placeholder="Postcode">
													</div>
												</div>
												
												<div class="col-md-offset-1 col-sm-offset-2 col-sm-7 col-lg-6 col-md-7 m-bottom-10 clearfix">
													<div class="col-sm-12">
														<div class="input-group">
															<input type="text" id="disabledInput" value="Same values to Postal Address?" class="form-control disabled" disabled>
															<span class="input-group-addon"> <input type="checkbox" class="sameToPost"> Yes</span>
														</div>
													</div>
												</div>
											
											</div>
											<div class="tab-pane fade clearfix" id="postalAddress">
												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="pobox" class="col-sm-3 control-label">PO Box</label>
													<div class="col-sm-9">
														<input type="email" class="form-control" id="pobox" placeholder="PO Box">
													</div>
												</div>
												
												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="unitlevel2" class="col-sm-3 control-label">Unit/Level</label>
													<div class="col-sm-9">
														<input type="email" class="form-control" id="unitlevel2" placeholder="Unit/Level">
													</div>
												</div>
											
												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="number2" class="col-sm-3 control-label">Number</label>
													<div class="col-sm-9">
														<input type="email" class="form-control" id="number2" placeholder="Number">
													</div>
												</div>
											
												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="street2" class="col-sm-3 control-label">Street*</label>
													<div class="col-sm-9">
														<input type="email" class="form-control" id="street2" placeholder="Street">
													</div>
												</div>
												
												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="suburb2" class="col-sm-3 control-label">Suburb*</label>
													<div class="col-sm-9">
														<!-- <input type="email" class="form-control" id="suburb2" placeholder="Suburb"> -->
														<select class="form-control" id="suburb2">
															<option>Select</option>
															<option>1</option>
															<option>2</option>
															<option>3</option>
															<option>4</option>
															<option>5</option>
														</select>
													</div>
												</div>
												
												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="state2" class="col-sm-3 control-label">State*</label>
													<div class="col-sm-9">
														<input type="email" class="form-control" id="state2" placeholder="State">
													</div>
												</div>
												
												<div class="col-sm-6 m-bottom-10 clearfix">
													<label for="postcode2" class="col-sm-3 control-label">Postcode*</label>
													<div class="col-sm-9">
														<input type="email" class="form-control" id="postcode2" placeholder="Area Code">
													</div>
												</div>
											</div>
										</div>
									</div>
      								
      								<div class="col-sm-6 m-bottom-10 clearfix">
										<label for="abn" class="col-sm-3 control-label">ABN*</label>
										<div class="col-sm-9">
											<input type="email" class="form-control" id="abn" placeholder="ABN">
										</div>
									</div>
      								
      								<div class="col-sm-6 m-bottom-10 clearfix">
										<label for="acn" class="col-sm-3 control-label">ACN*</label>
										<div class="col-sm-9">
											<input type="email" class="form-control" id="acn" placeholder="ACN">
										</div>
									</div>
      								
      								<div class="col-sm-6 m-bottom-10 clearfix">
										<label for="staxnum" class="col-sm-3 control-label">Stax*</label>
										<div class="col-sm-9">
											<input type="email" class="form-control" id="staxnum" placeholder="Stax Number">
										</div>
									</div>
      								
      								<div class="col-sm-6 m-bottom-10 clearfix">
										<label for="activity" class="col-sm-3 control-label">Activity*</label>
										<div class="col-sm-9">
											<select class="form-control" id="activity">
												<option>Select</option>
												<option>1</option>
												<option>2</option>
												<option>3</option>
												<option>4</option>
												<option>5</option>
											</select>
										</div>
									</div>
									
									<div class="col-sm-6 m-bottom-10 clearfix">
										<label for="activity" class="col-sm-3 control-label">Parent*</label>
										<div class="col-sm-9">
											<select class="form-control" id="activity">
												<option>Select</option>
												<option>None</option>
												<option>1</option>
												<option>2</option>
												<option>3</option>
												<option>4</option>
												<option>5</option>
											</select>
										</div>
									</div>
									
									<div class="clearfix"></div>
									
									<div class="box">
										<div class="box-head pad-5 m-bottom-5">
											<label><i class="fa fa-phone-square fa-lg"></i> Telephone</label>
										</div>
										
										<div class="box-area pad-5 clearfix">
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
												<label for="areaCode" class="col-sm-3 control-label">Areacode*</label>
												<div class="col-sm-9">
													<input type="email" class="form-control" id="areaCode" placeholder="Area Code">
												</div>
											</div>
											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
												<label for="officeNumber" class="col-sm-3 control-label">Office*</label>
												<div class="col-sm-9">
													<input type="email" class="form-control" id="officeNumber" placeholder="Office Number">
												</div>
											</div>
											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
												<label for="directNumber" class="col-sm-3 control-label">Direct</label>
												<div class="col-sm-9">
													<input type="email" class="form-control" id="directNumber" placeholder="Direct Number">
												</div>
											</div>
											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
												<label for="mobileNumber" class="col-sm-3 control-label">Mobile</label>
												<div class="col-sm-9">
													<input type="email" class="form-control" id="mobileNumber" placeholder="Mobile Number">
												</div>
											</div>
										</div>
									</div>
									
									<div class="clearfix"></div>
								    
      								<div class="box">
										<div class="box-head pad-5 m-bottom-5">
											<label><i class="fa fa-envelope fa-lg"></i> Email</label>
										</div>
										
										<div class="box-area pad-5 clearfix">
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
												<label for="generalEmail" class="col-sm-3 control-label">General*</label>
												<div class="col-sm-9">
													<input type="email" class="form-control" id="generalEmail" placeholder="General">
												</div>
											</div>
											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
												<label for="directEmail" class="col-sm-3 control-label">Direct</label>
												<div class="col-sm-9">
													<input type="email" class="form-control" id="directEmail" placeholder="Direct">
												</div>
											</div>
											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
												<label for="accountsEmail" class="col-sm-3 control-label">Accounts</label>
												<div class="col-sm-9">
													<input type="email" class="form-control" id="accountsEmail" placeholder="Accounts">
												</div>
											</div>
											
											<div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix">
												<label for="maintenanceEmail" class="col-lg-3 col-sm-3 control-label">Maintenance</label>
												<div class="col-lg-9 col-sm-9">
													<input type="email" class="form-control" id="maintenanceEmail" placeholder="Maintenance">
												</div>
											</div>
										</div>
									</div>
									
									<div class="m-top-10 clearfix">
										<div class="clearfix">
											<label for="comments" class="control-label">Comments</label>
											<textarea class="form-control" id="comments" rows="3"></textarea>
										</div>
									</div>
									
								    <div class="m-top-15 clearfix">
								    	<div>
								        	<input type="submit" name="addNewClientSubmit" class="btn btn-success" value="Save" />
								        </div>
								    </div>
								</div>
							</form>
							<?php endif; ?>
						</div>
					</div>
					
					<div class="col-md-3">
						<?php if(isset($client)): ?>
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
												<p class="news-item-preview">
													May 25, 2014
												</p>
											</div>
										</li>
										<li>
											<div>
												<a href="#" class="news-item-title">Updated the company details and contact information for James Tiling Co.</a>
												<p class="news-item-preview">
													May 20, 2014
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
						<?php endif; ?>
						
						<?php if(isset($add)): ?>
						<div class="box">
							<div class="box-head pad-5">
								<label><i class="fa fa-info-circle fa-lg"></i> Header</label>
							</div>
							<div class="box-area pad-5">
								<p>Project Created: <strong>6/12/2014</strong></p>
								<p>Select Contact Person</p>
								<div class="clearfix">
									<select data-placeholder="Select Contact Person" class="chosen" id="contactperson" style="width: 100%;">
										<option></option>
										<option value="add">Add New</option>
										<option>Alton Vallo</option>
										<option>Ami Armbruster</option>
										<option>Amina Joo</option>
										<option>Andree Rodreguez</option>
										<option>Angel Drum</option>
										<option>Angie Jarvie</option>
										<option>Archie Legg</option>
										<option>Aubrey Belue</option>
										<option>Ava Rizer</option>
										<option>Avery Rinaldi</option>
										<option>Barb Verge</option>
										<option>Billye Rebelo</option>
										<option>Blake Garand</option>
										<option>Bo Reeb</option>
										<option>Brice Nuckles</option>
										<option>Bryan Hutter</option>
										<option>Charles Huertas</option>
										<option>Chery Bert</option>
										<option>Chet Traynor</option>
										<option>Chris Coons</option>
										<option>Christen Lowder</option>
										<option>Cindy Good</option>
										<option>Clark Griffieth</option>
										<option>Claudio Mayson</option>
										<option>Clayton Woodside</option>
										<option>Cleotilde Reimers</option>
										<option>Clifford Tumlin</option>
										<option>Dale Wison</option>
										<option>Daryl Ross</option>
										<option>Dell Alcina</option>
										<option>Demarcus Markowitz</option>
										<option>Denyse True</option>
										<option>Elane Bever</option>
										<option>Elijah Alberti</option>
										<option>Elise Molter</option>
										<option>Ellis Duppstadt</option>
										<option>Emmitt Riojas</option>
										<option>Erica Spang</option>
										<option>Etsuko Mcilrath</option>
										<option>Evia Kellerhouse</option>
										<option>Francene Henze</option>
										<option>Genny Neathery</option>
										<option>Gerald Begay</option>
										<option>Heide Coker</option>
										<option>Hortensia Mills</option>
										<option>Jacquline Gerard</option>
										<option>Jarrett Mattews</option>
										<option>Jeni Schanz</option>
										<option>Julieann Faw</option>
										<option>Kanesha Cowherd</option>
										<option>Keenan Dampier</option>
										<option>Keshia Edward</option>
										<option>Kevin Macdonald</option>
										<option>Khadijah Feder</option>
										<option>Kirk Spadoni</option>
										<option>Kory Arney</option>
										<option>Kyoko Rollo</option>
										<option>Lashell Duda</option>
										<option>Leigh Storie</option>
										<option>Lynne Lapan</option>
										<option>Maddie Maxim</option>
										<option>Manuel Oneil</option>
										<option>Margarito Blount</option>
										<option>Matt Scally</option>
										<option>Mui Polak</option>
										<option>Myles Moller</option>
										<option>Nedra Avilla</option>
										<option>Noble Ehlert</option>
										<option>Nola Songer</option>
										<option>Nona Gaskamp</option>
										<option>Owen Harig</option>
										<option>Philip Going</option>
										<option>Phylicia Easley</option>
										<option>Phyllis Grubaugh</option>
										<option>Ray Shapiro</option>
										<option>Raye Trimm</option>
										<option>Raymond Vasbinder</option>
										<option>Reed Rossin</option>
										<option>Regan Mcneal</option>
										<option>Reyes Hardie</option>
										<option>Richard Willcutt</option>
										<option>Richie Bearden</option>
										<option>Roscoe Ovitt</option>
										<option>Shantae Christo</option>
										<option>Sheldon Gathings</option>
										<option>Shin Mattos</option>
										<option>Stephine Jeffreys</option>
										<option>Suanne Bergh</option>
										<option>Sydney Ebinger</option>
										<option>Tasha Mastroianni</option>
										<option>Tomoko Vella</option>
										<option>Trent Paek</option>
										<option>Trina Loera</option>
										<option>Trula Marcella</option>
										<option>Van Fleitas</option>
										<option>Victoria Buchholz</option>
										<option>Winford Mcdaniels</option>
										<option>Xuan Hinerman</option>
										<option>Zachariah Melendrez</option>
										<option>Zachary Hardrick</option>
									</select>
								</div>
							</div>
						</div>
						
						<div class="box">
							<div class="box-head pad-5">
								<label><i class="fa fa-info-circle fa-lg"></i> Information</label>
							</div>
							<div class="box-area pad-5">
								<p>
									The table can be sortable by the header. It has search feature using a sub text or keyword you are searching. Clicking the Name of the company will lead to the Company Details Screen.
								</p>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
							</div>
						</div>
						<?php endif; ?>
					</div>
				</div>				
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('modal-sample'); ?>
<?php $this->logout->index(); ?>
<?php $footerDisplay = array('table'=>1,'maps'=>0,'chart'=>0,'tour'=>1); ?>
<?php $this->parts->footer($footerDisplay); ?>