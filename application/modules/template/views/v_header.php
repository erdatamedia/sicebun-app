<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title><?= $title; ?></title>
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
	<link href="//www.amcharts.com/lib/3/plugins/export/export.css" rel="stylesheet" type="text/css" />
	<link href="<?= base_url('assets/vendors/custom/fullcalendar/fullcalendar.bundle.css');  ?>" rel="stylesheet" type="text/css" />
	<link href="<?= base_url('assets/vendors/base/vendors.bundle.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?= base_url('assets/demo/default/base/style.bundle.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?= base_url('assets/vendors/datatables/datatables.min.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?= base_url('assets/vendors/viewerjs/viewer.css')?>" rel="stylesheet" type="text/css">
	<link rel="shortcut icon" href="<?= base_url('assets/img/favico.png'); ?>" />
	
	<style type="text/css">
		@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

		/* Font family overrides */
		body, h1, h2, h3, h4, h5, h6, .m-portlet__head-title, .m-menu__link-text, .form-control, .btn, th, td, .select2-selection__rendered, .select2-results__option {
			font-family: 'Plus Jakarta Sans', sans-serif !important;
		}

		/* Modern scrollbar */
		::-webkit-scrollbar {
			width: 6px;
			height: 6px;
		}
		::-webkit-scrollbar-track {
			background: #f1f5f9;
		}
		::-webkit-scrollbar-thumb {
			background: #cbd5e1;
			border-radius: 4px;
		}
		::-webkit-scrollbar-thumb:hover {
			background: #10b981;
		}

		/* Background color */
		body {
			background-color: #f8fafc !important;
		}

		/* Header adjustments */
		.m-header {
			background-color: rgba(255, 255, 255, 0.9) !important;
			backdrop-filter: blur(8px);
			border-bottom: 1px solid #e2e8f0 !important;
			box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05) !important;
		}

		.m-brand {
			background-color: transparent !important;
			border-bottom: none !important;
			width: 255px !important;
		}

		/* Portlet (Card) Customization */
		.m-portlet {
			border-radius: 16px !important;
			border: 1px solid #e2e8f0 !important;
			box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05) !important;
			background: #ffffff !important;
			overflow: hidden;
			transition: transform 0.2s ease, box-shadow 0.2s ease;
		}

		.m-portlet:hover {
			transform: translateY(-2px);
			box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.05), 0 4px 6px -4px rgba(16, 185, 129, 0.05) !important;
		}

		.m-portlet__head {
			border-bottom: 1px solid #f1f5f9 !important;
			padding: 0 24px !important;
			background: #ffffff !important;
		}

		.m-portlet__head-title {
			color: #1e293b !important;
			font-weight: 600 !important;
			font-size: 1.15rem !important;
		}

		.m-portlet__body {
			padding: 24px !important;
		}

		/* Aside Left Menu (Sidebar) */
		.m-aside-left {
			background-color: #ffffff !important;
			border-right: 1px solid #e2e8f0 !important;
			box-shadow: none !important;
		}

		.m-aside-menu {
			background-color: #ffffff !important;
			padding: 16px 0 !important;
		}

		.m-aside-menu.m-aside-menu--skin-light .m-menu__nav .m-menu__item > .m-menu__heading,
		.m-aside-menu.m-aside-menu--skin-light .m-menu__nav .m-menu__item > .m-menu__link {
			padding: 12px 24px !important;
			border-radius: 8px !important;
			margin: 4px 12px !important;
			transition: all 0.2s ease !important;
		}

		/* Active / Hover sidebar items */
		.m-aside-menu.m-aside-menu--skin-light .m-menu__nav .m-menu__item.m-menu__item--active > .m-menu__link,
		.m-aside-menu.m-aside-menu--skin-light .m-menu__nav .m-menu__item.m-menu__item--expanded > .m-menu__link {
			background-color: #ecfdf5 !important;
			color: #059669 !important;
		}

		.m-aside-menu.m-aside-menu--skin-light .m-menu__nav .m-menu__item.m-menu__item--active > .m-menu__link .m-menu__link-text,
		.m-aside-menu.m-aside-menu--skin-light .m-menu__nav .m-menu__item.m-menu__item--active > .m-menu__link .m-menu__link-icon {
			color: #059669 !important;
			font-weight: 600 !important;
		}

		.m-aside-menu.m-aside-menu--skin-light .m-menu__nav .m-menu__item:hover > .m-menu__link {
			background-color: #f1f5f9 !important;
			color: #1e293b !important;
		}

		/* Tables */
		.table {
			border-collapse: separate !important;
			border-spacing: 0 !important;
			width: 100% !important;
		}

		.table th {
			background-color: #f8fafc !important;
			color: #475569 !important;
			font-weight: 600 !important;
			border-bottom: 2px solid #e2e8f0 !important;
			padding: 12px 16px !important;
		}

		.table td {
			padding: 14px 16px !important;
			border-bottom: 1px solid #f1f5f9 !important;
			vertical-align: middle !important;
			color: #334155 !important;
		}

		.table-hover tbody tr:hover {
			background-color: #f8fafc !important;
		}

		/* Buttons */
		.btn {
			border-radius: 8px !important;
			font-weight: 500 !important;
			padding: 10px 18px !important;
			box-shadow: none !important;
			transition: all 0.2s ease !important;
		}

		.btn-primary {
			background-color: #10b981 !important;
			border-color: #10b981 !important;
			color: #ffffff !important;
		}

		.btn-primary:hover, .btn-primary:focus, .btn-primary:active {
			background-color: #059669 !important;
			border-color: #059669 !important;
		}

		.btn-success {
			background-color: #059669 !important;
			border-color: #059669 !important;
			color: #ffffff !important;
		}

		.btn-success:hover {
			background-color: #047857 !important;
			border-color: #047857 !important;
		}

		/* Form control styling */
		.form-control, select, textarea {
			border-radius: 8px !important;
			border: 1px solid #cbd5e1 !important;
			padding: 10px 14px !important;
			transition: border-color 0.2s ease, box-shadow 0.2s ease !important;
			color: #1e293b !important;
		}

		.form-control:focus, select:focus, textarea:focus {
			border-color: #10b981 !important;
			box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15) !important;
			outline: none !important;
		}

		/* Dropzone and custom UI items */
		.dropzone {
			border: 2px dashed #cbd5e1 !important;
			border-radius: 12px !important;
			background: #f8fafc !important;
		}
		
		.dropzone:hover {
			border-color: #10b981 !important;
		}

		.dropzone .dz-preview.dz-error .dz-error-message {
			margin-top: 10px;
		}

		.cover {
			width: 36px; 
			height: 36px; 
			object-fit: contain; 
			border: 1px solid #cbd5e1; 
			cursor: zoom-in;
			border-radius: 6px !important;
		}

		.dropzone .dz-preview.dz-error .dz-error-mark {
			filter: grayscale(100%) brightness(40%) sepia(100%) hue-rotate(-50deg) saturate(600%) contrast(0.8) !important;
		}

		.note-editable {
			padding-top: 30px !important;
		}

		img {
			cursor: pointer !important;
		}
	</style>

	<script type="text/javascript">
		function imgError(image) {
			image.onerror = "";
			image.src = "<?= base_url('assets/img/not_found.png') ?>";
			return true;
		}
	</script>
