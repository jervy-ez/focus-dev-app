<div class="app-main__inner">

	<div class="app-page-title">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div class="page-title-icon"> <i class="fas fa-users icon-gradient bg-orange-gradient"></i> </div>
				<div>Users List <div class="page-title-subheading">Meet the team and get connected.</div> </div>
			</div>
			<div class="page-title-actions">
				<button type="button" data-toggle="tooltip" title="" data-placement="bottom" class="hide btn-shadow mr-3 btn btn-dark" data-original-title="Example Tooltip">
					<i class="fa fa-star"></i>
				</button>
				<div class="d-inline-block dropdown">
					<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-info">
						<span class="btn-icon-wrapper pr-2 opacity-7"><i class="fa fa-cog fa-w-20"></i></span>Control
					</button>
					<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
						<ul class="nav flex-column">
              <li class="nav-item"><a href="<?php echo base_url('Users/account') ?>" class="nav-link">&nbsp; <i class="nav-link-icon fas fa-user-circle"></i><span> &nbsp; My Profile</span></a></li>
              <li class="nav-item"><a href="<?php echo base_url('Users/add') ?>" class="nav-link">&nbsp; <i class="nav-link-icon fas fa-user-plus"></i><span> &nbsp; Add New User</span></a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>            

  <div class="row">
    <?php foreach ($data_users_list->result() as $data_user): ?>
      <div class="col-md-6 col-lg-6 col-xl-4">
        <div id="" class="main-card mb-3 card">
          <div id="" class="card-body p-0">
            <div class="card-shadow-primary card-border   card">
              <div class="dropdown-menu-header">
                <div class="dropdown-menu-header-inner bg-secondary text-center white-text ">
                  <div class="menu-header-image" style="background-image: url('<?php echo base_url(); ?>temp_images/header/abstract9.jpg');"></div>
                  <div class="menu-header-content">
                    <div class="avatar-icon-wrapper btn-hover-shine mb-2 avatar-icon-xxl">
                      <div class="avatar-icon margin-auto">
                       <a href="<?php echo base_url() ?>Users/account/<?php echo $data_user->user_id; ?>" class="" id="">
                         <?php if(isset($data_user->profile_photo) && $data_user->profile_photo != '' ): ?>
                           <img width="64"  src="<?php echo base_url(); ?>uploads/user_photo/<?php echo $data_user->profile_photo; ?>" alt="<?php echo $data_user->first_name; ?>">
                         <?php else: ?>
                           <img width="64"  src="<?php echo base_url(); ?>img/user_profile.png" alt="<?php echo $data_user->first_name; ?>">
                         <?php endif; ?>
                       </a>
                     </div>
                   </div>
                   <div>
                    <h5 class="menu-header-title"><?php echo $data_user->first_name.' '.$data_user->last_name; ?></h5>
                    <h6 class="menu-header-subtitle mb-3"><?php echo $data_user->role_types; ?></h6>
                  </div>
                  <div class="menu-header-btn-pane">
                    <div class="cursor-default btn-icon btn btn-alternate btn-xs"><em class="fas fa-envelope-open-text text-white"></em> &nbsp; <?php echo $data_user->general_email; ?></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
            <div id="" class="p-3 text-center">
              <?php if(isset($data_user->office_ext) && $data_user->office_ext != '' ): ?>
                <div data-toggle="tooltip" title="" data-placement="top" data-original-title="Office Ext" class="cursor-default btn-icon btn btn-info btn-sm"><?php echo $data_user->office_ext; ?></div>
              <?php endif; ?>
              <?php if(isset($data_user->office_number) && $data_user->office_number != '' ): ?>
               <div data-toggle="tooltip" title="" data-placement="top" data-original-title="Office" class="cursor-default btn-icon btn btn-primary btn-sm"><em class="fas fa-headset text-white"></em> &nbsp; <?php echo $data_user->office_number; ?></div>

             <?php endif; ?>
             <?php if(isset($data_user->mobile_number) && $data_user->mobile_number != '' ): ?>
               <div data-toggle="tooltip" title="" data-placement="top" data-original-title="Mobile" class="cursor-default btn-icon btn btn-danger btn-sm"><em class="fas fa-mobile-alt text-white"></em> &nbsp; <?php echo $data_user->mobile_number; ?></div>
             <?php endif; ?>



             <div  class="cursor-default btn-icon btn btn-info btn-sm pull-right" style=" background: none !important;    border: none !important; height: 45px;"></div>



           </div>
         </div>
       </div>
      </div>
    <?php endforeach; ?>
  </div>
    
</div>