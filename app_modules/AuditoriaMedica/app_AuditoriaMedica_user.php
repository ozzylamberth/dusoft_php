<?php

/**
 * $Id: app_AuditoriaMedica_user.php,v 1.14 2006/12/19 20:53:49 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Contiene los metodos para realizar las autorizaciones.
 */

class app_AuditoriaMedica_user extends classModulo
{

    var $limit;
    var $conteo;

     function app_AuditoriaMedica_user()
     {
          $this->limit=GetLimitBrowser();
          return true;
     }

     /**
     *
     */
     function main()
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          
          $query = "SELECT DISTINCT XX.* 
          		FROM ((SELECT DISTINCT C.empresa_id, C.razon_social 
                        	  FROM planes_auditores_ext AS A, planes AS B,
                                empresas AS C 
                           WHERE A.usuario_id=".UserGetUID()."
                           AND A.plan_id = B.plan_id 
                           AND B.empresa_id=C.empresa_id) 
                         
                    UNION (SELECT DISTINCT C.empresa_id, C.razon_social 
                       	  FROM planes_auditores_int AS A, planes AS B, 
                             	  empresas AS C 
                           WHERE A.usuario_id=".UserGetUID()."
                           AND A.plan_id = B.plan_id 
                           AND B.empresa_id=C.empresa_id))  AS XX;";                      
          
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

          if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al ejecutar el query de permisos";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
          }
          while ($data = $resultado->FetchRow()) {
               $auditoria_medica[$data['razon_social']]= $data;
          }
     
          $url[0]='app';
          $url[1]='AuditoriaMedica';
          $url[2]='user';
          $url[3]='FormaMenus';
          $url[4]='DatosAuditoria';
     
          $arreglo[0]='EMPRESA';
     
          $this->salida.= gui_theme_menu_acceso('AUDITORIA MEDICA',$arreglo,$auditoria_medica,$url,ModuloGetURL('system','Menu'));//,ModuloGetURL('system','Menu')
          return true;
     }


     function Menu()
     {
          if(empty($_SESSION['AUDITORIA']['EMPRESA']))
          {
               $_SESSION['AUDITORIA']['EMPRESA_ID']=$_REQUEST['DatosAuditoria']['empresa_id'];
               $_SESSION['AUDITORIA']['EMPRESA']=$_REQUEST['DatosAuditoria']['razon_social'];
          }
          if(!$this->FormaMenus()){
               return false;
          }
          return true;
     }

     
     function BuscarPlan_Auditor()
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          
          $query = "(SELECT DISTINCT B.plan_descripcion, A.plan_id, A.usuario_id, 'ext' as tipo,
          				A.sw_tipo_auditoria
          			FROM planes_auditores_ext AS A, planes AS B 
                         WHERE A.usuario_id=".UserGetUID()." 
                         AND A.plan_id = B.plan_id
                         ORDER BY A.plan_id DESC)
                    
                   	UNION 
				(SELECT DISTINCT B.plan_descripcion, A.plan_id, A.usuario_id, 'int' AS tipo,
                    		A.sw_tipo_auditoria
                         FROM planes_auditores_int AS A, planes AS B 
                         WHERE A.usuario_id=".UserGetUID()."
                         AND A.plan_id = B.plan_id 
                         ORDER BY A.plan_id DESC);";
		
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

          if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al ejecutar el query de busqueda de planes.";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
          }
          while ($data = $resultado->FetchRow()) 
          {
               $planes[] = $data;
          }
          return $planes;
     }


     function TiposIdPacientes()
     {
          list($dbconn) = GetDBconn();
          $query = "SELECT * FROM tipos_id_pacientes ORDER BY indice_de_orden";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          while (!$result->EOF) {
               $vars[]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
          }

          $result->Close();
          return $vars;
     }

		
	function FormateoFechaLocal($fecha)
	{
          if(!empty($fecha))
          {
               $f=explode(".",$fecha);
               $fecha_arreglo=explode(" ",$f[0]);
               $fecha_real=explode("-",$fecha_arreglo[0]);
               return strftime("%A, %d de %B de %Y",strtotime($fecha_arreglo[0]));
          }
          else
          {
               return "-----";
          }

          return true;
	}

	
	
	function Get_Servicios()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT DISTINCT(b.servicio),b.descripcion
                         FROM
                              departamentos a,
                              servicios b
                         WHERE 
                              a.empresa_id='".$_SESSION['AUDITORIA']['EMPRESA_ID']."'
                          AND a.servicio=b.servicio";
          $result = $dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
               
          while (!$result->EOF) {
               $vars[$result->fields[0]]=$result->fields[1];
               $result->MoveNext();
          }
               
          $result->Close();
          return $vars;
	}
     
     
     function Get_hc_modulos()
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();		
					//-----cambio dar agregue WHERE activo='1'
					$query="SELECT * FROM system_hc_modulos WHERE activo='1';";        
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
					$result = $dbconn->Execute($query);
					$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
				
          while ($data = $result->FetchRow()){
               $vars[] = $data;
          }
               
          $result->Close();
          return $vars;
     }
	
     
     function Get_Profesionales()
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();		

					$query="SELECT * 
          	   		FROM profesionales
                  WHERE estado = '1'
                  ORDER BY nombre ASC;";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
					$result = $dbconn->Execute($query);
					$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
				
          while ($data = $result->FetchRow()){
               $vars[] = $data;
          }
               
          $result->Close();
          return $vars;
     }
	
     
     function Get_TipoAuditoria()
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();		
		$query="SELECT * 
          	   FROM notas_auditoria_tipo
                  ORDER BY nota_auditoria_tipo_id ASC;";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
				
          while ($data = $result->FetchRow()){
               $vars[] = $data;
          }
               
          $result->Close();
          return $vars;
     }


