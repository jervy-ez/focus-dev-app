<?php date_default_timezone_set("Australia/Perth");  // date is set to perth ?>
<?php $this->load->module('bulletin_board'); ?>
<?php $this->load->module('dashboard'); ?>
<?php $months = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"); ?>
<!-- title bar -->


<?php
date_default_timezone_set("Australia/Perth");

$current_year = date("Y");
$current_month = date("m");
$current_day = date("d");

$last_year = $current_year-1;
$start_last_year = "01/01/$last_year";
$last_year_same_date = "$current_day/$current_month/$last_year";

$start_current_year = "01/01/$current_year";
$current_date = date("d/m/Y");
?>

<!-- maps api js -->

<script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyDs1g6kHxbVrkQe7e_CmR6MsfV_3LmLSlc"></script>

<script type="text/javascript">
	var data = { "locations": <?php echo $this->dashboard->focus_get_map_locations(); ?>};	
	var emp_data = { "locations": <?php echo $this->dashboard->emp_get_locations_points(); ?>};
</script>

<script type="text/javascript" src="<?php echo base_url(); ?>js/maps/markerclusterer_packed.js"></script>

<!--[if IE]><script type="text/javascript" src="<?php echo base_url(); ?>js/excanvas.js"></script><![endif]-->
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.knob.min.js"></script>
<script type="text/javascript"> $(function() {  $(".knob").knob(); });</script>
<script typ