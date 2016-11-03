<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('invoice'); ?>
<?php $this->load->module('company'); ?>

<?php


var_dump( number_format($this->invoice->get_project_invoiced('35031','66654.16'),2)    );