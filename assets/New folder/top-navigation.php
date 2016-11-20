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
 

			<ul id="mobile_nav" class="nav navbar-nav navbar-left">
				<li>
					<a href="<?php echo base_url(); ?>company"> <i class="fa fa-users fa"></i> Company</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>projects"> <i class="fa fa-map-marker fa"></i> Projects</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>wip"> <i class="fa fa-tasks fa"></i> WIP</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>purchase_order"> <i class="fa fa-credit-card fa"></i> Purchase Orders</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>invoice"> <i class="fa fa-list-alt fa"></i> Invoice</a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>contacts"> <i class="fa fa-phone-square fa"></i> Contacts</a>
				</li>		
				<?php if($this->session->userdata('users') > 0 || $this->session->userdata('is_admin') ==  1): ?>		
					<li>
						<a href="<?php echo base_url(); ?>users"> <i class="fa fa-users fa"></i> Users</a>
					</li>
				<?php endif; ?>
				 <?php if($this->session->userdata('is_admin') == 1 || $this->session->userdata('bulletin_board') >= 1 ): ?>
				<li>
					<a href="<?php echo base_url(); ?>bulletin_board"> <i class="fa fa-newspaper-o fa" id="bulletin_board_lbl_sb"></i> Bulletin Board</a>
				</li>
				<?php endif; ?>
				<li>
					<a href="#" class="currently_logged_user"> <i class="fa fa-sign-in fa" ></i> Currenlty Logged-in</a>
				</li>
				<li class="divider-menu"></li>
			</ul>

			<?php if($this->session->userdata('is_admin') == 1 ): ?>

				<ul class="nav navbar-nav navbar-left">
					<li>
						<a role="menuitem" tabindex="-1" href="<?php echo base_url(); ?>admin"><i class="fa fa-coffee"></i> Admin Defaults</a>
					</li>
					<li>
						<a role="menuitem" tabindex="-1" href="<?php echo base_url(); ?>admin/company"><i class="fa fa-briefcase"></i> Focus Company</a>
					</li>
					<li>
						<a role="menuitem" tabindex="-1" href="<?php echo base_url(); ?>dashboard/sales_forecast"><i class="fa fa-bar-chart"></i> Sales Forecast</a>
					</li>
				</ul>

			<?php endif; ?>



			<ul class="nav navbar-nav navbar-left">
				<li>

				<form methd="post" action="<?php echo base_url(); ?>search" style="margin: 8px 0px 0px;">
					
<input type="text" name="project_id" placeholder="seach project no" class="input-control input-xs tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="Seach by Project Number<br />Press the Enter button to submit" style=" border-radius: 4px; border: none; padding: 2px 6px;">
				
				</form>

				</li>
			</ul>


			
			<ul class="nav navbar-nav navbar-right">
				<li>
					<a role="menuitem"><i class="fa fa-quote-left" aria-hidden="true"></i> &nbsp;<em><?php echo $this->session->userdata('role_types'); ?></em>&nbsp; <i class="fa fa-quote-right" aria-hidden="true"></i></a>
				</li>
				<li id="fat-menu" class="dropdown">
					<a href="#" id="drop3" role="button" class="dropdown-toggle tour-6" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo ucfirst($this->session->userdata('user_first_name')).' '.ucfirst($this->session->userdata('user_last_name')); ?> <b class="caret"></b></a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="drop3">
						<?php if($this->session->userdata('users') > 0 || $this->session->userdata('is_admin') ==  1): ?>
							<li role="presentation">
								<a role="menuitem" tabindex="-1" href="<?php echo base_url(); ?>users/account/<?php echo $this->session->userdata('user_id'); ?>"><i class="fa fa-cog"></i> My Account</a>
							</li>
							<li role="presentation" class="divider"></li>
						<?php endif; ?>
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
