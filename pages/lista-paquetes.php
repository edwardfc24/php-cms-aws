<?php
header('Content-Type: text/html; charset=utf-8');
include_once 'header.php';

ini_set('display_errors',1); 
ini_set('display_startup_errors',1); 
error_reporting(-1);

$paqueteBLL = new PaqueteBLL();
$arregloPaquetes = $paqueteBLL->selectAll();

//Obtengo todos los servicios
$servicioBLL = new ServicioBLL();
$arregloServicios = $servicioBLL->selectAll();
?>
<div class="right_col" role="main">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="page-title">
				<div class="title_left">
					<h3><span class="es">Paquetes <small>registrados en la plataforma</small></span><span class="pt hidden">Pacotes <small>registrado na plataforma</small></span></h3>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="x_panel">
				<div class="x_title">
					<h2>Lista de <span class="es">Paquetes</span><span class="pt hidden">Pacotes</span></h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<table class="table table-striped table-bordered" id="listaPaquetes">
						<thead>
							<tr>
								<th>ID</th>
								<th><span class="es">Paquete (ES)</span><span class="pt hidden">Pacote (ES)</span></th>
								<th><span class="es">Paquete (PT)</span><span class="pt hidden">Pacote (PT)</span></th>
								<th><span class="es">Orden</span><span class="pt hidden">Ordem</span></th>
								<th>Estado</th>
								<th><span class="es">Acciones</span><span class="pt hidden">Ações</span></th>
							</tr>
						</thead>
						<tbody><?php  
							foreach ($arregloPaquetes as $objPaquete) {?>
							<tr>
								<td><?php echo $objPaquete->getIdPaquete(); ?></td>
								<td><?php echo utf8_decode($objPaquete->getTxtNombreEs()); ?></td>
								<td><?php echo utf8_decode($objPaquete->getTxtNombrePt()); ?></td>
								<td><?php echo $objPaquete->getOrden(); ?></td>
								<td><?php echo ($objPaquete->getEstado() == 1)?'Activo': 'Inactivo'; ?></td>
								<td>
									<a href="paquete.php?task=cargar&id=<?php echo $objPaquete->getIdPaquete(); ?>" title="Editar" style="font-size: 18px; line-height: 1;"><i class="fa fa-edit fa-fw"></i></a>
									<a href="javascript:eliminarPaquete(<?php echo $objPaquete->getIdPaquete(); ?>);" title="Eliminar" style="font-size: 18px; line-height: 1;"><i class="fa fa-remove fa-fw"></i></a>
									<a href="javascript:asociarServicios('<?php echo utf8_decode($objPaquete->getTxtNombreEs()); ?>', <?php echo $objPaquete->getIdPaquete(); ?>);" title="Asociar Servicios" style="font-size: 18px; line-height: 1;"><i class="fa fa-star fa-fw"></i></a>
								</td>
							</tr><?php  
						}?>
					</tbody>
				</table>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-6">
				<a href="paquete.php" class="btn btn-primary pull-right"><span class="es">Nuevo Paquete</span><span class="pt hidden">Novo Pacote</span></a>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<input type="hidden" id="idPaquete" value="" />
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12"><span class="es">Servicios</span><span class="pt hidden">Serviços</span></label>
					<div class="col-md-9 col-sm-9 col-xs-12"><?php  
						foreach ($arregloServicios as $objServicio) {
							if($objServicio->getEstado() == 1) {?>
							<div class="">
								<label>
									<input type="checkbox" class="js-switch servicios" value="<?php echo $objServicio->getIdServicio(); ?>" /> <span class="es"><?php echo utf8_decode($objServicio->getTxtNombreEs()); ?></span><span class="pt hidden"><?php echo utf8_decode($objServicio->getTxtNombrePt()); ?></span>
								</label>
							</div><?php  
						}
					}?>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="modal-footer">
			<button id="cancel" type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			<a id="enviar" href="javascript:guardarPaquete();" class="btn btn-primary">Guardar</a>
		</div>
	</div>
</div>
</div>
<?php include_once 'footer.php'; ?>
<script type="text/javascript">
	$(document).ready(function () {
		$("#listaPaquetes").DataTable({
			responsive: true,
			"ordering": false,
			"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
			language: {
				"sProcessing": "Procesando...",
				"sLengthMenu": "Mostrar _MENU_",
				"sZeroRecords": "No se encontraron resultados",
				"sEmptyTable": "Ningún dato disponible en esta tabla",
				"sInfo": "Mostrando del _START_ al _END_ de un total de _TOTAL_ usuarios",
				"sInfoEmpty": "Mostrando usuarios del 0 al 0 de un total de 0 usuarios",
				"sInfoFiltered": "(filtrado de un total de _MAX_ usuarios)",
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

	function eliminarPaquete(id){
		alertify.confirm('Eliminar Paquete', 'Esta seguro de eliminar este paquete? Se borraran los datos permanentemente.' 
			,function(){
				$.ajax({
					data : {task:"eliminar", id: id},
					url: 'paquete.php',
					type: 'post',
					success: function (data) {
						alertify.notify('Se elimin&oacute; el paquete correctamente.', 'success', 3, function () {
							window.location.href = 'lista-paquetes.php';
						});
					}
				});			
			}
			, function(){});
	}

	function asociarServicios(nombre, id){
		$(".modal-title").html(nombre);
		$("#idPaquete").val(id);
		$.ajax({
			data : {task: "consulta", paquete: id },
			url: 'funciones-paquete.php',
			type: 'post',
			success: function (data) {
				var servicios = JSON.parse(data);
				if (servicios.length > 0) {
					for (var i = 0; i < servicios.length ; i++) {
						changeSwitchery($("input.servicios[value="+servicios[i]+"]"), true);
					}
					$("#exampleModal").modal();
				} else {
					$(".servicios").each(function() {
						changeSwitchery($(this), false);
					});
					$("#exampleModal").modal();
				}
			}
		});
	}

	function guardarPaquete() {
		$("#cancel").attr("disabled", "disabled");
		$("#enviar").addClass("hidden");
		var paquete = $("#idPaquete").val();
		servicios = [];
		$(".servicios").each(function(){
			if($(this).is(":checked"))
				servicios.push($(this).val());
		});
		$.ajax({
			data : {task: "grabar", paquete: paquete, servicios: JSON.stringify(servicios)},
			url: 'funciones-paquete.php',
			type: 'post',
			success: function (data) {
				alertify.notify('Se asociaron los servicios correctamente.', 'success', 2, function () {
					$("#cancel").removeAttr("disabled");
					$("#enviar").removeClass("hidden");
					$("#exampleModal").modal('hide');
				});
			}
		});	
	}
	function changeSwitchery(element, checked) {
		if ( ( element.is(':checked') && checked == false ) || ( !element.is(':checked') && checked == true ) ) {
			element.parent().find('.switchery').trigger('click');
		}
	}
</script>





