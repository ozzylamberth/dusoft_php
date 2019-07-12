  /**
    * Archivo Javascript encargado de crear el calendario
    *
    * @package IPSOFT-SIIS
    * @version $Revision: 1.2 $
    * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
    * @author Hugo F  Manrique
    */
  var label;
  var estilo;
  /**
    * Funcion donde se invoca el calendario
    * 
    * @param string campo Nombre del campo en la forma donde se pondra la fecha
    * @param string sep Idebtificador del separador
    * @param string dia Identificador del dia 
    * @param string mes Identificador del mes 
    * @param string anyo Identificador del año 
    */
  function CrearCalendario(campo,sep,dia,mes,anyo)
  {
    var html = ObtenerCalendario(campo,sep,dia,mes,anyo);
    document.getElementById("calendario_px"+campo).innerHTML = html;
    document.getElementById("calendario_px"+campo).style.visibility = "visible";
  }  
  /**
    * Funcion en la que se obtiene el ultimo dia de un mes dado
    *
    * @param string mes Identificador del mes 
    * @param string anyo Identificador del año 
    *
    * @return int ultimo_dia
    */
  function ObtenrUltimoDia(mes,anyo)
  { 
    
    var ultimo_dia = 28;
    flag = true;
    try
    {
      while(flag)
      {
        fec = new Date(anyo,mes,ultimo_dia+1);
        
        if(fec.getMonth() != mes)
          break; 
        ultimo_dia++;
      }
    }
    catch(error){}
    return ultimo_dia; 
  }
  /**
    * Funcion donde se obtiene el estilo que tendra la celda del dia en el calendario
    * dependiendo si se trata de un sabado, un domingo o un dia entre lunes y viernes
    *
    * @param string dia Identificador del dia 
    * @param string mes Identificador del mes 
    * @param string anyo Identificador del año 
    */
  function ObtenerEstilo(anyo,mes,dia)
  {
    fecha_actual = new Date();
    fecha_busqueda = new Date(anyo,mes,dia);
    
    if (fecha_actual.getFullYear() == anyo && fecha_actual.getMonth()  == mes && fecha_actual.getDate() == dia)
    {
      label = " style=\"color:#1C428A;text-decoration:none\" ";
      estilo = " class=\"modulo_table_list\" style=\"background:#FFFFFF\"";
    }
    else
    {
      switch(fecha_busqueda.getDay())
      {
        case 6:
          label = " style=\"color:#1C428A;text-decoration:none\" ";
          estilo = "class=\"modulo_list_oscuro\" ";
        break;
        case 0:
          label = "style=\"color:#FFFFFF;text-decoration:none\"";
          estilo = " class=\"modulo_table_list_title\" ";
        break;
        default:
          label = " style=\"color:#1C428A;text-decoration:none\" ";
          estilo = " class=\"modulo_list_claro\" ";
        break;
      }
    }
  }
  /**
    * Funcion donde se crea el html del calendario
    * 
    * @param string campo Nombre del campo en la forma donde se pondra la fecha
    * @param string sep Idebtificador del separador
    * @param string dia Identificador del dia 
    * @param string mes Identificador del mes 
    * @param string anyo Identificador del año 
    *
    * @return string salida
    */
  function ObtenerCalendario(campo,sep,dia,mes,anyo)
  {
    var meses = new Array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
    var fecha_actual = new Date();
    var anyo2;
    if(dia == '')
    {
      anyo = fecha_actual.getFullYear();
      mes = fecha_actual.getMonth();
      dia = fecha_actual.getDate();
      anyo2 = anyo;
    }
    else
    {
      if(anyo < 1971)
      {
        x = 1971 - anyo*1;
        m = parseInt(x/28) +1;
        anyo2 = anyo *m;
      }
      anyo2 = anyo;
    }
    
    var fecha = new Date(anyo2,mes,dia);
    var fecha2 = new Date(anyo2,mes,1);
    
    dia_semana = fecha2.getDay();
    if(dia_semana == 0) dia_semana = 7;
    
    ultimo_dia = ObtenrUltimoDia(mes,anyo2);	
    var m1 = "";
    (parseInt(mes) < 9)? m1 = "0"+(parseInt(mes)+1): m1 = parseInt(mes)+1;
    
    var salida = '';
    salida += "<table width=300 align=\"center\" cellspacing=1 cellpadding=0 border=0 class=\"modulo_table_list\">\n";
    salida += "	<tr>\n";
    salida += "		<td colspan=\"7\" align=\"center\" >\n";
    salida += "			<table width=\"100%\">\n";
    salida += "				<tr class=\"label\">\n";
    salida += "					<td><b>MES</b>\n";
    salida += "						<select class=\"select\" name=\"mes\" onChange=\"CrearCalendario('"+campo+"','"+sep+"',"+dia+",this.value,"+anyo+")\">\n";
    
    var sel = "";
    for(i=0; i<meses.length; i++)
    {
      (i == mes)? sel = "selected":sel = "";
      salida += "							<option value="+i+" "+sel+">"+meses[i]+"</option>\n";
    }
    salida += "						</select>\n";
    salida += "					</td>\n";
    salida += "					<td><b>AÑO </b>\n";
    salida += "						<select class=\"select\" name=\"year\" onChange=\"CrearCalendario('"+campo+"','"+sep+"',"+dia+","+mes+",this.value)\">\n";
    
    var anyo_actual = fecha_actual.getFullYear();
    
    for(i =anyo_actual  - 108 ; i<anyo_actual+20; i++)
    {
      (i == anyo)? sel = "selected":sel = "";
      salida += "							<option value="+i+" "+sel+">"+i+"</option>\n";
    }
    salida += "						</select>\n";
    salida += "					</td>\n";
    salida += "					<td width=\"10\" class=\"formulacion_table_list\">\n";
    salida += "					  <a title=\"CERRAR\" href=\"javascript:Ocultar_"+campo+"('')\" style=\"color:#FFFFFF\">X</a>\n";
    salida += "					</td>\n";
    salida += "				</tr>\n";
    salida += "			</table>\n";
    salida += "		</td>\n";
    salida += "	</tr>\n";
    salida += "	<tr class=\"formulacion_table_list\">\n";
    salida += "		<td width=14% >LUN</td>\n";
    salida += "	  <td width=14% >MAR</td>\n";
    salida += "	  <td width=14% >MIE</td>\n";
    salida += "	  <td width=14% >JUE</td>\n";
    salida += "	  <td width=14% >VIE</td>\n";
    salida += "	  <td width=14% >SAB</td>\n";
    salida += "		<td width=14% >DOM</td>\n";
    salida += "	</tr>\n";
    salida += "	<tr height=\"21\">\n";
    
    var fecha_dia = 1;
    for (i=1;i<=7;i++)
    {
      if (i < dia_semana)
      {
        salida += "		<td ></td>\n";
      } 
      else 
      {
        dia1 = fecha_dia;
        if(i < 10) dia1 = "0"+fecha_dia;
        
        ObtenerEstilo(anyo2,mes,fecha_dia);
        
        salida += "		<td align=center "+estilo+">\n";
        salida += "			<a "+label+" href=\"javascript:Ocultar_"+campo+"('"+dia1+sep+m1+sep+anyo+"')\"><b>"+dia1+"</b></a>\n";
        salida += "		</td>\n";
        fecha_dia++;
      }
    }
    salida += "	</tr>\n";
    
    dia_semana = 1;
    while (fecha_dia <= ultimo_dia)
    {
      if (dia_semana == 1)	salida += "	<tr height=\"21\">\n";
      
      dia1 = fecha_dia;
      if(fecha_dia < 10) dia1 = "0"+fecha_dia;
        
      ObtenerEstilo(anyo2,mes,fecha_dia);
      
      salida += "		<td align=center "+estilo+">\n";
      salida += "			<a "+label+" href=\"javascript:Ocultar_"+campo+"('"+dia1+sep+m1+sep+anyo+"')\"><b>"+dia1+"</b></a>\n";
      salida += "		</td>\n";
      fecha_dia++;
      dia_semana++;

      if (dia_semana == 8)
      {
        dia_semana = 1;
        salida += "		</tr>\n";
      }
    }

    if(dia_semana > 1)
    {
      for (j=dia_semana; j<=7; j++)
      {
        salida += "		<td></td>\n";
      }
    }

    salida += "	</tr>\n";
    salida += "</table>\n";
    
    return salida;
  }	