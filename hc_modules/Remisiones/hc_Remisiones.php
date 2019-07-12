<?php

/**
* Submodulo de Remisiones.
*
* Submodulo para manejar las remisiones a otros centros.
* @author Tizziano Perea Ocoro <t_perea@hotmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_Remisiones.php,v 1.4 2006/07/17 18:50:57 tizziano Exp $
*/


/**
* Remisiones
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
*/

class Remisiones extends hc_classModules
{

      var $limit;
      var $conteo;


/**
* Esta función Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/

    function Remisiones()
    {
        $this->limit=5;
        return true;
    }


/**
* Esta función retorna los datos de concernientes a la version del submodulo
* @access private
*/

    function GetVersion()
    {
        $informacion=array(
        'version'=>'1',
        'subversion'=>'0',
        'revision'=>'0',
        'fecha'=>'01/27/2005',
        'autor'=>'TIZZIANO PEREA OCORO',
        'descripcion_cambio' => '',
        'requiere_sql' => false,
        'requerimientos_adicionales' => '',
        'version_kernel' => '1.0'
        );
        return $informacion;
    }


/**
* Esta función verifica si este submodulo fue utilizado para la atencion de un paciente.
*
* @access private
* @return text Datos HTML de la pantalla.
*/

    function GetEstado()
    {
        $pfj=$this->frmPrefijo;
        list($dbconn) = GetDBconn();

        $query="SELECT A.motivo_remision_id, B.descripcion_otro_motivo
                FROM hc_conducta_remision_motivos AS A, hc_conducta_remision AS B
                WHERE A.evolucion_id=".$this->evolucion."
                AND B.evolucion_id=".$this->evolucion."
                AND A.ingreso=".$this->ingreso."
                AND B.ingreso=".$this->ingreso.";";

        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        if (!$resulta->EOF)
        {
            $estado = $resulta->GetRows();
        }

        if (empty($estado))
        {
            return false;
        }
        else
        {
            return true;
        }
    }

/**
* Esta función retorna la presentación del submodulo (consulta o inserción).
* @author Tizziano Perea Ocoro <t_perea@hotmail.com>
* @access public
* @return text Datos HTML de la pantalla.
* @param text Determina la acción a realizar.
*/

     function GetForma()
     {
          $pfj=$this->frmPrefijo;
          if(empty($_REQUEST['accion'.$pfj]))
          {
               UNSET ($_SESSION['prueba']);
               $this->frmForma();
          }
          else
          {
               if($_REQUEST['Buscar'.$pfj] == 'BUSQUEDA')
               {
                    $this->Busqueda($_REQUEST['criterio'.$pfj],$_REQUEST['codigo'.$pfj],$_REQUEST['descripcion'.$pfj]);
                    return true;
               }
               elseif($_REQUEST['accion'.$pfj] == 'Busqueda')
               {
                    $this->Busqueda($_REQUEST['criterio'.$pfj],$_REQUEST['codigo'.$pfj],$_REQUEST['descripcion'.$pfj]);
                    return true;
               }
     
               if ($_REQUEST['guardar'.$pfj]== 'GUARDAR')
               {
                    if($this->Insert_Motivos_Remision()==true)
                    {
                         if ($_SESSION['INSERTO'] == '1')
                         {
                              $this->frmFormaConfirmacion();
                         }
                         else
                         {
                              $this->frmForma();
                              unset ($_SESSION['INSERTO']);
                         }
                    }
                    else
                    {
                         $this->frmForma();
                    }
               }
          }
          return $this->salida;
     }


    function GetConsulta()
    {
        $this->frmConsulta();
        return $this->salida;
    }



      /**
      * Busca los niveles de atencion
      * @access public
      * @return array
      * @param string plan_id
      */
        function Niveles()
        {
            $pfj=$this->frmPrefijo;
            list($dbconn) = GetDBconn();
            $query="SELECT distinct a.descripcion, a.nivel
                    FROM niveles_atencion as a, centros_remision as b
                    WHERE a.nivel=b.nivel ORDER BY a.nivel";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
            }

