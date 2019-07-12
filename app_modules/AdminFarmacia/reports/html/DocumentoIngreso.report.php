<?php
	/**
	* $Id: proveedores.report.php,v 1.1 2010/04/08 20:36:35 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	*/
  IncludeClass('ConexionBD');
  IncludeClass('ClaseUtil');
  IncludeClass("ListaReportes","classes","app","ReportesInventariosGral");
  IncludeClass('MovBodegasSQL',null,'app','Inv_MovimientosBodegas');
	class DocumentoIngreso_report 
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
	  function DocumentoIngreso_report($datos=array())
	  {
			$this->datos=$datos;			
	    return true;
	  }
		/**
    *
    */
		function GetMembrete()
		{
			$est  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:10pt\"";
			$titulo .= "<b $est >DOCUMENTO DE INGRESO A LA FARMACIA<br>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'subtitulo'=>' ',
							  'logo'=>'logocliente.png',
							  'align'=>'left'));
			return $Membrete;
		}

		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
	  {
			$nc = new ListaReportes();
			$cl = new ClaseUtil();
      
    $prefijo=$this->datos['prefijo'];
    $empresa_id=$this->datos['empresa_id'];
    $numero=$this->datos['numero'];
    $consulta=new MovBodegasSQL();
    $resultado=$consulta->SacarDocumento($empresa_id,$prefijo,$numero);
    $Tercero = $consulta->ObtenerTerceroDocumentoIngresoPrestamo($prefijo,$numero);
	 
   $html .= "                 <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
         $html .= "                    <tr>\n";
         $html .= "                       <td width=\"35%\" align=\"center\">\n";
         $html .= "                       <a title='RAZON SOCIAL DE LA EMPRESA'>";
         $html .= "                        EMPRESA";
         $html .= "                       </a>";
         $html .= "                       </td>\n";
         $nombre=$consulta->ColocarEmpresa($resultado['empresa_id']); 
         $html .= "                       <td width=\"65%\" align=\"left\">\n";
         $html .= "                          ".$nombre[0]['razon_social'];
         $html .= "                         </td>\n";
         $html .= "                       </tr>\n";
         $html .= "                    <tr>\n";
         $html .= "                       <td width=\"8%\" align=\"center\">\n";
         $html .= "                       <a title='CENTRO DE UTILIDAD'>";
         $html .= "                        CENTRO DE UTILIDAD";
         $html .= "                       </a>";
         $html .= "                       </td>\n";
     
         $centro=$consulta->ColocarCentro($resultado['centro_utilidad']);
         $html .= "                        <td align=\"left\">\n";
         $html .= "                          ".$centro[0]['descripcion'];
         $html .= "                         </td>\n";
         $html .= "                       </tr>\n";
         $html .= "                    <tr>\n";
         $html .= "                       <td width=\"5%\" align=\"center\">\n";
         $html .= "                       <a title='BODEGA'>";
         $html .= "                        BODEGA";
         $html .= "                       </a>";
         $html .= "                       </td>\n";
         $bodega=$consulta->bodegasname($resultado['bodega']);
         $html .= "                        <td align=\"left\">\n";
         $html .= "                          ".$bodega[0]['descripcion'];
         $html .= "                         </td>\n";
         $html .= "                    </tr>\n";
         $html .= "                   </table>\n";
         $html .= "                   <br>\n";   



         $html .= "                 <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
         $html .= "                    <tr>\n";
         $html .= "                       <td width=\"35%\" align=\"center\">\n";
         $html .= "                       <a>";
         $html .= "                        TIPO MOVIMIENTO";
         $html .= "                       </a>";
         $html .= "                       </td>\n";
         $html .= "                       <td align=\"left\">\n";
         $html .= "                          ".$resultado['tipo_movimiento'];
         $html .= "                       </td>\n";
         $html .= "                       <td width=\"25%\"align=\"center\">\n";
         $html .= "                       <a TITLE='TIPO DOCUMENTO BODEGA ID'>";
         $html .= "                        DOC BOD ID";
         $html .= "                       </a>";
         $html .= "                       </td>\n";
         $html .= "                        <td align=\"left\">\n";
         $html .= "                          ".$resultado['tipo_doc_bodega_id'];
         $html .= "                        </td>\n";
         $html .= "                       </tr>\n";
         $html .= "                    <tr>\n";
         $html .= "                        <td width=\"35%\" align=\"center\">\n";
         $html .= "                          <a>";
         $html .= "                            DESCRIPCION";
         $html .= "                          </a>";
         $html .= "                        </td>\n";
         $html .= "                       <td COLSPAN='3' align=\"left\">\n";
         $html .= "                          ".$resultado['descripcion'];
         $html .= "                       </td>\n";
         $html .= "                      </tr>\n";
         $html .= "                    <tr>\n";
         $html .= "                       <td width=\"8%\" align=\"center\">\n";
         $html .= "                       <a title='PREFIJO - NUMERO'>";
         $html .= "                        NUMERO";
         $html .= "                       </a>";
         $html .= "                       </td>\n";
         $html .= "                        <td align=\"left\">\n";
         $html .= "                          ".$resultado['prefijo']."-".$resultado['numero'];
         $html .= "                         </td>\n";
         $html .= "                       <td width=\"8%\" align=\"center\">\n";
         $html .= "                       <a title='FECHA DE REGISTRO'>";
         $html .= "                        FECHA";
         $html .= "                       </a>";
         $html .= "                       </td>\n";
         $html .= "                        <td align=\"left\">\n";
         $html .= "                          ".substr($resultado['fecha_registro'],0,10);
         $html .= "                         </td>\n";
         $html .= "                       </tr>\n";
         $html .= "                    <tr>\n";
         $html .= "                       <td align=\"center\">\n";
         $html .= "                        OBSERVACIONES";
         $html .= "                       </td>\n";
         $html .= "                        <td COLSPAN='3' align=\"left\">\n";
         $html .= "                          ".$resultado['observacion'];
         $html .= "                         </td>\n";
         $html .= "                    </tr>\n";

          
         
          
		 if($resultado['sw_maneja_proyectos']=='1')
		 {
			$proyecto=$consulta->SacarNombreProyecto($resultado['bodegas_doc_id'],$empresa_id,$prefijo,$numero);
			
			if($proyecto!=false)
			{
				$html .= "                    <tr>\n";
				$html .= "                       <td align=\"center\">\n";
				$html .= "                       <a title='NOMBRE DEL PROYECTO'>";
				$html .= "                        PROYECTO";
				$html .= "                       </a>";
				$html .= "                       </td>\n";
				$html .= "                        <td COLSPAN='3' align=\"left\">\n";
				$html .= "                          ".$proyecto['codigo_proyecto_cg']."-".$proyecto['descripcion'];
				$html .= "                         </td>\n";
				$html .= "                    </tr>\n";
			}
			
		 }
		 
         $html .= "                    <tr>\n";
         $html .= "                       <td align=\"center\">\n";
         $html .= "                       <a title='USUARIO QUE ELABORO EL RECIBO'>";
         $html .= "                        USUARIO";
         $html .= "                       </a>";
         $html .= "                       </td>\n";
         $USUARIO=$consulta->NombreUsu($resultado['usuario_id']);
         $html .= "                        <td COLSPAN='3' align=\"left\">\n";
         $html .= "                          ".$resultado['usuario_id']."-".$USUARIO[0]['nombre'];
         $html .= "                         </td>\n";
         $html .= "                    </tr>\n";
         $html .= "                   </table>\n";

         $html .= "                   <br>\n"; 

          if($resultado['tipo_doc_bodega_id']=='I007' || $resultado['tipo_doc_bodega_id']=='E002')
          {
         $html .= "                 <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
         $html .= "                    <tr>\n";
         $html .= "                        <td COLSPAN='7' align=\"center\">\n";
         $html .= "                         <a>";
         $html .= "                           TERCERO";
         $html .= "                         </a>";
         $html .= "                        </td>\n";
         $html .= "                    </tr>\n";
         $html .= "                    <tr>\n";
         $html .= "                       <td width=\"35%\" align=\"center\">\n";
         $html .= "                       <a title='IDENTIFICACION DEL TERCERO'>";
         $html .= "                        ID";
         $html .= "                       </a>";
         $html .= "                       </td>\n";
         $html .= "                       <td width=\"65%\" align=\"left\">\n";
         $html .= "                          ".$Tercero[0]['tipo_id_tercero']." - ".$Tercero[0]['tercero_id'];
         $html .= "                         </td>\n";
         $html .= "                       </tr>\n";
         $html .= "                       <td width=\"5%\" align=\"center\">\n";
         $html .= "                       <a title='NOMBRE'>";
         $html .= "                        NOMBRE TERCERO";
         $html .= "                       </a>";
         $html .= "                       </td>\n";
         $html .= "                        <td align=\"left\">\n";
         $html .= "                          ".$Tercero[0]['nombre_tercero'];
         $html .= "                         </td>\n";
         $html .= "                    </tr>\n";
         $html .= "                   </table>\n";
         $html .= "                   <br>\n";   
          }
         
         if(!empty($resultado['DATOS_ADICIONALES']))
         {
              $html .= "                 <table BORDER='1' width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
              $html .= "                    <tr>\n";
              $html .= "                        <td COLSPAN='4' align=\"center\">\n";
              $html .= "                         <a>";
              $html .= "                           DATOS ADICIONALES";
              $html .= "                         </a>";
              $html .= "                        </td>\n";
              $html .= "                    </tr>\n";
            foreach($resultado['DATOS_ADICIONALES'] as $doc_val=>$valor)
            {
                //var_dump($resultado['DETALLE']);
                $html .= "                    <tr>\n";
                $html .= "                      <td WIDTH='35%' align=\"left\">\n";
                $html .= "                       ".$doc_val;
                $html .= "                      </td>\n";
                $html .= "                      <td WIDTH='65%' align=\"left\">\n";
                $html .= "                       <a>";
                $html .= "                       ".$valor;
                $html .= "                      </td>\n";
                $html .= "                    </tr>\n";
            }
              $html .= "                   </table>\n";
          }

         
         $html .= "                   <br>\n"; 
         $html .= "                 <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
         $html .= "                    <tr>\n";
         $html .= "                        <td COLSPAN='5' align=\"center\">\n";
         $html .= "                         <a>";
         $html .= "                           PRODUCTOS QUE CONTIENE ESTE DOCUMENTO";
         $html .= "                         </a>";
         $html .= "                        </td>\n";
         $html .= "                    </tr>\n";
         $html .= "                    <tr>\n";
         /*$html .= "                        <td WIDTH='8%' align=\"center\">\n";
         $html .= "                          <a TITLE='MOVIMIENTO ID'>";
         $html .= "                            MOV ID";
         $html .= "                          </a>";
         $html .= "                        </td>\n";*/
         $html .= "                        <td WIDTH='15%' align=\"center\">\n";
         $html .= "                          <a TITLE='CODIGO DEL PRODUCTO'>";
         $html .= "                            CODIGO";
         $html .= "                          </a>";
         $html .= "                        </td>\n";
         $html .= "                        <td WIDTH='35%' align=\"center\">\n";
         $html .= "                          <a TITLE='DESCRIPCION DEL PRODUCTO'>";
         $html .= "                            DESCRIPCION";
         $html .= "                          </a>";
         $html .= "                        </td>\n";
         $html .= "                        <td WIDTH='15%' align=\"center\">\n";
         $html .= "                          <a TITLE='FECHA VENCIMIENTO'>";
         $html .= "                            FECHA VENCIMIENTO";
         $html .= "                          </a>";
         $html .= "                        </td>\n";
         $html .= "                        <td WIDTH='15%' align=\"center\">\n";
         $html .= "                          <a TITLE='LOTE'>";
         $html .= "                            LOTE";
         $html .= "                          </a>";
         $html .= "                        </td>\n";
         /*$html .= "                        <td WIDTH='15%' align=\"center\">\n";
         $html .= "                          <a TITLE='UNIDAD DEL PRODUCTO'>";
         $html .= "                            UNIDAD";
         $html .= "                          </a>";
         $html .= "                        </td>\n";*/
         $html .= "                        <td WIDTH='5%' align=\"center\">\n";
         $html .= "                          <a TITLE='UNIDAD DEL PRODUCTO'>";
         $html .= "                            CANTIDAD";
         $html .= "                          </a>";  
         $html .= "                        </td>\n";
         $valor_unitario= ModuloGetVar('app','Inv_MovimientosBodegas','documentos_valorunitario_'.$resultado['empresa_id']);
         $valor_un=explode(",",$valor_unitario);
        $contar=count($valor_un);
        
          $html .= "                       </tr>\n";
         $valorTotal=0;
         $k=0;
    
      foreach($resultado['DETALLE'] as $doc_val=>$valor)
       {
            $valor_pactado=$consulta->valorunitario($valor['codigo_producto'],$empresa_id);
          
            $porc_iva = ($valor['porcentaje_gravamen']/100)+1;
                 $ValorSubTotal = ($valor['total_costo']/$porc_iva);
                 $IvaProducto=$valor['total_costo']-$ValorSubTotal;
                 $IvaTotal=$IvaTotal+($IvaProducto);
                 $ValorUnitario = ($ValorSubTotal/$valor['cantidad']);
                 $valorTotal=$valorTotal+$valor['total_costo'];
                 
                 $html .= "                    <tr>\n";
                 /*$html .= "                       <td class=\"normal_10AN\" align=\"left\">\n";
                 $html .= "                       ".$valor['movimiento_id'];
                 $html .= "                       </td>\n";*/
                 $html .= "                      <td align=\"left\">\n";
                 $html .= "                       <a>";
                 $html .= "                       ".$valor['codigo_producto'];
                 $html .= "                      </td>\n";
                 $html .= "                      <td align=\"left\">\n";
                 $html .= "                       ".$valor['nombre']."";
                 $html .= "                      </td>\n";
                 $html .= "                      <td align=\"left\">\n";
                 $html .= "                       <a>";
                 $html .= "                       ".$valor['fecha_vencimiento'];
                 $html .= "                      </td>\n";
                  $html .= "                      <td align=\"left\">\n";
                 $html .= "                       <a>";
                 $html .= "                       ".$valor['lote'];
                 $html .= "                      </td>\n";
                 
                 /*$html .= "                      <td align=\"left\">\n";
                 $html .= "                       ".$valor['descripcion_unidad']."";
                 $html .= "                      </td>\n";*/
                 $html .= "                      <td align=\"left\">\n";
                 list($entero,$decimal) = explode(".",$valor['cantidad']);
                 if($decimal>0)
                  {
                   $html .= "                       ".$valor['cantidad'];
                  }
                  else
                  {
                   $html .= "                       ".$entero;
                  } 
                
                 $html .= "                    </tr>\n";
                 
                 $k++;
      }
                 $html .= "                    <tr>\n";
                 $html .= "                      <td colspan='3' align=\"right\">\n";
                 $html .= "                       ";
                 $html .= "                      </td>\n";
                /* if(empty($si_esfarmacia))
                 {
                  $html .= "                      <td align=\"right\">\n";
                  $html .= "                       <label class='label_error'>IVA</label>";
                  $html .= "                      </td>\n";
                  $html .= "                      <td align=\"right\">\n";
                  $html .= "                      ".FormatoValor($IvaTotal);
                  $html .= "                      </td>\n";
                 }*/
                 $html .= "                    </tr>\n";
                 
                 $html .= "                    <tr>\n";
                 $html .= "                      <td colspan='3' align=\"right\">\n";
                 $html .= "                       ";
                 $html .= "                      </td>\n";
                 /*if(empty($si_esfarmacia))
                 {
                  $html .= "                      <td align=\"right\">\n";
                  $html .= "                       <label class='label_error'>TOTAL</label>";
                  $html .= "                      </td>\n";
                  $html .= "                      <td align=\"right\">\n";
                  $html .= "                      ".FormatoValor($valorTotal);
                  $html .= "                      </td>\n";
                 }*/
                 $html .= "                    </tr>\n";
         $html .= "                   </table>\n";
         $html .= "                    <br>\n";
         $html .= "                 <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
         $html .= "                    <tr>\n";
         $html .= "                        <td COLSPAN='1' align=\"center\">\n";
         $html .= "                         <a>";
         $html .= "                           FECHA DE IMPRESION";
         $html .= "                         </a>";
         $html .= "                        </td>\n";
         $html .= "                        <td COLSPAN='1' align=\"center\">\n";
         $html .= "                         <a>";
         $html .= "                           USUARIO IMPRESION";
         $html .= "                         </a>";
         $html .= "                        </td>\n";
         $html .= "                    </tr>\n";
         $html .= "                    <tr>\n";
         $html .= "                        <td COLSPAN='1' align=\"center\">\n";
         $html .= "                         <a>";
         $html .= "                           ".date("Y-m-d");
         $html .= "                         </a>";
         $html .= "                        </td>\n";
         $html .= "                        <td COLSPAN='1' align=\"center\">\n";
         $html .= "                         <a>";
         $USUARIO=$consulta->NombreUsu(UserGetUID());
         $html .= "                          ".UserGetUID()."-".$USUARIO[0]['nombre'];
         $html .= "                         </a>";
         $html .= "                        </td>\n";
         $html .= "                    </tr>\n";
         $html .= "                  </table>\n";
   
			
	    return $html;
		}
	}
?>