$.fn.dataTableExt.oApi.fnResetAllFilters = function (oSettings, bDraw/*default true*/) {
        for(iCol = 0; iCol < oSettings.aoPreSearchCols.length; iCol++) {
                oSettings.aoPreSearchCols[ iCol ].sSearch = '';
        }
        oSettings.oPreviousSearch.sSearch = '';
 
        if(typeof bDraw === 'undefined') bDraw = true;
        if(bDraw) this.fnDraw();
}

var url = $(location).attr('href').split("/").splice(0, 7).join("/");
var segments = url.split( '/' );
var segmentlength = segments.length;
var company = segments[3].replace("#", ""); // Change to 3 when live / 4 for local

if(company == 'company'){
    var companyTable = $('#companyTable').dataTable({
        "iDisplayLength": 20,
        "aLengthMenu": [[13, 20, 25, 50, -1], [13, 20, 25, 50, "All"]],
        "aoColumnDefs": [{ "bVisible": false, "aTargets":[4] }]
        
    });
}else{
    var companyTable = $('#companyTable').dataTable({
        "iDisplayLength": 20,
        "aLengthMenu": [[13, 20, 25, 50, -1], [13, 20, 25, 50, "All"]]
    });
}


$('#dataTable_noCustom').dataTable({
    "iDisplayLength": 7,
    "aLengthMenu": [[7, 14, 21, 28, -1], [7, 14, 21, 28, "All"]]
});

$('#shoppingCenterTable').dataTable({
    "iDisplayLength": 20,
    "aLengthMenu": [[13, 20, 25, 50, -1], [13, 20, 25, 50, "All"]]
});

$('#shoppingCenterTable_prj').dataTable({
    "iDisplayLength": 6,
    "aLengthMenu": [[6, 12, 18, 24, -1], [6, 12, 18, 24, "All"]]
});

$('#userLogsTble').dataTable({
    "iDisplayLength": 13,
    "aLengthMenu": [[13, 20, 25, 50, -1], [13, 20, 25, 50, "All"]],
    "order": [[ 6, "desc" ]],
   "aoColumnDefs": [{ "bVisible": false, "aTargets":[6] },{ "bVisible": false, "aTargets":[1] },{"targets": 3,"orderable": false} ]
});

var centerTable_prj = $('#shoppingCenterTable_prj').dataTable();
centerTable_prj.fnFilter('*','3');



$('#po_table').dataTable({
    "iDisplayLength": 13,
    "aLengthMenu": [[13, 20, 25, 50, -1], [13, 20, 25, 50, "All"]],
    "aoColumns" : [
            { sWidth: '5%' },
            { sWidth: '5%' },
            { sWidth: '5%' },
            { sWidth: '15%' },
            { sWidth: '15%' },
            { sWidth: '18%' },
            { sWidth: '5%' },
            { sWidth: '12%' },
            { sWidth: '10%' },
            { sWidth: '5%' },
            { sWidth: '4%' },
            { sWidth: '1%' }
        ],
         "aoColumnDefs":[{"targets": 6,"orderable": false},{"targets": 2,"orderable": false},{ "bVisible": false, "aTargets":[11] }]
});


$('#invoice_table').dataTable({
    "iDisplayLength": 13,
    "aLengthMenu": [[13, 20, 25, 50, -1], [13, 20, 25, 50, "All"]],
         "aoColumnDefs":[{"targets": 5,"orderable": false}]
});


$('#invoice_paid_table').dataTable({
    "iDisplayLength": 13,
    "aLengthMenu": [[13, 20, 25, 50, -1], [13, 20, 25, 50, "All"]],
         "aoColumnDefs":[{"targets": 4,"orderable": false}]
});


$('#reconciled_list_table').dataTable({
    "iDisplayLength": 13,
    "aLengthMenu": [[13, 20, 25, 50, -1], [13, 20, 25, 50, "All"]],
    "aoColumns" : [
            { sWidth: '5%' },
            { sWidth: '5%' },
            { sWidth: '5%' },
            { sWidth: '15%' },
            { sWidth: '15%' },
            { sWidth: '18%' },
            { sWidth: '5%' },
            { sWidth: '12%' },
            { sWidth: '10%' },
            { sWidth: '5%' },
            { sWidth: '4%' },
            { sWidth: '1%' }
        ],
         "aoColumnDefs":[{"targets": 6,"orderable": false},{"targets": 2,"orderable": false},{ "bVisible": false, "aTargets":[11] }]
});


