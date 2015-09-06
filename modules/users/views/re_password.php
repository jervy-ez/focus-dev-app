<div class="sign_in_bg">
	<?php if(@$error): ?>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-6 col-md-offset-3">
				<div class="border-less-box alert alert-danger fade in">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true"> × </button>
					<h4>Oh snap! You got an error!</h4>
					<?php echo $error; ?>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>
	
	<?php if(@$signin_error): ?>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-6 col-md-offset-3">
				<div class="border-less-box alert alert-danger fade in">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true"> × </button>
					<h4>Oh snap! You got an error!</h4>
					<?php echo $signin_error; ?>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>
			
	<div class="container pad-20">
		<div class="row">
			<div class="col-xs-12 col-md-6 col-md-offset-3 sign_form">
				<h1 class="text-center">Update Account</h1>
				<div class="well">

					<form method="post" class="change_password_form" onkeypress="return event.keyCode != 13;">						

						<div id="passstrength" class="pad-5 border-less-box alert alert-info m-bottom-10">Note: The new password must contain a minimum of 7 characters, a number, a symbol and a capital letter.</div>

						<div class="clearfix m-top-0 m-bottom-10">
							<label for="new_password" class="col-sm-5 control-label m-top-5" style="font-weight: normal;">New Password</label>
							<div class="col-sm-7">
								<input type="password" class="form-control tooltip-enabled" id="new_password" name="new_password" placeholder="New Password" value="" data-original-title="Note: The new password must contain a minimum of 7 characters, a number, a symbol and a capital letter.">
							</div>
						</div>


						<div class="clearfix m-top-10 m-bottom-10">
							<label for="confirm_password" class="col-sm-5 control-label m-top-5" style="font-weight: normal;">Confirm Password</label>
							<div class="col-sm-7">
								<input type="password" class="form-control" disabled="true" id="confirm_password" name="confirm_password" placeholder="Confirm Password" value="">
							</div>
						</div>
						<div class="clearfix"></div>




					</form>
					<div class="clearfix"></div>


				</div>
			</div>
		</div>
	</div>
</div>