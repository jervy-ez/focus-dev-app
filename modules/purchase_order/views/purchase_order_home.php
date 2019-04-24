<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>
<?php $this->load->module('projects'); ?>
<?php $this->load->module('purchase_order'); ?>
<?php $this->load->model('purchase_order_m'); ?>
<?php $this->load->module('bulletin_board'); ?>
<!-- title bar -->



<?php $this->load->module('dashboard'); ?>
<?php $this->load->model('dashboard_m'); ?>


<div class="container-fluid head-control">
	<div class="container-fluid">
		<div class="row">

			<div class="col-md-6 col-sm-4 col-xs-12 pull-left">
				<header class="page-header">
					<h3><?php $datestring = "%l, %F %d, %Y"; $time = time(); //use time() for timestamp  ?>
						<?php echo $screen; ?><br><small><?php echo mdate($datestring, $time); #echo date("l, F d, Y"); ?></small>
					</h3>
				</header>
			</div>

			<div class="page-nav-options col-md-6 col-sm-8 col-xs-12 pull-right hidden-xs">
				<ul class="nav nav-tabs navbar-right">
					<li>
						<a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Home</a>
					</li>
					<li>
						<a class="btn-small sb-open-right"><i class="fa fa-file-text-o"></i> Project Comments</a>
					</li>
					<li>
						<a href="#" class="btn-small btn-primary" data-toggle="modal" data-target="#po_filter_modal"><i class="fa fa-print"></i> PO Report</a>
					</li>

					<?php if( $this->session->userdata('purchase_orders') > 1 || $this->session->userdata('is_admin') == 1 ): ?>

						<li>
							<a href="<?php echo base_url(); ?>reports/myob_names"><i class="fa fa-print"></i> MYOB-Company Names</a>
						</li> 

					<?php endif; ?>
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
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<div class="left-section-box po">

								<?php if(isset($error)): ?>
									<div class="pad-10 no-pad-t">
										<div class="border-less-box alert alert-danger fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>Oh snap! You got an error!</h4>
											<?php echo $error;?>
										</div>
									</div>
								<?php endif; ?>

								<?php if(@$this->session->flashdata('success_add')): ?>
									<div class="pad-10 no-pad-t">
										<div class="border-less-box alert alert-success fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>Cheers!</h4>
											<?php echo $this->session->flashdata('success_add');?>
										</div>
									</div>
								<?php endif; ?>

								<?php if(@$this->session->flashdata('success_remove')): ?>
									<div class="pad-10 no-pad-t">
										<div class="border-less-box alert alert-danger fade in">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4>I hope you did the right thing.</h4>
											<?php echo $this->session->flashdata('success_remove');?>
										</div>
									</div>
								<?php endif; ?>	



								<?php
									if($this->session->userdata('user_role_id') == 3){
										$po_rev_prime = 'active';
										$def_prime = '';
									//	project manager


										echo '<script type="text/javascript">
										setTimeout(function(){
											$("#po_review select.po_rev_pm option").remove();
											$("#po_review select.po_rev_pm").append("<option>'.$this->session->userdata('user_first_name').' '.$this->session->userdata('user_last_name').'</option>");
											$("#po_review select.po_rev_pm").val("'.$this->session->userdata('user_first_name').' '.$this->session->userdata('user_last_name').'").trigger("change");

										},500);</script>';


									}elseif($this->session->userdata('user_role_id') == 20){
										$po_rev_prime = 'active';
										$def_prime = '';
										// account manager

										echo '<script type="text/javascript">
										setTimeout(function(){
											$("#po_review select.po_rev_pm option").remove();
											$("#po_review select.po_rev_pm").append("<option>'.$this->session->userdata('user_first_name').' '.$this->session->userdata('user_last_name').'</option>");
											$("#po_review select.po_rev_pm").val("'.$this->session->userdata('user_first_name').' '.$this->session->userdata('user_last_name').'").trigger("change");

										},500);</script>';

									}elseif($this->session->userdata('user_id') == 20){
										$po_rev_prime = 'active';
										$def_prime = '';
										//echo "<p>GF</p>"; gary ford

										echo '<script type="text/javascript">
										setTimeout(function(){
											$("#po_review select.po_rev_pm option").remove();
											$("#po_review select.po_rev_pm").append("<option>'.$this->session->userdata('user_first_name').' '.$this->session->userdata('user_last_name').'</option>");
											$("#po_review select.po_rev_pm").val("'.$this->session->userdata('user_first_name').' '.$this->session->userdata('user_last_name').'").trigger("change");

										},500);</script>';
									}elseif($this->session->userdata('user_role_id') == 7){
										$po_rev_prime = 'active';
										$def_prime = '';
										// maintenance

										echo '<script type="text/javascript">
										setTimeout(function(){
											$("#po_review select.po_rev_pm option").remove();
											$("#po_review select.po_rev_pm").append("<option value=\"Maintenance Manager\">Maintenance Manager</option>");
											$("#po_review select.po_rev_pm").val("Maintenance Manager").trigger("change");

										},500);</script>';
									}elseif($this->session->userdata('user_role_id') == 2){
										//echo "<p>PA</p>";
										$po_rev_prime = 'active';
										$def_prime = '';

										$user_id = $this->session->userdata('user_id');
										$pa_data = $this->dashboard_m->fetch_pa_assignment($user_id);
										$assignment = array_shift($pa_data->result_array());
										$pa_pms = explode(',',$assignment['project_manager_ids']);

										echo '<script type="text/javascript">
										setTimeout(function(){
											$("#po_review select.po_rev_pm option").remove();';
											

											foreach ($users->result_array() as $row){
												if($row['user_role_id']==3 || $row['user_role_id']==20):

													if (in_array($row['user_id'], $pa_pms)){ 
														echo '$("#po_review select.po_rev_pm").append("<option value=\"'.$row['user_first_name'].' '.$row['user_last_name'].'\">'.$row['user_first_name'].' '.$row['user_last_name'].'</option>");';														
													}

													if($row['user_id'] == $assignment['project_manager_primary_id']){
														echo '$("#po_review select.po_rev_pm").val("'.$row['user_first_name'].' '.$row['user_last_name'].'").trigger("change");';
													}
												endif;

											}
										echo '},500);</script>';         

									}else{
										$po_rev_prime = '';
										$def_prime = 'active';
									//	echo "<p>deff</p>";
									}
								?>




								<?php
									if(isset($_GET['po_rev']) && $_GET['po_rev'] != ''){
										$po_rev_prime = 'active';
										$def_prime = '';
									}else{
										$po_rev_prime = '';
										$def_prime = 'active';
									}
								?>


								<div class="row clearfix">

										<div class="col-lg-4 col-md-12">
											<div class="box-head pad-left-15 clearfix">
												<label><?php echo $screen; ?> List</label>
												<div id="aread_test"></div>
											</div>
										</div>
										
										<div class="col-lg-8 col-md-12">
											<div class="pad-left-15 pad-right-10 clearfix box-tabs">
												<div class="pull-right">
													<div class="input-group  pull-right" style="width:350px;margin-right: -5px;">
														<span class="input-group-addon">PO Number</span>

														<input type="text" class="form-control" placeholder="Search PO Number" id="po_number_srch_rec">
														<span class="input-group-addon btn btn-info srch_btn_po_rec" id="">Search</span>
													</div>
												</div>


												<ul id="myTab" class="nav nav-tabs pull-right">

													<?php if($this->session->userdata('is_admin') == 1): ?>
														<li class="<?php echo $po_rev_prime; ?>">
															<a class="po_review_lnk" href="#po_review" data-toggle="tab"><i class="fa fa-file-text-o fa-lg"></i> PO Review</a>
														</li>
													<?php endif; ?>
													<li class="<?php echo $def_prime; ?>">
														<a href="#outstanding" data-toggle="tab"><i class="fa fa-level-up fa-lg"></i> Outstanding</a>
													</li>
													<li class="" >
														<a href="#reconciled" data-toggle="tab" id="search_reconciled_btn_tab"><i class="fa fa-check-square-o fa-lg"></i> Search Reconciled</a>
													</li>
													<li class="">
														<a href="" data-toggle="modal" data-target="#po_date_filter_modal"><i class="fa fa-filter fa-lg"></i> Filter by Date</a>
													</li>
												</ul>
											</div>
										</div>

								</div>





								<div class="box-area">

									<div class="box-tabs m-bottom-15">
										<div class="tab-content">



								<div id="po_review" class="tab-pane clearfix <?php echo $po_rev_prime; ?>">
												<div class="m-bottom-15 clearfix">


													<div class="box-area po-area">
														<table id="po_rev_table" class="table table-bordered table_adjusted" cellspacing="0" width="100%">

															<thead><tr><th>CPO Date</th><th>PO Number</th><th>Project</th><th>Contractor</th><th>Price w/ GST</th><th>Estimate w/ GST</th><th>Finish Date</th><th>Personel</th><th>Action</th> <th>finish_date_tmpstp_date</th></tr></thead>
															


															<tbody>

																<?php

																$total_price_exgst = 0;
																$total_price_incgst = 0;

																$today_date = new DateTime(date("Y-m-d"));
																$today_date_timestamp = $today_date->format('U'); 

																$date_month_today = '01'.date('/m/Y');
																$date_next_month = '01'.date("/m/Y", strtotime("today +1 Month"));
																$has_been_reviewed = 0;
 
																	foreach ($po_list->result_array() as $row){


																		$q_po_reviewer = $this->purchase_order_m->check_po_reviewer($row['works_id'],$date_month_today,$date_next_month);

																		if($q_po_reviewer->num_rows > 0){
																			$po_reviewer_data = array_shift($q_po_reviewer->result());

																			$po_marker = 'posted_rev';
																			$po_rv_estimate = floatval($po_reviewer_data->estimate_price_wgst);
																			$po_rv_action = $po_reviewer_data->action;
																			$has_been_reviewed = 1;
																		}else{
																			$po_marker = '';
																			$po_rv_estimate = '';
																			$po_rv_action = '';
																			$has_been_reviewed = 0;
																		}



																		$srchDate = date_format(date_create_from_format('d/m/Y', $row['date_site_finish']), 'Y-m-d');
																		$date_control = $today_date->diff(new DateTime($srchDate));

																		$project_month_ended = strtotime($srchDate. ' + 30 days');

																		if($date_control->days > 29 && $project_month_ended < $today_date_timestamp ){

																		$comp_insurance_status = $this->purchase_order->check_contractor_insurance($row['company_client_id']);

																		$balance_a = $this->purchase_order->check_balance_po($row['works_id']);

                                    $prj_defaults = $this->projects->display_project_applied_defaults($row['project_id']);

                                    $inc_gst_price = $this->purchase_order->ext_to_inc_gst($row['price'],$prj_defaults['admin_gst_rate']);



echo '<tr class="'.$po_marker.'"><td>'.$row['work_cpo_date'].'</td><td>'.$row['works_id'].'</td><td><a href="'.base_url().'projects/view/'.$row['project_id'].'" target="_blank"  >'.$row['project_id'].' - '.$row['project_name'].'<a></td><td>'.$row['contractor_name'].'</td><td><span class="inc-gst">'.number_format($inc_gst_price,2).'</span> </td>';



if( $has_been_reviewed == 1){
	echo '<td> <strong>'.number_format($po_rv_estimate,2).'</strong> </td>';
}else{



	if($inc_gst_price > 1){
//echo '<td> <input disabled class="form-control input-sm estimate_price_wgst" placeholder="$ Estimate" value="'.number_format($inc_gst_price,2).'" /> </td>';


		echo '<td> <strong><input class="hide estimate_price_wgst" value="'.$inc_gst_price.'" /> '.number_format($inc_gst_price,2).'</strong> </td>';
	}else{


		echo '<td> <input class="form-control input-sm estimate_price_wgst" placeholder="$ Estimate w/ GST" value="" /> </td>';

	}


}






echo '<td>'.$row['date_site_finish'].'</td> <td>'.$row['user_first_name'].' '.$row['user_last_name'].'</td> 
<td>';


if( $has_been_reviewed == 1){

	
	

	if($po_rv_action == 1){
		echo '<strong>Request for Removal</strong>';
	}

	if($po_rv_action == 2){
		echo '<strong>Waiting for Invoice</strong>';
	}


}else{


echo '<select class="form-control input-sm po_action_set" id="'.$row['works_id'].'_'.$row['project_id'].'_'.$row['project_manager_id'].'_'.$row['project_admin_id'].'" >
	<option value="0" selected="" disabled="">Pls Select an Action</option>
	<option value="1">Request for Removal</option>
	<option value="2">Waiting for Invoice</option>
</select>';
}
			
			echo'</td>

<td>'.$row['workfinish_tmpstp_date'].'</td>
</tr>';


 




/*

																		echo '<tr class="reviewed" id="'.$prj_defaults['admin_gst_rate'].'"><td><a href="#" data-toggle="modal" data-target="#invoice_po_modal" data-backdrop="static" onclick="select_po_item(\''.$row['works_id'].'-'.$row['project_id'].'\');" id="'.$row['works_id'].'-'.$row['project_id'].'" class="select_po_item">'.$row['works_id'].'</a></td><td><a href="'.base_url().'projects/view/'.$row['project_id'].'" >'.$row['project_id'].'</a></td><td>'.$row['work_cpo_date'].'</td><td><a href="'.base_url().'works/update_work_details/'.$row['project_id'].'/'.$row['works_id'].'">';

																		if($row['contractor_type']==2){

																			if($row['job_sub_cat']=='Other'){
																				echo $row['other_work_desc'];
																			}else{ 
                                        echo $row['job_sub_cat'];
																			}

																		}elseif($row['contractor_type']==3){
																			if($row['supplier_cat_name']=='Other'){
																				echo $row['other_work_desc'];
																			}else{
																				echo $row['supplier_cat_name'];
																			}
																		}else{ }


																		$total_price_exgst = $row['price'] + $total_price_exgst;

																		$inc_gst_price = $this->purchase_order->ext_to_inc_gst($row['price'],$prj_defaults['admin_gst_rate']);

																		$total_price_incgst = $inc_gst_price + $total_price_incgst;

																		echo '</a></td><td id="'.$comp_insurance_status.'"><span class="'.$comp_insurance_status.'">'.($comp_insurance_status == 'red_bad' ? '<a class="tooltip-test" title="Incomplete Insurance">' : '').''.$row['contractor_name'].''.($comp_insurance_status == 'red_bad' ? '</a>' : '').'</span></td><td>'.$row['project_name'].'</td><td>'.$row['job_date'].'</td><td>'.$row['client_name'].'</td><td>'.$row['user_first_name'].' '.$row['user_last_name'].'</td><td><span class="ex-gst">'.number_format($row['price'],2).'</span><br /><span class="hide">-</span><span class="inc-gst">'.number_format($inc_gst_price,2).'</span></td>';
                                    echo '<td><span class="ex-gst">'.number_format($balance_a,2).'</span><br /><span class="hide">-</span><span class="inc-gst">'.number_format($this->purchase_order->ext_to_inc_gst($balance_a,$prj_defaults['admin_gst_rate']),2).'</span></td>';
                                    echo '<td>'.$row['workfinish_tmpstp_date'].'</td>';
                                    echo '</tr>';*/
																  }
																  }
																?>

                                <?php  
                                  foreach ($work_joinery_list->result_array() as $row_j){


																		$srchDate = date_format(date_create_from_format('d/m/Y', $row_j['date_site_finish']), 'Y-m-d');
																		$date_control_j = $today_date->diff(new DateTime($srchDate));
																		$project_month_ended = strtotime($srchDate. ' + 30 days');


																		if($date_control_j->days > 29 && $project_month_ended < $today_date_timestamp ){



																		$comp_insurance_status = $this->purchase_order->check_contractor_insurance($row_j['company_client_id']);

                                  	$total_price_exgst = $row_j['price'] + $total_price_exgst;
                                    $j_prj_defaults = $this->projects->display_project_applied_defaults($row_j['project_id']);


                                  	$inc_gst_price_j = $this->purchase_order->ext_to_inc_gst($row_j['price'],$j_prj_defaults['admin_gst_rate']);

                                  	$total_price_incgst = $inc_gst_price_j + $total_price_incgst;


									$balance_b = $this->purchase_order->check_balance_po($row_j['works_id'],$row_j['work_joinery_id']);

                                    echo '<tr class="reviewed" id="'.$j_prj_defaults['admin_gst_rate'].'"><td><a href="#" data-toggle="modal" data-target="#invoice_po_modal" data-backdrop="static" onclick="select_po_item(\''.$row_j['works_id'].'-'.$row_j['work_joinery_id'].'-'.$row_j['project_id'].'\');" id="'.$row_j['works_id'].'-'.$row_j['work_joinery_id'].'-'.$row_j['project_id'].'" class="select_po_item">'.$row_j['works_id'].'-'.$row_j['work_joinery_id'].'</a></td><td><a href="'.base_url().'projects/view/'.$row_j['project_id'].'" >'.$row_j['project_id'].'</a></td><td>'.$row_j['work_cpo_date'].'</td><td><a href="'.base_url().'works/update_work_details/'.$row_j['project_id'].'/'.$row_j['works_id'].'">';
                                    echo $row_j['joinery_name'];
                                    echo '</a></td><td id="'.$comp_insurance_status.'"><span class="'.$comp_insurance_status.'">'.$row_j['contractor_name'].'</span></td><td>'.$row_j['project_name'].'</td><td>'.$row_j['job_date'].'</td><td>'.$row_j['client_name'].'</td><td>'.$row_j['user_first_name'].' '.$row_j['user_last_name'].'</td><td><span class="ex-gst">'.number_format($row_j['price'],2).'</span><br /><span class="hide">-</span><span class="inc-gst">'.number_format($inc_gst_price_j,2).'</span></td>';
                                    echo '<td><span class="ex-gst">'.number_format($balance_b,2).'</span><br /><span class="hide">-</span><span class="inc-gst">'.number_format($this->purchase_order->ext_to_inc_gst($balance_b,$j_prj_defaults['admin_gst_rate']),2).'</span></td>';
                                    echo '<td>'.$row_j['workfinish_tmpstp_date'].'</td>';
                                    echo '</tr>';
                                  } 
                                  } 
                               ?>

															</tbody>

														</table>

													</div>



												</div>
											</div>





											<div class="tab-pane  clearfix <?php echo $def_prime; ?>" id="outstanding">
												<div class="m-bottom-15 clearfix">


													<div class="box-area po-area">
														<table id="po_table" class="table table_adjusted  table-striped table-bordered" cellspacing="0" width="100%">
															<thead><tr><th>PO Number</th><th>Project Number</th><th>CPO Date</th><th>Job Description</th><th>Contractor</th><th>Project Name</th><th>Job Date</th><th>Client</th><th>Project Manager</th><th>Price</th><th>Balance</th><th>cpo_tmpstp_date</th></tr></thead>
															<tbody>

																<?php

																$total_price_exgst = 0;
																$total_price_incgst = 0;

																	foreach ($po_list->result_array() as $row){

																		$comp_insurance_status = $this->purchase_order->check_contractor_insurance($row['company_client_id']);

																		$balance_a = $this->purchase_order->check_balance_po($row['works_id']);

                                    $prj_defaults = $this->projects->display_project_applied_defaults($row['project_id']);

																		echo '<tr id="'.$prj_defaults['admin_gst_rate'].'"><td><a href="#" data-toggle="modal" data-target="#invoice_po_modal" data-backdrop="static" onclick="select_po_item(\''.$row['works_id'].'-'.$row['project_id'].'\');" id="'.$row['works_id'].'-'.$row['project_id'].'" class="select_po_item">'.$row['works_id'].'</a></td><td><a href="'.base_url().'projects/view/'.$row['project_id'].'" >'.$row['project_id'].'</a></td><td>'.$row['work_cpo_date'].'</td><td><a href="'.base_url().'works/update_work_details/'.$row['project_id'].'/'.$row['works_id'].'">';

																		if($row['contractor_type']==2){

																			if($row['job_sub_cat']=='Other'){
																				echo $row['other_work_desc'];
																			}else{ 
                                        echo $row['job_sub_cat'];
																			}

																		}elseif($row['contractor_type']==3){
																			if($row['supplier_cat_name']=='Other'){
																				echo $row['other_work_desc'];
																			}else{
																				echo $row['supplier_cat_name'];
																			}
																		}else{ }


																		$total_price_exgst = $row['price'] + $total_price_exgst;

																		$inc_gst_price = $this->purchase_order->ext_to_inc_gst($row['price'],$prj_defaults['admin_gst_rate']);

																		$total_price_incgst = $inc_gst_price + $total_price_incgst;

																		echo '</a></td><td id="'.$comp_insurance_status.'"><span class="'.$comp_insurance_status.'">'.($comp_insurance_status == 'red_bad' ? '<a class="tooltip-test" title="Incomplete Insurance">' : '').''.$row['contractor_name'].''.($comp_insurance_status == 'red_bad' ? '</a>' : '').'</span></td><td>'.$row['project_name'].'</td><td>'.$row['job_date'].'</td><td>'.$row['client_name'].'</td><td>'.$row['user_first_name'].' '.$row['user_last_name'].'</td><td><span class="ex-gst">'.number_format($row['price'],2).'</span><br /><span class="hide">-</span><span class="inc-gst">'.number_format($inc_gst_price,2).'</span></td>';
                                    echo '<td><span class="ex-gst">'.number_format($balance_a,2).'</span><br /><span class="hide">-</span><span class="inc-gst">'.number_format($this->purchase_order->ext_to_inc_gst($balance_a,$prj_defaults['admin_gst_rate']),2).'</span></td>';
                                    echo '<td>'.$row['cpo_tmpstp_date'].'</td>';
                                    echo '</tr>';
																  }
																?>

                                <?php  
                                  foreach ($work_joinery_list->result_array() as $row_j){


																		$comp_insurance_status = $this->purchase_order->check_contractor_insurance($row_j['company_client_id']);

                                  	$total_price_exgst = $row_j['price'] + $total_price_exgst;
                                    $j_prj_defaults = $this->projects->display_project_applied_defaults($row_j['project_id']);


                                  	$inc_gst_price_j = $this->purchase_order->ext_to_inc_gst($row_j['price'],$j_prj_defaults['admin_gst_rate']);

                                  	$total_price_incgst = $inc_gst_price_j + $total_price_incgst;


									$balance_b = $this->purchase_order->check_balance_po($row_j['works_id'],$row_j['work_joinery_id']);

                                    echo '<tr id="'.$j_prj_defaults['admin_gst_rate'].'"><td><a href="#" data-toggle="modal" data-target="#invoice_po_modal" data-backdrop="static" onclick="select_po_item(\''.$row_j['works_id'].'-'.$row_j['work_joinery_id'].'-'.$row_j['project_id'].'\');" id="'.$row_j['works_id'].'-'.$row_j['work_joinery_id'].'-'.$row_j['project_id'].'" class="select_po_item">'.$row_j['works_id'].'-'.$row_j['work_joinery_id'].'</a></td><td><a href="'.base_url().'projects/view/'.$row_j['project_id'].'" >'.$row_j['project_id'].'</a></td><td>'.$row_j['work_cpo_date'].'</td><td><a href="'.base_url().'works/update_work_details/'.$row_j['project_id'].'/'.$row_j['works_id'].'">';
                                    echo $row_j['joinery_name'];
                                    echo '</a></td><td id="'.$comp_insurance_status.'"><span class="'.$comp_insurance_status.'">'.$row_j['contractor_name'].'</span></td><td>'.$row_j['project_name'].'</td><td>'.$row_j['job_date'].'</td><td>'.$row_j['client_name'].'</td><td>'.$row_j['user_first_name'].' '.$row_j['user_last_name'].'</td><td><span class="ex-gst">'.number_format($row_j['price'],2).'</span><br /><span class="hide">-</span><span class="inc-gst">'.number_format($inc_gst_price_j,2).'</span></td>';
                                    echo '<td><span class="ex-gst">'.number_format($balance_b,2).'</span><br /><span class="hide">-</span><span class="inc-gst">'.number_format($this->purchase_order->ext_to_inc_gst($balance_b,$j_prj_defaults['admin_gst_rate']),2).'</span></td>';
                                    echo '<td>'.$row_j['cpo_tmpstp_date'].'</td>';
                                    echo '</tr>';
                                  } 
                               ?>

															</tbody>
														</table>
													</div>



												</div>
											</div>
											<div class="tab-pane  clearfix" id="reconciled">

												<div class="m-bottom-15 clearfix">

												<div class="box-area po-area">

														<div id="" class="">
														</div>

														<div class="row m-3 m-bottom-10"><div class="col-xs-6"><div class="" id="">
	
															<p>The reconciled PO list is moved, You can click <a class="btn btn-xs btn-info"  href="<?php echo base_url(); ?>purchase_order/reconciled">Here</a> to view full reconciled list.</p>
</div></div>



<div class="col-xs-6"><div id="" class="">





</div></div></div>

<style type="text/css">
	.red_bad a {
		color: red;
		cursor: pointer;
		text-decoration: none;
		font-weight: bold;
	}

	.blue_ok{
		/*color: blue;
		font-weight: bold;*/
	}


	.table_adjusted{
		font-size: 13px !important;
	}
</style>





														<table class="table table-striped table-bordered table_adjusted " cellspacing="0" width="100%">
															<thead><tr><th>PO Number</th><th>Project Number</th><th>CPO Date</th><th>Job Description</th><th>Contractor</th><th>Project Name</th><th>Reconciled Date</th><th>Client</th><th>Project Manager</th><th>Price</th><th>Balance</th></tr></thead>
															<tbody class="dynamic_search_result_reconciled_list">
																<tr><td colspan="11">Please input your PO number in the search field.</td></tr>
															</tbody>
														</table> 

													</div>
												</div>
											</div>
										</div>
									</div>				
								</div>
							</div>
						</div>
						
					</div>				
				</div>	
			
		</div>
	</div>
</div>



<style type="text/css">
	

	table tr.posted_rev{
		background-color: #ffa9e6;                      
	}

	table tr.posted_rev td, table tr.posted_rev td a{
		color: #000 !important;
	}

	table tr.posted_rev input.estimate_price_wgst, table tr.posted_rev select.po_action_set{
		pointer-events: none;
	}



	table#po_rev_table tr:hover, table#po_rev_table tr.prj_rvw_rw:hover td, table#po_rev_table tr.prj_rvw_rw:hover a {
		background-color: #84f1ff;
		color: #000 !important;
	}

	table#po_rev_table tr td {
		    vertical-align: inherit;
	}

	table#po_rev_table .has_error{
		border-color: red;
		color: red;
	}

	table#po_rev_table thead, table#po_table thead{
		background-color: #f1f1f1;
		font-size: 13px;
	}

