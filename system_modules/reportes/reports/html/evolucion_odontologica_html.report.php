<?php
//Reporte de prueba formato HTML
//
//Un reporte es una clase con el nombre de reporte y el sufijo '_report'
class evolucion_odontologica_html_report
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
    function evolucion_odontologica_html_report($datos=array())
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

//FUNCION CrearReporte()
//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
	function CrearReporte()
	{
		$pfj=$this->frmPrefijo;

		IncludeLib("funciones_facturacion");
		IncludeLib("tarifario_cargos");

		$infoEvolucion = $this->Get_Evoluciones_Odontologicas();
		$Cuentas = $this->BuscarCuentas($this->datos[cuenta]);
		$usuario = $this->NombreUs($this->datos[usuario_id]);

		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar', 'inscripcion'.$pfj=>$programas));
		$Salida.="<form name=\"evoluciones_odontologicas$pfj\" action=\"$accion\" method=\"post\">";

		$Salida.="<br><br><center>";
		$Salida.="<label><font size='6' face='arial'>EVOLUCION ODONTOLOGICA</font></label>";
		$Salida.="</center><br><br>";

		$Salida.="<table border=\"1\" width=\"100%\" align=\"center\" class=\"hc_table_list\">";
		$Salida.="<tr class=\"hc_table_list_title\">";
		$Salida.="<td width=\"100%\" align=\"center\"><font size='4' face='arial'>EVOLUCION HISTORIA CLINICA ODONTOLOGICA</font></td>";
		$Salida.="</tr>";
          
          
		$Salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$Salida.="<td width=\"100%\" align=\"center\"><font size='4' face='arial'>DATOS EVOLUCION</font></td>";
		$Salida.="</tr>";
 

		foreach($infoEvolucion as $k => $v)
		{
			$Salida.="<tr>";
			$Salida.="<td width=\"100%\">";
			$Salida.="<table border=\"0\" class=\"hc_table_list\" width=\"100%\">";

			$Salida.="<tr class=\"hc_table_list_title\" align=\"center\">";
			$Salida.="<td width=\"5%\"><b><font size='2' face='arial'>EVOLUCION</font></b></td>";
               $Salida.="<td width=\"5%\"><b><font size='2' face='arial'>FECHA</font></b></td>";
			$Salida.="<td width=\"5%\"><b><font size='2' face='arial'>DIENTE</font></b></td>";
			$Salida.="<td width=\"10%\"><b><font size='2' face='arial'>SUPERFICIE</font></b></td>";
			$Salida.="<td width=\"55%\"><b><font size='2' face='arial'>DESCRIPCION DEL PROCEDIMIENTO EJECUTADO</font></b></td>";
			$Salida.="<td width=\"5%\"><b><font size='2' face='arial'>AUTORIZACION</font></b></td>";
			$Salida.="<td width=\"5%\"><b><font size='2' face='arial'>FACTURA</font></b></td>";
			$Salida.="<td width=\"5%\"><b><font size='2' face='arial'>CM/CP</font></b></td>";
               $Salida.="<td width=\"5%\"><b><font size='2' face='arial'>DX</font></b></td>";
			$Salida.="<td width=\"10%\"><b><font size='2' face='arial'>ODONTOLOGO</font></b></td>";
			$Salida.="</tr>";

			for($j=0; $j<sizeof($v[evo]); $j++)
			{   
                    $usuario1 = $this->NombreUs($v[evo][$j][11]);

                    list($fecha,$hora) = explode(" ",$v[evo][$j][8]);
                    $fecha = $fecha;
          
				/*OBTENER EQUIVALENCIAS*/
				$validados=ValdiarEquivalencias($this->datos[plan],$v[evo][$j][5]);
				/*FIN OBTENER EQUIVALENCIAS*/

				if(!empty($validados))
				{
					$cargo_liq[0]=array('tarifario_id'=>$validados[0]['tarifario_id'],'cargo'=>$validados[0]['cargo'],'cantidad'=>1,'autorizacion_int'=>'','autorizacion_ext'=>'');

					/*LIQUIDAR CUENTA*/
					$resul=LiquidarCargosCuentaVirtual($cargo_liq,array(),array(),array(),$this->datos[plan],$Cuentas[0],$Cuentas[1],$Cuentas[2],$this->datos[servicio],$this->datos[tipoidpaciente],$this->datos[paciente],'','');
					/*FIN LIQUIDAR CUENTA*/

					$Salida.="<tr class=\"modulo_list_claro\">";
					$Salida.="<td width=\"5%\" align=\"center\"><font size='1' face='arial'>".$v[evo][$j][9]."</font></td>";
                         $Salida.="<td width=\"5%\" align=\"center\"><font size='1' face='arial'>".$fecha."</font></td>";
					$Salida.="<td width=\"5%\" align=\"center\"><font size='1' face='arial'>".$v[evo][$j][0]."</font></td>";
					$Salida.="<td width=\"10%\" align=\"center\"><font size='1' face='arial'>".$v[evo][$j][2]."</font></td>";
					$Salida.="<td width=\"55%\" align=\"justify\"><font size='1' face='arial'>".$v[evo][$j][6]." - ".$v[evo][$j][3]." -</font>";
					$Salida.="<label class=\"label_error\"><font size='1' face='arial'>"."  (".$v[evo][$j][4].").</font></label></td>";
					$Salida.="<td width=\"5%\" align=\"center\">&nbsp;</td>";
					$Salida.="<td width=\"5%\" align=\"center\"><font size='1' face='arial'>".$this->datos[cuenta]."</font></td>";
					$Salida.="<td width=\"5%\" align=\"center\"><font size='1' face='arial'>".$resul['valor_total_paciente']."</font></td>";
                         $Salida.="<td width=\"5%\" align=\"center\"><font size='1' face='arial'>".$v[evo][$j][7]."</font></td>";
					$Salida.="<td width=\"10%\" align=\"center\"><font size='1' face='arial'>".substr($usuario1,0,15)."</font></td>";
					$Salida.="</tr>";
				}
				else
				{
					$Salida.="<tr class=\"modulo_list_claro\">";
					$Salida.="<td width=\"5%\" align=\"center\"><font size='1' face='arial'>".$v[evo][$j][9]."</font></td>";
                         $Salida.="<td width=\"5%\" align=\"center\"><font size='1' face='arial'>".$fecha."</font></td>";
					$Salida.="<td width=\"5%\" align=\"center\"><font size='1' face='arial'>".$v[evo][$j][0]."</font></td>";
					$Salida.="<td width=\"10%\" align=\"center\"><font size='1' face='arial'>".$v[evo][$j][2]."</font></td>";
					$Salida.="<td width=\"55%\" align=\"justify\"><font size='1' face='arial'>".$v[evo][$j][6]." - ".$v[evo][$j][3]." -</font>";
					$Salida.="<label class=\"label_error\"><font size='1' face='arial'>"."  (".$v[evo][$j][4].").</font></label></td>";
					$Salida.="<td width=\"5%\" align=\"center\">&nbsp;</td>";
					$Salida.="<td width=\"5%\" align=\"center\"><font size=1' face='arial'>".$this->datos[cuenta]."</font></td>";
					$Salida.="<td width=\"5%\" align=\"center\"><font size='1' face='arial'>Sin Equivalencia.</font></td>";
                         $Salida.="<td width=\"5%\" align=\"center\"><font size='1' face='arial'>".$v[evo][$j][7]."</font></td>";
					$Salida.="<td width=\"10%\" align=\"center\"><font size='1' face='arial'>".substr($usuario1,0,15)."</font></td>";
					$Salida.="</tr>";
				}

			}
			$Salida.="</table>";
			$Salida.="</td>";
			$Salida.="</tr>";
		}
		$Salida.="</table><BR>";
          
          $apoyos=$this->BuscarApoyosOdontograma();
          if($apoyos<>NULL)
          {
               $Salida.="<table border=\"1\" class=\"hc_table_list\" width=\"100%\">";

               $Salida.="<tr class=\"hc_table_list_title\" align=\"center\">";
               $Salida.="<td width=\"8%\"><font size='2' face='arial'>EVOLUCION</font></td>";
               $Salida.="<td width=\"8%\"><font size='2' face='arial'>FECHA</font></td>";
               $Salida.="<td width=\"5%\"><font size='2' face='arial'>DIENTE</font></td>";
               $Salida.="<td width=\"6%\"><font size='2' face='arial'>CANTIDAD</font></td>";
               $Salida.="<td width=\"40%\"><font size='2' face='arial'>DESCRIPCION DEL PROCEDIMIENTO EJECUTADO</font></td>";
               $Salida.="<td width=\"10%\"><font size='2' face='arial'>AUTORIZACION</font></td>";
               $Salida.="<td width=\"10%\"><font size='2' face='arial'>FACTURA</font></td>";
               $Salida.="<td width=\"10%\"><font size='2' face='arial'>CM / CP</font></td>";
               $Salida.="<td width=\"10%\"><font size='2' face='arial'>ODONTOLOGO</font></td>";
               $Salida.="</tr>";
               
               for($i=0;$i<sizeof($apoyos);$i++)
               {
                    if( $i % 2)
                    {
                         $estilo='modulo_list_claro';
                    }
                    else
                    {
                         $estilo='modulo_list_oscuro';
                    }

                    list($fecha1,$hora) = explode(" ",$apoyos[$i]['fecha']);
                    $fecha1 = $fecha1;
                    
                    /*OBTENER EQUIVALENCIAS*/
                    $validados=ValdiarEquivalencias($this->datos[plan],$apoyos[$i]['cargo']);
                    /*FIN OBTENER EQUIVALENCIAS*/

                    if(!empty($validados))
                    {
                         $cargo_liq[0]=array('tarifario_id'=>$validados[0]['tarifario_id'],'cargo'=>$validados[0]['cargo'],'cantidad'=>1,'autorizacion_int'=>'','autorizacion_ext'=>'');

                         /*LIQUIDAR CUENTA*/
                         $resul=LiquidarCargosCuentaVirtual($cargo_liq,array(),array(),array(),$this->datos[plan],$Cuentas[0],$Cuentas[1],$Cuentas[2],$this->datos[servicio],$this->datos[tipoidpaciente],$this->datos[paciente],'','');
                         /*FIN LIQUIDAR CUENTA*/
                         $Salida.="<tr class=\"$estilo\">";
                         $Salida.="<td align=\"center\"><font size='1' face='arial'>".$apoyos[$i]['evolucion_id']."";
                         $Salida.="</font></td>";
                         $Salida.="<td align=\"center\"><font size='1' face='arial'>".$fecha1."";
                         $Salida.="</font></td>";
                         $Salida.="<td align=\"center\"><font size='1' face='arial'>".$apoyos[$i]['hc_tipo_ubicacion_diente_id']."";
                         $Salida.="</font></td>";
                         $Salida.="<td align=\"center\"><font size='1' face='arial'>".$apoyos[$i]['cantidad']."";
                         $Salida.="</font></td>";
                         $Salida.="<td align=\"justify\"><font size='1' face='arial'>".$apoyos[$i]['descripcion']."";
                         $Salida.="</font></td>";
                         $Salida.="<td align=\"center\">&nbsp;";
                         $Salida.="</font></td>";
                         $Salida.="<td align=\"center\"><font size='1' face='arial'>".$this->datos[cuenta]."";
                         $Salida.="</font></td>";
                         $Salida.="<td align=\"center\"><font size='1' face='arial'>".$resul['valor_total_paciente']."";
                         $Salida.="</font></td>";
                         $Salida.="<td align=\"center\"><font size='1' face='arial'>".substr($usuario,0,15).".";
                         $Salida.="</font></td>";
                         $Salida.="</tr>";
                    }
                    else
                    {
                         $Salida.="<tr class=\"$estilo\">";
                         $Salida.="<td align=\"center\"><font size='1' face='arial'>".$apoyos[$i]['evolucion_id']."";
                         $Salida.="</font></td>";
                         $Salida.="<td align=\"center\"><font size='1' face='arial'>".$fecha1."";
                         $Salida.="</font></td>";
                         $Salida.="<td align=\"center\"><font size='1' face='arial'>".$apoyos[$i]['hc_tipo_ubicacion_diente_id']."";
                         $Salida.="</font></td>";
                         $Salida.="<td align=\"center\"><font size='1' face='arial'>".$apoyos[$i]['cantidad']."";
                         $Salida.="</font></td>";
                         $Salida.="<td align=\"justify\"><font size='1' face='arial'>".$apoyos[$i]['descripcion']."";
                         $Salida.="</font></td>";
                         $Salida.="<td align=\"center\">&nbsp;";
                         $Salida.="</font></td>";
                         $Salida.="<td align=\"center\"><font size='1' face='arial'>".$this->datos[cuenta]."";
                         $Salida.="</font></td>";
                         $Salida.="<td align=\"center\"><font size='1' face='arial'>Sin Equivalencia.";
                         $Salida.="</font></td>";
                         $Salida.="<td align=\"center\"><font size='1' face='arial'>".substr($usuario,0,15).".";
                         $Salida.="</font></td>";
                         $Salida.="</tr>";                    
                    }
               }
               $Salida.="</table><br>";
          }
          
          $presup=$this->BuscarPresupuestosOdontograma();
          if($presup<>NULL)
          {
               $Salida.="<table border=\"1\" class=\"hc_table_list\" width=\"100%\">";
               $Salida.="<tr class=\"hc_table_list_title\" align=\"center\">";
               $Salida.="<td width=\"8%\"><font size='2' face='arial'>EVOLUCION</font></td>";
               $Salida.="<td width=\"8%\"><font size='2' face='arial'>FECHA</font></td>";
               $Salida.="<td width=\"55%\"><font size='2' face='arial'>DESCRIPCION DEL PROCEDIMIENTO EJECUTADO</font></td>";
               $Salida.="<td width=\"8%\"><font size='2' face='arial'>AUTORIZACION</font></td>";
               $Salida.="<td width=\"8%\"><font size='2' face='arial'>FACTURA</font></td>";
               $Salida.="<td width=\"8%\"><font size='2' face='arial'>CM / CP</font></td>";
               $Salida.="<td width=\"8%\"><font size='2' face='arial'>DX</font></td>";
               $Salida.="<td width=\"10%\"><font size='2' face='arial'>ODONTOLOGO</font></td>";
               $Salida.="</tr>";

               for($i=0;$i<sizeof($presup);$i++)
               {
                    if( $i % 2)
                    {
                         $estilo='modulo_list_claro';
                    }
                    else
                    {
                         $estilo='modulo_list_oscuro';
                    }
                    
                    /*OBTENER EQUIVALENCIAS*/
                    $validados=ValdiarEquivalencias($this->datos[plan],$presup[$i]['cargo']);
                    /*FIN OBTENER EQUIVALENCIAS*/

                    if(!empty($validados))
                    {
                         $cargo_liq[0]=array('tarifario_id'=>$validados[0]['tarifario_id'],'cargo'=>$validados[0]['cargo'],'cantidad'=>1,'autorizacion_int'=>'','autorizacion_ext'=>'');

                         /*LIQUIDAR CUENTA*/
                         $resul=LiquidarCargosCuentaVirtual($cargo_liq,array(),array(),array(),$this->datos[plan],$Cuentas[0],$Cuentas[1],$Cuentas[2],$this->datos[servicio],$this->datos[tipoidpaciente],$this->datos[paciente],'','');
                         /*FIN LIQUIDAR CUENTA*/

                         $Salida.="<tr class=\"$estilo\">";
                         $Salida.="<td align=\"center\"><font size='1' face='arial'>".$presup[$i]['evolucion_id']."";
                         $Salida.="</font></td>";
                         
                         list($fecha2,$hora) = explode(" ",$presup[$i]['fecha']);
                         $fecha2 = $fecha2;
                         
                         $Salida.="<td align=\"center\"><font size='1' face='arial'>".$fecha2."";
                         $Salida.="</font></td>";
                         $Salida.="<td align=\"justify\"><font size='1' face='arial'>".$presup[$i]['descripcion']."";
                         $Salida.="</font></td>";
                         $Salida.="<td align=\"center\"><font size='1' face='arial'>&nbsp;";
                         $Salida.="</font></td>";
                         $Salida.="<td align=\"center\"><font size='1' face='arial'>".$this->datos[cuenta]."";
                         $Salida.="</font></td>";
                         $Salida.="<td align=\"center\"><font size='1' face='arial'>".$resul['valor_total_paciente']."";
                         $Salida.="</font></td>";
                         
                         $dx_ppto = $this->Select_DX_Presupuestos($presup[$i]['cargo'], $presup[$i]['hc_odontograma_primera_vez_id']);
                         $Salida.="<td align=\"center\"><font size='1' face='arial'>".$dx_ppto."";
                         $Salida.="</font></td>";

                         $Salida.="<td align=\"center\"><font size='1' face='arial'>".substr($usuario,0,15).".";
                         $Salida.="</font></td>";
                         $Salida.="</tr>";
                    }
                    else
                    {    
                         $Salida.="<td align=\"center\"><font size='1' face='arial'>".$presup[$i]['evolucion_id']."";
                         $Salida.="</font></td>";
                         $Salida.="<td align=\"center\"><font size='1' face='arial'>".$fecha2."";
                         $Salida.="</font></td>";
                         $Salida.="<td align=\"justify\"><font size='1' face='arial'>".$presup[$i]['descripcion']."";
                         $Salida.="</font></td>";
                         $Salida.="<td align=\"center\"><font size='1' face='arial'>&nbsp;";
                         $Salida.="</font></td>";
                         $Salida.="<td align=\"center\"><font size='1' face='arial'>".$this->datos[cuenta]."";
                         $Salida.="</font></td>";
                         $Salida.="<td align=\"center\"><font size='1' face='arial'>Sin Equivalencia.";
                         $Salida.="</font></td>";
                         
                         $dx_ppto = $this->Select_DX_Presupuestos($presup[$i]['cargo'], $presup[$i]['hc_odontograma_primera_vez_id']);
                         $Salida.="<td align=\"center\"><font size='1' face='arial'>".$dx_ppto."";
                         $Salida.="</font></td>";
                         
                         $Salida.="<td align=\"center\"><font size='1' face='arial'>".substr($usuario,0,15).".";
                         $Salida.="</font></td>";
                         $Salida.="</tr>";
                    }
               }
               $Salida.="</table>";
          }
		$Salida.="</form>";
          return $Salida;
     }


