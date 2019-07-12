<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Pedido.report.php,v 1.5 2010/01/02  
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Sandra Viviana Pantoja T.
  */
  /**
  * Clase Reporte: Pedido_report
  * 
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2010  IPSOFT - SA (www.ipsoft-sa.com)
  * @author Sandra Viviana Pantoja T.
  */

	class Pedido_report 
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
		function Pedido_report($datos=array())
		{
			$this->datos=$datos;
			return true;
		}
		
		function GetMembrete()
		{
		    
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			$titulo .= " <b $estilo>DOCUMENTO DE PEDIDO No ".$this->datos['solicitud_prod_a_bod_ppal_id']." </b>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'subtitulo'=>' ',
							  'logo'=>'logocliente.png',
							  'align'=>'left'));
			return $Membrete;
		}

		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
		{
			IncludeClass('ConexionBD');
			IncludeClass('PedidosFarmacia_A_BodegaPrincipalSQL','','app','PedidosFarmacia_A_BodegaPrincipal');
			$est  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
			$ods = new PedidosFarmacia_A_BodegaPrincipalSQL();
      /*
      * Correcion al Reporte Minimizando el codigo y trayendo los tipos de productos,
      * haciendo mas dinamico el reporte.
      * Mauro
      */
       $tproducto = $ods->TipoProductos();
      //print_r($tproducto);
			//$Inf = $ods->ObtenerDetalleDeSolicitu($this->datos['solicitud_prod_a_bod_ppal_id'],$this->datos['empresa_id'],$this->datos['bodega'],$this->datos['centroU'],"-1");
	  $InfEmp = $ods->ObtenerInformacion($this->datos['empresa_id']);
      $nombre=$ods->GetNombreUsuarioImprime();
      $ped=$ods->ObtenerDetalleDeSolicitu2($this->datos['solicitud_prod_a_bod_ppal_id']);
      $cab=$ods->ObtenerCabecera($this->datos['solicitud_prod_a_bod_ppal_id']);
      $ped=$ods->ObtenerDetalleDeSolicitu2($this->datos['solicitud_prod_a_bod_ppal_id']);
	  $ped2=$ods->ConsultarUsuarioRealizaPedido($this->datos['solicitud_prod_a_bod_ppal_id']);
			
      
			/*print_r($cab);*/
			
    	$sty = " style=\"text-align:left;text-indent:8pt\" ";
			
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
			$estilo2 = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px; text-indent:6pt\""; 
			$estilo3 = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\""; 
			$Salida .= "	<table border=\"1\" align =\"center\" cellpading=\"0\" cellspacing=\"0\" bordercolor=\"#000000\" width=\"100%\" rules=\"all\">\n";
			$Salida .= "		<tr $estilo2 height=\"21\">\n";
			$Salida .= "			<td width=\"15%\" ><b>PAIS:</b></td>\n";
			$Salida .= "			<td width=\"25%\"><b>".$InfEmp['0']['pais']."</b></td>\n";
			$Salida .= "			<td width=\"15%\" ><b>DEPARTAMENTO - MUNICIPIO:</b></td>\n";
			$Salida .= "			<td width=\"25%\"><b>".$InfEmp['0']['departamento']."- ".$InfEmp['0']['municipio']."</b></td>\n";
    		$Salida .= "		</tr>\n";
			$Salida .= "		<tr $estilo2 height=\"21\">\n";
			$Salida .= "			<td width=\"25%\" ><b>COD. FARMACIA:</b></td>\n";
			$Salida .= "			<td width=\"25%\" ><b>".$InfEmp['0']['empresa_id']."-".$cab['centro_utilidad']."</b></td>\n";
			$Salida .= "			<td width=\"25%\" ><b>FARMACIA:</b></td>\n";
			$Salida .= "			<td width=\"55%\"><b>".$InfEmp['0']['razon_social']."-".$cab['nombre_bodega']."</b></td>\n";
			$Salida .= "		</tr>\n";
			$Salida .= "		<tr $estilo2 height=\"21\">\n";
			$Salida .= "			<td width=\"25%\" ><b>DIRECCION:</b></td>\n";
			$Salida .= "			<td ><b>".$InfEmp['0']['direccion']."</b></td>\n";
           	$Salida .= "			<td width=\"25%\"><b>TELÉFONO:</b></td>\n";
			$Salida .= "			<td ><b>".$InfEmp['0']['telefonos']."</td>\n";
			$Salida .= "		</tr>\n";
			/*$Salida .= "		<tr $estilo2 height=\"21\">\n";
			$Salida .= "			<td width=\"25%\"><b>FAX:</b></td>\n";
			$Salida .= "			<td ><b>".$InfEmp['0']['fax']."</td>\n";
			$Salida .= "			<td width=\"25%\"><b>E-MAIL:</b></td>\n";
			$Salida .= "			<td ><b>".$InfEmp['0']['email']."</td>\n";
			$Salida .= "		</tr>\n";*/
			$Salida .= "		<tr $estilo2 height=\"21\">\n";
			$Salida .= "			<td colspan=\"4\"><label class=\"label_error\">Observacion :</label> ".$cab['observacion']."</td>";
			$Salida .= "		</tr>\n";
			$Salida .= "	</table><br>\n";

			foreach($tproducto as $key => $tp)
      {
				
        $Productos = $ods->ObtenerDetalleDeSolicitu($this->datos['solicitud_prod_a_bod_ppal_id'],$this->datos['empresa_id'],$this->datos['bodega'],$this->datos['centroU'],$tp['tipo_producto_id'],"-1");
        if(!empty($Productos))
        {
        $Salida .= "<table width=\"95%\" align=\"center\" cellpading=\"0\" cellspacing=\"0\">\n";
        $Salida .= "  <tr height=\"21\">\n";
				$Salida .= "    <td><b> - PEDIDO POR MEDICAMENTOS ".$tp['descripcion'].": ";
				$Salida .= "    </td>\n"; 
				$Salida .= "  </tr>\n"; 
				$Salida .= "</table>\n"; 
				$Salida .= "	<table width=\"100%\" style=\"border:1px solid #000000;font-size:9.5px;\" rules=\"all\" align=\"center\">\n";
				$Salida .= "		<tr $estilo2 height=\"21\" align=\"center\">\n";
				//$Salida .= "			<td width=\"25%\" ><b>MOLECULA</b></td>\n";
				//$Salida .= "			<td width=\"25%\" ><b>LOCALIZACION</b></td>\n";
				$Salida .= "			<td ><b>CODIGO</b></td>\n";
				$Salida .= "			<td ><b>PRODUCTO</b></td>\n";
				$Salida .= "			<td ><b>CANTIDAD</td>\n";
				$Salida .= "			<td width=\"5%\"><b>RQ.AUT</td>\n";
				$Salida .= "			<td ><b>OBS</td>\n";
				$Salida .= "		</tr>\n";
              foreach($Productos as $key => $prod)
              {
							$Salida .= "		<tr >\n";
							//$Salida .= "			<td >".$da['molecula']."</td>\n";
        					//$Salida .= "			<td >".$da['localiza']."</td>\n";
        					$Salida .= "			<td >".$prod['codigo_producto']."</td>\n";
        					$Salida .= "			<td >".$prod['producto']."</td>\n";
        					$Salida .= "			<td >".FormatoValor($prod['cantidad_solic'])."</td>\n";
        					$Salida .= "			<td align=\"center\">";
							if($prod['sw_requiereautorizacion_despachospedidos']=='1')
							$Salida .= " 			<img title=\"EL PRODUCTO REQUIERE AUTORIZACION PARA SER DESPACHADO\" src=\"".GetThemePath()."/images/alarma.gif\" border='0' >	";
        					$Salida .= "			</td>";
							$Salida .= "			<td > ";
							$Salida .= "				<i><u><b>";
							$Salida .= "					".$prod['observacion'];
							$Salida .= "				</b><u></i>";
							
							$Salida .= "			</td>\n";
        					$Salida .= "		</tr>\n";
             }
			$Salida .= "    </table>\n";
		  
        }
      }
			
		  	    
			$Salida .= "             <table  width=\"95%\">\n";
            $Salida .= "             <tr class=\"label\"  valign=\"bottom\" >\n";
            $Salida .= "                <td align=\"LEFT\" height=\"50\">________________________________________</td>\n";
            $Salida .= "              </tr>\n";        
            $Salida .= "               <tr class=\"label\" >\n";
            $Salida .= "                <td align=\"LEFT\">FIRMA Y SELLO AUTORIZADO</td>\n";
            $Salida .= "               </tr>\n";
            $Salida .= "	</table>\n";
			$Salida .= "   <table align='right' border='0' width='95%' style=\"border:1px solid #000000;font-size:7.5px;\">";
			$Salida .= "       <tr align='right'>\n";
			$Salida .= "         <td width='50%' align=\"right\" >";
			$Salida .= "           USUARIO :";
			$Salida .= "       ".$nombre[0]['nombre']."&nbsp;";
			$Salida .= "      </td>\n";
			$Salida .= "       </tr>\n";
			$Salida .= "       <tr align='right'>\n";
			$Salida .= "         <td width='50%' align=\"right\" >";
			$Salida .= "       ".$nombre[0]['descripcion']."&nbsp;";
			$Salida .= "      </td>\n";
			$Salida .= "       </tr>\n";
			$Salida .= "       <tr align='right'>\n";
			$Salida .= "         <td width='50%' align=\"right\" >";
			$Salida .= "       FECHA DE IMPRESION :".date("Y-m-d (H:i:s a)")."&nbsp;";
			$Salida .= "     </td>\n";
			$Salida .= "     </tr>\n";
			$Salida .= "       <tr align='right'>\n";
			$Salida .= "         <td width='50%' align=\"right\" >";
			$Salida .= "           USUARIO (REALIZO EL PEDIDO) :";
			$Salida .= "       ".$ped2[0]['nombre']."&nbsp;";
			$Salida .= "      </td>\n";
			$Salida .= "       </tr>\n";
			$Salida .= "       <tr align='right'>\n";
			$Salida .= "         <td width='50%' align=\"right\" >";
			$Salida .= "       ".$ped2[0]['descripcion']."&nbsp;";
			$Salida .= "      </td>\n";
			$Salida .= "       </tr>\n";
			$Salida .= "       <tr align='right'>\n";
			$Salida .= "         <td width='50%' align=\"right\" >";
			$Salida .= "       FECHA DE REGISTRO :".$ped2[0]['fecha_registro']."&nbsp;";
			$Salida .= "     </td>\n";
			$Salida .= "     </tr>\n";
			$Salida .= "    </table>\n";
            return $Salida;
		}
		
	}
?>