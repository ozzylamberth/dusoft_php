<?php

/**
 * $Id: solicitud_insumos_documentopaciente_html.report.php,v 1.3 2007/05/23 14:55:58 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba formato HTML
 */

class solicitud_insumos_documentopaciente_html_report
{
	//VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
	var $datos;
     var $insumostmp;

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
     function solicitud_insumos_documentopaciente_html_report($datos=array())
     {
          $this->datos = $datos;
          $this->insumostmp = $_SESSION['DATOS_DOCUMENTO'];
          return true;
     }


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
	
	//*******************************************termino	
          $Datos = $this->ObtenerIngresoPaciente($this->insumostmp[1], $this->insumostmp[2]);
		$DatosResponsable = $this->ObtenerDatosResponsables($this->insumostmp[5]);
          $Bodega = $this->BodegaDocumento($this->insumostmp[0], $this->insumostmp[7]);
          $Departamento = $this->DepartamentoDocumento($this->insumostmp[8]);
          
          $Salida .= "	<table width=\"95%\" align=\"center\" border=\"0\">\n";
		$Salida .= "		<tr>\n";
		$Salida .= "			<td align=\"center\" width=\"25%\" height=\"30\"><b>DESCARGO DE MEDICAMENTOS E INSUMOS</b></td>\n";
		$Salida .= "		</tr>\n";     	
          $Salida .= "		<tr>\n";
          $Salida .= "			<td align=\"center\" width=\"25%\" height=\"10\"><b>DESCARGO DE MEDICAMENTOS E INSUMOS No.: ".$this->insumostmp[0]."</b></td>\n";
		$Salida .= "		</tr>\n";
		$Salida .= "	</table><br>";
		
		$Salida .= "	<table width=\"95%\" align=\"center\" border=\"1\">\n";		
		$Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\" align=\"center\" width=\"25%\" colspan=\"4\"><b>DATOS PACIENTE</b></td>\n";		
		$Salida .= "		</tr>\n";		
		$Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\" width=\"15%\"><b>RESPONSABLE</b></td>\n";
		$Salida .= "			<td class=\"normal_10\" width=\"25%\">".$DatosResponsable['nombre_tercero']."</td>\n";
		$Salida .= "			<td class=\"normal_10N\" width=\"15%\"><b>PACIENTE</td>\n";
		$Salida .= "			<td class=\"normal_10\" width=\"45%\">".$Datos['nombre']."</td>\n";
		$Salida .= "		</tr>\n";
	
		$Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\" width=\"15%\">NIT.</td>\n";
		$Salida .= "			<td class=\"normal_10\" width=\"25%\">".$DatosResponsable['tipo_id_tercero']." - ".$DatosResponsable['tercero_id']."</td>\n";
		$Salida .= "			<td class=\"normal_10N\" width=\"15%\"><b>IDENTIFICACION</td>\n";
		$Salida .= "			<td class=\"normal_10\" width=\"45%\">".$this->insumostmp[1]." - ".$this->insumostmp[2]."</td>\n";
		$Salida .= "		</tr>\n";

		$Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\" width=\"15%\">PLAN</td>\n";
		$Salida .= "			<td class=\"normal_10\" width=\"25%\">".$DatosResponsable['plan_descripcion']." ".$Datos[0]['paciente_id']."</td>\n";
		$Salida .= "			<td class=\"normal_10N\" colspan=\"2\">Nº INGRESO : ".$this->insumostmp[6]."</td>\n";
		$Salida .= "		</tr>\n";
		
		$Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\" colspan=\"2\">NIVEL : ".$this->insumostmp[4]."</td>\n";
		$Salida .= "			<td class=\"normal_10N\" colspan=\"2\">Nº CUENTA : ".$this->insumostmp[3]."</td>\n";
		$Salida .= "		</tr>\n";
          
          $Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\">BODEGA :</td>\n";
		$Salida .= "			<td class=\"normal_10\">".$Bodega[1]."</td>\n";
          $Salida .= "			<td class=\"normal_10N\" width=\"15%\">DEPARTAMENTO : </td>\n";
		$Salida .= "			<td class=\"normal_10\" width=\"45%\">".$Departamento[0]."</td>\n";
          $Salida .= "		</tr>\n";

