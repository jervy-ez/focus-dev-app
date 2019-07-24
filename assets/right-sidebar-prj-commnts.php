</div>
<?php $this->load->module('projects'); ?>
<?php $this->load->model('projects_m'); ?>
<?php $projects_q = $this->projects_m->display_all_projects(); ?>




<div id="prj_comments_sidebar" class="dynmc_sb main-sidebar main-sidebar-right right-sb-oc"  style="display:none; width: 35%;height: 100%;background-color: #0E283C;position: fixed;top: 0px;right: -25%;color: #fff;z-index: 10000;">


	<div class="section" style="height:100%; overflow: auto;">
	<!--  -->

		<div class="" >
			<input type="hidden" class="prjc_user_id" value="<?php echo $this->session->userdata('user_id'); ?>" />
			<input type="hidden" class="prjc_user_first_name" value="<?php echo $this->session->userdata('user_first_name'); ?>" />
			<input type="hidden" class="prjc_user_last_name" value="<?php echo $this->session->userdata('user_last_name'); ?>" />

			<?php if(isset($project_id) ): ?> 
				<p><strong>Project Number: <?php echo $project_id; ?></strong>   <span  class="close-sb pull-right pointer strong"><i class="fa-times-circle fa"></i> Close</span> </p>
			<?php endif; ?>

			<div class="note_side_area">


				<?php if(!isset($project_id) ): ?> 
					<div class="notes_init_search">
						Search Project Number   <span  class="close-sb pull-right pointer strong"><i class="fa-times-circle fa"></i> Close</span> 

						<div class="clearfix m-top-15" style="padding: 0px; color: #555 !important;">                       

							<select class="prjc_project_id form-control pull-left" id="prjc_project_id" style="width:100%;">
								<option value="" selected="selected" disabled="disabled">Select Project</option>
								<?php foreach ($projects_q->result_array() as $project_info): ?>
									<?php echo'<option value="'.$project_info['project_id'].'">'.$project_info['project_id'].' '.$project_info['project_name'].'</option>'; ?>
								<?php endforeach;   ?>
							</select>    

							<div class="btn btn-default proj_comments_search_bttn m-left-5" id=""  style="    position: absolute;    right: 0;">Search</div>

						</div>      
					</div>
				<?php else: ?>
					<select class="prjc_project_id form-control" id="prjc_project_id" style="width:235px; display:none;">
						<option value="<?php echo $project_id; ?>" selected="selected" ><?php echo $project_id; ?></option>       
					</select>
				<?php endif; ?>

				<div class="notes_side_form m-top-15 clearfix" <?php echo (isset($project_id) ? '' : ' style="display:none;"'); ?>>
				


         <?php if($this->session->userdata('projects') >= 2): ?>
          <textarea class="form-control notes_comment_text " rows="5" id="notes_comment_text" placeholder="Comments"></textarea>
          <!-- add class to text area upper_c_first_word_sentence -->
          <div class="btn btn-primary btn-sm m-top-10 pull-right submit_notes_prj">Submit</div>


        <?php endif; ?>


					<div class="btn btn-warning btn-md m-top-10 m-right-5 pull-right proj_comments_search_bttn" id=""><i class="fa fa-refresh"></i> </div>
					<p class="m-top-20" style="width: 140px;"><strong>Project Comments</strong></p>
				</div>
				<div class="notes_side_content clearfix">      
					<?php if(isset($project_id) ): ?>
						<?php echo $this->projects->list_project_comments($project_id); ?>
					<?php else: ?>
						<div class="notes_line no_posted_comment"><p>No comments displayed.</p></div>
					<?php endif; ?>
				</div>

			</div>
		</div>

		<!--  -->
	</div>
</div>