</style>

<div class="modal fade" id="confirm_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog  modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Please Confirm</h4>
				<input type="hidden" class="selected_action" value="0">
				<input type="hidden" class="price_estimate" value="0">
				<input type="hidden" class="po_data" value="0">
			</div>
			<div class="modal-body clearfix pad-10">
				<center><h4>Are you sure with your review?</h4></center>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left process_cancel" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-success pull-right process_continue" data-dismiss="modal">Yes, Continue</button>		
			</div>
		</div>
	</div>
</div>



<script type="text/javascript">


	$('.process_continue').click(function(){

	var estimate_price_wgst = $('input.price_estimate').val();
	var po_action_set = $('input.selected_action').val();
	var po_data = $('input.po_data').val();

 

	if(!estimate_price_wgst){
		estimate_price_wgst = '';
	}

	$('select.po_action_set').removeClass('has_error');
	$('input.estimate_price_wgst').removeClass('has_error');




	if(estimate_price_wgst < 1 && po_action_set == 2){
		$('select#'+po_data).parent().parent().find('input.estimate_price_wgst').addClass('has_error');
		$('select#'+po_data).val(0);
		alert('Please set estimate amount with GST.');

	}else{
		$('select#'+po_data).parent().parent().addClass('posted_rev');

		$('#loading_modal').modal({"backdrop": "static", "show" : true} );
		setTimeout(function(){
			$('#loading_modal').modal('hide');
		},2000);

 
      	var estimate_parsed = estimate_price_wgst;

    //  	$('select#'+estimate_price_wgst).parent().parent().find('input.estimate_price_wgst').prop('disabled', true);

    if(po_action_set == 1){
    	var action_selected = 'Request for Removal';
    }

    if(po_action_set == 2){
    	var action_selected = 'Waiting for Invoice';

    }


      	$('select#'+po_data).prop('disabled', true).hide().replaceWith('<strong>'+action_selected+'</strong>');
      	$('select#'+po_data).parent().parent().find('input.estimate_price_wgst').prop('disabled', true).hide().replaceWith('<strong>'+estimate_price_wgst+'</strong>');





     // alert(estimate_price_wgst);
	//	$(this).parent().parent().find('select.po_action_set').prop('disabled', true);
     // 	$(this).parent().parent().find('input.estimate_price_wgst').prop('disabled', true);
    //  	$(this).hide();

      	var po_data = po_data+'_'+estimate_parsed+'_'+po_action_set;

     	 ajax_data(po_data,'purchase_order/po_review_process','');
	}

//	





	});


