<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>


<?php $this->load->module('projects'); ?>
<?php $this->load->model('projects_m'); ?>

<?php $this->load->module('company'); ?>
<?php $this->load->module('bulletin_board'); ?>



<?php //$this->load->module('dashboard'); ?>
<?php //$this->load->model('dashboard_m'); ?>




<?php if( $this->session->userdata('user_role_id') == 2 ): ?>

  <?php 

  $user_id = $this->session->userdata('user_id');
  $pa_data = $this->projects_m->fetch_user_pa_assignment($user_id);
  $assignment = array_shift($pa_data->result_array());

  $prime_pm = $assignment['project_manager_primary_id'];
  $group_pm = explode(',', $assignment['project_manager_ids']);

  ?>

<?php  endif; ?>


<?php if( $this->session->userdata('user_role_id') == 7 ): 
  $prime_pm = 29;
  $group_pm = array(29);
 endif; ?>


<?php //$this->invoice->reload_invoiced_amount(); ?>

<!-- title bar -->
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
            <a href="<?php echo base_url(); ?>projects"><i class="fa fa-map-marker"></i> Projects</a>
          </li> 
        

        </ul>
      </div>

    </div>
  </div>
</div>
<!-- title bar -->
 <script type="text/javascript">


function reCountWip(tble_target,colA,colB){


  var wip_tot_prj = 0;
  var wip_tot_cst = 0;
  var wip_tot_hrs = 0;
  var wip_tot_uin = 0;

  var cst = 0;
  var hrs = 0;
  var uin = 0;

  $('tbody.'+tble_target+' > tr').each(function(index, tr) {

  if($(this).css('display') != 'none'){




  wip_tot_prj++;
 
  cst = $(this).find('td:nth-child('+colA+')').text();
  cst = cst.replace(",", "");
  wip_tot_cst =  parseFloat(wip_tot_cst)+parseFloat(cst);
 

if(colB > 0){

  hrs = $(this).find('td:nth-child('+colB+')').text();
  wip_tot_hrs =  parseFloat(wip_tot_hrs)+parseFloat(hrs);

  var colC =  parseInt(colB)+1;

  uin = $(this).find('td:nth-child('+colC+')').text();
  uin = uin.replace(",", "");
  wip_tot_uin =  parseFloat(wip_tot_uin)+parseFloat(uin);




}



  }





});


$('.'+tble_target+'_prj').text(wip_tot_prj.toLocaleString());
$('.'+tble_target+'_cst').text(wip_tot_cst.toLocaleString());



if(colB > 0){
  $('.'+tble_target+'_hrs').text(wip_tot_hrs.toLocaleString());
  $('.'+tble_target+'_uin').text(wip_tot_uin.toLocaleString());

}

}


</script>

<div class="container-fluid">

<div class="test"></div>
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





                <div class="row clearfix">

                    <div class="col-lg-4 col-md-12">
                      <div class="box-head pad-left-15 clearfix">
                        <label><?php echo $screen; ?></label>
                        <p style="margin: -5px 0 5px;"><strong>Notice</strong> <i class="fa fa-info-circle"></i> Posting notes only does not count as review. <br /> <i class="fa fa-quote-left"></i> WIP Reviews must be completed each week by Wednesday midnight. <i class="fa fa-quote-right"></i></p>
                      </div>
                    </div>
                    
                    <div class="col-lg-8 col-md-12">
                      <div class="pad-left-15 pad-right-10 clearfix box-tabs"  style=" margin: 5px 0 10px;">  

                      <div class="pull-right m-left-10">
                          <div class="input-group  pull-right" style="width:200px;margin-right: -5px;">
                            <span class="input-group-addon">Project ID</span>

                          <?php /*  <input type="text" class="form-control" placeholder="Project ID" id="progress_claim_srch_rec"> */ ?>


                            <textarea class="form-control" placeholder="Project ID" id="progress_claim_srch_rec" style="     height: 34px;"></textarea>
                          </div>
                        </div>
 
                        <div class="input-group  pull-right" style="width:320px; margin-right: -5px;">
                          <span class="input-group-addon">Project Manager</span>
                          
