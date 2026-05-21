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
								<label>Eartag</label>
								<input type="text" class="form-control" name="eartag" id="eartag" required />
							</div>
							<div class="form-group">
								<label>Tanggal Lahir</label>
								<input type="datetime-local" class="form-control" name="birthdate" id="birthdate" required />
							</div>
							<div class="form-group">
								<label>Jenis Kelamin</label>
								<select class="form-control" name="gender" id="gender" required >
									<option value="1" selected="1">Jantan</option>
									<option value="0">Betina</option>
								</select>
							</div>
						</div>
						<div class="col-lg-6 col-sm-12 col-12">
							<div class="form-group">
								<label>Bangsa</label>
								<br />
								<select class="form-control m-select2" name="race" id="race" required style="width: 100%"></select>
							</div>
							<div class="form-group">
								<label>Pemilik</label>
								<select class="form-control m-select2" name="user" id="user" required style="width: 100%"></select>
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

	const id_column = 'id_cattle'
	let table = null;
	const form_modal = $('#form_modal')
	const delete_modal = $('#delete_modal')
	const input_delete_id = $('#delete_id')
	const input_id = $('#id')
	const input_eartag = $('#eartag')
	const input_race = $('#race')
	const input_user = $('#user')
	const input_birthdate = $('#birthdate')
	const input_gender = $('#gender')

	function editRow(id){
		$.ajax({
			url: '<?= base_url('api/'.$module.'/one') ?>',
			data: {id:id},
			type: 'GET',
			beforeSend: function (xhr, settings){
				mApp.block(form_modal)
			},
			success: function(response){
				input_id.val(response[id_column])
				input_eartag.val(response['eartag'])

				const selected_race = new Option(response['race_name'], response['id_race'], true, true)
				const selected_user = new Option(response['owner'], response['id_user'], true, true)
				input_race.append(selected_race).trigger('change')
				input_user.append(selected_user).trigger('change')

				input_birthdate.val(new Date(response['birthdate'].replace(' ', 'T')+'Z').toJSON().slice(0,19))

				input_gender.val(response['gender'])

				mApp.unblock(form_modal)
			},
			error: function(error){
				mApp.unblock(form_modal)
			}
		});
	}

	form_modal.on('hidden.bs.modal', function(e) {
		input_id.val('')
		input_eartag.val('')
		input_race.val(null).trigger('change')
		input_user.val(null).trigger('change')
		input_birthdate.val('')
		input_gender.val('1')
	})

	$('#save_form').submit(function(event) {
		event.preventDefault()
		$.ajax({
			url: '<?= base_url('api/'.$module.'/save') ?>',
			data: {
				id: input_id.val(),
				eartag: input_eartag.val(),
				race: input_race.val(),
				user: input_user.val(),
				birthdate: input_birthdate.val(),
				gender: input_gender.val()
			},
			type: 'POST',
			beforeSend: function (xhr, settings){
				mApp.block(form_modal);
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
		});
	});

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
		});
	});

	delete_modal.on('hidden.bs.modal', function(e) {
		input_delete_id.val('')
	});

	function formatRepo(repo) {
		if (repo.loading) return repo.text
			return repo.name
	}

	function formatRepoSelection(repo) {
		return repo.name || repo.text;
	}

	function select(id, url){
		$(id).select2({
			placeholder: "Pilih",
			allowClear: true,
			ajax: {
				url: url,
				dataType: 'json',
				delay: 250,
				data: function(params) {
					return {
						q: params.term, 
						page: params.page
					};
				},
				processResults: function(data, params) {
					params.page = params.page || 1;

					return {
						results: data.items,
						pagination: {
							more: (params.page * 30) < data.total_count
						}
					};
				},
				cache: false
			},
			escapeMarkup: function(markup) {
				return markup;
			}, 
			templateResult: formatRepo,
			templateSelection: formatRepoSelection
		});
	}

	select('#race', '<?= base_url('api/cattle/race') ?>')
	select('#user', '<?= base_url('api/cattle/user') ?>')

	$(document).ready( function () {
		table = $('#table').DataTable({
			'responsive': true,
			'processing': true,
			'serverSide': true,
			'ajax': '<?= base_url('api/'.$module.'/all') ?>',
			'columns': [
			{title: 'ID',data: id_column},
			{title: 'Eartag',data: 'eartag'},
			{title: 'Bangsa',data: 'race_name'},
			{title: 'Pemilik',data: 'owner'},
			{title: 'Tgl Lahir',data: 'birthdate'},
			{title: 'Kelamin',data: 'gender'},
			{title: 'Aksi',data: id_column},
			],
			'columnDefs': [
			{
				'render': function (data, type, row) {
					if (row['age'] >= 1) {
						return row['birthdate'] + '<br/>' + Math.round(row['age']) + ' tahun'
					} else if (0.1 <= row['age'] && row['age'] < 1) {
						return row['birthdate'] + '<br/>' + (Number(row['age']).toFixed(1)*10) + ' bulan'
					} else {
						return row['birthdate'] + '<br/>' + row['age_in_days'] + ' hari'
					}
				},
				'targets': 4
			},
			{
				'render': function (data, type, row) {
					return (row['gender']>0) ? 'Jantan' : 'Betina'
				},
				'targets': 5
			},
			{
				'render': function (data, type, row) {
					return `
					<div class="btn-group btn-group-sm" role="group" aria-label="First group">
					<a class="btn btn-info" href="<?= base_url('cattle/history/') ?>${row[id_column]}">Riwayat Bobot</a>
					<button class="btn btn-warning" onclick="editRow(${row[id_column]})" data-toggle="modal" data-target="#form_modal"><i class="fa fa-pencil"></i></button>
					<button class="btn btn-danger" onclick="deleteRow(${row[id_column]})" data-toggle="modal" data-target="#delete_modal"><i class="fa fa-trash"></i></button>
					</div>`;
				},
				'targets': 6
			},
			]
		});

		$('#refresh_btn').click(()=>{
			table.ajax.reload(null, false)
		})

	})
</script>

