<?php

   /**
  * @package IPSOFT-SIIS
  * @version $Id: Formularios_HTML.class.php,v 1.8 2010/01/19 13:23:00 mauricio Exp $ 
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

  /**
  * Clase Vista: Formularios_HTML_MenuHTML
  * Clase Contiene Metodos para el despliegue de Formularios del Módulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.8 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

	class Formularios_HTML
	{
		/**
		* Constructor de la clase
		*/
		function Formularios_HTML(){}
		/**
	    * @param array $action Vector de links de la aplicaion
		* 
		*/
		function Form_CrearMolecula($action,$Moleculas,$request)
		{
		
		$accion=$action['volver'];
		$accion=ModuloGetURL('app','Inv_CodificacionProductos','controller','Crearlaboratorios');
	  /*
    * CARGA CODIGO JAVASRCIPT PARA
    * RESTRICCIONES DE LABORATORIO PROVEEDORES
    * FORMULARIO DE INGRESO Y MODIFICACION DE LABORATORIOS.
    */
	  $html ="    
   <script languaje=\"javascript\">
   function acceptNum(evt)
  { 
  var nav4 = window.Event ? true : false;
  var key = nav4 ? evt.which : evt.keyCode;
  return (key < 13 || (key >= 48 && key <= 57) || key == 45 || key == 46);
  } 
  </script>";
  
  
  
  
  
  
  
  $html .='
  
  
  
  <script languaje="javascript">
  
  
  function Confirmar(formulario)
  {
  var band=0;
  
  if(document.FormularioMolecula.molecula_id.value=="")
  {
  alert("Campo De Codigo de Molecula Está Vacío");
  band=1;
  }
  
  if(document.FormularioMolecula.descripcion.value=="")
  {
  alert("Campo De Nombre Molecula Está Vacío");
  band=1;
  }
  
  /*if(document.FormularioMolecula.concentracion.value=="")
  {
  alert("Campo De Concentracion Está Vacío");
  band=1;
  }*/
  
    
    
    if(band==1)
          {
          alert("Por Favor, llenar los Campos Vacíos!!!");
          }
          
          else
          {
              //alert("Formulario bien diligenciado!!!");
              var entrar = confirm("Confirmar Envio de datos?")
              if (entrar) 
              {
                //alert("Haz dado en Aceptar");
               if(document.FormularioMolecula.token.value==1) //Si es Ingreso de Laboratorio
                  {
                  xajax_InsertarMolecula(formulario);
                  alert("ingreso");
                  }
                       else
                          {
                          xajax_GuardarModMolecula(formulario);
                          //alert("Modi");
                          }
               
                
              }
                else
                {
                  alert("Haz Cancelado");
                                   
                }
          }
  
  }
  
  
  function Busqueda_()
  {
        
        var nombre= document.buscador.descripcion.value;
        var codigo= document.buscador.molecula_id.value;
        var sw_medicamento= document.buscador.sw_medicamento.value;
        
        xajax_BusquedaMolecula_Nombre(nombre,codigo,sw_medicamento);
                       
  }
  
  
  function acceptNum(evt)
{ 
  var nav4 = window.Event ? true : false;
  var key = nav4 ? evt.which : evt.keyCode;
  return (key < 13 || (key >= 48 && key <= 57) || key == 45 || key == 46);
}
  
  
  </script>
  ';
  
  //5) Paso LLamado a funcion: Crea un intermediario para el llamado a la funcion xajax
    $html .= "<script>";
    $html .= " function Paginador(offset)\n";
    $html .= "  {";
    $html .= "    xajax_MoleculasT('1',offset);\n";
    $html .= "  }\n";
    $html .= "</script>";
     //BusquedaMolecula_Nombre($Nombre,$Codigo,$Sw_Medicamento,$offset)
    $html .= "<script>";
    $html .= " function PaginadorBusquedas(Nombre,Codigo,Sw_Medicamento,offset)\n";
    $html .= "  {";
    $html .= "    xajax_BusquedaMolecula_Nombre(Nombre,Codigo,Sw_Medicamento,offset);\n";
    $html .= "  }\n";
    $html .= "</script>";
  
  
    $html .= ThemeAbrirTabla('MOLECULAS (SubClases)');
      
    //BUSCADOR
    $html .= "<br>";
    $html .= "<form name=\"buscador\" method=\"POST\">";
    $html .= "<table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "<tr class=\"modulo_table_list_title\">";
    $html .= "<td colspan=\"5\" align=\"center\">";
    $html .= "BUSCADOR";
    $html .= "</td>";
    $html .= "</tr>";
    
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "Nombre :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" name=\"descripcion\">";
    
    $html .= "</td>";
    
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "Codigo :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" name=\"molecula_id\" maxlength=\"10\">";
    $html .= "<input type=\"hidden\" name='sw_medicamento' value='1'>";
    $html .= "</td>";
    
    
    $html .= "<td align=\"center\">";
    $html .= "<input class=\"input-submit\" type=\"button\" name='Buscar' value='Buscar' onclick=\"Busqueda_();\">";
    $html .= "</td>";
    $html .= "</tr>";
    
    
    
    
    
    
    $html .="</table>
            </form>";
    
     //FIN BUSCADOR 
      
      
      
      
      
      
      
	  $html .= "<BR><BR><CENTER><a class=\"label_error\" href=\"#\" onclick=\"xajax_IngresoMolecula()\">[::CREAR NUEVA MOLECULA::]</a><BR></CENTER>";
	  
	  $html .= "<script>\n";
      $html .= "  function mOvr(src,clrOver)\n";
      $html .= "  {\n";
      $html .= "    src.style.background = clrOver;\n";
      $html .= "  }\n";
      $html .= "  function mOut(src,clrIn)\n";
      $html .= "  {\n";
      $html .= "    src.style.background = clrIn;\n";
      $html .= "  }\n";
      $html .= "  function acceptDate(evt)\n";
      $html .= "  {\n";
      $html .= "    var nav4 = window.Event ? true : false;\n";
      $html .= "    var key = nav4 ? evt.which : evt.keyCode;\n";
      $html .= "    return (key <= 13 ||(key >= 47 && key <= 57));\n";
      $html .= "  }\n";
      $html .= "</script>\n";

	  $html .= "<div id=\"Listado\">\n"; //DIV PARA EL LISTADO DE LABORATORIOS CREADOS
        
    $html .= "<script>";
    $html .= "xajax_MoleculasT('1');";
    $html .= "</script>";
        
       
		  $html .= "</div>"; //CIERRA DIV DE LISTADO DE MOLECULAS
      $html .= "<form name=\"forma\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\"><br>\n";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
	  
	  $html .= "                   <div id='error_ter' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
       
    $html .= $this->CrearVentana(600,"MOLECULAS");
    $html .= ThemeCerrarTabla();
		
	  return $html;
 
		}

	/*
	* FUNCION PARA CREAR FORMULARIOS PARA EL INGRESO DE LABORATORIOS
	* @param String action : Contiene Codigo HTML.
	* Return String Html : Contiene codigo HTML.
	*/	
		
		
	function Form_CrearLaboratorio($action,$datos,$Laboratorios,$request)
		{
		$accion=$action['volver'];
		$accion=ModuloGetURL('app','Inv_CodificacionProductos','controller','Crearlaboratorios');
	  
    
	  $html .="
    
  <script languaje=\"javascript\">
  
  function limpiar() 
    {
  document.getElementById('error_terco').innerHTML=\"\";
    }
    
  function Tachar(valor)
    {   
    limpiar();
    if(valor=='NIT')
    {
     document.crearproveedor.dv.disabled=false; //getElementById('dv').disabled=false;
    }
    else
    {
     document.crearproveedor.dv.disabled=true;//document.getElementById('dv').disabled=true;
    }

  }
  
          //Validador de Formulario Proveedores
    
          function ValidadorUltraTercero1(forma)
          {
          var tipo_identificacion,id_tercero,nombre,pais,departamento,municipio,direccion,telefono,faz,mail,celular,perjur;
     
              if(forma.tipo_id_tercero.value==\"NIT\" && forma.dv.value=='')
              {
                document.getElementById('error_terco').innerHTML=\"EL DIGITO DE VERIFICACION DEL NIT SE ENCUENTRA VACIO\"; 
                return;
              }
              
              if(forma.tercero_id.value == \"\")
              {
                document.getElementById('error_terco').innerHTML=\"EL CAMPO TERCERO ID SE ENCUENTRA VACIO\"; 
                return ;
              }
             
              if(forma.nombre_tercero.value==\"\")
              {
                document.getElementById('error_terco').innerHTML=\"EL CAMPO NOMBRE SE ENCUENTRA VACIO\"; 
                return false;
              }
            
              if(forma.pais.value=='' )
              {
                document.getElementById('error_terco').innerHTML=\"SE DEBE SELECCIONAR UN PAIS\"; 
                return false;
              }
              
              if(forma.dpto.value==0 )
              {
                document.getElementById('error_terco').innerHTML=\"SE DEBE SELECCIONAR UN DEPARTAMENTO\"; 
                return false;
              }
              if(forma.mpio.value==0 )
              {
                document.getElementById('error_terco').innerHTML=\"SE DEBE SELECCIONAR UN MUNICIPIO\"; 
                return false;
              }
               
              if(forma.direccion.value==\"\")
              {
                document.getElementById('error_terco').innerHTML=\"EL CAMPO DIRECCION ESTA VACIO\"; 
                return false;
              }
                 
              if(!forma.sw_persona_juridica[0].checked && !forma.sw_persona_juridica[1].checked)
              { 
                document.getElementById('error_terco').innerHTML=\"DEBE DEFINIR SI EL PROVEEDOR ES PERSONA JURIDICA O NATURAL\"; 
                return ;
              }

              if(!forma.sw_regimen_comun[0].checked && !forma.sw_regimen_comun[1].checked)
              {
                document.getElementById('error_terco').innerHTML=\"LA OPCION DE REGIMEN COMUN O SIMPLIFICADO ESTA VACIA\"; 
                return false;
              }
                
              if(!forma.sw_gran_contribuyente[0].checked && !forma.sw_gran_contribuyente[1].checked)
              {
                document.getElementById('error_terco').innerHTML=\"LA OPCION DE GRAN CONTRIBUYENTE ESTA VACIA\"; 
                return false;
              }

              if(forma.porcentaje_rtf.value =='')
              {
                document.getElementById('error_terco').innerHTML=\"LA CASILLA DE RTF ESTA VACIA\"; 
                return false;
              }
              
              if(forma.porcentaje_ica.value =='')
              {
                document.getElementById('error_terco').innerHTML=\"LA CASILLA DE RT ICA ESTA VACIA\"; 
                return false;
              }

              if(document.getElementById('grupos').value==0)
              { 
                 document.getElementById('error_terco').innerHTML=\"DEBE ESCOGER UN GRUPO DE ACTIVIDAD A LA CUAL PERTENECE ESTE PROVEEDOR\"; 
                 return false;
              }
                    
              if(forma.actividad_id.value==0)
              { 
                 document.getElementById('error_terco').innerHTML=\"DEBE ESCOGER UNA ACTIVIDAD A LA QUE PERTENECE EL PROVEEDOR\"; 
                 return false;
              }
              
              forma.tipo_pais_id.value = forma.pais.value;
              forma.tipo_dpto_id.value = forma.dpto.value;
              forma.tipo_mpio_id.value = forma.mpio.value;  
                         
              //xajax_GuardarProveedor(tipo_identificacion,id_tercero,nombre,pais,document.getElementById('dptox').value,document.getElementById('mpios').value,direccion,telefono,faz,mail,celular,perjur,dv,dg,dc,te,dxc,sw_regimen_comun,sw_gran_contribuyente,actividad_id,porcentaje_rtf,porcentaje_ica);
              
            xajax_GuardarProveedor(xajax.getFormValues('crearproveedor'));
            }
    
   function acceptNum(evt)
  { 
  var nav4 = window.Event ? true : false;
  var key = nav4 ? evt.which : evt.keyCode;
  return (key < 13 || (key >= 48 && key <= 57) || key == 45 || key == 46);
  } 

  
  </script>";
  
  
  
  
  
  
  
  $html .='
  
  
  
  <script languaje="javascript">
  
  
  function Confirmar(formulario)
  {
    var band=0;
    if(document.FormularioLaboratorio.laboratorio_id.value=="")
    {
      alert("Campo De Codigo de Laboratorio Está Vacío");
      band=1;
    }
    
    if(document.FormularioLaboratorio.pais.value=="-1")
    {
      alert("No se ha seleccionado el pais del laboratorio");
      return;
    }
  
  if(document.FormularioLaboratorio.descripcion.value=="")
  {
  alert("Campo De Nombre laboratorio Está Vacío");
  band=1;
  }
  
  if(document.FormularioLaboratorio.direccion.value=="")
  {
  alert("Campo De Direccion Está Vacío");
  band=1;
  }
  if(document.FormularioLaboratorio.telefono.value=="")
  {
  alert("Campo De Telefono Está Vacío");
  band=1;
  }
    
    
    if(band==1)
          {
          alert("Por Favor, llenar los Campos Vacíos!!!");
          }
          
          else
          {
              //alert("Formulario bien diligenciado!!!");
              var entrar = confirm("Confirmar Envio de datos?")
              if (entrar) 
              {
                //alert("Haz dado en Aceptar");
               if(document.FormularioLaboratorio.token.value==1) //Si es Ingreso de Laboratorio
                  {
                  xajax_InsertarLaboratorio(formulario);
                  //alert("ingreso");
                  }
                       else
                          {
                          xajax_GuardarModLaboratorio(formulario);
                          //alert("Modi");
                          }
               
                
              }
                else
                {
                  alert("Haz Cancelado");
                                   
                }
          }
  
  }
  
  
  function titular(Codigolaboratorio,descripcion,tipo_pais_id)
  {
  var entrar = confirm("Confirma Ingresar Laboratorio, tambien como Titular Registro Invima?")
      if (entrar) 
          {
          xajax_IngresoTitular(Codigolaboratorio,descripcion,tipo_pais_id);
          }
  }
  
  
  function fabricante(Codigolaboratorio,descripcion)
  {
    var entrar = confirm("Confirma Ingresar Laboratorio, tambien como Fabricante?")
    if (entrar) 
    {
      xajax_IngresoLaboratorioFabricante(Codigolaboratorio,descripcion);
    }
  }
  
  
  
  function ValidarIngreso(formulario)
  {
  
  var band=0;
        if(document.FormularioLaboratorioFabricante.registro_invima.value=="")
        {
        alert("Campo De Registro Invima Está Vacío");
        band=1;
        }
        
        if(band==1)
          {
          alert("Por Favor, llenar los Campos Vacíos!!!");
          }
          
          else
          {
              xajax_GuardarLaboratorioFabricante(formulario);
          }
  }
  
  
  
  function Busqueda_()
  {
        
        var nombre= document.buscador.descripcion.value;
        var codigo= document.buscador.molecula_id.value;
                
        xajax_BusquedaLaboratorio_Nombre(nombre,codigo);
    
  }
  
    
  function acceptNum(evt)
{ 
  var nav4 = window.Event ? true : false;
  var key = nav4 ? evt.which : evt.keyCode;
  return (key < 13 || (key >= 48 && key <= 57) || key == 45 || key == 46);
}
  
  
  </script>
  ';
  
  $html .= "<script>";
    $html .= " function Paginador(offset)\n";
    $html .= "  {";
    $html .= "    xajax_LaboratoriosT(offset);\n";
    $html .= "  }\n";
    $html .= "</script>";
  
  
  $html .= "<script>";
    $html .= " function PaginadorBusquedas(Nombre,Codigo,offset)\n";
    $html .= "  {";
    $html .= "    xajax_BusquedaLaboratorio_Nombre(Nombre,Codigo,offset);\n";
    $html .= "  }\n";
    $html .= "</script>";
  
    $html .= ThemeAbrirTabla('LABORATORIOS');
      
    //BUSCADOR
    $html .= "<br>";
    $html .= "<form name=\"buscador\" method=\"POST\">";
    $html .= "<table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "<tr class=\"modulo_table_list_title\">";
    $html .= "<td colspan=\"5\" align=\"center\">";
    $html .= "BUSCADOR";
    $html .= "</td>";
    $html .= "</tr>";
    
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "Nombre :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" name=\"descripcion\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    
    $html .= "</td>";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "Codigo :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" name=\"molecula_id\" maxlength=\"4\" onkeyup=\"this.value=this.value.toUpperCase()\" >";
    $html .= "</td>";
     $html .= "<td align=\"center\">";
    $html .= "<input class=\"input-submit\" type=\"button\" name='Buscar' value='Buscar' onclick=\"Busqueda_();\">";
    $html .= "</td>";
    $html .= "</tr>";
    $html .= "</tr>";
    
    
    
    
    
    
    $html .="</table>
            </form>";
    
     //FIN BUSCADOR 
      
      
      
      
      
	  $html .= "<BR><BR><CENTER><a class=\"label_error\" href=\"#\" onclick=\"xajax_IngresoLaboratorio(".$datos.")\">[::CREAR NUEVO LABORATORIO::]</a><BR></CENTER>";
	  
	  $html .= "<script>\n";
      $html .= "  function mOvr(src,clrOver)\n";
      $html .= "  {\n";
      $html .= "    src.style.background = clrOver;\n";
      $html .= "  }\n";
      $html .= "  function mOut(src,clrIn)\n";
      $html .= "  {\n";
      $html .= "    src.style.background = clrIn;\n";
      $html .= "  }\n";
      $html .= "  function acceptDate(evt)\n";
      $html .= "  {\n";
      $html .= "    var nav4 = window.Event ? true : false;\n";
      $html .= "    var key = nav4 ? evt.which : evt.keyCode;\n";
      $html .= "    return (key <= 13 ||(key >= 47 && key <= 57));\n";
      $html .= "  }\n";
      $html .= "</script>\n";

	  $html .= "<div id=\"Listado\">\n"; //DIV PARA EL LISTADO DE LABORATORIOS CREADOS
       
    $html .= "<script>";
    $html .= "    xajax_LaboratoriosT();\n";
    $html .= "</script>";       
        
		$html .= "</div>"; //CIERRA DIV DE LISTADO DE LABORATORIOS
      $html .= "<form name=\"forma\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\"><br>\n";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
	  
	  $html .= "                   <div id='error_ter' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
       
    $html .= $this->CrearVentana(600,"LABORATORIO");
    $html .= ThemeCerrarTabla();
		
	  return $html;
	
		}	
	
  
  
  
  
  
  
  function Form_CrearTipoInsumo($action,$Moleculas,$request)
		{
		$accion=$action['volver'];
		$accion=ModuloGetURL('app','Inv_CodificacionProductos','controller','Crearlaboratorios');
	  /*
    * CARGA CODIGO JAVASRCIPT PARA
    * RESTRICCIONES DE LABORATORIO PROVEEDORES
    * FORMULARIO DE INGRESO Y MODIFICACION DE LABORATORIOS.
    */
	  $html ="    
   <script languaje=\"javascript\">
   function acceptNum(evt)
  { 
  var nav4 = window.Event ? true : false;
  var key = nav4 ? evt.which : evt.keyCode;
  return (key < 13 || (key >= 48 && key <= 57) || key == 45 || key == 46);
  } 
  </script>";
  
  
  
  
  
  
  
  $html .='
  
  
  
  <script languaje="javascript">
  
  
  function Confirmar(formulario)
  {
  var band=0;
  
  if(document.FormularioMolecula.molecula_id.value=="")
  {
  alert("Campo De Codigo de Tipo Insumo Está Vacío");
  band=1;
  }
  
  if(document.FormularioMolecula.descripcion.value=="")
  {
  alert("Campo De Nombre Insumo Está Vacío");
  band=1;
  }
  
  /*if(document.FormularioMolecula.concentracion.value=="")
  {
  alert("Campo De MEDIDA/TALLA... Está Vacío");
  band=1;
  }*/
  
    
    
    if(band==1)
          {
          alert("Por Favor, llenar los Campos Vacíos!!!");
          }
          
          else
          {
              //alert("Formulario bien diligenciado!!!");
              var entrar = confirm("Confirmar Envio de datos?")
              if (entrar) 
              {
                //alert("Haz dado en Aceptar");
               if(document.FormularioMolecula.token.value==1) //Si es Ingreso de Laboratorio
                  {
                  xajax_InsertarMolecula(formulario);
                  //alert("ingreso");
                  }
                       else
                          {
                          xajax_GuardarModMolecula(formulario);
                          //alert("Modi");
                          }
               
                
              }
                else
                {
                  alert("Haz Cancelado");
                                   
                }
          }
  
  }
  
  
  function Busqueda_()
  {
        
        var nombre= document.buscador.descripcion.value;
        var codigo= document.buscador.molecula_id.value;
        var sw_medicamento= document.buscador.sw_medicamento.value;
        
        xajax_BusquedaMolecula_Nombre(nombre,codigo,sw_medicamento);
                       
  }
  
  
  function acceptNum(evt)
{ 
  var nav4 = window.Event ? true : false;
  var key = nav4 ? evt.which : evt.keyCode;
  return (key < 13 || (key >= 48 && key <= 57) || key == 45 || key == 46);
}
  
  
  </script>
  ';
    $html .= ThemeAbrirTabla('TIPOS DE INSUMOS (SubClases)');
      
   //BUSCADOR
    $html .= "<br>";
    $html .= "<form name=\"buscador\" method=\"POST\">";
    $html .= "<table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "<tr class=\"modulo_table_list_title\">";
    $html .= "<td colspan=\"5\" align=\"center\">";
    $html .= "BUSCADOR";
    $html .= "</td>";
    $html .= "</tr>";
    
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "Nombre :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" name=\"descripcion\">";
    
    $html .= "</td>";
    
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "Codigo :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" name=\"molecula_id\" maxlength=\"10\">";
    $html .= "<input type=\"hidden\" name='sw_medicamento' value='0'>";
    $html .= "</td>";
    
    
    $html .= "<td align=\"center\">";
    $html .= "<input class=\"input-submit\" type=\"button\" name='Buscar' value='Buscar' onclick=\"Busqueda_();\">";
    $html .= "</td>";
    $html .= "</tr>";
    
    
    
    
    
    
    $html .="</table>
            </form>";
    
     //FIN BUSCADOR 
      
      $html .= "<script>";
    $html .= " function Paginador(offset)\n";
    $html .= "  {";
    $html .= "    xajax_MoleculasT('0',offset);\n";
    $html .= "  }\n";
    $html .= "</script>";
     //BusquedaMolecula_Nombre($Nombre,$Codigo,$Sw_Medicamento,$offset)
    $html .= "<script>";
    $html .= " function PaginadorBusquedas(Nombre,Codigo,Sw_Medicamento,offset)\n";
    $html .= "  {";
    $html .= "    xajax_BusquedaMolecula_Nombre(Nombre,Codigo,Sw_Medicamento,offset);\n";
    $html .= "  }\n";
    $html .= "</script>";
      
      
      
      
      
	  $html .= "<BR><BR><CENTER><a class=\"label_error\" href=\"#\" onclick=\"xajax_IngresoMolecula()\">[::CREAR NUEVO TIPO DE INSUMO::]</a><BR></CENTER>";
	  
	  $html .= "<script>\n";
      $html .= "  function mOvr(src,clrOver)\n";
      $html .= "  {\n";
      $html .= "    src.style.background = clrOver;\n";
      $html .= "  }\n";
      $html .= "  function mOut(src,clrIn)\n";
      $html .= "  {\n";
      $html .= "    src.style.background = clrIn;\n";
      $html .= "  }\n";
      $html .= "  function acceptDate(evt)\n";
      $html .= "  {\n";
      $html .= "    var nav4 = window.Event ? true : false;\n";
      $html .= "    var key = nav4 ? evt.which : evt.keyCode;\n";
      $html .= "    return (key <= 13 ||(key >= 47 && key <= 57));\n";
      $html .= "  }\n";
      $html .= "</script>\n";

	  $html .= "<div id=\"Listado\">\n"; //DIV PARA EL LISTADO DE LABORATORIOS CREADOS
    $html .= "<script>";
    $html .= "xajax_MoleculasT('0');";
    $html .= "</script>";
    
		$html .= "</div>"; //CIERRA DIV DE LISTADO DE TIPOS DE INSUMOS
      $html .= "<form name=\"forma\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\"><br>\n";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
	  
	  $html .= "                   <div id='error_ter' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
       
    $html .= $this->CrearVentana(600,"MOLECULAS");
    $html .= ThemeCerrarTabla();
		
	  return $html;
 
		}

  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
	
	// CREAR LA CAPITA
	function CrearVentana($tmn,$Titulo)
    {
      $html .= "<script>\n";
      $html .= "  var contenedor = 'Contenedor';\n";
      $html .= "  var titulo = 'titulo';\n";
      $html .= "  var hiZ = 4;\n";
      $html .= "  function OcultarSpan()\n";
      $html .= "  { \n";
      $html .= "    try\n";
      $html .= "    {\n";
      $html .= "      e = xGetElementById('Contenedor');\n";
      $html .= "      e.style.display = \"none\";\n";
      $html .= "    }\n";
      $html .= "    catch(error){}\n";
      $html .= "  }\n";
      //Mostrar Span
      $html .= "  function MostrarSpan(titulo)\n";
      $html .= "  { \n";
      $html .= "    try\n";
      $html .= "    {\n";
      $html .= "      e = xGetElementById('Contenedor');\n";
      $html .= "      e.style.display = \"\";\n";
      $html .= "      Iniciar(titulo);\n";
      $html .= "    }\n";
      $html .= "    catch(error){alert(error)}\n";
      $html .= "  }\n";

      $html .= "  function MostrarTitle(Seccion)\n";
      $html .= "  {\n";
      $html .= "    xShow(Seccion);\n";
      $html .= "  }\n";
      $html .= "  function OcultarTitle(Seccion)\n";
      $html .= "  {\n";
      $html .= "    xHide(Seccion);\n";
      $html .= "  }\n";

      $html .= "  function Iniciar(enc)\n";
      $html .= "  {\n";
      $html .= "    tit = (enc == undefined)? 'LABORATORIO':enc;\n";
      $html .= "    contenedor = 'Contenedor';\n";
      $html .= "    titulo = 'titulo';\n";
      $html .= "    ele = xGetElementById('Contenido');\n";
      $html .= "    xResizeTo(ele,".$tmn.", 'auto');\n";
      $html .= "    ele = xGetElementById(contenedor);\n";
      $html .= "    xResizeTo(ele,".$tmn.", 'auto');\n";
      $html .= "    xMoveTo(ele, xClientWidth()/4, xScrollTop()+20);\n";
      $html .= "    ele = xGetElementById(titulo);\n";
      $html .= "    ele.innerHTML = tit;\n";
      $html .= "    xResizeTo(ele,".($tmn - 20).", 20);\n";
      $html .= "    xMoveTo(ele, 0, 0);\n";
      $html .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
      $html .= "    ele = xGetElementById('cerrar');\n";
      $html .= "    xResizeTo(ele,20, 20);\n";
      $html .= "    xMoveTo(ele,".($tmn - 20).", 0);\n";
      $html .= "  }\n";

      $html .= "  function myOnDragStart(ele, mx, my)\n";
      $html .= "  {\n";
      $html .= "    window.status = '';\n";
      $html .= "    if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
      $html .= "    else xZIndex(ele, hiZ++);\n";
      $html .= "    ele.myTotalMX = 0;\n";
      $html .= "    ele.myTotalMY = 0;\n";
      $html .= "  }\n";
      $html .= "  function myOnDrag(ele, mdx, mdy)\n";
      $html .= "  {\n";
      $html .= "    if (ele.id == titulo) {\n";
      $html .= "      xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
      $html .= "    }\n";
      $html .= "    else {\n";
      $html .= "      xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
      $html .= "    }  \n";
      $html .= "    ele.myTotalMX += mdx;\n";
      $html .= "    ele.myTotalMY += mdy;\n";
      $html .= "  }\n";
      $html .= "  function myOnDragEnd(ele, mx, my)\n";
      $html .= "  {\n";
      $html .= "  }\n";
      
      
      $html.= "function Cerrar(Elemento)\n";
           $html.= "{\n";
           $html.= "    capita = xGetElementById(Elemento);\n";
           $html.= "    capita.style.display = \"none\";\n";
           $html.= "}\n";
      
      
      
      $html .= "</script>\n";
      $html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
      $html .= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;text-align:center;\">".$Titulo."</div>\n";
      $html .= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
      $html .= "  <div id='Contenido' class='d2Content'>\n";
      //En ese espacio se visualiza la informacion extraida de la base de datos.
      $html .= "  </div>\n";
      $html .= "</div>\n";
		
      return $html;
    }
    
    
    
    
    
	
	
	/*
	* FUNCION PARA CREAR FORMULARIOS PARA LA CODIFICACION DE PRODUCTOS
	* @param String action : Contiene Codigo HTML.
	* Return String Html : Contiene codigo HTML.
	*/	
		
		
	function Codificacion_Productos($action,$TablaCodificacion)
        		{
		//$accion=$action['volver'];
		$accion=ModuloGetURL('app','Inv_CodificacionProductos','controller','');
		$html  = ThemeAbrirTabla('CODIFICACION GENERAL DE ITEMS DEL INVENTARIO');
		
		
		$html .= "  <table border=\"0\" width=\"50%\" align=\"center\">";
		$html .= "  <tr><td>";
		
		//action del formulario= Donde van los datos del formulario.
		$html .= "      <table border=\"0\" width=\"82%\" align=\"center\" class=\"modulo_table_list\">";
		
		
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"2\">";
		$html .= "GRUPO";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" colspan=\"2\">";
		$html .= "CLASE";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" colspan=\"2\">";
		$html .= "SUBCLASE";
		$html .= "      </td>";
		$html .= "      </tr>";
		
		$html .= $TablaCodificacion;
		
				
		
		$html .= "      </table>";
				
		$html .= "  </td></tr>";
		$html .= '  <form name="forma" action="'.$action['volver'].'" method="post">';
		$html .= "  <tr>";
		$html .= "  <td align=\"center\"><br>";
		$html .= '  <input class="input-submit" type="submit" name="volver" value="Volver">';
		
		$html .= "  </td>";
		$html .= "  </form>";
		$html .= "  </tr>";
		$html .= "  </table>";
		$html .= ThemeCerrarTabla();
		$html .= '<script languaje="javascript">
			desactivar();
		</script>';
		return $html;
	
		}

   function FormularioBusquedaProductos($productos = array(), $criterio = '', $termino = '', $conteo, $pagina, $paginador, $offset){
        echo "Vista inicio";
        $pgn = AutoCarga::factory("ClaseHTML");

        $action1=ModuloGetURL('app','Inv_CodificacionProductos','controller','MenuCodificacionProductos');

        $salida .= ThemeAbrirTabla('DIAS TRATAMIENTOS');
        $salida .= "<form name=\"buscador_productos\" id=\"pedido\" method=\"post\">\n";
        $salida .= "    <table width=\"35%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "      <tr><td class=\"modulo_table_list_title\" align=\"center\" colspan=\"3\">BUSCAR PRODUCTOS</td></tr>\n";
        $salida .= "      <tr class=\"modulo_list_claro\">";
        $salida .= "        <td  align=\"center\">
                              <select id='criterio' name=\"criterio\">   
                                <option value=\"codigo\">C&oacute;digo</option>
                                <option value=\"nombre\">Nombre</option>
                                <option value=\"molecula\">Molecula</option>
                              </select>
                            </td>";
        $salida .= "        <td><input type=\"text\" value=\"\" name=\"termino\" id=\"tercero_id\" class=\"input-text\"></td>";
        $salida .= "        <td align=\"center\" colspan=\"\"><input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\"></td>";
        $salida .= "      </tr>\n";
        $salida .= "    </table>\n";
        $salida .= "<form>";
        $salida .= "<br>";

        //Tabla resultado de busqueda de productos
        $salida .= "<form name=\"buscador_productos\" id=\"pedido\" method=\"post\">\n";
        $salida .= "    <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "      <tr>
                            <td class=\"modulo_table_list_title\" align=\"center\">C&oacute;digo</td>
                            <td class=\"modulo_table_list_title\" align=\"center\">Nombre</td>
                            <td class=\"modulo_table_list_title\" align=\"center\">Mol&eacute;cula</td>
                            <td class=\"modulo_table_list_title\" align=\"center\">Dias Tratamiento</td>
                            <td class=\"modulo_table_list_title\" align=\"center\">Acci&oacute;n</td>
                          </tr>\n";
        //Renderizado de los productos encontrados (filas de la tabla de resultado de busqueda)
        $conteo = 1;
        foreach ($productos as $producto) {
          $parametros = array(
            'criterio' => $criterio,
            'termino' => $termino,
            'codigo_producto' => $producto['codigo_producto'],
            'nombre_producto' => $producto['nombre_producto'],
            'molecula_producto' => $producto['molecula_producto'],
            'periodo_dispensacion' => $producto['periodo_dispensacion'],
            'offsetBusqueda' => $offset
          );
          $urlBotonEditar = ModuloGetURL('app', 'Inv_CodificacionProductos', 'controller', 'ActualizarPeriodoDispensacion', $parametros);
          $clase = $conteo%2 == 0? "class=\"modulo_list_oscuro\"" : "class=\"modulo_list_claro\"";
          $salida.= sprintf("<tr ".$clase."><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td align=\"center\"><a href='%s'>Editar</a></td></tr>", $producto['codigo_producto'], $producto['nombre_producto'], $producto['molecula_producto'], $producto['periodo_dispensacion'], $urlBotonEditar);

          $conteo++;
        }

        $salida .= "    </table>\n";
        $salida .= "</form>\n";
        $salida .= "    ".$pgn->ObtenerPaginado($conteo,$pagina,$paginador);

        $salida .= "    <br>\n";
        $salida .= "    <table align=\"center\" width=\"35%\">\n";
        $salida .= "      <tr><td align=\"center\">\n";
        $salida .= "        <form name=\"formavolver\" method=\"POST\" action=\"$action1\">";
        $salida .= "          <input type=\"submit\" class=\"input-submit\" name=\"Volver\" value=\"VOLVER\">";
        $salida .= "        </form>";
        $salida .= "      </td></tr>\n";
        $salida .= "    </table>\n";

        $salida .= ThemeCerrarTabla();

        return $salida;
    }	

    function FormularioActualizacionPeriodoDispensacion($periodo_dispensacion = '', $logs = array(), $conteo = 0, $pagina = 0, $paginador){
      $request = $_REQUEST;
      $periodo_dispensacion = !empty($periodo_dispensacion) || $periodo_dispensacion == "0"? $periodo_dispensacion : $request['periodo_dispensacion'];

      $salida = "
      <script type='text/javascript'>
        function soloNumeros(e){
          var teclaPulsada=window.event ? window.event.keyCode:e.which;
          var valor=document.getElementById('nuevo_periodo_dispensacion').value;
          if(teclaPulsada==45 && valor.indexOf('-')==-1) {
              document.getElementById('nuevo_periodo_dispensacion').value='-'+valor;
          }
          console.log(teclaPulsada);
          if(teclaPulsada==13 || teclaPulsada==8 || teclaPulsada==0 ||  (teclaPulsada==46 && valor.indexOf('.')==-1)) {
              return true;
          }
          return /\d/.test(String.fromCharCode(teclaPulsada));
        }
      </script>";

      $pgn = AutoCarga::factory("ClaseHTML");
      
      $urlVolver = ModuloGetURL('app', 'Inv_CodificacionProductos', 'controller', 'DiasTratamientos', array('criterio' => $request['criterio'], 'termino' => $request['termino'], 'offsetBusqueda'=> $request['offsetBusqueda']));
      $salida .= ThemeAbrirTabla('ACTUALIZACION DIAS TRATAMIENTO');
      //Formulario de edicion
      $salida .= "<form name=\"actualizar_dias_tratamiento\" id=\"pedido\" method=\"post\">\n";
      $salida .= "    <table width=\"35%\" cellspacing=\"2\" cellpadding=\"3\" border=\"0\" align=\"center\">\n";
      $salida .= "      <tr>";
      $salida .= "        <td class=\"modulo_table_list_title\" align=\"center\" alignleftn=\"center\">
                           CODIGO
                          </td>";
      $salida .= "        <td class=\"modulo_list_claro\">".$request['codigo_producto']."</td>";
      $salida .= "      </tr><tr>";
      $salida .= "        <td class=\"modulo_table_list_title\" align=\"center\" alignleftn=\"center\">
                            NOMBRE
                          </td>";
      $salida .= "        <td class=\"modulo_list_claro\">".$request['nombre_producto']."</td>";
      $salida .= "      </tr><tr>"; 
      $salida .= "        <td class=\"modulo_table_list_title\" align=\"center\" alignleftn=\"center\">
                            MOLECULA
                          </td>";
      $salida .= "        <td class=\"modulo_list_claro\">".$request['molecula_producto']."</td>";
      $salida .= "      </tr><tr>";
      $salida .= "        <td class=\"modulo_table_list_title\" align=\"center\" alignleftn=\"center\">
                            DIAS TRATAMIENTO  
                          </td>";
      $salida .= "        <td class=\"modulo_list_claro\">".$periodo_dispensacion."</td>";
      $salida .= "      </tr><tr>";
      $salida .= "        <td class=\"modulo_table_list_title\" align=\"center\" alignleftn=\"center\">
                            NUEVO DIAS TRATAMIENTO
                          </td>";
      $salida .= "        <td class=\"modulo_list_claro\"><input style=\"width:100%;\" type=\"text\" id=\"nuevo_periodo_dispensacion\" name=\"nuevo_periodo_dispensacion\" maxlength=\"15\" onkeypress=\"return soloNumeros(event);\"></td>";
      $salida .= "      </tr><tr>";
      $salida .= "        <td class=\"modulo_list_claro\" colspan=\"2\" align=\"center\"> <input type=\"submit\" class=\"input-submit\" name=\"Guardar\" value=\"Guardar\"> </td>";
      $salida .= "      </tr>\n";
      $salida .= "    </table>\n";
      $salida .= "    <input type=\"hidden\" name=\"periodo_dispensacion\" value=\"".$periodo_dispensacion."\">";
      $salida .= "    <input type=\"hidden\" name=\"codigo_producto\" value=\"".$request['codigo_producto']."\">";
      $salida .= "</form>";
      $salida .= "<br>";

      //tabla historial de actualizaciones 
      $salida .= "    <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
      $salida .= "      <tr><td class=\"modulo_table_list_title\" colspan=\"4\" align=\"center\">HISTORIAL DE ACTUALIZACIONES</td></tr>";
      $salida .= "      <tr>
                          <td class=\"modulo_table_list_title\" align=\"center\">Usuario</td>
                          <td class=\"modulo_table_list_title\" align=\"center\">Valor Anterior</td>
                          <td class=\"modulo_table_list_title\" align=\"center\">Valor Actual</td>
                          <td class=\"modulo_table_list_title\" align=\"center\">Fecha</td>
                        </tr>\n";
      //Renderizado de filas del historial de actualizaciones
      foreach ($logs as $log) {
        $salida.= sprintf("<tr><td>%s</td><td>%s</td><td>%s</td><td align=\"center\">%s</td></tr>", $log['nombre_usuario'], $log['anterior'], $log['actual'], $this->FechaStamp($log['fecha'])." ".$this->HoraStamp($log['fecha']));
      }
      $salida .= "  </table>\n<br>";
      $salida .= "    ".$pgn->ObtenerPaginado($conteo,$pagina,$paginador);

      //boton volver
      $salida .= "    <table align=\"center\" width=\"20%\">\n";
      $salida .= "      <tr>";
      $salida .= "        <form name=\"formavolver\" method=\"POST\" action=\"$urlVolver\">";
      $salida .= "          <td align=\"center\"><input  type=\"submit\" class=\"input-submit\" name=\"Volver\" value=\"VOLVER\"></td>";
      $salida .= "        </form>";
      $salida .= "      </tr>\n";
      $salida .= "    </table>\n<br>";

      $salida .= ThemeCerrarTabla();

      return $salida;
    }


          /**
     * Se encarga de separar la fecha del formato timestamp
     * @access private
     * @return string
     * @param date fecha
     */
    function FechaStamp($fecha) {
        if ($fecha) {
            $fech = strtok($fecha, "-");
            for ($l = 0; $l < 3; $l++) {
                $date[$l] = $fech;
                $fech = strtok("-");
            }
            return ceil($date[2]) . "/" . str_pad(ceil($date[1]), 2, 0, STR_PAD_LEFT) . "/" . str_pad(ceil($date[0]), 2, 0, STR_PAD_LEFT);
        }
    }

    function HoraStamp($hora) {
        $hor = strtok($hora, " ");
        for ($l = 0; $l < 4; $l++) {
            $time[$l] = $hor;
            $hor = strtok(":");
        }
        $x = explode('.', $time[3]);
        return $time[1] . ":" . $time[2] . ":" . $x[0];
    }

		
	}
?>