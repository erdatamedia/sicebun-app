<script type='text/javascript'>

	function createChart(id, data){
		return AmCharts.makeChart(id, {
			type: 'serial',
			theme: 'none',
			dataProvider: data,
			valueAxes: [ {
				gridColor: '#FFFFFF',
				gridAlpha: 0.2,
				dashLength: 0
			} ],
			showHandOnHover: true,
			gridAboveGraphs: true,
			startDuration: 1,
			graphs: [ {
				balloonText: '[[category]]: <b>[[value]]</b>',
				fillAlphas: 0.8,
				lineAlpha: 0.2,
				type: 'column',
				valueField: 'total'
			} ],
			chartCursor: {
				categoryBalloonEnabled: false,
				cursorAlpha: 0,
				zoomable: false
			},
			chartScrollbar: {
				graph: 'g1',
				oppositeAxis: false,
				offset: 30,
				scrollbarHeight: 30,
				backgroundAlpha: 0,
				selectedBackgroundAlpha: 0.1,
				selectedBackgroundColor: '#888888',
				graphFillAlpha: 0,
				graphLineAlpha: 0.5,
				selectedGraphFillAlpha: 0,
				selectedGraphLineAlpha: 1,
				autoGridCount: true,
				color: '#AAAAAA'
			},
			categoryField: 'name',
			categoryAxis: {
				gridPosition: 'start',
				gridAlpha: 0,
				tickPosition: 'start',
				tickLength: 20
			},
			export: {
				enabled: true
			}
		})
	}

	$(document).ready( function () {
		const data1 = JSON.parse('<?= $sapi_per_provinsi ?>')
		const data2 = JSON.parse('<?= $sapi_per_kota ?>')
		const data3 = JSON.parse('<?= $sapi_per_kecamatan ?>')
		const data4 = JSON.parse('<?= $sapi_per_kelurahan ?>')
		const chart1 = createChart('chart1', data1)
		const chart2 = createChart('chart2', data2)
		const chart3 = createChart('chart3', data3)
		const chart4 = createChart('chart4', data4)

		const charts = [chart1, chart2, chart3, chart4]

		charts.forEach((item, index)=>{
			item.addListener('dataUpdated', (e)=> {
				$('.amcharts-chart-div').find('a').remove()
				if (index!=3) {
					$('.amcharts-graph-column').css('cursor', 'pointer')
				}
			})

			if  (index!=3) {
				item.addListener('clickGraphItem', (e)=> {
					const wilayah = (index==0) ? 'provinsi' : 
					(index==1) ? 'kota' : 'kecamatan'

					window.location = '<?= base_url('beranda') ?>' + '?' + wilayah + '=' + e.item.category
				})
			}
		})

		

	})
</script>