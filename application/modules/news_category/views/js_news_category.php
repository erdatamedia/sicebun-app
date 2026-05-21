<div class="modal fade" id="form_modal" role="dialog" aria-labelledby="editLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editLabel" id="title">
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
								<label>Nama Ketegori</label>
								<input type="text" class="form-control" name="name" id="name" required />
							</div>
						</div>
						<div class="col-lg-6 col-sm-12 col-12">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="reset" class="btn btn-secondary" data-dismiss="modal">
						Batal
					</button>
					<button type="submit" class="btn btn-success">
						Simpan
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deleteLabel">
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
					<p>Aksi ini mengakibatkan data akan hilang selamanya, anda yakin?</p>
				</div>
				<div class="modal-footer">
					<button type="reset" class="btn btn-secondary" data-dismiss="modal">
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

	const id_column = 'id_category'
	let table = null;
	const form_modal = $('#form_modal');
	const delete_modal = $('#delete_modal');
	const input_delete_id = $('#delete_id');
	const input_id = $('#id');
	const input_name = $('#name');

	function editRow(id){
		$.ajax({
			url: '<?= base_url('api/'.$module.'/one') ?>',
			data: {id:id},
			type: 'GET',
			beforeSend: function (xhr, settings){
				mApp.block(form_modal);
			},
			success: function(response){
				input_id.val(response[id_column]);
				input_name.val(response['name']);
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
	});

	$('#save_form').submit(function(event) {
		event.preventDefault();
		$.ajax({
			url: '<?= base_url('api/'.$module.'/save') ?>',
			data: {
				id: input_id.val(),
				name: input_name.val(),
			},
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
				toastr.success(error['message']);
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
		const columns = [
		{title: 'ID',data: id_column},
		{title: 'Kategori',data: 'name'},
		{title: 'Jumlah Berita',data: 'total_news'},
		{title: 'Aksi',data: id_column},
		]
		table = $('#table').DataTable({
			'responsive': true,
			'processing': true,
			'serverSide': true,
			'ajax': '<?= base_url('api/'.$module.'/all') ?>',
			'columns': columns,
			'columnDefs': [
			{
				'render': function (data, type, row) {
					return `
					<div class="btn-group btn-group-sm" role="group" aria-label="First group">
					<button class="btn btn-warning" onclick="editRow(${row[id_column]})" data-toggle="modal" data-target="#form_modal"><i class="fa fa-pencil"></i></button>
					<button class="btn btn-danger" onclick="deleteRow(${row[id_column]})" data-toggle="modal" data-target="#delete_modal"><i class="fa fa-trash"></i></button>
					</div>`;
				},
				'targets': columns.length-1
			},
			]
		});

		$('#refresh_btn').click(()=>{
			table.ajax.reload(null, false);
		});

	});
</script>

