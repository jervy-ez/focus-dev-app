<?php $this->load->module('dev_notes'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>temp_css/datatables.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>temp_css/bootstrap-datetimepicker-standalone.css">

<div class="app-main__inner">

  <!-- main content here -->

    <div class="row">

      
      <div class="col-md-12 col-lg-11">
        <div class="mb-3 card">
          <div class="card-header-tab card-header">
            <div class="card-header-title">
              <i class="header-icon fas fa-list-alt icon-gradient  bg-orange-gradient "> </i>
              <?php echo $post_detail['dn_title']; ?>
            </div>
            <ul class="nav">
              <li class="nav-item"><a data-toggle="tab" href="#dev_notes_area" class="nav-link show active orange-text">Details</a></li>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content">

              <div class="tab-pane show active" id="dev_notes_area" role="tabpanel">

                <form class="needs-validation" id="newUserDetails" method="post" action="<?php echo base_url(); ?>Dev_notes/update_post" novalidate="novalidate">
                <div class="form-row mb-3">

                  <div class="col-sm-5">
                    <div class="input-group ">
                      <span class="input-group-addon"><i class="fa fa-pencil-square-o  "></i></span>
                      <input required type="text" class="form-control" placeholder="Title" name="notes_title" id="notes_title" maxlength="50" tabindex="1" value="<?php echo $post_detail['dn_title']; ?>" autocomplete="off"> 
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
                  <script type="text/javascript"> document.getElementById('dn_assnmt').value = <?php echo ($this->input->post('dn_assnmt') ? $this->input->post('dn_assnmt') : $post_detail['dn_prgm_user_id'] ); ?>; </script>


                  <div class="col-sm-4">
                    <div class="input-group date" id=" ">
                    <div id="" class="input-group-prepend"><span class="input-group-text"><i class="fas fa-calendar  "></i> &nbsp; Start </span></div>
                    <input tabindex="3" type="text" placeholder="DD/MM/YYYY"  class="form-control" id="start_date_picker" name="date_stamp" value="<?php echo $post_detail['dn_date_commence']; ?>" autocomplete="off">
                   </div>
                 </div>

               </div>



                <div class="form-row  mb-3">
                  <div id="" class="col-sm-12">
                    <div class="m-t-10">
                      <div class="pad-5 clearfix">
                        <div class="clearfix ">
                          <div class="">
                            <textarea required class="form-control" id="project_notes" rows="20" tabindex="4" name="comments"  placeholder="Details"  style="resize: vertical; z-index: auto; position: relative; line-height: 20px; font-size: 14px; transition: none; background: transparent !important;"><?php echo  ($this->input->post('comments') ? $this->input->post('comments') : $post_detail['dn_post_details'] ); ?></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>





 
                <div class="form-row mb-3">


                 <div class="col-sm-4">
                    <div class="input-group date" id=" ">
                    <div id="" class="input-group-prepend"><span class="input-group-text"><i class="fas fa-calendar  "></i> &nbsp; Completion </span></div>
                    <input tabindex="6" type="text" placeholder="DD/MM/YYYY" class="form-control datepicker" id="dn_date_complete" name="dn_date_complete" value="<?php echo $post_detail['dn_date_complete']; ?>" autocomplete="off">
                   </div>
                 </div>

                  <div class="col-sm-2">
                    <select required class="form-control" id="dn_section" name="dn_section" tabindex="5">
                      <option  value="" disabled selected>Section</option>
                      <?php
                        foreach ($sections as $sectn ) {
                          echo '<option value="'.$sectn->dn_section_id.'">'.$sectn->dn_section_label.'</option>';
                        }
                      ?>                             
                    </select>
                  </div>  
                  <script type="text/javascript"> document.getElementById('dn_section').value = '<?php echo ($this->input->post('dn_section') ? $this->input->post('dn_section') : $post_detail['dn_section_id'] ); ?>'; </script>

 

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
                  <script type="text/javascript"> document.getElementById('dn_category').value = '<?php echo ($this->input->post('dn_category') ? $this->input->post('dn_category') : $post_detail['dn_category'] ); ?>'; </script>

                  
                  <div class="col-sm-3">

                  <input type="hidden" name="post_id" value="<?php echo $post_detail['dn_id']; ?>">
                    <a href="../" class="btn btn-secondary">Return</a>


                    <button class="btn btn-orange process_Newbasic_info pull-right"  type="submit" ><em class="fas fa-save"></em> Submit</button>
                  </div>
                </div>


 
                </form>
              </div>              
            </div>
          </div> 
        </div>
        
      </div>


      

    </div>
    <!-- main content here -->
  </div>








