<div class="modal fade" id="form_modal" role="dialog" aria-labelledby="editLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editLabel" id="title">
					Form Direktori
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
							<div class="form-group">
								<label>Nama Direktori</label>
								<input type="text" class="form-control" name="nama" id="nama" required />
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label>Cover</label>
								<div class="custom-file">
									<input type="file" class="custom-file-input" id="cover" name="cover" />
									<label class="custom-file-label" for="cover">Pilih Foto</label>
								</div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label>Informasi</label>
								<textarea type="text" class="form-control" name="informasi" id="informasi"></textarea>
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

<div class="modal fade" id="info_modal" tabindex="-1" role="dialog" aria-labelledby="infoLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="infoLabel">
					Informasi
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						&times;
					</span>
				</button>
			</div>
			<input type="hidden" name="id" id="delete_id" />
			<div class="modal-body">
				<div id="informasi_div"></div>
			</div>
			<div class="modal-footer">
				<button type="reset" class="btn btn-secondary" data-dismiss="modal">
					Tutup
				</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="foto_modal" tabindex="-1" role="dialog" aria-labelledby="infoLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="infoLabel">
					Foto
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						&times;
					</span>
				</button>
			</div>
			<form id="foto_form">
				<input type="hidden" name="id" id="foto_id" />
				<div class="modal-body">
					<div class="tab-pane" id="m_tabs_1_2" role="tabpanel">
						<div class="form-group m-form__group row">
							<div class="col-sm-12">
								<div id="dropzone" class="m-dropzone dropzone m-dropzone--primary">
									<div class="m-dropzone__msg dz-message needsclick">
										<h3 class="m-dropzone__msg-title">
											Seret dan lepas file disini atau klik untuk pilih file
										</h3>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="reset" class="btn btn-secondary" data-dismiss="modal">
						Tutup
					</button>
					<button type="submit" class="btn btn-success">
						Simpan
					</button>
				</div>
			</form>
		</div>
	</div>
</div>