$('.process_cancel').click(function(){
	var selection_id = $('input.po_data').val();
	$('input.price_estimate').val('0');
	$('input.po_data').val('0');
	$('select#'+selection_id).val('0')
	$('input.selected_action').val('0');
});


	$('select.po_action_set').on("change", function(e) {
		$('#confirm_modal').modal({"backdrop": "static", "show" : true} );
		var data_po = $(this).attr('id');
		var data_action = $(this).val();

		var estimate_price_wgst = $(this).parent().parent().find('input.estimate_price_wgst').removeClass('has_error').val();

		$('input.selected_action').val(data_action);
		$('input.price_estimate').val(estimate_price_wgst);
		$('input.po_data').val(data_po);



	});

 
	


  $('input#po_number_srch_rec').on("keyup", function(e) { //number_only number only
    var po_number_srch_rec = $(this).val();
    po_number_srch_rec = po_number_srch_rec.replace(/[^\d]/g,'');
    $(this).val(po_number_srch_rec);

    if($(this).val().length < 1 ){
    	var companyTable = $('#po_table').dataTable();
        companyTable.fnFilter('','0'); 

        $('.dynamic_search_result_reconciled_list').html('<tr><td colspan="11">Please input your PO number in the search field.</td></tr>');
    }


  });

  $('.srch_btn_po_rec').click(function() {
    $('select#rec_outstading_pm').val('');
    var po_number_srch_rec = $('input#po_number_srch_rec').val();
    if(po_number_srch_rec == ''){
      $('input#po_number_srch_rec').addClass('has-error');
    }else{
      $('input#po_number_srch_rec').removeClass('has-error');

      $('#loading_modal').modal({"backdrop": "static", "show" : true} );
      setTimeout(function(){
        $('#loading_modal').modal('hide');
      },1000);

		$('input#po_number_srch_rec').focus();

      ajax_data(po_number_srch_rec,'purchase_order/get_reconciled_result','.dynamic_search_result_reconciled_list');



 
    var companyTable = $('#po_table').dataTable();
    //alert($(this).val());   
        companyTable.fnFilter(po_number_srch_rec,'0'); 




    }
  });

 



