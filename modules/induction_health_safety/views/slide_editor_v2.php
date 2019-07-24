<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>
<?php $this->load->module('projects'); ?>
<?php $this->load->module('bulletin_board'); ?>
<?php $this->load->module('schedule'); ?>
<?php $this->load->model('admin_m'); ?>

<script src="<?php echo base_url(); ?>js/vue.js"></script>
<script src="<?php echo base_url(); ?>js/moment.min.js"></script>
<script src="<?php echo base_url(); ?>js/jmespath.js"></script>

<!-- title bar -->

<style>
  .expired{
    color: red !important;
  }

</style>

<input type = "hidden" id = "base_url" value = "<?php echo base_url() ?>">
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
        </ul>
      </div>

    </div>
  </div>
</div>
<!-- title bar -->

<div class="container-fluid" id = "ihs_app">
  <!-- Example row of columns -->
  	<div class="row">       
    	<?php $this->load->view('assets/sidebar'); ?>
    	<div class="section col-sm-12 col-md-11 col-lg-11">
      		<div class="container-fluid">
        		<div class="row" style = "border-bottom: 1px solid; border-color: #CCC">
          			<div class="col-lg-4 col-md-12 hidden-md hidden-sm hidden-xs">
            			<?php if(@$this->session->flashdata('project_deleted')): ?>
	              		<div class="m-15">
		                	<div class="border-less-box alert alert-danger fade in">
		                  		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		                  		<h4>Opps! No turning back now!</h4>
		                  		<?php echo $this->session->flashdata('project_deleted');?>
		                	</div>
	              		</div>
           				 <?php endif; ?>
			            <div class="box-head pad-10 clearfix">
			                <label style = "font-size: 21px"><?php echo $screen; ?> List</label><span> (<a href="#" data-placement="right" class="popover-test" title="" data-content="Hello there mate! Welcome to the clients screen." data-original-title="Welcome">?</a>)</span>
			                <p>This is where the Induction, Health and Safety are listed.</p>
			                <p><a href="#" class="tooltip-test" title="Tooltip">This link</a> and <a href="#" class="tooltip-test" title="Tooltip">that link</a> should have tooltips on hover.</p>               
			            </div>
          			</div>
       			</div>
		        <div class="box-area">
		        	<div class="col-sm-12 pad-5">
		        		<div class="col-sm-12 pad-5">
		        			<div class="col-sm-1 pad-5">Select Project: </div>
		        			<div class="col-sm-4 pad-5">
		        				<select class = "form-control input-sm" v-model = "project_id" v-on:change = "selectProject">
		        					<option v-for = "project_list in project_list" :value="project_list.project_id">{{ project_list.project_id + "-" +  project_list.project_name}}</option>
		        				</select>
		        			</div>
		        		</div>

		        		<div class="col-sm-5 pad-5" style = "background-color:  #b3ffff">
		        			<div class="col-sm-12 pad-5 text-center"><b>Details</b></div>

		        			<div class="col-sm-12 pad-5">
		        				<select class = "form-control input-sm" v-model = "slide_selection" v-on:change = "selectSlide">
		        					<option value="1">Slide 1 (Opening Slide)</option>
		        					<option value="2">Slide 2 (Project Outline)</option>
									<option value="3">Slide 3 (Access)</option>
									<option value="4">Slide 4 (Amenities & Emergency Exits)</option>
									<option value="5">Slide 5 (Emergency Preparedness & Response)</option>
									<option value="6">Slide 6 (PPE)</option>
		        				</select>
		        			</div>

							<div class="col-sm-12 pad-5" v-show = "slide1">
								<div class="col-sm-12 pad-5 text-center"><b>Opening Slide</b></div>
								<div class="col-sm-12 pad-5">
									<table id="fixTable" class = "table table-condensed table-bordered m-bottom-0 table-striped fancyTable" style = "font-size: 12px">
				                      	<tbody v-for = "project_details in project_details">
				                        	
				                        	<tr>
				                          		<td>Client Logo:</td>
												<td v-if = "project_details.logo_path == null">Client Logo not Available. Please Provide Client Logo on the Client List under the Company Page</td>
				                          		<td v-if = "project_details.logo_path !== null"><img :src="getImageLogo(project_details.logo_path)" alt="" style = "width: 400px"></td>
				                          	</tr>
				                          	<tr>
				                          		<td>Site Address:</td>
				                          		<td>{{ getSiteAddress(project_details.project_name,project_details.unit_level,project_details.unit_number,project_details.street,project_details.po_box,project_details.suburb,project_details.name,project_details.postcode) }}</td>
				                          	</tr>
				                          	<tr>
				                          		<td>Site Commencement Date:</td>
				                          		<td>{{ getStartDate(project_details.date_site_commencement) }}</td>
				                          	</tr>
				                          	<tr>
				                          		<td>Site Completion Date:</td>
				                          		<td>{{ getEndDate(project_details.date_site_finish) }}</td>
				                          	</tr>
				                          	<tr>
				                          		<td>Project Manager:</td>
				                          		<td>
				                          			{{ getProjectManager(project_details.pm_name,project_details.pm_mobile_number,project_details.pm_email) }}
				                          		</td>
				                          	</tr>
				                          	<tr>
				                          		<td>Leading Hand:</td>
				                          		<td>{{ getLeadingHand(project_details.lh_name,project_details.lh_mobile_number,project_details.lh_email,project_details.manual_lh,project_details.manual_lh_contact,project_details.manual_lh_email) }}</td>
				                          	</tr>
				                        </tbody>
				                    </table>
								</div>
								<div class="col-sm-12 pad-5"><button type = "button" class = "btn btn-success pull-right" v-on:click = "saveSlide1" v-show="is_saved">Save</button></div>
							</div>

							<div class="col-sm-12 pad-5" v-show = "slide2">
								<div class="col-sm-12 pad-5 text-center"><b>Project Outline</b></div>

								<div class="col-sm-12 pad-5">
									<textarea id = "project_outline" class = "form-control input-sm" style = "width: 550px; height: 200px; font-size: 20px" v-model = "projectOutline"></textarea>
								</div>
								<div class="col-sm-12 pad-5">
									<button type = "button" class = "btn btn-success btn-sm pull-right" v-on:click = "saveProjectOutline" v-show="is_saved">Save Project outline</button>
								</div>
							</div>

							<div class="col-sm-12 pad-5" v-show = "slide3">
								<div class="col-sm-12 pad-5 text-center"><b>Access</b></div>
								<div class="col-sm-12 pad-5">
									<div class="col-sm-2 pad-5">General: </div>
									<div class="col-sm-4 pad-5"><input type="text" class = "form-control input-sm" v-model = "generalSiteHours"></div>
									<div class="col-sm-2 pad-5">Noisy Works: </div>
									<div class="col-sm-4 pad-5"><input type="text" class = "form-control input-sm" v-model = "noisySiteHours"></div>
									<div class="col-sm-2 pad-5">Others: </div>
									<div class="col-sm-4 pad-5"><input type="text" class = "form-control input-sm" v-model = "otherSiteHours"></div>
									<div class="col-sm-6 pad-5"><button type = "button" class = "btn btn-success btn-sm btn-block" v-on:click="saveSiteHours" v-show="is_saved">Save</button></div>
								</div>
								<div class="col-sm-12 pad-5">
									<form action="<?php echo base_url(); ?>induction_health_safety/upload_access?project_id=<?php echo $project_id ?>" method="post" enctype="multipart/form-data">
									<span class="btn btn-primary btn-sm btn-block btn-file" v-show="is_saved">
								    	<i class = "fa fa-plus-circle"></i> Attach File<input type="file" name="userfile[]" multiple="multiple" accept="image/*" onchange="form.submit()">
									</span>
									</form>
								</div>
								<div class="col-sm-12 pad-5">
									<img :src="accessFile" alt="" style = "width: 100%" v-if="!accessPDFExt()">
									<embed :src="accessFile" width="100%" height="400px" v-if="accessPDFExt()"/>


								</div>
							</div>

							<div class="col-sm-12 pad-5" v-show = "slide4">
								<div class="col-sm-12 pad-5 text-center"><b>Amenities & Emergency Exits</b></div>
								
								<div class="col-sm-12 pad-5">
									<form action="<?php echo base_url(); ?>induction_health_safety/upload_amenities?project_id=<?php echo $project_id ?>" method="post" enctype="multipart/form-data">
									<span class="btn btn-primary btn-sm btn-block btn-file" v-show="is_saved">
								    	<i class = "fa fa-plus-circle"></i> Attach File<input type="file" name="userfile[]" multiple="multiple" accept="image/*" onchange="form.submit()">
									</span>
									</form>
								</div>
								<div class="col-sm-12 pad-5">
									<img :src="amenitiesFile" alt="" style = "width: 100%" v-if="!amenitiesPDFExt()">
									<embed :src="amenitiesFile" width="100%" height="400px" v-if="amenitiesPDFExt()"/>
								</div>

								<div class="col-sm-12 pad-5"><button type = "button" class = "btn btn-success pull-right" v-on:click="saveSlide4" v-show="is_saved">Save</button></div>
							</div>
							

							<div class="col-sm-12 pad-5" v-show = "slide5">
								<div class="col-sm-12 pad-5 text-center"><b>Emergency Preparedness & Response</b></div>

								<div class="col-sm-12 pad-5"><b>Closest Medical</b></div>

								<div class="col-sm-3 pad-5">Name:</div>
								<div class="col-sm-9 pad-5"><input type="text" class = "form-control input-sm" v-model = "medicalName"></div>

								<div class="col-sm-3 pad-5">Phone Number/s:</div>
								<div class="col-sm-9 pad-5"><input type="text" class = "form-control input-sm" v-model = "medicalPhoneNumber" v-on:blur="format_number" v-on:click="reset_number" maxlength="14"></div>

								<div class="col-sm-3 pad-5">Address:</div>
								<div class="col-sm-9 pad-5"><input type="text" class = "form-control input-sm" v-model = "medicalAddress" style = "background: white" readonly v-on:click = "enter_address(1)"></div>

								<div class="col-sm-12 pad-5"><b>Closest Emergency Hospital</b></div>

								<div class="col-sm-3 pad-5">Name:</div>
								<div class="col-sm-9 pad-5"><input type="text" class = "form-control input-sm" v-model = "emergencyName"></div>

								<div class="col-sm-3 pad-5">Phone Number/s:</div>
								<div class="col-sm-9 pad-5"><input type="text" class = "form-control input-sm" v-model = "emergencyPhoneNumber"></div>

								<div class="col-sm-3 pad-5">Address:</div>
								<div class="col-sm-9 pad-5"><input type="text" class = "form-control input-sm" v-model = "emergencyAddress" style = "background: white" readonly v-on:click = "enter_address(2)"></div>

								<div class="col-sm-12 pad-5"><button type = "button" class = "btn btn-sm btn-success pull-right" v-on:click = "saveEmeregencyDetails" v-show="is_saved">Save</button></div>
							</div>

							<div class="col-sm-12 pad-5" v-show = "slide6">
								<div class="col-sm-12 pad-5 text-center"><b>Personal Protective Equipment</b></div>

								<table class = "table table-condensed table-bordered m-bottom-0 table-striped fancyTable">
									<tr v-for = "ppe_items in ppe_items">
										<td><input type="checkbox" :value="ppe_items.ppe_item" v-model="ppe_selected"></td>
										<td>{{ ppe_items.ppe_item }}</td>
										<td><img :src="getPPEImage(ppe_items.ppe_file_name)" alt="" style = "width: 50px"></td>
									</tr>
									<tr>
										<td colspan = 3><button type = "button" class = "btn btn-success btn-sm pull-right" v-on:click="savePPE" v-show="is_saved">Save</button></td>
									</tr>
								</table>
							</div>
		        		</div>

		        		<div class="col-sm-7 pad-5">
		        			<div class="col-sm-12 pad-5 text-center"><b>Slide Preview</b></div>
		        			<div class="col-sm-12 pad-10" style = "background-color: #b3b3cc; border-style: inset; height: 600px">
		        				<div class="col-sm-12" v-show = "slide1">
			        				<img src="../uploads/project_induction_template/site_details.jpg" alt="" style = "width: 100%">
									<img :src="imgPath" alt="" class = "client_logo">
									<div class = "site_address">
										<table style = "width: 100%">
											<tr>
												<td style = "width: 200px; height:80px; padding: 5px; vertical-align: top; font-size:20px" colspan=2><b>#{{ project_id + " " + project_name }}</b></td>
											</tr>
											<tr>
												<td style = "width: 200px; padding: 5px; vertical-align: top"><b>Site Address: </b></td>
												<td style = "padding:5px">{{ siteAddress }}</td>
											</tr>

											<tr>
												<td style = "width: 200px; padding: 5px"><b>Commencement Date: </b></td>
												<td style = "padding:5px">{{ startDate }}</td>
											</tr>

											<tr>
												<td style = "width: 200px; padding: 5px"><b>Completion Date: </b></td>
												<td style = "padding:5px">{{ endDate }}</td>
											</tr>

											<tr>
												<td style = "width: 200px; padding: 5px; vertical-align: top"><b>Project Manager: </b></td>
												<td style = "padding:5px">
													{{ PMName }}
													<p>{{ this.pm_mobile_number +" "+ this.pm_email}}</p>
													<!-- <p>{{ }}</p> -->
												</td>
											</tr>

											<tr>
												<td style = "width: 200px; padding: 5px; vertical-align: top" v-show = "has_leadinghand()"><b>Leading Hand: </b></td>
												<td style = "padding:5px" v-show = "has_leadinghand()">
													<span v-if="this.LHName !== ''">
														{{ this.LHName }}
														<p>{{ this.lh_mobile_number +" "+this.lh_email}}1</p>
														<!-- <p >{{ }}</p> -->
													</span>

													<span v-if="this.LHName == ''">
														{{ this.lh_manual }}
														<p>{{ this.lh_manual_contact +" "+ this.lh_manual_email }}</p>
														<!-- <p>{{  }}</p> -->
													</span>
													
												</td>
											</tr>
											
										</table>
										
									</div>
			        			</div>

			        			<div class="col-sm-12" v-show = "slide2">
			        				<img src="../uploads/project_induction_template/project_outline.jpg" alt="" style = "width: 100%">
			        				
			        				<div style = "position: absolute; top: 60px; left: 30px; width: 700px; font-size: 21px; height:250px; display: -ms-flexbox; display: -webkit-flex; display: flex; -ms-flex-align: center; align-items: center; -webkit-box-align: center; justify-content: center;" id = "project_outline_preview">
			        					<!-- <p style = "font-size: 20px">{{ projectOutline }}</p> -->
			        				</div>
									
			        			</div>

			        			<div class="col-sm-12" v-show = "slide3">
			        				<img src="../uploads/project_induction_template/site_access.jpg" alt="" style = "width: 100%">
			        				<img :src="accessFile" alt="" style = "position: absolute; top: 20px; right: 20px; width: 550px; height: 300px"  v-if="!accessPDFExt()">
			        				<embed :src="accessFile" style = "position: absolute; top: 20px; right: 20px; width: 550px; height: 300px" v-if="accessPDFExt"/>
			        				<div style = "position: absolute; top: 270px; left: 30px">

			        					<span><b>General: </b></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span>{{ generalSiteHours }}</span><br>
			        					<span><b>Noisy Works: </b></span> <span>{{ noisySiteHours }}</span><br>
			        					<span><b>Others: </b></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span>{{ otherSiteHours }}</span><br>
			        				</div>
			        			</div>

			        			<div class="col-sm-12" v-show = "slide4">
			        				<img src="../uploads/project_induction_template/amenities_emergency_exits.jpg" alt="" style = "width: 100%">
			        				<img :src="amenitiesFile" alt="" style = "position: absolute; top: 60px; right: 20px; width: 600px; height: 270px" v-if="!amenitiesPDFExt()">
			        				<embed :src="amenitiesFile" style = "position: absolute; top: 60px; right: 20px; width: 600px; height: 270px" v-if="amenitiesPDFExt()"/>
			        				<div style = "position: absolute; top: 340px; left: 200px"><b><?php echo $induction_slide4_notes ?></b></div>
			        			</div>

			        			<div class="col-sm-12" v-show = "slide5">
			        				<img src="../uploads/project_induction_template/emergency_preparedness_response.jpg" alt="" style = "width: 100%">
			        				<div style = "position: absolute; top: 70px; left: 50px; width: 650px;">
			        					<table style = "width: 100%">
			        						<tr>
			        							<td colspan = 2 style = "padding: 5px"><b>Closest Medical</b></td>
			        						</tr>
			        						<tr>
			        							<td style = "padding: 5px">Name: </td>
			        							<td style = "padding: 5px">{{ medicalName }}</td>
			        						</tr>
			        						<tr>
			        							<td style = "padding: 5px">Contact Number/s: </td>
			        							<td style = "padding: 5px">{{ medicalPhoneNumber }}</td>
			        						</tr>
			        						<tr>
			        							<td style = "padding: 5px">Address: </td>
			        							<td style = "padding: 5px">{{ medicalAddress }}</td>
			        						</tr>

			        						<tr>
			        							<td colspan = 2 style = "padding: 5px"><b>Closest Emergency Hospital</b></td>
			        						</tr>
			        						<tr>
			        							<td style = "padding: 5px">Name: </td>
			        							<td style = "padding: 5px">{{ emergencyName }}</td>
			        						</tr>
			        						<tr>
			        							<td style = "padding: 5px">Contact Number/s: </td>
			        							<td style = "padding: 5px">{{ emergencyPhoneNumber }}</td>
			        						</tr>
			        						<tr>
			        							<td style = "padding: 5px">Address: </td>
			        							<td style = "padding: 5px">{{ emergencyAddress }}</td>
			        						</tr>
			        					</table>
			        				</div>
			        			</div>

			        			<div class="col-sm-12" v-show = "slide6">
			        				<img src="../uploads/project_induction_template/personal_protective_equipment.jpg" alt="" style = "width: 100%">
			        				<div style = "position: absolute; top: 70px; left: 150px; width: 550px;" >
			        					<div class="col-sm-6"  v-for = "ppe_selected in ppe_selected">
			        						<div class="row" v-for = "ppe_items in ppe_items" v-if="ppe_selected == ppe_items.ppe_item">
			        							<div class="col-sm-6" style = "padding-left: 50px"><img :src="getPPEImage(ppe_items.ppe_file_name)" alt="" style = "height: 40px"></div>
			        							<div class="col-sm-6">{{ ppe_items.ppe_item }}</div>
			        						</div>
			        					</div>
				
										<div class="clearfix"></div>
			        					<div class = "col-sm-12" style = "position: absolute; top: 230px; left: 0px; font-size: 12px"><b><?php echo $induction_slide6_notes ?></b></div>
			        					<!-- <table style = "width: 100%" v-for = "ppe_selected in ppe_selected">
			        						<tr v-for = "ppe_items in ppe_items" v-if="ppe_selected == ppe_items.ppe_item">
			        							<td><img :src="getPPEImage(ppe_items.ppe_file_name)" alt="" style = "width: 50px"></td>
			        							<td>{{ ppe_items.ppe_item }}</td>
			        						</tr>
			        					</table> -->
			        				</div>
			        			</div>

			        			<div class="col-sm-12 pad-10">
			        				<div class = "pull-right" style = "border: 2px solid red; padding: 20px; border-radius: 25px 0px 0px 25px; background-color: white">
			        					<span class="fa fa-file-pdf-o" style = "font-size: 30px" title = "Generate PDF for All Slide" v-on:click="generateAllSlide"></span>&nbsp;&nbsp;&nbsp;&nbsp;
			        					<span class="fa fa-file-pdf-o" style = "font-size: 30px" title = "Generate PDF for Current Slide" v-on:click="generateSelectedSlide"></span>
			        				</div>
			        			</div>
		        			</div>
		        			
		        		</div>
		        	</div>
		        </div>
		    </div>
	    </div>
	</div>

	<div class="modal fade" id="frm_address" role="dialog">
    	<div class="modal-dialog">
      		<!-- Modal content-->
      		<div class="modal-content">
        		<div class="modal-header">
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
          			<h4 class="modal-title">Enter Address</h4>
        		</div>
        		<div class="modal-body row">
          			<div class="col-sm-3 pad-5">Unit/Level</div>
          			<div class="col-sm-9 pad-5"><input type="text" class = "form-control input-sm" placeholder = "Unit/Level"></div>

          			<div class="col-sm-3 pad-5">Number</div>
          			<div class="col-sm-9 pad-5"><input type="text" class = "form-control input-sm" placeholder = "Number"></div>

          			<div class="col-sm-3 pad-5">Street</div>
          			<div class="col-sm-9 pad-5"><input type="text" class = "form-control input-sm" placeholder = "Street"></div>

					<div class="col-sm-3 pad-5">State</div>
          			<div class="col-sm-9 pad-5">
          				<select class = "form-control input-sm">
          				</select>
          			</div>   

          			<div class="col-sm-3 pad-5">State</div>
          			<div class="col-sm-9 pad-5">
          				<select class = "form-control input-sm">
          				</select>
          			</div> 

          			<div class="col-sm-3 pad-5">Suburb</div>
          			<div class="col-sm-9 pad-5">
          				<select class = "form-control input-sm">
          				</select>
          			</div> 

          			<div class="col-sm-3 pad-5">Postcode</div>
          			<div class="col-sm-9 pad-5">
          				<select class = "form-control input-sm">
          				</select>
          			</div>          			
        		</div>
        		<div class="modal-footer">
          			<button type="button" class="btn btn-default btn-success" v-on:click="insert_address" data-dismiss="modal">Insert</button>
          			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        		</div>
      		</div>
        
    	</div>
  	</div>
