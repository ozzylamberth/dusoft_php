<?php
  /**************************************************************************************
  * $Id: definirProv.php,v 1.4 2007/07/11 21:48:29 jgomez Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  * @author Jaime gomez
  **************************************************************************************/

  $VISTA = "HTML";
  $_ROOT = "../../../";
  include "../../../app_modules/CrearProveedores/classes/CrearSQL.class.php";
  include "../../../classes/ClaseHTML/ClaseHTML.class.php";
  if (!IncludeClass('ManejoTerceros'))
  {
     die(MsgOut("Error al incluir archivo","ManejoTerceros"));
  }
  
  function InsertarBancoProveedor($form)
  {
    $consulta=new CrearSQL();
    $objResponse = new xajaxResponse();
	  //$objResponse->alert(print_r($form,true));
    
    $rta=$consulta->InsertarBancoProveedor_SQL($form);
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $BancosProveedor=$consulta->ListarBancosProveedor($form['codigo_proveedor_id']);
    
    
    $html  = "  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
		$html .= "    <tr class=\"formulacion_table_list\">\n";
    $html .= "      <td width=\"50%\">BANCO</td>\n";
    $html .= "      <td width=\"24%\">NUMERO</td>\n";
    $html .= "      <td width=\"24%\">TIPO CUENTA</td>\n";
    $html .= "      <td width=\"2%\">OP</td>\n";
		$html .= "    </tr>\n";
    $i=0;
		
		foreach($BancosProveedor as $key=> $dtl)
		{
      $html .= "                    <tr class=\"modulo_list_claro\">\n";
      $html .= "                      <td>".$dtl['descripcion']."</td>\n";
      $html .= "                      <td>".$dtl['numero_cuenta']."</td>\n";
      $html .= "                      <td>".$dtl['tipo_cuenta']."</td>\n";
      $html .= "                      <td>\n";
      $html .= "                        <a href=\"#\" title=\"INACTIVAR\">\n";
      $html .= "                          <img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\">\n";
      $html .= "                        </a>\n";
      $html .= "                      </td>\n";
      $html .= "                    </tr>\n";
    }
    $html .= "  </table><br>\n";
    $objResponse->assign("bancos_asoc","innerHTML",$html);
    return $objResponse;
  }
  
  
  /**
    * Metodo para Asignar bancos a un  proveedor
    * @return string  con la forma de los datos para Asignar un banco a un proveedor.
    * @access public
   */
    
  function asignacion_bancos($CodigoProveedor)
  {
    $consulta=new CrearSQL();
    $Bancos=$consulta->ListarBancos_P($CodigoProveedor);
	$TiposCuentas=$consulta->ListarTiposCuentas();
	//Lista los Bancos que no tenga asignados un proveedor
    if(count($Bancos)<1)
    {
      $Bancos=$consulta->ListarBancos_T($CodigoProveedor); //si no tiene Bancos, los muestra todos.
    }
    $BancosProveedor=$consulta->ListarBancosProveedor($CodigoProveedor); 
    $objResponse = new xajaxResponse();
  /*
  * Pendiente por arreglar el Formulario
  */
		 $html  = "    <form name=\"BancoProveedor\" id=\"BancoProveedor\" method=\"post\">\n";
		 $html .= "                  <table width=\"0%\" align=\"center\" class=\"modulo_table_list\">\n";
     $html .= "                    <tr class=\"modulo_table_list_title\">\n";
     $html .= "                      <td align=\"center\" width=\"15%\" colspan=\"3\">\n";
     $html .= "                       LISTADO DE BANCOS A INCLUIR:";
     $html .= "                      </td>\n";
		 $html .= "                    </tr>\n";
		 $html .= "                    <tr>\n";
	   $html .= "<tr>\n"; 
		 $html .= "                    <td align=\"center\" width=\"80%\" class=\"formulacion_table_list\">\n";
     $html .= "                        <a title='Banco'>BANCO :</a>";//<a title='ORGANIZAR POR NUMERO DE DOCUMENTO' href=\"".$javadx2."\">".$imagen1."</a>
     $html .= "                      </td>\n";
		 $html .= "                    <td align=\"center\" width=\"10%\">\n";
     $html .='<select name="banco" class="select">';
		$i=0;
    
	foreach($Bancos as $key=>$dato)
		{
      $html .='<option value='.$Bancos[$i]["banco"].'>'.$Bancos[$i]["descripcion"].'</option>\n';
      $i=$i+1;
		}
    $html .='</select><br\n> ';
    $html .= "    </td>\n";
		$html .= "  </tr>\n";
		$html .= "  <tr>\n";
		$html .= "    <td>NUMERO DE CUENTA</td>\n";
		$html .= "    <td>\n";
		$html .= "      <input name=\"numero_cuenta\" type=\"text\" class=\"input-text\" style=\"width:80%\">\n";
    $html .= "    </td>\n";
		$html .= "  </tr>\n";
		
		$html .= "  <tr>\n";
		$html .= "    <td>TIPO DE CUENTA</td>\n";
		$html .= "    <td>\n";
		$html .='<select name="tipo_de_cuenta" class="select">';	
			foreach($TiposCuentas as $key=>$dtl)
			{
			$html .='<option value='.$dtl["tipo_de_cuenta_id"].'>'.$dtl["tipo_de_cuenta_id"].' '.$dtl["descripcion"].'</option>\n';
			}
		$html .='</select><br\n> ';
		$html .= "    </td>\n";
		$html .= "  </tr>\n";
		
	  $html .= "<tr>\n"; 
         $html .= "                      <td align=\"center\" width=\"23%\" colspan='2'>\n";
         //$html .= "                        <a title='Banco' href='#' onclick='xajax_InsertarBancoProveedor(".$CodigoProveedor.")'>INCLUIR BANCO</a>";
		 $html .= "<input type=\"button\" value=\"Incluir Banco\" onclick=\"xajax_InsertarBancoProveedor(xajax.getFormValues('BancoProveedor'));\">"; //'.$CodigoProveedor.',xajax.getFormValues("BancoProveedor")
		 $html .= "<input type=\"hidden\" value=".$CodigoProveedor." name=\"codigo_proveedor_id\">";
		 $html .= "</form>";
     $html .= "                      </td>\n";
     $html .= "                    </tr>\n";
		 $html .= "                    </table>\n";
		 
		 $html .= "<br><br>";
		 //Con xajax ubicar listado de bancos aqu?
    $html .= " <div id=\"bancos_asoc\">\n";
		$html .= "  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
		$html .= "    <tr class=\"formulacion_table_list\">\n";
    $html .= "      <td width=\"50%\">BANCO</td>\n";
    $html .= "      <td width=\"24%\">NUMERO</td>\n";
    $html .= "      <td width=\"24%\">TIPO CUENTA</td>\n";
    $html .= "      <td width=\"2%\">OP</td>\n";
		$html .= "    </tr>\n";
    $i=0;
		
		foreach($BancosProveedor as $key=> $dtl)
		{
      $html .= "                    <tr class=\"modulo_list_claro\">\n";
      $html .= "                      <td>".$dtl['descripcion']."</td>\n";
      $html .= "                      <td>".$dtl['numero_cuenta']."</td>\n";
      $html .= "                      <td>".$dtl['tipo_cuenta']."</td>\n";
      $html .= "                      <td>\n";
      $html .= "                        <a href=\"#\" title=\"INACTIVAR\">\n";
      $html .= "                          <img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\">\n";
      $html .= "                        </a>\n";
      $html .= "                      </td>\n";
      $html .= "                    </tr>\n";
		}
		$html .= "                    </table>\n";	
		$html .= "                  </div>\n";	

    $objResponse->assign("Contenido","innerHTML",$html);
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
  }
  
  
  
  /**
    * Metodo para modificar un  proveedor
    * @return string  $salida con la forma de los datos para crear un proveedor
    * @access public
   */
   
  function Modificar_pro($proveedor_id)
  {


      $consulta=new CrearSQL();
      $consulta1=new ManejoTerceros();
      $objResponse = new xajaxResponse();
      $vector=$consulta1->GetProveedor($proveedor_id);
      $path = SessionGetVar("rutaImagenes");
      //var_dump($vector);
     if(!empty($vector))
     {
      $salida .= "                  <div id='error_terco' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
      $salida .= "                   <table width=\"88%\" align=\"center\" class=\"modulo_table_list\">\n";
      $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
      $salida .= "                      <td  align=\"center\" colspan='4'>\n";
      $salida .= "                         DATOS DEL PROVEEDOR";
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                       <input type=\"hidden\" id=\"old_tipos_id\" name=\"old_tipos_id\" value=\"".$vector['tipo_id_tercero']."\">\n";
      $salida .= "                       <input type=\"hidden\" id=\"old_terco_id\" name=\"old_terco_id\" value=\"".$vector['tercero_id']."\">\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td   align=\"center\">\n";
      $salida .= "                         TIPO ID TERCERO";
      $salida .= "                       </td>\n";
      $salida .= "                       <td colspan='3' align=\"left\" >\n";
      $tipos_id_ter3=$consulta->Terceros_id();
      if(!empty($tipos_id_ter3))
      {
        $salida .= "                       <select id=\"tipos_idx39\" name=\"tipos_idx39\" class=\"select\" onchange=\"Tachar9(this.value);\">";
        for($i=0;$i<count($tipos_id_ter3);$i++)
        {
          if($vector['tipo_id_tercero']==$tipos_id_ter3[$i]['tipo_id_tercero'])
          {
            $salida .="                           <option value=\"".$tipos_id_ter3[$i]['tipo_id_tercero']."\" selected>".$tipos_id_ter3[$i]['tipo_id_tercero']."</option> \n";
          }
          else
          {
            $salida .="                           <option value=\"".$tipos_id_ter3[$i]['tipo_id_tercero']."\">".$tipos_id_ter3[$i]['tipo_id_tercero']."</option> \n";
          }
        }
        $salida .= "                       </select>\n";
      }
      $salida .= "                        &nbsp; TERCERO ID";
      $salida .= "                         <input type=\"text\" class=\"input-text\" id=\"terco_id9\" name=\"terco_id9\" maxlength=\"20\" size=\"20\" value=\"".$vector['tercero_id']."\" onkeypress=\"return acceptNum(event)\">";
      $salida .= "                        &nbsp;-&nbsp;";
      if($vector['tipo_id_tercero']=='NIT')
      {
        $salida .= "                         <input type=\"text\" class=\"input-text\" id=\"dv9\" name=\"dv9\" maxlength=\"1\" size=\"1\" value=\"".$vector['dv']."\" onkeypress=\"return acceptNum(event)\">";
      }
      else
      {
        $salida .= "                         <input type=\"text\" class=\"input-text\" id=\"dv9\" name=\"dv9\" maxlength=\"1\" size=\"1\" value=\"\" onkeypress=\"return acceptNum(event)\" disabled>";
      }

      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td align=\"center\">\n";
      $salida .= "                        NOMBRE";
      $salida .= "                       </td>\n";
      $salida .= "                       <td colspan='3'  align=\"left\">\n";
      $salida .= "                         <input type=\"text\" class=\"input-text\" id=\"nom_man9\" name=\"nom_man9\" size=\"60\" value=\"".$vector['nombre_tercero']."\" onkeypress=\"\">";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td  align=\"center\">\n";
      $salida .= "                        PAIS";
      $salida .= "                       </td>\n";
      $salida .= "                       <td colspan='3' align=\"left\">\n";
      $Pais=$consulta->Paises();
      if(!empty($Pais))
      {
        $salida .= "                       <select id=\"paisex9\" name=\"paisex9\" class=\"select\" onchange=\"Departamentos29(this.value);\">";
        $salida .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";

        for($i=0;$i<count($Pais);$i++)
        {
           if($vector['tipo_pais_id']==$Pais[$i]['tipo_pais_id'])
            {
              $salida .="                           <option value=\"".$Pais[$i]['tipo_pais_id']."\" selected>".$Pais[$i]['pais']."</option> \n";
            }
           else
            {
              $salida .="                           <option value=\"".$Pais[$i]['tipo_pais_id']."\">".$Pais[$i]['pais']."</option> \n";
            }

        }
        $salida .= "                       </select>\n";
      }
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td align=\"center\">\n";
      $salida .= "                        DEPARTAMENTO";
      $salida .= "                       </td>\n";
      $salida .= "                       <input type=\"hidden\" id=\"ban_dep9\" name=\"ban_dep9\" value=\"1\">\n";
      $salida .= "                       <input type=\"hidden\" id=\"h_departamento9\" name=\"h_departamento9\" value=\"0\">\n";
      $salida .= "                       <td colspan='3' align=\"left\" id=\"depart\">\n";
      //////////////////////////////
      $Departamentos=$consulta->DePX($vector['tipo_pais_id']);
      $path = SessionGetVar("rutaImagenes");
         if(!empty($Departamentos))
          {
              $salida .= "                       <select id=\"dptox9\" name=\"dptox9\" class=\"select\" onchange=\"Municipios19(document.getElementById('paisex9').value,document.getElementById('dptox9').value);\">";
              $salida .="                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
              for($i=0;$i<count($Departamentos);$i++)
                {
                  if($vector['tipo_dpto_id']==$Departamentos[$i]['tipo_dpto_id'])
                  {
                     $salida .= "         <option value=\"".$Departamentos[$i]['tipo_dpto_id']."\" selected>".$Departamentos[$i]['departamento']."</option> \n";

                  }
                  else
                  {
                     $salida .= "         <option value=\"".$Departamentos[$i]['tipo_dpto_id']."\">".$Departamentos[$i]['departamento']."</option> \n";

                  }


                }
              $salida .= "                           <option value=\""."otro"."\">OTRO</option> \n";
           }
              $salida .= "                       </select>\n";


      //////////////////////////////
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td align=\"center\">\n";
      $salida .= "                        MUNICIPIO";
      $salida .= "                       </td>\n";
      $salida .= "                       <input type=\"hidden\" id=\"ban_mun9\" name=\"ban_mun9\" value=\"1\">\n";
      $salida .= "                       <input type=\"hidden\" id=\"h_municipio9\" name=\"h_municipio9\" value=\"0\">\n";
      $salida .= "                       <td colspan='3' align=\"left\" id=\"muni\">\n";
      ////////////////////////////////////////////////
      $Municipios=$consulta->DeMX($vector['tipo_pais_id'],$vector['tipo_dpto_id']);

          if(!empty($Municipios))
          {
              $salida .= "                       <select id=\"mpios9\" name=\"mpios9\" class=\"select\" onchange=\"Municipio3(this.value);Exam1();\">";//
              $salida .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
              for($i=0;$i<count($Municipios);$i++)
                {
                  if($vector['tipo_mpio_id']==$Municipios[$i]['tipo_mpio_id'])
                  {
                    $salida .= "                           <option value=\"".$Municipios[$i]['tipo_mpio_id']."\" selected>".$Municipios[$i]['municipio']."</option> \n";
                  }
                  else
                  {
                    $salida .= "                           <option value=\"".$Municipios[$i]['tipo_mpio_id']."\">".$Municipios[$i]['municipio']."</option> \n";
                  }

                }
              $salida .= "                           <option value=\""."otro"."\">OTRO</option> \n";
              $salida .= "                       </select>\n";
              $salida = $objResponse->setTildes($salida);

           }

      ///////////////////////////////////////////////
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td  align=\"center\">\n";
      $salida .= "                        DIRECCION";
      $salida .= "                       </td>\n";
      $salida .= "                       <td colspan='3' align=\"left\">\n";
      $salida .= "                         <input type=\"text\" class=\"input-text\" name=\"direc9\" id=\"direc9\" maxlength=\"60\" size=\"60\" value=\"".$vector['direccion']."\" onkeypress=\"\">";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td align=\"center\">\n";
      $salida .= "                        TELEFONO";
      $salida .= "                       </td>\n";
      $salida .= "                       <td colspan='2' align=\"left\">\n";
      $salida .= "                         <input type=\"text\" class=\"input-text\" id=\"phone9\" name=\"phone9\" maxlength=\"20\" size=\"20\" value=\"".$vector['telefono']."\" onkeypress=\"return acceptNum(event)\">";
      $salida .= "                        &nbsp;";
      $salida .= "                        FAX";
      $salida .= "                        <input type=\"text\" class=\"input-text\" id=\"fax9\" name=\"fax9\" maxlength=\"20\" size=\"20\" value=\"".$vector['fax']."\" onkeypress=\"return acceptNum(event)\">";
      $salida .= "                       </td>\n";
      $salida .= "                       <td align=\"center\">\n";
      $salida .= "                        CELULAR";
      $salida .= "                         <input type=\"text\" class=\"input-text\" id=\"cel9\" name=\"cel9\" maxlength=\"14\" size=\"14\" value=\"".$vector['celular']."\" onkeypress=\"return acceptNum(event)\">";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td align=\"center\">\n";
      $salida .= "                        E-MAIL";
      $salida .= "                       </td>\n";
      $salida .= "                       <td align=\"left\">\n";
      $salida .= "                         <input type=\"text\" class=\"input-text\" id=\"e_mail9\" name=\"e_mail9\" maxlength=\"45\" size=\"45\" value=\"".$vector['email']."\" onkeypress=\"\">";
      $salida .= "                       </td>\n";
      $salida .= "                       <td colspan='2'  align=\"center\">\n";
      if($vector['sw_persona_juridica']=='1')
      {
         $salida .= "                          PERSONA NATURAL";
         $salida .= "                          <input type=\"radio\" class=\"input-text\" id=\"persona19\" name=\"persona19\" value=\"0\">\n";
         $salida .= "                          PERSONA JURIDICA";
         $salida .= "                          <input type=\"radio\" class=\"input-text\" id=\"persona29\" name=\"persona19\" value=\"1\" checked>\n";
      }
      elseif($vector['sw_persona_juridica']=='0')
      {
         $salida .= "                          PERSONA NATURAL";
         $salida .= "                          <input type=\"radio\" class=\"input-text\" id=\"persona19\" name=\"persona19\" value=\"0\" checked>\n";
         $salida .= "                          PERSONA JURIDICA";
         $salida .= "                          <input type=\"radio\" class=\"input-text\" id=\"persona29\" name=\"persona19\" value=\"1\" >\n";
      }
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    </table>";
      $salida .= "                   <table width=\"88%\" align=\"center\" class=\"modulo_table_list\">\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width='25%' align=\"left\">\n";
      $salida .= "                        DIAS DE GRACIA";
      $salida .= "                         <input type=\"text\" class=\"input-text\" name=\"dia_gra9\" id=\"dia_gra9\" maxlength=\"3\" size=\"3\" value=\"".$vector['dias_gracia']."\" onkeypress=\"\">";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width='25%'  align=\"left\">\n";
      $salida .= "                        DIAS CREDITO";
      $salida .= "                        <input type=\"text\" class=\"input-text\" name=\"dia_cre9\" id=\"dia_cre9\" maxlength=\"3\" size=\"3\" value=\"".$vector['dias_credito']."\" onkeypress=\"\">";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width='22%'  align=\"left\">\n";
      $salida .= "                        TIEMPO ENTREGA";
      $salida .= "                         <input type=\"text\" class=\"input-text\" name=\"time_e9\" id=\"time_e9\" maxlength=\"3\" size=\"3\" value=\"".$vector['tiempo_entrega']."\" onkeypress=\"\">";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width='28%' align=\"left\">\n";
      $salida .= "                        DESCUENTO CONTADO";
      $salida .= "                         &nbsp;<input type=\"text\" class=\"input-text\" name=\"des_cont9\" id=\"des_cont9\" maxlength=\"3\" size=\"3\" value=\"".$vector['descuento_por_contado']."\" onkeypress=\"\">%";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    </table>\n";
      $salida .= "                   <table width=\"88%\" align=\"center\" class=\"modulo_table_list\">\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td colspan='2' align=\"left\">\n";

      if($vector['sw_regimen_comun']=='1')
      {
         $salida .= "                         REGIMEN COMUN <input type=\"radio\" class=\"input-text\" name=\"rg\" id=\"rgc9\" value=\"1\" onkeypress=\"\" checked>";
         $salida .= "                         &nbsp;";
         $salida .= "                         REGIMEN SIMPLIFICADO <input type=\"radio\" class=\"input-text\" name=\"rg\" id=\"rgs9\" value=\"0\" onkeypress=\"\">";
      }
      elseif($vector['sw_regimen_comun']=='0')
      {
         $salida .= "                         REGIMEN COMUN <input type=\"radio\" class=\"input-text\" name=\"rg9\" id=\"rgc9\" value=\"1\" onkeypress=\"\">";
         $salida .= "                         &nbsp;";
         $salida .= "                         REGIMEN SIMPLIFICADO <input type=\"radio\" class=\"input-text\" name=\"rg9\" id=\"rgs9\" value=\"0\" onkeypress=\"\" checked>";
      }
      elseif(empty($vector['sw_regimen_comun']))
      {
         $salida .= "                         REGIMEN COMUN <input type=\"radio\" class=\"input-text\" name=\"rg9\" id=\"rgc9\" value=\"1\" onkeypress=\"\">";
         $salida .= "                         &nbsp;";
         $salida .= "                         REGIMEN SIMPLIFICADO <input type=\"radio\" class=\"input-text\" name=\"rg9\" id=\"rgs9\" value=\"0\" onkeypress=\"\">";
      }
      $salida .= "                       </td>\n";
      $salida .= "                       <td colspan='2' align=\"left\">\n";

      if($vector['sw_gran_contribuyente']=='1')
      {
          $salida .= "                         GRAN CONTRIBUYENTE &nbsp;&nbsp;&nbsp; SI<input type=\"radio\" class=\"input-text\" name=\"gc9\" id=\"gcs9\" value=\"1\" onkeypress=\"\" checked>";
          $salida .= "                         &nbsp;";
          $salida .= "                         NO <input type=\"radio\" class=\"input-text\" name=\"gc9\" id=\"gcn9\" value=\"0\" onkeypress=\"\">";

      }
      elseif($vector['sw_gran_contribuyente']=='0')
      {
         $salida .= "                         GRAN CONTRIBUYENTE &nbsp;&nbsp;&nbsp; SI<input type=\"radio\" class=\"input-text\" name=\"gc9\" id=\"gcs9\" value=\"1\" onkeypress=\"\">";
         $salida .= "                         &nbsp;";
         $salida .= "                         NO <input type=\"radio\" class=\"input-text\" name=\"gc9\" id=\"gcn9\" value=\"0\" onkeypress=\"\" checked>";
      }
      elseif(empty($vector['sw_gran_contribuyente']))
      {
          $salida .= "                         GRAN CONTRIBUYENTE &nbsp;&nbsp;&nbsp; SI<input type=\"radio\" class=\"input-text\" name=\"gc9\" id=\"gcs9\" value=\"1\" onkeypress=\"\">";
          $salida .= "                         &nbsp;";
          $salida .= "                         NO <input type=\"radio\" class=\"input-text\" name=\"gc9\" id=\"gcn9\" value=\"0\" onkeypress=\"\">";
      }

      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td colspan='2' align=\"left\">\n";
      $salida .= "                           PORCENTAJE RTF";
      $salida .= "                           <input type=\"text\" class=\"input-text\" name=\"rtf9\" id=\"rtf9\" maxlength=\"3\" size=\"3\" value=\"".$vector['porcentaje_rtf']."\" onkeypress=\"return acceptNum(event)\">%&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
      $salida .= "                             PORCENTAJE ICA";

      if($vector['tipo_pais_id']=='CO' && $vector['tipo_dpto_id']=='76' && $vector['tipo_mpio_id']=='001')
      {
        $salida .= "                           <input type=\"hidden\" name=\"ica_h9\" id=\"ica_h9\" value=\"1\">";
        $salida .= "                           <input type=\"text\" class=\"input-text\" name=\"ica9\" id=\"ica9\" maxlength=\"3\" size=\"3\" value=\"".$vector['porcentaje_ica']."\" onkeypress=\"return acceptNum(event)\">%";
      }
      else
      {
         $salida .= "                           <input type=\"hidden\" name=\"ica_h9\" id=\"ica_h9\" value=\"0\">";
         $salida .= "                           <input type=\"text\" class=\"input-text\" name=\"ica9\" id=\"ica9\" maxlength=\"3\" size=\"3\" value=\"\" onkeypress=\"return acceptNum(event)\" disabled>%";

      }
      $salida .= "                        </td>\n";
      $salida .= "                       <td colspan='2' align=\"left\">\n";
      $salida .= "                        </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td colspan='4' align=\"left\">\n";
      $salida .= "                        GRUPO DE ACTIVIDAD &nbsp;";
      $Grupo_id=$consulta->SacarGrupo($vector['actividad_id']);
      $Grupos=$consulta->ListaGruposActividades();
      if(!empty($Grupos))
      {
        $salida .= "                       <select id=\"grupos9\" name=\"grupos9\" class=\"select\" onchange=\"Actividades9(this.value);\">";
        $salida .= "                          <option value=\"0\">SELECCIONAR </option> \n";
        for($i=0;$i<count($Grupos);$i++)
        {
          if($Grupo_id[0]['grupo_id']==$Grupos[$i]['grupo_id'])
          {
            $salida .="                         <option title='".$Grupos[$i]['descripcion']."' value=\"".$Grupos[$i]['grupo_id']."\" selected>".substr($Grupos[$i]['descripcion'],0,65)."</option> \n";
          }
          else
          {
            $salida .="                         <option title='".$Grupos[$i]['descripcion']."' value=\"".$Grupos[$i]['grupo_id']."\">".substr($Grupos[$i]['descripcion'],0,65)."</option> \n";
          }

        }
        $salida .= "                       </select>\n";
      }
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                           <input type=\"hidden\" name=\"provee_id9\" id=\"provee_id9\" value=\"".$proveedor_id."\">";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td colspan='4' align=\"left\">\n";
      $salida .= "                        ACTIVIDAD &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
      /////////////////////////////////////////////////
      $actividades=$consulta->ListaActividades($Grupo_id[0]['grupo_id']);
      $salida .= "<select id=\"actividades9\" name=\"actividades9\" class=\"select\" onchange=\"\">";
        //var_dump($actividades);
        if(!empty($actividades))
        {
          $salida .= "                          <option value=\"0\">SELECCIONAR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
          for($i=0;$i<count($actividades);$i++)
          {
            if($vector['actividad_id']==$actividades[$i]['actividad_id'])
            {
              $salida.="<option value=\"".$actividades[$i]['actividad_id']."\" selected>".substr($actividades[$i]['descripcion'],0,60)."</option>\n";
            }
            else
            {
              $salida.="<option value=\"".$actividades[$i]['actividad_id']."\">".substr($actividades[$i]['descripcion'],0,60)."</option>\n";
            }
          }
        }
        else
        {
          $salida .="<option value=\"0\">SELECCIONAR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
        }
      $salida .= "</select>";
      ///////////////////////////////////////////////
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td colspan='4'  align=\"center\">\n";
      $salida .= "                         <input type=\"button\" class=\"input-submit\" onclick=\"ValidadorUltraTercero2();\" value=\"Registrar\">\n";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                 </table>\n";
      $salida = $objResponse->setTildes($salida);
      $objResponse->assign("ContenidoMod","innerHTML",$salida);
     }
     else
     {
       $objResponse->assign("errorMod","innerHTML",$resultado);
     }
     return $objResponse;

  }
    /**
    * Metodo para activar o desactivar un proveedor
    *
    * @return string  $salida con la forma de los datos para crear un proveedor
    * @access public
    */
  function switch_proveedor($estado,$primary)
  {
      $consulta=new CrearSQL();
      $objResponse = new xajaxResponse();
      $resultado=$consulta->sw_proveedor($estado,$primary);
      $path = SessionGetVar("rutaImagenes");

      if($resultado =="1" && $estado=="1")
       {
          $nuevocen = "javascript:Sw_Proveedor('0','".$primary."');";
          $salida .= "                          <a  title=\"DESHABILITAR\" class=\"label_error\" href=\"".$nuevocen."\">\n";
          $salida .= "                          <sub><img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
          $salida .= "                         </a>\n";
        }
        elseif($resultado =="1" && $estado=="0")
        {
          $nuevocen = "javascript:Sw_Proveedor('1','".$primary."');";
          $salida .= "                          <a  title=\"HABILITAR\" class=\"label_error\" href=\"".$nuevocen."\">\n";
          $salida .= "                          <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
          $salida .= "                         </a>\n";
        }
          $objResponse->assign($primary,"innerHTML",$salida);

     return $objResponse;

  }



    /**
    * Metodo para crear un tercero
    *
    * @return string  $salida con la forma de los datos para crear un tercero
    * @access public
    */
 function Departamento2($id_pais)
    {
      $consulta=new CrearSQL();
      $objResponse = new xajaxResponse();
      $Departamentos=$consulta->DePX($id_pais);
      $path = SessionGetVar("rutaImagenes");
      //$objResponse->alert("sss $id_pais");

      if($id_pais != "0")
       {
          if(!empty($Departamentos))
          {
            $salida  = "                       <select id=\"tipo_dpto_id\" name=\"tipo_dpto_id\" class=\"select\" onchange=\"Municipios1(document.crearproveedor.tipo_pais_id.value,this.value);\">";
            $salida .= "                          <option value=\"0\">----SELECCIONAR----</option> \n";
              for($i=0;$i<count($Departamentos);$i++)
                {
                  $salida .= "                           <option value=\"".$Departamentos[$i]['tipo_dpto_id']."\">".$Departamentos[$i]['departamento']."</option> \n";
                }
            $salida .= "                           <option value=\""."otro"."\">OTRO</option> \n";
            $salida .= "                       </select>\n";
            $salida = $objResponse->setTildes($salida);
            $objResponse->assign("depart","innerHTML",$salida);
            $salida1 = "                       <select id=\"mpios\" name=\"mpios\" class=\"select\" disabled>";
            $salida1 .= "                          <option value=\"0\">----SELECCIONAR----</option> \n";
            $salida1 .= "                       </select>\n";
            $objResponse->assign("muni","innerHTML",$salida1);
            $objResponse->assign("h_departamento","value","0");
            $objResponse->assign("h_municipio","value","0");
          }

       }
       else
       {
          $salida = "                       <select id=\"tipo_dpto_id\" name=\"tipo_dpto_id\" class=\"select\" disabled>";
          $salida .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
          $salida .= "                       </select>\n";
          $objResponse->assign("depart","innerHTML",$salida);
          $salida1 = "                       <select id=\"tipo_mpio_id\" name=\"tipo_mpio_id\" class=\"select\" disabled>";
          $salida1 .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
          $salida1 .= "                       </select>\n";
          $objResponse->assign("muni","innerHTML",$salida1);
          $objResponse->assign("h_departamento","value","0");
          $objResponse->assign("h_municipio","value","0");

       }

       return $objResponse;
    }

      /**
    * Metodo para crear un tercero
    *
    * @return string  $salida con la forma de los datos para crear un tercero
    * @access public
    */
 function Departamento29($id_pais)
    {
      $consulta=new CrearSQL();
      $objResponse = new xajaxResponse();
      $Departamentos=$consulta->DePX($id_pais);
      $path = SessionGetVar("rutaImagenes");
      //$objResponse->alert("sss $id_pais");

      if($id_pais != "0")
       {
         //  var_dump($Departamentos);
          if(!empty($Departamentos))
          {
              $salida = "                       <select id=\"dptox9\" name=\"dptox9\" class=\"select\" onchange=\"Municipios19(document.getElementById('paisex9').value,document.getElementById('dptox9').value);\">";
              $salida .="                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
              for($i=0;$i<count($Departamentos);$i++)
                {
                  $salida .= "                           <option value=\"".$Departamentos[$i]['tipo_dpto_id']."\">".$Departamentos[$i]['departamento']."</option> \n";
                }
              $salida .= "                           <option value=\""."otro"."\">OTRO</option> \n";
              $salida .= "                       </select>\n";
            $salida = $objResponse->setTildes($salida);
            $objResponse->assign("depart","innerHTML",$salida);
            $salida1 = "                       <select id=\"mpios9\" name=\"mpios9\" class=\"select\" disabled>";
            $salida1 .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
            $salida1 .= "                       </select>\n";
            $objResponse->assign("muni","innerHTML",$salida1);
            $objResponse->assign("h_departamento9","value","0");
            $objResponse->assign("h_municipio9","value","0");
          }
            else
            {
              //$objResponse->alert("saaa $id_pais");
              $inc="<label class=\"label_error\" style=\"text-transform: uppercase; text-align:center; font-size:10px;\">INSERTAR</label>";
              $salida = " <input type=\"text\" class=\"input-text\" id=\"dptox9\" name=\"dptox9\" size=\"30\" onkeypress=\"\" value=\"\">\n";
              $salida.=$inc;
              $salida1 = " <input type=\"text\" class=\"input-text\" id=\"mpios9\" name=\"mpios9\" size=\"30\" onkeypress=\"\" value=\"\">\n";
              $salida1.=$inc;
              $objResponse->assign("depart","innerHTML",$salida);
              $objResponse->assign("muni","innerHTML",$salida1);
              //$salida .= "                       <input type=\"hidden\" id=\"h_departamento\" name=\"h_departamento\" value=\"0\">\n";
              //$salida .= "                       <input type=\"hidden\" id=\"h_municipio\" name=\"h_municipio\" value=\"0\">\n";
              $objResponse->assign("h_departamento9","value","1");
              $objResponse->assign("h_municipio9","value","1");
            }

       }
       else
       {
          $salida = "                       <select id=\"dptox9\" name=\"dptox9\" class=\"select\" disabled>";
          $salida .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
          $salida .= "                       </select>\n";
          $objResponse->assign("depart","innerHTML",$salida);
          $salida1 = "                       <select id=\"mpios9\" name=\"mpios9\" class=\"select\" disabled>";
          $salida1 .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
          $salida1 .= "                       </select>\n";
          $objResponse->assign("muni","innerHTML",$salida1);
          $objResponse->assign("h_departamento9","value","0");
          $objResponse->assign("h_municipio9","value","0");

       }

       return $objResponse;
    }

