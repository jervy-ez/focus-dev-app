<!DOCTYPE html>
<html lang="en">

<?php include "includes/head.php";
include "storescripts/connect_to_mysql.php";
?>
<?php
if(isset($_GET['viewid'])){
	$targetID=preg_replace('#[^0-9]#i','',$_GET['viewid']);
	$sqlO=mysql_query("SELECT * FROM er_section WHERE er_sec_id='$targetID' LIMIT 1");
	$actCount=mysql_num_rows($sqlO);
	if($actCount > 0) {
		while($row=mysql_fetch_array($sqlO)){
			$sec_id = $row["er_sec_id"];
			$er_sec_name = $row["er_sec_name"];
			$er_sec_grade_level = $row["er_sec_grade_level"];
		}
	}else {
		echo "No record found!";
		exit();	
	}
}else{
	echo "No record found";
	exit();
}
?>
<?php
$array=sql();
$dynamicList2 = "";
	
$query2 = mysql_query("SELECT * FROM sis_student WHERE er_sec_id='$targetID'");
$employeeCount2 = mysql_num_rows($query2);
if($employeeCount2 > 0) {
	while($row = mysql_fetch_array($query2)){
			$Student_ID = $row["SIS_stud_id"];
		$Student_LRN = $row["SIS_stud_LRN"];
		$stud_Lname = $row["SIS_stud_Lname"];
		$stud_Fname = $row["SIS_stud_Fname"];
		$stud_Mname = $row["SIS_stud_Minitial"];
		$Student_House_No = $row["SIS_stud_house_no"];
		$Student_Barangay = $row["SIS_stud_brgy"];
		$Student_City = $row["SIS_stud_city"];
		$Student_Province = $row["SIS_stud_province"];
		$Student_Age = $row["SIS_stud_age"];
		$Student_Birthday = $row["SIS_stud_birthdate"];
		$Student_Sex = $row["SIS_stud_gender"];
		$Student_Contact_No = $row["SIS_stud_contact_no"];
		$Student_Religion = $row["SIS_stud_religion"];
		$Student_Mother_Tongue = $row["SIS_stud_mother_tongue"];
		$Student_IP = $row["SIS_stud_IP"];
		$Student_Prev_School_Attended = $row["SIS_stud_prev_school_attended"];
		$Student_Type = $row["SIS_stud_type"];
		$Student_Remarks = $row["SIS_remarks"];
		$dynamicList2 .= '<tr>
						<td>' . $Student_LRN . '</td>
						<td>' . $stud_Lname .',&nbsp;'.$stud_Fname.'&nbsp;'.$stud_Mname.'</td>
						<td>' . $Student_Sex . '</td>
						<td>' . $Student_Birthday . '</td>
						<td>' . $Student_Age . '</td>
						<td>' . $Student_Mother_Tongue . '</td>
						<td>' . $Student_IP . '</td>
						<td>' . $Student_Religion . '</td>
						<td>' . $Student_House_No . '</td>
						<td>' . $Student_Barangay . '</td>
						<td>' . $Student_City . '</td>
						<td>' . $Student_Province . '</td>
						<td>' . 
								"
								<a href='student_profile_new.php?viewid=$Student_ID' class='fa fa-user'>&nbsp;View Profile</a>
								" 
						. '</td>
						 </tr>';
	}
}else {
	$dynamicList = "No record yet";	
}
mysql_close();
?>
<body>
<?php
include "includes/navigation.php";
?>
    <div id="wrapper">



<div class="col-lg-12 col-md-12" style="padding-bottom:7%; padding-top: 2%;">
<!--============================================================================================================================================================================ -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							List of Students in Grade <em><strong><?php echo $er_sec_grade_level ?></strong></em> Section <em><strong><?php echo $er_sec_name ?></strong></em>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            
                            <a href="student.php" align="right">Go Back</a>
                            
                            
                             <?php
							 	
								if($array['sched']==1){
							?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="schedule.php?getid=<?php echo $sec_id ?>" align="right">View Schedule</a>
							<?php
								}
							?>
                            
                            
                            <?php
								if($array['sched']==1){
							?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                            <a href="subjects.php?getid=<?php echo $sec_id ?>" align="right">View Subjects</a>
                            <?php
								}
							?>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example" style="font-size:12px">
                                    <thead>
                                        <tr>
                                        	
                                            <th>LRN</th>
                                            <th>Name</th>
                                            <th>Sex</th>
                                            <th>Birthdate</th>
                                            <th>Age</th>
                                            <th>Mother Tongue</th>
                                            <th>IP</th>
                                            <th>Religion</th>
                                            <th>House No.</th>
                                            <th>Barangay</th>
                                            <th>City</th>
                                            <th>Province</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                          <?php echo $dynamicList2; ?>
                                    
                                    </tbody>
                                </table>
                          </div>
                            <!-- /.table-responsive -->
                        </div>
                        </div>
                    </div>
                </div>
           
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

<?php include("includes/script_foot.php"); ?>


</body>

</html>
