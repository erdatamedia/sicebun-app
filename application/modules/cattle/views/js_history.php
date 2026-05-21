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
				<input type="hidden" name="id_cattle" id="id_cattle" value="<?= $id ?>" />
				<div class="modal-body">
					<div class="row">
						<div class="form-group col-6">
							<label>Lingkar Dada</label>
							<input type="number" class="form-control" name="lingkar_dada" id="lingkar_dada" required />
						</div>
						<div class="form-group col-6">
							<label>Panjang Badan</label>
							<input type="number" class="form-control" name="panjang_badan" id="panjang_badan" required />
						</div>
						<div class="form-group col-6">
							<label>Bobot</label>
							<input type="number" class="form-control" name="bobot" id="bobot" required />
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

	const id_column = 'id_weighing'
	let chart = null
	let table = null
	const form_modal = $('#form_modal')
	const delete_modal = $('#delete_modal')
	const input_delete_id = $('#delete_id')
	const input_id = $('#id')
	const input_id_cattle = $('#id_cattle')
	const input_lingkar_dada = $('#lingkar_dada')
	const input_panjang_badan = $('#panjang_badan')
	const input_bobot = $('#bobot')

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
				input_lingkar_dada.val(response['lingkar_dada'])
				input_panjang_badan.val(response['panjang_badan'])
				input_bobot.val(response['bobot'])
				mApp.unblock(form_modal)
			},
			error: function(error){
				mApp.unblock(form_modal)
			}
		});
	}

	form_modal.on('hidden.bs.modal', function(e) {
		input_id.val('')
		input_lingkar_dada.val('')
		input_panjang_badan.val('')
		input_bobot.val('')
	})

	$('#save_form').submit(function(event) {
		event.preventDefault()
		$.ajax({
			url: '<?= base_url('api/'.$module.'/save') ?>',
			data: {
				id: input_id.val(),
				id_cattle: input_id_cattle.val(),
				lingkar_dada: input_lingkar_dada.val(),
				panjang_badan: input_panjang_badan.val(),
				bobot: input_bobot.val(),
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

	function reloadChart(){
		$.ajax({
			url: '<?= base_url('api/'.$module.'/chart') ?>',
			type: 'GET',
			data: {id: '<?= $id ?>'},
			beforeSend: function (xhr, settings){
				mApp.block($('#chart'))
			},
			success: function(response){
				mApp.unblock($('#chart'))
				chart.dataProvider = response
				chart.validateData()
			},
			error: function(error){
				toastr.error(error.responseText)
				mApp.unblock($('#chart'))
			}
		})
	}

	$(document).ready( function () {
		table = $('#table').DataTable({
			'responsive': true,
			'processing': true,
			'serverSide': true,
			'ajax': '<?= base_url('api/'.$module.'/'.$id) ?>',
			'columns': [
			{title: 'ID',data: id_column},
			{title: 'Tgl Input',data: 'created_date'},
			{title: 'Lingkar Dada (cm)',data: 'lingkar_dada'},
			{title: 'Panjang Badan (cm)',data: 'panjang_badan'},
			{title: 'Bobot',data: 'bobot'},
			{title: 'Hasil dari Estimasi',data: 'estimated'},
			{title: 'Foto',data: 'photo'},
			{title: 'Aksi',data: id_column},
			],
			'columnDefs': [
			{
				'render': function (data, type, row) {
					return (row['estimated']>0) ? 'Ya' : 'Tidak'
				},
				'targets': 5
			},
			{
				'render': function (data, type, row) {
					return `
					<div class="btn-group btn-group-sm" role="group" aria-label="First group">
					<button class="btn btn-warning" onclick="editRow(${row[id_column]})" data-toggle="modal" data-target="#form_modal"><i class="fa fa-pencil"></i></button>
					<button class="btn btn-danger" onclick="deleteRow(${row[id_column]})" data-toggle="modal" data-target="#delete_modal"><i class="fa fa-trash"></i></button>
					</div>`;
				},
				'targets': 7
			},
			]
		});

		chart = AmCharts.makeChart('chart', {
			'type': 'serial',
			'theme': 'light',
			'mouseWheelZoomEnabled': true,
			'dataDateFormat': 'YYYY-MM-DD',
			'graphs': [{
				'id': 'g1',
				'balloon': {
					'drop': true,
					'adjustBorderColor': false,
					'color': '#ffffff'
				},
				'bullet': 'round',
				'bulletBorderAlpha': 1,
				'bulletColor': '#FFFFFF',
				'bulletSize': 5,
				'hideBulletsCount': 50,
				'lineThickness': 1,
				'title': 'red line',
				'useLineColorForBulletBorder': true,
				'valueField': 'value',
				'balloonText': '<span>[[value]]</span>'
			}],
			'chartScrollbar': {
				'graph': 'g1',
				'oppositeAxis': false,
				'offset': 30,
				'scrollbarHeight': 30,
				'backgroundAlpha': 0,
				'selectedBackgroundAlpha': 0.1,
				'selectedBackgroundColor': '#888888',
				'graphFillAlpha': 0,
				'graphLineAlpha': 0.5,
				'selectedGraphFillAlpha': 0,
				'selectedGraphLineAlpha': 1,
				'autoGridCount': true,
				'color': '#AAAAAA'
			},
			'chartCursor': {
				'pan': true,
				'valueLineEnabled': true,
				'valueLineBalloonEnabled': true,
				'cursorAlpha': 1,
				'cursorColor': '#258cbb',
				'limitToGraph': 'g1',
				'valueLineAlpha': 0.2,
				'valueZoomable': true
			},
			'valueScrollbar': {
				'oppositeAxis': false,
				'offset': 50,
				'scrollbarHeight': 10
			},
			'categoryField': 'date',
			'categoryAxis': {
				'parseDates': true,
				'dashLength': 1,
				'minorGridEnabled': true
			},
			'export': {
				'enabled': true
			},
			'dataProvider': []
		})

		reloadChart()

		chart.addListener('rendered', zoomChart)

		zoomChart()

		function zoomChart() {
			chart.zoomToIndexes(chart.dataProvider.length - 40, chart.dataProvider.length - 1)
		}

		$('#refresh_btn').click(()=>{
			table.ajax.reload(null, false)
			reloadChart()
		})

	})
</script>

