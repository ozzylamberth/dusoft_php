<?php

/**
 * $Id: app_NotaOperatoria_user.php,v 1.19 2008/12/29 16:28:29 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Contiene los metodos para realizar las autorizaciones.
 */

class app_NotaOperatoria_user extends classModulo
{

    var $limit;
    var $conteo;

     function app_NotaOperatoria_user()
     {
          $this->limit=GetLimitBrowser();
          return true;
     }

     /**
     *
     */
     function main()
     {
          unset($_SESSION['BIO']);
          list($dbconn) = GetDBconn();
          GLOBAL $ADODB_FETCH_MODE;
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
     
          $query = "SELECT b.razon_social as descripcion1, b.empresa_id
                    FROM userpermisos_bioestadistica as a, empresas as b
                    WHERE a.usuario_id=".UserGetUID()." and a.empresa_id=b.empresa_id";
          
          $resulta=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al ejecutar el query de permisos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          while ($data = $resulta->FetchRow()) {
               $bioestadistica[$data['descripcion1']]= $data;
          }
     
          $url[0]='app';
          $url[1]='NotaOperatoria';
          $url[2]='user';
          $url[3]='Menu';
          $url[4]='Bio';
     
          $arreglo[0]='EMPRESA';
     
          $this->salida.= gui_theme_menu_acceso('NotaOperatoria',$arreglo,$bioestadistica,$url,ModuloGetURL('system','Menu'));
          return true;
     }

     function Menu()
     {
          if(empty($_SESSION['BIO']['EMPRESA']))
          {
               $_SESSION['BIO']['EMPRESA']=$_REQUEST['Bio']['empresa_id'];
               $_SESSION['BIO']['NOM_EMP']=$_REQUEST['Bio']['descripcion1'];
          }
          if(!$this->FormaMenus()){
               return false;
          }
          return true;
     }

     function LlamarFormaBuscarPaciente()
     {
          if(!$this->FormaBuscarPaciente()){
               return false;
          }
          return true;
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

     function BuscarPaciente()
     {
          $filtroTipoDocumento = '';
          $filtroDocumento='';
          $filtroNombres='';

          if($_REQUEST[TipoDocumento]!='')
          {   $filtroTipoDocumento=" AND c.tipo_id_paciente = '".$_REQUEST[TipoDocumento]."'";   }

          if (!empty($_REQUEST[Documento]))
          {   $filtroDocumento =" AND c.paciente_id ='".$_REQUEST[Documento]."'";   }

          if ($_REQUEST[Nombres] != '')
          {
               $a=explode(' ',$_REQUEST[Nombres]);
               foreach($a as $k=>$v)
               {
                    if(!empty($v))
                    {
                         $filtroNombres.=" and (upper(c.primer_nombre||' '||c.segundo_nombre||' '||
                                             c.primer_apellido||' '||c.segundo_apellido) like '%".strtoupper($_REQUEST[Nombres])."%')";
                    }
               }
          }
          if(empty($_REQUEST['Of'])){ $_REQUEST['Of']=0; }

          list($dbconn) = GetDBconn();
          if(empty($_REQUEST['paso']))
          {
               $query = "SELECT	c.tipo_id_paciente, c.paciente_id,
                                   c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre
                                   FROM pacientes as c
                                   WHERE c.paciente_id is not null
                                   $filtroTipoDocumento $filtroDocumento $filtroNombres";
               $result=$dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al buscar";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               if(!$result->EOF)
               {
                    $_SESSION['SPY']=$result->RecordCount();
               }
               $result->Close();
          }

          $query = "SELECT	c.tipo_id_paciente, c.paciente_id,
                    c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre
                    FROM pacientes as c
                    WHERE c.paciente_id is not null
                    $filtroTipoDocumento $filtroDocumento $filtroNombres
                    order by nombre
                    LIMIT ".$this->limit." OFFSET ".$_REQUEST['Of']."";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al buscar";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          while(!$result->EOF)
          {
               $var[]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
          }

          if(empty($var))
          {  $this->frmError["MensajeError"]="NO SE OBTUVO RESULTADOS.";  }

