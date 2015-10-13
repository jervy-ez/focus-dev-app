var segment_index = 5;  // 5 if live |||   6 is local

    //dynamic_value_ajax
    function ajax_data(value,controller_method,classLocation){
      // controller_method class/methodtho
      $.ajax({
        'url' : base_url+controller_method,
        'type' : 'POST',
        'data' : {'ajax_var' : value },
        'success' : function(data){
          var divLocation = $(classLocation);
          if(data){
            divLocation.html(data);
          }
        }
      });
    }
    //dynamic_value_ajax81
function site_start_onDateChange() {
  /*var proj_date = document.getElementById('project_date').value;
  var proj_date_parts = proj_date.split('/');
  proj_date = new Date(proj_date_parts[2],proj_date_parts[1]-1,proj_date_parts[0]); 
  var site_date = document.getElementById('site_start').value;
  var site_date_parts = site_date.split('/');
  site_date = new Date(site_date_parts[2],site_date_parts[1]-1,site_date_parts[0]); 
  if(proj_date > site_date){
    alert("Site Start Date cannot be later than the project Date");
    document.getElementById('site_start').value = "";
  }*/
}
function site_finish_onDateChange() {
  var proj_date = document.getElementById('project_date').value;
  var proj_date_parts = proj_date.split('/');
  proj_date = new Date(proj_date_parts[2],proj_date_parts[1]-1,proj_date_parts[0]); 
  var site_finish = document.getElementById('site_finish').value;
  var site_finish_parts = site_finish.split('/');
  site_finish = new Date(site_finish_parts[2],site_finish_parts[1]-1,site_finish_parts[0]); 
  var site_date = document.getElementById('site_start').value;
  var site_date_parts = site_date.split('/');
  site_date = new Date(site_date_parts[2],site_date_parts[1]-1,site_date_parts[0]); 
  /*if(proj_date > site_finish){
    alert("Site Finish Date cannot be later than the project Date");
    document.getElementById('site_finish').value = "";

  }else{*/
    if(site_date > site_finish){
      alert("Site Finish Date cannot be later than the Site Start Date");
      document.getElementById('site_finish').value = "";
    }
 // }
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(?:\d{3})+(?!\d))/g, ",");
}

var joinery_name = [];
var work_id = 0;
var work_contractor_id = 0;
var selected_work_contractor_id = 0;
var company_id = 0;
var user_id = 0;
var work_price = 0;
var work_price_edited = 0;
var work_estimate = 0;
var work_estimate_edited = 0;
var works_contrator_id = 0;
var work_cont_exgst = 0;
var work_cont_exgst_edited = 0;
var work_cont_incgst = 0;
var work_cont_incgst_edited = 0;
var work_is_selected = 0;
var proj_id = 0;
var file_name = "";
var ext = "";
var project_attachment_id = 0;
var attachment_type_id = 0;
var attach_type = "";
var show = 0;

var work_joinery_id = 0;
var work_joinery_unit_price = 0;
var work_joinery_unit_price_edited = 0;
var work_joinery_unit_estimated = 0;
var work_joinery_unit_estimated_edited = 0;
var work_joinery_price = 0;
var work_joinery_price_edited = 0;
var work_joinery_estimate = 0;
var work_joinery_estimate_edited = 0;
var quoted = 0;
var work_joinery_qty = 0;
var work_joinery_qty_edited = 0;
var joinery_work_id = 0;

var work_markup = 0;
var gst_rate = 0;
var inc_gst = 0;
var ex_gst = 0;
var client_attached = 0;
var contractor_set = 0;
var send_email = 0;
var no_cqr = 0;
var no_cpo = 0;
var job_date = 0;

var variation_id = 0;
var idleTime = 0;
var show_userlist = 0;

var log_in_try = 3;
if(typeof(Storage) !== "undefined") {
  localStorage.idle = 0;
} else {
  alert("Sorry, your browser does not support web storage...");
}
function get_project_id(){
  var url = $(location).attr('href').split("/").splice(0, 7).join("/");
  var segments = url.split( '/' );
  var segmentlength = segments.length;
  var proj_id = segments[segment_index].replace("#", ""); // changes to 6 - when local and 5 -  on live site
  return proj_id;
}
function num_to_month(month_num){
  var month = "";
  switch(month_num){
    case '01':
      month = "JAN";
      break;
    case '02':
      month = "FEB";
      break;
    case '03':
      month = "MAR";
      break;
    case '04':
      month = "APR";
      break;
    case '05':
      month = "MAY";
      break;
    case '06':
      month = "JUN";
      break;
    case '07':
      month = "JUL";
      break;
    case '08':
      month = "AUG";
      break;
    case '09':
      month = "SEP";
      break;
    case '10':
      month = "OCT";
      break;
    case '11':
      month = "NOV";
      break;
    case '12':
      month = "DEC";
      break;
  }
  return month;
}


  function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
  }

  function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
  }

  function checkCookie() {
    var user=getCookie("username");
    if (user != "") {
        alert("Welcome again " + user);
    } else {
       user = prompt("Please enter your name:","");
       if (user != "" && user != null) {
           setCookie("username", user, 30);
       }
    }
  }

$(document).ready(function() {
  $(document).click(function(){
    $("ul.dropdown-menu").hide();
    $("dropdown-menu").hide();
  });
//Dropbox================
window.dropbox_connect = function(project_id){
  $('#attachement_loading_modal').modal('show');
  window.open(baseurl+'attachments/dropbox_authenticate?proj_id='+project_id, '_self', true);
}

$.post(baseurl+"attachments/view_attachment_type",
{
  type: 1
},
function(result){
  $('#attachment_type').html(result);
});

$("#attachment_type").change(function(){
  var attachment_type = $("#attachment_type").val();
  if(attachment_type == 'Add_New'){
    $('.modal').modal('hide');
    $("#attachment_type_modal").modal('show');
    $.post(baseurl+"attachments/view_attachment_type",
    {
      type:2
    },
    function(result){
      $('#table_attachment_type').html(result);
      $("#txt_attachment_type").val("");
      $("#btn_add_attachment_type").show();
      $("#btn_update_attachment_type").hide();
      $("#btn_delete_attachment_type").hide();
    });
  }
});
//Dropbox================ 
//Insurance 
$("#attach_pl").click(function(){
  $(".insurance_title").html("Public Liability Insurance");
  $("#insurance_type").val(1);
});

$("#attach_wc").click(function(){
  $(".insurance_title").html("Workers Compensation Insurance");
  $("#insurance_type").val(2);
});

$("#attach_ip").click(function(){
  $(".insurance_title").html("Income Protection Insurance");
  $("#insurance_type").val(3);
});

$("#update_insurance_pl").click(function(){
  var comp_id = $("#company_id_data").val();
  var expiration = $("#pl_expiration").val();
  if(expiration == ""){
    $.post(baseurl+"company/if_insurance_not_exist",
    {
      comp_id: comp_id,
      ins_stat: 1
    },
    function(result){
      alert("You provide a blank Expiration Date, Insurance now removed!");
      $(".pl_insurance_"+comp_id).hide();
      $(".pl_sdate").val("");
      $(".pl_expdate").val("");
    });
  }else{
    $.post(baseurl+"company/update_insurance_exp_date",
    {
      comp_id: comp_id,
      insurance_type: 1,
      expiration: expiration
    },
    function(result){
      alert("Insurance Updated!");
    });
  }
  
});

$("#update_insurance_wc").click(function(){
  var expiration = $("#wc_expiration").val();
  var comp_id = $("#company_id_data").val();
  if(expiration == ""){
    $.post(baseurl+"company/if_insurance_not_exist",
    {
      comp_id: comp_id,
      ins_stat: 2
    },
    function(result){
      alert("You provide a blank Expiration Date, Insurance now removed!");
      $(".wc_insurance_"+comp_id).hide();
      $(".wc_sdate").val("");
      $(".wc_expdate").val("");
    });
  }else{
    $.post(baseurl+"company/update_insurance_exp_date",
    {
      comp_id: comp_id,
      insurance_type: 2,
      expiration: expiration
    },
    function(result){
      alert("Insurance Updated!");
    });
  }
});

$("#update_insurance_ip").click(function(){
  var expiration = $("#ip_expiration").val();
  var comp_id = $("#company_id_data").val();
  if(expiration == ""){
    $.post(baseurl+"company/if_insurance_not_exist",
    {
      comp_id: comp_id,
      ins_stat: 3
    },
    function(result){
      alert("You provide a blank Expiration Date, Insurance now removed!");
      $(".ip_insurance_"+comp_id).hide();
      $(".ip_sdate").val("");
      $(".ip_expdate").val("");
    });
  }else{
    $.post(baseurl+"company/update_insurance_exp_date",
    {
      comp_id: comp_id,
      insurance_type: 3,
      expiration: expiration
    },
    function(result){
      alert("Insurance Updated!");
    });
  }
});
$("#complete_print").click(function(){
  $("#contractorlist_modal_title").html("with Complete Insurance");
  $.post(baseurl+"company/filter_contractor_list",
  {
    filter: 1
  },
  function(result){
    //$("#contractor_list_filtered").html(result);
    var contents  = result;
    var printWindow = window.open('', '', 'height=700,width=1500,top=100,left=100,location=no,toolbar=no,resizable=no,menubar=no');
    printWindow.document.write('<html><head>');
    printWindow.document.write('<link href="'+baseurl+'css/print.css" rel="stylesheet" type="text/css" />');
    printWindow.document.write('</head><body class="print_body">');
    printWindow.document.write('<img src="'+baseurl+'img/focus-logo-print.png" width="206" height="66" />');
    printWindow.document.write('<div class="header clearfix">   <p><strong class="pull-right">Contractors List (with Complete Insurance)</strong> </div>');
    printWindow.document.write(contents);
    printWindow.document.write('<a href="#" onclick="this.parentNode.removeChild(this); window.print(); window.close();" class="print_bttn print_me_now">Print Now!</a>');
    printWindow.document.write('</body></html>');
    printWindow.document.close();
  });
});
$("#incomplete_print").click(function(){
  $("#contractorlist_modal_title").html("with Incomplete Insurance");
  $.post(baseurl+"company/filter_contractor_list",
  {
    filter: 2
  },
  function(result){
    //$("#contractor_list_filtered").html(result);

    var contents  = result;
    var printWindow = window.open('', '', 'height=700,width=1500,top=100,left=100,location=no,toolbar=no,resizable=no,menubar=no');
    printWindow.document.write('<html><head>');
    printWindow.document.write('<link href="'+baseurl+'css/print.css" rel="stylesheet" type="text/css" />');
    printWindow.document.write('</head><body class="print_body">');
    printWindow.document.write('<img src="'+baseurl+'img/focus-logo-print.png" width="206" height="66" />');
    printWindow.document.write('<div class="header clearfix">   <p><strong class="pull-right">Contractors List (with Incomplete Insurance)</strong> </div>');
    printWindow.document.write(contents);
    printWindow.document.write('<a href="#" onclick="this.parentNode.removeChild(this); window.print(); window.close();" class="print_bttn print_me_now">Print Now!</a>');
    printWindow.document.write('</body></html>');
    printWindow.document.close();
  });
});
window.clk_pl_insurance = function(comp_id){
  $.ajax({
    url: baseurl+'uploads/company/insurance/'+comp_id+'/'+comp_id+'_Public_Liability.pdf',
    type:'HEAD',
    error: function()
    {
      $.post(baseurl+"company/if_insurance_not_exist",
      {
        comp_id: comp_id,
        ins_stat: 1
      },
      function(result){
        alert("Insurance Doesn't Exist!");
        $(".pl_insurance_"+comp_id).hide();
        $(".pl_sdate").val("");
        $(".pl_expdate").val("");
      });
    },
    success: function()
    {
      var d = new Date();
      var n = d.getTime();
      window.open(baseurl+'uploads/company/insurance/'+comp_id+'/'+comp_id+'_Public_Liability.pdf?'+n);
    }
  });
  return false;
}

window.clk_wc_insurance = function(comp_id){
  $.ajax({
    url: baseurl+'uploads/company/insurance/'+comp_id+'/'+comp_id+'_Workers_Compensation.pdf',
    type:'HEAD',
    error: function()
    {
      $.post(baseurl+"company/if_insurance_not_exist",
      {
        comp_id: comp_id,
        ins_stat: 2
      },
      function(result){
        alert("Insurance Doesn't Exist!");
        $(".wc_insurance_"+comp_id).hide();
        $(".wc_sdate").val("");
        $(".wc_expdate").val("");
      });
    },
    success: function()
    {
        var d = new Date();
        var n = d.getTime();
        window.open(baseurl+'uploads/company/insurance/'+comp_id+'/'+comp_id+'_Workers_Compensation.pdf?'+n);
    }
  });
  return false;
}

window.clk_ip_insurance = function(comp_id){
  $.ajax({
    url: baseurl+'uploads/company/insurance/'+comp_id+'/'+comp_id+'_Income_Protection.pdf',
    type:'HEAD',
    error: function()
    {
      $.post(baseurl+"company/if_insurance_not_exist",
      {
        comp_id: comp_id,
        ins_stat: 3
      },
      function(result){
        alert("Insurance Doesn't Exist!");
        $(".ip_insurance_"+comp_id).hide();
        $(".ip_sdate").val("");
        $(".ip_expdate").val("");
      });
    },
    success: function()
    {
        var d = new Date();
        var n = d.getTime();
        window.open(baseurl+'uploads/company/insurance/'+comp_id+'/'+comp_id+'_Income_Protection.pdf?'+n);
    }
  });
  return false;
}
//Insurance

var variation_id = $("#var_id").val();
if(variation_id !== undefined){
  proj_id = get_project_id();
  $.post(baseurl+"works/var_works_total",
  {
    proj_id: proj_id,
    variation_id: variation_id
  },
  function(result){
    var works_totals = result.split( '/' );
    var t_price = works_totals[0];
    var t_estimate = works_totals[1];
    var t_quoted = works_totals[2];
    $("#var-work-total-price").val(t_price);
    $("#var-work-total-estimate").val(t_estimate);
    $("#var-work-total-quoted").val(t_quoted);
    $.post(baseurl+"works/fetch_gst_rate",{},function(result){ 
        gst_rate = result; 
        $.post(baseurl+"works/job_date_entered",
        {
          proj_id: proj_id
        },
        function(result){
          job_date = result;
        });
    });
  });
}

var url = $(location).attr('href').split("/").splice(0, 8).join("/");
var segments = url.split( '/' );
var segmentlength = segments.length;
if(segmentlength > segment_index){ // changes to 6 - when local and 5 - when on live site
  get_project_totals();
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(?:\d{3})+(?!\d))/g, ",");
}

function get_project_totals(){
  proj_id = get_project_id();
  $.post(baseurl+"works/works_total",
  {
    proj_id: proj_id
  },
  function(result){
    var works_totals = result.split( '/' );
    var t_price = works_totals[0];
    var t_estimate = works_totals[1];
    var t_quoted = works_totals[2];
    $("#work-total-price").val(t_price);
    $("#work-total-estimate").val(t_estimate);
    $("#work-total-quoted").val(t_quoted);
    $.post(baseurl+"works/fetch_gst_rate",{},function(result){ 
        gst_rate = result; 
        $.post(baseurl+"works/job_date_entered",
        {
          proj_id: proj_id
        },
        function(result){
          job_date = result;
          $.post(baseurl+"variation/get_variation_total",
          {
            proj_id: proj_id
          },
          function(result){
            var var_totals = result.split( '|' );
            var t_accepted = var_totals[0];
            var t_unaccepted = var_totals[1];
    
            $("#var_unaccepted_total").val(t_unaccepted);
            $("#var_accepted_total").val(t_accepted);

            proj_id = get_project_id();
            $.post(baseurl+"projects/fetch_project_total_values",
            {
              proj_id: proj_id
            },
            function(result){  
              var result_arr = result.split('|');
              var proj_ex_gst = result_arr[0];
              var proj_inc_gst = result_arr[1];
              // proj_ex_gst = numberWithCommas(proj_ex_gst);
              // alert(proj_ex_gst);
              $("#proj_ex_gst").html(proj_ex_gst);
              $("#proj_inc_gst").html(proj_inc_gst);
            });  
          });

        });
    });
  });
}
//CONTRACT===
window.create_contract = function(a){
  var prog_payment_stat = $("#prog_payment_stat").val();
  if(prog_payment_stat == 0){
    alert("Progress Payment is not yest set!");
  }else{
    $("#contract_notes_reports_tab").modal("show");
    var project_id = a;
    $.post(baseurl+"works/get_contract_notes",
    { project_id : project_id },
    function(result){
      var proj_notes = result.split( '|' );

      $("#reports_contract_date").val(proj_notes[0]);
      $("#reports_plans_elv_draw").val(proj_notes[1]);
      $("#reports_sched_work_quotation").val(proj_notes[2]);
      $("#reports_condition_quote_contract").val(proj_notes[3]);
    });
  }
  return false;
}
$("#create_contract").click(function(){
    var project_id = get_project_id();
    var contract_date = $("#contract_date").val();
    var plans_elv_draw = "";
    var sched_work_quotation = "";
    var condition_quote_contract = "";

    if(contract_date == ""){
      contract_date = $("#reports_contract_date").val();
      plans_elv_draw = $("#reports_plans_elv_draw").val();
      sched_work_quotation = $("#reports_sched_work_quotation").val();
      condition_quote_contract = $("#reports_condition_quote_contract").val();
    }else{
      contract_date = $("#contract_date").val();
      plans_elv_draw = $("#plans_elv_draw").val();
      sched_work_quotation = $("#sched_work_quotation").val();
      condition_quote_contract = $("#condition_quote_contract").val();
    }

    $.post(baseurl+"works/insert_contract_notes",
    { 
      project_id : project_id,
      cont_date: contract_date,
      plans_elv_draw: plans_elv_draw,
      sched_works_qoute: sched_work_quotation,
      cond_quote_cont: condition_quote_contract
    },
    function(result){
      $.post(baseurl+"works/view_send_pdf", 
      {
      }, 
      function(result){
        $.post(baseurl+"send_emails/display_proj_pdf_list", 
        { 
          project_id: project_id
        }, 
        function(result){
          $("#project_pdf_list").html(result);
          window.open(baseurl+'works/contract_tot_rfntf/'+project_id);
        });
      });
    });
});

$("#create_contract_send_pdf").click(function(){ 
    var project_id = get_project_id();
    $.post(baseurl+"works/insert_contract_notes",
    { 
      project_id : project_id,
      cont_date: $("#contract_date").val(),
      plans_elv_draw: $("#plans_elv_draw").val(),
      sched_works_qoute:$("#sched_work_quotation").val(),
      cond_quote_cont: $("#condition_quote_contract").val()
    },
    function(result){
      
      $.post(baseurl+"works/view_send_pdf", 
      {
      }, 
      function(result){
        $.post(baseurl+"send_emails/display_proj_pdf_list", 
        { 
          project_id: project_id
        }, 
        function(result){
          $("#project_pdf_list").html(result);
          setTimeout(window.open(baseurl+'works/contract_tot_rfntf/'+project_id), 10000);
        });
      });
    });

    
})
//Contract===
//================= IDLE ================
  // $.post(baseurl+"users/user_login",
  // {},
  // function(result){
  //   alert(result);
  // });

  $("#log_out_prev_user").click(function(){
    $.post(baseurl+"users/logout",
    {},
    function(result){
      localStorage.idle = 0;
      window.location.reload();
    });
  });

  $("#show_userlist").click(function(){
    if(show_userlist == 0){
      show_userlist = 1;

      $.post(baseurl+"users/user_login",
      {},
      function(result){
        $("#user_list").html(result);
      });
      setInterval(function() {
      $.post(baseurl+"users/user_login",
        {},
        function(result){
          $("#user_list").html(result);
        });
      }, 10000);
    }else{
      show_userlist = 0;
    }
  });
  
  $(this).mousemove(function (e) {
    if(localStorage.idle < 15){
      localStorage.idle = 0;
    }
  });


  $(this).keypress(function (e) {
    if(localStorage.idle < 15){
      localStorage.idle = 0;
    }
    // $.post(baseurl+"users/set_user_time",
    // {},
    // function(result){
    //   //alert(result);
    // });
  });

  var home = segments[segment_index-2];
  if(home !== ""){
    $.post(baseurl+"users/check_user_if_remembered",
    {},
    function(result){
      run_idle_timer();
    });
    
    // $.post(baseurl+"users/reset_user_log_min",
    // {},
    // function(result){
    // });
    setInterval(function() {
      $.post(baseurl+"users/set_user_log",
      {},
      function(result){
        if(result !== "0"){
          localStorage.idle = 0;
          window.location.reload();
        }
        //alert(result);
      });
    }, 60000);
  }
  var home = segments[segment_index-2];
  
  function run_idle_timer(){
    var idle_int = null;
    idle_int = setInterval(function() {
      if(typeof(Storage) !== "undefined") {
        localStorage.idle = Number(localStorage.idle) + 1;
      } else {
        alert("Sorry, your browser does not support web storage...");
      }
        if(localStorage.idle > 14){

          clearInterval(idle_int);
          $.post(baseurl+"users/set_user_log_min",
          {},
          function(result){
            result_arr = result.split( '|' );
            $('#idle_log_in_form').modal({
              backdrop: 'static',
              keyboard: false
            })
            $("#inputUserName").val(result_arr[0]);
            $("#inputPassword").val(result_arr[1]);

            var remember = result_arr[2];
            if(remember == 0){
              $('#remember').attr('checked', false);
            }else{
              $('#remember').attr('checked', true);
            }
            log_in_try = 3;
            $("#no_of_tries").html(log_in_try);
            check_idle_tiem();
          });
        }else{
          $("#idle_log_in_form").modal('hide');
          localStorage.idle = Number(localStorage.idle) + 1;
        } 
    }, 60000);
  }

  function check_idle_tiem(){
    var check_idle = setInterval(function() {
      if(localStorage.idle == 0){
        clearInterval(check_idle);
        $("#idle_log_in_form").modal('hide');
      }else{
        localStorage.idle = Number(localStorage.idle) + 1;
      }
    }, 1000);
  }

  window.resign_in = function(){

    var uname = $("#inputUserName").val();
    var upass = $("#inputPassword").val();
    if($('#remember').is(":checked"))
    {
      var remember = 1;
    }else{
      var remember = 0;
    }
    
    $.post(baseurl+"users/re_login_user",
    {
      uname: uname,
      upass: upass,
      remember: remember
    },
    function(result){
      switch(result){
        case '0':
          alert("Username and password did not match or User do not exist!");
          
          if(log_in_try == 1){
            alert("You reached the number of tries allowed, you will be automatically logged-off!");
            log_in_try = 3;
            $.post(baseurl+"users/logout",
            {},
            function(result){
              window.location.reload();
            });
          }else{
            log_in_try = log_in_try - 1;
            $("#no_of_tries").html(log_in_try);
          }
          
          break;
        case '1':
          localStorage.idle = 0;
          $("#idle_log_in_form").modal('hide');

          setTimeout(run_idle_timer(), 1000);
          break;
        case '2':
          alert("You log-in as a different user, all unsaved data will be deleted!");
          $.post(baseurl+"users/logout",
          {},
          function(result){
            window.location.reload();
          });
          break;
      }
    });
    
    // $.post(baseurl+"users/set_log_modal_hidden",
    // {},
    // function(result){
    //   alert(result);
    //   $("#idle_log_in_form").modal('hide');
    // });
  }
  window.sign_out = function(){
    $.post(baseurl+"users/logout",
    {},
    function(result){
      localStorage.idle = 0;
      window.location.reload();
    });
  }
//================= IDLE ================
//Variation ==================
  var var_segment_index = segment_index + 1;
  var url_variation_segment = segments[var_segment_index];
  if(url_variation_segment == 'variation'){
    $("#add_new_var").removeAttr('disabled');
    $("#variation_name").val("");
    $("#variation_name").attr('disabled','disabled');
    $("#var_site_hrs").val("");
    $("#var_site_hrs").attr('disabled','disabled');
    $("#var_is_double_time").val("");
    $("#var_is_double_time").attr('disabled','disabled');
    $("#var_credit").val("");
    $("#var_credit").attr('disabled','disabled');
    $("#var_markup").val("");
    $("#var_markup").attr('disabled','disabled');
    $("#variation_notes").val("");
    $("#variation_notes").attr('disabled','disabled');
    
    $("#var_acceptance_date").val("");
    $("#var_acceptance_date").attr('disabled','disabled');
    
    $("#var_save").hide();
    $("#var_update").hide();
    $("#var_delete").hide();
    $.post(baseurl+"variation/variation_list", 
    { 
      proj_id: proj_id
    }, 
    function(result){
      $("#proj_variation_list").html(result);
    });
  }

  window.load_variation = function(){
    $("#add_new_var").removeAttr('disabled');
    $("#variation_name").val("");
    $("#variation_name").attr('disabled','disabled');
    $("#var_site_hrs").val("");
    $("#var_site_hrs").attr('disabled','disabled');
    $("#var_is_double_time").val("");
    $("#var_is_double_time").attr('disabled','disabled');
    $("#var_credit").val("");
    $("#var_credit").attr('disabled','disabled');
    $("#var_markup").val("");
    $("#var_markup").attr('disabled','disabled');
    $("#variation_notes").attr('disabled','disabled');
    $("#variation_notes").val("");

    $("#var_acceptance_date").val("");
    $("#var_acceptance_date").attr('disabled','disabled');
    $("#var_save").hide();
    $("#var_update").hide();
    $("#var_delete").hide();
    $.post(baseurl+"variation/variation_list", 
    { 
      proj_id: proj_id
    }, 
    function(result){
      $("#proj_variation_list").html(result);
      $.post(baseurl+"variation/get_variation_total",
      {
        proj_id: proj_id
      },
      function(result){
        var var_totals = result.split( '|' );
        var t_accepted = var_totals[0];
        var t_unaccepted = var_totals[1];
    
        $("#var_unaccepted_total").val(t_unaccepted);
        $("#var_accepted_total").val(t_accepted);
        $(".variation_total").html(t_accepted);
      });
    });
  }

  $("#add_new_var").click(function(){
    //$("#add_new_var").attr('disabled','disabled');
    variation_id = 0;
    $("#variation_name").val("");
    $("#variation_name").removeAttr('disabled');
    $("#variation_notes").val("");
    $("#variation_notes").removeAttr('disabled');
    $("#var_site_hrs").val("");
    $("#var_site_hrs").removeAttr('disabled');
    $("#var_is_double_time").val("");
    $("#var_is_double_time").removeAttr('disabled');
    $("#var_credit").val("");
    $("#var_credit").removeAttr('disabled');
    
    $.post(baseurl+"variation/get_proj_markup", 
    { 
      proj_id: proj_id
    }, 
    function(result){
       $("#var_markup").val(result);
    });
   
    $("#var_markup").removeAttr('disabled');
    $("#var_acceptance_date").val("");
    //$("#var_acceptance_date").removeAttr('disabled');
    $("#var_save").show();
    $("#var_update").hide();
    $("#var_delete").hide();
  });
  $("#var_save").click(function(){
    var var_credit = $("#var_credit").val();
    var_credit = var_credit.replace(',', '' );
    $.post(baseurl+"variation/add_variation", 
    { 
      proj_id: proj_id,
      var_name: $("#variation_name").val(),
      var_site_hrs: $("#var_site_hrs").val(),
      var_is_double_time: $("#var_is_double_time").val(),
      var_credit: var_credit,
      var_markup: $("#var_markup").val(),
      var_acceptance_date: $("#var_acceptance_date").val(),
      var_notes: $("#variation_notes").val(),
    }, 
    function(result){
      $("#proj_variation_list").html(result);
      $("#add_new_var").removeAttr('disabled');
      $("#variation_name").val("");
      $("#variation_name").attr('disabled','disabled');
      $("#var_site_hrs").val("");
      $("#var_site_hrs").attr('disabled','disabled');
      $("#var_is_double_time").val("");
      $("#var_is_double_time").attr('disabled','disabled');
      $("#var_credit").val("");
      $("#var_credit").attr('disabled','disabled');
      $("#var_markup").val("");
      $("#var_markup").attr('disabled','disabled');
      
      $("#var_acceptance_date").val("");
      //$("#var_acceptance_date").attr('disabled','disabled');
      $("#var_save").hide();
      $("#var_update").hide();
      $("#var_delete").hide();
    });
  });
  window.clk_edit_variation = function(a){
    variation_id = a;
     $.post(baseurl+"variation/display_variation", 
    { 
      variation_id: variation_id
    }, 
    function(result){
      var_arr = result.split('|');
      $("#variation_name").val(var_arr[0]);
      $("#var_site_hrs").val(var_arr[1]);
      $("#var_is_double_time").val(var_arr[2]);
      $("#var_credit").val(var_arr[3]);
      $("#var_markup").val(var_arr[4]);
      $("#var_acceptance_date").val(var_arr[5]);
      $("#variation_notes").val(var_arr[6]);
      var final_invoiced = var_arr[7];

      if(var_arr[5] == ""){
        $("#variation_name").removeAttr('disabled');
        $("#var_site_hrs").removeAttr('disabled');
        $("#var_is_double_time").removeAttr('disabled');
        $("#var_credit").removeAttr('disabled');
        $("#var_markup").removeAttr('disabled');
        $("#variation_notes").removeAttr('disabled');
        if(job_date !== "" && final_invoiced == '0'){
          $("#var_acceptance_date").removeAttr('disabled');
        }
        
        $("#var_delete").show();
      }else{
        $("#variation_name").attr('disabled','disabled');
        $("#var_site_hrs").attr('disabled','disabled');
        $("#var_is_double_time").attr('disabled','disabled');
        $("#var_credit").attr('disabled','disabled');
        $("#var_markup").attr('disabled','disabled');
        $("#variation_notes").attr('disabled','disabled');
        if(job_date !== "" && final_invoiced == '0'){
          $("#var_acceptance_date").removeAttr('disabled');
        }
        $("#var_delete").hide();
      }
        $("#var_save").hide();
        $("#var_update").show();
        
    });
    return false;
  }
  $("#var_update").click(function(){
    var var_credit = $("#var_credit").val();
    var_credit = var_credit.replace(',', '' );
    $.post(baseurl+"variation/update_variation", 
    { 
      proj_id: proj_id,
      variation_id: variation_id,
      var_name: $("#variation_name").val(),
      var_site_hrs: $("#var_site_hrs").val(),
      var_is_double_time: $("#var_is_double_time").val(),
      var_credit: var_credit,
      var_markup: $("#var_markup").val(),
      var_acceptance_date: $("#var_acceptance_date").val(),
      var_notes: $("#variation_notes").val(),
    }, 
    function(result){
      $("#proj_variation_list").html(result);
      $("#add_new_var").removeAttr('disabled');
      $("#variation_name").val("");
      $("#variation_name").attr('disabled','disabled');
      $("#variation_notes").val("");
      $("#variation_notes").attr('disabled','disabled');
      $("#var_site_hrs").val("");
      $("#var_site_hrs").attr('disabled','disabled');
      $("#var_is_double_time").val("");
      $("#var_is_double_time").attr('disabled','disabled');
      $("#var_credit").val("");
      $("#var_credit").attr('disabled','disabled');
      $("#var_markup").val("");
      $("#var_markup").attr('disabled','disabled');
        
      $("#var_acceptance_date").val("");
      $("#var_acceptance_date").attr('disabled','disabled');
      $("#var_save").hide();
      $("#var_update").hide();
      $("#var_delete").hide();

      $.post(baseurl+"variation/get_variation_total",
      {
        proj_id: proj_id
      },
      function(result){
        var var_totals = result.split( '|' );
        var t_accepted = var_totals[0];
        var t_unaccepted = var_totals[1];
    
        $("#var_unaccepted_total").val(t_unaccepted);
        $("#var_accepted_total").val(t_accepted);
        $(".variation_total").html(t_accepted);
      });
    });
  });
  $("#delete_var_yes").click(function(){
    $.post(baseurl+"variation/delete_variation", 
    {
      proj_id: proj_id,
      variation_id: variation_id
    }, 
    function(result){
      $("#proj_variation_list").html(result);
    });
  });
  // $("#var_delete").click(function(){

  // });
