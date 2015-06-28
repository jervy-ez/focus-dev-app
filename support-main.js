function get_wip_cost_total(){
  var project_ids_wip = [];
  $(".dataTables_scrollBody").find('table tbody tr').each(function( index ) {
    project_ids_wip.push($(this).find('td.sorting_1').text());
    project_ids_wip.toString();
  });

  ajax_data(project_ids_wip,'wip/sum_total_wip_cost','.totals_wip');
}

function remove_last(po_trans_id,work_id,joinery_id){
  var data = po_trans_id+'*'+work_id+'*'+joinery_id;
  ajax_data(data,'purchase_order/remove_last_trans','');
  setTimeout(function(){ $('#reconciliated_po_modal').modal('hide'); }, 1000);
  window.location.assign(baseurl+"purchase_order?reload=1");
}

function count_percent(){
  var progres_percent_total = 0;
  $('.progress-body').find('tr td input.progress-percent').each(function(){
    progres_percent_total = progres_percent_total + parseInt($(this).val());   
  });

  return progres_percent_total;
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
  var limit = Math.abs(Math.abs(value - count_percent()) - 100);

  if(value > 0 && percent_remain > 0 && value != ''){
    $("input#"+var_elem_id).val(value);

    progres_total++;
    count = progres_total;

    $("input#"+var_elem_id).parent().parent().parent().before('<tr><td scope="row" class="t-head" id=""><div class="m-top-10 progress-item">Progress <span class="progress_counter"></span></div></th><td><div class="input-group"><div class="input-group-addon">%</div><input type="text" class="form-control progress-percent" onclick="getHighlight(\'progress-'+count+'-percent\')" onchange="progressPercent(this)" value="'+percent_remain+'" placeholder="Percent" id="progress-'+count+'-percent" name="progress-'+count+'-percent"/></div></td><td><input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" class="form-control date_daily_a text-left progress_date" id="progress-'+count+'-date" name="progress-'+count+'-date"></td><td><strong><div class="m-top-5">$<span class="total_cost_progress">00.00</span> ex-gst</div></strong></td><td></td><td><strong><span class="progress_outstanding"></span></strong></td></tr>');

  }else{
    $("input#"+var_elem_id).val(limit);
  }
  update_progress_each();


setTimeout(function(){

  $('.date_daily_a').datepicker().on('changeDate', function(e){
      validate_progress_dates();
  });

},1000);




}
 

function numberWithCommas(n, sep, decimals) {
    sep = sep || "."; // Default to period as decimal separator
    decimals = decimals || 2; // Default to 2 decimals

    return n.toLocaleString().split(sep)[0]
    + sep
    + n.toFixed(decimals).split(sep)[1];
  }

  var date_validate_count = 0;
var run_once = 0;
var run_once_more = 1;
  function progressPercent(element_obj){
    value = element_obj.value;
    var_elem_id = element_obj.getAttribute("id");
    var percent_remain = 100 - count_percent();
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

    $("input#"+var_elem_id).val(new_val);


  }else{

    var next_item = $("input#"+var_elem_id).parent().parent().parent().next().find('input.progress-percent').attr('id')
    $("input#"+var_elem_id).parent().parent().parent().remove();

    var next_item_val = $('input#'+next_item).val();
    var percent_remain = 100 - count_percent();

    var new_val = parseInt(next_item_val) + percent_remain;

    $('input#'+next_item).val(new_val);
  }

  update_progress_each();




  $('.date_daily_b').datepicker().on('changeDate', function(e){
    if(run_once == 0){
      validate_progress_dates();
      run_once_more = 1;
    }

    run_once++;

    if(run_once > 2){
      validate_progress_dates();      
    }

   
  });




}

