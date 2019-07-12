<?php

/**
 * $Id: reporteLiquidacionQX_html.report.php,v 1.1 2006/11/20 18:33:35 lorena Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba formato HTML
 */

class reporteLiquidacionQX_html_report
{
	//VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
	var $datos;

	//PARAMETROS PARA LA CONFIGURACION DEL REPORTE
	//NO MODIFICAR POR EL MOMENTO - DELEN UN TIEMPITO PARA TERMINAR EL DESARROLLO
	var $title       = '';
	var $author      = '';
	var $sizepage    = 'leter';
	var $Orientation = '';
	var $grayScale   = false;
	var $headers     = array();
	var $footers     = array();

    //CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
    function reporteLiquidacionQX_html_report($datos=array())
    {
		    
		    $this->datos=$datos;
        return true;
    }

// 	//METODO PRIVADO NO MODIFICAR
// 	function GetParametrosReport()
// 	{
// 		$parametros = array('title' => $this->title,'author' => $this->author,'sizepage' => $this->sizepage,'Orientation'=> $this->Orientation,'grayScale' => $this->grayScale,'headers' => $this->headers,'footers' =>$this->footers )
// 		return $parametros;
// 	}
//
//

	//FUNCION GetMembrete() - SI NO VA UTILIZAR MEMBRETE EXTERNO PUEDE BORRAR ESTE METODO
	//RETORNA EL MEMBRETE DEL DOCUMENTO
	//
	// SI RETORNA FALSO SIGNIFICA EL REPORTE NO UTILIZA MEMBRETE EXTERNO AL MISMO REPORTE.
	// SI RETORNA ARRAY HAY DOS OPCIONES:
	//
	// 1. SI $file='NombreMembrete' EL REPORTE UTILIZARA UN MEMBRETE UBICADO EN
	//    reports/HTML/MEMBRETES/NombreMembrete y el arraglo $datos_membrete
	//    seran los parametros especificos de este membrete.
	//
	//	  EJEMPLO:
	//
	// 			function GetMembrete()
	// 			{
	// 				$Membrete = array('file'=>'NombreMembrete','datos_membrete'=>array('titulo'=>'ESTE ES EL TITULO DEL REPORTE','subtitulo'=>'SUBTITULO'));
	// 				return $Membrete;
	// 			}
	//
	// 2. SI $file=false  SIGNIFICA QUE UTILIZA UN MEMBRETE GENERICO QUE CONCISTE EN UN
	//    LOGO (SI LO HAY), UN TITULO, UN SUBTITULO Y UNA POSICION DEL LOGO (IZQUIERDA,DERECHA O CENTRO)
	//    LOS PARAMETROS DEL VECTOR datos_membrete DEBN SER:
	//    titulo    : TITULO DE REPORTE
	//    subtitulo : SUBTITULO DEL REPORTE
	//    logo      : LA RUTA DE UN LOGO DENTRO DEL DIRECTORIO images (EN EL RAIZ)
	//    align     : POSICION DEL LOGO (left,center,right)
	//
	//	  EJEMPLO:
	//
	// 			function GetMembrete()
	// 			{
	// 				$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'ESTE ES EL TITULO DEL REPORTE',
	// 																		'subtitulo'=>'subtitulo'
	// 																		'logo'=>'logocliente.png'
	// 																		'align'=>'left'));
	// 				return $Membrete;
	// 			}

// 	function GetMembrete()
// 	{
// 		$Membrete = array('file'=>'MembreteDePrueba','datos_membrete'=>array('titulo'=>'ESTE ES EL TITULO DEL REPORTE',
// 																'subtitulo'=>'subtitulo',
// 																'logo'=>'logocliente.png',
// 																'align'=>'left'));
// 		return $Membrete;
// 	}
	
	function GetMembrete()
	{
		$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'',
																'subtitulo'=>'',
																'logo'=>'logocliente.png',
																'align'=>'left'));
		return $Membrete;
	}

