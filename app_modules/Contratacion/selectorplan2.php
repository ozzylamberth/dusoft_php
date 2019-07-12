<?php

/**
 * $Id: selectorplan2.php,v 1.1.1.1 2009/09/11 20:36:30 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: realizar la busqueda de los planes
 * y mostrar la infromación del contrato para guardar uno nuevo
 */

    ?>
    <head>
    <title>INFORMACIÓN DEL PLAN</title>
    <script languaje="javascript" src="selectortarifario2.js">
    </script>
    <style>
    .input-submit
    {
        color: #000000;
        font-size: 11px;
    }
    .input-bottom
    {
        color: #000000;
        font-weight: bold;
        font-size: 9px;
    }
    </style>
    <?php
    $_ROOT='../../';
    $VISTA='HTML';
    include $_ROOT.'includes/enviroment.inc.php';
    $fileName = $_ROOT."themes/$VISTA/".GetTheme()."/style/style.css";
    $fileName = "<link href=\"$fileName\" rel=\"stylesheet\" type=\"text/css\">\n";
    echo $fileName;
    ?>
    </head>

    <form name=contratacion1 method=GET action="selectorplan2.php">
    <table valign=bottom width=100%>
    <tr>
    <td align=center><br>
        <table valign=bottom width=100% border=0 class="modulo_table_list">
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>NÚMERO DE CONTRATO:</td>
        <td width=65%>
        <?
            $esta=-1;
            $swau=-1;
            $swaf=-1;
            $swfa=-1;
            $swim=-1;
            $swcd=-1;
            echo ("<input type=\"hidden\" name=\"empresacon\" value=".$_REQUEST['empresacon'].">");
            echo ("<input type=\"hidden\" name=\"tipoplacon\" value=".$_REQUEST['tipoplacon'].">");
            echo ("<input type=\"hidden\" name=\"estadocont\" value=".$_REQUEST['estadocont'].">");
            if(!($_REQUEST['empresacon']==-1))
            {
                $busqueda1="AND A.empresa_id='".$_REQUEST['empresacon']."'";
            }
            else
            {
                $busqueda1='';
            }
            if(!($_REQUEST['tipoplacon']==-1))
            {
                $busqueda2="AND A.sw_tipo_plan='".$_REQUEST['tipoplacon']."'";
            }
            else
            {
                $busqueda2='';
            }
            if($_REQUEST['estadocont']==1)
            {
                $busqueda3='';
            }
            else if($_REQUEST['estadocont']==2)
            {
                $busqueda3="AND A.estado='1'";
            }
            else if($_REQUEST['estadocont']==3)
            {
                $busqueda3="AND A.estado='0'";
            }
            list($dbconn) = GetDBconn();
