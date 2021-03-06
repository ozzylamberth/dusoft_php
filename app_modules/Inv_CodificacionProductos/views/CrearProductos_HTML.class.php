<?php

/**
 * @package IPSOFT-SIIS
 * @version $Id: CrearProductos_HTML.class.php,v 1.13 2010/01/18 15:22:22 mauricio Exp $ 
 * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Mauricio Adrian Medina Santacruz
 */

/**
 * Clase Vista: Formularios_HTML_MenuHTML
 * Clase Contiene Metodos para el despliegue de Formularios del M�dulo
 *
 * @package IPSOFT-SIIS
 * @version $Revision: 1.13 $
 * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Mauricio Adrian Medina Santacruz
 */
class CrearProductos_HTML {

    /**
     * Constructor de la clase
     */
    function CrearProductos_HTML()
    {
        
    }

    /**
     * @param array 
     * $action Vector de links de la aplicaion
     * 
     */
    function main($request, $Grupos, $action, $PerfilesTerapeuticos)
    {
        $accion = $action['volver'];

        //Propiedades para el Calendario
        $html .="
    <link rel=\"stylesheet\" type=\"text/css\" href=\"javascripts/JCalc/src/css/jscal2.css\" />
    <link rel=\"stylesheet\" type=\"text/css\" href=\"javascripts/JCalc/src/css/border-radius.css\" />
    <link rel=\"stylesheet\" type=\"text/css\" href=\"javascripts/JCalc/src/css/steel/steel.css\" />
    <script type=\"text/javascript\" src=\"javascripts/JCalc/src/js/jscal2.js\"></script>
    <script type=\"text/javascript\" src=\"javascripts/JCalc/src/js/lang/es.js\"></script>
    ";

        $html .='
  
  
  
  <script languaje="javascript">
  


    function VerificarPos(pos)
    {
        var codigo = document.getElementById("codigo");
        var cod_acuerdo228_id = document.getElementById("cod_acuerdo228_id");
        var rips_no_pos = document.getElementById("rips_no_pos");
        if(pos == true)
        {
            codigo.disabled = false;
            cod_acuerdo228_id.disabled = false;
            rips_no_pos.disabled = true;
            return;
        }
        if(pos == false)
        {
            codigo.disabled = true;
            cod_acuerdo228_id.disabled = true;
            rips_no_pos.disabled = false;
            return;
        }
    }
  
  
  
  //Para Busquedas de Clases
   function Busqueda()
  {
        
        var nombre= document.buscador.descripcion.value;
        var codigo= document.buscador.clase_id.value;
        var grupo= document.buscador.GrupoId.value;
        var nombregrupo= document.buscador.NombreGrupo.value;
        var sw_medicamento= document.buscador.sw_medicamento.value;
        
       
        xajax_BusquedaClasesAsignadas(nombregrupo,grupo,nombre,codigo,sw_medicamento);
       
  }
  
  //Para Busquedas de SubClases
  function Busqueda_()
  {
        //Parametros de Busqueda
        var nombre= document.buscador_.descripcion.value;
        var codigo= document.buscador_.clase_id.value;
        
        //Datos Anexos
        var grupo= document.buscador_.GrupoId.value; //Codigo de Grupo
        var clase= document.buscador_.ClaseId.value; //Codigo de Clase
        var nombregrupo= document.buscador_.NombreGrupo.value; //Nombre del Grupo
        var nombreclase= document.buscador_.NombreClase.value; //Nombre de la Clase
        var sw_medicamento= document.buscador_.Sw_Medicamento.value; //Si es Medicamento o Insumos
        
       // alert(nombregrupo);
        
        xajax_BusquedaSubClasesAsignadas(nombregrupo,nombreclase,grupo,clase,nombre,codigo,sw_medicamento);
        
  }
  
  
  function acceptNum(evt)
  { 
      var nav4 = window.Event ? true : false;
      var key = nav4 ? evt.which : evt.keyCode;
      return (key < 13 || (key >= 48 && key <= 57) || key == 45 || key == 46);
  }
  
  </script>
  ';
        /*
         * @Autor: Jonier Murillo Hurtado
         * @Fecha: Julio 15 de 2011
         * @Observaciones: 
         *  Porci�n de C�digo agregado, para la visualizaci�n de la ventana emergente, a trav�s de la cual
         *  se ingresara el Factor de conversi�n para cada medicamento.
         * <Inicia aqui>
         */

        $javaC = "<script>\n";
        $javaC .= "   var contenedor\n";

        $javaC .= "   function CargarContenedor(Elemento)\n";
        $javaC .= "   {\n";
        $javaC .= "        contenedor = Elemento;\n";
        $javaC .= "   }\n";

        $javaC .= "   var titulo = 'titulo';\n";
        $javaC .= "   var hiZ = 2;\n";
        $javaC .= "   var DatosFactor = new Array();\n";
        $javaC .= "   var EnvioFactor = new Array();\n";

        $javaC .= "   function Iniciar(tit, suministro_id, Elemento)\n";
        $javaC .= "   {\n";
        $javaC .= "       Capa = xGetElementById(Elemento);\n";
        $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()+120);\n";

        $javaC .= "       document.getElementById('tituloAnul').innerHTML = '<center>'+tit+'</center>';\n";
        $javaC .= "       document.getElementById('errorAnul').innerHTML = '';\n";
        $javaC .= "       document.oculta.observacion.value = '';\n";
        $javaC .= "       document.oculta.suministro.value = suministro_id;\n";

        $javaC .= "       ele = xGetElementById('tituloAnul');\n";
        $javaC .= "       xResizeTo(ele, 280, 20);\n";
        $javaC .= "       xMoveTo(ele, 0, 0);\n";
        $javaC .= "       ele = xGetElementById('cerrarAnul');\n";
        $javaC .= "       xResizeTo(ele,20, 20);\n";
        $javaC .= "       xMoveTo(ele, 280, 0);\n";
        $javaC .= "   }\n";

        $javaC .= "   function IniciarCapaSum(tit, Elemento)\n";
        $javaC .= "   {\n";
        $javaC .= "	      Capa = xGetElementById(Elemento);\n";
        $javaC .= "	      xResizeTo(Capa, 620, 'auto');\n";
        $javaC .= "       document.getElementById('titulo').innerHTML = '<center>'+tit+'</center>';\n";
        $javaC .= "       document.getElementById('error').innerHTML = '';\n";
        $javaC .= "       Capa = xGetElementById(Elemento);\n";
        $javaC .= "       xMoveTo(Capa, xClientWidth()/4, xScrollTop()+24);\n";
        $javaC .= "       ele = xGetElementById('titulo');\n";
        $javaC .= "       xResizeTo(ele, 600, 20);\n";
        $javaC .= "       xMoveTo(ele, 0, 0);\n";
        $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
        $javaC .= "       ele = xGetElementById('cerrar');\n";
        $javaC .= "       xResizeTo(ele,20, 20);\n";
        $javaC .= "       xMoveTo(ele, 600, 0);\n";
        $javaC .= "   }\n";

        $javaC .= "   function FuncionFactorEnvio(forma)\n";
        $javaC .= "   {\n";
        $javaC .= "	      ValidacionPremisos();\n";
        $javaC .= "   }\n";

        $javaC .= "   function IniciarCambioFac(tit, Elemento, ExistenciaFac, Codigo, Unidad, Dosificacion)\n";
        $javaC .= "   {\n";
        $javaC .= "	      Capa = xGetElementById(Elemento);\n";
        $javaC .= "       xMoveTo(Capa, xClientWidth()/2, xScrollTop()+60);\n";

