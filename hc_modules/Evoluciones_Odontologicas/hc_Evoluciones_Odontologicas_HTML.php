<?php

/**
* Submodulo de Evoluciones Odontologicas (HTML).
*
* Submodulo para manejar los reportes de las evoluciones odontologicas.
* @author Tizziano Perea Ocoro <tizzianop@gmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_Evoluciones_Odontologicas_HTML.php,v 1.18 2006/12/19 21:00:13 jgomez Exp $
*/

/**
* Evoluciones_Odontologicas_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo Evoluciones_Odontologicas, se extiende la clase Evoluciones_Odontologicas y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class Evoluciones_Odontologicas_HTML extends Evoluciones_Odontologicas
{

	function Evoluciones_Odontologicas_HTML()
	{
	    $this->Evoluciones_Odontologicas();//constructor del padre
       	return true;
	}

  /**
* Esta función retorna los datos de concernientes a la version del submodulo
* @access private
*/

  function GetVersion()
  {
    $informacion=array(
    'version'=>'1',
    'subversion'=>'0',
    'revision'=>'0',
    'fecha'=>'04/19/2005',
    'autor'=>'TIZZIANO PEREA OCORO',
    'descripcion_cambio' => '',
    'requiere_sql' => false,
    'requerimientos_adicionales' => '',
    'version_kernel' => '1.0'
    );
    return $informacion;
  }
