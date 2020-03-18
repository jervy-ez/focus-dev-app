<?php date_default_timezone_set("Australia/Perth");  // date is set to perth and important setting for diff timezone acounts ?>
<?php $this->load->module('company'); ?>
<?php $this->load->module('projects'); ?>
<?php $this->load->module('bulletin_board'); ?>
<?php $this->load->module('schedule'); ?>
<?php $this->load->model('admin_m'); ?>

<script src="<?php echo base_url(); ?>js/vue.js"></script>
<script src="<?php echo base_url(); ?>js/moment.min.js"></script>
<script src="<?php echo base_url(); ?>js/jmespath.js"></script>
<!-- title bar -->

<style>
  .expired{
    color: red !important;
  }

</style>

<input type = "hidden" id = "base_url" value = "<?php echo base_url() ?>">
<div class="container-fluid head-control">
  <div class="container-fluid">
    <div class="row">

      <div class="col-md-5 col-sm-4 col-xs-12 pull-left">
        <header class="page-header">
          <h3><?php $datestring = "%l, %F %d, %Y"; $time = time(); //use time() for timestamp  ?>
            <?php echo $screen; ?> Screen<br><small><?php echo mdate($datestring, $time); #echo date("l, F d, Y"); ?></small>
          </h3>
        </header>
      </div>

      <div class="page-nav-options col-md-7 col-sm-8 col-xs-12 pull-right hidden-xs">
        <ul class="nav nav-tabs navbar-right">
          <li>
            <a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Home</a>
          </li>
          <?php if($this->session->userdata('is_admin') == 1 || $this->session->userdata('user_role_id') == 4): ?>
          <li>
            <a href="<?php echo base_url(); ?>induction_health_safety"><i class="fa fa-home"></i> Induction Site Staff</a>
          </li>
          <li>
            <a href="<?php echo base_url(); ?>induction_health_safety/inductions_projects"><i class="fa fa-home"></i> Induction Projects</a>
          </li>
          <li>
            <a href="<?php echo base_url(); ?>induction_health_safety/inductions_videos"><i class="fa fa-home"></i> Uploading Video for Induction</a>
          </li>
          <?php if(isset($_GET['project_id'])): ?>
          <li>
            <a href="<?php echo base_url(); ?>induction_health_safety/induction_slide_editor_view?project_id=<?php echo $_GET['project_id'] ?>"><i class="fa fa-home"></i> Induction Slide Templates</a>
          </li>
          <?php else: ?>
          <li>
            <a href="<?php echo base_url(); ?>induction_health_safety/induction_slide_editor_view"><i class="fa fa-home"></i> Induction Slide Templates</a>
          </li>
          <?php endif; ?>
          <?php endif; ?>
          <?php if($this->session->userdata('user_role_id') == 2 || $this->session->userdata('user_role_id') == 3): ?>
          <?php if(isset($_GET['project_id'])): ?>
          <li>
            <a href="<?php echo base_url(); ?>induction_health_safety/induction_slide_editor_view?project_id=<?php echo $_GET['project_id'] ?>"><i class="fa fa-home"></i> Induction Slide Templates</a>
          </li>
          <?php else: ?>
          <li>
            <a href="<?php echo base_url(); ?>induction_health_safety/induction_slide_editor_view"><i class="fa fa-home"></i> Induction Slide Templates</a>
          </li>
          <?php endif; ?>
          <li>
            <a href="<?php echo base_url(); ?>induction_health_safety/inductions_projects"><i class="fa fa-home"></i> Induction Projects</a>
          </li>
          <?php if(isset($_GET['project_id'])): ?>
          <?php if($this->session->userdata('user_role_id') == 2 || $this->session->userdata('user_role_id') == 3): ?>
          <li>
            <a href="<?php echo base_url(); ?>induction_health_safety/project_induction_site_staff?project_id=<?php echo $project_id ?>"><i class="fa fa-video"></i> Send Induction Video Link</a>
          </li>
          <?php endif; ?>
          <li>
            <a href="<?php echo base_url(); ?>projects/view/<?php echo $project_id ?>"><i class="fa fa-map-marker"></i> Project Details</a>
          </li>
          <?php endif; ?>
          <?php endif; ?>
        </ul>
      </div>

    </div>
  </div>
