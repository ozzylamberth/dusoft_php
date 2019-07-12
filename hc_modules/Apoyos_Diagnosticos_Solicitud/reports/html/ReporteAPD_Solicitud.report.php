<?php
/**
 * $Id: ReporteAPD_Solicitud.report.php,v 1.3 2007/12/07 18:25:13 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 */
class ReporteAPD_Solicitud_report
{
	var $datos;
	
	function ReporteAPD_Solicitud_report($datos=array())
	{
		$this->datos=$datos;
		return true;
	}

	//PARAMETROS PARA LA CONFIGURACION DEL REPORTE
	var $title       = '';
	var $author      = '';
	var $sizepage    = 'leter';
	var $Orientation = '';
	var $grayScale   = false;
	var $headers     = array();
	var $footers     = array();

	
	function GetMembrete()
	{
		$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'',
													 'subtitulo'=>'',
													 'logo'=>'logocliente.png',
													 'align'=>'left'));
		return $Membrete;
	}
	 
	function CrearReporte()
	{
    $solicitudes = $this->Consulta_Solicitud_Apoyod();
    $paciente = $this->Consulta_Datos_Paciente();
    $HTML_WEB_PAGE.= $this->frmPaciente($paciente);          
    $HTML_WEB_PAGE.= $this->frmHistoria($solicitudes);
    
    $this->GetDatosProfesional();
    $HTML_WEB_PAGE.="<BR><BR><TABLE ALIGN=\"center\" WIDTH=\"90%\">";
    $HTML_WEB_PAGE.="<TR>";
    $HTML_WEB_PAGE.="<TD ALIGN=\"left\" CLASS='normal_10N'>Nombres y Apellidos del Médico:&nbsp;&nbsp;".$this->datosProfesional['nombre']."</td>";
    $HTML_WEB_PAGE.="</TR>";
    $HTML_WEB_PAGE.="<TR>";
    $HTML_WEB_PAGE.="<TD ALIGN=\"left\" CLASS='normal_10N'>Registro Médico No.:&nbsp;&nbsp;".$this->datosProfesional['tarjeta_profesional']."</td>";
    $HTML_WEB_PAGE.="</TR>";
    $HTML_WEB_PAGE.="</TABLE>";

		return $HTML_WEB_PAGE;
	}
	
  function frmPaciente($vector2)
  {
		$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
    $edad = $this->CalcularEdad($vector2[0]['fecha_nacimiento'],'');
    $cama = $this->BuscarCama($vector2[0]['orden_servicio_id'],$vector2[0]['ingreso']);
    
    $salida2.="<table align=\"center\" border=\"1\"  cellpading= \"0\" cellspacing=\"0\" width=\"100%\" $estilo>";
    $salida2.=" <tr class=\"label\">";
    $salida2.="   <td align=\"center\" colspan=\"4\">DATOS DEL PACIENTE</td>";
    $salida2.=" </tr>";
    $salida2.=" <tr align=\"left\">";
    $salida2.="   <td class=\"normal_10N\">PACIENTE:</td>";
    $salida2.="   <td align=\"justify\" colspan=\"2\">".$vector2[0]['cedula']." ".$vector2[0]['nombre']."</td>";
    $salida2.="   <td ><b>EDAD: </b>".$edad['anos']." años</td>";
    $salida2.=" </tr>";
    $salida2.=" <tr align=\"left\">";
    $salida2.="   <td class=\"normal_10N\" >FECHA NACIMIENTO:</td>";
    $salida2.="   <td align=\"justify\">".$vector2[0]['fecha_nacimiento']."</td>";
    $salida2.="   <td ><b>SEXO: </b>".$vector2[0]['sexo_id']."</td>";
    $salida2.="   <td ><b>CAMA: </b>".$cama['cama']."</td>";
    $salida2.=" </tr>";
    $salida2.=" <tr align=\"left\">";
    $salida2.="   <td class=\"normal_10N\">DIRECCION RESIDENCIA:</td>";
    $salida2.="   <td align=\"justify\">".$vector2[0]['residencia_direccion']."</td>";
    $salida2.="   <td class=\"normal_10N\">TELEFONO RESIDENCIA</td>";
    $salida2.="   <td align=\"justify\">".$vector2[0]['residencia_telefono']."</td>";
    $salida2.=" </tr>";
    $salida2.=" <tr align=\"left\">";
    $salida2.="   <td colspan=\"2\"><b>CLIENTE:</b> ".$vector2[0]['nombre_tercero']."</td>";
    $salida2.="   <td colspan=\"2\"><b>PLAN:</b> ".$vector2[0]['plan_descripcion']."</td>";
    $salida2.=" </tr>";

    $salida2.="</table><BR><BR>";
    return $salida2;
	}	
	
	function frmHistoria($vector1)
	{
		if($vector1)
		{
   		$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";

			$salida .= "<table  align=\"center\" border=\"1\" width=\"100%\" cellpading= \"0\" cellspacing=\"0\" $estilo>";
			$salida .= "<tr class=\"normal_10N\">";
			$salida .= "<td align=\"center\" colspan=\"4\">APOYOS DIAGNOSTICOS SOLICITADOS</td>";
			$salida .= "</tr>";
      
      $tipo = "";
			for($i=0;$i<sizeof($vector1);$i++)
			{
				$hc_os_solicitud_id =$vector1[$i][hc_os_solicitud_id];
				$a = $this->FechaStamp($vector1[$i][fecha]);
				$b = $this->HoraStamp($vector1[$i][fecha]);
                    
        $usuario_solicitud = $vector1[$i][usuario_solicitud];

				$fecha = $a.' - '.$b;
				if( $i % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
                    
        //Responsable Tizziano Perea
        if(!empty($this->datos[PARAMETRO]) AND !empty($vector1[$i][usuario_solicitud]))
        {  
          $row=4;
          $profesionales = $this->ReconocerProfesional($usuario_solicitud);
          $nombre = $profesionales[0][nombre];
          $usuario_solicitud = $profesionales[0][usuario_id];
        }
        else
        {
          $row = '3';
        }
        
        if($tipo != $vector1[$i][tipo])
        {
          $salida .= "  <tr class=\"label\">\n";
          $salida .= "    <td colspan =\"3\" align=\"center\" > TIPO: ".$vector1[$i][tipo]."</td>\n";
          $salida .= "  </tr>\n";
          $salida .= "  <tr class=\"normal_10N\" align=\"center\">";
    			$salida .= "    <td width=\"20%\">CARGO</td>";
    			$salida .= "    <td width=\"60%\">DESCRIPCION</td>";
    			$salida .= "    <td width=\"20%\">FECHA/HORA EVOLUCION</td>";
    			$salida .= "  </tr>";
        }
        $tipo = $vector1[$i][tipo];
        
				$salida.="  <tr>\n";
				$salida.="    <td align=\"center\" >".$vector1[$i][cargo]."</td>";
				$salida.="    <td align=\"left\"   >".$vector1[$i][descripcion]."</td>";
				$salida.="    <td align=\"left\"   >".$fecha."</td>";
				$salida.="  </tr>\n";
        if(trim($vector1[$i][observacion]) != "")
        {
          $salida.="<tr >";
          $salida.="  <td colspan = '1' class=\"normal_10N\" align=\"center\" >Observacion</td>";
          $salida.="  <td colspan = '2' align=\"left\" width=\"84%\">".$vector1[$i][observacion]."</td>";
          $salida.="</tr>";
        }          
        //Responsable Tizziano Perea
        if(!empty($this->datos[PARAMETRO]) AND !empty($vector1[$i][usuario_solicitud]))                    
        {
          $salida.="<tr >";
          $salida.="  <td colspan = 1 class=\"normal_10N\" align=\"center\">Orden Profesional</td>";
          $salida.="  <td colspan = 2 align=\"left\" width=\"84%\">".$nombre."</td>";
          $salida.="</tr>";
        }

				$diag =$this->Diagnosticos_Solicitados($vector1[$i][hc_os_solicitud_id]);
				if(!empty($diag))
				{				
          $salida.="  <tr class=\"normal_10N\">";
          $salida.="    <td align=\"center\" >Diagnosticos Presuntivos</td>";
          $salida.="    <td colspan = 2 align=\"left\" width=\"84%\">";
          $salida.="        <table width=\"100%\" $estilo border=\"1\" cellpading= \"0\" cellspacing=\"0\">";
          $salida.="          <tr class=\"normal_10N\" align=\"center\">";
          $salida.="            <td width=\"10%\">PRIMARIO</td>";
          $salida.="            <td width=\"10%\">TIPO DX</td>";
          $salida.="            <td width=\"10%\">CODIGO</td>";
          $salida.="            <td width=\"70%\">DIAGNOSTICO</td>";
          $salida.="          </tr>";
				
          for($j=0;$j<sizeof($diag);$j++)
          {
            $salida.="          <tr>";
                       
            if($diag[$j]['sw_principal']==1)
            {
							$salida.="            <td align=\"center\" width=\"10%\">DX 1</td>";
            }
            else
            {
							$salida.="            <td align=\"center\" width=\"10%\">&nbsp;</td>";
            }
					
            if($diag[$j][tipo_diagnostico] == '1')
            {
							$salida.="            <td align=\"center\" width=\"10%\"> ID </td>";
            }
             elseif($diag[$j][tipo_diagnostico] == '2')
            {
							$salida.="            <td align=\"center\" width=\"10%\"> CN </td>";
            }
            else
            {
							$salida.="            <td align=\"center\" width=\"10%\"> CR </td>";
            }

            $salida.="            <td width=\"10%\" align=\"center\">".$diag[$j][diagnostico_id]."</td>";
            $salida.="            <td width=\"70%\" align=\"justify\">".$diag[$j][diagnostico_nombre]."</td>";
            $salida.="          </tr>";
          }

          $salida.="        <tr>";
          $salida.="          <td align=\"center\" colspan=\"4\" valign=\"top\" >&nbsp;( ID )&nbsp;-&nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( CN )&nbsp;-&nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( CR )&nbsp;-&nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;&nbsp;</td>";
          $salida.="        </tr>";
  				$salida.="      </table>";
  				$salida.="    </td>";
  				$salida.="  </tr>";
				}
				//cambio dar										
				if($vector1[$i][sw_ambulatorio]==1)
				{
          $salida.="<tr class=\"normal_10N\">";
          $salida.="  <td colspan =5 align=\"CENTER\"  class=\"label\">SOLICITUD AMBULATORIA</td>";
          $salida.="</tr>";										
				}
				//fin cambio dar
			}
			$salida.="</table><br>";
		}
		return $salida;
	}
	
	
	function Consulta_Datos_Paciente()
  {
    list($dbconnect) = GetDBconn();
    
    $query = "SELECT DISTINCT w.nombre_tercero, 
                      p.plan_descripcion,
                      a.tipo_afiliado_id, 
                      a.plan_id,        
                      d.primer_nombre||' '||d.segundo_nombre||' '||
                      d.primer_apellido||' '||d.segundo_apellido as nombre,
                      d.tipo_id_paciente||' '||d.paciente_id as cedula,
                      d.fecha_nacimiento,
                      d.residencia_direccion,
                      d.residencia_telefono,
                      d.fecha_registro,
                      d.sexo_id,
                      d.ocupacion_id,
                      d.nombre_madre,
                      a.orden_servicio_id,
                      r.ingreso
            FROM      os_ordenes_servicios AS a
                      LEFT JOIN os_maestro AS e 
                      ON (a.orden_servicio_id=e.orden_servicio_id)
                      LEFT JOIN hc_os_solicitudes AS q 
                      ON (e.hc_os_solicitud_id=q.hc_os_solicitud_id)
                      LEFT JOIN hc_evoluciones AS r 
                      ON (q.evolucion_id = r.evolucion_id),
                      planes AS p,
                      terceros AS w,
                      pacientes AS d
            WHERE     a.tipo_id_paciente = '".$this->datos['TIPOPACIENTE_ID']."'
            AND       a.paciente_id = '".$this->datos['PACIENTE_ID']."'
            AND       a.plan_id = p.plan_id
            AND       w.tipo_id_tercero = p.tipo_tercero_id
            AND       w.tercero_id = p.tercero_id
            AND       a.tipo_id_paciente = d.tipo_id_paciente
            AND       a.paciente_id=d.paciente_id ";
    
    $result = $dbconnect->Execute($query);
    if ($dbconnect->ErrorNo() != 0)
    {
      $this->error = "Error al buscar en la consulta de solictud de apoyos";
      $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
      return false;
    }
    else
    { 
      $i=0;
      while (!$result->EOF)
      {
        $vector[$i]=$result->GetRowAssoc($ToUpper = false);
        $result->MoveNext();
        $i++;
      }
    }
    $result->Close();
    return $vector;
	}
	
     //cor - clzc -ads
     function Consulta_Solicitud_Apoyod()
     {
          $pfj=$this->frmPrefijo;
          list($dbconnect) = GetDBconn();
     
          $criterio='';
          if(!empty($this->plan_id)){
               $criterio=",informacion_cargo('".$this->plan_id."',a.cargo,'".$this->departamento."')";
          }
          
          $query = "SELECT 
                         a.cargo, a.hc_os_solicitud_id, a.sw_ambulatorio,
                         b.descripcion, 
                         d.evolucion_id, d.ingreso,
                         c.descripcion as tipo, 
                         d.fecha, 
                         e.observacion, e.usuario_solicitud 
                         $criterio
                         
                         FROM
                         hc_os_solicitudes a 
                         left join hc_os_solicitudes_apoyod e on (a.hc_os_solicitud_id = e.hc_os_solicitud_id), 
                         cups b, 
                         apoyod_tipos c, 
                         hc_evoluciones d 
                         
                         WHERE 
                         a.paciente_id = '".$this->datos['PACIENTE_ID']."'
                         AND a.tipo_id_paciente = '".$this->datos['TIPOPACIENTE_ID']."'
                         AND a.evolucion_id = d.evolucion_id 
                         AND d.evolucion_id = ".$this->datos['EVOLUCION']."
                         AND a.cargo = b.cargo 
                         AND e.apoyod_tipo_id = c.apoyod_tipo_id 
                         ORDER BY d.evolucion_id DESC, a.hc_os_solicitud_id ASC;";
                         
          $result = $dbconnect->Execute($query);
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de solictud de apoyos";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          { 
               $i=0;
               while (!$result->EOF)
               {
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
               }
          }
          $result->Close();
          return $vector;
     } 
     
     //cor - clzc- ads
     function Diagnosticos_Solicitados($hc_os_solicitud_id)
     {
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= "SELECT a.diagnostico_id, a.diagnostico_nombre, b.tipo_diagnostico,
          			 b.sw_principal
                   FROM diagnosticos a, hc_os_solicitudes_diagnosticos b
                   WHERE b.hc_os_solicitud_id = $hc_os_solicitud_id AND a.diagnostico_id = b.diagnostico_id";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la tabla apoyod_tipos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ 
          	$i=0;
			while (!$result->EOF)
			{
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
			}
		}
          $result->Close();
          return $vector;
	}

     //Responsable Tizziano Perea
     function ReconocerProfesional($usuario_solicitud)
	{
		list($dbconn) = GetDBconn();
		GLOBAL $ADODB_FETCH_MODE;
          
          if($usuario_solicitud)
          {
          	$criterio = "WHERE usuario_id = ".$usuario_solicitud."";
          }     
          
     	$sql="SELECT usuario_id, nombre
                FROM profesionales
                $criterio
                ORDER BY nombre ASC;";
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;		
          if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al traer profesional";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while($data = $result->FetchRow())
          {
          	$profesional[] = $data;
          }
          return $profesional;
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
     
  	function PartirFecha($fecha)
  	{
  		$a=explode('-',$fecha);
  		$b=explode(' ',$a[2]);
  		$c=explode(':',$b[1]);
  		$d=explode('.',$c[2]);
  		return $a[0].'-'.$a[1].'-'.$b[0].' '.$c[0].':'.$c[1].':'.$d[0];
  	}
    /**
    *
    */
    function BuscarCama($orden,$ingreso)
    {
      $cama = array();
      if($ingreso)
      {
        $sql = "SELECT  cama 
                FROM    movimientos_habitacion
                WHERE   ingreso = $ingreso 
                AND     fecha_egreso IS NULL";
      }
      else
      {
        $sql = "SELECT  cama
                FROM    hc_os_solicitudes_manuales_datos_adicionales 
                WHERE   orden_servicio_id = $orden ";
      }

      list($dbconn) = GetDBconn();
      $rst = $dbconn->Execute($sql);
      if ($dbconn->ErrorNo() != 0) 
      {
        echo "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      if(!$rst->EOF)
        $cama = $rst->GetRowAssoc($ToUpper = false);  
      
      $rst->Close();
      return $cama;
    }
  
    function CalcularEdad($FechaInicio,$FechaFin)
    {
    	if (empty($FechaFin))
    	{
    	  $FechaFin=date("Y-m-d");
    	}
  		$FechaInicio=str_replace("/","-",$FechaInicio);
  		$fech = strtok ($FechaInicio,"-");
  		for($l=0;$l<3;$l++)
  		{
        $date[$l]=$fech;
  			$fech = strtok ("-");
  		}
  		$a=explode(" ",$date[2]);
  		$date[2]=$a[0];
  		$dia=$date[2];
  		$mes=$date[1];
  		$ano=$date[0];
  		$a=explode(':',$a[1]);
  		$hora=$a[0];
  		$minutos=$a[1];
  		$segundos=$a[2];
  		if(!checkdate($mes,$dia,$ano))
  		{
  		  return false;
  		}
  		$FechaFin=str_replace("/","-",$FechaFin);
  		$fech = strtok ($FechaFin,"-");
  		for($l=0;$l<3;$l++)
  		{$date[$l]=$fech;
  			$fech = strtok ("-");
  		}
  		$a=explode(" ",$date[2]);
  		$date[2]=$a[0];
  		$a=explode(':',$a[1]);
  		$hora1=$a[0];
  		$minutos1=$a[1];
  		$segundos1=$a[2];
      if(!checkdate($date[1],$date[2],$date[0]))
  		{
  		  return false;
  		}
      $edad=(ceil($date[0])-$ano);
      $meses=$date[1]-$mes;
      $dias=$date[2]-$dia;
			$hora=$hora1-$hora;
			$minutos=$minutos1-$minutos;
			$segundos=$segundos1-$segundos;
      $total=($edad*365)+($meses*30)+$dias;
      $edad=floor($total/365);
      $meses=floor(($total%365)/30);
      $dias=floor(($total%365)%30);
      $edad_aprox=floor($total/365);
			if($edad_aprox>0)
			{
				$edad_aprox.=' Años';
				$edad_rips=$edad;
				$unidad_rips=1;
			}
			else
			{
				if($meses>0)
				{
					$edad_aprox=$meses.' Meses';
					$edad_rips=$meses;
					$unidad_rips=2;
				}
				else
				{
					$edad_aprox=$dias.' Dias';
					$edad_rips=$dias;
					$unidad_rips=3;
				}
			}
      $edad_en_dias=$dias + ($meses * 30) + ($edad * 365);
     	return array('anos'=>$edad,'meses'=>$meses,'dias'=>$dias,'edad_aprox'=>$edad_aprox,'edad_rips'=>$edad_rips,'unidad_rips'=>$unidad_rips, 'edad_en_dias'=>$edad_en_dias);
    }
}
?>