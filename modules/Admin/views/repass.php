<div class="h-100">
	<div class="h-100 no-gutters row">
		<div class="d-none d-lg-block col-lg-4">
			<div class="" style="background: #d0581c; display: block; height: 100%; width: 100%; padding-top: 50%;">
				<img src="<?php echo base_url(); ?>img/AriesRailLogo-Hi.jpg" style="width: 412px; height: 291px; margin: 0 auto; display: block; ">
			</div>
		</div>
		<div class="h-100 d-flex bg-white justify-content-center align-items-center col-md-12 col-lg-8">
			<div class="mx-auto app-login-box col-sm-12 col-md-10 col-lg-9">
				<div class=""><h2 style="color: #d0581c;    font-weight: bold;">Job Summary</h2></div>
				<h4 class="mb-0">
					<span class="d-block">Your password has expired</span> 
				</h4>
				<h6 class="mt-3">Please input your log-in credentials and update.</h6>
				<div class="divider row"></div>
				<div>

					<?php if(isset($form_error_login) && $form_error_login != ''): ?>
						<div class="row">
							<div class="col-md-12">
								<div class="alert alert-danger fade show p-1" role="alert">
									<?php echo "$form_error_login"; ?>
								</div>
							</div>
						</div>
					<?php endif; ?>


					<form class="form-horizontal" method="post" action="<?php echo base_url() ?>Admin/changePwrd">
						<input type="hidden" name="userId" value="<?php echo $user_id; ?>">
						<input type="hidden" name="userName" value="<?php echo $user_name; ?>">
						
						<div class="form-row">
							<div class="col-md-6">
								<div class="position-relative form-group" data-toggle="tooltip" title="" data-placement="top" data-original-title="Must contain at least one  number and one uppercase and lowercase letter, and at least 8 or more characters">
									<label for="newPassword" class="">New Password</label>
									<input type="password" id="newPassword" placeholder="New Password here..." required="" name="newPassword" value="" class="form-control" minlength="8" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
								</div>
							</div> 


							<div class="col-md-6">
								<div class="position-relative form-group" data-toggle="tooltip" title="" data-placement="top" data-original-title="Must contain at least one  number and one uppercase and lowercase letter, and at least 8 or more characters">
									<label for="rePassword" class="">Confirm Password</label>
									<input type="password" id="rePassword" placeholder="Confirm Password here..." required="" name="rePassword" value="" class="form-control" minlength="8" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
								</div>
							</div>
						</div>
						<div class="position-relative">
							<?php if(@$error): ?>

								<label for="exampleCheck" class="form-check-label text-danger mt-20"><h5><em class="fas fa-exclamation-triangle"></em> <strong>Error</strong></h5><?php echo $error; ?></label>
							<?php endif; ?>							
						</div>
						<div class="divider row"></div>
						<div class="d-flex align-items-center">
							<div class="ml-auto">
								<button class="btn btn-prime btn-lg" type="submit" >Update and Login</button>
							</div>
						</div>



					</form>
				</div>
			</div>
		</div>
	</div>
</div>