function getHighlight(id){
  $('input#'+id).select();
  prev_data = $('input#'+id).val();
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



function validate_progress_dates(){

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



    for (var i = 0; i < date_values_lenght; i++) {
      
      if( date_sorted[i] >= date_values[i] ){
        $('input#'+date_ids[i]).parent().removeClass('has-error');
      }else{
        $('input#'+date_ids[i]).parent().addClass('has-error');
        has_error = 1;
      }
    }

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



function print_job_book(){
  var contents  = $("#job_book_area").html();
  var printWindow = window.open('', '', 'height=800,width=1000,top=100,left=100,location=no,toolbar=no,resizable=yes,menubar=no,scrollbars=yes');
  printWindow.document.write('<html><head>');
  printWindow.document.write('<link href="'+baseurl+'css/print.css" rel="stylesheet" type="text/css" />');
  printWindow.document.write('</head><body class="print_body">');
  printWindow.document.write('<img src="'+baseurl+'img/focus-logo-print.png" width="206" height="66" />');
  printWindow.document.write(contents);
  printWindow.document.write('<a href="#" onclick="this.parentNode.removeChild(this); window.print(); window.close();" class="print_bttn print_me_now">Print Now!</a>');
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

  ajax_data(data,'invoice/insert_invoice_progress','');
   
 });


window.location.assign("?submit_invoice="+project_id);
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

  ajax_data(data,'invoice/insert_invoice_few_progress','');
   
 });


window.location.assign("?submit_invoice="+project_id);

}