//Variation ==================
//Purchase ORDER ==============
$("#filter_po_bydate").click(function(){
  var start_date = $("#po_start_date").val();
  var end_date = $("#po_end_date").val();
  $.post(baseurl+"purchase_order/purchase_order_filtered", 
  { 
    start_date: start_date,
    end_date: end_date
  }, 
  function(result){ 
      if(start_date == "" || end_date == ""){
        alert("Start Date and End Date is Required!");
      }else{
        var contents  = result;//$(".po_date_range").html();
        var start_date_arr = start_date.split('/');
        var end_date_arr = end_date.split('/');
        var sd_day = start_date_arr[0];
        var sd_month = start_date_arr[1];
        var sd_year = start_date_arr[2];
        var month_str = num_to_month(sd_month);

        var end_date_arr = end_date.split('/');
        var ed_day = end_date_arr[0];
        var ed_month = end_date_arr[1];
        var ed_year = end_date_arr[2];
        var emonth_str = num_to_month(ed_month);

        var curr_date = new Date();
        var curr_month = curr_date.getMonth() + 1;
        if(curr_month < 10){
          curr_month = "0"+curr_month;
        }
        curr_month = num_to_month(curr_month);
        var curr_day = curr_date.getDate();
        var curr_year = curr_date.getFullYear();

        curr_date = curr_day+" "+curr_month+" "+curr_year;

        var printWindow = window.open('', '', 'height=700,width=1000,top=100,left=100,location=no,toolbar=no,resizable=no,menubar=no');
        printWindow.document.write('<html><head>');
        printWindow.document.write('<link href="'+baseurl+'css/print.css" rel="stylesheet" type="text/css" />');
        printWindow.document.write('</head><body class="print_body">');
        printWindow.document.write('<img src="'+baseurl+'img/focus-logo-print.png" width="206" height="66" />');
        printWindow.document.write('<div style = "position: absolute; top:0px; width: 100%"><strong class = "pull-right" style = "font-size: 18px">Contract PO in Date Range &nbsp&nbsp</strong></div>');
        printWindow.document.write('<div style = "position: absolute; top:25px; width: 100%"><strong class = "pull-right" style = "font-size: 16px">'+sd_day+' '+month_str+' '+sd_year+' to ' +ed_day+' '+emonth_str+' '+ed_year+ ' &nbsp&nbsp</strong></div>');
        printWindow.document.write('<div style = "position: absolute; top:50px; width: 100%"><strong class = "pull-right" style = "font-size: 14px">Date Printed: '+curr_date+' &nbsp&nbsp</strong></div>');
        printWindow.document.write(contents);
        printWindow.document.write('<a href="#" onclick="this.parentNode.removeChild(this); window.print(); window.close();" class="print_bttn print_me_now" style = "top: 65px">Print Now!</a>');
        printWindow.document.write('</body></html>');
        printWindow.document.close();
      }
  });
  //window.open(baseurl+"purchase_order/"+);
  
  
});
//Project Forms Send E-mail ============================
$("#lbl_attached").hide();
$("#lbl_no_attached").show();
$("#project_forms").change(function(){

  var form = $("#project_forms").val();
  switch(form){
    case "1":
      //var project_id = get_project_id();
      var file_path = baseurl+"works/proj_summary_w_cost/"+proj_id;
      window.open(file_path,"_blank");
      $.post(baseurl+"works/view_send_pdf", 
      {
      }, 
      function(result){
        //window.open(baseurl+"projects/view/"+proj_id, '_self', true); 
        $.post(baseurl+"send_emails/display_proj_pdf_list", 
        { 
          project_id: proj_id
        }, 
        function(result){
          $("#project_pdf_list").html(result);
        });
      });
      break;
    case "2":
      //var project_id = get_project_id();
      var file_path = baseurl+"works/proj_summary_wo_cost/"+proj_id;
      window.open(file_path,"_blank");
      $.post(baseurl+"works/view_send_pdf", 
      {
      }, 
      function(result){
        //window.open(baseurl+"projects/view/"+proj_id, '_self', true); 
        $.post(baseurl+"send_emails/display_proj_pdf_list", 
        { 
          project_id: proj_id
        }, 
        function(result){
          $("#project_pdf_list").html(result);
        });
      });
      break;
    case "3":
      //var project_id = get_project_id();
      var file_path = baseurl+"works/proj_joinery_summary_w_cost/"+proj_id;
      window.open(file_path,"_blank");
      $.post(baseurl+"works/view_send_pdf", 
      {
      }, 
      function(result){
        //window.open(baseurl+"projects/view/"+proj_id, '_self', true); 
        $.post(baseurl+"send_emails/display_proj_pdf_list", 
        { 
          project_id: proj_id
        }, 
        function(result){
          $("#project_pdf_list").html(result);
        });
      });
      break;
    case "4":
      //var project_id = get_project_id();
      var file_path = baseurl+"works/proj_joinery_summary_wo_cost/"+proj_id;
      window.open(file_path,"_blank");
      $.post(baseurl+"works/view_send_pdf", 
      {
      }, 
      function(result){
        //window.open(baseurl+"projects/view/"+proj_id, '_self', true); 
        $.post(baseurl+"send_emails/display_proj_pdf_list", 
        { 
          project_id: proj_id
        }, 
        function(result){
          $("#project_pdf_list").html(result);
        });
      });
      break;
    case "5":
      var file_path = baseurl+"works/variation_summary/"+proj_id;
      window.open(file_path,"_blank");
       $.post(baseurl+"works/view_send_pdf", 
      {
      }, 
      function(result){
        //window.open(baseurl+"projects/view/"+proj_id, '_self', true); 
        $.post(baseurl+"send_emails/display_proj_pdf_list", 
        { 
          project_id: proj_id
        }, 
        function(result){
          $("#project_pdf_list").html(result);
        });
      });
      break;
    case "6":
      alert("No variation yet");
      break;
    case "7":
      var prog_payment_stat = $("#prog_payment_stat").val();
      if(prog_payment_stat == 0){
        alert("Progress Payment is not yest set!");
      }else{
        $("#contract_notes").modal("show");
        var project_id = proj_id;
        $.post(baseurl+"works/get_contract_notes",
        { project_id : project_id },
        function(result){
          var proj_notes = result.split( '|' );

          $("#contract_date").val(proj_notes[0]);
          $("#plans_elv_draw").val(proj_notes[1]);
          $("#sched_work_quotation").val(proj_notes[2]);
          $("#condition_quote_contract").val(proj_notes[3]);
        });
      }
      break;
    case "8":
      window.open(baseurl+'reports/Request_for_New_Trade_Deptor_Form.pdf', '_blank', 'fullscreen=yes');
      break;
  }
  
});
$("#merge_attach").click(function(){
  $("#lbl_attached").show();
  $("#lbl_no_attached").hide();
  client_attached = 1;
  var checkboxValues = [];
  $('input[name=chk_proj_forms]:checked').map(function() {
    checkboxValues.push($(this).val());
  });
  //var project_id = get_project_id();
  //window.open(baseurl+"send_emails/combine_project_form", '_self', true); 
  $.post(baseurl+"send_emails/combine_project_form",
  {
    project_id: proj_id,
    checkboxValues: checkboxValues
  },
  function(result){
    window.open(baseurl+'send_emails/display_merge_attachment', '_blank', 'fullscreen=yes');
  });
});
$("#send_attachment").click(function(){
  if(client_attached == 1){
    var subject = $("#subject").val();
    if(subject !== ""){
      //var project_id = get_project_id();
      var email = $("#client_email").val();
      var cc = $("#cc_email").val();
      var msg = $("#message_to_client").val();
      $.post(baseurl+"send_emails/send_email_to_client",
      {
        proj_id: proj_id,
        clien_email: email,
        cc: cc,
        subject: subject,
        message: msg
      },
      function(result){
        alert(result);
      }); 
    }else{
      alert("No Subject has been entered!");
    }
  }else{
    alert("No Attachment Please Attach a file");
  }
  
});
 // ========Send to contracotr ============
window.view_send_contractor = function(){
  //var project_id = get_project_id();
  $.post(baseurl+"works/job_date_entered",
  {
    proj_id: proj_id
  },
  function(result){
    job_date = result;
    if(job_date == ""){
      $("#tab_send_contractor_cpo").hide();
    }else{
      $("#tab_send_contractor_cpo").show();
    }
    $.post(baseurl+"send_emails/display_work_contractor_list", 
    { 
      project_id: proj_id
    }, 
    function(result){
      $("#contractor_list").html(result);
    });
    $.post(baseurl+"send_emails/display_proj_pdf_list", 
    { 
      project_id: proj_id
    }, 
    function(result){
      $("#project_pdf_list").html(result);
    });
  });
  
}

window.view_send_client = function(){
  $.post(baseurl+"send_emails/display_proj_pdf_list", 
  { 
    project_id: proj_id
  }, 
  function(result){
    $("#project_pdf_list").html(result);
  });
}

window.view_send_contractor_cpo = function(){
  //var project_id = get_project_id();
  $.post(baseurl+"send_emails/display_work_contractor_list_cpo", 
  { 
    project_id: proj_id
  }, 
  function(result){
    $("#contractor_list_cpo").html(result);
  });
}


window.check_all = function(){
  if($('.checkall_contractor').is(':checked')){
    $(".cont_checkbox").each(function(){
      this.checked = true;
    });
  }else{
     $(".cont_checkbox").each(function(){
      this.checked = false;
    });
  }
}

window.check_all_cpo = function(){
  if($('.checkall_contractor_cpo').is(':checked')){
    $(".cont_checkbox_cpo").each(function(){
      this.checked = true;
    });
  }else{
     $(".cont_checkbox_cpo").each(function(){
      this.checked = false;
    });
  }
}

$("#btn_creat_cqr").click(function(){
  //var checkboxValues_contractor = [];
  //var project_id = get_project_id();
  $('input[class=cont_checkbox]:checked').map(function() {
    //checkboxValues_contractor.push($(this).val());
    work_contractor_id = $(this).val();
    $.post(baseurl+"works/fetch_works_contractors", 
    { 
      work_contractor_id: work_contractor_id
    }, 
    function(result){
      var result = result.split( '|' );
      var comp_id = result[0];
      var work_id = result[1];
      $.get(baseurl+"works/contractor_quote_request_nodisplay", 
      { 
        project_id: proj_id,
        work_id:work_id,
        comp_id:comp_id
      }, 
      function(result){
        $.post(baseurl+"send_emails/display_work_contractor_list", 
        { 
          project_id: proj_id
        }, 
        function(result){
          $("#contractor_list").html(result);
        });
      })
    });
  });
});
window.show_cqr = function(a){
  var work_contractor_id = a;
  //var project_id = get_project_id();
  $.post(baseurl+"works/fetch_works_contractors", 
  { 
    work_contractor_id: work_contractor_id
  }, 
  function(result){
      var result = result.split( '|' );
      var comp_id = result[0];
      var work_id = result[1];
      window.open(baseurl+"works/contractor_quote_request/"+proj_id+"/"+work_id+"/"+comp_id);
  });
}

$("#btn_create_cpo").click(function(){
  //var project_id = get_project_id();
    if(job_date == ""){
      alert("Cannot Create CPO, Job Date Required!");
    }else{
      $('input[class=cont_checkbox_cpo]:checked').map(function(){
        
        work_contractor_id = $(this).val();
        $.post(baseurl+"works/fetch_works_contractors", 
        { 
          work_contractor_id: work_contractor_id
        }, 
        function(result){
          var result = result.split( '|' );
          var is_selected = result[2];
          var work_id = result[1];
          if(is_selected == 1){
            $.get(baseurl+"works/work_contractor_po_nodisplay", 
            { 
              project_id: proj_id,
              work_id:work_id,
            }, 
            function(result){
              $.post(baseurl+"send_emails/display_work_contractor_list", 
              { 
                project_id: proj_id
              }, 
              function(result){
                $("#contractor_list").html(result);

                var proj_job_cat = $("#send_pdf_proj_job_category").val();
                if(proj_job_cat == "Maintenance"){
                  window.open(baseurl+"works/maintenance_site_sheet/"+proj_id+"/"+work_id);
                }
              });
            });
          }
        });
      });
    }
});
window.show_cpo = function(a){
  var work_contractor_id = a;
  //var project_id = get_project_id();
  $.post(baseurl+"works/fetch_works_contractors", 
  { 
    work_contractor_id: work_contractor_id
  }, 
  function(result){
      var result = result.split( '|' );
      //var comp_id = result[0];
      var work_id = result[1];
      window.open(baseurl+"works/work_contractor_po/"+proj_id+"/"+work_id);
  });
}
window.show_joinery_cpo = function(a){
  var work_contractor_id = a;
  //var project_id = get_project_id();
  $.post(baseurl+"works/fetch_works_contractors", 
  { 
    work_contractor_id: work_contractor_id
  }, 
  function(result){
      var result = result.split( '|' );
      //var comp_id = result[0];
      var work_joinery_id = result[1];
      var work_and_joinery_id = work_joinery_id.split( '-' );
      work_id = work_and_joinery_id[0];
      window.open(baseurl+"works/work_contractor_po/"+proj_id+"/"+work_id+"/"+work_joinery_id);
  });
}
$("#btn_send_cqr").click(function(){
  var project_id = get_project_id();
  var contractor_email = ""
  send_email = 1;
  no_cqr = 0;
  var contractor_selected = 0;
  $('input[class=cont_checkbox]:checked').map(function() {
    //checkboxValues_contractor.push($(this).val());
    contractor_selected++;
    work_contractor_id = $(this).val();
    $.post(baseurl+"works/fetch_works_contractors", 
    { 
      work_contractor_id: work_contractor_id
    }, 
    function(result){
      var result = result.split( '|' );
      var comp_id = result[0];
      var work_id = result[1];
      var cqr_created = result[3];
      if(cqr_created == 0){
        no_cqr++;
      }
      $.post(baseurl+"works/get_contractor_email",
      { 
        comp_id: comp_id,
        work_id:work_id
      }, 
      function(result){
        if(result !== ""){
          if(contractor_email == ""){
            contractor_email = result;
          }else{
            contractor_email = contractor_email+","+result;
          }
          $("#contractor_email_add").val(contractor_email);
          $.post(baseurl+"attachments/get_project_shared_link",
          { 
            proj_id: project_id
          }, 
          function(result){
             $("#contractor_email_message").val(result);
          });
        }else{
          alert("One of the selected Contractor/Suppliers has no E-mail Address");
        }
      });
    });
  });
  if(contractor_selected > 1){
    $("#contractor_alt_email_add").attr('disabled','disabled');;
  }else{
    $("#contractor_alt_email_add").removeAttr('disabled');
  }
  $('button[id="send_email"]').removeAttr('disabled');
});
$("#btn_send_cpo").click(function(){
  var project_id = get_project_id();
  var contractor_selected = 0;
  //var project_id = get_project_id();
  var contractor_email = "";
  send_email = 2;
  no_cpo = 0;
  $('input[class=cont_checkbox_cpo]:checked').map(function() {
    contractor_selected++;
    //checkboxValues_contractor.push($(this).val());
    work_contractor_id = $(this).val();
    $.post(baseurl+"works/fetch_works_contractors", 
    { 
      work_contractor_id: work_contractor_id
    }, 
    function(result){
      var result = result.split( '|' );
      var comp_id = result[0];
      var work_id = result[1];
      var is_selected = result[2];
      var cpo_created = result[4];
      if(is_selected == 1){
        if(cpo_created == 0){
          no_cpo++;
        }
        $.post(baseurl+"works/get_contractor_email",
        { 
          comp_id: comp_id,
          work_id:work_id
        }, 
        function(result){
          if(result !== ""){
            if(contractor_email == ""){
              contractor_email = result;
            }else{
              contractor_email = contractor_email+","+result;
            }
            $("#contractor_email_add").val(contractor_email);
            $.post(baseurl+"attachments/get_project_shared_link",
            { 
              proj_id: project_id
            }, 
            function(result){
               $("#contractor_email_message").val(result);
            });
          }else{
            alert("One of the selected Contractor/Suppliers has no E-mail Address");
          }
        });
      }
    });
  });

  if(contractor_selected > 1){
    $("#contractor_alt_email_add").attr('disabled','disabled');;
  }else{
    $("#contractor_alt_email_add").removeAttr('disabled');
  }

  $('button[id="send_email"]').removeAttr('disabled');
});
$("#sending_button").hide();
var counter = 0;
$("#send_email").click(function(){
  counter = 0;
  if(send_email == 1){
    if(no_cqr > 0){
      alert("Cannot send Some of the selected Contractor doesn't have CQR created yet!");
    }else{
      //var project_id = get_project_id();
      var contractor_email = ""
      send_email = 1;
      $('input[class=cont_checkbox]:checked').map(function() {
        //checkboxValues_contractor.push($(this).val());
        work_contractor_id = $(this).val();
        $.post(baseurl+"works/fetch_works_contractors", 
        { 
          work_contractor_id: work_contractor_id
        }, 
        function(result){
          var result = result.split( '|' );
          var comp_id = result[0];
          var work_id = result[1];
          $.post(baseurl+"works/get_contractor_email",
          { 
            comp_id: comp_id,
            work_id:work_id
          }, 
          function(result){
            var alt_email = $("#contractor_alt_email_add").val();
            var cc_email = $("#contractor_cc").val();
            var bcc_email = $("#contractor_bcc").val();
            var cont_email_subject = $("#contractor_subject").val();
            var cont_email_msg = $("#contractor_email_message").val();



            // on 3 second
            
            $("#send_email").hide();
            $("#sending_button").show();
            $.post(baseurl+"send_emails/send_email_to_contractor_cqr",
            { 
              contractor_email: result,
              alt_email: alt_email,
              cc_email: cc_email,
              bcc_email: bcc_email,
              cont_email_subject: cont_email_subject,
              cont_email_msg: cont_email_msg,
              project_id: proj_id,
              work_id: work_id,
              comp_id: comp_id,
            }, 
            function(result){
             /* if(result == 1){

              }*/
              setTimeout(function(){
                $.post(baseurl+"send_emails/display_work_contractor_list", 
                { 
                  project_id: proj_id
                }, 
                function(result){
                  if(counter == 0 ){
                    alert("E-mail Successfully Sent");
                  }
                  $("#send_email").show();
                  $("#sending_button").hide();

                  $("#contractor_email_add").val("");
                  $("#contractor_alt_email_add").val("");
                  $("#contractor_cc").val("");
                  $("#contractor_bcc").val("");
                  $("#contractor_subject").val("");
                  $("#contractor_email_message").val("");
                  $('button[id="send_email"]').attr('disabled','disabled');
                  
                  counter++;
                  $("#contractor_list").html(result);
                });
              }, 5000);  // on 5 second
            });

                
          });
        });
      });
    }
  }else{
    if(no_cpo > 0){
      alert("Cannot send Some of the selected Contractor doesn't have CPO created yet!");
    }else{
      //var project_id = get_project_id();
      var contractor_email = ""
      send_email = 1;
      $('input[class=cont_checkbox_cpo]:checked').map(function() {
        //checkboxValues_contractor.push($(this).val());
        work_contractor_id = $(this).val();
        $.post(baseurl+"works/fetch_works_contractors", 
        { 
          work_contractor_id: work_contractor_id
        }, 
        function(result){
          var result = result.split( '|' );
          var comp_id = result[0];
          var work_id = result[1];
          var is_selected = result[2];
          if(is_selected == 1){
            $.post(baseurl+"works/get_contractor_email",
            { 
              comp_id: comp_id,
              work_id:work_id
            }, 
            function(result){
              var alt_email = $("#contractor_alt_email_add").val();
              var cc_email = $("#contractor_cc").val();
              var bcc_email = $("#contractor_bcc").val();
              var cont_email_subject = $("#contractor_subject").val();
              var cont_email_msg = $("#contractor_email_message").val();
              var proj_job_cat = $("#send_pdf_proj_job_category").val();

              $("#send_email").hide();
              $("#sending_button").show();

              $.post(baseurl+"send_emails/send_email_to_contractor_cpo",
              { 
                contractor_email: result,
                alt_email: alt_email,
                cc_email: cc_email,
                bcc_email: bcc_email,
                cont_email_subject: cont_email_subject,
                cont_email_msg: cont_email_msg,
                project_id: proj_id,
                work_id: work_id,
                comp_id: comp_id,
                proj_job_cat: proj_job_cat
              }, 
              function(result){
                setTimeout(function(){
                  $.post(baseurl+"send_emails/display_work_contractor_list", 
                  { 
                    project_id: proj_id
                  }, 
                  function(result){
                    if(counter == 0 ){
                      alert("E-mail Successfully Sent");
                    }

                    $("#send_email").show();
                    $("#sending_button").hide();

                    $("#contractor_email_add").val("");
                    $("#contractor_alt_email_add").val("");
                    $("#contractor_cc").val("");
                    $("#contractor_bcc").val("");
                    $("#contractor_subject").val("");
                    $("#contractor_email_message").val("");
                    $('button[id="send_email"]').attr('disabled','disabled');
                    $("#contractor_list").html(result);

                    counter++;
                  });
                }, 5000);  // on 5 second
              });
            });
          }
        });
      });
    }
  }
});
 // ======== Send to Contractor ============
//Project Forms Send E-mail ============================
// Contacts =================
  window.contact_details = function(a){
    contact_person_id = a;
    $.post(baseurl+"contacts/display_selected_contacts",
    {
      contact_person_id: contact_person_id
    },
    function(result){
      var contact_detail = result.split( '|' );
      var company_name = contact_detail[0];
      var contact_comp = contact_detail[1];
      var location = contact_detail[3]+", "+contact_detail[2];
      var office_no = contact_detail[4];
      var mobile = contact_detail[5];
      var email = contact_detail[6];
      var company_desc = contact_detail[7];
      $("#contact_name").text(company_name);
      $("#company_name").text(contact_comp);
      $("#company_location").text(location);
      $("#office_num").text(office_no);
      $("#mobile").text(mobile);
      $("#cont_email").text(email);
      $("#company_desc").text(company_desc);
    });
  }
// Contacts =================
  // Work Attachment====================================
// $.post(baseurl+"attachments/view_attachment_type",
// {
//   type: 1
// },
// function(result){
//   $('#attachment_type').html(result);
// });
// $.post(baseurl+"attachments/view_attachment_type",
// {
//   type: 5
// },
// function(result){
//   $('.sel_attachment_type').html(result);
// });
// $("#attachment_type").change(function(){
//   var attachment_type = $("#attachment_type").val();
//   if(attachment_type == 'Add_New'){
//     $('.modal').modal('hide');
//     $("#attachment_type_modal").modal('show');
//     $.post(baseurl+"attachments/view_attachment_type",
//     {
//       type:2
//     },
//     function(result){
//       $('#table_attachment_type').html(result);
//       $("#txt_attachment_type").val("");
//       $("#btn_add_attachment_type").show();
//       $("#btn_update_attachment_type").hide();
//       $("#btn_delete_attachment_type").hide();
//     });
//   }

// });

window.select_attachment_type = function(a){
  project_attachment_id = a;
  //$('tbody tr#row-work-'+work_id).find('.work-set-quote-'+work_id).text(quoted);
  attach_type = $("#attachment_cell_"+a).text();
  $("#txt_attachment_type").val(attach_type);
  $("#btn_add_attachment_type").hide();
  $("#btn_update_attachment_type").show();
  $("#btn_delete_attachment_type").show();
}

$("#btn_add_attachment_type").click(function(){
  var attachment_type = $("#txt_attachment_type").val();
  if(attachment_type == ""){
    alert("Please type in the Attachment Type");
  }else{
    $.post(baseurl+"attachments/insert_attachment_type",
    {
       attachment_type:attachment_type
    },
    function(result){
      $.post(baseurl+"attachments/view_attachment_type",
      {
        type:2
      },
      function(result){
        $('#table_attachment_type').html(result);
        $("#txt_attachment_type").val("");
        $("#btn_add_attachment_type").show();
        $("#btn_update_attachment_type").hide();
        $("#btn_delete_attachment_type").hide();

        $.post(baseurl+"attachments/view_attachment_type",
        {
          type: 1
        },
        function(result){
          $('#attachment_type').html(result);

          $.post(baseurl+"attachments/view_attachment_type", 
          { 
            type: 3
          }, 
          function(result){
            $("#add_work_attachment_types").html(result);
          });
        });
      });
    });
  }
});

$("#btn_update_attachment_type").click(function(){
  var attachment_type = $("#txt_attachment_type").val();
  $.post(baseurl+"attachments/update_attachment_type",
  {
    project_attachment_id: project_attachment_id,
    attachment_type:attachment_type
  },
  function(result){
    $.post(baseurl+"attachments/view_attachment_type",
    {
      type:2
    },
    function(result){
      $('#table_attachment_type').html(result);
      $("#txt_attachment_type").val("");
      $("#btn_add_attachment_type").show();
      $("#btn_update_attachment_type").hide();
      $("#btn_delete_attachment_type").hide();
      $.post(baseurl+"attachments/view_attachment_type",
      {
        type: 1
      },
      function(result){
        $('#attachment_type').html(result);

        $.post(baseurl+"attachments/view_attachment_type", 
        { 
          type: 3
        }, 
        function(result){
          $("#add_work_attachment_types").html(result);
        });
      });
    });
  });
})

$("#btn_delete_attachment_type").click(function(){
  $.post(baseurl+"attachments/attachment_type_verfication",
  {
    project_attachment_id: project_attachment_id,
  },
  function(result){
    if(result == 1){
      $.post(baseurl+"attachments/view_attachment_type",
      {
        type:2
      },
      function(result){
        $('#table_attachment_type').html(result);
        $("#txt_attachment_type").val("");
        $("#btn_add_attachment_type").show();
        $("#btn_update_attachment_type").hide();
        $("#btn_delete_attachment_type").hide();
        $.post(baseurl+"attachments/view_attachment_type",
        {
          type: 1
        },
        function(result){
          $('#attachment_type').html(result);

          $.post(baseurl+"attachments/view_attachment_type", 
          { 
            type: 3
          }, 
          function(result){
            $("#add_work_attachment_types").html(result);
          });
        });
      });
    }else{
      alert(result);
    }
  });
});
/*window.get_attach_type = function(a){
  $("#lbl_attachment_type_"+a).hide());
  $("#sel_attachment_type_"+a).show();
}*/


