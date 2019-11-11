<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('client_supply'); ?>
<?php $this->load->model('client_supply_m'); ?> 
<?php $this->load->module('bulletin_board'); ?>

<?php $this->load->module('projects'); ?>
<?php $this->load->model('projects_m'); ?>
<?php $date_today = date('d/m/Y'); ?>

<?php $custom =  " AND  `project`.`is_paid` = '0' AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) > UNIX_TIMESTAMP( STR_TO_DATE('".$date_today."', '%d/%m/%Y') )  "; ?>
<?php $projects_q = $this->projects_m->display_all_projects($custom); ?>

<?php $supply_list_q = $this->client_supply_m->list_client_supply(); ?>
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
         



        <ul id="myTab" class="nav nav-tabs pull-right">
<li>
            <a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Home</a>
          </li>


        <li class="">
            <a href="#edit_supply" data-toggle="tab" class="edit_supply_tab" style="display:none;"><i class="fa fa-list fa-lg"></i> Supply Details</a>
          </li> 


        <?php if(@$this->session->flashdata('error_add')): ?>

          <li class="" >
            <a href="#supply_list" class="default_nav_btn" data-toggle="tab"><i class="fa fa-table fa-lg"></i> Supply List</a>
          </li>



        <?php if($this->session->userdata('client_supply') ==  2): ?>
                  <li class="active">
                    <a href="#new_supply" class="default_nav_btn" data-toggle="tab" class="new_supply_tab"><i class="fa fa-plus-square fa-lg"></i> New Supply</a>
                  </li>
        <?php endif; ?>



        <?php else: ?>

          <li class="active" >
            <a href="#supply_list" class="default_nav_btn" data-toggle="tab"><i class="fa fa-table fa-lg"></i> Supply List</a>
          </li>



                  <?php if($this->session->userdata('client_supply') ==  2): ?>
                    <li class="">
                      <a href="#new_supply" class="default_nav_btn" data-toggle="tab" class="new_supply_tab"><i class="fa fa-plus-square fa-lg"></i> New Supply</a>
                    </li> 
                  <?php endif; ?>

        <?php endif; ?>



        </ul>

        <style type="text/css"> ul#myTab li.active a  { color: #fff !important; border: 1px solid #fff;border-bottom: 0px !important; } </style>
 
      </div>

    </div>
  </div>
</div>
<!-- title bar -->

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
                      <?php echo $this->session->flashdata('success_add');?>
                    </div>
                  </div>
                <?php endif; ?>

 
                <div class="pad-10 no-pad-t hide">
                  <div class="border-less-box alert alert-success fade in pad-10">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <p>New client supply added!<?php echo $this->session->flashdata('success_add');?></p>
                  </div>
                </div>

                <?php if(@$this->session->flashdata('error_add')): ?>
                  <div class="pad-10 no-pad-t">
                    <div class="border-less-box alert alert-danger fade in pad-10">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                      <p><?php echo $this->session->flashdata('error_add'); ?></p>
                    </div>
                  </div>
                <?php endif; ?>                
 

                <div class="row clearfix">
                    <div class="col-lg-6">
                      <div class="box-head pad-left-15 clearfix">
                        <label><?php echo $screen; ?></label>
                        <div id="aread_test"></div>
                      </div>
                    </div>
                    <div id="" class="col-lg-6">
                        <button id="" class="btn btn-danger pointer pull-right btn-sm delete_supply_btn" style="display:none;">Delete Supply</button>                      
                    </div>
                </div>

                <div class="box-area">

                  <div class="box-tabs m-bottom-15">
                    <div class="tab-content">




<?php if(@$this->session->flashdata('error_add')): ?>
                      <div class="tab-pane  clearfix" id="supply_list">
<?php else: ?>
                      <div class="tab-pane  clearfix active" id="supply_list">
<?php endif; ?>
                        <div class="m-bottom-15 clearfix">
                          <div class="box-area tble-cstm-a cstm_filter_set" >



                            <table id="dataTable_client_supply" class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size: 14px !important;">
                              <thead><tr><th class="hide">id</th><th>Project</th><th>Client</th><th>Supply Name</th><th>Warehouse</th><th>Outbound</th><th></th> </tr></thead>
                              <tbody>                              

                                <?php foreach ($supply_list_q->result() as $supply): ?>

                                <?php $status_late = ($supply->unix_dlvy_dt < strtotime(date('Y-m-d')) ? 'late_delv' : '');  ?>

                                <?php $status_late = ($supply->is_delivered_date  != '' ? 'is_supply_delivrd' : $status_late ); ?>



                                  
                                <tr class="<?php echo $status_late; ?>">
                                  <td class="hide"><?php echo $supply->unix_dlvy_dt; ?> <?php echo $supply->project_name; ?></td>
                                  <td><a href="<?php echo base_url(); ?>projects/view/<?php echo $supply->project_id; ?>" target="_blank"><?php echo $supply->project_id; ?></a></td>
                                  <td><?php echo $supply->company_name; ?>



                                   </td>
                                  


                                  <?php if($this->session->userdata('client_supply') ==  2): ?>
                                    <td><a href="#" class="pointer view_edit_supply" id="<?php echo $supply->client_supply_id; ?>"> <?php echo $supply->supply_name; ?></a></td>
                                  <?php else: ?>


                                    <td><strong><?php echo $supply->supply_name; ?></strong></td>

                                  <?php endif; ?>


                                  <td><?php echo $supply->warehouse; ?></td>
                                  <td><?php echo $supply->delivery_date; ?></td>

                                  <?php if( $supply->photos != '' ): ?>
                                    <td> <em class="fa fa-photo fa-lg  view_img pointer pad-3" id="set_img_<?php echo $supply->client_supply_id; ?>" style="color:#35a239;"  ></em> </td>
                                  <?php else: ?>
                                    <td> 


        <?php if($this->session->userdata('client_supply') ==  2): ?>
                                    <em class="fa fa-cloud-upload upload_img fa-lg pointer pad-3" style="color:#3f51b5;" id="<?php echo $supply->client_supply_id; ?>" ></em>
<?php endif; ?>


                                     </td>
                                  <?php endif; ?>
                                </tr>

                                <?php endforeach; ?>

                              </tbody>
                            </table>

                          </div>
                        </div>
                      </div>


                      <script type="text/javascript">
                        setTimeout(function(){
$('.cstm_filter_set #dataTable_client_supply_filter label').prepend('<select class="form-control pull-right m-left-10 supply_tbl_sort" onChange="client_supply_tble(this.value)" style="height: 30px;"><option value="" disabled>Sort Site Delivery</option><option selected="" value="1">Delivery Date ASC</option> <option value="2">Delivery Date DESC</option>    </select>    ');
                        


$('table#dataTable_client_supply th').click(function(){
  $('select.supply_tbl_sort').val('');
});
                        },2000);
                      </script>



                      <div style="display:none;"  >

                       <?php foreach ($supply_list_q->result() as $supply): ?>
                        <?php $photos_arr = explode(',', $supply->photos); ?>

                        <?php foreach ($photos_arr as $key => $image): ?>
                          <a href="<?php echo base_url(); ?>docs/client_supply/<?php echo $image; ?>" class="lb_<?php echo $supply->client_supply_id; ?>" id="<?php echo str_replace('.','_', $image); ?>" data-lightbox="<?php echo $supply->client_supply_id; ?>"  data-title="<?php echo $supply->supply_name.' - '.$supply->warehouse; ?>">img</a>
                        <?php endforeach; ?>


                        <script type="text/javascript">
                          $('#set_img_<?php echo $supply->client_supply_id; ?>').click(function(){
                            $('a.lb_<?php echo $supply->client_supply_id; ?>:first').trigger('click');
                          });
                        </script>

                      <?php endforeach; ?>

                    </div>

                      <style type="text/css">
                        .tble-cstm-a .row{
                          padding: 0 5px !important;
                        }
                      </style>

                      <script type="text/javascript">
                        $('.upload_img').click(function(){
                          var client_supply_id = $(this).attr('id');
                          $('input#add_supply_photos').trigger('click');
                          $('input#client_supply_id').val(client_supply_id);


                        });


 


                        function uploadConfirm () {

                          $('#loading_modal').modal({"backdrop": "static", "show" : true} );
                          $('form#added_photos').submit();
                        }


                      </script>
                     