////////////////////////
  
	function frmConsulta()
	{
          $pfj=$this->frmPrefijo;
          $this->BuscarPlan();
          IncludeLib("funciones_facturacion");
          IncludeLib("tarifario_cargos");
     
          $infoEvolucion = $this->Get_Evoluciones_Odontologicas();
          $Cuentas = $this->BuscarCuentas($this->cuenta);
          $usuario = $this->NombreUs($this->usuario_id);
          
          if($infoEvolucion)
          {
               $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar', 'inscripcion'.$pfj=>$programas));
               $this->salida.="<form name=\"evoluciones_odontologicas$pfj\" action=\"$accion\" method=\"post\">";
               $this->salida.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"hc_table_list\">";
               $this->salida.="<tr class=\"hc_table_list_title\">";
               $this->salida.="<td width=\"100%\" align=\"center\">CONSOLIDADO EVOLUCIONES ODONTOLOGICAS</td>";
               $this->salida.="</tr>";
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="<td width=\"100%\" align=\"center\">DATOS EVOLUCION</td>";
               $this->salida.="</tr>";
               foreach($infoEvolucion as $k => $v)
               {
                    $this->salida.="<tr>";
                    $this->salida.="<td width=\"100%\">";
                    $this->salida.="<table border=\"0\" class=\"hc_table_list\" width=\"100%\">";
     
                    $this->salida.="<tr class=\"hc_table_list_title\" align=\"center\">";
                    $this->salida.="<td width=\"8%\">EVOLUCION</td>";
                    $this->salida.="<td width=\"8%\">FECHA</td>";
                    $this->salida.="<td width=\"5%\">DIENTE</td>";
                    $this->salida.="<td width=\"10%\">SUPERFICIE</td>";
                    $this->salida.="<td width=\"40%\">DESCRIPCION DEL PROCEDIMIENTO EJECUTADO</td>";
                    $this->salida.="<td width=\"10%\">AUTORIZACION</td>";
                    $this->salida.="<td width=\"10%\">FACTURA</td>";
                    $this->salida.="<td width=\"10%\">CM / CP</td>";
                    $this->salida.="<td width=\"10%\">DX</td>";
                    $this->salida.="<td width=\"10%\">ODONTOLOGO</td>";
                    $this->salida.="</tr>";
     
                    for($j=0; $j<sizeof($v[evo]); $j++)
                    {
                         $usuario1 = $this->NombreUs($v[evo][$j][11]);
                         
                         list($fecha,$hora) = explode(" ",$v[evo][$j][8]);
     
                         /*OBTENER EQUIVALENCIAS*/
                         $validados=ValdiarEquivalencias($this->plan,$v[evo][$j][5]);
                         /*FIN OBTENER EQUIVALENCIAS*/
     
                         if(!empty($validados))
                         {
                              $cargo_liq[0]=array('tarifario_id'=>$validados[0]['tarifario_id'],'cargo'=>$validados[0]['cargo'],'cantidad'=>1,'autorizacion_int'=>'','autorizacion_ext'=>'');
     
                              /*LIQUIDAR CUENTA*/
                              $resul=LiquidarCargosCuentaVirtual($cargo_liq,array(),array(),array(),$this->plan,$Cuentas[0],$Cuentas[1],$Cuentas[2],$this->servicio,$this->tipoidpaciente,$this->paciente,'','');
                              /*FIN LIQUIDAR CUENTA*/
     
                              $this->salida.="<tr class=\"modulo_list_claro\">";
                              $this->salida.="<td width=\"8%\" align=\"center\">".$v[evo][$j][9]."</td>";
                              
                              $this->salida.="<td width=\"8%\" align=\"center\">".$fecha."</td>";
                              
                              $this->salida.="<td width=\"5%\" align=\"center\">".$v[evo][$j][0]."</td>";
                              $this->salida.="<td width=\"10%\" align=\"center\">".$v[evo][$j][2]."</td>";
                              $this->salida.="<td width=\"60%\" align=\"justify\">".$v[evo][$j][6]." - ".$v[evo][$j][3]." -";
                              $this->salida.="<label class=\"label_error\">"."  (".$v[evo][$j][4].").</label></td>";
                              $this->salida.="<td width=\"10%\" align=\"center\">&nbsp;</td>";
                              $this->salida.="<td width=\"10%\" align=\"center\">".$this->cuenta."</td>";
                              $this->salida.="<td width=\"10%\" align=\"center\">".$resul['valor_total_paciente']."</td>";
                              
                              $this->salida.="<td width=\"10%\" align=\"center\">".$v[evo][$j][7]."</td>";
                                                            
                              $this->salida.="<td width=\"10%\" align=\"center\">".substr($usuario1,0,15).".</td>";
                              $this->salida.="</tr>";
                         }
                         else
                         {
                              $this->salida.="<tr class=\"modulo_list_claro\">";
                              $this->salida.="<td width=\"8%\" align=\"center\">".$v[evo][$j][9]."</td>";
                              
                              $this->salida.="<td width=\"8%\" align=\"center\">".$fecha."</td>";
                              
                              $this->salida.="<td width=\"5%\" align=\"center\">".$v[evo][$j][0]."</td>";
                              $this->salida.="<td width=\"10%\" align=\"center\">".$v[evo][$j][2]."</td>";
                              $this->salida.="<td width=\"60%\" align=\"justify\">".$v[evo][$j][6]." - ".$v[evo][$j][3]." -";
                              $this->salida.="<label class=\"label_error\">"."  (".$v[evo][$j][4].").</label></td>";
                              $this->salida.="<td width=\"10%\" align=\"center\">&nbsp;</td>";
                              $this->salida.="<td width=\"10%\" align=\"center\">".$this->cuenta."</td>";
                              $this->salida.="<td width=\"10%\" align=\"center\">Sin Equivalencia.</td>";
                              
                              $this->salida.="<td width=\"10%\" align=\"center\">".$v[evo][$j][7]."</td>";
                              
                              $this->salida.="<td width=\"10%\" align=\"center\">".substr($usuario1,0,15).".</td>";
                              $this->salida.="</tr>";
                         }
     
                    }
                    $this->salida.="</table>";
                    $this->salida.="</td>";
                    $this->salida.="</tr>";
               }
               $this->salida.="</table><br>";
          }
     
          $apoyos=$this->BuscarApoyosOdontograma();
          if($apoyos<>NULL)
          {
               $this->salida.="<table border=\"0\" class=\"hc_table_list\" width=\"100%\">";

               $this->salida.="<tr class=\"hc_table_list_title\" align=\"center\">";
               $this->salida.="<td width=\"8%\">EVOLUCION</td>";
               $this->salida.="<td width=\"8%\">FECHA</td>";
               $this->salida.="<td width=\"55%\">DESCRIPCION DEL PROCEDIMIENTO EJECUTADO</td>";
               $this->salida.="<td width=\"8%\">AUTORIZACION</td>";
               $this->salida.="<td width=\"8%\">FACTURA</td>";
               $this->salida.="<td width=\"4%\">CAN.</td>";
               $this->salida.="<td width=\"8%\">CM / CP</td>";
               $this->salida.="<td width=\"8%\">DX</td>";                    
               $this->salida.="<td width=\"10%\">ODONTOLOGO</td>";
               $this->salida.="</tr>";
               
               for($i=0;$i<sizeof($apoyos);$i++)
               {
                    $Cantidad = ($apoyos[$i]['cantidad'] - $apoyos[$i]['cantidad_pend']);
                    
                    if( $i % 2)
                    {
                         $estilo='modulo_list_claro';
                    }
                    else
                    {
                         $estilo='modulo_list_oscuro';
                    }
                    
                    /*OBTENER EQUIVALENCIAS*/
                    $validados=ValdiarEquivalencias($this->plan,$apoyos[$i]['cargo']);
                    /*FIN OBTENER EQUIVALENCIAS*/

                    if(!empty($validados))
                    {
                         $dx_apoyos = $this->Select_DX_Apoyos($apoyos[$i]['cargo'], $apoyos[$i]['hc_odontograma_primera_vez_id']);
                    	foreach($dx_apoyos as $j => $dx_apoyos)
                         {
                              $cargo_liq[0]=array('tarifario_id'=>$validados[0]['tarifario_id'],'cargo'=>$validados[0]['cargo'],'cantidad'=>$dx_apoyos['cantidad_realizada'],'autorizacion_int'=>'','autorizacion_ext'=>'');
     
                              /*LIQUIDAR CUENTA*/
                              $resul=LiquidarCargosCuentaVirtual($cargo_liq,array(),array(),array(),$this->plan,$Cuentas[0],$Cuentas[1],$Cuentas[2],$this->servicio,$this->tipoidpaciente,$this->paciente,'','');
                              /*FIN LIQUIDAR CUENTA*/
                              
     
                              $USapoyo = $this->NombreUs($dx_apoyos['usuarioid_ppto']);
                              
                              $this->salida.="<tr class=\"$estilo\">";
                              //$this->salida.="<td align=\"center\">".$apoyos[$i]['evolucion_id']."";
                              $this->salida.="<td align=\"center\">".$dx_apoyos['evolucion_ppto']."";
                              $this->salida.="</td>";
                              
                              //list($fecha1,$hora) = explode(" ",$apoyos[$i]['fecha']);
                              list($fecha1,$hora) = explode(" ",$dx_apoyos['fechareg_ppto']);
          
                              $this->salida.="<td align=\"center\">".$fecha1."";
                              $this->salida.="</td>";
                              $this->salida.="<td align=\"justify\">".$apoyos[$i]['descripcion']."";
                              $this->salida.="</td>";
                              $this->salida.="<td align=\"center\">";
                              $this->salida.="</td>";
                              $this->salida.="<td align=\"center\">".$this->cuenta."";
                              $this->salida.="</td>";
                              
                              $this->salida.="<td align=\"center\">".$dx_apoyos['cantidad_realizada']."";
                              $this->salida.="</td>";
     
                              $this->salida.="<td align=\"center\">".$resul['valor_total_paciente']."";
                              $this->salida.="</td>";
                              
                              //$this->salida.="<td align=\"center\">".$dx_apoyos."";
                              $this->salida.="<td align=\"center\">".$dx_apoyos['diagnostico_id']."";
                              $this->salida.="</td>";
                              
                              $this->salida.="<td align=\"center\">".substr($USapoyo,0,15).".";
                              $this->salida.="</td>";
                              $this->salida.="</tr>";
                         }
                    }
                    else
                    {
                         $dx_apoyos = $this->Select_DX_Apoyos($apoyos[$i]['cargo'], $apoyos[$i]['hc_odontograma_primera_vez_id']);
                         foreach($dx_apoyos as $j => $dx_apoyos)
                         {
                              $USapoyo = $this->NombreUs($dx_apoyos['usuarioid_ppto']);
                              
                              $this->salida.="<tr class=\"$estilo\">";
                              //$this->salida.="<td align=\"center\">".$apoyos[$i]['evolucion_id']."";
                              $this->salida.="<td align=\"center\">".$dx_apoyos['evolucion_ppto']."";
                              $this->salida.="</td>";
                              
                              //list($fecha1,$hora) = explode(" ",$apoyos[$i]['fecha']);
                              list($fecha1,$hora) = explode(" ",$dx_apoyos['fechareg_ppto']);
                              $this->salida.="<td align=\"center\">".$fecha1."";
                              $this->salida.="</td>";
                              $this->salida.="<td align=\"justify\">".$apoyos[$i]['descripcion']."";
                              $this->salida.="</td>";
                              $this->salida.="<td align=\"center\">";
                              $this->salida.="</td>";
                              $this->salida.="<td align=\"center\">".$this->cuenta."";
                              $this->salida.="</td>";
                              
                              $this->salida.="<td align=\"center\">".$dx_apoyos['cantidad_realizada']."";
                              $this->salida.="</td>";
     
                              $this->salida.="<td align=\"center\">Sin Equivalencia.";
                              $this->salida.="</td>";
                              
                              
                              //$this->salida.="<td align=\"center\">".$dx_apoyos."";
                              $this->salida.="<td align=\"center\">".$dx_apoyos['diagnostico_id']."";
                              $this->salida.="</td>";
                              
                              $this->salida.="<td align=\"center\">".substr($USapoyo,0,15).".";
                              $this->salida.="</td>";
                              $this->salida.="</tr>";
                         }
                    }
               }
               $this->salida.="</table><br>";
          }
          
          $presup=$this->BuscarPresupuestosOdontograma();
          if($presup<>NULL)
          {
               $this->salida.="<table border=\"0\" class=\"hc_table_list\" width=\"100%\">";

               $this->salida.="<tr class=\"hc_table_list_title\" align=\"center\">";
               $this->salida.="<td width=\"8%\">EVOLUCION</td>";
               $this->salida.="<td width=\"8%\">FECHA</td>";
               $this->salida.="<td width=\"55%\">DESCRIPCION DEL PROCEDIMIENTO EJECUTADO</td>";
               $this->salida.="<td width=\"8%\">AUTORIZACION</td>";
               $this->salida.="<td width=\"8%\">FACTURA</td>";
               $this->salida.="<td width=\"4%\">CAN.</td>";
               $this->salida.="<td width=\"8%\">CM / CP</td>";
               $this->salida.="<td width=\"8%\">DX</td>";                    
               $this->salida.="<td width=\"10%\">ODONTOLOGO</td>";
               $this->salida.="</tr>";

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
                    $validados=ValdiarEquivalencias($this->plan,$presup[$i]['cargo']);
                    /*FIN OBTENER EQUIVALENCIAS*/

                    if(!empty($validados))
                    {
                         $dx_ppto = $this->Select_DX_Presupuestos($presup[$i]['cargo'], $presup[$i]['hc_odontograma_primera_vez_id']);
                         foreach($dx_ppto as $k => $dx_ppto)
                         {
                              $cargo_liq[0]=array('tarifario_id'=>$validados[0]['tarifario_id'],'cargo'=>$validados[0]['cargo'],'cantidad'=>$dx_ppto['cantidad_realizada'],'autorizacion_int'=>'','autorizacion_ext'=>'');
     
                              /*LIQUIDAR CUENTA*/
                              $resul=LiquidarCargosCuentaVirtual($cargo_liq,array(),array(),array(),$this->plan,$Cuentas[0],$Cuentas[1],$Cuentas[2],$this->servicio,$this->tipoidpaciente,$this->paciente,'','');
                              /*FIN LIQUIDAR CUENTA*/
     
                              $USppto = $this->NombreUs($dx_ppto['usuarioid_ppto']);
                              
                              $this->salida.="<tr class=\"$estilo\">";
                              //$this->salida.="<td align=\"center\">".$presup[$i]['evolucion_id']."";
                              $this->salida.="<td align=\"center\">".$dx_ppto['evolucion_ppto']."";
                              $this->salida.="</td>";
                              
                              //list($fecha2,$hora) = explode(" ",$presup[$i]['fecha']);
                              list($fecha2,$hora) = explode(" ",$dx_ppto['fechareg_ppto']);
          
                              $this->salida.="<td align=\"center\">".$fecha2."";
                              $this->salida.="</td>";
                              $this->salida.="<td align=\"justify\">".$presup[$i]['descripcion']."";
                              $this->salida.="</td>";
                              $this->salida.="<td align=\"center\">";
                              $this->salida.="</td>";
                              $this->salida.="<td align=\"center\">".$this->cuenta."";
                              $this->salida.="</td>";
                              
                              $this->salida.="<td align=\"center\">".$dx_ppto['cantidad_realizada']."";
                              $this->salida.="</td>";
                              
                              $this->salida.="<td align=\"center\">".$resul['valor_total_paciente']."";
                              $this->salida.="</td>";
                              
                              //$this->salida.="<td align=\"center\">".$dx_ppto."";
                              $this->salida.="<td align=\"center\">".$dx_ppto['diagnostico_id']."";
                              $this->salida.="</td>";
                              
                              $this->salida.="<td align=\"center\">".substr($USppto,0,15).".";
                              $this->salida.="</td>";
                              $this->salida.="</tr>";
                    	}
                    }
                    else
                    {
                         $dx_ppto = $this->Select_DX_Presupuestos($presup[$i]['cargo'], $presup[$i]['hc_odontograma_primera_vez_id']);
                         foreach($dx_ppto as $k => $dx_ppto)
                         {
                              $USppto = $this->NombreUs($dx_ppto['usuarioid_ppto']);
                              
                              $this->salida.="<tr class=\"$estilo\">";
                              //$this->salida.="<td align=\"center\">".$presup[$i]['evolucion_id']."";
                              $this->salida.="<td align=\"center\">".$dx_ppto['evolucion_ppto']."";
                              $this->salida.="</td>";
                              
                              //list($fecha2,$hora) = explode(" ",$presup[$i]['fecha']);
                              list($fecha2,$hora) = explode(" ",$dx_ppto['fechareg_ppto']);
                              $this->salida.="<td align=\"center\">".$fecha2."";
                              $this->salida.="</td>";
                              $this->salida.="<td align=\"justify\">".$presup[$i]['descripcion']."";
                              $this->salida.="</td>";
                              $this->salida.="<td align=\"center\">";
                              $this->salida.="</td>";
                              $this->salida.="<td align=\"center\">".$this->cuenta."";
                              $this->salida.="</td>";
     
                              $this->salida.="<td align=\"center\">".$Cantidad."";
                              $this->salida.="</td>";
                                                  
                              $this->salida.="<td align=\"center\">Sin Equivalencia.";
                              $this->salida.="</td>";
                              
                              
                              //$this->salida.="<td align=\"center\">".$dx_ppto."";
                              $this->salida.="<td align=\"center\">".$dx_ppto['cantidad_realizada']."";
                              $this->salida.="</td>";
                              
                              $this->salida.="<td align=\"center\">".substr($USppto,0,15).".";
                              $this->salida.="</td>";
                              $this->salida.="</tr>";
                         }
                    }
               }
               $this->salida.="</table><br>";
          }
          $this->salida.="</form>";
		return true;
	}


	function frmHistoria()
	{
          $pfj=$this->frmPrefijo;
          $this->BuscarPlan();
          IncludeLib("funciones_facturacion");
          IncludeLib("tarifario_cargos");
     
          $infoEvolucion = $this->Get_Evoluciones_Odontologicas2();
          $Cuentas = $this->BuscarCuentas($this->cuenta);
          $usuario = $this->NombreUs($this->usuario_id);
          
          if($infoEvolucion)
          {
               $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar', 'inscripcion'.$pfj=>$programas));
               $salida.="<form name=\"evoluciones_odontologicas$pfj\" action=\"$accion\" method=\"post\">";
               $salida.="<table border=\"1\" width=\"100%\" align=\"center\" class=\"hc_table_list\">";
               $salida.="<tr class=\"hc_table_list_title\">";
               $salida.="<td width=\"100%\" align=\"center\">EVOLUCION HISTORIA CLINICA ODONTOLOGICA</td>";
               $salida.="</tr>";
               $salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $salida.="<td width=\"100%\" align=\"center\">DATOS EVOLUCION</td>";
               $salida.="</tr>";
               foreach($infoEvolucion as $k => $v)
               {
                    $salida.="<tr>";
                    $salida.="<td width=\"100%\">";
                    $salida.="<table border=\"1\" class=\"hc_table_list\" width=\"100%\">";
     
                    $salida.="<tr class=\"hc_table_list_title\" align=\"center\">";
                    $salida.="<td width=\"8%\">EVOLUCION</td>";
                    $salida.="<td width=\"8%\">FECHA</td>";
                    $salida.="<td width=\"5%\">DIENTE</td>";
                    $salida.="<td width=\"10%\">SUPERFICIE</td>";
                    $salida.="<td width=\"40%\">DESCRIPCION DEL PROCEDIMIENTO EJECUTADO</td>";
                    $salida.="<td width=\"10%\">AUTORIZACION</td>";
                    $salida.="<td width=\"10%\">FACTURA</td>";
                    $salida.="<td width=\"10%\">CM / CP</td>";
                    $salida.="<td width=\"10%\">DX</td>";
                    $salida.="<td width=\"10%\">ODONTOLOGO</td>";
                    $salida.="</tr>";
     
                    for($j=0; $j<sizeof($v[evo]); $j++)
                    {
                         $usuario1 = $this->NombreUs($v[evo][$j][11]);
                         
                         list($fecha,$hora) = explode(" ",$v[evo][$j][8]);
     
                         /*OBTENER EQUIVALENCIAS*/
                         $validados=ValdiarEquivalencias($this->plan,$v[evo][$j][5]);
                         /*FIN OBTENER EQUIVALENCIAS*/
     
                         if(!empty($validados))
                         {
                              $cargo_liq[0]=array('tarifario_id'=>$validados[0]['tarifario_id'],'cargo'=>$validados[0]['cargo'],'cantidad'=>1,'autorizacion_int'=>'','autorizacion_ext'=>'');
     
                              /*LIQUIDAR CUENTA*/
                              $resul=LiquidarCargosCuentaVirtual($cargo_liq,array(),array(),array(),$this->plan,$Cuentas[0],$Cuentas[1],$Cuentas[2],$this->servicio,$this->tipoidpaciente,$this->paciente,'','');
                              /*FIN LIQUIDAR CUENTA*/
     
                              $salida.="<tr class=\"modulo_list_claro\">";
                              $salida.="<td width=\"8%\" align=\"center\">".$v[evo][$j][9]."</td>";
                              
                              $salida.="<td width=\"8%\" align=\"center\">".$fecha."</td>";
                              
                              $salida.="<td width=\"5%\" align=\"center\">".$v[evo][$j][0]."</td>";
                              $salida.="<td width=\"10%\" align=\"center\">".$v[evo][$j][2]."</td>";
                              $salida.="<td width=\"60%\" align=\"justify\">".$v[evo][$j][6]." - ".$v[evo][$j][3]." -";
                              $salida.="<label class=\"label_error\">"."  (".$v[evo][$j][4].").</label></td>";
                              $salida.="<td width=\"10%\" align=\"center\">&nbsp;</td>";
                              $salida.="<td width=\"10%\" align=\"center\">".$this->cuenta."</td>";
                              $salida.="<td width=\"10%\" align=\"center\">".$resul['valor_total_paciente']."</td>";
                              
                              $salida.="<td width=\"10%\" align=\"center\">".$v[evo][$j][7]."</td>";
                                                            
                              $salida.="<td width=\"10%\" align=\"center\">".substr($usuario1,0,15).".</td>";
                              $salida.="</tr>";
                         }
                         else
                         {
                              $salida.="<tr class=\"modulo_list_claro\">";
                              $salida.="<td width=\"8%\" align=\"center\">".$v[evo][$j][9]."</td>";
                              
                              $salida.="<td width=\"8%\" align=\"center\">".$fecha."</td>";
                              
                              $salida.="<td width=\"5%\" align=\"center\">".$v[evo][$j][0]."</td>";
                              $salida.="<td width=\"10%\" align=\"center\">".$v[evo][$j][2]."</td>";
                              $salida.="<td width=\"60%\" align=\"justify\">".$v[evo][$j][6]." - ".$v[evo][$j][3]." -";
                              $salida.="<label class=\"label_error\">"."  (".$v[evo][$j][4].").</label></td>";
                              $salida.="<td width=\"10%\" align=\"center\">&nbsp;</td>";
                              $salida.="<td width=\"10%\" align=\"center\">".$this->cuenta."</td>";
                              $salida.="<td width=\"10%\" align=\"center\">Sin Equivalencia.</td>";
                              
                              $salida.="<td width=\"10%\" align=\"center\">".$v[evo][$j][7]."</td>";
                              
                              $salida.="<td width=\"10%\" align=\"center\">".substr($usuario1,0,15).".</td>";
                              $salida.="</tr>";
                         }
     
                    }
                    $salida.="</table>";
                    $salida.="</td>";
                    $salida.="</tr>";
               }
               $salida.="</table><br>";
          }

          $apoyos=$this->BuscarApoyosOdontograma();
          if($apoyos<>NULL)
          {
               $salida.="<table border=\"1\" class=\"hc_table_list\" width=\"100%\">";

               $salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $salida.="<td width=\"100%\" align=\"center\" colspan=\"9\">DATOS EVOLUCION</td>";
               $salida.="</tr>";
               
               $salida.="<tr class=\"hc_table_list_title\" align=\"center\">";
               $salida.="<td width=\"8%\">EVOLUCION</td>";
               $salida.="<td width=\"8%\">FECHA</td>";
               $salida.="<td width=\"55%\">DESCRIPCION DEL PROCEDIMIENTO EJECUTADO</td>";
               $salida.="<td width=\"8%\">AUTORIZACION</td>";
               $salida.="<td width=\"8%\">FACTURA</td>";
               $salida.="<td width=\"4%\">CAN.</td>";
               $salida.="<td width=\"8%\">CM / CP</td>";
               $salida.="<td width=\"8%\">DX</td>";                    
               $salida.="<td width=\"10%\">ODONTOLOGO</td>";
               $salida.="</tr>";
               
               for($i=0;$i<sizeof($apoyos);$i++)
               {
                    $Cantidad = ($apoyos[$i]['cantidad'] - $apoyos[$i]['cantidad_pend']);
                    
                    if( $i % 2)
                    {
                         $estilo='modulo_list_claro';
                    }
                    else
                    {
                         $estilo='modulo_list_oscuro';
                    }
                    
                    /*OBTENER EQUIVALENCIAS*/
                    $validados=ValdiarEquivalencias($this->plan,$apoyos[$i]['cargo']);
                    /*FIN OBTENER EQUIVALENCIAS*/

                    if(!empty($validados))
                    {
                         $dx_apoyos = $this->Select_DX_Apoyos($apoyos[$i]['cargo'], $apoyos[$i]['hc_odontograma_primera_vez_id']);
                    	foreach($dx_apoyos as $j => $dx_apoyos)
                         {
                              $cargo_liq[0]=array('tarifario_id'=>$validados[0]['tarifario_id'],'cargo'=>$validados[0]['cargo'],'cantidad'=>$dx_apoyos['cantidad_realizada'],'autorizacion_int'=>'','autorizacion_ext'=>'');
     
                              /*LIQUIDAR CUENTA*/
                              $resul=LiquidarCargosCuentaVirtual($cargo_liq,array(),array(),array(),$this->plan,$Cuentas[0],$Cuentas[1],$Cuentas[2],$this->servicio,$this->tipoidpaciente,$this->paciente,'','');
                              /*FIN LIQUIDAR CUENTA*/
                              
     
                              $USapoyo = $this->NombreUs($dx_apoyos['usuarioid_ppto']);
                              
                              $salida.="<tr class=\"$estilo\">";
                              //$salida.="<td align=\"center\">".$apoyos[$i]['evolucion_id']."";
                              $salida.="<td align=\"center\">".$dx_apoyos['evolucion_ppto']."";
                              $salida.="</td>";
                              
                              //list($fecha1,$hora) = explode(" ",$apoyos[$i]['fecha']);
                              list($fecha1,$hora) = explode(" ",$dx_apoyos['fechareg_ppto']);
          
                              $salida.="<td align=\"center\">".$fecha1."";
                              $salida.="</td>";
                              $salida.="<td align=\"justify\">".$apoyos[$i]['descripcion']."";
                              $salida.="</td>";
                              $salida.="<td align=\"center\">";
                              $salida.="</td>";
                              $salida.="<td align=\"center\">".$this->cuenta."";
                              $salida.="</td>";
                              
                              $salida.="<td align=\"center\">".$dx_apoyos['cantidad_realizada']."";
                              $salida.="</td>";
     
                              $salida.="<td align=\"center\">".$resul['valor_total_paciente']."";
                              $salida.="</td>";
                              
                              //$salida.="<td align=\"center\">".$dx_apoyos."";
                              $salida.="<td align=\"center\">".$dx_apoyos['diagnostico_id']."";
                              $salida.="</td>";
                              
                              $salida.="<td align=\"center\">".substr($USapoyo,0,15).".";
                              $salida.="</td>";
                              $salida.="</tr>";
                         }
                    }
                    else
                    {
                         $dx_apoyos = $this->Select_DX_Apoyos($apoyos[$i]['cargo'], $apoyos[$i]['hc_odontograma_primera_vez_id']);
                         foreach($dx_apoyos as $j => $dx_apoyos)
                         {
                              $USapoyo = $this->NombreUs($dx_apoyos['usuarioid_ppto']);
                              
                              $salida.="<tr class=\"$estilo\">";
                              //$salida.="<td align=\"center\">".$apoyos[$i]['evolucion_id']."";
                              $salida.="<td align=\"center\">".$dx_apoyos['evolucion_ppto']."";
                              $salida.="</td>";
                              
                              //list($fecha1,$hora) = explode(" ",$apoyos[$i]['fecha']);
                              list($fecha1,$hora) = explode(" ",$dx_apoyos['fechareg_ppto']);
                              $salida.="<td align=\"center\">".$fecha1."";
                              $salida.="</td>";
                              $salida.="<td align=\"justify\">".$apoyos[$i]['descripcion']."";
                              $salida.="</td>";
                              $salida.="<td align=\"center\">";
                              $salida.="</td>";
                              $salida.="<td align=\"center\">".$this->cuenta."";
                              $salida.="</td>";
                              
                              $salida.="<td align=\"center\">".$dx_apoyos['cantidad_realizada']."";
                              $salida.="</td>";
     
                              $salida.="<td align=\"center\">Sin Equivalencia.";
                              $salida.="</td>";
                              
                              
                              //$salida.="<td align=\"center\">".$dx_apoyos."";
                              $salida.="<td align=\"center\">".$dx_apoyos['diagnostico_id']."";
                              $salida.="</td>";
                              
                              $salida.="<td align=\"center\">".substr($USapoyo,0,15).".";
                              $salida.="</td>";
                              $salida.="</tr>";
                         }
                    }
               }
               $salida.="</table><br>";
          }
          
          $presup=$this->BuscarPresupuestosOdontograma();
          if($presup<>NULL)
          {
               $salida.="<table border=\"1\" class=\"hc_table_list\" width=\"100%\">";

               $salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $salida.="<td width=\"100%\" align=\"center\" colspan=\"9\">DATOS EVOLUCION</td>";
               $salida.="</tr>";
               
               $salida.="<tr class=\"hc_table_list_title\" align=\"center\">";
               $salida.="<td width=\"8%\">EVOLUCION</td>";
               $salida.="<td width=\"8%\">FECHA</td>";
               $salida.="<td width=\"55%\">DESCRIPCION DEL PROCEDIMIENTO EJECUTADO</td>";
               $salida.="<td width=\"8%\">AUTORIZACION</td>";
               $salida.="<td width=\"8%\">FACTURA</td>";
               $salida.="<td width=\"4%\">CAN.</td>";
               $salida.="<td width=\"8%\">CM / CP</td>";
               $salida.="<td width=\"8%\">DX</td>";                    
               $salida.="<td width=\"10%\">ODONTOLOGO</td>";
               $salida.="</tr>";

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
                    $validados=ValdiarEquivalencias($this->plan,$presup[$i]['cargo']);
                    /*FIN OBTENER EQUIVALENCIAS*/

                    if(!empty($validados))
                    {
                         $dx_ppto = $this->Select_DX_Presupuestos($presup[$i]['cargo'], $presup[$i]['hc_odontograma_primera_vez_id']);
                         foreach($dx_ppto as $k => $dx_ppto)
                         {
                              $cargo_liq[0]=array('tarifario_id'=>$validados[0]['tarifario_id'],'cargo'=>$validados[0]['cargo'],'cantidad'=>$dx_ppto['cantidad_realizada'],'autorizacion_int'=>'','autorizacion_ext'=>'');
     
                              /*LIQUIDAR CUENTA*/
                              $resul=LiquidarCargosCuentaVirtual($cargo_liq,array(),array(),array(),$this->plan,$Cuentas[0],$Cuentas[1],$Cuentas[2],$this->servicio,$this->tipoidpaciente,$this->paciente,'','');
                              /*FIN LIQUIDAR CUENTA*/
     
                              $USppto = $this->NombreUs($dx_ppto['usuarioid_ppto']);
                              
                              $salida.="<tr class=\"$estilo\">";
                              //$salida.="<td align=\"center\">".$presup[$i]['evolucion_id']."";
                              $salida.="<td align=\"center\">".$dx_ppto['evolucion_ppto']."";
                              $salida.="</td>";
                              
                              //list($fecha2,$hora) = explode(" ",$presup[$i]['fecha']);
                              list($fecha2,$hora) = explode(" ",$dx_ppto['fechareg_ppto']);
          
                              $salida.="<td align=\"center\">".$fecha2."";
                              $salida.="</td>";
                              $salida.="<td align=\"justify\">".$presup[$i]['descripcion']."";
                              $salida.="</td>";
                              $salida.="<td align=\"center\">";
                              $salida.="</td>";
                              $salida.="<td align=\"center\">".$this->cuenta."";
                              $salida.="</td>";
                              
                              $salida.="<td align=\"center\">".$dx_ppto['cantidad_realizada']."";
                              $salida.="</td>";
                              
                              $salida.="<td align=\"center\">".$resul['valor_total_paciente']."";
                              $salida.="</td>";
                              
                              //$salida.="<td align=\"center\">".$dx_ppto."";
                              $salida.="<td align=\"center\">".$dx_ppto['diagnostico_id']."";
                              $salida.="</td>";
                              
                              $salida.="<td align=\"center\">".substr($USppto,0,15).".";
                              $salida.="</td>";
                              $salida.="</tr>";
                    	}
                    }
                    else
                    {
                         $dx_ppto = $this->Select_DX_Presupuestos($presup[$i]['cargo'], $presup[$i]['hc_odontograma_primera_vez_id']);
                         foreach($dx_ppto as $k => $dx_ppto)
                         {
                              $USppto = $this->NombreUs($dx_ppto['usuarioid_ppto']);
                              
                              $salida.="<tr class=\"$estilo\">";
                              //$salida.="<td align=\"center\">".$presup[$i]['evolucion_id']."";
                              $salida.="<td align=\"center\">".$dx_ppto['evolucion_ppto']."";
                              $salida.="</td>";
                              
                              //list($fecha2,$hora) = explode(" ",$presup[$i]['fecha']);
                              list($fecha2,$hora) = explode(" ",$dx_ppto['fechareg_ppto']);
                              $salida.="<td align=\"center\">".$fecha2."";
                              $salida.="</td>";
                              $salida.="<td align=\"justify\">".$presup[$i]['descripcion']."";
                              $salida.="</td>";
                              $salida.="<td align=\"center\">";
                              $salida.="</td>";
                              $salida.="<td align=\"center\">".$this->cuenta."";
                              $salida.="</td>";
     
                              $salida.="<td align=\"center\">".$Cantidad."";
                              $salida.="</td>";
                                                  
                              $salida.="<td align=\"center\">Sin Equivalencia.";
                              $salida.="</td>";
                              
                              
                              //$salida.="<td align=\"center\">".$dx_ppto."";
                              $salida.="<td align=\"center\">".$dx_ppto['cantidad_realizada']."";
                              $salida.="</td>";
                              
                              $salida.="<td align=\"center\">".substr($USppto,0,15).".";
                              $salida.="</td>";
                              $salida.="</tr>";
                         }
                    }
               }
               $salida.="</table><br>";
          }
          $salida.="</form>";
		return $salida;
	}



	function SetStyle($campo)
	{
		if ($this->frmError[$campo]||$campo=="MensajeError")
		{
		if ($campo=="MensajeError")
			{
			return ("<tr><td class=\"hc_tderror\" colspan=\"3\">".$this->frmError["MensajeError"]."</td></tr>");
			}
			return ("hc_tderror");
		}
		return ("hc_tdlabel");
	}


	function frmForma()
	{
		$pfj=$this->frmPrefijo;

		if(empty($this->titulo))
		{
			$this->salida  = ThemeAbrirTablaSubModulo('EVOLUCIONES ODONTOLOGICAS');
		}
		else
		{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}

		IncludeLib("funciones_facturacion");
		IncludeLib("tarifario_cargos");

		$RUTA = $_ROOT ."cache/evolucionodontologica".$this->cuenta.".pdf";
		$mostrar = "<script>\n";
		$mostrar.= "	var rem=\"\";\n";
		$mostrar.= "  	function abreVentana()\n";
		$mostrar.= "  	{\n";
		$mostrar.= "    	var nombre = \"\"\n";
		$mostrar.= "    	var url2 = \"\"\n";
		$mostrar.= "    	var str = \"\"\n";
		$mostrar.= "    	var alto = screen.height\n";
		$mostrar.= "    	var ancho = screen.width\n";
		$mostrar.= "    	var nombre = \"REPORTE\";\n";
		$mostrar.= "    	var str = \"ancho,alto,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
		$mostrar.= "    	var url2 = '$RUTA';\n";
		$mostrar.= "    	rem = window.open(url2, nombre, str);\n";
		$mostrar.= "    };\n";      
		$mostrar.="</script>\n";
		$this->salida.="$mostrar";
		$infoEvolucion = $this->Get_Evoluciones_Odontologicas();
		$Cuentas = $this->BuscarCuentas($this->cuenta);
          $usuario = $this->NombreUs($this->usuario_id);
		
          $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar', 'inscripcion'.$pfj=>$programas));
          $this->salida.="<form name=\"evoluciones_odontologicas$pfj\" action=\"$accion\" method=\"post\">";
          $this->salida.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"hc_table_list\">";
          $this->salida.="<tr class=\"hc_table_list_title\">";
          $this->salida.="<td width=\"100%\">CONSOLIDADO EVOLUCIONES ODONTOLOGICAS</td>";
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
          $this->salida.="<td width=\"100%\" align=\"center\">DATOS EVOLUCION</td>";
          $this->salida.="</tr>";
          foreach($infoEvolucion as $k => $v)
          {
               $this->salida.="<tr>";
               $this->salida.="<td width=\"100%\">";
               $this->salida.="<table border=\"0\" class=\"hc_table_list\" width=\"100%\">";

               $this->salida.="<tr class=\"hc_table_list_title\" align=\"center\">";
               $this->salida.="<td width=\"8%\">EVOLUCION</td>";
               $this->salida.="<td width=\"8%\">FECHA</td>";
               $this->salida.="<td width=\"5%\">DIENTE</td>";
               $this->salida.="<td width=\"10%\">SUPERFICIE</td>";
               $this->salida.="<td width=\"40%\">DESCRIPCION DEL PROCEDIMIENTO EJECUTADO</td>";
               $this->salida.="<td width=\"10%\">AUTORIZACION</td>";
               $this->salida.="<td width=\"10%\">FACTURA</td>";
               $this->salida.="<td width=\"10%\">CM / CP</td>";
               $this->salida.="<td width=\"10%\">DX</td>";
               $this->salida.="<td width=\"10%\">ODONTOLOGO</td>";
               $this->salida.="</tr>";

               for($j=0; $j<sizeof($v[evo]); $j++)
               {
                    $usuario1 = $this->NombreUs($v[evo][$j][11]);
                    
                    list($fecha,$hora) = explode(" ",$v[evo][$j][8]);

                    /*OBTENER EQUIVALENCIAS*/
                    $validados=ValdiarEquivalencias($this->plan,$v[evo][$j][5]);
                    /*FIN OBTENER EQUIVALENCIAS*/

                    if(!empty($validados))
                    {
                         $cargo_liq[0]=array('tarifario_id'=>$validados[0]['tarifario_id'],'cargo'=>$validados[0]['cargo'],'cantidad'=>1,'autorizacion_int'=>'','autorizacion_ext'=>'');

                         /*LIQUIDAR CUENTA*/
                         $resul=LiquidarCargosCuentaVirtual($cargo_liq,array(),array(),array(),$this->plan,$Cuentas[0],$Cuentas[1],$Cuentas[2],$this->servicio,$this->tipoidpaciente,$this->paciente,'','');
                         /*FIN LIQUIDAR CUENTA*/

                         $this->salida.="<tr class=\"modulo_list_claro\">";
                         $this->salida.="<td width=\"8%\" align=\"center\">".$v[evo][$j][9]."</td>";
                         
                         $this->salida.="<td width=\"8%\" align=\"center\">".$fecha."</td>";
                         
                         $this->salida.="<td width=\"5%\" align=\"center\">".$v[evo][$j][0]."</td>";
                         $this->salida.="<td width=\"10%\" align=\"center\">".$v[evo][$j][2]."</td>";
                         $this->salida.="<td width=\"60%\" align=\"justify\">".$v[evo][$j][6]." - ".$v[evo][$j][3]." -";
                         $this->salida.="<label class=\"label_error\">"."  (".$v[evo][$j][4].").</label></td>";
                         $this->salida.="<td width=\"10%\" align=\"center\">&nbsp;</td>";
                         $this->salida.="<td width=\"10%\" align=\"center\">".$this->cuenta."</td>";
                         $this->salida.="<td width=\"10%\" align=\"center\">".$resul['valor_total_paciente']."</td>";
                         
                         $this->salida.="<td width=\"10%\" align=\"center\">".$v[evo][$j][7]."</td>";
                                                       
                         $this->salida.="<td width=\"10%\" align=\"center\">".substr($usuario1,0,15).".</td>";
                         $this->salida.="</tr>";
                    }
                    else
                    {
                         $this->salida.="<tr class=\"modulo_list_claro\">";
                         $this->salida.="<td width=\"8%\" align=\"center\">".$v[evo][$j][9]."</td>";
                         
                         $this->salida.="<td width=\"8%\" align=\"center\">".$fecha."</td>";
                         
                         $this->salida.="<td width=\"5%\" align=\"center\">".$v[evo][$j][0]."</td>";
                         $this->salida.="<td width=\"10%\" align=\"center\">".$v[evo][$j][2]."</td>";
                         $this->salida.="<td width=\"60%\" align=\"justify\">".$v[evo][$j][6]." - ".$v[evo][$j][3]." -";
                         $this->salida.="<label class=\"label_error\">"."  (".$v[evo][$j][4].").</label></td>";
                         $this->salida.="<td width=\"10%\" align=\"center\">&nbsp;</td>";
                         $this->salida.="<td width=\"10%\" align=\"center\">".$this->cuenta."</td>";
                         $this->salida.="<td width=\"10%\" align=\"center\">Sin Equivalencia.</td>";
                         
                         $this->salida.="<td width=\"10%\" align=\"center\">".$v[evo][$j][7]."</td>";
                         
                         $this->salida.="<td width=\"10%\" align=\"center\">".substr($usuario1,0,15).".</td>";
                         $this->salida.="</tr>";
                    }
               }
               $this->salida.="</table>";
               $this->salida.="</td>";
               $this->salida.="</tr>";
          }
          $this->salida.="</table><br>";

          $apoyos=$this->BuscarApoyosOdontograma();
          if($apoyos<>NULL)
          {
               $this->salida.="<table border=\"0\" class=\"hc_table_list\" width=\"100%\">";

               $this->salida.="<tr class=\"hc_table_list_title\" align=\"center\">";
               $this->salida.="<td width=\"8%\">EVOLUCION</td>";
               $this->salida.="<td width=\"8%\">FECHA</td>";
               $this->salida.="<td width=\"55%\">DESCRIPCION DEL PROCEDIMIENTO EJECUTADO</td>";
               $this->salida.="<td width=\"8%\">AUTORIZACION</td>";
               $this->salida.="<td width=\"8%\">FACTURA</td>";
               $this->salida.="<td width=\"4%\">CAN.</td>";
               $this->salida.="<td width=\"8%\">CM / CP</td>";
               $this->salida.="<td width=\"8%\">DX</td>";                    
               $this->salida.="<td width=\"10%\">ODONTOLOGO</td>";
               $this->salida.="</tr>";
               
               for($i=0;$i<sizeof($apoyos);$i++)
               {
                    $Cantidad = ($apoyos[$i]['cantidad'] - $apoyos[$i]['cantidad_pend']);
                    
                    if( $i % 2)
                    {
                         $estilo='modulo_list_claro';
                    }
                    else
                    {
                         $estilo='modulo_list_oscuro';
                    }
                    
                    /*OBTENER EQUIVALENCIAS*/
                    $validados=ValdiarEquivalencias($this->plan,$apoyos[$i]['cargo']);
                    /*FIN OBTENER EQUIVALENCIAS*/

                    if(!empty($validados))
                    {
                         $dx_apoyos = $this->Select_DX_Apoyos($apoyos[$i]['cargo'], $apoyos[$i]['hc_odontograma_primera_vez_id']);
                    	foreach($dx_apoyos as $j => $dx_apoyos)
                         {
                              $cargo_liq[0]=array('tarifario_id'=>$validados[0]['tarifario_id'],'cargo'=>$validados[0]['cargo'],'cantidad'=>$dx_apoyos['cantidad_realizada'],'autorizacion_int'=>'','autorizacion_ext'=>'');
     
                              /*LIQUIDAR CUENTA*/
                              $resul=LiquidarCargosCuentaVirtual($cargo_liq,array(),array(),array(),$this->plan,$Cuentas[0],$Cuentas[1],$Cuentas[2],$this->servicio,$this->tipoidpaciente,$this->paciente,'','');
                              /*FIN LIQUIDAR CUENTA*/
                              
     
                              $USapoyo = $this->NombreUs($dx_apoyos['usuarioid_ppto']);
                              
                              $this->salida.="<tr class=\"$estilo\">";
                              //$this->salida.="<td align=\"center\">".$apoyos[$i]['evolucion_id']."";
                              $this->salida.="<td align=\"center\">".$dx_apoyos['evolucion_ppto']."";
                              $this->salida.="</td>";
                              
                              //list($fecha1,$hora) = explode(" ",$apoyos[$i]['fecha']);
                              list($fecha1,$hora) = explode(" ",$dx_apoyos['fechareg_ppto']);
          
                              $this->salida.="<td align=\"center\">".$fecha1."";
                              $this->salida.="</td>";
                              $this->salida.="<td align=\"justify\">".$apoyos[$i]['descripcion']."";
                              $this->salida.="</td>";
                              $this->salida.="<td align=\"center\">";
                              $this->salida.="</td>";
                              $this->salida.="<td align=\"center\">".$this->cuenta."";
                              $this->salida.="</td>";
                              
                              $this->salida.="<td align=\"center\">".$dx_apoyos['cantidad_realizada']."";
                              $this->salida.="</td>";
     
                              $this->salida.="<td align=\"center\">".$resul['valor_total_paciente']."";
                              $this->salida.="</td>";
                              
                              //$this->salida.="<td align=\"center\">".$dx_apoyos."";
                              $this->salida.="<td align=\"center\">".$dx_apoyos['diagnostico_id']."";
                              $this->salida.="</td>";
                              
                              $this->salida.="<td align=\"center\">".substr($USapoyo,0,15).".";
                              $this->salida.="</td>";
                              $this->salida.="</tr>";
                         }
                    }
                    else
                    {
                         $dx_apoyos = $this->Select_DX_Apoyos($apoyos[$i]['cargo'], $apoyos[$i]['hc_odontograma_primera_vez_id']);
                         foreach($dx_apoyos as $j => $dx_apoyos)
                         {
                              $USapoyo = $this->NombreUs($dx_apoyos['usuarioid_ppto']);
                              
                              $this->salida.="<tr class=\"$estilo\">";
                              //$this->salida.="<td align=\"center\">".$apoyos[$i]['evolucion_id']."";
                              $this->salida.="<td align=\"center\">".$dx_apoyos['evolucion_ppto']."";
                              $this->salida.="</td>";
                              
                              //list($fecha1,$hora) = explode(" ",$apoyos[$i]['fecha']);
                              list($fecha1,$hora) = explode(" ",$dx_apoyos['fechareg_ppto']);
                              $this->salida.="<td align=\"center\">".$fecha1."";
                              $this->salida.="</td>";
                              $this->salida.="<td align=\"justify\">".$apoyos[$i]['descripcion']."";
                              $this->salida.="</td>";
                              $this->salida.="<td align=\"center\">";
                              $this->salida.="</td>";
                              $this->salida.="<td align=\"center\">".$this->cuenta."";
                              $this->salida.="</td>";
                              
                              $this->salida.="<td align=\"center\">".$dx_apoyos['cantidad_realizada']."";
                              $this->salida.="</td>";
     
                              $this->salida.="<td align=\"center\">Sin Equivalencia.";
                              $this->salida.="</td>";
                              
                              
                              //$this->salida.="<td align=\"center\">".$dx_apoyos."";
                              $this->salida.="<td align=\"center\">".$dx_apoyos['diagnostico_id']."";
                              $this->salida.="</td>";
                              
                              $this->salida.="<td align=\"center\">".substr($USapoyo,0,15).".";
                              $this->salida.="</td>";
                              $this->salida.="</tr>";
                         }
                    }
               }
               $this->salida.="</table><br>";
          }
          
          $presup=$this->BuscarPresupuestosOdontograma();
          if($presup<>NULL)
          {
               $this->salida.="<table border=\"0\" class=\"hc_table_list\" width=\"100%\">";

               $this->salida.="<tr class=\"hc_table_list_title\" align=\"center\">";
               $this->salida.="<td width=\"8%\">EVOLUCION</td>";
               $this->salida.="<td width=\"8%\">FECHA</td>";
               $this->salida.="<td width=\"55%\">DESCRIPCION DEL PROCEDIMIENTO EJECUTADO</td>";
               $this->salida.="<td width=\"8%\">AUTORIZACION</td>";
               $this->salida.="<td width=\"8%\">FACTURA</td>";
               $this->salida.="<td width=\"4%\">CAN.</td>";
               $this->salida.="<td width=\"8%\">CM / CP</td>";
               $this->salida.="<td width=\"8%\">DX</td>";                    
               $this->salida.="<td width=\"10%\">ODONTOLOGO</td>";
               $this->salida.="</tr>";

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
                    $validados=ValdiarEquivalencias($this->plan,$presup[$i]['cargo']);
                    /*FIN OBTENER EQUIVALENCIAS*/

                    if(!empty($validados))
                    {
                         $dx_ppto = $this->Select_DX_Presupuestos($presup[$i]['cargo'], $presup[$i]['hc_odontograma_primera_vez_id'], $presup[$i]['hc_odontogramas_primera_vez_presupuesto_id']);
                         foreach($dx_ppto as $k => $dx_ppto)
                         {
                              $cargo_liq[0]=array('tarifario_id'=>$validados[0]['tarifario_id'],'cargo'=>$validados[0]['cargo'],'cantidad'=>$dx_ppto['cantidad_realizada'],'autorizacion_int'=>'','autorizacion_ext'=>'');
     
                              /*LIQUIDAR CUENTA*/
                              $resul=LiquidarCargosCuentaVirtual($cargo_liq,array(),array(),array(),$this->plan,$Cuentas[0],$Cuentas[1],$Cuentas[2],$this->servicio,$this->tipoidpaciente,$this->paciente,'','');
                              /*FIN LIQUIDAR CUENTA*/
     
                              $USppto = $this->NombreUs($dx_ppto['usuarioid_ppto']);
                              
                              $this->salida.="<tr class=\"$estilo\">";
                              //$this->salida.="<td align=\"center\">".$presup[$i]['evolucion_id']."";
                              $this->salida.="<td align=\"center\">".$dx_ppto['evolucion_ppto']."";
                              $this->salida.="</td>";
                              
                              //list($fecha2,$hora) = explode(" ",$presup[$i]['fecha']);
                              list($fecha2,$hora) = explode(" ",$dx_ppto['fechareg_ppto']);
          
                              $this->salida.="<td align=\"center\">".$fecha2."";
                              $this->salida.="</td>";
                              $this->salida.="<td align=\"justify\">".$presup[$i]['descripcion']."";
                              $this->salida.="</td>";
                              $this->salida.="<td align=\"center\">";
                              $this->salida.="</td>";
                              $this->salida.="<td align=\"center\">".$this->cuenta."";
                              $this->salida.="</td>";
                              $this->salida.="<td align=\"center\">".$dx_ppto['cantidad_realizada']."";
                              $this->salida.="</td>";
                              
                              $this->salida.="<td align=\"center\">".$resul['valor_total_paciente']."";
                              $this->salida.="</td>";
                              
                              //$this->salida.="<td align=\"center\">".$dx_ppto."";
                              $this->salida.="<td align=\"center\">".$dx_ppto['diagnostico_id']."";
                              $this->salida.="</td>";
                              
                              $this->salida.="<td align=\"center\">".substr($USppto,0,15).".";
                              $this->salida.="</td>";
                              $this->salida.="</tr>";
                    	}
                    }
                    else
                    {
                         $dx_ppto = $this->Select_DX_Presupuestos($presup[$i]['cargo'], $presup[$i]['hc_odontograma_primera_vez_id']);
                         foreach($dx_ppto as $k => $dx_ppto)
                         {
                              $USppto = $this->NombreUs($dx_ppto['usuarioid_ppto']);
                              
                              $this->salida.="<tr class=\"$estilo\">";
                              //$this->salida.="<td align=\"center\">".$presup[$i]['evolucion_id']."";
                              $this->salida.="<td align=\"center\">".$dx_ppto['evolucion_ppto']."";
                              $this->salida.="</td>";
                              
                              //list($fecha2,$hora) = explode(" ",$presup[$i]['fecha']);
                              list($fecha2,$hora) = explode(" ",$dx_ppto['fechareg_ppto']);
                              $this->salida.="<td align=\"center\">".$fecha2."";
                              $this->salida.="</td>";
                              $this->salida.="<td align=\"justify\">".$presup[$i]['descripcion']."";
                              $this->salida.="</td>";
                              $this->salida.="<td align=\"center\">";
                              $this->salida.="</td>";
                              $this->salida.="<td align=\"center\">".$this->cuenta."";
                              $this->salida.="</td>";
     
                              $this->salida.="<td align=\"center\">".$Cantidad."";
                              $this->salida.="</td>";
                                                  
                              $this->salida.="<td align=\"center\">Sin Equivalencia.";
                              $this->salida.="</td>";
                              
                              
                              //$this->salida.="<td align=\"center\">".$dx_ppto."";
                              $this->salida.="<td align=\"center\">".$dx_ppto['cantidad_realizada']."";
                              $this->salida.="</td>";
                              
                              $this->salida.="<td align=\"center\">".substr($USppto,0,15).".";
                              $this->salida.="</td>";
                              $this->salida.="</tr>";
                         }
                    }
               }
               $this->salida.="</table><br>";
          }
