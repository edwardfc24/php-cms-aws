<?php
header('Content-Type: text/html; charset=utf-8');
include_once 'header.php';

ini_set('display_errors',1); 
ini_set('display_startup_errors',1); 
error_reporting(-1);


if (isset($_REQUEST['id'])) {
	$id = $_REQUEST['id'];
}

$idServicio = 0;
//Obtengo los datos de la Campana
$campanaBLL = new CampanaBLL();
$campana = $campanaBLL->selectById($id);
$nombre = $campana->getGestion();
if($campana->getTipoCampana() == "Safra")
	$nombre = $nombre.'-'.($nombre+1);
//Obtengo la propiedad
$propiedadBLL = new PropiedadBLL();
$propiedad = $propiedadBLL->selectById($campana->getIdPropiedad());
//Obtengo los servicios seleccionados
$paqueteServicioBLL = new PaqueteServicioBLL();
$arregloPaquetes = $paqueteServicioBLL->selectByPaqueteId($campana->getIdPaquete());
// Inicializo el BLL de Servicio y de Detalle
$servicioBLL = new ServicioBLL();
$detalle = new DetalleServicioBLL();
?>
<div class="right_col" role="main">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="page-title">
				<div class="title_left">
					<h3><?php echo utf8_decode($propiedad->getTxtNombre()); ?> / <span class="es">Estudio</span><span class="pt hidden">Estudo</span> <?php echo $campana->getTipoCampana().' '.$nombre; ?></h3>
				</div>
				<a href="propiedad-campania.php?&id=<?php echo $propiedad->getIdPropiedad(); ?>" class="btn btn-success pull-right"><span class="es">Volver a estudios de la propiedad</span><span class="pt hidden">Voltar às estudos do fazenda</span></a>
			</div>
		</div>
		<div class="col-md-12">
			<div class="x_panel">
				<div class="x_title">
					<h2><span class="es">Servicios del Paquete</span><span class="pt hidden">Serviços do Pacote</span></h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<div class="col-xs-4">
						<ul class="nav nav-tabs tabs-left"><?php
						$cont = 0;  
						foreach ($arregloPaquetes as $relacion) {
							if($cont == 0){
								$servicio = $servicioBLL->selectById($relacion->getIdServicio());
								$idServicio = $servicio->getIdServicio(); 
								$nombreServicio = $servicio->getTxtNombrePt();
							}
							$servicio = $servicioBLL->selectById($relacion->getIdServicio());?>
							<li id="n<?php echo $relacion->getIdServicio(); ?>" class="navegacion <?php echo ($cont == 0)? 'active':''; ?>">
								<a href="javascript:;" data-servicio="<?php echo $servicio->getIdServicio(); ?>" data-nombre="<?php echo $servicio->getTxtNombrePt(); ?>"><span class="es"><?php echo $servicio->getTxtNombreEs(); ?></span><span class="pt hidden"><?php echo $servicio->getTxtNombrePt(); ?></span></a>
								</li><?php 
								$cont++;
							} ?>
						</ul>
					</div>
					<div class="col-xs-8">
						<div class="tab-content">
							<div class="tab-pane active">
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="col-lg-12 bg-file">
										<input type="hidden" id="idServicioTemporal" value="<?php echo $idServicio; ?>">
										<input type="hidden" id="nombreServicioTemporal" value="<?php echo $nombreServicio; ?>">
										<input type="hidden" value="<?php echo $id; ?>" id="estudio"/>
										<input type="hidden" value="" id="servicio">
										<input type="hidden" id="inicial">
										<div class="filemanager">

											<div class="search">
												<input type="search" placeholder="Find a file.." />
											</div>

											<div class="breadcrumbs"></div>

											<ul class="data"></ul>

											<div class="nothingfound">
												<div class="nofiles"></div>
												<span>No hay archivos aqu&iacute;.</span>
											</div>

										</div>

										<div id="cargaArchivos" class="hidden">
											<form id="upload" role="form" action="cargaArchivos.php" method="POST" enctype="multipart/form-data" directory webkitdirectory mozdirectory>
												<input type="hidden" id="path" name="path" value="<?php echo $propiedad->getIdPropiedad().'/'.$campana->getTipoCampana().'/'.$nombre.'/'.$campana->getIdCampana().'/'; ?>">
												<input type="hidden" name="task" value="cargarImagen"/>
												<input type="hidden" id="relative" name="relative" value=""/>

												<div id="drop">
													Arrastre los archivos aqu&iacute;
													<a>Buscar</a>
													<input type="file" name="upImage" multiple />
												</div>

												<ul>
													<!-- The file uploads will be shown here -->
												</ul>
											</form>
										</div>
										<div class="botones">
											<a class="btn btn-success addFolder" href="#"><span class="es">Agregar carpeta</span><span class="pt">Adicionar pasta</span></a>
											<a class="btn btn-danger delFolder hidden" href="#"><span class="es">Eliminar carpeta</span><span class="pt">Excluir Pasta</span></a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include_once 'footer.php'; ?>
