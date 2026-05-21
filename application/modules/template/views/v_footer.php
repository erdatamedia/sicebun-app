</div>

<footer class="m-grid__item		m-footer ">
	<div class="m-container m-container--fluid m-container--full-height m-page__container">
		<div class="m-stack m-stack--flex-tablet-and-mobile m-stack--ver m-stack--desktop">
			<div class="m-stack__item m-stack__item--left m-stack__item--middle m-stack__item--last">
				<span class="m-footer__copyright">
					2020 &copy;
				</span>
			</div>
		</div>
	</div>
</footer>

</div>

<div class="m-scroll-top m-scroll-top--skin-top" data-toggle="m-scroll-top" data-scroll-offset="500" data-scroll-speed="300">
	<i class="la la-arrow-up"></i>
</div>

<script src="<?= base_url('assets/vendors/base/vendors.bundle.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/demo/default/base/scripts.bundle.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/vendors/custom/fullcalendar/fullcalendar.bundle.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/vendors/datatables/datatables.min.js'); ?>" type="text/javascript"></script>
<script src='<?= base_url('assets/vendors/viewerjs/viewer.js')?>'></script>
<script src="//www.amcharts.com/lib/3/amcharts.js" type="text/javascript"></script>
<script src="//www.amcharts.com/lib/3/serial.js" type="text/javascript"></script>
<script src="//www.amcharts.com/lib/3/radar.js" type="text/javascript"></script>
<script src="//www.amcharts.com/lib/3/pie.js" type="text/javascript"></script>
<script src="//www.amcharts.com/lib/3/plugins/tools/polarScatter/polarScatter.min.js" type="text/javascript"></script>
<script src="//www.amcharts.com/lib/3/plugins/animate/animate.min.js" type="text/javascript"></script>
<script src="//www.amcharts.com/lib/3/plugins/export/export.min.js" type="text/javascript"></script>
<script src="//www.amcharts.com/lib/3/themes/light.js" type="text/javascript"></script>
<script type="text/javascript">

	function formatRepo(data) {
		if (data.loading) return data.text
			return data.name
	}

	function formatRepoSelection(data) {
		return data.name || data.text
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
					}
				},
				processResults: function(data, params) {
					params.page = params.page || 1;

					return {
						results: data.items,
						pagination: {
							more: (params.page * 30) < data.total_count
						}
					}
				},
				cache: false
			},
			escapeMarkup: function(markup) {
				return markup
			}, 
			templateResult: formatRepo,
			templateSelection: formatRepoSelection
		})
	}

	function uploadSummernote(id, file, dir) {
		data = new FormData()
		data.append('file', file)
		data.append('dir', dir)

		$.ajax({
			data: data,
			type: 'POST',
			url: '<?= base_url('api/upload_summernote') ?>',
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function (xhr, settings){
				mApp.block($(id))
			},
			success: function(url) {
				if (url) {
					toastr.success('Uploaded')
					$(id).summernote('insertImage', url)
				} else {
					toastr.error('Upload failed')
				}
				mApp.unblock($(id))
			},
			fail: function(){
				toastr.error('Upload failed')
				mApp.unblock($(id))
			}
		});
	}

	jQuery(document).ready(() => {

		readURL= (input, previewImg) => {
			if (input.files && input.files[0]) {
				var reader = new FileReader();

				reader.onload = function (e) {
					$(previewImg).attr('src', e.target.result);
				}

				reader.readAsDataURL(input.files[0]);
			}
		}


	});
</script>
