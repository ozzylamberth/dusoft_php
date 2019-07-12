
<html>
<head>
<title>VENTANA EJEMPLO</title>
<script languaje="javascript" src="selectorCiudad.js"></script>
<?
// ejemplo.php  09/12/2003
// ---------------------------------------------------------------------------------------//
// eHospital v 0.1                                                                       //
// Copyright (C) 2003 InterSoftware Ltda.                                               //
// Emai: intersof@telesat.com.co                                                       //
// -----------------------------------------------------------------------------------//
// Autor: Jairo Duvan Diaz Martinez,Darling Dorado,Lorena Aragón                     //                       //
// Proposito del Archivo: ejemplo de la busqueda de los paises de origen de los     //
// inscriptos,y permite adicionar departamentos y municipos.                        //
//                                                                                //
// ------------------------------------------------------------------------------//

  $VISTA='HTML';
	$_ROOT='../../';
	include_once $_ROOT.'includes/enviroment.inc.php';
/**
*
*         $pais=GetVarConfigAplication('DefaultPais');
*					$dpto=GetVarConfigAplication('DefaultDpto');
*					$ciudad=GetVarConfigAplication('DefaultMpio');
*
*
* Este arreglo se define desde el archivo 'CONFIGDB.PHP', con el fin que se deje por DEFAULT
* EL PAIS,DEPTO Y CIUDAD en donde esta residente el software SIIS.
*
* NOTA: estes es un ejemplo para que se pueda lograr la insercion desde la ventana pequeña a la grande
* en la ventana padre(padre) deberemos colocar los objetos del mismo nombre que estan
* en las funciones del buscador:
*
*
* npais --->Nombre del objeto donde se insertara el pais...ejemplo Colombia...
* pais  --->Hidden donde se  guarda el Codigo del pais... ejemplo CO, esto es para cuando se guarde.
* ndpto --->Nombre del departamento  ejemplo Valle del Cauca
* dpto  --->Hidden del codigo de departmanento que se guarda para cuando se quiere insertar en la tabla.
* nmpio --->Nombre del municipio/ciudad ejemplo..Cali
* mpio  --->Hidden del codigo de municipio que se guarda para cuando se quiere insertar en la tabla
*
*
* NOMBRE DE LA FORMA ---> Para poder que la funcion trabaje bien es necesario que el nombre de la forma
* del lugar de donde se esta llamando se llame ['forma'] ejemplo mi modulo es de ADMISIONES,
* necesito llamar la funcion, entonces es necesario que yo le coloque el nombre a mi forma, 'forma',para que
* pueda funcionar correctamente.
*
*
* Ademas para el buen funcionamiento del buscador es necesario aparte de lo anterior:
*
* - LLamar el archivo javascript donde estan las funciones del buscador:
*   <script languaje="javascript" src="selectorCiudad.js"></script>
* - al crear el boton 'BUSCAR', se le colocara el evento ONCLICK llamando
*   a la funcion del buscador por medio de la ventana emergente el codigo debera sera asi:
*
*  onClick="abrirVentana('UBICACION','selector.php',450,200,0,this.form)"
*/
?>
</head>
<center>
<form name=forma method=POST>
<table>
<tr>
  <td>
   Paises
  </td>
  <td>
    <input type=text name=npais value="<?echo $pais1?>" readonly>
    <input type=hidden name=pais value=<?echo $cod_pais?> >
  </td>
  <td>
   Departamento
  </td>
  <td>
    <input type=text name=ndpto value="<?echo $dpto1?>" readonly>
    <input type=hidden name=dpto value=<?echo $cod_dpto?>>
  </td>
  <td>
    Ciudad
  </td>
  <td>
    <input type=text name=nmpio value="<?echo $ciudad1?>" readonly>
   <input type=hidden name=mpio value=<?echo $cod_ciudad?> >
	</td>
  <td>
 <input type=submit name=Cambiar value=Cambiar onClick="abrirVentana('BUSCADOR_DESTINO','selector.php',450,200,0,this.form)">

	</td>
</tr>
</table>
</form>
</html>