</script>

<!-- Modal -->
<div class="modal fade" id="invoice_po_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
  <form method="post" action="<?php base_url(); ?>purchase_order/insert_work_invoice">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Purchase Order</h4>
      </div>
      <div class="modal-body">
      	<div class="container-fluid">
      		<div class="row">

      			<div class="col-sm-12 border-bottom">
      				<div class="clearfix col-sm-6">
      					<p>PO Number: <strong class="po_number_mod">00/00</strong></p>
      				</div>
      				<div class="clearfix col-sm-6">
      					<p>Description: <strong class="po_desc_mod">Xxxx</strong></p>
      				</div>
      				<div class="clearfix col-sm-6">
      					<p>Total: <strong class="po_total_mod">$00.00</strong> ex-gst</p>
      				</div>
      				<div class="clearfix col-sm-6">
      					<p>Balance Ext GST: <strong class="po_balance_mod">$0.00</strong></p>
      				</div>  				
      			</div>

            <div class="po_error"></div>


      			<div class="col-sm-6">
      				<div class="clearfix m-top-15">
      					<label for="po_date_value" class="col-sm-3 control-label text-left m-top-10" style="font-weight: normal;">Date*</label>
      					<div class="col-sm-9">
      						<div class="input-group">
      							<span class="input-group-addon" id=""><i class="fa fa-calendar"></i></span>
      							<input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control datepicker" id="po_date_value" tabindex="1" name="po_date_value" value="<?php echo date("d/m/Y"); ?>" >
      						</div>     						
      						
      					</div>
      				</div>
      			</div>

            <input type="hidden" name="po_number_item" id="po_number_item" class="po_number_item">
            <input type="hidden" name="po_actual_balance" id="po_actual_balance" class="po_actual_balance">
            <input type="hidden" name="po_gst" id="po_gst" class="po_gst">
            <input type="hidden" name="po_project_id" id="po_project_id" class="po_project_id">


      			<div class="col-sm-6">
      				<div class="clearfix m-top-15">
      					<label for="po_amount_value" class="col-sm-3 control-label text-left m-top-10" style="font-weight: normal;">Amount*</label>
      					<div class="col-sm-9">
      						<div class="input-group m-bottom-10">
      							<span class="input-group-addon" id="">$</span>
      							<input type="text" placeholder="Amount" class="form-control number_format" onkeyup="" id="po_amount_value" name="po_amount_value" value="" tabindex="2">
                    <span class="input-group-addon" id="">ex-gst</span>
      						</div>
      					</div>
      				</div>
      			</div>

      			<div class="col-sm-6">
      				<div class="clearfix">
      					<label for="po_reference_value" class="col-sm-4 control-label text-left m-top-10" style="font-weight: normal;">Invoice No*</label>
      					<div class="col-sm-8">
      						<input type="text" placeholder="Invoice Number" class="form-control" id="po_reference_value" name="po_reference_value" value="" tabindex="3">
      					</div>
      				</div>
      			</div>

      			<div class="col-sm-6">
              <div class="clearfix">
                <label for="po_amount_value_inc_gst" class="col-sm-3 control-label text-left" style="font-weight: normal;"></label>
                <div class="col-sm-9">
                  <div class="input-group m-bottom-10">
                    <span class="input-group-addon" id="">$</span>
                    <input type="text" placeholder="Amount" class="form-control number_format" id="po_amount_value_inc_gst" name="po_amount_value_inc_gst" value="" tabindex="2">
                    <span class="input-group-addon" id="">inc-gst</span>
                  </div>
                </div>
              </div>
            </div>

      			<div class="col-sm-12">
      				<div class="clearfix  m-top-10">
      					<label for="po_notes_value" class="col-sm-1 control-label text-left m-top-10" style="font-weight: normal;">Notes</label>
      					<div class="col-sm-11">
      						


                  <div class="input-group m-bottom-10">
                    <input type="text" placeholder="Notes" class="form-control" id="po_notes_value" name="po_notes_value" value="" tabindex="4">
                    <input type="text" class="hidden" disabled="disabled">
                    <span class="input-group-addon" id=""><i class="fa fa-exclamation-triangle"></i> Is Reconciled <input class="m-top-10" type="checkbox" name="is_reconciled" id="po_is_reconciled_value"></span>
                  </div>



      					</div>
      				</div>
      			</div>

      			<div class="col-sm-12 m-top-15">
      				<table class="table table-bordered">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Amount ext-gst</th>
                  <th>Invoice Number</th>
                  <th>Notes</th>
                </tr>
              </thead>
              <tbody class="po_history">                
              </tbody>
            </table>


            <?php if($this->session->userdata('is_admin') == 1): ?>
            	<div id="" class="pull-right btn btn-warning zero_payment">Zero Payment</div>
            <?php endif; ?>
            <p><span class="po_note_msg red_bad"></span></p>
      			</div>
 
      		</div>
      	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default po_cancel_values" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success po_set_values pull-right"><i class="fa fa-floppy-o"></i> Save</button>
        <input type="submit" class="hide submit_po_screen" name="submit_po">
      </div>
    </div>
    </form>
  </div>
