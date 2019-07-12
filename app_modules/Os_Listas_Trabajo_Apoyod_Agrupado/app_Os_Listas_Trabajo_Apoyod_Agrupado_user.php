<?php

/**
 * $Id: app_Os_Listas_Trabajo_Apoyod_Agrupado_user.php,v 1.3 2009/12/07 13:57:42 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo para el manejo de listas de trabajo de Apoyos diagnosticos- Meter plantilla
 * Modulo de Listas de Trabajo para Apoyos Diagnosticos (PHP).
 */

class app_Os_Listas_Trabajo_Apoyod_Agrupado_user extends classModulo
{
    var $limit;
    var $conteo;//para saber cuantos registros encontró


    function app_Os_Listas_Trabajo_Apoyod_Agrupado_user()
    {
            $this->limit=GetLimitBrowser();
            return true;
    }

    function main()
    {
            if(!$this->BuscarPermisosUser())
            {
                    return false;
            }
      SessionSetVar("filtrarUsuario",false);
            return true;
    }

    function main_pro()
    {
      if(!$this->BuscarPermisosUserPro())
      {
        return false;
      }
      SessionSetVar("filtrarUsuario",true);
      return true;
    }


    /**
    * La funcion BuscarPermisosUser recibe todas las variables de manejo y verifica si el
    * usuario posee los permisos para acceder al modulo del laboratorio.
    * Nota: las variables pueden llegar por REQUEST o por Parametros.
    * @access private
    * @return boolean
    */
    function BuscarPermisosUser()
    {
            unset ($_SESSION['BUSQUEDA']['filtroOpcionExamenes']);
            list($dbconn) = GetDBconn();
            GLOBAL $ADODB_FETCH_MODE;
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $query="SELECT a.tipo_presentacion, b.tipo_os_lista_id, f.nombre_lista,
                            f.tipo_os_lista_id, c.departamento,    c.descripcion as dpto,
                            d.descripcion as centro, e.empresa_id,    e.razon_social as emp,
                            d.centro_utilidad, a.usuario_id, c.sw_maneja_vitros
                            FROM userpermisos_os_listas_trabajo_apoyod a,
                            userpermisos_os_listas_trabajo_apoyod_detalle b,
                            departamentos c, centros_utilidad d,empresas e, tipos_os_listas_trabajo f
                            WHERE a.usuario_id = b.usuario_id AND a.departamento = b.departamento
                            AND f.tipo_os_lista_id = b.tipo_os_lista_id AND b.departamento = f.departamento
                            AND a.usuario_id=".UserGetUID()." AND c.departamento=a.departamento
                            AND d.centro_utilidad=c.centro_utilidad AND e.empresa_id=d.empresa_id
                            AND e.empresa_id=c.empresa_id ORDER BY centro,b.tipo_os_lista_id";
            $resulta = $dbconn->Execute($query);
            while($data = $resulta->FetchRow())
            {
                    $laboratorio[$data['emp']][$data['centro']][$data['dpto']]=$data;
            }
            $resulta = $dbconn->Execute($query);
            while(!$resulta->EOF)
            {
                    $var[]=$resulta->GetRowAssoc($ToUpper = false);
                    $resulta->MoveNext();
            }
            $_SESSION['LTRABAJOAPOYOD']['LISTAS']=$var;
            $url[0]='app';
            $url[1]='Os_Listas_Trabajo_Apoyod_Agrupado';
            $url[2]='user';
            $url[3]='Menuatencion';
            $url[4]='Listas_Trabajo_Apoyod';
            $arreglo[0]='EMPRESA';
            $arreglo[1]='CENTRO UTILIDAD';
            $arreglo[2]='ATENCION DE LISTA DE TRABAJO';
            $this->salida.= gui_theme_menu_acceso('ATENCION DE LISTA DE TRABAJO',$arreglo,$laboratorio,$url);
            return true;
    }


    /**
    * Menuatencion, esta funcion  es donde desemboca el menu iteractivo creado por arley.
    * @access public
    * @return boolean
    */
    function Menuatencion()
    {
            $_SESSION['LTRABAJOAPOYOD']['EMPRESA_ID']=$_REQUEST['Listas_Trabajo_Apoyod']['empresa_id'];
            $_SESSION['LTRABAJOAPOYOD']['CENTROUTILIDAD']=$_REQUEST['Listas_Trabajo_Apoyod']['centro_utilidad'];
            $_SESSION['LTRABAJOAPOYOD']['NOM_CENTRO']=$_REQUEST['Listas_Trabajo_Apoyod']['centro'];
            $_SESSION['LTRABAJOAPOYOD']['NOM_EMP']=$_REQUEST['Listas_Trabajo_Apoyod']['emp'];
            $_SESSION['LTRABAJOAPOYOD']['NOM_DPTO']=$_REQUEST['Listas_Trabajo_Apoyod']['dpto'];
            $_SESSION['LTRABAJOAPOYOD']['DPTO']=$_REQUEST['Listas_Trabajo_Apoyod']['departamento'];



            //para la nueva version se agrega este campo con el fin de saber
            //el modo de presentacion que va a manejar el usuario en las listas
            //si el tipo es 1 es el modo convencional uno a uno y si es 2 es el modo grilla
            $_SESSION['LTRABAJOAPOYOD']['TIPO_PRESENTACION']=$_REQUEST['Listas_Trabajo_Apoyod']['tipo_presentacion'];
            $res=$this->ConsultaSwVitros($_SESSION['LTRABAJOAPOYOD']['DPTO']);
            $_SESSION['LTRABAJOAPOYOD']['SW_VITROS']=$res[0];
            //MAuroB
//          $_REQUEST['TipoDocumento']='';
//          $_REQUEST['Documento']='';
//          $_REQUEST['Nombres']='';
//          $_REQUEST['Numero_Orden']='';
//          $_REQUEST['Historia_Prefijo']='';
//          $_REQUEST['Historia_Numero']='';
//          $_REQUEST['Fecha']=date('Y-m-d');
//          $_REQUEST['opcion_examenes']='2';
/*          $_SESSION['BUSQUEDA']['filtroOpcionExamenes']='2';
            $_SESSION['BUSQUEDA']['filtroFecha']=date('Y-m-d');*/
            //fin MAuroB
            if(!$this->FormaMetodoBuscar())
            //if(!$this->BuscarOrden())
            {
                    return false;
            }
            return true;
    }


    /**
    *
    */
    function ConsultaSwVitros($depto){
        $query="SELECT  sw_maneja_vitros
                        FROM            departamentos
                        WHERE       departamento = '".$depto."'";
        list($dbconn) = GetDBconn();
        $result = $dbconn->Execute($query);
        return $result->FetchRow();

    }
    /**
    * La funcion BuscarPermisosUser recibe todas las variables de manejo y verifica si el
    * usuario posee los permisos para acceder al modulo del laboratorio.
    * Nota: las variables pueden llegar por REQUEST o por Parametros.
    * @access private
    * @return boolean
    */
    function BuscarPermisosUserPro()
    {
            unset ($_SESSION['BUSQUEDA']['filtroOpcionExamenes']);
            list($dbconn) = GetDBconn();
            GLOBAL $ADODB_FETCH_MODE;
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $query="SELECT a.tipo_presentacion, b.tipo_os_lista_id, f.nombre_lista,
                            f.tipo_os_lista_id, c.departamento, c.descripcion as dpto,
                            d.descripcion as centro, e.empresa_id, e.razon_social as emp,
                            d.centro_utilidad, a.usuario_id
                            FROM user_permisos_os_listatra_apoyod_profesionales a,
                            user_permisos_os_listatra_apoyod_detalle_profesionales b, departamentos c,
                            centros_utilidad d,empresas e, tipos_os_listas_trabajo f
                            WHERE a.usuario_id = b.usuario_id AND a.departamento = b.departamento
                            AND    a.tipo_id_tercero = b.tipo_id_tercero AND a.tercero_id = b.tercero_id
                            AND f.tipo_os_lista_id = b.tipo_os_lista_id AND b.departamento = f.departamento
                            AND a.usuario_id = '".UserGetUID()."' AND c.departamento=a.departamento
                            AND d.centro_utilidad=c.centro_utilidad AND e.empresa_id=d.empresa_id
                            AND e.empresa_id=c.empresa_id ORDER BY centro,b.tipo_os_lista_id";
            $resulta = $dbconn->Execute($query);
            while($data = $resulta->FetchRow())
            {
                    $laboratorio[$data['emp']][$data['centro']][$data['dpto']]=$data;
            }

            $resulta = $dbconn->Execute($query);
            while(!$resulta->EOF)
            {
                    $var[]=$resulta->GetRowAssoc($ToUpper = false);
                    $resulta->MoveNext();
            }
            $_SESSION['LTRABAJOAPOYOD']['LISTAS']=$var;
            $url[0]='app';
            $url[1]='Os_Listas_Trabajo_Apoyod_Agrupado';
            $url[2]='user';
            $url[3]='Menuatencion';
            $url[4]='Listas_Trabajo_Apoyod';
            $arreglo[0]='EMPRESA';
            $arreglo[1]='CENTRO UTILIDAD';
            $arreglo[2]='ATENCION DE LISTA DE TRABAJO';

            $this->salida.= gui_theme_menu_acceso('ATENCION DE LISTA DE TRABAJO',$arreglo,$laboratorio,$url);
            $_SESSION['LTRABAJOAPOYOD']['PROFESIONAL']='es profesional';
            return true;
    }


    function GetForma()
    {
			//inicio lorena
			unset ($_SESSION['LISTA']['APOYO']['responsable']);
			if($_REQUEST['responsable']!=-1)
			{
				$_SESSION['LISTA']['APOYO']['responsable']= $_REQUEST['responsable'];
			}
			//fin lorena
			
			if($_REQUEST['accion']=='crear_forma_examen')//OK
			{
				$_SESSION['LISTA']['APOYO']['tecnica_id']=$_REQUEST['selector_multitecnica'];
				$this->frmCrearFormaE();
			}

			if($_REQUEST['accion']=='insertar')//OK
			{
				if ($this->Insertar()==false)
				{
					$this->frmCrearFormaE();
				}
				else
				{
					if (!empty($_REQUEST['firma']))
					{
						$this->LlenadoConfirmarFirmaResultado();
					}
					else
					{
						$this->BuscarOrden();
					}
				}
			}

			if($_REQUEST['accion']=='modificacion_resultados')//ok
			{
				if ($_REQUEST['consultando']=='1')
				{
					$_SESSION['CONSULTANDO_APD']='1';
				}
				$this->frmModificacion_Resultados($_REQUEST['resultado_id'], $_REQUEST['evolucion_id'],  $_REQUEST['hc_os_solicitud_id'], $_REQUEST['usuario_profesional'], $_REQUEST['nombre'],$_REQUEST['numero_cumplimiento'],$_REQUEST['fecha_cumplimiento'], $_REQUEST['usuario_profesional_autoriza']);
			}

			if($_REQUEST['accion']=='modificar')//ok
			{
				//verifica si no es un profesional o si no esta firmando, lo que indica
				//que solo modificara pero no cerrarra el examen.
				if ((empty($_SESSION['LTRABAJOAPOYOD']['PROFESIONAL'])) OR (empty($_REQUEST['firma'])))
				{
					unset ($_SESSION['LTA']);
					if ($this->ActualizarDatosResultado($_REQUEST['resultado_id'], $_REQUEST['cargo'], $_REQUEST['tecnica_id'])==false)
					{//echo "Entro modificar LTA";
						$this->frmModificacion_Resultados();
					}
					else
					{
						$this->BuscarOrden();
					}
				}
				else
				{
					$this->ConfirmarFirmaResultado($_REQUEST['resultado_id'], $_REQUEST['cargo'], $_REQUEST['tecnica_id']);
				}
			}

			if($_REQUEST['accion']=='insertar_observacion_adicional')//OK
			{
				if ($this->Insertar_Observacion_Adicional($_REQUEST['resultado_id']) == true)
				{
					$_REQUEST['observacion_adicional']='';
					$this->frmModificacion_Resultados();
				}
				else
				{
					$this->frmModificacion_Resultados();
				}
			}

			//acciones de la nueva version
			if($_REQUEST['accion']=='capturar_resultados')//ok
			{
				$this->Capturar_Resultados($_REQUEST['tipo_id_paciente'], $_REQUEST['paciente_id'],$_REQUEST['nombre'],
				$_REQUEST['servicio'], $_REQUEST['numero_cumplimiento'],$_REQUEST['fecha_cumplimiento']);
			}

			if($_REQUEST['accion']=='insertar_resultado')
			{
				if($_REQUEST['posicion'] OR $_REQUEST['posicion'] == '0')
				{
					if($_REQUEST['opcion'] == 'capturar_observacion')//ok
					{//print_r($_REQUEST);echo "<br>--------------------<br>";
						$this->Constructor_Session_Apoyo_Mto($_REQUEST['tipo_id_paciente'], $_REQUEST['paciente_id']);
						$this->frmForma_Observacion_Prestador_Servicio($_REQUEST['tipo_id_paciente'], $_REQUEST['paciente_id'],$_REQUEST['nombre'],
						$_REQUEST['servicio'], $_REQUEST['numero_cumplimiento'],$_REQUEST['fecha_cumplimiento'], $_REQUEST['posicion']);
					}
					elseif($_REQUEST['opcion'] == 'cambio_tecnica')//ok
					{
						$this->Capturar_Resultados($_REQUEST['tipo_id_paciente'], $_REQUEST['paciente_id'],$_REQUEST['nombre'],   $_REQUEST['servicio'], $_REQUEST['numero_cumplimiento'],$_REQUEST['fecha_cumplimiento']);
					}
				}
				else //ok
				{
					if ($this->Insertar_Resultado($_REQUEST['tipo_id_paciente'], $_REQUEST['paciente_id'],$_REQUEST['nombre'],$_REQUEST['servicio'], $_REQUEST['numero_cumplimiento'],$_REQUEST['fecha_cumplimiento'])==false)
					{
						$this->Capturar_Resultados($_REQUEST['tipo_id_paciente'], $_REQUEST['paciente_id'],$_REQUEST['nombre'], $_REQUEST['servicio'], $_REQUEST['numero_cumplimiento'],$_REQUEST['fecha_cumplimiento']);
					}
					else
					{
						if (!empty($_SESSION['FIRMA']['apoyos']))
						{
							$this->LlenadoConfirmarFirmaResultado();
						}
						else
						{
							$this->BuscarOrden();
						}
					}
				}
			}

			if($_REQUEST['accion']=='insertar_observacion_prestador_servicio')//ok
			{
				$_SESSION['APOYO'][$_REQUEST['tipo_id_paciente']][$_REQUEST['paciente_id']][$_REQUEST['indice']]['observacion'] = $_REQUEST['observacion'];
				$this->Capturar_Resultados($_REQUEST['tipo_id_paciente'], $_REQUEST['paciente_id'],$_REQUEST['nombre'],
				$_REQUEST['servicio'], $_REQUEST['numero_cumplimiento'],$_REQUEST['fecha_cumplimiento']);
			}
			
			return $this->salida;
    }


