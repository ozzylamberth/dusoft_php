<?php

/**
* $Id: app_Os_Atencion_user.php,v 1.7 2010/03/18 19:03:44 hugo Exp $
* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* @package IPSOFT-SIIS
*
* Modulo para el manejo de ordenes de servicio.
*/

/**
* app_Os_Atencion_user.php 
*
* Clase para procesar los datos del formulario mediante la operaciones de consulta ,captura y de insercion.
* del modulo Os_Atencion se extiende la clase Os_Atencion y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/
class app_Os_Atencion_user extends classModulo
{

    var $dos;
    var $uno;
    var $limit;
    var $conteo;//para saber cuantos registros encontró

    // METODOS ***********************************

    /**
    * Es el contructor de la clase Os_Atencion
    * @return boolean
    */
    function app_Os_Atencion_user()
    {
        $this->limit=GetLimitBrowser();
        return true;
    }


    /**
    * La funcion main es la principal y donde se llama FormaPrincipal
    * que muestra los diferentes tipos de busqueda de una cuenta para hospitalización.
    * @access public
    * @return boolean
    */
    function main()
    {
        if(!$this->BuscarPermisosUser())
        {
            return false;
        }
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

        list($dbconn) = GetDBconn();
        GLOBAL $ADODB_FETCH_MODE;

        $query=" SELECT

                c.departamento,c.descripcion as dpto, d.descripcion as
                centro,e.empresa_id,e.razon_social as emp,d.centro_utilidad,
                b.usuario_id,b.sw_solo_cumplimiento,b.sw_honorario

                FROM
                userpermisos_os_atencion b, departamentos c, centros_utilidad d,empresas e

                WHERE
                b.usuario_id=".UserGetUID()."

                AND
                c.departamento=b.departamento
                AND
                d.centro_utilidad=c.centro_utilidad
                AND
                e.empresa_id=d.empresa_id
                AND
                e.empresa_id=c.empresa_id

                ORDER BY centro";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $resulta = $dbconn->Execute($query);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        while($data = $resulta->FetchRow())
        {
            $laboratorio[$data['emp']][$data['centro']][$data['dpto']]=$data;
        }

        $url[0]='app';
        $url[1]='Os_Atencion';
        $url[2]='user';
        $url[3]='Menuatencion';
        $url[4]='laboratorio';

        $arreglo[0]='EMPRESA';
        $arreglo[1]='CENTRO UTILIDAD';
        $arreglo[2]='ATENCION DE ORDENES DE SERVICIO';
        $accion=ModuloGetURL('system','Menu','user','main');
        $this->salida.= gui_theme_menu_acceso('ATENCION DE ORDENES DE SERVICIO',$arreglo,$laboratorio,$url,$accion);
        return true;
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
    * Realiza la busqueda general de los pacientes que tienen ordenes de servicios pendientes
    * @access private
    * @return array
    */
    function BusquedaCompleta()
    {
        $TipoDoc=$_REQUEST['TipoDocumento'];

        if (!empty($TipoDoc))
        {
            $y=" AND b.tipo_id_paciente='".$TipoDoc."' ";
        }
        else
        {
            $y='';
        }

        $NUM=$_REQUEST['Of'];
        if(!$NUM)
        {
            $NUM='0';
        }

        $limit=$this->limit;

        list($dbconn) = GetDBconn();

        if(!empty($_SESSION['SPY']))
        {
            $x=" LIMIT ".$this->limit." OFFSET $NUM";
        }
        else
        {
            $x='';
        }

        $query="SELECT DISTINCT
                btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||
                b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
                b.tipo_id_paciente,b.paciente_id,c.sw_estado
                ,a.plan_id
                --,a.orden_servicio_id

                FROM pacientes as b,os_ordenes_servicios a,
                os_maestro c,os_internas d

                WHERE
                c.numero_orden_id=d.numero_orden_id
                AND a.orden_servicio_id=c.orden_servicio_id
                AND d.departamento='".$_SESSION['LABORATORIO']['DPTO']."'
                AND
                --(c.sw_estado=1 OR c.sw_estado=2 OR c.sw_estado=3)
                c.sw_estado IN('1','2','3','0')
                AND DATE(c.fecha_activacion) <= DATE(NOW())
                AND DATE(c.fecha_vencimiento) >= DATE(NOW())
                AND a.tipo_id_paciente=b.tipo_id_paciente
                $y
                AND a.paciente_id=b.paciente_id $x";


        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        if ($result->RecordCount() > 0)
        {
            $this->dos=2;
        }
        else
        {
            $this->dos='';
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



    /**
    * Realiza la busqueda según el plan,documento .. de los pacientes que
    * tienen ordenes de servicios pendientes
    * @access private
    * @return boolean
    */
    function BuscarOrden()
    {
		/* echo '<pre>';
		print_r($_REQUEST);
		echo '</pre>'; */
        $Buscar1=$_REQUEST['Busc'];
        $Buscar=$_REQUEST['Buscar'];
        $Busqueda=$_REQUEST['TipoBusqueda'];
        $TipoBuscar=$_REQUEST['TipoBuscar'];
        $arreglo=$_REQUEST['arreglo'];
        $TipoCuenta=$_REQUEST['TipoCuenta'];
        $NUM=$_REQUEST['Of'];
//echo "spy1=".$_SESSION['SPY']."<br>";
        if($Buscar)
        {
            unset($_SESSION['SPY']);
        }
        if(!$Busqueda)
        {
            $new=$TipoBuscar;
        }

        if(!$NUM)
        {
            $NUM='0';
        }

//echo "spy2=".$_SESSION['SPY']."<br>";
        foreach($_REQUEST as $v=>$v1)
        {
            if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID')
            {
                $vec[$v]=$v1;
            }
        }

        $_REQUEST['Of']=$NUM;

        if($Buscar1)
        {
            $this->FormaMetodoBuscar($Busqueda,$arr,$f,$vec);
            return true;
        }

        list($dbconn) = GetDBconn();
        unset($_SESSION['SPY']);


        $TIPO_ID = $_REQUEST['TipoDocumento'];
        $ID = trim($_REQUEST['Documento']);
        $N = strtoupper(trim($_REQUEST['nombres']));
        $A = strtoupper(trim($_REQUEST['apellidos']));
        $PLAN_ID = $_REQUEST['Responsable'];
        $ORDEN_ID=trim($_REQUEST['NumIngreso']);
		
        $FiltrarNombres = TRUE;
        $Filtro = array();
        $FiltrosPaciente = NULL;
        $FiltroOSxPlanID = NULL;
        $FiltroSolicitudesxPlanID = NULL;

        if(empty($ID) && empty($N) && empty($A) && empty($ORDEN_ID))
        {
            $this->frmError["MensajeError"]='DIGITE UN VALOR PARA LA BUSQUEDA .. (Documento, Nombres, Apellidos ó Numero de orden)';
            $this->FormaMetodoBuscar($Busqueda='',$Cuentas,$f=true,$vec);
            return true;
        }

        if(empty($ORDEN_ID))
        {
            if(!empty($ID))
            {
                $IDsimilares = substr_count($ID,"%");
                $ID = str_replace ("%", "", $ID);

                if(!$IDsimilares)
                {
                    $Filtro[] = "paciente_id = '$ID'";
                    $FiltrarNombres = FALSE;
                }
                else
                {
                    $Filtro[] = "paciente_id LIKE '$ID%'";
                }

                if($TIPO_ID != -1)
                {
                    $Filtro[] = "tipo_id_paciente = '$TIPO_ID'";
                }
            }

            foreach($Filtro as $k=>$v)
            {
                $Filtros .= "AND $v\n";
            }

            if($FiltrarNombres)
            {
                $FN = $this->GetFiltroNombres($N,$A);
                foreach($FN as $k=>$v)
                {
                    $Filtros .= "AND $v\n";
                }
            }
            if(!empty($Filtros))
            {
                $FiltrosPaciente = substr_replace ( $Filtros, "", 0 ,4 );
            }
            else
            {
                $this->uno=1;
                $this->frmError["MensajeError"]='LA BÚSQUEDA NO ARROJO RESULTADOS.';
                $this->FormaMetodoBuscar($Busqueda='',$Cuentas,$f=true,$vec);
                return true;
            }

            if($PLAN_ID != -1)
            {
                $FiltroOSxPlanID = "AND a.plan_id = $PLAN_ID";
                $FiltroSolicitudesxPlanID = "AND b.plan_id = $PLAN_ID";
            }
        }

        //$this->Buscar1($ORDEN_ID, $FiltrosPaciente, $FiltroOSxPlanID, $FiltroSolicitudesxPlanID, $NUM);
        $datos = $this->Buscar1($ORDEN_ID, $FiltrosPaciente, $FiltroOSxPlanID, $FiltroSolicitudesxPlanID, $NUM);

        if($datos)
        {
            $this->FormaMetodoBuscar($Busqueda='',$datos,$f=true,$vec);
            return true;
        }
        else
        {
            $this->uno=1;
            $this->frmError["MensajeError"]='LA BÚSQUEDA NO ARROJO RESULTADOS.';
            $this->FormaMetodoBuscar($Busqueda='',$Cuentas,$f=true,$vec);
            return true;
       }
    }//FIN DEL METODO



    function GetFiltroNombres($N,$A)
    {
        $Filtro =array();
        if(!empty($N))
        {
            $Nsimilares = substr_count($N,"%");
            //TODO CON LIKE PORQUE EN LA BASE DE DATOS LOS NOMBRES SE ESTAN GUADANDO CON ESPACIOS.
            $Nsimilares = 1;
            $N = str_replace ("%", " ", $N);

            $N = explode(" ",preg_replace("/\s{2,}/"," ",trim($N)));

            if(count($N)>1)
            {
                if($Nsimilares)
                {
                    $Filtro[] = "(primer_nombre LIKE '%$N[0]%' AND segundo_nombre LIKE '%$N[1]%')";
                }
                else
                {
                    $Filtro[] = "(primer_nombre = '$N[0]' AND segundo_nombre = '$N[1]')";
                }
            }
            else
            {
                if(!empty($N[0]))
                {
                    if($Nsimilares)
                    {
                        $Filtro[] = "(primer_nombre LIKE '%$N[0]%' OR segundo_nombre LIKE '%$N[0]%')";
                    }
                    else
                    {
                        $Filtro[] = "(primer_nombre = '$N[0]' OR segundo_nombre = '$N[0]')";
                    }
                }
            }
        }
		
		
        if(!empty($A))
        {
            $Asimilares = substr_count($A,"%");
            //TODO CON LIKE PORQUE EN LA BASE DE DATOS LOS NOMBRES SE ESTAN GUADANDO CON ESPACIOS.
            $Asimilares = 1;
            $A = str_replace ("%", " ", $A);

            $A = explode(" ",preg_replace("/\s{2,}/"," ",trim($A)));
            if(count($A)>1)
            {
                if($Asimilares)
                {
                    $Filtro[] = "(primer_apellido LIKE '%$A[0]%' AND segundo_apellido LIKE '%$A[1]%')";
                }
                else
                {
                    $Filtro[] = "(primer_apellido = '$A[0]' AND segundo_apellido = '$A[1]')";
                }
            }
            else
            {
                if(!empty($A[0]))
                {
                    if($Asimilares)
                    {
                        $Filtro[] = "(primer_apellido LIKE '%$A[0]%' OR segundo_apellido LIKE '%$A[0]%')";
                    }
                    else
                    {
                        $Filtro[] = "(primer_apellido = '$A[0]' OR segundo_apellido = '$A[0]')";
                    }
                }
            }
        }
        return $Filtro;
    }


    /**
    * Menuatencion, esta funcion  es donde desemboca el menu iteractivo creado por arley.
    * @access public
    * @return boolean
    */
    function Menuatencion()
    {
        $_SESSION['LABORATORIO']['EMPRESA_ID']=$_REQUEST['laboratorio']['empresa_id'];
        $_SESSION['LABORATORIO']['CENTROUTILIDAD']=$_REQUEST['laboratorio']['centro_utilidad'];
        $_SESSION['LABORATORIO']['NOM_CENTRO']=$_REQUEST['laboratorio']['centro'];
        $_SESSION['LABORATORIO']['NOM_EMP']=$_REQUEST['laboratorio']['emp'];
        $_SESSION['LABORATORIO']['NOM_DPTO']=$_REQUEST['laboratorio']['dpto'];
        $_SESSION['LABORATORIO']['DPTO']=$_REQUEST['laboratorio']['departamento'];

        //parte experimental de claudi si esta en 1 es por q es imagenologia
        //debe mostrar los honorarios.
        $_SESSION['LABORATORIO']['SW_HONORARIO']=$_REQUEST['laboratorio']['sw_honorario'];

        //si viene en 1 es por q solo muestra las q hayb q cumplir
        //si es 0 muestra que se puede pagar
        $_SESSION['LABORATORIO']['SW_ESTADO']=$_REQUEST['laboratorio']['sw_solo_cumplimiento'];

        $_SESSION['OS_ATENCION']['CARGARFILTRO']=TRUE;

        if(!$this->FormaMetodoBuscar())
        {
            return false;
        }
        return true;
    }


    /**
    * funcion que trae lo nombres de los medicos especialistas amarrados a los departamentos
    */
    function ComboProfesionales()
    {
        list($dbconn) = GetDBconn();


        $query="SELECT DISTINCT x.usuario_id,c.nombre,c.tipo_id_tercero,c.tercero_id

                    FROM profesionales_departamentos a, tipos_profesionales b,profesionales c
                    ,profesionales_usuarios x

                    WHERE a.departamento='".$_SESSION['LABORATORIO']['DPTO']."'
                    --AND b.tipo_profesional=6
                    AND a.tipo_id_tercero=c.tipo_id_tercero
                    AND a.tercero_id=c.tercero_id
                    AND x.tercero_id=c.tercero_id
                    AND x.tipo_tercero_id=c.tipo_id_tercero";


        $resulta=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al listar las empresas";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        $i=0;

        while (!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }


    /**
    *
    */
    function BuscarNombreCop($plan)
    {
        list($dbconn) = GetDBconn();
        $query="SELECT nombre_copago,nombre_cuota_moderadora,tipo_liquidacion_cargo
                    FROM planes
                            WHERE plan_id=$plan
                            ";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
                $this->error = "Error al traer los planes";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
        }
        $var=$result->GetRowAssoc($ToUpper = false);
        return $var;
    }


    /**
    *
    */
    function AgregarCargo()
    {
        $_SESSION['CUENTAS']['OS']['plan_id']=$_REQUEST['Plan'];
        $_SESSION['CUENTAS']['OS']['cuenta']=$_REQUEST['Cuenta'];
        $_SESSION['CUENTAS']['OS']['arreglo']=$_REQUEST['Datos'];
        $_SESSION['CUENTAS']['OS']['RETORNO']['contenedor']='app';
        $_SESSION['CUENTAS']['OS']['RETORNO']['modulo']='Os_Atencion';
        $_SESSION['CUENTAS']['OS']['RETORNO']['tipo']='user';
        $_SESSION['CUENTAS']['OS']['RETORNO']['metodo']='FormaMetodoBuscar';

        $this->ReturnMetodoExterno('app','Facturacion','user','');
        return true;
    }



    /**
    * funcion que se comunica con el moc de creacion de pacientes de darling
    * funciona cuando se solicita por ejemplo un examen de sangre..algo que va a ser
    * momentaneo
    */
    function CreacionPacientes()
    {
        $_SESSION['CAJARAPIDA']['EXT']['RETORNO']['contenedor']='app';
        $_SESSION['CAJARAPIDA']['EXT']['RETORNO']['modulo']='Os_Atencion';
        $_SESSION['CAJARAPIDA']['EXT']['RETORNO']['tipo']='user';
        $_SESSION['CAJARAPIDA']['EXT']['RETORNO']['metodo']='FormaMetodoBuscar';
        $_SESSION['CAJARAPIDA']['EXT']['RETORNO']['argumentos']=array();

        $_SESSION['CAJARAPIDA']['EXT']['PACIENTE']['EMPRESA']=$_SESSION['LABORATORIO']['EMPRESA_ID'];
        $_SESSION['CAJARAPIDA']['EXT']['PACIENTE']['DPTO']=$_SESSION['LABORATORIO']['DPTO'];
        $_SESSION['CAJARAPIDA']['EXT']['PACIENTE']['SERVICIO']='';
        $_SESSION['CAJARAPIDA']['EXT']['PACIENTE']['DPTONOMBRE']=$_SESSION['LABORATORIO']['NOM_DPTO'];
        $_SESSION['CAJARAPIDA']['EXT']['PACIENTE']['CAJAID']='';

        $_SESSION['CAJARAPIDA']['EXT']['PACIENTE']['tipo_id_paciente']=$_REQUEST['tipod'];
        $_SESSION['CAJARAPIDA']['EXT']['PACIENTE']['paciente_id']=$_REQUEST['doc'];
        $this->ReturnMetodoExterno('app','CajaRapida','user','LlamarformaBuscarExt');
        return true;
    }





    /**
    * Esta es la funcion que verifica si el paciente tiene una autorizacion previa
    */
    function RevisarAuto()
    {
        list($dbconn) = GetDBconn();
        unset($_SESSION['AUTORIZACIONES']);
        $nom=urldecode($_REQUEST['nombre']);
        $tipo=$_REQUEST['tipoid'];
        $id=$_REQUEST['idp'];
        $plan=$_REQUEST['plan_id'];

        $query="SELECT DISTINCT
            a.orden_servicio_id

            FROM os_ordenes_servicios a,
            os_maestro c,os_internas d

            WHERE
            c.numero_orden_id=d.numero_orden_id
            AND a.orden_servicio_id=c.orden_servicio_id
            AND d.departamento='".$_SESSION['LABORATORIO']['DPTO']."'
            AND a.tipo_id_paciente='$tipo'
            AND a.paciente_id='$id'
            AND c.sw_estado  IN('1','2','3','0')
            AND DATE(c.fecha_activacion) <= DATE(NOW())";

        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al traer ordenes de servicios";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $ordenS=$result->fields[0];

        //ultima modificacion de esta parte...16 nov 2004.
        if(!empty($result->fields[0]))
        {
            $query="SELECT a.sw_afiliacion,b.orden_servicio_id,b.tipo_afiliado_id,b.rango,b.semanas_cotizadas
                    FROM planes a,os_ordenes_servicios b
                    WHERE a.plan_id=$plan
                    AND a.plan_id=b.plan_id
                    AND b.orden_servicio_id=".$result->fields[0].";";

            $result = $dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al traer los planes";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            $var=$result->GetRowAssoc($ToUpper = false);
        }

        if($var[sw_afiliacion]==1)
        {
            $_SESSION['AUTORIZACIONES']['RETORNO']['contenedor']='app';
            $_SESSION['AUTORIZACIONES']['RETORNO']['modulo']='Os_Atencion';
            $_SESSION['AUTORIZACIONES']['RETORNO']['metodo']='Seleccion';
            $_SESSION['AUTORIZACIONES']['RETORNO']['tipo']='user';

            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente']=$tipo;
            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id']=$id;
            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']=$plan;
            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_afiliado_id']=$var[tipo_afiliado_id];
            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['rango']=$var[rango];
            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['semanas_cotizadas']=$var[semanas_cotizadas];
            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['orden_servicio_id']=$var[orden_servicio_id];

            $arr_ac=array('idp'=>$id,'plan_id'=>$plan,'tipoid'=>$tipo,'nombre'=>urlencode($nom));
            $_SESSION['AUTORIZACIONES']['RETORNO']['argumentos']=$arr_ac;
            $this->ReturnMetodoExterno('app','Autorizacion','user','AutorizacionCaja');
            return true;
        }
        else
        {
            $this->FrmOrdenar($nom,$tipo,$id);
            return true;
        }
    }


    /**
    *
    */
    function Seleccion()
    {

        if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion']))
        {
            $nom=urldecode($_REQUEST['nombre']);
            $tipo=$_REQUEST['tipoid'];
            $id=$_REQUEST['idp'];
            $plan=$_REQUEST['plan_id'];

            $this->FrmOrdenar($nom,$tipo,$id);
            return true;
        }
        else
        {
            $this->FormaMetodoBuscar();
            return true;
        }

    }


    /**
    *
    * esta funcion trae los estados dependiendo del tipo y paciente_id
    * para que se vea reflejado en el listado ojo con esta funcion
    */
    function Traer_Estados_Os_maestros($TipoId,$PacienteId)
    {
        list($dbconn) = GetDBconn();
        $query="SELECT DISTINCT (c.sw_estado)

                FROM os_ordenes_servicios a,os_maestro c,os_internas d

                WHERE
                c.numero_orden_id=d.numero_orden_id
                AND a.orden_servicio_id=c.orden_servicio_id
                AND d.departamento='".$_SESSION['LABORATORIO']['DPTO']."'
                AND c.sw_estado IN('1','2','3','0')
                AND DATE(c.fecha_activacion) <= DATE(NOW())
                AND DATE(c.fecha_vencimiento) >= DATE(NOW())
                AND a.tipo_id_paciente='$TipoId'
                AND a.paciente_id='$PacienteId'";

            $result = $dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0) {
                $this->error = "ERROR AL CONSULTAR ESTADOS";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            $arr=array();

            while(!$result->EOF)
            {
                $vars[]=$result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }

            for($i=0;$i<sizeof($vars);$i++)
            {
                if($vars[$i][sw_estado]=='0' OR $vars[$i][sw_estado]=='1')
                {
                    $arr[1]=1;//pagas
                }

                if($vars[$i][sw_estado]=='2')
                {
                    $arr[2]=2;//cumplimiento
                }

                if($vars[$i][sw_estado]=='3')
                {
                    $arr[3]=3;//atencion
                }
            }
            return $arr;
    }


    /**
    * funcion buscar1 es la que se filtra por el tipo del paciente y la identificacion del
    * paciente.
    * @access private
    * @return array
    */
    function Buscar1($ORDEN_ID=NULL, $FiltrosPaciente=NULL, $FiltroOSxPlanID=NULL, $FiltroSolicitudesxPlanID=NULL, $NUM)
    {
        list($dbconn) = GetDBconn();
        $limit=$this->limit;

        if(!empty($_SESSION['SPY']))
        {
            $LIMITE = " LIMIT ".$this->limit." OFFSET $NUM";
        }
        else
        {
            $LIMITE = '';
        }

        $DPTO = $_SESSION['LABORATORIO']['DPTO'];

        if($ORDEN_ID)
        {
            $query = "
                        SELECT
                            btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
                            b.tipo_id_paciente,
                            b.paciente_id

                        FROM
                            os_ordenes_servicios a,
                            pacientes b,
                            os_maestro c,
                            os_internas d

                        WHERE
                        c.numero_orden_id=d.numero_orden_id
                        AND a.orden_servicio_id=c.orden_servicio_id
                        AND d.departamento = '$DPTO'
                        AND c.sw_estado IN('1','2','3','0')
                        AND c.fecha_activacion <= DATE(NOW())
                        AND c.fecha_vencimiento >= DATE(NOW())
                        AND a.tipo_id_paciente=b.tipo_id_paciente
                        AND a.paciente_id=b.paciente_id
                        AND a.orden_servicio_id = $ORDEN_ID

                        ORDER BY nombre $LIMITE
            ";
        }
        else
        {
	        $query = "
                        SELECT DISTINCT
                            btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
                            b.tipo_id_paciente,
                            b.paciente_id

                        FROM
                        (
                            (
                                SELECT b.*, a.plan_id
                                FROM

                                (
                                    SELECT
                                        tipo_id_paciente,
                                        paciente_id,
                                        primer_nombre,
                                        segundo_nombre,
                                        primer_apellido,
                                        segundo_apellido

                                    FROM
                                        pacientes

                                    WHERE $FiltrosPaciente

                                ) AS b,
                                    os_ordenes_servicios a,
                                    os_maestro c,
                                    os_internas d

                                WHERE
                                c.numero_orden_id=d.numero_orden_id
                                AND a.orden_servicio_id=c.orden_servicio_id
                                AND d.departamento = '$DPTO'
                                AND c.sw_estado IN('1','2','3','0')
                                AND DATE(c.fecha_activacion) <= DATE(NOW())
                                AND DATE(c.fecha_vencimiento) >= DATE(NOW())
                                AND a.tipo_id_paciente=b.tipo_id_paciente
                                AND a.paciente_id=b.paciente_id
                                $FiltroOSxPlanID
                            )
                            UNION
                            (
                                SELECT  a.*, b.plan_id
                                FROM
                                    (
                                        SELECT
                                            tipo_id_paciente,
                                            paciente_id,
                                            primer_nombre,
                                            segundo_nombre,
                                            primer_apellido,
                                            segundo_apellido

                                        FROM
                                            pacientes

                                        WHERE $FiltrosPaciente

                                    ) AS a,
                                    hc_os_solicitudes b,
                                    departamentos_cargos e

                                WHERE b.paciente_id = a.paciente_id
                                AND b.tipo_id_paciente = a.tipo_id_paciente
                                AND b.evolucion_id IS NOT NULL
                                AND b.sw_estado = '1'
                                AND e.departamento = '$DPTO'
                                AND e.cargo = b.cargo
                                $FiltroSolicitudesxPlanID

                            )
                            UNION
                            (
                                SELECT  a.*, b.plan_id
                                FROM
                                    (
                                        SELECT
                                            tipo_id_paciente,
                                            paciente_id,
                                            primer_nombre,
                                            segundo_nombre,
                                            primer_apellido,
                                            segundo_apellido

                                        FROM
                                            pacientes

                                        WHERE $FiltrosPaciente

                                    ) AS a,
                                    hc_os_solicitudes b,
                                    hc_os_solicitudes_manuales c,
                                    departamentos_cargos d

                                WHERE b.paciente_id = a.paciente_id
                                AND b.tipo_id_paciente = a.tipo_id_paciente
                                AND b.evolucion_id IS NULL
                                AND b.sw_estado = '1'
                                AND c.hc_os_solicitud_id = b.hc_os_solicitud_id
                                AND d.departamento = '$DPTO'
                                AND d.cargo = b.cargo
                                $FiltroSolicitudesxPlanID
                            )
                        )AS b
                        ORDER BY nombre $LIMITE;
                ";
        }
		
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "ERROR AL CONSULTAR POR EL TIPO Y LA IDENTIFICACIÓN DEL PACIENTE";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        if(empty($_SESSION['SPY']))
        {
            $_SESSION['SPY'] = $result->RecordCount();

            $ControlPrimeraVez = 0;
            while(!$result->EOF && $ControlPrimeraVez < $this->limit)
            {
                $vars[]=$result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
                $ControlPrimeraVez++;
            }

        }
        else
        {
            while(!$result->EOF)
            {
                $vars[]=$result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }

        $result->Close();
        return $vars;
    }





    /**
    * esta es la secuencia de la tabla  os_laboratorios usada para ser insertada
    * y mostrar el numero cuando se cree la orden
    */
    function TraerSecuencia()
    {
        list($dbconn) = GetDBconn();
        $query="select nextval('os_laboratorios_codigo_muestra_seq');";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al traer la secuencia";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        return $result->fields[0];
    }



    function InsertarOrdenLab()
    {
        $secuencia_orden=$this->TraerSecuencia();
        list($dbconn) = GetDBconn();

        //aqui va la consulta a la tabla ingresos a ver si no faltan datos obligatorios....
        // y le pide los datos............... que faltan.

        $query="INSERT INTO os_laboratorios
                (
                    numero_orden_id,
                    codigo_muestra,
                    fecha,
                    usuario_id
                )
                VALUES
                (
                    ".$_SESSION['LABORATORIO']['N_ORDEN'].",
                    $secuencia_orden,
                    now(),
                    ".UserGetUID()."
                )";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al insertar en os_laboratorios";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        $this->FrmMostrarDatos($secuencia_orden);
        return true;
    }


    function BuscarPermiso()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $query = "SELECT e.descripcion, d.servicio, d.via_ingreso, a.caja_id, d.departamento, d.descripcion as
        descripcion3,d.prefijo_fac_contado,d.prefijo_fac_credito,
        c.descripcion as descripcion2, c.centro_utilidad, c.empresa_id, b.razon_social as descripcion1,
                                        d.cuenta_tipo_id
                                        FROM userpermisos_cajas_rapidas as a, empresas as b, departamentos as c,
                                        cajas_rapidas as d, centros_utilidad as e
                                        WHERE a.usuario_id=".UserGetUID()." and d.departamento=c.departamento
                                        and d.departamento='".$_SESSION['LABORATORIO']['DPTO']."'
                                        and d.cuenta_tipo_id !='03'
                                        and c.empresa_id=b.empresa_id and a.caja_id=d.caja_id
                                        and e.centro_utilidad=c.centro_utilidad and e.empresa_id=c.empresa_id";
/*        $query = "SELECT e.descripcion,
												d.servicio, d.via_ingreso, a.caja_id, d.departamento, d.descripcion as
												descripcion3,d.prefijo_fac_contado,d.prefijo_fac_credito,
												c.descripcion as descripcion2, c.centro_utilidad, c.empresa_id,
												b.razon_social as descripcion1, d.cuenta_tipo_id
									FROM userpermisos_cajas_rapidas as a, empresas as b,
											departamentos as c,	cajas_rapidas as d, centros_utilidad as e
									WHERE a.usuario_id=".UserGetUID()."	and d.departamento=c.departamento
									--and d.departamento='".$_SESSION['LABORATORIO']['DPTO']."'
									and d.cuenta_tipo_id !='03'
									and c.empresa_id=b.empresa_id
									and a.caja_id=d.caja_id
									and e.centro_utilidad=c.centro_utilidad and e.empresa_id=c.empresa_id
									--AND b.empresa_id = '".$_SESSION['LABORATORIO'][EMPRESA_ID]."'
									--AND b.empresa_id = d.empresa_id
									;";*/
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $resulta=$dbconn->Execute($query);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
        }

        $_SESSION['cuantascajas']=$resulta->RecordCount();

        while ($data = $resulta->FetchRow())
        {
                $centro[$data['descripcion1']][$data['descripcion2']][$data['descripcion3']]=$data;
                $seguridad[$data['empresa_id']][$data['departamento']][$data['caja_id']]=1;
        }

        $url[0]='app';
        $url[1]='CajaGeneral';
        $url[2]='user';
        $url[3]='CajaRapida';
        $url[4]='Caja';
        $arreglo[0]='EMPRESA';
        $arreglo[1]='DEPARTAMENTO';
        $arreglo[2]='CAJA RAPIDA';
        $_SESSION['SEGURIDAD']['CAJARAPIDA']['arreglo']=$arreglo;
        $_SESSION['SEGURIDAD']['CAJARAPIDA']['caja']=$centro;
        $_SESSION['SEGURIDAD']['CAJARAPIDA']['url']=$url;
        $_SESSION['SEGURIDAD']['CAJARAPIDA']['puntos']=$seguridad;
        return true;
    }



    /**
    * funcion que llama a frmordenar cuando se ha pagado....
    */
    function LLamarOrdenar()
    {
        $this->FrmOrdenar( $_SESSION['CAJA']['AUX']['nom'],$_SESSION['CAJA']['AUX']['tipo_id_paciente'],$_SESSION['CAJA']['AUX']['paciente_id']);
        unset($_SESSION['CAJA']['AUX']['nom']);
        unset($_SESSION['CAJA']['AUX']['tipo_id_paciente']);
        unset($_SESSION['CAJA']['AUX']['paciente_id']);
        return true;
    }


    /**
    *
    */
    function MenuCaja()
    {
        $datos=array('vector'=>$_REQUEST['vector'],'nom'=>$_REQUEST['nom'],'tipoid'=>$_REQUEST['tipoid'],'id'=>$_REQUEST['id'],'afiliado'=>$_REQUEST['afiliado'],'rango'=>$_REQUEST['rango'],'sem'=>$_REQUEST['sem'],'plan'=>$_REQUEST['plan'],'auto'=>$_REQUEST['auto'],'servicio'=>$_REQUEST['servicio']);
        $_SESSION['CAJA']['op']=$_REQUEST['op'];
        foreach($_SESSION['SEGURIDAD']['CAJARAPIDA']['caja'] as $k=>$v)
        {
            foreach($v as $t=>$h)
            {
                foreach($h as $p=>$m)
                {
                    $_SESSION['SEGURIDAD']['CAJARAPIDA']['caja'][$k][$t][$p]['datoscaja']=$datos;
                }
            }
        }
        $this->salida.= gui_theme_menu_acceso('CAJA RAPIDA',$_SESSION['SEGURIDAD']['CAJARAPIDA']['arreglo'],$_SESSION['SEGURIDAD']['CAJARAPIDA']['caja'],$_SESSION['SEGURIDAD']['CAJARAPIDA']['url'],ModuloGetURL('app','Os_Atencion','user','BuscarCuentaActiva',array('op'=>$_REQUEST['op'],'id'=>$_REQUEST['id'],'id_tipo'=>$_REQUEST['tipoid'],'nom'=>$_REQUEST['nom'],'plan_id'=>$_REQUEST['plan'])));
        return true;
    }




    /**
    * esta funcion trae la informacion del medico
    */
    function TraerInformacion_Medico($os_solicitud)
    {
        list($dbconn) = GetDBconn();

        $query="select a.evolucion_id,b.fecha,c.nombre,c.usuario_id,
                        d.descripcion from hc_os_solicitudes a,hc_evoluciones
                        b,system_usuarios c,departamentos d
                        WHERE a.hc_os_solicitud_id='$os_solicitud'
                        AND a.evolucion_id=b.evolucion_id
                        AND c.usuario_id=b.usuario_id
                        AND d.departamento=b.departamento";

        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "NO SE PUDO TRAER LA INFORMACION DEL MEDICO.";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        if(!$result->EOF)
        {
            $var[]=$result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }

        $result->Close();
        return $var;
    }

function TraerEquivalencia($cargo)
    {
        list($dbconn) = GetDBconn();

        $query="select codigo_datalab
						from interface_datalab_codigos
                        WHERE codigo_cups='$cargo'";

        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "NO SE PUDO TRAER LA INFORMACION DE LA EQUIVALENCIA.";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        if(!$result->EOF)
        {
            $var[]=$result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }

        $result->Close();
        return $var;
    }
	
	function TraerdptoTomado($orden)
    {
        list($dbconn) = GetDBconn();

        $query="select d.descripcion
						from os_ordenes_servicios os, departamentos d, os_maestro osm
                        WHERE d.departamento =  departamento_pt AND os.orden_servicio_id = osm.orden_servicio_id AND osm.numero_orden_id='$orden'";

        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "NO SE PUDO TRAER LA INFORMACION DE LA EQUIVALENCIA.";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        if(!$result->EOF)
        {
            $var[]=$result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }

        $result->Close();
        return $var;
    }

    /**esta funcion tare las ordenes de servicios especiales q estan en estado 0
    * $spia es una variable q si esta activa  va a realizar un record count del query
    * si no va vacia y se realiza el query comun y corriente.
    */
    function TraerOrdenesServicio_Especiales($TipoId,$PacienteId,$spia='')
    {
        list($dbconn) = GetDBconn();

        $filtro_cuenta=", os_cuenta_activa('$TipoId','$PacienteId',c.plan_id) as sw_cuenta";

        $query="SELECT
                    c.plan_id,c.plan_descripcion,a.servicio,h.descripcion as serv_des, a.fecha_registro,
                    sw_cargo_multidpto as switche,
                    CASE c.sw_tipo_plan
                    WHEN '0' THEN d.nombre_tercero
                    WHEN '1' THEN 'SOAT'
                    WHEN '2' THEN 'PARTICULAR'
                    WHEN '3' THEN 'CAPITACION - '||d.nombre_tercero
                    ELSE e.descripcion END,

                    a.tipo_afiliado_id,a.rango,a.orden_servicio_id,f.numero_orden_id,a.fecha_registro,
                    i.fecha_vencimiento, f.cargo as cargoi,g.cargo,g.descripcion as des1,i.cantidad,
                    a.autorizacion_int,a.autorizacion_ext,a.observacion,i.hc_os_solicitud_id,
                    k.tipo_afiliado_nombre,h.sw_cargo_multidpto$filtro_cuenta

                    FROM os_ordenes_servicios as a, pacientes as b, planes c,
                    terceros d, tipos_planes as e, os_internas as f, cups g,
                    servicios h,os_maestro i, tipos_afiliado k

                    WHERE
                    a.orden_servicio_id=i.orden_servicio_id
                    AND i.numero_orden_id=f.numero_orden_id
                    AND a.tipo_id_paciente=b.tipo_id_paciente
                    AND a.paciente_id=b.paciente_id
                    AND a.tipo_id_paciente='$TipoId'
                    AND a.paciente_id='$PacienteId'
                    AND a.servicio=h.servicio
                    AND g.cargo=f.cargo
                    AND c.plan_id=a.plan_id
                    AND e.sw_tipo_plan=c.sw_tipo_plan
                    AND c.tercero_id=d.tercero_id
                    AND c.tipo_tercero_id=d.tipo_id_tercero
                    AND f.departamento='".$_SESSION['LABORATORIO']['DPTO']."'
                    AND i.sw_estado='0'
                    AND a.tipo_afiliado_id=k.tipo_afiliado_id
                    AND DATE(i.fecha_activacion) <= DATE(NOW())
                    --cambio dar AND DATE(i.fecha_vencimiento) >= DATE(NOW())
                    AND DATE(i.fecha_vencimiento) >= DATE(NOW())
                    ORDER BY c.plan_id,i.fecha_vencimiento,a.orden_servicio_id";

        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL TRAER LAS ORDENES DE SERVICIO.";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        if($spia==true)
        {
            return $result->RecordCount();
        }
        while (!$result->EOF)
        {
            $var[]=$result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        $result->Close();
        return $var;
    }

    // $spia es una variable q si esta activa  va a realizar un record count del query
    //si no va vacia y se realiza el query comun y corriente.
    function TraerOrdenesServicio($TipoId,$PacienteId,$spia='')
    {
      list($dbconn) = GetDBconn();
      
      $filtro_cuenta=", os_cuenta_activa('$TipoId','$PacienteId',c.plan_id) as sw_cuenta";

     $query="SELECT
                    c.plan_id,c.plan_descripcion,a.servicio,h.descripcion as serv_des, a.fecha_registro,
                    sw_cargo_multidpto as switche,
                    CASE c.sw_tipo_plan
                    WHEN '0' THEN d.nombre_tercero
                    WHEN '1' THEN 'SOAT'
                    WHEN '2' THEN 'PARTICULAR'
                    WHEN '3' THEN 'CAPITACION - '||d.nombre_tercero
                    ELSE e.descripcion END,

                    a.tipo_afiliado_id,a.rango,a.orden_servicio_id,f.numero_orden_id,a.fecha_registro,
                    i.fecha_vencimiento, f.cargo as cargoi,g.cargo,g.descripcion as des1,i.cantidad,
                    a.autorizacion_int,a.autorizacion_ext,a.observacion,
                    k.tipo_afiliado_nombre,h.sw_cargo_multidpto$filtro_cuenta

                    FROM os_ordenes_servicios as a, pacientes as b, planes c,
                    terceros d, tipos_planes as e, os_internas as f, cups g,
                    servicios h,os_maestro i, tipos_afiliado k

                    WHERE
                    a.orden_servicio_id=i.orden_servicio_id
                    AND i.numero_orden_id=f.numero_orden_id
                    AND a.tipo_id_paciente=b.tipo_id_paciente
                    AND a.paciente_id=b.paciente_id
                    AND a.tipo_id_paciente='$TipoId'
                    AND a.paciente_id='$PacienteId'
                    AND a.servicio=h.servicio
                    AND g.cargo=f.cargo
                    AND c.plan_id=a.plan_id
                    AND e.sw_tipo_plan=c.sw_tipo_plan
                    AND c.tercero_id=d.tercero_id
                    AND c.tipo_tercero_id=d.tipo_id_tercero
                    AND f.departamento='".$_SESSION['LABORATORIO']['DPTO']."'
                    AND i.sw_estado=1
                    AND a.tipo_afiliado_id=k.tipo_afiliado_id
                    AND DATE(i.fecha_activacion) <= DATE(NOW())
                    AND DATE(i.fecha_vencimiento) >= DATE(NOW())
                    ORDER BY c.plan_id,i.fecha_vencimiento,a.orden_servicio_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "ERROR AL TRAER LAS ORDENES DE SERVICIO.";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        if($spia==true)
        {
            return $result->RecordCount();
        }
        while (!$result->EOF)
        {
            $var[]=$result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        $result->Close();
        return $var;
}


//si no va vacia y se realiza el query comun y corriente.
//trae las ordenes de servicio de estado 2 osea pagado.
function TraerOrdenesServicio_estado2($TipoId,$PacienteId)
{
    list($dbconn) = GetDBconn();
		
    $query = "SELECT distinct c.plan_id,c.plan_descripcion,a.servicio,h.descripcion as serv_des, a.fecha_registro,
                        sw_cargo_multidpto as switche,
                        CASE c.sw_tipo_plan WHEN '0' THEN d.nombre_tercero
                        WHEN '1' THEN 'SOAT'
                        WHEN '2' THEN 'PARTICULAR'
                        WHEN '3'
                        THEN 'CAPITACION - '||d.nombre_tercero ELSE e.descripcion END,
                        a.tipo_afiliado_id,a.rango,a.orden_servicio_id,f.numero_orden_id,a.fecha_registro, i.fecha_vencimiento,
                        f.cargo as cargoi,g.descripcion as des1,i.cantidad, a.autorizacion_int,a.autorizacion_ext,a.observacion,
                        k.tipo_afiliado_nombre,l.os_tipo_solicitud_id
                        FROM os_ordenes_servicios as a,os_maestro i, pacientes as b, tipos_afiliado k,
                        servicios h, os_internas as f, cups g, hc_os_solicitudes l, planes c, tipos_planes as e, terceros d
                        WHERE a.tipo_id_paciente='$TipoId' AND a.paciente_id='$PacienteId'
                        AND a.orden_servicio_id=i.orden_servicio_id
                        AND i.sw_estado=2
                        AND i.hc_os_solicitud_id=l.hc_os_solicitud_id
                        AND DATE(i.fecha_activacion) <= DATE(NOW())
                        AND DATE(i.fecha_vencimiento) >= DATE(NOW())
                        AND i.numero_orden_id=f.numero_orden_id
                        AND f.departamento='".$_SESSION['LABORATORIO']['DPTO']."'
                        AND g.cargo=f.cargo
                        AND a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id
                        AND a.tipo_afiliado_id=k.tipo_afiliado_id
                        AND a.servicio=h.servicio
                        AND c.plan_id=a.plan_id
                        AND c.tercero_id=d.tercero_id AND c.tipo_tercero_id=d.tipo_id_tercero
                        ORDER BY c.plan_id,i.fecha_vencimiento,a.orden_servicio_id";
 /*echo $query="SELECT
                    c.plan_id,c.plan_descripcion,a.servicio,h.descripcion as serv_des,
                    sw_cargo_multidpto as switche,
                    CASE c.sw_tipo_plan
                    WHEN '0' THEN d.nombre_tercero
                    WHEN '1' THEN 'SOAT'
                    WHEN '2' THEN 'PARTICULAR'
                    WHEN '3' THEN 'CAPITACION - '||d.nombre_tercero
                    ELSE e.descripcion END,
                    a.tipo_afiliado_id,a.rango,a.orden_servicio_id,f.numero_orden_id,a.fecha_registro,
                    i.fecha_vencimiento, f.cargo as cargoi,g.descripcion as des1,i.cantidad,
                    a.autorizacion_int,a.autorizacion_ext,a.observacion,
                    k.tipo_afiliado_nombre,l.os_tipo_solicitud_id
                    FROM os_ordenes_servicios as a, pacientes as b, planes c,
                    terceros d, tipos_planes as e, os_internas as f, cups g,
                    servicios h,os_maestro i, tipos_afiliado k, hc_os_solicitudes l
                    WHERE
                    a.orden_servicio_id=i.orden_servicio_id
                    AND i.numero_orden_id=f.numero_orden_id
                    AND a.tipo_id_paciente=b.tipo_id_paciente
                    AND a.paciente_id=b.paciente_id
                    AND a.tipo_id_paciente='$TipoId'
                    AND a.paciente_id='$PacienteId'
                    AND a.servicio=h.servicio
                    AND g.cargo=f.cargo
                    AND c.plan_id=a.plan_id
                    AND e.sw_tipo_plan=c.sw_tipo_plan
                    AND c.tercero_id=d.tercero_id
                    AND c.tipo_tercero_id=d.tipo_id_tercero
                    AND f.departamento='".$_SESSION['LABORATORIO']['DPTO']."'
                    AND i.sw_estado=2
                    AND a.tipo_afiliado_id=k.tipo_afiliado_id
                    AND i.hc_os_solicitud_id=l.hc_os_solicitud_id
                    AND DATE(i.fecha_activacion) <= NOW()
                    ORDER BY c.plan_id,i.fecha_vencimiento,a.orden_servicio_id";exit;*/
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
                $this->error = "ERROR AL TRAER LAS ORDENES DE SERVICIOS.";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            if($spia==true)
            {
                return $result->RecordCount();
            }
            while (!$result->EOF) {
                            $var[]=$result->GetRowAssoc($ToUpper = false);
                            $result->MoveNext();
                    }
                    $result->Close();
                    return $var;
}

//si no va vacia y se realiza el query comun y corriente.
//trae las ordenes de servicio de estado 3 osea atender.
function TraerOrdenesServicio_estado3($TipoId,$PacienteId)
{
    list($dbconn) = GetDBconn();

		$query="
					SELECT
						c.plan_id,
						c.plan_descripcion,
						a.servicio,
						h.descripcion as serv_des,
						sw_cargo_multidpto as switche,
							CASE c.sw_tipo_plan
							WHEN '0' THEN d.nombre_tercero
							WHEN '1' THEN 'SOAT'
							WHEN '2' THEN 'PARTICULAR'
							WHEN '3' THEN 'CAPITACION - '||d.nombre_tercero
							ELSE e.descripcion END,
						a.tipo_afiliado_id,
						a.rango,
						a.orden_servicio_id,
						f.numero_orden_id,
						a.fecha_registro,
						i.fecha_vencimiento, 
						f.cargo as cargoi,
						g.descripcion as des1,
						i.cantidad,
						a.autorizacion_int,
						a.autorizacion_ext,
						a.observacion,
						k.tipo_afiliado_nombre,
						l.os_tipo_solicitud_id,
						osc.fecha_cumplimiento ,
						osc.numero_cumplimiento
					FROM 
						os_ordenes_servicios as a,
						pacientes as b, 
						planes c,
						terceros d, 
						tipos_planes as e, 
						os_internas as f, 
						cups g, 
						hc_os_solicitudes l,
						servicios h,
						os_maestro i,
						tipos_afiliado k,
						os_cumplimientos as osc,
						os_cumplimientos_detalle as oscd
					WHERE
						a.tipo_id_paciente=osc.tipo_id_paciente
						AND a.paciente_id=osc.paciente_id
						AND osc.departamento=f.departamento
						AND a.tipo_id_paciente='".$TipoId."'
						AND a.paciente_id='".$PacienteId."'
						AND osc.fecha_cumplimiento = oscd.fecha_cumplimiento
						AND osc.numero_cumplimiento = oscd.numero_cumplimiento
						AND oscd.numero_orden_id = i.numero_orden_id					
						AND a.orden_servicio_id=i.orden_servicio_id
						AND i.numero_orden_id=f.numero_orden_id
						AND a.tipo_id_paciente=b.tipo_id_paciente
						AND a.paciente_id=b.paciente_id
						AND a.servicio=h.servicio
						AND g.cargo=f.cargo
						AND c.plan_id=a.plan_id
						AND e.sw_tipo_plan=c.sw_tipo_plan
						AND c.tercero_id=d.tercero_id
						AND c.tipo_tercero_id=d.tipo_id_tercero
						AND f.departamento='".$_SESSION['LABORATORIO']['DPTO']."'
						AND i.sw_estado=3
						AND a.tipo_afiliado_id=k.tipo_afiliado_id
						AND i.hc_os_solicitud_id=l.hc_os_solicitud_id
						AND DATE(i.fecha_activacion) <= DATE(NOW())
						AND DATE(i.fecha_vencimiento) >= DATE(NOW())
										
              ORDER BY f.numero_orden_id
		
		";
		
		/*
		
   $query="SELECT
                    c.plan_id,c.plan_descripcion,a.servicio,h.descripcion as serv_des,
                    sw_cargo_multidpto as switche,
                    CASE c.sw_tipo_plan
                    WHEN '0' THEN d.nombre_tercero
                    WHEN '1' THEN 'SOAT'
                    WHEN '2' THEN 'PARTICULAR'
                    WHEN '3' THEN 'CAPITACION - '||d.nombre_tercero
                    ELSE e.descripcion END,
                    a.tipo_afiliado_id,a.rango,a.orden_servicio_id,f.numero_orden_id,a.fecha_registro,
                    i.fecha_vencimiento, f.cargo as cargoi,g.descripcion as des1,i.cantidad,
                    a.autorizacion_int,a.autorizacion_ext,a.observacion,
                    k.tipo_afiliado_nombre,l.os_tipo_solicitud_id
                    FROM os_ordenes_servicios as a, pacientes as b, planes c,
                    terceros d, tipos_planes as e, os_internas as f, cups g, hc_os_solicitudes l,
                    servicios h,os_maestro i,tipos_afiliado k
                    WHERE
                    a.orden_servicio_id=i.orden_servicio_id
                    AND i.numero_orden_id=f.numero_orden_id
                    AND a.tipo_id_paciente=b.tipo_id_paciente
                    AND a.paciente_id=b.paciente_id
                    AND a.tipo_id_paciente='$TipoId'
                    AND a.paciente_id='$PacienteId'
                    AND a.servicio=h.servicio
                    AND g.cargo=f.cargo
                    AND c.plan_id=a.plan_id
                    AND e.sw_tipo_plan=c.sw_tipo_plan
                    AND c.tercero_id=d.tercero_id
                    AND c.tipo_tercero_id=d.tipo_id_tercero
                    AND f.departamento='".$_SESSION['LABORATORIO']['DPTO']."'
                    AND i.sw_estado=3
                    AND a.tipo_afiliado_id=k.tipo_afiliado_id
                    AND i.hc_os_solicitud_id=l.hc_os_solicitud_id
                    AND DATE(i.fecha_activacion) <= DATE(NOW())
                    AND DATE(i.fecha_vencimiento) >= DATE(NOW())
                    ORDER BY f.numero_orden_id";
										
				*/
				//echo $query;
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
                $this->error = "ERROR AL TRAER LAS ORDENES DE SERVICIOS.";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            if($spia==true)
            {
                return $result->RecordCount();
            }
            while (!$result->EOF) {
                            $var[]=$result->GetRowAssoc($ToUpper = false);
                            $result->MoveNext();
                    }
                    $result->Close();
                    return $var;
}



    /**
    * Esta funcion permite buscar una cuenta activa en un paciente.
    * si tiene una cuenta activa o no para tener la opcion de cargarla a la cuenta.
    * @return array
    */
    function BuscarCuentaActiva($id,$tipo,$nom,$op,$plan)
    {
	if(empty($op))
	{
            $id=$_REQUEST['id'];
            $tipo=$_REQUEST['id_tipo'];
            $nom=urldecode($_REQUEST['nom']);
            $plan=$_REQUEST['plan_id'];
            $op=$_REQUEST['op'];
            $dpto = $_REQUEST['departamento_pt'];
	}
			
        if(empty($op))
        {
            $this->frmError["MensajeError"]="SELECCIONE MINIMO 1 CARGO";
				$this->FrmOrdenar($nom,$tipo,$id,$op,$dpto);
				return true;
	}
      
        if($dpto == '-1')
        {
            $this->frmError["MensajeError"] = "PARA LA ORDEN SELECCIONADA, SE DEBE INDICAR EL PUNTO DE TOMADO";
				$this->FrmOrdenar($nom,$tipo,$id,$op,$dpto);
				return true;
        }

	foreach($_REQUEST['op'] as $index=>$codigo)
	{
            $valores=explode(",",$codigo);// "--->>".print_r($valores);exiT;
            break;
	}

			//"$valores[7]" este es el campo->servicio para determinar si es ambulatorio
			//o si es hospitalario,si es ambulatorio no mostrara cargar a la cuenta
			list($dbconn) = GetDBconn();
      
		  $query = "SELECT servicio,descripcion FROM servicios WHERE servicio='".$valores[7]."' AND sw_cargo_multidpto='1'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "ERROR AL TRAER LAS ORDENES DE SERVICIOS.";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			//si viene en 1 o mas es por q si se deberia buscar si existe una cuenta..
			if($result->RecordCount() >=1)
			{
				 $query="SELECT
								a.numerodecuenta,a.plan_id,a.total_cuenta,c.servicio,c.descripcion,
								d.ingreso,f.plan_descripcion,e.nombre_tercero as tercero,
								(a.total_cuenta - a.valor_cubierto - (a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_letras)) as saldo

								FROM cuentas a,servicios c,ingresos d,terceros e,planes f

								WHERE
								c.servicio='".$result->fields[0]."'
								AND d.tipo_id_paciente='". $tipo."'
								AND d.paciente_id='". $id."'
								AND a.ingreso=d.ingreso
								AND d.estado=1
								AND a.estado=1
								AND c.sw_cargo_multidpto=1
								AND e.tipo_id_tercero=f.tipo_tercero_id
								AND e.tercero_id=f.tercero_id
								AND a.plan_id='". $plan."'
								AND a.plan_id=f.plan_id";
								//AND DATE(a.fecha_vencimiento) > NOW()

				//exit;
						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) 
						{
								$this->error = "ERROR AL TRAER LAS ORDENES DE SERVICIOS.";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
						}
						while (!$result->EOF) 
						{
							$var[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
						}
			}
			$result->Close();
      
      if($dpto && $dpto != '-1')
      {
        foreach($_REQUEST['op'] as $key => $d)
        {
          $dtl = explode(",",$d);
          
          $sql  = "UPDATE os_ordenes_servicios ";
          $sql .= "SET    departamento_pt = '".$dpto."' ";
          $sql .= "WHERE  orden_servicio_id = ".$dtl[9]." ";
          
          $result = $dbconn->Execute($sql);
        }
      }
      
			$this->LiquidacionOrden($var,$nom,$tipo,$id,$op,$plan,'','',$dpto);
			return true;
    }



        /**
        * funcion que trae el nombre de la especialista ya sea radiologa,o bacteriologa
        * solo para el caso de que sea imagenologia.
        */
        function TraerEspecialista($Norden)
        {
                list($dbconn) = GetDBconn();

            $query = "SELECT nombre
                FROM os_cumplimientos_detalle a,profesionales b,profesionales_usuarios x
                WHERE a.numero_orden_id = ".$Norden."
                AND x.usuario_id=a.usuario_id
                AND x.tipo_tercero_id=b.tipo_id_tercero
                AND x.tercero_id=b.tercero_id
                AND departamento = '".$_SESSION['LABORATORIO']['DPTO']."'";

                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "ERROR AL TRAER EL NÚMERO DE CUMPLIMIENTO.";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                return $result->fields[0];
        }


    /*
        Funcion que trae el numero de cumplimiento generado
        al darse la atencion
    */
    function TraerNumeroCumplimiento($orden_id)
    {
                list($dbconn) = GetDBconn();

                $query = "SELECT numero_cumplimiento,fecha_cumplimiento
                FROM os_cumplimientos_detalle
                WHERE numero_orden_id = ".$orden_id." AND
                departamento = '".$_SESSION['LABORATORIO']['DPTO']."' order by numero_cumplimiento asc";

                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "ERROR AL TRAER EL NÚMERO DE CUMPLIMIENTO.";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                return $var[]=$result->GetRowAssoc($ToUpper = false);
    }


    /*
    * Esta funcion trae los datos de la tabla
    * os_maestro,os_ordenes_servicios segun el numero de la orden.
    * @return array
    */
    function DatosOs($orden)
    {
      list($dbconn) = GetDBconn();
      $query = "(
                  SELECT  a.orden_servicio_id,
                          a.autorizacion_int,
                          a.autorizacion_ext,
                          a.plan_id,
                          a.tipo_afiliado_id,
                          a.semanas_cotizadas,
                          servicio,
                          a.tipo_id_paciente,
                          a.paciente_id,
                          a.usuario_id,
                          a.fecha_registro,
                          a.observacion,
                          a.rango,
                          b.numero_orden_id,
                          b.sw_estado,
                          b.orden_servicio_id,
                          b.fecha_vencimiento,
                          b.cantidad,
                          b.hc_os_solicitud_id,
                          b.fecha_activacion,
                          b.cargo_cups,
                          b.fecha_refrendar,
                          b.numerodecuenta,
                          c.os_maestro_cargos_id,
                          c.numero_orden_id,
                          c.tarifario_id,
                          c.cargo,
                          c.transaccion,
                          d.tarifario_id,
                          d.grupo_tarifario_id,
                          d.subgrupo_tarifario_id,
                          d.cargo,
                          d.descripcion,
                          d.precio,
                          d.tipo_cargo,
                          d.grupo_tipo_cargo,
                          d.gravamen,
                          d.sw_cantidad,
                          d.nivel,
                          d.sw_honorarios,
                          d.concepto_rips,
                          d.sw_uvrs,
                          d.grupos_mapipos,
                          d.tipo_unidad_id,
                          b.cargo_cups,
                          d.descripcion,
                          c.os_maestro_cargos_id,
                          NULL AS sw_factura
                  FROM    os_ordenes_servicios a,
                          os_maestro b,
                          os_maestro_cargos c,
                          tarifarios_detalle as d
                  WHERE   a.orden_servicio_id=b.orden_servicio_id 
                  AND     b.numero_orden_id=$orden
                  AND     b.numero_orden_id=c.numero_orden_id and c.cargo=d.cargo
                  AND     c.tarifario_id=d.tarifario_id
                  UNION ALL 
                  SELECT NULL AS orden_servicio_id,
                          NULL as autorizacion_int,
                          NULL as autorizacion_ext,
                          a.plan_id,
                          NULL as tipo_afiliado_id,
                          NULL as semanas_cotizadas,
                          NULL as servicio,
                          NULL as tipo_id_paciente,
                          NULL as paciente_id,
                          NULL as usuario_id,
                          NULL as fecha_registro,
                          NULL as observacion,
                          NULL as rango,
                          a.numero_orden_id,
                          NULL as sw_estado,
                          NULL as orden_servicio_id,
                          NULL as fecha_vencimiento,
                          a.cantidad,
                          NULL as hc_os_solicitud_id,
                          NULL as fecha_activacion,
                          a.cargo_cups,
                          NULL as fecha_refrendar,
                          NULL as numerodecuenta,
                          NULL as os_maestro_cargos_id,
                          a.numero_orden_id,
                          a.tarifario_id,
                          a.cargo,
                          a.transaccion,
                          a.tarifario_id,
                          a.grupo_tarifario_id,
                          a.subgrupo_tarifario_id,
                          a.cargo,
                          d.descripcion,
                          d.precio,
                          a.tipo_cargo,
                          a.grupo_tipo_cargo,
                          NULL as gravamen,
                          NULL as sw_cantidad,
                          a.nivel,
                          NULL as sw_honorarios,
                          a.concepto_rips,
                          NULL as sw_uvrs,
                          NULL as grupos_mapipos,
                          a.tipo_unidad_id,
                          a.cargo_cups,
                          d.descripcion,
                          NULL as os_maestro_cargos_id,
                          a.sw_factura
                  FROM    tmp_cuentas_cargos a,
                          tarifarios_detalle as d
                  WHERE   a.numero_orden_id=$orden
                  AND     a.cargo=d.cargo
                  AND     a.tarifario_id=d.tarifario_id
                  )";
																		
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "ERROR AL TRAER LAS ORDENES DE SERVICIOS.";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            while (!$result->EOF) {
                            $var[]=$result->GetRowAssoc($ToUpper = false);
                            $result->MoveNext();
            }

            $result->Close();
            return $var;
    }

	function TraerCargosAdicionados($orden)
	{
				list($dbconn) = GetDBconn();
				$query = "SELECT a.numero_orden_id,
												a.cantidad,
												a.cargo_cups,
												a.numero_orden_id,
												a.tarifario_id,
												a.cargo,
												a.transaccion,
												a.tarifario_id,
												a.grupo_tarifario_id,
												a.subgrupo_tarifario_id,
												a.cargo,
												d.descripcion,
												d.precio,
												a.tipo_cargo,
												a.grupo_tipo_cargo,
												a.nivel,
												a.concepto_rips,
												a.tipo_unidad_id,
												a.cargo_cups,
												d.descripcion
								FROM tmp_cuentas_cargos a,tarifarios_detalle as d
								WHERE a.numero_orden_id=$orden
								AND a.cargo=d.cargo
								AND a.tarifario_id=d.tarifario_id;";
						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
										$this->error = "ERROR AL TRAER LAS ORDENES DE SERVICIOS.";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
						}
						while (!$result->EOF) {
														$var[]=$result->GetRowAssoc($ToUpper = false);
														$result->MoveNext();
						}
						$result->Close();
						return $var;
	}

    function Actualizar_Tmp_cargos()
    {
        $empresa=$_SESSION['LABORATORIO']['EMPRESA_ID'];
        $centro=$_SESSION['LABORATORIO']['CENTROUTILIDAD'];
        $depto=$_SESSION['LABORATORIO']['DPTO'];
        $servicio=$_SESSION['OS_ATENCION']['SERVICIO_ID'];
        $numero_orden=$_REQUEST['numero_orden'];
        $cargobase=$_REQUEST['cargobase'];
        $descripcion=$_REQUEST['descripcion'];
        $plan=$_REQUEST['plan'];
        $orden=$_SESSION['OS_ATENCION']['ORDEN'];
        $sw_hay_cuenta=$_REQUEST['sw_hay_cuenta'];
        list($dbconn) = GetDBconn();
        if(!empty($_SESSION['OS_ATENCION']['PROCEDIMIENTOS']))
        {
            for($i=0; $i<sizeof($_SESSION['OS_ATENCION']['PROCEDIMIENTOS']);$i++)
            {
                if($_REQUEST['seleccion'.$i])
                {
                    if(!is_numeric($_REQUEST["cargo".$_SESSION['OS_ATENCION']['PROCEDIMIENTOS'][$i]['cargo']]))
                    {
                        $this->frmError["MensajeError"]='LA CANTIDAD DEBE SER NUMERICA';
                        $this->FrmVerEquivalencias($cargobase,$descripcion);
                        return true;
                    }
                    $valor=explode('||//',$_REQUEST['seleccion'.$i]);
                    $cantidad=$_REQUEST["cargo".$_SESSION['OS_ATENCION']['PROCEDIMIENTOS'][$i]['cargo']];
                    $tarifario_id=$valor[0];
                    $cargo=$valor[1];
                    $precio=$valor[2];
                    $grupo_tarifario_id=$valor[3];
                    $subgrupo_tarifario_id=$valor[4];
                    $tipo_cargo=$valor[5];
                    $grupo_tipo_cargo=$valor[6];
                    $nivel=$valor[7];
                    $tipo_unidad_id=$valor[8];
                    $sw_honorarios=$valor[9];
                    $concepto_rips=$valor[10];
                    //CASO CUANDO NO HAY CUENTA CREADA
                    if($sw_hay_cuenta)
                    {
                        $campos1='numerodecuenta,';
                        $valor1=$_SESSION['OS_ATENCION']['cuenta'].",";
                    }
                    else
                    {$campos1='';$valor1='';}
										$cantidad = $_REQUEST["cargo".$_SESSION['OS_ATENCION']['PROCEDIMIENTOS'][$i]['cargo']];
										$cantidad_aumentada = $cantidad+1;
            								$query="INSERT INTO tmp_cuentas_cargos
                                    (
                                        empresa_id,
                                        centro_utilidad,
                                        $campos1
                                        numero_orden_id,
                                        departamento,
                                        tarifario_id,
                                        tipo_cargo,
                                        grupo_tipo_cargo,
                                        cargo,
                                        cantidad,
                                        tipo_unidad_id,
                                        precio,
                                        servicio_cargo,
                                        plan_id,
                                        cargo_cups,
                                        grupo_tarifario_id,
                                        subgrupo_tarifario_id,
                                        nivel,
                                        sw_honorarios,
                                        concepto_rips
                                    )
                                    VALUES
                                    (
                                        '$empresa',
                                        '$centro',
                                        $valor1
                                        $orden,
                                        '$depto',
                                        '$tarifario_id',
                                        '$tipo_cargo',
                                        '$grupo_tipo_cargo',
                                        '$cargo',
                                        ".$_REQUEST["cargo".$_SESSION['OS_ATENCION']['PROCEDIMIENTOS'][$i]['cargo']].",
                                        '$tipo_unidad_id',
                                        $precio,
                                        $servicio,
                                        $plan,
                                        '$cargobase',
                                        '$grupo_tarifario_id',
                                        '$subgrupo_tarifario_id',
                                        '$nivel',
                                        '$sw_honorarios',
                                        '$concepto_rips'
                                    );";
									//echo $query;
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0){
                            $this->error = "Error al insertar en tmp_cuentas_cargos";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            return false;
                        }
                }
            }
        }
        $this->LiquidacionOrden('','','','','','','');
        return true;
}

		/**
    * Esta funcion carga a la cuenta los cargos que se ha seleccionado, solo si
    * si tiene una cuenta activa o no para tener la opcion de cargarla a la cuenta.
    * @return boolean
    */
    function InsertarCargoCuenta()
    {
        IncludeLib("tarifario_cargos");
        IncludeLib("funciones_facturacion");
        $nom=$_REQUEST['nom'];
        $id=$_REQUEST['pac'];
        $tipo=$_REQUEST['tipo_id'];
        
				list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();

				$sw_automatico='0';				
        foreach($_REQUEST['op'] as $index=>$codigo)
        {
					$valores=explode(",",$codigo);

					$query="SELECT tarifario_id,cargo FROM os_maestro_cargos
													WHERE numero_orden_id=".$valores[0]."";
					$resulta=$dbconn->execute($query);
					if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											return false;
					}
					while(!$resulta->EOF)
					{
							//$datos=$this->DatosOs($valores[0]);//print_r($datos);
							unset($datos);
							$query = "SELECT *, b.cargo_cups,d.descripcion,c.os_maestro_cargos_id 
												FROM 	os_ordenes_servicios a,
															os_maestro b,
															os_maestro_cargos c,
															tarifarios_detalle as d
												WHERE a.orden_servicio_id=b.orden_servicio_id 
															AND b.numero_orden_id=".$valores[0]."
															AND b.numero_orden_id=c.numero_orden_id 
															AND c.cargo=d.cargo
															AND c.tarifario_id=d.tarifario_id";
							$result = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
											$this->error = "ERROR AL TRAER LAS ORDENES DE SERVICIOS.";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											$dbconn->RollbackTrans();
											return false;
							}
							while (!$result->EOF) {
															$datos[]=$result->GetRowAssoc($ToUpper = false);
															$result->MoveNext();
							}
							/*$Liq=LiquidarCargoCuenta($_REQUEST['cuenta'],$resulta->fields[0],$resulta->fields[1],$valores[5],0,0,false,false,0,$valores[7],$_REQUEST['plan'],$datos[tipo_afiliado_id],$datos[rango],$datos[semanas_cotizadas],false);
							$DescuentoEmp=$Liq[valor_descuento_empresa];
							$DescuentoPac=$Liq[valor_descuento_paciente];
							$Moderadora=$Liq[cuota_moderadora];
							$Precio=$Liq[precio_plan];
							$ValorCargo=$Liq[valor_cargo];
							$ValorPac=$Liq[copago];
							$ValorNo=$Liq[valor_no_cubierto];
							$ValorCub=$Liq[valor_cubierto];
							$ValEmpresa=$Liq[valor_empresa];
							$facturado=$Liq[facturado];*/
							$AutoExt=$valores[3];
							$AutoInt=$valores[4];
							/*$codigo='NULL';
							$agru=BuscarGrupoTipoCargo($resulta->fields[1],$resulta->fields[0],&$dbconn);
							if(!empty($agru))
							{
											$codigo=$agru;
							}
							if(empty($AutoExt)){$AutoExt='NULL';}
							if(empty($AutoInt)){$AutoInt='NULL';}*/
							unset($arreglo);
							$arreglo[]=array('cargo'=>$resulta->fields[1],'tarifario'=>$resulta->fields[0],'servicio'=>$valores[7],'aut_int'=>$AutoInt,'aut_ext'=>$AutoExt,'cups'=>$datos[0][cargo_cups],'cantidad'=>$valores[5],'departamento'=>$_SESSION['LABORATORIO']['DPTO'],'sw_cargue'=>4,'numero_orden_id'=>$valores[0]);
							$insertar = InsertarCuentasDetalle($_SESSION['LABORATORIO']['EMPRESA_ID'],$_SESSION['LABORATORIO']['CENTROUTILIDAD'],$_REQUEST['cuenta'],$_REQUEST['plan'],$arreglo,'',&$dbconn);

							if(empty($insertar))
							{
											$this->frmError["MensajeError"]="ERROR: OCURRIO UN ERROR AL INSERTAR.";
											echo "error : InsertarCuentasDetalle";
											echo "Error DB : " . $dbconn->ErrorMsg();
//											exit;
							}							
										
							/*  $query="UPDATE os_maestro_cargos SET transaccion=$Transaccion
									WHERE numero_orden_id=".$valores[0]." AND cargo='".$resulta->fields[1]."'
									AND tarifario_id='".$resulta->fields[0]."'";
									$dbconn->Execute($query);
									//es importante determinar este error
									if($dbconn->Affected_Rows() == 0){
													$this->error = "fallo actualizacion [transaccion] os_maestro_cargos";
													$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
													$dbconn->RollbackTrans();
													return false;
													}*/
								
												
								$resulta->MoveNext();
											
					}
					//ojo esto se cambio con el fin de q quede en estado 2 en ves de (3) para asignar el medico
					$query="UPDATE os_maestro SET sw_estado='2' where numero_orden_id='$valores[0]'";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al actualizar en os_maestro1";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
					}
					
					//ADICIONADO POR LORENA PUES NO LIQUIDA LOS DATOS ADICIONADOS MANUALMENTE	
					
					foreach($_REQUEST['op'] as $index=>$codigo){				
						$valores=explode(",",$codigo);
						$datos=$this->DatosOs($valores[0]);
						$query="(SELECT tarifario_id,cargo,'1' as adicionado,transaccion,cantidad  FROM tmp_cuentas_cargos 
									WHERE numero_orden_id=".$valores[0].")";
						$resulta=$dbconn->execute($query);
						if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}else{
							
							while(!$resulta->EOF){
							
								//$Liq=LiquidarCargoCuenta($_REQUEST['cuenta'],$resulta->fields[0],$resulta->fields[1],$valores[5],0,0,false,false,0,$valores[7],$_REQUEST['plan'],$datos[tipo_afiliado_id],$datos[rango],$datos[semanas_cotizadas],false);
								$Liq=LiquidarCargoCuenta($_REQUEST['cuenta'],$resulta->fields[0],$resulta->fields[1],$resulta->fields[4],0,0,false,false,0,$valores[7],$_REQUEST['plan'],$datos[tipo_afiliado_id],$datos[rango],$datos[semanas_cotizadas],$valores[0],false);
								
								$DescuentoEmp=$Liq[valor_descuento_empresa];
								$DescuentoPac=$Liq[valor_descuento_paciente];
								$Moderadora=$Liq[cuota_moderadora];
								$Precio=$Liq[precio_plan];
								$ValorCargo=$Liq[valor_cargo];
								$ValorPac=$Liq[copago];
								$ValorNo=$Liq[valor_no_cubierto];
								$ValorCub=$Liq[valor_cubierto];
								$ValEmpresa=$Liq[valor_empresa];
								$facturado=$Liq[facturado];							
								$AutoExt='NULL';
								$AutoInt='NULL';
								
								$query="SELECT nextval('cuentas_detalle_transaccion_seq')";
								$result=$dbconn->Execute($query);
								$Transaccion=$result->fields[0];
		
								$query = "INSERT INTO cuentas_detalle (
																	transaccion,
																	empresa_id,
																	centro_utilidad,
																	numerodecuenta,
																	departamento,
																	tarifario_id,
																	cargo,
																	cantidad,
																	precio,
																	valor_cargo,
																	valor_nocubierto,
																	valor_cubierto,
																	usuario_id,
																	facturado,
																	fecha_cargo,
																	fecha_registro,
																	valor_descuento_empresa,
																	valor_descuento_paciente,
																	autorizacion_int,
																	autorizacion_ext,
																	servicio_cargo,
																	porcentaje_gravamen,
																	sw_cuota_paciente,
																	sw_cuota_moderadora,
																	codigo_agrupamiento_id,
																	cargo_cups,
																	sw_cargue,
																	numero_orden_id)
									VALUES ($Transaccion,'".$_SESSION['LABORATORIO']['EMPRESA_ID']."',
									'".$_SESSION['LABORATORIO']['CENTROUTILIDAD']."',".$_REQUEST['cuenta'].",'".$_SESSION['LABORATORIO']['DPTO']."','".$resulta->fields[0]."','".$resulta->fields[1]."',".$resulta->fields[4].",
									$Precio,$ValorCargo,
									$ValorNo,$ValorCub,".UserGetUID().",$facturado,'now()',
									'now()',$DescuentoEmp,$DescuentoPac,
									$AutoInt,$AutoExt,'$valores[7]',
									".$Liq[porcentaje_gravamen].",".$Liq[sw_cuota_paciente].",".$Liq[sw_cuota_moderadora].",NULL,'".$datos[0][cargo_cups]."','4',".$valores[0].")";
									//echo "<br><br>".$query;
									//echo "<br><br>".$resulta->fields[1];
								$dbconn->Execute($query);		
								if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al insertar en cuentas_detalle";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
								}else{
									$query = "DELETE FROM tmp_cuentas_cargos WHERE numero_orden_id='".$valores[0]."'
													AND tarifario_id='".$resulta->fields[0]."'
													AND cargo='".$resulta->fields[1]."'";
													
									$dbconn->Execute($query);
									if($dbconn->ErrorNo() != 0) {
										$this->error = "Error al insertar en cuentas_detalle";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$dbconn->RollbackTrans();
										return false;
									}
								}
								$resulta->MoveNext();
							}
						}	
					}		
											
					//FIN ADICION lorena
					
					$dbconn->CommitTrans();
					
					$query="
										SELECT	sw_cumplido_automatico
										FROM		departamentos_cargos
										WHERE		departamento = '".$_SESSION['LABORATORIO']['DPTO']."'
														AND cargo =  '".$datos[0][cargo_cups]."'
					";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "ERROR AL TRAER SW DE AUTOMATICOS DE DEPTOS CARGO.";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
					}
					$sw_auto=$result->fields[0];
					if($sw_auto[sw_cumplido_automatico]=='1')
					{
						$sw_automatico='1';
					}
        }
				
				
				
				//MauroB
				//VALIDAR SW DE CUMPLIDO Y TOMADO AUTOMATICO
