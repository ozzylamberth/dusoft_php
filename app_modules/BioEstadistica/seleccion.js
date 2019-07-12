// Emai: intersof@telesat.com.co                                                     //
// ---------------------------------------------------------------------------------//
// Autor: Jairo Duvan Diaz Martinez                																 //
//                                                                                //
// ------------------------------------------------------------------------------//
var f;
var v;





function MandarInformacion_A_VentanaHija(señal){

		if(señal=='1')
		{
				window.opener.document.data.ing.value=document.forma.code.value;
		}
 		else if(señal=='2')
		{
				window.opener.document.data.egreso.value=document.forma.code.value;
		}
		else if(señal=='3')
		{
			window.opener.document.data.finalidad.value=document.forma.code.value;
  	}
		document.forma.code.value="";
	  window.close();
}




function PasarInformacion(codigo,x)
{
	 var coma;
	 var arr;
	 coma=",";
	 if(x==true)
	 {
		document.forma.code.value +=coma.concat(codigo); 	
	 }
	 else
	 {
	 		var linea = new String();
			linea=document.forma.code.value;
	 		document.forma.code.value=linea.replace(coma.concat(codigo), "");
	 }
}