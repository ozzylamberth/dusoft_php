// Emai: intersof@telesat.com.co                                                       //
// -----------------------------------------------------------------------------------//
// Autor: Jairo Duvan Diaz Martinez, Darling Liliana Dorado M, Lorena Aragón                     //
// Proposito del Archivo: realizar la busqueda de los paises de origen de los       //
// pacientes,y permite adicionar departamentos y municipos.                        //
//                                                                                //
// ------------------------------------------------------------------------------//
var f;
var v;

function cambioGrupo(p,frm,emp){
    var ban=frm.bandera.value;
    window.location='ventanaClasificacion.php?grupo='+p+"&bandera="+ban+"&Empresa="+emp;

}

function cambioClase(frm,emp){
  var ban=frm.bandera.value;
  var p = frm.grupo.value;
	var x = frm.clasePr.value;
	window.location="ventanaClasificacion.php?grupo="+p+"&clasePr="+x+"&bandera="+ban+"&Empresa="+emp;
}

function cambioSubClase(frm,emp){
  var ban=frm.bandera.value;
  var p = frm.grupo.value;
	var x = frm.clasePr.value;
	var s = frm.subclase.value;
	window.location="ventanaClasificacion.php?grupo="+p+"&clasePr="+x+"&subclase="+s+"&bandera="+ban+"&Empresa="+emp;
}

function ValoresSeleccion(grupo,clasePr,subclase,frm,emp){
	var p=frm.grupo.options[frm.grupo.options.selectedIndex].value;
	var c=frm.clasePr.options[frm.clasePr.options.selectedIndex].value;
  var e=frm.subclase.options[frm.subclase.options.selectedIndex].value;
	if(frm.bandera.value != '1' && (window.opener.document.forma.grupo.value != p || window.opener.document.forma.clasePr.value!=c || window.opener.document.forma.subclase.value!=e)){
		window.opener.document.forma.codProducto.value=frm.serialNum.value;
	}
	if(p!=0 && c!=0 && e!=0){
 		window.opener.document.forma.NomGrupo.value = frm.grupo.options[frm.grupo.options.selectedIndex].text;
	  window.opener.document.forma.grupo.value = p;
		window.opener.document.forma.NomClase.value = frm.clasePr.options[frm.clasePr.options.selectedIndex].text;
    window.opener.document.forma.clasePr.value = c;
		window.opener.document.forma.NomSubClase.value = frm.subclase.options[frm.subclase.options.selectedIndex].text;
    window.opener.document.forma.subclase.value = e;
	}
	close();
}


function ValoresFabricante(x,y){
 		window.opener.document.forma.fabricante.value = y;
	  window.opener.document.forma.valorFab.value = x;
	  close();
}

function ParametrosBusqueda(grupo,clasePr,subclase,frm){
  var p=frm.grupo.options[frm.grupo.options.selectedIndex].value;
	var c=frm.clasePr.options[frm.clasePr.options.selectedIndex].value;
  var e=frm.subclase.options[frm.subclase.options.selectedIndex].value;
  if(p!=0){
	window.opener.document.forma.NomGrupo.value = frm.grupo.options[frm.grupo.options.selectedIndex].text;
	window.opener.document.forma.grupo.value = p;
	}else{
  window.opener.document.forma.grupo.value = p;
	window.opener.document.forma.NomGrupo.value='-------';
	}
  if(c!=0){
  window.opener.document.forma.NomClase.value = frm.clasePr.options[frm.clasePr.options.selectedIndex].text;
	window.opener.document.forma.clasePr.value = c;
	}else{
	window.opener.document.forma.NomClase.value='-------';
  window.opener.document.forma.clasePr.value = c;
	}
  if(e!=0){
  window.opener.document.forma.NomSubClase.value = frm.subclase.options[frm.subclase.options.selectedIndex].text;
	window.opener.document.forma.subclase.value = e;
	}else{
	window.opener.document.forma.NomSubClase.value='-------';
  window.opener.document.forma.subclase.value = e;
	}
	close();
}

function cambioUbicaNUno(n1,frm){
  document.forma.ubicacionid.value=n1;
	frm.submit();
}

function cambioUbicaNDos(frm){
	var x = frm.nivelDos.value;
  document.forma.ubicacionid.value=x;
	frm.submit();
}

function cambioUbicaNTres(frm){
	var y = frm.nivelTres.value;
	document.forma.ubicacionid.value=y;
	frm.submit();
}

function cambioUbicaNCuatro(frm){
	var z = frm.nivelCuatro.value;
	document.forma.ubicacionid.value=z;
	frm.submit();
}


function valorUbicacion(frm){
  valor=frm.ubicacionid.value;
  document.forma.ubicacionid.value=valor;
	frm.submit();
}