// 				 $query="
// 									SELECT	sw_cumplido_automatico
// 									FROM		departamentos_cargos
// 									WHERE		departamento = '".$_SESSION['LABORATORIO']['DPTO']."'
// 													AND cargo =  '".$datos[0][cargo_cups]."'
// 				";
// 				$result = $dbconn->Execute($query);
// 				if ($dbconn->ErrorNo() != 0) {
// 								$this->error = "ERROR AL TRAER SW DE AUTOMATICOS DE DEPTOS CARGO.";
// 								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
// 								$dbconn->RollbackTrans();
// 								return false;
// 				}
// 				$sw_auto=$result->fields[0];
				
				if(($sw_automatico=='1') AND ($_SESSION['LABORATORIO']['SW_HONORARIO'])==0)
				{
					$this->CambiarEstadoACumplimiento($nom,$tipo,$id,sizeof($_REQUEST['op']),$_REQUEST['op'],1,0);
				}
				//Fin MauroB
							
				
				
				if(EMPTY($_SESSION['LABORATORIO']['CAJARAPIDA']))
				{
								$action2=ModuloGetURL('app','Os_Atencion','user','FrmOrdenar',array('nombre'=>$nom,'tipoid'=>$tipo,'idp'=>$id,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of']));
				}
				else
				{
								$contenedor=$_SESSION['LABORATORIO']['RETORNO']['contenedor'];
								$modulo=$_SESSION['LABORATORIO']['RETORNO']['modulo'];
								$tipo=$_SESSION['LABORATORIO']['RETORNO']['tipo'];
								$metodo=$_SESSION['LABORATORIO']['RETORNO']['metodo'];
								$action2=ModuloGetURL($contenedor,$modulo,$tipo,$metodo);
				}
				$this->FormaMensaje('DATOS CARGADOS A LA CUENTA EXITOSAMENTE','INFORMACION',$action2,'volver');
				return true;
    }

//***********************************************************
//INSERTAR INSUMOS Y MEDICAMENTOS

	function ActualizarTmp_cuenta_imd($orden,$Cuenta)
	{
		list($dbconn) = GetDBconn();
		/*$query = "UPDATE tmp_cuenta_imd 
							SET numerodecuenta=$Cuenta
							WHERE numero_orden_id=$orden;";
		$result=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al actualizar numerodecuenta tmp_cuenta_imd";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		return true;*/
    $query = "SELECT c.numero_orden_id
              FROM (SELECT a.orden_servicio_id   
                    FROM os_maestro a
                    WHERE a.numero_orden_id=".$orden."
                    ) a,os_maestro b,tmp_cuenta_imd c
              WHERE a.orden_servicio_id=b.orden_servicio_id AND b.numero_orden_id=c.numero_orden_id;";
    $result=$dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
        $this->error = "Error al Guardar en la Base de Datos";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
    }else{
      while(!$result->EOF)
      {
          $var[]= $result->GetRowAssoc($ToUpper = false);
          $result->MoveNext();
      }
      for($i=0;$i<sizeof($var);$i++){
        $query = "UPDATE tmp_cuenta_imd 
                SET numerodecuenta=$Cuenta
                WHERE numero_orden_id=".$var[$i]['numero_orden_id'].";";              
        $result=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al actualizar numerodecuenta tmp_cuenta_imd";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
      }
      return true;
    }
    return false;
	}
	
	
  /**
  * EMPEZAMOS AQUI
  */
  function GuardarTodosCargosIyM()
  {
      $Cuenta=$_SESSION['OS_ATENCION']['cuenta'];
      $Ingreso=$_SESSION['OS_ATENCION']['ingreso'];
      $PlanId=$_SESSION['Os_Atencion']['PlanId'];
      $Nivel=$_REQUEST['Nivel'];
      $TipoId=$_SESSION['OS_ATENCION']['tipo'];
      $PacienteId=$_SESSION['OS_ATENCION']['id'];
      $Fecha=date('Y-m-d');

      $this->ActualizarTmp_cuenta_imd($_REQUEST['orden'],$Cuenta);

      list($dbconn) = GetDBconn();
      $query = "SELECT count(a.numerodecuenta)
      FROM tmp_cuenta_imd as a WHERE a.numerodecuenta=$Cuenta";
      $result=$dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Guardar en la Base de Datos";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
      }

      if($result->fields[0]==0)
      {
          $this->frmError["MensajeError"]="NO HA AGREGADO NINGUN INSUMO.";
          if(!$this->LiquidacionOrden()){
            return false;
          }
          return true;
      }
        
      $argu=array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
