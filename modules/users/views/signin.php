<div class="sign_in_bg" style=" background-image:url('<?php echo base_url(); ?>img/SOJOURN-CHRISTMAS-SEASONS-2017-2.jpg') !important; height: 99.9%; margin-top: 0px !important;">
    
<script src="<?php echo base_url(); ?>js/snow-it.min.js"></script>

	<script type="text/javascript">
	$.fn.snowit({

  // min size of snowflake
  minSize : 10,

  // max size of snowflake
  maxSize : 50,

  // the number of flakes generated
  total : 100,

  // speed of flakes when fall down
  speed: 75,

  // color of snowflake
  flakeColor : "#FFFFFF"
  
});
	</script>

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
	
	<?php if(isset($signin_error)): ?>
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


	<?php if(@$this->session->flashdata('new_pass_msg')): ?>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-6 col-md-offset-3">
				<div class="border-less-box alert alert-success fade in">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true"> × </button>
					<h4>The new password is been set!</h4>
					<?php echo $this->session->flashdata('new_pass_msg');?>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>
			
	<div class="container pad-20">
		<div class="row">
			<div class="col-xs-12 col-md-6 col-md-offset-3 sign_form">
				<h1 class="text-center">Sign in</h1>
				<div class="well"  style="border: 2px solid #b5b5b5; border-radius: 8px;">
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
<style type="text/css">
	body {
		margin-top: 0 !important;
		padding-top: 0 !important;
	}
	
	.lis-flake {
	    -webkit-touch-callout: none; /* iOS Safari */
    -webkit-user-select: none; /* Safari */
     -khtml-user-select: none; /* Konqueror HTML */
       -moz-user-select: none; /* Firefox */
        -ms-user-select: none; /* Internet Explorer/Edge */
            user-select: none; /* Non-prefixed version, currently
                                  supported by Chrome and Opera */
    z-index: 30;
    border: 0 !important;
    
}
</style>