  //nueva version de esta funcion ok mayo/03/2005
    function Plantillas_Examenes()
    {
            //cargando datos a la variable de session $_SESSION['LISTA']['APOYO']
            if ($_REQUEST['retorno'] != '1')
            {
                $_SESSION['LISTA']['APOYO']['tipo_id_paciente']=$_REQUEST['tipo_id_paciente'];
                $_SESSION['LISTA']['APOYO']['paciente_id']=$_REQUEST['paciente_id'];
                $_SESSION['LISTA']['APOYO']['nombre']=$_REQUEST['nombre'];
                $_SESSION['LISTA']['APOYO']['cargo']=$_REQUEST['cargo'];
                $_SESSION['LISTA']['APOYO']['evolucion_id']=$_REQUEST['evolucion_id'];
                $_SESSION['LISTA']['APOYO']['numero_orden_id']=$_REQUEST['numero_orden_id'];
                $_SESSION['LISTA']['APOYO']['hc_os_solicitud_id']=$_REQUEST['hc_os_solicitud_id'];
                $informacion_examen = $this->GetInfoExamen($_SESSION['LISTA']['APOYO']['cargo']);
                if (!empty($informacion_examen))
                {
                        $_SESSION['LISTA']['APOYO']['titulo']= $informacion_examen['titulo'];
                        $_SESSION['LISTA']['APOYO']['informacion']= $informacion_examen['informacion'];
                }
                else
                {
                        $_SESSION['LISTA']['APOYO']['titulo']=$_REQUEST['titulo'];
                        $_SESSION['LISTA']['APOYO']['informacion']= '';
                }
            }

            $multitecnica= $this->Consultar_Tecnicas_Examen('','','',$_SESSION['LISTA']['APOYO']['cargo']);

        if (sizeof($multitecnica)>1)
            {
                    $this->frmSeleccion_Tecnica($multitecnica);
                    return true;
            }
            else
            {
                    $_SESSION['LISTA']['APOYO']['tecnica_id']=$multitecnica[0][tecnica_id];
                    $this->frmCrearFormaE();
                    return true;
            }
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
            if ($dbconn->ErrorNo() != 0)
            {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            else
            {
                    if($result->EOF)
                    {
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "La tabla maestra 'tipos_id_pacientes' esta vacia ";
                            return false;
                    }
                    while (!$result->EOF)
                    {
                            $vars[$result->fields[0]]=$result->fields[1];
                            $result->MoveNext();
                    }
            }
            $result->Close();
            return $vars;
    }


    /**
    * Realiza la busqueda según el nuemro de la orde, el tipo de docuemnto,
    * el documento .de los pacientes que tienen examenes para ingresar resultados.
    * @access private
    * @return boolean
    */
    function BuscarOrden()
    {
        //usuario_id_profesional_autoriza se refiere al que firma.
        //usuario_id_profesional es el que diagnostica.
        unset($_SESSION['APOYO']);
        unset($_SESSION['LISTA']['APOYO']);
        list($dbconn) = GetDBconn();
        //$dbconn->debug = true;
        if ($_REQUEST['Buscar_Cargar_Session'] != '')
        {
            if (empty($_REQUEST['op']))
            {
                $this->frmError["MensajeError"]="DEBE SELECCIONAR UN TIPO DE LISTA PARA LA PROGRAMACION";
                $this->FormaMetodoBuscar();
                return true;
            }
            
            $tipo_documento   = trim($_REQUEST['TipoDocumento']);
            $documento        = trim($_REQUEST['Documento']);
            $nombres          = strtoupper(trim($_REQUEST['Nombres']));
            $apellidos        = strtoupper(trim($_REQUEST['Apellidos']));
            $numero_orden     = trim($_REQUEST['Numero_Orden']);
            $historia_prefijo = trim($_REQUEST['Historia_Prefijo']);
            $historia_numero  = trim($_REQUEST['Historia_Numero']);
            $fecha1           = explode("-",trim($_REQUEST['Fecha']));
            $fecha            = $fecha1[2]."-".$fecha1[1]."-".$fecha1[0];
            $cumplimiento     = trim($_REQUEST['Cumplimiento']);
            $opcion_examenes  = $_REQUEST['opcion_examenes'];

            if (!empty($fecha))
            {
							$fecha = str_replace("/", "-", $fecha);
							$FechaExplodeTime  = explode(" ",$fecha);
							$fecha = $FechaExplodeTime[0];

							$F1 = explode("-",$fecha);

							if(!checkdate($F1[1],$F1[2],$F1[0]))
							{
								$fecha = '';
							}
							else
							{
								$fecha = "$F1[0]-$F1[1]-$F1[2]";
							}
            }

            $Mfecha = $fecha;

            //bloque de variables para almacenar las sentencias sql.
            $filtroTipoDocumento    = '';
            $filtroDocumento        = '';
            $filtroNombres          = '';
            $filtroApellidos        = '';
            $filtroNumeroOrden      = '';
            $filtroHistoria_Prefijo = '';
            $filtroHistoria_Numero  = '';
            $filtroCumplimiento     = '';
            $filtroFecha            = '';
            $filtroExamenes         = '';
            $filtroPrincipalTipo1   = ''; // Para Busqueda con el No. de Cumplimiento, No. de la OS o No. de Id
            $filtroPrincipalTipo2   = ''; // Para Busqueda con los nombre y/o apellidos del paciente.

            //bloque de variables para almacenar el dato y no la sentencia sql.
            $filtroOpcionExamenes = $opcion_examenes;

            //SI LLEGA EL DOCUMENTO FILTRA POR EL DE LO CONTRARIO VERIFICA SI LLEGA NOMBRE O PALLIDOS
            
            if($cumplimiento != '')
            {
                $cont = substr_count($cumplimiento, "-");

                if(($cont==1)||($cont==2))
                {
                    $cumplimiento = explode("-",$cumplimiento);
                    $fecha_cumplimiento  = $cumplimiento[0];
                    $numero_cumplimiento = $cumplimiento[1];
                    $F1 = strtotime ("$fecha_cumplimiento");
                    if ($F1 === -1)
                    {
                        if(!empty($fecha))
                        {
                            $fecha_cumplimiento = $fecha;
                        }
                        else
                        {
                            $fecha_cumplimiento = date("Y-m-d");
                        }
                    }
                    else
                    {
                        $fecha_cumplimiento = date("Y-m-d", $F1);
                    }
                }
                else
                {
                    $numero_cumplimiento = $_REQUEST['Cumplimiento'];
                    if(!empty($fecha))
                    {
                        $fecha_cumplimiento = $fecha;
                    }
                    else
                    {
                        $fecha_cumplimiento = date("Y-m-d");
                    }
                }

                $Mfecha = $fecha_cumplimiento;


                $filtroPrincipalTipo1  = " a.numero_cumplimiento = $cumplimiento";
                $filtroPrincipalTipo1 .= " AND a.fecha_cumplimiento = '$fecha_cumplimiento'";
                $filtroPrincipalTipo1 .= " AND a.departamento = '".$_SESSION['LTRABAJOAPOYOD']['DPTO']."'";
            }
            elseif($numero_orden != '') //SI LLEGA EL NUMERO DE ORDEN FILTRA POR EL
            {
                $filtroPrincipalTipo1  = " a.departamento = '".$_SESSION['LTRABAJOAPOYOD']['DPTO']."'";
                $filtroPrincipalTipo1 .= " AND b.numero_orden_id = $numero_orden";
            }
            elseif($documento != '') //SI LLEGA EL NO. DE ID FILTRA POR EL TIENE EN CUENTA LA FECHA Y EL TIPO DE ID
            {

                $filtroPrincipalTipo1  = " a.departamento = '".$_SESSION['LTRABAJOAPOYOD']['DPTO']."'";
                $filtroPrincipalTipo1 .= " AND a.paciente_id = '$documento'";

                if((!empty($tipo_documento)) AND ($tipo_documento != -1))
                {
                    $filtroPrincipalTipo1 .= " AND a.tipo_id_paciente = '$tipo_documento'";
                }

                if(!empty($fecha))
                {
                    $filtroPrincipalTipo1 .= " AND a.fecha_cumplimiento = '$fecha'";
                }

            }
            elseif(!empty($historia_numero))//SI LLEGA EL NUMERO DE HC
            {
                $filtroPrincipalTipo1  = " a.departamento = '".$_SESSION['LTRABAJOAPOYOD']['DPTO']."' ";

                if((!empty($historia_prefijo)))
                {
                    $filtroPrefijoHC = " AND historia_prefijo = '$historia_prefijo' ";
                }

                $filtroPrincipalTipo1 .= " AND a.paciente_id IN (SELECT paciente_id FROM historias_clinicas WHERE historia_numero = '$documento' $filtroPrefijoHC)";
                $filtroPrincipalTipo1 .= " AND a.tipo_id_paciente IN (SELECT tipo_id_paciente FROM historias_clinicas WHERE historia_numero = '$documento' $filtroPrefijoHC)";

                if(!empty($fecha))
                {
                    $filtroPrincipalTipo1 .= " AND a.fecha_cumplimiento = '$fecha' ";
                }

            }// SI NO LLEGO UN TIPO DE LLAVE PRINCIPAL Y LLEGAN DATOS DEL NOMBRE O DE LOS APLLIDOS GENERA UNA BUSQUEDA POR ELLOS.
            elseif($nombres != '' OR $apellidos != '')
            {
                if (!empty($nombres))
				{
					$Nsimilares = substr_count($nombres,"%");
					
					//TODO CON LIKE PORQUE EN LA BASE DE DATOS LOS NOMBRES SE ESTAN GUADANDO CON ESPACIOS.
					$Nsimilares = 1;
					$nombres = str_replace ("%", " ", $nombres);

					$nombres = explode(" ",preg_replace("/\s{2,}/"," ",trim($nombres)));

					if(count($nombres)>1)
					{
						if($Nsimilares)
						{
							$filtroNombres = "(primer_nombre LIKE '%$nombres[0]%' AND segundo_nombre LIKE '%$nombres[1]%')";
						}
						else
						{
							$filtroNombres = "(primer_nombre = '$nombres[0]' AND segundo_nombre = '$nombres[1]')";
						}
					}
					else
					{
						if(!empty($nombres[0]))
						{
							if($Nsimilares)
							{
								$filtroNombres = "(primer_nombre LIKE '%$nombres[0]%' OR segundo_nombre LIKE '%$nombres[0]%')";
							}
							else
							{
								$filtroNombres = "(primer_nombre = '$nombres[0]' OR segundo_nombre = '$nombres[0]')";
							}
						}
					}
				}
				if(!empty($apellidos))
				{
					$Asimilares = substr_count($apellidos,"%");
					//TODO CON LIKE PORQUE EN LA BASE DE DATOS LOS NOMBRES SE ESTAN GUADANDO CON ESPACIOS.
					$Asimilares = 1;
					$apellidos = str_replace ("%", " ", $apellidos);

					$apellidos = explode(" ",preg_replace("/\s{2,}/"," ",trim($apellidos)));
					if(count($apellidos)>1)
					{
						if($Asimilares)
						{
							$filtroApellidos = "(primer_apellido LIKE '%$apellidos[0]%' AND segundo_apellido LIKE '%$apellidos[1]%')";
						}
						else
						{
							$filtroApellidos = "(primer_apellido = '$apellidos[0]' AND segundo_apellido = '$apellidos[1]')";
						}
					}
					else
					{
						if(!empty($apellidos[0]))
						{
							if($Asimilares)
							{
								$filtroApellidos = "(primer_apellido LIKE '%$apellidos[0]%' OR segundo_apellido LIKE '%$apellidos[0]%')";
							}
							else
							{
								$filtroApellidos = "(primer_apellido = '$apellidos[0]' OR segundo_apellido = '$apellidos[0]')";
							}
						}
					}
				}
				
                if(!empty($filtroNombres))
                {
                    if(!empty($filtroApellidos))
                    {
                        $filtroPrincipalTipo2= $filtroNombres ." AND ".$filtroApellidos;
						//echo '<br> filtro que sale1: '.$filtroPrincipalTipo2;
                    }
                    else
                    {
                        $filtroPrincipalTipo2 = $filtroNombres;
						//echo '<br> filtro que sale2: '.$filtroPrincipalTipo2;
                    }
                }
                else
                {
                    if(!empty($filtroApellidos))
                    {
                        $filtroPrincipalTipo2 = $filtroApellidos;
						//echo '<br> filtro que sale con apellidos: '.$filtroPrincipalTipo2;
                    }
                    else
                    {
                        $this->frmError["MensajeError"]="NO SE PUDO REALIZAR EL FILTRADO POR LOS CAMPOS NOMBRES Y/O APELLIDOS DEL PACIENTE";
                        $this->FormaMetodoBuscar();
                        return true;
                    }
                }
			}
			
            else //SI NO HAY FILTROS PRINCIPALES FILTRO POR LOS DE FECHA O LOS DEL DIA.
            {
                if(empty($fecha))
                {
                  if($_REQUEST['Fecha'] == 'CUALQUIER FECHA')
                  {
                    $fecha = date("Y-m-d");
                    $filtroPrincipalTipo1 = " a.fecha_cumplimiento <= '$fecha'";  
                  }
                  else
                  {
                    $fecha = date("Y-m-d");
                    $filtroPrincipalTipo1 = " a.fecha_cumplimiento = '$fecha'";
                  }
                    
                }
                else
                {
                    $filtroPrincipalTipo1 = " a.fecha_cumplimiento = '$fecha'";
                }
                
                $filtroPrincipalTipo1 .= " AND a.departamento = '".$_SESSION['LTRABAJOAPOYOD']['DPTO']."'";
            }
            
            switch($opcion_examenes)
            {
                case '1'://Examenes  sin transcribir
                    $filtroExamenes = "AND b.resultado_id ISNULL";
                break;

                case '2'://examenes transcritos sin firmar
                    $filtroExamenes = "AND b.resultado_id IS NOT NULL AND b.usuario_id_profesional_autoriza ISNULL";
                break;

                case '3'://examenes transcritos firmados
                    $filtroExamenes = "AND b.resultado_id IS NOT NULL AND b.usuario_id_profesional_autoriza IS NOT NULL";
                break;

                default://todos los examenes
                    $filtroExamenes = '';
            }

            //LISTAS SELECCIONADAS.
            if (!empty($_REQUEST['op']))
            {
                $ListasSeleccionadas="";
                foreach($_REQUEST['op'] as $k=>$v)
                {
                    $ListasSeleccionadas.= "'$v' ";
                }
                $ListasSeleccionadas = trim($ListasSeleccionadas);
                $ListasSeleccionadas = str_replace(" ",",",$ListasSeleccionadas);
                $ListasSeleccionadas = "AND d.tipo_os_lista_id IN ($ListasSeleccionadas)";
            }

            if(empty($ListasSeleccionadas))
            {
                $this->frmError["MensajeError"]="DEBE SELECCIONAR UN TIPO DE LISTA PARA LA PROGRAMACION";
                $this->FormaMetodoBuscar($var);
                return true;
            }
            if(SessionGetVar("filtrarUsuario"))
              $filtroExamenes .= " AND b.usuario_id_profesional = ".UserGetUID()." ";
        }
        else
        {
            $filtroPrincipalTipo1 = $_SESSION['BUSQUEDA']['filtroPrincipalTipo1'];
            $filtroPrincipalTipo2 = $_SESSION['BUSQUEDA']['filtroPrincipalTipo2'];
            $filtroExamenes =       $_SESSION['BUSQUEDA']['filtroExamenes'];
            $ListasSeleccionadas =  $_SESSION['BUSQUEDA']['ListasSeleccionadas'];
            foreach ($_SESSION['BUSQUEDA']['POSTDATA'] as $k=>$v)
            {
                $_REQUEST[$k]=$v;
            }
						$opcion_examenes = $_REQUEST['opcion_examenes'];
						$filtroOpcionExamenes = $opcion_examenes;
//             $filtroTipoDocumento = $_SESSION['BUSQUEDA']['filtroTipoDocumento'];
//             $filtroDocumento = $_SESSION['BUSQUEDA']['filtroDocumento'];
//             $filtroNombres = $_SESSION['BUSQUEDA']['filtroNombres'];
//             $filtroNumeroOrden = $_SESSION['BUSQUEDA']['filtroNumeroOrden'];
//             $filtroHistoria_Prefijo = $_SESSION['BUSQUEDA']['filtroHistoria_Prefijo'];
//             $filtroHistoria_Numero = $_SESSION['BUSQUEDA']['filtroHistoria_Numero'];
//             $filtroCumplimiento = $_SESSION['BUSQUEDA']['filtroCumplimiento'];
//             $filtroFecha = $_SESSION['BUSQUEDA']['filtroFecha'];
//             $filtroExamenes = $_SESSION['BUSQUEDA']['filtroExamenes'];
//             $_REQUEST['op'] = $_SESSION['BUSQUEDA']['listas'];
//             $filtroOpcionExamenes = $_SESSION['BUSQUEDA']['filtroOpcionExamenes'];
        }

        if(empty($_REQUEST['conteo']))
        {

            if ($opcion_examenes == 1 and $_SESSION['LTRABAJOAPOYOD']['TIPO_PRESENTACION'] == '2')
            {
                $tipo_presentasion = '2';
            }
            else
            {
                $tipo_presentasion = '1';
            }

            if(!empty($filtroPrincipalTipo1))
            {
                $query = $this->GetQueryBuscarOrdenTipo1(&$filtroPrincipalTipo1, &$filtroExamenes, &$ListasSeleccionadas, $tipo_presentasion, $conteo=true);
				//echo '<br> QUERY principal 1 con cedula del paciente: <br>'.$query;
            }
            elseif(!empty($filtroPrincipalTipo2))
            {
                $query = $this->GetQueryBuscarOrdenTipo2(&$filtroPrincipalTipo2, &$filtroExamenes, &$ListasSeleccionadas, $tipo_presentasion, $conteo=true);
				//echo '<br>Query Principal 2 con nombres o apellidos: <br>'.$query;
            }
            else
            {
                $this->frmError["MensajeError"]="NO SE PUDO ESTABLECER EL FILTRO DE LA BUSQUEDA";
                $this->FormaMetodoBuscar();
                return true;
            }
            
            $resulta = $dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            list($this->conteo)=$resulta->fetchRow();
        }
        else
        {
            $this->conteo = $_REQUEST['conteo'];
        }

        unset($query);
				
        if ($opcion_examenes == 1 && $_SESSION['LTRABAJOAPOYOD']['TIPO_PRESENTACION'] == '2')
        {
            $tipo_presentasion = '2';
        }
        else
        {
            $tipo_presentasion = '1';
        }

        if(!empty($filtroPrincipalTipo1))
        {
            $query = $this->GetQueryBuscarOrdenTipo1(&$filtroPrincipalTipo1, &$filtroExamenes, &$ListasSeleccionadas, $tipo_presentasion, $conteo=false);
        }
        elseif(!empty($filtroPrincipalTipo2))
        {
            $query = $this->GetQueryBuscarOrdenTipo2(&$filtroPrincipalTipo2, &$filtroExamenes, &$ListasSeleccionadas, $tipo_presentasion, $conteo=false);
        }
        else
        {
            $this->frmError["MensajeError"]="NO SE PUDO ESTABLECER EL FILTRO DE LA BUSQUEDA";
            $this->FormaMetodoBuscar();
            return true;
        }

        if ($_REQUEST['Buscar_Cargar_Session'] != '')
        {
                unset ($_SESSION['BUSQUEDA']);

                $_SESSION['BUSQUEDA']['filtroPrincipalTipo1'] = $filtroPrincipalTipo1;
                $_SESSION['BUSQUEDA']['filtroPrincipalTipo2'] = $filtroPrincipalTipo2;
                $_SESSION['BUSQUEDA']['filtroExamenes'] = $filtroExamenes;
                $_SESSION['BUSQUEDA']['ListasSeleccionadas'] = $ListasSeleccionadas;
                $_SESSION['BUSQUEDA']['opcion_examenes'] = $opcion_examenes;
                $_SESSION['BUSQUEDA']['POSTDATA'] = $_POST;
                unset($_SESSION['BUSQUEDA']['POSTDATA']['Buscar_Cargar_Session']);
//                 $_SESSION['BUSQUEDA']['filtroTipoDocumento'] = $filtroTipoDocumento;
//                 $_SESSION['BUSQUEDA']['filtroDocumento'] = $filtroDocumento;
//                 $_SESSION['BUSQUEDA']['filtroNombres'] = $filtroNombres;
//                 $_SESSION['BUSQUEDA']['filtroNumeroOrden'] = $filtroNumeroOrden;
//                 $_SESSION['BUSQUEDA']['filtroHistoria_Prefijo'] = $filtroHistoria_Prefijo;
//                 $_SESSION['BUSQUEDA']['filtroHistoria_Numero'] = $filtroHistoria_Numero;
//                 $_SESSION['BUSQUEDA']['filtroCumplimiento'] = $filtroCumplimiento;
//                 $_SESSION['BUSQUEDA']['filtroFecha'] =  $filtroFecha;
//                 $_SESSION['BUSQUEDA']['filtroExamenes'] =  $filtroExamenes;
//                 $_SESSION['BUSQUEDA']['listas']=$_REQUEST['op'];
//                 $_SESSION['BUSQUEDA']['filtroOpcionExamenes'] = $filtroOpcionExamenes;
        }
		
        $resulta = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        if ($filtroOpcionExamenes == 1 and $_SESSION['LTRABAJOAPOYOD']['TIPO_PRESENTACION'] == '2')
        {
            while(!$resulta->EOF)
            {
                $var[$resulta->fields[0].'/'.$resulta->fields[1]][$resulta->fields[2]][$resulta->fields[3].'/'.$resulta->fields[4]]=$resulta->GetRowAssoc($ToUpper = false);
                $resulta->MoveNext();
            }
        }
        else
        {
            while(!$resulta->EOF)
            {
                $var[]=$resulta->GetRowAssoc($ToUpper = false);
                $resulta->MoveNext();
            }
        }

            if($this->conteo==='0')
            {
                $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO PARA $Mfecha";
                $this->FormaMetodoBuscar($var);
                return true;
            }

            $this->FormaMetodoBuscar($var);
            return true;

    }//fin del metodo
    /**
    * Metodo que retorna query de una busqueda por alguna llave
    *
    */
    function GetQueryBuscarOrdenTipo1($filtroPrincipalTipo1, $filtroExamenes, $ListasSeleccionadas, $tipo_presentasion, $conteo)
    {

        if($conteo)
        {
            $filtroConteo = "COUNT(*)";
            $filtroOffSet = "";
        }
        else
        {
            //CALCULO DEL OFFSET
            if(!$_REQUEST['Of'])
            {
                $Of='0';
            }
            else
            {
                $Of=$_REQUEST['Of'];
                if($Of > $this->conteo)
                {
                    $Of = 0;
                    $_REQUEST['Of'] = 0;
                    $_REQUEST['paso1'] = 1;
                }
            }

            $filtroConteo = "*";
            $filtroOffSet  = " ORDER BY  servicio_descripcion, fecha_cumplimiento, numero_cumplimiento, tipo_os_lista_id ";
            $filtroOffSet .= " LIMIT ".$this->limit." OFFSET $Of";
        }


        if($tipo_presentasion == '1')
        {
            $select_tipo_presentasion = "
                a.tipo_id_paciente,
                a.paciente_id,
                j.servicio,
                a.numero_cumplimiento,
                a.fecha_cumplimiento,
                l.evolucion_id,
                d.tipo_os_lista_id,
                k.descripcion as servicio_descripcion,
                i.descripcion,
                d.nombre_lista,
                a.numero_orden_id,
                h.cargo_cups as cargo,
                b.resultado_id,
                b.usuario_id_profesional,
                b.usuario_id_profesional_autoriza,
                btrim(c.primer_nombre||' '||c.segundo_nombre||' '|| c.primer_apellido||' '||c.segundo_apellido) as nombre,
								h.hc_os_solicitud_id
            ";


        }
        else
        {

            $select_tipo_presentasion = "
                a.tipo_id_paciente,
                a.paciente_id,
                j.servicio,
                a.numero_cumplimiento,
                a.fecha_cumplimiento,
                '' as historia_prefijo,
                '' as historia_numero,
                k.descripcion as servicio_descripcion,
                btrim(c.primer_nombre||' '||c.segundo_nombre||' '|| c.primer_apellido||' '||c.segundo_apellido) as nombre,
                d.tipo_os_lista_id
            ";
        }

        $query = "SELECT $filtroConteo
                    FROM
                    (
                        SELECT DISTINCT $select_tipo_presentasion

                        FROM
                        (
                            SELECT
                                a.tipo_id_paciente,
                                a.paciente_id,
                                a.numero_cumplimiento,
                                a.fecha_cumplimiento,
                                b.numero_orden_id

                            FROM
                                os_cumplimientos a,
                                os_cumplimientos_detalle b

                            WHERE
                                $filtroPrincipalTipo1
                                AND b.numero_cumplimiento=a.numero_cumplimiento
                                AND b.fecha_cumplimiento= a.fecha_cumplimiento
                                AND b.departamento=a.departamento
                                AND b.sw_estado = '1'
                        )   AS a
                            LEFT JOIN hc_resultados_sistema AS b ON(b.numero_orden_id = a.numero_orden_id),
                            pacientes c,
                            tipos_os_listas_trabajo d,
                            tipos_os_listas_trabajo_detalle e,
                            os_maestro h,
                            cups i,
                            hc_os_solicitudes l,
                            os_ordenes_servicios j,
                            servicios k

                        WHERE
                            c.paciente_id = a.paciente_id
                            AND c.tipo_id_paciente = a.tipo_id_paciente
                            AND h.numero_orden_id  = a.numero_orden_id
                            AND h.sw_estado IN ('1','2','3','4','n')
                            AND i.cargo = h.cargo_cups
                            AND j.orden_servicio_id  = h.orden_servicio_id
                            AND k.servicio = j.servicio
                            AND l.hc_os_solicitud_id = h.hc_os_solicitud_id
                            $ListasSeleccionadas
														$filtroExamenes
                            AND e.tipo_os_lista_id = d.tipo_os_lista_id
                            AND e.grupo_tipo_cargo = i.grupo_tipo_cargo
                            AND e.tipo_cargo = i.tipo_cargo
                    )AS q $filtroOffSet ";
		//echo $query;
        return $query;
		
    }

    /**
    * Metodo que retorna query de una busqueda por nombre y/o apellidos
    *
    */
    function GetQueryBuscarOrdenTipo2($filtroPrincipalTipo2, $filtroExamenes, $ListasSeleccionadas, $tipo_presentasion, $conteo=true)
    {

        if($conteo)
        {
            $filtroConteo = "COUNT(*)";
            $filtroOffSet = "";
        }
        else
        {
            //CALCULO DEL OFFSET
            if(!$_REQUEST['Of'])
            {
                $Of='0';
            }
            else
            {
                $Of=$_REQUEST['Of'];
                if($Of > $this->conteo)
                {
                    $Of = 0;
                    $_REQUEST['Of'] = 0;
                    $_REQUEST['paso1'] = 1;
                }
            }

            $filtroConteo = "*";
            $filtroOffSet  = " ORDER BY  servicio_descripcion, fecha_cumplimiento, numero_cumplimiento, tipo_os_lista_id ";
            $filtroOffSet .= " LIMIT ".$this->limit." OFFSET $Of";
        }


         if($tipo_presentasion == '1')
        {
            $select_tipo_presentasion = "
                a.tipo_id_paciente,
                a.paciente_id,
                j.servicio,
                a.numero_cumplimiento,
                a.fecha_cumplimiento,
                l.evolucion_id,
                d.tipo_os_lista_id,
                k.descripcion as servicio_descripcion,
                i.descripcion,
                d.nombre_lista,
                a.numero_orden_id,
                h.cargo_cups as cargo,
                b.resultado_id,
                b.usuario_id_profesional,
                b.usuario_id_profesional_autoriza,
                a.nombre,
								h.hc_os_solicitud_id
            ";


        }
        else
        {

            $select_tipo_presentasion = "
                a.tipo_id_paciente,
                a.paciente_id,
                j.servicio,
                a.numero_cumplimiento,
                a.fecha_cumplimiento,
                '' as historia_prefijo,
                '' as historia_numero,
                k.descripcion as servicio_descripcion,
                a.nombre,
                d.tipo_os_lista_id
            ";
        }

        $query = "SELECT $filtroConteo
                    FROM
                    (
                        SELECT DISTINCT $select_tipo_presentasion

                        FROM
                        (
                            SELECT
                                a.tipo_id_paciente,
                                a.paciente_id,
                                a.numero_cumplimiento,
                                a.fecha_cumplimiento,
                                b.numero_orden_id,
                                c.nombre

                            FROM
                                os_cumplimientos a,
                                os_cumplimientos_detalle b,
                                (
                                    SELECT
                                        tipo_id_paciente,
                                        paciente_id,
                                        btrim(primer_nombre||' '||segundo_nombre||' '||primer_apellido||' '||segundo_apellido) as nombre

                                    FROM pacientes
                                    WHERE
                                    $filtroPrincipalTipo2
                                ) as c
                            WHERE
                                a.departamento = '".$_SESSION['LTRABAJOAPOYOD']['DPTO']."'
                                AND a.paciente_id = c.paciente_id
                                AND a.tipo_id_paciente = c.tipo_id_paciente
                                AND b.numero_cumplimiento=a.numero_cumplimiento
                                AND b.fecha_cumplimiento= a.fecha_cumplimiento
                                AND b.departamento=a.departamento
                                AND b.sw_estado = '1'
                        )   AS a
                            LEFT JOIN hc_resultados_sistema AS b ON(b.numero_orden_id = a.numero_orden_id),
                            tipos_os_listas_trabajo d,
                            tipos_os_listas_trabajo_detalle e,
                            os_maestro h,
                            cups i,
                            hc_os_solicitudes l,
                            os_ordenes_servicios j,
                            servicios k

                        WHERE
                            h.numero_orden_id  = a.numero_orden_id
                            AND h.sw_estado IN ('1','2','3','4')
                            AND i.cargo = h.cargo_cups
                            AND j.orden_servicio_id  = h.orden_servicio_id
                            AND k.servicio = j.servicio
                            AND l.hc_os_solicitud_id = h.hc_os_solicitud_id
                            $ListasSeleccionadas
														$filtroExamenes
                            AND e.tipo_os_lista_id = d.tipo_os_lista_id
                            AND e.grupo_tipo_cargo = i.grupo_tipo_cargo
                            AND e.tipo_cargo = i.tipo_cargo
                    )AS q  $filtroOffSet ";
        return $query;
    }

    function ConsultaExamenesPaciente($resultado_id)
    {
            list($dbconnect) = GetDBconn();
            $query="select a.resultado_id, a.paciente_id, a.tipo_id_paciente, a.cargo, a.tecnica_id,
            a.fecha_realizado, a.usuario_id, a.observacion_prestacion_servicio,
            b.numero_orden_id,  k.nombre as transcriptor, f.razon_social as laboratorio,
            case when (g.titulo_examen = '' or g.titulo_examen ISNULL) then h.descripcion
            else g.titulo_examen end as titulo, g.informacion

            FROM hc_resultados as a left join system_usuarios as k on (a.usuario_id = k.usuario_id),
            hc_resultados_sistema as b, apoyod_cargos as g, cups as h,
            os_maestro as c left join os_internas as d on(c.numero_orden_id=d.numero_orden_id)
            left join departamentos as e on(d.departamento=e.departamento) left join empresas
            as f on(e.empresa_id=f.empresa_id)

            WHERE a.resultado_id = b.resultado_id and
            a.resultado_id = ".$resultado_id." and b.numero_orden_id=c.numero_orden_id
            and a.cargo = g.cargo and g.cargo = h.cargo";

            $result = $dbconnect->Execute($query);
            if ($dbconnect->ErrorNo() != 0)
            {
                    $this->error = "Error al Consultar los datos del examen";
                    $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
                    return false;
            }
            $a=$result->GetRowAssoc($ToUpper = false);

            //cargando las observaciones adicionales
            $query="SELECT a.resultado_id, a.observacion_adicional,
            a.fecha_registro_observacion, c.nombre_tercero as usuario_observacion
            FROM hc_resultados_observaciones_adicionales as a,
            profesionales_usuarios as b, terceros as c
            WHERE resultado_id = ".$resultado_id." AND
            a.usuario_id = b.usuario_id and b.tipo_tercero_id = c.tipo_id_tercero and
            b.tercero_id = c.tercero_id order by a.observacion_resultado_id";

            $result = $dbconnect->Execute($query);
            if ($dbconnect->ErrorNo() != 0)
            {
                    $this->error = "Error al consultar las observaciones adicionales al resultado del apoyo";
                    $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
                    return false;
            }
            else
            {
                    while (!$result->EOF)
                    {
                            $vector2[]=$result->GetRowAssoc($ToUpper = false);
                            $result->MoveNext();
                    }
            }
            $a[observaciones_adicionales]=$vector2;
            //fin de las observaciones adicionales

      if ($_SESSION['CONSULTANDO_APD'] =='1')
            {
                    //revisando si el examen ya fue leido por el medico
                    $query = "SELECT resultado_id, sw_prof, sw_prof_dpto, sw_prof_todos, evolucion_id
                    FROM hc_apoyod_lecturas_profesionales WHERE resultado_id = ".$resultado_id."";

                    $result = $dbconnect->Execute($query);
                    if ($dbconnect->ErrorNo() != 0)
                    {
                            $this->error = "Error al consultar si el examen ya fue visto por el medico";
                            $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
                            return false;
                    }
                    else
                    {
                            while (!$result->EOF)
                            {
                                    $vector3[]=$result->GetRowAssoc($ToUpper = false);
                                    $result->MoveNext();
                            }
                    }
                    $a[lecturas]=$vector3;
                    //fin de la revision
            }
            return $a;
    }


    function ConsultaDetalle($resultado_id, $cargo, $tecnica_id)
    {
            list($dbconnect) = GetDBconn();
            $query= "SELECT DISTINCT
            a.lab_examen_id, a.resultado_id, a.cargo, a.tecnica_id,
            a.resultado, a.sw_alerta, a.rango_min, a.rango_max, a.unidades,
            b.lab_plantilla_id, b.nombre_examen
            FROM hc_apoyod_resultados_detalles a, lab_examenes b
            WHERE  a.resultado_id = ".$resultado_id."   AND a.cargo= '".$cargo."'
            AND a.tecnica_id = ".$tecnica_id." AND a.tecnica_id = b.tecnica_id
            AND a.cargo = b.cargo AND a.lab_examen_id = b.lab_examen_id
            order by b.lab_plantilla_id";

            $result = $dbconnect->Execute($query);
            if ($dbconnect->ErrorNo() != 0)
            {
                    $this->error = "Error al consultar el detalle del examen";
                    $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
                    return false;
            }
            else
            {
                    while (!$result->EOF)
                    {
                            $fact[]=$result->GetRowAssoc($ToUpper = false);
                            $result->MoveNext();
                    }
            }
            return $fact;
    }


    //caso especial para cuando el departamento maneja honorarios
    function ConsultaNombreProfesionalHonorario($numero_orden_id)
    {
            list($dbconnect) = GetDBconn();
            $query="SELECT b.nombre_tercero
            FROM profesionales_usuarios as a, terceros as b, os_cumplimientos_detalle c
            WHERE c.numero_orden_id = ".$numero_orden_id." AND
            a.usuario_id = c.usuario_id AND a.tipo_tercero_id = b.tipo_id_tercero AND
            a.tercero_id = b.tercero_id";
            $result = $dbconnect->Execute($query);
            if ($dbconnect->ErrorNo() != 0)
            {
                    $this->error = "Error al consultar el nombre del profesional";
                    $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
                    return false;
            }
            $a=$result->GetRowAssoc($ToUpper = false);
            return $a;
    }


    function ConfirmarFirmaResultado($resultado_id, $cargo, $tecnica_id)
    {
            //hasta aqui el request existe asi que se almacenan los valores en variables de session
            //ya que despues de pasar por aqui el request desaparece
            $k=0;
          $_SESSION['LTA']['RESULTADO_ID']=$resultado_id;
            $_SESSION['LTA']['CARGO']=$cargo;
            $_SESSION['LTA']['TECNICA_ID']=$tecnica_id;

      $_SESSION['LTA']['ITEMS']=$_REQUEST['items'.$k];
            $_SESSION['LTA']['OBSERVACION']=$_REQUEST['observacion'];
            $_SESSION['LTA']['FIRMA']=$_REQUEST['firma'];
            $_SESSION['LTA']['RESPONSABLE_RESULTADO']=$_REQUEST['responsable'];

            $subindice = $_REQUEST['items'.$k];
            for ($i=0; $i< $subindice; $i++)
            {
                    $_SESSION['LTA']['RESULTADO'.$k.$i] = $_REQUEST['resultado'.$k.$i];
                    $_SESSION['LTA']['SW_PATOLOGICO'.$k.$i] = $_REQUEST['sw_patologico'.$k.$i];
                    $_SESSION['LTA']['RMIN'.$k.$i] = $_REQUEST['rmin'.$k.$i];
                    $_SESSION['LTA']['RMAX'.$k.$i] = $_REQUEST['rmax'.$k.$i];
                    $_SESSION['LTA']['UNIDADES'.$k.$i] = $_REQUEST['unidades'.$k.$i];
                    $_SESSION['LTA']['LAB_EXAMEN'.$k.$i] = $_REQUEST['lab_examen'.$k.$i];
            }

            $mensaje='ESTA SEGURO QUE DESEA FIRMAR EL RESULTADO DE ESTE EXAMEN.';
            $arreglo=array();
            $c='app';
            $m='Os_Listas_Trabajo_Apoyod_Agrupado';
            $me='ActualizarDatosResultado';
            $me2='BuscarOrden';
            $Titulo='FIRMAR EXAMEN';
            $boton1='ACEPTAR';
            $boton2='CANCELAR';
            $this->ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,$arreglo,$c,$m,$me,$me2);
            return true;
    }