window.view_file = function(a){
  project_attachment_id = a;
  $.post(baseurl+"attachments/fetch_selected_proj_attachment",
  {
     project_attachments_id: project_attachment_id,
  },
  function(result){
    var result_arr = result.split('|');
    var file_name = result_arr[0];
    var is_selected = result_arr[1];
    if(is_selected == 0){
      $("#show_attachment_modal").modal('show');
      $("#attachment_filename").html(result);
      $('#iframe_view_attachment').attr('src', baseurl+'/uploads/project_attachments/'+proj_id+'/'+file_name);
    }else{
      alert("Cant view file, selected file is on dropbox, unselect item to view it here or go to the dropbox acount to view it");
    }
    
  });

  $("#remove_attachment").click(function(){
    $.post(baseurl+"attachments/remove_attachment",
    {
      project_attachments_id: project_attachment_id,
      proj_id: proj_id
    },
    function(result){
      $("#attachement_loading_modal").modal("show");
      window.open(baseurl+'attachments/refresh_attachment_page?proj_id='+proj_id, '_self', true);
    });
  });
  /*var attach_file = a.split( '|' );
  proj_id = attach_file[0];
  work_id = attach_file[1];
  file_name = attach_file[2];
  ext = file_name.split('.').pop().toLowerCase();
  switch(ext){
    case 'jpg': case 'gif': case 'png':
      $("#btn_download_file").text('Download');
      $("#attach_img_name").text(file_name);
      $("#attach_image").attr("src",baseurl+'/uploads/project_attachments/'+proj_id+'/'+work_id+'/'+file_name);
      $('.modal').modal('hide');
      $('#attachment_modal_img').modal('show');
      break;
    case 'pdf':
      $("#btn_download_file").text('View');
      $("#attach_img_name").text(file_name);
      $("#attach_image").attr("src",baseurl+'/img/PDF-logo.png');
      $('.modal').modal('hide');
      $('#attachment_modal_img').modal('show');
      break;
    case 'zip': case 'rar': 
     $("#btn_download_file").text('Download');
      $("#attach_img_name").text(file_name);
      $("#attach_image").attr("src",baseurl+'/img/zip_ico.png');
      $('.modal').modal('hide');
      $('#attachment_modal_img').modal('show');
      break;
    default:
      $("#btn_download_file").text('Download');
      $("#attach_img_name").text(file_name);
      $("#attach_image").attr("src",baseurl+'/img/doc.png');
      $('.modal').modal('hide');
      $('#attachment_modal_img').modal('show');
      break;
  }*/
}
$("#btn_download_file").click(function(){
  if(ext == 'pdf'){
    var file_path = baseurl+'uploads/project_attachments/'+proj_id+'/'+work_id+'/'+file_name;
    window.open(file_path,"_blank");
  }else{
    $.post(baseurl+"works/download_file",
    {
      proj_id: proj_id,
      work_id: work_id,
      file_name: file_name
    },
    function(result){
      $('.modal').modal('hide');
      $('#attachment_modal_img').modal('hide');
    });
  } 
});
$("#btn_delete_attachment").click(function(){
  $.post(baseurl+"works/delete_attachment",
  {
    proj_id: proj_id,
    work_id: work_id,
    file_name: file_name
  },
  function(result){
    window.open(baseurl+'works/update_work_details/'+proj_id+"/"+work_id, '_self', true);
  });
});
//$(".attachment_type").hide();
window.clk_attach_type = function(a){
  project_attachments_id = a;
}
window.change_attach_type = function(a){
  attachment_type_id = a;
  $('.modal').modal('hide');
  $("#change_attachment_type_conf_modal").modal('show');
}
$("#change_attach_type_no").click(function(){
  $("#sel_attachment_type_"+project_attachments_id).val(attachment_type_id);
});
$("#change_attach_type_yes").click(function(){
  attach_type = $("#sel_attachment_type_"+project_attachments_id).val();
  $.post(baseurl+"attachments/edit_attachment_type",
  {
    project_attachments_id: project_attachments_id,
    attach_type: attach_type
  },
  function(result){
  });
});
window.chk_select_attachment = function(a){
  project_attachments_id = a;
  var proj_id = get_project_id();
  $('#attachement_loading_modal').modal('show');
  if($("#chck_isselected_"+a).is(':checked')){
    $.post(baseurl+"attachments/attachment_isselected",
    {
      project_attachments_id: project_attachments_id,
      proj_attachment_status: 1,
      proj_id: proj_id
    },
    function(result){
       $('#attachement_loading_modal').modal('hide');
    });
  }else{
    $.post(baseurl+"attachments/attachment_isselected",
    {
      project_attachments_id: project_attachments_id,
      proj_attachment_status: 0,
      proj_id: proj_id
    },
    function(result){
      $('#attachement_loading_modal').modal('hide');
    });
  }
}
  //===================================
  var url_user_segment = segments[segment_index - 2];
  if(url_user_segment == 'users'){
    $.post(baseurl+"users/login_users", 
    {}, 
    function(result){
      $("#login_user_list").html(result);
    });
    setInterval(function() {
      $.post(baseurl+"users/login_users", 
      {}, 
      function(result){
        $("#login_user_list").html(result);
      });
      
    }, 10000);
  }
  
  window.select_user_id = function(a){
    user_id = a;
  }
  window.btn_logout_user = function(a){
    user_id = a;
    $.post(baseurl+"users/logout_user", 
    {
      user_id: user_id
    }, 
    function(result){
      $("#user_list").html(result);//$("#login_user_list").html(result);
    });
  };

  $("#show1").hide();
  $("#show2").hide();
  $("#show3").hide();
  $("#show4").hide();


  $("#edit_est_markup").show();
  $("#save_est_markup").hide();

  $("#edit_work_dates").show();
  $("#save_work_dates").hide();

  $("#edit_considerations").show();
  $("#save_considerations").hide();

  $("#edit_notes").show();
  $("#save_notes").hide();

  //$("#btn_select_subcontractor").hide();
  $("#worktype").change(function(){
    var wtype = $("#worktype").val();
    if(wtype == '2_82'){
      $('#other_work_description').css('display', 'block').focus();
    }else{
      $('#other_work_description').css('display', 'none');
    }
    if(wtype == '2_53'){
      var exist = $("#joinery_exist").val();
      if(exist == 1){
        alert("Joinery already exist on the works list, please other work description.");
        $("#worktype").trigger("chosen:updated");
        $("#worktype").val("");
        $("#s2id_autogen1_search").val("");
        $("#select2-chosen-1").html("");
      }
    }
    /*if(wtype == "Supplier"){
      $("#show1").hide();
      $("#show2").hide();
      $("#show3").show();
      $("#show4").show();
      $('#works').empty();
      $.post(baseurl+"works/diplay_sup_cat", 
      { 
      }, 
      function(result){
        $("#works").html(result);
      });
    }else{
      $("#show1").show();
      $("#show2").show();
      $("#show3").show();
      $("#show4").show();
      $('#works').empty();
      $("#works").text("");
    }*/
  });

  


  $("#time-half").keyup(function(){

    var timeHalf = parseInt($(this).val());
    var doubleTime = parseInt($('#double-time').val());
    var standardLabour = parseInt(100 - (timeHalf + doubleTime));

    if(standardLabour < 1 || standardLabour > 100 ){
      $(this).parent().parent().addClass('has-error');
      $(this).parent().parent().addClass('has-feedback');

      alert('Please reduce the values of Time & Half Labour');
    }else{
      $('#standard-labour').val(standardLabour);
      $('.standard-labour').text('% '+standardLabour);
      $(this).parent().parent().removeClass('has-error');
      $(this).parent().parent().removeClass('has-feedback');
    }

  });

    $("#double-time").keyup(function(){

    var doubleTime = $(this).val();
    var timeHalf = $('#time-half').val();
    var standardLabour = 100 - (+timeHalf + +doubleTime);

    //alert(gstRate+' '+installationLabour+' '+standardLabour+ ' '+checkSum);

    if(standardLabour < 1 || standardLabour > 100 ){
      $(this).parent().parent().addClass('has-error');
      $(this).parent().parent().addClass('has-feedback');
      alert('Please reduce the values of Installation Labour');
    }else{
      $('#standard-labour').val(standardLabour);
      $('.standard-labour').text('% '+standardLabour);
      $(this).parent().parent().removeClass('has-error');
      $(this).parent().parent().removeClass('has-feedback');
    }

  });

  $("#work_estimate").keyup(function(){
    var quote = 0;
    var w_markup = $("#work_markup").val();
    w_markup = w_markup.replace(',', '' );
    var w_estimate = $("#work_estimate").val();
    w_estimate = w_estimate.replace(',', '' );
    if(w_markup > 0){
      quote = +(w_estimate) +  (+(w_estimate) * (+(w_markup)/100));
    }else{
      quote = +(w_estimate) +  (+(w_estimate) * (+($("#projmarkup").val())/100));
    }
  
    var gst = $("#gst").val();
    gst = gst.replace(',', '' );
    var inc_gst = (quote* (gst/100) )+quote;
    inc_gst = numberWithCommas(inc_gst);
    $('.inc_gst').val(inc_gst);

    quote = numberWithCommas(quote);
    $("#work_quote_val").val(quote);
  })


  $("#work_markup").blur(function(){
    var minmarkup = $("#minmarkup").val()-0;
    var markup = $("#work_markup").val();
    if(markup < minmarkup){
      alert("Mark Up entered is lower than the minimum value allowed!");
      $("#work_markup").val("");
    }else{
      var w_markup = $("#work_markup").val();
      w_markup = w_markup.replace(',', '' );
      var w_estimate = $("#work_estimate").val();
      w_estimate = w_estimate.replace(',', '' );
      var quote = +(w_estimate) +  (+(w_estimate) * (+(w_markup)/100));
      quote = numberWithCommas(quote);
      $("#work_quote_val").val(quote);
      /*var quote = 0;
      var w_markup = $("#work_markup").val();
      if(w_markup > 0){
        quote = +($("#work_estimate").val()) +  (+($("#work_estimate").val()) * (+($("#work_markup").val())/100));
      }else{
        quote = +($("#work_estimate").val()) +  (+($("#work_estimate").val()) * (+($("#projmarkup").val())/100));
      }
      $("#work_quote_val").val(quote);

      var gst = $("#gst").val();
      var inc_gst = (quote* (gst/100) )+quote;
      $('.inc_gst').text(inc_gst);
  */
    }

    
  });

  $("#sel_job_cat").change(function(){
    var selectedval = $(this).val();
    $('#works').empty();
    $.post(baseurl+"works/display_job_sub_cat", 
    { 
      job_cat: selectedval
    }, 
    function(result){
      $("#works").html(result);
    });
  });

  $('#site_start').bind('changeDate', site_start_onDateChange);
  $('#site_finish').bind('changeDate', site_finish_onDateChange);
 
 
  $('#street').keyup(function(evt){
    var txt = $(this).val();
    $(this).val(txt.replace(/^(.)|\s(.)/g, function($1){ return $1.toUpperCase( ); }));
  });

  // $("#client_po").change(function(){
  //   var formattedDate = new Date();
  //   var d = formattedDate.getDate();
  //   var m =  formattedDate.getMonth();
  //   m += 1;  // JavaScript months are 0-11
  //   var y = formattedDate.getFullYear();
  //   $("#job_date").val(d+"/"+m+"/"+y);
  // });

  //=== Works JS ====
  $("#btnaddcontractor").hide();
  $("#btnadd_var_contractor").hide();
  $("#btnaddcontractor").click(function(){
  
    $("#select2-chosen-1").text("");
    $('select#contact_person').attr('disabled',false);
    $('select#contact_person').empty();
    
    var formattedDate = new Date();
    var d = formattedDate.getDate();
    var m =  formattedDate.getMonth();
    m += 1;  // JavaScript months are 0-11
    var y = formattedDate.getFullYear();
    $("#contractor_date_entered").val(d+"/"+m+"/"+y);

    $("#work_contructor_name").val("");

    //$("select#work_contructor_name").val("");

    $("#contact_person").empty();
    $("#inc_gst").val("");
    $("#price_ex_gst").val("");
   
    $("#save_contractor").show();
    $("#create_cqr").hide();
    $("#update_contractor").hide();
    $("#delete_contractor").hide();
   
  });
$("#btnadd_var_contractor").click(function(){
  $("#select2-chosen-1").text("");
  $('select#contact_person').attr('disabled',false);
  $('select#contact_person').empty();
  
  var formattedDate = new Date();
  var d = formattedDate.getDate();
  var m =  formattedDate.getMonth();
  m += 1;  // JavaScript months are 0-11
  var y = formattedDate.getFullYear();
  $("#contractor_date_entered").val(d+"/"+m+"/"+y);

  $("#var_work_contructor_name").val("");

    //$("select#work_contructor_name").val("");

  $("#contact_person").empty();
  // $("#inc_gst").val("");
  // $("#price_ex_gst").val("");
   
  $("#save_var_contractor").show();
  $("#create_var_cqr").hide();
  $("#update_var_contractor").hide();
  $("#delete_var_contractor").hide();
});
$('.work_contractor_click').click(function () {

    $('table#table-wd').find('tr').each(function(){
      $(this).removeClass('active_row_work');
    });

    $(this).parent().parent().addClass('active_row_work');

});


  window.selwork = function(a){
    contractor_set = 1;
    work_id = a;
    selected_work_contractor_id = 0;
    work_joinery_id = 0;
    joinery_work_id = 0;
    //var proj_id = get_project_id();
    
      $.post(baseurl+"works/display_work_contractor", 
      { 
        proj_id: proj_id,
        work_id: a
      }, 
      function(result){
        $("#work_contractors").html(result);
        $("#btn_select_subcontractor").show();
        $("#cont_cpono").html(work_id);
        // if(job_date == ""){
        //   $("#btnaddcontractor").show();
        // }else{
          // var work_estimate = $(".work-set-estimate-"+work_id).val();
          // if(work_estimate == 0){
            $("#btnaddcontractor").show();
          // }else{
          //   $("#btnaddcontractor").hide();
          // }
        //}
      });
    
    /*var proj_id = get_project_id();
    $.post(baseurl+"works/display_work_contractor", 
    { 
      proj_id: proj_id,
      work_id: a
    }, 
    function(result){
      $("#work_contractors").html(result);

      $("#btn_select_subcontractor").show();
      $("#cont_cpono").html(work_id);
      $("#btnaddcontractor").show();
    });*/
    return false;
  }

  window.selvar_work = function(a){
    var var_acceptance_date = $("#variation_acceptance_date").val();
    contractor_set = 1;
    work_id = a;
    selected_work_contractor_id = 0;
    work_joinery_id = 0;
    joinery_work_id = 0;
    //var proj_id = get_project_id();
    
      $.post(baseurl+"works/display_var_work_contractor", 
      { 
        var_acceptance_date: var_acceptance_date,
        proj_id: proj_id,
        work_id: a
      }, 
      function(result){
        $("#var_work_contractors").html(result);
        $("#btn_select_subcontractor").show();
        $("#var_cont_cpono").html(work_id);
        // if(job_date == ""){
        //   $("#btnaddcontractor").show();
        // }else{
          // var work_estimate = $(".work-set-estimate-"+work_id).val();
          // if(work_estimate == 0){
            $("#btnadd_var_contractor").show();
          // }else{
          //   $("#btnaddcontractor").hide();
          // }
        //}
      });
    
    /*var proj_id = get_project_id();
    $.post(baseurl+"works/display_work_contractor", 
    { 
      proj_id: proj_id,
      work_id: a
    }, 
    function(result){
      $("#work_contractors").html(result);

      $("#btn_select_subcontractor").show();
      $("#cont_cpono").html(work_id);
      $("#btnaddcontractor").show();
    });*/
    return false;
  }

  window.selwork_badge = function(a){
    contractor_set = 0;
    work_id = a;
    selected_work_contractor_id = 0;
    work_joinery_id = 0;
    joinery_work_id = 0;
    //var proj_id = get_project_id();
    
 
      $.post(baseurl+"works/display_work_contractor", 
      { 
        proj_id: proj_id,
        work_id: a
      }, 
      function(result){
        $("#work_contractors").html(result);
        $("#btn_select_subcontractor").show();
        $("#cont_cpono").html(work_id);
        // if(job_date == ""){
        //   $("#btnaddcontractor").show();
        // }else{
          // var work_estimate = $(".work-set-estimate-"+work_id).val();
          // if(work_estimate == 0){
            $("#btnaddcontractor").show();
        //   }else{
        //     $("#btnaddcontractor").hide();
        //   }
        // }
      });

    return false;
  }
  
  window.sel_var_work_badge = function(a){
    var var_acceptance_date = $("#variation_acceptance_date").val();
    contractor_set = 0;
    work_id = a;
    selected_work_contractor_id = 0;
    work_joinery_id = 0;
    joinery_work_id = 0;
    //var proj_id = get_project_id();
    
 
      $.post(baseurl+"works/display_var_work_contractor", 
      { 
        var_acceptance_date: var_acceptance_date,
        proj_id: proj_id,
        work_id: a
      }, 
      function(result){
        $("#var_work_contractors").html(result);
        $("#btn_select_subcontractor").show();
        $("#var_cont_cpono").html(work_id);
        // if(job_date == ""){
        //   $("#btnaddcontractor").show();
        // }else{
          // var work_estimate = $(".work-set-estimate-"+work_id).val();
          // if(work_estimate == 0){
            $("#btnadd_var_contractor").show();
        //   }else{
        //     $("#btnaddcontractor").hide();
        //   }
        // }
      });

    return false;
  }
