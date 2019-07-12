<?php
  class DMLs_pqrs extends ConexionBD
  {
    /*********************************
    * Constructor
    *********************************/
    function DMLs_pqrs(){}
		 


     /**************************************************************************************
	* Insertar informacion de casos PQRS
	* @return boolean
	***************************************************************************************/   
    function insertar_caso($datos)
	{
	 
	 $sql  = "INSERT INTO esm_registro_pqrs ";
	 $sql .= "                   ( ";
	 $sql .= "       	         registro_pqrs_id, ";
	 $sql .=	"                    empresa_id, ";
	 $sql .= "	                 bodega, ";
	 $sql .= "	                 responsable_solucion, ";
	 $sql .= "	                 prioridad, ";
	 $sql .= "	                 estado_caso, ";
	 $sql .=	"                    paciente_id, ";
	 $sql .= "	                 fuerza_id, ";
	 $sql .= "	                 nombres, ";
	 $sql .= "	                 apellidos, ";
	 $sql .= "	                 genero, ";
	 $sql .= "	                 fecha_nacimiento, ";
	 $sql .= "	                 direccion, ";
	 $sql .= "	                 tel_casa, ";
	 $sql .= "	                 num_celular, ";
	 $sql .= "	                 email_paciente, ";
	 $sql .= "	                 categoria_caso, ";
	 $sql .= "	                 fecha_registro, ";
	 $sql .= "	                 usuario_id ";
	 $sql .= "	                 ) ";
	 $sql .= "	                  VALUES ";
	 $sql .= "	                 ( ";
	 $sql .= "	                  DEFAULT, ";
	 $sql .= "	                  '".$datos['empresa']."', ";
	 $sql .= "	                  '".$datos['farmacia']."', ";
	 $sql .= "	                  '".$datos['resp_caso']."', ";
	 $sql .= "	                  '".$datos['prioridad']."', ";
	 $sql .= "	                  '".$datos['estado_caso']."', ";
	 $sql .= "	                  '".$datos['cedula']."', ";
	 $sql .= "	                  '".$datos['fuerza']."', ";
	 $sql .= "	                  '".$datos['nombres']."', ";
	 $sql .= "	                  '".$datos['apellidos']."', ";
	 $sql .= "	                  '".$datos['sexo']."', ";
	 $sql .= "	                  '".$datos['fecha_naci']."'::date, ";
	 $sql .= "	                  '".$datos['direccion']."', ";
	 $sql .= "	                  '".$datos['telefono']."', ";
	 $sql .= "	                  '".$datos['celular']."', ";
	 $sql .= "	                  '".$datos['email']."', ";
	 $sql .= "	                   ".$datos['categoria'].", ";
	 $sql .= "	                   now(), ";
	 $sql .= "	                   ".UserGetUID()." ";
	 $sql .= "	                   ) RETURNING registro_pqrs_id ;  ";
	 
	 //echo "sql: ".$sql;
	 
     if(!$rst = $this->ConexionBaseDatos($sql))
        return false;	 
     
	 $id = array();
	 while(!$rst->EOF)
	 {
	  $id = $rst->GetRowAssoc($ToUpper = false);
	  $rst->MoveNext();
	 }
      $rst->Close();
	 
	 $sql  = "INSERT INTO esm_registro_pqrs_d ";
	 $sql .= "                   ( ";
	 $sql .= "                     registro_pqrs_d_id, ";
	 $sql .= "                     id_caso, ";
	 $sql .= "                     observacion, ";
	 $sql .= "                     fecha_registro, ";
	 $sql .= "                     usuario_id ";
	 $sql .= "                    ) ";
	 $sql .= "                     VALUES ";
	 $sql .= "                    ( ";
	 $sql .= "                     DEFAULT, ";
	 $sql .= "                     ".$id['registro_pqrs_id'].", ";
	 $sql .= "                     '".$datos['observacion']."', ";
	 $sql .= "                     now(), ";
	 $sql .= "                     ".UserGetUID()." ";
	 $sql .= "                     ); ";
	 
	 //echo "sql: ".$sql;	 
	 
     if(!$recordset = $this->ConexionBaseDatos($sql))
        return false;		
	 
     $recordset->Close();
     
	 
	 return true;
	}

  
     /**************************************************************************************
	* Listado de informacion de casos PQRS
	* @return boolean
	***************************************************************************************/
    function Listar_datosPqrsAct($filtros,$offset)
	{
	 /*$this->debug=true;*/
	 if($filtros['caso']!="")
		 $where .= " AND erp.registro_pqrs_id = ".$filtros['caso']." ";
     
     $sql  = "        SELECT         ";         
     $sql .=	"					   erp.registro_pqrs_id as caso, ";     
     $sql .=	"					   erp.empresa_id,  ";     
     $sql .=	"					   g.descripcion as bodega, ";     
     $sql .=	"                      su.nombre as responsable_caso, ";     
     $sql .=	"	                    CASE WHEN erp.prioridad = '1' THEN 'ALTA' ELSE CASE WHEN erp.prioridad = '2'  ";     
     $sql .=	"                       THEN 'MEDIA' ELSE 'BAJA' END END AS prioridad, ";     
     $sql .=	"						ec.estado AS estado_caso, ";     
     $sql .=	"						erp.paciente_id, ";     
     $sql .=	"						tf.descripcion AS fuerza, ";     
     $sql .=	"						erp.nombres || ' ' || erp.apellidos AS nombre_pac, ";     
     $sql .=	"						cc.tipo_categoria AS categoria_caso, ";     
     $sql .=	"						erpd.observacion, ";     
     $sql .=	"						erp.fecha_registro as fecha_caso, ";     
     $sql .=	"						erpd.fecha_registro as fecha_seguim, ";     
     $sql .=	"						erp.usuario_id ";     
     $sql .=	"			 FROM esm_registro_pqrs erp, ";     
     $sql .=	"						esm_registro_pqrs_d erpd, ";     
     $sql .=	"						system_usuarios_farmacias su, ";     
     $sql .=	"                        esm_tipos_fuerzas tf, ";     
     $sql .=	"						pqrs_estado_casos ec, ";     
     $sql .=	"						pqrs_categoria_casos cc, ";     
     $sql .=	"						bodegas g ";     
     $sql .=	"          WHERE erp.registro_pqrs_id = erpd.id_caso ";     
     $sql .=	"              AND  erp.responsable_solucion = su.usuario_id ";     
     $sql .=	"              AND  erp.fuerza_id = tf.codigo_fuerza ";     
     $sql .=	"              AND  erp.estado_caso = ec.estado_caso_id ";     
     $sql .=	"              AND  erp.categoria_caso = cc.categoria_id ";     
     $sql .=	"              AND  erp.empresa_id = g.empresa_id AND erp.bodega = g.bodega ";     
	 $sql .= "               ".$where." ";
      
	 $cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
	 $this->ProcesarSqlConteo($cont,$offset);
	 
	 $sql .= "ORDER BY 1 ASC ";
	 $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
     
     if(!$rst = $this->ConexionBaseDatos($sql))
        return false;	 
     
	 $datos = array();
	 while(!$rst->EOF)
	 {
	  $datos[ ] = $rst->GetRowAssoc($ToUpper = false);
	  $rst->MoveNext();
	 }
      $rst->Close();	 
	 
	 return $datos;
	}
	
	
     /**************************************************************************************
	* Lista Datos de un caso Pqrs especifico / query minimo
	* @return boolean
	***************************************************************************************/
    function Listar_CasosUpd($caso)
	{
     
     $sql  = "          SELECT                     ";             
     $sql .=	"						observacion,   ";     
     $sql .=	"						fecha_registro ";  
     $sql .=	"			 FROM  ";     
     $sql .=	"						esm_registro_pqrs_d  ";     
     $sql .=	"          WHERE id_caso = ".$caso.";";	
	 
     if(!$rst = $this->ConexionBaseDatos($sql))
        return false;	 
     
	 $datos = array();
	 while(!$rst->EOF)
	 {
	  $datos[ ] = $rst->GetRowAssoc($ToUpper = false);
	  $rst->MoveNext();
	 }
      $rst->Close();	 
	 
	 return $datos;	
	}

     /**************************************************************************************
	* Actualizar casos Pqrs
	* @return boolean
	***************************************************************************************/
    function ActualizarCasoPqrs($numcaso,$empresa,$observ,$cerrar)
    {
	 
	 $T = 0;
	 $sql  = "INSERT INTO esm_registro_pqrs_d ";
	 $sql .= "                    ( ";
	 $sql .= "                      registro_pqrs_d_id, ";
	 $sql .= "                      id_caso, ";
	 $sql .= "                      observacion, ";
	 $sql .= "                      fecha_registro, ";
	 $sql .= "                      usuario_id ";
	 $sql .= "                     ) ";
	 $sql .= "                      VALUES ";
	 $sql .= "                     ( ";
	 $sql .= "                      DEFAULT, ";
	 $sql .= "                      ".$numcaso.", ";
	 $sql .= "                      '".$observ."', ";
	 $sql .= "                      now(), ";
	 $sql .= "                      ".UserGetUID()." ";
	 $sql .= "                      ) ;";
	
	 if(!$rst = $this->ConexionBaseDatos($sql))
        {return false;}	
	    else 
		  { $T++;}

     if($cerrar)
	 {
	  $sql  = "UPDATE esm_registro_pqrs ";
	  $sql .= "       SET estado_caso = 4 ";
	  $sql .= " WHERE  registro_pqrs_id = ".$numcaso."; ";

	  if(!$rst = $this->ConexionBaseDatos($sql))
         {return false;}	
	      else 
		    { $T++;}
	  
	 }

     if($T>0)
	    return true;
		else 
		return false;
		
	 
	}

	
  }
?>