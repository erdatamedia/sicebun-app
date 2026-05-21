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
					<ul class="nav nav-tabs" role="tablist">
						<li class="nav-item">
							<a class="nav-link active show" data-toggle="tab" href="#m_tabs_1_1">
								Info
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle="tab" href="#m_tabs_1_2">
								Konten
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle="tab" href="#m_tabs_1_3">
								Cover
							</a>
						</li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active show" id="m_tabs_1_1" role="tabpanel">
							<div class="row">
								<div class="form-group m-form__group col-md-4 col-sm-12">
									<label for="category">
										Kategori
									</label>
									<br />
									<select class="form-control" id="category" name="category" style="width: 100%"></select>
								</div>
								<div class="form-group m-form__group col-md-8 col-sm-12">
									<label for="title">
										Judul
									</label>
									<input type="text" class="form-control" id="title" name="title"/>
								</div>
								<div class="form-group m-form__group col-md-4 col-sm-12">
									<label for="author">
										Penulis
									</label>
									<br />
									<select class="form-control" id="author" name="author" style="width: 100%"></select>
								</div>
								<div class="form-group m-form__group col-md-8 col-sm-12">
									<label for="tags">
										Tags (pisahkan dengan koma)
									</label>
									<input type="text" class="form-control" id="tags" name="tags"/>
								</div>
								<div class="form-group m-form__group col-sm-12">
									<label for="subtitle">
										Deskripsi singkat
									</label>
									<textarea class="form-control" id="subtitle" name="subtitle"></textarea>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="m_tabs_1_2" role="tabpanel">
							<div class="row">
								<div class="form-group m-form__group col-12">
									<textarea id="content"></textarea>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="m_tabs_1_3" role="tabpanel">
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
	Dropzone.autoDiscover = false

	toastr.options = {
		'preventDuplicates': true,
	}

	const id_column = 'id_news'
	let table = null
	const form_modal = $('#form_modal')
	const delete_modal = $('#delete_modal')
	const input_delete_id = $('#delete_id')
	const input_id = $('#id')

	const input_category = $('#category')
	const input_author = $('#author')
	const input_title = $('#title')
	const input_tags = $('#tags')
	const input_subtitle = $('#subtitle')
	const input_content = $('#content')

	const dropzone = new Dropzone('div#dropzone', {
		autoProcessQueue: false,
		autoQueue: false,
		acceptedFiles: 'image/*',
		maxFiles: 1,
		maxFilesize: 2,
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

	input_content.summernote({
		callbacks: {
			onImageUpload: function(files) {
				uploadSummernote('#content', files[0], '<?= $module ?>')
			},
		}
	})

	function editRow(id){
		$.ajax({
			url: '<?= base_url('api/'.$module.'/one') ?>',
			data: {id:id},
			type: 'GET',
			beforeSend: function (xhr, settings){
				mApp.block(form_modal)
			},
			success: function(response){
				input_id.val(response['id_news'])
				input_category.val(response['id_category'])
				input_author.val(response['id_user'])
				input_title.val(response['title'])
				input_tags.val(response['tags'])
				input_subtitle.val(response['subtitle'])
				input_content.summernote('code',response['content'])
				autosize(input_subtitle)

				const selected_category = new Option(response['category'], response['id_category'], true, true)
				const selected_author = new Option(response['author'], response['id_user'], true, true)
				input_category.append(selected_category).trigger('change')
				input_author.append(selected_author).trigger('change')
				mApp.unblock(form_modal)

				if (response['cover']) {
					const mockFile = { name: response['cover'], size: response['size'] }
					dropzone.emit('addedfile', mockFile)
					dropzone.emit('thumbnail', mockFile, response['url'])
					dropzone.emit('complete', mockFile)
					dropzone.files.push(mockFile)
					$('.dz-progress').remove()

					dropzone._callbacks.removedfile[2] = function(file) {
						if (response['cover']==file['name']){
							if (confirm('Anda yakin untuk menghapus gambar ini?')) {
								mApp.block(dropzone.element)
								$.ajax({
									url: '<?= base_url('api/'.$module.'/delete_image') ?>',
									data: {id: input_id.val()},
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
									}
								})
							} else {
								dropzone.emit('addedfile', mockFile)
								dropzone.emit('thumbnail', mockFile, response['url'])
								dropzone.emit('complete', mockFile)
								dropzone.files.push(mockFile)
							}
						}
					}
				}

			},
			error: function(error){
				mApp.unblock(form_modal)
			}
		})
	}

	form_modal.on('hidden.bs.modal', function(e) {
		input_id.val('')
		input_category.val(null).trigger('change')
		input_author.val(null).trigger('change')
		input_title.val('')
		input_tags.val('')
		input_subtitle.val('')
		input_content.summernote('code','')

		dropzone._callbacks.removedfile[2] = function(file){}
		dropzone.files.forEach((file)=>{
			dropzone.removeFile(file)
		})

	})

	$('#save_form').submit(function(event) {
		event.preventDefault()
		var formData = new FormData()
		formData.append('id_category', input_category.val())
		formData.append('id_user', input_author.val())
		formData.append('title', input_title.val())
		formData.append('tags', input_tags.val())
		formData.append('subtitle', input_subtitle.val())
		formData.append('content', input_content.val())
		formData.append('file', dropzone.files[0])
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

	function formatRecord(record) {
		if (record.loading) return record.text
			return record.name
	}

	function formatRecordSelection(record) {
		return record.name || record.text
	}

	function select(id, url){
		$(id).select2({
			placeholder: 'Pilih',
			allowClear: true,
			ajax: {
				url: url,
				dataType: 'json',
				delay: 250,
				data: function(params) {
					return {
						q: params.term, 
						page: params.page
					}
				},
				processResults: function(data, params) {
					params.page = params.page || 1

					return {
						results: data.items,
						pagination: {
							more: (params.page * 30) < data.total_count
						}
					}
				},
				cache: false
			},
			escapeMarkup: function(markup) {
				return markup
			}, 
			templateResult: formatRecord,
			templateSelection: formatRecordSelection
		})
	}

	select('#category', '<?= base_url('api/'.$module.'/category') ?>')
	select('#author', '<?= base_url('api/'.$module.'/author') ?>')

	function draft(btn,id){
		$.ajax({
			url: '<?= base_url('api/'.$module.'/draft') ?>',
			data: {id: id},
			type: 'POST',
			beforeSend: function (xhr, settings){
				mApp.block(btn)
			},
			success: function(response){
				toastr.success('Berhasil menjadi draft')
				table.ajax.reload(null, false)
			},
			error: function(error){
				toastr.error(error.responseText)
				mApp.unblock(btn)
			}
		})
	}

	function publish(btn,id){
		$.ajax({
			url: '<?= base_url('api/'.$module.'/publish') ?>',
			data: {id: id},
			type: 'POST',
			beforeSend: function (xhr, settings){
				mApp.block(btn)
			},
			success: function(response){
				toastr.success('Berhasil dipublish')
				table.ajax.reload(null, false)
			},
			error: function(error){
				toastr.error(error.responseText)
				mApp.unblock(btn)
			}
		})
	}

	$(document).ready( function () {
		table = $('#table').DataTable({
			'responsive': true,
			'processing': true,
			'serverSide': true,
			'ajax': '<?= base_url('api/'.$module.'/all') ?>',
			'columns': [
			{title: 'ID',data: id_column},
			{title: 'Kategori',data: 'category'},
			{title: 'Judul',data: 'title'},
			{title: 'Cover',data: 'cover'},
			{title: 'Penulis',data: 'author'},
			{title: 'Jumlah Kunjungan',data: 'click_count'},
			{title: 'Status',data: 'state'},
			{title: 'Aksi',data: id_column},
			],
			'columnDefs': [
			{
				'render': function (data, type, row) {
					return `<img class='cover' onerror='imgError(this);' src="${row['cover']}" />`;
				},
				'targets': 3
			},
			{
				'render': function (data, type, row) {
					const draftBtn = (row['state']!='draft') ? `<button class="btn btn-info" onclick="draft(this,${row[id_column]})">Jadikan draft</button>` : ''
					const publishBtn = (row['state']!='published') ? `<button class="btn btn-info" onclick="publish(this,${row[id_column]})">Publish</button>` : ''
					return `
					<div class="btn-group btn-group-sm" role="group" aria-label="First group">
					${draftBtn}
					${publishBtn}
					<button class="btn btn-warning" onclick="editRow(${row[id_column]})" data-toggle="modal" data-target="#form_modal"><i class="fa fa-pencil"></i></button>
					<button class="btn btn-danger" onclick="deleteRow(${row[id_column]})" data-toggle="modal" data-target="#delete_modal"><i class="fa fa-trash"></i></button>
					</div>`
				},
				'targets': 7
			},
			]
		})

		$('#refresh_btn').click(()=>{
			table.ajax.reload(null, false)
		})

	})
</script>