<?php if(@$this->session->flashdata('error_add')): ?>
                      <div class="tab-pane  clearfix active" id="new_supply">
<?php else: ?>
                      <div class="tab-pane  clearfix " id="new_supply">
<?php endif; ?>



 
                        <div class="m-bottom-15 clearfix">
                          <div class="box-area" style="margin: 0 -5px;">


                            <form method="post" id="added_photos" action="<?php echo base_url(); ?>client_supply/upload_photos" class="hide" enctype="multipart/form-data">
                              <input type="hidden" name="client_supply_id" id="client_supply_id">
                              <input type="file" multiple="multiple" name="supply_photos[]" onChange='uploadConfirm()' autocomplete="off" id="add_supply_photos" class="form-control pad-5" style="color:#FFF;">
                            </form>

    
    <form method="post" action="<?php echo base_url(); ?>client_supply/process_form_supply" id="" enctype="multipart/form-data">


   <?php foreach ($projects_q->result_array() as $project_info): ?>


<?php //var_dump($project_info); ?>

<?php //echo "<p>----------------</p>"; ?>

   <?php endforeach; ?>

                            <div class="col-sm-6">

                             <span><strong class="pad-5" style="font-size: 18px;">Inbound</strong></span>
                              <div class="input-group m-bottom-10  ">
                                <span id="" class="input-group-addon"> New Supply </span>
                                <input type="text" placeholder="Supply Name" required name="supply_name" id="supply_name" value="" autocomplete="off" class="form-control  "  maxlength="35" tabindex="1" style="z-index: 0 !important;">
                                <span class="input-group-addon"> 35 Chars Max</span>
                              </div> 

 


                              <div class="input-group m-bottom-10">
                                <span id="" class="input-group-addon"><i class="fa fa-briefcase"></i> Project Number </span>
                                <select name="project_data" class="form-control chosen projects_set_a" id="select_project" required>
                                  <option value="" selected="selected" >Select Project</option>
                                  <?php foreach ($projects_q->result_array() as $project_info): ?>
                                    <?php echo'<option value="'.$project_info['project_id'].'_'.$project_info['client_id'].'_'.$project_info['date_site_finish'].'">'.$project_info['project_id'].' '.$project_info['project_name'].'</option>'; ?>
                                  <?php endforeach;   ?>
                                </select>    
                              </div>  



                              <div class="input-group m-bottom-10">
                                <span id="" class="input-group-addon"><i class="fa fa-calendar"></i>  Delivery Date To Warehouse </span>
                                <input type="text"  placeholder="DD/MM/YYYY" id="date_goods_expected" name="date_goods_expected" value="" autocomplete="off" class="form-control " style="z-index: 0 !important;">
                               
                              </div>  
 
                              <div class="input-group m-bottom-10">
                                <span id="" class="input-group-addon"><i class="fa fa-calendar"></i>  Date Goods Arrived </span>
                                <input type="text"  placeholder="DD/MM/YYYY" id="date_goods_arrived" name="date_goods_arrived" value="" autocomplete="off" class="form-control " style="z-index: 0 !important;">
                               
                              </div>  
 

 
                              <div class="input-group m-bottom-10">
                                <span id="" class="input-group-addon"><i class="fa fa-calculator "></i> Qty </span>
                                <input type="text"  placeholder="Qty" name="qty" id="qty" value="1" autocomplete="off" class="form-control  " style="z-index: 0 !important;">
                              </div>
  

                              
                              <div id="" class=""><p><br /><hr /></p></div>


                             <span><strong class="pad-5" style="font-size: 18px;">Outbound</strong></span>
 
                              <div class="input-group m-bottom-10">
                                <span id="" class="input-group-addon"><i class="fa fa-truck"></i> Delivered By </span>
                                <select name="delivered_by" class="form-control" >
                                  <option value="Focus">Focus</option> 
                                  <option value="Courier">Courier</option> 
                                  <option value="Other">Other</option>
                                </select>
                              </div>     
 
                              <div class="input-group m-bottom-10">
                                <span id="" class="input-group-addon"><i class="fa fa-user"></i> To be Advised </span>
                                <select name="to_be_advised" class="form-control">
                                  <option value="Client">Client</option> 
                                  <option value="Unknown">Unknown</option> 
                                  <option value="Other">Other</option>
                                </select>
                              </div>    



                                      <div class="input-group tltip_adjt  m-bottom-10">
                                <span id="" class="input-group-addon"><i class="fa fa-calendar-check-o"></i> Delivery  To Site Date  </span>
                                <input type="text"  placeholder="DD/MM/YYYY" id="delivery_date" name="delivery_date" value="" autocomplete="off" class="form-control datepicker_shipping" style="z-index: 0 !important;">
                               
                              </div>
 

                              <div class="input-group m-bottom-10">
                                <span id="" class="input-group-addon"><i class="fa fa-question-circle "></i> Delivered Directly To Site </span>
                                <select name="is_deliver_to_site_select"  class="form-control is_deliver_to_site_select"><option  selected="selected" value="1">Yes</option> <option value="0">  No </option></select>
                              </div>


  


                              <div class="input-group m-bottom-10 set_address_select" style="display:none;">
                                <span id="" class="input-group-addon"><i class="fa fa-address-book"></i> Address</span>
                                <input type="text" placeholder="Address" name="set_address" value="" autocomplete="off" class="form-control  " style="z-index: 0 !important;">
                               
                              </div>  



 
                            </div>

                            <div class="col-sm-6">


 

                            <div id="" class="clearfix"></div>

                             <span><strong class="pad-5" style="font-size: 18px;">Goods Located</strong></span>

                             <input type="hidden" name="warehouse_selected" id="warehouse_selected">

                            <div id="" class="clearfix"></div>
 




                            <div class=" "> 




                              <div class="input-group m-bottom-10">
                                <span id="" class="input-group-addon"><i class="fa fa-question-circle "></i> Choose Warehouse </span>


                                <select required="1" name="warehouse_area" id="warehouse_area"  class="form-control">
                                  <option selected="selected" value="0" disabled="">Select </option> 
                                  <option value="2">WA - Warehouse</option>
                                  <option value="3">NSW - Warehouse</option>
                                  <option value="Other">Other</option>
                                  <option value="Un-Allocated">Un-Allocated</option>
                                </select>

                              </div>

                              <div class="  pad-5"> 

                                <p class="pull-left set_warehouse_selected"><strong class="m-bottom-10 block   pad-5">Please Select Warehouse &nbsp; </strong></p><span class="warehouse_set_value"></span>



                              </div>


<div id="" class="clearfix"><br /></div>

                             <hr style="margin: 5px 0px 10px;">

   <div class="input-group pad-left-5 pad-right-5 m-bottom-10">
                                  <span class="input-group-addon" style="    text-align: left;"><i class="fa fa-file-photo-o"></i> Photos</span>
                                  
                                  <input type="file" multiple="multiple" name="supply_photos[]" autocomplete="off" id="supply_photos" class="form-control pad-5 supply_photos" style="color:#FFF;">
                                    
                                </div>
                                