        $javaC .= "       DatosFactor[0] = ExistenciaFac;\n";
        $javaC .= "       DatosFactor[1] = Codigo;\n";
        $javaC .= "       DatosFactor[2] = Unidad;\n";
        $javaC .= "       DatosFactor[3] = Dosificacion;\n";

        $javaC .= "       document.CambioFact.vectorFactor.value = DatosFactor;\n";
        $javaC .= "       document.getElementById('tituloFac').innerHTML = '<center>'+tit+'</center>';\n";
        $javaC .= "       document.getElementById('Unidad_Dos').innerHTML = '<center>'+Dosificacion+'</center>';\n";
        $javaC .= "       document.getElementById('errorFac').innerHTML = '';\n";

        $javaC .= "       ele = xGetElementById('tituloFac');\n";
        $javaC .= "       xResizeTo(ele, 280, 20);\n";
        $javaC .= "       xMoveTo(ele, 0, 0);\n";

        $javaC .= "       ele = xGetElementById('cerrarFac');\n";
        $javaC .= "       xResizeTo(ele,20, 20);\n";
        $javaC .= "       xMoveTo(ele, 280, 0);\n";
        $javaC .= "   }\n";

        $javaC .= "   function myOnDragStart(ele, mx, my)\n";
        $javaC .= "   {\n";
        $javaC .= "     window.status = '';\n";
        $javaC .= "     if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
        $javaC .= "     else xZIndex(ele, hiZ++);\n";
        $javaC .= "     ele.myTotalMX = 0;\n";
        $javaC .= "     ele.myTotalMY = 0;\n";
        $javaC .= "   }\n";

        $javaC .= "   function myOnDrag(ele, mdx, mdy)\n";
        $javaC .= "   {\n";
        $javaC .= "     if (ele.id == titulo) {\n";
        $javaC .= "       xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
        $javaC .= "     }\n";
        $javaC .= "     else {\n";
        $javaC .= "       xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
        $javaC .= "     }  \n";
        $javaC .= "     ele.myTotalMX += mdx;\n";
        $javaC .= "     ele.myTotalMY += mdy;\n";
        $javaC .= "   }\n";

        $javaC .= "   function myOnDragEnd(ele, mx, my)\n";
        $javaC .= "   {\n";
        $javaC .= "   }\n";

        $javaC.= "    function MostrarCapa(Elemento)\n";
        $javaC.= "    {\n;";
        $javaC.= "      capita = xGetElementById(Elemento);\n";
        $javaC.= "      capita.style.display = \"\";\n";
        $javaC.= "    }\n";

        $javaC.= "    function Cerrar(Elemento)\n";
        $javaC.= "    {\n";
        $javaC.= "      capita = xGetElementById(Elemento);\n";
        $javaC.= "      capita.style.display = \"none\";\n";
        $javaC.= "    }\n";

        $javaC.= "    function load_page()\n";
        $javaC.= "    {\n";
        $javaC.= "      location.reload();\n";
        $javaC.= "    }\n";

        $javaC.= "</script>\n";
        $html .= $javaC;
        
        
        $html .= "  <script> 
                        function mostrar_descripcion_tipo_riesgo(){
                            var selected_id = document.getElementById('tipo_riesgo');
                            var tipo_riesgo_id = selected_id.options[selected_id.selectedIndex].value;
                            
                            var contenedores = document.getElementsByName('descripcion_tipo_riesgo');


                            for (i = 1; i <= contenedores.length ; i++) { 
                                document.getElementById('descripcion_tipo_riesgo_'+i).style.display = 'none'   
                            }
                            
                            document.getElementById('descripcion_tipo_riesgo_'+tipo_riesgo_id).style.display = 'block'
                        }
						
						
						
                    </script> 
            
        ";
        

        /*
         * @Autor: Jonier Murillo Hurtado
         * @Fecha: Julio 15 de 2011
         * @Observaciones: 
         *  Porci�n de C�digo agregado, para la visualizaci�n de la ventana emergente, a trav�s de la cual
         *  se ingresara el Factor de conversi�n para cada medicamento.
         * <Hasta aqui>
         */
        $html .="<script>";
		
		
		
