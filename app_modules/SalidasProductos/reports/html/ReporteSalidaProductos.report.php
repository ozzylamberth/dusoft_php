<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: 
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
  /**
  * Clase Reporte: ReporteSalidaProductos
  * 
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
  //IncludeClass('',null,'app','');
	class ReporteSalidaProductos_report
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
		
		
		/**
    * Contructor de la clase
    * 
    * @param array $datos
    *
    * @return boolean
    */
    function ReporteSalidaProductos_report($datos=array())
		{
			$this->datos=$datos;
      //print_r($datos);
			return true;
		}
	
	  /**
    * Funcion que coloca el menbrete del reporte
    *
    * @return array
    **/
	
	 function GetMembrete()
   {
      $estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
      $titulo .= "<b $estilo>REPORTE DE SALIDA DE PRODUCTOS  </b>";
  
      $Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
                                          'subtitulo'=>"<b $estilo>DOCUMENTO </b>",'logo'=>'logocliente.png','align'=>'left'));//logocliente.png
      return $Membrete;
   }	
		

		/**
    * Funcion que retorna el html del reporte (lo que va dentro del tag <body>)
		*
    * @return String
    */
  function CrearReporte()
	{
    IncludeClass('ConexionBD');
	  IncludeClass('SalidasProductosSQL','','app','SalidasProductos');
    IncludeClass('MovBodegasSQL','','app','Inv_MovimientosBodegas');
    $usuario=UserGetUID();
    
    //$mdl = AutoCarga::factory('SalidasProductosSQL', '', 'app', 'SalidasProductos');
    $empresa_id = SessionGetVar("empresa_id");
    $consulta=new SalidasProductosSQL();
    $consulta1=new MovBodegasSQL();
    $prefijo='ESP';
    $resultado=$consulta->SacarDocumento($empresa_id,$this->datos['prefijo'],$this->datos['numero']);
    $contar=count($resultado);
    for($i=0;$i<$contar;$i++)
    {
      $detalle=$consulta->SacarDocDetalle($empresa_id,$this->datos['prefijo'],$this->datos['numero']);
    }
    $jefe_bodega=ModuloGetVar('app','InvTomaFisica','JEFE_DE_BODEGA');
    
    //print_r($detalle);    
    $ESTILO3="style=\"font-family: sans_serif, sans_serif, Verdana, helvetica, Arial; font-size: 10px; color:#000000; font-weight: bold\"";
    $ESTILO2="style=\"font-family:sans_serif, Verdana, helvetica, Arial; font-size: 10px;color: #000000;font-weight: bold\"";
    $html .= "  <table border=\"1\" width=\"90%\" align=\"center\" cellspacing='0'>\n";
    $html .= "   <tr>\n";
    $html .= "     <td width=\"35%\" align=\"center\">\n";
    $html .= "       <a title='RAZON SOCIAL DE LA EMPRESA'>";
    $html .= "        EMPRESA";
    $html .= "       </a>";
    $html .= "     </td>\n";
    $nombre=$consulta1->ColocarEmpresa($empresa_id); 
    //print_r($nombre);
    $html .= "     <td width=\"65%\" align=\"left\">\n";
    $html .= "       ".$nombre[0]['razon_social'];
    $html .= "     </td>\n";
    $html .= "   </tr>\n";
    $html .= "   <tr>\n";
    $html .= "    <td width=\"5%\" align=\"center\">\n";
    $html .= "     <a title='BODEGA'>";
    $html .= "      BODEGA";
    $html .= "     </a>";
    $html .= "    </td>\n";
    $bodega=$consulta1->bodegasname($resultado[0]['bodega']);
    //$html .= "<pre>".print_r($resultado,true)."</pre>";
    $html .= "    <td align=\"left\">\n";
    $html .= "      ".$bodega[0]['descripcion'];
    $html .= "    </td>\n";
    $html .= "   </tr>\n";
    $html .= "  </table>\n";
    
    $html .= " <br>\n";   

    $html .= " <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
    $html .= "   <tr>\n";
    $html .= "    <td width=\"35%\" align=\"center\">\n";
    $html .= "    <a>";
    $html .= "     TIPO MOVIMIENTO";
    $html .= "    </a>";
    $html .= "    </td>\n";
    $html .= "    <td align=\"left\">\n";
    $html .= "     ".$resultado[0]['tipo_movimiento'];
    $html .= "    </td>\n";
    $html .= "    <td width=\"25%\"align=\"center\">\n";
    $html .= "     <a TITLE='TIPO DOCUMENTO BODEGA ID'>";
    $html .= "      DOC BOD ID";
    $html .= "     </a>";
    $html .= "    </td>\n";
    $html .= "    <td align=\"left\">\n";
    $html .= "      ".$resultado[0]['tipo_doc_bodega_id'];
    $html .= "    </td>\n";
    $html .= "    </tr>\n";
    $html .= "    <tr>\n";
    $html .= "      <td width=\"35%\" align=\"center\">\n";
    $html .= "      <a>";
    $html .= "       DESCRIPCION";
    $html .= "      </a>";
    $html .= "      </td>\n";
    $html .= "      <td COLSPAN='3' align=\"left\">\n";
    $html .= "        ".$resultado[0]['descripcion'];
    $html .= "      </td>\n";
    $html .= "      </tr>\n";
    $html .= "      <tr>\n";
    $html .= "       <td width=\"8%\" align=\"center\">\n";
    $html .= "        <a title='PREFIJO - NUMERO'>";
    $html .= "         NUMERO";
    $html .= "        </a>";
    $html .= "       </td>\n";
    $html .= "       <td align=\"left\">\n";
    $html .= "         ".$resultado[0]['prefijo']."-".$resultado[0]['numero'];
    $html .= "       </td>\n";
    $html .= "       <td width=\"8%\" align=\"center\">\n";
    $html .= "        <a title='FECHA DE REGISTRO'>";
    $html .= "         FECHA";
    $html .= "        </a>";
    $html .= "       </td>\n";
    $html .= "       <td align=\"left\">\n";
    $html .= "         ".substr($resultado[0]['fecha_registro'],0,10);
    $html .= "       </td>\n";
    $html .= "      </tr>\n";        
    $html .= "      <tr>\n";
    $html .= "       <td align=\"center\">\n";
    $html .= "        <a title='USUARIO QUE ELABORO EL RECIBO'>";
    $html .= "          USUARIO";
    $html .= "        </a>";
    $html .= "       </td>\n";
    $USUARIO=$consulta1->NombreUsu($resultado[0]['usuario_id']);
    $html .= "       <td COLSPAN='3' align=\"left\">\n";
    $html .= "        ".$resultado[0]['usuario_id']."-".$USUARIO[0]['nombre'];
    $html .= "       </td>\n";
    $html .= "       </tr>\n";     
    $html .= "       <tr>\n";
    $html .= "        <td align=\"center\">\n";
    $html .= "         <a title='JEFE DE BODEGA'>";
    $html .= "          JEFE BODEGA";
    $html .= "         </a>";
    $html .= "        </td>\n";
    $jefe_b=$consulta1->NombreUsu($jefe_bodega);
    $html .= "        <td COLSPAN='3' align=\"left\">\n";
    $html .= "          ".$jefe_bodega."-".$jefe_b[0]['nombre'];
    $html .= "        </td>\n";
    $html .= "       </tr>\n";      
    $html .= " </table>\n";
    
    $html .= "  <br>\n"; 
    $html .= "  <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
    $html .= "   <tr>\n";
    $html .= "    <td COLSPAN='11' align=\"center\">\n";
    $html .= "    <a>";
    $html .= "     PRODUCTOS QUE CONTIENE ESTE DOCUMENTO";
    $html .= "    </a>";
    $html .= "    </td>\n";
    $html .= "    </tr>\n";
    $html .= "    <tr>\n";
    $html .= "     <td WIDTH='8%' align=\"center\">\n";
    $html .= "      <a TITLE='MOVIMIENTO ID'>";
    $html .= "        MOV ID";
    $html .= "      </a>";
    $html .= "     </td>\n";
    $html .= "     <td WIDTH='8%' align=\"center\">\n";  
    $html .= "      <a TITLE='TORRE'>";
    $html .= "       TORRE";
    $html .= "     </a>";
    $html .= "     </td>\n";
    $html .= "     <td WIDTH='8%' align=\"center\">\n";
    $html .= "      <a TITLE='ENCARGADO TORRE'>";
    $html .= "       ENCARGADO";
    $html .= "      </a>";
    $html .= "     </td>\n";     
    $html .= "     <td WIDTH='15%' align=\"center\">\n";
    $html .= "       <a TITLE='CODIGO DEL PRODUCTO'>";
    $html .= "        CODIGO";
    $html .= "       </a>";
    $html .= "     </td>\n";
    $html .= "     <td WIDTH='35%' align=\"center\">\n";
    $html .= "      <a TITLE='DESCRIPCION DEL PRODUCTO'>";
    $html .= "        DESCRIPCION";
    $html .= "      </a>";
    $html .= "     </td>\n";
    $html .= "     <td WIDTH='35%' align=\"center\">\n";
    $html .= "      <a TITLE='FECHA DE VENCIMIENTO'>";
    $html .= "        FECHA VENCIMIENTO";
    $html .= "      </a>";
    $html .= "     </td>\n";
    $html .= "     <td WIDTH='35%' align=\"center\">\n";
    $html .= "      <a TITLE='LOTE'>";
    $html .= "        LOTE";
    $html .= "      </a>";
    $html .= "     </td>\n";
    $html .= "     <td WIDTH='15%' align=\"center\">\n";
    $html .= "      <a TITLE='UNIDAD DEL PRODUCTO'>";
    $html .= "        UNIDAD";
    $html .= "      </a>";
    $html .= "     </td>\n";
    $html .= "     <td WIDTH='10%' align=\"center\">\n";
    $html .= "      <a TITLE='UNIDAD DEL PRODUCTO'>";
    $html .= "       CANTIDAD";
    $html .= "      </a>";
    $html .= "     </td>\n";
    $html .= "     <td WIDTH='10%' align=\"center\">\n";
    $html .= "      <a TITLE='VALOR UNIDAD'>";
    $html .= "       VALOR UNITARIO";
    $html .= "      </a>";
    $html .= "     </td>\n";    
    $html .= "     <td WIDTH='10%' align=\"center\">\n";
    $html .= "      <a TITLE='TOTAL COSTO'>";
    $html .= "       TOTAL";
    $html .= "      </a>";
    $html .= "     </td>\n";
    $html .= "    </tr>\n";
    $valorTotal=0;
    foreach($detalle as $doc_val=>$valor)
    {
      $paramTorres=$consulta1->Buscarparamprod($empresa_id,$valor['codigo_producto']);
      //$html .= "<pre>".print_r($valor,true)."</pre>";
      $html .= "  <tr>\n";
      $html .= "   <td class=\"normal_10AN\" align=\"left\">\n";
      $html .= "    ".$valor['movimiento_id'];
      $html .= "   </td>\n";
      $html .= "   <td align=\"left\">\n";
      $html .= "    <a>";
      $html .= "    ".$paramTorres['torre'];
      $html .= "   </td>\n";
      $html .= "   <td align=\"left\">\n";
      $html .= "    <a>";
      $html .= "     ".$paramTorres['dueno_torre'];
      $html .= "   </td>\n";
      $html .= "   <td align=\"left\">\n";
      $html .= "    <a>";
      $html .= "     ".$valor['codigo_producto'];
      $html .= "   </td>\n";
      $html .= "   <td align=\"left\">\n";
      $html .= "     ".$valor['descripcion']."";
      $html .= "   </td>\n";
      $html .= "   <td align=\"left\">\n";
      $html .= "     ".$valor['fecha_vencimiento']."";
      $html .= "   </td>\n";
      $html .= "   <td align=\"left\">\n";
      $html .= "     ".$valor['lote']."";
      $html .= "   </td>\n";
      $html .= "   <td align=\"left\">\n";
      $html .= "     ".$valor['descripcion_unidad']."";
      $html .= "   </td>\n";
      $html .= "   <td align=\"left\">\n";
      list($entero,$decimal) = explode(".",$valor['cantidad']);
      if($decimal>0)
      {
        $html .= "   ".$valor['cantidad'];
      }
      else
      {
        $html .= "   ".$entero;
      } 
      $html .= "    </td>\n";
      $html .= "    <td align=\"left\">\n";
      $html .= "     ".$valor['costo_inventario'];
      $html .= "    </td>\n";
      $html .= "    <td align=\"RIGHT\">\n";
      $html .= "      ".FormatoValor($valor['total_costo']);
      $html .= "   </td>\n";
      $html .= "  </tr>\n";
      $valorTotal=$valorTotal+$valor['total_costo'];
    }
    $html .= "  <tr>\n";
    $html .= "   <td colspan='9' align=\"right\">\n";
    $html .= "    ";
    $html .= "   </td>\n";
    $html .= "   <td align=\"right\">\n";
    $html .= "    <label class='label_error'>TOTAL</label>";
    $html .= "    </td>\n";
    $html .= "   <td align=\"right\">\n";
    $html .= "    ".FormatoValor($valorTotal);
    $html .= "   </td>\n";
    $html .= "  </tr>\n";
    $html .= " </table>\n";
 
      return $html; 
    }
	}
?>