<div id="" class=" pad-5 selected_to_upload"><p class=" m-3"><strong>No files selected</strong></p></div>






                            </div>
                           
                           





                              <div id="" class="" style="   margin-top: -10px;"><br /><p><hr /></p><br /></div>
 



                            <div id="" class="clearfix"></div>

                            <div id="" class="pad-5">

                              <div class="box m-top-5" style="border: 1px solid #cccccc;border-radius: 5px;overflow: hidden;">
                                <div class="box-head pad-5" style="border-bottom: 1px solid #cccccc;    background: #eee;    padding: 10px 12px 9px;">
                                 <span style="font-size: 14px;"> <i class="fa fa-pencil-square-o"></i> Description </span>
                               </div>

                               <div class="box-area pad-5 clearfix">
                                <div class="clearfix ">
                                  <div class="">
                                    <textarea class="form-control" id="project_notes" rows="20" tabindex="30" name="description" placeholder="Description" style="resize: vertical; margin-top: 0px; margin-bottom: 0px; min-height:100px; height: 150px;"></textarea>
                                  </div>
                                </div>
                              </div>
                            </div>

                          </div>


                          <button type="submit" class="btn pull-right btn-success m-5"><em class="fa fa-floppy-o"></em> Save Supply</button>
                      



                              <style type="text/css">.tltip_adjt .tooltip,.tltip_adjt .tooltip .tooltip-inner{ max-width: 380px !important; width: 380px !important; }</style>

                            </div>



                            <div id="" class="clearfix"></div>

                           





</form>

                          </div>
                        </div>
                      </div>
<!-- Edit Supply -->



<div class="tab-pane  clearfix " id="edit_supply">


    <form method="post" action="<?php echo base_url(); ?>client_supply/update_form_supply" id="" enctype="multipart/form-data">

 
                        <div class="m-bottom-15 clearfix">
                          <div class="box-area" style="margin: 0 -5px;">





   



   
                            <div class="col-sm-6">

                             <span><strong class="pad-5" style="font-size: 18px;">Inbound</strong></span>
                            

                             <div class="input-group m-bottom-10  ">
                                <span id="" class="input-group-addon"> Supply Name </span>
                                <input type="text" placeholder="Supply Name"  name="supply_name" id="supply_name" value="" autocomplete="off" class="form-control"  maxlength="35" tabindex="1" style="z-index: 0 !important;">
                                <span class="input-group-addon"> 35 Chars Max</span>
                                <input type="hidden" name="supply_data_id" id="supply_data_id">
                             
                              </div> 

 


                              <div class="input-group m-bottom-10">
                                <span id="" class="input-group-addon"><i class="fa fa-briefcase"></i> Project Number: <span class="updtset_prj_id"></span></span>
                                <select name="project_id" class="form-control chosen projects_set_b" id="select_project" >
                                  <option value="" selected="selected" >Change Project Number</option>
                                  <?php foreach ($projects_q->result_array() as $project_info): ?>
                                    <?php echo'<option value="'.$project_info['project_id'].'_'.$project_info['client_id'].'_'.$project_info['date_site_finish'].'">'.$project_info['project_id'].' '.$project_info['project_name'].'</option>'; ?>
                                  <?php endforeach;   ?>
                                </select>    
                              </div>  

                              <input type="hidden" name="init_project_id" id="init_project_id" >


                              <div class="input-group m-bottom-10">
                                <span id="" class="input-group-addon"><i class="fa fa-calendar"></i>  Delivery Date To Warehouse </span>
                                <input type="text"  placeholder="DD/MM/YYYY" id="date_goods_expected" name="date_goods_expected" value="" autocomplete="off" class="form-control" style="z-index: 0 !important;">
                               
                              </div>  
 
                              <div class="input-group m-bottom-10">
                                <span id="" class="input-group-addon"><i class="fa fa-calendar"></i>  Date Goods Arrived </span>
                                <input type="text"  placeholder="DD/MM/YYYY" id="date_goods_arrived" name="date_goods_arrived" value="" autocomplete="off" class="form-control" style="z-index: 0 !important;">
                               
                              </div>  

 
                              <div class="input-group m-bottom-10">
                                <span id="" class="input-group-addon"><i class="fa fa-calculator "></i> Qty </span>
                                <input type="text" placeholder="Qty" name="qty" id="qty" value="1" autocomplete="off" class="form-control  " style="z-index: 0 !important;">
                              </div>
  

                              
                              <div id="" class=""><p><br></p><hr><p></p></div>


                             <span><strong class="pad-5" style="font-size: 18px;">Outbound</strong></span>
 
                              <div class="input-group m-bottom-10">
                                <span id="" class="input-group-addon"><i class="fa fa-truck"></i> Delivered By </span>
                                <select name="delivered_by" id="delivered_by" class="form-control">
                                  <option value="Focus">Focus</option> 
                                  <option value="Courier">Courier</option> 
                                  <option value="Other">Other</option>
                                </select>
                              </div>     
 
                              <div class="input-group m-bottom-10">
                                <span id="" class="input-group-addon"><i class="fa fa-user"></i> To be Advised </span>
                                <select name="to_be_advised" id="to_be_advised" class="form-control">
                                  <option value="Client">Client</option> 
                                  <option value="Unknown">Unknown</option> 
                                  <option value="Other">Other</option>
                                </select>
                              </div>    



                   
                                      <div class="input-group tltip_adjt  m-bottom-10">
                                <span id="" class="input-group-addon"><i class="fa fa-calendar-check-o"></i> Delivery  To Site Date  </span>
                                <input type="text"  placeholder="DD/MM/YYYY" id="delivery_date" name="delivery_date" value="" autocomplete="off" class="form-control datepicker_shipping" style="z-index: 0 !important;">
                               
                              </div>
 

                              <div class="input-group m-bottom-10">
                                <span id="" class="input-group-addon"><i class="fa fa-question-circle "></i> Delivered Directly To Site </span>
                                <select name="is_deliver_to_site_select" class="form-control is_deliver_to_site_select"><option selected="selected" value="1">Yes</option> <option value="0">  No </option></select>
                              </div>


  


                              <div class="input-group m-bottom-10 set_address_select" style="display:none;">
                                <span id="" class="input-group-addon"><i class="fa fa-address-book"></i> Address</span>
                                <input type="text" placeholder="Address" name="set_address" id="set_address" value="" autocomplete="off" class="form-control  " style="z-index: 0 !important;">
                               
                              </div>  

<p><hr /></p>





<div class="btn pull-left btn-info set_as_delivered" onclick="set_as_delivered()" id=""><em class="fa fa-truck"></em> Set As Delivered</div>

<div class="input-group m-bottom-10 is_delevered_ok">
  <span id="" class="input-group-addon  "><i class="fa fa-truck "></i> Delivered: <strong class="date_is_delivered" ></strong> </span>
</div>


                            </div>

                            <div class="col-sm-6">


 

                            <div id="" class="clearfix"></div>

                             <span><strong class="pad-5" style="font-size: 18px;">Goods Located</strong></span>

                             <input type="hidden" name="ups_warehouse_selected" id="warehouse_selected">

                            <div id="" class="clearfix"></div>
 




                            <div class=" "> 




                              <div class="input-group m-bottom-10">
                                <span id="" class="input-group-addon"><i class="fa fa-question-circle "></i> Choose Warehouse </span>


                                <select  name="warehouse_area" id="warehouse_area" class="form-control">
                                  <option selected="selected" value="0" disabled="">Select </option> 
                                  <option value="2">WA - Warehouse</option>
                                  <option value="3">NSW - Warehouse</option>
                                  <option value="Other">Other</option>
                                  <option value="Un-Allocated">Un-Allocated</option>
                                </select>

                              </div>

                              <div class="  pad-5"> 

                                <p class="pull-left set_warehouse_selected"><strong class="m-bottom-10 block   pad-5">Please Select Warehouse &nbsp; </strong></p><span class="warehouse_set_value"></span>



                              </div>


<div id="" class="clearfix"><br></div>

                             <hr style="margin: 5px 0px 10px;">

   <div class="input-group pad-left-5 pad-right-5 m-bottom-10">
                                  <span class="input-group-addon" style="    text-align: left;"><i class="fa fa-file-photo-o"></i> Photos</span>
                                  
                                  <input type="file" multiple="multiple" name="supply_photos[]" autocomplete="off" id="update_supply_photos" class="form-control pad-5 update_supply_photos" style="color:#FFF;">


                                    
                                </div>
                                
