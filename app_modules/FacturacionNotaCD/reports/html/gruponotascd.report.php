<?php
	/**************************************************************************************
	* $Id: gruponotascd.report.php,v 1.1.1.1 2009/09/11 20:36:46 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	*
	* 
	**************************************************************************************/
	class gruponotascd_report 
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
	 	function gruponotascd_report($datos=array())
	  {
			$this->datos=$datos;
	  	return true;
	  }
		
		function GetMembrete()
		{
			$estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			$Membrete = array('file'=>false,
							  'datos_membrete'=>array('titulo'=>'<b '.$estilo.' >LISTADO DE NOTAS CREDITO</b>',
										'subtitulo'=>'',
										'logo'=>'logocliente.png',
										'align'=>'left'));
			return $Membrete;
		}
	    //FUNCION CrearReporte()
		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
	  {
			//$estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px; text-indent:7pt\"";
			$estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
			$factura = $this->ObtenerNotas();
			
			$Salida .= "	<table align=\"center\" cellpading=\"0\" cellspacing=\"0\" width=\"90%\" $estilo >\n";
			$Salida .= "		<tr>";
			$Salida .= "			<td ><b>Fecha Impresion ".date("d/m/Y")."</b></td>";
			$Salida .= "		</tr>";
			$Salida .= "	</table><br>";
			$Salida .= "	<table align=\"center\" cellpading=\"0\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\" width=\"85%\" rules=\"all\" $estilo>\n";
			$Salida .= "		<tr>\n";
			$Salida .= "			<td align=\"center\" width=\"8%\"><b>NOTA</b></td>\n";
			$Salida .= "			<td align=\"center\" width=\"%\" ><b>CLIENTE</b></td>\n";
			$Salida .= "			<td align=\"center\" width=\"8%\"><b>Nº GLOSA</b></td>\n";
			$Salida .= "			<td align=\"center\" width=\"10%\"><b>FACTURA</b></td>\n";
			$Salida .= "			<td align=\"center\" width=\"10%\"><b>F. GLOSA</b></td>\n";
			$Salida .= "			<td align=\"center\" width=\"10%\"><b>V. GLOSA</b></td>\n";
			$Salida .= "			<td align=\"center\" width=\"10%\"><b>V. ACEPTADO</b></td>\n";
			$Salida .= "			<td align=\"center\" width=\"10%\"><b>V. NO ACEPTADO</b></td>\n";
			$Salida .= "			<td align=\"center\" width=\"14%\"><b>RESPONSABLE</b></td>\n";
			$Salida .= "		</tr>\n";
			
			for($i=0; $i<sizeof($factura); $i++)
			{
				$Salida .= "			<tr>\n";
				$Salida .= "				<td >".$factura[$i]['prefijo_nota']." ".$factura[$i]['numero']."</td>\n";
				$Salida .= "				<td align=\"justify\">".$factura[$i]['nombre_tercero']."</td>\n";
				$Salida .= "				<td >".$factura[$i]['glosa_id']."</td>\n";
				$Salida .= "				<td >".$factura[$i]['prefijo']." ".$factura[$i]['factura_fiscal']."</td>\n";
				$Salida .= "				<td align=\"center\" >".$factura[$i]['fecha_glosa']."</td>\n";
				$Salida .= "				<td align=\"right\"  >".formatoValor($factura[$i]['valor_glosa'])."</td>\n";
				$Salida .= "				<td align=\"right\"  >".formatoValor($factura[$i]['valor_aceptado'])."</td>\n";
				$Salida .= "				<td align=\"right\"  >".formatoValor($factura[$i]['valor_no_aceptado'])."</td>\n";
				$Salida .= "				<td >".$factura[$i]['nombre']."</b></td>\n";
				$Salida .= "			</tr>\n";
			}
			
			$Salida .= "		</table><br>\n";
		  return $Salida;
		}
		
		/********************************************************************************** 
		* Funcion donde se obtiene el sql que hace la busqueda de facturas segun los 
		* criterios que se hayan dado para la misma, se suben los dos sql (en el que se 
		* cuenta el numero de registros y el que busca los datos y se suben a session) 
		* 
		* @return boolean 
		***********************************************************************************/
		function ObtenerNotas()
		{		
			$empresa_id = $_SESSION['NotasCD']['empresa'];
			
			$sql  = "SELECT	NC.prefijo AS prefijo_nota, ";
			$sql .= "				NC.numero, ";
			$sql .= "				G.glosa_id, ";
			$sql .= "				G.prefijo, ";
			$sql .= "				G.factura_fiscal, ";
			$sql .= "				T.nombre_tercero,	";
			$sql .= "				SU.nombre, ";
			$sql .= "				TO_CHAR(G.fecha_glosa,'DD/MM/YYYY') AS fecha_glosa, ";
			$sql .= "				SUM(NC.valor_glosa) AS valor_glosa, ";
			$sql .= "				SUM(NC.valor_aceptado) AS valor_aceptado, ";
			$sql .= "				SUM(NC.valor_no_aceptado) AS valor_no_aceptado ";
			$sql .= "FROM 	glosas G, ";
			$sql .= "				view_fac_facturas F, ";
			$sql .= "				terceros T, ";
			$sql .= "				system_usuarios SU, ";
			$sql .= "				(	SELECT 	prefijo, ";
			$sql .= "									numero, ";
			$sql .= "									glosa_id, ";
			$sql .= "									usuario_id, ";
			$sql .= "									TO_CHAR(fecha_registro,'DD/MM/YYYY') AS registro, ";
			$sql .= "									COALESCE(SUM(valor_glosa),0) AS valor_glosa, ";
			$sql .= "									COALESCE(SUM(valor_aceptado),0) AS valor_aceptado, ";
			$sql .= "									COALESCE(SUM(valor_no_aceptado),0) AS valor_no_aceptado  ";
			$sql .= "					FROM 		notas_credito_glosas ";
			$sql .= "					GROUP BY 1,2,3,4,5 ";
			$sql .= "					UNION ";
			$sql .= "					SELECT 	prefijo, ";
			$sql .= "									numero, ";
			$sql .= "									glosa_id, ";
			$sql .= "									usuario_id,  ";
			$sql .= "									TO_CHAR(fecha_registro,'DD/MM/YYYY') AS registro, ";
			$sql .= "									COALESCE(SUM(valor_glosa),0) AS valor_glosa,  ";
			$sql .= "									COALESCE(SUM(valor_aceptado),0) AS valor_aceptado, ";
			$sql .= "									COALESCE(SUM(valor_no_aceptado),0) AS valor_no_aceptado  ";
			$sql .= "					FROM 		notas_credito_glosas_detalle_cargos  ";
			$sql .= "					GROUP BY 1,2,3,4,5 ";
			$sql .= "					UNION ";
			$sql .= "					SELECT 	prefijo, ";
			$sql .= "									numero, ";
			$sql .= "									glosa_id, ";
			$sql .= "									usuario_id,  ";
			$sql .= "									TO_CHAR(fecha_registro,'DD/MM/YYYY') AS registro, ";
			$sql .= "									COALESCE(SUM(valor_glosa),0) AS valor_glosa,  ";
			$sql .= "									COALESCE(SUM(valor_aceptado),0) AS valor_aceptado, ";
			$sql .= "									COALESCE(SUM(valor_no_aceptado),0) AS valor_no_aceptado  ";
			$sql .= "					FROM 		notas_credito_glosas_detalle_inventarios  ";
			$sql .= "					GROUP BY 1,2,3,4,5 ";
			$sql .= "				) AS NC ";	
			$sql .= "WHERE 	G.empresa_id = '".$empresa_id."'  ";
			$sql .= "AND 		G.sw_estado <> '0'  ";
			$sql .= "AND 		G.prefijo = F.prefijo  ";
			$sql .= "AND 		G.factura_fiscal = F.factura_fiscal  ";
			$sql .= "AND		F.tercero_id = T.tercero_id  ";
			$sql .= "AND 		F.tipo_id_tercero = T.tipo_id_tercero  ";
			$sql .= "AND 		NC.valor_glosa > 0  ";
			$sql .= "AND 		NC.glosa_id = G.glosa_id  ";
			$sql .= "AND		SU.usuario_id = NC.usuario_id ";

			if($this->datos['fecha_inicio'] != "")
			{
				$fecha = explode("/",$this->datos['fecha_inicio']);
				$sql .= "AND  G.fecha_glosa >= '".$fecha[2]."-".$fecha[1]."-".$fecha[0]." 00:00:00' ";
			}
			if($this->datos['fecha_fin'] != "")
			{
				$fecha = explode("/",$this->datos['fecha_fin']);
				$sql .= "AND  G.fecha_glosa <= '".$fecha[2]."-".$fecha[1]."-".$fecha[0]." 00:00:00' ";
			}
			
			if($this->datos['tercero_id'] != "")
				$sql .= "AND T.tercero_id = ".$this->datos['tercero_id']." ";
			
			if($this->datos['tipo_id_tercero'] != 0)
				$sql .= "AND T.tipo_id_tercero = '".$this->datos['tipo_id_tercero']."' ";
			
			if($this->datos['numero_glosa'] != "")
				$sql .= "AND G.glosa_id = ".$this->datos['numero_glosa']." ";
			
			if($this->datos['nombreTercero'])
				$sql .= "AND	T.nombre_tercero ILIKE '%".$this->datos['nombreTercero']."%' ";
			
			$sql .= "GROUP BY 1,2,3,4,5,6,7,8 ";
			$sql .= "ORDER BY 2 DESC ";
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while (!$rst->EOF)
			{
				$fac[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }			
		    
		  return $fac;
		}
		/**************************************************************************************
		*
		***************************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			$rst = $dbconn->Execute($sql);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			return $rst;
		}
	}
?>