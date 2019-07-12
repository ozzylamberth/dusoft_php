<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Afiliaciones.class.php,v 1.31 2008/09/01 20:42:45 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase: EventosSoat
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.31 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class EventosSoat extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function EventosSoat(){}
    /**
    * Funcion donde se obtienen los ingresos relacionados a un evento
    * 
    * @param integer $evento identificador del evento
    * @param string $tipo_doc Tipo de documento
    * @param string $documento Numero del documento
    *
    * @return boolean
    */
    function ObtenerIngresosEvento($evento,$tipo_doc,$documento)
    {
      $sql  = "SELECT IA.ingreso ";
      $sql .= "FROM   ingresos_soat IA, ";
      $sql .= "       ingresos IG ";
      $sql .= "WHERE  IA.evento = ".$evento." ";
      $sql .= "AND    IG.paciente_id = ".$documento." ";
      $sql .= "AND    IG.tipo_id_paciente = '".$tipo_doc."' ";
      $sql .= "AND    IG.ingreso = IA.ingreso ";
      
      $datos = array();
      if(!$rst = $this->ConexionBaseDatos($sql))
          return false;

      while(!$rst->EOF)
      {
        $datos[] =  $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();

      return $datos;
    }    
    /**
    * Funcion donde se obtienen los permisos de usuario
    * 
    * @param integer $usuario identificador del usuario
    *
    * @return mixed
    */
    function ObtenerPermisos($usuario)
    {
      $sql  = "SELECT A.empresa_id,";
      $sql .= "       B.razon_social AS descripcion1,";
      $sql .= "       A.centro_utilidad,";
      $sql .= "       C.descripcion AS descripcion2 ";
      $sql .= "FROM   userpermisos_soat AS A,";
      $sql .= "       empresas AS B,";
      $sql .= "       centros_utilidad AS C ";
      $sql .= "WHERE  A.usuario_id=".$usuario." ";
      $sql .= "AND    A.empresa_id=B.empresa_id ";
      $sql .= "AND    A.centro_utilidad=C.centro_utilidad ";
      $sql .= "AND    A.empresa_id=C.empresa_id ";
      
      $datos = array();
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      while(!$rst->EOF)
      {
        $datos[$rst->fields[1]][$rst->fields[3]]=$rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();

      return $datos;
    }
  }
?>