<?php $this->load->model('purchase_order_m'); ?>
<div class="box-area po-area">
  <table id="po_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
     <thead><tr><th>PO Number</th><th>CPO Date</th><th>Req Date</th><th>GST %</th><th>Contractor PO</th><th>Invoiced</th><th>Out Standing</th><th>Contractor</th></tr></thead>
       <tbody>
       <?php
         $project_id = 0;
         $project_tot_cpo = 0;
         $proj_tot_inv = 0;
         $proj_tot_outstanding = 0;
         $gt_cpo = 0;
         $gt_inv = 0;
         $gt_outstanding = 0;
         foreach ($po_list_ordered->result_array() as $row){
            $work_id = $row['works_id'];
            if($project_id !== 0 && $project_id !== $row['project_id']):
              echo '<tr><td colspan = 8 style = "border-top: 1px solid #888"></td></tr>';
              echo '<tr><td colspan = 4 align = right><strong>Sub Total:</strong></td>';
              echo '<td align = right><strong>'.number_format($project_tot_cpo,2).'</strong></td>';
              echo '<td align = right><strong>'.number_format($proj_tot_inv,2).'</strong></td>';
              echo '<td align = right><strong>'.number_format($proj_tot_outstanding,2).'</strong></td>';
              echo '<td align = center><strong>ex GST</strong></td></tr>';
              echo '<tr><td colspan = 8 style = "border-top: 1px solid #888"></td></tr>';
              $project_tot_cpo = 0;
              $proj_tot_inv = 0;
              $proj_tot_outstanding = 0;
            endif;
            if($project_id !== $row['project_id']){
              $project_id = $row['project_id'];
              echo '<tr><td><strong>'.$row['project_id'].'</strong></td>';
              echo '<td colspan = 4><strong>'.$row['project_name'].'</strong></td>';
              echo '<td colspan = 4 align = center><strong>'.$row['company_name'].'</strong></td></tr>';
            }
            echo '<tr><td><a href="#" data-toggle="modal" data-target="#invoice_po_modal" data-backdrop="static" class="select_po_item">'.$row['works_id'].'</a></td>';
            echo '<td>'.$row['work_cpo_date'].'</td>';
            echo '<td>'.$row['work_reply_date'].'</td>';
            echo '<td align= right>'.$gst_rate.' %</td>';
            $project_tot_cpo = $project_tot_cpo + $row['price'];
            $gt_cpo = $gt_cpo + $row['price'];
            echo '<td align= right>'.number_format($row['price'],2).'</td>';
            echo '<td align= right>';
            $po_tot_inv_q = $this->purchase_order_m->get_po_total_paid($work_id);
            $invoiced = 0;
            foreach ($po_tot_inv_q->result_array() as $po_tot_row){
              $invoiced = $po_tot_row['total_paid'];
              echo number_format($po_tot_row['total_paid'],2);
            }
            $proj_tot_inv = $proj_tot_inv + $invoiced;
            $gt_inv = $gt_inv + $invoiced;
            $out_standing = $row['price'] - $invoiced;
            $proj_tot_outstanding = $proj_tot_outstanding + $out_standing;
            $gt_outstanding = $gt_outstanding + $out_standing;
            echo '</td>';
            echo '<td align=right>'.number_format($this->purchase_order->check_balance_po($row['works_id']),2).'</td>';
            echo '<td>'.$row['contractor_name'].'</td>';
            echo '</tr>';
            
         }
        echo '<tr><td colspan = 8 style = "border-top: 1px solid #888"></td></tr>';
        echo '<tr><td colspan = 4 align = right><strong>Sub Total:</strong></td>';
        echo '<td align = right><strong>'.number_format($project_tot_cpo,2).' </strong></td>';
        echo '<td align = right><strong>'.number_format($proj_tot_inv,2).'</strong></td>';
        echo '<td align = right><strong>'.number_format($proj_tot_outstanding,2).'</strong></td>';
        echo '<td align = center><strong>ex GST</strong></td></tr>';
        echo '<tr><td colspan = 8 style = "border-top: 1px solid #888"></td></tr>';

        echo '<tr><td colspan = 8 style = "border-top: 5px solid #888"></td></tr>';
        echo '<tr><td colspan = 4 align = right><strong>Grand Total:</strong></td>';
        echo '<td align = right><strong>'.number_format($gt_cpo,2).'</strong></td>';
        echo '<td align = right><strong>'.number_format($gt_inv,2).'</strong></td>';
        echo '<td align = right><strong>'.number_format($gt_outstanding,2).'</strong></td></tr>';
        echo '<tr><td colspan = 8 style = "border-top: 5px solid #888"></td></tr>';
       ?>
       <?php 
         foreach ($work_joinery_list->result_array() as $row_j){
           echo '<tr><td><a href="#" data-toggle="modal" data-target="#invoice_po_modal" data-backdrop="static" class="select_po_item">'.$row_j['works_id'].'-'.$row_j['work_joinery_id'].'/'.$row_j['project_id'].'</a></td><td>'.$row_j['work_cpo_date'].'</td><td><a href="'.base_url().'works/update_work_details/'.$row_j['project_id'].'/'.$row_j['works_id'].'">';
           echo $row_j['joinery_name'];
           echo '</a></td><td>'.$row_j['contractor_name'].'</td><td><a href="'.base_url().'projects/view/'.$row_j['project_id'].'" >'.$row_j['project_id'].'</a></td><td>'.$row_j['job_date'].'</td><td>'.$row_j['client_name'].'</td><td>'.$row_j['user_first_name'].' '.$row_j['user_last_name'].'</td><td>'.number_format($row_j['price'],2).'</td>';
           echo '<td>'.number_format($this->purchase_order->check_balance_po($row_j['works_id'],$row_j['work_joinery_id']),2).'</td>';
           echo '</tr>';
         } 
       ?>
     </tbody>
   </table>
</div>