/*
          //////////////////////////
          // SISTEMA DE IMPRESION //
          /////////////////////////
          $reporte= new GetReports();
          $mostrar=$reporte->GetJavaReport('system','reportes','evolucion_odontologica_html',array('ingreso'=>$this->ingreso, 'evolucion'=>$this->evolucion, 'cuenta'=>$this->cuenta, 'usuario_id'=>$this->usuario_id, 'plan'=>$this->plan, 'servicio'=>$this->servicio, 'tipoidpaciente'=>$this->tipoidpaciente, 'paciente'=>$this->paciente),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
          $nombre_funcion=$reporte->GetJavaFunction();
          $this->salida .=$mostrar;

          $this->salida.="<br><br><center>";
          $this->salida.="<label class=\"label_mark\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp;&nbsp;IMPRIMIR REPORTE DE EVOLUCION</a>";
          $this->salida.="</center>";

          //////////////////////////
          // SISTEMA DE IMPRESION //
          /////////////////////////
*/
					IncludeLib("reportes/evolucionodontologica");
					CrearReporteEvolucionOdontologica($this->ingreso,$this->evolucion, $this->cuenta, $this->usuario_id, $this->plan, $this->servicio, $this->tipoidpaciente, $this->paciente);
          $this->salida.="<br><br><center>";
          $this->salida.="<label class=\"label_mark\"><a href=\"javascript:abreVentana()\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp;&nbsp;IMPRIMIR REPORTE DE EVOLUCION</a>";
          $this->salida.="</center>";

          $this->salida.="</form>";
		$this->salida.= ThemeCerrarTablaSubModulo();
		return true;
	}

}

?>
