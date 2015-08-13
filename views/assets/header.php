<!DOCTYPE>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet" type="text/css">        
        <link href="<?php echo base_url(); ?>css/bootstrap-theme.min.css" rel="stylesheet" type="text/css">

        <link href="<?php echo base_url(); ?>css/select2.css" rel="stylesheet"/>
        <link href="<?php echo base_url(); ?>css/main.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>js/vendor/jquery-1.11.0.min.js"></script>
        
		<link href="<?php echo base_url(); ?>css/font-awesome.min.css" rel="stylesheet">
        <script src="<?php echo base_url(); ?>js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
        		
		<script src="https://maps.googleapis.com/maps/api/js?v=3&amp;sensor=false"></script>
    	<script type="text/javascript" src="<?php echo base_url(); ?>js/maps/data.js"></script>
    	<script type="text/javascript" src="<?php echo base_url(); ?>js/maps/markerclusterer_packed.js"></script>
    	
    	<?php //if($chart): ?>
		<link href="<?php echo base_url(); ?>css/c3.css" rel="stylesheet" type="text/css">
		<script src="<?php echo base_url(); ?>js/c3/d3.js" charset="utf-8"></script>
		<script src="<?php echo base_url(); ?>js/c3/c3.js"></script>
		<?php //endif; ?>
    </head>
    <body>
    	<!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->