<div id="" class=" pad-5 selected_to_upload"><p class=" m-3"><strong>No files selected</strong></p></div>






                            </div>
                           



                            <div id="" class="clearfix"></div>



                              <div id="" class="" style=""><hr /></div>
 



                            <div id="" class="pad-5">

                              <div class="box m-top-5" style="border: 1px solid #cccccc;border-radius: 5px;overflow: hidden;">
                                <div class="box-head pad-5" style="border-bottom: 1px solid #cccccc;    background: #eee;    padding: 10px 12px 9px;">
                                 <span style="font-size: 14px;"> <i class="fa fa-pencil-square-o"></i> Description </span>
                               </div>

                               <div class="box-area pad-5 clearfix">
                                <div class="clearfix ">
                                  <div class="">
                                    <textarea class="form-control" id="project_notes" rows="20" tabindex="30" name="description" placeholder="Description" style="resize: vertical; margin-top: 0px; margin-bottom: 0px; min-height:100px; height: 150px;"></textarea>
                                  </div>
                                </div>
                              </div>
                            </div>

                          </div>



                          <button type="submit" class="btn pull-right btn-success m-5"><em class="fa fa-floppy-o"></em> Submit Update Supply</button>
                      

 

                            </div>



                            <div id="" class="clearfix"></div>

                           



                          </div>
                        </div>
</form>
                      </div>







<!-- Edit Supply -->
                      <p><br /></p>
                      <p><br /></p>
                      <p><br /></p>
                      <p><br /></p>
                      <p><br /></p>
                      <p><br /></p>
                      <p><br /></p>
                      <p><br /></p>
                      <p><br /></p>

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

 

                      <p><br /></p>

                      <p><br /></p>

</div>

    

<script type="text/javascript">

  $('select#warehouse_area').on("change", function(e) {
    var area = $(this).val();

    if(area == '2'){
      $('#wa_warehouse_setup').modal('show');
    }else if(area == '3'){
      $('#nsw_warehouse_setup').modal('show');
    }else{

      $('.set_warehouse_selected').html(' <p class="pull-left    pad-5"><strong>Selected Warehouse &nbsp; </strong></p> <span class="badge badge-info  btn-info pad-5 warehouse_set_value pointer">'+area+'</span> &nbsp; <span class="badge badge-info m-top-3 btn-warning pointer remove_set_wh" style="padding: 5px 8px;"><em class="fa fa-trash"></em></span>');

      $('input#warehouse_selected').val(area);

      $('.remove_set_wh').click(function(){
        $(this).remove();
        $('.warehouse_set_value').remove();
        $('select#warehouse_area').val('0');
        $('.set_warehouse_selected').html('<strong class="m-bottom-10   pad-5 block">Please Select Warehouse &nbsp; </strong>');
      });



    }

  });


  $('select.is_deliver_to_site_select').on("change", function(e) {
    var is_deliver_to_site_select = $(this).val();



    if(is_deliver_to_site_select == 1){
      $('.set_address_select').hide();
    }else{
      $('.set_address_select').show();

    }

  });



function set_as_delivered(){

var supply_data_id = $('#edit_supply input#supply_data_id').val();
 
 window.location = '<?php echo base_url(); ?>client_supply/set_as_delivered/'+supply_data_id;



}


  $('.view_edit_supply').click(function(){

    var supply_id = $(this).attr('id');




  $.ajax({
   'url' : "<?php echo base_url(); ?>client_supply/view_supply/"+supply_id,
   'type' : 'POST',
   'dataType': 'html',
   'success' : function(result){
      var supply_data = result.split("|");

      $('#edit_supply input#supply_name').val(supply_data[1]);
      $('.updtset_prj_id').html('<strong>'+supply_data[2]+'</strong>');
      $('#edit_supply  input#init_project_id').val(supply_data[2]);

  //$('div.projects_set_b a.select2-choice').trigger('click');


    //  .val(supply_data[2]);


    $('#edit_supply input#supply_data_id').val(supply_data[0]);


      $('#edit_supply input#qty').val(supply_data[4]);


      $('#edit_supply select#delivered_by').val(supply_data[7]);
      $('#edit_supply select#to_be_advised').val(supply_data[8]);
      $('#edit_supply select.is_deliver_to_site_select').val(supply_data[10]);

      if(supply_data[10] > 0){
        $('#edit_supply .set_address_select').hide();
        $('#edit_supply .set_address_select input#set_address').val('');
      }else{
        $('#edit_supply .set_address_select').show();
        $('#edit_supply .set_address_select input#set_address').val(supply_data[11]);

      }

var option_set = '';

    $("#edit_supply select.projects_set_b option").each(function(i){
    //  alert($(this).text() + " : " + $(this).val());
    var option_text = $(this).text();
      if( option_text.indexOf(supply_data[2]) !== -1 ){
//        alert( $(this).val() );
        option_set = $(this).val();



    var prj_date = option_set.split("_");
    var limit_date = prj_date[2];
    var startdate = limit_date;
    var new_date = moment(startdate, "DD/MM/YYYY");

    new_date.add(<?php echo 2+($static_data['weeks_delivery']*7) ; ?>, 'days');

    $('#edit_supply .datepicker_shipping').datetimepicker({format: 'DD/MM/YYYY',useCurrent: false}).val('');
    $('#edit_supply .datepicker_shipping').data("DateTimePicker").maxDate(new_date);



    $('#edit_supply input#date_goods_expected').datetimepicker({format: 'DD/MM/YYYY',useCurrent: false}).val('');
    $('#edit_supply input#date_goods_expected').data("DateTimePicker").maxDate(new_date);

    $('#edit_supply input#date_goods_arrived').datetimepicker({format: 'DD/MM/YYYY',useCurrent: false}).val('');
    $('#edit_supply input#date_goods_arrived').data("DateTimePicker").maxDate(new_date);



      $('#edit_supply input#delivery_date').val(supply_data[9]);
      $('#edit_supply input#date_goods_expected').val(supply_data[5]);
      $('#edit_supply input#date_goods_arrived').val(supply_data[6]);

    //  alert(supply_data[9]+' ___ '+supply_data[5]+' ___ '+supply_data[6]+' ___ ');



      }
    });



      if(supply_data[12] != ''){
        $('#edit_supply .selected_to_upload').html('<p class=" m-3"><strong>Uploaded Photos</strong></p>');


        var uploadedPhotosArr = supply_data[12].split(',');


        var arrayLength = uploadedPhotosArr.length;
        for (var i = 0; i < arrayLength; i++) {

         $('#edit_supply .selected_to_upload').append('<a href="<?php echo base_url(); ?>docs/client_supply/'+uploadedPhotosArr[i]+'" style="" class=" vw_'+supply_data[2]+'" data-lightbox="vw_'+supply_data[2]+'"  data-title="'+supply_data[1]+' - '+supply_data[14]+'"><img class="img-thumbnail rounded m-right-10 m-bottom-10"  width="150" height="150"  src="<?php echo base_url(); ?>docs/client_supply/'+uploadedPhotosArr[i]+'" /></a>' );



         $('#edit_supply .selected_to_upload').append('<em class="fa fa-times-circle fa-lg del_ups_photos pointer" onClick="del_ups_photos(this)" id="'+uploadedPhotosArr[i]+'" style="color: red; margin: -4px 16px 10px -20px; top: 30px; display: inline-block; font-size: 24px; text-shadow: #999 -1px 1px 1px; vertical-align: top;"></em>');

       }







      }else{
        $('#edit_supply .selected_to_upload').html('<p class=" m-3 no_pix_uploaded"><strong>No photos uploaded</strong></p>');
      }


      $('#edit_supply textarea#project_notes').val(supply_data[13]);

      if(supply_data[14] != ''){
        $('#edit_supply  .set_warehouse_selected').html('<p class="pull-left   pad-5 "><strong>Selected Warehouse &nbsp; </strong></p><span class="badge badge-info  btn-info pad-5 warehouse_set_value pointer">'+supply_data[14]+'</span>');
      }

      $('#edit_supply input#warehouse_selected').val(supply_data[14]);


      if(supply_data[16] == ''){
        $('.set_as_delivered').show();
        $('.is_delevered_ok').hide();

      }else{

        $('.set_as_delivered').hide();
        $('.is_delevered_ok').show();
        $('.is_delevered_ok .date_is_delivered').text(supply_data[16]);

      }



/*



    echo $supply_data['client_supply_id'].'|';
    echo $supply_data['supply_name'].'|';
    echo $supply_data['project_id'].'|';
    echo $supply_data['client_id'].'|';
    echo $supply_data['quantity'].'|';
    echo $supply_data['date_goods_expected'].'|';
    echo $supply_data['date_goods_arrived'].'|';
    echo $supply_data['delivered_by'].'|';
    echo $supply_data['to_be_advised'].'|';
    echo $supply_data['delivery_date'].'|';
    echo $supply_data['is_deliver_to_site'].'|';
    echo $supply_data['address'].'|';
    echo $supply_data['photos'].'|';
    echo $supply_data['description'].'|';
    echo $supply_data['warehouse'].'|';
    echo $supply_data['is_active'];



*/

      

      


//*****


  //    projects_set_b
 

    }
  });






    $('#loading_modal').modal({"backdrop": "static", "show" : true} );

    setTimeout(function(){
      $('#loading_modal').modal('hide');
      $('a.edit_supply_tab').show().trigger('click');
      $('button.delete_supply_btn').attr('id',supply_id).show();

    },2000);

//edit_supply







  });


  $('.default_nav_btn').click(function(){
    $('a.edit_supply_tab').hide();
    $('button.delete_supply_btn').hide();
  });
  