<?php /*
                          <select class="form-control prjrvw_pm_selection">
                            <option value="all|0">View All</option>
                            <?php
                              foreach ($pm_list->result_array() as $row):
                                      echo '<option value="'.$row['name_log'].'|'.$row['project_manager_id'].'" >'.$row['name_log'].'</option>';
                              endforeach;
                            ?>
                          </select>

*/ ?>


<select class="form-control prjrvw_pm_selection">
  <option value="all|0">View All</option>
  <?php
  foreach ($users->result_array() as $row):
    if($row['user_role_id']==3 || $row['user_role_id']==20   ):
      if( $this->session->userdata('user_role_id') == 2 ):
        if( in_array($row['user_id'], $group_pm) ):
          echo '<option value="'.$row['user_first_name'].' '.$row['user_last_name'].'|'.$row['user_id'].'" >'.$row['user_first_name'].' '.$row['user_last_name'].'</option>';
        endif;
        else:
          echo '<option value="'.$row['user_first_name'].' '.$row['user_last_name'].'|'.$row['user_id'].'" >'.$row['user_first_name'].' '.$row['user_last_name'].'</option>';

        endif;

        


        endif;
        endforeach;
        ?>
      </select>


                          
                        </div>
  

                        <script type="text/javascript">
                          <?php if( $this->session->userdata('user_role_id') == 20 || $this->session->userdata('user_role_id') ==  3): ?>
                            var pm_name = '';
                            var pm_set_selector = "<?php echo ucfirst($this->session->userdata('user_first_name')).' '.ucfirst($this->session->userdata('user_last_name')).'|'.$this->session->userdata('user_id'); ?>";
                            setTimeout(function(){ 
                              $('select.prjrvw_pm_selection').val(pm_set_selector).trigger("change").parent().hide();
                            },1);
                          <?php endif; ?>


                            <?php if( $this->session->userdata('user_role_id') == 7 ): ?>
                            var pm_name = 'Maintenance Manager';
                            var pm_set_selector = "Maintenance Manager|29";
                            setTimeout(function(){ 
                              $('select.prjrvw_pm_selection').val(pm_set_selector).trigger("change").parent().hide();
                            },1);
                          <?php endif; ?>



 

                          function reset_pa_table(){
/*

                            <?php if( $this->session->userdata('user_role_id') == 2 ): ?>
                              <?php foreach ($group_pm as $key => $value): ?>

                              $('.table.prj_rvw_tbl tr.prj_rvw_rw').hide();

                               setTimeout(function(){ 
                                 $.each($('td.rw_pm_slct'), function (index, itm_obj) { 
                                  var prj_list_text = $(itm_obj).text();
                                  var pms_to_look = '<?php echo $value; ?>';

                                  if( prj_list_text.includes('pm_'+pms_to_look)  ){
                                    $(itm_obj).parent().show();
                                  } 
                                });

                               },1);
                           <?php endforeach; ?>
                         <?php  endif; ?>

*/
                       }

                         



                       $( document ).ready(function() {
                        setTimeout(function(){ 
                         reset_pa_table();
                       },50);
                      });


                      </script>


                    
                         


                      </div>
                    </div>

                </div>
 <?php if( $this->session->userdata('user_role_id') == 2 ): ?>
<style type="text/css">
  
  .table.prj_rvw_tbl tr.prj_rvw_rw{
   /* display: none;*/
  }
</style>
   <?php  endif; ?>


                <script type="text/javascript">



 
  $('#progress_claim_srch_rec').on("input", function(e) { //number_only number only

    var project_id_rvw = $(this).val();
    var pm_selected = $('select.prjrvw_pm_selection').val();

    $('select.prjrvw_pm_selection').val(pm_selected).trigger("change");

      $.each($('strong.prj_id_rvw'), function (index, itm_obj) { 
        var prj_list_text = $(itm_obj).text();


        if ( $(itm_obj).parent().parent().parent().css('display') == 'none' || $(itm_obj).parent().parent().parent().css("visibility") == "hidden"){

        }else{
          if( prj_list_text.includes( project_id_rvw)  ){
           $(itm_obj).parent().parent().parent().show();
         }else{
           $(itm_obj).parent().parent().parent().hide();
         }
       }



     });

 






  });


