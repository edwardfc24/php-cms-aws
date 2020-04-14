<?php
header('Content-Type: text/html; charset=utf-8');
include_once 'header.php';

ini_set('display_errors',1); 
ini_set('display_startup_errors',1); 
error_reporting(-1);


if (isset($_REQUEST['id'])) {
	$id = $_REQUEST['id'];
}

$servicioBLL = new ServicioBLL();
$servicio = $servicioBLL->selectById($id);

$detalleservicioBLL = new DetalleServicioBLL();
$arregloDetalleservicios = $detalleservicioBLL->selectByServiceId($id);

?>
<div class="right_col" role="main">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="page-title">
				<div class="title_left">
					<h3><?php echo $servicio->getTxtNombre(); ?></h3>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="x_panel">
				<div class="x_title">
					<h2>Detalle del Servicio</h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<table class="table table-striped table-bordered" id="listaDetalleservicios">
						<thead>
							<tr>
								<th>ID</th>
								<th>Detalle</th>
								<th>Orden</th>
								<th>Estado</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody><?php  
							foreach ($arregloDetalleservicios as $objDetalleservicio) {?>
							<tr>
								<td><?php echo $objDetalleservicio->getIdDetalleservicio(); ?></td>
								<td><?php echo $objDetalleservicio->getTxtNombre(); ?></td>
								<td><?php echo $objDetalleservicio->getOrden(); ?></td>
								<td><?php echo ($objDetalleservicio->getEstado() == 1)?'Activo': 'Inactivo'; ?></td>
								<td>
									<a href="javascript:editar(<?php echo $objDetalleservicio->getIdDetalleservicio(); ?>, '<?php echo $objDetalleservicio->getTxtNombre(); ?>', <?php echo $objDetalleservicio->getEstado(); ?>, <?php echo $objDetalleservicio->getOrden(); ?>);" title="Editar" style="font-size: 18px; line-height: 1;"><i class="fa fa-edit fa-fw"></i></a>
									<a href="javascript:eliminarDetalleservicio(<?php echo $objDetalleservicio->getIdDetalleservicio(); ?>, <?php echo $servicio->getIdServicio(); ?>);" title="Eliminar" style="font-size: 18px; line-height: 1;"><i class="fa fa-remove fa-fw"></i></a>
								</td>
							</tr><?php  
						}?>
					</tbody>
				</table>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-6">
				<a href="javascript:agregarDetalle();" class="btn btn-primary pull-right">Nuevo detalle de Servicio</a>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Detalle del Servicio</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<input type="hidden" id="idServicio" value="<?php echo $id; ?>" />
				<input type="hidden" id="detalleServicio" value="0">
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Nombre</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input class="form-control" placeholder="Nombre" id="detNombre" type="text" autocomplete="off" value="">
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Orden</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input class="form-control" placeholder="Orden" id="detOrden" type="Number" autocomplete="off" value="1">
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="checkbox">
					<label class="">
						<div class="icheckbox_flat-green checked">
							<input class="flat" checked id="detEstado" type="checkbox" value="1">
							<ins class="iCheck-helper"></ins>
						</div> Activo
					</label>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				<a href="javascript:guardarDetalle();" class="btn btn-primary">Guardar</a>
			</div>
		</div>
	</div>
</div>
</div>
<?php include_once 'footer.php'; ?>
<script type="text/javascript">
	$(document).ready(function () {
		$("#listaDetalleservicios").DataTable({
			responsive: true,
			"ordering": false,
			"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
			language: {
				"sProcessing": "Procesando...",
				"sLengthMenu": "Mostrar _MENU_",
				"sZeroRecords": "No se encontraron resultados",
				"sEmptyTable": "Ningún dato disponible en esta tabla",
				"sInfo": "Mostrando del _START_ al _END_ de un total de _TOTAL_ detalles",
				"sInfoEmpty": "Mostrando detalles del 0 al 0 de un total de 0 detalles",
				"sInfoFiltered": "(filtrado de un total de _MAX_ detalles)",
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

	function eliminarDetalleservicio(id, servicio){
		alertify.confirm('Eliminar Detalle', 'Esta seguro de eliminar este detalle? Se borraran los datos permanentemente.' 
			,function(){
				$.ajax({
					data : {task:"eliminar", detalle: id},
					url: 'funciones-detalle.php',
					type: 'post',
					success: function (data) {
						alertify.notify('Se elimin&oacute; el detalle correctamente.', 'success', 3, function () {
							window.location.href = 'detalle-servicio.php?id='+servicio;
						});
					}
				});			
			}
			, function(){});
	}

	function agregarDetalle(){
		$("#detalleServicio").val("0");
		$("#detNombre").val("");
		$("#detOrden").val("1");
		$("#detEstado").prop('checked', true);
		$("#exampleModal").modal();
	}

	function editar(id, nombre, estado, orden){
		$("#detalleServicio").val(id);
		$("#detNombre").val(nombre);
		$("#detOrden").val(orden);
		if(estado == 1)
			$("#detEstado").prop('checked', true);
		else
			$("#detEstado").prop('checked', false);
		$("#exampleModal").modal();
	}

	function guardarDetalle() {
		var servicio = $("#idServicio").val();
		var detalle = $("#detalleServicio").val();
		var nombre = $("#detNombre").val();
		var orden = $("#detOrden").val();
		var estado = 0;
		if($("#detEstado").is(":checked"))
			estado = 1;
		var task = "insertar";
		if(detalle != 0)
			task = "actualizar";
		$.ajax({
			data : {task: task, detalle: detalle, nombre: nombre, estado: estado, servicio: servicio, orden: orden},
			url: 'funciones-detalle.php',
			type: 'post',
			success: function (data) {
				if(task == "insertar"){
					alertify.notify('Se insert&oacute; el detalle correctamente.', 'success', 2, function () {
						window.location.href = 'detalle-servicio.php?id='+servicio;
					});
				} else {
					alertify.notify('Se actualiz&oacute; el detalle correctamente.', 'success', 2, function () {
						window.location.href = 'detalle-servicio.php?id='+servicio;
					});
				}
			}
		});		
	}
</script>





