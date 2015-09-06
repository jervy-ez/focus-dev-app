<?php //if($this->session->userdata('logged_in')){ echo 'signin kna'; }else{ echo 'hindi pa';} ?>
<div class="navbar navbar-inverse navbar-fixed-top top-nav" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand logo" href="<?php echo base_url(); ?>" ><em><i class="fa fa-tachometer"></i> Sojourn</em></a>
		</div>

		<div class="navbar-collapse collapse">
			<!-- <ul class="nav navbar-nav">
				<li>
					<a href="#" role="button"><i class="fa fa-coffee"></i>link</a>					
				</li>
			</ul> -->

			<?php if($this->session->userdata('is_admin') == 1 ): ?>

			<ul class="nav navbar-nav navbar-left">
				<li id="fat-menu" class="dropdown">
					<a href="#" id="drop3" role="button" class="dropdown-toggle tour-6" data-toggle="dropdown"><i class="fa fa-coffee"></i> Admin Controls <b class="caret"></b></a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="drop3">
						<li role="presentation">
							<a role="menuitem" tabindex="-1" href="<?php echo base_url(); ?>admin">Defaults</a>
						</li>
						<li role="presentation">
							<a role="menuitem" tabindex="-1" href="<?php echo base_url(); ?>admin/company">Focus Company</a>
						</li>
						<li role="presentation">
							<a role="menuitem" tabindex="-1" href="<?php echo base_url(); ?>users">Users</a>
						</li>						
					</ul>
				</li>
			</ul>

		<?php endif; ?>

		<?php if($this->session->userdata('user_id') == 7 ): ?>

			<ul class="nav navbar-nav navbar-left">
				<li id="fat-menu" class="dropdown">
					<a href="#" id="drop3" role="button" class="dropdown-toggle tour-6" data-toggle="dropdown"><i class="fa fa-coffee"></i> Admin Controls <b class="caret"></b></a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="drop3">
						<li role="presentation">
							<a role="menuitem" tabindex="-1" href="<?php echo base_url(); ?>users">Users</a>
						</li>
						<li role="presentation" class="divider"></li>
						<li role="presentation">
							<a role="menuitem" tabindex="-1" href="<?php echo base_url(); ?>users/add"><i class="fa fa-user-plus"></i> Add New User</a>
						</li>
					</ul>
				</li>
			</ul>

		<?php endif; ?>





		

			<!-- <ul class="nav navbar-nav navbar-right">
				<li>
					<a role="menuitem" data-toggle="modal" data-target="#logout" tabindex="-1" href="#"><i class="fa fa-sign-out"></i> Sign Out</a>				
				</li>
			</ul> -->


			
			<ul class="nav navbar-nav navbar-right">
				<li id="fat-menu" class="dropdown">
					<a href="#" id="drop3" role="button" class="dropdown-toggle tour-6" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo ucfirst($this->session->userdata('user_first_name')).' '.ucfirst($this->session->userdata('user_last_name')); ?> <b class="caret"></b></a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="drop3">
						<!-- <li role="presentation">
							<a role="menuitem" tabindex="-1" href="#">Action</a>
						</li>
						<li role="presentation">
							<a role="menuitem" tabindex="-1" href="#">Another action</a>
						</li>
						<li role="presentation">
							<a role="menuitem" tabindex="-1" href="#">Something else here</a>
						</li> 
						<li role="presentation" class="divider"></li>-->
						<li role="presentation">
							<a role="menuitem" data-toggle="modal" data-target="#logout" tabindex="-1" href="#"><i class="fa fa-sign-out"></i> Sign Out</a>
						</li>
					</ul>
				</li>
			</ul>
			

		</div><!--/.navbar-collapse -->
	</div>
</div>


<div id="sb-site">