$('#worksTable').dataTable({
	"iDisplayLength": 13,
    "aLengthMenu": [[13, 20, 25, 50, -1], [13, 20, 25, 50, "All"]]
});

$('#referralsTable').dataTable({
    "iDisplayLength": 13,
    "aLengthMenu": [[13, 20, 25, 50, -1], [13, 20, 25, 50, "All"]]
});

$('#sitelabourTable').dataTable({
    paging: false,
    //"iDisplayLength": 13,
    //"aLengthMenu": [[13, 20, 25, 50, -1], [13, 20, 25, 50, "All"]],
    "order": []
});

$('#pending_leaves_tbl').dataTable({
    "iDisplayLength": 13,
    // "order": [[ 0, "desc" ]],
    "aoColumnDefs": [
                {
                    "orderable": false,
                    "targets": [ 0, 2, 4 ]
                }
            ],
    "aLengthMenu": [[13, 20, 25, 50, -1], [13, 20, 25, 50, "All"]]
});
$('#approved_leaves_tbl').dataTable({
    "iDisplayLength": 13,
    "order": [[ 10, "desc" ]],
    "aoColumnDefs": [
                {
                    "orderable": false,
                    "targets": [ 0, 2, 4, 8 ]
                },
                {
                    "visible": false,
                    "targets": [ 10 ]
                },
            ],
    "aLengthMenu": [[13, 20, 25, 50, -1], [13, 20, 25, 50, "All"]]
});
$('#unapproved_leaves_tbl').dataTable({
    "iDisplayLength": 13,
    // "order": [[ 0, "desc" ]],
    "aoColumnDefs": [
                {
                    "orderable": false,
                    "targets": [ 0, 2, 4, 7, 8 ]
                }
            ],
    "aLengthMenu": [[13, 20, 25, 50, -1], [13, 20, 25, 50, "All"]]
});
$('#pending_by_superv_tbl').dataTable({
    "iDisplayLength": 13,
    "order": [[ 0, "desc" ]],
    "aLengthMenu": [[13, 20, 25, 50, -1], [13, 20, 25, 50, "All"]]
});
$('#approved_by_superv_tbl').dataTable({
    "iDisplayLength": 13,
    "order": [[ 1, "asc" ]],
    "aLengthMenu": [[13, 20, 25, 50, -1], [13, 20, 25, 50, "All"]]
});

$('#onboardTable').dataTable({
    "iDisplayLength": 13,
    "aLengthMenu": [[13, 20, 25, 50, -1], [13, 20, 25, 50, "All"]]
});

$('#declinedOnboardTable').dataTable({
    "iDisplayLength": 13,
    "aLengthMenu": [[13, 20, 25, 50, -1], [13, 20, 25, 50, "All"]]
});

var variationTable = $('#variationTable').dataTable({
	"iDisplayLength": 13,
    "aLengthMenu": [[13, 20, 25, 50, -1], [13, 20, 25, 50, "All"]]
});
   
var table = $('#projectTable').dataTable({
	"iDisplayLength": 20,
    "aLengthMenu": [[20, 25, 50,75, -1], [ 20, 25, 50,75, "All"]],
    "order": [[ 0, "desc" ]],
    "aoColumns" : [
            { sWidth: '10%' },
            { sWidth: '30%' },
            { sWidth: '30%' },
            { sWidth: '10%' },
            { sWidth: '10%' },
            { sWidth: '10%' }
        ],
   "aoColumnDefs": [{ "bVisible": false, "aTargets":[6] },{ "bVisible": false, "aTargets":[7] },{"targets": 4,"orderable": false}] 
});

var wipTable = $('#wipTable').dataTable({ 
	"order": [[ 0, "desc" ]],
	"scrollCollapse": true,
	"paging":         false,
	"scrollY":        "700px",
    "aoColumnDefs": [{ "bVisible": false, "aTargets":[7] },{ "bVisible": false, "aTargets":[9] },{"targets": 4,"orderable": false},{"targets": 5,"orderable": false},{"targets": 6,"orderable": false}]   //add_to revsion update


});

 


