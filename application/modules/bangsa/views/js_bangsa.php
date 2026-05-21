<div class="modal fade" id="form_modal" role="dialog" aria-labelledby="editLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editLabel" id="title">
					Form Bangsa
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
						<div class="col-sm-12">
							<div class="form-group m-form__group">
								<label for="nama_bangsa">
									Nama Bangsa
								</label>
								<input type="text" class="form-control" id="nama_bangsa" name="nama_bangsa" required />
							</div>
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
	toastr.options = {
		'preventDuplicates': true,
	}

	const id_column = 'id_bangsa'
	let table = null
	const form_modal = $('#form_modal')
	const delete_modal = $('#delete_modal')
	const input_delete_id = $('#delete_id')
	const input_id = $('#id')

	const input_nama_bangsa 		= $('#nama_bangsa')


	function editRow(el, id,photo){
		const editBtn = $(el)
		$.ajax({
			url: '<?= base_url('api/'.$module.'/one') ?>',
			data: {id:id},
			type: 'GET',
			beforeSend: function (xhr, settings){
				editBtn.prop('disabled',true)
				editBtn.html('<i class="fa fa-spin fa-spinner"></i>')
			},
			success: function(response){
				input_id.val(response[id_column])
				
				input_nama_bangsa.val(response['nama_bangsa'])

				editBtn.prop('disabled',false)
				editBtn.html('<i class="fa fa-pencil"></i>')
				form_modal.modal('show')
			},
			error: function(error){
				editBtn.prop('disabled',false)
				editBtn.html('<i class="fa fa-pencil"></i>')
			}
		})
	}

	form_modal.on('hidden.bs.modal', function(e) {
		input_id.val('')
		input_nama_bangsa.val('')
	})

	$('#save_form').submit(function(event) {
		event.preventDefault()
		var formData = new FormData()
		formData.append('nama_bangsa',input_nama_bangsa.val())
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
				mApp.block(form_modal)
			},
			success: function(response){
				toastr.success("Data Disimpan.")
				table.ajax.reload(null, false)
				form_modal.modal('hide')
				mApp.unblock(form_modal)
			},
			error: function(error){
				toastr.error(error.responseText)
				mApp.unblock(form_modal)
			}
		})
	})

	function deleteRow(id){
		input_delete_id.val(id)
	}

	$('#delete_form').submit(function(event) {
		event.preventDefault()
		$.ajax({
			url: '<?= base_url('api/'.$module.'/delete') ?>',
			data: {
				id: input_delete_id.val(),
			},
			type: 'POST',
			beforeSend: function (xhr, settings){
				mApp.block(delete_form)
			},
			success: function(response){
				toastr.success("Data Dihapus.")
				table.ajax.reload(null, false)
				delete_modal.modal('hide')
				mApp.unblock(delete_form)
			},
			error: function(error){
				toastr.error(error.responseText)
				mApp.unblock(delete_form)
			}
		})
	})

	delete_modal.on('hidden.bs.modal', function(e) {
		input_delete_id.val('')
	})

	$(document).ready( function () {
		const img = new Viewer(document.getElementById('tbody'))

		const columns = [
		{ title: 'ID',data: id_column},
		{ title: 'No Sapi',data: 'nama_bangsa'},
		{ 
			title: 'Aksi', data: id_column, sortable: false,
			render: function (data, type, row) {
				return `
				<div class="btn-group btn-group-sm" role="group" aria-label="children group">
				<button class="btn btn-warning" onclick="editRow(this,${row[id_column]},0)"><i class="fa fa-pencil"></i></button>
				<button class="btn btn-danger" onclick="deleteRow(${row[id_column]})" data-toggle="modal" data-target="#delete_modal"><i class="fa fa-trash"></i></button>
				</div>`
			},
		},
		]
		table = $('#table').DataTable({
			drawCallback: function(settings) {
				img.update()
			},
			'responsive': true,
			'processing': true,
			'serverSide': true,
			'ajax': '<?= base_url('api/'.$module.'/all') ?>',
			'columns': columns,
		})

		$('#refresh_btn').click(()=>{
			table.ajax.reload(null, false)
		})

	})
</script>

