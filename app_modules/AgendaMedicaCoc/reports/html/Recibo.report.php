<?php

/**
 * $Id: Recibo.report.php,v 1.1 2009/09/02 13:08:12 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class Recibo_report
{

	//CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
	function Recibo_report($datos=array())
	{
	$this->datos=$datos;
			return true;
	}

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


	function GetMembrete()
	{
		$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'<font size="6">'.GetVarConfigAplication('Cliente').'</font>',
																'subtitulo'=>'<font size="5">ASIGNACION CITAS</font>',
																'logo'=>'',
																'align'=>'center'));
		return $Membrete;
	}

    /**
    *
    */
    function CrearReporte()
    {
					/***** generamos el html ********/
					//print_r($this->datos);
					$salida.="<table width='100%' border=2>";
					$salida.="<TR>";
					$salida.="<TD>";
					$salida.="<table width='100%' border=0>";
					$salida.="<TR>";
					$salida.="<TD WIDTH='40' colspan='2'><font size='2'><b>CENTRO ATENCION</b></font>:&nbsp;<font size='3'>".$this->datos[departamento]."</font></TD>";
					$salida.="<TD WIDTH='40' ><font size='2'><b>DIRECCION</b></font>:&nbsp;<font size='3'>".$this->datos[departamentoUbicacion]."</font></TD>";
					$salida.="<TD WIDTH='40' ><font size='2'><b>TELEFONO</b></font>:&nbsp;<font size='3'>".$this->datos[telefono_cancelacion_cita]."</font></TD>";
					$salida.="</TR>";
					$salida.="<TR>";
					$salida.="<TD WIDTH='60'><font size='2'><b>CITA NO.</b> </font>:&nbsp;<font size='3'>".$this->datos['idcita']."</font></TD>";
					$salida.="<TD  WIDTH='40'><font size='2'><b>FECHA</b></font> :&nbsp;<font size='3'>".$this->datos[fechacita]."</font></TD>";
					$salida.="  </TR>";
					$salida.="  <TR>";
					$salida.="<TD  WIDTH='60'><font size='2'><b>NOMBRE</b>:</font>&nbsp;<font size='3'>".$this->datos[paciente]."</font></TD>";
					$salida.="<TD  WIDTH='40'><font size='2'><b>IDENTIFICACION</b>:</font>&nbsp;<font size='3'>".$this->datos[identificacion]."</font></TD>";
					$salida.="  </TR>";

					$salida.="  <TR>";
					$salida.="<TD  WIDTH='30'><font size='2'><b>TIPO DE CITA</b> :</font>&nbsp;<font size='3'>".$this->datos[tipoconsulta]."</font></TD>";
					$salida.="<TD  WIDTH='30'><font size='2'><b>ATIENDE Dr(a)</b> :</font>&nbsp;<font size='3'>".$this->datos[profesional]."</font></TD>";
					$salida.="  </TR>";
					$salida.="  <TR>";
					$salida.="<TD  WIDTH='30' colspan='2'><font size='2'><b>VALOR</b> :</font>&nbsp;<font size='3'>$".$this->datos[liqcita][valor_total_paciente]."</font></TD>";
					$salida.="  </TR>";
					$salida.="</table>";
					$salida.="</TD>";
					$salida.="  </TR>";
					$salida.="</table>";
					$salida.="<br>";
					$salida.="<table width='100%' border=0>";
					$salida.="  <TR>";
					$salida.="<TD  WIDTH='30' align='right'><font size='1'><b>Asigno cita</b> :&nbsp;".$this->GetNomUsuario($this->datos[UsuarioId])."</font></TD>";
					$salida.="  </TR>";
					$salida.="</table>";
					$salida.="<br>";
					$salida.="<br>";
					$salida.="<table width='100%' border=0>";
					$salida.="  <TR><font size='3'><b>";
					$salida.="<TD  WIDTH='30' align='center'>Recuerde que si no puede asistir a la cita debe cancelarla al menos con ".$this->datos[DiasCancelacion]." horas antes</TD>";
					$salida.="  </TR>";
					$salida.="  <TR><font size='3'><b>";
					$salida.="<TD  WIDTH='30' align='center'>Para cualquier solicitud comunicarse al telefono ".$this->datos[TelefonoCancelacion]."</TD>";
					$salida.="  </TR>";
					$salida.="  <TR><font size='3'><b>";
					$salida.="<TD  WIDTH='30' align='center'>Favor llegar 15 minutos antes de la hora asignada.</TD>";
					$salida.="  </TR>";					
					$salida.="</table>";
					/*$salida.="<table width='100%' border=1>";
					$salida.="  <TR><font size='3'><b>";
					$salida.="<TD WIDTH='70'><label><font size='4'><b>CITA NO.</b> </font>:&nbsp;".$this->datos['idcita']."</label></TD>";
					$salida.="  </TR>";
					
					$salida.="  <TR><font size='3'><b>";
					$salida.="<TD  WIDTH='30' align='justify'>".$this->datos[paciente]." con identificacion ".$this->datos[identificacion]." tiene una cita ".$this->datos[liqcita][cargos][0][descripcion]." el dia ".$this->datos[fechacita]." y sera atendido por ".$this->datos[profesional]."</TD>";
					$salida.="  </TR>";
					$salida.="  <TR><font size='3'><b>";
					$salida.="<TD  WIDTH='30' align='justify'>Recuerde que si no puede asistir a la cita debe cancelarla al menos con ".$this->datos[DiasCancelacion]." horas antes</TD>";
					$salida.="  </TR>";
					$salida.="  <TR><font size='3'><b>";
					$salida.="<TD  WIDTH='30' align='justify'>Para cualquier solicitud comunicarse al telefono ".$this->datos[TelefonoCancelacion]."</TD>";
					$salida.="  </TR>";
					$salida.="  </TR>";
					$salida.="  <TR><font size='3'><b>";
					$salida.="<TD  WIDTH='30' align='justify'>Asigno cita ".$this->datos[NombreUsuario]."</TD>";
					$salida.="  </TR>";
					$salida.="</table>";*/



/*if( $i % 2){ $estilo2='#CCCCCC';}
						else {$estilo2='#DDDDDD';}*/

					/*$salida.="<table width='100%' border=1>";
					$salida.="  <TR><font size='3'><b>";
					$salida.="<TD colspan='5' WIDTH='70'><label class='label_error'>DETALLE</label></TD>";
					$salida.="<TD  WIDTH='70'>VALORES</TD>";
					$salida.="  </b></font></TR>";
					for($i=1;$i<sizeof($arr);$i++)
					{
						$salida.="<TR>";
						$x=$i;          
            while($arr[$i][cargo]==$arr[$x][cargo]
              AND $arr[$i][tarifario_id]==$arr[$x][tarifario_id])
            {			//factura cliente
									if($arr[1][sw_tipo]==1)
									{
											$salida.="  <TD colspan='5' WIDTH='70'><font size='1'>".$arr[$x][desccargo]."</font></TD>";
											 											
									}
									else
									{   //factura paciente	
											$salida.="  <TD  colspan='5' WIDTH='70'><font size='1'>".$arr[$x][desccargo]."</font></TD>";
									}
                  $x++;
            }//fin while principal
            $i=$x;
						
 						if($i==2)
						{
							$salida.="  <TD  ROWSPAN='3' WIDTH='70'><font size='1'>";
							if($arr[1][valor_cuota_paciente]>0)
							{
								$salida.=$arr[0][nombre_copago].":&nbsp;".$arr[1][valor_cuota_paciente]."<br>";
							}

							if($arr[1][valor_cuota_moderadora]>0)
							{
									$salida.=$arr[0][nombre_cuota_moderadora].":&nbsp;".$arr[1][valor_cuota_moderadora]."<br>";
							}

							if($arr[1][valor_cargo]>0)
							{
									$salida.="Valor no Cubierto :&nbsp;".$datos[1][valor_cargo]."<br>";
							}

							if($arr[1][gravamen] > 0)
							{
									$salida.="Valor no Cubierto :&nbsp;".$arr[1][gravamen]."<br>";
							}

							$salida.="TOTAL CUENTA:&nbsp;".$arr[1][total_factura]."<br>";

							$salida.="  </TD>";	
						}
						$salida.="</TR>";
					}
						$salida.="</table>";



			$salida.="<table width='100%' border=1>";
			$salida.="  <TR><font size='3'><b>";
			$salida.="<TD bgcolor='#CCCCCC'  COLSPAN='2' WIDTH='70'><label><font size='2'><b>USUARIO</b> </font>:&nbsp;".$arr[0][usuario_id].":&nbsp;".$arr[0][usuario]." </label></TD>";
			$salida.="  </TR>";*/
						
       return $salida;
    }



			
		
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
				
				
				
      // print_r($var);
        
        return $var;
    
	}



