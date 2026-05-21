<div class="m-grid__item m-grid__item--fluid m-wrapper">
	<div class="m-content">
		<div class="row">
			<div class="col-xl-12">
				<div class="m-portlet " id="m_portlet">
					<div class="m-portlet__head">
						<div class="m-portlet__head-caption">
							<div class="m-portlet__head-title">
								<h5 class="m-portlet__head-text">
									<?= $title; ?>
								</h5>
							</div>
						</div>
					</div>
					<div class="m-portlet__body">
						<?php if (empty($data)) { ?>
							<a target="_blank" href="<?= base_url('assets/format import sapi sicebun.xlsx') ?>" class="text-link">Download Template</a>
							<form method="post" action="<?= base_url('sapi/import') ?>" enctype="multipart/form-data">
								<br />
								<div class="form-group">
									<label>Pilih Inseminator</label>
									<br />
									<select class="form-control m-select2" name="id_inseminator" id="id_inseminator" required style="width: 100%"></select>
								</div>
								<div class="form-group">
									<label>Unggah file</label>
									<input type="file" name="file" class="form-control" required />
								</div>
								<div class="form-group">
									<input type="submit" class="btn btn-success" value="Submit" />
								</div>
							</form>
						<?php } ?>
						<?php if (!empty($data)) { ?>
							<form method="post" action="<?= base_url('sapi/save') ?>">
								<a href="<?= base_url('sapi/import') ?>" class="btn btn-warning mb-3">Kembali</a>
								<input type="hidden" name="id_inseminator" value="<?= $id_inseminator ?>" />
								<input type="submit" name="" class="btn btn-success mb-3" value="Import" />
								<h5>Preview</h5>
								<table id="table" width="100%" class="table table-hover">
									<thead>
										<tr>
											<th>No Sapi</th>
											<th>Peternak</th>
											<th>Alamat</th>
											<th>Tgl Lahir Induk</th>
										</tr>
									</thead>
									<tbody id="tbody">
										<?php foreach ($data as $key => $value): ?>
											<tr>
												<td><input type="text" name="no_sapi[]" class="form-control-plaintext" value="<?= $value['no_sapi'] ?>"></td>
												<td><input type="text" name="peternak[]" class="form-control-plaintext" value="<?= $value['peternak'] ?>"></td>
												<td><input type="text" name="alamat[]" class="form-control-plaintext" value="<?= $value['alamat'] ?>"></td>
												<td><input type="text" name="tgl_lahir[]" class="form-control-plaintext" value="<?= $value['tgl_lahir'] ?>"></td>
											</tr>
										<?php endforeach ?>
									</tbody>
								</table>
							</form>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>