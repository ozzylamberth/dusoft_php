<?php

/**
 * $Id: app_RespuestasAuditor_user.php,v 1.3 2005/11/22 21:18:09 darling Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Contiene los metodos para realizar las autorizaciones.
 */

class app_RespuestasAuditor_user extends classModulo
{

    var $limit;
    var $conteo;

     function app_RespuestasAuditor_user()
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
               $respuestas_auditor[$data['razon_social']]= $data;
          }
     
          $url[0]='app';
          $url[1]='RespuestasAuditor';
          $url[2]='user';
          $url[3]='FormaMenus';
          $url[4]='DatosRespuesta';
     
          $arreglo[0]='EMPRESA';
     
          $this->salida.= gui_theme_menu_acceso('RESPUESTAS AUDITOR',$arreglo,$respuestas_auditor,$url);
          return true;
     }


     function Menu()
     {
          if(empty($_SESSION['RESPUESTAS']['EMPRESA']))
          {
               $_SESSION['RESPUESTAS']['EMPRESA_ID']=$_REQUEST['DatosRespuesta']['empresa_id'];
               $_SESSION['RESPUESTAS']['EMPRESA']=$_REQUEST['DatosRespuesta']['razon_social'];
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
          $query = "( SELECT DISTINCT B.plan_descripcion, A.plan_id, A.usuario_id, 'ext' as tipo,
											A.sw_tipo_auditoria
											FROM planes_auditores_ext AS A, planes AS B 
											WHERE A.usuario_id=".UserGetUID()." 
											AND A.plan_id = B.plan_id
											AND B.fecha_final >= now() AND B.fecha_inicio <= now()
											AND B.empresa_id='".$_SESSION['RESPUESTAS']['EMPRESA_ID']."' AND B.estado='1'
											ORDER BY A.plan_id DESC
										)                    
                   	UNION
										( 
												SELECT DISTINCT B.plan_descripcion, A.plan_id, A.usuario_id, 'int' AS tipo,
												A.sw_tipo_auditoria
												FROM planes_auditores_int AS A, planes AS B 
												WHERE A.usuario_id=".UserGetUID()."
												AND A.plan_id = B.plan_id 
												AND B.fecha_final >= now() AND B.fecha_inicio <= now()
												AND B.empresa_id='".$_SESSION['RESPUESTAS']['EMPRESA_ID']."' AND B.estado='1'
												ORDER BY A.plan_id DESC
										);";
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


     function Get_Profesionales()
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();		

					$query="SELECT DISTINCT A.*
									FROM profesionales AS A, notas_auditoria AS B, hc_evoluciones AS C
									WHERE A.estado = '1'
									AND C.ingreso = B.ingreso
									AND A.usuario_id = C.usuario_id
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

	
     /**
	* Realiza la busqueda seg?n el plan,documento .. de los pacientes que
	* tienen ordenes de servicios pendientes
	* @access private
	* @return boolean
	*/
     function BuscarOrden()
	{
          /********descarga de objetos de la forma***********/
          $Buscar1=$_REQUEST['Busc'];
          $Buscar=$_REQUEST['Buscar'];
          $Busqueda=$_REQUEST['TipoBusqueda'];
          $TipoBuscar=$_REQUEST['TipoBuscar'];
          $arreglo=$_REQUEST['arreglo'];
          $TipoCuenta=$_REQUEST['TipoCuenta'];
          $NUM=$_REQUEST['Of'];
          
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
//          $TipoId=$_REQUEST['TipoDocumento'];
//           $PacienteId=$_REQUEST['Documento'];
          $plan=$_REQUEST['plan'];
//           $hc_modulo = $_REQUEST['tipo_historia'];
          $centro = $_REQUEST['centroU'];
          $unidadFF = $_REQUEST['unidadF'];
          $dpto = $_REQUEST['DptoSel'];
//           $evolucion=$_REQUEST['evo_oculto'];
//           $ingreso=$_REQUEST['ing_oculto'];
//           $cuenta=$_REQUEST['cuenta_oculto'];
//           $pre_factura=$_REQUEST['pre_oculto'];
//           $factura=$_REQUEST['fac_oculto'];
          $profesional=$_REQUEST['profesional_escojer'];
          
//           $nom = explode(" ",$_REQUEST['nombres']);

          /*********Armada de sqls********/
//           if($TipoId <> -1 && $TipoId <> '*'){ $tipo=" AND	b.tipo_id_paciente='$TipoId' ";}
//           if($PacienteId){ $paciente="AND b.paciente_id LIKE('$PacienteId%')";}
//           if($orden){ $n_orden=" AND c.numero_orden_id LIKE($orden%)";}
          
/*          if(!empty($_REQUEST['nombres']))
          {					
               $nombre=" AND (UPPER(b.primer_nombre) LIKE('".strtoupper($nom[0])."%') OR UPPER(b.segundo_nombre) LIKE('".strtoupper($nom[0])."%'))"; 
               if($nom[1] != "")
               {
                    $nombre.=" AND (UPPER(b.primer_apellido) LIKE('".strtoupper($nom[1])."%') OR UPPER(b.segundo_apellido) LIKE('".strtoupper($nom[1])."%'))";
               }
          }*/
        
          if($plan != '-1')
          { 
          	$sql_plan=" AND f.plan_id='$plan'";
          }

/*          //nuevo Tizziano Perea
          if($hc_modulo <> -1 && $hc_modulo <> '*'){ $sql_modulo=" AND c.hc_modulo='$hc_modulo' ";}
          //nuevo Tizziano Perea*/
          
          if(!empty($centro) AND $centro != '-1')
          { 
          	$center = "AND e.centro_utilidad='$centro'";
          }
          
          if(!empty($unidadFF) AND $unidadFF != '-1')
          {
               $unidad = "AND e.unidad_funcional='$unidadFF'";
          }
          
          if(!empty($dpto) AND $dpto != '-1')
          {
          	$depar = "AND e.departamento='$dpto'"; 
          }
          
          
/*          if(!empty($evolucion))
          {$sql_evol="AND c.evolucion_id='$evolucion'";}

           if(!empty($ingreso))
          {$sql_ing="AND c.ingreso='$ingreso'";}
         
          if(!empty($cuenta))
          {$sql_cuenta="AND c.numerodecuenta='$cuenta'";}*/
                    
		if($profesional != '*' AND $profesional != '-1' AND !empty($profesional))
          {$sql_profesional="AND c.usuario_id='$profesional'";}					

/*          if(!empty($pre_factura) OR !empty($factura))
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
          }*/
          
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
     
          $dat = $this->Buscar1($sql_plan,$sql_fi,$sql_ff,$NUM,$depar,$unidad,$center,$sql_profesional);
          if(!empty($dat))
          {		
     		//si encontro algo lo ejecuto con limit porque si no no hace falta
               $datos=$this->Buscar1($sql_plan,$sql_fi,$sql_ff,$NUM,$depar,$unidad,$center,$sql_profesional);
          }
          if($datos){
               $this->FormaMetodoBuscar($Busqueda='',$datos,$f=true);
               return true;
          }
          else{
               $this->uno=1;
               $this->frmError["MensajeError"]='LA B?SQUEDA NO ARROJO RESULTADOS.';
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
	function Buscar1($sql_plan,$sql_fi,$sql_ff,$NUM,$depar,$unidad,$center,$sql_profesional)
	{
          $x='';
          list($dbconn) = GetDBconn();
          $limit=$this->limit;
          list($dbconn) = GetDBconn();
          if(!empty($_SESSION['SPY']))
          {   $x=" LIMIT ".$this->limit." OFFSET $NUM";   }
          else
          {   $x='';   }

          $query="SELECT DISTINCT btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
                  b.tipo_id_paciente, b.paciente_id, c.evolucion_id as hc_evolucion,
                  d.nota_auditoria_id, d.ingreso, d.evolucion_id, d.fecha_registro,
                  d.sw_prioridad, d.nota, d.sw_responder, f.plan_id
                    
                  FROM pacientes as b, ingresos a, hc_evoluciones c 
                  LEFT JOIN notas_auditoria d
                  ON (d.evolucion_id = c.evolucion_id OR d.evolucion_id IS NULL), departamentos e, cuentas f
                    
                  WHERE e.empresa_id = '".$_SESSION['RESPUESTAS']['EMPRESA_ID']."' 
                        $center
                        $unidad
                        $depar
                        AND c.departamento=e.departamento
                        AND c.estado = '0'
                        $sql_profesional
                        AND c.ingreso = a.ingreso
                        $sql_fi
                  	    $sql_ff
                        $sql_plan
                        AND f.ingreso=c.ingreso
                        AND a.tipo_id_paciente = b.tipo_id_paciente
                        AND a.paciente_id = b.paciente_id
	                   AND d.ingreso = c.ingreso
                        AND d.usuario_id = ".UserGetUID()."
                        AND d.estado = '1'
                  	    ORDER BY d.fecha_registro DESC, d.sw_prioridad DESC, c.evolucion_id DESC
                  	    $x";
     
          $result = $dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "ERROR AL CONSULTAR POR EL TIPO Y LA IDENTIFICACI?N DEL PACIENTE";
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
	
     
     function GetInformacion_NotaAuditoria($nota_auditoria_id)
     {
     			GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
		
					$sql = "SELECT A.*, B.descripcion AS descripcion_tipo_nota
									FROM notas_auditoria A, notas_auditoria_tipo B, notas_auditoria_tipos_seleccion C
									WHERE A.nota_auditoria_id = ".$nota_auditoria_id."
									AND C.nota_auditoria_id=A.nota_auditoria_id
									AND C.nota_auditoria_tipo_id=B.nota_auditoria_tipo_id;";							
							
                  
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($sql);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
     
          while ($data = $result->FetchRow())
          {
               $Info_notas[] = $data;
          }
          
          $result->Close();
          return $Info_notas;
     }
     
     
     function GetRespuesta_NotaAuditoria($nota_auditoria_id)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
		
          $sql = "SELECT * FROM notas_auditoria_respuestas
                  WHERE nota_auditoria_id = ".$nota_auditoria_id."
                  ORDER BY fecha_registro ASC;";
                  
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($sql);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
     
          while ($data = $result->FetchRow())
          {
               $Info_respuestas[] = $data;
          }
          
          $result->Close();
          return $Info_respuestas;
     }
     
     
     function InsertarRespuesta_NotaAuditoria()
     {
     	$nota_auditoria_id = $_REQUEST['nota_auditoria_id'];
          $respuesta = $_REQUEST['respuesta'];

          list($dbconn) = GetDBconn();
		
          $sql = "SELECT tipo_profesional 
          	   FROM profesionales WHERE usuario_id = ".UserGetUID().";";
                  
          $result = $dbconn->Execute($sql);
          
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
     
          list ($tipo_profesional) = $result->FetchRow();
          
          if($tipo_profesional == '5' OR empty($tipo_profesional))
          {
          	$sw_tipo_usuario = '2';
          }else
          {
          	$sw_tipo_usuario = '1';
          }

          
          $queryInsert = "INSERT INTO notas_auditoria_respuestas (nota_auditoria_id,
          											 fecha_registro,
                                                                  usuario_id,
                                                                  respuesta,
                                                                  sw_tipo_usuario)
          									VALUES    (".$nota_auditoria_id.",
                                                       		 now(),
                                                                  ".UserGetUID().",
                                                                  '$respuesta',
                                                                  '$sw_tipo_usuario');";
		$result = $dbconn->Execute($queryInsert);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
              
          $this->frmError["MensajeError"]='DATOS INSERTADOS SATISFACTORIAMENTE.';
          $this->Informacion_NotaAuditoria();
          $result->Close();
          return true;
     
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
     
     
     function cerrarCasoAuditoria()
     {
     	$nota_auditoria_id = $_REQUEST['nota_auditoria_id'];
		
          list($dbconn) = GetDBconn();
          $query_cerrar="UPDATE notas_auditoria 
                         SET estado='0'
                         WHERE nota_auditoria_id=".$nota_auditoria_id.";";               
          $result = $dbconn->Execute($query_cerrar);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
					$this->frmError["MensajeError"]="EL CASO FUE CERRADO.";      
          $this->FormaMenus();
          return true;
     }
     
     
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
     
     
     function TraerNombre_Plan($plan)
     {
          GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();

          $query ="SELECT plan_descripcion
          		FROM planes
                    WHERE plan_id = ".$plan.";";
                    
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

	
	
  /**
   * Llama la forma ConfirmarAccion (forma de mensaje de dos botones).
   * @ access public
   * @ return boolean
   */
  function ConfirmarAccion()
  {
        $arreglo=$_REQUEST['arreglo'];
        $Cuenta=$_REQUEST['Cuenta'];
        $c=$_REQUEST['c'];
        $m=$_REQUEST['m'];
        $me=$_REQUEST['me'];
        $me2=$_REQUEST['me2'];
        $mensaje=$_REQUEST['mensaje'];
        $Titulo=$_REQUEST['titulo'];
        $boton1=$_REQUEST['boton1'];
        $boton2=$_REQUEST['boton2'];

        $this->salida=ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,array($c,$m,'user',$me,$arreglo),array($c,$m,'user',$me2,$arreglo));
        return true;
  }
	
     	
//------------------------------------------------------------------------------
}//fin clase user
?>