<?php if(isset($_GET['pmr']) &&  $_GET['pmr'] != '' ): ?>

  var pm_selected_er = <?php echo $_GET['pmr']; ?>;
  var pm_selected_set = '';

  $.each($('select.prjrvw_pm_selection option'), function (index, itm_obj) { 
    var pm_list_text = $(itm_obj).val();

    if( pm_list_text.includes( pm_selected_er)  ){
           pm_selected_set = pm_list_text;
    }
  });

  setTimeout(function(){ 
    $('select.prjrvw_pm_selection').val(pm_selected_set).trigger("change"); 
  },1);

<?php endif; ?>



  $('select.prjrvw_pm_selection').on("change", function(e){
   var pm_id_raw = $(this).val();
   var pm_id_arr = pm_id_raw.split("|");



                  //  $('input#progress_claim_srch_rec').val('');

                   

                    if(pm_id_arr[1] > 0){

                      $.each($('td.rw_pm_slct'), function (index, itm_obj) { 
                        var prj_list_text = $(itm_obj).text();

                       // alert(prj_list_text);

                     //   if( prj_list_text.includes('pm_'+pm_id_arr[1])){


                        if( prj_list_text == 'pm_'+pm_id_arr[1] ){
                         $(itm_obj).parent().show();

                       }else{
                         $(itm_obj).parent().hide();
                       }

                      });

                    }else{
                     


                      
                    $('tr.prj_rvw_rw').show(); 



                    }

 

       reCountWip('prj_wip_rvw',5,7);
     
      
 setTimeout(function(){    reCountWip('prj_qut_rvw',5,7);   },100);

 setTimeout(function(){   reCountWip('un_invoiced_rvw',7,0);    },200);


                  });



               </script>

          

                <div class="box-area">

                  <div class="box-tabs m-bottom-15">
                  <div class="tab-content"> 


                  <div class="clearfix pull-left" style="margin: 7px 0 0px 3px;">
 
                    <div class="hide" style=" background:#7d1e62 ; font-size: 12px; padding: 1px 8px;  color:#fff;  float: right;     border: 1px solid #424141;    height: 20px;    margin: 0px 5px;     border-radius: 10px;    display: block;"><strong>Late Reviewed</strong></div>
                     <div  style=" background:#424141; font-size: 12px; padding: 1px 8px;  color:#F7901E;  float: right;     border: 1px solid #424141;    height: 20px;    margin: 0px 5px;     border-radius: 10px;    display: block;"><strong>Un-Reviewed</strong></div>
                   <div  style=" background:#FFA9E6; font-size: 12px; padding: 1px 8px;  color:#000;  float: right;     border: 1px solid #000000;    height: 20px;    margin: 0px 5px;     border-radius: 10px;    display: block;"><strong>Reviewed</strong></div>

                  </div>



                        <div id="" class=" pull-right"  style="margin: 6px 10px 5px 0;">
                          <ul id="myTabPill" class="nav   nav-pills"> 
                            <li class="active">
                              <a id="prj_stat_tab_wip"  href="#wip" data-toggle="tab"> WIP</a>
                            </li>
                            <li class="">
                              <a id="prj_stat_tab_quote"  href="#quote" data-toggle="tab">Quotes</a>
                            </li>
                            <li class="" >
                              <a id="prj_stat_tab_uninvoiced"  href="#uninvoiced" data-toggle="tab">Un-Invoiced</a>
                            </li>
                          </ul>
                          <style type="text/css">
                            ul#myTabPill li.active a{
                              background-color: #428BCA !important;
                              color: #fff !important;

                            }

                            .box-area p {
                              margin: 20px 0 0 0;
                              font-weight: bold;
                            }
                          </style>


                      </div>

 
                      <div class="tab-pane  clearfix " id="quote">
                        <div class="m-bottom-15 clearfix">


                          <div class="box-area clearfix">  
                            
                         <div id="" class="clearfix" style="margin: -20px;"></div>


                          <strong class="m-0 pull-left">New projects for quotation.</strong>
                            <div id="" class="pull-left"> &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp; 


                           

                            </div>



                            
                          </div>

 <div id="" class="" style="height: 74%;    overflow-y: scroll;">
                            <table id=" " class="prj_rvw_tbl table table-bordered" cellspacing="0" width="100%">

                            <thead>
  

                              <tr> <th>Finish</th> <th>Start</th><th>Client</th> <th>Project</th> <th>Total WIP</th> <th>Quote Deadline  <i class="fa  fa-sort-numeric-asc"></i>  </th> <th>Install</th> <th>Invoiced</th> <th class=" hide">PM</th> </tr> 
                            

                            </thead>
                            <tbody  class="prj_qut_rvw"> 
                              <?php echo $this->projects->display_all_projects('quote',1); ?>
                            </tbody>  

                        <tfoot>     
  <tr> <th> </th> <th> </th><th> </th> <th>Total Projects Count: <strong class="prj_qut_rvw_prj">0</strong></th> <th><strong class="prj_qut_rvw_cst">0</strong> </th> <th>  </th> <th><strong class="prj_qut_rvw_hrs">0</strong> Hrs</th> <th><strong class="prj_qut_rvw_uin">0</strong></th> <th class=" hide"> </th> </tr>
 </tfoot>

                          </table>   
                          </div>                         
                        </div> 
                      </div>
                      <div class="tab-pane clearfix active" id="wip">
                        <div class="m-bottom-15 clearfix">
                          <div class="box-area clearfix"> 

                         <div id="" class="clearfix" style="margin: -20px;"></div>
                            <strong class="m-0 pull-left">Work-in-progress projects.</strong>
                       <?php /*
                                                    <div id="" class="pull-left"> &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp; 
                                                      &nbsp; Total Jobs: <strong class="prj_wip_rvw_prj">0</strong> &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp; 
                                                      &nbsp; Total WIP: <strong class="prj_wip_rvw_cst">0</strong> Ex-gst &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp; 
                                                      &nbsp; Total Install Hrs: <strong class="prj_wip_rvw_hrs">0</strong> Hrs
                                                    </div>
                                                   */ ?> 
                          </div>
                          <div id="" class="" style="height: 74%;    overflow-y: scroll;">
                            <table id=" " class="prj_rvw_tbl table table-bordered" cellspacing="0" width="100%">
                              <thead>
                                <tr>
                                <th>Finish  <i class="fa fa-sort-numeric-asc"></i></th> 
                                <th>Start</th>
                                <th>Client</th> 
                                <th>Project</th> 
                                <th>Total</th> 
                                <th>Job Date</th> 
                                <th>Install</th> 
                                <th>Invoiced</th> <th class=" hide">PM</th> </tr> 
                              </thead>
                              <tbody class="prj_wip_rvw"> 
                                <?php echo $this->projects->display_all_projects('wip',1); ?>
                              </tbody>
                              <tfoot>
                               <tr> <th> </th> <th> </th><th> </th> <th>Total Projects Count: <strong class="prj_wip_rvw_prj">0</strong></th> <th><strong class="prj_wip_rvw_cst">0</strong> </th> <th>  </th> <th><strong class="prj_wip_rvw_hrs">0</strong> Hrs</th> <th><strong class="prj_wip_rvw_uin">0</strong></th> <th class=" hide"> </th> </tr> 
                             </tfoot>
                           </table>
                         </div>
                        </div>
                      </div>

                      <div class="tab-pane clearfix" id="uninvoiced">
                        <div class="m-bottom-15 clearfix">
                         <div class="box-area">  
                          

                         <div id="" class="clearfix" style="margin: -20px;"></div>
                          <strong class="m-0 pull-left">On-going projects that needs to be invoiced.</strong>
                          
