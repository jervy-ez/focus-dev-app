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

      <div class="col-md-5 col-sm-4 col-xs-12 pull-left">
        <header class="page-header">
          <h3><?php $datestring = "%l, %F %d, %Y"; $time = time(); //use time() for timestamp  ?>
            <?php echo $screen; ?> Screen<br><small><?php echo mdate($datestring, $time); #echo date("l, F d, Y"); ?></small>
          </h3>
        </header>
      </div>

      <div class="page-nav-options col-md-7 col-sm-8 col-xs-12 pull-right hidden-xs">
        <ul class="nav nav-tabs navbar-right">
          	<li>
            	<a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Home</a>
          	</li>
          	<?php if($this->session->userdata('is_admin') == 1 || $this->session->userdata('user_role_id') == 4): ?>
          	<li>
            	<a href="<?php echo base_url(); ?>induction_health_safety"><i class="fa fa-home"></i> Induction Site Staff</a>
          	</li>
          	<li>
            	<a href="<?php echo base_url(); ?>induction_health_safety/inductions_projects"><i class="fa fa-home"></i> Induction Projects</a>
          	</li>
           	<li>
            	<a href="<?php echo base_url(); ?>induction_health_safety/inductions_videos"><i class="fa fa-home"></i> Uploading Video for Induction</a>
          	</li>
          	<?php if(isset($_GET['project_id'])): ?>
	        <li>
	            <a href="<?php echo base_url(); ?>induction_health_safety/induction_slide_editor_view?project_id=<?php echo $_GET['project_id'] ?>"><i class="fa fa-home"></i> Induction Slide Templates</a>
	        </li>
	    	<?php else: ?>
	    	<li>
	            <a href="<?php echo base_url(); ?>induction_health_safety/induction_slide_editor_view"><i class="fa fa-home"></i> Induction Slide Templates</a>
	        </li>
	        <?php endif; ?>
      		<?php endif; ?>
      		<?php if($this->session->userdata('user_role_id') == 2 || $this->session->userdata('user_role_id') == 3 || $this->session->userdata('user_role_id') == 20): ?>
      		<?php if(isset($_GET['project_id'])): ?>
	        <li>
	            <a href="<?php echo base_url(); ?>induction_health_safety/induction_slide_editor_view?project_id=<?php echo $_GET['project_id'] ?>"><i class="fa fa-home"></i> Induction Slide Templates</a>
	        </li>
	    	<?php else: ?>
	    	<li>
	            <a href="<?php echo base_url(); ?>induction_health_safety/induction_slide_editor_view"><i class="fa fa-home"></i> Induction Slide Templates</a>
	        </li>
	        <?php endif; ?>
	        <li>
	            <a href="<?php echo base_url(); ?>induction_health_safety/inductions_projects"><i class="fa fa-home"></i> Induction Projects</a>
	        </li>
	        <?php if(isset($_GET['project_id'])): ?>
	        <?php if($this->session->userdata('user_role_id') == 2): ?>
	        <li>
	            <a href="<?php echo base_url(); ?>induction_health_safety/project_induction_site_staff?project_id=<?php echo $project_id ?>"><i class="fa fa-video"></i> Send Induction Video Link</a>
	        </li>
	        <?php endif; ?>
	        <li>
	        	<a href="<?php echo base_url(); ?>projects/view/<?php echo $project_id ?>"><i class="fa fa-map-marker"></i> Project Details</a>
	        </li>
	        <?php endif; ?>
	        <?php endif; ?>
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
          				<?php if($this->session->userdata('is_admin') == 1 || $this->session->userdata('user_role_id') == 4): ?>
          				<?php if(isset($_GET['project_id'])): ?>
	            			<a href="<?php echo base_url(); ?>induction_health_safety/project_induction_site_staff?project_id=<?php echo $project_id ?>" class = "btn btn-success btn-sm pull-right"><i class="fa fa-video"></i> Send Induction Video Link</a>
	            			<a href="<?php echo base_url(); ?>projects/view/<?php echo $project_id ?>" class = "btn btn-success btn-sm pull-right"><i class="fa fa-map-marker"></i> Project Details</a>
	      				<?php endif; ?>
	      				<?php endif; ?>
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
												<td v-if = "project_details.has_brand_logo == 0">Brand Logo not Available. Please Provide Brand Logo on the Brand Tab, under the Projects Page</td>
				                          		<td v-if = "project_details.has_brand_logo == 1"><img :src="getImageLogo(project_details.brand_id,project_details.has_brand_logo)" alt="" style = "width: 400px"></td>
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
									<img :src="accessFile" alt="" style = "width: 100%" v-if="!accessPDFExt()" v-show = "accessFileUploaded">
									<embed :src="accessFile" width="100%" height="400px" v-if="accessPDFExt()" v-show = "accessFileUploaded"/>


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
									<img :src="amenitiesFile" alt="" style = "width: 100%" v-if="!amenitiesPDFExt()" v-show = "amenitiesPDFExtUploaded">
									<embed :src="amenitiesFile" width="100%" height="400px" v-if="amenitiesPDFExt()" v-show = "amenitiesPDFExtUploaded"/>
								</div>

								<div class="col-sm-12 pad-5"><button type = "button" class = "btn btn-success pull-right" v-on:click="saveSlide4" v-show="is_saved">Save</button></div>
							</div>
							

							<div class="col-sm-12 pad-5" v-show = "slide5">
								<div class="col-sm-12 pad-5 text-center"><b>Emergency Preparedness & Response</b></div>

								<div class="col-sm-12 pad-5"><b>Closest Medical</b></div>

								<div class="col-sm-3 pad-5">Name:</div>
								<div class="col-sm-9 pad-5"><input type="text" class = "form-control input-sm" v-model = "medicalName"></div>

								<div class="col-sm-3 pad-5">Phone Number/s:</div>
								<div class="col-sm-9 pad-5"><input type="text" class = "form-control input-sm" v-model = "medicalPhoneNumber" v-on:blur="format_number(1)" v-on:click="reset_number(1)" maxlength="14"></div>

								<div class="col-sm-3 pad-5">Address:</div>
								<div class="col-sm-9 pad-5"><input type="text" class = "form-control input-sm" v-model = "medicalAddress" style = "background: white" readonly v-on:click = "enter_address(1)"></div>

								<div class="col-sm-12 pad-5"><b>Closest Emergency Hospital</b></div>

								<div class="col-sm-3 pad-5">Name:</div>
								<div class="col-sm-9 pad-5"><input type="text" class = "form-control input-sm" v-model = "emergencyName"></div>

								<div class="col-sm-3 pad-5">Phone Number/s:</div>
								<div class="col-sm-9 pad-5"><input type="text" class = "form-control input-sm" v-model = "emergencyPhoneNumber" v-on:blur="format_number(2)" v-on:click="reset_number(2)" maxlength="14"></div>

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
		        			<div class="col-sm-12 pad-5 text-center">
		        				<?php if (file_exists("./uploads/induction_videos/".$project_id."/inductioncomp.mp4")): ?>
		        				<button type = "button" class = "btn btn-success btn-sm pull-left" v-on:click="showVideo"><span class="fa fa-file-pdf-o"></span> Show Video</button>
		        				<?php endif; ?>
		        				<b>Slide Preview</b>
		        				<button type = "button" class = "btn btn-primary btn-sm pull-right" v-on:click="generateAllSlide"><span class="fa fa-file-pdf-o"></span> Generate All Slides</button></div>
		        			<div class="col-sm-12 pad-10" style = "background-color: #b3b3cc; border-style: inset; height: 600px">
		        				<div class="col-sm-12" style = "height: 100%" id = "no_pdf"> <b>PDF not Available</b></div>
		        				<iframe  id = "slide_preview" src="" style = "position: relative; top: 0; left: 0; width: 100%; height: 100%; background: white"></iframe>
		        				<video width="100%" controls v-show="show_video">
								  <source src="../uploads/induction_videos/<?php echo $project_id ?>/inductioncomp.mp4" type="video/mp4">
								</video>
			        			
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
        			<div class="col-sm-12 pad-5"><label for="" style = "color: red">All fields with (*) is required Field</label></div>
          			<div class="col-sm-3 pad-5">Unit/Level</div>
          			<div class="col-sm-9 pad-5"><input type="text" class = "form-control input-sm" placeholder = "Unit/Level" v-model = "add_unit_level"></div>

          			<div class="col-sm-3 pad-5">Number</div>
          			<div class="col-sm-9 pad-5"><input type="text" class = "form-control input-sm" placeholder = "Number" v-model = "add_number"></div>

          			<div class="col-sm-3 pad-5">Street<span style = "color: red">*</span></div>
          			<div class="col-sm-9 pad-5"><input type="text" class = "form-control input-sm" placeholder = "Street" v-model = "add_street"></div>  

          			<div class="col-sm-3 pad-5">State<span style = "color: red">*</span></div>
          			<div class="col-sm-9 pad-5">
          				<select class = "form-control chosen" id = "state_name">
          					<option v-for = "stateList in stateList" :value="stateList.name">{{ stateList.name }}</option>
          				</select>
          			</div> 

          			<div class="col-sm-3 pad-5">Suburb<span style = "color: red">*</span></div>
          			<div class="col-sm-9 pad-5">
          				<select class = "form-control chosen" id = "add_suburb" >
          					<!-- <option v-for = "generalAddressList in generalAddressList" :value="generalAddressList.suburb">{{ generalAddressList.suburb }}</option> -->
          				</select>
          			</div> 

          			<div class="col-sm-3 pad-5">Postcode<span style = "color: red">*</span></div>
          			<div class="col-sm-9 pad-5">
          				<select class = "form-control chosen" id = "add_postcode">
          					<!-- <option  v-for = "generalAddressList in generalAddressList" :value="generalAddressList.postcode">{{ generalAddressList.postcode }}</option> -->
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