$('#dataTable_development').dataTable({
    "iDisplayLength": 20,
    "aLengthMenu": [[20, 30, 40, 50, -1], [20, 30, 40, 50, "All"]],   
    "aoColumnDefs":[
        {"targets": 4,"orderable": false} ,{ "bVisible": false, "aTargets":[7] }]
});


function setSortDev(valSelect){
   //alert('test');
    var table = $('#dataTable_development').DataTable();
    var sort = valSelect;


    if(sort == 1){
        table.order( [ 7, 'asc' ] ).draw();
    }

    if(sort == 2){
        table.order( [ 7, 'desc' ] ).draw();
    }

}





 
$(".select-client-tbl").on("change", function(e) { 
 	//alert($(this).val());
 	var table = $('#projectTable').dataTable();
 	var search = $(this).val();
 	var val = search.split("|");
 	//alert(val[0]);
 	var itemSearch = val[0];
 	//table.column(2).search(itemSearch).draw();
 	table.fnFilter(itemSearch,'2');
});


 $('.select-personal').on("change", function(e) { 
    var search = $(this).val();
    var table = $('#projectTable').dataTable();

    if(search == 'ORD'){
        table.fnResetAllFilters();
    }else{
        table.fnFilter(search,'6');
    }
 });





 $(".select-status-tbl").on("change", function(e) { 

    var table = $('#projectTable').dataTable();
    //alert($(this).val());
    if($(this).val() == ''){
        table.fnResetAllFilters();
    }else{
        var search = $(this).val();       
        table.fnFilter(search,'7');
    }
 });





/*
$('#projectTable_filter input').replaceWith('<input type="text" placeholder="Seach" class="form-control input-sm prj_gen_seearch">');




$('#projectTable_filter input.prj_gen_seearch').keyup( function(){
    var search = $(this).val();
    $('#reconciled_list_table_filter input').val(search);
    var table = $('#projectTable').dataTable();
    table.fnFilter(search);
});
*/


 $('.userLogsBtn').click(function(){

    var table = $('#userLogsTble').dataTable();

    var project_number = $('#project_number').val();
    var type = $('select#type').val();
    var user = $('select#user').val();

    table.fnFilter(project_number,'0');
    table.fnFilter(type,'1');
    table.fnFilter(user,'5');


    var filter_date_a = $('#filter_date_a').val().split('/');
    var date_a = new Date(filter_date_a[1]+'/'+filter_date_a[0]+'/'+filter_date_a[2]).getTime();
    date_a = date_a/1000;

    var filter_date_b = $('#filter_date_b').val().split('/');
    var date_b = new Date(filter_date_b[1]+'/'+filter_date_b[0]+'/'+filter_date_b[2]).getTime();
    date_b = date_b/1000;

    if(filter_date_a != '' && filter_date_b != ''){

        var table = $('#userLogsTble').DataTable();

        jQuery.fn.dataTableExt.afnFiltering.push(
            function( oSettings, aData, iDataIndex ) {
                var iColumn = 6;
                var iMin = date_a;
                var iMax = date_b;

                var iVersion = aData[iColumn] == "-" ? 0 : aData[iColumn]*1;
                if ( iMin === "" && iMax === "" )
                {
                    return true;
                }
                else if ( iMin === "" && iVersion < iMax )
                {
                    return true;
                }
                else if ( iMin < iVersion && "" === iMax )
                {
                    return true;
                }
                else if ( iMin < iVersion && iVersion < iMax )
                {
                    return true;
                }
                return false;
            }
            );

        table.draw();

        

    }



 });

/*
 $('#filter_invoice_table').click(function(){
    var table = $('#invoice_table').dataTable();

    var invoice_project_number = $('#invoice_project_number').val();
    var invoice_project_name = $('#invoice_project_name').val();
    var invoice_progress_claim = $('#invoice_progress_claim').val();
    var invoiced_date_from = $('#invoiced_date_from').val();
    var invoiced_date_to = $('#invoiced_date_to').val();
    var invoice_client_name = $('#invoice_client_name').val();

    if(invoice_project_number != ''){     
        table.fnFilter(invoice_project_number,'0');
    }

    if(invoice_project_name != ''){     
        table.fnFilter(invoice_project_name,'1');
    }

    if(invoice_progress_claim != ''){     
        table.fnFilter(invoice_progress_claim,'2');
    }

    if(invoice_client_name != ''){     
        table.fnFilter(invoice_client_name,'3');
    }

 });
*/




