<div class="col-lg-3 col-md-12">
	<div class="pad-left-15 clearfix">										
		<label class="project_name">Attachments</p></label>
	</div>
</div>
<div class="col-sm-9 m-bottom-10">
	<form action="<?php echo base_url(); ?>attachments/do_upload/<?php echo $project_id ?>" method="post" enctype="multipart/form-data">
						<!--<input type="submit" value="Upload" class = "btn btn-default btn-sm pull-right">-->
		<span class="btn btn-primary btn-sm btn-file pull-right">
		    <i class = "fa fa-plus-circle"></i> Attach File<input type="file" name="userfile[]" multiple="multiple" accept="image/*" onchange="form.submit()">
		</span>
		<select name="attachment_type" id="attachment_type" class = "input-sm pull-right"></select>
		<label for = "attachment_type" class = "pull-right">Attachment Type: </label>
	</form>
			<!-- <button type = "button" class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#attach_file_modal"><i class = "fa fa-plus-circle"></i> Attach File/s</button>-->
</div>
<div class="col-sm-12 clearfix">
	<div class="box-area pad-10">
		<table class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead> 
				<tr> 
					<th>File Name</th> 
					<th>Type</th> 
					<th>Modified</th> 
					<th width = 20px>Selected</th>
				</tr> 
			</thead> 
			<tbody>
				<?php $this->attachments->display_project_attachments(); ?>
			</tbody>
		</table>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="change_attachment_type_conf_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
	   	<div class="modal-content">
		    <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			    <h4 class="modal-title">Confirmation</h4>
		    </div>
		    <div class="modal-body">
		    	Are you sure you want to change Attachment Type of the selected Attachment?
		    </div>
		    <div class="modal-footer">
		      	<button type = "button" id = "change_attach_type_yes" class="btn btn-danger" data-dismiss="modal">Yes</button>
		       	<button type="button" id = "change_attach_type_no" class="btn btn-default" data-dismiss="modal">No</button>
		    </div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="attachment_type_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
	   	<div class="modal-content">
		    <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			    <h4 class="modal-title">Attachment Types</h4>
		    </div>
		    <div class="modal-body" style = "height: 300px">
		    	<div class="col-sm-12">
			    	<input type="text" id = "txt_attachment_type" class = "input-sm" placeholder = "Attachment Type">
			    	<button type = "button" class = "btn btn-primary" id = "btn_add_attachment_type">Add</button>
			    	<button type = "button" class = "btn btn-success" id = "btn_update_attachment_type">Update</button>
			    	<button type = "button" class = "btn btn-danger" id = "btn_delete_attachment_type">Delete</button>
		    	</div>
		    	<div class = "col-sm-12" style = "height: 10px"></div>
		      	<div id = "table_attachment_type" style = " height: 200px; overflow:auto" class = "col-sm-12">
		      	</div>
		    </div>
		    <!--<div class="modal-footer">
		      	<button type = "button" id = "change_attach_type_yes" class="btn btn-danger" data-dismiss="modal">Add New</button>
		       	<button type="button" id = "change_attach_type_no" class="btn btn-default" data-dismiss="modal">No</button>
		    </div>-->
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="show_attachment_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style = "width: 1000px">
	   	<div class="modal-content">
		    <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			    <h4 class="modal-title"><label for="" id = "attachment_filename"></label></h4>
		    </div>
		    <div class="modal-body" style = "height: 500px">
		    	<iframe id = "iframe_view_attachment" src="" frameborder="0" style = "width: 100%; height: 480px; oveflow: auto"></iframe>
		    </div>
		    <div class="modal-footer">
		    	<button type = "button" id = "download_attachment" class="btn btn-success" data-dismiss="modal">Download</button>
		      	<button type = "button" id = "remove_attachment" class="btn btn-danger" data-dismiss="modal">Delete</button>
		       	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		    </div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->