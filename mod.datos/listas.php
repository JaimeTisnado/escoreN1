<?php

OpenConection();
$fechaActual = fn_getFechaDefault();

 
$sQL_getUsuarios = fn_EjecutarQuery("
select a.idUsuario, a.nomUsuario, a.nickUsuario, a.passUsuario, a.lastLogin, a.lastIP, a.isActivo, b.idPerfil, b.nomPerfil
from usuarios a
inner join perfiles b
	on a.idPerfil = b.idPerfil
order by 2
");	
	 
$sQL_getPerfiles = fn_EjecutarQuery("
select a.idPerfil, a.nomPerfil, a.isActivo
from perfiles a
where a.isActivo = 1
order by 2
");
							
$sQL_getAccesos = fn_EjecutarQuery("
select a.idAcceso, a.nomAcceso, a.linkAcceso, a.orden, a.parentID, a.isActivo
from accesos a
where a.isActivo = 1
order by 2
");

$sQL_getAccesosPadre = fn_EjecutarQuery("
select a.idAcceso, a.nomAcceso, a.linkAcceso, a.orden, a.parentID, a.isActivo
from accesos a
where a.isActivo = 1
and a.parentID = 0
order by a.orden
");	

$sQL_getPaises = fn_EjecutarQuery("
select a.idPais, a.nomPais, a.isActivo
from paises a
where a.isActivo = 1
order by 2
");

$sQL_getDepartamentos = fn_EjecutarQuery("
select b.idPais, b.nomPais, a.idDepartamento, a.nomDepartamento, a.isActivo
from departamentos a
inner join paises b
	on b.idPais = a.idPais
where a.isActivo = 1
order by a.nomDepartamento
");

$sQL_getMunicipios = fn_EjecutarQuery("
select p.idPais, p.nomPais, d.idDepartamento, d.nomDepartamento, m.idMunicipio, m.nomMunicipio, m.isActivo
from municipios m
inner join departamentos d
	on d.idDepartamento = m.idDepartamento
inner join paises p
	on p.idPais = d.idPais
where m.isActivo = 1
order by m.nomMunicipio
");

$sQL_getCategorias = fn_EjecutarQuery("
select a.idCategoria, a.nomCategoria, a.isActivo
from categorias a
where a.isActivo = 1
order by 2
");

$sQL_getGrados = fn_EjecutarQuery("
select a.idGrado, a.nomGrado, a.isActivo
from grados a
where a.isActivo = 1
order by 2
");

$sQL_getItems = fn_EjecutarQuery("
select c.idCategoria, c.nomCategoria, g.idGrado, g.nomGrado, i.idItem, i.nomItem, i.isActivo
from items i
inner join categorias c
	on c.idCategoria = i.idCategoria
inner join grados g
	on g.idGrado = i.idGrado
where i.isActivo = 1
order by i.nomItem
");

?>