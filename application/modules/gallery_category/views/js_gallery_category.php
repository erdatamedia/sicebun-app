<div class="modal fade" id="form_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel" id="title">
					Edit
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						&times;
					</span>
				</button>
			</div>
			<form id="save_form">
				<input type="hidden" name="id" id="id" />
				<div class="modal-body">
					<div class="row">
						<div class="col-lg-6 col-sm-12 col-12">
							<div class="form-group">
								<label>Name</label>
								<input type="text" class="form-control" name="name" id="name" required>
							</div>
							<div class="form-group">
								<label>File</label>
								<input type="file" class="form-control" name="file" id="file" accept="image/png, image/jpeg" required>
							</div>
						</div>
						<div class="col-lg-6 col-sm-12 col-12">
							<div class="form-group">
								<label>Preview</label>
								<br>
								<a target="_blank" href="#">
									<img id="preview_img" src=" " style="width: 150px; height: 150px; object-fit: contain; border: 1px solid #e4e4e4;"/>
								</a>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="reset" class="btn btn-secondary" data-dismiss="modal">
						Batal
					</button>
					<button type="submit" class="btn btn-primary">
						Simpan
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">
					Hapus
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						&times;
					</span>
				</button>
			</div>
			<form id="delete_form">
				<input type="hidden" name="id" id="delete_id" />
				<div class="modal-body">
					<p>This action cannot be undone, are you sure want to delete this record?</p>
				</div>
				<div class="modal-footer">
					<button type="reset" class="btn btn-success" data-dismiss="modal">
						Batal
					</button>
					<button type="submit" class="btn btn-danger">
						Hapus
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">

	let table = null;
	const form_modal = $('#form_modal');
	const delete_modal = $('#delete_modal');
	const input_delete_id = $('#delete_id');
	const input_id = $('#id');
	const input_name = $('#name');
	const input_file = $('#file');
	const preview_img = $('#preview_img');

	function editRow(id){
		input_file.prop('required', false);
		$.ajax({
			url: '<?= base_url('api/'.$module.'/one') ?>',
			data: {id:id},
			type: 'GET',
			beforeSend: function (xhr, settings){
				mApp.block(form_modal);
			},
			success: function(response){
				input_id.val(response['id']);
				input_name.val(response['name']);
				preview_img.attr('src','<?= base_url('assets/uploads/category/thumb/') ?>'+response['thumb']);
				preview_img.parent().attr('href','<?= base_url('assets/uploads/category/') ?>'+response['file']);
				mApp.unblock(form_modal);
			},
			error: function(error){
				mApp.unblock(form_modal);
			}
		});
	}

	form_modal.on('hidden.bs.modal', function(e) {
		input_id.val('');
		input_name.val('');
		input_file.val('');
		input_file.prop('required', true);
		preview_img.attr('src',' ');
		preview_img.parent().attr('href','#');
	});

	$('#save_form').submit(function(event) {
		event.preventDefault();
		var formData = new FormData();
		formData.append('name', input_name.val());
		formData.append('file', input_file[0].files[0]);
		if (input_id.val()){
			formData.append('id', input_id.val());
		}
		$.ajax({
			url: '<?= base_url('api/'.$module.'/save') ?>',
			data: formData,
			contentType: false,
			processData: false,
			type: 'POST',
			beforeSend: function (xhr, settings){
				mApp.block(form_modal);
			},
			success: function(response){
				toastr.success("Data Disimpan.");
				table.ajax.reload(null, false);
				form_modal.modal('hide');
				mApp.unblock(form_modal);
			},
			error: function(error){
				mApp.unblock(form_modal);
			}
		});
	});

	function deleteRow(id){
		input_delete_id.val(id);
	}

	$('#delete_form').submit(function(event) {
		event.preventDefault();
		$.ajax({
			url: '<?= base_url('api/'.$module.'/delete') ?>',
			data: {
				id: input_delete_id.val(),
			},
			type: 'POST',
			beforeSend: function (xhr, settings){
				mApp.block(delete_form);
			},
			success: function(response){
				toastr.success("Data Dihapus.");
				table.ajax.reload(null, false);
				delete_modal.modal('hide');
				mApp.unblock(delete_form);
			},
			error: function(error){
				mApp.unblock(delete_form);
			}
		});
	});

	delete_modal.on('hidden.bs.modal', function(e) {
		input_delete_id.val('');
	});

	$(document).ready( function () {

		$("#file").change((e)=>{
			readURL(e.currentTarget,'#preview_img');
		});

		table = $('#table').DataTable({
			'responsive': true,
			'processing': true,
			'serverSide': true,
			'ajax': '<?= base_url('api/'.$module.'/all') ?>',
			'columnDefs': [
			{
				'render': function (data, type, row) {
					return `<a target="_blank" href="<?= base_url('assets/uploads/category/'); ?>${row[3]}">
					<img onerror="imgError(this);" src="<?= base_url('assets/uploads/category/thumb/'); ?>${row[2]}" style="width: 36px; height: 36px; object-fit: contain; border: 1px solid #e4e4e4;" />
					</a>`;
				},
				'targets': 2
			},
			{
				'render': function (data, type, row) {
					return `
					<div class="btn-group btn-group-sm" role="group" aria-label="First group">
					<button class="btn btn-warning" onclick="editRow(${row[0]})" data-toggle="modal" data-target="#form_modal"><i class="fa fa-pencil"></i></button>
					<button class="btn btn-danger" onclick="deleteRow(${row[0]})" data-toggle="modal" data-target="#delete_modal"><i class="fa fa-trash"></i></button>
					</div>`;
				},
				'targets': 3
			},
			]
		});

		$('#refresh_btn').click(()=>{
			table.ajax.reload(null, false);
		});

	});
</script>

