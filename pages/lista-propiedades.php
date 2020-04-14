<?php
header('Content-Type: text/html; charset=utf-8');
include_once 'header.php';

ini_set('display_errors',1); 
ini_set('display_startup_errors',1); 
error_reporting(-1);

$task = "";

if(isset($_REQUEST["task"]))
	$task = $_REQUEST["task"];

$propiedadBLL = new PropiedadBLL();
$arregloPropiedades = $propiedadBLL->selectAll();
$clienteBLL = new ClienteBLL();

switch ($task) {
	case "cliente":
	$id = $_REQUEST["id"];
	$arregloPropiedades = $propiedadBLL->selectByClientId($id);
	break;
}
?>
<div class="right_col" role="main">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="page-title">
				<div class="title_left">
					<h3><span class="es">Propiedades <small>registrados en la plataforma</small></span><span class="pt hidden">Fazendas <small>registrado na plataforma</small></span></h3>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="x_panel">
				<div class="x_title">
					<h2>Lista de <span class="es">Propiedades</span><span class="pt hidden">Fazendas</span></h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<table class="table table-striped table-bordered" id="listaPropiedades">
						<thead>
							<tr>
								<th>ID</th>
								<th><span class="es">Propiedad</span></th>
								<th><span class="es">Ubicaci&oacute;n</span><span class="pt hidden">Localização</span></th>
								<th><span class="es">Propietario</span><span class="pt hidden">Propiet&aacute;rio</span></th>
								<th><span class="es">Acciones</span><span class="pt hidden">Ações</span></th>
							</tr>
						</thead>
						<tbody><?php  
							foreach ($arregloPropiedades as $objPropiedad) {
								$datosCliente = $clienteBLL->selectById($objPropiedad->getIdCliente());?>
								<tr>
									<td><?php echo $objPropiedad->getIdPropiedad(); ?></td>
									<td><?php echo utf8_decode($objPropiedad->getTxtNombre()); ?></td>
									<td>(<?php echo $objPropiedad->getLatitud(); ?>, <?php echo $objPropiedad->getLongitud(); ?>)</td>
									<td><?php echo utf8_decode($datosCliente->getTxtNombre().' '.$datosCliente->getTxtApellidos()); ?></td>
									<td>
										<a href="propiedad.php?task=cargar&id=<?php echo $objPropiedad->getIdPropiedad(); ?>" title="Editar" style="font-size: 18px; line-height: 1;"><i class="fa fa-edit fa-fw"></i></a>
										<a href="javascript:eliminarPropiedad(<?php echo $objPropiedad->getIdPropiedad(); ?>);" title="Eliminar" style="font-size: 18px; line-height: 1;"><i class="fa fa-remove fa-fw"></i></a>
										<a href="propiedad-campania.php?&id=<?php echo $objPropiedad->getIdPropiedad(); ?>" title="Campañas" style="font-size: 18px; line-height: 1;"><i class="fa fa-star fa-fw"></i></a>
									</td>
								</tr><?php  
							}?>
						</tbody>
					</table>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-6">
					<a href="propiedad.php" class="btn btn-primary pull-right"><span class="es">Nueva Propiedad</span><span class="pt hidden">Nova Fazenda</span></a>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include_once 'footer.php'; ?>
<script type="text/javascript">
	$(document).ready(function () {
		$("#listaPropiedades").DataTable({
			responsive: false,
			"ordering": false,
			"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
			language: {
				"sProcessing": "Procesando...",
				"sLengthMenu": "Mostrar _MENU_",
				"sZeroRecords": "No se encontraron resultados",
				"sEmptyTable": "Ningún dato disponible en esta tabla",
				"sInfo": "Mostrando del _START_ al _END_ de un total de _TOTAL_ propiedades",
				"sInfoEmpty": "Mostrando propiedades del 0 al 0 de un total de 0 propiedades",
				"sInfoFiltered": "(filtrado de un total de _MAX_ propiedades)",
				"sInfoPostFix": "",
				"sSearch": "Buscar:",
				"sUrl": "",
				"sInfoThousands": ",",
				"sLoadingRecords": "Cargando...",
				"oPaginate": {
					"sFirst": "Primero",
					"sLast": "Último",
					"sNext": "Siguiente",
					"sPrevious": "Anterior"
				},
				"oAria": {
					"sSortAscending": ": Activar para ordenar la columna de manera ascendente",
					"sSortDescending": ": Activar para ordenar la columna de manera descendente"
				}
			},
			buttons: [
			'excel'
			]
		});
	});

	function eliminarPropiedad(id){
		alertify.confirm('Eliminar Propiedad', 'Esta seguro de eliminar este propiedad? Se borraran los datos permanentemente.' 
			,function(){
				$.ajax({
					data : {task:"eliminarPropiedad", propiedad: id},
					url: 'funciones-eliminado.php',
					type: 'post',
					success: function (data) {
						alertify.notify('Se elimin&oacute; el propiedad correctamente.', 'success', 1.5, function () {
							window.location.href = 'lista-propiedades.php';
						});
					}
				});			
			}
			, function(){});
	}
</script>