$('.delete_supply_btn').click(function(){
  var id = $(this).attr('id');
  window.location = '<?php echo base_url(); ?>client_supply/delete_supply/'+id;
});

function del_ups_photos(data_photo){


  data_photo.previousSibling.remove();
  data_photo.remove();

var var_elem_id = data_photo.getAttribute("id");

var supply_id = $('input#supply_data_id').val();

var data = supply_id+'|'+var_elem_id;

var project_id = $('.updtset_prj_id').text();

 
 

 var img_id = var_elem_id.replace(".", "_");

$('a#'+img_id).remove();
//$('em#'+data_photo).remove();
//$('#'+var_elem_id).remove();


$.ajax({
  'url' : "<?php echo base_url(); ?>client_supply/del_photo_supply/"+supply_id+'/'+var_elem_id,
 'type' : 'POST',
 'dataType': 'html'});



}

















</script>   

 
<!-- MODAL -->
 <div class="modal fade " id="wa_warehouse_setup" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="margin-top: 20px; overflow: hidden;">
  <div class="modal-dialog" style="width: 1280px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_warehouse_mod" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title msgbox" id="myModalLabel">Focus Shopfit Pty Ltd - Warehouse</h4>
      </div>
      <div class="modal-body">

        <div id="" class="wa_warehouse">

        <div id="" class="dispatch_area rrunit set_wh" data-wh-location="WA - Warehouse Dispatch Area" style="background:#FE4700;">DISPATCH<br />AREA</div>

        <div id="" class="storeroom_area rrunit set_wh" data-wh-location="WA - Warehouse Storeroom" style="background:#FE7400;">STOREROOM</div>
 

          <div id="" class="racking_row_1_bay_1 rrunit level_set" data-bay-level="5" data-wh-location="WA - Warehouse Racking Row 1 Bay 1" style="background:#FED200;"  data-toggle="modal" data-target="#bay_level" tabindex="-1">BAY 1</div>
          <div id="" class="racking_row_1_bay_2 rrunit level_set" data-bay-level="5" data-wh-location="WA - Warehouse Racking Row 1 Bay 2" style="background:#FED200;"  data-toggle="modal" data-target="#bay_level" tabindex="-1">BAY 2</div>
          <div id="" class="racking_row_1_bay_3 rrunit level_set" data-bay-level="5" data-wh-location="WA - Warehouse Racking Row 1 Bay 3" style="background:#FED200;"  data-toggle="modal" data-target="#bay_level" tabindex="-1">BAY 3</div>
          <div id="" class="racking_row_1_bay_4 rrunit level_set" data-bay-level="5" data-wh-location="WA - Warehouse Racking Row 1 Bay 4" style="background:#FED200;"  data-toggle="modal" data-target="#bay_level" tabindex="-1">BAY 4</div>


          
          <div id="" class="racking_row_2_bay_1 rrunit level_set" data-bay-level="5" data-wh-location="WA - Warehouse Racking Row 2 Bay 1" style="background:#00CB00;" data-toggle="modal" data-target="#bay_level" tabindex="-1">BAY 1</div>
          <div id="" class="racking_row_2_bay_2 rrunit level_set" data-bay-level="5" data-wh-location="WA - Warehouse Racking Row 2 Bay 2" style="background:#00CB00;" data-toggle="modal" data-target="#bay_level" tabindex="-1">BAY 2</div>
          <div id="" class="racking_row_2_bay_3 rrunit level_set" data-bay-level="5" data-wh-location="WA - Warehouse Racking Row 2 Bay 3" style="background:#00CB00;" data-toggle="modal" data-target="#bay_level" tabindex="-1">BAY 3</div>
          <div id="" class="racking_row_2_bay_4 rrunit level_set" data-bay-level="6" data-wh-location="WA - Warehouse Racking Row 2 Bay 4" style="background:#00CB00;" data-toggle="modal" data-target="#bay_level" tabindex="-1">BAY 4</div>


          
          <div id="" class="racking_row_3_bay_1 rrunit level_set" data-bay-level="5" data-wh-location="WA - Warehouse Racking Row 3 Bay 1" style="background:#009898;" data-toggle="modal" data-target="#bay_level" tabindex="-1">BAY 1</div>
          <div id="" class="racking_row_3_bay_2 rrunit level_set" data-bay-level="5" data-wh-location="WA - Warehouse Racking Row 3 Bay 2" style="background:#009898;" data-toggle="modal" data-target="#bay_level" tabindex="-1">BAY 2</div>
          <div id="" class="racking_row_3_bay_3 rrunit level_set" data-bay-level="7" data-wh-location="WA - Warehouse Racking Row 3 Bay 3" style="background:#009898;" data-toggle="modal" data-target="#bay_level" tabindex="-1">BAY 3</div>
          <div id="" class="racking_row_3_bay_4 rrunit level_set" data-bay-level="7" data-wh-location="WA - Warehouse Racking Row 3 Bay 4" style="background:#009898;" data-toggle="modal" data-target="#bay_level" tabindex="-1">BAY 4</div>



          
          <div id="" class="racking_row_4_bay_1 rrunit level_set" data-bay-level="7" data-wh-location="WA - Warehouse Racking Row 4 Bay 1" data-toggle="modal" data-target="#bay_level" tabindex="-1">BAY 1</div>
          <div id="" class="racking_row_4_bay_2 rrunit level_set" data-bay-level="7" data-wh-location="WA - Warehouse Racking Row 4 Bay 2" data-toggle="modal" data-target="#bay_level" tabindex="-1">BAY 2</div>
          <div id="" class="racking_row_4_bay_3 rrunit level_set" data-bay-level="7" data-wh-location="WA - Warehouse Racking Row 4 Bay 3" data-toggle="modal" data-target="#bay_level" tabindex="-1">BAY 3</div>
          <div id="" class="racking_row_4_bay_4 rrunit level_set" data-bay-level="7" data-wh-location="WA - Warehouse Racking Row 4 Bay 4" data-toggle="modal" data-target="#bay_level" tabindex="-1">BAY 4</div>


          <div id="" class="mezzanine_storage_unit_1 rrunit  set_wh" data-wh-location="WA - Warehouse Mezzanine Storage Unit 1" style="background:#1240AA; color:#fff; padding: 15px 0px !important;">
            <span>UNIT 1</span>
            <p><?php $this->client_supply->display_client_logo(1); ?></p>
          </div>
          <div id="" class="mezzanine_storage_unit_2 rrunit  set_wh" data-wh-location="WA - Warehouse Mezzanine Storage Unit 2" style="background:#1240AA; color:#fff; padding: 15px 0px !important;">
            <span>UNIT 2</span>
            <?php $this->client_supply->display_client_logo(2); ?>
          </div>
          <div id="" class="mezzanine_storage_unit_3 rrunit  set_wh" data-wh-location="WA - Warehouse Mezzanine Storage Unit 3" style="background:#1240AA; color:#fff; padding: 15px 0px !important;">
            <span>UNIT 3</span>
            <?php //$this->client_supply->display_client_logo(3); ?>
          </div>
          <div id="" class="mezzanine_storage_unit_4 rrunit  set_wh" data-wh-location="WA - Warehouse Mezzanine Storage Unit 4" style="background:#1240AA; color:#fff; padding: 15px 0px !important;">
            <span>UNIT 4</span>
            <?php //$this->client_supply->display_client_logo(4); ?>
          </div>
          <div id="" class="mezzanine_storage_unit_5 rrunit  set_wh" data-wh-location="WA - Warehouse Mezzanine Storage Unit 5" style="background:#1240AA; color:#fff; padding: 15px 0px !important;">
            <span>UNIT 5</span>
            <?php $this->client_supply->display_client_logo(5); ?>
          </div>
          <div id="" class="mezzanine_storage_unit_6 rrunit  set_wh" data-wh-location="WA - Warehouse Mezzanine Storage Unit 6" style="background:#1240AA; color:#fff; padding: 15px 0px !important;">
            <span>UNIT 6</span>
            <?php $this->client_supply->display_client_logo(6); ?>
          </div>


          <div id="" class="mezzanine_storage_unit_7 rrunit  set_wh" data-wh-location="WA - Warehouse Mezzanine Storage Unit 7" style="background:#7109A9; color:#fff; padding: 15px 0px !important;">
            <span>UNIT 7</span>
            <?php //$this->client_supply->display_client_logo(7); ?>
          </div>
          <div id="" class="mezzanine_storage_unit_8 rrunit  set_wh" data-wh-location="WA - Warehouse Mezzanine Storage Unit 8" style="background:#7109A9; color:#fff; padding: 15px 0px !important;">
            <span>UNIT 8</span>
            <?php // $this->client_supply->display_client_logo(8); ?>
          </div>
          <div id="" class="mezzanine_storage_unit_9 rrunit  set_wh" data-wh-location="WA - Warehouse Mezzanine Storage Unit 9" style="background:#7109A9; color:#fff; padding: 15px 0px !important;">
            <span>UNIT 9</span>
            <?php //$this->client_supply->display_client_logo(9); ?>
          </div>
          <div id="" class="mezzanine_storage_unit_10 rrunit  set_wh" data-wh-location="WA - Warehouse Mezzanine Storage Unit 10" style="background:#7109A9; color:#fff; padding: 15px 0px !important;">
            <span>UNIT 10</span>
            <?php //$this->client_supply->display_client_logo(10); ?>
          </div>
          <div id="" class="mezzanine_storage_unit_11 rrunit  set_wh" data-wh-location="WA - Warehouse Mezzanine Storage Unit 11" style="background:#7109A9; color:#fff; padding: 15px 0px !important;">
            <span>UNIT 11</span>
            <?php //$this->client_supply->display_client_logo(11); ?>
          </div>
          <div id="" class="mezzanine_storage_unit_12 rrunit  set_wh" data-wh-location="WA - Warehouse Mezzanine Storage Unit 12" style="background:#7109A9; color:#fff; padding: 15px 0px !important;">
            <span>UNIT 12</span>
            <?php //$this->client_supply->display_client_logo(12); ?>
          </div>

        </div>

      </div>
      <div id="confirmButtons" class="modal-footer"></div>
    </div>
  </div>