    function LlenadoConfirmarFirmaResultado()
    {
            $mensaje='ESTA SEGURO QUE DESEA FIRMAR EL RESULTADO DE ESTE EXAMEN.';
            $arreglo=array();
            $c='app';
            $m='Os_Listas_Trabajo_Apoyod_Agrupado';
            $me='FirmarDatosResultado';
            $me2='BuscarOrden';
            $Titulo='FIRMAR EXAMEN';
            $boton1='ACEPTAR';
            $boton2='CANCELAR';
            $this->ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,$arreglo,$c,$m,$me,$me2);
            return true;
    }


    function FirmarDatosResultado()
    {
            list($dbconn) = GetDBconn();
            $dbconn->BeginTrans();
      if (empty($_SESSION['FIRMA']['apoyos']))
            {
        $_SESSION['FIRMA']['apoyos'] = 1;
            }
      for($cont=0;$cont<$_SESSION['FIRMA']['apoyos'];$cont++)
            {
                    unset ($a);
                    /*$query="UPDATE hc_resultados_sistema SET usuario_id_profesional = ".UserGetUID()."
                                    WHERE resultado_id = ".$_SESSION['LTA_FIRMA_INS']['RESULTADO_ID'][$cont]."";*/

         $query="UPDATE hc_resultados_sistema SET usuario_id_profesional_autoriza = ".UserGetUID()."
                                    WHERE resultado_id = ".$_SESSION['LTA_FIRMA_INS']['RESULTADO_ID'][$cont]."";

                    //usuario_id_profesional ya no es el que firma sino el responsable del diagnostico
                    //usuario_id_profesional_autoriza es el que firma.


                    $resulta1=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                            $this->error = "Error al actualizar el resultado con la firma del profesional";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                    }

                    //bloque que cambia el estado de os maestro cuando se ha firmado un resultado que ha sido digitado

                    //OJO CLAUDIA AQUIE SE ESTA TRAYENDO LA EVOLUCION DE NUEVO Y ELLA YA EXISTE EN UNA VARIBLE DE SSION ESTO CUANDO
                    //SE HACE POR TRANSCRIPCION INDIVIDUAL EN MODO CREACION, HABRIA QUE MIRAR SI AL MODIFICAR O AL INSERTAR GRUPAL SE TIEN ESA
                    //VARIABLE.

                    $query="SELECT  a.numero_orden_id, c.evolucion_id
                    FROM hc_resultados_sistema a, os_maestro b, hc_os_solicitudes c
                    WHERE a.resultado_id = ".$_SESSION['LTA_FIRMA_INS']['RESULTADO_ID'][$cont]."
                    and a.numero_orden_id = b.numero_orden_id
                    and b.hc_os_solicitud_id = c.hc_os_solicitud_id";
                    $resulta1=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                            $this->error = "Error al buscar el numero de la orden y la evolucion_id";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                    }
                    else
                    {
                            $a=$resulta1->GetRowAssoc($ToUpper = false);
                            if ($resulta1->RecordCount() > 0)
                            {
                                    $query="UPDATE os_maestro SET sw_estado = '4'
                                    WHERE numero_orden_id = ".$a[numero_orden_id]."";
                                    $resulta1=$dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0)
                                    {
                                            $this->error = "Error al actualizar el estado en os_maestro1";
                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                            $dbconn->RollbackTrans();
                                            return false;
                                    }
                                    //LO NUEVO de la lectura grupal
                                    if(!empty($a[evolucion_id]))
                                    {
                                            $query="SELECT  count (*) FROM hc_apoyod_lectura_grupal
                                            WHERE evolucion_id_solicitud = ".$a[evolucion_id]." and (sw_prof = '1' OR sw_prof_dpto = '1'
                                            OR sw_prof_todos = '1')";
                                            $resulta1=$dbconn->Execute($query);
                                            if ($dbconn->ErrorNo() != 0)
                                            {
                                                    $this->error = "Error al consultar las lecturas";
                                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                    $dbconn->RollbackTrans();
                                                    return false;
                                            }
                                            else
                                            {
                                                    list($conteo)=$resulta1->fetchRow();
                                            }
                                            if($conteo==1)
                                            {
                                                    $query="UPDATE hc_apoyod_lectura_grupal SET sw_prof = '2'
                                                    WHERE evolucion_id_solicitud = ".$a[evolucion_id]." and sw_prof= '1';

                                                    UPDATE hc_apoyod_lectura_grupal SET sw_prof_dpto = '2'
                                                    WHERE evolucion_id_solicitud = ".$a[evolucion_id]." and sw_prof_dpto= '1';

                                                    UPDATE hc_apoyod_lectura_grupal SET sw_prof_todos = '2'
                                                    WHERE evolucion_id_solicitud = ".$a[evolucion_id]." and sw_prof_todos= '1'";

                                                    $resulta1=$dbconn->Execute($query);
                                                    if ($dbconn->ErrorNo() != 0)
                                                    {
                                                            $this->error = "Error al actualizar el estado de las lecturas";
                                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                            $dbconn->RollbackTrans();
                                                            return false;
                                                    }
                                            }
                                    }
                            //FIN DE LO NUEVO
                            }
                            else
                            {
                                    return false;
                            }
                    }
                    //fin de los estados
                //MauroB
                    //$this->EliminaExamenListaVitros($_SESSION['LTA_FIRMA_INS']['RESULTADO_ID'][$cont]);
                //fin MauroB
      }
            $dbconn->CommitTrans();
            $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
            unset ($_SESSION['LTA_FIRMA_INS']['RESULTADO_ID']);

