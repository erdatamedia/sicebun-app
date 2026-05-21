<div class="m-grid__item m-grid__item--fluid m-wrapper">
	<div class="m-content">
		<div class="row">

			<div class="col-sm-12">
				<div class="m-portlet" id="m_portlet">
					<div class="m-portlet__head">
						<div class="m-portlet__head-caption">
							<div class="m-portlet__head-title">
								<h5 class="m-portlet__head-text">
									Jumlah Sapi per Provinsi
								</h5>
							</div>
						</div>
					</div>
					<div class="m-portlet__body">
						<div id="chart1" style="height: 300px;"></div>
					</div>
				</div>
			</div>

			<div class="col-sm-12">
				<div class="m-portlet" id="m_portlet">
					<div class="m-portlet__head">
						<div class="m-portlet__head-caption">
							<div class="m-portlet__head-title">
								<h5 class="m-portlet__head-text">
									Jumlah Sapi per Kota / Kabupaten
								</h5>
							</div>
						</div>
					</div>
					<div class="m-portlet__body">
						<div id="chart2" style="height: 300px;"></div>
					</div>
				</div>
			</div>

			<div class="col-sm-12">
				<div class="m-portlet" id="m_portlet">
					<div class="m-portlet__head">
						<div class="m-portlet__head-caption">
							<div class="m-portlet__head-title">
								<h5 class="m-portlet__head-text">
									Jumlah Sapi per Kecamatan
								</h5>
							</div>
						</div>
					</div>
					<div class="m-portlet__body">
						<div id="chart3" style="height: 300px;"></div>
					</div>
				</div>
			</div>

			<div class="col-sm-12">
				<div class="m-portlet" id="m_portlet">
					<div class="m-portlet__head">
						<div class="m-portlet__head-caption">
							<div class="m-portlet__head-title">
								<h5 class="m-portlet__head-text">
									Jumlah Sapi per Kelurahan / Desa
								</h5>
							</div>
						</div>
					</div>
					<div class="m-portlet__body">
						<div id="chart4" style="height: 300px;"></div>
					</div>
				</div>
			</div>

			<div class="col-sm-6">
				<div class="m-portlet" id="m_portlet">
					<div class="m-portlet__head">
						<div class="m-portlet__head-caption">
							<div class="m-portlet__head-title">
								<h5 class="m-portlet__head-text">
									Info Jumlah Sapi
								</h5>
							</div>
						</div>
					</div>
					<div class="m-portlet__body">
						<table class="w-100 table table-bordered">
							<tbody>
								<tr>
									<th width="20%" class="bg-success text-white">Semua Sapi</th>
									<td><?= $total ?> ekor</td>
								</tr>
								<?php if (!empty($total_per_inseminator)) { ?>
									<tr>
										<th colspan="2" class="text-center bg-info text-white">Total Per Inseminator</th>
									</tr>
								<?php } ?>
								<?php foreach ($total_per_inseminator as $key => $value) { ?>
									<tr>
										<th><?= $value->name ?></th>
										<td><?= $value->total.' ekor' ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="col-sm-6">
				<div class="m-portlet" id="m_portlet">
					<div class="m-portlet__head">
						<div class="m-portlet__head-caption">
							<div class="m-portlet__head-title">
								<h5 class="m-portlet__head-text">
									Jumlah Sapi Per Status
								</h5>
							</div>
						</div>
					</div>
					<div class="m-portlet__body">
						<table class="w-100 table table-bordered">
							<tbody>
								<?php foreach ($total_per_status as $key => $value) { ?>
									<tr>
										<td class="bg-success text-white" width="20%"><?= $value->status ?></td>
										<td><?= $value->total.' ekor' ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>