function GetFacturaXEmpresa($switche,$cuenta)
{
	list($dbconn) = GetDBconn();
	if(!empty($switche))
        {
							//$var[0]=$this->EncabezadoFactura($cuenta);
							$var[0]=$_SESSION['CAJA']['FACTURA']['encabezado'];
							$query = "select c.prefijo, c.factura_fiscal, a.valor_nocubierto,a.precio,
												a.cargo, a.tarifario_id, a.cantidad, a.fecha_cargo, a.transaccion,
												b.descripcion as desccargo, a.departamento, b.grupo_tipo_cargo, c.sw_tipo,
												e.texto1, e.texto2, e.mensaje, f.*
												from cuentas_detalle as a, tarifarios_detalle as b,
												fac_facturas_cuentas as c, fac_tipos_facturas as e, fac_facturas as f
												where a.numerodecuenta=$cuenta and a.cargo=b.cargo
												and a.tarifario_id=b.tarifario_id
												and a.cargo!='DESCUENTO'
												and c.numerodecuenta=a.numerodecuenta
												and c.sw_tipo=1
												and a.empresa_id=e.empresa_id
												and c.prefijo=e.prefijo
												and c.prefijo=f.prefijo
												and c.factura_fiscal=f.factura_fiscal
												order by b.grupo_tipo_cargo desc ";
              $result = $dbconn->Execute($query);
              if ($dbconn->ErrorNo() != 0) {
                      $this->error = "Error al Cargar el Modulo";
                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                      return false;
              }
              while(!$result->EOF)
              {
                      $var[]=$result->GetRowAssoc($ToUpper = false);
                      $result->MoveNext();
              }
							$result->Close();
                           
        }
return $var;

}





