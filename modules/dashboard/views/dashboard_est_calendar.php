
<!-- 
<link rel="stylesheet" href="<?php echo base_url(); ?>calendar_planner/bootstrap2/css/bootstrap-responsive.css">-->

<link rel="stylesheet" href="<?php echo base_url(); ?>calendar_planner/css/calendar.css"> 
<!-- 
<div class="page-header">

<div class="pull-right form-inline">
<div class="btn-group">
<button class="btn btn-primary" data-calendar-nav="prev">&lt;&lt; Prev</button>
<button class="btn" data-calendar-nav="today">Today</button>
<button class="btn btn-primary" data-calendar-nav="next">Next &gt;&gt;</button>
</div>
</div>

<h3></h3>
<small>To see example with events navigate to march 2013</small>
</div> -->

<div class="row">

<div class="col-md-12">
<div id="calendar"></div>
</div>
<!-- 
<div class="col-md-4">			
<h4>Events</h4>
<small>This list is populated with events dynamically</small>
<ul id="eventlist" class="nav nav-list"></ul>
</div>  -->
</div> 
<div class="modal fade" id="events-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="margin-top: 120px; overflow: hidden;">
  <div class="modal-dialog modal-sm" style="width: 450px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Message Box</h4>
      </div>
<div class="modal-body" style="height: 400px">
</div>
      <div id="confirmButtons" class="modal-footer"></div>
    </div>
  </div>
</div>


<style type="text/css">
	.cal-month-day {  height: 70px !important;  }
	.cal-year-box [class*="span"], .cal-month-box [class*="cal-cell"] {    min-height: 70px;    border-right: 1px solid #e1e1e1;    position: relative;	}
</style>
  
<script type="text/javascript" src="<?php echo base_url(); ?>calendar_planner/underscore/underscore-min.js"></script>
 
<script type="text/javascript" src="<?php echo base_url(); ?>calendar_planner/jstimezonedetect/jstz.min.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>calendar_planner/js/calendar.js"></script>

<script type="text/javascript">

(function($) { 

var options = {
events_source: '<?php echo base_url(); ?>dashboard/estimators/estimators_events_json',
view: 'month',
tmpl_path: '<?php echo base_url(); ?>calendar_planner/tmpls/',
views: {
      day:   {
        enable: 0 //disabled
      }
    },
tmpl_cache: false,
day: '<?php echo date("Y-m-d"); ?>',  //yyyy-mm-dd 2013-03-11
onAfterEventsLoad: function(events) {
if(!events) {
return;
}
var list = $('#eventlist');
list.html('');

$.each(events, function(key, val) {
$(document.createElement('li'))
.html('<a href="' + val.url + '">' + val.title + '</a>')
.appendTo(list);
});
}, 
onAfterViewLoad: function(view) {
$('#year_estimate_viewed').text(this.getTitle());
$('.btn-group button').removeClass('active');
$('button[data-calendar-view="' + view + '"]').addClass('active');
},
classes: {
months: {
general: 'label'
}
}
};

var calendar = $('#calendar').calendar(options);

$('.btn-group button[data-calendar-nav]').each(function() {
var $this = $(this);
$this.click(function() {
calendar.navigate($this.data('calendar-nav'));
});
});






// $('.btn-group button[data-calendar-view]').each(function() {
// 	var $this = $(this);
// 	$this.click(function() {
// 		calendar.view($this.data('calendar-view'));
// 	});
// });



calendar.setOptions({first_day: 2});
calendar.setOptions({modal: '#events-modal'});
calendar.setOptions({format12: true});
calendar.setOptions({display_week_numbers: false});
calendar.setOptions({weekbox: false});
calendar.setOptions({viewChangeClicked : false});
 

calendar.navigate('today');




//ssscalendar.view();




/*$('#language').change(function(){
calendar.setLanguage($(this).val());
calendar.view();
});*/


$('#events-modal .modal-header, #events-modal .modal-footer').click(function(e){
//e.preventDefault();
//e.stopPropagation();
});
}(jQuery));




</script>


<?php 

/*


		date_default_timezone_set("Australia/Perth");


echo strtotime("15-07-2017 ") * 1000;  // d-m-y


echo '<br />';


echo strtotime("20-07-2017") * 1000;  // d-m-y


echo '<br />';

echo strtotime("05-07-2017");  // d-m-y


echo '<br />';

echo strtotime("25-07-2017");  // d-m-y


echo '<br />';

echo strtotime("25-12-2017");  // d-m-y




echo '<br />';


echo '<br />';


echo date("m/d/y", (1363111200000/1000) );


*/ ?>