//AQUI TODOS LOS METODOS QUE USTED QUIERA


	function Get_Evoluciones_Odontologicas()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query="SELECT A.hc_odontograma_primera_vez_id, A.evolucion_id, B.fecha
		FROM hc_odontogramas_primera_vez AS A, hc_evoluciones AS B
		WHERE A.tipo_id_paciente='".$this->datos[tipoidpaciente]."'
		AND A.paciente_id='".$this->datos[paciente]."'
		AND A.sw_activo='1'
		AND A.evolucion_id = B.evolucion_id
		ORDER BY A.hc_odontograma_primera_vez_id ASC;";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while($data = $resulta->FetchRow())
		{
			$odonto[] = $data;
		}

		if(!empty($odonto))
		{
			for($i=0; $i<sizeof($odonto); $i++)
			{
				$query="(SELECT A.hc_tipo_ubicacion_diente_id,
				A.hc_odontograma_primera_vez_id,
				B.descripcion AS des1,
				C.descripcion AS des2,
				D.descripcion AS des3,
				E.cargo,
				F.descripcion AS des4,
                    H.diagnostico_id,
                    H.fecha_registro,
                    H.evolucion_id,
                    I.diagnostico_nombre,
                    A.usuario_id,
                    1 AS control
       
				FROM hc_odontogramas_primera_vez_detalle AS A,
				hc_tipos_cuadrantes_dientes AS B,
				hc_tipos_problemas_dientes AS C,
				hc_tipos_productos_dientes AS D,
				hc_tipos_problemas_soluciones_dientes AS E,
				cups AS F, hc_odontogramas_tratamientos_evolucion_primera_vez AS H,
                    diagnosticos AS I
				WHERE A.hc_odontograma_primera_vez_id=".$odonto[$i][0]."
				AND A.hc_tipo_cuadrante_id=B.hc_tipo_cuadrante_id
				AND A.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
				AND A.hc_tipo_producto_diente_id=D.hc_tipo_producto_diente_id
				AND A.hc_tipo_problema_diente_id=E.hc_tipo_problema_diente_id
				AND A.hc_tipo_producto_diente_id=E.hc_tipo_producto_diente_id
                    AND A.hc_odontograma_primera_vez_detalle_id=H.hc_odontograma_primera_vez_detalle_id
                    AND H.diagnostico_id=I.diagnostico_id
				AND A.estado='0'
				AND E.cargo=F.cargo
				ORDER BY A.hc_odontograma_primera_vez_id, A.hc_tipo_ubicacion_diente_id, control)
                    
                    UNION

				(SELECT A.hc_tipo_ubicacion_diente_id, 
                     A.hc_odontograma_primera_vez_id, 
                     B.descripcion AS des1, 
                     C.descripcion AS des2, 
                     D.descripcion AS des3, 
                     E.cargo, 
                     F.descripcion AS des4, 
                     H.diagnostico_id, 
                     H.fecha_registro, 
                     H.evolucion_id, 
                     I.diagnostico_nombre,
                     A.usuario_id,
                     2 AS control

				FROM hc_odontogramas_primera_vez_detalle AS A, 
                    hc_tipos_cuadrantes_dientes AS B, hc_tipos_problemas_dientes AS C,
                    hc_tipos_productos_dientes AS D, 
                    hc_tipos_problemas_soluciones_dientes AS E, cups AS F, 
                    hc_odontogramas_tratamientos_evolucion_tratamiento AS H, 
                    diagnosticos AS I 

				WHERE A.hc_odontograma_primera_vez_id=".$odonto[$i][0]."
                    AND A.hc_tipo_cuadrante_id=B.hc_tipo_cuadrante_id 
                    AND A.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id 
                    AND A.hc_tipo_producto_diente_id=D.hc_tipo_producto_diente_id 
                    AND A.hc_tipo_problema_diente_id=E.hc_tipo_problema_diente_id 
                    AND A.hc_tipo_producto_diente_id=E.hc_tipo_producto_diente_id 
                    AND A.hc_odontograma_primera_vez_detalle_id=H.hc_odontograma_tratamiento_detalle_id 
                    AND H.diagnostico_id=I.diagnostico_id 
                    AND A.estado='0' 
                    AND E.cargo=F.cargo 
                    ORDER BY A.hc_odontograma_primera_vez_id, A.hc_tipo_ubicacion_diente_id, control);";

				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				while($var = $resulta->FetchRow())
				{
					$odonto[$i][evo][] = $var;
				}
			}
		}
		return $odonto;
	}

	function BuscarCuentas($cuenta)
	{
		list($dbconn) = GetDBconn();
		$query="SELECT tipo_afiliado_id,
		rango,
		semanas_cotizadas
		FROM cuentas
		WHERE numerodecuenta=".$cuenta.";";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$odonto[0]=$resulta->fields[0];
		$odonto[1]=$resulta->fields[1];
		$odonto[2]=$resulta->fields[2];
		return $odonto;
	}

	function NombreUs($user)
	{
		list($dbconn) = GetDBconn();
		$query="SELECT nombre
		FROM system_usuarios
		WHERE usuario_id=".$user.";";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}

		list($usuario) = $resulta->FetchRow();
		return $usuario;
	}
     
     function BuscarApoyosOdontograma()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT A.hc_odontograma_primera_vez_id,
          A.evolucion_id,
		B.cargo,
		B.hc_tipo_ubicacion_diente_id,
		B.cantidad,
		B.estado,
		C.descripcion,
          D.fecha
		FROM hc_odontogramas_primera_vez AS A
          LEFT JOIN hc_evoluciones AS D on (A.evolucion_id=D.evolucion_id),
		hc_odontogramas_primera_vez_apoyod AS B,
		cups AS c
		WHERE A.tipo_id_paciente='".$this->datos[tipoidpaciente]."'
		AND A.paciente_id='".$this->datos[paciente]."'
		AND A.sw_activo='1'
		AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
          AND B.estado='0'
		AND B.cargo=C.cargo;";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function BuscarPresupuestosOdontograma()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT A.hc_odontograma_primera_vez_id,
          A.evolucion_id,
		B.cargo,
		B.cantidad,
		B.estado,
		C.descripcion,
          D.fecha
		FROM hc_odontogramas_primera_vez AS A 
          LEFT JOIN hc_evoluciones AS D on (A.evolucion_id=D.evolucion_id),
		hc_odontogramas_primera_vez_presupuesto AS B,
		cups AS c
		WHERE A.tipo_id_paciente='".$this->datos[tipoidpaciente]."'
		AND A.paciente_id='".$this->datos[paciente]."'
		AND A.sw_activo='1'
		AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
          AND B.estado='0'
		AND B.cargo=C.cargo;";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}
     
     function Select_DX_Presupuestos($cargo, $odontograma)
	{
		list($dbconn) = GetDBconn();
		$query="SELECT diagnostico_id
		FROM hc_odontogramas_tratamientos_evolucion_presupuesto
		WHERE cargo = $cargo
		AND hc_odontograma_primera_vez_id = $odontograma;";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		list($dx_ppto) = $resulta->FetchRow();
		return $dx_ppto;
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
