</div>
<?php $this->load->module('projects'); ?>
<?php $this->load->model('projects_m'); ?>
<?php $projects_q = $this->projects_m->display_all_projects(); ?>
<div id="main-sidebar" class="main-sidebar main-sidebar-right right-sb-oc"  >
	<div class="section">
	<!--  -->

		<div class="" >
			<input type="hidden" class="prjc_user_id" value="<?php echo $this->session->userdata('user_id'); ?>" />
			<input type="hidden" class="prjc_user_first_name" value="<?php echo $this->session->userdata('user_first_name'); ?>" />
			<input type="hidden" class="prjc_user_last_name" value="<?php echo $this->session->userdata('user_last_name'); ?>" />

			<div class="note_side_area">


				<?php if(!isset($project_id) ): ?> 
					<div class="notes_init_search">
						Search Project Number  <a href="#"><strong  class="close-sb pull-right">Close</strong></a>

						<div class="clearfix m-top-15" style="padding: 0px;">                       

							<select class="prjc_project_id chosen_alt" id="prjc_project_id" style="width:235px;">
								<option value="" selected="selected" disabled="disabled">Select Project</option>
								<?php foreach ($projects_q->result_array() as $project_info): ?>
									<?php echo'<option value="'.$project_info['project_id'].'">'.$project_info['project_id'].' '.$project_info['project_name'].'</option>'; ?>
								<?php endforeach;   ?>
							</select>    

							<div class="btn btn-default proj_comments_search_bttn m-left-5" id="">Search</div>

						</div>      
					</div>
				<?php else: ?>
					<select class="prjc_project_id form-control" id="prjc_project_id" style="width:235px; display:none;">
						<option value="<?php echo $project_id; ?>" selected="selected" ><?php echo $project_id; ?></option>       
					</select>
				<?php endif; ?>

				<div class="notes_side_form m-top-15 clearfix" <?php echo (isset($project_id) ? '' : ' style="display:none;"'); ?>>
					<textarea class="form-control notes_comment_text " rows="5" id="notes_comment_text" placeholder="Comments"></textarea>
					<!-- add class to text area upper_c_first_word_sentence -->
					<div class="btn btn-primary btn-sm m-top-10 pull-right submit_notes_prj">Submit</div>
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

<style type="text/css">
	
  .notes_line.user_postby_ian, .notes_line.user_postby_ian small {
    background-color: #ed9b26;
    color: #fff !important;
  }

  .notes_line.user_postby_ian{
    padding: 5px;
  }
  
</style>

<?php if(isset($project_id) ): ?>
	<script type="text/javascript">$('select#prjc_project_id').val('<?php echo $project_id; ?>'); </script>
<?php endif; ?>  