//FUNCION CrearReporte()
//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
	function CrearReporte(){
	
	
   $NoLiquidacion=$this->datos['NoLiquidacion']; 
		if($_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS']){
      $Salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
      $Salida .= "    <tr class=\"label\"><td colspan=\"4\">CARGOS DEL ACTO QUIRURGICO No. ".$NoLiquidacion."</td></tr>";
      $Salida .= "    <tr>";
      $Salida .= "    <td width=\"10%\" class=\"label\">ANESTESIOLOGO</td>";
      $nombreTercero=$this->NombreTercero($_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS'][1][1]['liquidacion']['DA']['tipo_id_tercero'],$_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS'][1][1]['liquidacion']['DA']['tercero_id']);
      $Salida .= "    <td width=\"40%\">".$nombreTercero['nombre_tercero']."</td>";
      $Salida .= "    <td width=\"10%\" class=\"label\">AYUDANTE</td>";
      $nombreTercero=$this->NombreTercero($_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS'][1][1]['liquidacion']['DY']['tipo_id_tercero'],$_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS'][1][1]['liquidacion']['DY']['tercero_id']);
      $Salida .= "    <td width=\"40%\">".$nombreTercero['nombre_tercero']."</td>";
      $Salida .= "    </tr>";
      foreach($_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS'] as $indiceCirujano=>$Vector){
        $Salida .= "        <tr>";
        $Salida .= "         <td class=\"label\" width=\"10%\">CIRUJANO ".$indiceCirujano."</td>";
        $nombreTercero=$this->NombreTercero($Vector[1]['tipo_id_cirujano'],$Vector[1]['cirujano_id']);
        $Salida .= "         <td colspan=\"3\">".$nombreTercero['nombre_tercero']."</td>";
        $Salida .= "       </tr>";
        foreach($Vector as $indiceProcedimiento=>$DatosQX){
          $Salida .= "    <tr class=\"modulo_list_oscuro\">";
          $Salida .= "      <td colspan=\"4\">";
          $Salida .= "       <BR><table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
          $descripciones=$this->DescripcionCargosCups($DatosQX['cargo_cups']);
          $Salida .= "       <tr class=\"modulo_list_claro\">";
          $Salida .= "        <td  width=\"10%\" class=\"label\">CARGO CUPS</td>";
          $Salida .= "        <td colspan=\"4\">".$DatosQX['cargo_cups']." - ".$descripciones['descripcion']."</td>";
          $Salida .= "       </tr>";
          if($DatosQX['uvrs']){
              $Salida .= "       <tr class=\"modulo_list_claro\">";
              $Salida .= "        <td  width=\"10%\" class=\"label\">UVRS/G.QX</td>";
              $Salida .= "        <td colspan=\"4\">".$DatosQX['uvrs']."</td>";
              $Salida .= "       </tr>";
          }
          elseif($DatosQX['grupo_qx'])
          {
              $Salida .= "       <tr class=\"modulo_list_claro\">";
              $Salida .= "        <td  width=\"10%\" class=\"label\">Grupo QX</td>";
              $Salida .= "        <td colspan=\"4\">".$DatosQX['grupo_qx']."</td>";
              $Salida .= "       </tr>";
          }
          $descripciones=$this->DescripcionCargosTarifario($DatosQX['tarifario_id']);
          $Salida .= "       <tr class=\"modulo_list_claro\">";
          $Salida .= "        <td  width=\"10%\" class=\"label\">EQUIVALENCIA</td>";
          $Salida .= "        <td colspan=\"4\">".$descripciones['tarifario']." ".$DatosQX['cargo']." - ".$DatosQX['descripcion']."</td>";
          $Salida .= "       </tr>";
          $Salida .= "          <tr>";
          $Salida .= "          <td class=\"label\" align=\"left\" width=\"10%\">".$indiceProcedimiento."</td>";
          $Salida .= "          <td class=\"label\" align=\"left\" width=\"20%\">CARGO</td>";
          $Salida .= "          <td class=\"label\" align=\"right\" width=\"10%\">%</td>";
          $Salida .= "          <td class=\"label\" align=\"right\" width=\"30%\">VALOR CUBIERTO</td>";
          $Salida .= "          <td class=\"label\" align=\"right\">VALOR NO CUBIERTO</td>";
          $Salida .= "          </tr>";
          foreach($DatosQX['liquidacion'] as $derecho=>$DatosDerecho){
            if($DatosDerecho['facturado']==2){
              $Salida .= "        <tr class=\"hc_table_submodulo_list_title\">";
            }else{
              $Salida .= "        <tr class=\"modulo_list_claro\">";
            }
            
            $Salida .= "        <td class=\"label\" align=\"left\">$derecho</td>";
            $descripciones=$this->DescripcionCargosTarifario($DatosDerecho['tarifario_id']);
            $Salida .= "        <td align=\"left\">".$descripciones['tarifario']." - ".$DatosDerecho['cargo']."</td>";
            if($valoresManual==1){
                $Salida .= "        <td align=\"center\"><input align=\"right\" type=\"text\" class=\"input-text\" size=\"8\" name=\"Porcentajes[$indiceCirujano][$indiceProcedimiento][liquidacion][$derecho]\" value=\"".$DatosDerecho['PORCENTAJE']."\"></td>";
            }else{
                $Salida .= "        <td align=\"right\">".$DatosDerecho['PORCENTAJE']."</td>";
            }
            if($valoresManual==1){
              $Salida .= "        <td align=\"center\"><input align=\"right\" type=\"text\" class=\"input-text\" size=\"8\" name=\"valoresCubiertos[$indiceCirujano][$indiceProcedimiento][liquidacion][$derecho]\" value=\"".FormatoValor($DatosDerecho['valor_cubierto'])."\"></td>";
            }else{
               $Salida .= "        <td align=\"right\">".FormatoValor($DatosDerecho['valor_cubierto'])."</td>";
            }
            if($valoresManual==1){
              $Salida .= "        <td align=\"center\"><input align=\"right\" type=\"text\" class=\"input-text\" size=\"8\" name=\"valoresNoCubiertos[$indiceCirujano][$indiceProcedimiento][liquidacion][$derecho]\" value=\"".FormatoValor($DatosDerecho['valor_no_cubierto'])."\"></td>";
            }else{
              $Salida .= "        <td align=\"right\">".FormatoValor($DatosDerecho['valor_no_cubierto'])."</td>";
            }
            $Salida .= "        </tr>";
          }
          $Salida .= "       </table>";
          $Salida .= "      </td>";
          $Salida .= "    </tr>";
        }
      }
      $Salida .= "    </table>";
    }
    /*echo "<pre>";
    echo print_r($_SESSION['ARREGLO_LIQUIDACIONQX_EQUIPOS']);
    echo "</pre>";*/
    if($DatosQXEquipos=$_SESSION['ARREGLO_LIQUIDACIONQX_EQUIPOS']){
      $Salida .= "    <BR><table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
      $Salida .= "    <tr class=\"label\"><td colspan=\"4\">CARGOS DE EQUIPOS DEL ACTO QUIRURGICO No. ".$NoLiquidacion."</td></tr>";
      for($i=0;$i<sizeof($DatosQXEquipos);$i++){
        $Salida .= "    <tr class=\"modulo_list_oscuro\">";
        $Salida .= "      <td colspan=\"4\">";
        $Salida .= "       <table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
        $Salida .= "       <tr>";
        $Salida .= "        <td  width=\"10%\" class=\"label\">EQUIPO</td>";
        $Salida .= "        <td colspan=\"4\">".$DatosQXEquipos[$i]['descripcion_equipo']."&nbsp&nbsp&nbsp;<label class=\"label\">DURACION:&nbsp&nbsp&nbsp;</label>".$DatosQXEquipos[$i]['duracion']."</td>";
        $Salida .= "       </tr>";
        $descripciones=$this->DescripcionCargosTarifario($DatosQXEquipos[$i]['tarifario_id']);
        $Salida .= "       <tr class=\"modulo_list_claro\">";
        $Salida .= "        <td  width=\"10%\" class=\"label\">EQUIVALENCIA</td>";
        $Salida .= "        <td colspan=\"4\">".$descripciones['tarifario']." ".$DatosQXEquipos[$i]['cargo']." - ".$DatosQXEquipos[$i]['descripcion']."</td>";
        $Salida .= "       </tr>";
        $Salida .= "          <tr class=\"modulo_table_list_title\">";
        $Salida .= "          <td class=\"label\" width=\"10%\">TIPO EQUIPO</td>";
        $Salida .= "          <td class=\"label\" width=\"10%\">CANTIDAD</td>";
        $Salida .= "          <td class=\"label\" width=\"30%\">VALOR CUBIERTO</td>";
        $Salida .= "          <td class=\"label\" width=\"30%\">VALOR NO CUBIERTO</td>";
        $Salida .= "          <td class=\"label\" width=\"10%\">FACTURADO</td>";
        $Salida .= "          </tr>";
        $Salida .= "        <tr class=\"modulo_list_claro\">";
        if($DatosQXEquipos[$i]['tipo_equipo']=='fijo'){
          $Salida .= "        <td class=\"label\" align=\"center\">FIJO</td>";
        }else{
          $Salida .= "        <td class=\"label\" align=\"center\">MOVIL</td>";
        }
        $Salida .= "        <td>".$DatosQXEquipos[$i]['cantidad']."</td>";
        if($valoresManual==1){
          $Salida .= "        <td align=\"center\"><input align=\"right\" type=\"text\" class=\"input-text\" size=\"8\" name=\"valoresCubiertosEquipos[$i]\" value=\"".FormatoValor($DatosQXEquipos[$i]['valor_cubierto'])."\"></td>";
        }else{
          $Salida .= "        <td align=\"right\">".FormatoValor($DatosQXEquipos[$i]['valor_cubierto'])."</td>";
        }
        if($valoresManual==1){
          $Salida .= "        <td align=\"center\"><input align=\"right\" type=\"text\" class=\"input-text\" size=\"8\" name=\"valoresNoCubiertosEquipos[$i]\" value=\"".FormatoValor($DatosQXEquipos[$i]['valor_no_cubierto'])."\"></td>";
        }else{
          $Salida .= "        <td align=\"right\">".FormatoValor($DatosQXEquipos[$i]['valor_no_cubierto'])."</td>";
        }

        if($DatosQXEquipos[$i]['facturado']=='1'){
          $Salida .= "        <td align=\"center\">SI</td>";
        }else{
          $Salida .= "        <td align=\"center\">NO</td>";
        }
        $Salida .= "      </table>";
        $Salida .= "      </td>";
        $Salida .= "    </tr>";
      }
      $Salida .= "    </table>";
    }
    
    //consulta de los medicamentos en la cuenta del paciente
    $cargos=$this->CargosMedicamentosCuentaPaciente($NoLiquidacion);
    $cargosDev=$this->CargosMedicamentosCuentaPacienteDevol($NoLiquidacion);
    if(is_array($cargos) || is_array($cargosDev)){
      $Salida .= "    <BR><table width=\"80%\" border=\"0\" align=\"center\" class=\"normal_10\">";
      $Salida .= "    <tr class=\"label\"><td colspan=\"6\">INSUMOS Y MEDICAMENTOS CARGADOS EN LA CUENTA</td></tr>";
      $Salida .= "    <tr>";
      $Salida .= "    <td class=\"label\" width=\"15%\">CODIGO</td>";
      $Salida .= "    <td class=\"label\" width=\"15%\">CANTIDAD</td>";
      $Salida .= "    <td class=\"label\">PRODUCTO</td>";
      $Salida .= "    <td class=\"label\" width=\"15%\">VALOR NO CUBIERTO</td>";
      $Salida .= "    <td class=\"label\" width=\"15%\">VALOR CUBIERTO</td>";
      $Salida .= "    <td class=\"label\" width=\"15%\">FACTURADO</td>";
      $Salida .= "    </tr>";
      if(is_array($cargos)){
        $Salida .= "    <tr><td class=\"label\" colspan=\"6\">DESPACHOS</td></tr>";
        for($i=0;$i<sizeof($cargos);$i++){          
          $Salida .= "    <tr>";
          $Salida .= "    <td align=\"left\">".$cargos[$i]['codigo_producto']."</td>";
          $divisor=(int)($cargos[$i]['cantidad']);
          if($cargos[$i]['cantidad']%$divisor){
            $Salida .= "    <td align=\"left\">".$cargos[$i]['cantidad']."</td>";
          }else{
            $Salida .= "    <td align=\"left\">".$divisor."</td>";
          }
          $Salida .= "    <td align=\"left\">".$cargos[$i]['descripcion']."</td>";
          $Salida .= "    <td align=\"left\">".$cargos[$i]['valor_nocubierto']."</td>";
          $Salida .= "    <td align=\"left\">".$cargos[$i]['valor_cubierto']."</td>";
          if($cargos[$i]['facturado']==1){
            $Salida .= "    <td align=\"center\">SI</td>";
          }else{
             $Salida .= "    <td align=\"center\">NO</td>";
          }
          $y++;
        }
      }
      if(is_array($cargosDev)){
        $Salida .= "    <tr><td class=\"label\" colspan=\"6\">DEVOLUCIONES</td></tr>";
        for($i=0;$i<sizeof($cargosDev);$i++){          
          $Salida .= "    <tr>";
          $Salida .= "    <td align=\"left\">".$cargosDev[$i]['codigo_producto']."</td>";
          $divisor=(int)($cargosDev[$i]['cantidad']);
          if($cargosDev[$i]['cantidad']%$divisor){
            $Salida .= "    <td align=\"left\">".$cargosDev[$i]['cantidad']."</td>";
          }else{
          $Salida .= "    <td align=\"left\">".$divisor."</td>";
          }
          $Salida .= "    <td align=\"left\">".$cargosDev[$i]['descripcion']."</td>";
          $Salida .= "    <td align=\"left\">".$cargosDev[$i]['valor_nocubierto']."</td>";
          $Salida .= "    <td align=\"left\">".$cargosDev[$i]['valor_cubierto']."</td>";
          if($cargosDev[$i]['facturado']==1){
            $Salida .= "    <td align=\"center\">SI</td>";
          }else{
             $Salida .= "    <td align=\"center\">NO</td>";
          }
          $y++;
        }
      }
      $Salida .= "    </table>";
    }
    //fin
    
		return $Salida;			
	}	      
