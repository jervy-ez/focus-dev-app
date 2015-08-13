</div>

<div class="sb-slidebar sb-right sb-style-overlay">

	<input type="hidden" class="prjc_user_id" value="<?php echo $this->session->userdata('user_id'); ?>" />
	<input type="hidden" class="prjc_user_first_name" value="<?php echo $this->session->userdata('user_first_name'); ?>" />
	<input type="hidden" class="prjc_user_last_name" value="<?php echo $this->session->userdata('user_last_name'); ?>" />
	
	<div class="note_side_area">
		<?php if(!isset($project_id) ): ?> 
		<div class="notes_init_search">
			Search Project Number

			<div class="input-group m-top-10 ">
				<input class="form-control prjc_project_id" id="prjc_project_id" placeholder="Project Number" />
				<div class="input-group-addon btn btn-default proj_comments_search_bttn" id="">Search</div>
			</div>
		</div>
		<?php endif; ?>

		<div class="notes_side_form m-top-15 clearfix" <?php echo (isset($project_id) ? '' : ' style="display:none;"'); ?>>
			<textarea class="form-control notes_comment_text" rows="5" id="notes_comment_text" placeholder="Comments"></textarea>
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

<div id="logout" class="modal fade" tabindex="-1" data-width="760" style="display: none; overflow: hidden;">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">We are starting to miss you</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<h4>Are you sure to sign out????</h4>
						<p>Signing out will clear your session and drops you back to the login screen.</p>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<form method="post" role="form" action="<?php echo base_url().'users/logout' ?>">
					<input type="hidden" value="1" name="logout" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button type="submit" name="logout-submit" class="btn btn-danger">Sign me out now</button>
				</form>
			</div>
		</div>
	</div>
</div>

