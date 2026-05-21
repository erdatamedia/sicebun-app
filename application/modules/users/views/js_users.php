<div class="modal fade" id="form_modal" tabindex="-1" role="dialog" aria-labelledby="editLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editLabel" id="title">
					Form Pengguna
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
							<div class="form-group">
								<label>Email</label>
								<input type="email" class="form-control" name="email" id="email" required />
								<small>Password default adalah 123456, bisa diganti saat masuk aplikasi mobile</small>
							</div>
							<div class="form-group">
								<label>Nama</label>
								<input type="text" class="form-control" name="name" id="name" required />
							</div>
							<div class="form-group">
								<label>No. HP / WA</label>
								<input type="text" class="form-control" name="phone" id="phone" required />
							</div>
							<div id="passTrigger" class="m-checkbox-list" style="display: none;">
								<label class="m-checkbox">
									<input type="checkbox" onchange="togglePass(this)" id="ganti">
									Ganti password
									<span></span>
								</label>
							</div>
							<div class="form-group" id="p1" style="display: none;">
								<label>Password Baru</label>
								<input type="text" class="form-control" id="password" />
							</div>
							<div class="form-group" id="p2" style="display: none;">
								<label>Konfirmasi Password</label>
								<input type="text" class="form-control" id="konfirmasi" />
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Peran</label>
								<select class="w-100 form-control" class="peran" id="peran">
									<option value="">Pilih</option>
									<option value="inseminator">Inseminator</option>
									<option value="peneliti">Peneliti</option>
									<option value="admin">Admin</option>
								</select>
							</div>
							<div class="form-group">
								<label>Alamat</label>
								<textarea type="text" class="form-control" name="address" id="address"></textarea>
							</div>
							<div class="form-group">
								<label>Foto</label>
								<div class="custom-file">
									<input type="file" class="custom-file-input" id="photo" name="photo" />
									<label class="custom-file-label" for="photo">Pilih Foto</label>
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

	const id_column = 'id_user'
	let table = null
	const form_modal = $('#form_modal')
	const delete_modal = $('#delete_modal')
	const input_delete_id = $('#delete_id')
	const input_id = $('#id')
	const input_email = $('#email')
	const input_name = $('#name')
	const input_phone = $('#phone')
	const input_peran = $('#peran')
	const input_address = $('#address')
	const input_photo = $('#photo')
	
	const passTrigger = $('#passTrigger')
	const p1 = $('#p1')
	const p2 = $('#p2')

	const input_ganti = $('#ganti')
	const input_password = $('#password')
	const input_konfirmasi = $('#konfirmasi')

	function togglePass(el) {
		if (el.checked) {
			p1.show()
			p2.show()
		} else {
			p1.hide()
			p2.hide()
			input_password.val('')
			input_konfirmasi.val('')
		}
	}

	function editRow(el,id){
		const editBtn = $(el)
		$.ajax({
			url: '<?= base_url('api/'.$module.'/one') ?>',
			data: { id:id },
			type: 'GET',
			beforeSend: function (xhr, settings){
				editBtn.prop('disabled',true)
				editBtn.html('<i class="fa fa-spin fa-spinner"></i>')
			},
			success: function(response){
				input_id.val(response[id_column])

				input_email.val(response['email'])
				input_name.val(response['name'])
				input_phone.val(response['phone'])
				input_peran.val(response['peran'])
				input_address.val(response['address'])
				
				editBtn.prop('disabled',false)
				editBtn.html('<i class="fa fa-pencil"></i>')
				form_modal.modal('show')

				passTrigger.show()

			},
			error: function(error){
				editBtn.prop('disabled',false)
				editBtn.html('<i class="fa fa-pencil"></i>')
			}
		})
	}

	form_modal.on('hidden.bs.modal', function(e) {
		input_id.val('')
		input_email.val('')
		input_name.val('')
		input_phone.val('')
		input_peran.val('')
		input_address.val('')
		input_photo.val('')
		$('.custom-file-label').text('Pilih Foto')

		passTrigger.hide()
	})

	$('#save_form').submit(function(event) {
		event.preventDefault()
		var formData = new FormData()
		formData.append('email',input_email.val())
		formData.append('name',input_name.val())
		formData.append('phone',input_phone.val())
		formData.append('peran',input_peran.val())
		formData.append('address',input_address.val())

		if (input_ganti.prop('checked')) {
			formData.append('ganti',input_ganti.val())
			formData.append('password',input_password.val())
			formData.append('konfirm',input_konfirmasi.val())
		}
		if (input_photo.val()) {
			formData.append('photo',input_photo[0].files[0])
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
				if (response['status']) {
					toastr.success(response['msg'])
					table.ajax.reload(null, false)
					form_modal.modal('hide')
				} else {
					toastr.error(response['msg'])
				}

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
		{title: 'ID',data: id_column},
		{title: 'Email',data: 'email'},
		{title: 'Nama',data: 'name'},
		{title: 'Alamat',data: 'address'},
		{title: 'No HP/WA',data: 'phone'},
		{title: 'Peran',data: 'peran'},
		{
			title: 'Foto',data: id_column, sortable: false,
			render: function (data, type, row) {
				const photo = `<img src="${row['photo']}" onerror="imgError(this)" style="width: 75px; cursor: pointer;" />`
				return photo
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

				let email = `<button class="btn btn-primary" onclick="sendEmail('${row['email']}')"><i class="fa fa-envelope"></i></button>`
				email = ``

				return `
				<div class="btn-group btn-group-sm" role="group" aria-label="First group">
				${activeBtn}
				${email}
				<button class="btn btn-warning" onclick="editRow(this,${row[id_column]})"><i class="fa fa-pencil"></i></button>
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

