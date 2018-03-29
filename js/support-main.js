function get_wip_cost_total(){
  var project_ids_wip = [];
  $("table#wipTable").find('tbody tr').each(function( index ) {
    project_ids_wip.push($(this).find('td:first-child').text());
    project_ids_wip.toString();
  });

  //alert(project_ids_wip);

  ajax_data(project_ids_wip,'wip/sum_total_wip_cost','.totals_wip');
}

function remove_elam_email(obj){
  var elm_val = obj.value;

  var element = document.getElementById(elm_val);
  element.outerHTML = "";
  delete element; 


  var email_list_raw = $('input.email-list').val();
  var email_list_arr = email_list_raw.split(';');


  for(var i = email_list_arr.length - 1; i >= 0; i--) {
    if(email_list_arr[i] === elm_val) {
      email_list_arr.splice(i, 1);
    }
  }

  var new_set_array_email = email_list_arr.join(';');

  $('input.email-list').val(new_set_array_email);



}

function remove_last(po_trans_id,work_id,joinery_id){
  var data = po_trans_id+'*'+work_id+'*'+joinery_id;
  ajax_data(data,'purchase_order/remove_last_trans','');

  setTimeout(function(){ $('#invoice_po_modal').modal('hide'); }, 2000);
  setTimeout(function(){ $('#reconciliated_po_modal').modal('hide'); }, 2000);
  setTimeout(function(){ window.location.assign(baseurl+"purchase_order?reload=1"); }, 2000);
}

function is_alphabet(strValue) {
  var objRegExp = /^[a-zA-Z ]+$/;
  return objRegExp.test(strValue);
}

function count_percent(){
  var progres_percent_total = 0;
  $('.progress-body').find('tr td input.progress-percent').each(function(){
    progres_percent_total = progres_percent_total + parseFloat($(this).val());   
  });

  return progres_percent_total.toFixed(2);
}

function upperCaseEachWord(str){
  var strArr = str.split('.');

  return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});

}

function sentenceCase(str){
 var n=str.split(".");
 var vfinal=""
 for(i=0;i<n.length;i++)
 {
   var spaceput=""
   var spaceCount=n[i].replace(/^(\s*).*$/,"$1").length;
   n[i]=n[i].replace(/^\s+/,"");
   var newstring=n[i].charAt(n[i]).toUpperCase() + n[i].slice(1);
   for(j=0;j<spaceCount;j++)
     spaceput=spaceput+" ";
   vfinal=vfinal+spaceput+newstring+".";
 }
 vfinal=vfinal.substring(0, vfinal.length - 1);
 return vfinal;
}

function if_first_date_higher(firstValue,secondValue){
  if(firstValue == '' || secondValue == ''){
   return 0;
 }

 firstValue = firstValue.split('/');
 secondValue = secondValue.split('/');


 var firstDate = new Date(firstValue[1]+"/"+firstValue[0]+"/"+firstValue[2]);
 var secondDate = new Date(secondValue[1]+"/"+secondValue[0]+"/"+secondValue[2]);

 if (firstDate > secondDate){
   return 1;
 }else{
   return 0;
 }
}


function remove_custom_pm(element_obj){
  value = element_obj.value;
  var_elem_id = element_obj.getAttribute("id");
  $("#"+var_elem_id).parent().parent().remove();
}


var counter = 0;

var progres_total = $('input.num_progress').val();
var prev_data = 0;

function final_progress(element_obj){
  value = element_obj.value;
  var_elem_id = element_obj.getAttribute("id");

  if(value == ''){
    $("input#"+var_elem_id).val('0');
  }
  $('.progress_invoice_button').hide();

  var percent_remain = 100 - count_percent();
  percent_remain = Math.round(percent_remain * 100) / 100;
  // round 2 decimal    Math.round(progress_percent_val * 100) / 100;


  var limit = Math.abs(Math.abs(value - count_percent()) - 100);
  
  limit = Math.round(limit * 100) / 100;

  if(value > 0 && percent_remain > 0 && value != ''){
    $("input#"+var_elem_id).val(value);

    progres_total++;
    count = progres_total;

    $("input#"+var_elem_id).parent().parent().parent().before('<tr><td scope="row" class="t-head" id=""><div class="m-top-10 progress-item">Progress <span class="progress_counter"></span></div></th><td><div class="input-group"><div class="input-group-addon">%</div><input type="text" class="form-control progress-percent" onclick="getHighlight(\'progress-'+count+'-percent\')" onchange="progressPercent(this)" value="'+percent_remain+'" placeholder="Percent" id="progress-'+count+'-percent" name="progress-'+count+'-percent"/></div></td><td><input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control date_daily_a text-left progress_date" id="progress-'+count+'-date" name="progress-'+count+'-date"></td><td><strong><div class="m-top-5">$<span class="total_cost_progress">00.00</span> ex-gst</div></strong></td><td></td><td><strong><span class="progress_outstanding"></span></strong></td></tr>');

  }else{
    $("input#"+var_elem_id).val(limit);
  }
  update_progress_each();



  $('.date_daily_a').datepicker().on('changeDate', function(e){
    var date_id = $(this).attr('id');
    validate_progress_dates(date_id);
  });
}

function selected_shopping_center(val_shopping_center){
  var shpping_cntr_arr = val_shopping_center.split('|');

  var selected_shopping_center_detail = shpping_cntr_arr['1']+' '+shpping_cntr_arr['2']+' '+shpping_cntr_arr['3']+' '+shpping_cntr_arr['4']+' '+shpping_cntr_arr['5']+' '+shpping_cntr_arr['6'];

  $('#brand_shopping_center').val(shpping_cntr_arr['0']);
  $('#selected_shopping_center_detail').val(selected_shopping_center_detail);
  $('#selected_shopping_center_text').html(selected_shopping_center_detail).parent().parent().removeClass('has-error');
  $('#select_shopping_center_modal').modal('hide');
}



function readURL(input,targetImg) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    //var img = new Image();

    reader.onload = function (e) {
      $(targetImg).attr('src', e.target.result);
      //img.src = e.target.result;

      $(targetImg).parent().css('height', $(targetImg).innerWidth() );
    }



    reader.readAsDataURL(input.files[0]);
  }
}

function invoice_paid_modal(element_obj){
  var elem_id = element_obj.getAttribute("id");
  var elem_id_arr = elem_id.split('_');
  var progress_text = $('a#'+elem_id).text();
  var amount = $('a#'+elem_id).parent().parent().find('.invocie_amount_total').text();
  var gst = $('a#'+elem_id).parent().parent().attr('id')/100;
  var outstanding = $('a#'+elem_id).parent().parent().find('.invocie_outstanding').text();



  var inc_gst_amount = parseFloat(amount) + parseFloat(amount*gst);

  
  $('.amount_ext_gst').val(outstanding);
  outstanding = removeCommas(outstanding);
  var inc_gst_amount = parseFloat(outstanding) + parseFloat(outstanding*gst);

  $('.po_total_mod_inc_gst').text(numberWithCommas(inc_gst_amount));
  $('.po_balance_mod').text(outstanding);
  $('.po_desc_mod').text(progress_text);


  var data_history = elem_id_arr['1']+'*'+elem_id_arr['0'];

  $('.payment_history').empty();

  ajax_data(data_history,'invoice/list_payment_history','.payment_history');

  $('button.invoice_remove_trans').attr('id',data_history);
}

function invoice_payment_modal(element_obj){
  var elem_id = element_obj.getAttribute("id");
  var elem_id_arr = elem_id.split('_');
  var progress_text = $('a#'+elem_id).text();
  var amount = $('a#'+elem_id).parent().parent().find('.invocie_amount_total').text();
  var gst = $('a#'+elem_id).parent().parent().attr('id')/100;
  var outstanding = $('a#'+elem_id).parent().parent().find('.invocie_outstanding').text();

  $('.po_total_mod').text(amount);

  amount = removeCommas(amount);

  var inc_gst_amount = parseFloat(amount) + parseFloat(amount*gst);

  
   inc_gst_amount = parseFloat(Number(inc_gst_amount).toFixed(2));

  $('.po_total_mod_inc_gst').text(numberWithCommas(inc_gst_amount));
  $('.po_balance_mod').text('0.00');


  outstanding = removeCommas(outstanding);
  $('.amount_ext_gst').val(outstanding);
  var inc_gst_amount = parseFloat(outstanding) + parseFloat(outstanding*gst);

  inc_gst_amount = parseFloat(Number(inc_gst_amount).toFixed(2));


  inc_gst_amount = removeCommas( numberWithCommas(inc_gst_amount) );
  $('.amount_inc_gst').val(inc_gst_amount);

  $('.invoice_current_value').val(outstanding);
  $('.invoice_current_value_inc_gst').val(inc_gst_amount);

  $('#is_invoice_paid_check').prop('checked', true);

  $('.po_desc_mod').text(progress_text);
  $('.invoice_gst').val(gst);
  $('.invoice_project_id').val(elem_id_arr['1']);
  $('.invoice_id').val(elem_id_arr['0']);
  $('.invoice_order').val(elem_id_arr['3']);

  $('#invoice_payment_reference').val('');
  $('#invoice_payment_notes').val('');

  $('#invoice_payment_date').parent().parent().parent().removeClass('has-error');
  $('#invoice_payment_reference').parent().parent().parent().removeClass('has-error');
  $('#amount_ext_gst').parent().parent().parent().removeClass('has-error');
  $('#amount_inc_gst').parent().parent().parent().removeClass('has-error');


  var data_history = elem_id_arr['1']+'*'+elem_id_arr['0'];

  $('.payment_history').empty();

  ajax_data(data_history,'invoice/list_payment_history','.payment_history');

  $('button.invoice_remove_trans').attr('id',data_history);
}


function open_invoice_filter(){
  $('#invoiceModal').modal('show');
}


function numberWithCommas(x) {
  var parts = x.toString().split(".");
  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  return parts.join(".");
}

var date_validate_count = 0;
var run_once = 0;
var run_once_more = 1;
function progressPercent(element_obj){
  value = element_obj.value;
  var_elem_id = element_obj.getAttribute("id");
  var percent_remain = 100 - count_percent();


  percent_remain = Math.round(percent_remain * 100) / 100;


  var count_percent_val = count_percent();

  $('.progress_invoice_button').hide();


  if(value > 0 && percent_remain > 0){

    $("input#"+var_elem_id).val(percent_remain);
    percent_remain = value;

    progres_total++;
    count = progres_total;

    $("input#"+var_elem_id).parent().parent().parent().before('<tr><td scope="row" class="t-head" id=""><div class="m-top-10 progress-item">Progress <span class="progress_counter"></span></div></th><td><div class="input-group"><div class="input-group-addon">%</div><input type="text" class="form-control progress-percent" onclick="getHighlight(\'progress-'+count+'-percent\')" onchange="progressPercent(this)" value="'+percent_remain+'" placeholder="Percent" id="progress-'+count+'-percent" name="progress-'+count+'-percent"/></div></td><td><input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control date_daily_b text-left progress_date" id="progress-'+count+'-date" name="progress-'+count+'-date"></td><td><strong><div class="m-top-5">$<span class="total_cost_progress">00.00</span> ex-gst</div></strong></td><td></td><td><strong><span class="progress_outstanding"></span></strong></td></tr>');




  }else if(percent_remain < 0){

    var curr_val = $('input#'+var_elem_id).val();
    //$("input#"+var_elem_id).val(prev_data);
    /*count_percent_val - Math.abs(percent_remain) -*/
    var new_val = count_percent() - curr_val;

    new_val = 100 - Math.abs(new_val);

    new_val = Math.round(new_val * 100) / 100;

    $("input#"+var_elem_id).val(new_val);


  }else{

    var next_item = $("input#"+var_elem_id).parent().parent().parent().next().find('input.progress-percent').attr('id')
    $("input#"+var_elem_id).parent().parent().parent().remove();

    var next_item_val = $('input#'+next_item).val();
    var percent_remain = 100 - count_percent();

    var new_val = parseFloat(next_item_val) + percent_remain;
    new_val = Math.round(new_val * 100) / 100;

    $('input#'+next_item).val(new_val);
  }

  update_progress_each();




  $('.date_daily_b').datepicker().on('changeDate', function(e){

    var date_id = $(this).attr('id');

    if(run_once == 0){
      validate_progress_dates(date_id);
      run_once_more = 1;
    }

    run_once++;

    if(run_once > 2){
      validate_progress_dates(date_id);
    }


  });




}

function getHighlight(id){
  $('input#'+id).select();
  prev_data = $('input#'+id).val();
}

function convertToNumbers(str){
 var arr = "abcdefghijklmnopqrstuvwxyz".split("");
 if(str == ''){
  return 0;
}else{
  return str.replace(/[a-z]/ig, function(m){ return arr.indexOf(m.toLowerCase()) + 1 });
}
}

function delete_project_invoice(){
  var project_id = $('input.project_number').val();
  ajax_data(project_id,'invoice/delete_all_invoices',''); 
}

function delete_project_invoice_b(){
  var project_id = $('input.project_number').val();
  ajax_data(project_id,'invoice/delete_some_invoices','');   
}


function removeCommas(str) {
  while (str.search(",") >= 0) {
    str = (str + "").replace(',', '');
  }
  return str;
};


function user_role_set(db,cmp,prj,wip,po,inv,urs,bb,ps){
  var user_role_values = [db,cmp,prj,wip,po,inv,urs,bb,ps];
  var user_role_areas = ['dashboard_access','company_access','projects_access','wip_access','purchase_orders_access','invoice_access','users_access','bulletin_board','project_schedule'];

  var arrayLength = user_role_areas.length;
  for (var i = 0; i < arrayLength; i++) {
    var role_value = user_role_values[i];
    $('input#'+user_role_areas[i]).val(role_value);
    //$('input#'+user_role_areas[i]).val(db);

    if(user_role_values[i] == 2){
      $('.'+user_role_areas[i]).find('.check-a').bootstrapSwitch('state', true);
      $('.'+user_role_areas[i]).find('.check-b').bootstrapSwitch('state', true);
    }else if(user_role_values[i] == 1){
      $('.'+user_role_areas[i]).find('.check-a').bootstrapSwitch('state', true);
      $('.'+user_role_areas[i]).find('.check-b').bootstrapSwitch('state', false);
    }else{
      $('.'+user_role_areas[i]).find('.check-a').bootstrapSwitch('state', false);
      $('.'+user_role_areas[i]).find('.check-b').bootstrapSwitch('state', false);
    }

  }  


//EDITED =========
  // var user_role_values = [db,cmp,prj,wip,po,inv,urs,bb];
  // var user_role_areas = ['dashboard_access','company_access','projects_access','wip_access','purchase_orders_access','invoice_access','users_access','bulletin_board'];

  // var arrayLength = user_role_areas.length;
  // for (var i = 0; i < arrayLength; i++) {

  //   $('input#'+user_role_areas[i]).val(db);


  //   if(user_role_values[i] == 2){
  //     $('.'+user_role_areas[i]).find('.check-a').bootstrapSwitch('state', true);
  //     $('.'+user_role_areas[i]).find('.check-b').bootstrapSwitch('state', true);
  //   }else if(user_role_values[i] == 1){
  //     $('.'+user_role_areas[i]).find('.check-a').bootstrapSwitch('state', true);
  //     $('.'+user_role_areas[i]).find('.check-b').bootstrapSwitch('state', false);
  //   }else{
  //     $('.'+user_role_areas[i]).find('.check-a').bootstrapSwitch('state', false);
  //     $('.'+user_role_areas[i]).find('.check-b').bootstrapSwitch('state', false);
  //   }

  // }  

}



var add_custom_pm_cntr = 0;


