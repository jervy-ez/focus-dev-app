<div class="col-sm-12 col-md-1 col-lg-1 tour-2" id="leftCol" >
	<div class="nav nav-stacked affix-top" id="sidebar" >
		<div class="side-tools clearfix"  id="my-other-element">
			<ul>
				<!-- <li>
					<a href="<?php echo base_url(); ?>"> <i class="fa fa-tachometer fa-3x"></i> <label class="control-label">Dashboard</label> </a>
				</li> -->
				<?php if($this->session->userdata('company') >= 1): ?>
				<li>
					<a href="<?php echo base_url(); ?>company"> <i class="fa fa-university fa-3x"></i> <label class="control-label">Company</label></a>
				</li>
				<?php endif; ?>
				<?php if($this->session->userdata('projects') >= 1): ?>
					<li>
						<a href="<?php echo base_url(); ?>projects"> <i class="fa fa-map-marker fa-3x"></i> <label class="control-label">Projects</label></a>
					</li>
				<?php endif; ?>
			<?php /*	<li>
					<a href="<?php echo base_url(); ?>wip"> <i class="fa fa-tasks fa-3x"></i> <label class="control-label">WIP</label></a>
				</li> */ ?>

				<?php if($this->session->userdata('purchase_orders') >= 1): ?>
				<li>
					<a href="<?php echo base_url(); ?>purchase_order"> <i class="fa fa-credit-card fa-3x"></i> <label class="control-label">Purchase Orders</label></a>
				</li>
				<?php endif; ?>

				<?php if($this->session->userdata('invoice') >= 1): ?>
				<li>
					<a href="<?php echo base_url(); ?>invoice"> <i class="fa fa-list-alt fa-3x"></i> <label class="control-label">Invoice</label></a>
				</li>
				<?php endif; ?>

				<?php if($this->session->userdata('is_admin') == 1 || $this->session->userdata('labour_schedule') >= 1 ): ?>
				<li>
					<a href="<?php echo base_url(); ?>schedule"> <i class="fa fa-calendar fa-3x"></i> <label class="control-label">Schedule</label></a>
				</li>
				<?php endif; ?>
				<li>
					<a href="<?php echo base_url(); ?>contacts"> <i class="fa fa-phone-square fa-3x"></i> <label class="control-label">Contacts</label></a>
				</li>
				<?php if( $this->session->userdata('site_labour') >= 1 || $this->session->userdata('is_admin') ==  1): ?>		
					<li>
						<a href="<?php echo base_url(); ?>site_labour"> <i class="fa fa-street-view fa-3x"></i> <label class="control-label">Site Labour</label></a>
					</li>
				<?php endif; ?>	
				<?php if($this->session->userdata('is_admin') ==  1 || $this->session->userdata('user_id') == 6 || $this->session->userdata('user_id') == 32 ): ?>		
					<li>
						<b id = "site_staff_cont_upload" style='border-radius: 70px; color: white; position: relative; float: right; border: 1px solid #888; background: red; padding: 2px 5px 2px 5px; font-size: 10px'></b>
						<a href="<?php echo base_url(); ?>induction_health_safety"><i class="fa fa-shield fa-3x" style="margin-left: 16px;"></i> <label class="control-label">Induction,Health<br> and Safety</label></a>
					</li>
				<?php endif; ?>
		<?php /*		<?php if($this->session->userdata('users') > 0 || $this->session->userdata('is_admin') ==  1): ?>		
					<li>
						<a href="<?php echo base_url(); ?>referrals"> <i class="fa fa-share-alt fa-3x"></i> <label class="control-label">Referrals</label></a>
					</li>
				<?php endif; ?>		*/ ?>	
				<?php if($this->session->userdata('is_admin') ==  1): ?>
					<li>
						<a href="<?php echo base_url(); ?>incident_report"> <i class="fa fa-ambulance fa-3x"></i> <label class="control-label">Incident Report</label></a>
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
				
				<?php if($this->session->userdata('users') > 2 || $this->session->userdata('is_admin') ==  1): ?>
				<li>
					<a class="currently_logged_user pointer"> <i class="fa fa-sign-in fa-3x" ></i> <label class="control-label">Currenlty Logged-in</label></a>
				</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</div>

<script>
	$("#site_staff_cont_upload").hide();
	var baseurl = '<?php echo base_url() ?>';

	var is_admin = "<?php echo $this->session->userdata('is_admin') ?>";
	var user_id = "<?php echo $this->session->userdata('user_id') ?>";
	//setInterval(function(){ 
	if(is_admin == '1' || user_id == '6' || user_id == '32'){
		$.post(baseurl+"induction_health_safety/get_temporary_cont_site_staff",
        {},
        function(result){
        	$("#site_staff_cont_upload").show();
           	$("#site_staff_cont_upload").html(result);
        }); 
	}
		
	// }, 60000);

	
</script>