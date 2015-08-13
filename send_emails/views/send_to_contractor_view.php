<div class="col-sm-5 pad-10">
	<div class="col-sm-12">
		<div class="col-sm-3"><button type = "button" class = "btn btn-success btn-sm form-control" id = "btn_creat_cqr">Create CQR</button></div>
		<div class="col-sm-3"><button type = "button" class = "btn btn-success btn-sm form-control" id = "btn_create_cpo">Create CPO</button></div>
		<?php if($this->session->userdata('is_admin') == 1 ): ?><div class="col-sm-3"><button type = "button" class = "btn btn-success btn-sm form-control" id = "btn_send_cqr">Send CQR</button></div><?php endif; ?>
		<?php if($this->session->userdata('is_admin') == 1 ): ?><div class="col-sm-3"><button type = "button" class = "btn btn-success btn-sm form-control" id = "btn_send_cpo">Send CPO</button></div><?php endif; ?>
	</div>
	<div class="col-sm-12" style = "height: 20px"></div>
	<div class="col-sm-12" id="contractor_list" style = "height: 400px; overflow: auto">
	</div>
</div>
<div class="col-sm-7 pad-10">
	<div class="col-sm-2">
		<label for="">Send to:</label>
	</div>
	<div class="col-sm-10">
		<textarea id="contractor_email_add" class = "form-control" style = "height: 50px" placeholder = "E-mail Address" disabled></textarea>
	</div>
	<div class="col-sm-12"></div>
	<div class="col-sm-2">
		<label for="">Alt. E-mail/s:</label>
	</div>
	<div class="col-sm-10">
		<input type = "text" id="contractor_alt_email_add" class = "form-control" placeholder = "E-mail Address">
	</div>
	<div class="col-sm-12"></div>
	<div class="col-sm-2">
		<label for="">cc:</label>
	</div>
	<div class="col-sm-10">
		<input type = "text" id="contractor_cc" class = "form-control" placeholder = "E-mail Address">
	</div>
	<div class="col-sm-12"></div>
	<div class="col-sm-2">
		<label for="">bcc:</label>
	</div>
	<div class="col-sm-10">
		<input type = "text" id="contractor_bcc" class = "form-control" placeholder = "E-mail Address">
	</div>
	<div class="col-sm-12"></div>
	<div class="col-sm-2 pad-10">
		<label for="">Subject:</label>
	</div>
	<div class="col-sm-9">
		<input type="text" class = "form-control input-sm" id = "contractor_subject" placeholder = "Subject">
	</div>
	<div class = "col-sm-1"><button type = "button" class = "btn btn-success btn-xs form-control" id = "send_email" disabled>Send</button></div>
	<div class="col-sm-12"><label for="">Message</label></div>
	<div class="col-sm-12">
		<textarea id="contractor_email_message" class = "form-control" style = "height: 200px" placeholder = "Content"></textarea>
	</div>
</div>