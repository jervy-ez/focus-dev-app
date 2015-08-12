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