//      print_r($argu);

      $_SESSION['OS_ATENCION']['RETORNO']['contenedor']='app';
      $_SESSION['OS_ATENCION']['RETORNO']['modulo']='Os_Atencion';
      $_SESSION['OS_ATENCION']['RETORNO']['tipo']='user';
      $_SESSION['OS_ATENCION']['RETORNO']['metodo']='RetornoInsumos';
      $_SESSION['OS_ATENCION']['RETORNO']['argumentos']=$argu;
      $_SESSION['OS_ATENCION']['CUENTA']=$Cuenta;						
      $this->ReturnMetodoExterno('app','InvBodegas','user','LiquidarIYMOrdenServicio');						 
      
      return true;
  }

  /**
  *
  */
  function RetornoInsumos()
  {
      $Cuenta=$_REQUEST['Cuenta'];
      $Nivel=$_REQUEST['Nivel'];
      $PlanId=$_REQUEST['PlanId'];
      $Ingreso=$_REQUEST['Ingreso'];
      $Fecha=$_REQUEST['Fecha'];
      $TipoId=$_REQUEST['TipoId'];
      $PacienteId=$_REQUEST['PacienteId'];
      if($_SESSION['OS_ATENCION']['RETORNO']['Bodega']===true)
      {
            unset($_SESSION['OS_ATENCION']['RETORNO']);
            $mensaje='Los Documentos de Bodega han sido Creados Satisfactoriamente.';
            $accion=ModuloGetURL('app','Os_Atencion','user','LiquidacionOrden',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado));
            if(!$this-> FormaMensaje($mensaje,'CREAR DOCUMENTO',$accion,'Aceptar')){
            return false;
            }
            return true;
      }
      else
      {
						
            $mensaje=$_SESSION['OS_ATENCION']['RETORNO']['Mensaje_Error'].'<br>';
						unset($_SESSION['OS_ATENCION']['RETORNO']);                        
            $accion=ModuloGetURL('app','Os_Atencion','user','LiquidacionOrden',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado));
            if(!$this-> FormaMensaje($mensaje,'ERROR AL CREAR EL DOCUMENTO',$accion,'Aceptar')){            return false;
            }
            return true;
      }
  }