//                       H.descripcion AS descripcion2,
//                        tipos_liq_habitacion AS H,
//                        AND A.tipo_liq_habitacion=H.tipo_liq_habitacion
            $consulta  = "SELECT A.plan_id,
                        A.num_contrato,
                        A.plan_descripcion,
                        B.razon_social,
                        C.nombre_tercero,
                        D.descripcion,
                        A.estado,
                        A.tipo_tercero_id,
                        A.tercero_id,
                        A.servicios_contratados,
                        A.contacto,
                        F.nombre,
                        A.protocolos,
                        G.descripcion AS descripcion1,
												A.sw_contrata_hospitalizacion,
                        I.descripcion AS descripcion3,
                        J.descripcion AS descripcion4,
                        K.descripcion AS descripcion5,
                        A.sw_autoriza_sin_bd,
                        A.sw_afiliacion,
                        A.sw_facturacion_agrupada,
                        A.sw_paragrafados_imd,
                        A.sw_paragrafados_cd,
                        A.observacion,
                        A.nombre_copago,
                        A.nombre_cuota_moderadora,
                        A.lineas_atencion,
                        A.sw_base_liquidacion_imd,
                        A.actividad_incumplimientos,
                        A.sw_exceder_monto_mensual,
                        A.fecha_inicio,
                        A.fecha_final,
                        A.meses_consulta_base_datos,
                        A.horas_cancelacion,
                        A.telefono_cancelacion_cita,
                        A.tipo_para_imd
                        FROM planes AS A
                        LEFT JOIN tipos_cliente AS G ON
                        (A.tipo_cliente=G.tipo_cliente),
                        empresas AS B,
                        terceros AS C,
                        tipos_planes AS D,
                        planes_encargados AS E,
                        system_usuarios AS F,
                        tipos_liquidacion_semanas_cotizadas AS I,
                        tipo_liquidaciones_cargos AS J,
                        tipos_paragrafados_imd AS K
                        WHERE A.empresa_id=B.empresa_id
                        AND A.tipo_tercero_id=C.tipo_id_tercero
                        AND A.tercero_id=C.tercero_id
                        AND A.sw_tipo_plan=D.sw_tipo_plan
                        AND A.plan_id=E.plan_id
                        AND E.usuario_id=F.usuario_id
                        AND A.tipo_liquidacion_id=I.tipo_liquidacion_id
                        AND A.tipo_liquidacion_cargo=J.tipo_liquidacion_cargo
                        AND A.tipo_para_imd=K.tipo_para_imd
                        $busqueda1
                        $busqueda2
                        $busqueda3
                        ORDER BY num_contrato";
            $resultado=$dbconn->Execute($consulta);
        ?>
        <select name=tarifario1 onChange="cambio(this.form)" class="select">
        <option value=-1>--  SELECCIONE  --</option>"
        <?php
            while(!$resultado->EOF)
            {
                if($resultado->fields[1]==$_REQUEST['tarifario1'])
                {
                    echo "<option value=\"".$resultado->fields[1]."\" selected>".$resultado->fields[1]."".' -- '."".$resultado->fields[2]."</option>";
                    $plan=$resultado->fields[0];
                    $desc=$resultado->fields[2];
                    $empr=$resultado->fields[3];
                    $clie=$resultado->fields[4];
                    $tipo=$resultado->fields[5];
                    $esta=$resultado->fields[6];
                    $tite=$resultado->fields[7];
                    $terc=$resultado->fields[8];
                    $serv=$resultado->fields[9];
                    $cont=$resultado->fields[10];
                    $nomb=$resultado->fields[11];
                    $prot=$resultado->fields[12];
                    $des1=$resultado->fields[13];
										//CAMBIO HABITACIONES
                    $des2=$resultado->fields[14];
										//FIN
                    $des3=$resultado->fields[15];
                    $des4=$resultado->fields[16];
                    $des5=$resultado->fields[17];
                    $swau=$resultado->fields[18];
                    $swaf=$resultado->fields[19];
                    $swfa=$resultado->fields[20];
                    $swim=$resultado->fields[21];
                    $swcd=$resultado->fields[22];
                    $obse=$resultado->fields[23];
                    $noco=$resultado->fields[24];
                    $nocm=$resultado->fields[25];
                    $line=$resultado->fields[26];
                    $base=$resultado->fields[27];
                    $acin=$resultado->fields[28];
                    $exce=$resultado->fields[29];
                    $fini=$resultado->fields[30];
                    $ffin=$resultado->fields[31];
                    $mcbd=$resultado->fields[32];
                    $hoca=$resultado->fields[33];
                    $lica=$resultado->fields[34];
                    $tpim=$resultado->fields[35];
                }
                else
                {
                    echo "<option value=\"".$resultado->fields[1]."\">".$resultado->fields[1]."".' -- '."".$resultado->fields[2]."</option>";
                }
                $resultado->MoveNext();
            }
        ?>
        </select>
        <?php
        $fecha=explode('-',$fini);
        $fini=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];
        $fecha=explode('-',$ffin);
        $ffin=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];
        echo ("<input type=\"hidden\" name=\"plan\" value=".$plan.">");
        echo ("<input type=\"hidden\" name=\"descr2ctra\" value='".$desc."'>");
        echo ("<input type=\"hidden\" name=\"contactoctra\" value='".$cont."'>");
        echo ("<input type=\"hidden\" name=\"feinictra\" value=".$fini.">");
        echo ("<input type=\"hidden\" name=\"fefinctra\" value=".$ffin.">");
        echo ("<input type=\"hidden\" name=\"paragramed\" value=".$swim.">");
        echo ("<input type=\"hidden\" name=\"paragracar\" value=".$swcd.">");
        echo ("<input type=\"hidden\" name=\"tipoTerceroId\" value=".$tite.">");
        echo ("<input type=\"hidden\" name=\"codigo\" value=".$terc.">");
        echo ("<input type=\"hidden\" name=\"nombre\" value=".$clie.">");
        echo ("<input type=\"hidden\" name=\"tipoparimd\" value=".$tpim.">");
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>DESCRIPCIÓN DEL CONTRATO:</td>
        <td width=65% align=left>
        <?
        echo $desc;
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>ESTADO:</td>
        <td width=65% align=left>
        <?
        if($esta==1)
        {
        echo "ACTIVO";
        }
        else if($esta==0)
        {
        echo "INACTIVO";
        }
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>EMPRESA:</td>
        <td width=65% align=left>
        <?
        echo $empr;
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>TIPO PLAN:</td>
        <td width=65% align=left>
        <?
        echo $tipo;
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>CLIENTE:</td>
        <td width=65% align=left>
        <?
        echo $clie;
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>TIPO:</td>
        <td width=65% align=left>
        <?
        echo $tite;
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>CÓDIGO:</td>
        <td width=65% align=left>
        <?
        echo $terc;
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>SERVICIOS CONTRATADOS:</td>
        <td width=65% align=left>
        <?
        echo $serv;
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>CONTACTO:</td>
        <td width=65% align=left>
        <?
        echo $cont;
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>ENCARGADO:</td>
        <td width=65% align=left>
        <?
        echo $nomb;
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>URL PROTÓCOLOS:</td>
        <td width=65% align=left>
        <?
        echo $prot;
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>FECHA INICIAL:</td>
        <td width=65% align=left>
        <?
        echo $fini;
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>FECHA FINAL:</td>
        <td width=65% align=left>
        <?
        echo $ffin;
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>EXCEDER EL VALOR MENSUAL DEL CONTRATO:</td>
        <td width=65% align=left>
        <?
        if($exce==1)
        {
        echo "SI";
        }
        else if($exce==0)
        {
        echo "NO";
        }
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>TIPO CLIENTE:</td>
        <td width=65% align=left>
        <?
        echo $des1;
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>LIQUIDACIÓN DE LA HABITACIÓN:</td>
        <td width=65% align=left>
        <?
				//CAMBIO HABITACIONES
        if($des2==1)
        {
        echo "SI";
        }
        else if($des2==0)
        {
        echo "NO";
        }
				//FIN CAMBIO HABITACIONES
