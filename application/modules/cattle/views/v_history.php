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
						<div class="m-portlet__head-tools">
							<ul class="m-portlet__nav">
								<li class="m-portlet__nav-item">
									<a href="<?= base_url('cattle') ?>" class="btn btn-info btn-sm">
										Kembali ke daftar sapi
									</a>
								</li>
								<li class="m-portlet__nav-item">
									<button id="refresh_btn" class="btn btn-success btn-sm">
										Refresh
									</button>
								</li>
								<li class="m-portlet__nav-item">
									<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#form_modal">
										Add
									</button>
								</li>
							</ul>
						</div>
					</div>
					<div class="m-portlet__body">
						<div id="chart" style="height: 300px;"></div>
						<br/>
						<table id="table" width="100%" class="table table-hover"></table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>