//FIN INSERTAR INSUMOS Y MEDICAMENTOS
//***********************************************************

    /*funcion que deja listo al paciente para que sea visto en las hojas de trabajo*/
    function InsertCumplimiento_Y_Detalle($dpto,$norden,$tipoId,$PacId,&$dbconn,$serial,$user,$cargo='',$orden_servicio)
    {
      //list($dbconn) = GetDBconn();
      
      $sw_auto = array();
      if(!empty($cargo))
      {
        /*$query = "SELECT	sw_tomado_automatico,
                          interface_externo
                  FROM		departamentos_cargos
                  WHERE		departamento = '".$_SESSION['LABORATORIO']['DPTO']."'
                  AND     cargo =  '".$cargo."'";
        
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
                echo $this->error = "ERROR AL TRAER SW DE AUTOMATICOS DE DEPTOS CARGO.";
                echo $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
        }
        while (!$result->EOF) 
        {
                        $sw_auto=$result->GetRowAssoc($ToUpper = false);
                        $result->MoveNext();
        }*/
		
        if($this->tomados[$cargo]['sw_tomado_automatico']=='1')
        {
            $campo= ",sw_estado";
            $valor= ",'1',";
			$campo1= ",fecha_tomado";
			$campo2= ",usuario_tomado";
			$valor1= "now()";
			$valor2= UserGetUID();
			$valor3= ",";
        }
      }
      if(empty($user))
      {
        $query="INSERT INTO os_cumplimientos_detalle
                  (
                    numero_orden_id,
                    numero_cumplimiento,
                    fecha_cumplimiento,
                    departamento,
                    hora_cumplimiento
                    ".$campo."
					".$campo1."
					".$campo2."
                  )
                  VALUES
                  (
                    '".$norden."',
                     ".$serial.",
                    '".date("Y-m-d")."',
                    '".$dpto."',
                    '".$this->horaCumplimiento."'
                     ".$valor."
					 '".$valor1."'
					 ".$valor3."
					 ".$valor2."
                  )";
      }
      else
      {
          $query="INSERT INTO os_cumplimientos_detalle
                    (
                      numero_orden_id,
                      numero_cumplimiento,
                      fecha_cumplimiento,
                      departamento,
                      usuario_id,
                      hora_cumplimiento
                      ".$campo."
					  ".$campo1."
					  ".$campo2."
                    )
                    VALUES
                    (
                      '".$norden."',
                       ".$serial.",
                      '".date("Y-m-d")."',
                      '".$dpto."',
                       ".$user.",
                      '".$this->horaCumplimiento."'
                       ".$valor."
					   '".$valor1."'
					   ".$valor3."
					   ".$valor2."
                    )";
      }
	  
	     $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) 
      {
        $this->error = "Error al insertar en os_cumplimientos_detalle";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        $dbconn->RollbackTrans();
        return false;   
      }
      $cod_cump = date("Ymd").$serial.$dpto;
      $dtlb = ModulogetVar('app','Os_Atencion','datalab');
      if(trim($dtlb) == "true")
      {
        if(!empty($cargo) && $this->tomados[$cargo]['interface_externo'] == '1')
  			{
          if(!$sql = $this->ObtenerSqlSolicitarDatalab($cod_cump,$norden,$orden_servicio,$cargo,$tipoId,$PacId,$this->horaCumplimiento))
            return false;
          if($sql != "")
          {	
            $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) 
            {
              $this->error = "Error al insertar en os_cumplimientos_detalle";
              echo $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              $dbconn->RollbackTrans();
              return false;   
            }
            //$query="SELECT actualizar_estado_os_datalab(".$orden_servicio.")";
            //$dbconn->Execute($query);
          }
        }
      }
      return true;
    }
    /**
    * Funcion donde se obtiene el sql para insertar en las tablas de .
    *
    * @param string $numero_orden_id Identificador del numero de orden
    * @param string $orden_servicio_id Identificador de la orden de servicio
    * @param string $cargo Identificador del cargo
    * @param string $tipo_id_paciente Tipo de identificacion del paciente
    * @param string $paciente_id Numero del documento del paciente
    * @param string $horaCumplimiento Hora en la que se da el cumplimieto
    *
    * @return string
    */
    function ObtenerSqlSolicitarDatalab($cod_cump,$numero_orden_id,$orden_servicio_id,$cargo,$tipo_id_paciente,$paciente_id,$horaCumplimiento)
    {
    	$a = explode(":",$horaCumplimiento);
      
      $turno = "NOCHE";
      if($a[0] == 14 || $a[0] == 20 && intval($a[1]) == 0)
        $a[0] = $a[0]-1;
        
      if(intval($a[0]) >= 6 && intval($a[0]) < 14)
        $turno = "MAÑANA";
      else if(intval($a[0]) >= 14 && intval($a[0]) <= 20)
        $turno = "TARDE";
      
      $codigo_examen = Get_CodigoDatalab($cargo);
      if(empty($codigo_examen))
        return '';
    	
      $parentesco = $puntoAtencion = $proveedor = "";
      $centro1 = SessionGetVar('LABORATORIO');
      
	  //$centro['centro_utilidad'] = $centro['CENTROUTILIDAD'];
      $centro = Get_OS_Deptarmento($orden_servicio_id);
      $centro['empresa_id'] = $centro1['EMPRESA_ID'];
      
      $centroUtilidad = Get_CentroUtilidad($centro);
      $tarifa = Get_Tarifa($numero_orden_id);
      $pagador = Get_Pagador($numero_orden_id);
    	$servicio = Get_Servicio($numero_orden_id);
      $servicio2 = Get_Servicio2($numero_orden_id);
      
      if(!empty($servicio2)){
        $servicio['equivalencia'] = $servicio2['equivalencia'];
		$servicio['descripcion'] = $servicio2['descripcion'];
      }
      
      else{
        $servicio['equivalencia'] = $servicio['equivalencia'];
      }
    	$datos_paciente = Get_Datos_Paciente($tipo_id_paciente, $paciente_id);
		
      $datos_solicitud = Get_Datos_Solicitud($numero_orden_id);
    	$tipo_identificacion = Get_DatalabTiposIdentificacion($tipo_id_paciente);
    	$valor_cargo = Get_ValorCargo($numero_orden_id,$cargo);
      $tipo_afiliado = Get_TipoAfiliado($datos_solicitud['tipo_afiliado_id']);
      $sexo = Get_Sexo($datos_paciente[sexo_id]);
      $zona = Get_DatalabZonas($datos_paciente[zona_residencia]);
      $afiliado = Get_DatosAfiliado($paciente_id, $tipo_id_paciente);
      if($afiliado['parentesco_id']){
        $parentesco = Get_Parentesco($afiliado['parentesco_id']);
      }
      else{
        $parentesco = '1';
      }
      if($afiliado['eps_punto_atencion_id']){
        $puntoAtencion = Get_PuntosAtencion($afiliado['eps_punto_atencion_id']);
      }
      else{
        $puntoAtencion = Get_UsuarioAtencion($paciente_id, $tipo_id_paciente);
      }
      if(is_numeric($datos_solicitud['prestador']))
        $proveedor = Get_Proveedor($datos_solicitud['prestador']);
      
      $hc = $paciente_id;
            
      /*$pagador[plan_id]=str_pad($pagador[plan_id],4,0,STR_PAD_LEFT);
      $pagador[plan_id]= 'SIIS'.$pagador[plan_id];*/
      
      $pagador[plan_id]= $pagador[equivalencia];

      if ($servicio[servicio] == '3')
        //$tipo_orden = 'R';
		$tipo_orden = '1';
      else
        //$tipo_orden = 'U';
		$tipo_orden = '2';

      if ($datos_solicitud[cama] == '')
        $cama = Get_Cama($numero_orden_id);
      else
        $cama = $datos_solicitud[cama];
    		
      
	  //cargarmos profesional con el nombre del medico que hizo la solicitud(ya sea manual o de evolucion)
      if ($datos_solicitud[usuario_id]!='' AND $datos_solicitud[profesional] == '')
        $profesional = $datos_solicitud[nombre];
      else
        $profesional = $datos_solicitud[profesional];
    	
      $profesional_id = Get_IdentificacionProfesional($datos_solicitud[usuario_id]);
      $medico = Get_Profesional($datos_solicitud[usuario_id],$profesional);
      
      
      //si es manual o no encuentra equivalencia medico se va vacio, sino medico se carga con la equivalencia.
      //$medico = Get_Medico($datos_solicitud[usuario_id]);
      /*if($datos_solicitud[usuario_id]!='')
        $medico = 'MUID'.$datos_solicitud[usuario_id];
      else
        $medico = 'MUIDNULL';*/
      
      $sql = "";
      $fn = (empty($datos_paciente['fecha_nacimiento']))? "NULL" : "'".$datos_paciente['fecha_nacimiento']."'";
      foreach($codigo_examen as $key => $dtll)
      {
        $sql .= " INSERT INTO interface_datalab_solicitudes (";
        $sql .= "        hc,"; //ok
        $sql .= "        apellido,";//ok
        $sql .= "        nombre,";//ok
        $sql .= "        sexo,";//ok
        $sql .= "        fecha_nacimiento,";//ok
        $sql .= "        hc1,";//ok
        $sql .= "        hc2,";//ok
        $sql .= "        hc3,";//ok
        $sql .= "        hc4,";//ok
        $sql .= "        hc5,";//ok
        $sql .= "        hc6,";//ok
        $sql .= "        fecha_hora_envio,"; //ok
        $sql .= "        orden1,"; //ok
        $sql .= "        orden2,";//ok
        $sql .= "        orden3,";//ok
        $sql .= "        orden4,";//ok
        $sql .= "        orden5,";//ok
        $sql .= "        orden6,";//ok
        $sql .= "        orden7,";//ok
        $sql .= "        orden13,";//ok
        $sql .= "        tipo_orden,";//ok
        $sql .= "        ordcomentario,";//ok
        $sql .= "        codigo_examen,";//ok
        $sql .= "        orden1_char,";//ok
        $sql .= "        orden2_char,";//ok
        $sql .= "        orden3_char,";//ok
        $sql .= "        orden6_char,";//ok
        $sql .= "        orden10,";//ok
        $sql .= "        orden14,";//ok
        $sql .= "        orden15,";
        $sql .= "        empresa_id ";
        $sql .= "       ) ";//ok
        $sql .= "VALUES(";
        $sql .= "        '".$hc."',";//ok
        $sql .= "        '".$datos_paciente['apellidos']."',"; //ok
        $sql .= "        '".$datos_paciente['nombres']."',";//ok
        $sql .= "        '".$sexo."',";//ok
        $sql .= "         ".$fn.",";//ok
        //$sql .= "        '".$tipo_identificacion."',";
        $sql .= "        '".$tipo_id_paciente."',";//ok
        $sql .= "        '".$tipo_afiliado."',";//ok
        $sql .= "        '".$parentesco."',";//ok
        $sql .= "        '".$datos_paciente['residencia_direccion']."',";//ok
        $sql .= "        '".$datos_paciente['residencia_telefono']."',";//ok
        $sql .= "        '".$datos_paciente['email']."',";//ok
        //$sql .= "        '".$zona."',";
        $sql .= "        NOW(),";//ok
        $sql .= "        '".$centroUtilidad['datalab_centro_utilidad']."',";//ok
        $sql .= "        '".$servicio['equivalencia']."',";//ok
        $sql .= "        '".$pagador['plan_id']."',";//ok
        /*$sql .= "        '".$proveedor."',"; //ok*/
        $sql .= "        '1',"; //ok
        $sql .= "        '".$puntoAtencion."',"; //ok (parametrizar -> interface_datalab_puntos_atencion )
        $sql .= "        '".$medico['equivalencia']."',";//ok
        $sql .= "        '".$cama."',"; //ok
        $sql .= "        '".$cod_cump."',"; // definir  variable de ingreso 
        $sql .= "        '".$tipo_orden."',"; // validar variable de ingreso
        $sql .= "        '".$datos_solicitud['observacion']."',"; //ok
        $sql .= "        '".$dtll['codigo_datalab']."',"; //ok
        $sql .= "        '".$centroUtilidad['descripcion']."',"; //ok
        $sql .= "        '".$servicio['descripcion']."',";//ok
        $sql .= "        '".$pagador['plan_descripcion']." ".$pagador['rango']."',";//ok
        $sql .= "        '".$medico['nombre']."',";//ok
        $sql .= "        '".$valor_cargo['precio']."',";//ok
        $sql .= "        '".$orden_servicio_id."',";//ok
        $sql .= "        '".$numero_orden_id."', ";
        $sql .= "        '".$centro['empresa_id']."' ";
        $sql .= "       ); ";//ok
		
      }
      
    	return $sql;
    }
		/**
		* Esta funcion cambia el estado de la orden de pagada a cumplida.
		* Se modifico para que este proceso se pudiera hacer en forma automatica
		* @param $accion Cuando este parametro llega en verdadero significa que se esta
		*									llamando para hacer el cumplimiento en forma automatica y se
		*									deben tomar los valores que legan por paramtro y no por request
		* @param $retorno Si el retorno es true se tomo el retorno de este metodo, en caso
		*									contrario se toma el retorno del metodo que lo llamo
		*/
    function CambiarEstadoACumplimiento($nom='',$id_tipo='',$id='',$vect='',$op='',$accion=0,$retorno=1)
    {
   //MauroB
			if($accion==0)
			{
				$nom=$_REQUEST['nom'];
				$id_tipo=$_REQUEST['id_tipo'];
				$id=$_REQUEST['id'];
				$vect=$_REQUEST['vect'];
				$op=$_REQUEST['op'];
				$retorno=$_REQUEST['retorno'];
			}
			if(empty($retorno))$retorno=1;
			//esto toco hacerlo asi por que el callmetodoexterno no me esta pasando los parametros
			if($_SESSION['OS_ATENCION']['sw']==1)
			{
				$nom=$_SESSION['OS_ATENCION']['nom'];
				$id_tipo=$_SESSION['OS_ATENCION']['tipo'];
				$id=$_SESSION['OS_ATENCION']['id'];
				$vect=sizeof($_SESSION['OS_ATENCION']['op']);
				$op=$_SESSION['OS_ATENCION']['op'];
				$retorno=0;
				unset($_SESSION['OS_ATENCION']['sw']);
			}

      list($dbconn) = GetDBconn();
      
      $query="SELECT secuencia_os_cumplimiento_restringido('".$_SESSION['LABORATORIO']['DPTO']."')";
        //fin MauroB
      $result=$dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) 
      {
        $this->error = "Error al insertar en cuentas_detalle";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        $dbconn->RollbackTrans();
        return false;   
      }
      $this->horaCumplimiento = date('H:i');
      $serial=$result->fields[0];//generamos el numero de cumplimiento

                $query = "INSERT INTO os_cumplimientos
                          (
                            numero_cumplimiento,
                            fecha_cumplimiento,
                            departamento,
                            tipo_id_paciente,
                            paciente_id,
                            hora_cumplimiento
                          )
                          VALUES
                          (
                            ".$serial.",
                            '".date("Y-m-d")."',
                            '".$_SESSION['LABORATORIO']['DPTO']."',
                            '".$id_tipo."',
                            '".$id."',
                            '".$this->horaCumplimiento."'
                          )";
						  
            $dbconn->StartTrans();
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al insertar en os_cumplimientos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;   }
        $contador=0;
				$r=0;
        //for($r=0;$r<$vect;$r++)
        IncludeLib('funciones_datalab');
				$cadena = "";
        foreach($op as $campo => $valor)
        {
					if(!empty($valor))
          {
            $val = explode(",",$valor);
            if($val[1])
              $cadena .= (($cadena == "")? "":",")."'".$val[1]."'";  
          }
        }
        
        $sql  = "SELECT	cargo, ";
        $sql .= "       sw_tomado_automatico, ";
        $sql .= "       interface_externo ";
        $sql .= "FROM		departamentos_cargos ";
        $sql .= "WHERE	departamento = '".$_SESSION['LABORATORIO']['DPTO']."' ";
        $sql .= "AND    cargo IN (".$cadena.") ";
        
        $result = $dbconn->Execute($sql);
        
        if ($dbconn->ErrorNo() != 0) 
        {
          echo $this->error = "ERROR AL TRAER SW DE AUTOMATICOS DE DEPTOS CARGO.";
          echo $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $dbconn->RollbackTrans();
          return false;
        }
        $this->tomados = array();
        while (!$result->EOF) 
        {
          $this->tomados[$result->fields[0]] = $result->GetRowAssoc($ToUpper = false);
          $result->MoveNext();
        }
        
        $result->Close();
        
        foreach($op as $campo => $valor)
        {
					if(empty($valor))
          {
            $contador=$contador + 1;
          }
          else
          {
            if($_REQUEST['profe'][$r]==-1 and !empty($valor))
            {
              $this->frmError["MensajeError"] = "DEBE ASIGNAR UN ESPECILISTA";
              $this->FrmOrdenar($nom,$id_tipo,$id);
              $dbconn->RollbackTrans();
              return true;
            }
            
            $valores=explode(",",$valor);
			$ordenServicio = $valores[9];
            $query="UPDATE os_maestro SET sw_estado='3' where numero_orden_id='$valores[0]'
                    AND sw_estado ='2' AND orden_servicio_id='$valores[9]'";
			
            $dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0) 
            {
              $this->error = "Error al actualizar en os_maestro2";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              $dbconn->RollbackTrans();
              return false;
            }
			
			
			
            $this->InsertCumplimiento_Y_Detalle($_SESSION['LABORATORIO']['DPTO'],$valores[0],$id_tipo,$id,$dbconn,$serial,$_REQUEST['profe'][$r],$valores[1],$valores[9]);
			
		 }
        }//ECHO "XXX".$_REQUEST['vect'];
        //echo "-->".$contador;
		
        if($vect==$contador)
        {
                $this->frmError["MensajeError"] = "DEBE SELECCIONAR LA CASILLA";
                $this->FrmOrdenar($nom,$id_tipo,$id);
                $dbconn->RollbackTrans();
                return true;
        }
        else
        {
          $dbconn->CompleteTrans();   //termina la transaccion
          /*$query="SELECT actualizar_estado_os_datalab($valores[9])";
          $dbconn->Execute($query);*/
					if($retorno==1){
						$this->FrmOrdenar($nom,$id_tipo,$id);
					}
        }
        return true;
    }


    function TraerNombreTarifario($tarifario_id,$cargo)
    {
                list($dbconn) = GetDBconn();
                $query="SELECT descripcion FROM tarifarios_detalle
                                WHERE tarifario_id='$tarifario_id' AND cargo='$cargo'";
                $resulta=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0){
                    $this->error = "Error al listar las empresas";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }

                return $resulta->fields[0];
    }


function ReporteFichaLaboratorio()
        {
                if (!IncludeFile("classes/reports/reports.class.php"))
                {
                        $this->error = "No se pudo inicializar la Clase de Reportes";
            $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
                        return false;
        }
                $AND=" ";
                $search="";
            //  print_r($_REQUEST['sel']);exit;
                $arr=$_REQUEST['sel'];
                if(sizeof($arr)>0)
                {       $search="";
                        $union= "";
                        for($k=0;$k<sizeof($arr);$k++)
            {
                            if($k==0)
                            {
                                $union = ' and  (';
                            }
                            else
                            {

                                $union = ' or ';
                            }
                            $search.= "$union a.numero_orden_id= ".$arr[$k]."";
                        }
                        $search.=")";
                }
                else
                {       $AND=" ";
                        $search="";
                }

                 list($dbconn) = GetDBconn();

                $query = "SELECT c.historia_prefijo, c.historia_numero,
                a.numero_cumplimiento, a.departamento,
                a.tipo_id_paciente, a.paciente_id, a.fecha_cumplimiento,
                btrim(b.primer_nombre||' '||b.segundo_nombre||' '|| b.primer_apellido||
                ' '||b.segundo_apellido,'') as nombre
                FROM os_cumplimientos a
                left join historias_clinicas c on (a.paciente_id = c.paciente_id
                AND a.tipo_id_paciente =    c.tipo_id_paciente), pacientes b

                WHERE  a.paciente_id = b.paciente_id AND a.tipo_id_paciente =
                b.tipo_id_paciente AND a.numero_cumplimiento = ".$_REQUEST['numero']."
                AND a.fecha_cumplimiento = '".$_REQUEST['fecha_cumplimiento']."'
                AND a.departamento = '".$_SESSION['LABORATORIO']['DPTO']."'

        order by a.fecha_cumplimiento, a.numero_cumplimiento";

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


                $var[0][razon_social]=$_SESSION['LABORATORIO']['NOM_EMP'];


              $query = "SELECT a.numero_orden_id, b.sw_estado, e.tipo_os_lista_id, e.nombre_lista, c.cargo, c.descripcion,
                b.hc_os_solicitud_id, b.orden_servicio_id
                FROM os_cumplimientos_detalle as a, os_maestro as b, cups as c left join
                tipos_os_listas_trabajo_detalle as d on (c.grupo_tipo_cargo = d.grupo_tipo_cargo
                AND c.tipo_cargo = d.tipo_cargo) left join tipos_os_listas_trabajo as e
                on (d.tipo_os_lista_id = e.tipo_os_lista_id)
                WHERE a.numero_cumplimiento = ".$var[0][numero_cumplimiento]." AND
                a.fecha_cumplimiento = '".$var[0][fecha_cumplimiento]."' AND
                a.departamento = '".$var[0][departamento]."' AND a.numero_orden_id = b.numero_orden_id
                AND b.cargo_cups = c.cargo
                AND a.departamento = e.departamento $search
                order by e.tipo_os_lista_id, a.numero_orden_id asc";

                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al buscar en la consulta de medicamentos recetados";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
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
                $var[0][cargos]=$vector;

                //--------------cambio dar departamentos

                $query = "SELECT c.descripcion, b.departamento
                                    FROM  hc_os_solicitudes as a, hc_evoluciones as b, departamentos as c
                                    WHERE a.hc_os_solicitud_id=".$vector[0][hc_os_solicitud_id]."
                                    and a.evolucion_id=b.evolucion_id and b.departamento=c.departamento";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al buscar en la consulta de medicamentos recetados";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }

                if(!$result->EOF)
                {       //es desde la hc
                        $var[0][nombre_dpto]=$result->fields[0];
                }
                else
                {
                        $query = "SELECT a.departamento
                                            FROM hc_os_solicitudes_manuales_datos_adicionales as a
                                            WHERE a.orden_servicio_id=".$vector[0][orden_servicio_id]."";
                        $results = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->error = "Error al buscar en la consulta de medicamentos recetados";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            return false;
                        }
                        if(!$results->EOF)
                        {       //es desde la hc
                                $var[0][nombre_dpto]=$results->fields[0];
                        }
                }
                //----------fin cambio dar

                unset($vector);

                $classReport = new reports;
                $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
        $reporte=$classReport->PrintReport($tipo_reporte='pos',$tipo_modulo='app',$modulo='Os_Atencion',$reporte_name='rotulo_laboratorio_lt',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
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
                //$this->ListadoPacientesEvolucionCerrada();
                $this->FrmOrdenar($_REQUEST['nom'],$_REQUEST['tipoid'],$_REQUEST['id']);
        return true;
        }






////////////lo de lorena que esta insertarndo claudia
function LlamaProgramCitasImagen()
{
    $_SESSION['citas']['nombre']=$_REQUEST['nombre'];
        $_SESSION['citas']['tipo']=$_REQUEST['tipo_id_paciente'];
        $_SESSION['citas']['id']=$_REQUEST['paciente_id'];
        $_SESSION['citas']['tipo_equipo']=$_REQUEST['tipo_equipo'];
        //echo 'in'.$_REQUEST['numero_orden_id'];
        $this->ProgramacionCitasImagen($_REQUEST['numero_orden_id'],$_REQUEST['tipo_id_paciente'], $_REQUEST['paciente_id']);
        return true;
}

function TiposEquiposImagen()
    {
    list($dbconn) = GetDBconn();
      $query="SELECT tipo_equipo_imagen_id,descripcion FROM os_imagenes_tipo_equipos";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
      $datos=$result->RecordCount();
            if($datos){
        while(!$result->EOF) {
                    $var[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                }
            }
        }
        return $var;
    }
    function SeleccionEquiposImagen()
    {//echo "<br>------>>>".$_REQUEST['numerOrdenId'];
      if($_REQUEST['Salir'])
        {
            $this->FrmOrdenar($_SESSION['citas']['nombre'],$_SESSION['citas']['tipo'], $_SESSION['citas']['id']);
            return true;
        }
        if (!$_REQUEST['tipoEquipo'])
        {
      $this->frmError["MensajeError"]="DEBE SELECCIONAR UN EQUIPO.";
            $this->ProgramacionCitasImagen($_REQUEST['numerOrdenId'],$_REQUEST['tipoIdPaciente'],$_REQUEST['PacienteId']);
            return true;
        }
    $this->ReservaEquipoTurno($_REQUEST['tipoEquipo'],$_REQUEST['numerOrdenId'],$_REQUEST['tipoIdPaciente'],$_REQUEST['PacienteId']);
        return true;
    }

    function SeleccionEquiposTipo($tipoEquipo)
    {
      list($dbconn) = GetDBconn();
      $query="SELECT equipo_imagen_id,descripcion FROM os_imagenes_equipos
        WHERE estado='1' AND tipo_equipo_imagen_id='$tipoEquipo' order by equipo_imagen_id";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
      $datos=$result->RecordCount();
            if($datos){
        while(!$result->EOF) {
                    $var[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                }
            }
        }
        return $var;
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
            if($fecha){
                    $fech = strtok ($fecha,"-");
                    for($l=0;$l<3;$l++)
                    {
                        $date[$l]=$fech;
                        $fech = strtok ("-");
                    }
                    return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
            }
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

    function VerificarDisponibilidadEquipo($Rango,$Equipo,$rangoInterval)
    {
    list($dbconn) = GetDBconn();
        $timeInt=date("H:i:s",mktime(0,(0+$rangoInterval),0,date("m"),date("d"),date("Y")));
      $query="SELECT disponibilidad_equipo_imagen('$Equipo','$Rango','$timeInt')";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
      $respuesta=$result->fields[0];
      if($respuesta=='t'){
              $dispocision=0;
            }else{
        $dispocision=1;//DISPONIBLE
            }
        }
        $result->Close();
        return $dispocision;
  }

    function VerificarDisponibilidadEquipoCitas($Rango,$Equipo,$rangoInterval){

        list($dbconn) = GetDBconn();
        $timeInt=date("H:i:s",mktime(0,(0+$rangoInterval),0,date("m"),date("d"),date("Y")));
        $query="SELECT c.tipo_id_paciente,c.paciente_id,d.primer_apellido,d.segundo_apellido,
        d.primer_nombre,d.segundo_nombre,residencia_telefono
        FROM os_imagenes_citas a,os_maestro b,os_ordenes_servicios c,pacientes d
        WHERE a.equipo_imagen_id='$Equipo' AND a.estado<>'2' AND
        '$Rango' >= a.fecha_hora_cita AND '$Rango' < ((a.fecha_hora_cita + a.duracion)) AND
        a.numero_orden_id=b.numero_orden_id AND b.orden_servicio_id=c.orden_servicio_id AND
        c.tipo_id_paciente=d.tipo_id_paciente AND c.paciente_id=d.paciente_id";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $datos=$result->RecordCount();
            if($datos){
                $var=$result->GetRowAssoc($ToUpper = false);
            }
        }
        $result->Close();
        return $var;
  }

    function CrearReservaCitaImagen()
    {
    if ($_REQUEST['VOLVER'])
    {
        $this->ProgramacionCitasImagen($_REQUEST['numerOrdenId'],$_REQUEST['tipoIdPaciente'], $_REQUEST['PacienteId']);
        return true;
    }
    if(sizeof($_REQUEST['HoraProgram'])<1)
    {
    $this->frmError["MensajeError"]="DEBE SELECCIONAR UN HORARIO PARA LA CITA.";
    $this->ReservaEquipoTurno($_REQUEST['tipoEquipo'],$_REQUEST['numerOrdenId'],$_REQUEST['tipoIdPaciente'], $_REQUEST['PacienteId']);
        return true;
    }
      $rangoInterval=$_REQUEST['rangoInterval'];
    $HoraProgram=$_REQUEST['HoraProgram'];
        $valorTmp=$HoraProgram[0];
        $cadena=explode('/',$valorTmp);
        $Equip=$cadena[0];
        $FechaIni=$cadena[1];
        $Fecha=$this->FechaStamp($FechaIni);
        $CadenaFechaIn = explode ('/', $Fecha);
        $HoraDef=$this->HoraStamp($FechaIni);
        $CadenaHoraIn = explode (':',$HoraDef);
        if(sizeof($HoraProgram)==1){
            $Fecha=$this->FechaStamp($FechaIni);
            $infoCadena = explode ('/', $Fecha);
            $dia=$infoCadena[0];
            $mes=$infoCadena[1];
            $ano=$infoCadena[2];
            $HoraDef=$this->HoraStamp($FechaIni);
            $infoCadena = explode (':',$HoraDef);
            $Hora=$infoCadena[0];
            $Minutos=$infoCadena[1];
            $rango=$_REQUEST['rango'];
            $duracionmin=(mktime($Hora,($Minutos+$rangoInterval),0,$mes,$dia,$ano)-mktime($CadenaHoraIn[0],$CadenaHoraIn[1],0,$CadenaFechaIn[1],$CadenaFechaIn[0],$CadenaFechaIn[2]))/60;
            $HorasDura=(int)($duracionmin/60);
            $HorasDura=str_pad($HorasDura,2,0,STR_PAD_LEFT);
      $MinutosDura=($duracionmin%60);
            $MinutosDura=str_pad($MinutosDura,2,0,STR_PAD_LEFT);
            $Duracion=$HorasDura.':'.$MinutosDura;
        }else{
            $cont=sizeof($HoraProgram)-1;
            $valorTmp=$HoraProgram[$cont];
            $cadena=explode('/',$valorTmp);
            $FechaFin=$cadena[1];
            $Fecha=$this->FechaStamp($FechaFin);
            $CadenaFechaFn = explode ('/', $Fecha);
            $HoraDef=$this->HoraStamp($FechaFin);
            $CadenaHoraFn = explode (':',$HoraDef);
            $duracionmin=(mktime($CadenaHoraFn[0],($CadenaHoraFn[1]+$rangoInterval),0,$CadenaFechaFn[1],$CadenaFechaFn[0],$CadenaFechaFn[2])-mktime($CadenaHoraIn[0],$CadenaHoraIn[1],0,$CadenaFechaIn[1],$CadenaFechaIn[0],$CadenaFechaIn[2]))/60;
            $HorasDura=(int)($duracionmin/60);
            $HorasDura=str_pad($HorasDura,2,0,STR_PAD_LEFT);
      $MinutosDura=($duracionmin%60);
            $MinutosDura=str_pad($MinutosDura,2,0,STR_PAD_LEFT);
            $Duracion=$HorasDura.':'.$MinutosDura.':'.'00';
        }
        list($dbconn) = GetDBconn();
        $query = "INSERT INTO os_imagenes_citas(numero_orden_id,tipo_profesional_id,
        profesional_id,equipo_imagen_id,fecha_hora_cita,duracion,estado,usuario_id,
        fecha_registro)VALUES('".$_REQUEST['numerOrdenId']."',NULL,NULL,'$Equip','$FechaIni','$Duracion','0','".UserGetUID()."',
        '".date("Y-m-d H:i:s")."')";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $dbconn->CommitTrans();
            $mensaje='Su Reserva ha sido Creada Exitosamente';
            $titulo='RESERVA CITA IMAGENES';
            $accion=ModuloGetURL('app','Os_Atencion','user','Llamado_Os_Atencion',array('nombre'=>$_SESSION['citas']['nombre'],'tipo_id_paciente'=>$_SESSION['citas']['tipo'],'paciente_id'=>$_SESSION['citas']['id']));
            $boton = "ACEPTAR";
            $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
            return true;
        }
        return true;
  }

    function Llamado_Os_Atencion()
{
        $this->FrmOrdenar($_REQUEST['nombre'],$_REQUEST['tipo_id_paciente'], $_REQUEST['paciente_id']);
        return true;
}


function AsignacionProfCitasImagen()
{
    if(!$_SESSION['citas_profesional'])
        {
            $_SESSION['citas_profesional']['nombre']=$_REQUEST['nombre'];
            $_SESSION['citas_profesional']['tipo']=$_REQUEST['tipo_id_paciente'];
            $_SESSION['citas_profesional']['id']=$_REQUEST['paciente_id'];
        }

        $this->FormaBusquedaCitaImagen();
        return true;
    }


    function SeleccionCitasPendientesImagen($DiaEspe){
    list($dbconn) = GetDBconn();
        $query="SELECT a.os_imagen_cita_id,a.fecha_hora_cita,a.numero_orden_id,c.tipo_id_paciente,c.paciente_id,d.primer_nombre,d.segundo_nombre,
        d.primer_apellido,d.segundo_apellido,e.descripcion as equipo,a.duracion,f.nombre
        FROM os_imagenes_citas a LEFT JOIN profesionales f ON (a.tipo_profesional_id=f.tipo_id_tercero AND a.profesional_id=f.tercero_id),os_maestro b,os_ordenes_servicios c,pacientes d,os_imagenes_equipos e
        WHERE a.numero_orden_id=b.numero_orden_id AND b.orden_servicio_id=c.orden_servicio_id AND
    c.tipo_id_paciente=d.tipo_id_paciente AND c.paciente_id=d.paciente_id AND
    a.equipo_imagen_id=e.equipo_imagen_id AND
        date(a.fecha_hora_cita)='$DiaEspe' AND a.estado='0' ORDER BY  a.equipo_imagen_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
      $datos=$result->RecordCount();
            if($datos){
        while(!$result->EOF){
                  $var[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                }
            }
        }
        $result->Close();
        return $var;
    }

    function AsignacionProfesionalCita(){
    $this->FormaAsignacionProfesionalCita($_REQUEST['citaId'],$_REQUEST['identificacion'],$_REQUEST['nombre'],
        $_REQUEST['equipo'],$_REQUEST['duracion'],$_REQUEST['numeroOrden'],$_REQUEST['fechaInicio']);
        return true;
    }

    /**
* Funcion que busca en los profesionales especialistas existentes en la base de datos
* @return array
*/
    function profesionalesEspecialistaTurnosImagen($fechaInicio,$duracion){

        list($dbconn) = GetDBconn();
        $query = "SELECT  DISTINCT x.tercero_id,x.nombre as nombre_tercero,x.tipo_id_tercero
        FROM profesionales x,profesionales_departamentos y
        WHERE x.tipo_id_tercero=y.tipo_id_tercero AND x.tercero_id=y.tercero_id AND y.departamento='".$_SESSION['LABORATORIO']['DPTO']."'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            if($result->EOF){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'profesionales' esta vacia ";
                return false;
            }
            $i=0;
            while (!$result->EOF) {
                $vars[$i]=$result->GetRowAssoc($toUpper=false);
              $result->MoveNext();
              $i++;
            }
        }
        $result->Close();
        return $vars;
    }

    function GuardarAsignacionProf(){
        if($_REQUEST['asignar']){
          if($_REQUEST['profesional']==-1){
        $this->frmError["MensajeError"]="Debe Elegir un profesional";
        $this->FormaAsignacionProfesionalCita($_REQUEST['citaId'],$_REQUEST['identificacion'],$_REQUEST['nombre'],$_REQUEST['equipo'],$_REQUEST['duracion'],$_REQUEST['numeroOrden']);
            return true;
            }
          list($dbconn) = GetDBconn();
            $cadena=explode('/',$_REQUEST['profesional']);
            $query="UPDATE os_imagenes_citas SET tipo_profesional_id='".$cadena[1]."',profesional_id='".$cadena[0]."' WHERE os_imagen_cita_id='".$_REQUEST['citaId']."'";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
        }
        $this->FormaBusquedaCitaImagen();
        return true;
    }