/******************************************************************************
*MUNICIPIOS
********************************************************************************/
    function Municipios9($id_pais,$id_dpto)
    {
      $consulta=new CrearSQL();
      $objResponse = new xajaxResponse();
      $Municipios=$consulta->DeMX($id_pais,$id_dpto);
      $path = SessionGetVar("rutaImagenes");
      //$objResponse->alert("sss $id_dpto");

      if($id_dpto != "0" && $id_dpto != "otro")
       {

         //  var_dump($Departamentos);Municipio3(municipio)
          if(!empty($Municipios))
          {
              $salida = "                       <select id=\"mpios9\" name=\"mpios9\" class=\"select\" onchange=\"Municipio39(this.value);Exam1();\">";//
              $salida .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
              for($i=0;$i<count($Municipios);$i++)
                {
                  $salida .= "                           <option value=\"".$Municipios[$i]['tipo_mpio_id']."\">".$Municipios[$i]['municipio']."</option> \n";
                }

              $salida .= "                           <option value=\""."otro"."\">OTRO</option> \n";
              $salida .= "                       </select>\n";
            $salida = $objResponse->setTildes($salida);
            $objResponse->assign("muni","innerHTML",$salida);
           }
            else
            {
              $inc="<label class=\"label_error\" style=\"text-transform: uppercase; text-align:center; font-size:10px;\">INSERTAR</label>";
              $salida1 = " <input type=\"text\" class=\"input-text\" id=\"mpios9\" name=\"mpios9\" size=\"30\" onkeypress=\"\" value=\"\">\n";
              $salida1.=$inc;
              $objResponse->assign("muni","innerHTML",$salida1);
              $objResponse->assign("h_municipio9","value","1");
            }

       }
       elseif($id_dpto == "otro")
            {
              //$objResponse->alert("serasss $id_dpto");
              $inc="<label class=\"label_error\" style=\"text-transform: uppercase; text-align:center; font-size:10px;\">INSERTAR</label>";
              $salida = " <input type=\"text\" class=\"input-text\" id=\"dptox9\" name=\"dptox9\" size=\"30\" onkeypress=\"\" value=\"\">\n";
              $salida.=$inc;
              $salida1 = " <input type=\"text\" class=\"input-text\" id=\"mpios9\" name=\"mpios9\" size=\"30\" onkeypress=\"\" value=\"\">\n";
              $salida1.=$inc;
              $objResponse->assign("depart","innerHTML",$salida);
              $objResponse->assign("muni","innerHTML",$salida1);
              $objResponse->assign("h_departamento9","value","1");
              $objResponse->assign("h_municipio9","value","1");
            }
            else
            {
                $salida1 = "                       <select id=\"mpios9\" name=\"mpios9\" class=\"select\" disabled>";
                $salida1 .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
                $salida1 .= "                       </select>\n";
                $objResponse->assign("muni","innerHTML",$salida1);

            }

       return $objResponse;
    }


