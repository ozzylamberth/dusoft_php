<?php
  /**
  * Archivo Ajax (ConsultaxAfiliados)
  * Archivo que contiene funciones las cuales permiten conectarse con la BD por medio de xajax lo que permite no recargar la pagina para obtener una consulta
  *
  * @version $Id: ConsultaxAfiliados.php,v 1.3 2009/09/30 12:52:13 hugo Exp $   
  * @package IPSOFT-SIIS
  * @author Jaime Gomez  
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  */
    function Crear_el_pdf_carnet($vector,$vector1)
    {
        $objResponse=new xajaxResponse();
        $datos=array();

        $seleccionados=count($vector);
        if($seleccionados==0)
        {
            $salida="NO HA SELECCIONADO NINGUN USUARIO PARA CARNETIZAR";
            $objResponse->alert($salida);
            return $objResponse;
        }
       
        $i=0;
        foreach($vector as $key=>$value)
        {
            list($tipo_afiliacion,$afiliacion_id,$tipo_id_afiliado,$afiliado_id)=explode("-",$value);

            $consulta = AutoCarga::factory("ConsultarAfiliados", "", "app","UV_Afiliaciones");
            $datos_de_consulta[$i]=$consulta->ConsultarDatosConvenio($tipo_afiliacion,$afiliacion_id,$tipo_id_afiliado,$afiliado_id);
            $i++;
            //var_dump($datos_de_consulta);
         /*   
            $datos[$i]['tipo_afiliacion']=$tipo_afiliacion;
            $datos[$i]['afiliacion_id']=$afiliacion_id;
            $datos[$i]['tipo_id_afiliado']=$tipo_id_afiliado;
            $datos[$i]['afiliado_id']=$afiliado_id;*/
        }
        
        $posicion=$vector1['dc'];
        if(EMPTY($posicion))
        {
            $salida="NO HA SELECCIONADO EL PUNTO DE COMIENZO DE LA HOJA DE CARNETIZACION";
            $objResponse->alert($salida);
            return $objResponse;
        }
        $path = SessionGetVar("rutaImagenes");
        $objResponse=new xajaxResponse();
        $Dir="cache/carnet_univalle.pdf";
        //require "app_modules/UV_Afiliaciones/reports/pdf/carnet.php";
        require("classes/fpdf/html_Univalle_class.php");
        require("app_modules/UV_Afiliaciones/reports/pdf/fpdf_reporte_univalle.class.php");
        define('FPDF_FONTPATH','font/');

        $pdf= new fpdf_reporte_univalle('P','mm','legal');
        $pdf->set_correcion_x(1);//0.92
        $pdf->set_correcion_y(1);//0.60
        //$pdf2d=new PDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial','',10);
            //foreach($datos as $k=>$v)
            //{ 

/////////////////hoja1

            for($i=0; $i<count($datos_de_consulta);$i++)
            {
                if($posicion == 11)
                {
                    $posicion = 1;
                    $pdf->AddPage();
                    $pdf->SetFont('Arial','',10);
                }

                //echo $i;
                if($posicion == 1 && !empty($datos_de_consulta[$i]))
                {


                    list($tipo_id_conv,$conv_id,$nombre,$fecha_vence)=explode("@",$datos_de_consulta[$i]['convenio_datos']);
                        


                
                    $pdf->Text_corregida(9,45,$datos_de_consulta[$i]['afiliado_tipo_id']." - ".$datos_de_consulta[$i]['afiliado_id']);//CEDULA
                    $pdf->Text_corregida(52,45,$datos_de_consulta[$i]['primer_apellido']." ".$datos_de_consulta[$i]['segundo_apellido']);//APELLIDOS
                    $pdf->Text_corregida(52,52,$datos_de_consulta[$i]['primer_nombre']." ".$datos_de_consulta[$i]['segundo_nombre']);//NOMBRES
                    $pdf->SetFont('Arial','',7);
                    $pdf->Text_corregida(9,61,$nombre); //U
                    $pdf->SetFont('Arial','',10);
                    $pdf->Text_corregida(52,61,$datos_de_consulta[$i]['fecha_nacimiento']);//NACIMIENTO
                    $pdf->Text_corregida(9,69,$fecha_vence);//VENCE

                    if($datos_de_consulta[$i]['eps_tipo_afiliado_id']=='C')
                    {
                        $pdf->Text_corregida(58,68,"X");//COTIZA
                    }
                    if($datos_de_consulta[$i]['eps_tipo_afiliado_id']=='B')
                    {
                        $pdf->Text_corregida(89,68,"X");//BENEFCIA
                    }
                    
                    $i++;
                    $posicion++;
                }


                if($posicion == 2 && !empty($datos_de_consulta[$i]))
                {
                    list($tipo_id_conv,$conv_id,$nombre,$fecha_vence)=explode("@",$datos_de_consulta[$i]['convenio_datos']);
                    $pdf->Text_corregida(114,45,$datos_de_consulta[$i]['afiliado_tipo_id']." - ".$datos_de_consulta[$i]['afiliado_id']);//CEDULA
                    $pdf->Text_corregida(154,45,$datos_de_consulta[$i]['primer_apellido']." ".$datos_de_consulta[$i]['segundo_apellido']);//APELLIDOS
                    $pdf->Text_corregida(154,52,$datos_de_consulta[$i]['primer_nombre']." ".$datos_de_consulta[$i]['segundo_nombre']);//NOMBRES
                    $pdf->SetFont('Arial','',7);
                    $pdf->Text_corregida(114,61,$nombre); //U
                    $pdf->SetFont('Arial','',10);
                    $pdf->Text_corregida(154,61,$datos_de_consulta[$i]['fecha_nacimiento']);//NACIMIENTO
                    $pdf->Text_corregida(114,69,$fecha_vence);//VENCE
                    if($datos_de_consulta[$i]['eps_tipo_afiliado_id']=='C')
                    {
                        $pdf->Text_corregida(161,68,"X");//COTIZA
                    }
                    if($datos_de_consulta[$i]['eps_tipo_afiliado_id']=='B')
                    {
                        $pdf->Text_corregida(192,68,"X");//BENEFCIA
                    }
                    $i++;
                    $posicion++;
                }


                if($posicion == 3 && !empty($datos_de_consulta[$i]))
                {
                    list($tipo_id_conv,$conv_id,$nombre,$fecha_vence)=explode("@",$datos_de_consulta[$i]['convenio_datos']);
                    $pdf->Text_corregida(9,109,$datos_de_consulta[$i]['afiliado_tipo_id']." - ".$datos_de_consulta[$i]['afiliado_id']);//CEDULA
                    $pdf->Text_corregida(52,109,$datos_de_consulta[$i]['primer_apellido']." ".$datos_de_consulta[$i]['segundo_apellido']);//APELLIDOS
                    $pdf->Text_corregida(52,117,$datos_de_consulta[$i]['primer_nombre']." ".$datos_de_consulta[$i]['segundo_nombre']);//NOMBRES
                    $pdf->SetFont('Arial','',7);
                    $pdf->Text_corregida(9,125,$nombre); //U
                    $pdf->SetFont('Arial','',10);
                    $pdf->Text_corregida(52,125,$datos_de_consulta[$i]['fecha_nacimiento']);//NACIMIENTO
                    $pdf->Text_corregida(9,132,$fecha_vence);//VENCE
                    if($datos_de_consulta[$i]['eps_tipo_afiliado_id']=='C')
                    {
                        $pdf->Text_corregida(58,132,"X");//COTIZA
                    }
                    if($datos_de_consulta[$i]['eps_tipo_afiliado_id']=='B')
                    {
                        $pdf->Text_corregida(89,132,"X");//BENEFCIA
                    }
                    $i++;
                    $posicion++;
                }


                 if($posicion == 4 && !empty($datos_de_consulta[$i]))
                {
                    list($tipo_id_conv,$conv_id,$nombre,$fecha_vence)=explode("@",$datos_de_consulta[$i]['convenio_datos']);
                    $pdf->Text_corregida(114,109,$datos_de_consulta[$i]['afiliado_tipo_id']." - ".$datos_de_consulta[$i]['afiliado_id']);//CEDULA
                    $pdf->Text_corregida(154,109,$datos_de_consulta[$i]['primer_apellido']." ".$datos_de_consulta[$i]['segundo_apellido']);//APELLIDOS
                    $pdf->Text_corregida(154,117,$datos_de_consulta[$i]['primer_nombre']." ".$datos_de_consulta[$i]['segundo_nombre']);//NOMBRES
                    $pdf->SetFont('Arial','',7);
                    $pdf->Text_corregida(114,125,$nombre); //U
                    $pdf->SetFont('Arial','',10);
                    $pdf->Text_corregida(154,125,$datos_de_consulta[$i]['fecha_nacimiento']);//NACIMIENTO
                    $pdf->Text_corregida(114,132,$fecha_vence);//VENCE
                    if($datos_de_consulta[$i]['eps_tipo_afiliado_id']=='C')
                    {
                        $pdf->Text_corregida(161,132,"X");//COTIZA
                    }
                    if($datos_de_consulta[$i]['eps_tipo_afiliado_id']=='B')
                    {
                        $pdf->Text_corregida(192,132,"X");//BENEFCIA
                    }
                    $i++;
                    $posicion++;
                }


                if($posicion == 5 && !empty($datos_de_consulta[$i]))
                {
                    list($tipo_id_conv,$conv_id,$nombre,$fecha_vence)=explode("@",$datos_de_consulta[$i]['convenio_datos']);
                    $pdf->Text_corregida(9,173,$datos_de_consulta[$i]['afiliado_tipo_id']." - ".$datos_de_consulta[$i]['afiliado_id']);//CEDULA
                    $pdf->Text_corregida(52,173,$datos_de_consulta[$i]['primer_apellido']." ".$datos_de_consulta[$i]['segundo_apellido']);//APELLIDOS
                    $pdf->Text_corregida(52,181,$datos_de_consulta[$i]['primer_nombre']." ".$datos_de_consulta[$i]['segundo_nombre']);//NOMBRES
                    $pdf->SetFont('Arial','',7);
                    $pdf->Text_corregida(9,189,$nombre); //U
                    $pdf->SetFont('Arial','',10);
                    $pdf->Text_corregida(52,189,$datos_de_consulta[$i]['fecha_nacimiento']);//NACIMIENTO
                    $pdf->Text_corregida(9,196,$fecha_vence);//VENCE
                    if($datos_de_consulta[$i]['eps_tipo_afiliado_id']=='C')
                    {
                        $pdf->Text_corregida(58,196,"X");//COTIZA
                    }
                    if($datos_de_consulta[$i]['eps_tipo_afiliado_id']=='B')
                    {
                        $pdf->Text_corregida(89,196,"X");//BENEFCIA
                    }
                    $i++;
                    $posicion++;
                }

                //var_dump($datos_de_consulta[$i]);
                 if($posicion == 6 && !empty($datos_de_consulta[$i]))
                {   
                    list($tipo_id_conv,$conv_id,$nombre,$fecha_vence)=explode("@",$datos_de_consulta[$i]['convenio_datos']);
                    $pdf->Text_corregida(114,173,$datos_de_consulta[$i]['afiliado_tipo_id']." - ".$datos_de_consulta[$i]['afiliado_id']);//CEDULA
                    $pdf->Text_corregida(154,173,$datos_de_consulta[$i]['primer_apellido']." ".$datos_de_consulta[$i]['segundo_apellido']);//APELLIDOS
                    $pdf->Text_corregida(154,181,$datos_de_consulta[$i]['primer_nombre']." ".$datos_de_consulta[$i]['segundo_nombre']);//NOMBRES
                    $pdf->SetFont('Arial','',7);
                    $pdf->Text_corregida(114,189,$nombre); //U
                    $pdf->SetFont('Arial','',10);
                    $pdf->Text_corregida(154,189,$datos_de_consulta[$i]['fecha_nacimiento']);//NACIMIENTO
                    $pdf->Text_corregida(114,196,$fecha_vence);//VENCE
                    if($datos_de_consulta[$i]['eps_tipo_afiliado_id']=='C')
                    {
                        $pdf->Text_corregida(161,196,"X");//COTIZA
                    }
                    if($datos_de_consulta[$i]['eps_tipo_afiliado_id']=='B')
                    {
                        $pdf->Text_corregida(192,196,"X");//BENEFCIA
                    }
                    $i++;$posicion++;
                }



                if($posicion == 7 && !empty($datos_de_consulta[$i]))
                {

                    list($tipo_id_conv,$conv_id,$nombre,$fecha_vence)=explode("@",$datos_de_consulta[$i]['convenio_datos']);
                    $pdf->Text_corregida(9,236,$datos_de_consulta[$i]['afiliado_tipo_id']." - ".$datos_de_consulta[$i]['afiliado_id']);//CEDULA
                    $pdf->Text_corregida(52,236,$datos_de_consulta[$i]['primer_apellido']." ".$datos_de_consulta[$i]['segundo_apellido']);//APELLIDOS
                    $pdf->Text_corregida(52,244,$datos_de_consulta[$i]['primer_nombre']." ".$datos_de_consulta[$i]['segundo_nombre']);//NOMBRES
                    $pdf->SetFont('Arial','',7);
                    $pdf->Text_corregida(9,252,$nombre); //U
                    $pdf->SetFont('Arial','',10);
                    $pdf->Text_corregida(52,252,$datos_de_consulta[$i]['fecha_nacimiento']);//NACIMIENTO
                    $pdf->Text_corregida(9,259,$fecha_vence);//VENCE
                    if($datos_de_consulta[$i]['eps_tipo_afiliado_id']=='C')
                    {
                        $pdf->Text_corregida(56,258,"X");//COTIZA
                    }
                    if($datos_de_consulta[$i]['eps_tipo_afiliado_id']=='B')
                    {
                        $pdf->Text_corregida(89,258,"X");//BENEFCIA
                    }
                    $i++;$posicion++;
                }


                if($posicion == 8 && !empty($datos_de_consulta[$i]))
                {

                    list($tipo_id_conv,$conv_id,$nombre,$fecha_vence)=explode("@",$datos_de_consulta[$i]['convenio_datos']);
                    $pdf->Text_corregida(114,236,$datos_de_consulta[$i]['afiliado_tipo_id']." - ".$datos_de_consulta[$i]['afiliado_id']);//CEDULA
                    $pdf->Text_corregida(154,236,$datos_de_consulta[$i]['primer_apellido']." ".$datos_de_consulta[$i]['segundo_apellido']);//APELLIDOS
                    $pdf->Text_corregida(154,244,$datos_de_consulta[$i]['primer_nombre']." ".$datos_de_consulta[$i]['segundo_nombre']);//NOMBRES
                    $pdf->SetFont('Arial','',7);
                    $pdf->Text_corregida(114,252,$nombre); //U
                    $pdf->SetFont('Arial','',10);
                    $pdf->Text_corregida(154,252,$datos_de_consulta[$i]['fecha_nacimiento']);//NACIMIENTO
                    $pdf->Text_corregida(114,259,$fecha_vence);//VENCE
                    if($datos_de_consulta[$i]['eps_tipo_afiliado_id']=='C')
                    {
                        $pdf->Text_corregida(161,260,"X");//COTIZA
                    }
                    if($datos_de_consulta[$i]['eps_tipo_afiliado_id']=='B')
                    {
                        $pdf->Text_corregida(192,260,"X");//BENEFCIA
                    }
                    $i++;$posicion++;
                }


                if($posicion == 9 && !empty($datos_de_consulta[$i]))
                {
                    //var_dump("a yaaaaaaaaaaa");
                    list($tipo_id_conv,$conv_id,$nombre,$fecha_vence)=explode("@",$datos_de_consulta[$i]['convenio_datos']);
                    $pdf->Text_corregida(9,299,$datos_de_consulta[$i]['afiliado_tipo_id']." - ".$datos_de_consulta[$i]['afiliado_id']);//CEDULA
                    $pdf->Text_corregida(52,299,$datos_de_consulta[$i]['primer_apellido']." ".$datos_de_consulta[$i]['segundo_apellido']);//APELLIDOS
                    $pdf->Text_corregida(52,307,$datos_de_consulta[$i]['primer_nombre']." ".$datos_de_consulta[$i]['segundo_nombre']);//NOMBRES
                    $pdf->SetFont('Arial','',7);
                    $pdf->Text_corregida(9,315,$nombre); //U
                    $pdf->SetFont('Arial','',10);
                    $pdf->Text_corregida(52,315,$datos_de_consulta[$i]['fecha_nacimiento']);//NACIMIENTO
                    $pdf->Text_corregida(9,322,$fecha_vence);//VENCE
                    if($datos_de_consulta[$i]['eps_tipo_afiliado_id']=='C')
                    {
                        $pdf->Text_corregida(58,322,"X");//COTIZA
                    }
                    if($datos_de_consulta[$i]['eps_tipo_afiliado_id']=='B')
                    {
                       $pdf->Text_corregida(89,322,"X");//BENEFCIA
                    }
                    $i++;$posicion++;
                }


                if($posicion == 10 && !empty($datos_de_consulta[$i]))
                {

                    list($tipo_id_conv,$conv_id,$nombre,$fecha_vence)=explode("@",$datos_de_consulta[$i]['convenio_datos']);
                    $pdf->Text_corregida(114,299,$datos_de_consulta[$i]['afiliado_tipo_id']." - ".$datos_de_consulta[$i]['afiliado_id']);//CEDULA
                    $pdf->Text_corregida(154,299,$datos_de_consulta[$i]['primer_apellido']." ".$datos_de_consulta[$i]['segundo_apellido']);//APELLIDOS
                    $pdf->Text_corregida(154,307,$datos_de_consulta[$i]['primer_nombre']." ".$datos_de_consulta[$i]['segundo_nombre']);//NOMBRES
                    $pdf->SetFont('Arial','',7);
                    $pdf->Text_corregida(114,315,$nombre); //U
                    $pdf->SetFont('Arial','',10);
                    $pdf->Text_corregida(154,315,$datos_de_consulta[$i]['fecha_nacimiento']);//NACIMIENTO
                    $pdf->Text_corregida(114,322,$fecha_vence);//VENCE
                    if($datos_de_consulta[$i]['eps_tipo_afiliado_id']=='C')
                    {
                        $pdf->Text_corregida(161,322,"X");//COTIZA
                    }
                    if($datos_de_consulta[$i]['eps_tipo_afiliado_id']=='B')
                    {
                        $pdf->Text_corregida(192,322,"X");//BENEFCIA
                    }
                    $i++;$posicion++;
                }

            }

        $pdf->Output($Dir,'F');
        $salida="SE HAN CARNETIZADO ".$seleccionados." USUARIOS";
        $objResponse->alert($salida);
        $objResponse->Call("abreVentanaHT");
        return $objResponse;
    }
    
    function RegistroCarta($vector)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse=new xajaxResponse();
       // var_dump($vector);
       // $registrar = AutoCarga::factory("AccidentesdeTrabajoLogica", "", "app","UV_AccidentesdeTrabajo");
        //$ciudades=$registrar->ConsultarCiudades($departamento);

        //if(!empty($ciudades))
        //{
        //    $salida .="   <option value= \"-1\" selected>SELECCIONAR</option>";
         //   for($j=0;$j<sizeof($ciudades);$j++)
           // {
             //   $salida .=" <option value= '".$ciudades[$j]['tipo_mpio_id']."'>".$ciudades[$j]['municipio']."</option>";
           // }
         //    $objResponse->assign("ciudades","disabled",false);
      //  }
        //else
       // {
        //    $salida .="   <option value= \"-1\" >SELECCIONAR</option>";
            //$salida="<label class='label_error'>ESE DEPARTAMENTO NO TIENEN MUNICIPIOS REGISTRADOS </label>";
       // }

        $objResponse->alert($salida);
        //$objResponse->assign("ciudades","innerHTML",$salida);
       
       return $objResponse;
    }
    /**
    * Funcion que se utiliza para crear un select el cual contendra los municipiso de determinado departamento
    * @param string $departamento
    * @return string $salida con la lista de la ciudades
    **/
    function Llamar_ciudades($departamento)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse=new xajaxResponse();
        $registrar = AutoCarga::factory("ConsultarAfiliados", "", "app","UV_Afiliaciones");
         
        $dto = explode("-",$departamento);
        $ciudades=$registrar->ObtenerCiudades($dto[0]);

        if(!empty($ciudades))
        {
            $salida .="   <option value= \"-1\" selected>SELECCIONAR</option>";
            foreach($ciudades as $key => $dtl)
            {
                $salida .=" <option value= '".$dtl['municipio']."'>".$dtl['municipio']."</option>";
            }
             $objResponse->assign("ciudades","disabled",false);
        }
        else
        {
            $salida .="   <option value= \"-1\" >SELECCIONAR</option>";
            //$salida="<label class='label_error'>ESE DEPARTAMENTO NO TIENEN MUNICIPIOS REGISTRADOS </label>";
        }

        //$objResponse->alert($salida);
        $objResponse->assign("ciudades","innerHTML",$salida);
       
       return $objResponse;
    }
    /**
    *
    *
    *
    **/
    function ImpresionCarnetParte1($vector)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        //var_dump($vector);
        $salida .= "                 <form name='carnetizar2' id='carnetizar2' action=\"#\" method=\"post\">\n";
        $salida .= "                    <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            for($i=1;$i<11;$i++)
            {   
                $salida .= "                    <tr class=\"modulo_list_claro\" >\n";
                $salida .= "                      <td width=\"50%\" >\n";                          //a.bodegas_doc_id,    a.tipo_doc_bodega_id,
                $salida .= "                         <input type=\"radio\" class=\"input-text\" name=\"dc\" value=\"".$i."\">\n";
                $salida .= "                          <sub><img src=\"".$path."/images/smartcard112.png\" border=\"0\" width=\"110\" height=\"50\"></sub>\n";
                $salida .= "                       </td>\n";
                $i++;
                $salida .= "                      <td width=\"50%\" >\n";                          //a.bodegas_doc_id,    a.tipo_doc_bodega_id,
                $salida .= "                         <input type=\"radio\" class=\"input-text\" name=\"dc\" value=\"".($i)."\" onclick='alert(this.value);'>\n";
                $salida .= "                          <sub><img src=\"".$path."/images/smartcard112.png\" border=\"0\" width=\"110\" height=\"50\"></sub>\n";
                $salida .= "                       </td>\n";
                $salida .= "                    </tr>\n";
            }
                $salida .= "                    <tr class=\"modulo_list_claro\" >\n";
                $salida .= "                      <td colspan='2' align='center'>\n";                          //a.bodegas_doc_id,    a.tipo_doc_bodega_id,
                $salida .= "                        <input type=\"button\" class=\"input-submit\" value=\"GENERAR PDF\" onclick=\"xajax_Crear_el_pdf_carnet(xajax.getFormValues('carnetizar'),xajax.getFormValues('carnetizar2'));\">\n";
                $salida .= "                      </td>\n";
                $salida .= "                    </tr>\n";
                $salida .= "            </table>\n";
                $salida .= "          </form>\n";
        $objResponse->assign("ContenidoMed","innerHTML",utf8_encode($salida));
        return $objResponse;
    }
    
    /**
    *   Funcion que sirve para obtener la busqueda de afiliados por diferentes criterios de busqueda
    *   @param string $datos
    *   @param string $pagina
    *   @param string $contador
    *   @return array $salida vector con todos datos de los afiliados encontrados en la busqueda
    **/
    function  BuscarDatosCarnet($datos,$pagina,$contador)
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
        $afi = AutoCarga::factory("ConsultarAfiliados", "", "app","UV_Afiliaciones");

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
         //var_dump($afiliados);
       //$objResponse->alert();

        if(!empty($afiliados))
        {
            SessionDelVar("BUSQUEDA");
            SessionDelVar("PAGINA");
            SessionSetVar("BUSQUEDA",$datos);
            SessionSetVar("PAGINA",$pagina);
            $salida .= "                 <table width=\"100%\" align=\"center\">\n";
            $salida .= "                    <tr class=\"normal_10AN\">\n";
            $salida .= "                       <td width=\"50%\" align=\"left\">\n";
            $salida .= "                       SE ENCONTRARON (".$contador.") REGISTRO(S)";
            $salida .= "                      </td>\n";
            $salida .= "                       <td width=\"50%\" align=\"right\">\n";
            $javadx = "javascript:MostrarCapa('ContenedorMed');Iniciar3('SELECCIONE PUNTO DE INICIO');xajax_ImpresionCarnetParte1(xajax.getFormValues('carnetizar'));";
            $salida .= "                       <input type=\"button\" class=\"input-submit\" value=\"CARNETIZAR\" onclick=\"".$javadx."\">\n";
            $salida .= "                      </td>\n";                  
            $salida .= "                   </tr>\n";
            
            $salida .= "                 </tABLE>\n";
            $salida .= "                 <form name='carnetizar' id='carnetizar' action=\"#\" method=\"post\">\n";
            $salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                       <td width=\"2%\" align=\"center\">\n";
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
            $salida .= "                       <td width=\"13%\" align=\"center\">\n";
            $salida .= "                       <a title='IDENTIFICACION DEL AFILIADO'>";
            $salida .= "                         IDENTIFICACION";
            $salida .= "                       </a>";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"27%\" align=\"center\">\n";
            $salida .= "                       <a title='NOMBRE DEL AFILIADO'>";
            $salida .= "                          NOMBRE";
            $salida .= "                       </a>";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"12%\" align=\"center\">\n";
            $salida .= "                       <a title='ESTAMENTO'>";
            $salida .= "                        ESTAMENTO";
            $salida .= "                       </a>";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"12%\" align=\"center\">\n";
            $salida .= "                       <a title='DEPENDENCIA'>";
            $salida .= "                        DEPENDENCIA";
            $salida .= "                       </a>";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"12%\" align=\"center\">\n";
            $salida .= "                       <a title='TIPO DE APORTANTE'>";
            $salida .= "                        APORTANTE";
            $salida .= "                       </a>";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"8%\" align=\"center\">\n";
            $salida .= "                       <a title='FECHA DE AFILIACION'>";
            $salida .= "                          FECHA";
            $salida .= "                       </a>";
            $salida .= "                       </td>\n";
            $salida .= "                       <td colspan='1' width=\"4%\" align=\"center\">\n";
            $salida .= "                          CARNETIZADO";
            $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";
            for($i=0;$i<count($afiliados);$i++)
            {   
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
                $salida .= "                      <td align=\"left\">\n";
                $salida .= "                       <a title='".$afiliados[$i]['descripcion_dependencia']."'>";
                $salida .= "                       ".$afiliados[$i]['descripcion_dependencia'];//$afiliados[$i]['codigo_dependencia_id'];
                $salida .= "                       </a>\n";
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"left\">\n";
                $salida .= "                       <a title='".$afiliados[$i]['descripcion_tipo_aportante']."'>";
                $salida .= "                       ".$afiliados[$i]['descripcion_tipo_aportante'];//$afiliados[$i]['tipo_aportante_id'];
                $salida .= "                       </a>\n";
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"center\">\n";
                $salida .= "                       ".$afiliados[$i]['fecha_afiliacion'];
                $salida .= "                      </td>\n";       
//                 $salida .= "                      <td  align=\"center\">\n";                          //a.bodegas_doc_id,    a.tipo_doc_bodega_id,
//                 $nuevousu = ModuloGetURL('app','UV_Afiliaciones','controller','Info_AfiliadosCotizante',array('eps_afiliacion_id'=>$afiliados[$i]['eps_afiliacion_id'],'afiliado_tipo_id'=>$afiliados[$i]['afiliado_tipo_id'],'afiliado_id'=>$afiliados[$i]['afiliado_id'],'cuantos'=>$contador));//"javascript:MostarDatosDocumento('".$empresa_id."','".$valor['prefijo']."','".$valor['numero']."');MostrarCapa('ContenedorDet');IniciarDoc('DATOS DEL DOCUMENTO');";//
//                 $salida .= "                         <a title='INFORMACION COMPLETA DEL USUARIO' href=\"".$nuevousu."\">";
//                 $salida .= "                          <sub><img src=\"".$path."/images/modificar.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
//                 $salida .= "                         </a>\n";
//                 $salida .= "                       </td>\n";
                $salida .= "                      <td  align=\"center\">\n";
                 if($afiliados[$i]['estamento_id']=='V' || (!empty($afiliados[$i]['cotizante_conv_tipo_id']) && !empty($afiliados[$i]['cotizante_conv_id'])))
                 {
                                                                                                        //["afiliado_tipo_id"]=>    string(2) "CC"    ["afiliado_id"]=>    string(2) "11"
                    $salida .="                     <input type=\"checkbox\" name=\"".$i."\" value=\"".$afiliados[$i]['eps_tipo_afiliado_id']."-".$afiliados[$i]['eps_afiliacion_id']."-".$afiliados[$i]['afiliado_tipo_id']."-".$afiliados[$i]['afiliado_id']."\">";

                                                                                                
//                      $beneficiarioA = ModuloGetURL('app','UV_Afiliaciones','controller','Solicitud_CartaConvenio',array('eps_afiliacion_id'=>$afiliados[$i]['eps_afiliacion_id'],'afiliado_tipo_id'=>$afiliados[$i]['afiliado_tipo_id'],'afiliado_id'=>$afiliados[$i]['afiliado_id']));//"javascript:MostarDatosDocumento('".$empresa_id."','".$valor['prefijo']."','".$valor['numero']."');MostrarCapa('ContenedorDet');IniciarDoc('DATOS DEL DOCUMENTO');";//
//                      $salida .= "                         <a title='Solicitud de atencion por convenio' href=\"".$beneficiarioA."\">";
//                      $salida .= "                          <sub><img src=\"".$path."/images/show.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
//                      $salida .= "                         </a>\n";
                 }
                 
                 $salida .= "                       </td>\n";
                $salida .= "                    </tr>\n";
            } 
            $salida .= "                    </table>\n";
            $salida .= "                 </form>\n";
            $salida .= "                 <table width=\"100%\" align=\"center\">\n";
            $salida .= "                    <tr class=\"normal_10AN\">\n";
            $salida .= "                     <td align=\"right\">\n";
            $javadx = "javascript:MostrarCapa('ContenedorMed');Iniciar3('SELECCIONE PUNTO DE INICIO');xajax_ImpresionCarnetParte1(xajax.getFormValues('carnetizar'));";
            $salida .= "                       <input type=\"button\" class=\"input-submit\" value=\"CARNETIZAR\" onclick=\"".$javadx."\">\n";
            $salida .= "                      </td>\n";
            $salida .= "                   </tr>\n";
            $salida .= "                 </tABLE>\n";
            $salida .= "                    <br>\n";
            $op="1";
            $slc=$documentos;        
            $salida .= "".ObtenerPaginadorCAR($pagina,$path,$contador,$op,$datos);
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

        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $afi = AutoCarga::factory("ConsultarAfiliados", "", "app","UV_Afiliaciones");
                
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

        if($contador==0)
        {
            $contador = $afi->GetAfiliados($datos, $count=true, $limit=false, $offset=0);
            SessionDelVar("CONTADOR");
            SessionSetVar("CONTADOR",$contador);
        }
             
        $limit=20;
        $offset=($pagina-1)*$limit;
        $afiliados = $afi->GetAfiliados($datos, $count=false, $limit, $offset);

        if(!empty($afiliados))
        {
          SessionDelVar("BUSQUEDA");
          SessionDelVar("PAGINA");
          SessionSetVar("BUSQUEDA",$datos);
          SessionSetVar("PAGINA",$pagina);
          $perfil = $vector_permiso[$usuario]['perfil_id'];
          
          //$salida .= "<pre>".print_r(SessionGetVar("permisosAfiliaciones"),true)."</pre>";
          $salida .= "                 <table width=\"100%\" align=\"center\">\n";
          $salida .= "                    <tr class=\"normal_10AN\">\n";
          $salida .= "                       <td width=\"50%\" align=\"left\">\n";
          $salida .= "                       SE ENCONTRARON  (".$contador.") REGISTRO(S)";
          $salida .= "                      </td>\n";
          $salida .= "                       <td width=\"50%\" align=\"right\">\n";
          $salida .= "                       ";
          $salida .= "                      </td>\n";
          $salida .= "                   </tr>\n";
          $salida .= "                 </tABLE>\n";
          $salida .= "  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          $salida .= "    <tr class=\"modulo_table_list_title\">\n";
          $salida .= "      <td width=\"2%\">\n";
          $salida .= "        <a title='EPS TIPO AFILIADO'>T</a>";
          $salida .= "      </td>\n";
          $salida .= "      <td width=\"12%\">ESTADO</td>\n";
          $salida .= "      <td width=\"33%\">AFILIADO</td>\n";
          $salida .= "      <td width=\"14%\">ESTAMENTO</td>\n";
          $salida .= "      <td width=\"12%\">DEPENDENCIA</td>\n";
          $salida .= "      <td width=\"12%\">\n";
          $salida .= "        <a title='TIPO DE APORTANTE'>APORTANTE</a>";
          $salida .= "      </td>\n";
          $salida .= "      <td width=\"8%\">\n";
          $salida .= "        <a title='FECHA DE AFILIACION'>FECHA</a>";
          $salida .= "      </td>\n";
          $salida .= "      <td colspan=\"4\" width=\"%\" >OPCIONES</td>\n";
          $salida .= "    </tr>\n";
          
          for($i=0;$i<count($afiliados);$i++)
          {   
            $salida .= "    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
            $salida .= "      <td class=\"normal_10AN\" align=\"center\">\n";
            $salida .= "        <a title='".$afiliados[$i]['descripcion_eps_tipo_afiliado']."'>";
            $salida .= "          ".$afiliados[$i]['eps_tipo_afiliado_id'];
            $salida .= "        </a>\n";
            $salida .= "        </td>\n";
            $salida .= "        <td >\n";
            $salida .= "          ".$afiliados[$i]['descripcion_estado']." - ".$afiliados[$i]['descripcion_subestado']."\n";
            $salida .= "        </td>\n";
            $salida .= "        <td>\n";
            $salida .= "          <b class=\"normal_10AN\">".$afiliados[$i]['afiliado_tipo_id']." ".$afiliados[$i]['afiliado_id']."</b> ".$afiliados[$i]['nombre_afiliado'];
            $salida .= "        </td>\n";
            $salida .= "        <td >\n";
            $salida .= "          <a title='".$afiliados[$i]['estamento_id']."'>".$afiliados[$i]['descripcion_estamento']."</a>\n";
            $salida .= "        </td>\n";
            $salida .= "        <td >".$afiliados[$i]['descripcion_dependencia']."</td>\n";
            $salida .= "        <td >".$afiliados[$i]['descripcion_tipo_aportante']."</td>\n";
            $salida .= "        <td align=\"center\">".$afiliados[$i]['fecha_afiliacion']."</td>\n";       
            
            $nuevousu = ModuloGetURL('app','UV_Afiliaciones','controller','Info_AfiliadosCotizante',array('eps_afiliacion_id'=>$afiliados[$i]['eps_afiliacion_id'],'afiliado_tipo_id'=>$afiliados[$i]['afiliado_tipo_id'],'afiliado_id'=>$afiliados[$i]['afiliado_id'],'cuantos'=>$contador));
            
            $salida .= "                      <td  align=\"center\" width=\"3\">\n";                          //a.bodegas_doc_id,    a.tipo_doc_bodega_id,
            $salida .= "                         <a title='INFORMACION COMPLETA DEL USUARIO' href=\"".$nuevousu."\">";
            $salida .= "                          <sub><img src=\"".$path."/images/modificar.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
            $salida .= "                         </a>\n";
            $salida .= "                       </td>\n";
            if($afiliados[$i]['estado_afiliado_id'] != "RE")
            {  
              $salida .= "                      <td  align=\"center\" width=\"3\">\n";
              if($afiliados[$i]['eps_tipo_afiliado_id']=='C' && $perfil=='C' )
              {
                $beneficiario = ModuloGetURL('app','UV_Afiliaciones','controller','AdicionarBeneficiario',array('afiliado_tipo_id'=>$afiliados[$i]['afiliado_tipo_id'],'afiliado_id'=>$afiliados[$i]['afiliado_id'],"eps_afiliacion_id"=>$afiliados[$i]['eps_afiliacion_id']));
                $salida .= "                         <a title='ADICIONAR BENEFICIARIO' href=\"".$beneficiario."\">";
                $salida .= "                          <sub><img src=\"".$path."/images/usuarios.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $salida .= "                         </a>\n";
              }
              else
                $salida .= "                        &nbsp;";
                                
              $salida .= "                       </td>\n";
              $salida .= "                      <td  align=\"center\" width=\"3\">\n";
              if($afiliados[$i]['estamento_id']=='V' && $perfil=='C')
              {
                  $beneficiarioA = ModuloGetURL('app','UV_Afiliaciones','controller','ModificarFechasConvenio',array('eps_afiliacion_id'=>$afiliados[$i]['eps_afiliacion_id'],'afiliado_tipo_id'=>$afiliados[$i]['afiliado_tipo_id'],'afiliado_id'=>$afiliados[$i]['afiliado_id']));
                  $salida .= "                         <a title='Modificar Fechas Convenio' href=\"".$beneficiarioA."\">";
                  $salida .= "                          <sub><img src=\"".$path."/images/fecha_fin.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                  $salida .= "                         </a>\n";
              }
              else
                $salida .= "                        &nbsp;";
              
              $salida .= "                       </td>\n";
              $salida .= "                      <td  align=\"center\" width=\"3\">\n";
              if($afiliados[$i]['estamento_id']!='V' && $perfil=='C')
              {
                $beneficiarioA = ModuloGetURL('app','UV_Afiliaciones','controller','Solicitud_CartaConvenio',array('eps_afiliacion_id'=>$afiliados[$i]['eps_afiliacion_id'],'afiliado_tipo_id'=>$afiliados[$i]['afiliado_tipo_id'],'afiliado_id'=>$afiliados[$i]['afiliado_id'],"eps_tipo_afiliado_id"=>$afiliados[$i]['eps_tipo_afiliado_id']));//"javascript:MostarDatosDocumento('".$empresa_id."','".$valor['prefijo']."','".$valor['numero']."');MostrarCapa('ContenedorDet');IniciarDoc('DATOS DEL DOCUMENTO');";//
                $salida .= "                         <a title='Solicitud de atencion por convenio' href=\"".$beneficiarioA."\">";
                $salida .= "                          <sub><img src=\"".$path."/images/show.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $salida .= "                         </a>\n";
              }
              else
                $salida .= "                        &nbsp;";
                
              $salida .= "                       </td>\n";
            }
            else
            {
              $salida .= "<td colspan=\"3\"></td>\n"; 
            }
            $salida .= "                    </tr>\n";
          } 
          $salida .= "                    </table>\n";
          $salida .= "                    <br>\n";
          $op="1";
          $slc=$documentos;        
          $salida .= "".ObtenerPaginadorAFI($pagina,$path,$contador,$op,$datos);
          $objResponse->call("Mostrar1");
          
          if($datos['eps_tipo_afiliado_id'] == 'C')
          {
            SessionSetVar("BuscadorAfiliados",$datos);
            $objResponse->assign("cotizantes_beneficiarios","style.display","block");
          }
          else
          {
            SessionDelVar("BuscadorAfiliados",$datos);
            $objResponse->assign("cotizantes_beneficiarios","style.display","none");
          }
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
        $objResponse->assign("tabla_afiliados","innerHTML",$salida);
        return $objResponse;
    }
    /**
    * Funcion que sirve para la paginacion de registros generados por el buscador de 
    * @param string $pagina
    * @param string $path direccion de los temas visuales(imagenes) de la aplicacion
    * @param string $slc cantidad total de registros  
    * @param string $op opcion  para mostrar el paginador (arriba =0 , abajo =1)
    * @param array $datos vector que contiene los datos a buscar
    * @return string $Tabla con la forma del paginador
    *
    **/
    function ObtenerPaginadorCAR($pagina,$path,$slc,$op,$datos)
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
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarAfiliadosCar((xajax.getFormValues('consulta_afiliacion')),'1','".$slc."');\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarAfiliadosCar((xajax.getFormValues('consulta_afiliacion')),'".($pagina-1)."','".$slc."');\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
              $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:BuscarAfiliadosCar((xajax.getFormValues('consulta_afiliacion')),'".$i."','".$slc."');\">".$i."</a></td>\n";
            }
            $columnas++;
          }
        }
        if($pagina <  $NumeroPaginas )
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarAfiliadosCar((xajax.getFormValues('consulta_afiliacion')),'".($pagina+1)."','".$slc."');\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:BuscarAfiliadosCar((xajax.getFormValues('consulta_afiliacion')),'".($NumeroPaginas)."','".$slc."');\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
    /**
    * Funcion pata habilitar el combo de entidades convenio,segun el estamento
    *
    * @param string $estamento_id Identificador del estamento
    *
    * @return object
    */
    function EntidadesConvenios($estamento_id)
    {
      $afi = AutoCarga::factory("Afiliaciones","","app","UV_Afiliaciones");
      $est = $afi->ObtenerEstamentos($estamento_id);

      $objResponse = new xajaxResponse();
      if($est[$estamento_id]['estamento_siis'] == 'V')
        $objResponse->call("HabilitarEntidad");
      else
        $objResponse->call("InhabilitarEntidad");
        
      return $objResponse;
    }
?>