//--------------------------------------------------------
  window.clk_work_price = function(a){
    work_id = a;
    work_price = $(".work-set-price-"+a).val();
    work_price = work_price.replace(',', '');
  }
  window.update_price = function(a){
    work_price_edited = $(".work-set-price-"+a).val();
    work_price_edited = work_price_edited.replace(',', '');
    if(work_price_edited !== work_price){
      $('.modal').modal('hide');
      $('#work_update_conf').modal('show');
    }
   
  }
  $("#update_price_no").click(function(){
    if(work_price_edited !== work_price){
      work_price = numberWithCommas(Math.round(work_price));
      $(".work-set-price-"+work_id).val(work_price);
    }
  });
  $("#update_price_yes").click(function(){
    $.post(baseurl+"works/update_work",
    {
      work_id: work_id,
      price: work_price_edited,
      update_stat: 7
    },
    function(result){
      //var proj_id = get_project_id();
      get_project_totals();
      // $.post(baseurl+"works/works_total",
      // {
      //   proj_id: proj_id
      // },
      // function(result){
      //   var works_totals = result.split( '/' );
      //   var t_price = works_totals[0];
      //   var t_estimate = works_totals[1];
      //   var t_quoted = works_totals[2];
      //   $("#work-total-price").val(t_price);
      //   $("#work-total-estimate").val(t_estimate);
      //   $("#work-total-quoted").val(t_quoted);
      // });
    });
  });
  //===============================
  window.clk_work_estimate = function(a){
    $(this).select();
    work_id = a;
    work_estimate = $(".work-set-estimate-"+a).val();
    work_estimate = work_estimate.replace(',', '');
    $.post(baseurl+"works/get_work_markup",
    {
      work_id: work_id
    },
    function(result){
      work_markup = result;
    });
  }
  // window.focus_works_estimate = function(a){
  //    work_id = a;
  //   $.post(baseurl+"works/get_work_markup",
  //   {
  //     work_id: work_id
  //   },
  //   function(result){
  //     work_markup = result;
  //   });
  // }

  window.ku_update_estimate = function(a){
      work_id = a;
      work_markup = $(".row_works_mark_up_"+a).val();
      work_estimate_edited = $(".work-set-estimate-"+a).val();
      work_estimate_edited = work_estimate_edited.replace(',', '');
      //var proj_id = get_project_id();
      quoted = +(work_estimate_edited) +  (+(work_estimate_edited) * (+(work_markup)/100));
      quoted_w_comma = numberWithCommas(Math.round(quoted));
      $('tbody tr#row-work-'+work_id).find('.work-set-quote-'+work_id).text(quoted_w_comma);
  }
  window.update_estimate = function(a){
    //work_estimate_edited = $(".work-set-estimate-"+a).val();
    //work_estimate_edited = work_estimate_edited.replace(',', '');
    //if(work_estimate_edited !== work_estimate){
      //$('.modal').modal('hide');
      //$('#work_update_estimate_conf').modal('show');

      //var proj_id = get_project_id();
     // $.post(baseurl+"works/get_work_markup",
     // {
     //   work_id: work_id
    //  },
    //  function(result){
     //   var work_markup = result;
    //    var quoted = +(work_estimate_edited) +  (+(work_estimate_edited) * (+(work_markup)/100));
        $.post(baseurl+"works/update_work",
        {
          work_id: work_id,
          price: work_estimate_edited,
          quoted: quoted,
          update_stat: 8
        },
        function(result){
          //quoted = numberWithCommas(quoted);
          //$('tbody tr#row-work-'+work_id).find('.work-set-quote-'+work_id).text(quoted);
          
          get_project_totals();
          // $.post(baseurl+"works/works_total",
          // {
          //   proj_id: proj_id
          // },
          // function(result){
          //   var works_totals = result.split( '/' );
          //   var t_price = works_totals[0];
          //   var t_estimate = works_totals[1];
          //   var t_quoted = works_totals[2];
          //   $("#work-total-price").val(t_price);
          //   $("#work-total-estimate").val(t_estimate);
          //   $("#work-total-quoted").val(t_quoted);

          //   if(variation_id !== undefined){
          //     proj_id = get_project_id();
          //     $.post(baseurl+"works/var_works_total",
          //     {
          //       proj_id: proj_id,
          //       variation_id: variation_id
          //     },
          //     function(result){
          //       var works_totals = result.split( '/' );
          //       var t_price = works_totals[0];
          //       var t_estimate = works_totals[1];
          //       var t_quoted = works_totals[2];
          //       $("#var-work-total-price").val(t_price);
          //       $("#var-work-total-estimate").val(t_estimate);
          //       $("#var-work-total-quoted").val(t_quoted);
          //       $.post(baseurl+"works/fetch_gst_rate",{},function(result){ 
          //         gst_rate = result; 
          //         $.post(baseurl+"works/job_date_entered",
          //         {
          //           proj_id: proj_id
          //         },
          //         function(result){
          //           job_date = result;
          //         });
          //       });
          //     });
          //   }
          // });
          
        });
    //  });
   // }
  }


  $("#update_estimate_no").click(function(){
    if(work_estimate_edited !== work_estimate){
      work_estimate = numberWithCommas(Math.round(work_estimate));
      $(".work-set-estimate-"+work_id).val(work_estimate);
    }
  });

  $("#update_estimate_yes").click(function(){
    //var proj_id = get_project_id();
     $.post(baseurl+"works/get_work_markup",
    {
      work_id: work_id
    },
    function(result){
      var work_markup = result;
      var quoted = +(work_estimate_edited) +  (+(work_estimate_edited) * (+(work_markup)/100));
      $.post(baseurl+"works/update_work",
      {
        work_id: work_id,
        price: work_estimate_edited,
        quoted: quoted,
        update_stat: 8
      },
      function(result){
        quoted = numberWithCommas(Math.round(quoted));
        $('tbody tr#row-work-'+work_id).find('.work-set-quote-'+work_id).text(quoted);
        $.post(baseurl+"works/works_total",
        {
          proj_id: proj_id
        },
        function(result){
          var works_totals = result.split( '/' );
          var t_price = works_totals[0];
          var t_estimate = works_totals[1];
          var t_quoted = works_totals[2];
          $("#work-total-price").val(t_price);
          $("#work-total-estimate").val(t_estimate);
          $("#work-total-quoted").val(t_quoted);
        });
      });
    });
  });

  //====================================
  //====== Joinery =====================
  window.clk_work_joinery_estimate = function(a){
    work_joinery_id = a;
    work_joinery_estimate = $(".work-joinery-set-estimate-"+a).val();
    work_joinery_estimate = work_estimate.replace(',', '');
  }

  window.update_joinery_estimate = function(a){
    work_joinery_estimate_edited = $(".work-joinery-set-estimate-"+a).val();
    work_joinery_estimate_edited = work_joinery_estimate_edited.replace(',', '');
    if(work_joinery_estimate_edited !== work_joinery_estimate){
      $('.modal').modal('hide');
      $('#work_joinery_update_estimate_conf').modal('show');
    }
  }

  $("#update_joinery_estimate_no").click(function(){
    if(work_joinery_estimate_edited !== work_joinery_estimate){
      $(".work-joinery-set-estimate-"+work_joinery_id).val(work_joinery_estimate);
    }
  });

  $("#update_joinery_estimate_yes").click(function(){
    //var proj_id = get_project_id();
    $.post(baseurl+"works/get_work_joinery_markup",
    {
      work_joinery_id: work_joinery_id
    },
    function(result){
      var work_markup = result;
      var quoted = +(work_joinery_estimate_edited) +  (+(work_joinery_estimate_edited) * (+(work_markup)/100));
      $.post(baseurl+"works/update_work_joinery",
      {
        work_joinery_id: work_joinery_id,
        price: work_joinery_estimate_edited,
        quoted: quoted
      },
      function(result){
        var works_joinery_totals = result.split( '|' );
        var tj_estimate = works_joinery_totals[0];
        var tj_quote = works_joinery_totals[1];
        var work_id = works_joinery_totals[2];
        quoted = numberWithCommas(quoted);
        tj_quote = numberWithCommas(Math.round(tj_quote));
        tj_estimate = numberWithCommas(Math.round(tj_estimate));
        $('tbody tr#row-work-joinery-'+work_joinery_id).find('.work-joinery-set-quote-'+work_joinery_id).text(quoted);
        $('tbody tr#row-work-'+work_id).find('.work-set-estimate-'+work_id).text(tj_estimate);
        $('tbody tr#row-work-'+work_id).find('.work-set-quote-'+work_id).text(tj_quote);
        $.post(baseurl+"works/works_total",
        {
          proj_id: proj_id
        },
        function(result){
          var works_totals = result.split( '/' );
          var t_price = works_totals[0];
          var t_estimate = works_totals[1];
          var t_quoted = works_totals[2];
          $("#work-total-price").val(t_price);
          $("#work-total-estimate").val(t_estimate);
          $("#work-total-quoted").val(t_quoted);
        });
      });
    });
  });

  window.selwork_joinery = function(a){
    work_joinery_id = a;
    contractor_set = 1;
    selected_work_contractor_id = 0;
    $.post(baseurl+"works/get_joinery_work_id", 
    { 
      work_joinery_id: work_joinery_id
    }, 
    function(result){
      work_id = result;
      joinery_work_id = work_id+"-"+work_joinery_id;
      //var proj_id = get_project_id();
      $.post(baseurl+"works/display_work_contractor", 
      { 
        proj_id: proj_id,
        work_id: joinery_work_id
      }, 
      function(result){
        $("#work_contractors").html(result);
        $("#btn_select_subcontractor").show();
        $("#cont_cpono").html(joinery_work_id);
        //var proj_id = get_project_id();
       
          //if(job_date == ""){
            $("#btnaddcontractor").show();
          //}
      });
    });
    return false;
  }

  window.sel_var_work_joinery = function(a){
    work_joinery_id = a;
    contractor_set = 1;
    selected_work_contractor_id = 0;
    var var_acceptance_date = $("#variation_acceptance_date").val();
    $.post(baseurl+"works/get_joinery_work_id", 
    { 
      work_joinery_id: work_joinery_id
    }, 
    function(result){
      work_id = result;
      joinery_work_id = work_id+"-"+work_joinery_id;
      //var proj_id = get_project_id();
      $.post(baseurl+"works/display_work_contractor", 
      { 
        var_acceptance_date: var_acceptance_date,
        proj_id: proj_id,
        work_id: joinery_work_id
      }, 
      function(result){
        $("#var_work_contractors").html(result);
        $("#btn_select_subcontractor").show();
        $("#var_cont_cpono").html(joinery_work_id);
        //var proj_id = get_project_id();
       
          //if(job_date == ""){
            $("#btnadd_var_contractor").show();
          //}
      });
    });
    return false;
  }

  window.clk_work_joinery_unit_price = function(a){
    work_joinery_id = a;
    work_joinery_unit_price = $(".work_joinery_set_unit_price_"+a).val();
  }
  window.ku_work_joinery_unit_price = function(a){
    work_joinery_id = a;

    // var joinery_works_id = $("#joinery_works_id").val();
    // if(joinery_works_id !== ""){
    //   $(".work-set-estimate-"+joinery_works_id).attr("disabled", true);
    // }

    var qty = $(".work_joinery_set_qty_"+a).val();
    work_joinery_unit_price_edited = $(".work_joinery_set_unit_price_"+a).val();
    var t_price = work_joinery_unit_price_edited * qty;
    $(".work_joinery_set_total_price_"+a).val(t_price);
  }
  window.update_joinery_unit_price = function(a){
    work_joinery_id = a;
    if(work_joinery_unit_price !== work_joinery_unit_price_edited){
      //$('.modal').modal('hide');
      //$("#update_work_joinery_unit_price_conf").modal('show');
      var qty = $(".work_joinery_set_qty_"+work_joinery_id).val();
      var t_price = $(".work_joinery_set_total_price_"+work_joinery_id).val();
      $.post(baseurl+"works/update_joinery_selected_subitem_price", 
      { 
        work_joinery_id: work_joinery_id,
        work_joinery_unit_price: work_joinery_unit_price_edited,
        t_price: t_price,
        joinery_qty: qty
      }, 
      function(result){
      });
    }
  }
  $("#update_work_joinery_unitprice_no").click(function(){
    $(".work_joinery_set_unit_price_"+work_joinery_id).val(work_joinery_unit_price);
    var qty = $(".work_joinery_set_qty_"+work_joinery_id).val();
    var t_price = work_joinery_unit_price * qty;
    $(".work_joinery_set_total_price_"+work_joinery_id).val(t_price);
  });
  $("#update_work_joinery_unitprice_yes").click(function(){
    var qty = $(".work_joinery_set_qty_"+work_joinery_id).val();
    var t_price = $(".work_joinery_set_total_price_"+work_joinery_id).val();
    $.post(baseurl+"works/update_joinery_selected_subitem_price", 
    { 
      work_joinery_id: work_joinery_id,
      work_joinery_unit_price: work_joinery_unit_price_edited,
      t_price: t_price,
      joinery_qty: qty
    }, 
    function(result){
    });
  });
  window.clk_work_joinery_unit_estimate = function(a){
    work_joinery_id = a;
    work_joinery_unit_estimated = $(".work_joinery_set_unit_estimate_"+work_joinery_id).val();
  }
  window.ku_work_joinery_unit_estimate = function(a){
    work_joinery_id = a;

    // var joinery_works_id = $("#joinery_works_id").val();
    // if(joinery_works_id !== ""){
    //   $(".work-set-estimate-"+joinery_works_id).attr("disabled", true);
    // }

    var qty = $(".work_joinery_set_qty_"+work_joinery_id).val();
    var joinery_markup = $("#joinery_markup_"+work_joinery_id).val();
    work_joinery_unit_estimated_edited = $(".work_joinery_set_unit_estimate_"+a).val();
    work_joinery_unit_estimated_edited = work_joinery_unit_estimated_edited.replace(/,/g, '');
    var t_estimate = qty * work_joinery_unit_estimated_edited;
    $(".work-joinery-set-estimate-"+work_joinery_id).val(numberWithCommas(t_estimate));
    quoted = +(t_estimate) +  (+(t_estimate) * (+(joinery_markup)/100));
    $('tbody tr#row-work-joinery-'+work_joinery_id).find('.work-joinery-set-quote-'+work_joinery_id).text(numberWithCommas(quoted));
  }
  window.update_joinery_unit_estimate = function(){
    if(work_joinery_unit_estimated_edited !== work_joinery_unit_estimated){
      //$('.modal').modal('hide');
      //$("#update_work_joinery_unit_estimate_conf").modal("show");
      work_id = $("#works_id").val();
      var total_estimate = $(".work-joinery-set-estimate-"+work_joinery_id).val();
      total_estimate = total_estimate.replace(/,/g, '');
      $.post(baseurl+"works/update_joinery_selected_subitem_estimate", 
      { 
        work_joinery_id: work_joinery_id,
        work_id: work_id,
        work_joinery_unit_estimated: work_joinery_unit_estimated_edited,
        t_estimate: total_estimate,
        quoted: quoted
      }, 
      function(result){
        $.post(baseurl+"works/get_joinery_totals", 
        { 
          work_id: work_id
        }, 
        function(result){
          var works_joinery_totals = result.split( '|' );
          var work_joinery_price = works_joinery_totals[0];
          var work_joinery_estimate = works_joinery_totals[1];
          var work_joinery_quoted = works_joinery_totals[2];
          work_joinery_quoted = numberWithCommas(Math.round(work_joinery_quoted));
          work_joinery_estimate = numberWithCommas(Math.round(work_joinery_estimate));
          work_joinery_price = numberWithCommas(Math.round(work_joinery_price));
          $(".work-set-price-"+work_id).val(work_joinery_price);
          $(".work-set-estimate-"+work_id).val(work_joinery_estimate);
          $('tbody tr#row-work-'+work_id).find('.work-set-quote-'+work_id).text(work_joinery_quoted);
          //var proj_id = get_project_id();

          get_project_totals();
          // $.post(baseurl+"works/works_total",
          // {
          //   proj_id: proj_id
          // },
          // function(result){
          //   var works_totals = result.split( '/' );
          //   var t_price = works_totals[0];
          //   var t_estimate = works_totals[1];
          //   var t_quoted = works_totals[2];
          //   $("#work-total-price").val(t_price);
          //   $("#work-total-estimate").val(t_estimate);
          //   $("#work-total-quoted").val(t_quoted);
          // });
        });
      });
    }
  }

  window.update_var_joinery_unit_estimate = function(){
    if(work_joinery_unit_estimated_edited !== work_joinery_unit_estimated){
      //$('.modal').modal('hide');
      //$("#update_work_joinery_unit_estimate_conf").modal("show");
      work_id = $("#var_works_id").val();
      var total_estimate = $(".work-joinery-set-estimate-"+work_joinery_id).val();
      total_estimate = total_estimate.replace(/,/g, '');
      $.post(baseurl+"works/update_joinery_selected_subitem_estimate", 
      { 
        work_joinery_id: work_joinery_id,
        work_id: work_id,
        work_joinery_unit_estimated: work_joinery_unit_estimated_edited,
        t_estimate: total_estimate,
        quoted: quoted
      }, 
      function(result){
        $.post(baseurl+"works/get_joinery_totals", 
        { 
          work_id: work_id
        }, 
        function(result){
          var works_joinery_totals = result.split( '|' );
          var work_joinery_price = works_joinery_totals[0];
          var work_joinery_estimate = works_joinery_totals[1];
          var work_joinery_quoted = works_joinery_totals[2];
          work_joinery_quoted = numberWithCommas(Math.round(work_joinery_quoted));
          work_joinery_estimate = numberWithCommas(Math.round(work_joinery_estimate));
          work_joinery_price = numberWithCommas(Math.round(work_joinery_price));
          $(".work-set-price-"+work_id).val(work_joinery_price);
          $(".work-set-estimate-"+work_id).val(work_joinery_estimate);
          $('tbody tr#row-work-'+work_id).find('.work-set-quote-'+work_id).text(work_joinery_quoted);
          //var proj_id = get_project_id();
          proj_id = get_project_id();
          $.post(baseurl+"works/var_works_total",
          {
            proj_id: proj_id,
            variation_id: variation_id
          },
          function(result){
            var works_totals = result.split( '/' );
            var t_price = works_totals[0];
            var t_estimate = works_totals[1];
            var t_quoted = works_totals[2];
            $("#var-work-total-price").val(t_price);
            $("#var-work-total-estimate").val(t_estimate);
            $("#var-work-total-quoted").val(t_quoted);
            $.post(baseurl+"works/fetch_gst_rate",{},function(result){ 
              gst_rate = result; 
              $.post(baseurl+"works/job_date_entered",
              {
                proj_id: proj_id
              },
              function(result){
                job_date = result;
              });
            });
          });
        });
      });
    }
  }

  $("#update_work_joinery_unitestimate_no").click(function(){
    $(".work_joinery_set_unit_estimate_"+work_joinery_id).val(work_joinery_unit_estimated);
    var qty = $(".work_joinery_set_qty_"+work_joinery_id).val();
    var joinery_markup = $("#joinery_markup").val();
    var t_estimate = qty * work_joinery_unit_estimated;
    $(".work-joinery-set-estimate-"+work_joinery_id).val(t_estimate);
    quoted = +(t_estimate) +  (+(t_estimate) * (+(joinery_markup)/100));
    $('tbody tr#row-work-joinery-'+work_joinery_id).find('.work-joinery-set-quote-'+work_joinery_id).text(quoted);
  });

  $("#update_work_joinery_unitestimate_yes").click(function(){
    work_id = $("#works_id").val();
    var total_estimate = $(".work-joinery-set-estimate-"+work_joinery_id).val();
    $.post(baseurl+"works/update_joinery_selected_subitem_estimate", 
    { 
      work_joinery_id: work_joinery_id,
      work_id: work_id,
      work_joinery_unit_estimated: work_joinery_unit_estimated_edited,
      t_estimate: total_estimate,
      quoted: quoted
    }, 
    function(result){
      $.post(baseurl+"works/get_joinery_totals", 
      { 
        work_id: work_id
      }, 
      function(result){
        var works_joinery_totals = result.split( '|' );
        var work_joinery_price = works_joinery_totals[0];
        var work_joinery_estimate = works_joinery_totals[1];
        var work_joinery_quoted = works_joinery_totals[2];

        work_joinery_quoted = numberWithCommas(Math.round(work_joinery_quoted));
        work_joinery_estimate = numberWithCommas(Math.round(work_joinery_estimate));
        work_joinery_price = numberWithCommas(Math.round(work_joinery_price));

        $(".work-set-price-"+work_id).val(work_joinery_price);
        $(".work-set-estimate-"+work_id).val(work_joinery_estimate);
        $('tbody tr#row-work-'+work_id).find('.work-set-quote-'+work_id).text(work_joinery_quoted);
        //var proj_id = get_project_id();
        $.post(baseurl+"works/works_total",
        {
          proj_id: proj_id
        },
        function(result){
          var works_totals = result.split( '/' );
          var t_price = works_totals[0];
          var t_estimate = works_totals[1];
          var t_quoted = works_totals[2];
          $("#work-total-price").val(t_price);
          $("#work-total-estimate").val(t_estimate);
          $("#work-total-quoted").val(t_quoted);
        });
      });
    });
  });
  window.clk_work_joinery_qty = function(a){
    work_joinery_qty = $(".work_joinery_set_qty_"+a).val();
  }
  window.ku_work_joinery_qty = function(a){
    work_joinery_id = a;
    // var joinery_works_id = $("#joinery_works_id").val();
    // if(joinery_works_id !== ""){
    //   $(".work-set-estimate-"+joinery_works_id).attr("disabled", true);
    // }

    

    work_joinery_qty_edited = $(".work_joinery_set_qty_"+a).val();
    work_joinery_unit_price_edited = $(".work_joinery_set_unit_price_"+a).val();
    work_joinery_unit_price_edited=work_joinery_unit_price_edited.replace(/,/g, '');
    var t_price = work_joinery_unit_price_edited * work_joinery_qty_edited;
    $(".work_joinery_set_total_price_"+a).val(numberWithCommas(t_price));

    var joinery_markup = $("#joinery_markup_"+work_joinery_id).val();
    work_joinery_unit_estimated_edited = $(".work_joinery_set_unit_estimate_"+a).val();
    work_joinery_unit_estimated_edited = work_joinery_unit_estimated_edited.replace(/,/g, '');
    var t_estimate = work_joinery_qty_edited * work_joinery_unit_estimated_edited;
    $(".work-joinery-set-estimate-"+work_joinery_id).val(numberWithCommas(t_estimate));
    quoted = +(t_estimate) +  (+(t_estimate) * (+(joinery_markup)/100));
    $('tbody tr#row-work-joinery-'+work_joinery_id).find('.work-joinery-set-quote-'+work_joinery_id).text(numberWithCommas(quoted));
  }
  window.update_joinery_qty = function(){
    //$('.modal').modal('hide');
    //$("#update_work_joinery_qty_conf").modal("show");
    var work_contractor = $("#work_joinery_contractor_id").val();
    if(work_contractor = "" || work_contractor == 0){
      work_id = $("#works_id").val();
      var total_estimate = $(".work-joinery-set-estimate-"+work_joinery_id).val();
      total_estimate = total_estimate.replace(/,/g, '');
      $.post(baseurl+"works/update_joinery_selected_subitem_estimate", 
      { 
        work_joinery_id: work_joinery_id,
        work_id: work_id,
        work_joinery_unit_estimated: work_joinery_unit_estimated_edited,
        t_estimate: total_estimate,
        quoted: quoted
      }, 
      function(result){var works_joinery_totals = result.split( '|' );
        var work_joinery_price = works_joinery_totals[0];
        var work_joinery_estimate = works_joinery_totals[1];
        var work_joinery_quoted = works_joinery_totals[2];

        work_joinery_quoted = numberWithCommas(Math.round(work_joinery_quoted));
        work_joinery_estimate = numberWithCommas(Math.round(work_joinery_estimate));
        work_joinery_price = numberWithCommas(Math.round(work_joinery_price));

        $(".work-set-price-"+work_id).val(work_joinery_price);
        if(work_joinery_estimate > 0){
          $(".work-set-estimate-"+work_id).val(work_joinery_estimate);
          $('tbody tr#row-work-'+work_id).find('.work-set-quote-'+work_id).text(work_joinery_quoted);
        }
        var t_price = $(".work_joinery_set_total_price_"+work_joinery_id).val();
        t_price = t_price.replace(/,/g, '');
        $.post(baseurl+"works/set_joinery_subitem_contractor", 
        { 
          work_joinery_id: work_joinery_id,
          work_id: work_id,
          unit_price: work_joinery_unit_price_edited,
          t_price: t_price,
          qty: work_joinery_qty_edited,
          company_id: ""
        }, 
        function(result){
          $.post(baseurl+"works/get_joinery_totals", 
            { 
              work_id: work_id
            }, 
            function(result){
              var works_joinery_totals = result.split( '|' );
              var work_joinery_price = works_joinery_totals[0];
              var work_joinery_estimate = works_joinery_totals[1];
              var work_joinery_quoted = works_joinery_totals[2];
              work_joinery_quoted = numberWithCommas(Math.round(work_joinery_quoted));
              work_joinery_estimate = numberWithCommas(Math.round(work_joinery_estimate));
              work_joinery_price = numberWithCommas(Math.round(work_joinery_price));
              $(".work-set-price-"+work_id).val(work_joinery_price);
              $(".work-set-estimate-"+work_id).val(work_joinery_estimate);
              $('tbody tr#row-work-'+work_id).find('.work-set-quote-'+work_id).text(work_joinery_quoted);
          // $.post(baseurl+"works/get_joinery_totals", 
          // {
          //   work_id: work_id,
          // }, 
          // function(result){
          //   var works_joinery_totals = result.split( '|' );
          //   var work_joinery_price = works_joinery_totals[0];
          //   var work_joinery_estimate = works_joinery_totals[1];
          //   var work_joinery_quoted = works_joinery_totals[2];

          //   work_joinery_quoted = numberWithCommas(Math.round(work_joinery_quoted));
          //   work_joinery_estimate = numberWithCommas(Math.round(work_joinery_estimate));
          //   work_joinery_price = numberWithCommas(Math.round(work_joinery_price));

          //   $(".work-set-price-"+work_id).val(work_joinery_price);
          //   if(work_joinery_estimate > 0){
          //     $(".work-set-estimate-"+work_id).val(work_joinery_estimate);
          //     $('tbody tr#row-work-'+work_id).find('.work-set-quote-'+work_id).text(work_joinery_quoted);
          //   }
            

            //var proj_id = get_project_id();

            get_project_totals();
            // $.post(baseurl+"works/works_total",
            // {
            //   proj_id: proj_id
            // },
            // function(result){
            //   var works_totals = result.split( '/' );
            //   var t_price = works_totals[0];
            //   var t_estimate = works_totals[1];
            //   var t_quoted = works_totals[2];
            //   $("#work-total-price").val(t_price);
            //   $("#work-total-estimate").val(t_estimate);
            //   $("#work-total-quoted").val(t_quoted);
            // });
          });
        });
      });
    }else{
      work_id = $("#works_id").val();
      var total_estimate = $(".work-joinery-set-estimate-"+work_joinery_id).val();
      total_estimate = total_estimate.replace(/,/g, '');
      $.post(baseurl+"works/update_joinery_selected_subitem_estimate", 
      { 
        work_joinery_id: work_joinery_id,
        work_id: work_id,
        work_joinery_unit_estimated: work_joinery_unit_estimated_edited,
        t_estimate: total_estimate,
        quoted: quoted
      }, 
      function(result){var works_joinery_totals = result.split( '|' );
        var work_joinery_price = works_joinery_totals[0];
        var work_joinery_estimate = works_joinery_totals[1];
        var work_joinery_quoted = works_joinery_totals[2];

        work_joinery_quoted = numberWithCommas(Math.round(work_joinery_quoted));
        work_joinery_estimate = numberWithCommas(Math.round(work_joinery_estimate));
        work_joinery_price = numberWithCommas(Math.round(work_joinery_price));

        $(".work-set-price-"+work_id).val(work_joinery_price);
        if(work_joinery_estimate > 0){
          $(".work-set-estimate-"+work_id).val(work_joinery_estimate);
          $('tbody tr#row-work-'+work_id).find('.work-set-quote-'+work_id).text(work_joinery_quoted);
        }
        var t_price = $(".work_joinery_set_total_price_"+work_joinery_id).val();
        t_price = t_price.replace(/,/g, '');
        $.post(baseurl+"works/update_joinery_selected_subitem_price", 
        { 
          work_joinery_id: work_joinery_id,
          work_joinery_unit_price: work_joinery_unit_price_edited,
          t_price: t_price,
          joinery_qty: work_joinery_qty_edited
        }, 
        function(result){
          $.post(baseurl+"works/get_joinery_totals", 
          { 
            work_id: work_id
          }, 
          function(result){
            var works_joinery_totals = result.split( '|' );
            var work_joinery_price = works_joinery_totals[0];
            var work_joinery_estimate = works_joinery_totals[1];
            var work_joinery_quoted = works_joinery_totals[2];
            work_joinery_quoted = numberWithCommas(Math.round(work_joinery_quoted));
            work_joinery_estimate = numberWithCommas(Math.round(work_joinery_estimate));
            work_joinery_price = numberWithCommas(Math.round(work_joinery_price));
            $(".work-set-price-"+work_id).val(work_joinery_price);
            $(".work-set-estimate-"+work_id).val(work_joinery_estimate);
            $('tbody tr#row-work-'+work_id).find('.work-set-quote-'+work_id).text(work_joinery_quoted);
          // $.post(baseurl+"works/get_joinery_totals", 
          // {
          //   work_id: work_id,
          // }, 
          // function(result){
          //   var works_joinery_totals = result.split( '|' );
          //   var work_joinery_price = works_joinery_totals[0];
          //   var work_joinery_estimate = works_joinery_totals[1];
          //   var work_joinery_quoted = works_joinery_totals[2];

          //   work_joinery_quoted = numberWithCommas(Math.round(work_joinery_quoted));
          //   work_joinery_estimate = numberWithCommas(Math.round(work_joinery_estimate));
          //   work_joinery_price = numberWithCommas(Math.round(work_joinery_price));

          //   $(".work-set-price-"+work_id).val(work_joinery_price);
          //   if(work_joinery_estimate > 0){
          //     $(".work-set-estimate-"+work_id).val(work_joinery_estimate);
          //     $('tbody tr#row-work-'+work_id).find('.work-set-quote-'+work_id).text(work_joinery_quoted);
          //   }
            //var proj_id = get_project_id();


            get_project_totals();
            // $.post(baseurl+"works/works_total",
            // {
            //   proj_id: proj_id
            // },
            // function(result){
            //   var works_totals = result.split( '/' );
            //   var t_price = works_totals[0];
            //   var t_estimate = works_totals[1];
            //   var t_quoted = works_totals[2];
            //   $("#work-total-price").val(t_price);
            //   $("#work-total-estimate").val(t_estimate);
            //   $("#work-total-quoted").val(t_quoted);
            // });
          });
        });
      });
    }
  }

  window.update_var_joinery_qty = function(){

    //$('.modal').modal('hide');
    //$("#update_work_joinery_qty_conf").modal("show");
    var work_contractor = $("#work_joinery_contractor_id").val();
    if(work_contractor = "" || work_contractor == 0){
      work_id = $("#var_works_id").val();
      var total_estimate = $(".work-joinery-set-estimate-"+work_joinery_id).val();
      $.post(baseurl+"works/update_joinery_selected_subitem_estimate", 
      { 
        work_joinery_id: work_joinery_id,
        work_id: work_id,
        work_joinery_unit_estimated: work_joinery_unit_estimated_edited,
        t_estimate: total_estimate,
        quoted: quoted
      }, 
      function(result){
        var works_joinery_totals = result.split( '|' );
        var work_joinery_price = works_joinery_totals[0];
        var work_joinery_estimate = works_joinery_totals[1];
        var work_joinery_quoted = works_joinery_totals[2];

        work_joinery_quoted = numberWithCommas(Math.round(work_joinery_quoted));
        work_joinery_estimate = numberWithCommas(Math.round(work_joinery_estimate));
        work_joinery_price = numberWithCommas(Math.round(work_joinery_price));

        $(".work-set-price-"+work_id).val(work_joinery_price);
        if(work_joinery_estimate > 0){
          $(".work-set-estimate-"+work_id).val(work_joinery_estimate);
          $('tbody tr#row-work-'+work_id).find('.work-set-quote-'+work_id).text(work_joinery_quoted);
        }
        var t_price = $(".work_joinery_set_total_price_"+work_joinery_id).val();
        $.post(baseurl+"works/set_joinery_subitem_contractor", 
        { 
          work_joinery_id: work_joinery_id,
          work_id: work_id,
          unit_price: work_joinery_unit_price_edited,
          t_price: t_price,
          qty: work_joinery_qty_edited,
          company_id: ""
        }, 
        function(result){
          $.post(baseurl+"works/get_joinery_totals", 
          {
            work_id: work_id,
          }, 
          function(result){
            var works_joinery_totals = result.split( '|' );
            var work_joinery_price = works_joinery_totals[0];
            var work_joinery_estimate = works_joinery_totals[1];
            var work_joinery_quoted = works_joinery_totals[2];

            work_joinery_quoted = numberWithCommas(Math.round(work_joinery_quoted));
            work_joinery_estimate = numberWithCommas(Math.round(work_joinery_estimate));
            work_joinery_price = numberWithCommas(Math.round(work_joinery_price));

            $(".work-set-price-"+work_id).val(work_joinery_price);
            if(work_joinery_estimate > 0){
              $(".work-set-estimate-"+work_id).val(work_joinery_estimate);
              $('tbody tr#row-work-'+work_id).find('.work-set-quote-'+work_id).text(work_joinery_quoted);
            }
            

            //var proj_id = get_project_id();
            proj_id = get_project_id();
            $.post(baseurl+"works/var_works_total",
            {
              proj_id: proj_id,
              variation_id: variation_id
            },
            function(result){
              var works_totals = result.split( '/' );
              var t_price = works_totals[0];
              var t_estimate = works_totals[1];
              var t_quoted = works_totals[2];
              $("#var-work-total-price").val(t_price);
              $("#var-work-total-estimate").val(t_estimate);
              $("#var-work-total-quoted").val(t_quoted);
              $.post(baseurl+"works/fetch_gst_rate",{},function(result){ 
                gst_rate = result; 
                $.post(baseurl+"works/job_date_entered",
                {
                  proj_id: proj_id
                },
                function(result){
                  job_date = result;
                });
              });
            });
          });
        });
      });
    }else{
      work_id = $("#var_works_id").val();
      var total_estimate = $(".work-joinery-set-estimate-"+work_joinery_id).val();
      $.post(baseurl+"works/update_joinery_selected_subitem_estimate", 
      { 
        work_joinery_id: work_joinery_id,
        work_id: work_id,
        work_joinery_unit_estimated: work_joinery_unit_estimated_edited,
        t_estimate: total_estimate,
        quoted: quoted
      }, 
      function(result){var works_joinery_totals = result.split( '|' );
        var work_joinery_price = works_joinery_totals[0];
        var work_joinery_estimate = works_joinery_totals[1];
        var work_joinery_quoted = works_joinery_totals[2];

        work_joinery_quoted = numberWithCommas(Math.round(work_joinery_quoted));
        work_joinery_estimate = numberWithCommas(Math.round(work_joinery_estimate));
        work_joinery_price = numberWithCommas(Math.round(work_joinery_price));

        $(".work-set-price-"+work_id).val(work_joinery_price);
        if(work_joinery_estimate > 0){
          $(".work-set-estimate-"+work_id).val(work_joinery_estimate);
          $('tbody tr#row-work-'+work_id).find('.work-set-quote-'+work_id).text(work_joinery_quoted);
        }
        var t_price = $(".work_joinery_set_total_price_"+work_joinery_id).val();
        $.post(baseurl+"works/update_joinery_selected_subitem_price", 
        { 
          work_joinery_id: work_joinery_id,
          work_joinery_unit_price: work_joinery_unit_price_edited,
          t_price: t_price,
          joinery_qty: work_joinery_qty_edited
        }, 
        function(result){
          $.post(baseurl+"works/get_joinery_totals", 
          {
            work_id: work_id,
          }, 
          function(result){
            var works_joinery_totals = result.split( '|' );
            var work_joinery_price = works_joinery_totals[0];
            var work_joinery_estimate = works_joinery_totals[1];
            var work_joinery_quoted = works_joinery_totals[2];

            work_joinery_quoted = numberWithCommas(Math.round(work_joinery_quoted));
            work_joinery_estimate = numberWithCommas(Math.round(work_joinery_estimate));
            work_joinery_price = numberWithCommas(Math.round(work_joinery_price));

            $(".work-set-price-"+work_id).val(work_joinery_price);
            if(work_joinery_estimate > 0){
              $(".work-set-estimate-"+work_id).val(work_joinery_estimate);
              $('tbody tr#row-work-'+work_id).find('.work-set-quote-'+work_id).text(work_joinery_quoted);
            }
            //var proj_id = get_project_id();
            proj_id = get_project_id();
            $.post(baseurl+"works/var_works_total",
            {
              proj_id: proj_id,
              variation_id: variation_id
            },
            function(result){
              var works_totals = result.split( '/' );
              var t_price = works_totals[0];
              var t_estimate = works_totals[1];
              var t_quoted = works_totals[2];
              $("#var-work-total-price").val(t_price);
              $("#var-work-total-estimate").val(t_estimate);
              $("#var-work-total-quoted").val(t_quoted);
              $.post(baseurl+"works/fetch_gst_rate",{},function(result){ 
                gst_rate = result; 
                $.post(baseurl+"works/job_date_entered",
                {
                  proj_id: proj_id
                },
                function(result){
                  job_date = result;
                });
              });
            });
          });
        });
      });
    }
  }


  $("#update_work_joinery_qty_no").click(function(){
    $(".work_joinery_set_qty_"+work_joinery_id).val(work_joinery_qty);
    work_joinery_unit_price_edited = $(".work_joinery_set_unit_price_"+a).val();
    var t_price = work_joinery_unit_price_edited * work_joinery_qty_edited;
    $(".work_joinery_set_total_price_"+a).val(t_price);

    var joinery_markup = $("#joinery_markup").val();
    work_joinery_unit_estimated_edited = $(".work_joinery_set_unit_estimate_"+a).val();
    var t_estimate = work_joinery_qty_edited * work_joinery_unit_estimated_edited;
    $(".work-joinery-set-estimate-"+work_joinery_id).val(t_estimate);
    quoted = +(t_estimate) +  (+(t_estimate) * (+(joinery_markup)/100));
    $('tbody tr#row-work-joinery-'+work_joinery_id).find('.work-joinery-set-quote-'+work_joinery_id).text(quoted);
  });

  $("#update_work_joinery_qty_yes").click(function(){
    var work_contractor = $("#work_joinery_contractor_id").val();
    if(work_contractor = "" || work_contractor == 0){
      work_id = $("#works_id").val();
      var total_estimate = $(".work-joinery-set-estimate-"+work_joinery_id).val();
      $.post(baseurl+"works/update_joinery_selected_subitem_estimate", 
      { 
        work_joinery_id: work_joinery_id,
        work_id: work_id,
        work_joinery_unit_estimated: work_joinery_unit_estimated_edited,
        t_estimate: total_estimate,
        quoted: quoted
      }, 
      function(result){var works_joinery_totals = result.split( '|' );
        var work_joinery_price = works_joinery_totals[0];
        var work_joinery_estimate = works_joinery_totals[1];
        var work_joinery_quoted = works_joinery_totals[2];

        work_joinery_quoted = numberWithCommas(Math.round(work_joinery_quoted));
        work_joinery_estimate = numberWithCommas(Math.round(work_joinery_estimate));
        work_joinery_price = numberWithCommas(Math.round(work_joinery_price));

        $(".work-set-price-"+work_id).val(work_joinery_price);
        $(".work-set-estimate-"+work_id).val(work_joinery_estimate);
        $('tbody tr#row-work-'+work_id).find('.work-set-quote-'+work_id).text(work_joinery_quoted);
        
        var t_price = $(".work_joinery_set_total_price_"+work_joinery_id).val();
        $.post(baseurl+"works/set_joinery_subitem_contractor", 
        { 
          work_joinery_id: work_joinery_id,
          work_id: work_id,
          unit_price: work_joinery_unit_price_edited,
          t_price: t_price,
          qty: work_joinery_qty_edited,
          company_id: ""
        }, 
        function(result){
          $.post(baseurl+"works/get_joinery_totals", 
          {
            work_id: work_id,
          }, 
          function(result){
            var works_joinery_totals = result.split( '|' );
            var work_joinery_price = works_joinery_totals[0];
            var work_joinery_estimate = works_joinery_totals[1];
            var work_joinery_quoted = works_joinery_totals[2];

            work_joinery_quoted = numberWithCommas(Math.round(work_joinery_quoted));
            work_joinery_estimate = numberWithCommas(Math.round(work_joinery_estimate));
            work_joinery_price = numberWithCommas(Math.round(work_joinery_price));

            $(".work-set-price-"+work_id).val(work_joinery_price);
            $(".work-set-estimate-"+work_id).val(work_joinery_estimate);
            $('tbody tr#row-work-'+work_id).find('.work-set-quote-'+work_id).text(work_joinery_quoted);

            //var proj_id = get_project_id();
            $.post(baseurl+"works/works_total",
            {
              proj_id: proj_id
            },
            function(result){
              var works_totals = result.split( '/' );
              var t_price = works_totals[0];
              var t_estimate = works_totals[1];
              var t_quoted = works_totals[2];
              $("#work-total-price").val(t_price);
              $("#work-total-estimate").val(t_estimate);
              $("#work-total-quoted").val(t_quoted);
            });
          });
        });
      });
    }else{
      work_id = $("#works_id").val();
      var total_estimate = $(".work-joinery-set-estimate-"+work_joinery_id).val();
      $.post(baseurl+"works/update_joinery_selected_subitem_estimate", 
      { 
        work_joinery_id: work_joinery_id,
        work_id: work_id,
        work_joinery_unit_estimated: work_joinery_unit_estimated_edited,
        t_estimate: total_estimate,
        quoted: quoted
      }, 
      function(result){var works_joinery_totals = result.split( '|' );
        var work_joinery_price = works_joinery_totals[0];
        var work_joinery_estimate = works_joinery_totals[1];
        var work_joinery_quoted = works_joinery_totals[2];

        work_joinery_quoted = numberWithCommas(Math.round(work_joinery_quoted));
        work_joinery_estimate = numberWithCommas(Math.round(work_joinery_estimate));
        work_joinery_price = numberWithCommas(Math.round(work_joinery_price));

        $(".work-set-price-"+work_id).val(work_joinery_price);
        $(".work-set-estimate-"+work_id).val(work_joinery_estimate);
        $('tbody tr#row-work-'+work_id).find('.work-set-quote-'+work_id).text(work_joinery_quoted);
        
        var t_price = $(".work_joinery_set_total_price_"+work_joinery_id).val();
        $.post(baseurl+"works/update_joinery_selected_subitem_price", 
        { 
          work_joinery_id: work_joinery_id,
          work_joinery_unit_price: work_joinery_unit_price_edited,
          t_price: t_price,
          joinery_qty: work_joinery_qty_edited
        }, 
        function(result){
          $.post(baseurl+"works/get_joinery_totals", 
          {
            work_id: work_id,
          }, 
          function(result){
            var works_joinery_totals = result.split( '|' );
            var work_joinery_price = works_joinery_totals[0];
            var work_joinery_estimate = works_joinery_totals[1];
            var work_joinery_quoted = works_joinery_totals[2];

            work_joinery_quoted = numberWithCommas(Math.round(work_joinery_quoted));
            work_joinery_estimate = numberWithCommas(Math.round(work_joinery_estimate));
            work_joinery_price = numberWithCommas(Math.round(work_joinery_price));

            $(".work-set-price-"+work_id).val(work_joinery_price);
            $(".work-set-estimate-"+work_id).val(work_joinery_estimate);
            $('tbody tr#row-work-'+work_id).find('.work-set-quote-'+work_id).text(work_joinery_quoted);

            //var proj_id = get_project_id();
            $.post(baseurl+"works/works_total",
            {
              proj_id: proj_id
            },
            function(result){
              var works_totals = result.split( '/' );
              var t_price = works_totals[0];
              var t_estimate = works_totals[1];
              var t_quoted = works_totals[2];
              $("#work-total-price").val(t_price);
              $("#work-total-estimate").val(t_estimate);
              $("#work-total-quoted").val(t_quoted);
            });
          });
        });
      });
    }
  });

  window.unset_work_joinery_company = function(a){
    work_joinery_id = a;
    var proj_id = get_project_id();
    $('tbody tr#row-work-joinery-'+work_joinery_id).find('.work_joinery_set_comp_'+work_joinery_id+' a').text("");
    $(".add_comp_joinery_badge_"+a).css('display', 'block');
    $('.unset_joinery_comp_badge_'+a).css('display', 'none');
    
    $(".work_joinery_set_unit_price_"+a).val(0);
    $(".work_joinery_set_total_price_"+a).val(0);
    var var_acceptance_date = $("#variation_acceptance_date").val();
    $.post(baseurl+"works/unset_joinery_subitem_contractor", 
    { 
      var_acceptance_date: var_acceptance_date,
      work_joinery_id: work_joinery_id,
      work_id: work_id,
      proj_id: proj_id
    }, 
    function(result){
      $("#work_contractors").html(result);
      $.post(baseurl+"works/get_joinery_totals", 
      {
        work_id: work_id,
      }, 
      function(result){
        var works_joinery_totals = result.split( '|' );
        var work_joinery_price = works_joinery_totals[0];
        var work_joinery_estimate = works_joinery_totals[1];
        var work_joinery_quoted = works_joinery_totals[2];

        work_joinery_quoted = numberWithCommas(Math.round(work_joinery_quoted));
        work_joinery_estimate = numberWithCommas(Math.round(work_joinery_estimate));
        work_joinery_price = numberWithCommas(Math.round(work_joinery_price));

        $(".work-set-price-"+work_id).val(work_joinery_price);
        $(".work-set-estimate-"+work_id).val(work_joinery_estimate);
        $('tbody tr#row-work-'+work_id).find('.work-set-quote-'+work_id).text(work_joinery_quoted);

        


        get_project_totals();
        // $.post(baseurl+"works/works_total",
        // {
        //   proj_id: proj_id
        // },
        // function(result){
        //   var works_totals = result.split( '/' );
        //   var t_price = works_totals[0];
        //   var t_estimate = works_totals[1];
        //   var t_quoted = works_totals[2];
        //   $("#work-total-price").val(t_price);
        //   $("#work-total-estimate").val(t_estimate);
        //   $("#work-total-quoted").val(t_quoted);
        // });
      });
    });
    
  }
  //====== Joinery =====================
  window.clk_work_cont_exgst = function(a){
    works_contrator_id = a;
    work_cont_exgst = $(".work-set-exgst-"+a).val();
    work_cont_exgst = work_cont_exgst.replace(',', '');
    // $.post(baseurl+"works/fetch_gst_rate", 
    // { 
    // }, 6
    // function(result){
    //   gst_rate = result;
    // });
  }
  window.ku_update_exgst = function(a){
    works_contrator_id = a;
    
    work_cont_exgst_edited = $(".work-set-exgst-"+a).val();
    work_cont_exgst_edited = work_cont_exgst_edited.replace(',', '');
    var percent_gst_rate = +(work_cont_exgst_edited)* (+(gst_rate)/100);
    inc_gst = +(work_cont_exgst_edited) + +(percent_gst_rate);
    inc_gst = inc_gst.toFixed(2);
    var inc_gst_with_comma = numberWithCommas(inc_gst);
    $(".work-set-incgst-"+works_contrator_id).val(inc_gst_with_comma);
  }

  window.update_exgst = function(a){

    //work_cont_exgst_edited = $(".work-set-exgst-"+a).val();
    //work_cont_exgst_edited = work_cont_exgst_edited.replace(',', '');
    //if(work_cont_exgst_edited !== work_cont_exgst){
      //$('.modal').modal('hide');
     // $('#work_cont_update_conf').modal('show');

      //$.post(baseurl+"works/fetch_gst_rate", 
     // { 
     // }, 
     // function(result){
        //var gst_rate = result;
       // gst_rate = +(work_cont_exgst_edited)* (+(gst_rate)/100);
       // var inc_gst = +(work_cont_exgst_edited) + +(gst_rate);
        work_cont_exgst_edited = $(".work-set-exgst-"+works_contrator_id).val();

        work_cont_exgst_edited = work_cont_exgst_edited.replace(',', '');
        inc_gst = $(".work-set-incgst-"+works_contrator_id).val();
        inc_gst = inc_gst.replace(',', '');
        $.post(baseurl+"works/update_contractor_gst", 
        { 
          works_contrator_id: works_contrator_id,
          ex_gst: work_cont_exgst_edited,
          inc_gst: inc_gst
        }, 
        function(result){
          //inc_gst = numberWithCommas(inc_gst);
         // $(".work-set-incgst-"+works_contrator_id).val(inc_gst);
          if(result == 1){
            if(joinery_work_id == 0){
              $.post(baseurl+"works/update_work",
              {
                work_id: work_id,
                price: work_cont_exgst_edited,
                update_stat: 7
              },
              function(result){
                work_cont_exgst_edited = numberWithCommas(Math.round(work_cont_exgst_edited));
                $('.work-set-price-'+work_id).val(work_cont_exgst_edited);
                //var proj_id = get_project_id();
                if(variation_id !== undefined){
                  proj_id = get_project_id();
                  $.post(baseurl+"works/var_works_total",
                  {
                    proj_id: proj_id,
                    variation_id: variation_id
                  },
                  function(result){
                    var works_totals = result.split( '/' );
                    var t_price = works_totals[0];
                    var t_estimate = works_totals[1];
                    var t_quoted = works_totals[2];
                    $("#var-work-total-price").val(t_price);
                    $("#var-work-total-estimate").val(t_estimate);
                    $("#var-work-total-quoted").val(t_quoted);
                    $.post(baseurl+"works/fetch_gst_rate",{},function(result){ 
                        gst_rate = result; 
                        $.post(baseurl+"works/job_date_entered",
                        {
                          proj_id: proj_id
                        },
                        function(result){
                          job_date = result;
                        });
                    });
                  });
                }else{

                  get_project_totals();
                  // $.post(baseurl+"works/works_total",
                  // {
                  //   proj_id: proj_id
                  // },
                  // function(result){
                  //   var works_totals = result.split( '/' );
                  //   var t_price = works_totals[0];
                  //   var t_estimate = works_totals[1];
                  //   var t_quoted = works_totals[2];
                  //   $("#work-total-price").val(t_price);
                  //   $("#work-total-estimate").val(t_estimate);
                  //   $("#work-total-quoted").val(t_quoted);
                  // });
                }
              });
            }else{
              var joinery_qty = $('.work_joinery_set_qty_'+work_joinery_id).val(); 
              var t_price = +($('.work_joinery_set_qty_'+work_joinery_id).val()) * +(work_cont_exgst_edited);
              $.post(baseurl+"works/update_joinery_selected_subitem_price",
              {
                work_joinery_id: work_joinery_id,
                work_joinery_unit_price: work_cont_exgst_edited,
                t_price: t_price,
                joinery_qty: joinery_qty
              },
              function(result){
                $('.work_joinery_set_unit_price_'+work_joinery_id).val(work_cont_exgst_edited);
                $('.work_joinery_set_total_price_'+work_joinery_id).val(t_price);
                $.post(baseurl+"works/get_joinery_totals", 
                { 
                  work_id: work_id
                }, 
                function(result){
                  var works_joinery_totals = result.split( '|' );
                  var work_joinery_price = works_joinery_totals[0];
                  var work_joinery_estimate = works_joinery_totals[1];
                  var work_joinery_quoted = works_joinery_totals[2];

                  work_joinery_quoted = numberWithCommas(Math.round(work_joinery_quoted));
                  work_joinery_estimate = numberWithCommas(Math.round(work_joinery_estimate));
                  work_joinery_price = numberWithCommas(Math.round(work_joinery_price));

                  $(".work-set-price-"+work_id).val(work_joinery_price);
                  if(work_joinery_estimate > 0){
                    $(".work-set-estimate-"+work_id).val(work_joinery_estimate);
                    $('tbody tr#row-work-'+work_id).find('.work-set-quote-'+work_id).text(work_joinery_quoted);
                  }
                  
                  $('.unset_joinery_comp_badge_'+work_joinery_id).css('display', 'block');
                  //var proj_id = get_project_id();
                  if(variation_id !== undefined){
                    proj_id = get_project_id();
                    $.post(baseurl+"works/var_works_total",
                    {
                      proj_id: proj_id,
                      variation_id: variation_id
                    },
                    function(result){
                      var works_totals = result.split( '/' );
                      var t_price = works_totals[0];
                      var t_estimate = works_totals[1];
                      var t_quoted = works_totals[2];
                      $("#var-work-total-price").val(t_price);
                      $("#var-work-total-estimate").val(t_estimate);
                      $("#var-work-total-quoted").val(t_quoted);
                      $.post(baseurl+"works/fetch_gst_rate",{},function(result){ 
                          gst_rate = result; 
                          $.post(baseurl+"works/job_date_entered",
                          {
                            proj_id: proj_id
                          },
                          function(result){
                            job_date = result;
                          });
                      });
                    });
                  }else{

                    get_project_totals();
                    // $.post(baseurl+"works/works_total",
                    // {
                    //   proj_id: proj_id
                    // },
                    // function(result){
                    //   var works_totals = result.split( '/' );
                    //   var t_price = works_totals[0];
                    //   var t_estimate = works_totals[1];
                    //   var t_quoted = works_totals[2];
                    //   $("#work-total-price").val(t_price);
                    //   $("#work-total-estimate").val(t_estimate);
                    //   $("#work-total-quoted").val(t_quoted);
                    // });
                  }
                });
              });
            }
          }
        });
     // });
    //}
  }
  $("#update_exgst_no").click(function(){
    if(work_cont_exgst_edited !== work_cont_exgst){
      $(".work-set-exgst-"+works_contrator_id).val(work_cont_exgst);
    }
  });
  $("#update_exgst_yes").click(function(){
    // $.post(baseurl+"works/fetch_gst_rate", 
    // { 
    // }, 
    // function(result){
    //   var gst_rate = result;
      gst_rate = +(work_cont_exgst_edited)* (+(gst_rate)/100);
      var inc_gst = +(work_cont_exgst_edited) + +(gst_rate);
      $.post(baseurl+"works/update_contractor_gst", 
      { 
        works_contrator_id: works_contrator_id,
        ex_gst: work_cont_exgst_edited,
        inc_gst: inc_gst
      }, 
      function(result){
        inc_gst = numberWithCommas(inc_gst);
        $(".work-set-incgst-"+works_contrator_id).val(inc_gst);
        if(result == 1){
          $.post(baseurl+"works/update_work",
          {
            work_id: work_id,
            price: work_cont_exgst_edited,
            update_stat: 7
          },
          function(result){
            work_cont_exgst_edited = numberWithCommas(Math.round(work_cont_exgst_edited));
            $('.work-set-price-'+work_id).val(work_cont_exgst_edited);
            //var proj_id = get_project_id();
            $.post(baseurl+"works/works_total",
            {
              proj_id: proj_id
            },
            function(result){
              var works_totals = result.split( '/' );
              var t_price = works_totals[0];
              var t_estimate = works_totals[1];
              var t_quoted = works_totals[2];
              $("#work-total-price").val(t_price);
              $("#work-total-estimate").val(t_estimate);
              $("#work-total-quoted").val(t_quoted);
            });
          });
        }
        
      });
    //});
  });
  //====================================
  window.clk_work_cont_incgst = function(a){
    works_contrator_id = a;
    work_cont_incgst = $(".work-set-incgst-"+a).val();
    work_cont_incgst = work_cont_incgst.replace(',', '');
    // $.post(baseurl+"works/fetch_gst_rate", 
    // { 
    // }, 
    // function(result){
    //   gst_rate = result;
    // });
  }
  window.ku_update_incgst = function(a){
    works_contrator_id = a;
    // $.post(baseurl+"works/fetch_gst_rate", 
    // { 
    // }, 
    // function(result){
    //   gst_rate = result;
      work_cont_incgst_edited = $(".work-set-incgst-"+a).val();
      work_cont_incgst_edited = work_cont_incgst_edited.replace(',', '');
      var percent_gst_rate = +(work_cont_incgst_edited)/ ((+(gst_rate)+100)/+(gst_rate));
      ex_gst = work_cont_incgst_edited - percent_gst_rate;
      ex_gst = ex_gst.toFixed(2);
      //ex_gst = ex_gst.toFixed(2);
      var ex_gst_with_comma = numberWithCommas(ex_gst);
      $(".work-set-exgst-"+works_contrator_id).val(ex_gst_with_comma);
    //});
    
  }

  window.update_incgst = function(a){
    //work_cont_incgst_edited = $(".work-set-incgst-"+a).val();
   // work_cont_incgst_edited = work_cont_incgst_edited.replace(',', '');
   // if(work_cont_incgst_edited !== work_cont_incgst){
      //$('.modal').modal('hide');
      //$('#work_cont_inc_update_conf').modal('show');
      //$.post(baseurl+"works/fetch_gst_rate", 
     // { 
      //}, 
    //  function(result){
     //   var gst_rate = result;

     //   gst_rate = +(work_cont_incgst_edited)/ ((+(gst_rate)+100)/+(gst_rate));
   //     var ex_gst = work_cont_incgst_edited - gst_rate;
    //    ex_gst = ex_gst.toFixed(2);
        ex_gst = $(".work-set-exgst-"+works_contrator_id).val();
        ex_gst = ex_gst.replace(',', '');
        work_cont_incgst_edited = $(".work-set-incgst-"+works_contrator_id).val();
        work_cont_incgst_edited = work_cont_incgst_edited.replace(',', '');
        $.post(baseurl+"works/update_contractor_gst", 
        { 
          works_contrator_id: works_contrator_id,
          ex_gst: ex_gst,
          inc_gst: work_cont_incgst_edited
        }, 
        function(result){
          if(result == 1){
            if(joinery_work_id == 0){
              $.post(baseurl+"works/update_work",
              {
                work_id: work_id,
                price: ex_gst,
                update_stat: 7
              },
              function(result){
                ex_gst = numberWithCommas(Math.round(ex_gst));
                $('.work-set-price-'+work_id).val(ex_gst);

                //var proj_id = get_project_id();
                if(variation_id !== undefined){
                  proj_id = get_project_id();
                  $.post(baseurl+"works/var_works_total",
                  {
                    proj_id: proj_id,
                    variation_id: variation_id
                  },
                  function(result){
                    var works_totals = result.split( '/' );
                    var t_price = works_totals[0];
                    var t_estimate = works_totals[1];
                    var t_quoted = works_totals[2];
                    $("#var-work-total-price").val(t_price);
                    $("#var-work-total-estimate").val(t_estimate);
                    $("#var-work-total-quoted").val(t_quoted);
                    $.post(baseurl+"works/fetch_gst_rate",{},function(result){ 
                        gst_rate = result; 
                        $.post(baseurl+"works/job_date_entered",
                        {
                          proj_id: proj_id
                        },
                        function(result){
                          job_date = result;
                        });
                    });
                  });
                }else{

                  get_project_totals();
                  // $.post(baseurl+"works/works_total",
                  // {
                  //   proj_id: proj_id
                  // },
                  // function(result){
                  //   var works_totals = result.split( '/' );
                  //   var t_price = works_totals[0];
                  //   var t_estimate = works_totals[1];
                  //   var t_quoted = works_totals[2];
                  //   $("#work-total-price").val(t_price);
                  //   $("#work-total-estimate").val(t_estimate);
                  //   $("#work-total-quoted").val(t_quoted);
                  // });
                }
              });
            }else{
              var joinery_qty = $('.work_joinery_set_qty_'+work_joinery_id).val(); 
              var t_price = +($('.work_joinery_set_qty_'+work_joinery_id).val()) * +(ex_gst);
              $.post(baseurl+"works/update_joinery_selected_subitem_price",
              {
                work_joinery_id: work_joinery_id,
                work_joinery_unit_price: ex_gst,
                t_price: t_price,
                joinery_qty: joinery_qty
              },
              function(result){
                $('.work_joinery_set_unit_price_'+work_joinery_id).val(ex_gst);
                $('.work_joinery_set_total_price_'+work_joinery_id).val(t_price);
                $.post(baseurl+"works/get_joinery_totals", 
                { 
                  work_id: work_id
                }, 
                function(result){
                  var works_joinery_totals = result.split( '|' );
                  var work_joinery_price = works_joinery_totals[0];
                  var work_joinery_estimate = works_joinery_totals[1];
                  var work_joinery_quoted = works_joinery_totals[2];

                  work_joinery_quoted = numberWithCommas(Math.round(work_joinery_quoted));
                  work_joinery_estimate = numberWithCommas(Math.round(work_joinery_estimate));
                  work_joinery_price = numberWithCommas(Math.round(work_joinery_price));

                  $(".work-set-price-"+work_id).val(work_joinery_price);
                  if(work_joinery_estimate > 0){
                    $(".work-set-estimate-"+work_id).val(work_joinery_estimate);
                    $('tbody tr#row-work-'+work_id).find('.work-set-quote-'+work_id).text(work_joinery_quoted);
                  }
                  $('.unset_joinery_comp_badge_'+work_joinery_id).css('display', 'block');
                  //var proj_id = get_project_id();
                  if(variation_id !== undefined){
                    proj_id = get_project_id();
                    $.post(baseurl+"works/var_works_total",
                    {
                      proj_id: proj_id,
                      variation_id: variation_id
                    },
                    function(result){
                      var works_totals = result.split( '/' );
                      var t_price = works_totals[0];
                      var t_estimate = works_totals[1];
                      var t_quoted = works_totals[2];
                      $("#var-work-total-price").val(t_price);
                      $("#var-work-total-estimate").val(t_estimate);
                      $("#var-work-total-quoted").val(t_quoted);
                      $.post(baseurl+"works/fetch_gst_rate",{},function(result){ 
                          gst_rate = result; 
                          $.post(baseurl+"works/job_date_entered",
                          {
                            proj_id: proj_id
                          },
                          function(result){
                            job_date = result;
                          });
                      });
                    });
                  }else{
                    get_project_totals();
                    // $.post(baseurl+"works/works_total",
                    // {
                    //   proj_id: proj_id
                    // },
                    // function(result){
                    //   var works_totals = result.split( '/' );
                    //   var t_price = works_totals[0];
                    //   var t_estimate = works_totals[1];
                    //   var t_quoted = works_totals[2];
                    //   $("#work-total-price").val(t_price);
                    //   $("#work-total-estimate").val(t_estimate);
                    //   $("#work-total-quoted").val(t_quoted);
                    // });
                  }
                });
              });
            }
            
          }
          //ex_gst = numberWithCommas(ex_gst);
          //$(".work-set-exgst-"+works_contrator_id).val(ex_gst);
        });
      //});
    //}
  }
  $("#update_incgst_no").click(function(){
    if(work_cont_incgst_edited !== work_cont_incgst){
      $(".work-set-incgst-"+works_contrator_id).val(work_cont_incgst);
    }
  });
  $("#update_incgst_yes").click(function(){
    // $.post(baseurl+"works/fetch_gst_rate", 
    // { 
    // }, 
    // function(result){
    //   var gst_rate = result;

      gst_rate = +(work_cont_incgst_edited)/ ((+(gst_rate)+100)/+(gst_rate));
      var ex_gst = work_cont_incgst_edited - gst_rate;
      ex_gst = ex_gst.toFixed(2);

      $.post(baseurl+"works/update_contractor_gst", 
      { 
        works_contrator_id: works_contrator_id,
        ex_gst: ex_gst,
        inc_gst: work_cont_incgst_edited
      }, 
      function(result){
        if(result == 1){
          $.post(baseurl+"works/update_work",
          {
            work_id: work_id,
            price: ex_gst,
            update_stat: 7
          },
          function(result){
            ex_gst = numberWithCommas(Math.round(ex_gst));
            $('.work-set-price-'+work_id).val(ex_gst);

            //var proj_id = get_project_id();
            $.post(baseurl+"works/works_total",
            {
              proj_id: proj_id
            },
            function(result){
              var works_totals = result.split( '/' );
              var t_price = works_totals[0];
              var t_estimate = works_totals[1];
              var t_quoted = works_totals[2];
              $("#work-total-price").val(t_price);
              $("#work-total-estimate").val(t_estimate);
              $("#work-total-quoted").val(t_quoted);
            });
          });
        }
        ex_gst = numberWithCommas(ex_gst);
        $(".work-set-exgst-"+works_contrator_id).val(ex_gst);
      });
    //});
  });
  $("#inc_gst").keyup(function(){
    var inc_gst = $("#inc_gst").val();
    inc_gst = inc_gst.replace(",", "");
    // $.post(baseurl+"works/fetch_gst_rate", 
    // { 
    // }, 
    // function(result){
    //   var gst_rate = result;
      gst_rate = +(inc_gst)/ ((+(gst_rate)+100)/+(gst_rate));
      var ex_gst = inc_gst - gst_rate;
      ex_gst = ex_gst.toFixed(2);
      ex_gst = numberWithCommas(ex_gst);
      $("#price_ex_gst").val(ex_gst);
    //});
  })
  
  $("#price_ex_gst").keyup(function(){
    var ex_gst = $("#price_ex_gst").val();
    ex_gst = ex_gst.replace(",", "");
    // $.post(baseurl+"works/fetch_gst_rate", 
    // { 
    // }, 
    // function(result){
    //   var gst_rate = result;
      gst_rate = +(ex_gst)* (+(gst_rate)/100);
      var inc_gst = +(ex_gst) + +(gst_rate);
      inc_gst = numberWithCommas(inc_gst);
      $("#inc_gst").val(inc_gst);
    //});
  });

  $("#cont_saving_button").hide();
  $("#save_contractor").click(function(){
    var date_entered = $("#contractor_date_entered").val();
    var result = $("#work_contructor_name").val();
    var comp_id = result.split("|");
    comp_id = comp_id[1];
    var  contact_person_id= $("#contact_person").val();

    var var_acceptance_date = $("#variation_acceptance_date").val();
    if(date_entered == "" || result == "" || contact_person_id == ""){
      alert("Please fill in required fields");
    }else{
      //var proj_id = get_project_id();
      if(contact_person_id == "" || contact_person_id == 0 || contact_person_id == undefined){
        alert("Please Select Contact person!");
      }else{
        $("#save_contractor").hide();
        $("#cont_saving_button").show();
        if(joinery_work_id == 0){

          $.post(baseurl+"works/insert_contractor", 
          { 
            var_acceptance_date:var_acceptance_date,
            proj_id: proj_id,
            work_id: work_id,
            date_added: date_entered,
            comp_id: comp_id,
            contact_person_id: contact_person_id
          }, 
          function(result){
            setTimeout(function(){
              $("#save_contractor").show();
              $("#cont_saving_button").hide();
              $("#work_contractors").html(result);
              $(".modal").modal("hide");
            }, 5000);  // on 5 second
          });
        }else{
          $.post(baseurl+"works/insert_contractor", 
          { 
            var_acceptance_date: var_acceptance_date,
            proj_id: proj_id,
            work_id: joinery_work_id,
            date_added: date_entered,
            comp_id: comp_id,
            contact_person_id: contact_person_id
          }, 
          function(result){
            setTimeout(function(){
              $("#save_contractor").show();
              $("#cont_saving_button").hide();
              $("#work_contractors").html(result);
              $(".modal").modal("hide");
            }, 5000);  // on 5 second
          });
        }
      }
      
    }
  });

  $("#cont_saving_var_button").hide();
  $("#save_var_contractor").click(function(){
    var date_entered = $("#contractor_date_entered").val();
    var result = $("#var_work_contructor_name").val();
    var comp_id = result.split("|");
    comp_id = comp_id[1];
    var  contact_person_id= $("#contact_person").val();

    var var_acceptance_date = $("#variation_acceptance_date").val();
    if(date_entered == "" || result == "" || contact_person_id == ""){
      alert("Please fill in required fields");
    }else{
      $("#save_var_contractor").hide();
      $("#cont_saving_var_button").show();
      //var proj_id = get_project_id();
      if(joinery_work_id == 0){
        $.post(baseurl+"works/var_insert_contractor", 
        { 
          var_acceptance_date: var_acceptance_date,
          proj_id: proj_id,
          work_id: work_id,
          date_added: date_entered,
          comp_id: comp_id,
          contact_person_id: contact_person_id
        }, 
        function(result){
          setTimeout(function(){
            $("#save_var_contractor").show();
            $("#cont_saving_var_button").hide();
            $("#var_work_contractors").html(result);
            $(".modal").modal("hide");
          }, 5000);  // on 5 second
        });
      }else{
        $.post(baseurl+"works/var_insert_contractor", 
        { 
          var_acceptance_date: var_acceptance_date,
          proj_id: proj_id,
          work_id: joinery_work_id,
          date_added: date_entered,
          comp_id: comp_id,
          contact_person_id: contact_person_id
        }, 
        function(result){
          setTimeout(function(){
            $("#save_var_contractor").show();
            $("#cont_saving_var_button").hide();
            $("#var_work_contractors").html(result);
            $(".modal").modal("hide");
          }, 5000);  // on 5 second
        });
      }
    }
  });

  window.selcontractor = function(a){
    $("#save_contractor").hide();
    $("#create_cqr").show();
    $("#update_contractor").show();
    $("#delete_contractor").show();
    
    work_contractor_id = a;
    $.post(baseurl+"works/select_work_contractor", 
    { 
      work_id: work_id,
      work_contractor_id: work_contractor_id
    }, 
    function(result){
      var output = result.split("|");
      var date_entered = output[0];
      var contractor_name = output[1]+"|"+output[2];
      var ex_gst = output[3];
      var inc_gst = output[4];
      work_is_selected = output[5];
      var is_reconciled = output[6];
      var cont_person_id = output[7];
      if(is_reconciled == 1){
        $("#delete_contractor").hide();
      }
      $("#contractor_date_entered").val(date_entered);
      $("#work_contructor_name").val(contractor_name);
      $('#s2id_work_contructor_name span.select2-chosen').text(output[1]);

      setTimeout(function(){

       $('select#contact_person').attr('disabled',false).val(cont_person_id);

      },1000);
      
      //$("#price_ex_gst").val(ex_gst);
      //$("#inc_gst").val(inc_gst);
      var myVal = output[1]+"|"+output[2];
      var controller_method = 'projects/find_contact_person';

      //$('select#contact_person').empty();
      var classLocation = 'select#contact_person';
      ajax_data(myVal,controller_method,classLocation);
      //var proj_id = get_project_id();
      var cont_works_id = $("#cont_cpono").text();
      proj_id = get_project_id();
      var var_acceptance_date = $("#variation_acceptance_date").val();
      $.post(baseurl+"works/display_work_contractor", 
      {
        var_acceptance_date: var_acceptance_date,
        proj_id: proj_id,
        work_id: cont_works_id
      },function(result){
        $("#work_contractors").html(result);
      });
    });
  }

  window.sel_var_contractor = function(a){
    $("#save_var_contractor").hide();
    $("#create_var_cqr").show();
    $("#update_var_contractor").show();
    $("#delete_var_contractor").show();
    
    work_contractor_id = a;
    $.post(baseurl+"works/select_work_contractor", 
    { 
      work_id: work_id,
      work_contractor_id: work_contractor_id
    }, 
    function(result){
      var output = result.split("|");
      var date_entered = output[0];
      var contractor_name = output[1]+"|"+output[2];
      var ex_gst = output[3];
      var inc_gst = output[4];
      work_is_selected = output[5];
      $("#contractor_date_entered").val(date_entered);
      $("#var_work_contructor_name").val(contractor_name);
      $('#s2id_var_work_contructor_name span.select2-chosen').text(output[1]);

      setTimeout(function(){

       $('select#contact_person').attr('disabled',false).val(cont_person_id);

      },1000);
      
      //$("#price_ex_gst").val(ex_gst);
      //$("#inc_gst").val(inc_gst);
      var myVal = output[1]+"|"+output[2];
      var controller_method = 'projects/find_contact_person';

      //$('select#contact_person').empty();
      var classLocation = 'select#contact_person';
      ajax_data(myVal,controller_method,classLocation);
      //var proj_id = get_project_id();
      var var_acceptance_date = $("#variation_acceptance_date").val();
      $.post(baseurl+"works/display_var_work_contractor", 
      {
        var_acceptance_date: var_acceptance_date,
        proj_id: proj_id,
        work_id: work_id
      },function(result){
        $("#var_work_contractors").html(result);
      });
    });
  }

  $("#update_contractor").click(function(){
    var date_entered = $("#contractor_date_entered").val();
    var result = $("#work_contructor_name").val();
    var comp_id = result.split("|");
   
    comp_id = comp_id[1];
    var  contact_person_id= $("#contact_person").val();
   
    var var_acceptance_date = $("#variation_acceptance_date").val();
   
    if(date_entered == "" || result == "" || contact_person_id == ""){
      alert("Please fill in required fields");
    }else{
      //var proj_id = get_project_id();
      if(contact_person_id == "" || contact_person_id == 0 || contact_person_id == undefined){
        alert("Please Select Contact person!");
      }else{
    /*var inc_gst = $("#inc_gst").val();
    var ex_gst = $("#price_ex_gst").val();
    inc_gst = inc_gst.replace(",", "");
    ex_gst = ex_gst.replace(",", "");*/
    //var proj_id = get_project_id();
        if(joinery_work_id == 0){
          $.post(baseurl+"works/update_contractor", 
          { 
            var_acceptance_date: var_acceptance_date,
            proj_id: proj_id,
            work_contractor_id: work_contractor_id,
            work_id: work_id,
            date_added: date_entered,
            comp_id: comp_id,
            contact_person_id: contact_person_id,
            work_is_selected: work_is_selected
            // inc_gst: inc_gst,
            // ex_gst: ex_gst
          }, 
          function(result){
            $("#work_contractors").html(result);
            if(work_is_selected == 1){
              var comp_name_work = $('tr.cont-'+work_contractor_id).find('.item-cont-'+work_contractor_id+'-comp a').text();
              $('tbody tr#row-work-'+work_id).find('.work-set-comp-'+work_id+' a').text(comp_name_work);
            //   $.post(baseurl+"works/update_work",
            //   {
            //     proj_id: proj_id,
            //     work_id: work_id,
            //     contact_person_id: contact_person_id,
            //     update_stat: 9
            //   },
            //   function(result){
            //     // ex_gst = numberWithCommas(Math.round(ex_gst));
            //     // $(".work-set-price-"+work_id).val(ex_gst);
            //   });
            }
          });
        }else{
          $.post(baseurl+"works/update_contractor", 
          { 
            var_acceptance_date: var_acceptance_date,
            proj_id: proj_id,
            work_contractor_id: work_contractor_id,
            work_id: joinery_work_id,
            date_added: date_entered,
            comp_id: comp_id,
            contact_person_id: contact_person_id,
            work_is_selected: work_is_selected
            // inc_gst: inc_gst,
            // ex_gst: ex_gst
          }, 
          function(result){
            $("#work_contractors").html(result);
            if(work_is_selected == 1){
              var comp_name_work = $('tr.cont-'+work_contractor_id).find('.item-cont-'+work_contractor_id+'-comp a').text();
              $('tbody tr#row-work-'+work_id).find('.work-set-comp-'+work_id+' a').text(comp_name_work);
              // $.post(baseurl+"works/update_work",
              // {
              //   work_id: work_id,
              //   contact_person_id: contact_person_id,
              //   update_stat: 9
              // },
              // function(result){
              //   // ex_gst = numberWithCommas(Math.round(ex_gst));
              //   // $(".work-set-price-"+work_id).val(ex_gst);
              // });
            }
          });
        }
      }
    }
    
  });

  $("#update_var_contractor").click(function(){
    var date_entered = $("#contractor_date_entered").val();
    var result = $("#var_work_contructor_name").val();
    var comp_id = result.split("|");
   
    comp_id = comp_id[1];
    var  contact_person_id= $("#contact_person").val();
   
    var var_acceptance_date = $("#variation_acceptance_date").val();
    /*var inc_gst = $("#inc_gst").val();
    var ex_gst = $("#price_ex_gst").val();
    inc_gst = inc_gst.replace(",", "");
    ex_gst = ex_gst.replace(",", "");*/
    //var proj_id = get_project_id();
    if(joinery_work_id == 0){
      $.post(baseurl+"works/update_var_contractor", 
      { 
        var_acceptance_date: var_acceptance_date,
        proj_id: proj_id,
        work_contractor_id: work_contractor_id,
        work_id: work_id,
        date_added: date_entered,
        comp_id: comp_id,
        contact_person_id: contact_person_id,
        work_is_selected: work_is_selected
        // inc_gst: inc_gst,
        // ex_gst: ex_gst
      }, 
      function(result){
        $("#var_work_contractors").html(result);
        if(work_is_selected == 1){
          var comp_name_work = $('tr.cont-'+work_contractor_id).find('.item-cont-'+work_contractor_id+'-comp a').text();
          $('tbody tr#row-work-'+work_id).find('.work-set-comp-'+work_id+' a').text(comp_name_work);
          // $.post(baseurl+"works/update_work",
          // {
          //   work_id: work_id,
          //   price: ex_gst,
          //   update_stat: 7
          // },
          // function(result){
          //   ex_gst = numberWithCommas(Math.round(ex_gst));
          //   $(".work-set-price-"+work_id).val(ex_gst);
          // });
        }
      });
    }else{
      $.post(baseurl+"works/update_var_contractor", 
      { 
        var_acceptance_date: var_acceptance_date,
        proj_id: proj_id,
        work_contractor_id: work_contractor_id,
        work_id: joinery_work_id,
        date_added: date_entered,
        comp_id: comp_id,
        contact_person_id: contact_person_id,
        work_is_selected: work_is_selected
        // inc_gst: inc_gst,
        // ex_gst: ex_gst
      }, 
      function(result){
        $("#var_work_contractors").html(result);
        if(work_is_selected == 1){
          var comp_name_work = $('tr.cont-'+work_contractor_id).find('.item-cont-'+work_contractor_id+'-comp a').text();
          $('tbody tr#row-work-'+work_id).find('.work-set-comp-'+work_id+' a').text(comp_name_work);
          // $.post(baseurl+"works/update_work",
          // {
          //   work_id: work_id,
          //   price: ex_gst,
          //   update_stat: 7
          // },
          // function(result){
          //   ex_gst = numberWithCommas(Math.round(ex_gst));
          //   $(".work-set-price-"+work_id).val(ex_gst);
          // });
        }
      });
    }
    
  });

  $("#btn_work_con_del_conf_yes").click(function(){
    //var proj_id = get_project_id();
    var var_acceptance_date = $("#variation_acceptance_date").val();
    if(joinery_work_id == 0){
      
      $.post(baseurl+"works/delete_contractor", 
      { 
        var_acceptance_date: var_acceptance_date,
        work_contractor_id: work_contractor_id,
        work_id: work_id,
        proj_id: proj_id
      }, 
      function(result){
        $("#work_contractors").html(result);
        $('.modal').modal('hide');
      });
    }else{
      $.post(baseurl+"works/delete_contractor", 
      { 
        var_acceptance_date: var_acceptance_date,
        proj_id: proj_id,
        work_contractor_id: work_contractor_id,
        work_id: joinery_work_id,
      }, 
      function(result){
        $("#work_contractors").html(result);
        $('.modal').modal('hide');
      });
    }
    
  });

  $("#btn_var_work_con_del_conf_yes").click(function(){
    //var proj_id = get_project_id();
    var var_acceptance_date = $("#variation_acceptance_date").val();
    if(joinery_work_id == 0){
      
      $.post(baseurl+"works/delete_contractor", 
      { 
        var_acceptance_date: var_acceptance_date,
        work_contractor_id: work_contractor_id,
        work_id: work_id,
        proj_id: proj_id
      }, 
      function(result){
        $("#var_work_contractors").html(result);
        $('.modal').modal('hide');
      });
    }else{
      $.post(baseurl+"works/delete_var_contractor", 
      { 
        var_acceptance_date: var_acceptance_date,
        proj_id: proj_id,
        work_contractor_id: work_contractor_id,
        work_id: joinery_work_id,
      }, 
      function(result){
        $("#var_work_contractors").html(result);
        $('.modal').modal('hide');
      });
    }
    
  });



  window.sel_work_con = function(a){
    selected_work_contractor_id = a;
    var exprice = $(".work-set-exgst-"+selected_work_contractor_id).val();
    var comp_name_work = $('tr.cont-'+selected_work_contractor_id).find('.item-cont-'+selected_work_contractor_id+'-comp a').text();

    var var_acceptance_date = $("#variation_acceptance_date").val();
    $.post(baseurl+"works/is_joinery", 
    { 
      work_id: work_id,
    }, 
    function(result){
      proj_id = get_project_id();
      if(result == 53){
        $.post(baseurl+"works/have_sub_item", 
        { 
          work_id: work_id,
        }, 
        function(result){
          if(result == 0){
            var orgPrice = $('tbody tr#row-work-'+work_id).find('.work-set-price-'+work_id).text();
            var exprice = $(".work-set-exgst-"+selected_work_contractor_id).val();
            exprice = exprice.replace(',','');
            exprice = numberWithCommas(Math.round(exprice));
            $('.work-set-price-'+work_id).val(exprice);
           // $('tbody tr#row-work-'+work_id).find('.work-set-comp-'+work_id+' a').css('display', 'block');
            $('tbody tr#row-work-'+work_id).find('.work-set-comp-'+work_id+' a').text(comp_name_work);
            $('.add_comp_badge_'+work_id).hide();
            $('.unset_comp_badge_'+work_id).css('display', 'block');
            $.post(baseurl+"works/select_contractor", 
            { 
              var_acceptance_date: var_acceptance_date,
              selected_work_contractor_id: selected_work_contractor_id,
              work_id: work_id,
              proj_id: proj_id
            }, 
            function(result){
              $("#work_contractors").html(result);
              
              get_project_totals();
              // $.post(baseurl+"works/works_total",
              // {
              //   proj_id: proj_id
              // },
              // function(result){
              //   var works_totals = result.split( '/' );
              //   var t_price = works_totals[0];
              //   var t_estimate = works_totals[1];
              //   var t_quoted = works_totals[2];
              //   $("#work-total-price").val(t_price);
              //   $("#work-total-estimate").val(t_estimate);
              //   $("#work-total-quoted").val(t_quoted);
              // });
            });
          }else{
            if(work_joinery_id == 0){
              $('.modal').modal('hide');
              //$("#set_work_joinery_contractor_conf").modal("show");
              var exprice = $(".work-set-exgst-"+selected_work_contractor_id).val();
              exprice = exprice.replace(',','');
              //var comp_name_work = $('tr.cont-'+selected_work_contractor_id).find('.item-cont-'+selected_work_contractor_id+'-comp a').text();
              var orgPrice = $('tbody tr#row-work-'+work_id).find('.work-set-price-'+work_id).text();
              exprice = numberWithCommas(Math.round(exprice));
              $('.work-set-price-'+work_id).val(exprice);
              $('tbody tr#row-work-'+work_id).find('.work-set-comp-'+work_id+' a').text(comp_name_work);
              $('.add_comp_badge_'+work_id).hide();
              $('.unset_comp_badge_'+work_id).css('display', 'block');
              //var proj_id = get_project_id();
              $.post(baseurl+"works/select_contractor", 
              { 
                var_acceptance_date: var_acceptance_date,
                proj_id: proj_id,
                selected_work_contractor_id: selected_work_contractor_id,
                work_id: work_id,
                all: 1
              }, 
              function(result){
                $("#work_contractors").html(result);
                get_project_totals();
                // $.post(baseurl+"works/works_total",
                // {
                //   proj_id: proj_id
                // },
                // function(result){
                //   var works_totals = result.split( '/' );
                //   var t_price = works_totals[0];
                //   var t_estimate = works_totals[1];
                //   var t_quoted = works_totals[2];
                //   $("#work-total-price").val(t_price);
                //   $("#work-total-estimate").val(t_estimate);
                //   $("#work-total-quoted").val(t_quoted);
                  $.post(baseurl+"works/view_works_list", 
                  { 
                  }, 
                  function(result){
                      window.open(baseurl+"projects/view/"+proj_id+"/?show=1", '_self', true);
                  })
                //});
              });

            }else{
              var work_contractor = $('tbody tr#row-work-'+work_id).find('.work-set-comp-'+work_id+' a').text();
              if(work_contractor == ""){
                var qty = $(".work_joinery_set_qty_"+work_joinery_id).val();
                exprice = $(".work-set-exgst-"+selected_work_contractor_id).val();
                exprice = exprice.replace(',','');
                var tj_price = exprice * qty;
                var var_acceptance_date = $("#variation_acceptance_date").val();
                $.post(baseurl+"works/set_joinery_subitem_contractor",
                {
                  var_acceptance_date: var_acceptance_date,
                  proj_id: proj_id,
                  work_joinery_id: work_joinery_id,
                  joinery_work_id: joinery_work_id,
                  work_id: work_id,
                  qty: qty,
                  unit_price: exprice,
                  t_price: tj_price,
                  company_id: selected_work_contractor_id
                },
                function(result){
                  $("#work_contractors").html(result);
                  $(".add_comp_joinery_badge_"+work_joinery_id).hide();
                  $('tbody tr#row-work-joinery-'+work_joinery_id).find('.work_joinery_set_comp_'+work_joinery_id+' a').text(comp_name_work);
                  $('.work_joinery_set_unit_price_'+work_joinery_id).val(exprice);
                  $('.work_joinery_set_total_price_'+work_joinery_id).val(tj_price);

                  $.post(baseurl+"works/get_joinery_totals", 
                  { 
                    work_id: work_id
                  }, 
                  function(result){
                    var works_joinery_totals = result.split( '|' );
                    var work_joinery_price = works_joinery_totals[0];
                    var work_joinery_estimate = works_joinery_totals[1];
                    var work_joinery_quoted = works_joinery_totals[2];

                    work_joinery_quoted = numberWithCommas(Math.round(work_joinery_quoted));
                    work_joinery_estimate = numberWithCommas(Math.round(work_joinery_estimate));
                    work_joinery_price = numberWithCommas(Math.round(work_joinery_price));

                    $(".work-set-price-"+work_id).val(work_joinery_price);
                    if(work_joinery_estimate > 0 ){
                      $(".work-set-estimate-"+work_id).val(work_joinery_estimate);
                      $('tbody tr#row-work-'+work_id).find('.work-set-quote-'+work_id).text(work_joinery_quoted);
                    }
                  
                    $('.unset_joinery_comp_badge_'+work_joinery_id).css('display', 'block');
                    //var proj_id = get_project_id();

                    get_project_totals();
                    // $.post(baseurl+"works/works_total",
                    // {
                    //   proj_id: proj_id
                    // },
                    // function(result){
                    //   var works_totals = result.split( '/' );
                    //   var t_price = works_totals[0];
                    //   var t_estimate = works_totals[1];
                    //   var t_quoted = works_totals[2];
                    //   $("#work-total-price").val(t_price);
                    //   $("#work-total-estimate").val(t_estimate);
                    //   $("#work-total-quoted").val(t_quoted);
                    // });
                  });
                });
              }else{
                $.post(baseurl+"works/unset_work_contractor",
                {
                  work_id: work_id
                },
                function(result){
                  //=============
                  var qty = $(".work_joinery_set_qty_"+work_joinery_id).val();
                  exprice = $(".work-set-exgst-"+selected_work_contractor_id).val();
                  exprice = exprice.replace(',','');
                  var tj_price = exprice * qty;
                  $.post(baseurl+"works/set_joinery_subitem_contractor",
                  {
                    work_joinery_id: work_joinery_id,
                    work_id: work_id,
                    qty: qty,
                    unit_price: exprice,
                    t_price: tj_price,
                    company_id: selected_work_contractor_id
                  },
                  function(result){
                    $(".add_comp_joinery_badge_"+work_joinery_id).hide();
                    $('tbody tr#row-work-joinery-'+work_joinery_id).find('.work_joinery_set_comp_'+work_joinery_id+' a').text(comp_name_work);
                    $('.work_joinery_set_unit_price_'+work_joinery_id).val(exprice);
                    $('.work_joinery_set_total_price_'+work_joinery_id).val(tj_price);

                    $.post(baseurl+"works/get_joinery_totals", 
                    { 
                      work_id: work_id
                    }, 
                    function(result){
                      var works_joinery_totals = result.split( '|' );
                      var work_joinery_price = works_joinery_totals[0];
                      var work_joinery_estimate = works_joinery_totals[1];
                      var work_joinery_quoted = works_joinery_totals[2];

                      work_joinery_quoted = numberWithCommas(Math.round(work_joinery_quoted));
                      work_joinery_estimate = numberWithCommas(Math.round(work_joinery_estimate));
                      work_joinery_price = numberWithCommas(Math.round(work_joinery_price));

                      $(".work-set-price-"+work_id).val(work_joinery_price);
                      $(".work-set-estimate-"+work_id).val(work_joinery_estimate);
                      $('tbody tr#row-work-'+work_id).find('.work-set-quote-'+work_id).text(work_joinery_quoted);

                      get_project_totals();
                      //var proj_id = get_project_id();
                      // $.post(baseurl+"works/works_total",
                      // {
                      //   proj_id: proj_id
                      // },
                      // function(result){
                      //   var works_totals = result.split( '/' );
                      //   var t_price = works_totals[0];
                      //   var t_estimate = works_totals[1];
                      //   var t_quoted = works_totals[2];
                      //   $("#work-total-price").val(t_price);
                      //   $("#work-total-estimate").val(t_estimate);
                      //   $("#work-total-quoted").val(t_quoted);

                        //$('tbody tr#row-work-'+work_id).find('.work-set-comp-'+work_id+' a').text("");
                        $('tbody tr#row-work-'+work_id).find('.work-set-comp-'+work_id+' a').hide();

                        //$('.unset_comp_badge_'+work_id).css('visibility', 'hidden');
                        $(".add_comp_badge_"+work_id).css('display', 'block');
                        $(".unset_comp_badge_"+work_id).hide();
                        $.post(baseurl+"works/view_works_list", 
                        { 
                        }, 
                        function(result){
                            window.open(baseurl+"projects/view/"+proj_id+"/?show=1", '_self', true); 
                        })
                     // });
                    });
                  });
                  //=============
                 
                });
              }
            }
          }
        });
      }else{
        var orgPrice = $('tbody tr#row-work-'+work_id).find('.work-set-price-'+work_id).text();
        exprice = exprice.replace(',','');
        exprice = numberWithCommas(Math.round(exprice));
        $('.work-set-price-'+work_id).val(exprice);
        $('tbody tr#row-work-'+work_id).find('.work-set-comp-'+work_id+' a').text(comp_name_work);
        $('.add_comp_badge_'+work_id).hide();
        $('.unset_comp_badge_'+work_id).css('display', 'block');

        $.post(baseurl+"works/select_contractor", 
        { 
          var_acceptance_date: var_acceptance_date,
          selected_work_contractor_id: selected_work_contractor_id,
          work_id: work_id,
          proj_id: proj_id
        }, 
        function(result){
          $("#work_contractors").html(result);

          get_project_totals();
          // //var proj_id = get_project_id();
          // $.post(baseurl+"works/works_total",
          // {
          //   proj_id: proj_id
          // },
          // function(result){
          //   var works_totals = result.split( '/' );
          //   var t_price = works_totals[0];
          //   var t_estimate = works_totals[1];
          //   var t_quoted = works_totals[2];
          //   $("#work-total-price").val(t_price);
          //   $("#work-total-estimate").val(t_estimate);
          //   $("#work-total-quoted").val(t_quoted);
          // });
        });
      }

    });
  }

  window.sel_var_work_con = function(a){
    selected_work_contractor_id = a;
    var exprice = $(".work-set-exgst-"+selected_work_contractor_id).val();
    var comp_name_work = $('tr.cont-'+selected_work_contractor_id).find('.item-cont-'+selected_work_contractor_id+'-comp a').text();

    var var_acceptance_date = $("#variation_acceptance_date").val();
    $.post(baseurl+"works/is_joinery", 
    { 
      work_id: work_id,
    }, 
    function(result){
      proj_id = get_project_id();
      if(result == 53){
        $.post(baseurl+"works/have_sub_item", 
        { 
          work_id: work_id,
        }, 
        function(result){
          if(result == 0){
            var orgPrice = $('tbody tr#row-work-'+work_id).find('.work-set-price-'+work_id).text();
            var exprice = $(".work-set-exgst-"+selected_work_contractor_id).val();
            exprice = exprice.replace(',','');
            exprice = numberWithCommas(Math.round(exprice));
            $('.work-set-price-'+work_id).val(exprice);
           // $('tbody tr#row-work-'+work_id).find('.work-set-comp-'+work_id+' a').css('display', 'block');
            $('tbody tr#row-work-'+work_id).find('.work-set-comp-'+work_id+' a').text(comp_name_work);
            $('.add_comp_badge_'+work_id).hide();
            $('.unset_comp_badge_'+work_id).css('display', 'block');
            $.post(baseurl+"works/select_var_contractor", 
            { 
              var_acceptance_date: var_acceptance_date,
              selected_work_contractor_id: selected_work_contractor_id,
              work_id: work_id,
              proj_id: proj_id
            }, 
            function(result){
              $("#var_work_contractors").html(result);
              
              get_project_totals();
              // $.post(baseurl+"works/works_total",
              // {
              //   proj_id: proj_id
              // },
              // function(result){
              //   var works_totals = result.split( '/' );
              //   var t_price = works_totals[0];
              //   var t_estimate = works_totals[1];
              //   var t_quoted = works_totals[2];
              //   $("#work-total-price").val(t_price);
              //   $("#work-total-estimate").val(t_estimate);
              //   $("#work-total-quoted").val(t_quoted);
              // });
            });
          }else{
            if(work_joinery_id == 0){
              $('.modal').modal('hide');
              //$("#set_work_joinery_contractor_conf").modal("show");
              var exprice = $(".work-set-exgst-"+selected_work_contractor_id).val();
              exprice = exprice.replace(',','');
              //var comp_name_work = $('tr.cont-'+selected_work_contractor_id).find('.item-cont-'+selected_work_contractor_id+'-comp a').text();
              var orgPrice = $('tbody tr#row-work-'+work_id).find('.work-set-price-'+work_id).text();
              exprice = numberWithCommas(Math.round(exprice));
              $('.work-set-price-'+work_id).val(exprice);
              $('tbody tr#row-work-'+work_id).find('.work-set-comp-'+work_id+' a').text(comp_name_work);
              $('.add_comp_badge_'+work_id).hide();
              $('.unset_comp_badge_'+work_id).css('display', 'block');
              //var proj_id = get_project_id();
              $.post(baseurl+"works/select_var_contractor", 
              { 
                var_acceptance_date: var_acceptance_date,
                proj_id: proj_id,
                selected_work_contractor_id: selected_work_contractor_id,
                work_id: work_id,
                all: 1
              }, 
              function(result){
                $("#var_work_contractors").html(result);

                get_project_totals();
                // $.post(baseurl+"works/works_total",
                // {
                //   proj_id: proj_id
                // },
                // function(result){
                //   var works_totals = result.split( '/' );
                //   var t_price = works_totals[0];
                //   var t_estimate = works_totals[1];
                //   var t_quoted = works_totals[2];
                //   $("#work-total-price").val(t_price);
                //   $("#work-total-estimate").val(t_estimate);
                //   $("#work-total-quoted").val(t_quoted);
                  $.post(baseurl+"works/view_works_list", 
                  { 
                  }, 
                  function(result){
                      window.open(baseurl+"projects/view/"+proj_id+"/?show=1", '_self', true);
                  });
                //});
              });

            }else{
              var work_contractor = $('tbody tr#row-work-'+work_id).find('.work-set-comp-'+work_id+' a').text();
              if(work_contractor == ""){
                var qty = $(".work_joinery_set_qty_"+work_joinery_id).val();
                exprice = $(".work-set-exgst-"+selected_work_contractor_id).val();
                exprice = exprice.replace(',','');
                var tj_price = exprice * qty;
                var var_acceptance_date = $("#variation_acceptance_date").val();
                $.post(baseurl+"works/set_var_joinery_subitem_contractor",
                {
                  var_acceptance_date: var_acceptance_date,
                  proj_id: proj_id,
                  work_joinery_id: work_joinery_id,
                  joinery_work_id: joinery_work_id,
                  work_id: work_id,
                  qty: qty,
                  unit_price: exprice,
                  t_price: tj_price,
                  company_id: selected_work_contractor_id
                },
                function(result){
                  $("#var_work_contractors").html(result);
                  $(".add_comp_joinery_badge_"+work_joinery_id).hide();
                  $('tbody tr#row-work-joinery-'+work_joinery_id).find('.work_joinery_set_comp_'+work_joinery_id+' a').text(comp_name_work);
                  $('.work_joinery_set_unit_price_'+work_joinery_id).val(exprice);
                  $('.work_joinery_set_total_price_'+work_joinery_id).val(tj_price);

                  $.post(baseurl+"works/get_joinery_totals", 
                  { 
                    work_id: work_id
                  }, 
                  function(result){
                    var works_joinery_totals = result.split( '|' );
                    var work_joinery_price = works_joinery_totals[0];
                    var work_joinery_estimate = works_joinery_totals[1];
                    var work_joinery_quoted = works_joinery_totals[2];

                    work_joinery_quoted = numberWithCommas(Math.round(work_joinery_quoted));
                    work_joinery_estimate = numberWithCommas(Math.round(work_joinery_estimate));
                    work_joinery_price = numberWithCommas(Math.round(work_joinery_price));

                    $(".work-set-price-"+work_id).val(work_joinery_price);
                    if(work_joinery_estimate > 0 ){
                      $(".work-set-estimate-"+work_id).val(work_joinery_estimate);
                      $('tbody tr#row-work-'+work_id).find('.work-set-quote-'+work_id).text(work_joinery_quoted);
                    }
                  
                    $('.unset_joinery_comp_badge_'+work_joinery_id).css('display', 'block');
                    
                    get_project_totals();
                    //var proj_id = get_project_id();
                    // $.post(baseurl+"works/works_total",
                    // {
                    //   proj_id: proj_id
                    // },
                    // function(result){
                    //   var works_totals = result.split( '/' );
                    //   var t_price = works_totals[0];
                    //   var t_estimate = works_totals[1];
                    //   var t_quoted = works_totals[2];
                    //   $("#work-total-price").val(t_price);
                    //   $("#work-total-estimate").val(t_estimate);
                    //   $("#work-total-quoted").val(t_quoted);
                    // });
                  });
                });
              }else{
                $.post(baseurl+"works/unset_work_contractor",
                {
                  work_id: work_id
                },
                function(result){
                  //=============
                  var qty = $(".work_joinery_set_qty_"+work_joinery_id).val();
                  exprice = $(".work-set-exgst-"+selected_work_contractor_id).val();
                  exprice = exprice.replace(',','');
                  var tj_price = exprice * qty;
                  $.post(baseurl+"works/set_var_joinery_subitem_contractor",
                  {
                    work_joinery_id: work_joinery_id,
                    work_id: work_id,
                    qty: qty,
                    unit_price: exprice,
                    t_price: tj_price,
                    company_id: selected_work_contractor_id
                  },
                  function(result){
                    $(".add_comp_joinery_badge_"+work_joinery_id).hide();
                    $('tbody tr#row-work-joinery-'+work_joinery_id).find('.work_joinery_set_comp_'+work_joinery_id+' a').text(comp_name_work);
                    $('.work_joinery_set_unit_price_'+work_joinery_id).val(exprice);
                    $('.work_joinery_set_total_price_'+work_joinery_id).val(tj_price);

                    $.post(baseurl+"works/get_joinery_totals", 
                    { 
                      work_id: work_id
                    }, 
                    function(result){
                      var works_joinery_totals = result.split( '|' );
                      var work_joinery_price = works_joinery_totals[0];
                      var work_joinery_estimate = works_joinery_totals[1];
                      var work_joinery_quoted = works_joinery_totals[2];

                      work_joinery_quoted = numberWithCommas(Math.round(work_joinery_quoted));
                      work_joinery_estimate = numberWithCommas(Math.round(work_joinery_estimate));
                      work_joinery_price = numberWithCommas(Math.round(work_joinery_price));

                      $(".work-set-price-"+work_id).val(work_joinery_price);
                      $(".work-set-estimate-"+work_id).val(work_joinery_estimate);
                      $('tbody tr#row-work-'+work_id).find('.work-set-quote-'+work_id).text(work_joinery_quoted);

                      get_project_totals();
                      //var proj_id = get_project_id();
                      // $.post(baseurl+"works/works_total",
                      // {
                      //   proj_id: proj_id
                      // },
                      // function(result){
                      //   var works_totals = result.split( '/' );
                      //   var t_price = works_totals[0];
                      //   var t_estimate = works_totals[1];
                      //   var t_quoted = works_totals[2];
                      //   $("#work-total-price").val(t_price);
                      //   $("#work-total-estimate").val(t_estimate);
                      //   $("#work-total-quoted").val(t_quoted);

                        //$('tbody tr#row-work-'+work_id).find('.work-set-comp-'+work_id+' a').text("");
                        $('tbody tr#row-work-'+work_id).find('.work-set-comp-'+work_id+' a').hide();

                        //$('.unset_comp_badge_'+work_id).css('visibility', 'hidden');
                        $(".add_comp_badge_"+work_id).css('display', 'block');
                        $(".unset_comp_badge_"+work_id).hide();
                        $.post(baseurl+"works/view_works_list", 
                        { 
                        }, 
                        function(result){
                            window.open(baseurl+"projects/view/"+proj_id+"/?show=1", '_self', true); 
                        })
                      //});
                    });
                  });
                  //=============
                 
                });
              }
            }
          }
        });
      }else{
        var orgPrice = $('tbody tr#row-work-'+work_id).find('.work-set-price-'+work_id).text();
        exprice = exprice.replace(',','');
        exprice = numberWithCommas(Math.round(exprice));
        $('.work-set-price-'+work_id).val(exprice);
        $('tbody tr#row-work-'+work_id).find('.work-set-comp-'+work_id+' a').text(comp_name_work);
        $('.add_comp_badge_'+work_id).hide();
        $('.unset_comp_badge_'+work_id).css('display', 'block');

        $.post(baseurl+"works/select_var_contractor", 
        { 
          var_acceptance_date: var_acceptance_date,
          selected_work_contractor_id: selected_work_contractor_id,
          work_id: work_id,
          proj_id: proj_id
        }, 
        function(result){
          $("#var_work_contractors").html(result);

          get_project_totals();
          //var proj_id = get_project_id();
          // $.post(baseurl+"works/works_total",
          // {
          //   proj_id: proj_id
          // },
          // function(result){
          //   var works_totals = result.split( '/' );
          //   var t_price = works_totals[0];
          //   var t_estimate = works_totals[1];
          //   var t_quoted = works_totals[2];
          //   $("#work-total-price").val(t_price);
          //   $("#work-total-estimate").val(t_estimate);
          //   $("#work-total-quoted").val(t_quoted);
          // });
        });
      }

    });
  }  
  /*$("#set_work_joinery_contractor_yes").click(function(){
    var exprice = $(".work-set-exgst-"+selected_work_contractor_id).val();
    exprice = exprice.replace(',','');
    var comp_name_work = $('tr.cont-'+selected_work_contractor_id).find('.item-cont-'+selected_work_contractor_id+'-comp a').text();
    var orgPrice = $('tbody tr#row-work-'+work_id).find('.work-set-price-'+work_id).text();
    exprice = numberWithCommas(Math.round(exprice));
    $('.work-set-price-'+work_id).val(exprice);
    $('tbody tr#row-work-'+work_id).find('.work-set-comp-'+work_id+' a').text(comp_name_work);
    $('.add_comp_badge_'+work_id).hide();
    $('.unset_comp_badge_'+work_id).css('display', 'block');
    $.post(baseurl+"works/select_contractor", 
    { 
      selected_work_contractor_id: selected_work_contractor_id,
      work_id: work_id,
      all: 1
    }, 
    function(result){
      $("#work_contractors").html(result);
      var proj_id = get_project_id();
      $.post(baseurl+"works/works_total",
      {
        proj_id: proj_id
      },
      function(result){
        var works_totals = result.split( '/' );
        var t_price = works_totals[0];
        var t_estimate = works_totals[1];
        var t_quoted = works_totals[2];
        $("#work-total-price").val(t_price);
        $("#work-total-estimate").val(t_estimate);
        $("#work-total-quoted").val(t_quoted);
        $.post(baseurl+"works/view_works_list", 
        { 
        }, 
        function(result){
            window.open(baseurl+"projects/view/"+proj_id+"/?show=1", '_self', true);
        })
      });
    });
  })*/
  window.unset_work_company = function(a){
    work_id = a;
    $.post(baseurl+"works/unset_work_contractor",
    {
      work_id: work_id
    },
    function(result){
      $.post(baseurl+"works/view_works_list", 
      { 
      }, 
      function(result){
        window.open(baseurl+"projects/view/"+proj_id, '_self', true);
      });
    });
  }

  window.unset_var_work_company = function(a){
    work_id = a;
    var var_id = $("#var_id").val();
    $.post(baseurl+"works/unset_work_contractor",
    {
      work_id: work_id
    },
    function(result){
      $.post(baseurl+"variation/view_variation_joinery", 
      { 
        proj_id:proj_id,
        var_id: var_id
      }, 
      function(result){
        window.open(baseurl+"projects/view/"+proj_id, '_self', true);
      });
    });
  }

 /* $("#btn_select_subcontractor").click(function(){
    if(selected_work_contractor_id == 0){
      alert("You have not selected a contractor or the selected contractor is already set");
    }else{
      $.post(baseurl+"works/select_contractor", 
      { 
        selected_work_contractor_id: selected_work_contractor_id,
        work_id: work_id,
      }, 
      function(result){
        $("#work_contractors").html(result);
        //window.open(baseurl+"works/update_work_details/"+proj_id+"/"+work_id, '_self', true);
        $.post(baseurl+"works/view_works_list", 
        { 
          //work_type: work_type,
          //work_con_sup_id: work_con_sup_id,
        }, 
        function(result){
          //window.open(baseurl+"projects/", '_self', true);
          //window.open(baseurl+"projects/view/"+proj_id, '_self', true);
          location.reload();
        })
      });
    }
  });*/
  $("#update_work_desc").hide();
  $("#edit_work_desc").hide();
  $("#edit_est_markups").hide();
  $("#edit_work_date").hide();
  $("#edit_considerations_list").hide();
  $('#update_work_notes').prop('readonly', true);
  $("#update_replyby_desc").prop('readonly',true);
  $("#chkdeltooffice").attr("disabled", true);
  $("#btn_edit_work_desc").click(function(){
    $("#lbl_work_desc").hide();
    $("#btn_edit_work_desc").hide();
    $("#edit_work_desc").show();
    var work_con_sup_id = $("#hid_work_con_sup_id").val();
    //$('#worktype option[value="' + work_con_sup_id +'"]').prop('selected', true);
  });
  $("#btn_save_work_desc").click(function(){
    var work_joinery_id = $("#work_joinery_id").val();
    var work_id = $("#work_id").val();
    var proj_id = $("#proj_id").val();
    if(work_joinery_id == ""){
      var work_type = $("#worktype").val();
      if(work_type == '2_82'){
        var other_work_description = $("#other_work_description").val();
        $.post(baseurl+"works/update_other_work_desc", 
        { 
          work_id: work_id,
          other_work_description: other_work_description,
        }, 
        function(result){
          window.open(baseurl+"works/update_work_details/"+proj_id+"/"+work_id, '_self', true);
          $("#lbl_work_desc").show();
          $("#btn_edit_work_desc").show();
          $("#edit_work_desc").hide();
        });
      }else{
        var arr = work_type.split('_');
        work_type = arr[0];
        var work_con_sup_id = arr[1];
        $.post(baseurl+"works/update_work", 
        { 
          work_id: work_id,
          proj_id: proj_id,
          work_type: work_type,
          work_con_sup_id: work_con_sup_id,
          update_stat: 5
        }, 
        function(result){
          window.open(baseurl+"works/update_work_details/"+proj_id+"/"+work_id, '_self', true);
          $("#lbl_work_desc").show();
          $("#btn_edit_work_desc").show();
          $("#edit_work_desc").hide();
        });
      }
    }else{
      var joinery_name = $("#work_joinery_name").val();
      $.post(baseurl+"works/update_joinery_name", 
      { 
        work_joinery_id: work_joinery_id,
        joinery_name: joinery_name,
      }, 
      function(result){
        window.open(baseurl+"works/update_work_details/"+proj_id+"/"+work_id+"/"+work_joinery_id, '_self', true);
      });
    }
   
  });

  $("#btn_work_del_conf_yes").click(function(){
    var work_joinery_id = $("#work_joinery_id").val();
    var work_id = $("#work_id").val();
    var proj_id = $("#proj_id").val();
    if(work_joinery_id == ""){
      $.post(baseurl+"works/update_work",
      {
        work_id: work_id,
        proj_id: proj_id,
        update_stat: 6
      },
      function(result){
        $.post(baseurl+"works/view_works_list", 
        { 
          //work_type: work_type,
          //work_con_sup_id: work_con_sup_id,
        }, 
        function(result){
          window.open(baseurl+"projects/view/"+proj_id, '_self', true);
        })
      });
    }else{
      $.post(baseurl+"works/delete_selected_joinery_subitem",
      {
        work_joinery_id: work_joinery_id
      },
      function(result){
        $.post(baseurl+"works/view_works_list", 
        { 
          //work_type: work_type,
          //work_con_sup_id: work_con_sup_id,
        }, 
        function(result){
          window.open(baseurl+"projects/view/"+proj_id, '_self', true);
        })
      });
    }
  });

  $("#back_to_works").click(function(){
    //var work_id = $("#work_id").val();
    //var proj_id = $("#proj_id").val();
    $.post(baseurl+"works/view_works_list", 
    { 
      //work_id: work_id,
      //proj_id: proj_id
      //work_type: work_type,
      //work_con_sup_id: work_con_sup_id,
      //update_stat: 5
    }, 
    function(result){
      window.open(baseurl+"projects/view/"+proj_id, '_self', true);
    })
    return false;
  });

  $("#back_to_variation").click(function(){
    $.post(baseurl+"variation/back_to_variations", 
    { 
    }, 
    function(result){
      window.open(baseurl+"projects/view/"+proj_id+"/variation", '_self', true);
    })
    return false;
  });
  $("#btn_edit_est_markup").click(function(){
    $("#est_markup").hide();
    $("#edit_est_markups").show();
    $("#btn_edit_est_markup").hide();
    $("#save_est_markup").show();
  });
  $("#save_est_markup").click(function(){
    var work_joinery_id = $("#work_joinery_id").val();
    var work_id = $("#work_id").val();
    var proj_id = $("#proj_id").val();
    var work_markup = $("#work_markup").val();
    work_markup = work_markup.replace(",", "");

    $.post(baseurl+"works/update_work", 
    { 
      joinery_id: work_joinery_id,
      work_id: work_id,
      proj_id: proj_id,
      work_markup: work_markup,
      update_stat: 1
    }, 
    function(result){
      $.post(baseurl+"works/get_joinery_totals", 
      { 
         work_id: work_id
      }, 
      function(result){
        var works_joinery_totals = result.split( '|' );
        var work_joinery_price = works_joinery_totals[0];
        var work_joinery_estimate = works_joinery_totals[1];
        var work_joinery_quoted = works_joinery_totals[2];
        work_joinery_quoted = numberWithCommas(Math.round(work_joinery_quoted));
        work_joinery_estimate = numberWithCommas(Math.round(work_joinery_estimate));
        work_joinery_price = numberWithCommas(Math.round(work_joinery_price));
        $(".work-set-price-"+work_id).val(work_joinery_price);
        $(".work-set-estimate-"+work_id).val(work_joinery_estimate);
        $('tbody tr#row-work-'+work_id).find('.work-set-quote-'+work_id).text(work_joinery_quoted);
       
        get_project_totals();
        if(work_joinery_id == ""){
          window.open(baseurl+"works/update_work_details/"+proj_id+"/"+work_id, '_self', true);
        }else{
          window.open(baseurl+"works/update_work_details/"+proj_id+"/"+work_id+"/"+work_joinery_id, '_self', true);
        }
        $("#est_markup").show();
        $("#edit_est_markups").hide();;
        $("#btn_edit_est_markup").show();
        $("#save_est_markup").hide();
      });
    });
  });
  $("#edit_notes").click(function(){
    $('#update_work_notes').prop('readonly', false);
    $("#edit_notes").hide();
    $("#save_notes").show();
  });
  $("#save_notes").click(function(){
    var work_id = $("#work_id").val();
    var proj_id = $("#proj_id").val();
    var update_work_notes = $("#update_work_notes").val();

    var work_joinery_id = $("#work_joinery_id").val();

    $.post(baseurl+"works/update_work", 
    { 
      work_id: work_id,
      proj_id: proj_id,
      update_work_notes: update_work_notes,
      work_joinery_id: work_joinery_id,
      update_stat: 3
    }, 
    function(result){
      window.open(baseurl+"works/update_work_details/"+proj_id+"/"+work_id+"/"+work_joinery_id, '_self', true);
      $('#work_notes').prop('readonly', true);
      $("#edit_notes").show();
      $("#save_notes").hide();
    });

  });
  $("#edit_work_dates").click(function(){
    $("#edit_work_dates").hide();
    $("#save_work_dates").show();
    $("#work_date").hide();
    $("#edit_work_date").show();
    $("#chkdeltooffice").attr("disabled", false);
    $("#update_replyby_desc").prop('readonly',false);
  });
  $("#save_work_dates").click(function(){
    var work_id = $("#work_id").val();
    var proj_id = $("#proj_id").val();
    var work_joinery_id = $("#work_joinery_id").val();
    var work_replyby_date = $("#work_replyby_date").val();
    var update_replyby_desc = $("#update_replyby_desc").val();
    var goods_deliver_by_date = $("#goods_deliver_by_date").val();
    if($('#chkdeltooffice').is(':checked')){
      var chkdeltooffice = 1;
    }else{
      var chkdeltooffice = 0;
    }
  
    $.post(baseurl+"works/update_work", 
    { 
      work_id: work_id,
      work_joinery_id: work_joinery_id,
      proj_id: proj_id,
      work_replyby_date: work_replyby_date,
      update_replyby_desc: update_replyby_desc,
      chkdeltooffice: chkdeltooffice,
      goods_deliver_by_date: goods_deliver_by_date,
      update_stat: 2
    }, 
    function(result){
      window.open(baseurl+"works/update_work_details/"+proj_id+"/"+work_id+"/"+work_joinery_id, '_self', true);
      $("#edit_work_dates").show();
      $("#save_work_dates").hide();
      $("#work_date").show();
      $("#edit_work_date").hide();
      $("#chkdeltooffice").attr("disabled", true);
      $("#update_replyby_desc").prop('readonly',true);
    });
       
  });
  $("#edit_considerations").click(function(){
    $("#edit_considerations").hide();
    $("#save_considerations").show();
    $("#considerations").hide();
    $("#edit_considerations_list").show();
  });
  $("#save_considerations").click(function(){
    var work_joinery_id = $("#work_joinery_id").val();
    var work_id = $("#work_id").val();
    var proj_id = $("#proj_id").val();
    if($('#chkcons_site_inspect').is(':checked')){
      var chkcons_site_inspect = 1;
    }else{
      var chkcons_site_inspect = 0;
    }
    if($('#chckcons_week_work').is(':checked')){
      var chckcons_week_work = 1;
    }else{
      var chckcons_week_work = 0;
    }
    if($('#chckcons_spcl_condition').is(':checked')){
      var chckcons_spcl_condition = 1;
    }else{
      var chckcons_spcl_condition = 0;
    }
    if($('#chckcons_weekend_work').is(':checked')){
      var chckcons_weekend_work = 1;
    }else{
      var chckcons_weekend_work = 0;
    }
    if($('#chckcons_addnl_visit').is(':checked')){
      var chckcons_addnl_visit = 1;
    }else{
      var chckcons_addnl_visit = 0;
    }
    if($('#chckcons_afterhrs_work').is(':checked')){
      var chckcons_afterhrs_work = 1;
    }else{
      var chckcons_afterhrs_work = 0;
    }
    if($('#chckcons_oprte_duringinstall').is(':checked')){
      var chckcons_oprte_duringinstall = 1;
    }else{
      var chckcons_oprte_duringinstall = 0;
    }
    if($('#chckcons_new_premises').is(':checked')){
      var chckcons_new_premises = 1;
    }else{
      var chckcons_new_premises = 0;
    }
    if($('#chckcons_free_access').is(':checked')){
      var chckcons_free_access = 1;
    }else{
      var chckcons_free_access = 0;
    }
    if($('#chckcons_others').is(':checked')){
      var chckcons_others = 1;
      var other_consideration = $("#other_consideration").val();
    }else{
      var chckcons_others = 0;
      var other_consideration = "";
    }
    $.post(baseurl+"works/update_work", 
    { 
      work_id: work_id,
      proj_id: proj_id,
      work_joinery_id: work_joinery_id,
      chkcons_site_inspect: chkcons_site_inspect,
      chckcons_week_work: chckcons_week_work,
      chckcons_spcl_condition: chckcons_spcl_condition,
      chckcons_weekend_work: chckcons_weekend_work,
      chckcons_addnl_visit: chckcons_addnl_visit,
      chckcons_afterhrs_work: chckcons_afterhrs_work,
      chckcons_oprte_duringinstall: chckcons_oprte_duringinstall,
      chckcons_new_premises: chckcons_new_premises,
      chckcons_free_access: chckcons_free_access,
      chckcons_others: chckcons_others,
      other_consideration: other_consideration,
      update_stat: 4
    }, 
    function(result){
      window.open(baseurl+"works/update_work_details/"+proj_id+"/"+work_id+"/"+work_joinery_id, '_self', true);
      $("#edit_considerations").show();
      $("#save_considerations").hide();
      $("#considerations").show();
      $("#edit_considerations_list").hide();
    });
    
  });
  $("#work_cont_po").click(function(){
    //var project_id = get_project_id();
   
      if(job_date == ""){
        alert("Cannot Create CPO, Job Date Required!");
      }else{
        //var proj_id = get_project_id();
        if(work_id == 0){
          alert("Please select Work Contractor");
        }else{
          if(contractor_set == 1){
            if(joinery_work_id == 0){
              window.open(baseurl+"works/work_contractor_po/"+proj_id+"/"+work_id);
            }else{
              window.open(baseurl+"works/work_contractor_po/"+proj_id+"/"+work_id+"/"+joinery_work_id);
            }
            
          }else{
            alert("Contractor is not yet selected!");
          }
        }
      }
      return false;
  });

