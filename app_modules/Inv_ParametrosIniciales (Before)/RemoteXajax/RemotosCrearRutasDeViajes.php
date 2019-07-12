<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: RemotosCrearRutasDeViajes.php
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
    
  function ZonasT($CodigoPais)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasCrearRutasDeViajes","classes","app","Inv_ParametrosIniciales");
  
  $Zonas=$sql->Listar_Zonas("1");
  
      $html .= "<fieldset class=\"fieldset\">\n";
      $html .= "  <legend class=\"normal_10AN\">ZONAS GEOGRAFICAS</legend>\n";
          
      $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td width=\"7%\">CODIGO</td>\n";
      $html .= "      <td width=\"25%\">DESCRIPCION</td>\n";
      $html .= "      <td width=\"20%\">ESTADO</td>\n";
      $html .= "      <td width=\"20%\">MODIFICAR</td>\n";
      
      
      $html .= "    </tr>\n";

          $est = "modulo_list_claro";
          $bck = "#DDDDDD";
          foreach($Zonas as $key => $z)
          {
            ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
            ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

            $html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
            $html .= "      <td >".$z['zona_id']."</td><td>".$z['descripcion']." </td>\n";
                    
        if($z['estado']==1)
          {
          $html .= "<td align=\"center\">
                <a href=\"#\" onclick=\"xajax_CambioEstado('inv_zonas','estado','0','".$z['zona_id']."','zona_id','".$CodigoPais."')\">\n";
          $html .="<img title=\"INACTIVAR\" src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></a></td>\n";
          }
          else
            {
            $html .= "<td align=\"center\"><a href=\"#\" onclick=\"xajax_CambioEstado('inv_zonas','estado','1','".$z['zona_id']."','zona_id','".$CodigoPais."')\">\n";
            $html .="<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></td>\n";
            }
                      
        $html .= "      <td align=\"center\">\n";
            $html .= "        <a href=\"#\" onclick=\"xajax_ModificarZonas('".$z['zona_id']."','".$CodigoPais."')\">\n";
            $html .= "          <img title=\"MODIFICAR\" src=\"".GetThemePath()."/images/modificar.png\" border=\"0\">\n";
                                           // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
            $html .= "        </a>\n";
            $html .= "      </td>\n";
            
            
          }
          
          
          
          
          $html .= "    </table>\n";
          $html .= "</fieldset><br>\n";
          
          $objResponse->assign("ListadoZonas","innerHTML",$objResponse->setTildes($html));
          return $objResponse;
          
  }
  
   function RutasViajesT($CodigoPais,$offset)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasCrearRutasDeViajes","classes","app","Inv_ParametrosIniciales");
  
  $RutasViajes=$sql->Listar_RutasViajes($offset);
  
  
  $action['paginador'] = "Paginador('".$CodigoPais."'";
        
        
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);
  
      $html .= "<fieldset class=\"fieldset\">\n";
      $html .= "  <legend class=\"normal_10AN\">RUTAS DE VIAJES</legend>\n";
          
      $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td width=\"7%\">CODIGO DE RUTA</td>\n";
      $html .= "      <td width=\"25%\">NOMBRE DE LA RUTA</td>\n";
      $html .= "      <td width=\"25%\">EMPRESA ORIGEN</td>\n";
      $html .= "      <td width=\"25%\">UBICACION</td>\n";
      $html .= "      <td width=\"25%\">DIRECCION</td>\n";
      $html .= "      <td width=\"20%\">ESTADO</td>\n";
      $html .= "      <td width=\"20%\">MODIFICAR</td>\n";
      $html .= "      <td width=\"20%\">CONFIGURAR RUTA</td>\n";
      
      
      $html .= "    </tr>\n";

          $est = "modulo_list_claro";
          $bck = "#DDDDDD";
          foreach($RutasViajes as $key => $rv)
          {
            ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
            ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

            $html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
            
            $html .= "      
            <td>".$rv['rutaviaje_origen_id']."</td>
            <td>".$rv['descripcion']." </td>
            <td>".$rv['razon_social']." </td>
            <td>".$rv['pais']."-".$rv['departamento']."-".$rv['municipio']." </td>
            <td>".$rv['direccion']." </td>
            \n";
            
                    
        if($rv['estado']==1)
          {
          $html .= "<td align=\"center\">
                <a href=\"#\" onclick=\"xajax_CambioEstadoRutaViaje('inv_rutasviajes_origen','estado','0','".$rv['rutaviaje_origen_id']."','rutaviaje_origen_id')\">\n";
          $html .="<img title=\"INACTIVAR\" src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></a></td>\n";
          }
          else
            {
            $html .= "<td align=\"center\"><a href=\"#\" onclick=\"xajax_CambioEstadoRutaViaje('inv_rutasviajes_origen','estado','1','".$rv['rutaviaje_origen_id']."','rutaviaje_origen_id')\">\n";
            $html .="<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></td>\n";
            }
                      
            $html .= "      <td align=\"center\">\n";
            $html .= "        <a href=\"#\" onclick=\"xajax_ModificarRutaViaje('".$rv['rutaviaje_origen_id']."')\">\n";
            $html .= "          <img title=\"MODIFICAR\" src=\"".GetThemePath()."/images/modificar.png\" border=\"0\">\n";
                                           // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
            $html .= "        </a>\n";
            $html .= "      </td>\n";
            
            $html .= "      <td align=\"center\">\n";
            $html .= "        <a href=\"#Configurar\" onclick=\"xajax_ConfigurarRutaViaje('".$rv['rutaviaje_origen_id']."','".$rv['descripcion']."','".$rv['razon_social']."')\">\n";
            $html .= "          <img title=\"CONFIGURAR UNA RUTA DE VIAJE\" src=\"".GetThemePath()."/images/ambulancia.png\" border=\"0\">\n";
                                           // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
            $html .= "        </a>\n";
            $html .= "      </td>\n";
            }
          
          
          
          
          $html .= "    </table>\n";
          $html .= "</fieldset><br>\n";
          
          $objResponse->assign("ListadoRutasViajes","innerHTML",$objResponse->setTildes($html));
          return $objResponse;
          
  }
    
  function AsignarDepartamentosZonas($CodigoPais)
  {
  $objResponse = new xajaxResponse();
  
  $sql = AutoCarga::factory("ConsultasCrearRutasDeViajes","classes","app","Inv_ParametrosIniciales");
  
  $Zonas=$sql->Listar_Zonas("0");
  $SelectZona .= "<Select name=\"Zonas\" id=\"Zonas\" onChange=\"xajax_VerZona(this.value,'".$CodigoPais."','')\" class=\"select\" size=\"1\" style=\"width:40%;height:40%\" >";
  $SelectZona .="<option value=\"\" \">--Seleccionar--</option>";
  foreach($Zonas as $key => $z)
        {
        $SelectZona .="<option value='".$z['zona_id']."' >";
        $SelectZona .=$z['zona_id']." - ".$z['descripcion'];
        $SelectZona .="</option>";
        }
  $SelectZona .= "</Select>";
  
  $Departamentos=$sql->Listar_Departamentos($CodigoPais);
  
  $SelectDepartamentos .= "<Select name=\"tipo_dpto_id\" id=\"tipo_dpto_id\" class=\"select\" size=\"1\" style=\"width:40%;height:40%\" onchange=\"xajax_SeleccionarMpio('".$CodigoPais."',this.value)\">";
  $SelectDepartamentos .="<option value=\"\">--Seleccionar--</option>";
  foreach($Departamentos as $key => $d)
        {
        $SelectDepartamentos .="<option value='".$d['tipo_dpto_id']."'\">";
        $SelectDepartamentos .=$d['tipo_dpto_id']." - ".$d['departamento'];
        }
  $SelectDepartamentos .= "</Select>";
  
  $html .= "<form name=\"FormularioZonas\" id=\"FormularioZonas\">";
  $html .= "      <table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
  
  $html .= "<tr class=\"modulo_table_list_title\">";
  $html .="<td colspan=\"2\">SELECCIONE</td>";
  $html .="<input type=\"hidden\" name=\"tipo_pais_id\" id=\"tipo_pais_id\" value='".$CodigoPais."'>";
  $html .="</tr>"; 
    
  $html .="<td class=\"modulo_table_list_title\" width=\"40%\">SELECCIONA UNA ZONA</td><td width=\"50%\">".$SelectZona."</td>";
  $html .="</tr>";
  $html .="<tr>";
  $html .="<td class=\"modulo_table_list_title\">SELECCIONA UN DEPARTAMENTO:</td><td>".$SelectDepartamentos."</td>";
  $html .="</tr>";
  $html .="<tr>";
  $html .="<td class=\"modulo_table_list_title\">SELECCIONA UN MUNICIPIO:</td><td> 
              <DIV id=\"municipio\"> 
              <input name=\"tipo_mpio_id\" type=\"hidden\" value=\"\">
              </DIV></td>";
  $html .="</tr>";
  $html .="<tr>";
  $html .="<td colspan=\"2\" align=\"center\" class=\"modulo_table_list_title\"><input type=\"button\" class=\"modulo_table_list\" value=\"Adicionar a La Zona\" onclick=\"ValidarIngresoZona(xajax.getFormValues('FormularioZonas'))\"></td>";
  $html .="</tr>";
   
      
  $html .= "      </table>";
  $html .="</form>";
  
       $html .="<center><b>INFORMACION DE ZONA</b></center>";
		   $html .="<div id=\"ResultadoXZona\">";
      
      $Select .= "<center><Select name=\"ZonasConfiguradas\" id=\"ZonasConfiguradas\" class=\"select\" size=\"5\" style=\"width:65%;height:65%\" >";
      $Select .= "</Select></center>";
  $html .= $Select;
      $html .="</div>";
      
          $objResponse->assign("ZonasConfiguradas","innerHTML",$objResponse->setTildes($html));
          return $objResponse;
          
  }
  

  
  
  function InsertarConfigurarZonas($datos)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasCrearRutasDeViajes","classes","app","Inv_ParametrosIniciales");
  
  $token=$sql->InsertarConfigurarZonas($datos);
  
  if($token)
  {
  $objResponse->script("Cerrar('Contenedor');");
  $objResponse->script("xajax_VerZona('".$datos['Zonas']."','".$datos['tipo_pais_id']."','')");
  //$objResponse->alert("Ingreso Exitoso!!");
  }
   else
  $objResponse->alert("Error en el Ingreso... Es Posible que ya esté asignada a otra Zona!!");
  
  
  
  return $objResponse;
  }
  
  
  
  
  function VerZona($ZonaId,$CodigoPais,$Descripcion)
  {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasCrearRutasDeViajes","classes","app","Inv_ParametrosIniciales");
    
  $MunicipiosZona=$sql->Listar_MunicipiosZona($ZonaId,$CodigoPais);
    
  
  $Select .= "<center><Select name=\"ZonasMunicipios\" class=\"select\" size=\"5\" style=\"width:65%;height:65%\" >";
        foreach($MunicipiosZona as $key => $mz)
        {
        $Select .="<option value='".$mz['zona_mpio_id']."' ondblclick=\"xajax_BorrarMpioZona('inv_zonas_mpios','".$mz['zona_mpio_id']."','zona_mpio_id','".$ZonaId."','".$CodigoPais."')\">";
        $Select .="Zona : ".$mz['zona_id']."-(".$mz['descripcion'].")     Departamento :".$mz['departamento']."    Municipio: ".$mz['municipio'];
        }
  $Select .= "</Select></center>";
  
  $html = $Select;
    
  $objResponse->assign("ResultadoXZona","innerHTML",$objResponse->setTildes($html));
    
  return $objResponse;	
	}
 
  
  
  
  
  function SeleccionarMpio($Pais,$Departamento,$Municipio)
  {
  $objResponse = new xajaxResponse();
  
  $sql = AutoCarga::factory("ConsultasCrearRutasDeViajes","classes","app","Inv_ParametrosIniciales");
	
  if($Departamento=="")
  {
    $html .= "      <input name=\"tipo_mpio_id\" type=\"hidden\" value=\"\">";
  
  }
        else
        {
              $Municipios=$sql->ListarMunicipios($Pais,$Departamento);
              $select .="<select class=\"select\" name=\"tipo_mpio_id\">";
              $select .="<option value=\"\">";
              $select .="--Seleccionar--";
              $select .="</option>";
              foreach($Municipios as $key => $mpio)
                    {
              if($Municipio==$mpio['tipo_mpio_id'])
              $selected = " selected ";
              else
              $selected = " ";
                    $select .="<option value='".$mpio['tipo_mpio_id']."' ".$selected.">";
                    $select .=$mpio['tipo_mpio_id']."-".$mpio['municipio'];
                    $select .="</option>";
                    }
              $select .="</select>";
              
              $html .=$select;
          }
  
  
  $objResponse->assign("municipio","innerHTML",$objResponse->setTildes($html));
  return $objResponse;
  }
  
  
  
    
    
  function BorrarMpioZona($tabla,$id,$campo_id,$ZonaId,$CodigoPais)
  {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ClaseUtil"); //cargo Funcion General para cambiar de estado un registro.
    $token=$sql->Borrar_Registro($tabla,$id,$campo_id);
      
    
    
    if($token)
    {
    $objResponse->script("xajax_VerZona('".$ZonaId."','".$CodigoPais."');"); 
    //$objResponse->call("xajax_EstadosDocumentosT");
    }
 else
    $objResponse->alert("Error al Borrar!!"); 
    return $objResponse;	
	}
   
   
  function CrearRutasDeViaje($CodigoPais)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasEstadosDocumentos","classes","app","Inv_ParametrosIniciales");
    $html .="         <center>";
      $html .= "        <div id=\"ListadoRutasViajes\">\n"; 
      $html .= "								 </div>\n";
      $html .="         </center><br>";
      
      
      $html .="<a name=\"Configurar\">
      <center>";
      $html .= "        <div id=\"ConfigurarRutasViajes\">\n"; 
      $html .= "								 </div>\n";
      $html .="         </center><br>";
      
  $objResponse->script("xajax_RutasViajesT();");
  $objResponse->assign("CrearRutasViajes","innerHTML",$objResponse->setTildes($html));
  return $objResponse;
  }
  
  
  
  
 
  function CambioEstado($tabla,$campo,$valor,$id,$campo_id,$CodigoPais)
  {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ClaseUtil"); //cargo Funcion General para cambiar de estado un registro.
    $sql->Cambio_Estado($tabla,$campo,$valor,$id,$campo_id);
        
    $objResponse->script("xajax_ZonasT();");
    $objResponse->script("xajax_AsignarDepartamentosZonas('".$CodigoPais."');");
    $objResponse->script("xajax_CrearRutasDeViaje('".$CodigoPais."');");
    return $objResponse;	
	}
  
  
  function ModZonas($datos)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasCrearRutasDeViajes","classes","app","Inv_ParametrosIniciales");
  
  $token=$sql->ModificarZona($datos);
  if($token)
  {
  $objResponse->script("xajax_ZonasT('".$datos['tipo_pais_id']."');");
  $objResponse->script("xajax_AsignarDepartamentosZonas('".$datos['tipo_pais_id']."');");
  $objResponse->script("xajax_CrearRutasDeViaje('".$datos['tipo_pais_id']."');");
  $objResponse->call("Cerrar('Contenedor')");
  $objResponse->alert("Modificacion Exitosa!!");
  }
    else
        $objResponse->alert("Error al Modificar!!!");
  return $objResponse;
  }
 
 
 
  function ModificarZonas($ZonaId,$CodigoPais)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasCrearRutasDeViajes","classes","app","Inv_ParametrosIniciales");
  
  $Zona=$sql->Buscar_Zona($ZonaId);
  
  //Scripts Javascripts
  
  $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "  <tr><td>";
		//action del formulario= Donde van los datos del formulario.
		$html .= "  <form name=\"FormularioCreacionZonas\" id=\"FormularioCreacionZonas\" method=\"post\">";
		
		$html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"2\">";
		$html .= "      CREACION DE ZONAS GEOGRAFICAS";
		$html .= "      </td>";
		$html .= "      </tr>";
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      CODIGO ZONA :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .= '      <input style="width:100%;height:100%" class="input-text" type="Text" name="zona_id" maxlength="7" onkeyup="this.value=this.value.toUpperCase()" value="'.$Zona[0]['zona_id'].'">';
		$html .= "      </td>";
		$html .= "      </tr>";
		
		
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      DESCRIPCION :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .= '      <input style="width:100%;height:100%" class="input-text" type="Text" name="descripcion" maxlength="70" onkeyup="this.value=this.value.toUpperCase()" value="'.$Zona[0]['descripcion'].'">';
		$html .= "      <input type=\"hidden\" name=\"estado\" value=\"1\">";
    $html .= "      </td>";
		$html .= "      </tr>";
		
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
		$html .= '      <input type="hidden" name="token" value="0">';
    $html .= '      <input type="hidden" name="tipo_pais_id" id="tipo_pais_id" value="'.$CodigoPais.'">';    //esto es para definir si es Update o Insert
    $html .= '      <input type="hidden" name="zona_id_old" value="'.$Zona[0]['zona_id'].'">'; //esto es para definir si es Update o Insert
    $html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"Confirmar(xajax.getFormValues('FormularioCreacionZonas'))\">";
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "		</form>";
		$html .= "      </table>";
		  
  
  
    $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
    
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
  
  }
  
  

