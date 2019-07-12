<?php

/**
 * $Id: solicitud_insumos_cuentapaciente_html.report.php,v 1.3 2007/05/23 14:55:58 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba formato HTML
 */

class solicitud_insumos_cuentapaciente_html_report
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
     function solicitud_insumos_cuentapaciente_html_report($datos=array())
     {
          $this->datos = $datos;
          $this->insumostmp = $_SESSION['DATOS_INSUMOSTMP'];
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
          $Datos = $this->ObtenerIngresoPaciente($this->datos['tipo_id_paciente'], $this->datos['paciente_id']);
		$DatosResponsable = $this->ObtenerDatosResponsables($this->datos['plan']);
          
          $Salida .= "	<table width=\"95%\" align=\"center\" border=\"0\">\n";
		$Salida .= "		<tr>\n";
		$Salida .= "			<td align=\"center\" width=\"25%\" height=\"30\"><b>DESCARGO DE MEDICAMENTOS E INSUMOS</b></td>\n";		
		$Salida .= "		</tr>\n";
          $Salida .= "		<tr>\n";
          $Salida .= "			<td align=\"center\" width=\"25%\" height=\"30\"><b>DESCARGO DE MEDICAMENTOS E INSUMOS No.: ".$this->datos['Documento']."</b></td>\n";
		$Salida .= "		</tr>\n";
		$Salida .= "	</table><br>";
		
		$Salida .= "	<table width=\"95%\" align=\"center\" border=\"1\">\n";		
		$Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\" align=\"center\" width=\"25%\" colspan=\"4\"><b>DATOS PACIENTE</b></td>\n";		
		$Salida .= "		</tr>\n";		
		$Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\" width=\"15%\"><b>RESPONSABLE</b></td>\n";
		$Salida .= "			<td class=\"normal_10\" width=\"25%\">".$DatosResponsable['nombre_tercero']."</td>\n";
		$Salida .= "			<td class=\"normal_10N\" width=\"10%\"><b>PACIENTE</td>\n";
		$Salida .= "			<td class=\"normal_10\" width=\"50%\">".$Datos['nombre']."</td>\n";
		$Salida .= "		</tr>\n";
	
		$Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\" width=\"15%\">NIT.</td>\n";
		$Salida .= "			<td class=\"normal_10\" width=\"25%\">".$DatosResponsable['tipo_id_tercero']." - ".$DatosResponsable['tercero_id']."</td>\n";
		$Salida .= "			<td class=\"normal_10N\" width=\"10%\"><b>IDENTIFICACION</td>\n";
		$Salida .= "			<td class=\"normal_10\" width=\"50%\">".$this->datos['tipo_id_paciente']." - ".$this->datos['paciente_id']."</td>\n";
		$Salida .= "		</tr>\n";

		$Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\" width=\"15%\">PLAN</td>\n";
		$Salida .= "			<td class=\"normal_10\" width=\"25%\">".$DatosResponsable['plan_descripcion']." ".$Datos[0]['paciente_id']."</td>\n";
		$Salida .= "			<td class=\"normal_10N\" colspan=\"2\">Nº INGRESO : ".$this->datos['ingreso']."</td>\n";
		$Salida .= "		</tr>\n";
		
		$Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\" width=\"15%\">BODEGA</td>\n";
		$Salida .= "			<td class=\"normal_10\" width=\"25%\">".$this->datos['bodega']['BODEGA']['descripcion']."</td>\n";
		$Salida .= "			<td class=\"normal_10N\" colspan=\"2\">Nº CUENTA : ".$this->datos['cuenta']."</td>\n";
		$Salida .= "		</tr>\n";
		
          $Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\" width=\"15%\">DEPARTAMENTO</td>\n";
		$Salida .= "			<td class=\"normal_10\" width=\"25%\">".$this->datos['bodega']['DPTO']."</td>\n";
		$Salida .= "			<td class=\"normal_10N\" colspan=\"2\">NIVEL : ".$this->datos['nivel']."</td>\n";
		$Salida .= "		</tr>\n";
          $Salida .= "	</table><br>\n";
          
          $Salida .= "	<table width=\"95%\" align=\"center\" border=\"1\">\n";		
		$Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\" align=\"center\" width=\"25%\" colspan=\"5\"><b>INSUMOS SOLICITADOS PARA EL PACIENTE</b></td>\n";		
		$Salida .= "		</tr>\n";		
		$Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\" width=\"10%\" align=\"center\"><b>COD. PRODUCTO</b></td>\n";
		$Salida .= "			<td class=\"normal_10N\" width=\"30%\" align=\"center\"><b>DESCRIPCION</b></td>\n";
		$Salida .= "			<td class=\"normal_10N\" width=\"15%\" align=\"center\"><b>BODEGA</b></td>\n";
		$Salida .= "			<td class=\"normal_10N\" width=\"10%\" align=\"center\"><b>PRECIO</b></td>\n";
		$Salida .= "			<td class=\"normal_10N\" width=\"10%\" align=\"center\"><b>CANTIDAD</b></td>\n";          		
		$Salida .= "		</tr>\n";
		
          for($i=0; $i<sizeof($this->insumostmp); $i++)
          {
               $NombreProducto = "";
               $NombreProducto = $this->DescripcionMedicamentos($this->insumostmp[$i]['codigo_producto']);
               $Salida .= "		<tr>\n";
               $Salida .= "			<td class=\"normal_10\" align=\"center\">".$this->insumostmp[$i]['codigo_producto']."</td>\n";
               $Salida .= "			<td class=\"normal_10\">".$NombreProducto."</td>\n";
               $Salida .= "			<td class=\"normal_10\">".$this->insumostmp[$i]['desbodega']."</td>\n";
               $Salida .= "			<td class=\"normal_10\" align=\"center\">".$this->insumostmp[$i]['precio']."</td>\n";
               $Salida .= "			<td class=\"normal_10\" align=\"center\">".$this->insumostmp[$i]['cantidad']."</td>\n";          		
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
     
     
     /**
     * Funcion que consulta los datos de los medicamentos o insumos que hacen parte del detalle del documento de la bodega
     * @return boolean
     */
     function DescripcionMedicamentos($Codigo)
     {
	     list($dbconn) = GetDBconn();
          $query="SELECT descripcion
                  FROM   inventarios_productos
     		   WHERE codigo_producto = '$Codigo';";
          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Guardar en la Base de Datos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          return $result->fields[0];
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