/**
	* La funcion tipo_id_paciente se encarga de obtener de la base de datos
	* los diferentes tipos de identificacion de los paciente.
	* @access public
	* @return array
	*/
	function tipo_id_paciente()
  	{
          list($dbconn) = GetDBconn();
          $query = "SELECT * FROM tipos_id_pacientes ORDER BY indice_de_orden";
          $result = $dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          else{
               if($result->EOF){
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "La tabla maestra 'tipos_id_pacientes' esta vacia ";
                    return false;
               }
                    while (!$result->EOF) {
                         $vars[$result->fields[0]]=$result->fields[1];
                         $result->MoveNext();
                    }
          }
          $result->Close();
          return $vars;
	}

	
     /**
	* Realiza la busqueda según el plan,documento .. de los pacientes que
	* tienen ordenes de servicios pendientes
	* @access private
	* @return boolean
	*/
     function BuscarOrden()
	{
          /********descarga de objetos de la forma***********/
					unset($_SESSION['AUDITORIA']['VECTOR']);
          $Buscar1=$_REQUEST['Busc'];
          $Buscar=$_REQUEST['Buscar'];
          $Busqueda=$_REQUEST['TipoBusqueda'];
          $TipoBuscar=$_REQUEST['TipoBuscar'];
          $arreglo=$_REQUEST['arreglo'];
          $TipoCuenta=$_REQUEST['TipoCuenta'];
          $NUM=$_REQUEST['Of'];
          
          if($_SESSION['DATOS_BUSQUEDA']['lleno']==1){            
            $_REQUEST['centroutilidad']=$_SESSION['DATOS_BUSQUEDA']['centroutilidad'];
            $_REQUEST['unidadfunc']=$_SESSION['DATOS_BUSQUEDA']['unidadfunc'];
            $_REQUEST['departamento']=$_SESSION['DATOS_BUSQUEDA']['departamento'];
            $_REQUEST['centroU']=$_SESSION['DATOS_BUSQUEDA']['centroU'];
            $_REQUEST['unidadF']=$_SESSION['DATOS_BUSQUEDA']['unidadF'];
            $_REQUEST['DptoSel']=$_SESSION['DATOS_BUSQUEDA']['DptoSel'];
            $_REQUEST['TipoDocumento']=$_SESSION['DATOS_BUSQUEDA']['TipoDocumento'];
            $_REQUEST['Documento']=$_SESSION['DATOS_BUSQUEDA']['Documento'];
            $_REQUEST['nombres']=$_SESSION['DATOS_BUSQUEDA']['nombres'];
            $_REQUEST['servicio']=$_SESSION['DATOS_BUSQUEDA']['servicio'];
            $_REQUEST['tipo_historia']=$_SESSION['DATOS_BUSQUEDA']['tipo_historia'];
            $_REQUEST['profesional_escojer']=$_SESSION['DATOS_BUSQUEDA']['profesional_escojer'];
            $_REQUEST['fechaini']=$_SESSION['DATOS_BUSQUEDA']['fechaini'];
            $_REQUEST['fechafin']=$_SESSION['DATOS_BUSQUEDA']['fechafin'];
            $_REQUEST['parametros']=$_SESSION['DATOS_BUSQUEDA']['parametros'];            
            UNSET($_SESSION['DATOS_BUSQUEDA']);               
          }
          
          if($Buscar)
          {   unset($_SESSION['SPY']);  }
          if(!$Busqueda)
          {$new=$TipoBuscar;}
          if(!$NUM)
          {   $NUM='0';   }
          foreach($_REQUEST as $v=>$v1)
          {
               if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID')
               {   $vec[$v]=$v1;   }
          }
          $_REQUEST['Of']=$NUM;
          if($Buscar1){
                    $this->FormaMetodoBuscar($Busqueda,$arr,$f);
                    return true;
          }
     
          /***********conexion a db***********/
          list($dbconn) = GetDBconn();
          unset($_SESSION['SPY']);
          
          /******descarga de variables******/				
          $fechaIni=$_REQUEST['fechaini'];
          $fechaFin=$_REQUEST['fechafin'];
          $TipoId=$_REQUEST['TipoDocumento'];
          $PacienteId=$_REQUEST['Documento'];
          $servicio=$_REQUEST['servicio'];
          $hc_modulo = $_REQUEST['tipo_historia'];
          $centro = $_REQUEST['centroU'];
          $unidadFF = $_REQUEST['unidadF'];
          $dpto = $_REQUEST['DptoSel'];
          $evolucion=$_REQUEST['evo_oculto'];
          $ingreso=$_REQUEST['ing_oculto'];
          $cuenta=$_REQUEST['cuenta_oculto'];
          $pre_factura=$_REQUEST['pre_oculto'];
          $factura=$_REQUEST['fac_oculto'];
          $profesional=$_REQUEST['profesional_escojer'];
          
          $nom = explode(" ",$_REQUEST['nombres']);

          /*********Armada de sqls********/
          if($TipoId <> -1 && $TipoId <> '*'){ $tipo=" AND	b.tipo_id_paciente='$TipoId' ";}
          if($PacienteId){ $paciente="AND b.paciente_id LIKE('$PacienteId%')";}
          if($orden){ $n_orden=" AND c.numero_orden_id LIKE($orden%)";}
          
          if(!empty($_REQUEST['nombres']))
          {					
               $nombre=" AND (UPPER(b.primer_nombre) LIKE('".strtoupper($nom[0])."%') OR UPPER(b.segundo_nombre) LIKE('".strtoupper($nom[0])."%'))"; 
               if($nom[1] != "")
               {
                    $nombre.=" AND (UPPER(b.primer_apellido) LIKE('".strtoupper($nom[1])."%') OR UPPER(b.segundo_apellido) LIKE('".strtoupper($nom[1])."%'))";
               }
          }
        
          if($servicio <> -1 && $servicio <> '*'){ $sql_serv=" AND e.servicio='$servicio' ";}
     
          //nuevo Tizziano Perea
          if($hc_modulo <> -1 && $hc_modulo <> '*'){ $sql_modulo=" AND c.hc_modulo='$hc_modulo' ";}
          //nuevo Tizziano Perea
          
          if(!empty($centro) AND $centro != '-1')
          { $center = "AND d.centro_utilidad='$centro'"; }
          
          if(!empty($unidadFF) AND $unidadFF != '-1')
          { $unidad = "AND d.unidad_funcional='$unidadFF'"; }
          
          if(!empty($dpto) AND $dpto != '-1')
          { $depar = "AND c.departamento='$dpto'"; }
          
          if(!empty($evolucion))
          {$sql_evol="AND c.evolucion_id='$evolucion'";}

           if(!empty($ingreso))
          {$sql_ing="AND c.ingreso='$ingreso'";}
         
          if(!empty($cuenta))
          {$sql_cuenta="AND c.numerodecuenta='$cuenta'";}
                    
		if($profesional != '*' AND $profesional != '-1' AND !empty($profesional))
          {$sql_profesional="AND c.usuario_id='$profesional'";}					

          if(!empty($pre_factura) OR !empty($factura))
          { 	
          	if(empty($pre_factura) AND !empty($factura))
               {
               	$sql_factura="AND i.factura_fiscal='$factura' AND i.numerodecuenta=c.numerodecuenta";
               }elseif(!empty($pre_factura) AND empty($factura))
               {
               	$pre_factura = strtoupper($pre_factura);
               	$sql_factura="AND i.prefijo='$pre_factura' AND i.numerodecuenta=c.numerodecuenta";
               }elseif(!empty($pre_factura) AND !empty($factura))
               {
               	$pre_factura = strtoupper($pre_factura);
               	$sql_factura="AND i.factura_fiscal='$factura' AND i.prefijo='$pre_factura' AND i.numerodecuenta=c.numerodecuenta";
               }
          }
          
		if($fechaIni)
          {
               $fechaIni=$this->Change_Formatt_Date($fechaIni);
               $sql_fi="AND date(a.fecha_ingreso)>= '$fechaIni' ";
          }
          if($fechaFin)
          {
               $fechaFin=$this->Change_Formatt_Date($fechaFin);
               $sql_ff="AND date(a.fecha_ingreso)<= '$fechaFin' ";
          }
     
          $dat = $this->Buscar1($tipo,$paciente,$sql_serv,$nombre,$sql_fi,$sql_ff,$sql_evol,$NUM,$sql_modulo,$depar,$sql_ing,$sql_cuenta,$sql_factura,$unidad,$center,$sql_profesional);
					if(!empty($dat))
					{		//si encontro algo lo ejecuto con limit porque si no no hace falta
							$datos=$this->Buscar1($tipo,$paciente,$sql_serv,$nombre,$sql_fi,$sql_ff,$sql_evol,$NUM,$sql_modulo,$depar,$sql_ing,$sql_cuenta,$sql_factura,$unidad,$center,$sql_profesional);
					}
          if($datos){
               $this->FormaMetodoBuscar($Busqueda='',$datos,$f=true);
               return true;
          }
          else{
               $this->uno=1;
               $this->frmError["MensajeError"]='LA BÚSQUEDA NO ARROJO RESULTADOS.';
               $this->FormaMetodoBuscar($Busqueda='',$Cuentas,$f=true);
               return true;
          }
	}

	
	/****cambiamos la ubicacion de las fechas********/
	function Change_Formatt_Date($fecha)
	{
		$f=explode("-",$fecha);
		return $f[2]."-".$f[1]."-".$f[0];
	}
	
	

	/**
	* funcion buscar1 es la que se filtra por el tipo del paciente y la identificacion del
	* paciente.
	* @access private
	* @return array
	*/
	function Buscar1($TipoId,$PacienteId,$sql_serv,$nom,$sql_fi,$sql_ff,$sql_evol,$NUM,$sql_modulo,$depar,$sql_ing,$sql_cuenta,$sql_factura,$unidad,$center,$sql_profesional)
	{
					$x='';
          list($dbconn) = GetDBconn();
          $limit=$this->limit;
          list($dbconn) = GetDBconn();
          if(!empty($_SESSION['SPY']))
          {   $x=" LIMIT ".$this->limit." OFFSET $NUM";   }
          else
          {   $x='';   }

          if(!empty($PacienteId) OR !empty($sql_modulo) OR !empty($sql_serv))
          {
               $query="SELECT DISTINCT
                         btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||
                         b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
                         b.tipo_id_paciente,b.paciente_id
                         ,e.descripcion,d.descripcion as desc,a.ingreso,a.estado,a.fecha_ingreso,
                         c.evolucion_id, c.fecha, c.usuario_id     
     
                         FROM pacientes as b,ingresos a,
                         hc_evoluciones c,departamentos d,servicios e, cuentas x
     
                         WHERE                                        
                         a.tipo_id_paciente=b.tipo_id_paciente
                         AND a.paciente_id=b.paciente_id
                         $TipoId $PacienteId
                         AND x.plan_id = ".$_SESSION['AUDITORIA']['PLAN']."
                         AND x.ingreso=c.ingreso
                         AND c.ingreso=a.ingreso
                         $center
                         $unidad                         
                         $depar
                         AND d.departamento=c.departamento
                         $sql_profesional
                         AND c.estado='0'
                         AND d.servicio=e.servicio
                         $sql_serv
                         $sql_fi
                         $sql_ff
                         $nom  
                         $sql_modulo
                         ORDER BY a.ingreso ASC
                         $x";
            }elseif(!empty($sql_evol))
            {
                 $query="SELECT DISTINCT
                         btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||
                         b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
                         b.tipo_id_paciente,b.paciente_id
                         ,e.descripcion,d.descripcion as desc,a.ingreso,a.estado,a.fecha_ingreso,
                         c.evolucion_id, c.fecha, c.usuario_id
     
                         FROM pacientes as b,ingresos a,
                         hc_evoluciones c,departamentos d,servicios e, cuentas x
     
                         WHERE                                        
                         a.tipo_id_paciente=b.tipo_id_paciente
                         AND a.paciente_id=b.paciente_id
                         $TipoId $PacienteId
                         AND x.plan_id = ".$_SESSION['AUDITORIA']['PLAN']."
                         AND x.ingreso=c.ingreso
                         AND a.ingreso=c.ingreso
                         AND c.estado='0'
                         $sql_profesional
                         AND d.departamento=c.departamento
                         AND d.servicio=e.servicio
                         $sql_fi
                         $sql_ff
                         $nom  
                         $sql_evol
                         ORDER BY a.ingreso ASC
                         $x";
            }elseif(!empty($sql_ing))
            {
                 $query="SELECT DISTINCT
                         btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||
                         b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
                         b.tipo_id_paciente,b.paciente_id
                         ,e.descripcion,d.descripcion as desc,a.ingreso,a.estado,a.fecha_ingreso,
                         c.evolucion_id, c.fecha, c.usuario_id
     
                         FROM pacientes as b,ingresos a,
                         hc_evoluciones c,departamentos d,servicios e, cuentas x
     
                         WHERE                                        
                         a.tipo_id_paciente=b.tipo_id_paciente
                         AND a.paciente_id=b.paciente_id
                         $TipoId $PacienteId
                         AND x.plan_id = ".$_SESSION['AUDITORIA']['PLAN']."
                         AND x.ingreso=c.ingreso
                         AND a.ingreso=c.ingreso                         
                         AND c.estado='0'
                         $sql_profesional
                         $sql_ing
                         AND d.departamento=c.departamento
                         AND d.servicio=e.servicio
                         $sql_fi
                         $sql_ff
                         $nom  
                         ORDER BY a.ingreso ASC
                         $x";
            }elseif(!empty($sql_cuenta))
            {
                 $query="SELECT DISTINCT
                         btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||
                         b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
                         b.tipo_id_paciente,b.paciente_id
                         ,e.descripcion,d.descripcion as desc,a.ingreso,a.estado,a.fecha_ingreso,
                         c.evolucion_id, c.fecha, c.usuario_id
     
                         FROM pacientes as b,ingresos a,
                         hc_evoluciones c,departamentos d,servicios e, cuentas x
     
                         WHERE
                                        
                         a.tipo_id_paciente=b.tipo_id_paciente
                         AND a.paciente_id=b.paciente_id
                         $TipoId $PacienteId
                         AND c.ingreso=a.ingreso
                         AND c.estado='0'
                         $sql_profesional
                         $sql_cuenta
                         AND c.numerodecuenta=x.numerodecuenta
                         AND x.plan_id = ".$_SESSION['AUDITORIA']['PLAN']."
                         AND d.departamento=c.departamento
                         AND d.servicio=e.servicio
                         $sql_fi
                         $sql_ff
                         $nom  
                         ORDER BY a.ingreso ASC
                         $x";
            }elseif(!empty($sql_factura))
            {
                 $query="SELECT DISTINCT
                         btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||
                         b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
                         b.tipo_id_paciente,b.paciente_id
                         ,e.descripcion,d.descripcion as desc,a.ingreso,a.estado,a.fecha_ingreso,
                         c.evolucion_id, c.fecha, c.usuario_id
     
                         FROM pacientes as b,ingresos a,
                         hc_evoluciones c,departamentos d,servicios e,
                         fac_facturas_cuentas i, cuentas x
     
                         WHERE
                                        
                         a.tipo_id_paciente=b.tipo_id_paciente
                         AND a.paciente_id=b.paciente_id
                         $TipoId $PacienteId
                         $sql_factura
                         AND i.numerodecuenta=x.numerodecuenta
                         AND x.plan_id = ".$_SESSION['AUDITORIA']['PLAN']."
                         AND c.ingreso=a.ingreso
                         AND c.estado='0'
                         $sql_profesional
                         AND d.departamento=c.departamento
                         AND d.servicio=e.servicio
                         $sql_fi
                         $sql_ff
                         $nom  
                         ORDER BY a.ingreso ASC
                         $x";
            }elseif(!empty($center) OR !empty($unidad) OR !empty($depar))
            {
          	$query="SELECT btrim(b.primer_nombre||' '||b.segundo_nombre||' ' || b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
                              SUB.evolucion_id, SUB.fecha, SUB.usuario_id,
                              SUB.ingreso, SUB.estado, SUB.fecha_ingreso,
                              SUB.tipo_id_paciente, SUB.paciente_id,
                              d.descripcion as desc,
                              e.descripcion
                       FROM 
                             (SELECT c.evolucion_id, c.fecha, c.usuario_id, c.departamento,
                                   a.ingreso, a.estado, a.fecha_ingreso,
                                   a.tipo_id_paciente, a.paciente_id
                              FROM hc_evoluciones c,
                                   cuentas x,
                                   ingresos a
                              WHERE c.estado='0'
                                    AND c.estado='0'
                                    $depar
                                    $sql_profesional
                                    AND c.numerodecuenta = x.numerodecuenta
                                    AND x.plan_id = ".$_SESSION['AUDITORIA']['PLAN']."
                                    AND a.ingreso = c.ingreso
                                    $sql_fi
                                    $sql_ff) AS SUB,
                    
                         pacientes as b,
                         departamentos d,
                         servicios e
                    WHERE
                         SUB.tipo_id_paciente = b.tipo_id_paciente 
                         AND SUB.paciente_id = b.paciente_id
                         $TipoId $PacienteId
                         AND SUB.departamento = d.departamento
                         AND d.servicio = e.servicio
                         $nom
                    ORDER BY SUB.ingreso ASC    
                    $x"; 
            }else
            {
               $query="SELECT DISTINCT
                         btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||
                         b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
                         b.tipo_id_paciente,b.paciente_id
                         ,e.descripcion,d.descripcion as desc,a.ingreso,a.estado,a.fecha_ingreso,
                         c.evolucion_id, c.fecha, c.usuario_id
     
                         FROM pacientes as b,ingresos a,
                         hc_evoluciones c,departamentos d,servicios e, cuentas x
                              
                         WHERE
                                        
                         a.tipo_id_paciente=b.tipo_id_paciente
                         AND a.paciente_id=b.paciente_id
                         $TipoId $PacienteId
                         AND c.ingreso=x.ingreso
                         AND x.plan_id = ".$_SESSION['AUDITORIA']['PLAN']."
                         AND c.ingreso=a.ingreso
                         AND c.estado='0'
                         $sql_profesional
                         $center
                         $unidad                         
                         $depar
                         AND d.departamento = c.departamento
                         AND d.servicio=e.servicio
                         $sql_fi
                         $sql_ff
                         $nom  
                         ORDER BY a.ingreso ASC
                         $x";
            }

   		$result = $dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "ERROR AL CONSULTAR POR EL TIPO Y LA IDENTIFICACIÓN DEL PACIENTE";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          if(!empty($_SESSION['SPY']))
          {
               while(!$result->EOF)
               {
                    $vars[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
               }
          }
          else
          {
               $vars=$result->RecordCount();
               $_SESSION['SPY']=$vars;
          }
          $result->Close();
		return $vars;
	}
	
     
     function Get_Evoluciones($ingreso)
     {
          GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBConn();
          $query="SELECT evolucion_id
          	   FROM hc_evoluciones
                  WHERE ingreso=$ingreso
                  ORDER BY evolucion_id DESC;";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
				
          while ($data = $result->FetchRow()){
               $vars[] = $data;
          }
               
          $result->Close();
          return $vars;
     }
     
     
     function Insertar_NotaAuditoria()
     {
          $ingreso = $_REQUEST['ingreso'];
          $nota_auditora = $_REQUEST['nota_auditoria'];
          $tipo_notaAuditoria = $_REQUEST['sel_tipoauditoria'];
          $privada = $_REQUEST['privacidad'];
         // $responder = $_REQUEST['responder'];
          $prioridad = $_REQUEST['prioridad'];
          $verProfesional = $_REQUEST['ver_profesional'];
          $evolucion = $_REQUEST['evolucion_print'];
          $cerrar_caso = $_REQUEST['cerrar_caso'];
          
         
          if(EMPTY($_SESSION['AUDITORIA']['VECTOR'])){
               $this->frmError["nota_auditoria_tipo_id"]=1;
               $this->frmError["MensajeError"]="DEBE SELECCIONAR UN TIPO DE NOTA DE AUDITORIA.";
               $this->FormaAdicion_NotaAuditoria();
               return true;
					}
 
          if($privada == -1){
               $this->frmError["privada"]=1;
               $this->frmError["MensajeError"]="DEBE SELECCIONAR UN TIPO DE PRIVACIDAD.";
               $this->FormaAdicion_NotaAuditoria();
               return true;
					}
          
          if($prioridad == -1){
          	$prioridad = '0';
					}
         
          if($nota_auditora == '' OR empty($nota_auditora)){
               $this->frmError["nota_auditora"]=1;
               $this->frmError["MensajeError"]="DEBE REDACTAR UNA NOTA PARA LA CORRESPONDIENTE AUDITORIA.";
               $this->FormaAdicion_NotaAuditoria();
               return true;
					}			
					
					$verProfesional = '0';
					$responder = '0'; 
          if($_REQUEST['responder']==1)
          {
          		$responder = '1';
          } 
          elseif($_REQUEST['responder']==2)
          {
          		$verProfesional = '1';
          }
					else
					{		//es la opcion ninguna
							$verProfesional = '0';
							$responder = '0';
					}					
          								
          if($evolucion == -1)
          { $evolucion = 'NULL';}
          
          if($responder== '1')
          {$verProfesional = '1';}
          
          if($_SESSION['AUDITORIA']['TIPO_PLAN'] == 'int')
          {
          	$tipo_auditor = '1';
          }elseif($_SESSION['AUDITORIA']['TIPO_PLAN'] == 'ext')
          {
          	$tipo_auditor = '0';
          }
          
          if ($cerrar_caso == '1')
          {
          	$estado = '0';
          }else
          {
          	$estado = '1';
          }

          list($dbconn) = GetDBConn();
					$dbconn->BeginTrans();
					$query=" SELECT nextval('notas_auditoria_nota_auditoria_id_seq')";
					$result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error SELECT nextval";
							$this->fileError = __FILE__;
							$this->lineError = __LINE__;		
							$dbconn->RollbackTrans();
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
          }
					$id=$result->fields[0];
					$result->Close();
				
					$query="INSERT INTO notas_auditoria ( nota_auditoria_id,
																								estado,
																								fecha_registro,
																								usuario_id,
																								nota,
																								ingreso,
																								evolucion_id,
																								sw_privada,
																								sw_responder,
																								sw_prioridad,
																								sw_medico,
																								sw_tipo_auditor)
											VALUES(										$id,
																								'$estado',
																								'now()',
																								".UserGetUID().",
																								'$nota_auditora',
																								".$ingreso.",
																								".$evolucion.",
																								'$privada',
																								'$responder',
																								'$prioridad',
																								'$verProfesional',
																								'$tipo_auditor');";
					$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error notas_auditoria";
							$this->fileError = __FILE__;
							$this->lineError = __LINE__;		
							$dbconn->RollbackTrans();
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
          }
		
					foreach($_SESSION['AUDITORIA']['VECTOR'] as $k => $v)
					{					
							$query = "INSERT INTO notas_auditoria_tipos_seleccion (nota_auditoria_id,
																																		nota_auditoria_tipo_id)
												VALUES($id,".$v[id].")";
							$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error notas_auditoria";
									$this->fileError = __FILE__;
									$this->lineError = __LINE__;		
									$dbconn->RollbackTrans();
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
							}		
					}
								
					$dbconn->CommitTrans();
					unset($_SESSION['AUDITORIA']['VECTOR']);
					$this->frmError["MensajeError"]='LA INSERCION DE LA NOTA FUE UN ÉXITO.';
					$_REQUEST['confirmar_insert'] = '1';
					$this->FormaAdicion_NotaAuditoria();
     			return true;
     }
     
     