//next('<input type="text" placeholder="Seach" class="form-control input-sm prj_gen_seearch">');

 // .replaceWith('<input type="text" placeholder="Seach" class="form-control input-sm prj_gen_seearch">');

$('.po-area #po_table_filter').prepend($('.outstading_pm').html());
$('.po-area #reconciled_list_table_filter').prepend($('.outstading_pm').html());

$('#shoppingCenterTable_filter').prepend($('.state_select_list').html());
$('#shoppingCenterTable_prj_filter').append($('.state_select_list').html());


$('#po_table_filter select#outstading_pm').on("change", function(e) { 
    var companyTable = $('#po_table').dataTable();
    var searchA = $(this).val();
    companyTable.fnFilter(searchA,'8');
});

$('#reconciled_list_table_filter select#outstading_pm').on("change", function(e) { 
    var companyTable = $('#reconciled_list_table').dataTable();
    var searchA = $(this).val();
    companyTable.fnFilter(searchA,'8');
});







$('.po-area #po_table_filter').prepend($('.cpo_date_filter').html());
$('.po-area #reconciled_list_table_filter').prepend($('.cpo_date_filter').html());


$('#po_table_filter select#cpo_date_filter').on("change", function(e) { 
    var companyTable = $('#po_table').DataTable();
    var sort_val = $(this).val();
    companyTable.order( [ 11, sort_val ] ).draw();
});

$('#reconciled_list_table_filter select#cpo_date_filter').on("change", function(e) { 
    var companyTable = $('#reconciled_list_table').DataTable();
    var sort_val = $(this).val();
    companyTable.order( [ 11, sort_val ] ).draw();
});


/*
$('.add_shopping_center_project').click(function(){
    setTimeout(function(){
        $("#shoppingCenterTable_prj").dataTable().fnDestroy();
        $("#shoppingCenterTable_prj").dataTable();
        $('#shoppingCenterTable_prj_filter').prepend($('.state_select_list').html());
    },500);
});
*/

$('select.select_state_shopping_center').on("change", function(e) { 
    var companyTable = $('#shoppingCenterTable_prj').dataTable();
    var search_raw = $(this).val();
    var search_arr = search_raw.split('|');
    companyTable.fnFilter(search_arr['1'],'3');
});

$('select.select_state_shopping_center').on("change", function(e) { 
    var companyTable = $('#shoppingCenterTable').dataTable();
    var search = $(this).val();
    companyTable.fnFilter(search,'3');
});

