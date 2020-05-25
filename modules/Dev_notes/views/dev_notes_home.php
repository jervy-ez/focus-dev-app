<?php $this->load->module('dev_notes'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>temp_css/datatables.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>temp_css/bootstrap-datetimepicker-standalone.css">

<div class="app-main__inner">
  
  <!-- main content here -->

    <div class="row">
      <div class="col-md-8 col-lg-9 ">

        <div class="mb-3 card">
          <div class="card-header-tab card-header">
            <div class="card-header-title">
              <i class="header-icon fas fa-list-alt icon-gradient  bg-orange-gradient "> </i>
              Development Notes
            </div>
            <ul class="nav">
              <li class="nav-item"><a data-toggle="tab" href="#dev_notes_area" class="nav-link show active orange-text">Development</a></li>
              <li class="nav-item"><a data-toggle="tab" href="#bugs_area" class="nav-link show orange-text">Bugs</a></li>
              <li class="nav-item"><a data-toggle="tab" href="#add_new" class="nav-link show orange-text">Add New</a></li>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content">

              <div class="tab-pane show active" id="dev_notes_area" role="tabpanel">
                <table id="dataTable_development" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" width="100%">
                  <thead> <tr> <th style="display:none;">ID</th> <th>Title</th> <th>Category</th> <th>Status</th> <th>Posted</th> <th>Assignment</th> <th>Section</th>  <th>unix_date</th> </tr> </thead> 
                  <tbody>
                    <?php  $this->dev_notes->list_post(); ?>
                  </tbody>
                </table>
              </div>

              <div class="tab-pane show" id="bugs_area" role="tabpanel">
                <table id="dataTable_noCustom_dnotes_bugs" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" width="100%">
                  <thead> <tr> <th style="display:none;">ID</th> <th>Title</th> <th>Category</th> <th>Status</th> <th>Posted</th> <th>Assignment</th> <th>Section</th> <th>unix_date</th></tr> </thead> 
                  <tbody>
                    <?php  $this->dev_notes->list_post(1); ?>
                  </tbody>
                </table>
              </div>

              <div class="tab-pane show" id="add_new" role="tabpanel">
                <form class="needs-validation" id="newUserDetails" method="post" action="<?php echo base_url(); ?>Dev_notes/post" novalidate="novalidate">
                <div class="form-row mb-3">

                  <div class="col-sm-6">
                    <div class="input-group ">
                      <span class="input-group-addon"><i class="fa fa-pencil-square-o  "></i></span>
                      <input required type="text" class="form-control" placeholder="Title" name="notes_title" id="notes_title" maxlength="50" tabindex="1" value="" autocomplete="off"> 
                    </div>
                  </div>

                  <div class="col-sm-3">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-user-plus" aria-hidden="true"></i></span>
                      </div>
                      <select class="form-control" id="dn_assnmt" name="dn_assnmt" tabindex="2">
                        <?php foreach ($programmers as $prg ) {                       
                          echo '<option value="'.$prg->user_id.'">'.$prg->first_name.'</option>';
                        }
                        ?>                             
                      </select>
                    </div>
                  </div>


                  <div class="col-sm-3">
                    <div class="input-group date" id=" ">
                    <div id="" class="input-group-prepend"><span class="input-group-text"><i class="fas fa-calendar  "></i> &nbsp; Start Date </span></div>
                    <input tabindex="3" type="text" placeholder="DD/MM/YYYY"  class="form-control" id="start_date_picker" name="date_stamp" value="" autocomplete="off">
                   </div>
                 </div>
               </div>



                <div class="form-row  mb-3">
                  <div id="" class="col-sm-12">
                    <div class="m-t-10">
                      <div class="pad-5 clearfix">
                        <div class="clearfix ">
                          <div class="">
                            <textarea required class="form-control" id="project_notes" rows="20" tabindex="4" name="comments"  placeholder="Details"  style="resize: vertical; z-index: auto; position: relative; line-height: 20px; font-size: 14px; transition: none; background: transparent !important;"></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>





 
                <div class="form-row mb-3">


                 <div class="col-sm-3">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-bug" aria-hidden="true"></i> &nbsp; Is a Bug Report?</span>
                    </div>
                    <select required class="form-control" id="dn_bugs" name="dn_bugs"  >
                      <option  value="0" selected>No</option>
                      <option value="1"> Yes</option>                       
                    </select>
                  </div>
                </div>

                  <div class="col-sm-3">
                    <select required class="form-control" id="dn_section" name="dn_section" tabindex="5">
                      <option  value="" disabled selected>Section</option>
                      <?php
                        foreach ($sections as $sectn ) {
                          echo '<option value="'.$sectn->dn_section_id.'">'.$sectn->dn_section_label.'</option>';
                        }
                      ?>                             
                    </select>
                  </div>  

 

                  <div class="col-sm-3">
                    <div class="input-group ">
                   <div class="input-group-prepend">
                      <span class="input-group-text">


                      <i class="fa fa-flag  " aria-hidden="true"></i></span>

                      </div>
                      <select required class="form-control" id="dn_category" name="dn_category" tabindex="6">
                      <option value="" disabled selected>Category</option>
                        <option value="Urgent">Urgent</option>
                        <option value="Important">Important</option>
                        <option value="When Time Permits">When Time Permits</option>   
                        <option value="Maybe">Maybe</option>
                      </select>
                    </div>
                  </div>

                  
                  <div class="col-sm-3">

                    <input type="reset" class="btn btn-warning cancel_post_dn" value="Cancel">


                    <button class="btn btn-orange process_Newbasic_info pull-right"  type="submit" ><em class="fas fa-save"></em> Submit</button>
                  </div>
                </div>


 
                </form>
              </div>              
            </div>
          </div> 
        </div>
        
      </div>


      <div id="" class="col-md-4 col-lg-3">


      <div class="mb-3 card">
          <div class="card-header-tab card-header">
            <div class="card-header-title">
              <i class="header-icon fas fa-clipboard-list icon-gradient  bg-orange-gradient "> </i>
              Sections
            </div>
            
          </div>
          <div class="card-body pt-1">
            <div class="tab-content">

              <div class="tab-pane show active" id="dev_notes_area" role="tabpanel">
               

               <div class="vertical-time-icons vertical-timeline mt-1 pt-1">
                
                 <?php foreach ($sections as $sectn ): ?>

                <div class="vertical-timeline-item vertical-timeline-element">
                  
                    <div class="vertical-timeline-element-icon bounce-in">
                      <div class="timeline-icon border-secondary bg-secondary">
                        <i class="fas fa-circle fa-w-8 text-white"></i>
                      </div>
                    </div>
                    <div class="vertical-timeline-element-content bounce-in">
                      <p class="mt-3 mb-3"><?php echo $sectn->dn_section_label; ?></p>
                    </div>
                
                </div>

                

                <?php endforeach; ?>



                

                
                

              </div>


              </div>
              


                            
            </div>
          </div> 

          <div class="d-block text-right card-footer">
            <form method="post" action="<?php echo base_url(); ?>Dev_notes/add_section">
              <div class="input-group mb-1  mt-1">
                <input placeholder="New" type="text" class="form-control" name="dn_n_section" autocomplete="off">
                <div class="input-group-append">
                  <button class="btn btn-orange" type="submit"><em class="fas fa-save"></em> Save</button>
                </div>


              </div>
            </form>


          </div>


        </div>



      </div>

 


    </div>
    <!-- main content here -->
  </div>