/*     function cerrarCasoAuditoria()
     {
     	$ingreso = $_REQUEST['ingreso'];
          $evolucion = $_REQUEST['EvolucionSelect'];
          
          if($evolucion == '-1')
          {
          	$sql_evolucion = "AND evolucion_id IS NULL";
          }
          else
          {
          	$sql_evolucion = "AND evolucion_id=".$evolucion."";
          }
     	
          list($dbconn) = GetDBconn();
          $query_busqueda="SELECT ingreso 
          			  FROM notas_auditoria
                           WHERE ingreso=".$ingreso."
                           AND usuario_id=".UserGetUID()."
                           $sql_evolucion;";
		
          $result = $dbconn->Execute($query_busqueda);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          list($borrar) = $result->FetchRow();
          
          if(empty($borrar))
          {
               $this->frmError["MensajeError"]="ESTA NOTA AUN NO TIENE UN CASO DE AUDITORIA, POR LO TANTO NO SE PUEDE CERRAR.";
               $this->FormaAdicion_NotaAuditoria();
               return true;
          }
          else
          {
              $query_cerrar="UPDATE notas_auditoria 
               			SET estado='0'
                              WHERE ingreso=".$ingreso."
                              AND usuario_id=".UserGetUID()."
						$sql_evolucion;";
               
               $result = $dbconn->Execute($query_cerrar);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
          }
          $this->FormaAdicion_NotaAuditoria();
          return true;
     }*/
     
     
     function TraerUsuario($usuario)
     {
          GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();

          $query ="SELECT usuario, nombre
          		FROM system_usuarios
                    WHERE usuario_id = ".$usuario.";";
                    
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          $data = $result->FetchRow();
          return $data;
     }
     
     
     function BusquedaNota($ingreso)
     {
          GLOBAL $ADODB_FETCH_MODE;
					list($dbconn) = GetDBconn();
          $query_busqueda="SELECT A.ingreso, A.evolucion_id, A.nota, A.sw_prioridad,
          				    A.usuario_id, A.fecha_registro, A.sw_tipo_auditor,
                                  A.estado, A.sw_responder, B.nombre
          			  FROM notas_auditoria AS A,
                                system_usuarios AS B
                           WHERE ingreso=".$ingreso."
                           AND A.usuario_id = B.usuario_id
                           ORDER BY evolucion_id DESC;";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query_busqueda);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          
          while ($data = $result->FetchRow()){
               $vars[] = $data;
          }
               
          $result->Close();
          return $vars;
     }
     
     
	//******funcion q prepara la cadena para ejecutarse en el sql*******/
	/*****mediante la palabra reservada IN()********/
	function Prepar_Cadena($codigos)
	{
		$cadena="";
		$tok = strtok($codigos, ",");
		while ($tok) {
          if($tok)
          {
               $cadena="'".$tok."'".",";
          }
               $tok = strtok(",");
          }
          return $cadena."0";
 	}

     	
//------------------------------------------------------------------------------
}//fin clase user
?>