$('#filter_wip_table').click(function(){

    var wipTable = $('#wipTable').dataTable();
    var searchA = $('.select-client-tbl').val();
    //alert(val[0]);

    if(searchA!=''){
        var valA = searchA.split("|");
        var itemSearchA = valA[0];
        wipTable.fnFilter(itemSearchA,'2');
    }



    var itemSearchB_raw = $('select.select-pm-tbl').val();

    var itemSearchB = itemSearchB_raw.split('|');

    if(itemSearchB_raw!=''){
        wipTable.fnFilter(itemSearchB['0'],'3');
    }


    var catValueRaw = $("select#select-cat-tbl").val().toString();

    if(catValueRaw!=''){
        var itemSearchCat = catValueRaw.replace(/,/g , "|");
        if(itemSearchCat!=''){
            wipTable.fnFilter(itemSearchCat,'7', true, false);
        }
    }


    if($('#finish_date_start').val()!=''){
            $.fn.dataTable.ext.search.push(
                function( settings, data, dataIndex ) {
                    var endDateArr = $('#finish_date_start').val().split('/');
                    var dateColmnArr = data[6].split('/');

                    var endDate = new Date(endDateArr[1]+'/'+endDateArr[0]+'/'+endDateArr[2]).getTime();
                    var dateColmn = new Date(dateColmnArr[1]+'/'+dateColmnArr[0]+'/'+dateColmnArr[2]).getTime();

                    if ( ( isNaN( endDate )  ) || 
                        ( endDate > dateColmn ) )
                    {
                        return false;
                    }
                    return true; 
                }
                );   
           // wipTable.fnFilter();
            wipTable.fnFilter();

        }else{
            wipTable.fnFilter();
        }



 


        if($('#finish_date').val()!=''){
            $.fn.dataTable.ext.search.push(
                function( settings, data, dataIndex ) {
                    var endDateArr = $('#finish_date').val().split('/');
                    var dateColmnArr = data[6].split('/');

                    var endDate = new Date(endDateArr[1]+'/'+endDateArr[0]+'/'+endDateArr[2]).getTime();
                    var dateColmn = new Date(dateColmnArr[1]+'/'+dateColmnArr[0]+'/'+dateColmnArr[2]).getTime();

                    if ( ( isNaN( endDate )  ) || 
                        ( endDate < dateColmn ) )
                    {
                        return false;
                    }
                    return true; 
                }
                );   
            wipTable.fnFilter();

        }else{
            wipTable.fnFilter();
        }

        if($('#cost_total').val().length > 0 && $('#cost_total').val() != ''){
            $.fn.dataTable.ext.search.push(
                function( settings, data, dataIndex ) {
                    var cost_total = $('#cost_total').val();
                    var cost_range = data[8];

                    cost_total = parseInt(cost_total.replace(',', ''));
                    cost_range = parseInt(cost_range.replace(',', ''));

                    if (cost_total>=cost_range){
                        return true;
                    }
                    return false;
                }
            );
            wipTable.fnFilter();
      }

        else{
            wipTable.fnFilter();
        }



        var wip_sort = $('select.wip_sort').val();
        var wipTableSort = $('#wipTable').DataTable();

        if(wip_sort == 'clnt_asc'){
            wipTableSort.order( [ 2, 'asc' ] ).draw();
        }else if(wip_sort == 'clnt_desc'){
            wipTableSort.order( [ 2, 'desc' ] ).draw();
        }else if(wip_sort == 'fin_d_asc'){
            wipTableSort.order( [ 9, 'asc' ] ).draw();
        }else if(wip_sort == 'fin_d_desc'){
            wipTableSort.order( [ 9, 'desc' ] ).draw();
        }else if(wip_sort == 'prj_num_asc'){
            wipTableSort.order( [ 0, 'asc' ] ).draw();
        }else if(wip_sort == 'prj_num_desc'){
            wipTableSort.order( [ 0, 'desc' ] ).draw();
        }else { $order_q = ''; }

});


$('#po_table_filter input.input-sm').keyup( function(){
    var search = $(this).val();
    $('#reconciled_list_table_filter input').val(search);
    var reconciled_list_table = $('#reconciled_list_table').dataTable();
    reconciled_list_table.fnFilter(search);
});

$('#reconciled_list_table_filter input.input-sm').keyup( function(){
    var search = $(this).val();
    $('#po_table_filter input').val(search);
    var po_table = $('#po_table').dataTable();
    po_table.fnFilter(search);
});




$('#invoice_table_filter input.input-sm').keyup( function(){
    var search = $(this).val();
    $('#invoice_paid_table_filter input').val(search);
    var invoice_paid_table = $('#invoice_paid_table').dataTable();
    invoice_paid_table.fnFilter(search);

    var exGst = 0;
    var exGst_b = 0;

    $('.outsdng').each(function(){
        exGst = parseFloat(exGst) + parseFloat(removeCommas($(this).text()));
        exGst = exGst.toFixed(2);
    });

    $('.outsding-b').each(function(){
        exGst_b = parseFloat(exGst_b) + parseFloat(removeCommas($(this).text()));
        exGst_b = exGst_b.toFixed(2);
    });


    $('.total-invoiced-head').text(numberWithCommas(exGst));
    $('.total-outstanding-head').text(numberWithCommas(exGst_b));


});

$('#invoice_paid_table_filter input.input-sm').keyup( function(){
    var search = $(this).val();
    $('#invoice_table_filter input').val(search);
    var invoice_table = $('#invoice_table').dataTable();
    invoice_table.fnFilter(search);
});


$("#complete_sort").on("click", function() { 
    var companyTable = $('#companyTable').dataTable();
    //alert($(this).val());
        var search = '1';       
        companyTable.fnFilter(search,'4');
    
});

