<!-- cuts the body here -->

<?php if($this->admin->_is_logged_in() ): ?>
  <div class="app-main">
  <?php $this->load->view('assets/sidebar'); ?>
<?php else: ?>
  <div>
<?php endif; ?>



  <!-- outer not yet body -->

  <div class="app-main__outer">

    <?php $this->load->view($main_content); ?>

    <?php if($this->admin->_is_logged_in() ): ?>
      <?php $this->load->view('assets/footer'); ?>
    <?php endif; ?>



  </div>

  <!-- outer not yet body -->


<!--  <script src="http://maps.google.com/maps/api/js?sensor=true"></script>  -->
</div>
<!-- cuts the end of the body here -->