//*****************************************fin de termino
 
	function NombreTercero($tipo_id_tercero,$tercero_id){
    list($dbconn) = GetDBconn();
    $query="SELECT nombre_tercero
    FROM terceros
    WHERE tipo_id_tercero='".$tipo_id_tercero."' AND tercero_id='".$tercero_id."'";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()>0){
        $vars=$result->GetRowAssoc($toUpper=false);
      }
    }
    $result->Close();
    return $vars;
  }
  
  function DescripcionCargosCups($cargo_cups){
    list($dbconn) = GetDBconn();
    $query="SELECT descripcion
    FROM cups
    WHERE cargo='".$cargo_cups."'";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()>0){
        $vars=$result->GetRowAssoc($toUpper=false);
      }
    }
    $result->Close();
    return $vars;
  }

  function DescripcionCargosTarifario($tarifario_id){
    list($dbconn) = GetDBconn();
   $query="SELECT a.descripcion as tarifario
    FROM tarifarios a
    WHERE a.tarifario_id='".$tarifario_id."'";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()>0){
        $vars=$result->GetRowAssoc($toUpper=false);
      }
    }
    $result->Close();
    return $vars;
  }
  
  function CargosMedicamentosCuentaPaciente($NoLiquidacion){
    $query="SELECT c.codigo_producto,sum(c.cantidad) as cantidad,sum(b.valor_cubierto) as valor_cubierto,
    sum(b.valor_nocubierto) as valor_nocubierto,b.facturado,
    (SELECT d.descripcion FROM inventarios_productos d WHERE c.codigo_producto=d.codigo_producto) as descripcion
    FROM cuentas_codigos_agrupamiento a,cuentas_detalle b,bodegas_documentos_d c
    WHERE a.cuenta_liquidacion_qx_id='".$NoLiquidacion."' AND a.codigo_agrupamiento_id=b.codigo_agrupamiento_id AND b.cargo='IMD' AND
    a.bodegas_doc_id=c.bodegas_doc_id AND a.numeracion=c.numeracion AND b.consecutivo=c.consecutivo
    GROUP BY c.codigo_producto,b.facturado";
    list($dbconn) = GetDBconn();
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()>0){
        while(!$result->EOF){
          $vars[]=$result->GetRowAssoc($toUpper=false);
          $result->MoveNext();
        }
      }
    }
    return $vars;
  }
  
  function CargosMedicamentosCuentaPacienteDevol($NoLiquidacion){
    $query="SELECT c.codigo_producto,sum(c.cantidad) as cantidad,sum(b.valor_cubierto) as valor_cubierto,
    sum(b.valor_nocubierto) as valor_nocubierto,b.facturado,
    (SELECT d.descripcion FROM inventarios_productos d WHERE c.codigo_producto=d.codigo_producto) as descripcion
    FROM cuentas_codigos_agrupamiento a,cuentas_detalle b,bodegas_documentos_d c
    WHERE a.cuenta_liquidacion_qx_id='".$NoLiquidacion."' AND a.codigo_agrupamiento_id=b.codigo_agrupamiento_id AND b.cargo='DIMD' AND
    a.bodegas_doc_id=c.bodegas_doc_id AND a.numeracion=c.numeracion AND b.consecutivo=c.consecutivo
    GROUP BY c.codigo_producto,b.facturado";
    list($dbconn) = GetDBconn();
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()>0){
        while(!$result->EOF){
          $vars[]=$result->GetRowAssoc($toUpper=false);
          $result->MoveNext();
        }
      }
    }
    return $vars;
  }

  

    //---------------------------------------
}

?>
