<div style="margin:30px auto;background:#fff;padding:0px;border-radius:5px;width:600px;max-width:100%">
	<h2 style="text-align:left;white-space:unset;text-transform:capitalize">
		Kode Verifikasi Kamu [<?= $otp ?>]
	</h2>

	<p style="color:#333;text-align:left;font-size:16px;line-height:1.58em">
		Berikut adalah kode verifikasi yang dapat digunakan untuk login ke SIBOBA:
	</p>


	<div style="display:block;width:250px;max-width:100%;font-weight:bold;font-size:50px;color:#333;outline:0px;text-decoration:none;border-radius:50px;margin:30px auto;text-align:center;text-transform:capitalize">
		<?= $otp ?>
	</div>

	<div><p style="color:#333;text-align:center;font-size:16px;line-height:1.58em">
		<!-- Kode di atas hanya berlaku untuk 30 menit. Jangan memberitahukan kode tersebut ke siapapun, termasuk pihak SIBOBA. -->
		Jangan memberitahukan kode tersebut ke siapapun, termasuk pihak SIBOBA.
	</p></div>

	<!-- <p style="color:#333;text-align:left;font-size:16px;line-height:1.58em">
		Bila ada pertanyaan, silahkan hubungi kami
	</p> -->
</div>