//          si hay datos y session no esta vacia
//              regresa el formulario
//          sino
//              busca orden
            if($_SESSION[FIRMA][apoyos] < $_SESSION[DATOS_APD][cantidad_datos])
            {
                $tipo_id_paciente=$_SESSION['DATOS_APD']['CONSULTA_RESULT']['tipo_id_paciente'];
                $paciente_id=$_SESSION['DATOS_APD']['CONSULTA_RESULT']['paciente_id'];
                $nombre=$_SESSION['DATOS_APD']['CONSULTA_RESULT']['nombre'];
                $servicio=$_SESSION['DATOS_APD']['CONSULTA_RESULT']['servicio'];
                $numero_cumplimiento=$_SESSION['DATOS_APD']['CONSULTA_RESULT']['numero_cumplimiento'];
                $fecha_cumplimiento=$_SESSION['DATOS_APD']['CONSULTA_RESULT']['fecha_cumplimiento'];
                unset($_SESSION['DATOS_APD']);
                unset($_SESSION['APOYO']);
                $this->Capturar_Resultados($tipo_id_paciente, $paciente_id, $nombre, $servicio, $numero_cumplimiento, $fecha_cumplimiento);
            }
            else
            {
                $this->BuscarOrden();
            }
            return true;
    }


    function ActualizarDatosResultado($resultado_id, $cargo, $tecnica_id)
    {//echo "<br>entre a ActualizarDatosResultado";
    //print_r($_REQUEST);
      $k=0;
            if(!empty($_SESSION['LTA']))
            {
                    $resultado_id = $_SESSION['LTA']['RESULTADO_ID'];
                    $cargo = $_SESSION['LTA']['CARGO'];
                    $tecnica_id = $_SESSION['LTA']['TECNICA_ID'];

          $_REQUEST['items'.$k] = $_SESSION['LTA']['ITEMS'];
                    $_REQUEST['observacion']=$_SESSION['LTA']['OBSERVACION'];
                    $_REQUEST['firma'] = $_SESSION['LTA']['FIRMA'];
                    $_REQUEST['responsable']=$_SESSION['LTA']['RESPONSABLE_RESULTADO'];

                    $subindice = $_REQUEST['items'.$k];
                    for ($i=0; $i< $subindice; $i++)
                    {
                            $_REQUEST['resultado'.$k.$i] = $_SESSION['LTA']['RESULTADO'.$k.$i];;
                            $_REQUEST['sw_patologico'.$k.$i] = $_SESSION['LTA']['SW_PATOLOGICO'.$k.$i];
                            $_REQUEST['rmin'.$k.$i] = $_SESSION['LTA']['RMIN'.$k.$i];
                            $_REQUEST['rmax'.$k.$i] = $_SESSION['LTA']['RMAX'.$k.$i];
                            $_REQUEST['unidades'.$k.$i] = $_SESSION['LTA']['UNIDADES'.$k.$i];
                            $_REQUEST['lab_examen'.$k.$i] = $_SESSION['LTA']['LAB_EXAMEN'.$k.$i];
                    }
            }
            $subindice = $_REQUEST['items'.$k];
            for ($i=0; $i< $subindice; $i++)
            {
                    if (($_REQUEST['resultado'.$k.$i] === '') OR ($_REQUEST['resultado'.$k.$i] == -1))
                    {
                            $this->frmError['resultado'.$k.$i]=1;
                            $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS, DEBE ESCRIBIR UN RESULTADO.";
                            return false;
                    }
            }
      //echo 'lograr';
            //OJO CLAUDIA
            //segun lo probado funciona ok la inserrcion, la modificacion y la firma de un examen
            //multiple e unico en el modo individual.

            //lo que sigue para finiquitar esta parte es definir con alex ya que cuando retorna este false
            //al faltar un resultado el sistema no sabe a donde retornar
            //continuar en eso el martes 14 de junio.

            list($dbconn) = GetDBconn();
            $dbconn->BeginTrans();

            $query="UPDATE hc_resultados SET observacion_prestacion_servicio = '".$_REQUEST['observacion']."'
                            WHERE resultado_id = ".$resultado_id."";
            $resulta1=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                    $this->error = "Error al actualizar la observacion del prestador del servicio en hc_resultados";
                    echo "Error al actualizar la observacion del prestador del servicio en hc_resultados";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
            }
            else
            {
                        if($_REQUEST['responsable']!=-1){
                                $responsable=$_REQUEST['responsable'];
                            }else{
                                $responsable='NULL';
                            }
                            //aqui firma
                            /*ECHO $query="UPDATE hc_resultados_sistema SET usuario_id_profesional = ".UserGetUID()."
                                            WHERE resultado_id = ".$resultado_id."";*/
                            $query="UPDATE hc_resultados_sistema SET usuario_id_profesional = $responsable
                          WHERE resultado_id = ".$resultado_id."";
                            $resulta1=$dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0)
                            {
                                    $this->error = "Error al actualizar usuario_id_profesional en hc_resultados_sistema";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    $dbconn->RollbackTrans();
                                    return false;
                            }
                    if($_REQUEST['firma']==1)
                    {
                        $realizar_firma=true;
                        //Verificar sw de vitros depto
                        if($_SESSION['LTRABAJOAPOYOD']['SW_VITROS']=='1'){
                            //confirma que se hayan realizado todos los subexamenes vitros
                            $ex_vitros=$this->ConfirmaExamenCompletoVitros($resultado_id, $cargo, $tecnica_id);
                            if(!$ex_vitros){$realizar_firma=false;}
                        }
                        if($realizar_firma)
                        {
                            //aqui firma
                             $query="UPDATE hc_resultados_sistema SET usuario_id_profesional_autoriza = ".UserGetUID()."
                                            WHERE resultado_id = ".$resultado_id."";
//                          $query="UPDATE hc_resultados_sistema SET usuario_id_profesional_autoriza = $responsable
//                        WHERE resultado_id = ".$resultado_id."";
                            $resulta1=$dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0)
                            {
                                    $this->error = "Error al actualizar usuario_id_profesional_autoriza en hc_resultados_sistema";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    $dbconn->RollbackTrans();
                                    return false;
                            }//inserto este else para alterar el estado en os_maestro a 4.
                            else
                            {
                                    $query="SELECT  a.numero_orden_id, c.evolucion_id
                                    FROM hc_resultados_sistema a, os_maestro b, hc_os_solicitudes c
                                    WHERE a.resultado_id = ".$resultado_id." and a.numero_orden_id = b.numero_orden_id
                                    and b.hc_os_solicitud_id = c.hc_os_solicitud_id";
                                    $resulta1=$dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0)
                                    {
                                            $this->error = "Error al buscar el numero de la orden";
                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                            $dbconn->RollbackTrans();
                                            return false;
                                    }
                                    else
                                    {
                                            $a=$resulta1->GetRowAssoc($ToUpper = false);
                                            if ($resulta1->RecordCount() > 0)
                                            {
                                                    $query="UPDATE os_maestro SET sw_estado = '4'
                                                    WHERE numero_orden_id = ".$a[numero_orden_id]."";
                                                    $resulta1=$dbconn->Execute($query);
                                                    if ($dbconn->ErrorNo() != 0)
                                                    {
                                                            $this->error = "Error al actualizar el estado en os_maestro2";
                                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                            $dbconn->RollbackTrans();
                                                            return false;
                                                    }
                                                    //LO NUEVO de la lectura grupal
                                                    if(!empty($a[evolucion_id]))
                                                    {
                                                            $query="SELECT  count (*) FROM hc_apoyod_lectura_grupal
                                                            WHERE evolucion_id_solicitud = ".$a[evolucion_id]." and (sw_prof = '1' OR sw_prof_dpto = '1'
                                                            OR sw_prof_todos = '1')";
                                                            $resulta1=$dbconn->Execute($query);
                                                            if ($dbconn->ErrorNo() != 0)
                                                            {
                                                                    $this->error = "Error al consultar el numero de lecturas realizadas";
                                                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                    $dbconn->RollbackTrans();
                                                                    return false;
                                                            }
                                                            else
                                                            {
                                                                    list($conteo)=$resulta1->fetchRow();
                                                            }
                                                            if($conteo==1)
                                                            {
                                                                    $query="UPDATE hc_apoyod_lectura_grupal SET sw_prof = '2'
                                                                    WHERE evolucion_id_solicitud = ".$a[evolucion_id]." and sw_prof= '1';

                                                                    UPDATE hc_apoyod_lectura_grupal SET sw_prof_dpto = '2'
                                                                    WHERE evolucion_id_solicitud = ".$a[evolucion_id]." and sw_prof_dpto= '1';

                                                                    UPDATE hc_apoyod_lectura_grupal SET sw_prof_todos = '2'
                                                                    WHERE evolucion_id_solicitud = ".$a[evolucion_id]." and sw_prof_todos= '1'";

                                                                    $resulta1=$dbconn->Execute($query);
                                                                    if ($dbconn->ErrorNo() != 0)
                                                                    {
                                                                            $this->error = "Error al actualizar el estado en hc_apoyod_lectura_grupal";
                                                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                            $dbconn->RollbackTrans();
                                                                            return false;
                                                                    }
                                                            }
                                                    }
                                                    //FIN DE LO NUEVO
                                            }
                                            else
                                            {
                                                    return false;
                                            }
                                    }
                            }
                            //MauroB
                            //si se firmaron los subexamenes vitros,los borramos de la bd Vitros

                            if(($_SESSION['LTRABAJOAPOYOD']['SW_VITROS']=='1') && ($ex_vitros)){
                                $this->EliminaExamenListaVitros($resultado_id);
                            }
                            //finMauroB
                    }else{
                    //faltan examenes por resultado no se puede firmar

                    }
                }
            }
            for ($i=0; $i< $subindice; $i++)
            {
                    if ($_REQUEST['sw_patologico'.$k.$i]!='')
                    {
                            $sw_alerta = '1';
                    }
                    else
                    {
                            if ((($_REQUEST['rmin'.$k.$i]) != '') and (($_REQUEST['rmax'.$k.$i]) != ''))
                            {
                                    if (($_REQUEST['resultado'.$k.$i]>= $_REQUEST['rmin'.$k.$i]) and ($_REQUEST['resultado'.$k.$i] <= $_REQUEST['rmax'.$k.$i]))
                                    {
                                            $sw_alerta = '0';
                                    }
                                    else
                                    {
                                            $sw_alerta = '1';
                                    }
                            }
                            else
                            {
                                    $sw_alerta = '0';
                            }
                    }
                    $query="UPDATE hc_apoyod_resultados_detalles
                                    SET resultado = '".$_REQUEST['resultado'.$k.$i]."', sw_alerta = '".$sw_alerta."',
                                    rango_min = '".$_REQUEST['rmin'.$k.$i]."', rango_max = '".$_REQUEST['rmax'.$k.$i]."',
                                    unidades = '".$_REQUEST['unidades'.$k.$i]."'
                                    WHERE lab_examen_id = ".$_REQUEST['lab_examen'.$k.$i]."
                                    and resultado_id = ".$resultado_id."
                                    and cargo = '".$cargo."' and tecnica_id = ".$tecnica_id."";
                    $resulta1=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                            $this->error = "Error al actualizar el resultado en  hc_apoyod_resultados_detalles";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                    }
            }
            $dbconn->CommitTrans();
            $this->frmError["MensajeError"]="DATOS MODIFICADOS SATISFACTORIAMENTE.";
            if (!empty($_REQUEST['firma']))
            {
                    $this->BuscarOrden();
            }
            return true;
    }