</div>
<style>
	.client_logo{
		position: absolute;
		top: 20px;
		right: 20px;
		width: 200px;
	}

	.site_address{
		position: absolute;
		top: 50px;
		left: 50px;
		width: 520px;
		font-size: 20px;
	}
</style>

<script>

	tinymce.init({ 
        selector:'#project_outline',
        height: '300px',
        menubar: false,
        plugins: [
        		    'advlist autolink lists link image charmap print preview anchor textcolor',
                	'searchreplace visualblocks code fullscreen',
                	'insertdatetime media table contextmenu paste code help wordcount',
              	],
        //toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
        toolbar: 'bold italic',
        browser_spellcheck: true
       
    });


	var baseurl = '<?php echo base_url(); ?>';
	var app = new Vue({
      	el: '#ihs_app',
      	data: {
      		ppe_items: [
			    { 
			    	ppe_item: 'Coveralls',
			    	ppe_file_name: 'coveralls.png',
			    },
			    { 
			    	ppe_item: 'Steel Capped Boots',
			    	ppe_file_name: 'steel_capped_boots.png',
			    },
			    { 
			    	ppe_item: 'Ear Muffs',
			    	ppe_file_name: 'ear_muffs.png',
			    },
			    { 
			    	ppe_item: 'Fall Arrest Harness',
			    	ppe_file_name: 'fall_arrest_harness.png',
			    },
			    { 
			    	ppe_item: 'Gloves',
			    	ppe_file_name: 'gloves.png',
			    },
			    { 
			    	ppe_item: 'Hard Hat',
			    	ppe_file_name: 'hard_hat.png',
			    },
			    { 
			    	ppe_item: 'High Vis Vest',
			    	ppe_file_name: 'high_vis_vest.png',
			    },
			    { 
			    	ppe_item: 'Protective Glasses',
			    	ppe_file_name: 'protective_glasses.png',
			    },
			    { 
			    	ppe_item: 'Respiratory',
			    	ppe_file_name: 'respiratory.png',
			    }, 
			   
		    ],
        	project_list: [],
        	project_name: "",
        	project_id: '<?php echo $project_id ?>',
        	project_details: [],
        	slide_details: [],
        	slide_selection: null,
        	slide1: false,
			slide2: false,
			slide3: false,
			slide4: false,
			slide5: false,
			slide6: false,
			imgPath: "",
			siteAddress: "",
			startDate: "",
			endDate: "",
			PMName: "",
			pm_mobile_number: "",
			pm_email: "",
			
			LHName: "",
			lh_mobile_number: "",
			lh_email: "",

			medicalName: "",
			medicalPhoneNumber: "",
			medicalAddress: "",
			emergencyName: "",
			emergencyPhoneNumber: "",
			emergencyAddress: "",

			projectOutline: "",

			ppe_selected: [ 'Coveralls','Steel Capped Boots'],

			generalSiteHours: '',
			noisySiteHours: '',
			otherSiteHours: '',

			accessFile: '',
			accessIsPDF: true,

			amenitiesFile: '',
			amenitiesIsPDF: true,

			is_saved: false,

       	},

       	filters: {
          	getDayname: function(date){
            	return moment(date).format('ddd');
          	},

          	ausdate: function(date) {
            	return moment(date).format('DD/MM/YYYY');
          	},
          
          	getTime: function(date){
            	return moment(date).format('h:m a');
          	},
          	replaceApos: function(string){
            	return
          	}
      	},

      	mounted: function(){
        	this.fetchProjectList();
        	this.fetchProjectDetails();
        	this.fetchSlideDetails();
        },

        methods: {
        	has_leadinghand: function(){
        		if(this.lh_mobile_number == null && this.lh_manual == null){
        			return false;
        		}else{
        			return true;
        		}
        	},


        	insert_address: function(){

        	},

        	enter_address: function(a){
        		$("#frm_address").modal("show");
        	},

        	saveSlide4: function(){
        		if(this.amenitiesFile == ""){
        			alert("Selected Project MAP is not uploaded. Please upload MAP.");
        		}else{
        			$.post(baseurl+"induction_health_safety/set_cleared_slides",
		          	{
		          		slide_no: 4,
		          		project_id: this.project_id
		          	},
		          	function(result){
		            	alert("Slide 4 Details Saved");
		          	});
        		}
        		
        	},

        	saveSlide1: function(){
        		if(this.imgPath == ""){
        			alert("Selected Contractor Logo is not uploaded. Please upload logo.");
        		}else{
        			$.post(baseurl+"induction_health_safety/set_cleared_slides",
		          	{
		          		slide_no: 1,
		          		project_id: this.project_id
		          	},
		          	function(result){
		            	alert("Slide 1 Details Saved");
		          	});
        		}
        		
        	},

        	reset_number: function(){
        		var phoneNumber = this.medicalPhoneNumber;
        		phoneNumber = phoneNumber.replace(/ /g, '');
				phoneNumber = phoneNumber.replace('(', '');
				phoneNumber = phoneNumber.replace(')', '');
				this.medicalPhoneNumber = phoneNumber;
        	},

        	format_number: function(){

        		var phoneNumber = this.medicalPhoneNumber;

        		this.medicalPhoneNumber = '('+phoneNumber.substring(0,2)+") "+phoneNumber.substring(2,6)+" "+phoneNumber.substring(6,10);
				              
        	},

        	fetchProjectList: function(){

	          	$.post(baseurl+"induction_health_safety/fetch_induction_projects_list",
	          	{},
	          	function(result){
	            	app.project_list = JSON.parse(result);
	          	});
	          
	        },

	        fetchProjectDetails: function(){
	          	$.post(baseurl+"induction_health_safety/fetch_induction_projects_details",
	          	{
	          		project_id: this.project_id
	          	},
	          	function(result){
	            	app.project_details = JSON.parse(result);
	          	});
	          
	        },

	        fetchSlideDetails: function(){
	          	$.post(baseurl+"induction_health_safety/fetch_induction_slide_detials",
	          	{
	          		project_id: this.project_id
	          	},
	          	function(result){
	            	app.slide_details = JSON.parse(result);
	          	});
	          
	        },

	        set_is_saved: function(){
	        	var is_saved = 0;
	        	for (var key in app.slide_details) {
	                is_saved = app.slide_details[key].is_saved;
	            }

	        	if(is_saved == 1){
	        		this.is_saved = false;
	        	}else{
	        		this.is_saved = true;
	        	}
	        },

	        selectProject: function(){
	        	window.open(baseurl+"induction_health_safety/induction_slide_editor_view?project_id="+this.project_id, "_self");
	        },

	        getImageLogo: function(imgpath){
	        	var imgpath = ".."+imgpath;
	        	this.imgPath = imgpath;
	        	return imgpath;
	        },

	        getSiteAddress: function(project_name,unit_level,unit_number,street,po_box,suburb,name,postcode){
	        	this.project_name = project_name;
	        	var site_address = unit_level + " " + unit_number + " " + street + " " + po_box + " " + suburb + " " + name + " " + postcode;
	        	this.siteAddress = site_address;
	        	return site_address;
	        },

	        getStartDate: function(start_date){
	        	this.startDate = start_date;
	        	return start_date;
	        },

	        getEndDate: function(end_date){
	        	this.endDate = end_date;
	        	return end_date;
	        },

	        getProjectManager: function(pm_name,pm_mobile_number,pm_email){
	        	this.PMName = pm_name;
	        	this.pm_mobile_number = pm_mobile_number;
				this.pm_email = pm_email;
	        	return pm_name;
	        },

			getLeadingHand: function(lh_name,lh_mobile_number,lh_email,lh_manual,lh_manual_contact,lh_manual_email){
				this.LHName = lh_name;
				this.lh_mobile_number = lh_mobile_number;
				this.lh_email = lh_email;
				this.lh_manual = lh_manual;
				this.lh_manual_contact = lh_manual_contact;
				this.lh_manual_email = lh_manual_email;
				return lh_name;
			},

	        selectSlide: function(){
	        	switch(this.slide_selection){
	        		case '1':
	        			this.slide1 = true;
						this.slide2 = false;
						this.slide3 = false;
						this.slide4 = false;
						this.slide5 = false;
						this.slide6 = false;
						this.set_is_saved();
	        			break;
	        		case '2':
	        			this.slide1 = false;
						this.slide2 = true;
						this.slide3 = false;
						this.slide4 = false;
						this.slide5 = false;
						this.slide6 = false;
						this.getProjectOutline();
						this.set_is_saved();
						$.post(baseurl+"induction_health_safety/fetch_induction_slide_project_details",
			          	{
			          		project_id: this.project_id
			          	},
			          	function(result){
			          		tinymce.activeEditor.setContent(result);
			          		$("#project_outline_preview").html(result);
			            	//app.slide_details = JSON.parse(result);
			          	});

	        			break;
	        		case '3':
	        			this.slide1 = false;
						this.slide2 = false;
						this.slide3 = true;
						this.slide4 = false;
						this.slide5 = false;
						this.slide6 = false;
						this.getAccessImage();
						this.getSiteHours();
						this.set_is_saved();
	        			break;
	        		case '4':
	        			this.getAmenitesImage();
	        			this.slide1 = false;
						this.slide2 = false;
						this.slide3 = false;
						this.slide4 = true;
						this.slide5 = false;
						this.slide6 = false;
						this.set_is_saved();
	        			break;
	        		case '5':
	        			this.slide1 = false;
						this.slide2 = false;
						this.slide3 = false;
						this.slide4 = false;
						this.slide5 = true;
						this.slide6 = false;
						this.getEPR();
						this.set_is_saved();
	        			break;
	        		case '6':
	        			this.slide1 = false;
						this.slide2 = false;
						this.slide3 = false;
						this.slide4 = false;
						this.slide5 = false;
						this.slide6 = true;
						this.getPPE();
						this.set_is_saved();
	        			break;
	        		default:
	        			this.slide1 = false;
						this.slide2 = false;
						this.slide3 = false;
						this.slide4 = false;
						this.slide5 = false;
						this.slide6 = false;
	        			break;

	        	}
	        },

	        accessPDFExt: function(){
	        	return this.accessIsPDF;
	        },

	        getAccessImage: function(){
		        var file_name = "";
		        var project_id = "";

		        for (var key in app.slide_details) {
	                file_name = app.slide_details[key].acces_map_filename;
	                project_id = app.slide_details[key].project_id;

	                var file_arr = file_name.split('.');
	               	var extenstion = file_arr[1];
	            }
	            // var image_path = '../uploads/project_inductions_images/'+project_id+'/'+file_name;
	            this.accessFile = '../uploads/project_inductions_images/'+project_id+'/'+file_name;
	            if(extenstion == 'pdf'){
	              	this.accessIsPDF = true;
	            }else{
	              	this.accessIsPDF = false;
	            }
	          
	            // return image_path;
   			
	        },

	        amenitiesPDFExt: function(){
	        	return this.amenitiesIsPDF;
	        },


	        getAmenitesImage: function(){
		       	var file_name = "";
		        var project_id = "";
		        for (var key in app.slide_details) {
	               	file_name = app.slide_details[key].amenities_map_filename;
	               	project_id = app.slide_details[key].project_id;

	               	var file_arr = file_name.split('.');
	               	var extenstion = file_arr[1];
	            }

	            if(extenstion == 'pdf'){
	              	this.amenitiesIsPDF = true;
	            }else{
	              	this.amenitiesIsPDF = false;
	            }

	            this.amenitiesFile = '../uploads/project_inductions_images/'+project_id+'/'+file_name;
	            // var image_path = '../uploads/project_inductions_images/'+project_id+'/'+file_name;
	            // return image_path;
	        },

	        

	        getProjectOutline: function(){
	        	for (var key in app.slide_details) {
                	this.projectOutline = app.slide_details[key].project_ouline_text;
              	}
	        	
	        	return this.projectOutline;
	        },

	        getPPE: function(){
	        	for (var key in app.slide_details) {
                	this.ppe_selected = app.slide_details[key].ppe_list;
                	this.ppe_selected = JSON.parse(this.ppe_selected);
              	}
	        },


	        getEPR: function(){
	        	for (var key in app.slide_details) {
                	this.medicalName = app.slide_details[key].epr_medical_name;
					this.medicalPhoneNumber = app.slide_details[key].epr_medical_contact;
					this.medicalAddress = app.slide_details[key].epr_medical_address;
					this.emergencyName = app.slide_details[key].epr_emergency_name;
					this.emergencyPhoneNumber = app.slide_details[key].epr_emergency_contacts;
					this.emergencyAddress = app.slide_details[key].epr_emergency_address;
              	}
	        	
	        },

	        saveProjectOutline: function(){
	        	var project_outline = tinyMCE.activeEditor.getContent();
	        	$.post(baseurl+"induction_health_safety/update_induction_slide_project_outline",
	          	{
	          		project_id: this.project_id,
	          		project_outline: project_outline
	          	},
	          	function(result){
	          		$("#project_outline_preview").html(result);
	          		alert("Project Outline Saved");
	            	//app.slide_details = JSON.parse(result);
	          	});
	        },

	        saveSiteHours: function(){
				$.post(baseurl+"induction_health_safety/update_induction_slide_site_hours",
	          	{
	          		project_id: this.project_id,
	          		generalSiteHours: this.generalSiteHours,
	          		noisySiteHours: this.noisySiteHours,
	          		otherSiteHours: this.otherSiteHours
	          	},
	          	function(result){
	          		app.project_details = JSON.parse(result);
	          		alert("Site Hours Saved");
	          		// location.reload();


	            	//app.slide_details = JSON.parse(result);
	          	});
			},

			getSiteHours: function(){
				for (var key in app.project_details) {
					this.generalSiteHours = app.project_details[key].general_site_hours;
					if(this.generalSiteHours == ""){
						this.generalSiteHours = '7am to 5pm';
					}
					this.noisySiteHours = app.project_details[key].noisy_site_hours;
					this.otherSiteHours = app.project_details[key].other_site_hours;
				}
			},



	        getPPEImage: function(ppe_file_name){
	        	var img_path = '../uploads/project_induction_template/ppe/'+ppe_file_name;
	        	return img_path;
	        },

	        

			saveEmeregencyDetails: function(){
				$.post(baseurl+"induction_health_safety/update_induction_slide_emergency",
	          	{
	          		project_id: this.project_id,
	          		medical_name: this.medicalName,
					medical_phone_number: this.medicalPhoneNumber,
					medical_address: this.medicalAddress,
					emergency_name: this.emergencyName,
					emergency_phone_number: this.emergencyPhoneNumber,
					emergency_address: this.emergencyAddress,
	          	},
	          	function(result){
	          		alert("Project Medical Emergency Details Saved");
	            	app.slide_details = JSON.parse(result);
	          	});
				
			},

			
			savePPE: function(){
				$.post(baseurl+"induction_health_safety/update_induction_slide_ppe",
	          	{
	          		project_id: this.project_id,
	          		ppe_selected: this.ppe_selected
	          	},
	          	function(result){
	          		alert("PPE Saved");
	          		app.slide_details = JSON.parse(result);
	          	});
			},



			generateAllSlide: function(){
				var cleared_slides = "";
				for (var key in app.project_details) {
					cleared_slides = app.project_details[key].cleared_slides;
				}

				var cleared_slides_arr = cleared_slides.split(",");
				var num = cleared_slides_arr.length;

				if(num < 6){
					alert("Some Slide have not been completed please update and save slides");
				}else{
					var x = 1;
					while(x <= 6){
						window.open(baseurl+"induction_health_safety/generated_selected_pdf?project_id="+this.project_id+"&slide_no="+x);
						x++;
					}
				}

				$.post(baseurl+"induction_health_safety/set_inductions_as_saved",
	          	{
	          		project_id: this.project_id
	          	},
	          	function(result){
	          		app.slide_details = JSON.parse(result);
	          	});


			},

			generateSelectedSlide: function(){
				if(this.slide1 || this.slide2 || this.slide3 || this.slide4 || this.slide5 || this.slide6){
					if(this.slide1 == true){
						window.open(baseurl+"induction_health_safety/generated_selected_pdf?project_id="+this.project_id+"&slide_no=1");
					}

					if(this.slide2 == true){
						window.open(baseurl+"induction_health_safety/generated_selected_pdf?project_id="+this.project_id+"&slide_no=2");
					}

					if(this.slide3 == true){
						window.open(baseurl+"induction_health_safety/generated_selected_pdf?project_id="+this.project_id+"&slide_no=3");
					}

					if(this.slide4 == true){
						window.open(baseurl+"induction_health_safety/generated_selected_pdf?project_id="+this.project_id+"&slide_no=4");
					}

					if(this.slide5 == true){
						window.open(baseurl+"induction_health_safety/generated_selected_pdf?project_id="+this.project_id+"&slide_no=5");
					}

					if(this.slide6 == true){
						window.open(baseurl+"induction_health_safety/generated_selected_pdf?project_id="+this.project_id+"&slide_no=6");
					}
				}else{
					alert("Please Select Slide");
				}
			},

        },
    });
</script>
