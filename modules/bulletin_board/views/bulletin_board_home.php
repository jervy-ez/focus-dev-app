<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('bulletin_board'); ?>

<!-- title bar -->
<div class="container-fluid head-control">
	<div class="container-fluid">
		<div class="row">

			<div class="col-md-6 col-sm-4 col-xs-12 pull-left">
				<header class="page-header">
					<h3><?php $datestring = "%l, %F %d, %Y"; $time = time(); //use time() for timestamp  ?>
						<?php echo $screen; ?> Screen<br><small><?php echo mdate($datestring, $time); #echo date("l, F d, Y"); ?></small>
					</h3>
				</header>
			</div>

			<div class="page-nav-options col-md-6 col-sm-8 col-xs-12 pull-right hidden-xs">
				<ul class="nav nav-tabs navbar-right">
					<li>
						<a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Home</a>
					</li>					
					<li>
						<a href="<?php echo base_url(); ?>projects" class="btn-small btn-primary"><i class="fa fa-print"></i> Projects</a>
					</li>  		
					<li>
						<a class="btn-small sb-open-right"><i class="fa fa-file-text-o"></i> Project Comments</a>
					</li>
				</ul>
			</div>

		</div>
	</div>
</div>
<!-- title bar -->

<div class="container-fluid">
	<!-- Example row of columns -->
	<div class="row">				
		<?php $this->load->view('assets/sidebar'); ?>
		<div class="section col-sm-12 col-md-11 col-lg-11">
			<div class="container-fluid">

				<div class="row">

          <?php if($this->session->userdata('is_admin') == 1 || $this->session->userdata('bulletin_board') > 1 ): ?>

           <div class="col-md-9">

           <?php else: ?>

           <div class="col-md-12">

           <?php endif; ?>

						<div class="left-section-box clearfix">


						<div class="clearfix"></div>


							<div class="box-head pad-10 clearfix">
								<div class="pull-right" style="margin-top: -15px;">							 
									<div class="clearfix"></div>
								</div>
								<label><?php echo $screen; ?></label>

								<div class="pull-right"><input type="text" placeholder="Search..." class="form-control search_title_bb" style="width:250px;"></div>
							</div>

              <?php if(@$this->session->flashdata('error')): ?>
                <div class="no-pad-t m-bottom-10 m-left-10">
                  <div class="border-less-box alert alert-danger fade in">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>                    
                    <?php echo $this->session->flashdata('error');?>
                  </div>
                </div>
              <?php endif; ?>

              <?php if(@$this->session->flashdata('success_post')): ?>
                <div class="no-pad-t m-bottom-10 m-left-10">
                  <div class="border-less-box alert alert-success fade in">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>                   
                    <?php echo $this->session->flashdata('success_post');?>
                  </div>
                </div>
              <?php endif; ?>

							<div class="clearfix box-area pad-10 m-left-10 m-right-10 pad-top-10 box-area-po">
                				<?php $this->bulletin_board->list_post(); ?>								
							</div>
						</div>
					</div>


          <?php if($this->session->userdata('is_admin') == 1 || $this->session->userdata('bulletin_board') > 1 ): ?>

            <form class="form-horizontal" role="form" method="post" action="<?php echo base_url(); ?>bulletin_board/post">
              <div class="col-md-3 m-top-5">
                <div class="box">
                  <div class="box-head pad-5">
                    <label>Post Here</label>
                  </div>

                  <div class="box-area clearfix pad-5">
                    <div class="clearfix m-top-10 m-bottom-10">                    
                      <div class="col-sm-12">
                        <input type="text" class="form-control upper_c_each_word" value="<?php echo $this->session->flashdata('title');?>" placeholder="Title" name="title" id="title_of_post">
                      </div>            
                    </div>

                    <div class="clearfix m-top-10 m-bottom-10">                    
                      <div class="col-sm-12">
                        <textarea class="form-control upper_c_first_word_sentence" placeholder="My Post" name="my_post" id="my_post" rows="10"><?php echo $this->session->flashdata('my_post');?></textarea>                      
                      </div>            
                    </div>

                    <input type="hidden" class="form-control" value="<?php echo $this->session->userdata('user_id'); ?>" name="token">

                    <button type="submit" name="submit" class="btn btn-success pull-right m-bottom-10 m-right-5"><i class="fa fa-floppy-o"></i> Submit</button>
                    <div class="m-left-5">
                    	<input class="check-swtich" type="checkbox" name="set_urgent" data-on-text="Yes" data-off-text="No" data-label-text="Is it urgent?">
                    </div>

                  </div>
                </div>
              </div>    
            </form>

          <?php endif; ?>
					
				</div>				
			</div>
		</div>
	</div>

</div>

<?php $this->load->view('assets/logout-modal'); ?>



<!-- Modal -->
<div class="modal fade" id="edit_post" tabindex="-1" role="dialog" aria-labelledby="edit_post" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="post"  action="<?php echo base_url(); ?>bulletin_board/update_post">
      <div class="modal-header">

      	<div class="pull-right">            		
      		<input class="check-swtich set_urgent_edit" type="checkbox" name="set_urgent_edit" id="set_urgent_edit" data-on-text="Yes" data-off-text="No" data-label-text="Is it urgent?">
      	</div>
        <h4 class="modal-title" id="myModalLabel">Edit Post</h4>
      </div>
      	<div class="modal-body pad-10">
      		<div class="container-fluid">
      			<div class="row">

      				<div class="col-sm-12">
      					<div class="input-group m-bottom-10">
      						<span class="input-group-addon" id=""><strong>Title</strong></span>
      						<input type="text" placeholder="Post Title" class="form-control" id="post_title" name="post_title" value="">
      					</div>

      					<div class="box m-top-15 ">
      						<div class="box-head pad-5">
      							<label for="email_msg_no_insurance">Content</label>
      						</div>

      						<div class="box-area pad-5 clearfix ">
      							<div class="clearfix ">
      								<div class="">
      									<textarea class="form-control" id="post_content" name="post_content" rows="10"></textarea>												
      								</div>
      							</div>
      						</div>
      					</div>

      					<input type="hidden" name="expiry_date" id="expiry_date">
      					<input type="hidden" name="post_id" id="post_id">
      					<input type="hidden" name="is_urgent" id="is_urgent">

      				</div>

      			</div>
      		</div>
      	</div>
      	<div class="modal-footer">
      		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      		<button type="submit" class="btn btn-success">Save Changes</button>
      	</div>
      </form>
    </div>
  </div>
</div>

<?php // $this->bulletin_board->list_latest_post(); ?>