//===================== Maintenance Site Sheet ====================
  $("#maintenance_site_sheet").click(function(){
    if(work_id == 0 || work_id == ""){
      alert("No Work selected!");
    }else{
      if($(".add_comp_badge_"+work_id).is(":visible") == false){
        window.open(baseurl+"works/maintenance_site_sheet/"+proj_id+"/"+work_id);
      }else{
        alert("No CPO yet!");
      }
    }
    return false;  
  });

  //var segmentlength = segments.length;
  //alert(segmentlength);
  if(segmentlength >= (segment_index-1)){
    var update_project = segments[segment_index-1];
    if(update_project == 'add'){
      $(".maintenance_site_cont_form").hide();
    }else{
      if($("#update_proj_type").val() !== "Maintenance"){
        $(".maintenance_site_cont_form").hide();
      }
    }
  }

  $("#job_category").change(function(){

    if($(this).val() == "Maintenance"){
      $(".maintenance_site_cont_form").show();
    }else{
      $(".maintenance_site_cont_form").hide();
    }
  });

  $("#btn_create_mss").click(function(){
    if(job_date == ""){
      alert("Cannot Create CPO, Job Date Required!");
    }else{
      $('input[class=cont_checkbox]:checked').map(function(){
        work_contractor_id = $(this).val();
        $.post(baseurl+"works/fetch_works_contractors", 
        { 
          work_contractor_id: work_contractor_id
        }, 
        function(result){
          var result = result.split( '|' );
          var is_selected = result[2];
          var work_id = result[1];
          if(is_selected == 1){
            window.open(baseurl+"works/maintenance_site_sheet/"+proj_id+"/"+work_id);
          }
        });
      });
    }
  });

  $('#work_notes').bind('change keyup', function(event) {
    //Option 1: Limit to # of rows in textarea
    var project_type = $("#project_type").val();
    if(project_type == "Maintenance"){
      rows = $(this).attr('rows');
      //Optiion 2: Limit to arbitrary # of rows
      rows = 14;

      var value = '';
      var splitval = $(this).val().split("\n");

      for(var a=0;a<rows && typeof splitval[a] != 'undefined';a++) {
        if(a>0) value += "\n";
        value += splitval[a];
      }
      $(this).val(value);
    }
  });

  $('#update_work_notes').bind('change keyup', function(event) {
    //Option 1: Limit to # of rows in textarea
    var project_type = $("#project_type").val();
    if(project_type == "Maintenance"){
       rows = $(this).attr('rows');
      //Optiion 2: Limit to arbitrary # of rows
      rows = 14;

      var value = '';
      var splitval = $(this).val().split("\n");

      for(var a=0;a<rows && typeof splitval[a] != 'undefined';a++) {
        if(a>0) value += "\n";
        value += splitval[a];
      }
      $(this).val(value);
    }
   
  });

  
