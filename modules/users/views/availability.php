<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('admin'); ?>
<?php $this->load->module('bulletin_board'); ?>
<?php $user_un_ave = array(); ?>
<?php $time_diff = array(); ?>

<?php $current_date_time = strtotime(date("Y-m-d h:i A")); ?>

<?php foreach($users as $key => $user_init): ?> 
	<?php if(!$this->users->if_user_is_available($user_init->primary_user_id)): ?>
		<?php array_push($user_un_ave, $user_init->primary_user_id); ?>

		<?php $ave_data = $this->users->fetch_user_ave_data($user_init->primary_user_id); ?>
		<?php $diff_time =  $ave_data['date_time_stamp_b'] - $current_date_time; ?>
		<?php $time_diff[$user_init->primary_user_id] = $diff_time; ?>
	<?php endif; ?>
<?php endforeach; ?>

<?php arsort($time_diff); ?>


<?php 


$all_focus_company_q = $this->admin_m->fetch_all_company_focus();
$all_focus_company = $all_focus_company_q->result();


?>


<?php $post_code = array(); ?>
<?php foreach($all_focus_company as $key => $fcomp_data): ?>

<?php
	$admin_company_details = $this->admin_m->fetch_single_company_focus($fcomp_data->company_id);
	$data_comp = array_shift($admin_company_details->result_array() );

	$query_address= $this->company_m->fetch_complete_detail_address($data_comp['address_id']);
	$temp_data = array_shift($query_address->result_array());

	$p_query_address = $this->company_m->fetch_complete_detail_address($data_comp['postal_address_id']);
	$p_temp_data = array_shift($p_query_address->result_array());

	 $post_code[$fcomp_data->company_id] = $fcomp_data->area_code;


?>




<div id="f_compnay_<?php echo $fcomp_data->company_id; ?>" class="modal fade" tabindex="-1" data-width="760" style="display: none; overflow: hidden;">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title"><?php echo $fcomp_data->company_name; ?></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					
					<p><strong>Physical Address</strong></p>
				
					<div class='col-xs-12'>
						<p><i class="fa fa-globe" aria-hidden="true"></i>
							<?php echo ucwords(strtolower($temp_data['unit_level'])).'/'.$temp_data['unit_number'].' '.ucwords(strtolower($temp_data['street'])).' '.ucwords(strtolower($temp_data['suburb'])).' '.$temp_data['shortname'].' '.$temp_data['postcode']; ?>
						</p>
					</div>

					<div class='col-xs-12'><hr /></div>


					<p><strong>Postal Address</strong></p>
				
					<div class='col-xs-12'>
						<p><i class="fa fa-inbox " aria-hidden="true"></i>
							<?php echo 'PO Box '.$p_temp_data['po_box'].' '.ucwords(strtolower($p_temp_data['street'])).' '.ucwords(strtolower($p_temp_data['suburb'])).' '.$p_temp_data['shortname'].' '.$p_temp_data['postcode']; ?>
						</p>
					</div>


					<div class='col-xs-12'><hr /></div>

					<p><strong>Business</strong></p>
					<div class='col-xs-6'>
						 <p><i class="fa fa-briefcase" aria-hidden="true"></i> ABN: <?php echo $fcomp_data->abn; ?></p>
					</div> 
					<div class='col-xs-6'>
						 <p><i class="fa fa-briefcase" aria-hidden="true"></i> ACN:  <?php echo $fcomp_data->acn; ?></p>
					</div> 




					<div class='col-xs-12'><hr /></div>

					<p><strong>Contact Details</strong></p>
					<div class='col-xs-6'>
						 <p><i class="fa fa-phone-square" aria-hidden="true"></i> Office Number: <?php echo '<span class="area_code_comp">'.$fcomp_data->area_code.'</span> '.$fcomp_data->office_number; ?></p>
					</div> 
					<div class='col-xs-6'>
						 <p><i class="fa fa-envelope" aria-hidden="true"></i> Email:  <?php echo $fcomp_data->general_email; ?></p>
					</div> 
				</div>
			</div>
		</div>
	</div>
</div>

<?php endforeach; ?>