<?php /*
                            <div id="" class="pull-left"> &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp; 
                            
                              &nbsp; Total Jobs: <strong class="un_invoiced_rvw_prj">0</strong> &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp; 
                              &nbsp; Total WIP: <strong class="un_invoiced_rvw_cst">0</strong> Ex-gst &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;
                            </div>
*/ ?>


                        </div>

                        <div id="" class="clearfix"></div>

                        <div id="" class="" style="height: 74%;    overflow-y: scroll;">
                         <table id=" " class="prj_rvw_tbl table table-bordered" cellspacing="0" width="100%">
                          <thead>
                            <tr> <th>Finish</th> <th>Client</th>  <th>Project</th> <th>Invoicing <i class="fa fa-sort-numeric-asc"></i></th> <th>Progress</th> <th>Percent</th> <th>Amount</th> <th class=" hide">PM</th></tr> 
                          </thead>
                          <tbody class="un_invoiced_rvw"> 
                            <?php  echo $this->projects->list_un_invoiced_rvw(); ?>
                          </tbody>
                          <tfoot>
                           <tr> <th></th> <th></th>  <th>Total Projects Count: <strong class="un_invoiced_rvw_prj">0</strong></th> <th>    </th> <th></th> <th></th> <th><strong class="un_invoiced_rvw_cst">0</strong> </th> <th class=" hide"> </th></tr>
                         </tfoot>
                       </table>
                     </div>
                      </div>
                    </div>

                    <style type="text/css">


                    table.prj_rvw_tbl thead{
                        background: #ddd;
                      }

                    table.prj_rvw_tbl{
                      font-size: 14px;  
                    }


                    table tr.needed_rev{
                      background-color: #424141;
                      color: #fff;
                      
                    }


                    table tr.posted_rev{
                      background-color: #ffa9e6;                      
                    }

                    table tr.posted_rev td, table tr.posted_rev td a{
                      color: #000 !important;
                    }


                    .prj_qut_rvw, .prj_qut_rvw tr td, .prj_qut_rvw tr td a {
                      color: #F7901E  !important;
                    }

                    .un_invoiced_rvw, .un_invoiced_rvw tr td, .un_invoiced_rvw tr td a {
                      color: #F7901E  !important;
                    }

                    </style>




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


