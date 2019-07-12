<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultasCrearProductos.class.php,v 1.8 2010/01/26 18:17:44 sandra Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.8 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */ 



 class ConsultasCrearProductos extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function ConsultasCrearproductos(){}

  
  
  //Para el Buscador
  //Busqueda Por ClasesXGrupos
  function ListadoClasesxGrupo($CodigoGrupo)
{

          $sql="
          SELECT 
          cla.clase_id,
          cla.descripcion
          FROM 
            inv_clases_inventarios cla
          where
          cla.grupo_id = '".$CodigoGrupo."'
          and
          cla.sw_tipo_empresa='".$_REQUEST['datos']['sw_tipo_empresa']."'
          ";
          
     $sql .= "ORDER BY cla.clase_id ;";
      
	//$this->debug=true;
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
}
  
function ListadoSubClasesConClase($CodigoGrupo,$CodigoClase)
{

          $sql="
            select
            sub.subclase_id,
            sub.descripcion
            from
            inv_subclases_inventarios sub
            where
            sub.grupo_id = '".$CodigoGrupo."'
            and
            sub.clase_id = '".$CodigoClase."'
            ";
            
      
    $sql .= "ORDER BY sub.subclase_id; ";
      
            
            
 //$this->debug=true;
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
}  
  
    
  function BusquedaTitular($Nombre)
  {
  $sql="
            select
            titular_reginvima_id as codigo,
            descripcion
            from
            inv_titulares_reginvima
            where
            descripcion ILike '%".$Nombre."%';";
 //$this->debug=true;
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
  
  
  }


  
  function BusquedaFabricante($Nombre)
  {
    $sql = "select fabricante_id as codigo,
                   descripcion
            from   inv_fabricantes
            where  descripcion ILike '%".$Nombre."%'
            ORDER BY descripcion ";
 //$this->debug=true;
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
  
  
  }

  
  
  
  
  
  
  
  
  
  function BuscarAcuerdo228($Codigo)
  {
  $sql="
            select
            cod_acuerdo228_id as codigo,
            descripcion
            from
            inv_codigo_acuerdo_228
            where
            cod_acuerdo228_id = '".$Codigo."';";
 //$this->debug=true;
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
  
  
  }
  
  
  function BuscarMensaje($Codigo)
  {
  $sql="
            select
            mensaje_id as codigo,
            descripcion
            from
            inv_mensajes_producto
            where
            mensaje_id = '".$Codigo."';";
 //$this->debug=true;
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
  
  
  }
  
  
  
  
  
  function ListaEspecialidadxProducto_($CodigoProducto,$offset)
  {
  $sql="
            select 
                  esp.especialidad,
                  esp.descripcion
                  from 
                  especialidades esp
                  where
                  esp.especialidad Not In
                  (
                  select 
                  espx.especialidad
                  from
                  inv_especialidad_x_producto espx
                  where
                  espx.codigo_medicamento <> '".$CodigoProducto."'
                  )";
 
if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        
    $sql .= "ORDER BY lab.laboratorio_id ";
      $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";  


  if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
  
  
  }
  
  
  //para Asignar Nivel de Atencion y Uso a un Medicamento
  function Asignar($Tabla,$Valor,$CodigoProducto,$Campos)
  {
  
  $ProductoxNivel=$CodigoProducto."".$Valor;
  //$this->debug=true;
    $sql .= "INSERT INTO ".$Tabla." ( ";
    $sql .= $Campos;
    $sql .= "       ) ";
    $sql .= "VALUES ( ";
    $sql .= "        '".$ProductoxNivel."',";
    $sql .= "        '".$Valor."',";
    $sql .= "        '".$CodigoProducto."'";
    $sql .= "       ); ";
    
  
  
  
 if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
  
  
  
  }
  
  
  //para Asignar Nivel de Atencion y Uso a un Medicamento
  function Borrar($Tabla,$Valor,$CodigoProducto,$Campo)
  {
  
  $ProductoxNivel=$CodigoProducto."".$Valor;
  //$this->debug=true;
    $sql .= "DELETE FROM ".$Tabla." ";
    $sql .= "where  ";
    $sql .= "        ".$Campo;
    $sql .= "        =";
    $sql .= "        '".$ProductoxNivel."'";
    $sql .= "; ";
    
  
  
  
 if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
  
  
  
  }
  




	function Modificar_ProductoMedicamento($Formulario_Productos)
	{

	$sql  = "UPDATE medicamentos";
	$sql .= "       SET ";
	/*$sql .= "       via_administracion_id =";
	$sql .= "        '".$Formulario_Productos['via_administracion_id']."',";*/
	$sql .= "       sw_fotosensible =";
	$sql .= "        '".$Formulario_Productos['sw_manejo_luz']."',";
	$sql .= "       cod_forma_farmacologica =";
	$sql .= "        '".$Formulario_Productos['cod_forma_farmacologica']."',";
	$sql .= "       concentracion_forma_farmacologica =";
	$sql .= "        '".$Formulario_Productos['concentracion']."',";   
	$sql .= "       cod_principio_activo =";
	$sql .= "        '".$Formulario_Productos['cod_principio_activo']."',";   
	$sql .= "       cod_concentracion =";
	$sql .= "        '".$Formulario_Productos['cod_concentracion']."',";   
	$sql .= "       sw_liquidos_electrolitos =";
	$sql .= "        '".$Formulario_Productos['sw_liquidos_electrolitos']."',";
	$sql .= "       sw_uso_controlado =";
	$sql .= "        '".$Formulario_Productos['sw_uso_controlado']."',";
	$sql .= "       sw_antibiotico =";
	$sql .= "        '".$Formulario_Productos['sw_antibiotico']."',";
	$sql .= "       sw_refrigerado =";
	$sql .= "        '".$Formulario_Productos['sw_refrigerado']."',";
	$sql .= "       sw_alimento_parenteral =";
	$sql .= "        '".$Formulario_Productos['sw_alimento_parenteral']."',";    
	$sql .= "       sw_alimento_enteral =";
	$sql .= "        '".$Formulario_Productos['sw_alimento_enteral']."',";
	$sql .= "       dias_previos_vencimiento =";
	$sql .= "        '".$Formulario_Productos['dias_previos_vencimiento']."',";
	//campos que vienen a modificar de medicamentos Cosmitet
	$sql .= "       cod_anatomofarmacologico =";
	$sql .= "        '".$Formulario_Productos['cod_anatofarmacologico']."',";
	$sql .= "       sw_pos =";
	$sql .= "        '".$Formulario_Productos['sw_pos']."',";
	$sql .= "       codigo_cum =";
	$sql .= "        '".$Formulario_Productos['codigo_cum']."',";
	$sql .= "       unidad_medida_medicamento_id =";
	$sql .= "        '".$Formulario_Productos['unidad_id']."',";
	$sql .= "       sw_farmacovigilancia =";
	$sql .= "        '".$Formulario_Productos['sw_farmacovigilancia']."',";
	$sql .= "       descripcion_alerta =";
	$sql .= "        '".$Formulario_Productos['descripcion_alerta']."',";
	$sql .= "       usuario_id =";
	$sql .= "        ".UserGetUID().",";
	$sql .= "       fecha_registro = ";
	$sql .= "        NOW() ";
	$sql .= " where ";
	$sql .= "codigo_medicamento =";			
	$sql .= "        '".$Formulario_Productos['codigo_producto']."';";

	// $this->debug=true;

	if(!$rst = $this->ConexionBaseDatos($sql)) 
	return false;
	else
	return true;

	$rst->Close();

	}


	//Insertar Datos de Insumos/Medicamentos a la Base de datos
  function Modificar_ProductoInsumo($Formulario_Productos)
  {
    /*$this->debug=true;*/
	$sql  = "UPDATE inventarios_productos";
	$sql .= "       SET ";
	$sql .= "       descripcion =";
	$sql .= "        '".$Formulario_Productos['descripcion']."',";
	$sql .= "       descripcion_abreviada =";
	$sql .= "        '".$Formulario_Productos['descripcion_abreviada']."',";
	$sql .= "       codigo_cum =";
	$sql .= "        '".$Formulario_Productos['codigo_cum']."',";
	$sql .= "       codigo_alterno =";
	$sql .= "        '".$Formulario_Productos['codigo_alterno']."',";
	$sql .= "       codigo_barras =";
	$sql .= "        '".$Formulario_Productos['codigo_barras']."',";
	$sql .= "       fabricante_id =";
	$sql .= "        '".$Formulario_Productos['fabricante_id']."',";
	$sql .= "       sw_pos =";
	$sql .= "        '".$Formulario_Productos['sw_pos']."',";
	$sql .= "       cod_acuerdo228_id =";
	$sql .= "        '".$Formulario_Productos['cod_acuerdo228_id']."',";
	/*$sql .= "       cod_forma_farmacologica =";
	$sql .= "        '".$Formulario_Productos['cod_forma_farmacologica']."',";*/
	$sql .= "       unidad_id =";
	$sql .= "        '".$Formulario_Productos['unidad_id']."',";
	$sql .= "       contenido_unidad_venta =";
	$sql .= "        '".$Formulario_Productos['cantidad']."',";
	$sql .= "       cod_anatofarmacologico =";
	$sql .= "        '".$Formulario_Productos['cod_anatofarmacologico']."',";
	$sql .= "       mensaje_id =";
	$sql .= "        '".$Formulario_Productos['mensaje_id']."',";
	$sql .= "       codigo_mindefensa =";
	$sql .= "        '".$Formulario_Productos['codigo_mindefensa']."',";
	$sql .= "       codigo_invima =";
	$sql .= "        '".$Formulario_Productos['codigo_invima']."',";
	$sql .= "       vencimiento_codigo_invima =";
	$sql .= "        '".$Formulario_Productos['vencimiento_codigo_invima']."',";
	$sql .= "       titular_reginvima_id =";
	$sql .= "        '".$Formulario_Productos['titular_reginvima_id']."',";
	$sql .= "       porc_iva =";
	$sql .= "        '".$Formulario_Productos['porc_iva']."',";
	$sql .= "       sw_generico =";
	$sql .= "        '".$Formulario_Productos['sw_generico']."',";
	$sql .= "       sw_venta_directa =";
	$sql .= "        '".$Formulario_Productos['sw_venta_directa']."',";
	$sql .= "       tipo_pais_id =";
	$sql .= "        '".$Formulario_Productos['tipo_pais_id']."',";     
	$sql .= "       tipo_producto_id =";
	$sql .= "        '".$Formulario_Productos['tipo_producto_id']."',";
	$sql .= "       presentacioncomercial_id =";
	$sql .= "        '".$Formulario_Productos['presentacioncomercial_id']."',";  
	$sql .= "       cantidad =";
	$sql .= "        '".$Formulario_Productos['cantidad_p']."', ";   
	$sql .= "       tratamiento_id =";
	$sql .=         (($Formulario_Productos['tratamiento_id'])? $Formulario_Productos['tratamiento_id']:"NULL" ); 
	$sql .= "		,usuario_id = ".UserGetUID()."";
	$sql .= "		,fecha_registro = NOW(), ";
	$sql .= "       cod_adm_presenta =";
	$sql .= "        '".$Formulario_Productos['cod_presenta']."' ";
	$sql .= "		where ";
	$sql .= "codigo_producto =";			
	$sql .= "        '".$Formulario_Productos['codigo_producto']."';";
	//$this->debug=true;
	if(!$rst = $this->ConexionBaseDatos($sql)) 
	return false;
	else
	return true;

	$rst->Close();
	}
  
  
  
  
  
  
  
  
  
  //Insertar Datos de Insumos/Medicamentos a la Base de datos
  function Insertar_ProductoInsumo($Formulario_Productos)
  {
   /* $this->debug=true;*/
    //print_r()
    
    $codigo_barras=eregi_replace("'","-",$Formulario_Productos['codigo_barras']);
    $sql  = "INSERT INTO inventarios_productos (";
    $sql .= "       grupo_id, ";
    $sql .= "       clase_id, ";
    $sql .= "       subclase_id,";
    $sql .= "       producto_id,";
    $sql .= "       descripcion,";
    $sql .= "       descripcion_abreviada,";
    $sql .= "       codigo_producto,";
    $sql .= "       codigo_cum,";
    $sql .= "       codigo_alterno,";
    $sql .= "       codigo_barras,";
    $sql .= "       fabricante_id,";
    $sql .= "       sw_pos,";
    $sql .= "       cod_acuerdo228_id,";
    $sql .= "       unidad_id,";
    $sql .= "       contenido_unidad_venta,";
    $sql .= "       cod_anatofarmacologico,";
    $sql .= "       mensaje_id,";
    $sql .= "       codigo_mindefensa,";
    $sql .= "       codigo_invima,";
    $sql .= "       vencimiento_codigo_invima,";
    $sql .= "       titular_reginvima_id,";
    $sql .= "       porc_iva,";
    $sql .= "       sw_generico,";
    $sql .= "       sw_venta_directa,";
    $sql .= "       tipo_pais_id,";
    $sql .= "       tipo_producto_id,";
    $sql .= "       presentacioncomercial_id,";
    $sql .= "       cantidad,";
    $sql .= "       tratamiento_id,";
    $sql .= "       usuario_id,";
    $sql .= "       cod_adm_presenta";
	  $sql .= "       ) ";
    $sql .= "VALUES ( ";
    $sql .= "        '".$Formulario_Productos['grupo_id']."',";
    $sql .= "        '".$Formulario_Productos['clase_id']."',";
    $sql .= "        '".$Formulario_Productos['subclase_id']."',";
    $sql .= "        '".$Formulario_Productos['producto_id']."',";
    $sql .= "        '".$Formulario_Productos['descripcion']."',";
    $sql .= "        '".$Formulario_Productos['descripcion_abreviada']."',";
    $sql .= "        '".$Formulario_Productos['codigo_producto']."',";
    $sql .= "        '".$Formulario_Productos['codigo_cum']."',";
    $sql .= "        '".$Formulario_Productos['codigo_alterno']."',";
    $sql .= "        '".$codigo_barras."',";
    $sql .= "        '".$Formulario_Productos['fabricante_id']."',";
    $sql .= "        '".$Formulario_Productos['sw_pos']."',";
    $sql .= "        '".$Formulario_Productos['cod_acuerdo228_id']."',";
    $sql .= "        '".$Formulario_Productos['unidad_id']."',";
    $sql .= "        '".$Formulario_Productos['cantidad']."',";
    $sql .= "        '".$Formulario_Productos['cod_anatofarmacologico']."',";
    $sql .= "        '".$Formulario_Productos['mensaje_id']."',";
    $sql .= "        '".$Formulario_Productos['codigo_mindefensa']."',";
    $sql .= "        '".$Formulario_Productos['codigo_invima']."',";
    $sql .= "        '".$Formulario_Productos['vencimiento_codigo_invima']."',";
    $sql .= "        '".$Formulario_Productos['titular_reginvima_id']."',";
    $sql .= "        '".$Formulario_Productos['porc_iva']."',";
    $sql .= "        '".$Formulario_Productos['sw_generico']."',";
    $sql .= "        '".$Formulario_Productos['sw_venta_directa']."',";
    $sql .= "        '".$Formulario_Productos['tipo_pais_id']."',";
    $sql .= "        '".$Formulario_Productos['tipo_producto_id']."',";
    $sql .= "        '".$Formulario_Productos['presentacioncomercial_id']."',";
    $sql .= "        '".$Formulario_Productos['cantidad_p']."',";
    $sql .= "        ".(($Formulario_Productos['tratamiento_id'])? $Formulario_Productos['tratamiento_id']:"NULL" );
    $sql .= "        ,".UserGetUID().",";
    $sql .= "        '".$Formulario_Productos['cod_presenta']."' ); ";
    //$sql .= "         ); ";			
  
  //$this->debug=true;
  
  if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
  
  }
  