<script type="text/javascript">
	Dropzone.autoDiscover = false
	toastr.options = {
		'preventDuplicates': true,
	}

	const id_column = 'id_direktori'
	let table = null
	const form_modal = $('#form_modal')
	const delete_modal = $('#delete_modal')
	const input_delete_id = $('#delete_id')
	const input_id = $('#id')
	const input_nama = $('#nama')
	const input_cover = $('#cover')
	const input_informasi = $('#informasi')
	input_informasi.summernote({
		callbacks: {
			onImageUpload: function(files) {
				uploadSummernote('#informasi', files[0], '')
			},
		}
	})

	const dropzone = new Dropzone('div#dropzone', {
		autoProcessQueue: false,
		autoQueue: false,
		acceptedFiles: 'image/*,video/*',
		maxFilesize: 5,
		url: '#',
		paramName: 'file',
		timeout: 300000,
		addRemoveLinks: true,
		success: function(files, response, event){
			mApp.unblock('Upload berhasil')
			dropzone.files.forEach((file)=>{
				dropzone.removeFile(file)
			})
		},
		init: function() {
			this.on('addedfile', function(file) { $('.dz-progress').remove() })
			this.on('removedfile', function(file) { })
		},
		dictRemoveFile: 'Hapus file',
	})

	function editRow(el,id){
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
				input_informasi.summernote('code',response['informasi'])
				autosize(input_informasi)

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
		input_cover.val('')
		input_informasi.summernote('code','')
		$('.custom-file-label').text('Pilih foto')
	})

	$('#save_form').submit(function(event) {
		event.preventDefault()
		var formData = new FormData()
		formData.append('nama',input_nama.val())
		formData.append('informasi',input_informasi.val())
		if (input_cover.val()) {
			formData.append('cover',input_cover[0].files[0])
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
				mApp.unblock(delete_form)
			}
		})
	})

	delete_modal.on('hidden.bs.modal', function(e) {
		input_delete_id.val('')
	})

	function infoRow(el,id){
		const infoBtn = $(el)
		$.ajax({
			url: '<?= base_url('api/'.$module.'/one') ?>',
			data: {id:id},
			type: 'GET',
			beforeSend: function (xhr, settings){
				infoBtn.prop('disabled',true)
				infoBtn.html('<i class="fa fa-spin fa-spinner"></i>')
			},
			success: function(response){
				$('#informasi_div').html(response['informasi'])
				infoBtn.prop('disabled',false)
				infoBtn.text('Informasi')
				$('#info_modal').modal('show')
			},
			error: function(error){
				infoBtn.prop('disabled',false)
				infoBtn.text('Informasi')
			}
		})
	}

	function fotoRow(el,id){
		const fotoBtn = $(el)
		const isiBtn = fotoBtn.html()
		$.ajax({
			url: '<?= base_url('api/'.$module.'/one') ?>',
			data: {id:id},
			type: 'GET',
			beforeSend: function (xhr, settings){
				fotoBtn.prop('disabled',true)
				fotoBtn.html('<i class="fa fa-spin fa-spinner"></i>')
			},
			success: function(response){
				dropzone._callbacks.removedfile[2] = function(file){}
				dropzone.files.forEach((file)=>{
					dropzone.removeFile(file)
				})
				$('#foto_id').val(response[id_column])
				if (response['foto']) {
					for (index_photo in response['foto']) {
						const photo = response['foto'][index_photo]
						const mockFile = { name: photo['filename'], size: photo['size'], id: photo['id_galeri'] }
						dropzone.emit('addedfile', mockFile)
						dropzone.emit('thumbnail', mockFile, photo['file'])
						dropzone.emit('complete', mockFile)
						dropzone.files.push(mockFile)
						$('.dz-progress').remove()
						$('.dz-size').hide()

						dropzone._callbacks.removedfile[2] = function(file) {
							if (file['id']) {
								if (confirm('Anda yakin untuk menghapus gambar ini?')) {
									mApp.block(dropzone.element)
									$.ajax({
										url: '<?= base_url('api/'.$module.'/delete_media') ?>',
										data: {id: file['id']},
										type: 'POST',
										beforeSend: function (xhr, settings){
											mApp.block(dropzone.element)
										},
										success: function(response){
											toastr.success('Gambar Berhasil Dihapus')
											table.ajax.reload(null, false)
											mApp.unblock(dropzone.element)
										},
										error: function(error){
											toastr.error(error.responseText)
											mApp.unblock(dropzone.element)

											dropzone.emit('addedfile', mockFile)
											dropzone.emit('thumbnail', mockFile, photo['file'])
											dropzone.emit('complete', mockFile)
											dropzone.files.push(mockFile)
										}
									})
								} else {
									dropzone.emit('addedfile', mockFile)
									dropzone.emit('thumbnail', mockFile, photo['file'])
									dropzone.emit('complete', mockFile)
									dropzone.files.push(mockFile)
								}
							}
						}
					}
				}

				fotoBtn.prop('disabled',false)
				fotoBtn.html(isiBtn)
				$('#foto_modal').modal('show')
			},
			error: function(error){
				fotoBtn.prop('disabled',false)
				fotoBtn.html(isiBtn)
			}
		})
	}

	$('#foto_form').submit(function(event) {
		event.preventDefault()
		var formData = new FormData()
		dropzone.files.forEach((item)=>{
			if (item['status']=='added') {
				formData.append('file[]', item)
			}
		})
		const foto_id = $('#foto_id')
		if (foto_id.val()){
			formData.append('id', foto_id.val());
		}
		$.ajax({
			url: '<?= base_url('api/'.$module.'/save_foto') ?>',
			data: formData,
			contentType: false,
			processData: false,
			type: 'POST',
			beforeSend: function (xhr, settings){
				mApp.block($('#foto_form'))
			},
			success: function(response){
				toastr.success("Data Disimpan.")
				table.ajax.reload(null, false)
				$('#foto_modal').modal('hide')
				mApp.unblock($('#foto_form'))
			},
			error: function(error){
				toastr.error(error.responseText)
				mApp.unblock($('#foto_form'))
			}
		})
	})

	$(document).ready( function () {
		const img = new Viewer(document.getElementById('tbody'))

		const columns = [
		{title: 'ID',data: id_column},
		{title: 'Nama',data: 'nama'},
		{title: 'Cover',data: 'cover', sortable: false},
		{title: 'Total Galeri',data: 'total', sortable: false},
		{title: 'Aksi',data: id_column, sortable: false},
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
			'columnDefs': [
			{
				'render': function (data, type, row) {
					return `<div><img class="img img-fluid" src="${row['cover']}" onerror="imgError(this)" /></div>`
				},
				'targets': columns.length-3
			},
			{
				'render': function (data, type, row) {
					return `<div><button class="btn btn-sm btn-info text-white" onclick="fotoRow(this,${row[id_column]})">
					<i class="fa fa-image mr-1"></i>
					${row['total']} media</button></div>`
				},
				'targets': columns.length-2
			},
			{
				'render': function (data, type, row) {
					return `
					<div><button class="btn btn-primary btn-sm" onclick="infoRow(this,${row[id_column]})">Informasi</button>
					<div class="btn-group btn-group-sm" role="group" aria-label="First group">
					<button class="btn btn-warning" onclick="editRow(this,${row[id_column]})"><i class="fa fa-pencil"></i></button>
					<button class="btn btn-danger" onclick="deleteRow(${row[id_column]})" data-toggle="modal" data-target="#delete_modal"><i class="fa fa-trash"></i></button>
					</div></div>`
				},
				'targets': columns.length-1
			},
			]
		})

		$('#refresh_btn').click(()=>{
			table.ajax.reload(null, false)
		})

	})
</script>