/******************************************************************************
*MUNICIPIOS
********************************************************************************/
    function Municipios($id_pais,$id_dpto)
    {
      $consulta=new CrearSQL();
      $objResponse = new xajaxResponse();
      $Municipios=$consulta->DeMX($id_pais,$id_dpto);
      $path = SessionGetVar("rutaImagenes");
      //$objResponse->alert("sss $id_dpto");

      if($id_dpto != "0")
      {
          if(!empty($Municipios))
          {
            $salida = "                       <select id=\"tipo_mpio_id\" name=\"tipo_mpio_id\" class=\"select\" onchange=\"Municipio3(this.value);Exam();\">";//
            $salida .= "                          <option value=\"0\">----SELECCIONAR----</option> \n";
            for($i=0;$i<count($Municipios);$i++)
            {
              $salida .= "                           <option value=\"".$Municipios[$i]['tipo_mpio_id']."\">".$Municipios[$i]['municipio']."</option> \n";
            }
            $salida .= "                       </select>\n";
            $salida = $objResponse->setTildes($salida);
            $objResponse->assign("muni","innerHTML",$salida);
          }
      }
      else
      {
        $salida1 = "                       <select id=\"tipo_mpio_id\" name=\"tipo_mpio_id\" class=\"select\" disabled>";
        $salida1 .= "                          <option value=\"0\">----SELECCIONAR----</option> \n";
        $salida1 .= "                       </select>\n";
        $objResponse->assign("muni","innerHTML",$salida1);
      }
      return $objResponse;
    }

 /*********************************************************************************
 *FUNCION PARA GUARDAR PERSONAS
 **********************************************************************************/
 function GuardarPersona($tipo_identificacion,
                         $id_tercero,
                         $nombre,
                         $pais,
                         $departamento,
                         $municipio,
                         $direccion,
                         $telefono,
                         $faz,
                         $email,
                         $celular,
                         $perjur,
                         $dv)
 {
      $path = SessionGetVar("rutaImagenes");
      $objResponse = new xajaxResponse();
      $consulta=new CrearSQL();
      //$objResponse->alert("Hoddla ");
      $REGISTRAR=$consulta->GuardarPersonas($tipo_identificacion,
                                            $id_tercero,
                                            strtoupper($nombre),
                                            $pais,
                                            $departamento,
                                            $municipio,
                                            $direccion,
                                            $telefono,
                                            $faz,
                                            $email,
                                            $celular,
                                            $perjur,
                                            $dv);

       if($REGISTRAR=="EXITO")
        {
             $objResponse->assign("error_ter","innerHTML","USUARIO REGISTRADO EXITOSAMENTE");
              $objResponse->call("CerrarTrocha");
             /*$Tercero=$consulta->Nombres($tipo_identificacion,$id_tercero);
            if(!empty($Tercero))
            {
              $tercero_tipo_id=$Tercero[0]['tipo_id_tercero'];
              $tercero_id=$Tercero[0]['tercero_id'];
              $tercero_ids=$Tercero[0]['tipo_id_tercero']."-".$Tercero[0]['tercero_id'];
              $tercero_nombre=$Tercero[0]['nombre_tercero'];
              //$objResponse->alert("Hola1 $tercero_id");
              $objResponse->assign("nom_terc","value",$tercero_id);
              //$objResponse->alert("Hola2 $tercero_id");
              $objResponse->assign("tercerito_tip","value",$tercero_tipo_id);
              $objResponse->assign("tercerito","value",$tercero_id);
              $objResponse->assign("id_tercerox","value",$tercero_id);
              $objResponse->assign("td_terceros_nue_mov","innerHTML",$tercero_nombre);
              $objResponse->assign("ter_id_nuedoc","value",$tercero_ids);
              $objResponse->assign("ter_nom_nue_doc","value",$tercero_nombre);
              $objResponse->assign("nombre_tercero","innerHTML",$tercero_nombre);

              $objResponse->assign("tipo_id_tercero_sel","value",$tercero_tipo_id);
              $objResponse->assign("id_tercero_sel","value",$tercero_id);
              $objResponse->assign("nombre_tercero_sel","value",$tercero_nombre);
            }

            $TiposTercerosId=$consulta->Terceros_id();
            $salida ="<select name=\"tipox_id\" id=\"tipox_id\" class=\"select\" onchange=\"\">";
            for($i=0;$i<count($TiposTercerosId);$i++)
            {
                if($TiposTercerosId[$i]['tipo_id_tercero']==$tipo_identificacion)
                {
                  $salida .="                           <option value=\"".$TiposTercerosId[$i]['tipo_id_tercero']."\" selected>".$TiposTercerosId[$i]['tipo_id_tercero']."</option> \n";
                }
                else
                {
                $salida .="                           <option value=\"".$TiposTercerosId[$i]['tipo_id_tercero']."\">".$TiposTercerosId[$i]['tipo_id_tercero']."</option> \n";
                }
            }
            $salida .="                         </select>\n";
            $objResponse->assign("tercero_identic","innerHTML",$salida);
            $objResponse->assign("tipos_ids_terceroxa","innerHTML",$salida);*/

        }
        else
        {
          $objResponse->assign("error_terco","innerHTML",$REGISTRAR);
        }

    return $objResponse;
 }

 function UpProveedor($tipo_identificacion,$id_tercero,$new_tipo_identificacion,$new_id_tercero,$nombre,$pais,$dptox,$mpios,$direccion,$telefono,$fax,$email,$celular,$perjur,$dv,$dg,$dc,$te,$dxc,$sw_regimen_comun,$sw_gran_contribuyente,$actividad_id,$porcentaje_rtf,$porcentaje_ica,$proveedor_id)
 {

    $llave['tipo_id_tercero']=$tipo_identificacion;
    $llave['tercero_id']=$id_tercero;
    $llave1['codigo_proveedor_id']=$proveedor_id;
    $datos['tipo_id_tercero']=$new_tipo_identificacion;
    $datos['tercero_id']=$new_id_tercero;
    $datos['nombre_tercero']=strtoupper($nombre);
    $datos['tipo_pais_id']=$pais;
    $datos['tipo_dpto_id']=$dptox;
    $datos['tipo_mpio_id']=$mpios;
    $datos['direccion']=$direccion;
    $datos['telefono']=$telefono;
    $datos['fax']=$fax;
    $datos['email']=$email;
    $datos['celular']=$celular;
    $datos1['dias_gracia']=$dg;
    $datos1['dias_credito']=$dc;
    $datos1['tiempo_entrega']=$te;
    $datos1['descuento_por_contado']=$dxc;
    $datos['sw_persona_juridica']=$perjur;
    $datos['dv']=$dv;
    $datos1['sw_regimen_comun']=$sw_regimen_comun;
    $datos1['sw_gran_contribuyente']=$sw_gran_contribuyente;
    $datos1['actividad_id']=$actividad_id;
    $datos1['porcentaje_rtf']=$porcentaje_rtf;
    $datos1['porcentaje_ica']=$porcentaje_ica;




    $path = SessionGetVar("rutaImagenes");
    $objResponse = new xajaxResponse();
    $consulta=new CrearSQL();
    $consulta1= new ManejoTerceros();

    $flecha=$consulta1->GetProveedor($proveedor_id);
    //$objResponse->alert("Hoddla $direccion");
    $REGISTRAR=$consulta1->UpdateTercero($llave,$datos);
    $REGISTRAR1=$consulta1->UpdateProveedor($llave1,$datos1);
       if($REGISTRAR=="1" && $REGISTRAR1=="1")
        {
          $objResponse->assign("error_ter","innerHTML","PROVEEDOR ACTUALIZADO EXITOSAMENTE");
          $objResponse->call("CerrarTrocha1");

                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                        ".$datos['tipo_id_tercero']."";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 if($datos['tipo_id_tercero']=='NIT')
                 {
                   $salida .= "                       ".$datos['tercero_id']."-".$datos['dv'];
                 }
                 else
                 {
                   $salida .= "                       ".$datos['tercero_id']."";
                 }
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                        <a title='".$datos['nombre_tercero']."'>";
                 $salida .= "                         ".substr($datos['nombre_tercero'],0,27)."";
                 $salida .= "                        </a>";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                        <a title='".$datos['direccion']."'>";
                 $salida .= "                      ".substr($datos['direccion'],0,20)."";
                 $salida .= "                        </a>";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                        <a title='".$datos['telefono']."'>";
                 $salida .= "                        ".substr($datos['telefono'],0,16)."";
                 $salida .= "                        </a>";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                        <a title='".$datos1['dias_gracia']."'>";
                 $salida .= "                          ".$datos1['dias_gracia']."";
                 $salida .= "                        </a>";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                        <a title='".$datos1['dias_credito']."'>";
                 $salida .= "                 ".$datos1['dias_credito']."";
                 $salida .= "                        </a>";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                        <a title='".$datos1['tiempo_entrega']."'>";
                 $salida .= "                 ".$datos1['tiempo_entrega']."";
                 $salida .= "                        </a>";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                       ".$datos1['descuento_por_contado']."";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 if($datos1['sw_regimen_comun']=='1')
                  {
                  $salida .= "                         <a>\n";
                  $salida .= "                          <sub><img src=\"".$path."/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
                  $salida .= "                         <a>\n";

                  }
                  elseif($datos1['sw_regimen_comun']=='0')
                  {
                    $salida .= "                         <a>\n";
                    $salida .= "                          <sub><img src=\"".$path."/images/delete.gif\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
                    $salida .= "                         <a>\n";
                  }
                 $salida .= "                     </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 if($datos1['sw_gran_contribuyente']=='1')
                  {
                  $salida .= "                         <a>\n";
                  $salida .= "                          <sub><img src=\"".$path."/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
                  $salida .= "                         <a>\n";

                  }
                  elseif($datos1['sw_gran_contribuyente']=='0')
                  {
                    $salida .= "                         <a>\n";
                    $salida .= "                          <sub><img src=\"".$path."/images/delete.gif\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
                    $salida .= "                         <a>\n";
                  }
                 $salida .= "                     </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                       ".$datos1['actividad_id']."";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                       ".$datos1['porcentaje_rtf']."";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                       ".$datos1['porcentaje_ica']."";
                 $salida .= "                      </td>\n";

                 $salida .= "                      <td id='".$datos1['codigo_proveedor_id']."' align=\"left\" class=\"normal_10AN\">\n";
                 if($flecha['estado']=='1')
                 {
                   $nuevocen ="javascript:Sw_Proveedor('0','".$datos1['codigo_proveedor_id']."');";
                   $salida .= "                          <a  title=\"DESHABILITAR\" class=\"label_error\" href=\"".$nuevocen."\">\n";
                   $salida .= "                          <sub><img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                   $salida .= "                         </a>\n";
                 }
                 elseif($flecha['estado']=='0')
                 {
                   $nuevocen = "javascript:Sw_Proveedor('1','".$proveedor_id."');";
                   $salida .= "                          <a  title=\"HABILITAR\" class=\"label_error\" href=\"".$nuevocen."\">\n";
                   $salida .= "                          <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                   $salida .= "                         </a>\n";
                 }

                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $nuevocen ="javascript:MostrarCapa('ContenedorMod');Mod_Proveedor('".$proveedor_id."');IniciarMod('MODIFICAR PROVEEDOR');";
                 $salida .= "                          <a  title=\"MODIFICAR PROVEEDOR\" class=\"label_error\" href=\"".$nuevocen."\">\n";
                 $salida .= "                          <sub><img src=\"".$path."/images/edita.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                 $salida .= "                         </a>\n";
                 $salida .= "                      </td>\n";
                 $tr="tr".$proveedor_id;
                 $objResponse->assign($tr,"innerHTML",$salida);
        }
        else
        {
          $objResponse->assign("error_terco","innerHTML",$REGISTRAR);
        }

   return $objResponse;
 }
 /*********************************************************************************
 *FUNCION PARA GUARDAR PROVEEDOR
 **********************************************************************************/
 //function GuardarProveedor($tipo_identificacion,$id_tercero,$nombre,$pais,$dptox,$mpios,$direccion,$telefono,$fax,$email,$celular,$perjur,$dv,$dg,$dc,$te,$dxc,$sw_regimen_comun,$sw_gran_contribuyente,$actividad_id,$porcentaje_rtf,$porcentaje_ica)
 function GuardarProveedor($datos)
 {
    $path = SessionGetVar("rutaImagenes");
    $objResponse = new xajaxResponse();
    $consulta=new CrearSQL();
    $consulta1= new ManejoTerceros();
    //$objResponse->alert("Hoddla $direccion");
    $REGISTRAR=$consulta1->NewTerceroProveedor($datos);
       if($REGISTRAR=="1")
        {
           $objResponse->assign("error_ter","innerHTML","PROVEEDOR REGISTRADO EXITOSAMENTE");
           $objResponse->call("CerrarTrocha");
        }
        else
        {
          $cad= "ERRORES : " . $consulta1->Err() . "<br>" . $consulta1->ErrMsg() . "<br>";
          $objResponse->assign("error_terco","innerHTML",$cad);
        }
   return $objResponse;
 }


 function Guardar_DYM2($vienen,$id_pais,$departamentox,$Municipio)
{
     $consulta=new CrearSQL();
     $objResponse = new xajaxResponse();
     //$objResponse->alert("VIENEN $vienen");
     if($vienen==2)
     {
          $revisar=$consulta->Consultadpto($departamentox);

          if(empty($revisar))
          {
            $departamentox=strtoupper($departamentox);
            $Municipio=strtoupper($Municipio);
            $GuardarD=$consulta->GXD($id_pais,UTF8_DECODE($departamentox));

            $GuardarM=$consulta->GXM($id_pais,$GuardarD,UTF8_DECODE($Municipio));

            $LISTO="YA ESTAN".$GuardarD."Y".$GuardarM;

            //$objResponse->alert("r $LISTO");

            $objResponse->assign("dptox","value",$GuardarD);

            $objResponse->assign("mpios","value",$GuardarM);

            $objResponse->assign("ban_dep","value","1");

            $objResponse->assign("ban_mun","value","1");
          }
          elseif(Is_array($revisar))
          {
            $GuardarD=$revisar[0]['tipo_dpto_id'];
            //var_dump($revisar);
            $LISTO="YA ESTA REPETIDO DEPATAMENTO".$GuardarD;

            //$objResponse->alert("r $LISTO");

            $revisar=$consulta->Consultampio($id_pais,$GuardarD,$Municipio);

            if(empty($revisar))
            {
               $Municipio=strtoupper($Municipio);
               $GuardarM=$consulta->GXM($id_pais,$GuardarD,UTF8_DECODE($Municipio));

            }
            elseif(Is_array($revisar))
            {
                $GuardarM=$revisar[0]['tipo_mpio_id'];

                $toca="municipio ya existe".$GuardarM;

                //$objResponse->alert("r $toca");

            }


            $objResponse->assign("dptox","value",$GuardarD);

            $objResponse->assign("mpios","value",$GuardarM);

            $objResponse->assign("ban_dep","value","1");

            $objResponse->assign("ban_mun","value","1");

          }


     }
     elseif($vienen==1)
     {
          $revisar=$consulta->Consultampio($id_pais,$departamentox,$Municipio);
          //var_dump($revisar);
          if(empty($revisar))
          {
            $Municipio=strtoupper($Municipio);
            $GuardarM=$consulta->GXM($id_pais,$departamentox,UTF8_DECODE($Municipio));

            $LISTO="MUNICIPIO GRABDO".$GuardarM;

            //$objResponse->alert("r $LISTO");

          }
          elseif(Is_array($revisar))
          {
            $GuardarM=$revisar[0]['tipo_mpio_id'];

            $toca="municipio ya existe".$GuardarM;

            //$objResponse->alert("r $toca");

          }

            $objResponse->assign("mpios","value",$GuardarM);

            $objResponse->assign("ban_dep","value","1");

            $objResponse->assign("ban_mun","value","1");



     }


     $objResponse->call("Guardarzeta");
     return $objResponse;

}





 function Guardar_DYM1($vienen,$id_pais,$departamentox,$Municipio)
{
     $consulta=new CrearSQL();
     $objResponse = new xajaxResponse();
     //$objResponse->alert("VIENEN $vienen");
     if($vienen==2)
     {
          $revisar=$consulta->Consultadpto($departamentox);

          if(empty($revisar))
          {
            $departamentox=strtoupper($departamentox);
            $Municipio=strtoupper($Municipio);
            $GuardarD=$consulta->GXD($id_pais,UTF8_DECODE($departamentox));

            $GuardarM=$consulta->GXM($id_pais,$GuardarD,UTF8_DECODE($Municipio));

            $LISTO="YA ESTAN".$GuardarD."Y".$GuardarM;

            //$objResponse->alert("r $LISTO");

            $objResponse->assign("dptox","value",$GuardarD);

            $objResponse->assign("mpios","value",$GuardarM);

            $objResponse->assign("ban_dep","value","1");

            $objResponse->assign("ban_mun","value","1");
          }
          elseif(Is_array($revisar))
          {
            $GuardarD=$revisar[0]['tipo_dpto_id'];
            //var_dump($revisar);
            $LISTO="YA ESTA REPETIDO DEPATAMENTO".$GuardarD;

            //$objResponse->alert("r $LISTO");

            $revisar=$consulta->Consultampio($id_pais,$GuardarD,$Municipio);

            if(empty($revisar))
            {
               $Municipio=strtoupper($Municipio);
               $GuardarM=$consulta->GXM($id_pais,$GuardarD,UTF8_DECODE($Municipio));

            }
            elseif(Is_array($revisar))
            {
                $GuardarM=$revisar[0]['tipo_mpio_id'];

                $toca="municipio ya existe".$GuardarM;

                //$objResponse->alert("r $toca");

            }


            $objResponse->assign("dptox","value",$GuardarD);

            $objResponse->assign("mpios","value",$GuardarM);

            $objResponse->assign("ban_dep","value","1");

            $objResponse->assign("ban_mun","value","1");

          }


     }
     elseif($vienen==1)
     {
          $revisar=$consulta->Consultampio($id_pais,$departamentox,$Municipio);
          //var_dump($revisar);
          if(empty($revisar))
          {
            $Municipio=strtoupper($Municipio);
            $GuardarM=$consulta->GXM($id_pais,$departamentox,UTF8_DECODE($Municipio));

            $LISTO="MUNICIPIO GRABDO".$GuardarM;

            //$objResponse->alert("r $LISTO");

          }
          elseif(Is_array($revisar))
          {
            $GuardarM=$revisar[0]['tipo_mpio_id'];

            $toca="municipio ya existe".$GuardarM;

            //$objResponse->alert("r $toca");

          }

            $objResponse->assign("mpios","value",$GuardarM);

            $objResponse->assign("ban_dep","value","1");

            $objResponse->assign("ban_mun","value","1");



     }


     $objResponse->call("Guardarbeta");

     return $objResponse;

}


