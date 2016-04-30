<!-- =============================================== -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

  <?php
    $data['title'] = $title;
    $data['subject'] = '';
  ?>


  <?php $this->load->view('/assets/bread_crumbs',$data) ?>

  <!-- Main content -->
  <section class="content">

    <div id="" class="row">

      <?php if(@$error): ?>




        <div class="col-md-12">

          <div class="box  box-danger  box-solid collapsed-box" id="schedule_error_box" style="">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="icon fa fa-ban"></i> Login Fail! &nbsp; <small style="color:#fff;">Please check your login credentials.</small></h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                <button type="button" class="btn btn-box-tool" id="close_alert_box" aria-hidden="true"><i class="fa fa-times"></i></button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="display: none;">
              <?php echo $error;?>
            </div>
            <!-- /.box-body -->
          </div>




        </div>




      <?php endif; ?>




      <div class="col-md-12 message" style="<?php echo (@$hide_message == 1 ? 'display:none;' : ''); ?>">
        <!-- Widget: user widget style 1 -->
        <div class="box box-widget widget-user-2">
          <!-- Add the bg color to the header using any of the bg-* classes -->
          <div class="widget-user-header" style="background: url('https://my.bicol-u.edu.ph/ors_v0a0/static/images/background.jpg') center center;background-size: cover;height: 175px;">

            <div class="widget-user-image">
              <img class="profile-user-img img-responsive img-circle" src="https://my.bicol-u.edu.ph/ors_v0a0/static/images/president.jpg" alt="User profile picture">
            </div>
            <!-- /.widget-user-image -->
            <h3 class="widget-user-username" style="text-shadow: #001C2B 0px 1px 1px; color:#fff; ">Arnulfo M. Mascarinas</h3>
            <h5 class="widget-user-desc" style="text-shadow: #001C2B 0px 1px 1px; color:#fff; ">SUC President IV</h5>
          </div>
          <div class="box-footer no-padding">
            <div id="" class="pad-10">
              <p>As we gear ourselves towards world-class stature, we aim for clientele satisfaction in all areas of service.</p> <p>BU's online registration system aims to give every user-students, faculty, non-teaching personnel, and administrators-a pleasant and satisfactory experience in availing of our online services. </p>
              <p>Thank you for choosing Bicol University!</p>
            </div>
          </div>
        </div>
        <!-- /.widget-user -->
      </div>


      <div class="col-md-6 sign_in_form" style="display:<?php echo (@$hide_message == 1 ? 'display:block;' : 'none'); ?>;"  >



        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Sign in to start your session</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <form action="<?php echo base_url(); ?>signin" method="post" id="signin_form">
              <div class="form-group has-feedback <?php if(form_error('user_id_accnt')){ echo 'has-error';} ?>">

                <?php if(@$form_type == 'students'): ?>
                  <input type="text" name="user_id_accnt" class="form-control user_id" placeholder="Student Number">
                <?php elseif(@$form_type == 'faculty'):  ?>
                  <input type="text" name="user_id_accnt" class="form-control user_id" placeholder="BU Email">
                <?php else: ?>
                  <input type="text" name="user_id_accnt" class="form-control user_id" placeholder="">
                <?php endif; ?>
                
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
              </div>
              <div class="form-group has-feedback">
                <input type="password" name="password_accnt" class="form-control" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
              </div>
              <div class="row">
                <div class="col-xs-8">
                  <div class="checkbox icheck" style="padding-top: 0;margin-top: 0;">
                    <a href="#">I forgot my password</a>                   
                  </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                  <input type="hidden" class="form_type" name="form_type" value='<?php echo (@$form_type ? $form_type : ''); ?>'>
                  <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
                <!-- /.col -->
              </div>
            </form>
          </div>
          <!-- /.box-body -->
        </div>


      </div>

    </div>


    <!-- /.box -->
  </section>
  <!-- /.content -->
</div>  
<!-- /.content-wrapper -->

<script type="text/javascript">

  $('button#close_alert_box').click(function(){
   $(this).parent().parent().parent().hide();
 });



  $('a.menu_form_toggle').click(function(){

    $('a.menu_form_toggle').parent().removeClass('active');

    $(this).parent().addClass('active');

    $('#signin_form')[0].reset();
    $('.message').hide();

    var form_type = $(this).attr('id');
    $('.sign_in_form').show();

    if(form_type == 'faculty'){
      $('input.user_id').attr('placeholder','BU Email');
      $('input.form_type').val('faculty');
    }else if(form_type == 'students'){
      $('input.user_id').attr('placeholder','Student Number');
      $('input.form_type').val('students');
    }else{

    }


  });
</script>