//        echo $des2;
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>LIQUIDACIÓN DE SEMANAS PARA DÍAS DE CARENCIA:</td>
        <td width=65% align=left>
        <?
        echo $des3;
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>LIQUIDACIÓN DE CARGOS:</td>
        <td width=65% align=left>
        <?
        echo $des4;
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>TIPO PARAGRAFADOS DE INSUMOS Y MEDICAMENTOS:</td>
        <td width=65% align=left>
        <?
        echo $des5;
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>PERMITIR AUTORIZACIÓN SIN BASE DE DATOS:</td>
        <td width=65% align=left>
        <?
        if($swau==1)
        {
        echo "SI";
        }
        else if($swau==0)
        {
        echo "NO";
        }
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>MANEJO DE BASE DE DATOS DE AFILIADOS:</td>
        <td width=65% align=left>
        <?
        if($swaf==1)
        {
        echo "SI";
        }
        else if($swaf==0)
        {
        echo "NO";
        }
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>FACTURACIÓN AGRUPADA:</td>
        <td width=65% align=left>
        <?
        if($swfa==1)
        {
        echo "SI";
        }
        else if($swfa==0)
        {
        echo "NO";
        }
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>PARAGRAFADOS INSUMOS Y MEDICAMENTOS:</td>
        <td width=65% align=left>
        <?
        if($swim==1)
        {
        echo "SI";
        }
        else if($swim==0)
        {
        echo "NO";
        }
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>PARAGRAFADOS CARGOS DIRECTOS:</td>
        <td width=65% align=left>
        <?
        if($swcd==1)
        {
        echo "SI";
        }
        else if($swcd==0)
        {
        echo "NO";
        }
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>NOMBRE DEL COPAGO:</td>
        <td width=65% align=left>
        <?
        echo $noco;
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>NOMBRE DE LA CUOTA MODERADORA:</td>
        <td width=65% align=left>
        <?
        echo $nocm;
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>MESES PARA CONSULTAR EN LA BD:</td>
        <td width=65% align=left>
        <?
        echo $mcbd;
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>MOSTRAR LOS ÚLTIMOS DÍAS DE INCUMPLIMIENTO:</td>
        <td width=65% align=left>
        <?
        echo $acin;
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>HORAS PREVIAS PARA CANCELAR CITA:</td>
        <td width=65% align=left>
        <?
        echo $hoca;
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>LÍNEAS PARA CANCELACIÓN DE CITA:</td>
        <td width=65% align=left>
        <?
        echo $lica;
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>LÍNEAS DE ATENCIÓN -- AUTORIZACIONES:</td>
        <td width=65% align=left>
        <?
        echo $line;
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>OBSERVACIÓN:</td>
        <td width=65% align=left>
        <?
        echo $obse;
        ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>SERVICIOS ASISTENCIALES CONTRATADOS:</td>
        <td width=65% align=left>
                <?
                if($plan<>NULL)
                {
                    $query = "SELECT A.servicio,
                            B.descripcion
                            FROM planes_servicios AS A,
                            servicios AS B
                            WHERE A.plan_id=".$plan."
                            AND A.servicio=B.servicio
                            ORDER BY A.servicio;";
                    $resulta = $dbconn->Execute($query);
                    ?>
                    <table class="modulo_list_claro" width=100% border=0>
                    <?
                        while(!$resulta->EOF)
                        {
                            ?>
                            <tr>
                            <td>
                            <?
                            echo "".$resulta->fields[1]."";
                            ?>
                            </td>
                            </tr>
                            <?
                            $resulta->MoveNext();
                        }
                    ?>
                    </table>
                <?
                }
                ?>
        </td>
        </tr>
        <tr class="modulo_list_claro">
        <td class="modulo_table_list_title" width=35%>BASE PARA LA LIQUIDACIÓN DE INSUMOS Y MEDICAMENTOS:</td>
        <td width=65% align=left>
        <?
        if($base==1)
        {
            echo "COSTO PROMEDIO";
        }
        else if($base==2)
        {
            echo "LISTA DE VENTA";
        }
        else if($base==3)
        {
            echo "LISTA DE VENTA";
        }
        ?>
        </td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td align=center><br>
        <table valign=bottom width=30% border=0>
        <tr>
        <td align=center>
        <input type=submit name=Aceptar class="input-bottom" value="ACEPTAR" onClick="copiarValor(this.form)">
        </td>
        </tr>
        </table>
    </td>
    </tr>
    </table>
    </form>
