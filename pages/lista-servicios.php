<?php
header('Content-Type: text/html; charset=utf-8');
include_once 'header.php';

ini_set('display_errors',1); 
ini_set('display_startup_errors',1); 
error_reporting(-1);

$servicioBLL = new ServicioBLL();
$arregloServicios = $servicioBLL->selectAll();

?>
<div class="right_col" role="main">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="page-title">
				<div class="title_left">
					<h3><span class="es">Servicios <small>registrados en la plataforma</small></span><span class="pt hidden">Serviços <small>registrado na plataforma</small></span></h3>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="x_panel">
				<div class="x_title">
					<h2>Lista de Servicios</h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<table class="table table-striped table-bordered" id="listaServicios">
						<thead>
							<tr>
								<th>ID</th>
								<th><span class="es">Servicio (ES)</span><span class="pt hidden">Serviço (ES)</span></th>
								<th><span class="es">Servicio (PT)</span><span class="pt hidden">Serviço (PT)</span></th>
								<th><span class="es">Orden</span><span class="pt hidden">Ordem</span></th>
								<th>Estado</th>
								<th><span class="es">&Iacute;cono</span><spna class="pt hidden">&Iacute;cone</spna></th>
								<th><span class="es">Acciones</span><span class="pt hidden">Ações</span></th>
							</tr>
						</thead>
						<tbody><?php  
						foreach ($arregloServicios as $objServicio) {?>
						<tr>
							<td><?php echo $objServicio->getIdServicio(); ?></td>
							<td><?php echo utf8_decode($objServicio->getTxtNombreEs()); ?></td>
							<td><?php echo utf8_decode($objServicio->getTxtNombrePt()); ?></td>
							<td><?php echo $objServicio->getOrden(); ?></td>
							<td><?php echo ($objServicio->getEstado() == 1)?'Activo': 'Inactivo'; ?></td>
							<td class="bg-dark">
								<img src="<?php echo '../iconos/'.$objServicio->getIdServicio().'.png'; ?>" class="img-responsive"> 
							</td>
							<td>
								<a href="servicio.php?task=cargar&id=<?php echo $objServicio->getIdServicio(); ?>" title="Editar" style="font-size: 18px; line-height: 1;"><i class="fa fa-edit fa-fw"></i></a>
								<a href="javascript:eliminarServicio(<?php echo $objServicio->getIdServicio(); ?>);" title="Eliminar" style="font-size: 18px; line-height: 1;"><i class="fa fa-remove fa-fw"></i></a>
								<a href="javascript:cargarImagen(<?php echo $objServicio->getIdServicio(); ?>);" title="Cargar &iacute;cono" style="font-size: 18px; line-height: 1;"><i class="fa fa-image fa-fw"></i></a>
							</td>
							</tr><?php  
						}?>
					</tbody>
				</table>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-6">
				<a href="servicio.php" class="btn btn-primary pull-right"><span class="es">Nuevo Servicio</span><span class="pt hidden">Novo Serviço</span></a>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="cargaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><span class="es">Cargar &iacute;cono</span><span class="pt hidden">Carregar &iacute;cone</span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="upload" class="upload" role="form" action="cargarIcono.php" method="POST" enctype="multipart/form-data">
					<input type="hidden" id="idServicio" name="idServicio" value=""/>
					<div id="drop" class="drop">
						<span class="es">Arrastre el &iacute;cono aqu&iacute;</span><span class="pt hidden">Arraste o &iacute;cone aqui</span>
						<a>Buscar</a>
						<input type="file" name="upImage" multiple />
					</div>
					<ul></ul>
				</form>
			</div>
			<div class="modal-footer">
				<button id="cancel" type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>
</div>
<?php include_once 'footer.php'; ?>
<script type="text/javascript">
	$(document).ready(function () {
		$("#listaServicios").DataTable({
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

		var ul = $("#upload ul");
		$("#drop a").click(function(){
			$(this).parent().find("input").click();
		});
		$("#upload").fileupload({
			dropZone: $("#drop"),
			add: function (e, data) {

				var tpl = $("<li class=\"working\"><input type=\"text\" value=\"0\" data-width=\"32\" data-height=\"32\" data-fgColor=\"#0788a5\" data-readOnly=\"1\" data-bgColor=\"#3e4043\" /><p></p><span></span></li>");
				tpl.find("p").text(data.files[0].name)
				.append("<i>" + formatFileSize(data.files[0].size) + "</i>");

				data.context = tpl.appendTo(ul);

				tpl.find("input").knob();

				tpl.find("span").click(function(){

					if(tpl.hasClass("working")){
						jqXHR.abort();
					}

					tpl.fadeOut(function(){
						tpl.remove();
					});

				});
				var jqXHR = data.submit();
			},
			progress: function(e, data){
				var progress = parseInt(data.loaded / data.total * 100, 10);
				data.context.find("input").val(progress).change();

				if(progress == 100){
					data.context.removeClass("working");
					$("#cargaModal").modal('hide');
					window.location.href = "http://admin.pixadvisor.com.br/pages/lista-servicios.php";
				}
			},
			fail:function(e, data){
				data.context.addClass("error");
			}

		});

		$(document).on("drop dragover", function (e) {
			e.preventDefault();
		});
		function formatFileSize(bytes) {
			if (typeof bytes !== "number") {
				return "";
			}

			if (bytes >= 1000000000) {
				return (bytes / 1000000000).toFixed(2) + " GB";
			}

			if (bytes >= 1000000) {
				return (bytes / 1000000).toFixed(2) + " MB";
			}

			return (bytes / 1000).toFixed(2) + " KB";
		}
	});

	function eliminarServicio(id){
		alertify.confirm('Eliminar Servicio', 'Esta seguro de eliminar este servicio? Se borraran los datos permanentemente.' 
			,function(){
				$.ajax({
					data : {task:"eliminar", id: id},
					url: 'servicio.php',
					type: 'post',
					success: function (data) {
						alertify.notify('Se elimin&oacute; el servicio correctamente.', 'success', 3, function () {
							window.location.href = 'lista-servicios.php';
						});
					}
				});			
			}
			, function(){});
	}

	function cargarImagen(id) {
		$("#idServicio").val(id);
		$("#cargaModal").modal();
	}
</script>