</div>


<style type="text/css">

tr.late_delv td {
    background-color: #ffc6c6 !important;
}

tr.is_supply_delivrd td {
    background-color: #bef5be !important;
}




.wa_warehouse{
  background: url('<?php echo base_url(); ?>img/wa_warehouse.png');
    height: 720px;
    background-size: 100%;
    background-repeat: no-repeat;
}

.storeroom_area{
    padding: 50px 0 !important;
    right: 395px;
    top: 367px;
    height: 226px !important;
    width: 127px !important;
}

.dispatch_area{
    padding: 50px 0 !important;
    right: 391px;
    top: 72px;
    height: 221px !important;
    width: 100px !important;
}

.racking_row_1_bay_1 {
  right: 212px;
  top: 200px;
}

.racking_row_1_bay_2 {
  right: 152px;
  top: 200px;
}

.racking_row_1_bay_3 {
  right: 92px;
  top: 200px;
}

.racking_row_1_bay_4 {
  right: 32px;
  top: 200px;
}

.racking_row_2_bay_1{
  right: 212px;
  top: 338px;
}

.racking_row_2_bay_2{
  right: 152px;
  top: 338px;
}

.racking_row_2_bay_3{
  right: 92px;
  top: 338px;
}

.racking_row_2_bay_4{
  right: 32px;
  top: 338px;
}

.racking_row_3_bay_1{
  right: 212px;
  top: 363px;
}

.racking_row_3_bay_2{
  right: 152px;
  top: 363px;
}

.racking_row_3_bay_3{
  right: 92px;
  top: 363px;
}

.racking_row_3_bay_4{
  right: 32px;
  top: 363px;
}

.racking_row_4_bay_1{
  right: 212px;
  top: 501px;
}

.racking_row_4_bay_2{
  right: 152px;
  top: 501px;
}

.racking_row_4_bay_3{
  right: 92px;
  top: 501px;
}

.racking_row_4_bay_4{
  right: 32px;
  top: 501px;
}




.mezzanine_storage_unit_1{
left: 564px;
    top: 496px;
    width: 108px !important;
    height: 176px !important;
    padding: 70px 0 !important;
}

.mezzanine_storage_unit_2{
    left: 458px;
    top: 496px;
    width: 105px !important;
    height: 237px !important;
    padding: 70px 0 !important;
}

.mezzanine_storage_unit_3{
    left: 350px;
    top: 471px;
    width: 107px !important;
    height: 262px !important;
    padding: 95px 0 !important;
}

.mezzanine_storage_unit_4{
    left: 242px;
    top: 471px;
    width: 107px !important;
    height: 262px !important;
    padding: 95px 0 !important;
}

.mezzanine_storage_unit_5{
left: 135px;
    top: 471px;
    width: 106px !important;
    height: 262px !important;
    padding: 95px 0 !important;
}

.mezzanine_storage_unit_6{
    left: 28px;
    top: 471px;
    width: 106px !important;
    height: 262px !important;
    padding: 95px 0 !important;
}

.mezzanine_storage_unit_12{
    left: 485px;
    width: 93px !important;
  top: 247px;
  height: 151px !important;
  padding: 50px 0 !important;
}

.mezzanine_storage_unit_11{
    left: 394px;
 width: 92px !important;
 top: 247px;
 height: 151px !important;
 padding: 50px 0 !important;
}

.mezzanine_storage_unit_10{
    left: 302px;
    width: 93px !important;
 top: 247px;
 height: 151px !important;
 padding: 50px 0 !important;
}

.mezzanine_storage_unit_9{
    left: 209px;
    width: 94px !important;
 top: 247px;
 height: 151px !important;
 padding: 50px 0 !important;
}

.mezzanine_storage_unit_8{
    left: 118px;
 width: 92px !important;
 top: 247px;
 height: 151px !important;
 padding: 50px 0 !important;
}


.mezzanine_storage_unit_7{
    left: 27px;
 width: 92px !important;
 top: 247px;
 height: 151px !important;
 padding: 50px 0 !important;
}

.rrunit{
  padding: 2px;
  text-align: center;
  width: 61px;
  height: 26px;
  border: 1px solid #000;
  position: absolute;
  background: #07e3ff;
}

