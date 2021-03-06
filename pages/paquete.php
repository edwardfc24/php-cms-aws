<?php
header('Content-Type: text/html; charset=utf-8');
include_once 'header.php';


$documentReadyScript = "";
$task = "";
$mostrar = true;
$nombre_es = "";
$nombre_pt = "";
$orden = "";
// Consultando el request 
if (isset($_REQUEST['task']))
	$task = $_REQUEST['task'];

$tituloPagina = "Registro de Paquete";

if (isset($_REQUEST['id'])) {
	$id = $_REQUEST['id'];
	$paqueteBLL = new PaqueteBLL();
	$paquete = $paqueteBLL->selectById($id);
	$nombre_es = $paquete->getTxtNombreEs();
	$nombre_pt = $paquete->getTxtNombrePt();
	$mostrar = false;
	$state = $paquete->getEstado();
	$orden = $paquete->getOrden();
}

if (isset($_REQUEST['nombre_es'])) {
	$nombre_es = $_REQUEST['nombre_es'];
}
if (isset($_REQUEST['nombre_pt'])) {
	$nombre_pt = $_REQUEST['nombre_pt'];
}
if (isset($_REQUEST['orden'])) {
	$orden = $_REQUEST['orden'];
}

if (isset($_REQUEST['state'])) {
	$state = $_REQUEST['state'];
} else {
	if($task == "")
		$state = 1;
	elseif($task == "actualizar")
		$state = 0;
}
?>
<div class="right_col" role="main">
	<div class="row">
		<div class="col-md-6 col-sm-8 col-xs-12">
			<div class="x_panel">
				<div class="x_title">
					<h2><?php echo $tituloPagina; ?></h2>
					<div class="clearfix"></div>
				</div>
				<form id="creacionPaquete" role="form" action="paquete.php" method="POST">
					<div class="x_content">

						<input type="hidden" name="id" value="<?php echo $id; ?>" />
						<input type="hidden" name="task" value="<?php echo ($task == 'cargar')?'actualizar':'insertar'; ?>">
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><span class="es">Nombre en espa&ntilde;ol</span><span class="pt hidden">Nome em espanhol</span></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input class="form-control" name="nombre_es" type="text" autocomplete="off" value="<?php echo $nombre_es; ?>">
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><span class="es">Nombre en portugu&eacute;s</span><span class="pt hidden">Nome em português</span></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input class="form-control" name="nombre_pt" type="text" autocomplete="off" value="<?php echo $nombre_pt; ?>">
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><span class="es">Orden</span><span class="pt hidden">Ordem</span></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input class="form-control" name="orden" type="number" min="0" autocomplete="off" value="<?php echo $orden; ?>">
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="checkbox">
							<label class="">
								<div class="icheckbox_flat-green <?php echo ($state == 1)?'checked':''; ?>">
									<input class="flat" <?php echo ($state == 1)?'checked':''; ?>  name="state" type="checkbox" value="1">
									<ins class="iCheck-helper"></ins>
								</div> <span class="es">Activo</span><span class="pt hidden">Ativo</span>
							</label>
						</div>
					</div>
					<div>
						<input type="submit" class="btn btn-primary pull-right" value="Guardar">
						<a href="lista-paquetes.php" class="btn btn-danger pull-right">Cancelar</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php 

switch ($task) {
	case "insertar":
	insertar($nombre_es, $nombre_pt, $orden, $state);
	break;
	case "eliminar" :
	eliminar($id);
	break;
	case "actualizar":
	actualizar($id, $nombre_es, $nombre_pt, $orden, $state);
	break;
}

function insertar($nombre_es, $nombre_pt, $orden, $state){
	$paqueteBLL = new PaqueteBLL();
	$paquete = $paqueteBLL->insert($nombre_es, $nombre_pt, $orden, $state);
	if (isset($paquete)) {
		alert_redirect("success", "Paquete insertado correctamente.", "lista-paquetes.php");
	}else {
		alert_redirect("error", "Error al insertar Paquete, por favor intente nuevamente", "lista-paquetes.php");
	}
}
function actualizar($id, $nombre_es, $nombre_pt, $orden, $state){
	$paqueteBLL = new PaqueteBLL();
	$paquete = $paqueteBLL->update($id, $nombre_es, $nombre_pt, $orden, $state);
	if (isset($id)) {	
		alert_redirect("success", "Paquete insertado correctamente. Correo Enviado.", "lista-paquetes.php");
	} else {
		alert_redirect("error", "Error al insertar Paquete, por favor intente nuevamente", "lista-paquetes.php");
	}
}

function eliminar($id) {
	$paqueteBLL = new PaqueteBLL();
	$paqueteBLL->delete($id);
	alert_redirect("success", "Paquete eliminado correctamente.", "lista-paquetes.php");
}
?>
<?php include_once 'footer.php'; ?>