<style>


/*.notes_btn_prjrvw
*/

tr.prj_rvw_rw:hover, tr.prj_rvw_rw:hover td, tr.prj_rvw_rw:hover a{
  background-color:  #84f1ff;
  color:#000 !important;
}

.notes_line small{
  color: #8A8A8A !important;
}

tr.current_item_prjrvw{
  background-color: #ffa9e6 !important;
}

tr.current_item_prjrvw_selected{
  background-color: #84f1ff !important;
  color:#000 !important;  
}


tr.posted_rev_late,
tr.posted_rev_late td,

tr.posted_rev_late td a{
  background-color: #7d1e62 !important;
  color: #fff !important;
}


table.prj_rvw_tbl{
text-align: left;
  position: relative;
  border-collapse: collapse; 
  margin: 0;
  padding: 0;
}
 
table.prj_rvw_tbl thead th {
 
  position: sticky;
  top: 0; /* Don't forget this, required for the stickiness */
  background: #DDDDDD;
  font-weight: bold;
  
}
 
table.prj_rvw_tbl tfoot th {
 
  position: sticky;
  bottom: 0; /* Don't forget this, required for the stickiness */
  background: #DDDDDD;
  font-weight: bold;
 
}


table.prj_rvw_tbl td p {
  white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin: 0px;
}

</style>
   

<div class="toggle_notes_prjrvw" style="display:none; width: 35%;height: 100%;background-color: #0E283C;position: fixed;top: 0px;right: -35%;color: #fff;z-index: 10001;">
 

<div class="note_side_area pad-10">


  <input type="hidden" class="prjrvw_user_id" value="<?php echo $this->session->userdata('user_id'); ?>" />
  <input type="hidden" class="prjrvw_user_first_name" value="<?php echo ucfirst($this->session->userdata('user_first_name')); ?>" />
  <input type="hidden" class="prjrvw_user_last_name" value="<?php echo ucfirst($this->session->userdata('user_last_name')); ?>" />
  <input type="hidden" class="prjrvw_project_id" value="0" />
  <input type="hidden" class="prjrvw_btn_clck_id" value="" />

         
          <div class="notes_init_search"><strong class="pointer close_toggle_prjrvw pull-right"><i class="fa-times-circle fa"></i> Close</strong><strong id="" >Post New Notes</strong><br /><span class="project_title_rvw">PRJID</span> </div>

          <div class="notes_side_form m-top-15 clearfix" style="">
          <textarea class="form-control notes_comment_text " rows="5" id="notes_comment_text" placeholder="Notes" style="resize: vertical; min-height: 100px;"></textarea>
          <!-- add class to text area upper_c_first_word_sentence -->

          <div class="btn btn-info btn-sm m-top-10  no_updates_btn" id="#">No Updates</div>

         <!--  <div class="btn btn-danger btn-sm m-top-10  completed_review_btn" id="#">Completed</div> -->

          <div class="btn btn-primary btn-sm m-top-10 pull-right submit_notes_prj">Submit</div>
          <div class="btn btn-warning btn-md m-top-10 m-right-5 pull-right proj_rvw_reload_bttn" id=""><i class="fa fa-refresh"></i> </div>
          <p class="m-top-10 m-bottom-0" style="width: 140px;"><strong>Project Notes</strong></p>
        </div>

        <div class="notes_side_content clearfix"  style="overflow-y: auto; padding: 0 10px 0 0;">
          <div class="notes_line no_posted_comment"><p>No comments displayed.</p></div>
        </div>

      </div>
