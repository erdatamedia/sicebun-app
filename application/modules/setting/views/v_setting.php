<div class="m-grid__item m-grid__item--fluid m-wrapper">
	<div class="m-subheader ">
		<div class="d-flex align-items-center">
			<div class="mr-auto">
				<h3 class="m-subheader__title ">
					<?= $title; ?>
				</h3>
			</div>
		</div>
	</div>
	<div class="m-content">
		<div class="row">
			<div class="col-md-6">
				<div class="m-portlet" id="m_portlet">
					<div class="m-portlet__head">
						<div class="m-portlet__head-caption">
							<div class="m-portlet__head-title">
								<h5 class="m-portlet__head-text">
									Ganti Password
								</h5>
							</div>
						</div>
					</div>
					<div class="m-portlet__body">
						<form id="change_form">
							<div class="modal-body">
								<div class="row">
									<!-- <div class="form-group col-12" id="error_div" style="display: none;">
										<div class="m-alert m-alert--outline alert alert-danger alert-dismissible" role="alert">
											<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
											<span></span>
										</div>
									</div> -->
									<div class="form-group col-12">
										<label>Password lama</label>
										<input type="password" class="form-control" name="old_password" id="old_password" required />
									</div>
									<div class="form-group col-12">
										<label>Password Baru</label>
										<input type="password" class="form-control" name="new_password" id="new_password" required />
									</div>
									<div class="form-group col-12">
										<label>Konfirmasi Password</label>
										<input type="password" class="form-control" name="confirm_password" id="confirm_password" required />
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
		</div>
	</div>
</div>