<!-- title bar -->
<div class="container-fluid head-control">
	<div class="container-fluid">
		<div class="row">

			<div class="col-md-6 col-sm-4 col-xs-12 pull-left">
				<header class="page-header">
					<h3><?php $datestring = "%l, %F %d, %Y"; $time = time(); //use time() for timestamp  ?>
						<?php echo $screen; ?> Screen<br><small><?php echo mdate($datestring, $time); #echo date("l, F d, Y"); ?></small>
					</h3>
				</header>
			</div>

			<div class="page-nav-options col-md-6 col-sm-8 col-xs-12 pull-right hidden-xs">
				<ul class="nav nav-tabs navbar-right">
					<li>
						<a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Home</a>
					</li>
					<?php if($this->session->userdata('users') > 0 || $this->session->userdata('is_admin') ==  1): ?>
						<li>
							<a href="<?php echo base_url(); ?>users/account/<?php echo $this->session->userdata('user_id'); ?>"><i class="fa fa-cog"></i> My Account</a>
						</li>
						<li>
							<a href="<?php echo base_url(); ?>users"><i class="fa fa-users"></i> Users</a>
						</li>
					<?php endif; ?>
					<?php if($this->session->userdata('is_admin') == 1 ): ?>
						<li>
							<a href="<?php echo base_url(); ?>admin" class="btn-small">Defaults</a>
						</li>
						<li>
							<a href="<?php echo base_url(); ?>admin/company" class="btn-small">Company</a>
						</li>
						<li>
							<a href="<?php echo base_url(); ?>users/user_logs">User Logs</a>
						</li>
					<?php endif; ?>


					<!-- <li>
						<a href="" class="btn-small"><i class="fa fa-magic"></i> Tour</a>
					</li> -->
				</ul>
			</div>

		</div>
	</div>
</div>
<!-- title bar -->


<div class="container-fluid">
	<!-- Example row of columns -->
	<div class="row">				
		<?php $this->load->view('assets/sidebar'); ?>
		<div class="section col-sm-12 col-md-11 col-lg-11">
			<div class="container-fluid basic">



				<div class="row">

				<div id="" class="col-lg-12">
					 



								<div class="border-less-box alert pad-5 row" style="background: #f9f9f9; border: 1px solid #cccbcb;">
									<div class="col-sm-1"><span style="color: white; background: green;" data-placement="bottom" class="tooltip-enabled badge" title="" data-original-title="The default so available via landline, mobile, email &amp; Skype (all options)"><i class="fa fa-check-circle"></i> Available </span></div>
									<div class="col-sm-1"><span style="color: white; background: darkorange;" data-placement="bottom" class="tooltip-enabled badge" title="" data-original-title="(Could also read AWAY) - Available via mobile &amp; email only"><i class="fa fa-arrow-circle-left"></i> Out of Office   </span></div>
									<div class="col-sm-1"><span style="color: white; background: red;" data-placement="bottom" class="tooltip-enabled badge" title="" data-original-title="This means that you are not contactable right now, send a message"><i class="fa fa-exclamation-circle"></i> Busy </span></div>
									<div class="col-sm-1"><span style="color: white; background: gray;" data-placement="bottom" class="tooltip-enabled badge" title="" data-original-title="Unavailable, forward if URGENT, will respond to message on return"><i class="fa fa-minus-circle"></i> Leave   </span></div>
									<div class="col-sm-1"><span style="color: white; background: purple;" data-placement="bottom" class="tooltip-enabled badge" title="" data-original-title="Unavailable, take a message, forward if URGENT"><i class="fa fa-times-circle"></i> Sick  </span></div>
									
									<div class="col-sm-2 text-right"><span data-toggle="modal" data-target="#f_compnay_4" tabindex="-1" data-placement="bottom" class="tooltip-enabled badge pointer" title="" data-original-title="Click to View Details" style="background: #00ADEF;"> &nbsp; FSF Group Pty Ltd   &nbsp;  </span></div>
									<div class="col-sm-2 text-right"><span data-toggle="modal" data-target="#f_compnay_5" tabindex="-1" data-placement="bottom" class="tooltip-enabled badge pointer" title="" data-original-title="Click to View Details" style="background: #F779B5;"> &nbsp; Focus Shopfit Pty Ltd   &nbsp;  </span></div>
									<div class="col-sm-2 text-right"><span data-toggle="modal" data-target="#f_compnay_6" tabindex="-1" data-placement="bottom" class="tooltip-enabled badge pointer" title="" data-original-title="Click to View Details" style="background: #F7901E;"> &nbsp; Focus Shopfit NSW Pty Ltd  &nbsp;  </span></div>

								</div>


				</div>
					
					<div class="col-lg-12">
						<div class="left-section-box" style="margin: -2px 0 0 0;">
							


							<div class="box-area clearfix">


								<?php if(@$this->session->flashdata('new_focus_company')): ?>
									<div class="no-pad-t m-bottom-10 pad-left-10">
										<div class="border-less-box alert alert-success fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>Congratulations!</h4>
											<?php echo $this->session->flashdata('new_focus_company');?>
										</div>
									</div>
								<?php endif; ?>
								
									<div class="row clearfix pad-left-15  pad-right-15 pad-bottom-10">

										<?php foreach ($time_diff as $key => $value): ?>
											<?php $user_un_ave_dta = $this->users->fetch_user_ave_data($key); ?>

											<?php if( $user_un_ave_dta['company_name'] == 'FSF Group Pty Ltd'){ $wid_type = 'c'; } ?>
											<?php if( $user_un_ave_dta['company_name'] == 'Focus Shopfit Pty Ltd'){ $wid_type = 'b'; } ?>
											<?php if( $user_un_ave_dta['company_name'] == 'Focus Shopfit NSW Pty Ltd'){ $wid_type = 'a'; } ?>

											<?php
												if( $user_un_ave_dta['status'] == 'Busy' ){ $color = 'red'; }
												elseif( $user_un_ave_dta['status'] == 'Out of Office' ){ $color = 'orange'; }
												elseif( $user_un_ave_dta['status'] == 'Leave' ){ $color = 'gray'; }
												elseif( $user_un_ave_dta['status'] == 'Sick' ){ $color = 'purple'; }
												else{ $color = 'green'; }
											?>

											<?php
												$user_contact_q = $this->user_model->fetch_user($key);
												$user_contact = array_shift($user_contact_q->result());

											 ?>


											<div class="col-md-4 col-sm-6 col-lg-3 col-xs-12">
												<div class="user_tags box-widget">
													<div class="box wid-type-<?php echo $wid_type; ?>">
														<div class="box-area pad-5 text-left pad-bottom-10 clearfix">
															<?php if($user_un_ave_dta['user_profile_photo'] == ''): ?>														
																<div class="text-center" style="float: left; overflow: hidden; height: 50px; ">
																	&nbsp; &nbsp; <i class="fa fa-user fa-3x"></i>
																</div>
															<?php else: ?>
																<div style="float: left; overflow: hidden; height: 50px; ">
																	<a href="<?php echo base_url('users/account/'.$key); ?>" ><img src="<?php echo base_url(); ?>uploads/users/<?php echo $user_un_ave_dta['user_profile_photo']; ?>" style="margin: 5px 5px; width: 50px;"></a>
																</div>
															<?php endif; ?>
															<p style="">


