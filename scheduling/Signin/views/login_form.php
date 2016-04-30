<div class="col-md-4 col-md-offset-4" style="margin-top:100px;">
  <div class="login-logo">
    <img src="<?php echo base_url(); ?>img/bulogo2.png">
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Enter your email to start your scheduling</p>


    <?php if(@$error): ?>
      <div class="">
        <div class="border-less-box alert alert-danger fade in">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <?php echo $error;?>
        </div>
      </div>
    <?php endif; ?>

    <form action="" method="post">
      <div class="form-group has-feedback">
        <input type="signin_email" class="form-control" name="user_email" placeholder="Email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck" style="padding-top: 0;margin-top: 0;">
            <a href="https://my.bicol-u.edu.ph/alpha/default">Return to ORS</a><br>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat" name="proceed">Proceed</button>
        </div>
        <!-- /.col -->
      </div>
    </form>



  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
