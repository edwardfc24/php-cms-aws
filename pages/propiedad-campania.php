<?php
header('Content-Type: text/html; charset=utf-8');
include_once 'header.php';

ini_set('display_errors',1); 
ini_set('display_startup_errors',1); 
error_reporting(-1);


if (isset($_REQUEST['id'])) {
	$id = $_REQUEST['id'];
}

$propiedadBLL = new PropiedadBLL();
$propiedad = $propiedadBLL->selectById($id);

$campanaBLL = new CampanaBLL();
$arregloCampanas = $campanaBLL->selectByPropertyId($id);

//Obtengo todos los paquetes
$paqueteBLL = new PaqueteBLL();
$arregloPaquetes = $paqueteBLL->selectAll();
?>
<div class="right_col" role="main">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="page-title">
				<div class="title_left">
					<h3><?php echo $propiedad->getTxtNombre(); ?></h3>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="x_panel">
				<div class="x_title">
					<h2><span class="es">Estudios de la Propiedad</span><span class="pt hidden">Estudos Fazenda</span></h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<table class="table table-striped table-bordered" id="listaCampanas">
						<thead>
							<tr>
								<th>ID</th>
								<th>Tipo</th>
								<th><span class="es">Gesti&oacute;n</span><span class="pt hidden">Gestão</span></th>
								<th><span class="es">Fecha de Estudio</span><span class="pt hidden">Data</span></th>
								<th><span class="es">Paquete</span><span class="pt hidden">Pacote</span></th>
								<th><span class="es">Acciones</span><span class="pt hidden">Ações</span></th>
							</tr>
						</thead>
						<tbody><?php  
						foreach ($arregloCampanas as $objCampana) {
							$arregloFecha = explode("-", $objCampana->getFechaEstudio());
							$fechaCorregida = $arregloFecha[2]."/".$arregloFecha[1]."/".$arregloFecha[0];
							$pacote = $paqueteBLL->selectById($objCampana->getIdPaquete());?>
							<tr>
								<td><?php echo $objCampana->getIdCampana(); ?></td>
								<td><?php echo utf8_decode($objCampana->getTipoCampana()); ?></td>
								<td><?php echo $objCampana->getGestion(); ?></td>
								<td><?php echo $fechaCorregida; ?></td>
								<td><span class="es"><?php echo $pacote->getTxtNombreEs(); ?></span><span class="pt hidden"><?php echo $pacote->getTxtNombrePt(); ?></span></td>
								<td>
									<a href="javascript:eliminarCampana(<?php echo $objCampana->getIdCampana(); ?>, <?php echo $propiedad->getIdPropiedad(); ?>);" title="Eliminar" style="font-size: 18px; line-height: 1;"><i class="fa fa-remove fa-fw"></i></a>
									<a href="javascript:limpiarRedirigir(<?php echo $objCampana->getIdCampana(); ?>);" title="Cargar Elementos" style="font-size: 18px; line-height: 1;"><i class="fa fa-star fa-fw"></i></a>
								</td>
								</tr><?php  
							}?>
						</tbody>
					</table>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-6">
					<a href="javascript:agregarCampana();" class="btn btn-primary pull-right"><span class="es">Nuevo Estudio</span><span class="pt hidden">Novo Estudo</span></a>
					<a href="lista-propiedades.php?task=cliente&id=<?php echo $propiedad->getIdCliente(); ?>" class="btn btn-success pull-right"><span class="es">Volver a propiedades del cliente</span><span class="pt hidden">Voltar às fazendas do cliente</span></a>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel"><span class="es">Nuevo Estudio</span><span class="pt hidden">Novo Estudo</span></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<input type="hidden" id="idPropiedad" value="<?php echo $id; ?>" />
					<input type="hidden" id="campana" value="0">
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">Tipo</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
							<select class="form-control" id="tipo">
								<option value="Safra" >Safra</option>
								<option value="Safrina" >Safrina</option>
							</select>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">Gesti&oacute;n</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
							<select class="form-control" id="gestion">
								<option value="2017">2017</option>
								<option value="2018">2018</option>
								<option value="2019">2019</option>
							</select>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12"><span class="es">Fecha</span></label>
						<div class="col-md-9 col-sm-9 col-xs-12">
							<input type="text" id="fecha" name="fecha" class="form-control datepicker">
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">Paquete</label>
						<div class="col-md-9 col-sm-9 col-xs-12"><?php  
						foreach ($arregloPaquetes as $objPaquete) {
							if($objPaquete->getEstado() == 1) {?>
							<div class="radio">
								<label class="">
									<div class="iradio_flat-green">
										<input type="radio" class="flat paquete" name="paquete" value="<?php echo $objPaquete->getIdPaquete(); ?>">
									</div> <span class="es"><?php echo utf8_decode($objPaquete->getTxtNombreEs()); ?></span><span class="pt hidden"><?php echo utf8_decode($objPaquete->getTxtNombrePt()); ?></span>
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
				<a id="enviar" href="javascript:guardarCampana();" class="btn btn-primary">Guardar</a>
			</div>
		</div>
	</div>
</div>
</div>
<?php include_once 'footer.php'; ?>
<script type="text/javascript">
	$(document).ready(function () {
		$("#listaCampanas").DataTable({
			responsive: true,
			"ordering": false,
			"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
			language: {
				"sProcessing": "Procesando...",
				"sLengthMenu": "Mostrar _MENU_",
				"sZeroRecords": "No se encontraron resultados",
				"sEmptyTable": "Ningún dato disponible en esta tabla",
				"sInfo": "Mostrando del _START_ al _END_ de un total de _TOTAL_ estudios",
				"sInfoEmpty": "Mostrando estudios del 0 al 0 de un total de 0 estudios",
				"sInfoFiltered": "(filtrado de un total de _MAX_ estudios)",
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
		$(".datepicker").daterangepicker({
			singleDatePicker: !0,
			singleClasses: "picker_4",
			"locale": {
				"format": "DD/MM/YYYY",
				"weekLabel": "S",
				"daysOfWeek": [
				"D",
				"L",
				"M",
				"X",
				"J",
				"V",
				"S"
				],
				"monthNames": [
				"Enero",
				"Febrero",
				"Marzo",
				"Abril",
				"Mayo",
				"Junio",
				"Julio",
				"Agosto",
				"Septiembre",
				"Octubre",
				"Noviembre",
				"Diciembre"
				],
				"firstDay": 1
			}
		});
	});

	function eliminarCampana(id, propiedad){
		alertify.confirm('Eliminar Campana', 'Esta seguro de eliminar este detalle? Se borraran los datos permanentemente.' 
			,function(){
				$.ajax({
					data : {task:"eliminar", campana: id},
					url: 'funciones-campana.php',
					type: 'post',
					success: function (data) {
						alertify.notify('Se elimin&oacute; la gesti&oacute;n correctamente.', 'success', 2, function () {
							window.location.href = 'propiedad-campania.php?id='+propiedad;
						});
					}
				});			
			}
			, function(){});
	}

	function agregarCampana(){
		$("#campana").val("0");
		$("#tipo").removeAttr("readonly");
		$("#gestion").removeAttr("readonly");
		$(".paquete").prop("checked", false);
		$("#exampleModal").modal();
	}

	function editar(id, tipo, gestion, fecha, paquete){
		$("#campana").val(id);
		$("#tipo option[value='"+tipo+"']").prop('selected', true);
		$("#tipo").attr("readonly","readonly");
		$("#gestion option[value='"+gestion+"']").prop('selected', true);
		$("#gestion").attr("readonly","readonly");
		$("input.paquete[value="+paquete+"]").prop("checked", true);
		$("#fecha").val(fecha);
		$("#exampleModal").modal();
		$.ajax({
			data : {task: "consulta", campana: id },
			url: 'funciones-campana.php',
			type: 'post',
			success: function (data) {
				var paquetes = JSON.parse(data);
				for (var i = 0; i < paquetes.length ; i++) {
					changeSwitchery($("input.paquetes[value="+paquetes[i]+"]"), true);
				}
				$("#campana").val(id);
				$("#tipo option[value='"+tipo+"']").prop('selected', true);
				$("#tipo").attr("readonly","readonly");
				$("#gestion option[value='"+gestion+"']").prop('selected', true);
				$("#gestion").attr("readonly","readonly");
				$("#fecha").val(fecha);
				$("#exampleModal").modal();
			}
		});
	}

	function guardarCampana() {
		$("#cancel").attr("disabled", "disabled");
		$("#enviar").addClass("hidden");
		var propiedad = $("#idPropiedad").val();
		var campana = $("#campana").val();
		var tipo = $("#tipo").val();
		var gestion = $("#gestion").val();
		var fecha = $("#fecha").val();
		var task = "insertar";
		if(campana != 0)
			task = "actualizar";
		var paquete = 0;
		$(".paquete").each(function(){
			if($(this).is(":checked"))
				paquete = $(this).val();
		});
		$.ajax({
			data : {task: task, campana: campana, tipo: tipo, gestion: gestion, fecha: fecha, propiedad: propiedad, paquete: paquete},
			url: 'funciones-campana.php',
			type: 'post',
			success: function (data) {
				if(task == "insertar"){
					alertify.notify('Se insert&oacute; la camp&ntilde;a correctamente.', 'success', 2, function () {
						window.location.href = 'propiedad-campania.php?id='+propiedad;
					});
				} else {
					alertify.notify('Se actualiz&oacute; la camp&ntilde;a correctamente.', 'success', 2, function () {
						window.location.href = 'propiedad-campania.php?id='+propiedad;
					});
				}
			}
		});	
	}
	function changeSwitchery(element, checked) {
		if ( ( element.is(':checked') && checked == false ) || ( !element.is(':checked') && checked == true ) ) {
			element.parent().find('.switchery').trigger('click');
		}
	}

	function limpiarRedirigir(idCampana){
		localStorage.setItem("actualPropiedad", "");
		localStorage.setItem("idServicioActual", "");
		localStorage.setItem("nombreServicioActual", "");
		window.location.href = "alimentacion.php?id=" + idCampana;
	}
</script>





