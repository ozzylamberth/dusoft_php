<?php

/**
 * $Id: SolicitudesDevolucionesDpto_html.report.php,v 1.5 2007/06/28 21:41:41 luis Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba formato HTML
 */

class SolicitudesDevolucionesDpto_html_report
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
	function SolicitudesDevolucionesDpto_html_report($datos=array())
	{

			$this->datos=$datos;
			return true;
	}

	function CrearReporte()
	{

		$style= "style=\"font-size:12px; font-weight:bold;\"";
		$style1= "style=\"font-size:12px\"";

		$Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
		$Salida.="	<tr>";
		$Salida.="		<td><img src=\"../../../../images/logocliente.png\" border=\"0\"></td>";
		$Salida.="	</tr>";
		$Salida.="</table><br>";
		
		$Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
		$Salida.="<tr>";
		$Salida.="  <td $style align=\"center\" width=\"100%\">SOLICITUDES DE DEVOLUCION DE MEDICAMENTOS E INSUMOS</td>";
		$Salida.="</tr>";
		$Salida.="<tr>";
		$Salida.="  <td $style align=\"center\" width=\"100%\">ESTACION :&nbsp&nbsp&nbsp; ".$this->datos['estacion']."</td>";
		$Salida.="</tr>";
		$Salida.="<tr>";
		$Salida.="  <td $style align=\"center\" width=\"100%\">BODEGA :&nbsp&nbsp&nbsp; ".$_SESSION['Bodegas']['bodega']." - ".$_SESSION['Bodegas']['bodega_desc']."</td>";
		$Salida.="</tr>";
		$Salida.="</table><BR>";
		$marca=0;
		$vector=$this->DatosDevolucionesDepartamento($this->datos['estacion_id'],$this->datos['SolicitudId']);
		if($vector){
			foreach($vector as $paciente=>$vector){
				$pacienteAnt=-1;
				foreach($vector as $devolucionId=>$vector1){
					$devolucionIdAnt=-1;
					foreach($vector1 as $consecutivoId=>$datos){
						if($paciente!=$pacienteAnt){
							$pacienteAnt=$paciente;
							if($marca==1){
								$Salida.="<BR><BR>";
							}
							$Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
							$Salida.="<tr>";
							$Salida.="  <td $style align=\"left\" width=\"15%\">PACIENTE</td>";
							$Salida.="  <td $style1  align=\"left\" colspan=\"3\">$paciente ".$datos['nombrepac']."</td>";
							$Salida.="</tr>";
							$Salida.="<tr>";
							$Salida.="  <td $style align=\"left\" width=\"15%\">PLAN</td>";
							$Salida.="  <td $style1  align=\"left\" width=\"30%\">".$datos['plan_descripcion']."</td>";
							$Salida.="  <td $style align=\"left\" width=\"15%\">PIEZA Y CAMA</td>";
							$Salida.="  <td $style1  align=\"left\">".$datos['pieza']." ".$datos['cama']."</td>";
							$Salida.="</tr>";
							$Salida.="<tr>";
							$Salida.="  <td $style align=\"left\" width=\"15%\">TIPO AFILIADO</td>";
							$Salida.="  <td $style1  align=\"left\" width=\"30%\">".$datos['tipo_afiliado_id']."</td>";
							$Salida.="  <td $style align=\"left\" width=\"15%\">RANGO</td>";
							$Salida.="  <td $style1  align=\"left\">".$datos['rango']."</td>";
							$Salida.="</tr>";
							$Salida.="</table>";
							$marca=1;
							if($devolucionId!=$devolucionIdAnt){
								$Salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
								$Salida.="<tr>";
								$Salida.="  <td $style align=\"left\" width=\"15%\">No. DEVOLUCION</td>";
								$Salida.="  <td $style1  align=\"left\" width=\"30%\">$devolucionId</td>";
								$Salida.="  <td $style align=\"left\" width=\"15%\">FECHA</td>";
								(list($fecha,$HoraTot)=explode(' ',$datos['fecha_registro']));
								(list($ano,$mes,$dia)=explode('-',$fecha));
								(list($hora,$min)=explode(':',$HoraTot));                  
								$Salida.="  <td $style1  align=\"left\">".strftime('%b %d de %Y  %H:%M',mktime($hora,$min,0,$mes,$dia,$ano))."</td>";
								$Salida.="</tr>";
								$Salida.="<tr>";
								$Salida.="  <td $style align=\"left\" width=\"15%\">ESTACION</td>";
								$Salida.="  <td $style1  align=\"left\" width=\"30%\">".$datos['nomestacion']."</td>";
								$Salida.="  <td $style align=\"left\" width=\"15%\">USUARIO QUE DEVUELVE</td>";
								$Salida.="  <td $style1  align=\"left\">".$datos['usuario_id'].' - '.$datos['usuarioestacion']."</td>";
								$Salida.="</tr>";
								$Salida.="</table>";
								$devolucionIdAnt=$devolucionId;
								$Salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
								$Salida.="<tr>";
								$Salida.="  <td $style align=\"center\" width=\"15%\">CODIGO</td>";
								$Salida.="  <td $style align=\"center\" width=\"60%\">MEDICAMENTO</td>";
								$Salida.="  <td $style  align=\"center\" width=\"25%\">CANTIDAD</td>";
								$Salida.="</tr>";
								$Salida.="</table>";
								$Salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
								$Salida.="<tr>";
								$Salida.="  <td $style1 align=\"left\" width=\"15%\">".$datos['codigo_producto']."</td>";
								$Salida.="  <td $style1 align=\"left\" width=\"60%\">".$datos['desmed']."</td>";
								$Salida.="  <td $style1  align=\"left\" width=\"25%\">".FormatoValor($datos['cantidad'])." ".$datos['abreviatura']."</td>";
								$Salida.="</tr>";
								if($datos['ubicacion']){
								$Salida.="<tr>";
								$Salida.="  <td colspan=\"3\" $style1 align=\"left\">".$datos['ubicacion']."</td>";
								$Salida.="</tr>";
								}
								$Salida.="</table>";
							}else{
								$Salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
								$Salida.="<tr>";
								$Salida.="  <td $style1 align=\"left\" width=\"15%\">".$datos['codigo_producto']."</td>";
								$Salida.="  <td $style1 align=\"left\" width=\"60%\">".$datos['desmed']."</td>";
								$Salida.="  <td $style1  align=\"left\" width=\"25%\">".FormatoValor($datos['cantidad'])." ".$datos['abreviatura']."</td>";
								$Salida.="</tr>";
								if($datos['ubicacion']){
								$Salida.="<tr>";
								$Salida.="  <td colspan=\"3\" $style1 align=\"left\">".$datos['ubicacion']."</td>";
								$Salida.="</tr>";
								}
								$Salida.="</table>";
							}
						}else{
							if($devolucionId!=$devolucionIdAnt){
								$Salida.="<BR><table  align=\"center\" border=\"1\"  width=\"100%\">";
								$Salida.="<tr>";
								$Salida.="  <td $style align=\"left\" width=\"15%\">No. DEVOLUCION</td>";
								$Salida.="  <td $style1  align=\"left\" width=\"30%\">$devolucionId</td>";
								$Salida.="  <td $style align=\"left\" width=\"15%\">FECHA</td>";
								(list($fecha,$HoraTot)=explode(' ',$datos['fecha_registro']));
								(list($ano,$mes,$dia)=explode('-',$fecha));
								(list($hora,$min)=explode(':',$HoraTot));                  
								$Salida.="  <td $style1  align=\"left\">".strftime('%b %d de %Y  %H:%M',mktime($hora,$min,0,$mes,$dia,$ano))."</td>";                    
								$Salida.="</tr>";
								$Salida.="<tr>";
								$Salida.="  <td $style align=\"left\" width=\"15%\">ESTACION</td>";
								$Salida.="  <td $style1  align=\"left\" width=\"30%\">".$datos['nomestacion']."</td>";
								$Salida.="  <td $style align=\"left\" width=\"15%\">USUARIO QUE DEVUELVE</td>";
								$Salida.="  <td $style1  align=\"left\">".$datos['usuario_id'].' - '.$datos['usuarioestacion']."</td>";
								$Salida.="</tr>";
								$Salida.="</table>";
								$devolucionIdAnt=$devolucionId;
								$Salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
								$Salida.="<tr>";
								$Salida.="  <td $style align=\"center\" width=\"15%\">CODIGO</td>";
								$Salida.="  <td $style align=\"center\" width=\"60%\">MEDICAMENTO</td>";
								$Salida.="  <td $style  align=\"center\" width=\"25%\">CANTIDAD</td>";
								$Salida.="</tr>";
								$Salida.="</table>";
								$Salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
								$Salida.="<tr>";
								$Salida.="  <td $style1 align=\"left\" width=\"15%\">".$datos['codigo_producto']."</td>";
								$Salida.="  <td $style1 align=\"left\" width=\"60%\">".$datos['desmed']."</td>";
								$Salida.="  <td $style1  align=\"left\" width=\"25%\">".FormatoValor($datos['cantidad'])." ".$datos['abreviatura']."</td>";
								$Salida.="</tr>";
								if($datos['ubicacion']){
								$Salida.="<tr>";
								$Salida.="  <td colspan=\"3\" $style1 align=\"left\">".$datos['ubicacion']."</td>";
								$Salida.="</tr>";
								}
								$Salida.="</table>";
							}else{
								$Salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
								$Salida.="<tr>";
								$Salida.="  <td $style1 align=\"left\" width=\"15%\">".$datos['codigo_producto']."</td>";
								$Salida.="  <td $style1 align=\"left\" width=\"60%\">".$datos['desmed']."</td>";
								$Salida.="  <td $style1  align=\"left\" width=\"25%\">".FormatoValor($datos['cantidad'])." ".$datos['abreviatura']."</td>";
								$Salida.="</tr>";
								if($datos['ubicacion']){
								$Salida.="<tr>";
								$Salida.="  <td colspan=\"3\" $style1 align=\"left\">".$datos['ubicacion']."</td>";
								$Salida.="</tr>";
								}
								$Salida.="</table>";
							}
						}
					}
				}
			}
		}
		$usuario=$this->GetInfoUsuario();
		
		$Salida.= "<br><div align=\"right\"><label style=\"font-size:10px\"> Imprimió: ".$usuario[usuario_id]." - ".$usuario[nombre]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha Impresión:&nbsp;&nbsp;".date("Y-m-d , g:i a")."</label></div>";
		
		//$solicitudes=explode(".-.",$this->datos['solicitudes']);
		//$this->UpdateImpreso($solicitudes);
		
		echo $Salida;
	}
	
	
	function UpdateImpreso($solicitudes)
	{
		list($dbconn) = GetDBconn();
		
		
		$query1="	SELECT max(sw_impreso)
							FROM inv_solicitudes_devolucion";
			
		$result = $dbconn->Execute($query1);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		$num=$result->fields[0];
		
		foreach($solicitudes as $soli)
		{
			$query1="	SELECT sw_impreso
								FROM inv_solicitudes_devolucion
								WHERE documento=$soli;";
				
			$result1 = $dbconn->Execute($query1);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$sw_impreso=$result1->fields[0];
			
			if($sw_impreso==0)
			{
				$query="	UPDATE inv_solicitudes_devolucion
									SET sw_impreso=".($num+1)."
									WHERE documento=$soli;";
				
				$result = $dbconn->Execute($query);
				
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					echo $dbconn->ErrorMsg();
					return false;
				}
			}
			else break;
		}
		return true;
	}
	
	
	function GetInfoUsuario($usuario_id)
	{
		if(!$usuario_id)
			$usuario_id=UserGetUID();
		
		list($dbconn) = GetDBconn();
		
		$query="SELECT *
						FROM system_usuarios
						WHERE usuario_id=$usuario_id";
		
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$datos=$result->RecordCount();
			if($datos)
			{
				while(!$result->EOF)
				{
					$vars=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		return $vars;
	}
    //AQUI TODOS LOS METODOS QUE USTED QUIERA

	function DatosDevolucionesDepartamento($estacion_id,$solicitud){

		list($dbconn) = GetDBconn();
    
		if($solicitud)
		{
			$cond="AND a.documento=$solicitud";
		}
		
		$query = "(SELECT i.tipo_id_paciente||' '||i.paciente_id,a.documento,det.consecutivo,a.estacion_id,a.fecha_registro,a.ingreso,d.nombre as usuarioestacion,a.usuario_id,c.descripcion as deptoestacion,
		e.rango,k.tipo_afiliado_nombre as tipo_afiliado_id,h.plan_descripcion,
		l.primer_nombre||' '||l.segundo_nombre||' '||l.primer_apellido||' '||l.segundo_apellido as nombrepac,
		j.cama,j.pieza,b.descripcion as nomestacion,
		det.codigo_producto,invp.descripcion as desmed,det.cantidad,bu.descripcion as ubicacion,u.abreviatura,exis.existencia
		FROM inv_solicitudes_devolucion a,estaciones_enfermeria b,departamentos c,system_usuarios d,cuentas e
    LEFT JOIN movimientos_habitacion f ON(e.numerodecuenta=f.numerodecuenta AND f.fecha_egreso is NULL)
		LEFT JOIN camas j ON(f.cama=j.cama)
		,planes h,ingresos i,tipos_afiliado k,pacientes l,inv_solicitudes_devolucion_d det
		,inventarios_productos invp
		LEFT JOIN existencias_bodegas exis ON (invp.codigo_producto=exis.codigo_producto AND exis.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' AND exis.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' AND exis.bodega='".$_SESSION['Bodegas']['bodega']."')
		LEFT JOIN bodegas_ubicaciones bu ON (exis.ubicacion_id=bu.ubicacion_id)
		,unidades u
		WHERE a.bodega='".$_SESSION['Bodegas']['bodega']."' AND a.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' AND
		a.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' AND a.estado='0' AND a.estacion_id=b.estacion_id AND b.estacion_id='".$estacion_id."'
		$cond
		AND b.departamento=c.departamento AND a.usuario_id=d.usuario_id AND a.ingreso=e.ingreso AND (e.estado='1' OR e.estado='2')
		AND a.ingreso=i.ingreso AND e.plan_id=h.plan_id AND k.tipo_afiliado_id=e.tipo_afiliado_id AND i.tipo_id_paciente=l.tipo_id_paciente AND i.paciente_id=l.paciente_id AND
		a.documento=det.documento AND det.estado='0' AND
		det.codigo_producto=invp.codigo_producto AND invp.unidad_id=u.unidad_id
		ORDER BY l.tipo_id_paciente,l.paciente_id,a.fecha)";

		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
				while(!$result->EOF) {
					$vars[$result->fields[0]][$result->fields[1]][$result->fields[2]]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		$result->Close();
		return $vars;

	}

}

$VISTA = "HTML";
$_ROOT = "../../../../";
include	 $_ROOT."includes/enviroment.inc.php";
$filename="themes/".$VISTA."/".GetTheme()."/module_theme.php";
IncludeFile($filename);


$obj=new SolicitudesDevolucionesDpto_html_report($_REQUEST);
$obj->CrearReporte();

?>
