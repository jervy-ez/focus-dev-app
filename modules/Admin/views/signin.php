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
					<span class="d-block">Welcome back,</span>
					<span>Please sign in to your account.</span>
				</h4>
				<h6 class="mt-3">Input your credential here.</h6>
				<div class="divider row"></div>
				<div>
					<form class="form-horizontal" method="post" action="">	
						<div class="form-row">
							<div class="col-md-6">
								<div class="position-relative form-group">
									<label for="inputUserName" class="">Username</label>
									<input type="text" id="inputUserName" name="user_name" class="form-control" placeholder="Username here..."  value="">

								</div>
							</div>
							<div class="col-md-6">
								<div class="position-relative form-group">
									<label for="inputPassword" class="">Password</label>
									<input type="password" id="inputPassword" placeholder="Password here..." name="password" value="" class="form-control">
								</div>
							</div>
						</div>
						<div class="position-relative">

							<?php if(isset($error) && $error!= ''): ?>
								<label for="exampleCheck" class="form-check-label text-danger mt-20"><h5><em class="fas fa-exclamation-triangle"></em> <strong>Please check your login details</strong></h5><?php echo $error; ?></label>
							<?php endif; ?>

						</div>
						<div class="divider row"></div>
						<div class="d-flex align-items-center">
							<div class="ml-auto">
								<button class="btn btn-prime btn-lg" type="submit" >Login to Dashboard</button>
							</div>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>
</div>