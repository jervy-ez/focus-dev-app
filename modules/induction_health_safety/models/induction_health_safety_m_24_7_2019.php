<?php

class Induction_health_safety_m extends CI_Model{	
	
	function __construct(){
		parent::__construct();
	}
	
	public function fetch_user_emergency_contacts(){
		$query = $this->db->query("SELECT * from sitestaff_emergency_contacts where is_contractors = 0");
		return $query;
	}

	public function fetch_cont_sitestaff_emergency_contacts(){
		$query = $this->db->query("SELECT * from sitestaff_emergency_contacts where is_contractors = 1");
		return $query;
	}

	public function fetch_user_licences_certificates(){
		$query = $this->db->query("SELECT * from user_license_certificates where is_contractors = 0 order by expiration_date");
		return $query;
	}

	public function fetch_cont_sitestaff_licences_certificates(){
		$query = $this->db->query("SELECT * from user_license_certificates where is_contractors = 1");
		return $query;
	}

	public function fetch_user_training_records(){
		$query = $this->db->query("SELECT * from traning_records where is_contractors = 0");
		return $query;
	}

	public function fetch_cont_sitestaff_training_records(){
		$query = $this->db->query("SELECT * from traning_records where is_contractors = 1");
		return $query;
	}

	public function fetch_license_cert_type(){
		$query = $this->db->query("SELECT * from licences_certs_types");
		return $query;
	}

	public function add_emergency_contact($user_id,$contact_fname,$contact_sname,$relation,$contacts,$is_contractors){
		$this->db->query("INSERT INTO sitestaff_emergency_contacts (user_id,contact_fname,contact_sname,relation,contacts,is_contractors) values('$user_id','$contact_fname','$contact_sname','$relation','$contacts','$is_contractors') ");
	}