          $this->FormaBuscarPaciente($var);
          return true;
     }

     function LlamarModificarDatosPaciente()
     {
          unset($_SESSION['ADMISIONES']);
          $_SESSION['ADMISIONES']['PACIENTE']['paciente_id']=$_REQUEST['paciente'];
          $_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['tipoid'];
          $_SESSION['ADMISIONES']['PACIENTE']['ingreso']=$_REQUEST['ingreso'];

          $_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']['argumentos']=array();
          $_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']['contenedor']='app';
          $_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']['modulo']='NotaOperatoria';
          $_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']['tipo']='user';
          $_SESSION['ADMISIONES']['MODIFICAR']['RETORNO']['metodo']='FormaBuscarPaciente';

          $this->ReturnMetodoExterno('app','Admisiones','user','ModificarDatosPacienteExt');
          return true;
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
		$query="select DISTINCT(b.servicio),b.descripcion
                    FROM
                    departamentos a,
                    servicios b
                    WHERE 
                    a.empresa_id='".$_SESSION['BIO']['EMPRESA']."'
                    and a.servicio=b.servicio";
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

		$query="SELECT * 
          	   FROM system_hc_modulos;";
          
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
          
          
          /******descarga de variables switche de mostrar************/
          $ming=$_REQUEST['ming'];
          $meg=$_REQUEST['meg'];
          $mfil=$_REQUEST['mfil'];
          $mevol=$_REQUEST['mevol'];
          
          
          /******descarga de variables******/				
          $fechaIni=$_REQUEST['fechaini'];
          $fechaFin=$_REQUEST['fechafin'];
          $TipoId=$_REQUEST['TipoDocumento'];
          $PacienteId=$_REQUEST['Documento'];
          $servicio=$_REQUEST['servicio'];
          $evolucion=$_REQUEST['hc_evol'];
          $hc_modulo = $_REQUEST['tipo_historia'];
          
          /*******descarga de variables(codigo de diagnosticos,finalidad etc..)********/
          $diagnostico_ingreso=$_REQUEST['ing'];//diagnostico ingreso
          $diagnostico_ingreso=$_REQUEST['egreso'];//diagnostico egreso
          $finalidad=$_REQUEST['finalidad'];//diagnostico egreso
		
          //Cambio de Busqueda por nombre y apellido          
          $nom = explode(" ",$_REQUEST['nombres']);
     	
          //$nom=trim($_REQUEST['nombres']);
          
          if(($TipoId == -1 OR $TipoId == '*') AND empty($PacienteId) AND (empty($fechaIni) AND empty($fechaFin)) AND ($servicio == -1 OR $servicio == '*') AND ($hc_modulo == -1 OR $hc_modulo == '*') AND empty($evolucion) AND empty($_REQUEST['nombres']))
          {
               $this->uno=1;
               $this->frmError["MensajeError"]='POR FAVOR, USTED DEBE UTILIZAR ALGUN FILTRO PARA LA BUSQUEDA.';
               $this->FormaMetodoBuscar($Busqueda='',$Cuentas,$f=true);
               return true;
          }
          
          if(($TipoId == -1 OR $TipoId == '*') AND !empty($PacienteId))
          {
               $this->uno=1;
               $this->frmError["MensajeError"]='POR FAVOR, USTED DEBE UTILIZAR ALGUN FILTRO PARA LA BUSQUEDA.';
               $this->FormaMetodoBuscar($Busqueda='',$Cuentas,$f=true);
               return true;
          }
          
          if(empty($PacienteId) AND ($TipoId != -1 AND $TipoId != '*'))
          {
               $this->uno=1;
               $this->frmError["MensajeError"]='POR FAVOR, USTED DEBE UTILIZAR ALGUN FILTRO PARA LA BUSQUEDA.';
               $this->FormaMetodoBuscar($Busqueda='',$Cuentas,$f=true);
               return true;
          }
           
          if((!empty($fechaIni) AND empty($fechaFin)) OR (empty($fechaIni) AND !empty($fechaFin)))
          {
               $this->uno=1;
               $this->frmError["MensajeError"]='POR FAVOR, USTED DEBE UTILIZAR ALGUN FILTRO PARA LA BUSQUEDA.';
               $this->FormaMetodoBuscar($Busqueda='',$Cuentas,$f=true);
               return true;
          }
          
          /*********Armada de sqls********/
          if($TipoId <> -1 && $TipoId <> '*'){ $tipo=" AND a.tipo_id_paciente = '".$TipoId."'";}
          if($PacienteId){ $paciente="AND a.paciente_id ='".$PacienteId."'";}
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
          
          if(!empty($evolucion))
          {$sql_evol="AND c.evolucion_id='$evolucion'";}
                    
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
     
          $this->Buscar1($tipo,$paciente,$sql_serv,$nombre,$sql_fi,$sql_ff,$sql_evol,$NUM,$sql_modulo);
          $datos=$this->Buscar1($tipo,$paciente,$sql_serv,$nombre,$sql_fi,$sql_ff,$sql_evol,$NUM,$sql_modulo);
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
	function Buscar1($TipoId,$PacienteId,$sql_serv,$nom,$sql_fi,$sql_ff,$sql_evol,$NUM,$sql_modulo)
	{
    list($dbconn) = GetDBconn();
    $limit=$this->limit;
    list($dbconn) = GetDBconn();
    if(!empty($_SESSION['SPY']))
    {   $x=" LIMIT ".$this->limit." OFFSET $NUM";   }
    else
    {   $x='';   }

		$query = "SELECT  a.*,
                      b.*,
                      BTRIM(b.primer_nombre||' '||b.segundo_nombre||' ' || b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
                      e.descripcion,
                      d.descripcion AS desc
                    FROM 
                    (
                         SELECT DISTINCT
                         	  a.ingreso, a.estado, a.fecha_ingreso, 
                                a.tipo_id_paciente, a.paciente_id,
                                c.departamento
                         
                         FROM   ingresos a,
                         	  hc_evoluciones AS c
                         
                         WHERE c.ingreso = a.ingreso  
                         $TipoId $PacienteId
                         $sql_fi
                         $sql_ff
                    	AND c.estado = '0'
                    	$sql_evol
                    	$sql_modulo

                    ) AS a,
                         pacientes AS b,
                         departamentos AS d,
                         servicios AS e 
                    
                    WHERE b.tipo_id_paciente = a.tipo_id_paciente
                    AND b.paciente_id = a.paciente_id 
                    $nom
                    AND d.departamento = a.departamento 
                    AND d.servicio = e.servicio
                    $sql_serv
                    ORDER BY a.ingreso ASC
                    $x;";
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
	
	/***********funcion que trae la informacion de las evoluciones**************/
	function Get_Info_Evoluciones($ingreso)
 	{
          list($dbconn) = GetDBconn();
          $busca=  "SELECT f.usuario_id, h.nombre,f.evolucion_id,f.fecha_cierre,l.descripcion
                    FROM
                    hc_evoluciones as f ,profesionales_usuarios as g,profesionales as h, 	
                    tipos_profesionales l
                    WHERE
                    f.usuario_id=g.usuario_id
                    and f.ingreso='$ingreso' 
                    and f.estado='0'
                    and g.tercero_id=h.tercero_id 
                    and g.tipo_tercero_id=h.tipo_id_tercero 
                    and l.tipo_profesional=h.tipo_profesional
                    ORDER BY 	f.evolucion_id ASC		 ";
          $resulta=$dbconn->execute($busca);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                   $this->fileError = __FILE__;
                                   $this->lineError = __LINE__;
               return false;
          }
     
          while(!$resulta->EOF)
          {
               $var[]=$resulta->GetRowAssoc($ToUpper = false);
               $resulta->MoveNext();
          }
          return $var;
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
	
	/***********funcion que trae la informacion de las evoluciones**************/
	function Get_Info_Diagnosticos($ingreso,$codigos,$clave)
 	{
          list($dbconn) = GetDBconn();
          if(!empty($codigos))
          {
               $cadena=$this->Prepar_Cadena($codigos);
               $sql="AND a.tipo_diagnostico_id IN(".$cadena.")";
          }
          
          if($clave=='1')//si es 1 es por q es ingreso, si es 2 es por q es egreso
          {	
               $tabla='hc_diagnosticos_ingreso';
               $order="ORDER BY sw_principal DESC,x.fecha_cierre ASC";
          }
          else
          {
               $tabla='hc_diagnosticos_egreso';
               $order="ORDER BY x.fecha_cierre ASC";
          }
			
     	$busca=  "SELECT b.diagnostico_nombre,b.diagnostico_id
                    FROM
                    $tabla a, diagnosticos b, hc_evoluciones x
                    WHERE
                    a.tipo_diagnostico_id=b.diagnostico_id
                    AND a.evolucion_id=x.evolucion_id
                    AND x.ingreso='$ingreso'
                    AND x.estado='0'
                    $sql
                    $order";
          $resulta=$dbconn->execute($busca);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $this->fileError = __FILE__;
               $this->lineError = __LINE__;
               return false;
          }

          while(!$resulta->EOF)
          {
               $var[]=$resulta->GetRowAssoc($ToUpper = false);
               $resulta->MoveNext();
          }
          return $var;
     }
	
	function Get_Finalidad($ingreso,$codigos)
	{
		if(!empty($codigos))
		{
			$cadena=$this->Prepar_Cadena($codigos);
			$sql="AND b.tipo_finalidad_id IN(".$cadena.")";
		}
		list($dbconn) = GetDBconn();
		$query = "SELECT detalle,a.tipo_finalidad_id,b.evolucion_id
                    FROM   hc_tipos_finalidad as a
                    
                    join hc_evoluciones as x on( x.estado='0' AND x.ingreso='$ingreso' )
                    join hc_finalidad as b on (a.tipo_finalidad_id=b.tipo_finalidad_id 
                    and b.evolucion_id=x.evolucion_id)
                    $sql
                    order by tipo_finalidad_id desc;";
          $resulta=$dbconn->execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $this->fileError = __FILE__;
               $this->lineError = __LINE__;
	          return false;
          }

          while(!$resulta->EOF)
          {
               $var[]=$resulta->GetRowAssoc($ToUpper = false);
               $resulta->MoveNext();
          }
          return $var;
	}
     
     function Get_Info_NotasOperatorias($ingreso)
     {
		list($dbconn) = GetDBconn();
		$query = "SELECT A.hc_nota_operatoria_cirugia_id, A.evolucion_id, A.programacion_id
          		FROM hc_notas_operatorias_cirugias AS A,
                         hc_evoluciones AS B
                    WHERE B.ingreso = ".$ingreso."
                    AND A.evolucion_id = B.evolucion_id;";
          $resulta=$dbconn->execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $this->fileError = __FILE__;
               $this->lineError = __LINE__;
	          return false;
          }

          while(!$resulta->EOF)
          {
               $var[]=$resulta->GetRowAssoc($ToUpper = false);
               $resulta->MoveNext();
          }
          return $var;
     }
     
     function Get_Info_Justificacion_NOPOS($ingreso)
     {
		list($dbconn) = GetDBconn();
		$query = "SELECT A.justificacion_no_pos_id
          		FROM   hc_justificaciones_no_pos_hospitalaria_medicamentos AS A,
					  hc_formulacion_medicamentos AS B
                    WHERE  A.ingreso = ".$ingreso."
                    AND    B.ingreso = A.ingreso
                    AND    B.justificacion_no_pos_id = A.justificacion_no_pos_id;";
          $resulta=$dbconn->execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $this->fileError = __FILE__;
               $this->lineError = __LINE__;
	          return false;
          }

          while(!$resulta->EOF)
          {
               $var[]=$resulta->GetRowAssoc($ToUpper = false);
               $resulta->MoveNext();
          }
          return $var;
     }
	
	function GetDatosEpicrisis($ingreso)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();

		$query="SELECT *
                  FROM hc_epicrisis
                  WHERE ingreso=$ingreso";
						
		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - GetDatosEpicrisis - SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		else
		{
			if($result->RecordCount() > 0)
			{
				while(!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		$dbconn->CommitTrans();
		return $vars;
	}
	
	function ConsultarNotasAdministrativas($TipoDocumento,$Documento)
	{
		list($dbconn) = GetDBconn();
		$query="SELECT *
						FROM 	notas_administrativas_consulta_externa
						WHERE tipo_id_paciente = '$TipoDocumento'
						AND paciente_id = $Documento";
						
		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - GetDatosEpicrisis - SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if($result->RecordCount() > 0)
			{
				return true;
			}
			return false;
		}
	}
	
	function GetSoloLectura()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT sw_solo_lectura,sw_modificar_datos
			FROM userpermisos_bioestadistica
			WHERE usuario_id = ".UserGetUID()."";
						
		$result = $dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg()."[".get_class($this)."][".__LINE__."]";
			return false;
		}
		else
		{
			$vars=$result->GetRowAssoc($toUpper=false);
			return $vars;
		}
	}

//------------------------------------------------------------------------------
}//fin clase user
?>