</div>



<!-- modal -->






<!-- Modal -->
<div class="modal fade" id="po_filter_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Purchase Order Report</h4>
        <!-- <span> Note: <strong>State is required</strong>. The rest, if blank it selects all.</span> -->
      </div>
      <div class="modal-body clearfix pad-10">

        <div class="error_area"></div>

  


        <div class="input-group m-bottom-10">
          <span class="input-group-addon" id="">
            Focus Company*
          </span>
          <select class="form-control focus_company m-bottom-10" id="focus_company">
            <option value="">Select Focus Company</option>
            <?php
 
	            foreach ($all_focus_company->result_array() as $row){ 
	                echo '<option value="'.$row['company_name'].'|'.$row['company_id'].'" >'.$row['company_name'].'</option>';
	             
	            }
            ?>
          </select>
        </div>


        <div class="input-group m-bottom-10">
          <span class="input-group-addon" id="">
            Project Manager
          </span>
          <select class="form-control project_manager m-bottom-10" id="project_manager">
            <option value="All PM|">Select Project Manager</option>
            <?php
            foreach ($users->result_array() as $row){
              if($row['user_role_id']==3 || $row['user_role_id']==20):
                echo '<option value="'.$row['user_first_name'].' '.$row['user_last_name'].'|'.$row['user_id'].'" >'.$row['user_first_name'].' '.$row['user_last_name'].'</option>';
              endif;
            }
            ?>
          </select>
        </div>

        <div class="">
          <div class="input-group m-bottom-10">
            <span class="input-group-addon">
              Invoice Status
            </span>
             

            <select class="status_switcher form-control"  id="status_switcher" >
              <option value="0" selected='true'>Outstanding</option>
              <option value="1" >Reconciled</option>
            </select>

           <script type="text/javascript">


  $('select#status_switcher').change(function(){
    var status_switcher = $('select#status_switcher').val();

    if(status_switcher=='0'){
      $('.reconciled_date').hide();
    }else{
      $('.reconciled_date').show();
    }

    $('input.cpo_date_a').val('');
    $('input.cpo_date_b').val('');
    $('input.reconciled_date_a').val('');
    $('input.reconciled_date_b').val('');

 
  });



