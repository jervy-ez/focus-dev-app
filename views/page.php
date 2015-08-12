<?php $this->load->module('users'); ?>
<?php $this->load->view('assets/header'); ?>

<?php if($this->users->_is_logged_in() ): ?>
	<?php $this->load->view('assets/top-navigation'); ?>
<?php endif; ?>

<?php //echo modules::run("menu"); ?>
<?php $this->load->view($main_content); ?>
<?php $this->load->view('assets/footer'); ?>