//MauroB
    /**
    * Se encarga de verificar si los examenes vitros que estan a punto de ser firmados
    * han sido todos diagnosticados y transcitos.
    * @param $resultado_id  resultado de la muestra
    * @param $cargo                 identificacion del cargo
    * @param $tecnica_id        tecnica utilizada
    * @return boolean
    */
    function ConfirmaExamenCompletoVitros($resultado_id, $cargo, $tecnica_id){
        list($dbconnect) = GetDBconn();
        $query="SELECT  sufijo_muestra_id FROM      interface_vitros_cargo WHERE        codigo_cups = '$cargo'";
        $result = $dbconnect->Execute($query);
        if ($dbconnect->ErrorNo() != 0){
                $this->error = "Error al realizar consultar de examen completo";
                $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
                return false;
        }else
        {
            $sufijo=$result->fields[0];
            if((!empty($sufijo))||($sufijo!=NULL)){
                //es un resultado vitros
                $query="SELECT  count(*) FROM   hc_apoyod_resultados_detalles    WHERE  resultado_id    = $resultado_id";
                $result = $dbconnect->Execute($query);
                if ($dbconnect->ErrorNo() != 0){
                        $this->error = "Error al realizar consultar de examen completo";
                        $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
                        return false;
                }else{
                    $cont=$result->fields[0];
                    if($cont<$sufijo){
                        return false;
                    }else{
                        return true;
                    }
                }
            }else{
                //no pertenece a vitros
                return true;
            }
        }

    }//fin ConfirmaExamenCompleto
