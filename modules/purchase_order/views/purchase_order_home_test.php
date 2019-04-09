<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>
<?php $this->load->module('projects'); ?>
<?php $this->load->module('purchase_order'); ?>
<?php $this->load->module('bulletin_board'); ?>

<?php $total = 0; ?>
<?php $total_c = 0; ?>


														<table id="po_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
															
															<tbody>

																<?php
 

																	foreach ($po_list->result_array() as $row){

																		$balance_a = $this->purchase_order->check_balance_po($row['works_id']);


																		echo '<tr id="">
																		<td></td>
																		<td></td>
																		<td></td>
																		<td></td>
																		<td></td>
																		<td></td>
																		<td></td>
																		<td></td>
																		<td></td>
																		<td>'.$row['project_manager_id'].'</td>
																		<td><span class="ex-gst">'.number_format($balance_a,2).'</span ></td>';
                                    echo '</tr>';

if( $row['project_manager_id'] == 15){
$total = $total + $balance_a;
}


if( $row['focus_company_id'] == 6){
$total_c = $total_c + $balance_a;
}



																  }
																?>

                                <?php 
                                  foreach ($work_joinery_list->result_array() as $row_j){

 


									$balance_b = $this->purchase_order->check_balance_po($row_j['works_id'],$row_j['work_joinery_id']);

                                 


																		echo '<tr id="">
																		<td></td>
																		<td></td>
																		<td></td>
																		<td></td>
																		<td></td>
																		<td></td>
																		<td></td>
																		<td></td>
																		<td></td>
																		<td>'.$row_j['project_manager_id'].'</td>
																		<td><span class="ex-gst">'.number_format($balance_b,2).'</span ></td>';


                                    echo '</tr>';


if( $row_j['project_manager_id'] == 15){
$total = $total + $balance_b;
}


if( $row_j['focus_company_id'] == 6){
$total_c = $total_c + $balance_b;
}


                                  } 
                                ?>

															</tbody>
														</table>




<?php echo $total; ?><p></p>
<?php echo $total_c; ?>