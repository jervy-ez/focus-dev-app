<div class = "col-sm-1">
	<label for="">Clients Name:</label>
</div>
<div  class = "col-sm-11">
	<label for=""><?php echo $compname ?></label>
</div>
<div class = "col-sm-12"></div>
<div class = "col-sm-1">
	<label for="">Email Address:</label>
</div>
<div  class = "col-sm-10">
	<input type="text" id = "client_email" class = "input-sm form-control" title = "E-mail Address" placeholder = "Enter Client Email-Address" value = "<?php echo $client_email ?>">
</div>
<div class = "col-sm-12"></div>
<div class = "col-sm-1">
	<label for="">Subject:</label>
</div>
<div  class = "col-sm-10">
	<input type="text" id = "subject" class = "input-sm form-control" title = "Subject" placeholder = "Enter Subject">
</div>
<div  class = "col-sm-1">
	<button type = "button" id = "send_attachment" class="btn btn-success">Send</button>
</div>
<div class = "col-sm-12"></div>
<div class = "col-sm-1">
	<label for="">Create PDF:</label>
</div>
<div  class = "col-sm-10">
	<select id="project_forms" class = "input-sm">
		<option value=0 disabled selected>Select Project Reports</option>
		<option value=1>Project Summary with Cost</option>
		<option value=2>Project Summary without Cost</option>
		<option value=3>Joinery Summary with Cost</option>
		<option value=4>Joinery Summary without Cost</option>
		<option value=5>Variation Summary with Cost</option>
		<option value=6>Variation Summary without Cost</option>
		<option value=7>Quotation and Contract</option>
		<option value=8>Request for New Trade Form</option>
	</select>
	<label for="" id = "lbl_attached"><span class="fa fa-paperclip">Files Attached</span></label>
	<label for="" id = "lbl_no_attached"><b>No Files Attached</b></span></label>
</div>
<div class = "col-sm-12" style = "height: 20px"></div>
<div class="col-sm-6 box-area pad-10">
	<table id="companyTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
		<caption><b class= "pull-center">Attachments</b> <button id = "merge_attach" class = "btn btn-success btn-sm pull-right">Merge and Attached</button></caption>
		<thead>
			<tr>
				<th width = 20px></th>
				<th>Description</th>
				<th>Date Modified</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
			<?php echo $this->send_emails->display_project_forms(); ?>
		</tbody>
	</table>
</div>
<div class="col-sm-6">
	<label for="">Message:</label>
	<textarea name="" id="message_to_client" class = "col-sm-12" style = "height: 200px"></textarea>
</div>