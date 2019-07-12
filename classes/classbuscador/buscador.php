<html>
<head>
<?php
// Index.php  21/10/2003
// --------------------------------------------------------------------------------------//
// eHospital v 0.1                                                                      //
// Copyright (C) 2003 InterSoftware Ltda.                                              //
// Emai: intersof@telesat.com.co                                                      //
// ----------------------------------------------------------------------------------//
// Autor: Jairo Duvan Diaz Martinez (jaja)                                          //
// Proposito del Archivo: Concatena con la clase para realizar la busqueda         //
// de 'CONSULTA EXTERNA' necesita modulo para trabajar..                          //
// ------------------------------------------------------------------------------//

$VISTA='HTML';
$_ROOT='../../';
include_once $_ROOT.'includes/enviroment.inc.php';//se incluye la clase del buscador.....
include_once("buscador.class.php");//se incluye la clase del buscador.....
include("html.php");

if(!empty($_REQUEST['tipo'])){
	$_SESSION['TIPO_BUSCADOR'] = $_REQUEST['tipo'];
	$_SESSION['SQL']=$_REQUEST['sql'];
	$_SESSION['key']=$_REQUEST['key'];
	$_SESSION['FORMA']=$_REQUEST['forma'];
	$_SESSION['SPIA']=$_REQUEST['alias']; //CASO de LORENA .....
	$_SESSION['plan']=$_REQUEST['plan'];
	$_SESSION['tipo_cargo']=$_REQUEST['tipo_cargo'];
	$_SESSION['grupo_tipo_cargo']=$_REQUEST['grupo_tipo_cargo'];
	$_SESSION['tipoProcedimiento']=$_REQUEST['tipoProcedimiento'];
  $_SESSION['PREFIJO']=$_REQUEST['pfj'];
	$_SESSION['CARGO']=$_REQUEST['cargo'];

	$_SESSION['EMPRESA']=$_REQUEST['Empresa'];
	$_SESSION['CU']=$_REQUEST['CU'];
	$_SESSION['BODEGA']=$_REQUEST['Bodega'];

/*******************[ Arley ]***********************/
	$_SESSION['bodegas']=unserialize(stripslashes($_REQUEST['bdgas']));
}
$TIPO_BUSCADOR = $_SESSION['TIPO_BUSCADOR'];
$PLAN=$_SESSION['SQL'];
$spia=$_SESSION['SPIA'];
if(empty($TIPO_BUSCADOR)){
    die(MsgOut('ERROR AL CARGAR EL BUSCADOR','No llego el tipo de submodulo'));
}
//echo $TIPO_BUSCADOR;
switch($TIPO_BUSCADOR){


case "diagnostico":                        //ultimo arreglo del buscador

$pfj=$_SESSION['PREFIJO'];
$Configb['Btabla']  = 'diagnosticos'; //nombre de la tabla de busqueda
$Configb['Bcampos']  = '2';//numero de campos que posee la tabla para la muestra
$Configb['Bclave']  = 'diagnostico_nombre'; // esta el campo  clave de busqueda.....
$Configb['Bclave1']  = 'diagnostico_id'; // esta el campo  clave de busqueda.....
$Configb['Bncampo']="diagnostico_id,diagnostico_nombre";//Aqui el nombre de los campos
$Configb['Bnombres']="Codigo Cargo";//Aqui se coloca como quiere que salga el nombre
//de sus campos en la base de datos se colocan separado por espacios..
$Configb['Bcol1']="#DDDDDD";//color de intercalado definifo
$Configb['Bcol2']="#CCCCCC";//color de intercalado definifo
$Configb['Bcab']="#D3DCE3";//color de la cabecera de la tabla..
$Configb['BNhidden']="2";//numero de hiddens a crear

//if($_SESSION['SQL']=='aps') // solicitud de apoyo diagnosticos esta variable la utiliza claudia..
 //{
	//$Configb['Bx']="codigo1 cargoj";//
//}else{

	if($spia=='car1')
	{
	$Configb['Bx']="codigo1 cargo1";//
	}elseif($spia=='car2'){
	$Configb['Bx']="codigo2 cargo2";//
	}
	else{$Configb['Bx']="codigo$pfj cargo$pfj";}//
//}


$dato=new buscador;
$s=$Configb['Bx'];
$form=$_SESSION['FORMA']; //nombre de la forma para pasar los datos...
abre_html();
$dato->imprime_java($s,$form);
$spi=$_REQUEST['spi'];

if($spi==true)
{
$Configb['spi']=$_REQUEST['spi'];
$Configb['conteo']=$_REQUEST['conteo'];
$Configb['Of']=$_REQUEST['Of'];
$Configb['buscar']=$_REQUEST['buscar'];
$Configb['key1']=$_REQUEST['key1'];
}
$dato->recibir_dato($Configb);
cierra_html();
break;


case "proveedores":                //ultimo arreglo del buscador
$_SESSION['CLIENTES_TERCEROS']="and cal_cli='0'";
$pfj=$_SESSION['PREFIJO'];
$Configb['Btabla']  = 'terceros'; //nombre de la tabla de busqueda
$Configb['Bcampos']  = '3';//numero de campos que posee la tabla para la muestra
$Configb['Bclave']  = 'nombre_tercero'; // esta el campo  clave de busqueda.....
$Configb['Bclave1']  = 'tercero_id'; // esta el campo  clave de busqueda.....
$Configb['Bncampo']="tercero_id,nombre_tercero,tipo_id_tercero";//Aqui el nombre de los campos
$Configb['Bnombres']="Codigo Nombre Tipo";//Aqui se coloca como quiere que salga el nombre
//de sus campos en la base de datos se colocan separado por espacios..
$Configb['Bcol1']="#DDDDDD";//color de intercalado definifo
$Configb['Bcol2']="#CCCCCC";//color de intercalado definifo
$Configb['Bcab']="#D3DCE3";//color de la cabecera de la tabla..
$Configb['BNhidden']="3";//numero de hiddens a crear
//echo $_REQUEST['buscar'];
//echo $_REQUEST['buscar2'];
$Configb['Bx']="codigo nombre tipoTerceroId";
$dato=new buscador;
$s=$Configb['Bx'];
$form=$_SESSION['FORMA']; //nombre de la forma para pasar los datos...
abre_html();
$dato->imprime_java($s,$form);
$spi=$_REQUEST['spi'];

if($spi==true)
{
$Configb['spi']=$_REQUEST['spi'];
$Configb['conteo']=$_REQUEST['conteo'];
$Configb['Of']=$_REQUEST['Of'];
$Configb['buscar']=$_REQUEST['buscar'];
$Configb['key1']=$_REQUEST['key1'];
}
$dato->recibir_dato_proveedor($Configb);
cierra_html();
break;


case "servicios":                //ultimo arreglo del buscador
$_SESSION['CLIENTES_TERCEROS']="AND A.tercero_id=B.tercero_id AND A.tipo_id_tercero=B.tipo_id_tercero";
$pfj=$_SESSION['PREFIJO'];
$Configb['Btabla']  = 'terceros AS A, terceros_proveedores_servicios_salud AS B'; //nombre de la tabla de busqueda
$Configb['Bcampos']  = '3';//numero de campos que posee la tabla para la muestra
$Configb['Bclave']  = 'A.nombre_tercero'; // esta el campo  clave de busqueda.....
$Configb['Bclave1']  = 'B.tercero_id'; // esta el campo  clave de busqueda.....
$Configb['Bncampo']="B.tercero_id,A.nombre_tercero,B.tipo_id_tercero";//Aqui el nombre de los campos
$Configb['Bnombres']="Codigo Nombre Tipo";//Aqui se coloca como quiere que salga el nombre
//de sus campos en la base de datos se colocan separado por espacios..
$Configb['Bcol1']="#DDDDDD";//color de intercalado definifo
$Configb['Bcol2']="#CCCCCC";//color de intercalado definifo
$Configb['Bcab']="#D3DCE3";//color de la cabecera de la tabla..
$Configb['BNhidden']="3";//numero de hiddens a crear
//echo $_REQUEST['buscar'];
//echo $_REQUEST['buscar2'];
$Configb['Bx']="codigo nombre tipoTerceroId";
$dato=new buscador;
$s=$Configb['Bx'];
$form=$_SESSION['FORMA']; //nombre de la forma para pasar los datos...
abre_html();
$dato->imprime_java($s,$form);
$spi=$_REQUEST['spi'];

if($spi==true)
{
$Configb['spi']=$_REQUEST['spi'];
$Configb['conteo']=$_REQUEST['conteo'];
$Configb['Of']=$_REQUEST['Of'];
$Configb['buscar']=$_REQUEST['buscar'];
$Configb['key1']=$_REQUEST['key1'];
}
$dato->recibir_dato_proveedor($Configb);
cierra_html();
break;

case "inventarios":
$_REQUEST['BodegaDest'];
$_REQUEST['CentroDest'];
$pfj=$_SESSION['PREFIJO'];

if(empty($_SESSION['SQLA'])){
	$_SESSION['SQL']=$_REQUEST['sql'];
	$_SESSION['SQLA']=$_REQUEST['sqla'];
	$_SESSION['SQLB']=$_REQUEST['sqlb'];
	$_SESSION['SQLC']=$_REQUEST['CentroDest'];
  $_SESSION['SQLD']=$_REQUEST['BodegaDest'];
}
$Configb['Btabla']  ='inventarios'; //nombre de la tabla de busqueda
$Configb['Bcampos']  ='5';//numero de campos que posee la tabla para la muestra
$Configb['Bclave']  ='descripcion'; // esta el campo  clave de busqueda.....
$Configb['Bclave1']  ='codigo_producto'; // esta el campo  clave de busqueda.....
$Configb['Bncampo']="SELECT x.codigo_producto,l.descripcion,x.precio_venta,z.existencia,y.descripcion,x.costo,c.existencia as exisdest FROM inventarios x,inventarios_productos l,unidades y,existencias_bodegas z LEFT JOIN existencias_bodegas c ON(z.codigo_producto=c.codigo_producto AND z.empresa_id=c.empresa_id AND c.centro_utilidad='".$_SESSION['SQLC']."' AND c.bodega='".$_SESSION['SQLD']."') WHERE l.unidad_id=y.unidad_id AND z.estado='1'";//Aqui el nombre de los campos
$Configb['Bnombres']="Codigo Nombre Precio Existencia Unidad";//Aqui se coloca como quiere que salga el nombre
//de sus campos en la base de datos se colocan separado por espacios..
$Configb['Bcol1']="#DDDDDD";//color de intercalado definifo
$Configb['Bcol2']="#CCCCCC";//color de intercalado definifo
$Configb['Bcab']="#D3DCE3";//color de la cabecera de la tabla..
$Configb['BNhidden']="7";//numero de hiddens a crear
//echo $_REQUEST['buscar'];
//echo $_REQUEST['buscar2'];
$Configb['Bx']="codigo nombreProducto precioProducto ExisProducto unidadProducto costoProducto ExisDest";
$dato=new buscador;
$s=$Configb['Bx'];
$form=$_SESSION['FORMA']; //nombre de la forma para pasar los datos...
abre_html();
$dato->imprime_java($s,$form);
$spi=$_REQUEST['spi'];

if($spi==true)
{
$Configb['spi']=$_REQUEST['spi'];
$Configb['conteo']=$_REQUEST['conteo'];
$Configb['Of']=$_REQUEST['Of'];
$Configb['buscar']=$_REQUEST['buscar'];
$Configb['key1']=$_REQUEST['key1'];
}
$dato->recibir_dato_Inventario($Configb);
cierra_html();
break;


case "procedimientosQX":
$pfj=$_SESSION['PREFIJO'];
$Configb['Btabla']  ='qx_grupos_tipo_cargo'; //nombre de la tabla de busqueda
$Configb['Bcampos']  ='2';//numero de campos que posee la tabla para la muestra
$Configb['Bclave']  ='descripcion'; // esta el campo  clave de busqueda.....
$Configb['Bclave1']  ='cargo'; // esta el campo  clave de busqueda.....
  if($_SESSION['tipoProcedimiento']==-1){
		$Configb['Bncampo']="SELECT d.cargo,d.descripcion FROM qx_grupos_tipo_cargo a, cups d
		WHERE a.grupo_tipo_cargo=d.grupo_tipo_cargo";
	}else{
    $cadena=explode('/',$_SESSION['tipoProcedimiento']);
		$tipocargo=$cadena[0];
		$grupotipoCargo=$cadena[1];
		$Configb['Bncampo']="SELECT d.cargo,d.descripcion FROM
		qx_grupos_tipo_cargo a,tipos_cargos c,cups d
		WHERE a.grupo_tipo_cargo=c.grupo_tipo_cargo AND
		c.grupo_tipo_cargo=d.grupo_tipo_cargo AND c.tipo_cargo=d.tipo_cargo
		AND c.tipo_cargo='$tipocargo'";
  }
//Aqui el nombre de los campos
$Configb['Bnombres']="Codigo Descripcion";//Aqui se coloca como quiere que salga el nombre
//de sus campos en la base de datos se colocan separado por espacios..
$Configb['Bcol1']="#DDDDDD";//color de intercalado definifo
$Configb['Bcol2']="#CCCCCC";//color de intercalado definifo
$Configb['Bcab']="#D3DCE3";//color de la cabecera de la tabla..
$Configb['BNhidden']="2";//numero de hiddens a crear
//echo $_REQUEST['buscar'];
//echo $_REQUEST['buscar2'];
$Configb['Bx']="codigos procedimiento";
$dato=new buscador;
$s=$Configb['Bx'];
$form=$_SESSION['FORMA']; //nombre de la forma para pasar los datos...
abre_html();
$dato->imprime_java($s,$form);
$spi=$_REQUEST['spi'];

if($spi==true)
{
$Configb['spi']=$_REQUEST['spi'];
$Configb['conteo']=$_REQUEST['conteo'];
$Configb['Of']=$_REQUEST['Of'];
$Configb['buscar']=$_REQUEST['buscar'];
$Configb['key1']=$_REQUEST['key1'];
}
$dato->recibir_dato_ProcedimientosQX($Configb);
cierra_html();
break;
/*
case "procedimiento":
$Configb['Btabla']  = 'procedimientos_qx'; //nombre de la tabla de busqueda
$Configb['Bcampos']  = '2';//numero de campos que posee la tabla para la muestra
$Configb['Bclave']  = 'descripcion'; // esta el campo  clave de busqueda.....
$Configb['Bclave1']  = 'procedimiento'; // esta el campo  clave de busqueda.....
$Configb['Bncampo']="procedimiento,descripcion";//Aqui el nombre de los campos
$Configb['Bnombres']="Codigo Procedimiento";//Aqui se coloca como quiere que salga el nombre
//de sus campos en la base de datos se colocan separado por espacios..
$Configb['Bcol1']="#DDDDDD";//color de intercalado definifo
$Configb['Bcol2']="#CCCCCC";//color de intercalado definifo
$Configb['Bcab']="#D3DCE3";//color de la cabecera de la tabla..
$Configb['BNhidden']="2";//numero de hiddens a crear
if($spia=='codigos')
{

$Configb['Bx']="codigos procedimiento";//
}else{$Configb['Bx']="codigo procedimiento";}
//echo $_REQUEST['buscar'];
//echo $_REQUEST['buscar2'];
$dato=new buscador;
$s=$Configb['Bx'];
$form=$_SESSION['FORMA']; //nombre de la forma para pasar los datos...
abre_html();
$dato->imprime_java($s,$form);
$dato->recibir_dato($Configb);
cierra_html();
break;
*/
	case "cargos": //ultimo arreglo del buscador

	$pfj=$_SESSION['PREFIJO'];
	$form=$_SESSION['FORMA']; //nombre de la forma para pasar los datos...
	$Configb['Bnombres']="CODIGO NOMBRE";//Aqui se coloca como quiere que salga el nombre
	$Configb['Bcampos']  = '2';
	$Configb['Bncampo']="cargo,descripcion,tarifario_id";//Aqui el nombre de los campos
	$hola=explode(",",$_SESSION['key']);
	$Configb['Bclave']=$hola[0];
	$Configb['Bclave1']=$hola[1];
	$Configb['Bcol1']="#E6E6CC";//color de intercalado definifo
	$Configb['Bcol2']="#F3F3E9";//color de intercalado definifo
	$Configb['Bcab']="#CCCCCC";//color de la cabecera de la tabla..
	$Configb['BNhidden']="3";//numero de hiddens a crear
	$Configb['Bx']=$form."CTipoCargo ".$form."CNombreCargo ".$form."CTarifario";//
	$dato=new buscador;
	$s=$Configb['Bx'];
	abre_html1();
	$dato->imprime_java($s,$form);
	$spi=$_REQUEST['spi'];

	if($spi==true)
	{
	$Configb['spi']=$_REQUEST['spi'];
	$Configb['conteo']=$_REQUEST['conteo'];
	$Configb['Of']=$_REQUEST['Of'];
	$Configb['buscar']=$_REQUEST['buscar'];
	$Configb['key1']=$_REQUEST['key1'];
	}
	$dato->rec_datos($Configb);
	cierra_html();
	break;

case "planT":
$form=$_SESSION['FORMA']; //nombre de la forma para pasar los datos...
$Configb['Bcampos']  = '9';//numero de campos que posee la tabla para la muestra
$hola=explode(",",$_SESSION['key']);
$Configb['Bclave']=$hola[0]; // esta el campo  clave de busqueda.....
$Configb['Bclave1']=$hola[1]; // esta el campo  clave de busqueda.....
$Configb['Bnombres']="POS CODIGO NOMBRE PRESENTACIÓN FORMULAFARMA CONCENTRACIÓN PRINCIPIO_ACTIVO UNIDAD BODEGA";//Aqui se coloca como quiere que salga el nombre de sus campos en la base de datos se colocan separado por espacios..
$Configb['Bcol1']="#DDDDDD";//color de intercalado definifo
$Configb['Bcol2']="#CCCCCC";//color de intercalado definifo
$Configb['Bcab']="#D3DCE3";//color de la cabecera de la tabla..
$Configb['BNhidden']="9";//numero de hiddens a crear
if($spia)
	$Configb['Bx']=$form."EsPos$spia ".$form."IdMedicamento$spia ".$form."NombreMedicamento$spia ".$form."PresentMedicamento$spia ".$form."FormFarmMedicamento$spia ".$form."ConcMedicamento$spia ".$form."PrincipioActivo$spia ".$form."Unidad$spia ".$form."Bodega$spia";//
else
	$Configb['Bx']=$form."EsPos ".$form."IdMedicamento ".$form."NombreMedicamento ".$form."PresentMedicamento ".$form."FormFarmMedicamento ".$form."ConcMedicamento ".$form."PrincipioActivo ".$form."Unidad ".$form."Bodega";//

$dato=new buscador;
$s=$Configb['Bx'];
abre_html1();
$dato->imprime_java($s,$form);
$spi=$_REQUEST['spi'];

if($spi==true)
{
$Configb['spi']=$_REQUEST['spi'];
$Configb['conteo']=$_REQUEST['conteo'];
$Configb['Of']=$_REQUEST['Of'];
$Configb['buscar']=$_REQUEST['buscar'];
$Configb['key1']=$_REQUEST['key1'];
}
$dato->rec_datos2($Configb);
cierra_html();
break;

//ARLEY NO SE ACUERDA POR QUE  ESTE CASO
/*case "Mezclas":
$form=$_SESSION['FORMA']; //nombre de la forma para pasar los datos...
//$Configb['Btabla']  = 'medicamentos_bodega'; //nombre de la tabla de busqueda
$Configb['Bcampos']  = '8';//numero de campos que posee la tabla para la muestra
$hola=explode(",",$_SESSION['key']);
$Configb['Bclave']=$hola[0]; // esta el campo  clave de busqueda.....
$Configb['Bclave1']=$hola[1]; // esta el campo  clave de busqueda.....
$Configb['Bnombres']="CODIGO NOMBRE PRESENTACIÓN FORMULAFARMA CONCENTRACIÓN PRINCIPIO_ACTIVO UNIDAD BODEGA";//Aqui se coloca como quiere que salga el nombre de sus campos en la base de datos se colocan separado por espacios..
$Configb['Bcol1']="#DDDDDD";//color de intercalado definifo
$Configb['Bcol2']="#CCCCCC";//color de intercalado definifo
$Configb['Bcab']="#D3DCE3";//color de la cabecera de la tabla..
$Configb['BNhidden']="8";//numero de hiddens a crear
$Configb['Bx']=$form."IdMedicamento ".$form."NombreMedicamento ".$form."PresentMedicamento ".$form."FormFarmMedicamento ".$form."ConcMedicamento ".$form."PrincipioActivo ".$form."Unidad ".$form."Bodega";//
$dato=new buscador;
$s=$Configb['Bx'];
abre_html1();
$dato->imprime_java($s,$form);
$spi=$_REQUEST['spi'];

if($spi==true)
{
$Configb['spi']=$_REQUEST['spi'];
$Configb['conteo']=$_REQUEST['conteo'];
$Configb['Of']=$_REQUEST['Of'];
$Configb['buscar']=$_REQUEST['buscar'];
$Configb['key1']=$_REQUEST['key1'];
}

$dato->rec_datos($Configb);
cierra_html();
break;
*/
	//darling para el buscador de cargos
	case "InsertarCargo":                    //ultimo arreglo del buscador
	if($_REQUEST['departamento']){
		unset($_SESSION['BUSCADOR']['DEPTO']);
		$_SESSION['BUSCADOR']['DEPTO']=$_REQUEST['departamento'];
	}
	
	$Configb['Bcampos']  = '2';//6numero de campos que posee la tabla para la muestra
	$Configb['Bclave']='cargo'; // esta el campo  clave de busqueda.....
	$Configb['Bclave1']='descripcion'; // esta el campo  clave de busqueda.....
	//$Configb['Bnombres']="DESCRIPCION TARIFARIO GRUPO SUBGRUPO CARGO PRECIO COBERTURA GRAVAMEN PORCENTAJE SWCANTIDAD";//Aqui se coloca como quiere que salga el nombre de sus campos en la base de datos se colocan separado por espacios..
	//$Configb['Bnombres']="DESCRIPCION GRUPO SUBGRUPO PRECIO CARGO SWCANTIDAD";//Aqui se coloca como quiere que salga el nombre de sus campos en la base de datos se colocan separado por espacios..
	$Configb['Bnombres']="DESCRIPCION CARGO";//Aqui se coloca como quiere que salga el nombre de sus campos en la base de datos se colocan separado por espacios..

	$Configb['Bcol1']="#DDDDDD" ;   //"#7588A4";//color de intercalado definifo
	$Configb['Bcol2']="#CCCCCC";//color de intercalado definifo
	$Configb['Bcab']="#D3DCE3";//color de la cabecera de la tabla..
	$Configb['BNhidden']="10";//numero de hiddens a crear
	//$Configb['Bx']="Descripcion GrupoTarifario SubGrupoTarifario Precio Cargo Swcantidad";//
	$Configb['Bx']="Descripcion Cargo";//
	$Configb['plan']=$_SESSION['plan'];
	$Configb['tiposolicitud']=$_SESSION['SQL'];
	$dato=new buscador;
	$s=$Configb['Bx'];
	$form=$_SESSION['FORMA']; //nombre de la forma para pasar los datos...
	abre_html();
	$dato->imprime_java($s,$form);
	$spi=$_REQUEST['spi'];

	if($spi==true)
	{
	$Configb['spi']=$_REQUEST['spi'];
	$Configb['conteo']=$_REQUEST['conteo'];
	$Configb['Of']=$_REQUEST['Of'];
	$Configb['buscar']=$_REQUEST['buscar'];
	$Configb['key1']=$_REQUEST['key1'];
	}
	$dato->RecibeDatos($Configb);
	cierra_html();
	break;


case "InsertarInsumos":                     //ultimo arreglo del buscador
$Configb['Bcampos']  = '6';//numero de campos que posee la tabla para la muestra
$Configb['Bclave']='codigo_producto'; // esta el campo  clave de busqueda.....
$Configb['Bclave1']='descripcion'; // esta el campo  clave de busqueda.....
$Configb['Bnombres']="CODIGO DESCRIPCION PRECIO %GRAVAMEN PRESENTACION MEDICAMENTO";//Aqui se coloca como quiere que salga el nombre de sus campos en la base de datos se colocan separado por espacios..
$Configb['Bcol1']="#DDDDDD" ;   //"#7588A4";//color de intercalado definifo
$Configb['Bcol2']="#CCCCCC";//color de intercalado definifo
$Configb['Bcab']="#D3DCE3";//color de la cabecera de la tabla..
$Configb['BNhidden']="10";//numero de hiddens a crear
$Configb['Bx']="Codigo Descripcion Precio Gravamen";//
$Configb['plan']=$_SESSION['plan'];
//$Configb['empresa']=$_SESSION['SQL'];
$Configb['bodega']=$_SESSION['BODEGA'];
$Configb['empresa']=$_SESSION['EMPRESA'];
$Configb['cu']=$_SESSION['CU'];
$dato=new buscador;
$s=$Configb['Bx'];
$form=$_SESSION['FORMA']; //nombre de la forma para pasar los datos...
abre_html();
$dato->imprime_java($s,$form);
$spi=$_REQUEST['spi'];

if($spi==true)
{
$Configb['spi']=$_REQUEST['spi'];
$Configb['conteo']=$_REQUEST['conteo'];
$Configb['Of']=$_REQUEST['Of'];
$Configb['buscar']=$_REQUEST['buscar'];
$Configb['key1']=$_REQUEST['key1'];
}
$dato->RecibeDatosInsumos($Configb);
cierra_html();
break;


case "BuscarCargo":
//darling
$Configb['Bcampos']  = '2';
$Configb['Bncampo']="cargo,descripcion,tarifario_id";//Aqui el nombre de los campos
$Configb['Bclave']='cargo'; // esta el campo  clave de busqueda.....
$Configb['Bclave1']='descripcion'; // esta el campo  clave de busqueda.....
$Configb['Bnombres']="CODIGO DESCRIPCION";//Aqui se coloca como quiere que salga el nombre de sus campos en la base de datos se colocan separado por espacios..
$Configb['Bcol1']="#DDDDDD" ;   //"#7588A4";//color de intercalado definifo
$Configb['Bcol2']="#CCCCCC";//color de intercalado definifo
$Configb['Bcab']="#D3DCE3";//color de la cabecera de la tabla..
$Configb['BNhidden']="4";//numero de hiddens a crear
$Configb['Bx']="Codigo Cargo TarifarioId";//
$Configb['plan']=$PLAN;
$dato=new buscador;
$s=$Configb['Bx'];
$form=$_SESSION['FORMA']; //nombre de la forma para pasar los datos...
abre_html();
$dato->imprime_java($s,$form);
$spi=$_REQUEST['spi'];

if($spi==true)
{
$Configb['spi']=$_REQUEST['spi'];
$Configb['conteo']=$_REQUEST['conteo'];
$Configb['Of']=$_REQUEST['Of'];
$Configb['buscar']=$_REQUEST['buscar'];
$Configb['key1']=$_REQUEST['key1'];
}
$dato->rec_datos($Configb);
cierra_html();
break;
/*
case "procedimiento":
$Configb['Btabla']  = '2'; //nombre de la tabla de busqueda
//$Configb['Bcampos']  = '2';//numero de campos que posee la tabla para la muestra
$Configb['Bclave']  = 'codigo'; // esta el campo  clave de busqueda.....
$Configb['Bclave1']  = 'procedimiento'; // esta el campo  clave de busqueda.....
//$Configb['Bncampo']="procedimiento,descripcion";//Aqui el nombre de los campos
$Configb['Bnombres']="CODIGO PROCEDIMIENTO";//Aqui se coloca como quiere que salga el nombre
//de sus campos en la base de datos se colocan separado por espacios..
$Configb['Bcol1']="#DDDDDD";//color de intercalado definifo
$Configb['Bcol2']="#CCCCCC";//color de intercalado definifo
$Configb['Bcab']="#D3DCE3";//color de la cabecera de la tabla..
$Configb['BNhidden']="2";//numero de hiddens a crear
$Configb['Bx']="codigo procedimiento";
$dato=new buscador;
$s=$Configb['Bx'];
$form=$_SESSION['FORMA']; //nombre de la forma para pasar los datos...
abre_html();
$dato->imprime_java($s,$form);
$dato->Rec_Data($Configb);
cierra_html();
break;
*/
case "procedimiento":
$Configb['Bnombres']="CODIGO PROCEDIMIENTO";//Aqui se coloca como quiere que salga el nombre
$Configb['Bcampos']  = '2';
$hola=explode(",",$_SESSION['key']);
$Configb['Bclave']=$hola[0];
$Configb['Bclave1']=$hola[1];
$Configb['Bncampo']="cargo, descripcion, tarifario_id, grupo_tipo_cargo";//Aqui el nombre de los campos
$Configb['Bcol1']="#E6E6CC";//color de intercalado definifo
$Configb['Bcol2']="#F3F3E9";//color de intercalado definifo
$Configb['Bcab']="#CCCCCC";//color de la cabecera de la tabla..
$Configb['BNhidden']="4";//numero de hiddens a crear
$Configb['Bx']="codigos procedimiento tarifario_id grupo_tipo_cargo";
$dato=new buscador;
$s=$Configb['Bx'];
$form=$_SESSION['FORMA']; //nombre de la forma para pasar los datos...
abre_html1();

										//PREGUNTAR A ARLEY SI ES NECESARIO USAR LA FUNC ABRE_HTML
										//* QUE EL FOCUS NO FUNCIONA BIEN..................

$dato->imprime_java($s,$form);
$spi=$_REQUEST['spi'];
if($spi==true)
{
$Configb['spi']=$_REQUEST['spi'];
$Configb['conteo']=$_REQUEST['conteo'];
$Configb['Of']=$_REQUEST['Of'];
$Configb['buscar']=$_REQUEST['buscar'];
$Configb['key1']=$_REQUEST['key1'];
}
$dato->rec_datos($Configb);
cierra_html();
break;

}
		?>
</body>
</html>
