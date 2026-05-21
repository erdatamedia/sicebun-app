<ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
	<li class="m-menu__section">
		<h4 class="m-menu__section-text">
			Main
		</h4>
		<i class="m-menu__section-icon flaticon-more-v3"></i>
	</li>
	<li class="m-menu__item  m-menu__item--submenu <?php if ($module=='beranda') {echo('m-menu__item--active');} ?>" aria-haspopup="true" >
		<a  href="<?=(base_url('beranda')); ?>" class="m-menu__link ">
			<i class="m-menu__link-icon fa fa-home"></i>
			<span class="m-menu__link-title">
				<span class="m-menu__link-wrap">
					<span class="m-menu__link-text">
						Beranda
					</span>
				</span>
			</span>
		</a>
	</li>
	<li class="m-menu__item  m-menu__item--submenu <?php if ($module=='bangsa') {echo('m-menu__item--active');} ?>" aria-haspopup="true" >
		<a  href="<?=(base_url('bangsa')); ?>" class="m-menu__link ">
			<i class="m-menu__link-icon fa fa-list"></i>
			<span class="m-menu__link-title">
				<span class="m-menu__link-wrap">
					<span class="m-menu__link-text">
						Bangsa Sapi
					</span>
				</span>
			</span>
		</a>
	</li>
	<li class="m-menu__item  m-menu__item--submenu <?php if (in_array($module, ['sapi','import'])) {echo('m-menu__item--open m-menu__item--expanded');} ?>" aria-haspopup="true"  data-menu-submenu-toggle="hover">
		<a  href="#" class="m-menu__link m-menu__toggle">
			<i class="m-menu__link-icon fa fa-list"></i>
			<span class="m-menu__link-text">
				Sapi
			</span>
			<i class="m-menu__ver-arrow la la-angle-right"></i>
		</a>
		<div class="m-menu__submenu ">
			<span class="m-menu__arrow"></span>
			<ul class="m-menu__subnav">
				<li class="m-menu__item <?php if ($module=='sapi') {echo('m-menu__item--active');} ?>" aria-haspopup="true" >
					<a  href="<?=(base_url('sapi')); ?>" class="m-menu__link ">
						<i class="m-menu__link-bullet m-menu__link-bullet--dot">
							<span></span>
						</i>
						<span class="m-menu__link-text">
							Daftar Sapi
						</span>
					</a>
				</li>
				<li class="m-menu__item <?php if ($module=='import') {echo('m-menu__item--active');} ?>" aria-haspopup="true" >
					<a  href="<?=(base_url('sapi/import')); ?>" class="m-menu__link ">
						<i class="m-menu__link-bullet m-menu__link-bullet--dot">
							<span></span>
						</i>
						<span class="m-menu__link-text">
							Import Data Sapi
						</span>
					</a>
				</li>
			</ul>
		</div>
	</li>
	<li class="m-menu__item  m-menu__item--submenu <?php if ($module=='konsultan') {echo('m-menu__item--active');} ?>" aria-haspopup="true" >
		<a  href="<?=(base_url('konsultan')); ?>" class="m-menu__link ">
			<i class="m-menu__link-icon fa fa-stethoscope"></i>
			<span class="m-menu__link-title">
				<span class="m-menu__link-wrap">
					<span class="m-menu__link-text">
						Konsultan
					</span>
				</span>
			</span>
		</a>
	</li>
	<li class="m-menu__item  m-menu__item--submenu <?php if ($module=='direktori') {echo('m-menu__item--active');} ?>" aria-haspopup="true" >
		<a  href="<?=(base_url('direktori')); ?>" class="m-menu__link ">
			<i class="m-menu__link-icon fa fa-folder"></i>
			<span class="m-menu__link-title">
				<span class="m-menu__link-wrap">
					<span class="m-menu__link-text">
						Direktori
					</span>
				</span>
			</span>
		</a>
	</li>
	<li class="m-menu__item  m-menu__item--submenu <?php if ($module=='users') {echo('m-menu__item--active');} ?>" aria-haspopup="true" >
		<a  href="<?=(base_url('users')); ?>" class="m-menu__link ">
			<i class="m-menu__link-icon fa fa-users"></i>
			<span class="m-menu__link-title">
				<span class="m-menu__link-wrap">
					<span class="m-menu__link-text">
						Pengguna
					</span>
				</span>
			</span>
		</a>
	</li>
	<li class="m-menu__item  m-menu__item--submenu <?php if ($module=='laporan') {echo('m-menu__item--active');} ?>" aria-haspopup="true" >
		<a  href="<?=(base_url('laporan')); ?>" class="m-menu__link ">
			<i class="m-menu__link-icon fa fa-print"></i>
			<span class="m-menu__link-title">
				<span class="m-menu__link-wrap">
					<span class="m-menu__link-text">
						Laporan
					</span>
				</span>
			</span>
		</a>
	</li>
</ul>