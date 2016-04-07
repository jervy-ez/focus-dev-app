<!DOCTYPE html>
<html lang="en">
<?php
include "includes/head.php";

?>

<body>


<?php
include "includes/navigation.php";
?>
        

                <div class="col-lg-12 col-md-12" style="padding-top: 3%;">
              
                       
                    <div class="row">
                    	
                        <div class="col-md-12" style="padding-top:1%;">
                        <div class="col-md-1"></div>
                        <div class="col-md-2"></div>
                        <div class="col-md-1"></div>    
							<?php
								$array=sql();
								if($array['stud_info']==1){
							?>
                            <div class="col-md-2"><a href="student_selection.php" style="color:#FFFFFF; text-decoration:none;">
                                <button class="btn  btn-primary  bg-blue btn-block btn-flat" style="padding:30px; background-color:#191970;">
                                    <i class="fa fa-group fa-5x"></i> <br>                                     
                                    <strong style="margin-left:-15px;">Student Information</strong>
                                </button></a>
                            </div> 
                            <?php
								}
								else{
							?>
                            
                            
                            <?php }?>
                            
                            <?php
								if($array['stud_info']==1){
							?>
                           <div class="col-md-2"><a href="../e-reg/reportStudType.php" style="color:#FFFFFF; text-decoration:none">
                                <button class="btn btn-primary bg-blue btn-block btn-flat" style="padding:30px; background-color:#191970;" name="document">
                                    <i class="fa fa-bar-chart-o fa-5x"></i> <br> 
                                    <strong style="margin-left:-15px;">Statistics</strong>
                                </button></a>
                            </div>
                             <?php
								}
								else{
							?>
                            
                            <?php }?>
                            <div class="col-md-2"></div>
                            <br><br><br><br><br><br><br><br><br>
                            <div class="col-md-3"></div>
                            <?php
								
								if($array['act']==1){
							?>
                            <div class="col-md-2"><a href="activityDB.php" style="color:#FFFFFF; text-decoration:none">
                                <button class="btn btn-primary bg-blue btn-block btn-flat" style="padding:30px; background-color:#191970;" name="activity">
                                    <i class="fa fa-tasks fa-5x"></i> <br> 
                                    <strong style="margin-left:-15px;">Activities</strong>
                                </button></a>
                            </div>
                             <?php
								}
								else{
							?>
       						
                            <?php
								}
								if($array['org']==1){
							?>
                            <div class="col-md-2"><a href="organizationDB.php" style="color:#FFFFFF; text-decoration:none">
                                <button class="btn btn-primary bg-blue btn-block btn-flat" style="padding:30px; background-color:#191970;" name="activity">
                                    <i class="fa fa-sitemap fa-5x"></i> <br> 
                                    <strong style="margin-left:-15px;">Organizations</strong>
                                </button></a>
                            </div>
                             <?php
								}
								else{
							?>
                           
                            <?php
								}
								if($array['violtn']==1){
							?>
                            <div class="col-md-2"><a href="violationDB.php" style="color:#FFFFFF; text-decoration:none">
                                <button class="btn btn-primary bg-blue btn-block btn-flat" style="padding:30px; background-color:#191970;" name="activity">
                                    <i class="fa fa-legal fa-5x"></i> <br> 
                                    <strong style="margin-left:-15px;">Violations & Sanctions</strong>
                                </button></a>
                            </div>
                            <?php
								}
								else{
							?>
                            
                            <?php
								}
								
							?>
                        	
                            
                            
                        </div>

                    </div>
                     
                          
                 
                    <!-- /.panel .chat-panel -->
                </div>
					


    <!-- jQuery -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="bower_components/raphael/raphael-min.js"></script>
    <script src="bower_components/morrisjs/morris.min.js"></script>
    <script src="js/morris-data.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>

</body>

</html>
