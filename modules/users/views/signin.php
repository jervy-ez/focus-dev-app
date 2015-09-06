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
				<h1 class="text-center">Sign in</h1>
				<div class="well">
					<form class="form-horizontal" method="post" action="">					
						
						<div class="form-group">
							<label for="inputUserName" class="col-sm-2 control-label">User Name</label>
							<div class="col-sm-10">
								<div class="input-group <?php if(form_error('user_name')){ echo 'has-error has-feedback';} ?>">
									<span class="input-group-addon"><i class="fa fa-user"></i></span>
									<input type="text" id="inputUserName" placeholder="User Name" name="user_name" class="form-control"  value="<?php echo $this->input->post('user_name'); ?>">
								</div>
							</div>
						</div>
											
						<div class="form-group">
							<label for="inputPassword3" class="col-sm-2 control-label">Password</label>
							<div class="col-sm-10">
								<div class="input-group <?php if(form_error('password')){ echo 'has-error has-feedback';} ?>">
									<span class="input-group-addon"><i class="fa fa-unlock-alt"></i></span>
									<input type="password" id="inputPassword" placeholder="Password" name="password" value="" class="form-control">
								</div>
							</div>
						</div>
											
						<div class="form-group">
							<label for="inputPassword3" class="col-sm-2 control-label"></label>
							<div class="col-sm-10">
								<div class="input-group <?php if(form_error('password')){ echo 'has-error has-feedback';} ?>">
									<input type="checkbox" name="remember" id="remember" class="remember">&nbsp;
									<label for="remember" class="control-label"> Remember me</label>
								</div>
								<button style="margin-top: 5px;" type="submit" class="btn btn-primary pull-right" onclik = "sign_in()"><i class="fa fa-sign-in"></i> Sign in</button>
							</div>
						</div>	
						
					</form>
				</div>
			</div>
		</div>
	</div>
</div>