</script>

          </div>
        </div>


 



        <div id="" class="clearfix">   <hr class="clearfix" style="padding: 0;margin: 14px 0;">  </div>



        <div id="" class="clearfix row error_tag" style="padding: 0 5px;" >




        	<div class="col-md-6 col-sm-6 col-xs-12 clearfix cpo_date" style="" >
        		<div class="input-group m-bottom-10">
        			<span class="input-group-addon" id="">
        				CPO Date A
        			</span>         
        			<input type="text" class="form-control cpo_date_a" id="cpo_date_a" name="cpo_date_a" value="" placeholder="DD/MM/YYYY">
        		</div>
        	</div>


        	<div class="col-md-6 col-sm-6 col-xs-12 clearfix cpo_date">
        		<div class="input-group m-bottom-10">
        			<span class="input-group-addon" id="">
        				CPO Date B
        			</span>         
        			<input type="text" class="form-control cpo_date_b" id="cpo_date_b" name="cpo_date_b" value="" placeholder="DD/MM/YYYY">
        		</div>
        	</div>
        </div>


<script type="text/javascript">


$('#cpo_date_a').datetimepicker({ format: 'DD/MM/YYYY'});
$('#cpo_date_b').datetimepicker({
useCurrent: false, //Important! See issue #1075
format: 'DD/MM/YYYY'
});
$("#cpo_date_a").on("dp.change", function (e) {

$('#cpo_date_b').datetimepicker({
useCurrent: false, //Important! See issue #1075
format: 'DD/MM/YYYY'
});


});
$("#cpo_date_b").on("dp.change", function (e) {
$(this).data("DateTimePicker").minDate(e.date);
});


