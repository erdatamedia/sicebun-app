<div class="m-grid__item m-grid__item--fluid m-wrapper">
	<div class="m-content">
		<div class="row">
			<div class="col-sm-6">
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
					<form class="m-form" action="<?= base_url('laporan/cetak') ?>" method="POST">
						<div class="m-portlet__body">
							<div class="form-group m-form__group row">
								<label class="col-lg-3 col-form-label">
									Provinsi
								</label>
								<div class="col-lg-6">
									<select class="form-control" id="provinsi" name="provinsi" style="width: 100%"></select>
								</div>
							</div>
							<div class="form-group m-form__group row">
								<label class="col-lg-3 col-form-label">
									Kota
								</label>
								<div class="col-lg-6">
									<select class="form-control" id="kota" name="kota" style="width: 100%"></select>
								</div>
							</div>
							<div class="form-group m-form__group row">
								<label class="col-lg-3 col-form-label">
									Kecamatan
								</label>
								<div class="col-lg-6">
									<select class="form-control" id="kecamatan" name="kecamatan" style="width: 100%"></select>
								</div>
							</div>
							<div class="m-form__group form-group row">
								<label class="col-lg-3 col-form-label">
									Kelurahan
								</label>
								<div class="col-lg-6">
									<select class="form-control" id="kelurahan" name="kelurahan" style="width: 100%"></select>
								</div>
							</div>
						</div>
						<div class="m-portlet__foot m-portlet__foot--fit">
							<div class="m-form__actions m-form__actions">
								<div class="row">
									<div class="col-lg-3"></div>
									<div class="col-lg-6">
										<button type="submit" class="btn btn-success">
											Cetak
										</button>
										<button type="reset" class="btn btn-secondary">
											Reset
										</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>