function Guardar_DYM($vienen,$id_pais,$departamentox,$Municipio)
{
     $consulta=new CrearSQL();
     $objResponse = new xajaxResponse();
     //$objResponse->alert("VIENEN $vienen");
     if($vienen==2)
     {
          $revisar=$consulta->Consultadpto($departamentox);

          if(empty($revisar))
          {
            $departamentox=strtoupper($departamentox);
            $Municipio=strtoupper($Municipio);
            $GuardarD=$consulta->GXD($id_pais,UTF8_DECODE($departamentox));

            $GuardarM=$consulta->GXM($id_pais,$GuardarD,UTF8_DECODE($Municipio));

            $LISTO="YA ESTAN".$GuardarD."Y".$GuardarM;

            //$objResponse->alert("r $LISTO");

            $objResponse->assign("dptox","value",$GuardarD);

            $objResponse->assign("mpios","value",$GuardarM);

            $objResponse->assign("ban_dep","value","1");

            $objResponse->assign("ban_mun","value","1");
          }
          elseif(Is_array($revisar))
          {
            $GuardarD=$revisar[0]['tipo_dpto_id'];
            //var_dump($revisar);
            $LISTO="YA ESTA REPETIDO DEPATAMENTO".$GuardarD;

            //$objResponse->alert("r $LISTO");

            $revisar=$consulta->Consultampio($id_pais,$GuardarD,$Municipio);

            if(empty($revisar))
            {
               $Municipio=strtoupper($Municipio);
               $GuardarM=$consulta->GXM($id_pais,$GuardarD,UTF8_DECODE($Municipio));

            }
            elseif(Is_array($revisar))
            {
                $GuardarM=$revisar[0]['tipo_mpio_id'];

                $toca="municipio ya existe".$GuardarM;

                //$objResponse->alert("r $toca");

            }


            $objResponse->assign("dptox","value",$GuardarD);

            $objResponse->assign("mpios","value",$GuardarM);

            $objResponse->assign("ban_dep","value","1");

            $objResponse->assign("ban_mun","value","1");

          }


     }
     elseif($vienen==1)
     {
          $revisar=$consulta->Consultampio($id_pais,$departamentox,$Municipio);
          //var_dump($revisar);
          if(empty($revisar))
          {
            $Municipio=strtoupper($Municipio);
            $GuardarM=$consulta->GXM($id_pais,$departamentox,UTF8_DECODE($Municipio));

            $LISTO="MUNICIPIO GRABDO".$GuardarM;

            //$objResponse->alert("r $LISTO");

          }
          elseif(Is_array($revisar))
          {
            $GuardarM=$revisar[0]['tipo_mpio_id'];

            $toca="municipio ya existe".$GuardarM;

            //$objResponse->alert("r $toca");

          }

            $objResponse->assign("mpios","value",$GuardarM);

            $objResponse->assign("ban_dep","value","1");

            $objResponse->assign("ban_mun","value","1");



     }


     $objResponse->call("Guardaralfa");

     return $objResponse;

}
 /**
    * Metodo para crear un tercero
    *
    * @return string  $salida con la forma de los datos para crear un tercero
    * @access public
    */
 function CrearUSA()
 {

      $path = SessionGetVar("rutaImagenes");
      $objResponse = new xajaxResponse();
      $consulta=new CrearSQL();
      $salida  = "                <div id=\"ventana_terceros\">\n";
      $salida .= "                 <form name=\"formcreausu\">\n";
      $salida .= "                  <div id='error_terco' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
      $salida .= "                   <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";
      $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
      $salida .= "                      <td  align=\"center\" colspan='2'>\n";
      $salida .= "                         CREAR TERCERO";
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        TIPO ID TERCERO";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"70%\" align=\"left\" >\n";
      $tipos_id_ter3=$consulta->Terceros_id();
                if(!empty($tipos_id_ter3))
                {
                  $salida .= "                       <select id=\"tipos_idx3\" name=\"tipos_idx3\" class=\"select\" onchange=\"Tachar(this.value);\">";


                  for($i=0;$i<count($tipos_id_ter3);$i++)
                  {
                    $salida .="                           <option value=\"".$tipos_id_ter3[$i]['tipo_id_tercero']."\">".$tipos_id_ter3[$i]['tipo_id_tercero']."</option> \n";
                  }
                  $salida .= "                       </select>\n";
                }
      $salida .= "                        &nbsp; TERCERO ID";
      $salida .= "                         <input type=\"text\" class=\"input-text\" id=\"terco_id\" name=\"terco_id\" maxlength=\"20\" size=\"20\" value=\"\" onkeypress=\"return acceptNum(event)\">";
      $salida .= "                        &nbsp;-&nbsp;";
      $salida .= "                         <input type=\"text\" class=\"input-text\" id=\"dv\" name=\"dv\" maxlength=\"1\" size=\"1\" value=\"\" onkeypress=\"return acceptNum(event)\">";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        NOMBRE";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
      $salida .= "                         <input type=\"text\" class=\"input-text\" id=\"nom_man\" name=\"nom_man\" size=\"50\" value=\"\" onkeypress=\"\">";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        PAIS";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
      $Pais=$consulta->Paises();

                if(!empty($Pais))
                {
                  $salida .= "                       <select id=\"paisex\" name=\"paisex\" class=\"select\" onchange=\"Departamentos2(this.value);\">";
                  $salida .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";

                  for($i=0;$i<count($Pais);$i++)
                  {
                    $salida .="                           <option value=\"".$Pais[$i]['tipo_pais_id']."\">".$Pais[$i]['pais']."</option> \n";
                  }
                  $salida .= "                       </select>\n";
                }
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        DEPARTAMENTO";
      $salida .= "                       </td>\n";
      $salida .= "                       <input type=\"hidden\" id=\"ban_dep\" name=\"ban_dep\" value=\"0\">\n";
      $salida .= "                       <input type=\"hidden\" id=\"h_departamento\" name=\"h_departamento\" value=\"0\">\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\" id=\"depart\">\n";
      $salida .= "                       <select id=\"dptox\" name=\"dptox\" class=\"select\" disabled>";
      $salida .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
      $salida .= "                       </select>\n";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        MUNICIPIO";
      $salida .= "                       </td>\n";
      $salida .= "                       <input type=\"hidden\" id=\"ban_mun\" name=\"ban_mun\" value=\"0\">\n";
      $salida .= "                       <input type=\"hidden\" id=\"h_municipio\" name=\"h_municipio\" value=\"0\">\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\" id=\"muni\">\n";
      $salida .= "                       <select id=\"mpios\" name=\"mpios\" class=\"select\" disabled>";
      $salida .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
      $salida .= "                       </select>\n";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        DIRECCION";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
      $salida .= "                         <input type=\"text\" class=\"input-text\" name=\"direc\" id=\"direc\" maxlength=\"50\" size=\"50\" value=\"\" onkeypress=\"\">";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        TELEFONO";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
      $salida .= "                         <input type=\"text\" class=\"input-text\" id=\"phone\" name=\"phone\" maxlength=\"30\" size=\"30\" value=\"\" onkeypress=\"return acceptNum(event)\">";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        FAX";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
      $salida .= "                         <input type=\"text\" class=\"input-text\" id=\"fax\" name=\"fax\" maxlength=\"15\" size=\"30\" value=\"\" onkeypress=\"return acceptNum(event)\">";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        E-MAIL";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
      $salida .= "                         <input type=\"text\" class=\"input-text\" id=\"e_mail\" name=\"e_mail\" maxlength=\"50\" size=\"50\" value=\"\" onkeypress=\"\">";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        CELULAR";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
      $salida .= "                         <input type=\"text\" class=\"input-text\" id=\"cel\" name=\"cel\" maxlength=\"15\" size=\"30\" value=\"\" onkeypress=\"return acceptNum(event)\">";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td colspan='2'  align=\"center\">\n";
      $salida .= "                          PERSONA NATURAL";
      $salida .= "                          <input type=\"radio\" class=\"input-text\" id=\"persona1\" name=\"persona1\" value=\"0\" checked>\n";
      $salida .= "                          PERSONA JURIDICA";
      $salida .= "                          <input type=\"radio\" class=\"input-text\" id=\"persona2\" name=\"persona1\" value=\"1\" >\n";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td colspan='2'  align=\"center\">\n";
      $salida .= "                         <input type=\"button\" class=\"input-submit\" onclick=\"ValidadorUltraTercero();\" value=\"Registrar\">\n";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                 </table>\n";
      $salida .= "                </form>\n";
      $salida .= "         </div>\n";
      $salida = $objResponse->setTildes($salida);
      $objResponse->assign("ContenidoCent","innerHTML",$salida);
      return $objResponse;




 }

  /**
  * Metodo para crear un proveedores
  *
  * @return string  $salida con la forma de los datos para crear un tercero
  * @access public
  */
  function CrearUSA1()
  {
      $path = SessionGetVar("rutaImagenes");
      $objResponse = new xajaxResponse();
      $consulta=new CrearSQL();
      $salida  = "  <div id=\"ventana_terceros\">\n";
      $salida .= "    <div id='error_terco' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
      $salida .= "    <table width=\"94%\" align=\"center\" class=\"modulo_table_list\">\n";
      $salida .= "      <tr class=\"modulo_table_list_title\">\n";
      $salida .= "        <td  align=\"center\" colspan='4'>CREAR PROVEEDOR</td>\n";
      $salida .= "      </tr>\n";
      $salida .= "      <tr class=\"modulo_list_claro\">\n";
      $salida .= "        <td width=\"18%\" class=\"formulacion_table_list\">IDENTIFICACION</td>\n";
      $salida .= "        <td align=\"left\" colspan=\"3\">\n";
      $salida .= "          <select id=\"tipo_id_tercero\" name=\"tipo_id_tercero\" class=\"select\" onchange=\"Tachar(this.value);\">";
      $tipos_id_ter3=$consulta->Terceros_id();
      foreach($tipos_id_ter3 as $k => $dtl)
        $salida .="                           <option value=\"".$dtl['tipo_id_tercero']."\">".$dtl['tipo_id_tercero']."</option> \n";

      $salida .= "          </select>&nbsp;&nbsp;\n";
      $salida .= "          <input type=\"text\" class=\"input-text\" id=\"tercero_id\" name=\"tercero_id\" maxlength=\"20\" size=\"20\" value=\"\" onkeypress=\"return acceptNum(event)\">";
      $salida .= "          &nbsp;-&nbsp;";
      $salida .= "          <input type=\"text\" class=\"input-text\" id=\"dv\" name=\"dv\" maxlength=\"1\" size=\"1\" value=\"\" onkeypress=\"return acceptNum(event)\">";
      $salida .= "        </td>\n";
      $salida .= "      </tr>\n";
      $salida .= "      <tr class=\"modulo_list_claro\">\n";
      $salida .= "        <td class=\"formulacion_table_list\">NOMBRE</td>\n";
      $salida .= "        <td colspan='3'  align=\"left\">\n";
      $salida .= "          <input type=\"text\" class=\"input-text\" id=\"nombre_tercero\" name=\"nombre_tercero\" size=\"60\" value=\"\" onkeypress=\"\">";
      $salida .= "        </td>\n";
      $salida .= "      </tr>\n";
      $url1 = "classes/BuscadorLocalizacion/BuscadorLocalizacion.class.php?pais=".$pais."&dept=".$dpto."&mpio=".$mpio."&forma=crearproveedor";
      $salida .= "      <tr class=\"formulacion_table_list\">\n";
      $salida .= "        <td>LOCALIZACION</td>\n";
      $salida .= "        <td class=\"modulo_list_claro\" colspan='2' >\n";
      $salida .= "          <input type=\"hidden\" name=\"pais\" >\n";
      $salida .= "          <input type=\"hidden\" name=\"tipo_pais_id\" >\n";
      $salida .= "          <input type=\"hidden\" name=\"dpto\" >\n";
      $salida .= "          <input type=\"hidden\" name=\"tipo_dpto_id\" >\n";
      $salida .= "          <input type=\"hidden\" name=\"mpio\" >\n";
      $salida .= "          <input type=\"hidden\" name=\"tipo_mpio_id\" >\n";
      $salida .= "          <label id=\"ubicacion\">".$NomPais." - ".$NomDpto." - ".$NomMpio."</label>\n";
      $salida .= "        </td>\n";
      $salida .= "        <td class=\"modulo_list_claro\" >\n";
      $salida .= "          <input type=\"button\" class=\"input-submit\" name=\"cPrecedencia\" value=\"Ubicacion\" target=\"localidad\" onclick=\"window.open('".$url1."', 'localidad', 'toolbar=no,width=500,heigth=350,resizable=no,scrollbars=yes').focus(); return false;\">\n";
      $salida .= "        </td>\n";
      $salida .= "      </tr>\n";
      $salida .= "      <tr class=\"modulo_list_claro\">\n";
      $salida .= "        <td class=\"formulacion_table_list\">DIRECCION</td>\n";
      $salida .= "        <td colspan='3' align=\"left\">\n";
      $salida .= "           <input type=\"text\" class=\"input-text\" name=\"direccion\" id=\"direccion\" maxlength=\"60\" size=\"60\" value=\"\" >";
      $salida .= "        </td>\n";
      $salida .= "      </tr>\n";
      /*
      * Nuevos Campos para el Nuevo Proyecto
      * fecha 3-septiembre-2009-> Duana
      */
      $salida .= "      <tr class=\"modulo_list_claro\">\n";
      $salida .= "        <td class=\"formulacion_table_list\">GERENTE</td>\n";
      $salida .= "        <td colspan='3' align=\"left\">\n";
      $salida .= "           <input type=\"text\" class=\"input-text\" name=\"nombre_gerente\" id=\"nombre_gerente\" maxlength=\"60\" size=\"60\" value=\"\" >";
      $salida .= "        </td>\n";
      $salida .= "      </tr>\n";
      $salida .= "      <tr class=\"modulo_list_claro\">\n";
      $salida .= "        <td class=\"formulacion_table_list\" width=\"25%\">TELEFONO GERENTE</td>\n";
      $salida .= "        <td colspan='3' align=\"left\">\n";
      $salida .= "           <input maxlength=\"15\" style=\"width:26%\" type=\"text\" class=\"input-text\" name=\"telefono_gerente\" id=\"telefono_gerente\" value=\"\" onkeypress=\"return acceptNum(event)\">";
      $salida .= "        </td>\n";
      $salida .= "      </tr>\n";
      $salida .= "      <tr class=\"modulo_list_claro\">\n";
      $salida .= "        <td class=\"formulacion_table_list\">REPRESENTANTE DE VENTAS</td>\n";
      $salida .= "        <td colspan='3' align=\"left\">\n";
      $salida .= "          <input type=\"text\" class=\"input-text\" id=\"representante_ventas\" name=\"representante_ventas\" maxlength=\"20\" style=\"width:80%\" value=\"\">";
      $salida .= "        </td>\n";
      $salida .= "      </tr>\n";
      $salida .= "      <tr class=\"modulo_list_claro\">\n";
      $salida .= "        <td class=\"formulacion_table_list\" width=\"25%\">TELEFONO REPRESENTANTE DE VENTAS</td>\n";
      $salida .= "        <td colspan='3' align=\"left\">\n";
      $salida .= "          <input maxlength=\"15\" style=\"width:26%\" type=\"text\" class=\"input-text\" id=\"telefono_representante_ventas\" name=\"telefono_representante_ventas\" value=\"\" onkeypress=\"return acceptNum(event)\">";
      $salida .= "        </td>\n";
      $salida .= "      </tr>\n";
      /*
      * Fin Nuevos Campos para el Nuevo Proyecto
      * fecha 3-septiembre-2009-> Duana
      */

      $salida .= "      <tr class=\"modulo_list_claro\">\n";
      $salida .= "        <td class=\"formulacion_table_list\">TELEFONO EMPRESA</td>\n";
      $salida .= "        <td width=\"25%\" align=\"left\">\n";
      $salida .= "          <input type=\"text\" class=\"input-text\" id=\"telefono\" name=\"telefono\" maxlength=\"20\" style=\"width:80%\" value=\"\" onkeypress=\"return acceptNum(event)\">";
      $salida .= "        </td>";
      $salida .= "        <td width=\"25%\" class=\"formulacion_table_list\">CELULAR</td>";
      $salida .= "        <td width=\"25%\" align=\"left\">\n";
      $salida .= "          <input type=\"text\" class=\"input-text\" id=\"celular\" name=\"celular\" maxlength=\"15\" style=\"width:80%\" value=\"\" onkeypress=\"return acceptNum(event)\">";
      $salida .= "        </td>\n";
      $salida .= "      </tr>\n";
      $salida .= "      <tr class=\"modulo_list_claro\">\n";
      $salida .= "        <td class=\"formulacion_table_list\">FAX</td>";
      $salida .= "        <td align=\"left\">\n";
      $salida .= "          <input type=\"text\" class=\"input-text\" id=\"fax\" name=\"fax\" maxlength=\"15\" style=\"width:80%\" value=\"\" onkeypress=\"return acceptNum(event)\">";
      $salida .= "        </td>\n";
      $salida .= "        <td class=\"formulacion_table_list\">E-MAIL</td>\n";
      $salida .= "        <td align=\"left\">\n";
      $salida .= "          <input type=\"text\" class=\"input-text\" id=\"email\" name=\"email\" maxlength=\"45\" style=\"width:80%\" value=\"\" onkeypress=\"\">";
      $salida .= "        </td>\n";
      $salida .= "      </tr>\n";
      $salida .= "      <tr class=\"modulo_list_claro\">\n";
      $salida .= "        <td colspan='2' class=\"formulacion_table_list\"> TIPO CONSTITUCION</td>\n";
      $salida .= "        <td><input type=\"radio\" class=\"input-text\" name=\"sw_persona_juridica\" value=\"0\"> PERSONA NATURAL</td>\n";
      $salida .= "        <td><input type=\"radio\" class=\"input-text\" name=\"sw_persona_juridica\" value=\"1\" > PERSONA JURIDICA</td>\n";
      $salida .= "      </tr>\n";
      $salida .= "      <tr class=\"modulo_list_claro\">\n";
      $salida .= "        <td class=\"formulacion_table_list\">DIAS DE GRACIA</td>\n";
      $salida .= "        <td>\n";
      $salida .= "          <input type=\"text\" class=\"input-text\" name=\"dias_gracia\" id=\"dias_gracia\" maxlength=\"3\" size=\"3\" value=\"0\" onkeypress=\"return acceptNum(event)\">";
      $salida .= "        </td>\n";
      $salida .= "        <td class=\"formulacion_table_list\">DIAS CREDITO</td>\n";
      $salida .= "        <td>\n";
      $salida .= "          <input type=\"text\" class=\"input-text\" name=\"dias_credito\" id=\"dias_credito\" maxlength=\"3\" size=\"3\" value=\"0\" onkeypress=\"return acceptNum(event)\">";
      $salida .= "        </td>\n";
      $salida .= "      </tr>\n";
      $salida .= "      <tr class=\"modulo_list_claro\">\n";
      $salida .= "        <td class=\"formulacion_table_list\">TIEMPO ENTREGA</td>\n";
      $salida .= "        <td>\n";
      $salida .= "          <input type=\"text\" class=\"input-text\" name=\"tiempo_entrega\" id=\"tiempo_entrega\" maxlength=\"3\" size=\"3\" value=\"0\" onkeypress=\"return acceptNum(event)\">";
      $salida .= "        </td>\n";
      $salida .= "        <td class=\"formulacion_table_list\">DESCUENTO CONTADO</td>\n";
      $salida .= "        <td>\n";
      $salida .= "          <input type=\"text\" class=\"input-text\" name=\"descuento_por_contado\" id=\"descuento_por_contado\" maxlength=\"3\" size=\"3\" value=\"0\" onkeypress=\"return acceptNum(event)\">%";
      $salida .= "        </td>\n";
      $salida .= "      </tr>\n";
      $salida .= "      <tr class=\"modulo_list_claro\">\n";
      $salida .= "        <td colspan=\"2\" class=\"formulacion_table_list\">REGIMEN</td>\n";
      $salida .= "        <td><input type=\"radio\" name=\"sw_regimen_comun\" value=\"1\" > COMUN</td>\n";
      $salida .= "        <td><input type=\"radio\" name=\"sw_regimen_comun\" value=\"0\" > SIMPLIFICADO</td>\n";
      $salida .= "      </tr>\n";
      $salida .= "      <tr class=\"modulo_list_claro\">\n";
      $salida .= "        <td colspan=\"2\" class=\"formulacion_table_list\">GRAN CONTRIBUYENTE</td>\n";
      $salida .= "        <td><input type=\"radio\" class=\"input-text\" name=\"sw_gran_contribuyente\" value=\"1\" >SI</td>\n";
      $salida .= "        <td><input type=\"radio\" class=\"input-text\" name=\"sw_gran_contribuyente\" value=\"0\">NO</td>\n";
      $salida .= "      </tr>\n";
      $salida .= "      <tr class=\"modulo_list_claro\">\n";
      $salida .= "        <td class=\"formulacion_table_list\">PORCENTAJE RTF</td>\n";
      $salida .= "        <td>\n";
      $salida .= "          <input type=\"text\" class=\"input-text\" name=\"porcentaje_rtf\" id=\"porcentaje_rtf\" maxlength=\"3\" size=\"3\" value=\"\" onkeypress=\"return acceptNum(event)\">%\n";
      $salida .= "        </td>\n";
      $salida .= "        <td class=\"formulacion_table_list\">PORCENTAJE ICA</td>\n";
      $salida .= "        <td>\n";
      $salida .= "          <input type=\"text\" class=\"input-text\" name=\"porcentaje_ica\" id=\"porcentaje_ica\" maxlength=\"3\" size=\"3\" value=\"\" onkeypress=\"return acceptNum(event)\">%";
      $salida .= "        </td>\n";
      $salida .= "      </tr>\n";
      $salida .= "      <tr class=\"modulo_list_claro\">\n";
      $salida .= "        <td class=\"formulacion_table_list\">GRUPO DE ACTIVIDAD</td>\n";
      $salida .= "        <td colspan='3' align=\"left\">\n";
      $salida .= "          <select id=\"grupos\" name=\"grupos\" class=\"select\" onchange=\"Actividades(this.value);\">";
      $salida .= "            <option value=\"0\">----SELECCIONAR----</option> \n";

      $Grupos=$consulta->ListaGruposActividades();

      if(!empty($Grupos))
      {
        for($i=0;$i<count($Grupos);$i++)
          $salida .="                           <option value=\"".$Grupos[$i]['grupo_id']."\">".substr($Grupos[$i]['descripcion'],0,65)."</option> \n";
      }
      $salida .= "          </select>\n";
      $salida .= "        </td>\n";
      $salida .= "      </tr>\n";
      $salida .= "      <tr class=\"modulo_list_claro\">\n";
      $salida .= "        <td class=\"formulacion_table_list\">ACTIVIDAD</td>";
      $salida .= "        <td colspan='3' align=\"left\">\n";
      $salida .= "          <select id=\"actividades\" name=\"actividad_id\" class=\"select\" onchange=\"\">";
      $salida .= "            <option value=\"0\">----SELECCIONAR----</option> \n";
      $salida .= "          </select>\n";
      $salida .= "        </td>\n";
      $salida .= "      </tr>\n";
      /*
      * Nuevos Campos para el Nuevo Proyecto
      * fecha 3-septiembre-2009-> Duana
      */
      $salida .= "      <tr class=\"modulo_list_claro\">\n";
      $salida .= "        <td class=\"formulacion_table_list\">PRIORIDAD DE COMPRA</td>\n";
      $salida .= "        <td><input type=\"radio\" name=\"prioridad_compra\" value=\"1\" > Alta</td>\n";
      $salida .= "        <td><input type=\"radio\" name=\"prioridad_compra\" value=\"2\" checked> Media</td>\n";
      $salida .= "        <td><input type=\"radio\" name=\"prioridad_compra\" value=\"3\" > Baja</td>\n";
      $salida .= "      </tr>\n";
	  /*
      * Fin Nuevos Campos para el Nuevo Proyecto
      * fecha 3-septiembre-2009-> Duana
      */



      $salida .= "      <tr class=\"modulo_list_claro\">\n";
      $salida .= "        <td colspan='4'  align=\"center\">\n";
      $salida .= "          <input type=\"button\" class=\"input-submit\" onclick=\"ValidadorUltraTercero1(document.crearproveedor);\" value=\"Registrar\">\n";
      $salida .= "        </td>\n";
      $salida .= "      </tr>\n";
      $salida .= "    </table>\n";
      $salida .= "  </div>\n";
      $salida = $objResponse->setTildes($salida);
      $objResponse->assign("ContenidoCent","innerHTML",$salida);
      return $objResponse;
  }


 /**
    * Metodo para extraer las actividades industriales segun el grupo escogido
    *
    * @param string  $grupo
    * @return string  $salida con la el select de actividades
    * @access public
    */
 function Actividades_sgrupo9($grupo)
 {
    $objResponse = new xajaxResponse();
    $consulta=new CrearSQL();
    $actividades=$consulta->ListaActividades($grupo);
    //var_dump($actividades);
    if(!empty($actividades))
     {
       $salida .= "                          <option value=\"0\">SELECCIONAR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
       for($i=0;$i<count($actividades);$i++)
       {
         $salida.="<option value=\"".$actividades[$i]['actividad_id']."\">".substr($actividades[$i]['descripcion'],0,60)."</option>\n";
       }
     }
     else
     {
       $salida ="<option value=\"0\">SELECCIONAR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
     }
    $objResponse->assign("actividades9","innerHTML",$salida);
    return $objResponse;
 }

 /**
    * Metodo para extraer las actividades industriales segun el grupo escogido
    *
    * @param string  $grupo
    * @return string  $salida con la el select de actividades
    * @access public
    */
 function Actividades_sgrupo($grupo)
 {
    $objResponse = new xajaxResponse();
    $consulta=new CrearSQL();
    $actividades=$consulta->ListaActividades($grupo);
    //var_dump($actividades);
    $salida .= "    <option value=\"0\">----SELECCIONAR----</option> \n";
    if(!empty($actividades))
    {
      for($i=0;$i<count($actividades);$i++)
      {
        $salida.="<option value=\"".$actividades[$i]['actividad_id']."\">".substr($actividades[$i]['descripcion'],0,60)."</option>\n";
      }
    }

    $objResponse->assign("actividades","innerHTML",$salida);
    return $objResponse;
 }

  /**
    * Metodo para colocar el menu dependiendo del tipo de busqueda
    *
    * @param integer  $tipo_de_busqueda (CRITERIO DE BUSQUEDA)
    * @return string  $salida con la forma del menu
    * @access public
    */
 function TipoBusqueda($tipo_de_busqueda)
 {
       $objResponse = new xajaxResponse();
       $consulta=new CrearSQL();

       if($tipo_de_busqueda==0)
       {
         $salida .= "           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
         $objResponse->call("Volver");
       }
       if($tipo_de_busqueda==1)
       {
          $tipos_id=$consulta->Terceros_id();
          $salida .="                          TIPO DE DOCUMENTO";
          if(!empty($tipos_id))
          {
            $salida.= "                            <select id=\"tipos_id\" name=\"tipos_id\" class=\"select\" onchange=\"Tachar(this.value);\">";
            for($i=0;$i<count($tipos_id);$i++)
            {
              $salida.="                                 <option value=\"".$tipos_id[$i]['tipo_id_tercero']."\">".$tipos_id[$i]['tipo_id_tercero']."</option> \n";
            }
            $salida.= "                             </select>\n";
          }
          $salida .= "                             &nbsp; TERCERO ID";
          $salida .= "                             <input type=\"text\" class=\"input-text\" id=\"terco_id\" name=\"terco_id\" maxlength=\"20\" size=\"20\" value=\"\" onkeypress=\"return acceptNum(event)\" onkeydown=\"recogerTecla(event);\" onclick=\"limpiar()\">";
          $salida .= "                             &nbsp; - &nbsp;";
          $salida .= "                             <input type=\"text\" class=\"input-text\" id=\"dv\" name=\"dv\" maxlength=\"1\" size=\"1\" value=\"\" onkeypress=\"return acceptNum(event)\" onkeydown=\"recogerTecla(event);\" onclick=\"limpiar()\">";
       }
       elseif($tipo_de_busqueda==2)
       {
          $salida .= "                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NOMBRE TERCERO";
          $salida .= "                             <input type=\"text\" class=\"input-text\" id=\"terco_id\" name=\"terco_id\" maxlength=\"50\" size=\"50\" value=\"\" onkeypress=\"return acceptm(event)\" onkeydown=\"recogerTecla(event);\">";
       }
       $objResponse->assign("aux","innerHTML",$salida);
       return $objResponse;
 }

  /**
    * Metodo para colocar el menu dependiendo del tipo de busqueda para provvedores
    *
    * @param integer  $tipo_de_busqueda (CRITERIO DE BUSQUEDA)
    * @return string  $salida con la forma del menu
    * @access public
    */
 function TipoBusqueda1($tipo_de_busqueda)
 {
       $objResponse = new xajaxResponse();
       $consulta=new CrearSQL();

       if($tipo_de_busqueda==0)
       {
         $salida .= "           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
         $objResponse->call("Volver");
       }
       if($tipo_de_busqueda==1)
       {
          $tipos_id=$consulta->Terceros_id();
          $salida .="                          TIPO DE DOCUMENTO";
          if(!empty($tipos_id))
          {
            $salida.= "                            <select id=\"tipos_id\" name=\"tipos_id\" class=\"select\" onchange=\"Tachar1(this.value);\">";
            for($i=0;$i<count($tipos_id);$i++)
            {
              $salida.="                                 <option value=\"".$tipos_id[$i]['tipo_id_tercero']."\">".$tipos_id[$i]['tipo_id_tercero']."</option> \n";
            }
            $salida.= "                             </select>\n";
          }
          $salida .= "                             &nbsp; TERCERO ID";
          $salida .= "                             <input type=\"text\" class=\"input-text\" id=\"terco_id1\" name=\"terco_id1\" maxlength=\"20\" size=\"20\" value=\"\" onkeypress=\"return acceptNum(event)\" onkeydown=\"recogerTeclab(event);\" onclick=\"limpiar()\">";
          $salida .= "                             &nbsp; - &nbsp;";
          $salida .= "                             <input type=\"text\" class=\"input-text\" id=\"dv1\" name=\"dv1\" maxlength=\"1\" size=\"1\" value=\"\" onkeypress=\"return acceptNum(event)\" onkeydown=\"recogerTeclab(event);\" onclick=\"limpiar()\">";
       }
       elseif($tipo_de_busqueda==2)
       {
          $salida .= "                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NOMBRE";
          $salida .= "                             <input type=\"text\" class=\"input-text\" id=\"terco_id1\" name=\"terco_id1\" maxlength=\"50\" size=\"50\" value=\"\" onkeypress=\"return acceptm(event)\" onkeydown=\"recogerTeclab(event);\">";
       }
       elseif($tipo_de_busqueda==3)
       {
          $salida .= "                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RAZON SOCIAL";
          $salida .= "                             <input type=\"text\" class=\"input-text\" id=\"terco_id1\" name=\"terco_id1\" maxlength=\"50\" size=\"50\" value=\"\" onkeypress=\"return acceptm(event)\" onkeydown=\"recogerTeclab(event);\">";
       }
       $objResponse->assign("aux","innerHTML",$salida);
       return $objResponse;
 }
 /**
    * Metodo para obtener terceros filtrados por un tipo de identificacion
    * @param integer  $tipo_de_busqueda (CRITERIO DE BUSQUEDA)
    * @param integer  $valor_de_busqueda
    * @param integer  $limite (cantidad de refistros a mostrar por pagina)
    * @param integer  $offset (pagina de la consulta)
    * @return array
    * @access public
    */
 function GetProveedores($tipo_de_busqueda,$tipo_de_busqueda_aux,$valor_de_busqueda,$limite=20,$offset=0)
 {
   $objResponse = new xajaxResponse();
   $path = SessionGetVar("rutaImagenes");
   $consulta=new ManejoTerceros();
   $consulta1=new CrearSQL();
    /*



      tipo_id_tercero
      tercero_id
       direccion
      telefono
      -fax
      -email
      -celular
      -sw_persona_juridica
      -cal_cli
      -usuario_id
      -fecha_registro
      -busca_persona
      nombre_tercero
      dv
    ----------------------------------
       tipo_id_tercero
      tercero_id
       direccion
      telefono
       estado
       dias_gracia
       dias_credito
       tiempo_entrega
       descuento_por_contado
       cupo
       sw_regimen_comun
       sw_gran_contribuyente
       actividad_id
       porcentaje_rtf
       porcentaje_ica*/
   if($tipo_de_busqueda==1 && $tipo_de_busqueda_aux!='' && $valor_de_busqueda!='')
   {
     $filtro="WHERE a.tipo_id_tercero='".$tipo_de_busqueda_aux."' AND a.tercero_id LIKE '".$valor_de_busqueda."%'
     ORDER BY tipo_id_tercero,tercero_id,nombre_tercero";
   }
   elseif($tipo_de_busqueda==1 && $tipo_de_busqueda_aux!='')
   {
     $filtro="WHERE a.tipo_id_tercero='".$tipo_de_busqueda_aux."'
     ORDER BY tipo_id_tercero,tercero_id,nombre_tercero";
   }
   elseif($tipo_de_busqueda==2 && $valor_de_busqueda!='')
   {
     $filtro="WHERE a.nombre_tercero LIKE '%".strtoupper($valor_de_busqueda)."%'
     ORDER BY tipo_id_tercero,tercero_id,nombre_tercero";
   }
   elseif($tipo_de_busqueda==2 && $valor_de_busqueda=='')
   {
     $filtro="ORDER BY nombre_tercero";
   }
   elseif($tipo_de_busqueda==3 && $valor_de_busqueda!='')
   {
     $filtro="WHERE a.nombre_tercero LIKE '%".strtoupper($valor_de_busqueda)."%'
     ORDER BY tipo_id_tercero,tercero_id,nombre_tercero";
   }
   elseif($tipo_de_busqueda==3 && $valor_de_busqueda=='')
   {
     $filtro="ORDER BY nombre_tercero";
   }
   else
   {
     $filtro="ORDER BY tipo_id_tercero,tercero_id,nombre_tercero";
   }
   //$objResponse->alert("777".$offset);
   $cuantos=$consulta1->ContarRegistrosProv($tipo_de_busqueda,$tipo_de_busqueda_aux,$valor_de_busqueda);
    if($cuantos===false)
    {
      echo "ErrorContador".$consulta1->frmError['MensajeError'];
    }
   

    $offset1=$limite*($offset-1);
    $vector=$consulta->GetTercerosProveedores($filtro,$limite,$offset1);
    //$objResponse->assign("error_ter","innerHTML",$vector);
    //var_dump($vector);
    if(!empty($vector))
    {/*$javadx1 = "javascript:OrdernarPor('".$tipo_de_busqueda."','".$tipo_de_busqueda_aux."','".$valor_de_busqueda."','tipo_id_tercero','1','".$limite."','".$offset."');";
         $javadx2 = "javascript:OrdernarPor('".$tipo_de_busqueda."','".$tipo_de_busqueda_aux."','".$valor_de_busqueda."','tercero_id','1','".$limite."','".$offset."');";
         $javadx3 = "javascript:OrdernarPor('".$tipo_de_busqueda."','".$tipo_de_busqueda_aux."','".$valor_de_busqueda."','nombre','1','".$limite."','".$offset."');";*/
//          $imagen = $path."/images/abajo.png";
//          $imagen2 = $path."/images/abajob.jpeg";
//          $imagen1 = "<sub><img src=\"".$imagen."\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
//          $imagen3 = "<sub><img src=\"".$imagen2."\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";

		/*
		* Aca es en donde se desplegaran los terceros Proveedores creados
		*/


         $salida  = "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
         $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
         $salida .= "                      <td align=\"center\" width=\"8%\">\n";
         $salida .= "                        <a title='TIPO DE DOCUMENTO'>TIPO ID </a>";//<a title='ORGANIZAR POR TIPO DOCUMENTO' href=\"".$javadx1."\">".$imagen1."</a>
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"9%\">\n";
         $salida .= "                        <a title='NUMERO DE IDENTIFICACION'>NUMERO</a>";//<a title='ORGANIZAR POR NUMERO DE DOCUMENTO' href=\"".$javadx2."\">".$imagen1."</a>
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"23%\">\n";
         $salida .= "                        <a title='NOMBRE TERCERO O RAZON SOCIAL'>NOMBRE";//<a title='ORGANIZAR POR NOMBRE DE TERCERO' href=\"".$javadx3."\">".$imagen1."</a>
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"18%\">\n";
         $salida .= "                        <a title='DIRECCION'>DIR<a>";
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"10%\">\n";
         $salida .= "                        <a title='TELEFONO'>TEL<a>";
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"4%\">\n";
         $salida .= "                        <a title='DIAS DE GRACIA'>DG<a>";
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"4%\">\n";
         $salida .= "                        <a title='DIAS CREDITO'>DC<a>";
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"4%\">\n";
         $salida .= "                        <a title='TIEMPO DE ENTREGA'>TE<a>";
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"4%\">\n";
         $salida .= "                        <a title='DESCUENTO POR CONTADO'>DxC<a>";
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"2%\">\n";
         $salida .= "                        <a title='REGIMEN COMUN'>RC<a>";
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"2%\">\n";
         $salida .= "                        <a title='GRAN CONTRIBUYENTE'>GC<a>";
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"10%\">\n";
         $salida .= "                        <a title='ACTIVIDAD'>ACTIVIDAD<a>";
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"2%\">\n";
         $salida .= "                        <a title='PORCENTAJE RENTENCION EN LA FUENTE'>RTF<a>";
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"2%\">\n";
         $salida .= "                        <a title='PORCENTAJE RENTENCION ICA'>RT_ICA<a>";
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"2%\">\n";
         $salida .= "                        <a title='SWITCH ESTADO'>SW<a>";
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"2%\">\n";
         $salida .= "                        <a title='MODIFICAR PROVEEDOR'>MOD<a>";
         $salida .= "                      </td>\n";
		 $salida .= "                      <td align=\"center\" width=\"2%\">\n";
         $salida .= "                        <a title='BANCOS - CUENTAS BANCARIAS'>BANK<a>";
         $salida .= "                      </td>\n";
         $salida .= "                    </tr>\n";

           foreach($vector as $clave => $valor)
           {
             foreach($valor as $clave => $valor)
             {
                 $salida .= "                    <tr id='tr".$valor['codigo_proveedor_id']."' class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                        ".$valor['tipo_id_tercero']."";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 if($valor['tipo_id_tercero']=='NIT')
                 {
                   $salida .= "                       ".$valor['tercero_id']."-".$valor['dv'];

                 }
                 else
                 {
                   $salida .= "                       ".$valor['tercero_id']."";
                 }
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                        <a title='".$valor['nombre_tercero']."'>";
                 $salida .= "                         ".substr($valor['nombre_tercero'],0,27)."";
                 $salida .= "                        </a>";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                        <a title='".$valor['direccion']."'>";
                 $salida .= "                      ".substr($valor['direccion'],0,20)."";
                 $salida .= "                        </a>";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                        <a title='".$valor['telefono']."'>";
                 $salida .= "                        ".substr($valor['telefono'],0,16)."";
                 $salida .= "                        </a>";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                        <a title='".$valor['dias_gracia']."'>";
                 $salida .= "                          ".$valor['dias_gracia']."";
                 $salida .= "                        </a>";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                        <a title='".$valor['dias_credito']."'>";
                 $salida .= "                 ".$valor['dias_credito']."";
                 $salida .= "                        </a>";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                        <a title='".$valor['tiempo_entrega']."'>";
                 $salida .= "                 ".$valor['tiempo_entrega']."";
                 $salida .= "                        </a>";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                       ".$valor['descuento_por_contado']."";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 if($valor['sw_regimen_comun']=='1')
                 {
                  $salida .= "                         <a>\n";
                  $salida .= "                          <sub><img src=\"".$path."/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
                  $salida .= "                         <a>\n";

                  }

                 elseif($valor['sw_regimen_comun']=='0')
                 {
                   $salida .= "                         <a>\n";
                   $salida .= "                          <sub><img src=\"".$path."/images/delete.gif\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
                   $salida .= "                         <a>\n";
                 }
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 if($valor['sw_gran_contribuyente']=='1')
                  {
                  $salida .= "                         <a>\n";
                  $salida .= "                          <sub><img src=\"".$path."/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
                  $salida .= "                         <a>\n";

                  }
                  elseif($valor['sw_gran_contribuyente']=='0')
                  {
                    $salida .= "                         <a>\n";
                    $salida .= "                          <sub><img src=\"".$path."/images/delete.gif\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
                    $salida .= "                         <a>\n";
                  }
                 $salida .= "                     </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                       ".$valor['actividad_id']."";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                       ".$valor['porcentaje_rtf']."";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                       ".$valor['porcentaje_ica']."";
                 $salida .= "                      </td>\n";

                 $salida .= "                      <td id='".$valor['codigo_proveedor_id']."' align=\"left\" class=\"normal_10AN\">\n";
                 if($valor['estado']=='1')
                 {
                   $nuevocen ="javascript:Sw_Proveedor('0','".$valor['codigo_proveedor_id']."');";
                   $salida .= "                          <a  title=\"DESHABILITAR\" class=\"label_error\" href=\"".$nuevocen."\">\n";
                   $salida .= "                          <sub><img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                   $salida .= "                         </a>\n";
                 }
                 elseif($valor['estado']=='0')
                 {
                   $nuevocen = "javascript:Sw_Proveedor('1','".$valor['codigo_proveedor_id']."');";
                   $salida .= "                          <a  title=\"HABILITAR\" class=\"label_error\" href=\"".$nuevocen."\">\n";
                   $salida .= "                          <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                   $salida .= "                         </a>\n";
                 }
                 $salida .="                      </td>\n";
                 $salida .="                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $nuevocen="javascript:MostrarCapa('ContenedorMod'); Mod_Proveedor('".$valor['codigo_proveedor_id']."');IniciarMod('MODIFICAR PROVEEDOR');";
                 $salida .="                         <a  title=\"MODIFICAR PROVEEDOR\" class=\"label_error\" href=\"".$nuevocen."\">\n";
                 $salida .="                          <sub><img src=\"".$path."/images/edita.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                 $salida .="                         </a>\n";
                 $salida .="                      </td>\n";
				 
				 /*
				 * NUEVO CAMPO PARA LA ASIGNACION DE BANCOS Y CUENTAS BANCARIAS A UN PROVEEDOR.
				 */
				 $salida .="                      <td align=\"left\" class=\"normal_10AN\">\n";
                 //$nuevocen="		javascript:MostrarCapa('ContenedorBank'); ('".$valor['codigo_proveedor_id']."');IniciarBank('BANCOS DEL PROVEEDOR');";
                 $salida .="                         <a  title=\"CUENTAS BANCARIAS\" class=\"label_error\" href=\"#\" Onclick=\"xajax_asignacion_bancos('".$valor['codigo_proveedor_id']."');\">\n";
                 $salida .="                          <sub><img src=\"".$path."/images/banco.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                 $salida .="                         </a>\n";
                 $salida .="                      </td>\n";
				 
			     $salida .="                   </tr>\n";
               }

             }
            $salida .= "                </table>\n";

            //$cuantos['count'];

            $salida .=" ".ObtenerPaginadoProv($path,$cuantos,'1',$tipo_de_busqueda,$tipo_de_busqueda_aux,$valor_de_busqueda,$limite,$offset);
            $objResponse->assign("lista_prov","innerHTML",$salida);
    }
     else
     {
         $cad= "ERRORES : " . $consulta->Err() . "<br>" . $consulta->ErrMsg() . "<br>";
         $cad.= "NO SE ENCONTRARON RESULTADOS CON ESE CRITERIO DE BUSQUEDA";
         $objResponse->assign("error_ter","innerHTML",$cad);
         $salida .= "      <table align=\"center\" width=\"50%\">\n";
         $salida .= "       <tr>\n";
         $salida .= "        <td align=\"center\" colspan='7'>\n";
         $salida .= "          <input type=\"button\" class=\"input-submit\" value=\"Volver y Mostrar Todos\" onclick=\"Volver1();\">\n";
         $salida .= "        </td>\n";
         $salida .= "       </tr>\n";
         $salida .= "      </table>\n";
         $objResponse->assign("lista_prov","innerHTML",$salida);
     }


   return $objResponse;
 }

 /**
    * Metodo para obtener terceros ORDENADOS por nombre,por numero_id,o por nombre
    *
    * @param integer  $tipo_de_busqueda (CRITERIO DE BUSQUEDA)
    * @param integer  $valor_de_busqueda
    * @param integer  $limite (cantidad de refistros a mostrar por pagina)
    * @param integer  $offset (pagina de la consulta)
    * @return array
    * @access public
    */
 function GetTercerinosOrderBy($tipo_de_busqueda,$tipo_de_busqueda_aux,$valor_de_busqueda,$campo,$orden,$limite=20,$offset=0)
 {
   $objResponse = new xajaxResponse();
   $path = SessionGetVar("rutaImagenes");
   $consulta=new ManejoTerceros();
   $consulta1=new CrearSQL();
    //$objResponse->alert();
//    $objResponse->alert($tipo_de_busqueda);
//    $objResponse->alert($limite);
//    $objResponse->alert($offset);
   if($tipo_de_busqueda==1 && $tipo_de_busqueda_aux!='' && $valor_de_busqueda!='' && $campo=='tipo_id_tercero' && $orden=='2')
   {
     $filtro="WHERE tipo_id_tercero='".$tipo_de_busqueda_aux."' AND tercero_id LIKE '".$valor_de_busqueda."%'
     ORDER BY tipo_id_tercero";
   }
   if($tipo_de_busqueda==1 && $tipo_de_busqueda_aux!='' && $valor_de_busqueda!='' && $campo=='tercero_id' && $orden=='2')
   {
     $filtro="WHERE tipo_id_tercero='".$tipo_de_busqueda_aux."' AND tercero_id LIKE '".$valor_de_busqueda."%'
     ORDER BY tipo_id_tercero,tercero_id";
   }
   if($tipo_de_busqueda==1 && $tipo_de_busqueda_aux!='' && $valor_de_busqueda!='' && $campo=='tipo_id_tercero' && $orden=='2')
   {
     $filtro="WHERE tipo_id_tercero='".$tipo_de_busqueda_aux."' AND tercero_id LIKE '".$valor_de_busqueda."%'
     ORDER BY tipo_id_tercero";
   }
   if($tipo_de_busqueda==1 && $tipo_de_busqueda_aux!='' && $valor_de_busqueda!='' && $campo=='tipo_id_tercero' && $orden=='1')
   {
     $filtro="WHERE tipo_id_tercero='".$tipo_de_busqueda_aux."' AND tercero_id LIKE '".$valor_de_busqueda."%'
     ORDER BY tipo_id_tercero DESC";
   }
   if($tipo_de_busqueda==1 && $tipo_de_busqueda_aux!='' && $valor_de_busqueda!='' && $campo=='tercero_id' && $orden=='1')
   {
     $filtro="WHERE tipo_id_tercero='".$tipo_de_busqueda_aux."' AND tercero_id LIKE '".$valor_de_busqueda."%'
     ORDER BY tercero_id DESC";
   }
   if($tipo_de_busqueda==1 && $tipo_de_busqueda_aux!='' && $valor_de_busqueda!='' && $campo=='nombre' && $orden=='1')
   {
     $filtro="WHERE tipo_id_tercero='".$tipo_de_busqueda_aux."' AND tercero_id LIKE '".$valor_de_busqueda."%'
     ORDER BY nombre_tercero DESC";
   }
   elseif($tipo_de_busqueda==1 && $tipo_de_busqueda_aux!='')
   {
     $filtro="WHERE tipo_id_tercero='".$tipo_de_busqueda_aux."'";
   }
   elseif($tipo_de_busqueda==2)
   {
     $filtro="WHERE nombre_tercero LIKE '%".strtoupper($valor_de_busqueda)."%'";
   }
   else
   {
     $filtro="";
   }
   //$objResponse->alert("777".$filtro);
   $cuantos=$consulta1->ContarRegistros($tipo_de_busqueda,$tipo_de_busqueda_aux,$valor_de_busqueda);
    if($cuantos===false)
    {
      echo $consulta1->frmError['MensajeError'];
    }
    $offset1=$limite*($offset-1);
    echo "a".$filtro;
    $vector=$consulta->GetTerceros($filtro,$limite,$offset1);
    $objResponse->assign("error_ter","innerHTML",$vector);
    if(!empty($vector))
    {
         $javadx1 = "javascript:OrdernarPor('".$tipo_de_busqueda."','".$tipo_de_busqueda_aux."','".$valor_de_busqueda."','tipo_id_tercero','2','".$limite."','".$offset."');";
         $javadx2 = "javascript:OrdernarPor('".$tipo_de_busqueda."','".$tipo_de_busqueda_aux."','".$valor_de_busqueda."','tercero_id','2','".$limite."','".$offset."');";
         $javadx3 = "javascript:OrdernarPor('".$tipo_de_busqueda."','".$tipo_de_busqueda_aux."','".$valor_de_busqueda."','nombre','2','".$limite."','".$offset."');";
         $imagen = $path."/images/abajo.png";
         $imagen2 = $path."/images/abajob.jpeg";
         $imagen1 = "<sub><img src=\"".$imagen."\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
         $imagen3 = "<sub><img src=\"".$imagen2."\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
         $salida  = "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
         $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
         $salida .= "                      <td align=\"center\" width=\"8%\">\n";
         $salida .= "                        <a title='TIPO DE DOCUMENTO'>TIPO ID </a><a title='ORGANIZAR POR TIPO DOCUMENTO' href=\"".$javadx1."\">".$imagen1."</a>";
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"10%\">\n";
         $salida .= "                        <a title='NUMERO DE IDENTIFICACION'>NUMERO</a> <a title='ORGANIZAR POR NUMERO DE DOCUMENTO' href=\"".$javadx2."\">".$imagen1."</a>";
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"23%\">\n";
         $salida .= "                        <a title='NOMBRE TERCERO'>NOMBRE<a title='ORGANIZAR POR NOMBRE DE TERCERO' href=\"".$javadx3."\">".$imagen1."</a>";
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"4%\">\n";
         $salida .= "                        <a title='PAIS'>PAIS<a>";
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"4%\">\n";
         $salida .= "                        <a title='DEPARTAMENTO ID'>DPTO<a>";
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"4%\">\n";
         $salida .= "                        <a title='MUNICIPIO ID'>MPIO<a>";
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"15%\">\n";
         $salida .= "                        <a title='DIRECCION'>DIR<a>";
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"11%\">\n";
         $salida .= "                        <a title='TELEFONO'>TEL<a>";
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"9%\">\n";
         $salida .= "                        <a title='FAX'>FAX<a>";
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"10%\">\n";
         $salida .= "                        <a title='CELULAR'>CEL<a>";
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"2%\">\n";
         $salida .= "                        <a title='PERSONA JURIDICA'>PJ<a>";
         $salida .= "                      </td>\n";
         $salida .= "                    </tr>\n";

           foreach($vector as $clave => $valor)
           {
             foreach($valor as $clave => $valor)
             {

                 $salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                        ".$valor['tipo_id_tercero']."";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                       ".$valor['tercero_id']."";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                        <a title='".$valor['nombre_tercero']."'>";
                 $salida .= "                         ".substr($valor['nombre_tercero'],0,27)."";
                 $salida .= "                        </a>";
                 $salida .= "                      </td>\n";
                 $pais=$consulta1->sacar_pais($valor['tipo_pais_id']);
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                        <a title='".$pais['pais']."'>";
                 $salida .= "                          ".$valor['tipo_pais_id']."";
                 $salida .= "                        </a>";
                 $salida .= "                      </td>\n";
                 $depar=$consulta1->sacar_depar($valor['tipo_pais_id'],$valor['tipo_dpto_id']);
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                        <a title='".$depar['departamento']."'>";
                 $salida .= "                 ".$valor['tipo_dpto_id']."";
                 $salida .= "                        </a>";
                 $salida .= "                      </td>\n";
                 $mpio=$consulta1->sacar_mpio($valor['tipo_pais_id'],$valor['tipo_dpto_id'],$valor['tipo_mpio_id']);
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                        <a title='".$mpio['municipio']."'>";
                 $salida .= "                 ".$valor['tipo_mpio_id']."";
                 $salida .= "                        </a>";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                        <a title='".$valor['direccion']."'>";
                 $salida .= "                      ".substr($valor['direccion'],0,20)."";
                 $salida .= "                        </a>";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                        <a title='".$valor['telefono']."'>";
                 $salida .= "                        ".substr($valor['telefono'],0,16)."";
                 $salida .= "                        </a>";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                       ".$valor['fax']."";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                        ".$valor['celular']."";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 if($valor['sw_persona_juridica']=='1')
                 {
                   $salida .= "                 SI";
                 }
                 elseif($valor['sw_persona_juridica']=='0')
                 {
                   $salida .= "                 NO";
                 }
                 $salida .= "                     </td>\n";
                 $salida .= "                   </tr>\n";
               }

             }
            $salida .= "                </table>\n";

            //$cuantos['count'];

            $salida .=" ".ObtenerPaginadoTer($path,$cuantos,'1',$tipo_de_busqueda,$tipo_de_busqueda_aux,$valor_de_busqueda,$limite,$offset);
            $objResponse->assign("lista_ter","innerHTML",$salida);
    }
     else
     {
         $cad= "ERRORES : " . $consulta->Err() . "<br>" . $consulta->ErrMsg() . "<br>";
         $cad= "NO SE ENCONTRARON RESULTADOS CON ESE CRITERIO DE BUSQUEDA";
         $objResponse->assign("error_ter","innerHTML",$cad);
         $salida .= "      <table align=\"center\" width=\"50%\">\n";
         $salida .= "       <tr>\n";
         $salida .= "        <td align=\"center\" colspan='7'>\n";
         $salida .= "          <input type=\"button\" class=\"input-submit\" value=\"Volver y Mostrar Todos\" onclick=\"Volver();\">\n";
         $salida .= "        </td>\n";
         $salida .= "       </tr>\n";
         $salida .= "      </table>\n";
         $objResponse->assign("lista_ter","innerHTML",$salida);
     }


   return $objResponse;
 }

   /**
    * Metodo para obtener terceros filtrados por un tipo de identificacion
    *
    * @param integer  $tipo_de_busqueda (CRITERIO DE BUSQUEDA)
    * @param integer  $valor_de_busqueda
    * @param integer  $limite (cantidad de refistros a mostrar por pagina)
    * @param integer  $offset (pagina de la consulta)
    * @return array
    * @access public
    */
 function GetTercerinos($tipo_de_busqueda,$tipo_de_busqueda_aux,$valor_de_busqueda,$limite=20,$offset=0)
 {
   $objResponse = new xajaxResponse();
   $path = SessionGetVar("rutaImagenes");
   $consulta=new ManejoTerceros();
   $consulta1=new CrearSQL();
    //$objResponse->alert();
//    $objResponse->alert($tipo_de_busqueda);
//    $objResponse->alert($limite);
//    $objResponse->alert($offset);
   if($tipo_de_busqueda==1 && $tipo_de_busqueda_aux!='' && $valor_de_busqueda!='')
   {
     $filtro="WHERE tipo_id_tercero='".$tipo_de_busqueda_aux."' AND tercero_id LIKE '".$valor_de_busqueda."%'
     ORDER BY tipo_id_tercero,tercero_id,nombre_tercero";
   }
   elseif($tipo_de_busqueda==1 && $tipo_de_busqueda_aux!='')
   {
     $filtro="WHERE tipo_id_tercero='".$tipo_de_busqueda_aux."'
     ORDER BY tipo_id_tercero,tercero_id,nombre_tercero";
   }
   elseif($tipo_de_busqueda==2)
   {
     $filtro="WHERE nombre_tercero LIKE '%".strtoupper($valor_de_busqueda)."%'
     ORDER BY tipo_id_tercero,tercero_id,nombre_tercero";
   }
   else
   {
     $filtro="";
   }
   //$objResponse->alert("777".$filtro);
   $cuantos=$consulta1->ContarRegistros($tipo_de_busqueda,$tipo_de_busqueda_aux,$valor_de_busqueda);
    if($cuantos===false)
    {
      echo $consulta1->frmError['MensajeError'];
    }
    $offset1=$limite*($offset-1);
    $vector=$consulta->GetTerceros($filtro,$limite,$offset1);
    if(!empty($vector))
    {/*$javadx1 = "javascript:OrdernarPor('".$tipo_de_busqueda."','".$tipo_de_busqueda_aux."','".$valor_de_busqueda."','tipo_id_tercero','1','".$limite."','".$offset."');";
         $javadx2 = "javascript:OrdernarPor('".$tipo_de_busqueda."','".$tipo_de_busqueda_aux."','".$valor_de_busqueda."','tercero_id','1','".$limite."','".$offset."');";
         $javadx3 = "javascript:OrdernarPor('".$tipo_de_busqueda."','".$tipo_de_busqueda_aux."','".$valor_de_busqueda."','nombre','1','".$limite."','".$offset."');";*/
         $imagen = $path."/images/abajo.png";
         $imagen2 = $path."/images/abajob.jpeg";
         $imagen1 = "<sub><img src=\"".$imagen."\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
         $imagen3 = "<sub><img src=\"".$imagen2."\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
         $salida  = "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
         $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
         $salida .= "                      <td align=\"center\" width=\"8%\">\n";
         $salida .= "                        <a title='TIPO DE DOCUMENTO'>TIPO ID </a>";//<a title='ORGANIZAR POR TIPO DOCUMENTO' href=\"".$javadx1."\">".$imagen1."</a>
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"10%\">\n";
         $salida .= "                        <a title='NUMERO DE IDENTIFICACION'>NUMERO</a>";//<a title='ORGANIZAR POR NUMERO DE DOCUMENTO' href=\"".$javadx2."\">".$imagen1."</a>
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"23%\">\n";
         $salida .= "                        <a title='NOMBRE TERCERO'>NOMBRE";//<a title='ORGANIZAR POR NOMBRE DE TERCERO' href=\"".$javadx3."\">".$imagen1."</a>
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"4%\">\n";
         $salida .= "                        <a title='PAIS'>PAIS<a>";
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"4%\">\n";
         $salida .= "                        <a title='DEPARTAMENTO ID'>DPTO<a>";
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"4%\">\n";
         $salida .= "                        <a title='MUNICIPIO ID'>MPIO<a>";
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"15%\">\n";
         $salida .= "                        <a title='DIRECCION'>DIR<a>";
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"11%\">\n";
         $salida .= "                        <a title='TELEFONO'>TEL<a>";
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"9%\">\n";
         $salida .= "                        <a title='FAX'>FAX<a>";
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"10%\">\n";
         $salida .= "                        <a title='CELULAR'>CEL<a>";
         $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"2%\">\n";
         $salida .= "                        <a title='PERSONA JURIDICA'>PJ<a>";
         $salida .= "                      </td>\n";
         $salida .= "                    </tr>\n";

           foreach($vector as $clave => $valor)
           {
             foreach($valor as $clave => $valor)
             {

                 $salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                        ".$valor['tipo_id_tercero']."";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                       ".$valor['tercero_id']."";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                        <a title='".$valor['nombre_tercero']."'>";
                 $salida .= "                         ".substr($valor['nombre_tercero'],0,27)."";
                 $salida .= "                        </a>";
                 $salida .= "                      </td>\n";
                 $pais=$consulta1->sacar_pais($valor['tipo_pais_id']);
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                        <a title='".$pais['pais']."'>";
                 $salida .= "                          ".$valor['tipo_pais_id']."";
                 $salida .= "                        </a>";
                 $salida .= "                      </td>\n";
                 $depar=$consulta1->sacar_depar($valor['tipo_pais_id'],$valor['tipo_dpto_id']);
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                        <a title='".$depar['departamento']."'>";
                 $salida .= "                 ".$valor['tipo_dpto_id']."";
                 $salida .= "                        </a>";
                 $salida .= "                      </td>\n";
                 $mpio=$consulta1->sacar_mpio($valor['tipo_pais_id'],$valor['tipo_dpto_id'],$valor['tipo_mpio_id']);
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                        <a title='".$mpio['municipio']."'>";
                 $salida .= "                 ".$valor['tipo_mpio_id']."";
                 $salida .= "                        </a>";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                        <a title='".$valor['direccion']."'>";
                 $salida .= "                      ".substr($valor['direccion'],0,20)."";
                 $salida .= "                        </a>";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                        <a title='".$valor['telefono']."'>";
                 $salida .= "                        ".substr($valor['telefono'],0,16)."";
                 $salida .= "                        </a>";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                       ".$valor['fax']."";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 $salida .= "                        ".$valor['celular']."";
                 $salida .= "                      </td>\n";
                 $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                 if($valor['sw_persona_juridica']=='1')
                 {
                   $salida .= "                 SI";
                 }
                 elseif($valor['sw_persona_juridica']=='0')
                 {
                   $salida .= "                 NO";
                 }
                 $salida .= "                     </td>\n";
                 $salida .= "                   </tr>\n";
               }

             }
            $salida .= "                </table>\n";

            //$cuantos['count'];

            $salida .=" ".ObtenerPaginadoTer($path,$cuantos,'1',$tipo_de_busqueda,$tipo_de_busqueda_aux,$valor_de_busqueda,$limite,$offset);
            $objResponse->assign("lista_ter","innerHTML",$salida);
    }
     else
     {
         $cad= "ERRORES : " . $consulta->Err() . "<br>" . $consulta->ErrMsg() . "<br>";
         $cad= "NO SE ENCONTRARON RESULTADOS CON ESE CRITERIO DE BUSQUEDA";
         $objResponse->assign("error_ter","innerHTML",$cad);
         $salida .= "      <table align=\"center\" width=\"50%\">\n";
         $salida .= "       <tr>\n";
         $salida .= "        <td align=\"center\" colspan='7'>\n";
         $salida .= "          <input type=\"button\" class=\"input-submit\" value=\"Volver y Mostrar Todos\" onclick=\"Volver();\">\n";
         $salida .= "        </td>\n";
         $salida .= "       </tr>\n";
         $salida .= "      </table>\n";
         $objResponse->assign("lista_ter","innerHTML",$salida);
     }


   return $objResponse;
 }

    /**
    * Metodo para obtener el paginador de terceros
    *
    * @param string   $path (ruta de las imagenes del siis)
    * @param string   $slc (numero total de registro de la consulta realizada)
    * @param string   $op (posciuon del paginador arriba o abajo)
    * @param string   $path (ruta de las imagenes del siis)
    * @param integer  $tipo_de_busqueda (CRITERIO DE BUSQUEDA)
    * @param integer  $tipo_de_busqueda_aux (CRITERIO DE BUSQUEDA)
    * @param integer  $valor_de_busqueda
    * @param integer  $limite (cantidad de refistros a mostrar por pagina)
    * @param integer  $pagina (pagina de la consulta)
    * @return string  $Tabla (devuelve la llave del paginador)
    * @access public
    */
   function ObtenerPaginadoTer($path,$slc,$op,$tipo_de_busqueda,$tipo_de_busqueda_aux,$valor_de_busqueda,$limite,$pagina)
    {

      $TotalRegistros = $slc['count'];
      $TablaPaginado = "";

      if($limite == null)
      {
        $uid = UserGetUID();
         $LimitRow = intval(GetLimitBrowser());
      }
      else
      {
        $LimitRow = $limite;
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
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">P???inas:</td>\n";
          if($pagina > 1)
          {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";    //tercos('".tipo_de_busqueda."','".tipo_de_busqueda_aux."','".valor_de_busqueda."','".limite."','1')
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:tercos('".$tipo_de_busqueda."','".$tipo_de_busqueda_aux."','".$valor_de_busqueda."','".$limite."','1')\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:tercos('".$tipo_de_busqueda."','".$tipo_de_busqueda_aux."','".$valor_de_busqueda."','".$limite."','".($pagina-1)."')\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
              $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:tercos('".$tipo_de_busqueda."','".$tipo_de_busqueda_aux."','".$valor_de_busqueda."','".$limite."','".$i."')\">".$i."</a></td>\n";
            }
            $columnas++;
          }
        }
        if($pagina <  $NumeroPaginas )
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";      //tercos('".$tipo_de_busqueda."','".$tipo_de_busqueda_aux."','".$valor_de_busqueda."','".$limite."','".$NumeroPaginas."')
          $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:tercos('".$tipo_de_busqueda."','".$tipo_de_busqueda_aux."','".$valor_de_busqueda."','".$limite."','".($pagina+1)."')\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:tercos('".$tipo_de_busqueda."','".$tipo_de_busqueda_aux."','".$valor_de_busqueda."','".$limite."','".$NumeroPaginas."')\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td>\n";
          $columnas +=2;
        }
        $aviso .= "   <tr><td class=\"label\"  colspan=".$columnas." align=\"center\">\n";
        $aviso .= "     P???ina&nbsp;".$pagina." de ".$NumeroPaginas."</td>\n";
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
    * Metodo para obtener el paginador de proveedores
    *
    * @param string   $path (ruta de las imagenes del siis)
    * @param string   $slc (numero total de registro de la consulta realizada)
    * @param string   $op (posciuon del paginador arriba o abajo)
    * @param string   $path (ruta de las imagenes del siis)
    * @param integer  $tipo_de_busqueda (CRITERIO DE BUSQUEDA)
    * @param integer  $tipo_de_busqueda_aux (CRITERIO DE BUSQUEDA)
    * @param integer  $valor_de_busqueda
    * @param integer  $limite (cantidad de refistros a mostrar por pagina)
    * @param integer  $pagina (pagina de la consulta)
    * @return string  $Tabla (devuelve la llave del paginador)
    * @access public
    */
   function ObtenerPaginadoProv($path,$slc,$op,$tipo_de_busqueda,$tipo_de_busqueda_aux,$valor_de_busqueda,$limite,$pagina)
    {

      $TotalRegistros = $slc['count'];
      $TablaPaginado = "";

      if($limite == null)
      {
        $uid = UserGetUID();
         $LimitRow = intval(GetLimitBrowser());
      }
      else
      {
        $LimitRow = $limite;
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
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">P???inas:</td>\n";
          if($pagina > 1)
          {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";             //tipo_de_busqueda,   tipo_de_busqueda_aux,      valor_de_busqueda, limite, offset
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Proves('".$tipo_de_busqueda."','".$tipo_de_busqueda_aux."','".$valor_de_busqueda."','".$limite."','1')\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Proves('".$tipo_de_busqueda."','".$tipo_de_busqueda_aux."','".$valor_de_busqueda."','".$limite."','".($pagina-1)."')\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
              $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:Proves('".$tipo_de_busqueda."','".$tipo_de_busqueda_aux."','".$valor_de_busqueda."','".$limite."','".$i."')\">".$i."</a></td>\n";
            }
            $columnas++;
          }
        }
        if($pagina <  $NumeroPaginas )
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";      //tercos('".$tipo_de_busqueda."','".$tipo_de_busqueda_aux."','".$valor_de_busqueda."','".$limite."','".$NumeroPaginas."')
          $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Proves('".$tipo_de_busqueda."','".$tipo_de_busqueda_aux."','".$valor_de_busqueda."','".$limite."','".($pagina+1)."')\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:Proves('".$tipo_de_busqueda."','".$tipo_de_busqueda_aux."','".$valor_de_busqueda."','".$limite."','".$NumeroPaginas."')\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td>\n";
          $columnas +=2;
        }
        $aviso .= "   <tr><td class=\"label\"  colspan=".$columnas." align=\"center\">\n";
        $aviso .= "     P???ina&nbsp;".$pagina." de ".$NumeroPaginas."</td>\n";
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
	
	
	
	

?>