</script>



        <div id="" class="clearfix row reconciled_date error_tag" style="padding: 0 5px; display:none;" >

        	<div class="col-md-6 col-sm-6 col-xs-12 clearfix reconciled_date" >
        		<div class="input-group m-bottom-10">
        			<span class="input-group-addon" id="">
        				Reconciled Date A
        			</span>         
        			<input type="text" class="form-control reconciled_date_a" id="reconciled_date_a" name="reconciled_date_a" value="" placeholder="DD/MM/YYYY">
        		</div>
        	</div>


        	<div class="col-md-6 col-sm-6 col-xs-12 clearfix reconciled_date"   >
        		<div class="input-group m-bottom-10">
        			<span class="input-group-addon" id="">
        				Reconciled Date B
        			</span>         
        			<input type="text" class="form-control reconciled_date_b" id="reconciled_date_b" name="reconciled_date_b" value="" placeholder="DD/MM/YYYY">
        		</div>
        	</div>
        </div>


<script type="text/javascript">


$('#reconciled_date_a').datetimepicker({ format: 'DD/MM/YYYY'});
$('#reconciled_date_b').datetimepicker({
useCurrent: false, //Important! See issue #1075
format: 'DD/MM/YYYY'
});
$("#reconciled_date_a").on("dp.change", function (e) {

$('#reconciled_date_b').datetimepicker({
useCurrent: false, //Important! See issue #1075
format: 'DD/MM/YYYY'
});


});
$("#reconciled_date_b").on("dp.change", function (e) {
$(this).data("DateTimePicker").minDate(e.date);
});


</script>

        <div id="" class="clearfix">   <hr class="clearfix" style="padding: 0;margin: 14px 0;">  </div>
     
        <div class="input-group m-bottom-10">
        	<span class="input-group-addon" id="">For MYOB</span>         
        	<select class="for_myob form-control" id="for_myob" name="for_myob" title="">
        		<option value="0">No</option>
        		<option value="1" selected="true">Yes</option>                               
        	</select>       
        </div>


   <div class="input-group m-bottom-10" style="display:none;">
        	<span class="input-group-addon" id="">Document Type</span>         
        	<select class="output_file form-control" id="output_file" name="output_file" title="">
        		<option value="pdf">PDF</option>
        		<option value="csv"  selected="true">CSV</option>                               
        	</select>       
        </div>

        <div class="input-group m-bottom-10  tooltip-enabled" title="" data-html="true" data-placement="top" data-original-title="These are POs that's already been exported for reports CSV. Select YES to include them back in the list." >
        	<span class="input-group-addon" id="">Include Duplicate</span>         
        	<select class="include_duplicate form-control" id="include_duplicate" name="include_duplicate" title="">
        		<option value="0" selected="true">No</option>
        		<option value="1" >Yes</option>                               
        	</select>       
        </div>

        <div class="input-group m-bottom-10">
        	<span class="input-group-addon" id="">Sort</span>         
        	<select class="po_sort form-control" id="po_sort" name="po_sort" title="po_sort*">
        		<option value="clnt_asc">Company Name A-Z</option>  
        		<option value="clnt_desc">Company Name Z-A</option>
        		<option value="cpo_d_asc">CPO Date Asc</option> 
        		<option value="cpo_d_desc">CPO Date Desc</option>    
        		<option value="prj_num_asc">Project Number Asc</option>  
        		<option value="prj_num_desc">Project Number Desc</option>    
        		<option value="reconciled_d_asc">Reconciled Date Asc</option>  
        		<option value="reconciled_d_desc">Reconciled Date Desc</option>                                   
        	</select>       
        </div>

        <?php if( $this->session->userdata('purchase_orders') < 2): ?>

        	<script type="text/javascript">
        		$('select#for_myob').val('0').parent().hide();
        		$('select#include_duplicate').val('1').parent().hide();
        		$('select#output_file').val('pdf').parent().hide();



        	</script>

        <?php endif; ?>






        <div id="" class="clearfix">   <hr class="clearfix" style="padding: 0;margin: 14px 0;">  </div>

        <div class="pull-right"> 
        <p>&nbsp;</p>
        	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        	<button type="button" class="btn btn-primary po_submit">Submit</button>
        </div>

    </div>
</div>
</div>
</div>

<script type="text/javascript">
	

	$('input#po_number_srch_rec').keypress(function(event){

		var keycode = (event.keyCode ? event.keyCode : event.which);
		if(keycode == '13'){
			//alert('You pressed a "enter" key in textbox');	
			$('.srch_btn_po_rec').trigger('click');
		}
		event.stopPropagation();
	});


</script>