          $Salida .= "	</table><br>\n";
          
          $Salida .= "	<table width=\"95%\" align=\"center\" border=\"1\">\n";		
		$Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\" align=\"center\" width=\"25%\" colspan=\"5\"><b>INSUMOS SOLICITADOS PARA EL PACIENTE</b></td>\n";		
		$Salida .= "		</tr>\n";		
		$Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\" width=\"10%\" align=\"center\"><b>COD. PRODUCTO</b></td>\n";
		$Salida .= "			<td class=\"normal_10N\" width=\"30%\" align=\"center\"><b>DESCRIPCION</b></td>\n";
		$Salida .= "			<td class=\"normal_10N\" width=\"10%\" align=\"center\"><b>CANTIDAD</b></td>\n";          		
		$Salida .= "		</tr>\n";
		
          $detalleDoc = $this->DatosDetalleDelDocumento($this->insumostmp[0], $this->insumostmp[7]);
          
          for($i=0; $i<sizeof($detalleDoc); $i++)
          {
               $Salida .= "		<tr>\n";
               $Salida .= "			<td class=\"normal_10\" align=\"center\">".$detalleDoc[$i]['codigo_producto']."</td>\n";
               $Salida .= "			<td class=\"normal_10\">".$detalleDoc[$i]['descripcion']."</td>\n";
               $Salida .= "			<td class=\"normal_10\" align=\"center\">".$detalleDoc[$i]['cantidad']."</td>\n";          		
               $Salida .= "		</tr>\n";
     	}
          $Salida .= "	</table><br>\n";
     	
          $DatosUS = $this->GetDatosUsuario();
          $largo = strlen($DatosUS['nombre']);
		if($largo < '5')
          {$largo = $largo + '12'; }
          $largo = $largo + '16';
		for ($l=0; $l<$largo; $l++)
		{
      		$cad = $cad.'_';
    		}

          $Salida .= "	<br><br><br><br><table width=\"95%\" align=\"left\" border=\"0\">\n";		
		$Salida .= "		<tr>\n";
          $Salida .= "			<td class=\"normal_10N\" align=\"left\" width=\"95%\">".$cad."";
          $Salida .= "			</td>\n";
          $Salida .= "		</tr>\n";
		$Salida .= "			<td class=\"normal_10N\" align=\"left\" width=\"95%\">USUARIO : ".$DatosUS['nombre']."";
          $Salida .= "				<br>\n";
          $Salida .= "					".strtoupper($DatosUS['descripcion'])."\n";
          $Salida .= "			</td>\n";
		$Salida .= "		</tr>\n";		
          
          $fechita = date("d-m-Y H:i:s");
          $FechaImprime = $this->FechaStamp($fechita);
          $HoraImprime = $this->HoraStamp($fechita);
		
          $Salida .= "		<tr>";
          $Salida .= "			<td ALIGN=\"RIGHT\" WIDTH=\"50%\" class=\"normal_10\"><FONT SIZE='1'>Imprimió:&nbsp;".$DatosUS['nombre']." - ".$DatosUS['usuario']."</FONT></td>\n";
		$Salida .= "		</tr>";
          $Salida .= "		<tr>";
          $Salida .= "			<td ALIGN=\"RIGHT\" WIDTH=\"50%\" class=\"normal_10\"><FONT SIZE='1'>Fecha Impresión :&nbsp;&nbsp;".$FechaImprime." - ".$HoraImprime."</FONT></td>\n";
		$Salida .= "		</tr>";
          $Salida .= "</table><br>\n";

