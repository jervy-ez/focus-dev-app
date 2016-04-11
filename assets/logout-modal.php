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




<?php $home = $this->uri->segment(1); ?>
<?php if($home != ""): ?>

<div class="users_utility_footer" style="position: fixed; bottom: 0px; background-color: #888">
  <div class="btn-group dropup pull-right" style="width: 100%;">
    <a class="btn dropdown-toggle" data-toggle="dropdown" style="color: white; width: 100%;" id="show_userlist">Users Currently Logged-in<span class="caret"></span></a>
    <div id="user_list" class="dropdown-menu pad-5" style="width: 250px; text-align: left; height: 300px; overflow: auto"></div>
  </div>
</div>
<?php endif; ?>


<?php $this->load->view('assets/right-sidebar-prj-commnts'); ?>