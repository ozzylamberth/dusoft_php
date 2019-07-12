<?php
	/**************************************************************************************
	* $Id: carteracuentas.report.php,v 1.4 2007/05/16 22:34:59 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	**************************************************************************************/
	include_once "./app_modules/Cartera/classes/Cartera.class.php";		
	class carteracuentas_report extends Cartera
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
	  function carteracuentas_report($datos=array())
	  {
			$this->datos=$datos;
	    return true;
	  }
		
		function GetMembrete()
		{
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:12px\"";
			$titulo .= "REPORTE TOTALES DE CUENTAS SIN FACTURAR</b>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'subtitulo'=>' ',
							  'logo'=>'logocliente.png',
							  'align'=>'left'));
			return $Membrete;
		}

		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
	  {
			$Cuentas = $this->ObtenerCuentasSinFacturar($this->datos['estado'],$this->datos['deptno'],$_SESSION['cartera']['empresa_id']);
			$estilo1 = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px; text-indent:6pt\""; 

			if(sizeof($Cuentas) > 0)
			{
				$final = 0;
				if($this->datos['deptno'])
				{
					$dpto = $this->ObtenerDepartamento($this->datos['deptno']);
					$Salida .= "<center>\n";
					$Salida .= "	<label class=\"label\">TOTAL DE CUENTAS PARA EL DEPARTAMENTO ".strtoupper($dpto['descripcion'])."";
					$Salida .= "</center><br>\n";
				}
				
				$Salida .= "<table width=\"90%\" border=\"1\" bordercolor=\"#000000\"  align=\"center\" cellpading=\"0\" cellspacing=\"0\"  $estilo1>\n";
				foreach($Cuentas as $key => $cuenta)
				{		
					$Salida .= "	<tr height=\"16\">\n";
					$Salida .= "		<td width=\"35%\" ><b>TOTAL CUENTAS ".$key."S</b></td>\n";
					$Salida .= "		<td align=\"right\"><b>$".formatoValor($cuenta['total_cuenta'])."</b></td>\n";
					$Salida .= "	</tr>\n";
					$final += $cuenta['total_cuenta'];
				}
				$Salida .= "	<tr height=\"16\">\n";
				$Salida .= "		<td ><b>TOTAL SIN FACTURAR</b></td>\n";
				$Salida .= "		<td align=\"right\" width=\"15%\"><b>$".formatoValor($final)."</b></td>\n";
				$Salida .= "	</tr>\n";	
				$Salida .= "</table><br>\n";
			}
			else
			{
				$Salida .= "			<center><b class=\"label\">NO HAY CUENTAS SIN FACTURAR</b></center>\n";
			}
	    return $Salida;
		}
		/***************************************************************************************
		* Funciuon donde se consultan las cuentas que no han sido facturadas 
		* 
		* @params char 		$estado 			Indica el estado en el del cual se quiere obtener las cuentas
		* @params string 	$departamento Indica el departamento del cual se desea obtener las 
		*																facturas
		* @return array 	Datos de las cuentas sin facturar - numerodecuenta,total_cuenta,estado,
		*									descripcion,fecha
		****************************************************************************************/
		function ObtenerCuentasSinFacturar($estado,$departamento,$empresa)
		{
			$sql .= "SELECT	SUM(CU.total_cuenta) AS total_cuenta, ";
			$sql .= "				CE.descripcion AS estado ";
			$sql .= "FROM		departamentos DE,";
			$sql .= "				cuentas CU, ";
			$sql .= "				cuentas_estados CE,";
			$sql .= "				ingresos IG ";
			$sql .= "WHERE	CU.ingreso = IG.ingreso ";
			$sql .= "AND		CU.total_cuenta  > 0 ";
			$sql .= "AND		IG.departamento_actual = DE.departamento ";
			$sql .= "AND		CU.estado = CE.estado ";
			$sql .= "AND		CU.empresa_id = '".$empresa."' ";
			
			if($estado)
				$sql .= "AND		CU.estado = '".$estado."' ";
			else
				$sql .= "AND		CU.estado IN ('1','2','3')";
			
			if($departamento)
				$sql .= "AND		DE.departamento = '".$departamento."' ";
			
			$sql .= "Group BY CE.descripcion ";
			$sql .= "ORDER BY CE.descripcion ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
			
			$cuentas = array();
			
			while(!$rst->EOF)
			{
				$cuentas[$rst->fields[1]] =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $cuentas;
		}
		
		function ObtenerDepartamento($departamento)
		{
			$sql .= "SELECT	descripcion ";
			$sql .= "FROM		departamentos ";
			$sql .= "WHERE	departamento = '".$departamento."' ";
				
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$departamento = array();
			
			while(!$rst->EOF)
			{
				$departamento =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $departamento;
		}
	}
?>