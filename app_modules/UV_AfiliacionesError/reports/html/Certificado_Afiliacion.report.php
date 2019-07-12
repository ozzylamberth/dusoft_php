<?php

/**
 * $Id: Certificado_Afiliacion.report.php,v 1.1.1.1 2009/09/11 20:36:58 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class Certificado_Afiliacion_report
{
	var $datos;

	//PARAMETROS PARA LA CONFIGURACION DEL REPORTE
	var $title       = '';
	var $author      = '';
	var $sizepage    = 'leter';
	var $Orientation = '';
	var $grayScale   = false;
	var $headers     = array();
	var $footers     = array();

	function Certificado_Afiliacion_report($datos=array())
	{
	$this->datos=$datos;
			return true;
	}

	function GetMembrete()
	{
		$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'',
																'subtitulo'=>'',
																'logo'=>'',
																'align'=>'center'));
		return $Membrete;
	}

	/**
	*
	*/
	function CrearReporte()
	{
			$smarty=getSmarty(dirname (__FILE__));
			$smarty->compile_check = true;			
			//var_dump($this->datos);
            
			if(!empty($this->datos))
			{
                if($this->datos['datos']['eps_tipo_afiliado_id']=="C")
                {
                  //  if($this->datos['datos']['DATOS_COTIZANTE']['estamento_id']=="J" || $this->datos['datos']['DATOS_COTIZANTE']['estamento_id']=="D" || $this->datos['datos']['DATOS_COTIZANTE']['estamento_id']=="E" || $this->datos['datos']['DATOS_COTIZANTE']['estamento_id']=="O")
                   // {
                            if($this->datos['datos']['tipo_sexo_id']==="F")
                            {
                                $smarty->assign("Sexo","la Sra.");
                            }
                            elseif($this->datos['datos']['tipo_sexo_id']==="M")
                            {
                                $smarty->assign("Sexo","el Sr.");
                            }
                            $smarty->assign("NombreUsuario",$this->datos['datos']['nombre_afiliado']);
                            $cedula=$this->datos['datos']['afiliado_tipo_id']."-".$this->datos['datos']['afiliado_id'];
                            $smarty->assign("Cedula",$cedula);
                            $smarty->assign("Estamento_descripcion",$this->datos['datos']['DATOS_COTIZANTE']['descripcion_estamento']);
                            //$smarty->assign("Ciudad",$this->datos['datos']['nombre_afiliado']);
                        // $smarty->assign("dia_numero_carne_vence",$this->datos['datos']['nombre_afiliado']);
                        // $smarty->assign("mes_carne_vence",$this->datos['datos']['nombre_afiliado']);
                        // $smarty->assign("ano_carne_vence",$this->datos['datos']['nombre_afiliado']);
                            list($ano,$mes,$dia) = explode("-",$this->datos['datos']['fecha_afiliacion']);
                            $smarty->assign("dia_numero_fecha_afi",$dia);
                            $smarty->assign("mes_fecha_afi",GetMes($mes));
                            $smarty->assign("ano_fecha_afi",$ano);
                            $smarty->assign("Diaenletras",ValorEnLetras(date("d"),2,' ',' '));
                            $smarty->assign("Diaennumeros",date("d"));
                            $smarty->assign("mes_act",GetMes(date("m")));
                            $smarty->assign("Ano_enletras",ValorEnLetras(date("Y"),2,' ',' '));
                            $smarty->assign("Ano_num",date("Y"));
                            $salida=$smarty->fetch('empleados.html.tpl');
                            
                    //}

                }
                elseif($this->datos['datos']['eps_tipo_afiliado_id']=="B")
                {
                    if($this->datos['datos']['tipo_sexo_id']==="F")
                    {
                    $smarty->assign("Sexo","la Sra.");
                    }
                    elseif($this->datos['datos']['tipo_sexo_id']==="M")
                    {
                        $smarty->assign("Sexo","el Sr.");
                    }
                    $smarty->assign("NombreUsuario",$this->datos['datos']['nombre_afiliado']);
                    $smarty->assign("Tipo_Documento",$this->datos['datos']['afiliado_tipo_id']);
                    $smarty->assign("Estamento_que_Afilia",$this->datos['datos']['Estamento_que_Afilia']);
                    $smarty->assign("Sexo_que_Afilia",$this->datos['datos']['Sexo_que_Afilia']);
                    $smarty->assign("Nombre_que_Afilia",$this->datos['datos']['afiliado_id']);
                    $smarty->assign("tipo_de_documento_que_afilia",$this->datos['datos']['afiliado_id']);
                    $smarty->assign("Num_idetificacion_que_Afilia",$this->datos['datos']['afiliado_id']);
                    $smarty->assign("dia_numero_carne_vence",$this->datos['datos']['afiliado_id']);
                    $smarty->assign("mes_des_carne_vence",$this->datos['datos']['afiliado_id']);
                    $smarty->assign("ano_carne_vence",$this->datos['datos']['afiliado_id']);
                    $smarty->assign("dia_numero_fecha_afi",$this->datos['datos']['afiliado_id']);
                    $smarty->assign("mes_fecha_afi",$this->datos['datos']['afiliado_id']);
                    $smarty->assign("ano_fecha_afi",$this->datos['datos']['afiliado_id']);
                    $smarty->assign("Diaenletras",$this->datos['datos']['afiliado_id']);
                    $smarty->assign("dia_numero_fecha_afi",$this->datos['datos']['afiliado_id']);
                    $smarty->assign("dia_numero_fecha_afi",$this->datos['datos']['afiliado_id']);
                    $smarty->assign("dia_numero_fecha_afi",$this->datos['datos']['afiliado_id']);
                    $smarty->assign("dia_numero_fecha_afi",$this->datos['datos']['afiliado_id']);
                    
                    //$smarty->assign("Ciudad",$this->datos['datos']['nombre_afiliado']);
                // $smarty->assign("dia_numero_carne_vence",$this->datos['datos']['nombre_afiliado']);
                // $smarty->assign("mes_carne_vence",$this->datos['datos']['nombre_afiliado']);
                // $smarty->assign("ano_carne_vence",$this->datos['datos']['nombre_afiliado']);
                    list($ano,$mes,$dia) = explode("-",$this->datos['datos']['fecha_afiliacion']);
                    $smarty->assign("dia_numero_fecha_afi",$dia);
                    $smarty->assign("mes_fecha_afi",GetMes($mes));
                    $smarty->assign("ano_fecha_afi",$ano);
                    $smarty->assign("Diaenletras",ValorEnLetras(date("d"),2,' ',' '));
                    $smarty->assign("Diaennumeros",date("d"));
                    $smarty->assign("mes_act",GetMes(date("m")));
                    $smarty->assign("Ano_enletras",ValorEnLetras(date("Y"),2,' ',' '));
                    $smarty->assign("Ano_num",date("Y"));
                    $salida=$smarty->fetch('beneficiario.html.tpl');

                
                }


// Sexo_que_Afilia
// Nombre_que_Afilia
// tipo_de_documento_que_afilia
// Num_idetificacion_que_Afilia
// dia_numero_carne_vence
// mes_des_carne_vence
// ano_carne_vence
// dia_numero_fecha_afi
// mes_fecha_afi
// ano_fecha_afi
// Diaenletras
// Diaennumeros
// mes_act
// Ano_enletras
// Ano_num

         
//                 elseif($this->datos['datos']['estamento_id']=="J")
//                 {
// 
//                 }

                
            }

            
            unset($smarty);
            return $salida;
	}


}
?>