	public function update_emergency_contact($sitestaff_emergency_contacts_id,$contact_fname,$contact_sname,$relation,$contacts){
		$this->db->query("UPDATE sitestaff_emergency_contacts 
							set contact_fname = '$contact_fname',
								contact_sname = '$contact_sname',
								relation = '$relation',
								contacts = '$contacts'
							where sitestaff_emergency_contacts_id = '$sitestaff_emergency_contacts_id' ");
	}

	public function remove_emergency_contact($sitestaff_emergency_contacts_id){
		$this->db->query("DELETE from sitestaff_emergency_contacts where sitestaff_emergency_contacts_id = '$sitestaff_emergency_contacts_id' ");
	}

	public function remove_site_staff_emergency_contact($user_id){
		$this->db->query("DELETE from sitestaff_emergency_contacts where user_id = '$user_id' ");
	}

	public function add_licence_cert($user_id,$LCtype,$LCName,$lcNumber,$expirationDate,$is_contractors,$has_expiration){
		$today_date = date("Y-m-d");
		$this->db->query("INSERT INTO user_license_certificates (date_entered,user_id,is_license,type,number,expiration_date,has_expiration,is_contractors) values('$today_date','$user_id','$LCtype','$LCName','$lcNumber','$expirationDate','$has_expiration','$is_contractors') ");
	}

	public function update_licence_cert($user_license_certificates_id,$LCtype,$LCName,$lcNumber,$expirationDate,$has_expiration){
		$this->db->query("UPDATE user_license_certificates set is_license = '$LCtype', type = '$LCName' , number = '$lcNumber', expiration_date = '$expirationDate', has_expiration = '$has_expiration' where user_license_certificates_id = '$user_license_certificates_id'");
	}

	public function remove_licence_cert($user_license_certificates_id){
		$this->db->query("DELETE from user_license_certificates where user_license_certificates_id = '$user_license_certificates_id'");
	}

	public function add_training($user_id,$trainingName,$trainingDate,$trainingLoc,$is_contractors){
		$today_date = date("Y-m-d");
		$this->db->query("INSERT INTO traning_records (date_entered,user_id,training_type,date_undertaken,taken_with,is_contractors) values('$today_date','$user_id','$trainingName','$trainingDate','$trainingLoc','$is_contractors') ");
	}

	public function update_training($training_records_id,$trainingName,$trainingDate,$trainingLoc){
		$this->db->query("UPDATE traning_records set training_type = '$trainingName',date_undertaken = '$trainingDate',taken_with = '$trainingLoc' where training_records_id = '$training_records_id' ");
	}

	public function remove_training($training_records_id){
		$this->db->query("DELETE from traning_records where training_records_id = '$training_records_id' ");
	}
	
	public function add_lc_type($lctypename){
		$this->db->query("INSERT INTO licences_certs_types (lc_type_name) values('$lctypename') ");	
	}

	public function temp_cont_site_staff(){
		$query = $this->db->query("SELECT distinct(a.company_id), b.company_name from temp_contractors_staff a
										left join company_details b on b.company_id = a.company_id
										where is_approved = 0
								");	
		return $query;
	}

	public function temp_cont_site_staff_submitted(){
		$query = $this->db->query("SELECT distinct(a.company_id), b.company_name from temp_contractors_staff a
										left join company_details b on b.company_id = a.company_id
										where is_approved = 0
											and induction_date_sent != '0000-00-00'
								");	
		return $query;
	}

	

	

	public function fetch_temp_sitestaff(){
		$query = $this->db->query("SELECT * from temp_contractors_staff a 
										left join company_details b on b.company_id = a.company_id
										where a.is_approved = 0
								");	
		return $query;
	}

	public function fetch_temp_lc(){
		$query = $this->db->query("SELECT * from temp_certificate_license");	
		return $query;
	}

	public function fetch_temp_training(){
		$query = $this->db->query("SELECT * from temp_trainings");	
		return $query;
	}

	public function update_induction_email_sent($company_id){
		$query = $this->db->query("UPDATE company_details set induction_email_stat = '1' where company_id = '$company_id'");
	}

	public function fetch_temp_contractors(){
		$query = $this->db->query("SELECT distinct(a.company_id), b.company_name, b.induction_date_sent, d.first_name, d.last_name, e.general_email,f.*
									from temp_contractors_staff a
										left join company_details b on a.company_id = b.company_id
										LEFT join contact_person_company c on c.company_id = b.company_id
										LEFT JOIN contact_person d ON  d.contact_person_id =  c.contact_person_id
										LEFT JOIN email e ON e.email_id =  d.email_id
										LEFT JOIN contact_number f ON  f.contact_number_id = d.contact_number_id
									WHERE b.active = '1'
										AND is_approved = 0
										group by b.company_name
								");	
		return $query;
	}

	public function fetch_temp_cont_sitestaff($company_id){
		$query = $this->db->query("SELECT * from temp_contractors_staff where company_id = '$company_id' and is_approved = 0");	
		return $query;
	}

	public function fetch_selected_temp_cont_sitestaff($temp_contractors_staff_id){
		$query = $this->db->query("SELECT * from temp_contractors_staff where temp_contractors_staff_id = '$temp_contractors_staff_id'");	
		return $query;
	}

	public function fetch_temp_license_cert($temp_contractors_staff_id){
		$query = $this->db->query("SELECT * FROM `temp_certificate_license` WHERE `temp_contractors_staff_id` = '$temp_contractors_staff_id'");
		return $query;	
	}

	public function fetch_sitestaff_licences_certificates($user_id){
		$query = $this->db->query("SELECT * from user_license_certificates where user_id = '$user_id' and is_contractors = 1");
		return $query;
	}

	public function fetch_temp_trainings($temp_contractors_staff_id){
		$query = $this->db->query("SELECT * FROM `temp_trainings` WHERE `temp_contractors_staff_id` = '$temp_contractors_staff_id'");
		return $query;	
	}

	public function fetch_sitestaff_training($user_id){
		$query = $this->db->query("SELECT * from traning_records where user_id = '$user_id' and is_contractors = 1");
		return $query;
	}

	public function set_temp_data_approve($temp_contractors_staff_id){
		$query = $this->db->query("UPDATE temp_contractors_staff set is_approved = '1' where temp_contractors_staff_id = '$temp_contractors_staff_id'");
	}

	public function fetch_induction_projects_list($induction_categories,$induction_project_value){
		$query_text = "SELECT * from project where job_category in (".$induction_categories.") and budget_estimate_total >= '".$induction_project_value."' or project_total >= '".$induction_project_value."' and is_active = 1 order by project_id desc";
		$query = $this->db->query($query_text);
		return $query;
	}

	public function fetch_induction_projects_details($project_id){
		$query = $this->db->query("SELECT isd.*,
											a.project_name,
											a.project_date,
											a.date_site_commencement,
											a.date_site_finish,
											a.job_type, 
											a.shop_tenancy_number,
											sc.common_name, 
											b.brand_id,
											b.has_brand_logo, 
											site_address.*, 
											concat(pm.user_first_name,' ',pm.user_last_name) as pm_name,
											pm_contacts.area_code as pm_area_code,
											pm_contacts.office_number as pm_office_number,
											pm_contacts.direct_number as pm_direct_number,
											pm_contacts.mobile_number as pm_mobile_number,
											pm_email.general_email as pm_email,
											concat(lh.user_first_name,' ',lh.user_last_name) as lh_name,
											lh_contacts.area_code as lh_area_code,
											lh_contacts.office_number as lh_office_number,
											lh_contacts.direct_number as lh_direct_number,
											lh_contacts.mobile_number as lh_mobile_number,
											lh_email.general_email as lh_email,
											d.lh_name as manual_lh,
											d.lh_contact as manual_lh_contact,
											d.lh_email as manual_lh_email
									from project a
									left join induction_slide_details isd on isd.project_id = a.project_id
									left join brand b on b.brand_id = a.brand_id
									left join (
													SELECT ad.address_detail_id, 
															ad.unit_number,
															ad.unit_level,
															ad.street,
															ad.po_box,
															ag.suburb,
															ag.postcode,
															s.name,
															s.shortname,
															s.country,
															s.phone_area_code
														from address_detail ad
															left join address_general ag on ag.general_address_id = ad.general_address_id
															left join states s on s.id = ag.state_id
												) site_address on site_address.address_detail_id = a.address_id
									left join (SELECT user_id, user_first_name, user_last_name, user_contact_number_id, user_email_id from users) pm on pm.user_id = a.project_manager_id
									left join (SELECT * from contact_number) pm_contacts on pm_contacts.contact_number_id = pm.user_contact_number_id
									left join (SELECT email_id, general_email from email) pm_email on pm_email.email_id = pm.user_email_id
									left join project_schedule c on c.project_id = a.project_id
									left join (SELECT user_id, user_first_name, user_last_name,user_contact_number_id, user_email_id from users) lh on lh.user_id = c.leading_hand_id
									left join (SELECT * from contact_number) lh_contacts on lh_contacts.contact_number_id = lh.user_contact_number_id
									left join (SELECT email_id, general_email from email) lh_email on lh_email.email_id = lh.user_email_id
									left join manual_entry_project_schedule d on d.project_schedule_id = c.project_schedule_id
									left join shopping_center sc on sc.detail_address_id = a.address_id 
									where a.project_id = '$project_id'
							");
		return $query;
	}

	public function fetch_induction_slide_detials($project_id){
		$query = $this->db->query("SELECT * from induction_slide_details where project_id = '$project_id'");
		return $query;
	}

	public function update_induction_slide_project_outline($project_id,$project_outline){
		$query = $this->db->query("SELECT * from induction_slide_details where project_id = '$project_id'");
		if($query->num_rows == 0){
			$this->db->query("INSERT INTO induction_slide_details (project_id,project_ouline_text) values('$project_id','$project_outline')");
		}else{
			$this->db->query("UPDATE induction_slide_details set project_ouline_text = '$project_outline' where project_id = '$project_id'");
		}
		
	}

	public function update_induction_slide_access($project_id,$file_name){

		$query = $this->db->query("SELECT * from induction_slide_details where project_id = '$project_id'");
		if($query->num_rows == 0){
			$this->db->query("INSERT INTO induction_slide_details (project_id,acces_map_filename) values('$project_id','$file_name')");
		}else{
			$this->db->query("UPDATE induction_slide_details set acces_map_filename = '$file_name' where project_id = '$project_id'");
		}
	}

	public function update_induction_videos($project_id){

		$query = $this->db->query("SELECT * from induction_slides_videos where project_id = '$project_id'");
		if($query->num_rows == 0){
			$this->db->query("INSERT INTO induction_slides_videos (project_id,video_uploaded) values('$project_id','1')");
		}else{
			$this->db->query("UPDATE induction_slides_videos set video_uploaded = '1' where project_id = '$project_id'");
		}

	}

	public function update_induction_slide_amenities($project_id,$file_name){

		$query = $this->db->query("SELECT * from induction_slide_details where project_id = '$project_id'");
		if($query->num_rows == 0){
			$this->db->query("INSERT INTO induction_slide_details (project_id,amenities_map_filename) values('$project_id','$file_name')");
		}else{
			$this->db->query("UPDATE induction_slide_details set amenities_map_filename = '$file_name' where project_id = '$project_id'");
		}
	}

	public function update_induction_slide_emergency($project_id,$epr_medical_name,$epr_medical_contact,$epr_medical_address,$epr_emergency_name,$epr_emergency_contacts,$epr_emergency_address,$med_add_unit_level,$med_add_number,$med_add_street,$med_state_name,$med_add_suburb,$med_add_postcode,$emer_add_unit_level,$emer_add_number,$emer_add_street,$emer_state_name,$emer_add_suburb,$emer_add_postcode
){
		
		$query = $this->db->query("SELECT * from induction_slide_details where project_id = '$project_id'");
		if($query->num_rows == 0){
			$this->db->query("INSERT INTO induction_slide_details 
								(project_id,epr_medical_name,epr_medical_contact,epr_medical_address,epr_emergency_name,epr_emergency_contacts,epr_emergency_address,medical_add_unitlevel,medical_add_number,medical_add_street,medical_add_state,medical_add_suburb,medical_add_postcode,emergency_add_unitlevel,emergency_add_number,emergency_add_street,emergency_add_state,emergency_add_suburb,emergency_add_postcode) 
						values('$project_id','$epr_medical_name','$epr_medical_contact','$epr_medical_address','$epr_emergency_name','$epr_emergency_contacts','$epr_emergency_address','$med_add_unit_level','$med_add_number','$med_add_street','$med_state_name','$med_add_suburb','$med_add_postcode','$emer_add_unit_level','$emer_add_number','$emer_add_street','$emer_state_name','$emer_add_suburb','$emer_add_postcode'
)");
		}else{    
			$this->db->query("UPDATE induction_slide_details 
								set epr_medical_name = '$epr_medical_name',
									epr_medical_contact = '$epr_medical_contact',
									epr_medical_address = '$epr_medical_address',
									epr_emergency_name = '$epr_emergency_name',
									epr_emergency_contacts = '$epr_emergency_contacts',
									epr_emergency_address = '$epr_emergency_address',
									medical_add_unitlevel = '$med_add_unit_level',
									medical_add_number = '$med_add_number',
									medical_add_street = '$med_add_street',
									medical_add_state = '$med_state_name',
									medical_add_suburb = '$med_add_suburb',
									medical_add_postcode = '$med_add_postcode',
									emergency_add_unitlevel = '$emer_add_unit_level',
									emergency_add_number = '$emer_add_number',
									emergency_add_street = '$emer_add_street',
									emergency_add_state = '$emer_state_name',
									emergency_add_suburb = '$emer_add_suburb',
									emergency_add_postcode = '$emer_add_postcode'
							where project_id = '$project_id'
						");
		}
	}
	
	public function update_induction_slide_ppe($project_id,$ppe_selected){

		$query = $this->db->query("SELECT * from induction_slide_details where project_id = '$project_id'");
		if($query->num_rows == 0){
			$this->db->query("INSERT INTO induction_slide_details (project_id,ppe_list) values('$project_id','$ppe_selected')");
		}else{
			$this->db->query("UPDATE induction_slide_details set ppe_list = '$ppe_selected' where project_id = '$project_id'");
		}
	}
	

	public function update_induction_slide_site_hours($project_id,$generalSiteHours,$noisySiteHours,$otherSiteHours){
		$this->db->query("UPDATE induction_slide_details set general_site_hours = '$generalSiteHours',noisy_site_hours = '$noisySiteHours',other_site_hours = '$otherSiteHours' where project_id = '$project_id'");
	}

	public function set_cleared_slides($slide_no,$project_id){
		$slide = '%'.$slide_no.'%';

		
		$query = $this->db->query("SELECT * from induction_slide_details where project_id = '$project_id'");
		if($query->num_rows == 0){
			$this->db->query("INSERT INTO induction_slide_details (project_id,cleared_slides) values('$project_id','$slide_no')");
		}else{
			$query1 = $this->db->query("SELECT * from induction_slide_details where cleared_slides like '$slide' and project_id = '$project_id'");
			if($query1->num_rows == 0){
				$cleared_slide = $slide_no;
				foreach ($query->result_array() as $row){
					$cleared_slide = $row['cleared_slides'].",".$slide_no;
				}

				$this->db->query("UPDATE induction_slide_details set cleared_slides = '$cleared_slide' where project_id = '$project_id'");
			}
		}

		
	}

	public function set_inductions_as_saved($project_id){
		$this->db->query("UPDATE induction_slide_details set is_saved = 1 where project_id = '$project_id'");
	}

	public function fetch_induction_videos(){

		$query = $this->db->query("SELECT a.*, b.video_uploaded, c.project_name, d.company_name
									from induction_slide_details a
										left join induction_slides_videos b on b.project_id = a.project_id
										left join project c on c.project_id = a.project_id
										left join company_details d on d.company_id = c.client_id
									where a.is_saved = 1
								");
		return $query;

	}

	public function fetch_induction_videos_generated($project_id){

		$query = $this->db->query("SELECT * from induction_slides_videos
									where project_id = '$project_id'
										and video_uploaded = 1
								");
		if($query->num_rows == 0){
			return 0;
		}else{
			return 1;
		}

	}

	public function fetch_state(){

		$query = $this->db->query("SELECT * from states");
		return $query;

	}

	public function fetch_general_address_suburb($state_name){

		$query = $this->db->query("SELECT distinct(a.suburb) as suburb from address_general a
										left join states b on b.id = a.state_id
									where b.name = '$state_name'
									order by a.suburb
								");
		return $query;

	}

	public function fetch_general_address($suburb){

		$query = $this->db->query("SELECT postcode from address_general a
										left join states b on b.id = a.state_id
									where a.suburb = '$suburb'
									order by a.postcode
								");
		return $query;

	}

	public function fetch_induction_video_person_watch($project_id){
		$query = $this->db->query("SELECT a.*, 
										b.site_staff_fname, 
										b.site_staff_sname, 
										d.company_name, 
										c.project_name, 
										e.user_first_name, 
										e.user_last_name,
										f.site_staff_fname as o_site_staff_fname,
										f.site_staff_sname as o_site_staff_sname,
										g.company_name as other_company_name 
									from induction_video_person_watch a
										left join contractos_site_staff b on b.contractor_site_staff_id = a.contractor_site_staff_id
										left join induction_other_company_sitestaff f on f.induction_other_company_sitestaff_id = a.contractor_site_staff_id
										left join induction_other_company g on g.induction_other_company_id = f.induction_other_company_id				
										left join users e on e.user_id = a.contractor_site_staff_id
										left join project c on c.project_id = a.project_id
										left join company_details d on d.company_id = b.company_id
										where a.project_id = '$project_id'
								");
		return $query;
	}

	public function fetch_other_company(){

		$query = $this->db->query("SELECT * from induction_other_company");
		return $query;

	}

	public function view_other_company_site_staff(){

		$query = $this->db->query("SELECT 
										a.*, 
										b.company_name
									from induction_other_company_sitestaff a
									left join induction_other_company b on b.induction_other_company_id = a.induction_other_company_id
								");
		return $query;
	}

	public function view_project_other_company_site_staff($project_id){

		$query = $this->db->query("SELECT 
										a.*, 
										b.company_name, 
										ivpw.induction_video_person_watch_id,
										ies.induction_email_send_id from induction_other_company_sitestaff a
									left join induction_other_company b on b.induction_other_company_id = a.induction_other_company_id
									left join (SELECT * from `induction_video_person_watch` where project_id = '$projec_id' and site_staff_type = 3) as ivpw ON ivpw.contractor_site_staff_id = a.induction_other_company_sitestaff_id
									left join (SELECT * from `induction_email_send` where project_id = '$projec_id' and  site_staff_type = 3) as ies ON ies.site_staff_id = a.induction_other_company_sitestaff_id
								");
		return $query;
	}

	public function insert_other_company($company_name){
		$this->db->query("INSERT INTO induction_other_company (company_name) values('$company_name')");

		$last_insert_id = $this->db->insert_id();
		return $last_insert_id;
	}

	public function insert_other_company_site_staff($company_id,$site_staff_fname,$site_staff_sname,$mobile_number,$email){
		$this->db->query("INSERT INTO induction_other_company_sitestaff (induction_other_company_id,site_staff_fname,site_staff_sname,mobile_number,email) values('$company_id','$site_staff_fname','$site_staff_sname','$mobile_number','$email')");
	}

	public function update_other_company_site_staff($induction_other_company_sitestaff_id,$company_id,$site_staff_fname,$site_staff_sname,$mobile_number,$email){
		$this->db->query("UPDATE induction_other_company_sitestaff 
								set induction_other_company_id = '$company_id',
									site_staff_fname = '$site_staff_fname',
									site_staff_sname = '$site_staff_sname',
									mobile_number = '$mobile_number',
									email = '$email'
								where induction_other_company_sitestaff_id = '$induction_other_company_sitestaff_id'
						");
	}

	public function delete_other_company_site_staff($induction_other_company_sitestaff_id){
		$this->db->query("DELETE from induction_other_company_sitestaff where induction_other_company_sitestaff_id = '$induction_other_company_sitestaff_id'");
	}

	public function get_brand_logo($brand_id){
		$query = $this->db->query("SELECT * from brand where brand_id = '$brand_id' and has_brand_logo = 1 ");
		if($query->num_rows == 0){
			return 0;
		}else{
			return 1;
		}
	}

	public function update_brand($brand_id){
		$this->db->query("UPDATE brand set has_brand_logo = 1 where brand_id = '$brand_id' ");
	}
	
	public function get_project_contractors($project_id){
		$query = $this->db->query("SELECT a.company_client_id from works a
									left join contractos_site_staff b on b.company_id = a.company_client_id
									where a.project_id = '$project_id'
										and a.company_client_id != 0
										and b.contractor_site_staff_id is not null
									group by a.company_client_id
								");
		return $query;
	}

	public function save_induction_video_link_sent($project_id,$site_staff_id,$site_staff_type){
		$query = $this->db->query("SELECT * from induction_email_send where project_id = '$project_id' and site_staff_id = '$site_staff_id' and site_staff_type = '$site_staff_type' ");
		if($query->num_rows == 0){
			$this->db->query("INSERT INTO induction_email_send (project_id,site_staff_id,site_staff_type) values('$project_id','$site_staff_id','$site_staff_type')");
		}
	}

	public function get_user_site_staff($projec_id){
		$query = $this->db->query("SELECT `users`.*,
									`email`.`general_email`,
									`contact_number`.*,
									`company_details`.`company_name`,
									`notes`.`comments`,
									`users`.`if_admin`,
									`users`.`direct_company`,
									`users`.`is_third_party`,
									`email`.`personal_email`,
									IF(`i_email_send`.induction_email_send_id is null, 0,1)	as induction_email_send_id,	
									IF(`i_video_watch`.induction_video_person_watch_id is null, 0,1) as induction_video_watch
											FROM `users` 
											LEFT JOIN (SELECT * from induction_email_send where project_id = '$projec_id' and site_staff_type = 1) i_email_send on i_email_send.site_staff_id = `users`.user_id
											LEFT JOIN (SELECT * from induction_video_person_watch where project_id = '$projec_id' and site_staff_type = 1)  i_video_watch on i_video_watch.contractor_site_staff_id = `users`.user_id
											LEFT JOIN `email` ON `email`.`email_id` = `users`.`user_email_id`
											LEFT JOIN `contact_number` ON `contact_number`.`contact_number_id` = `users`.`user_contact_number_id`
											LEFT JOIN `company_details` ON `company_details`.`company_id` = `users`.`user_focus_company_id`
											LEFT JOIN `notes` ON `notes`.`notes_id` = `users`.`user_comments_id`
											WHERE `users`.`is_active` = '1' 
												and `users`.`is_site_staff` = '1'
											ORDER BY `users`.`user_focus_company_id` ASC,`users`.`user_first_name` ASC
								");
		return $query;
	}

	function fetch_site_staff($project_id){
		$query = $this->db->query("SELECT 
										a.*, 
										REPLACE(b.company_name, '&apos;', '`') as company_name, 
										c.*, 
										d.*, 
										e.*, 
										ivpw.induction_video_person_watch_id as ss_watched, 
										ies.induction_email_send_id 
										from contractos_site_staff a 
											left join company_details b on b.company_id = a.company_id  
											left join contact_person_company c on c.company_id = b.company_id
											left join contact_person d on d.contact_person_id = c.contact_person_id
											left join email e on e.email_id = d.email_id
											LEFT JOIN (SELECT * from `induction_video_person_watch` where project_id = '$project_id' and site_staff_type = 2) as ivpw ON ivpw.contractor_site_staff_id = a.contractor_site_staff_id
											LEFT JOIN (SELECT * from `induction_email_send` where project_id = '$project_id' and site_staff_type = 2) as ies ON ies.site_staff_id = a.contractor_site_staff_id
											
											where c.is_primary = 1
											order by b.company_name
									");	

		return $query;

	}

	public function fetch_induction_projects(){

		$query = $this->db->query("SELECT a.project_id as project_number, 
									a.*, 
									b.*,
									c.video_uploaded,
									d.company_name,
									f.postcode
								from project a
									left join induction_slide_details b on b.project_id = a.project_id
									left join induction_slides_videos c on c.project_id = a.project_id
									left join company_details d on d.company_id = a.client_id
									left join address_detail e on e.address_detail_id = a.address_id
									left join address_general f on f.general_address_id = e.general_address_id
								where a.job_category in ('Full Fitout', 'Kiosk', 'Refurbishment')
									and a.budget_estimate_total >= 50000
									and a.is_active = 1
									and STR_TO_DATE(a.project_date,'%d/%m/%Y') > '2019-02-18'
								order by a.project_id desc
								");
		return $query;

	}

	public function fetch_project_induction_other_sitestaff($project_id){
		$query = $this->db->query("SELECT *,
										ivpw.induction_video_person_watch_id as ss_watched, 
										ies.induction_email_send_id 
									from induction_project_other_site_staff a
										left join induction_other_company_sitestaff b on b.induction_other_company_sitestaff_id = a.induction_other_company_site_staff_id
										left join induction_other_company c on c.induction_other_company_id = b.induction_other_company_id
										LEFT JOIN (SELECT * from `induction_video_person_watch` where project_id = '$project_id' and site_staff_type = 3) as ivpw ON ivpw.contractor_site_staff_id = a.induction_other_company_site_staff_id
										LEFT JOIN (SELECT * from `induction_email_send` where project_id = '$project_id' and site_staff_type = 3) as ies ON ies.site_staff_id = a.induction_other_company_site_staff_id
									where a.project_id = '$project_id'
								");
		return $query;
		
	}

	public function insert_induction_project_other_site_staff($project_id,$induction_other_company_site_staff_id){
		$query = $this->db->query("SELECT * from induction_project_other_site_staff where project_id = '$project_id' and induction_other_company_site_staff_id = '$induction_other_company_site_staff_id'");
		if($query->num_rows == 0){
			$this->db->query("INSERT INTO induction_project_other_site_staff (induction_other_company_site_staff_id,project_id) values('$induction_other_company_site_staff_id','$project_id')");
		}
	}

	public function remove_induction_project_other_site_staff($induction_project_other_site_staff_id){
		$this->db->query("DELETE from induction_project_other_site_staff where induction_project_other_site_staff_id = '$induction_project_other_site_staff_id'");
	}

	public function project_is_exempted_induction(){
		$query = $this->db->query("SELECT * from induction_exempted_projects a
										left join project b on b.project_id = a.project_id
								");
		return $query;
	}

	public function fetch_induction_postcode_filters(){
		$query = $this->db->query("SELECT * from induction_postcode_filters");
		return $query;
	}

	public function fetch_all_pa(){
		$query = $this->db->query("SELECT * from users where user_role_id = 2 and is_active = 1");
		return $query;
	}
	
}