function Revision_Cita($numero_orden_id, $cargo)
{
    list($dbconnect) = GetDBconn();
     $query= " SELECT a.cargo, a.departamento, b.sw_cita, a.tipo_equipo_imagen_id
        FROM departamentos_cargos_citas a, os_imagenes_tipo_equipos b
        WHERE a.tipo_equipo_imagen_id = b.tipo_equipo_imagen_id
        AND a.cargo = '".$cargo."' and a.departamento = '".$_SESSION['LABORATORIO']['DPTO']."' ";
        $result = $dbconnect->Execute($query);
        if ($dbconnect->ErrorNo() != 0)
        {
            $this->error = "Error al crear subexamen generico";
            $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
            return false;
        }
        $a=$result->GetRowAssoc($ToUpper = false);
    if($a[sw_cita]=='1')
        {
            $query= " SELECT numero_orden_id FROM os_imagenes_citas WHERE numero_orden_id = ".$numero_orden_id."";
            $result = $dbconnect->Execute($query);
            if ($dbconnect->ErrorNo() != 0)
            {
                $this->error = "Error al crear subexamen generico";
                $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
                return false;
            }
            $b=$result->GetRowAssoc($ToUpper = false);
            $a[existe_cita]=$b;
        }
      return $a;
}
//MenuOs_Atencion
//-------------cambio darling SOLICITUD MANUAL

    function LlamarFormaBuscar()
    {
            unset($_SESSION['DATOS_PACIENTE']['PACIENTE']);
            unset($_SESSION['DATOS_PACIENTE']['DATOS']);

            list($dbconn) = GetDBconn();
            $sql="select nextval('asignacuentavirtual_seq')";
            $result = $dbconn->Execute($sql);
            $dato=$result->fields[0];
            if ($dbconn->ErrorNo() != 0)
            {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }

            $_SESSION['LABORATORIO']['SERIAL']=$dato;

            if(!$this->FormaBuscar()){
                    return false;
            }
            return true;
    }

    function responsables($plan_id)
    {
      list($dbconn) = GetDBconn();
	  
		if(!ModuloGetVar('', '', 'planes_x_centro_utilidad_'.$_SESSION['LABORATORIO']['EMPRESA_ID'].''))
		{
			$query  = "SELECT a.plan_id,";
			$query .= "       a.plan_descripcion,";
			$query .= "       a.tercero_id,";
			$query .= "       a.tipo_tercero_id ";
			$query .= "FROM   planes a ";
			$query .= "WHERE  a.fecha_final >= now() ";
			$query .= "AND    a.estado='1' ";
			$query .= "AND    a.fecha_inicio <= now() ";
			$query .= "AND    a.empresa_id='".$_SESSION['LABORATORIO']['EMPRESA_ID']."'";
		}
		else
		{
			$query  = " SELECT	a.plan_id, ";
			$query .=	"			a.plan_descripcion, ";
			$query .= "			a.tercero_id, ";
			$query .= "			a.tipo_tercero_id ";
			$query .= " FROM 	planes a, ";
			$query .= " 		planes_centro_utilidad b ";
			$query .= " WHERE 	a.fecha_final >= now() ";
			$query .= " AND 	a.estado=1 ";
			$query .= " AND 	a.fecha_inicio <= now() ";
			$query .= " AND     a.empresa_id = '".$_SESSION['LABORATORIO']['EMPRESA_ID']."' ";
			$query .= " AND     a.empresa_id = b.empresa_id ";
			$query .= " AND     b.centro_utilidad = '".$_SESSION['LABORATORIO']['CENTROUTILIDAD']."' ";
			$query .= " AND     a.plan_id = b.plan_id ";
		}
      
      if($plan_id)
        $query .= "AND    a.plan_id = ".$plan_id." ";
      
      $query .= "ORDER BY a.plan_descripcion";
      
      $result = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) 
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      if(!$result->EOF)
      {
              while (!$result->EOF)
              {
                      $var[]=$result->GetRowAssoc($ToUpper = false);
                      $result->MoveNext();
              }
      }
      $result->Close();
      return $var;
    }

    function BuscarCamposObligatorios()
    {
                list($dbconn) = GetDBconn();
                $query="SELECT campo,sw_mostrar,sw_obligatorio FROM pacientes_campos_obligatorios";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }

                while(!$result->EOF){
                        $var[$result->fields[0]]=$result->GetRowAssoc($ToUpper = false);
                        $result->MoveNext();
                }

                $result->Close();
                return $var;
    }
    		/**
		* Metodo de validacion de los datos del paciente
		* 
		* @return boolean
		*/
		function DatosPacienteModulo()
		{
      $request = $_REQUEST;
			
			$this->Documento = $request['Documento'];
			$this->TipoDocumento = $request['Tipo'];
			
			$datos = array();
			$datos['tipo_id_paciente'] = $request['Tipo'];
			if($request['Documento'])
				$datos['paciente_id'] = $request['Documento'];
			$datos['plan_id'] = $request['plan'];
			$datos['prefijo'] = $request['prefijo'];
			$datos['historia'] = $request['historia'];
			
			$_REQUEST['tipo_id_paciente'] = $request['Tipo'];
			$_REQUEST['paciente_id'] = $request['Documento'];
			$_REQUEST['plan_id'] = $request['plan'];
			$_REQUEST['prefijo'] = $request['prefijo'];
			$_REQUEST['historia'] = $request['historia'];
			$_REQUEST['afiliacion'] = true;
			
			$this->action['cancelar'] = ModuloGetURL('app','Os_Atencion','user','LlamarFormaBuscar');
			$this->action['volver'] = ModuloGetURL('app','Os_Atencion','user',"RetornoPaciente",$datos);
			return true;
		}
/*
    function BuscarPaciente()
    {
     
                    list($dbconn) = GetDBconn();
                    
                    $query = "SELECT sw_tipo_plan FROM planes
                                        WHERE estado='1' and plan_id=".$_REQUEST['plan']."
                                        and fecha_final >= now() and fecha_inicio <= now()";
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error al Cargar el Modulo";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    return false;
                    }
                    //1 soat
                    if($result->fields[0]==1)
                    {
                                $this->frmError["MensajeError"]="Los Planes Soat deben Realizar el proceso en la Central de Autorizaciones.";
                                $this->FormaBuscar();
                                return true;
                    }

                    

                    if(($_REQUEST['Tipo']=='AS' OR $_REQUEST['Tipo']=='MS') && !$_REQUEST['Documento'])
                    {  $_REQUEST['Documento']=$this->CallMetodoExterno('app','Pacientes','user','IdentifiacionNN');  }

                    if($_REQUEST['Tipo']==-1 AND !$_REQUEST['Documento'] AND !$_REQUEST['prefijo'] AND !$_REQUEST['historia'])
                    {
                                $this->frmError["MensajeError"]="DEBE ELEGIR CRITERIOS PARA LA BUSQUEDA.";
                                $this->FormaBuscar();
                                return true;
                    }

                    if($_REQUEST['prefijo'] OR $_REQUEST['historia'])
                    {
                                $query = "SELECT tipo_id_paciente, paciente_id FROM historias_clinicas
                                                    WHERE historia_numero='".$_REQUEST['historia']."'
                                                    AND historia_prefijo='".strtoupper($_REQUEST['prefijo'])."'";
                                $result=$dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                                $this->error = "Error al Guardar SELECT en historias_clinicas";
                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                return false;
                                }
                                if($result->EOF)
                                {
                                        $this->frmError["MensajeError"]="LA HISTORIA NO EXISTE.";
                                        $this->FormaBuscar();
                                        return true;
                                }
                                else
                                {
                                            $_REQUEST['Documento']=$result->fields[1];
                                            $_REQUEST['Tipo']=$result->fields[0];
                                }
                                $result->Close();
                    }
                    elseif($_REQUEST['Tipo']==-1 OR !$_REQUEST['Documento'])
                    {
                                if($_REQUEST['Tipo']==-1){ $this->frmError["Tipo"]=1; }
                                if(!$_REQUEST['Documento']){ $this->frmError["Documento"]=1; }
                                $this->frmError["MensajeError"]="PARA BUSCA POR DOCUMENTOS DEBE DIGITAR LAS DOS OPCIONES.";
                                $this->FormaBuscar();
                                return true;
                    }
										//Si no biene plan el sistema busca si ya existe uno creado u 
										//lo asigna por defecto
										if($_REQUEST['plan']==-1)
                    {
												$query = "
																	SELECT	B.plan_id, C.plan_descripcion
																	FROM	ingresos A,
																				cuentas B,
																				planes C
																	WHERE	A.tipo_id_paciente = 	'".$_REQUEST['Tipo']."'
																				AND A.paciente_id =	'".$_REQUEST['Documento']."'
																				AND A.estado = '1'	
																				AND A.ingreso = B.ingreso
																				AND B.estado IN ('1','2')
																				AND B.plan_id = C.plan_id
												";
												$result=$dbconn->Execute($query);
												if ($dbconn->ErrorNo() != 0) {
													$this->error = "Error al Guardar SELECT en historias_clinicas";
													$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
													return false;
												}
												else
												{
													$plan_id=$result->fields[0];
													$plan_descripcion=$result->fields[1];
													
														//Por dudas en el proceso solo se muestra el plan 
														//en el que esta el paciente en este momento
														if($Plan==-1){ $this->frmError["plan"]=1; }
														$this->frmError["MensajeError"]="El paciente tiene cuante creada con : ".$plan_descripcion;
														$this->FormaBuscar();
														return true;
								}
																
                    }

 			$_REQUEST['tipo_id_paciente'] = $_REQUEST['Tipo'];
			$_REQUEST['paciente_id'] = $_REQUEST['Documento'];
			$_REQUEST['plan_id'] = $_REQUEST['plan'];
      
      $pct = $this->ReturnModuloExterno('app','DatosPaciente','user');
			
      $datos['tipo_id_paciente'] = $_REQUEST['Tipo'];
			$datos['paciente_id'] = $_REQUEST['Documento'];
			$datos['plan_id'] = $_REQUEST['plan'];
      
      $action['cancelar'] = ModuloGetURL('app','Os_Atencion','user','LlamarFormaBuscar');
			$action['volver'] = ModuloGetURL('app','Os_Atencion','user','RetornoPaciente',$datos);

			$pct->SetActionVolver($action['volver']);
			$pct->FormaDatosPaciente($action);
			
			$this->SetJavaScripts("Ocupaciones");
			$this->salida = $pct->salida;
      
      return true;
    }*/
    
    function BuscarPaciente()
    {
    
      list($dbconn) = GetDBconn();
      
      $query = "SELECT sw_tipo_plan FROM planes
                          WHERE estado='1' and plan_id=".$_REQUEST['plan']."
                          and fecha_final >= now() and fecha_inicio <= now()";
      $result = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
                      $this->error = "Error al Cargar el Modulo";
                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                      return false;
      }
      //1 soat
      if($result->fields[0]==1)
      {
                  $this->frmError["MensajeError"]="Los Planes Soat deben Realizar el proceso en la Central de Autorizaciones.";
                  $this->FormaBuscar();
                  return true;
      }

      

      if(($_REQUEST['Tipo']=='AS' OR $_REQUEST['Tipo']=='MS') && !$_REQUEST['Documento'])
      {  $_REQUEST['Documento']=$this->CallMetodoExterno('app','Pacientes','user','IdentifiacionNN');  }

      if($_REQUEST['Tipo']==-1 AND !$_REQUEST['Documento'] AND !$_REQUEST['prefijo'] AND !$_REQUEST['historia'])
      {
                  $this->frmError["MensajeError"]="DEBE ELEGIR CRITERIOS PARA LA BUSQUEDA.";
                  $this->FormaBuscar();
                  return true;
      }

      if($_REQUEST['prefijo'] OR $_REQUEST['historia'])
      {
                  $query = "SELECT tipo_id_paciente, paciente_id FROM historias_clinicas
                                      WHERE historia_numero='".$_REQUEST['historia']."'
                                      AND historia_prefijo='".strtoupper($_REQUEST['prefijo'])."'";
                  $result=$dbconn->Execute($query);
                  if ($dbconn->ErrorNo() != 0) {
                                  $this->error = "Error al Guardar SELECT en historias_clinicas";
                                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                  return false;
                  }
                  if($result->EOF)
                  {
                          $this->frmError["MensajeError"]="LA HISTORIA NO EXISTE.";
                          $this->FormaBuscar();
                          return true;
                  }
                  else
                  {
                              $_REQUEST['Documento']=$result->fields[1];
                              $_REQUEST['Tipo']=$result->fields[0];
                  }
                  $result->Close();
      }
      elseif($_REQUEST['Tipo']==-1 OR !$_REQUEST['Documento'])
      {
                  if($_REQUEST['Tipo']==-1){ $this->frmError["Tipo"]=1; }
                  if(!$_REQUEST['Documento']){ $this->frmError["Documento"]=1; }
                  $this->frmError["MensajeError"]="PARA BUSCA POR DOCUMENTOS DEBE DIGITAR LAS DOS OPCIONES.";
                  $this->FormaBuscar();
                  return true;
      }
      //Si no biene plan el sistema busca si ya existe uno creado u 
      //lo asigna por defecto
      if($_REQUEST['plan']==-1)
      {
          $query = "
                    SELECT	B.plan_id, C.plan_descripcion
                    FROM	ingresos A,
                          cuentas B,
                          planes C
                    WHERE	A.tipo_id_paciente = 	'".$_REQUEST['Tipo']."'
                          AND A.paciente_id =	'".$_REQUEST['Documento']."'
                          AND A.estado = '1'	
                          AND A.ingreso = B.ingreso
                          AND B.estado IN ('1','2')
                          AND B.plan_id = C.plan_id
          ";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar SELECT en historias_clinicas";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          else
          {
            $plan_id=$result->fields[0];
            $plan_descripcion=$result->fields[1];
            
              //Por dudas en el proceso solo se muestra el plan 
              //en el que esta el paciente en este momento
              if($Plan==-1){ $this->frmError["plan"]=1; }
              $this->frmError["MensajeError"]="El paciente tiene cuante creada con : ".$plan_descripcion;
              $this->FormaBuscar();
              return true;
          }
                  
      }

 			$_REQUEST['tipo_id_paciente'] = $_REQUEST['Tipo'];
			$_REQUEST['paciente_id'] = $_REQUEST['Documento'];
			$_REQUEST['plan_id'] = $_REQUEST['plan'];
      
      $pct = $this->ReturnModuloExterno('app','DatosPaciente','user');
			
      $datos['tipo_id_paciente'] = $_REQUEST['Tipo'];
			$datos['paciente_id'] = $_REQUEST['Documento'];
			$datos['plan_id'] = $_REQUEST['plan'];
      
      $action['cancelar'] = ModuloGetURL('app','Os_Atencion','user','LlamarFormaBuscar');
			$action['volver'] = ModuloGetURL('app','Os_Atencion','user','RetornoPaciente',$datos);

			$pct->SetActionVolver($action['volver']);
			$pct->FormaDatosPaciente($action);
			
			$this->SetJavaScripts("Ocupaciones");
			$this->salida = $pct->salida;
      
      return true;
    }
/*
    function RetornoPaciente()
    {
    	$request = $_REQUEST;
      
      unset($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']);
      $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']=$_SESSION['PACIENTES']['PACIENTE']['ARREGLO'];
      unset($_SESSION['PACIENTES']['PACIENTE']['ARREGLO']);

      unset($_SESSION['DATOS_PACIENTE']);
                        //$_SESSION['DATOS_PACIENTE']['nombre']=$_REQUEST['nombre'];
      $_SESSION['DATOS_PACIENTE']['tipo_id'] = $request['tipo_id_paciente'];
      $_SESSION['DATOS_PACIENTE']['paciente_id'] = $request['paciente_id'];
                      //$_SESSION['DATOS_PACIENTE']['edad']= $_REQUEST['edad_paciente'];
      $_SESSION['DATOS_PACIENTE']['plan_id'] = $request['plan_id'];

      list($dbconn) = GetDBconn();

      $query = "SELECT plan_descripcion,sw_tipo_plan FROM planes
      WHERE plan_id=".$_SESSION['DATOS_PACIENTE']['plan_id']."";
      $result=$dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) 
      {
                                      $this->error = "Error al Guardar en la Base de Datos";
                                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                      return false;
      }
      $_SESSION['DATOS_PACIENTE']['plan_descripcion']=$result->fields[0];

      //unset($_SESSION['PACIENTES']);
      $this->PedirAutorizacion();
      return true;

    }*/
    function RetornoPaciente()
    {
			$request = $_REQUEST;
			
			if($request['plan_id'])
			{
				$_SESSION['DATOS_PACIENTE']['plan_id'] = $request['plan_id'];
				$_SESSION['DATOS_PACIENTE']['paciente_id'] = $request['paciente_id'];
				$_SESSION['DATOS_PACIENTE']['tipo_id'] = $request['tipo_id_paciente'];
			}
			$this->action1 = ModuloGetURL('app','Os_Atencion','user','FormaMetodoBuscar');
			$this->action2 = ModuloGetURL('app','Os_Atencion','user','RetornoAutorizacion');
						
			$this->AutorizarPaciente();
			return true;
    }
    /**
		* Llama el modulo de autorizaciones
    *
		* @param string tipo de documento
		* @param int numero de documento
		* @param int plan_id
		*
    * @return boolean		
    */
		function AutorizarPaciente($td = null,$doc= null,$plan= null)
		{
      $datos['idp'] = $_SESSION['DATOS_PACIENTE']['paciente_id'];
      $datos['tipoid'] = $_SESSION['DATOS_PACIENTE']['tipo_id'];
      $datos['plan_id'] = $_SESSION['DATOS_PACIENTE']['plan_id'];
			$datos['afiliado'] = $_REQUEST['afilia'];
			
			IncludeClass('Autorizaciones','','app','NCAutorizaciones');
					
			$aut = new Autorizaciones();
			$planes = $aut->ObtenerTiposPlanes($datos['plan_id']);
			$Autoriza = $this->ReturnModuloExterno('app','NCAutorizaciones','user');
			
			if($planes['sw_tipo_plan'] == '0' ||$planes['sw_tipo_plan'] == '1' ||$planes['sw_tipo_plan'] == '2' || $planes['sw_tipo_plan'] == '3')
			{
				$Autoriza->SetActionVolver($this->action1);
				$Autoriza->SetActionAceptar($this->action2);
				if(!$Autoriza->SetClaseAutorizacion('OS'))
				{
					$this->FormaMensaje($Autoriza->frmError['mensajeError'],'AUTORIZACIONES');	
					return true;
				}
				
				$Autoriza->FormaValidarAutoAdmisionHospitalizacion($datos);
				$this->salida = $Autoriza->salida;
			}
			else
				{
					$mensaje = "EL TIPO DE PLAN: ".$planes['sw_tipo_plan'].", NO ES VALIDO, FAVOR REVISAR LA INTEGRIDAD DE LA BASE DE DATOS";

					if($_SESSION['AdmHospitalizacion']['tipoorden'] == 'Externa')
						$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','Buscar',array('TIPOORDEN'=>'Externa'));  
					else
						$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','MetodoBuscarHospitalizacion');
												
					$this-> FormaMensaje($mensaje,'AUTORIZACIONES');						
				}
			
			return true;
		}
    
    function PedirAutorizacion()
    {
                unset($_SESSION['AUTORIZACIONES']);
                $_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id']=$_SESSION['DATOS_PACIENTE']['paciente_id'];
                $_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente']=$_SESSION['DATOS_PACIENTE']['tipo_id'];
                $_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']=$_SESSION['DATOS_PACIENTE']['plan_id'];
                $_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARGUMENTOS']=array();
                $_SESSION['AUTORIZACIONES']['RETORNO']['contenedor']='app';
                $_SESSION['AUTORIZACIONES']['RETORNO']['modulo']='Os_Atencion';
                $_SESSION['AUTORIZACIONES']['RETORNO']['tipo']='user';
                $_SESSION['AUTORIZACIONES']['RETORNO']['metodo']='RetornoAutorizacion';

                $this->ReturnMetodoExterno('app','Autorizacion','user','SolicitudAutorizacionAmbulatoria');
                return true;
    }