function InsertarZonas($datos)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasCrearRutasDeViajes","classes","app","Inv_ParametrosIniciales");
  
  $token=$sql->InsertarZonas($datos);
  
  if($token)
  {
  $objResponse->call("Cerrar('Contenedor')");
  $objResponse->script("xajax_ZonasT();");
  $objResponse->script("xajax_AsignarDepartamentosZonas('".$datos['tipo_pais_id']."');");
  $objResponse->script("xajax_CrearRutasDeViaje('".$datos['tipo_pais_id']."');");
  $objResponse->alert("Ingreso Exitoso!!");
  $objResponse->script("tabPane.setSelectedIndex('0');");
  }
  else
  $objResponse->alert("Error en el Ingreso... Revisa que no exista la Zona!!");
  
  
  
  return $objResponse;
  }


  function IngresoZonas($CodigoPais)
  {
  $objResponse = new xajaxResponse();
  		
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "  <tr><td>";
		//action del formulario= Donde van los datos del formulario.
		$html .= "  <form name=\"FormularioCreacionZonas\" id=\"FormularioCreacionZonas\" method=\"post\">";
		
		$html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"2\">";
		$html .= "      CREACION DE ZONAS GEOGRAFICAS";
		$html .= "      </td>";
		$html .= "      </tr>";
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      CODIGO ZONA :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .= '      <input style="width:100%;height:100%" class="input-text" type="Text" name="zona_id" maxlength="7" onkeyup="this.value=this.value.toUpperCase()" >';
		$html .= "      </td>";
		$html .= "      </tr>";
		
		
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      DESCRIPCION :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .= '      <input style="width:100%;height:100%" class="input-text" type="Text" name="descripcion" maxlength="70" onkeyup="this.value=this.value.toUpperCase()">';
		$html .= "      <input type=\"hidden\" name=\"estado\" value=\"1\">";
    $html .= "      </td>";
		$html .= "      </tr>";
		
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
		$html .= '      <input type="hidden" name="token" value="1">';
    $html .= '      <input type="hidden" name="tipo_pais_id" value="'.$CodigoPais.'">';    //esto es para definir si es Update o Insert
    $html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"Confirmar(xajax.getFormValues('FormularioCreacionZonas'))\">";
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "		</form>";
		$html .= "      </table>";
		  
  
  
    $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
  }
  
  
  
  function InsertarRutaViaje($datos)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasCrearRutasDeViajes","classes","app","Inv_ParametrosIniciales");
  
  $token=$sql->InsertarRutaViaje($datos);
  
  if($token)
  {
  $objResponse->call("Cerrar('Contenedor')");
  $objResponse->script("xajax_RutasViajesT();");
  $objResponse->script("xajax_AsignarDepartamentosZonas('".$datos['tipo_pais_id']."');");
  $objResponse->alert("Ingreso Exitoso!!");
  
  }
  else
  $objResponse->alert("Error en el Ingreso... Revisa que no exista la Ruta de Viaje!!");
  
  
  
  return $objResponse;
  }
  
  
  
  
   function IngresoRutaViaje($CodigoPais)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasCrearRutasDeViajes","classes","app","Inv_ParametrosIniciales");
  $ListaEmpresa = $sql->Listar_Empresas(); 

  $SelectEmpresa .="<select class=\"select\" name=\"empresa_id\" style=\"width:100%;height:100%\" onchange=\"xajax_InformacionEmpresa(this.value,'InfoEmpresa');\">";
              $SelectEmpresa .="<option value=\"\">";
              $SelectEmpresa .="--Seleccionar--";
              $SelectEmpresa .="</option>";
              foreach($ListaEmpresa as $key => $le)
                    {
                    $SelectEmpresa .="<option value='".$le['empresa_id']."' >";
                    $SelectEmpresa .=$le['empresa_id']."-".$le['empresa'];
                    $SelectEmpresa .="</option>";
                    }
              $SelectEmpresa .="</select>";
                              
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "  <tr><td>";
		//action del formulario= Donde van los datos del formulario.
		$html .= "  <form name=\"FormularioCreacionRutasViajes\" id=\"FormularioCreacionRutasViajes\" method=\"post\">";
		
		$html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"2\">";
		$html .= "      CREACION DE RUTAS DE VIAJES";
		$html .= "      </td>";
		$html .= "      </tr>";
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      CODIGO RUTA DE VIAJE :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"40%\">";
		$html .= '      <input style="width:100%;height:100%" class="input-text" type="Text" name="rutaviaje_origen_id" maxlength="4" onkeyup="this.value=this.value.toUpperCase()" >';
		$html .= "      </td>";
		$html .= "      </tr>";
		
		
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      NOMBRE DE LA RUTA :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .= '      <input style="width:100%;height:100%" class="input-text" type="Text" name="descripcion" maxlength="60" onkeyup="this.value=this.value.toUpperCase()">';
		$html .= "      </td>";
		$html .= "      </tr>";
		
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      EMPRESA ORIGEN DE RUTA :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .=        $SelectEmpresa;
    $html .= "      </td>";
		$html .= "      </tr>";
    
   $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\" colspan=\"2\">";
		$html .= "      INFORMACION :";
		$html .= "      <div id=\"InfoEmpresa\"></div>";
		$html .= "      </td>";
		$html .= "      </tr>"; 
    
    
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
		$html .= '      <input type="hidden" name="token" value="1">';
    $html .= "      <input type=\"hidden\" name=\"estado\" value=\"1\">";
    $html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"Confirmar2(xajax.getFormValues('FormularioCreacionRutasViajes'))\">";
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "		</form>";
		$html .= "      </table>";
		  
  
  
    $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
  }
  
 


 function ModificarRutaViaje($RutaViaje_Origen_Id)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasCrearRutasDeViajes","classes","app","Inv_ParametrosIniciales");
  $RutaViaje = $sql->Buscar_RutaViaje($RutaViaje_Origen_Id); 
  $ListaEmpresa = $sql->Listar_Empresas();  

  $SelectEmpresa .="<select class=\"select\" name=\"empresa_id\" style=\"width:100%;height:100%\" onchange=\"xajax_InformacionEmpresa(this.value,'InformacionEmpresa');\">";
              $SelectEmpresa .="<option value=\"\">";
              $SelectEmpresa .="--Seleccionar--";
              $SelectEmpresa .="</option>";
              $selected ="";
              foreach($ListaEmpresa as $key => $le)
                    {
                    if($RutaViaje[0]['empresa_id']==$le['empresa_id'])
                    $selected="selected";
                    else
                    $selected="";
                    
                    $SelectEmpresa .="<option value='".$le['empresa_id']."' ".$selected." >";
                    $SelectEmpresa .=$le['empresa_id']."-".$le['empresa'];
                    $SelectEmpresa .="</option>";
                    }
              $SelectEmpresa .="</select>";
                              
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "  <tr><td>";
		//action del formulario= Donde van los datos del formulario.
		$html .= "  <form name=\"FormularioCreacionRutasViajes\" id=\"FormularioCreacionRutasViajes\" method=\"post\">";
		
		$html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"2\">";
		$html .= "      MODIFICAR RUTA DE VIAJE";
		$html .= "      </td>";
		$html .= "      </tr>";
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      CODIGO RUTA DE VIAJE :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"40%\">";
		$html .= '      <input value="'.$RutaViaje[0]['rutaviaje_origen_id'].'" style="width:100%;height:100%" class="input-text" type="Text" name="rutaviaje_origen_id" maxlength="4" onkeyup="this.value=this.value.toUpperCase()" >';
		$html .= "      </td>";
		$html .= "      </tr>";
		
		
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      NOMBRE DE LA RUTA :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .= '      <input value="'.$RutaViaje[0]['descripcion'].'" style="width:100%;height:100%" class="input-text" type="Text" name="descripcion" maxlength="60" onkeyup="this.value=this.value.toUpperCase()">';
		$html .= "      </td>";
		$html .= "      </tr>";
		
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      EMPRESA ORIGEN DE RUTA :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .=        $SelectEmpresa;
    $html .= "      </td>";
		$html .= "      </tr>";
    
   $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\" colspan=\"2\">";
		$html .= "      INFORMACION :";
		$html .= "      <div id=\"InformacionEmpresa\"></div>";
		$html .= "      </td>";
		$html .= "      </tr>"; 
    
    
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
		$html .= '      <input type="hidden" name="token" value="0">';
    $html .= "      <input type=\"hidden\" name=\"rutaviaje_origen_id_old\" value='".$RutaViaje[0]['rutaviaje_origen_id']."'>";
    $html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"Confirmar2(xajax.getFormValues('FormularioCreacionRutasViajes'))\">";
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "		</form>";
		$html .= "      </table>";
		  
  
  
    $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
  }




   function ModRutaViaje($datos)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasCrearRutasDeViajes","classes","app","Inv_ParametrosIniciales");
  
  $token=$sql->ModificarRutaViaje($datos);
  if($token)
  {
  $objResponse->script("xajax_RutasViajesT();");
  $objResponse->script("Cerrar('Contenedor')");
  $objResponse->alert("Modificacion Exitosa!!");
  }
    else
        $objResponse->alert("Error al Modificar!!!");
  return $objResponse;
  }
  
  


 
   function InformacionEmpresa($Empresa_Id,$Div)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasCrearRutasDeViajes","classes","app","Inv_ParametrosIniciales");
  $Empresa = $sql->Buscar_Empresa($Empresa_Id);
		
    if($Empresa_Id!="")
    {
		$html .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
				
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"20%\">";
		$html .= "      Nombre :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"40%\">";
		$html .= '     '.$Empresa[0]['empresa'];
		$html .= "      </td>";
		$html .= "      </tr>";
		
		
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      Localizacion :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .= '      '.$Empresa[0]['pais']."-".$Empresa[0]['departamento']."-".$Empresa[0]['municipio'];
    $html .= "      </td>";
		$html .= "      </tr>";
		
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      Direccion :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .= '      '.$Empresa[0]['direccion'];
    $html .= "      </td>";
		$html .= "      </tr>";
		
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      Telefono :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .= '      '.$Empresa[0]['telefonos'];
    $html .= "      </td>";
		$html .= "      </tr>";
		 
    $html .= "      </table>";
		}
  
  
    $objResponse->assign($Div,"innerHTML",$objResponse->setTildes($html));
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    return $objResponse;
  }
  
  
  
  function CambioEstadoRutaViaje($tabla,$campo,$valor,$id,$campo_id)
  {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ClaseUtil"); //cargo Funcion General para cambiar de estado un registro.
    $sql->Cambio_Estado($tabla,$campo,$valor,$id,$campo_id);
        
    $objResponse->script("xajax_RutasViajesT();");
    return $objResponse;	
	}
  
  
  function ConfigurarRutaViaje($RutaViaje_Origen_Id,$Ruta,$EmpresaOrigen)
  {
  $objResponse = new xajaxResponse();
  
  $sql = AutoCarga::factory("ConsultasCrearRutasDeViajes","classes","app","Inv_ParametrosIniciales");
  
  $Zonas=$sql->Listar_Zonas("0");
  $SelectZona .= "<Select name=\"Zonas\" id=\"Zonas\" onChange=\"xajax_EmpresasXZonas(this.value,'')\" class=\"select\" size=\"1\" style=\"width:40%;height:40%\" >";
  $SelectZona .="<option value=\"\" \">--Seleccionar--</option>";
  foreach($Zonas as $key => $z)
        {
        $SelectZona .="<option value='".$z['zona_id']."' >";
        $SelectZona .=$z['zona_id']." - ".$z['descripcion'];
        $SelectZona .="</option>";
        }
  $SelectZona .= "</Select>";
  
  $html .= "<form name=\"FormularioRutas\" id=\"FormularioRutas\">";
  $html .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
  
  $html .= "<tr class=\"modulo_table_list_title\">";
  $html .="<td colspan=\"2\">CONFIGURAR RUTA:".$Ruta." CON ORIGEN EN:".$EmpresaOrigen."</td>";
  $html .="<td align=\"center\">INFO. EMPRESA SELECCIONADA</td>";
  $html .="</tr>"; 
    
  $html .="<td class=\"modulo_table_list_title\" width=\"25%\">SELECCIONA UNA ZONA</td><td width=\"35%\">".$SelectZona."</td>";
  $html .= "<td class=\"modulo_table_list\" width=\"60%\" rowspan=\"4\">
  <div id=\"InfoEmpresaSeleccionada\">.</div></td>";
  $html .="</tr>";
  
  
  $html .="<tr>";
  $html .="<td class=\"modulo_table_list_title\">SELECCIONA EMPRESA DESTINO:</td>
  <td><div id=\"EmpresaXZona\">Selecciona una Zona...</div></td>";
  
  
  $html .="</tr>";
  
  $html .="<tr>";
  $html .="<input type=\"hidden\" name=\"rutaviaje_origen_id\" value='".$RutaViaje_Origen_Id."'>" ;
  $html .="<td colspan=\"2\" align=\"center\" class=\"modulo_table_list_title\"><input type=\"button\" class=\"modulo_table_list\" value=\"Adicionar a La Zona\" onclick=\"ValidarIngresoRuta(xajax.getFormValues('FormularioRutas'))\"></td>";
  $html .="</tr>";
   
      
  $html .= "      </table>";
  $html .="</form>";
  
       $html .="<center><b>INFORMACION DE RUTA</b></center>";
		   $html .="<div id=\"ResultadoXRuta\">";
      
      $Select .= "<center>";
      $Select .= "</center>";
  $html .= $Select;
      $html .="</div>";
      
          $objResponse->assign("ConfigurarRutasViajes","innerHTML",$objResponse->setTildes($html));
          $objResponse->script("xajax_MostrarInfoRuta('".$RutaViaje_Origen_Id."');");
          return $objResponse;
          
  }
  
  function EmpresasXZonas($ZonaId)
  {
  $objResponse = new xajaxResponse();
  
  $sql = AutoCarga::factory("ConsultasCrearRutasDeViajes","classes","app","Inv_ParametrosIniciales");
	
  if($ZonaId=="")
  {
    $html .= "Selecciona una Zona...";
  
  }
        else
        {
              $EmpresasXZonas=$sql->EmpresasXZona($ZonaId);
              $select .="<select class=\"select\" name=\"empresa_id\"  onchange=\"xajax_InformacionEmpresa(this.value,'InfoEmpresaSeleccionada')\">";
              $select .="<option value=\"\">";
              $select .="--Seleccionar--";
              $select .="</option>";
              foreach($EmpresasXZonas as $key => $exz)
                    {
              
                    $select .="<option value='".$exz['empresa_id']."'>";
                    $select .=$exz['empresa_id']."-".$exz['razon_social'];
                    $select .="</option>";
                    }
              $select .="</select>";
              
              $html .=$select;
          }
  
  
  $objResponse->assign("EmpresaXZona","innerHTML",$objResponse->setTildes($html));
  return $objResponse;
  }
  
  
   
  function InsertarConfigurarRuta($datos)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasCrearRutasDeViajes","classes","app","Inv_ParametrosIniciales");
  
  $token=$sql->InsertarConfigurarRuta($datos);
  
  if($token)
  {
  $objResponse->script("Cerrar('Contenedor');");
  $objResponse->script("xajax_MostrarInfoRuta('".$datos['rutaviaje_origen_id']."')");
  //$objResponse->alert("Ingreso Exitoso!!");
  }
   else
  $objResponse->alert("Error en el Ingreso... Es Posible que ya esté Creada la Ruta -> Destino!!");
  
  
  
  return $objResponse;
  }
  
 function MostrarInfoRuta($RutaId)
  {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasCrearRutasDeViajes","classes","app","Inv_ParametrosIniciales");
    
  $InfoRuta=$sql->MostrarInfoRuta($RutaId);
    
  
  $Select .= "<center><Select name=\"InfoRutas\" class=\"select\" size=\"5\" style=\"width:65%;height:65%\" >";
        foreach($InfoRuta as $key => $ir)
        {
        $Select .="<option value='".$ir['descripcion']."' ondblclick=\"xajax_BorrarRuta('inv_rutasviajes_destinos','".$ir['codigo']."','rutaviaje_destinoempresa_id','".$RutaId."')\">";
        $Select .="Recorrido :".$ir['descripcion']."    Destino: ".$ir['razon_social'];
        }
  $Select .= "</Select></center>";
  
  $html = $Select;
    
  $objResponse->assign("ResultadoXRuta","innerHTML",$objResponse->setTildes($html));
    
  return $objResponse;	
	}
   
   
   function BorrarRuta($tabla,$id,$campo_id,$RutaId)
  {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ClaseUtil"); //cargo Funcion General para cambiar de estado un registro.
    $token=$sql->Borrar_Registro($tabla,$id,$campo_id);
      
    
    
    if($token)
    {
    $objResponse->script("xajax_MostrarInfoRuta('".$RutaId."');"); 
    }
 else
    $objResponse->alert("Error al Borrar!!"); 
    return $objResponse;	
	}
   
?>