<span class="pull-right" >
<span class="ave_status_text_page"><?php echo $this->users->get_user_availability($key,1); ?> &nbsp; <?php echo $this->users->get_user_ave_comments($key); ?></span>

<br />



<span	style=" color:#fff; background:<?php echo $color; ?>; margin:5px 10px 0 0;" data-placement="left" class="badge tooltip-enabled pull-left" title="" data-original-title="<?php echo $user_contact->general_email; ?>"><i class="fa fa-envelope-o" aria-hidden="true"></i></span>

<?php if($user_contact->direct_number != ''): ?>
<span	style="<?php if( $user_un_ave_dta['status'] == 'Out of Office'  || $user_un_ave_dta['status'] == 'Sick'  || $user_un_ave_dta['status'] == 'Busy' || $user_un_ave_dta['status'] == 'Leave'){echo 'opacity: 0.5;'; } ?> color:#fff; background:<?php echo $color; ?>; margin:5px 10px 0 0;" data-placement="left" class="badge tooltip-enabled pull-left" title="" data-original-title="+<?php echo  $post_code[$user_contact->user_focus_company_id].' '.$user_contact->direct_number; ?>"><i class="fa fa-phone" aria-hidden="true"></i></span>
<?php endif; ?>


<?php if($user_contact->mobile_number != ''): ?>
<span	style="<?php if(  $user_un_ave_dta['status'] == 'Sick'  || $user_un_ave_dta['status'] == 'Busy'|| $user_un_ave_dta['status'] == 'Leave'){echo 'opacity: 0.5;'; } ?> color:#fff; background:<?php echo $color; ?>; margin:5px 10px 0 0;" data-placement="left" class="badge tooltip-enabled pull-left" title="" data-original-title="+<?php echo  $user_contact->mobile_number; ?>"><i class="fa fa-mobile" aria-hidden="true"></i></span>
<?php endif; ?>

</span>
																

																<span class="name"><a href="<?php echo base_url('users/account/'.$key); ?>" ><?php echo $user_un_ave_dta['user_first_name']; ?></a><br /><span style="font-size: 14px;"><?php echo $user_un_ave_dta['user_last_name']; ?></span></span>



															</p>
														</div>
													</div>




												</div>
											</div>

										<?php endforeach; ?>