		return $Salida;			
	}	      
	//*****************************************fin de termino
 
	/************************************************************************************ 
     * Funcion que permite traer la informacion de la glosa y el detalle del acta de 
     * conciliacion (si la hay) de las factura pertenecientes a un cliente
     * 
     * @return array datos de las facturas
     *************************************************************************************/
     function ObtenerIngresoPaciente($tipoidpaciente,$paciente)
     {
          list($dbconn) = GetDBconn();
          GLOBAL $ADODB_FETCH_MODE;
          $query  = "SELECT PC.paciente_id,
                              PC.tipo_id_paciente,
                              PC.primer_nombre ||' '|| PC.segundo_nombre ||' '|| PC.primer_apellido ||' '|| PC.segundo_apellido AS nombre
                    FROM	   pacientes PC
                    WHERE   PC.tipo_id_paciente = '".$tipoidpaciente."'
                    AND	   PC.paciente_id = '".$paciente."';";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if($dbconn->ErrorNo() != 0){
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }else{		
                    $Datos = $result->FetchRow();
          }	
          return $Datos;
     }
     
     
     function ObtenerDatosResponsables($plan)
     {
          list($dbconn) = GetDBconn();
          GLOBAL $ADODB_FETCH_MODE;
          $query  = "SELECT A.plan_descripcion,
          			   B.nombre_tercero, B.tipo_id_tercero, B.tercero_id
                     FROM   planes AS A,
                     	   terceros AS B
                     WHERE  plan_id = '".$plan."'
                     AND    B.tipo_id_tercero = A.tipo_tercero_id
                     AND    B.tercero_id = A.tercero_id;";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if($dbconn->ErrorNo() != 0){
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }else{		
                    $Datos = $result->FetchRow();
          }	
          return $Datos;
     }
     
     
     /**
     * Funcion que consulta los datos de los medicamentos o insumos que hacen parte del detalle del documento de la bodega
     * @return boolean
     */
     function DatosDetalleDelDocumento($Documento, $Bodega_id)
     {
	     list($dbconn) = GetDBconn();
          $query="SELECT x.codigo_producto, z.descripcion, x.cantidad, a.bodega,
          		     d.descripcion as bodegadesc 
                  FROM bodegas_documentos_d x,
                  	   inventarios y,
                       inventarios_productos z,
                       bodegas_doc_numeraciones a,
                       bodegas as d
     		   WHERE x.numeracion = '$Documento' 
                  AND x.bodegas_doc_id = '$Bodega_id'
                  AND x.bodegas_doc_id = a.bodegas_doc_id 
                  AND y.empresa_id = a.empresa_id
                  AND a.bodega = d.bodega 
                  AND x.codigo_producto = y.codigo_producto 
                  AND x.codigo_producto = z.codigo_producto";
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
     
     
     /**
     * Funcion que trae la bodega.
     * @return boolean
     */
    function BodegaDocumento($Documento, $Bodega_id)
     {
	     list($dbconn) = GetDBconn();
          $query="SELECT a.bodega,
          		     d.descripcion 
                  FROM bodegas_documentos_d x,
                       bodegas_doc_numeraciones a,
                       bodegas as d
     		   WHERE x.numeracion = '$Documento' 
                  AND x.bodegas_doc_id = '$Bodega_id'
                  AND x.bodegas_doc_id = a.bodegas_doc_id 
                  AND a.bodega = d.bodega;";
          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Guardar en la Base de Datos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          $Bodega = $result->FetchRow();
          return $Bodega;
     }
     

     /**
     * Funcion que trae la bodega.
     * @return boolean
     */
     function DepartamentoDocumento($Dpto)
     {
	     list($dbconn) = GetDBconn();
          $query="SELECT descripcion
                  FROM departamentos
     		   WHERE departamento = '$Dpto';";
          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Guardar en la Base de Datos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          $Departamento = $result->FetchRow();
          return $Departamento;
     }

     
     function GetDatosUsuario()
     {
          list($dbconn) = GetDBconn();
          GLOBAL $ADODB_FETCH_MODE;
          $query  = "SELECT *
                     FROM   system_usuarios
                     WHERE  usuario_id = ".UserGetUID().";";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if($dbconn->ErrorNo() != 0){
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }else{		
                    $Datos = $result->FetchRow();
          }	
          return $Datos;
     
     }
     
     
     function FechaStamp($fecha)
	{
		if($fecha){
               $fech = strtok ($fecha,"-");
               for($l=0;$l<3;$l++)
               {
                    $date[$l]=$fech;
                    $fech = strtok ("-");
               }
               return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
		}
	}
     

     function HoraStamp($hora)
	{
		$hor = strtok ($hora," ");
		for($l=0;$l<4;$l++)
		{
               $time[$l]=$hor;
               $hor = strtok (":");
		}

		$x = explode (".",$time[3]);
		return  $time[1].":".$time[2].":".$x[0];
	}
     
    //---------------------------------------
}

?>
