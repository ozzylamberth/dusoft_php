<?php

/**
 * $Id: ConsultaDocumentosBodega_html.report.php,v 1.4 2007/05/15 13:56:13 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba formato HTML
 */

class ConsultaDocumentosBodega_html_report
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
    function ConsultaDocumentosBodega_html_report($datos=array())
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
  function CrearReporte()
  {
  //*******************************************termino
        
        $datosDocumento=$this->DatosDocumentoBodega($this->datos['numeracion'],$this->datos['bodegas_doc_id'],$this->datos['solicitud']);
        $datosDetalleDoc=$this->DatosDetalleDelDocumento($this->datos['numeracion'],$this->datos['bodegas_doc_id']);
        
	      $Salida.="<table  align=\"center\" border=\"0\"  width=\"70%\" class=\"normal_10\">";
				$Salida.="<tr><td width=\"30%\" align=\"center\" class=\"normal_10N\">DOCUMENTO DE BODEGA</td></tr>";
        $Salida.="</table><BR>";
        $Salida.="<table  align=\"center\" border=\"0\"  width=\"70%\" class=\"normal_10\">";
        $Salida.="<tr>";
        $Salida.="<td width=\"20%\" class=\"normal_10N\">BODEGA</td><td>".$_SESSION['BODEGAS']['NombreBodega']."</td>";
        $Salida.="<td width=\"20%\" class=\"normal_10N\">DOCUMENTO</td><td>".$datosDocumento[0][0]['prefijo']." - ".$this->datos['numeracion']."</td>";
        $Salida.="</tr>";        
        $Salida.="<tr>";        
        $Salida.="<td width=\"20%\" class=\"normal_10N\">CONCEPTO</td><td>".$datosDocumento[0][0]['nomconcepto']."</td>";        
        $Salida.="<td width=\"20%\" class=\"normal_10N\">FECHA</td><td>".$datosDocumento[0][0]['fecha']."</td>";                
        $Salida.="</tr>";                
        $Salida.="</tr><td width=\"20%\" class=\"normal_10N\">USUARIO</td><td>".$datosDocumento[0][0]['usuario']."</td></tr>";
        if(empty($datosDetalleDoc[0]['numero_factura'])){
          $Salida.="<tr><td width=\"20%\" class=\"normal_10N\">COSTO TOTAL</td><td>".$datosDocumento[0][0]['costo_tot_doc']."</td></tr>";                 
          if($datosDocumento[0][0]['observacion']){
            $Salida.="<tr><td width=\"20%\" class=\"normal_10N\">OBSERVACIONES</td><td>".$datosDocumento[0][0]['observacion']."</td></tr>";       
          }
          if($datosDocumento[0][0]['bodegatrans'] && $datosDocumento[0][0]['centroutilitrans']){
          $Salida.="<tr><td width=\"20%\" class=\"normal_10N\">BODEGA TRANSFERENCIA</td><td>".$datosDocumento[0][0]['bodegatrans']." - ".$datosDocumento[1][BodegaTrans]."</td></tr>";        
          }        
          if($datosDocumento[2]['Solicitud']){
            $Salida.="<tr><td width=\"20%\" class=\"normal_10N\">ESTACION</td><td>".$datosDocumento[2][Estacion]."</td></tr>";        
            $Salida.="<tr><td width=\"20%\" class=\"normal_10N\">DEPARTAMENTO</td><td>".$datosDocumento[2][DptoEstacion]."</td></tr>";        
            $Salida.="<tr><td width=\"20%\" class=\"normal_10N\">SOLICITA</td><td>".$datosDocumento[2][UsuarioEstacion]."</td></tr>";                           
          }
        }  
        $Salida.="</table>";        
        
        if(!empty($datosDetalleDoc[0]['numero_factura'])){
          $Salida.="<table  align=\"center\" border=\"0\"  width=\"70%\" class=\"normal_10\">"; 
          $Salida .= "           <tr>";
          $Salida .= "           <td width=\"20%\" class=\"normal_10N\">No. FACTURA</td>";
          $Salida .= "           <td class=\"normal_10\" colspan=\"3\">".$datosDetalleDoc[0]['numero_factura']."</td>";
          $Salida .= "           </tr>";
          $Salida .= "           <tr>";
          $Salida .= "           <td width=\"20%\" class=\"normal_10N\">FLETES</td>";
          $Salida .= "           <td class=\"normal_10\">".$datosDetalleDoc[0]['costo_fletes']."</td>";
          $Salida .= "           <td width=\"20%\" class=\"normal_10N\">OTROS GASTOS</td>";
          $Salida .= "           <td class=\"normal_10\">".$datosDetalleDoc[0]['otros_gastos']."</td>";
          $Salida .= "           </tr>";
          $Salida .= "           <tr>";
          $Salida .= "           <td width=\"20%\" class=\"normal_10N\">PROVEEDOR</td>";
          $Salida .= "           <td class=\"normal_10\" colspan=\"3\">".$datosDetalleDoc[0]['nombre_tercero']."</td>";
          $Salida .= "           </tr>";
          $Salida .= "           <tr>";
          $Salida .= "           <td width=\"20%\" class=\"normal_10N\">OBSERVACIONES</td>";
          $Salida .= "           <td class=\"normal_10\" colspan=\"3\">".$datosDetalleDoc[0]['observaciones']."</td>";
          $Salida .= "           </tr>";
          $SumaTotalCosto=0;
          for($i=0;$i<sizeof($datosDetalleDoc);$i++){
            if(!empty($datosDetalleDoc[$i]['iva_compra'])){
              $valorIva=($datosDetalleDoc[$i]['total_costo']*$datosDetalleDoc[$i]['iva_compra'])/100;
              $totalIva=$datosDetalleDoc[$i]['total_costo']+$valorIva;
            }else{
              $totalIva=$datosDetalleDoc[$i]['total_costo'];
            }
            $SumaCostoTot=$datosDetalleDoc[$i]['cantidad'] * $totalIva;
            $SumaTotalCosto+=$SumaCostoTot;
          }
          $Salida .= "           <tr class=\"modulo_list_claro\">";
          $Salida .= "           <td width=\"35%\" class=\"normal_10N\">COSTO TOTAL</td>";
          $Salida .= "           <td class=\"normal_10\" colspan=\"3\">".($SumaTotalCosto+$datosDetalleDoc[0]['otros_gastos'])."</td>";
          $Salida .= "           </tr>";
          $Salida.="</table>";
        }
        $Salida.="<br><br>";
        $Salida.="<table  align=\"center\" border=\"0\"  class=\"normal_10\" width=\"90%\">";        
        $Salida.="<tr>";
        $Salida.="<td class=\"normal_10N\">CODIGO</td>";
        $Salida.="<td class=\"normal_10N\">DESCRIPCION</td>";
        $Salida.="<td class=\"normal_10N\">CANTIDAD</td>";        
        $Salida.="<td class=\"normal_10N\" align=\"right\">COSTO</td>";
        $Salida.="<td class=\"normal_10N\" align=\"right\">IVA</td>\n";
        $Salida.="<td class=\"normal_10N\" align=\"right\">COSTO + IVA </td>\n";
        $Salida.="<td class=\"normal_10N\" align=\"right\">COSTO TOTAL</td>\n";        
        $Salida.="</tr>";        
        $productos=$datosDocumento[0];$SumaTotalCosto=0;
        for($i=0;$i<sizeof($productos);$i++)//por cada medicamento de la solicitud
        {
          $Salida.="<tr>";
          $Salida.="<td>".$productos[$i]['codigo_producto']."</td>";
          $Salida.="<td>".$productos[$i]['descripcion']."</td>";
          $Salida.="<td>".floor($productos[$i]['cantidad'])." ".$productos[$i]['abreviatura']."</td>";                                     
          $Salida.="<td align=\"right\">".floor($productos[$i]['total_costo'])."</td>";          
          if(!empty($productos[$i]['iva_compra'])){
            $valorIva=(($productos[$i]['total_costo']*$productos[$i]['iva_compra'])/100);
            $Salida.="   <td align=\"right\">".floor($productos[$i]['iva_compra'])." % - ".$valorIva."</td>";
            $Salida.="   <td align=\"right\">".$totalIva=$productos[$i]['total_costo']+$valorIva."</td>";
            $Salida.="   <td align=\"right\">".$SumaCostoTot=($productos[$i]['cantidad'] * $totalIva)."</td>";
          }else{
            $Salida.="   <td align=\"right\">0</td>";
            $Salida.="   <td align=\"right\">".$totalIva=$productos[$i]['total_costo']."</td>";
            $Salida.="   <td align=\"right\">".$SumaCostoTot=($productos[$i]['cantidad'] * $totalIva)."</td>";
          }
          $Salida.="</tr>";    
          $SumaTotalCosto+=$SumaCostoTot;
          $SumaTotalCostoUnit+=$totalIva;
          $SumaIvas+=$valorIva;
          $SumaCostos+=$productos[$i]['total_costo'];         
        }
        $Salida.="   <tr>\n";
        $Salida.="   <td class=\"normal_10N\" align=\"right\" colspan=\"3\">TOTALES</td>";        
        $Salida.="   <td align=\"right\">".$SumaCostos."</td>";
        $Salida.="   <td align=\"right\">".$SumaIvas."</td>";
        $Salida.="   <td align=\"right\">".$SumaTotalCostoUnit."</td>";
        $Salida.="   <td align=\"right\">".$SumaTotalCosto."</td>";
        $Salida.="</table><BR>";      
				$Salida.="<table  align=\"right\" border=\"0\"  class=\"normal_10\" width=\"30%\">";        
        $Salida.="<tr><td class=\"normal_10N\">USUARIO IMPRIME</td><td class=\"normal_10\">".$this->NombreUsuario()."</td></tr>";
        $Salida.="<tr><td class=\"normal_10N\">FECHA IMPRIME</td><td class=\"normal_10\">".date("Y-m-d H:i")."</td></tr>";
        $Salida.="</table><BR>";  
  	    return $Salida;
  //*****************************************fin de termino
  }


  function DatosDocumentoBodega($Documento,$concepto,$centroutiliTrans,$BodegaTrans,$solicitud_id){
      list($dbconn) = GetDBconn();
        $query="SELECT a.codigo_producto,b.descripcion,a.cantidad,(a.total_costo / ((a.iva_compra * 0.01) + 1)) AS total_costo,a.iva_compra,
        (CASE WHEN c.abreviatura IS NULL THEN c.descripcion ELSE c.abreviatura END) as abreviatura,        
        y.centro_utilidad_transferencia as centroutilitrans,y.bodega_destino_transferencia as bodegatrans,        
        y.fecha,y.total_costo as costo_tot_doc,z1.descripcion as nomconcepto,
        z.prefijo,z2.usuario||' - '||z2.nombre as usuario,y.observacion     
        FROM bodegas_documentos_d a,inventarios_productos b,unidades c,bodegas_doc_numeraciones x,
        bodegas_documentos y
        JOIN bodegas_doc_numeraciones z ON(y.bodegas_doc_id=z.bodegas_doc_id)
        JOIN tipos_doc_bodega z1 ON (z.tipo_doc_bodega_id=z1.tipo_doc_bodega_id)
        JOIN system_usuarios z2 ON(y.usuario_id=z2.usuario_id)
        WHERE a.numeracion='".$Documento."' AND a.bodegas_doc_id='".$concepto."' AND a.bodegas_doc_id=x.bodegas_doc_id AND
        x.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND
        x.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND x.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND
        a.codigo_producto=b.codigo_producto AND b.unidad_id=c.unidad_id AND 
        a.numeracion=y.numeracion AND a.bodegas_doc_id=y.bodegas_doc_id";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $datos=$result->RecordCount();
            if($datos){
                while(!$result->EOF) {
                    $varsProductos[0][]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
            }
            $result->Close();
        }
        $query="SELECT descripcion FROM bodegas WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$varsProductos[0][0]['centroutilitrans']."' AND bodega='".$varsProductos[0][0]['bodegatrans']."'";
        $result = $dbconn->Execute($query);
        $varsProductos[1]['BodegaTrans']=$result->fields[0];
        
        $query="(SELECT x.solicitud_id FROM hc_solicitudes_medicamentos x WHERE x.numeracion='$Documento' AND x.bodegas_doc_id='$concepto')
        UNION
        (SELECT x.solicitud_id FROM bodegas_documentos_inv_devolucion x WHERE x.numeracion='$Documento' AND x.bodegas_doc_id='$concepto')";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{          
          if($result->RecordCount()>0){
              $vars=$result->GetRowAssoc($toUpper=false);
          }
        }
        
        $query="SELECT a.estacion_id,b.descripcion as deesta,c.descripcion as dpto,a.usuario_id,d.nombre
        FROM hc_solicitudes_medicamentos a,estaciones_enfermeria b,departamentos c,system_usuarios d
        WHERE a.solicitud_id='".$vars[solicitud_id]."' AND a.estacion_id=b.estacion_id AND b.departamento=c.departamento AND a.usuario_id=d.usuario_id";
        $result = $dbconn->Execute($query);
        $varsProductos[2]['Solicitud']=$vars[solicitud_id];
        $varsProductos[2]['Estacion']=$result->fields[0].' - '.$result->fields[1];
        $varsProductos[2]['DptoEstacion']=$result->fields[2];
        $varsProductos[2]['UsuarioEstacion']=$result->fields[3].' - '.$result->fields[4];                
        return $varsProductos;
    }
    
    function DatosDetalleDelDocumento($Documento,$concepto){

        list($dbconn) = GetDBconn();
        $query="SELECT x.codigo_producto,z.descripcion,x.cantidad,(x.total_costo / ((x.iva_compra * 0.01) + 1)) AS total_costo,x.iva_compra,
        com.numero_factura,com.otros_gastos,com.costo_fletes,com.observaciones,
        ter.nombre_tercero
        FROM bodegas_documentos_d x
        LEFT JOIN bodegas_documentos_compras com ON(x.numeracion=com.numeracion AND x.bodegas_doc_id=com.bodegas_doc_id)
        LEFT JOIN terceros ter ON(ter.tipo_id_tercero=com.tipo_id_proveedor AND ter.tercero_id=com.proveedor_id)
        ,inventarios y,inventarios_productos z,bodegas_doc_numeraciones a
        WHERE x.numeracion='$Documento' AND x.bodegas_doc_id='$concepto' AND x.bodegas_doc_id=a.bodegas_doc_id AND y.empresa_id=a.empresa_id AND x.codigo_producto=y.codigo_producto AND x.codigo_producto=z.codigo_producto";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $datos=$result->RecordCount();
            if($datos){
                while(!$result->EOF){
                    $vars[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
            }
        }
        return $vars;
    }
    
    function NombreUsuario(){
      list($dbconn) = GetDBconn();
        $query="SELECT a.nombre
        FROM system_usuarios a        
        WHERE a.usuario_id=".UserGetUID()."";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{            
            if($result->RecordCount()>0){
                return $result->fields[0];
            }
        }
        return false;
    }
    
    

    //AQUI TODOS LOS METODOS QUE USTED QUIERA

	



    //---------------------------------------
}

?>