<script type="text/javascript">
	$(function(){
		if(localStorage.getItem("idServicioActual") == '' || localStorage.getItem("idServicioActual") == undefined ){
			var idServicio = $("#idServicioTemporal").val();
			var nombreServicio = $("#nombreServicioTemporal").val();
			localStorage.setItem("idServicioActual", idServicio);
			localStorage.setItem("nombreServicioActual", nombreServicio);
			$("#servicio").val(idServicio);
			var anteriorRuta = $("#path").val();
			$("#inicial").val(anteriorRuta);
			$("#path").val(anteriorRuta + nombreServicio + "/");
			$(".navegacion").removeClass("active");
			$("#n" + idServicio).addClass("active");
		} else {
			$("#servicio").val(localStorage.getItem("idServicioActual"));
			var anteriorRuta = $("#path").val();
			$("#inicial").val(anteriorRuta);
			$("#path").val(anteriorRuta + localStorage.getItem("nombreServicioActual") + "/");
			$(".navegacion").removeClass("active");
			$("#n" + localStorage.getItem("idServicioActual")).addClass("active");
		}
		var filemanager = $('.filemanager'),
		breadcrumbs = $('.breadcrumbs'),
		fileList = filemanager.find('.data');
		var id = $("#estudio").val();
		var servicio = $("#servicio").val();

		$(".delFolder").on("click", function(e){
			e.preventDefault();
			var completo = $("#path").val();
			alertify.confirm('Eliminar Carpeta', 'Esta seguro de eliminar esta carpeta y todo su contenido.', function(){ 
				$.ajax({
					method: "POST",
					url: "eliminarCarpeta.php",
					dataType:"json",
					data: { ruta: completo },
					success: function (msg) {
						if (msg['estado'] == 'success'){
							alertify.success('Se ha eliminado con éxito la carpeta');
							window.location.hash = encodeURIComponent($( ".breadcrumbs a:last" ).attr("href"));
							localStorage.setItem("actualPropiedad", $( ".breadcrumbs a:last" ).attr("href"));
							window.location.reload(false); 
						}else
						alertify.error('No se pudo eliminar la carpeta');
					}
				}); 
			},
			function(){}).set('labels', {ok:'SI', cancel:'NO'});
			return false;
		});

		$(".addFolder").on("click", function(e){
			e.preventDefault();
			var completo = $("#path").val();
			alertify.prompt("Nueva Carpeta","Ingrese el nombre de la nueva carpeta.", "",
				function(evt, value ){
					$.ajax({
						method: "POST",
						url: "nuevaCarpeta.php",
						dataType:"json",
						data: { ruta: completo, nombre: value },
						success: function (msg) {
							if (msg['estado'] == 'success'){
								alertify.success('Se ha creado con éxito la carpeta');
								localStorage.setItem("actualPropiedad", $("#path").val());
								window.location.reload(false); 
							}else
							alertify.error('No se pudo crear la carpeta');
						}
					}); 
				},
				function(){
				});
			return false;
		});

		$(".navegacion a").on("click", function(e){
			localStorage.setItem("idServicioActual", $(this).data("servicio"));
			localStorage.setItem("nombreServicioActual", $(this).data("nombre"));
			window.location.hash= "";
			window.location.reload(true);
		});

		$(document).on("click", ".files", function(e){
			e.preventDefault();
			var completo = $(this).attr("title");
			var nombre = $(this).data("real");
			alertify.confirm('Eliminar Archivo', 'Esta seguro de eliminar '+nombre, function(){ 
				$.ajax({
					method: "POST",
					url: "eliminar.php",
					dataType:"text",
					data: { ruta: completo },
					success: function (msg) {
						if (msg == 'true'){
							alertify.success('Se ha eliminado con éxito el archivo '+nombre);
							localStorage.setItem("actualPropiedad", completo);
							window.location.reload(false); 
						}else
						alertify.error('No se pudo eliminar el archivo '+nombre);
					}
				}); 
			},
			function(){}).set('labels', {ok:'SI', cancel:'NO'});
			return false;
		});

		$.get('consultaCarpetas.php?id='+ id +'&servicio='+ servicio, function(data) {
			var preview = data[0],
			response = [preview],
			currentPath = '',
			breadcrumbsUrls = [];

			var folders = [],
			files = [];

			$(window).on('hashchange', function(){
				goto(window.location.hash);
			}).trigger('hashchange');

			filemanager.find('.search').click(function(){
				var search = $(this);
				search.find('span').hide();
				search.find('input[type=search]').show().focus();
			});

			filemanager.find('input').on('input', function(e){
				folders = [];
				files = [];

				var value = this.value.trim();

				if(value.length) {
					filemanager.addClass('searching');
					window.location.hash = 'search=' + value.trim();
				}
				else {
					filemanager.removeClass('searching');
					window.location.hash = encodeURIComponent(currentPath);
				}

			}).on('keyup', function(e){
				var search = $(this);
				if(e.keyCode == 27) {
					search.trigger('focusout');
				}
			}).focusout(function(e){
				var search = $(this);
				if(!search.val().trim().length) {
					window.location.hash = encodeURIComponent(currentPath);
					search.hide();
					search.parent().find('span').show();
				}
			});

			fileList.on('click', 'li.folders', function(e){
				e.preventDefault();
				var nextDir = $(this).find('a.folders').attr('href');
				if(filemanager.hasClass('searching')) {
					breadcrumbsUrls = generateBreadcrumbs(nextDir);
					filemanager.removeClass('searching');
					filemanager.find('input[type=search]').val('').hide();
					filemanager.find('span').show();
				}
				else {
					breadcrumbsUrls.push(nextDir);
				}

				window.location.hash = encodeURIComponent(nextDir);
				currentPath = nextDir;
				var ruta = breadcrumbsUrls[breadcrumbsUrls.length - 1];
				var inicio = $("#inicial").val();
				$("#path").val(inicio + ruta + "/");
				var nombre = $("#nombrePropiedad").html();
				if(ruta == nombre){
					$(".delFolder").addClass("hidden");
				} else {
					$(".delFolder").removeClass("hidden");
				}
			});

			breadcrumbs.on('click', 'a', function(e){
				e.preventDefault();

				var index = breadcrumbs.find('a').index($(this)),
				nextDir = breadcrumbsUrls[index];

				var ruta =  breadcrumbsUrls[index];
				var inicio = $("#inicial").val();
				$("#path").val(inicio + ruta + "/");
				var nombre = $("#nombrePropiedad").html();
				if(ruta == nombre){
					$(".delFolder").addClass("hidden");
				} else {
					$(".delFolder").removeClass("hidden");
				}

				breadcrumbsUrls.length = Number(index);

				window.location.hash = encodeURIComponent(nextDir);

			});

			function goto(hash) {
				hash = decodeURIComponent(hash).slice(1).split('=');
				if (hash.length) {
					var rendered = '';
					if (hash[0] === 'search') {
						filemanager.addClass('searching');
						rendered = searchData(response, hash[1].toLowerCase());
						if (rendered.length) {
							currentPath = hash[0];
							render(rendered);
						}
						else {
							render(rendered);
						}
					}
					else if (hash[0].trim().length) {

						rendered = searchByPath(hash[0]);

						if (rendered.length) {

							currentPath = hash[0];
							var inicio = $("#inicial").val();
							$("#path").val(inicio + currentPath + "/");
							breadcrumbsUrls = generateBreadcrumbs(hash[0]);
							render(rendered);

						}
						else {
							currentPath = hash[0];
							breadcrumbsUrls = generateBreadcrumbs(hash[0]);
							render(rendered);
						}

					}
					else {
						currentPath = preview.path;
						breadcrumbsUrls.push(preview.path);
						render(searchByPath(preview.path));
					}
				}
			}

			function generateBreadcrumbs(nextDir){
				var path = nextDir.split('/').slice(0);
				for(var i=1;i<path.length;i++){
					path[i] = path[i-1]+ '/' +path[i];
				}
				return path;
			}

			function searchByPath(dir) {
				var path = dir.split('/'),
				demo = response,
				flag = 0;
				for(var i=0;i<path.length;i++){
					for(var j=0;j<demo.length;j++){
						if(demo[j].name === path[i]){
							flag = 1;
							demo = demo[j].items;
							break;
						}
					}
				}
				demo = flag ? demo : [];
				return demo;
			}

			function searchData(data, searchTerms) {
				data.forEach(function(d){
					if(d.type === 'folder') {
						searchData(d.items,searchTerms);
						if(d.name.toLowerCase().match(searchTerms)) {
							folders.push(d);
						}
					}
					else if(d.type === 'file') {
						if(d.name.toLowerCase().match(searchTerms)) {
							files.push(d);
						}
					}
				});
				return {folders: folders, files: files};
			}

			function render(data) {

				var scannedFolders = [],
				scannedFiles = [];

				if(Array.isArray(data)) {

					data.forEach(function (d) {
						if (d.type === 'folder') {
							scannedFolders.push(d);
						}
						else if (d.type === 'file') {
							scannedFiles.push(d);
						}
					});

				}
				else if(typeof data === 'object') {
					scannedFolders = data.folders;
					scannedFiles = data.files;
				}

				fileList.empty().hide();

				if(!scannedFolders.length && !scannedFiles.length) {
					filemanager.find('.nothingfound').show();
					$('#cargaArchivos').removeClass('hidden');
				}
				else {
					filemanager.find('.nothingfound').hide();
					$('#cargaArchivos').removeClass('hidden');
				}

				if(scannedFolders.length) {

					scannedFolders.forEach(function(f) {

						var itemsLength = f.items.length,
						name = escapeHTML(f.name),
						icon = '<span class="icon folder"></span>';

						if(itemsLength) {
							icon = '<span class="icon folder full"></span>';
						}

						if(itemsLength == 1) {
							itemsLength += ' item';
						}
						else if(itemsLength > 1) {
							itemsLength += ' items';
						}
						else {
							itemsLength = 'Empty';
						}

						var folder = $('<li class="folders"><a href="'+ f.path +'" title="'+ f.path +'" class="folders">'+icon+'<span class="name">' + name + '</span> <span class="details">' + itemsLength + '</span></a></li>');
						folder.appendTo(fileList);
					});

				}

				if(scannedFiles.length) {

					scannedFiles.forEach(function(f) {

						var fileSize = bytesToSize(f.size),
						name = escapeHTML(f.name),
						fileType = name.split('.'),
						icon = '<span class="icon file"></span>';

						fileType = fileType[fileType.length-1];

						icon = '<span class="icon file f-'+fileType+'">.'+fileType+'</span>';

						var file = $('<li class="files"><a href="#" title="'+ f.path +'" data-real="'+name+'" class="files">'+icon+'<span class="name">'+ name +'</span> <span class="details">'+fileSize+'</span></a></li>');
						file.appendTo(fileList);
					});
				}

				var url = '';

				if(filemanager.hasClass('searching')){
					url = '<span>Search results: </span>';
					fileList.removeClass('animated');
				}
				else {
					fileList.addClass('animated');
					breadcrumbsUrls.forEach(function (u, i) {

						var name = u.split('/');

						if (i !== breadcrumbsUrls.length - 1) {
							url += '<a href="'+u+'"><span class="folderName">' + name[name.length-1] + '</span></a> <span class="arrow">→</span> ';
						}
						else {
							url += '<span class="folderName">' + name[name.length-1] + '</span>';
						}
					});
				}

				breadcrumbs.text('').append(url);
				fileList.attr('style', 'display: inline-blok;')
			}

			function escapeHTML(text) {
				return text.replace(/\&/g,'&amp;').replace(/\</g,'&lt;').replace(/\>/g,'&gt;');
			}

			function bytesToSize(bytes) {
				var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
				if (bytes == 0) return '0 Bytes';
				var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
				return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
			}
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
		$("#relative").val(data.files[0].relativePath);
		var jqXHR = data.submit();
	},

	progress: function(e, data){
		var progress = parseInt(data.loaded / data.total * 100, 10);
		data.context.find("input").val(progress).change();

		if(progress == 100){
			data.context.removeClass("working");
		}
	},

	fail:function(e, data){
		data.context.addClass("error");
	},
	stop:function(e){
		var prop = $("#propiedad").val();
		localStorage.setItem("actualPropiedad", $("#path").val());
		window.location.reload(false); 
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
</script>





