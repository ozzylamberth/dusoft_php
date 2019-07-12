<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Recibo.report.php,v 1.8 2010/03/16 18:41:57 hugo Exp $ 
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  */ 
  /**
  * Clase Reporte: ReportePorUsuario_report 
  * reporte que contiene los datos del afiliado dependiendo de su estamento.
  * @package IPSOFT-SIIS
  * @version $Revision: 1.8 $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  */
  class Recibo_report
  {
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
  	function Recibo_report($datos=array())
  	{
      $this->datos=$datos;
  		return true;
  	}
    /**
    *
    */
  	function GetMembrete()
  	{
  		$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'<font size="4">'.GetVarConfigAplication('Cliente').'</font>',
  																'subtitulo'=>'<font size="4">ASIGNACION CITAS</font>',
  																'logo'=>'',
  																'align'=>'center'));
  		return $Membrete;
  	}
    /**
    *
    */
    function CrearReporte()
    {
      if(empty($this->datos['fechacita']))
        $this->datos['fechacita'] = SIIS_sfrtime($this->datos['fecha_cita'],"I");
      
      if(empty($this->datos['liqcita']['valor_total_paciente']))
      {
        IncludeLib("tarifario_cargos");
        IncludeLib('funciones_facturacion');
        $cargo_liq[]=array('tarifario_id'=>$this->datos['tarifario_id'],'cargo'=>$this->datos['cargo'],'cantidad'=>$this->datos['cantidad'],'autorizacion_int'=>$this->datos['autorizacion_ext'],'autorizacion_ext'=>$this->datos['autorizacion_int']);
        $emp = BuscarEmpleadorOrden($this->datos['numero_orden_id']);
        $cargo_fact = LiquidarCargosCuentaVirtual($cargo_liq, array(),array(),array(),$this->datos['plan_id'] ,$this->datos['tipo_afiliado_id'] ,$this->datos['rango'] ,$this->datos['semanas'],$this->datos['servicio'],$this->datos['tipo_id_paciente'],$this->datos['paciente_id'],$emp['tipo_id_empleador'],$emp['empleador_id']);
        $this->datos['liqcita'] = $cargo_fact;
      }
      
			$salida .= "<table width='100%' border=1 rules=\"all\">\n";
			$salida .= "  <TR>\n";
      $salida .= "    <TD>\n";
      $salida .= "      <table width='100%' border=0>\n";
      $salida .= "        <TR class=\"label\">\n";
      $salida .= "          <TD WIDTH='40%'>\n";
      $salida .= "            <font size='2'><b>CENTRO ATENCION</b></font>:&nbsp;<font size='3'>".$this->datos[departamento]."</font>\n";
      $salida .= "          </TD>\n";
      $salida .= "          <TD WIDTH='30%' >\n";
      $salida .= "            <font size='2'><b>DIRECCION</b></font>:&nbsp;<font size='3'>".$this->datos[departamentoUbicacion]."</font>\n";
      $salida .= "          </TD>\n";
      $salida .= "          <TD WIDTH='30%' >\n";
      $salida .= "            <font size='2'><b>TELEFONO</b></font>:&nbsp;<font size='3'>".$this->datos[TelefonoCancelacion]."</font>\n";
      $salida .= "          </TD>\n";
      $salida .= "        </TR>";
      $salida .= "        <TR class=\"normal_10\">";
      $salida .= "          <TD ><font size='2'><b>CITA NO.</b> </font>:&nbsp;<font size='3'>".$this->datos['idcita']."</font></TD>";
      $salida .= "          <TD colspan='2'><font size='2'><b>FECHA</b></font> :&nbsp;<font size='3'>".$this->datos['fechacita']."</font></TD>";
      $salida .= "        </TR>";
      $salida .= "        <TR class=\"normal_10\">";
      $salida .= "          <TD><font size='2'><b>NOMBRE</b>:</font>&nbsp;<font size='3'>".$this->datos[paciente]."</font></TD>";
      $salida .= "          <TD colspan='2'><font size='2'><b>IDENTIFICACION</b>:</font>&nbsp;<font size='3'>".$this->datos[identificacion]."</font></TD>";
      $salida .= "        </TR>";
      $salida .= "        <TR class=\"normal_10\">";
      $salida .= "          <TD ><font size='2'><b>TIPO DE CITA</b> :</font>&nbsp;<font size='3'>".$this->datos[tipoconsulta]."</font></TD>";
      $salida .= "          <TD colspan='2'><font size='2'><b>ATIENDE Dr(a)</b> :</font>&nbsp;<font size='3'>".$this->datos[profesional]."</font></TD>";
      $salida .= "        </TR>";
      
      if($this->datos[nom_consultorio]){
        $salida .= "        <TR class=\"normal_10\">";
        $salida .= "          <TD ><font size='2'><b>CONSULTORIO</b> :</font>&nbsp;<font size='3'>".$this->datos[nom_consultorio]."</font></TD>";
        $salida .= "          <TD colspan='2'><font size='2'><b>TIPO</b> :</font>&nbsp;<font size='3'>".$this->datos[ubicacion]."</font></TD>";
        $salida .= "        </TR>";
      }
      
      
      if($this->datos['sw_anestesiologo'])
      {
        $salida .= "        <TR class=\"label\">";
        $salida .= "          <TD colspan='3'>\n";
        $salida .= "            <font size='2'>\n";
        $salida .= "              EL PACIENTE ".(($this->datos['sw_anestesiologo'] == '1')? "":"NO")." REQUIERE ANESTESIÓLOGO\n";
        $salida .= "            </font>\n";
        $salida .= "          </TD>";
        $salida .= "        </TR>";
      }
      $salida .= "        <TR class=\"normal_10\">";
      $salida .= "          <TD colspan='3'><font size='2'><b>VALOR</b> :</font>&nbsp;<font size='3'>$".FormatoValor($this->datos[liqcita][valor_total_paciente])."</font></TD>";
      $salida .= "        </TR>";
      $salida .= "      </table>";
      $salida .= "    </TD>";
      $salida .= "  </TR>";
      $salida .= "</table>";
      $salida .= "<br>";
      $salida .= "<table width='100%' border=0>";
      $salida .= "  <TR>";
      $salida .= "    <TD align='right'><font size='1'><b>Asigno cita</b> :&nbsp;".$this->GetNomUsuario($this->datos[UsuarioId])."</font></TD>";
      $salida .= "  </TR>";
      $salida .= "</table>";
      $salida .= "<br>";
      $salida .= "<br>";
      $salida .= "<table width='100%' border=0>";
      $salida .= "  <TR><font size='3'><b>";
      $salida .= "    <TD align='center'>Recuerde que si no puede asistir a la cita debe cancelarla al menos con ".$this->datos[DiasCancelacion]." horas antes</TD>";
      $salida .= "  </TR>";
      $salida .= "  <TR><font size='3'><b>";
      $salida .= "    <TD align='center'>Para cualquier solicitud comunicarse al telefono ".$this->datos[TelefonoCancelacion]."</TD>";
      $salida .= "  </TR>";
      $salida .= "  <TR><font size='3'><b>";
      $salida .= "    <TD align='center'>Favor llegar 15 minutos antes de la hora asignada.</TD>";
      $salida .= "  </TR>";					
      $salida .= "</table>";
      return $salida;
    }
    /**
    *
    */
    function GetNomUsuario($usuario_id)
    {
		   list($dbconn) = GetDBconn();
        
				//siempre se hace la del paciente
				$query = "select usuario from system_usuarios where usuario_id=$usuario_id;";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}
				$var=$result->fields[0];
				$result->Close();
        return $var;
    }
  }
?>