<script type="text/javascript">






  $('select#for_myob').on("change", function(e) {
    var for_myob = $(this).val();

    if(for_myob == 1){
    	$('select#output_file').val('csv');
    	$('select#output_file').parent().hide();


    	$('select#include_duplicate').val(0);
    	$('select#include_duplicate').parent().show();

    }else{

    	$('select#output_file').val('pdf');
    	$('select#output_file').parent().show();



    	$('select#include_duplicate').parent().hide();
    	$('select#include_duplicate').val(1);


    	
    }


});
	



  $('.po_submit').click(function(){

    var project_manager = $('select#project_manager').val();
    var status = $('select#status_switcher').val();
  
    var cpo_date_a = $('input#cpo_date_a').val();
    var cpo_date_b = $('input#cpo_date_b').val();
  
    var reconciled_date_a = $('input#reconciled_date_a').val();
    var reconciled_date_b = $('input#reconciled_date_b').val();
  
    var output_file = $('select.output_file').val();
    var po_sort = $('select.po_sort').val();


    var focus_company = $('select.focus_company').val();

    var for_myob = $('#for_myob').val();
    var include_duplicate = $('#include_duplicate').val();
    
    var has_error = 0;

    project_manager = project_manager || '';
    status = status || '';
    output_file = output_file || '';

 


if(focus_company == ''){
	has_error = 1;
	$('select.focus_company').parent().addClass('has-error');
}else{
	$('select.focus_company').parent().removeClass('has-error');
	has_error = 0; 





  if( reconciled_date_a != '' && reconciled_date_b != '' ){
  	has_error = 0; 
  	$('.error_tag .reconciled_date').removeClass('has-error');

  }else{

  	if(  (reconciled_date_a != '' && reconciled_date_b == '')    || (reconciled_date_a == '' && reconciled_date_b != '')   ){

  		has_error = 1;
  		$('.error_tag .reconciled_date').addClass('has-error');

  	}else{


	  	if(cpo_date_a == '' && cpo_date_b == '' ){
	  		has_error = 1;
	  		$('.error_tag .cpo_date').addClass('has-error'); 
	  	}else{
	  		has_error = 0;
	  		$('.error_tag .cpo_date').removeClass('has-error'); 
	  	}
  	}

  }
}



   var data = project_manager+'*'+status+'*'+cpo_date_a+'*'+cpo_date_b+'*'+reconciled_date_a+'*'+reconciled_date_b+'*'+output_file+'*'+po_sort+'*'+focus_company+'*'+for_myob+'*'+include_duplicate;
  //alert(data);
  $('.report_result').html('');





    if(has_error == 1){
    	$('.error_area').html('<div class="border-less-box alert alert-danger fade in pad-5 m-bottom-10"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><p>Please fix the errors.</p></div>');
    }else{
    	$('.error_tag .cpo_date').removeClass('has-error'); 
    	$('.error_tag .reconciled_date').removeClass('has-error'); 
    	$('.error_area').html('');

    	$('#loading_modal').modal('show');
    	$('#po_filter_modal').modal('hide');

 
   
    if(output_file == 'pdf'){

      $.ajax({
        'url' : base_url+'reports/purchase_order_report',
        'type' : 'POST',
        'data' : {'ajax_var' : data },
        'success' : function(data){
          if(data){
            $('#loading_modal').modal('hide');
            $('.report_result').html(data);
            window.open(baseurl+'docs/temp/'+data+'.pdf', '', 'height=590,width=850,top=100,left=100,location=no,toolbar=no,resizable=yes,menubar=no,scrollbars=yes',true);
          }
        }
      });   


    }else{


     $('#filter_invoice').modal('hide');
     $('#loading_modal').modal('hide');
            window.open(baseurl+'reports/purchase_order_report?ajax_var='+data, '_blank');


    } 
 

    }


});


</script>



<!-- Modal -->


<div class="report_result hide hidden"></div>



<!-- Modal -->
<div class="modal fade" id="reconciliated_po_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Purchase Order</h4>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">

            <div class="col-sm-12 border-bottom">
              <div class="clearfix col-sm-6">
                <p>PO Number: <strong class="po_number_mod">0000/0000</strong></p>
              </div>
              <div class="clearfix col-sm-6">
                <p>Description: <strong class="po_desc_mod">Xxxx</strong></p>
              </div>
              <div class="clearfix col-sm-6">
                <p>Total: <strong class="po_total_mod">$00000</strong> ex-gst</p>
              </div>
              <div class="clearfix col-sm-6">
                <p>Balance: <strong class="po_balance_mod">$0.00</strong></p>
              </div>  

            </div>

            

            <div class="col-sm-12 m-top-15">
                <p>Note: <strong class="">Negative Value for Balance means "Over Paid"</strong></p>
              <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Amount ext-gst</th>
                  <th>Invoice Number</th>
                  <th>Notes</th>
                </tr>
              </thead>
              <tbody class="po_history return_outstanding">                
              </tbody>
            </table>
            </div>
 
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default po_cancel_values" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<div style="display:none;" class="outstading_pm">
	<select id="outstading_pm" class="form-control  pull-right input-sm m-left-10 po_rev_pm"  style="width:200px;">
		<option value="">Select Project Manager</option>
		<?php
		foreach ($users->result_array() as $row){
			if($row['user_role_id']==3 || $row['user_role_id']==20):
				echo '<option value="'.$row['user_first_name'].' '.$row['user_last_name'].'" >'.$row['user_first_name'].' '.$row['user_last_name'].'</option>';
			endif;
		}
		?>
		<option value="Gary Ford">Gary Ford</option>
	</select>
</div>



<div id="" style="display:none;" class="cpo_date_filter">
	<select id="cpo_date_filter" class="form-control  pull-right input-sm m-left-10"  style="width:200px;">
		<option value="">Sort CPO Date</option>
		<option value="asc">Ascending</option>
		<option value="desc">Descending</option>
	</select>
</div>




<div id="" style="display:none;" class="cpo_date_filter">
	<select id="complation_date_filter" class="form-control  pull-right input-sm m-left-10"  style="width:200px;">
		<option value="">Sort Completion Date</option>
		<option value="asc">Ascending</option>
		<option value="desc">Descending</option>
	</select>
</div>

<!-- Purchase Order Filter modal -->
<div class="modal fade" id="po_date_filter_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Purchase Order Filter</h4>
      </div>
      <div class="modal-body">
        <div class="input-group m-bottom-10">
          <span class="input-group-addon" id="">
            <i class="fa fa-calendar"></i>
          </span>
          <input type="text" data-date-format="dd/mm/yyyy" placeholder="From" class="form-control datepicker" id="po_start_date" name="po_start_date" value="" >
        </div>

        <div class="input-group m-bottom-10">
          <span class="input-group-addon" id="">
            <i class="fa fa-calendar"></i>
          </span>
          <input type="text" data-date-format="dd/mm/yyyy" placeholder="To" class="form-control datepicker" id="po_end_date" name="po_end_date" value="" >
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="filter_po_bydate" data-dismiss="modal"><i class="fa fa-filter"></i> Filter</button>
      </div>
    </div>
  </div>
</div>

<div class="po_legend_o hide">
	<p class="pad-top-5 m-left-10"> &nbsp;  &nbsp; <span class="ex-gst">$<?php echo number_format($total_price_exgst,2); ?>Ex GST</span> &nbsp;  &nbsp; <span class="inc-gst">$<?php echo number_format($total_price_incgst,2); ?> Inc GST</span></p>
</div>


<script type="text/javascript">
  $('div.zero_payment').click(function(){
    $('input#po_amount_value').val('0.00');
    $('input#po_amount_value_inc_gst').val('0.00');
    $('input#po_is_reconciled_value').prop('checked', true);
  });


</script>


<style type="text/css">
	/*.po-area #companyTable_length, .po-area #companyTable_filter{
		display: none;
		visibility: hidden;
	}*/
	.ex-gst{
		color: rgb(219, 0, 255);  font-weight: bold;
	}

	.inc-gst{
		color: rgb(31, 121, 52);  font-weight: bold;
	}
</style>
<?php $this->bulletin_board->list_latest_post(); ?>
<?php $this->load->view('assets/logout-modal'); ?>


<?php if($this->session->userdata('purchase_order') == 1 || $this->session->userdata('is_admin') ==  1): ?> <style type="text/css"> button.po_set_values{ display: block !important; }</style> <?php endif; ?>