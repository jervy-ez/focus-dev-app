<?php $this->load->module('company'); ?>
<?php $this->load->module('projects'); ?>
<?php $this->load->module('attachments'); ?>
<?php $this->load->module('send_emails'); ?>
<div class="container-fluid" style = "padding: 0px">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<ul class="nav nav-tabs">
			<?php if($this->session->userdata('is_admin') == 1 ): ?>
			<li class = "active">
				<a href="#send_to_client" data-toggle="tab"><i class="fa fa-envelope-o"></i> Send to Client</a>
			</li>
			<?php endif; ?>
			<li>
				<a href="#send_to_contractor" onclick = "view_send_contractor()" data-toggle="tab"><i class="fa fa-envelope-o"></i>Send to Contractor</a>
			</li>
		</ul>
		<div class="tab-content">
			<?php if($this->session->userdata('is_admin') == 1 ): ?>
			<div class="tab-pane fade in  clearfix active" id = "send_to_client">
				<!-- Send to client -->
				<?php echo $this->send_emails->send_to_client_view(); ?>
				<!-- Send to Client -->
			</div>
			<?php endif; ?>
			<div class="tab-pane fade in  clearfix" id = "send_to_contractor">
				<?php echo $this->send_emails->send_to_contractor_view(); ?>
			</div>
		</div>
	</div>
</div>