.rrunit:hover{
  background: red !important;
  opacity: 1 !important;
  cursor: pointer !important;
}



</style>
<!-- MODAL -->
 
<!-- MODAL -->
 <div class="modal fade " id="nsw_warehouse_setup" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="margin-top: 20px; overflow: hidden;">
  <div class="modal-dialog" style="width: 1280px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_warehouse_mod" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title msgbox" id="myModalLabel">Focus Shopfit NSW Pty Ltd - Warehouse</h4>
      </div>
      <div class="modal-body" style="">

        <div id="" class="nsw_warehouse">
 

        <div id="" class="small_items set_wh rrunit" data-wh-location="NSW - Warehouse Small Items" style="background:#afb1ff;">SMALL ITEMS</div>

        <div id="" class="marshalling_area rrunit set_wh" data-wh-location="NSW - Warehouse Marshalling Area" style="background:#F878D7;">MARSHALLING AREA</div>
        <div id="" class="warehouse_floor_area_1 rrunit set_wh" data-wh-location="NSW - Warehouse Floor Area 1" style="background:#4FD0F6;">WAREHOUSE FLOOR AREA 1</div>
        <div id="" class="warehouse_floor_area_2 rrunit set_wh" data-wh-location="NSW - Warehouse Floor Area 2" style="background:#4FD0F6;">WAREHOUSE FLOOR AREA 2</div>
        <div id="" class="warehouse_floor_area_3 rrunit set_wh" data-wh-location="NSW - Warehouse Floor Area 3" style="background:#4FD0F6;">WAREHOUSE FLOOR AREA 3</div>
        <div id="" class="warehouse_floor_area_4 rrunit set_wh" data-wh-location="NSW - Warehouse Floor Area 4" style="background:#4FD0F6;">WAREHOUSE FLOOR AREA 4</div>

          <div id="" class="side_wall_storage_bay_1 gen_storage_a level_set" data-bay-level="4" data-wh-location="NSW - Warehouse Side Wall Storage Bay 1" style="background:#FED200;" data-toggle="modal" data-target="#bay_level" tabindex="-1">BAY 1</div>
          <div id="" class="side_wall_storage_bay_2 gen_storage_a level_set" data-bay-level="4" data-wh-location="NSW - Warehouse Side Wall Storage Bay 2" style="background:#FED200;" data-toggle="modal" data-target="#bay_level" tabindex="-1">BAY 2</div>
          <div id="" class="side_wall_storage_bay_3 gen_storage_a level_set" data-bay-level="4" data-wh-location="NSW - Warehouse Side Wall Storage Bay 3" style="background:#FED200;" data-toggle="modal" data-target="#bay_level" tabindex="-1">BAY 3</div>
          <div id="" class="side_wall_storage_bay_4 gen_storage_a level_set" data-bay-level="4" data-wh-location="NSW - Warehouse Side Wall Storage Bay 4" style="background:#FED200;" data-toggle="modal" data-target="#bay_level" tabindex="-1">BAY 4</div>
          <div id="" class="side_wall_storage_bay_5 gen_storage_a level_set" data-bay-level="4" data-wh-location="NSW - Warehouse Side Wall Storage Bay 5" style="background:#FED200;" data-toggle="modal" data-target="#bay_level" tabindex="-1">BAY 5</div>
          
          <div id="" class="rear_wall_storage_bay_1 gen_storage_b level_set" data-bay-level="4" data-wh-location="NSW - Warehouse Rear Wall Storage Bay 6" style="background:#00CB00;" data-toggle="modal" data-target="#bay_level" tabindex="-1">BAY 6</div>
          <div id="" class="rear_wall_storage_bay_2 gen_storage_b level_set" data-bay-level="4" data-wh-location="NSW - Warehouse Rear Wall Storage Bay 7" style="background:#00CB00;" data-toggle="modal" data-target="#bay_level" tabindex="-1">BAY 7</div>
          <div id="" class="rear_wall_storage_bay_3 gen_storage_b level_set" data-bay-level="4" data-wh-location="NSW - Warehouse Rear Wall Storage Bay 8" style="background:#00CB00;" data-toggle="modal" data-target="#bay_level" tabindex="-1">BAY 8</div>


          

        </div>

      </div>
    </div>
  </div>
</div>


<style type="text/css">

.nsw_warehouse {
    background: url('<?php echo base_url(); ?>img/nsw_warehouse.png');
    height: 776px;
    background-repeat: no-repeat;
    width: auto;
    background-position: top center;
}

.small_items{
    padding: 50px 0 !important;
    left: 283px;
    top: 515px;
    height: 142px !important;
    width: 153px !important;
}

.marshalling_area{
    padding: 50px 0 !important;
    left: 152px;
    top: 163px;
    height: 150px !important;
    width: 198px !important;
}

.warehouse_floor_area_1{
       padding: 50px 0 !important;
    left: 357px;
    top: 163px;
    height: 150px !important;
    width: 437px !important;
}

.warehouse_floor_area_2{
    padding: 50px 0 !important;
    left: 800px;
    top: 163px;
    height: 150px !important;
      width: 247px !important;
}

.warehouse_floor_area_3{
        padding: 50px 0 !important;
    left: 799px;
    top: 319px;
    height: 296px !important;
  width: 248px !important;
}


.warehouse_floor_area_4{
      padding: 50px 0 !important;
    left: 799px;
    top: 621px;
    height: 162px !important;
    width: 330px !important;
}


.side_wall_storage_bay_1 {
    top: 79px;
    left: 268px;
}

.side_wall_storage_bay_2 {
    top: 79px;
    left: 424px;
}

.side_wall_storage_bay_3 {
    top: 79px;
    left: 579px;
}

.side_wall_storage_bay_4 {
    top: 79px;
    left: 735px;
}

.side_wall_storage_bay_5 {
    top: 79px;
   left: 889px;
}

.rear_wall_storage_bay_1{
    right: 141px;
    top: 163px;
}

.rear_wall_storage_bay_2{
    right: 141px;
    top: 313px;
}

.rear_wall_storage_bay_3{
     right: 141px;
    top: 463px;
    height: 152px !important;
}


.gen_storage_b{
  width: 83px;
  height: 151px;
  padding: 60px 0px;
  text-align: center;
  border: 1px solid #000;
  position: absolute;
}

.gen_storage_a{

  padding:27px 2px;
  text-align: center;
width: 157px;
    height: 78px;
  border: 1px solid #000;
  position: absolute;
}

.rrunit{
  padding: 2px;
  text-align: center;
  width: 61px;
  height: 26px;
  border: 1px solid #000;
  position: absolute;
  background: #07e3ff;
}

.rrunit:hover, .gen_storage_a:hover, .gen_storage_b:hover{
  background: red !important;
  opacity: 1 !important;
  cursor: pointer !important;
}

</style>
<!-- MODAL -->


<!-- MODAL BAY LEVEL -->

  <div class="modal fade" id="bay_level" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close close_warehouse_mod" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Select Level</h4>
        </div>
 
          <div class="modal-body pad-10">

            <div id="" class="">


            <input type="hidden" class="bay_value_set" value="">

            <div class="lvl_sltctns  set_wh " id="lvl7" data-wh-location="Level 7" style="background:#0097F7;"> LEVEL 7</div>
            <div class="lvl_sltctns  set_wh " id="lvl6" data-wh-location="Level 6" style="background:#00007F;"> LEVEL 6</div>
            <div class="lvl_sltctns  set_wh " id="lvl5" data-wh-location="Level 5" style="background:#0097F7;"> LEVEL 5</div>
            <div class="lvl_sltctns  set_wh " id="lvl4" data-wh-location="Level 4" style="background:#00007F;"> LEVEL 4</div>
            <div class="lvl_sltctns  set_wh " id="lvl3" data-wh-location="Level 3" style="background:#0097F7;"> LEVEL 3</div>
            <div class="lvl_sltctns  set_wh " id="lvl2" data-wh-location="Level 2" style="background:#00007F;"> LEVEL 2</div>
            <div class="lvl_sltctns  set_wh " id="lvl1" data-wh-location="Level 1" style="background:#0097F7;"> LEVEL 1</div>



            </div>

          </div>
     
      </div>
    </div>
  </div>

  <style type="text/css">

    .lvl_sltctns{
    padding: 12px;
    border: 1px solid #fff;
    width: 100%;
    height: 50px;
    text-align: center;
    color: #fff;
    font-size: 15px;
    text-shadow: 1px 1px 3px #000;
    }

  .lvl_sltctns:hover{
    opacity: 0.5;
    cursor: pointer;
  }

  </style>

