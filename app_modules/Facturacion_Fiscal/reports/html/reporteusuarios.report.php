<?php

/**
 * $Id: reporteusuarios.report.php,v 1.1.1.1 2009/09/11 20:36:46 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class reporteusuarios_report
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
	function reporteusuarios_report($datos=array())
	{
			$this->datos=$datos;
			return true;
	}


	function GetMembrete()
	{
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'',
												'subtitulo'=>'','logo'=>'logocliente.png','align'=>'left'));
			return $Membrete;
	}

	/**
	*
	*/
	function CrearReporte()
	{
			$arr=$this->DatosReporte($this->datos[usuario],$this->datos[fecha],$this->datos[empresa]);
			IncludeLib("tarifario");
			$Salida.="<p class=\"normal_10\" align=\"right\">Fecha impresión: ".date("Y-m-d")."</p>";
			$Salida.="<br>";
			$Salida.="<p class=\"normal_12N\" align=\"CENTER\">USUARIO: ".$arr[0][usuario]." - ".$arr[0][nombreu]."  -  Fecha Facturas: ".$arr[0][fecha]."</p>";
			$Salida.="<br>";
			$total=0;
			$cuentas=$valpagarpaciente=$valpagarcliente=$cant=0;
			$totalfact=$totalcuenta=0;
			for($i=0; $i<sizeof($arr); )
			{
					$Salida.="<p class=\"normal_12N\">".$arr[$i][nombre_tercero]."</p>";
					$d=$i;
					while($arr[$i][tipo_id_tercero]==$arr[$d][tipo_id_tercero]
					AND $arr[$i][tercero_id]==$arr[$d][tercero_id])
					{
							$Salida.="<p class=\"normal_10N\">".$arr[$d][plan_descripcion]."</p>";
							$Salida.="<DIV>";
							//tabla con el cabezote del listado
							$Salida.="<table border=\"1\" width=\"100%\" align=\"center\" cellspacing=\"3\"  cellpadding=\"2\">";
							$Salida.="<tr align=\"center\" class=\"normal_10N\">";
							$Salida.="<td width=\"15%\">FACTURA</td>";
							$Salida.="<td width=\"15%\">IDENTIFICACION</td>";
							$Salida.="<td width=\"15%\">PACIENTE</td>";
							$Salida.="<td width=\"10%\">CUENTA</td>";
							$Salida.="<td width=\"10%\">BONOS</td>";
							$Salida.="<td width=\"10%\">VAL PACIE.</td>";
							$Salida.="<td width=\"10%\">VAL CLIEN.</td>";
							$Salida.="<td width=\"10%\">TOTAL</td>";
							$Salida.="</tr>";
							$j=$d;
							$sub=$total=$valpac=$valcli=$bono0;
							while($arr[$d][plan_id]==$arr[$j][plan_id])
							{
										$Salida.="<tr class=\"normal_10\">";
										//es una factura
										if(!empty($arr[$j][factura_fiscal]))
										{
											if($arr[$j][factura_fiscal]==$arr[$j-1][factura_fiscal]
													AND $arr[$j][prefijo]==$arr[$j-1][prefijo])
											{  $Salida.="<td align=\"center\">&nbsp;</td>";  }
											else
											{
													$Salida.="<td align=\"center\">".$arr[$j][prefijo]." ".$arr[$j][factura_fiscal]."</td>";
													$cant++;
											}
											$valpagarpaciente=$arr[$j][valor_total_paciente];
											$valpagarcliente=$arr[$j][valor_total_empresa];
											$totalfact+=str_replace(".","",FormatoValor($arr[$j][valor_total_empresa]));
											$sub+=$arr[$j][valor_nocubierto];
											$total+=str_replace(".","",FormatoValor($arr[$j][total_cuenta]));
											$valpac+=str_replace(".","",FormatoValor($valpagarpaciente));
											$valcli+=str_replace(".","",FormatoValor($valpagarcliente));
											$bono+=$arr[$j][total_bonos];
										}
										else
										{
													if($arr[$j][estado]==3)
													{  $Salida.="<td align=\"center\">--cuenta cuadrada--</td>";  }
													elseif($arr[$j][estado]==0)
													{  $Salida.="<td align=\"center\">--cuenta cerrada--</td>";  }
													$cuentas++;
													$totalcuenta+=$arr[$j][total_cuenta];
										}
										$Salida.="<td>".$arr[$j][tipo_id_paciente]." ".$arr[$j][paciente_id]."</td>";
										$Salida.="<td>".$arr[$j][nombre]."</td>";
										$Salida.="<td align=\"center\">".$arr[$j][numerodecuenta]."</td>";
										$Salida.="<td align=\"right\">".FormatoValor($arr[$j][total_bonos])."</td>";
										$moderadora=$arr[$j][valor_cuota_moderadora];
										$valpagarpaciente=$arr[$j][valor_total_paciente];
										$valpagarcliente=$arr[$j][valor_total_empresa];
										$Salida.="<td align=\"right\">".FormatoValor($valpagarpaciente)."</td>";
										$Salida.="<td align=\"right\">".FormatoValor($valpagarcliente)."</td>";
										$Salida.="<td align=\"right\">".FormatoValor($arr[$j][total_cuenta])."</td>";
										$Salida.="</tr>";
										$j++;
							}
							$Salida.="<tr class=\"modulo_list_oscuro\">";
							$Salida.="<td colspan=\"3\">&nbsp;</td>";
							$Salida.="<td align=\"center\" class=\"normal_10N\">SUB TOTAL ---></td>";
							$Salida.="<td class=\"normal_10N\" align=\"right\">".FormatoValor($bono)."</td>";
							$Salida.="<td class=\"normal_10N\" align=\"right\">".FormatoValor($valpac)."</td>";
							$Salida.="<td class=\"normal_10N\" align=\"right\">".FormatoValor($valcli)."</td>";
							$Salida.="<td class=\"normal_10N\" align=\"right\">".FormatoValor($total)."</td>";
							$Salida.="</tr>";
							$Salida.="</table>";
							$Salida.="</DIV>";
							$d=$j;
					}
					$i=$d;
			}
			$Salida.="<DIV>";
			$Salida.="<table border=\"0\" width=\"100%\" align=\"center\"  class=\"modulo_list_claro\">";
			if(!empty($cant))
			{
					$Salida.="<tr>";
					$Salida.="<td class=\"normal_10N\">CANTIDAD FACTURAS :  $cant";
					$Salida.="</td>";
					$Salida.="</tr>";
					$Salida.="<tr>";
					$Salida.="<td class=\"normal_10N\">VALOR TOTAL FACTURAS ($):  ".FormatoValor($totalfact)."";
					$Salida.="</td>";
					$Salida.="</tr>";
			}
			if(!empty($cuentas))
			{
					$Salida.="<tr>";
					$Salida.="<td class=\"normal_10N\">CANTIDAD CUENTAS  :  $cuentas";
					$Salida.="</td>";
					$Salida.="</tr>";
					$Salida.="<tr>";
					$Salida.="<td class=\"normal_10N\">VALOR TOTAL CUENTAS ($):  ".FormatoValor($totalcuenta)."";
					$Salida.="</td>";
					$Salida.="</tr>";
			}
			$Salida.="</table>";
			$Salida.="</DIV>";
			return $Salida;
	}


  /**
  *
  */
  function DatosReporte($usuario,$fecha,$empresa)
  {      //solo trae las facturas de cliente sw_tipo=1 cliente
        list($dbconn) = GetDBconn();
        $query = "( select c.estado, a.tipo_id_tercero, a.tercero_id, a.empresa_id, a.prefijo, a.factura_fiscal, a.total_factura,
                    a.valor_cuota_paciente, a.plan_id,
                    b.numerodecuenta, d.plan_descripcion, e.nombre_tercero, c.total_cuenta,
                    case b.sw_tipo when 0 then 'PACIENTE' else 'CLIENTE' end as tipo,
                    f.tipo_id_paciente, f.paciente_id, g.primer_nombre||' '||g.primer_apellido as nombre,
                    h.razon_social, h.tipo_id_tercero as tipoid, h.id, p.usuario, p.nombre as nombreu,
										r.total_bonos, '".$fecha."' as fecha,
										b.sw_tipo,
										c.valor_total_paciente, c.valor_total_empresa,
										a.valor_cuota_moderadora, a.total_factura, a.valor_cargos, a.gravamen
                    from fac_facturas as a, fac_facturas_cuentas as b
                    left join rc_detalle_hosp as q on(q.numerodecuenta=b.numerodecuenta)
                    left join  recibos_caja as r on(q.prefijo=r.prefijo and q.recibo_caja=r.recibo_caja),
                    cuentas as c,
                    planes as d, terceros as e, ingresos as f, pacientes as g,
                    empresas as h, system_usuarios as p
                    where a.usuario_id=$usuario and a.estado=0
                    and a.prefijo=b.prefijo and a.factura_fiscal=b.factura_fiscal
                    and b.sw_tipo=1 and b.numerodecuenta=c.numerodecuenta and a.plan_id=d.plan_id
                    and a.tipo_id_tercero=e.tipo_id_tercero and a.tercero_id=e.tercero_id
                    and c.ingreso=f.ingreso and f.tipo_id_paciente=g.tipo_id_paciente
                    and f.paciente_id=g.paciente_id
                    and a.empresa_id=h.empresa_id
                    and a.usuario_id=p.usuario_id
                    and a.empresa_id='".$empresa."'
                    and date(a.fecha_registro)='".$fecha."'
                    order by e.nombre_tercero, a.plan_id, a.prefijo, a.factura_fiscal
                  )
                  union
                  ( select c.estado, d.tipo_tercero_id, d.tercero_id, c.empresa_id, NULL, NULL, c.total_cuenta,
										c.valor_cuota_paciente, c.plan_id,
										c.numerodecuenta,d.plan_descripcion, e.nombre_tercero, c.total_cuenta,
										NULL,f.tipo_id_paciente, f.paciente_id,
										g.primer_nombre||' '||g.primer_apellido as nombre,
										h.razon_social, h.tipo_id_tercero as tipoid, h.id, p.usuario,
										p.nombre, r.total_bonos, '".$fecha."' as fecha,
										NULL,c.valor_total_paciente, c.valor_total_empresa,
										c.valor_cuota_moderadora, NULL, c.valor_total_cargos,NULL
										from  cuentas as c left join rc_detalle_hosp as q on(q.numerodecuenta=c.numerodecuenta)
										left join recibos_caja as r on(q.prefijo=r.prefijo and q.recibo_caja=r.recibo_caja),
										planes as d, terceros as e, ingresos as f, pacientes as g, empresas as h, system_usuarios as p
										where c.usuario_cierre=$usuario and c.estado=3
										and c.plan_id=d.plan_id and d.tipo_tercero_id=e.tipo_id_tercero
										and d.tercero_id=e.tercero_id and c.ingreso=f.ingreso
										and f.tipo_id_paciente=g.tipo_id_paciente and f.paciente_id=g.paciente_id
										and d.empresa_id=h.empresa_id and d.usuario_id=p.usuario_id
										and d.empresa_id='".$empresa."'
										and date(c.fecha_registro)='".$fecha."'
										order by e.nombre_tercero, c.plan_id
									)";
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
        return $var;
  }

}

?>
