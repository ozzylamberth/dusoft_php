<?php

/**
 * $Id: app_ImpresionHC_user.php,v 1.14 2009/08/11 21:28:23 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_ImpresionHC_user extends classModulo
{

     function main()
     {	
          $accion = ModuloGetURL($_REQUEST['ModuloRETORNO']['contenedor'],$_REQUEST['ModuloRETORNO']['modulo'],$_REQUEST['ModuloRETORNO']['tipo'],$_REQUEST['ModuloRETORNO']['metodo']);
          unset($_SESSION['IMPRESIONHC']);
          $_SESSION['IMPRESIONHC']['INGRESO']=$_REQUEST['ingreso'];
          $_SESSION['IMPRESIONHC']['EVOLUCION']=$_REQUEST['evolucion'];
          $_SESSION['IMPRESIONHC']['ACCION']=$accion;
          $this->FormaImpresionSolicitudes();
          return true;
     }

     function DatosPacienteIngreso($ingreso)
     {
          list($dbconn) = GetDBconn();
          $query = "select a.tipo_id_paciente, a.paciente_id,
                    b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido
                    from ingresos as a, pacientes as b
                    where a.ingreso=$ingreso and a.tipo_id_paciente=b.tipo_id_paciente
                    and a.paciente_id=b.paciente_id";
          $resulta=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
          }

          $_SESSION['IMPRESIONHC']['PACIENTE']['nombre_paciente'] = $resulta->fields[2];
          $_SESSION['IMPRESIONHC']['PACIENTE']['tipo_id_paciente'] = $resulta->fields[0];
          $_SESSION['IMPRESIONHC']['PACIENTE']['paciente_id'] = $resulta->fields[1];

          return true;
     }

     function DatosPacienteEvolucion($evolucion)
     {
          list($dbconn) = GetDBconn();
          $query = "select a.tipo_id_paciente, a.paciente_id,
                    b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido
                    from ingresos as a, pacientes as b, hc_evoluciones as c
                    where c.evolucion_id=$evolucion and c.ingreso=a.ingreso
                    and a.tipo_id_paciente=b.tipo_id_paciente
                    and a.paciente_id=b.paciente_id";
          $resulta=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
          }

          $_SESSION['IMPRESIONHC']['PACIENTE']['nombre_paciente'] = $resulta->fields[2];
          $_SESSION['IMPRESIONHC']['PACIENTE']['tipo_id_paciente'] = $resulta->fields[0];
          $_SESSION['IMPRESIONHC']['PACIENTE']['paciente_id'] = $resulta->fields[1];

          return true;
     }

	/**
	*
	*/
	function Reportesolicitudes()
	{
          IncludeLib('funciones_central_impresion');
          if(!empty($_SESSION['IMPRESIONHC']['EVOLUCION']))
          {	$var[0] = EncabezadoReporteEvolucion($_SESSION['IMPRESIONHC']['EVOLUCION'],$_SESSION['IMPRESIONHC']['PACIENTE']['tipo_id_paciente'],$_SESSION['IMPRESIONHC']['PACIENTE']['paciente_id']);  }
          else
          {	$var[0] = EncabezadoReporteIngreso($_SESSION['IMPRESIONHC']['INGRESO'],$_SESSION['IMPRESIONHC']['PACIENTE']['tipo_id_paciente'],$_SESSION['IMPRESIONHC']['PACIENTE']['paciente_id']);  }

          if (!IncludeFile("classes/reports/reports.class.php")) {
               $this->error = "No se pudo inicializar la Clase de Reportes";
               $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
               return false;
          }

          for($i=0; $i<sizeof($_SESSION['IMPRESIONHC']['ARR_SOLICITUDES']);$i++)
          {
               $var[$i+1]=$_SESSION['IMPRESIONHC']['ARR_SOLICITUDES'][$i];
          }

          $classReport = new reports;
          if($_REQUEST['pos']==1)
          {
               $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
               $reporte=$classReport->PrintReport('pos','app','Central_de_Autorizaciones','solicitudes',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);

               if(!$reporte){
                    $this->error = $classReport->GetError();
                    $this->mensajeDeError = $classReport->MensajeDeError();
                    unset($classReport);
                    return false;
               }

               $resultado=$classReport->GetExecResultado();
               unset($classReport);

               if(!empty($resultado[codigo])){
                         "El PrintReport retorno : " . $resultado[codigo] . "<br>";
               }

               $this->FormaImpresionSolicitudes();
               return true;
          }
          else
          {
               if ($_REQUEST['parametro_retorno'] == '1')
               {
                    IncludeLib("reportes/solicitudes");
                    GenerarSolicitud($var);
                    if(is_array($var))
                    {
                         $RUTA = $_ROOT ."cache/solicitudes".UserGetUID().".pdf";
                         $mostrar ="\n<script language='javascript'>\n";
                         $mostrar.="var rem=\"\";\n";
                         $mostrar.="  function abreVentana(){\n";
                         $mostrar.="    var nombre=\"\"\n";
                         $mostrar.="    var url2=\"\"\n";
                         $mostrar.="    var str=\"\"\n";
                         $mostrar.="    var ALTO=screen.height\n";
                         $mostrar.="    var ANCHO=screen.width\n";
                         $mostrar.="    var nombre=\"REPORTE\";\n";
                         $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
                         $mostrar.="    var url2 ='$RUTA';\n";
                         $mostrar.="    rem = window.open(url2, nombre, str)};\n";
                         $mostrar.="</script>\n";
                         $this->salida.="$mostrar";
                         $this->salida.="<BODY onload=abreVentana();>";
                    }
                    $this->ReturnMetodoExterno('app','ImpresionHC','user','FormaImpresionSolicitudes');
               }
               else
               {
                    IncludeLib("reportes/solicitudes");
                    $vector['evolucion']=$_SESSION['IMPRESIONHC']['EVOLUCION'];
                    $vector['ingreso']=$_SESSION['IMPRESIONHC']['INGRESO'];
                    $vector['TipoDocumento']=$_SESSION['IMPRESIONHC']['PACIENTE']['tipo_id_paciente'];
                    $vector['Documento']=$_SESSION['IMPRESIONHC']['PACIENTE']['paciente_id'];
                    $vector['Nombres']=$_SESSION['IMPRESIONHC']['PACIENTE']['nombre'];
                    GenerarSolicitud($vector);
                    $this->FormaImpresionSolicitudes($vector,2);
                    return true;
               }
          }
	}

	/**
	*
	*/
	function ReporteOrdenServicio()
	{
          IncludeLib('funciones_central_impresion');
          if(!empty($_SESSION['IMPRESIONHC']['EVOLUCION']))
          {	$var[0] = EncabezadoReporteEvolucion($_SESSION['IMPRESIONHC']['EVOLUCION'],$_SESSION['IMPRESIONHC']['PACIENTE']['tipo_id_paciente'],$_SESSION['IMPRESIONHC']['PACIENTE']['paciente_id']);  }
          else
          {	$var[0] = EncabezadoReporteIngreso($_SESSION['IMPRESIONHC']['INGRESO'],$_SESSION['IMPRESIONHC']['PACIENTE']['tipo_id_paciente'],$_SESSION['IMPRESIONHC']['PACIENTE']['paciente_id']);  }

          if (!IncludeFile("classes/reports/reports.class.php")) {
               $this->error = "No se pudo inicializar la Clase de Reportes";
               $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
               return false;
          }

          list($dbconn) = GetDBconn();
          $query = "select a.*,
                    e.numero_orden_id,e.sw_estado, e.fecha_vencimiento, e.cantidad,
                    e.hc_os_solicitud_id, e.fecha_activacion, e.fecha_refrendar, e.cargo_cups,
                    f.descripcion, g.cargo, g.departamento, l.descripcion as desdpto,
                    h.cargo as cargoext,  i.plan_proveedor_id, i.plan_descripcion as planpro,
                    j.sw_estado, a.observacion,
                    z.tarifario_id, z.cargo, y.requisitos,
                    x.nombre_tercero as nompro, x.direccion  as dirpro, x.telefono as telpro,
                    s.descripcion as descar, q.evolucion_id, a.semanas_cotizadas, a.plan_id,
                    a.servicio, a.rango, n.observacion as obsapoyo, m.observacion as obsinter,
                    m.especialidad,AB.descripcion as especialidad_nombre, BB.observacion as obsnoqx
                    from os_ordenes_servicios as a
                    join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id)
                    join cups as f  on(e.cargo_cups=f.cargo)
                    left join os_internas as g on (e.numero_orden_id=g.numero_orden_id)
                    left join departamentos as l on(g.departamento=l.departamento)
                    left join os_externas as h on (e.numero_orden_id=h.numero_orden_id)
                    left join planes_proveedores as i on(h.plan_proveedor_id=i.plan_proveedor_id)
                    left join hc_os_solicitudes as q on(e.hc_os_solicitud_id=q.hc_os_solicitud_id)
                    left join hc_os_solicitudes_apoyod as n on(n.hc_os_solicitud_id=q.hc_os_solicitud_id)
                    left join hc_os_solicitudes_interconsultas as m on(m.hc_os_solicitud_id=q.hc_os_solicitud_id)
                    left join hc_os_solicitudes_no_quirurgicos as BB on(BB.hc_os_solicitud_id=q.hc_os_solicitud_id)
                    left join especialidades as AB on(AB.especialidad=m.especialidad )
                    left join os_maestro_cargos as z on(z.numero_orden_id=e.numero_orden_id)
                    join tarifarios_detalle as s on(s.cargo=z.cargo and s.tarifario_id=z.tarifario_id)
                    left join terceros as x on(x.tipo_id_tercero=i.tipo_id_tercero and x.tercero_id=i.tercero_id)
                    left join hc_apoyod_requisitos as y on(f.cargo=y.cargo),
                    autorizaciones as j
                    where a.orden_servicio_id=".$_REQUEST['orden']."
                    and a.tipo_afiliado_id='".$_REQUEST['afiliado']."'
                    and a.plan_id='".$_REQUEST['plan']."'
                    and a.tipo_id_paciente='".$_REQUEST['tipoid']."'
                    and a.paciente_id='".$_REQUEST['paciente']."'
                    and (a.autorizacion_int=j.autorizacion or a.autorizacion_ext=j.autorizacion)
                    and j.sw_estado=0
                    and q.evolucion_id is not null
                    order by e.numero_orden_id";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          else
          {
               while (!$result->EOF)
               {
                    $var[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
               }
          }
          $result->Close();

          $classReport = new reports;
          if($_REQUEST['pos']==1)
          {
               $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
               $reporte=$classReport->PrintReport('pos','app','CentroAutorizacion','ordenservicio',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);

               if(!$reporte){
                    $this->error = $classReport->GetError();
                    $this->mensajeDeError = $classReport->MensajeDeError();
                    unset($classReport);
                    return false;
               }

               $resultado=$classReport->GetExecResultado();
               unset($classReport);

               if(!empty($resultado[codigo])){
                    "El PrintReport retorno : " . $resultado[codigo] . "<br>";
               }

               $this->FormaImpresionSolicitudes();
               return true;
          }
          else
          {
               if ($_REQUEST['parametro_retorno'] == '1')
               {
                    IncludeLib("reportes/ordenservicio");
                    GenerarOrden($var);
                    if(is_array($var))
                    {
                         $RUTA = $_ROOT ."cache/ordenservicio".$var['orden'].".pdf";
                         $mostrar ="\n<script language='javascript'>\n";
                         $mostrar.="var rem=\"\";\n";
                         $mostrar.="  function abreVentana(){\n";
                         $mostrar.="    var nombre=\"\"\n";
                         $mostrar.="    var url2=\"\"\n";
                         $mostrar.="    var str=\"\"\n";
                         $mostrar.="    var ALTO=screen.height\n";
                         $mostrar.="    var ANCHO=screen.width\n";
                         $mostrar.="    var nombre=\"REPORTE\";\n";
                         $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
                         $mostrar.="    var url2 ='$RUTA';\n";
                         $mostrar.="    rem = window.open(url2, nombre, str)};\n";
                         $mostrar.="</script>\n";
                         $this->salida.="$mostrar";
                         $this->salida.="<BODY onload=abreVentana();>";
                    }
                    $this->ReturnMetodoExterno('app','ImpresionHC','user','FormaImpresionSolicitudes');
               }
               else
               {
                    IncludeLib("reportes/ordenservicio");
                    $vector['orden']=$_REQUEST['orden'];
                    GenerarOrden($vector);
                    $this->FormaImpresionSolicitudes($vector,3);
                    return true;
               }
          }
	}
     
     /*
     * Funcion Obtiene los datos del profesional que formulo los medicamentos a solicitar.
     */
     function ProfesionalFormulacion_Medicamento($usuario_id)
     {
          list($dbconn) = GetDBconn();
     	$query="SELECT usuario ||' - '|| nombre 
                  FROM system_usuarios
                  WHERE usuario_id = ".$usuario_id.";";
          $resultado = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error en la consulta";
               $this->mensajeDeError = "Ocurrió en error al intentar obtener los resultados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
     	list($Profesional) = $resultado->FetchRow();
          return $Profesional;
     }

     function ReporteFormulaMedica()
	{
          if (!IncludeFile("classes/reports/reports.class.php"))
          {
               $this->error = "No se pudo inicializar la Clase de Reportes";
               $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
               return false;
          }

		//cargando criterios.
          $criterio_paciente   = $_SESSION['IMPRESIONHC']['PACIENTE']['paciente_id'];
	     $criterio_tipo_id    = $_SESSION['IMPRESIONHC']['PACIENTE']['tipo_id_paciente'];
          $criterio_ingreso    = $_REQUEST['ingreso'];
          $criterio_evolucion  = $_REQUEST['evolucion_id'];
          //fin de criterios

          //Medicamentos Parametrizables
          if($_REQUEST['rango'] == 'uso_controlado')
          {
          	$parametros = "AND K.sw_uso_controlado = '1' AND A.codigo_producto = '".$_REQUEST['codigo_producto']."'";
               $uso_controlado = '1';
               
          }elseif($_REQUEST['rango'] == 'no_pos')
          {
			$parametros = "AND K.sw_pos = '0' AND A.codigo_producto = '".$_REQUEST['codigo_producto']."'";          
          }else
          {
          	$parametros = "AND K.sw_uso_controlado = '0' AND K.sw_pos = '1'";
          }
          //Medicamentos Parametrizables
		
          list($dbconn) = GetDBconn();
          GLOBAL $ADODB_FETCH_MODE;
          
          // Informacion de los datos del Paciente.//tipo_dptos departamento - tipo_mpios municipio
		$queryI="SELECT btrim(w.primer_nombre||' '||w.segundo_nombre||' '||w.primer_apellido||' '||w.segundo_apellido,'') AS paciente,
                         w.tipo_id_paciente, w.paciente_id, w.sexo_id, w.fecha_nacimiento,
                         w.residencia_direccion, w.residencia_telefono,
                         x.historia_numero, x.historia_prefijo, 
                         n.fecha_cierre, n.fecha, 
                         y.fecha_ingreso, y.ingreso, 
					v.tipo_afiliado_id, v.tipo_afiliado_nombre, 
                         t.plan_id, t.sw_tipo_plan, t.plan_descripcion, 
                         s.rango,
                         p.nombre_tercero, p.nombre_tercero AS cliente,
                         em.tipo_id_tercero AS tipo_empresa, em.id, em.razon_social,
												 dpto.departamento, mpio.municipio
                         
          	  
                 FROM 	pacientes AS w
                 		LEFT JOIN historias_clinicas AS x ON (w.paciente_id = x.paciente_id AND w.tipo_id_paciente = x.tipo_id_paciente),
                 		ingresos AS y,
                         hc_evoluciones AS n,
                         cuentas AS s
                         LEFT JOIN tipos_afiliado AS v ON (s.tipo_afiliado_id = v.tipo_afiliado_id),
                         planes AS t,
                         terceros AS p,
                         empresas AS em,
												 tipo_dptos dpto,
												 tipo_mpios mpio                 
                 WHERE   w.paciente_id = '".$criterio_paciente."'
                 AND 	w.tipo_id_paciente = '".$criterio_tipo_id."'
                 AND 	y.ingreso = ".$criterio_ingreso."
                 AND 	y.paciente_id = w.paciente_id
                 AND 	y.tipo_id_paciente = w.tipo_id_paciente
                 AND 	n.evolucion_id = ".$criterio_evolucion."
                 AND 	n.estado = '0'
                 AND 	n.ingreso = y.ingreso
                 AND 	n.numerodecuenta = s.numerodecuenta
                 AND 	em.empresa_id = s.empresa_id
                 AND 	s.plan_id = t.plan_id    
                 AND 	t.tercero_id = p.tercero_id
                 AND 	t.tipo_tercero_id = p.tipo_id_tercero        
								 AND 	w.tipo_pais_id = dpto.tipo_pais_id
								 AND 	w.tipo_dpto_id = dpto.tipo_dpto_id
								 AND 	w.tipo_pais_id = mpio.tipo_pais_id
								 AND 	w.tipo_dpto_id = mpio.tipo_dpto_id
								 AND 	w.tipo_mpio_id = mpio.tipo_mpio_id;";  
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($queryI);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;          

          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          
          $datosPaciente = $result->FetchRow();
					if($uso_controlado == '1')
					{$datosPaciente[uso_controlado] = '1';}
                         
          $queryM="SELECT B.evolucion_id,
          		   A.sw_estado, A.codigo_producto, A.cantidad, A.dosis, A.frecuencia,
                       A.unidad_dosificacion, A.observacion, B.fecha_registro, B.usuario_id,
                       H.descripcion as producto, 
                       C.descripcion as principio_activo,
                       K.sw_uso_controlado, 
                       CASE WHEN K.sw_pos = 1 THEN 'POS' ELSE 'NO POS' END AS item,
                       M.nombre AS via,
                       L.descripcion AS unidad,
                       'M' AS tipo_solicitud,
                       B.dias_tratamiento
          	 
                FROM   hc_formulacion_medicamentos AS A
                	   LEFT JOIN hc_vias_administracion AS M ON (A.via_administracion_id = M.via_administracion_id),
                	   hc_formulacion_medicamentos_eventos AS B,
                       inventarios_productos AS H,
                       medicamentos AS K,
                       inv_med_cod_principios_activos AS C,                       
                       unidades AS L,
                       hc_evoluciones N
                
                WHERE B.evolucion_id = ".$criterio_evolucion."
                AND   B.evolucion_id =  N.evolucion_id
                AND   N.estado = '0'
                AND   A.num_reg_formulacion = B.num_reg
                AND   A.sw_estado = '1'
                $parametros
                AND   H.codigo_producto = A.codigo_producto
                AND   K.codigo_medicamento = A.codigo_producto
                AND   K.cod_principio_activo = C.cod_principio_activo
                AND   H.unidad_id = L.unidad_id
                ORDER BY K.sw_pos, A.codigo_producto, B.evolucion_id;";                         
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($queryM);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;          

          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }                         
 		while ($data = $result->FetchRow())
          {
               $vectorM[] = $data;
          }        
          
          // Creando Vector de Medicamentos
          $vectorOriginal = array();
          if($vectorM)
          { array_push($vectorOriginal, $vectorM); }
          
          if($_REQUEST['soluciones'] != '1')
          {
               $queryS="SELECT DISTINCT A.num_mezcla,
                         B.evolucion_id,
                         A.sw_estado, DET.codigo_producto, A.cantidad,
                         A.volumen_infusion, A.unidad_volumen, B.usuario_id,
                         A.observacion, DET.cantidad AS cantidad_producto, 
                         DET.unidad_dosificacion AS unidad_suministro,
                         DET.dosis,
                         H.descripcion as producto, 
                         C.descripcion as principio_activo,
                         K.sw_uso_controlado, 
                         CASE WHEN K.sw_pos = 1 THEN 'POS' ELSE 'NO POS' END AS item,
                         L.descripcion AS unidad,
                         'S' AS tipo_solicitud
                    
                    FROM   hc_formulacion_mezclas AS A,
                         hc_formulacion_mezclas_eventos AS B,
                         hc_formulacion_mezclas_detalle AS DET,
                         inventarios_productos AS H,
                         medicamentos AS K,
                         inv_med_cod_principios_activos AS C,                       
                         unidades AS L,
                         hc_evoluciones N
                    
                    WHERE B.evolucion_id = ".$criterio_evolucion."
                    AND   B.evolucion_id =  N.evolucion_id
                    AND   N.estado = '0'
                    AND   A.num_mezcla = B.num_mezcla
                    AND   A.num_mezcla = DET.num_mezcla
                    AND   A.sw_estado = '1'
                    AND   H.codigo_producto = DET.codigo_producto
                    AND   K.codigo_medicamento = DET.codigo_producto
                    AND   K.cod_principio_activo = C.cod_principio_activo
                    AND   H.unidad_id = L.unidad_id
                    ORDER BY A.num_mezcla DESC;";
                    
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $result = $dbconn->Execute($queryS);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;          
     
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }                         
               while ($dataS = $result->FetchRow())
               {
                    $vectorS[] = $dataS;
               }
               
               if($vectorS)
               { array_push($vectorOriginal, $vectorS); }
          }
       
          $qdatosP = "SELECT	r.descripcion as tipo_profesional,
          			   	p.tipo_id_tercero as tipo_id_medico, p.tercero_id as medico_id, 
          				q.tarjeta_profesional, q.nombre AS nombre_tercero
                             
          		  FROM	hc_evoluciones AS n,
                      		profesionales AS q,
                              terceros AS p,
                              tipos_profesionales AS r
                              
                      WHERE	n.evolucion_id = ".$criterio_evolucion."
                      AND 	n.estado = '0'
                      AND 	n.usuario_id = q.usuario_id
                      AND 	q.tipo_id_tercero = p.tipo_id_tercero
                      AND 	q.tercero_id = p.tercero_id
                      AND 	q.tipo_profesional = r.tipo_profesional";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($qdatosP);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;          

          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          
          $datosProfesional = $result->FetchRow();
          
          $vectorGeneral = array();
          array_push($vectorGeneral, $datosPaciente);
          array_push($vectorGeneral, $vectorOriginal);
          array_push($vectorGeneral, $datosProfesional);
          
          if($_REQUEST['impresion_pos']=='1')
          {
               $classReport = new reports;
               $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
               $reporte=$classReport->PrintReport($tipo_reporte='pos',$tipo_modulo='app',$modulo='ImpresionHC',$reporte_name='formulamedica',$vectorGeneral,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
               if(!$reporte)
               {
                    $this->error = $classReport->GetError();
                    $this->mensajeDeError = $classReport->MensajeDeError();
                    unset($classReport);
                    return false;
               }

               $resultado=$classReport->GetExecResultado();
               unset($classReport);

               if(!empty($resultado[codigo]))
               {
                    "El PrintReport retorno : " . $resultado[codigo] . "<br>";
               }
               if ($_REQUEST['parametro_retorno'] == '1')
               {
                    if ($_REQUEST['modulo_invoca'] == 'impresionhc')
                    {
                         $this->ReturnMetodoExterno('app','ImpresionHC','user','FormaImpresionSolicitudes');
                    }
               }
          }
          else
          {
               if ($_REQUEST['parametro_retorno'] == '1')
               {
                    IncludeLib("reportes/formula_hospitalaria_soluciones_y_medicamentos");
                    GenerarFormula($datosPaciente, $vectorOriginal, $datosProfesional);
                    
                    if(is_array($datosPaciente) AND is_array($vectorOriginal))
                    {
                         $RUTA = $_ROOT ."cache/formula_medica_hos.pdf";
                         $DIR="printer.php?ruta=$RUTA";
                         $RUTA1= GetBaseURL() . $DIR;
                         $mostrar ="\n<script language='javascript'>\n";
                         $mostrar.="var rem=\"\";\n";
                         $mostrar.="  function abreVentana(){\n";
                         $mostrar.="    var url2=\"\"\n";
                         $mostrar.="    var width=\"400\"\n";
                         $mostrar.="    var height=\"300\"\n";
                         $mostrar.="    var winX=Math.round(screen.width/2)-(width/2);\n";
                         $mostrar.="    var winY=Math.round(screen.height/2)-(height/2);\n";
                         $mostrar.="    var nombre=\"Printer_Mananger\";\n";
                         $mostrar.="    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",resizable=no,status=no,scrollbars=yes,location=no\";\n";
                         $mostrar.="    var url2 ='$RUTA1';\n";
                         $mostrar.="    rem = window.open(url2, nombre, str)};\n";
                         $mostrar.="</script>\n";
                         $this->salida.="$mostrar";
                         $this->salida.="<BODY onload=abreVentana();>";
                    }
                    if ($_REQUEST['modulo_invoca'] == 'impresionhc')
                    {
                         $this->ReturnMetodoExterno('app','ImpresionHC','user','FormaImpresionSolicitudes');
                    }
               }
          }
          return true;
     }
     
    function Get_Info_NotasOperatorias($ingreso)
    {
      list($dbconn) = GetDBconn();
      $query = "SELECT  A.hc_nota_operatoria_cirugia_id, 
                        A.evolucion_id, 
                        A.programacion_id
                FROM    hc_notas_operatorias_cirugias AS A,
                        hc_evoluciones AS B
                WHERE   B.ingreso = ".$ingreso."
                AND     A.evolucion_id = B.evolucion_id
                AND     A.sw_estado = '1'
                ORDER BY A.hc_nota_operatoria_cirugia_id DESC;";
      $resulta=$dbconn->execute($query);
      if ($dbconn->ErrorNo() != 0) 
      {
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
    /**
    *
    */
    function ObtenerLecturaApoyos($evolucion,$paciente)
    {
      $cxn = AutoCarga::factory("ConexionBD");
      $cxn->debug = true;
      
      $ingreso = array();
      
      $sql  = "SELECT ingreso ";
      $sql .= "FROM   hc_evoluciones ";
      $sql .= "WHERE  evolucion_id = ".$evolucion." ";
      
      if(!$rst = $cxn->ConexionBaseDatos($sql))
				return false;
      
      while(!$rst->EOF)
			{
				$ingreso = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
      
      $sql  = "SELECT B.evolucion_solicitud_ex ";
      $sql .= "FROM   ( ";
      $sql .= "         ( ";
      $sql .= "           SELECT  A.*,";
      $sql .= "                   H.sw_modo_resultado,";
      $sql .= "                   H.fecha_realizado,";
      $sql .= "                   H.resultado_id ,";
      $sql .= "                   CASE WHEN F.resultado_id IS NULL THEN '0'";
      $sql .= "                        ELSE F.resultado_id END AS resultados_sistema,";
      $sql .= "                   CASE WHEN G.resultado_id IS NULL THEN '0'";
      $sql .= "                        ELSE G.resultado_id END AS resultado_manual";
      $sql .= "           FROM    (";
      $sql .= "                     SELECT	B.usuario_id,";
      $sql .= "                             B.departamento,";
      $sql .= "                             B.fecha,";
      $sql .= "                             A.hc_os_solicitud_id,";
      $sql .= "                             A.cargo,";
      $sql .= "                             A.os_tipo_solicitud_id,";
      $sql .= "                             CASE WHEN C.sw_estado IS NULL THEN '0' ";
      $sql .= "                                  ELSE C.sw_estado END AS realizacion,";
      $sql .= "                             C.numero_orden_id,";
      $sql .= "                             A.evolucion_id AS evolucion_solicitud_ex ";
      $sql .= "                     FROM	  hc_os_solicitudes A LEFT JOIN ";
      $sql .= "                             hc_apoyod_lectura_grupal_detalle D ";
      $sql .= "                             ON( A.evolucion_id = D.evolucion_id_solicitud ), ";
      $sql .= "                             hc_evoluciones B,";
      $sql .= "                             os_maestro C";
      $sql .= "                     WHERE	  A.tipo_id_paciente = '".$paciente['tipo_id_paciente']."'";
      $sql .= "                     AND     A.paciente_id = '".$paciente['paciente_id']."'";
      //$sql .= "                     AND     A.evolucion_id = ".$evolucion."";
      $sql .= "                     AND     B.evolucion_id = A.evolucion_id";
      $sql .= "                     AND     B.ingreso = ".$ingreso['ingreso']." ";
      $sql .= "                     AND     A.hc_os_solicitud_id = C.hc_os_solicitud_id";
      $sql .= "                   ) A";
      $sql .= "                   LEFT JOIN hc_resultados_sistema F ";
      $sql .= "                   ON(A.numero_orden_id = F.numero_orden_id)";
      $sql .= "                   LEFT JOIN hc_resultados_manuales G ";
      $sql .= "                   ON(A.numero_orden_id = G.numero_orden_id)";
      $sql .= "                   LEFT JOIN hc_resultados H ";
      $sql .= "                   ON ( G.resultado_id = H.resultado_id)";
      $sql .= "           WHERE H.fecha_realizado IS NOT NULL";
      $sql .= "         )";
      $sql .= "         UNION ALL ";
      $sql .= "         (";
      $sql .= "           SELECT  A.*,";
      $sql .= "                   H.sw_modo_resultado,";
      $sql .= "                   H.fecha_realizado,";
      $sql .= "                   H.resultado_id ,";
      $sql .= "                   CASE WHEN F.resultado_id IS NULL THEN '0' ";
      $sql .= "                        ELSE F.resultado_id END AS resultados_sistema, ";
      $sql .= "                   CASE WHEN G.resultado_id IS NULL THEN '0' ";
      $sql .= "                        ELSE G.resultado_id END AS resultado_manual";
      $sql .= "           FROM  (";
      $sql .= "                   SELECT	B.usuario_id,";
      $sql .= "                           B.departamento, ";
      $sql .= "                           B.fecha, ";
      $sql .= "                           A.hc_os_solicitud_id, ";
      $sql .= "                           A.cargo, ";
      $sql .= "                           A.os_tipo_solicitud_id,";
      $sql .= "                           CASE WHEN C.sw_estado IS NULL THEN '0'";
      $sql .= "                                ELSE C.sw_estado END AS realizacion,";
      $sql .= "                           C.numero_orden_id,";
      $sql .= "                           A.evolucion_id AS evolucion_solicitud_ex ";
      $sql .= "                   FROM	  hc_os_solicitudes A LEFT JOIN ";
      $sql .= "                           hc_apoyod_lectura_grupal_detalle D ";
      $sql .= "                           ON( A.evolucion_id = D.evolucion_id_solicitud ), ";
      $sql .= "                           hc_evoluciones B,";
      $sql .= "                           os_maestro C";
      $sql .= "                   WHERE	  A.tipo_id_paciente = '".$paciente['tipo_id_paciente']."'";
      $sql .= "                   AND     A.paciente_id = '".$paciente['paciente_id']."'";
      //$sql .= "                   AND     A.evolucion_id = ".$evolucion." ";
      $sql .= "                   AND     B.evolucion_id = A.evolucion_id";
      $sql .= "                   AND     B.ingreso = ".$ingreso['ingreso']." ";
      $sql .= "                   AND     A.hc_os_solicitud_id = C.hc_os_solicitud_id ";
      $sql .= "                 ) A";
      $sql .= "                 LEFT JOIN hc_resultados_sistema AS F ";
      $sql .= "                 ON(A.numero_orden_id = F.numero_orden_id)";
      $sql .= "                 LEFT JOIN hc_resultados_manuales AS G ";
      $sql .= "                 ON(A.numero_orden_id = G.numero_orden_id)";
      $sql .= "                 LEFT JOIN hc_resultados as H";
      $sql .= "                 ON (F.resultado_id = H.resultado_id)";
      $sql .= "           WHERE H.fecha_realizado IS NOT NULL";
      $sql .= "         )";
      $sql .= "       ) B";
      $sql .= "       LEFT JOIN hc_apoyod_resultados_detalles C ";
      $sql .= "       ON (B.resultado_id = C.resultado_id AND C.cargo = B.cargo)";
      $sql .= "       LEFT JOIN hc_apoyod_lecturas_profesionales D ";
      $sql .= "       ON (D.resultado_id = C.resultado_id),";
      $sql .= "       apoyod_cargos E,";
      $sql .= "       cups F,";
      $sql .= "       apoyod_cargos_tecnicas G,";
      $sql .= "       lab_examenes H,";
      $sql .= "       hc_os_autorizaciones M ";
      $sql .= "WHERE  B.cargo = E.cargo ";
      $sql .= "AND    E.cargo = F.cargo ";
      $sql .= "AND    G.cargo = B.cargo ";
      $sql .= "AND    C.tecnica_id = G.tecnica_id ";
      $sql .= "AND    H.cargo = C.cargo ";
      $sql .= "AND    H.tecnica_id = C.tecnica_id ";
      $sql .= "AND    H.lab_examen_id = C.lab_examen_id ";
      $sql .= "AND    B.hc_os_solicitud_id = M.hc_os_solicitud_id ";
      
      $cxn = AutoCarga::factory("ConexionBD");
      
      if(!$rst = $cxn->ConexionBaseDatos($sql))
				return false;
      
      $datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
      return $datos;
    }
    /**
    *
    */
    function ConsultaResultadosNoSolicitados($paciente)
    {
   		$sql  = "SELECT d.evolucion_id ";
      $sql .= "FROM   hc_resultados_nosolicitados a ";
      $sql .= "	      LEFT JOIN hc_resultados b  ";
      $sql .= "       ON (a.resultado_id = b.resultado_id) ";
      $sql .= "  		  LEFT JOIN apoyod_cargos c ";
      $sql .= "       ON (b.cargo = c.cargo)  ";
      $sql .= "       LEFT JOIN hc_apoyod_lecturas_profesionales d ";
      $sql .= "       ON (b.resultado_id = d.resultado_id) ";
      $sql .= "  		  LEFT JOIN hc_evoluciones e ";
      $sql .= "       ON (d.evolucion_id = e.evolucion_id) ";
      $sql .= "WHERE	b.tipo_id_paciente = '".$paciente['tipo_id_paciente']."'";
      $sql .= "AND    B.paciente_id = '".$paciente['paciente_id']."' ";

      $cxn = AutoCarga::factory("ConexionBD");
      
      if(!$rst = $cxn->ConexionBaseDatos($sql))
				return false;
      
      $datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
      return $datos;
    }
  }//end of class
?>