</div>

<?php if(isset($_GET['prj_ret_rev']) && $_GET['prj_ret_rev']!= ''):  

  $target_tr = $_GET['prj_ret_rev'];

  $prj_det_listarrA = explode('-', $_GET['prj_ret_rev']);
  $prj_det_listarrB = explode('_', $prj_det_listarrA[1]);

?>

<script type="text/javascript">


  $('ul#myTabPill li').removeClass('active');
  $('.tab-pane').removeClass('active');

  setTimeout(function(){   
    $('ul#myTabPill li a#prj_stat_tab_<?php echo $prj_det_listarrB[0]; ?>').parent().addClass('active');
    $('.tab-pane#<?php echo $prj_det_listarrB[0]; ?>').addClass('active');
  },1);


  setTimeout(function(){   
    $(window).scrollTop($('tr#<?php echo $target_tr; ?>').addClass('current_item_prjrvw').offset().top - 100);
  },700);


 

// $('ul#myTabPill li a#prj_stat_tab_<?php echo $prj_det_listarrB[0]; ?>').trigger('click');

//  tab-pane  clearfix active


// 


 //.offset().top - 70)






</script>

<?php endif; ?>

 

<script type="text/javascript">

$(document).ready(function() {



$(window).on("resize load", function(){

  var newWidth = $(window).width() / 5;
 

  $('table.prj_rvw_tbl td p').css('width',newWidth+'px')


});

 



/*

  var isMobile = window.matchMedia("only screen and (max-width: 760px)").matches;

    if (isMobile) {

      $('html').css('cursor','pointer');
      $('body').css('cursor','pointer');
      $('#main').css('cursor','pointer');
        //Conditional script here
    }
*/



/*
 $('.toggle_notes_prjrvw').animate({
    right: '-35%'
  }).hide();
*/
 

  $(document).bind("click touchstart", function(event){



/*
   $(".view_notes_prjrvw").click(function(event){
    
    if(event.currentTarget === this){

alert('open');
    }else{


alert('outside');

    }


  }); 

*/


// alert( event.target.className ); 




    var trigger = $(".view_notes_prjrvw")[0];
    var sideAreaPrjRw = $(".toggle_notes_prjrvw");
    var obj_clicked = event.target.className;

 //   var target = $(this).

 // alert(obj_clicked );


if( obj_clicked.includes('view_notes_prjrvw')  ){
 
//alert('clicked button');

   $('.toggle_notes_prjrvw').show().animate({
        right: '0'
      });


}else{
//alert('not button');



if (sideAreaPrjRw !== event.target && !sideAreaPrjRw.has(event.target).length && trigger !== event.target) {


$('.toggle_notes_prjrvw').animate({
    right: '-35%'
  }); //.hide();


}else{

     $('.toggle_notes_prjrvw').show();
    }





}





    if ( $(this).hasClass('view_notes_prjrvw') ) {
     //do something it does have the protected class!
  //   alert("i have the pretty and awesome classes");
   }





    if (sideAreaPrjRw !== event.target && !sideAreaPrjRw.has(event.target).length && trigger !== event.target) {
   //alert('1');
 
      

/*

      if ( $('.toggle_notes_prjrvw').css('display') == 'none'){

    //alert('3');
 
     } else {


  //   alert('4');
     }

*/




    }else{
//     alert('2');

 //    $('.toggle_notes_prjrvw').show();
    }






  });


});


  $('.submit_notes_prj').click(function(){

  $('.proj_rvw_reload_bttn').find('i').addClass('fa-spin');
  $('.notes_side_content').empty().append('<div class="notes_line no_posted_comment"><p><i class="fa fa-cog fa-spin"></i> Loading...</p></div>');


    $('.no_posted_comment').remove();

    var prjc_user_id = $('.prjc_user_id').val();
    var prjc_user_first_name = $('.prjc_user_first_name').val();
    var prjc_user_last_name = $('.prjc_user_last_name').val();
    var prjc_project_id = $('.prjrvw_project_id').val();
    var notes_comment_text = $('.notes_comment_text').val();
    var result = '';
    var dataString = prjc_user_id+'`'+prjc_project_id+'`'+notes_comment_text+'`0';

    $('.notes_comment_text').empty().val('');

  //$('.notes_side_content').prepend('<div class="notes_line"><p>'+notes_comment_text+'</p><small><i class="fa fa-user"></i> '+prjc_user_first_name+' '+prjc_user_last_name+'<br><i class="fa fa-calendar"></i> '+result+'</small></div>');


  if(notes_comment_text!=''){
    $.post(baseurl+"projects/add_project_comment",{ 
      'ajax_var': dataString
    },function(result){
   //   $('.notes_side_content').prepend('<div class="notes_line"><p class="" style="">'+notes_comment_text+'</p><br /><small><i class="fa fa-user"></i> '+prjc_user_first_name+' '+prjc_user_last_name+'<br><i class="fa fa-calendar"></i> '+result+'</small></div>');
    //  $('.recent_prj_comment').empty().append('<p>'+notes_comment_text+'</p><small><i class="fa fa-user"></i> '+prjc_user_first_name+' '+prjc_user_last_name+'<br><i class="fa fa-calendar"></i> '+result+'</small>');
    });
  }


  setTimeout(function(){   
    $('.proj_rvw_reload_bttn').find('i').removeClass('fa-spin');
    $('.proj_rvw_reload_bttn').trigger('click');
 },1000);




});




