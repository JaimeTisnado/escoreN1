<?php
// Funciones de Base de Datos

OpenConection(); // abre Conexion default


# ------------------------- FUNCIONES GENERALES -----------------------------

function fndb_mostrarMenuPanel($idUsuario){

if (!isset($idUsuario)) $idUsuario = 0;

$sSQL = "
select a.idPerfilAcceso, b.idPerfil, b.nomPerfil, c.idAcceso, c.nomAcceso, c.linkAcceso, c.parentID,
(select count(*) from accesos where parentID = c.idAcceso) registros
from perfilaccesos a
inner join perfiles b
	on b.idPerfil = a.idPerfil
inner join accesos c
	on c.idAcceso = a.idAcceso
inner join usuarios d
	on d.idPerfil = a.idPerfil
where d.idUsuario = $idUsuario
and c.parentID = 0
order by c.orden;
";

$resultado = fn_EjecutarQuery($sSQL) ;

echo '<ul class="list_main">';
echo '<li class="active"><a href="'.JPATH_BASE_WEB.DSW.'dashboard.php"><i class="icon-menu fa-home"></i>Inicio</a></li>';
while ($sRow = fn_ExtraerQuery($resultado))
{
		  $idAcceso 	= $sRow[strtolower('idAcceso')];
		  $nomAcceso 	= $sRow[strtolower('nomAcceso')];  // nombre
		  $linkAcceso	= $sRow[strtolower('linkAcceso')]; // link   
		  $registros	= $sRow[strtolower('registros')];       
		
		  if ($registros == 0) {
			  echo '<li><a href="'.JPATH_BASE_WEB.DSW.$linkAcceso.'">'.$nomAcceso.'</a>'.'</li>';
		  }else{
				
$sSQL2 = "
select a.idPerfilAcceso, b.idPerfil, b.nomPerfil, c.idAcceso, c.nomAcceso, c.linkAcceso, c.parentID
from perfilaccesos a
inner join perfiles b
	on b.idPerfil = a.idPerfil
inner join accesos c
	on c.idAcceso = a.idAcceso
inner join usuarios d
	on d.idPerfil = a.idPerfil
where d.idUsuario = $idUsuario
and c.isActivo = 1
and c.parentID = $idAcceso
order by c.orden;
";
$resultado2 = fn_EjecutarQuery($sSQL2) ;
				
				echo '<li><a href="javascript:;">'.$nomAcceso.'<i class="icon-right caret fa-caret-down"></i></a>';
				echo '	<ul class="list_main_sub">';
				while ($sRow2 = fn_ExtraerQuery($resultado2))
				{
					$idAcceso 	= $sRow2[strtolower('idAcceso')];
					$nomAcceso 	= $sRow2[strtolower('nomAcceso')];  // nombre
					$linkAcceso	= $sRow2[strtolower('linkAcceso')]; // link 
				
					echo '<li><a href="'.JPATH_BASE_WEB.DSW.$linkAcceso.'"><i class="icon-menu-sub fa-angle-right"></i>'.$nomAcceso.'</a>'.'</li>';
				} // fin while segundo
				echo '	</ul>';
				echo '</li>';
		}// fin if
			
} // fin while inicial
echo '</ul>';

} // fin mostrarMenuPanel


# ------------------------- FUNCIONES GENERALES ------------------------------

function fndb_MostrarNoEncontrado(){

echo '
<div id="header_mensaje">
		<div class="container">
			<div class="titulo_content">';
echo '<h1>Error - Página no encontrada</h1>';
echo '		</div> <!-- welcome_mensaje -->';
echo '</div><!-- container -->
</div><!-- header_mensaje -->';

echo '
<div class="container">
<div class="content">';
echo '<p>La página que intenta llegar no existe, o se ha movido. Por favor, use el menú para acceder a lo que busca.</p>';
echo '
</div><!-- content -->
</div><!-- container -->';

}


# ------------------------- FUNCIONES BASE DE DATOS -----------------------------


// ---------------<-  S E G U R I D A D   ->---------------
// ---------------<- perfiles  ->---------------

function fndb_nuevoPerfil($IdUsuario, $nomPerfil, $isActivo){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("insert into perfiles (nomPerfil,
											 fechaCreacion,
											 isActivo
											)
					
					values ('$nomPerfil',
							'$fechaHora',
							'$isActivo'
							)
				");
				

return 1;

}


