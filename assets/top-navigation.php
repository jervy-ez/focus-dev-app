<!-- the top bar i think here -->
        <div class="app-header header-shadow top_bar_color_orage header-text-light">
            <div class="app-header__logo">
                <div class="logo-src"></div>
                <div class="header__pane ml-auto">
                    <div>
                        <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="app-header__mobile-menu">
                <div>
                    <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>
            <div class="app-header__menu">
                <span>
                    <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                        <span class="btn-icon-wrapper">
                            <i class="fa fa-ellipsis-v fa-w-6"></i>
                        </span>
                    </button>
                </span>
            </div>

            <div class="app-header__content">
                <?php if($this->session->is_admin == 'on'): ?>
                <div class="app-header-left">
                    <div class="search-wrapper hide">
                        <div class="input-holder">
                            <input type="text" class="search-input text-white" placeholder="Type to search">
                            <button class="search-icon"><span></span></button>
                        </div>
                        <button class="close"></button>
                    </div>
                    <ul class="header-menu nav">
                        <li class="nav-item">
                            <a href="<?php echo base_url(); ?>Dev_notes" class="nav-link">
                                <i class="nav-link-icon fa fa-laptop-code"> </i>
                                Dev Notes
                            </a>
                        </li>
                    </ul>
                </div>
                <?php endif; ?>



                <div class="app-header-right">
                    <div class="header-btn-lg pr-0">
                        <div class="widget-content p-0">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="btn-group">

                                        <a data-toggle="dropdown" class="" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                            
                                            <div id="" class="top-avatar border-2-white rounded-circle">
                                                <?php if(isset($this->session->user_profile_photo) && $this->session->user_profile_photo != '' ): ?>
                                                    <img width="42" class="" src="<?php echo base_url(); ?>uploads/user_photo/<?php echo $this->session->user_profile_photo; ?>" alt="<?php echo $this->session->user_first_name; ?>">
                                                <?php else: ?>
                                                    <img width="42" class="" src="<?php echo base_url(); ?>img/user_profile.png" alt="<?php echo $this->session->user_first_name; ?>">
                                                <?php endif; ?>
                                            </div>

                                            <i class="fa fa-angle-down ml-2" style="vertical-align: middle;    padding-top: 14px;    color: #fff;"></i>
                                        </a>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                            <ul class="nav flex-column">
                                                <li class="nav-item-header nav-item">My Account</li>
                                                <li class="nav-item">
                                                    <a href="<?php echo base_url(); ?>Users/account"  tabindex="0" class="dropdown-item"><em class="fas fa-user-circle"></em> &nbsp; My Profile</a>
                                                </li>
                                                <li class="nav-item-divider nav-item"></li>
                                                <li class="nav-item-btn nav-item">
                                                    <a href="<?php echo base_url(); ?>signout"  tabindex="0" class="btn-block  btn-shadow btn btn-danger btn-sm"><em class="fas fa-sign-out-alt"></em> &nbsp; Sign Out</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content-left  ml-3 header-user-info">
                                    <div class="widget-heading">
                                        <?php echo $this->session->userdata('user_first_name').' '.$this->session->user_last_name; ?>
                                    </div>
                                    <div class="widget-subheading"><?php echo $this->session->user_role_type; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

  

        <!-- the top bar i think here -->