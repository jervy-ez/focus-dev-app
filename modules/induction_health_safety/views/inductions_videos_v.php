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
          	<li class="nav-item">
            	<a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Home</a>
          	</li>
          	<li>
            	<a href="<?php echo base_url(); ?>induction_health_safety"><i class="fa fa-home"></i> Induction Site Staff</a>
          	</li>
          	<li>
            	<a href="<?php echo base_url(); ?>induction_health_safety/inductions_projects"><i class="fa fa-home"></i> Induction Projects</a>
          	</li>
           	<li>
            	<a href="<?php echo base_url(); ?>induction_health_safety/inductions_videos"><i class="fa fa-home"></i> Uploading Video for Induction</a>
          	</li>
          	<li>
            	<a href="<?php echo base_url(); ?>induction_health_safety/induction_slide_editor_view"><i class="fa fa-home"></i> Induction Slide Templates</a>
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
		        	<div class="col-sm-5 pad-5">
		        		<div class="col-sm-12"><input type="text" class = "form-control input-sm" placeholder = "search..."></div>
		        		<div class="col-sm-12 pad-5" style = "height: 400px; overflow: auto">
			              	<table class = "table  table-condensed table-bordered m-bottom-0 table-striped fancyTable">
			                	<tr>
			                  		<th>Project ID</th>
			                  		<th>PDF Generated</th>
			                  		<th>Videos</th>
			                	</tr>
			               	 	<tr v-for = "video_list in video_list">
			                  		<td :title = "'Project Name: ' + video_list.project_name + '\nClient Name: '+ video_list.company_name">
			                  			<a :href="fetchProjectURL(video_list.project_id)">{{ video_list.project_id }}</a>
			                  		</td>
			                  		<td>
			                  			<span class="fa fa-file-pdf-o" style = "font-size: 30px" title = "View Opening Slide" v-on:click="viewPDF(video_list.project_id,1)"></span>
										<span class="fa fa-file-pdf-o" style = "font-size: 30px" title = "View Project Outline" v-on:click="viewPDF(video_list.project_id,2)"></span>
										<span class="fa fa-file-pdf-o" style = "font-size: 30px" title = "View Access" v-on:click="viewPDF(video_list.project_id,3)"></span>
										<span class="fa fa-file-pdf-o" style = "font-size: 30px" title = "View Amenities and Emergency Exits" v-on:click="viewPDF(video_list.project_id,4)"></span>
										<span class="fa fa-file-pdf-o" style = "font-size: 30px" title = "View EPR" v-on:click="viewPDF(video_list.project_id,5)"></span>
										<span class="fa fa-file-pdf-o" style = "font-size: 30px" title = "View PPE" v-on:click="viewPDF(video_list.project_id,6)"></span>
										<span class="fa fa-list pull-right" style = "font-size: 30px" title = "View who watch the video" v-on:click="viewViewer(video_list.project_id)"></span>
			                  		</td>
			                  		<td v-if= "video_list.video_uploaded == 0 || video_list.video_uploaded == null">
			                  			<form :action="get_action_path(video_list.project_id)" method="post" enctype="multipart/form-data">
										<span class="btn btn-primary btn-sm btn-block btn-file">
									    	<i class = "fa fa-plus-circle"></i> Upload Video<input type="file" name="userfile[]" multiple="multiple" accept="image/*" onchange="form.submit()">
										</span>
										</form>
			                  			
			                  		</td>
			                  		<td v-if= "video_list.video_uploaded == 1">
			                  			<span class="fa fa-video-camera" style = "font-size: 30px" title = "Play Video" v-on:click="play_video(video_list.project_id)">
			                  			<span class="fa fa-envelope-open pull-right" style = "font-size: 30px" title = "Send Upload Notification" v-on:click="sendUploadNotifcation(video_list.project_id)">
			                  		
			                  		</td>
			                	</tr>
			              </table>
			            </div>
		        	</div>
		        	<div class="col-sm-7 pad-5">
		        		<div class="col-sm-12" style = "background-color: #b3b3cc; border-style: inset; height: 600px">
		        			<!-- <video width="100%" controls v-show="show_video">
							  <source :src="videoPath" type="video/mp4">
							</video> -->
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
		                          	<td v-if="video_viewer_list.site_staff_type == 1">{{ video_viewer_list.user_first_name  +" "+ video_viewer_list.user_last_name}}</td>
		                          	<td v-if="video_viewer_list.site_staff_type == 2">{{ video_viewer_list.site_staff_fname +" "+ video_viewer_list.site_staff_sname}}</td>
		                          	<td v-if="video_viewer_list.site_staff_type == 3">{{ video_viewer_list.o_site_staff_fname +" "+ video_viewer_list.o_site_staff_sname}}</td>
		                          	<td v-if="video_viewer_list.site_staff_type == 1">Focus Shopfit</td>
		                          	<td v-if="video_viewer_list.site_staff_type == 2">{{ video_viewer_list.company_name }}</td>
		                          	<td v-if="video_viewer_list.site_staff_type == 3">{{ video_viewer_list.other_company_name }}</td>
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


	var baseurl = '<?php echo base_url(); ?>';
	var app = new Vue({
      	el: '#ihs_app',
      	data: {
      		video_list: [],
      		pdfFile: "",
      		show_pdf: false,
      		show_video: false,
      		video_viewer_list: [],
      		search_site_staff: '',
      		videoPath: "",
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
        	this.fetchVideoList();
        },

        methods: {
        	fetchProjectURL: function(project_id){
        		return baseurl+"projects/view/"+project_id;
        	},
        	
        	get_action_path: function(project_id){
        		return baseurl+"induction_health_safety/upload_videos?project_id="+project_id;
        	},

        	viewPDF: function(project_id,a){
        		this.show_pdf = true;
				this.show_video = false;
				var start = Date.now();
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



        	fetchVideoList: function(){
	          	$.post(baseurl+"induction_health_safety/fetch_induction_videos",
	          	{
	          	},
	          	function(result){
	            	app.video_list = JSON.parse(result);
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

        },
    });
</script>