//=================== Maintenance Site Sheet
  $("#work_cont_quote_req").click(function(){
    //var proj_id = get_project_id();
    if(work_id == 0){
      alert("Please select Work Contractor");
    }else{
      $.post(baseurl+"works/works_contractors", 
      { 
        work_id: work_id
      }, 
      function(result){
        var work_contrator_id = result.split("|");
        var x = 0;
        while(x < (work_contrator_id.length - 1)){
          var contractor_id = work_contrator_id[x];
          window.open(baseurl+"works/contractor_quote_request/"+proj_id+"/"+work_id+"/"+contractor_id);
          x++;
        }
      });
      
    }
    return false;
  });
  $("#create_cqr").click(function(){
    var result = $("#work_contructor_name").val();
    var comp_id = result.split("|");
   
    comp_id = comp_id[1];
    var cont_works_id = $("#cont_cpono").text();
    window.open(baseurl+"works/contractor_quote_request/"+proj_id+"/"+cont_works_id+"/"+comp_id);
  });

  $("#create_var_cqr").click(function(){
    var result = $("#var_work_contructor_name").val();
    var comp_id = result.split("|");
   
    comp_id = comp_id[1];
    var cont_works_id = $("#var_cont_cpono").text();
    window.open(baseurl+"works/contractor_quote_request/"+proj_id+"/"+cont_works_id+"/"+comp_id);
  });
  //--- Sort Selection in works ---work_contructor_name