<div class="toggle_project_amendments dynmc_sb" style="display:none; width: 35%; overflow-y: auto; height: 100%;background-color: #0E283C;position: fixed;top: 0px;right: -35%;color: #fff;z-index: 1040;">


  <div class="prj_amnd_side_area pad-10">


    <input type="hidden" class="prjamnd_user_id" value="<?php echo $this->session->userdata('user_id'); ?>" />
    <input type="hidden" class="prjamnd_user_first_name" value="<?php echo ucfirst($this->session->userdata('user_first_name')); ?>" />
    <input type="hidden" class="prjamnd_user_last_name" value="<?php echo ucfirst($this->session->userdata('user_last_name')); ?>" />
    <input type="hidden" class="prjamnd_project_id" value="0" />
    <input type="hidden" class="prjamnd_btn_clck_id" value="" />

    <!--  <span class="project_title_amnd">PRJID</span>  -->

    <?php $this->load->module('projects'); ?>
    <?php $this->load->model('projects_m'); ?>
    <?php $projects_q = $this->projects_m->display_all_projects(); ?>

      <div class="notes_init_search"><strong class="side_bar_label">Project Amendments</strong>  <strong class="pointer close_toggle_amnds pull-right"><i class="fa-times-circle fa"></i> Close</strong> 
    <?php if(!isset($project_id) ): ?> 

        <div class="clearfix m-top-15" style="padding: 0px; color: #555 !important;">                       

          <select class="amnds_project_id form-control pull-left" id="amnds_project_id" style="width:100%;">
            <option value="" style="display:none;">Select Project</option>
            <?php $date_today_tmpstp = strtotime("now"); ?>

            <?php foreach ($projects_q->result_array() as $project_info): ?>
              <?php echo'<option value="'.$project_info['project_id'].'"';

              echo ' class=" '. ($project_info['unix_start_date'] <=  $date_today_tmpstp ? 'prj_disabled_amnd' : '').' " >'.$project_info['project_id'].' '.$project_info['project_name'].'</option>'; ?>
            <?php endforeach;   ?>
          </select>    

          <div class="btn btn-default proj_amnds_search_bttn m-left-5" id=""  style="    position: absolute;    right:5px;">Search</div>

        </div>      
      </div>
    <?php else: ?>
      <select class="amnds_project_id form-control" id="amnds_project_id" style="width:235px; display:none;">
        <option value="<?php echo $project_id; ?>" selected="selected" ><?php echo $project_id; ?></option>       
      </select>
          <div class="btn btn-default proj_amnds_search_bttn m-left-5" id=""  style="    display:none; position: absolute;    right:5px;">Search</div>

          <script type="text/javascript">



              setTimeout(function(){

 
          $('.proj_amnds_search_bttn').trigger('click');


          $('.sb-open-right, .submit_notes_prj').click(function(){
            setTimeout(function(){
              $('.proj_comments_search_bttn').trigger('click');

            },100);
          });

          $('.prj_amndnts_bttn').click(function(){
            $('.proj_amnd_reload_bttn').trigger('click');
          });





          


            },100);



          </script>
    <?php endif; ?>


    <div class="amnds_side_form m-top-15 clearfix" style="display:none; margin-bottom: -30px;">
      <textarea class="form-control amnds_comment_text " rows="5" id="amnds_comment_text" placeholder="Details" style="resize: vertical; min-height: 100px;"></textarea>
      <div class="btn btn-primary btn-sm m-top-10 pull-right submit_amnds_prj">Submit</div>
      <div class="btn btn-warning btn-md m-top-10 m-right-5 pull-right proj_amnd_reload_bttn" id=""><i class="fa fa-refresh"></i> </div>
    </div>

    <div class="amnds_line_select_project"  style="padding-top: 10px;   margin-bottom: 10px; font-weight:bold;"><p style="padding-top:10px; clear: both;">Please Select Project.</p></div>
    <div class="amnds_side_content  clearfix"  style=" margin-top:10px;  clear: both; "></div>

  </div>
</div>

<style type="text/css">
	
  .notes_line.user_postby_ian, .notes_line.user_postby_ian small {
    background-color: #ed9b26 !important;
    color: #fff !important;
  }

  .notes_line.comment_type_0{
    padding: 5px;
    background-color: #16466b;
    color: #fff !important;
  }

  .notes_line.comment_type_0 small{
    color: #fff !important;
  }

</style>

<?php if(isset($project_id) ): ?>
	<script type="text/javascript">$('select#prjc_project_id').val('<?php echo $project_id; ?>'); </script>
<?php endif; ?>  