//Insercion de Medicamentos.
  function Insertar_ProductoMedicamento($Formulario_Productos)
  {
 
    //$this->debug=true;
    $sql  = "INSERT INTO medicamentos (";
    $sql .= "       codigo_medicamento, ";
	  $sql .= "       descripcion_alerta, ";
	  $sql .= "       sw_farmacovigilancia,";
    $sql .= "       sw_fotosensible,";
    
    
    $sql .= "       cod_anatomofarmacologico,";
    $sql .= "       cod_principio_activo,";
    $sql .= "       cod_forma_farmacologica,";
    $sql .= "       sw_pos,";
    $sql .= "       codigo_cum,";
    $sql .= "       dias_previos_vencimiento,";
    $sql .= "       sw_liquidos_electrolitos,";
    $sql .= "       sw_uso_controlado,";
    $sql .= "       sw_antibiotico,";
    $sql .= "       sw_refrigerado,";
    $sql .= "       sw_alimento_parenteral,";
    $sql .= "       unidad_medida_medicamento_id,";
    $sql .= "       sw_alimento_enteral,";
    $sql .= "       concentracion_forma_farmacologica,";
    $sql .= "       cod_concentracion,";
    $sql .= "       usuario_id ";
    
    
	  $sql .= "       ) ";
    $sql .= "VALUES ( ";
    $sql .= "        '".$Formulario_Productos['codigo_producto']."',";
    $sql .= "        '".$Formulario_Productos['descripcion_alerta']."',";
    $sql .= "        '".$Formulario_Productos['sw_farmacovigilancia']."',";
    $sql .= "        '".$Formulario_Productos['sw_manejo_luz']."',";
    
    
    $sql .= "        '".$Formulario_Productos['cod_anatofarmacologico']."',";
    $sql .= "        '".$Formulario_Productos['cod_principio_activo']."',";
    $sql .= "        '".$Formulario_Productos['cod_forma_farmacologica']."',";
    $sql .= "        '".$Formulario_Productos['sw_pos']."',";
    $sql .= "        '".$Formulario_Productos['codigo_cum']."',";
    $sql .= "        '".$Formulario_Productos['dias_previos_vencimiento']."',";
    $sql .= "        '".$Formulario_Productos['sw_liquidos_electrolitos']."',";
    $sql .= "        '".$Formulario_Productos['sw_uso_controlado']."',";
    $sql .= "        '".$Formulario_Productos['sw_antibiotico']."',";
    $sql .= "        '".$Formulario_Productos['sw_refrigerado']."',";
    $sql .= "        '".$Formulario_Productos['sw_alimento_parenteral']."',";
    $sql .= "        '".$Formulario_Productos['unidad_id']."',";
    $sql .= "        '".$Formulario_Productos['sw_alimento_enteral']."',";
    $sql .= "        '".$Formulario_Productos['concentracion']."',";
    $sql .= "        '".$Formulario_Productos['cod_concentracion']."',";
    $sql .= "        ".UserGetUID()." ";
    
	  $sql .= "       ); ";			
  
  /*
    //Insercion de Niveles de Atencion
    if($Formulario_Productos['nivel_i']!="")
    {
    $ProductoxNivelAtencion=$Formulario_Productos['codigo_producto']."".$Formulario_Productos['nivel_i'];
    $sql .= "INSERT INTO inv_producto_x_nivel_atencion (";
    $sql .= "       producto_x_nivel,";
    $sql .= "       nivel, ";
	  $sql .= "       codigo_producto ";
    $sql .= "       ) ";
    $sql .= "VALUES ( ";
    $sql .= "        '".$ProductoxNivelAtencion."',";
    $sql .= "        '".$Formulario_Productos['nivel_i']."',";
     $sql .= "        '".$Formulario_Productos['codigo_producto']."'";
    $sql .= "       ); ";
    }
    
    if($Formulario_Productos['nivel_ii']!="")
    {
    $ProductoxNivelAtencion=$Formulario_Productos['codigo_producto']."".$Formulario_Productos['nivel_ii'];
    $sql .= "INSERT INTO inv_producto_x_nivel_atencion (";
    $sql .= "       producto_x_nivel,";
    $sql .= "       nivel, ";
    $sql .= "       codigo_producto ";
    $sql .= "       ) ";
    $sql .= "VALUES ( ";
    $sql .= "        '".$ProductoxNivelAtencion."',";
    $sql .= "        '".$Formulario_Productos['nivel_ii']."',";
    $sql .= "        '".$Formulario_Productos['codigo_producto']."'";
    $sql .= "       ); ";
    }
    
    if($Formulario_Productos['nivel_iii']!="")
    {
    $ProductoxNivelAtencion=$Formulario_Productos['codigo_producto']."".$Formulario_Productos['nivel_iii'];
    $sql .= "INSERT INTO inv_producto_x_nivel_atencion (";
    $sql .= "       producto_x_nivel,";
    $sql .= "       nivel, ";
    $sql .= "       codigo_producto ";
    $sql .= "       ) ";
    $sql .= "VALUES ( ";
    $sql .= "        '".$ProductoxNivelAtencion."',";
    $sql .= "        '".$Formulario_Productos['nivel_iii']."',";
    $sql .= "        '".$Formulario_Productos['codigo_producto']."'";
    $sql .= "       ); ";
    }
    
    if($Formulario_Productos['nivel_iv']!="")
    {
    $ProductoxNivelAtencion=$Formulario_Productos['codigo_producto']."".$Formulario_Productos['nivel_iv'];
    $sql .= "INSERT INTO inv_producto_x_nivel_atencion (";
    $sql .= "       producto_x_nivel,";
    $sql .= "       nivel, ";
    $sql .= "       codigo_producto ";
    $sql .= "       ) ";
    $sql .= "VALUES ( ";
    $sql .= "        '".$ProductoxNivelAtencion."',";
    $sql .= "        '".$Formulario_Productos['nivel_iv']."',";
    $sql .= "        '".$Formulario_Productos['codigo_producto']."'";
    $sql .= "       ); ";
    }
    
    
    
    
    //Insercion de Niveles de Uso
    if($Formulario_Productos['nivelu_h']!="")
    {
    $ProductoxNivelUso=$Formulario_Productos['codigo_producto']."".$Formulario_Productos['nivelu_h'];
    $sql .= "INSERT INTO inv_producto_x_nivel_de_uso (";
    $sql .= "       producto_x_nivel_de_uso,";
    $sql .= "       codigo_producto, ";
	  $sql .= "       nivel_de_uso_id ";
	  $sql .= "       ) ";
    $sql .= "VALUES ( ";
    $sql .= "        '".$ProductoxNivelUso."',";
    $sql .= "        '".$Formulario_Productos['codigo_producto']."',";
    $sql .= "        '".$Formulario_Productos['nivelu_h']."'";
    $sql .= "       ); ";
    }
    
    
    if($Formulario_Productos['nivelu_e']!="")
    {
    $ProductoxNivelUso=$Formulario_Productos['codigo_producto']."".$Formulario_Productos['nivelu_e'];
    $sql .= "INSERT INTO inv_producto_x_nivel_de_uso (";
    $sql .= "       producto_x_nivel_de_uso,";
    $sql .= "       codigo_producto, ";
	  $sql .= "       nivel_de_uso_id ";
    $sql .= "       ) ";
    $sql .= "VALUES ( ";
    $sql .= "        '".$ProductoxNivelUso."',";
    $sql .= "        '".$Formulario_Productos['codigo_producto']."',";
    $sql .= "        '".$Formulario_Productos['nivelu_e']."'";
    $sql .= "       ); ";
    }
    
    if($Formulario_Productos['nivelu_g']!="")
    {
    $ProductoxNivelUso=$Formulario_Productos['codigo_producto']."".$Formulario_Productos['nivelu_g'];
    $sql .= "INSERT INTO inv_producto_x_nivel_de_uso (";
    $sql .= "       producto_x_nivel_de_uso,";
    $sql .= "       codigo_producto, ";
	  $sql .= "       nivel_de_uso_id ";
    $sql .= "       ) ";
    $sql .= "VALUES ( ";
    $sql .= "        '".$ProductoxNivelUso."',";
    $sql .= "        '".$Formulario_Productos['codigo_producto']."',";
    $sql .= "        '".$Formulario_Productos['nivelu_g']."'";
    $sql .= "       ); ";
    }
    
    if($Formulario_Productos['nivelu_c']!="")
    {
    $ProductoxNivelUso=$Formulario_Productos['codigo_producto']."".$Formulario_Productos['nivelu_c'];
    $sql .= "INSERT INTO inv_producto_x_nivel_de_uso (";
    $sql .= "       producto_x_nivel_de_uso,";
    $sql .= "       codigo_producto, ";
	  $sql .= "       nivel_de_uso_id ";
    $sql .= "       ) ";
    $sql .= "VALUES ( ";
    $sql .= "        '".$ProductoxNivelUso."',";
    $sql .= "        '".$Formulario_Productos['codigo_producto']."',";
    $sql .= "        '".$Formulario_Productos['nivelu_c']."'";
    $sql .= "       ); ";
    }
    */
    
    
    
    
    
    
    
   //$this->debug=true; 
    
    
    
    
  if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
  
  }





  
  
  
  function InsertarEspxProd_($Especialidad,$CodigoProducto)
  {
  
    $sql  = "INSERT INTO inv_especialidad_x_producto (";
    $sql .= "       especialidad_x_producto_id, ";
	  $sql .= "       codigo_medicamento    , ";
	  $sql .= "       especialidad     ";
	  $sql .= "       ) ";
    $sql .= "VALUES ( ";
    $sql .= "        '".$CodigoProducto."".$Especialidad."',";
	  $sql .= "        '".$CodigoProducto."',";
	  $sql .= "        '".$Especialidad."'";
	  $sql .= "       ); ";			
  
  
  
 if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
    
   }
  
  
  
  
  function Medicamento_NivelUso_Atencion($Tabla,$CodigoMedicamento,$campo)
  {
  
  $sql= "             select 
                             ".$campo."
                          
                    from
                            ".$Tabla."
                   where
                          codigo_producto = '".$CodigoMedicamento."';";
  
  
  //$this->debug=true;
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
  
  
  }
  
  
  
  
  
  function BuscadorProductosCreados($Grupo_id,$Clase_Id,$SubClase_Id,$Cod_Anatofarmacologico,$Codigo_Barras,$Descripcion)
  {
  
  $sql="
          Select 
                          grp.grupo_id || ' ' ||grp.descripcion as Grupo,
                          cla.clase_id || ' ' || cla.descripcion as Clase,
                          sub.subclase_id || ' ' || sub.descripcion Subclase,
                          prod.codigo_producto,
                          prod.descripcion,
                          uni.descripcion || ' X ' || prod.cantidad as presentacion,
                          imf.descripcion as Forma,
                          prod.porc_iva as iva,
                          prod.estado,
                          grp.sw_medicamento
                    from
                          inv_grupos_inventarios grp,
                          inv_clases_inventarios cla,
                          inv_subclases_inventarios sub,
                          inventarios_productos prod,
                          unidades uni,
                          inv_med_cod_forma_farmacologica imf
                    where
                          prod.grupo_id ILike '%".$grupo_Id."%'
                          and
                          prod.clase_id ILike '%".$Clase_Id."%'
                          and
                          prod.subclase_id ILike '%".$Subclase_Id."%'
                          and
                          prod.cod_anatofarmacologico ILike '%".$Cod_Anatofarmacologico."%'
                          and
                          prod.codigo_barras ILike '%".$Codigo_Barras."%'
                          and
                          prod.descripcion ILike '%".$Descripcion."%' 
                          and
                          prod.subclase_id = sub.subclase_id
                          and
                          sub.clase_id = prod.clase_id
                          and
                          sub.grupo_id = prod.grupo_id
                          and
                          sub.clase_id = cla.clase_id
                          and
                          cla.grupo_id = prod.grupo_id
                          and
                          cla.grupo_id = grp.grupo_id
                          and
                          prod.unidad_id = uni.unidad_id
                          and
                          prod.cod_forma_farmacologica = imf.cod_forma_farmacologica;";
  
  
  
  }
  
  
  
  
  
  
  
  
  
  
  function Buscar_Medicamento($CodigoMedicamento)
  {
  
  $sql= "             select 
                             med.sw_fotosensible,
                             med.cod_forma_farmacologica,
                             med.cod_concentracion,
                             med.concentracion_forma_farmacologica,
                              med.sw_farmacovigilancia,
                              med.descripcion_alerta,
                              med.sw_liquidos_electrolitos,
                              med.sw_uso_controlado,
                              med.sw_antibiotico,
                              med.sw_refrigerado,
                              med.sw_alimento_parenteral,
                              med.sw_alimento_enteral,
                              med.dias_previos_vencimiento,
							  med.cod_principio_activo
                          
                    from
                            medicamentos med
                   where
                          med.codigo_medicamento = '".$CodigoMedicamento."';";
  
  
  //$this->debug=true;
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
  
  
  }
  
  
  
  
  function Buscar_ProductoBarras($CodigoBarras)
  {
  $codigo_barras=eregi_replace("'","-",$CodigoBarras);
  $sql= "             select 
                              grp.grupo_id || ' ' ||grp.descripcion as Grupo,
                              cla.clase_id || ' ' || cla.descripcion as Clase,
                              sub.subclase_id || ' ' || sub.descripcion Subclase,
                              prod.producto_id,
                              prod.codigo_cum,
                              prod.codigo_alterno,
                              prod.codigo_barras,
                              prod.descripcion,
                              prod.fabricante_id,
                              prod.sw_pos,
                              prod.cod_acuerdo228_id,
                              prod.cod_forma_farmacologica,
                              prod.unidad_id,
                              prod.cantidad,
                              prod.cod_anatofarmacologico,
                              prod.mensaje_id,
                              prod.codigo_mindefensa,
                              prod.codigo_invima,
                              prod.vencimiento_codigo_invima,
                              prod.titular_reginvima_id,
                              prod.porc_iva,
                              prod.sw_generico,
                              prod.sw_venta_directa,
                              prod.tipo_producto_id,
                              fab.descripcion as fabricante,
                              itri.descripcion as titular
                          
                    from
                            inv_grupos_inventarios grp,
                            inv_clases_inventarios cla,
                            inv_subclases_inventarios sub,
                            inventarios_productos prod,
                            inv_fabricantes fab,
                            inv_titulares_reginvima itri
                   where
                          prod.codigo_barras = '".$codigo_barras."'
                          and
                          prod.fabricante_id = fab.fabricante_id
                          and
                          prod.titular_reginvima_id = itri.titular_reginvima_id
                          and
                          sub.subclase_id = prod.subclase_id
                          and
                          sub.clase_id = prod.clase_id
                          and
                          sub.grupo_id = prod.grupo_id
                          and
                          sub.clase_id = cla.clase_id
                          and
                          cla.grupo_id = prod.grupo_id
                          and
                          grp.grupo_id = prod.grupo_id;";
  
  
  //$this->debug=true;
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
  
  
  }
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  function Buscar_Producto($CodigoProducto)
  {
  
  $sql= "             select 
                              grp.grupo_id || ' ' ||grp.descripcion as Grupo,
                              cla.clase_id || ' ' || cla.descripcion as Clase,
                              sub.subclase_id || ' ' || sub.descripcion as Subclase,
                              sub.subclase_id,
                              prod.grupo_id as grupo_id_producto,
                              prod.clase_id as clase_id_producto,
                              prod.subclase_id as subclase_id_producto,
                              prod.producto_id,
                              prod.codigo_cum,
                              prod.codigo_alterno,
                              prod.codigo_barras,
                              prod.descripcion,
                              prod.descripcion_abreviada,
                              prod.fabricante_id,
                              prod.sw_pos,
                              prod.cod_acuerdo228_id,
                              prod.cod_forma_farmacologica,
                              prod.unidad_id,
                              prod.contenido_unidad_venta as cantidad,
                              prod.cod_anatofarmacologico,
                              prod.mensaje_id,
                              prod.codigo_mindefensa,
                              prod.codigo_invima,
                              prod.vencimiento_codigo_invima,
                              prod.titular_reginvima_id,
                              prod.porc_iva,
                              prod.sw_generico,
                              prod.sw_venta_directa,
                              prod.tipo_producto_id,
                              prod.tipo_pais_id,
                              fab.descripcion as fabricante,
                              itri.descripcion as titular,
                              prod.cantidad as cantidad_p,
                              prod.presentacioncomercial_id,
							  prod.tratamiento_id,
							  prod.cod_adm_presenta
                          
                    from
                            inv_grupos_inventarios grp,
                            inv_clases_inventarios cla,
                            inv_subclases_inventarios sub,
                            inventarios_productos prod,
                            inv_fabricantes fab,
                            inv_titulares_reginvima itri
                   where
                          prod.codigo_producto = '".$CodigoProducto."'
                          and
                          prod.fabricante_id = fab.fabricante_id
                          and
                          prod.titular_reginvima_id = itri.titular_reginvima_id
                          and
                          sub.subclase_id = prod.subclase_id
                          and
                          sub.clase_id = prod.clase_id
                          and
                          sub.grupo_id = prod.grupo_id
                          and
                          sub.clase_id = cla.clase_id
                          and
                          cla.grupo_id = prod.grupo_id
						  and
                          grp.grupo_id = prod.grupo_id;";
  
  
  //$this->debug=true;
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
  
  
  }
  function Lista_Productos_CreadosBuscados($Grupo_Id,$Clase_Id,$SubClase_Id,$Descripcion,$CodAnatofarmacologico,$CodigoBarras,$codigo_producto,$offset)
  {
  $codigo_barras=eregi_replace("'","-",$CodigoBarras);
  
  if(!empty($Grupo_Id))
      $filtro .= " and prod.grupo_id = '".$Grupo_Id."' ";
  if(!empty($Clase_Id))
      $filtro .= " and prod.clase_id = '".$Clase_Id."' ";
  if(!empty($SubClase_Id))
      $filtro .= " and prod.subclase_id = '".$SubClase_Id."' ";
  if(!empty($Descripcion))
      $filtro .= " and prod.descripcion ILike '%".$Descripcion."%'  ";     
     
  if(!empty($CodAnatofarmacologico))
      $filtro .= " and prod.cod_anatofarmacologico = '".$CodAnatofarmacologico."' ";
      
  if(!empty($CodigoBarras))
      $filtro .= " and prod.codigo_barras ILike '%".$codigo_barras."%' ";
   
  if(!empty($codigo_producto))
      $filtro .= " and prod.codigo_producto ILike '%".$codigo_producto."%' ";
   
    // $this->debug=true;
	  $sql="
            Select 
                          grp.descripcion as Grupo,
                          cla.descripcion as Clase,
                          sub.descripcion as Subclase,
                          prod.codigo_producto,
                          prod.descripcion,
                          prod.contenido_unidad_venta || '  ' || uni.descripcion as presentacion,
                          prod.porc_iva as iva,
                          prod.estado,
                          grp.sw_medicamento,
                          pc.descripcion || ' X ' || prod.cantidad as presentacion_comercial
                          
                    from
                          inv_grupos_inventarios grp,
                          inv_clases_inventarios cla,
                          inv_subclases_inventarios sub,
                          inventarios_productos prod,
                          unidades uni,
                          inv_presentacioncomercial as pc
                          
                    where
                         grp.grupo_id = cla.grupo_id
                          and
                          cla.sw_tipo_empresa ='".$_REQUEST['datos']['sw_tipo_empresa']."'
                          and
                          cla.clase_id = sub.clase_id
                          and
                          sub.grupo_id = grp.grupo_id
                          and
                          sub.subclase_id = prod.subclase_id
                          and
                          sub.grupo_id = prod.grupo_id
                          and
                          sub.clase_id = prod.clase_id
                          and
                          prod.unidad_id = uni.unidad_id
                          and
                          prod.presentacioncomercial_id = pc.presentacioncomercial_id
                          ".$filtro."
                           ";
         if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        
    $sql .= "ORDER BY grp.grupo_id, prod.estado DESC,prod.descripcion ASC ";
      $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";  

   //$this->debug=true;
  if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
   }
      
  
  
  
  function Lista_Productos_Creados($offset)
  {
      //$this->debug=true;
      
      $sql="
              Select 
                          grp.descripcion as Grupo,
                          cla.descripcion as Clase,
                          sub.descripcion as Subclase,
                          prod.codigo_producto,
                          prod.descripcion,
                          prod.contenido_unidad_venta || '  ' || uni.descripcion as presentacion,
                          prod.porc_iva as iva,
                          prod.estado,
                          grp.sw_medicamento,
                          pc.descripcion || ' X ' || prod.cantidad as presentacion_comercial
                    from
                          inv_grupos_inventarios grp,
                          inv_clases_inventarios cla,
                          inv_subclases_inventarios sub,
                          inventarios_productos prod,
                          unidades uni,
                          inv_presentacioncomercial as pc
                    where
                          grp.grupo_id = cla.grupo_id
                          and
                          cla.sw_tipo_empresa ='".$_REQUEST['datos']['sw_tipo_empresa']."'
                          and
                          cla.clase_id = sub.clase_id
                          and
                          sub.grupo_id = grp.grupo_id
                          and
                          sub.subclase_id = prod.subclase_id
                          and
                          sub.grupo_id = prod.grupo_id
                          and
                          sub.clase_id = prod.clase_id
                          and
                          prod.unidad_id = uni.unidad_id
                          and
                          prod.presentacioncomercial_id = pc.presentacioncomercial_id
                          ";
                    
  if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        
    $sql .= "ORDER BY grp.grupo_id, prod.estado DESC,prod.descripcion ASC ";
      $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";  


  if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
   }
  
  
  
  
  
  
  
  
  
  function Listar_EspecialidadesxProducto($CodigoProducto)
  {
  $sql="
            select 
                      espx.especialidad_x_producto_id as codigo,
                      esp.descripcion
                  from 
                      especialidades esp,
                      inv_especialidad_x_producto espx
                  where
                      espx.codigo_medicamento = '".$CodigoProducto."'
                      and
                      espx.especialidad = esp.especialidad
                      order by esp.descripcion;";
 //$this->debug=true;
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
  
  
  }
  
  
 function Listar_ViasAdministracion($CodigoProducto)
  {
  $sql="
            select 
                  viad.via_administracion_id,
                  viad.nombre
                  from 
                  hc_vias_administracion viad
                  where
                  viad.via_administracion_id Not In
                                          (
                                          select 
                                          viadp.via_administracion_id
                                          from
                                          inv_medicamentos_vias_administracion  viadp
                                          where
                                          viadp.codigo_medicamento = '".$CodigoProducto."'
                                          ) 
                  order by viad.nombre;";
 //$this->debug=true;
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
  }
  
  function Listar_Vias_De_Administracion()
  {
  
  $sql="
            select
            via_administracion_id as codigo,
            nombre
            from
            hc_vias_administracion
            order by nombre;";
  
  //$this->debug=true;
  if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
  
  }

  function Listar_Vias_Administracion()
  {
  $sql="
            select 
                  via_administracion_id as codigo,
                  nombre
                  from 
                  hc_vias_administracion
                  order by nombre;";
 //$this->debug=true;
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
  
  
  }
  
  
  function Listar_ViasAdministracionxProducto($CodigoProducto)
  {
  $sql="
            select 
                      viadx.codigo_medicamento,
                      viadx.via_administracion_id,
                      viad.nombre
                  from 
                      hc_vias_administracion viad,
                      inv_medicamentos_vias_administracion viadx
                  where
                      viadx.codigo_medicamento = '".$CodigoProducto."'
                      and
                      viadx.via_administracion_id = viad.via_administracion_id
                      order by viad.nombre;";
 //$this->debug=true;
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
  
  
  }
  
  
  
 function Listar_PresentacionesComerciales()
  {
  $sql="
            select
            presentacioncomercial_id as codigo,
            descripcion
            from
            inv_presentacioncomercial
            order by descripcion;";
 //$this->debug=true;
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
  
  
  }  
 
  
  
  
  
  
  
  function Listar_Especialidades($CodigoProducto)
  {
  $sql="
            select 
                  esp.especialidad,
                  esp.descripcion
                  from 
                  especialidades esp
                  where
                  esp.especialidad Not In
                                          (
                                          select 
                                          espx.especialidad
                                          from
                                          inv_especialidad_x_producto espx
                                          where
                                          espx.codigo_medicamento = '".$CodigoProducto."'
                                          ) 
                  order by esp.descripcion;";
 //$this->debug=true;
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
  
  
  }
  
  
  
  
  function Listar_PrincipiosActivos()
  {
  $sql="
            select 
                  pa.cod_principio_activo,
                  pa.descripcion
                  from 
                  inv_med_cod_principios_activos pa
                  
                  order by pa.cod_principio_activo;";
 //$this->debug=true;
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
  
  
  }
  
  
  
  
  function Listar_Tipos_Productos()
  {
  $sql="
            select
            tipo_producto_id as codigo,
            descripcion
            from
            inv_tipo_producto
            order by codigo;";
 //$this->debug=true;
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
  
  
  }
 
  
  function Listar_Perfiles_Terapeuticos()
  {
  $sql="
            select
            cod_anatomofarmacologico as codigo,
            descripcion
            from
            inv_med_cod_anatofarmacologico
            order by descripcion;";
 //$this->debug=true;
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }  
      $resultado->Close();
     // return $sql;
      return $documentos;
  } 
  
  
  
  function Listar_TratamientosProductos()
  {
  $sql=" SELECT
			tratamiento_id,
			descripcion
			FROM
			inv_tratamientos_productos;";
 //$this->debug=true;
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
  
  
  }

  
  function Listar_Presentacion_Comercial()
  {
  $sql="
            select
            cod_forma_farmacologica as codigo,
            descripcion
            from
            inv_med_cod_forma_farmacologica
            order by descripcion;";
 //$this->debug=true;
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
  
  
  }
  
  
   function Listar_CodigosConcentracion()
  {
  $sql="
            select
            cod_concentracion as codigo,
            descripcion
            from
            inv_med_cod_concentraciones
            order by descripcion;";
 //$this->debug=true;
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
  
  
  }

  
  
  function Listar_Unidades_Medida()
  {
  $sql="
            select
            unidad_id as codigo,
            descripcion,
            abreviatura
            from
            unidades
            order by descripcion;";
 //$this->debug=true;
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
  
  
  }
  
  
  
   function Listar_Unidades_MedidaMedicamentos()
  {
  $sql="
            select
            unidad_medida_medicamento_id as codigo,
            descripcion,
            abreviatura
            from
            inv_unidades_medida_medicamentos
            order by descripcion;";
 //$this->debug=true;
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
  
  
  }
  
  
  
  
  function Listar_Paises()
  {
  $sql="
            select
            tipo_pais_id as codigo,
            pais
            from
            tipo_pais
            order by pais;";
 //$this->debug=true;
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
  
  
  }
  
  
  
  
  function Consecutivo_Producto($Grupo_Id,$Clase_Id,$SubClase_Id)
  {
  $sql="
            select
            inv_mostrar_serial('".$Grupo_Id."','".$Clase_Id."','".$SubClase_Id."') as producto_id;";
  
  //$this->debug=true;
  if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
  
  }
  
 

