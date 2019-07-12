<?php
  /**
  * $Id: Solicitud_Convenio.report.php,v 1.1.1.1 2009/09/11 20:36:58 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  *
  */
  class Solicitud_Convenio_report
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

  	function Solicitud_Convenio_report($datos=array())
  	{
      //var_dump($datos);
      $this->datos=$datos;
      return true;
  	}
    // el cual entra en vigente hasta {$VIGENCIA_CARNET}
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
    function capitalize_words($string)
    {
      $string=strtolower($string);
      $string = explode(' ', $string);
      foreach($string as $key => $value)
      {
        $string[$key] = ucwords($string[$key]);
      }
      return implode(' ', $string);
    }
    /**
  	*
  	*/
  	function CrearReporte()
  	{
      $smarty=getSmarty(dirname (__FILE__));
      $smarty->compile_check = true;
            
      if(!empty($this->datos))
      { 
        if($this->datos['datos']['cotizante']['eps_tipo_afiliado_id']=="C")
        {
          $smarty->assign("DIRIGIDO",$this->datos['datos']['carta']['dirigido']);
          $smarty->assign("NOMBRE_DESTINATARIO",strtoupper($this->datos['datos']['carta']['destinatario']));
          $smarty->assign("CARGO",$this->capitalize_words($this->datos['datos']['carta']['cargo']));
          $smarty->assign("ENTIDAD",$this->datos['datos']['carta']['entidad']);
          $smarty->assign("CIUDAD",$this->capitalize_words($this->datos['datos']['carta']['ciudades']));

          list($codigo,$descripcion) = explode("-",$this->datos['datos']['carta']['depto']);
          $smarty->assign("DEPTO","(".$this->capitalize_words($descripcion).")");

          if($this->datos['datos']['cotizante']['tipo_sexo_id']==="F")
          {
            $smarty->assign("SEXO","a la Se&#241;ora");
          }
          elseif($this->datos['datos']['cotizante']['tipo_sexo_id']==="M")
          {
            $smarty->assign("SEXO","al Se&#241;or");
          }
          list($n1,$n2,$n3,$n4) = explode(" ",$this->datos['datos']['cotizante']['nombre_afiliado']);
          $smarty->assign("NOMBRE",$n3." ".$n4." ".$n1." ".$n2);

          if($this->datos['datos']['cotizante']['afiliado_tipo_id']==="CE")
          {
            $smarty->assign("TIPO_ID","Cedula de extranjeria");
          }
          elseif($this->datos['datos']['cotizante']['afiliado_tipo_id']==="CC")
          {
            $smarty->assign("TIPO_ID","Cedula de ciudadania");
          }
          elseif($this->datos['datos']['cotizante']['afiliado_tipo_id']==="TI")
          {
            $smarty->assign("TIPO_ID","Tarjeta de identidad");
          }
          elseif($this->datos['datos']['cotizante']['afiliado_tipo_id']==="PA")
          {
            $smarty->assign("TIPO_ID","Pasaporte");
          }

          $smarty->assign("TERCERO_ID",$this->datos['datos']['cotizante']['afiliado_id']);
          $smarty->assign("TIPO_AFILIACION",$this->capitalize_words($this->datos['datos']['cotizante']['descripcion_eps_tipo_afiliado']));
          $smarty->assign("CONSULTA_ESPECIALISTA",$this->datos['datos']['carta']['consulta_especialista']);
          $smarty->assign("HONORARIOS",$this->datos['datos']['carta']['honorarios_med']);
          $smarty->assign("GASTOS_H",$this->datos['datos']['carta']['gasto_hosp']);
          $smarty->assign("MEDICAMENTOS",$this->datos['datos']['carta']['medicamentos_genricos']);
          $smarty->assign("RAYOS",$this->datos['datos']['carta']['rayos']);
          $smarty->assign("EXAMENES",$this->datos['datos']['carta']['examenes']);
          $smarty->assign("DATOS_COTIZANTE","");
          $smarty->assign("FIRMA",strtoupper($this->datos['datos']['carta']['firma']));

          //$smarty->assign("Ciudad",$this->datos['datos']['nombre_afiliado']);
          // $smarty->assign("dia_numero_carne_vence",$this->datos['datos']['nombre_afiliado']);
          // $smarty->assign("mes_carne_vence",$this->datos['datos']['nombre_afiliado']);
          // $smarty->assign("ano_carne_vence",$this->datos['datos']['nombre_afiliado']);
          list($dia,$mes,$ano) = explode("-",$this->datos['datos']['carta']['fecha_ini']);

          $fecha_inicial=$dia." de ".GetMes($mes)." de ".$ano;
          $smarty->assign("FECHA1",$fecha_inicial);
          list($dia,$mes,$ano) = explode("-",$this->datos['datos']['carta']['fecha_fin']);
          $fecha_final=$dia." de ".GetMes($mes)." de ".$ano;
          $smarty->assign("FECHA2",$fecha_final);

          $smarty->assign("DIA_FECHA_HOY",date("d"));
          $smarty->assign("MES_HOY",GetMes(date("m")));
          $smarty->assign("ANO_FECHA_HOY",date("Y"));
          $salida=$smarty->fetch('convenio.html.tpl');
        }
        else
        {
          $this->datos['datos'] = $this->datos['datos']['cotizante'];
          
          if($this->datos['datos']['eps_tipo_afiliado_id']=="B")
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
            $smarty->assign("Num_Documento",$this->datos['datos']['afiliado_id']);
            $smarty->assign("Estamento_que_Afilia",$this->datos['datos']['DATOS_BENEFICIARIO']['descripcion_estamento']);
            $smarty->assign("Sexo_que_Afilia",$this->datos['datos']['Sexo_que_Afilia']);
            $smarty->assign("Nombre_que_Afilia",$this->datos['datos']['DATOS_BENEFICIARIO']['nombre_cotizante']);
            $smarty->assign("tipo_de_documento_que_afilia",$this->datos['datos']['DATOS_BENEFICIARIO']['cotizante_tipo_id']);
            $smarty->assign("Num_idetificacion_que_Afilia",$this->datos['datos']['DATOS_BENEFICIARIO']['cotizante_id']);
            //$smarty->assign("dia_numero_carne_vence",$this->datos['datos']['afiliado_id']);
            //$smarty->assign("mes_des_carne_vence",$this->datos['datos']['afiliado_id']);
            //$smarty->assign("ano_carne_vence",$this->datos['datos']['afiliado_id']);
            if($this->datos['datos']['tipo_sexo_id']=="F")
              $smarty->assign("Sexo_afiliado","afiliada");
            elseif($this->datos['datos']['tipo_sexo_id']=="M")
              $smarty->assign("Sexo_afiliado","afiliado");
            
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
        }
      }
      unset($smarty);
      return $salida;
  	}
  }
?>