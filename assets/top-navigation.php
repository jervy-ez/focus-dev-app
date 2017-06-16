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
				</ul>

			<?php endif; ?>




			<ul class="nav navbar-nav navbar-left">
				<li>
 
<input type="text" id="search_project_num" name="search_project_num" placeholder="seach project no" class="input-control input-xs tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="Seach by Project Number<br />Press the Enter button to submit" style="margin-top: 8px; border-radius: 4px; border: none; padding: 2px 6px;">
				 

				</li>
			</ul>






			<ul class="nav navbar-nav navbar-right">



<?php if($this->session->userdata('is_admin') == 1 || $this->session->userdata('user_role_id') == 16 || $this->session->userdata('is_admin') == 1 ): ?>

				<?php if (strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false): ?>
					
<?php if($this->session->userdata('is_admin') == 1 ): ?>					
					<li>
<a role="menuitem" data-toggle="modal" data-target="#view_dash" tabindex="-1" href="#"><span style="background: #fff; padding: 2px 8px; border-radius: 8px; color: #000000; text-shadow: none; margin-right: 5px;"> <span id="simulation_pm_name"></span> <i class="fa fa-eye-slash fa-lg" aria-hidden="true"></i> View</span></a>
					</li>
	<?php endif; ?>

				<?php endif; ?>
	<?php endif; ?>

				<li>
					<a role="menuitem"><i class="fa fa-quote-left" aria-hidden="true"></i> &nbsp;<em><?php echo $this->session->userdata('role_types'); ?></em>&nbsp; <i class="fa fa-quote-right" aria-hidden="true"></i></a>
				</li>


<!-- avv here -->	<li id="fat-menu" class="dropdown">
					<a href="#" id="drop3" role="button" class="dropdown-toggle ave_status_text" data-toggle="dropdown"><?php $this->users->get_user_availability($this->session->userdata('user_id')); ?> <b class="caret"></b></a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="drop3">
					
							<li role="">
								<a class="pointer set_ave_def" role="menuitem" tabindex="-1"  style="color: green;"><i class="fa fa-check-circle"></i> Available </a>
							</li>

							<li role="">
								<a class="pointer set_ave" role="menuitem" tabindex="-1" style="color: orange;" data-toggle="modal" data-target="#set_availability" tabindex="-1" href="#"><i class="fa fa-arrow-circle-left"></i> Out of Office </a>
							</li>

							<li role="">
								<a class="pointer set_ave" role="menuitem" tabindex="-1" style="color: red;" data-toggle="modal" data-target="#set_availability" tabindex="-1" href="#"><i class="fa fa-exclamation-circle"></i> Busy </a>
							</li>

							<li role="">
								<a class="pointer set_ave" role="menuitem" tabindex="-1" style="color: gray;" data-toggle="modal" data-target="#set_availability" tabindex="-1" href="#"><i class="fa fa-minus-circle"></i> Leave </a>
							</li>
							
							<li role="">
								<a class="pointer set_ave" role="menuitem" tabindex="-1" style="color: purple;" data-toggle="modal" data-target="#set_availability" tabindex="-1" href="#"><i class="fa fa-times-circle"></i> Sick </a>
							</li>
							
							
					</ul>
				</li>






				<li id="fat-menu" class="dropdown">
					<a href="#" id="drop3" role="button" class="dropdown-toggle tour-6" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo ucfirst($this->session->userdata('user_first_name')).' '.ucfirst($this->session->userdata('user_last_name')); ?> <b class="caret"></b></a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="drop3">
						<?php if($this->session->userdata('users') > 0 || $this->session->userdata('is_admin') ==  1): ?>
							<li role="presentation">
								<a role="menuitem" tabindex="-1" href="<?php echo base_url(); ?>users/account/<?php echo $this->session->userdata('user_id'); ?>"><i class="fa fa-cog"></i> My Account</a>
							</li>
						<?php endif; ?>


						<?php if( $this->session->userdata('is_admin') ==  1): ?>
							<li role="presentation">
								<a role="menuitem" tabindex="-1" href="<?php echo base_url(); ?>dev_notes"><i class="fa fa-pencil"></i> Dev Notes</a>
							</li>
                        				
                            <li>
                                <a role="menuitem" tabindex="-1" href="<?php echo base_url(); ?>dashboard/sales_forecast"><i class="fa fa-bar-chart"></i> Sales Forecast</a>
                            </li>
						<?php endif; ?>
						
						

<?php if($this->session->userdata('is_admin') == 1 || $this->session->userdata('user_role_id') == 16 || $this->session->userdata('is_admin') == 1 ): ?>
<?php if (strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false): ?>

<li><a role="menuitem" data-toggle="modal" data-target="#management_report" tabindex="-1" href="#"><i class="fa fa-file-text-o" aria-hidden="true"></i> Management Report</a></li>

<?php endif; ?>
<?php endif; ?>
						
						<li role="presentation" class="divider"></li>

						<li role="presentation">
							<a role="menuitem" data-toggle="modal" data-target="#logout" tabindex="-1" href="#"><i class="fa fa-sign-out"></i> Sign Out</a>
						</li>
					</ul>
				</li>
			</ul>
			

		</div><!--/.navbar-collapse -->
	</div>
</div>




<div class="">
	<div class="modal fade" id="projects_search_result" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-body clearfix pad-10">
					<table id="" class="table table-striped">
						<thead>
							<tr>
								<th>Number</th>
								<th>Project Title</th>
							</tr>
						</thead>
						<tbody class="search_result_projects"></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>



	<div class="modal fade" id="view_dash" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">View Other Dashboard</h4>
				</div>
				<form method="post" >
					<div class="modal-body pad-10">

						<div id="" class=""><select id="view_other_dashboard" class="form-control m-bottom-10">
							<option selected value="">Select Project Manager</option>
							<?php foreach ($project_manager as $row){	
									if($row->user_id != 29){ echo '<option value="'.$row->user_id.'-pm">'.$row->user_first_name.' '.$row->user_last_name.'</option>'; }
							}?>
							<option value="29-mn">Maintenance Manager</option>
						</select></div>

					</div>
				</form>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		$('select#view_other_dashboard').on("change", function(e) {
			var data = $(this).val();
     		$('#view_dash').modal('hide');
			$('#loading_modal').modal({"backdrop": "static", "show" : true} );

			setTimeout(function(){
     			$('#loading_modal').modal('hide');
				window.open("?dash_view="+data);
			},2000);
		});
	</script>



<?php if($this->session->userdata('is_admin') == 1 || $this->session->userdata('user_role_id') == 16 || $this->session->userdata('is_admin') == 1 ): ?>
	<div class="modal fade" id="management_report" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Management Report Setup</h4>
				</div>
				<form method="post" >
					<div class="modal-body pad-10">

						<div id="" class=""><select id="management_report_year" class="form-control m-bottom-10">
							<option selected value="">Select Year</option>
							<?php
							for ($starting_count=2015; $starting_count <= date('Y'); $starting_count++) { 
								echo '<option value="'.$starting_count.'">'.$starting_count.'</option>';
							}
							?>

						</select></div>

						<div id="" class=""><select id="management_report_pm" class="form-control m-bottom-10">
							<option selected value="">Select Project Manager</option>
							<?php foreach ($project_manager as $row){							
									echo '<option value="'.$row->user_id.'">'.$row->user_first_name.' '.$row->user_last_name.'</option>';
							}?>
						</select></div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<input type="button" class="btn btn-success set_management_report" data-dismiss="modal" value="Set Report">
					</div>
				</form>
			</div>
		</div>
	</div>
<?php endif; ?>

</div>




<div id="sb-site">