function update_progress_each(){
  var project_total_raw = $("input.project_total_raw").val();
  var progres_counter = 0;
  $('.progress-body').find('tr td input.progress-percent').each(function(){
    progres_counter++;    
    $(this).parent().parent().prev().find('.progress-item').text('Progress '+progres_counter);
    var progress_percent_val = project_total_raw*($(this).val() / 100);

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


  $('#po_is_reconciled_value').prop('checked', true);
  $('.po_number_mod').text(po_item_row[0]);
  $('.po_desc_mod').text(po_item_row[2]);
  $('.po_total_mod').text('$'+po_item_row[8]);
  $('.po_balance_mod').text('$0.00');
  $('#po_amount_value').val(po_item_row[9]);
  $('.po_number_item').val(po_item_row[0]);
  $('.po_actual_balance').val(po_item_row[9]);

  var data = po_item_row[0];
  ajax_data(data,'purchase_order/po_history','.return_outstanding');



}






function select_po_item(obj_id){
  var po_item_row = [];
  $('a#'+obj_id).parent().parent().find('td').each(function(){
    var item = $(this).text();
    po_item_row.push(item);

    //alert(item);
  });

  $('#po_is_reconciled_value').prop('checked', true);

  $('.po_number_mod').text(po_item_row[0]);
  $('.po_desc_mod').text(po_item_row[2]);
  $('.po_total_mod').text('$'+po_item_row[8]);
  $('.po_balance_mod').text('$0.00');
  $('#po_amount_value').val(po_item_row[9]);
  $('.po_number_item').val(po_item_row[0]);
  $('.po_actual_balance').val(po_item_row[9]);

  var data = po_item_row[0];

  ajax_data(data,'purchase_order/po_history','.po_history');
}


function progress_invoice(element_obj){
  var progress_invoice = element_obj.getAttribute("id");
    $('input.progress_invoice_id').val(progress_invoice);
    var progress_cost_value = $('#'+progress_invoice).parent().parent().parent().find('.total_cost_progress').text();
    $('input.invoice_item_amount').val(progress_cost_value);
}


$(document).ready(function(){

  jQuery.fn.reverse = [].reverse;

  $('.progress_invoice').click(function(){
    var progress_invoice = $(this).attr('id');
    var progress_cost_value = $(this).parent().parent().parent().find('.total_cost_progress').text();


    $('input.progress_invoice_id').val(progress_invoice);


    $('input.invoice_item_amount').val(progress_cost_value);
  });


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


$('.remove_invoice').click(function(){
  var invoice_raw = $(this).attr('id');
  var invoice_arr = invoice_raw.split("-");

  $('.progress_invoice_button').remove();

  var progress = invoice_arr['1'];

  $(this).parent().parent().parent().parent().html('<div class="progress_invoice_button"><button class="btn btn-primary  m-right-5 progress_invoice" id="'+progress+'" data-toggle="modal" onclick="progress_invoice(this);" data-target="#set_invoice_modal"><i class="fa fa-file-text-o"></i> Set Invoice</button></div>');

  var invoice_id = invoice_arr['0'];
  var project_id = invoice_arr['2'];

  $(".job_book_notes").find('.notes_line').last().remove();
  var job_book_notes =  $(".job_book_notes").html();

  var data = invoice_id+'*'+project_id+'*'+job_book_notes;
  ajax_data(data,'invoice/un_invoice_item','');

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
  validate_progress_dates();
});

$('input#progress_payment_amount_value').click(function(){
  $(this).select();
});

$('.progress_paid').click(function(){
  var progress_id = $(this).attr('id');
  $("input#invoice_payment_reference_no").focus().click();
  var progres_text = $(this).parent().parent().find('.t-head').text();

  if($.trim($("selector").html())==''){}

  var total_cost_progress = $(this).parent().parent().find('.total_cost_progress').text();

  var progress_outstanding = $(this).parent().parent().find('.progress_outstanding').text();
  var invoice_id_progress = $(this).parent().parent().find('.progress-item').attr('id');
  $('.po_total_mod').text(total_cost_progress);

  if(progress_outstanding != ''){
    progress_outstanding = progress_outstanding.substring(1);
    progress_outstanding = removeCommas(progress_outstanding);

    total_cost_progress = progress_outstanding;

  }


 // alert(progres_text+' '+total_cost_progress);

  $('.po_desc_mod').text(progres_text);
  $('#is_paid_check').prop('checked', true);
  //total_cost_progress = removeCommas(total_cost_progress);
  $('input#progress_payment_amount_value').val(total_cost_progress);
  $('input#progress_id').val(progress_id);
  $('input#invoice_id_progress').val(invoice_id_progress);
  $('input#invoice_outstanding').val(progress_outstanding);

  var progress_id_arr = progress_id.split("_");
  var project_id = progress_id_arr['0'];
  var invoice_id = invoice_id_progress;

  var data = project_id+'*'+invoice_id;

  $('.payment_history').empty();

  ajax_data(data,'invoice/list_payment_history','.payment_history');


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

$('#progress_payment_amount_value').on("keyup", function(e) {

  var payment_value = $(this).val();
  var amount_to_pay = $('.po_total_mod').text();

  var invoice_outstanding = $('input#invoice_outstanding').val();

  if(invoice_outstanding != ''){
    amount_to_pay = invoice_outstanding;
  }

  payment_value = removeCommas(payment_value);
  amount_to_pay = removeCommas(amount_to_pay);

  var balance = amount_to_pay - payment_value;



  if(balance <= 0){
    $('#is_paid_check').prop('checked', true);
  }else{
    $('#is_paid_check').prop('checked', false);
    
  }

  balance = numberWithCommas(balance);

  $('.po_balance_mod').text('$'+balance);





  if($(this).val() == ''){
    $('#is_paid_check').prop('checked', false);
  }




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

  }else{
    $('#progress_payment_amount_value').val('');
    $('.po_balance_mod').text('$'+total);

  }

});



  $('.set_invoice_modal_submit').click(function(){
    var project_id = $('input.project_number').val();
    var id_bttn = $('input.progress_invoice_id').val();
    var cc_emails  = $('input#cc_emails').val();

    var job_book_notes = $('.job_book_notes').html();
    var invoice_item_amount = $('input.invoice_item_amount').val();

    var invoice_notes = $('textarea#invoice_notes').val();
    invoice_notes = invoice_notes.replace(/\r?\n/g, '<br />');

    var progressArr = id_bttn.split("_");

    if(progressArr[0]=='F'){
      progressArr[1] = '';
    }

    invoice_notes = job_book_notes+'<div class="notes_line"><br /><p><strong>'+project_id+progressArr[0]+progressArr[1]+'<span class="pull-right"><strong>$'+invoice_item_amount+' EX-GST</strong></span></strong></p><p>'+invoice_notes+'</p></div>';



    var date_set_invoice_data = $('input.date_set_invoice_data').val();
    var job_book_details_id = $('input.job_book_details_id').val();

    $('.print_job_book_notes').html(invoice_notes);





    var data = project_id+'*'+id_bttn+'*'+cc_emails+'*'+invoice_notes+'*'+date_set_invoice_data+'*'+job_book_details_id;
    $('#set_invoice_modal').modal('hide');

    $('input#cc_emails').val('');

    //alert(data);

    ajax_data(data,'invoice/set_invoice_progress','');

    print_job_book();

    setTimeout(function(){
      window.location.assign("?submit_invoice="+project_id);
    },1000);


  });

  $('.save_progress_values').click(function(){
      update_project_invoice();
      $(this).hide();
      $('.update_progress_values').show();  

  });

  $('.update_progress_values_b').click(function(){
    delete_project_invoice_b();
    setTimeout(function(){
      update_project_invoice_b();
    },1000);
  });


  $('.update_progress_values').click(function(){
    delete_project_invoice();
    setTimeout(function(){
      update_project_invoice();
    },1000);
  });

  // custom counter container and text
  $("#project_name").maxlength({
    counterContainer: $(".char-counter-remains"),
    text: '%left Characters left.'
  });

  $('#filter_wip_table').click(function(){
    get_wip_cost_total();  
    var filters = $(".select-client-tbl").val()+'*'+$(".select-pm-tbl").val()+'*'+$('#select-cat-tbl').val()+'*'+$('#finish_date_start').val()+'*'+$('#finish_date').val()+'*'+$('#cost_total').val();
    ajax_data(filters,'wip/dynamic_wip_table','.dynamic_wip_table');
  });
  

  $(".view_applied_settings").on("click", function(event) {
    event.preventDefault();
    $('.admin_settings').toggle();
  }); //this is working select callbak!



  $('.filter-wip').on("click", function(event) {
    event.preventDefault();
  });

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
  var total = $('.po_actual_balance').val();
  //total = total.slice(1);

  if (this.checked) {
    $('#po_amount_value').val(total);
    $('.po_balance_mod').text('$0.00');
  }else{
    $('#po_amount_value').val(0.00);
    $('.po_balance_mod').text('$'+total);
  }

});

$('#po_amount_value').click(function(){
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

  if(progress_payment_amount_value == '' || progress_payment_amount_value <= 0){
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

    ajax_data(data,'invoice/progress_payment','.test');

    $("#po_date_value").val(strDate);
    $("#invoice_payment_reference_no").val('');
    $('#is_paid_check').prop('checked', true);
    $("#po_notes_value").val('');

  $('.po_error').empty();
  var project_id = $('input.project_number').val();
  setTimeout(function(){

    $('#payment_modal').modal('hide');
   window.location.assign("?submit_invoice="+project_id);


 }, 500);

}else{
  $('.po_error').html('<div class="border-less-box alert alert-danger fade in pad-5 clearfix col-sm-12 "><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><p>Please complete the form having <strong>*</strong></p></div>');
}

});

$('.po_set_values').on("click", function(event) {
  var error = 0;
  var po_date_value = $("#po_date_value").val();
  var po_amount_value = $("#po_amount_value").val();
  var po_reference_value = $("#po_reference_value").val();

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
  }

  if(po_amount_value == '' || po_amount_value <= 0){
    $("#po_amount_value").parent().parent().parent().addClass('has-error');
    error = 1;

  }

  if(po_reference_value == ''){
    $("#po_reference_value").parent().parent().addClass('has-error');
    error = 1;
  }

  if (error == 0){
    var d = new Date();
    var strDate = d.getDate()+"/"+(d.getMonth()+1)+"/"+d.getFullYear();

    $("#po_date_value").parent().parent().parent().removeClass('has-error');
    $("#po_amount_value").parent().parent().parent().removeClass('has-error');
    $("#po_reference_value").parent().parent().removeClass('has-error');


    
    var data = po_date_value+'*'+po_number_item+'*'+po_notes_value+'*'+po_reference_value+'*'+po_amount_value+'*'+po_is_reconciled_value;

    ajax_data(data,'purchase_order/insert_work_invoice','');

    $("#po_date_value").val(strDate);
    $("#po_reference_value").val('');
    $('#po_is_reconciled_value').prop('checked', true);
    $("#po_notes_value").val('');

  //  window.location.reload(true);

  $('.po_error').empty();
  setTimeout(function(){ $('#invoice_po_modal').modal('hide'); window.location.assign(baseurl+"purchase_order?reload=1");}, 1000);

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


$( "#po_amount_value" ).keyup(function( event ) {

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






$("select#job_category").on("change", function(e) {

  var job_category = $(this).val();

  if(job_category == 'Maintenance'){
    $("select#project_manager").val('29');
    $("select#project_administrator").val('8');
    $("select#estimator").val('8');
  }else{

    $("select#project_manager").val('');
    $("select#project_administrator").val('');
    $("select#estimator").val('');
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

get_wip_cost_total();


$('.print-wip').on("click", function(event) {
  event.preventDefault();
  var totals_wip = $('.totals_wip').html();

  var wip_client = $('.select-client-tbl').val();
  var wip_pm = $('.select-pm-tbl').val();
  var wip_find_start_finish_date = $('#finish_date_start').val();
  var wip_find_finish_date = $('#finish_date').val();
  var wip_cost_total = $('#cost_total').val();
  var selected_cat = $('#select-cat-tbl').val();

  var wip_project_total = $('.wip_project_total').html();
  var wip_project_estimate = $('.wip_project_estimate').html();
  var wip_project_quoted = $('.wip_project_quoted').html();
  var wip_project_total_invoiced = $('.wip_project_total_invoiced').html();

  var date_printed = $('header.page-header small').text();


  var contents  = $("#print_wip_table_area").html();

  var printWindow = window.open('', '', 'height=600,width=1200,top=100,left=100,location=no,toolbar=no,resizable=yes,menubar=no,scrollbars=yes');
  printWindow.document.write('<html><head>');
  printWindow.document.write('<link href="'+baseurl+'css/print.css" rel="stylesheet" type="text/css" />');
  printWindow.document.write('</head><body class="print_body">');
  printWindow.document.write('<img src="'+baseurl+'img/focus-logo-print.png" width="206" height="66" />');
  printWindow.document.write('<div class="header clearfix">   <p><strong class="pull-right">Work In Progress Report</strong>  ');


  if(wip_find_start_finish_date!='' || wip_find_finish_date!=''){
    printWindow.document.write('<strong>By Completion Date </strong>');
  }

  if(wip_find_start_finish_date!=''){
    printWindow.document.write(' From<strong>:'+wip_find_start_finish_date+'</strong>');
  }

  if(wip_find_finish_date!=''){
    printWindow.document.write(' To<strong>:'+wip_find_finish_date+'</strong>');
  }

  printWindow.document.write('</p></div>');


  printWindow.document.write(contents);

  printWindow.document.write('<p><br /></p><div class="totals clearfix"><p class="pull-right m-left-20"><strong>Invoiced</strong> '+wip_project_total_invoiced+'</p> <p class="pull-right m-left-20">'+wip_project_total+'</p> <p class="pull-right m-left-20">'+wip_project_quoted+'</p> <p class="green-estimate pull-right m-left-20">'+wip_project_estimate+'</p></div>');



  printWindow.document.write('<p><br /></p><div class="footer clearfix">');

  if(wip_pm!=''){
    printWindow.document.write('<p class="pull-left m-right-20">Project Manager: <strong>'+wip_pm+'</strong></p>');
  }


  printWindow.document.write('<p class="pull-left">Date Printed: <strong>'+date_printed+'</strong></p>');

  printWindow.document.write('<p class="pull-right">Category Selected: <strong>'+selected_cat+'</strong></p>');

  printWindow.document.write('</div>');





  printWindow.document.write('<a href="#" onclick="this.parentNode.removeChild(this); window.print(); window.close();" class="print_bttn print_me_now">Print Now!</a>');


  printWindow.document.write('</body></html>');
  printWindow.document.close();

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


$(".add_shopping_center_project").on("click", function(event){

  var brand = $('#brand').val();
  var streetNumber = $('#street-number').val();
  var street = $('#street-c').val();
  var state = $('#state_c').val();
  var suburb = $('#suburb_c').val();
  var postcode = $('#postcode_c').val();
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
    var data = brand+'*'+streetNumber+'*'+street+'*'+state+'*'+suburb+'*'+postcode;
    ajax_data(data,'shopping_center/dynamic_add_shopping_center','');
    $('#brand').val('');
    $('#street-number').val('');
    $('#street-c').val('');
    $('#state_c').val('');
    $('#suburb_c').val('');
    $('#postcode_c').val('');

    $('.state-option-c .select2-chosen').text('Choose a State');
    $('.suburb-option-c .select2-chosen').text('Choose a suburb...');
    $('.postcode-option-c .select2-chosen').text('Choose a Postcode...');
    $('select.brand_shopping_center').append('<option value="'+brand+'">'+brand+'</option>');
    alert(brand+' is now available for selection under Shopping Center!');


  }





});


});