function add_custom_pm(classGroup,id){
  var comp_id = classGroup.split("_");
  add_custom_pm_cntr++;
  $('#'+id).parent().parent().after('<div class="col-lg-4 col-md-6 col-xs-6" id=""><div class="input-group m-bottom-10"><input type="text" class="form-control m-bottom-10 input-sm " id="'+comp_id[3]+'_other_nm_'+add_custom_pm_cntr+'" name="'+comp_id[3]+'_other_nm_'+add_custom_pm_cntr+'" placeholder="Other" value="" style="width: 50%;"><input type="text" class="'+classGroup+' form-control m-bottom-10 data_name input-sm click_select focus_percent_val_pm" id="'+comp_id[3]+'_other_pm_'+add_custom_pm_cntr+'" name="'+comp_id[3]+'_other_pm_'+add_custom_pm_cntr+'" placeholder="%" value="" style="width: 50%;" onkeyup="check_split(\''+classGroup+'\',\''+comp_id[3]+'_other_pm_'+add_custom_pm_cntr+'\');"><input type="text" disabled="disabled"  readonly="readonly" value="" class="form-control input-sm focus_computed"  id="focus_computed_other_'+add_custom_pm_cntr+'" style="display:none; width: 50%;" /><span class="input-group-addon pointer remove_custom_pm" onClick="remove_custom_pm(this);" id="rm_bttn_otr_'+add_custom_pm_cntr+'"><strong><i class="fa fa-trash-o"></i></strong></span></div></div>');
}



function check_split(classGroup,id){

  var numInputs = $('input.'+classGroup).length;
  var input_value_item = $('input#'+id).val();

  if(input_value_item!=''){
    $('input#'+id).addClass('addedSplit');
  }else{
    $('input#'+id).val('').removeClass('addedSplit');
  }


  if(input_value_item.indexOf('.') !== -1 ) {
    var decimal_part = input_value_item.split(".");
    if(decimal_part[1].length > 2){
      var newInputValue = decimal_part[0]+'.'+decimal_part[1].substring(0,2);
      $('input#'+id).val(newInputValue);
    }
  }

  if(input_value_item > 100 ){
    $('input#'+id).val('').removeClass('addedSplit');
  }

  var total_shares = 0.00;
  $('input.'+classGroup+'.addedSplit').each(function () {
    var input_value = $(this).val();
    if(input_value!=''){
      total_shares = total_shares + parseFloat($(this).val());
    }
  });

  if(total_shares > 100){
    $('input#'+id).val('').removeClass('addedSplit');
  }

  var numHasAdded = $('input.'+classGroup+'.addedSplit').length;

  var total_shares_added = 0.00;
  $('input.'+classGroup+'.addedSplit').each(function () {
    var input_value = $(this).val();
    if(input_value!=''){
      total_shares_added = total_shares_added + parseFloat($(this).val());
    }
  });

  var numRemain = numInputs - numHasAdded;
  var remain_shares = 100 - total_shares_added;

  var share = remain_shares/numRemain;

  var num_length = share.toString();

  if(num_length.length > 4){
    //var split_share = Math.floor(share * 100)/100;
  }else{
  }

    var split_share = Math.ceil(share * 100)/100;

  //alert(split_share);
  //alert(remain_shares+'--'+total_shares+'--'+total_shares_added+'--'+share+'--'+numRemain);

  $('input.'+classGroup).each(function () {
    if(!$(this).hasClass('addedSplit') ){
      $(this).val(split_share);
    }
  });




}

function validate_progress_dates(date_id){

  var input_date = $('input#'+date_id).val();

 // alert(input_date);


 var prev_progress_date = $('input#progress-0-date').val();
 var counter_progress_loop = 0;
 var has_error = 0;
 var good_to_go = 0;

 var date_ids = [];
 var date_values = [];
 var date_sorted = [];

 var site_start = $('#site_start').val();
 var site_finish = $('#site_finish').val();

 var site_start_arr = site_start.split('/');
 var time_stamp_site_start = new Date(site_start_arr[1]+"/"+site_start_arr[0]+"/"+site_start_arr[2]);
 site_start = Date.parse(time_stamp_site_start);

 var site_finish_arr = site_finish.split('/');
 var time_stamp_site_finish = new Date(site_finish_arr[1]+"/"+site_finish_arr[0]+"/"+site_finish_arr[2]);
 site_finish = Date.parse(time_stamp_site_finish);



 var input_date_arr = input_date.split('/');
 var time_stamp_input_date = new Date(input_date_arr[1]+"/"+input_date_arr[0]+"/"+input_date_arr[2]);
 input_date = Date.parse(time_stamp_input_date);


 $('.progress-body-list').find('tr').reverse().each(function(){

  var progress_date = $(this).find("input.progress_date").val();
  var progress_id = $(this).find("input.progress_date").attr('id');

  if(date_ids.indexOf(progress_id) == -1){
    if(progress_date != ''){
      date_ids.push(progress_id);
    }

    if(progress_date != ''){


      var progress_date_arr = progress_date.split('/');
      var time_stamp_date = new Date(progress_date_arr[1]+"/"+progress_date_arr[0]+"/"+progress_date_arr[2]);


      date_values.push(Date.parse(time_stamp_date));
    }

    if(progress_date != ''){

      var progress_date_arr = progress_date.split('/');
      var time_stamp_date = new Date(progress_date_arr[1]+"/"+progress_date_arr[0]+"/"+progress_date_arr[2]);

      date_sorted.push(Date.parse(time_stamp_date));
    }
  }



});

 date_sorted.sort();

 date_sorted.reverse();


 var date_values_lenght = date_sorted.length;

 var indexSorted = date_sorted.indexOf(input_date);
 var indexDateValue = date_values.indexOf(input_date);
 

 if(indexSorted != indexDateValue){
  $('input#'+date_id).parent().addClass('has-error');
  has_error = 1;

  $('body').focus();
       // 

       $('input#'+date_id).trigger('click');

       $('.progress_date').prop('disabled', true);
       $('input#'+date_id).prop('disabled', false);


     }else{
      $('input#'+date_id).parent().removeClass('has-error');
      $('.progress_date').prop('disabled', false);



      $('.datepicker').hide();
      $('input#'+date_id).blur();

      $('body').focus();
    }


/*
    for (var i = 0; i < date_values_lenght; i++) {

      alert(date_sorted[i]);
      
      if( date_sorted[i] != input_date ){

        $('input#'+date_id).parent().addClass('has-error');
        has_error = 1;
      }else{
        $('input#'+date_id).parent().removeClass('has-error');
      }
    }
    */



/*
    if(site_start > date_values[date_values_lenght-1]){
      $('input#'+date_ids[date_values_lenght-1]).parent().addClass('has-error');
      has_error = 1;

      alert('Staring Invoice should not before the Site Start Date.');
    }else{
      $('input#'+date_ids[date_values_lenght-1]).parent().removeClass('has-error');
    }


    if(site_finish < date_values[0]){
      $('input#'+date_ids[0]).parent().addClass('has-error');
      has_error = 1;
      alert('Final Invoice should not exeed the Site Finish Date.');
    }else{
      $('input#'+date_ids[0]).parent().removeClass('has-error');
    }
    */

    date_ids = [];
    date_values = [];
    date_sorted = [];





    if(has_error == 0){
      $('button.save_progress_values').show();
      $('button.update_progress_values').show();
      $('button.update_progress_values_b').show();
    }else{
      $('button.save_progress_values').hide();
      $('button.update_progress_values').hide();
      $('button.update_progress_values_b').hide();
    }



  }


