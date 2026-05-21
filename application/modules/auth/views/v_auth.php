<!DOCTYPE html>
<html lang="en" >
<head>
	<meta charset="utf-8" />
	<title>
		Login
	</title>
	<meta name="description" content="Latest updates and statistic charts">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
	<script>
		WebFont.load({
			google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>
	
	<link href="<?php echo base_url('assets/vendors/base/vendors.bundle.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url('assets/demo/default/base/style.bundle.css'); ?>" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" href="<?= base_url('assets/img/favico.png'); ?>" />
	
</head>

<body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  >

	<div class="m-grid m-grid--hor m-grid--root m-page">
		<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-grid--tablet-and-mobile m-grid--hor-tablet-and-mobile m-login m-login--1 m-login--signin" id="m_login" style="align-self: center;">
			<div class="m-grid__item m-grid__item--order-tablet-and-mobile-2 m-login__aside">
				<div class="m-stack m-stack--hor m-stack--desktop">
					<div class="m-stack__item m-stack__item--fluid">
						<div class="m-login__wrapper">
							<div class="m-login__logo">
								<img src="<?= base_url('assets/img/logo.png'); ?>" width="100px">
							</div>


							<?php if ($this->session->flashdata('error_msg')) { ?>
								<div class="m-alert m-alert--outline alert alert-danger alert-dismissible" role="alert">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
									<span><?php echo $this->session->flashdata('error_msg'); ?></span>
								</div>
								<?php unset($_SESSION['error_msg']); ?>
							<?php } ?>

							<div class="m-login__signin">
								<div class="m-login__head">
									<h3 class="m-login__title">
										Login Administrator
									</h3>
								</div>
								<form class="m-login__form m-form" method="POST" action="<?= base_url('auth/login'); ?>">
									<div class="form-group m-form__group">
										<input class="form-control m-input" type="email" placeholder="Email" name="username" autocomplete="off" required />
									</div>
									<div class="form-group m-form__group">
										<input class="form-control m-input m-login__form-input--last" type="password" placeholder="Password" name="password" required />
									</div>
									<div class="row m-login__form-sub">
										<div class="col m--align-left m-login__form-left">
											<label class="m-checkbox  m-checkbox--success">
												<input type="checkbox" name="remember"  onclick="showPassword()">
												Tampilkan password
												<span></span>
											</label>
										</div>
									</div>
									<div class="m-login__form-action">
										<input type="submit" id="m_login_signin_submit" class="btn btn-info m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn m-login__btn--primary" value="Masuk" />
									</div>
								</form>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="<?= base_url('assets/vendors/base/vendors.bundle.js'); ?>" type="text/javascript"></script>
	<script type="text/javascript">
		function showPassword() {
			var x = document.getElementsByName("password")[0];
			if (x.type === "password") {
				x.type = "text";
			} else {
				x.type = "password";
			}
		}
	</script>

</body>

</html>
