<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('bulletin_board'); ?>
<?php $color_group = array('000','4DAB4D','935FA6','795548','00ADEF','F779B5','F7901E','4DAB4D','935FA6','795548'); ?>

<?php $color_group['3197'] = '4caf50'; ?>
<?php $leave_requests = $this->session->userdata('leave_requests'); ?>
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
						<li>
							<a href="<?php echo base_url(); ?>users/leave_details/<?php echo $this->session->userdata('user_id'); ?>">My Leave Requests</a>
						</li>
					<?php if ($leave_requests == 1): ?>
						<li>
							<a href="<?php echo base_url(); ?>users/leave_approvals/<?php echo $this->session->userdata('user_id'); ?>">Leave Approvals</a>
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
					
					<div class="col-md-12">
						<div class="left-section-box">
							<div class="box-head pad-10 clearfix">						

								<label><?php echo $screen; ?></label><span> (<a href="#" data-placement="right" class="popover-test" title="" data-content="Hello there mate! This is the new Focus organizational chart and resposibility matrix screen." data-original-title="Welcome">?</a>)</span>
								<p class="hide"><a href="#" class="tooltip-test" title="Tooltip">This link</a> and <a href="#" class="tooltip-test" title="Tooltip">that link</a> should have tooltips on hover.</p>								
							</div>
							<div class="  clearfix">


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
 
<p><br /></p>
										 <h4><strong>Organizational Chart</strong></h4>
										 <hr style="margin: 5px 0;">


										 <style type="text/css">

										 	.box a,.box h3{
										 		color: #fff !important;

										 	}

										 	.box a:hover{
										 		text-transform:none !important;
										 		text-decoration: none !important;
										 	}

										 	.gray_color{
												border: 3px solid gray !important;
										 	}

										 	.gray_color{
												border: 3px solid #555 !important;
										 	}

										 	.gray_color + p + p{
												color: #555 !important;

										 	}

										 	 .gray_color + p strong{
													background: #555 !important;
													color: #fff !important;
													padding: 3px 6px;
													border-radius: 6px;
												}

										 </style>



<div class="  clearfix">

 
<?php echo $this->users->loop_compamy_group(4); ?>
 

												<style type="text/css">

										<?php foreach ($all_focus_company as $key => $value): ?>
											
												.user_<?php echo $value->company_id; ?>_comp_group{
													border: 3px solid #<?php echo $color_group[$value->company_id]; ?>;
												}

												.user_<?php echo $value->company_id; ?>_comp_group + p strong{
													background: #<?php echo $color_group[$value->company_id]; ?>;
													color: #fff;
													padding: 3px 6px;
													border-radius: 6px;
												}


												.user_<?php echo $value->company_id; ?>_comp_group + p + p, .user_<?php echo $value->company_id; ?>_comp_group + p + p a{
													color: #<?php echo $color_group[$value->company_id]; ?>;
													font-weight: bold;
													    margin-top: -5px;
												}



 

			.wid-type-<?php echo $value->company_id; ?>_comp_group .widg-head{
				opacity: 0.5;
				background: #fff !important;

				color: #<?php echo $color_group[$value->company_id]; ?> !important;
				padding-left: 20px;
			}

			.wid-type-<?php echo $value->company_id; ?>_comp_group{
																	background: #<?php echo $color_group[$value->company_id]; ?> !important;
 
			}
 


										<?php endforeach; ?>

 


.blanks{
		width: 200px;
	}

												</style>


										</div>
									

									<p><br /></p>

									<p><br /></p>

										<span class="pull-right print_matrix pointer   tooltip-enabled" title="" data-html="true" data-placement="left" data-original-title="<center> Set scale to 33% on more settings during print preview.</center>"  ><strong>Print <i class="fa fa-print" aria-hidden="true"></i></strong></span>
										<h4 id="responsibility_matrix">
											<strong>Responsibility Matrix</strong></h4>
											<span><em><strong  >Click the user profile photo to toggle the Direct Reports</strong></em></span>
										 

										

										<hr style="margin: 5px 0;">
