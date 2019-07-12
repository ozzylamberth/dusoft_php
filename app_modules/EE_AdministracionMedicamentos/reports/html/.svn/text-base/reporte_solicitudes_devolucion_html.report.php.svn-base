<?php

/**
 * $Id: reporte_solicitudes_devolucion_html.report.php,v 1.2 2011/04/26 15:14:17 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

//Reporte de Formulacion de medicamentos formato HTML
//este reporte es usado desde la central de impresion de hospitalizacion
//segun la orden se puede generar cuatro tipos distintos de
//formulas (pos, no pos justificados , no pos a peticion del paciente y de uso controlado)

class reporte_solicitudes_devolucion_html_report
{
    //VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
    var $datos;
    var $title       = '';
    var $author      = '';
    var $sizepage    = 'leter';
    var $Orientation = '';
    var $grayScale   = false;
    var $headers     = array();
    var $footers     = array();

    //CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
    function reporte_solicitudes_devolucion_html_report($datos=array())
    {
          $this->datos=$datos;
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


     //Funciones de Vistas.
     function CrearReporte()
     {
		if($this->datos[letra] == "M")
          {
          	$tipo = "MEDICAMENTOS";
          }else
          {
          	$tipo = "INSUMOS";          
          }
     
          $Salida.= "<TABLE ALIGN=\"CENTER\" WIDTH=\"90%\" BORDER=\"0\" class=\"modulo_table\">";
          $Salida.= "<TR>";
          $Salida.= "<TD ALIGN=\"CENTER\" class=\"Normal_10N\" WIDTH=\"30%\">SOLICITUDES PENDIENTES DE DEVOLUCIONES DE $tipo</TD>";
          $Salida.= "</TR>";
          $Salida.= "</TABLE><BR><BR><BR>\n";
          
          $Salida.="<table width=\"90%\" align=\"center\" border=\"0\" class=\"modulo_table\">";
          $Salida.="<tr>\n";
          $Salida.="<td class=\"Normal_10N\" ALIGN=\"JUSTIFY\" WIDTH=\"20%\">IDENTIFICACION:&nbsp;".$this->datos[datosPaciente][tipo_id_paciente]." ".$this->datos[datosPaciente][paciente_id]."</FONT></td>\n";
          $Salida.="<td class=\"Normal_10N\" ALIGN=\"JUSTIFY\" WIDTH=\"30%\">NOMBRE:&nbsp;".strtoupper($this->datos[datosPaciente][nombre_completo])."</FONT></td>\n";
          $Salida.="<td class=\"Normal_10N\" ALIGN=\"JUSTIFY\" WIDTH=\"20%\">CAMA:&nbsp;".$this->datos[datosPaciente][cama];
          $Salida.="</td>\n";
          $Salida.="</table>\n";
          
          $fechaIngreso = $this->FechaStamp($this->datos[datosPaciente][fecha_ingreso]);
          
          $Salida.= "<TABLE ALIGN=\"CENTER\" WIDTH=\"90%\" BORDER=\"0\" class=\"modulo_table\">";
          $Salida.= "<TR>";
          $Salida.= "<TD ALIGN=\"JUSTIFY\" class=\"Normal_10N\" WIDTH=\"30%\">FECHA DE INGRESO:&nbsp;&nbsp;".$fechaIngreso."</TD>";
          $Salida.= "<TD ALIGN=\"JUSTIFY\" class=\"Normal_10N\" WIDTH=\"40%\">No. INGRESO:&nbsp;&nbsp;".$this->datos[datosPaciente][ingreso]."</TD>";
          $Salida.= "<TD ALIGN=\"JUSTIFY\" class=\"Normal_10N\" WIDTH=\"30%\">No.CUENTA:&nbsp;&nbsp;".$this->datos[datosPaciente][numerodecuenta]."</TD>";
          $Salida.= "</TR>";
          $Salida.= "</TABLE>\n";
          
          $Salida.= "<TABLE ALIGN=\"CENTER\" WIDTH=\"90%\" BORDER=\"0\" class=\"modulo_table\">";
          $Salida.= "<TR>";
          $Salida.= "<TD ALIGN=\"JUSTIFY\" class=\"Normal_10N\" WIDTH=\"50%\">PLAN:&nbsp;&nbsp;".$this->datos[datosPaciente][plan_descripcion]."</TD>";
          $Salida.= "<TD ALIGN=\"JUSTIFY\" class=\"Normal_10N\" WIDTH=\"50%\">CLIENTE:&nbsp;&nbsp;".$this->datos[datosPaciente][nombre_tercero]."</TD>";
          $Salida.= "</TR>";
          $Salida.= "</TABLE><BR><BR><BR>\n";
          
          $bodega = $this->datos[bodega];
          for($s=0;$s<sizeof($bodega); $s++)
          {
               if($bodega[$s][bodega] != $bodega[$s-1][bodega])
               {
                    $devo_impresiones = $this->BusquedaDevoluciones_Pendientes($this->datos[datos_estacion],$bodega[$s][bodega],$this->datos[datosPaciente],$this->datos[letra]);
                    if(is_array($devo_impresiones))
                    {
                         $vector_devo = array();
                         array_push($vector_devo, $devo_impresiones);
                    }
               }
          }

          $Salida .= "<table border=\"1\" width=\"80%\" align=\"center\">\n";
          $Salida .= "<tr class=\"normal_10N\">\n";
          $Salida .= "<td align=\"center\" width=\"20%\">BODEGA</td>\n";
          $Salida .= "<td align=\"center\" width=\"10%\">CODIGO</td>\n";
          $Salida .= "<td align=\"center\" width=\"40%\">PRODUCTO</td>\n";
          $Salida .= "<td align=\"center\" align=\"center\" width=\"3%\">CANTIDAD</td>\n";
          $Salida .= "</tr>";
          
          foreach($vector_devo as $k => $vector_devo)
          {
               for($i=0;$i<sizeof($vector_devo);$i++)
               {
                    $nom_bodega=$this->TraerNombreBodega($this->datos[datos_estacion],$vector_devo[$i][bodega]);
                    
                    $Salida .= "<tr class=\"normal_10\">\n";
                    $Salida .= "<td width=\"20%\">$nom_bodega</td>\n";
                    $Salida .= "<td width=\"10%\">".$vector_devo[$i][codigo_producto]."</td>\n";
                    $Salida .= "<td width=\"40%\">".$vector_devo[$i][descripcion]."</td>\n";
                    $Salida .= "<td align=\"center\" width=\"3%\">".$vector_devo[$i][cantidad]."</td>\n";
                    $Salida .=" </tr>";
               }
          }
          $Salida .= "</table>\n";          
          
          
          $this->GetDatosProfesional();
          $Salida.="<BR><BR><TABLE ALIGN=\"center\" WIDTH=\"90%\">";
          $Salida.="<TR>";
          $Salida.="<TD ALIGN=\"left\" CLASS='normal_10N'>Nombres y Apellidos del Médico:&nbsp;&nbsp;".$this->datosProfesional['nombre']."</td>";
          $Salida.="</TR>";
          $Salida.="<TR>";
          $Salida.="<TD ALIGN=\"left\" CLASS='normal_10N'>Registro Médico No.:&nbsp;&nbsp;".$this->datosProfesional['tarjeta_profesional']."</td>";
          $Salida.="</TR>";
          $Salida.="</TABLE>\n";

          return $Salida;
     }   

     
     /*
     * Funcion que busca las solicitudes de devoluciones
     * Pendientes de cada respectivo paciente.
     */     
     function BusquedaDevoluciones_Pendientes($datos_estacion,$bodega,$datosPaciente,$letra)
     {
	     GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          
          if($letra == 'M')
          {
               $query = "SELECT a.bodega, b.codigo_producto, d.descripcion, b.cantidad 
                         FROM inv_solicitudes_devolucion a, inv_solicitudes_devolucion_d b
                         LEFT JOIN bodega_paciente x ON (b.codigo_producto = x.codigo_producto
                                                         AND x.sw_tipo_producto = '$letra'
                                                         AND x.ingreso = '".$datosPaciente['ingreso']."'),
                         inventarios_productos d,
                         medicamentos j
                         
                         WHERE a.empresa_id = '".$datos_estacion['empresa_id']."' 
                         AND a.centro_utilidad = '".$datos_estacion['centro_utilidad']."'
                         AND a.bodega = '".$bodega."'
                         AND a.ingreso = '".$datosPaciente['ingreso']."'
                         AND a.estacion_id = '".$datos_estacion['estacion_id']."'
                         AND (a.estado='0' OR a.estado='1') 
                         AND a.documento=b.documento
                         AND d.codigo_producto = b.codigo_producto
                         AND d.codigo_producto = j.codigo_medicamento
                         ORDER BY d.descripcion;";
                         
          }
          else
          {
               $query = "SELECT a.bodega, 
                                d.codigo_producto, d.descripcion, 
                                b.cantidad 
                    
                    FROM inv_solicitudes_devolucion a, 
                         inv_solicitudes_devolucion_d b, 
                         inventarios_productos d, 
                         bodega_paciente x 
                    
                    WHERE a.empresa_id = '".$datos_estacion['empresa_id']."'
                    AND a.centro_utilidad = '".$datos_estacion['centro_utilidad']."'
                    AND a.bodega = '".$bodega."'
                    AND a.ingreso = '".$datosPaciente['ingreso']."'
                    AND a.estacion_id = '".$datos_estacion['estacion_id']."'
                    AND (a.estado='0' OR a.estado='1')
                    AND a.documento=b.documento
                    AND b.codigo_producto = d.codigo_producto 
                    AND x.sw_tipo_producto = '$letra'
                    AND x.codigo_producto = d.codigo_producto 
                    AND a.ingreso = x.ingreso
                    ORDER BY d.descripcion;";
          }

          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error en la consulta";
               $this->mensajeDeError = "Ocurrió un error al intentar obtener los resultados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          while($data = $resultado->FetchRow())
          {
			$devoluciones[] = $data;
          }
          return $devoluciones;
     }
     
     /**
     *       GetEstacionBodega
     *
     *       obtiene el nombre de la bodega.
     *
     *       @Author Jairo Duvan Diaz M.
     *       @access Public
     *       @return array, false ó string
     *       @param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
     */
     function TraerNombreBodega($estacion,$bodega)
     {
          list($dbconn) = GetDBconn();
          $query="SELECT descripcion FROM bodegas
                    WHERE empresa_id='".$estacion['empresa_id']."'
                    AND centro_utilidad='".$estacion['centro_utilidad']."'
                    AND bodega='$bodega'";
               $resulta=$dbconn->execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               return $resulta->fields[0];
     }

     
     function GetDatosProfesional()
	{
          list($dbconn) = GetDBconn();
          $sql="SELECT A.tipo_id_tercero, A.tercero_id, A.nombre,
               	   A.tarjeta_profesional, B.especialidad, C.descripcion
                FROM profesionales AS A,
               	 profesionales_usuarios AS E
                LEFT JOIN profesionales_especialidades AS B
                ON(E.tipo_tercero_id=B.tipo_id_tercero AND E.tercero_id=B.tercero_id)
                LEFT JOIN especialidades AS C ON(C.especialidad = B.especialidad)
                WHERE A.usuario_id =".UserGetUID()."
                AND A.usuario_id = E.usuario_id
                AND E.tercero_id = A.tercero_id
                AND E.tipo_tercero_id = A.tipo_id_tercero;";
		$result = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
          {
               return false;
          }
		while(!$result->EOF)
		{
			$this->datosProfesional = $result->GetRowAssoc(false);
			$result->MoveNext();
		}
		return $this->datosProfesional;
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

     function FechaStampT($fecha)
     {
          if($fecha){
               $fech = strtok ($fecha,"-");
               for($l=0;$l<3;$l++)
               {
                    $date[$l]=$fech;
                    $fech = strtok ("-");
               }
               return  ceil($date[0])."/".ceil($date[1])."/".ceil($date[2]);
          }
     }

}

?>