$('.close_toggle_prjrvw').click(function(e) {
 

  $('tr.prj_rvw_rw').removeClass('current_item_prjrvw_selected');

 


  $('.project_title_rvw').text('PRJID');
  $('.toggle_notes_prjrvw').animate({
    right: '-35%'
  }).hide();


  //alert( $(e.target).attr('class') );
});
  
 

 
 $('.no_updates_btn').click(function(event) {

  var project_id = $(this).attr('id');
  $(this).html('<i class="fa fa-refresh fa-spin"></i> Loading...');

  $.post(baseurl+"projects/set_date_review",{ 
    'ajax_var': project_id
  });
  var target_row_id = $('input.prjrvw_btn_clck_id').val();

//  alert(target_row_id);

  setTimeout(function(){   
    $('.no_updates_btn').text('No Updates').hide();
   // $('tr.current_item_prjrvw .needs_review').hide();
   $('tr#'+target_row_id).removeClass('current_item_prjrvw_selected').removeClass('needed_rev').addClass('posted_rev');

 },1000);

/*
  $('#loading_modal').modal({"backdrop": "static", "show" : true} );

  setTimeout(function(){
    $('#loading_modal').modal('hide');
  },2000);

*/
});

  $('.view_notes_prjrvw').click(function(event) {
 

      $('.box-area tr').removeClass('current_item_prjrvw_selected');


      event.preventDefault(); // because it is an anchor element

      $(this).parent().parent().addClass('current_item_prjrvw_selected');

      $('textarea#notes_comment_text').val('');

      var project_raw_name = $(this).parent().text();

      var project_id = $(this).next().find('.prj_id_rvw').text();

      var project_id_tbl_row = $(this).parent().parent().attr('id');



     // $('input.prjrvw_btn_clck_id').val( $(this).parent().parent().parent().attr('id') );


      $('input.prjrvw_btn_clck_id').val(project_id_tbl_row);



      $('.project_title_rvw').text(project_raw_name);
      $('input.prjrvw_project_id').val(project_id);

      var notes_height = $('.toggle_notes_prjrvw').innerHeight() - 240;

      $('.notes_side_content').css('height',notes_height+'px');

      var is_needed_review =  $(this).parent().parent().attr('class');


      if ( $(this).parent().parent().is( ".needed_rev" ) ) {

 
   
        $("input.no_updates_checkbox").prop("checked", false);
        $('.no_updates_btn').show().attr('id',project_id);
    //    $('.completed_review_btn').show().attr('id',project_id);
      }else{

        $("input.no_updates_checkbox").prop("checked", true);
    //    $('.completed_review_btn').show().attr('id',project_id);
        $('.no_updates_btn').hide();
      }
  

 


    //  alert( $(this).attr('class') );
    //  $('.toggle_notes_prjrvw').toggle();




 //   $(this).find('i').addClass('fa-spin'); !!!!!!!!!!!!!!!!1

/*
    setTimeout(function(){   
      $('.proj_comments_search_bttn').find('i').removeClass('fa-spin');
    },1000);
*/

   // var prjc_project_id = $('select#prjc_project_id').val();
    $('.notes_side_content').empty().append('<div class="notes_line no_posted_comment"><p><i class="fa fa-cog fa-spin"></i> Loading...</p></div>');

    $.post(baseurl+"projects/list_project_comments",{ 'project_id': project_id, 'is_prj_rvw': '1'  },function(result){    
      if(result == 'Error'){
        $('.notes_side_form').hide();
        setTimeout(function(){   
          $('.notes_side_content').empty().append('<div class="notes_line no_posted_comment"><p>Project Not Found!</p></div>');
        },1000);
      }else{
        setTimeout(function(){   
          $('.notes_side_form').show();         
          $('.notes_side_content').empty().append(result);
        },1000);
      }    
    });
  });






