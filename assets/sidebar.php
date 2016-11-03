<div class="col-sm-12 col-md-1 col-lg-1 tour-2" id="leftCol" >
	<div class="nav nav-stacked affix-top" id="sidebar" >
		<div class="side-tools clearfix"  id="my-other-element">
			<ul>
				<!-- <li>
					<a href="<?php echo base_url(); ?>"> <i class="fa fa-tachometer fa-3x"></i> <label class="control-label">Dashboard</label> </a>
				</li> -->
				<li>
					<a href="<?php echo base_url(); ?>company"> <i class="fa fa-university fa-3x"></i> <label class="control-label">Company</label></a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>projects"> <i class="fa fa-map-marker fa-3x"></i> <label class="control-label">Projects</label></a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>wip"> <i class="fa fa-tasks fa-3x"></i> <label class="control-label">WIP</label></a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>purchase_order"> <i class="fa fa-credit-card fa-3x"></i> <label class="control-label">Purchase Orders</label></a>
				</li>
				<li>
					<a href="<?php echo base_url(); ?>invoice"> <i class="fa fa-list-alt fa-3x"></i> <label class="control-label">Invoice</label></a>
				</li>
				<?php if($this->session->userdata('is_admin') == 1 || $this->session->userdata('labour_schedule') >= 1 ): ?>
				<li>
					<a href="<?php echo base_url(); ?>schedule"> <i class="fa fa-calendar fa-3x"></i> <label class="control-label">Schedule</label></a>
				</li>
				<?php endif; ?>
				<li>
					<a href="<?php echo base_url(); ?>contacts"> <i class="fa fa-phone-square fa-3x"></i> <label class="control-label">Contacts</label></a>
				</li>
				<?php if($this->session->userdata('users') > 0 || $this->session->userdata('is_admin') ==  1): ?>		
					<li>
						<a href="<?php echo base_url(); ?>referrals"> <i class="fa fa-share-alt fa-3x"></i> <label class="control-label">Referrals</label></a>
					</li>
				<?php endif; ?>			
				<?php if($this->session->userdata('users') > 0 || $this->session->userdata('is_admin') ==  1): ?>		
					<li>
						<a href="<?php echo base_url(); ?>users/availability"> <i class="fa fa-tags fa-3x"></i> <label class="control-label">Availability</label></a>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>users"> <i class="fa fa-users fa-3x"></i> <label class="control-label">Users</label></a>
					</li>
				<?php endif; ?>
				<?php if($this->session->userdata('is_admin') == 1 || $this->session->userdata('bulletin_board') >= 1 ): ?>
				<li>
					<a href="<?php echo base_url(); ?>bulletin_board"> <i class="fa fa-newspaper-o fa-3x" id="bulletin_board_lbl_sb"></i> <label class="control-label">Bulletin Board</label></a>
				</li>
				<?php endif; ?>
				
				<li>
					<a href="#" class="currently_logged_user"> <i class="fa fa-sign-in fa-3x" ></i> <label class="control-label">Currenlty Logged-in</label></a>
				</li>
			</ul>
		</div>
	</div>
</div>