</head>
<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-light m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">
	<div class="m-grid m-grid--hor m-grid--root m-page">
		<header class="m-grid__item m-header"  data-minimize-offset="200" data-minimize-mobile-offset="200" >
			<div class="m-container m-container--fluid m-container--full-height">
				<div class="m-stack m-stack--ver m-stack--desktop">

					<div class="m-stack__item m-brand  m-brand--skin-light">
						<div class="m-stack m-stack--ver m-stack--general">
							<div class="m-stack__item m-stack__item--middle m-brand__logo" style="padding-left: 15px;">
								<a href="<?= base_url('beranda'); ?>" class="m-brand__logo-wrapper" style="display: flex; align-items: center; gap: 10px; text-decoration: none;">
									<img src="<?= base_url('assets/img/logo.png'); ?>" alt="SICEBUN Logo" style="height: 35px; width: auto; object-fit: contain; cursor: pointer !important;" onerror="imgError(this)">
									<span style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 18px; color: #10b981; letter-spacing: 0.5px;">SICEBUN</span>
								</a>
							</div>
							<div class="m-stack__item m-stack__item--middle m-brand__tools">
								<a href="javascript:;" id="m_aside_left_minimize_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-desktop-inline-block">
									<span></span>
								</a>
								<a href="javascript:;" id="m_aside_left_offcanvas_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-tablet-and-mobile-inline-block">
									<span></span>
								</a>
								<a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
									<i class="flaticon-more"></i>
								</a>
							</div>
						</div>
					</div>

					<div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">
						
						<button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-light " id="m_aside_header_menu_mobile_close_btn">
							<i class="la la-close"></i>
						</button>
						<div id="m_header_menu" class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-light m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-light m-aside-header-menu-mobile--submenu-skin-light">
						</div>
						<div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general">
							<div class="m-stack__item m-topbar__nav-wrapper">
								<ul class="m-topbar__nav m-nav m-nav--inline">
									<li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light" data-dropdown-toggle="click">
										<a href="#" class="m-nav__link m-dropdown__toggle">
											<span class="m-nav__link-icon">
												<i class="flaticon-grid-menu"></i>
											</span>
										</a>
										<div class="m-dropdown__wrapper">
											<div class="m-dropdown__inner">
												<div class="m-dropdown__body">
													<div class="m-dropdown__content">
														<ul class="m-nav m-nav--skin-light">
															<li class="m-nav__item">
																<a href="<?= base_url('setting') ?>" class="m-nav__link">
																	<i class="m-nav__link-icon flaticon-cogwheel"></i>
																	<span class="m-nav__link-title">
																		<span class="m-nav__link-wrap">
																			<span class="m-nav__link-text">
																				Pengaturan
																			</span>
																		</span>
																	</span>
																</a>
															</li>
															<li class="m-nav__separator m-nav__separator--fit"></li>
															<li class="m-nav__item">
																<a href="<?= base_url('auth/logout'); ?>" class="btn btn-danger m-btn m-btn--label-brand m-btn--bolder">
																	Logout
																</a>
															</li>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</li>
								</ul>
							</div>
						</div>
						
					</div>
				</div>
			</div>
		</header>
		<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
			
			<button class="m-aside-left-close  m-aside-left-close--skin-light " id="m_aside_left_close_btn">
				<i class="la la-close"></i>
			</button>
			<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-light ">
				
				<div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-light m-aside-menu--submenu-skin-light" data-menu-vertical="true" data-menu-scrollable="false" data-menu-dropdown-timeout="500">
					<?php $this->load->view('template/v_menu', ['module'=>$module]); ?>
				</div>
				
			</div>