<div id="" class="matrix_area_pos">
<div id="" class="page_print"> 
<style type="text/css">
	
@media print { 
	.blanks{
		width: 75px;
	}

	.box-area{
		margin:5px 0px;
	}

	.info_btn_lnk{
		display: none;
	}

	hr{
		background-color: #fff;
		border-color: #fff;
	} 
 
 


<?php foreach ($all_focus_company as $key => $value): ?>
											
										

												.user_<?php echo $value->company_id; ?>_comp_group{
													border: 3px solid #<?php echo $color_group[$value->company_id]; ?>;
												}

												.user_<?php echo $value->company_id; ?>_comp_group + p strong{
													background: #<?php echo $color_group[$value->company_id]; ?>;
													color: #fff;
													padding: 3px 6px;
													border-radius: 6px;
												}


												.user_<?php echo $value->company_id; ?>_comp_group + p + p, .user_<?php echo $value->company_id; ?>_comp_group + p + p a{
													color: #<?php echo $color_group[$value->company_id]; ?>;
													font-weight: bold;
													    margin-top: -5px;
												}



 

			.wid-type-<?php echo $value->company_id; ?>_comp_group .widg-head{
				opacity: 0.5;
				background: #fff !important;

				color: #<?php echo $color_group[$value->company_id]; ?> !important;
				padding-left: 20px;
			}

			.wid-type-<?php echo $value->company_id; ?>_comp_group{
																	background: #<?php echo $color_group[$value->company_id]; ?> !important;
 
			}
 


										<?php endforeach; ?>











}
</style>




										<?php $this->users->loop_user_supervisor(); ?>
										</div>

</div>
									</div>
					



							</div>
						</div>
					</div>					
<?php /*
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
					*/ ?>
					
				</div>				
			</div>
		</div>
	</div>
</div>


<div id="" class="print_out"></div>

<script type="text/javascript">

var btn_click_counter = 0;

	$('.user_dir').click(function(){
		var id_box = $(this).attr('id');
		var id_arr = id_box.split('_');
		var pm_set_id = id_arr[1];



		if(btn_click_counter%2 == 0){



			$('.box-area').hide();


			//	$('.default_blank').hide();
	 

			setTimeout(function(){

			$('#'+id_box).parent().parent().parent().show(); 

			$('#'+id_box).parent().parent().parent().children().show(); 
			// 	$('#'+id_box).prev().hide();
				$('.direp_'+pm_set_id).show();   
			//	$('.blanks.direp_3').show();

				//$('#'+id_box).children().show(); 

			}, 100);



		}else{

			$('.box-area').show();
		}

		btn_click_counter++;
	});



	$('.print_matrix').click(function(){


		var contents = $(".matrix_area_pos").html();
		var frame1 = $('<iframe />');
		frame1[0].name = "frame1";
		frame1.css({ "position": "absolute", "top": "-1000000px" });
		$("body").append(frame1);
		var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
		frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html><head><title>Responsibility Matrix</title>');
        frameDoc.document.write('</head><body>');
        //Append the external CSS file.
        frameDoc.document.write('<link href="<?php echo base_url(); ?>css/print.css" rel="stylesheet" type="text/css" />');
        //Append the DIV contents.
        frameDoc.document.write('<h1 style="font-size:20px;">Responsibility Matrix</h1><hr style="background-color: #000; border-color: #000; "/><br />');
        frameDoc.document.write(contents);
        frameDoc.document.write('</body></html>');
        frameDoc.document.close();
        setTimeout(function () {
        	window.frames["frame1"].focus();
        	window.frames["frame1"].print();
        	frame1.remove();
        }, 500);


	});
	
</script>
 

<?php $this->bulletin_board->list_latest_post(); ?>
<?php $this->load->view('assets/logout-modal'); ?>