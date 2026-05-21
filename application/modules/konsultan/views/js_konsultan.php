<div class="modal fade" id="form_modal" role="dialog" aria-labelledby="editLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editLabel" id="title">
					Form Sapi
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
						<div class="col-sm-6">
							<div class="form-group m-form__group">
								<label for="nama">
									Nama
								</label>
								<input type="text" class="form-control" id="nama" name="nama" required />
							</div>
							<div class="form-group m-form__group">
								<label for="telp">
									Telp
								</label>
								<input type="text" class="form-control" id="telp" name="telp" required />
							</div>
							<div class="form-group m-form__group">
								<label for="asal">
									Asal
								</label>
								<input type="text" class="form-control" id="asal" name="asal" required />
							</div>
							<div class="form-group m-form__group">
								<label for="alamat">
									Alamat
								</label>
								<textarea class="form-control" id="alamat" name="alamat" required></textarea>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group m-form__group">
								<label for="link">
									Link
								</label>
								<input type="text" class="form-control" id="link" name="link" required />
							</div>
							<div class="form-group m-form__group">
								<label for="profesi">
									Profesi
								</label>
								<input type="text" class="form-control" id="profesi" name="profesi" required />
							</div>
							<div class="form-group m-form__group">
								<label for="keahlian">
									Keahlian
								</label>
								<textarea id="keahlian" class="form-control" required></textarea>
							</div>
							<div class="form-group">
								<label>Foto</label>
								<div class="custom-file">
									<input type="file" class="custom-file-input" id="foto" name="foto" />
									<label class="custom-file-label" for="foto">Pilih Foto</label>
								</div>
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

	const id_column = 'id_konsultan'
	let table = null
	const form_modal = $('#form_modal')
	const delete_modal = $('#delete_modal')
	const input_delete_id = $('#delete_id')
	const input_id = $('#id')

	const input_nama 		= $('#nama')
	const input_telp 		= $('#telp')
	const input_alamat 		= $('#alamat')
	const input_link 		= $('#link')
	const input_asal 		= $('#asal')
	const input_profesi 	= $('#profesi')
	const input_keahlian 	= $('#keahlian')
	const input_foto 		= $('#foto')
	autosize(input_alamat)
	autosize(input_keahlian)


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
				
				input_nama.val(response['nama'])
				input_telp.val(response['telp'])
				input_alamat.val(response['alamat'])
				input_link.val(response['link'])
				input_asal.val(response['asal'])
				input_profesi.val(response['profesi'])
				input_keahlian.val(response['keahlian'])
				
				autosize(input_alamat)
				autosize(input_keahlian)

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
		input_nama.val('')
		input_telp.val('')
		input_alamat.val('')
		input_link.val('')
		input_asal.val('')
		input_profesi.val('')
		input_keahlian.val('')
		input_foto.val('')
		$('.custom-file-label').text('Pilih Foto')
	})

	$('#save_form').submit(function(event) {
		event.preventDefault()
		var formData = new FormData()
		formData.append('nama',input_nama.val())
		formData.append('telp',input_telp.val())
		formData.append('asal',input_asal.val())
		formData.append('alamat',input_alamat.val())
		formData.append('link',input_link.val())
		formData.append('profesi',input_profesi.val())
		formData.append('keahlian',input_keahlian.val())
		if (input_foto.val()) {
			formData.append('foto',input_foto[0].files[0])
		}
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


	toggleActive = (e,id) => {
		const btn = $(e)
		const icon = btn.find('i')
		const is_active = ((btn.data('is_active') == '0') ? '1' : '0')
		$.ajax({
			type: 'POST',
			url: "<?php echo base_url('api/'.$module.'/activate') ?>",
			data: {id: id, is_active: is_active},
			beforeSend: () =>{ mApp.block(btn) },
			complete: () =>{ mApp.unblock(btn) },
			success: (response)=>{
				if (response) {
					btn.data('is_active', is_active)
					if (is_active=='1') {
						btn.removeClass('btn-secondary')
						btn.addClass('btn-success')
						icon.removeClass('la-toggle-off')
						icon.addClass('la-toggle-on')
					} else {
						btn.removeClass('btn-success')
						btn.addClass('btn-secondary')
						icon.removeClass('la-toggle-on')
						icon.addClass('la-toggle-off')
					}
				}
				toastr.success("Data Updated.")
			},
			error: (error) => {
				toastr.error("Cannot be Updated.")
			},
		})
	}


	$(document).ready( function () {
		const img = new Viewer(document.getElementById('tbody'))

		const columns = [
		{ title: 'ID',data: id_column },
		{ title: 'Nama',data: 'nama' },
		{ title: 'Telp',data: 'telp' },
		{ title: 'Asal',data: 'asal' },
		{ title: 'Alamat',data: 'alamat' },
		{ title: 'Link',data: 'link' },
		{ title: 'Profesi',data: 'profesi' },
		{ title: 'Keahlian',data: 'keahlian' },
		{ 
			title: 'Foto',data: 'foto',
			render: function (data, type, row) {
				const foto =  row['foto'] ? `<img class="img img-thumbnail" width="50px" height="50px" onerror="imgError(this)" src="${row['foto']}" />` : ''
				return `<div>${foto}</div>`
			},
		},
		{ 
			title: 'Aksi',data: id_column, sortable: false,
			render: function (data, type, row) {
				let activeBtn = 
				`<button type="button" class="btn btn-success" data-is_active=${row['is_active']} onclick="toggleActive(this,${row[id_column]})">
				<i class="la la-toggle-on"></i>
				</button>`

				if (row['is_active']=='0') {
					activeBtn = 
					`<button type="button" class="btn btn-secondary" data-is_active=${row['is_active']} onclick="toggleActive(this,${row[id_column]})">
					<i class="la la-toggle-off"></i>
					</button>`
				}

				return `
				<div class="btn-group btn-group-sm" role="group" aria-label="children group">
				${activeBtn}
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
			responsive: true,
			processing: true,
			serverSide: true,
			ajax: '<?= base_url('api/'.$module.'/all') ?>',
			columns: columns,
		})

		$('#refresh_btn').click(()=>{
			table.ajax.reload(null, false)
		})

	})
</script>