<div id="" class="clearfix"></div>

 
									

										
										<?php foreach($users as $key => $user): ?> 
 
 



												<?php if( $user->company_name == 'FSF Group Pty Ltd'){ $wid_type = 'c'; } ?>
												<?php if( $user->company_name == 'Focus Shopfit Pty Ltd'){ $wid_type = 'b'; } ?>
												<?php if( $user->company_name == 'Focus Shopfit NSW Pty Ltd'){ $wid_type = 'a'; } ?>

<?php if($this->users->if_user_is_available($user->primary_user_id)): ?>
<div class="col-md-4 col-sm-6 col-lg-3 col-xs-12">

												<div class="user_tags box-widget">

													<div class="box wid-type-<?php echo $wid_type; ?>"  >

														 

 <div class="box-area pad-5 text-left pad-bottom-10 clearfix">
 	<?php if($user->user_profile_photo == ''): ?>

 		<div class="text-center" style="float: left; overflow: hidden; height: 50px; padding-top:5px; ">
 			&nbsp; &nbsp; <a class="user_link"  href="<?php echo base_url('users/account/'.$user->primary_user_id); ?>"><i class="fa fa-user fa-3x"></i></a>
 		</div>



 	<?php else: ?>

 		<div style="float: left; overflow: hidden; height: 50px; ">
 			<a class="user_link"  href="<?php echo base_url('users/account/'.$user->primary_user_id); ?>"><img src="<?php echo base_url(); ?>uploads/users/<?php echo $user->user_profile_photo; ?>" style="margin: 5px 5px; width: 50px;"></a>
 		</div>
 	<?php endif; ?>



 	<p>
 	<?php // echo base_url('users/account/'.$user->primary_user_id); ?>
<?php // echo '***'.$user->primary_user_id.'***'; ?>

<span class="pull-right ">

 	<span class="ave_status_text_page m-bottom-10"><?php echo $this->users->get_user_availability($user->primary_user_id,1); ?>



	<?php
 		$user_contact_q = $this->user_model->fetch_user($user->primary_user_id);
 		$user_contact = array_shift($user_contact_q->result());

 		?>

 		<br />

<span	style="color:#fff; background:green; margin-right:10px;" data-placement="left" class="badge m-top-5 tooltip-enabled pull-left" title="" data-original-title="<?php echo $user_contact->general_email; ?>"><i class="fa fa-envelope-o" aria-hidden="true"></i></span>
<?php if($user_contact->direct_number != ''): ?>
<span	style="color:#fff; background:green; margin-right:10px;" data-placement="left" class="badge m-top-5 tooltip-enabled pull-left" title="" data-original-title="+<?php echo  $post_code[$user_contact->user_focus_company_id].' '.$user_contact->direct_number; ?>"><i class="fa fa-phone" aria-hidden="true"></i></span>
<?php endif; ?>


<?php if($user_contact->mobile_number != ''): ?>
<span	style="color:#fff; background:green; margin-right:10px;" data-placement="left" class="badge m-top-5 tooltip-enabled pull-left" title="" data-original-title="+<?php echo  $user_contact->mobile_number; ?>"><i class="fa fa-mobile" aria-hidden="true"></i></span>
<?php endif; ?>

</span>



 	</span>
 	 
 
 	


																<span class="name"><a href="<?php echo base_url('users/account/'.$user->primary_user_id); ?>" ><?php  echo $user->user_first_name; ?></a><br /><span style="font-size: 14px;"><?php   echo $user->user_last_name;  ?></span></span>

 


</p>

 </div>
</div>

														 


													</div>
												</div>

 	<?php endif; ?>
											 

										<?php endforeach; ?>						
										

									</div>
					
							



							</div>
						</div>
					</div>					

					<!--<div class="col-md-3">
						
						<div class="box">
							<div class="box-head pad-5">
								<label><i class="fa fa-info-circle fa-lg"></i> Users Currently Logged-In</label>
								<button type = "button" class = "btn btn-primary btn-sm pull-right" id = "btn_logout_user">Log-out User</button>
							</div>
							<div class="box-area pad-10" id="login_user_list" style = "height: 200px; overflow: auto">								
							</div>
						</div>
					</div>-->

					
				</div>				
			</div>
		</div>
	</div>
</div>

<style type="text/css">
	.user_tags{
		float: left;
		padding: 5px;
		width: 100%;
	}

	.user_tags .name{
		font-size: 18px;
		margin-top: 5px;
		padding: 0 0px 0 70px;
		display: block;
	}


	.box-area a{
		color: white;
	}

</style>




<?php $this->bulletin_board->list_latest_post(); ?>
<?php $this->load->view('assets/logout-modal'); ?>