// editar
function fndb_editarPerfil($IdUsuario, $idPerfil, $nomPerfil, $isActivo){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("update perfiles set nomPerfil = '$nomPerfil',
										   fechaCreacion = '$fechaHora',
										   isActivo = '$isActivo'
																
					where idPerfil = $idPerfil
				");
				

return 1;

}

// informacion por Id
function fndb_getPerfilbyId($Id) {

global $sArray;

$sQL = fn_EjecutarQuery("select a.idPerfil, a.nomPerfil, a.isActivo
				  from perfiles a
				  where a.idPerfil = $Id
				  ");

$sArray = fn_ExtraerQuery($sQL);
return $sArray;

} // fin funcion


// ---------------<- perfiles  ->---------------



// ---------------<- usuarios  ->---------------

function fndb_loginUsuario($nickUsuario, $passUsuario){
global $sArray;

$sQL = fn_EjecutarQuery("
select a.idUsuario, a.nomUsuario, a.nickUsuario, a.isActivo, b.idPerfil, b.nomPerfil,
i.idItem, i.nomItem
from usuarios a
inner join perfiles b
	on a.idPerfil = b.idPerfil
inner join items i
	on i.idItem = a.idItem
where a.nickUsuario = '$nickUsuario'
and a.passUsuario = '$passUsuario'
");

$sArray = fn_ExtraerQuery($sQL);				  
return $sArray;

}

function fndb_nuevoUsuario($idPerfil, $idItem, $nomUsuario, $nickUsuario, $passUsuario, $isActivo){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("insert into usuarios (idPerfil,
											    idItem,
												nomUsuario,
												nickUsuario,
												passUsuario,
												fechaCreacion,
												isActivo
												)
					
					values ('$idPerfil',
							'$idItem',
							'$nomUsuario',
							'$nickUsuario',
							'$passUsuario',
							'$fechaHora',
							'$isActivo'
							)
				");
return 1;

}


// editar
function fndb_editarUsuario($idUsuario, $idPerfil, $idItem, $nomUsuario, $nickUsuario, $passUsuario, $isActivo){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("update usuarios set idPerfil = '$idPerfil',
												idItem = '$idItem',
												nomUsuario = '$nomUsuario',
												nickUsuario = '$nickUsuario',
												passUsuario = '$passUsuario',
												fechaCreacion = '$fechaHora',
												isActivo = '$isActivo'
																
					where idUsuario = $idUsuario
					
				");
return 1;
}

function fndb_editarLoginUsuario($idUsuario, $lastIP){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("
update usuarios set lastLogin = '$fechaHora',
lastIP = '$lastIP'					
where idUsuario = $idUsuario			
");
return 1;

}

// informacion por Id
function fndb_getUsuariobyId($Id) {

global $sArray;

$sQL = fn_EjecutarQuery("
select u.idUsuario, u.nomUsuario, u.nickUsuario, u.passUsuario, u.lastLogin, u.lastIP, u.lastLogin, u.isActivo, p.idPerfil, p.nomPerfil,
i.idItem, i.nomItem, c.idCategoria, c.nomCategoria, g.idGrado, g.nomGrado
from usuarios u
inner join perfiles p
	on p.idPerfil = u.idPerfil
inner join items i
	on i.idItem = u.idItem
inner join categorias c
	on c.idCategoria = i.idCategoria
inner join grados g
	on g.idGrado = i.idGrado
where u.idUsuario = $Id
");

$sArray = fn_ExtraerQuery($sQL);
return $sArray;

} // fin funcion

function fndb_cambiarPassword($idUsuario, $passUsuario){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("
update usuarios set passUsuario = '$passUsuario'
where idUsuario = $idUsuario
");

return 1;

}


function fndb_existeUsuario($nickUsuario){

$sQL = fn_EjecutarQuery("
select count(*) registros
from usuarios
where nickUsuario = '$nickUsuario';
");
$rOW = fn_ExtraerQuery($sQL);
return $rOW['registros'];

}

// ---------------<- usuarios  ->---------------



// ---------------<- accesos  ->---------------


// funcion obtener el numero siguiente de orden
function fndb_getOrdenAccesos (){
$sQL = fn_EjecutarQuery("select max(orden) orden 
					from accesos
					");
$rOW = fn_ExtraerQuery($sQL);
$orden = $rOW['orden'] +1;

return $orden;

}

// funcion para actualizar le numero de orden
function fndb_updateOrdenAccesos ($id, $original, $nuevo) {


$sQL = fn_EjecutarQuery("update accesos set orden = '$nuevo'
		  			where orden = '$original'
					and idAcceso = $id
					");

$sQL = fn_EjecutarQuery("update accesos set orden = '$original'
		  			where orden = '$nuevo'
					and idAcceso <> $id
					");
				  
					
}


function fndb_nuevoAcceso ($nomAcceso, $linkAcceso, $parentID, $isActivo){

$fechaHora	= fn_getFechaHoraDefault();
$orden 		= fndb_getOrdenAccesos();

$sQL = fn_EjecutarQuery("insert into accesos (nomAcceso,
											linkAcceso,
											orden,
											parentID,
											fechaCreacion,
											isActivo
											)
					
					values ('$nomAcceso',
							'$linkAcceso',
							'$orden',
							'$parentID',
							'$fechaHora',
							'$isActivo'
							)
				");
				
				
return 1;

}

// editar
function fndb_editarAcceso ($idAcceso, $nomAcceso, $linkAcceso, $parentID, $isActivo){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("update accesos set 	nomAcceso = '$nomAcceso',
											linkAcceso = '$linkAcceso',
											parentID = '$parentID',
											fechaCreacion = '$fechaHora',
											isActivo = '$isActivo'
														
					where idAcceso = $idAcceso
					
				");
				
					
return 1;

}

// informacion por Id
function fndb_getAccesobyId($Id) {

global $sArray;

$sQL = fn_EjecutarQuery("select a.idAcceso, a.nomAcceso, a.linkAcceso, a.orden, a.parentID, a.isActivo
				  from accesos a
				  where a.idAcceso = $Id
				  ");

$sArray = fn_ExtraerQuery($sQL);
return $sArray;

} // fin funcion


// ---------------<- accesos  ->---------------


// ---------------<- perfilaccesos  ->---------------

function fndb_nuevoPerfilAcceso($idPerfil, $idAcceso){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("insert into perfilaccesos (idAcceso,
												idPerfil
												)
					
					values ('$idAcceso',
							'$idPerfil'
							)
				") ;
				

return 1;

}

function fndb_deletePerfilAcceso($Id){

$sQL = fn_EjecutarQuery("delete from perfilaccesos
					where idPerfilAcceso = $Id
				") ;
				

return 1;
	
} // fin funcion fndb_deletePerfilAcceso


// ---------------<- perfilaccesos ->---------------
// ---------------<-  S E G U R I D A D   ->---------------


// ---------------<- rubricas ->---------------

function fndb_existeRubricaItem($idItem){

$sQL = fn_EjecutarQuery("
select count(*) registros
from rubricas
where idItem = '$idItem'
");
$rOW = fn_ExtraerQuery($sQL);
return $rOW['registros'];

}

function fndb_nuevaRubrica($idUsuario, $idItem, $nomRubrica, $memoRubrica, $rutaRubrica, $isActivo){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("insert into rubricas (idItem,
											 nomRubrica,
											 memoRubrica,
											 rutaRubrica,
											 fechaCreacion,
											 isActivo
											)
					
					values ('$idItem',
							'$nomRubrica',
							'$memoRubrica',
							'$rutaRubrica',
							'$fechaHora',
							'$isActivo'
							)
				");
				

return 1;

}


// editar
function fndb_editarRubrica($idUsuario, $idRubrica, $idItem, $nomRubrica, $memoRubrica, $rutaRubrica, $isActivo){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("update rubricas set idItem = '$idItem',
											nomRubrica = '$nomRubrica',
											memoRubrica = '$memoRubrica',
											rutaRubrica = '$rutaRubrica',
										   fechaCreacion = '$fechaHora',
										   isActivo = '$isActivo'
																
					where idRubrica = $idRubrica
				");
				

return 1;

}

// informacion por Id
function fndb_getRubricabyId($Id) {

global $sArray;

$sQL = fn_EjecutarQuery("select r.idRubrica, r.nomRubrica, r.memoRubrica, r.rutaRubrica, r.isActivo, i.idItem, i.nomItem
					from rubricas r
					inner join items i
					on i.idItem = r.idItem
				  	where r.idRubrica = $Id
				  ");

$sArray = fn_ExtraerQuery($sQL);
return $sArray;

} // fin funcion

// informacion por Id
function fndb_getRubricabyIdItem($Id) {

global $sArray;

$sQL = fn_EjecutarQuery("select r.idRubrica, r.nomRubrica, r.memoRubrica, r.rutaRubrica, r.isActivo, i.idItem, i.nomItem
					from rubricas r
					inner join items i
					on i.idItem = r.idItem
				  	where r.idItem = $Id
				  ");

$sArray = fn_ExtraerQuery($sQL);
return $sArray;

} // fin funcion

// ---------------<- rubricas  ->---------------


// ---------------<- categorias ->---------------

function fndb_existeCategoria($iCodigo){

$sQL = fn_EjecutarQuery("
select count(*) registros
from categorias
where codigoCategoria = '$iCodigo'
");
$rOW = fn_ExtraerQuery($sQL);
return $rOW['registros'];

}

function fndb_nuevaCategoria($idUsuario, $nomCategoria, $codigoCategoria, $memoCategoria,  $isActivo){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("insert into categorias (nomCategoria,
											 memoCategoria,
											 codigoCategoria,
											 fechaCreacion,
											 isActivo
											)
					
					values ('$nomCategoria',
							'$memoCategoria',
							'$codigoCategoria',
							'$fechaHora',
							'$isActivo'
							)
				");
				

return 1;

}


// editar
function fndb_editarCategoria($idUsuario, $idCategoria, $nomCategoria, $codigoCategoria, $memoCategoria,  $isActivo){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("update categorias set nomCategoria = '$nomCategoria',
											memoCategoria = '$memoCategoria',
											codigoCategoria = '$codigoCategoria',
										   fechaCreacion = '$fechaHora',
										   isActivo = '$isActivo'
																
					where idCategoria = $idCategoria
				");
				

return 1;

}

// informacion por Id
function fndb_getCategoriabyId($Id) {

global $sArray;

$sQL = fn_EjecutarQuery("select a.idCategoria, a.nomCategoria, a.memoCategoria, a.codigoCategoria, a.isActivo
				  from categorias a
				  where a.idCategoria = $Id
				  ");

$sArray = fn_ExtraerQuery($sQL);
return $sArray;

} // fin funcion


// ---------------<- categorias  ->---------------


// ---------------<- grados ->---------------

function fndb_existeGrado($iCodigo){

$sQL = fn_EjecutarQuery("
select count(*) registros
from grados
where codigoGrado = '$iCodigo'
");
$rOW = fn_ExtraerQuery($sQL);
return $rOW['registros'];

}

function fndb_nuevoGrado($idUsuario, $nomGrado, $codigoGrado, $memoGrado, $isActivo){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("insert into grados (nomGrado,
											 codigoGrado,
											 memoGrado,
											 fechaCreacion,
											 isActivo
											)
					
					values ('$nomGrado',
							'$codigoGrado',
							'$memoGrado',
							'$fechaHora',
							'$isActivo'
							)
				");
				

return 1;

}


// editar
function fndb_editarGrado($idUsuario, $idGrado, $nomGrado, $codigoGrado, $memoGrado, $isActivo){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("update grados set nomGrado = '$nomGrado',
											codigoGrado = '$codigoGrado',
											memoGrado = '$memoGrado',
										   fechaCreacion = '$fechaHora',
										   isActivo = '$isActivo'
																
					where idGrado = $idGrado
				");
				

return 1;

}

// informacion por Id
function fndb_getGradobyId($Id) {

global $sArray;

$sQL = fn_EjecutarQuery("select a.idGrado, a.nomGrado, a.codigoGrado, a.memoGrado, a.isActivo
				  from grados a
				  where a.idGrado = $Id
				  ");

$sArray = fn_ExtraerQuery($sQL);
return $sArray;

} // fin funcion


// ---------------<- grados  ->---------------


// ---------------<- paises ->---------------

function fndb_existePais($iCodigo){

$sQL = fn_EjecutarQuery("
select count(*) registros
from paises
where codigoPais = '$iCodigo'
");
$rOW = fn_ExtraerQuery($sQL);
return $rOW['registros'];

}

function fndb_nuevoPais($idUsuario, $nomPais, $codigoPais, $isActivo){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("insert into paises (nomPais,
											 codigoPais,
											 fechaCreacion,
											 isActivo
											)
					
					values ('$nomPais',
							'$codigoPais',
							'$fechaHora',
							'$isActivo'
							)
				");
				

return 1;

}


// editar
function fndb_editarPais($idUsuario, $idPais, $nomPais, $codigoPais, $isActivo){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("update paises set nomPais = '$nomPais',
										   codigoPais = '$codigoPais',
										   fechaCreacion = '$fechaHora',
										   isActivo = '$isActivo'
																
					where idPais = $idPais
				");
				

return 1;

}

// informacion por Id
function fndb_getPaisbyId($Id) {

global $sArray;

$sQL = fn_EjecutarQuery("select a.idPais, a.nomPais, a.codigoPais, a.isActivo
				  from paises a
				  where a.idPais = $Id
				  ");

$sArray = fn_ExtraerQuery($sQL);

return $sArray;
} // fin funcion


function fndb_getPaisbyCodigo($iCodigo) {

global $sArray;

$sQL = fn_EjecutarQuery("
select a.idPais, a.nomPais, a.codigoPais, a.isActivo
from paises a
where a.codigoPais = '$iCodigo'
");

$sArray = fn_ExtraerQuery($sQL);
return $sArray;

} // fin funcion

// ---------------<- paises  ->---------------



// ---------------<- departamentos ->---------------

function fndb_existeDepartamento($iCodigo){

$sQL = fn_EjecutarQuery("
select count(*) registros
from departamentos
where codigoDepartamento = '$iCodigo'
");
$rOW = fn_ExtraerQuery($sQL);
return $rOW['registros'];

}

function fndb_nuevoDepartamento($idUsuario, $idPais, $nomDepartamento, $codigoDepartamento, $isActivo){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("insert into departamentos (idPais,
											 nomDepartamento,
											 codigoDepartamento,
											 fechaCreacion,
											 isActivo
											)
					
					values ('$idPais',
							'$nomDepartamento',
							'$codigoDepartamento',
							'$fechaHora',
							'$isActivo'
							)
				");
				

return 1;

}


// editar
function fndb_editarDepartamento($idUsuario, $idPais, $idDepartamento, $nomDepartamento, $codigoDepartamento, $isActivo){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("update departamentos set nomDepartamento = '$nomDepartamento',
										   codigoDepartamento = '$codigoDepartamento',
										   fechaCreacion = '$fechaHora',
										   isActivo = '$isActivo'
																
					where idDepartamento = $idDepartamento
				");
				

return 1;

}

// informacion por Id
function fndb_getDepartamentobyId($Id) {

global $sArray;

$sQL = fn_EjecutarQuery("
select b.idPais, b.nomPais, a.idDepartamento, a.nomDepartamento, a.codigoDepartamento, a.isActivo
from departamentos a
inner join paises b
	on b.idPais = a.idPais
where a.idDepartamento = $Id
");

$sArray = fn_ExtraerQuery($sQL);
return $sArray;

} // fin funcion


function fndb_getDepartamentobyCodigo($iCodigo) {

global $sArray;

$sQL = fn_EjecutarQuery("
select b.idPais, b.nomPais, a.idDepartamento, a.nomDepartamento, a.codigoDepartamento, a.isActivo
from departamentos a
inner join paises b
	on b.idPais = a.idPais
where a.codigoDepartamento = '$iCodigo'
");

$sArray = fn_ExtraerQuery($sQL);
return $sArray;

} // fin funcion

// ---------------<- departamentos ->---------------



// ---------------<- municipios ->---------------

function fndb_existeMunicipio($iCodDepto,$iCodMunicipio){

$sQL = fn_EjecutarQuery("
select count(*) registros
from municipios m
inner join departamentos d
	on d.idDepartamento = m.idDepartamento
where d.codigoDepartamento = '$iCodDepto'
and m.codigoMunicipio = '$iCodMunicipio'
");
$rOW = fn_ExtraerQuery($sQL);
return $rOW['registros'];

}

function fndb_nuevoMunicipio($idUsuario, $idDepartamento, $nomMunicipio, $codigoMunicipio, $isActivo){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("insert into municipios (idDepartamento,
											 nomMunicipio,
											 codigoMunicipio,
											 fechaCreacion,
											 isActivo
											)
					
					values ('$idDepartamento',
							'$nomMunicipio',
							'$codigoMunicipio',
							'$fechaHora',
							'$isActivo'
							)
				");
				

return 1;

}


// editar
function fndb_editarMunicipio($idUsuario, $idDepartamento, $idMunicipio, $nomMunicipio, $codigoMunicipio, $isActivo){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("update municipios set nomMunicipio = '$nomMunicipio',
										   codigoMunicipio = '$codigoMunicipio',
										   fechaCreacion = '$fechaHora',
										   isActivo = '$isActivo'
																
					where idMunicipio = $idMunicipio
				");
				

return 1;

}

// informacion por Id
function fndb_getMunicipiobyId($Id) {

global $sArray;

$sQL = fn_EjecutarQuery("
select p.idPais, p.nomPais, d.idDepartamento, d.nomDepartamento, m.idMunicipio, m.nomMunicipio, m.codigoMunicipio, m.isActivo
from municipios m
inner join departamentos d
	on d.idDepartamento = m.idDepartamento
inner join paises p
	on p.idPais = d.idPais
where m.idMunicipio = $Id
");

$sArray = fn_ExtraerQuery($sQL);
return $sArray;

} // fin funcion


function fndb_getMunicipiobyCodigo($iCodigo) {

global $sArray;

$sQL = fn_EjecutarQuery("
select p.idPais, p.nomPais, d.idDepartamento, d.nomDepartamento, m.idMunicipio, m.nomMunicipio, m.codigoMunicipio, m.isActivo
from municipios m
inner join departamentos d
	on d.idDepartamento = m.idDepartamento
inner join paises p
	on p.idPais = d.idPais
where m.codigoMunicipio = '$iCodigo'
");

$sArray = fn_ExtraerQuery($sQL);
return $sArray;

} // fin funcion


function fndb_getMunicipiobyCodigoDeptoMuni($iCodDepto,$iCodMunicipio) {

global $sArray;

$sQL = fn_EjecutarQuery("
select p.idPais, p.nomPais, d.idDepartamento, d.nomDepartamento, m.idMunicipio, m.nomMunicipio, m.codigoMunicipio, m.isActivo
from municipios m
inner join departamentos d
	on d.idDepartamento = m.idDepartamento
inner join paises p
	on p.idPais = d.idPais
where d.codigoDepartamento = '$iCodDepto' 
and m.codigoMunicipio = '$iCodMunicipio'
");

$sArray = fn_ExtraerQuery($sQL);
return $sArray;

} // fin funcion

// ---------------<- municipios ->---------------


// ---------------<- centroseducativos ->---------------

function fndb_existeCentroEducativo($iCodigo){

$sQL = fn_EjecutarQuery("
select count(*) registros
from centroseducativos
where codigoCentro = '$iCodigo'
");
$rOW = fn_ExtraerQuery($sQL);
return $rOW['registros'];

}

function fndb_nuevoCentroEducativo($idUsuario, $idMunicipio, $nomCentro, $codigoCentro, $isActivo){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("insert into centroseducativos (idMunicipio,
											 nomCentro,
											 codigoCentro,
											 fechaCreacion,
											 isActivo
											)
					
					values ('$idMunicipio',
							'$nomCentro',
							'$codigoCentro',
							'$fechaHora',
							'$isActivo'
							)
				");
				

return 1;

}


// editar
function fndb_editarCentroEducativo($idUsuario, $idCentroEducativo, $idMunicipio, $nomCentro, $codigoCentro, $isActivo){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("update centroseducativos set idMunicipio = '$idMunicipio',
										   nomCentro = '$nomCentro',
										   codigoCentro = '$codigoCentro',
										   fechaCreacion = '$fechaHora',
										   isActivo = '$isActivo'
																
					where idCentroEducativo = $idCentroEducativo
				");
				

return 1;

}

// informacion por Id
function fndb_getCentroEducativobyId($Id) {

global $sArray;

$sQL = fn_EjecutarQuery("
select p.idPais, p.nomPais, d.idDepartamento, d.nomDepartamento, m.idMunicipio, m.nomMunicipio, c.idCentroEducativo,
c.nomCentro, c.codigoCentro, c.isActivo
from centroseducativos c
inner join municipios m
	on m.idMunicipio = c.idMunicipio
inner join departamentos d
	on d.idDepartamento = m.idDepartamento
inner join paises p
	on p.idPais = d.idPais
where c.idCentroEducativo = $Id
");

$sArray = fn_ExtraerQuery($sQL);
return $sArray;

} // fin funcion

function fndb_getCentroEducativobyCodigo($iCodigo) {

global $sArray;

$sQL = fn_EjecutarQuery("
select p.idPais, p.nomPais, d.idDepartamento, d.nomDepartamento, m.idMunicipio, m.nomMunicipio, c.idCentroEducativo,
c.nomCentro, c.codigoCentro, c.isActivo
from centroseducativos c
inner join municipios m
	on m.idMunicipio = c.idMunicipio
inner join departamentos d
	on d.idDepartamento = m.idDepartamento
inner join paises p
	on p.idPais = d.idPais
where c.codigoCentro = '$iCodigo'
");

$sArray = fn_ExtraerQuery($sQL);
return $sArray;

} // fin funcion

// ---------------<- centroseducativos ->---------------


// ---------------<- items ->---------------

function fndb_existeItem($idCategoria, $idGrado, $iCodigo){

$sQL = fn_EjecutarQuery("
select count(*) registros
from items
where idCategoria = '$idCategoria'
and idGrado = '$idGrado'
and codigoItem = '$iCodigo'
");
$rOW = fn_ExtraerQuery($sQL);
return $rOW['registros'];

}


function fndb_nuevoItem($idUsuario, $idCategoria, $idGrado, $nomItem, $codigoItem, $memoItem, $maxRevisiones, $isActivo){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("insert into items (idCategoria,
											 idGrado,
											 nomItem,
											 codigoItem,
											 memoItem,
											 maxRevisiones,
											 fechaCreacion,
											 isActivo
											)
					
					values ('$idCategoria',
							'$idGrado',
							'$nomItem',
							'$codigoItem',
							'$memoItem',
							'$maxRevisiones',
							'$fechaHora',
							'$isActivo'
							)
				");
				

return 1;

}


// editar
function fndb_editarItem($idUsuario, $idItem, $idCategoria, $idGrado, $nomItem, $codigoItem, $memoItem, $maxRevisiones, $isActivo){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("update items set idCategoria = '$idCategoria',
										   idGrado = '$idGrado',
										   nomItem = '$nomItem',
										   codigoItem = '$codigoItem',
										   memoItem = '$memoItem',
										   maxRevisiones = '$maxRevisiones',
										   fechaCreacion = '$fechaHora',
										   isActivo = '$isActivo'
																
					where idItem = $idItem
				");
				

return 1;

}

// informacion por Id
function fndb_getItembyId($Id) {

global $sArray;

$sQL = fn_EjecutarQuery("
select c.idCategoria, c.nomCategoria, g.idGrado, g.nomGrado, i.idItem, i.nomItem, i.codigoItem, 
i.memoItem, i.maxRevisiones, i.isActivo
from items i
inner join categorias c
	on c.idCategoria = i.idCategoria
inner join grados g
	on g.idGrado = i.idGrado
where i.idItem = $Id
");

$sArray = fn_ExtraerQuery($sQL);
return $sArray;

} // fin funcion


function fndb_getItembyCodigo($iCodCategoria, $iCodGrado, $iCodItem) {

global $sArray;

$sQL = fn_EjecutarQuery("
select c.idCategoria, c.nomCategoria, g.idGrado, g.nomGrado, i.idItem, i.nomItem, i.codigoItem, i.memoItem, i.isActivo
from items i
inner join categorias c
	on c.idCategoria = i.idCategoria
inner join grados g
	on g.idGrado = i.idGrado
where c.codigoCategoria = '$iCodCategoria'
and g.codigoGrado = '$iCodGrado'
and i.codigoItem = '$iCodItem'
");

$sArray = fn_ExtraerQuery($sQL);
return $sArray;

} // fin funcion

// ---------------<- items ->---------------


// ---------------<- configuraciones ->---------------

// editar
function fndb_editarConfiguracion($idUsuario, $idConfiguracion, $nomConfiguracion, $valorConfiguracion){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("update configuraciones set nomConfiguracion = '$nomConfiguracion',
										   valorConfiguracion = '$valorConfiguracion'
																
					where idConfiguracion = $idConfiguracion
				");
return 1;

}

// informacion por Id
function fndb_getConfiguracionbyId($Id) {

global $sArray;

$sQL = fn_EjecutarQuery("select a.idConfiguracion, a.nomConfiguracion, a.valorConfiguracion
				  from configuraciones a
				  where a.idConfiguracion = $Id
				  ");

$sArray = fn_ExtraerQuery($sQL);

return $sArray;
} // fin funcion


// ---------------<- configuraciones  ->---------------



// ---------------<- mensajes  ->---------------

function fndb_nuevoMensaje($idUsuario, $idFrom, $idTo, $asunto, $mensaje){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("insert into mensajes (fromMensaje,
											 toMensaje,
											 asunto,
											 mensaje,
											 fechaMensaje,
											 isLeido,
											 isEliminado
											)
					
					values ('$idFrom',
							'$idTo',
							'$asunto',
							'$mensaje',
							'$fechaHora',
							'0',
							'0'
							)
				");
				
return 1;

}

function fndb_obtenerDatosCalificacionesMensaje($idImagen){
$datos = '';

$sSQL = fn_EjecutarQuery("
select si.anioLectivo, si.idImagen, u.nomUsuario, si.calificacion
from scoreitems si
inner join usuarios u
on u.idUsuario = si.idUsuario
where idImagen = $idImagen;
");

while ($sRow = fn_ExtraerQuery($sSQL))
{
	$anioLectivo 	= $sRow[strtolower('anioLectivo')];
  	$idImagen 		= $sRow[strtolower('idImagen')]; 
  	$nomUsuario		= $sRow[strtolower('nomUsuario')]; 
  	$calificacion	= $sRow[strtolower('calificacion')];
	
	$datos .= '<b>'.$nomUsuario.':</b> '.$calificacion.'<br/>';
}

return $datos;
}

function fndb_enviarMensajeSupervisorItem($fromMensaje, $idItem, $IDItemImagen, $idImagen){

$fechaHora	= fn_getFechaHoraDefault();

$sSQL = fn_EjecutarQuery("
select u.idUsuario, u.nomUsuario, p.idPerfil, p.nomPerfil
from usuarios u
inner join perfiles p
	on p.idPerfil = u.idPerfil
inner join items i
	on i.idItem = u.idItem
	and i.idItem = $idItem
where u.isActivo = '1'
and u.idPerfil = '3'
order by u.idUsuario;
");
while ($sRow = fn_ExtraerQuery($sSQL))
{
	$idUsuario 	= $sRow[strtolower('idUsuario')];
  	$nomUsuario = $sRow[strtolower('nomUsuario')]; 
  	$idPerfil	= $sRow[strtolower('idPerfil')]; 
  	$nomPerfil	= $sRow[strtolower('nomPerfil')]; 
	
	$mensaje	= '<p><b>Estimado Supervisor</b></p><p>Se le notifica que se ha enviado el item '.$IDItemImagen.' para realizar
	puntuación.</p>'.fndb_obtenerDatosCalificacionesMensaje($idImagen);
	fndb_nuevoMensaje(0, $fromMensaje, $idUsuario, 'Revisión de Item '.$IDItemImagen, $mensaje);
		  
}

return 0;

}


function fndb_editarMensajeLeido($idMensaje){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("
update mensajes set isLeido = '1'
where idMensaje = $idMensaje
");
return 1;

}

function fndb_eliminarMensaje($idMensaje){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("
update mensajes set isEliminado = '1'
where idMensaje = $idMensaje
");
return 1;

}

function fndb_getMensajebyId($Id) {

$idUsuario			= $_SESSION[_NameSession_idUser];
global $sArray;

$sQL = fn_EjecutarQuery("
select m.idMensaje, m.fromMensaje, uf.nomUsuario remitente, m.toMensaje, m.asunto, m.mensaje, m.fechaMensaje, m.isLeido, m.isEliminado
from mensajes m
inner join usuarios uf
	on uf.idUsuario = m.fromMensaje
where m.idMensaje = $Id
and m.toMensaje = $idUsuario
");

$sArray = fn_ExtraerQuery($sQL);

return $sArray;
} // fin funcion


function fndb_getMensajesUsuario() {

$idUsuario	= $_SESSION[_NameSession_idUser];
global $sArray;

$sQL = fn_EjecutarQuery("
select m.idMensaje, m.fromMensaje, uf.nomUsuario remitente, m.toMensaje, m.asunto, m.mensaje, m.fechaMensaje, m.isLeido, m.isEliminado
from mensajes m
inner join usuarios uf
	on uf.idUsuario = m.fromMensaje
where m.toMensaje = $idUsuario
");

$sArray = fn_ExtraerQuery($sQL);

return $sArray;
} // fin funcion


function fndb_getMensajesNoLeidos() {

$idUsuario	= $_SESSION[_NameSession_idUser];
global $sArray;

$sQL = fn_EjecutarQuery("
select a.idMensaje, a.fromMensaje, a.toMensaje, a.asunto, a.mensaje, a.fechaMensaje, a.isLeido, a.isEliminado
from mensajes a
where a.toMensaje = $idUsuario
and a.isLeido = 0
");

$sArray = fn_ExtraerQuery($sQL);

return $sArray;
} // fin funcion


function fndb_getMensajesNoLeidosTop() {

$idUsuario	= $_SESSION[_NameSession_idUser];
global $sArray;

$sQL = fn_EjecutarQuery("
select a.idMensaje, a.fromMensaje, uf.nomUsuario remitente, a.toMensaje, a.asunto, a.mensaje, a.fechaMensaje, a.isLeido, a.isEliminado
from mensajes a
inner join usuarios uf
	on uf.idUsuario = a.fromMensaje
where a.toMensaje = $idUsuario
and a.isLeido = '0'
order by a.fechaMensaje desc
limit 3 offset 0
");
$nMensajes = fn_NumeroRegistros($sQL);

	if ($nMensajes != 0){
		echo '<li><a href="javascript:;"><span class="msg">'.$nMensajes.'</span> Mensajes<i class="icon-right fa-caret-down"></i></a>';
		echo '<ul class="list_top_sub">';
		while ($sRow = fn_ExtraerQuery($sQL) ){
			$idMensaje 		= $sRow[strtolower('idMensaje')];
			$asunto 		= $sRow[strtolower('asunto')];
			$remitente 		= $sRow[strtolower('remitente')];
			
			$fechaMensaje 	= $sRow[strtolower('fechaMensaje')];
			$fechaHora	= fn_getFechaHoraDefault();
			$horasDif = fn_getDiferenciaEntreFechas($fechaHora,$fechaMensaje,'HORAS',true).' hrs';
			
			echo '<li><a href="'.JPATH_BASE_WEB.DSW.'mod.mensajes/leer_Mensaje.php?id='.$idMensaje.'">
				<div class="mensaje">
					<span class="header">
					<span class="remitente"><i class="icon-fa fa-caret-right"></i>'.$remitente.'</span>
					<span class="hora">'.$horasDif.'</span>
					</span>
					<span class="asunto">'.$asunto.'</span>
				</div>
				</a>
			</li><li class="dividerMensaje"></li>';
		}
		echo '<li><a href="'.JPATH_BASE_WEB.DSW.'mod.mensajes/">Todos los mensajes</a></li>
		</ul>';
	}else {
		echo '<li><a href="javascript:;"><span class="msg" id="msg">'.$nMensajes.'</span> Mensajes</a></li>';
	}

} // fin funcion

// ---------------<- mensajes  ->---------------


// ---------------<- imagenesitems  ->---------------

function fndb_existeImagen($anioLectivo, $idCentroEducativo, $idItem, $nomImagen){

$sQL = fn_EjecutarQuery("
select count(*) registros
from imagenesitems
where anioLectivo = '$anioLectivo'
and idCentroEducativo = '$idCentroEducativo'
and idItem = '$idItem'
and nomImagen = '$nomImagen'
");
$rOW = fn_ExtraerQuery($sQL);
return $rOW['registros'];

}

function fndb_nuevaImagen($idUsuario, $anioLectivo, $idCentroEducativo, $idItem, $nomImagen){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("insert into imagenesitems (anioLectivo,
											 idCentroEducativo,
											 idItem,
											 nomImagen,
											 fechaCarga,
											 flagAnclaje,
											 flagCalificacion,
											 flagRevisado,
											 calificacionFinal
											)
					
					values ('$anioLectivo',
							'$idCentroEducativo',
							'$idItem',
							'$nomImagen',
							'$fechaHora',
							'0',
							'0',
							'0',
							'0'
							)
				");
				
return 1;

}


function fndb_getImagenItembyId($idImagen) {

#global $sArray;

$sQL = fn_EjecutarQuery("
select im.idImagen, im.anioLectivo, im.nomImagen, im.flagAnclaje, im.flagCalificacion, im.flagRevisado,
im.calificacionFinal, im.idCentroEducativo, ce.nomCentro, im.idItem, i.nomItem,
i.idCategoria, c.nomCategoria, c.codigoCategoria, i.idGrado, g.nomGrado, g.codigoGrado
from imagenesitems  im
inner join items i
	on i.idItem = im.idItem
inner join categorias c
	on c.idCategoria = i.idCategoria
inner join grados g
	on g.idGrado = i.idGrado
inner join centroseducativos ce
	on ce.idCentroEducativo = im.idCentroEducativo
where im.idImagen = $idImagen
");

$sArray = fn_ExtraerQuery($sQL);

return $sArray;
} // fin funcion


function fndb_validaProcesoAnclaje($anioLectivo){

$sQL = fn_EjecutarQuery("
select count(*) registros
from imagenesitems
where anioLectivo = '$anioLectivo'
and flagAnclaje = '1'
");
$rOW = fn_ExtraerQuery($sQL);
return $rOW['registros'];

}


function fndb_anclarImagenesItem($idUsuario, $anioLectivo){

#Validar Proceso
$vProceso		= fndb_validaProcesoAnclaje($anioLectivo);
if ($vProceso != 0){
 return 2;	
}

$fechaHora		= fn_getFechaHoraDefault();
$sRowConf		= fndb_getConfiguracionbyId(2); #Porcentaje Doble Revision
$porcDoble		= ($sRowConf[strtolower('valorConfiguracion')] / 100);

$sQL = fn_EjecutarQuery("select count(*) totalPreguntas from imagenesitems im where im.anioLectivo = $anioLectivo; ");
$sRow = fn_ExtraerQuery($sQL);
$totalPreguntas = $sRow[strtolower('totalPreguntas')];

$sQL = fn_EjecutarQuery("
update imagenesitems set flagAnclaje = '1' 
where idImagen in (
select im.idImagen
from imagenesitems im
where im.anioLectivo = $anioLectivo
and im.flagRevisado = 0
order by random() 
limit ($totalPreguntas * $porcDoble) offset 0
);
");
if ($sQL) {
	return 0;
}else{
	return 1;	
}#if


}#function



#Se obtendran imagenes para iniciar calificacion
/*
- Este no marcada como calificada (flagCalificacion)
- Este no marcada como finalizada (flagRevisado)
*/
function fndb_getImagenItemCalificar() {

global $sArray;
$idUsuario 	= $_SESSION[_NameSession_idUser];
$sRowUser	= fndb_getUsuariobyId($idUsuario);
$idItem		= $sRowUser[strtolower('idItem')];
if (isset($idItem)){
$sQL = fn_EjecutarQuery("
select im.idImagen, im.anioLectivo, im.nomImagen, im.idCentroEducativo, ce.nomCentro, im.idItem, i.nomItem,
i.idCategoria, c.nomCategoria, c.codigoCategoria, i.idGrado, g.nomGrado, g.codigoGrado
from imagenesitems  im
inner join items i
	on i.idItem = im.idItem
	and im.idItem = $idItem
	and i.isActivo = 1
inner join categorias c
	on c.idCategoria = i.idCategoria
inner join grados g
	on g.idGrado = i.idGrado
inner join centroseducativos ce
	on ce.idCentroEducativo = im.idCentroEducativo
where im.flagCalificacion = '0'
and im.flagRevisado = '0'
and im.idImagen not in (select idImagen from imagenesasignadas)
order by random() 
limit 1;
");

$sArray = fn_ExtraerQuery($sQL);
}

return $sArray;
} // fin funcion


#Se obtendran imagenes para doble revision que cumpla lo siguiente:
/*
- Este marcada como doble revision (flagAnclaje)
- Este previamente marcada como ya calificada por 1er usuario (flagCalificacion)
- Este no marcada como finalizada el proceso de calificación (flagRevisado)
- No haya realizado una calificacion a la imagen.
*/
function fndb_getImagenItemCalificarAnclaje() {

global $sArray;
$idUsuario 	= $_SESSION[_NameSession_idUser];
$sRowUser	= fndb_getUsuariobyId($idUsuario);
$idItem		= $sRowUser[strtolower('idItem')];
if (isset($idItem)){
$sQL = fn_EjecutarQuery("
select im.idImagen, im.anioLectivo, im.nomImagen, im.idCentroEducativo, ce.nomCentro, im.idItem, i.nomItem,
i.idCategoria, c.nomCategoria, c.codigoCategoria, i.idGrado, g.nomGrado, g.codigoGrado
from imagenesitems  im
inner join items i
	on i.idItem = im.idItem
	and im.idItem = $idItem
	and i.isActivo = 1
inner join categorias c
	on c.idCategoria = i.idCategoria
inner join grados g
	on g.idGrado = i.idGrado
inner join centroseducativos ce
	on ce.idCentroEducativo = im.idCentroEducativo
inner join scoreitems si
	on si.idImagen = im.idImagen
	and si.anioLectivo = im.anioLectivo
where im.flagAnclaje = '1'
and im.flagCalificacion = '1'
and im.flagRevisado = '0'
and im.idImagen not in (select idImagen from scoreitems where idUsuario = $idUsuario)
and im.idImagen not in (select idImagen from imagenesasignadas)
group by im.idImagen, im.anioLectivo, im.nomImagen, im.idCentroEducativo, ce.nomCentro, im.idItem, i.nomItem,
i.idCategoria, c.nomCategoria, c.codigoCategoria, i.idGrado, g.nomGrado, g.codigoGrado
having count(si.idUsuario) = 1
order by random() 
limit 1;
");

$sArray = fn_ExtraerQuery($sQL);
}

return $sArray;
} // fin funcion


#Se obtendran imagenes para calificacion por el supervisor:
/*
- Este marcada como doble revision (flagAnclaje)
- Este previamente marcada como ya calificada por 1er usuario (flagCalificacion)
- Este no marcada como finalizada el proceso de calificación (flagRevisado)
- Tenga las dos revisiones previas (revisores).
- *En la pantalla se hara validacion si el idPerfil del usuario es 'Supervisor'.
*/
function fndb_getImagenItemCalificarSupervisor() {

global $sArray;
$idUsuario 	= $_SESSION[_NameSession_idUser];
$sRowUser	= fndb_getUsuariobyId($idUsuario);
$idItem		= $sRowUser[strtolower('idItem')]; 
if (isset($idItem)){
$sQL = fn_EjecutarQuery("
select im.idImagen, im.anioLectivo, im.nomImagen, im.idCentroEducativo, ce.nomCentro, im.idItem, i.nomItem,
i.idCategoria, c.nomCategoria, c.codigoCategoria, i.idGrado, g.nomGrado, g.codigoGrado
from imagenesitems  im
inner join items i
	on i.idItem = im.idItem
	and im.idItem = $idItem
	and i.isActivo = 1
inner join categorias c
	on c.idCategoria = i.idCategoria
inner join grados g
	on g.idGrado = i.idGrado
inner join centroseducativos ce
	on ce.idCentroEducativo = im.idCentroEducativo
--Calificada
inner join scoreitems si
	on si.idImagen = im.idImagen
	and si.anioLectivo = im.anioLectivo
where im.flagAnclaje = '1'
and im.flagCalificacion = '1'
and im.flagRevisado = '0'
and im.idImagen not in (select idImagen from imagenesasignadas)
group by im.idImagen, im.anioLectivo, im.nomImagen, im.idCentroEducativo, ce.nomCentro, im.idItem, i.nomItem,
i.idCategoria, c.nomCategoria, c.codigoCategoria, i.idGrado, g.nomGrado, g.codigoGrado
having count(si.idUsuario) = 2
order by random() 
limit 1;
");

$sArray = fn_ExtraerQuery($sQL);
}

return $sArray;
} // fin funcion


#Se obtendran imagenes para calificacion por el Coordinador:
/*
- Este marcada como doble revision (flagAnclaje)
- Este previamente marcada como ya calificada por 1er usuario (flagCalificacion)
- Este no marcada como finalizada el proceso de calificación (flagRevisado)
- Tenga las dos revisiones previas (revisores).
- *En la pantalla se hara validacion si el idPerfil del usuario es 'Coordinador'.
*/
function fndb_getImagenItemCalificarCoordinador() {

global $sArray;
$idUsuario 	= $_SESSION[_NameSession_idUser];
$sRowUser	= fndb_getUsuariobyId($idUsuario);
$idItem		= $sRowUser[strtolower('idItem')];
if (isset($idItem)){
$sQL = fn_EjecutarQuery("
select im.idImagen, im.anioLectivo, im.nomImagen, im.idCentroEducativo, ce.nomCentro, im.idItem, i.nomItem,
i.idCategoria, c.nomCategoria, c.codigoCategoria, i.idGrado, g.nomGrado, g.codigoGrado
from imagenesitems  im
inner join items i
	on i.idItem = im.idItem
	and im.idItem = $idItem
	and i.isActivo = 1
inner join categorias c
	on c.idCategoria = i.idCategoria
inner join grados g
	on g.idGrado = i.idGrado
inner join centroseducativos ce
	on ce.idCentroEducativo = im.idCentroEducativo
--Calificada
inner join scoreitems si
	on si.idImagen = im.idImagen
	and si.anioLectivo = im.anioLectivo
where im.flagAnclaje = '1'
and im.flagCalificacion = '1'
and im.flagRevisado = '0'
and im.idImagen not in (select idImagen from imagenesasignadas)
group by im.idImagen, im.anioLectivo, im.nomImagen, im.idCentroEducativo, ce.nomCentro, im.idItem, i.nomItem,
i.idCategoria, c.nomCategoria, c.codigoCategoria, i.idGrado, g.nomGrado, g.codigoGrado
having count(si.idUsuario) = 3
order by random() 
limit 1;
");

$sArray = fn_ExtraerQuery($sQL);
}

return $sArray;
} // fin funcion


#=== Obtener Calificacion de Revisores (Supervisor) ===
function fndb_getCalificacionesRevisores($idImagen,$anioLectivo) {

$sQL = fn_EjecutarQuery("
select si.idScore, si.idImagen, si.anioLectivo, si.calificacion, si.idUsuario, si.fechaCalificacion
from scoreitems si
inner join imagenesitems im
	on im.idImagen = si.idImagen
	and im.anioLectivo = si.anioLectivo
where si.idImagen = $idImagen
and si.anioLectivo = $anioLectivo
order by si.fechaCalificacion asc
");

return $sQL;

} // fin funcion


function fndb_enviarMensajeCoordinadorItem($fromMensaje, $idItem, $IDItemImagen, $idImagen){

$fechaHora	= fn_getFechaHoraDefault();

$sSQL = fn_EjecutarQuery("
select u.idUsuario, u.nomUsuario, p.idPerfil, p.nomPerfil
from usuarios u
inner join perfiles p
	on p.idPerfil = u.idPerfil
inner join items i
	on i.idItem = u.idItem
	and i.idItem = $idItem
where u.isActivo = '1'
and u.idPerfil = '4'
order by u.idUsuario;
");
while ($sRow = fn_ExtraerQuery($sSQL))
{
	$idUsuario 	= $sRow[strtolower('idUsuario')];
  	$nomUsuario = $sRow[strtolower('nomUsuario')]; 
  	$idPerfil	= $sRow[strtolower('idPerfil')]; 
  	$nomPerfil	= $sRow[strtolower('nomPerfil')]; 
	
	$mensaje	= '<p><b>Estimado Coordinador</b></p><p>Se le notifica que se ha enviado el item '.$IDItemImagen.' para realizar
	puntuación.</p>'.fndb_obtenerDatosCalificacionesMensaje($idImagen);
	fndb_nuevoMensaje(0, $fromMensaje, $idUsuario, 'Revisión de Item '.$IDItemImagen, $mensaje);
		  
}

return 0;

}


function fndb_marcarCalificacionItem($idImagen, $anioLectivo){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("
update imagenesitems set flagCalificacion = '1'
where idImagen = $idImagen
and anioLectivo = $anioLectivo
");
return 0;

}


function fndb_marcarCalificacionFinal($idImagen, $anioLectivo, $calificacion){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("
update imagenesitems set calificacionFinal = '$calificacion',
flagRevisado = '1'
where idImagen = $idImagen
and anioLectivo = $anioLectivo
");
return 0;

}


#Funcion para validar si el item esta marcado como revisado
function fndb_validaRevision($anioLectivo, $idImagen){

$sQL = fn_EjecutarQuery("
select count(*) registros
from imagenesitems
where anioLectivo = '$anioLectivo'
and idImagen = '$idImagen'
and flagRevisado = '1'
");
$rOW = fn_ExtraerQuery($sQL);
return $rOW['registros'];

}

#Funcion para obtener imagenes pendientes de revisar para validacion de asignacion de imagens
function fndb_obtenerItemsPendientesRevisar($idImagen, $anioLectivo, $idItem){

$sQL = fn_EjecutarQuery("
select count(*) registros
from imagenesitems im
inner join items i
	on i.idItem = im.idItem
	and im.idItem = $idItem
	and i.isActivo = 1
left join imagenesasignadas ia
	on ia.idImagen = im.idImagen
	and ia.anioLectivo = im.anioLectivo
where im.flagRevisado = '0'
and ia.idImagen is null
");
$rOW = fn_ExtraerQuery($sQL);
return $rOW['registros'];

}

// ---------------<- imagenesitems  ->---------------


// ---------------<- scoreitems  ->---------------


#Funcion para revisar si la calificacion es igual a una de los revisores (SUPERVISOR)
function fndb_validaCalificacion($idImagen, $anioLectivo, $calificacion){

$sQL = fn_EjecutarQuery("
select count(*) registros
from scoreitems
where anioLectivo = '$anioLectivo'
and idImagen = '$idImagen'
and calificacion = '$calificacion'
");
$rOW = fn_ExtraerQuery($sQL);
return $rOW['registros'];

}

function fndb_existeCalificacion($idUsuario, $anioLectivo, $idImagen){

$sQL = fn_EjecutarQuery("
select count(*) registros
from scoreitems
where anioLectivo = '$anioLectivo'
and idImagen = '$idImagen'
and idUsuario = '$idUsuario'
");
$rOW = fn_ExtraerQuery($sQL);
return $rOW['registros'];

}

function fndb_calificarItem($fechaIniCalificacion, $idUsuario, $anioLectivo, $idImagen, $calificacion, $memoScore){

$fechaCalificacion	= fn_getFechaHoraDefault();

$nExiste	= fndb_existeCalificacion($idUsuario, $anioLectivo, $idImagen);

	if ($nExiste == 0){#Evitar doble calificacion del mismo Usuario
	$sQL = fn_EjecutarQuery("insert into scoreitems (idUsuario,
												 anioLectivo,
												 idImagen,
												 calificacion,
												 fechaIniCalificacion,
												 fechaCalificacion,
												 memoscore
												)
						
						values ('$idUsuario',
								'$anioLectivo',
								'$idImagen',
								'$calificacion',
								'$fechaIniCalificacion',
								'$fechaCalificacion',
								'$memoScore'
								)
					");
					
	fndb_marcarCalificacionItem($idImagen, $anioLectivo);
	return 0;
	}else{
	return 1;
	}

}#funcion



function fndb_existeCalificacionDoble($idUsuario, $anioLectivo, $idImagen){

$sQL = fn_EjecutarQuery("
select count(*) registros
from scoreitemsdobles
where anioLectivo = '$anioLectivo'
and idImagen = '$idImagen'
and idUsuario = '$idUsuario'
");
$rOW = fn_ExtraerQuery($sQL);
return $rOW['registros'];

}

function fndb_calificarItemDoble($fechaIniCalificacion, $idUsuario, $anioLectivo, $idImagen, $calificacion, $memoScore){

$fechaCalificacion	= fn_getFechaHoraDefault();

$nExiste	= fndb_existeCalificacionDoble($idUsuario, $anioLectivo, $idImagen);

	if ($nExiste == 0){#Evitar doble calificacion del mismo Usuario
			$sQL = fn_EjecutarQuery("insert into scoreitemsdobles (idUsuario,
												 anioLectivo,
												 idImagen,
												 calificacion,
												 fechaIniCalificacion,
												 fechaCalificacion,
												 memoscore
												)
						
						values ('$idUsuario',
								'$anioLectivo',
								'$idImagen',
								'$calificacion',
								'$fechaIniCalificacion',
								'$fechaCalificacion',
								'$memoScore'
								)
					");
						
		return 0;
	}else{
		return 1;
	}

}#funcion


function fndb_getCalificacionScoreItem($idImagen, $anioLectivo) {

$sQL = fn_EjecutarQuery("
select si.idScore, si.calificacion
from scoreitems si
inner join imagenesitems im
	on im.idImagen = si.idImagen
	and im.anioLectivo = si.anioLectivo
where si.idImagen = $idImagen
and si.anioLectivo = $anioLectivo
order by si.fechaCalificacion desc
limit 1;
");

$sArray = fn_ExtraerQuery($sQL);

return $sArray[strtolower('calificacion')];

} // fin funcion


/*
Utilizado para validar que exista solamente dos calificaciones para
el proceso de doble revision. (el numero de registros debe ser a 1 para poder asignar otro puntaje
*/
function fndb_validarDobleRevision($anioLectivo, $idImagen){

$sQL = fn_EjecutarQuery("
select count(*) registros
from scoreitems
where anioLectivo = '$anioLectivo'
and idImagen = '$idImagen'
");
$rOW = fn_ExtraerQuery($sQL);
return $rOW['registros'];

}


function fndb_obtenerTotalCalificacionesbyUsuario($idUsuario){

$sQL = fn_EjecutarQuery("
select v1.TCalificaciones TC1, coalesce(v2.TCalificaciones,0) TC2, (v1.TCalificaciones + coalesce(v2.TCalificaciones,0)) TCalificaciones, 
u.idUsuario, u.nomUsuario
from usuarios u
inner join (
select count(*) TCalificaciones, si.idUsuario
from scoreitems si
inner join imagenesitems im
on im.idImagen = si.idImagen
and im.anioLectivo = im.anioLectivo
group by si.idUsuario
) v1 on v1.idUsuario = u.idUsuario
left join (
select count(*) TCalificaciones, si.idUsuario
from scoreitemsdobles si
inner join imagenesitems im
on im.idImagen = si.idImagen
and im.anioLectivo = im.anioLectivo
group by si.idUsuario
) v2 on v2.idUsuario = u.idUsuario
where u.idUsuario = $idUsuario;
");
$rOW = fn_ExtraerQuery($sQL);
return $rOW[strtolower('TCalificaciones')];

}


function fndb_obtenerTotalCalificacionesbyUsuarioItemFecha($idUsuario, $idItem, $fechaCalificacion){

$sQL = fn_EjecutarQuery("
select v1.TCalificaciones TC1, coalesce(v2.TCalificaciones,0) TC2, (v1.TCalificaciones + coalesce(v2.TCalificaciones,0)) TCalificaciones, 
u.idUsuario, u.nomUsuario
from usuarios u
inner join (
select count(*) TCalificaciones, si.idUsuario
from scoreitems si
inner join imagenesitems im
on im.idImagen = si.idImagen
and im.anioLectivo = im.anioLectivo
and im.idItem = $idItem
where to_char(si.fechaCalificacion, 'YYYY-MM-DD') = '$fechaCalificacion'
group by si.idUsuario
) v1 on v1.idUsuario = u.idUsuario
left join (
select count(*) TCalificaciones, si.idUsuario
from scoreitemsdobles si
inner join imagenesitems im
on im.idImagen = si.idImagen
and im.anioLectivo = im.anioLectivo
and im.idItem = $idItem
where to_char(si.fechaCalificacion, 'YYYY-MM-DD') = '$fechaCalificacion'
group by si.idUsuario
) v2 on v2.idUsuario = u.idUsuario
where u.idUsuario = $idUsuario;
");
$rOW = fn_ExtraerQuery($sQL);
return $rOW[strtolower('TCalificaciones')];

}


function fndb_obtenerMaxCalificacionItembyUsuario($idUsuario){

$sQL = fn_EjecutarQuery("
select i.maxRevisiones TRevisionesItem
from items i
inner join usuarios u
	on u.idItem = i.idItem
	and i.isActivo = 1
where u.idUsuario = $idUsuario;
");
$rOW = fn_ExtraerQuery($sQL);
return $rOW[strtolower('TRevisionesItem')];

}

function fndb_validarTotalCalificacionesbyUsuario($idUsuario){

$TCalificaciones = fndb_obtenerTotalCalificacionesbyUsuario($idUsuario);#$rOW[strtolower('TCalificaciones')];
$TRevisionesItem = fndb_obtenerMaxCalificacionItembyUsuario($idUsuario);#$rOW[strtolower('TRevisionesItem')];
$sArray = array(
'TCalificaciones'=>$TCalificaciones, 
'TRevisionesItem'=>$TRevisionesItem
);

	if ($TCalificaciones >= $TRevisionesItem) {
		return true;	
	}else{
		return false;
	}
	
}#function

// ---------------<- scoreitems  ->---------------




// ---------------<- imagenesasignadas  ->---------------

function fndb_asignarImagen($idUsuario, $idImagen, $anioLectivo){

$fechaAsignacion = fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("insert into imagenesasignadas (idUsuario,
											 idImagen,
											 anioLectivo,
											 fechaAsignacion
											)
					
					values ('$idUsuario',
							'$idImagen',
							'$anioLectivo',
							'$fechaAsignacion'
							)
				");
					
return 0;

}#funcion


function fndb_existeAsignacionImagen($idUsuario, $idImagen, $anioLectivo){

$sQL = fn_EjecutarQuery("
select count(*) registros
from imagenesasignadas
where anioLectivo = '$anioLectivo'
and idImagen = '$idImagen'
and idUsuario <> '$idUsuario'
");
$rOW = fn_ExtraerQuery($sQL);
return $rOW['registros'];

}

function fndb_existeAsignacionImagenbyUsuario($idUsuario) {#, $idImagen, $anioLectivo){

$sQL = fn_EjecutarQuery("
select count(*) registros
from imagenesasignadas
--where anioLectivo = '$anioLectivo'
--and idImagen = '$idImagen'
--and idUsuario = '$idUsuario'
where idUsuario = '$idUsuario'
");
$rOW = fn_ExtraerQuery($sQL);
return $rOW['registros'];

}

function fndb_desasignarImagen($idUsuario, $idImagen, $anioLectivo){

$fechaAsignacion = fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("delete from imagenesasignadas 
						where idUsuario = $idUsuario
						and idImagen = $idImagen
						and anioLectivo = $anioLectivo
						");
return 0;

}#funcion


function fndb_desasignarImagenbyUsuario($idUsuario){ #, $idImagen, $anioLectivo){

$fechaAsignacion = fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("delete from imagenesasignadas 
						where idUsuario = $idUsuario
						");
return 0;

}#funcion

// ---------------<- imagenesasignadas  ->---------------


// ---------------<- horarios ->---------------
function fndb_existeHorario($diaSemana){

$sQL = fn_EjecutarQuery("
select count(*) registros
from horarios
where diaSemana = '$diaSemana'
");
$rOW = fn_ExtraerQuery($sQL);
return $rOW['registros'];

}

function fndb_nuevoHorario($idUsuario, $diaSemana, $horaInicio, $horaFinal, $isActivo){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("insert into horarios (diaSemana,
											horaInicio,
											horaFinal,
											fechaCreacion,
											isActivo
											)
					
					values ('$diaSemana',
							'$horaInicio',
							'$horaFinal',
							'$fechaHora',
							'$isActivo'
							)
				");
				
				
return 0;

}

// editar
function fndb_editarHorario($idUsuario, $idHorario, $diaSemana, $horaInicio, $horaFinal, $isActivo){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("update horarios set diaSemana = '$diaSemana',
											horaInicio = '$horaInicio',
											horaFinal = '$horaFinal',
											fechaCreacion = '$fechaHora',
											isActivo = '$isActivo'
														
					where idHorario = $idHorario
					
				");
				
					
return 0;

}

// informacion por Id
function fndb_getHorariobyId($ID) {

global $sArray;

$sQL = fn_EjecutarQuery("
select h.idHorario, h.diaSemana, h.horaInicio, h.horaFinal, h.isActivo
from horarios h
where h.idHorario = $ID
");

$sArray = fn_ExtraerQuery($sQL);
return $sArray;

} // fin funcion


// informacion por dia semana
function fndb_getHorariobyDiaSemana($diaSemana) {

$sQL = fn_EjecutarQuery("
select h.idHorario, h.diaSemana, h.horaInicio, h.horaFinal, h.isActivo
from horarios h
where h.diaSemana = $diaSemana
");

$sArray = fn_ExtraerQuery($sQL);
return $sArray;

} // fin funcion

// ---------------<- horarios  ->---------------



// ---------------<- reportes  ->---------------
function fndb_getDatosReporte1() {

global $sArray;

$sQL = fn_EjecutarQuery("select count(*) totalPreguntas from imagenesitems im
inner join items i
	on i.idItem = im.idItem
where i.isActivo = 1 ");
$sRow = fn_ExtraerQuery($sQL);
$totalPreguntas = $sRow[strtolower('totalPreguntas')];

$sQL = fn_EjecutarQuery("select count(*) totalCalificadas from imagenesitems im
inner join items i
	on i.idItem = im.idItem
where im.flagRevisado = '1'
and i.isActivo = 1 ");
$sRow = fn_ExtraerQuery($sQL);
$totalCalificadas = $sRow[strtolower('totalCalificadas')];

$totalPendientes = ($totalPreguntas -  $totalCalificadas);

$sArray = array(
'totalPendientes'=>$totalPendientes, 
'totalCalificadas'=>$totalCalificadas
);

return $sArray;
} // fin funcion


function fndb_getDatosReporte2() {

global $sArray;

$sQL = fn_EjecutarQuery("select count(*) totalPreguntas from imagenesitems im
inner join items i
	on i.idItem = im.idItem
where i.isActivo = 1 ");
$sRow = fn_ExtraerQuery($sQL);
$totalPreguntas = $sRow[strtolower('totalPreguntas')];

$sQL = fn_EjecutarQuery("select count(*) totalCalificadas from imagenesitems im
inner join items i
	on i.idItem = im.idItem
where im.flagRevisado = '1'
and i.isActivo = 1 ");
$sRow = fn_ExtraerQuery($sQL);
$totalCalificadas = $sRow[strtolower('totalCalificadas')];

$totalPendientes = ($totalPreguntas -  $totalCalificadas);

$sArray = array(
'totalPreguntas'=>$totalPreguntas,
'totalPendientes'=>$totalPendientes, 
'totalCalificadas'=>$totalCalificadas
);

return $sArray;
} // fin funcion


function fndb_getReporteCalificacionesUsuario($idUsuario, $fecha = '') {
$sQL = fn_EjecutarQuery("
select v1.calificaciones C1, coalesce(v2.calificaciones,0) C2, (v1.calificaciones + coalesce(v2.calificaciones,0)) calificaciones, 
i.nomItem, u.nomUsuario, i.maxRevisiones
from usuarios u
inner join items i
on i.idItem = u.idItem
and i.isActivo = 1
inner join(
select count(*) calificaciones, u.idUsuario, u.nomUsuario
from scoreitems si
inner join imagenesitems im
on im.idImagen = si.idImagen
and im.anioLectivo = si.anioLectivo
inner join items i
on i.idItem = im.idItem
inner join usuarios u
on u.idUsuario = si.idUsuario
where to_char(si.fechaCalificacion, 'YYYY-MM-DD') like '%$fecha%'
group by u.idUsuario, u.nomUsuario
) v1 on v1.idUsuario = u.idUsuario
left join(
select count(*) calificaciones, u.idUsuario, u.nomUsuario
from scoreitemsdobles si
inner join imagenesitems im
on im.idImagen = si.idImagen
and im.anioLectivo = si.anioLectivo
inner join items i
on i.idItem = im.idItem
inner join usuarios u
on u.idUsuario = si.idUsuario
where to_char(si.fechaCalificacion, 'YYYY-MM-DD') like '%$fecha%'
group by u.idUsuario, u.nomUsuario
) v2 on v2.idUsuario = u.idUsuario
where u.idUsuario = $idUsuario
order by u.nomUsuario;
");

return $sQL;
} // fin funcion


// ---------------<- reportes  ->---------------


//-- arreglos
function obtenerCalificacionSupervisorArreglo($idImagen){

$sQL = fn_EjecutarQuery("
select si.calificacion
from scoreitems si
inner join usuarios u
on u.idUsuario = si.idUsuario
inner join perfiles p
on u.idPerfil = p.idPerfil
where si.idImagen = $idImagen
and p.idPerfil = 3
");
$sRow = fn_ExtraerQuery($sQL);
$calificacion = $sRow[strtolower('calificacion')];
return $calificacion;
}

function fndb_validaCalificacionRevidoresArreglo($idImagen, $calificacion){

$sQL = fn_EjecutarQuery("
select count(*) registros
from scoreitems si
inner join usuarios u
on u.idUsuario = si.idUsuario
inner join perfiles p
on u.idPerfil = p.idPerfil
where idImagen = '$idImagen'
and calificacion = '$calificacion'
and p.idPerfil = 2
");
$rOW = fn_ExtraerQuery($sQL);
return $rOW['registros'];

}

function fndb_getCalificacionesRevisoresArreglo($idImagen) {

$sQL = fn_EjecutarQuery("
select si.idScore, si.idImagen, si.anioLectivo, si.calificacion, si.idUsuario, si.fechaCalificacion
from scoreitems si
inner join usuarios u
on u.idUsuario = si.idUsuario
inner join perfiles p
on u.idPerfil = p.idPerfil
where si.idImagen = $idImagen
and p.idPerfil = 2
order by si.fechaCalificacion asc
");

return $sQL;

} // fin funcion

function fndb_marcarCalificacionFinalArreglo($idImagen, $calificacion){

$fechaHora	= fn_getFechaHoraDefault();

$sQL = fn_EjecutarQuery("
update imagenesitems set calificacionFinal = '$calificacion'
where idImagen = $idImagen
");
return 0;

}

//-- arreglos

?>