//fin MauroB


    function Consultar_Opciones($lab_examen_id, $cargo, $tecnica_id)
    {
            list($dbconnect) = GetDBconn();
            $query= "SELECT opcion from lab_plantilla2
            WHERE lab_examen_id = ".$lab_examen_id." AND cargo = '".$cargo."'
            AND tecnica_id = ".$tecnica_id."";
            $result = $dbconnect->Execute($query);
            if ($dbconnect->ErrorNo() != 0)
            {
                    $this->error = "Error al realizar consultar las opciones";
                    $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
                    return false;
            }
            else
            {
                    while (!$result->EOF)
                    {
                        $fact[]=$result->GetRowAssoc($ToUpper = false);
                        $result->MoveNext();
                    }
            }
        return $fact;
    }


    function Insertar()
    {
            $k=0;
            $fecha= $_REQUEST['fecha_realizado']; //la fecha y la observacion se manejaron sin el indice $k
            if($fecha =='')
            {
                    $this->frmError['fecha_realizado'.$k]=1;
                    $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS, SELECCIONE UNA FECHA.";
                    return false;
            }
            else
            {
                    $cad=explode ('-',$fecha);
                    $dia = $cad[0];
                    $mes = $cad[1];
                    $ano = $cad[2];
                    $fecha=$cad[2].'-'.$cad[1].'-'.$cad[0];
                    if (date("Y-m-d",mktime(0,0,0,$mes,$dia,$ano)) > date("Y-m-d",mktime(0,0,0,date('m'),date('d'),date('Y'))))
                    {
                            $this->frmError['fecha_realizado'.$k]=1;
                            $this->frmError["MensajeError"]="FECHA INVALIDA, SELECCIONE UNA FECHA INFERIOR O IGUAL A LA ACTUAL .";
                            return false;
                    }
            }

            if (!$_REQUEST['items'.$k])
            {
                    $this->frmError["MensajeError"]="CAMPOS DE RESULTADO INEXISTENTES.";
                    return false;
            }
						$subindice = $_REQUEST['items'.$k];
            for ($i=0; $i< $subindice; $i++)
            {
							$cadena = $this->ReemplazarCaracteres($_REQUEST['resultado'.$k.$i]);
							if (trim($cadena) === '' or $_REQUEST['resultado'.$k.$i] == -1)
							{
								$this->frmError['resultado'.$k.$i]=1;
								$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS, DEBE ESCRIBIR UN RESULTADO.";
								return false;
							}
            }

            list($dbconn) = GetDBconn();
            $dbconn->BeginTrans();

            //OBTENER EL TIPO DE RESULTADO - CAMBIO GENERADO PARA INSERTAR RESULTADOS DE PNQ
            $query= "SELECT c.apoyod_tipo_id as tipo_resultadoapd,
                            d.grupo_tipo_cargo as tipo_resultadonqx
                            FROM cups a, grupos_noqx_apoyod b left join apoyod_tipos c
                            on (b.grupo_tipo_cargo = c.apoyod_tipo_id) left join
                            no_qx_grupos_tipo_cargo d on (b.grupo_tipo_cargo = d.grupo_tipo_cargo)
                            WHERE a.cargo = '".$_SESSION['LISTA']['APOYO']['cargo']."'
                            AND a.grupo_tipo_cargo = b.grupo_tipo_cargo";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Consultar el tipo de resultado para el examen";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $tipo_resultado=$result->GetRowAssoc($ToUpper = false);
            if ($tipo_resultado)
            {
                    if ($tipo_resultado[tipo_resultadoapd]!= NULL OR $tipo_resultado[tipo_resultadoapd]!= '')
                    {
                            $os_tipo_resultado = ModuloGetVar('','','TipoSolicitudApoyod');
                    }
                    else
                    {
                            if ($tipo_resultado[tipo_resultadonqx]!= NULL OR $tipo_resultado[tipo_resultadonqx]!= '')
                            {
                                $os_tipo_resultado = 'PNQ';
                            }
                            else
                            {
                                $this->frmError["MensajeError"]="ESTE EXAMEN NO TIENE ASOCIADO UN TIPO DE RESULTADO.";
                                return false;
                            }
                    }
            }
            else
            {
                    $this->frmError["MensajeError"]="ESTE EXAMEN NO TIENE ASOCIADO UN TIPO DE RESULTADO.";
                    return false;
            }
            //FIN
            //realiza el id manual de la tabla
            $query="SELECT nextval('hc_resultados_resultado_id_seq')";
            $result=$dbconn->Execute($query);
            $resultado_id=$result->fields[0];
            //fin de la operacion

            $query="INSERT INTO hc_resultados (resultado_id,
                            cargo, tecnica_id,
                            fecha_registro, usuario_id, tipo_id_paciente, paciente_id,
                            fecha_realizado,
                            os_tipo_resultado,
                            observacion_prestacion_servicio, sw_modo_resultado)
                            VALUES(".$resultado_id.",
                            '".$_SESSION['LISTA']['APOYO']['cargo']."', ".$_SESSION['LISTA']['APOYO']['tecnica_id'].",
                            now(), ".UserGetUID().", '".$_SESSION['LISTA']['APOYO']['tipo_id_paciente']."',
                            '".$_SESSION['LISTA']['APOYO']['paciente_id']."',
                            '".$fecha."', '".$os_tipo_resultado."', '".$_REQUEST['observacion']."', '1')";

            $resulta1=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                    $this->error = "Error al insertar en hc_resultados";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
            }
            else
            {
                if(!empty($_SESSION['LISTA']['APOYO']['responsable']))
                {
                    $responsable=$_SESSION['LISTA']['APOYO']['responsable'];
                }
                else
                {
                    $responsable='NULL';
                }
//              $query="INSERT INTO hc_resultados_sistema
//                              (resultado_id, numero_orden_id,usuario_id_profesional)
//                              VALUES  (".$resultado_id.", ".$_SESSION['LISTA']['APOYO']['numero_orden_id'].",
//                              $responsable)";
                $permiso=$this->ConsultaPermisoLecturaSinFirma($_SESSION['LISTA']['APOYO']['cargo'], $_SESSION['LTRABAJOAPOYOD']['DPTO']);
                if(($permiso==NULL)||(empty($permiso))){
                    $permiso='0';}
                //MauroB
                $query="INSERT INTO hc_resultados_sistema
                                (resultado_id, numero_orden_id,usuario_id_profesional,sw_consulta_examen_sin_firmar)
                                VALUES  (".$resultado_id.", ".$_SESSION['LISTA']['APOYO']['numero_orden_id'].",
                                $responsable,$permiso)";
                //fin MauroB
                $resulta3=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                        $this->error = "Error al insertar en hc_resultados_sistema";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                }
                else
                {
                        for ($i=0; $i< $subindice; $i++)
                        {
                                $lab_examen='lab_examen'.$k.$i;
                                $res='resultado'.$k.$i;

                                if ($_REQUEST['sw_patologico'.$k.$i]!='')
                                {
                                        $sw_alerta = '1';
                                }
                                else
                                {
                                        if ((($_REQUEST['rmin'.$k.$i]) != '') and (($_REQUEST['rmax'.$k.$i]) != ''))
                                        {
                                                if (($_REQUEST[$res]>= $_REQUEST['rmin'.$k.$i]) and ($_REQUEST[$res] <= $_REQUEST['rmax'.$k.$i]))
                                                {
                                                        $sw_alerta = '0';
                                                }
                                                else
                                                {
                                                        $sw_alerta = '1';
                                                }
                                        }
                                        else
                                        {
                                                $sw_alerta = '0';
                                        }
                                }
                                if ($_REQUEST['rmin'.$k.$i] == 'NULL'){$_REQUEST['rmin'.$k.$i]='';}
                                if ($_REQUEST['rmax'.$k.$i] == 'NULL'){$_REQUEST['rmax'.$k.$i]='';}
                                if ($_REQUEST['unidades'.$k.$i] == 'NULL'){$_REQUEST['unidades'.$k.$i]='';}


                                $query="INSERT INTO hc_apoyod_resultados_detalles
                                (cargo, tecnica_id, lab_examen_id, resultado_id, resultado,
                                sw_alerta, rango_min, rango_max, unidades)
                                VALUES  ('".$_SESSION['LISTA']['APOYO']['cargo']."',
                                ".$_SESSION['LISTA']['APOYO']['tecnica_id'].",
                                ".$_REQUEST[$lab_examen].",".$resultado_id.",'".$_REQUEST[$res]."', '".$sw_alerta."',
                                '".$_REQUEST['rmin'.$k.$i]."',  '".$_REQUEST['rmax'.$k.$i]."',  '".$_REQUEST['unidades'.$k.$i]."')";

                                $resulta4=$dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0)
                                {
                                        $this->error = "Error al insertar en hc_apoyod_resultados_detalles";
                                        $this->frmError["MensajeError"]="ERROR AL INSERTAR EL RESULTADO.";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                                }
                        }
                }
            }
            $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
            $dbconn->CommitTrans();
            $_SESSION['LTA_FIRMA_INS']['RESULTADO_ID'][0]=$resultado_id;
            return true;
    }


    /**
        * Llama la forma ConfirmarAccion (forma de mensaje de dos botones).
        * @ access public
        * @ return boolean
    */
    function ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,$arreglo,$c,$m,$me,$me2)
    {
            if(empty($Titulo))
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
            }
            $this->salida=ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,array($c,$m,'user',$me,$arreglo),array($c,$m,'user',$me2,$arreglo));
            return true;
    }


    function Insertar_Observacion_Adicional($resultado_id)
    {
            list($dbconn) = GetDBconn();
            if( $_REQUEST['observacion_adicional']=='')
            {
                    $this->frmError["observacion_adicional"]=1;
                    $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS. ESCRIBA UNA OBSERVACION";
                    return false;
            }
            $query = "INSERT INTO hc_resultados_observaciones_adicionales
                                (resultado_id, usuario_id, observacion_adicional, fecha_registro_observacion)
                                VALUES(".$resultado_id.", ".UserGetUID().", '".$_REQUEST['observacion_adicional']."',
                                now());";

            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                    $this->error = "Error al insertar en hc_resultados_observaciones_adicionales";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->frmError["MensajeError"]="NO FUE POSIBLE INSERTAR LA OBSERVACION";
                    return false;
            }
            $this->frmError["MensajeError"]="OBSERVACION INSERTADA SATISFACTORIAMENTE";
            return true;
    }


    //DARLING
    /**
    * Separa la fecha del formato timestamp
    * @access private
    * @return string
    * @param date fecha
    */
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


    /*
    * Cambiamos el formato timestamp a un formato de fecha legible para el usuario
    */
    function FechaStampNombreMes($fecha)
    {
        if(!empty($fecha))
        {
                $f=explode(".",$fecha);
                $fecha_arreglo=explode(" ",$f[0]);
                $fecha_real=explode("-",$fecha_arreglo[0]);
                return str_replace(' ',"_",ucwords(strftime("%d %B %Y",strtotime($fecha_arreglo[0]))));
        }
        else
        {
            return "-----";
        }
        return true;
    }


    /**
    * Separa la hora del formato timestamp
    * @access private
    * @return string
    * @param date hora
    */
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


    function Obtener_Edad($tipo_id_paciente, $paciente_id)
    {
            list($dbconn) = GetDBconn();
            $query="SELECT fecha_nacimiento FROM pacientes
            WHERE  paciente_id = '".$paciente_id."' AND
            tipo_id_paciente =  '".$tipo_id_paciente."'";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                    $this->error = "Error al consultar la tabla hc_medicamentos_recetados_amb";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            $a=$result->GetRowAssoc($ToUpper = false);
            $result->Close();
            $edad_paciente = CalcularEdad($a[fecha_nacimiento],date("Y-m-d"));
            return $edad_paciente;
    }



    //********************************funciones para la nueva version

    function GetSexo($tipo_id_paciente, $paciente_id)
    {
            list($dbconn) = GetDBconn();
            $query="SELECT sexo_id FROM pacientes
            WHERE  paciente_id = '".$paciente_id."' AND
            tipo_id_paciente =  '".$tipo_id_paciente."'";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                    $this->error = "Error al consultar el sexo del paciente";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            if (!$result->EOF)
            {
                    list($sexo)=$result->FetchRow();
                    $result->Close();
            }
            return $sexo;
    }

    function ConsultaOrdenesPaciente($paciente_id, $tipo_id_paciente, $servicio, $numero_cumplimiento, $fecha_cumplimiento, $sexo_paciente)
    {
			list($dbconnect) = GetDBconn();
			//$dbconnect->debug = true;
			//condicion para traer los elementos de las listas seleccionadas
      //$_REQUEST['op'] = $_SESSION['BUSQUEDA']['listas'];
      $_REQUEST['op'] =  $_SESSION['BUSQUEDA']['POSTDATA']['op'];
			if (!empty($_REQUEST['op']))
			{
				$search="";
				$union= "";
				$indice = 1;
				foreach($_REQUEST['op'] as $index=>$codigo)
				{
					$arreglo=explode(",",$codigo);
					if($indice==1)
					{
						$union = ' and  ((';
					}
					else
					{
						$union = ' or (';
					}
					$search.= "$union f.tipo_os_lista_id = '".$arreglo[0]."')";
					$indice++;
				}
				$search.=")";
			}
			else
			{
				$search="";
			}
			//fin de la condicion

		if(!empty($numero_cumplimiento) and !empty($fecha_cumplimiento))
		{
			$query = "SELECT b.numero_orden_id, 
									c.cargo_cups as cargo, 
									d.servicio,
									b.departamento, 
									b.numero_cumplimiento, 
									b.fecha_cumplimiento,
									h.nombre_lista, 
									i.descripcion as servicio_descripcion,
									case when (j.titulo_examen = '' or j.titulo_examen ISNULL)
									then g.descripcion else j.titulo_examen end as titulo, j.informacion,
									b.usuario_id, c.hc_os_solicitud_id, k.evolucion_id

								FROM os_cumplimientos_detalle b
								left join hc_resultados_sistema as e on (b.numero_orden_id = e.numero_orden_id),
								os_maestro c, os_ordenes_servicios d,
								tipos_os_listas_trabajo_detalle as f, cups as g,
								tipos_os_listas_trabajo as h, servicios i, apoyod_cargos j,
								hc_os_solicitudes k

								WHERE b.numero_cumplimiento = ".$numero_cumplimiento." AND
								b.fecha_cumplimiento = '".$fecha_cumplimiento."' AND
								b.departamento ='".$_SESSION['LTRABAJOAPOYOD']['DPTO']."'
								and b.numero_orden_id = c.numero_orden_id
								and c.orden_servicio_id = d.orden_servicio_id
								and d.servicio = i.servicio
								and c.sw_estado = '3' and b.sw_estado ='1'
								and e.resultado_id ISNULL
								and c.cargo_cups=g.cargo
								and g.cargo = j.cargo
								$search
								and f.grupo_tipo_cargo = g.grupo_tipo_cargo
								and f.tipo_cargo = g.tipo_cargo
								and f.tipo_os_lista_id=h.tipo_os_lista_id
								and c.hc_os_solicitud_id = k.hc_os_solicitud_id

								and (j.sexo_id = '".$sexo_paciente."' OR j.sexo_id = '0')

								order by h.nombre_lista";
		}
            else
            {
                    $filtro_Servicio ='';
                    if(!empty($servicio))
                    {
                            $filtro_Servicio =" and d.servicio = '".$servicio."'";
                    }

                    $query = "SELECT b.numero_orden_id, c.cargo_cups as cargo, d.servicio,
                                        b.departamento, b.numero_cumplimiento, b.fecha_cumplimiento,
                                        h.nombre_lista, i.descripcion as servicio_descripcion,
                                        case when (j.titulo_examen = '' or j.titulo_examen ISNULL)
                                        then g.descripcion else j.titulo_examen end as titulo, j.informacion,
                                        b.usuario_id, c.hc_os_solicitud_id, k.evolucion_id

                                        FROM os_cumplimientos a, os_cumplimientos_detalle b
                                        left join hc_resultados_sistema as e on (b.numero_orden_id = e.numero_orden_id),
                                        os_maestro c,   os_ordenes_servicios d,
                                        tipos_os_listas_trabajo_detalle as f, cups as g,
                                        tipos_os_listas_trabajo as h, servicios i, apoyod_cargos j,
                                        hc_os_solicitudes k

                                        WHERE a.paciente_id = '".$paciente_id."'
                                        and a.tipo_id_paciente = '".$tipo_id_paciente."'
                                        and a.numero_cumplimiento = b.numero_cumplimiento
                                        and a.fecha_cumplimiento = b.fecha_cumplimiento
                                        and a.departamento = b.departamento
                                        and b.departamento ='".$_SESSION['LTRABAJOAPOYOD']['DPTO']."'
                                        and b.numero_orden_id = c.numero_orden_id
                                        and c.orden_servicio_id = d.orden_servicio_id
                                        and d.servicio = i.servicio
                                        $filtro_Servicio
                                        and c.sw_estado = '3' and b.sw_estado ='1'
                                        and e.resultado_id ISNULL
                                        and c.cargo_cups=g.cargo
                                        and g.cargo = j.cargo
                                        $search
                                        and f.grupo_tipo_cargo = g.grupo_tipo_cargo
                                        and f.tipo_cargo = g.tipo_cargo
                                        and f.tipo_os_lista_id=h.tipo_os_lista_id
                                        and c.hc_os_solicitud_id = k.hc_os_solicitud_id

                                        and (j.sexo_id = '".$sexo_paciente."' OR j.sexo_id = '0')

                                        order by h.nombre_lista";
            }

            // echo "<br><pre>";print_r($query);
            $result = $dbconnect->Execute($query);
            if ($dbconnect->ErrorNo() != 0)
            {
                    $this->error = "Error en la busqueda";
                    $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
                    return false;
            }
            else
            {
                    while (!$result->EOF)
                    {
                            $fact[]=$result->GetRowAssoc($ToUpper = false);
                            $result->MoveNext();
                    }
            }
            if(empty($_SESSION['APOYO'][$tipo_id_paciente][$paciente_id]))
            {
                    $this->Constructor_Session_Apoyo($tipo_id_paciente, $paciente_id, $fact);
            }
            return $fact;
    }


    //construye las variables de session iniciales
    function Constructor_Session_Apoyo($tipo_id_paciente, $paciente_id, $fact)
    {
      $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id]['apoyos'] = sizeof($fact);
            for($k=0; $k<sizeof($fact); $k++)
            {
                    $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['cargo']= $fact[$k]['cargo'];
                    $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['numero_orden_id']= $fact[$k]['numero_orden_id'];
                    $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['hc_os_solicitud_id']= $fact[$k]['hc_os_solicitud_id'];
                    $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['evolucion_id']= $fact[$k]['evolucion_id'];
                    if (!empty($fact[$k]['usuario_id']))
                    {
                            $profesional_honorarios = $this->ConsultaNombreProfesional($fact[$k]['usuario_id']);
                            $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['profesional_honorario'] = $profesional_honorarios[nombre_tercero];
                    }
                    $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['titulo']= $fact[$k]['titulo'];
                    $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['informacion']= $fact[$k]['informacion'];
                    $this->Consultar_Tecnicas_Examen($tipo_id_paciente, $paciente_id, $k, $fact[$k]['cargo']);
            }
            return true;
    }


    function Constructor_Session_Apoyo_Mto($tipo_id_paciente, $paciente_id)
    {
        for($k=0; $k<$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id]['apoyos']; $k++)
        {
        $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['fecha_realizado']= $_REQUEST['fecha_realizado'.$k];
                $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['firma'] = $_REQUEST['firma'.$k];
                $e = 0;
                for($i=0; $i<$_REQUEST['vector'.$k]; $i++)
                {
                        if ($_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['lab_plantilla_id']=='2')
                        {
                                if($_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['lab_examen_id']==$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i+1]['lab_examen_id'])
                                {
                                    $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['resultado']=$_REQUEST['resultado'.$k.$e];
                                }
                else
                                {
                                        $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['rango_min']=$_REQUEST['rmin'.$k.$e];
                                        $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['rango_max']=$_REQUEST['rmax'.$k.$e];
                                        $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['unidades_2']=$_REQUEST['unidades'.$k.$e];
                                        $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['resultado']=$_REQUEST['resultado'.$k.$e];
                                        $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['sw_patologico']=$_REQUEST['sw_patologico'.$k.$e];
                                        $e++;
                                }
                        }
                        else
                        {
                                $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['rango_min']=$_REQUEST['rmin'.$k.$e];
                                $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['rango_max']=$_REQUEST['rmax'.$k.$e];
                                $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['unidades_1']=$_REQUEST['unidades'.$k.$e];
                                $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['resultado']=$_REQUEST['resultado'.$k.$e];
                                $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['sw_patologico']=$_REQUEST['sw_patologico'.$k.$e];
                                $e++;
                        }
                }
        }
        $_SESSION['CONSTRUCTOR_REQUEST']=1;
        return true;
    }