/*
    function RetornoAutorizacion()
    {
                $_SESSION['DATOS_PACIENTE']['PACIENTE']['tipo_afiliado_id']=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo_afiliado_id'];
                $_SESSION['DATOS_PACIENTE']['PACIENTE']['rango']=$_SESSION['AUTORIZACIONES']['RETORNO']['rango'];
                $_SESSION['DATOS_PACIENTE']['PACIENTE']['semanas']=$_SESSION['AUTORIZACIONES']['RETORNO']['semanas'];
                $_SESSION['DATOS_PACIENTE']['PACIENTE']['observacion_ingreso']=$_SESSION['AUTORIZACIONES']['RETORNO']['observacion_ingreso'];

                if(empty($_SESSION['AUTORIZACIONES']['RETORNO']['ext'])){  $_SESSION['AUTORIZACIONES']['RETORNO']['ext']='NULL'; }
                $_SESSION['DATOS_PACIENTE']['PACIENTE']['AUTORIZACIONEXT']=$_SESSION['AUTORIZACIONES']['RETORNO']['ext'];
                $_SESSION['DATOS_PACIENTE']['PACIENTE']['AUTORIZACION']=$_SESSION['AUTORIZORIZACIONES']['RETORNO']['NumAutorizacion'];

                $_SESSION['DATOS_PACIENTE']['Autorizacion']=$_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion'];
                $_SESSION['DATOS_PACIENTE']['NumAutorizacion']=$_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion'];

                if(empty($_SESSION['DATOS_PACIENTE']['NumAutorizacion']))
                {  $_SESSION['DATOS_PACIENTE']['NumAutorizacion']='NULL';  }

                unset($_SESSION['AUTORIZACIONES']);

                if(empty($_SESSION['DATOS_PACIENTE']['Autorizacion'])
                        AND empty($_SESSION['DATOS_PACIENTE']['NumAutorizacion']))
                {
                            if(empty($_SESSION['DATOS_PACIENTE']['NumAutorizacion']))
                            {   $Mensaje = 'No se pudo realizar la Autorización.';   }
                            $accion=ModuloGetURL('app','Os_Atencion','user','FormaBuscar');
                            if(!$this-> FormaMensaje($Mensaje,'AUTORIZACION SOLICITUD MANUAL',$accion,'')){
                            return false;
                            }
                            return true;
                }

                if(empty($_SESSION['DATOS_PACIENTE']['Autorizacion'])
                AND !empty($_SESSION['DATOS_PACIENTE']['NumAutorizacion']))
                {
                                        $Mensaje = 'No se Autorizo al Paciente.';
                                        $accion=ModuloGetURL('app','Os_Atencion','user','FormaBuscar');
                                        if(!$this-> FormaMensaje($Mensaje,'AUTORIZACIONES',$accion,'')){
                                        return false;
                                        }
                                        return true;
                }

                $this->FormaDatosSolicitud();
                return true;
    }
*/

    function RetornoAutorizacion()
    {
      $datos = $_REQUEST;
     
			$_SESSION['DATOS_PACIENTE']['PACIENTE']['rango'] = $datos['autorizacion']['rango'];
			$_SESSION['DATOS_PACIENTE']['PACIENTE']['semanas'] = $datos['autorizacion']['semanas'];
			$_SESSION['DATOS_PACIENTE']['PACIENTE']['plan_id'] = $datos['autorizacion']['plan_id'];
			$_SESSION['DATOS_PACIENTE']['PACIENTE']['paciente_id'] = $datos['autorizacion']['paciente_id'];
			$_SESSION['DATOS_PACIENTE']['PACIENTE']['AUTORIZACION'] = $datos['autorizacion']['numero_autorizacion'];
			$_SESSION['DATOS_PACIENTE']['Autorizacion'] = $datos['autorizacion']['numero_autorizacion'];
			$_SESSION['DATOS_PACIENTE']['NumAutorizacion'] = $datos['autorizacion']['numero_autorizacion'];
			$_SESSION['DATOS_PACIENTE']['PACIENTE']['tipo_id_paciente'] = $datos['autorizacion']['tipo_id_paciente'];
			$_SESSION['DATOS_PACIENTE']['PACIENTE']['tipo_afiliado_id'] = $datos['autorizacion']['tipoafiliado'];

      $_SESSION['DATOS_PACIENTE']['PACIENTE']['observacion_ingreso']=$_SESSION['AUTORIZACIONES']['RETORNO']['observacion_ingreso'];
      $_SESSION['DATOS_PACIENTE']['PACIENTE']['AUTORIZACIONEXT']=$datos['autorizacion']['codigo_autorizacion'];

      if(empty($_SESSION['DATOS_PACIENTE']['NumAutorizacion']))
      {  $_SESSION['DATOS_PACIENTE']['NumAutorizacion']='NULL';  }

      unset($_SESSION['AUTORIZACIONES']);

      if(empty($_SESSION['DATOS_PACIENTE']['Autorizacion'])
              AND empty($_SESSION['DATOS_PACIENTE']['NumAutorizacion']))
      {
        if(empty($_SESSION['DATOS_PACIENTE']['NumAutorizacion']))
        {   $Mensaje = 'No se pudo realizar la Autorización.';   }
        $accion=ModuloGetURL('app','Os_Atencion','user','FormaBuscar');
        if(!$this-> FormaMensaje($Mensaje,'AUTORIZACION SOLICITUD MANUAL',$accion,'')){
        return false;
        }
        return true;
      }

      if(empty($_SESSION['DATOS_PACIENTE']['Autorizacion'])
      AND !empty($_SESSION['DATOS_PACIENTE']['NumAutorizacion']))
      {
        $Mensaje = 'No se Autorizo al Paciente.';
        $accion=ModuloGetURL('app','Os_Atencion','user','FormaBuscar');
        if(!$this-> FormaMensaje($Mensaje,'AUTORIZACIONES',$accion,''))
          return false;
        
        return true;
      }

      $this->FormaDatosSolicitud();
      return true;
    }

  function NombrePaciente($TipoDocumento,$Documento)
    {
                    list($dbconn) = GetDBconn();
                    $query = "SELECT primer_nombre||' '||segundo_nombre||' '||primer_apellido||' '||segundo_apellido as nombre
                                        FROM pacientes
                                        WHERE paciente_id='$Documento' AND tipo_id_paciente ='$TipoDocumento'";
                    $resulta=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error al Guardar en la Base de Datos";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    return false;
                    }
                    $vars=$resulta->GetRowAssoc($ToUpper = false);
                    return $vars;
    }

  function TiposServicios()
    {
                    list($dbconn) = GetDBconn();
                    $query = "select servicio, descripcion from servicios where sw_asistencial=1";
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            return false;
                    }

                    while(!$result->EOF)
                    {
                                    $vars[]=$result->GetRowAssoc($ToUpper = false);
                                    $result->MoveNext();
                    }
                    $result->Close();
                    return $vars;
    }

    function PlanesProveedores()
    {
            list($dbconnect) = GetDBconn();
            $query= "select plan_descripcion,
                            plan_proveedor_id
                    FROM    planes_proveedores order by plan_descripcion";
            $result = $dbconnect->Execute($query);
            if ($dbconnect->ErrorNo() != 0)
            {
                $this->error = "Error al buscar en la tabla apoyod_tipos";
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
            $result->Close();
            return $vector;
    }

    function BuscarDepartamento()
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT a.* FROM departamentos as a, servicios as b
                                WHERE a.empresa_id= '".$_SESSION['LABORATORIO']['EMPRESA_ID']."'
                                and a.servicio=b.servicio and b.sw_asistencial=1
                                ORDER BY descripcion";
            $result = $dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            else{
                    if($result->EOF){
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "La tabla maestra 'tipo_id_pacientes' esta vacia ";
                            return false;
                    }
                            while (!$result->EOF) {
                                    $vars[]=$result->GetRowAssoc($ToUpper = false);;
                                    $result->MoveNext();
                            }
            }
            $result->Close();
            return $vars;
    }

    function GetEquivalenciasCargos($plan,$cargobase)
    {
                list($dbconn) = GetDBconn();
                $query="(
                                SELECT a.tarifario_id,tarif.descripcion AS nomtarifario,a.cargo,a.descripcion,a.precio,
                                        a.grupo_tarifario_id, a.subgrupo_tarifario_id, a.tipo_cargo,
                                        a.grupo_tipo_cargo, a.nivel, a.tipo_unidad_id, a.sw_honorarios,
                                        a.concepto_rips
                                FROM tarifarios_equivalencias c,tarifarios_detalle a,plan_tarifario b,planes pl,tarifarios tarif
                                WHERE c.cargo_base = '".$cargobase."'
                                AND c.tarifario_id = a.tarifario_id
                                AND c.cargo=a.cargo
                                AND a.grupo_tarifario_id = b.grupo_tarifario_id
                                AND a.subgrupo_tarifario_id = b.subgrupo_tarifario_id
                                AND a.tarifario_id = b.tarifario_id
                                AND b.plan_id=$plan
                                AND excepciones(b.plan_id,b.tarifario_id,a.cargo) = 0
                                AND b.plan_id=pl.plan_id
                                AND a.tarifario_id=tarif.tarifario_id

                                UNION

                                SELECT a.tarifario_id,tarif.descripcion  AS nomtarifario,a.cargo,a.descripcion,a.precio,
                                        a.grupo_tarifario_id, a.subgrupo_tarifario_id, a.tipo_cargo,
                                        a.grupo_tipo_cargo, a.nivel, a.tipo_unidad_id, a.sw_honorarios,
                                        a.concepto_rips
                                FROM tarifarios_equivalencias c,tarifarios_detalle a, excepciones b,tarifarios tarif,planes pl
                                WHERE c.cargo_base = '".$cargobase."'
                                AND a.tarifario_id = c.tarifario_id
                                AND a.cargo = c.cargo
                                AND b.tarifario_id = c.tarifario_id
                                AND b.cargo = c.cargo
                                AND b.plan_id = $plan
                                AND b.sw_no_contratado = 0
                                AND b.plan_id=pl.plan_id
                                AND a.tarifario_id=tarif.tarifario_id
                                );";
                                
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }

                while(!$result->EOF){
                        $var[]=$result->GetRowAssoc($ToUpper = false);
                        $result->MoveNext();
                }

                $result->Close();
                return $var;
    }

    function Profesionales()
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT * FROM profesionales
                                WHERE tipo_profesional in(1,2)
                                ORDER BY nombre";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            else{
                    if($result->EOF){
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "La tabla maestra 'tipo_id_pacientes' esta vacia ";
                            return false;
                    }
                            while (!$result->EOF) {
                                    $vars[]=$result->GetRowAssoc($ToUpper = false);;
                                    $result->MoveNext();
                            }
            }
            $result->Close();
            return $vars;
    }

    function GuardarDatosSolicitud()
    {
            if($_REQUEST['Serv']==-1 || !$_REQUEST['Fecha'])
            {
                    if($_REQUEST['Serv']==-1){ $this->frmError["Serv"]=1; }
                    if(!$_REQUEST['Fecha']){ $this->frmError["Fecha"]=1; }
                    $this->frmError["MensajeError"]="Faltan datos obligatorios.";
                    if(!$this->FormaDatosSolicitud()){
                                    return false;
                    }
                    return true;
            }

            $_SESSION['LABORATORIO']['DATOS']['MEDICO']=$_REQUEST['Medico'];
            $f=explode('/',$_REQUEST['Fecha']);
            $Fecha=$f[2].'-'.$f[1].'-'.$f[0];
            $_SESSION['LABORATORIO']['DATOS']['FECHA']=$Fecha;
            if(is_numeric($_REQUEST['Origen1'])) $_REQUEST['Origen'] = $_REQUEST['Origen1'];
              
            $_SESSION['LABORATORIO']['DATOS']['ENTIDAD']=$_REQUEST['Origen'];
            $_SESSION['LABORATORIO']['DATOS']['SERVICIO']=$_REQUEST['Serv'];
            $_SESSION['LABORATORIO']['DATOS']['OBSERVACION']=$_REQUEST['Observacion'];
            $_SESSION['LABORATORIO']['SERVICIO']=$_REQUEST['Serv'];

            $_SESSION['LABORATORIO']['DATOS']['CAMA']=$_REQUEST['cama'];

            if(!empty($_REQUEST['MedInt']))
            {
                    $_SESSION['LABORATORIO']['DATOS']['MEDICO']=$_REQUEST['MedInt'];
            }

            if(!empty($_REQUEST['departamento']))
            {
                    $dpto=explode('||',$_REQUEST['departamento']);
                    $_SESSION['LABORATORIO']['DATOS']['DEPARTAMENTO']=$dpto[1];
                    $_SESSION['LABORATORIO']['DATOS']['IDDEPARTAMENTO']=$dpto[0];
            }

            $this->Apoyos();
            return true;
    }

    function Apoyos()
    {
                    $this->frmForma();
                    return true;
    }

    function BuscarDatosTmp()
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT a.*, b.*
                                FROM tmp_solicitud_manual as a, tmp_solicitud_manual_detalle as b
                                WHERE a.codigo=".$_SESSION['LABORATORIO']['SERIAL']."
                                AND a.tipo_id_paciente='".$_SESSION['DATOS_PACIENTE']['tipo_id']."'
                                AND a.paciente_id='".$_SESSION['DATOS_PACIENTE']['paciente_id']."'
                                AND a.tmp_solicitud_manual_id=b.tmp_solicitud_manual_id
                                AND a.usuario_id=".UserGetUID()."
                                order by a.cargo_cups";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            while (!$result->EOF)
            {
                $vars[]=$result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }

            $result->Close();
            return $vars;
    }

    function Busqueda_AvanzadaCargos()
    {
                list($dbconn) = GetDBconn();
                $opcion      = ($_REQUEST['criterio1apoyo']);
                $cargo       = ($_REQUEST['cargoapoyo']);
                $descripcion =STRTOUPPER($_REQUEST['descripcionapoyo']);

                $filtroTipoCargo = '';
                $busqueda1 = '';
                $busqueda2 = '';

                if ($cargo != '')
                {
                        $busqueda1 =" AND a.cargo ILIKE '$cargo%'";
                }

                if ($descripcion != '')
                {
                        $busqueda2 ="AND a.descripcion ILIKE '%$descripcion%'";
                }

                if(empty($_REQUEST['conteoapoyo']))
                {
                        if($opcion == '002')
                        {       //frecuentes
                                $query = "SELECT count(a.cargo)
                                FROM cups a,apoyod_tipos b, departamentos_cargos as c, apoyod_solicitud_frecuencia as d
                                WHERE a.cargo = d.cargo and d.departamento='".$_SESSION['LABORATORIO']['DPTO']."'
                                and a.grupo_tipo_cargo = b.apoyod_tipo_id
                                and a.cargo=c.cargo and c.departamento='".$_SESSION['LABORATORIO']['DPTO']."'
                                $dpto $espe $busqueda1 $busqueda2";
                        }
                        else
                        {
                                $query = "SELECT count(a.cargo)
                                FROM cups a,apoyod_tipos b, departamentos_cargos as c
                                WHERE a.grupo_tipo_cargo = b.apoyod_tipo_id
                                and a.cargo=c.cargo and c.departamento='".$_SESSION['LABORATORIO']['DPTO']."'
                                $filtroTipoCargo    $busqueda1 $busqueda2";
                        }
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                        }
                        list($this->conteo)=$resulta->FetchRow();
                }
                else
                {
                        $this->conteo=$_REQUEST['conteoapoyo'];
                        $_SESSION['SPY']=$this->conteo;
                }
                if(!$_REQUEST['Ofapoyo'])
                {
                        $Of='0';
                }
                else
                {
                        $Of=$_REQUEST['Ofapoyo'];
                        if($Of > $this->conteo)
                        {
                                $Of=0;
                                $_REQUEST['Ofapoyo']=0;
                                $_REQUEST['paso1apoyo']=1;
                        }
                }
                if($opcion == '002')
                {
                        $query = "SELECT DISTINCT a.cargo, a.descripcion, b.apoyod_tipo_id, b.descripcion as tipo
                        FROM cups a,apoyod_tipos b, departamentos_cargos as c, apoyod_solicitud_frecuencia as d
                        WHERE a.cargo = d.cargo and  d.departamento='".$_SESSION['LABORATORIO']['DPTO']."'
                        and a.grupo_tipo_cargo = b.apoyod_tipo_id
                        and a.cargo=c.cargo and c.departamento='".$_SESSION['LABORATORIO']['DPTO']."'
                        $dpto $espe $busqueda1 $busqueda2
                        order by a.descripcion, a.cargo
                        LIMIT ".$this->limit." OFFSET $Of;";
                }
                else
                {
                        $query = "SELECT a.cargo, a.descripcion, b.apoyod_tipo_id, b.descripcion as tipo
                        FROM cups a,apoyod_tipos b, departamentos_cargos as c
                        WHERE a.grupo_tipo_cargo = b.apoyod_tipo_id
                        and a.cargo=c.cargo and c.departamento='".$_SESSION['LABORATORIO']['DPTO']."'
                        $filtroTipoCargo    $busqueda1 $busqueda2
                        order by b.apoyod_tipo_id, a.cargo
                        LIMIT ".$this->limit." OFFSET $Of;";
                }
                $resulta = $dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0)
                {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                                                                            //$this->conteo=$resulta->RecordCount();
                $i=0;
                while(!$resulta->EOF)
                {
                        $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
                        $resulta->MoveNext();
                        $i++;
                }

                if($this->conteo==='0')
                {
                                $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
                }

                //$this->frmForma($var);
                $this->FrmAgregarCargos($var);
                return true;
    }
  //empieza
    function ProductosInventariosBodega($codigoBus,$DescripcionBus,$bodega)
    {
        if($_REQUEST['Volver'])
        {
            $this->LiquidacionOrden();
            return true;
        }
        $codigoBus=$_REQUEST['codigoBus'];
        $DescripcionBus=$_REQUEST['DescripcionBus'];
        if(!empty($codigoBus))
        {
          $sql=" AND x.codigo_producto ILIKE '$codigoBus%'";
        }
        else
        {
          $sql="";
        }

        if(!empty($DescripcionBus))
        {
            $sql1=" AND i.descripcion ILIKE '%".strtoupper($DescripcionBus)."%'";
        }
        else
        {
          $sql1="";
        }

        //$bodega=$_REQUEST['bodega'];
        $bodega=explode(',',$_REQUEST['bodega']);
        $this->paginaActual = 1;
        $this->offset = 0;
        if($_REQUEST['offset']){
          $this->paginaActual = intval($_REQUEST['offset']);
          if($this->paginaActual > 1){
            $this->offset = ($this->paginaActual - 1) * ($this->limit);
          }
        }
        list($dbconn) = GetDBconn();
/*  
        $query = "SELECT  a.codigo_producto,
                        b.descripcion,
                        a.existencia, 
                        d.precio_venta
                  FROM    existencias_bodegas a,
                    inventarios_productos b,
                    inv_grupos_inventarios c, 
                    inventarios d
                  WHERE   a.codigo_producto=b.codigo_producto
                    AND a.empresa_id=d.empresa_id
                    AND a.codigo_producto=d.codigo_producto
                    AND a.bodega='".$bodega[0]."'
                    AND b.grupo_id=c.grupo_id
                    AND (c.sw_medicamento='1' OR c.sw_insumos='1')
                    $sql
                    $sql1
                  ORDER BY b.descripcion";
*/                  
        $query = "SELECT x.codigo_producto, 
                         CASE WHEN lote.existencia_actual IS NOT NULL THEN lote.existencia_actual ELSE x.existencia END as existencia, 
                         i.descripcion, lote.lote, lote.fecha_vencimiento, h.precio_venta
                  FROM existencias_bodegas as x 
                      LEFT JOIN existencias_bodegas_lote_fv lote ON (x.empresa_id=lote.empresa_id AND x.centro_utilidad=lote.centro_utilidad AND x.codigo_producto=lote.codigo_producto AND x.bodega=lote.bodega)
                      INNER JOIN inventarios h ON (h.codigo_producto=x.codigo_producto AND h.empresa_id=x.empresa_id)
                      INNER JOIN inventarios_productos i ON (x.codigo_producto=i.codigo_producto)
                      INNER JOIN inv_grupos_inventarios c ON c.grupo_id=i.grupo_id
                  WHERE x.bodega='".$bodega[0]."' AND (c.sw_medicamento='1' OR c.sw_insumos='1') AND (lote.existencia_actual > 0 AND x.existencia > 0)
                    $sql
                    $sql1
                  ORDER BY i.descripcion";
        
        if(empty($_REQUEST['conteo'])){
          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0){
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
          }
          $this->conteo=$result->RecordCount();
        }else{
          $this->conteo=$_REQUEST['conteo'];
        }
        $query.=" LIMIT " . $this->limit . " OFFSET ".$this->offset."";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }else{
          while(!$result->EOF){
            $vars[]=$result->GetRowAssoc($toUpper=false);
            $result->MoveNext();
          }
        }
        $this->BuscadorProductoInv($NoLiquidacion,$TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso,$codigoBus,$DescripcionBus,$bodega,$vars);
        return true;
    }

        function InsertarProductoTmpInventario()
        {
            list($dbconn) = GetDBconn();
            //$Cuenta=$_SESSION['OS_ATENCION']['CUENTA'];
            //$tipoid=$_SESSION['CAJA']['TIPO_ID_TERCERO'];
            //$tercero=$_SESSION['CAJA']['TERCEROID'];
            $tipoid=$_SESSION['OS_ATENCION']['tipo'];
            $tercero=$_SESSION['OS_ATENCION']['id'];
            $ProductosBodega=$_SESSION['OS_ATENCION']['PRODUCTOS'];
//            print_r($_REQUEST);
            for($i=0;$i<sizeof($ProductosBodega);$i++)
            {
                if(!empty($_REQUEST['producto'.$ProductosBodega[$i]['codigo_producto'].$i]))
                {
                    $valortotal=$_REQUEST['producto'.$ProductosBodega[$i]['codigo_producto']]*$ProductosBodega[$i]['precio_venta'];
                    $bodega=explode(',',$_SESSION['OS_ATENCION']['bodega']);
                    $Departamento=$_SESSION['LABORATORIO']['DPTO'];
                    $query ="SELECT b.servicio
                            FROM departamentos as b
                            WHERE b.departamento='$Departamento'";
                    $results = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                      $this->error = "Error al Cargar el Modulo";
                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                      return false;
                    }
                    $Servicio=$results->fields[0];
                    $plan=$_SESSION['OS_ATENCION']['PlanId'];
                    $sql="INSERT INTO tmp_cuenta_imd(
                                                    empresa_id,
                                                    centro_utilidad,
                                                    departamento,
                                                    bodega,
                                                    numero_orden_id,
                                                    codigo_producto,
                                                    lote,
                                                    cantidad,
                                                    precio,
                                                    fecha_cargo,
                                                    plan_id,
                                                    servicio_cargo,
                                                    fecha_vencimiento
                                                    )
                                                    VALUES
                                                    (
                                                    '".$_SESSION['LABORATORIO']['EMPRESA_ID']."',
                                                    '".$_SESSION['LABORATORIO']['CENTROUTILIDAD']."',
                                                    '".$Departamento."',
                                                    '".$bodega[0]."',
                                                    ".$_SESSION['OS_ATENCION']['ORDEN'].",
                                                    '".$ProductosBodega[$i]['codigo_producto']."',
                                                    '".$ProductosBodega[$i]['lote']."',
                                                    ".$_REQUEST['producto'.$ProductosBodega[$i]['codigo_producto'].$i].",
                                                    ".$ProductosBodega[$i]['precio_venta'].",
                                                    now(),
                                                    ".$plan.",
                                                    ".$Servicio.",
                                                    '".$ProductosBodega[$i]['fecha_vencimiento']."'
                                                    );";
                      $resulta=$dbconn->execute($sql);

                      if ($dbconn->ErrorNo() != 0) {
                                          $this->error = "Error al Cargar el Modulo";
                                          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                          $this->fileError = __FILE__;
                                          $this->lineError = __LINE__;
                                          return false;
                      }
                }
            }
            $this->LiquidacionOrden('','','','','','','');
            return true;
    }

    function TraerIMDAdicionados($cuenta,$orden)
    {
        list($dbconn) = GetDBconn();
        if(!empty($cuenta))
            $sql="a.numerodecuenta=$cuenta";
        elseif(!empty($orden))
            $sql="a.numero_orden_id=$orden";

        $query="SELECT  a.*, 
                        b.descripcion, 
                        c.descripcion AS desbodega,
                        d.descripcion as deservicio
                FROM    tmp_cuenta_imd a,
                        inventarios_productos b,
                        bodegas c,
                        servicios d
                WHERE   $sql
                AND     a.codigo_producto = b.codigo_producto
                AND     a.bodega = c.bodega
                AND     a.servicio_cargo = d.servicio;";
                
        $result=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar tmp_cuenta_imd -".$query;
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$result->EOF)
        {
            $var[]= $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        $result->Close();
        if(!empty($var))
        {
            $_SESSION['CAJA']['IMD_CUENTA']=$var;
            $j=0;
            for($i=0;$i<sizeof($var); $i++)
            {
                if (!IncludeClass("LiquidacionCargosInventario"))
                {
                        $this->frmError["MensajeError"]='NO SE PUDO INCLUIR LA CLASE LiquidacionCargosInventario.class.php';
                        $this->LiquidacionOrden();
                        return $var;
                        //  die(MsgOut("NO SE PUDO INCLUIR LA CLASE"));
                }

                $a= new LiquidacionCargosInventario;
                if(($retorno = $a->GetLiquidacionProducto($var[$i][codigo_producto], $var[$i][empresa_id], $var[$i][cantidad], array('plan_id'=>$var[$i][plan_id],'departamento'=>$_SESSION['LABORATORIO']['DPTO'],'tmp_cuenta_insumos_id'=>$var[$i][tmp_cuenta_insumos_id],"sw_factura"=>$var[$i]['sw_factura'],"lote"=>$var[$i]['lote'])))===false)
                {
                        $this->frmError["MensajeError"]="EL PRODUCTO ['".$var[$i][codigo_producto]."'] NO EXISTE ";
                        $this->LiquidacionOrden();
                        return $var;
                        //die(MsgOut($a->Err(),$a->ErrMsg()));
                }
                $insumos[$j]=$retorno;
                $j++;
            }
        }
        $_SESSION['CAJA']['IMD_OS']=$insumos;
      //  echo '<br>IMD--->'; print_r($insumos);
        return $insumos;
    }

    function TraerBodega($dat)
    {
//        $datos=explode(',',$dat);
        $bodega=$dat['bodega'];
        $empresa=$dat['empresa_id'];
        $centro=$dat['centro_utilidad'];
        list($dbconn) = GetDBconn();
        $query="SELECT descripcion
                                FROM bodegas
                                WHERE bodega=$bodega
                                AND empresa_id='".$empresa."'
                                AND centro_utilidad='".$centro."';";
        $result=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar tmp_imd_adicionales-".$query;
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $bodega= $result->GetRowAssoc($ToUpper = false);
        $result->Close();
        return $bodega;
    }


  /**
  * Busca las bodegas que tiene una empresa
  * @access public
  * @return boolean
  */
  function Bodegas()
  {
        $EmpresaId=$_SESSION['LABORATORIO']['EMPRESA_ID'];
                $depto=$_SESSION['LABORATORIO']['DPTO'];
        if($_SESSION['LABORATORIO']['CENTROUTILIDAD'])
        { $CU="and centro_utilidad='".$_SESSION['LABORATORIO']['CENTROUTILIDAD']."'"; }

        list($dbconn) = GetDBconn();
        
        $query="SELECT * FROM bodegas
                                WHERE empresa_id='$EmpresaId' $CU
                                    AND departamento='".$depto."'
                                order by descripcion;";
								
        $result=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }
        while(!$result->EOF)
        {
          $var[]= $result->GetRowAssoc($ToUpper = false);
          $result->MoveNext();
        }
        $result->Close();
        return $var;
  }

    function Busqueda_Avanzada()
    {
                list($dbconn) = GetDBconn();
                
                $opcion      = ($_REQUEST['criterio1apoyo']);
                $cargo       = ($_REQUEST['cargoapoyo']);
                $descripcion =STRTOUPPER($_REQUEST['descripcionapoyo']);

                $filtroTipoCargo = '';
                $busqueda1 = '';
                $busqueda2 = '';

                if ($cargo != '')
                {
                        $busqueda1 =" AND a.cargo ILIKE '$cargo%'";
                }

                if ($descripcion != '')
                {
                        $busqueda2 ="AND a.descripcion ILIKE '%$descripcion%'";
                }

                if(empty($_REQUEST['conteoapoyo']))
                {
                        if($opcion == '002')
                        {       //frecuentes
                                $query = "SELECT count(a.cargo)
                                FROM cups a,apoyod_tipos b, departamentos_cargos as c, apoyod_solicitud_frecuencia as d
                                WHERE a.cargo = d.cargo and d.departamento='".$_SESSION['LABORATORIO']['DPTO']."'
                                and a.grupo_tipo_cargo = b.apoyod_tipo_id
                                and a.cargo=c.cargo and c.departamento='".$_SESSION['LABORATORIO']['DPTO']."'
                                $dpto $espe $busqueda1 $busqueda2";
                        }
                        else
                        {
                                $query = "SELECT count(a.cargo)
                                FROM cups a,apoyod_tipos b, departamentos_cargos as c
                                WHERE a.grupo_tipo_cargo = b.apoyod_tipo_id
                                and a.cargo=c.cargo and c.departamento='".$_SESSION['LABORATORIO']['DPTO']."'
                                $filtroTipoCargo    $busqueda1 $busqueda2";
                        }
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                        }
                        list($this->conteo)=$resulta->FetchRow();
                }
                else
                {
                        $this->conteo=$_REQUEST['conteoapoyo'];
                        $_SESSION['SPY']=$this->conteo;
                }
                if(!$_REQUEST['Ofapoyo'])
                {
                        $Of='0';
                }
                else
                {
                        $Of=$_REQUEST['Ofapoyo'];
                        if($Of > $this->conteo)
                        {
                                $Of=0;
                                $_REQUEST['Ofapoyo']=0;
                                $_REQUEST['paso1apoyo']=1;
                        }
                }
                if($opcion == '002')
                {
                        $query = "SELECT DISTINCT a.cargo, a.descripcion, b.apoyod_tipo_id, b.descripcion as tipo
                        FROM cups a,apoyod_tipos b, departamentos_cargos as c, apoyod_solicitud_frecuencia as d
                        WHERE a.cargo = d.cargo and  d.departamento='".$_SESSION['LABORATORIO']['DPTO']."'
                        and a.grupo_tipo_cargo = b.apoyod_tipo_id
                        and a.cargo=c.cargo and c.departamento='".$_SESSION['LABORATORIO']['DPTO']."'
                        $dpto $espe $busqueda1 $busqueda2
                        order by a.descripcion, a.cargo
                        LIMIT ".$this->limit." OFFSET $Of;";
                }
                else
                {
                        $query = "SELECT a.cargo, a.descripcion, b.apoyod_tipo_id, b.descripcion as tipo
                        FROM cups a,apoyod_tipos b, departamentos_cargos as c
                        WHERE a.grupo_tipo_cargo = b.apoyod_tipo_id
                        and a.cargo=c.cargo and c.departamento='".$_SESSION['LABORATORIO']['DPTO']."'
                        $filtroTipoCargo    $busqueda1 $busqueda2
                        order by b.apoyod_tipo_id, a.cargo
                        LIMIT ".$this->limit." OFFSET $Of;";
                }
                $resulta = $dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0)
                {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                                                                            //$this->conteo=$resulta->RecordCount();
                $i=0;
                while(!$resulta->EOF)
                {
                        $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
                        $resulta->MoveNext();
                        $i++;
                }

                if($this->conteo==='0')
                {
                                $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
                }

                $this->frmForma($var);
                return true;
    }


    function GuardarApoyo()
    {
            IncludeLib("malla_validadora");
            IncludeLib("funciones_facturacion");
            unset($_SESSION['LABORATORIO']['VECTOR']['CARGOS']);
            $datos=$_REQUEST['SeleccionCargos'];
						//print_r($datos);
            $_SESSION['LABORATORIO']['VECTOR']['CARGOS']=$datos;
            foreach($datos as $cargo=>$valor)
            {
                (list($apoyod_tipo_id,$descripcion)=explode('||//',$valor));
                $x=ValidarCargoMalla($cargo,$_SESSION['DATOS_PACIENTE']['plan_id'],$_SESSION['LABORATORIO']['SERVICIO']);
                //pasa la malla
                if(is_numeric($x))
                {
                  //varias equivalencias
                  if($x==2)
                  {       
                    //cups, apoyod_tipo_id,'apoyo'=>$_REQUEST['apoyo'])
                    $this->FormaVariasEquivalencias($cargo,$apoyod_tipo_id);
                    return true;
                  }
                }
                elseif(is_array($x))
                {       //paso la malla
                        //un vector [] tarifario cargo descricpion cantidad
                        $vector[0]=array('tarifario'=>$x[tarifario],'cargo'=>$x[cargo],'descripcion'=>$x[descripcion],'cantidad'=>1);
                        $insertar=$this->InsertarTmp($cargo,$apoyod_tipo_id,$vector,1);
                        if(empty($insertar)){
                            $this->frmError["MensajeError"]="ERROR EN LA INSERCCION";
                            $this->frmForma('');
                            return true;
                        }
                        unset($_SESSION['LABORATORIO']['VECTOR']['CARGOS'][$cargo]);
                }
                else
                {       //se quedo en la malla
                        //un vector [] tarifario cargo descricpion cantidad
                        $equi=ValdiarEquivalencias($_SESSION['DATOS_PACIENTE']['plan_id'],$cargo);
                        $vector[0]=array('tarifario'=>$equi[0][tarifario_id],'cargo'=>$equi[0][cargo],'descripcion'=>$equi[0][descripcion],'cantidad'=>1);
                        $insertar=$this->InsertarTmp($cargo,$apoyod_tipo_id,$vector,0);
                        if(empty($insertar)){
                            $this->frmError["MensajeError"]="ERROR EN LA INSERCCION2";
                            $this->frmForma('');
                            return true;
                        }
                        unset($_SESSION['LABORATORIO']['VECTOR']['CARGOS'][$cargo]);
                        $this->frmError["MensajeError"]="EL CARGO ". $cargo ." SE QUEDA EN LA MALLA PORQUE: $x";
                        //$this->frmError["MensajeError"]="NO SE PUEDE SOLICITAR PORQUE: $x";
                        //$this->frmForma('');
                        //return true;
                }
            }
            $this->frmError["MensajeError"]="SOLICITUD GUARDADA";
            $this->frmForma('');
            return true;
    }

    function GuardarEquivalencias()
    {
            $f=0;
            foreach($_REQUEST as $k => $v)
            {
                    if(substr_count($k,'Equi'))
                    {  $f++;  }
            }

            if($f==0)
            {
                    $this->frmError["MensajeError"]="DEBE ELEGIR AL MENOS UNA EQUIVALENCIA";
                    $this->FormaVariasEquivalencias($_REQUEST['cups'],$_REQUEST['apoyod_tipo_id']);
                    return true;
            }

            $vector='';
            foreach($_REQUEST as $k => $v)
            {
                    if(substr_count($k,'Equi'))
                    {
                            //0 tarifario 1cargo 2 descripcion 3 cantidad
                            $dat=explode('//',$v);
                            $vector[]=array('tarifario'=>$dat[0],'cargo'=>$dat[1],'descripcion'=>$dat[2],'cantidad'=>1);
                    }
            }

            $insertar=$this->InsertarTmp($_REQUEST['cups'],$_REQUEST['apoyod_tipo_id'],$vector,1);
            if(!empty($insertar))
            {       $this->frmError["MensajeError"]="SOLICITUD GUARDADA";  }
            else
            {       $this->frmError["MensajeError"]="ERROR EN LA INSERCCION";  }

            unset($_SESSION['LABORATORIO']['VECTOR']['CARGOS'][$_REQUEST['cups']]);
            if(!empty($_SESSION['LABORATORIO']['VECTOR']['CARGOS']))
            {
                    $_REQUEST['SeleccionCargos']=$_SESSION['LABORATORIO']['VECTOR']['CARGOS'];
                    $this->GuardarApoyo();
                    return true;
            }
            else
            {
                    unset($_SESSION['LABORATORIO']['VECTOR']['CARGOS']);
                    $this->frmForma('');
                    return true;
            }
    }


    function EliminarCargo()
    {
            list($dbconn) = GetDBconn();
            $dbconn->BeginTrans();

            $query =" DELETE FROM tmp_solicitud_manual_detalle WHERE tmp_solicitud_manual_id=".$_REQUEST['id']."
                                AND tmp_solicitud_manual_detalle_id=".$_REQUEST['idDetalle']."";
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error DELETE FROM tmp_solicitud_manual_detalle";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
            }

            $query =" SELECT * FROM tmp_solicitud_manual_detalle WHERE tmp_solicitud_manual_id=".$_REQUEST['id']."";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error SELECT";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }

            if($result->EOF)
            {
                    $query =" DELETE FROM tmp_solicitud_manual WHERE tmp_solicitud_manual_id=".$_REQUEST['id']."";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error DELETE FROM tmp_solicitud_manual";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                    }
            }

            $dbconn->CommitTrans();
            $this->frmError["MensajeError"]="SE ELIMINO";

            $this->frmForma('');
            return true;
    }

		function EliminarCargoAdicionado()
		{
						list($dbconn) = GetDBconn();
						$dbconn->BeginTrans();
						$orden=$_REQUEST['numero_orden_id'];
						$cargo=$_REQUEST['cargo'];
						$tarifario=$_REQUEST['tarifario_id'];
						$tmp_cuenta_insumos_id=$_REQUEST['tmp_cuenta_insumos_id'];
						if($cargo=='IMD' AND $tarifario=='SYS')
						{
								$query =" DELETE FROM tmp_cuenta_imd
													WHERE tmp_cuenta_insumos_id=$tmp_cuenta_insumos_id;";
						}
						else
						{
								$query =" DELETE FROM tmp_cuentas_cargos 
													WHERE numero_orden_id=$orden
													AND tarifario_id='$tarifario'
													AND cargo='$cargo'";
						}
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error DELETE FROM tmp_cuentas_cargos/tmp_cuenta_imd";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$dbconn->RollbackTrans();
										return false;
						}
	
	
						$dbconn->CommitTrans();
						$this->frmError["MensajeError"]="SE ELIMINO";
	
						$this->LiquidacionOrden();
						return true;
		}

    function InsertarTmp($cups,$tipo,$vector,$sw)
    {
                    list($dbconn) = GetDBconn();
                    $dbconn->BeginTrans();

                    $query=" SELECT nextval('tmp_solicitud_manual_tmp_solicitud_manual_id_seq')";
                    $result=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error INSERT INTO cuentas_detalle ";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                    }
                    $id=$result->fields[0];

                    $query = "INSERT INTO tmp_solicitud_manual (
                                                tmp_solicitud_manual_id,
                                                codigo,
                                                tipo_id_paciente,
                                                paciente_id,
                                                apoyod_tipo_id,
                                                cargo_cups,
                                                fecha_registro,
                                                usuario_id,
                                                sw_os)
                                        VALUES ($id,".$_SESSION['LABORATORIO']['SERIAL'].",'".$_SESSION['DATOS_PACIENTE']['tipo_id']."','".$_SESSION['DATOS_PACIENTE']['paciente_id']."','$tipo','$cups','now()',".UserGetUID().",'$sw')";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error INSERT INTO cuentas_detalle ";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                    }

                    for($i=0; $i<sizeof($vector); $i++)
                    {
                            $query = "INSERT INTO tmp_solicitud_manual_detalle (
                                                        tmp_solicitud_manual_id,
                                                        tarifario_id,
                                                        cargo,
                                                        descripcion,
                                                        cantidad)
                                                VALUES ($id,'".$vector[$i]['tarifario']."','".$vector[$i]['cargo']."','".$vector[$i]['descripcion']."',".$vector[$i]['cantidad'].")";
                            $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error INSERT INTO cuentas_detalle ";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    $dbconn->RollbackTrans();
                                    return false;
                            }
                    }
                    $dbconn->CommitTrans();
                    return true;
    }
