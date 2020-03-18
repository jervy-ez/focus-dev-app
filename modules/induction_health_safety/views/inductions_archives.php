<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>
<?php $this->load->module('projects'); ?>
<?php $this->load->module('bulletin_board'); ?>
<?php $this->load->module('schedule'); ?>
<?php $this->load->model('admin_m'); ?>
<?php $this->load->module('induction_health_safety'); ?>



<?php
    $fetch_archive_types = $this->admin_m->get_archive_types();
    $archive_types= $fetch_archive_types->result();
?>
<?php  echo $tab ?>

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

        <?php if($this->session->userdata('is_admin') ==  1 || $this->session->userdata('user_id') == 6 || $this->session->userdata('user_id') == 32  ): ?>
          <li>
            <a href="<?php echo base_url(); ?>induction_health_safety" class = "active"><i class="fa fa-home"></i> Induction Site Staff</a>
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

        <?php endif; ?>

            <li>
              <a href="<?php echo base_url(); ?>induction_health_safety/archive_documents"><i class="fa fa-file-text-o"></i> Archive Documents</a>
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
                <label style = "font-size: 21px"><?php echo $screen; ?></label><span> (<a href="#" data-placement="right" class="popover-test" title="" data-content="Hello there mate! Welcome to the clients screen." data-original-title="Welcome">?</a>)</span>
                <p>This is where the Induction, Health and Safety are listed.</p>              
            </div>


          </div>

          <div class="col-lg-8 col-md-12 hidden-md hidden-sm hidden-xs pad-5">
           <!--  <button type = "button" class = "btn btn-warning btn-sm pull-right" v-on:click = "showEditInductionSlide">View Induction Slide Template</button>
            <button type = "button" class = "btn btn-success btn-sm pull-right" v-on:click = "showInductionVideos">View Induction Videos</button>
            <button type = "button" class = "btn btn-primary btn-sm pull-right" v-on:click = "showInductionProjects">View Prjects for Induction</button> -->
          </div>
        </div>

        









        <div class="row">
          <div class="col-sm-12">
            <div class="left-section-box">
              

              <div class="row clearfix" style="margin-bottom: -6px;">
                <div class="col-lg-6 col-md-6 col-sm-4">
                  <div class="pad-left-15 clearfix">
                    <label class="h4">Archive Documents</label>
                  </div>
                </div>
              </div>


              <?php if(sizeof($archive_types_for_upload) > 0): ?>

              <div class="box-area pad-10 border-top">
                <div id="myTabContent" class="tab-content">
                  <div id="users" aria-labelledby="users" class="tab-pane fade active in">
                    <div class="box  m-top-0">
                      <div class="box-head   pad-5"><label><i class="fa fa-user fa-lg"></i> Document Upload</label></div>


                        <div class="box-area clearfix pad-10">
                          <div class=" m-top-0">

                            <?php foreach($archive_types_for_upload as $key => $archive_data): ?>
                             <!--  <option value="<?php echo $archive_data->registry_types_id; ?>_<?php echo $archive_data->registry_name; ?>" > <?php echo $archive_data->registry_name; ?> </option> -->
 
                             <div id="" class="col-md-6">
                              <form method="post" action="<?php echo base_url(); ?>induction_health_safety/upload_docs_ind" id="upload_docs_ind_<?php echo $archive_data->registry_types_id; ?>" enctype="multipart/form-data">
                                <div class="input-group m-bottom-15">
                                  <span class="input-group-addon"  style=" border-color: #3E8F3E; color:#3E8F3E; width:35%; text-align: left;"><i class="fa fa-file"></i> <?php echo $archive_data->registry_name; ?></span>
                                  
                                  <input type="file" multiple="multiple" name="archive_files[]" requaired autocomplete="off" id="archive_name_edt" class="form-control btn-success pad-5" style="color:#FFF;">
                                  
                                  <input type="hidden" name="archive_registry_types" id="archive_registry_types" value="<?php echo $archive_data->registry_types_id; ?>">
                                  <input type="hidden" name="archive_registry_name" id="archive_registry_name" value="<?php echo $archive_data->registry_name; ?>">
                                  
                                  <span class="input-group-addon btn btn-default" style=" border-color: #3E8F3E; color:#3E8F3E;" onClick=" $('form#upload_docs_ind_<?php echo $archive_data->registry_types_id; ?>').submit(); " ><i class="fa fa-upload"></i> Upload</span>
                                </div>
                              </form>
                            </div>

                          <?php endforeach; ?>

                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
              </div>
               
              <?php endif; ?>


              <div class="box-area pad-10 border-top">
                <div id="myTabContent" class="tab-content">
                  <div id="users" aria-labelledby="users" class="tab-pane fade active in">
                    <div class="box  m-top-0">
                      <div class="box-head   pad-5"><label><i class="fa fa-user fa-lg"></i> Uploaded Files</label></div>


                        <div class="box-area clearfix pad-5">
                          <div class=" m-top-0">




                            <?php foreach($archive_types as $key => $archive_data): ?>
                             <!--  <option value="<?php echo $archive_data->registry_types_id; ?>_<?php echo $archive_data->registry_name; ?>" > <?php echo $archive_data->registry_name; ?> </option> -->


                             <div id="" class="col-md-3 m-bottom-5" >
                              <div class="box orange-border" style=" height: 305px;">
                                <div class="box-head orange-bg pad-5 m-bottom-5">
                                  &nbsp;<label class=""><i class="fa fa-file-text-o fa-lg"></i>&nbsp; <?php echo $archive_data->registry_name; ?></label>
                                  <button class="btn btn-xs pull-right btn-primary hide" style="margin:1px;">View All</button>
                                </div>
                                <div class="box-area pad-5 clearfix">
                                  <div class=" m-bottom-10 clearfix pad-5"  style="height: 215px;    overflow: auto;">
                                    <?php echo $this->induction_health_safety->view_uploaded_files_arch($archive_data->registry_types_id); ?>

                                    <?php if($this->session->userdata('is_admin') == 1 ): ?>
                                      <div id="" class="">
                                        <hr style=" margin: 5px 0;">
                                        <p><strong>Old Files</strong></p>
                                        <?php echo $this->induction_health_safety->view_uploaded_files_arch($archive_data->registry_types_id,1); ?>
                                      </div>
                                    <?php endif; ?>

                                  </div>
                                </div>
                              </div>
                            </div>

                          <?php endforeach; ?>


                          <?php if($this->session->userdata('is_admin') !=  1  ): ?>
                            <style type="text/css">

                              .for_admin{ visibility: hidden; display: none; }

                            </style>
                          <?php endif; ?>


                            <div id="" class="clearfix"></div>


                            <p><br /> &nbsp; <br /></p>
                            <p><br /> &nbsp; <br /></p> 

                            <p><br /> &nbsp; <br /></p>
                            <p><br /> &nbsp; <br /></p> 

                            <p><br /> &nbsp; <br /></p>
                            <p><br /> &nbsp; <br /></p> 


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
<?php $this->bulletin_board->list_latest_post(); ?>