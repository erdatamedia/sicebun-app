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
								<label for="no_sapi">
									No Sapi
								</label>
								<input type="text" class="form-control" id="no_sapi" name="no_sapi" required />
							</div>
							<div class="form-group m-form__group">
								<label for="peternak">
									Peternak
								</label>
								<input type="text" class="form-control" id="peternak" name="peternak" required />
							</div>
							<div class="form-group m-form__group">
								<label for="alamat">
									Alamat
								</label>
								<textarea class="form-control" id="alamat" name="alamat" required></textarea>
							</div>
							<div class="row">
								<div class="col-sm-6 form-group m-form__group">
									<label for="kelurahan">
										Kelurahan
									</label>
									<br/>
									<select class="form-control" id="kelurahan" name="kelurahan" required style="width: 100%">
										<option value="">Pilih</option>
									</select>
								</div>
								<div class="col-sm-6 form-group m-form__group">
									<label for="kecamatan">
										Kecamatan
									</label>
									<br/>
									<select class="form-control" id="kecamatan" name="kecamatan" required style="width: 100%">
										<option value="">Pilih</option>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6 form-group m-form__group">
									<label for="kota">
										Kota
									</label>
									<br/>
									<select class="form-control" id="kota" name="kota" required style="width: 100%">
										<option value="">Pilih</option>
									</select>
								</div>
								<div class="col-sm-6 form-group m-form__group">
									<label for="provinsi">
										Provinsi
									</label>
									<br/>
									<select class="form-control" id="provinsi" name="provinsi" required style="width: 100%">
										<option value="">Pilih</option>
									</select>
								</div>
							</div>
							<div class="form-group m-form__group">
								<label for="bangsa">
									Bangsa
								</label>
								<br />
								<select class="form-control m-select2" name="bangsa" id="bangsa" required style="width: 100%"></select>
							</div>
							<div class="form-group m-form__group">
								<label for="tgl_lahir">
									Tgl Lahir Induk
								</label>
								<input type="datetime-local" class="form-control" id="tgl_lahir" name="tgl_lahir" />
							</div>
							<div class="form-group">
								<label>Foto Pemilik</label>
								<div class="custom-file">
									<input type="file" class="custom-file-input" id="foto_pemilik" name="foto_pemilik" />
									<label class="custom-file-label" for="foto_pemilik">Pilih Foto</label>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group m-form__group">
								<label for="ib_1">
									IB 1
								</label>
								<input type="datetime-local" class="form-control" id="ib_1" name="ib_1" />
							</div>
							<div class="form-group m-form__group">
								<label for="ib_2">
									IB 2
								</label>
								<input type="datetime-local" class="form-control" id="ib_2" name="ib_2"/>
							</div>
							<div class="form-group m-form__group">
								<label for="ib_3">
									IB 3
								</label>
								<input type="datetime-local" class="form-control" id="ib_3" name="ib_3"/>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group m-form__group">
										<label>Kondisi</label>
										<div class="checkbox-inline">
											<label class="checkbox">
												<input type="checkbox" name="is_bunting">
												<span></span>Bunting
											</label>
											&nbsp;
											<label class="checkbox">
												<input type="checkbox" name="is_melahirkan">
												<span></span>Melahirkan
											</label>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group m-form__group">
										<label for="status">
											Status
										</label>
										<select type="date" class="form-control" id="status" name="status" required>
											<option value="">Pilih</option>
											<option value="ib1">IB 1</option>
											<option value="ib2">IB 2</option>
											<option value="ib3">IB 3</option>
											<option value="bunting">Bunting</option>
											<option value="tidak_bunting">Tidak Bunting</option>
											<option value="gangrep">Gangrep</option>
											<option value="kelahiran">Kelahiran</option>
										</select>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label>Foto Sapi</label>
								<div class="custom-file">
									<input type="file" class="custom-file-input" id="foto_sapi" name="foto_sapi" />
									<label class="custom-file-label" for="foto_sapi">Pilih Foto</label>
								</div>
							</div>
							<div class="form-group m-form__group">
								<label for="gangrep">
									Gangguan Reproduksi
								</label>
								<textarea id="gangrep" class="form-control" placeholder="Jika ada"></textarea>
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

	const id_column = 'id_sapi'
	let table = null
	const form_modal = $('#form_modal')
	const delete_modal = $('#delete_modal')
	const input_delete_id = $('#delete_id')
	const input_id = $('#id')

	const input_no_sapi 		= $('#no_sapi')
	const input_peternak 		= $('#peternak')
	const input_alamat 			= $('#alamat')
	const input_provinsi 		= $('#provinsi')
	const input_kota 			= $('#kota')
	const input_kecamatan 		= $('#kecamatan')
	const input_kelurahan 		= $('#kelurahan')
	const input_bangsa 			= $('#bangsa')
	const input_tgl_lahir 		= $('#tgl_lahir')
	const input_ib_1 			= $('#ib_1')
	const input_ib_2 			= $('#ib_2')
	const input_ib_3 			= $('#ib_3')
	const input_is_bunting 		= $('#is_bunting')
	const input_is_melahirkan 	= $('#is_melahirkan')
	const input_status 			= $('#status')
	const input_gangrep 		= $('#gangrep')
	const input_foto_pemilik 	= $('#foto_pemilik')
	const input_foto_sapi 		= $('#foto_sapi')
	autosize(input_gangrep)


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
				
				input_no_sapi.val(response['no_sapi'])
				input_peternak.val(response['peternak'])
				input_alamat.val(response['alamat'])

				if (response['provinsi']) {
					const selected_prov = new Option(response['provinsi'], response['provinsi'], true, true)
					input_provinsi.append(selected_prov).trigger('change')
				}

				if (response['kota']) {
					const selected_kota = new Option(response['kota'], response['kota'], true, true)
					input_kota.append(selected_kota).trigger('change')
				}

				if (response['kecamatan']) {
					const selected_kec = new Option(response['kecamatan'], response['kecamatan'], true, true)
					input_kecamatan.append(selected_kec).trigger('change')
				}

				if (response['kelurahan']) {
					const selected_kec = new Option(response['kelurahan'], response['kelurahan'], true, true)
					input_kelurahan.append(selected_kec).trigger('change')
				}

				if (response['id_bangsa']) {
					const selected_race = new Option(response['nama_bangsa'], response['id_bangsa'], true, true)
					input_bangsa.append(selected_race).trigger('change')
				}
				if (response['tgl_lahir']) {
					input_tgl_lahir.val(response['tgl_lahir'].replace(' ','T'))
				}
				if (response['ib_1']) {
					input_ib_1.val(response['ib_1'].replace(' ','T'))
				}
				if (response['ib_2']) {
					input_ib_2.val(response['ib_2'].replace(' ','T'))
				}
				if (response['ib_3']) {
					input_ib_3.val(response['ib_3'].replace(' ','T'))
				}
				input_is_bunting.val(response['is_bunting'])
				input_is_melahirkan.val(response['is_melahirkan'])
				input_status.val(response['status'])
				input_gangrep.val(response['gangrep'])
				autosize(input_gangrep)

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
		input_no_sapi.val('')
		input_peternak.val('')
		input_alamat.val('')
		input_provinsi.empty()
		input_kota.empty()
		input_kecamatan.empty()
		input_kelurahan.empty()
		input_bangsa.val('')
		input_tgl_lahir.val('')
		input_ib_1.val('')
		input_ib_2.val('')
		input_ib_3.val('')
		input_is_bunting.val('')
		input_is_melahirkan.val('')
		input_status.val('')
		input_gangrep.val('')
		input_foto_pemilik.val('')
		input_foto_sapi.val('')
		$('.custom-file-label').text('Pilih Foto')
	})

	$('#save_form').submit(function(event) {
		event.preventDefault()
		var formData = new FormData()
		formData.append('no_sapi',input_no_sapi.val())
		formData.append('peternak',input_peternak.val())
		formData.append('alamat',input_alamat.val())
		formData.append('provinsi',input_provinsi.val())
		formData.append('kota',input_kota.val())
		formData.append('kecamatan',input_kecamatan.val())
		formData.append('kelurahan',input_kelurahan.val())
		formData.append('id_bangsa',input_bangsa.val())
		formData.append('tgl_lahir',input_tgl_lahir.val())
		formData.append('ib_1',input_ib_1.val())
		formData.append('ib_2',input_ib_2.val())
		formData.append('ib_3',input_ib_3.val())
		formData.append('is_bunting',input_is_bunting.val())
		formData.append('is_melahirkan',input_is_melahirkan.val())
		formData.append('status',input_status.val())
		formData.append('gangrep',input_gangrep.val())
		if (input_foto_pemilik.val()) {
			formData.append('foto_pemilik',input_foto_pemilik[0].files[0])
		}
		if (input_foto_sapi.val()) {
			formData.append('foto_sapi',input_foto_sapi[0].files[0])
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

	function status($status) {
		return $status=='ib1' ? 'Inseminasi Buatan 1' 
		:  $status=='ib2' ? 'Inseminasi Buatan 2' 
		:  $status=='ib3' ? 'Inseminasi Buatan 3' 
		:  $status=='bunting' ? 'Bunting' 
		:  $status=='tidak_bunting' ? 'Tidak Bunting' 
		:  $status=='gangrep' ? 'Gangguan Reproduksi' 
		:  'Kelahiran' 
	}

	select('#bangsa', '<?= base_url('api/'.$module.'/bangsa') ?>')

	select('#provinsi', '<?= base_url('api/wilayah/provinsi') ?>')
	select('#kota', '<?= base_url('api/wilayah/kota') ?>')
	select('#kecamatan', '<?= base_url('api/wilayah/kecamatan') ?>')
	select('#kelurahan', '<?= base_url('api/wilayah/kelurahan') ?>')

	$(document).ready( function () {
		const img = new Viewer(document.getElementById('tbody'))

		const columns = [
		{title: 'ID',data: id_column},
		{title: 'No Sapi',data: 'no_sapi'},
		{
			title: 'Peternak',data: 'peternak',
			render: function (data, type, row) {
				const res = []
				if (row['peternak']) {
					res.push('Nama : ' + row['peternak'])
				}
				if (row['alamat']) {
					res.push('Alamat : ' + row['alamat'])
				}
				if (row['kelurahan']) {
					res.push(row['kelurahan'])
				}
				if (row['kecamatan']) {
					res.push(row['kecamatan'])
				}
				if (row['kota']) {
					res.push(row['kota'])
				}
				if (row['provinsi']) {
					res.push(row['provinsi'])
				}
				return `<div>${res.join('<br>')}</div>`
			},
		},
		{title: 'Bangsa',data: 'bangsa'},
		{title: 'Tgl Lahir',data: 'tgl_lahir'},
		{
			title: 'Status',data: 'status',
			render: function (data, type, row) {
				return `<div>${status(row['status'])}</div>`
			},
		},
		{
			title: 'IB ke 1',data: id_column, sortable: false,
			render: function (data, type, row) {
				const no = '1'
				const straw = 'Kode Straw : ' + (row['straw_' + no] ? row['straw_' + no] : '-')
				const tgl_birahi = 'Tgl Birahi : ' + (row['birahi_' + no] ? row['birahi_' + no] : '-')
				const tgl_ib = 'Tgl IB : ' + (row['ib_' + no] ? row['ib_' + no] : '-')
				const ket = 'ket : ' + (row['ket_' + no] ? row['ket_' + no] : '-')
				return `<div>${straw} <br> ${tgl_birahi} <br> ${tgl_ib} <br> ${ket}</div>`
			},
		},
		{
			title: 'IB ke 2',data: id_column, sortable: false,
			render: function (data, type, row) {
				const no = '2'
				const straw = 'Kode Straw : ' + (row['straw_' + no] ? row['straw_' + no] : '-')
				const tgl_birahi = 'Tgl Birahi : ' + (row['birahi_' + no] ? row['birahi_' + no] : '-')
				const tgl_ib = 'Tgl IB : ' + (row['ib_' + no] ? row['ib_' + no] : '-')
				const ket = 'ket : ' + (row['ket_' + no] ? row['ket_' + no] : '-')
				return `<div>${straw} <br> ${tgl_birahi} <br> ${tgl_ib} <br> ${ket}</div>`
			},
		},
		{
			title: 'IB ke 3',data: id_column, sortable: false,
			render: function (data, type, row) {
				const no = '3'
				const straw = 'Kode Straw : ' + (row['straw_' + no] ? row['straw_' + no] : '-')
				const tgl_birahi = 'Tgl Birahi : ' + (row['birahi_' + no] ? row['birahi_' + no] : '-')
				const tgl_ib = 'Tgl IB : ' + (row['ib_' + no] ? row['ib_' + no] : '-')
				const ket = 'ket : ' + (row['ket_' + no] ? row['ket_' + no] : '-')
				return `<div>${straw} <br> ${tgl_birahi} <br> ${tgl_ib} <br> ${ket}</div>`
			},
		},
		{
			title: 'Foto Pemilik & Sapi',data: id_column, sortable: false,
			render: function (data, type, row) {
				const fotoPemilik = row['foto_pemilik'] ? `<img class="img img-thumbnail" width="50px" height="50px" onerror="imgError(this)" src="${row['foto_pemilik']}" />` : ''
				const fotoSapi =  row['foto_sapi'] ? `<img class="img img-thumbnail" width="50px" height="50px" onerror="imgError(this)" src="${row['foto_sapi']}" />` : ''
				return `<div>${fotoPemilik} ${fotoSapi}</div>`
			},
		},
		{
			title: 'Gangrep',data: id_column, sortable: false,
			render: function (data, type, row) {
				return `<div>${row['gangrep'] ? row['gangrep'] : '-'}</div>`
			},
		},
		{
			title: 'Aksi',data: id_column, sortable: false,
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
			order: [[0, 'desc' ]],
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

