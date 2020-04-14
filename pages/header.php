<?php
date_default_timezone_set('America/La_Paz');
ini_set('display_errors',1); 
ini_set('display_startup_errors',1); 
error_reporting(-1);
session_start();
ini_set('memory_limit', '-1');
ini_set('default_charset', 'utf-8');
define('R_PATH', '../');

if (!isset($_SESSION["userId"]) || empty($_SESSION['userId'])) {
	header("Location: login.php");
	return;
}
$documentReadyScript = "";
$userId = $_SESSION["userId"];

require_once R_PATH."server/DAL/Connection.php";
include_once R_PATH."server/DTO/Usuario.php";
include_once R_PATH."server/DTO/Cliente.php";
include_once R_PATH."server/DTO/Paquete.php";
include_once R_PATH."server/DTO/Propiedad.php";
include_once R_PATH."server/DTO/Servicio.php";
include_once R_PATH."server/DTO/Campana.php";
include_once R_PATH."server/DTO/PaqueteServicio.php";
include_once R_PATH."server/DTO/DetalleServicio.php";

include_once R_PATH.'server/BLL/UsuarioBLL.php';
include_once R_PATH.'server/BLL/ClienteBLL.php';
include_once R_PATH.'server/BLL/PaqueteBLL.php';
include_once R_PATH.'server/BLL/PropiedadBLL.php';
include_once R_PATH.'server/BLL/ServicioBLL.php';
include_once R_PATH.'server/BLL/CampanaBLL.php';
include_once R_PATH."server/BLL/PaqueteServicioBLL.php";
include_once R_PATH."server/BLL/DetalleServicioBLL.php";

include_once'UploadHandler.php';
include_once 'UIUtilities.php';
include_once R_PATH.'PHPMailer/PHPMailerAutoload.php';

$UsuarioBLL = new UsuarioBLL();
$objUsuarioAutenticado = $UsuarioBLL->selectById($userId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<!-- Meta, title, CSS, favicons, etc. -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Administrador de PixAdvisor</title>
	<!-- Bootstrap -->
	<link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<!-- Font Awesome -->
	<link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<!-- NProgress -->
	<link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
	<!-- iCheck -->
	<link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
	<!-- bootstrap-progressbar -->
	<link href="../vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
	<!-- JQVMap -->
	<link href="../vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
	<!-- bootstrap-daterangepicker -->
	<link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
	<!-- Datatables -->
	<link href="../vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
	<link href="../vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
	<link href="../vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
	<link href="../vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
	<link href="../vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
	<!-- PNotify -->
	<link href="../vendors/pnotify/dist/pnotify.css" rel="stylesheet">
	<link href="../vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
	<link href="../vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">
	<!-- Alertify -->
	<link href="../vendors/alertify/css/alertify.css" rel="stylesheet" />
	<link href="../vendors/alertify/css/themes/bootstrap.css" rel="stylesheet" />
	<!-- FileBrowser -->
	<link href="../vendors/fileBrowser/css/custom.css" rel="stylesheet" type="text/css">
	<link href="../vendors/fileBrowser/css/fileBrowser.css" rel="stylesheet" type="text/css">
	<!-- Switchery -->
	<link href="../vendors/switchery/dist/switchery.min.css" rel="stylesheet">
	<!-- Custom Theme Style -->
	<link href="../build/css/fileBrowser.css" rel="stylesheet">
	<link href="../build/css/custom.min.css" rel="stylesheet">
</head>

<body class="nav-md">
	<div class="container body">
		<div class="main_container">
			<div class="col-md-3 left_col">
				<div class="left_col scroll-view">
					<div class="navbar nav_title" style="border: 0;">
						<a href="index.php" class="site_title"><img src="../images/logo.png" class="img-responsive"></a>
					</div>
					<div class="clearfix"></div>
					<br />
					<!-- sidebar menu -->
					<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
						<div class="menu_section">
							<ul class="nav side-menu">
								<li><a href="index.php"><i class="fa fa-home"></i> <span class="es">Inicio</span><span class="pt hidden">In&iacute;cio</span></a></li>
								<li><a><i class="fa fa-briefcase"></i> Clientes <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="lista-clientes.php">Lista de Clientes</a></li>
										<li><a href="cliente.php"><span class="es">Registrar Cliente</span><span class="pt hidden">Registre Cliente</span></a></li>
									</ul>
								</li>
								<li><a><i class="fa fa-map-marker"></i> <span class="es">Propiedades</span><span class="pt hidden">Fazendas</span> <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="lista-propiedades.php"><span class="es">Lista de Propiedades</span><span class="pt hidden">Lista de Fazendas</span></a></li>
										<li><a href="propiedad.php"><span class="es">Registrar Propiedad</span><span class="pt hidden">Registre Fazenda</span></a></li>
									</ul>
								</li>
								<li><a><i class="fa fa-table"></i> <span class="es">Paquetes</span><span class="pt hidden">Pacotes</span> <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="lista-paquetes.php"><span class="es">Lista de Paquetes</span><span class="pt hidden">Lista de Pacotes</span></a></li>
										<li><a href="paquete.php"><span class="es">Registrar Paquete</span><span class="pt hidden">Registre Pacote</span></a></li>
										<li><a href="lista-servicios.php"><span class="es">Lista de Servicios</span><span class="pt hidden">Lista de Serviços</span></a></li>
										<li><a href="servicio.php"><span class="es">Registrar Servicio</span><span class="pt hidden">Registre Serviço</span></a></li>
									</ul>
								</li>
								<li><a><i class="fa fa-users"></i> <span class="es">Usuarios</span><span class="pt hidden">Usu&aacute;rios</span> <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="lista-usuarios.php"><span class="es">Lista de Usuarios</span><span class="pt hidden">Lista de Usu&aacute;rios</span></a></li>
										<li><a href="usuario.php"><span class="es">Registrar Usuarios</span><span class="pt hidden">Registre Usu&aacute;rio</span></a></li>
									</ul>
								</li>
							</ul>
						</div>
					</div>
					<!-- /sidebar menu -->

					<!-- /menu footer buttons -->
					<div class="sidebar-footer hidden-small">
						<a data-toggle="tooltip" data-placement="top" title="Salir" href="login.php" class="pull-right">
							<span class="glyphicon glyphicon-off" aria-hidden="true"></span>
						</a>
					</div>
					<!-- /menu footer buttons -->
				</div>
			</div>

			<!-- top navigation -->
			<div class="top_nav">
				<div class="nav_menu">
					<nav>
						<div class="nav toggle">
							<a id="menu_toggle"><i class="fa fa-bars"></i></a>
						</div>
						<ul class="nav navbar-nav navbar-right">
							<li class="">
								<a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
									<?php echo $objUsuarioAutenticado->getTxtNombre().' '. $objUsuarioAutenticado->getTxtApellidos(); ?>
									<span class=" fa fa-angle-down"></span>
								</a>
								<ul class="dropdown-menu dropdown-usermenu pull-right">
									<li><a href="login.php"><i class="fa fa-sign-out pull-right"></i> <span class="es">Salir</span><span class="pt hidden">Sair</span></a></li>
								</ul>
							</li>
						</ul>
						<div class="language pull-right">
							<a href="javascript:;" class="lang active espa" data-lang="es">Espa&ntilde;ol</a> - <a href="javascript:;" class="lang portu" data-lang="pt">Portugu&eacute;s</a>
						</div>
					</nav>
				</div>
			</div>
        <!-- /top navigation -->