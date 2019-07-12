<?php
/**
 * $Id: SIIS_Perfiles.class.php,v 1.1 2006/11/29 21:59:17 alex Exp $
 */

/**
 * Clase para la consulta de permisos para los modulos
 *
 * @author Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
 * @version $Revision: 1.1 $
 * @package SIIS
 */

 class SIIS_Perfiles
 {

    function SIIS_Perfiles()
    {
        return true;
    }

    function SetVectorUser($usuario_id)
    {
        list($dbconn) = GetDBconn();
        GLOBAL $ADODB_FETCH_MODE;
        $sql = "
                (
                    SELECT
                        b.modulo,
                        b.modulo_tipo,
                        b.grupo_id,
                        b.componente_id

                    FROM
                        system_modulos_permisos AS a,
                        system_modulos_permisos_perfiles_componentes AS b
                        LEFT JOIN system_modulos_permisos_excepciones AS c ON
                            (
                                c.modulo = b.modulo
                                AND c.modulo_tipo = b.modulo_tipo
                                AND c.grupo_id = b.grupo_id
                                AND c.componente_id = b.componente_id
                                AND c.usuario_id = $usuario_id
                                AND c.sw_permiso = 'I'
                            )
                    WHERE
                        a.usuario_id = $usuario_id
                        AND b.modulo = a.modulo
                        AND b.modulo_tipo = a.modulo_tipo
                        AND b.perfil_id = a.perfil_id
                        AND c.sw_permiso IS NULL
                )
                UNION
                (
                    SELECT
                        b.modulo,
                        b.modulo_tipo,
                        b.grupo_id,
                        b.componente_id

                    FROM  system_modulos_permisos_excepciones AS b
                    WHERE   b.usuario_id = $usuario_id AND b.sw_permiso = 'A'

                )
        ";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if ($dbconn->ErrorNo() != 0)
        {
            die(MsgOut("Error SQL en CLASS SIIS_Perfiles",$dbconn->ErrorMsg()));
        }

        if(!$result->EOF)
        {
            while($fila = $result->FetchRow())
            {
                $SIIS_Perfiles[$usuario_id][$fila['modulo']][$fila['modulo_tipo']][$fila['grupo_id']][$fila['componente_id']]=true;
            }
        }
        $result->Close();
    }

    function GetPermiso($modulo,$modulo_tipo,$grupo_id,$componente_id,$usuario_id=null)
    {
        if(empty($modulo)||empty($modulo_tipo)||empty($grupo_id)||empty($componente_id))
        {
           // return false;
        }
        if(empty($usuario_id))
        {
            $usuario_id = UserGetUID();
        }

        global $SIIS_Perfiles;

        if(empty($SIIS_Perfiles))
        {

        }


        //ECHO "<PRE>SIIS_Perfiles :<BR>".PRINT_R($SIIS_Perfiles,TRUE)."</PRE><BR>";


        if($SIIS_Perfiles[$usuario_id][$modulo][$modulo_tipo][$grupo_id][$componente_id])
        {
            return true;
        }
        else
        {
            return false;
        }

    }//fin del metodo

 }
 ?>