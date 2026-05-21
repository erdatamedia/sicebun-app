<script type="text/javascript">
	$(document).ready( function () {
		select('#provinsi', '<?= base_url('api/wilayah/provinsi') ?>')
		select('#kota', '<?= base_url('api/wilayah/kota') ?>')
		select('#kecamatan', '<?= base_url('api/wilayah/kecamatan') ?>')
		select('#kelurahan', '<?= base_url('api/wilayah/kelurahan') ?>')
	})
</script>
