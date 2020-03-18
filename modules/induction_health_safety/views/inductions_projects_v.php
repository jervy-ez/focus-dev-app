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
          	<?php if($this->session->userdata('is_admin') == 1 || $this->session->userdata('user_role_id') == 4 ): ?>
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
	        <?php if($this->session->userdata('user_role_id') == 2 || $this->session->userdata('user_role_id') == 3): ?>
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
			                <p>This is where the Projects that have Induction, Health and Safety are listed.</p>
			                <p><a href="#" class="tooltip-test" title="Tooltip">This link</a> and <a href="#" class="tooltip-test" title="Tooltip">that link</a> should have tooltips on hover.</p>               
			            </div>
          			</div>
       			</div>
		        <div class="box-area">
		        	<div class="col-sm-5 pad-5">
		        		<div class="col-sm-12 pad-5">
		        			<select v-model="selectPA" class = "form-control input-sm">
		        				<option value = "0">All</option>
								<option v-for="pa_list in pa_list" :value="pa_list.user_id">
								    {{ pa_list.user_first_name +" "+ pa_list.user_last_name }}
								</option>
							</select>
		        		</div>
		        		<div class="col-sm-12"><input type="text" class = "form-control input-sm" placeholder = "search..." v-model ="searchIndProjects"></div>
		        		<div class="col-sm-12 pad-5" style = "height: 570px; overflow: auto">
			              	<table class = "table  table-condensed table-bordered m-bottom-0 table-striped fancyTable">
			                	<tr>
			                  		<th>Project ID</th>
			                  		<th>PDF Generated</th>
			                  		<th>Videos</th>
			                	</tr>
			               	 	<tr v-for = "project_list in filterIndProjects" v-if="is_exempted(project_list.project_number,project_list.postcode,project_list.job_date,project_list.date_site_commencement,project_list.project_admin_id,project_list.unaccepted_date)">
			                  		<td :title = "'Project Name: ' + project_list.project_name + '\nClient Name: '+ project_list.company_name">
			                  			<a :href="fetchProjectURL(project_list.project_number)">{{ project_list.project_number}}</a>
			                  			<a :href="fetchSlideTemplatURL(project_list.project_number)" class = "pull-right" style = "font-size: 20px"><i class="fa fa-arrow-circle-right"></i></a>
			                  		</td>
			                  		<td>
			                  			<span class="fa fa-file-pdf-o" style = "font-size: 30px; color:red" v-show="show_slide(project_list.cleared_slides,1,1)" title = "View Opening Slide" v-on:click="viewPDF(project_list.project_id,1)"></span>
			                  			<span class="fa fa-file-pdf-o" style = "font-size: 30px;" v-show="show_slide(project_list.cleared_slides,1,0)" title = "View Opening Slide"></span>

										<span class="fa fa-file-pdf-o" style = "font-size: 30px; color:red" v-show="show_slide(project_list.cleared_slides,2,1)" title = "View Project Outline" v-on:click="viewPDF(project_list.project_id,2)"></span>
										<span class="fa fa-file-pdf-o" style = "font-size: 30px" v-show="show_slide(project_list.cleared_slides,2,0)" title = "View Project Outline"></span>

										<span class="fa fa-file-pdf-o" style = "font-size: 30px; color:red" v-show="show_slide(project_list.cleared_slides,3,1)" title = "View Access" v-on:click="viewPDF(project_list.project_id,3)"></span>
										<span class="fa fa-file-pdf-o" style = "font-size: 30px" v-show="show_slide(project_list.cleared_slides,3,0)" title = "View Access" ></span>
										
										<span class="fa fa-file-pdf-o" style = "font-size: 30px; color: red" v-show="show_slide(project_list.cleared_slides,4,1)" title = "View Amenities and Emergency Exits" v-on:click="viewPDF(project_list.project_id,4)"></span>
										<span class="fa fa-file-pdf-o" style = "font-size: 30px" v-show="show_slide(project_list.cleared_slides,4,0)" title = "View Amenities and Emergency Exits"></span>

										<span class="fa fa-file-pdf-o" style = "font-size: 30px; color: red" v-show="show_slide(project_list.cleared_slides,5,1)" title = "View EPR" v-on:click="viewPDF(project_list.project_id,5)"></span>
										<span class="fa fa-file-pdf-o" style = "font-size: 30px" v-show="show_slide(project_list.cleared_slides,5,0)" title = "View EPR" ></span>

										<span class="fa fa-file-pdf-o" style = "font-size: 30px; color: red" v-show="show_slide(project_list.cleared_slides,6,1)" title = "View PPE" v-on:click="viewPDF(project_list.project_id,6)"></span>
										<span class="fa fa-file-pdf-o" style = "font-size: 30px" v-show="show_slide(project_list.cleared_slides,6,0)" title = "View PPE"></span>

										<span class="fa fa-list pull-right" style = "font-size: 30px" title = "View who watch the video" v-on:click="viewViewer(project_list.project_id)"></span>
			                  		</td>
			                  		<td v-if= "project_list.video_uploaded == 0 || project_list.video_uploaded == null">
			                  			Video not yet uploaded
			                  			
			                  		</td>
			                  		<td v-if= "project_list.video_uploaded == 1">
			                  			<span class="fa fa-video-camera" style = "font-size: 30px" title = "Play Video" v-on:click="play_video(project_list.project_id)">
			                  		</td>
			                	</tr>
			              </table>
			            </div>
		        	</div>
		        	<div class="col-sm-7 pad-5">
		        		<div class="col-sm-12" style = "background-color: #b3b3cc; border-style: inset; height: 600px">
		        			<video width="100%" controls :src="videoPath" v-show="show_video"></video>
		        			<iframe  :src="pdfFile" style = "position: relative; top: 0; left: 0; width: 100%; height: 100%; background: white" v-show = "show_pdf"></iframe>
		        		</div>
		        	</div>
		        </div>
		    </div>
	    </div>
	</div>