</div>
<!-- title bar -->

<div class="container-fluid" id = "ihs_app">
  <!-- Example row of columns -->
    <div class="row">       
      <?php $this->load->view('assets/sidebar'); ?>
      <div class="section col-sm-12 col-md-11 col-lg-11">
          <div class="container-fluid">
            <div class="row" style = "border-bottom: 1px solid; border-color: #CCC">
                <button type = "button" id = "btn_view_qrcode" class = "btn btn-success btn-sm pull-right" v-on:click = "view_qrcode">View Video Link QR Code</button>
                <div class="col-lg-12 col-md-12 hidden-md hidden-sm hidden-xs">
                  <?php if(@$this->session->flashdata('project_deleted')): ?>
                    <div class="m-15">
                      <div class="border-less-box alert alert-danger fade in">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                          <h4>Opps! No turning back now!</h4>
                          <?php echo $this->session->flashdata('project_deleted');?>
                      </div>
                    </div>
                   <?php endif; ?>
                  <div class="box-head pad-10 clearfix">
                      <label class="project_name"><h3><?php echo $project_name; ?></h3><p style = "padding-bottom: 10px; "><b>(Induction Contractor Site Staff)</b><br></p><p>Client: <strong><?php //echo $client_company_name; ?></strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Project No.<?php echo $project_id; ?></p></label>              
                  </div>
                  
                </div>

            </div>
            <div class="box-area" style = "padding-top: 20px">
              <div class="col-sm-5 pad-5">
                <div class="col-sm-12 pad-5"><b>Site Staff</b></div>
                <div class="box-tabs m-bottom-15 pad-5" >
                  <ul class="nav nav-tabs">
                    <li class = "active" v-on:click="select_tab(1)"><a data-toggle="tab" href="#focusSiteStaff">Focus Site Staff</a></li>
                    <li v-on:click="select_tab(2)"><a data-toggle="tab" href="#ContractorSiteStaff">Contractor Site Staff</a></li>
                    <li v-on:click="select_tab(3)"><a data-toggle="tab" href="#other">Other</a></li>
                  </ul>

                  <div class="tab-content">
                    <div id="focusSiteStaff" class="tab-pane fade in active row pad-5">
                      <div class="col-sm-10 pad-5">
                        <input type="text" class = "form-control input-sm" placeholder = "search..." v-model="searchFocusSiteStaff">
                      </div>
                      <div class="col-sm-2 pad-5"><button type = "button" class = "btn btn-success btn-block btn-sm" v-on:click="sendFocusSSLink">Select Staff</button></div>
                      <div class="col-sm-12 pad-5" style = "height: 400px; overflow: auto">
                        <table class = "table table-condensed table-bordered m-bottom-0 table-striped fancyTable" style = "font-size: 12px">
                          <thead>
                            <th></th>
                            <th>Site Staff Name</th>
                            <th></th>
                            <th></th>
                          </thead>
                          <tbody>
                            <tr v-for = "focusSiteStaff in filterFocusSiteStaff">
                              <td style = "width: 20px"><input type="checkbox" :value='focusSiteStaff.general_email+"/"+focusSiteStaff.user_id' v-model="checkedFocusSSEmails"></td>
                              <td>{{ focusSiteStaff.user_first_name + " " + focusSiteStaff.user_last_name }}</td>
                              <td style = "width: 30px">
                                <img v-if = "focusSiteStaff.induction_video_watch == 0" src="<?php echo base_url() ?>img/send_email/watched-disabled.png" style = "height: 30px" alt="" title = "Haven't Watch Induction Video">
                                <img v-if = "focusSiteStaff.induction_video_watch == 1" src="<?php echo base_url() ?>img/send_email/watched.png" style = "height: 30px" alt="" title = "Already Watch Induction Video">
                              </td>
                              <td style = "width: 30px">
                                <img v-if="focusSiteStaff.induction_email_send_id == 0" src="<?php echo base_url() ?>img/send_email/email-disabled.png" style = "height: 30px" alt="" title = "Induction Video Link is not yet sent">
                                <img v-if="focusSiteStaff.induction_email_send_id == 1" src="<?php echo base_url() ?>img/send_email/email-send.png" style = "height: 30px" alt="" title = "Induction Video already Sent">
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div id="ContractorSiteStaff" class="tab-pane fade in row pad-5">
                      <div class="col-sm-2 pad-5">Search by:</div>
                      <div class="col-sm-8 pad-5">
                        <select class = "form-control input-sm" v-model="filterCSSBy">
                          <option value="1">Site Staff</option>
                          <option value="0">Company Name</option>
                        </select>
                      </div>
                      <div class="clearfix"></div>

                      <div class="col-sm-2 pad-5">Search:</div>
                      <div class="col-sm-8 pad-5">
                        <input type="text" class = "form-control input-sm" placeholder = "search..." v-model="searchContractorStaff">
                      </div>

                      <div class="col-sm-12 pad-5" style = "height: 400px; overflow: auto">
                        <table class = "table table-condensed table-bordered m-bottom-0 table-striped fancyTable" style = "font-size: 12px">
                          <thead>
                            <th>Site Staff Name</th>
                            <th>Company Name</th>
                            <th></th>
                          </thead>
                          <tbody v-for = "projectsContractors in projectsContractors">
                            <tr v-for = "contractorSiteStaff in filterContractorSiteStaff" v-if = "projectsContractors.company_client_id == contractorSiteStaff.company_id">
                              <td>{{ contractorSiteStaff.site_staff_fname +" "+ contractorSiteStaff.site_staff_sname }}</td>
                              <td>{{ contractorSiteStaff.company_name}}</td>
                              <td style = "width: 30px">
                                <img v-if = "contractorSiteStaff.ss_watched == null" src="<?php echo base_url() ?>img/send_email/watched-disabled.png" style = "height: 30px" alt="" title = "Haven't Watch Induction Video">
                                <img v-if = "contractorSiteStaff.ss_watched > 1" src="<?php echo base_url() ?>img/send_email/watched.png" style = "height: 30px" alt="" title = "Already Watch Induction Video">
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div id="other" class="tab-pane fade in row pad-5">
                      <div class="col-sm-2 pad-5">Search by:</div>
                      <div class="col-sm-8 pad-5">
                        <select class = "form-control input-sm">
                          <option value="1">Site Staff</option>
                          <option value="0">Company Name</option>
                        </select>
                      </div>
                      <div class="col-sm-2 pad-5"><button type = "button" class = "btn btn-primary btn-block btn-sm" v-on:click="show_other_company_details">Add New</button></div>

                      <div class="clearfix"></div>

                      <div class="col-sm-2 pad-5">Search:</div>
                      <div class="col-sm-8 pad-5">
                        <input type="text" class = "form-control input-sm" placeholder = "search...">
                      </div>
                      <div class="col-sm-2 pad-5"><button type = "button" class = "btn btn-success btn-block btn-sm" v-on:click="selectOSiteStaff">Select</button></div>
                      <div class="col-sm-12 pad-5" style = "height: 400px; overflow: auto">
                        <table class = "table table-condensed table-bordered m-bottom-0 table-striped fancyTable" style = "font-size: 12px">
                          <thead>
                            <th></th>
                            <th></th>
                            <th>Company Name</th>
                            <th>Site Staff Name</th>
                            <th></th>
                            <th></th>
                          </thead>
                          <tbody>
                            <tr v-for = "projectIndOtherSiteStaffList in projectIndOtherSiteStaffList">
                              <td style = "width: 30px"><input type="checkbox" :value = "projectIndOtherSiteStaffList.email+'/'+projectIndOtherSiteStaffList.induction_other_company_sitestaff_id" v-model="projectInductionOSiteStaffID"></td>
                               <td style = "width: 30px"><button type = "button" class = "btn btn-danger btn-sm" title = "Remove Site Staff from this project" v-on:click="remove_project_other_sitestaff(projectIndOtherSiteStaffList.induction_project_other_site_staff_id)"><i class="fa fa-trash"></i></button></td>
                              <td>{{ projectIndOtherSiteStaffList.company_name }}</td>
                              <td>{{ projectIndOtherSiteStaffList.site_staff_fname +" "+projectIndOtherSiteStaffList.site_staff_sname }}</td>
                              <td style = "width: 30px" v-on:click = "select_other_company(projectIndOtherSiteStaffList.induction_other_company_sitestaff_id)">
                                <img v-if = "projectIndOtherSiteStaffList.ss_watched == null" src="<?php echo base_url() ?>img/send_email/watched-disabled.png" style = "height: 30px" alt="" title = "Haven't Watch Induction Video">
                                <img v-if = "projectIndOtherSiteStaffList.ss_watched > 1" src="<?php echo base_url() ?>img/send_email/watched.png" style = "height: 30px" alt="" title = "Already Watch Induction Video">
                              </td>
                              <td style = "width: 30px">
                                <img v-if="projectIndOtherSiteStaffList.induction_email_send_id == null" src="<?php echo base_url() ?>img/send_email/email-disabled.png" style = "height: 30px" alt="" title = "Induction Video Link is not yet sent">
                                <img v-if="projectIndOtherSiteStaffList.induction_email_send_id > 0" src="<?php echo base_url() ?>img/send_email/email-send.png" style = "height: 30px" alt="" title = "Induction Video already Sent">
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-7">
                <div class="col-sm-3 pad-5"><label for="">Send To:</label></div>
                <div class="col-sm-9 pad-5"><textarea class = "form-control input-sm" v-model="sendTo" readonly="true"></textarea></div>

                <div class="col-sm-3 pad-5"><label for="">Alternate Email:</label></div>
                <div class="col-sm-9 pad-5"><input type="text" class = "form-control input-sm" :readonly = "alt_email" v-model = "altEmail"></div>

                <div class="col-sm-3 pad-5"><label for="">CC:</label></div>
                <div class="col-sm-9 pad-5"><input type="text" class = "form-control input-sm" v-model = "ccEmail"></div>

                <div class="col-sm-3 pad-5"><label for="">BCC:</label></div>
                <div class="col-sm-9 pad-5"><input type="text" class = "form-control input-sm" v-model = "bccEmail"></div>

                <div class="col-sm-3 pad-5"><label for="">Subject:</label></div>
                <div class="col-sm-9 pad-5"><input type="text" class = "form-control input-sm" v-model = "subjectEmail"></div>

                <div class="col-sm-12 pad-5"><textarea class = "form-control" style = "height: 200px;" id = "contentEmail"></textarea></div>

                <div class="col-sm-12 pad-5"><button type = "button" class = "btn btn-success btn-sm pull-right" v-on:click = "sendEmail">Send</button></div>
              </div>
            </div>
        </div>
      </div>
  </div>

  <div class="modal fade" id="frm_add_other_company" role="dialog">
      <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Other Company Site Staff Details</h4>
            </div>
            <div class="modal-body row">
              <div class="col-sm-10 pad-5">
                <div class="row">
                  <div class="col-sm-2 pad-5">Search by:</div>
                  <div class="col-sm-10 pad-5">
                    <select class = "form-control input-sm">
                      <option value="1">Site Staff</option>
                      <option value="0">Company Name</option>
                    </select>
                    </div>
                  <div class="col-sm-2">
                    Search:
                  </div>
                  <div class="col-sm-10"><input type = "text" class = "form-control input-sm" placeholder = "Search..." v-model = "searchSiteStaff"></div>
                </div>
              </div>
              <div class="col-sm-2 pad-5"><button tyep = "button" class = "btn btn-primary btn-sm" v-on:click="showAddNewForm">Add New</button></div>
              <div class="col-sm-12 pad-5" style = "height: 200px; overflow:auto">
                <table class = "table table-condensed table-bordered m-bottom-0 table-striped fancyTable" style = "font-size: 12px">
                  <thead>
                    <th></th>
                    <th>Company Name</th>
                    <th>Site Staff Name</th>
                  </thead>
                  <tbody>
                    <tr v-for = "otherCopmanySiteStaff in otherCopmanySiteStaff">
                      <td><input type="checkbox" :value='otherCopmanySiteStaff.induction_other_company_sitestaff_id' v-model="checkedOCompanyList"></td>
                      <td v-on:click = "select_other_company(otherCopmanySiteStaff.induction_other_company_sitestaff_id)">{{ otherCopmanySiteStaff.company_name }}</td>
                      <td>{{ otherCopmanySiteStaff.site_staff_fname +" "+otherCopmanySiteStaff.site_staff_sname }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>        
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-success" v-on:click="add_other_company" data-dismiss="modal">Add</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        
      </div>
  </div>

  <div class="modal fade" id="frm_add_new_other_company" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Other Site Staff Details</h4>
        </div>
        <div class="modal-body row text-center">
          <div class="col-sm-3 pad-5">Company Name: </div>
          <div class="col-sm-9 pad-5">
            <select v-model = "other_company_id" class = "form-control input-sm" v-on:change="change_other_company">
              <option v-for = "otherCopmany in otherCopmany" :value="otherCopmany.induction_other_company_id">{{ otherCopmany.company_name}}</option>
              <option value="0">Other</option>
            </select>
                 
          </div>  
          <div class="col-sm-3 pad-5" v-show="show_select_other_company"></div>
          <div class="col-sm-9 pad-5" v-show="show_select_other_company">
            <input type="text" class = "form-control input-sm" v-model = "company_name">
          </div>
          <div class="col-sm-3 pad-5">Site Staff Name: </div>
          <div class="col-sm-9 pad-5"><input type="text" class = "form-control input-sm" v-model = "other_site_staff_fname"></div>  
          <div class="col-sm-3 pad-5">Site Staff Surname: </div>
          <div class="col-sm-9 pad-5"><input type="text" class = "form-control input-sm" v-model = "other_site_staff_sname"></div> 
          <div class="col-sm-3 pad-5">Mobile number: </div>
          <div class="col-sm-9 pad-5"><input type="text" class = "form-control input-sm" v-model = "other_mobile_number"></div>  
          <div class="col-sm-3 pad-5">E-mail: </div>
          <div class="col-sm-9 pad-5"><input type="text" class = "form-control input-sm" v-model = "other_email"></div>   
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning pull-left" v-on:click="addCompanySiteStaff" data-dismiss="modal" v-show = "show_add_other_company">Add</button>
          <button type="button" class="btn btn-default btn-success" v-on:click="update_other_company" data-dismiss="modal" v-show = "!show_add_other_company">Update</button>
          <button type="button" class="btn btn-default btn-danger pull-left" v-on:click="delete_other_company" data-dismiss="modal" v-show = "!show_add_other_company">Remove</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>  
    </div>
  </div>


  <div class="modal fade" id="frm_induction_video_qrcode" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Induction Video Link QR Code</h4>
        </div>
        <div class="modal-body row text-center">
          <img src="<?php echo base_url() ?>projects/induction_qrcode" style = "width: 200px" />';
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning pull-left" v-on:click="generateBlueBook">Generate Blue Book Cover</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>  
    </div>
  </div>
</div>

<?php $this->load->view('assets/logout-modal'); ?>
<style>
  .client_logo{
    position: absolute;
    top: 20px;
    right: 20px;
    width: 200px;
  }

  .site_address{
    position: absolute;
    top: 50px;
    left: 50px;
    width: 520px;
    font-size: 20px;
  }
</style>

<script>

  // $(document).ready(function() {
  //   $("#no_pdf").show();
  //   $("#slide_preview").hide();

  //   $('#state_name').on("change", function(e) {
  //     var state_name = $('#state_name').val();
  //     $.post(baseurl+"induction_health_safety/fetch_general_address_suburb",
  //     {
  //       state_name: state_name
  //     },
  //     function(result){
  //       $("#add_suburb").empty();
  //       $.each(JSON.parse(result), function (key, value)  {
  //           $("#add_suburb").append($('<option></option>').val(value.suburb).html(value.suburb));
  //       });
  //     });

      
      
  //   });

  //   $('#add_suburb').on("change", function(e) {
  //     var suburb = $('#add_suburb').val();
  //     $.post(baseurl+"induction_health_safety/fetch_general_address",
  //     {
  //       suburb: suburb
  //     },
  //     function(result){
  //       $("#add_postcode").empty();
  //       $.each(JSON.parse(result), function (key, value)  {
  //           $("#add_postcode").append($('<option></option>').val(value.postcode).html(value.postcode));
  //       });
  //     });
  //   });

  // });

  // // $('#s2id_state_name span.select2-chosen').change(function(){
  // //  alert("fdsfs");
  // // });
  // // window.change_state = function(){
  // //  alert("fdsfs");
  // // }

  


  tinymce.init({ 
        selector:'#project_outline',
        height: '300px',
        menubar: false,
        plugins: [
                'advlist autolink lists link image charmap print preview anchor textcolor',
                  'searchreplace visualblocks code fullscreen',
                  'insertdatetime media table contextmenu paste code help wordcount',
                ],
        //toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
        toolbar: 'bold italic',
        browser_spellcheck: true
        
    });
  var project_id = '<?php echo $project_id; ?>';

  var baseurl = '<?php echo base_url(); ?>';
  var app = new Vue({
        el: '#ihs_app',
        data: {
          searchFocusSiteStaff: "",
          focusSiteStaff: [],
          searchContractorStaff: "",
          contractorSiteStaff: [],
          filterCSSBy: 1,
          otherCopmany: [],
          other_company_id: "",
          show_select_other_company: false,

          other_company_id: "",
          company_name: "",
          other_site_staff_fname: "",
          other_site_staff_sname: "",
          other_mobile_number: "",
          other_email: "",
          show_add_other_company: true,
          otherCopmanySiteStaff: [],
          induction_other_company_sitestaff_id: '',

          checkedFocusSSEmails: [],
          projectsContractors: [],
          sendTo: "",
          selectedSiteStaffEmail: [],
          selectedSiteStaff: [],
          alt_email: false,
          altEmail: "",
          ccEmail: "",
          bccEmail: "",
          subjectEmail: "",
          siteStaffType: 1,

          searchSiteStaff: "",

          projectIndOtherSiteStaffList: [],
          checkedOCompanyList: [],
          projectInductionOSiteStaffID: [],
        },

        filters: {
            getDayname: function(date){
              return moment(date).format('ddd');
            },

            ausdate: function(date) {
              return moment(date).format('DD/MM/YYYY');
            },
          
            getTime: function(date){
              return moment(date).format('h:m a');
            },
            replaceApos: function(string){
              return
            }
        },

        mounted: function(){
          this.fetchFocusSiteStaff();
          this.fetchContractorSiteStaff();
          this.fetchOtherCompany();
          this.fetchOtherCompanySiteStaff();
          this.fetchProjectContractors();
          this.fetchProjectIndOtherSiteStaff();
        },

        methods: {  
          selectOSiteStaff: function(){
            this.siteStaffType = 3;
            this.sendTo = this.projectInductionOSiteStaffID;
            var a = this.sendTo+"";
            var result_arr =  a.split(',');
            
            var legnth = result_arr.length;
            if(legnth > 1){
              this.alt_email = true;
              this.altEmail = "";
            }else{
              this.alt_email = false;
            }
            var x = 0;
            this.selectedSiteStaffEmail = [];
            this.selectedSiteStaff = [];
            while(x < legnth){
                var checked_val = result_arr[x];
                var checked_val_arr = checked_val.split('/');

                this.selectedSiteStaffEmail.push(checked_val_arr[0]);
                this.selectedSiteStaff.push(checked_val_arr[1]);
                x++;
            }
            this.subjectEmail = "Induction Video Link",
            $.post(baseurl+"induction_health_safety/get_video_link_email_defaults",
            {
              contractor: 'oss'
            },
            function(result){
                $("#contentEmail").val(result + "\n\nPlease see this link: "+baseurl+"direct_contractor_upload/contractor_induction_video?project_id="+project_id);
            });
            this.sendTo = this.selectedSiteStaffEmail.toString();
            this.selectedSiteStaff = this.selectedSiteStaff.toString();
          },

          fetchProjectIndOtherSiteStaff: function(){
            $.post(baseurl+"induction_health_safety/fetch_project_induction_other_sitestaff",
            {
              project_id: project_id,
            },
            function(result){
              app.projectIndOtherSiteStaffList = JSON.parse(result);
            });
          },

          
          showAddNewForm: function(){
            this.show_add_other_company = true;
            this.show_select_other_company = false;
            this.other_company_id = "";
            this.company_name = "";
            this.other_site_staff_fname = "";
            this.other_site_staff_sname = "";
            this.other_mobile_number = "";
            this.other_email = "";
            $("#frm_add_new_other_company").modal('show');
          },

          addCompanySiteStaff: function(){
            $.post(baseurl+"induction_health_safety/insert_other_company_site_staff",
            {
              company_id: this.other_company_id,
              company_name: this.company_name,
              site_staff_fname: this.other_site_staff_fname,
              site_staff_sname: this.other_site_staff_sname,
              mobile_number: this.other_mobile_number,
              email: this.other_email
            },
            function(result){
                app.otherCopmanySiteStaff = JSON.parse(result);
                $.post(baseurl+"induction_health_safety/view_other_company",
                {
                },
                function(result){
                    app.otherCopmany = JSON.parse(result);
                });
            });
          },

          select_tab: function(a){
            this.siteStaffType = a;
          },
          
          fetchProjectContractors: function(){
            $.post(baseurl+"induction_health_safety/get_project_contractors",
            {
              project_id: '<?php echo $project_id; ?>'
            },
            function(result){
                app.projectsContractors = JSON.parse(result);
            });
            
          },

          fetchFocusSiteStaff: function(){
            $.post(baseurl+"induction_health_safety/get_user_site_staff",
            {
              project_id: '<?php echo $project_id; ?>'
            },
            function(result){
                app.focusSiteStaff = JSON.parse(result);
            });
            
          },

          fetchContractorSiteStaff: function(){
            $.post(baseurl+"induction_health_safety/fetch_contractor_site_staff",
            {
              project_id: '<?php echo $project_id; ?>'
            },
            function(result){
                app.contractorSiteStaff = JSON.parse(result);
            });
            
          },

          fetchOtherCompany: function(){
            $.post(baseurl+"induction_health_safety/view_other_company",
            {
            },
            function(result){
                app.otherCopmany = JSON.parse(result);
            });
            
          },


          fetchOtherCompanySiteStaff: function(){
            $.post(baseurl+"induction_health_safety/view_other_company_site_staff",
            {
            },
            function(result){
                app.otherCopmanySiteStaff = JSON.parse(result);
            });
            
          },

          show_other_company_details: function(){
            $("#frm_add_other_company").modal("show");
          },

          change_other_company:function(){
            this.company_name = "";
            if(this.other_company_id == 0){
              this.show_select_other_company = true;
            }else{
              this.show_select_other_company = false;
            }
          },

          add_other_company: function(){
            $.post(baseurl+"induction_health_safety/insert_induction_project_other_site_staff",
            {
              project_id: '<?php echo $project_id; ?>',
              checkedOCompanyList: this.checkedOCompanyList
            },
            function(result){
              app.projectIndOtherSiteStaffList = JSON.parse(result);
            });
          },

          remove_project_other_sitestaff: function(induction_project_other_site_staff_id){
            $.post(baseurl+"induction_health_safety/remove_induction_project_other_site_staff",
            {
              project_id: '<?php echo $project_id; ?>',
              induction_project_other_site_staff_id: induction_project_other_site_staff_id
            },
            function(result){
              app.projectIndOtherSiteStaffList = JSON.parse(result);
            });

          },

          select_other_company: function(induction_other_company_sitestaff_id){
            this.induction_other_company_sitestaff_id = induction_other_company_sitestaff_id;

            for (var key in app.otherCopmanySiteStaff) {
              if(app.otherCopmanySiteStaff[key].induction_other_company_sitestaff_id == this.induction_other_company_sitestaff_id){
                this.other_company_id = app.otherCopmanySiteStaff[key].induction_other_company_id;
                this.company_name = "";
                this.other_site_staff_fname = app.otherCopmanySiteStaff[key].site_staff_fname;
                this.other_site_staff_sname = app.otherCopmanySiteStaff[key].site_staff_sname;
                this.other_mobile_number = app.otherCopmanySiteStaff[key].mobile_number;
                this.other_email = app.otherCopmanySiteStaff[key].email;
              }
            }
            this.show_select_other_company = false;
           
            this.show_add_other_company = false;

            $("#frm_add_new_other_company").modal("show");
          },

          update_other_company: function(){
            $.post(baseurl+"induction_health_safety/update_other_company_site_staff",
            {
              induction_other_company_sitestaff_id: this.induction_other_company_sitestaff_id,
              company_id: this.other_company_id,
              company_name: this.company_name,
              site_staff_fname: this.other_site_staff_fname,
              site_staff_sname: this.other_site_staff_sname,
              mobile_number: this.other_mobile_number,
              email: this.other_email
            },
            function(result){
                app.otherCopmanySiteStaff = JSON.parse(result);
                $.post(baseurl+"induction_health_safety/view_other_company",
                {
                },
                function(result){
                    app.otherCopmany = JSON.parse(result);
                });
            });
          },

          delete_other_company: function(){
            $.post(baseurl+"induction_health_safety/delete_other_company_site_staff",
            {
              induction_other_company_sitestaff_id: this.induction_other_company_sitestaff_id
            },
            function(result){
                app.otherCopmanySiteStaff = JSON.parse(result);
            });
          },

          sendFocusSSLink: function(){
            this.siteStaffType = 1;
            this.sendTo = this.checkedFocusSSEmails;
            var a = this.sendTo+"";
            var result_arr =  a.split(',');
            
            var legnth = result_arr.length;
            if(legnth > 1){
              this.alt_email = true;
              this.altEmail = "";
            }else{
              this.alt_email = false;
            }
            var x = 0;
            this.selectedSiteStaffEmail = [];
            this.selectedSiteStaff = [];
            while(x < legnth){
                var checked_val = result_arr[x];
                var checked_val_arr = checked_val.split('/');

                this.selectedSiteStaffEmail.push(checked_val_arr[0]);
                this.selectedSiteStaff.push(checked_val_arr[1]);
                x++;
            }
            this.subjectEmail = "Induction Video Link",
            $.post(baseurl+"induction_health_safety/get_video_link_email_defaults",
            {
              contractor: 'fss'
            },
            function(result){
                $("#contentEmail").val(result + "\n\nPlease see this link: "+baseurl+"direct_contractor_upload/contractor_induction_video?project_id="+project_id);
            });
            this.sendTo = this.selectedSiteStaffEmail.toString();
            this.selectedSiteStaff = this.selectedSiteStaff.toString();
            
          },

          view_qrcode: function(){
            $.post(baseurl+"projects/induction_qrcode_file",
            {
              project_id: project_id
            },
            function(result){
              alert(result);
              $("#frm_induction_video_qrcode").modal('show');
            });
            
          },

          generateBlueBook: function(){
            $.post(baseurl+"induction_health_safety/qrcode_blue_book",
            {
              project_id: project_id
            },
            function(result){
              window.open(baseurl+'docs/temp/blue_book_cover.pdf');
            });
            
          },

          sendEmail: function(){
            var message = $("#contentEmail").val();

            $.post(baseurl+"induction_health_safety/send_induction_video_link",
            {
              project_id: project_id,
              selectedSiteStaff: this.selectedSiteStaff,
              siteStaffType: this.siteStaffType,
              email_to: this.sendTo,
              altEmail: this.altEmail,
              email_cc: this.ccEmail,
              email_bcc: this.bccEmail,
              subject: this.subjectEmail,
              message: message
            },
            function(result){
              alert(result);
            });
            
          },

        },

        computed:{
          filterFocusSiteStaff: function(){
            this.focusSiteStaff.sort((a,b) => a.user_first_name > b.user_first_name ? 1 : -1);

            return this.focusSiteStaff.filter((siteStaff) => {
              return siteStaff.user_first_name.toLowerCase().match(this.searchFocusSiteStaff.toLowerCase())  || siteStaff.user_last_name.toLowerCase().match(this.searchFocusSiteStaff.toLowerCase());
            });
          },

          filterContractorSiteStaff: function(){

            this.contractorSiteStaff.sort((a,b) => a.site_staff_fname > b.site_staff_fname ? 1 : -1);
            if(this.filterCSSBy == 1){
              return this.contractorSiteStaff.filter((siteStaff) => {
                return siteStaff.site_staff_fname.toLowerCase().match(this.searchContractorStaff.toLowerCase())  || siteStaff.site_staff_sname.toLowerCase().match(this.searchContractorStaff.toLowerCase());
              });
            }else{
              return this.contractorSiteStaff.filter((siteStaff) => {
                return siteStaff.company_name.toLowerCase().match(this.searchContractorStaff.toLowerCase());
              });
            }
           
          },

          

        },

    });
</script>