        $html .='function Confirmar_1(Formulario_Productos)
    {
		
		if (confirm("Desea guardar los cambios")) { 
			 
	 
		
		//buscar()
		var band=0;
		var cadena = [];
		var temp;
    
          if(Formulario_Productos.codigo_cum=="")
          {
          cadena.push(" Codigo CUM Vac�o\n");

          
          band=1;
          }
          
                   
          if(Formulario_Productos.codigo_alterno=="")
          {
          cadena.push("Codigo Alterno Vac�o\n");
          }

	      //ADICION: validacion campo cod_presenta no vacio (codigo admin. presentacion)
          if(Formulario_Productos.cod_presenta=="")
          {
          cadena.push("Codigo Administrativo Presentacion Vacio\n");
		  band=1;
          }
               
			   
          
          if(Formulario_Productos.codigo_barras=="")
          {
          cadena.push("Codigo De Barras Vac�o\n");
          band=1;
          }
          
          if(Formulario_Productos.descripcion=="")
          {
          cadena.push("Descripcion del producto\n");
          band=1;
          }
          
          if(Formulario_Productos.fabricante_id=="")
          {
          cadena.push("No Haz Seleccionado Un Fabricante\n");
          band=1;
          }
          
          if(Formulario_Productos.tipo_pais_id=="")
          {
          cadena.push("No Haz Seleccionado Un Pais\n");
          band=1;
          }
          
          if(!Formulario_Productos.sw_pos)
          {
          cadena.push("No Haz Seleccionado Si es Productos Pos o No POS\n");
          band=1;
          }
          
          
          
          if(Formulario_Productos.cod_acuerdo228_id=="" && !Formulario_Productos.cod_acuerdo228_id.disabled)
          {
          /*cadena.push("No Haz Asignado Codigo Acuerdo 228\n");*/
          cadena.push("No Haz Asignado Acuerdo 029 POS\n");
          band=1;
          }
          
          if(Formulario_Productos.rips_no_pos=="" && !Formulario_Productos.rips_no_pos.disabled)
          {
          cadena.push("Falta el Rips No POS\n");
          band=1;
          }
          
          if(Formulario_Productos.descripcion_abreviada=="")
          {
          cadena.push("Falta la Descripcion Abreviada!!!\n");
          band=1;
          }
		  
          if(Formulario_Productos.dci=="")
          {
          cadena.push("Falta la Denominacion Comun Internacional!\n");
          band=1;
          }		  
          
          if(Formulario_Productos.cod_forma_farmacologica=="")
          {
          cadena.push("No Haz Asignado Una Presentacion Comercial\n");
          band=1;
          }
          
          if(Formulario_Productos.unidad_id=="")
          {
          cadena.push("No Haz Asignado Tipo de Unidad al Producto\n");
          band=1;
          }
          
          if(Formulario_Productos.cantidad=="")
          {
          cadena.push("No ha Asignado una Cantidad x Unidad\n");
          band=1;
          }
          
          if(Formulario_Productos.cod_anatofarmacologico=="")
          {
          cadena.push("No ha Seleccionado un perfil Terapeutico\n");
          band=1;
          }
          //GERMAN
          if(Formulario_Productos.cod_unspsc=="")
          {
          cadena.push("No ha Seleccionado un codigo unspsc\n");
          band=1;
          }
          //FIN
          if(Formulario_Productos.mensaje_id=="")
          {
          cadena.push("No ha Asignado un Mensaje para el Producto\n");
          band=1;
          }
          
          if(Formulario_Productos.codigo_mindefensa=="")
          {
          cadena.push("Codigo de Min. Defensa, Vac�o\n");
          band=1;
          }
          
          
          if(Formulario_Productos.codigo_invima=="")
          {
          cadena.push("Codigo de Registro Invima, Vac�o\n");
          band=1;
          }
    
          if(Formulario_Productos.vencimiento_codigo_invima=="")
          {
          cadena.push("No Ha Seleccionado, La fecha de Vencimiento del Registro Invima\n");
          band=1;
          }
          
          if(Formulario_Productos.titular_reginvima_id=="")
          {
          cadena.push("No ha seleccionado un Titular del Registro Invima\n");
          band=1;
          }
          
          
          if(Formulario_Productos.porc_iva=="")
          {
          cadena.push("Porcentaje de Iva, Vac�o\n");
          band=1;
          }
          
		  if(Formulario_Productos.porc_iva<0)
          {
          cadena.push("Porcentaje de Iva, Debe Ser Mayor a Cero\n");
          band=1;
          }
          
          
          if(!Formulario_Productos.sw_generico)
          {
          cadena.push("No Ha Seleccionado si el Producto Es Generico o No!!!\n");
          band=1;
          }
          
          
          if(!Formulario_Productos.sw_venta_directa)
          {
          cadena.push("No Ha Seleccionado si el Producto Permite \n venta Directa (sin Formula) o No!!!\n");
          band=1;
          }
          
          
          if(Formulario_Productos.tipo_producto_id=="")
          {
          cadena.push("No Ha Seleccionado un tipo de producto!!!\n");
          band=1;
          }
          
 

          if(!Formulario_Productos.sw_regulado)
          {
          cadena.push("No Ha Seleccionado si el Producto Es Regulado o No!!!\n");
          band=1;
          }
          
          if(Formulario_Productos.tipo_riesgo=="")
          {
            cadena.push("No Ha Seleccionado un tipo de riesgo!!!\n");
            band=1;
          }
         
   
   
   
        var entrar = confirm("Continuar?")

        if (entrar) 
              {
                 
                 if(band==1)
                  {
                  alert(cadena);
                  alert("�Por favor, Diligenciar todos los Datos!");
                   } 
                      else
                      {
							
                            if(Formulario_Productos.sw_medicamento=="1")
                            {
						
                                //para evaluar si es Modificacion o Ingreso: token=1-> Ingreso, token=0-> Modificaci�n
                                if(Formulario_Productos.token=="1")
								
                                          {
                                           //alert("METODO A EJECUTAR CREACION PRODUCTOS(xajax_InsertarProductoInsumo) - (xajax_IngresoProductoMedicamento)");
										   xajax_InsertarProductoInsumo(Formulario_Productos,\'0\');
                                           //xajax_Volver(3);
                                           xajax_IngresoProductoMedicamento(Formulario_Productos);
                                          }
                                           else 
                                              {
											  // alert("METODO A EJECUTAR (xajax_ModificarProductoInsumo) - (xajax_ModProductoMedicamento)");
											  //console.log("Formulario_Productos ", Formulario_Productos);
											  //alert("Formulario_Productos == 1");
											 
                                               xajax_ModificarProductoInsumo(Formulario_Productos,\'0\');
					                           xajax_ModProductoMedicamento(Formulario_Productos);
											   //  xajax_ModificarProductoMedicamento(Formulario_Productos);
											    
                                               }
                                    
                            }
                               else
                               {
							
                                      //para evaluar si es Modificacion o Ingreso: token=1-> Ingreso, token=0-> Modificaci�n
                                      if(Formulario_Productos.token=="1")
                                          {
										  
										 //alert("METODO A EJECUTAR  CREACION INSUMO(xajax_InsertarProductoInsumo) - ()");
                                          xajax_InsertarProductoInsumo(Formulario_Productos,\'1\');
                                          }
                                          else
                                              {
											//alert("METODO A EJECUTAR (xajax_ModificarProductoInsumo) - ()");//2185V000933236
											//xajax_Volver(3);
                                              xajax_ModificarProductoInsumo(Formulario_Productos,\'1\');
                                              }
                               }
                      }
              }
               else
                {
                  return(false);
                }
                   
    }}';
        $html .="</script>";

		///FUNCION encargada de escribir en un input oculto el texto de un select
		$html .="<script>";
        $html .='function descripcionUnidadMedida()
          {
			
			    var lista = document.getElementById("unidad_id");
 
				// Obtener el índice de la opción que se ha seleccionado
				var indiceSeleccionado = lista.selectedIndex;
				// Con el índice y el array "options", obtener la opción seleccionada
				var opcionSeleccionada = lista.options[indiceSeleccionado];
				 
				// Obtener el valor y el texto de la opción seleccionada
				var textoSeleccionado = opcionSeleccionada.text;
				var valorSeleccionado = opcionSeleccionada.value;
				 
				//alert("Opción seleccionada: " + textoSeleccionado + "\n Valor de la opción: " + valorSeleccionado);
						//	 alert(document.FormularioProducto.unidad_id.value);
						
				document.getElementById("unidadMedidaDescripcion").value = textoSeleccionado;
						
		  
		  }';		
		$html .="</script>";
		
		///FUNCION encargada de escribir en un input oculto el texto de un select
		$html .="<script>";
        $html .='function descripcionPrincipioActivo()
          {
				
			    var lista = document.getElementById("cod_principio_activo");
 
				// Obtener el índice de la opción que se ha seleccionado
				var indiceSeleccionado = lista.selectedIndex;
				// Con el índice y el array "options", obtener la opción seleccionada
				var opcionSeleccionada = lista.options[indiceSeleccionado];
				 
				// Obtener el valor y el texto de la opción seleccionada
				var textoSeleccionado = opcionSeleccionada.text;
				var valorSeleccionado = opcionSeleccionada.value;
				 
			//	alert("Opción seleccionada: " + textoSeleccionado + "\n Valor de la opción: " + valorSeleccionado);
						//	 alert(document.FormularioProducto.unidad_id.value);
						
				document.getElementById("principioActivoDescripcion").value = textoSeleccionado;
						
		  
		  }';	
		$html .="</script>";		
		
		///FUNCION encargada de escribir en un input oculto el texto de un select
		$html .="<script>";
        $html .='function descripcionComercialFarmaco()
          {
			
			   // var lista = document.getElementById("cod_comerfarmacologico");
				  var lista = document.getElementById("presentacioncomercial_id");
				// Obtener el índice de la opción que se ha seleccionado
				var indiceSeleccionado = lista.selectedIndex;
				// Con el índice y el array "options", obtener la opción seleccionada
				var opcionSeleccionada = lista.options[indiceSeleccionado];
				 
				// Obtener el valor y el texto de la opción seleccionada
				var textoSeleccionado = opcionSeleccionada.text;
				var valorSeleccionado = opcionSeleccionada.value;
				 
				//alert("Opción seleccionada: " + textoSeleccionado + "\n Valor de la opción: " + valorSeleccionado);
						//	 alert(document.FormularioProducto.unidad_id.value);
						
				return document.getElementById("comercialFarmacoDescripcion").value = textoSeleccionado;						
		  
		  }';		
		$html .="</script>";	
		
		///FUNCION encargada de escribir en un input oculto el texto de un select
		$html .="<script>";
        $html .='function descripcionAnatoFarmacologico()
          {
			
			    var lista = document.getElementById("cod_anatofarmacologico");
 
				// Obtener el índice de la opción que se ha seleccionado
				var indiceSeleccionado = lista.selectedIndex;
				// Con el índice y el array "options", obtener la opción seleccionada
				var opcionSeleccionada = lista.options[indiceSeleccionado];
				 
				// Obtener el valor y el texto de la opción seleccionada
				var textoSeleccionado = opcionSeleccionada.text;
				var valorSeleccionado = opcionSeleccionada.value;
				 
				//alert("Opción seleccionada: " + textoSeleccionado + "\n Valor de la opción: " + valorSeleccionado);
						//	 alert(document.FormularioProducto.unidad_id.value);
						
				document.getElementById("anatoFarmacologicoDescripcion").value = textoSeleccionado;
						
		     
		  }';		
		$html .="</script>";
			
		//GERMAN			
		///FUNCION encargada de escribir en un input oculto el texto de un select
		$html .="<script>";
        $html .='function descripcionBienesServicios()
          {
			
			    var lista = document.getElementById("cod_unspsc");
 
				// Obtener el índice de la opción que se ha seleccionado
				var indiceSeleccionado = lista.selectedIndex;
				// Con el índice y el array "options", obtener la opción seleccionada
				var opcionSeleccionada = lista.options[indiceSeleccionado];
				 
				// Obtener el valor y el texto de la opción seleccionada
				var textoSeleccionado = opcionSeleccionada.text;
				var valorSeleccionado = opcionSeleccionada.value;
				 
				//alert("Opción seleccionada: " + textoSeleccionado + "\n Valor de la opción: " + valorSeleccionado);
						//	 alert(document.FormularioProducto.unidad_id.value);
						
				document.getElementById("codigoBienesDescripcion").value = textoSeleccionado;
						
		     
		  }';		
		$html .="</script>";
		//FIN	
		
		/**
		*+Descripcion Metodo encargado de escribir en un input oculto la descripcion
		*             de la lista que contiene los titulares
		*@fecha 24/08/2016
		**/
		$html .="<script>";
		$html .='function mostrar_descripcion_titular(){
					 
					 var lista = document.getElementById("titular_reginvima_id");
 
					// Obtener el índice de la opción que se ha seleccionado    
					var indiceSeleccionado = lista.selectedIndex; 
					// Con el índice y el array "options", obtener la opción seleccionada
					var opcionSeleccionada = lista.options[indiceSeleccionado];
					 
					// Obtener el valor y el texto de la opción seleccionada
					var textoSeleccionado = opcionSeleccionada.text;
					var valorSeleccionado = opcionSeleccionada.value;
					 
					//alert("Opción seleccionada: " + textoSeleccionado + "\n Valor de la opción: " + valorSeleccionado);
					//alert(document.FormularioProducto.unidad_id.value);
							
					document.getElementById("descripcion_titular_reginvima").value = textoSeleccionado;
					 
                          
		}';
		$html .="</script>";
		
		///FUNCION encargada de escribir en un input oculto el texto de un select
		$html .="<script>";
        $html .='function descripcionTratamientoEspecial()
          {
			
			    var lista = document.getElementById("tratamiento_id");
 
				// Obtener el índice de la opción que se ha seleccionado
				var indiceSeleccionado = lista.selectedIndex;
				// Con el índice y el array "options", obtener la opción seleccionada
				var opcionSeleccionada = lista.options[indiceSeleccionado];
				 
				// Obtener el valor y el texto de la opción seleccionada
				var textoSeleccionado = opcionSeleccionada.text;
				var valorSeleccionado = opcionSeleccionada.value;
				 
				//alert("Opción seleccionada: " + textoSeleccionado + "\n Valor de la opción: " + valorSeleccionado);
						//	 alert(document.FormularioProducto.unidad_id.value);
						
				document.getElementById("tratamientosEspecialesDescripcion").value = textoSeleccionado;
						
		  
		  }';		
		$html .="</script>";
		
		
		//Enfocando un campo de texto del formulario
		
		
        //para validar los checkbox del formulario del ingreso Medicamentos
        $html .="<script>";
        $html .="
          function asignar(dato,metodo)
          {
		  
          if(document.FormularioProductoMedicamento.NAI.checked==true)
             {
             var x=document.FormularioProductoMedicamento.NAI.value;
             document.FormularioProductoMedicamento.nivel_i.value=x;
            // alert(x);
             xajax_asignar('inv_producto_x_nivel_atencion',x,document.FormularioProductoMedicamento.codigo_producto.value,'producto_x_nivel,nivel,codigo_producto',metodo);
             }
              else
                  {
                  var x=document.FormularioProductoMedicamento.NAI.value;
                  //alert(x);
                  xajax_borrar('inv_producto_x_nivel_atencion',x,document.FormularioProductoMedicamento.codigo_producto.value,'producto_x_nivel');
                  document.FormularioProductoMedicamento.nivel_i.value='';
                  }
                  
                  
          if(document.FormularioProductoMedicamento.NAII.checked==true)
             {
             var x=document.FormularioProductoMedicamento.NAII.value;
            // alert(x);
             xajax_asignar('inv_producto_x_nivel_atencion',x,document.FormularioProductoMedicamento.codigo_producto.value,'producto_x_nivel,nivel,codigo_producto',metodo);
             document.FormularioProductoMedicamento.nivel_ii.value=x;
             }
              else
                  {
                  var x=document.FormularioProductoMedicamento.NAII.value;
                  //alert(x);
                  xajax_borrar('inv_producto_x_nivel_atencion',x,document.FormularioProductoMedicamento.codigo_producto.value,'producto_x_nivel');
                  document.FormularioProductoMedicamento.nivel_ii.value='';
                  }
                  
          if(document.FormularioProductoMedicamento.NAIII.checked==true)
             {
             var x=document.FormularioProductoMedicamento.NAIII.value;
            // alert(x);
             xajax_asignar('inv_producto_x_nivel_atencion',x,document.FormularioProductoMedicamento.codigo_producto.value,'producto_x_nivel,nivel,codigo_producto',metodo);
             document.FormularioProductoMedicamento.nivel_iii.value=x;
             }
              else
                  {
                  var x=document.FormularioProductoMedicamento.NAIII.value;
                  //alert(x);
                  xajax_borrar('inv_producto_x_nivel_atencion',x,document.FormularioProductoMedicamento.codigo_producto.value,'producto_x_nivel');
                  document.FormularioProductoMedicamento.nivel_iii.value='';
                  }
          
          if(document.FormularioProductoMedicamento.NAIV.checked==true)
             {
             var x=document.FormularioProductoMedicamento.NAIV.value;
            // alert(x);
             xajax_asignar('inv_producto_x_nivel_atencion',x,document.FormularioProductoMedicamento.codigo_producto.value,'producto_x_nivel,nivel,codigo_producto',metodo);
             document.FormularioProductoMedicamento.nivel_iv.value=x;
             }
              else
                  {
                  var x=document.FormularioProductoMedicamento.NAIV.value;
                  //alert(x);
                  xajax_borrar('inv_producto_x_nivel_atencion',x,document.FormularioProductoMedicamento.codigo_producto.value,'producto_x_nivel');
                  document.FormularioProductoMedicamento.nivel_iv.value='';
                  }
          
          }
          
		  //FUNCION PARA AÑADIR NIVELES DE USO
          function incluir(dato, metodo)
          {
         // alert(metodo);
          if(document.FormularioProductoMedicamento.NUH.checked==true)
              {
              var y=document.FormularioProductoMedicamento.NUH.value;
              xajax_asignar('inv_producto_x_nivel_de_uso',y,document.FormularioProductoMedicamento.codigo_producto.value,'producto_x_nivel_de_uso,nivel_de_uso_id,codigo_producto',metodo);
              document.FormularioProductoMedicamento.nivelu_h.value=y;
              //alert(y);
              }
              else
                  {
                  var y=document.FormularioProductoMedicamento.NUH.value;
                  xajax_borrar('inv_producto_x_nivel_de_uso',y,document.FormularioProductoMedicamento.codigo_producto.value,'producto_x_nivel_de_uso');
                  document.FormularioProductoMedicamento.nivelu_h.value='';
                  }
                  
           

          if(document.FormularioProductoMedicamento.NUE.checked==true)
              {
              var y=document.FormularioProductoMedicamento.NUE.value;
              xajax_asignar('inv_producto_x_nivel_de_uso',y,document.FormularioProductoMedicamento.codigo_producto.value,'producto_x_nivel_de_uso,nivel_de_uso_id,codigo_producto',metodo);
             document.FormularioProductoMedicamento.nivelu_e.value=y;
              }
              else
                  {
                  var y=document.FormularioProductoMedicamento.NUE.value;
                  xajax_borrar('inv_producto_x_nivel_de_uso',y,document.FormularioProductoMedicamento.codigo_producto.value,'producto_x_nivel_de_uso');
                  document.FormularioProductoMedicamento.nivelu_e.value='';
                  }
                  
          if(document.FormularioProductoMedicamento.NUG.checked==true)
              {
              var y=document.FormularioProductoMedicamento.NUG.value;
              xajax_asignar('inv_producto_x_nivel_de_uso',y,document.FormularioProductoMedicamento.codigo_producto.value,'producto_x_nivel_de_uso,nivel_de_uso_id,codigo_producto',metodo);
             document.FormularioProductoMedicamento.nivelu_g.value=y;
              }
              else
                  {
                  var y=document.FormularioProductoMedicamento.NUG.value;
                  xajax_borrar('inv_producto_x_nivel_de_uso',y,document.FormularioProductoMedicamento.codigo_producto.value,'producto_x_nivel_de_uso');
                  document.FormularioProductoMedicamento.nivelu_g.value='';
                  }
                  
          
          if(document.FormularioProductoMedicamento.NUC.checked==true)
              {
              var y=document.FormularioProductoMedicamento.NUC.value;
              xajax_asignar('inv_producto_x_nivel_de_uso',y,document.FormularioProductoMedicamento.codigo_producto.value,'producto_x_nivel_de_uso,nivel_de_uso_id,codigo_producto',metodo);
             document.FormularioProductoMedicamento.nivelu_c.value=y;
              }
              else
                  {
                  var y=document.FormularioProductoMedicamento.NUC.value;
                  xajax_borrar('inv_producto_x_nivel_de_uso',y,document.FormularioProductoMedicamento.codigo_producto.value,'producto_x_nivel_de_uso');
                  document.FormularioProductoMedicamento.nivelu_c.value='';
                  }
          
          }
  
  
  
  ";
        $html .="</script>";




        //para desplegar el Textarea de farmacovigilancia
        $html .= "
  <script>

function mostrardiv() {

div = document.getElementById('FarmacoVigilancia');

div.style.display = \"\";


}

function cerrar() {

div = document.getElementById('FarmacoVigilancia');
div.style.display='none';
document.FormularioProductoMedicamento.descripcion_alerta.value=\"\";

}

</script>
";

        //Confirmar 2 es para validar el formulario de los medicamento

        $html .="<script>";
        $html .='function Confirmar_2(FormularioProductoMedicamento)
    {
		
		//NIVELES DE ATENCION
	    var nivelesAtencion =[];
	    var cadenaNivelesAtencion = "";
		var codigoProducto = "";
			if(FormularioProductoMedicamento.NAI != undefined){	   
				nivelesAtencion.push(FormularioProductoMedicamento.NAI);	
			}
			if(FormularioProductoMedicamento.NAII != undefined){	   
				nivelesAtencion.push(FormularioProductoMedicamento.NAII);
			}
			if(FormularioProductoMedicamento.NAIII != undefined){   
				nivelesAtencion.push(FormularioProductoMedicamento.NAIII);
			}
			if(FormularioProductoMedicamento.NAIV != undefined){   
				nivelesAtencion.push(FormularioProductoMedicamento.NAIV);
			}
	
	cadenaNivelesAtencion = nivelesAtencion.join(",");
	codigoProducto = FormularioProductoMedicamento.codigo_producto;
	xajax_GuardarNivelesDeAtencion(cadenaNivelesAtencion,codigoProducto,"actualizarNivelesAtencion","atencion");
	
		//NIVELES DE USO
		var nivelesUso =[];
		var cadenaNivelesUso = "";
		
			if(FormularioProductoMedicamento.NUH != undefined){	   
				nivelesUso.push(FormularioProductoMedicamento.NUH);	
			}
			if(FormularioProductoMedicamento.NUE != undefined){	   
				nivelesUso.push(FormularioProductoMedicamento.NUE);
			}
			if(FormularioProductoMedicamento.NUG != undefined){   
				nivelesUso.push(FormularioProductoMedicamento.NUG);
			}
			if(FormularioProductoMedicamento.NUC != undefined){   
				nivelesUso.push(FormularioProductoMedicamento.NUC);
			}
	
	cadenaNivelesUso = nivelesUso.join(",");
	xajax_GuardarNivelesDeAtencion(cadenaNivelesUso,codigoProducto,"actualizarNivelesUso","uso");
	
	
	
		//VIAS DE ADMINISTRACION
		var idViaAdministracionId = document.getElementById("idViaAdministracionId"); 
		var idViaAdministracionIdLenght = idViaAdministracionId.options.length;
		var nivelesViaAdministracion =[];
		var cadenanivelesViaAdministracion = "";
		
		for(var i=0; i<idViaAdministracionIdLenght; i++){
			
			nivelesViaAdministracion.push(idViaAdministracionId.options[i].value);
			
		}
		cadenanivelesViaAdministracion = nivelesViaAdministracion.join(",");
		xajax_GuardarNivelesDeAtencion(cadenanivelesViaAdministracion,codigoProducto,"actualizarViasAdmin","Vias de administracion");
		
		
	
	
	
    var band=0;
    var cadena = [];
    var temp;
    
          
          if(!FormularioProductoMedicamento.sw_manejo_luz)
          {
          cadena.push("No Ha Seleccionado Si el Producto posee Manejo Luz  o No\n");
          band=1;
          }
          
           if(FormularioProductoMedicamento.via_administracion_id=="")
          {
          cadena.push("No Ha Seleccionado una Via de Administracion\n");
          band=1;
          }
          
          if(!FormularioProductoMedicamento.sw_farmacovigilancia)
          {
          cadena.push("No Ha Seleccionado Si el Producto presenta Farmacovigilancia o No\n");
          band=1;
          }
          
          if(FormularioProductoMedicamento.concentracion=="")
          {
          cadena.push("El medicamento debe Tener una Concentracion!!!\n");
          band=1;
          }
          
                    
          if(FormularioProductoMedicamento.nivel_i=="" && FormularioProductoMedicamento.nivel_ii=="" && FormularioProductoMedicamento.nivel_iii=="" && FormularioProductoMedicamento.nivel_iv=="")
          {
          cadena.push("Debe seleccionar al menos 1 Nivel de Atenci�n!!!\n");
          band=1;
          }
         
         if(FormularioProductoMedicamento.nivelu_h=="" && FormularioProductoMedicamento.nivelu_e=="" && FormularioProductoMedicamento.nivelu_g=="" && FormularioProductoMedicamento.nivelu_c=="")
          {
          cadena.push("Debe seleccionar al menos 1 Nivel de Uso!!!\n");
          band=1;
          }
		  
		  if(FormularioProductoMedicamento.dias_previos_vencimiento=="" || FormularioProductoMedicamento.dias_previos_vencimiento < 0)         {
          cadena.push("Debe Diligenciar un dia Previo al Vencimiento correcto!!!\n");
          band=1;
          }
          
          /*
          if(FormularioProductoMedicamento.sin_items=="0")
          {
          cadena.push("Debes seleccionar al menos Una Especialidad!!!\n");
          band=1;
          }*/
   
   
        var entrar = confirm("Continuar?")

        if (entrar) 
              {
                
                 if(band==1)
                  {
                  //alert(cadena);
                  alert("�Por favor, Diligenciar todos los Datos!");
                   } 
                      else
                      { 
                               if(FormularioProductoMedicamento.token=="1"){
									xajax_InsertarProductoMedicamento(FormularioProductoMedicamento);
                                  }else
                                      {
										
                                             if(FormularioProductoMedicamento.medicamento_vacio=="0")
                                                {
												
                                                xajax_InsertarProductoMedicamento(FormularioProductoMedicamento);
                                                xajax_ModificarProductoMedicamento(FormularioProductoMedicamento);
                                                }
                                                else
                                                {
												
                                              xajax_ModificarProductoMedicamento(FormularioProductoMedicamento);
                                                }
                                      }
                      }
                  
              
              }
                else
                {
                  return(false);
                }   
                   
    }';
        $html .="</script>";






        $html .="<script>";
        $html .= "function buscar()";
        $html .="{";
        $html .="var grupo_id= document.BuscadorProductos.grupo_id.value;";  
        $html .="var clase_id= document.BuscadorProductos.clase_id.value;";
        $html .="var subclase_id= document.BuscadorProductos.subclase_id.value;";
        $html .="var cod_anatofarmacologico= document.BuscadorProductos.cod_anatofarmacologico.value;";
        $html .="var descripcion= document.BuscadorProductos.descripcion.value;";
        $html .="var codigo_barras= document.BuscadorProductos.codigo_barras.value;";
        $html .="var codigo_producto= document.BuscadorProductos.codigo_producto.value;";
        $html .= "var tipo_producto = document.BuscadorProductos.tipo_producto.value;";

        $html .="xajax_Productos_CreadosBuscados(grupo_id,clase_id,subclase_id,descripcion,cod_anatofarmacologico,codigo_barras,codigo_producto,tipo_producto);";
        $html .="}";
        $html .="</script>";






        $html .="<script>";
        $html .="function calendario()
    {
    Calendar.setup({
              inputField : \"calendar-field\",
              trigger    : \"calendar-trigger\",
              dateFormat : \"%Y-%m-%d\",
              onSelect   : function() { this.hide() }
               });
               //alert('Hola');
               
    }";
        $html .="</script>";



        $html .="<script>";
        $html .= "  function Paginador_1(CodigoGrupo,NombreGrupo,Sw_Medicamento,offset)\n";
        $html .= "  {";
        $html .= "    xajax_Continuar_Con_Clases(CodigoGrupo,NombreGrupo,Sw_Medicamento,offset);\n";
        $html .= "  }\n";
        $html .="</script>";


        $html .="<script>";
        $html .= "  function Paginador_2(NombreGrupo,CodigoGrupo,Nombre,Codigo,Sw_Medicamento,offset)\n";
        $html .= "  {";
        $html .= "    xajax_BusquedaClasesAsignadas(NombreGrupo,CodigoGrupo,Nombre,Codigo,Sw_Medicamento,offset);\n";
        $html .= "  }\n";
        $html .="</script>";


        $html .="<script>";
        $html .= "  function Paginador_3(CodigoGrupo,CodigoClase,NombreClase,NombreGrupo,Sw_Medicamento,offset)\n";
        $html .= "  {";
        $html .= "    xajax_Continuar_Con_SubClases(CodigoGrupo,CodigoClase,NombreClase,NombreGrupo,Sw_Medicamento,offset);\n";
        $html .= "  }\n";
        $html .="</script>";

        $html .="<script>";
        $html .= "  function Paginador_4(NombreGrupo,NombreClase,CodigoGrupo,CodigoClase,Nombre,Codigo,Sw_Medicamento,offset)\n";
        $html .= "  {";
        $html .= "    xajax_BusquedaSubClasesAsignadas(NombreGrupo,NombreClase,CodigoGrupo,CodigoClase,Nombre,Codigo,Sw_Medicamento,offset);\n";
        $html .= "  }\n";
        $html .="</script>";

        $html .="<script>";
        $html .= "  function Paginador_5(codigo_producto,offset)\n";
        $html .= "  {";
        $html .= "    xajax_ListaEspecialidadxProducto(codigo_producto,offset);\n";
        $html .= "  }\n";
        $html .="</script>";

        $html .="<script>";
        $html .= "  function Paginador_6(param,offset)\n";
        $html .= "  {";
        $html .= "    xajax_Productos_Creados(param,offset);\n";
        $html .= "  }\n";
        $html .="</script>";

        $html .="<script>";
        $html .= "  function Paginador_7(grupo_id,clase_id,subclase_id,descripcion,cod_anatofarmacologico,codigo_barras,codigo_producto,offset)\n";
        $html .= "  {";
        $html .= "    xajax_Productos_CreadosBuscados(grupo_id,clase_id,subclase_id,descripcion,cod_anatofarmacologico,codigo_barras,codigo_producto,offset);\n";
        $html .= "  }\n";
        $html .="</script>";

        //Prueba Lector de Codigos de Barras
        $html .= "
            <script language='javascript'>
                
                document.onkeyup = Buscar_CodigoBarras;   
                function Buscar_CodigoBarras(e)
                    {
                    var valor=document.BuscadorProductos.codigo_barras.value;
                    KeyID = (window.event) ? event.keyCode : e.keyCode;
                    //tecla=(document.all) ? e.keyCode : e.which;

                            if(KeyID==13) 
                            {
                              //window.e.keyCode=0;
                              xajax_Productos_CreadosBuscados('','','',document.getElementById('nombre_producto').value,'',valor);
                              //alert('has apretado intro');

                            }

                      }

           </script>";










        $html .= "
            <script language='javascript'>
                    function getKey(e,valor)
                    {
                    tecla=(document.all) ? e.keyCode : e.which;

                            if(tecla==13) 
                            {
                              window.e.keyCode=0;
                              
                              xajax_Buscar_ProductoConCodigoBarras(valor);
                              //alert('has apretado intro');

                            }

                      }

           </script>";


        $html .= "<script>";
        $html .= "function Confirmar_Modificar(Formulario)";
        $html .= "{";
        $html .= "  if(Formulario.select_grupo_id==\"\")";
        $html .= "    {";
        $html .= "    alert(\"Debe Seleccionar Un Grupo Por Favor\");";
        $html .= "    return false;";
        $html .= "    }";
        $html .= "  if(Formulario.select_clase_id==\"\")";
        $html .= "    {";
        $html .= "    alert(\"Debe Seleccionar Una Clase Por Favor\");";
        $html .= "    return false;";
        $html .= "    }";
        $html .= "  if(Formulario.select_subclase_id==\"\")";
        $html .= "    {";
        $html .= "    alert(\"Debe Seleccionar Una SubClase Por Favor\");";
        $html .= "    return false;";
        $html .= "    }";
        $html .= "  var entrar = confirm(\"Atencion: Con Esta Accion, Cambia la Codificacion del Producto. Desea Continuar?\");";
        $html .= "    if (entrar) ";
        $html .= "    {";
        //$html .= "      alert('ok');";
        $html .= "      xajax_Guardar_NuevaClasificacion(Formulario);";
        $html .= "    }";
        $html .= "      else";
        $html .= "      {";
        $html .= "      return false;";
        $html .= "      }";
        $html .= "}";
        $html .= "</script>";

        $html .= ThemeAbrirTabla('CREACION DE PRODUCTOS');

        //URL CREACION DE PRODUCTO
        $Url = ModuloGetURL("app", "Inv_CodificacionProductos", "controller", "Crear_Productos");
        //FIN URL    

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
        $html .= "<center>";
        $html .= "	<table width=\"100%\" align=\"center\">\n";
        $html .= "		<tr>\n";
        $html .= "			<td>\n";
        $html .= "				<table width=\"100%\" align=\"center\">\n";
        $html .= "					<tr>\n";
        $html .= "						<td>\n";
        $html .= "							<div class=\"tab-pane\" id=\"creacion_productos\">\n";
        $html .= "								<script>	tabPane = new WebFXTabPane( document.getElementById( \"creacion_productos\" )); </script>\n";
        $html .= "								<div class=\"tab-page\" id=\"grupos_inventarios\">\n";
        $html .= "									<h2 class=\"tab\">GRUPOS INVENTARIOS</h2>\n";
        $html .= "									<script>	tabPane.addTabPage( document.getElementById(\"grupos_inventarios\")); </script>\n";


        $html .= "<div id=\"Listado\">\n"; //DIV PARA EL LISTADO DE GRUPOS CREADOS
        $html .= "<center>";
        $html .= "<fieldset class=\"fieldset\" style=\"width:40%\" align=\"center\">\n";
        $html .= "  <legend class=\"normal_10AN\" >SELECCIONE EL GRUPO</legend>\n";

        $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";

        $html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td width=\"5%\">CODIGO</td>\n";
        $html .= "      <td width=\"30%\">NOMBRE DEL GRUPO</td>\n";
        $html .= "      <td width=\"2%\">MEDICAMENTOS</td>\n";
        $html .= "      <td width=\"3%\">CONTINUAR...";


        $html .= "</td>";

        $html .= "    </tr>\n";

        $est = "modulo_list_claro";
        $bck = "#DDDDDD";
        foreach ($Grupos as $key => $grp)
        {
            ($est == "modulo_list_claro") ? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
            ($bck == "#CCCCCC") ? $bck = "#DDDDDD" : $bck = "#CCCCCC";

            $html .= "    <tr class=\"" . $est . "\" onmouseout=mOut(this,\"" . $bck . "\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
            $html .= "      <td >" . $grp['grupo_id'] . "</td><td>" . $grp['descripcion'] . " </td>\n";
            $html .= "      </td>";
            if ($grp['sw_medicamento'] == 1)
                $html .= "<td align=\"center\"><img title=\"GRUPO PARA MEDICAMENTOS\" src=\"" . GetThemePath() . "/images/si.png\" border=\"0\"></td>\n";
            else
                $html .= "<td align=\"center\"><img title=\"GRUPO DIFERENTE A MEDICAMENTOS\" src=\"" . GetThemePath() . "/images/no.png\" border=\"0\"></td>\n";

            $html .= "      <td align=\"center\">\n";
            $html .= "        <a href=\"#\" onclick=\"xajax_Continuar_Con_Clases('" . $grp['grupo_id'] . "','" . $grp['descripcion'] . "','" . $grp['sw_medicamento'] . "')\">\n";
            $html .= "          <img title=\"CONTINUAR...\" src=\"" . GetThemePath() . "/images/flecha.png\" border=\"0\">\n";
            // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
            $html .= "        </a>\n";
            $html .= "      </td>\n";
        }
        $html .= "    </table>\n";
        $html .= "</fieldset>\n";
        $html .="</center>";

        $html .= "      <br>\n";

        $html .= "								  </div>\n";
        $html .= "								</div>\n";
        $html .= "								<div class=\"tab-page\" id=\"clases_inventarios\">\n";
        $html .= "									<h2 class=\"tab\">CLASES INVENTARIOS</h2>\n";
        $html .= "									<script>	tabPane.addTabPage( document.getElementById(\"clases_inventarios\")); </script>\n";

        //$html .="<center>";
        //$html .= "<fieldset class=\"fieldset\" style=\"width:40%\" align=\"center\">\n";
        // $html .= "  <legend class=\"normal_10AN\" >OPCIONES DE SELECCION</legend>\n";
        //$html .= "  <table border=\"0\"  align=\"center\" >\n";
        //$html .= "      <td align=\"center\" width=\"50%\">\n";
        //Ac� se desplegar� Un buscador y un listado de Clases asociadas a un Grupo Seleccionado anteriormente
        $html .="<div id=\"clases\">";
        $html .="</div>";
        //     $html .= "      </td>\n";
        //   $html .= "      <td align=\"center\" width=\"50%\">\n";
//Ac� se desplegar� Un buscador y un listado de SubClases asociadas a un Grupo y una Clase Seleccionados anteriormente
        $html .= "								</div>\n";
        $html .= "								<div class=\"tab-page\" id=\"subclases_inventarios\">\n";
        $html .= "									<h2 class=\"tab\">SUBCLASES INVENTARIOS</h2>\n";
        $html .= "									<script>	tabPane.addTabPage( document.getElementById(\"subclases_inventarios\")); </script>\n";


        $html .="<div id=\"subclases\">";
        $html .="</div>";
        //$html .= "      </td>\n";

        $html .= "								</div>\n";

        /*
         * Tab para el Formulario de Ingreso de Productos
         */
        $html .= "								<div class=\"tab-page\" id=\"ingreso\">\n";
        $html .= "									<h2 class=\"tab\">FORMULARIO INGRESO</h2>\n";
        $html .= "									<script>	tabPane.addTabPage( document.getElementById(\"ingreso\")); </script>\n";
        $html .="<div id=\"formulario_ingreso\">";
        $html .="</div>";
        $html .= "								</div>\n";
        //Ac� se desplegar� el formulario de Ingreso de Productos


        $html .= "								<div class=\"tab-page\" id=\"productos_inventarios\">\n";
        $html .= "									<h2 class=\"tab\">PRODUCTOS INVENTARIOS</h2>\n";
        $html .= "									<script>	tabPane.addTabPage( document.getElementById(\"productos_inventarios\")); </script>\n";




        $html .="<div id=\"IngresoProductos\">";
        $html .="</div>";




        $html .="<script>";
        $html .= "xajax_Productos_Creados();";
        $html .="</script>";

        //Captura Info del Buscador y se env�a a xajax
//Buscador de Productos Creados
        $html .= "<form name=\"BuscadorProductos\" method=\"POST\" action=\"#\">";
        $html .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $html .= "<tr class=\"modulo_table_list_title\">";
        $html .= "<td colspan=\"6\" align=\"center\">";
        $html .= "BUSCADOR";
        $html .= "</td>";
        $html .= "</tr>";


        $SelectPerfiles = '<SELECT NAME="cod_anatofarmacologico" SIZE="1" class="select" style="width:100%;height:100%">';
        $SelectPerfiles .= '<OPTION VALUE="">Todos</OPTION>';
        foreach ($PerfilesTerapeuticos as $key => $pt)
        {
            $SelectPerfiles .= '<OPTION VALUE="' . $pt['codigo'] . '" >' . $pt['descripcion'] . '</OPTION>';
        }
        $SelectPerfiles .='</SELECT>';


        $SelectGrupos = '<SELECT NAME="grupo_id" SIZE="1" class="select" style="width:100%;height:100%">';
        $SelectGrupos .= '<OPTION VALUE="" onclick="xajax_buscar_clases_grupo(this.value);"></OPTION>';
        foreach ($Grupos as $key => $gru)
        {
            $SelectGrupos .= '<OPTION VALUE="' . $gru['grupo_id'] . '" onclick="xajax_buscar_clases_grupo(this.value);">' . $gru['grupo_id'] . " " . $gru['descripcion'] . '</OPTION>';
        }
        $SelectGrupos .='</SELECT>';

        $html .= "<tr class=\"modulo_list_claro\">";
        $html .= "<td class=\"formulacion_table_list\" width=\"10%\" >";
        $html .= "GRUPO :";
        $html .= "</td>";
        $html .= "<td>";
        $html .= $SelectGrupos;
        $html .= "</td>";

        $html .= "<td class=\"formulacion_table_list\" width=\"15%\">";
        $html .= "CLASE : ";
        $html .= "</td>";
        $html .= "<td width=\"20%\">";
        $html .= "<div id=\"select_clases\">Seleccione Grupo...
    <input type=\"hidden\" name=\"clase_id\" value=\"\">
    </div> ";
        $html .= "</td>";

        $html .= "<td class=\"formulacion_table_list\" width=\"10%\">";
        $html .= "SUBCLASE : ";
        $html .= "</td>";
        $html .= "<td>";
        $html .= "<div id=\"select_subclases\">
    Seleccione Grupo y Clase...
    <input type=\"hidden\" name=\"subclase_id\" value=\"\">
    </div> ";
        $html .= "</td>";
        $html .= "</tr>";

        $html .= "<tr class=\"modulo_list_claro\">";
        $html .= "<td class=\"formulacion_table_list\">";
        $html .= "CODIGO PRODUCTO : ";
        $html .= "</td>";
        $html .= "<td>";
        $html .= "<input class=\"input-text\" type=\"text\" name=\"codigo_producto\" id=\"codigo_producto\"  maxlength=\"80\" onkeyup=\"this.value=this.value.toUpperCase();\" style=\"width:100%;height:100%\" >";
        $html .= "</td>";

        $html .= "<td class=\"formulacion_table_list\">";
        $html .= "PERFIL TERAPEUTICO : ";
        $html .= "</td>";
        $html .= "<td>";
        $html .= $SelectPerfiles;
        $html .= "</td>";

        $html .= "<td class=\"formulacion_table_list\">";
        $html .= "CODIGO DE BARRAS:";
        $html .= "</td>";
        $html .= "<td>";
        $html .= "<input class=\"input-text\" type=\"text\" name=\"codigo_barras\" maxlength=\"30\" onkeyup=\"Buscar_CodigoBarras(this.value);\" style=\"width:100%;height:100%\">";
        $html .= "</td>";
        $html .= "</tr>";

        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "<td class=\"formulacion_table_list\">";
        $html .= "  DESCRIPCION : ";
        $html .= "</td>";
        $html .= "<td colspan=\"1\">";
        $html .= "<input class=\"input-text\" type=\"text\" name=\"descripcion\" id=\"nombre_producto\"  maxlength=\"100\" onkeyup=\"this.value=this.value.toUpperCase();\" style=\"width:100%;height:100%\" >";
        $html .= "</td>";
		$html .= "<td class=\"formulacion_table_list\">";
        $html .= "TIPO PRODUCTO : ";
        $html .= "</td>";
        $html .= "<td colspan=\"1\">";

        $html .= "<select class = 'select' id='tipo_producto'>
                            <option value = ''>--- Seleccionar ---</option>
                             <option value = '1'>Regulado</option>
                              <option value = '0   '>No Regulado</option>
                      </select>
        ";
        $html .= "</td>";
        $html .= "      <td class=\"label\" align=\"center\" colspan=\"4\">";
        $html .= "     <input class=\"input-submit\" type=\"button\" value=\"BUSCAR\" name=\"boton\" onclick=\"buscar();\" >";
        $html .= "      </td>";
        $html .= "      </tr>";


        $html .="</table>
			</form>";


        $html .= "<div id=\"Listado_Productos\">\n";
        $html .= "</div>"; //CIERRA DIV DE LISTADO DE PRODUCTOS CREADOS

        $html .= "								</div>\n";


        $html .= "							</div>\n";
        $html .= "						</td>\n";
        $html .= "					</tr>\n";
        $html .= "				</table>\n";
        $html .= "			</td>\n";
        $html .= "		</tr>\n";
        $html .= "  </table>\n";
        $html .= "  <form name=\"forma\" action=\"" . $accion . "\" method=\"post\">\n";
        $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
        $html .= "    <tr>\n";
        $html .= "      <td align=\"center\">\n";
        $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "  </table>\n";
        $html .= "</form>\n";
        $html .= $this->CrearVentana(600, "CREAR PRODUCTO NUEVO");

        $html .= ThemeCerrarTabla();
        //Se dirija a la ultima pesta�ita
        $html .= "<script>";
        $html .= "tabPane.setSelectedIndex(4);";
        $html .= "</script>";




        return($html);
    }

    // CREAR LA CAPITA
    function CrearVentana($tmn, $Titulo)
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
        $html .= "  function MostrarSpan()\n";
        $html .= "  { \n";
        $html .= "    try\n";
        $html .= "    {\n";
        $html .= "      e = xGetElementById('Contenedor');\n";
        $html .= "      e.style.display = \"\";\n";
        $html .= "      Iniciar();\n";
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

        $html .= "  function Iniciar()\n";
        $html .= "  {\n";
        $html .= "    contenedor = 'Contenedor';\n";
        $html .= "    titulo = 'titulo';\n";
        $html .= "    ele = xGetElementById('Contenido');\n";
        $html .= "    xResizeTo(ele," . $tmn . ", 'auto');\n";
        $html .= "    ele = xGetElementById(contenedor);\n";
        $html .= "    xResizeTo(ele," . $tmn . ", 'auto');\n";
        $html .= "    xMoveTo(ele, xClientWidth()/4, xScrollTop()+20);\n";
        $html .= "    ele = xGetElementById(titulo);\n";
        $html .= "    xResizeTo(ele," . ($tmn - 20) . ", 20);\n";
        $html .= "    xMoveTo(ele, 0, 0);\n";
        $html .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
        $html .= "    ele = xGetElementById('cerrar');\n";
        $html .= "    xResizeTo(ele,20, 20);\n";
        $html .= "    xMoveTo(ele," . ($tmn - 20) . ", 0);\n";
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
        $html .= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;text-align:center;\">" . $Titulo . "</div>\n";
        $html .= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
        $html .= "  <div id='Contenido' class='d2Content'>\n";
        //En ese espacio se visualiza la informacion extraida de la base de datos.
        $html .= "  </div>\n";
        $html .= "</div>\n";



        return $html;
    }

}

?>