<!-- Modal -->
	<div class="modal fade" id="view_video_watcher" role="dialog">
    	<div class="modal-dialog">
      <!-- Modal content-->
	      	<div class="modal-content">
		        <div class="modal-header">
		          	<button type="button" class="close" data-dismiss="modal">&times;</button>
		          	<h4 class="modal-title">Site Staff who watched the video</h4>
		        </div>
		        <div class="modal-body row">
		        	<div class="col-sm-5"><input type="text" class = "form-control input-sm" placeholder = "Search..." v-model = "search_site_staff"></div>
		        	
		        	<div class="col-sm-12 pad-5" style = "height: 300px; overflow:auto">
	                    <table id="fixTable" class = "table table-condensed table-bordered m-bottom-0 table-striped fancyTable" style = "font-size: 12px">
	                      	<thead>
		                        <tr>
		                          	<th>Site Staff Name</th>
		                          	<th>Company Name</th>
		                          	<th>Projec Name</th>
		                          	<th>Date Watched</th>
		                        </tr>
	                      	</thead>
	                      	<tbody>
		                        <tr v-for="video_viewer_list in filterSiteStaff">
		                          	<td v-if = "video_viewer_list.site_staff_type == 1">{{ video_viewer_list.user_first_name +" "+ video_viewer_list.user_last_name}}</td>
		                          	<td v-if = "video_viewer_list.site_staff_type == 2">{{ video_viewer_list.site_staff_fname +" "+ video_viewer_list.site_staff_sname}}</td>
		                          	<td v-if = "video_viewer_list.site_staff_type == 3">{{ video_viewer_list.o_site_staff_fname +" "+ video_viewer_list.o_site_staff_sname}}</td>
		                          	<td v-if = "video_viewer_list.site_staff_type == 1">Focus Shopfit </td>
		                          	<td v-if = "video_viewer_list.site_staff_type == 2">{{ video_viewer_list.company_name }}</td>
		                          	<td v-if = "video_viewer_list.site_staff_type == 3">{{ video_viewer_list.other_company_name }}</td>
		                          	<td>{{ video_viewer_list.project_name }}</td>
		                          	<td>{{ video_viewer_list.date_watched }}</td>
		                        </tr>
							</tbody>
						</table>
					</div>
		        </div>
		        <div class="modal-footer">
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
                	'insertdatetime media table contextmenu paste code help wordcount'
              	],
        //toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
        toolbar: 'bold italic'
       
    });

	var dateObj = new Date();
	var month = ('0' + (dateObj.getMonth() + 1)).slice(-2);
	var date = ('0' + dateObj.getDate()).slice(-2);
	var year = dateObj.getFullYear();
	var datetoday = year + '-' + month + '-' + date;

	var current_user_id = "<?php echo $this->session->userdata('user_id') ?>";
	var role_id = "<?php echo $this->session->userdata('user_role_id') ?>"; 
	var is_admin = "<?php echo $this->session->userdata('is_admin') ?>";
	if(role_id == 3){
		current_user_id = 0;
	}else{
		if(is_admin == 1){
			current_user_id = 0;
		}
	}

	var baseurl = '<?php echo base_url(); ?>';
	var app = new Vue({
      	el: '#ihs_app',
      	data: {
      		a: "sdfsf",
      		project_list: [],
      		pdfFile: "",
      		show_pdf: false,
      		show_video: false,
      		video_viewer_list: [],
      		search_site_staff: '',
      		videoPath: "",
      		exempted_projects: [],
      		exempted_postcode: [],
      		searchIndProjects: "",
      		pa_list: [],
      		selectPA: current_user_id,
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
          	},
      	},

      	mounted: function(){
        	this.fetchProjectList();
        	this.fetch_exempted_projects();
        	this.fetch_induction_postcode_filters();
        	this.fetchPAList();
        },

        methods: {


        	fetchPAList: function(){
        		$.post(baseurl+"induction_health_safety/fetch_all_pa",
	          	{
	          	},
	          	function(result){
	            	app.pa_list = JSON.parse(result);

	          	});
        	},

        	fetch_exempted_projects: function(){
        		$.post(baseurl+"induction_health_safety/project_is_exempted_induction",
	          	{
	          	},
	          	function(result){
	            	app.exempted_projects = JSON.parse(result);
	          	});
        	},

        	fetch_induction_postcode_filters: function(){
        		$.post(baseurl+"induction_health_safety/fetch_induction_postcode_filters",
	          	{
	          	},
	          	function(result){
	            	app.exempted_postcode = JSON.parse(result);
	          	});
        		
        	},

        	is_exempted: function(project_id,post_code,job_date,commencement_date,project_admin_id,unaccepted_date){
        		var is_exempted = 0;

        		for (var key in app.exempted_projects) {
			        if(app.exempted_projects[key].project_id == project_id){
				        is_exempted = 1;
			        }
			    }

			    if(is_exempted == 0){
			    	for (var key in app.exempted_postcode) {
		              	if(+(app.exempted_postcode[key].start_postcode) <= +(post_code) && +(app.exempted_postcode[key].end_postcode) >= +(post_code)){
			                is_exempted = 1;
		              	}
		            }
			    }

			    if(is_exempted == 0){
			    	if(job_date == ""){
		              	var date_arr = commencement_date.split("/");
		              	var day = date_arr[0];
		              	var month = date_arr[1];
		              	var year = date_arr[2];
		              	var project_sdate = Date.parse(year+"-"+month+"-"+day);
						var date_today = Date.parse(datetoday);
						if(unaccepted_date == ""){
							if (date_today > project_sdate) {
							 	is_exempted = 1;
							}
						}else{
							is_exempted = 1;
						}
						
				    }
			    }
        		
			    if(this.selectPA > 0){
	            	if(this.selectPA !== project_admin_id){
	            		is_exempted = 1;
	            	}
	            }
	            

	            if(is_exempted == 1){
	            	return false;
	            }else{
	            	return true;
	            }
        		
        	},

        	fetchProjectURL: function(project_id){
        		return baseurl+"projects/view/"+project_id;
        	},

        	fetchSlideTemplatURL: function(project_number){
        		return baseurl+"induction_health_safety/induction_slide_editor_view?project_id="+project_number;
        	},

        	show_slide: function(cleared_slides,slide_no,is_active){
        		if(cleared_slides == null){
        			if(is_active == 0){
        				return true;
        			}else{
        				return false;
        			}
        			
        		}else{
        			var cs_arr = cleared_slides.split(',');
	        		var num_arr = cs_arr.length;
	        		var exist = 0;
	        		var x = 0;
	        		while(x < num_arr){
	        			if(slide_no == cs_arr[x]){
	        				exist = 1;
	        			}
	        			x++;
	        		}

	        		if(exist == 1){
	        			if(is_active == 1){
	        				return true;
	        			}else{
	        				return false;
	        			}
	        		}else{
	        			if(is_active == 1){
	        				return false;
	        			}else{
	        				return true;
	        			}
	        		}
        		}
        		
        		
        	},

        	get_action_path: function(project_id){
        		return baseurl+"induction_health_safety/upload_videos?project_id="+project_id;
        	},

        	viewPDF: function(project_id,a){
        		var start = Date.now();
        		this.show_pdf = true;
				this.show_video = false;
        		switch(a){
        			case 1:
        				this.pdfFile = "../uploads/project_induction_slides/"+project_id+"/slide1.pdf?time="+start;
        				break;
        			case 2:
        				this.pdfFile = "../uploads/project_induction_slides/"+project_id+"/slide2.pdf?time="+start;
        				break;
        			case 3:
        				this.pdfFile = "../uploads/project_induction_slides/"+project_id+"/slide3.pdf?time="+start;
        				break;
        			case 4:
        				this.pdfFile = "../uploads/project_induction_slides/"+project_id+"/slide4.pdf?time="+start;
        				break;
        			case 5:
        				this.pdfFile = "../uploads/project_induction_slides/"+project_id+"/slide5.pdf?time="+start;
        				break;
        			case 6:
        				this.pdfFile = "../uploads/project_induction_slides/"+project_id+"/slide6.pdf?time="+start;
        				break;
        		}
        	},

        	play_video: function(project_id){
        		this.show_pdf = false;
				this.show_video = true;
				this.videoPath = "../uploads/induction_videos/"+project_id+"/inductioncomp.mp4";
        	},

        	fetchProjectList: function(){
	          	$.post(baseurl+"induction_health_safety/fetch_induction_projects",
	          	{
	          	},
	          	function(result){
	            	app.project_list = JSON.parse(result);
	          	});
	          
	        },

	        viewViewer: function(project_id){
	        	$.post(baseurl+"induction_health_safety/fetch_induction_video_person_watch",
	          	{
	          		project_id: project_id
	          	},
	          	function(result){
	            	app.video_viewer_list = JSON.parse(result);
	            	$("#view_video_watcher").modal("show");
	          	});
	        },

	        sendUploadNotifcation: function(project_id){
	        	$.post(baseurl+"induction_health_safety/send_video_uploaded_notification",
	          	{
	          		project_id: project_id
	          	},
	          	function(result){
	            	alert(result);
	          	});
	        },

        },

        computed: {
        	filterSiteStaff: function(){
	          	this.video_viewer_list.sort((a,b) => a.site_staff_fname > b.site_staff_fname ? 1 : -1);

	          	return this.video_viewer_list.filter((siteStaff) => {
	            	return siteStaff.site_staff_fname.toLowerCase().match(this.search_site_staff.toLowerCase()) || siteStaff.site_staff_sname.toLowerCase().match(this.search_site_staff.toLowerCase());
	          	});
	        },

	        filterIndProjects: function(){
	          	//this.project_list.sort((a,b) => a.site_staff_fname > b.site_staff_fname ? 1 : -1);

	          	return this.project_list.filter((projects) => {
	            	return projects.project_number.toLowerCase().match(this.searchIndProjects.toLowerCase());
	          	});
	        },

        },
    });
</script>
