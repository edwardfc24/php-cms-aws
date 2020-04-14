<?php
header('Content-Type: text/html; charset=utf-8');
include_once 'header.php';


$documentReadyScript = "";
$task = "";
$mostrar = true;
$nombre = "";
$latitud = "";
$longitud = "";
$cliente = ""; 	

// Consultando el request 
if (isset($_REQUEST['task']))
	$task = $_REQUEST['task'];

$tituloPagina = "Registro de Propiedad";

if (isset($_REQUEST['id'])) {
	$id = $_REQUEST['id'];
	$propiedadBLL = new PropiedadBLL();
	$propiedad = $propiedadBLL->selectById($id);
	$nombre = $propiedad->getTxtNombre();
	$latitud = $propiedad->getLatitud();
	$longitud = $propiedad->getLongitud();
	$cliente = $propiedad->getIdCliente();
	$mostrar = false;
}

if (isset($_REQUEST['nombre'])) {
	$nombre = $_REQUEST['nombre'];
}

if (isset($_REQUEST['posicion'])) {
	$posicion = $_REQUEST['posicion'];
}

if (isset($_REQUEST['cliente'])) {
	$cliente = $_REQUEST['cliente'];
}

?>
<div class="right_col" role="main">
	<div class="row">
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=XXXXXXXXXXXXXXXXXXXXXXXXX"></script>
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div id="map" style="width:100%; height: 500px;"></div>
		</div>
		<div class="col-md-6 col-sm-8 col-xs-12">
			<div class="x_panel">
				<div class="x_title">
					<h2><?php echo $tituloPagina; ?></h2>
					<div class="clearfix"></div>
				</div>
				<form id="creacionPropiedad" role="form" action="propiedad.php" method="POST">
					<div class="x_content">
						<input type="hidden" name="id" value="<?php echo $id; ?>" />
						<input type="hidden" name="task" value="<?php echo ($task == 'cargar')?'actualizar':'insertar'; ?>">
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><span class="es">Nombre</span><span class="pt hidden">Nome</span></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input class="form-control" name="nombre" type="text" autocomplete="off" value="<?php echo $nombre; ?>">
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><span class="es">Coordenadas</span><span class="pt hidden">Localização</span></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input class="form-control" type="text" name="posicion" autocomplete="off" value="<?php echo '('.$latitud.', '.$longitud.')'; ?>">
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Cliente</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<select class="form-control" name="cliente" <?php echo ($cliente != "")?'readonly':''; ?>><?php 
									$clienteBLL = new ClienteBLL();
									$arregloClientes = $clienteBLL->selectAll();
									foreach ($arregloClientes as $objCliente) {?>
									<option value="<?php echo $objCliente->getIdCliente(); ?>" <?php echo ($cliente == $objCliente->getIdCliente())?'selected':''; ?>>
										<?php echo utf8_decode($objCliente->getTxtNombre().' '.$objCliente->getTxtApellidos()); ?>
									</option><?php 								
								} ?>    
							</select>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="clearfix"></div>
				</div>
				<div>
					<input type="submit" class="btn btn-primary pull-right" value="Guardar">
					<a href="lista-propiedades.php" class="btn btn-danger pull-right">Cancelar</a>
				</div>
			</div>
		</form>
	</div>
</div>
</div>
</div>
<?php 

switch ($task) {
	case "insertar":
	insertar($nombre, $posicion, $cliente);
	break;
	case "eliminar" :
	eliminar($id);
	break;
	case "actualizar":
	actualizar($id, $nombre, $posicion, $cliente);
	break;
}

function insertar($nombre, $posicion, $cliente){
	$coord = explode(",", $posicion);
	$coord[0] = str_replace("(", "", $coord[0]);
	$coord[1] = str_replace(")", "", $coord[1]);

	$propiedadBLL = new PropiedadBLL();
	$propiedad = $propiedadBLL->insert($nombre, $coord[0], $coord[1], $cliente);
	if (isset($propiedad)) {
		alert_redirect("success", "Propiedad insertada correctamente.", "lista-propiedades.php");
	}else {
		alert_redirect("error", "Error al insertar Propiedad, por favor intente nuevamente", "lista-propiedades.php");
	}
}

function actualizar($id, $nombre, $posicion, $cliente){
	$coord = explode(",", $posicion);
	$coord[0] = str_replace("(", "", $coord[0]);
	$coord[1] = str_replace(")", "", $coord[1]);

	$propiedadBLL = new PropiedadBLL();
	$aux = $propiedadBLL->selectById($id);

	$propiedad = $propiedadBLL->update($id, $aux->getTxtNombre(), $coord[0], $coord[1], $aux->getIdCliente());

	if (isset($id)) {
		alert_redirect("success", "Propiedad actualizada correctamente. Correo Enviado.", "lista-propiedades.php");
	} else {
		alert_redirect("error", "Error al actualizar Propiedad, por favor intente nuevamente", "lista-propiedades.php");
	}
}
function eliminar($id) {
	$propiedadBLL = new PropiedadBLL();
	require_once "../server/start.php";
	$prefix = $id.'/';
	$objects = $s3->getIterator('ListObjects', array('Bucket' => $config['s3']['bucket'],'Prefix' => $prefix));
	if($objects){
		foreach ($objects as $object) {
			$porEliminar[] = ['Key' => $object['Key']];
		}
		$s3->deleteObjects([
			'Bucket' => $config['s3']['bucket'], 
			'Delete' => [ 
			'Objects' => $porEliminar
			]
			]);
	}
	$propiedadBLL->delete($id);
	alert_redirect("success", "Propiedad eliminado correctamente.", "lista-propiedades.php");
}
?>
<?php include_once 'footer.php'; ?>
<script type="text/javascript">
	var map, marker;
	google.maps.event.addDomListener(window, 'load', init);
	function init() {
		var mapOptions = {
			zoom: 6,
			center: new google.maps.LatLng(-8.951936,-56.0446247),
			scrollwheel: false
		};
		var mapElement = document.getElementById('map');
		map = new google.maps.Map(mapElement, mapOptions);
		google.maps.event.addListener(map, 'click', function(event) {
			placeMarker(event.latLng);
		});

		function placeMarker(location) {
			if (marker == undefined){
				marker = new google.maps.Marker({
					position: location,
					map: map, 
					animation: google.maps.Animation.DROP,
				});
			}
			else{
				marker.setPosition(location);
			}
			map.setCenter(location);
			$( "input[name='posicion']" ).val(location);
		}
	}
</script>
