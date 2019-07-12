<?php
	/**************************************************************************************
	* $Id: hc_PlanTerapeuticoHospitalizacion.php,v 1.29 2009/04/22 18:18:32 hugo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* $Revision: 1.29 $ 	
	* @author Hugo F. Manrique Arango
	*
	* Codigo tomado del submodulo de PlanTerapeutico.
	***************************************************************************************/
	class PlanTerapeuticoHospitalizacion extends hc_classModules
	{
		var $limit;
		var $conteo;

		function PlanTerapeuticoHospitalizacion() //Constructor Padre
		{
			$this->hc_classModules(); //constructor del padre

			$this->frmError = array();
			$this->error='';
			$this->empresa=SessionGetVar('SYSTEM_USUARIO_EMPRESA');
			$this->user_id=UserGetUID();

			return true;
		}
		/***********************************************************************
		* GetConsulta() llama a la funcion FrmConsulta del submoduloHijo HTML para obtiener el
		* HTML de listado y lo retorna a la funcion xxx del modulo
		*************************************************************************/
		function GetConsulta()//Obtiene el HTMLde tipo consulta
		{
			$this->FrmConsulta();
			return $this->salida;
		}

		/***********************************************************************
		* Esta metodo captura los datos de la impresión de la Historia Clinica.
		* @access private
		* @return text Datos HTML de la pantalla.
		************************************************************************/
		function GetReporte_Html()
		{
			$imprimir=$this->frmHistoria();
			if($imprimir==false)
			{
				return true;
			}
			return $imprimir;
		}
		/***********************************************************************
		* Esta función verifica si este submodulo fue utilizado para la atencion 
		* de un paciente.
		*
		* @access private
		* @return text Datos HTML de la pantalla.
		*************************************************************************/
		function GetEstado()
		{
			$pfj=$this->frmPrefijo;
			list($dbconn) = GetDBconn();
               
               // Verificacion de omisión de confirmación de medicamentos pendientes.
               $query="SELECT sw_omitir
                       FROM historias_clinicas_templates
                       WHERE hc_modulo = '".$this->hc_modulo."'
                       AND submodulo = '".$this->submodulo."';";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
               if($resulta->fields[0] == '0')
               {
                    $query="SELECT Count(A.sw_confirmacion_formulacion)
                            FROM hc_formulacion_medicamentos AS A,
                                 hc_formulacion_medicamentos_eventos AS B
                            WHERE A.ingreso = ".$this->ingreso."
                            AND A.num_reg = B.num_reg
                            AND A.sw_confirmacion_formulacion = '0'
                            AND B.usuario_id = ".UserGetUID().";";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al Cargar el Modulo";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         return false;
                    }
                    $i=0;
                    while(!$resulta->EOF)
                    {
                         $estado=$resulta->GetRowAssoc($ToUpper = false);
                         $resulta->MoveNext();
                         $i++;
                    }
                    
                    if ($estado[count] > 0)
                    {
                         return false;
                    }
                    else
                    {
                         return true;
                    }
               }
               else
               {
                    return true;
               }
		}
		/*************************************************************************
		* GetForma() llama a la funcion FrmForma del submoduloHijo HTML para 
		* obtiener el HTML del formulario y lo retorna a la funcion xxx del modulo
		**************************************************************************/
		function GetForma()
		{
			$pfj=$this->frmPrefijo;
			$action='';
			if (!empty($_REQUEST['subModuloAction']))
			{
				$action=$_REQUEST['subModuloAction'];
			}
			if (!empty($_REQUEST['accion'.$pfj]))
			{
				$action=$_REQUEST['accion'.$pfj];
			}
			$this->FrmForma($action);
			return $this->salida;
		}
		/*********************** NUEVAS FUNCIONALIDADES **********************/
		
	  /********************************************************************
		*
		*********************************************************************/  
		function Medicamentos_Frecuentes_Diagnostico()
		{
			$sql .= "SELECT	CASE WHEN ME.sw_pos = 1 THEN 'POS' ";
			$sql .= "				ELSE 'NO POS' END AS item, ";
			$sql .= "				HD.codigo_medicamento,";
			$sql .= "				ID.descripcion AS producto, ";
			$sql .= "				IA.descripcion AS principio_activo, ";
			$sql .= "				ME.concentracion_forma_farmacologica AS cff, ";
			$sql .= "				ME.unidad_medida_medicamento_id AS ummi,";
			$sql .= "				IM.descripcion AS umm, ";
			$sql .= "				IF.descripcion AS forma, ";
			$sql .= " 			IF.unidad_dosificacion, ";
			$sql .= "				IF.cod_forma_farmacologica,";
			$sql .= "				HM.descripcion AS categoria ";
			$sql .= "FROM		hc_formulacion_hospitalaria_grupos_medicamentos HM,";
			$sql .= "				hc_formulacion_hospitalaria_grupos_medicamentos_d HD,";
			$sql .= "				hc_formulacion_hospitalaria_templates HT,";
			$sql .= "				inv_med_cod_principios_activos AS IA, ";
			$sql .= "				inventarios_productos AS ID, ";
			$sql .= "				inv_med_cod_forma_farmacologica AS IF, ";
			$sql .= "				medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IM ";
			$sql .= "				ON(ME.unidad_medida_medicamento_id = IM.unidad_medida_medicamento_id) ";
			$sql .= "WHERE 	HD.codigo_medicamento = ID.codigo_producto ";
			$sql .= "AND 		ID.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
			$sql .= "AND 		ID.estado = '1' ";
			$sql .= "AND		HM.grupo_id = HD.grupo_id ";
			$sql .= "AND		HD.codigo_medicamento = ME.codigo_medicamento ";
			$sql .= "AND		HT.hc_modulo = '".$this->hc_modulo."' ";
			$sql .= "AND		HM.grupo_id = HT.grupo_id ";
			$sql .= "AND 		IF.cod_forma_farmacologica = ME.cod_forma_farmacologica ";
			$sql .= "ORDER BY categoria,producto ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$i=0;
			$medicamentos = array();
			while (!$rst->EOF)
			{
				$medicamentos[$rst->fields[10]][$i] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
				$i++;
			}

			$rst->Close();
			return $medicamentos;
		}
		/********************************************************************
		*
		*********************************************************************/  
		function ObtenerMezclas()
		{
			$sql .= "SELECT	CASE WHEN ME.sw_pos = 1 THEN 'POS' ";
			$sql .= "				ELSE 'NO POS' END AS item, ";
			$sql .= "				HD.codigo_medicamento AS codigo_producto,";
			$sql .= "				ID.descripcion AS producto, ";
			$sql .= "				IA.descripcion AS principio_activo, ";
			$sql .= "				ME.concentracion_forma_farmacologica AS cff, ";
			$sql .= "				ME.unidad_medida_medicamento_id AS ummi,";
			$sql .= "				IM.descripcion AS umm, ";
			$sql .= "				IF.descripcion AS forma, ";
			$sql .= " 			IF.unidad_dosificacion, ";
			$sql .= "				IF.cod_forma_farmacologica,";
			$sql .= "				HM.descripcion AS mezcla, ";
			$sql .= "				HM.mezcla_id, ";
			$sql .= "				HH.descripcion AS categoria, ";
			$sql .= "				SE.sw_soluciones ";
			$sql .= "FROM		hc_formulacion_hospitalaria_mezclas HM,";
			$sql .= "				hc_formulacion_hospitalaria_mezclas_d HD,";
			$sql .= "				hc_formulacion_hospitalaria_mezclas_grupos HH,";
			$sql .= "				hc_formulacion_hospitalaria_mezclas_grupos_d HG,";
			$sql .= "				hc_formulacion_hospitalaria_mezclas_templates HT, ";
			$sql .= "				inv_med_cod_principios_activos AS IA, ";
			$sql .= "				inventarios_productos AS ID, ";
			$sql .= "				inv_med_cod_forma_farmacologica AS IF, ";
			$sql .= "				medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IM ";
			$sql .= "				ON(ME.unidad_medida_medicamento_id = IM.unidad_medida_medicamento_id) ";
			$sql .= "				LEFT JOIN ";
			$sql .= "				(	SELECT	HM.sw_soluciones, ";
			$sql .= "									HD.codigo_medicamento ";
			$sql .= "					FROM		hc_formulacion_hospitalaria_grupos_medicamentos_mezclas HM,";
			$sql .= "									hc_formulacion_hospitalaria_grupos_medicamentos_mezclas_d HD ";
			$sql .= "					WHERE		HM.grupo_id = HD.grupo_id ";
			$sql .= "				) AS SE ";
			$sql .= "				ON(ME.codigo_medicamento = SE.codigo_medicamento) ";
			$sql .= "WHERE 	HD.codigo_medicamento = ID.codigo_producto ";
			$sql .= "AND 		ID.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
			$sql .= "AND 		ID.estado = '1' ";
			$sql .= "AND		HM.mezcla_id = HD.mezcla_id ";
			$sql .= "AND		HD.codigo_medicamento = ME.codigo_medicamento ";
			$sql .= "AND		HG.grupo_mezcla_id = HH.grupo_mezcla_id ";
			$sql .= "AND		HM.mezcla_id = HG.mezcla_id ";
			$sql .= "AND 		IF.cod_forma_farmacologica = ME.cod_forma_farmacologica ";
			$sql .= "AND		HT.grupo_mezcla_id = HH.grupo_mezcla_id ";
			$sql .= "AND		HT.hc_modulo = '".$this->hc_modulo."' ";
			$sql .= "ORDER BY categoria,mezcla ";

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$i=0;
			$medicamentos = array();
			$soluciones = array();
			
			while (!$rst->EOF)
			{
				$medicamentos[$rst->fields[12]][$rst->fields[10]][$i] = $rst->GetRowAssoc($ToUpper = false);
				$soluciones[$rst->fields[11]][$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
				$i++;
			}
			$rst->Close();
			SessionSetVar("SolucionesPrevias",$soluciones);
			
			return $medicamentos;
		}
		/********************************************************************
		*
		*********************************************************************/  
		function ObtenerGrupos()
		{
			$sql .= "SELECT	HH.descripcion  ";
			$sql .= "FROM		hc_formulacion_hospitalaria_mezclas_grupos HH ";
			$sql .= "ORDER BY 1 ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$grupos = array();
			while (!$rst->EOF)
			{
				$grupos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}

			$rst->Close();
			return $grupos;
		}
		/********************************************************************
		*
		*********************************************************************/  
    function Consulta_Solicitud_Medicamentos()
    {
			$pfj = $this->frmPrefijo;
			$dato = 3;
			if(($this->tipo_profesional=='1') || ($this->tipo_profesional=='2') || ($this->tipo_profesional=='3'))
				$dato =1;
			
			SessionSetVar("SolicitudAutorizacion",$this->tipo_profesional);
			
			$_SESSION['PROFESIONAL'.$this->frmPrefijo] = $dato;
			SessionSetVar("tipoProfesionalhc",$dato);

	    $sql  = "SELECT ID.descripcion AS producto, ";
			$sql .= "				IA.descripcion AS principio_activo, ";
			$sql .= "				IM.descripcion AS umm, ";
			$sql .= "				HA.nombre, ";
			$sql .= "				FM.dosis, ";
			$sql .= "				FM.unidad_dosificacion, ";
			$sql .= "				FM.cantidad, ";
			$sql .= "				FM.observacion, ";
			$sql .= "				CASE WHEN FM.sw_estado = '8' AND FM.sw_confirmacion_formulacion = '1' THEN '0'";
			$sql .= "						 WHEN FM.sw_estado = '0' AND FM.sw_confirmacion_formulacion = '0' THEN '8'";
			$sql .= "						ELSE FM.sw_estado END AS sw_estado, ";
			$sql .= "				FM.codigo_producto, ";
			$sql .= "				FM.frecuencia, ";
			$sql .= "				FM.via_administracion_id, ";
			$sql .= "				CASE WHEN ME.sw_pos = 1 THEN 'POS'";
			$sql .= "						 ELSE 'NO POS' END AS item, ";
			$sql .= "				SU.nombre AS med_formula, ";
			$sql .= "				SD.nombre AS med_modifica, ";
			$sql .= "				SU.usuario_id, ";
			$sql .= "				FM.sw_confirmacion_formulacion, ";
			$sql .= "				FH.usuario_registro, ";
			$sql .= "				FM.num_reg_formulacion, ";
			$sql .= "				FM.sw_requiere_autorizacion_no_pos, ";
			$sql .= "				FM.justificacion_no_pos_id, ";
			$sql .= "				FM.dias_tratamiento, ";
			$sql .= "				ID.sw_solicita_autorizacion ";
			$sql .= "FROM 	inv_med_cod_principios_activos AS IA, ";
			$sql .= "				hc_formulacion_medicamentos FM,";
			$sql .= "				hc_formulacion_medicamentos_eventos FH,";
			$sql .= "				inventarios_productos ID, ";
			$sql .= "				medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IM ";
			$sql .= "				ON(ME.unidad_medida_medicamento_id = IM.unidad_medida_medicamento_id), ";
			$sql .= "				hc_vias_administracion HA, ";
			$sql .= "				system_usuarios SU, ";
			$sql .= "				system_usuarios SD ";
			$sql .= "WHERE	ID.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
			$sql .= "AND 		FM.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND		FM.ingreso = ".$this->ingreso." ";
			$sql .= "AND		FH.num_reg = FM.num_reg ";
			$sql .= "AND		SU.usuario_id = FH.usuario_id ";
			$sql .= "AND		SD.usuario_id = FH.usuario_registro ";
			$sql .= "AND		HA.via_administracion_id = FM.via_administracion_id ";
			$sql .= "ORDER BY FM.sw_estado,producto ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$medica = array();
			$datos = array();
			while (!$rst->EOF)
			{
				$medica[$rst->fields[8]][] = $rst->GetRowAssoc($ToUpper = false);
				$datos[$rst->fields[9]] = $rst->GetRowAssoc($ToUpper = false);
				if($rst->fields[8] == '1')	$datos[$rst->fields[9]]['activar'] = "1";
				$rst->MoveNext();
			}
			SessionSetVar("MedicamentosFormulados",$datos); 
			return $medica;
		} 
		/*******************************************************************************
		* Funcion donde se obtuiene la informacion de las soluciones
		********************************************************************************/
		function FormulacionSoluciones()
    {
	    $sql  = "SELECT FM.num_mezcla, ";			
			$sql .= "				FM.volumen_infusion, ";
			$sql .= "				FM.unidad_volumen, ";
			$sql .= "				FM.cantidad, ";
			$sql .= "				FM.observacion, ";
			$sql .= "				CASE WHEN FM.sw_estado = '8' THEN '0'";
			$sql .= "						ELSE FM.sw_estado END AS sw_estado, ";
			$sql .= "				FD.codigo_producto,";
			$sql .= "				FD.sw_solucion, ";
			$sql .= "				FD.cantidad as cmedicamento, ";
	    $sql .= "				ID.descripcion AS producto, ";
			$sql .= "				IA.descripcion AS principio_activo, ";
			$sql .= "				IM.descripcion AS umm, ";
			$sql .= "				CASE WHEN ME.sw_pos = 1 THEN 'POS'";
			$sql .= "						 ELSE 'NO POS' END AS item, ";
			$sql .= "				SU.nombre AS med_formula, ";
			$sql .= "				FD.dosis, ";
			$sql .= "				FD.unidad_dosificacion, ";
			$sql .= "				SU.usuario_id ";
			$sql .= "FROM 	hc_formulacion_mezclas FM,";
			$sql .= "				hc_formulacion_mezclas_detalle FD,";
			$sql .= "				inventarios_productos ID, ";
			$sql .= "				inv_med_cod_principios_activos AS IA,";
			$sql .= "				medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IM ";
			$sql .= "				ON(ME.unidad_medida_medicamento_id = IM.unidad_medida_medicamento_id), ";
			$sql .= "				hc_formulacion_mezclas_eventos FH, ";
			$sql .= "				system_usuarios SU ";
			$sql .= "WHERE	ID.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
			$sql .= "AND 		FD.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND 		FD.num_mezcla = FM.num_mezcla ";
			$sql .= "AND		FM.ingreso = ".$this->ingreso." ";
			$sql .= "AND		FH.num_reg = FM.num_reg ";
			$sql .= "AND 		FH.usuario_id = SU.usuario_id ";

			$sql .= "ORDER BY FM.sw_estado,FD.sw_solucion DESC ";
			
 			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$soluciones = array();
			$datos = array();
			while (!$rst->EOF)
			{
				$soluciones[$rst->fields[5]][$rst->fields[0]][$rst->fields[6]] = $rst->GetRowAssoc($ToUpper = false);
				
				$datos[$rst->fields[0]][$rst->fields[6]] = $rst->GetRowAssoc($ToUpper = false);
				$datos[$rst->fields[0]][0]['sw_estado'] = $rst->fields[5];
				
				if($rst->fields[5] == '1')	$datos[$rst->fields[0]][0]['activar'] = "1";
				$rst->MoveNext();
			}
			SessionSetVar("SolucionesFormuladas",$datos);
			
			return $soluciones;
		}
		/*******************************************************************************
		*
		********************************************************************************/
		function Busqueda_Avanzada_Medicamentos()
		{
			$pfj = $this->frmPrefijo;
			$where = "";
			
			$sql .= "SELECT CASE WHEN ME.sw_pos = 1 THEN 'POS'";
			$sql .= "						 ELSE 'NO POS' END AS item,";
			$sql .= "				IM.codigo_producto, ";
			$sql .= "				ME.codigo_medicamento, ";
			$sql .= "				IM.descripcion as producto, ";
			$sql .= "				IU.descripcion AS umm, ";
			$sql .= "				ME.concentracion_forma_farmacologica AS cff,";
			$sql .= "				ME.unidad_medida_medicamento_id AS ummi,";
			$sql .= "				ME.factor_conversion, ";
			$sql .= "				ME.factor_equivalente_mg,";
			$sql .= "				IA.descripcion AS principio_activo,";
			$sql .= "				IF.descripcion AS forma,";
			$sql .= "				IF.unidad_dosificacion,";
			$sql .= "				IF.cod_forma_farmacologica, ";
			$sql .= "				IM.sw_solicita_autorizacion ";
			
			if(!$this->bodega)
			{
				$where .= "FROM 	inventarios_productos IM, ";
			}
			else
			{
				$sql .= "					,BC.existencia ";
				$where .= "FROM 	inventarios_productos IM LEFT JOIN ";
				$where .= "				hc_bodegas_consultas BC ";
				$where .= "				ON(BC.bodega_unico='".$this->bodega."') ";
				$where .= "				LEFT JOIN existencias_bodegas EB ";
				$where .= "				ON(	EB.empresa_id = BC.empresa_id AND ";
				$where .= "					EB.centro_utilidad = BC.centro_utilidad AND ";
				$where .= "					EB.bodega = BC.bodega AND ";
				$where .= "					IM.codigo_producto = BC.codigo_producto ";
				$where .= "				),";
			}
			
			$where .= "				inv_med_cod_principios_activos IA,  ";
			$where .= "				inv_med_cod_forma_farmacologica IF,  ";
			$where .= "				inventarios IT,  ";
			$where .= "				medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IU ";
			$where .= "				ON(ME.unidad_medida_medicamento_id = IU.unidad_medida_medicamento_id) ";
			$where .= "WHERE	IM.codigo_producto = ME.codigo_medicamento ";
			$where .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
			$where .= "AND 		ME.cod_forma_farmacologica = IF.cod_forma_farmacologica ";
			$where .= "AND 		IM.estado = '1' ";
			$where .= "AND 		IT.estado = '1' ";
			$where .= "AND 		IT.empresa_id = '".$this->empresa_id."' ";
			$where .= "AND 		IT.codigo_producto = IM.codigo_producto ";
			
			$producto = $_REQUEST['producto'.$pfj];
			$principio_activo = $_REQUEST['principio_activo'.$pfj];
			
			if ($producto != '') $where .= "AND		IM.descripcion ILIKE '%".$producto."%'";
			if ($principio_activo != '') $where .= "AND 		IA.descripcion ILIKE '%".$principio_activo."%'";
			
			$this->ProcesarSqlConteo("SELECT COUNT(*) $where");
			
			$orden = "producto";
			if($_REQUEST['orden']) $orden = $_REQUEST['orden'];
			$sql .= $where;
			$sql .= "ORDER BY $orden ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = SessionGetVar("MedicamentosSeleccionados");
			$retorno = array();
			while(!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			SessionSetVar("MedicamentosSeleccionados",$datos);
			return $retorno;
		}
		/******************************************************************************* 
		* Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
		* importantes a la hora de referenciar al paginador
		* 
		* @param String Cadena que contiene la consulta sql del conteo 
		* @param int numero que define el limite de datos,cuando no se desa el del 
		* 			 usuario,si no se pasa se tomara por defecto el del usuario 
		* @return boolean 
		********************************************************************************/
		function ProcesarSqlConteo($consulta,$limite=null)
		{
			$this->offset = 0;
			$this->paginaActual = 1;
			if($limite == null)
			{
				$this->limit = UserGetVar(UserGetUID(),'LimitRowsBrowser');
				if(!$this->limit)
					$this->limit = 20;
			}
			else
			{
				$this->limit = $limite;
			}
			
			if($_REQUEST['offset'])
			{
				$this->paginaActual = intval($_REQUEST['offset']);
				if($this->paginaActual > 1)
				{
					$this->offset = ($this->paginaActual - 1) * ($this->limit);
				}
			}		

			if(!$result = $this->ConexionBaseDatos($consulta))
				return false;

			if(!$result->EOF)
			{
				$this->conteo = $result->fields[0];
				$result->MoveNext();
			}
			$result->Close();
			return true;
		}
		/********************************************************************
		*
		*********************************************************************/  
    function ConsultaMedicamentosFormulados()
    {
			$sql  = "SELECT FM.num_reg, ";
			$sql .= "				ID.descripcion AS producto, ";
			$sql .= "				IA.descripcion AS principio_activo, ";
			$sql .= "				IM.descripcion AS umm, ";
			$sql .= "				HA.nombre, ";
			$sql .= "				FM.dosis, ";
			$sql .= "				FM.unidad_dosificacion, ";
			$sql .= "				FM.cantidad, ";
			$sql .= "				FM.observacion, ";
			$sql .= "				FM.sw_estado, ";				
			$sql .= "				FM.codigo_producto, ";
			$sql .= "				FM.frecuencia, ";
			$sql .= "				FM.via_administracion_id, ";
			$sql .= "				TO_CHAR(FM.fecha_registro,'DD/MM/YYYY HH24:MI') AS fecha, ";
			$sql .= "				CASE WHEN ME.sw_pos = 1 THEN 'POS'";
			$sql .= "						 ELSE 'NO POS' END AS item,";
			$sql .= "				SU.nombre AS usuario, ";
			$sql .= "				SE.observacion_suministro, ";
			$sql .= "				SE.fecha_suministro, ";
			$sql .= "				SE.usuario_suministro, ";
			$sql .= "				SE.cantidad_suministrada, ";
			$sql .= "				SE.cantidad_aprovechada, ";
			$sql .= "				SE.cantidad_perdidas, ";
			$sql .= "				ID.unidad_id ";
			$sql .= "FROM 	inv_med_cod_principios_activos AS IA, ";
			$sql .= "				hc_formulacion_medicamentos_eventos FM LEFT JOIN ";
			$sql .= "				(	SELECT 	HS.observacion AS observacion_suministro, ";
			$sql .= "									TO_CHAR(HS.fecha_realizado,'DD/MM/YYYY HH24:MI') AS fecha_suministro, ";
			$sql .= "									HS.num_reg_formulacion, ";
			$sql .= "									HS.cantidad_suministrada, ";
			$sql .= "									HS.cantidad_aprovechada, ";
			$sql .= "									HS.cantidad_perdidas, ";				
			$sql .= "									US.nombre AS usuario_suministro ";
			$sql .= "					FROM		hc_formulacion_suministro_medicamentos HS, ";
			$sql .= "									system_usuarios US  ";
			$sql .= "					WHERE		HS.usuario_id_control = US.usuario_id ";
			$sql .= " 				AND			HS.sw_estado = '1' ";
			$sql .= "					ORDER BY fecha_suministro DESC ) AS SE ";
			$sql .= "				ON(SE.num_reg_formulacion = FM.num_reg), ";
			$sql .= "				hc_formulacion_medicamentos_historico FH, ";
			$sql .= "				inventarios_productos ID, ";
			$sql .= "				medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IM ";
			$sql .= "				ON(ME.unidad_medida_medicamento_id = IM.unidad_medida_medicamento_id), ";
			$sql .= "				hc_vias_administracion HA, ";
			$sql .= "				system_usuarios SU ";
			$sql .= "WHERE	ID.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
			$sql .= "AND 		FM.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND		FH.num_reg = FM.num_reg ";
			$sql .= "AND		FM.ingreso = ".$this->ingreso." ";
			$sql .= "AND		HA.via_administracion_id = FM.via_administracion_id ";
			$sql .= "AND		SU.usuario_id = FM.usuario_id ";
			$sql .= "ORDER BY FM.num_reg, SE.fecha_suministro ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$medica = array();
			while (!$rst->EOF)
			{
				$medica['formulacion'][$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$medica['suministro'][$rst->fields[0]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			return $medica;
		}
		/********************************************************************
		*
		*********************************************************************/  
    function ConsultaAccionesMedicamentos()
    {
			$sql  = "SELECT FH.num_reg, ";
			$sql .= "				ID.descripcion AS producto, ";
			$sql .= "				IA.descripcion AS principio_activo, ";
			$sql .= "				IM.descripcion AS umm, ";
			$sql .= "				HA.nombre, ";
			$sql .= "				FM.dosis, ";
			$sql .= "				FM.unidad_dosificacion, ";
			$sql .= "				FM.cantidad, ";
			$sql .= "				FM.observacion, ";			
			$sql .= "				FM.codigo_producto, ";
			$sql .= "				FM.frecuencia, ";
			$sql .= "				FM.via_administracion_id, ";
			$sql .= "				CASE WHEN FM.sw_estado = '8' THEN '0' ";
			$sql .= "						ELSE FM.sw_estado END AS sw_estado, ";	
			$sql .= "				TO_CHAR(FM.fecha_registro,'DD/MM/YYYY HH24:MI') AS fecha, ";
			$sql .= "				CASE WHEN ME.sw_pos = 1 THEN 'POS'";
			$sql .= "						 ELSE 'NO POS' END AS item,";
			$sql .= "				SU.nombre AS usuario, ";
			$sql .= "				FH.sw_observacion, ";
			$sql .= "				FH.sw_via_administracion_id, ";
			$sql .= "				FH.sw_unidad_dosificacion, ";
			$sql .= "				FH.sw_dosis, ";
			$sql .= "				FH.sw_frecuencia, ";		
			$sql .= "				FH.sw_cantidad ";
			$sql .= "FROM 	inv_med_cod_principios_activos AS IA, ";
			$sql .= "				hc_formulacion_medicamentos_eventos FM, ";
			$sql .= "				hc_formulacion_medicamentos_historico_d FH, ";
			$sql .= "				inventarios_productos ID, ";
			$sql .= "				medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IM ";
			$sql .= "				ON(ME.unidad_medida_medicamento_id = IM.unidad_medida_medicamento_id), ";
			$sql .= "				hc_vias_administracion HA, ";
			$sql .= "				system_usuarios SU ";
			$sql .= "WHERE	ID.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
			$sql .= "AND 		FM.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND		FH.num_reg_evento = FM.num_reg ";
			$sql .= "AND		FM.ingreso = ".$this->ingreso." ";
			$sql .= "AND		HA.via_administracion_id = FM.via_administracion_id ";
			$sql .= "AND		SU.usuario_id = FM.usuario_registro ";
			$sql .= "ORDER BY FH.num_reg_evento ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$medica = array();
			while (!$rst->EOF)
			{
				$medica[$rst->fields[0]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			return $medica;
		}
		/*******************************************************************************
		*
		********************************************************************************/
		function ConsultaSolucionesFormuladas($op)
    {
	    $sql = "";
			if($op == 2)
			{
				$sql  = "SELECT FM.num_mezcla, ";			
				$sql .= "				FM.volumen_infusion, ";
				$sql .= "				FM.unidad_volumen, ";
				$sql .= "				FM.cantidad, ";
				$sql .= "				FM.observacion, ";
				$sql .= "				CASE WHEN FM.sw_estado = '8' THEN '0'";
				$sql .= "						 ELSE FM.sw_estado END AS sw_estado,";
				$sql .= "				TO_CHAR(FM.fecha_registro,'DD/MM/YYYY HH24:MI') AS fecha, ";
				$sql .= "				FD.codigo_producto,";
				$sql .= "				FD.sw_solucion, ";
				$sql .= "				FD.cantidad as cmedicamento, ";
		    $sql .= "				ID.descripcion AS producto, ";
		    $sql .= "				ID.unidad_id, ";
				$sql .= "				IA.descripcion AS principio_activo, ";
				$sql .= "				IM.descripcion AS umm, ";
				$sql .= "				CASE WHEN ME.sw_pos = 1 THEN 'POS'";
				$sql .= "						 ELSE 'NO POS' END AS item,";
				$sql .= "				SU.nombre, ";
				$sql .= "				FD.dosis, ";
				$sql .= "				FD.unidad_dosificacion, ";
				$sql .= "				SE.observacion_suministro, ";
				$sql .= "				SE.fecha_suministro, ";
				$sql .= "				SE.usuario_suministro, ";
				$sql .= "				SE.cantidad_suministrada, ";
				$sql .= "				SE.cantidad_aprovechada, ";
				$sql .= "				SE.cantidad_perdidas ";	
				$sql .= "FROM 	hc_formulacion_mezclas_eventos FM,";
				$sql .= "				hc_formulacion_mezclas_detalle FD LEFT JOIN ";
				$sql .= "				(	SELECT 	HS.observacion AS observacion_suministro, ";
				$sql .= "									TO_CHAR(HS.fecha_realizado,'DD/MM/YYYY HH24:MI') AS fecha_suministro, ";
				$sql .= "									HS.num_mezcla, ";
				$sql .= "									HS.codigo_producto, ";
				$sql .= "									HS.cantidad_suministrada, ";
				$sql .= "									HS.cantidad_aprovechada, ";
				$sql .= "									HS.cantidad_perdidas, ";	
				$sql .= "									US.nombre AS usuario_suministro ";
				$sql .= "					FROM		hc_formulacion_suministro_soluciones HS, ";
				$sql .= "									system_usuarios US  ";
				$sql .= "					WHERE		HS.usuario_id_control = US.usuario_id ";
				$sql .= " 				AND			HS.sw_estado = '1' ) AS SE ";
				$sql .= "				ON(	SE.num_mezcla = FD.num_mezcla AND ";
				$sql .= "						SE.codigo_producto = FD.codigo_producto), ";
				$sql .= "				inventarios_productos ID, ";
				$sql .= "				inv_med_cod_principios_activos AS IA,";
				$sql .= "				medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IM ";
				$sql .= "				ON(ME.unidad_medida_medicamento_id = IM.unidad_medida_medicamento_id), ";
				$sql .= "				system_usuarios SU ";
				$sql .= "WHERE	ID.codigo_producto = ME.codigo_medicamento ";
				$sql .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
				$sql .= "AND 		FD.codigo_producto = ME.codigo_medicamento ";
				$sql .= "AND 		FD.num_mezcla = FM.num_mezcla ";
				$sql .= "AND		FM.ingreso = ".$this->ingreso." ";
				$sql .= "AND		FM.usuario_id = SU.usuario_id ";
				$sql .= "AND		FM.sw_estado <> '1' ";
				$sql .= "ORDER BY sw_estado,FD.sw_solucion DESC ";
			}
			else
			{
				$sql  = "SELECT DISTINCT FM.num_mezcla, ";			
				$sql .= "				FM.volumen_infusion, ";
				$sql .= "				FM.unidad_volumen, ";
				$sql .= "				FM.cantidad, ";
				$sql .= "				FM.observacion, ";
				$sql .= "				CASE WHEN FM.sw_estado = '8' THEN '0'";
				$sql .= "						 ELSE FM.sw_estado END AS sw_estado,";
				$sql .= "				TO_CHAR(FH.fecha_registro,'DD/MM/YYYY HH24:MI') AS fecha, ";
				$sql .= "				FD.codigo_producto,";
				$sql .= "				FD.sw_solucion, ";
				$sql .= "				FD.cantidad as cmedicamento, ";
		    $sql .= "				ID.descripcion AS producto, ";
		    $sql .= "				ID.unidad_id, ";
				$sql .= "				IA.descripcion AS principio_activo, ";
				$sql .= "				IM.descripcion AS umm, ";
				$sql .= "				CASE WHEN ME.sw_pos = 1 THEN 'POS'";
				$sql .= "						 ELSE 'NO POS' END AS item,";
				$sql .= "				SU.nombre, ";
				$sql .= "				FD.dosis, ";
				$sql .= "				FD.unidad_dosificacion, ";
				$sql .= "				SE.observacion_suministro, ";
				$sql .= "				SE.fecha_suministro, ";
				$sql .= "				SE.usuario_suministro, ";
				$sql .= "				SE.cantidad_suministrada, ";
				$sql .= "				SE.cantidad_aprovechada, ";
				$sql .= "				SE.cantidad_perdidas ";	
				$sql .= "FROM 	hc_formulacion_mezclas_eventos FH,";
				$sql .= "				hc_formulacion_mezclas FM,";
				$sql .= "				hc_formulacion_mezclas_detalle FD LEFT JOIN ";
				$sql .= "				(	SELECT 	HS.observacion AS observacion_suministro, ";
				$sql .= "									TO_CHAR(HS.fecha_realizado,'DD/MM/YYYY HH24:MI') AS fecha_suministro, ";
				$sql .= "									HS.num_mezcla, ";
				$sql .= "									HS.codigo_producto, ";
				$sql .= "									HS.cantidad_suministrada, ";
				$sql .= "									HS.cantidad_aprovechada, ";
				$sql .= "									HS.cantidad_perdidas, ";	
				$sql .= "									US.nombre AS usuario_suministro ";
				$sql .= "					FROM		hc_formulacion_suministro_soluciones HS, ";
				$sql .= "									system_usuarios US  ";
				$sql .= "					WHERE		HS.usuario_id_control = US.usuario_id ";
				$sql .= " 				AND			HS.sw_estado = '1') AS SE ";
				$sql .= "				ON(	SE.num_mezcla = FD.num_mezcla AND ";
				$sql .= "						SE.codigo_producto = FD.codigo_producto), ";
				$sql .= "				inventarios_productos ID, ";
				$sql .= "				inv_med_cod_principios_activos AS IA,";
				$sql .= "				medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IM ";
				$sql .= "				ON(ME.unidad_medida_medicamento_id = IM.unidad_medida_medicamento_id), ";
				$sql .= "				system_usuarios SU ";
				$sql .= "WHERE	ID.codigo_producto = ME.codigo_medicamento ";
				$sql .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
				$sql .= "AND 		FD.codigo_producto = ME.codigo_medicamento ";
				$sql .= "AND 		FD.num_mezcla = FM.num_mezcla ";
				$sql .= "AND 		FH.num_reg = FM.num_reg ";
				$sql .= "AND		FM.ingreso = ".$this->ingreso." ";
				$sql .= "AND 		FD.num_mezcla = FH.num_mezcla ";
				$sql .= "AND		FH.ingreso = ".$this->ingreso." ";
				$sql .= "AND		FH.usuario_id = SU.usuario_id ";
				$sql .= "AND		FM.sw_estado = '1' ";
				$sql .= "ORDER BY FM.num_mezcla,sw_estado,FD.sw_solucion DESC ";
			}
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$soluciones = array();
			while (!$rst->EOF)
			{
				if($rst->fields[5]<>'0')
				{
					$soluciones[$rst->fields[5]][$rst->fields[0]][$rst->fields[7]] = $rst->GetRowAssoc($ToUpper = false);
				}
				elseif($rst->fields[5] == '0')
				{
					$soluciones[0][$rst->fields[6]][$rst->fields[7]] = $rst->GetRowAssoc($ToUpper = false);
				}
				$rst->MoveNext();
			}
			return $soluciones;
		}
		/********************************************************************************
    * Consulta de los medicamentos de la canasta de Cirugia.
		********************************************************************************/
    function ConsultaCanastaMedica()
    {
      $sql = "SELECT A.*, 
                    (select nombre from system_usuarios where usuario_id = A.usuario_ordeno) as us_orden,
                    (select nombre from system_usuarios where usuario_id = A.usuario_suministro) as us_suministro,
                     B.descripcion
              FROM 	estacion_enfermeria_qx_iym_suministrados AS A,
                    inventarios_productos AS B
              WHERE ingreso=".$this->ingreso."
              AND 	A.codigo_producto = B.codigo_producto
              ORDER BY A.fecha_registro DESC;";
 			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
      return $datos;
    }
		/********************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		* 
		* @param string sentencia sql a ejecutar 
		* @return rst 
		*********************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug = true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			return $rst;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function FechaStamp($fecha)
    {
			if($fecha)
			{
				$fech = strtok ($fecha,"-");
				for($l=0;$l<3;$l++)
        {
					$date[$l]=$fech;
          $fech = strtok ("-");
        }
				return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
      }
		}
		/********************************************************************************
    * SeleccionFactorConversion
    *
    * Funcion que selecciona el factor de conversion de un medicamento
    * para su suministro en una unidad diferente
		*********************************************************************************/
    function SeleccionFactorConversion($codigo, $unidad, $unidad_dosificacion, $cantidad)
    {        
      $sql = "SELECT	factor_conversion 
							FROM		hc_formulacion_factor_conversion
							WHERE 	codigo_producto = '".$codigo."'
							AND 		unidad_id = '".trim($unidad)."'
							AND			unidad_dosificacion = '".trim($unidad_dosificacion)."';";

 			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			$cadenita = $cantidad." ".$unidad_dosificacion;
			if($datos['factor_conversion'])
				$cadenita = ($cantidad*$datos['factor_conversion'])." ".$unidad_dosificacion;
      
			return $cadenita;
    }
		/********************************************************************************
		*
		*********************************************************************************/
		function HoraStamp($hora)
		{
			$hor = strtok ($hora," ");
			for($l=0;$l<4;$l++)
			{
				$time[$l]=$hor;
				$hor = strtok (":");
			}
			$x = explode (".",$time[3]);
			return  $time[1].":".$time[2].":".$x[0];
		}
	}//End class
?>