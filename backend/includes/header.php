	<?php
	session_start();
	if (!isset($_SESSION['pwd'])) {
		header('location:login.php');
	}
	?>
	<!DOCTYPE html>
	<html lang="en">

	<head>

		<meta charset="utf-8">
		<meta name="author" content="Yudha Yogasara">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>.:: Administrator Panel ::.</title>
		<script type="text/javascript">
			function confirmDel() {
				var x = confirm("Apakah Anda yakin ingin menghapus data ini?");

				if (x == true) {
					return true;
				} else {
					return false;
				}
			}

			function confirmAdd() {
				var a = confirm("Anda ingin menambahkan data?");

				if (a == true) {
					return true;
				} else {
					return false;
				}
			}

			function confirmUpdate() {
				var b = confirm("Anda yakin ingin mengubah data ini?");

				if (b == true) {
					return true;
				} else {
					return false;
				}
			}

			function confirmUpdateAdmin() {
				var c = confirm("Anda yakin ingin mengubah kata sandi ?");

				if (c == true) {
					return true;
				} else {
					return false;
				}
			}
		</script>

		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>

		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link id="bootstrap-style" href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
		<link id="base-style" href="css/style.css" rel="stylesheet">
		<link id="base-style-responsive" href="css/style-responsive.css" rel="stylesheet">
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>
		<link rel="shortcut icon" href="img/favicon.ico">

	</head>

	<body>
		<!-- start: Header -->
		<div class="navbar">
			<div class="navbar-inner">
				<div class="container-fluid">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<a class="brand" href="index.html"><span> </span></a>

					<!-- start: Header Menu -->
					<div class="nav-no-collapse header-nav">
						<ul class="nav pull-right">

							<!-- start: User Dropdown -->
							<li class="dropdown">
								<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
									<i class="halflings-icon white user"></i> Administrator
									<span class="caret"></span>
								</a>
								<ul class="dropdown-menu">
									<li class="dropdown-menu-title">
										<span>Pengaturan Akun</span>
									</li>
									<li><a href="change_admin_password.php"><i class="halflings-icon cog"></i> Ganti Password
										</a></li>
									<li><a href="logout.php"><i class="halflings-icon off"></i> Keluar</a></li>
								</ul>
							</li>
							<!-- end: User Dropdown -->
						</ul>
					</div>
					<!-- end: Header Menu -->

				</div>
			</div>
		</div>
		<!-- start: Header -->