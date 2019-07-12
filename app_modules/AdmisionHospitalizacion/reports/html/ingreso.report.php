<?php

	/**************************************************************************************
	 * $Id: ingreso.report.php,v 1.6 2010/07/23 12:53:35 sandra Exp $ 
	 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	 * @package IPSOFT-SIIS
	 * 
	 **************************************************************************************/

	class ingreso_report 
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
		function ingreso_report($datos=array())
		{
			$this->datos=$datos;
			return true;
		}
		
		function GetMembrete()
		{
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			$titulo .= " <b $estilo>INFORME DEL INGRESO DEL PACIENTE</b>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'subtitulo'=>' ',
							  'logo'=>'logocliente.png',
							  'align'=>'left'));
			return $Membrete;
		}

		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
		{
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
			$estilo2 = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px; text-indent:6pt\""; 
			$estilo3 = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\""; 
			$cantidad = $this->ValidacionIngreso($this->datos);
			$Datos = $this->ObtenerIngresoPaciente();
			$x = sizeof($Datos)-1;
			
			$EdadArr=CalcularEdad($Datos[0]['fecha_nacimiento'],$FechaFin);
      
 			$Salida .= "<table width=\"95%\" align=\"center\" cellpading=\"0\" cellspacing=\"0\">\n";
      $Salida .= "  <tr $estilo height=\"21\">\n";
      $Salida .= "    <td><b>OBSERVACIÓN: PACIENTE ";
      if($cantidad == 0)
        $Salida .= "NUEVO";
      else
        $Salida .= "CON HISTORIA CLINICA</b>";
      $Salida .= "    </td>\n"; 
      $Salida .= "  </tr>\n"; 
      $Salida .= "</table>\n"; 
			$Salida .= "	<table border=\"1\" align =\"center\" cellpading=\"0\" cellspacing=\"0\" bordercolor=\"#000000\" width=\"95%\">\n";
			$Salida .= "		<tr $estilo2 height=\"21\">\n";
			$Salida .= "			<td width=\"25%\" ><b>MEDICO</b></td>\n";
			$Salida .= "			<td width=\"75%\"><b>".$Datos[0]['nombre']."&nbsp;</b></td>\n";
			$Salida .= "		</tr>\n";
			$Salida .= "	</table><br>\n";
			
			$Salida .= "	<table width=\"95%\" align=\"center\" cellpading=\"0\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">\n";
			$Salida .= "		<tr $estilo2 height=\"21\">\n";
			$Salida .= "			<td width=\"25%\" ><b>Nº INGRESO</b></td>\n";
			$Salida .= "			<td width=\"25%\" ><b>".$Datos[0]['ingreso']."</b></td>\n";
			$Salida .= "			<td width=\"25%\" ><b>FECHA INGRESO</b></td>\n";
			$Salida .= "			<td width=\"25%\" ><b>".$Datos[0]['fecha_ingreso']."</b></td>\n";
			$Salida .= "		</tr>\n";
		
			$Salida .= "		<tr $estilo2 height=\"21\">\n";
			$Salida .= "			<td ><b>Nº CUENTA</b></td>\n";
			$Salida .= "			<td ><b>".$Datos[0]['numerodecuenta']."</b></td>\n";
			$Salida .= "			<td colspan=\"2\">&nbsp;</td>\n";
			$Salida .= "		</tr>\n";

			$Salida .= "		<tr $estilo2 height=\"21\">\n";
			$Salida .= "			<td ><b>PACIENTE</b></td>\n";
			$Salida .= "			<td ><b>".$Datos[0]['tipo_id_paciente']." ".$Datos[0]['paciente_id']."</b></td>\n";
			$Salida .= "			<td colspan=\"2\"><b>".$Datos[0]['nombres']." ".$Datos[0]['apellidos']."</b></td>\n";
			$Salida .= "		</tr>\n";
			
			$Salida .= "		<tr $estilo2 height=\"21\">\n";
			$Salida .= "			<td ><b>EDAD</b></td>\n";
			$Salida .= "			<td ><b>".$EdadArr['edad_aprox']."</b></td>\n";
			$Salida .= "			<td colspan= \"2\">&nbsp;</td>\n";
			$Salida .= "		</tr>\n";

			$Salida .= "		<tr $estilo2 height=\"21\">\n";
			$Salida .= "			<td ><b>DIRECCION</b></td>\n";
			$Salida .= "			<td ><b>".$Datos[0]['residencia_direccion']."&nbsp;</b></td>\n";
			$Salida .= "			<td ><b>TELÉFONO</b></td>\n";
			$Salida .= "			<td ><b>".$Datos[0]['residencia_telefono']."&nbsp;</td>\n";
			$Salida .= "		</tr>\n";

      $Salida .= "    <tr $estilo2 height=\"21\">\n";
      $Salida .= "      <td ><b>TIPO AFILIADO</b></td>\n";
      $Salida .= "      <td ><b>".$Datos[0]['tipo_afiliado_nombre']."</b></td>\n";
      $Salida .= "      <td ><b>RANGO: ".$Datos[0]['rango']."</b></td>\n";
      $Salida .= "      <td ><b>SEMANAS COTIZADAS: ".$Datos[0]['semanas_cotizadas']."</b></td>\n";
      $Salida .= "    </tr>\n";
      
			$Salida .= "		<tr $estilo2 height=\"21\">\n";
			$Salida .= "			<td ><b>ENTIDAD</b></td>\n";
			$Salida .= "			<td ><b>".$Datos[0]['tipo_tercero_id']." ".$Datos[0]['tercero_id']."</b></td>\n";
			$Salida .= "			<td colspan=\"2\"><b>".$Datos[0]['nombre_tercero']."</b></td>\n";
			$Salida .= "		</tr>\n";
			
			$Salida .= "		<tr $estilo2 height=\"21\">\n";
			$Salida .= "			<td ><b>PLAN</b></td>\n";
			$Salida .= "			<td colspan=\"3\"><b>".$Datos[0]['plan_descripcion']."</b></td>\n";
			$Salida .= "		</tr>\n";

			$Salida .= "		<tr $estilo2 height=\"21\">\n";
			$Salida .= "			<td ><b>VIA DE INGRESO</b></td>\n";
			$Salida .= "			<td ><b>".$Datos[0]['via_ingreso_nombre']."</b></td>\n";
			$Salida .= "			<td colspan=\"2\"><b>RESPONSABLE: ".$Datos[0]['responsable']."</b></td>\n";
			$Salida .= "		</tr>\n";
      
			if($Datos[0]['comentario'])
			{
				$Salida .= "    <tr $estilo2 height=\"21\">\n";
				$Salida .= "      <td ><b>OBSERVACIONES</b></td>\n";
				$Salida .= "      <td colspan=\"3\"><b>".$Datos[0]['comentario']."</b></td>\n";
				$Salida .= "    </tr>\n";
			}

			$Salida .= "	</table><br>\n";
			
      //Datos de la programacion de cirugia en caso que sea una orden de ingreso a cirugia
      $DatosProgramacion = $this->ObtenerDatosProgramacionQX($Datos[0]['numerodecuenta']);
      if(is_array($DatosProgramacion)){
      $Salida .= "  <table width=\"95%\" align=\"center\" cellpading=\"0\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">\n";
      $Salida .= "    <tr $estilo2 height=\"21\">\n";
      $Salida .= "      <td width=\"25%\" ><b>Nº PROGRAMACION</b></td>\n";
      $Salida .= "      <td width=\"25%\" ><b>".$DatosProgramacion[0]['programacion_id']."</b></td>\n";
      $Salida .= "      <td width=\"25%\" ><b>CIRUJANO</b></td>\n";
      $Salida .= "      <td width=\"25%\" ><b>".$DatosProgramacion[0]['cirujano']."</b></td>\n";
      $Salida .= "    </tr>\n";
      
      $Salida .= "    <tr $estilo2 height=\"21\">\n";
      $Salida .= "      <td width=\"25%\" ><b>ANESTESIOLOGO</b></td>\n";
      $Salida .= "      <td width=\"25%\" ><b>".$DatosProgramacion[0]['anestesiologo']."</b></td>\n";
      $Salida .= "      <td width=\"25%\" ><b>AYUDANTE</b></td>\n";
      $Salida .= "      <td width=\"25%\" ><b>".$DatosProgramacion[0]['ayudante']."</b></td>\n";
      $Salida .= "    </tr>\n";
      
      $Salida .= "    <tr $estilo2 height=\"21\">\n";
      $Salida .= "      <td><b>DIAGNOSTICO</b></td>\n";
      $Salida .= "      <td colspan=\"3\"><b>".$DatosProgramacion[0]['diagnostico_nombre']."</b></td>\n";
      $Salida .= "    </tr>\n";
      
      for($i=0;$i<sizeof($DatosProgramacion);$i++){
        $Salida .= "    <tr $estilo2 height=\"21\">\n";
        $Salida .= "      <td><b>PROCEDIMIENTO</b></td>\n";
        $Salida .= "      <td colspan=\"3\"><b>".$DatosProgramacion[$i]['procedimiento_qx']." - ".$DatosProgramacion[$i]['descripcion']."</b></td>\n";
        $Salida .= "    </tr>\n";
      }  
      
      $Salida .= "  </table><br>\n";
      }
      //fin
      
			return $Salida;
		}
		/************************************************************************************ 
		* Funcion que permite traer la informacion de la glosa y el detalle del acta de 
		* conciliacion (si la hay) de las factura pertenecientes a un cliente
		* 
		* @return array datos de las facturas
		*************************************************************************************/
		function ObtenerIngresoPaciente()
		{
			
			$sql  = "SELECT 	PC.paciente_id,
												PC.tipo_id_paciente,
												PC.primer_apellido ||' '|| PC.segundo_apellido AS apellidos,
												PC.primer_nombre ||' '|| PC.segundo_nombre AS nombres,
												PC.fecha_nacimiento,
												PC.fecha_nacimiento_es_calculada,
												PC.residencia_direccion,
												PC.residencia_telefono,
												IG.ingreso,
												TO_CHAR(IG.fecha_ingreso,'DD/MM/YYYY HH12:MI am') AS fecha_ingreso,
												CU.numerodecuenta,
												VI.via_ingreso_nombre,
												TC.nombre_tercero,
												PL.plan_descripcion,
												PL.tercero_id,
												PL.tipo_tercero_id,
												PR.nombre,
												SU.nombre AS responsable,
                        TA.tipo_afiliado_nombre,
                        CU.rango,
                        CU.semanas_cotizadas,
                        IG.comentario
								FROM		pacientes PC,
												vias_ingreso VI,
												tipos_id_pacientes TI,
												system_usuarios SU,
												cuentas CU LEFT JOIN tipos_afiliado TA
                        ON(CU.tipo_afiliado_id = TA.tipo_afiliado_id),
												planes PL,
												terceros TC,
												ingresos IG LEFT JOIN pacientes_urgencias PU
												ON(	IG.ingreso = PU.ingreso) 
												LEFT JOIN	profesionales PR
												ON(	PR.tipo_id_tercero = PU.tipo_id_tercero AND
														PR.tercero_id = PU.tercero_id)
								WHERE		PC.tipo_id_paciente = TI.tipo_id_paciente
								AND			IG.paciente_id = PC.paciente_id
								AND			IG.tipo_id_paciente = PC.tipo_id_paciente
								AND			VI.via_ingreso_id = IG.via_ingreso_id
								AND			SU.usuario_id = IG.usuario_id
								AND			CU.ingreso = IG.ingreso
								AND			PL.plan_id = CU.plan_id
								AND			PC.tipo_id_paciente = '".$this->datos['tipo_id_paciente']."'
								AND			PC.paciente_id = '".$this->datos['paciente_id']."' 
								AND			PL.tipo_tercero_id = TC.tipo_id_tercero 
								AND			PL.tercero_id = TC.tercero_id ";
				$sql .= "AND		IG.ingreso = ".$this->datos['ingreso']." ";

			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;	
			 
			if(!$rst->EOF)
			{
				$ingreso[0] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $ingreso;
		}
    /**
    * Obtiene la cantidad de ingresos que tiene el paciente, que son diferentes,
    * al ingreso que se desea imprimir
    *
    * @param array $datos Arreglo con la informacion del ingreso
    *
    * @return mixed
    */
    function ValidacionIngreso($datos)
    {
      $sql  = "SELECT COUNT(*) AS cantidad ";
      $sql .= "FROM		ingresos ";
      $sql .= "WHERE	tipo_id_paciente = '".$datos['tipo_id_paciente']."' ";
			$sql .= "AND		paciente_id = '".$datos['paciente_id']."' ";
      $sql .= "AND		ingreso != ".$datos['ingreso']." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;	
			
      $retorno = array();
			if(!$rst->EOF)
			{
				$retorno = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $retorno['cantidad'];
    }
    /**
    * Funcion que permite realizar la conexion a la base de datos y ejecutar la
    * consulta sql
    *
    * @param string $sql sentencia sql a ejecutar
    *
    * @return object $rst
    */
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug=true;
			$rst = $dbconn->Execute($sql);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			return $rst;
		}
    /************************************************************************************ 
    * Funcion que permite traer la informacion de la programacion de cirugia del paciente    
    * 
    * @return array datos de la programacion
    *************************************************************************************/
    function ObtenerDatosProgramacionQX($cuenta){
    
        $sql  = "SELECT b.programacion_id,c.nombre_tercero as cirujano,
                        e.nombre_tercero as anestesiologo,
                        e.nombre_tercero as ayudante,g.diagnostico_nombre,
                        h.procedimiento_qx,i.descripcion 
                        
                  FROM  estacion_enfermeria_qx_pendientes_ingresar a,
                        qx_programaciones b                       
                        LEFT JOIN terceros c ON(b.tipo_id_cirujano=c.tipo_id_tercero AND b.cirujano_id=c.tercero_id)
                        LEFT JOIN qx_anestesiologo_programacion d ON(b.programacion_id=d.programacion_id)
                        LEFT JOIN terceros e ON(d.tipo_id_tercero=e.tipo_id_tercero AND d.tercero_id=e.tercero_id)
                        LEFT JOIN terceros f ON(d.tipo_id_ayudante=f.tipo_id_tercero AND d.ayudante_id=f.tercero_id)                        
                        LEFT JOIN diagnosticos g ON(b.diagnostico_id=g.diagnostico_id)                        
                        LEFT JOIN qx_procedimientos_programacion h ON(b.programacion_id=h.programacion_id)                        
                        LEFT JOIN cups i ON(h.procedimiento_qx=i.cargo)                        
                          
                  WHERE a.sw_estado='1' 
                        AND a.numerodecuenta=$cuenta
                        AND a.programacion_id=b.programacion_id
                        AND b.estado='1'  
                ";
        if(!$rst = $this->ConexionBaseDatos($sql))
          return false;        
        while(!$rst->EOF)
        {
          $datos[] = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }
	}
?>