<script type="text/javascript">

  $(document).on('change', '.chosen.projects_set_a', function(evt, params) {
    var selected_project_data = $(this).val();
    var prj_date = selected_project_data.split("_");
    var limit_date = prj_date[2];
    var startdate = limit_date;
    var new_date = moment(startdate, "DD/MM/YYYY");

    new_date.add(<?php echo 1+($static_data['weeks_delivery']*7) ; ?>, 'days');

    $('#new_supply .datepicker_shipping').datetimepicker({
      format: 'DD/MM/YYYY',
      useCurrent: false
    }).val('');

    $('#new_supply .datepicker_shipping').data("DateTimePicker").maxDate(new_date);

    $('#new_supply #date_goods_expected').datetimepicker({
      format: 'DD/MM/YYYY',
      useCurrent: false
    }).val('');

    $('#new_supply #date_goods_expected').data("DateTimePicker").maxDate(new_date);

    $('#new_supply #date_goods_arrived').datetimepicker({
      format: 'DD/MM/YYYY',
      useCurrent: false
    }).val('');

    $('#new_supply #date_goods_arrived').data("DateTimePicker").maxDate(new_date);




  }); 












  $(document).on('change', '.chosen.projects_set_b', function(evt, params) {
    var selected_project_data = $(this).val();
    var prj_date = selected_project_data.split("_");
    var limit_date = prj_date[2];
    var startdate = limit_date;
    var new_date = moment(startdate, "DD/MM/YYYY");

    new_date.add(<?php echo 1+($static_data['weeks_delivery']*7) ; ?>, 'days');

    $('#edit_supply .datepicker_shipping').datetimepicker({
      format: 'DD/MM/YYYY',
      useCurrent: false
    }).val('');

    $('#edit_supply .datepicker_shipping').data("DateTimePicker").maxDate(new_date);




    $('#edit_supply #date_goods_expected').datetimepicker({
      format: 'DD/MM/YYYY',
      useCurrent: false
    }).val('');

    $('#edit_supply #date_goods_expected').data("DateTimePicker").maxDate(new_date);

    $('#edit_supply #date_goods_arrived').datetimepicker({
      format: 'DD/MM/YYYY',
      useCurrent: false
    }).val('');

    $('#edit_supply #date_goods_arrived').data("DateTimePicker").maxDate(new_date);





  }); 








  $('.level_set').click(function(){

    var level_set = $(this).attr('data-wh-location')


    //$('.set_warehouse_selected').html('<strong>Selected Warehouse: </strong>'+level_set);

    $('input.bay_value_set').val(level_set);

    var level_number = $(this).attr('data-bay-level');
    $('.lvl_sltctns').hide();
    for (i = 1; i <= level_number; i++) {
      $('#lvl'+i).show();
    }
  });


  $('.set_wh').click(function(){
    $('#wa_warehouse_setup').modal('hide');
    $('#nsw_warehouse_setup').modal('hide');
    $('#bay_level').modal('hide');
 

    var wh_location = $('input.bay_value_set').val()+' '+$(this).attr('data-wh-location');


    $('.set_warehouse_selected').html('<p class="pull-left   pad-5 "><strong>Selected Warehouse &nbsp; </strong></p>    <span class="badge badge-info  btn-info pad-5 warehouse_set_value pointer">'+wh_location+'</span>  &nbsp; <span class="badge badge-info m-top-3 btn-warning pointer remove_set_wh" style="padding: 5px 8px;"><em class="fa fa-trash"></em></span>');

    $('input.bay_value_set').val('');

      $('.remove_set_wh').click(function(){
        $('.warehouse_set_value').remove();
        $(this).remove();
        $('.set_warehouse_selected').html('<strong class="m-bottom-10   pad-5 block">Please Select Warehouse &nbsp; </strong>');
      });

      var warehouse_selected = $('#new_supply .warehouse_set_value').text();
      $('#new_supply input#warehouse_selected').val(warehouse_selected);
      
      var warehouse_selected = $('#edit_supply .warehouse_set_value').text();
      $('#edit_supply input#warehouse_selected').val(warehouse_selected);

      $('select#warehouse_area').val('0');
    });

    $('.close_warehouse_mod').click(function(){
      $('select#warehouse_area').val('0');
    });

    $('#wa_warehouse_setup').on('hidden.bs.modal', function () {
      $('select#warehouse_area').val('0');
    });

    $('#bay_level').on('hidden.bs.modal', function () {
      $('select#warehouse_area').val('0');
    });

    $('#nsw_warehouse_setup').on('hidden.bs.modal', function () {
      $('select#warehouse_area').val('0');
    });


    $("input.supply_photos").change(function(){

      var x = 0;
      var supply_photos = document.getElementById('supply_photos');

      if(supply_photos.files.length > 0){

        $('.selected_to_upload').html('<p class=" m-3"><strong>Selected Files</strong></p>');

        for (var i = 0; i < supply_photos.files.length; ++i) {
          var name = supply_photos.files.item(i).name;
          $('.selected_to_upload').append(' <span class="badge badge-info m-top-3 btn-info pad-5">'+name+'</span>');
        }

        $('.selected_to_upload').append('&nbsp; <span class="badge badge-info m-top-3 btn-warning pointer remove_set_files" style="padding: 5px 8px;"><em class="fa fa-trash"></em></span>');

        $('.remove_set_files').click(function() {
          $('input.supply_photos').val('');
          $('.selected_to_upload').html('<p class=" m-3"><strong>No files selected</strong></p>');

          $("input#update_supply_photos").val('');
          $("input#supply_photos").val('');
        });
      }else{
        $('.selected_to_upload').html('<p class=" m-3"><strong>No files selected</strong></p>');
      }
    });



    $("input.update_supply_photos").change(function(){

      $('.added_photos_here').remove();
      $('.added_photos_text').remove();

      var x = 0;
      var supply_photos = document.getElementById('update_supply_photos');

      $('.no_pix_uploaded').hide();

      if(supply_photos.files.length > 0){
        $('.selected_to_upload').prepend('<p class=" m-3 added_photos_text"><strong>Selected Files</strong></p><div id="" class="added_photos_here"></div>');
        for (var i = 0; i < supply_photos.files.length; ++i) {
          var name = supply_photos.files.item(i).name;
          $('.added_photos_here').append(' <span class="badge badge-info m-top-3 btn-info pad-5">'+name+'</span>');
        }

        $('.added_photos_here').append('&nbsp; <span class="badge badge-info m-top-3 btn-warning pointer remove_set_files_ups" style="padding: 5px 8px;"><em class="fa fa-trash"></em></span>');

        $('.remove_set_files_ups').click(function() {
          $('input.update_supply_photos').val('');
        //  $('.selected_to_upload').html('<p class=" m-3"><strong>No files selected</strong></p>');
          $('.added_photos_here').html('');
          $('.added_photos_text').html('');

          $("input#update_supply_photos").val('');
          $("input#supply_photos").val('');
          $('.no_pix_uploaded').show();
        });
      }else{
        $('.selected_to_upload').html('<p class=" m-3 added_photos_text"><strong>No files selected</strong></p>');
      }
    });

 



 
</script>




<!-- MODAL BAY LEVEL -->

<link href="<?php echo base_url(); ?>css/lightbox.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url(); ?>js/lightbox.js" ></script>

 
<?php $this->bulletin_board->list_latest_post(); ?>
<?php $this->load->view('assets/logout-modal'); ?>