$('.proj_rvw_reload_bttn').click(function(){



  var project_id = $('input.prjrvw_project_id').val();
  var target_btn = $('input.prjrvw_btn_clck_id').val();


  $('textarea#notes_comment_text').val('');

  $(this).find('i').addClass('fa-spin');

  setTimeout(function(){   
    $('.proj_rvw_reload_bttn').find('i').removeClass('fa-spin');
  },1000);

  $('tr#'+target_btn+' i.view_notes_prjrvw').trigger('click');


});


</script>

 

<div class="po_legend hide">
  <p class="pad-top-5 m-left-10"> &nbsp;  &nbsp; <span class="ex-gst">Ex GST</span> &nbsp;  &nbsp; <span class="inc-gst">Inc GST</span></p>
</div>



 

<script type="text/javascript">
  

  $('#progress_claim_srch_rec').keypress(function(event){


    reCountWip('prj_wip_rvw',5,7);



    setTimeout(function(){    reCountWip('prj_qut_rvw',5,7);   },200);


    setTimeout(function(){   reCountWip('un_invoiced_rvw',7,0);    },400);


    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
      //alert('You pressed a "enter" key in textbox');  
      //$('.srch_btn_prgs_c_rec').trigger('click');
    }
    event.stopPropagation();



  });


</script>

<script type="text/javascript">
  $('button.zero_payment').click(function(){
    $('input#amount_ext_gst').val('0.00');
    $('input#amount_inc_gst').val('0.00');
    $('#is_invoice_paid_check').prop('checked', true);
  });

 

//prj_wip_rvw




       reCountWip('prj_wip_rvw',5,7);
     
      
 setTimeout(function(){    reCountWip('prj_qut_rvw',5,7);   },100);

 setTimeout(function(){   reCountWip('un_invoiced_rvw',7,0);    },200);

// setTimeout(function(){   reCountWip('un_invoiced_rvw');    },200);


//$(".location table tbody tr td:nth-child(2)").addClass("black");


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

  .notes_line.user_postby_ian, .notes_line.user_postby_ian small {
    background-color: #ed9b26;
    color: #fff !important;
  }

  .notes_line.user_postby_ian{
    padding: 5px;
  }

  .notes_line.comment_type_0{
    background: none !important;
  }

</style>
<?php $this->bulletin_board->list_latest_post(); ?>
<?php $this->load->view('assets/logout-modal'); ?>