<script type="text/javascript">
	const change_form = $('#change_form')
	const input_old_password = $('#old_password')
	const input_new_password = $('#new_password')
	const input_confirm_password = $('#confirm_password')
	// const error_div = $('#error_div')

	$(document).ready( function () {
		change_form.submit(function(event) {
			event.preventDefault();

			if (input_new_password.val()!=input_confirm_password.val()) {
				toastr.error('Konfirmasi password tidak sama')
			} else
			$.ajax({
				url: '<?= base_url('api/'.$module.'/changepass') ?>',
				data: {
					old_password: input_old_password.val(),
					new_password: input_new_password.val(),
				},
				type: 'POST',
				beforeSend: function (xhr, settings){
					mApp.block(change_form)
				},
				success: function(response) {
					if (response['status']) {
						toastr.success(response['message'])
						input_old_password.val('')
						input_new_password.val('')
						input_confirm_password.val('')
					} else {
						toastr.error(response['message'])
					}
					mApp.unblock(change_form)
				},
				error: function(error, a,b){
					toastr.error(error.status + ' ' + error.statusText)
					// error_div.show()	
					// error_div.find("span").text(error.status + ' ' + error.statusText)
					mApp.unblock(change_form)
				}
			})
		})


	})
</script>