function InsertarViadxProd($via_administracion_id,$CodigoProducto)
  {
  //$this->debug=true;
    $sql  = "INSERT INTO inv_medicamentos_vias_administracion (";
    $sql .= "       codigo_medicamento    , ";
	  $sql .= "       via_administracion_id     ";
	  $sql .= "       ) ";
    $sql .= "VALUES ( ";
    $sql .= "        '".$CodigoProducto."',";
	  $sql .= "        '".$via_administracion_id."'";
	  $sql .= "       ); ";			
  
  
  
 if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
    
   }
  
   function BorrarViadxProd($tabla,$id,$campo_id,$Anex,$CodigoProducto)
  {
  
   // $this->debug=true;
    $sql  = "Delete from ".$tabla." ";
      $sql .= "Where ".$campo_id." = '".$id."' 
      ".$Anex."= '".$CodigoProducto."';";
            
  
  
  
 if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
  
  
  
  }

  //Insertar Datos de Insumos/Medicamentos a la Base de datos
  function Modificar_ClasificacionProducto($Formulario)
  {
    /*$this->debug=true;*/
    $sql .= "UPDATE medicamentos";
    $sql .= "       SET ";
    $sql .= "       cod_principio_activo = '".$Formulario['select_subclase_id']."' ";
    $sql .= "where ";
    $sql .= "codigo_medicamento = '".$Formulario['codigo_producto']."'; ";
    
    $sql .= "UPDATE inventarios_productos";
    $sql .= "       SET ";
    $sql .= "       grupo_id = '".$Formulario['select_grupo_id']."', ";
    $sql .= "       clase_id = '".$Formulario['select_clase_id']."', ";
    $sql .= "       subclase_id = '".$Formulario['select_subclase_id']."' ";
    $sql .= "where ";
    $sql .= "codigo_producto = '".$Formulario['codigo_producto']."' ";			
    $sql .= "RETURNING codigo_producto;";
    /*$this->debug=true;*/
  
  if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				{
            $documentos=Array();
            while(!$rst->EOF)
            {
              $documentos = $rst->GetRowAssoc($ToUpper = false);
              $rst->MoveNext();
              }
              $rst->Close();
        return $documentos;
        }
	  }
  
  
  
}
?>
