<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
	require '../includes/connect.php';

?>
<?php
include "includes/navigation.php";?>
<head>
    <title>Oro Site High School | Student Profiling</title>
	<link href="img/logo1.gif" rel="icon">
    <link href="extras/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.css" rel="stylesheet">    
    <link href="extras/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>


<body> 
<?php
		if(isset($_GET['id'])){
			$select_id=preg_replace('#[^0-9]#i','',$_GET['id']);
			$sql=mysql_query("SELECT * FROM er_section WHERE er_sec_id='$select_id' LIMIT 1");
			$count=mysql_num_rows($sql);
			if($count > 0) {
				while($row=mysql_fetch_array($sql)){
					$section_id = $row["er_sec_id"];
					$er_section_name = $row["er_sec_name"];
					$er_grade_and_section = $row["er_sec_grade_level"];
		}}else {
		echo "No record found!";
		exit();	
		}}else{
		echo "No record found";
	exit();}?>
<?php
	$arr=sql();
	$list2 = "";
	
		$q2 = mysql_query("SELECT * FROM sp_student");
		$count2 = mysql_num_rows($q2);
		if($count2 > 0) {
			while($row = mysql_fetch_assoc($q2)){
	  $Student_ID = $row["sp_stud_id"];
					$row["sp_stud_LRN"];
					$row["sp_stud_Lname"];
					$row["sp_stud_Fname"];
					$row["sp_stud_Minitial"];
					$row["sp_stud_house_no"];
					$row["sp_stud_brgy"];
					$row["sp_stud_city"];
					$row["sp_stud_province"];
					$row["sp_stud_age"];
	  $Student_Birthday =strftime("%B %d %Y", strtotime($row["sp_stud_birthdate"]));
					$row["sp_stud_gender"];
					$row["sp_stud_contact_no"];
					$row["sp_stud_religion"];
					$row["sp_stud_mother_tongue"];
					$row["sp_stud_IP"];
					$row["sp_stud_prev_school_attended"];
					$row["sp_stud_type"];
					$row["sp_remarks"];
		
		$list2 .= '<tr>
<td>' . $row["sp_stud_id"]. '</td><td>' . $row["sp_stud_LRN"] .',&nbsp;'.row["sp_stud_Fname"].'&nbsp;'.$row["sp_stud_Minitial"].'</td><td>' . $row["sp_stud_gender"] . '</td><td>' . $row["sp_stud_birthdate"]. '</td><td>' . $row["sp_stud_age"] . '</td><td>' . $row["sp_stud_mother_tongue"]. '</td><td>' . $row["sp_stud_IP"] . '</td><td>' . $row["sp_stud_religion"] . '</td><td>' . $row["sp_stud_house_no"] . '</td><td>' . $row["sp_stud_brgy"] . '</td><td>' . $row["sp_stud_city"] . '</td><td>' . $row["sp_stud_province"]. '</td>
						<td>' . 
		<a href='student_profile_new.php?viewid=$Student_ID' class='fa fa-user fa-5x' title='View Profile'></a> . '</td>
						</tr>';
	}}
	?>
	
          <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"> 
                            <a href="student.php" align="right"><img src="img/GoBackButton.png" height="50": width="50"></a>    
 <?php						 	
	if($array['sched']==1){?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	
						<a href="schedule.php?getid=<?php echo $section_id ?>" align="right">View Schedule</a>
							<?php }?> 
                            <?php if($array['sched']==1){?>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                            <a href="subjects.php?getid=<?php echo $section_id ?>" align="right">View Subjects</a>
                            <?php }?>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|
       <h3><center>List of Students in Grade <em><strong><?php echo $er_sec_grade_level ?></strong></em> Section <em><strong>
	   <?php echo $er_sec_name ?>
	   | S.Y. 2016-2017</strong></em></center></h3>
           <p><center>Adviser:<strong><?php echo $er_section_name ?></strong></center></p>
                        </div>
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example" style="font-size:12px">
                                    <thead>
                                        <tr>
                                        	 <th>LRN</th>	<th>Name</th>	<th>Sex</th>	<th>Birthdate</th>	<th>Age</th>
                                             <th>Mother Tongue</th>		<th>IP</th>		<th>Religion</th>		<th>House No.</th>
                                             <th>Barangay</th>	<th>City</th>	<th>Province</th>	<th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                          <?php echo $dynamicList2; ?>
                                    
                                    </tbody>
                                </table>
                          </div></div></div></div></div></div></div>
</body>

</html>