$("#incomplete_sort").on("click", function() { 
    var companyTable = $('#companyTable').dataTable();
    //alert($(this).val());
        var search = '0';       
        companyTable.fnFilter(search,'4');
    
});

/*

    $('#cost_total').keyup( function() {

    	$(this).keyup( function() {

    	var wipTable = $('#wipTable').dataTable();
    	var itemSearch = $(this).val(); 
    	wipTable.fnFilter(itemSearch,'5');


    	});

    	
 		//wipTable.fnFilter(itemSearch);
    } );
*/
 

   





$('#dataTable_noCustom_dnotes').dataTable({
    "iDisplayLength": 20,
    "aLengthMenu": [[20, 30, 40, 50, -1], [20, 30, 40, 50, "All"]],   
        "aoColumnDefs": [{ "bVisible": false, "aTargets":[0] }]
});

var table = $('#dataTable_development').dataTable();
table.fnFilter('Outstanding','3');

$("select#select-status-dnotes-tbl").on("change", function(e) { 
    var table = $('#dataTable_development').dataTable();
    var search = $(this).val(); 
    table.fnFilter(search,'3');
}); 


 


$('#dataTable_noCustom_dnotes_bugs').dataTable({
    "iDisplayLength": 20,
    "aLengthMenu": [[20, 30, 40, 50, -1], [20, 30, 40, 50, "All"]],   
    "aoColumnDefs": [
    { "bVisible": false, "aTargets":[0] },
    {"targets": 4,"orderable": false},
    {"bVisible": false, "aTargets":[7] }

    ],


});

var table = $('#dataTable_noCustom_dnotes_bugs').dataTable();
table.fnFilter('Outstanding','3');

$("select#select-status-dnotes-tbl").on("change", function(e) { 
    var table = $('#dataTable_noCustom_dnotes_bugs').dataTable();
    var search = $(this).val(); 
    table.fnFilter(search,'3');
}); 

$('#contacts_tbl').dataTable({
    "iDisplayLength": 20,
    "order": [[ 1, "asc" ]],
    "aLengthMenu": [[13, 20, 25, 50, -1], [13, 20, 25, 50, "All"]],
    "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false
            }]
});

$('.contacts_area #contacts_tbl_filter').prepend($('.filter_contacts').html());

$('#contacts_tbl_filter select#filter_contacts').on("change", function(e) { 
    var contactsTable = $('#contacts_tbl').dataTable();
    var searchA = $(this).val();
    contactsTable.fnFilter(searchA,'0');
});

$('#focus_contacts_tbl').dataTable({
    "iDisplayLength": 20,
    "aLengthMenu": [[13, 20, 25, 50, -1], [13, 20, 25, 50, "All"]]
});

$('.focus_area #focus_contacts_tbl_filter').prepend($('.generate_focus_contacts').html());

$('#focus_contacts_tbl_filter #gen_btn').on("click", function(e) { 

    var s = window.location.href;
    var n = s.indexOf('#');
    s = s.substring(0, n != -1 ? n : s.length);

    if ($('#gen_type').val() == 0){
     window.open(s+'/generate_pdf_contact_list', '_blank');
    } else {
     window.open(s+'/generate_csv_contact_list', '_blank');
    }
});

var po_wip_join = $('#po_wip_join').dataTable({ 
    "order": [[ 6, "asc" ]],
    "scrollCollapse": true,
    "paging":         false,
    "scrollY":        "700px",
    "aoColumnDefs": [{"targets": 2,"orderable": false},{"targets": 3,"orderable": false},{ "bVisible": false, "aTargets":[5] },{ "bVisible": false, "aTargets":[6] }]   //add_to revsion update //{ "bVisible": false, "aTargets":[7] },{ "bVisible": false, "aTargets":[9] },{"targets": 4,"orderable": false},{"targets": 5,"orderable": false},{"targets": 6,"orderable": false}

});


function setSortJoinTbl(valSelect){
   //alert('test');
    var table = $('#po_wip_join').DataTable();
    var sort_val = valSelect.split('_');

    if(sort_val != ''){
        var colmn = sort_val[0];
        var ordr = sort_val[1];
        table.order( [ colmn, ordr ] ).draw();
    }
}