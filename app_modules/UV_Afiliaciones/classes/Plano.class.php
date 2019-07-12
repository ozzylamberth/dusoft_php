<?php
  /**
  * @package SINERGIAS
  * @version $Id: Plano.class.php,v 1.6 2009/10/05 18:27:11 hugo Exp $
  * @copyright (C) 2010 Cosmitet LTDA 
  * @author 
  */
  /**
  * Clase : 

  *
  * @package SINERGIAS  * @version $Revision: 1.6 $
  * @copyright (C) 2010 Cosmitet LTDA 
  * @author 
  */
  IncludeClass("Afiliaciones", "", "app","UV_Afiliaciones");
  class Plano extends Afiliaciones
  {
    /**
    * Contructor de la clase
    */
    function Plano(){}
    
    
    /**
    * Funcion donde se obtiene la informacion de los ailiados, para
    * hacer la creacion de un archivo plano
    *
    * @param array $datos vector con los parametros de busqueda
    *
    * @return array 
    */
    function ObtenerPlano($datos)
    {
       $sql  = "SELECT ";
      $sql  .= " afi.afiliado_id,";
      $sql  .= " afi.afiliado_tipo_id,";
      $sql  .= " afi.rango_afiliado_atencion as ANTES,";
      $sql  .= " pla.plan_descripcion        AS plan_descripcion,";
      $sql  .= " ben.cotizante_tipo_id,";
      $sql  .= " ben.cotizante_id,";
      $sql  .= " datos.primer_apellido,";
      $sql  .= " datos.segundo_apellido,";
      $sql  .= " datos.primer_nombre,";
      $sql  .= " datos.segundo_nombre,";
      $sql  .= " datos.fecha_nacimiento,";
      $sql  .= " datos.tipo_sexo_id,";
      $sql  .= " afi.eps_tipo_afiliado_id,";
      $sql  .= " cot.tipo_estado_civil_id,";
      $sql  .= " ben.parentesco_id,";
      $sql  .= " datos.direccion_residencia,";
      $sql  .= " datos.telefono_residencia,";
      $sql  .= " ate.eps_punto_atencion_id,";
      $sql  .= " ate.eps_punto_atencion_nombre,";
      $sql  .= " afi.fecha_afiliacion,";
      $sql  .= " afi.fecha_vencimiento,";
      $sql  .= " afi.estado_afiliado_id,";
      $sql  .= " descripcion_eps_tipo_afiliado, ";
      $sql  .= " est.descripcion_estamento";     
      
      $sql  .= " FROM";
      $sql  .= " eps_afiliados afi";
      $sql  .= " JOIN eps_afiliados_datos datos ON  datos.afiliado_id = afi.afiliado_id AND datos.afiliado_tipo_id = afi.afiliado_tipo_id";
      $sql  .= " JOIN eps_puntos_atencion ate ON  ate.eps_punto_atencion_id = afi.eps_punto_atencion_id";
      $sql  .= " LEFT JOIN eps_afiliados_cotizantes cot ON afi.eps_afiliacion_id = cot.eps_afiliacion_id AND afi.afiliado_tipo_id = cot.afiliado_tipo_id AND afi.afiliado_id = cot.afiliado_id";
      $sql  .= " LEFT JOIN eps_afiliados_beneficiarios ben ON afi.eps_afiliacion_id = ben.eps_afiliacion_id AND afi.afiliado_tipo_id = ben.afiliado_tipo_id AND afi.afiliado_id = ben.afiliado_id";
      $sql  .= " JOIN      planes    pla                   ON  afi.plan_atencion=pla.plan_id";
      $sql  .= " JOIN  eps_tipos_afiliados         eta     ON  eta.eps_tipo_afiliado_id=afi.eps_tipo_afiliado_id ";
	  $sql  .= " LEFT  join eps_estamentos         est     ON  cot.estamento_id=est.estamento_id";
      $sql  .= " WHERE";
	  
     // $sql  = " CAST (afi.fecha_afiliacion AS DATE)  BETWEEN '1996-01-01' AND CURRENT_DATE ";
	  if($datos['fecha_inicio'])
      {
			$sql .= " CAST (afi.fecha_afiliacion AS DATE)  BETWEEN  '".$this->DividirFecha($datos['fecha_inicio'])."' ";
	  }else{
			$sql .= " CAST (afi.fecha_afiliacion AS DATE)  BETWEEN '1996-01-01'  ";
	  }
	  if($datos['fecha_final'])
      {
		 $sql .= "  AND  '".$this->DividirFecha($datos['fecha_final'])."' ";
	  }else{
		 $sql .= "  AND  CURRENT_DATE ";
	  }
      $sql  .= " ";
      $sql  .= " ORDER BY afi.fecha_afiliacion,afi.plan_atencion,afi.eps_afiliacion_id";


      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      return $rst;
    }
    
  }
?>