var my_options = $("#worktype option");
var selected = $("#worktype").val(); /* preserving original selection, step 1 */

my_options.sort(function(a,b) {
    if (a.text > b.text) return 1;
    else if (a.text < b.text) return -1;
    else return 0
})

$("#worktype").empty().append( my_options );
$("#worktype").val(selected); /* preserving original selection, step 2 */
  //--- Sort Selection in works Contractor ---
var my_options = $("#work_contructor_name option");
var selected = $("#work_contructor_name").val(); 

my_options.sort(function(a,b) {
    if (a.text > b.text) return 1;
    else if (a.text < b.text) return -1;
    else return 0
})

$("#work_contructor_name").empty().append( my_options );
$("#work_contructor_name").val(selected); 


$.post(baseurl+"attachments/view_attachment_type", 
{ 
  type: 3
}, 
function(result){
  $("#add_work_attachment_types").html(result);
});

$("#show_attachment_type_modal").click(function(){
  $('.modal').modal('hide');
  $("#attachment_type_modal").modal('show');
  $("#txt_attachment_type").val("");
  $("#btn_add_attachment_type").show();
  $("#btn_update_attachment_type").hide();
  $("#btn_delete_attachment_type").hide();
  $.post(baseurl+"attachments/view_attachment_type",
    {
      type:2
    },
    function(result){
      $('#table_attachment_type').html(result);
    });
  return false
});

$("#save_work_attachment").hide();
$("#edit_work_attachment").show();
$("#work_attachment_type_list").show();
$("#edit_work_attachment_type_list").hide();

$("#edit_work_attachment").click(function(){
  $("#save_work_attachment").show();
  $("#edit_work_attachment").hide();
  $("#work_attachment_type_list").hide();
  $("#edit_work_attachment_type_list").show();
  /*$.post(baseurl+"attachments/view_attachment_type", 
  { 
    type: 4
  }, 
  function(result){
    $("#edit_work_attachment_type_list").html(result);
  });*/
});
$("#save_work_attachment").click(function(){
  var checkboxValues = [];
  $('input[name=work_attachment_type]:checked').map(function() {
              checkboxValues.push($(this).val());
  });
  work_id = $("#work_id").val();
  proj_id = $("#proj_id").val();
  $.post(baseurl+"works/update_work_attachment_type",
  {
    work_id: work_id,
    checkboxValues:checkboxValues,
  },
  function(result){
    window.open(baseurl+'works/update_work_details/'+proj_id+"/"+work_id, '_self', true);
  });
  $("#save_work_attachment").hide();
  $("#edit_work_attachment").show();
  $("#work_attachment_type_list").show();
  $("#edit_work_attachment_type_list").hide();
});
$("#work_desc").show();
$("#work_joinery").hide();

$("#is_joinery").click(function(){
  if($("#is_joinery").is(":checked")){
    $("#work_desc").hide();
    $("#work_joinery").show();
    $("#work_joinery_name").focus();
    $("#work_joinery_name").val("");
    $.post(baseurl+"works/fetch_joinery",
    {
    },
    function(result){
      joinery_name = JSON.parse(result);
      $.mockjax({
          url: '*',
          responseTime: 2000,
          response: function (settings) {
              var query = settings.data.query,
                  queryLowerCase = query.toLowerCase(),
                  re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi'),
                  suggestions = $.grep(joinery_name, function (country) {
                       // return country.value.toLowerCase().indexOf(queryLowerCase) === 0;
                      return re.test(country.value);
                  }),
                  response = {
                      query: query,
                      suggestions: suggestions
                  };

              this.responseText = JSON.stringify(response);
          }
      });

      $('#work_joinery_name').autocomplete({
          // serviceUrl: '/autosuggest/service/url',
          lookup: joinery_name,
          lookupFilter: function(suggestion, originalQuery, queryLowerCase) {
              var re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
              return re.test(suggestion.value);
          },
          onSelect: function(suggestion) {
              $('#selction-ajax').html('You selected: ' + suggestion.value + ', ' + suggestion.data);
          },
          onHint: function (hint) {
              $('#autocomplete-ajax-x').val(hint);
          },
          onInvalidateSelection: function() {
              $('#selction-ajax').html('You selected: none');
          }
          
      });
      /*$( "#work_joinery_name" ).autocomplete({
        source: joinery_name
      });*/
    });
  }else{
    $("#work_desc").show();
    $("#work_joinery").hide();
  }
});

$("#work_joinery_name").change(function(){
  $('.modal').modal('hide');
  $("#joinery_modal").modal('show');
});
$(".joinery_items").hide();
window.show_joinery = function(a){
  var show = $("#show").val();
    $.post(baseurl+"works/view_works_list", 
    { 
    }, 
    function(result){
      if(show == 1){
        window.open(baseurl+"projects/view/"+proj_id, '_self', true);
      }else{
        window.open(baseurl+"projects/view/"+proj_id+"/?show=1", '_self', true); 
      }
    })
  return false;
}

window.show_variation_joinery = function(a){
  var show = $("#var_show").val();
  var var_id = $("#var_id").val();
  $.post(baseurl+"variation/view_variation_joinery", 
  { 
    proj_id:proj_id,
    var_id: var_id
  }, 
  function(result){
    if(show == 1){
      window.open(baseurl+"projects/view/"+proj_id, '_self', true);
    }else{
      window.open(baseurl+"projects/view/"+proj_id+"/?show=1", '_self', true); 
    }
  });
  return false;
}
  /*$("#site_start").change(function(){
    alert("sfsd");
  });*/
 /* $("#frmcontractor").hide();
  $("#btnaddcontractor").click(function(){
    $("#txtselcompdate").val("");
    $("#selcontcompany").val("");
    $("#selattention").val("");
    $("#txtselcompnote").val("");
    $("#txtselcompincgst").val("");
    $("#txtselcompexgst").val("");
    $("#txtselcompremarks").val("");
    $("#addcontractor").show();
    $("#updatecontractor").hide();
    $("#removecontractor").hide();
  });
  $("#showaddworkmodal").click(function(){
    $("#works").val("");
    $("#work_estimate").val("");
    $("#work_sdate").val("");
    $("#work_markup").val("");
    $("#work_fdate").val("");
    $("#work_quote_val").val("");
    $("#work_replyby_date").val("");
    $("#replyby_desc").val("");
    $('#chkdeltooffice').attr('checked', false);
    $('#chkcons_site_inspect').attr('checked', false);
    $('#chckcons_week_work').attr('checked', false);
    $('#chckcons_spcl_condition').attr('checked', false);
    $('#chckcons_weekend_work').attr('checked', false);
    $('#chckcons_addnl_visit').attr('checked', false);
    $('#chckcons_afterhrs_work').attr('checked', false);
    $('#chckcons_oprte_duringinstall').attr('checked', false);
    $('#chckcons_new_premises').attr('checked', false);
    $('#chckcons_free_access').attr('checked', false);
    $("#other_consideration").val("");
    $("#work_notes").val("");
    $("#work_cpodate_req").val("");
    $("#work_cpo_date").val("");
    $("#addcontractor").show();
    $("#updatecontractor").hide();
    $("#removecontractor").hide();
  });
  window.contractor = function(){
    $("#frmcontractor").show();
    return false;
  }
  window.editwork = function(a){
    work_id = a;
    $.post(baseurl+"works/display_work_form", 
    { 
      work_id: a
    }, 
    function(result){
      $("#frm_add_works").html(result);
    });
    $("#addcontractor").hide();
    $("#updatecontractor").show();
    $("#removecontractor").show();
  }*/

  /*$("#attach").click(function(){
    alert("dsfsd");
  });*/

  // affix sidebar
  $('#sidebar').affix({offset : {top : 75}});
  var $body = $(document.body);
  var navHeight = $('.top-nav').outerHeight(true) + 10;
  $body.scrollspy({target : '#leftCol',offset : navHeight });
  // affix sidebar  

  $('.popover-test').popover();
  $('.popover-form').popover();
  
   
  
  $('.tooltip-test').tooltip(); 
  $('.tooltip-enabled').tooltip();    
  
  $('#loading-example-btn').click(function(){
        var btn = $(this);
        btn.button('loading');
        setTimeout(function () {
            btn.button('reset');
            $(".alert").alert('close');
        }, 1000);
    });


  var base_mark_up = 0;
  $("#job_category").change(function(){
    var job_category = $(this).val();
    
    $('#project_markup').empty();
    $('.min_mark_up').empty();


    $.post(baseurl+"projects/fetch_mark_up_by",{ 
      job_cat: job_category
    },
    function(result){
      var mark_up = result.split("|");
      base_mark_up = mark_up[0];
      $('#project_markup').val(mark_up[0]);
      $('.min_mark_up').text(mark_up[1]);
      $('.min_mark_up').val(mark_up[1]);
    });
  });

  $('#project_markup').change(function(){


    var project_markup = parseInt($(this).val()).toFixed(2);
    var min_mark_up = parseInt($('.min_mark_up').text());

    var base_mark_up = min_mark_up;
   
    if(project_markup < min_mark_up){
      $(this).val(base_mark_up);
    }

    if($(this).val() == ''){
      $(this).val(base_mark_up);
    }
      //load_data_ajax($(this).val());
}); //this is working select callbak!


window.toggleShoppingCenterDetails = function(id){
    var shoppingCenterId = id;

    $.post(baseurl+"shopping_center/get_shopping_center_detail",{ 
      shopping_center_id: id

    },function(result){

      var shopping_center_details = result.split("|");

      $('input#edit_brand').val(shopping_center_details[0]);
      $('input#edit_street_number').val(shopping_center_details[1]);
      $('input#edit_street').val(shopping_center_details[2]);

      $("select#state_b").val(shopping_center_details[6]+'|'+shopping_center_details[5]+'|'+shopping_center_details[8]+'|'+shopping_center_details[7]);
      $('#s2id_state_b span.select2-chosen').text(shopping_center_details[5]);

      $("select#suburb_b").append('<option selected value="'+shopping_center_details[4]+'|'+shopping_center_details[5]+'|'+shopping_center_details[8]+'">'+shopping_center_details[10]+'</option>');
      $('#s2id_suburb_b span.select2-chosen').text(shopping_center_details[10]);

      $("select#postcode_b").append('<option selected value="'+shopping_center_details[3]+'">'+shopping_center_details[3]+'</option>');
      $('#s2id_postcode_b span.select2-chosen').text(shopping_center_details[3]);

      $('input#shopping_center_id').val(shopping_center_details[9]);
      $('input#edit_common_name').val(shopping_center_details[11]);
      var delHref = $('.delete-shopping').attr('href');
      $('.delete-shopping').attr('href',delHref+shopping_center_details[9]);

    });

    $('.add-shopping-center').hide();
    $('.edit-shopping-center').show();

    return false;
  }

/*$('#work_markup').blur(function(){

    var work_markup = parseInt($(this).val()).toFixed(2);
    var min_mark_up = parseInt($('.min_mark_up').text());

    if(work_markup < min_mark_up){
      $(this).val(min_mark_up);
    }

    if($(this).val() == ''){
      $(this).val(min_mark_up);
    }


    var quote = +($("#work_estimate").val()) +  (+($("#work_estimate").val()) * (+($("#work_markup").val())/100));
    $("#work_quote_val").val(quote);


      //load_data_ajax($(this).val());
}); *///this is working select callbak!


    $(".number_format").keyup(function (event) {
    // skip for arrow keys
    if (event.which >= 37 && event.which <= 40) {
      event.preventDefault();
    }

    var currentVal = $(this).val();
    var testDecimal = testDecimals(currentVal);
    if (testDecimal.length > 1) {
      console.log("You cannot enter more than one decimal point");
      currentVal = currentVal.slice(0, -1);
    }
    $(this).val(replaceCommas(currentVal));

  });

    function testDecimals(currentVal) {
      var count;
      currentVal.match(/\./g) === null ? count = 0 : count = currentVal.match(/\./g);
      return count;
    }

    function replaceCommas(yourNumber) {
      var components = yourNumber.toString().split(".");
      if (components.length === 1) 
        components[0] = yourNumber;
      components[0] = components[0].replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      if (components.length === 2)
        components[1] = components[1].replace(/\D/g, "");
      return components.join(".");
    }


    $(".select-focus").on("change", function(e) {      
        var myVal = $(this).val();
        var controller_method = 'projects/set_jurisdiction';
        $('#s2id_state_a .select2-chosen').empty();

        $('select.state-option-a').empty();
        var classLocation = 'select.state-option-a';
        ajax_data(myVal,controller_method,classLocation);

        $('select.select_state_shopping_center').empty();
        var classLocation = 'select.select_state_shopping_center';
        ajax_data(myVal,controller_method,classLocation);

        ajax_data(myVal,'projects/set_jurisdiction_shoping_center','.brand_shopping_center');
    });


    $(".find_contact_person").on("change", function(e) {
      
        var myVal = $(this).val();
        var controller_method = 'projects/find_contact_person';

        $('select#contact_person').empty();
        var classLocation = 'select#contact_person';
        ajax_data(myVal,controller_method,classLocation);


      
      //load_data_ajax($(this).val());
    }); //this is working select callbak!





    $(".get_address_invoice").on("change", function(e) {
      
        var myVal = $(this).val();
 /*       var controller_method = 'projects/find_contact_person';

        $('select#contact_person').empty();
        var classLocation = 'select#contact_person';
        ajax_data(myVal,controller_method,classLocation);
*/


        $.ajax({
        'url' : base_url+'projects/fetch_address_company_invoice',
        'type' : 'POST',
        'data' : {'ajax_var' : myVal },
        'success' : function(data){
          //var divLocation = $(classLocation);
          if(data){

             var address_raw = data.split("|");

             $('#unitlevel2').val(address_raw[0]);
             $('#number2').val(address_raw[1]);
             $('#street2').val(address_raw[2]);
             $('#pobox').val(address_raw[3]);

             var state_invoice_val = address_raw[4]+'|'+address_raw[5]+'|'+address_raw[6]+'|'+address_raw[7];
             $('select#state_b').val(state_invoice_val);
             $('.state-option-b span.select2-chosen').text(address_raw[5]);

             var suburb_invoice_upper = address_raw[8].toUpperCase();

             var state_invoice_val = suburb_invoice_upper+'|'+address_raw[5]+'|'+address_raw[6];

             $('select#suburb_b').append('<option selected value="'+state_invoice_val+'">'+address_raw[8]+'</option>');
             $('select#suburb_b').val(state_invoice_val);
             $('.suburb-option-b span.select2-chosen').text(address_raw[8]);


             $('select#postcode_b').append('<option selected value="'+address_raw[9]+'">'+address_raw[9]+'</option>');
             $('select#postcode_b').val(address_raw[9]);
             $('.postcode-option-b span.select2-chosen').text(address_raw[9]);

          }
        }
      });
      
      //load_data_ajax($(this).val());
    }); //this is working select callbak!



    $('select#job_type').on("change", function(e) {
      
        var myVal = $(this).val();

        if(myVal == 'Shopping Center'){
          $('.site_address').hide();
          $('.shopping_center').show();
          $('.is_shopping_center').val(1);



        }else{
          $('.site_address').show();
          $('.shopping_center').hide();
          $('.is_shopping_center').val(0);

          $('#shop_tenancy_number').val('');
          $('#shopping_center').val('');
          $('#shopping_center_suburb').val('');

        }
  
    });


    $(".project_comment_btn").click(function(){

      var prjc_user_id = $('.prjc_user_id').val();
      var prjc_user_first_name = $('.prjc_user_first_name').val();
      var prjc_user_last_name = $('.prjc_user_last_name').val();
      var prjc_project_id = $('.prjc_project_id').val();
      var project_comment = $('.project_comment').val();

      var dataString = prjc_user_id+'|'+prjc_project_id+'|'+project_comment;

      $('.project_comment').empty().val('');

      if(project_comment!=''){
        $.post(baseurl+"projects/add_project_comment",{ 
          'ajax_var': dataString
        },function(result){
          $('.box-list ul').prepend('<li><div class="pad-10 m-bottom-10"><p class="news-item-preview"><i class="fa fa-quote-left"></i> '+project_comment+'</p><small><i class="fa fa-user"></i> '+prjc_user_first_name+' '+prjc_user_last_name+'<br><i class="fa fa-calendar"></i> '+result+'</small></div></li>');
          $('.recent_prj_comment').empty().append('<p>'+project_comment+'</p><small><i class="fa fa-user"></i> '+prjc_user_first_name+' '+prjc_user_last_name+'<br><i class="fa fa-calendar"></i> '+result+'</small>');



        });
      }

    });


    $("#unit_level").on("change", function(e) {
      if ($('.sameToPost').is(':checked')) {
        //alert($(this).val());
        $("#unitlevel2").val($(this).val());
      }
      //load_data_ajax($(this).val());
    }); //this is working select callbak!     
    
    $("#number").on("change", function(e) {
      if ($('.sameToPost').is(':checked')) {
        $("#number2").val($(this).val());
      }       
    });
    
    $("#street").on("change", function(e) {
      if ($('.sameToPost').is(':checked')) {
        $("#street2").val($(this).val());
      }       
    });

    $('#state_a').on("change", function(e) {

      //alert($(this).val());

    if ($('.sameToPost').is(':checked')) {

        var setValRaw = $(this).val().split("|");
          var optionText = setValRaw[1];

        $('#state_b').val( $(this).val() );
          $('.state-option-b span.select2-chosen').text(optionText);
      }

  

  });

  $('#suburb_a').on("change", function(e) {

      if ($('.sameToPost').is(':checked')) {
        var setValRaw = $(this).val().split("|");
          var optionText = setValRaw[0];
          var optionVal = $(this).val();

          optionText = optionText.toLowerCase();
          optionText = optionText.replace(/\b./g, function(m){ return m.toUpperCase(); });

          $('#suburb_b').empty().append('<option selected="selected" value="'+optionVal+'">'+optionText+'</option>');
          $('#suburb_b').val( $(this).val());
          $('.suburb-option-b span.select2-chosen').text(optionText);
        }
        //$('.postcode-option-b').empty().append('<option value="">Choose a Postcode...</option>'); 
  });



  $('.data-area').on("hover", function(e) {
    var this_button = $(this).attr('id');
    
    alert(this_button);
    });


    $("#edit_company_name").click(function(){
      $(this).hide();
      $("#save_company_name").show();
      $('.company_name').hide();
      $('.company_name_data').show();
      $('#delete_company').show();
      $('#delete_focus').show();
      
    });

    $("#save_company_name").click(function(){
      $(this).hide();
      $("#edit_company_name").show();
      $('.company_name').show();
      $('#delete_company').hide();
      $('#delete_focus').hide();
      $('.company_name_data').hide();
      var comp_name = $('#company_name_data').val();
      $('.company_name').empty().text(comp_name);
    });

    $("#edit_physical_address").click(function(){
      $(this).hide();
      $("#save_physical_address").show();
      $('.physical_address_group').hide();
      $('.physical_address_group_data').show();
    });

    $("#save_physical_address").click(function(){
      $(this).hide();
      $("#edit_physical_address").show();
      $('.physical_address_group').show();
      $('.physical_address_group_data').hide();
    });

    $("#edit_postal_address").click(function(){
      $(this).hide();
      $("#save_postal_address").show();
      $('.postal_address_group').hide();
      $('.postal_address_group_data').show();
    });

    $("#save_postal_address").click(function(){
      $(this).hide();
      $("#edit_postal_address").show();
      $('.postal_address_group').show();
      $('.postal_address_group_data').hide();
    });

    $("#edit_bank_details").click(function(){
      $(this).hide();
      $("#save_bank_details").show();
      $('.bank_details_group').hide();
      $('.bank_details_group_data').show();
    });

    $("#save_bank_details").click(function(){
      $(this).hide();
      $("#edit_bank_details").show();
      $('.bank_details_group').show();
      $('.bank_details_group_data').hide();

      var bank_name = $('#bank-name').val();
      var account_name = $('#account-name').val();
      var account_number = $('#account-number').val();
      var bsb_number = $('#bsb-number').val();

      $('span.data-bank-name').empty().text(bank_name);
      $('span.data-account-name').empty().text(account_name);
      $('span.data-account-number').empty().text(account_number);
      $('span.data-bsb-number').empty().text(bsb_number);
    });

    $("#edit_more_details").click(function(){
      $(this).hide();
      $("#save_more_details").show();
      $('.more_details_group').hide();
      $('.more_details_group_data').show();
    });

    $("#save_more_details").click(function(){
      $(this).hide();
      $("#edit_more_details").show();
      $('.more_details_group').show();
      $('.more_details_group_data').hide();
    });

    $("#edit_comment_details").click(function(){
      $(this).hide();
      $("#save_comment_details").show();
      $('.comments-data').hide();
      $('.comments').show();
    });

    $("#save_comment_details").click(function(){
      $(this).hide();
      $("#edit_comment_details").show();
      $('.comments-data').show();
      $('.comments').hide();

      $('.comments-data').empty().text($('.comments').val());

    });


    $("#edit_primary_contact").click(function(){
      $(this).hide();
      $("#save_primary_contact").show();
      $('.primary-contact-group').hide();
      $('.primary-contact-group-data').show();
    });

    $("#save_primary_contact").click(function(){

      var primary_office_number = $("#primary_office_number").val();
      var primary_mobile_number = $("#primary_mobile_number").val();
      var has_error = 0;

      if(primary_office_number == ''){
        if(primary_mobile_number == '' ){
          has_error = 1;
        }
      }

      if(primary_mobile_number == '' ){
        if(primary_office_number == ''){
          has_error = 1;          
        }
      }

      if(has_error == 0){
        $(this).hide();
        $("#edit_primary_contact").show();
        $('.primary-contact-group').show();
        $('.primary-contact-group-data').hide();
      }


    });

    $(".edit_other_contact").click(function(){
      $(this).hide();
      var target = $(this).attr('id').substring(19);
      $('#save_other_contact_'+target).show();
      $('#delete_other_contact_'+target).show();
      $('.other-contact-group_'+target).hide();
      $('.other-contact-group-other_data_'+target).show();
    });

    $(".save_other_contact").click(function(){
      $(this).hide();
      var target = $(this).attr('id').substring(19);
      $('#delete_other_contact_'+target).hide();
      $('#edit_other_contact_'+target).show();
      $('.other-contact-group_'+target).show();
      $('.other-contact-group-other_data_'+target).hide();
    });

    $("#add_new_contact").click(function(){
      $(this).hide();
      $('#add_save_contact').show();
      $('#cancel_contact').show();
      $('.new_contact_area').show();
      $('#other_first_name').focus();
    });


    $("#cancel_contact").click(function(){
      $("#add_new_contact").show();
      $('#add_save_contact').hide();
      $('#cancel_contact').hide();

      $('.new_contact_area .form-control').each(function(){
        $(this).val('');
      });

      $('.new_contact_area').hide();
    });

    $("#edit_other_details").click(function(){
      $(this).hide();
      $('#save_other_details').show();
      $('.more_details_group').hide();
      $('.more_details_group_data').show();
    });

    $("#save_other_details").click(function(){
      $(this).hide();
      $('#edit_other_details').show();
      $('.more_details_group').show();
      $('.more_details_group_data').hide();
    });


    $("#edit_contact_details").click(function(){
      $(this).hide();
      $('#save_contact_details').show();
      $('.contact_details_group').hide();
      $('.contact_details_group_data').show();
    });

    $("#save_contact_details").click(function(){
      $(this).hide();
      $('#edit_contact_details').show();
      $('.contact_details_group').show();
      $('.contact_details_group_data').hide();
    });