            while(!$result->EOF){
            $niveles[]=$result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
            }

            $result->Close();
            return $niveles;
        }


      /**
      *
      */
        function CentrosRemisionNivel($nivel)
        {
            $pfj=$this->frmPrefijo;
            list($dbconn) = GetDBconn();
            $query = "SELECT * FROM centros_remision WHERE nivel=$nivel";
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

      /**
      *
      */
        function CentrosRemision()
        {
            $pfj=$this->frmPrefijo;
            list($dbconn) = GetDBconn();
            $query = "SELECT * FROM centros_remision";
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


        /**
        *
        */
        function Busqueda($opcion,$codigo,$descripcion)
        {
            $pfj=$this->frmPrefijo;
            list($dbconn) = GetDBconn();
            $descripcion =STRTOUPPER($descripcion);
            if(empty($opcion) AND  empty($codigo))
            {
                $opcion=$_REQUEST['criterio'.$pfj];
                $codigo=$_REQUEST['codigo'.$pfj];
                $descripcion=STRTOUPPER($_REQUEST['descripcion'.$pfj]);
            }

            $filtroTipoCodigo = '';
            $busqueda1 = '';
            $busqueda2 = '';

            if ($codigo != '')
            {  $busqueda1 =" AND centro_remision LIKE '%$codigo%'";  }

            if ($descripcion != '')
            {  $busqueda2 ="AND descripcion LIKE '%$descripcion%'";  }

            if ($opcion != 'Todas' and !EMPTY($opcion))
            {  $filtroTipoCodigo ="AND nivel='$opcion'";  }

            if(empty($_REQUEST['conteo'.$pfj]))
            {
                $query = "SELECT count(*) FROM centros_remision
                            WHERE centro_remision is not null
                            $busqueda1 $busqueda2 $filtroTipoCodigo";
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
            {  $this->conteo=$_REQUEST['conteo'.$pfj];  }

            if(!$_REQUEST['Of'.$pfj])
            {
                $Of='0';
            }
            else
            {
                $Of=$_REQUEST['Of'.$pfj];
                if($Of > $this->conteo)
                {
                    $Of=0;
                    $_REQUEST['Of'.$pfj]=0;
                    $_REQUEST['paso'.$pfj]=1;
                }
            }

            $query = "SELECT * FROM centros_remision
                        WHERE centro_remision is not null
                        $busqueda1 $busqueda2 $filtroTipoCodigo
                        order by nivel LIMIT ".$this->limit." OFFSET $Of;";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            if(!$resulta->EOF)
            {
                while(!$resulta->EOF)
                {
                $var[]=$resulta->GetRowAssoc($ToUpper = false);
                $resulta->MoveNext();
                }
            }

            if($this->conteo==='0')
            {
                $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
            }

            $this->frmForma($_REQUEST['datos'.$pfj],$var);
            return true;
        }


        /**
        * Busca los Motivos de Remision
        * @author Tizziano Perea Ocoro <t_perea@hotmail.com>
        * @access public
        * @return array
        * @param string plan_id
        */
        function Motivos_Remision()
        {
            $pfj=$this->frmPrefijo;
            list($dbconn) = GetDBconn();
            $query = "SELECT motivo_remision_id, descripcion
                    FROM hc_motivos_remision;";
            $resulta=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $i=0;
            while(!$resulta->EOF)
            {
            $motivos[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
            }
            return $motivos;
        }


        /**
        * Busca los tipos de referencia
        * @author Tizziano Perea Ocoro <t_perea@hotmail.com>
        * @access public
        * @return array
        * @param string plan_id
        */
        function Get_Referencia()
        {
            $pfj=$this->frmPrefijo;
            list($dbconn) = GetDBconn();
            $query = "SELECT *
                      FROM hc_tipo_remision
                      ORDER BY indice_orden ASC;";
            $resulta=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            while(!$resulta->EOF)
            {
               $ref[]=$resulta->GetRowAssoc($ToUpper = false);
               $resulta->MoveNext();
            }
            $resulta->Close();
            return $ref;
        }


        /**
        * Busca los tipos de niveles de los centros de remision
        * @author Tizziano Perea Ocoro <t_perea@hotmail.com>
        * @access public
        * @return array
        * @param string plan_id
        */
        function Get_Niveles()
        {
            $pfj=$this->frmPrefijo;
            list($dbconn) = GetDBconn();
            $query = "SELECT *
                      FROM niveles_atencion;";
            $resulta=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            while(!$resulta->EOF)
            {
               $niveles[]=$resulta->GetRowAssoc($ToUpper = false);
               $resulta->MoveNext();
            }
            $resulta->Close();
            return $niveles;
        }


        /**
        * Busca los Motivos de Remision
        * @author Tizziano Perea Ocoro <t_perea@hotmail.com>
        * @access public
        * @return array
        * @param string plan_id
        */
        function Get_Numero_Motivos_Remision()
        {
            $pfj=$this->frmPrefijo;
            list($dbconn) = GetDBconn();
            $query = "SELECT COUNT (*)
                      FROM hc_motivos_remision;";
            $resulta=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            list ($num_motivo)=$resulta->FetchRow();
            $resulta->close();
            $num_motivo = ceil (($num_motivo + 1)/4);
            return $num_motivo;
        }


        /**
        * Inserta todo lo relacionado con la remision del paciente
        * @author Tizziano Perea Ocoro <t_perea@hotmail.com>
        * @access public
        * @return array
        * @param string plan_id
        */
        function Insert_Motivos_Remision()
        {
            $pfj=$this->frmPrefijo;

            $obser = $_REQUEST['observacion'.$pfj];
            $refe = $_REQUEST['ref'.$pfj];
            $niv = $_REQUEST['niveles'.$pfj];
            $amb = $_REQUEST['ambulancia'.$pfj];
            $centro = $_REQUEST['centro'];
            $otro = $_REQUEST['otro'.$pfj];

            for($p=0;$p<sizeof($_REQUEST['vect'.$pfj]);$p++)
            {
                $motivo = $_REQUEST['sno'.$p];
                if (!empty ($motivo))
                {
                    break;
                }
            }

            if (empty($motivo) && empty($otro))
            {
                $this->frmError["MensajeError"] = "FALTA INFORMACION ACERCA DEL MOTIVO DE REFERENCIA";
                $this->frmForma();
                return true;
            }

            /*-----------------------------------------------------------------------------
            SELECCIONO UN NUEVO REGISTRO PARA ACTUALIZAR DATOS
            O PARA CREAR UN NUEVO REGISTRO
            -----------------------------------------------------------------------------*/
            list($dbconn) = GetDBconn();
            $query = "SELECT *
                      FROM hc_conducta_remision
                      WHERE ingreso = ".$this->ingreso."
                      AND evolucion_id = ".$this->evolucion.";";

            $resulta=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            while(!$resulta->EOF)
            {
               $indice=$resulta->GetRowAssoc($ToUpper = false);
               $resulta->MoveNext();
            }

            /*-----------------------------------------------------------------------------
            INSERTO EN CONDUCTA REMISIONES TODOS LOS DATOS PERTIENTES A LA REMISION
            -----------------------------------------------------------------------------*/
            if (!empty($obser))
            {
                $obser = "'$obser'";
            }
            else
            {
                $obser = 'NULL';
            }

            if (empty($refe)) $refe=0;
            if ($niv == -1) $niv='NULL';
            if (empty($amb)) $amb=0;

            if (!empty($otro))
            {
                $otro = "'$otro'";
            }
            else
            {
	            $otro = 'NULL';
            }

            if ($indice[ingreso] != $this->ingreso && $indice[evolucion_id] != $this->evolucion)
            {
                $query = "INSERT INTO hc_conducta_remision
                        (ingreso, evolucion_id, descripcion_otro_motivo,
                        observaciones, tipo_remision, traslado_ambulancia,
                        nivel_centro_remision,usuario_id)
                        VALUES
                        (".$this->ingreso.",".$this->evolucion.",$otro,
                        $obser,$refe,$amb,$niv,".UserGetUID().")";

                $result=$dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Ocurrió un error al intentar ingresar el signo vital del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    return false;
                }
            }
            else
            {
                $query = "UPDATE hc_conducta_remision
                        SET descripcion_otro_motivo = $otro,
                            observaciones = $obser,
                            tipo_remision = $refe,
                            traslado_ambulancia = $amb,
                            nivel_centro_remision = $niv,
                            usuario_id = ".UserGetUID()."
                        WHERE ingreso = ".$this->ingreso."
                        AND evolucion_id = ".$this->evolucion.";";

                $result=$dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Ocurrió un error al intentar ingresar el signo vital del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    return false;
                }
            }

        /*-----------------------------------------------------------------------------
            SELECCIONO UN NUEVO REGISTRO PARA ACTUALIZAR DATOS
            O PARA CREAR UN NUEVO REGISTRO
            -----------------------------------------------------------------------------*/

            list($dbconn) = GetDBconn();
            $query = "SELECT *
                    FROM hc_conducta_remision_centros
                    WHERE ingreso = ".$this->ingreso."
                    AND evolucion_id = ".$this->evolucion.";";

            $resulta=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            while(!$resulta->EOF)
            {
               $center=$resulta->GetRowAssoc($ToUpper = false);
               $resulta->MoveNext();
            }


            /*-----------------------------------------------------------------------------
            INSERTO EL CENTRO DE REMISION DONDE SERA ENVIADO EL PACIENTE
            -----------------------------------------------------------------------------*/
            $_SESSION['CODCENTRO'] = $centro;
            if (!empty($centro))
            {
	            $centro="'$centro'";
            }
            else
            {
	            $centro= 'NULL';
            }


            if ($center[ingreso] != $this->ingreso && $center[evolucion_id] != $this->evolucion)
            {
                $query = "INSERT INTO hc_conducta_remision_centros
                        (ingreso, evolucion_id, centro_remision)
                        VALUES
                        (".$this->ingreso.",".$this->evolucion.",
                        $centro)";

            $result=$dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al ejecutar la conexion";
                $this->mensajeDeError = "Ocurrió un error al intentar ingresar el signo vital del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                return false;
            }
            else
            {
                $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
            }
            }
            else
            {
                $query = "UPDATE hc_conducta_remision_centros
                                SET centro_remision = $centro
                                WHERE evolucion_id = ".$this->evolucion."
                                AND ingreso = ".$this->ingreso.";";

                $result=$dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Ocurrió un error al intentar ingresar el signo vital del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    return false;
                }
                else
                {
                    $this->frmError["MensajeError"]="SE ACTUALIZO EL CENTRO DONDE SERA REMITIDO EL PACIENTE .";
                }
            }

            /*-----------------------------------------------------------------------------
            SE UTILIZA PARA ACTUALIZAR LOS MOTIVOS DE REFERENCIA
            POR LOS CUALES LOS PACIENTES SON REMITIDOS
            -----------------------------------------------------------------------------*/
            list($dbconn) = GetDBconn();
            $query = "SELECT *
                    FROM hc_conducta_remision_motivos
                    WHERE ingreso = ".$this->ingreso."
                    AND evolucion_id = ".$this->evolucion.";";

            $resulta=$dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            while(!$resulta->EOF)
            {
               $update=$resulta->GetRowAssoc($ToUpper = false);
               $resulta->MoveNext();
            }

            if (!empty ($update))
            {
                $query = "DELETE FROM hc_conducta_remision_motivos
                            WHERE evolucion_id=".$this->evolucion."
                            AND ingreso = ".$this->ingreso.";";

                $resulta=$dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
            }

            /*-----------------------------------------------------------------------------
            INSERTO LOS MOTIVOS POR LOS CUALES LOS PACIENTES SON REMITIDOS
            -----------------------------------------------------------------------------*/

            for($p=0;$p<sizeof($_REQUEST['vect'.$pfj]);$p++)
            {
                $motivo = $_REQUEST['sno'.$p];

                if(!empty($motivo))
                {
                    $query="INSERT INTO hc_conducta_remision_motivos
                                            (ingreso,
                                             evolucion_id,
                                             motivo_remision_id)
                                     VALUES (".$this->ingreso.",
                                             ".$this->evolucion.",
                                             $motivo);";
                    $result=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                    else
                    {
                        $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
                        $inserto = true;
                    }
                }
            }
            $_SESSION['INSERTO'] = '1';
            return true;
        }


        /**
        * Busca el centro de Remision Insertados
        * @author Tizziano Perea Ocoro <t_perea@hotmail.com>
        * @access public
        * @return array
        * @param string plan_id
        */
       function BuscarCentro()
       {
            if (!empty ($_SESSION['CODCENTRO']))
            {
                list($dbconn) = GetDBconn();
                $query = "SELECT *
                         FROM centros_remision
                         WHERE centro_remision = '".$_SESSION['CODCENTRO']."';";
                $resulta=$dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }

                while(!$resulta->EOF)
                {
                    $hospital=$resulta->GetRowAssoc($ToUpper = false);
                    $resulta->MoveNext();
                }
                unset ($_SESSION['CODCENTRO']);
                return $hospital;
            }
            else
            {
                return true;
            }
        }

        /**
        * Busca los Motivos de Remision Insertados
        * @author Tizziano Perea Ocoro <t_perea@hotmail.com>
        * @access public
        * @return array
        * @param string plan_id
        */
        function GetMotivos_Remision()
        {
            $pfj=$this->frmPrefijo;
            list($dbconn) = GetDBconn();
            $query = "SELECT A.descripcion, C.descripcion_otro_motivo
                      FROM hc_motivos_remision AS A, hc_conducta_remision_motivos AS B
                      LEFT JOIN hc_conducta_remision AS C
                      ON(B.ingreso = C.ingreso AND B.evolucion_id = C.evolucion_id)
                      WHERE A.motivo_remision_id = B.motivo_remision_id
                      AND B.ingreso = C.ingreso
                      AND B.evolucion_id = C.evolucion_id
                      AND B.ingreso = ".$this->ingreso."
                      AND B.evolucion_id = ".$this->evolucion.";";

            $resulta=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            else
            {
                $num_motivo =$resulta->GetRows();
                //$num_motivo = ceil (($num_motivo + 1)/4);
                return $num_motivo;
            }
        }

        /**
        * Busca los Motivos la conducta de Remision Insertada
        * @author Tizziano Perea Ocoro <t_perea@hotmail.com>
        * @access public
        * @return array
        * @param string plan_id
        */
        function GetConduta_Remision()
        {
            $pfj=$this->frmPrefijo;
            list($dbconn) = GetDBconn();
            $query = "SELECT A.observaciones, B.descripcion, A.traslado_ambulancia,
                             C.nombre, C.descripcion
                      FROM hc_conducta_remision AS A
                      LEFT JOIN hc_tipo_remision AS B ON(B.tipo_remision_id = A.tipo_remision)
                      LEFT JOIN system_usuarios AS C ON(A.usuario_id = C.usuario_id)
                      WHERE A.ingreso = ".$this->ingreso."
                      AND A.evolucion_id = ".$this->evolucion.";";

            $resulta=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            else
            {
                $conducta = $resulta->GetRows();
                return $conducta;
            }
        }
}
?>
