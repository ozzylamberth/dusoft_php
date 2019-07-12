<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: BancoSangreSQL.class.php,v 1.1 2008/01/09 15:00:06 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Manuel Ruiz Fernandez
  */
  /**
  * Clase : BancoSangreSQL
  * 
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Manuel Ruiz Fernandez
  */

  class BancoSangreSQL extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function BancoSangreSQL(){}
    /**
    * Funcion donde se verifica el permiso del usuario para el ingreso
    * al modulo
    *
    * @return array $datos vector que contiene la informacion de la consulta del codigo de
    * la empresa y la razon social 
    */
    function ObtenerPermisos()
    {
      $sql  = "SELECT   EM.empresa_id AS empresa, ";
      $sql .= "         EM.razon_social AS razon_social ";
      $sql .= "FROM     userpermisos_banco_sangre CP, empresas EM ";
      $sql .= "WHERE    CP.usuario_id = ".UserGetUID()." ";
      $sql .= "         AND CP.empresa_id = EM.empresa_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consultan los tipos de donante
    *
    * @return array $datos vector que contiene la informacion de la consulta de los tipos 
    * de donante
    */
    function ConsultarTiposDonante()
    {
      //echo "Consulta Tipos Donante";
      $sql  = "SELECT   tipo_donante_id, descripcion ";
      $sql .= "FROM     tipos_donante ";
      $sql .= "WHERE    sw_activo='1' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consultan los convenios existentes
    *
    * @return array $datos vector que contiene la informacion de la consulta de los 
    * convenios
    */
    function ConsultarConvenios()
    {
      $sql  = "SELECT    convenio_id, descripcion ";
      $sql .= "FROM      convenios ";
      $sql .= "WHERE     sw_activo='1' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;      
    }
    /**
    * Funcion donde se consultan los tipos de identificacion
    *
    * @return array $datos vector que contiene la informacion de la consulta de los tipos 
    * de identificacion
    */
    function ConsultarTipoId()
    {
      //$this->debug=true;
      $sql  = "SELECT    indice_de_orden, tipo_id_paciente, descripcion ";
      $sql .= "FROM      tipos_id_pacientes ";
      $sql .= "ORDER BY  indice_de_orden, tipo_id_paciente, descripcion ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consultan los tipos de fuerzas
    *
    * @return array $datos vector que contiene la informacion de la consulta de los tipos 
    * de fuerzas
    */
    function ConsultarTipoFuerzas()
    {
      $sql  = "SELECT   fuerza_id, descripcion ";
      $sql .= "FROM     tipo_fuerzas ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consultan las categorias
    *
    * @return array $datos vector que contiene la informacion de la consulta de las 
    * categorias
    */
    function ConsultarCategorias()
    {
      $sql  = "SELECT   estado_fuerza_id, categoria ";
      $sql .= "FROM     estado_fuerza";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consultan los tipos de grados para una categoria indicada
    *
    * @param string $categoria contiene el valor de la categoria
    * @return array $datos vector que contiene la informacion de la consulta de los tipos 
    * de grados
    */
    function ConsultarGradoCategoria($categoria)
    {
      $sql  = "SELECT     ecf.grado_id, tg.descripcion ";
      $sql .= "FROM       equivalencias_clasificacion_finaciera ecf, tipo_grados tg ";
      $sql .= "WHERE      estado_fuerza_id = '".$categoria."' AND ";
      $sql .= "           ecf.grado_id = tg.grado_id ";
      $sql .= "GROUP BY   ecf.grado_id, tg.descripcion ";
      $sql .= "ORDER BY   ecf.grado_id, tg.descripcion ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consulta la clasificacion financiera
    *
    * @param string $categoria contiene el valor de la categoria
    * @param intger $grado contiene el valor del grado
    * @return array $datos vector que contiene la informacion de la consulta de la  
    * clasificacion financiera
    */
    function ConsultarClasiFinanciera($categoria, $grado)
    {
      $sql  = "SELECT   ecf.clasifi_finaci_id, ecf.estado_fuerza_id, ecf.grado_id, ";
      $sql .= "         cf.descripcion ";
      $sql .= "FROM     equivalencias_clasificacion_finaciera ecf, ";
      $sql .= "         clasificaciones_financieros cf ";
      $sql .= "WHERE    ecf.estado_fuerza_id='".$categoria."' AND ";
      $sql .= "         ecf.grado_id='".$grado."' AND ";
      $sql .= "         cf.clasifi_finaci_id = ecf.clasifi_finaci_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consultan los tipos de sexo
    *
    * @return array $datos vector que contiene la informacion de la consulta de los tipos 
    * de sexo
    */
    function ConsultarTiposSexo()
    {
      $sql  = "SELECT    sexo_id, descripcion ";
      $sql .= "FROM      tipo_sexo ";
      $sql .= "WHERE     sw_mostrar='1' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consultan los tipos de estado civil
    *
    * @return array $datos vector que contiene la informacion de la consulta de los tipos 
    * de estado civil
    */
    function ConsultarTiposEstadoCivil()
    {
      $sql  = "SELECT    indice_de_orden, tipo_estado_civil_id, descripcion ";
      $sql .= "FROM      tipo_estado_civil ";
      $sql .= "ORDER BY  indice_de_orden, tipo_estado_civil_id, descripcion ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consulta si el donante tiene clasificacion financiera
    *
    * @param string $no_id contiene el valor del numero de identificacion
    * @param string $tipo_id contiene el valor del tipo de identificacion
    * @return array $datos vector que contiene la informacion de la clasificacion 
    * financiera
    */
    function ConsultarMilitar($no_id, $tipo_id)
    {
      $sql  = "SELECT    clasifi_finaci_id ";
      $sql .= "FROM      donantes d ";
      $sql .= "WHERE     d.donante_id='".$no_id."' AND ";
      $sql .= "          d.tipo_id_donante='".$tipo_id."' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consulta el codigo de la clasificacion financiera de un paciente
    *
    * @param string $no_id contiene el valor del numero de identificacion
    * @param string $tipo_id contiene el valor del tipo de identificacion
    * @return array $datos vector que contiene la informacion de la clasificacion 
    * financiera
    */
    function ConsultarMilitarPaci($no_id, $tipo_id)
    {
      $sql  = "SELECT    pi.clasifi_finaci_id ";
      $sql .= "FROM      pacientes p, paciente_issfa pi ";
      $sql .= "WHERE     p.paciente_id='".$no_id."' AND ";
      $sql .= "          p.tipo_id_paciente='".$tipo_id."' AND ";
      $sql .= "          p.paciente_id=pi.paciente_id AND ";
      $sql .= "          p.tipo_id_paciente=pi.tipo_id_paciente ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consulta si el numero de identificacion y el tipo de identificacion 
    * ya existen
    *
    * @param string $no_id contiene el valor del numero de identificacion
    * @param string $tipo_id contiene el valor del tipo de identificacion
    * @return array $datos vector que contiene la informacion de la consulta 
    */
    function ConsultarIdentificacion($no_id, $tipo_id)
    {
      $sql  = "SELECT   d.donante_id, d.tipo_id_donante ";
      $sql .= "FROM     donantes d ";
      $sql .= "WHERE    d.donante_id='".$no_id."' AND d.tipo_id_donante='".$tipo_id."' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consulta la informacion del donante
    *
    * @param string $no_id contiene el valor del numero de identificacion
    * @param string $tipo_id contiene el valor del tipo de identificacion
    * @param array $militar vector con la informacion de la clasificacion financiera
    * @return array $datos vector que contiene la informacion del donante
    */
    function ConsultarDonante($no_id, $tipo_id, $militar)
    {
      //$this->debug=true;
      $sql  = "SELECT    d.codigo_donante, d.donante_id, d.tipo_id_donante, ";
      $sql .= "          d.fecha_registro, ";
      $sql .= "          tp.descripcion as desc_tipo_id, d.tipo_donante_id, ";
      $sql .= "          td.descripcion as desc_tipo_donante, d.convenio_id, ";
      $sql .= "          co.descripcion as desc_convenio, d.primer_apellido, ";
      $sql .= "          d.segundo_apellido, d.primer_nombre, d.segundo_nombre, ";
      $sql .= "          d.fecha_nacimiento, d.edad, d.sexo_id, ";
      $sql .= "          ts.descripcion as desc_sexo, d.tipo_estado_civil_id, ";
      $sql .= "          tec.descripcion as desc_est_civil, d.ocupacion_id, ";
      $sql .= "          oc.ocupacion_descripcion as desc_ocupacion, d.email, ";
      $sql .= "          d.tel_domicilio, d.dir_domicilio, d.tel_trabajo, d.dir_trabajo, ";
      $sql .= "          d.no_celular, d.grupo_sanguineo, d.rh_gs, d.observaciones ";
      //$sql .= "          d.estado_donante_id, ed.descripcion as desc_est_donante, ";
      //$sql .= "          d.causa_donacion_id, cd.descripcion as desc_cau_donante ";
      if($militar[0]['clasifi_finaci_id']!="")
      {
        $sql .= "          , d.clasifi_finaci_id, cf.descripcion as desc_clasi_finan, ";
        $sql .= "          d.estado_fuerza_id, ef.categoria as categoria, ";
        $sql .= "          d.grado_id, tg.descripcion as desc_tipo_grado, ";
        $sql .= "          d.fuerza_id, tf.descripcion as desc_tipo_fuerza ";
      }
      $sql .= "FROM      donantes d left join convenios co ";
      $sql .= "          on(d.convenio_id=co.convenio_id), ";
      /*$sql .= "          left join estados_donante_causas edc ";
      $sql .= "          on(d.estado_donante_id=edc.estado_donante_id AND ";
      $sql .= "          d.causa_donacion_id=edc.causa_donacion_id) ";
      $sql .= "          left join estados_donante ed ";
      $sql .= "          on(edc.estado_donante_id=ed.estado_donante_id) ";
      $sql .= "          left join causas_donacion cd ";
      $sql .= "          on(edc.causa_donacion_id=cd.causa_donacion_id), ";*/
      //$sql .= "          left join estados_donante ed ";
      //$sql .= "          on(d.estado_donante_id=ed.estado_donante_id), ";
      $sql .= "          tipos_id_pacientes tp, tipos_donante td, tipo_sexo ts, ";
      $sql .= "          tipo_estado_civil tec, ocupaciones oc ";
      if($militar[0]['clasifi_finaci_id']!="")
      {
        $sql .= "          , equivalencias_clasificacion_finaciera ecf, ";
        $sql .= "          clasificaciones_financieros cf, estado_fuerza ef, ";
        $sql .= "          tipo_grados tg, tipo_fuerzas tf ";
      }
      $sql .= "WHERE     d.donante_id='".$no_id."' AND ";
      $sql .= "          d.tipo_id_donante='".$tipo_id."' AND ";
      $sql .= "          d.tipo_id_donante=tp.tipo_id_paciente AND ";
      $sql .= "          d.tipo_donante_id=td.tipo_donante_id AND ";
      $sql .= "          d.sexo_id=ts.sexo_id AND ";
      $sql .= "          d.tipo_estado_civil_id=tec.tipo_estado_civil_id AND ";
      $sql .= "          d.ocupacion_id=oc.ocupacion_id ";
      if($militar[0]['clasifi_finaci_id']!="")
      {
        $sql .= "          AND d.clasifi_finaci_id=ecf.clasifi_finaci_id AND ";
        $sql .= "          d.estado_fuerza_id=ecf.estado_fuerza_id AND ";
        $sql .= "          d.grado_id=ecf.grado_id AND ";
        $sql .= "          ecf.estado_fuerza_id=ef.estado_fuerza_id AND ";
        $sql .= "          ecf.grado_id=tg.grado_id AND ";
        $sql .= "          ecf.clasifi_finaci_id=cf.clasifi_finaci_id AND ";
        $sql .= "          d.fuerza_id=tf.fuerza_id ";
      } 
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    
    /**
    * Funcion que permite consultar el estado de un donante y las causas de este
    * 
    * @param string $no_id valor del numero de identificacion
    * @param string $tipo_id valor del tipo de identificacion
    * @return array $datos vector con la informacion de la consulta del estado del donante
    */
    function ConsultarEstadoDC($no_id, $tipo_id)
    {     
      $sql  = "SELECT  ed.estado_donante_id, cd.causa_donacion_id, ";
      $sql .= "        ed.descripcion as desc_est_donante, ";
      $sql .= "        cd.descripcion as desc_cau_donante, d.observaciones_estado, ";
      $sql .= "        d.tiempo_estado ";
      $sql .= "FROM    donantes d join estados_donante ed on ";
      $sql .= "        (d.estado_donante_id=ed.estado_donante_id) ";
      $sql .= "        left join causas_donacion cd on ";
      $sql .= "        (d.causa_donacion_id=cd.causa_donacion_id) ";
      $sql .= "WHERE   d.donante_id='".$no_id."' AND ";
      $sql .= "        d.tipo_id_donante='".$tipo_id."' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    
    /**
    * Funcion donde se consulta el lugar de nacimiento del donante
    *
    * @param string $no_id contiene el valor del numero de identificacion
    * @param string $tipo_id contiene el valor del tipo de identificacion
    * @return array $datos vector que contiene la informacion del lugar de nacimiento
    * del donante
    */
    function ConsultarLugarNacimiento($no_id, $tipo_id)
    {
      $sql  = "SELECT    d.naci_pais_id, d.naci_dpto_id, d.naci_mpio_id, ";
      $sql .= "          tpa.pais as desc_naci_pais, tdp.departamento as desc_naci_dpto, ";
      $sql .= "          tmp.municipio as desc_naci_mpio ";
      $sql .= "FROM      donantes d, tipo_mpios tmp, tipo_dptos tdp, tipo_pais tpa ";
      $sql .= "WHERE     d.donante_id='".$no_id."' AND ";
      $sql .= "          d.tipo_id_donante='".$tipo_id."' AND ";
      $sql .= "          d.naci_mpio_id=tmp.tipo_mpio_id AND ";
      $sql .= "          d.naci_dpto_id=tmp.tipo_dpto_id AND ";
      $sql .= "          d.naci_pais_id=tmp.tipo_pais_id AND ";
      $sql .= "          tmp.tipo_pais_id=tdp.tipo_pais_id AND ";
      $sql .= "          tmp.tipo_dpto_id=tdp.tipo_dpto_id AND ";
      $sql .= "          tdp.tipo_pais_id=tpa.tipo_pais_id";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consulta el lugar de domicilio del donante
    *
    * @param string $no_id contiene el valor del numero de identificacion
    * @param string $tipo_id contiene el valor del tipo de identificacion
    * @return array $datos vector que contiene la informacion del lugar de  
    * nacimiento del donante
    */
    function ConsultarLugarDomicilio($no_id, $tipo_id)
    {
      $sql  = "SELECT    d.domi_pais_id, d.domi_dpto_id, d.domi_mpio_id, ";
      $sql .= "          tpa.pais as desc_domi_pais, tdp.departamento as desc_domi_dpto, ";
      $sql .= "          tmp.municipio as desc_domi_mpio ";
      $sql .= "FROM      donantes d, tipo_mpios tmp, tipo_dptos tdp, tipo_pais tpa ";
      $sql .= "WHERE     d.donante_id='".$no_id."' AND ";
      $sql .= "          d.tipo_id_donante='".$tipo_id."' AND ";
      $sql .= "          d.domi_mpio_id=tmp.tipo_mpio_id AND ";
      $sql .= "          d.domi_dpto_id=tmp.tipo_dpto_id AND ";
      $sql .= "          d.domi_pais_id=tmp.tipo_pais_id AND ";
      $sql .= "          tmp.tipo_pais_id=tdp.tipo_pais_id AND ";
      $sql .= "          tmp.tipo_dpto_id=tdp.tipo_dpto_id AND ";
      $sql .= "          tdp.tipo_pais_id=tpa.tipo_pais_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consulta el lugar de domicilio del paciente
    *
    * @param string $no_id contiene el valor del numero de identificacion
    * @param string $tipo_id contiene el valor del tipo de identificacion
    * @return array $datos vector que contiene la informacion del lugar de  
    * domicilio del paciente
    */    
    function ConsultarLugarDomicilioP($no_id, $tipo_id)
    {
      $sql  = "SELECT    d.domi_pais_id, d.domi_dpto_id, d.domi_mpio_id, ";
      $sql .= "          tpa.pais as desc_domi_pais, tdp.departamento as desc_domi_dpto, ";
      $sql .= "          tmp.municipio as desc_domi_mpio ";
      $sql .= "FROM      pacientes p, tipo_mpios tmp, tipo_dptos tdp, tipo_pais tpa ";
      $sql .= "WHERE     p.paciente_id='".$no_id."' AND ";
      $sql .= "          p.tipo_id_paciente='".$tipo_id."' AND ";
      $sql .= "          p.tipo_mpio_id=tmp.tipo_mpio_id AND ";
      $sql .= "          p.tipo_dpto_id=tmp.tipo_dpto_id AND ";
      $sql .= "          p.tipo_pais_id=tmp.tipo_pais_id AND ";
      $sql .= "          tmp.tipo_pais_id=tdp.tipo_pais_id AND ";
      $sql .= "          tmp.tipo_dpto_id=tdp.tipo_dpto_id AND ";
      $sql .= "          tdp.tipo_pais_id=tpa.tipo_pais_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consulta la informacion de la historia clinica
    *
    * @param string $no_id contiene el valor del numero de identificacion
    * @param string $tipo_id contiene el valor del tipo de identificacion
    * @return array $datos vector que contiene la informacion de la historia clinica  
    */    
    function ConsultarDonanteHC($no_id, $tipo_id)
    {
      $sql  = "SELECT    * ";
      $sql .= "FROM      historias_clinicas ";
      $sql .= "WHERE     paciente_id='".$no_id."' AND tipo_id_paciente='".$tipo_id."' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consulta la informacion del paciente
    *
    * @param string $no_id contiene el valor del numero de identificacion
    * @param string $tipo_id contiene el valor del tipo de identificacion
    * @return array $datos vector que contiene la informacion del paciente  
    */
    function  ConsultarDonantePaciente($no_id, $tipo_id, $militarP)
    {
      $sql  = "SELECT    p.paciente_id, p.tipo_id_paciente, ";
      $sql .= "          tp.descripcion as desc_tipo_id, p.primer_apellido, ";
      $sql .= "          p.segundo_apellido, p.primer_nombre, p.segundo_nombre, ";
      $sql .= "          p.fecha_nacimiento, p.residencia_direccion, ";
      $sql .= "          p.residencia_telefono, p.sexo_id, ts.descripcion as desc_sexo, ";
      $sql .= "          p.tipo_estado_civil_id, tec.descripcion as desc_est_civil, ";
      $sql .= "          p.tipo_pais_id, p.tipo_dpto_id, p.tipo_mpio_id, ";
      $sql .= "          tpa.pais as desc_domi_pais, tdp.departamento as desc_domi_dpto, ";
      $sql .= "          tmp.municipio as desc_domi_mpio, p.ocupacion_id, ";
      $sql .= "          oc.ocupacion_descripcion as desc_ocupacion ";
      if($militarP[0]['clasifi_finaci_id']!="")
      {
        $sql .= "        , pi.clasifi_finaci_id, cf.descripcion as desc_clasi_finan, ";
        $sql .= "        pi.estado_fuerza_id, ef.categoria as categoria, ";
        $sql .= "        pi.grado_id, tg.descripcion as desc_tipo_grado, ";
        $sql .= "        pi.fuerza_id, tf.descripcion as desc_tipo_fuerza ";
      }
      $sql .= "FROM      pacientes p left join tipo_estado_civil tec ";
      $sql .= "          on(p.tipo_estado_civil_id=tec.tipo_estado_civil_id) ";
      $sql .= "          left join ocupaciones oc on(p.ocupacion_id=oc.ocupacion_id), ";
      $sql .= "          tipos_id_pacientes tp, tipo_mpios tmp, ";
      $sql .= "          tipo_dptos tdp, tipo_pais tpa, tipo_sexo ts ";
      if($militarP[0]['clasifi_finaci_id']!="")
      {
        $sql .= "        , equivalencias_clasificacion_finaciera ecf, ";
        $sql .= "        clasificaciones_financieros cf, estado_fuerza ef, ";
        $sql .= "        tipo_grados tg, tipo_fuerzas tf, paciente_issfa pi ";
      }
      $sql .= "WHERE     p.paciente_id='".$no_id."' AND ";
      $sql .= "          p.tipo_id_paciente='".$tipo_id."' AND ";
      $sql .= "          p.tipo_id_paciente=tp.tipo_id_paciente AND ";
      $sql .= "          p.sexo_id=ts.sexo_id AND ";
      $sql .= "          p.tipo_mpio_id=tmp.tipo_mpio_id AND ";
      $sql .= "          p.tipo_dpto_id=tmp.tipo_dpto_id AND ";
      $sql .= "          p.tipo_pais_id=tmp.tipo_pais_id AND ";
      $sql .= "          tmp.tipo_pais_id=tdp.tipo_pais_id AND ";
      $sql .= "          tmp.tipo_dpto_id=tdp.tipo_dpto_id AND ";
      $sql .= "          tdp.tipo_pais_id=tpa.tipo_pais_id ";
      if($militarP[0]['clasifi_finaci_id']!="")
      {
        $sql .= "        AND p.paciente_id=pi.paciente_id AND ";
        $sql .= "        p.tipo_id_paciente=pi.tipo_id_paciente AND ";
        $sql .= "        pi.clasifi_finaci_id=ecf.clasifi_finaci_id AND ";
        $sql .= "        pi.estado_fuerza_id=ecf.estado_fuerza_id AND ";
        $sql .= "        pi.grado_id=ecf.grado_id AND ";
        $sql .= "        ecf.estado_fuerza_id=ef.estado_fuerza_id AND ";
        $sql .= "        ecf.grado_id=tg.grado_id AND ";
        $sql .= "        ecf.clasifi_finaci_id=cf.clasifi_finaci_id AND ";
        $sql .= "        pi.fuerza_id=tf.fuerza_id ";
      }
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion que permite calcular la edad indicando una fecha
    *
    * @param string $fNac contiene el valor de la fecha
    * @return array $datos contiene el valor de la edad calculada
    */    
    function CalcularEdad($fNac)
    {
      $sql  = "SELECT edad('".$fNac."') as edad ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se registran los datos del donante
    *
    * @param array $request contiene la informacion del donante
    * @param string $edad valor de la edad del donante
    * @return array vector que contiene el codigo del donante y el codigo del detalle 
    * registrado
    */
    function IngresarDetalleDonante($request, $edad)
    {
      if($request['tipoIngreso']=="nuevo")
      {
        $indice = array();
        
        $this->ConexionTransaccion();
        
        $sql = "SELECT NEXTVAL('donantes_codigo_donante_seq'::regclass) AS sq ";
        
        if(!$rst = $this->ConexionBaseDatos($sql)) return false;
          
        if(!$rst->EOF)
        {
          $indice = $rst->GetRowAssoc($ToUpper=false);
          $rst->MoveNext();
        }
        
        $rst->Close();
        
        $sqlerror = "SELECT setval('donantes_codigo_donante_seq', ".($indice['sq']-1).") ";
        
        $sql  = "INSERT INTO donantes( ";
        $sql .= "       codigo_donante, ";
        $sql .= "       donante_id, ";
        $sql .= "       tipo_id_donante, ";
        $sql .= "       fecha_registro, ";
        $sql .= "       tipo_donante_id, ";
        $sql .= "       convenio_id, ";
        $sql .= "       primer_apellido, ";
        $sql .= "       segundo_apellido, ";
        $sql .= "       primer_nombre, ";
        $sql .= "       segundo_nombre, ";
        $sql .= "       naci_pais_id, ";
        $sql .= "       naci_dpto_id, ";
        $sql .= "       naci_mpio_id, ";
        $sql .= "       fecha_nacimiento, ";
        $sql .= "       edad, ";
        $sql .= "       sexo_id, ";
        $sql .= "       tipo_estado_civil_id, ";
        $sql .= "       ocupacion_id, ";
        $sql .= "       email, ";
        $sql .= "       domi_pais_id, ";
        $sql .= "       domi_dpto_id, ";
        $sql .= "       domi_mpio_id, ";
        $sql .= "       tel_domicilio, ";
        $sql .= "       dir_domicilio, ";
        $sql .= "       tel_trabajo, ";
        $sql .= "       dir_trabajo, ";
        $sql .= "       no_celular, ";
        $sql .= "       clasifi_finaci_id, ";
        $sql .= "       estado_fuerza_id, ";
        $sql .= "       grado_id, ";
        $sql .= "       fuerza_id, ";
        $sql .= "       usuario_id ";
        $sql .= ")VALUES( ";
        $sql .= "       ".$indice['sq'].", ";
        $sql .= "       '".$request['noId']."', ";
        $sql .= "       '".$request['tipoId']."', ";
        $sql .= "       '".$request['fechaActual']."', ";
        $sql .= "       ".$request['tipoDonador'].", ";
        if($request['convenio']!="" && $request['convenio']!="-1")
          $sql .= "     ".$request['convenio'].", ";
        else
          $sql .= "     NULL, ";
        $sql .= "       '".$request['apellidoPaterno']."', ";
        $sql .= "       '".$request['apellidoMaterno']."', ";
        $sql .= "       '".$request['primerNombre']."', ";
        $sql .= "       '".$request['segundoNombre']."', ";
        $sql .= "       '".$request['pais']."', ";
        $sql .= "       '".$request['dpto']."', ";
        $sql .= "       '".$request['mpio']."', ";
        $sql .= "       '".$request['fechaNacimiento']."', ";
        $sql .= "       ".$edad.", ";
        $sql .= "       '".$request['sexo']."', ";
        $sql .= "       '".$request['estadoCivil']."', ";
        $sql .= "       '".$request['ocupacion_id']."', ";
        $sql .= "       '".$request['email']."', ";
        $sql .= "       '".$request['paisM3']."', ";
        $sql .= "       '".$request['dptoM3']."', ";
        $sql .= "       '".$request['mpioM3']."', ";
        $sql .= "       '".$request['telDomicilio']."', ";
        $sql .= "       '".$request['dirDomicilio']."', ";
        $sql .= "       '".$request['telTrabajo']."', ";
        $sql .= "       '".$request['dirTrabajo']."', ";
        $sql .= "       '".$request['noCelular']."', ";
        if($request['clasificacion']!="" && $request['clasificacion']!="-1")
          $sql .= "     ".$request['clasificacion'].", ";
        else
          $sql .= "     NULL, ";
        $sql .= "       '".$request['categoria']."', ";
        if($request['grado']!="" && $request['grado']!="-1")  
          $sql .= "     ".$request['grado'].", ";
        else
          $sql .= "     NULL, ";
        if($request['fuerza']!="" && $request['fuerza']!="-1")
          $sql .= "       ".$request['fuerza'].", ";
        else
          $sql .= "       NULL, ";
        $sql .= "       ".UserGetUID()." ";
        $sql .= ") ";
        
        if(!$rst = $this->ConexionTransaccion($sql))
        {
          if(!$rst = $this->ConexionTransaccion($sqlerror)) return false;
          return false;
        }

        $this->ConexionTransaccion();
        
        $indice1 = array();
        
        $sql1 = "SELECT NEXTVAL('detalle_donante_detalle_donante_id_seq'::regclass) AS sq1 ";
        
        if(!$rst1 = $this->ConexionBaseDatos($sql1)) return false;
          
        if(!$rst1->EOF)
        {
          $indice1 = $rst1->GetRowAssoc($ToUpper=false);
          $rst1->MoveNext();
        }
        
        $rst1->Close();
        
        $sqlerror1 = "SELECT setval('detalle_donante_detalle_donante_id_seq', ".($indice1['sq1']-1).") ";
        
        $sql1  = "INSERT INTO detalle_donante( ";
        $sql1 .= "       detalle_donante_id, ";
        $sql1 .= "       fecha, ";
        $sql1 .= "       hora, ";
        $sql1 .= "       codigo_donante ";
        $sql1 .= ")VALUES( ";
        $sql1 .= "       ".$indice1['sq1'].", ";
        $sql1 .= "       '".$request['fechaActual']."', ";
        $sql1 .= "       '".$request['horaActual']."', ";
        $sql1 .= "       ".$indice['sq'].") ";
                
        if(!$rst1 = $this->ConexionTransaccion($sql1))
        {
          if(!$rst1 = $this->ConexionTransaccion($sqlerror1)) return false;
          return false;
        }
        
        $this->Commit();
        return array("cod_don"=>$indice['sq'], "cod_det"=>$indice1['sq1']);
      }else{
        $this->ConexionTransaccion();
    
        $sql  = "UPDATE   donantes ";
        $sql .= "SET      tipo_donante_id = ".$request['tipoDonador'].", ";
        if($request['convenio']!="")
          $sql .= "       convenio_id = ".$request['convenio'].", ";
        else 
          $sql .= "       convenio_id = NULL, ";
        $sql .= "         primer_apellido = '".$request['apellidoPaterno']."', ";
        $sql .= "         segundo_apellido = '".$request['apellidoMaterno']."', ";
        $sql .= "         primer_nombre = '".$request['primerNombre']."', ";
        $sql .= "         segundo_nombre = '".$request['segundoNombre']."', ";
        $sql .= "         naci_pais_id = '".$request['pais']."', ";
        $sql .= "         naci_dpto_id = '".$request['dpto']."', ";
        $sql .= "         naci_mpio_id = '".$request['mpio']."', ";
        $sql .= "         fecha_nacimiento = '".$request['fechaNacimiento']."', ";
        $sql .= "         edad = ".$edad.", ";
        $sql .= "         sexo_id = '".$request['sexo']."', ";
        $sql .= "         tipo_estado_civil_id = '".$request['estadoCivil']."', ";
        $sql .= "         ocupacion_id = '".$request['ocupacion_id']."', ";
        $sql .= "         email = '".$request['email']."', ";
        $sql .= "         domi_pais_id = '".$request['paisM3']."', ";
        $sql .= "         domi_dpto_id = '".$request['dptoM3']."', ";
        $sql .= "         domi_mpio_id = '".$request['mpioM3']."', ";
        $sql .= "         tel_domicilio = '".$request['telDomicilio']."', ";
        $sql .= "         dir_domicilio = '".$request['dirDomicilio']."', ";
        $sql .= "         tel_trabajo = '".$request['telTrabajo']."', ";
        $sql .= "         dir_trabajo = '".$request['dirTrabajo']."', ";
        $sql .= "         no_celular = '".$request['noCelular']."', ";
        if($request['clasificacion']!="" && $request['clasificacion']!="-1")
          $sql .= "       clasifi_finaci_id = ".$request['clasificacion'].", ";
        else
          $sql .= "       clasifi_finaci_id = NULL, ";
        if($request['categoria']!="" && $request['categoria']!="-1")  
          $sql .= "       estado_fuerza_id = '".$request['categoria']."', ";
        else
          $sql .= "       estado_fuerza_id = NULL, ";
        if($request['grado']!="" && $request['grado']!="-1")
          $sql .= "       grado_id = ".$request['grado'].", ";
        else
          $sql .= "       grado_id = NULL, ";
        if($request['fuerza']!="" && $request['fuerza']!="-1")  
          $sql .= "       fuerza_id = ".$request['fuerza'].", ";
        else
          $sql .= "       fuerza_id = NULL, ";
        $sql .= "         usuario_id = ".UserGetUID()." ";
        $sql .= "WHERE    codigo_donante = ".$request['codDonante']." ";
        
        if(!$rst = $this->ConexionTransaccion($sql))
        {
          return false;
        }
        
        $this->ConexionTransaccion();
        
        $indice1 = array();
        
        $sql1 = "SELECT NEXTVAL('detalle_donante_detalle_donante_id_seq'::regclass) AS sq1 ";
        
        if(!$rst1 = $this->ConexionBaseDatos($sql1)) return false;
          
        if(!$rst1->EOF)
        {
          $indice1 = $rst1->GetRowAssoc($ToUpper=false);
          $rst1->MoveNext();
        }
        
        $rst1->Close();
        
        $sqlerror1 = "SELECT setval('detalle_donante_detalle_donante_id_seq', ".($indice1['sq1']-1).") ";
        
        $sql1  = "INSERT INTO detalle_donante( ";
        $sql1 .= "       detalle_donante_id, ";
        $sql1 .= "       fecha, ";
        $sql1 .= "       hora, ";
        $sql1 .= "       codigo_donante ";
        $sql1 .= ")VALUES( ";
        $sql1 .= "       ".$indice1['sq1'].", ";
        $sql1 .= "       '".$request['fechaActual']."', ";
        $sql1 .= "       '".$request['horaActual']."', ";
        $sql1 .= "       ".$request['codDonante'].") ";
                
        if(!$rst1 = $this->ConexionTransaccion($sql1))
        {
          if(!$rst1 = $this->ConexionTransaccion($sqlerror1)) return false;
          return false;
        }        
        
        $this->Commit();
        
        return array("cod_don"=>$request['codDonante'], "cod_det"=>$indice1['sq1']);
      }
    }
    /**
    * Funcion donde se consultan lo grupos sanguineos
    *
    * @return array $datos vector que contiene la informacion de los grupos sanguineos
    */    
    function ConsultarGrupoSanguineo()
    {
      $sql  = "SELECT    DISTINCT(grupo_sanguineo) ";
      $sql .= "FROM      hc_tipos_sanguineos ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;      
    }
    /**
    * Funcion donde se consulta la informacion de los tipos de RH
    *
    * @return array $datos vector que contiene la informacion de los tipos de RH
    */
    function ConsultarRH()
    {
      $sql  = "SELECT    DISTINCT(rh) ";
      $sql .= "FROM      hc_tipos_rh ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
     /**
    * Funcion donde se consulta la informacion de los subgrupos de RH
    *
    * @return array $datos vector que contiene la informacion de los subgrupos de RH
    */
    function ConsultarSubgrupoRH()
    {
      $sql  = "SELECT    * ";
      $sql .= "FROM      subgrupos_rh_neg ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
     /**
    * Funcion donde se consulta la tipificacion del donante
    *
    * @param integer $cod_don valor del codigo del donante
    * @return array $datos vector que contiene la informacion de la tipificacion
    */
    function ConsultarTipificacion($cod_don)
    {
      $sql  = "SELECT    grupo_sanguineo, rh_gs, subgrupo_rh, rh_sg, observaciones ";
      $sql .= "FROM      donantes ";
      $sql .= "WHERE     codigo_donante = ".$cod_don." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se registran los signos vitales del donante
    *
    * @param array $request vector con los datos a registrar 
    * @param string $sgrupo valor del subgrupo
    * @param string $sgrh valor del RH del subgrupo
    * @return array $request['codDonante'] vector que contiene el codigo del donante
    */
    function IngresarSignosTipificacion($request, $sgrupo, $sgrh)
    {
      //$this->debug = true;
      
      $this->ConexionTransaccion();

      $sql  = "UPDATE   detalle_donante "; 
      $sql .= "SET      tension_arterial=".$request['tensionArterial'].", ";
      $sql .= "         pulso=".$request['pulso'].", ";
      $sql .= "         frec_respiratoria=".$request['frecRespiratoria'].", ";
      $sql .= "         temperatura=".$request['temperatura'].", ";
      $sql .= "         peso=".$request['peso'].", ";
      $sql .= "         altura=".$request['altura'].", ";
      $sql .= "         masa_corporal=".$request['masaCorporal']." ";
      $sql .= "WHERE    detalle_donante_id=".$request['cod_det']." ";
      
      if(!$rst = $this->ConexionTransaccion($sql))
      {
        return false;
      }

      $this->ConexionTransaccion();
      
      $sql1  = "UPDATE  donantes ";
      $sql1 .= "SET     grupo_sanguineo='".$request['grupoSanguineo']."', ";
      $sql1 .= "        rh_gs='".$request['factorRH']."', ";
      if($sgrupo!="")
        $sql1 .= "      subgrupo_rh='".$sgrupo."', ";
      else
        $sql1 .= "      subgrupo_rh=NULL, ";
      if($sgrh!="")
        $sql1 .= "      rh_sg='".$sgrh."', ";
      else
        $sql1 .= "      rh_sg=NULL, ";
      $sql1 .= "        observaciones='".$request['observaciones']."' ";
      $sql1 .= "WHERE   codigo_donante=".$request['cod_don']." ";
      
      if(!$rst1 = $this->ConexionTransaccion($sql1))
      {
        return false;
      }
      
      $this->Commit();
      
      return $request['codDonante'];
    }
    /**
    * Funcion donde se consultan las preguntas del cuestionario
    *
    * @param array $request vector que contiene el filtro para la consulta
    * @return array $datos vector que contiene las preguntas del cuestionario
    */    
    function ConsultarCuestionario($request)
    {
      $sql  = "SELECT    cuestionario_id, pregunta ";
      $sql .= "FROM      cuestionario_donante ";
      $sql .= "WHERE     sw_activo='1' AND ";
      $sql .= "          (sexo_id='0' OR ";
      $sql .= "          sexo_id='".$request['sexo']."') ";
      $sql .= "ORDER BY  cuestionario_id, pregunta ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consultan las preguntas del cuestionario
    *
    * @param array $request vector que contiene el filtro para la consulta
    * @return array $request['cod_don'] vector que contiene el codigo del donante
    */ 
    function AlmacenarRespuestas($request)
    {
      //$this->debug=true;

      $this->ConexionTransaccion();
      for($i=0;$i<$request['cantPreg'];$i++)
      {
        $indice = array();      
        
        $sql = "SELECT NEXTVAL('respuestas_donante_respuesta_donante_id_seq'::regclass) AS sq ";
  
        if(!$rst = $this->ConexionBaseDatos($sql)) return false;
            
        if(!$rst->EOF)
        {
          $indice = $rst->GetRowAssoc($ToUpper=false);
          $rst->MoveNext();
        }
        
        $rst->Close();
        
        $sqlerror = "SELECT setval('respuestas_donante_respuesta_donante_id_seq', ".($indice['sq']-1).") ";
        
        $sql  = "INSERT INTO respuestas_donante( ";
        $sql .= "       respuesta_donante_id, ";
        $sql .= "       detalle, ";
        $sql .= "       respuesta, ";
        $sql .= "       fecha_registro, ";
        $sql .= "       cuestionario_id, ";
        $sql .= "       codigo_donante ";
        $sql .= ")VALUES( ";
        $sql .= "       ".$indice['sq'].", ";
        $sql .= "       '".$request['detalle'.$i]."', ";
        $sql .= "       '".$request['respuesta'.$i]."', ";
        $sql .= "       NOW(), ";
        $sql .= "       ".$request['cuestionarioId'.$i].", ";
        $sql .= "       ".$request['cod_don']." ";
        $sql .= ") ";
        
        if(!$rst = $this->ConexionTransaccion($sql))
        {
          if(!$rst = $this->ConexionBaseDatos($sqlerror)) return false;
          return false;
        }    
        $rst->Close();
      }
      
      $this->Commit();
      
      return $request['cod_don'];
    }
    /**
    * Funcion donde se consulta el detalle del donante
    *
    * @param integer $cod_det valor del codigo del detalle
    * @return array $datos vector que contiene la informacion del detalle del donante
    */ 
    function ConsultarDetalleDonante($cod_det)
    {
      //$this->debug=true;
      $sql  = "SELECT    * ";
      $sql .= "FROM      detalle_donante ";
      $sql .= "WHERE     detalle_donante_id = ".$cod_det." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consultan las respuestas de un donante, indicando una fecha
    *
    * @param integer $cod_don codigo del donante
    * @param string $fecha_det fecha de registro de las respuestas
    * @return array $datos vector que contiene las respuestas del donante
    */ 
    function ConsultarRespuestas($cod_don, $fecha_det)
    {
      $sql  = "SELECT    rd.respuesta_donante_id, rd.detalle, rd.respuesta, ";
      $sql .= "          rd.cuestionario_id, rd.codigo_donante, rd.fecha_registro, ";
      $sql .= "          cd.pregunta ";
      $sql .= "FROM      respuestas_donante rd, cuestionario_donante cd ";
      $sql .= "WHERE     rd.codigo_donante=".$cod_don." AND ";
      $sql .= "          rd.fecha_registro='".$fecha_det."' AND ";
      $sql .= "          rd.cuestionario_id = cd.cuestionario_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consultan los tipos de bolsas
    *
    * @return array $datos vector que contiene los tipos de bolsas existentes
    */ 
    function ConsultarTiposBolsa()
    {
      $sql  = "SELECT    indice_de_orden, tipo_bolsa_id, descripcion ";
      $sql .= "FROM      tipos_bolsas ";
      $sql .= "WHERE     sw_activo='1' ";
      $sql .= "ORDER BY  indice_de_orden, tipo_bolsa_id, descripcion ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consultan los estados del donante
    *
    * @return array $datos vector que contiene los estados del donante existentes
    */ 
    function ConsultarEstadosDonante()
    {
      $sql  = "SELECT    estado_donante_id, descripcion ";
      $sql .= "FROM      estados_donante ";
      $sql .= "WHERE     sw_activo='1' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consultan la informacion de los estados del donante y sus posibles 
    * causas
    *
    * @param String $estDon contiene el codigo del estado del donante
    * @return array $datos 
    */
    function ConsultarCausasEstados($estDon)
    { 
      //$this->debug=true;
      
      $sql  = "SELECT    ed.estado_donante_id, ed.descripcion as desc_estado_donante, ";
      $sql .= "          cd.causa_donacion_id, cd.descripcion as desc_causa_donacion ";
      $sql .= "FROM      estados_donante_causas edc, estados_donante ed, ";
      $sql .= "          causas_donacion cd ";
      $sql .= "WHERE     edc.estado_donante_id = ".$estDon." ";
      $sql .= "          AND edc.estado_donante_id = ed.estado_donante_id ";
      $sql .= "          AND edc.causa_donacion_id = cd.causa_donacion_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion en la que se almacena la informacion del registro de donacion
    *
    * @param array $request contiene los datos del request
    * @return integer $indice2['sq2'] contiene el codigo del detalle del registro de 
    * la donacion
    */
    function IngresoRegistroDonacion($request)
    {
      //$this->debug=true;
      $this->ConexionTransaccion();
    
      $sql  = "UPDATE  donantes ";
      $sql .= "SET     estado_donante_id = ".$request['estadoDonacion'].", ";
      if($request['causas']!="" && $request['causas']!="0")
        $sql .= "      causa_donacion_id = ".$request['causas'].", ";
      else
        $sql .= "      causa_donacion_id = NULL, ";
      $sql .= "        observaciones_estado = '".$request['observaciones']."', ";
      $tiempo_estado = "".$request['cantTiempo']." ".$request['unidTiempo']."";
      $sql .= "        tiempo_estado = '".$tiempo_estado."' ";      
      $sql .= "WHERE   codigo_donante = ".$request['codDonante']." ";
      
      if(!$rst = $this->ConexionTransaccion($sql))
      {
        return false;
      }
      
      $this->ConexionTransaccion();
      
      $indice1 = array();
      
      $sql1 = "SELECT NEXTVAL('tipos_bolsas_donante_tipo_bolsa_donante_id_seq'::regclass) AS sq1 ";
        
      if(!$rst1 = $this->ConexionBaseDatos($sql1)) return false;
        
      if(!$rst1->EOF)
      {
        $indice1 = $rst1->GetRowAssoc($ToUpper=false);
        $rst1->MoveNext();
      }
      
      $rst1->Close();
      
      $sqlerror1 = "SELECT setval('tipos_bolsas_donante_tipo_bolsa_donante_id_seq', ".($indice1['sq1']-1).") ";
      
      $sql1  = "INSERT INTO tipos_bolsas_donante( ";
      $sql1 .= "       tipo_bolsa_donante_id, ";
      $sql1 .= "       codigo_donante, ";
      $sql1 .= "       tipo_bolsa_id, ";
      $sql1 .= "       otra_bolsa, ";
      $sql1 .= "       fecha_registro, ";
      $sql1 .= "       usuario_id ";
      $sql1 .= ")VALUES( ";
      $sql1 .= "       ".$indice1['sq1'].", ";
      $sql1 .= "       ".$request['codDonante'].", ";
      $sql1 .= "       ".$request['tipoBolsa'].", ";
      $sql1 .= "       '".$request['otros']."', ";
      $sql1 .= "       NOW(), ";
      $sql1 .= "       ".UserGetUID()." ";
      $sql1 .= ") ";
      
      if(!$rst1 = $this->ConexionTransaccion($sql1))
      {
        if(!$rst1 = $this->ConexionTransaccion($sqlerror1)) return false;
        return false;
      }
      
      $this->ConexionTransaccion();
      
      $indice2 = array();
      
      $sql2 = "SELECT NEXTVAL('detalle_registro_donacion_det_registro_donacion_id_seq'::regclass) AS sq2 ";
      
      if(!$rst2 = $this->ConexionBaseDatos($sql2)) return false;
          
      if(!$rst2->EOF)
      {
        $indice2 = $rst2->GetRowAssoc($ToUpper=false);
        $rst2->MoveNext();
      }
      
      $rst2->Close();
      
      $sqlerror2 = "SELECT setval('detalle_donante_detalle_donante_id_seq', ".($indice2['sq2']-1).") ";
      
      $sql2  = "INSERT INTO detalle_registro_donacion( "; 
      $sql2 .= "       det_registro_donacion_id, ";
      $sql2 .= "       aspecto_general, ";
      $sql2 .= "       brazos_lesion, ";
      $sql2 .= "       actividad_peligrosa, ";
      $sql2 .= "       flebotomia_brazo, ";
      $sql2 .= "       puncion, ";
      $sql2 .= "       fecha_registro, ";
      $sql2 .= "       hora, ";
      $sql2 .= "       codigo_donante, ";
      $sql2 .= "       usuario_id, ";
      $sql2 .= "       estado_donante_id, ";
      $sql2 .= "       causa_donacion_id, ";
      $sql2 .= "       observaciones_estado, ";
      $sql2 .= "       tiempo_estado ";
      $sql2 .= ")VALUES( ";
      $sql2 .= "       ".$indice2['sq2'].", ";
      $sql2 .= "       '".$request['aspecto']."', ";
      $sql2 .= "       '".$request['brazosLesion']."', ";
      $sql2 .= "       '".$request['actividad']."', ";
      $sql2 .= "       '".$request['flebotomia']."', ";
      $sql2 .= "       '".$request['puncion']."', ";
      $sql2 .= "       '".$request['fechaActual']."', ";
      $sql2 .= "       '".$request['horaActual']."', ";
      $sql2 .= "       ".$request['codDonante'].", ";
      $sql2 .= "       ".UserGetUID().", ";
      $sql2 .= "       ".$request['estadoDonacion'].", ";
      if($request['causas']!="" && $request['causas']!="0")
        $sql2 .= "     ".$request['causas'].", ";
      else
        $sql2 .= "     NULL, ";
      $sql2 .= "       '".$request['observaciones']."', ";
      $sql2 .= "       '".$tiempo_estado."' ";
      $sql2 .= ") ";
      
      if(!$rst2 = $this->ConexionTransaccion($sql2))
      {
        if(!$rst2 = $this->ConexionTransaccion($sqlerror2)) return false;
        return false;
      }
      
      $this->Commit();
      
      return $indice2['sq2'];
    }
    /**
    * Funcion donde se consulta la informacion del detalle del registro de donacion
    * 
    * @param integer $detRegDon codigo del registro de donacion
    * @return array $datos vector que contiene la informacion del detalle del registro
    * de donacion
    */
    function ConsultarDetRegDonacion($detRegDon)
    {
      //$this->debug=true;
      $sql  = "SELECT    drd.det_registro_donacion_id, drd.aspecto_general, ";
      $sql .= "          drd.brazos_lesion, drd.actividad_peligrosa, ";
      $sql .= "          drd.flebotomia_brazo, drd.puncion, drd.fecha_registro, ";
      $sql .= "          drd.hora, drd.usuario_id, drd.estado_donante_id, ";
      $sql .= "          drd.causa_donacion_id, drd.observaciones_estado, ";
      $sql .= "          drd.tiempo_estado, ed.descripcion as desc_estado, ";
      $sql .= "          cd.descripcion as desc_causa ";
      $sql .= "FROM      detalle_registro_donacion drd join estados_donante ed ";
      $sql .= "          on(drd.estado_donante_id=ed.estado_donante_id) ";     
      $sql .= "          left join causas_donacion cd ";
      $sql .= "          on(drd.causa_donacion_id=cd.causa_donacion_id) ";
      $sql .= "WHERE     det_registro_donacion_id = ".$detRegDon." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consulta la informacion del ultimo registro de tipo de bolsa
    * relacionado a un donante
    *
    * @param integer $codDonante codigo del donante
    * @return array datos contiene la informacion del registro de tipo de bolsa para un 
    * donante
    */
    function ConsultarTipoBolsaD($codDonante)
    {
      //$this->debug=true;
    
      $sql  = "SELECT    tbd.tipo_bolsa_donante_id,  tbd.tipo_bolsa_id, ";
      $sql .= "          tbd.otra_bolsa, tbd.fecha_registro, tbd.usuario_id, ";
      $sql .= "          tb.descripcion, su.usuario, su.nombre ";
      $sql .= "FROM      tipos_bolsas_donante tbd, tipos_bolsas tb, ";
      $sql .= "          system_usuarios su ";
      $sql .= "WHERE     tbd.tipo_bolsa_donante_id = ";
      $sql .= "          (SELECT  MAX(tbd.tipo_bolsa_donante_id) ";
      $sql .= "          FROM     tipos_bolsas_donante tbd ";
      $sql .= "          WHERE    codigo_donante = ".$codDonante.") ";
      $sql .= "          AND tbd.tipo_bolsa_id = tb.tipo_bolsa_id ";
      $sql .= "          AND tbd.usuario_id = su.usuario_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se consulta el registro de donacion teniendo en cuenta varios 
    * parametros de busqueda
    *
    * @param array $request vector con los datos del request
    * @param string $pg_siguiente 
    * @param string $sgrupo valor del subgrupo
    * @param string $sgrh valor del rh del subgrupo 
    */
    function ConsultarDonacionFiltro($request, $pg_siguiente, $sgrupo, $sgrh)
    {
      //$this->debug = true;
    
      $sql  = "SELECT    drd.fecha_registro, d.primer_nombre, d.segundo_nombre, ";
      $sql .= "          d.segundo_apellido, d.edad, d.sexo_id, d.grupo_sanguineo, ";
      $sql .= "          d.rh_gs, d.subgrupo_rh, d.rh_sg, drd.estado_donante_id, ";
      $sql .= "          ed.descripcion, d.primer_apellido, drd.det_registro_donacion_id, ";
      $sql .= "          d.codigo_donante, d.donante_id, d.tipo_id_donante, ";
      $sql .= "          drd.causa_donacion_id, drd.observaciones_estado, ";
      $sql .= "          drd.tiempo_estado ";
      $whr  = "FROM      donantes d, detalle_registro_donacion drd, estados_donante ed ";
      $whr .= "WHERE     d.codigo_donante = drd.codigo_donante ";
      $whr .= "          AND drd.estado_donante_id = ed.estado_donante_id ";
      if($request['cedula'])
        $whr .= "        AND d.donante_id = ".$request['cedula']." ";
      if($request['tipoId'] && $request['tipoId']!="-1")
        $whr .= "        AND d.tipo_id_donante = '".$request['tipoId']."' ";
      if($request['grupoSanguineo'] && $request['grupoSanguineo']!="-1")
        $whr .= "        AND d.grupo_sanguineo = '".$request['grupoSanguineo']."' ";
      if($request['factorRH'] && $request['factorRH']!="-1")
        $whr .= "        AND d.rh_gs = '".$request['factorRH']."' ";
      if($sgrupo)
        $whr .= "        AND d.subgrupo_rh = '".$sgrupo."' ";
      if($sgrh)
        $whr .= "        AND d.rh_sg = '".$sgrh."' ";
      if($request['fechaInicio'])
        $whr .= "        AND drd.fecha_registro >= '".$request['fechaInicio']."' ";
      if($request['fechaFin'])
        $whr .= "        AND drd.fecha_registro <= '".$request['fechaFin']."' ";
      $whr1  = "ORDER BY drd.fecha_registro DESC, d.primer_nombre, d.segundo_nombre, "; 
      $whr1 .= "         d.segundo_apellido, d.edad, d.sexo_id, d.grupo_sanguineo, ";
      $whr1 .= "         d.rh_gs, d.subgrupo_rh, d.rh_sg, d.estado_donante_id, ";
      $whr1 .= "         ed.descripcion, d.primer_apellido, drd.det_registro_donacion_id, ";
      $whr1 .= "         d.codigo_donante ";
         
      if(!$this->ProcesarSqlConteo("SELECT COUNT(*) $whr",$pg_siguiente,null,50))
        return false;
      
      $whr2  = "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql.$whr.$whr1.$whr2))
        return false;
      
      $datos = array();
      while (!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    
    function ConsultarIdCodDon($cod_don)
    {
      //$this->debug=true;
      $codigo = explode('-',$cod_don);
      $fecha = $codigo[0];
      $cod = $codigo[1];
      
      $sql  = "SELECT   donante_id, tipo_id_donante ";
      $sql .= "FROM     donantes ";
      $sql .= "WHERE    codigo_donante=".$cod." ";
      $sql .= "         AND TO_CHAR(fecha_registro,'MMDD')='".$fecha."' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    
    function ConsultarTipoProductoFrac()
    {
      $sql  = "SELECT   * ";
      $sql .= "FROM     tipos_productos_frac ";
      $sql .= "WHERE    sw_activo='1' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    
    function IngresarFracSangre($request)
    {
      //$this->debug = true;
      $indice = array();
      
      $this->ConexionTransaccion();
      
      $sql = "SELECT nextval('det_frac_sangre_det_frac_sangre_id_seq'::regclass) AS sq ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
          
      if(!$rst->EOF)
      {
        $indice = $rst->GetRowAssoc($ToUpper=false);
        $rst->MoveNext();
      }
      
      $rst->Close();
      
      $sqlerror = "SELECT setval('det_frac_sangre_det_frac_sangre_id_seq', ".($indice['sq']-1).") ";
      
      $sql  = "INSERT INTO det_frac_sangre( ";
      $sql .= "       det_frac_sangre_id, ";
      $sql .= "       leucorreducidos, ";
      $sql .= "       irradiados, ";
      $sql .= "       fecha_hora_frac, ";
      $sql .= "       fecha_caducidad, ";
      $sql .= "       cantidad, ";
      $sql .= "       observacion, ";
      $sql .= "       codigo_donante, ";
      $sql .= "       tipo_producto_frac_id, ";
      $sql .= "       responsable_id, ";
      $sql .= "       usuario_id ";
      $sql .= ")VALUES( ";
      $sql .= "       ".$indice['sq'].", ";
      $sql .= "       '".$request['leucorreducidos']."', ";
      $sql .= "       '".$request['irradiados']."', ";
      $sql .= "       '".$request['fechaHoraFrac']."', ";
      $sql .= "       '".$request['fechaCaducidad']."', ";
      $sql .= "       ".$request['cantidad'].", ";
      $sql .= "       '".$request['observacion']."', ";
      $sql .= "       ".$request['codDonante'].", ";
      $sql .= "       ".$request['tipoProducto'].", ";
      $sql .= "       1, ";
      $sql .= "       ".UserGetUID()." ";
      $sql .= "       ) ";
      
      if(!$rst = $this->ConexionTransaccion($sql))
      {
        if(!$rst = $this->ConexionTransaccion($sqlerror)) return false;
        return false;
      }
      
      $this->Commit();
      
      return $indice['sq'];
    }
    
    function ConsultarDetFracSang($det_frac)
    {
      $sql  = "SELECT   dfs.det_frac_sangre_id, dfs.leucorreducidos, dfs.irradiados, ";
      $sql .= "         dfs.fecha_hora_frac, dfs.cantidad, dfs.observacion, ";
      $sql .= "         dfs.tipo_producto_frac_id, dfs.responsable_id, dfs.usuario_id, ";
      $sql .= "         dfs.codigo_donante, tpf.descripcion as desc_prod_frac, ";
      $sql .= "         dfs.fecha_caducidad ";
      $sql .= "FROM     det_frac_sangre dfs, tipos_productos_frac tpf ";
      $sql .= "WHERE    dfs.det_frac_sangre_id=".$det_frac." AND ";
      $sql .= "         dfs.tipo_producto_frac_id=tpf.tipo_producto_frac_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    
    function ConsultarFracSangFiltro($request, $pg_siguiente)
    {
      //$this->debug=true;
    
      $sql  = "SELECT    TO_CHAR(dfs.fecha_hora_frac, 'DD/MM/YYYY') as fecha_hf, ";
      $sql .= "          dfs.leucorreducidos, dfs.irradiados, ";
      $sql .= "          dfs.det_frac_sangre_id, dfs.cantidad, dfs.observacion, ";
      $sql .= "          dfs.tipo_producto_frac_id, dfs.responsable_id, dfs.usuario_id, ";
      $sql .= "          dfs.codigo_donante, tpf.descripcion as desc_prod_frac, ";
      $sql .= "          TO_CHAR(dfs.fecha_caducidad, 'DD/MM/YYYY') as fecha_c, ";
      $sql .= "          d.donante_id, d.tipo_id_donante ";
      $whr  = "FROM      det_frac_sangre dfs, tipos_productos_frac tpf, donantes d ";
      $whr .= "WHERE     d.codigo_donante = dfs.codigo_donante ";
      $whr .= "          AND dfs.tipo_producto_frac_id = tpf.tipo_producto_frac_id ";
      if($request['tipoProducto'] && $request['tipoProducto']!="-1")
        $whr .= "        AND dfs.tipo_producto_frac_id = ".$request['tipoProducto']." ";
      if($request['fechaInicio'])
        $whr .= "        AND dfs.fecha_hora_frac::date>='".$request['fechaInicio']."'::date ";
      if($request['fechaFin'])
        $whr .= "        AND dfs.fecha_hora_frac::date<='".$request['fechaFin']."'::date ";
      $whr1  = "ORDER BY  dfs.fecha_hora_frac DESC, dfs.leucorreducidos, ";
      $whr1 .= "          dfs.irradiados, dfs.det_frac_sangre_id, ";
      $whr1 .= "          dfs.cantidad, dfs.observacion, ";
      $whr1 .= "          dfs.tipo_producto_frac_id, dfs.responsable_id, dfs.usuario_id, "; 
      $whr1 .= "          dfs.codigo_donante, tpf.descripcion, ";
      $whr1 .= "          dfs.fecha_caducidad, d.donante_id, d.tipo_id_donante ";
      
      if(!$this->ProcesarSqlConteo("SELECT COUNT(*) $whr",$pg_siguiente,null,50))
        return false;
      
      $whr2  = "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql.$whr.$whr1.$whr2))
        return false;
      
      $datos = array();
      while (!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    
    function ConsultarProcedencias()
    {
      $sql  = "SELECT   * ";
      $sql .= "FROM     procedencias ";
      $sql .= "WHERE    sw_activo='1' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    
    function ConsultarResponsables($tipo_prof)
    {
      //$this->debug = true;
      $sql  = "SELECT    p.tipo_id_tercero, p.tercero_id, p.nombre, p.tipo_profesional ";
      $sql .= "FROM      profesionales p ";
      $sql .= "WHERE     p.tipo_profesional=".$tipo_prof." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    
    function IngresarHemocomponentes($request)
    {
      //$this->debug=true;
      if(!$request['subgrupoRH'])
      {
        $sgrupo = "";
        $sgrh = "";
      }else{
        $sg = explode("/", $request['subgrupoRH']);
        
        if(sizeof($sg)==2)
        {
          $sgrupo = $sg[0];
          $sgrh = $sg[1];
        }
      }
      
      if($request['responsable'])
      {
        $resp = explode("/", $request['responsable']);
        if(sizeof($resp)==2)
        {
          $tercero_id = $resp[0];
          $tipo_id = $resp[1];
        }
      }
      $indice = array();
      
      $this->ConexionTransaccion();
      
      $sql = "SELECT NEXTVAL('hemocomponentes_otros_bancos_hemocomponente_id_seq'::regclass) AS sq ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
        
      if(!$rst->EOF)
      {
        $indice = $rst->GetRowAssoc($ToUpper=false);
        $rst->MoveNext();
      }
      
      $rst->Close();
      
      $sqlerror = "SELECT setval('hemocomponentes_otros_bancos_hemocomponente_id_seq', ".($indice['sq']-1).") ";
    
      $sql  = "INSERT INTO hemocomponentes_otros_bancos( ";
      $sql .= "       hemocomponente_id, ";
      $sql .= "       fecha_hora_actual, ";
      $sql .= "       fecha_extraccion, ";
      $sql .= "       procedencia_id, ";
      $sql .= "       otros, ";
      $sql .= "       cod_procedencia, ";
      $sql .= "       grupo_sanguineo, ";
      $sql .= "       rh_gs, ";
      if($request['subgrupoRH'])
      {
        $sql .= "     subgrupo_rh, ";
        $sql .= "     rh_sg, ";
      }
      $sql .= "       fecha_caducidad, ";
      $sql .= "       temperatura, ";
      $sql .= "       tipo_producto_frac_id, ";
      $sql .= "       cantidad, ";
      $sql .= "       observacion, ";
      $sql .= "       tipo_id_tercero, ";
      $sql .= "       tercero_id, ";
      $sql .= "       usuario_id ";
      $sql .= ")VALUES( ";
      $sql .= "       ".$indice['sq'].", ";
      $sql .= "       '".$request['fechaActual']." ".$request['horaActual']."', ";
      $sql .= "       '".$request['fechaExtraccion']."', ";
      $sql .= "       ".$request['procedencia'].", ";
      $sql .= "       '".$request['otros']."', ";
      $sql .= "       '".$request['codProcedencia']."', ";
      $sql .= "       '".$request['grupoSanguineo']."', ";
      $sql .= "       '".$request['factorRH']."', ";
      if($request['subgrupoRH'])
      {
        $sql .= "     '".$sgrupo."', ";
        $sql .= "     '".$sgrh."', ";
      }
      $sql .= "       '".$request['fechaCaducidad']."', ";
      $sql .= "       ".$request['temperatura'].", ";
      $sql .= "       ".$request['tipoProducto'].", ";
      $sql .= "       ".$request['cantidad'].", ";
      $sql .= "       '".$request['observacion']."', ";
      $sql .= "       '".$tipo_id."', ";
      $sql .= "       '".$tercero_id."', ";
      $sql .= "       ".UserGetUID().") ";
      
      if(!$rst = $this->ConexionTransaccion($sql))
      {
        if(!$rst = $this->ConexionTransaccion($sqlerror)) return false;
        return false;
      }
      
      $rst->Close();
      
      $this->Commit();
      
      return $indice['sq'];
    }
  }
?>