//equivalente a consultar plantillas examen
    function Consultar_Tecnicas_Examen($tipo_id_paciente, $paciente_id, $k, $cargo)
    {
            list($dbconnect) = GetDBconn();
            $query = "SELECT cargo, tecnica_id, nombre_tecnica, sw_predeterminado
            FROM apoyod_cargos_tecnicas WHERE cargo = '".$cargo."' order by sw_predeterminado desc";

            $result = $dbconnect->Execute($query);
            if ($dbconnect->ErrorNo() != 0)
            {
                    $this->error = "Error en la consulta de tecnicas";
                    $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
                    return false;
            }
            else
            {
                    while (!$result->EOF)
                    {
                            $fact[]=$result->GetRowAssoc($ToUpper = false);
                            $result->MoveNext();
                    }
            }

      if ($_SESSION['LTRABAJOAPOYOD']['TIPO_PRESENTACION'] == '2')
      {
                    for($j=0;$j<sizeof($fact); $j++)
                    {
                            $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica'][$j]['tecnica_id']= $fact[$j]['tecnica_id'];
                            $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica'][$j]['nombre_tecnica']= $fact[$j]['nombre_tecnica'];
                            if($j==0)
                            {
                                    $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']= $fact[$j]['tecnica_id'];
                            }
                    }
            }
            return $fact;
    }


  function ConsultaComponentesExamen($cargo, $tecnica_id, $sexo_id , $edad, $k, $tipo_id_paciente, $paciente_id, $indice)
    {
            list($dbconnect) = GetDBconn();
            $query = "SELECT  a.cargo, a.tecnica_id, a.lab_examen_id,
                            a.lab_plantilla_id, a.nombre_examen, a.indice_de_orden,
                            b.sexo_id, b.rango_min, b.rango_max,
                            b.edad_min, b.edad_max, b.unidades as unidades_1,
                            c.opcion,   c.unidades as unidades_2,   d.detalle
                            FROM lab_examenes a left join lab_plantilla1 b on
                            (a.cargo = b.cargo and a.tecnica_id = b.tecnica_id and
                            a.lab_examen_id = b.lab_examen_id and (b.sexo_id = '".$sexo_id."' OR
                            b.sexo_id isNULL OR b.sexo_id = '0')
                            and (".$edad." >= b.edad_min OR b.edad_min isNULL OR b.edad_min = 0)
                            and (".$edad." <= b.edad_max OR b.edad_max isNULL OR b.edad_max = 0))
                            left join lab_plantilla2 c on (a.cargo = c.cargo and a.tecnica_id = c.tecnica_id
                            and a.lab_examen_id = c.lab_examen_id)
                            left join lab_plantilla3 d  on (a.cargo = d.cargo and a.tecnica_id = d.tecnica_id
                            and a.lab_examen_id = d.lab_examen_id)
                            WHERE a.cargo='".$cargo."' and a.tecnica_id = ".$tecnica_id."
                            order by a.indice_de_orden";


            $result = $dbconnect->Execute($query);
            if ($dbconnect->ErrorNo() != 0)
            {
                    $this->error = "Error al consultar los componentes del examen";
                    $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
                    return false;
            }
            else
            {
                    while (!$result->EOF)
                    {
                            $fact[]=$result->GetRowAssoc($ToUpper = false);
                            $result->MoveNext();
                    }
            }


            if (($_SESSION['LTRABAJOAPOYOD']['TIPO_PRESENTACION'] == '2'))
      {
                    if($indice == $k OR empty($indice))
                    {
              if($_SESSION['CONSTRUCTOR_REQUEST']!=1)
                            {
                                    for($i=0;$i<sizeof($fact); $i++)
                                    {
                                            $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['lab_examen_id']=$fact[$i]['lab_examen_id'];
                                            $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['nombre_examen']=$fact[$i]['nombre_examen'];
                                            $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['lab_plantilla_id']=$fact[$i]['lab_plantilla_id'];
                                            $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['rango_min']=$fact[$i]['rango_min'];
                                            $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['sexo_id']=$fact[$i]['sexo_id'];
                                            $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['rango_max']=$fact[$i]['rango_max'];
                                            $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['edad_min']=$fact[$i]['edad_min'];
                                            $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['edad_max']=$fact[$i]['edad_max'];
                                            $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['unidades_1']=$fact[$i]['unidades_1'];
                                            $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['unidades_2']=$fact[$i]['unidades_2'];
                                            $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['opcion']=$fact[$i]['opcion'];
                                            $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['detalle']=$fact[$i]['detalle'];
                                    }
                            }
                    }
            }
            return $fact;
    }



  function Insertar_Resultado($tipo_id_paciente, $paciente_id, $nombre, $servicio, $numero_cumplimiento, $fecha_cumplimiento)
  {
		//echo "Insertar_Resultado<br>";
		//  print_r($_SESSION);echo "<br>-------------<br>";
		//print_r($_REQUEST);
		//exit();
		unset($_SESSION['FIRMA']['apoyos']);
		$indice = $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id]['apoyos'];
		for ($k=0; $k<$indice; $k++)
		{
			if ((!empty ($_REQUEST['insertar_resultado'.$k]))   OR (!empty ($_REQUEST['insertar_todos'])))
			{
				$fecha = $_REQUEST['fecha_realizado'.$k];
				if($fecha == '')
				{
					$this->frmError['fecha_realizado'.$k]=1;
					$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS, SELECCIONE UNA FECHA.";
					return false;
				}
				else
				{
					$cad=explode ('-',$fecha);
					$dia = $cad[0];
					$mes = $cad[1];
					$ano = $cad[2];
					$fecha=$cad[2].'-'.$cad[1].'-'.$cad[0];
					$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['fecha_realizado_formateada'] = $fecha;
					if (date("Y-m-d",mktime(0,0,0,$mes,$dia,$ano)) > date("Y-m-d",mktime(0,0,0,date('m'),date('d'),date('Y'))))
					{
						$this->frmError['fecha_realizado'.$k]=1;
						$this->frmError["MensajeError"]="FECHA INVALIDA, SELECCIONE UNA FECHA INFERIOR O IGUAL A LA ACTUAL .";
						return false;
					}
				}
				
				if (!$_REQUEST['items'.$k])
				{
					$this->frmError["MensajeError"]="CAMPOS DE RESULTADO INEXISTENTES.";
					return false;
				}

				$subindice = $_REQUEST['items'.$k];
				for ($i=0; $i<$subindice; $i++)
				{
					$cadena = $this->ReemplazarCaracteres($_REQUEST['resultado'.$k.$i]);
					if (trim($cadena) === '' or $_REQUEST['resultado'.$k.$i] == -1)
					{
						$this->frmError['resultado'.$k.$i]=1;
						$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS, DEBE ESCRIBIR UN RESULTADO.";
						return false;
					}
				}
			}
		}
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();

		$cont=0;
		for ($k=0; $k<$indice; $k++)
		{
			if ((!empty ($_REQUEST['insertar_resultado'.$k]))   OR (!empty ($_REQUEST['insertar_todos'])))
			{
							//OBTENER EL TIPO DE RESULTADO - CAMBIO GENERADO PARA INSERTAR RESULTADOS DE PNQ
							$query= "SELECT c.apoyod_tipo_id as tipo_resultadoapd,
															d.grupo_tipo_cargo as tipo_resultadonqx
															FROM cups a, grupos_noqx_apoyod b left join apoyod_tipos c
															on (b.grupo_tipo_cargo = c.apoyod_tipo_id) left join
															no_qx_grupos_tipo_cargo d on (b.grupo_tipo_cargo = d.grupo_tipo_cargo)
															WHERE a.cargo = '".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['cargo']."'
															AND a.grupo_tipo_cargo = b.grupo_tipo_cargo";
							$result=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
											$this->error = "Error al Consultar el tipo de resultado para el examen";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											return false;
							}
							$tipo_resultado=$result->GetRowAssoc($ToUpper = false);
							if ($tipo_resultado)
							{
											if ($tipo_resultado[tipo_resultadoapd]!= NULL OR $tipo_resultado[tipo_resultadoapd]!= '')
											{
															$os_tipo_resultado = ModuloGetVar('','','TipoSolicitudApoyod');
											}
											else
											{
															if ($tipo_resultado[tipo_resultadonqx]!= NULL OR $tipo_resultado[tipo_resultadonqx]!= '')
															{
																	$os_tipo_resultado = 'PNQ';
															}
															else
															{
																	$this->frmError["MensajeError"]="ESTE EXAMEN NO TIENE ASOCIADO UN TIPO DE RESULTADO.";
																	return false;
															}
											}
							}
							else
							{
											$this->frmError["MensajeError"]="ESTE EXAMEN NO TIENE ASOCIADO UN TIPO DE RESULTADO.";
											return false;
							}
							//FIN

							//realiza el id manual de la tabla
							$query="SELECT nextval('hc_resultados_resultado_id_seq')";
							$result=$dbconn->Execute($query);
							$resultado_id=$result->fields[0];
							//fin de la operacion

							$query="INSERT INTO hc_resultados (resultado_id,
															cargo, tecnica_id,
															fecha_registro, usuario_id, tipo_id_paciente, paciente_id,
															fecha_realizado,
															os_tipo_resultado,
															observacion_prestacion_servicio, sw_modo_resultado)

															VALUES(".$resultado_id.",
															'".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['cargo']."',
															".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada'].",
															now(), ".UserGetUID().", '".$tipo_id_paciente."','".$paciente_id."',
															'".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['fecha_realizado_formateada']."',
															'".$os_tipo_resultado."',
															'".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['observacion']."', '1')";

							$resulta1=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
											$this->error = "Error al insertar en hc_resultados";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											$dbconn->RollbackTrans();
											return false;
							}
							else
							{
		//                                              $query="INSERT INTO hc_resultados_sistema (resultado_id, numero_orden_id)
		//                                              VALUES  (".$resultado_id.",
		//                                                                  ".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['numero_orden_id'].")";
									//MauroB
									//echo "<br>kl->>>>>".$k;
									if(empty($_SESSION['LTRABAJOAPOYOD']['PROFESIONAL']))//no es profesional
									{
											if(!empty($_REQUEST['responsable'.$k]))
											{
													$responsable=$_REQUEST['responsable'.$k];
											}else
											{
													$responsable=$_SESSION['LISTA']['APOYO']['responsable'];
											}
									}else
									{
											$responsable = UserGetUID();
									}
							$query="INSERT INTO hc_resultados_sistema (resultado_id, numero_orden_id,usuario_id_profesional)
																	VALUES  (".$resultado_id.",
																											".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['numero_orden_id'].",
																											".$responsable.")";
							//fin MauroB


									$resulta3=$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0)
									{
													$this->error = "Error al insertar en hc_resultados_sistema";
													$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
													echo "Error al insertar en hc_resultados_sistema";
													$this->frmError["MensajeError"]="DEBE SELECCIONAR RESPONSABLE DEL DIAGNOSTICO";
													$dbconn->RollbackTrans();
													return false;
									}
									else
									{
													$subindice = $_REQUEST['items'.$k];
													for ($i=0; $i<$subindice; $i++)
													{
																	$lab_examen='lab_examen'.$k.$i;
																	$res='resultado'.$k.$i;
																	if ($_REQUEST['sw_patologico'.$k.$i]!='')
																	{
																					$sw_alerta = '1';
																	}
																	else
																	{
																					if ((($_REQUEST['rmin'.$k.$i]) != '') and (($_REQUEST['rmax'.$k.$i]) != ''))
																					{
																									if (($_REQUEST[$res]>= $_REQUEST['rmin'.$k.$i]) and ($_REQUEST[$res] <= $_REQUEST['rmax'.$k.$i]))
																									{
																													$sw_alerta = '0';
																									}
																									else
																									{
																													$sw_alerta = '1';
																									}
																					}
																					else
																					{
																									$sw_alerta = '0';
																					}
																	}
																	if ($_REQUEST['rmin'.$k.$i] == 'NULL'){$_REQUEST['rmin'.$k.$i]='';}
																	if ($_REQUEST['rmax'.$k.$i] == 'NULL'){$_REQUEST['rmax'.$k.$i]='';}
																	if ($_REQUEST['unidades'.$k.$i] == 'NULL'){$_REQUEST['unidades'.$k.$i]='';}

																	$query="INSERT INTO hc_apoyod_resultados_detalles
																	(cargo, tecnica_id, lab_examen_id, resultado_id, resultado,
																	sw_alerta, rango_min, rango_max, unidades)
																	VALUES  (
																	'".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['cargo']."',
																	".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada'].",
																	".$_REQUEST[$lab_examen].",".$resultado_id.",'".$_REQUEST[$res]."', '".$sw_alerta."',
																	'".$_REQUEST['rmin'.$k.$i]."',  '".$_REQUEST['rmax'.$k.$i]."',  '".$_REQUEST['unidades'.$k.$i]."')";

																	$resulta4=$dbconn->Execute($query);
																	if ($dbconn->ErrorNo() != 0)
																	{
																					$this->error = "Error al insertar en hc_apoyod_resultados_detalles";
																					$this->frmError["MensajeError"]="ERROR AL INSERTAR EL RESULTADO EN hc_apoyod_resultados_detalles.";
																					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																					$dbconn->RollbackTrans();
																					return false;
																	}
		//                                              //Mauro
		//
		//                                                  if(empty($_SESSION['LTRABAJOAPOYOD']['PROFESIONAL']))//no es profesional
		//                                                  {
		//                                                      if(!empty($_REQUEST['responsable'.$k.$i]))
		//                                                      {
		//                                                          $responsable=$_REQUEST['responsable'.$k.$i];
		//                                                      }else
		//                                                      {
		//                                                          $responsable=$_SESSION['LISTA']['APOYO']['responsable'];
		//                                                      }
		//                                                  }else
		//                                                  {
		//                                                      $responsable = UserGetUID();
		//                                                  }
		//                                               $query="UPDATE hc_resultados_sistema
		//                                                              SET usuario_id_profesional = '".$responsable."'
		//                                                              WHERE resultado_id = ".$resultado_id."
		//                                                                          AND numero_orden_id = ".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['numero_orden_id']."
		//                                                                          ";
		//                                              $resul_usu=$dbconn->Execute($query);
		//                                                  if ($dbconn->ErrorNo() != 0)
		//                                              {
		//                                                      $this->error = "Error al actualizar en hc_resultados_sistema";
		//                                                      $this->frmError["MensajeError"]=".Error al actualizar en hc_resultados_sistema";
		//                                                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
		//                                                      $dbconn->RollbackTrans();
		//                                                      return false;
		//                                              }
		//                                              //Fin MauroB
													}
									}
							}
							if ($_REQUEST['firma'.$k])
							{
									 $_SESSION['LTA_FIRMA_INS']['RESULTADO_ID'][$cont]=$resultado_id;
									 $cont = $cont + 1;
							}
			}
		}

		$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
		$dbconn->CommitTrans();
		$_SESSION['FIRMA']['apoyos']=$cont;
		return true;
	}


    function GetInfoExamen($cargo)
    {
            list($dbconn) = GetDBconn();
            $query="SELECT case when (a.titulo_examen = '' or a.titulo_examen ISNULL)
            then b.descripcion else a.titulo_examen end as titulo, a.informacion
            FROM apoyod_cargos a, cups b
            WHERE a.cargo = b.cargo and a.cargo = '".$cargo."'";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                    $this->error = "Error al consultar los datos del examen";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            if (!$result->EOF)
            {
                    $datos=$result->GetRowAssoc($ToUpper = false);
                    $result->Close();
            }
            return $datos;
    }


    function CrearGenerico($cargo, $titulo)
    {
            list($dbconn) = GetDBconn();
            $dbconn->BeginTrans();
            $query="SELECT COUNT(*) FROM apoyod_cargos_tecnicas
            WHERE cargo = '".$cargo."' and tecnica_id = 1";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                    $this->error = "Error en la consulta de apoyod_cargos_tecnicas";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            list($var_existe_apoyo_tecnica)=$result->FetchRow();

            if ($var_existe_apoyo_tecnica == 0)
            {
                    $query="SELECT COUNT(*) FROM apoyod_cargos
                    WHERE cargo = '".$cargo."'";
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                            $this->error = "Error en la consulta del Pagador";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            return false;
                    }
                    list($var_existe_apoyo)=$result->FetchRow();
                    if ($var_existe_apoyo == 0)
                    {
                            $query="INSERT INTO apoyod_cargos
                            (cargo,titulo_examen, sexo_id, apoyod_tipo_id)
                            VALUES  ('".$cargo."', '".$titulo."', 0,
                            (SELECT grupo_tipo_cargo FROM cups WHERE cargo = '".$cargo."'))";

                            $result=$dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0)
                            {
                                    $this->error = "Error al insertar en apoyod_cargos";
                                    $this->frmError["MensajeError"]="Error al insertar en apoyod_cargos.";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    $dbconn->RollbackTrans();
                                    return false;
                            }

                            $query="INSERT INTO apoyod_cargos_tecnicas
                            (tecnica_id, cargo, nombre_tecnica, sw_predeterminado)
                            VALUES  (1, '".$cargo."', 'Tecnica Generica', 0)";

                            $result=$dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0)
                            {
                                    $this->error = "Error al insertar en apoyod_cargos_tecnicas";
                                    $this->frmError["MensajeError"]="Error al insertar en apoyod_cargos_tecnicas.";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    $dbconn->RollbackTrans();
                                    return false;
                            }
                    }
                    else
                    {
                            $query="INSERT INTO apoyod_cargos_tecnicas
                            (tecnica_id, cargo, nombre_tecnica, sw_predeterminado)
                            VALUES  (1, '".$cargo."', 'Tecnica Generica', 0)";

                            $result=$dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0)
                            {
                                    $this->error = "Error al insertar en apoyod_cargos_tecnicas";
                                    $this->frmError["MensajeError"]="Error al insertar en apoyod_cargos_tecnicas.";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    $dbconn->RollbackTrans();
                                    return false;
                            }
                    }
            }
            $query="SELECT COUNT(*) FROM lab_examenes
            WHERE tecnica_id = 1 and cargo = '".$cargo."' and
            lab_examen_id = 0";
            $result = $dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0)
            {
                    $this->error = "Error en la consulta del lab_examenes";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            list($var_existe_lab_examen)=$result->FetchRow();
            if ($var_existe_lab_examen == 0)
            {
                    $query="INSERT INTO lab_examenes
                    (tecnica_id, cargo, lab_examen_id, lab_plantilla_id, nombre_examen)
                    VALUES  (1, '".$cargo."', 0, 0, 'GENERICO')";

                    $result=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                            $this->error = "Error al insertar en lab_examenes";
                            $this->frmError["MensajeError"]="Error al insertar en lab_examenes.";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                    }
            }
            $dbconn->CommitTrans();
            return true;
    }