function EncabezadoFactura($cuenta)
  {
        list($dbconn) = GetDBconn();
        $query = "select (a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_bonos) as abonos,
                  a.numerodecuenta, a.ingreso, a.plan_id, a.empresa_id, b.plan_descripcion,
                  c.nombre_tercero, c.tipo_id_tercero, c.tercero_id, d.tipo_id_paciente, d.paciente_id,
                  e.primer_nombre||' '||e.segundo_nombre||' '||e.primer_apellido||' '||e.segundo_apellido as nombre,
                  e.residencia_telefono, e.residencia_direccion, d.departamento_actual as dpto, h.descripcion,
                  i.razon_social, i.direccion, i.telefonos, i.tipo_id_tercero as tipoid,
                  i.id, j.departamento, k.municipio, d.fecha_registro, a.rango, Z.tipo_afiliado_nombre,
                  b.nombre_cuota_moderadora, b.nombre_copago, x.nombre as usuario, x.usuario_id,
									a.valor_cuota_moderadora, a.valor_cuota_paciente, a.valor_nocubierto,
									a.valor_total_paciente, a.valor_total_empresa, a.valor_descuento_paciente,
									a.valor_descuento_empresa, a.valor_cubierto
                  from cuentas as a, planes as b, terceros as c, pacientes as e, departamentos as  h,
                  empresas as i, tipo_dptos as j, tipo_mpios as k, ingresos as d,
                  system_usuarios as x, tipos_afiliado as Z
                  where a.numerodecuenta=$cuenta and a.plan_id=b.plan_id and b.tercero_id=c.tercero_id
                  and b.tipo_tercero_id=c.tipo_id_tercero
                  and x.usuario_id=".UserGetUID()."
                  and a.tipo_afiliado_id=Z.tipo_afiliado_id
                  and d.ingreso=a.ingreso and d.tipo_id_paciente=e.tipo_id_paciente
                  and d.paciente_id=e.paciente_id
                  and a.empresa_id=i.empresa_id and i.tipo_pais_id=j.tipo_pais_id and i.tipo_dpto_id=j.tipo_dpto_id
                  and i.tipo_pais_id=k.tipo_pais_id and i.tipo_dpto_id=k.tipo_dpto_id and i.tipo_mpio_id=k.tipo_mpio_id
                  and d.departamento_actual=h.departamento";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        $var=$resulta->GetRowAssoc($ToUpper = false);
        $resulta->Close();
        return $var;
  }


	
}
?>

