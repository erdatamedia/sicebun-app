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
						<div class="col-sm-6">
							<div class="form-group" style="height: 100%; padding-bottom: 40px;">
								<label>Formula</label>
								<textarea class="form-control" name="formula" id="formula" required style="height: 100%"></textarea>
							</div>
						</div>
						<div class="col-sm-6">
							<h5>Ciri - ciri</h5>
							<div class="form-group">
								<label>Bangsa</label>
								<br />
								<select class="form-control m-select2" name="race" id="race" style="width: 100%"></select>
							</div>
							<div class="form-group">
								<label>Fisiologis</label>
								<select class="form-control" name="fisiologis" id="fisiologis">
									<option selected value="">Pilih / kosongkan</option>
									<option value="pedet">Pedet</option>
									<option value="muda">Muda</option>
									<option value="dewasa">Dewasa</option>
								</select>
							</div>
							<div class="form-group">
								<label>Jenis Kelamin</label>
								<select class="form-control" name="gender" id="gender">
									<option selected value="">Pilih / kosongkan</option>
									<option value="1">Jantan</option>
									<option value="0">Betina</option>
								</select>
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

<div class="modal fade" id="formula_modal" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deleteLabel">
					Tes Formula
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						&times;
					</span>
				</button>
			</div>
			<form id="formula_form">
				<div class="modal-body">
					<div class="row" id="formula_div"></div>
				</div>
				<div class="modal-footer">
					<button type="reset" class="btn btn-secondary" data-dismiss="modal">
						Tutup
					</button>
					<button class="btn btn-success">
						Hitung
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">

	const id_column = 'id_formula'
	let table = null
	const form_modal = $('#form_modal')
	const delete_modal = $('#delete_modal')
	const formula_modal = $('#formula_modal')
	const input_delete_id = $('#delete_id')
	const input_id = $('#id')
	const input_formula = $('#formula')
	const input_race = $('#race')
	const input_gender = $('#gender')
	const input_fisiologis = $('#fisiologis')

	const formula_div = $('#formula_div')

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
				input_formula.val(response['formula'])

				if (response['id_race']){
					const selected_race = new Option(response['race_name'], response['id_race'], true, true)
					input_race.append(selected_race).trigger('change')
				}

				input_gender.val(response['gender'])
				input_fisiologis.val(response['fisiologis'])

				mApp.unblock(form_modal)
			},
			error: function(error){
				mApp.unblock(form_modal)
			}
		})
	}

	function testFormula(id){
		$.ajax({
			url: '<?= base_url('api/'.$module.'/one') ?>',
			data: {id:id},
			type: 'GET',
			beforeSend: function (xhr, settings){
				mApp.block(formula_modal)
			},
			success: function(response){
				formula_div.empty()

				const div = document.createElement('div')
				div.className = 'col-md-6 col-sm-12 my-3'
				const code = document.createElement('code')
				code.id = 'formula_code'
				code.style.display = 'block'
				code.style.whiteSpace = 'pre-wrap'
				code.innerText = response['formula']
				div.appendChild(code)
				formula_div.append(div)

				const input_div = document.createElement('div')
				input_div.className = 'col-md-6 col-sm-12'
				response['parameter'].forEach((item, index)=>{
					const input_group_div = document.createElement('div')
					input_div.className = 'form-group'

					const label = document.createElement('label')
					label.for = item
					label.innerText = item
					const input = document.createElement('input')
					input.name = item
					input.required = true
					input.type = 'number'
					input.className = 'form-control'

					input_group_div.appendChild(label)
					input_group_div.appendChild(input)
					input_div.appendChild(input_group_div)
				})
				formula_div.append(input_div)

				mApp.unblock(formula_modal)
			},
			error: function(error){
				mApp.unblock(formula_modal)
			}
		})
	}

	form_modal.on('hidden.bs.modal', function(e) {
		input_id.val('')
		input_formula.val('')
		input_race.val('').trigger('change')
		input_gender.val('')
		input_fisiologis.val('')
	})

	$('#save_form').submit(function(event) {
		event.preventDefault()
		$.ajax({
			url: '<?= base_url('api/'.$module.'/save') ?>',
			data: {
				id: input_id.val(),
				formula: input_formula.val(),
				race: input_race.val(),
				gender: input_gender.val(),
				fisiologis: input_fisiologis.val(),
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
		})
	})

	delete_modal.on('hidden.bs.modal', function(e) {
		input_delete_id.val('')
	})

	$('#formula_form').submit(function(event) {
		event.preventDefault()
		let formulaReqBody = {
			formula: $('#formula_code').text(),
		}
		const inputs = $('input[name^="$"]')
		if (inputs) {
			inputs.each((index, item)=>{
				formulaReqBody[item.name] = item.value
			})
		}

		$.ajax({
			url: '<?= base_url('api/'.$module.'/calculate') ?>',
			data: formulaReqBody,
			type: 'POST',
			beforeSend: function (xhr, settings){
				mApp.block(formula_modal)
			},
			success: function(response){

				$('code').not(':first').remove();

				const code = document.createElement('code')
				code.id = 'formula_code'
				code.style.display = 'block'
				code.style.whiteSpace = 'pre-wrap'
				code.innerText = response['formula']

				const code2 = document.createElement('code')
				code2.id = 'formula_code'
				code2.style.display = 'block'
				code2.style.whiteSpace = 'pre-wrap'
				code2.innerText = response['result']

				$('#formula_code').after(code2)
				$('#formula_code').after(code)

				mApp.unblock(formula_modal)
			},
			error: function(error){
				toastr.error(error.responseText)
				mApp.unblock(formula_modal)
			}
		})
	})

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

	$(document).ready( function () {
		table = $('#table').DataTable({
			'responsive': true,
			'processing': true,
			'serverSide': true,
			'ajax': '<?= base_url('api/'.$module.'/all') ?>',
			'columns': [
			{title: 'ID',data: id_column},
			{title: 'Formula',data: 'formula'},
			{title: 'Ciri-ciri',data: id_column},
			{title: 'Aksi',data: id_column},
			],
			'columnDefs': [
			{
				'render': function (data, type, row) {
					const race = (row['race_name']) ? 'Bangsa : ' + row['race_name']+'<br/>' : ''
					const gender = (row['gender']) ? ((row['gender']>0) ? 'Jenis Kelamin : Jantan<br/>' : 'Jenis Kelamin : Betina<br/>') : ''
					const fisiologis = row['fisiologis'] ? 'Fisiologis : '+row['fisiologis']+'<br/>' : ''
					const result = (race + gender + fisiologis)=='' ? '-' : race + gender + fisiologis
					return result
				},
				'targets': 2
			},
			{
				'render': function (data, type, row) {
					return `
					<button class="btn btn-info btn-sm" onclick="testFormula(${row[id_column]})" data-toggle="modal" data-target="#formula_modal">Tes Formula</button>&nbsp;
					<div class="btn-group btn-group-sm" role="group" aria-label="First group">
					<button class="btn btn-warning" onclick="editRow(${row[id_column]})" data-toggle="modal" data-target="#form_modal"><i class="fa fa-pencil"></i></button>
					<button class="btn btn-danger" onclick="deleteRow(${row[id_column]})" data-toggle="modal" data-target="#delete_modal"><i class="fa fa-trash"></i></button>
					</div>`;
				},
				'targets': 3
			},
			]
		});

		$('#refresh_btn').click(()=>{
			table.ajax.reload(null, false)
		})

	})
</script>