//---------------------------------------------------
                        //funciones que estan siendo llamadas por lineas comentadas
    function ImprimirApoyoDiagnostico()
    {
            $var=$this->ReporteResultadoApoyod();
            if (!IncludeFile("classes/reports/reports.class.php"))
            {
                    $this->error = "No se pudo inicializar la Clase de Reportes";
                    $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
                    return false;
            }
            $classReport = new reports;
            $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pdf');
            $reporte=$classReport->PrintReport($tipo_reporte='pdf',$tipo_modulo='app',$modulo='Os_Listas_Trabajo_Apoyod_Agrupado',$reporte_name='examenes',$var,$impresora,$orientacion='P',$unidades='mm',$formato='letter',$html=1);

            /*
            $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pdf');
            $reporte=$classReport->PrintReport($tipo_reporte='pdf',$tipo_modulo='app',$modulo='Os_Listas_Trabajo_Apoyod_Agrupado',$reporte_name='examenes',$var,$impresora='starPC16',$orientacion='P',$unidades='mm',$formato='letter',$html=1);
            */

            if(!$reporte)
            {
                    $this->error = $classReport->GetError();
                    $this->mensajeDeError = $classReport->MensajeDeError();
                    UNSET($classReport);
                    return false;
            }
            $resultado=$classReport->GetExecResultado();
            UNSET($classReport);
            $this->BuscarOrden();
            return true;
    }


function ReporteResultadoApoyod()
{
        list($dbconnect) = GetDBconn();
        $query="select a.paciente_id, a.tipo_id_paciente, a.resultado_id, a.fecha_realizado,
        a.usuario_id, l.nombre_tercero, a.observacion_prestacion_servicio,
        case when (g.titulo_examen = '' or g.titulo_examen ISNULL) then h.descripcion
        else g.titulo_examen end as titulo, g.informacion, f.razon_social as laboratorio,
        f.tipo_id_tercero, f.id,

        btrim(n.primer_nombre||' '||n.segundo_nombre||' '||
        n.primer_apellido||' '||n.segundo_apellido,'') as nombre, n.sexo_id as sexo_paciente
        FROM hc_resultados as a left join pacientes n on (a.paciente_id = n.paciente_id
        AND a.tipo_id_paciente= n.tipo_id_paciente),
        apoyod_cargos as g, cups as h, hc_resultados_sistema as b,
        os_maestro as c left join os_internas as d on(c.numero_orden_id=d.numero_orden_id)
        left join departamentos as e on(d.departamento=e.departamento) left join empresas
        as f on(e.empresa_id=f.empresa_id), profesionales_usuarios as k,
        terceros as l where a.resultado_id = b.resultado_id and
        a.resultado_id = ".$_REQUEST['resultado_id']." and b.numero_orden_id=c.numero_orden_id
        and a.cargo = g.cargo and g.cargo = h.cargo and b.usuario_id_profesional = k.usuario_id
        and k.tipo_tercero_id = l.tipo_id_tercero and k.tercero_id = l.tercero_id";

        $result = $dbconnect->Execute($query);
        if ($dbconnect->ErrorNo() != 0)
        {
                $this->error = "Error al Consultar los datos del examen";
                $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
                return false;
        }
        $a=$result->GetRowAssoc($ToUpper = false);

        //meter plantilla
        $query= "SELECT DISTINCT
                        a.lab_examen_id, a.resultado_id, a.resultado,
                        a.sw_alerta, b.lab_plantilla_id, b.nombre_examen,
                        b.unidades, c.rango_max,
                        c.rango_min, c.sexo_id
                        FROM lab_examenes b, hc_apoyod_resultados_detalles a
                        left join lab_plantilla1 c on (a.lab_examen_id = c.lab_examen_id)
                        left join lab_plantilla2 as d on    (a.lab_examen_id = d.lab_examen_id)

                        left join lab_plantilla3 as e on (a.lab_examen_id = e.lab_examen_id)

                        WHERE  a.resultado_id = ".$_REQUEST['resultado_id']." AND a.lab_examen_id=b.lab_examen_id";

        $result = $dbconnect->Execute($query);
        if ($dbconnect->ErrorNo() != 0)
        {
                $this->error = "Error al consultar los resultados de los examenes";
                $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
                return false;
        }
        else
        {
                while (!$result->EOF)
                {
                    $vector[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                }
        }
        $a[detalle]=$vector;

        //IncludeLib("reportes/examenes");
        //GenerarExamen($a);
        //si esta variable de session esta en 1 es por que saldra al otro lado
        //$_SESSION['LISTA']['APOYOD']['SW']=$_REQUEST['resultado_id'];
        //$this->FormaMetodoBuscar($_SESSION['VECTOR DE BUSQUEDA']);
        //return true;
        return $a;
    }


    //NUEVAS FIUNCIONES MAURICIO Y LORENA ESTAN OK
    function ProfesionalesDepartamento()
    {
            list($dbconn) = GetDBconn();
            $query="SELECT c.usuario_id,a.tipo_id_tercero||' '||a.tercero_id as identificacion,
            nombre_tercero as nombre
            FROM profesionales_departamentos a,terceros b,profesionales_usuarios c
            WHERE a.departamento='".$_SESSION['LTRABAJOAPOYOD']['DPTO']."'
            AND a.tipo_id_tercero=b.tipo_id_tercero AND a.tercero_id=b.tercero_id
            AND b.tipo_id_tercero=c.tipo_tercero_id AND a.tercero_id=c.tercero_id";
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al consultar los profesionales del departamento";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            else
            {
                while(!$result->EOF)
                {
                    $vector[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                }
                $result->Close();
            }
            return $vector;
  }

    function BuscaProfesionalCumplimiento($orden)
    {
            list($dbconn) = GetDBconn();
            $query="SELECT a.usuario_id
            FROM os_cumplimientos_detalle a
            WHERE a.numero_orden_id=".$orden." AND a.usuario_id IS NOT NULL";
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al consultar la tabla os_cumplimientos_detalle";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            else
            {
                if($result->RecordCount()>0)
                {
                    $vector=$result->GetRowAssoc($ToUpper = false);
                    $result->Close();
                }
            }
            return $vector;
    }

    function BuscaProfesionalResultado($orden)
    {
            list($dbconn) = GetDBconn();
            $query="SELECT usuario_id_profesional as usuario_id
            FROM hc_resultados_sistema a
            WHERE a.numero_orden_id=".$orden." AND usuario_id_profesional IS NOT NULL";
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al consultar la tabla hc_resultados_sistema";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            else
            {
                if($result->RecordCount()>0)
                {
                    $vector=$result->GetRowAssoc($ToUpper = false);
                    $result->Close();
                }
            }
            return $vector;
    }

  //revisra para pasra a la de alex
    function ConsultaNombreProfesional($usuario_profesional)
    {
            list($dbconnect) = GetDBconn();
            $query="select b.nombre_tercero from profesionales_usuarios as a, terceros as b where a.usuario_id = ".$usuario_profesional." and a.tipo_tercero_id = b.tipo_id_tercero and a.tercero_id = b.tercero_id";
            $result = $dbconnect->Execute($query);
            if ($dbconnect->ErrorNo() != 0){
                    $this->error = "Error al Consultar los datos del examen";
                    $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
                    return false;
            }

            $a=$result->GetRowAssoc($ToUpper = false);
            return $a;
    }

    //MauroB
        /**
        * Concatena el numero_cumplimiento y la fecha_cumplimiento para crear le numero
        *  de cumplimiento que debe ser visto por el medico y que servira de guia para
        * el controld e los examenes, en el formato que se configuro en la tabla departamentos
        *  para cada uno d elos departamentos existentes en la empresa
        * @param $fecha_cumplimiento
        * @param $numero_cumplimiento
        * @param $departamento
        * @return $cumplimiento
        */
        function ConvierteCumplimiento($fecha_cumplimiento,$numero_cumplimiento,$departamento){
            list($dbconn) = GetDBconn();
            $query="SELECT  formato_cumplimiento
                            FROM        departamentos
                            WHERE       departamento = '$departamento'";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0){
                    echo "<br>Error BD " . $dbconn->ErrorMsg();
                    return false;
            }
            $res=$result->fields[0];
            $result->Close();
            if($res=='0'){
                $fecha=substr(str_replace("-","",$fecha_cumplimiento),2);
            }elseif($res=='1'){
                $fecha=substr(str_replace("-","",$fecha_cumplimiento),2,-2);
            }elseif($res=='2'){
                $fecha=substr(str_replace("-","",$fecha_cumplimiento),2,-4);
            }
//          echo "<br>-->".$fecha;exit;
            $cumplimiento=$fecha."-".$numero_cumplimiento;
            return $cumplimiento;
        }//fin ConvierteCumplimiento

        /**
    * Caso de eliminacion de un examen de la lista de examenes preseleccionados para ser enviados a la Vitros.
    * los libera para ser trabajados en otra ubicacion en la maquina o manalmente
    * @access public
    * @return null
    */
    function EliminaExamenListaVitros($resultado_id){
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query="SELECT a.interface_vitros_control_examen_id,
                                        a.muestra_id,
                                        a.numero_orden_id
                        FROM  interface_vitros_control_examen_detalle a
                        WHERE a.resultado_id = $resultado_id
                        ";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0){
                $this->error = "Error al eliminar interface_vitros_control_examen 1";
                echo "Error al eliminar interface_vitros_control_examen 1";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
        }
        $control_id=$result->fields[0];
        $muestra_id=$result->fields[1];
        $numero_orden_id=$result->fields[2];
        if(($control_id!=NULL)||(!empty($control_id))) {
            $query="DELETE FROM  interface_vitros_control_examen_detalle
                            WHERE numero_orden_id=$numero_orden_id AND
                                        interface_vitros_control_examen_id = $control_id AND
                                        resultado_id = $resultado_id
                            ";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0){
                    $this->error = "Error al eliminar en interface_vitros_control_examen_detalle 1";
                    echo "Error al eliminar en interface_vitros_control_examen_detalle 1";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
            }

            $query="SELECT COUNT (numero_orden_id)
                            FROM interface_vitros_control_examen_detalle
                            WHERE interface_vitros_control_examen_id = $control_id";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0){
                    $this->error = "Error al contar en interface_vitros_control_examen_detalle 1";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            $cont=$result->fields[0];

            if($cont==0){
                $query="DELETE FROM  interface_vitros_control_examen
                                WHERE interface_vitros_control_examen_id = $control_id
                                ";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0){
                        $this->error = "Error al eliminar en interface_vitros_control_examen 2 ";
                        echo "Error al eliminar en interface_vitros_control_examen 2 ";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                }
            }
        }
            $dbconn->CommitTrans();
            $result->Close();
        return true;
    }//fin function EliminaExamenListaVitros

    /**
    * Se encarga de verificar si un determiando examen puede ser leido sin necesidad de la firma
    * desde la Hc. Este permiso depende si el departamento esta ono autorizado para dejarlo mostrar
    * @param $cargo cargo a analizar
    * @param $depto departamento del permiso
    * @return estado del swiche o falso si no puede
    */
    function ConsultaPermisoLecturaSinFirma($cargo, $depto){
        list($dbconn) = GetDBconn();
        $query="SELECT  c.sw_consulta_examen_sin_firmar
                        FROM        cups a,
                                        tipos_os_listas_trabajo_detalle b,
                                        tipos_os_listas_trabajo c
                        WHERE       a.cargo = '$cargo' AND
                                        a.tipo_cargo =  b.tipo_cargo  AND
                                        a.grupo_tipo_cargo =  b.grupo_tipo_cargo  AND
                                        b.tipo_os_lista_id  =  c.tipo_os_lista_id  AND
                                        c.departamento = '$depto'
                        ";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0){
                $this->error = "Error al consultar sw_consulta_examen_sin_firmar1";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
        }
        $sw=$result->fields[0];
        $result->Close();
        return $sw;
    }

    /**
    *
    */
    function CargaConvenciones($nombre,$cargo,$examen,$tecnica){
//      $vector['0']['0']="[PACIENTE]";
//      $vector['0']['1']=$nombre;
//      $vector['1']['0']="[CARGO]";
//      $vector['1']['1']=$cargo;
//      $vector['2']['0']="[EXAMEN]";
//      $vector['2']['1']=$examen;
//      $vector['3']['0']="[TECNICA]";
//      $vector['3']['1']=$tecnica;

        $vector['[PACIENTE]']=$nombre;
        $vector['[CARGO]']=$cargo;
        $vector['[EXAMEN]']=$examen;
        $vector['[TECNICA]']=$tecnica;
        return $vector;
    }
    //fin MauroB
	/*******************************************************************
	* Funcion que permite reemplazar los caracteres html por cadenas 
	* vacias
	* @params string $cadena Cadena html a modificar
	*	@returns string Cadena html sin caracteres html
	********************************************************************/
	function ReemplazarCaracteres($cadena)
	{
		$busqueda = array ('@<script[^>]*?>.*?</script>@si', // Remover javascript
                 '@<[\/\!]*?[^<>]*?>@si',          // Remover etiquetas HTML
                 '@([\r\n])[\s]+@',                // Remover espacios en blanco
                 '@&(quot|#34);@i',                // Reemplazar entidades HTML
                 '@&(amp|#38);@i',
                 '@&(lt|#60);@i',
                 '@&(gt|#62);@i',
                 '@&(nbsp|#160);@i',
                 '@&(iexcl|#161);@i',
                 '@&(cent|#162);@i',
                 '@&(pound|#163);@i',
                 '@&(copy|#169);@i',
                 '@&#(\d+);@e');                    // evaluar como php

		$reemplazar = array ('','','\1','"','&','<','>',' ',chr(161),chr(162),chr(163),chr(169),'chr(\1)');
		$texto = preg_replace($busqueda, $reemplazar, $cadena);
		return $texto;
	}
	//IMAGENOLOGIA
	function GetEstudiosImagenologia($orden)
	{
		list($dbconn) = GetDBconn();
		$query="SELECT B.*, A.estudio_id, A.admision
		  FROM estudios_pacs A INNER JOIN pacs B ON (B.id_pacs = A.id_pacs)
		  WHERE A.admision = ".$orden;
		// echo $query."<br>";			  
		$result = $dbconn->Execute($query);

		if($dbconn->ErrorNo() != 0)
		{
		  $this->error = "Error al Cargar SQL";
		  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg()."[".get_class($this)."][".__LINE__."]";
		  return false;
		}
		else
		{
		while(!$result->EOF)
		{
		$vars=$result->GetRowAssoc($ToUpper = false);
		$result->MoveNext();
		}
		}
		$dbconn->CommitTrans();
		return $vars;
	}
	//FIN IMAGENOLOGIA
}//fin clase user

?>