<?php $this->load->view('assets/logout-modal'); ?>
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
	$(document).ready(function() {
		$("#no_pdf").show();
		$("#slide_preview").hide();

		$('#state_name').on("change", function(e) {
			var state_name = $('#state_name').val();
			$.post(baseurl+"induction_health_safety/fetch_general_address_suburb",
			{
				state_name: state_name
			},
			function(result){
				$("#add_suburb").empty();
				$.each(JSON.parse(result), function (key, value)  {
				    $("#add_suburb").append($('<option></option>').val(value.suburb).html(value.suburb));
				});
			});

			
			
		});

		$('#add_suburb').on("change", function(e) {
			var suburb = $('#add_suburb').val();
			$.post(baseurl+"induction_health_safety/fetch_general_address",
			{
				suburb: suburb
			},
			function(result){
				$("#add_postcode").empty();
				$.each(JSON.parse(result), function (key, value)  {
				    $("#add_postcode").append($('<option></option>').val(value.postcode).html(value.postcode));
				});
			});
		});

	});

	// $('#s2id_state_name span.select2-chosen').change(function(){
	// 	alert("fdsfs");
	// });
	// window.change_state = function(){
	// 	alert("fdsfs");
	// }

	


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
      		show_video: false,
      		pdf_preview: false,
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

			stateList: [],
			generalAddressList: [],
			address_type:"",

			add_unit_level: "",
			add_number: "",
			add_street: "",
			state_name: "",
			add_suburb: "",
			add_postcode: "",

			med_add_unit_level: "",
			med_add_number: "",
			med_add_street: "",
			med_state_name: "",
			med_add_suburb: "",
			med_add_postcode: "",

			emer_add_unit_level: "",
			emer_add_number: "",
			emer_add_street: "",
			emer_state_name: "",
			emer_add_suburb: "",
			emer_add_postcode: "",

			accessFileUploaded: false,
			amenitiesPDFExtUploaded: false,

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
        	this.fethcState();
        	//this.fetchGeneralAddress();
        },

        methods: {	
        	showVideo:function(){
        		$("#no_pdf").hide();
				$("#slide_preview").hide();
				this.show_video = true;
        	},

        	fethcState: function(){
        		$.post(baseurl+"induction_health_safety/fetch_state",
				{
				},
				function(result){
				   	app.stateList = JSON.parse(result);
				});
        		
        	},
    //     	fetchGeneralAddress: function(){
    //     		$.post(baseurl+"induction_health_safety/fetch_general_address",
				// {
				// 	state_name: this.state_name
				// },
				// function(result){
				//    	app.generalAddressList = JSON.parse(result);
				// });

    //     	},


        	has_leadinghand: function(){
        		if(this.lh_mobile_number == null && this.lh_manual == null){
        			return false;
        		}else{
        			return true;
        		}
        	},


        	insert_address: function(){
        		this.state_name = $("#state_name").val();
        		this.add_suburb = $("#add_suburb").val();
        		this.add_postcode = $("#add_postcode").val();

        		if(this.add_street == null || this.state_name == null || this.add_suburb == null || this.add_postcode == null){
        			alert("All Fields with (*) are required.");
        		}else{
        			if(this.address_type == 1){
	        			this.med_add_unit_level = this.add_unit_level;
						this.med_add_number = this.add_number;
						this.med_add_street = this.add_street;
						this.med_state_name = this.state_name;
						this.med_add_suburb = this.add_suburb;
						this.med_add_postcode = this.add_postcode;

						if(this.med_add_unit_level == ""){
							if(this.med_add_number == ""){
								this.medicalAddress = this.med_add_street+", "+this.med_add_suburb+", "+this.med_state_name+", "+this.med_add_postcode;
							}else{
								this.medicalAddress = this.med_add_number+", "+this.med_add_street+", "+this.med_add_suburb+", "+this.med_state_name+", "+this.med_add_postcode;
							}
						}else{
							if(this.med_add_number == ""){
								this.medicalAddress = this.med_add_unit_level+", "+this.med_add_street+", "+this.med_add_suburb+", "+this.med_state_name+", "+this.med_add_postcode;
							}else{
								this.medicalAddress = this.med_add_unit_level+", "+this.med_add_number+", "+this.med_add_street+", "+this.med_add_suburb+", "+this.med_state_name+", "+this.med_add_postcode;
							}
						}

						

	        		}else{
	        			this.emer_add_unit_level = this.add_unit_level;
						this.emer_add_number = this.add_number;
						this.emer_add_street = this.add_street;
						this.emer_state_name = this.state_name;
						this.emer_add_suburb = this.add_suburb;
						this.emer_add_postcode = this.add_postcode;

						if(this.emer_add_unit_level == ""){
							if(this.emer_add_number == ""){
								this.emergencyAddress = this.emer_add_street+", "+this.emer_add_suburb+", "+this.emer_state_name+", "+this.emer_add_postcode;
							}else{
								this.emergencyAddress = this.emer_add_number+", "+this.emer_add_street+", "+this.emer_add_suburb+", "+this.emer_state_name+", "+this.emer_add_postcode;
							}
						}else{
							if(this.emer_add_number == ""){
								this.emergencyAddress = this.emer_add_unit_level+", "+this.emer_add_street+", "+this.emer_add_suburb+", "+this.emer_state_name+", "+this.emer_add_postcode;
							}else{
								this.emergencyAddress = this.emer_add_unit_level+", "+this.emer_add_number+", "+this.emer_add_street+", "+this.emer_add_suburb+", "+this.emer_state_name+", "+this.emer_add_postcode;
							}
						}
	        		}


        		}
        		
        	},

        	enter_address: function(a){
        		this.get_address(a);
        		$("#frm_address").modal("show");
        	},

        	saveSlide4: function(){
        		var project_id = this.project_id;
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
		            	var start = Date.now();
		            	$("#slide_preview").attr("src", "../uploads/project_induction_slides/"+project_id+"/slide4.pdf?time="+start);
			            $("#no_pdf").hide();
						$("#slide_preview").show();

						$.post(baseurl+"induction_health_safety/fetch_induction_projects_details",
				        {
				        	project_id: project_id
				        },
				        function(result){
				           	app.project_details = JSON.parse(result);
				        });
		          	});
        		}
        		
        	},

        	saveSlide1: function(){
        		var project_id = this.project_id;
        		if(this.imgPath == ""){
        			alert("Selected Brand Logo is not uploaded. Please upload logo.");
        		}else{
        			$.post(baseurl+"induction_health_safety/set_cleared_slides",
		          	{
		          		slide_no: 1,
		          		project_id: this.project_id
		          	},
		          	function(result){
		            	alert("Slide 1 Details Saved");
		            	var start = Date.now();
		            	$("#slide_preview").attr("src", "../uploads/project_induction_slides/"+project_id+"/slide1.pdf?time="+start);
		            	$("#no_pdf").hide();
						$("#slide_preview").show();
						$.post(baseurl+"induction_health_safety/fetch_induction_projects_details",
			          	{
			          		project_id: project_id
			          	},
			          	function(result){
			            	app.project_details = JSON.parse(result);
			          	});
		          	});

        			

        		}
        		
        	},

        	reset_number: function(a){
        		if(a == 1){
        			var phoneNumber = this.medicalPhoneNumber;
	        		phoneNumber = phoneNumber.replace(/ /g, '');
					phoneNumber = phoneNumber.replace('(', '');
					phoneNumber = phoneNumber.replace(')', '');
					this.medicalPhoneNumber = phoneNumber;
        		}else{
        			var phoneNumber = this.emergencyPhoneNumber;
	        		phoneNumber = phoneNumber.replace(/ /g, '');
					phoneNumber = phoneNumber.replace('(', '');
					phoneNumber = phoneNumber.replace(')', '');
					this.emergencyPhoneNumber = phoneNumber;
        		}
        		
        	},

        	format_number: function(a){
        		if(a == 1){
	        		var phoneNumber = this.medicalPhoneNumber;

	        		this.medicalPhoneNumber = '('+phoneNumber.substring(0,2)+") "+phoneNumber.substring(2,6)+" "+phoneNumber.substring(6,10);
				}else{
					var phoneNumber = this.emergencyPhoneNumber;

	        		this.emergencyPhoneNumber = '('+phoneNumber.substring(0,2)+") "+phoneNumber.substring(2,6)+" "+phoneNumber.substring(6,10);
				}             
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

	        getImageLogo: function(brand_id,has_brand_logo){
	        	var imgpath = "";
	        	if(has_brand_logo == 1){
	        		imgpath = "../uploads/brand_logo/"+brand_id+".jpg";
	        	}
	        	
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
				if(lh_name == null){
					lh_name = lh_manual
				}
				return lh_name;
			},

			get_address: function(address_type){
				this.address_type = address_type;
				
				for (var key in app.slide_details) {
	               	file_name = app.slide_details[key].amenities_map_filename;
	               	if(address_type == 1){
	               		if(this.med_add_street == "" || this.med_add_street == null){
	               			this.add_unit_level = app.slide_details[key].medical_add_unitlevel;
							this.add_number = app.slide_details[key].medical_add_number;
							this.add_street = app.slide_details[key].medical_add_street;
							this.state_name = app.slide_details[key].medical_add_state;
							this.add_suburb = app.slide_details[key].medical_add_suburb;
							this.add_postcode = app.slide_details[key].medical_add_postcode;
							

							this.med_add_unit_level = app.slide_details[key].medical_add_unitlevel;
							this.med_add_number = app.slide_details[key].medical_add_number;
							this.med_add_street = app.slide_details[key].medical_add_street;
							this.med_state_name = app.slide_details[key].medical_add_state;
							this.med_add_suburb = app.slide_details[key].medical_add_suburb;
							this.med_add_postcode = app.slide_details[key].medical_add_postcode;
						}else{
							this.add_unit_level = this.med_add_unit_level;
							this.add_number = this.med_add_number;
							this.add_street = this.med_add_street;
							this.state_name = this.med_state_name;
							this.add_suburb = this.med_add_suburb;
							this.add_postcode = this.med_add_postcode;
						}
						
	               	}else{
	               		if(this.emer_add_street == "" || this.emer_add_street == null){
							this.add_unit_level = app.slide_details[key].emergency_add_unitlevel;
							this.add_number = app.slide_details[key].emergency_add_number;
							this.add_street = app.slide_details[key].emergency_add_street;
							this.state_name = app.slide_details[key].emergency_add_state;
							this.add_suburb = app.slide_details[key].emergency_add_suburb;
							this.add_postcode = app.slide_details[key].emergency_add_postcode;
							
							this.emer_add_unit_level = app.slide_details[key].emergency_add_unitlevel;
							this.emer_add_number = app.slide_details[key].emergency_add_number;
							this.emer_add_street = app.slide_details[key].emergency_add_street;
							this.emer_state_name = app.slide_details[key].emergency_add_state;
							this.emer_add_suburb = app.slide_details[key].emergency_add_suburb;
							this.emer_add_postcode = app.slide_details[key].emergency_add_postcode;
						}else{
							this.add_unit_level = this.emer_add_unit_level;
							this.add_number = this.emer_add_number;
							this.add_street = this.emer_add_street;
							this.state_name = this.emer_state_name;
							this.add_suburb = this.emer_add_suburb;
							this.add_postcode = this.emer_add_postcode;
						}
	               	}

	               	$("#state_name").val(this.state_name);
					$('#s2id_state_name span.select2-chosen').text(this.state_name);
					$("#add_suburb").val(this.add_suburb);
					$('#s2id_add_suburb span.select2-chosen').text(this.add_suburb);
					$("#add_postcode").val(this.add_suburb);
					$('#s2id_add_postcode span.select2-chosen').text(this.add_postcode);
	               	
	            }
			},

	        selectSlide: function(){
	        	this.show_video = false;
	        	$('video').trigger('pause');
	        	if(this.project_id == ""){
	        		alert("Please Select Project");
	        		this.slide_selection = "";
	        	}else{
	        		var cleared_slides = "";
					for (var key in app.project_details) {
						cleared_slides = app.project_details[key].cleared_slides;
					}

					var num = 0;
					if(cleared_slides !== null){
						var cleared_slides_arr = cleared_slides.split(",");
						num = cleared_slides_arr.length;
					}

					

					if(num == 0){
						var pdf_created = 0;
					}else{
						var pdf_created = 0;
						var x = 0;
						while(x < num){
							if(this.slide_selection == cleared_slides_arr[x]){
								pdf_created = 1;
							}
							//window.open(baseurl+"induction_health_safety/generated_selected_pdf?project_id="+this.project_id+"&slide_no="+x);
							x++;
						}
						var start = Date.now();
						if(pdf_created == 0){
				        	$("#no_pdf").show();
							$("#slide_preview").hide();
						}else{
							$("#no_pdf").hide();
							$("#slide_preview").show();
							$("#slide_preview").attr("src", "../uploads/project_induction_slides/"+this.project_id+"/slide"+this.slide_selection+".pdf?time="+start);
							
						}
					}


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


	            if(file_name == ""){
	            	this.accessFile = '';
	            	this.accessFileUploaded = false;
	            }else{
	            	var start = Date.now();
	            	this.accessFileUploaded = true;
	            	this.accessFile = '../uploads/project_inductions_images/'+project_id+'/'+file_name+"?time="+start;
	            }
	            // var image_path = '../uploads/project_inductions_images/'+project_id+'/'+file_name;
	           

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
		        var extenstion = "";
		        for (var key in app.slide_details) {
	               	file_name = app.slide_details[key].amenities_map_filename;
	               	project_id = app.slide_details[key].project_id;

	               	if(file_name !== null){
	               		var file_arr = file_name.split('.');
	               		var extenstion = file_arr[1];
	               	}
	               	
	            }

	            if(extenstion == 'pdf'){
	              	this.amenitiesIsPDF = true;
	            	this.amenitiesPDFExtUploaded = true;
	            }else{
	              	this.amenitiesIsPDF = false;
	            }


	            if(file_name == ""){
	            	this.amenitiesPDFExtUploaded = false;
	            }else{
	            	var start = Date.now();
	            	this.amenitiesPDFExtUploaded = true;
	            	this.amenitiesFile = '../uploads/project_inductions_images/'+project_id+'/'+file_name+"?time="+start;
	            }
	            
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
	        		if(app.slide_details[key].ppe_list !== ""){
	        			this.ppe_selected = app.slide_details[key].ppe_list;
                		this.ppe_selected = JSON.parse(this.ppe_selected);
	        		}
                	
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
	        	var project_id = this.project_id;
	        	var project_outline = tinyMCE.activeEditor.getContent();
	        	$.post(baseurl+"induction_health_safety/update_induction_slide_project_outline",
	          	{
	          		project_id: this.project_id,
	          		project_outline: project_outline
	          	},
	          	function(result){
	          		$("#project_outline_preview").html(result);
	          		alert("Project Outline Saved");
	          		var start = Date.now();
	          		$("#slide_preview").attr("src", "../uploads/project_induction_slides/"+project_id+"/slide2.pdf?time="+start);
		            $("#no_pdf").hide();
					$("#slide_preview").show();
					$.post(baseurl+"induction_health_safety/fetch_induction_projects_details",
			        {
			        	project_id: project_id
			        },
			        function(result){
			           	app.project_details = JSON.parse(result);
			        });

	          		
	            	//app.slide_details = JSON.parse(result);
	          	});


	        },

	        saveSiteHours: function(){
	        	var project_id = this.project_id;
				$.post(baseurl+"induction_health_safety/update_induction_slide_site_hours",
	          	{
	          		project_id: this.project_id,
	          		generalSiteHours: this.generalSiteHours,
	          		noisySiteHours: this.noisySiteHours,
	          		otherSiteHours: this.otherSiteHours
	          	},
	          	function(result){
	          		alert("Site Hours Saved");
	          		var start = Date.now();
	          		$("#slide_preview").attr("src", "../uploads/project_induction_slides/"+project_id+"/slide3.pdf?time="+start);
		            $("#no_pdf").hide();
					$("#slide_preview").show();

					$.post(baseurl+"induction_health_safety/fetch_induction_projects_details",
			        {
			        	project_id: project_id
			        },
			        function(result){
			           	app.project_details = JSON.parse(result);
			        });
	          		// location.reload();


	            	//app.slide_details = JSON.parse(result);
	          	});
			},

			getSiteHours: function(){
				this.generalSiteHours = '7am to 5pm';
				for (var key in app.project_details) {
					this.generalSiteHours = app.project_details[key].general_site_hours;
					if(this.generalSiteHours == "" || this.generalSiteHours == null){
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
				var project_id = this.project_id;
				
				var medicalName = this.medicalName.replace("'", "''");
				var medicalAddress = this.medicalAddress.replace("'", "''");
				var emergencyName = this.emergencyName.replace("'", "''");
				var emergencyAddress = this.emergencyAddress.replace("'", "''");
				var med_add_street = this.med_add_street.replace("'", "''");
				var emer_add_street = this.emer_add_street.replace("'", "''");
				$.post(baseurl+"induction_health_safety/update_induction_slide_emergency",
	          	{
	          		project_id: this.project_id,
	          		medical_name: medicalName,
					medical_phone_number: this.medicalPhoneNumber,
					medical_address: medicalAddress,
					emergency_name: emergencyName,
					emergency_phone_number: this.emergencyPhoneNumber,
					emergency_address: emergencyAddress,

					med_add_unit_level: this.med_add_unit_level,
					med_add_number: this.med_add_number,
					med_add_street: med_add_street,
					med_state_name: this.med_state_name,
					med_add_suburb: this.med_add_suburb,
					med_add_postcode: this.med_add_postcode,

					emer_add_unit_level: this.emer_add_unit_level,
					emer_add_number: this.emer_add_number,
					emer_add_street: emer_add_street,
					emer_state_name: this.emer_state_name,
					emer_add_suburb: this.emer_add_suburb,
					emer_add_postcode: this.emer_add_postcode,
	          	},
	          	function(result){
	          		alert("Project Medical Emergency Details Saved");
	            	//app.slide_details = JSON.parse(result);
	            	var start = Date.now();
	            	$("#slide_preview").attr("src", "../uploads/project_induction_slides/"+project_id+"/slide5.pdf?time="+start);
		            $("#no_pdf").hide();
					$("#slide_preview").show();

					$.post(baseurl+"induction_health_safety/fetch_induction_projects_details",
			        {
			        	project_id: project_id
			        },
			        function(result){
			           	app.project_details = JSON.parse(result);
			        });
	          	});
				
			},

			
			savePPE: function(){
				var project_id = this.project_id;

				$.post(baseurl+"induction_health_safety/update_induction_slide_ppe",
	          	{
	          		project_id: this.project_id,
	          		ppe_selected: this.ppe_selected
	          	},
	          	function(result){
	          		alert("PPE Saved");
	          		var start = Date.now();
	          		$("#slide_preview").attr("src", "../uploads/project_induction_slides/"+project_id+"/slide6.pdf?time="+start);
		            $("#no_pdf").hide();
					$("#slide_preview").show();

					$.post(baseurl+"induction_health_safety/fetch_induction_projects_details",
			        {
			        	project_id: project_id
			        },
			        function(result){
			           	app.project_details = JSON.parse(result);
			        });
	          		//app.slide_details = JSON.parse(result);
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

					$.post(baseurl+"induction_health_safety/set_inductions_as_saved",
		          	{
		          		project_id: this.project_id
		          	},
		          	function(result){
		          		alert(result);
		          		//app.slide_details = JSON.parse(result);
		          		location.reload();
		          	});
				}
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