/*
    function CrearOs()
    {
            list($dbconn) = GetDBconn();
						
            $query = "SELECT a.*
                                FROM tmp_solicitud_manual as a
                                WHERE a.codigo=".$_SESSION['LABORATORIO']['SERIAL']."
                                AND a.tipo_id_paciente='".$_SESSION['DATOS_PACIENTE']['tipo_id']."'
                                AND a.paciente_id='".$_SESSION['DATOS_PACIENTE']['paciente_id']."'
                                AND a.usuario_id=".UserGetUID()."
                                order by a.cargo_cups";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo1";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            while (!$result->EOF)
            {
                $var[]=$result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
						
            $result->Close();

          //valida si hay ordenes o todas son solicitudes
            $query = "SELECT count(a.codigo)
                                FROM tmp_solicitud_manual as a
                                WHERE a.codigo=".$_SESSION['LABORATORIO']['SERIAL']."
                                AND a.tipo_id_paciente='".$_SESSION['DATOS_PACIENTE']['tipo_id']."'
                                AND a.paciente_id='".$_SESSION['DATOS_PACIENTE']['paciente_id']."'
                                AND a.usuario_id=".UserGetUID()."
                                AND a.sw_os='1'";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo1";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            if(!$result->EOF)
            {
                    $conteoOS=$result->RecordCount();
                    $result->Close();
            }

            if($conteoOS > 0)
            {
                    $query="SELECT nextval('os_ordenes_servicios_orden_servicio_id_seq')";
                    $result=$dbconn->Execute($query);
                    $orden=$result->fields[0];
                    $query = "INSERT INTO os_ordenes_servicios
                                                                (orden_servicio_id,
                                                                autorizacion_int,
                                                                autorizacion_ext,
                                                                plan_id,
                                                                tipo_afiliado_id,
                                                                rango,
                                                                semanas_cotizadas,
                                                                servicio,
                                                                tipo_id_paciente,
                                                                paciente_id,
                                                                usuario_id,
                                                                fecha_registro,
                                                                observacion)
                    VALUES($orden,1,NULL,".$_SESSION['DATOS_PACIENTE']['plan_id'].",'".$_SESSION['DATOS_PACIENTE']['PACIENTE']['tipo_afiliado_id']."',
                    '".$_SESSION['DATOS_PACIENTE']['PACIENTE']['rango']."',".$_SESSION['DATOS_PACIENTE']['PACIENTE']['semanas'].",
                    '".$_SESSION['LABORATORIO']['SERVICIO']."','".$_SESSION['DATOS_PACIENTE']['tipo_id']."','".$_SESSION['DATOS_PACIENTE']['paciente_id']."',
                    ".UserGetUID().",'now()','')";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                            $this->error = "Error INSERT INTO os_ordenes_servicios";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                    }

                    $query3="INSERT INTO hc_os_solicitudes_manuales_datos_adicionales(
                                                                        orden_servicio_id,
                                                                        cama,
                                                                        departamento)
                            VALUES($orden,'".$_SESSION['DATOS_PACIENTE']['DATOS']['CAMA']."','".$_SESSION['DATOS_PACIENTE']['DATOS']['DEPARTAMENTO']."')";
                    $dbconn->Execute($query3);
                    if ($dbconn->ErrorNo() != 0)
                    {
                            $this->error = "Error al insertar en hc_os_solicitudes_manuales";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                    }
            }

            for($i=0; $i<sizeof($var); $i++)
            {
                    $query1="SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq')";
                    $result=$dbconn->Execute($query1);
                    $hc_os_solicitud_id=$result->fields[0];

                    $query2="INSERT INTO hc_os_solicitudes
                                          (hc_os_solicitud_id, evolucion_id, cargo, os_tipo_solicitud_id, plan_id, paciente_id, tipo_id_paciente)
                                    VALUES($hc_os_solicitud_id,NULL,'".$var[$i][cargo_cups]."', 'APD',
                                    ".$_SESSION['DATOS_PACIENTE']['plan_id'].",
                                    '".$_SESSION['DATOS_PACIENTE']['paciente_id']."',
                                    '".$_SESSION['DATOS_PACIENTE']['tipo_id']."')";
                    $dbconn->Execute($query2);
                    if ($dbconn->ErrorNo() != 0)
                    {
                            $this->error = "Error al insertar en hc_os_solicitudes";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                    }

                    $query3="INSERT INTO hc_os_solicitudes_apoyod
                                                    (hc_os_solicitud_id, apoyod_tipo_id)
                                    VALUES($hc_os_solicitud_id, '".$var[$i][apoyod_tipo_id]."');";
                    $dbconn->Execute($query3);
                    if ($dbconn->ErrorNo() != 0)
                    {
                            $this->error = "Error al insertar en hc_os_solicitudes_apoyod";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                    }

                    if(empty($_SESSION['LABORATORIO']['DATOS']['IDDEPARTAMENTO']))
                    {   $dpto='NULL';   }
                    else
                    {   $dpto="'".$_SESSION['LABORATORIO']['DATOS']['IDDEPARTAMENTO']."'";   }

                    $query3="INSERT INTO hc_os_solicitudes_manuales(
                                        hc_os_solicitud_id,fecha,
                                        servicio,profesional,prestador,observaciones,
                                        tipo_id_paciente,paciente_id,fecha_resgistro,
                                        usuario_id,empresa_id,tipo_afiliado_id,rango,semanas_cotizadas,departamento)
                            VALUES($hc_os_solicitud_id, '".$_SESSION['LABORATORIO']['DATOS']['FECHA']."',
                            '".$_SESSION['LABORATORIO']['SERVICIO']."','".$_SESSION['LABORATORIO']['DATOS']['MEDICO']."',
                            '".$_SESSION['LABORATORIO']['DATOS']['ENTIDAD']."','".$_SESSION['LABORATORIO']['DATOS']['OBSERVACION']."',
                            '".$_SESSION['DATOS_PACIENTE']['tipo_id']."','".$_SESSION['DATOS_PACIENTE']['paciente_id']."',
                            'now()',".UserGetUID().",'".$_SESSION['LABORATORIO']['EMPRESA_ID']."',
                            '".$_SESSION['DATOS_PACIENTE']['PACIENTE']['tipo_afiliado_id']."','".$_SESSION['DATOS_PACIENTE']['PACIENTE']['rango']."',".$_SESSION['DATOS_PACIENTE']['PACIENTE']['semanas'].",$dpto);";
                    $dbconn->Execute($query3);
                    if ($dbconn->ErrorNo() != 0)
                    {
                            $this->error = "Error al insertar en hc_os_solicitudes_manuales";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                    }

                    $plan=$_SESSION['DATOS_PACIENTE']['plan_id'];
                    if($var[$i][sw_os] == 1)
                    {
                            $query = "select * from os_tipos_periodos_planes
                                                where plan_id=".$plan." and cargo='".$var[$i][cargo_cups]."'";
                            $result=$dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                            //echo "salida 2 <br>";
                                                            return false;
                            }
                            if(!$result->EOF)
                            {
                                    $vars=$result->GetRowAssoc($ToUpper = false);
                                    $Fecha=$this->FechaStamp($fecha_solicitud);
                                    $infoCadena = explode ('/',$Fecha);
                                    $intervalo=$this->HoraStamp($fecha_solicitud);
                                    $infoCadena1 = explode (':', $intervalo);
                                    $fechaAct=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_tramite_os]),$infoCadena[2]));
                                    if($fechaAct < date("Y-m-d H:i:s"))
                                    {  $fechaAct=date("Y-m-d H:i:s");  }
                                    $Fecha=$this->FechaStamp($fechaAct);
                                    $infoCadena = explode ('/',$Fecha);
                                    $intervalo=$this->HoraStamp($fechaAct);
                                    $infoCadena1 = explode (':', $intervalo);
                                    $venc=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_vigencia]),$infoCadena[2]));
                                    //fecha refrendar
                                    $Fecha=$this->FechaStamp($venc);
                                    $infoCadena = explode ('/',$Fecha);
                                    $intervalo=$this->HoraStamp($venc);
                                    $infoCadena1 = explode (':', $intervalo);
                                    $refrendar=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_refrendar]),$infoCadena[2]));
                            }
                            else
                            {                //si no hay unos tiempos especificos para el cargo toma los genericos
                                    $query = "select * from os_tipos_periodos_tramites
                                                        where cargo='".$var[$i][cargo_cups]."'";
                                    $result=$dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                    //echo "salida 3 <br>";
                                                                    return false;
                                    }
                                    if(!$result->EOF)
                                    {
                                            $vars=$result->GetRowAssoc($ToUpper = false);
                                            $Fecha=$this->FechaStamp($fecha_solicitud);
                                            $infoCadena = explode ('/',$Fecha);
                                            $intervalo=$this->HoraStamp($fecha_solicitud);
                                            $infoCadena1 = explode (':', $intervalo);
                                            $fechaAct=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_tramite_os]),$infoCadena[2]));
                                            if($fechaAct < date("Y-m-d H:i:s"))
                                            {  $fechaAct=date("Y-m-d H:i:s");  }
                                            $Fecha=$this->FechaStamp($fechaAct);
                                            $infoCadena = explode ('/',$Fecha);
                                            $intervalo=$this->HoraStamp($fechaAct);
                                            $infoCadena1 = explode (':', $intervalo);
                                            $venc=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_vigencia]),$infoCadena[2]));
                                            //fecha refrendar
                                            $Fecha=$this->FechaStamp($venc);
                                            $infoCadena = explode ('/',$Fecha);
                                            $intervalo=$this->HoraStamp($venc);
                                            $infoCadena1 = explode (':', $intervalo);
                                            $refrendar=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_refrendar]),$infoCadena[2]));
                                    }
                                    else
                                    {
                                            $tramite=ModuloGetVar('app','CentroAutorizacion','dias_tramite_os');
                                            $vigencia=ModuloGetVar('app','CentroAutorizacion','dias_vigencia');
                                            $vars=$result->GetRowAssoc($ToUpper = false);
                                            $Fecha=$this->FechaStamp($fecha_solicitud);
                                            $infoCadena = explode ('/',$Fecha);
                                            $intervalo=$this->HoraStamp($fecha_solicitud);
                                            $infoCadena1 = explode (':', $intervalo);
                                            $fechaAct=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$tramite),$infoCadena[2]));
                                            if($fechaAct < date("Y-m-d H:i:s"))
                                            {  $fechaAct=date("Y-m-d H:i:s");  }
                                            $Fecha=$this->FechaStamp($fechaAct);
                                            $infoCadena = explode ('/',$Fecha);
                                            $intervalo=$this->HoraStamp($fechaAct);
                                            $infoCadena1 = explode (':', $intervalo);
                                            $venc=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$vigencia),$infoCadena[2]));
                                            //fecha refrendar
                                            $Fecha=$this->FechaStamp($venc);
                                            $infoCadena = explode ('/',$Fecha);
                                            $intervalo=$this->HoraStamp($venc);
                                            $infoCadena1 = explode (':', $intervalo);
                                            $refrendar=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_refrendar]),$infoCadena[2]));
                                    }

                                    $query="SELECT nextval('os_maestro_numero_orden_id_seq')";
                                    $result=$dbconn->Execute($query);
                                    $numorden=$result->fields[0];

                                    $query = "INSERT INTO os_maestro
                                                                            (numero_orden_id,
                                                                            orden_servicio_id,
                                                                            sw_estado,
                                                                            fecha_vencimiento,
                                                                            hc_os_solicitud_id,
                                                                            fecha_activacion,
                                                                            cantidad,
                                                                            cargo_cups,
                                                                            fecha_refrendar)
                                    VALUES($numorden,$orden,1,'$venc',".$hc_os_solicitud_id.",'$fechaAct',1,'".$var[$i][cargo_cups]."','$refrendar')";
                                    $result=$dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                    $dbconn->RollbackTrans();
                                                    return false;
                                    }
                                    //insertar en hc_os_autorizaciones para que le aparezca a claudia
                                    $query = "INSERT INTO hc_os_autorizaciones
                                                                                    (autorizacion_int,autorizacion_ext,
                                                                                    hc_os_solicitud_id)
                                                                            VALUES(1,1,'".$hc_os_solicitud_id."')";
                                    $dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                    $dbconn->RollbackTrans();
                                                                    //echo "salida 5 <br>";
                                                                    return false;
                                    }

                                    $arr='';
																		//marca lorena
                                    $query = "SELECT a.*, b.*
                                                        FROM tmp_solicitud_manual as a, tmp_solicitud_manual_detalle as b
                                                        WHERE a.codigo=".$_SESSION['LABORATORIO']['SERIAL']."
                                                        AND a.tipo_id_paciente='".$_SESSION['DATOS_PACIENTE']['tipo_id']."'
                                                        AND a.paciente_id='".$_SESSION['DATOS_PACIENTE']['paciente_id']."'
                                                        AND a.tmp_solicitud_manual_id=b.tmp_solicitud_manual_id
                                                        AND a.usuario_id=".UserGetUID()." AND a.cargo_cups='".$var[$i][cargo_cups]."' 
																												AND a.tmp_solicitud_manual_id='".$var[$i][tmp_solicitud_manual_id]."' 
                                                        order by a.cargo_cups";
																											
																		//fin marca										
                                    $result = $dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                            $this->error = "Error al Cargar el Modulo";
                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                            return false;
                                    }
                                    while (!$result->EOF)
                                    {
                                        $arr[]=$result->GetRowAssoc($ToUpper = false);
                                        $result->MoveNext();
                                    }

                                    for($j=0; $j<sizeof($arr); $j++)
                                    {
                                                $query = "INSERT INTO os_maestro_cargos
                                                                                    (numero_orden_id,
                                                                                    tarifario_id,
                                                                                    cargo)
                                                                    VALUES($numorden,'".$arr[$j][tarifario_id]."','".$arr[$j][cargo]."')";
																																	
                                                $dbconn->Execute($query);
                                                if ($dbconn->ErrorNo() != 0)
                                                {
                                                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                            $dbconn->RollbackTrans();
                                                                            //echo "salida 6<br>";
                                                                            return false;
                                                }
                                    }//fin for os
                                    $query = "INSERT INTO os_internas(numero_orden_id,
                                                                                                            cargo,
                                                                                                            departamento)
                                                        VALUES($numorden,'".$var[$i][cargo_cups]."','".$_SESSION['LABORATORIO']['DPTO']."')";
                                    $dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                    $dbconn->RollbackTrans();
                                                                    //echo "salida 7<br>";
                                                                    return false;
                                    }
                                    //actualiza a 0 para indicar que ya paso por el proceso de autorizacion
                                    $query = "UPDATE hc_os_solicitudes SET    sw_estado=0
                                                        WHERE hc_os_solicitud_id=".$hc_os_solicitud_id."";
                                    $dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                                                    //echo "salida 8<br>";
                                                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                    $dbconn->RollbackTrans();
                                                    return false;
                                    }
                            }
                    }
            }
            $dbconn->CommitTrans();
            $this->frmError["MensajeError"] = 'LA ORDEN No. '.$orden.' FUE GENERADA.';

            $this->FrmOrdenar($_SESSION['DATOS_PACIENTE']['nombre'],$_SESSION['DATOS_PACIENTE']['tipo_id'],$_SESSION['DATOS_PACIENTE']['paciente_id']);
            return true;
    }*/
    function CrearOs()
    {
      list($dbconn) = GetDBconn();
			
      $query = "SELECT  a.*
                FROM    tmp_solicitud_manual as a
                WHERE   a.codigo=".$_SESSION['LABORATORIO']['SERIAL']."
                AND     a.tipo_id_paciente='".$_SESSION['DATOS_PACIENTE']['tipo_id']."'
                AND     a.paciente_id='".$_SESSION['DATOS_PACIENTE']['paciente_id']."'
                AND     a.usuario_id=".UserGetUID()."
                ORDER BY a.cargo_cups";
      $result = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) 
      {
        $this->error = "Error al Cargar el Modulo1";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      while (!$result->EOF)
      {
        $var[]=$result->GetRowAssoc($ToUpper = false);
        $result->MoveNext();
      }
      
      $result->Close();

      /*valida si hay ordenes o todas son solicitudes*/
      $query = "SELECT  count(a.codigo)
                FROM    tmp_solicitud_manual as a
                WHERE   a.codigo=".$_SESSION['LABORATORIO']['SERIAL']."
                AND a.tipo_id_paciente='".$_SESSION['DATOS_PACIENTE']['tipo_id']."'
                AND a.paciente_id='".$_SESSION['DATOS_PACIENTE']['paciente_id']."'
                AND a.usuario_id=".UserGetUID()."
                AND a.sw_os='1'";
      $result = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) 
      {
        $this->error = "Error al Cargar el Modulo1";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      if(!$result->EOF)
      {
        $conteoOS=$result->RecordCount();
        $result->Close();
      }

      if($conteoOS > 0)
      {
        $query="SELECT nextval('os_ordenes_servicios_orden_servicio_id_seq')";
        $result=$dbconn->Execute($query);
        $orden=$result->fields[0];
        $autoriza = (empty($_SESSION['DATOS_PACIENTE']['NumAutorizacion']))? "1":$_SESSION['DATOS_PACIENTE']['NumAutorizacion'];
        $query = "INSERT INTO os_ordenes_servicios
                    (
                      orden_servicio_id,
                      autorizacion_int,
                      autorizacion_ext,
                      plan_id,
                      tipo_afiliado_id,
                      rango,
                      semanas_cotizadas,
                      servicio,
                      tipo_id_paciente,
                      paciente_id,
                      usuario_id,
                      fecha_registro,
                      observacion
                    )
                  VALUES
                  (
                     ".$orden.",
                     ".$autoriza.",
                     ".$autoriza.",
                     ".$_SESSION['DATOS_PACIENTE']['plan_id'].",
                    '".$_SESSION['DATOS_PACIENTE']['PACIENTE']['tipo_afiliado_id']."',
                    '".$_SESSION['DATOS_PACIENTE']['PACIENTE']['rango']."',
                     ".$_SESSION['DATOS_PACIENTE']['PACIENTE']['semanas'].",
                    '".$_SESSION['LABORATORIO']['SERVICIO']."',
                    '".$_SESSION['DATOS_PACIENTE']['tipo_id']."',
                    '".$_SESSION['DATOS_PACIENTE']['paciente_id']."',
                     ".UserGetUID().",
                     NOW(),
                     ''
                  )";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error INSERT INTO os_ordenes_servicios";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $dbconn->RollbackTrans();
          return false;
        }

        $query3="INSERT INTO hc_os_solicitudes_manuales_datos_adicionales
                    (
                      orden_servicio_id,
                      cama,
                      departamento
                    )
                VALUES
                    (
                      $orden,
                      '".$_SESSION['DATOS_PACIENTE']['DATOS']['CAMA']."',
                      '".$_SESSION['DATOS_PACIENTE']['DATOS']['DEPARTAMENTO']."'
                    )";
        $dbconn->Execute($query3);
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al insertar en hc_os_solicitudes_manuales";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $dbconn->RollbackTrans();
          return false;
        }
      }

      for($i=0; $i<sizeof($var); $i++)
      {
        $query1="SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq')";
        $result=$dbconn->Execute($query1);
        $hc_os_solicitud_id=$result->fields[0];

        $query2 = "INSERT INTO hc_os_solicitudes
                      (
                        hc_os_solicitud_id, 
                        evolucion_id, 
                        cargo, 
                        os_tipo_solicitud_id, 
                        plan_id, 
                        paciente_id, 
                        tipo_id_paciente
                      )
                  VALUES
                      (
                        $hc_os_solicitud_id,
                        NULL,
                        '".$var[$i][cargo_cups]."', 
                        'APD',
                        ".$_SESSION['DATOS_PACIENTE']['plan_id'].",
                       '".$_SESSION['DATOS_PACIENTE']['paciente_id']."',
                       '".$_SESSION['DATOS_PACIENTE']['tipo_id']."'
                      )";
        $dbconn->Execute($query2);
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al insertar en hc_os_solicitudes";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $dbconn->RollbackTrans();
          return false;
        }

        $query3 = "INSERT INTO hc_os_solicitudes_apoyod
                      (
                        hc_os_solicitud_id, 
                        apoyod_tipo_id
                      )
                   VALUES
                      (
                        $hc_os_solicitud_id, 
                        '".$var[$i][apoyod_tipo_id]."'
                      );";
        $dbconn->Execute($query3);
        if ($dbconn->ErrorNo() != 0)
        {
                $this->error = "Error al insertar en hc_os_solicitudes_apoyod";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
        }

        if(empty($_SESSION['LABORATORIO']['DATOS']['IDDEPARTAMENTO']))
        {   $dpto='NULL';   }
        else
        {   $dpto="'".$_SESSION['LABORATORIO']['DATOS']['IDDEPARTAMENTO']."'";   }

        $query3="INSERT INTO hc_os_solicitudes_manuales
                    (
                      hc_os_solicitud_id,
                      fecha,
                      servicio,
                      profesional,
                      prestador,
                      observaciones,
                      tipo_id_paciente,
                      paciente_id,
                      fecha_resgistro,
                      usuario_id,
                      empresa_id,
                      tipo_afiliado_id,
                      rango,
                      semanas_cotizadas,
                      departamento
                    )
                 VALUES
                    (
                      $hc_os_solicitud_id, 
                      '".$_SESSION['LABORATORIO']['DATOS']['FECHA']."',
                      '".$_SESSION['LABORATORIO']['SERVICIO']."',
                      '".$_SESSION['LABORATORIO']['DATOS']['MEDICO']."',
                      '".$_SESSION['LABORATORIO']['DATOS']['ENTIDAD']."',
                      '".$_SESSION['LABORATORIO']['DATOS']['OBSERVACION']."',
                      '".$_SESSION['DATOS_PACIENTE']['tipo_id']."',
                      '".$_SESSION['DATOS_PACIENTE']['paciente_id']."',
                      'now()',
                      ".UserGetUID().",
                      '".$_SESSION['LABORATORIO']['EMPRESA_ID']."',
                      '".$_SESSION['DATOS_PACIENTE']['PACIENTE']['tipo_afiliado_id']."',
                      '".$_SESSION['DATOS_PACIENTE']['PACIENTE']['rango']."',
                       ".$_SESSION['DATOS_PACIENTE']['PACIENTE']['semanas'].",
                       $dpto
                    );";
        $dbconn->Execute($query3);
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al insertar en hc_os_solicitudes_manuales";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $dbconn->RollbackTrans();
          return false;
        }
        
        $sql  = "INSERT INTO hc_os_solicitudes_diagnosticos ";
        $sql .= "   ("; 
        $sql .= "     hc_os_solicitud_id,";
        $sql .= " 	  diagnostico_id,";
        $sql .= " 	  tipo_diagnostico,";
        $sql .= " 	  sw_principal "; 
        $sql .= "   )";
        $sql .= "SELECT ".$hc_os_solicitud_id." AS hc_os_solicitud_id,";
        $sql .= "       diagnostico_id,";
        $sql .= " 	    tipo_diagnostico,";
        $sql .= " 	    sw_principal ";
        $sql .= "FROM   tmp_solicitud_manual_dianosticos ";
        $sql .= "WHERE  tmp_solicitud_manual_id = ".$var[$i]['tmp_solicitud_manual_id']." ";
                
        $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "ERROR AL MOMENTO DE INGRESAR DIAGNOSTICOS";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $dbconn->RollbackTrans();
          return false;
        }
        
        $plan=$_SESSION['DATOS_PACIENTE']['plan_id'];
        if($var[$i][sw_os] == 1)
        {
                $query = "select * from os_tipos_periodos_planes
                                    where plan_id=".$plan." and cargo='".$var[$i][cargo_cups]."'";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                //echo "salida 2 <br>";
                                                return false;
                }
                if(!$result->EOF)
                {
                        $vars=$result->GetRowAssoc($ToUpper = false);
                        $Fecha=$this->FechaStamp($fecha_solicitud);
                        $infoCadena = explode ('/',$Fecha);
                        $intervalo=$this->HoraStamp($fecha_solicitud);
                        $infoCadena1 = explode (':', $intervalo);
                        $fechaAct=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_tramite_os]),$infoCadena[2]));
                        if($fechaAct < date("Y-m-d H:i:s"))
                        {  $fechaAct=date("Y-m-d H:i:s");  }
                        $Fecha=$this->FechaStamp($fechaAct);
                        $infoCadena = explode ('/',$Fecha);
                        $intervalo=$this->HoraStamp($fechaAct);
                        $infoCadena1 = explode (':', $intervalo);
                        $venc=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_vigencia]),$infoCadena[2]));
                        //fecha refrendar
                        $Fecha=$this->FechaStamp($venc);
                        $infoCadena = explode ('/',$Fecha);
                        $intervalo=$this->HoraStamp($venc);
                        $infoCadena1 = explode (':', $intervalo);
                        $refrendar=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_refrendar]),$infoCadena[2]));
                }
                else
                {                //si no hay unos tiempos especificos para el cargo toma los genericos
                        $query = "select * from os_tipos_periodos_tramites
                                            where cargo='".$var[$i][cargo_cups]."'";
                        $result=$dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                        //echo "salida 3 <br>";
                                                        return false;
                        }
                        if(!$result->EOF)
                        {
                                $vars=$result->GetRowAssoc($ToUpper = false);
                                $Fecha=$this->FechaStamp($fecha_solicitud);
                                $infoCadena = explode ('/',$Fecha);
                                $intervalo=$this->HoraStamp($fecha_solicitud);
                                $infoCadena1 = explode (':', $intervalo);
                                $fechaAct=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_tramite_os]),$infoCadena[2]));
                                if($fechaAct < date("Y-m-d H:i:s"))
                                {  $fechaAct=date("Y-m-d H:i:s");  }
                                $Fecha=$this->FechaStamp($fechaAct);
                                $infoCadena = explode ('/',$Fecha);
                                $intervalo=$this->HoraStamp($fechaAct);
                                $infoCadena1 = explode (':', $intervalo);
                                $venc=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_vigencia]),$infoCadena[2]));
                                //fecha refrendar
                                $Fecha=$this->FechaStamp($venc);
                                $infoCadena = explode ('/',$Fecha);
                                $intervalo=$this->HoraStamp($venc);
                                $infoCadena1 = explode (':', $intervalo);
                                $refrendar=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_refrendar]),$infoCadena[2]));
                        }
                        else
                        {
                                $tramite=ModuloGetVar('app','CentroAutorizacion','dias_tramite_os');
                                $vigencia=ModuloGetVar('app','CentroAutorizacion','dias_vigencia');
                                $vars=$result->GetRowAssoc($ToUpper = false);
                                $Fecha=$this->FechaStamp($fecha_solicitud);
                                $infoCadena = explode ('/',$Fecha);
                                $intervalo=$this->HoraStamp($fecha_solicitud);
                                $infoCadena1 = explode (':', $intervalo);
                                $fechaAct=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$tramite),$infoCadena[2]));
                                if($fechaAct < date("Y-m-d H:i:s"))
                                {  $fechaAct=date("Y-m-d H:i:s");  }
                                $Fecha=$this->FechaStamp($fechaAct);
                                $infoCadena = explode ('/',$Fecha);
                                $intervalo=$this->HoraStamp($fechaAct);
                                $infoCadena1 = explode (':', $intervalo);
                                $venc=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$vigencia),$infoCadena[2]));
                                //fecha refrendar
                                $Fecha=$this->FechaStamp($venc);
                                $infoCadena = explode ('/',$Fecha);
                                $intervalo=$this->HoraStamp($venc);
                                $infoCadena1 = explode (':', $intervalo);
                                $refrendar=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_refrendar]),$infoCadena[2]));
                        }

                        $query="SELECT nextval('os_maestro_numero_orden_id_seq')";
                        $result=$dbconn->Execute($query);
                        $numorden=$result->fields[0];

                        $query = "INSERT INTO os_maestro
                                                                (numero_orden_id,
                                                                orden_servicio_id,
                                                                sw_estado,
                                                                fecha_vencimiento,
                                                                hc_os_solicitud_id,
                                                                fecha_activacion,
                                                                cantidad,
                                                                cargo_cups,
                                                                fecha_refrendar)
                        VALUES($numorden,$orden,1,'$venc',".$hc_os_solicitud_id.",'$fechaAct',1,'".$var[$i][cargo_cups]."','$refrendar')";
                        $result=$dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                        }
                        //insertar en hc_os_autorizaciones para que le aparezca a claudia
                        $query = "INSERT INTO hc_os_autorizaciones
                                                                        (autorizacion_int,autorizacion_ext,
                                                                        hc_os_solicitud_id)
                                                                VALUES(1,1,'".$hc_os_solicitud_id."')";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                        $dbconn->RollbackTrans();
                                                        //echo "salida 5 <br>";
                                                        return false;
                        }

                        $arr='';
                        //marca lorena
                        $query = "SELECT a.*, b.*
                                            FROM tmp_solicitud_manual as a, tmp_solicitud_manual_detalle as b
                                            WHERE a.codigo=".$_SESSION['LABORATORIO']['SERIAL']."
                                            AND a.tipo_id_paciente='".$_SESSION['DATOS_PACIENTE']['tipo_id']."'
                                            AND a.paciente_id='".$_SESSION['DATOS_PACIENTE']['paciente_id']."'
                                            AND a.tmp_solicitud_manual_id=b.tmp_solicitud_manual_id
                                            AND a.usuario_id=".UserGetUID()." AND a.cargo_cups='".$var[$i][cargo_cups]."' 
                                            AND a.tmp_solicitud_manual_id='".$var[$i][tmp_solicitud_manual_id]."' 
                                            order by a.cargo_cups";
                                          
                        //fin marca										
                        $result = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                        }
                        while (!$result->EOF)
                        {
                            $arr[]=$result->GetRowAssoc($ToUpper = false);
                            $result->MoveNext();
                        }

                        for($j=0; $j<sizeof($arr); $j++)
                        {
                                    $query = "INSERT INTO os_maestro_cargos
                                                                        (numero_orden_id,
                                                                        tarifario_id,
                                                                        cargo)
                                                        VALUES($numorden,'".$arr[$j][tarifario_id]."','".$arr[$j][cargo]."')";
                                                      
                                    $dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0)
                                    {
                                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                $dbconn->RollbackTrans();
                                                                //echo "salida 6<br>";
                                                                return false;
                                    }
                        }//fin for os
                        $query = "INSERT INTO os_internas(numero_orden_id,
                                                                                                cargo,
                                                                                                departamento)
                                            VALUES($numorden,'".$var[$i][cargo_cups]."','".$_SESSION['LABORATORIO']['DPTO']."')";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                        $dbconn->RollbackTrans();
                                                        //echo "salida 7<br>";
                                                        return false;
                        }
                        //actualiza a 0 para indicar que ya paso por el proceso de autorizacion
                        $query = "UPDATE hc_os_solicitudes SET    sw_estado=0
                                            WHERE hc_os_solicitud_id=".$hc_os_solicitud_id."";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                                        //echo "salida 8<br>";
                                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                        }
                }
        }
      }
      
      $dbconn->CommitTrans();
            $this->frmError["MensajeError"] = 'LA ORDEN No. '.$orden.' FUE GENERADA.';

            $this->FrmOrdenar($_SESSION['DATOS_PACIENTE']['nombre'],$_SESSION['DATOS_PACIENTE']['tipo_id'],$_SESSION['DATOS_PACIENTE']['paciente_id']);
            return true;
    }