/*
  function print_job_book(){
    var contents  = $("#job_book_area").html();
    var printWindow = window.open('', '', 'height=900,width=800,top=50,left=50,location=no,toolbar=no,resizable=yes,menubar=no,scrollbars=yes');
    printWindow.document.write('<html><head>');
    printWindow.document.write('<script src="'+baseurl+'js/vendor/jquery-1.11.0.min.js" type="text/javascript" language="javascript" /></script>');
    //printWindow.document.write('<script type="text/javascript" src="http://d3js.org/d3.v3.min.js"></script><script type="text/javascript" src="http://rawgit.com/masayuki0812/c3/master/c3.js"></script>');
    printWindow.document.write('<link href="'+baseurl+'css/print.css" rel="stylesheet" type="text/css" />');
    //printWindow.document.write('<link href="'+baseurl+'css/c3.css" rel="stylesheet" type="text/css" />');
    printWindow.document.write('</head><body class="print_body">');
    printWindow.document.write(contents);
    printWindow.document.write('<a href="#" onclick="this.parentNode.removeChild(this); window.print(); window.close();" class="print_bttn print_me_now">Print Now!</a>');
    //printWindow.document.write('<script src="'+baseurl+'js/printable.js" type="text/javascript" language="javascript" /></script>');
    //printWindow.document.write('<style type="text/css" media="print">.c3-legend-item-hidden{ display:none !important; visibility:hidden !important; }</style>');
    printWindow.document.write('<style type="text/css" media="print">@page { size: portrait; } </style>');
    printWindow.document.write('</body></html>');
    printWindow.document.close();
  }
*/


  function print_job_book(){
    var contents  = $("#job_book_area").html();
    var printWindow = window.open('', '', 'height=800,width=1050,top=100,left=100,location=no,toolbar=no,resizable=yes,menubar=no,scrollbars=yes');
    printWindow.document.write('<html><head>');
    printWindow.document.write('<script src="'+baseurl+'js/vendor/jquery-1.11.0.min.js" type="text/javascript" language="javascript" /></script>');
    printWindow.document.write('<link href="'+baseurl+'css/print.css" rel="stylesheet" type="text/css" />');
    printWindow.document.write('</head><body class="print_body">');
    printWindow.document.write(contents);
    printWindow.document.write('<a href="#" onclick="this.parentNode.removeChild(this); window.print(); window.close();" class="print_bttn print_me_now">Print Now!</a>');
    printWindow.document.write('<script src="'+baseurl+'js/printable.js" type="text/javascript" language="javascript" /></script>');
    printWindow.document.write('</body></html>');
    printWindow.document.close();
  }


  function update_project_invoice(){
    var progres_counter = 0;
    var project_id = $('input.project_number').val();
    $('.progress-body').find('tr').each(function(){
      var final_payment = $(this).find("input.final_payment").val();

   // alert($(this).html());
   progres_counter++;    
   var row_progress_percent = $(this).find('input.progress-percent').val();
   var total_cost_progress = $(this).find('.total_cost_progress').text();
   var progress_date = $(this).find('input.progress_date').val();
   var data = row_progress_percent+'*'+progres_counter+'*'+total_cost_progress+'*'+final_payment+'*'+progress_date+'*'+project_id;

//alert(data);

     setTimeout(function(){
      ajax_data(data,'invoice/insert_invoice_progress','');
     },500);
    });

     setTimeout(function(){
          window.location.assign("?submit_invoice="+project_id);
     },10000);
  }


  function update_project_invoice_b(){
    var progres_counter = 0;
    var project_id = $('input.project_number').val();
    $('.progress-body').find('tr').each(function(){
      var final_payment = $(this).find("input.final_payment").val();

       // alert($(this).html());
       progres_counter++;    
       var row_progress_percent = $(this).find('input.progress-percent').val();
       var total_cost_progress = $(this).find('.total_cost_progress').text();
       var progress_date = $(this).find('input.progress_date').val();
       var data = row_progress_percent+'*'+progres_counter+'*'+total_cost_progress+'*'+final_payment+'*'+progress_date+'*'+project_id;

//alert(data);

      setTimeout(function(){
        ajax_data(data,'invoice/insert_invoice_few_progress','');
      },500);
      });

       setTimeout(function(){
            window.location.assign("?submit_invoice="+project_id);
       },10000);
  }




  function update_progress_each(){
    var project_total_raw = $("input.project_total_raw").val();
    var progres_counter = 0;
    $('.progress-body').find('tr td input.progress-percent').each(function(){
      progres_counter++;    
      $(this).parent().parent().prev().find('.progress-item').text('Progress '+progres_counter);
      var progress_percent_val = project_total_raw*($(this).val() / 100);

      progress_percent_val = Math.ceil(progress_percent_val * 100)/100; // round 2 decimal    Math.round(progress_percent_val * 100) / 100;

      $(this).parent().parent().parent().find('.total_cost_progress').text(numberWithCommas(progress_percent_val));
    });

    progres_counter = 0;
    $('.progress-body').find('tr').each(function(){
   // alert($(this).html());
   progres_counter++;    
   $(this).find('.t-head').attr('id','progress-'+progres_counter);
 });





  }


  var d = new Date();
  var strDate = d.getDate()+"/"+(d.getMonth()+1)+"/"+d.getFullYear();




  function return_outstanding_po_item(obj_id){


    var po_item_row = [];

    $('a#'+obj_id).parent().parent().find('td').each(function(){
      var item = $(this).text();
      po_item_row.push(item);

    //alert(item);
  });

    var total_mod = po_item_row[9].split('-');
    var balance_mod = po_item_row[10].split('-');


    $('#po_is_reconciled_value').prop('checked', true);
    $('.po_number_mod').text(po_item_row[0]);
    $('.po_desc_mod').text(po_item_row[3]);
    $('.po_total_mod').text('$'+total_mod[0]);
    $('.po_balance_mod').text('$'+balance_mod[0]);
    $('#po_amount_value').val(total_mod[0]);
    $('.po_number_item').val(po_item_row[0]);
    $('.po_actual_balance').val(total_mod[0]);

    var data = po_item_row[0];
    ajax_data(data,'purchase_order/po_history','.return_outstanding');



  }






  function select_po_item(obj_id){
    var po_item_row = [];
    var counter = 0;
    var item_id = '';

    $('.po_set_values').show();
     $('.po_note_msg').text('');

    var gst = $('a#'+obj_id).parent().parent().attr('id')/100;
    $('a#'+obj_id).parent().parent().find('td').each(function(){
      var item = $(this).text();
      po_item_row.push(item);

      if(counter == 4){
        item_id = $(this).attr('id');
      }

      counter++;

    }); 

    $('#po_is_reconciled_value').prop('checked', true);

    $('.po_number_mod').text(po_item_row[0]);
    $('.po_desc_mod').text(po_item_row[3]);
    $('.po_balance_mod').text('$0.00');
    $('.po_number_item').val(po_item_row[0]);
    $('input.po_project_id').val(po_item_row[1]);
 

    if(item_id == 'red_bad'){
      $('.po_note_msg').text('Note: This contractor has incomplete insurance.');
     $('.po_set_values').hide();   // toggle for admin access
    } 


    var po_actual_balance_arr = po_item_row[9].split('-');
    var po_amount_value_arr = po_item_row[10].split('-');


    $('.po_total_mod').text(po_actual_balance_arr[0]);

    $('.po_actual_balance').val(po_amount_value_arr[0]);
    $('#po_amount_value').val(po_amount_value_arr[0]);

    var inc_gst = parseFloat(removeCommas(po_item_row[10])) +  parseFloat(removeCommas(po_item_row[10])*gst)


    $('#po_amount_value_inc_gst').val(po_amount_value_arr[1]);
    $('#po_gst').val(gst);

    var data = po_item_row[0];

    ajax_data(data,'purchase_order/po_history','.po_history');
  }


  function progress_invoice(element_obj){
    var progress_invoice = element_obj.getAttribute("id");
    $('input.progress_invoice_id').val(progress_invoice);
    var progress_cost_value = $('#'+progress_invoice).parent().parent().parent().find('.total_cost_progress').text();
    $('input.invoice_item_amount').val(progress_cost_value);
    var invoice_percent_value = $('#'+progress_invoice).parent().parent().parent().find('.progress-percent').val();
    $('input.invoice_percent_value').val(invoice_percent_value);

  }

  function progress_invoice_variation(element_obj){
    var progress_invoice = element_obj.getAttribute("id");
    var progress_cost_value = $('.variation_total_cost').text();
    $('input.progress_invoice_id').val(progress_invoice);
    $('input.invoice_item_amount').val(progress_cost_value);
    $('input.invoice_percent_value').val('100.00');
  }


  $(document).ready(function(){

    $('.sb-open-right').click(function(){
      $('#main').find('#main-sidebar.prg_cmts_sde').show();
    });

    

    $('a.currently_logged_user').click(function(){
      $('#loggedin-sidebar').show();
      $("#user_list").html('');

      $('.spinner_loading').removeClass('fa-user');
      $('.spinner_loading').addClass('fa-cog');
      $('.spinner_loading').addClass('fa-spin');
      $('.text_loading_users').html('Updating List...');
      $.post(baseurl+"users/user_activity_list",{},
      function(result){

       setTimeout(function(){
        $("#user_list").html(result);

        $('.spinner_loading').removeClass('fa-spin');
        $('.spinner_loading').removeClass('fa-cog');
        $('.spinner_loading').addClass('fa-user');

        $('.text_loading_users').html('User List - <strong>Reload?</strong>');


      },2000);


        //$('.user_logged').show();
      });
    });

    $('.update_currently_logged_user').click(function(){
      $('#loggedin-sidebar').show();
      $("#user_list").html('');
      $('.spinner_loading').removeClass('fa-user');
      $('.spinner_loading').addClass('fa-cog');
      $('.spinner_loading').addClass('fa-spin');
      $('.text_loading_users').html('Updating List...');
      $.post(baseurl+"users/user_activity_list",{},
      function(result){
        
       setTimeout(function(){
        $("#user_list").html(result);

        $('.spinner_loading').removeClass('fa-spin');
        $('.spinner_loading').removeClass('fa-cog');
        $('.spinner_loading').addClass('fa-user');

        $('.text_loading_users').html('User List - <strong>Reload?</strong>');


      },2000);
        //$('.user_logged').show();
      });
    });


    $('#main-sidebar').simpleSidebar({
      opener: '.sb-open-right',
      wrapper: '#main',
      animation: {
        easing: "easeOutQuint"
      },
      sidebar: {
        align: 'right',
        closingLinks: '.close-sb',
        width: 360,
      },
      sbWrapper: {
        display: true
      },
      mask: {
        display: true
      }
    });




    $('#loggedin-sidebar').simpleSidebar({
      opener: '.currently_logged_user',
      wrapper: '#main',
      animation: {
        easing: "easeOutQuint"
      },
      sidebar: {
        align: 'right',
        closingLinks: '.close-sb',
        width: 360,
      },
      sbWrapper: {
        display: true
      },
      mask: {
        display: true
      }
    });






 //   $.slidebars({ scrollLock: true });

 jQuery.fn.reverse = [].reverse;

  //$('.variation_total_cost').text($('.variation_total').text());

  $('.progress_invoice').click(function(){
    var progress_invoice = $(this).attr('id');
    var progress_cost_value = $(this).parent().parent().parent().find('.total_cost_progress').text();


    $('input.progress_invoice_id').val(progress_invoice);



    $('input.invoice_item_amount').val(progress_cost_value);
    var invoice_percent_value = $('#'+progress_invoice).parent().parent().parent().find('.progress-percent').val();
    $('input.invoice_percent_value').val(invoice_percent_value);
  });


  $('#po_table_filter input').focus();
  $('#invoice_table_filter input').focus();

  $('.set_project_as_paid').click(function(){
    var data = $(this).attr('id');
    ajax_data(data,'invoice/set_project_as_paid','');

    setTimeout(function(){
      window.location.assign("?submit_invoice="+data);
    },1000);
  });

  $('.remove_recent_payment_b').click(function(event){
    var project_id = $(this).attr('id');
    var invoice_id = $('input#invoice_id_progress').val();


    var data = project_id+'*'+invoice_id;

    ajax_data(data,'invoice/remove_recent_payment','');

    setTimeout(function(){
      $('.history_b > tr').slice(-2).remove();
    },500);


    setTimeout(function(){
      window.location.assign("?submit_invoice="+project_id);
    },1000);

  });

  var each_remove_invoice = 0;

  $('.remove_invoice').reverse().each(function( index ){

    if(each_remove_invoice > 0){
      $(this).parent().hide();
    }else{
      $(this).parent().show();
    }

    //var line_text = $(this).parent().parent().parent().parent().parent().find('.progress-item').text();

    //alert(line_text+' '+each_remove_invoice);

    each_remove_invoice++;
  });

  $('.remove_vr').click(function(){
    var project_id = $(this).attr('id');

    $(".job_book_notes").find('.notes_line').last().remove();
    var job_book_notes =  $(".job_book_notes").html();
    var data = project_id+'*'+job_book_notes;


    ajax_data(data,'invoice/un_invoice_vr','.test');


    $('.vr_bttn_group').html('<div class="progress_vr_invoice_button"><button class="btn btn-primary  m-right-5 progress_invoice_variation" onclick="progress_invoice_variation(this)" id="VR_" data-toggle="modal" data-target="#set_invoice_modal"><i class="fa fa-file-text-o"></i> Set Invoice</button></div>');

  });

  $('#po_table_wrapper .dataTables_length').append($('.po_legend_o').html());
  $('#reconciled_list_table_wrapper .dataTables_length').append($('.po_legend_r').html());

  $('#invoice_table_wrapper .dataTables_length').append($('.po_legend').html());
  $('#invoice_paid_table_wrapper .dataTables_length').append($('.po_legend').html());



  $('.proj_comments_search_bttn').click(function(){
    $(this).find('i').addClass('fa-spin');

    setTimeout(function(){   
      $('.proj_comments_search_bttn').find('i').removeClass('fa-spin');
    },1000);


    var prjc_project_id = $('select#prjc_project_id').val();
    $('.notes_side_content').empty().append('<div class="notes_line no_posted_comment"><p><i class="fa fa-cog fa-spin"></i> Searching...</p></div>');

    $.post(baseurl+"projects/list_project_comments",{ 'project_id': prjc_project_id },function(result){    
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

  $('select.user-role-selection').on("change", function(e) {
    var data = $(this).val();


    $.post(baseurl+"users/fetch_user_access",{ 
      'ajax_var': data
    },function(result){
     var user_access_arr = result.split(",");

     setTimeout(function(){
       user_role_set(user_access_arr['3'],user_access_arr['4'],user_access_arr['5'],user_access_arr['6'],user_access_arr['7'],user_access_arr['8'],user_access_arr['9'],user_access_arr['11'],user_access_arr['12']);
     },500);



   });


  });

  $("#draggable").draggable();

  $('#companyTable_filter input').focus();
  $('#projectTable_filter input').focus();
  $('#wipTable_filter input').focus();
  $('#po_table_filter input').focus();
  $('#invoice_table_filter input').focus();
  $('input.search_title_bb').focus();


  $('input.progress_date').on("keydown", function(e) {
    e.stopPropagation();
    e.preventDefault();  
    e.returnValue = false;
    e.cancelBubble = true;
    return false;
  });


 //  $('input.datepicker#job_date').on("keydown", function(e) {
 //   e.stopPropagation();
 //   e.preventDefault();  
 //   e.returnValue = false;
 //   e.cancelBubble = true;
 //   return false;
 // });


  $('input.datepicker#site_start').on("keydown", function(e) {
    e.stopPropagation();
    e.preventDefault();  
    e.returnValue = false;
    e.cancelBubble = true;
    return false;
  });

  $('input.datepicker#site_finish').on("keydown", function(e) {
    e.stopPropagation();
    e.preventDefault();  
    e.returnValue = false;
    e.cancelBubble = true;
    return false;
  });


$('.set_ave').click(function(){
  var type = $(this).text();

  var currentdate = new Date();
  var set_month = (currentdate.getMonth()+1) < 10 ? '0' + (currentdate.getMonth()+1) : (currentdate.getMonth()+1);
  var set_day = ( currentdate.getDate() < 10 ? '0' + currentdate.getDate() : currentdate.getDate()  ); 

  var day_set_non_rec = set_day + "/" + set_month + "/" + currentdate.getFullYear();


  $('.day_set_non_rec').val(day_set_non_rec);


  $('.ave_type').text(type);

  $('.time_ave_a').val('');
  $('.time_ave_b').val('');
  $('.ave_notes').val('');

});


$('.set_ave_def').click(function(){


  var user_id_a = $('.prjc_user_id').val();
  var user_id_b = $('input#user_id_page').val();
  var pathname = window.location.pathname;

  if(document.querySelector("#user_id_page")){
    var user_id = user_id_b;
  }else{
    var user_id = user_id_a;
  }

  var data = pathname+'`'+user_id;

  if (pathname.toLowerCase().indexOf("users") >= 0){

  }else{


    $('#loading_modal').modal({"backdrop": "static", "show" : true} );

    $('.ave_status_text').html('<span style="color: green;"><i class="fa fa-check-circle"></i> Available </span> <b class="caret"></b>');
    setTimeout(function(){ 
      $('#loading_modal').modal('hide');
    },1000);



  }

  $.post(baseurl+"users/reset_availability",{'ajax_var':data}, 
    function(result){
      if(result == '1'){
        $('#loading_modal').modal({"backdrop": "static", "show" : true} );
        setTimeout(function(){
          location.reload();
        },1000);
      } 
    }
  );
});


$('.edit_f_ava').click(function(){
  var raw_data = $(this).attr('id');
  var data_set = raw_data.split("_");

  var raw_day = data_set[2];
  var date_selected = raw_day.split(' ');

  var raw_day_b = data_set[3];
  var date_selected_b = raw_day_b.split(' ');

  //241__03/12/2016 07:00 AM_03/12/2016 05:00 PM
  //alert(date_selected);

  $('.time_day_set_non_rec').val(date_selected[0]);
  $('.time_ave_a_up').val(date_selected[1]+' '+date_selected[2]);
  $('.time_ave_b_up').val(date_selected_b[1]+' '+date_selected_b[2]);
  $('.ave_notes_up').val(data_set[1]);

  $('input#ava_id_data').val(data_set[0]);
  
  var status = $(this).text().trim();


  $('.ave_type_up').text(status);


});

$('.delete_f_ava').click(function(){
  $('#loading_modal').modal({"backdrop": "static", "show" : true} );
  var data = $(this).attr('id');

  $.post(baseurl+"users/delete_user_ava",{
    'ajax_var':data 
  });

  setTimeout(function(){
   location.reload();
 },1000);
});


$('.delete_rec_ava').click(function(){
  $('#loading_modal').modal({"backdrop": "static", "show" : true} );
  var data = $(this).attr('id');

  $.post(baseurl+"users/delete_user_ava_rec",{
    'ajax_var':data 
  });

  setTimeout(function(){
   location.reload();
 },1000);
});



$('.update_ave').click(function(){
  var ava_id = $('input#ava_id_data').val();

  var start_date = $('.time_day_set_non_rec').val();


  var date_a = $('.time_ave_a_up').val();
  var date_b = $('.time_ave_b_up').val();
  var notes = $('.ave_notes_up').val();

  var pathname = window.location.pathname;
  var status = $('.ave_type_up').text().trim();


  var user_id_a = $('.prjc_user_id').val();
  var user_id_b = $('input#user_id_page').val();

  if(document.querySelector("#user_id_page")){
    var user_id = user_id_b;
  }else{
    var user_id = user_id_a;
  }

var data = start_date+' '+date_a+'`'+start_date+' '+date_b+'`'+notes+'`'+user_id+'`'+pathname+'`'+status+'`'+ava_id;


//alert(data);


$('#update_availability').modal('hide');
$('#loading_modal').modal('show');


$.post(baseurl+"users/update_availability",{
   'ajax_var':data 
  }, 
  function(result){

 //  alert(result);
 

      setTimeout(function(){
       location.reload();
      },1000);

     
  }
);



});

$('.submit_ave').click(function(){

  var is_complete = 1;

  if($('.time_ave_a').val() == '' || $('.time_ave_b').val() == ''){

    $('input.time_ave_a').parent().addClass('has-error');
    $('input.time_ave_b').parent().addClass('has-error');

  }else{

    $('input.time_ave_a').parent().removeClass('has-error');
    $('input.time_ave_b').parent().removeClass('has-error');

    var day_start = $('.day_set_non_rec').val();
    var date_a = day_start+' '+$('.time_ave_a').val();
    var date_b = day_start+' '+$('.time_ave_b').val();
    var notes = $('.ave_notes').val();

    var pathname = window.location.pathname;
    var status = $('.ave_type').text().trim();


    var user_id_a = $('.prjc_user_id').val();
    var user_id_b = $('input#user_id_page').val();

    if(document.querySelector("#user_id_page")){
      var user_id = user_id_b;
    }else{
      var user_id = user_id_a;
    }


    var data = date_a+'`'+date_b+'`'+notes+'`'+user_id+'`'+pathname+'`'+status;


    $('#set_availability').modal('hide');
    $('#loading_modal').modal('show');


    $.post(baseurl+"users/set_availability",{
        'ajax_var':data 
      }, 
      function(result){

        if(result == '1'){

          setTimeout(function(){
            location.reload();
          },1000);

        }else{
          //alert(result);
          setTimeout(function(){
            $('#loading_modal').modal('hide');
          },1000);
          $('.ave_status_text').html(result+' <b class="caret"></b>');
        }
      }
    );

  }

});





 

  $('input.click_select').click(function(){
    $(this).select();
  });

  
  $('.toggle_percent_amount').click(function(){
    var id_atrb = $(this).attr('id');

    var form_arr = id_atrb.split("-");
    var form_id = form_arr[1];

    $(this).find('i').addClass('fa-spin');

    setTimeout(function(){
      $('.toggle_percent_amount').find('i').removeClass('fa-spin');
    },1000);

    $('form#'+form_id+' input.focus_computed').toggle();
    $('form#'+form_id+' input.focus_percent_val').toggle();
    $('form#'+form_id+' input.focus_percent_val_pm').toggle();
    $('form#'+form_id+' input.focus_month_split').toggle();
    $('form#'+form_id+' input.focus_month_split_edt').toggle();
    $('form#'+form_id+' input.focus_percent_val_pm_edt').toggle();



    var data_amount = removeCommas($('form#'+form_id+' input#data_amount').val());

    $('form#'+form_id+' input.focus_percent_val').each(function (){
      var percent_value = parseFloat($(this).val());

      if(percent_value>0 && percent_value != ''){
        var computed = data_amount * (percent_value/100);
        var computed_rounded = numberWithCommas(Math.ceil(computed * 100)/100);
      }else{
        var computed_rounded = 0;
      }

      if(data_amount == '' || data_amount == 0){
        var computed_rounded = 0;
      }

      $(this).next().val('$'+computed_rounded);
    });




    $('form#'+form_id+' input.focus_month_split').each(function (){
      var percent_value = parseFloat($(this).val());

      if(percent_value>0 && percent_value != ''){
        var computed = data_amount * (percent_value/100);
        var computed_rounded_mns = numberWithCommas(Math.ceil(computed * 100)/100);
      }else{
        var computed_rounded_mns = 0;
      }

      if(data_amount == '' || data_amount == 0){
        var computed_rounded_mns = 0;
      }

      $(this).next().val('$'+computed_rounded_mns);
    });

// edit xomputation
    $('form#'+form_id+' input.focus_month_split_edt').each(function (){
      var percent_value = parseFloat($(this).val());

      if(percent_value>0 && percent_value != ''){
        var computed = data_amount * (percent_value/100);
        var computed_rounded_mns = numberWithCommas(Math.ceil(computed * 100)/100);
      }else{
        var computed_rounded_mns = 0;
      }

      if(data_amount == '' || data_amount == 0){
        var computed_rounded_mns = 0;
      }

      $(this).next().val('$'+computed_rounded_mns);
    });
// edit xomputation



    $('form#'+form_id+' input.focus_percent_val_pm').each(function (){

      var input_id = $(this).attr('id');
      var focus_arr = input_id.split("_");
      var data_amount_f = removeCommas($('input#focus_id_'+focus_arr[0]).val());

      var percent_value = parseFloat($(this).val());

      if(percent_value>0 && percent_value != ''){
        var percent_value = parseFloat($(this).val());
        var computed_pm = data_amount_f * (percent_value/100);
        var computed = data_amount * (computed_pm/100);
        var computed_rounded_pm = numberWithCommas(Math.ceil(computed * 100)/100);
      }else{
        var computed_rounded_pm = 0;
      }

      if(data_amount == '' || data_amount == 0){
        var computed_rounded_pm = 0;
      }

      $(this).next().val('$'+computed_rounded_pm);

    });



// edit xomputation
    $('form#'+form_id+' input.focus_percent_val_pm_edt').each(function (){


      var input_id = $(this).attr('id');
      var focus_arr = input_id.split("_");
      var data_amount_f = removeCommas($('input#focus_idedt_'+focus_arr[0]).val());

      var percent_value = parseFloat($(this).val());

      if(percent_value>0 && percent_value != ''){
        var percent_value = parseFloat($(this).val());
        var computed_pm = data_amount_f * (percent_value/100);
        var computed = data_amount * (computed_pm/100);
        var computed_rounded_pm = numberWithCommas(Math.ceil(computed * 100)/100);
      }else{
        var computed_rounded_pm = 0;
      }

      if(data_amount == '' || data_amount == 0){
        var computed_rounded_pm = 0;
      }

      $(this).next().val('$'+computed_rounded_pm);

    });
// edit xomputation

  });

  


  $('input.focus_pm_split').on("keyup", function(e) {
    var split_value = $(this).val();

    if(split_value != ''){

      $(this).addClass('addedSplit');
      var splits = $('input.focus_pm_split').length;
      var remaining_split = splits-1;

      if(remaining_split == 1){
        var share = (100 - split_value);
      }else{
        var share = (100 - split_value) / remaining_split;
      }

      share = Math.round(share * 100) / 100;

    //   alert(split_value+' '+share+' '+splits+' '+remaining_split);

      $('input.focus_pm_split').each(function () {
        if(!$(this).hasClass('addedSplit') ){
          $(this).val(share);
        }
      });
    }else{
      $('input.focus_pm_split').each(function () {
        $(this).removeClass('addedSplit');
        $(this).val('');
      });
    }
  });



/*
  $('input.focus_month_split').on("keyup", function(e) {
    var split_value = $(this).val();

    if(split_value != ''){

      $(this).addClass('addedSplit');
      var splits = $('input.focus_month_split').length;
      var remaining_split = splits-1;

      if(remaining_split == 1){
        var share = (100 - split_value);
      }else{
        var share = (100 - split_value) / remaining_split;
      }

      share = Math.round(share * 100) / 100;

      // alert(split_value+' '+share+' '+splits+' '+remaining_split);

      $('input.focus_month_split').each(function () {
        if(!$(this).hasClass('addedSplit') ){
          $(this).val(share);
        }
      });
    }else{
      $('input.focus_month_split').each(function () {
        $(this).removeClass('addedSplit');
        $(this).val('');
      });
    }
  });


*/

  $('input.search_title_bb').keyup(function(){
    var inputWd = $(this).val().toString().toLowerCase();
    $('.box-area-po .post_item').each(function () {
      $(this).hide();
      var rowText = $(this).text().toString();
      rowText = rowText.toString().toLowerCase();
      rowText = rowText.replace(/\s/g,'');

      var find = rowText.indexOf(inputWd);
      if(find  >= 0){
        $(this).show();
      }
    });
  });



  $('select#prjc_project_id').on("change", function(e) {
    $('.notes_side_form').hide();
    $('.notes_side_content').empty().append('<div class="notes_line no_posted_comment"><p>Please click search...</p></div>');
  });

  $('.submit_notes_prj').click(function(){


    $('.no_posted_comment').remove();

    var prjc_user_id = $('.prjc_user_id').val();
    var prjc_user_first_name = $('.prjc_user_first_name').val();
    var prjc_user_last_name = $('.prjc_user_last_name').val();
    var prjc_project_id = $('select#prjc_project_id').val();
    var notes_comment_text = $('.notes_comment_text').val();
    var result = '';
    var dataString = prjc_user_id+'`'+prjc_project_id+'`'+notes_comment_text;

    $('.notes_comment_text').empty().val('');

  //$('.notes_side_content').prepend('<div class="notes_line"><p>'+notes_comment_text+'</p><small><i class="fa fa-user"></i> '+prjc_user_first_name+' '+prjc_user_last_name+'<br><i class="fa fa-calendar"></i> '+result+'</small></div>');


  if(notes_comment_text!=''){
    $.post(baseurl+"projects/add_project_comment",{ 
      'ajax_var': dataString
    },function(result){
      $('.notes_side_content').prepend('<div class="notes_line"><p class="" style="">'+notes_comment_text+'</p><br /><small><i class="fa fa-user"></i> '+prjc_user_first_name+' '+prjc_user_last_name+'<br><i class="fa fa-calendar"></i> '+result+'</small></div>');
      $('.recent_prj_comment').empty().append('<p>'+notes_comment_text+'</p><small><i class="fa fa-user"></i> '+prjc_user_first_name+' '+prjc_user_last_name+'<br><i class="fa fa-calendar"></i> '+result+'</small>');
    });
  }

});

$('.remove_invoice').click(function(e){
  e.preventDefault;
  var invoice_raw = $(this).attr('id');
  var invoice_arr = invoice_raw.split("-");

  $('.progress_invoice_button').remove();

  var progress = invoice_arr['1'];

  $(this).parent().parent().parent().parent().parent().find('.progress_date').prop('disabled', false);
  $(this).parent().parent().parent().parent().parent().find('.progress-percent').prop('disabled', false);
  $(".invoices_list_item").find('.invoices_list').last().remove();

  $(this).parent().parent().parent().parent().html('<div class="progress_invoice_button"><button class="btn btn-primary  m-right-5 progress_invoice" id="'+progress+'" data-toggle="modal" onclick="progress_invoice(this);" data-target="#set_invoice_modal"><i class="fa fa-file-text-o"></i> Set Invoice</button></div>');

  var invoice_id = invoice_arr['0'];
  var project_id = invoice_arr['2'];



  $(".job_book_notes").find('.notes_line').first().remove();
  var job_book_notes =  $(".job_book_notes").html();

  var data = invoice_id+'*'+project_id+'*'+job_book_notes;
  ajax_data(data,'invoice/un_invoice_item','');


  var each_remove_invoice_x = 0;
  $('.remove_invoice').reverse().each(function( index ){
    if(each_remove_invoice_x > 0){
      $(this).parent().hide();
    }else{
      $(this).parent().show();
    }

    //var line_text = $(this).parent().parent().parent().parent().parent().find('.progress-item').text();

    //alert(line_text+' '+each_remove_invoice);

    each_remove_invoice_x++;
  });

});




$('#var_update').click(function(){

  setTimeout(function(){
    var project_total = removeCommas($('input.project_total_raw').val()); 
    var variation = removeCommas($('input.variation').val());

    var proj_ex_gst_raw = removeCommas($('.proj_ex_gst').text());
    proj_ex_gst_raw = parseFloat(proj_ex_gst_raw);
    proj_ex_gst_raw = proj_ex_gst_raw.toFixed(2);

    var variation_total_raw = removeCommas($('.variation_total').text());
    variation_total_raw = parseFloat(variation_total_raw);
    variation_total_raw = variation_total_raw.toFixed(2);

    var project_number = $('input.project_number').val();


    if(variation != variation_total_raw){
      alert('Alert! Variation Totals is been updated, Page is reloading.');
      location.reload();
    }
  },800);

});


$('.delete_post').click(function(e){
  e.preventDefault;
  var delete_id_post = $(this).attr('id');


  var confirm_action = confirm("Are you sure to delete this post?");
  if (confirm_action == true) {

    $(this).parent().parent().parent().parent().fadeOut();

    ajax_data(delete_id_post,'bulletin_board/remove_post','');


  } else {
   // txt = "You pressed Cancel!";
 }



});

$('.report_btn').click(function(e){

  e.preventDefault;

  $('.invoice_date_a').parent().removeClass('has-error');
  $('.invoice_date_b').parent().removeClass('has-error');
  $('.error_area').html('');

  $('#filter_invoice').modal('show');

  $('.report_result').html('');

});

$('.remove_recent_payment_a').click(function(event){
  var project_id = $(this).attr('id');
  var invoice_id = $('input#invoice_id_progress').val();


  var data = project_id+'*'+invoice_id;

  ajax_data(data,'invoice/remove_recent_payment','');

  setTimeout(function(){
    $('.history_a > tr').slice(-2).remove();
  },500);


  setTimeout(function(){
    window.location.assign("?submit_invoice="+project_id);
  },1000);

});


$('.invoice_remove_trans').click(function(event){
  var elem_id = $(this).attr('id');
  var elem_id_arr = elem_id.split('*');

  var project_id = elem_id_arr['0'];
  var invoice_id = elem_id_arr['1'];
  var data = project_id+'*'+invoice_id;

  ajax_data(data,'invoice/remove_recent_payment','');

  setTimeout(function(){
    $('.payment_history > tr').slice(-2).remove();
  },200);


  setTimeout(function(){
    window.location.assign("?submit_invoice_payment="+project_id);
  },1000);


});





$('#is_invoice_paid_check').click(function(){
  var invoice_current_value = $('.invoice_current_value').val();
  var invoice_current_value_inc_gst = $('.invoice_current_value_inc_gst').val();

    invoice_current_value_inc_gst = parseFloat(Number(invoice_current_value_inc_gst).toFixed(2));

  if(this.checked) {
    $('.amount_ext_gst').val(invoice_current_value);
    $('.amount_inc_gst').val(invoice_current_value_inc_gst);
    $('.po_balance_mod').text('0.00');
  }else{
    $('.amount_ext_gst').val('');
    $('.amount_inc_gst').val('');
    $('.po_balance_mod').text(invoice_current_value);
  }

});

$('input.amount_ext_gst').click(function(){
  $(this).select();
});

$('input.amount_inc_gst').click(function(){
  $(this).select();
});


$('input.amount_ext_gst').on("keyup", function(e) {
  var amount_ext_gst = $(this).val();
  var gst = $('input#invoice_gst').val();
  var invoice_outstanding_value_input = $('.invoice_current_value').val();

  if(Math.sign(amount_ext_gst) < 0){
    var sign = '-';
  }else{
    var sign = '';    
  }
  var invoice_outstanding_value_input = Math.abs(invoice_outstanding_value_input);

  var amount_ext_gst =  parseFloat( Math.abs(amount_ext_gst) );


  var invoice_outstanding_value = parseFloat(amount_ext_gst) + parseFloat(amount_ext_gst*gst);

    invoice_outstanding_value = parseFloat(Number(invoice_outstanding_value).toFixed(2));


  //invoice_outstanding_value = numberWithCommas(invoice_outstanding_value);

  $('.amount_inc_gst').val(sign+invoice_outstanding_value);

  if(amount_ext_gst == '' || amount_ext_gst <= 0){
    $('.amount_inc_gst').val('');
  }

  invoice_outstanding_value_input = parseFloat(invoice_outstanding_value_input);
  amount_ext_gst = parseFloat(amount_ext_gst);

  if(invoice_outstanding_value_input <= amount_ext_gst){
   $('#is_invoice_paid_check').prop('checked', true);
 }else{    
   $('#is_invoice_paid_check').prop('checked', false);
 }

 var po_balance_mod = invoice_outstanding_value_input - parseFloat(amount_ext_gst);

 po_balance_mod = po_balance_mod || '0.00';
 var po_balance_mod_final = po_balance_mod.toFixed('2');


 $('.po_balance_mod').text( numberWithCommas( sign+po_balance_mod_final) );

});



$('input.amount_inc_gst').on("keyup", function(e) {
  var amount_ext_gst = $(this).val();
  var gst = ($('input#invoice_gst').val()*100);
  var invoice_outstanding_value_input = $('.invoice_current_value_inc_gst').val();
  var invoice_current_value = $('.invoice_current_value').val();


  var amount_ext_gst = Math.abs(amount_ext_gst);
  var invoice_outstanding_value_input = Math.abs(invoice_outstanding_value_input);
  
  if(Math.sign(amount_ext_gst) < 0){
    var sign = '-';
  }else{
    var sign = '';    
  }

/*
  var invoice_outstanding_value = parseFloat(amount_ext_gst) + parseFloat(amount_ext_gst*gst);
  invoice_outstanding_value = numberWithCommas(invoice_outstanding_value);
  */

  var invoice_outstanding_value = amount_ext_gst - (amount_ext_gst / ((gst+100)/gst));
  invoice_outstanding_value = invoice_outstanding_value.toFixed(2);

  $('.amount_ext_gst').val(invoice_outstanding_value);

  if(amount_ext_gst == '' || amount_ext_gst <= 0){
    $('.amount_ext_gst').val('');
  }

  invoice_outstanding_value_input = parseFloat(invoice_outstanding_value_input);
  amount_ext_gst = parseFloat(amount_ext_gst);

  if(invoice_outstanding_value_input <= amount_ext_gst){
   $('#is_invoice_paid_check').prop('checked', true);
 }else{    
   $('#is_invoice_paid_check').prop('checked', false);
 }

 var po_balance_mod = invoice_outstanding_value_input - amount_ext_gst;

 po_balance_mod = po_balance_mod.toFixed(2);

 po_balance_mod = po_balance_mod || invoice_current_value;
 $('.po_balance_mod').text(sign+po_balance_mod);

});


$("#profile_photo").change(function(){
  readURL(this,'.user_avatar');
});

$('.invoice_payment_bttn').click(function(){
  var invoice_payment_date = $('#invoice_payment_date').val();
  var invoice_payment_reference = $('#invoice_payment_reference').val();
  var amount_ext_gst = $('#amount_ext_gst').val();
  var amount_inc_gst = $('#amount_inc_gst').val();
  var invoice_payment_notes = $('#invoice_payment_notes').val();
  var invoice_project_id = $('#invoice_project_id').val();
  var invoice_id = $('#invoice_id').val();
  var invoice_order = $('#invoice_order').val();
  var outstanding = $('.po_balance_mod').text();



  var has_error = 0;

  if ($("#is_invoice_paid_check").is( ":checked" )) {
    var is_invoice_paid_check = 1;
  }else{
    var is_invoice_paid_check = 0;
  }

  if(invoice_payment_date == ''){
    $('#invoice_payment_date').parent().parent().parent().addClass('has-error');
    has_error = 1;
  }else{
    $('#invoice_payment_date').parent().parent().parent().removeClass('has-error');
  }

  if(invoice_payment_reference == ''){
    $('#invoice_payment_reference').parent().parent().parent().addClass('has-error');
    has_error = 1;
  }else{
    $('#invoice_payment_reference').parent().parent().parent().removeClass('has-error');
  }

  if(amount_ext_gst == ''){
    $('#amount_ext_gst').parent().parent().parent().addClass('has-error');
    has_error = 1;
  }else{
    $('#amount_ext_gst').parent().parent().parent().removeClass('has-error');
  }

  if(amount_inc_gst == ''){
    $('#amount_inc_gst').parent().parent().parent().addClass('has-error');
    has_error = 1;
  }else{
    $('#amount_inc_gst').parent().parent().parent().removeClass('has-error');
  }

  if(has_error == 0){

    var data = invoice_payment_date+'*'+invoice_project_id+'_'+invoice_order+'*'+invoice_payment_notes+'*'+invoice_payment_reference+'*'+amount_ext_gst+'*'+is_invoice_paid_check+'*$'+outstanding+'*'+invoice_id;


    //var data = po_date_value+'*'+progress_id+'*'+po_notes_value+'*'+invoice_payment_reference_no+'*'+progress_payment_amount_value+'*'+is_paid_check+'*'+outstanding+'*'+invoice_id_progress;

    //alert(data);


    ajax_data(data,'invoice/progress_payment','.test');

    setTimeout(function(){
      window.location.assign("?submit_invoice_payment="+invoice_project_id);
      //alert(invoice_project_id+' '+invoice_id);
    },500);

  }

});



$('.progress_invoice_resend').click(function(event){
  event.preventDefault();
  var project_id = $('input.project_number').val();
  var id_bttn = $('input.progress_invoice_id').val();
  var job_book_notes = $('.job_book_notes').html();
  invoice_notes = job_book_notes;

  $('.print_job_book_notes').html(invoice_notes);
  print_job_book();
});


$('.date_daily').datepicker().on('changeDate', function(e){
  var date_id = $(this).attr('id');
  validate_progress_dates(date_id);
});

$('input#progress_payment_amount_value').click(function(){
  $(this).select();
});


$('.vr_paid').click(function(){
  var progress_id = $(this).attr('id');
  $("input#invoice_payment_reference_no").focus().click();
  var progres_text = 'Variation';
  var total_cost_progress = $('.variation_total_cost').text();
  var progress_outstanding = $(this).parent().parent().parent().find('.vr_outstanding').text();

  var progress_id_arr = progress_id.split("_");
  var project_id = progress_id_arr['0'];
  var invoice_id = progress_id_arr['1'];



  if($.trim($("selector").html())==''){}
    $('.po_total_mod').text(total_cost_progress);

  if(progress_outstanding != ''){
    progress_outstanding = removeCommas(progress_outstanding);
    total_cost_progress = progress_outstanding;
  }

  var project_gst_percent = $('.project_gst_percent_inv').text();
  project_gst_percent = parseFloat(project_gst_percent);
  project_gst_amount = total_cost_progress * (project_gst_percent/100);
  var inc_gst_cost = parseFloat(total_cost_progress) + parseFloat(project_gst_amount);


  inc_gst_cost = parseFloat(Number(inc_gst_cost).toFixed(2));

  $('.po_desc_mod').text(progres_text);
  $('#is_paid_check').prop('checked', true);
  //total_cost_progress = removeCommas(total_cost_progress);
  $('input#progress_payment_amount_value').val(total_cost_progress);
  $('input#progress_payment_amount_value_inc_gst').val(inc_gst_cost);
  $('input#progress_id').val(progress_id);
  $('input#invoice_id_progress').val(invoice_id);
  $('input#invoice_outstanding').val(progress_outstanding);

 // alert(po_total_mod_inc_gst);

 // var po_total_mod_inc_gst = po_total_mod_inc_gst.toFixed('2');
  $('.po_total_mod_inc_gst').text(inc_gst_cost);


  var data = project_id+'*'+invoice_id;

  $('.payment_history').empty();

  ajax_data(data,'invoice/list_payment_history','.payment_history');

});

$('.progress_paid').click(function(){
  var progress_id = $(this).attr('id');
  $("input#invoice_payment_reference_no").focus().click();
  var progres_text = $(this).parent().parent().find('.t-head').text();

  if($.trim($("selector").html())==''){}

  var total_cost_progress = $(this).parent().parent().find('.total_cost_progress').text();
  var progress_outstanding = $(this).parent().parent().find('.progress_outstanding').text();
  var invoice_id_progress = $(this).parent().parent().find('.progress-item').attr('id');
  
  var project_gst_percent = $('.project_gst_percent_inv').text();
  //project_gst_percent = parseFloat(project_gst_percent);
  var project_gst_amount = parseFloat(total_cost_progress) * (project_gst_percent/100);
  var inc_gst_cost = parseFloat(total_cost_progress) + parseFloat(project_gst_amount);
 

  var progress_id_arr = progress_id.split("_");
  var project_id = progress_id_arr['0'];
  var invoice_id = invoice_id_progress;

  var data = project_id+'*'+invoice_id;

  $('.payment_history').empty();

  ajax_data(data,'invoice/list_payment_history','.payment_history');



  if(progress_outstanding != ''){
    progress_outstanding = progress_outstanding.substring(1);
    progress_outstanding = removeCommas(progress_outstanding);
    total_cost_progress = progress_outstanding;

    project_gst_amount = total_cost_progress * (project_gst_percent/100);
    inc_gst_cost = parseFloat(total_cost_progress) + project_gst_amount;
  }

  inc_gst_cost = parseFloat(Number(inc_gst_cost).toFixed(2));



  //inc_gst_cost = numberWithCommas(inc_gst_cost);


  $('.po_total_mod').text(total_cost_progress);
  $('.po_total_mod_inc_gst').text(inc_gst_cost);

  if(progres_text.length == 1){
    progres_text = $('input.final_payment').val();
  }


  $('.po_desc_mod').text(progres_text);
  $('#is_paid_check').prop('checked', true);
  //total_cost_progress = removeCommas(total_cost_progress);
  $('input#progress_payment_amount_value').val(total_cost_progress);
  $('input#progress_payment_amount_value_inc_gst').val(inc_gst_cost);
  $('input#progress_id').val(progress_id);
  $('input#invoice_id_progress').val(invoice_id_progress);
  $('input#invoice_outstanding').val(progress_outstanding);



});

$('#payment_modal').on('hidden.bs.modal', function (e) {
  $("#po_date_value").val(strDate);
  $("#progress_payment_amount_value").val('');
  $("#invoice_payment_reference_no").val('');
  $('#is_paid_check').prop('checked', false);
  $("#po_notes_value").val('');
  $('.po_balance_mod').text('$0.00');
  $("#invoice_outstanding").val('');
});


$('#progress_payment_amount_value_inc_gst').click(function(){
  $(this).focus();
  $(this).select();
});

$('#progress_payment_amount_value_inc_gst').on("keyup", function(e) {
  var payment_value_inc_gst = removeCommas($(this).val());
  var invoice_outstanding = $('input#invoice_outstanding').val();


  if(Math.sign(payment_value_inc_gst) < 0){
    var sign = '-';
  }else{
    var sign = '';    
  }

  var project_gst_percent = parseInt($('.project_gst_percent_inv').text());

  var inc_gst_cost = payment_value_inc_gst - (payment_value_inc_gst / ((project_gst_percent+100)/project_gst_percent));

  var ex_gst_amount = inc_gst_cost || '';

  ex_gst_amount = ex_gst_amount.toFixed(2);

  $('#progress_payment_amount_value').val(ex_gst_amount);

  


  var payment_value = ex_gst_amount;
  var amount_to_pay = $('.po_total_mod').text();


  if(invoice_outstanding != ''){
    amount_to_pay = invoice_outstanding;
  }

  amount_to_pay = removeCommas(amount_to_pay);

  var balance = amount_to_pay - payment_value;



  balance = Math.abs(balance);

  if(balance <= 0){
    $('#is_paid_check').prop('checked', true);
  }else{
    $('#is_paid_check').prop('checked', false);
    
  }

  balance = numberWithCommas(balance);

  $('.po_balance_mod').text('$'+sign+balance);

  if($(this).val() == ''){
    $('#is_paid_check').prop('checked', false);
  }


});


var total_invoiced_row = $('input.total-invoiced-row').val();
var total_invoiced_outstanding_row = $('input.total-invoiced-outstanding-row').val();

var total_paid_row = $('input.total-paid-row').val();


/*
$('#invoice_paid_table_wrapper #invoice_paid_table_filter').append('<p class="m-top-5 m-right-15" style="float:right;">Total Paid (ext-gst): <strong class="ex-gst total-paid-head"> $'+total_paid_row+'</strong>  </p>')

$('#invoice_table_wrapper #invoice_table_filter').append('<p class="m-top-5" style="float:left;">Total Invoice (ext-gst): <strong class="total-invoiced-head ex-gst"> $'+total_invoiced_row+'</strong>  &nbsp; Outstanding (ex-gst): <strong class="total-outstanding-head ex-gst"> $'+total_invoiced_outstanding_row+'</strong></p>')

$('#invoice_table_wrapper #invoice_table_filter').prepend('<button class="btn btn-sm btn-primary pull-right m-left-10 m-right-5" onclick="open_invoice_filter()">Open Filter Screen</button>');
*/

$('#progress_payment_amount_value').on("keyup", function(e) {

  var payment_value = $(this).val();
  var amount_to_pay = $('.po_total_mod').text();

  var invoice_outstanding = $('input#invoice_outstanding').val();

  if(invoice_outstanding != ''){
    amount_to_pay = invoice_outstanding;
  }

  if(Math.sign(payment_value) < 0){
    var sign = '-';
  }else{
    var sign = '';    
  }


  payment_value = removeCommas(payment_value);
  amount_to_pay = removeCommas(amount_to_pay);

  var balance = amount_to_pay - payment_value;

  balance = balance.toFixed(2);

  balance = Math.abs(balance);

  if(balance <= 0){
    $('#is_paid_check').prop('checked', true);
  }else{
    $('#is_paid_check').prop('checked', false);
    
  }

  $('.po_balance_mod').text('$'+sign+balance);





  if($(this).val() == ''){
    $('#is_paid_check').prop('checked', false);
  }


  var project_gst_percent = $('.project_gst_percent_inv').text();
  project_gst_percent = parseFloat(project_gst_percent);
  project_gst_amount = payment_value * (project_gst_percent/100);
  var inc_gst_cost = parseFloat(payment_value) + project_gst_amount;

  var inc_gst_cost_x = inc_gst_cost || '0.00';
 

  inc_gst_cost_x = parseFloat(Number(inc_gst_cost_x).toFixed(2));

  $('input#progress_payment_amount_value_inc_gst').val(inc_gst_cost_x);

});





$('#is_paid_check').click(function(){
  var total = $('.po_total_mod').text();
  var invoice_outstanding = $('input#invoice_outstanding').val();

  if(invoice_outstanding != ''){
    total = invoice_outstanding;
  }
  //total = total.slice(1);

  if (this.checked) {
    $('#progress_payment_amount_value').val(total);
    $('.po_balance_mod').text('$0.00');



    var project_gst_percent = $('.project_gst_percent_inv').text();


    project_gst_percent = parseFloat(project_gst_percent)/100;



   project_gst_percent = parseFloat(Number(project_gst_percent).toFixed(2));

    var project_gst_amount = total * project_gst_percent;
    var inc_gst_cost = parseFloat(project_gst_amount) + parseFloat(total);


    //inc_gst_cost = parseFloat(total) + parseFloat(inc_gst_cost);


   inc_gst_cost = parseFloat(Number(inc_gst_cost).toFixed(2));

    var inc_gst_cost_x = inc_gst_cost || '';   // the use of || mean if value is null assgin to another means OR


    $('input#progress_payment_amount_value_inc_gst').val(inc_gst_cost_x);




  }else{
    $('#progress_payment_amount_value_inc_gst').val('');
    $('#progress_payment_amount_value').val('');
    $('.po_balance_mod').text('$'+total);
  }


});


$('.check-a').on('switchChange.bootstrapSwitch', function (event, state) {
  if(!state){
    $(this).parent().parent().parent().find('.check-b').bootstrapSwitch('state', false);
  }
});

$('.check-b').on('switchChange.bootstrapSwitch', function (event, state) {
  if(state){
    $(this).parent().parent().parent().find('.check-a').bootstrapSwitch('state', true);
  }
});

$('.is_admin').on('switchChange.bootstrapSwitch', function (event, state) {
  if(state){
    $('input#chk_is_peon').val(1);
  }else{
    $('input#chk_is_peon').val(0);
  }
});


$('#new_password').keyup(function(e) {

  var inputPassword = $(this).val();
  var noSpacePassword = inputPassword.replace(/\s/g, ''); //remove space
  $(this).val(noSpacePassword);

 var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
 var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
 var enoughRegex = new RegExp("(?=.{6,}).*", "g");

 if (false == enoughRegex.test($(this).val())) {
   $('#passstrength').removeClass('.alert-info').removeClass('alert-warning').removeClass('alert-success').addClass('alert-danger').show();
   $('#passstrength').html('Add more charactes please, minimum of 8.');
   $('form.change_password_form').find('input#confirm_password').prop('disabled', true);

 } else if (strongRegex.test($(this).val())) {
   $('#passstrength').removeClass('.alert-info').removeClass('alert-warning').removeClass('alert-danger').addClass('alert-success').show();
   $('#passstrength').html('Strong Password!');
   $('form.change_password_form').find('input#confirm_password').prop('disabled', false);




 } else if (mediumRegex.test($(this).val())) {
   $('#passstrength').removeClass('.alert-info').removeClass('alert-danger').removeClass('alert-success').addClass('alert-warning').show();
   $('#passstrength').html('Try mixing it with Symbols, Numbers and a Upper-Case Letter for the new password.');
   $('form.change_password_form').find('input#confirm_password').prop('disabled', true);


 } else {
   $('#passstrength').removeClass('.alert-info').removeClass('alert-warning').removeClass('alert-success').addClass('alert-danger').show();
   $('#passstrength').html('Weak Password, are you sure about this?');
   $('form.change_password_form').find('input#confirm_password').prop('disabled', true);
 }

 var new_pass = $('#new_password').val();
 var confirm_password = $('#confirm_password').val();

 if(new_pass == confirm_password){
  $('form.change_password_form').find('.change_passwprd_button').remove();
  $('form.change_password_form').append('<input type="submit" name="update_password" value="Update Password" class="pull-right btn btn-danger m-right-5 m-bottom-10 change_passwprd_button">');
}else{
  $('form.change_password_form').find('.change_passwprd_button').remove();
}


return true;
});



$('#confirm_password').keyup(function(e) {

  var inputPassword = $(this).val();
  var noSpacePassword = inputPassword.replace(/\s/g, ''); //remove space
  $(this).val(noSpacePassword);

  var new_pass = $('#new_password').val();
  var confirm_password = $(this).val();

  if(new_pass == confirm_password){
    $('#passstrength').removeClass('.alert-info').removeClass('alert-warning').removeClass('alert-danger').addClass('alert-success').show();
    $('#passstrength').html('Password matched!');
    $('form.change_password_form').find('.change_passwprd_button').remove();
    $('form.change_password_form').append('<input type="submit" name="update_password" value="Update Password" class="pull-right btn btn-danger m-right-5 m-bottom-10 change_passwprd_button">');
  }else{
    $('#passstrength').removeClass('.alert-info').removeClass('alert-warning').removeClass('alert-success').addClass('alert-danger').show();
    $('#passstrength').html('Please Confirm your new password.');
    $('form.change_password_form').find('.change_passwprd_button').remove();
  }

});




$('.check-swtich').bootstrapSwitch();



$('.check-swtich').on('switchChange.bootstrapSwitch', function (event, state) {

 //   alert($(this).data('checkbox'));
    //alert(event);
   // alert(state);

   var accessVal = $(this).data('checkbox');

   if(!state){
     var accessVal = $(this).data('checkbox') - 1;
   }

   var inputId = $(this).parent().parent().parent().attr('class');

   $('#'+inputId).val(accessVal);
 });


$('input#cc_emails').keyup(function(e){

  var inputted_email = $(this).val();

  inputted_email = inputted_email.replace(";", "");
  inputted_email = inputted_email.replace(",", "");
  inputted_email = inputted_email.replace(" ", "");

  var currnt_emails = $('input.email-list').val();

  if(currnt_emails.length == 0){
    var new_set_email = inputted_email;
  }else{
    var new_set_email = currnt_emails+','+inputted_email;
  }

  if(inputted_email.length > 0){

    if(e.keyCode == 186){
      $('input.email-list').val(new_set_email);
      $('input#cc_emails').val('');
      $('.email-container').prepend('<button type="button" id="'+inputted_email+'" value="'+inputted_email+'" class="m-right-5 btn btn-default btn-xs inputted_email_btn pull-left m-bottom-5" onclick="remove_elam_email(this)">'+inputted_email+'</button>');
    }
    if(e.keyCode == 188){
      $('input.email-list').val(new_set_email);
      $('input#cc_emails').val('');
      $('.email-container').prepend('<button type="button" id="'+inputted_email+'" value="'+inputted_email+'" class="m-right-5 btn btn-default btn-xs inputted_email_btn pull-left m-bottom-5" onclick="remove_elam_email(this)">'+inputted_email+'</button>');
    }
    if(e.keyCode == 32){
      $('input.email-list').val(new_set_email);
      $('input#cc_emails').val('');
      $('.email-container').prepend('<button type="button" id="'+inputted_email+'" value="'+inputted_email+'" class="m-right-5 btn btn-default btn-xs inputted_email_btn pull-left m-bottom-5" onclick="remove_elam_email(this)">'+inputted_email+'</button>');
    } 
  }


});

$('.set_invoice_modal_submit').click(function(){
  var project_id = $('input.project_number').val();
  var id_bttn = $('input.progress_invoice_id').val();
  var cc_emails  = $('input#cc_emails').val();

  var job_book_notes = $('.job_book_notes').html();
  var invoice_item_amount = $('input.invoice_item_amount').val();
  var invoice_percent_value = $('input.invoice_percent_value').val();

  var invoice_notes = $('textarea#invoice_notes').val();

  invoice_notes = invoice_notes.replace(/'/g, '&apos;');
  invoice_notes = invoice_notes.replace(/\r?\n/g, '<br class="block"/>');



  var proj_ex_gst_total = $('input.project_total_raw').val();

  proj_ex_gst_total = Math.round(proj_ex_gst_total * 100) / 100;

  var progressArr = id_bttn.split("_");

  if(progressArr[0]=='F'){
    progressArr[1] = '';
  }
  

  if(progressArr[0]=='VR'){
    var proj_ex_gst_total = $('input.variation').val();
  }

  invoice_notes = '<div class="notes_line"><p class="clearfix"><strong><span class="pull-left block text-left">'+project_id+progressArr[0]+progressArr[1]+'&nbsp; - '+invoice_percent_value+'% of $'+numberWithCommas(proj_ex_gst_total)+'</span> <span class="pull-right block text-right"><strong>$'+numberWithCommas(invoice_item_amount)+' EX-GST</strong></span></strong></p><p class="clearfix"><br />'+invoice_notes+'</p></div>'+job_book_notes;


$('input.raw_invoice_notes').val(invoice_notes);

  var date_set_invoice_data = $('input.date_set_invoice_data').val();
  var job_book_details_id = $('input.job_book_details_id').val();

  $('.print_job_book_notes').html(invoice_notes);

  var data = project_id+'`'+id_bttn+'`'+cc_emails+'`'+invoice_notes+'`'+date_set_invoice_data+'`'+job_book_details_id+'`'+invoice_item_amount;
  $('#set_invoice_modal').modal('hide');

  //$('input#cc_emails').val('');

  $("iframe.frame_container").contents().find('.print_job_book_notes').html(invoice_notes);
  $("iframe.frame_container").contents().find('.canvas_area').each( function(){ 
    $(this).html($(this).html().replace(/style="/g,'pdf_mrkp_styl="'));
  });
  
  $('#set_invoice_modal').modal('hide');
  $('#pdf_editor').modal('hide');
  //$('show_loading').trigger('click');





  var canvas_area = $("iframe.frame_container").contents().find('.canvas_area').html();
  $('textarea#content').val(canvas_area);



 $('#loading_modal').modal({"backdrop": "static", "show" : true} );


  setTimeout(function(){
   $('input.submit_invoice_set').trigger('click');
  },500);







   //alert(data);
   //ajax_data(data,'invoice/set_invoice_progress','');
/*
   $.ajax({
    'url' : base_url+'invoice/set_invoice_progress',
    'type' : 'POST',
    'data' : {'ajax_var' : data },
    'success' : function(data){
      if(data){
        print_job_book();
        window.location.assign("?submit_invoice="+project_id);
      }
    }
  });

*/

 });


$('.pdf_editor_bttn').click(function(){

  var invoice_notes = $('textarea#invoice_notes').val();
  var id_bttn = $('input.progress_invoice_id').val();
  var job_book_notes = $('.job_book_notes').html();
  var project_id = $('input.project_number').val();
  var progressArr = id_bttn.split("_");
  var invoice_percent_value = $('input.invoice_percent_value').val();
  var proj_ex_gst_total = $('input.project_total_raw').val();
  var invoice_item_amount = $('input.invoice_item_amount').val();

  proj_ex_gst_total = Math.round(proj_ex_gst_total * 100) / 100;

  if(progressArr[0]=='F'){
    progressArr[1] = '';
  }

  invoice_notes = invoice_notes.replace(/'/g, '&apos;');
  invoice_notes = invoice_notes.replace(/\r?\n/g, '<br class="block"/>');
  invoice_notes = '<div class="notes_line"><p class="clearfix"><strong><span class="pull-left block text-left">'+project_id+progressArr[0]+progressArr[1]+'&nbsp; - '+invoice_percent_value+'% of $'+numberWithCommas(proj_ex_gst_total)+'</span> <span class="pull-right block text-right"><strong>$'+numberWithCommas(invoice_item_amount)+' EX-GST</strong></span></strong></p><p class="clearfix"><br />'+invoice_notes+'</p></div>';


  $("iframe.frame_container").contents().find('.print_job_book_notes').prepend(invoice_notes);
});

$('.save_progress_values').click(function(){
 $('#loading_modal').modal({"backdrop": "static", "show" : true} );
  update_project_invoice();
  $(this).hide();
  $('.update_progress_values').show();  

});

$('.update_progress_values_b').click(function(){

     $('#loading_modal').modal({"backdrop": "static", "show" : true} );

  delete_project_invoice_b();
  setTimeout(function(){
    update_project_invoice_b();
  },2000);
});


$('.update_progress_values').click(function(){

       $('#loading_modal').modal({"backdrop": "static", "show" : true} );

  delete_project_invoice();
  setTimeout(function(){
    update_project_invoice();
  },2000);
});


$('.edit_post').click(function(e){
 e.preventDefault();

 var title = $(this).parent().parent().find('.post_title').text();
 var post_content = $(this).parent().parent().find('span.hidden_post_details').text();



 var post_details = $(this).attr('id');
 var post_details_arr = post_details.split("*");

 $('input#expiry_date').val(post_details_arr['1']);
 $('input#post_id').val(post_details_arr['0']);
 $('input#is_urgent').val(post_details_arr['2']);
 $('input#post_title').val(title);
 $('textarea#post_content').val(post_content);


 if(post_details_arr['2'] == 1){
  $('input.set_urgent_edit').bootstrapSwitch('state', true);
}else{
  $('input.set_urgent_edit').bootstrapSwitch('state', false);

}


});

  // custom counter container and text
  $("#project_name").maxlength({
    counterContainer: $(".char-counter-remains"),
    text: '%left Characters left.'
  });



  $("input.letter_segment").click(function (e) {
    $(this).select();
  });


  $('#delete_company').click(function(){
    alert('Company Deleted');
  });


  $("input.letter_segment").keypress(function (e) {
    var inputValue = e.charCode;
    if ((inputValue > 47 && inputValue < 58) && (inputValue != 32)) {
      e.preventDefault();
    }
  });


  $(".upper_c_each_word").keyup(function (e) {
    var input = $(this).val();
    input = upperCaseEachWord(input);
    $(this).val(input);
  });


  $(".upper_c_first_word_sentence").keyup(function (e) {
    var input = $(this).val();
    input = sentenceCase(input);
    $(this).val(input);
  });

  


  var error_company_filter = 0;
  $("input.letter_segment").keyup(function (e) {

    var out = 0;
    var v = this.value.toUpperCase();
    v = v.charAt(0);

    if(is_alphabet(v)){
      $(this).val(v);
    }else{
      $(this).val('');
    }

    $(this).select();

    var my_id = $(this).attr('id');
    var my_input = parseInt(convertToNumbers($(this).val()));
    var starting_letter_segment = parseInt(convertToNumbers($('input#starting_letter_segment').val()));
    var end_letter_segment = parseInt(convertToNumbers($('input#end_letter_segment').val()));

    if(my_input > 0 && starting_letter_segment > 0 && end_letter_segment > 0){
      if(starting_letter_segment >= end_letter_segment){
        $(this).parent().addClass('has-error');
        error_company_filter = 1;
      }else{
        $('input.letter_segment').parent().removeClass('has-error');
        error_company_filter = 0;
      }
    }

  });



  $('select#invoice_status').change(function(){
    var invoice_status = $('select#invoice_status').val();

    if(invoice_status=='2'){
      $('.invoice_date').hide();
      $('.invoiced_date').show();
    }else{
      $('.invoice_date').show();
      $('.invoiced_date').hide();
    }

    $('input.invoice_date_a').val('');
    $('input.invoice_date_b').val('');
    $('input.invoiced_date_a').val('');
    $('input.invoiced_date_b').val('');

 
  });



  $('.invoice_filter_submit').click(function(){

    var project_number = $('.project_number').val();
    var progress_claim = $('select#progress_claim').val();
    var clinet = $('select#client_invoice').val();

    var invoice_date_a = $('.invoice_date_a').val();
    var invoice_date_b = $('.invoice_date_b').val();

    var invoiced_date_a = $('.invoiced_date_a').val();
    var invoiced_date_b = $('.invoiced_date_b').val();

    var invoice_status = $('select#invoice_status').val();
    var invoice_sort = $('select.invoice_sort').val();
    var project_manager = $('select.project_manager').val();
    var output_file = $('select.output_file').val();



/*

    invoice_date
invoiced_date
*/
    var has_error = 0;

    progress_claim = progress_claim || '';

    invoice_status = invoice_status || '';

    if(if_first_date_higher(invoice_date_a,invoice_date_b)){
      $('.error_area').html('<div class="border-less-box alert alert-danger fade in pad-5 m-bottom-10"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><p>Please fix the errors.</p></div>')

      $('.invoice_date_a').parent().addClass('has-error');
      $('.invoice_date_b').parent().addClass('has-error');
      has_error = 1;

    }else{
     $('.error_area').empty();
     $('.invoice_date_a').parent().removeClass('has-error');
     $('.invoice_date_b').parent().removeClass('has-error');
     has_error = 0;

     $('#filter_invoice').modal('hide');
     $('#loading_modal').modal('show');
   }

   var data = project_number+'*'+progress_claim+'*'+clinet+'*'+invoice_date_a+'*'+invoice_date_b+'*'+invoice_status+'*'+invoice_sort+'*'+project_manager+'*'+invoiced_date_a+'*'+invoiced_date_b+'*'+output_file;
  //alert(data);
  $('.report_result').html('');

  
  if(has_error == 0){
    if(output_file == 'pdf'){

      $.ajax({
        'url' : base_url+'reports/invoice_report',
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
            window.open(baseurl+'reports/invoice_report?ajax_var='+data, '_blank');


    }


  }

});

$('.company_filter_submit').click(function(){
  var company_state = $('select.company_state').val();

  if (company_state === undefined || company_state === null) {
    $('select.company_state').parent().parent().addClass('has-error');
    error_company_filter = 1;
  }else{
    $('select.company_state').parent().parent().removeClass('has-error');
    error_company_filter = 0;
  }

  if(error_company_filter == 0){
    var timer = 30000;
    $('.error_area').html('');
    $('#filter_company').modal('hide');

    var company_type = $('select.company_type').val();
    var company_activity = $('select.company_activity').val();
    var starting_letter_segment = $('input#starting_letter_segment').val();
    var end_letter_segment = $('input#end_letter_segment').val();
    var company_sort = $('select.company_sort').val();


    if (company_type == '') {
      company_type = '';
      timer = timer + 10000;
    }

    if (company_activity === undefined || company_activity === null) {
      company_activity = '';
      timer = timer + 10000;
    }

    var data = company_state+'*'+company_type+'*'+company_activity+'*'+starting_letter_segment+'*'+end_letter_segment+'*'+company_sort;

    $('.report_result').html('');
      //var my_pdf = return_ajax_data(data,'reports/company_report','.report_result','alert');

      $('#loading_modal').modal('show');


      // controller_method class/methodtho
      $.ajax({
        'url' : base_url+'reports/company_report',
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










     //}, timer);


}else{
  $('.error_area').html('<div class="border-less-box alert alert-danger fade in pad-5 m-bottom-10"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><p>Please fix the errors.</p></div>')
}
});

$('#filter_wip_table').click(function(){
  //get_wip_cost_total();

  var project_managerRaw = $(".select-pm-tbl").val();

  project_managerRaw = project_managerRaw.split("|");
  var project_manager = project_managerRaw[0];


  var filters = $(".select-client-tbl").val()+'*'+project_manager+'*'+$('#select-cat-tbl').val()+'*'+$('#finish_date_start').val()+'*'+$('#finish_date').val()+'*'+$('#cost_total').val();
  ajax_data(filters,'wip/dynamic_wip_table','.dynamic_wip_table');
  
  setTimeout(function(){
    //get_wip_cost_total();
  }, 1100);


});



/*
    $("select.find_contact_person").on("change", function(e) {
      
          var myVal = $(this).val();
          var controller_method = 'projects/find_sub_client';

          $('select#sub_client').empty();
          var classLocation = 'select#sub_client';
          ajax_data(myVal,controller_method,classLocation);
 
      
      //load_data_ajax($(this).val());
    }); //this is working select callbak!

*/




  $('select#type').on("change", function(e) {
    var this_val = $(this).val();   
    if ( this_val == 'Client|1' ) { 
          $('.sub_client_select').show();


          $('.parent_comp_section').hide();
          $('select#sub_client').val('None|0'); 
      }else{
          $('select#sub_client').val('None|0');  
          $('.sub_client_select').hide(); 

          $('.parent_comp_section').show();
      }
  }); 



// project search key press
$('input#search_project_num').keypress(function (e) {
  var key = e.which;
  var search_project = $(this).val();
  if(key == 13) {  // the enter key code
    if(search_project != ''){
      // alert( $(this).val() );
      $('#loading_modal').modal('show');
       $.ajax({
        'url' : base_url+'search/search_project',
        'type' : 'POST',
        'data' : {'project_id' : search_project },
        'success' : function(data){
          if(data && data > 0){
            setTimeout(function(){ window.location.assign(baseurl+"projects/view/"+data); }, 1000);
          }else{
            setTimeout(function(){
              $('#loading_modal').modal('hide');
              $('.search_result_projects').html(data);
              $('#projects_search_result').modal('show');
            }, 1000);
          }
        }
      });
    }
  }
});
// project search key press


$('.chosen_type').on("change", function(e) {
  $('select#activity').val(null).trigger("change");
});


$('select#data_year').on("change", function(e) {
  var year_selection = $(this).val();
  window.location.assign(baseurl+"dashboard/sales_forecast/"+year_selection);

 // window.location.assign(baseurl+"dashboard/sales_forecast/year_selection/"+year_selection);
});


$('select#calendar_view').on("change", function(e) {
  var calendar_view = $(this).val();
  var calendar_year = $('select#calendar_year').val();

  var current_url = window.location.href;
  var n = current_url.indexOf("?");
  var basic_url = current_url.substring(0, n);

  window.location = basic_url+'?calendar_view='+calendar_view+'&calendar_year='+calendar_year;



 // window.location.assign(baseurl+"dashboard/sales_forecast/year_selection/"+year_selection);
});


$('select#calendar_year').on("change", function(e) {
  var calendar_year = $(this).val();
  var calendar_view = $('select#calendar_view').val();

  var current_url = window.location.href;
  var n = current_url.indexOf("?");
  var basic_url = current_url.substring(0, n);

  window.location = basic_url+'?calendar_view='+calendar_view+'&calendar_year='+calendar_year;

 // window.location.assign(baseurl+"dashboard/sales_forecast/year_selection/"+year_selection);
});


$(".shopping_center_state").on("change", function(e) {
  var stateRaw = $(this).val().split("|");
  var data = stateRaw[3]+'|dropdown|state_id|'+stateRaw[1]+'|'+stateRaw[2];    
  ajax_data(data,'company/get_suburb_list','.shopping_center_suburb');    
});


$("select.shopping_center_suburb").change(function(){
    //$('select.shopping_center_suburb').empty();
    var shopping_center_suburb = $(this).val();
    var shopping_center_state = $('.shopping_center_state').val();

    var data = shopping_center_suburb+'|'+shopping_center_state;

    ajax_data(data,'projects/fetch_shopping_center_state_sub','.brand_shopping_center');

  });


$(".view_applied_settings").on("click", function(event) {
  event.preventDefault();
  $('.admin_settings').toggle();
  }); //this is working select callbak!




/*
$('.return_to_outstanding_values').click(function(){
  var po_date_value = $('.ret_po_date_value').val();
  var po_amount_value = $(".ret_po_amount_value").val();
  var po_reference_value = $(".ret_po_reference_value").val();
  var po_notes_value = $(".ret_po_notes_value").val();
  var po_number_item = $(".ret_po_number_item").val();
  var data = po_date_value+'*'+po_number_item+'*'+po_notes_value+'*'+po_reference_value+'*'+po_amount_value;
  ajax_data(data,'purchase_order/set_outstanding_po','.aread_test');
  window.location = baseurl+'purchase_order?reload=2#';

});
*/
/*
$('.return_outstanding_po_item').on("click", function(event) {
 
});
*/
/*
$('.select_po_item').on("click", function(event) {
  event.preventDefault();
});
*/

$('#invoice_po_modal').on('hidden.bs.modal', function (e) {
  $("#po_date_value").val(strDate);
  $("#po_amount_value").val('');
  $("#po_reference_value").val('');
  $('#po_is_reconciled_value').prop('checked', false);
  $("#po_notes_value").val('');
  $('#po_amount_value_inc_gst').val('');
  //$('.po_balance_mod').text('$0.00');
});
/*
$('#reconciliated_po_modal').on('hidden.bs.modal', function (e) {
  $('#set_outstanding').prop('checked', false);

  $(".ret_po_date_value").val('');
  $(".ret_po_amount_value").val('');
  $(".ret_po_reference_value").val('');
  $(".ret_po_notes_value").val('');
  //$('.po_balance_mod').text('$0.00');
})
*/
/*
$('#reconciliated_po_modal').on('shown.bs.modal', function (e) {
  
  var po_item_row_return = [];

  $('.return_outstanding').find("tr:first").find('td').each(function( index ){
    var item = $(this).text();
    po_item_row_return.push(item);
  });

  var recent_pay = po_item_row_return[1].slice(1);

  $('#set_outstanding').click(function(){
    //total = total.slice(1);

    if (this.checked) {
      $(".ret_po_date_value").val(po_item_row_return[0]);
      $(".ret_po_amount_value").val(recent_pay);
      $(".ret_po_reference_value").val(po_item_row_return[2]);
      $(".ret_po_notes_value").val(po_item_row_return[3]);
    }else{
      $(".ret_po_date_value").val('');
      $(".ret_po_amount_value").val('');
      $(".ret_po_reference_value").val('');
      $(".ret_po_notes_value").val('');
    }
  });
});
*/


$('#po_is_reconciled_value').click(function(){
/*
  var total = $('.po_actual_balance').val();
  var po_gst = $('#po_gst').val();
  //total = total.slice(1);

  var with_gst = parseFloat(removeCommas(total)) + parseFloat(removeCommas(total)*po_gst);

  if (this.checked) {
    $('#po_amount_value').val(total);
    $('.po_balance_mod').text('$0.00');
    $('#po_amount_value_inc_gst').val(numberWithCommas(with_gst));

  }else{

    $('#po_amount_value').val(0.00);
    $('.po_balance_mod').text('$'+total);
    $('#po_amount_value_inc_gst').val(0.00);

  }
  */
});






$(".chosen_alt").select2({    allowClear : true  }).removeClass('form-control');


$('#po_amount_value').click(function(){
  $(this).select(); 
});

$('#po_amount_value_inc_gst').click(function(){
  $(this).select(); 
});


$('.po_cancel_values').on("click", function(event) {
  $('#invoice_po_modal').modal('hide');
  $("#po_date_value").val(strDate);
  $("#po_amount_value").val('');
  $("#po_reference_value").val('');
  $('#po_is_reconciled_value').prop('checked', false);
  $("#po_notes_value").val('');


  $("#po_date_value").parent().parent().parent().removeClass('has-error');
  $("#po_amount_value").parent().parent().parent().removeClass('has-error');
  $("#po_reference_value").parent().parent().removeClass('has-error');
  $("#invoice_payment_reference_no").parent().parent().removeClass('has-error');
  $("#progress_payment_amount_value").parent().parent().parent().removeClass('has-error');




  $('.po_error').empty();
});




$('.payment_set_values').on("click", function(event) {

  var error = 0;
  var po_date_value = $("input#po_date_value").val();
  var progress_payment_amount_value = $("input#progress_payment_amount_value").val();
  var invoice_payment_reference_no = $("input#invoice_payment_reference_no").val();
  var invoice_id_progress = $('input#invoice_id_progress').val();

  //var po_is_reconciled_value = $("#po_is_reconciled_value").val();

  if ($("#is_paid_check").is( ":checked" )) {
    var is_paid_check = 1;
  }else{
    var is_paid_check = 0;
  }

  var po_notes_value = $("input#po_notes_value").val();
  var progress_id = $("input#progress_id").val();

  if(po_date_value == ''){
    $("input#po_date_value").parent().parent().parent().addClass('has-error');
    error = 1;
  }else{
    $("#po_date_value").parent().parent().parent().removeClass('has-error');
  }

  if(progress_payment_amount_value == '' ){
    $("input#progress_payment_amount_value").parent().parent().parent().addClass('has-error');
    error = 1;
  }else{
    $("#progress_payment_amount_value").parent().parent().parent().removeClass('has-error');    
  }

  if(invoice_payment_reference_no == ''){
    $("input#invoice_payment_reference_no").parent().parent().addClass('has-error');
    error = 1;
  }else{
    $("#invoice_payment_reference_no").parent().parent().removeClass('has-error');    
  }

  if (error == 0){
    var d = new Date();
    var strDate = d.getDate()+"/"+(d.getMonth()+1)+"/"+d.getFullYear();


    $("#po_date_value").parent().parent().parent().removeClass('has-error');
    $("#po_amount_value").parent().parent().parent().removeClass('has-error');
    $("#po_reference_value").parent().parent().removeClass('has-error');
    $("#invoice_payment_reference_no").parent().parent().removeClass('has-error');
    $("#progress_payment_amount_value").parent().parent().parent().removeClass('has-error');

    var outstanding = $('.po_balance_mod').text();
    
    var data = po_date_value+'*'+progress_id+'*'+po_notes_value+'*'+invoice_payment_reference_no+'*'+progress_payment_amount_value+'*'+is_paid_check+'*'+outstanding+'*'+invoice_id_progress;

    //alert(data);

    ajax_data(data,'invoice/progress_payment','.test');

    $("#po_date_value").val(strDate);
    $("#invoice_payment_reference_no").val('');
    //$('#is_paid_check').prop('checked', true);
    $("#po_notes_value").val('');

    $('.po_error').empty();
    var project_id = $('input.project_number').val();
    setTimeout(function(){

      $('#payment_modal').modal('hide');
      window.location.assign("?submit_invoice="+project_id);


    }, 1500);

  }else{
    $('.po_error').html('<div class="border-less-box alert alert-danger fade in pad-5 clearfix col-sm-12 "><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><p>Please complete the form having <strong>*</strong></p></div>');
  }

});

$('.po_set_values').on("click", function(event) {
  var error = 0;
  var po_date_value = $("#po_date_value").val();
  var po_amount_value = $("#po_amount_value").val();
  var po_reference_value = $("#po_reference_value").val();
  var po_amount_value_inc_gst = $("#po_amount_value_inc_gst").val();

  //var po_is_reconciled_value = $("#po_is_reconciled_value").val();

  if ($("#po_is_reconciled_value").is( ":checked" )) {
    var po_is_reconciled_value = 1;
  }else{
    var po_is_reconciled_value = 0;
  }

  var po_notes_value = $("#po_notes_value").val();
  var po_number_item = $("#po_number_item").val();

  if(po_date_value == ''){
    $("#po_date_value").parent().parent().parent().addClass('has-error');
    error = 1;
  }else{
    $("#po_date_value").parent().parent().parent().removeClass('has-error');
  }

  if(po_amount_value == '' || po_amount_value <= 0){
    $("#po_amount_value").parent().parent().parent().addClass('has-error');
    error = 1;
  }else{
    $("#po_amount_value").parent().parent().parent().removeClass('has-error');
  }

  if(po_reference_value == ''){
    $("#po_reference_value").parent().parent().addClass('has-error');
    error = 1;
  }else{
    $("#po_reference_value").parent().parent().parent().removeClass('has-error');
  }

  if(po_amount_value_inc_gst == '' || po_amount_value_inc_gst <= 0){
    $("#po_amount_value_inc_gst").parent().parent().addClass('has-error');
    error = 1;
  }else{
    $("#po_amount_value_inc_gst").parent().parent().removeClass('has-error');
  }

  if (error == 0){
    // changed 10/16/2015 jrv
    var po_no = $("#po_number_item").val();
    var po_reference_value = $("#po_reference_value").val();
    var data = po_no+'*'+po_reference_value;
    // changed 10/16/2015 jrv
    $.post(baseurl+"purchase_order/no_insurance_send_email", 
    {
      'ajax_var':data // changed 10/16/2015 jrv
    }, 
    function(result){
      if(result !== '0'){
       // alert(result);
        var d = new Date();
        var strDate = d.getDate()+"/"+(d.getMonth()+1)+"/"+d.getFullYear();

        $("#po_date_value").parent().parent().parent().removeClass('has-error');
        $("#po_amount_value").parent().parent().parent().removeClass('has-error');
        $("#po_reference_value").parent().parent().removeClass('has-error');
        $("#po_amount_value_inc_gst").parent().parent().removeClass('has-error');

        $('.submit_po_screen').trigger('click');
      }else{
        var d = new Date();
        var strDate = d.getDate()+"/"+(d.getMonth()+1)+"/"+d.getFullYear();

        $("#po_date_value").parent().parent().parent().removeClass('has-error');
        $("#po_amount_value").parent().parent().parent().removeClass('has-error');
        $("#po_reference_value").parent().parent().removeClass('has-error');
        $("#po_amount_value_inc_gst").parent().parent().removeClass('has-error');

        $('.submit_po_screen').trigger('click');
      }
    });

    //var data = po_date_value+'*'+po_number_item+'*'+po_notes_value+'*'+po_reference_value+'*'+po_amount_value+'*'+po_is_reconciled_value;

    //ajax_data(data,'purchase_order/insert_work_invoice','');


    
/*
    $("#po_date_value").val(strDate);
    $("#po_reference_value").val('');
    $('#po_is_reconciled_value').prop('checked', true);
    $("#po_notes_value").val('');
    $("#po_amount_value_inc_gst").val('');
    */
  //  window.location.reload(true);

//  $('.po_error').empty();

  //setTimeout(function(){ $('#invoice_po_modal').modal('hide'); window.location.assign(baseurl+"purchase_order?reload=1");}, 1000);

}else{
  $('.po_error').html('<div class="border-less-box alert alert-danger fade in pad-5 clearfix col-sm-12 "><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><p>Please complete the form having <strong>*</strong></p></div>');
}

});


$('#payment_amount_value').keyup(function( event ) {

  var total = $('.po_actual_balance').val();
  total = total.replace(/,/g,'');


  var po_amount_value = $(this).val().replace(/,/g,'');
  if(po_amount_value==''){
    po_amount_value = 0;
  }
  var balance = total - po_amount_value;

  if(balance <= 0 ){
    $('#po_is_reconciled_value').prop('checked', true);
  }else{
    $('#po_is_reconciled_value').prop('checked', false);
  }

  balance = balance.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
  $('.po_balance_mod').text('$'+balance);

  

});


$('.set_management_report').click(function(){
  var management_report_year = $('select#management_report_year').val();
  var management_report_pm = $('select#management_report_pm').val();
  var has_error = 0;

  if(management_report_year == '' ){
    $('select#management_report_year').parent().addClass('has-error');
    has_error = 1;
  }else{
    $('select#management_report_year').parent().removeClass('has-error');
  }


  if(management_report_pm == ''){
    $('select#management_report_pm').parent().addClass('has-error');
    has_error = 1;
  }else{
    $('select#management_report_pm').parent().removeClass('has-error');
  }

  if(has_error == 0){

    var link_page_to = base_url+'dashboard/management_report/'+management_report_year+'_'+management_report_pm;

    //console.log();
    //alert(base_url);

     //window.location.assign(link_page_to);
 

    window.open(link_page_to,'Print Management Report','width=1100px,height=800px,resizable=false,top=25,left=25,location=no,toolbar=no,menubar=no,scrollbars=yes');
    


  }



});


$( "#po_amount_value" ).keyup(function( event ) {

  var total = $('.po_actual_balance').val();
  total = total.replace(/,/g,'');

  var gst = $('#po_gst').val();


  var po_amount_value = $(this).val().replace(/,/g,'');
  if(po_amount_value==''){
    po_amount_value = 0;
  }
  var balance = total - po_amount_value;

  if(balance <= 0 ){
    $('#po_is_reconciled_value').prop('checked', true);
  }else{
    $('#po_is_reconciled_value').prop('checked', false);
  }

  balance = balance.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
  $('.po_balance_mod').text('$'+balance);

  var with_gst = parseFloat(po_amount_value) + parseFloat(po_amount_value*gst);


  $('#po_amount_value_inc_gst').val(numberWithCommas(with_gst));

  if(po_amount_value==''){
    $('#po_amount_value_inc_gst').val('');
  }  

});


$("#po_amount_value_inc_gst").keyup(function( event ) {

  var total = $('.po_actual_balance').val();
  total = total.replace(/,/g,'');

  var gst = ($('#po_gst').val() * 100);


  var po_amount_value_inc_gst = $(this).val().replace(/,/g,'');

  //var with_o_gst = parseFloat(po_amount_value_inc_gst) - parseFloat(total*gst);

  var with_o_gst = po_amount_value_inc_gst - (po_amount_value_inc_gst / ((gst+100)/gst));
  with_o_gst = with_o_gst.toFixed(2);


  if(po_amount_value_inc_gst==''){
    var po_amount_value = 0;
    $('#po_amount_value').val('');
  }else{
    $('#po_amount_value').val(with_o_gst);
  }

  var balance = total - with_o_gst;

  if(balance <= 0 ){
    $('#po_is_reconciled_value').prop('checked', true);
  }else{
    $('#po_is_reconciled_value').prop('checked', false);
  }

  balance = balance.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');

  $('.po_balance_mod').text('$'+balance);

  if(po_amount_value_inc_gst==''){
    $('.po_balance_mod').text('$0.00');
  }



});




/*
$('.stored_rfc').click(function(event){
  event.preventDefault();

  var record_values = $(this).attr('id');
  var record_values_arr = record_values.split("-");
  var index = 0;
  var basic_data = [];

  var rfc_id = $(this).parent().parent().attr('id');


  $('input#rfc_token').val(rfc_id);


  $('a.delete_cancel').attr('href','?delete_rfc='+rfc_id);


  $(this).parent().parent().find('td').each(function( index ) {
    basic_data.push($(this).text());
  });

  $('form#forecast_form').fadeOut(300,function(){

    $(".monthly_breakdown").find('input').each(function( index ) {
      $(this).val(record_values_arr[index]);
      index++;
    });

    $('select#data_year').val(basic_data[2]);
    $('select#data_type').val(basic_data[1]);

    if(basic_data[3] == 'Focus Shopfit Pty Ltd'){
      $('select#focus_company').val(5);
    }else if(basic_data[3] == 'Focus Shopfit NSW Pty Ltd'){
      $('select#focus_company').val(6);
    }else{
      $('select#focus_company').val();
    }  

    $('input#data_name').val(basic_data[0]);
    $('input#data_amount').val(basic_data[4]);

    $('form#forecast_form').attr('action',base_url+'dashboard/update_sales_forecast');

    $('strong.data_label').text('Update Data');
    $('input.data_submit').val('Update Data').removeClass('btn-success').addClass('btn-info');
    $('.form_forecast_update_tools').show();

  });

  $('form#forecast_form').fadeIn(300);

  $('form#forecast_form').find('input.form-control').each(function( index ) {
    $(this).parent().removeClass('has-error');
  });

});

*/

/*
$('.data_cancel').click(function(event){
  event.preventDefault();

  $('form#forecast_form').fadeOut(300,function(){
    document.getElementById("forecast_form").reset();
    $('form#forecast_form').attr('action',base_url+'dashboard/add_data_sales_forecast');
    $('.form_forecast_update_tools').hide();
    $('strong.data_label').text('New Data');
    $('input.data_submit').val('Save Data').removeClass('btn-info').addClass('btn-success');
  });

  $('form#forecast_form').fadeIn(300);
});
*/
/*
$('.add_data_chart').click(function(){

  var data_name = $('input.data_name').val();
  var year = $('input.year').val();
  var display_type = $('select.display_type').val();
  var values_item = $('textarea.value_items').val();
 

setTimeout(function () {
    chart.load({
        columns:[ [data_name, 660, 630,620, 650, 640, 660, 650] ],
        type: display_type,
        colors: { data_name: '#CB0073' }
    });
}, 2000);



chart.select([data_name]);

});
  
*/
/*

$("select.sf_chart_dateSelection").on("change", function(e) {
  var year_selection = $(this).val();
  window.location.assign(baseurl+"dashboard/sales_forecast/year_selection/"+year_selection);
});
*/
$("select#job_category").on("change", function(e) {

  var job_category = $(this).val();
/*
  if(job_category == 'Company'){

    $("#selectId > option").each(function() {
      alert(this.text + ' ' + this.value);
    });

  }
*/
  if(job_category == 'Maintenance'){
    $("select#project_manager").val('29');
    $("select#project_administrator").val('8');
    $("select#estimator").val('8');
  }else{
/*
    $("select#project_manager").val('');
    $("select#project_administrator").val('');
    $("select#estimator").val('');
    */
  }
});

$('.dropdown-toggle').click(function(){
  $(this).next(".dropdown-menu").toggle();
});

$(".set_copy_work").on("click", function(event) {
  event.preventDefault();
  $('#copy_works').modal('show');
  var myVal = $(this).val();
  if(myVal==1){
    $('.copy_works_box').addClass('copy_toggle_fixed_bottom');
  }else{
    $('.copy_works_box').removeClass('copy_toggle_fixed_bottom');
    $('select.copy_work_project_id').val('');
    $('.copy_work_project_id .select2-chosen').text('Select Existing Project');
    $('select.all_company_project').val('');
    $('.all_company_project .select2-chosen').text('Select Client');
  }
  }); //this is working select callbak!


var project_ids_wip = [];
$("table#wipTable").find('tbody tr').each(function( index ) {
  project_ids_wip.push($(this).find('td:first-child').text());
  project_ids_wip.toString();
});

ajax_data(project_ids_wip,'wip/sum_total_wip_cost','.totals_wip_default');

$('.read_more').on("click", function(event) {
  event.preventDefault(); 
  $(this).hide();
  $(this).next('.remain').show();
  $(this).next('.remain').next('.read_less').show();
});


$('.read_less').on("click", function(event) {
  event.preventDefault(); 
  $(this).hide();
  $(this).prev('.remain').hide();
  $(this).prev('.remain').prev('.read_more').show();
});



$('.box-widg-head .tabs').click(function(){
  var tab_target = $(this).attr('id');

  $('.box-widg-head .tabs').each(function( index ) {
    $(this).removeClass('active');
  });

  $(this).addClass('active');

  var target = tab_target+'_area';

  $('.tab_container .tab_area').hide();
  $('.tab_container #'+target).fadeIn();


  $(this).parent().parent().parent().find('.collapse.out').removeClass('out');

  $(this).parent().parent().parent().find('.collapse').css('height','auto').addClass('in');

});


var pattern_reoc = 'daily';
var no_end_occur = 1;

$('a.pattern_reoc').click(function(){
  pattern_reoc = $(this).attr('id');
});


$('button.reoccur_submit_now').click(function(){


  var range_datetime_picker_1 = $('.range_datetime_picker_1').val();

  var date_a = range_datetime_picker_1+' '+$('.time_ave_a').val();
  var date_b = range_datetime_picker_1+' '+$('.time_ave_b').val();

  var notes = $('.ave_notes').val();
  var pathname = window.location.pathname;
  var status = $('.ave_type').text().trim();

  var user_id_a = $('.prjc_user_id').val();
  var user_id_b = $('input#user_id_page').val();

  if(document.querySelector("#user_id_page")){
    var user_id = user_id_b;
  }else{
    var user_id = user_id_a;
  }

  var appointment_time_a = $('.appointment_time_a').val();
  var appointment_time_b = $('.appointment_time_b').val();

  var range_datetime_picker_2 = $('.range_datetime_picker_2').val();


  if ($('input.no_end_occur').is(':checked')) {
    var no_end_occur = 1;
  }else{
    var no_end_occur = 0;
  }

  var data_range_a = '';
  var data_range_b = '';

  if (pattern_reoc == 'daily') {
    data_range_a = '';
    data_range_b = daily_day_set.toString();

  } else if (pattern_reoc == 'weekly') {
    data_range_a = $('input.recur_every_week_val').val();
    data_range_b = daily_weekly_set.toString();

  } else if (pattern_reoc == 'monthly') {
    data_range_b = $('input.pattern_of_monthly_day').val();
    data_range_a = $('input.recur_every_month_val').val();    

  } else if (pattern_reoc == 'yearly') {
    data_range_a = $('select.pattern_of_yearly_month').val();
    data_range_b = $('input.pattern_of_yearly_day').val();

  } else {

  }

  if(data_range_b == ''){
    alert('Please select days.')

  }else{
    $('#setting_reoccurrence').modal('hide');
    $('#loading_modal').modal({"backdrop": "static", "show" : true} );



    var data = date_a+'`'+date_b+'`'+notes+'`'+user_id+'`'+pathname+'`'+status;
    var reoccur = appointment_time_a+'`'+appointment_time_b+'`'+pattern_reoc+'`'+data_range_a+'`'+data_range_b+'`'+range_datetime_picker_1+'`'+range_datetime_picker_2+'`'+no_end_occur;

//alert(data);


$.post(baseurl+"users/set_availability_reoccur",{
  'ajax_var':data, 
  'reoccur':reoccur
}, 
function(result){

    // alert(result);

    if(result == '1'){

      setTimeout(function(){
        location.reload();
      },1000);

    }else{
      setTimeout(function(){
        $('#loading_modal').modal('hide');
      },1000);
      $('.ave_status_text').html(result+' <b class="caret"></b>');
    }
  }
  );
  }


});





  var daily_day_set = new Array();
  var daily_weekly_set = new Array();

$('#daily_p .rep_btn_cstm').click(function(){
  var day_btn = $(this).attr('id').split("_");

  //alert(day_btn);

  if($(this).hasClass('btn-primary') ){
    $(this).removeClass('btn-primary');

    var i = daily_day_set.indexOf(day_btn[0]);
    if(i != -1) {
      daily_day_set.splice(i, 1);
    }

  }else{
    $(this).addClass('btn-primary');
    daily_day_set.push(day_btn[0]);
  }

 // alert(daily_day_set.toString());
});

$('#weekly_p .rep_btn_cstm').click(function (){
  var day_btn = $(this).attr('id').split("_");

  //alert(day_btn);

  if($(this).hasClass('btn-primary') ){
    $(this).removeClass('btn-primary');

    var i = daily_weekly_set.indexOf(day_btn[0]);
    if(i != -1) {
      daily_weekly_set.splice(i, 1);
    }

  }else{
    $(this).addClass('btn-primary');
    daily_weekly_set.push(day_btn[0]);
  }

 // alert(daily_weekly_set.toString());

});

$('.set_reoccurrence').click(function(){
  var needs_date = 0;

  daily_day_set = new Array();
  daily_weekly_set = new Array();

  var day_start_ave = $('.day_set_non_rec').val();


  $('#summ_status_text').text( $('.ave_type').text()  );
  $('#summ_starting_date').text( day_start_ave );

  


  if($('input.time_ave_a').val() != ''){
    var date_time_start = $('input.time_ave_a').val();
    $('input.time_ave_a').parent().removeClass('has-error');
  }else{
    var date_time_start = '';
    needs_date = 1;
    $('input.time_ave_a').parent().addClass('has-error');
  }

  $('.range_datetime_picker_1').val(day_start_ave);
  $('.appointment_time_a').val(date_time_start);

  if($('input.time_ave_b').val() != ''){
    var date_time_end = $('input.time_ave_b').val();
    $('input.time_ave_b').parent().removeClass('has-error');
  }else{
    var date_time_end = '';
    $('input.time_ave_b').parent().addClass('has-error');
    needs_date = 1;
  }

  $('.appointment_time_b').val(date_time_end);

  if( $('.day_set_non_rec').val() != ''){
    var date_raw = $('.day_set_non_rec').val().split("/");
  }else{
    var date_raw = '';
    needs_date = 1;
  }

  if(needs_date == 0){
    $("#set_availability").modal('hide');
   $('#setting_reoccurrence').modal('show');
  } 

$('#summ_time').text( date_time_start+' - '+date_time_end );

  $('.pattern_of_monthly_day').val(date_raw[0]);
  $('.pattern_of_yearly_day').val(date_raw[0]);
  $('select.pattern_of_yearly_month').val(date_raw[1]);

  $('button.rep_btn_cstm').removeClass('btn-primary');
  $('button.rep_btn_cstm').removeClass('btn-primary');

  $('input.recur_every_week_val').val('1');
  $('input.recur_every_month_val').val('1');
  
  $('.no_end_occur').prop('checked', true);
  $('.range_datetime_picker_2').val('').prop('disabled', true);

});

$('.no_end_occur').click(function(){


  if(this.checked) {

    $('input.range_datetime_picker_2').val('').prop('disabled', true);
    $('#summ_end_date').text('No End');
  }else{

    $('input.range_datetime_picker_2').val('').prop('disabled', false);
  }

});






$('button.set_full_day').click(function(){
/*
  var currentdate = new Date();

  if(currentdate.getHours() > 12){
    var time_set = 'PM';
  }else{
    var time_set = 'AM';
  }

  var hours = currentdate.getHours() % 12 || 12;

  var set_month = (currentdate.getMonth()+1) < 10 ? '0' + (currentdate.getMonth()+1) : (currentdate.getMonth()+1);
*/

 // var current_date_time_a = currentdate.getDate() + "/" + set_month + "/" + currentdate.getFullYear() + " 07:00 AM";



 // var current_date_time_b = currentdate.getDate() + "/" + set_month + "/" + currentdate.getFullYear() + " 05:00 PM";


  $('input.time_ave_a').val("07:00 AM");
  $('input.time_ave_b').val("05:00 PM");


});


$('.print-wip').on("click", function(event) {
  event.preventDefault();
  var totals_wip = $('.totals_wip').html();
  var has_error = 0;

  var wip_client = $('select.report_company').val();
  var wip_pm = $('.select-pm-tbl').val();
  var wip_find_start_finish_date = $('#finish_date_start').val();
  var wip_find_finish_date = $('#finish_date').val();
  var wip_cost_total = $('#cost_total').val();
  var selected_cat = $('#select-cat-tbl').val();


  var wip_start_date_start_a = $('#start_date_start').val();
  var wip_start_date_b = $('#start_date').val();
  
  var doc_type = $('#doc_type').val();

  var date_created_start = $('#date_created_start').val();
  var date_created = $('#date_created').val();
  
  
  var un_acepted_start_date = $('#un_acepted_start_date').val();
  var un_acepted_end_date = $('#un_acepted_end_date').val();

  if(doc_type == 'WIP'){
    var prj_status = 'wip';
  }else{
    var prj_status = $('select#prj_status').val();
  }

 




  $('#loading_modal').modal('show');
  



  $('.report_result').html('');


  if(has_error == 0){
    setTimeout(function(){

      var wip_project_total = ''; // $('.wip_project_total').html();
      var wip_project_estimate = ''; // $('.wip_project_estimate').html();
      var wip_project_quoted = ''; // $('.wip_project_quoted').html();
      var wip_project_total_invoiced = ''; // $('.wip_project_total_invoiced').html();

      var wip_sort = $('select#wip_sort').val();

      var data = wip_client+'*'+wip_pm+'*'+wip_find_start_finish_date+'*'+wip_find_finish_date+'*'+wip_cost_total+'*'+selected_cat+'*'+wip_project_total+'*'+wip_project_estimate+'*'+wip_project_quoted+'*'+wip_project_total_invoiced+'*'+wip_sort+'*'+wip_start_date_start_a+'*'+wip_start_date_b+'*'+doc_type+'*'+date_created_start+'*'+date_created+'*'+prj_status+'*'+un_acepted_start_date+'*'+un_acepted_end_date;

 // alert(data);

      $.ajax({
        'url' : base_url+'reports/wip_report',
        'type' : 'POST',
        'data' : {'ajax_var' : data },
        'success' : function(data){
          if(data){
            $('#loading_modal').modal('hide');
            $('.report_result').html(data);
            window.open(baseurl+'docs/temp/'+data+'.pdf', '', 'height=600,width=850,top=100,left=100,location=no,toolbar=no,resizable=yes,menubar=no,scrollbars=yes',true);
          }
        }
      });  


    }, 1000);  
}



var wipTable = $('#wipTable').dataTable();
wipTable.fnFilter();
});



$(".state-option-c").on("change", function(e) {
  var stateRaw = $(this).val().split("|");
  var data = stateRaw[3]+'|dropdown|state_id|'+stateRaw[1]+'|'+stateRaw[2];    
  ajax_data(data,'company/get_suburb_list','#suburb_c');
  $('#postcode_c').empty().append('<option value="">Choose a Postcode...</option>');
  $('.suburb-option-c .select2-chosen').text("Choose a Suburb...");
  $('.postcode-option-c .select2-chosen').text("Choose a Postcode...");
  }); //this is working select callbak!


$(".suburb-option-c").on("change", function(e) {
  var setValRaw = $(this).val().split("|");
  var data = setValRaw[0];    
  ajax_data(data,'company/get_post_code_list','#postcode_c');
  if(data == ''){
    $('#postcode_c').empty().append('<option value="">Choose a Postcode...</option>');
  }
  $('.postcode-option-c .select2-chosen').text("Choose a Postcode...");
  }); //this is working select callbak!

/*
$(".add_shopping_center_project").on("click", function(event){

  var brand = $('#brand').val();
  var streetNumber = $('#street-number').val();
  var street = $('#street-c').val();
  var state = $('#state_c').val();
  var suburb = $('#suburb_c').val();
  var postcode = $('#postcode_c').val();
  var common_name = $('#common_name').val();
  var error = 0;

  if(brand==''){
    error = 1;
    $('#brand').parent().parent().addClass('has-error');
  }else{
    $('#brand').parent().parent().removeClass('has-error');
  }

  if(street==''){
    error = 1;
    $('#street-c').parent().parent().addClass('has-error');
  }else{
    $('#street-c').parent().parent().removeClass('has-error');
  }

  if(state==''){
    error = 1;
    $('#state_c').parent().parent().addClass('has-error');
  }else{
    $('#state_c').parent().parent().removeClass('has-error');
  }

  if(suburb==''){
    error = 1;
    $('#suburb_c').parent().parent().addClass('has-error');
  }else{
    $('#suburb_c').parent().parent().removeClass('has-error');
  }

  if(postcode==''){
    error = 1;
    $('#postcode_c').parent().parent().addClass('has-error');
  }else{
    $('#postcode_c').parent().parent().removeClass('has-error');
  }

  if(error == 0){
    var data = brand+'*'+streetNumber+'*'+street+'*'+state+'*'+suburb+'*'+postcode+'*'+common_name;
  //  ajax_data(data,'shopping_center/dynamic_add_shopping_center','');
    $('#brand').val('');
    $('#street-number').val('');
    $('#street-c').val('');
    $('#state_c').val('');
    $('#suburb_c').val('');
    $('#postcode_c').val('');
    $('#common_name').val('');

    $('.state-option-c .select2-chosen').text('Choose a State');
    $('.suburb-option-c .select2-chosen').text('Choose a suburb...');
    $('.postcode-option-c .select2-chosen').text('Choose a Postcode...');

    // $('select.brand_shopping_center').append('<option value="'+brand+'">'+brand+'</option>');
    alert(brand+' is now available for selection under Shopping Center!');

    var tbl_state_arr = state.split('|');
    var tbl_state = tbl_state_arr['1'];

    var tbl_suburb_arr = suburb.split('|');
    var tbl_suburb = upperCaseEachWord(tbl_suburb_arr['0']);
   }
});
*/


});
