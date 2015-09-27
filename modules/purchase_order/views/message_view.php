<p style = "font-family: Arial,Helvetica Neue,Helvetica,sans-serif;font-size: 12px; "><?php echo $message ?></p>

<div style = "font-family: Arial,Helvetica Neue,Helvetica,sans-serif;font-size: 12px; ">
	Regards,<br><br><br>
	<?php echo $sender ?><br/>
	<a href = "#" class = "font_email"><?php echo $send_email ?></a>
</div>
<br>
<img src="<?php echo base_url() ?>img/focus-logo-print.jpg" alt="" style = "width: 200px"> 
<!-- <img src="http://www.google.com/intl/en_ALL/images/logos/images_logo_lg.gif" alt="picture1" /> -->
<br>
<br>
<div style = "color: #6699FF; font-family: Arial,Helvetica Neue,Helvetica,sans-serif;font-size: 12px; ">
	<?php echo $comp_phone ?><br>
	<?php echo $comp_address_line1 ?><br>
	<?php echo $comp_address_line2 ?><br>
	<br>
	<?php echo $comp_address_line3 ?><br>
	<br>
	<?php 
		if($focus_company_id == 1 || $focus_company_id == 4 ){
	?>
		<table style = "color: #6699FF; font-family: Arial,Helvetica Neue,Helvetica,sans-serif;font-size: 12px; ">
			<tr>
				<td><?php echo $comp_name ?></td>
				<td><?php echo $abn1 ?></td>
			</tr>
			<tr>
				<td><?php echo $comp_name2 ?></td>
				<td><?php echo $abn2 ?></td>
			</tr>
			<tr>
				<td><?php echo $comp_name3 ?></td>
				<td><?php echo $abn3 ?></td>
			</tr>
		</table>
	<?php
		}
		if($focus_company_id == 2 || $focus_company_id == 5 ){
	?>
		<table style = "color: #6699FF; font-family: Arial,Helvetica Neue,Helvetica,sans-serif;font-size: 12px; ">
			<tr>
				<td><?php echo $comp_name2 ?></td>
				<td><?php echo $abn2 ?></td>
			</tr>
		</table>
	<?php
		}
		if($focus_company_id == 6){
	?>
		<table style = "color: #6699FF; font-family: Arial,Helvetica Neue,Helvetica,sans-serif;font-size: 12px; ">
			<tr>
				<td><?php echo $comp_name3 ?></td>
				<td><?php echo $abn3 ?></td>
			</tr>
		</table>
	<?php
		}
		 ?><br>
</div>