//--------------fin cambio dar


//MauroB
 /*
     * Esta funcion nos permite realizar una revision de las Ordenes de Servicio
     * Que tiene el paciente para ser autorizadas.
     */
     function ConteoOrdenesPaciente($pac_tipo_id,$pac_id,$departamento)
     {
          list($dbconn) = GetDBconn();
           $sql="

                        select count(*)
                        from
                                ( (select a.cargo
                from hc_os_solicitudes as a,
                                          hc_evoluciones as i,
                                            ingresos as j
                where j.tipo_id_paciente= '".$pac_tipo_id."'
                                    and j.paciente_id= '".$pac_id."'
                                    and i.ingreso=j.ingreso
                                    and i.evolucion_id=a.evolucion_id
                                    and a.sw_estado=1)
                                    UNION
                                    (
                                        select a.cargo
                                        from hc_os_solicitudes as a,
                                                    hc_os_solicitudes_manuales as i
                                        where i.tipo_id_paciente= '".$pac_tipo_id."'
                                            and i.paciente_id= '".$pac_id."'
                                            and a.hc_os_solicitud_id=i.hc_os_solicitud_id
                                            and a.sw_estado=1
                                    )
                                    ) AS a,
                            departamentos_cargos b
                            where  b.cargo = a.cargo
                            and b.departamento = '".$departamento."'

                                    ";          $res=$dbconn->Execute($sql);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Guardar en la Base de Datos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          $contador=$res->fields[0];
          $res->Close();
          if($contador >0){return 1;}else{return 0;}
     }



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
        *   Se encarga de validar el descueto aplicado a la liquidacion de la orden
        */
        function ValidaDescuentoLiquidacionOrden()
        {
            $valordescuento = $_REQUEST['valordescuento'];
            $sw_tipo = $_REQUEST['sw_tipo'];
            $indice  = $_REQUEST['indice'];
            $valor_cargo = $_REQUEST['valor_cargo'];

            $descuentos_cargos=$_SESSION['OS_ATENCION']['descuentos_cargos'];
            if ($_REQUEST['sw_tipo']==1)
            {
                $campo = "descuento_empresa";
            }
            elseif($_REQUEST['sw_tipo']==2)
            {
                $campo = "descuento_paciente";
            }
            //condicion de validacion
            //if($valordescuento > $valor_cargo)
						if($valordescuento < 0)
            {
                //$this->GetDescuento("VALOR EXCEDE EL MONTO VALIDO");
								$this->GetDescuento("EL VALOR DEBE SER POSITIVO");
            }
            else
            {
                $descuentos_cargos[$indice][$campo] = $valordescuento;
                $_SESSION['OS_ATENCION']['descuentos_cargos'] = $descuentos_cargos;
                //Si todo bien se llama a liquidar
                $this->LiquidacionOrden();
            }

            return true;
        }
    /*
    * Funcion donde se obtienen los cargos, que se adicionaran a la solicitud
    * de cargos antes realizada
    * 
    * @param integer $orden Numero de orden
    * @param string $servicio Identificador del servicio
    * @param integer $plan Identificador del plan
    * @param string $tipoIdPaciente Tipo de identificacion del paciente
    * @param string $idPaciente Numero de identificacion del paciente
    *
    * @return mixed
    */
    function ParagrafadosCargos($orden,$servicio,$plan,$tipoIdPaciente,$idPaciente)
    {
      $sql  = "SELECT DISTINCT PC.cargo_relacionado, ";
      $sql .= "       PC.cargo, ";
      $sql .= "       PC.cantidad, ";
      $sql .= "       PC.sw_factura, ";
      $sql .= "       PC.servicio, ";
      $sql .= "       CUI.descripcion AS decripcion_relacionado, ";
      $sql .= "       CUII.descripcion AS decripcion_base ";
      $sql .= "FROM   cargos_x_cargos PC ";
      $sql .= "       LEFT JOIN tmp_cuentas_cargos TM ";
      $sql .= "       ON( PC.cargo_relacionado = TM.cargo_cups AND ";
      $sql .= "           TM.numero_orden_id = ".$orden." ), ";
      $sql .= "       cups CUI, ";
      $sql .= "       cups CUII, ";
      $sql .= "       ( ";
      $sql .= "         SELECT  cargo_cups ";
      $sql .= "         FROM    os_maestro  ";
      $sql .= "         WHERE   numero_orden_id = ".$orden." ";
      $sql .= "       ) OS ";
      $sql .= "WHERE  PC.cargo = OS.cargo_cups ";
      $sql .= "AND    PC.cargo_relacionado = CUI.cargo ";
      $sql .= "AND    PC.cargo = CUII.cargo ";
      $sql .= "AND	  PC.servicio = '".$servicio."' ";
      $sql .= "AND    TM.sw_factura IS NULL ";
			
      $cxn = new ConexionBD();
      
      $datos = array();
      if(!$rst = $cxn->ConexionBaseDatos($sql))
        return false;

      while(!$rst->EOF)
      {
          $datos[] =  $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
      }
      $rst->Close();
      if(!empty($datos))
      {
        $sql  = "SELECT DISTINCT a.numerodecuenta ";
        $sql .= "FROM   cuentas a, ";
        $sql .= "       servicios c, ";
        $sql .= "       ingresos d, ";
        $sql .= "       planes f ";
  			$sql .= "WHERE	c.servicio='".$servicio."' ";
        $sql .= "AND    d.tipo_id_paciente = '". $tipoIdPaciente."' ";
        $sql .= "AND    d.paciente_id='". $idPaciente."' ";
        $sql .= "AND    a.ingreso=d.ingreso ";
        $sql .= "AND    d.estado = '1' ";
        $sql .= "AND    a.estado = '1' ";
        $sql .= "AND    c.sw_cargo_multidpto = '1' ";
        $sql .= "AND    a.plan_id = ". $plan." ";
        $sql .= "AND    a.plan_id=f.plan_id ";
        
        if(!$rst = $cxn->ConexionBaseDatos($sql))
          return false;

        if(!$rst->EOF)
        {
          $numero =  $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
        }
        $rst->Close();
        
        foreach($datos as $k => $dtl)
          $datos[$k]['numerodecuenta'] = $numero['numerodecuenta'];
      }
      return $datos;
    }
    /*
    * Funcion donde se obtienen los cargos, que se adicionaran a la solicitud
    * de cargos antes realizada
    * 
    * @param integer $orden Numero de orden
    * @param string $servicio Identificador del servicio
    * @param integer $plan Identificador del plan
    * @param string $tipoIdPaciente Tipo de identificacion del paciente
    * @param string $idPaciente Numero de identificacion del paciente
    *
    * @return mixed
    */
    function ParagrafadosInsumos($orden,$servicio,$plan,$tipoIdPaciente,$idPaciente,$empresa,$centro,$dpto)
    {             
      $sql  = "SELECT DISTINCT CI.codigo_producto, ";
      $sql .= "       CI.cargo, ";
      $sql .= "       CI.cantidad, ";
      $sql .= "       CI.sw_factura, ";
      $sql .= "       CI.servicio, ";
      $sql .= "       BD.bodega, ";
      $sql .= "       IV.precio_venta ";
      $sql .= "FROM   cargos_x_insumos CI ";
      $sql .= "       LEFT JOIN tmp_cuenta_imd TM ";
      $sql .= "       ON( CI.codigo_producto = TM.codigo_producto AND ";
      $sql .= "           TM.numero_orden_id = ".$orden." ), ";
      $sql .= "       ( ";
      $sql .= "         SELECT  cargo_cups ";
      $sql .= "         FROM    os_maestro  ";
      $sql .= "         WHERE   numero_orden_id = ".$orden." ";
      $sql .= "       ) OS, ";
      $sql .= "       bodegas BD, ";
      $sql .= "       existencias_bodegas EB,";
      $sql .= "       inventarios_productos ID, ";
      $sql .= "       inv_grupos_inventarios IG, ";
      $sql .= "       inventarios IV ";
      $sql .= "WHERE  CI.cargo = OS.cargo_cups ";
      $sql .= "AND    CI.codigo_producto = EB.codigo_producto ";
      $sql .= "AND    EB.codigo_producto = ID.codigo_producto ";
      $sql .= "AND    EB.empresa_id = IV.empresa_id ";
      $sql .= "AND    EB.codigo_producto = ID.codigo_producto ";
      $sql .= "AND    EB.bodega = BD.bodega ";
      $sql .= "AND    ID.grupo_id=IG.grupo_id ";
      $sql .= "AND    EB.codigo_producto = IV.codigo_producto ";
      $sql .= "AND    (IG.sw_medicamento='1' OR IG.sw_insumos='1') ";
      $sql .= "AND	  CI.servicio = '".$servicio."' ";
      $sql .= "AND    BD.sw_bodega_defecto = '1' ";
      $sql .= "AND    EB.existencia > 0 ";
      $sql .= "AND    BD.empresa_id = '".$empresa."' ";
      $sql .= "AND    BD.departamento = '".$dpto."' ";
      $sql .= "AND    TM.sw_factura IS NULL ";
	  
      if($centro)
        $sql .= "AND    BD.centro_utilidad = '".$centro."' ";
			
      $cxn = new ConexionBD();
     
      $datos = array();
      if(!$rst = $cxn->ConexionBaseDatos($sql))
        return false;

      while(!$rst->EOF)
      {
        $datos[] =  $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      if(!empty($datos))
      {
        $sql  = "SELECT DISTINCT a.numerodecuenta ";
        $sql .= "FROM   cuentas a, ";
        $sql .= "       servicios c, ";
        $sql .= "       ingresos d, ";
        $sql .= "       planes f ";
  			$sql .= "WHERE	c.servicio='".$servicio."' ";
        $sql .= "AND    d.tipo_id_paciente = '". $tipoIdPaciente."' ";
        $sql .= "AND    d.paciente_id='". $idPaciente."' ";
        $sql .= "AND    a.ingreso=d.ingreso ";
        $sql .= "AND    d.estado = '1' ";
        $sql .= "AND    a.estado = '1' ";
        $sql .= "AND    c.sw_cargo_multidpto = '1' ";
        $sql .= "AND    a.plan_id = ". $plan." ";
        $sql .= "AND    a.plan_id=f.plan_id ";
        
        if(!$rst = $cxn->ConexionBaseDatos($sql))
          return false;

        if(!$rst->EOF)
        {
          $numero =  $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
        }
        $rst->Close();
        
        foreach($datos as $k => $dtl)
          $datos[$k]['numerodecuenta'] = $numero['numerodecuenta'];
      }
      return $datos;
    }
    /**
    *
    */
    function AgregarCargosTmp($cargosSel,$request)
    {
      $dtSession = SessionGetVar("LABORATORIO");
      $dtSessOrd = SessionGetVar("OS_ATENCION");

      $cxn = new ConexionBD();
      $cxn->ConexionTransaccion();
      
      foreach($cargosSel as $k1 => $dt1)
      {
        foreach($dt1 as $k2 => $dt2)
        {
          foreach($dt2 as $k3 => $dt3)
          {
            if($dt3['cargo'])
            {
              $campo1 = $valor1 = "";
              if($dt3['numerodecuenta'])
              {
                $campo1 = "numerodecuenta,";
                $valor1 = $dt3['numerodecuenta'].",";
              }
              
							$sql  = "INSERT INTO tmp_cuentas_cargos ";
              $sql .= "( ";
              $sql .= "   empresa_id, ";
              $sql .= "   centro_utilidad, ";
              $sql .= "   ".$campo1." ";
              $sql .= "   numero_orden_id, ";
              $sql .= "   departamento, ";
              $sql .= "   tarifario_id, ";
              $sql .= "   tipo_cargo, ";
              $sql .= "   grupo_tipo_cargo, ";
              $sql .= "   cargo, ";
              $sql .= "   cantidad, ";
              $sql .= "   tipo_unidad_id, ";
              $sql .= "   precio, ";
              $sql .= "   servicio_cargo, ";
              $sql .= "   plan_id, ";
              $sql .= "   cargo_cups, ";
              $sql .= "   grupo_tarifario_id, ";
              $sql .= "   subgrupo_tarifario_id, ";
              $sql .= "   nivel, ";
              $sql .= "   sw_honorarios, ";
              $sql .= "   concepto_rips, ";
              $sql .= "   sw_factura ";
              $sql .= ") ";
              $sql .= "VALUES ";
              $sql .= "( ";
              $sql .= "    '".$dtSession['EMPRESA_ID']."',";
              $sql .= "    '".$dtSession['CENTROUTILIDAD']."',";
              $sql .= "     ".$valor1."";
              $sql .= "     ".$k1.",";
              $sql .= "    '".$dtSession['DPTO']."',";
              $sql .= "    '".$k2."',";
              $sql .= "    '".$dt3['tipo_cargo']."',";
              $sql .= "    '".$dt3['grupo_tipo_cargo']."',";
              $sql .= "    '".$dt3['cargo']."',";
              $sql .= "     ".$dt3['cantidad'].",";
              $sql .= "    '".$dt3['tipo_unidad_id']."',";
              $sql .= "     ".$dt3['precio'].",";
              $sql .= "    '".$dt3['servicio']."',";
              $sql .= "     ".$request['plan_id'].",";
              $sql .= "    '".$dt3['cargo_cups']."',";
              $sql .= "    '".$dt3['grupo_tarifario_id']."',";
              $sql .= "    '".$dt3['subgrupo_tarifario_id']."',";
              $sql .= "    '".$dt3['nivel']."',";
              $sql .= "    '".$dt3['sw_honorarios']."',";
              $sql .= "    '".$dt3['concepto_rips']."',";
              $sql .= "    '".$dt3['sw_factura']."'";
              $sql .= ") ";
              
              if(!$rst = $cxn->ConexionTransaccion($sql))
              {
                $this->mensajeDeError = $cxn->mensajeDeError;
                return false;
              }
            }
          }
        }
      }
      $cxn->Commit();
      return true;
    }
    /**
    *
    */
    function AgregarProductoTmpInventario($productos,$plan)
    {
      $dtSession = SessionGetVar("LABORATORIO");
      
      $cxn = new ConexionBD();
      
      $cxn->ConexionTransaccion();
      foreach($productos as $k1 => $dt1)
      {
        foreach($dt1 as $k2 => $dt2)
        {
          $valortotal = $dt2['cantidad']*$dt2['precio_venta'];
          $sql  = "INSERT INTO tmp_cuenta_imd ";
          $sql .= "( ";
          $sql .= "   empresa_id, ";
          $sql .= "   centro_utilidad, ";
          $sql .= "   departamento, ";
          $sql .= "   bodega, ";
          $sql .= "   numero_orden_id, ";
          $sql .= "   codigo_producto, ";
          $sql .= "   cantidad, ";
          $sql .= "   precio, ";
          $sql .= "   fecha_cargo, ";
          $sql .= "   plan_id, ";
          $sql .= "   servicio_cargo, ";
          $sql .= "   sw_factura ";
          $sql .= ") ";
          $sql .= "VALUES ";
          $sql .= "( ";
          $sql .= "   '".$dtSession['EMPRESA_ID']."', ";
          $sql .= "   '".$dtSession['CENTROUTILIDAD']."', ";
          $sql .= "   '".$dtSession['DPTO']."', ";
          $sql .= "   '".$dt2['bodega']."', ";
          $sql .= "    ".$k1.", ";
          $sql .= "   '".$dt2['codigo_producto']."', ";
          $sql .= "    ".$dt2['cantidad'].", ";
          $sql .= "    ".$dt2['precio_venta'].", ";
          $sql .= "    NOW(), ";
          $sql .= "    ".$plan.", ";
          $sql .= "    ".$dt2['servicio'].", ";
          $sql .= "    ".$dt2['sw_factura']." ";
          $sql .= ") ";
          
          if(!$rst = $cxn->ConexionTransaccion($sql))
          {
            $this->mensajeDeError = $cxn->mensajeDeError;
            return false;
          }
        }
      }
      $cxn->Commit();
      return true;
    }
    /**
    * Busca si el numero de identificacion del paciente es  numerico  o alfa numerico 
    * @access public
    * @return array
    */
    function Consulta_tipo_dato($empresa_id,$centro_utilidad)
    {
      $sql="SELECT sw_alfanumerico  FROM pacientes_alfanumerico
                        WHERE empresa_id='".$empresa_id."' and centro_utilidad='".$centro_utilidad."'  ";
       
          $cxn = new ConexionBD();

          $datos = array();
          if(!$rst = $cxn->ConexionBaseDatos($sql))
          return false;

          while(!$rst->EOF)
          {
          $datos =  $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
          }
          $rst->Close();
        return $datos;
    }
    /**
    * Metdoo donde se obtienen los departamentos de punto de tomado
    * segun el departamento de la orden de servicio
    *
    * @param integer $orden_servicio_id Numero de la orden de servicio
    * 
    * @return mixed 
    */
    function ObtenerDepartamentosPuntoTomado($orden_servicio_id)
    {
      $sql  = "SELECT DE.departamento, ";
      $sql .= "       DE.descripcion,";
      $sql .= "       DT.sw_defecto ";
      $sql .= "FROM   os_ordenes_servicios OS, ";
      $sql .= "       departamentos_punto_tomado DT,";
      $sql .= "       departamentos DE ";
      $sql .= "WHERE  OS.orden_servicio_id = ".$orden_servicio_id." ";
      $sql .= "AND    OS.departamento = DT.departamento ";
      $sql .= "AND    DT.departamento_pt = DE.departamento ";
      $sql .= "ORDER BY DE.descripcion ";

      $cxn = new ConexionBD();
      //$cxn->debug = true;
      $datos = array();
      if(!$rst = $cxn->ConexionBaseDatos($sql))
        return false;

      while(!$rst->EOF)
      {
        $datos[] =  $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
	
	/*
	*Funcion que trae el email del paciente
	*@ 19-VI-2012
	*@ author: Steven H. Gamboa
	*/
	function TraerEmail($id,$tipo)
	{
		$sql="SELECT email 
			  FROM pacientes
			  WHERE paciente_id='".$id."'
			  AND   tipo_id_paciente='".$tipo."'";
			  
			  $cxn = new ConexionBD();
        //$cxn->debug = true;
        $datos = array();
        if (!$rst = $cxn->ConexionBaseDatos($sql))
            return false;

        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }

        $rst->Close();
        return $datos;
	}
	
	
	/*
	*Funcion que trae orden_servicio_id, numero_orden_id, hc_os_solicitud_id, hc_os_solicitud_id, os_tipo_solicitud_id
	*@ 25-VI-2012
	*@ author: Steven H. Gamboa
	*/
	function TraerOsTipoSolicitud($orden)
	{
		/*$sql="	SELECT  
						HCS.hc_os_solicitud_id,
						HCS.os_tipo_solicitud_id		
				FROM	os_maestro OSM INNER JOIN
						hc_os_solicitudes HCS ON(OSM.hc_os_solicitud_id = HCS.hc_os_solicitud_id)
				WHERE	OSM.numero_orden_id	='".$orden."'";
		*/
		
		$sql="	SELECT
						OSM.numero_orden_id,
						OSM.hc_os_solicitud_id,
						HCS.os_tipo_solicitud_id
				FROM	os_maestro OSM, hc_os_solicitudes HCS
				WHERE	OSM.numero_orden_id='".$orden."'
				AND		HCS.hc_os_solicitud_id = OSM.hc_os_solicitud_id";
		
		$cxn = new ConexionBD();
        
		$datos = array();
        
		if (!$rst = $cxn->ConexionBaseDatos($sql))
        {
			return false;
		}

        while (!$rst->EOF)
		{
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
	}
	
	
	/*
	*Funcion que trae la observacion
	*@ 25-VI-2012
	*@ author: Steven H. Gamboa
	*/
	
	function TraerObservaciones($tipo_solicitud,$hc_os_solicitud_id)
	{
		if($tipo_solicitud == 'APD')
		{
			$tabla = 'hc_os_solicitudes_apoyod';
		}
		if($tipo_solicitud == 'QX')
		{
			$tabla = 'hc_os_solicitudes_acto_qx';
		}
		if($tipo_solicitud == 'PNQ')
		{
			$tabla = 'hc_os_solicitudes_no_quirurgicos';
		}
		if($tipo_solicitud == 'INT')
		{
			$tabla = 'hc_os_solicitudes_interconsultas';
		}
		
		$sql="	SELECT observacion
				FROM ".$tabla. "
				WHERE hc_os_solicitud_id='".$hc_os_solicitud_id."'";
		
		$cxn = new ConexionBD();
        
		$datos = array();
        
		if (!$rst = $cxn->ConexionBaseDatos($sql))
        {
			return false;
		}
        while (!$rst->EOF)
		{
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
	}
	
	/*
	*Funcion que trae la cantidad asignada para el cargo
	*@ 24-VII-2012
	*@ author: Steven H. Gamboa
	*/
	function MostrarCantidadCargo($numero_orden_id)
	{
		$sql="  SELECT 	cantidad, transaccion
				FROM	tmp_cuentas_cargos
				WHERE	numero_orden_id='".$numero_orden_id."'";
		
		$cxn = new ConexionBD();
        $datos = array();
		
		if (!$rst = $cxn->ConexionBaseDatos($sql))
        {
			return false;
		}
        while (!$rst->EOF)
		{
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
	}
	
	/*
	*Funcion que trae la cantidad asignada para el cargo
	*@ 25-VII-2012
	*@ author: Steven H. Gamboa
	*/
	function MostrarCantidadCargo2($transaccion)
	{
		$sql="  SELECT 	cantidad
				FROM	cuentas_detalle
				WHERE	transaccion='".$transaccion."'";
		
		$cxn = new ConexionBD();
        $datos = array();
		
		if (!$rst = $cxn->ConexionBaseDatos($sql))
        {
			return false;
		}
        while (!$rst->EOF)
		{
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
	}
	
	/*
	*Funcion que trae la cantidad asignada para el cargo y el el id transaccion de la tabla cuentas_detalle
	*@ 26-VII-2012
	*@ author: Steven H. Gamboa
	*/
	function MostrarCantidadCargoCuentasDetalle($numero_orden_id,$cargo_cups)
	{
		$sql="  SELECT	CD.transaccion,
						CD.cantidad,
						CD.cargo,
						CD.numero_orden_id,
						CD.cargo_cups,
						C.descripcion
				FROM	cuentas_detalle as CD, cups as C
				WHERE	CD.numero_orden_id = ".$numero_orden_id."
				AND		CD.cargo_cups ='".$cargo_cups."'
				AND		CD.cargo_cups=C.cargo";
		
		
		/*$sql="  SELECT  transaccion,
						cantidad,
						cargo
				FROM	cuentas_detalle
				WHERE	numero_orden_id= ".$numero_orden_id."";
		*/
		$cxn = new ConexionBD();
        $datos = array();
		
		if (!$rst = $cxn->ConexionBaseDatos($sql))
        {
			return false;
		}
        while (!$rst->EOF)
		{
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
	}
	
  }
?>