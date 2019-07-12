<?php
  /**
  * Archivo Ajax (ConsultaxAfiliados)
  * Archivo que contiene funciones las cuales permiten conectarse con la BD por medio de xajax lo que permite no recargar la pagina para obtener una consulta
  *
  * @version $Id: ConsultaxAfiliados.php,v 1.8 2007/11/09 13:56:03 jgomez Exp $   
  * @package IPSOFT-SIIS
  * @author Jaime Gomez  
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  
  */
    
    /**
    *   funcion que sirve para la asignacion de medicos a los diferentes grupos familiares
    *   @param string $datos
    *   @param string $pagina
    *   @param string $contador
    *   @return array $salida vector con todos datos de los beneficiarios encontrados en la busqueda
    **/
    function AsignarMedico_Grupo($eps_afiliacion_id,$afiliado_tipo_id,$afiliado_id,$td,$tipo_id_tercero,$tercero_id,$usuario_profesional,$nombre_medico)
    {
            $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();

        $cot = AutoCarga::factory("ConsultarAfiliados", "", "app","UV_MedicinaFamiliar");
        $medicos = $cot->ObtenerMedicos();
        $registrador=UserGetUID();
        //var_dump($medicos);

        $resultado_b=$cot->BuscarMedicosAntesdeRegistrar($eps_afiliacion_id,$afiliado_tipo_id,$afiliado_id);


        if($resultado_b===true)
        {
           $resultado=$cot->AsignarMedicoBD($eps_afiliacion_id,$afiliado_tipo_id,$afiliado_id,$tipo_id_tercero,$tercero_id,$usuario_profesional,$registrador);
        }
        
        //var_dump($resultado);
        if($resultado===true)
        {
            $cad="MEDICAMENTO REGISTRADO SATISFACTORIAMENTE";
            $objResponse->assign("error","innerHTML",$cad);
            $objResponse->call("VentanaClose");

            $salida.= "       <a title='".$afiliado_tipo_id." - ".$afiliado_id."' href=\"#\">";
            $salida.= "           <sub>".$nombre_medico."</sub>\n";
            $salida.= "      </a>";
            $objResponse->assign($td,"innerHTML",$salida);
        }
        else
        {
            $cad=$consultar->Error['MensajeError'];
            $objResponse->assign("errorMed","innerHTML",$cad.$resultado);
        }
        return $objResponse;

    }
    

    /**
    *   funcion que sirve para listar los grupos familiares de un respectivo medico
    *   @param string $medico_tipo_id
    *   @param string $medico_id
    *   @return array $salida vector con todos datos de los grupos familiares correspondientes al medico 
    **/

    function ListarGruposFamiliares($medico_tipo_id,$medico_id,$nombre)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();

        $cot = AutoCarga::factory("ConsultarAfiliados", "", "app","UV_MedicinaFamiliar");
        $grupos_familiares = $cot->ObternerListadeCotizantesPorMedico($medico_tipo_id,$medico_id);
        
        if(!empty($grupos_familiares))
        {
            
            $salida .= "                 <table class=\"modulo_table_list\" width=\"90%\" align=\"center\">\n";
            $salida .= "                   <tr class=\"modulo_table_list_title\" >\n";
            $salida .= "                     <td width='15%' align=\"center\">\n";
            $salida .= "                       AFILIACION";
            $salida .= "                     </td>\n";
            $salida .= "                     <td width='15%' align=\"center\">\n";
            $salida .= "                       IDENTIFICACION";
            $salida .= "                     </td>\n";
            $salida .= "                     <td width='70%' align=\"center\">\n";
            $salida .= "                       NOMBRE ";
            $salida .= "                     </td>\n";
            $salida .= "                     <td width='15%' align=\"center\">\n";
            $salida .= "                       <a title='VER GRUPO FAMILIAR'>";
            $salida .= "                       VER GRUPO";
            $salida .= "                       </a>";
            $salida .= "                     </td>\n";
            $salida .= "                   </tr>\n";
            for($i=0;$i<count($grupos_familiares);$i++)
            {
                $salida .= "                   <tr class=\"modulo_list_claro\" >\n";
                $salida .= "                     <td  align=\"left\">\n";
                $salida .= "                        ".$grupos_familiares[$i]['eps_afiliacion_id']."";
                $salida .= "                     </td>\n";
                $salida .= "                     <td  align=\"left\">\n";
                $salida .= "                        ".$grupos_familiares[$i]['tipo_id_cotizante']." - ".$grupos_familiares[$i]['cotizante_id'];
                $salida .= "                     </td>\n";
                $salida .= "                     <td  align=\"left\">\n";
                $salida .= "                       ".$grupos_familiares[$i]['nombre_afiliado']."";
                $salida .= "                     </td>\n";
                $salida .= "                     <td width='3%' align=\"center\" id='BotonCotizante'>\n";
                $beneficiario = "javascript:MostrarCapa('ContenedorGrup');Bus_Ben1('".$grupos_familiares[$i]['eps_afiliacion_id']."','".$grupos_familiares[$i]['tipo_id_cotizante']."','".$grupos_familiares[$i]['cotizante_id']."','".$medico_tipo_id."','".$medico_id."','".$nombre."');Iniciar2('GRUPO FAMILIAR');\"";
                $salida .= "                       <a title='VER GRUPO FAMILIAR' href=\"".$beneficiario."\">";
                $salida .= "                          <sub><img src=\"".$path."/images/familia1.jpeg\" border=\"0\" width=\"20\" height=\"20\"></sub>\n";
                $salida .= "                       </a>";
                $salida .= "                     </td>\n";
                $salida .= "                   </tr>\n";

            }
            $salida .= "                   </table>\n";
        }
        else
        {
            $salida .= "                 <table  width=\"100%\" align=\"center\" cellspacing='0' >\n";
            $salida .= "                   <tr>\n";
            $salida .= "                     <td  align=\"center\">\n";
            $salida .= "                       <label class='label_error'>ESTE MEDICO NO TIENE GRUPOS FAMILIARES ASIGNADOS</label>";
            $salida .= "                     </td>\n";
            $salida .= "                   </tr>\n";
            $salida .= "                 </table>\n";
        }

        $objResponse->assign("ContenidoGrup","innerHTML",utf8_encode($salida));
        return $objResponse;

    }


    /**
    *   funcion que sirve para la asignacion de medicos a los diferentes grupos familiares
    *   @param string $datos
    *   @param string $pagina
    *   @param string $contador
    *   @return array $salida vector con todos datos de los beneficiarios encontrados en la busqueda
    **/

    function ObtenerMedicos($eps_afiliacion_id,$afiliado_tipo_id,$afiliado_id,$td)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();

        $cot = AutoCarga::factory("ConsultarAfiliados", "", "app","UV_MedicinaFamiliar");
        $medicos = $cot->ObtenerMedicos();
        //var_dump($medicos);
        if(!empty($medicos))
        {
            $salida .= "                 <table class=\"modulo_table_list\" width=\"90%\" align=\"center\">\n";
            $salida .= "                   <tr class=\"modulo_table_list_title\" >\n";
            $salida .= "                     <td width='15%' align=\"center\">\n";
            $salida .= "                       IDENTIFICACION";
            $salida .= "                     </td>\n";
            $salida .= "                     <td width='70%' align=\"center\">\n";
            $salida .= "                       NOMBRE ";
            $salida .= "                     </td>\n";
            $salida .= "                     <td width='15%' align=\"center\">\n";
            $salida .= "                       ASIGNAR MEDICO";
            $salida .= "                     </td>\n";
            $salida .= "                   </tr>\n";
            for($i=0;$i<count($medicos);$i++)
            {
                $salida .= "                   <tr class=\"modulo_list_claro\" >\n";
                $salida .= "                     <td  align=\"left\">\n";
                $salida .= "                        ".$medicos[$i]['tipo_id_tercero']." - ".$medicos[$i]['tercero_id'];
                $salida .= "                     </td>\n";
                $salida .= "                     <td  align=\"left\">\n";
                $salida .= "                       ".$medicos[$i]['nombre']."";
                $salida .= "                     </td>\n";
                $salida .= "                     <td width='3%' align=\"center\" id='BotonCotizante'>\n";
                $salida .= "                       <a title='ASIGNAR MEDICO' href=\"javascript:SeleccionarMed('".$eps_afiliacion_id."','".$afiliado_tipo_id."','".$afiliado_id."','".$td."','".$medicos[$i]['tipo_id_tercero']."','".$medicos[$i]['tercero_id']."','".$medicos[$i]['usuario_profesional']."','".$medicos[$i]['nombre']."');\">";
                $salida .= "                          <sub><img src=\"".$path."/images/pparacarin.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $salida .= "                       </a>";
                $salida .= "                     </td>\n";
                $salida .= "                   </tr>\n";

            }
            $salida .= "                   </table>\n";
        }
        else
        {
            $salida .= "                 <table  width=\"100%\" align=\"center\" cellspacing='0' >\n";
            $salida .= "                   <tr class=\"modulo_table_list_title\" >\n";
            $salida .= "                     <td  align=\"center\">\n";
            $salida .= "                       <label class='label_error'>NO SE ENCONTRARON RESULTADOS</label>";
            $salida .= "                     </td>\n";
            $salida .= "                   </tr>\n";
            $salida .= "                 </table>\n";
        }

        $objResponse->assign("ContenidoMed","innerHTML",utf8_encode($salida));
        return $objResponse;

    }


    /**
    *   Funcion que sirve para obtener los beneficiarios de un cotizante
    *   @param string $datos
    *   @param string $pagina
    *   @param string $contador
    *   @return array $salida vector con todos datos de los beneficiarios encontrados en la busqueda
    **/
    function  BuscarBeneficiarios1($eps_afiliacion_id,$afiliado_tipo_id,$afiliado_id,$medico_tipo_id,$medico_id,$medicos_nombre)
    {

        $datos['afiliado_tipo_id']=$afiliado_tipo_id;
        $datos['afiliado_id']=$afiliado_id;
    
       
        $usuario=UserGetUID();
            
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();

        
        $cot = AutoCarga::factory("ConsultarAfiliados", "", "app","UV_MedicinaFamiliar");
        $afi = AutoCarga::factory("ConsultarBeneficiarios", "", "app","UV_MedicinaFamiliar");
        $cotizante = $cot->GetDatosAfiliado($eps_afiliacion_id,$afiliado_tipo_id,$afiliado_id);
        $afiliados = $afi->ObtenerBeneficiariosCotizante($datos);

         if(!empty($cotizante))
         {

                    $salida .= "               <div align='center' id=\"Cotizante\" style=\"width:100%; height:20px; z-index:1; border: 1px none #000000; overflow:hidden; scrollbars=no;\"onClick='' >";
                    $salida .= "                 <table class=\"modulo_table_list\" width=\"90%\" align=\"center\" cellspacing='0' >\n";
                    $salida .= "                   <tr class=\"modulo_table_list_title\" >\n";
                    $salida .= "                     <td width='97%' align=\"center\">\n";
                    $salida .= "                       <a title='NOMBRE AFILIADO'>";
                    $salida .= "                        ".$cotizante['nombre_afiliado']."  -  (COTIZANTE)";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width='3%' align=\"center\" id='BotonCotizante'>\n";
                    $salida .= "                       <a title='DESPLEGAR INFORMACION' href=\"javascript:biger('Cotizante','0','".$path."','BotonCotizante');\">";
                    $salida .= "                          <sub><img src=\"".$path."/images/abajo.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   </table>\n";
                    $salida .= "                 <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td width=\"30%\" class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        AFILIACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"10%\" align=\"LEFT\">\n";
                    $salida .= "                       ".$cotizante['eps_afiliacion_id']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"30%\" class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        IDENTIFICACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"30%\"align=\"LEFT\">\n";
                    $salida .= "                       <a  title='TIPO AFILIADO'>";
                    $salida .= "                         ".$cotizante['afiliado_tipo_id']."-".$cotizante['afiliado_id']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td  class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        SEXO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"LEFT\">\n";
                    $salida .= "                       ".$cotizante['descripcion_eps_tipo_sexo_id']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        ESTAMENTO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"LEFT\">\n";
                    $salida .= "                       <a  title='TIPO AFILIADO'>";
                    $salida .= "                         ".$cotizante["DATOS_COTIZANTE"]['descripcion_estamento']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        FECHA AFILIACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"center\">\n";
                    $salida .= "                       ".$cotizante['fecha_afiliacion']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        FECHA NACIMIENTO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"center\">\n";
                    $salida .= "                       <a title='TIPO AFILIADO'>";
                    $salida .= "                         ".$cotizante['fecha_nacimiento']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        ESTADO CIVIL";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"LEFT\">\n";
                    $salida .= "                       ".$cotizante["DATOS_COTIZANTE"]['descripcion_estado_civil']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        ESTRATO SOCIOECONOMICO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"LEFT\">\n";
                    $salida .= "                       <a title='TIPO AFILIADO'>";
                    $salida .= "                         ".$cotizante["DATOS_COTIZANTE"]['descripcion_estrato_socioeconomico']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        ZONA RESIDENCIAL";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"LEFT\">\n";
                    $salida .= "                       ".$cotizante['descripcion_zona_residencia']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        DIRECCION RESIDENCIA";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"LEFT\">\n";
                    $salida .= "                       <a title='TIPO AFILIADO'>";
                    $salida .= "                         ".$cotizante['direccion_residencia']." (".$cotizante['municipio']."-".$cotizante['departamento'].")";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        TELEFONO RESIDENCIA";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"LEFT\">\n";
                    $salida .= "                       ".$cotizante['telefono_residencia']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        TELEFONO MOVIL";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"LEFT\">\n";
                    $salida .= "                       <a title='TIPO AFILIADO'>";
                    $salida .= "                         ".$cotizante['telefono_movil']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                 </table>\n";
                    $salida .= "               </div>\n";
         }
              $i=0;  
        if(!empty($afiliados))
        {           

        //var_dump($afiliados);

            foreach($afiliados as $key=>$valor)
            {   
                foreach($valor as $key=>$valor1)
                {
                    $td="BotonBenef".$i;
                    $salida .= "               <div align='center' id=\"Benef".$i."\" style=\"width:100%; height:20px; z-index:1; border: 1px none #000000; overflow:hidden; scrollbars=no;\"onClick=''>";
                    $salida .= "                 <table class=\"modulo_table_list\" width=\"90%\" align=\"center\" cellspacing='0' >\n";
                    $salida .= "                   <tr class=\"modulo_table_list_title\" >\n";
                    $salida .= "                     <td width='97%' align=\"center\">\n";
                    $salida .= "                       <a title='NOMBRE AFILIADO'>";
                    $salida .= "                        ".$valor1['primer_nombre']." ".$valor1['segundo_nombre']." ".$valor1['primer_apellido']." ".$valor1['segundo_apellido']." -  (BENEFICIARIO)";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width='3%' align=\"center\" id='".$td."'>\n";
                    $salida .= "                       <a title='DESPLEGAR INFORMACION' href=\"javascript:biger('Benef".$i."','0','".$path."','".$td."');\">";
                    $salida .= "                          <sub><img src=\"".$path."/images/abajo.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   </table>\n";
                    $salida .= "                 <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td width=\"30%\" class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        AFILIACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"10%\" align=\"LEFT\">\n";
                    $salida .= "                       ".$valor1['eps_afiliacion_id']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"30%\" class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        IDENTIFICACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"30%\"align=\"LEFT\">\n";
                    $salida .= "                       <a  title='TIPO AFILIADO'>";
                    $salida .= "                         ".$valor1['afiliado_tipo_id']."-".$valor1['afiliado_id']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td  class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        SEXO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"LEFT\">\n";
                    if($valor1['tipo_sexo_id']=='M')
                    {
                        $salida .= "              MASCULINO       ";
                    }
                    else
                    {
                        $salida .= "              FEMENINO";
                    }
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        FECHA AFILIACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"center\">\n";
                    $salida .= "                       ".$valor1['fecha_afiliacion_sgss']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        PARENTESCO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"left\">\n";
                    $salida .= "                       ".$valor1['descripcion_parentesco']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        FECHA NACIMIENTO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"center\">\n";
                    $salida .= "                       <a title='TIPO AFILIADO'>";
                    $salida .= "                         ".$valor1['fecha_nacimiento']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        ZONA RESIDENCIAL";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"LEFT\">\n";
                    IF($valor1['zona_residencia']=='U')
                    {
                        $salida .= "                       URBANA";
                    }
                    else
                    {
                        $salida .= "                       RURAL";
                    }
                    
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        DIRECCION RESIDENCIA";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"LEFT\">\n";
                    $salida .= "                       <a title='DIRECCION RESIDENCIA'>";
                    $salida .= "                         ".$valor1['direccion_residencia']."-( ".$valor1['departamento_municipio'].")";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        TELEFONO RESIDENCIA";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"LEFT\">\n";
                    $salida .= "                       ".$valor1['telefono_residencia']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        TELEFONO MOVIL";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"LEFT\">\n";
                    $salida .= "                       <a title='TIPO AFILIADO'>";
                    $salida .= "                         ".$valor1['telefono_movil']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                 </table>\n";
                    $salida .= "                   </div>\n";
                    $i++;
                }
            }
        }
        $salida .= "                 <table WIDTH='100%'>\n";
        $salida .= "                   <tr>\n";
        $salida .= "                      <td align=\"CENTER\">\n";
        $beneficiario = "javascript:MostrarCapa('ContenedorGrup');ListarGrupos('".$medico_tipo_id."','".$medico_id."','".$medicos_nombre."');Iniciar2('GRUPOS FAMILIARES DEL MEDICO ".$medicos_nombre."');\"";
        $salida .="                         <a title='VOLVER A GRUPOS FAMILIARES' class='label_error' href=\"".$beneficiario."\">";
        $salida .="                          <label >VOLVER A GRUPOS FAMILIARES</label>\n";//usuarios.png
        $salida .="                         </a>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                 </table>\n";

        
        $objResponse->assign("ContenidoGrup","innerHTML",utf8_encode($salida));
        return $objResponse;
    }

    
    /**
    *   Funcion que sirve para obtener los beneficiarios de un cotizante
    *   @param string $datos
    *   @param string $pagina
    *   @param string $contador
    *   @return array $salida vector con todos datos de los beneficiarios encontrados en la busqueda
    **/
    function  BuscarBeneficiarios($eps_afiliacion_id,$afiliado_tipo_id,$afiliado_id)
    {

        $datos['afiliado_tipo_id']=$afiliado_tipo_id;
        $datos['afiliado_id']=$afiliado_id;
    
       
        $usuario=UserGetUID();
            
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();

        
        $cot = AutoCarga::factory("ConsultarAfiliados", "", "app","UV_MedicinaFamiliar");
        $afi = AutoCarga::factory("ConsultarBeneficiarios", "", "app","UV_MedicinaFamiliar");
        $cotizante = $cot->GetDatosAfiliado($eps_afiliacion_id,$afiliado_tipo_id,$afiliado_id);
        $afiliados = $afi->ObtenerBeneficiariosCotizante($datos);

         if(!empty($cotizante))
         {

                    $salida .= "               <div align='center' id=\"Cotizante\" style=\"width:100%; height:20px; z-index:1; border: 1px none #000000; overflow:hidden; scrollbars=no;\"onClick='' >";
                    $salida .= "                 <table class=\"modulo_table_list\" width=\"90%\" align=\"center\" cellspacing='0' >\n";
                    $salida .= "                   <tr class=\"modulo_table_list_title\" >\n";
                    $salida .= "                     <td width='97%' align=\"center\">\n";
                    $salida .= "                       <a title='NOMBRE AFILIADO'>";
                    $salida .= "                        ".$cotizante['nombre_afiliado']."  -  (COTIZANTE)";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width='3%' align=\"center\" id='BotonCotizante'>\n";
                    $salida .= "                       <a title='DESPLEGAR INFORMACION' href=\"javascript:biger('Cotizante','0','".$path."','BotonCotizante');\">";
                    $salida .= "                          <sub><img src=\"".$path."/images/abajo.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   </table>\n";
                    $salida .= "                 <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td width=\"30%\" class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        AFILIACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"10%\" align=\"LEFT\">\n";
                    $salida .= "                       ".$cotizante['eps_afiliacion_id']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"30%\" class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        IDENTIFICACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"30%\"align=\"LEFT\">\n";
                    $salida .= "                       <a  title='TIPO AFILIADO'>";
                    $salida .= "                         ".$cotizante['afiliado_tipo_id']."-".$cotizante['afiliado_id']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td  class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        SEXO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"LEFT\">\n";
                    $salida .= "                       ".$cotizante['descripcion_eps_tipo_sexo_id']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        ESTAMENTO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"LEFT\">\n";
                    $salida .= "                       <a  title='TIPO AFILIADO'>";
                    $salida .= "                         ".$cotizante["DATOS_COTIZANTE"]['descripcion_estamento']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        FECHA AFILIACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"center\">\n";
                    $salida .= "                       ".$cotizante['fecha_afiliacion']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        FECHA NACIMIENTO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"center\">\n";
                    $salida .= "                       <a title='TIPO AFILIADO'>";
                    $salida .= "                         ".$cotizante['fecha_nacimiento']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        ESTADO CIVIL";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"LEFT\">\n";
                    $salida .= "                       ".$cotizante["DATOS_COTIZANTE"]['descripcion_estado_civil']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        ESTRATO SOCIOECONOMICO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"LEFT\">\n";
                    $salida .= "                       <a title='TIPO AFILIADO'>";
                    $salida .= "                         ".$cotizante["DATOS_COTIZANTE"]['descripcion_estrato_socioeconomico']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        ZONA RESIDENCIAL";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"LEFT\">\n";
                    $salida .= "                       ".$cotizante['descripcion_zona_residencia']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        DIRECCION RESIDENCIA";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"LEFT\">\n";
                    $salida .= "                       <a title='TIPO AFILIADO'>";
                    $salida .= "                         ".$cotizante['direccion_residencia']." (".$cotizante['municipio']."-".$cotizante['departamento'].")";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        TELEFONO RESIDENCIA";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"LEFT\">\n";
                    $salida .= "                       ".$cotizante['telefono_residencia']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        TELEFONO MOVIL";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"LEFT\">\n";
                    $salida .= "                       <a title='TIPO AFILIADO'>";
                    $salida .= "                         ".$cotizante['telefono_movil']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                 </table>\n";
                    $salida .= "               </div>\n";
         }
              $i=0;  
        if(!empty($afiliados))
        {           

        //var_dump($afiliados);

            foreach($afiliados as $key=>$valor)
            {   
                foreach($valor as $key=>$valor1)
                {
                    $td="BotonBenef".$i;
                    $salida .= "               <div align='center' id=\"Benef".$i."\" style=\"width:100%; height:20px; z-index:1; border: 1px none #000000; overflow:hidden; scrollbars=no;\"onClick=''>";
                    $salida .= "                 <table class=\"modulo_table_list\" width=\"90%\" align=\"center\" cellspacing='0' >\n";
                    $salida .= "                   <tr class=\"modulo_table_list_title\" >\n";
                    $salida .= "                     <td width='97%' align=\"center\">\n";
                    $salida .= "                       <a title='NOMBRE AFILIADO'>";
                    $salida .= "                        ".$valor1['primer_nombre']." ".$valor1['segundo_nombre']." ".$valor1['primer_apellido']." ".$valor1['segundo_apellido']." -  (BENEFICIARIO)";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width='3%' align=\"center\" id='".$td."'>\n";
                    $salida .= "                       <a title='DESPLEGAR INFORMACION' href=\"javascript:biger('Benef".$i."','0','".$path."','".$td."');\">";
                    $salida .= "                          <sub><img src=\"".$path."/images/abajo.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   </table>\n";
                    $salida .= "                 <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td width=\"30%\" class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        AFILIACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"10%\" align=\"LEFT\">\n";
                    $salida .= "                       ".$valor1['eps_afiliacion_id']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"30%\" class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        IDENTIFICACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td width=\"30%\"align=\"LEFT\">\n";
                    $salida .= "                       <a  title='TIPO AFILIADO'>";
                    $salida .= "                         ".$valor1['afiliado_tipo_id']."-".$valor1['afiliado_id']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td  class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        SEXO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"LEFT\">\n";
                    if($valor1['tipo_sexo_id']=='M')
                    {
                        $salida .= "              MASCULINO       ";
                    }
                    else
                    {
                        $salida .= "              FEMENINO";
                    }
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        FECHA AFILIACION";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"center\">\n";
                    $salida .= "                       ".$valor1['fecha_afiliacion_sgss']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        PARENTESCO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"left\">\n";
                    $salida .= "                       ".$valor1['descripcion_parentesco']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        FECHA NACIMIENTO";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"center\">\n";
                    $salida .= "                       <a title='TIPO AFILIADO'>";
                    $salida .= "                         ".$valor1['fecha_nacimiento']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        ZONA RESIDENCIAL";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"LEFT\">\n";
                    IF($valor1['zona_residencia']=='U')
                    {
                        $salida .= "                       URBANA";
                    }
                    else
                    {
                        $salida .= "                       RURAL";
                    }
                    
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        DIRECCION RESIDENCIA";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"LEFT\">\n";
                    $salida .= "                       <a title='DIRECCION RESIDENCIA'>";
                    $salida .= "                         ".$valor1['direccion_residencia']."-( ".$valor1['departamento_municipio'].")";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        TELEFONO RESIDENCIA";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td  align=\"LEFT\">\n";
                    $salida .= "                       ".$valor1['telefono_residencia']."";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td class=\"modulo_table_list_title\"  align=\"center\">\n";
                    $salida .= "                        TELEFONO MOVIL";
                    $salida .= "                     </td>\n";
                    $salida .= "                     <td align=\"LEFT\">\n";
                    $salida .= "                       <a title='TIPO AFILIADO'>";
                    $salida .= "                         ".$valor1['telefono_movil']."";
                    $salida .= "                       </a>";
                    $salida .= "                     </td>\n";
                    $salida .= "                   </tr>\n";
                    $salida .= "                 </table>\n";
                    $salida .= "                   </div>\n";
                    $i++;
                }
            }
        }
        $objResponse->assign("ContenidoGrup","innerHTML",utf8_encode($salida));
        return $objResponse;
    }

  

    /**
    *   Funcion que sirve para obtener la busqueda de afiliados por diferentes criterios de busqueda
    *   @param string $datos
    *   @param string $pagina
    *   @param string $contador
    *   @return array $salida vector con todos datos de los afiliados encontrados en la busqueda
    **/
    function  BuscarDatos($datos,$pagina,$contador)
    {
        $vector_permiso=SessionGetVar("permisosAfiliaciones");
        $usuario=UserGetUID();
            
        if($datos==1)
        {
            $datos = SessionGetVar("BUSQUEDA");
        }
        //var_dumP($datos);
        //var_dump($pagina);
      //var_dump($contador);

//         foreach($datos as $key=>$valor)
//         {
//             if(!empty($valor))
//             {
//                 echo "aa".$key."--".$valor;
//             }
//         }
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $afi = AutoCarga::factory("ConsultarAfiliados", "", "app","UV_MedicinaFamiliar");

        if(!empty($datos['fecha1']))
        {
            $partes=explode("-", $datos['fecha1']);
            $datos['fecha1']=$partes[2]."-".$partes[1]."-".$partes[0];
        }

        if(!empty($datos['fecha2']))
        {
            $partes=explode("-", $datos['fecha2']);
            $datos['fecha2']=$partes[2]."-".$partes[1]."-".$partes[0];
        }

        //var_dump($datos);
        if($contador==0)
        {
            $contador = $afi->GetAfiliados($datos, $count=true, $limit=false, $offset=0);
            SessionDelVar("CONTADOR");
            SessionSetVar("CONTADOR",$contador);
        }
             
        $limit=20;
        $offset=($pagina-1)*$limit;
        $afiliados = $afi->GetAfiliados($datos, $count=false, $limit, $offset);
        // var_dump($afiliados);
       //$objResponse->alert();

        if(!empty($afiliados))
        {
            SessionDelVar("BUSQUEDA");
            SessionDelVar("PAGINA");
            SessionSetVar("BUSQUEDA",$datos);
            SessionSetVar("PAGINA",$pagina);
            $salida .= "                 <table width=\"100%\" align=\"center\">\n";
            $salida .= "                    <tr class=\"normal_10AN\">\n";
            $salida .= "                       <td width=\"100%\" align=\"left\">\n";
            $salida .= "                       SE ENCONTRARON (".$contador.") REGISTRO(S)";
            $salida .= "                      </td>\n";
            $salida .= "                   </tr>\n";
            $salida .= "                 </tABLE>\n";
            $salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                       <td width=\"5%\" align=\"center\">\n";
            $salida .= "                       <a title='EPS TIPO AFILIADO'>";
            $salida .= "                        T";
            $salida .= "                       </a>";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"8%\" align=\"center\">\n";
            $salida .= "                       <a title='ESTADO DEL AFILIADO'>";
            $salida .= "                        ESTADO";
            $salida .= "                       </a>";
//             $salida .= "                       </td>\n";
//             $salida .= "                       <td width=\"5%\" align=\"center\">\n";
//             $salida .= "                       <a title='SUBESTADO DEL AFILIADO'>";
//             $salida .= "                        SUBESTADO";
//             $salida .= "                       </a>";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"8%\" align=\"center\">\n";
            $salida .= "                       <a title='IDENTIFICACION DEL AFILIADO'>";
            $salida .= "                         IDENTIFICACION";
            $salida .= "                       </a>";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"25%\" align=\"center\">\n";
            $salida .= "                       <a title='NOMBRE DEL AFILIADO'>";
            $salida .= "                          NOMBRE";
            $salida .= "                       </a>";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"15%\" align=\"center\">\n";
            $salida .= "                       <a title='ESTAMENTO'>";
            $salida .= "                        ESTAMENTO";
            $salida .= "                       </a>";
            $salida .= "                       </td>\n";
//             $salida .= "                       <td width=\"12%\" align=\"center\">\n";
//             $salida .= "                       <a title='DEPENDENCIA'>";
//             $salida .= "                        DEPENDENCIA";
//             $salida .= "                       </a>";
//             $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"8%\" align=\"center\">\n";
            $salida .= "                       <a title='FECHA DE AFILIACION'>";
            $salida .= "                          FECHA";
            $salida .= "                       </a>";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"18%\" align=\"center\">\n";
             $salida .= "                       <a title='MEDICO FAMILIAR'>";
             $salida .= "                        MEDICO FAMILIAR";
             $salida .= "                       </a>";
             $salida .= "                       </td>\n";

            $salida .= "                       <td colspan='2' width=\"4%\" align=\"center\">\n";
            $salida .= "                          ACCIONES";
            $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";
            for($i=0;$i<count($afiliados);$i++)
            {   
                $td="medico".$i;
                $salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
                $salida .= "                       <td class=\"normal_10AN\" align=\"center\">\n";
                $salida .= "                       <a title='".$afiliados[$i]['descripcion_eps_tipo_afiliado']."'>";
                $salida .= "                       ".$afiliados[$i]['eps_tipo_afiliado_id'];
                $salida .= "                       </a>\n";
                $salida .= "                       </td>\n";
                $salida .= "                      <td align=\"center\">\n";
                $salida .= "                       <a title='".$afiliados[$i]['descripcion_estado']."'>";
                $salida .= "                       ".$afiliados[$i]['estado_afiliado_id'];
                $salida .= "                      </a>\n";
                $salida .= "                      - ";
//                 $salida .= "                      <td align=\"left\">\n";
                $salida .= "                       <a title='".$afiliados[$i]['descripcion_subestado']."'>";
                $salida .= "                       ".$afiliados[$i]['subestado_afiliado_id'];
                $salida .= "                       </a>\n";
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"left\">\n";
                $salida .= "                       ".$afiliados[$i]['afiliado_tipo_id']."-".$afiliados[$i]['afiliado_id'];
                $salida .= "                      </td>\n";
                $salida .= "                      <td class=\"normal_10AN\" align=\"left\">\n";
                $salida .= "                       ".$afiliados[$i]['nombre_afiliado'];
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"left\">\n";
                $salida .= "                       <a title='".$afiliados[$i]['estamento_id']."'>";
                $salida .= "                       ".$afiliados[$i]['descripcion_estamento'];
                $salida .= "                       </a>\n";
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"center\">\n";
                $salida .= "                       ".$afiliados[$i]['fecha_afiliacion'];
                $salida .= "                      </td>\n";
                $salida .= "                      <td id='".$td."' align=\"left\">\n";
                if(!empty($afiliados[$i]['tipo_id_medico']))
                {
                    $salida.= "                        <a title='".$afiliados[$i]['tipo_id_medico']." - ".$afiliados[$i]['medico_id']."' href=\"#\">";
                    $salida.= "                           <sub>".$afiliados[$i]['nombre_profesional']."</sub>\n";
                    $salida.= "                         </a>";  
                }
                else
                {       
                        $salida.= "                         &nbsp;";      
                }
                

                $salida .= "                      </td>\n";
//                 $salida .= "                      <td  align=\"center\">\n";                          //a.bodegas_doc_id,    a.tipo_doc_bodega_id,
//                 $nuevousu = ModuloGetURL('app','UV_MedicinaFamiliar','controller','Info_AfiliadosCotizante',array('eps_afiliacion_id'=>$afiliados[$i]['eps_afiliacion_id'],'afiliado_tipo_id'=>$afiliados[$i]['afiliado_tipo_id'],'afiliado_id'=>$afiliados[$i]['afiliado_id'],'cuantos'=>$contador));//"javascript:MostarDatosDocumento('".$empresa_id."','".$valor['prefijo']."','".$valor['numero']."');MostrarCapa('ContenedorDet');IniciarDoc('DATOS DEL DOCUMENTO');";//
//                 $salida .= "                         <a title='INFORMACION COMPLETA DEL USUARIO' href=\"".$nuevousu."\">";
//                 $salida .= "                          <sub><img src=\"".$path."/images/modificar.png\" border=\"0\" width=\"21\" height=\"21\"></sub>\n";
//                 $salida .= "                         </a>\n";
//                 $salida .= "                       </td>\n";
                $salida .= "                      <td  align=\"center\">\n";
                if($afiliados[$i]['eps_tipo_afiliado_id']=='C')
                {
                    $beneficiario = "javascript:MostrarCapa('ContenedorGrup');Bus_Ben('".$afiliados[$i]['eps_afiliacion_id']."','".$afiliados[$i]['afiliado_tipo_id']."','".$afiliados[$i]['afiliado_id']."');Iniciar2('GRUPO FAMILIAR');\"";
                    $salida .="                         <a title='CONSULTAR BENEFICIARIO (GRUPO FAMILIAR)' href=\"".$beneficiario."\">";
                    $salida .="                          <sub><img src=\"".$path."/images/familia1.jpeg\" border=\"0\" width=\"21\" height=\"21\"></sub>\n";//usuarios.png
                    $salida .="                         </a>\n";
                }
                else
                {
                    $salida .= "                        &nbsp;";

                }
                 $salida .= "                       </td>\n";
                 $salida .= "                      <td  align=\"center\">\n";

                $medicos = "javascript:MostrarCapa('ContenedorMed');ExtraerMedicos('".$afiliados[$i]['eps_afiliacion_id']."','".$afiliados[$i]['afiliado_tipo_id']."','".$afiliados[$i]['afiliado_id']."','".$td."');Iniciar3('MEDICOS FAMILIARES');\"";
                $salida .="                         <a title='CONSULTAR MEIDCOS FAMILIARES' href=\"".$medicos."\">";
                $salida .="                          <sub><img src=\"".$path."/images/medico3.jpeg\" border=\"0\" width=\"21\" height=\"21\"></sub>\n";//usuarios.png
                $salida .="                         </a>\n";
                $salida .= "                       </td>\n";
                $salida .= "                    </tr>\n";
            } 
            $salida .= "                    </table>\n";
            $salida .= "                    <br>\n";
            $op="1";
            $slc=$documentos;        
            $salida .= "".ObtenerPaginadorAFI($pagina,$path,$contador,$op,$datos);
            $objResponse->call("Mostrar1");
            
        }
        else
        {
            
            $salida .= "                 <table width=\"100%\" align=\"center\">\n";
            $salida .= "                    <tr class=\"label_error\">\n";
            $salida .= "                       <td width=\"100%\" align=\"center\">\n";
            $salida .= "                       NO SE ENCONTRARON RESULTADOS CON ESE CRITERIO DE BUSQUEDA";
            $salida .= "                      </td>\n";
            $salida .= "                   </tr>\n";
            $salida .= "                 </table>\n";
            $objResponse->call("Mostrar2");
        }
        $objResponse->assign("tabla_afiliados","innerHTML",utf8_encode($salida));
        return $objResponse;
    }


    /**
    * Funcion que sirve para la paginacion de registros generados por el buscador
    * @param string $pagina
    * @param string $path direccion de los temas visuales(imagenes) de la aplicacion
    * @param string $slc cantidad total de registros  
    * @param string $op opcion  para mostrar el paginador (arriba =0 , abajo =1)
    * @param array $datos vector que contiene los datos a buscar
    * @return string $Tabla con la forma del paginador
    *
    **/
    function ObtenerPaginadorAFI($pagina,$path,$slc,$op,$datos)
    {

      
     // var_dump($slc);
      $TotalRegistros = $slc;
      $TablaPaginado = "";
        
      if($limite == null)
      {
        $LimitRow = 20;
      }
      else
      {
        $LimitRow = 20;
      }
      if ($TotalRegistros > 0)
      {
        $columnas = 1;
        $NumeroPaginas = intval($TotalRegistros/$LimitRow);
        
         if($TotalRegistros%$LimitRow > 0)
        {
          $NumeroPaginas++;
        }
            
        $Inicio = $pagina;
        if($NumeroPaginas - $pagina < 9 )
        {
          $Inicio = $NumeroPaginas - 9;
        }
        elseif($pagina > 1)
        {
          $Inicio = $pagina - 1;
        }
        
        if($Inicio <= 0)
        {
          $Inicio = 1;
        }
          
        $estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:11pt;\" "; 

        $TablaPaginado .= "<tr>\n";
        if($NumeroPaginas > 1)
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">P�inas:</td>\n";
          if($pagina > 1)
          {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                                                                                         //  xajax_ObtenerDocumentosBodegaFinal(empresa_id,centro_utilidad,bodega,usuario_id,clas_documento,tipos_documento);
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarAfiliados((xajax.getFormValues('consulta_afiliacion')),'1','".$slc."');\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarAfiliados((xajax.getFormValues('consulta_afiliacion')),'".($pagina-1)."','".$slc."');\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td>\n";
            $columnas +=2;
          }
          $Fin = $NumeroPaginas + 1;
          if($NumeroPaginas > 10)
          {
            $Fin = 10 + $Inicio;
          }
            
          for($i=$Inicio; $i< $Fin ; $i++)
          {
            if ($i == $pagina )
            {
              $TablaPaginado .="    <td class=\"modulo_list_oscuro\" $estilo align=\"center\"><b>".$i."</b></td>\n";
            }
            else
            {
              $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:BuscarAfiliados((xajax.getFormValues('consulta_afiliacion')),'".$i."','".$slc."');\">".$i."</a></td>\n";
            }
            $columnas++;
          }
        }
        if($pagina <  $NumeroPaginas )
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarAfiliados((xajax.getFormValues('consulta_afiliacion')),'".($pagina+1)."','".$slc."');\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:BuscarAfiliados((xajax.getFormValues('consulta_afiliacion')),'".($NumeroPaginas)."','".$slc."');\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td>\n";
          $columnas +=2;
        }
        $aviso .= "   <tr><td class=\"label\"  colspan=".$columnas." align=\"center\">\n";
        $aviso .= "     Página&nbsp;".$pagina." de ".$NumeroPaginas."</td>\n";
        $aviso .= "   </tr>\n";
        
        if($op == 2)
        {
          $TablaPaginado .= $aviso;
        }
        else
        {
          $TablaPaginado = $aviso.$TablaPaginado;
        }
      }
      
      $Tabla .= "<table align=\"center\" cellspacing=\"3\" >\n";
      $Tabla .= $TablaPaginado;
      $Tabla .= "</table>";

      return utf8_decode($Tabla);
    }

  

    
    /**
    *   Funcion que sirve para obtener los subestados a paritr d un estado
    *   @param string $estado
    *   @return array $subestados vector con todos los subestados del afiliado
    **/
    function ObtenerSubestados($estado)
    {

        $objResponse = new xajaxResponse();
        $afi = AutoCarga::factory("Afiliaciones", "", "app","UV_Afiliaciones");
        $subestados = $afi->ObtenerTiposSubestadosAfiliados($estado);        
        //$objResponse->assign("JJJJJ");
        //var_dump($subestados);
         if(!empty($subestados))
         {
            $html .= "    <option value=\"0\">---Seleccionar---</option>\n";
            
            foreach($subestados as $key => $datos)
            {   
                $html .= "                  <option value=\"".$datos['subestado_afiliado_id']."\" >".$datos['descripcion_subestado']."</option>\n";
            }
            $html .= "              </select>\n";
             $objResponse->assign("subestado_afiliado_id","innerHTML",$html);
         }
   
        return $objResponse;
    }




    /**
    *   Funcion que sirve para obtener la busqueda de afiliados por diferentes criterios de busqueda
    *   @param string $pagina
    *   @param string $path
    *   @param string $slc
    *   @param string $op
    *   @param string $empresa_id
    *   @param string $centro_utilidad
    *   @param string $bodega
    *   @param string $usuario_id
    *   @param string $clas_documento
    *   @param string $tipos_documento
    *   @return array $salida vector con todos datos de los afiliados encontrados en la busqueda
    **/
    function ObtenerPaginador($pagina,$path,$slc,$op,$empresa_id,$centro_utilidad,$bodega,$usuario_id,$clas_documento,$tipos_documento)
    {

      
      //echo "io";
      $TotalRegistros = $slc['contador'];
      $TablaPaginado = "";
        
      if($limite == null)
      {
        $LimitRow = 20;
      }
      else
      {
        $LimitRow = 20;
      }
      if ($TotalRegistros > 0)
      {
        $columnas = 1;
        $NumeroPaginas = intval($TotalRegistros/$LimitRow);
        
         if($TotalRegistros%$LimitRow > 0)
        {
          $NumeroPaginas++;
        }
            
        $Inicio = $pagina;
        if($NumeroPaginas - $pagina < 9 )
        {
          $Inicio = $NumeroPaginas - 9;
        }
        elseif($pagina > 1)
        {
          $Inicio = $pagina - 1;
        }
        
        if($Inicio <= 0)
        {
          $Inicio = 1;
        }
          
        $estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:11pt;\" "; 

        $TablaPaginado .= "<tr>\n";
        if($NumeroPaginas > 1)
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">P�inas:</td>\n";
          if($pagina > 1)
          {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                                                                                         //  xajax_ObtenerDocumentosBodegaFinal(empresa_id,centro_utilidad,bodega,usuario_id,clas_documento,tipos_documento);
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:MostrarDocusFinal('1','".$empresa_id."','".$centro_utilidad."','".$bodega."','".$usuario_id."','".$clas_documento."','".$tipos_documento."');\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:MostrarDocusFinal('".($pagina-1)."','".$empresa_id."','".$centro_utilidad."','".$bodega."','".$usuario_id."','".$clas_documento."','".$tipos_documento."');\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td>\n";
            $columnas +=2;
          }
          $Fin = $NumeroPaginas + 1;
          if($NumeroPaginas > 10)
          {
            $Fin = 10 + $Inicio;
          }
            
          for($i=$Inicio; $i< $Fin ; $i++)
          {
            if ($i == $pagina )
            {
              $TablaPaginado .="    <td class=\"modulo_list_oscuro\" $estilo align=\"center\"><b>".$i."</b></td>\n";
            }
            else
            {
              $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:MostrarDocusFinal('".$i."','".$empresa_id."','".$centro_utilidad."','".$bodega."','".$usuario_id."','".$clas_documento."','".$tipos_documento."');\">".$i."</a></td>\n";
            }
            $columnas++;
          }
        }
        if($pagina <  $NumeroPaginas )
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:MostrarDocusFinal('".($pagina+1)."','".$empresa_id."','".$centro_utilidad."','".$bodega."','".$usuario_id."','".$clas_documento."','".$tipos_documento."');\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:MostrarDocusFinal('".($NumeroPaginas)."','".$empresa_id."','".$centro_utilidad."','".$bodega."','".$usuario_id."','".$clas_documento."','".$tipos_documento."');\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td>\n";
          $columnas +=2;
        }
        $aviso .= "   <tr><td class=\"label\"  colspan=".$columnas." align=\"center\">\n";
        $aviso .= "     P�ina&nbsp;".$pagina." de ".$NumeroPaginas."</td>\n";
        $aviso .= "   </tr>\n";
        
        if($op == 2)
        {
          $TablaPaginado .= $aviso;
        }
        else
        {
          $TablaPaginado = $aviso.$TablaPaginado;
        }
      }
      
      $Tabla .= "<table align=\"center\" cellspacing=\"3\" >\n";
      $Tabla .= $TablaPaginado;
      $Tabla .= "</table>";

      return $Tabla;
    }

    /**
    *   Funcion que sirve para obtener la busqueda de afiliados por diferentes criterios de busqueda
    *   @param string $direccion
    *   @param string $alt
    *   @param string $imagen
    *   @param string $empresa_id
    *   @param string $prefijo
    *   @param string $numero
    *   @return array $salida1 
    **/

    function RetornarImpresionDoc($direccion,$alt,$imagen,$empresa_id,$prefijo,$numero)
    {    
        global $VISTA;
        $imagen1 = "<sub><img src=\"".$imagen."\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
        $salida1 ="<a title='".$alt."' href=javascript:Imprimir('$direccion','$empresa_id','$prefijo','$numero')>".$imagen1."</a>";
        return $salida1;
    }
    

    /**
    *   Funcion que sirve para obtener la busqueda de afiliados por diferentes criterios de busqueda
    *   @param string $tr
    *   @param string $tmp
    *   @param string $bodega_doc_id
    *   @return array $salida1
    **/
    function BorrarTmpAfirmativo($tr,$tmp,$bodega_doc_id)
    {
        $consulta=new MovBodegasSQL();
        $objResponse = new xajaxResponse();
        $buscar=$consulta->EliminarDocTemporal($bodega_doc_id,$tmp,UserGetUID());
        if($buscar==1)
        {
            $objResponse->alert("EL DOCUMENTO TEMPORAL $tmp FUE ELIMINADO EXITOSAMENTE");
            $objResponse->remove($tr);
        }
        else
        { $objResponse->alert("NO SE PUEDE BORRAR");
        } 
        
        return $objResponse;
    }
?>