/* data tables op */
 
$('#worksTable tbody').on( 'click', 'span.remove-row', function () {
    var row = $(this).parent().parent();
    //var rowNode = row.node();
    row.remove();
});

$('#worksTable tbody').on( 'click', 'span.set-comp', function () {
   // alert('set-comp'); 
});
 
$('#worksTable tbody').on( 'click', 'span.add-attach', function () {
    //alert('add-attach'); 
});


 
$('#variationTable tbody').on( 'click', 'span.remove-row', function () {
    var row = $(this).parent().parent();
    //var rowNode = row.node();
    row.remove();
});
 
$('#variationTable tbody').on( 'click', 'span.set-comp', function () {
    //alert('set-comp'); 
});
 
$('#variationTable tbody').on( 'click', 'span.remove-comp', function () {
    //alert('remove-comp'); 
});
 
$('#variationTable tbody').on( 'click', 'span.add-attach', function () {
   // alert('add-attach'); 
});

/* data tables op */





$('input.input-wd').keyup(function(){
  var inputWd = $(this).val().toString().toLowerCase();
  $('table#table-wd tr').each(function () {
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





  $('#type').on("change", function(e) {
    var this_val = $(this).val();   
    if ( this_val == 'Client|1' ) {
          $('.bank_account').hide();
      }else{
          $('.bank_account').show();
      }
  }); 

  $('#postcode_a').on("change", function(e) {
    if ($('.sameToPost').is(':checked')) {
          var optionVal = $(this).val();
          $('#postcode_b').empty().append('<option value="'+optionVal+'">'+optionVal+'</option>');
        $('#postcode_b').val( $(this).val() );
          $('.postcode-option-b span.select2-chosen').text($(this).val());
      }
  });

    $('.job-date-set').on("change", function(e) {
    //if($(this).val() == ''){
     // $('.is_wip').prop('checked', false);
   // }else{
    //  $('.is_wip').prop('checked', true);
   // }
   if($(this).val() == ''){
      $('.delete-project-box').show();
      $('.is_wip').prop('checked', false);

    }else{
      $('.delete-project-box').hide();
      $('.is_wip').prop('checked', true);

    }
   });

    //$("#abn").focusout(function(){
  window.add_comp_abn_blur = function(){
    var type = $("#type").val();
    if(type == ""){
      alert("Please select type first.");
      $("#abn").val("");
    }else{
      if($("#abn").val() == ''){
        //$('.is_wip').prop('checked', false);
        $("#acn").val('');
      }else{

        var type_arr = type.split("|");
        type = type_arr[1];

        var abn = $("#abn").val().replace(/[^\d]/g, "");

        $.post(baseurl+"company/check_company_exist",
        {
          abn: abn,
          type: type
        },
        function(result){
          if(result == 1){
            alert("ABN already exist!");
            $("#abn").val("");
          }else{
            var new_abn_val = abn.substring( -2,2)+' '+abn.substring( 2,5)+' '+abn.substring( 5,8)+' '+abn.substring( 8,11);
            $("#abn").val(new_abn_val);

            var acn_val = abn.substring( 2,5)+' '+abn.substring( 5,8)+' '+abn.substring( 8,11);

            $("#acn").val(acn_val);
          }
        });
      }
    }
  };

/*
    $( ".mobile_number_assign" ).keyup(function() {

      keyup_counter++;
      var abn = $(".mobile_number_assign").val();

      if(keyup_counter%3==0){
        $(".mobile_number_assign").val(abn+' ');
      }
    });
*/
    $("#street2").on("change", function(e) {
      
      if($(this).val() !='' ){
        $("input#pobox").parent().addClass('disabled-input');
        $('div.state-option-b a.select2-choice').focus();
      }else{
        $("input#pobox").parent().removeClass('disabled-input');
      }
    });
  
    $("#pobox").on("change", function(e) {
      if($(this).val() !='' ){
        $("input#street2").parent().addClass('disabled-input');
        $('div.state-option-b a.select2-choice').focus();
      }else{
        $("input#street2").parent().removeClass('disabled-input');
      }
    });

    $(".sameToPost").click(function(){      
    if ($(this).is(':checked')) {

      $("#unitlevel2").val('');
        $("#number2").val('');
        $("#street2").val('');

        $("#state_b").val('');        
          $('.state-option-b span.select2-chosen').text('Choose a State...');

        $("#suburb_b").val('');
          $('.suburb-option-b span.select2-chosen').text('Choose a Suburb...');

        $("#postcode_b").val('');
          $('.postcode-option-b span.select2-chosen').text('Choose a Postcode...');

        $("#state_b").val($("#state_a").val());
        var stateValRaw = $("#state_a").val().split("|");
          var stateText = stateValRaw[1];
          $('.state-option-b span.select2-chosen').text(stateText);

      $("#suburb_b").empty();
      var setValRaw = $('#suburb_a').val().split("|");
          var optionText = setValRaw[0];
          var optionVal = $("#suburb_a").val();

          optionText = optionText.toLowerCase();
          optionText = optionText.replace(/\b./g, function(m){ return m.toUpperCase(); });

          $('#suburb_b').empty().append('<option value="'+optionVal+'">'+optionText+'</option>');
          $('#suburb_b').val( $('#suburb_a').val());

          $('.suburb-option-b span.select2-chosen').text(optionText);

          $("input#pobox").val('');


    

        $("#postcode_b").empty();
        var postcode_aVAl = $("#postcode_a").val();
        var child = '<option value="'+postcode_aVAl+'">'+postcode_aVAl+'</option>';
        $("#postcode_b").append(child);
        $("#postcode_b").val($("#postcode_a").val());
          $('.postcode-option-b span.select2-chosen').text(postcode_aVAl);

        $("#unitlevel2").val($("#unit_level").val());
        $("#number2").val($("#number").val());
        $("#street2").val($("#street").val());

            $("input#pobox").parent().addClass('disabled-input');
        $("input#unitlevel2").parent().addClass('disabled-input');
        $("input#number2").parent().addClass('disabled-input');
        $("input#street2").parent().addClass('disabled-input');

            $('div.state-option-b').addClass('disabled-input'); 
            $('div.suburb-option-b').addClass('disabled-input');  
            $('div.postcode-option-b').addClass('disabled-input');

        
    } else {
      //$(this).prop('checked',true);
      //alert("not checked");
        $("#unitlevel2").val('');
        $("#number2").val('');
        $("#street2").val('');

        $("#state_b").val('');        
          $('.state-option-b span.select2-chosen').text('Choose a State...');

        $("#suburb_b").val('');
          $('.suburb-option-b span.select2-chosen').text('Choose a Suburb...');

        $("#postcode_b").val('');
          $('.postcode-option-b span.select2-chosen').text('Choose a Postcode...');
  
            $('div.state-option-b').removeClass('disabled-input');  
            $('div.suburb-option-b').removeClass('disabled-input'); 
            $('div.postcode-option-b').removeClass('disabled-input');

            $("#unitlevel2").parent().removeClass('disabled-input');
        $("#number2").parent().removeClass('disabled-input');
        $("#street2").parent().removeClass('disabled-input');
            $('#pobox').parent().removeClass('disabled-input');
    }     
    });
    
    $(".chosen").select2({
      allowClear : true
    }).removeClass('form-control');
    
    $(".chosen_opt_a").select2({
      allowClear : true
    }).removeClass('form-control');


    $(".chosen-multi").select2({
      placeholder: "Select...",
    allowClear : true
  }).removeClass('form-control');

    var add_works = segments[segment_index-1]; // changes to 5 - when local and 4 -  on live site
    if(add_works == 'work_details'){
       $('select#worktype').select2('open');
    }
    
    $(".set-contact").on("click", function () {
      var contactType = $("#contact-types").val();
      var contactPersonItem = $("#contact-person-item").val();


      if(contactType && contactPersonItem){
        //alert(contactType+' - '+contactPersonItem+' - '+contactSetCount);



        var setValRawContactPerson = $("#contact-person-item").val().split("|");

        $('.contact-sets-area').append('<input type="hidden" name="contact-person-'+contactSetCount+'" class="contact-item-'+contactSetCount+'"  value="'+contactType+'|'+contactPersonItem+'" >');
        $('.contact-sets-area').append('<div onCLick="removeElem(\'contact-item-'+contactSetCount+'\')" class="contact-item-element" id="contact-item-'+contactSetCount+'">'+contactType+' - '+setValRawContactPerson[0]+' '+setValRawContactPerson[1] +'</div>');

        

        if($("#assigned-contact-impt").val() == ''){
          $("#assigned-contact-impt").val(contactSetCount);
        }else{
          $("#assigned-contact-impt").val($("#assigned-contact-impt").val()+','+contactSetCount);
        }


        contactSetCount++;
        
      }else{
        alert("Please Set Contact Type and Contact Person.");
      }

      $('#s2id_contact-person-item span#select2-chosen-10').text('Choose a Contact Person...');
      $("#contact-types").val('');
      $("select#contact-person-item").val(null);
    });

  $("input#company_name").focus();
    
    $(".set-add-contact").on("click", function () {
      var is_set_as_primary = 0;

        //alert(contactType+' - '+contactPersonItem+' - '+contactSetCount);
        $('.add-contact-area').append('<div class="item-form item-form-'+contactSetAddCount+'" ><div class="clearfix"></div> <div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix"><label for="contact_f_name_'+contactSetAddCount+'" class="col-sm-3 control-label">First Name*</label><div class="col-sm-9"><input type="text" class="form-control" id="contact_f_name_'+contactSetAddCount+'" placeholder="First Name" name="contact_f_name_'+contactSetAddCount+'" value=""></div></div><div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix"><label for="contact_l_name_'+contactSetAddCount+'" class="col-sm-3 control-label">Last Name*</label><div class="col-sm-9"><input type="text" class="form-control" id="contact_l_name_'+contactSetAddCount+'" placeholder="Last Name" name="contact_l_name_'+contactSetAddCount+'" value=""></div></div><div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix"><label for="gender_'+contactSetAddCount+'" class="col-sm-3 control-label">Gender*</label><div class="col-sm-9"><select name="contact_gender_'+contactSetAddCount+'"  class="form-control gender_add_set" id="gender_'+contactSetAddCount+'"><option value="Male">Male</option><option value="Female">Female</option></select></div></div><div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix"><label for="contact_email_'+contactSetAddCount+'" class="col-sm-3 control-label">Email*</label><div class="col-sm-9"><input type="email" class="form-control" id="contact_email_'+contactSetAddCount+'" placeholder="Email" name="contact_email_'+contactSetAddCount+'" value=""></div></div><div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix"><label for="contact_number_'+contactSetAddCount+'" class="col-sm-3 control-label">Office Contact</label><div class="col-sm-9"><div class="input-group"><span class="input-group-addon" id="area-code-text-'+contactSetAddCount+'"></span><input type="text" class="form-control contact_number_assign"  onchange="contact_number_assign(\'contact_number_'+contactSetAddCount+'\')" id="contact_number_'+contactSetAddCount+'" placeholder="Office Contact Number" name="contact_number_'+contactSetAddCount+'" value=""></div></div></div><div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix"><label for="mobile_number_'+contactSetAddCount+'" class="col-sm-3 control-label">Mobile</label><div class="col-sm-9"><input type="text" class="form-control mobile_number_assign mobile_number_assign"  onchange="mobile_number_assign(\'mobile_number_'+contactSetAddCount+'\')" id="mobile_number_'+contactSetAddCount+'" placeholder="Mobile Number" name="mobile_number_'+contactSetAddCount+'" value=""></div></div><div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix"><label class="col-sm-3 control-label" for="after_hours_'+contactSetAddCount+'">After Hours</label><div class="col-sm-9"><div class="input-group"><span class="input-group-addon" id="mobile-area-code-text-'+contactSetAddCount+'"></span><input type="text" value="" name="after_hours_'+contactSetAddCount+'" placeholder="After Hours Contact Number" onchange="contact_number_assign(\'after_hours_'+contactSetAddCount+'\')" id="after_hours_'+contactSetAddCount+'" class="form-control after_hours_assign"></div></div></div><div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix"><label for="contact_type_'+contactSetAddCount+'" class="col-sm-3 control-label">Contact Type</label><div class="col-sm-9"><select name="contact_type_'+contactSetAddCount+'" id="contact_type_'+contactSetAddCount+'" class="form-control"><option value="General">General</option><option value="Maintenance">Maintenance</option><option value="Accounts">Accounts</option><option value="Other">Other</option></select></div></div><div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix"><label class="col-sm-3 control-label" for="set_as_primary_'+contactSetAddCount+'">Set as Primary</label><input type="checkbox" name="set_as_primary_'+contactSetAddCount+'" id="set_as_primary_'+contactSetAddCount+'" class="set_as_primary" onclick="contact_set_primary(\'set_as_primary_'+contactSetAddCount+'\')" style="margin-top: 10px; margin-left: 5px;"></div><div class="col-md-6 col-sm-6 col-xs-12 m-bottom-10 clearfix"><div class="btn btn-warning pull-right" id="remove-form-_'+contactSetAddCount+'" onClick="removeFormAdd(\'item-form-'+contactSetAddCount+'\')">Remove Form</div></div><div class="clearfix"></div><hr /></div>');
        


        if($("#add-contact-impt").val() == ''){
          $("#add-contact-impt").val(contactSetAddCount);
        }else{
          $("#add-contact-impt").val($("#add-contact-impt").val()+','+contactSetAddCount);
        }
        $( "#contact_f_name_"+contactSetAddCount ).focus();


        $("#area-code-text-"+contactSetAddCount).text($("#areacode").val());
        $("#mobile-area-code-text-"+contactSetAddCount).text($("#areacode").val());


        contactSetAddCount++;

        $('input.set_as_primary').each(function(index, value){
          if($(this).is(':checked')){ is_set_as_primary = 1; }          
        });


        if(is_set_as_primary == 0){
          $('input.set_as_primary:first').click();
        }

    });


  $('.reset-form-data').click(function(){
    $("#assigned-contact-impt").val(null);
    $("#add-contact-impt").val(null);
    $('.add-contact-area').empty();
    $(".contact-sets-area").empty();
    contactSetCount = 1;
    contactSetAddCount = 1;
  });


  $('.edit-project').on('click', function(){
      if($('.project-form .form-control').prop("readonly")){
      $(this).val('Cancel Edit');     
      $('.project-form .form-control').each(function(){
        $(this).attr("readonly",false);
      });
      $('.save-project').removeClass('hidden').attr("disabled",false);
      $('#suburb').attr("disabled",false);
      $('#suburb_a').attr("disabled",false);
      $('#suburb_b').attr("disabled",false);
      $('#postcode_a').attr("disabled",false);
    }else{
      $(this).val('Edit project');      
      $('.project-form .form-control').each(function(){
        $(this).attr("readonly",true);
      });
      $('.save-project').addClass('hidden').attr("disabled",true);
      $('#suburb').attr("disabled",true);
      $('#suburb_a').attr("disabled",true);
      $('#suburb_b').attr("disabled",true);
      $('#postcode_a').attr("disabled",true);
    }
    $('#project_date').attr("readonly",true);
    $('state_a').attr("readonly",true); 
    $('#areacode').attr("readonly",true);     
  });
  
  
  var setname = $('.select2-default').text(); 
  //$(".chosen").select2("data", {id: "setname", text: setname}); hey!!!!!!!!!
  $('.edit-record').on('click', function(){
      if($('.company-form .form-control').prop("readonly")){
      $('.company-form .form-control').attr("readonly",false);
      $('.company-form .btn-success').attr("readonly",false);
      $(this).val('Cancel Edit');
      $('.btn-success').show().prop('type', 'submit');
      $('#suburb_a').attr("disabled",false);
      $('#suburb_b').attr("disabled",false);
      $('#postcode_a').attr("disabled",false);
      $('#postcode_b').attr("disabled",false);
      $('#type').attr("disabled",false);    
      $('#activity').attr("disabled",false);  
      $('#parent').attr("disabled",false);  
      $(".chosen").select2("readonly", false, "data", {id: "setname", text: setname});
      $("#contactperson").attr("disabled",false);   
    }else{
      $('.company-form .form-control').attr("readonly",true);
      $(this).val('Edit Record');
      $('.company-form .btn-success').attr("readonly",true);
      $('.btn-success').hide().prop('type', 'button');  
      $('#suburb_a').attr("disabled",true);
      $('#suburb_b').attr("disabled",true);
      $('#postcode_a').attr("disabled",true);
      $('#postcode_b').attr("disabled",true);
      $('#type').attr("disabled",true); 
      $('#activity').attr("disabled",true); 
      $('#parent').attr("disabled",true);
      $(".chosen").select2("readonly", true, "data", {id: "setname", text: setname});
      $("#contactperson").attr("disabled",true);
    }
    $('.state').attr("readonly",true);
    $('.areacode').attr("readonly",true);   
  });
  
  $('#add_contact').on('hide.bs.modal', function (e) {
    $('#contactperson').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
    $('#contact_f_name').val('').parent().parent().removeClass('has-error');
    $('#contact_l_name').val('').parent().parent().removeClass('has-error');
    $('#contact_email').val('').parent().parent().removeClass('has-error');
    $('#contact_number').val('').parent().parent().removeClass('has-error');
    $('#contact_company').val('').parent().parent().removeClass('has-error');
    $('#select2-chosen-2').empty();
    $('#gender').val('').removeAttr('selected').parent().parent().removeClass('has-error');
  });
  
  $('.add-contact-submit').click(function(){
    var contactDetail = new Array();
    var errorFormSub = 0;
    contactDetail['contact_f_name'] =  $('#contact_f_name').val();
    contactDetail['contact_l_name'] = $('#contact_l_name').val();
    contactDetail['gender'] = $('#gender').val();
    contactDetail['contact_email'] = $('#contact_email').val();
    contactDetail['contact_number'] = $('#contact_number').val();
    //contactDetail['contact_company'] = $('#contact_company').val();   
    
    //alert($('#select2-chosen-2').text());     
    for (var key in contactDetail){
      if (contactDetail.hasOwnProperty(key)){
          //alert(contactDetail[key]);          
          if(contactDetail[key]==''){
            $('#'+key).parent().parent().addClass('has-error');
            errorFormSub = 1;
          }else{
            $('#'+key).parent().parent().removeClass('has-error');
          }
        }
    }
    
    if(errorFormSub == 0){
      $('#add_contact').modal('hide');
      $("select#contactperson").append('<option selected="selected" value="'+contactDetail['contact_f_name']+'|'+contactDetail['contact_l_name']+'">'+contactDetail['contact_f_name']+' '+contactDetail['contact_l_name']+'</option>');
        
        /*
        var conName = contactDetail['contact_f_name']+' '+contactDetail['contact_l_name'];
        $(".chosen").select2("data", {id: "setname", text: conName},{allowClear : true});*/
    }
  });
  /* */
  var controller = 'company';
  
  //load_data_ajax
  function load_data_ajax(contactDetails){
    //alert(contactDetails);
    var val = contactDetails.split("|");
      $.ajax({
          'url' : base_url + controller + '/add_contact',
            'type' : 'POST', //the way you want to send data to your URL
            'data' : {'first_name' : val[0],'last_name':val[1],'gender':val[2],'email':val[3],'contact_number':val[4],'company':val[5] },
            'success' : function(data){ //probably this request will return anything, it'll be put in var "data"
              if(data){
                  //var valuesData = data.split("|");
                  //alert(data);
                  //state.disabled=false;
                  //state.val(valuesData[1]);
                  //state.readonly=true;
                  
                  //areacode.val(valuesData[2]);
                  $('.pop-test').html(data);
              }
          }
      });
  }
  /* */
    //$(".chosen").on("change", function(e) { alert($(this).val()); }); this is working select callbak!
    
 
$('select.all_company_project').on("change", function(e) {

  var all_company_project_raw = $(this).val().split("|");
  var all_company_project_id = all_company_project_raw[0];
  $('.copy_work_project_id a.select2-choice .select2-chosen').text('Select Existing Project');

  $('select.chosen_opt_a').append(options);

  if(all_company_project_id!=''){
    $('.chosen_opt_a option').not('.pg_id_'+all_company_project_id).detach();
  }
  $('.chosen_opt_a').select2();


});

   
 //$('#projectTable').table.column(1).search( val ? '^test$' : val, true, false ).draw();
   // table.column(1).data().unique().sort().each( function ( d, j ) { select.append( '<option value="'+d+'">'+d+'</option>' ); } );
   

//dynamic_value_ajax
  function dynamic_value(setVal,postVal,controlerFunc,classLocation){
      $.ajax({
          'url' : base_url+controlerFunc,
            'type' : 'POST',
            'data' : {postVal : setVal },
            'success' : function(data){
              var divLocation = $(classLocation);
                if(data){
                  divLocation.html(data);
              }
          }
      });
   }
   //dynamic_value_ajax

    $("#work-type").on("change", function(e) {
      var valuesData = $(this).val().split("|");
      var setVal = valuesData[2];
      dynamic_value(setVal,'postVal','projects/sub_job_cat_list','.work-desc');
    });


  
  $('.remove-job').click(function(){
    var removeId = $(this).attr('id');
    dynamic_value(removeId,'postVal','projects/removeWork','.ignore'); //note the .ignore means nothing
    $(this).parent().parent().remove();
  });

  $('.set-company').click(function(){
    var typeId = $(this).attr('id');
    //alert(typeId);
    dynamic_value(typeId,'postVal','company/select_contractor_supplier','.select-comp-type'); 
  });

  $('.sub-nav-bttn').click(function(){
    var nav_trgt = $(this).attr('id');

    $('.project-details-update').hide();
    $('.quotation-view').hide();
    $('.'+nav_trgt).show();
  });


  $("#company_name").on("change", function(e){
    $("#account-name").val($(this).val());
  });

    $('#work_notes').keyup(function() {
      var lines_left = 10;
      var vals = $("textarea").val().split(/\r\n|\r|\n/)
      .filter($.trim)
      .map($.trim);

      lines_left = lines_left - vals.length;

      $(".lines_left").text(lines_left);
    });


 $('.datepicker').datepicker()
});

$("#pl_expiration").keyup(function(){
  if ($(this).val().length == 2){
    $(this).val($(this).val() + "/");
  }else if ($(this).val().length == 5){
    $(this).val($(this).val() + "/");
  }
});

$("#wc_expiration").keyup(function(){
  if ($(this).val().length == 2){
    $(this).val($(this).val() + "/");
  }else if ($(this).val().length == 5){
    $(this).val($(this).val() + "/");
  }
});

$("#ip_expiration").keyup(function(){
  if ($(this).val().length == 2){
    $(this).val($(this).val() + "/");
  }else if ($(this).val().length == 5){
    $(this).val($(this).val() + "/");
  }
});

window.comp_abn_blur = function(){
    var type = $("#type").val();
    if(type == ""){
      alert("Please select type first.");
      $("#abn").val("");
    }else{
      if($("#abn").val() == ''){
        //$('.is_wip').prop('checked', false);
        $("#acn").val('');
      }else{

        var type_arr = type.split("|");
        type = type_arr[1];

        var abn = $("#abn").val().replace(/[^\d]/g, "");

        $.post(baseurl+"company/check_company_exist",
        {
          abn: abn,
          type: type
        },
        function(result){
          if(result == 1){
            alert("ABN already exist!");
            location.reload();
          }else{
            var new_abn_val = abn.substring( -2,2)+' '+abn.substring( 2,5)+' '+abn.substring( 5,8)+' '+abn.substring( 8,11);
            $("#abn").val(new_abn_val);

            var acn_val = abn.substring( 2,5)+' '+abn.substring( 5,8)+' '+abn.substring( 8,11);

            $("#acn").val(acn_val);
          }
        });
      }
    }
}

window.comp_type_change = function(){
  var comp_type = 0;
  var comp_type_name = "";
  var url = $(location).attr('href').split("/").splice(0, 8).join("/");
  var segments = url.split( '/' );
  var comp_id = segments[segment_index];
  $.post(baseurl+"company/check_company_type",
  {
    comp_id: comp_id
  },
  function(result){
     
    comp_type = result;
    switch(comp_type){
      case '1':
        comp_type_name = "Client";
        break;
      case '2':
        comp_type_name = "Contractor";
        break;
      case '3':
        comp_type_name = "Supplier";
        break;
    }

    var abn = $("#abn").val();
    var type = $("#type").val();
    var type_arr = type.split( '|' );
    var type = type_arr[1];

    if(comp_type !== type){
      $.post(baseurl+"company/check_company_exist",
      {
        abn: abn,
        type: type
      },
      function(result){

        if(result == 1){
          alert("Selected Company Type has the same ABN. Page will refresh");
          var val_comp_type = comp_type_name+"|"+comp_type;
          $("select#type").val(val_comp_type);
          location.reload();
        }
      }); 
    }
  }); 
  
  
}

function removeElem(value){
  $("."+value).remove();
  $("#"+value).remove();


  var assignedContactImpt = $("#assigned-contact-impt").val();

  var newAssignVal = assignedContactImpt.replace(value.substring(13),'');
  $("#assigned-contact-impt").val(newAssignVal);


}

function contact_set_primary(value){
  //$('.set_as_primary')
  $('.set_as_primary').prop('checked', false);  
  $('#'+value).prop('checked', true);
}


function removeFormAdd(value){
  $('.'+value).remove();

  var assignedContactImpt = $("#add-contact-impt").val();

  var newAssignVal = assignedContactImpt.replace(value.substring(10),'');
  $("#add-contact-impt").val(newAssignVal); 
}

function contact_number_assign(here){
  //var curVal = $("#"+here).val().replace(/\s/g, '');

  var curVal = $("#"+here).val().replace(/[^\d]/g, '');

  var valhere = curVal.substring( 0,4)+' '+curVal.substring(4,8)+' '+curVal.substring(8,12)+' '+curVal.substring(12,16)+' '+curVal.substring(16,20);

  var newString = valhere.replace(/\s+/g,' ').trim();

  $("#"+here).val(newString);
}

function mobile_number_assign(here){
//  var curVal = $("#"+here).val().replace(/\s/g, '');

  var curVal = $("#"+here).val().replace(/[^\d]/g, '');
  var valhere = curVal.substring( 0,4)+' '+curVal.substring(4,7)+' '+curVal.substring(7,10)+' '+curVal.substring(10,13)+' '+curVal.substring(13,16);
  var newString = valhere.replace(/\s+/g,' ').trim();
  $("#"+here).val(newString);

}

function toTitleCase(str){
    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}


function showProjectCmmnts(project_id){
  $.post(baseurl+"projects/list_project_comments",{ 
    'project_id': project_id
  },function(result){
    $('.prj_cmmnt_area').empty().append(result);
  });
}

function set_suburb_shopping_center(){
    var brand_shopping_center = $('select.brand_shopping_center').val();
    var select_focus = $('select.select-focus').val();
    var myVal = select_focus+'|'+brand_shopping_center;
    ajax_data(myVal,'projects/set_jurisdiction_shoping_center_by_name_and_sate','.shopping_center_suburb');
}
