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
					<h3><span class="es"><?php echo $servicio->getTxtNombreEs(); ?></span><span class="pt"><?php echo $servicio->getTxtNombrePt(); ?></span></h3>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="x_panel">
				<div class="x_title">
					<h2><span class="es">Detalle del Servicio</span><span class="pt">Detalhe do Serviço</span></h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<table class="table table-striped table-bordered" id="listaDetalleservicios">
						<thead>
							<tr>
								<th>ID</th>
								<th><span class="es">Detalle</span><span class="pt">Detalhe</span></th>
								<th><span class="es">Orden</span><span class="pt">Ordem</span></th>
								<th>Estado</th>
								<th><span class="es">Acciones</span><span class="pt">Ações</span></th>
							</tr>
						</thead>
						<tbody><?php  
						foreach ($arregloDetalleservicios as $objDetalleservicio) {?>
						<tr>
							<td><?php echo $objDetalleservicio->getIdDetalleServicio(); ?></td>
							<td><span class="es"><?php echo $objDetalleservicio->getTxtNombreEs(); ?></span><span class="pt"><?php echo $objDetalleservicio->getTxtNombrePt(); ?></span></td>
							<td><?php echo $objDetalleservicio->getOrden(); ?></td>
							<td><span class="es"><?php echo ($objDetalleservicio->getEstado() == 1)?'Activo': 'Inactivo'; ?></span><span class="pt"><?php echo ($objDetalleservicio->getEstado() == 1)?'Ativo': 'Inativo'; ?></span></td>
							<td>
								<a href="javascript:editar(<?php echo $objDetalleservicio->getIdDetalleservicio(); ?>, '<?php echo $objDetalleservicio->getTxtNombreEs(); ?>', '<?php echo $objDetalleservicio->getTxtNombrePt(); ?>', <?php echo $objDetalleservicio->getEstado(); ?>, <?php echo $objDetalleservicio->getOrden(); ?>);" title="Editar" style="font-size: 18px; line-height: 1;"><i class="fa fa-edit fa-fw"></i></a>
								<a href="javascript:eliminarDetalleservicio(<?php echo $objDetalleservicio->getIdDetalleservicio(); ?>, <?php echo $servicio->getIdServicio(); ?>);" title="Eliminar" style="font-size: 18px; line-height: 1;"><i class="fa fa-remove fa-fw"></i></a>
							</td>
							</tr><?php  
						}?>
					</tbody>
				</table>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-6">
				<a href="javascript:agregarDetalle();" class="btn btn-primary pull-right"><span class="es">Nuevo detalle de Servicio</span><span class="pt">Novo Detalhe do Serviço</span></a>
				<a href="lista-servicios.php" class="btn btn-danger pull-right">Cancelar</a>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><span class="es">Detalle del Servicio</span><span class="pt">Detalhe do Serviço</span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<input type="hidden" id="idServicio" value="<?php echo $id; ?>" />
				<input type="hidden" id="detalleServicio" value="0">
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12"><span class="es">Nombre en espa&ntilde;ol</span><span class="pt">Nome em espanhol</span></label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input class="form-control" id="detNombre_es" type="text" autocomplete="off" value="">
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12"><span class="es">Nombre en portugu&eacute;s</span><span class="pt hidden">Nome em português</span></label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input class="form-control" id="detNombre_pt" type="text" autocomplete="off" value="">
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12"><span class="es">Orden</span><span class="pt">Ordem</span></label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input class="form-control" id="detOrden" type="Number" autocomplete="off" value="1">
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="checkbox">
					<label class="">
						<div class="icheckbox_flat-green checked">
							<input class="flat" checked id="detEstado" type="checkbox" value="1">
							<ins class="iCheck-helper"></ins>
						</div> <span class="es">Activo</span><span class="pt">Ativo</span>
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
		$("#detNombre_es").val("");
		$("#detNombre_pt").val("");
		$("#detOrden").val("1");
		$("#detEstado").prop('checked', true);
		$("#exampleModal").modal();
	}

	function editar(id, nombre_es, nombre_pt, estado, orden){
		$("#detalleServicio").val(id);
		$("#detNombre_es").val(nombre_es);
		$("#detNombre_pt").val(nombre_pt);
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
		var nombre_es = $("#detNombre_es").val();
		var nombre_pt = $("#detNombre_pt").val();
		var orden = $("#detOrden").val();
		var estado = 0;
		if($("#detEstado").is(":checked"))
			estado = 1;
		var task = "insertar";
		if(detalle != 0)
			task = "actualizar";
		$.ajax({
			data : {task: task, detalle: detalle, nombre_es: nombre_es, nombre_pt: nombre_pt, estado: estado, servicio: servicio, orden: orden},
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





