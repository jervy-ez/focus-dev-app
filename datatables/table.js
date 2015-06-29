$.fn.dataTableExt.oApi.fnResetAllFilters = function (oSettings, bDraw/*default true*/) {
        for(iCol = 0; iCol < oSettings.aoPreSearchCols.length; iCol++) {
                oSettings.aoPreSearchCols[ iCol ].sSearch = '';
        }
        oSettings.oPreviousSearch.sSearch = '';
 
        if(typeof bDraw === 'undefined') bDraw = true;
        if(bDraw) this.fnDraw();
}

$('#companyTable').dataTable({
	"iDisplayLength": 13,
    "aLengthMenu": [[13, 20, 25, 50, -1], [13, 20, 25, 50, "All"]]
});


$('#po_table').dataTable({
    "iDisplayLength": 13,
    "aLengthMenu": [[13, 20, 25, 50, -1], [13, 20, 25, 50, "All"]]
});


$('#reconciled_list_table').dataTable({
    "iDisplayLength": 13,
    "aLengthMenu": [[13, 20, 25, 50, -1], [13, 20, 25, 50, "All"]]
});


$('#worksTable').dataTable({
	"iDisplayLength": 13,
    "aLengthMenu": [[13, 20, 25, 50, -1], [13, 20, 25, 50, "All"]]
});

var variationTable = $('#variationTable').dataTable({
	"iDisplayLength": 13,
    "aLengthMenu": [[13, 20, 25, 50, -1], [13, 20, 25, 50, "All"]]
});
   
var table = $('#projectTable').dataTable({
	"iDisplayLength": 13,
    "aLengthMenu": [[13, 20, 25, 50, -1], [13, 20, 25, 50, "All"]],
    "order": [[ 0, "desc" ]],
   "aoColumnDefs": [{ "bVisible": false, "aTargets":[6] }] 
});

var wipTable = $('#wipTable').dataTable({ 
	"order": [[ 0, "desc" ]],
	"scrollCollapse": true,
	"paging":         false,
	"scrollY":        "500px",
    "aoColumnDefs": [{ "bVisible": false, "aTargets":[7] }] 
});


 
 $(".select-client-tbl").on("change", function(e) { 
 	//alert($(this).val());
 	var table = $('#projectTable').dataTable();
 	var search = $(this).val();
 	var val = search.split("|");
 	//alert(val[0]);
 	var itemSearch = val[0];
 	//table.column(2).search(itemSearch).draw();
 	table.fnFilter(itemSearch);
});


 $('.select-personal').on("change", function(e) { 
    var search = $(this).val();

    if(search == 'ORD'){
        table.fnResetAllFilters();
    }else{
        table.fnFilter(search,'6');
    }
 });


 $(".select-arr-tbl").on("change", function(e) { 

 	var table = $('#projectTable').dataTable();
 	//alert($(this).val());
 	if($(this).val() == 'asc'){
 		//table.fnSort( [ [4,'asc'] ] ); 		
 		table.fnFilter('Unset','4');
 	}else if($(this).val() == 'desc'){
 		//table.fnSort( [ [4,'desc'] ] );
 		table.fnFilter('/','4');
 	}else{
    	table.fnResetAllFilters();
 	}
 });




$('.po-area #po_table_filter').prepend($('.outstading_pm').html());
$('.po-area #reconciled_list_table_filter').prepend($('.outstading_pm').html());


$('#po_table_filter select#outstading_pm').on("change", function(e) { 
    var companyTable = $('#po_table').dataTable();
    var searchA = $(this).val();
    companyTable.fnFilter(searchA,'7');
});

$('#reconciled_list_table_filter select#outstading_pm').on("change", function(e) { 
    var companyTable = $('#reconciled_list_table').dataTable();
    var searchA = $(this).val();
    companyTable.fnFilter(searchA,'7');
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



    var itemSearchB = $('select.select-pm-tbl').val(); 
    if(itemSearchB!=''){
        wipTable.fnFilter(itemSearchB,'3');
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

                    var endDate = new Date(endDateArr[1]+'/'+endDateArr[0]+'/'+endDateArr[2]).getTime() / 1000;
                    var dateColmn = new Date(dateColmnArr[1]+'/'+dateColmnArr[0]+'/'+dateColmnArr[2]).getTime() / 1000;

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

                    var endDate = new Date(endDateArr[1]+'/'+endDateArr[0]+'/'+endDateArr[2]).getTime() / 1000;
                    var dateColmn = new Date(dateColmnArr[1]+'/'+dateColmnArr[0]+'/'+dateColmnArr[2]).getTime() / 1000;

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
 

   






