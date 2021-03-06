<?php

/**
 * $Id: app_Facturacion_userclasses_HTML.php,v 1.96 2007/04/12 16:13:23 luis Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo visual de la facturacion.
 */

/**
* Clase app_Facturacion_userclasses_HTML
*
* Contiene los metodos en html para la presentacion
*/
IncludeClass("ClaseHTML");
IncludeClass('Facturacion','','app','Facturacion');
class app_Facturacion_userclasses_HTML extends app_Facturacion_user
{
  /**
  * Constructor de la clase app_Facturacion_userclasses_HTML
  * El constructor de la clase app_Facturacion_userclasses_HTML se encarga de llamar
  * a la clase app_Facturacion_user quien se encarga de el tratamiento
  * de la base de datos.
  * @return boolean
  */
  function app_Facturacion_userclasses_HTML()
  {
        $this->salida='';
        $this->app_Facturacion_user();
        return true;
  }


  function SetStyle($campo)
  {
        if ($this->frmError[$campo] || $campo=="MensajeError"){
          if ($campo=="MensajeError"){
            return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
          }
          return ("label_error");
        }
      return ("label");
  }
  /**
  *
  */
  function FormaMenus($opcion)
  {
        $this->salida .= ThemeAbrirTabla('MENUS CUENTAS');
        $this->salida .= "            <br>";
        $this->salida .= "            <table width=\"40%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
        $this->salida .= "               <tr>";
        $this->salida .= "                  <td align=\"center\" class=\"modulo_table_list_title\">MENU CUENTAS</td>";
        $this->salida .= "               </tr>";
        $this->salida .= "               <tr>";
        $accion=ModuloGetURL('app','Facturacion','user','main',array('SWCUENTAS'=>'Cuentas'));
        $this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accion\">Cuentas (Activas - Inactivas)</a></td>";
        $this->salida .= "               </tr>";        
        $accion=ModuloGetURL('app','Facturacion','user','CallFrmListaPacientesConSalida');
        $this->salida .= "               <tr>";
        $this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accion\">Listado Pacientes con Salida</a></td>";
        $this->salida .= "               </tr>";
				$accionR=ModuloGetURL('app','Facturacion','user','FrmGeneracionReportes');
        $this->salida .= "               <tr>";
        $this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionR\">Generacion de Reportes</a></td>";
        $this->salida .= "               </tr>";
        $this->salida .= "           </table>";
				if(SessionGetVar("Opciones") == "1")
					$accion = ModuloGetURL('system','Menu','user','main');
        else
					$accion = ModuloGetURL('app','Facturacion','user','PermisosUsuario');
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></p>";
        $this->salida .= "</form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }
  /**
  *
  */
    function EncabezadoEmpresa($Caja)
    {
        $datos = $this->DatosEncabezadoEmpresa($_SESSION['CUENTAS']['CAJA']);

        if(!empty($datos))
        {
            if(!$Caja)
                $var='DEPARTAMENTO';
            else
                $var='CAJA';

            $this->salida .= "<table  border=\"0\" class=\"modulo_table_list\" width=\"80%\" align=\"center\" >\n";
            $this->salida .= "  <tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "      <td>EMPRESA</td>\n";
            $this->salida .= "      <td>CENTRO UTILIDAD</td>\n";
						if($datos[descripcion1])
						{
							if(empty($_SESSION['CUENTAS']['RETORNO']))
                $this->salida .= "      <td>$var</td>\n";
						}

            $this->salida .= "  </tr>";
            $this->salida .= "  <tr align=\"center\">";
            if(!empty($_SESSION['CUENTAS']['RETORNO']))
            {
                $this->salida .= "      <td class=\"normal_10AN\">".$_SESSION['CUENTAS']['RETORNO']['empresa']."</td>\n";
                $this->salida .= "      <td class=\"normal_10AN\">".$_SESSION['CUENTAS']['RETORNO']['centro']."</td>\n";
            }
            else
            {
                $this->salida .= "      <td class=\"normal_10AN\" >".$datos[razon_social]."</td>\n";
                $this->salida .= "      <td class=\"normal_10AN\">".$datos[descripcion]."</td>\n";
                if($datos[descripcion1]) $this->salida .= "      <td class=\"normal_10AN\" >".$datos[descripcion1]."</td>\n";
            }
            $this->salida .= "  </tr>\n";
            $this->salida .= "</table>\n";
        }
  }
  /**
  * Muestra la forma con los diferentes tipos de busqueda de una cuenta.
  * @access private
  * @return boolean
  * @param int tipo de busqueda
  * @param text mensaje
  * @param int si existe encontro la cuenta
  * @param array arreglo con los datos de la cuenta
  * @param int departamento
  * @param int hace un listado
  */
  //function FormaMetodoBuscar($Busqueda,$mensaje,$D,$arr,$Departamento,$f,$LinkCargo,$Caja,$arreglo,$TipoCuenta,$new)
  function FormaMetodoBuscar($arr)
  {
      IncludeLib("tarifario");
      if(!$Busqueda){ $Busqueda=1; }
      $accion=ModuloGetURL('app','Facturacion','user','BuscarCuenta',array('Caja'=>$this->Caja,'arreglo'=>$arreglo,'Empresa'=>$Empresa,'CentroUtilidad'=>$CU,'TipoCuenta'=>$TipoCuenta));
      $Empresa=$this->Empresa;
      $CU=$this->CentroUtilidad;
      $this->salida .= "";
      $this->salida .= ThemeAbrirTabla('BUSCAR CUENTA');
      $this->EncabezadoEmpresa($this->Caja);
			
			$dpto = SessionGetVar("DepartamentoCuentas"); 
			
			$this->salida .= "<script>\n";
			$this->salida .= "      function acceptNum(evt)\n";
			$this->salida .= "      {\n";
			$this->salida .= "          var nav4 = window.Event ? true : false;\n";
			$this->salida .= "          var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "          return (key <= 13 || (key >= 48 && key <= 57) || key == 45);\n";
			$this->salida .= "      }\n";

			$this->salida .= "      function limpiarCampos(objeto)\n";
			$this->salida .= "      {\n";

			$this->salida .= "          objeto.Cuenta.value = \"\";\n";
			$this->salida .= "          objeto.Ingreso.value = \"\";\n";
			$this->salida .= "          objeto.Documento.value = \"\";\n";
			$this->salida .= "          objeto.Nombres.value = \"\";\n";
			$this->salida .= "          objeto.Apellidos.value = \"\";\n";
			$this->salida .= "          objeto.TipoDocumento.selectedIndex='0';\n";
			$this->salida .= "          try\n";
			$this->salida .= "          {\n";
			$this->salida .= "          	objeto.Departamento.selectedIndex='0';\n";
			$this->salida .= "      		}catch(error){}\n";
			$this->salida .= "      }\n";

			$this->salida .= "</script>\n";
			$this->salida .= "<br>\n";
			$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">\n";
			$this->salida .= "	<table border=\"0\" width=\"81%\" align=\"center\">\n";
			$this->salida .= "  	<tr>\n";
			$this->salida .= "    	<td>\n";
			$this->salida .= "      	<fieldset><legend class=\"normal_11N\">CRITERIOS DE BUSQUEDA</legend>\n";
			$this->salida .= "        	<table border=\"0\" width=\"95%\" align=\"center\">\n";
			$this->salida .= "           	<tr>\n";
			$this->salida .= "            	<td>\n";
			$this->salida .= "              	<table width=\"100%\" align=\"center\" border=\"0\">\n";
			$this->salida .= "                	<tr>\n";
			$this->salida .= "                  	<td class=\"normal_10AN\">No. CUENTA: </td>\n";
			$this->salida .= "                    <td>\n";
			$this->salida .= "                    	<input type=\"text\" class=\"input-text\" onkeypress=\"return acceptNum(event)\" name=\"Cuenta\" maxlength=\"32\" value=\"".$this->rqs['Cuenta']."\">\n";
			$this->salida .= "                    </td>\n";
			$this->salida .= "                    <td class=\"normal_10AN\">No. INGRESO: </td>\n";
			$this->salida .= "                    <td>\n";
			$this->salida .= "                    	<input type=\"text\" class=\"input-text\" onkeypress=\"return acceptNum(event)\" name=\"Ingreso\" maxlength=\"32\" value=\"".$this->rqs['Ingreso']."\">\n";
			$this->salida .= "                    </td>\n";
			$this->salida .= "                 	</tr>\n";
			$this->salida .= "                  <tr>\n";
			$this->salida .= "                  	<td class=\"normal_10AN\" width=\"18%\">TIPO DOCUMENTO: </td>\n";
			$this->salida .= "                    <td width=\"32%\">\n";
			$this->salida .= "                    	<select name=\"TipoDocumento\" class=\"select\">\n";
			$this->salida .= "                      	<option value=\"\">-------SELECCIONE-------</option>";
			if($_SESSION['CUENTAS']['TIPOCUENTA']!='02')
			{
					$tipo_id=$this->tipo_id_paciente();
					$this->BuscarIdPaciente($tipo_id,$this->rqs['TipoDocumento']);
			}
			else
			{
					$tipo_id_terceros=$this->tipo_id_terceros();
					$this->BuscarIdTerceros($tipo_id_terceros,'');
			}
			$this->salida .= "                    	</select>\n";
			$this->salida .= "                    </td>\n";
			$this->salida .= "                    <td width=\"18%\" class=\"normal_10AN\">DOCUMENTO: </td>\n";
			$this->salida .= "                    <td>\n";
			$this->salida .= "                    	<input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"".$this->rqs['Documento']."\">\n";
			$this->salida .= "                    </td>\n";
			$this->salida .= "                  </tr>\n";
			$this->salida .= "                  <tr>\n";
			$this->salida .= "                  	<td class=\"normal_10AN\">NOMBRES:</td>\n";
			$this->salida .= "                    <td>\n";
			$this->salida .= "                    	<input type=\"text\" class=\"input-text\" name=\"Nombres\" style=\"width:94%\" maxlength=\"64\" value=\"".$this->rqs['Nombres']."\">\n";
			$this->salida .= "                    </td>\n";
			$this->salida .= "                    <td class=\"normal_10AN\">APELLIDOS:</td>\n";
			$this->salida .= "                    <td>\n";
			$this->salida .= "                    	<input type=\"text\" class=\"input-text\" name=\"Apellidos\" style=\"width:94%\" maxlength=\"64\" value=\"".$this->rqs['Apellidos']."\">\n";
			$this->salida .= "                    </td>\n";
			$this->salida .= "                 	</tr>\n";
			if($dpto)
			{
				$this->salida .= "                  <tr>\n";
				$this->salida .= "                  	<td class=\"normal_10AN\">DEPARTAMENTO:</td>\n";
				$this->salida .= "                    <td colspan=\"3\">\n";

				if(sizeof($dpto) > 1 )
				{
					$this->salida .= "                    	<select name=\"Departamento\" class=\"select\">\n";
					$this->salida .= "                      	<option value=\"\">-------SELECCIONE-------</option>";
					foreach($dpto as $key => $val)
					{
						($this->rqs['Departamento'] == $val['departamento'])? $sel = "selected":$sel = "";
						$this->salida .= "                    		<option value=\"".$val['departamento']."\" $sel>".$val['descripcion']."</option>";
					}
					$this->salida .= "                    	</select>\n";
				}
				else
				{
					$this->salida .= "                    	<label class=\"label_mark\">".$dpto[0]['descripcion']."</lable>\n";
					$this->salida .= "                    	<iput type=\"hidden\" name=\"Departamento\" value=\"".$dpto[0]['departamento']."\">\n";
				}
				$this->salida .= "                  	<td>\n";
				$this->salida .= "                 	<tr>\n";
			}
			$this->salida .= "                 	<tr>\n";
			$this->salida .= "                  	<td colspan = '4' align=\"center\" >\n";
			$this->salida .= "                    	<table width=\"50%\">\n";
			$this->salida .= "                      	<tr>\n";
			$this->salida .= "                        	<td align=\"center\">\n";
			$this->salida .= "                          	<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\">\n";
			$this->salida .= "                          </td>\n";
			$this->salida .= "                          <td width=\"20%\">\n";
			$this->salida .= "                          	<input class=\"input-submit\" type=\"button\" onclick=\"limpiarCampos(document.formabuscar)\" name=\"Limpiar\" value=\"Limpiar Campos\">\n";
			$this->salida .= "                          </td>\n";
			$this->salida .= "                          </form>";
			if($_SESSION['CUENTAS']['CAJA'])
			{
					$actionM = ModuloGetURL('app','CajaGeneral','user','main');
			}
			elseif(!empty($_SESSION['CUENTAS']['RETORNO']))
					{
							$_SESSION['CUENTAS']['RETORNO']['volver'] = true;
							$arg = $_SESSION['CUENTAS']['RETORNO']['argumentos'];
							$Tipo = $_SESSION['CUENTAS']['RETORNO']['tipo'];
							$Modulo = $_SESSION['CUENTAS']['RETORNO']['modulo'];
							$Metodo = $_SESSION['CUENTAS']['RETORNO']['metodo'];
							$Contenedor = $_SESSION['CUENTAS']['RETORNO']['contenedor'];
							$actionM = ModuloGetURL($Contenedor,$Modulo,$Tipo,$Metodo,$arg);
					}
					else
							{
									$actionM=ModuloGetURL('app','Facturacion','user','Menus');
							}
			$this->salida .= "                          <form name=\"volverfrm\" action=\"$actionM\" method=\"post\">\n";
			$this->salida .= "                        <td align=\"center\">\n";
			$this->salida .= "                        	<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Volver\">\n";
			$this->salida .= "                        </td>\n";
			$this->salida .= "                        </form>\n";
			$this->salida .= "                     	</tr>\n";
			$this->salida .= "                  	</table>\n";
			$this->salida .= "                 	</td>\n";
			$this->salida .= "                </tr>\n";
			$this->salida .= "             	</table>\n";
			$this->salida .= "           	</td>\n";
			$this->salida .= "         	</tr>\n";
			$this->salida .= "       	</table>\n";
			$this->salida .= "    	</fieldset>\n";
			$this->salida .= "   	</td>\n";
			$this->salida .= " 	</tr>\n";
			$this->salida .= "</table>\n";

      //mensaje
      $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
      $this->salida .= $this->SetStyle("MensajeError");
      $this->salida .= "  </table>";
      if($_SESSION['CUENTAS']['TIPOCUENTA']!='01' || $_SESSION['CUENTAS']['CAJA']!='01')
      {
        if($arr)
        {
              $this->salida .= "       <br>";
              $this->salida .= "    <table width=\"98%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"normal_10\">";
              $this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
              $this->salida .= "        <td>No. CUENTA</td>";
              $this->salida .= "        <td>IDENTIFICACION</td>";
              $this->salida .= "        <td>PACIENTE</td>";
              $this->salida .= "        <td>PIEZA</td>";
              $this->salida .= "        <td>CAMA</td>";
              $this->salida .= "        <td>RESPONSABLE</td>";
              $this->salida .= "        <td>PLAN</td>";
              $this->salida .= "        <td>RANGO</td>";
                            $this->salida .= "        <td>FECHA APERTURA</td>";
                            $this->salida .= "        <td>HORA APERTURA</td>";
                            $this->salida .= "        <td>VALOR NO CUBIERTO</td>";
                            $this->salida .= "        <td>TOTAL CUENTA</td>";
              $this->salida .= "        <td>E</td>";
              $this->salida .= "        <td></td>";
              $this->salida .= "      </tr>";
                            for($i=0;$i<sizeof($arr);$i++)
                            {
                                    $Descripcion=$arr[$i][descripcion];
                                    $Pieza=$arr[$i][pieza];
                                    $Cama=$arr[$i][cama];
                                    $Cuenta=$arr[$i][numerodecuenta];
                                    $PlanId=$arr[$i][plan_id];
                                    $Nivel=$arr[$i][rango];
                                    $Fechas=$arr[$i][fecha1];
                                    $Total=$arr[$i][total_cuenta];
                                    $ValorNo=$arr[$i][valor_nocubierto];
                                    $TipoId=$arr[$i][tipo_id_paciente];
                                    $PacienteId=$arr[$i][paciente_id];
                                    $Estado=$arr[$i][estado];
                                    $Ingreso=$arr[$i][ingreso];
                                    $datos=$this->BuscarPlanes($PlanId,$Ingreso);
                                    //$Fechas=$this->FechaStamp($Fecha);
                                    //$Horas = $this->HoraStamp($arr[$i][fecha_registro]);
                                                                        $Horas = $arr[$i][hora1];
                                    if($LinkCargo==1) {$accionHRef=ModuloGetURL('app','Facturacion','user','Cargos',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Ingreso'=>$Ingreso,'Fecha'=>$Fecha));}
                                    else{ $accionHRef=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado));}
                                    if($_SESSION['CUENTAS']['CAJA']){ $accionHRef=ModuloGetURL('app','CajaGeneral','user','CajaHospitalaria',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'FechaC'=>$Fecha,'Ingreso'=>$Ingreso,'numero'=>$_SESSION['CUENTAS']['arreglo'][numero],'prefijo'=>$arreglo[prefijo],'TipoCuenta'=>$_SESSION['CUENTAS']['TIPOCUENTA'],'Tiponumeracion'=>$_SESSION['CUENTAS']['arreglo'][Tiponumeracion],'Empresa'=>$_SESSION['CUENTAS']['EMPRESA'],'CentroUtilidad'=>$_SESSION['CUENTAS']['CENTROUTILIDAD'],'Cajaid'=>$_SESSION['CUENTAS']['CAJA']));}
                                    elseif(!empty($_SESSION['CUENTAS']['RETORNO']))
                                    {
                                                $_SESSION['CUENTAS']['RETORNO']['volver']=false;
                                                $Contenedor=$_SESSION['CUENTAS']['RETORNO']['contenedor'];
                                                $Modulo=$_SESSION['CUENTAS']['RETORNO']['modulo'];
                                                $Tipo=$_SESSION['CUENTAS']['RETORNO']['tipo'];
                                                $Metodo=$_SESSION['CUENTAS']['RETORNO']['metodo'];
                                                $arg=$_SESSION['CUENTAS']['RETORNO']['argumentos'];
                                                $accionHRef=ModuloGetURL($Contenedor,$Modulo,$Tipo,$Metodo,array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'FechaC'=>$Fecha,'Ingreso'=>$Ingreso));
                                    }
                                    if( $i % 2){ $estilo='modulo_list_claro';}
                                    else {$estilo='modulo_list_oscuro';}
                                    $this->salida .= "      <tr class=\"$estilo\">";
                                    $this->salida .= "        <td align=\"center\">$Cuenta</td>";
                                    $this->salida .= "        <td>$TipoId $PacienteId</td>";
                                    $this->salida .= "        <td>".$arr[$i][nombre]."</td>";
                                    $this->salida .= "        <td align=\"center\">$Pieza</td>";
                                    $this->salida .= "        <td align=\"center\">$Cama</td>";
                                    $this->salida .= "        <td align=\"center\">".$datos[nombre_tercero]."</td>";
                                    $this->salida .= "        <td align=\"center\">".$datos[plan_descripcion]."</td>";
                                    $this->salida .= "        <td align=\"center\">$Nivel</td>";
                                    $this->salida .= "        <td align=\"center\">$Fechas</td>";
                                    $this->salida .= "        <td align=\"center\">$Horas</td>";
                                    if($_SESSION['CUENTAS']['SWCUENTAS']=='Cerradas')
                                    {
                                            $this->salida .= "        <td align=\"center\">".$arr[$i][nombre]."</td>";
                                            $this->salida .= "        <td align=\"center\">".$arr[$i][factura]."</td>";
                                    }
                                    else
                                    {
                                            $this->salida .= "        <td align=\"center\">".FormatoValor($ValorNo)."</td>";
                                            $this->salida .= "        <td align=\"center\">".FormatoValor($Total)."</td>";
                                    }
                                    $this->salida .= "        <td align=\"center\">".$Estado."</td>";
                                    $this->salida .= "        <td align=\"center\"><a href=\"$accionHRef\">VER</a></td>";
                                    $this->salida .= "      </tr>";
                            }//fin for
          $this->salida .= " </table>";

                    $Paginador = new ClaseHTML();
                    $this->salida .= "      <br>\n";
                    $this->salida .= "      ".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->action2);
        }//if
        if(!$f)
        {
            $Pendientes=$this->DatosTmpCuentasPendientes();
            if($Pendientes && !$Caja)
            {  $this->FormaCuentaPendientes($Pendientes);  }
        }
      }
        $this->salida .= ThemeCerrarTabla();
        return true;
  }

  /**
  *
  */
  function CalcularNumeroPasos($conteo){
    $numpaso=ceil($conteo/$this->limit);
    return $numpaso;
  }

  function CalcularBarra($paso){
    $barra=floor($paso/10)*10;
    if(($paso%10)==0){
      $barra=$barra-10;
    }
    return $barra;
  }

  function CalcularOffset($paso){
    $offset=($paso*$this->limit)-$this->limit;
    return $offset;
  }


  function RetornarBarra(){
    $this->conteo;
    $this->limit;

    if($this->limit>=$this->conteo){
        return '';
    }
    $paso=$_REQUEST['paso'];
    if(is_null($paso)){
      $paso=1;
    }
    $vec='';
    foreach($_REQUEST as $v=>$v1)
    {
      if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID' and  $v!='Of')
      {   $vec[$v]=$v1;   }
    }
    $accion=ModuloGetURL('app','Facturacion','user','BuscarCuenta',$vec);
    $barra=$this->CalcularBarra($paso);
    $numpasos=$this->CalcularNumeroPasos($this->conteo);
    $colspan=1;
    $this->salida .= "<br><table border='1' align='center'  cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
    if($paso > 1){
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
      $colspan+=1;
    }
    $barra ++;
    if(($barra+10)<=$numpasos){
      for($i=($barra);$i<($barra+10);$i++){
        if($paso==$i){
            $this->salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
        }else{
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
        }
        $colspan++;
      }
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
      $colspan+=2;
    }else{
      $diferencia=$numpasos-9;
      if($diferencia<=0){$diferencia=1;}
      for($i=($diferencia);$i<=$numpasos;$i++){
        if($paso==$i){
          $this->salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
        }else{
          $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
        }
        $colspan++;
      }
      if($paso!=$numpasos){
        $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
        $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
        $colspan++;
      }else{
       // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
        //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
      }
    }
    if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
    {
      if($numpasos>10)
      {
        $valor=10+3;
      }
      else
      {
        $valor=$numpasos+3;
      }
      $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>P?gina $paso de $numpasos</td><tr></table><br>";
    }
    else
    {
      if($numpasos>10)
      {
        $valor=10+5;
      }
      else
      {
        $valor=$numpasos+5;
      }
    $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>P?gina $paso de $numpasos</td><tr></table><br>";
    }

  }


  /**
  * Muestra los cargos que tiene pendientes un usuario.
  * @access private
  * @return void
  * @param array datos de los cargos pendientes
  */
  function FormaCuentaPendientes($Pendientes)
  {
      $this->salida .= "       <br>";
      $this->salida .= "       <p class=\"label_error\" align=\"center\">Usted tiene estos cargos pendientes.</p>";
      $this->salida .= "    <table width=\"94%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
      $this->salida .= "        <td>No. CUENTA</td>";
      $this->salida .= "        <td>IDENTIFICACION</td>";
      $this->salida .= "        <td>PACIENTE</td>";
      $this->salida .= "        <td>RESPONSABLE</td>";
      $this->salida .= "        <td>PLAN</td>";
      $this->salida .= "        <td>NIVEL</td>";
      $this->salida .= "        <td>FECHA APERTURA</td>";
      $this->salida .= "        <td>HORA APERTURA</td>";
      $this->salida .= "        <td></td>";
      $this->salida .= "      </tr>";
      for($i=0; $i<sizeof($Pendientes); $i++)
      {
          if( $i % 2) $estilo='modulo_list_claro';
          else $estilo='modulo_list_oscuro';
          $Cuenta=$Pendientes[$i][numerodecuenta];
          $Ingreso=$Pendientes[$i][ingreso];
          $PacienteId=$Pendientes[$i][paciente_id];
          $TipoId=$Pendientes[$i][tipo_id_paciente];
          $PNombre=$Pendientes[$i][primer_nombre];
          $SNombre=$Pendientes[$i][segundo_nombre];
          $PApellido=$Pendientes[$i][primer_apellido];
          $SApellido=$Pendientes[$i][segundo_apellido];
          $Nivel=$Pendientes[$i][rango];
          $PlanId=$Pendientes[$i][plan_id];
          $Fecha=$Pendientes[$i][fecha_registro];
          //$datos=$this->CallMetodoExterno('app','Triage','user','BuscarPlanes',array('PlanId'=>$PlanId,'Ingreso'=>$Ingreso));
          $datos=$this->BuscarPlanes($PlanId,$Ingreso);
          $Fechas=$this->FechaStamp($Fecha);
          $Horas=$this->HoraStamp($Fecha);
          $this->salida .= "      <tr class=\"$estilo\">";
          $this->salida .= "        <td align=\"center\">$Cuenta</td>";
          $this->salida .= "        <td>$TipoId $PacienteId</td>";
          $this->salida .= "        <td>$PNombre $SNombre $PApellido $SApellido</td>";
          $this->salida .= "        <td align=\"center\">".$datos[nombre_tercero]."</td>";
          $this->salida .= "        <td align=\"center\">".$datos[plan_descripcion]."</td>";
          $this->salida .= "        <td align=\"center\">$Nivel</td>";
          $this->salida .= "        <td align=\"center\">$Fechas</td>";
          $this->salida .= "        <td align=\"center\">$Horas</td>";
          $accion=ModuloGetURL('app','Facturacion','user','Cargos',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Ingreso'=>$Ingreso,'Fecha'=>$Fecha));
          $this->salida .= "        <td align=\"center\"><a href=\"$accion\">VER</a></td>";
          $this->salida .= "      </tr>";
      }
          $this->salida .= " </table>";
  }


  /**
  * Lista las cuentas PV
  * @access private
  * @return boolean
  */
  function FormaCajaPV($Cuentas,$Caja,$arreglo,$TipoCuenta,$Cuenta)
  {
      IncludeLib("tarifario");
      $Empresa=$this->Empresa;
      $CU=$this->CentroUtilidad;
      $this->salida .= "<br><table width=\"80%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
      $this->salida .= "        <td>No. CUENTA</td>";
      $this->salida .= "        <td>IDENTIFICACION</td>";
      $this->salida .= "        <td>NOMBRE Y/O RAZON SOCIAL</td>";
      $this->salida .= "        <td>TOTAL</td>";
      $this->salida .= "        <td></td>";
      $this->salida .= "      </tr>";
      for($i=0; $i<sizeof($Cuentas); $i++)
      {
          if( $i % 2) $estilo='modulo_list_claro';
          else $estilo='modulo_list_oscuro';
          $Cuenta=$Cuentas[$i][cuenta_pv];
          $this->salida .= "      <tr class=\"$estilo\" align=\"center\">";
          $this->salida .= "        <td>".$Cuentas[$i][cuenta_pv]."</td>";
          $this->salida .= "        <td>".$Cuentas[$i][tipo_tercero_id]." ". $Cuentas[$i][tercero_id]."</td>";
          $this->salida .= "        <td>".$Cuentas[$i][nombre_tercero]."</td>";
          $this->salida .= "        <td>".FormatoValor($Cuentas[$i][total])."</td>";
          if($Caja){ $accion=ModuloGetURL('app','CajaGeneral','user','CajaHospitalaria',array('Cuenta'=>$Cuenta,'numero'=>$arreglo[numero],'prefijo'=>$arreglo[prefijo],'TipoCuenta'=>$_SESSION['CUENTAS']['TIPOCUENTA'],'Tiponumeracion'=>$arreglo[Tiponumeracion],'Empresa'=>$_SESSION['CUENTAS']['EMPRESA'],'CentroUtilidad'=>$_SESSION['CUENTAS']['CENTROUTILIDAD'],'Cajaid'=>$Caja)); }
          else { $accion=ModuloGetURL('app','Facturacion','user','LlamaFormaDetalleCuentaPV',array('NumCuenta'=>$Cuentas[$i][cuenta_pv],'TipoCuenta'=>$TipoCuenta));  }
          $this->salida .= "        <td><a href=\"$accion\">VER</a></td>";
          $this->salida .= "      </tr>";
      }
      $this->salida .= "    </table>";
  }


  /**
  * Muestra el detalle de una cuenta PV.
  * @access private
  * @return boolean
  */
  function FormaDetalleCuentaPV($NumCuenta,$TipoCuenta)
  {
      IncludeLib("tarifario");
      $Dev=$this->DetalleCuentasPVDevoluciones($NumCuenta,false);
      $Det=$this->DetalleCuentasPV($NumCuenta,false);
      $this->salida .= ThemeAbrirTabla('DETALLES DE CUENTA PV No. '.$NumCuenta);
      $this->salida .= "<br><table width=\"80%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
      $this->salida .= "        <td>CODIGO</td>";
      $this->salida .= "        <td>DESCRIPCION</td>";
      $this->salida .= "        <td>PRECIO</td>";
      $this->salida .= "        <td>CANTIDAD</td>";
      $this->salida .= "        <td>IVA</td>";
      $this->salida .= "        <td>TOTAL</td>";
      $this->salida .= "      </tr>";
      for($i=0; $i<sizeof($Det); $i++)
      {
          if( $i % 2) $estilo='modulo_list_claro';
          else $estilo='modulo_list_oscuro';
          $this->salida .= "      <tr class=\"$estilo\" align=\"center\">";
          $this->salida .= "        <td>".$Det[$i][empresa_id]." - ". $Det[$i][codigo_producto]."</td>";
          $this->salida .= "        <td>".$Det[$i][descripcion]."</td>";
          $this->salida .= "        <td>".FormatoValor($Det[$i][precio_venta])."</td>";
          $this->salida .= "        <td>".$Det[$i][despachada]."</td>";
          $this->salida .= "        <td>".FormatoValor($Det[$i][gravamen])."</td>";
          $this->salida .= "        <td>".FormatoValor($Det[$i][total_venta])."</td>";
          $this->salida .= "      </tr>";
      }
      $this->salida .= "    </table><br>";
      if($Dev)
      {
          $this->salida .= "<p class=\"label\" align=\"center\">DEVOLUCIONES</p>";
          $this->salida .= "   <table width=\"80%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
          $this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
          $this->salida .= "        <td>CODIGO</td>";
          $this->salida .= "        <td>DESCRIPCION</td>";
          $this->salida .= "        <td>PRECIO</td>";
          $this->salida .= "        <td>CANTIDAD</td>";
          $this->salida .= "        <td>IVA</td>";
          $this->salida .= "        <td>TOTAL</td>";
          $this->salida .= "      </tr>";
          for($i=0; $i<sizeof($Dev); $i++)
          {
              if( $i % 2) $estilo='modulo_list_claro';
              else $estilo='modulo_list_oscuro';
              $this->salida .= "      <tr class=\"$estilo\" align=\"center\">";
              $this->salida .= "        <td>".$Dev[$i][empresa_id]." - ". $Dev[$i][codigo_producto]."</td>";
              $this->salida .= "        <td>".$Dev[$i][descripcion]."</td>";
              $this->salida .= "        <td>".FormatoValor($Dev[$i][precio_venta])."</td>";
              $this->salida .= "        <td>".$Dev[$i][despachada]."</td>";
              $this->salida .= "        <td>".FormatoValor($Dev[$i][gravamen])."</td>";
              $this->salida .= "        <td>".FormatoValor($Dev[$i][total_venta])."</td>";
              $this->salida .= "      </tr>";
          }
          $this->salida .= "    </table><br><br>";
      }
      $TotalDev=$this->DetalleCuentasPVDevoluciones($NumCuenta,true);
      $TotalDet=$this->DetalleCuentasPV($NumCuenta,true);
      $TotalCuenta=abs($TotalDet[sum]-$TotalDev[sum]);
      $TotalGravamen=$TotalDet[gravamen]-$TotalDev[gravamen];
      $this->salida .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "            <tr><td><fieldset><legend class=\"field\">TOTALES</legend>";
      $this->salida .= "  <table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "   <tr>";
      $this->salida .= "      <td class=\"label\">TOTAL CUENTA: </td>";
      $this->salida .= "      <td><input type=\"text\" class=\"input-text\" name=\"Cargos\" value=\"$TotalCuenta\" size=\"10\" readonly></td>";
      $this->salida .= "      <td width=\"6%\"></td>";
      $this->salida .= "      <td class=\"label\">TOTAL DEVOLUCIONES: </td>";
      $this->salida .= "      <td><input type=\"text\" class=\"input-text\" name=\"ValorTotal\" value=\"".$TotalDev[sum]."\" size=\"10\" readonly></td>";
      $this->salida .= "   </tr>";
      $this->salida .= "  </table>";
      $this->salida .= "  </fieldset></td></tr></table>";
      $accion=ModuloGetURL('app','Facturacion','user','main',array('TipoCuenta'=>$TipoCuenta));
      $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
      $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER A CUENTAS\"></p>";
      $this->salida .= "</form><br>";
      $this->salida .= ThemeCerrarTabla();
      return true;
  }


  /**
  * Muestra los datos del responsable(tercero) del paciente y los datos basicos del paciente
  * nombres, identificacion,numero de ingreso y la fecha y hora de apertura de la cuenta.
  * @access private
  * @return void
  * @param int plan_id
  * @param string tipo documento
  * @param int numero documento
  * @param int ingreso
  * @param string nivel
  * @param date fecha de registro de la cuenta
  */
    function Encabezado($PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$argu,$Cuenta)
    {
        $datos=$this->CuentaParticular($Cuenta,$PlanId);
        if(!$datos)
        {
            $datos=$this->BuscarPlanes($PlanId,$Ingreso);
            $Responsable=$datos[nombre_tercero];
            $ident=$datos[tipo_id_tercero].' '.$datos[tercero_id];
        }
                $afi=$this->BuscarTipoAfiliado($Cuenta);
        //$Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
        //$Apellidos=$this->BuscarApellidosPaciente($TipoId,$PacienteId);
        $Nombre=$this->BuscarNombreCompletoPaciente($TipoId,$PacienteId);
        $Fecha1=$this->FechaStamp($Fecha);
        $Hora=$this->HoraStamp($Fecha);

        $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\" >\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td width=\"45%\">\n";
        $this->salida .= "              <table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\" >\n";
        $this->salida .= "                  <tr>\n";
        $this->salida .= "                      <td valign=\"top\">\n";
        $this->salida .= "                          <fieldset><legend class=\"field\">RESPONSABLE</legend>\n";
        $this->salida .= "                              <table border=\"0\" width=\"98%\" align=\"center\" class=\"normal_10\">\n";
        $this->salida .= "                                  <tr><td class=\"label\" width=\"24%\">RESPONSABLE: </td><td>$Responsable</td></tr>\n";
        $this->salida .= "                                  <tr><td class=\"label\" width=\"24%\">IDENTIFICACION: </td><td>".$ident."</td></tr>\n";
        $this->salida .= "                                  <tr><td class=\"label\" width=\"24%\">PLAN: </td><td>".$datos[plan_descripcion]."</td></tr>\n";
        $this->salida .= "                                  <tr><td class=\"label\" width=\"24%\">TIPO AFILIADO: </td><td>".$afi[tipo_afiliado_nombre]."</td></tr>\n";
        $this->salida .= "                                  <tr><td class=\"label\" width=\"24%\">RANGO: </td><td>".$afi[rango]."</td></tr>\n";
        if(!empty($datos[protocolos]))
        {
            if(file_exists("protocolos/".$datos[protocolos].""))
            {
                $Protocolo=$datos[protocolos];
                $this->salida .= "  <script>\n";
                $this->salida .= "      function Protocolo(valor)\n";
                $this->salida .= "      {\n";
                $this->salida .= "          window.open('protocolos/'+valor,'PROTOCOLO','');\n";
                $this->salida .= "      }\n";
                $this->salida .= "  </script>\n";
                $accion = "javascript:Protocolo('$datos[protocolos]')";
                $this->salida .= "                              <tr><td class=\"label\" width=\"24%\">PROTOCOLO: </td><td><a href=\"$accion\">$Protocolo</a></td></tr>\n";
            }
        }
        if(!empty($argu))
        {
            $accion=ModuloGetURL('app','Facturacion','user','VerAutorizaciones',$argu);
            $this->salida .= "<tr><td class=\"label\">AUTORIZACIONES: </td> ";
            $this->salida .= "<td align=\"left\"><a href=\"$accion\">Ver Autorizaciones Plan</a></td></tr> ";
        }
        if($datos[sw_tipo_plan]==1)
        {
            if($datos[saldo]<=0)
            {
                $this->salida .= "                              <tr><td class=\"label_error\" width=\"24%\">SALDO SOAT ($): </td><td>".$datos[saldo]."</td></tr>";
            }
            else
            {
                $this->salida .= "                              <tr><td class=\"label\" width=\"24%\">SALDO SOAT ($): </td><td>".$datos[saldo]."</td></tr>";
            }
        }
        $this->salida .= "                              </table>\n";
        $this->salida .= "                      </fieldset>\n";
        $this->salida .= "                  </td>\n";
        $this->salida .= "              </tr>\n";
        $this->salida .= "          </table>\n";
        $this->salida .= "       </td>";
        $this->salida .= "       <td width=\"45%\">";
        $this->salida .= "          <table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "              <tr>\n";
        $this->salida .= "                  <td valign=\"top\">\n";
        $this->salida .= "                      <fieldset><legend class=\"field\">DATOS DEL PACIENTE</legend>\n";
        $this->salida .= "                          <table border=\"0\" width=\"98%\" align=\"center\" class=\"normal_10\">\n";
        $this->salida .= "                              <tr><td class=\"label\" width=\"35%\">PACIENTE: </td><td>$Nombre</td></tr>\n";
        $this->salida .= "                              <tr><td class=\"label\">IDENTIFICACION: </td><td>$TipoId  $PacienteId</td></tr>\n";
        $this->salida .= "                              <tr><td class=\"label\">No. INGRESO: </td><td>$Ingreso</td></tr>\n";
        $this->salida .= "                              <tr><td class=\"label\">FECHA APERTURA: </td><td>$Fecha1</td></tr>\n";
        $this->salida .= "                              <tr><td class=\"label\">HORA APERTURA: </td><td>$Hora</td></tr>\n";
        $this->salida .= "                          </table>\n";
        $this->salida .= "                      </fieldset>\n";
        $this->salida .= "                  </td>\n";
        $this->salida .= "              </tr>\n";
        $this->salida .= "          </table>";
        $this->salida .= "       </td>";
        $this->salida .= "    </tr>";
        $this->salida .= "  </table>\n";
    }

  /**
  * Muestra los totales de cuenta.
  * @access private
  * @return void
  * @param int numero de la cuenta
  */
  function TotalesCuenta($Cuenta,$sw)
    {
        IncludeLib("funciones_facturacion");
                $this->SetJavaScripts('PagosPaciente');
                $this->SetJavaScripts('DescuentosPaciente');
                $this->SetJavaScripts('DescuentosEmpresa');
                $this->SetJavaScripts('TotalPaciente');
        //$CantCargo=$this->TotalCargos($Cuenta);
        $Totales=$this->BuscarTotales($Cuenta);
                //-------estancia
                if (!IncludeFile("classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php"))
                {
                        die(MsgOut("Error al incluir archivo","El Archivo 'classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php' NO SE ENCUENTRA"));
                }

                $liqHab = new LiquidacionHabitaciones;
                $liqHab->LiquidarCargosInternacion($Cuenta,false);
                $Estancia = $liqHab->GetTotalCargosHabitacion();
                $totalEstancia = $Estancia['valor_cargo'];
                $totalEstanciaCubierta = $Estancia['valor_cubierto'];
                $totalEstanciaNoCubierta = $Estancia['valor_no_cubierto'];
                //-------fin estancia
            $TotalPaciente=$Totales[valor_total_paciente];
        $this->salida .= "      <table border=\"0\" width=\"89%\" align=\"center\" >";
        $this->salida .= "            <tr><td><fieldset><legend class=\"field\">TOTALES</legend>";
        $this->salida .= "  <table border=\"0\" width=\"75%\" align=\"center\"  >";
        $this->salida .= "   <tr>";
        $this->salida .= "      <td class=\"label\">TOTAL PAGADO PACIENTE: </td>";
                if($Totales[abono] > 0)
                {  $x=RetornarWinOpenPagos($Cuenta,'VER',$class);  }
        $this->salida .= "      <td><input type=\"text\" class=\"input-text\" name=\"\" value=\"".FormatoValor($Totales[abono])."\" size=\"10\" readonly>&nbsp;&nbsp;".$x."</td>";
        $this->salida .= "      <td width=\"5%\"></td>";
        $this->salida .= "      <td class=\"label\">TOTAL CUENTA: </td>";
                $totalCuenta = $Totales[total_cuenta]+$totalEstancia;
        $this->salida .= "      <td><input type=\"text\" class=\"input-text\" name=\"ValorTotal\" value=\"".FormatoValor($totalCuenta)."\" size=\"10\" readonly></td>";
        $this->salida .= "   </tr>";
        if($this->GetMostrarCopagoCuotaModeradora($Cuenta))
        {
            $this->salida .= "   <tr>";
            GLOBAL $QUERY_STRING;
            list($x,$request) = explode("metodo=".$_REQUEST['metodo']."&",$QUERY_STRING);
            $_SESSION['FACTURACION']['CUENTAS']['REQUEST'] = $request;
            if(!$sw)
            {
              $link1 = "<a href=\"".ModuloGetUrl("app","Facturacion","user","CallFormaModificarCuotaPaciente",array('numero_cuenta'=>$Cuenta,'nombre_paciente'=>"",'valor_cuota'=>$Totales[valor_cuota_paciente]))."\" class=\"label\">MODIFICAR</a>";
              $link2 = "<a href=\"".ModuloGetUrl("app","Facturacion","user","CallFormaModificarCuotaModeradora",array('numero_cuenta'=>$Cuenta,'nombre_paciente'=>"",'valor_cuota'=>$Totales[valor_cuota_moderadora]))."\" class=\"label\">MODIFICAR</a>";
            }
            $this->salida .= "      <td class=\"label\">CUOTA PACIENTE: </td>";
            $this->salida .= "      <td><input type=\"text\" class=\"input-text\" name=\"CuotaPaciente\" value=\"".FormatoValor($Totales[valor_cuota_paciente])."\" size=\"10\" readonly>&nbsp;&nbsp;$link1</td>";
            $this->salida .= "      <td></td>";
            $this->salida .= "      <td class=\"label\">CUOTA MODERADORA: </td>";
            $this->salida .= "      <td><input type=\"text\" class=\"input-text\" name=\"CuotaModeradora\" value=\"".FormatoValor($Totales[valor_cuota_moderadora])."\" size=\"10\" readonly>&nbsp;&nbsp;$link2</td>";
            $this->salida .= "   </tr>";
        }
        $this->salida .= "   <tr>";
                if($TotalPaciente > 0)
                {  $y=RetornarWinOpenTotalPaciente($Cuenta,'VER',$class);  }
        $this->salida .= "      <td class=\"label\">TOTAL PACIENTE: </td>";
        $this->salida .= "      <td><input type=\"text\" class=\"input-text\" name=\"TotalPaciente\" value=\"".FormatoValor($TotalPaciente)."\" size=\"10\" readonly>&nbsp;&nbsp;".$y."</td>";
        $this->salida .= "      <td></td>";
        $this->salida .= "      <td class=\"label\">TOTAL CUBIERTO: </td>";
        $this->salida .= "      <td><input type=\"text\" class=\"input-text\" name=\"TotalEmpresa\" value=\"".FormatoValor($Totales[valor_total_empresa])."\" size=\"10\" readonly></td>";
        $this->salida .= "   </tr>";
        $this->salida .= "   <tr>";
        $this->salida .= "      <td class=\"label\">TOTAL NO CUBIERTO: </td>";
        $this->salida .= "      <td><input type=\"text\" class=\"input-text\" name=\"TotalNo\" value=\"".FormatoValor($Totales[valor_nocubierto])."\" size=\"10\" readonly></td>";
        $this->salida .= "      <td></td>";
        $this->salida .= "      <td class=\"label\">SALDO: </td>";
                $saldo=SaldoCuentaPaciente($Cuenta) + $totalEstanciaNoCubierta;
          $this->salida .= "      <td><input type=\"text\" class=\"input-text\" name=\"Saldo\" value=\"".FormatoValor($saldo)."\" size=\"10\" readonly></td>";
        $this->salida .= "   </tr>";
        $this->salida .= "   <tr>";
        $this->salida .= "      <td class=\"label\">DESCUENTO PACIENTE: </td>";
                $d=RetornarWinOpenDescuentosPaciente($Cuenta,'VER',$class);
        $this->salida .= "      <td><input type=\"text\" class=\"input-text\" name=\"TotalEmpresa\" value=\"".FormatoValor($Totales[valor_descuento_paciente])."\" size=\"10\" readonly>&nbsp;&nbsp;$d</td>";
        $this->salida .= "      <td></td>";
                $emp=RetornarWinOpenDescuentosEmpresa($Cuenta,'VER',$class);
        $this->salida .= "      <td class=\"label\">DESCUENTO EMPRESA: </td>";
        $this->salida .= "      <td><input type=\"text\" class=\"input-text\" name=\"TotalPaciente\" value=\"".FormatoValor(  $Totales[valor_descuento_empresa])."\" size=\"10\" readonly>&nbsp;&nbsp;$emp</td>";
        $this->salida .= "   </tr>";
        $this->salida .= "   <tr>";
        $this->salida .= "      <td class=\"label\">DESCUENTO PACIENTE % </td>";
        $this->salida .= "      <td><input type=\"text\" class=\"input-text\" name=\"TotalEmpresa\" value=\"".FormatoValor($Totales[porcentaje_descuento_paciente])."\" size=\"10\" readonly></td>";
        $this->salida .= "      <td></td>";
        $this->salida .= "      <td class=\"label\">DESCUENTO EMPRESA % </td>";
        $this->salida .= "      <td><input type=\"text\" class=\"input-text\" name=\"TotalPaciente\" value=\"".FormatoValor(  $Totales[porcentaje_descuento_empresa])."\" size=\"10\" readonly></td>";
        $this->salida .= "   </tr>";
        $this->salida .= "   <tr>";
        $this->salida .= "      <td class=\"label\">TOTAL ESTANCIA: </td>";
        $this->salida .= "      <td><input type=\"text\" class=\"input-text\" name=\"\" value=\"".FormatoValor($totalEstancia)."\" size=\"10\" readonly></td>";
        $this->salida .= "      <td width=\"5%\"></td>";
        $this->salida .= "      <td class=\"label\">ABONO LETRAS</td>";
        $this->salida .= "      <td><input type=\"text\" class=\"input-text\" name=\"\" value=\"".FormatoValor($Totales[abono_letras])."\" size=\"10\" readonly></td>";
        $this->salida .= "   </tr>";
        $this->salida .= "  </table>";
        return true;
  }

  /**
  * Muestra el detalle de la cuenta.
  * @access private
  * @return boolean
  * @param int numero de la cuenta
  * @param string tipo documento
  * @param int numero documento
  * @param string nivel
  * @param string plan_id
  * @param int numero de cama
  * @param date fecha de la cuenta
  * @param int ingreso
  * @param array arreglo con los datos de la cuenta
  * @param int numero de transaccion
  */
    function FormaCuenta($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Cama,$Fecha,$Ingreso,$vars,$Transaccion,$mensaje,$Dev,$Estado,$arre)
    {
        if(empty($arre) AND !empty($_SESSION['CUENTAS']['ARREGLO_NOFACTURADOS']))
        {
          $arre = $_SESSION['CUENTAS']['ARREGLO_NOFACTURADOS'];
        }
        //IncludeLib("tarifario");
        //IncludeLib("funciones_facturacion");
                unset($_SESSION['CUENTA']['DIVISION']);
                unset($_SESSION['DIVISION']['CUENTA']);
                unset($_SESSION['DIVISION']['DIVISION']['ABONOS']);
        IncludeLib("funciones_admision");
        global $VISTA;
        //factura detalleda
        $RUTA = $_ROOT ."cache/factura.pdf";
        $mostrar ="\n<script>\n";
        $mostrar.="var rem=\"\";\n";
        $mostrar.="  function abreVentana(){\n";
        $mostrar.="    var nombre=\"\"\n";
        $mostrar.="    var url2=\"\"\n";
        $mostrar.="    var str=\"\"\n";
        $mostrar.="    var ALTO=screen.height\n";
        $mostrar.="    var ANCHO=screen.width\n";
        $mostrar.="    var nombre=\"REPORTE\";\n";
        $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.="    var url2 ='$RUTA';\n";
        $mostrar.="    rem = window.open(url2, nombre, str)};\n";
        //$mostrar.="</script>\n";
        //factura conceptos
        $RUTA = $_ROOT ."cache/facturaconceptos.pdf";
        //$mostrar ="\n<script language='javascript'>\n";
        $mostrar.="var rem=\"\";\n";
        $mostrar.="  function abreVentana2(){\n";
        $mostrar.="    var nombre=\"\"\n";
        $mostrar.="    var url2=\"\"\n";
        $mostrar.="    var str=\"\"\n";
        $mostrar.="    var ALTO=screen.height\n";
        $mostrar.="    var ANCHO=screen.width\n";
        $mostrar.="    var nombre=\"REPORTE\";\n";
        $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.="    var url2 ='$RUTA';\n";
        $mostrar.="    rem = window.open(url2, nombre, str)};\n";
/*        $RUTA = $_ROOT ."cache/hojacargos".$Cuenta.".pdf";
        $mostrar.="var rem=\"\";\n";
        $mostrar.="  function abreVentanaHC(){\n";
        $mostrar.="    var nombre=\"\"\n";
        $mostrar.="    var url2=\"\"\n";
        $mostrar.="    var str=\"\"\n";
        $mostrar.="    var ALTO=screen.height\n";
        $mostrar.="    var ANCHO=screen.width\n";
        $mostrar.="    var nombre=\"REPORTE\";\n";
        $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.="    var url2 ='$RUTA';\n";
        $mostrar.="    rem = window.open(url2, nombre, str)};\n";
        $RUTA = $_ROOT ."cache/hojacargos2".$Cuenta.".pdf";
        $mostrar.="var rem=\"\";\n";
        $mostrar.="  function abreVentanaHT(){\n";
        $mostrar.="    var nombre=\"\"\n";
        $mostrar.="    var url2=\"\"\n";
        $mostrar.="    var str=\"\"\n";
        $mostrar.="    var ALTO=screen.height\n";
        $mostrar.="    var ANCHO=screen.width\n";
        $mostrar.="    var nombre=\"REPORTE\";\n";
        $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.="    var url2 ='$RUTA';\n";
        $mostrar.="    rem = window.open(url2, nombre, str)};\n";
        $RUTA1 = $_ROOT ."cache/hojacargos3".$Cuenta.".pdf";
        $mostrar.="  function abreVentanaHC3(){\n";
        $mostrar.="    var nombre=\"\"\n";
        $mostrar.="    var url2=\"\"\n";
        $mostrar.="    var str=\"\"\n";
        $mostrar.="    var ALTO=screen.height\n";
        $mostrar.="    var ANCHO=screen.width\n";
        $mostrar.="    var nombre=\"REPORTE\";\n";
        $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.="    var url2 ='$RUTA1';\n";
        $mostrar.="    rem = window.open(url2, nombre, str)};\n";*/
        $mostrar.="</script>\n";
        $this->salida.="$mostrar";

        $Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
        $Apellidos=$this->BuscarApellidosPaciente($TipoId,$PacienteId);
        $TipoCuenta=$_SESSION['CUENTAS']['TIPOCUENTA'];
        if($vars)
        {
            $this->salida .= ThemeAbrirTabla('DETALLES DE LA CUENTA No. '.$Cuenta.' (Insumos y Medicamentos) '.$Nombres.' '.$Apellidos);
        }
        if($Dev)
        {
            $this->salida .= ThemeAbrirTabla('DETALLES DE LA CUENTA No. '.$Cuenta.' (Devoluci?n Insumos y Medicamentos) '.$Nombres.' '.$Apellidos);
        }
        if(!$Dev && !$vars)
        {
            $this->salida .= ThemeAbrirTabla('DETALLES DE CARGOS CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos);
        }
        $this->ConsultaAutorizacion();
        $this->EncabezadoEmpresa($Caja);
        $argu=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
        $this->Encabezado($PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$argu,$Cuenta);
        $this->TotalesCuenta($Cuenta);
        $this->salida .= "  </fieldset></td></tr></table><BR>";
        $Detalle=$this->BuscarDetalleCuenta($Cuenta);
        if(!$Dev && !$vars)
        {
            //botones
                        $var=$this->DatosFactura($Cuenta);
            $this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"65%\" align=\"center\">";
            $this->salida .= "    <tr align=\"center\">";
            if($_SESSION['LISTADO_PACIENTES_SALIDA']==1){
              $accionT=ModuloGetURL('app','Facturacion','user','CallFrmListaPacientesConSalida');
            }else{  
              $accionT=ModuloGetURL('app','Facturacion','user','main',array('TipoCuenta'=>$TipoCuenta));
            }
            $this->salida .= "           <form name=\"formabuscar\" action=\"$accionT\" method=\"post\">";
            $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"VOLVER A CUENTAS\"></td>";
            $this->salida .= "    </form>";
            if($_SESSION['CUENTAS']['SWCUENTAS']!='Cerradas')
            {
//ELIMINAR BOTON ANULAR
/*                  $msg='Esta seguro que desea Anular la Cuenta No. '.$Cuenta;
                  $arreglo=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado);
                  $accionEstado=ModuloGetURL('app','Facturacion','user','ConfirmarAccion',array('c'=>'app','m'=>'Facturacion','me2'=>'Cuenta','me'=>'AnularCuenta','mensaje'=>$msg,'titulo'=>'INACTIVAR CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos,'arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
                  $this->salida .= "    <form name=\"formaborrar\" action=\"$accionEstado\" method=\"post\">";
                  $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"ANULAR CUENTA\"></td>";
                  $this->salida .= "    </form>";*/
//FIN ELIMINAR BOTON ANULAR
                  $accion=ModuloGetURL('app','Facturacion','user','CrearDescuentos',array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
                  $this->salida .= "    <form name=\"formaborrar\" action=\"$accion\" method=\"post\">";
                  $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"DESCUENTOS\"></td>";
                  $this->salida .= "    </form>";
                  $accionAgregar=ModuloGetURL('app','Facturacion','user','LlamarFormaTiposCargos',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Ingreso'=>$Ingreso,'Fecha'=>$Fecha));
                  $this->salida .= "    <form name=\"formaborrar\" action=\"$accionAgregar\" method=\"post\">";
                  $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"AGREGAR CARGOS\"></td>";
                  $this->salida .= "    </form>";
                  //cambio responsable
                  $accionAgregar=ModuloGetURL('app','Facturacion','user','CambioResponsable',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Ingreso'=>$Ingreso,'Fecha'=>$Fecha));
                  $this->salida .= "    <form name=\"formaborrar\" action=\"$accionAgregar\" method=\"post\">";
                  $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"CAMBIO RESPONSABLE\"></td>";
                  $this->salida .= "    </form>";
                  if($_SESSION['ESTADO']=='A')
                  {
                      $msg='Esta seguro que desea Inactivar la Cuenta No. '.$Cuenta;
                      $arreglo=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado);
                      $accionEstado=ModuloGetURL('app','Facturacion','user','ConfirmarAccion',array('c'=>'app','m'=>'Facturacion','me2'=>'Cuenta','me'=>'InactivarCuenta','mensaje'=>$msg,'titulo'=>'INACTIVAR CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos,'arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
                      $this->salida .= "    <form name=\"formaborrar\" action=\"$accionEstado\" method=\"post\">";
                      $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"INACTIVAR CUENTA\"></td>";
                      $this->salida .= "    </form>";
                  }
                  if($_SESSION['ESTADO']=='I')
                  {
                      $accionEstado=ModuloGetURL('app','Facturacion','user','BuscarCuentaParaActivar',array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
                      $this->salida .= "    <form name=\"formaborrar\" action=\"$accionEstado\" method=\"post\">";
                      $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"ACTIVAR CUENTA\"></td>";
                      $this->salida .= "    </form>";
                  }
                  if(isset($_SESSION['CUENTAS']['PUNTOFACTURACION']))
                  {
                    $this->salida .= "    </tr>";
                    $this->salida .= "    <tr align=\"center\">";
                  }
                  if($Detalle)
                  {
                      $accion=ModuloGetURL('app','Facturacion','user','FormaMenuReliquidar',array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
                      $this->salida .= "    <form name=\"formaborrar\" action=\"$accion\" method=\"post\">";
                      $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"RELIQUIDAR\"></td>";
                      $this->salida .= "    </form>";
                  }
//PROCESO PARA FACTURAR
                  if(isset($_SESSION['CUENTAS']['PUNTOFACTURACION']))
                  {
											//$rutaVolver = ModuloGetURL('app','Facturacion','user','BuscarCuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'vars'=>$vars,'Nivel'=>$Nivel,'Transaccion'=>$Transaccion,'Mensaje'=>$mensaje,'Dev'=>$Dev,'Estado'=>$Estado));
											$rutaVolver = ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'vars'=>$vars,'Nivel'=>$Nivel,'Transaccion'=>$Transaccion,'Mensaje'=>$mensaje,'Dev'=>$Dev,'Estado'=>$Estado));
											$botones = $this->ReturnModuloExterno('app','Facturar','user');
					
											$this->salida .= $botones->FormaMostrarBotonesFacturar($Cuenta,$_SESSION['CUENTAS']['PUNTOFACTURACION']);
											$botones->SetActionVolver($rutaVolver);
									}
//FIN PROCESO PARA FACTURAR
                   //$this->salida .= "    </tr>";
            }
            elseif($_SESSION['CUENTAS']['SWCUENTAS']=='Cerradas')
            {
                    //$var=$this->DatosFactura($Cuenta);
                    IncludeLib("reportes/factura");
                    GenerarFactura($var);
                    $this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"FACTURA\" onclick=\"javascript:abreVentana()\"></td>";
                    IncludeLib("reportes/facturaconceptos");
                    GenerarFacturaConceptos($var);
                    $this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"FACTURA CONCEPTOS\" onclick=\"javascript:abreVentana2()\"></td>";

/*                    IncludeLib("reportes/hojacargos");
                    GenerarHojaCargos(array('numerodecuenta'=>$Cuenta));
                    $this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"HOJA CARGOS\" onclick=\"javascript:abreVentanaHC()\"></td>";*/
                    //IncludeLib("reportes/hojatransaccion");
                   // GenerarHojaTransaccion($var);
                   // $this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"HOJA TRANSACCION\" onclick=\"javascript:abreVentanaHT()\"></td>";

                /*  $var=$this->DatosFactura($Cuenta);
                  IncludeLib("reportes/factura");
                  GenerarFactura($var);
                  $this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"IMPRIMIR DETALLADA\" onclick=\"javascript:abreVentana()\"></td>";
                  IncludeLib("reportes/facturaconceptos");
                  GenerarFacturaConceptos($var);
                  $this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"IMPRIMIR CONCEPTOS\" onclick=\"javascript:abreVentana2()\"></td>";
                  */
                $this->salida .= "    </tr>";
            }

            if($Detalle)
            {
                //$this->salida .= "    <tr align=\"center\">";
                if($_SESSION['CUENTAS']['SWCUENTAS']!='Cerradas' AND sizeof($Detalle)>1 )
                {
                                        if($_SESSION['ESTADO']=='A')
                                        {
                      $msg='Esta Seguro que desea dividir la Cuenta No. '.$Cuenta.'.';
                      $arreglo=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado);
                      $accionEstado=ModuloGetURL('app','Facturacion','user','ConfirmarAccion',array('c'=>'app','m'=>'Facturacion','me2'=>'Cuenta','me'=>'TiposDivision','mensaje'=>$msg,'titulo'=>'DIVIDIR CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos,'arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
                      $this->salida .= "    <form name=\"formaborrar\" action=\"$accionEstado\" method=\"post\">";
                      $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"DIVIDIR CUENTA\"></td>";
                      $this->salida .= "    </form>";
                    }
                    else
                    {
                      $accionEstado=ModuloGetURL('app','Facturacion','user','TiposDivision',array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado));
                      $this->salida .= "    <form name=\"formaborrar\" action=\"$accionEstado\" method=\"post\">";
                      $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"DIVIDIR CUENTA\"></td>";
                      $this->salida .= "    </form>";
                    }
                    $accionPaq=ModuloGetURL('app','Facturacion','user','RealizarPaquetesCargos',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado));
                    $this->salida .= "    <form name=\"formaPaq\" action=\"$accionPaq\" method=\"post\">";
                    $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"PAQUETES\"></td>";
                    $this->salida .= "    </form>";
                }
/*                                IncludeLib("reportes/hojacargos");
                                GenerarHojaCargos(array('numerodecuenta'=>$Cuenta));
                                $this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"HOJA CARGOS\" onclick=\"javascript:abreVentanaHC()\"></td>";

                                IncludeLib("reportes/hojacargos2");
                                GenerarHojaCargos2(array('numerodecuenta'=>$Cuenta));
                                $this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"HOJA CARGOS2\" onclick=\"javascript:abreVentanaHT()\"></td>";

                                IncludeLib("reportes/hojacargos3");
                                GenerarHojaCargos3(array('numerodecuenta'=>$Cuenta));
                                $this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"HOJA CARGOS3\" onclick=\"javascript:abreVentanaHC3()\"></td>";*/

                                                //
                                                                $acchoja=ModuloGetURL('app','Facturacion','user','LlamarVentanaFinal',array('numerodecuenta'=>$Cuenta,'plan_id'=>$PlanId,'tipoid'=>$TipoId,'pacienteid'=>$PacienteId,'Nivel'=>$Nivel,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Transaccion'=>$Transaccion,'Dev'=>$Dev,'vars'=>$vars,'Estado'=>$Estado,'tiporeporte'=>'reportes'));
                                                                $this->salida .= "             <form name=\"reportes\" action=\"$acchoja\" method=\"post\">";
                                                                $this->salida .= "               <td><label class='label_mark'>Tipo Hoja Cargos: </label></td><td><select name=\"reporteshojacargos\" class=\"select\">";
                                                                //$this->salida .=" <option value='-1'>----SELECCIONE----</option>";
                                                                $reportes=$this->TraerReportesHojaCargos();
                                                                for($i=0; $i<sizeof($reportes); $i++)
                                                                {
                                                                        $this->salida .=" <option value=\"".$reportes[$i][ruta_reporte].",".$reportes[$i][titulo]."\">".$reportes[$i][titulo]."</option>";
                                                                }
                                                                $this->salida .= "              </select>";
                                                                $this->salida .= "              </td><td align = \"left\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VER\"><br></td></form>";
                                                //

                                //IncludeLib("reportes/hojatransaccion");
                                //GenerarHojaTransaccion($var);
                                //$this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"HOJA TRANSACCION\" onclick=\"javascript:abreVentanaHT()\"></td>";

                $this->salida .= "    </tr>";
            }
            $this->salida .= "    </table>";
            //fin botones
        }
        $this->salida .= "<p class=\"label_error\" align=\"center\">$mensaje</p>";
                //mensaje
                $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
                $this->salida .= $this->SetStyle("MensajeError");
                $this->salida .= "  </table>";
                //tabla pendientes cargar
                $y=BuscarPendientesCargar($Ingreso);
                if(!empty($y))
                {
                        $z=PendientesCargar($Ingreso);
                        $this->FormaPendientesCargar($z,$PlanId,$Cuenta,$TipoId,$PacienteId,$Nivel,$Cama,$Fecha,$Ingreso);
                }
                //fin pendientes cargar

                //habitaciones
                    unset($_SESSION['CUENTAS']['CAMA']['LIQ']);
                    if (!IncludeFile("classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php"))
                    {
                            die(MsgOut("Error al incluir archivo","El Archivo 'classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php' NO SE ENCUENTRA"));
                    }

                    $liqHab = new LiquidacionHabitaciones;
                    $hab = $liqHab->LiquidarCargosInternacion($Cuenta,false);

                    if(is_array($hab))
                    {
                            $_SESSION['CUENTAS']['CAMA']['LIQ']=$hab;
                            $this->FormaHabitaciones($hab,$PlanId,$Cuenta,$TipoId,$PacienteId,$Nivel,$Cama,$Fecha,$Ingreso);
                    }
                    elseif(empty($hab))
                    {       //ocurrio un error hay q mostrarlo
                                $this->salida .= "<p align=\"center\" class=\"label_error\">".$liqHab->Err()."<BR>".$liqHab->ErrMsg()."</p>";
                    }
                    /*IncludeLib('funciones_liquidacion_cargos');
                    $hab=GetDatosDias_X_Cargos($Ingreso,false);
                    if(!empty($hab) AND !empty($hab[0]['tipo_clase_cama_id']))
                    {
                            $this->FormaHabitaciones($hab,$PlanId,$Cuenta,$TipoId,$PacienteId,$Nivel,$Cama,$Fecha,$Ingreso);
                    }
                    elseif(!empty($hab) AND empty($hab[0]['tipo_clase_cama_id']))
                    {
                            $this->salida .= "<p class=\"label_error\" aling=\"center\">NO ESTA ASOCIADO LA CLASE CAMA (tipos_cama)</p>";
                    }*/
                //fin habitaciones

        //if(!$vars && !$Dev)
                //{
                
//Esto lo comente en el momento que se dise?o la nueva clase para mostrar los cargos de la cuenta  

//             if($Detalle)
//                         {
//                   $this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"90%\" align=\"center\"  class=\"modulo_table_list\">";
//                   $this->salida .= "    <tr align=\"center\" class=\"modulo_table_list_title\">";
//                   $this->salida .= "        <td>FECHA</td>";
//                   $this->salida .= "        <td width=\"46%\">CARGO</td>";
//                   $this->salida .= "        <td width=\"8%\">PRECIO UNI.</td>";
//                   $this->salida .= "        <td>CANT.</td>";
//                   $this->salida .= "        <td width=\"8%\">VALOR</td>";
//                   $this->salida .= "        <td width=\"8%\">VAL. NO CUBIERTO</td>";
//                   $this->salida .= "        <td width=\"5%\">FIRMA</td>";
//                                     $this->salida .= "        <td>DETALLE</td>";
//                   if($_SESSION['CUENTAS']['SWCUENTAS']!='Cerradas')
//                   { $this->salida .= "      <td colspan=\"2\">ACCION</td>"; }
//                   $this->salida .= "        <td>INT</td>";
//                   $this->salida .= "        <td>EXT</td>";
//                   $this->salida .= "        <td></td>";
//                   $this->salida .= "    </tr>";
//                   for($i=0;$i<sizeof($Detalle);)
//                   {
//                                             if(!empty($Detalle[$i][codigo_agrupamiento_id]))
//                                             {
//                                                     $d=$i;
//                                                     if(!empty($Detalle[$i][consecutivo]) AND !empty($Detalle[$i][cuenta_liquidacion_qx_id])){
//                             //en caso que no existan registros del acto quirurgico pero si medicamentos
//                             //asociados al acto quirurgico
//                             $Cantidad=$valor=$ValorNo=$ValEmpresa=0;
//                             while($Detalle[$i][codigo_agrupamiento_id]==$Detalle[$d][codigo_agrupamiento_id] || ($Detalle[$i][cuenta_liquidacion_qx_id]==$Detalle[$d][cuenta_liquidacion_qx_id] AND !empty($Detalle[$i][cuenta_liquidacion_qx_id])) )
//                             //while($Detalle[$i][codigo_agrupamiento_id]==$Detalle[$d][codigo_agrupamiento_id])
//                             {
//                               $Cantidad+=$Detalle[$d][cantidad];
//                               $valor+=$Detalle[$d][valor_cargo];
//                               $ValorNo+=$Detalle[$d][valor_nocubierto];
//                               $d++;
//                             }
//                             $des=$this->NombreCodigoAgrupamiento($Detalle[$i][codigo_agrupamiento_id]);
//                             if( $i % 2) $estilo='modulo_list_claro';
//                             else $estilo='modulo_list_oscuro';
//                             $this->salida .= "    <tr class=\"$estilo\" align=\"center\">";
//                             if(!empty($Detalle[$i][consecutivo]))
//                             {   $this->salida .= "        <td>".$this->FechaStamp($Detalle[$i][fecha_cargo])."</td>";   }
//                             else
//                             {   $this->salida .= "        <td>$FechaD</td>";    }
//                             $this->salida .= "        <td>$des[descripcion]</td>";
//                             $this->salida .= "        <td>".FormatoValor($Precio)."</td>";
//                             $this->salida .= "        <td>$Cantidad</td>";
//                             $this->salida .= "        <td>".FormatoValor($valor)."</td>";
//                             $this->salida .= "        <td>".FormatoValor($ValorNo)."</td>";
//                             $this->salida .= "        <td></td>";
//                             $this->salida .= "        <td>";
//                             $accionHRef=ModuloGetURL('app','Facturacion','user','DefinirForma',array('Transaccion'=>$Transaccion,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Cuenta'=>$Cuenta,'codigo'=>$Detalle[$i][codigo_agrupamiento_id],'doc'=>$des[bodegas_doc_id],'numeracion'=>$des[numeracion],'consecutivo'=>$Detalle[$i][consecutivo],'des'=>$des[descripcion],'qx'=>$des[cuenta_liquidacion_qx_id],'noFacturado'=>false));
//                             $this->salida .= "         <a href=\"$accionHRef\">VER..</a>   ";
//                             $this->salida .= "        </td>";
//                             $this->salida .= "       <td></td>";
//                             $this->salida .= "       <td></td>";
//                             $this->salida .= "       <td></td>";
//                             $this->salida .= "       <td></td>";
//                             $this->salida .= "       <td></td>";
//                           }else{
//                                                             $Cantidad=$valor=$ValorNo=$ValEmpresa=0;
//                                                             while($Detalle[$i][codigo_agrupamiento_id]==$Detalle[$d][codigo_agrupamiento_id] || ($Detalle[$i][cuenta_liquidacion_qx_id]==$Detalle[$d][cuenta_liquidacion_qx_id] AND !empty($Detalle[$i][cuenta_liquidacion_qx_id])) )
//                                                             //while($Detalle[$i][codigo_agrupamiento_id]==$Detalle[$d][codigo_agrupamiento_id])
//                                                             {
//                                                                 $Cantidad+=$Detalle[$d][cantidad];
//                                                                 $valor+=$Detalle[$d][valor_cargo];
//                                                                 $ValorNo+=$Detalle[$d][valor_nocubierto];
//                                                                 $d++;
//                                                             }
//                                                             $des=$this->NombreCodigoAgrupamiento($Detalle[$i][codigo_agrupamiento_id]);
//                                                             if( $i % 2) $estilo='modulo_list_claro';
//                                                             else $estilo='modulo_list_oscuro';
//                                                             $this->salida .= "    <tr class=\"$estilo\" align=\"center\">";
//                                                             if(!empty($Detalle[$i][consecutivo]))
//                                                             {       $this->salida .= "        <td>".$this->FechaStamp($Detalle[$i][fecha_cargo])."</td>";       }
//                                                             else
//                                                             {       $this->salida .= "        <td>$FechaD</td>";        }
//                                                             $this->salida .= "        <td>$des[descripcion]</td>";
//                                                             $this->salida .= "        <td>".FormatoValor($Precio)."</td>";
//                                                             $this->salida .= "        <td>$Cantidad</td>";
//                                                             $this->salida .= "        <td>".FormatoValor($valor)."</td>";
//                                                             $this->salida .= "        <td>".FormatoValor($ValorNo)."</td>";
//                                                             $this->salida .= "        <td></td>";
//                                                             $this->salida .= "        <td>";
//                                                             $accionHRef=ModuloGetURL('app','Facturacion','user','DefinirForma',array('Transaccion'=>$Transaccion,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Cuenta'=>$Cuenta,'codigo'=>$Detalle[$i][codigo_agrupamiento_id],'doc'=>$des[bodegas_doc_id],'numeracion'=>$des[numeracion],'consecutivo'=>$Detalle[$i][consecutivo],'des'=>$des[descripcion],'qx'=>$des[cuenta_liquidacion_qx_id],'noFacturado'=>false));
//                                                             $this->salida .= "         <a href=\"$accionHRef\">VER...</a>   ";
//                                                             $this->salida .= "        </td>";
//                                                             $this->salida .= "       <td></td>";
//                                                             $this->salida .= "       <td></td>";
//                                                             $this->salida .= "       <td></td>";
//                                                             $this->salida .= "       <td></td>";
//                                                             $this->salida .= "       <td></td>";
//                                                     }
//                                                     $i=$d;
//                                             }//fin if
//                                             else
//                                             {
//                                                     $NomCargo=$this->BuscarNombreCargo($Detalle[$i][tarifario_id],$Detalle[$i][cargo]);
//                                                     if( $i % 2) $estilo='modulo_list_claro';
//                                                     else $estilo='modulo_list_oscuro';
//                                                     $this->salida .= "    <tr class=\"$estilo\" align=\"center\">";
//                                                     $this->salida .= "        <td>".$this->FechaStamp($Detalle[$i][fecha_cargo])."</td>";
//                                                     $this->salida .= "        <td>$NomCargo[0]</td>";
//                                                     $this->salida .= "        <td>".FormatoValor($Detalle[$i][precio])."</td>";
//                                                     $this->salida .= "        <td>".round($Detalle[$i][cantidad])."</td>";
//                                                     $this->salida .= "        <td>".$Detalle[$i][valor_cargo]."</td>";
//                                                     $this->salida .= "        <td>".FormatoValor($Detalle[$i][valor_nocubierto])."</td>";
//                                                     $res=FirmaResultado($Detalle[$i][transaccion]);
//                                                     $img='';
//                                                     //hay resultado
//                                                     if(!empty($res))
//                                                     {
//                                                             $reporte= new GetReports();
//                                                             $mostrar=$reporte->GetJavaReport('app','Os_Listas_Trabajo_Apoyod_Agrupado','examenes_html',array('resultado_id'=>$res),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
//                                                             $nombre_funcion=$reporte->GetJavaFunction();
//                                                             $this->salida .=$mostrar;
//                                                             $this->salida .= "        <td><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/checksi.png\"></a></td>";
//                                                             unset($reporte);
//                                                     }
//                                                     else
//                                                     {  $this->salida .= "      <td></td>";  }
//                                                     $this->salida .= "        <td></td>";
//                                                     if($_SESSION['CUENTAS']['SWCUENTAS']!='Cerradas')
//                                                     {
//                                                             $accionM=ModuloGetURL('app','Facturacion','user','LlamaFormaModificar',array('Transaccion'=>$Detalle[$i][transaccion],'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Datos'=>$Detalle[$i]));
//                                                             $this->salida .= "    <td><a href=\"$accionM\">MODI</a></td>";
//                                                         /*  $mensaje='Esta seguro que desea eliminar este cargo.';
//                                                             $arreglo=array('Transaccion'=>$Detalle[$i][transaccion],'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
//                                                             $accionE=ModuloGetURL('app','Facturacion','user','ConfirmarAccion',array('c'=>'app','m'=>'Facturacion','me2'=>'Cuenta','me'=>'EliminarCargo','mensaje'=>$mensaje,'titulo'=>'ELIMINAR CARGO DE LA CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos,'arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
//                                                             */
//                                                         $accion=ModuloGetURL('app','Facturacion','user','DefinirForma',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'codigo'=>$_REQUEST['codigo'],'consecutivo'=>$consecutivo,'doc'=>$_REQUEST['doc'],'numeracion'=>$_REQUEST['numeracion'],'des'=>$des,'noFacturado'=>$noFacturado['facturado']));
//                                                              $accionE=ModuloGetURL('app','Facturacion','user','LlamarFormaEliminarCargo',array('Transaccion'=>$Detalle[$i][transaccion],'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
//                                                             $this->salida .= "    <td><a href=\"$accionE\">ELIM</a></td>";
//                                                     }
//                                                     else{
//                                                             $this->salida .= "       <td></td>";
//                                                             $this->salida .= "       <td></td>";
//                                                     }
//                                                     $D=$n=1;
//                                                     $imagenInt=$imagenExt='';
//                                                     if($Detalle[$i][interna]==='0')
//                                                     {  $imagenInt="no_autorizado.png";   $D=1; }
//                                                     elseif($Detalle[$i][interna] >100)
//                                                     {  $imagenInt="autorizado.png";   $D=0; }
//                                                     elseif($Detalle[$i][interna] ==1)
//                                                     {  $imagenInt="autorizadosiis.png";   $D=1; }
//                                                     if($Detalle[$i][externa]==='0')
//                                                     {  $imagenExt="no_autorizado.png";   $n=1; }
//                                                     elseif($Detalle[$i][externa] >100)
//                                                     {  $imagenExt="autorizado.png";   $n=0; }
//                                                     elseif($Detalle[$i][externa] ==1)
//                                                     {  $imagenExt="autorizadosiis.png";   $n=1; }
//                                                     $this->salida .= "       <td>";
//                                                     if($imagenInt)
//                                                     {  $this->salida .= "       <img src=\"".GetThemePath()."/images/$imagenInt\">"; }
//                                                     $this->salida .= "       </td>";
//                                                     $this->salida .= "       <td>";
//                                                     if($imagenExt)
//                                                     {  $this->salida .= "       <img src=\"".GetThemePath()."/images/$imagenExt\">";  }
//                                                     $this->salida .= "       </td>";
//                                                     if($D==0 OR $n==0)
//                                                     {  $this->salida .= "       <td><a href=\"javascript:ConsultaAutorizacion('DATOS DE LA AUTORIZACION','reports/$VISTA/datosautorizacioncargo.php',1000,250,'$TarifarioId','$Cargo',$Cuenta,".$Detalle[$i][interna].",0,'Int')\"><img src=\"".GetThemePath()."/images/informacion.png\" border=\"0\"></a></td>";  }
//                                                     else
//                                                     {  $this->salida .= "       <td></td>";  }
//                                                     $i++;
//                                             }//fin else
//                                     }//fin for
//                                     $this->salida .= "    </tr>";
//                                     $this->salida .= "  </table><br>";
//                                     }
//                     //tabla no facturados
//                                     if(empty($arre))
//                                     {
//                                             UNSET($_SESSION['CUENTAS']['ARREGLO_NOFACTURADOS']);
//                                             $x=$this->DetalleCuentaNoFacturados($Cuenta);
//                                             if(!empty($x))
//                                             {
//                                                     $this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"90%\" align=\"center\"  class=\"modulo_table_list\">";
//                                                     $this->salida .= "    <tr align=\"center\" class=\"modulo_table_list_claro\">";
//                                                     $accion=ModuloGetURL('app','Facturacion','user','CargosNoFacturados',array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'arre'=>$arre));
//                                                     $this->salida .= "       <td><a href=\"$accion\">VER CARGOS NO FACTURADOS</a></td>";
//                                                     $this->salida .= "    </tr>";
//                                                     $this->salida .= "  </table><br>";
//                                             }
//                                     }
//                                     else
//                                     {
//                                             $_SESSION['CUENTAS']['ARREGLO_NOFACTURADOS'] = $arre;
//                                             $this->FormaCargosNoFacturados($arre,$Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Fecha,$Ingreso,$Nombres,$Apellidos);
//                                     }//fin listado

//            fin comente                                    
                                    
                                    IncludeClass('DetalleCtaHTML','','app','Facturacion');  
                                    $this->IncludeJS("CrossBrowser");
                                    $accionM=ModuloGetURL('app','Facturacion','user','LlamaFormaModificar'); 
                                    $accionE=ModuloGetURL('app','Facturacion','user','LlamarFormaEliminarCargo'); 
                                    $accionDevol=ModuloGetURL('app','Facturacion','user','LlamaFormaDevolverIYMCta');                                
                                    $html = new DetalleCtaHTML();                                    
                                    $this->salida .= $html->CrearFormaDetalleCta($Cuenta,$_SESSION['CUENTAS']['SWCUENTAS'],$TipoId,$PacienteId,$Nivel,$PlanId,$Fecha,$Ingreso,$accionM,$accionE,$accionDevol,$this,$modificacionCargos=1);
                                     
       // }
        /*else
        {
            if($vars)
            {  $this->FormaDetalleMedicamentos($vars,$Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Cama,$Fecha,$Ingreso);  }
            if($Dev)
            {  $this->FormaDetalleMedicamentos($Dev,$Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Cama,$Fecha,$Ingreso);  }
        }*/
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

        /**
  * Muestra el detalle de la cuenta.
  * @access private
  * @return boolean
  * @param int numero de la cuenta
  * @param string tipo documento
  * @param int numero documento
  * @param string nivel
  * @param string plan_id
  * @param int numero de cama
  * @param date fecha de la cuenta
  * @param int ingreso
  * @param array arreglo con los datos de la cuenta
  * @param int numero de transaccion
  */
    function FormaCuentaCargosLiquidadosQX($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Cama,$Fecha,$Ingreso,$vars,$Transaccion,$mensaje,$Dev,$Estado,$arre,$id)
    {
        //IncludeLib("tarifario");
        //IncludeLib("funciones_facturacion");
                unset($_SESSION['CUENTA']['DIVISION']);
                unset($_SESSION['DIVISION']['CUENTA']);
                unset($_SESSION['DIVISION']['DIVISION']['ABONOS']);
        IncludeLib("funciones_admision");
        global $VISTA;
        //factura detalleda
        $RUTA = $_ROOT ."cache/factura.pdf";
        $mostrar ="\n<script>\n";
        $mostrar.="var rem=\"\";\n";
        $mostrar.="  function abreVentana(){\n";
        $mostrar.="    var nombre=\"\"\n";
        $mostrar.="    var url2=\"\"\n";
        $mostrar.="    var str=\"\"\n";
        $mostrar.="    var ALTO=screen.height\n";
        $mostrar.="    var ANCHO=screen.width\n";
        $mostrar.="    var nombre=\"REPORTE\";\n";
        $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.="    var url2 ='$RUTA';\n";
        $mostrar.="    rem = window.open(url2, nombre, str)};\n";
        //$mostrar.="</script>\n";
        //factura conceptos
        $RUTA = $_ROOT ."cache/facturaconceptos.pdf";
        //$mostrar ="\n<script language='javascript'>\n";
        $mostrar.="var rem=\"\";\n";
        $mostrar.="  function abreVentana2(){\n";
        $mostrar.="    var nombre=\"\"\n";
        $mostrar.="    var url2=\"\"\n";
        $mostrar.="    var str=\"\"\n";
        $mostrar.="    var ALTO=screen.height\n";
        $mostrar.="    var ANCHO=screen.width\n";
        $mostrar.="    var nombre=\"REPORTE\";\n";
        $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.="    var url2 ='$RUTA';\n";
        $mostrar.="    rem = window.open(url2, nombre, str)};\n";
        $RUTA = $_ROOT ."cache/hojacargos".$Cuenta.".pdf";
        $mostrar.="var rem=\"\";\n";
        $mostrar.="  function abreVentanaHC(){\n";
        $mostrar.="    var nombre=\"\"\n";
        $mostrar.="    var url2=\"\"\n";
        $mostrar.="    var str=\"\"\n";
        $mostrar.="    var ALTO=screen.height\n";
        $mostrar.="    var ANCHO=screen.width\n";
        $mostrar.="    var nombre=\"REPORTE\";\n";
        $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.="    var url2 ='$RUTA';\n";
        $mostrar.="    rem = window.open(url2, nombre, str)};\n";
        $RUTA = $_ROOT ."cache/hojacargos2".$Cuenta.".pdf";
        $mostrar.="var rem=\"\";\n";
        $mostrar.="  function abreVentanaHT(){\n";
        $mostrar.="    var nombre=\"\"\n";
        $mostrar.="    var url2=\"\"\n";
        $mostrar.="    var str=\"\"\n";
        $mostrar.="    var ALTO=screen.height\n";
        $mostrar.="    var ANCHO=screen.width\n";
        $mostrar.="    var nombre=\"REPORTE\";\n";
        $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.="    var url2 ='$RUTA';\n";
        $mostrar.="    rem = window.open(url2, nombre, str)};\n";
        $RUTA1 = $_ROOT ."cache/hojacargos3".$Cuenta.".pdf";
        $mostrar.="  function abreVentanaHC3(){\n";
        $mostrar.="    var nombre=\"\"\n";
        $mostrar.="    var url2=\"\"\n";
        $mostrar.="    var str=\"\"\n";
        $mostrar.="    var ALTO=screen.height\n";
        $mostrar.="    var ANCHO=screen.width\n";
        $mostrar.="    var nombre=\"REPORTE\";\n";
        $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.="    var url2 ='$RUTA1';\n";
        $mostrar.="    rem = window.open(url2, nombre, str)};\n";
        $mostrar.="</script>\n";
        $this->salida.="$mostrar";

        $Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
        $Apellidos=$this->BuscarApellidosPaciente($TipoId,$PacienteId);
        $TipoCuenta=$_SESSION['CUENTAS']['TIPOCUENTA'];
        if($vars)
        {
            $this->salida .= ThemeAbrirTabla('DETALLES DE LA CUENTA No. '.$Cuenta.' (Insumos y Medicamentos) '.$Nombres.' '.$Apellidos);
        }
        if($Dev)
        {
            $this->salida .= ThemeAbrirTabla('DETALLES DE LA CUENTA No. '.$Cuenta.' (Devoluci?n Insumos y Medicamentos) '.$Nombres.' '.$Apellidos);
        }
        if(!$Dev && !$vars)
        {
            $this->salida .= ThemeAbrirTabla('DETALLES DE CARGOS CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos);
        }
        $this->ConsultaAutorizacion();
        $this->EncabezadoEmpresa($Caja);
        $argu=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
        $this->Encabezado($PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$argu,$Cuenta);
        $this->TotalesCuenta($Cuenta);
        $this->salida .= "  </fieldset></td></tr></table><BR>";
                $accionT=ModuloGetURL('app','Facturacion','user','LlamaFormaCuantaPendientesCargar',array("Cuenta"=>$Cuenta,"TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"Nivel"=>$Nivel,"PlanId"=>$PlanId,"Cama"=>$Cama,"Fecha"=>$Fecha,"Ingreso"=>$Ingreso,"vars"=>$vars,"Transaccion"=>$Transaccion,"mensaje"=>$mensaje,"Dev"=>$Dev,"Estado"=>$Estado,"arre"=>$arre));
        $this->salida .= "           <form name=\"forma\" action=\"$accionT\" method=\"post\">";

                if($_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS']){
                    $this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
                    $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"4\">CARGOS DEL ACTO QUIRURGICO No. ".$_SESSION['Liquidacion_QX']['LIQUIDACION_ID']."</td></tr>";
                    $this->salida .= "    <tr class=\"modulo_list_oscuro\">";
                    $this->salida .= "    <td width=\"10%\" class=\"label\">ANESTESIOLOGO</td>";
                    $nombreTercero=$this->NombreTercero($_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS'][1][1]['liquidacion']['DA']['tipo_id_tercero'],$_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS'][1][1]['liquidacion']['DA']['tercero_id']);
                    $this->salida .= "    <td width=\"40%\">".$nombreTercero['nombre_tercero']."</td>";
                    $this->salida .= "    <td width=\"10%\" class=\"label\">AYUDANTE</td>";
                    $nombreTercero=$this->NombreTercero($_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS'][1][1]['liquidacion']['DY']['tipo_id_tercero'],$_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS'][1][1]['liquidacion']['DY']['tercero_id']);
                    $this->salida .= "    <td width=\"40%\">".$nombreTercero['nombre_tercero']."</td>";
                    $this->salida .= "    </tr>";
                    foreach($_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS'] as $indiceCirujano=>$Vector){
                        $this->salida .= "        <tr class=\"modulo_table_title\">";
                        $this->salida .= "         <td width=\"10%\">CIRUJANO ".$indiceCirujano."</td>";
                        $nombreTercero=$this->NombreTercero($Vector[1]['tipo_id_cirujano'],$Vector[1]['cirujano_id']);
                        $this->salida .= "         <td colspan=\"3\">".$nombreTercero['nombre_tercero']."</td>";
                        $this->salida .= "       </tr>";
                        foreach($Vector as $indiceProcedimiento=>$DatosQX){
                            $this->salida .= "    <tr class=\"modulo_list_oscuro\">";
                            $this->salida .= "      <td colspan=\"4\">";
                            $this->salida .= "       <table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
                            $descripciones=$this->DescripcionCargosCups($DatosQX['cargo_cups']);
                            $this->salida .= "       <tr class=\"modulo_list_claro\">";
                            $this->salida .= "        <td  width=\"10%\" class=\"label\">CARGO CUPS</td>";
                            $this->salida .= "        <td colspan=\"4\">".$DatosQX['cargo_cups']." - ".$descripciones['descripcion']."</td>";
                            $this->salida .= "       </tr>";
                            if($DatosQX['uvrs']){
                                $this->salida .= "       <tr class=\"modulo_list_claro\">";
                                $this->salida .= "        <td  width=\"10%\" class=\"label\">UVRS</td>";
                                $this->salida .= "        <td colspan=\"4\">".$DatosQX['uvrs']."</td>";
                                $this->salida .= "       </tr>";
                            }
                            $descripciones=$this->DescripcionCargosTarifario($DatosQX['tarifario_id']);
                            $this->salida .= "       <tr class=\"modulo_list_claro\">";
                            $this->salida .= "        <td  width=\"10%\" class=\"label\">EQUIVALENCIA</td>";
                            $this->salida .= "        <td colspan=\"4\">".$descripciones['tarifario']." ".$DatosQX['cargo']." - ".$DatosQX['descripcion']."</td>";
                            $this->salida .= "       </tr>";
                            $this->salida .= "          <tr class=\"modulo_table_list_title\">";
                            $this->salida .= "          <td width=\"10%\">".$indiceProcedimiento."</td>";
                            $this->salida .= "          <td width=\"20%\">CARGO</td>";
                            $this->salida .= "          <td width=\"10%\">%</td>";
                            $this->salida .= "          <td width=\"30%\">VALOR CUBIERTO</td>";
                            $this->salida .= "          <td>VALOR NO CUBIERTO</td>";
                            $this->salida .= "          </tr>";
                            foreach($DatosQX['liquidacion'] as $derecho=>$DatosDerecho){

                                $this->salida .= "        <tr class=\"modulo_list_claro\">";
                                $this->salida .= "        <td class=\"label\">$derecho</td>";
                                $descripciones=$this->DescripcionCargosTarifario($DatosDerecho['tarifario_id']);
                                $this->salida .= "        <td>".$descripciones['tarifario']." - ".$DatosDerecho['cargo']."</td>";
                                if($valoresManual==1){
                                    $this->salida .= "        <td align=\"center\"><input align=\"right\" type=\"text\" class=\"input-text\" size=\"8\" name=\"Porcentajes[$indiceCirujano][$indiceProcedimiento][liquidacion][$derecho]\" value=\"".$DatosDerecho['PORCENTAJE']."\"></td>";
                                }else{
                                    $this->salida .= "        <td align=\"right\">".$DatosDerecho['PORCENTAJE']."</td>";
                                }
                                if($valoresManual==1){
                                    $this->salida .= "        <td align=\"center\"><input align=\"right\" type=\"text\" class=\"input-text\" size=\"8\" name=\"valoresCubiertos[$indiceCirujano][$indiceProcedimiento][liquidacion][$derecho]\" value=\"".FormatoValor($DatosDerecho['valor_cubierto'])."\"></td>";
                                }else{
                                    $this->salida .= "        <td align=\"right\">".FormatoValor($DatosDerecho['valor_cubierto'])."</td>";
                                }
                                if($valoresManual==1){
                                    $this->salida .= "        <td align=\"center\"><input align=\"right\" type=\"text\" class=\"input-text\" size=\"8\" name=\"valoresNoCubiertos[$indiceCirujano][$indiceProcedimiento][liquidacion][$derecho]\" value=\"".FormatoValor($DatosDerecho['valor_no_cubierto'])."\"></td>";
                                }else{
                                    $this->salida .= "        <td align=\"right\">".FormatoValor($DatosDerecho['valor_no_cubierto'])."</td>";
                                }
                                $this->salida .= "        </tr>";
                            }
                            $this->salida .= "       </table>";
                            $this->salida .= "      </td>";
                            $this->salida .= "    </tr>";
                        }
                    }
                    $this->salida .= "    </table>";
                    $this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
                    $action=ModuloGetURL('app','Facturacion','user','CargarALaCuentaPaciente',array("NoLiquidacion"=>$_SESSION['Liquidacion_QX']['LIQUIDACION_ID'],"TipoDocumento"=>$TipoId,"Documento"=>$PacienteId,"externo"=>1,"Cuenta"=>$Cuenta,"TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"Nivel"=>$Nivel,"PlanId"=>$PlanId,
                    "Cama"=>$Cama,"Fecha"=>$Fecha,"Ingreso"=>$Ingreso,"vars"=>$vars,"Transaccion"=>$Transaccion,"mensaje"=>$mensaje,"Dev"=>$Dev,"Estado"=>$Estado,"arre"=>$arre,"id"=>$id));
                    $this->salida .= "    <tr><td align=\"left\"><a href=\"$action\"><img border = 0 src=\"".GetThemePath()."/images/cargar.png\"><b>&nbsp&nbsp;CARGAR A LA CUENTA</b></a></td></tr>";
                    $this->salida .= "    <tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"VOLVER\" name=\"Volver\"></td></tr>";
                    $this->salida .= "    </table>";
                }
            $this->salida .= "           </form>";
            $this->salida .= ThemeCerrarTabla();
      return true;

        }


        function FormaHabitaciones($hab,$Plan,$cuenta,$TipoId,$PacienteId,$Nivel,$Cama,$Fecha,$Ingreso)
        {
                unset($_SESSION['CUENTAS']['MOVIMIENTOS']);                
                $this->SetJavaScripts('DetalleCamas');
                $accion=ModuloGetURL('app','Facturacion','user','CargarHabitacion',array('Cuenta'=>$cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$Plan,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
                $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
                $this->salida .= "<table border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"90%\" align=\"center\"  class=\"modulo_table_list\">";
                $this->salida .= "    <tr align=\"center\" class=\"modulo_table_title\">";
                $this->salida .= "    <td colspan=\"6\">HABITACIONES</td>";
                $this->salida .= "    </tr>";
                $this->salida .= "    <tr align=\"center\" class=\"modulo_table_list_title\">";
                $this->salida .= "     <td width=\"8%\">TARIF.</td>";
                $this->salida .= "     <td width=\"8%\">CARGO</td>";
                $this->salida .= "     <td width=\"60%\">DESCRIPCION</td>";
                $this->salida .= "     <td width=\"8%\">PRECIO</td>";
                $this->salida .= "     <td width=\"8%\">CANTIDAD</td>";
                $this->salida .= "     <td width=\"8%\">TOTAL</td>";
                //$this->salida .= "     <td width=\"4%\"></td>";
                $this->salida .= "    </tr>";
                $total=0;
                for($i=0; $i<sizeof($hab); $i++)
                {
                        if( $i % 2) $estilo='modulo_list_claro';
                        else $estilo='modulo_list_oscuro';
                        $this->salida .= "    <tr class=\"$estilo\">";
                        $this->salida .= "     <td align=\"center\">".$hab[$i][tarifario_id]."</td>";
                        $this->salida .= "     <td align=\"center\">".$hab[$i][cargo]."</td>";
                        $this->salida .= "     <td>".$hab[$i][descripcion]."</td>";
                        $this->salida .= "     <td align=\"center\">".$hab[$i][precio_plan]."</td>";
                        $this->salida .= "     <td align=\"center\">".$hab[$i][cantidad]."</td>";
                        $this->salida .= "     <td align=\"center\">".$hab[$i][valor_cargo]."</td>";
                        //$this->salida .= "     <td align=\"center\"><input type=\"checkbox\" name=\"HAB$i\" value=\"".$i."\"></td>";
                        $this->salida .= "    </tr>";
                        $total +=$hab[$i][valor_cargo];
                }
                $this->salida .= "    <tr align=\"center\">";
                $this->salida .= "    <td colspan=\"5\" align=\"right\" class=\"label\">TOTAL ESTANCIA:</td>";
                $this->salida .= "    <td colspan=\"1\" align=\"right\" class=\"label\">".FormatoValor($total)."</td>";
                $this->salida .= "    </tr>";

                $this->salida .= "    <tr align=\"center\">";
                $camasMov=RetornarWinOpenDetalleCamas($Ingreso,'DETALLE DE MOVIMIENTOS','label');
                $this->salida .= "    <td colspan=\"3\" align=\"center\" class=\"label\">$camasMov</td>";
                $egreso = $this->ValidarEgresoPaciente($Ingreso);       
                if(!empty($egreso))
                {
                    $accion=ModuloGetURL('app','Facturacion','user','LlamarFormaLiquidacionManualHabitaciones',array('Cuenta'=>$cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$Plan,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
                    $this->salida .= "    <td colspan=\"3\" align=\"left\" class=\"label\"><a href=\"$accion\">LIQUIDACION MANUAL</a></td>";            
                    $this->salida .= "    </tr>";
                    $this->salida .= "    <tr>";
                    $accion=ModuloGetURL('app','Facturacion','user','LlamadoCargarHabitacionCuenta',array("EmpresaId"=>$_SESSION['CUENTAS']['EMPRESA'],'Cuenta'=>$cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$Plan,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
                    $this->salida .= "    <td colspan=\"3\" align=\"center\" class=\"label\"><a href=\"$accion\">CARGAR A LA CUENTA</a></td><td colspan=\"3\"><BR>&nbsp;</td>";
                    $this->salida .= "</form>";
                }
                else
                {   
                    $this->salida .= "  <td colspan=\"3\" align=\"center\" class=\"label_mark\">EL PACIENTE NO TIENE ORDEN DE SALIDA DE LA ESTACION</td>";              
                    
                }                
                $this->salida .= "</form>";
                $this->salida .= "    </tr>";
                $this->salida .= "  </table><br>";
        }
        
    function FormaLiquidacionManualHabitaciones($Cuenta,$TipoId,$PacienteId,$PlanId,$Nivel,$Fecha,$Ingreso,$mensaje)
    {    
        
        IncludeClass('LiquidacionHabitacionesCtaHTML','','app','Facturacion');
        $html = new LiquidacionHabitacionesCtaHTML();
        $accionEliminar=ModuloGetURL('app','Facturacion','user','EliminarCargoHabitacion');
        
        $accionModificar=ModuloGetURL('app','Facturacion','user','ModificarCargoHabitacion',
        array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,
        'PlanId'=>$PlanId,'Nivel'=>$Nivel,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
        
        $accionInsertar=ModuloGetURL('app','Facturacion','user','InsertarCargoHabitacion',
        array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,
        'PlanId'=>$PlanId,'Nivel'=>$Nivel,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
        
        $accionCargarCuenta=ModuloGetURL('app','Facturacion','user','LlamadoCargarHabitacionCuenta',
        array('EmpresaId'=>$_SESSION['CUENTAS']['EMPRESA'],'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,
        'PlanId'=>$PlanId,'Nivel'=>$Nivel,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
        
        $accionCancelar=ModuloGetURL('app','Facturacion','user','VolverDetalleCuenta',
        array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,
        'PlanId'=>$PlanId,'Nivel'=>$Nivel,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
        
        $this->salida .= $html->CrearFormaLiquidacionManualHabitaciones($_SESSION['CUENTAS']['EMPRESA'],$accionEliminar,$accionModificar,$accionInsertar,$accionCargarCuenta,$accionCancelar,
        $Cuenta,$TipoId,$PacienteId,$PlanId,$Nivel,$Fecha,$Ingreso,$mensaje);                        
        
        return true;
    }
    
    function FrmListaPacientesConSalida(){
      
      IncludeClass('ListadoPacientesconSalidaHTML','','app','Facturacion');
      $html = new ListadoPacientesconSalidaHTML();
      $accionSalir=ModuloGetURL('app','Facturacion','user','RegresarMenu');
      $this->salida .= $html->CrearFrmListaPacientesConSalida($_SESSION['CUENTAS']['EMPRESA'],$accionSalir);
      return true;  
        
    }


  /*Metodo javascript que abre la ventana emergente con los datos de una autorizacion
  * @access private
  */
  function ConsultaAutorizacion()
  {
            $this->salida .= "<SCRIPT>";
            $this->salida .= "function ConsultaAutorizacion(nombre, url, ancho, altura,Tarifario,Cargo,Cuenta,Autorizacion,Ayudas,tipo){";
            $this->salida .= " var str = 'width='+ancho+',height='+altura+',X=300,Y=800,resizable=no,status=no,scrollbars=yes';";
            $this->salida .= " var url2 = url+'?Tarifario='+Tarifario+'&Cargo='+Cargo+'&Cuenta='+Cuenta+'&Autorizacion='+Autorizacion+'&Ayudas='+Ayudas+'&Tipo='+tipo;";
            $this->salida .= " rem = window.open(url2, nombre, str);";
            $this->salida .= "  if (rem != null) {";
            $this->salida .= "     if (rem.opener == null) {";
            $this->salida .= "       rem.opener = self;";
            $this->salida .= "     }";
            $this->salida .= "  }";
            $this->salida .= "}";
            $this->salida .=  "</SCRIPT>";
  }

    /**
    *
    */
    function FormaPendientesCargar($arr,$Plan,$cuenta,$TipoId,$PacienteId,$Nivel,$Cama,$Fecha,$Ingreso)
    {           unset($_SESSION['Liquidacion_QX']);
                unset($_SESSION['LIQUIDACION_QX']);
                unset($_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS']);

                IncludeLib('funciones_admision');
                //IncludeLib('funciones_facturacion');
                IncludeLib('malla_validadora');
                $this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"90%\" align=\"center\"  class=\"modulo_table_list\">";
                $this->salida .= "    <tr align=\"center\" class=\"modulo_table_title\">";
                $this->salida .= "        <td class=\"label_error\"><img src=\"".GetThemePath()."/images/cargar.png\" border=\"0\">&nbsp;&nbsp;PENDIENTES POR CARGAR</td>";
                $this->salida .= "    </tr>";
                $this->salida .= "    <tr align=\"center\">";
                $this->salida .= "        <td>";
                $_SESSION['DATOS_ARREGLO']['CARGOS_PENDIENTES_CARGAR_CUENTA']=$arr;

                for($i=0; $i<sizeof($arr); $i++)
                {
                        $accion=ModuloGetURL('app','Facturacion','user','InsertarPendientesCargar',array('Cuenta'=>$cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$Plan,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'departamento'=>$arr[$i][departamento],'servicio'=>$arr[$i][servicio],'empresa'=>$arr[$i][empresa_id],'cu'=>$arr[$i][centro_utilidad],'ID'=>$arr[$i][procedimiento_pendiente_cargar_id]));
                        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
                        $this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"99%\" align=\"center\"  class=\"modulo_table_list\">";
                        $this->salida .= "    <tr align=\"center\" class=\"modulo_table_list_title\">";
                        $this->salida .= "        <td width=\"8%\">CUPS</td>";
                        $this->salida .= "        <td width=\"43%\">CARGO</td>";
                        $this->salida .= "        <td width=\"8%\">DEPARTAMENTO</td>";
                        $this->salida .= "        <td>USUARIO</td>";
                        $this->salida .= "        <td width=\"8%\">TIPO PROFESIONAL</td>";
                        $this->salida .= "        <td width=\"8%\">FECHA</td>";
                        $this->salida .= "        <td width=\"8%\">TIPO SALA</td>";
                        $this->salida .= "    </tr>";
                        if( $i % 2) $estilo='modulo_list_claro';
                        else $estilo='modulo_list_oscuro';
                        $this->salida .= "      <tr class=\"$estilo\" align=\"center\">";
                        $this->salida .= "       <td>".$arr[$i][cargo_cups]."</td>";
                        $this->salida .= "       <td>".$arr[$i][descups]."</td>";
                        $this->salida .= "       <td>".$arr[$i][desdpto]."</td>";
                        $this->salida .= "       <td>".$arr[$i][nombre]."</td>";
                        $this->salida .= "       <td>".$arr[$i][tipo]."</td>";
                        $this->salida .= "       <td>".FechaStamp($arr[$i][fecha])."</td>";
                        $disabled='';
                        if($arr[$i][sw_tipo_cargo]!='QX'){$disabled='disabled';}
                        $this->salida .= "            <td width=\"8%\" nowrap ><select $disabled name=\"TipoSala\" class=\"select\">";
                        $this->salida .="         <option value=\"-1\" selected>---seleccione---</option>";
                        $TiposSala=$this->TiposDeSalas();
                        for($x=0;$x<sizeof($TiposSala);$x++){
                            $value=$TiposSala[$x]['tipo_sala_id'].'/'.$TiposSala[$x]['sw_quirofano'];
                            $titulo=$TiposSala[$x]['descripcion'];
                            if($value==$arr[$x][tipo_sala_id]){
                                $this->salida .="     <option value=\"$value\" selected>$titulo</option>";
                            }else{
                                $this->salida .="     <option value=\"$value\">$titulo</option>";
                            }
                        }
                        $this->salida .= "       </select></td>";
                        $this->salida .= "    </tr>";
                        $malla='';
                        $malla=MallaValidadoraCargoCups($arr[$i][cargo_cups],$Plan,$arr[$i][servicio]);
                        //echo "<br><br>malla==>"; print_r($malla);
                        $this->salida .= "      <tr align=\"center\">";
                        $this->salida .= "       <td colspan=\"6\">".$malla['mensaje']."</td>";
                        $this->salida .= "    </tr>";

                        if(!empty($malla['validacion']))
                        {
                                $equi=ValdiarEquivalencias($Plan,$arr[$i][cargo_cups]);
                                if(!empty($equi))
                                {
                                        $this->salida .= "      <tr align=\"center\">";
                                        $this->salida .= "       <td colspan=\"7\">";
                                        $this->salida .= "     <br><table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
                                        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
                                        $this->salida .= "        <td>TARIFARIO</td>";
                                        $this->salida .= "        <td>CARGO</td>";
                                        $this->salida .= "        <td>DESCRIPCION</td>";
                                        $this->salida .= "        <td>PRECIO</td>";
                                        $this->salida .= "        <td></td>";
                                        $this->salida .= "      </tr>";
                                        for($j=0; $j<sizeof($equi); $j++)
                                        {
                                                if( $j % 2) $estilo='modulo_list_oscuro';
                                                else $estilo='modulo_list_claro';
                                                $this->salida .= "     <tr class=\"$estilo\">";
                                                $this->salida .= "        <td align=\"center\">".$equi[$j][tarifario_id]."</td>";
                                                $this->salida .= "        <td align=\"center\">".$equi[$j][cargo]."</td>";
                                                $this->salida .= "        <td>".$equi[$j][descripcion]."</td>";
                                                $this->salida .= "        <td align=\"center\">".FormatoValor($equi[$j][precio])."</td>";
                                                //hay varias
                                                if(sizeof($equi) >= 1)
                                                {
                                                        $x=PendientesCargarEquivalencias($arr[$i][procedimiento_pendiente_cargar_id],$equi[$j][cargo],$equi[$j][tarifario_id]);
                                                        if($x==1)
                                                        {  $this->salida .= "        <td align=\"center\"><input type = checkbox name= cargo".$equi[$j][tarifario_id]."".$equi[$j][cargo]." value=\"".$equi[$j][tarifario_id]."||".$equi[$j][cargo]."||".$arr[$i][cargo_cups]."||".$arr[$i][autorizacion_int]."||".$arr[$i][autorizacion_ext]."||".$arr[$i][tipo_id_tercero]."||".$arr[$i][tercero_id]."\" checked></td>";  }
                                                        else
                                                        {  $this->salida .= "        <td align=\"center\"><input type = checkbox name= cargo".$equi[$j][tarifario_id]."".$equi[$j][cargo]." value=\"".$equi[$j][tarifario_id]."||".$equi[$j][cargo]."||".$arr[$i][cargo_cups]."||".$arr[$i][autorizacion_int]."||".$arr[$i][autorizacion_ext]."||".$arr[$i][tipo_id_tercero]."||".$arr[$i][tercero_id]."\"></td>";  }
                                                }
                                                else
                                                {       //solo hay una equivalencia
                                                        $this->salida .= "        <td align=\"center\"><input type = checkbox name= cargo".$equi[$j][tarifario_id]."".$equi[$j][cargo]." value=\"".$equi[$j][tarifario_id]."||".$equi[$j][cargo]."||".$arr[$i][cargo_cups]."||".$arr[$i][autorizacion_int]."||".$arr[$i][autorizacion_ext]."||".$arr[$i][tipo_id_tercero]."||".$arr[$i][tercero_id]."\" checked></td>";
                                                }
                                                $this->salida .= "      </tr>";
                                        }
                                        $this->salida .= "     </table>";
                                        $this->salida .= "       </td>";
                                        $this->salida .= "    </tr>";
                                }
                        }
                        $this->salida .= "      <tr align=\"center\">";
                        $this->salida .= "       <td colspan=\"6\">";
                        $this->salida .= "         <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"LIQUIDAR\">";
                        $this->salida .= "       </td>";
                        $this->salida .= "    </tr>";
                        $this->salida .= "  </table><BR>";
                        $this->salida .= "</form>";
                }

                $this->salida .= "        </td>";
                $this->salida .= "    </tr>";
                $this->salida .= "  </table><br>";
    }


    function FormaCargosNoFacturados($arre,$Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Fecha,$Ingreso)
    {
            $this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"90%\" align=\"center\"  class=\"modulo_table_list\">";
            $this->salida .= "    <tr align=\"center\" class=\"modulo_table_list_claro\">";
            $accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'ocultar'=>'1'));
            $this->salida .= "       <td><a href=\"$accion\">OCULTAR CARGOS NO FACTURADOS</a></td>";
            $this->salida .= "    </tr>";
            $this->salida .= "  </table><br>";
            $this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"90%\" align=\"center\"  class=\"modulo_table_list\">";
            $this->salida .= "    <tr align=\"center\" class=\"modulo_table_title\"><td colspan=\"14\">CARGOS NO FACTURADOS</td></tr>";
            $this->salida .= "    <tr align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "        <td>FECHA</td>";
            $this->salida .= "        <td width=\"46%\">CARGO</td>";
            $this->salida .= "        <td width=\"8%\">PRECIO UNI.</td>";
            $this->salida .= "        <td>CANT.</td>";
            $this->salida .= "        <td width=\"8%\">VALOR</td>";
            $this->salida .= "        <td width=\"8%\">VAL. NO CUBIERTO</td>";
            $this->salida .= "        <td width=\"5%\">FIRMA</td>";
            $this->salida .= "        <td>DETALLE</td>";
            if($_SESSION['CUENTAS']['SWCUENTAS']!='Cerradas')
            { $this->salida .= "      <td colspan=\"2\">ACCION</td>"; }
            $this->salida .= "        <td>INT</td>";
            $this->salida .= "        <td>EXT</td>";
            $this->salida .= "        <td></td>";
            $this->salida .= "    </tr>";
            for($i=0;$i<sizeof($arre);)
            {
                    if(!empty($arre[$i][codigo_agrupamiento_id]))
                    {
                            $d=$i;
                            $Cantidad=$valor=$ValorNo=$ValEmpresa=0;
                            while($arre[$i][codigo_agrupamiento_id]==$arre[$d][codigo_agrupamiento_id])
                            {
                                $Cantidad+=$arre[$d][cantidad];
                                $valor+=$arre[$d][fac];
                                $ValorNo+=$arre[$d][valor_nocubierto];
                                $ValEmpresa +=$arre[$d][valor_cubierto];
                                $d++;
                            }
                            $des=$this->NombreCodigoAgrupamiento($arre[$i][codigo_agrupamiento_id]);
                            if( $i % 2) $estilo='modulo_list_claro';
                            else $estilo='modulo_list_oscuro';
                            $this->salida .= "    <tr class=\"$estilo\" align=\"center\">";
                            $this->salida .= "        <td>$FechaD</td>";
                            $this->salida .= "        <td>$des[descripcion]</td>";
                            $this->salida .= "        <td>".FormatoValor($Precio)."</td>";
                            $this->salida .= "        <td>$Cantidad</td>";
                            $this->salida .= "        <td>".FormatoValor($Valor)."</td>";
                            $this->salida .= "        <td>".FormatoValor($ValorNo)."</td>";
                            $this->salida .= "        <td>";
                            $accionHRef=ModuloGetURL('app','Facturacion','user','DefinirForma',array('Transaccion'=>$Transaccion,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Cuenta'=>$Cuenta,'codigo'=>$arre[$i][codigo_agrupamiento_id],'consecutivo'=>$arre[$i][consecutivo],'doc'=>$des[bodegas_doc_id],'numeracion'=>$des[numeracion],'des'=>$des[descripcion],'noFacturado'=>'0'));
                            $this->salida .= "         <a href=\"$accionHRef\">VER</a>   ";
                            $this->salida .= "        </td>";
                            $this->salida .= "       <td></td>";
                            $this->salida .= "       <td></td>";
                            $this->salida .= "       <td></td>";
                            $this->salida .= "       <td></td>";
                            $this->salida .= "       <td></td>";
                            $i=$d;
                    }//fin if
                    else
                    {
                            $NomCargo=$this->BuscarNombreCargo($arre[$i][tarifario_id],$arre[$i][cargo]);
                            if( $i % 2) $estilo='modulo_list_claro';
                            else $estilo='modulo_list_oscuro';
                            $this->salida .= "    <tr class=\"$estilo\" align=\"center\">";
                            $this->salida .= "        <td>".$this->FechaStamp($arre[$i][fecha_cargo])."</td>";
                            $this->salida .= "        <td>$NomCargo[0]</td>";
                            $this->salida .= "        <td>".FormatoValor($arre[$i][precio])."</td>";
                            $this->salida .= "        <td>".round($arre[$i][cantidad])."</td>";
                            $this->salida .= "        <td>".FormatoValor($arre[$i][valor_cargo])."</td>";
                            $this->salida .= "        <td>".FormatoValor($arre[$i][valor_nocubierto])."</td>";
                            $res=FirmaResultado($arre[$i][transaccion]);
                            $img='';
                            //hay resultado
                            if($res==1)
                            {  $this->salida .= "        <td><img src=\"".GetThemePath()."/images/checksi.png\"></td>";  }
                            else
                            {  $this->salida .= "      <td></td>";  }
                            $this->salida .= "        <td></td>";
                            if($_SESSION['CUENTAS']['SWCUENTAS']!='Cerradas')
                            {
                                    $accionM=ModuloGetURL('app','Facturacion','user','LlamaFormaModificar',array('Transaccion'=>$arre[$i]['transaccion'],'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Datos'=>$arre[$i]));
                                    $this->salida .= "    <td><a href=\"$accionM\">MODI</a></td>";
                                    /*$mensaje='Esta seguro que desea eliminar este cargo.';
                                    $arreglo=array('Transaccion'=>$arre[$i][transaccion],'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
                                    $accionE=ModuloGetURL('app','Facturacion','user','ConfirmarAccion',array('c'=>'app','m'=>'Facturacion','me2'=>'Cuenta','me'=>'EliminarCargo','mensaje'=>$mensaje,'titulo'=>'ELIMINAR CARGO DE LA CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos,'arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
                                    */
                                    $accionE=ModuloGetURL('app','Facturacion','user','LlamarFormaEliminarCargo',array('Transaccion'=>$arre[$i]['transaccion'],'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
                                    $this->salida .= "    <td><a href=\"$accionE\">ELIM</a></td>";
                            }
                            else{
                                    $this->salida .= "       <td></td>";
                                    $this->salida .= "       <td></td>";
                            }
                            $D=$n=1;
                            $imagenInt=$imagenExt='';
                            if($arre[$i][autorizacion_int]==='0')
                            {  $imagenInt="no_autorizado.png";   $D=1; }
                            elseif($arre[$i][autorizacion_int] >100)
                            {  $imagenInt="autorizado.png";   $D=0; }
                            elseif($arre[$i][autorizacion_int] ==1)
                            {  $imagenInt="autorizadosiis.png";   $D=1; }
                            if($arre[$i][autorizacion_ext]==='0')
                            {  $imagenExt="no_autorizado.png";   $n=1; }
                            elseif($arre[$i][autorizacion_ext] >100)
                            {  $imagenExt="autorizado.png";   $n=0; }
                            elseif($arre[$i][autorizacion_ext] ==1)
                            {  $imagenExt="autorizadosiis.png";   $n=1; }
                            $this->salida .= "       <td>";
                            if($imagenInt)
                            {  $this->salida .= "       <img src=\"".GetThemePath()."/images/$imagenInt\">"; }
                            $this->salida .= "       </td>";
                            $this->salida .= "       <td>";
                            if($imagenExt)
                            {  $this->salida .= "       <img src=\"".GetThemePath()."/images/$imagenExt\">";  }
                            $this->salida .= "       </td>";
                            if($D==0 OR $n==0)
                            {  $this->salida .= "       <td><a href=\"javascript:ConsultaAutorizacion('DATOS DE LA AUTORIZACION','reports/$VISTA/datosautorizacioncargo.php',1000,250,'$TarifarioId','$Cargo',$Cuenta,".$Detalle[$i][interna].",0,'Int')\"><img src=\"".GetThemePath()."/images/informacion.png\" border=\"0\"></a></td>";  }
                            else
                            {  $this->salida .= "       <td></td>";  }
                            $i++;
                    }//fin else
            }//fin for
            $this->salida .= "    </tr>";
            $this->salida .= "  </table><br>";
    }


  /**
  * Muestra el subdetalle de apoyos diagnosticos de una cuenta.
  * @access private
  * @return boolean
  * @param int numero de la cuenta
  * @param string tipo documento
  * @param int numero documento
  * @param string nivel
  * @param string plan_id
  * @param int numero de la cama
  * @param date fecha de la cuenta
  * @param int ingreso
  * @param int numero de transaccion
  * @param array arreglo con los datos de la cuenta
  */
    /*function FormaResultadosDiagnostico($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Cama,$Fecha,$Ingreso,$Transaccion,$Datos)
    {
        $Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
        $Apellidos=$this->BuscarApellidosPaciente($TipoId,$PacienteId);
        $this->salida .= ThemeAbrirTabla('DETALLES DE LA CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos.' TRANSACCION No. '.$Transaccion.'(Apoyos Diagnosticos)');
        $argu=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
        $this->Encabezado($PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$argu,$Cuenta);
        $this->salida .= " <table border=\"1\" cellspacing=\"2\" cellpadding=\"2\" width=\"85%\" align=\"center\"  >";
        $this->salida .= "    <tr class=\"modulo_table_title\">";
        $this->salida .= "        <td>DETALLE DE MEDIOS DIAGNOSTICOS CONSECUTIVO No. $Datos[0]</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr class=\"label\"><td><br>";
        $this->salida .= " <table border=\"0\" cellspacing=\"6\" cellpadding=\"6\" width=\"86%\" align=\"center\"  class=\"modulo_table_list\">";
        $this->salida .= "    <tr>";
        $this->salida .= "        <td class=\"modulo_table_list_title\" width=\"16%\">RESULTADO: </td>";
        $this->salida .= "        <td class=\"modulo_list_claro\">$Datos[9]</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr>";
        $this->salida .= "        <td class=\"modulo_table_list_title\">PROFESIONAL: </td>";
        $this->salida .= "        <td class=\"modulo_list_oscuro\"> Cod. $Datos[10]  &nbsp;&nbsp;$Datos[12]</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr>";
        $this->salida .= "        <td class=\"modulo_table_list_title\">OBSERVACION: </td>";
        $this->salida .= "        <td class=\"modulo_list_claro\">$Datos[11]</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "        </td>";
        $this->salida .= "    </tr>";
        $this->salida .= "  </table><br>";
        $accion=ModuloGetURL('app','Facturacion','user','DetalleApoyos',array('Transaccion'=>$Transaccion,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Cuenta'=>$Cuenta,'TotalCopago'=>$ValorCuota,'TotalNo'=>$ValorNo,'TotalEmpresa'=>$ValEmpresa,'ValTotal'=>$Valor));
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER AL DETALLE\"></p>";
        $this->salida .= "</form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }*


  /**
  * Muestra el detalle de las cirugias de una cuenta.
  * @access private
  * @return boolean
  * @param int numero de cuenta
  * @param string tipo documento
  * @param int numero documento
  * @param string nivel
  * @param string plan_id
  * @param int numero de la cama
  * @param date fecha de la cuenta
  * @param int ingreso
  * @param array arreglo con los datos de la cuenta
  * @param int numero de transaccion
  * @param int total del paciente
  * @param int total no cubierto
  * @param int total de la empresa
  * @param int valor total (cant. x precio)
  */
    function FormaCuentaCirugias($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Cama,$Fecha,$Ingreso,$var,$Transaccion,$TotalCopago,$TotalNo,$TotalEmpresa,$ValTotal,$accionQ)
    {
        IncludeLib("tarifario");
        $Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
        $Apellidos=$this->BuscarApellidosPaciente($TipoId,$PacienteId);
        $var=$this->DetalleCirugia($Transaccion);
        $this->salida .= ThemeAbrirTabla('DETALLES DE LA CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos.' TRANSACCION No. '.$Transaccion.' (Cirug?as)');
        $this->EncabezadoEmpresa($Caja);
        $argu=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
        $this->Encabezado($PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$argu,$Cuenta);
        $Fecha1=$this->FechaStamp($var[0][fecha_cirugia]);
        $Hora=$this->HoraStamp($var[0][fecha_cirugia]);
        $TotalPaciente=($TotalNo+$TotalCopago);
        if(!$TotalNo && !$TotalCopago && !$ValTotal)
        {
            $vars=$this->TotalesCirugia($Cuenta,$Transaccion);
            $TotalNo=$vars[valor_nocubierto];
            $TotalCopago=$vars[valor_cuota_paciente];
            $ValTotal=$vars[valor_cargo];
            $TotalEmpresa=$vars[valor_cubierto]-$TotalCopago;
            $TotalPaciente=($TotalNo+$TotalCopago);
        }
        $this->salida .= "     <table border=\"0\" width=\"90%\" align=\"center\" >";
        $this->salida .= "            <tr><td><fieldset><legend class=\"field\">DATOS CIRUGIA</legend>";
        $this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"99%\" align=\"center\"  class=\"modulo_table_list\">";
        $this->salida .= "    <tr>";
        $this->salida .= "        <td class=\"modulo_table_list_title\" width=\"15%\">No. OPERACION: </td>";
        $this->salida .= "        <td class=\"modulo_list_oscuro\">".$var[0][operacion]."</td>";
        $this->salida .= "        <td width=\"15%\" class=\"modulo_table_list_title\">QUIROFANO: </td>";
        $this->salida .= "        <td class=\"modulo_list_oscuro\">".$var[0][quirofano]."</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr>";
        $this->salida .= "        <td class=\"modulo_table_list_title\">FECHA CIRUGIA: </td>";
        $this->salida .= "        <td class=\"modulo_list_claro\">$Fecha1</td>";
        $this->salida .= "        <td class=\"modulo_table_list_title\">HORA CIRUGIA: </td>";
        $this->salida .= "        <td class=\"modulo_list_claro\">$Hora</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr>";
        $this->salida .= "        <td class=\"modulo_table_list_title\">DIAGNOSTICO: </td>";
        $this->salida .= "        <td class=\"modulo_list_oscuro\">".$var[0][diagnostico_nombre]."</td>";
        $Anestesista=$this->GetNombreProfesional($var[0][tipo_id_anestesista],$var[0][anestesista]);
        $this->salida .= "        <td class=\"modulo_table_list_title\">ANESTESISTA: </td>";
        $this->salida .= "        <td class=\"modulo_list_oscuro\">".$Anestesista."</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr>";
        $this->salida .= "        <td class=\"modulo_table_list_title\">AYUDANTE: </td>";
        $Ayudante=$this->GetNombreProfesional($var[0][tipo_id_ayudate],$var[0][ayudante]);
        $this->salida .= "        <td class=\"modulo_list_claro\">".$Ayudante."</td>";
        $this->salida .= "        <td class=\"modulo_table_list_title\">INSTRUMENTISTA: </td>";
        $this->salida .= "        <td class=\"modulo_list_claro\">".$var[0][instrumentista]."</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr>";
        $this->salida .= "        <td class=\"modulo_table_list_title\">CIRCULANTE 1: </td>";
        $Circulante1=$this->GetNombreProfesional($var[0][tipo_id_circulante1],$var[0][circulante1]);
        $this->salida .= "        <td class=\"modulo_list_oscuro\">".$Circulante1."</td>";
        $this->salida .= "        <td class=\"modulo_table_list_title\">CIRCULANTE 2: </td>";
        $Circulante2=$this->GetNombreProfesional($var[0][tipo_id_circulante2],$var[0][circulante2]);
        $this->salida .= "        <td class=\"modulo_list_oscuro\">".$Circulante2."</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "  </table>";
        $this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"99%\" align=\"center\"  class=\"modulo_table_list\">";
        $this->salida .= "    <tr align=\"center\" class=\"modulo_table_list_title\">";
        $this->salida .= "        <td>TOTAL PACIENTE</td>";
        $this->salida .= "        <td>VALOR TOTAL</td>";
        $this->salida .= "        <td>TOTAL NO CUBIERTO</td>";
        $this->salida .= "        <td>TOTAL COPAGO</td>";
        $this->salida .= "        <td>TOTAL EMPRESA</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr class=\"modulo_list_oscuro\" align=\"center\">";
        $this->salida .= "        <td>".FormatoValor($TotalPaciente)."</td>";
        $this->salida .= "        <td>".FormatoValor($ValTotal)."</td>";
        $this->salida .= "        <td>".FormatoValor($TotalNo)."</td>";
        $this->salida .= "        <td>".FormatoValor($TotalCopago)."</td>";
        $this->salida .= "        <td>".FormatoValor($TotalEmpresa)."</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "  </table>";
        $this->salida .= "          </fieldset></td></tr></table><BR>";
        if($var)
        {
              $this->salida .= " <table border=\"1\" cellspacing=\"2\" cellpadding=\"2\" width=\"98%\" align=\"center\"  >";
              $this->salida .= "    <tr class=\"modulo_table_title\">";
              $this->salida .= "        <td>DETALLE DE PROCEDIMIENTOS QUIRURGICOS</td>";
              $this->salida .= "    </tr>";
              for($i=0;$i<sizeof($var);$i++)
              {
                  $this->salida .= "    <tr>";
                  $this->salida .= "        <td><br>";
                  $this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"99%\" align=\"center\"  class=\"modulo_table_list\">";
                  $this->salida .= "    <tr>";
                  $this->salida .= "        <td  width=\"15%\" class=\"modulo_table_list_title\">CONSECUTIVO: </td>";
                  $this->salida .= "        <td class=\"modulo_list_claro\">".$var[$i][consecutivo]."</td>";
                  $this->salida .= "        <td  width=\"15%\" class=\"modulo_table_list_title\">VIA: </td>";
                  $this->salida .= "        <td class=\"modulo_list_claro\">".$var[$i][descripcion]."</td>";
                  $this->salida .= "    </tr>";
                  $this->salida .= "    <tr>";
                  $this->salida .= "        <td width=\"15%\"  class=\"modulo_table_list_title\" width=\"15%\">PROCEDIMIENTO: </td>";
                  $this->salida .= "        <td class=\"modulo_list_oscuro\">".$var[$i][procedimiento]."</td>";
                  $this->salida .= "        <td class=\"modulo_list_oscuro\" colspan=\"2\">".$var[$i][desc2]."</td>";
                  $this->salida .= "    </tr>";
                  if($var[$i][complicacion]){
                      $DescripcionC=$this->BuscarDiagnsotico($var[$i][complicacion]);
                      $this->salida .= "    <tr>";
                      $this->salida .= "        <td width=\"15%\"  class=\"modulo_table_list_title\" width=\"15%\">COMPLICACION: </td>";
                      $this->salida .= "        <td class=\"modulo_list_claro\">".$var[$i][complicacion]."</td>";
                      $this->salida .= "        <td class=\"modulo_list_claro\" colspan=\"2\">$DescripcionC</td>";
                      $this->salida .= "    </tr>";
                  }
                  $this->salida .= "    <tr>";
                  $this->salida .= "        <td width=\"15%\"  class=\"modulo_table_list_title\" width=\"15%\">CIRUJANO: </td>";
                  $this->salida .= "        <td class=\"modulo_list_oscuro\"  colspan=\"3\">".$var[$i][nombre]."</td>";
                  $this->salida .= "    </tr>";
                  $this->salida .= " </table><br>";
                  $this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"99%\" align=\"center\"  class=\"modulo_table_list\">";
                  $this->salida .= "    <tr align=\"center\" class=\"modulo_table_list_title\">";
                  $this->salida .= "        <td>TRANS.</td>";
                  $this->salida .= "        <td>CARGO</td>";
                  $this->salida .= "        <td>DESC. CARGO</td>";
                  $this->salida .= "        <td>PRECIO UNI.</td>";
                  $this->salida .= "        <td>CANT.</td>";
                  $this->salida .= "        <td>VALOR</td>";
                  $this->salida .= "        <td>VAL. NO CUBIERTO</td>";
                  $this->salida .= "        <td>COPAGO</td>";
                  $this->salida .= "        <td>VAL. EMPRESA</td>";
                  $this->salida .= "    </tr>";
                  $cant=$this->CantidadConsecutivos($var[$i][consecutivo]);
                  $TotalNo=$TotalCopago=$ValTotal=$TotalEmpresa=0;
                  if( $i % 2) $estilo='modulo_list_claro';
                  else $estilo='modulo_list_oscuro';
                  for($d=0;$d<$cant;$d++){
                      if( $i % 2) $estilo='modulo_list_claro';
                      else $estilo='modulo_list_oscuro';
                      $NomCargo=$this->BuscarNombreCargo($var[$i][tarifario_id],$var[$i][cargo]);
                      $ValEmpresa=($var[$i][valor_cubierto]-$var[$i][valor_cuota_paciente]);
                      $this->salida .= "    <tr class=\"$estilo\" align=\"center\">";
                      $this->salida .= "        <td>".$var[$i][transaccion]."</td>";
                      $this->salida .= "        <td>".$var[$i][cargo]."</td>";
                      $this->salida .= "        <td>".substr($NomCargo[0],0,30)."</td>";
                      $this->salida .= "        <td>".FormatoValor($var[$i][precio])."</td>";
                      $this->salida .= "        <td>".$var[$i][cantidad]."</td>";
                      $this->salida .= "        <td>".FormatoValor($var[$i][valor_cargo])."</td>";
                      $this->salida .= "        <td>".FormatoValor($var[$i][valor_nocubierto])."</td>";
                      $this->salida .= "        <td>".FormatoValor($var[$i][valor_cuota_paciente])."</td>";
                      $this->salida .= "        <td>".FormatoValor($ValEmpresa)."</td>";
                      $this->salida .= "    </tr>";
                      $TotalEmpresa+=$ValEmpresa;
                      $TotalNo+=$var[$i][valor_nocubierto];
                      $TotalCopago+=$var[$i][valor_cuota_paciente];
                      $ValTotal+=$var[$i][valor_cargo];
                    $i++;
                  }
                  $i=$i-1;
                  $TotalPaciente=$TotalNo+$TotalCopago;
                  if( $i % 2) $estilo='modulo_list_claro';
                  else $estilo='modulo_list_oscuro';
                  if($d==0) $estilo='modulo_list_claro';
                  $this->salida .= "    <tr class=\"$estilo\" align=\"center\">";
                  $this->salida .= "        <td colspan=\"5\"><b>TOTALES: </b></td>";
                  $this->salida .= "        <td><b>".FormatoValor($ValTotal)."</b></td>";
                  $this->salida .= "        <td><b>".FormatoValor($TotalNo)."</b></td>";
                  $this->salida .= "        <td><b>".FormatoValor($TotalCopago)."</b></td>";
                  $this->salida .= "        <td><b>".FormatoValor($TotalEmpresa)."</b></td>";
                  $this->salida .= "    </tr>";
                  $this->salida .= "  </table><br>";
                  $this->salida .= "        </td>";
                  $this->salida .= "    </tr>";
              }
              $this->salida .= "  </table><br>";
        }
        //otros cargos cirugia
        $vars=$this->DetalleCirugiaOtros($Transaccion);
        if($vars)
        {
              $this->salida .= " <table border=\"1\" cellspacing=\"2\" cellpadding=\"2\" width=\"98%\" align=\"center\"  >";
              $this->salida .= "    <tr class=\"modulo_table_title\">";
              $this->salida .= "        <td>DETALLE DE OTROS CARGOS QUIRURGICOS</td>";
              $this->salida .= "    </tr>";
              $this->salida .= "    <tr><td><br>";
              $this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"99%\" align=\"center\"  class=\"modulo_table_list\">";
              $this->salida .= "    <tr align=\"center\" class=\"modulo_table_list_title\">";
              $this->salida .= "        <td>TRANS.</td>";
              $this->salida .= "        <td>CARGO</td>";
              $this->salida .= "        <td>DESC. CARGO</td>";
              $this->salida .= "        <td>PRECIO UNI.</td>";
              $this->salida .= "        <td>CANT.</td>";
              $this->salida .= "        <td>VALOR</td>";
              $this->salida .= "        <td>VAL. NO CUBIERTO</td>";
              $this->salida .= "        <td>COPAGO</td>";
              $this->salida .= "        <td>VAL. EMPRESA</td>";
              $this->salida .= "    </tr>";
              $TotalNo=$TotalCopago=$ValTotal=$TotalEmpresa=0;
              for($i=0;$i<sizeof($vars);$i++)
              {
                        if( $i % 2) $estilo='modulo_list_claro';
                        else $estilo='modulo_list_oscuro';
                        //$NomCargo=$this->BuscarNombreCargo($vars[$i][tarifario_id],$vars[$i][cargo]);
                        $ValEmpresa=($vars[$i][valor_cubierto]-$vars[$i][valor_cuota_paciente]);
                        $this->salida .= "    <tr class=\"$estilo\" align=\"center\">";
                        $this->salida .= "        <td>".$vars[$i][transaccion]."</td>";
                        $this->salida .= "        <td>".$vars[$i][cargo]."</td>";
                        //$this->salida .= "        <td>".substr($NomCargo[0],0,30)."</td>";
                        $this->salida .= "        <td>".substr($vars[$i][descripcion],0,30)."</td>";
                        $this->salida .= "        <td>".FormatoValor($vars[$i][precio])."</td>";
                        $this->salida .= "        <td>".$vars[$i][cantidad]."</td>";
                        $this->salida .= "        <td>".FormatoValor($vars[$i][valor_cargo])."</td>";
                        $this->salida .= "        <td>".FormatoValor($vars[$i][valor_nocubierto])."</td>";
                        $this->salida .= "        <td>".FormatoValor($vars[$i][valor_cuota_paciente])."</td>";
                        $this->salida .= "        <td>".FormatoValor($ValEmpresa)."</td>";
                        $this->salida .= "    </tr>";
                        $TotalEmpresa+=$ValEmpresa;
                        $TotalNo+=$vars[$i][valor_nocubierto];
                        $TotalCopago+=$vars[$i][valor_cuota_paciente];
                        $ValTotal+=$vars[$i][valor_cargo];
              }
              $i=$i-1;
              $TotalPaciente=$TotalNo+$TotalCopago;
              if( $i % 2) $estilo='modulo_list_claro';
              else $estilo='modulo_list_oscuro';
              if($d==0) $estilo='modulo_list_claro';
              $this->salida .= "    <tr class=\"$estilo\" align=\"center\">";
              $this->salida .= "        <td colspan=\"5\"><b>TOTALES: </b></td>";
              $this->salida .= "        <td><b>".FormatoValor($ValTotal)."</b></td>";
              $this->salida .= "        <td><b>".FormatoValor($TotalNo)."</b></td>";
              $this->salida .= "        <td><b>".FormatoValor($TotalCopago)."</b></td>";
              $this->salida .= "        <td><b>".FormatoValor($TotalEmpresa)."</b></td>";
              $this->salida .= "    </tr>";
              $this->salida .= "  </table><br>";
              $this->salida .= "        </td>";
              $this->salida .= "    </tr>";
              $this->salida .= "  </table><br>";
        }
        if(!$accionQ)
        {
            $accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
            $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
            $this->salida .= "<p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER AL DETALLE\"></p>";
            $this->salida .= "</form>";
        }
        else
        {
            $this->salida .= "<form name=\"formabuscar\" action=\"$accionQ\" method=\"post\">";
            $this->salida .= "<p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></p>";
            $this->salida .= "</form>";
        }
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

 /**
  * Muestra los cargos que inserto con sus totales y la opcion de insertar un nuevo cargo.
  * @access private
  * @return boolean
  * @param int numero de la cuenta
  * @param string tipo documento
  * @param int numero documento
  * @param string nivel
  * @param string plan_id
  * @param int ingreso
  * @param date fecha de la cuenta
  */
  function FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var,$Ayudas,$Cobertura)
  {
        IncludeLib("tarifario");
        $Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
        $Apellidos=$this->BuscarApellidosPaciente($TipoId,$PacienteId);
        $Var=$this->CoutaPaciente($PlanId,$Nivel);
        $Copago=$Var[copago];
        $PorPaciente=$Var[cuota_moderadora];
        $Maximo=$Var[copago_maximo];
        $Minimo=$Var[copago_minimo];
        $this->salida .= ThemeAbrirTabla('AGREGAR CARGO A LA CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos);
        $this->EncabezadoEmpresa($Caja);
        $argu=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
        $this->Encabezado($PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$argu,$Cuenta);
        $datos=$this->DatosTmpCuentas($Cuenta);
        $this->salida .= "  <p class=\"label_error\" align=\"center\">$mensaje</p>";
        //$Apoyo=$this->DatosTmpAyudas($Cuenta);
        //$Ayudas=$this->DatosAyudasPasa($Cuenta);//cuando ya existe un cargo de agrupamiento
        //if(!$Apoyo && sizeof($datos)==1 && !$D) $f=true;

        if(sizeof($datos)==1 && !$D) $f=true;

        if($datos AND empty($D))
        { //$D existe si va a modificar
            if(sizeof($datos)==1 && !$D) $Paso=1;
            if(sizeof($datos)>1 || $Paso==1)
            {
                  $this->salida .= " <table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"93%\" align=\"center\" >";
                  $this->salida .= "    <tr align=\"center\" class=\"modulo_table_list_title\">";
                  $this->salida .= "        <td>DEPARTAMENTO</td>";
                  $this->salida .= "        <td>CARGO</td>";
                  $this->salida .= "        <td width=\"40%\">DESCRIPCION</td>";
                  $this->salida .= "        <td width=\"9%\">PRECIO</td>";
                  $this->salida .= "        <td>CANT.</td>";
                  $this->salida .= "        <td width=\"9%\">VALOR</td>";
                  $this->salida .= "        <td width=\"9%\">VAL. NO CUBIERTO</td>";
                  $this->salida .= "        <td width=\"9%\">VAL. CUBIERTO</td>";
                  $this->salida .= "        <td></td>";
                  $this->salida .= "        <td></td>";
                  $this->salida .= "    </tr>";
                  $TotalCub=$ValTotal=$TotalNo=$TotalCopago=$TotalEmpresa=$ValTotalPaciente=0;
                  $k=0;
                  for($i=0; $i<sizeof($datos);$i++)
                  {
                                                $Datos=$datos;
                                                $C=$Datos[$i][cargo];
                                                $x=1;
                                                $Valor=$Datos[$i][valor_cargo];
                                                $ValTotal+=$Valor;
                                                $ValorNo=$Datos[$i][valor_nocubierto];
                                                $TotalNo+=$ValorNo;
                                                $ValorCub=$Datos[$i][valor_cubierto];
                                                $TotalCub+=$ValorCub;
                                                $ValPac=$Datos[$i][valor_cuota_paciente];
                                                $TotalCopago+=$ValPac;
                                                $ValTotalPaciente+=$ValPac;
                                                $ValEmpresa=$Datos[$i][valor_cubierto]-$Datos[$i][valor_cuota_paciente];
                                                $TotalEmpresa+=$ValEmpresa;
                                                $Descripcion=$this->BuscarNombreCargo($Datos[$i][tarifario_id],$Datos[$i][cargo]);
                                                $Dpto=$this->BuscarNombreDpto($Datos[$i][departamento]);
                                                $c=round($Datos[$i][cantidad]);
                                                if( $k % 2) $estilo='modulo_list_claro';
                                                else $estilo='modulo_list_oscuro';
                                                $this->salida .= "    <tr class=\"$estilo\" align=\"center\">";
                                                $this->salida .= "        <td>$Dpto</td>";
                                                $this->salida .= "        <td>$C</td>";
                                                $this->salida .= "        <td>$Descripcion[0]</td>";
                                                $this->salida .= "        <td>".FormatoValor($Datos[$i][precio])."</td>";
                                                $this->salida .= "        <td>$c</td>";
                                                $this->salida .= "        <td>".FormatoValor($Valor)."</td>";
                                                $this->salida .= "        <td>".FormatoValor($ValorNo)."</td>";
                                                $this->salida .= "        <td>".FormatoValor($ValorCub)."</td>";
                                                //$this->salida .= "        <td>".FormatoValor($ValEmpresa)."</td>";
                                                $accionModificar=ModuloGetURL('app','Facturacion','user','LlamaFormaModificarCargoTmp',array('Transaccion'=>$Datos[$i][transaccion],'Datos'=>$datos[$i],'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
                                                $this->salida .= "        <td><a href=\"$accionModificar\" alt=\"Modificar los cargos del paciente\">MODI</a></td>";
                                                $accionEliminar=ModuloGetURL('app','Facturacion','user','EliminarCargoTmp',array('Transaccion'=>$Datos[$i][transaccion],'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
                                                $this->salida .= "        <td><a href=\"$accionEliminar\">ELIM</a></td>";
                                                $this->salida .= "    </tr>";
                                                $k++;
                                    }
                            }
                }

        if($datos)
        {
            if($x==1)
            {
                if( $j % 2) $estilo='modulo_list_claro';
                else $estilo='modulo_list_oscuro';
                $this->salida .= "    <tr class=\"$estilo\" align=\"center\">";
                $this->salida .= "        <td colspan=\"5\"><b>TOTALES: </b></td>";
                $this->salida .= "        <td><b>".FormatoValor($ValTotal)."</b></td>";
                $this->salida .= "        <td><b>".FormatoValor($TotalNo)."</b></td>";
                $this->salida .= "        <td><b>".FormatoValor($TotalCub)."</b></td>";
                //$this->salida .= "        <td><b>".FormatoValor($TotalEmpresa)."</b></td>";
                $this->salida .= "        <td colspan=\"2\"></td>";
                $this->salida .= "    </tr>";
                $this->salida .= "    </table><br>";
            }
        }
                global $_ROOT;
                $sw=ModuloGetVar('app','Facturacion','sw_gravar_cuota_paciente');
                $this->salida .= "\n<script>\n";
                $this->salida .= "var rem=\"\";\n";
                $this->salida .= "  function abrirVentana(){\n";
                $this->salida .= "    var car='';\n";
                $this->salida .= "    car=document.newcargo.TipoCargo.value;\n";
                $this->salida .= "    if(car==-1){\n";
                $this->salida .= "      alert('Debe elegir el tipo del Cargo.');\n";
                $this->salida .= "    }\n";
                $this->salida .= "    else{\n";
                $this->salida .= "      document.newcargo.TipoCargo.value=car;\n";
                $this->salida .= "      var nombre='';\n";
                $this->salida .= "      var url2='';\n";
                $this->salida .= "      var str='';\n";
                $this->salida .= "      var ALTO=screen.height;\n";
                $this->salida .= "      var ANCHO=screen.width;\n";
                $this->salida .= "      nombre=\"buscador_General\";\n";
                $this->salida .= "      str =\"ANCHO,ALTO,resizable=no,status=no,scrollbars=yes\";\n";
                $this->salida .= "      url2 ='".$_ROOT."classes/classbuscador/buscador.php?tipo=InsertarCargo&forma=newcargo&sql='+car+'&plan=$PlanId&departamento='+document.getElementById('Departamento').value;\n";
                $this->salida .= "      rem = window.open(url2, nombre, str);\n";
                $this->salida .= "    }\n";
                $this->salida .= "  }\n";
                $this->salida .= "</script>\n";
                if($D){
                        $accion=ModuloGetURL('app','Facturacion','user','ModificarCargoTmp',array('Transaccion'=>$D[transaccion],'Ayuda'=>$Ayuda));
                        $Boton='MODIFICAR CARGO';
                        $Modi=true;
                }
                else {
                        $accion=ModuloGetURL('app','Facturacion','user','InsertarCargoTmp', array('PorPaciente'=>$PorPaciente,'Maximo'=>$Maximo,'Minimo'=>$Minimo));
                        $Boton='AGREGAR CARGO..';
                }
                $this->salida .= " <form name=\"newcargo\" action=\"$accion\" method=\"post\">";

                $FechaCargo=date("d/m/Y");
                if($D)
                {
                        $Cobertura=($D[valor_cubierto]/$D[valor_cargo])*100;
                        $Dpto=$D[departamento];
                        $x=$this->BuscarNombreCargo($D[tarifario_id],$D[cargo]);
                        $Descripcion=$x[0];
                        $FechaCargo=$this->FechaStamp($D[fecha_cargo]);
                        if(!$FechaCargo)
                        {   $FechaCargo=$this->FechaStamp($D[fecha_registro]);   }
                        $Cant=round($D[cantidad]);
                        $Gravamen=$D[gravamen_valor_nocubierto]+$D[gravamen_valor_cubierto];
                        $ValEmp=$D[valor_cubierto];
                        $_REQUEST['MedInt']=$D[tipo_tercero_id]."||".$D[tercero_id];
                }
                else
                {
                        $Dpto=$this->Departamento;
                        $Descripcion='';
                        $Cant=1;
                }
                $this->salida .= " <table border=\"0\" width=\"90%\" align=\"center\"  class=\"normal_10\">";
                $this->salida .= "   <tr><td><fieldset><legend class=\"field\">AGREGAR CARGO</legend>";
                $this->salida .= "     <table height=\"74\" border=\"0\" width=\"98%\" align=\"center\" class=\"normal_10\">";
                $this->salida .= "              <input type=\"hidden\" name=\"PlanId\" value=\"$PlanId\">";
                $this->salida .= "              <input type=\"hidden\" name=\"PacienteId\" value=\"$PacienteId\">";
                $this->salida .= "              <input type=\"hidden\" name=\"TipoId\" value=\"$TipoId\">";
                $this->salida .= "              <input type=\"hidden\" name=\"Nivel\" value=\"$Nivel\">";
                $this->salida .= "              <input type=\"hidden\" name=\"Ingreso\" value=\"$Ingreso\">";
                $this->salida .= "              <input type=\"hidden\" name=\"Fecha\" value=\"$Fecha\">";
                $this->salida .= "              <input type=\"hidden\" name=\"Cuenta\" value=\"$Cuenta\">";

                /*$this->salida .= "              <input type=\"hidden\" name=\"TarifarioId\" value=\"".$D[tarifario_id]."\">";
                $this->salida .= "              <input type=\"hidden\" name=\"GrupoTarifario\">";
                $this->salida .= "              <input type=\"hidden\" name=\"SubGrupoTarifario\">";
                $this->salida .= "              <input type=\"hidden\" name=\"Gravamen\" value=\"$Gravamen\">";
                $this->salida .= "              <input type=\"hidden\" name=\"Cobertura\" value=\"$Cobertura\">";
                $this->salida .= "              <input type=\"hidden\" name=\"ValorCubierto\">";
                $this->salida .= "              <input type=\"hidden\" name=\"Consecutivo\" value=\"".$D[consecutivo]."\">";
                $this->salida .= "              <input type=\"hidden\" name=\"ValorCargo\" value=\"".$D[valor_cargo]."\">";
                $this->salida .= "              <input type=\"hidden\" name=\"Cons\" value=\"".$D[1]."\">";
                $this->salida .= "              <input type=\"hidden\" name=\"Porcentaje\">";
                $this->salida .= "              <input type=\"hidden\" name=\"Swcantidad\">";
*/
                $this->salida .= "       <tr>";
                $this->salida .= "         <td class=\"label\" width=\"18%\" >DEPARTAMENTO: </td>";
                $this->salida .= "         <td><select id=\"Departamento\" name=\"Departamento\" class=\"select\">";
                $departamento=$this->Departamentos();
                $this->BuscarDepartamento($departamento,$d=false,$Dpto);
                $this->salida .= "         </select></td>";
                $this->salida .= "       <td>&nbsp;</td>";
                $this->salida .= "       <td class=\"".$this->SetStyle("Cargo")."\">CARGO: </td>";
                if($Modi){
                        $this->salida .= "   <td><input type=\"text\" class=\"input-text\" name=\"Cargo\" size=\"10\" value=\"".$D[cargo_cups]."\" readonly></td>";
                        $this->salida .= "   <td colspan=\"2\"></td>";
                        $this->salida .= "              <input type=\"hidden\" name=\"TarifarioId\" value=\"".$D[tarifario_id]."\">";
                        $this->salida .= "              <input type=\"hidden\" name=\"CargoTarifario\" value=\"".$D[cargo]."\">";
                }
                else
                {
                        $this->salida .= "   <td><input type=\"text\" class=\"input-text\" name=\"Cargo\" size=\"10\" value=\"".$D[cargo]."\"></td>";
                        $this->salida .= "       <td>&nbsp;</td>";
                        $this->salida .= "                 <td colspan=\"2\"><select name=\"TipoCargo\" class=\"select\">";
                        $this->salida .= "                     <option value=\"\">-- TODOS LOS CARGOS --</option>";
                        $tipo=$this->TiposSolicitud();
                        for($i=0; $i<sizeof($tipo); $i++)
                        {
                                $this->salida .= "                     <option value=\"".$tipo[$i][grupo_tipo_cargo]."\">".$tipo[$i][descripcion]."</option>";
                        }
                        $this->salida .= "                 </select></td>";
                        $this->salida .= "   <td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"BUSCAR\" onclick=abrirVentana()></td>";
                }
                $this->salida .= "              </tr>";
                $this->salida .= "              <tr>";
                $this->salida .= "                <td class=\"label\">DESCRIPCION: </td>";
                $this->salida .= "                <td colspan=\"8\"><textarea cols=\"120\" rows=\"2\" class=\"textarea\"name=\"Descripcion\" readonly>$Descripcion</textarea></td>";
                $this->salida .= "                <td>&nbsp;</td>";
                $this->salida .= "              </tr>";
                $this->salida .= "              <tr>";
                $this->salida .= "                <td class=\"".$this->SetStyle("Cantidad")."\">CANTIDAD: </td>";
                $this->salida .= "                <td><input type=\"text\" class=\"input-text\" name=\"Cantidad\" size=\"5\" value=\"$Cant\"></td>";
                $this->salida .= "                <td>&nbsp;</td>";
                $this->salida .= "                <td></td>";
                $this->salida .= "                <td></td>";
                $this->salida .= "                <td>&nbsp;</td>";
                $this->salida .= "                  <td class=\"".$this->SetStyle("FechaCargo")."\">FECHA CARGO: </td>";
                $this->salida .= "                <td><input type=\"text\" name=\"FechaCargo\" value=\"$FechaCargo\" size=\"10\" class=\"input-text\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\">&nbsp;&nbsp;";
                $this->salida .=   ReturnOpenCalendario('newcargo','FechaCargo','/')."</td>";
                $this->salida .= "              </tr>";
                /*
                $this->salida .= "              <tr>";
                $this->salida .= "                <td class=\"label\">PRECIO: </td>";
                $this->salida .= "                <td><input type=\"text\" class=\"input-text\" name=\"Precio\" size=\"10\" value=\"".$D[precio]."\" readonly></td>";
                $this->salida .= "                <td>&nbsp;</td>";
                $this->salida .= "                <td class=\"".$this->SetStyle("Cantidad")."\">CANTIDAD: </td>";
                $this->salida .= "                <td><input type=\"text\" class=\"input-text\" name=\"Cantidad\" size=\"5\" value=\"$Cant\"></td>";
                $this->salida .= "                <td>&nbsp;</td>";
                $this->salida .= "                  <td class=\"".$this->SetStyle("FechaCargo")."\">FECHA CARGO: </td>";
                $this->salida .= "                <td><input type=\"text\" name=\"FechaCargo\" value=\"$FechaCargo\" size=\"10\" class=\"input-text\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\">&nbsp;&nbsp;";
                $this->salida .=   ReturnOpenCalendario('newcargo','FechaCargo','/')."</td>";
                $this->salida .= "              </tr>";
                */
                $this->salida .= "              <tr>";
                $this->salida .= "                <td class=\"label\">PROFESIONAL: </td>";
                $this->salida .= "                 <td colspan=\"7\"><select name=\"MedInt\" class=\"select\">";
                $this->salida .= "                     <option value=\"\">-------SELECCIONE-------</option>";
                $pro=$this->Profesionales();
                for($i=0; $i<sizeof($pro); $i++)
                {
                        if($pro[$i][tipo_id_tercero]."||".$pro[$i][tercero_id]==$_REQUEST['MedInt'])
                        {  $this->salida .=" <option value=\"".$pro[$i][tipo_id_tercero]."||".$pro[$i][tercero_id]."\" selected>".$pro[$i][nombre]."</option>";  }
                        else
                        {  $this->salida .=" <option value=\"".$pro[$i][tipo_id_tercero]."||".$pro[$i][tercero_id]."\">".$pro[$i][nombre]."</option>";  }
                }
                $this->salida .= "                 </select></td>";
                $this->salida .= "              </tr>";
                $this->salida .= "             </table>";
                $this->salida .= "          </fieldset></td></tr></table>";
                $this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"45%\" align=\"center\"  >";
                $this->salida .= "    <tr align=\"center\">";
                $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"$Boton\"></td>";
                $this->salida .= "    </form>";
                $accionEliminarTodos=ModuloGetURL('app','Facturacion','user','EliminarTodosCargos',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
                $this->salida .= "    <form name=\"formaborrar\" action=\"$accionEliminarTodos\" method=\"post\">";
                $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"ELIMINAR TODOS LOS CARGOS\"></td>";
                $this->salida .= "    </form>";
                $accionGuardarTodos=ModuloGetURL('app','Facturacion','user','GuardarTodosCargos',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
                $this->salida .= "    <form name=\"formaguardar\" action=\"$accionGuardarTodos\" method=\"post\">";
                $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"GUARDAR TODOS LOS CARGOS\"></td>";
                $this->salida .= "    </form>";
                $accionCancelar=ModuloGetURL('app','Facturacion','user','EliminarTodosCargos',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
                $this->salida .= "    <form name=\"formaguardar\" action=\"$accionCancelar\" method=\"post\">";
                $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\"></td>";
                $this->salida .= "    </form>";
                $this->salida .= "    </tr>";
                $this->salida .= "    </table><br>";
                $this->salida .= ThemeCerrarTabla();
                return true;
  }


 /**
  * Se utilizada listar en el combo los diferentes tipo de identifiacion de los pacientes
  * @access private
  * @return void
  */
  function BuscarIdPaciente($tipo_id,$TipoId='')
  {
        foreach($tipo_id as $value=>$titulo)
        {
          if($value==$TipoId){
            $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
          }else{
            $this->salida .=" <option value=\"$value\">$titulo</option>";
          }
        }
  }

 /**
  * Forma para los mansajes
  * @access private
  * @return void
  */
  function FormaMensaje($mensaje,$titulo,$accion,$boton)
  {
        $this->salida .= ThemeAbrirTabla($titulo);
        $this->salida .= "            <table width=\"60%\" align=\"center\" >";
        $this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "               <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
        if($boton){
           $this->salida .= "               <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"$boton\"></td></tr>";
        }
       else{
           $this->salida .= "               <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\"></td></tr>";
       }
        $this->salida .= "           </form>";
        $this->salida .= "           </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }

  /**
  *
  */
  function LiqManual()
  {
      $this->salida .= "<SCRIPT>";
      $this->salida .= "function manual(forma,valor){ ";
      $this->salida .= "  if(forma.Manual.checked){";
      $this->salida .= "    forma.DescuentoEmp.disabled=true; ";
      $this->salida .= "    forma.DescuentoPac.disabled=true; ";
      $this->salida .= "    forma.ValorPac.disabled=false;";
      $this->salida .= "    forma.ValorEmp.disabled=false;";
      $this->salida .= "  }";
      $this->salida .= "  else";
      $this->salida .= "  {";
      $this->salida .= "    forma.DescuentoEmp.disabled=false;";
      $this->salida .= "    forma.DescuentoPac.disabled=false;";
      $this->salida .= "    forma.ValorPac.disabled=true;";
      $this->salida .= "    forma.ValorEmp.disabled=true;";
      $this->salida .= "  }";
      $this->salida .= "}";
      $this->salida .= "</SCRIPT>";
  }



 /**
  * Se utilizada listar en el combo los diferentes tipo de departamentos de la clinica.
  * @access private
  * @return void
  */
  function BuscarDepartamento($departamento,$d=false,$Dpto)
  {
        if(!$d){
          $this->salida .=" <option value=\"-1\" selected>--TODOS--</option>";
        }
        foreach($departamento as $value=>$titulo)
        {
          if($value==$Dpto){
              $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
          }
          else {
             $this->salida .=" <option value=\"$value\" >$titulo</option>";
          }
        }
  }


  /**
  * Muestra el combo de los tipos id de los terceros.
  * @access private
  * @return void
  *
  */
  function BuscarIdTerceros($tipo,$Tipo)
  {
      for($i=0; $i<sizeof($tipo); $i++)
      {
          if($tipo[$i][tipo_id_tercero]==$Tipo){
              $this->salida .=" <option value=\"".$tipo[$i][tipo_id_tercero]."\" selected>".$tipo[$i][descripcion]."</option>";
          }
          else {
             $this->salida .=" <option value=\"".$tipo[$i][tipo_id_tercero]."\" >".$tipo[$i][descripcion]."</option>";
          }
      }
  }

//---------------------RELIQUIDAR------------------------

  /**
  *
  */
  function FormaMenuReliquidar()
  {
        $Cuenta=$_REQUEST['Cuenta'];
        $Transaccion=$_REQUEST['Transaccion'];
        $TipoId=$_REQUEST['TipoId'];
        $PacienteId=$_REQUEST['PacienteId'];
        $Nivel=$_REQUEST['Nivel'];
        $PlanId=$_REQUEST['PlanId'];
        $Pieza=$_REQUEST['Pieza'];
        $Cama=$_REQUEST['Cama'];
        $Fecha=$_REQUEST['Fecha'];
        $Ingreso=$_REQUEST['Ingreso'];
        $Ingreso=$_REQUEST['Ingreso'];
        $this->salida .= ThemeAbrirTabla('MENU RELIQUIDACION CUENTAS');
        $this->salida .= "            <br>";
        $this->salida .= "            <table width=\"40%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
        $this->salida .= "               <tr>";
        $this->salida .= "                  <td align=\"center\" class=\"modulo_table_list_title\">MENU RELIQUIDACIONES</td>";
        $this->salida .= "               </tr>";
        $this->salida .= "               <tr>";
        $mensaje='Esta seguro que desea Reliquidar los cargos de Insumos y Medicamentos de la Cuenta No. '.$Cuenta;
        $arreglo=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado);
        $accion=ModuloGetURL('app','Facturacion','user','ConfirmarAccion',array('c'=>'app','m'=>'Facturacion','me2'=>'Cuenta','me'=>'ReliquidarMedicamentos','mensaje'=>$mensaje,'titulo'=>'RELIQUIDAR CARGOS DE MEDICAMENTOS DE LA CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos,'arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
        $this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accion\">Reliquidar Insumos y Medicamentos</a></td>";
        $this->salida .= "               </tr>";
        $this->salida .= "               <tr>";
        $msg='Esta seguro que desea Reliquidar la Cuenta No. '.$Cuenta;
        $arreglo=array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
        $accion=ModuloGetURL('app','Facturacion','user','ConfirmarAccion',array('c'=>'app','m'=>'Facturacion','me2'=>'Cuenta','me'=>'ReliquidarCargos','mensaje'=>$msg,'titulo'=>'RELIQUIDAR CUENTA No. '.$Cuenta,'arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
        $this->salida .= "                  <td align=\"center\" class=\"modulo_list_oscuro\"><a href=\"$accion\">Reliquidar Cargos</a></td>";
        $this->salida .= "               </tr>";

        $this->salida .= "               <tr>";
        $msg='Esta seguro que desea Reliquidar la Cuenta No. '.$Cuenta;
        $arreglo=array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
        $accion=ModuloGetURL('app','Facturacion','user','ConfirmarAccion',array('c'=>'app','m'=>'Facturacion','me2'=>'Cuenta','me'=>'Reliquidar','mensaje'=>$msg,'titulo'=>'RELIQUIDAR CUENTA No. '.$Cuenta,'arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
        $this->salida .= "                  <td align=\"center\" class=\"modulo_list_oscuro\"><a href=\"$accion\">Reliquidar Cuenta</a></td>";
        $this->salida .= "               </tr>";

        $this->salida .= "           </table>";
        $accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER AL DETALLE\"></p><br>";
        $this->salida .= "</form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }


  /**
  *
  */
  function Cambio()
  {
      $this->salida .= "<SCRIPT>";
      $this->salida .= "function Cambio(valor,url){";
      $this->salida .= " window.location=url+'&Responsable='+valor;";
      $this->salida .= "}";
      $this->salida .=  "</SCRIPT>";
  }

  /**
  * Muestra la forma para buscar el paciente.
  * @access private
  * @return boolean
  * @param string tipo documento
  * @param int numero documento
  */
  function FormaCambiarPlan($Responsable,$Nivel,$Ingreso,$TipoId,$PacienteId,$Cuenta,$Fecha,$Pieza,$Cama)
  {
        if(!$Responsable)
        {
            $Responsable=$_REQUEST['Responsable'];
            $TipoId=$_REQUEST['TipoId'];
            $PacienteId=$_REQUEST['PacienteId'];
            $Nivel=$_REQUEST['Nivel'];
            $Pieza=$_REQUEST['Pieza'];
            $Cama=$_REQUEST['Cama'];
            $Fecha=$_REQUEST['Fecha'];
            $Ingreso=$_REQUEST['Ingreso'];
            $Cuenta=$_REQUEST['Cuenta'];
        }
        $action=ModuloGetURL('app','Facturacion','user','CambiarPlan',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Cama'=>$Cama,'Fecha'=>$Fecha,'Pieza'=>$Pieza));
        $this->Cambio();
        $this->salida .= ThemeAbrirTabla('BUSCAR PACIENTE');
        $this->salida .= "            <br><br>";
        $this->salida .= "            <table width=\"50%\" align=\"center\" border=\"0\">";
        $this->salida .= "             <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $accion=ModuloGetURL('app','Facturacion','user','FormaCambiarPlan',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Cama'=>$Cama,'Fecha'=>$Fecha,'Pieza'=>$Pieza));
        $this->salida .= "               <tr><td class=\"".$this->SetStyle("Responsable")."\">RESPONSABLE: </td><td><select name=\"Responsable\" class=\"select\" onChange=\"Cambio(this.value,'$accion')\">";
        $responsables=$this->responsables();
        $this->MostrarResponsable($responsables,$Responsable);
        $this->salida .= "              </select></td></tr>";
        $this->salida .= "    <tr height=\"20\"><td class=\"".$this->SetStyle("Nivel")."\">RANGO: </td>";
        $niveles=$this->CallMetodoExterno('app','Triage','user','Niveles',array('Responsable'=>$Responsable));
        if(sizeof($niveles)>1)
        {
              $this->salida .= "            <td><select name=\"Nivel\"  class=\"select\">";
              for( $i=0;$i<sizeof($niveles);$i++){
                  if($niveles[$i]==$Nivel)
                  {  $this->salida .=" <option value=\"$niveles[$i]\" selected>$niveles[$i]</option>";  }
                  else
                  {  $this->salida .=" <option value=\"$niveles[$i]\">$niveles[$i]</option>";  }

              }
              $this->salida .= "       </select></td></tr>";
        }
        else
        {
            $this->salida .= "        <td><input type=\"text\" class=\"input-text\" name=\"Nivel\" value=\"$niveles\" readonly size=\"5\"></td>";
        }
        $this->salida .= "               <tr><td align=\"right\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"><br></td></form>";
        $accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$_SESSION['NIVEL1'],'PlanId'=>$_SESSION['PLAN1'],'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
        $this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "               <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"><br></td></form></tr>";
        $this->salida .= "           </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }

  /**
  * Muestra el nombre del tercero con sus respectivos planes
  * @access private
  * @return string
  * @param array arreglor con los tipos de responsable
  * @param int el responsable que viene por defecto
  */
 function MostrarResponsable($responsables,$Responsable)
 {
      $i=0;
      $this->salida .=" <option value=\"-1\">-------SELECCIONE-------</option>";
      while( $i < sizeof($responsables)){
          $concate=strtok($responsables[$i],'|/');
          for($l=0;$l<4;$l++)
          {
            $var[$l]=$concate;
            $concate = strtok('|/');
          }
          if($var[0]==$Responsable){
              $this->salida .=" <option value=\"$var[0]\" selected>$var[1]</option>";
          }else{
              $this->salida .=" <option value=\"$var[0]\">$var[1]</option>";
          }
      $i++;
      }
 }

 /**
 *
 */
  function FormaDescuentos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha)
  {
        IncludeLib("tarifario");
        $this->salida .= ThemeAbrirTabla('DESCUENTOS CUENTA No. '.$Cuenta);
        $accion=ModuloGetURL('app','Facturacion','user','GuardarDescuentos',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
        $this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "           <br><table width=\"70%\" border=\"0\" align=\"center\"  cellspacing=\"3\" cellpadding=\"3\" class=\"modulo_table_list\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "               <tr align=\"center\" class=\"modulo_table_list_title\">";
        $this->salida .= "                  <td>TIPOS DESCUENTOS</td>";
        $this->salida .= "                  <td>DESC. EMPRESA</td>";
        $this->salida .= "                  <td>DESC. PACIENTE</td>";
        $this->salida .= "               </tr>";
         $Tipos=$this->BuscarSolicitudesDescuentos();
        for($i=0; $i<sizeof($Tipos); $i++)
        {
            $Des=$this->BuscarDescuentosCuenta($Cuenta,$Tipos[$i][grupo_tipo_cargo]);
            if($i % 2) {  $estilo="modulo_list_claro";  }
            else {  $estilo="modulo_list_oscuro";   }
            $this->salida .= "               <tr class=\"$estilo\">";
            $this->salida .= "                  <td aling=\"left\">&nbsp;".$Tipos[$i][descripcion]."</td>";
            $this->salida .= "                  <td align=\"center\"><input type=\"text\" size=\"5\" value=\"".FormatoValor($Des[0][descuento_empresa])."\" name=\"DesEmp,".$i.",".$Tipos[$i][grupo_tipo_cargo]."\"> %</td>";
            $this->salida .= "                  <td align=\"center\"><input type=\"text\" size=\"5\" value=\"".FormatoValor($Des[0][descuento_paciente])."\" name=\"DesPac,".$i.",".$Tipos[$i][grupo_tipo_cargo]."\"> %</td>";
            $this->salida .= "               </tr>";
        }
        $this->salida .= "           </table><BR>";
        $this->salida .= "           <br><table width=\"70%\" border=\"0\" align=\"center\"  cellspacing=\"3\" cellpadding=\"3\">";
        $this->salida .= "               <tr align=\"center\">";
        $this->salida .= "                <td width=\"50%\" ><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"GUARDAR\"></td>";
        $this->salida .= "                  </form>";
        $accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
        $this->salida .= "                <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "                    <td><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER AL DETALLE\"></td>";
        $this->salida .= "                </form>";
        $this->salida .= "               </tr>";
        $this->salida .= "           </table><BR>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }


//-------------------------------------------------------------------------------
  /**
  *
  */
  function PermisosFacturacion()
  {
      unset($_SESSION['FACTURACION']);
      $SystemId=UserGetUID();
      if(!empty($_SESSION['SEGURIDAD']['FILTRO']))
      {
            $this->salida.= gui_theme_menu_acceso('FACTURACION',$_SESSION['SEGURIDAD']['FISCAL']['arreglo'],$_SESSION['SEGURIDAD']['FISCAL']['facturacion'],$_SESSION['SEGURIDAD']['FISCAL']['url']);
            return true;
      }
      list($dbconn) = GetDBconn();
      GLOBAL $ADODB_FETCH_MODE;
      $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
      $query = "SELECT a.tipo_factura, b.sw_todos_cu, b.empresa_id, b.centro_utilidad,
                b.descripcion as descripcion3, c.tipo_numeracion, c.prefijo, d.razon_social as descripcion1,
                e.descripcion as descripcion2
                from userpermisos_tipos_facturas as a, fac_tipos_facturas as b, numeraciones as c, empresas as d, centros_utilidad as e
                where a.usuario_id=$SystemId and a.tipo_factura=b.tipo_factura
                and b.empresa_id=d.empresa_id and d.empresa_id=e.empresa_id and b.centro_utilidad=e.centro_utilidad
                and b.tipo_numeracion=c.tipo_numeracion order by d.empresa_id, b.centro_utilidad, a.tipo_factura";
      $resulta=$dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al ejecutar el query de permisos";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
      }
      while ($data = $resulta->FetchRow()) {
        $emp[$data['empresa_id']] += 1;
        $cu[$data['empresa_id']][$data['centro_utilidad']] += 1;
        $vect[$data['empresa_id']][$data['centro_utilidad']][$data['tipo_factura']]+= 1;
        $facturacion[$data['empresa_id']][]=$data;
        $seguridad[$data['empresa_id']][$data['centro_utilidad']][$data['tipo_factura']]=1;
      }

      $url[0]='app';
      $url[1]='Facturacion';
      $url[2]='user';
      $url[3]='ListadoCuentas';
      $url[4]='Facturacion';

      $arreglo[0][0]=$emp;
      $arreglo[0][1]='EMPRESA';
      $arreglo[0][2]='empresa_id';
      $arreglo[1][0]=$cu;
      $arreglo[1][1]='CENTRO UTILIDAD';
      $arreglo[1][2]='centro_utilidad';
      $arreglo[4][0]=$vect;
      $arreglo[4][1]='CUENTAS';
      $arreglo[4][2]='cuenta_filtro_id';

      $_SESSION['SEGURIDAD']['FISCAL']['arreglo']=$arreglo;
      $_SESSION['SEGURIDAD']['FISCAL']['facturacion']=$facturacion;
      $_SESSION['SEGURIDAD']['FISCAL']['url']=$url;
      $_SESSION['SEGURIDAD']['FISCAL']['puntos']=$seguridad;
      $this->salida.= gui_theme_menu_acceso('FACTURACION',$arreglo,$facturacion,$url);
      return true;
  }


  /**
  *
  */
  function FormaTiposCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha)
  {
        $this->salida .= ThemeAbrirTabla('AGREGAR CARGOS');
        $this->salida .= "            <br>";
        $this->salida .= "            <table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
        $this->salida .= "               <tr>";
        $this->salida .= "                  <td align=\"center\" class=\"modulo_table_list_title\">MENU ADMISIONES URGENCIAS</td>";
        $this->salida .= "               </tr>";
        $this->salida .= "               <tr>";
        $accionC=ModuloGetURL('app','Facturacion','user','Cargos',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Ingreso'=>$Ingreso,'Fecha'=>$Fecha));
        $this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionC\">Agregar Cargos</a></td>";
        $this->salida .= "               </tr>";
        $this->salida .= "               <tr>";
        $accionI=ModuloGetURL('app','Facturacion','user','LlamarFormaBodegas',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Ingreso'=>$Ingreso,'Fecha'=>$Fecha));
        $this->salida .= "                  <td align=\"center\" class=\"modulo_list_oscuro\"><a href=\"$accionI\">Agregar Insumos y Medicamentos</a></td>";
        $this->salida .= "               </tr>";
       /* $this->salida .= "               <tr>";
        $accionI=ModuloGetURL('app','Facturacion','user','',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Ingreso'=>$Ingreso,'Fecha'=>$Fecha));
        $this->salida .= "                  <td align=\"center\" class=\"modulo_list_oscuro\"><a href=\"$accionI\">Agregar Cirug?a</a></td>";
        $this->salida .= "               </tr>";*/
        $this->salida .= "           </table><BR>";
        $accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER AL DETALLE\"></p><br>";
        $this->salida .= "</form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }


 /**
  * Muestra los cargos que inserto con sus totales y la opcion de insertar un nuevo cargo.
  * @access private
  * @return boolean
  * @param int numero de la cuenta
  * @param string tipo documento
  * @param int numero documento
  * @param string nivel
  * @param string plan_id
  * @param int ingreso
  * @param date fecha de la cuenta
  */
  function  FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$D)
  {
        IncludeLib("tarifario");
        $Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
        $Apellidos=$this->BuscarApellidosPaciente($TipoId,$PacienteId);
        $this->salida .= ThemeAbrirTabla('AGREGAR CARGO A LA CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos);
        $this->EncabezadoEmpresa($Caja);
        $argu=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
        $this->Encabezado($PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$argu,$Cuenta);
        $datos=$this->DatosTmpInsumos($Cuenta);
        $this->salida .= "            <table width=\"50%\" align=\"center\" border=\"0\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "           </table>";
        if(!empty($datos) AND empty($D))
        {
            $this->salida .= " <table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"90%\" align=\"center\" >";
            $this->salida .= "    <tr align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "        <td>DEPARTAMENTO</td>";
            $this->salida .= "        <td>COD. PRODUCTO</td>";
            $this->salida .= "        <td>DESCRIPCION</td>";
            $this->salida .= "        <td>BODEGA</td>";
            $this->salida .= "        <td>PRECIO</td>";
            $this->salida .= "        <td>CANT.</td>";
            $this->salida .= "        <td></td>";
            $this->salida .= "        <td></td>";
            $this->salida .= "    </tr>";
            for($i=0; $i<sizeof($datos);$i++)
            {
                if( $i % 2) $estilo='modulo_list_claro';
                else $estilo='modulo_list_oscuro';

                $this->salida .= "    <tr class=\"$estilo\" align=\"center\">";
                $this->salida .= "        <td>".$datos[$i][desdpto]."</td>";
                $this->salida .= "        <td>".$datos[$i][codigo_producto]."</td>";
                $this->salida .= "        <td>".$datos[$i][descripcion]."</td>";
                $this->salida .= "        <td>".$datos[$i][desbodega]."</td>";
                $this->salida .= "        <td>".$datos[$i][precio]."</td>";
                $this->salida .= "        <td>".FormatoValor($datos[$i][cantidad])."</td>";
                $accionModificar=ModuloGetURL('app','Facturacion','user','LlamaFormaModificarCargoTmpIyM',array('ID'=>$Datos[$i][tmp_cuenta_insumos_id],'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Datos'=>$datos[$i]));
                $this->salida .= "        <td><a href=\"$accionModificar\" alt=\"Modificar los cargos del paciente\">MODI</a></td>";
                $accionEliminar=ModuloGetURL('app','Facturacion','user','EliminarCargoTmpIyM',array('ID'=>$datos[$i][tmp_cuenta_insumos_id],'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
                $this->salida .= "        <td><a href=\"$accionEliminar\">ELIM</a></td>";
                $this->salida .= "    </tr>";
            }
            $this->salida .= " </table>";
        }
        if(!empty($D))
        {
            $bod[0]=$d[bodega];
        }
                $bod=explode(',',$_SESSION['CUENTA']['BODEGA']);
                global $_ROOT;
                $sw=ModuloGetVar('app','Facturacion','sw_gravar_cuota_paciente');
                $this->salida .= "\n<script>\n";
                $this->salida .= "var rem=\"\";\n";
                $this->salida .= "  function abrirVentana(){\n";
                $this->salida .= "    var dpto='';\n";
                $this->salida .= "    dpto=document.newcargo.Departamento.value;\n";
                $this->salida .= "    var bodega='';\n";
                $this->salida .= "    bodega=document.newcargo.Bodegas.value;\n";
                $this->salida .= "    if(bodega==-1){\n";
                $this->salida .= "      alert('Debe elegir la Bodega.');\n";
                $this->salida .= "    }\n";
                $this->salida .= "    else{\n";
        $this->salida .= "      var nombre='';\n";
        $this->salida .= "      var url2='';\n";
        $this->salida .= "      var str='';\n";
        $this->salida .= "      var ALTO=screen.height;\n";
        $this->salida .= "      var ANCHO=screen.width;\n";
        $this->salida .= "      nombre=\"buscador_General\";\n";
        $this->salida .= "      str =\"ANCHO,ALTO,resizable=no,status=no,scrollbars=yes\";\n";
        $this->salida .= "      url2 ='".$_ROOT."classes/classbuscador/buscador.php?tipo=InsertarInsumos&forma=newcargo&plan='+'$PlanId'+'&Empresa='+'$bod[1]'+'&CU='+'$bod[2]'+'&Bodega='+bodega;\n";
        $this->salida .= "      rem = window.open(url2, nombre, str);\n";
        $this->salida .= "    }\n";
         $this->salida .= "  }\n";
        $this->salida .= "</script>\n";
        if($D){
            $accion=ModuloGetURL('app','Facturacion','user','ModificarCargoTmpIyM',array('id'=>$D[tmp_cuenta_insumos_id],'Datos'=>$D));
            $Boton='MODIFICAR CARGO';
            $Modi=true;
        }
        else {
            $accion=ModuloGetURL('app','Facturacion','user','InsertarInsumos');
            $Boton='AGREGAR CARGO';
        }
        $this->salida .= " <form name=\"newcargo\" action=\"$accion\" method=\"post\">";
        $FechaCargo=date("d/m/Y");
        $Dpto=$this->Departamento;
        $Descripcion='';
        $Cant=1;
        $this->salida .= " <table border=\"0\" width=\"90%\" align=\"center\"  class=\"normal_10\">";
        $this->salida .= "   <tr><td><fieldset><legend class=\"field\">AGREGAR CARGO</legend>";
        $this->salida .= "     <table height=\"74\" border=\"0\" width=\"98%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "              <input type=\"hidden\" name=\"PacienteId\" value=\"$PacienteId\">";
        $this->salida .= "              <input type=\"hidden\" name=\"TipoId\" value=\"$TipoId\">";
        $this->salida .= "              <input type=\"hidden\" name=\"PlanId\" value=\"$PlanId\">";
        $this->salida .= "              <input type=\"hidden\" name=\"Nivel\" value=\"$Nivel\">";
        $this->salida .= "              <input type=\"hidden\" name=\"Ingreso\" value=\"$Ingreso\">";
        $this->salida .= "              <input type=\"hidden\" name=\"Fecha\" value=\"$Fecha\">";
        $this->salida .= "              <input type=\"hidden\" name=\"Cuenta\" value=\"$Cuenta\">";
        $this->salida .= "              <input type=\"hidden\" name=\"Cobertura\">";
        $this->salida .= "              <input type=\"hidden\" name=\"EmpresaId\" value=\"$bod[1]\">";
        $this->salida .= "              <input type=\"hidden\" name=\"CU\" value=\"$bod[2]\">";
        $this->salida .= "              <input type=\"hidden\" name=\"Bodegas\" value=\"$bod[0]\">";
        $this->salida .= "              <input type=\"hidden\" name=\"CantMax\">";
        $this->salida .= "       <tr>";
        $this->salida .= "         <td class=\"label\" width=\"13%\" >DEPARTAMENTO: </td>";


        $departamento = $this->DptoBodega($bod[0]);
        if($departamento['sw_solicitar_departamento_al_cargar']==='0')
        {
            $this->salida .= "         <td class=\"label\">".$departamento[descripcion]."</td>";
            $this->salida .= "              <input type=\"hidden\" name=\"Departamento\" value=\"".$departamento[departamento]."\"";
        }
        else
        {
            $this->salida .= "         <td><select name=\"Departamento\" class=\"select\">";
            $departamento=$this->Departamentos();
            $this->BuscarDepartamento($departamento,$d=true,$Dpto);
            $this->salida .= "         </select></td>";
        }


        $this->salida .= "       <td>&nbsp;</td>";
        $this->salida .= "       <td class=\"".$this->SetStyle("Codigo")."\">COD. PROD: </td>";
        $this->salida .= "   <td><input type=\"text\" class=\"input-text\" readonly name=\"Codigo\" size=\"10\" value=\"".$D[codigo_producto]."\" ></td>";
        $this->salida .= "       <td>&nbsp;</td>";
        $bode=$this->NombreBodega($bod[0]);
        $this->salida .= "                 <td colspan=\"2\" class=\"label\">BODEGA:  ".$bode[descripcion]."</td>";
        $this->salida .= "   <td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"BUSCAR\" onclick=abrirVentana()></td>";
        $this->salida .= "              </tr>";
        $this->salida .= "              <tr>";
        $this->salida .= "                <td class=\"label\">DESCRIPCION: </td>";
        $this->salida .= "                <td><textarea cols=\"35\" rows=\"3\" class=\"textarea\"name=\"Descripcion\" readonly>".$D[descripcion]."</textarea></td>";
        $this->salida .= "                <td>&nbsp;</td>";
        $this->salida .= "                <td class=\"label\">PRECIO: </td>";
        $this->salida .= "                <td><input type=\"text\" class=\"input-text\" name=\"Precio\" size=\"10\" value=\"".$D[precio]."\" readonly></td>";
        $this->salida .= "                <td>&nbsp;</td>";
        $this->salida .= "                <td class=\"".$this->SetStyle("Cantidad")."\">CANTIDAD: </td>";
        $this->salida .= "                <td><input type=\"text\" class=\"input-text\" name=\"Cantidad\" size=\"5\" value=\"$Cant\"></td>";
        $this->salida .= "              </tr>";
        $this->salida .= "              <tr>";
        $this->salida .= "                <td class=\"label\">GRAVAMEN %: </td>";
        $this->salida .= "                <td><input type=\"text\" class=\"input-text\" name=\"Gravamen\" size=\"10\" value=\"".FormatoValor($Gravamen)."\" readonly></td>";
        $this->salida .= "                <td>&nbsp;</td>";
        $this->salida .= "                <td class=\"".$this->SetStyle("FechaCargo")."\">FECHA CARGO: </td>";
        $this->salida .= "                <td colspan=\"4\"><input type=\"text\" name=\"FechaCargo\" value=\"$FechaCargo\" size=\"10\" class=\"input-text\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\">&nbsp;&nbsp;";
        $this->salida .=   ReturnOpenCalendario('newcargo','FechaCargo','/')."</td>";
        $this->salida .= "              </tr>";
        $this->salida .= "             </table>";
        $this->salida .= "          </fieldset></td></tr></table>";
        $this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"45%\" align=\"center\"  >";
        $this->salida .= "    <tr align=\"center\">";
        $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"$Boton\"></td>";
        $this->salida .= "    </form>";
        $accionEliminarTodos=ModuloGetURL('app','Facturacion','user','EliminarTodosCargosIyM',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
        $this->salida .= "    <form name=\"formaborrar\" action=\"$accionEliminarTodos\" method=\"post\">";
        $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"ELIMINAR TODOS LOS CARGOS\"></td>";
        $this->salida .= "    </form>";
        $accionGuardarTodos=ModuloGetURL('app','Facturacion','user','GuardarTodosCargosIyM',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
        $this->salida .= "    <form name=\"formaguardar\" action=\"$accionGuardarTodos\" method=\"post\">";
        $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"GUARDAR TODOS LOS CARGOS\"></td>";
        $this->salida .= "    </form>";
        $accionCancelar=ModuloGetURL('app','Facturacion','user','EliminarTodosCargosIyM',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
        $this->salida .= "    <form name=\"formaguardar\" action=\"$accionCancelar\" method=\"post\">";
        $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\"></td>";
        $this->salida .= "    </form>";
        $this->salida .= "    </tr>";
        $this->salida .= "    </table><br>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }


  /**
  *
  */
  function FormaBodegas($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha)
  {
        $this->salida .= ThemeAbrirTabla('ELEGIR BODEGAS DE INSUMOS O MEDICAMENTOS');
        $accion=ModuloGetURL('app','Facturacion','user','BodegaInsumos',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
        $this->salida .= "    <form name=\"formaborrar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "            <table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "               <tr>";
        //$tipo=$this->Bodegas();
          $tipo=$this->BuscarBodegasPorUsuarioId();
        $this->salida .= "       <td class=\"label\">BODEGAS: </td>";
        $this->salida .= "                 <td colspan=\"2\"><select name=\"Bodegas\" class=\"select\">";
        $this->salida .= "                     <option value=\"-1\">----------BODEGAS----------</option>";
        for($i=0; $i<sizeof($tipo); $i++)
        {
            $this->salida .= "                     <option value=\"".$tipo[$i][bodega].",".$tipo[$i][empresa_id].",".$tipo[$i][centro_utilidad]."\">".$tipo[$i][descripcion]."</option>";
        }
        $this->salida .= "                 </select></td>";
        $this->salida .= "               </tr>";
        $this->salida .= "           </table>";
        $this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"45%\" align=\"center\"  >";
        $this->salida .= "    <tr align=\"center\">";
        $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"ACEPTAR\"></td>";
        $this->salida .= "    </form>";
        $accionCancelar=ModuloGetURL('app','Facturacion','user','LlamarFormaTiposCargos',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
        $this->salida .= "    <form name=\"formaborrar\" action=\"$accionCancelar\" method=\"post\">";
        $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"CANCELAR\"></td>";
        $this->salida .= "    </form>";
        $this->salida .= "    </tr>";
        $this->salida .= " </table>";
        $this->salida .= "</form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }

//--------------------CAMBIO RESPONSBALE---------------------------------

  /**
  *
  */
  function FormaCambioResponsable($PlanId,$Cuenta,$TipoId,$PacienteId,$Nivel,$Ingreso,$Fecha)
  {
        $action=ModuloGetURL('app','Facturacion','user','NuevoResponsable',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
        $this->salida .= ThemeAbrirTabla('CUENTAS -  CAMBIO RESPONSABLE');
        $this->salida .= "            <table width=\"50%\" align=\"center\" border=\"0\">";
        $this->salida .= "             <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "               <tr><td class=\"".$this->SetStyle("Responsable")."\">PLAN: </td><td><select name=\"Responsable\" class=\"select\">";
        $responsables=$this->responsables();
        $this->MostrarResponsable($responsables,$PlanId);
        $this->salida .= "              </select></td></tr>";
        $this->salida .= "               <tr><td align=\"right\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"><br></td>";
        $this->salida .= "           </form>";
        $actionM=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
        $this->salida .= "             <form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "               <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"><br></td></tr>";
        $this->salida .= "           </form>";
        $this->salida .= "           </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }


  /**
  *
  */
  function FormaDatosAfiliado($PlanId,$Cuenta,$TipoId,$PacienteId,$Nivel,$Ingreso,$Fecha)
  {
        $action=ModuloGetURL('app','Facturacion','user','GuardarNuevoPlan',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
        $this->salida .= ThemeAbrirTabla('CUENTAS - CAMBIO RESPONSABLE');
                if(!empty($_REQUEST['descripcion_plan']))
        {  $this->salida .= "<p class=\"label_mark\" align=\"center\">PLAN DE LA DIVISION - ".$_REQUEST['descripcion_plan']."</p>";  }
        $this->salida .= "      <table border=\"0\" width=\"50%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "  <form name=\"forma\" action=\"$action\" method=\"post\">";
        $tipo_afiliado=$this->Tipo_Afiliado();
        $this->salida .= "          <tr>";
        if(sizeof($tipo_afiliado)>1)
        {
            $this->salida .= "               <td class=\"".$this->SetStyle("TipoAfiliado")."\" width=\"30%\">TIPO AFILIADO: </td><td><select name=\"TipoAfiliado\" class=\"select\">";
            $this->BuscarIdTipoAfiliado($tipo_afiliado,$_REQUEST['TipoAfiliado']);
            $this->salida .= "              </select></td>";
        }
        else
        {
            $this->salida .= "            <td class=\"".$this->SetStyle("TipoAfiliado")."\" width=\"30%\">TIPO AFILIADO: </td>";
            $this->salida .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"TipoAfiliado\" value=\"".$tipo_afiliado[0][tipo_afiliado_id]."\">".$tipo_afiliado[0][tipo_afiliado_nombre]."</td>";
            $this->salida .= "            <td></td>";
        }
        $this->salida .= "          </tr>";
        $this->salida .= "          <tr>";
        $niveles=$this->Niveles();
        if(sizeof($niveles)>1)
        {
          $this->salida .= "               <tr><td class=\"".$this->SetStyle("Nivel")."\">RANGO: </td><td><select name=\"Nivel\" class=\"select\">";
          $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
          for($i=0; $i<sizeof($niveles); $i++)
          {
              if($niveles[$i][rango]==$_REQUEST['Nivel' ]){
                $this->salida .=" <option value=\"".$niveles[$i][rango]."\" selected>".$niveles[$i][rango]."</option>";
              }
              else{
                  $this->salida .=" <option value=\"".$niveles[$i][rango]."\">".$niveles[$i][rango]."</option>";
              }
          }
        }
        else
        {
            $this->salida .= "             <td class=\"".$this->SetStyle("Nivel")."\">RANGO: </td>";
            $this->salida .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"Nivel\" value=\"".$niveles[0][rango]."\">".$niveles[0][rango]."</td>";
            $this->salida .= "            <td></td>";
        }
        $this->salida .= "          </tr>";
        $this->salida .= "          <tr>";
        $this->salida .= "            <td class=\"".$this->SetStyle("SEM")."\">SEMANAS COTIZADAS: </td>";
        $this->salida .= "            <td><input type=\"text\"  class=\"input-text\" name=\"Semanas\" size=\"10\" value=\"".$_REQUEST['Semanas']."\"></td>";
        $this->salida .= "            <td></td>";
        $this->salida .= "          </tr>";
        $this->salida .= "          </table>";
        $this->salida .= "      <table border=\"0\" width=\"40%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "          <tr><td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"><br></td>";
        $this->salida .= "  </form>";
                if(!empty($_REQUEST['descripcion_plan']))
                {   $actionM=ModuloGetURL('app','Facturacion','user','CancelarDivision',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));   }
                else
                {   $actionM=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));  }
        $actionM=ModuloGetURL('app','Facturacion','user','CancelarDivision',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
        $this->salida .= "  <form name=\"formacancelar\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "               <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"CANCELAR\"><br></td>";
        $this->salida .= "  </form>";
        $this->salida .= "               </tr>";
        $this->salida .= "          </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }

  /**
  * Crear el combo de tipos de afiliados
  * @access private
  * @return string
  * @param array arreglo con los tipos de afiliados
  * @param int tipo de afiliado
  */
  function BuscarIdTipoAfiliado($tipo_afiliado,$TipoAfiliado='')
  {
        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
        for($i=0; $i<sizeof($tipo_afiliado); $i++)
        {
          if($tipo_afiliado[$i][tipo_afiliado_id]==$TipoAfiliado){
           $this->salida .=" <option value=\"".$tipo_afiliado[$i][tipo_afiliado_id]."\" selected>".$tipo_afiliado[$i][tipo_afiliado_nombre]."</option>";
          }
          else{
           $this->salida .=" <option value=\"".$tipo_afiliado[$i][tipo_afiliado_id]."\">".$tipo_afiliado[$i][tipo_afiliado_nombre]."</option>";
          }
        }
  }


  /**
  *
  */
  function Todos()
  {
      $this->salida .= "<SCRIPT>";
      $this->salida .= "function Todos(frm,x){";
      $this->salida .= "  if(x==true){";
      $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
      $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
      $this->salida .= "        frm.elements[i].checked=true";
      $this->salida .= "      }";
      $this->salida .= "    }";
      $this->salida .= "  }else{";
      $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
      $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
      $this->salida .= "        frm.elements[i].checked=false";
      $this->salida .= "      }";
      $this->salida .= "    }";
      $this->salida .= "  }";
      $this->salida .= "}";
      $this->salida .= "</SCRIPT>";
  }


  function FormaEquivalencias($PlanId,$Cuenta,$TipoId,$PacienteId,$Nivel,$Ingreso,$Fecha)
  {
        $Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
        $Apellidos=$this->BuscarApellidosPaciente($TipoId,$PacienteId);
        $this->salida .= ThemeAbrirTabla('CUENTAS -  EQUIVALENCIAS DE CARGOS CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos.'');
        $this->EncabezadoEmpresa($Caja);
        $_REQUEST=$_SESSION['CUENTA']['REQUEST'];
        $argu=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
        $this->Encabezado($PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$argu,$Cuenta);
                $act = $this->DetalleCambioACtual($PlanId,$Cuenta);
        $this->Todos();
        $this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "     </table>";
        $accion=ModuloGetURL('app','Facturacion','user','InsertarNuevoPlan',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "    <br> <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "          <tr class=\"modulo_table_list_title\">";
        $datPlan=$this->NombrePlan($PlanId);
        $this->salida .= "              <td width=\"50%\" colspan=\"5\">PLAN ACTUAL: ".$datPlan['plan_descripcion']."</td>";
        $datPlan=$this->NombrePlan($_SESSION['CUENTA']['CAMBIO']['nuevo_plan']);
        $this->salida .= "              <td width=\"50%\" colspan=\"4\">PLAN NUEVO: ".$datPlan['plan_descripcion']."</td>";
        $this->salida .= "          </tr>";
        $this->salida .= "          <tr class=\"modulo_table_list_title\">";
        $this->salida .= "              <td width=\"9%\">TARIFARIO</td>";
        $this->salida .= "              <td width=\"9%\">CARGO</td>";
        $this->salida .= "              <td width=\"9%\">CODIGO</td>";
        $this->salida .= "              <td width=\"4%\">CANTIDAD</td>";
        $this->salida .= "              <td width=\"29%\">DESCRIPCION</td>";
        $this->salida .= "              <td width=\"9%\">TARIFARIO</td>";
        $this->salida .= "              <td width=\"9%\">CARGO</td>";
        $this->salida .= "              <td width=\"30%\">DESCRIPCION</td>";
        $this->salida .= "              <td width=\"4%\"><input type=\"checkbox\" name=\"Todo\" onClick=\"Todos(this.form,this.checked)\"></td>";
        $this->salida .= "          </tr>";
        $this->salida .= "          <tr>";
        //actual
        $d = 0;
        $f = 0;
        $this->salida .= "              <td colspan=\"9\" width=\"100%\">";
        $this->salida .= "                <table border=\"1\" width=\"100%\" align=\"center\" class=\"normal_10\">";
        for($i=0; $i<sizeof($act); $i++)
        {
            if($i % 2) {  $estilo="modulo_list_claro";  }
            else {  $estilo="modulo_list_oscuro";   }
            $this->salida .= "                    <tr class=\"$estilo\">";
            $this->salida .= "                        <td width=\"8%\" align=\"center\">".$act[$i][tarifario_id]."</td>";
            $this->salida .= "                        <td width=\"9%\" align=\"center\">".$act[$i][cargo]."</td>";
            $this->salida .= "                        <td width=\"8%\" align=\"center\">".$act[$i][codigo_producto]."</td>";
            $this->salida .= "                        <td width=\"6%\" align=\"center\">".FormatoValor($act[$i][cantidad])."</td>";
            $this->salida .= "                        <td width=\"28%\">".$act[$i][descripcion]."</td>";
            //equivalencias
            $this->salida .= "                        <td>";
            $new=$this->Equivalencias($PlanId,$Cuenta,$act[$i][cargo],$act[$i][tarifario_id]);
            $this->salida .= "                          <table border=\"1\" width=\"100%\" align=\"center\" class=\"normal_10\">";
                        for($j=0; $j<sizeof($new); $j++)
                        {
                                if($j % 2) {  $estilo="modulo_list_claro";  }
                                else {  $estilo="modulo_list_oscuro";   }
                                $cont=0;
                                $cont=$this->ValidarContratoEqui($new[$j][tarifarionew],$new[$j][cargonew],$_SESSION['CUENTA']['CAMBIO']['nuevo_plan']);
                                $this->salida .= "    <input type=\"hidden\" name=\"Cambio\" value=\"".$act[$i][cambio_responsable_id]."\">";
                                if(!empty($cont))
                                {
                                        $this->salida .= "                                <tr class=\"$estilo\">";
                                        if((!empty($new[$j][gruponew]) AND !empty($new[$j][subnew]))
                                        AND (!empty($new[$j][cargonew]) AND !empty($new[$j][tarifarionew])) AND $cont>0)
                                        {
                                                $this->salida .= "                                  <td width=\"11%\" align=\"center\">".$new[$j][tarifarionew]."</td>";
                                                $this->salida .= "                                  <td width=\"11%\" align=\"center\">".$new[$j][cargonew]."</td>";
                                                $this->salida .= "                                  <td width=\"31%\">".$new[$j][desnew]."</td>";
                                                $x="New".$i.$new[$j][tarifarionew].$new[$j][cargonew].$act[$i][tarifario_id].$act[$i][cargo];
                                                $z=$act[$i][cambio_responsable_detalle_actual_id].",".$new[$j][tarifarionew].",".$new[$j][cargonew].",".$act[$i][tarifario_id].",".$act[$i][cargo];

                                                if($_REQUEST[$x] == $z)
                                                {
                                                        $this->salida .= "                                  <td width=\"1%\" align=\"center\"><input type=\"checkbox\" value=\"".$act[$i][cambio_responsable_detalle_actual_id].",".$new[$j][tarifarionew].",".$new[$j][cargonew].",".$act[$i][tarifario_id].",".$act[$i][cargo]."\" name=\"New".$i.$new[$j][tarifarionew].$new[$j][cargonew].$act[$i][tarifario_id].$act[$i][cargo]."\" checked></td>";
                                                }
                                                else
                                                {
                                                        $this->salida .= "                                  <td width=\"1%\" align=\"center\"><input type=\"checkbox\" value=\"".$act[$i][cambio_responsable_detalle_actual_id].",".$new[$j][tarifarionew].",".$new[$j][cargonew].",".$act[$i][tarifario_id].",".$act[$i][cargo]."\" name=\"New".$i.$new[$j][tarifarionew].$new[$j][cargonew].$act[$i][tarifario_id].$act[$i][cargo]."\"></td>";
                                                }
                                                $f++;
                                        }
                                        elseif((!empty($new[$j][gruponew]) AND !empty($new[$j][subnew]))
                                        AND (empty($new[$j][cargonew]) AND empty($new[$j][tarifarionew])))
                                        {  $d++;
                                                $this->salida .= "                                  <td align=\"center\" class=\"label_error\" colspan=\"4\">No Existen Equivalencias</td>";
                                        }
                                        elseif((empty($new[$j][gruponew]) AND empty($new[$j][subnew]))
                                        AND (!empty($new[$j][cargonew]) AND !empty($new[$j][tarifarionew])))
                                        {$d++;
                                                $this->salida .= "                                  <td align=\"center\" class=\"label_error\" colspan=\"4\">El Cargo Equivalente No esta Contratado</td>";
                                        }
                                        elseif((empty($new[$j][gruponew]) AND empty($new[$j][subnew]))
                                        AND (empty($new[$j][cargonew]) AND empty($new[$j][tarifarionew])))
                                        {$d++;
                                                $this->salida .= "                                  <td align=\"center\" class=\"label_error\" colspan=\"4\">El Grupo y SubGrupo al que Pertenece el Cargo no estan Contratados</td>";
                                        }
                                        elseif((!empty($new[$j][gruponew]) AND !empty($new[$j][subnew]))
                                        AND (!empty($new[$j][cargonew]) AND !empty($new[$j][tarifarionew])) AND empty($cont))
                                        {$d++;
                                                $this->salida .= "                                  <td align=\"center\" class=\"label_error\" colspan=\"4\">El Grupo y SubGrupo al que Pertenece el Cargo no estan Contratados</td>";
                                        }
                                        $this->salida .= "                                </tr>";
                                }elseif(!empty($act[$i][codigo_producto])){
                  $this->salida .= "                                  <input type=\"hidden\" value=\"".$act[$i][cambio_responsable_detalle_actual_id].",".$act[$i][tarifario_id].",".$act[$i][cargo]."\" name=\"New$i\" checked></td>";
                }
            }//segund0 for
            $this->salida .= "                            </table>";
            $this->salida .= "                        </td>";
            //fin equivalencias
            $this->salida .= "                    </tr>";
        }
        $this->salida .= "                  </table>";
        $this->salida .= "              </td>";
        $this->salida .= "          </tr>";
        $this->salida .= "          </table>";
        $this->salida .= "    <input type=\"hidden\" name=\"Cant\" value=\"$f\">";
        //si hay cargos sin equivalencias no se puede cambiar de plan
        if($d > 0)
        {
            $this->salida .= "</form>";
            $this->salida .= "    <br> <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
            $this->salida .= "          <tr class=\"label_error\">";
            $this->salida .= "              <td align=\"center\">NO SE PUEDE CAMBIAR AL NUEVO PLAN: Existen $d Cargos que no Tienen Equivalencias o No han sido Contratados en el Nuevo Plan.</td>";
            $this->salida .= "          </tr>";
            $accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
            $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
            $this->salida .= "          <tr>";
            $this->salida .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER AL DETALLE\"></td>";
            $this->salida .= "</form>";
            $this->salida .= "          </tr>";
            $this->salida .= "          </table>";
        }
        else
        {
            $this->salida .= "    <br> <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
            $this->salida .= "          <tr>";
            $this->salida .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"></td>";
            $this->salida .= "</form>";
                        if(!empty($_SESSION['CUENTA']['DIVISION']))
            {  $accion=ModuloGetURL('app','Facturacion','user','CancelarCambio',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));  }
                        else
                        {  $accion=ModuloGetURL('app','Facturacion','user','CancelarCambio',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));  }
            $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
            $this->salida .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></td>";
            $this->salida .= "          </tr>";
            $this->salida .= "          </table>";
        }
        $this->salida .= ThemeCerrarTabla();
        return true;
  }


//-------------------------DIVISION CUENTAS-----------------------------
  /**
  * Muestra Los tipos de division de cuentas que existen.
  * @access private
  * @return boolean
  * @param int numero de la cuenta
  * @param string tipo documento
  * @param int numero documento
  * @param string nivel
  * @param string plan_id
  * @param int numero de cama
  * @param date fecha de la cuenta
  * @param int ingreso
  */
    function FormaTiposDivision($PlanId,$Cuenta,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$Tipo)
    {
        $Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
        $Apellidos=$this->BuscarApellidosPaciente($TipoId,$PacienteId);
        $this->salida .= ThemeAbrirTabla('CUENTAS -  DIVISION DE LA CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos.'');
        $this->EncabezadoEmpresa($Caja);
        $_REQUEST=$_SESSION['CUENTA']['REQUEST'];
        $argu=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
        $this->Encabezado($PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$argu,$Cuenta);
        $this->TotalesCuenta($Cuenta);
        $this->salida .= "     </table><br>";
        if(empty($Tipo))
        {
            $accion=ModuloGetURL('app','Facturacion','user','BuscarDivision',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
            $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
            $this->salida .= "     <table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "          <tr class=\"modulo_table_list_title\">";
            $this->salida .= "            <td align=\"center\" colspan=\"2\">DIVISION DE CUENTA</td>";
            $this->salida .= "          </tr>";
            $this->salida .= "          <tr class=\"modulo_list_claro\">";
            $this->salida .= "            <td class=\"label\" align=\"center\">SELECCION EL CRITERIO: </td>";
            $this->salida .= "            <td><select name=\"Tipo\" class=\"select\">";
            $this->salida .="                   <option value=\"-1\">LISTAR TODOS</option>";
            $this->salida .="                   <option value=\"1\">VALOR</option>";
            $this->salida .="                   <option value=\"2\">FECHA</option>";
            $this->salida .="                   <option value=\"3\">DEPARTAMENTO</option>";
            $this->salida .="                   <option value=\"4\">SERVICIO</option>";
            $this->salida .= "              </select></td>";
            $this->salida .= "          </tr>";
            $this->salida .= "     </table>";
        }
        else
        {
            $accion=ModuloGetURL('app','Facturacion','user','DivisionCuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
            $this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
            $this->salida .= "     <table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "          <tr class=\"modulo_table_list_title\">";
            $this->salida .= "            <td align=\"center\" colspan=\"3\">DIVISION DE CUENTA</td>";
            $this->salida .= "          </tr>";
            $this->salida .= $this->SetStyle("MensajeError");
            if($Tipo==1){
              $this->salida .= "                <tr>";
              $this->salida .= "                    <td class=\"".$this->SetStyle("Valor")."\" align=\"center\">VALOR: </td>";
              $this->salida .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"Valor\"></td>";
              $this->salida .= "                </tr>";
            }
            if($Tipo==2){
              $this->salida .= "                <tr>";
              $this->salida .= "                    <td class=\"".$this->SetStyle("FechaI")."\" align=\"center\">DESDE: </td>";
              $this->salida .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"FechaI\">".ReturnOpenCalendario('forma','FechaI','/')."</td>";
              $this->salida .= "                </tr>";
              $this->salida .= "                <tr>";
              $this->salida .= "                    <td class=\"".$this->SetStyle("FechaF")."\" align=\"center\">HASTA: </td>";
              $this->salida .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"FechaF\">".ReturnOpenCalendario('forma','FechaF','/')."</td>";
              $this->salida .= "                </tr><br>";
            }
            if($Tipo==3){
              $this->salida .= "                <tr>";
              $this->salida .= "                <td class=\"label\">DEPARTAMENTO: </td><td><select name=\"Departamento\" class=\"select\">";
              $departamento=$this->Departamentos();
              $this->BuscarDepartamento($departamento,$d,$Dpto);
              $this->salida .= "                  </select></td>";
              $this->salida .= "                </tr>";
            }
            if($Tipo==4){
              $this->salida .= "                <tr>";
              $this->salida .= "                <td class=\"label\">TIPO SERVICIO: </td><td><select name=\"Servicio\" class=\"select\">";
              $tipo=$this->TiposServicios();
               $this->salida .=" <option value=\"-1\" selected>--TODOS--</option>";
              for($i=0; $i<sizeof($tipo); $i++)
              {
                  $this->salida .=" <option value=\"".$tipo[$i][servicio]."\">".$tipo[$i][descripcion]."</option>";
              }
              $this->salida .= "                  </select></td>";
              $this->salida .= "                </tr>";
            }
            /*if($Busqueda=='7'){
              $this->salida .= $this->SetStyle("MensajeError");
              $this->salida .= "                <tr><td colspan=\"2\">&nbsp;</td></tr>";
              $this->salida .= "                <tr><td class=\"".$this->SetStyle("Cama")."\">No. CAMA</td><td><input type=\"text\" class=\"input-text\" name=\"Cama\" maxlength=\"32\"></td></tr>";
              $this->salida .= "                <input type=\"hidden\" name=\"TipoBuscar\" value=\"$Busqueda\">";
              $this->salida .= "                 <input type=\"hidden\" name=\"LinkCargo\" value=\"$LinkCargo\">";
            }
            if($Busqueda=='8'){
              $this->salida .= $this->SetStyle("MensajeError");
              $this->salida .= "                <tr><td colspan=\"2\">&nbsp;</td></tr>";
                $this->salida .= "                <tr><td class=\"".$this->SetStyle("Factura")."\">No. FACTURA</td><td><input type=\"text\" class=\"input-text\" name=\"Factura\" maxlength=\"32\"></td></tr>";
              $this->salida .= "                <input type=\"hidden\" name=\"TipoBuscar\" value=\"$Busqueda\">";
              $this->salida .= "                 <input type=\"hidden\" name=\"LinkCargo\" value=\"$LinkCargo\">";
            }*/
            $this->salida .= "          <tr class=\"modulo_list_claro\">";
            $this->salida .= "          </tr>";
            $this->salida .= "     </table>";
            $this->salida .= "    <input type=\"hidden\" name=\"Tipo\" value=\"$Tipo\">";
        }
        $this->salida .= "     <table border=\"0\" width=\"50%\" align=\"center\">";
        $this->salida .= "          <tr>";
        $this->salida .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"></td>";
        $this->salida .= "</form>";
        if(!empty($Tipo))
        {
            $accion=ModuloGetURL('app','Facturacion','user','TiposDivision',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
            $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
            $this->salida .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER CRITERIOS\"></td>";
        }
        $accion=ModuloGetURL('app','Facturacion','user','CancelarDivision',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
        //$accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></td>";
        $this->salida .= "          </tr>";
        $this->salida .= "     </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
    *
    */
    function Bajar()
    {
      $this->salida .= "<SCRIPT>\n";
      $this->salida .= "function Bajar(formaa){\n";
      $this->salida .= "formaa.submit();\n";
      $this->salida .= "}\n";
      $this->salida .=  "</SCRIPT>\n";
    }

    /**
    *
    */
    function FormaListadoDivision($PlanId,$Cuenta,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$vars){
      
      $file = 'app_modules/Facturacion/RemoteXajax/DivisiondeCuentas.php';
      $this->SetXajax(array("reqCambiarCargoPlan","reqCambiarAbonoPlan","reqCambiarCargoPlanTotalPage"),$file);                   
      //GLOBAL $xajax;
      //$xajax->setFlag('debug',true);
      //unset($_SESSION['DIVISION_CUENTA_VARIOS_PLANES']);
      unset($_SESSION['CUENTA']['DIVISION_CUENTA']['ACCION_FINALIZAR']);
      unset($_SESSION['CUENTA']['DIVISION_CUENTA']['PLAN']);
      //Division de Cuentas
      /*$this->IncludeJS("CrossBrowser");
      $this->IncludeJS("CrossBrowserDrag");
      $this->IncludeJS("CrossBrowserEvent");
      $this->IncludeJS("RemoteScripting");
      SessionSetVar("RutaImagen",GetThemePath());
      $this->IncludeJS("ScriptRemoting/divisionCuentas.js",'app','Facturacion');
      $this->salida .= "  <script language='javascript'>\n";      
      $this->salida .= "  function CargoOtraCuenta(frm,valor,Cuenta,pagina){\n";
      $this->salida .= "    if(frm.plan.value==-1){";
      $this->salida .= "      alert('Seleccione el Plan');\n";
      $this->salida .= "    }else{\n";
      $this->salida .= "      var cadena=new Array();\n";
      $this->salida .= "      cadena[0]=valor;\n";
      $this->salida .= "      cadena[1]=frm.plan.value;\n";
      $this->salida .= "      cadena[2]=Cuenta;\n";
      $this->salida .= "      cadena[3]=pagina;\n";
      $this->salida .= "      jsrsExecute(\"app_modules/Facturacion/ScriptRemoting/divisionCuentas.php\", valores_resultado_insercion, \"InsertarDatosDivisionCuentaCargos\",cadena);";
      $this->salida .= "    }\n";
      $this->salida .= "  }\n";
      $this->salida .= "  function AbonoOtraCuenta(frm,valor,Cuenta,pagina){\n";
      $this->salida .= "    if(frm.plan.value==-1){";
      $this->salida .= "      alert('Seleccione el Plan');\n";
      $this->salida .= "    }else{\n";
      $this->salida .= "      var cadena=new Array();\n";
      $this->salida .= "      cadena[0]=valor;\n";
      $this->salida .= "      cadena[1]=frm.plan.value;\n";
      $this->salida .= "      cadena[2]=Cuenta;\n";
      $this->salida .= "      cadena[3]=pagina;\n";
      $this->salida .= "      jsrsExecute(\"app_modules/Facturacion/ScriptRemoting/divisionCuentas.php\", valores_resultado_insercion, \"InsertarDatosDivisionCuentaAbonos\",cadena);";
      $this->salida .= "    }\n";
      $this->salida .= "  }\n";
      $this->salida .= "  </script>";*/
      
      if(empty($PlanId) AND empty($Cuenta)){
          $Cuenta=$_REQUEST['Cuenta'];
          $TipoId=$_REQUEST['TipoId'];
          $PacienteId=$_REQUEST['PacienteId'];
          $Nivel=$_REQUEST['Nivel'];
          $PlanId=$_REQUEST['PlanId'];
          $Ingreso=$_REQUEST['Ingreso'];
          $Fecha=$_REQUEST['Fecha'];
          $Nivel=$_REQUEST['Nivel'];
      }
      $_SESSION['CUENTA']['DIVISION_CUENTA']['PLAN']=$PlanId;      
      //IncludeLib("tarifario");
      IncludeLib("funciones_facturacion");
      //$this->Bajar();
      $Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
      $Apellidos=$this->BuscarApellidosPaciente($TipoId,$PacienteId);
      $this->salida .= ThemeAbrirTabla('CUENTAS -  DIVISION DE LA CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos.'');      
      //$this->EncabezadoEmpresa($Caja);
      //$abono=$this->BuscarAbonos($Cuenta);
      $abono=PagosCuentaDivision($Cuenta);
      $argu=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
      //$this->Encabezado($PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$argu,$Cuenta);

      /******************** ID ********************/
      //$this->salida .= "<table border=\"0\" width=\"90%\" align=\"center\">";
      //$this->salida .= "<tr><td align=\"center\" width=\"100%\" id=\"MostrarCargosOtraCuenta\">";

      $accion=ModuloGetURL('app','Facturacion','user','InsertarDivisionCuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'vars'=>$vars,'abajo'=>true));
      $this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
      $contcols=(sizeof($_SESSION['DIVISION_CUENTA_VARIOS_PLANES']));
      //Manejo de los planes      
      $datPlan=$this->NombrePlan($PlanId); 
      $datTer=$this->NombreTercero($datPlan['tipo_tercero_id'],$datPlan['tercero_id']);     
      $_SESSION['DIVISION_CUENTA_VARIOS_PLANES'][0][$PlanId]=$datPlan['plan_descripcion'];      
      $this->salida .= "    <table border=\"0\" width=\"75%\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "        <tr class=\"modulo_table_list_title\">";      
      $this->salida .= "        <td width=\"50%\">RESPONSABLE PLAN:&nbsp;&nbsp;&nbsp;".$datTer['nombre_tercero']."</td>";      
      $this->salida .= "        <td width=\"50%\">PLAN ACTUAL:&nbsp;&nbsp;&nbsp;".$datPlan['plan_descripcion']."</td>";
      $this->salida .= "        </tr>";      
      $this->salida .= "        <tr class=\"modulo_list_claro\">";
      $this->salida .= "        <td colspan=\"2\" align=\"center\" class=\"label\">NUEVO PLAN: &nbsp;<select name=\"planNuevo\" class=\"select\">";
      $cons = $this->Planes($PlanId);
      $this->salida .="         <option value=\"-1\">---Seleccione---</option>";
      for($k=0; $k<sizeof($cons); $k++){
        $this->salida .="       <option value=\"".$cons[$k][plan_id]."\">".$cons[$k][plan_descripcion]."</option>";
      }
      $this->salida .= "        </select>";
      $this->salida .= "        <input type=\"submit\" name=\"SeleccionarNuevoPlan\" value=\"SELECCIONAR\" class=\"input-submit\">";
      $this->salida .= "        </td>";      
      $this->salida .= "        </tr>";
      $this->salida .= "        <tr class=\"modulo_list_claro\">"; 
      $this->salida .= "        <td colspan=\"2\" align=\"center\">";
      $this->salida .= "        <table border=\"0\" width=\"100%\" align=\"center\">";
      foreach($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice=>$vector){
        if($indice!='0'){
          foreach($vector as $plan=>$plan_nom){
            $this->salida .= "        <tr class=\"modulo_list_oscuro\">";
            $this->salida .= "        <td width=\"10%\" class=\"label\">$indice</td>";
            $this->salida .= "        <td>$plan_nom</td>";
            $this->salida .= "        </tr>"; 
          }
        }  
      }
      $this->salida .= "        </table>";     
      $this->salida .= "        </td>"; 
      $this->salida .= "        </tr>";     
      $this->salida .= "    </table>";
      //fin
      
      
      //abonos cuenta actual
      $this->salida .= "   <br><table border=\"0\" width=\"75%\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "          <tr class=\"modulo_table_list_title\">";
      $this->salida .= "            <td align=\"center\" colspan=\"".(8+$contcols)."\">ABONOS DE LA CUENTA ACTUAL</td>";
      $this->salida .= "          </tr>";
      unset($_SESSION['CUENTA']['ABONOS']['ACTUAL']);
      //if(!empty($abono[abonos]))
      if(!empty($abono)){
        $this->salida .= "<tr class=\"modulo_table_list_title \">";
        $this->salida .= "  <td width=\"12%\">RECIBO CAJA</td>";
        $this->salida .= "  <td width=\"15%\">FECHA</td>";
        $this->salida .= "  <td width=\"15%\">TOTAL EFECTIVO</td>";
        $this->salida .= "  <td width=\"15%\">TOTAL CHEQUES</td>";
        $this->salida .= "  <td width=\"15%\">TOTAL TARJETAS</td>";
        $this->salida .= "  <td width=\"15%\">TOTAL BONOS</td>";
        $this->salida .= "  <td width=\"15%\">TOTAL</td>";
        foreach($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice=>$vector){        
          foreach($vector as $plan=>$plan_nom){
            if($indice!='0'){$indice=$indice;}else{$indice='';}
            $this->salida .= "            <td width=\"3%\">$indice</td>";
          }
        }
        $this->salida .= "</tr>";
        $total=0;
        for($j=0; $j<sizeof($abono); $j++){
          if(empty($_SESSION['CUENTA']['ABONOS'][$abono[$j][prefijo].$abono[$j][recibo_caja]])){
            $rcaja=$abono[$j][prefijo].$abono[$j][recibo_caja];
            $fech=$abono[$j][fecha_ingcaja];
            $Te=FormatoValor($abono[$j][total_efectivo]);
            $Tc=FormatoValor($abono[$j][total_cheques]);
            $Tt=FormatoValor($abono[$j][total_tarjetas]);
            $Tb=FormatoValor($abono[$j][total_bonos]);
            $TOTAL=FormatoValor($abono[$j][total_abono]);
            if( $j % 2){ $estilo='modulo_list_claro';}
            else {$estilo='modulo_list_oscuro';}
            $this->salida .= "<tr class=\"$estilo\" align=\"center\">";
            $this->salida .= "  <td>$rcaja</td>";
            $this->salida .= "  <td>$fech</td>";
            $this->salida .= "  <td>$Te</td>";
            $this->salida .= "  <td>$Tc</td>";
            $this->salida .= "  <td>$Tt</td>";
            $this->salida .= "  <td>$Tb</td>";
            $this->salida .= "  <td class=\"label_error\">$TOTAL</td>";            
            $valor=$Cuenta."||//".$abono[$j][fecha_ingcaja]."||//".$abono[$j][total_efectivo]."||//".$abono[$j][total_cheques]."||//".$abono[$j][total_tarjetas]."||//".$abono[$j][total_bonos]."||//".$abono[$j][total_abono];
            //$this->salida .= "            <td align=\"center\"><a href=\"javascript:AbonoOtraCuenta(document.forma,'$valor','$Cuenta');\"><img border=\"0\" src=\"".GetThemePath()."/images/abajo.png\" title=\"Cargar a Otra Cuenta\"></a></td>";                        
            foreach($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice=>$vector){        
              foreach($vector as $plan=>$plan_nom){
                $che='';              
                if($abono[$j][cuenta]==$indice){$che='checked';}          
                $this->salida .= "            <td align=\"center\"><input $che title=\"$plan_nom\" type=\"radio\" name=\"".$abono[$j][prefijo]."||//".$abono[$j][recibo_caja]."\" value=\"".$indice."||//".$plan."||//".$valor."\" onclick=\"xajax_reqCambiarAbonoPlan(this.name,this.value)\"></td>";
              }
            } 
            $this->salida .= "</tr>";
            $total+=$abono[$j][total_abono];
          }
        }
        $this->salida .= "          <tr class=\"modulo_list_claro\">";
        $this->salida .= "            <td align=\"right\" class=\"label\" colspan=\"6\">TOTAL ABONOS:  </td>";
        $this->salida .= "            <td align=\"center\" class=\"label\">".FormatoValor($total)."</td>";
        foreach($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice=>$vector){        
          foreach($vector as $plan=>$plan_nom){            
            $this->salida .= "            <td width=\"4%\"></td>";
          }
        }        
        $this->salida .= "          </tr>";
      }
      $this->salida .= "     </table>";
      
      $det=$this->DetalleNuevo($Cuenta,$paginador=1);
      $this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
      $this->salida .= $this->SetStyle("MensajeError");
      $this->salida .= "     </table>";     
      
      $this->salida .= "<br><table width=\"90%\" align=\"center\">";
      $this->salida .= "<tr><td id=\"capa_cargos\">"; 
          
      $this->salida .= "   <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";          
      $this->salida .= "          <tr class=\"modulo_table_list_title\">";      
      $this->salida .= "            <td align=\"center\" colspan=\"".(12+$contcols)."\">CARGOS DE LA CUENTA ACTUAL</td>";
      $this->salida .= "          </tr>";      
      $this->salida .= "          <tr class=\"modulo_table_list_title\">";      
      $this->salida .= "            <td align=\"center\" colspan=\"11\">&nbsp;</td>";            
      ksort($_SESSION['DIVISION_CUENTA_VARIOS_PLANES']);
      foreach($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice=>$vector){        
        foreach($vector as $plan=>$plan_nom){
          if($indice!='0'){
            $chequeado='';
            if($_SESSION['DIVISION_CUENTA_VARIOS_PLANES1']['SELECCION_TOTAL'][$indice]==$this->paginaActual){$chequeado='checked';}
            $this->salida .= "            <td align=\"center\"><input type=\"checkbox\" name=\"SeleccionTotal$indice\" value=\"".$indice."||//".$plan."\" onclick=\"xajax_reqCambiarCargoPlanTotalPage(this.checked,'$Cuenta','".$this->limit."','".$this->offset."',this.value,'$plan_ini','".$this->paginaActual."')\" align=\"center\" $chequeado></td>";
          }else{
            $plan_ini=$plan;
            $this->salida .= "            <td align=\"center\">&nbsp;</td>";      
          }
        }
      }     
      $this->salida .= "          </tr>";
      
      $this->salida .= "          <tr class=\"modulo_table_list_title\">";
      $this->salida .= "            <td width=\"7%\">TARIFARIO</td>";
      $this->salida .= "            <td width=\"5%\">CARGO</td>";
      $this->salida .= "            <td width=\"10%\">CODIGO</td>";
      $this->salida .= "            <td>DESCRIPCION</td>";
      $this->salida .= "            <td width=\"8%\">FECHA CARGO</td>";
      $this->salida .= "            <td width=\"5%\">HORA</td>";
      $this->salida .= "            <td width=\"7%\">CANT</td>";
      $this->salida .= "            <td width=\"8%\">VALOR CARGO</td>";
      $this->salida .= "            <td width=\"8%\">VAL. NO CUBIERTO</td>";
      $this->salida .= "            <td width=\"8%\">VAL. CUBIERTO</td>";
      $this->salida .= "            <td width=\"10%\">DPTO.</td>";     
      foreach($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice=>$vector){        
        foreach($vector as $plan=>$plan_nom){
          if($indice!='0'){$indice=$indice;}else{$indice='';}
          $this->salida .= "            <td width=\"3%\">$indice</td>";
        }
      }    
      $this->salida .= "          </tr>";
      $car=$cubi=$nocub=0;
      if(!empty($det)){        
        for($i=0; $i<sizeof($det);$i++){          
          if($i % 2) {  $estilo="modulo_list_claro";  }
          else {  $estilo="modulo_list_oscuro";   }          
          //suma los totales del final
          $car+=$det[$i][valor_cargo];
          $cubi+=$det[$i][valor_cubierto];
          $nocub+=$det[$i][valor_nocubierto];                    
          $this->salida .= "            <tr class=\"$estilo\">";
          $this->salida .= "            <td width=\"7%\" align=\"center\">".$det[$i][tarifario_id]."</td>";
          $this->salida .= "            <td width=\"5%\" align=\"center\">".$det[$i][cargo]."</td>";
          $this->salida .= "            <td width=\"10%\" align=\"center\">".$det[$i][codigo_producto]."</td>";
          $this->salida .= "            <td>".$det[$i][descripcion]."</td>";
          $this->salida .= "            <td width=\"8%\" align=\"center\">".$this->FechaStamp($det[$i][fecha_cargo])."</td>";
          $this->salida .= "            <td width=\"5%\" align=\"center\">".$this->HoraStamp($det[$i][fecha_cargo])."</td>";
          $this->salida .= "            <td width=\"7%\" align=\"center\">".FormatoValor($det[$i][cantidad])."</td>";
          $this->salida .= "            <td width=\"8%\" align=\"center\">".FormatoValor($det[$i][valor_cargo])."</td>";
          $this->salida .= "            <td width=\"8%\" align=\"center\">".FormatoValor($det[$i][valor_nocubierto])."</td>";
          $this->salida .= "            <td width=\"8%\" align=\"center\">".FormatoValor($det[$i][valor_cubierto])."</td>";                                         
          $this->salida .= "            <td>".$det[$i][departamento]."</td>";
          $valor=$det[$i-1][transaccion].'||//'.$det[$i-1][cargo_cups].'||//'.$det[$i-1][codigo_agrupamiento_id].'||//'.$det[$i-1][consecutivo];
          //$this->salida .= "            <td align=\"center\"><a href=\"javascript:CargoOtraCuenta(document.forma,'$valor','$Cuenta');\"><img border=\"0\" src=\"".GetThemePath()."/images/abajo.png\" title=\"Cargar a Otra Cuenta\"></a></td>";
          foreach($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice=>$vector){        
            foreach($vector as $plan=>$plan_nom){
              $che='';                           
              if($det[$i][cuenta]==$indice){$che='checked';}          
              $this->salida .= "            <td width=\"3%\" align=\"center\"><input $che title=\"$plan_nom\" type=\"radio\" name=\"".$det[$i][transaccion]."\" value=\"".$indice."||//".$plan."\" onclick=\"xajax_reqCambiarCargoPlan(this.name,this.value)\"></td>";              
            }
          }
          $this->salida .= "            </tr>";         
        }        
        
        if($i % 2) {  $estilo="modulo_list_claro";  }
        else {  $estilo="modulo_list_oscuro";   }
        $this->salida .= "          <tr class=\"$estilo\">";
        $this->salida .= "            <td colspan=\"7\" class=\"label\"  align=\"right\">TOTALES:  </td>";
        $this->salida .= "            <td align=\"center\" class=\"label\">".FormatoValor($car)."</td>";
        $this->salida .= "            <td align=\"center\" class=\"label\">".FormatoValor($nocub)."</td>";
        $this->salida .= "            <td align=\"center\" class=\"label\">".FormatoValor($cubi)."</td>";
        $this->salida .= "            <td>&nbsp;</td>";
        //$this->salida .= "            <td align=\"center\"><a href=\"javascript:Bajar(document.forma);\"><img border=\"0\" src=\"".GetThemePath()."/images/abajo.png\"></a></td>";
        foreach($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice=>$vector){        
          foreach($vector as $plan=>$plan_nom){
            $this->salida .= "            <td width=\"3%\"></td>";
          }
        } 
        $this->salida .= "          </tr>";        
      }      
      $this->salida .= "     </table>";
      
      $this->salida .= "     </td></tr>";
      $this->salida .= "     </table>";
      $Paginador = new ClaseHTML();
      $this->actionPaginador=ModuloGetURL('app','Facturacion','user','ObtenerFormaListadoDivision',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'vars'=>$vars,'abajo'=>true));
      $this->salida .= "      ".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
      
      $this->salida .= "</form>";
      
             
             
//                  //--------------------------------CUENTAS NUEVAS---------------------------
//           //LISTADO DE LOS CARGOS DE LA NUEVA CUENTA
//           //$accion=ModuloGetURL('app','Facturacion','user','InsertarDivisionCuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'vars'=>$vars,'abajo'=>false));
//           //$this->salida .= "<form name=\"forma2\" action=\"$accion\" method=\"post\">";
//           //$this->salida .= "</form>";
//
//
//
//                  $new=$this->DetalleNuevo($Cuenta);
//                  unset($vecplan);
//                  $det=$new;
//                  for($j=0; $j<sizeof($det);)
//                  {
//                          $this->salida .= "<DIV align=\"center\" class=\"label_mark\">".$det[$j]['plan_descripcion']."</DIV>";
//                          $abono='';
//                          $vecplan[]=$det[$j]['plan_id'];
//                          $abono = $this->DivisionAbonosCuenta($Cuenta,$det[$j]['plan_id']);
//                          if($abono)
//                          {
//                                  $this->salida .= "   <br><table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
//                                  $this->salida .= "          <tr class=\"modulo_table_list_title\">";
//                                  $this->salida .= "            <td align=\"center\" colspan=\"8\">ABONOS DE LA CUENTA NUEVA PLAN ".$det[$j]['plan_descripcion']."</td>";
//                                  $this->salida .= "          </tr>";
//                                  $this->salida .= "<tr class=\"modulo_table_list_title \">";
//                                  $this->salida .= "  <td width=\"12%\">RECIBO CAJA</td>";
//                                  $this->salida .= "  <td width=\"15%\">FECHA</td>";
//                                  $this->salida .= "  <td width=\"15%\">TOTAL EFECTIVO</td>";
//                                  $this->salida .= "  <td width=\"15%\">TOTAL CHEQUES</td>";
//                                  $this->salida .= "  <td width=\"15%\">TOTAL TARJETAS</td>";
//                                  $this->salida .= "  <td width=\"15%\">TOTAL BONOS</td>";
//                                  $this->salida .= "  <td width=\"15%\">TOTAL</td>";
//                                  $this->salida .= "  <td width=\"4%\"></td>";
//                                  $this->salida .= "</tr>";
//                                  $total=0;
//                                  for($k=0; $k<sizeof($abono); $k++)
//                                  {
//                                          $total+=$abono[$k]['total_abono'];
//                                          if( $j % 2){ $estilo='modulo_list_claro';}
//                                          else {$estilo='modulo_list_oscuro';}
//                                          $this->salida .= "<tr class=\"$estilo\" align=\"center\">";
//                                          $this->salida .= "  <td>".$abono[$k]['prefijo']."".$abono[$k]['recibo_caja']."</td>";
//                                          $this->salida .= "  <td>".$abono[$k]['fecha_ingcaja']."</td>";
//                                          $this->salida .= "  <td>".FormatoValor($abono[$k]['total_efectivo'])."</td>";
//                                          $this->salida .= "  <td>".FormatoValor($abono[$k]['total_cheques'])."</td>";
//                                          $this->salida .= "  <td>".FormatoValor($abono[$k]['total_tarjetas'])."</td>";
//                                          $this->salida .= "  <td>".FormatoValor($abono[$k]['total_bonos'])."</td>";
//                                          $this->salida .= "  <td class=\"label_error\">".FormatoValor($abono[$k]['total_abono'])."</td>";
//                                          $this->salida .= "  <td align=\"center\" width=\"4%\"><input type=\"checkbox\" value=\"".$abono[$k]['prefijo'].",".$abono[$k]['recibo_caja'].",".$abono[$k]['plan_id'].",".$abono[$k]['fecha_ingcaja'].",".$abono[$k]['total_efectivo'].",".$abono[$k]['total_cheques'].",".$abono[$k]['total_tarjetas'].",".$abono[$k]['total_bonos'].",".$abono[$k]['total_abono']."\" name=\"nuevo".$abono[$k]['prefijo']."".$abono[$k]['recibo_caja']."\"></td>";
//                                          $this->salida .= "</tr>";
//                                          $total+=$v[total];
//                                  }
//                                  $this->salida .= "          <tr class=\"modulo_list_claro\">";
//                                  $this->salida .= "            <td align=\"right\" class=\"label\" colspan=\"6\">TOTAL ABONOS:  </td>";
//                                  $this->salida .= "            <td align=\"center\" class=\"label\">".FormatoValor($total)."</td>";
//                                  $this->salida .= "            <td align=\"center\" width=\"4%\"></td>";
//                                  $this->salida .= "          </tr>";
//                                  $this->salida .= "     </table>";
//                          }//fin del abono
//                          $this->salida .= "   <br>  <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
//                          $this->salida .= "          <tr class=\"modulo_table_list_title\">";
//                          $this->salida .= "            <td align=\"center\" colspan=\"10\">CARGOS DE LA NUEVA CUENTA PLAN ".$det[$j]['plan_descripcion']."</td>";
//                          $this->salida .= "          </tr>";
//                          $this->salida .= "          <tr class=\"modulo_table_list_title\">";
//                          $this->salida .= "            <td width=\"7%\">TARIFARIO</td>";
//                          $this->salida .= "            <td width=\"5%\">CARGO</td>";
//               $this->salida .= "            <td width=\"10%\">CODIGO</td>";
//                          $this->salida .= "            <td>DESCRIPCION</td>";
//                          $this->salida .= "            <td width=\"8%\">FECHA CARGO</td>";
//                          $this->salida .= "            <td width=\"7%\">CANT</td>";
//                          $this->salida .= "            <td width=\"10%\">VALOR CARGO</td>";
//                          $this->salida .= "            <td width=\"10%\">VAL. NO CUBIERTO</td>";
//                          $this->salida .= "            <td width=\"10%\">VAL. CUBIERTO</td>";
//                          $this->salida .= "            <td width=\"3%\"></td>";
//                          $this->salida .= "          </tr>";
//                          $d=$j;
//                          $car=$cubi=$nocub=0;
//                          while($det[$j]['plan_id']==$det[$d]['plan_id'])
//                          {
//                                  if($d % 2) {  $estilo="modulo_list_claro";  }
//                                  else {  $estilo="modulo_list_oscuro";   }
//                                  $this->salida .= "          <tr class=\"$estilo\">";
//                   //este codigo se comento para poder pasar los medicamentos de una cuenta a otra
//                                  /*if(!empty($det[$d][codigo_agrupamiento_id]) AND !empty($det[$d][consecutivo]))
//                                  {
//                                              $m=$d;
//                                              $Cantidad=$valor=$cub=$nocub=0;
//                                              while($det[$d][codigo_agrupamiento_id]==$det[$m][codigo_agrupamiento_id])
//                                              {
//                                                  $Cantidad+=$det[$m][cantidad];
//                                                  $valor+=$det[$m][fac];
//                                                  $cub+=$det[$m][valor_cubierto];
//                                                  $nocub+=$det[$m][valor_nocubierto];
//                                                  //suma los totales del final
//                                                  $car+=$det[$d][valor_cargo];
//                                                  $cubi+=$det[$d][valor_cubierto];
//                                                  $nocub+=$det[$d][valor_nocubierto];
//                                                  $m++;
//                                              }
//                                              $des=$this->NombreCodigoAgrupamiento($det[$d][codigo_agrupamiento_id]);
//                                              $this->salida .= "            <td align=\"center\">".$det[$d][tarifario_id]."</td>";
//                                              $this->salida .= "            <td align=\"center\">".$det[$d][cargo]."</td>";
//                         $this->salida .= "            <td align=\"center\">".$det[$d][codigo_producto]."</td>";
//                                              $this->salida .= "            <td>".$des[descripcion]."</td>";
//                                              $this->salida .= "            <td align=\"center\">".$this->FechaStamp($det[$d][fecha_cargo])."</td>";
//                                              $this->salida .= "            <td align=\"center\">".FormatoValor($Cantidad)."</td>";
//                                              $this->salida .= "            <td align=\"center\">".FormatoValor($valor)."</td>";
//                                              $this->salida .= "            <td align=\"center\">".FormatoValor($nocub)."</td>";
//                                              $this->salida .= "            <td align=\"center\">".FormatoValor($cub)."</td>";
//                                              $this->salida .= "            <td><input type=\"checkbox\" value=\"".$det[$d][transaccion].",".$det[$d][codigo_agrupamiento_id].",".$det[$d][consecutivo]."\" name=\"Go".$det[$d][transaccion].$det[$d][codigo_agrupamiento_id]."\"></td>";
//                                              $d=$m;
//                                  }//fin if
//                                  else
//                                  {*/
//                   //fin codigo comentado
//                       //suma los totales del final
//                                          $car+=$det[$d][valor_cargo];
//                                          $cubi+=$det[$d][valor_cubierto];
//                                          $nocub+=$det[$d][valor_nocubierto];
//                                          $this->salida .= "            <td align=\"center\">".$det[$d][tarifario_id]."</td>";
//                                          $this->salida .= "            <td align=\"center\">".$det[$d][cargo]."</td>";
//                       $this->salida .= "            <td align=\"center\">".$det[$d][codigo_producto]."</td>";
//                                          $this->salida .= "            <td>".$det[$d][descripcion]."</td>";
//                                          $this->salida .= "            <td align=\"center\">".$this->FechaStamp($det[$d][fecha_cargo])."</td>";
//                                          $this->salida .= "            <td align=\"center\">".FormatoValor($det[$d][cantidad])."</td>";
//                                          $this->salida .= "            <td align=\"center\">".FormatoValor($det[$d][valor_cargo])."</td>";
//                                          $this->salida .= "            <td align=\"center\">".FormatoValor($det[$d][valor_nocubierto])."</td>";
//                                          $this->salida .= "            <td align=\"center\">".FormatoValor($det[$d][valor_cubierto])."</td>";
//                                          $this->salida .= "            <td><input type=\"checkbox\" value=\"".$det[$d][transaccion].",".$det[$d][codigo_agrupamiento_id].",".$det[$d][consecutivo]."\" name=\"Go".$det[$d][transaccion].$det[$d][codigo_agrupamiento_id]."\"></td>";
//                                          $d++;
//                                  //}
//                                  $this->salida .= "          </tr>";
//                          }
//               if($i % 2) {  $estilo="modulo_list_claro";  }
//               else {  $estilo="modulo_list_oscuro";   }
//               $this->salida .= "          <tr class=\"$estilo\">";
//               $this->salida .= "            <td colspan=\"6\" class=\"label\"  align=\"right\">TOTALES:  </td>";
//               $this->salida .= "            <td align=\"center\" class=\"label\">".FormatoValor($car)."</td>";
//               $this->salida .= "            <td align=\"center\" class=\"label\">".FormatoValor($nocub)."</td>";
//               $this->salida .= "            <td align=\"center\" class=\"label\">".FormatoValor($cubi)."</td>";
//               $this->salida .= "            <td align=\"center\"><a href=\"javascript:Bajar(document.forma2);\"><img border=\"0\" src=\"".GetThemePath()."/images/arriba.png\"></a></td>";
//               $this->salida .= "          </tr>";
//                          $this->salida .= "     </table><br><br>";
//                          $j=$d;
//                  }
                    //---------------------abonos cuando solo han elegido abonos
//                  $det=$this->DivisionSoloAbonosCuenta($Cuenta,$vecplan);
//                  if(!empty($det))
//                  {
//                          for($j=0; $j<sizeof($det); $j++)
//                          {
//                                  $this->salida .= "<DIV align=\"center\" class=\"label_mark\">".$det[$j]['plan_descripcion']."</DIV>";
//                                  $abono='';
//                                  $vecplan[]=$det[$j]['plan_id'];
//                                  $abono = $this->DivisionAbonosCuenta($Cuenta,$det[$j]['plan_id']);
//                                  if($abono)
//                                  {
//                                          $this->salida .= "   <br><table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
//                                          $this->salida .= "          <tr class=\"modulo_table_list_title\">";
//                                          $this->salida .= "            <td align=\"center\" colspan=\"8\">ABONOS DE LA CUENTA NUEVA PLAN ".$det[$j]['plan_descripcion']."</td>";
//                                          $this->salida .= "          </tr>";
//                                          $this->salida .= "<tr class=\"modulo_table_list_title \">";
//                                          $this->salida .= "  <td width=\"12%\">RECIBO CAJA</td>";
//                                          $this->salida .= "  <td width=\"15%\">FECHA</td>";
//                                          $this->salida .= "  <td width=\"15%\">TOTAL EFECTIVO</td>";
//                                          $this->salida .= "  <td width=\"15%\">TOTAL CHEQUES</td>";
//                                          $this->salida .= "  <td width=\"15%\">TOTAL TARJETAS</td>";
//                                          $this->salida .= "  <td width=\"15%\">TOTAL BONOS</td>";
//                                          $this->salida .= "  <td width=\"15%\">TOTAL</td>";
//                                          $this->salida .= "  <td width=\"4%\"></td>";
//                                          $this->salida .= "</tr>";
//                                          $total=0;
//                                          for($k=0; $k<sizeof($abono); $k++)
//                                          {
//                                                  $total+=$abono[$k]['total_abono'];
//                                                  if( $j % 2){ $estilo='modulo_list_claro';}
//                                                  else {$estilo='modulo_list_oscuro';}
//                                                  $this->salida .= "<tr class=\"$estilo\" align=\"center\">";
//                                                  $this->salida .= "  <td>".$abono[$k]['prefijo']."".$abono[$k]['recibo_caja']."</td>";
//                                                  $this->salida .= "  <td>".$abono[$k]['fecha_ingcaja']."</td>";
//                                                  $this->salida .= "  <td>".FormatoValor($abono[$k]['total_efectivo'])."</td>";
//                                                  $this->salida .= "  <td>".FormatoValor($abono[$k]['total_cheques'])."</td>";
//                                                  $this->salida .= "  <td>".FormatoValor($abono[$k]['total_tarjetas'])."</td>";
//                                                  $this->salida .= "  <td>".FormatoValor($abono[$k]['total_bonos'])."</td>";
//                                                  $this->salida .= "  <td class=\"label_error\">".FormatoValor($abono[$k]['total_abono'])."</td>";
//                                                  $this->salida .= "  <td align=\"center\" width=\"4%\"><input type=\"checkbox\" value=\"".$abono[$k]['prefijo'].",".$abono[$k]['recibo_caja'].",".$abono[$k]['plan_id'].",".$abono[$k]['fecha_ingcaja'].",".$abono[$k]['total_efectivo'].",".$abono[$k]['total_cheques'].",".$abono[$k]['total_tarjetas'].",".$abono[$k]['total_bonos'].",".$abono[$k]['total_abono']."\" name=\"nuevo".$abono[$k]['prefijo']."".$abono[$k]['recibo_caja']."\"></td>";
//                                                  $this->salida .= "</tr>";
//                                                  $total+=$v[total];
//                                          }
//                                          $this->salida .= "          <tr class=\"modulo_list_claro\">";
//                                          $this->salida .= "            <td align=\"right\" class=\"label\" colspan=\"6\">TOTAL ABONOS:  </td>";
//                                          $this->salida .= "            <td align=\"center\" class=\"label\">".FormatoValor($total)."</td>";
//                                          $this->salida .= "            <td align=\"center\" width=\"4%\"></td>";
//                                          $this->salida .= "          </tr>";
//                                          $this->salida .= "     </table>";
//                                  }//fin del abono
//                          }
//                  }
                    //---------------------fin solo abonos


//          $this->salida .= "</td></tr>";
//          $this->salida .= "</table>";
          
          $this->salida .= "     <table border=\"0\" width=\"50%\" align=\"center\">";
          $this->salida .= "     <tr>";
          $accion=ModuloGetURL('app','Facturacion','user','BuscarDivision',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
          $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $this->salida .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER CRITERIOS\"></td>";
          $this->salida .= "</form>";
          $accion=ModuloGetURL('app','Facturacion','user','CancelarDivision',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
          $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $this->salida .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER AL DETALLE\"></td>";
          $this->salida .= "</form>";
          if($contcols>1){
            $msg='Esta seguro que la Divisi?n de la Cuenta No. '.$Cuenta.' esta Correcta';
            $arreglo=array('PlanId'=>$PlanId,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Nivel'=>$Nivel,'Fecha'=>$Fecha,'vars'=>$vars);
            $accionEstado=ModuloGetURL('app','Facturacion','user','ConfirmarAccion',array('c'=>'app','m'=>'Facturacion','me2'=>'FormaListadoDivision','me'=>'FinalizarDivision','mensaje'=>$msg,'titulo'=>'DIVIDIR CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos,'arreglo'=>$arreglo,'boton1'=>'CONTINUAR','boton2'=>'CANCELAR'));          
            $this->salida .= "    <form name=\"formabuscar\" action=\"$accionEstado\" method=\"post\">";
            $this->salida .= "      <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"TERMINAR DIVISION\"></td>";
            $this->salida .= "    </form>";
          }
          $this->salida .= "          </tr>";
          $this->salida .= "     </table>";
          $this->salida .= "     <script>";
          //$this->salida .= "     CrearVariables(new Array('$Cuenta','$PlanId','1'))";
          $this->salida .= "     </script>";
          $this->salida .= ThemeCerrarTabla();
          return true;
    }

/*
    function FormaListadoDivision($PlanId,$Cuenta,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$vars)
    {
          if(empty($PlanId) AND empty($Cuenta))
          {
              $Cuenta=$_REQUEST['Cuenta'];
              $TipoId=$_REQUEST['TipoId'];
              $PacienteId=$_REQUEST['PacienteId'];
              $Nivel=$_REQUEST['Nivel'];
              $PlanId=$_REQUEST['PlanId'];
              $Ingreso=$_REQUEST['Ingreso'];
              $Fecha=$_REQUEST['Fecha'];
              $Nivel=$_REQUEST['Nivel'];
          }
          //IncludeLib("tarifario");
          IncludeLib("funciones_facturacion");
          $this->Bajar();
          $Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
          $Apellidos=$this->BuscarApellidosPaciente($TipoId,$PacienteId);
          $this->salida .= ThemeAbrirTabla('CUENTAS -  DIVISION DE LA CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos.'');
          $this->EncabezadoEmpresa($Caja);
          //$abono=$this->BuscarAbonos($Cuenta);
                    $abono=PagosCuenta($Cuenta);
          $argu=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
          $this->Encabezado($PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$argu,$Cuenta);
          $accion=ModuloGetURL('app','Facturacion','user','InsertarDivisionCuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'vars'=>$vars,'abajo'=>true));
          $this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
          //abonos cuenta actual
          $this->salida .= "   <br><table border=\"0\" width=\"75%\" align=\"center\" class=\"modulo_table_list\">";
          $this->salida .= "          <tr class=\"modulo_table_list_title\">";
          $this->salida .= "            <td align=\"center\" colspan=\"8\">ABONOS DE LA CUENTA ACTUAL</td>";
          $this->salida .= "          </tr>";
          unset($_SESSION['CUENTA']['ABONOS']['ACTUAL']);
          //if(!empty($abono[abonos]))
          if(!empty($abono))
          {
                            $this->salida .= "<tr class=\"modulo_table_list_title \">";
                            $this->salida .= "  <td width=\"12%\">RECIBO CAJA</td>";
                            $this->salida .= "  <td width=\"15%\">FECHA</td>";
                            $this->salida .= "  <td width=\"15%\">TOTAL EFECTIVO</td>";
                            $this->salida .= "  <td width=\"15%\">TOTAL CHEQUES</td>";
                            $this->salida .= "  <td width=\"15%\">TOTAL TARJETAS</td>";
                            $this->salida .= "  <td width=\"15%\">TOTAL BONOS</td>";
                            $this->salida .= "  <td width=\"15%\">TOTAL</td>";
                            $this->salida .= "  <td width=\"4%\"></td>";
                            $this->salida .= "</tr>";
                            $total=0;
                            for($j=0; $j<sizeof($abono); $j++)
                            {
                                if(empty($_SESSION['CUENTA']['ABONOS'][$abono[$j][prefijo].$abono[$j][recibo_caja]]))
                                {
                                        $rcaja=$abono[$j][prefijo].$abono[$j][recibo_caja];
                                        $fech=$abono[$j][fecha_ingcaja];
                                        $Te=FormatoValor($abono[$j][total_efectivo]);
                                        $Tc=FormatoValor($abono[$j][total_cheques]);
                                        $Tt=FormatoValor($abono[$j][total_tarjetas]);
                                        $Tb=FormatoValor($abono[$j][total_bonos]);
                                        $TOTAL=FormatoValor($abono[$j][total_abono]);
                                        if( $j % 2){ $estilo='modulo_list_claro';}
                                        else {$estilo='modulo_list_oscuro';}
                                        $this->salida .= "<tr class=\"$estilo\" align=\"center\">";
                                        $this->salida .= "  <td>$rcaja</td>";
                                        $this->salida .= "  <td>$fech</td>";
                                        $this->salida .= "  <td>$Te</td>";
                                        $this->salida .= "  <td>$Tc</td>";
                                        $this->salida .= "  <td>$Tt</td>";
                                        $this->salida .= "  <td>$Tb</td>";
                                        $this->salida .= "  <td class=\"label_error\">$TOTAL</td>";
                                        $this->salida .= "  <td align=\"center\" width=\"4%\"><input type=\"checkbox\" value=\"".$abono[$j][prefijo].",".$abono[$j][recibo_caja].",".$abono[$j][fecha_ingcaja].",".$abono[$j][total_efectivo].",".$abono[$j][total_cheques].",".$abono[$j][total_tarjetas].",".$abono[$j][total_bonos].",".$abono[$j][total_abono]."\" name=\"actual".$abono[$j][prefijo]."".$abono[$j][recibo_caja]."\"></td>";
                                        $this->salida .= "</tr>";
                                        $total+=$abono[$j][total_abono];
                                }
                            }
              $this->salida .= "          <tr class=\"modulo_list_claro\">";
              $this->salida .= "            <td align=\"right\" class=\"label\" colspan=\"6\">TOTAL ABONOS:  </td>";
              $this->salida .= "            <td align=\"center\" class=\"label\">".FormatoValor($total)."</td>";
              $this->salida .= "            <td align=\"center\" width=\"4%\"></td>";
              $this->salida .= "          </tr>";
          }
          $this->salida .= "     </table>";
          $det=$this->DetalleTotal($Cuenta);
          $this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida .= "     </table>";
          $this->salida .= "   <br>  <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
          $this->salida .= "          <tr class=\"modulo_table_list_title\">";
          $this->salida .= "            <td align=\"center\" colspan=\"9\">CARGOS DE LA CUENTA ACTUAL</td>";
          $this->salida .= "          </tr>";
          $this->salida .= "          <tr class=\"modulo_table_list_title\">";
          $this->salida .= "            <td width=\"7%\">TARIFARIO</td>";
          $this->salida .= "            <td width=\"5%\">CARGO</td>";
          $this->salida .= "            <td>DESCRIPCION</td>";
          $this->salida .= "            <td width=\"8%\">FECHA CARGO</td>";
          $this->salida .= "            <td width=\"7%\">CANT</td>";
          $this->salida .= "            <td width=\"10%\">VALOR CARGO</td>";
          $this->salida .= "            <td width=\"10%\">VAL. NO CUBIERTO</td>";
          $this->salida .= "            <td width=\"10%\">VAL. CUBIERTO</td>";
          $this->salida .= "            <td width=\"3%\"></td>";
          $this->salida .= "          </tr>";
          $car=$cub=$nocub=0;
          if(!empty($det))
          {
              for($i=0; $i<sizeof($det);)
              {
                  if($i % 2) {  $estilo="modulo_list_claro";  }
                  else {  $estilo="modulo_list_oscuro";   }
                  $this->salida .= "          <tr class=\"$estilo\">";
                                    if(!empty($det[$i][codigo_agrupamiento_id]) AND !empty($det[$i][consecutivo]))
                                    {
                                                $d=$i;
                                                $Cantidad=$valor=$cub=$nocub=0;
                                                while($det[$i][codigo_agrupamiento_id]==$det[$d][codigo_agrupamiento_id])
                                                {
                                                    $Cantidad+=$det[$d][cantidad];
                                                    $valor+=$det[$d][fac];
                                                    $cub+=$det[$d][valor_cubierto];
                                                    $nocub+=$det[$d][valor_nocubierto];
                                                    $d++;
                                                }
                                                $des=$this->NombreCodigoAgrupamiento($det[$i][codigo_agrupamiento_id]);
                                                $this->salida .= "            <td align=\"center\">".$det[$i][tarifario_id]."</td>";
                                                $this->salida .= "            <td align=\"center\">".$det[$i][cargo]."</td>";
                                                $this->salida .= "            <td>".$des[descripcion]."</td>";
                                                $this->salida .= "            <td align=\"center\">".$this->FechaStamp($det[$i][fecha_cargo])."</td>";
                                                $this->salida .= "            <td align=\"center\">".FormatoValor($Cantidad)."</td>";
                                                $this->salida .= "            <td align=\"center\">".FormatoValor($valor)."</td>";
                                                $this->salida .= "            <td align=\"center\">".FormatoValor($nocub)."</td>";
                                                $this->salida .= "            <td align=\"center\">".FormatoValor($cub)."</td>";
                                                $i=$d;
                                    }//fin if
                                    else
                                    {
                                            $this->salida .= "            <td align=\"center\">".$det[$i][tarifario_id]."</td>";
                                            $this->salida .= "            <td align=\"center\">".$det[$i][cargo]."</td>";
                                            $this->salida .= "            <td>".$det[$i][descripcion]."</td>";
                                            $this->salida .= "            <td align=\"center\">".$this->FechaStamp($det[$i][fecha_cargo])."</td>";
                                            $this->salida .= "            <td align=\"center\">".FormatoValor($det[$i][cantidad])."</td>";
                                            $this->salida .= "            <td align=\"center\">".FormatoValor($det[$i][valor_cargo])."</td>";
                                            $this->salida .= "            <td align=\"center\">".FormatoValor($det[$i][valor_nocubierto])."</td>";
                                            $this->salida .= "            <td align=\"center\">".FormatoValor($det[$i][valor_cubierto])."</td>";
                                            $i++;
                                    }
                  $f=0;
                  for($d=0; $d<sizeof($vars); $d++)
                  {
                      if($vars[$d][transaccion]==$det[$i-1][transaccion])
                      {
                          $this->salida .= "            <td><input type=\"checkbox\" value=\"".$det[$i-1][transaccion].",".$det[$i-1][codigo_agrupamiento_id].",".$det[$i-1][consecutivo]."\" name=\"New".$det[$i-1][codigo_agrupamiento_id].$det[$i-1][codigo_agrupamiento_id]."\" checked></td>";
                          $d=sizeof($vars);
                          $f=1;
                      }
                  }
                  if($f==0)
                  {
                      $this->salida .= "            <td><input type=\"checkbox\" value=\"".$det[$i-1][transaccion].",".$det[$i-1][codigo_agrupamiento_id].",".$det[$i-1][consecutivo]."\" name=\"New".$det[$i-1][transaccion].$det[$i-1][codigo_agrupamiento_id]."\"></td>";
                  }
                  $this->salida .= "          </tr>";
                  $car+=$det[$i][valor_cargo];
                  $cub+=$det[$i][valor_cubierto];
                  $nocub+=$det[$i][valor_nocubierto];
              }
              if($i % 2) {  $estilo="modulo_list_claro";  }
              else {  $estilo="modulo_list_oscuro";   }
              $this->salida .= "          <tr class=\"$estilo\">";
              $this->salida .= "            <td colspan=\"5\" class=\"label\"  align=\"right\">TOTALES:  </td>";
              $this->salida .= "            <td align=\"center\" class=\"label\">".FormatoValor($car)."</td>";
              $this->salida .= "            <td align=\"center\" class=\"label\">".FormatoValor($nocub)."</td>";
              $this->salida .= "            <td align=\"center\" class=\"label\">".FormatoValor($cub)."</td>";
              $this->salida .= "            <td align=\"center\"><a href=\"javascript:Bajar(document.forma);\"><img border=\"0\" src=\"".GetThemePath()."/images/abajo.png\"></a></td>";
              $this->salida .= "          </tr>";
          }
          $this->salida .= "     </table>";
          $this->salida .= "</form>";
          //LISTADO DE LOS CARGOS DE LA NUEVA CUENTA
          $accion=ModuloGetURL('app','Facturacion','user','InsertarDivisionCuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'vars'=>$vars,'abajo'=>false));
          $this->salida .= "<form name=\"forma2\" action=\"$accion\" method=\"post\">";
          //abonos cuenta nueva
          $this->salida .= "   <br><table border=\"0\" width=\"75%\" align=\"center\" class=\"modulo_table_list\">";
          $this->salida .= "          <tr class=\"modulo_table_list_title\">";
          $this->salida .= "            <td align=\"center\" colspan=\"8\">ABONOS DE LA CUENTA NUEVA</td>";
          $this->salida .= "          </tr>";
          if(!empty($_SESSION['CUENTA']['ABONOS']))
          {
              $total=0;
                            $this->salida .= "<tr class=\"modulo_table_list_title \">";
                            $this->salida .= "  <td width=\"12%\">RECIBO CAJA</td>";
                            $this->salida .= "  <td width=\"15%\">FECHA</td>";
                            $this->salida .= "  <td width=\"15%\">TOTAL EFECTIVO</td>";
                            $this->salida .= "  <td width=\"15%\">TOTAL CHEQUES</td>";
                            $this->salida .= "  <td width=\"15%\">TOTAL TARJETAS</td>";
                            $this->salida .= "  <td width=\"15%\">TOTAL BONOS</td>";
                            $this->salida .= "  <td width=\"15%\">TOTAL</td>";
                            $this->salida .= "  <td width=\"4%\"></td>";
                            $this->salida .= "</tr>";
                            foreach($_SESSION['CUENTA']['ABONOS'] as $k => $v)
                            {
                                        $rcaja=$v[prefijo].$v[recibo];
                                        $fech=$v[fecha];
                                        $Te=FormatoValor($v[efectivo]);
                                        $Tc=FormatoValor($v[cheque]);
                                        $Tt=FormatoValor($v[tarjeta]);
                                        $Tb=FormatoValor($v[bonos]);
                                        $TOTAL=FormatoValor($v[total]);
                                        if( $j % 2){ $estilo='modulo_list_claro';}
                                        else {$estilo='modulo_list_oscuro';}
                                        $this->salida .= "<tr class=\"$estilo\" align=\"center\">";
                                        $this->salida .= "  <td>$rcaja</td>";
                                        $this->salida .= "  <td>$fech</td>";
                                        $this->salida .= "  <td>$Te</td>";
                                        $this->salida .= "  <td>$Tc</td>";
                                        $this->salida .= "  <td>$Tt</td>";
                                        $this->salida .= "  <td>$Tb</td>";
                                        $this->salida .= "  <td class=\"label_error\">$TOTAL</td>";
                                        $this->salida .= "  <td align=\"center\" width=\"4%\"><input type=\"checkbox\" value=\"".$v[prefijo].",".$v[recibo].",".$v[fecha].",".$v[efectivo].",".$v[cheque].",".$v[tarjeta].",".$v[bonos].",".$v[total]."\" name=\"nuevo".$v[prefijo]."".$v[recibo]."\"></td>";
                                        $this->salida .= "</tr>";
                                        $total+=$v[total];
                            }
              $this->salida .= "          <tr class=\"modulo_list_claro\">";
              $this->salida .= "            <td align=\"right\" class=\"label\" colspan=\"6\">TOTAL ABONOS:  </td>";
              $this->salida .= "            <td align=\"center\" class=\"label\">$total</td>";
              $this->salida .= "            <td align=\"center\" width=\"4%\"></td>";
              $this->salida .= "          </tr>";
          }
          $this->salida .= "     </table>";
          $new=$this->DetalleNuevo($Cuenta);
          $det=$new;
          $this->salida .= "   <br>  <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
          $this->salida .= "          <tr class=\"modulo_table_list_title\">";
          $this->salida .= "            <td align=\"center\" colspan=\"9\">CARGOS DE LA NUEVA CUENTA</td>";
          $this->salida .= "          </tr>";
          $this->salida .= "          <tr class=\"modulo_table_list_title\">";
          $this->salida .= "            <td width=\"7%\">TARIFARIO</td>";
          $this->salida .= "            <td width=\"5%\">CARGO</td>";
          $this->salida .= "            <td>DESCRIPCION</td>";
          $this->salida .= "            <td width=\"8%\">FECHA CARGO</td>";
          $this->salida .= "            <td width=\"7%\">CANT</td>";
          $this->salida .= "            <td width=\"10%\">VALOR CARGO</td>";
          $this->salida .= "            <td width=\"10%\">VAL. NO CUBIERTO</td>";
          $this->salida .= "            <td width=\"10%\">VAL. CUBIERTO</td>";
          $this->salida .= "            <td width=\"3%\"></td>";
          $this->salida .= "          </tr>";
          $car=$cub=$nocub=0;
          for($i=0; $i<sizeof($det);)
          {
              if($i % 2) {  $estilo="modulo_list_claro";  }
              else {  $estilo="modulo_list_oscuro";   }
              $this->salida .= "          <tr class=\"$estilo\">";
                            if(!empty($det[$i][codigo_agrupamiento_id]) AND !empty($det[$i][consecutivo]))
                            {
                                        $d=$i;
                                        $Cantidad=$valor=$cub=$nocub=0;
                                        while($det[$i][codigo_agrupamiento_id]==$det[$d][codigo_agrupamiento_id])
                                        {
                                            $Cantidad+=$det[$d][cantidad];
                                            $valor+=$det[$d][fac];
                                            $cub+=$det[$d][valor_cubierto];
                                            $nocub+=$det[$d][valor_nocubierto];
                                            $d++;
                                        }
                                        $des=$this->NombreCodigoAgrupamiento($det[$i][codigo_agrupamiento_id]);
                                        $this->salida .= "            <td align=\"center\">".$det[$i][tarifario_id]."</td>";
                                        $this->salida .= "            <td align=\"center\">".$det[$i][cargo]."</td>";
                                        $this->salida .= "            <td>".$des[descripcion]."</td>";
                                        $this->salida .= "            <td align=\"center\">".$this->FechaStamp($det[$i][fecha_cargo])."</td>";
                                        $this->salida .= "            <td align=\"center\">".FormatoValor($Cantidad)."</td>";
                                        $this->salida .= "            <td align=\"center\">".FormatoValor($valor)."</td>";
                                        $this->salida .= "            <td align=\"center\">".FormatoValor($nocub)."</td>";
                                        $this->salida .= "            <td align=\"center\">".FormatoValor($cub)."</td>";
                                        $this->salida .= "            <td><input type=\"checkbox\" value=\"".$det[$i][transaccion].",".$det[$i][codigo_agrupamiento_id].",".$det[$i][consecutivo]."\" name=\"Go".$det[$i][transaccion].$det[$i][codigo_agrupamiento_id]."\"></td>";
                                        $i=$d;
                            }//fin if
                            else
                            {
                                    $this->salida .= "            <td align=\"center\">".$det[$i][tarifario_id]."</td>";
                                    $this->salida .= "            <td align=\"center\">".$det[$i][cargo]."</td>";
                                    $this->salida .= "            <td>".$det[$i][descripcion]."</td>";
                                    $this->salida .= "            <td align=\"center\">".$this->FechaStamp($det[$i][fecha_cargo])."</td>";
                                    $this->salida .= "            <td align=\"center\">".FormatoValor($det[$i][cantidad])."</td>";
                                    $this->salida .= "            <td align=\"center\">".FormatoValor($det[$i][valor_cargo])."</td>";
                                    $this->salida .= "            <td align=\"center\">".FormatoValor($det[$i][valor_nocubierto])."</td>";
                                    $this->salida .= "            <td align=\"center\">".FormatoValor($det[$i][valor_cubierto])."</td>";
                                    $this->salida .= "            <td><input type=\"checkbox\" value=\"".$det[$i][transaccion].",".$det[$i][codigo_agrupamiento_id].",".$det[$i][consecutivo]."\" name=\"Go".$det[$i][transaccion].$det[$i][codigo_agrupamiento_id]."\"></td>";
                                    $i++;
                            }
              $this->salida .= "          </tr>";
              $car+=$det[$i][valor_cargo];
              $cub+=$det[$i][valor_cubierto];
              $nocub+=$det[$i][valor_nocubierto];
          }
          if(!empty($new))
          {
              if($i % 2) {  $estilo="modulo_list_claro";  }
              else {  $estilo="modulo_list_oscuro";   }
              $this->salida .= "          <tr class=\"$estilo\">";
              $this->salida .= "            <td colspan=\"5\" class=\"label\"  align=\"right\">TOTALES:  </td>";
              $this->salida .= "            <td align=\"center\" class=\"label\">".FormatoValor($car)."</td>";
              $this->salida .= "            <td align=\"center\" class=\"label\">".FormatoValor($nocub)."</td>";
              $this->salida .= "            <td align=\"center\" class=\"label\">".FormatoValor($cub)."</td>";

              $this->salida .= "            <td align=\"center\"><a href=\"javascript:Bajar(document.forma2);\"><img border=\"0\" src=\"".GetThemePath()."/images/arriba.png\"></a></td>";
              $this->salida .= "          </tr>";
          }
          $this->salida .= "     </table>";
          $this->salida .= "     <table border=\"0\" width=\"50%\" align=\"center\">";
          $this->salida .= "          <tr>";
          $this->salida .= "</form>";
          if(!empty($new))
          {
              $msg='Esta seguro que la Divisi?n de la Cuenta No. '.$Cuenta.' esta Correcta';
              $arreglo=array('PlanId'=>$PlanId,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Nivel'=>$Nivel,'Fecha'=>$Fecha,'vars'=>$vars);
              $accionEstado=ModuloGetURL('app','Facturacion','user','ConfirmarAccion',array('c'=>'app','m'=>'Facturacion','me2'=>'FormaListadoDivision','me'=>'FinalizarDivision','mensaje'=>$msg,'titulo'=>'DIVIDIR CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos,'arreglo'=>$arreglo,'boton1'=>'CONTINUAR','boton2'=>'CANCELAR'));
              $this->salida .= "    <form name=\"formabuscar\" action=\"$accionEstado\" method=\"post\">";
              $this->salida .= "      <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"TERMINAR DIVISION\"></td>";
              $this->salida .= "    </form>";
          }
          $accion=ModuloGetURL('app','Facturacion','user','BuscarDivision',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
          $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $this->salida .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER CRITERIOS\"></td>";
          $this->salida .= "</form>";
          $accion=ModuloGetURL('app','Facturacion','user','CancelarDivision',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
          $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $this->salida .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER AL DETALLE\"></td>";
          $this->salida .= "</form>";
          $this->salida .= "          </tr>";
          $this->salida .= "     </table>";
          $this->salida .= ThemeCerrarTabla();
          return true;
    }
*/
    /**
    *
    */
    function FormaAbonos($Cuenta)
    {
          $abono=$this->BuscarAbonos($Cuenta);
          $this->salida .= "   <br><table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
          $this->salida .= "          <tr class=\"modulo_table_list_title\">";
          $this->salida .= "            <td align=\"center\">ABONOS DE LA CUENTA ACTUAL</td>";
          $this->salida .= "          </tr>";
          if(!empty($abono[abonos]))
          {
                $this->salida .= "          <tr class=\"modulo_table_list_title\">";
                $this->salida .= "            <td>ABONOS DE LA CUENTA ACTUAL</td>";
                $this->salida .= "          </tr>";
                $this->salida .= "          <tr class=\"modulo_list_claro\">";
                $this->salida .= "            <td align=\"center\">ABONOS EFECTIVO: </td>";
                $this->salida .= "            <td align=\"center\">".FormatoValor($abono[abono_efectivo])."</td>";
                $this->salida .= "            <td align=\"center\" width=\"4%\"><input type=\"checkbox\" value=\"".$abono[abono_efectivo]."\" name=\"actual_abono_efectivo\"></td>";
                $this->salida .= "          </tr>";
          }
          $this->salida .= "     </table>";

    }

        function FormaCuentasDivision()
        {
                $this->salida .= ThemeAbrirTabla('CUENTAS - CUENTAS GENERADAS DE LA DIVISION DE LA CUENTA No. '.$_SESSION['DIVISION']['CUENTA'][0]['cuenta']);
                $this->salida .= "<table border=\"0\" width=\"50%\" align=\"center\" class=\"normal_10\">";
                $this->salida .= "   <tr>";
                $this->salida .= "          <td align=\"center\" class=\"label_mark\" colspan=\"2\">CUENTAS GENERADAS DESPUES DE LA DIVISION (Todas las cuentas estan inactivas)</td>";
                $this->salida .= "   </tr>";
                $this->salida .= "   <tr><td colspan=\"2\">&nbsp;</td></tr>";
                $this->salida .= "          <tr class=\"modulo_table_list_title\">";
                $this->salida .= "          <td align=\"center\" colspan=\"2\">ELIGA UNA CUENTA PARA SER ACTIVADA</td>";
                $this->salida .= "          </tr>";
                $accion=ModuloGetURL('app','Facturacion','user','ActivarCuentaDivision',array('Cuenta'=>$_REQUEST['Cuenta'],'TipoId'=>$_REQUEST['TipoId'],'PacienteId'=>$_REQUEST['PacienteId'],'Nivel'=>$_REQUEST['Nivel'],'PlanId'=>$_REQUEST['PlanId'],'Cama'=>$_REQUEST['Cama'],'Fecha'=>$_REQUEST['Fecha'],'Ingreso'=>$_REQUEST['Ingreso']));
                $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
                $this->salida .= "   <tr>";
                $this->salida .= "          <td width=\"92%\" class=\"label\">CUENTA INICIAL ".$_SESSION['DIVISION']['CUENTA'][0]['cuenta']."</td>";
                $this->salida .= "        <td width=\"8%\" align=\"center\"><input type=\"radio\" name=\"CuentaA\" value=\"".$_SESSION['DIVISION']['CUENTA'][0]['cuenta']."\"></td>";
                $this->salida .= "   </tr>";
                for($i=1; $i<sizeof($_SESSION['DIVISION']['CUENTA']); $i++)
                {
                        $this->salida .= "   <tr>";
                        $datPlan=$this->NombrePlan($_SESSION['DIVISION']['CUENTA'][$i]['plan']);
                        $this->salida .= "          <td class=\"label\">CUENTA GENERADA No. ".$_SESSION['DIVISION']['CUENTA'][$i]['cuenta']." - PLAN ".$datPlan['plan_descripcion']."</td>";
                        $this->salida .= "        <td align=\"center\"><input type=\"radio\" name=\"CuentaA\" value=\"".$_SESSION['DIVISION']['CUENTA'][$i]['cuenta']."\"></td>";
                        $this->salida .= "   </tr>";
                }
                $this->salida .= "</table>";
                $this->salida .= "<table border=\"0\" width=\"50%\" align=\"center\" class=\"normal_10\">";
                //unset($_SESSION['DIVISION']['CUENTA']);
                $this->salida .= "    <tr>";
                $this->salida .= "        <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACTIVAR CUENTA\"></td>";
                $this->salida .= "</form>";
                $accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$_REQUEST['Cuenta'],'TipoId'=>$_REQUEST['TipoId'],'PacienteId'=>$_REQUEST['PacienteId'],'Nivel'=>$_REQUEST['Nivel'],'PlanId'=>$_REQUEST['PlanId'],'Cama'=>$_REQUEST['Cama'],'Fecha'=>$_REQUEST['Fecha'],'Ingreso'=>$_REQUEST['Ingreso']));
                $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
                $this->salida .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER A LA CUENTA INICIAL\"></td>";
                $this->salida .= "</form>";
                $this->salida .= "          </tr>";
                $this->salida .= "</table>";
                $this->salida .= ThemeCerrarTabla();
                return true;
        }

        function FormaCuentaGenerada($plan,$descripcion,$Cuenta)
        {
                if(empty($Cuenta)){$Cuenta=$_REQUEST['Cuenta'];}
                $this->salida .= ThemeAbrirTabla('CUENTAS - CUENTAS GENERADAS HASTA AHORA DE LA DIVISION DE LA CUENTA No. '.$_SESSION['DIVISION']['CUENTA'][0]['cuenta']);
                $this->salida .= "<table border=\"0\" width=\"50%\" align=\"center\" class=\"normal_10\">";
                $this->salida .= "   <tr>";
                $this->salida .= "          <td align=\"center\" class=\"label_mark\">CUENTAS GENERADAS DESPUES DE LA DIVISION (estas cuentas estan inactivas)</td>";
                $this->salida .= "   </tr>";
                $this->salida .= "   <tr><td>&nbsp;</td></tr>";
                for($i=1; $i<sizeof($_SESSION['DIVISION']['CUENTA']); $i++)
                {
                        $this->salida .= "   <tr>";
                        $datPlan=$this->NombrePlan($_SESSION['DIVISION']['CUENTA'][$i]['plan']);
                        $this->salida .= "          <td class=\"label\">CUENTA GENERADA No. ".$_SESSION['DIVISION']['CUENTA'][$i]['cuenta']." - PLAN ".$datPlan['plan_descripcion']."</td>";
                        $this->salida .= "   </tr>";
                }
                $this->salida .= "    <tr>";
                $accion=ModuloGetURL('app','Facturacion','user','NuevoResponsable',array('Responsable'=>$plan,'descripcion_plan'=>$descripcion,'Cuenta'=>$_REQUEST['Cuenta'],"indice"=>$_REQUEST['indice'],'TipoId'=>$_REQUEST['TipoId'],'PacienteId'=>$_REQUEST['PacienteId'],'Nivel'=>$_REQUEST['Nivel'],'PlanId'=>$_REQUEST['PlanId'],'Cama'=>$_REQUEST['Cama'],'Fecha'=>$_REQUEST['Fecha'],'Ingreso'=>$_REQUEST['Ingreso']));
                $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
                $this->salida .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CONTINUAR\"></td>";
                $this->salida .= "</form>";
                $this->salida .= "          </tr>";
                $this->salida .= "</table>";
                $this->salida .= ThemeCerrarTabla();
                return true;
        }


    /**
    *
    */
    function FormaActivarCuentaDivision($PlanId,$Cuenta1,$Cuenta,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha)
    {
          $this->salida .= ThemeAbrirTabla('CUENTAS -  ACTIVAR CUENTA');
          $det=$this->DetalleTotal($Cuenta);
          $this->salida .= "      <table border=\"0\" width=\"70%\" align=\"center\" class=\"normal_10\">";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida .= "     </table>";
          $accion=ModuloGetURL('app','Facturacion','user','ActivarCuentaDivision',array('Cuenta'=>$Cuenta,'Cuenta1'=>$Cuenta1,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'vars'=>$vars,'abajo'=>true));
          $this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
          $this->salida .= "      <table border=\"0\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
          $this->salida .= "          <tr class=\"modulo_table_list_title\">";
          $this->salida .= "          <td align=\"center\" colspan=\"2\">ELIGA UNA CUENTA PARA SER ACTIVADA</td>";
          $this->salida .= "          </tr>";
          $this->salida .= "          <tr>";
          $this->salida .= "          <td align=\"center\" colspan=\"2\"><br><br></td>";
          $this->salida .= "          </tr>";
          $this->salida .= "          <tr>";
          $this->salida .= "          <td class=\"label\" align=\"right\" width=\"50%\">CUENTA No. $Cuenta1 (Inicial)</td>";
          $this->salida .= "          <td width=\"50%\"><input type=\"radio\" name=\"CuentaA\" value=\"$Cuenta1\"></td>";
          $this->salida .= "          </tr>";
          $this->salida .= "          <tr>";
          $this->salida .= "          <td class=\"label\" align=\"right\">CUENTA No. $Cuenta (Divisi?n)</td>";
          $this->salida .= "          <td><input type=\"radio\" name=\"CuentaA\" value=\"$Cuenta\"></td>";
          $this->salida .= "          </tr>";
          $this->salida .= "          <tr>";
          $this->salida .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"></td>";
          $this->salida .= "</form>";
          $accion=ModuloGetURL('app','Facturacion','user','main');
          $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $this->salida .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER A CUENTAS\"></td>";
          $this->salida .= "</form>";
          $this->salida .= "          </tr>";
          $this->salida .= "     </table>";
          $this->salida .= ThemeCerrarTabla();
          return true;
    }
//---------------------LA NUEVA FORMA CON CODIGO DE AGRUPAMIENTO-----------------------

  /**
  * Muestra el detalle de apoyos diagnsoticos de una cuenta.
  * @access private
  * @return boolean
  * @param int numero de la cuenta
  * @param string tipo documento
  * @param int numero documento
  * @param string nivel
  * @param string plan_id
  * @param int numero de la cama
  * @param date fecha de la cuenta
  * @param int ingreso
  * @param array arreglo con los datos de la cuenta
  * @param int numero de transaccion
  * @param int total del paciente
  * @param int total no cubierto
  * @param int total de la empresa
  * @param int valor total (cant. x precio)
  */
    function FormaDetalleCodigo($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Cama,$Fecha,$Ingreso,$var,$desc,$codigo,$documento,$numeracion,$Transaccion,$noFacturado)
    {
        global $VISTA;
        IncludeLib("tarifario");
                //IncludeLib("funciones_facturacion");
        $Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
        $Apellidos=$this->BuscarApellidosPaciente($TipoId,$PacienteId);
        $this->salida .= ThemeAbrirTabla('DETALLES DE LA CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos);
        $this->ConsultaAutorizacion();
        $this->EncabezadoEmpresa($Caja);
        $argu=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
        $this->Encabezado($PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$argu,$Cuenta);
        $this->TotalesCuenta($Cuenta);
        $this->salida .= "  </fieldset></td></tr></table><BR>";
        $this->salida .= " <table border=\"1\" cellspacing=\"2\" cellpadding=\"2\" width=\"98%\" align=\"center\">";
        $this->salida .= "    <tr class=\"modulo_table_title\">";
        $this->salida .= "        <td>DETALLE DE ".$desc."</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr>";
        $this->salida .= "        <td><br>";
        $this->salida .= " <table border=\"0\" cellpadding=\"2\" width=\"99%\" align=\"center\"  class=\"modulo_table_list\">";
        $this->salida .= "    <tr align=\"center\" class=\"modulo_table_list_title\">";
                if(empty($documento) AND empty($numeracion))
                {
                        $this->salida .= "        <td width=\"50%\">CARGO</td>";
                        $this->salida .= "        <td width=\"9%\">PRECIO.</td>";
                        $this->salida .= "        <td width=\"5%\">CANT.</td>";
                        $this->salida .= "        <td width=\"9%\">VALOR</td>";
                        $this->salida .= "        <td width=\"9%\">VAL. NO CUBIERTO</td>";
                        $this->salida .= "        <td width=\"9%\">VAL. CUBIERTO</td>";
                        $this->salida .= "        <td width=\"5%\">FIRMA</td>";
                }
                elseif(!empty($documento) AND !empty($numeracion))
                {
                        $this->salida .= "        <td width=\"10%\">CODIGO</td>";
                        $this->salida .= "        <td width=\"40%\">CARGO</td>";
                        $this->salida .= "        <td width=\"9%\">PRECIO.</td>";
                        $this->salida .= "        <td width=\"5%\">CANT.</td>";
                        $this->salida .= "        <td width=\"9%\">VALOR</td>";
                        $this->salida .= "        <td width=\"9%\">VAL. NO CUBIERTO</td>";
                        $this->salida .= "        <td width=\"9%\">VAL. CUBIERTO</td>";
                }
                if($_SESSION['CUENTAS']['SWCUENTAS']!='Cerradas')
                {  $this->salida .= "        <td colspan=\"2\" width=\"6%\">ACCION</td>";  }
                $this->salida .= "        <td width=\"2%\">INT</td>";
                $this->salida .= "        <td width=\"2%\">EXT</td>";
                $this->salida .= "        <td></td>";
        $this->salida .= "    </tr>";
        $ValTotal=$TotalNo=$TotalCub=0;
        for($i=0; $i<sizeof($var); $i++)
        {
            if( $i % 2) $estilo='modulo_list_claro';
            else $estilo='modulo_list_oscuro';
                        $ValTotal+=$var[$i][valor_cargo];
                        $TotalNo+=$var[$i][valor_nocubierto];
                        $TotalCub+=$var[$i][valor_cubierto];
            $this->salida .= "    <tr class=\"$estilo\">";
                        if(!empty($documento) AND !empty($numeracion))
                        {       $this->salida .= "        <td align=\"center\">".$var[$i][codigo_producto]."</td>";     }
            $this->salida .= "        <td>".$var[$i][descripcion]."</td>";
            $this->salida .= "        <td align=\"center\">".FormatoValor($var[$i][precio])."</td>";
            $this->salida .= "        <td align=\"center\">".FormatoValor($var[$i][cantidad])."</td>";
            $this->salida .= "        <td align=\"center\">".FormatoValor($var[$i][valor_cargo])."</td>";
            $this->salida .= "        <td align=\"center\">".FormatoValor($var[$i][valor_nocubierto])."</td>";
            $this->salida .= "        <td align=\"center\">".FormatoValor($var[$i][valor_cubierto])."</td>";
                        if(empty($documento) AND empty($numeracion))
                        {
                                $res=FirmaResultado($var[$i][transaccion]);
                                $img='';
                                //hay resultado
                                if($res==1)
                                {  $this->salida .= "        <td align=\"center\"><img src=\"".GetThemePath()."/images/checksi.png\"></td>";  }
                                else
                                {  $this->salida .= "      <td></td>";  }
                        }
            if($_SESSION['CUENTAS']['SWCUENTAS']!='Cerradas')
            {
                $accionM=ModuloGetURL('app','Facturacion','user','LlamaFormaModificar',array('Transaccion'=>$var[$i][transaccion],'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Datos'=>$var[$i],'des'=>$desc,'codigo'=>$codigo,'doc'=>$documento,'numeracion'=>$numeracion,'Transaccion'=>$Transaccion,'noFacturado'=>$noFacturado));
                $this->salida .= "        <td><a href=\"$accionM\">MODI</a></td>";
                /*$mensaje='Esta seguro que desea eliminar este cargo.';
                $arreglo=array('Transaccion'=>$var[$i][transaccion],'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Cama'=>$Cama,'Fecha'=>$Fecha,'des'=>$desc,'codigo'=>$codigo,'doc'=>$documento,'numeracion'=>$numeracion);
                $accionE=ModuloGetURL('app','Facturacion','user','ConfirmarAccion',array('c'=>'app','m'=>'Facturacion','me2'=>'DefinirForma','me'=>'EliminarCargo','mensaje'=>$mensaje,'titulo'=>'ELIMINAR CARGO DE LA CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos,'arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
                                */
                                $accionE=ModuloGetURL('app','Facturacion','user','LlamarFormaEliminarCargo',array('Transaccion'=>$var[$i][transaccion],'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Datos'=>$var[$i],'des'=>$desc,'codigo'=>$codigo,'doc'=>$documento,'numeracion'=>$numeracion,'Transaccion'=>$Transaccion,'noFacturado'=>$noFacturado));
                                if(empty($documento) AND empty($numeracion))
                {  $this->salida .= "        <td><a href=\"$accionE\">ELIM</a></td>";  }
                                    else
                {  $this->salida .= "        <td></td>";  }
            }
                        $imagenInt=$imagenExt='';
                        if($var[$i][autorizacion_int]==='0')
                        {  $imagenInt="no_autorizado.png";   $D=1; }
                        elseif($var[$i][autorizacion_int] >100)
                        {  $imagenInt="autorizado.png";   $D=0; }
                        elseif($var[$i][autorizacion_int] ==1)
                        {  $imagenInt="autorizadosiis.png";   $D=1; }

                        if($var[$i][autorizacion_ext]==='0')
                        {  $imagenExt="no_autorizado.png";   $n=1; }
                        elseif($var[$i][autorizacion_ext] >100)
                        {  $imagenExt="autorizado.png";   $n=0; }
                        elseif($var[$i][autorizacion_ext] ==1)
                        {  $imagenExt="autorizadosiis.png";   $n=1; }
                        $this->salida .= "       <td>";
                        if(!empty($imagenInt))
                        {  $this->salida .= "<img src=\"".GetThemePath()."/images/$imagenInt\">";  }
                        $this->salida .= "</td>";
                        $this->salida .= "       <td>";
                        if(!empty($imagenExt))
                        {  $this->salida .= "<img src=\"".GetThemePath()."/images/$imagenExt\">";  }
                        $this->salida .= "</td>";
                        if($imagenInt=="autorizado.png")
                        {  $this->salida .= "       <td><a href=\"javascript:ConsultaAutorizacion('DATOS DE LA AUTORIZACION','reports/$VISTA/datosautorizacioncargo.php',1000,250,'".$var[$i][tarifario_id]."','".$var[$i][cargo]."',$Cuenta,".$var[$i][autorizacion_interna].",1,'Int')\"><img src=\"".GetThemePath()."/images/informacion.png\" border=\"0\"></a></td>";  }
                        else
                        { $this->salida .= "        <td></td>";}
                        $this->salida .= "    </tr>";
        }
        if( $i % 2) $estilo='modulo_list_claro';
        else $estilo='modulo_list_oscuro';
        $this->salida .= "    <tr class=\"$estilo\" align=\"center\">";
                if(!empty($documento) AND !empty($numeracion))
        {  $this->salida .= "        <td colspan=\"4\"><b>TOTALES: </b></td>";  }
        else
                {  $this->salida .= "        <td colspan=\"3\"><b>TOTALES: </b></td>";  }
        $this->salida .= "        <td><b>".FormatoValor($ValTotal)."</b></td>";
        $this->salida .= "        <td><b>".FormatoValor($TotalNo)."</b></td>";
        $this->salida .= "        <td><b>".FormatoValor($TotalCub)."</b></td>";
        if($_SESSION['CUENTAS']['SWCUENTAS']!='Cerradas')
        {  $col=6; }
        else {  $col=1; }
        $this->salida .= "        <td colspan=\"$col\"></td>";
        $this->salida .= "    </tr>";
        $this->salida .= "  </table>";
                //ARRANQUE CALI LORENA
                if(!empty($documento) AND !empty($numeracion) && $desc!='DEVOLUCION DE MEDICAMENTOS'){
          $bodega=$this->BodegaDocumento($documento);
          $permiso=$this->ConfirmacionPermisoDevolucuionUsuario($bodega['bodega']);
          $this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" class=\"normal_10N\" width=\"98%\" align=\"center\">";
          $this->salida .= "    <tr>";
          if($permiso==1){
                     $accion=ModuloGetURL('app','Facturacion','user','LlamaFormaDevolverMedicamentos',array("Cuenta"=>$Cuenta,"TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"Nivel"=>$Nivel,"PlanId"=>$PlanId,"Cama"=>$Cama,"Fecha"=>$Fecha,"Ingreso"=>$Ingreso,"desc"=>$desc,"codigo"=>$codigo,"documento"=>$documento,"numeracion"=>$numeracion,"Transaccion"=>$Transaccion,"noFacturado"=>$noFacturado));
                     $this->salida .= "        <td align=\"right\"><a href=\"$accion\" class=\"link\">REALIZAR DEVOLUCION</a><br><label class=\"label_mark\">BOD: ".$bodega['descripcion']."</label></td>";
          }else{
            $this->salida .= "        <td align=\"right\" class=\"label_mark\">EL USUARIO NO TIENE PERMISOS PARA REALIZAR DEVOLUCIONES EN LA BODEGA ".$bodega['descripcion']."</td>";
          }
          $this->salida .= "    </tr>";
          $this->salida .= "  </table>";
                }
        //FIN ARRANQUE
                $this->salida .= "  </BR>";


        $this->salida .= "        </td>";
        $this->salida .= "    </tr>";
        $this->salida .= "  </table><br>";
        $accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER AL DETALLE\"></p>";
        $this->salida .= "</form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

        //ARRANQUE CALI LORENA
        function FormaDevolverMedicamentos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Cama,$Fecha,$Ingreso,$desc,$codigo,$documento,$numeracion,$Transaccion,$noFacturado)
    {
                //unset($_SESSION['FACTURACION_CUENTAS']);
                //print_R($_SESSION['FACTURACION_CUENTAS']['FECHAS_VENCE']);
        //global $VISTA;
        //IncludeLib("tarifario");
                //IncludeLib("funciones_facturacion");
                $this->salida .= "<script>";
                $this->salida .= "function VentanaFechaVence(codigoProducto,descripcionProducto,frm,cont,cantidadRestante){";
                $this->salida .= "valor = frm.cantidadDevoluciones[cont].value;";
                $this->salida .= "if( valor == -1) valor = frm.cantidadDevoluciones.selectedIndex; ";
                $this->salida .= "if(valor==-1){";
                $this->salida .= "alert('Seleccione la Cantidad que va a Devolver');";
                //$this->salida .= "return true;";
                $this->salida .= "}else{";
                //$this->salida .= "alert(cantidadRestante);";
                $this->salida .= "document.getElementById('codigoImprime').innerHTML=codigoProducto; ";
                $this->salida .= "document.getElementById('descripcionImprime').innerHTML=descripcionProducto; ";
                $this->salida .= "document.getElementById('cantidadRest').innerHTML=(valor-cantidadRestante);";
                $this->salida .= "document.getElementById('codigoProducto').value=codigoProducto;";
                $this->salida .= "document.getElementById('cantidadR').value=(valor-cantidadRestante);";
                $this->salida .= "document.getElementById('recarga').style.visibility='visible'; ";
                $this->salida .= "}";
                $this->salida .= "}";
                $this->salida .= "function ValidaSolicitud(frm){";
                $this->salida .= "  if(frm.fechaVencimiento.value=='' || frm.lote.value=='' || frm.cantidad.value==''){";
                $this->salida .= "      alert('Todos Los Campos son Obligatorios');";
                $this->salida .= "      return false;";
                $this->salida .= "  }";
                $this->salida .= "  if(frm.cantidad.value > frm.cantidadR.value){";
                $this->salida .= "      alert('La Cantidad del Lote no puede ser mayor a la Cantidad Restante Insertada');";
                $this->salida .= "      return false;";
                $this->salida .= "  }";
                $this->salida .= "  frm.submit();";
                //$this->salida .= "return true;";
                $this->salida .= "}";
                $this->salida .= "</script>";
        $Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
        $Apellidos=$this->BuscarApellidosPaciente($TipoId,$PacienteId);
        $this->salida .= ThemeAbrirTabla('DETALLES DE LA CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos);

                $action=ModuloGetURL('app','Facturacion','user','InsertarFechaVencimientoLote',array("Cuenta"=>$Cuenta,"TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"Nivel"=>$Nivel,"PlanId"=>$PlanId,"Cama"=>$Cama,"Fecha"=>$Fecha,"Ingreso"=>$Ingreso,"desc"=>$desc,"codigo"=>$codigo,"documento"=>$documento,"numeracion"=>$numeracion,"Transaccion"=>$Transaccion,"noFacturado"=>$noFacturado));
                $this->salida .= "       <form name=\"formaUno\" action=\"$action\" method=\"post\">\n";

        //$this->ConsultaAutorizacion();
        $this->EncabezadoEmpresa($Caja);
        $argu=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
        $this->Encabezado($PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$argu,$Cuenta);
        $this->TotalesCuenta($Cuenta);
        $this->salida .= "  </fieldset></td></tr></table><BR>";
                $this->salida .= "     <table border=\"0\" width=\"70%\" align=\"center\" class=\"normal_10\">";
                $this->salida .= $this->SetStyle("MensajeError");
                $this->salida .= "     </table>";
                $var=$this->MedicamentosDocumentoBodega($Cuenta,$codigo,$noFacturado);
                $motivos=$this->MotivosDevolucionIyM();
                if($var){
                    $this->salida .= " <table border=\"1\" cellspacing=\"2\" cellpadding=\"2\" width=\"98%\" align=\"center\">";
                    $this->salida .= "    <tr class=\"modulo_table_title\">";
                    $this->salida .= "        <td>DETALLE DE ".$desc."</td>";
                    $this->salida .= "    </tr>";
                    $this->salida .= "    <tr>";
                    $this->salida .= "    <td><br>";
                    $this->salida .= "      <table border=\"0\" cellpadding=\"2\" width=\"99%\" align=\"center\"  class=\"modulo_table_list\">";
                    $this->salida .= "    <tr align=\"center\" class=\"modulo_table_list_title\">";
                    $this->salida .= "        <td width=\"10%\">CODIGO</td>";
                    $this->salida .= "        <td>CARGO</td>";
                    $this->salida .= "        <td width=\"5%\">CANT.</td>";
                    $this->salida .= "        <td width=\"20%\">BODEGA</td>";
                    $this->salida .= "        <td width=\"10%\">DEVOLUCION</td>";
                    $this->salida .= "        <td width=\"20%\">&nbsp;</td>";
                    $this->salida .= "        <td width=\"20%\">&nbsp;</td>";
                    $this->salida .= "              <input type=\"hidden\" name=\"BodegasProd\" value=\"".$var[0][bodega]."\">";
                    $this->salida .= "              <input type=\"hidden\" name=\"Departamento\" value=\"".$var[0][departamento]."\">";
                    $cantidadesVect=$_REQUEST['cantidadDevol'];
                    $MotivosDevolucion=$_REQUEST['MotivosDevolucion'];
                    for($i=0; $i<sizeof($var); $i++){
                        if($i % 2){$estilo='modulo_list_claro';$estilo1='modulo_list_oscuro';}
                        else{$estilo='modulo_list_oscuro';$estilo1='modulo_list_claro';}
                        $this->salida .= "    <tr class=\"$estilo\">";
                        $this->salida .= "              <input type=\"hidden\" name=\"Consecutivos[".$var[$i][codigo_producto]."]\" value=\"".$var[$i][transaccion]."\">";
                        if($var[$i][sw_control_fecha_vencimiento]==1){
                            $this->salida .= "              <input type=\"hidden\" name=\"RequiereFechas[".$var[$i][codigo_producto]."]\" value=\"1\">";
                        }
                        $this->salida .= "        <td align=\"center\">".$var[$i][codigo_producto]."</td>";
                        $this->salida .= "        <td>".$var[$i][descripcion]."</td>";
                        $this->salida .= "        <td>".$var[$i][cant_cargada]."</td>";
                        $this->salida .= "        <td>".$var[$i][nom_bodega]."</td>";
                        $this->salida .= "        <td align=\"center\">";
                        $this->salida .= "          <select size=\"1\" name=\"cantidadDevol[".$var[$i][codigo_producto]."]\" class=\"select\" id=\"cantidadDevoluciones\">";
                        $this->salida.="            <option value = -1>-Cantidad-</option>";
                        for($cont=1;$cont<=$var[$i][cantidad];$cont++){
                            if($_SESSION['FACTURACION_CUENTAS']['PRODUCTOS_IYM_CANTIDADES_DEV'][$var[$i][codigo_producto]]==$cont || $cantidadesVect[$var[$i][codigo_producto]]==$cont){
                                $this->salida.="            <option value = $cont selected>$cont</option>";
                            }else{
                                $this->salida.="            <option value = $cont>$cont</option>";
                            }
                        }
                        $this->salida .= "          </select>";
                        $this->salida .= "        </td>";
                        $this->salida .= "        <td align=\"center\">";
                        if($var[$i][sw_control_fecha_vencimiento]==1){
                            $this->salida .= "      <table border=\"0\" cellpadding=\"2\" width=\"100%\" align=\"center\">";
                            $this->salida .= "      <tr>";
                            $sumaCantLotes=0;
                            foreach($_SESSION['FACTURACION_CUENTAS']['FECHAS_VENCE'][$var[$i][codigo_producto]] as  $lote=>$arreglo){
                                (list($cantidades,$fecha)=explode('||//',$arreglo));
                                $sumaCantLotes+=$cantidades;
                            }
                            $cantidadRes=$_SESSION['FACTURACION_CUENTAS']['PRODUCTOS_IYM_CANTIDADES_DEV'][$var[$i][codigo_producto]]-$sumaCantLotes;
                            if(empty($_SESSION['FACTURACION_CUENTAS']['PRODUCTOS_IYM_CANTIDADES_DEV'][$var[$i][codigo_producto]]) || $sumaCantLotes < $_SESSION['FACTURACION_CUENTAS']['PRODUCTOS_IYM_CANTIDADES_DEV'][$var[$i][codigo_producto]]){
                                $this->salida .= "      <td align=\"center\"><a href=\"javascript:VentanaFechaVence('".$var[$i][codigo_producto]."','".$var[$i][descripcion]."',document.formaUno,$i,$sumaCantLotes)\"><img border=\"0\" src=\"".GetThemePath()."/images/pparamed.png\" title=\"Insertar Fechas Vencimiento\"></a></td>";
                            }

                            if($_SESSION['FACTURACION_CUENTAS']['FECHAS_VENCE'][$var[$i][codigo_producto]]){
                                $this->salida .= "    <td>";
                                $this->salida .= "        <table width=\"100%\" border=\"0\" align=\"center\">";
                                $this->salida .= "        <tr class=\"modulo_table_title\">";
                                $this->salida .= "        <td>LOTE</td>";
                                $this->salida .= "        <td>CANT.</td>";
                                $this->salida .= "        <td>FECHA</td>";
                                $this->salida .= "        <td>&nbsp;</td>";
                                $this->salida .= "        </tr>";
                                foreach($_SESSION['FACTURACION_CUENTAS']['FECHAS_VENCE'][$var[$i][codigo_producto]] as $lote=>$valor){
                                    (list($cantidades,$fecha)=explode('||//',$valor));
                                    $this->salida .= "        <tr class=\"$estilo1\">";
                                    $this->salida .= "        <td>$lote</td>";
                                    $this->salida .= "        <td>$cantidades</td>";
                                    $this->salida .= "        <td>$fecha</td>";
                                    $actionEliminaFV=ModuloGetURL('app','Facturacion','user','EliminarFechaVencimientos',array("codigoProducto"=>$var[$i][codigo_producto],"lote"=>$lote,"Cuenta"=>$Cuenta,"TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"Nivel"=>$Nivel,"PlanId"=>$PlanId,"Cama"=>$Cama,"Fecha"=>$Fecha,"Ingreso"=>$Ingreso,"desc"=>$desc,"codigo"=>$codigo,"documento"=>$documento,"numeracion"=>$numeracion,"Transaccion"=>$Transaccion,"noFacturado"=>$noFacturado));
                                    $this->salida .= "        <td><a href=\"$actionEliminaFV\"><img title=\"Eliminar Lote\" border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
                                    $this->salida .= "       </tr>";
                                }
                                $this->salida .= "       </table>";
                                $this->salida .= "   </td>";
                            }
                            $this->salida .= "      </tr>";
                            $this->salida .= "      </table>";
                        }else{
                            $this->salida .= "        &nbsp;";
                        }
                        $this->salida .= "        </td>";
                        $this->salida .= "    <td>";
                        $this->salida .= "          <select size=\"1\" name=\"MotivosDevolucion[".$var[$i][codigo_producto]."]\" class=\"select\">";
                        $this->salida.="            <option value = -1>-Motivo Devolucion-</option>";
                        for($m=0;$m<sizeof($motivos);$m++){
                            if($_SESSION['FACTURACION_CUENTAS']['motivosDevolucion'][$var[$i][codigo_producto]]==$motivos[$m][motivo_devolucion_id] || $MotivosDevolucion[$var[$i][codigo_producto]]==$motivos[$m][motivo_devolucion_id]){
                                $this->salida.="            <option value = \"".$motivos[$m][motivo_devolucion_id]."\" selected>".$motivos[$m][descripcion]."</option>";
                            }else{
                                $this->salida.="            <option value = \"".$motivos[$m][motivo_devolucion_id]."\">".$motivos[$m][descripcion]."</option>";
                            }
                        }
                        $this->salida .= "          </select>";
                        $this->salida .= "    </td>";
                        $this->salida .= "    </tr>";

                    }
                    $this->salida .= " </table>";
                    $this->salida .= " </td></tr></table>";

                    $this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"99%\" align=\"center\">";
                    $this->salida .= " <tr><td align=\"right\"><input type=\"submit\" class=\"input-submit\" name=\"Devolver\" value=\"DESCARGO DE LA CUENTA\"></td></tr>";
                    $this->salida .= " </table>";
                    $this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"99%\" align=\"center\">";
                    $this->salida .= " <tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"volver\" value=\"VOLVER\"></td></tr>";
                    $this->salida .= " </table>";

                    $this->salida  .=" <div align='center' id=\"recarga\"  style=\"visibility:hidden\">";
                    $this->salida .= "                <input type=\"hidden\" name=\"codigoProducto\" id=\"codigoProducto\">";
                    $this->salida .= "                <input type=\"hidden\" name=\"cantidadR\" id=\"cantidadR\">";
                    $this->salida .= "          <table class=\"normal_10\" border=\"0\" width=\"100%\" align=\"center\">\n";
                    $this->salida .= "          <tr><td width=\"100%\">\n";
                    $this->salida .= "          <fieldset><legend class=\"field\">DATOS DEL PRODUCTO</legend>\n";
                    $this->salida .= "          <table cellspacing=\"1\" cellpadding=\"1\"border=\"0\" width=\"100%\" align=\"center\">\n";
                    $this->salida .= "          <tr><td></td></tr>\n";
                    $this->salida .= "         <tr class=\"modulo_table_title\" align=\"center\"><td align=\"center\" colspan=\"6\"><table><tr class=\"modulo_table_title\"><td><div id=\"codigoImprime\"></div></td><td><div id=\"descripcionImprime\"></div></td></tr></table></td></tr>\n";
                    $this->salida .= "         <tr class=\"modulo_table_title\" align=\"center\"><td align=\"center\" colspan=\"6\"><table><tr class=\"modulo_table_title\"><td>CANTIDAD QUE FALTA POR INSERTAR</td><td><div id=\"cantidadRest\"></div></td></tr></table></td></tr>\n";
                    $this->salida .= "          <tr class=\"modulo_list_claro\">\n";
                    $this->salida .= "          <td class=\"label\">FECHA VENCIMIENTO</td>\n";
                    $this->salida .= "          <td align=\"center\"><input type=\"text\" name=\"fechaVencimiento\" value=\"".$_REQUEST['fechaVencimiento']."\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\">\n";
                    $this->salida .= "          ".ReturnOpenCalendario('formaUno','fechaVencimiento','/')."</td>";
                    $this->salida .= "          <td class=\"label\">No. LOTE</td>\n";
                    $this->salida .= "          <td align=\"center\"><input class=\"input-text\" type=\"text\" name=\"lote\" value=\"".$_REQUEST['lote']."\"></td>\n";
                    $this->salida .= "          <td class=\"label\">CANTIDAD</td>\n";
                    $this->salida .= "          <td align=\"center\"><input class=\"input-text\" type=\"text\" name=\"cantidad\" value=\"".$_REQUEST['cantidad']."\"></td>\n";
                    $this-> salida .= "         <tr><td></td></tr>\n";
                    $this->salida .= "          <tr><td colspan=\"6\" align=\"center\">\n";//<input type=\"submit\" class=\"input-submit\" name=\"cancelar\" value=\"CANCELAR\">
                    $this->salida .= "          <input type=\"button\" class=\"input-submit\" name=\"insertar\" value=\"INSERTAR\" onclick=\"ValidaSolicitud(this.form)\"></td></tr>\n";
                    $this->salida .= "               </table>\n";
                    $this->salida .= "            </fieldset></td>\n";
                    $this->salida .= "         </table>\n";
                    $this->salida  .=" </div>";
                }else{
                    $this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"99%\" align=\"center\">";
                    $this->salida .= " <tr><td align=\"center\" class=\"label_error\">SE REALIZARON TODAS LAS DEVOLUCIONES DE LOS PRODUCTOS SOLICITADOS</td></tr>";
                    $this->salida .= " <tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"volver\" value=\"VOLVER\"></td></tr>";
                    $this->salida .= " </table>";

                }
                $this->salida .="  </form>\n";
                $this->salida .= ThemeCerrarTabla();
        return true;
            }

        //FIN ARRANQUE
        /**
        *
        */
        function FormaVariasEquivalencias($Departamento,$Servicio,$CargoCups,$nombre,$equi,$TipoId,$PacienteId,$Cuenta,$Nivel,$PlanId,$Fecha,$Ingreso,$cantidad,$FechaCargo,$profesional)
        {
                $Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
                $Apellidos=$this->BuscarApellidosPaciente($TipoId,$PacienteId);
                $this->salida .= ThemeAbrirTabla('AGREGAR CARGOS A LA CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos);
                $this->EncabezadoEmpresa($Caja);
                $argu=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
                $this->Encabezado($PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$argu,$Cuenta);
                //mensaje
                $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
                $this->salida .= $this->SetStyle("MensajeError");
                $this->salida .= "  </table>";
                $this->salida .= "     <br><table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
                $this->salida .= "          <tr><td colspan=\"5\">EL CARGO CUPS ($CargoCups) $nombre TIENE VARIAS EQUIVALENCIAS:</td></tr>";
                $this->salida .= "          <tr><td colspan=\"5\">&nbsp;</td></tr>";
                $this->salida .= "      <tr class=\"modulo_table_list_title\">";
                $this->salida .= "        <td>TARIFARIO</td>";
                $this->salida .= "        <td>CARGO</td>";
                $this->salida .= "        <td>DESCRIPCION</td>";
                $this->salida .= "        <td>PRECIO</td>";
                $this->salida .= "        <td></td>";
                $this->salida .= "      </tr>";
                //cambio lorena porque se cae el programa cuando mandaban este vector por request
                $_SESSION['FACTURACION']['VECTOR_EQUIVALENCIAS']=$equi;
                //fin cambio
                $accion=ModuloGetURL('app','Facturacion','user','InsertarCargoTmpEquivalencias',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'cups'=>$CargoCups,'descripcion'=>$nombre,'departamento'=>$Departamento,'servicio'=>$Servicio,'cantidad'=>$cantidad,'fechacar'=>$FechaCargo,'profesional'=>$profesional));
                $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
                for($i=0; $i<sizeof($equi); $i++)
                {
                        if( $i % 2) $estilo='modulo_list_oscuro';
                        else $estilo='modulo_list_claro';
                        $this->salida .= "     <tr class=\"$estilo\">";
                        $this->salida .= "        <td align=\"center\">".$equi[$i][tarifario_id]."</td>";
                        $this->salida .= "        <td align=\"center\">".$equi[$i][cargo]."</td>";
                        $this->salida .= "        <td>".$equi[$i][descripcion]."</td>";
                        $this->salida .= "        <td align=\"center\">".FormatoValor($equi[$i][precio])."</td>";
                        $this->salida .= "        <td align=\"center\"><input type = checkbox name= cargo".$equi[$i][tarifario_id]."".$equi[$i][cargo]." value=\"".$equi[$i][tarifario_id]."||".$equi[$i][cargo]."||".$equi[$i][descripcion]."||".$CargoCups."\"></td>";
                        $this->salida .= "      </tr>";
                }
                $this->salida .= "     </table>";
                $this->salida .= "     <table border=\"0\" width=\"50%\" align=\"center\">";
                $this->salida .= "          <tr>";
                $this->salida .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"></td>";
                $this->salida .= "</form>";
                $accion=ModuloGetURL('app','Facturacion','user','Cargos',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
                $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
                $this->salida .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></td>";
                $this->salida .= "</form>";
                $this->salida .= "          </tr>";
                $this->salida .= "     </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
        }

//---------------------CIRUGIA-------------------
    function FormaDetalleCirugia($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Cama,$Fecha,$Ingreso,$var,$med,$desc,$codigo,$qx)
    {
            $Nombres=$this->BuscarNombreCompletoPaciente($TipoId,$PacienteId);
            $this->salida .= ThemeAbrirTabla('DETALLES DE LA CUENTA No. '.$Cuenta.' '.$Nombres);
            $this->ConsultaAutorizacion();
            $this->EncabezadoEmpresa($Caja);
            $argu=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
            $this->Encabezado($PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$argu,$Cuenta);
            $this->TotalesCuenta($Cuenta);
            $this->salida .= "  </fieldset></td></tr></table><BR>";
            IncludeLib('funciones_facturacion');
            $vector = $this->DatosCirugia($_REQUEST['qx'],$Cuenta);
            $var=$vector[0];
      $this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
      $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"4\">CARGOS DEL ACTO QUIRURGICO No. ".$_REQUEST['qx']."</td></tr>";
      $this->salida .= "    <tr class=\"modulo_list_oscuro\">";
      $this->salida .= "    <td width=\"10%\" class=\"label\">ANESTESIOLOGO</td>";
      $nombreTercero=$this->BuscarTercero($var[1][1]['liquidacion']['DA']['tipo_id_tercero'],$var[1][1]['liquidacion']['DA']['tercero_id']);
      $this->salida .= "    <td width=\"40%\">".$nombreTercero[0]."</td>";
      $this->salida .= "    <td width=\"10%\" class=\"label\">AYUDANTE</td>";
      $nombreTercero=$this->BuscarTercero($var[1][1]['liquidacion']['DY']['tipo_id_tercero'],$var[1][1]['liquidacion']['DY']['tercero_id']);
      $this->salida .= "    <td width=\"40%\">".$nombreTercero[0]."</td>";
      $this->salida .= "    </tr>";
      foreach($var as $indiceCirujano=>$Vector)
            {
                    $this->salida .= "        <tr class=\"modulo_table_title\">";
                    $this->salida .= "         <td width=\"10%\">CIRUJANO ".$indiceCirujano."</td>";
                    $nombreTercero=$this->BuscarTercero($Vector[1]['tipo_id_cirujano'],$Vector[1]['cirujano_id']);
                    $this->salida .= "         <td colspan=\"3\">".$nombreTercero[0]."</td>";
                    $this->salida .= "       </tr>";
                    foreach($Vector as $indiceProcedimiento=>$DatosQX)
                    {
                            $this->salida .= "    <tr class=\"modulo_list_oscuro\">";
                            $this->salida .= "      <td colspan=\"4\">";
                            $this->salida .= "       <table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
                            $cups=NombreCargoCups($DatosQX['cargo_cups']);
                            $this->salida .= "       <tr class=\"modulo_list_claro\">";
                            $this->salida .= "        <td  width=\"10%\" class=\"label\">CARGO CUPS</td>";
                            $this->salida .= "        <td colspan=\"4\">".$DatosQX['cargo_cups']." - ".$cups."</td>";
                            $this->salida .= "       </tr>";
                            $tarifario=NombreTarifario($DatosQX['tarifario_id']);
                            $this->salida .= "       <tr class=\"modulo_list_claro\">";
                            $this->salida .= "        <td  width=\"10%\" class=\"label\">EQUIVALENCIA</td>";
                            $this->salida .= "        <td colspan=\"4\">".$tarifario." ".$DatosQX['cargo']." - ".$DatosQX['descripcion']."</td>";
                            $this->salida .= "       </tr>";
                            $this->salida .= "          <tr class=\"modulo_table_list_title\">";
                            $this->salida .= "          <td width=\"10%\">".$indiceProcedimiento."</td>";
                            $this->salida .= "          <td width=\"20%\">CARGO</td>";
                            $this->salida .= "          <td width=\"10%\">%</td>";
                            $this->salida .= "          <td width=\"30%\">VALOR CUBIERTO</td>";
                            $this->salida .= "          <td>VALOR NO CUBIERTO</td>";
                            $this->salida .= "          </tr>";
                            foreach($DatosQX['liquidacion'] as $derecho=>$DatosDerecho)
                            {
                                    $this->salida .= "        <tr class=\"modulo_list_claro\">";
                                    $this->salida .= "        <td class=\"label\">$derecho</td>";
                                    $tarifario=NombreTarifario($DatosDerecho['tarifario_id']);
                                    $this->salida .= "        <td>".$tarifario." - ".$DatosDerecho['cargo']."</td>";
                                    $this->salida .= "        <td align=\"right\">".$DatosDerecho['PORCENTAJE']."</td>";
                                    $this->salida .= "        <td align=\"right\">".FormatoValor($DatosDerecho['valor_cubierto'])."</td>";
                                    $this->salida .= "        <td align=\"right\">".FormatoValor($DatosDerecho['valor_no_cubierto'])."</td>";
                                    $this->salida .= "        </tr>";
                            }
                            $this->salida .= "       </table>";
                            $this->salida .= "      </td>";
                            $this->salida .= "    </tr>";
         }
      }
      $this->salida .= "    </table>";
            $DatosQXEquipos=$vector[1];
            if($DatosQXEquipos){
                $this->salida .= "    <BR><table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
                $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"4\">CARGOS DE EQUIPOS DEL ACTO QUIRURGICO No. ".$NoLiquidacion."</td></tr>";
                for($i=0;$i<sizeof($DatosQXEquipos);$i++){
                    $this->salida .= "    <tr class=\"modulo_list_oscuro\">";
                    $this->salida .= "      <td colspan=\"4\">";
                    $this->salida .= "       <table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
                    $this->salida .= "       <tr class=\"modulo_list_claro\">";
                    $this->salida .= "        <td  width=\"10%\" class=\"label\">EQUIPO</td>";
                    $this->salida .= "        <td colspan=\"4\">".$DatosQXEquipos[$i]['descripcion_equipo']."&nbsp&nbsp&nbsp;<label class=\"label\">DURACION:&nbsp&nbsp&nbsp;</label>".$DatosQXEquipos[$i]['duracion']."</td>";
                    $this->salida .= "       </tr>";
                    $descripciones=$this->DescripcionCargosTarifario($DatosQXEquipos[$i]['tarifario_id']);
                    $this->salida .= "       <tr class=\"modulo_list_claro\">";
                    $this->salida .= "        <td  width=\"10%\" class=\"label\">EQUIVALENCIA</td>";
                    $this->salida .= "        <td colspan=\"4\">".$descripciones['tarifario']." ".$DatosQXEquipos[$i]['cargo']." - ".$DatosQXEquipos[$i]['descripcion']."</td>";
                    $this->salida .= "       </tr>";
                    $this->salida .= "          <tr class=\"modulo_table_list_title\">";
                    $this->salida .= "          <td width=\"10%\">TIPO EQUIPO</td>";
                    $this->salida .= "          <td width=\"10%\">CANTIDAD</td>";
                    $this->salida .= "          <td width=\"30%\">VALOR CUBIERTO</td>";
                    $this->salida .= "          <td width=\"30%\">VALOR NO CUBIERTO</td>";
                    $this->salida .= "          <td width=\"10%\">FACTURADO</td>";
                    $this->salida .= "          </tr>";
                    $this->salida .= "        <tr class=\"modulo_list_claro\">";
                    if($DatosQXEquipos[$i]['tipo_equipo']=='fijo'){
                        $this->salida .= "        <td align=\"center\">FIJO</td>";
                    }else{
                        $this->salida .= "        <td align=\"center\">MOVIL</td>";
                    }
                    $this->salida .= "        <td>".$DatosQXEquipos[$i]['cantidad']."</td>";
                    if($valoresManual==1){
                        $this->salida .= "        <td align=\"center\"><input align=\"right\" type=\"text\" class=\"input-text\" size=\"8\" name=\"valoresCubiertosEquipos[$i]\" value=\"".FormatoValor($DatosQXEquipos[$i]['valor_cubierto'])."\"></td>";
                    }else{
                        $this->salida .= "        <td align=\"right\">".FormatoValor($DatosQXEquipos[$i]['valor_cubierto'])."</td>";
                    }
                    if($valoresManual==1){
                        $this->salida .= "        <td align=\"center\"><input align=\"right\" type=\"text\" class=\"input-text\" size=\"8\" name=\"valoresNoCubiertosEquipos[$i]\" value=\"".FormatoValor($DatosQXEquipos[$i]['valor_no_cubierto'])."\"></td>";
                    }else{
                        $this->salida .= "        <td align=\"right\">".FormatoValor($DatosQXEquipos[$i]['valor_no_cubierto'])."</td>";
                    }

                    if($DatosQXEquipos[$i]['facturado']=='1'){
                        $this->salida .= "        <td align=\"center\">SI</td>";
                    }else{
                        $this->salida .= "        <td align=\"center\">NO</td>";
                    }
                    $this->salida .= "      </table>";
                    $this->salida .= "      </td>";
                    $this->salida .= "    </tr>";
                }
                $this->salida .= "    </table>";
            }
            //consulta de los medicamentos en la cuenta del paciente
            $cargos=$this->CargosMedicamentosCuentaPaciente($_REQUEST['qx'],$Cuenta);
            $cargosDev=$this->CargosMedicamentosCuentaPacienteDevol($_REQUEST['qx'],$Cuenta);
            if(is_array($cargos) || is_array($cargosDev)){
                $this->salida .= "    <BR><table width=\"80%\" border=\"0\" align=\"center\" class=\"modulo_table_list_title\">";
                $this->salida .= "    <tr class=\"modulo_table_title\"><td colspan=\"6\">INSUMOS Y MEDICAMENTOS CARGADOS EN LA CUENTA</td></tr>";
                $this->salida .= "    <tr class=\"modulo_table_title\">";
                $this->salida .= "    <td width=\"15%\">CODIGO</td>";
                $this->salida .= "    <td width=\"15%\">CANTIDAD</td>";
                $this->salida .= "    <td>PRODUCTO</td>";
                $this->salida .= "    <td width=\"15%\">VALOR NO CUBIERTO</td>";
        $this->salida .= "    <td width=\"15%\">VALOR CUBIERTO</td>";
        $this->salida .= "    <td width=\"15%\">FACTURADO</td>";
                $this->salida .= "    </tr>";
                if(is_array($cargos)){
                    $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"6\">DESPACHOS</td></tr>";
                    for($i=0;$i<sizeof($cargos);$i++){
                        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
                        $this->salida .= "    <tr class=\"$estilo\">";
                        $this->salida .= "    <td align=\"left\">".$cargos[$i]['codigo_producto']."</td>";
                        $divisor=(int)($cargos[$i]['cantidad']);
                        if($cargos[$i]['cantidad']%$divisor){
                            $this->salida .= "    <td align=\"left\">".$cargos[$i]['cantidad']."</td>";
                        }else{
                            $this->salida .= "    <td align=\"left\">".$divisor."</td>";
                        }
                        $this->salida .= "    <td align=\"left\">".$cargos[$i]['descripcion']."</td>";
                        $this->salida .= "    <td align=\"left\">".$cargos[$i]['valor_nocubierto']."</td>";
            $this->salida .= "    <td align=\"left\">".$cargos[$i]['valor_cubierto']."</td>";
            if($cargos[$i]['facturado']==1){
              $this->salida .= "    <td align=\"center\"><img title=\"Cargo Facturado\" border = 0 src=\"".GetThemePath()."/images/checksi.png\"></td>";
            }else{
              $this->salida .= "    <td align=\"center\"><img title=\"Cargo No Facturado\" border = 0 src=\"".GetThemePath()."/images/checkno.png\"></td>";
            }
                        $y++;
                    }
                }
                if(is_array($cargosDev)){
                    $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"6\">DEVOLUCIONES</td></tr>";
                    for($i=0;$i<sizeof($cargosDev);$i++){
                        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
                        $this->salida .= "    <tr class=\"$estilo\">";
                        $this->salida .= "    <td align=\"left\">".$cargosDev[$i]['codigo_producto']."</td>";
                        $divisor=(int)($cargosDev[$i]['cantidad']);
                        if($cargosDev[$i]['cantidad']%$divisor){
                            $this->salida .= "    <td align=\"left\">".$cargosDev[$i]['cantidad']."</td>";
                        }else{
                        $this->salida .= "    <td align=\"left\">".$divisor."</td>";
                        }
                        $this->salida .= "    <td align=\"left\">".$cargosDev[$i]['descripcion']."</td>";
                        $this->salida .= "    <td align=\"left\">".$cargosDev[$i]['valor_nocubierto']."</td>";
            $this->salida .= "    <td align=\"left\">".$cargosDev[$i]['valor_cubierto']."</td>";
            if($cargosDev[$i]['facturado']==1){
              $this->salida .= "    <td align=\"center\"><img title=\"Cargo Facturado\" border = 0 src=\"".GetThemePath()."/images/checksi.png\"></td>";
            }else{
              $this->salida .= "    <td align=\"center\"><img title=\"Cargo No Facturado\" border = 0 src=\"".GetThemePath()."/images/checkno.png\"></td>";
            }
                        $y++;
                    }
                }
                $this->salida .= "    </table>";
            }
            $accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
            $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
            $this->salida .= "<p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER AL DETALLE\"></p>";
            $this->salida .= "</form>";

            $this->salida .= ThemeCerrarTabla();
            return true;
    }

    function FormaDetalleMedicamentosQx($var)
    {
            $this->salida .= "<br><table border=\"1\" cellpadding=\"2\" width=\"99%\" align=\"center\"  class=\"modulo_table_list_title\">";
            $this->salida .= "    <tr align=\"center\" class=\"modulo_table_list_title\">";
            if($_SESSION['CUENTAS']['SWCUENTAS']!='Cerradas')
            {  $this->salida .= "        <td colspan=\"11\" align=\"center\">MEDICAMENTOS CIRUGIA</td>";      }
            else
            {  $this->salida .= "        <td colspan=\"9\" align=\"center\">MEDICAMENTOS CIRUGIA</td>";   }
            $this->salida .= "    </tr>";
            $this->salida .= "    <tr align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "        <td width=\"10%\">CODIGO</td>";
            $this->salida .= "        <td width=\"40%\">CARGO</td>";
            $this->salida .= "        <td width=\"9%\">PRECIO.</td>";
            $this->salida .= "        <td width=\"5%\">CANT.</td>";
            $this->salida .= "        <td width=\"9%\">VALOR</td>";
            $this->salida .= "        <td width=\"9%\">VAL. NO CUBIERTO</td>";
            $this->salida .= "        <td width=\"9%\">VAL. CUBIERTO</td>";
            if($_SESSION['CUENTAS']['SWCUENTAS']!='Cerradas')
            {  $this->salida .= "     <td colspan=\"2\" width=\"6%\">ACCION</td>";  }
            $this->salida .= "        <td width=\"2%\">INT</td>";
            $this->salida .= "        <td width=\"2%\">EXT</td>";
            $this->salida .= "        <td></td>";
            $this->salida .= "    </tr>";
            $ValTotal=$TotalNo=$TotalCub=0;
            for($i=0; $i<sizeof($var); $i++)
            {
                    if( $i % 2) $estilo='modulo_list_claro';
                    else $estilo='modulo_list_oscuro';
                    $ValTotal+=$var[$i][valor_cargo];
                    $TotalNo+=$var[$i][valor_nocubierto];
                    $TotalCub+=$var[$i][valor_cubierto];
                    $this->salida .= "    <tr class=\"$estilo\">";
                    $this->salida .= "        <td align=\"center\">".$var[$i][codigo_producto]."</td>";
                    $this->salida .= "        <td>".$var[$i][descripcion]."</td>";
                    $this->salida .= "        <td align=\"center\">".FormatoValor($var[$i][precio])."</td>";
                    $this->salida .= "        <td align=\"center\">".FormatoValor($var[$i][cantidad])."</td>";
                    $this->salida .= "        <td align=\"center\">".FormatoValor($var[$i][valor_cargo])."</td>";
                    $this->salida .= "        <td align=\"center\">".FormatoValor($var[$i][valor_nocubierto])."</td>";
                    $this->salida .= "        <td align=\"center\">".FormatoValor($var[$i][valor_cubierto])."</td>";
                    if($_SESSION['CUENTAS']['SWCUENTAS']!='Cerradas')
                    {
                            $accionM=ModuloGetURL('app','Facturacion','user','LlamaFormaModificar',array('Transaccion'=>$var[$i][transaccion],'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Datos'=>$var[$i],'des'=>$desc,'codigo'=>$codigo,'doc'=>$documento,'numeracion'=>$numeracion));
                            $this->salida .= "        <td><a href=\"$accionM\">MODI</a></td>";
                            $mensaje='Esta seguro que desea eliminar este cargo.';
                            $arreglo=array('Transaccion'=>$var[$i][transaccion],'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Cama'=>$Cama,'Fecha'=>$Fecha,'des'=>$desc,'codigo'=>$codigo,'doc'=>$documento,'numeracion'=>$numeracion);
                            $accionE=ModuloGetURL('app','Facturacion','user','ConfirmarAccion',array('c'=>'app','m'=>'Facturacion','me2'=>'DefinirForma','me'=>'EliminarCargo','mensaje'=>$mensaje,'titulo'=>'ELIMINAR CARGO DE LA CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos,'arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
                                if(empty($documento) AND empty($numeracion))
                            {  $this->salida .= "        <td><a href=\"$accionE\">ELIM</a></td>";  }
                                else
                            {  $this->salida .= "        <td></td>";  }
                    }
                    $imagenInt=$imagenExt='';
                    if($var[$i][autorizacion_int]==='0')
                    {  $imagenInt="no_autorizado.png";   $D=1; }
                    elseif($var[$i][autorizacion_int] >100)
                    {  $imagenInt="autorizado.png";   $D=0; }
                    elseif($var[$i][autorizacion_int] ==1)
                    {  $imagenInt="autorizadosiis.png";   $D=1; }

                    if($var[$i][autorizacion_ext]==='0')
                    {  $imagenExt="no_autorizado.png";   $n=1; }
                    elseif($var[$i][autorizacion_ext] >100)
                    {  $imagenExt="autorizado.png";   $n=0; }
                    elseif($var[$i][autorizacion_ext] ==1)
                    {  $imagenExt="autorizadosiis.png";   $n=1; }
                    if(!empty($imagenInt))
                    {  $this->salida .= "       <td><img src=\"".GetThemePath()."/images/$imagenInt\"></td>";  }
                    else
                    {  $this->salida .= "       <td></td>";  }
                    if(!empty($imagenExt))
                    {  $this->salida .= "       <td><img src=\"".GetThemePath()."/images/$imagenExt\"></td>";  }
                    else
                    {  $this->salida .= "       <td></td>";  }
                    if($imagenInt=="autorizado.png")
                    {  $this->salida .= "       <td><a href=\"javascript:ConsultaAutorizacion('DATOS DE LA AUTORIZACION','reports/$VISTA/datosautorizacioncargo.php',1000,250,'".$var[$i][tarifario_id]."','".$var[$i][cargo]."',$Cuenta,".$var[$i][autorizacion_interna].",1,'Int')\"><img src=\"".GetThemePath()."/images/informacion.png\" border=\"0\"></a></td>";  }
                    else
                    { $this->salida .= "        <td></td>";}
                    $this->salida .= "    </tr>";
            }
            if( $i % 2) $estilo='modulo_list_claro';
            else $estilo='modulo_list_oscuro';
            $this->salida .= "    <tr class=\"$estilo\" align=\"center\">";
            $this->salida .= "        <td colspan=\"4\"><b>TOTALES: </b></td>";
            $this->salida .= "        <td><b>".FormatoValor($ValTotal)."</b></td>";
            $this->salida .= "        <td><b>".FormatoValor($TotalNo)."</b></td>";
            $this->salida .= "        <td><b>".FormatoValor($TotalCub)."</b></td>";
            if($_SESSION['CUENTAS']['SWCUENTAS']!='Cerradas')
            {  $col=6; }
            else {  $col=1; }
            $this->salida .= "        <td colspan=\"$col\"></td>";
            $this->salida .= "    </tr>";
            $this->salida .= "  </table><br>";
    }

//----------------------FIN CIRUGIA--------------

    

//------------------------------------------------------------------------------------
    /**
  * Muestra la forma que pregunta la anulacion o activacion de la orden de servicio que cuando de elimina el cargo desde la cuenta.
  * @access private
  * @return boolean
  */

    function FormaVerificacionAnulacionOS($Transaccion,$Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Fecha,$Ingreso,$observacion,$Pieza,$doc,$numeracion,$qx,$codigo,$des,$noFacturado,$Consecutivo){

        $this->salida .= ThemeAbrirTabla('VERIFICACION DEL ESTADO DE LA ORDEN DE SERVICIO');
        $accion=ModuloGetURL('app','Facturacion','user','EliminarCargoOrdenCumplida',array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,"observacion"=>$observacion,"Pieza"=>$Pieza,"doc"=>$doc,"numeracion"=>$numeracion,"qx"=>$qx,"codigo"=>$codigo,"des"=>$des,"noFacturado"=>$noFacturado,"Consecutivo"=>$Consecutivo));
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<table width=\"50%\" align=\"center\" border=0>";
        $this->salida .= "  <tr>";
        $this->salida .= "    <td class=\"label\" align=\"center\">LA ORDEN DE SERVICIO SE ENCUENTRA CUMPLIDA, SELECCIONE QUE DESEA REALIZAR CON LA ORDEN</td>";
        $this->salida .= "  </tr>";
        $this->salida .= "</table>";
        //botones
        $this->salida .= "  <BR><table width=\"50%\" align=\"center\" border=0>";
        $this->salida .= "  <tr><td align=\"center\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"Anular\" value=\"ANULAR ORDEN\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"Activar\" value=\"ACTIVAR ORDEN\">";
        $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"Volver\" value=\"VOLVER\">";
        $this->salida .= "  </td>";
        $this->salida .= "  </table>";
        $this->salida .= "</form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     * Llama a la forma FormaModificarCuotaPacienteCuotaModeradora
     * para Modificar la cuota paciente(copago)
     *
     */
    function CallFormaModificarCuotaPaciente()
    {
        $numero_cuenta = $_REQUEST['numero_cuenta'];
        $nombre_paciente = $_REQUEST['nombre_paciente'];
        $valor_cuota = $_REQUEST['valor_cuota'];
        $motivos = $this->GetMotivosCambioCopago();
        $this->salida .= ThemeAbrirTabla("MODIFICAR CUOTA PACIENTE CUENTA No. $numero_cuenta $nombre_paciente");
        $this->EncabezadoEmpresa();
        $this->salida .= "<table align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "</table>";
        $this->FormaModificarCuotaPacienteCuotaModeradora($numero_cuenta,$valor_cuota,$motivos,"CUOTA_PACIENTE");
        $this->salida .= ThemeCerrarTabla();
        return true;
    }//Fin CallModificarCuotaPaciente

    /**
     * Llama a la forma FormaModificarCuotaPacienteCuotaModeradora
     * para Modificar la cuota moderadora
     *
     */
    function CallFormaModificarCuotaModeradora()
    {
        $numero_cuenta = $_REQUEST['numero_cuenta'];
        $nombre_paciente = $_REQUEST['nombre_paciente'];
        $valor_cuota = $_REQUEST['valor_cuota'];
        $motivos = $this->GetMotivosCambioCuotaModeradora();
        $this->salida .= ThemeAbrirTabla("MODIFICAR CUOTA MODERADORA CUENTA No. $numero_cuenta $nombre_paciente");
        $this->EncabezadoEmpresa();
        $this->salida .= "<table align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "</table>";
        $this->FormaModificarCuotaPacienteCuotaModeradora($numero_cuenta,$valor_cuota,$motivos,"CUOTA_MODERADORA");
        $this->salida .= ThemeCerrarTabla();
        return true;
    }//Fin CallFormaModificarCuotaModeradora

    /**
     * Forma que pinta el formulario ya sea para modificar la cuota paciente
     * o la cuota moderadora.
     *
     * @param int numero_cuenta
     * @param array motivos()
     * @param mixed tipo_cuota(puede tomar los valores de CUOTA_PACIENTE=1 O CUOTA_MODERADORA=2)
     */
    function FormaModificarCuotaPacienteCuotaModeradora($numero_cuenta,$valor_cuota,$motivos,$tipo_cuota)
    {
        if($tipo_cuota == "CUOTA_PACIENTE" || $tipo_cuota == 1)
            $accion = ModuloGeturl("app","Facturacion","user","CallSetCuotaPaciente");
        elseif($tipo_cuota == "CUOTA_MODERADORA" || $tipo_cuota == 2)
            $accion = ModuloGeturl("app","Facturacion","user","CallSetCuotaModeradora");
        else
            trigger_error("Error en el m?todo FormaModificarCuotaPacienteCuotaModeradora<br>el parametro tipo_cuota tiene un valor invalido",E_USER_ERROR);
        $accion .= "&".$_SESSION['FACTURACION']['CUENTAS']['REQUEST'];
        $equivalencias['CUOTA_PACIENTE'] = "CUOTA PACIENTE";
        $equivalencias[1] = "CUOTA PACIENTE";
        $equivalencias['CUOTA_MODERADORA'] = "CUOTA MODERADORA";
        $equivalencias[2] = "CUOTA MODERADORA";
        $this->salida .= "<form name=\"frmCambio$tipo_cuota\" action=\"$accion\" method=\"post\">\n";
        $this->salida .= "  <table width=\"50%\" align=\"center\" class=\"label\" >";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td>";
        $this->salida .= "  <input type=\"hidden\" name=\"numero_cuenta\" value = \"$numero_cuenta\">";
        $this->salida .= "      <fieldset>";
        $this->salida .= "      <legend>DATOS ".$equivalencias[$tipo_cuota]."</legend>";
        $this->salida .= "      <table width=\"100%\">\n";
        $this->salida .= "          <tr>\n";
        $this->salida .= "              <td width=\"20%\" class=\"label\">VALOR</td>\n";
        $this->salida .= "              <td  width=\"80%\"><input type=\"text\" name=\"valor_cuota\" class=\"input-text\" value=\"$valor_cuota\"></td>\n";
        $this->salida .= "          </tr>\n";
        $this->salida .= "          <tr>\n";
        $this->salida .= "              <td class=\"label\">MOTIVO</td>\n";
        $this->salida .= "              <td>\n";
        $this->salida .= "                  <select name=\"motivo_modificacion\" class=\"select\" >\n";
        foreach($motivos as $key=>$motivo)
        {
            if($_REQUEST['motivo_modificacion'] == $motivo['motivo_cambio_id'])
                $this->salida .= "                  <option value=\"{$motivo['motivo_cambio_id']}\" selected>{$motivo['descripcion']}</option>\n";
            else
                $this->salida .= "                  <option value=\"{$motivo['motivo_cambio_id']}\">{$motivo['descripcion']}</option>\n";
        }
        $this->salida .= "                  </select>\n";
        $this->salida .= "              </td>\n";
        $this->salida .= "          </tr>\n";
        $this->salida .= "          <tr>\n";
        $this->salida .= "              <td class=\"label\" valign=\"top\">OBSERVACI?N</td>\n";
        $this->salida .= "              <td><textarea name=\"observacion\" style=\"width:100%\" class=\"textarea\">".$_REQUEST['observacion']."</textarea></td>\n";
        $this->salida .= "          </tr>\n";
        $this->salida .= "      </table>\n";
        $this->salida .= "      </fieldset>\n";
        $this->salida .= "  </td>\n";
        $this->salida .= "  </tr>\n";
        $this->salida .= "  </table>\n";
        $this->salida .= "      <table align=\"center\">\n";
        $this->salida .= "          <tr>\n";
        $this->salida .= "              <td>\n";
        $this->salida .= "                  <input type=\"submit\" class=\"input-bottom\" value=\"ACEPTAR\">\n";
        $this->salida .= "              </td>\n";
        $this->salida .= "  </form>\n";
        $accion = ModuloGetUrl("app","Facturacion","user","Cuenta")."&".$_SESSION['FACTURACION']['CUENTAS']['REQUEST'];
        $this->salida .= "  <form name=\"frmCancelar\" action=\"$accion\" method = \"post\">\n";
        $this->salida .= "              <td>\n";
        $this->salida .= "                  <input type=\"submit\" class=\"input-bottom\" value=\"CANCELAR\">\n";
        $this->salida .= "              </td>\n";
        $this->salida .= "  </form>\n";
        $this->salida .= "          </tr>\n";
        $this->salida .= "      </table>\n";
    }//Fin FormaModificarCuotaPacienteCuotaModeradora


    /**
     * Llama al metodo SetCuotaPaciente
     */
    function CallSetCuotaPaciente()
    {
        $numero_cuenta = $_REQUEST['numero_cuenta'];
        $valor = $_REQUEST['valor_cuota'];
        $tipo_id_motivo = $_REQUEST['motivo_modificacion'];
        $observacion = $_REQUEST['observacion'];
        if(!is_numeric($valor))
        {
            $this->frmError["MensajeError"] = "EL CAMPO VALOR DEBE SER NUM?RICO";
            $this->CallFormaModificarCuotaPaciente();
            return true;
        }
        elseif($valor<0)
        {
            $this->frmError["MensajeError"] = "EL CAMPO VALOR DEBE SER MAYOR QUE CERO";
            $this->CallFormaModificarCuotaPaciente();
            return true;
        }
        if($this->SetCuotaPaciente($numero_cuenta,$valor,$tipo_id_motivo,$observacion))
        {
            $this->frmError["MensajeError"] = "SE MODIFICO CORRECTAMENTE LA CUOTA PACIENTE";
        }
        else
        {
            $this->frmError["MensajeError"] = "ERROR AL MODIFICAR LA CUOTA PACIENTE";
        }

        if(!empty($_SESSION['CUENTAS']['RETORNO']))
        {
            $contenedor = $_SESSION['CUENTAS']['RETORNO']['contenedor'];
            $modulo = $_SESSION['CUENTAS']['RETORNO']['modulo'];
            $tipo = $_SESSION['CUENTAS']['RETORNO']['tipo'];
            $metodo = $_SESSION['CUENTAS']['RETORNO']['metodo'];
            $argumentos = $_SESSION['CUENTAS']['RETORNO']['argumentos'];
            $this->ReturnMetodoExterno($contenedor,$modulo,$tipo,$metodo,$argumentos);
        }
        else
        {
            $this->Cuenta();
        }
        return true;
    }//Fin CallSetCuotaPaciente

    /**
     * Llama al metodo SetCuotaModeradora
     */
    function CallSetCuotaModeradora()
    {
        $numero_cuenta = $_REQUEST['numero_cuenta'];
        $valor = $_REQUEST['valor_cuota'];
        $tipo_id_motivo = $_REQUEST['motivo_modificacion'];
        $observacion = $_REQUEST['observacion'];
        if(!is_numeric($valor))
        {
            $this->frmError["MensajeError"] = "EL CAMPO VALOR DEBE SER NUM?RICO";
            $this->CallFormaModificarCuotaModeradora();
            return true;
        }
        elseif($valor<0)
        {
            $this->frmError["MensajeError"] = "EL CAMPO VALOR DEBE SER MAYOR QUE CERO";
            $this->CallFormaModificarCuotaModeradora();
            return true;
        }
        if($this->SetCuotaModeradora($numero_cuenta,$valor,$tipo_id_motivo,$observacion))
        {
            $this->frmError["MensajeError"] = "SE MODIFICO CORRECTAMENTE LA CUOTA MODERADORA";
        }
        else
        {
            $this->frmError["MensajeError"] = "ERROR AL MODIFICAR LA CUOTA MODERADORA";
        }
        $this->Cuenta();
        return true;
    }//Fin CallSetCuotaModeradora

    //MauroB
    /**
    *
    */
    function FormaPideDatosAdicionalesRipsAC($cargos_cups,$mensaje,$sw_dato_complementario,$viasingreso)
    {
        $titulocampos = "&titulo[0]=CODIGO&titulo[1]=DESCRIPCION";
        $nombrecampossql = "&campossql[0]=diagnostico_id&campossql[1]=diagnostico_nombre";

        $action7  = "classes/BuscadorConsulta/BuscadorHtml.class.php?buscador=diagnosticos&forma=datosadicionalesrips";
        $action7 .= $nombrecampos.$nombrecampossql.$titulocampos;

        $tiposfinalidad    = $this->ConsultaTiposFinalidad();
        $tiposcausaexterna = $this->ConsultaCausaExterna();
        $tiposdiagnostico  = $this->ConsultaDiagnostico();
        $this->salida .= ThemeAbrirTabla("ADICION INFORMACION NECESARIA PARA RIPS CON EL CARGO(AC): ".$cargos_cups);
        $this->EncabezadoEmpresa();
        $this->salida .= "<BR>";
        $this->salida .= "<table align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "</table>";

        $this->salida .= "  <p class=\"label_error\" align=\"center\">$mensaje</p>";
        $accion = ModuloGeturl("app","Facturacion","user","PideDatosAdicionalesRips",array('dato_complementario'=>$sw_dato_complementario,'cargos_cups'=>$cargos_cups));
        $this->salida .= "<form name=\"datosadicionalesrips\" action=\"$accion\" method=\"post\">\n";
            $this->salida .= "\n<script>\n";
            $this->salida .= "var rem=\"\";\n";
            $this->salida .= "  function abrir(campo1,campo2){\n";
            $this->salida .= "      var nombre='';\n";
            $this->salida .= "      var url2='';\n";
            $this->salida .= "      var str='';\n";
            $this->salida .= "      var ALTO=screen.height;\n";
            $this->salida .= "      var ANCHO=screen.width;\n";
            $this->salida .= "      nombre=\"buscador_General\";\n";
            $this->salida .= "      str =\"ANCHO,ALTO,resizable=no,status=no,scrollbars=yes\";\n";
            $this->salida .= "      url2 = '$action7&campos[0]='+campo1+'&campos[1]='+campo2;\n";
            $this->salida .= "      rem = window.open(url2, nombre, str);\n";
            $this->salida .= "  }\n";
            $this->salida .= "</script>\n";
        $this->salida .= "  <input type=\"hidden\" name=\"datos\" value = 'adiciona'>";
        $this->salida .= "  <input type=\"hidden\" name=\"sw_dato_complementario\" value = '$sw_dato_complementario'>";
        $this->salida .= "  <input type=\"hidden\" name=\"Cuenta\" value=\"".$_SESSION['TMP_DATOS']['Cuenta']."\">";
        $this->salida .= "  <input type=\"hidden\" name=\"cargo\" value=\"$cargos_cups\">";
        $this->salida .= "  <fieldset><legend class=\"field\">DATOS RIPS NECESARIOS POR SER UN SERVICIO</legend> ";
        $this->salida .= "  <table width=\"80%\" align=\"center\" class=\"label\" >\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td width=\"25%\">FECHA DE CONSULTA:</td>\n";
        $this->salida .= "          <td width=\"25%\"><input type=\"text\" name=\"ac_fechaconsulta\" value=\"".$_REQUEST[ac_fechaconsulta]."\" size=\"10\" class=\"input-text\" readonly>\n";
        $this->salida .= "          ".ReturnOpenCalendario('datosadicionalesrips','ac_fechaconsulta','/')."</td>\n";
        $this->salida .= "          <td width=\"25%\">CODIGO DE PROCEDIMIENTO:</td>\n";
        $this->salida .= "          <td width=\"25%\">".$cargos_cups."</td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td width=\"25%\">FINALIDAD CONSULTA:</td>\n";
        $this->salida .= "          <td width=\"25%\"><select name=\"ac_tipofinalidad\" class=\"select\">";
    $this->salida .= "          <option value=\"\" selected>-------SELECCIONE-------</option>";
        foreach($tiposfinalidad as $tiposF => $tipos)
        {
            $this->salida .="     <option value=\"".$tipos['tipo_finalidad_id']."\" >".substr($tipos['detalle'],0,50)."</option>";
        }
    $this->salida .= "</select></td>";

        $this->salida .= "          <td width=\"25%\">CAUSA EXTERNA:</td>\n";
        $this->salida .= "          <td width=\"25%\"><select name=\"ac_causaexterna\" class=\"select\">";
    $this->salida .= "          <option value=\"\" selected>-------SELECCIONE-------</option>";
        foreach($tiposcausaexterna as $tiposC => $tipos)
        {
            $this->salida .="     <option value=\"".$tipos['causa_externa_id']."\" >".substr($tipos['descripcion'],0,50)."</option>";
        }

    $this->salida .= "</select></td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td width=\"25%\">DIAGNOSTICO PRINCIPAL:</td>\n";
        $this->salida .= "          <td width=\"20%\"  colspan=\"1\">";
        $this->salida .= "              <table width=\"100%\" align=\"center\" class=\"label\" >\n";
        $this->salida .= "                  <tr>\n";
        $this->salida .= "                      <td width=\"20%\"><input type=\"text\" name=\"ac_diagnostico\" value=\"\" size=\"10\" class=\"input-text\"></td>\n";
        $this->salida .= "                      <td width=\"75%\"><input type=\"text\" name=\"ac_diagnostico_descripcion\" value=\"\" size=\"30\" class=\"input-text\"></td>\n";
        $this->salida .= "                      <td width=\"5%\"><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"BUSCAR.\" onclick=abrir('ac_diagnostico','ac_diagnostico_descripcion')></td>\n";
        $this->salida .= "                  </tr>\n";
        $this->salida .= "              </table>";
        $this->salida .= "          </td>";
        $this->salida .= "          <td width=\"25%\">TIPO DIAGNOSTICO PRINCIPAL:</td>\n";
        $this->salida .= "          <td width=\"25%\"><select name=\"ac_tipodiagnostico\" class=\"select\">";
    $this->salida .= "              <option value=\"\">-------SELECCIONE-------</option>";
        $this->salida .= "              <option value=\"1\">IMPRESION DIAGNOSTICA</option>";
        $this->salida .= "              <option value=\"2\">CONFIRMADO NUEVO</option>";
        $this->salida .= "              <option value=\"3\">CONFIRMADO REPETIDO</option>";
        $this->salida .= "          </select></td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td width=\"25%\">NUMERO DE AUTORIZACION</td>\n";
        $this->salida .= "          <td width=\"25%\"><input type=\"text\" name=\"autorizacion\" value=\"1\" size=\"10\" class=\"input-text\"></td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "  </table>\n";
        $this->salida .= "  </fieldset>";
        if($sw_dato_complementario[sw_ah] == '1')
        {
            $this->salida .= "  <fieldset><legend class=\"field\">DATOS RIPS NECESARIOS POR SER DE HOSPITALIZACION</legend> ";
            $this->salida .= "  <table width=\"50%\" align=\"center\" class=\"label\" >\n";
            $this->salida .= "      <tr>\n";
            $this->salida .= "          <td width=\"25%\">VIAS DE INGRESO:</td>\n";
            $this->salida .= "          <td width=\"25%\"><select name=\"ah_ViaIngreso\" class=\"select\">";
            $this->salida .= "          <option value=\"\">-------SELECCIONE-------</option>";
            foreach($viasingreso as $viasI => $vias)
            {
                $this->salida .="     <option value=\"".$vias['via_ingreso_id']."\" selected>".substr($vias['via_ingreso_nombre'],0,50)."</option>";
            }
            $this->salida .= "          </select></td>\n";
            $this->salida .= "      </tr>\n";
            $this->salida .= "      <tr>\n";
            $this->salida .= "          <td width=\"25%\">FECHA DE INGRESO:</td>\n";
            $this->salida .= "          <td width=\"25%\"><input type=\"text\" name=\"ah_fechaingreso\" value=\"\" size=\"10\" class=\"input-text\" readonly>\n";
            $this->salida .= "          ".ReturnOpenCalendario('datosadicionalesrips','ah_fechaingreso','/')."</td>\n";
            $this->salida .= "          <td width=\"25%\">HORA DE INGRESO:</td>\n";
            $this->salida .= "          <td width=\"25%\">\n";
            //pide hora:minutos
            $this->salida .= "      <select name=\"ah_horarioingreso\" class=\"select\">\n";
            $this->salida .= "      <option value=\"-1\">--</option>\n";
            for($i=0;$i<24;$i++)
            {
                if($i<10)
                {
                    if($_POST['ah_horarioingreso']=="0$i")
                    {
                        $this->salida .="<option value=\"0$i\" selected>0$i</option>\n";
                    }
                    else
                    {
                        $this->salida .="<option value=\"0$i\">0$i</option>\n";
                    }
                }
                else
                {
                    if($_POST['ah_horarioingreso']=="$i")
                    {
                        $this->salida .="<option value=\"$i\" selected>$i</option>\n";
                    }
                    else
                    {
                        $this->salida .="<option value=\"$i\">$i</option>\n";
                    }
                }
            }
            $this->salida .= "      </select>\n";
            $this->salida .= " : ";
            $this->salida .= "      <select name=\"ah_minuteroingreso\" class=\"select\">\n";
            $this->salida .= "      <option value=\"-1\">--</option>\n";
            for($i=0;$i<60;$i++)
            {
                if($i<10)
                {
                    if($_POST['ah_minuteroingreso']=="0$i")
                    {
                        $this->salida .="<option value=\"0$i\" selected>0$i</option>\n";
                    }
                    else
                    {
                        $this->salida .="<option value=\"0$i\">0$i</option>\n";
                    }
                }
                else
                {
                    if($_POST['ah_minuteroingreso']=="$i")
                    {
                        $this->salida .="<option value=\"$i\" selected>$i</option>\n";
                    }
                    else
                    {
                        $this->salida .="<option value=\"$i\">$i</option>\n";
                    }
                }
            }
            $this->salida .= "      </select>\n";
            //fin pide hora:minutos
            $this->salida .= "          </td>\n";
            $this->salida .= "      </tr>\n";
            $this->salida .= "      <tr>\n";
/*          $this->salida .= "          <td width=\"25%\">NUMERO DE AUTORIZACION</td>\n";
            $this->salida .= "          <td width=\"25%\"><input type=\"text\" name=\"autorizacion\" value=\"1\" size=\"10\" class=\"input-text\"></td>\n";*/
            $this->salida .= "          <td width=\"25%\">CAUSA EXTERNA:</td>\n";
            $this->salida .= "          <td width=\"25%\"><select name=\"ah_causaexterna\" class=\"select\">";
            $this->salida .= "          <option value=\"\">-------SELECCIONE-------</option>";
            foreach($tiposcausaexterna as $tiposC => $tipos)
            {
                $this->salida .="     <option value=\"".$tipos['causa_externa_id']."\" selected>".substr($tipos['descripcion'],0,50)."</option>";
            }
            $this->salida .= "          </select></td>\n";
            $this->salida .= "      </tr>\n";
            //DIAGNOSTICO INGRESO ah_diagnosticoingreso
            $this->salida .= "          <td width=\"25%\">DIAGNOSTICO INGRESO:</td>\n";
            $this->salida .= "          <td width=\"20%\"  colspan=\"1\">";
            $this->salida .= "              <table width=\"100%\" align=\"center\" class=\"label\" >\n";
            $this->salida .= "                  <tr>\n";
            $this->salida .= "                      <td width=\"20%\"><input type=\"text\" name=\"ah_diagnosticoingreso\" value=\"\" size=\"10\" class=\"input-text\" readonly></td>\n";
            $this->salida .= "                      <td width=\"75%\"><input type=\"text\" name=\"ah_diagnosticoingreso_descripcion\" value=\"\" size=\"30\" class=\"input-text\" readonly></td>\n";
            $this->salida .= "                      <td width=\"5%\"><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"BUSCAR\" onclick=abrir('ah_diagnosticoingreso','ah_diagnosticoingreso_descripcion')></td>\n";
            $this->salida .= "                  </tr>\n";
            $this->salida .= "              </table>";
            $this->salida .= "          </td>";
            $this->salida .= "      </tr>\n";
            //FIN DIAGNOSTICO INGRESO
            //DIAGNOSTICO SALIDA ah_diagnosticosalida
            $this->salida .= "      <tr>\n";
            $this->salida .= "          <td width=\"25%\">DIAGNOSTICO SALIDA:</td>\n";
            $this->salida .= "          <td width=\"20%\"  colspan=\"1\">";
            $this->salida .= "              <table width=\"100%\" align=\"center\" class=\"label\" >\n";
            $this->salida .= "                  <tr>\n";
            $this->salida .= "                      <td width=\"20%\"><input type=\"text\" name=\"ah_diagnosticosalida\" value=\"\" size=\"10\" class=\"input-text\" readonly></td>\n";
            $this->salida .= "                      <td width=\"75%\"><input type=\"text\" name=\"ah_diagnosticosalida_descripcion\" value=\"\" size=\"30\" class=\"input-text\" readonly></td>\n";
            $this->salida .= "                      <td width=\"5%\"><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"BUSCAR\" onclick=abrir('ah_diagnosticosalida','ah_diagnosticosalida_descripcion')></td>\n";
            $this->salida .= "                  </tr>\n";
            $this->salida .= "              </table>";
            $this->salida .= "          </td>";
            //FIN DIAGNOSTICO SALIDA
            $this->salida .= "          <td width=\"25%\">ESTADO SALIDA:</td>\n";
            $this->salida .= "          <td width=\"25%\"><select name=\"ah_estadosalida\" class=\"select\">";
            $this->salida .= "              <option value=\"\">-------SELECCIONE-------</option>";
            $this->salida .= "              <option value=\"1\">VIVO(A)</option>";
            $this->salida .= "              <option value=\"2\">MUERTO(A)</option>";
            $this->salida .= "          </select></td>\n";
            $this->salida .= "      </tr>\n";
            $this->salida .= "      <tr>\n";
            $this->salida .= "          <td width=\"25%\">FECHA SALIDA:</td>\n";
            $this->salida .= "          <td width=\"25%\"><input type=\"text\" name=\"ah_fechasalida\" value=\"\" size=\"10\" class=\"input-text\" readonly>\n";
            $this->salida .= "          ".ReturnOpenCalendario('datosadicionalesrips','ah_fechasalida','/')."</td>\n";
            $this->salida .= "          <td width=\"25%\">HORA SALIDA:</td>\n";
            $this->salida .= "          <td width=\"25%\">\n";
            //pide hora:minutos
            $this->salida .= "      <select name=\"ah_horariosalida\" class=\"select\">";
            $this->salida .= "      <option value=\"-1\">--</option>";
            for($i=0;$i<24;$i++)
            {
                if($i<10)
                {
                    if($_POST['ah_horariosalida']=="0$i")
                    {
                        $this->salida .="<option value=\"0$i\" selected>0$i</option>";
                    }
                    else
                    {
                        $this->salida .="<option value=\"0$i\">0$i</option>";
                    }
                }
                else
                {
                    if($_POST['ah_horariosalida']=="$i")
                    {
                        $this->salida .="<option value=\"$i\" selected>$i</option>";
                    }
                    else
                    {
                        $this->salida .="<option value=\"$i\">$i</option>";
                    }
                }
            }
            $this->salida .= "      </select>";
            $this->salida .= " : ";
            $this->salida .= "      <select name=\"ah_minuterosalida\" class=\"select\">";
            $this->salida .= "      <option value=\"-1\">--</option>";
            for($i=0;$i<60;$i++)
            {
                if($i<10)
                {
                    if($_POST['ah_minuterosalida']=="0$i")
                    {
                        $this->salida .="<option value=\"0$i\" selected>0$i</option>";
                    }
                    else
                    {
                        $this->salida .="<option value=\"0$i\">0$i</option>";
                    }
                }
                else
                {
                    if($_POST['ah_minuterosalida']=="$i")
                    {
                        $this->salida .="<option value=\"$i\" selected>$i</option>";
                    }
                    else
                    {
                        $this->salida .="<option value=\"$i\">$i</option>";
                    }
                }
            }
            $this->salida .= "      </select>";
        //fin pide hora:minutos
            $this->salida .= "</td>\n";
            $this->salida .= "      </tr>\n";
            $this->salida .= "  </table>\n";
            $this->salida .= "  </fieldset>";

        }//fin ah
        if($sw_dato_complementario[sw_au] == '1')
        {
            $this->salida .= "  <fieldset><legend class=\"field\">DATOS RIPS NECESARIOS POR SER DE URGENCIAS</legend> ";
            $this->salida .= "  <table width=\"50%\" align=\"center\" class=\"label\" >\n";
            $this->salida .= "      <tr>\n";
            $this->salida .= "          <td width=\"25%\">FECHA DE INGRESO:</td>\n";
            $this->salida .= "          <td width=\"25%\"><input type=\"text\" name=\"au_fechaingreso\" value=\"\" size=\"10\" class=\"input-text\" readonly>\n";
            $this->salida .= "          ".ReturnOpenCalendario('datosadicionalesrips','au_fechaingreso','/')."</td>\n";
            $this->salida .= "          <td width=\"25%\">HORA DE INGRESO:</td>\n";
            $this->salida .= "          <td width=\"25%\">\n";
            //pide hora:minutos
            $this->salida .= "      <select name=\"au_horarioingreso\" class=\"select\">\n";
            $this->salida .= "      <option value=\"-1\">--</option>\n";
            for($i=0;$i<24;$i++)
            {
                if($i<10)
                {
                    if($_POST['au_horarioingreso']=="0$i")
                    {
                        $this->salida .="<option value=\"0$i\" selected>0$i</option>\n";
                    }
                    else
                    {
                        $this->salida .="<option value=\"0$i\">0$i</option>\n";
                    }
                }
                else
                {
                    if($_POST['au_horarioingreso']=="$i")
                    {
                        $this->salida .="<option value=\"$i\" selected>$i</option>\n";
                    }
                    else
                    {
                        $this->salida .="<option value=\"$i\">$i</option>\n";
                    }
                }
            }
            $this->salida .= "      </select>\n";
            $this->salida .= " : ";
            $this->salida .= "      <select name=\"au_minuteroingreso\" class=\"select\">\n";
            $this->salida .= "      <option value=\"-1\">--</option>\n";
            for($i=0;$i<60;$i++)
            {
                if($i<10)
                {
                    if($_POST['au_minuteroingreso']=="0$i")
                    {
                        $this->salida .="<option value=\"0$i\" selected>0$i</option>\n";
                    }
                    else
                    {
                        $this->salida .="<option value=\"0$i\">0$i</option>\n";
                    }
                }
                else
                {
                    if($_POST['au_minuteroingreso']=="$i")
                    {
                        $this->salida .="<option value=\"$i\" selected>$i</option>\n";
                    }
                    else
                    {
                        $this->salida .="<option value=\"$i\">$i</option>\n";
                    }
                }
            }
            $this->salida .= "      </select>\n";
            //fin pide hora:minutos
            $this->salida .= "          </td>\n";
            $this->salida .= "      </tr>\n";
            $this->salida .= "      <tr>\n";
/*          $this->salida .= "          <td width=\"25%\">NUMERO DE AUTORIZACION</td>\n";
            $this->salida .= "          <td width=\"25%\"><input type=\"text\" name=\"autorizacion\" value=\"1\" size=\"10\" class=\"input-text\"></td>\n";*/
            $this->salida .= "          <td width=\"25%\">CAUSA EXTERNA:</td>\n";
            $this->salida .= "          <td width=\"25%\"><select name=\"au_causaexterna\" class=\"select\">";
            $this->salida .= "          <option value=\"\">-------SELECCIONE-------</option>";
            foreach($tiposcausaexterna as $tiposC => $tipos)
            {
                $this->salida .="     <option value=\"".$tipos['causa_externa_id']."\" selected>".substr($tipos['descripcion'],0,50)."</option>";
            }
            $this->salida .= "          </select></td>\n";
            $this->salida .= "      </tr>\n";
            $this->salida .= "      <tr>\n";
            $this->salida .= "          <td width=\"25%\">DIAGNOSTICO SALIDA:</td>\n";
            $this->salida .= "          <td width=\"20%\"  colspan=\"1\">";
            $this->salida .= "              <table width=\"100%\" align=\"center\" class=\"label\" >\n";
            $this->salida .= "                  <tr>\n";
            $this->salida .= "                      <td width=\"20%\"><input type=\"text\" name=\"au_DiagnosticoSalida\" value=\"\" size=\"10\" class=\"input-text\" readonly></td>\n";
            $this->salida .= "                      <td width=\"75%\"><input type=\"text\" name=\"au_DiagnosticoSalida_descripcion\" value=\"\" size=\"30\" class=\"input-text\" readonly></td>\n";
            $this->salida .= "                      <td width=\"5%\"><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"BUSCAR\" onclick=abrirVentana('au_DiagnosticoSalida','au_DiagnosticoSalida_descripcion')></td>\n";
            $this->salida .= "                  </tr>\n";
            $this->salida .= "              </table>";
            $this->salida .= "          </td>";
            $this->salida .= "          <td width=\"25%\">DESTINO SALIDA:</td>\n";
            $this->salida .= "          <td width=\"25%\"><select name=\"au_destinosalida\" class=\"select\">";
            $this->salida .= "          <option value=\"\">-------SELECCIONE-------</option>";
    //      foreach($tiposdestinosalida as $tiposD => $tipos)
    //      {
    //          $this->salida .="     <option value=\"".$tipos['']."\" selected>".substr($tipos[''],0,50)."</option>";
    //      }
            $this->salida .= "</select></td>\n";
            $this->salida .= "      </tr>\n";
            $this->salida .= "      <tr>\n";
            $this->salida .= "          <td width=\"25%\">ESTADO SALIDA:</td>\n";
            $this->salida .= "          <td width=\"25%\"><select name=\"au_estadosalida\" class=\"select\">";
            $this->salida .= "              <option value=\"\">-------SELECCIONE-------</option>";
            $this->salida .= "              <option value=\"1\">VIVO(A)</option>";
            $this->salida .= "              <option value=\"2\">MUERTO(A)</option>";
            $this->salida .= "          </select></td>\n";
            $this->salida .= "          <td width=\"25%\">FECHA SALIDA:</td>\n";
            $this->salida .= "          <td width=\"25%\"><input type=\"text\" name=\"au_fechasalida\" value=\"\" size=\"10\" class=\"input-text\" readonly>\n";
            $this->salida .= "          ".ReturnOpenCalendario('datosadicionalesrips','au_fechasalida','/')."</td>\n";
            $this->salida .= "      </tr>\n";
            $this->salida .= "      <tr>\n";
            $this->salida .= "          <td width=\"25%\">HORA SALIDA:</td>\n";
            $this->salida .= "          <td width=\"25%\">\n";
            //pide hora:minutos
            $this->salida .= "      <select name=\"au_horariosalida\" class=\"select\">";
            $this->salida .= "      <option value=\"-1\">--</option>";
            for($i=0;$i<24;$i++)
            {
                if($i<10)
                {
                    if($_POST['au_horariosalida']=="0$i")
                    {
                        $this->salida .="<option value=\"0$i\" selected>0$i</option>";
                    }
                    else
                    {
                        $this->salida .="<option value=\"0$i\">0$i</option>";
                    }
                }
                else
                {
                    if($_POST['au_horariosalida']=="$i")
                    {
                        $this->salida .="<option value=\"$i\" selected>$i</option>";
                    }
                    else
                    {
                        $this->salida .="<option value=\"$i\">$i</option>";
                    }
                }
            }
            $this->salida .= "      </select>";
            $this->salida .= " : ";
            $this->salida .= "      <select name=\"au_minuterosalida\" class=\"select\">";
            $this->salida .= "      <option value=\"-1\">--</option>";
            for($i=0;$i<60;$i++)
            {
                if($i<10)
                {
                    if($_POST['au_minuterosalida']=="0$i")
                    {
                        $this->salida .="<option value=\"0$i\" selected>0$i</option>";
                    }
                    else
                    {
                        $this->salida .="<option value=\"0$i\">0$i</option>";
                    }
                }
                else
                {
                    if($_POST['au_minuterosalida']=="$i")
                    {
                        $this->salida .="<option value=\"$i\" selected>$i</option>";
                    }
                    else
                    {
                        $this->salida .="<option value=\"$i\">$i</option>";
                    }
                }
            }
            $this->salida .= "      </select>";
        //fin pide hora:minutos
            $this->salida .= "</td>\n";
            $this->salida .= "      </tr>\n";
            $this->salida .= "  </table>\n";
            $this->salida .= "  </fieldset>";
        }//fin au
        $this->salida .= "  <table align=\"center\">\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td>\n";
        $this->salida .= "                  <input type=\"submit\" class=\"input-bottom\" value=\"GUARDAR\">\n";
        $this->salida .= "          </td>\n";
        $this->salida .= "</form>\n";
/*      $accionCancela = ModuloGetUrl("app","Facturacion","user","PideDatosAdicionalesRips");
        $this->salida .= "          <form name=\"frmCancelar\" action=\"$accionCancela\" method = \"post\">\n";
        $this->salida .= "          <input type=\"hidden\" name=\"datos\" value = 'cancela'>";
        $this->salida .= "              <td>\n";
        $this->salida .= "                  <input type=\"submit\" class=\"input-bottom\" value=\"CANCELAR\">\n";
        $this->salida .= "              </td>\n";
        $this->salida .= "          </form>\n";*/
        $this->salida .= "      <tr>\n";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
    *
    */
    function FormaPideDatosAdicionalesRipsAP($cargos_cups)
    {
        $this->salida .= ThemeAbrirTabla("ADICION INFORMACION NECESARIA PARA RIPS CON EL CARGO(AP): ".$cargos_cups);
        $this->EncabezadoEmpresa();
        $this->salida .= "<table align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "</table>";
        $accion = ModuloGeturl("app","Facturacion","user","PideDatosAdicionalesRips");
        $this->salida .= "<form name=\"datosadicionalesrips\" action=\"$accion\" method=\"post\">\n";
        $this->salida .= "  <input type=\"hidden\" name=\"datos\" value = 'adiciona'>";
        $this->salida .= "  <table width=\"50%\" align=\"center\" class=\"label\" >\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td width=\"25%\">FECHA DE CONSULTA:</td>\n";
        $this->salida .= "          <td width=\"25%\"><input type=\"text\" name=\"ap_fechaprocedimiento\" value=\"\" size=\"10\" class=\"input-text\" readonly>\n";
        $this->salida .= "          ".ReturnOpenCalendario('datosadicionalesrips','ap_fechaprocedimiento','/')."</td>\n";
        $this->salida .= "          <td width=\"25%\">CODIGO DE PROCEDIMIENTO:</td>\n";
        $this->salida .= "          <td width=\"25%\">".$cargos_cups."</td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td width=\"25%\">AMBITO DEL PROCEDIMIENTO:</td>\n";
        $this->salida .= "          <td width=\"25%\"><select name=\"ap_ambitoprocedimiento\" class=\"select\">";
    $this->salida .= "              <option value=\"\">-------SELECCIONE-------</option>";
        $this->salida .= "              <option value=\"1\">AMBULATORIO</option>";
        $this->salida .= "              <option value=\"2\">HOSPITALARIO</option>";
        $this->salida .= "              <option value=\"3\">EN URGENCIAS</option>";
        $this->salida .= "          </select></td>\n";
        $this->salida .= "          <td width=\"25%\">FINALIDAD DEL PROCEDIMIENTO:</td>\n";
        $this->salida .= "          <td width=\"25%\"><select name=\"ap_finalidadprocedimiento\" class=\"select\">";
    $this->salida .= "              <option value=\"\">-------SELECCIONE-------</option>";
        $this->salida .= "              <option value=\"1\">DIAGNOSTICO</option>";
        $this->salida .= "              <option value=\"2\">TERAPEUTICO</option>";
        $this->salida .= "              <option value=\"3\">PROTECCION ESPECIFICA</option>";
        $this->salida .= "              <option value=\"4\">DETECCION TEMPRANA DE ENFERMEDAD GENERAL</option>";
        $this->salida .= "          </select></td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td width=\"25%\">NUMERO DE AUTORIZACION</td>\n";
        $this->salida .= "          <td width=\"25%\"><input type=\"text\" name=\"autorizacion\" value=\"1\" size=\"10\" class=\"input-text\"></td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "  </table>\n";
        $this->salida .= "  <table align=\"center\">\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td>\n";
        $this->salida .= "                  <input type=\"submit\" class=\"input-bottom\" value=\"ACEPTAR\">\n";
        $this->salida .= "          </td>\n";
        $this->salida .= "</form>\n";
/*      $accionCancela = ModuloGetUrl("app","Facturacion","user","PideDatosAdicionalesRips");
        $this->salida .= "          <form name=\"frmCancelar\" action=\"$accionCancela\" method = \"post\">\n";
        $this->salida .= "          <input type=\"hidden\" name=\"datos\" value = 'cancela'>";
        $this->salida .= "              <td>\n";
        $this->salida .= "                  <input type=\"submit\" class=\"input-bottom\" value=\"CANCELAR\">\n";
        $this->salida .= "              </td>\n";
        $this->salida .= "          </form>\n";*/
        $this->salida .= "      <tr>\n";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }
    /**
    *
    */
    function FormaPideDatosAdicionalesRipsAT($cargos_cups,$datos_cups)
    {
        $this->salida .= ThemeAbrirTabla("ADICION INFORMACION NECESARIA PARA RIPS CON EL CARGO(AT): ".$cargos_cups);
        $this->EncabezadoEmpresa();
        $this->salida .= "<table align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "</table>";
        $accion = ModuloGeturl("app","Facturacion","user","PideDatosAdicionalesRips");
        $this->salida .= "<form name=\"datosadicionalesrips\" action=\"$accion\" method=\"post\">\n";
        $this->salida .= "  <input type=\"hidden\" name=\"datos\" value = 'adiciona'>";
        $this->salida .= "  <table width=\"50%\" align=\"center\" class=\"label\" >\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td width=\"25%\">NUMERO DE AUTORIZACION</td>\n";
        $this->salida .= "          <td width=\"25%\"><input type=\"text\" name=\"autorizacion\" value=\"1\" size=\"10\" class=\"input-text\"></td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td width=\"25%\">TIPO DE SERVICIO:</td>\n";
        $this->salida .= "          <td width=\"25%\"><input type=\"text\" name=\"at_tiposervicio\" value=\"".$datos_cups['tipo_servicio']."\" size=\"10\" class=\"input-text\">".$datos_cups[tipo_servicio_descripcion]."</td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "  </table>\n";
        $this->salida .= "  <table align=\"center\">\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td>\n";
        $this->salida .= "                  <input type=\"submit\" class=\"input-bottom\" value=\"ACEPTAR\">\n";
        $this->salida .= "          </td>\n";
        $this->salida .= "</form>\n";
/*      $accionCancela = ModuloGetUrl("app","Facturacion","user","PideDatosAdicionalesRips");
        $this->salida .= "          <form name=\"frmCancelar\" action=\"$accionCancela\" method = \"post\">\n";
        $this->salida .= "          <input type=\"hidden\" name=\"datos\" value = 'cancela'>";
        $this->salida .= "              <td>\n";
        $this->salida .= "                  <input type=\"submit\" class=\"input-bottom\" value=\"CANCELAR\">\n";
        $this->salida .= "              </td>\n";
        $this->salida .= "          </form>\n";*/
        $this->salida .= "      <tr>\n";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }


    function FormaPideDatosAdicionalesRipsAU($cargos_cups,$datos_cups)
    {
        $titulocampos = "&titulo[0]=CODIGO&titulo[1]=DESCRIPCION";
        $nombrecampossql = "&campossql[0]=diagnostico_id&campossql[1]=diagnostico_nombre";

        $action7  = "classes/BuscadorConsulta/BuscadorHtml.class.php?buscador=diagnosticos&forma=datosadicionalesrips";
        $action7 .= $nombrecampos.$nombrecampossql.$titulocampos;
        $tiposcausaexterna = $this->ConsultaCausaExterna();
        $this->salida .= ThemeAbrirTabla("ADICION INFORMACION NECESARIA PARA RIPS PARA EL CARGO(AU): ".$cargos_cups);
        $this->EncabezadoEmpresa();
        $this->salida .= "<table align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "</table>";
        $accion = ModuloGeturl("app","Facturacion","user","PideDatosAdicionalesRips");
        $this->salida .= "<form name=\"datosadicionalesrips\" action=\"$accion\" method=\"post\">\n";
            $this->salida .= "\n<script>\n";
            $this->salida .= "var rem=\"\";\n";
            $this->salida .= "  function abrirVentana(campo1,campo2){\n";
            $this->salida .= "      var nombre='';\n";
            $this->salida .= "      var url2='';\n";
            $this->salida .= "      var str='';\n";
            $this->salida .= "      var ALTO=screen.height;\n";
            $this->salida .= "      var ANCHO=screen.width;\n";
            $this->salida .= "      nombre=\"buscador_General\";\n";
            $this->salida .= "      str =\"ANCHO,ALTO,resizable=no,status=no,scrollbars=yes\";\n";
            $this->salida .= "      url2 = '$action7&campos[0]='+campo1+'&campos[1]='+campo2;\n";
            $this->salida .= "      rem = window.open(url2, nombre, str);\n";
            $this->salida .= "  }\n";
            $this->salida .= "</script>\n";
        $this->salida .= "  <input type=\"hidden\" name=\"datos\" value = 'adiciona'>";
        $this->salida .= "  <table width=\"50%\" align=\"center\" class=\"label\" >\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td width=\"25%\">FECHA DE INGRESO:</td>\n";
        $this->salida .= "          <td width=\"25%\"><input type=\"text\" name=\"au_fechaingreso\" value=\"\" size=\"10\" class=\"input-text\" readonly>\n";
        $this->salida .= "          ".ReturnOpenCalendario('datosadicionalesrips','au_fechaingreso','/')."</td>\n";
        $this->salida .= "          <td width=\"25%\">HORA DE INGRESO:</td>\n";
        $this->salida .= "          <td width=\"25%\">\n";
        //pide hora:minutos
        $this->salida .= "      <select name=\"au_horarioingreso\" class=\"select\">\n";
        $this->salida .= "      <option value=\"-1\">--</option>\n";
        for($i=0;$i<24;$i++)
        {
            if($i<10)
            {
                if($_POST['au_horarioingreso']=="0$i")
                {
                    $this->salida .="<option value=\"0$i\" selected>0$i</option>\n";
                }
                else
                {
                    $this->salida .="<option value=\"0$i\">0$i</option>\n";
                }
            }
            else
            {
                if($_POST['au_horarioingreso']=="$i")
                {
                    $this->salida .="<option value=\"$i\" selected>$i</option>\n";
                }
                else
                {
                    $this->salida .="<option value=\"$i\">$i</option>\n";
                }
            }
        }
        $this->salida .= "      </select>\n";
        $this->salida .= " : ";
        $this->salida .= "      <select name=\"au_minuteroingreso\" class=\"select\">\n";
        $this->salida .= "      <option value=\"-1\">--</option>\n";
        for($i=0;$i<60;$i++)
        {
            if($i<10)
            {
                if($_POST['au_minuteroingreso']=="0$i")
                {
                    $this->salida .="<option value=\"0$i\" selected>0$i</option>\n";
                }
                else
                {
                    $this->salida .="<option value=\"0$i\">0$i</option>\n";
                }
            }
            else
            {
                if($_POST['au_minuteroingreso']=="$i")
                {
                    $this->salida .="<option value=\"$i\" selected>$i</option>\n";
                }
                else
                {
                    $this->salida .="<option value=\"$i\">$i</option>\n";
                }
            }
        }
        $this->salida .= "      </select>\n";
        //fin pide hora:minutos
        $this->salida .= "          </td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td width=\"25%\">NUMERO DE AUTORIZACION</td>\n";
        $this->salida .= "          <td width=\"25%\"><input type=\"text\" name=\"autorizacion\" value=\"1\" size=\"10\" class=\"input-text\"></td>\n";
        $this->salida .= "          <td width=\"25%\">CAUSA EXTERNA:</td>\n";
        $this->salida .= "          <td width=\"25%\"><select name=\"au_causaexterna\" class=\"select\">";
    $this->salida .= "          <option value=\"\">-------SELECCIONE-------</option>";
        foreach($tiposcausaexterna as $tiposC => $tipos)
        {
            $this->salida .="     <option value=\"".$tipos['causa_externa_id']."\" selected>".substr($tipos['descripcion'],0,50)."</option>";
        }
    $this->salida .= "          </select></td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td width=\"25%\">DIAGNOSTICO SALIDA:</td>\n";
            $this->salida .= "          <td width=\"20%\"  colspan=\"1\">";
            $this->salida .= "              <table width=\"100%\" align=\"center\" class=\"label\" >\n";
            $this->salida .= "                  <tr>\n";
            $this->salida .= "                      <td width=\"20%\"><input type=\"text\" name=\"au_DiagnosticoSalida\" value=\"\" size=\"10\" class=\"input-text\" readonly></td>\n";
            $this->salida .= "                      <td width=\"75%\"><input type=\"text\" name=\"au_DiagnosticoSalida_descripcion\" value=\"\" size=\"30\" class=\"input-text\" readonly></td>\n";
            $this->salida .= "                      <td width=\"5%\"><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"BUSCAR\" onclick=abrirVentana('au_DiagnosticoSalida','au_DiagnosticoSalida_descripcion')></td>\n";
            $this->salida .= "                  </tr>\n";
            $this->salida .= "              </table>";
            $this->salida .= "          </td>";
        $this->salida .= "          <td width=\"25%\">DESTINO SALIDA:</td>\n";
        $this->salida .= "          <td width=\"25%\"><select name=\"au_destinosalida\" class=\"select\">";
    $this->salida .= "          <option value=\"\">-------SELECCIONE-------</option>";
//      foreach($tiposdestinosalida as $tiposD => $tipos)
//      {
//          $this->salida .="     <option value=\"".$tipos['']."\" selected>".substr($tipos[''],0,50)."</option>";
//      }
    $this->salida .= "</select></td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td width=\"25%\">ESTADO SALIDA:</td>\n";
        $this->salida .= "          <td width=\"25%\"><select name=\"au_estadosalida\" class=\"select\">";
    $this->salida .= "              <option value=\"\">-------SELECCIONE-------</option>";
        $this->salida .= "              <option value=\"1\">VIVO(A)</option>";
        $this->salida .= "              <option value=\"2\">MUERTO(A)</option>";
        $this->salida .= "          </select></td>\n";
        $this->salida .= "          <td width=\"25%\">FECHA SALIDA:</td>\n";
        $this->salida .= "          <td width=\"25%\"><input type=\"text\" name=\"au_fechasalida\" value=\"\" size=\"10\" class=\"input-text\" readonly>\n";
        $this->salida .= "          ".ReturnOpenCalendario('datosadicionalesrips','au_fechasalida','/')."</td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td width=\"25%\">HORA SALIDA:</td>\n";
        $this->salida .= "          <td width=\"25%\">\n";
        //pide hora:minutos
        $this->salida .= "      <select name=\"au_horariosalida\" class=\"select\">";
        $this->salida .= "      <option value=\"-1\">--</option>";
        for($i=0;$i<24;$i++)
        {
            if($i<10)
            {
                if($_POST['au_horariosalida']=="0$i")
                {
                    $this->salida .="<option value=\"0$i\" selected>0$i</option>";
                }
                else
                {
                    $this->salida .="<option value=\"0$i\">0$i</option>";
                }
            }
            else
            {
                if($_POST['au_horariosalida']=="$i")
                {
                    $this->salida .="<option value=\"$i\" selected>$i</option>";
                }
                else
                {
                    $this->salida .="<option value=\"$i\">$i</option>";
                }
            }
        }
        $this->salida .= "      </select>";
        $this->salida .= " : ";
        $this->salida .= "      <select name=\"au_minuterosalida\" class=\"select\">";
        $this->salida .= "      <option value=\"-1\">--</option>";
        for($i=0;$i<60;$i++)
        {
            if($i<10)
            {
                if($_POST['au_minuterosalida']=="0$i")
                {
                    $this->salida .="<option value=\"0$i\" selected>0$i</option>";
                }
                else
                {
                    $this->salida .="<option value=\"0$i\">0$i</option>";
                }
            }
            else
            {
                if($_POST['au_minuterosalida']=="$i")
                {
                    $this->salida .="<option value=\"$i\" selected>$i</option>";
                }
                else
                {
                    $this->salida .="<option value=\"$i\">$i</option>";
                }
            }
        }
        $this->salida .= "      </select>";
    //fin pide hora:minutos
        $this->salida .= "</td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "  </table>\n";
        $this->salida .= "  <table align=\"center\">\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td>\n";
        $this->salida .= "                  <input type=\"submit\" class=\"input-bottom\" value=\"ACEPTAR\">\n";
        $this->salida .= "          </td>\n";
        $this->salida .= "</form>\n";
/*      $accionCancela = ModuloGetUrl("app","Facturacion","user","PideDatosAdicionalesRips");
        $this->salida .= "          <form name=\"frmCancelar\" action=\"$accionCancela\" method = \"post\">\n";
        $this->salida .= "          <input type=\"hidden\" name=\"datos\" value = 'cancela'>";
        $this->salida .= "              <td>\n";
        $this->salida .= "                  <input type=\"submit\" class=\"input-bottom\" value=\"CANCELAR\">\n";
        $this->salida .= "              </td>\n";
        $this->salida .= "          </form>\n";*/
        $this->salida .= "      <tr>\n";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
    *FormaPideDatosAdicionalesRipsAH
    *   @var cargos_cups cargo base del sistema
    *   @var datos_cups
    *   @var viasingreso
    */
    //FormaPideDatosAdicionalesRipsAH
    function FormaPideDatosAdicionalesRipsAH($cargos_cups,$datos_cups,$viasingreso)
    {
        $titulocampos = "&titulo[0]=CODIGO&titulo[1]=DESCRIPCION";
        $nombrecampossql = "&campossql[0]=diagnostico_id&campossql[1]=diagnostico_nombre";

        $action7  = "classes/BuscadorConsulta/BuscadorHtml.class.php?buscador=diagnosticos&forma=datosadicionalesrips";
        $action7 .= $nombrecampos.$nombrecampossql.$titulocampos;

        $tiposcausaexterna = $this->ConsultaCausaExterna();

        $this->salida .= ThemeAbrirTabla("ADICION INFORMACION NECESARIA PARA RIPS PARA EL CARGO(AH): ".$cargos_cups);
        $this->EncabezadoEmpresa();
        $this->salida .= "<table align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "</table>";
        $accion = ModuloGeturl("app","Facturacion","user","PideDatosAdicionalesRips");
        $this->salida .= "<form name=\"datosadicionalesrips\" action=\"$accion\" method=\"post\">\n";
            $this->salida .= "\n<script>\n";
            $this->salida .= "var rem=\"\";\n";
            $this->salida .= "  function abrirVentana(ncodigo,ndescripcion){\n";
            $this->salida .= "      var nombre='';\n";
            $this->salida .= "      var url2='';\n";
            $this->salida .= "      var str='';\n";
            $this->salida .= "      var ALTO=screen.height;\n";
            $this->salida .= "      var ANCHO=screen.width;\n";
            $this->salida .= "      nombre=\"buscador_General\";\n";
            $this->salida .= "      str =\"ANCHO,ALTO,resizable=no,status=no,scrollbars=yes\";\n";
        //    $this->salida .= "      url2 ='".$_ROOT."classes/classbuscador/buscador.php?tipo=diagnostico&forma=datosadicionalesrips';\n";
 	    $this->salida .= "      url2 ='$action7&campos[0]='+ncodigo+'&campos[1]='+ndescripcion;\n";
						  
            $this->salida .= "      rem = window.open(url2, nombre, str);\n";
            $this->salida .= "  }\n";
            $this->salida .= "</script>\n";
        $this->salida .= "  <input type=\"hidden\" name=\"datos\" value = 'adiciona'>";
        $this->salida .= "  <table width=\"50%\" align=\"center\" class=\"label\" >\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td width=\"25%\">VIAS DE INGRESO:</td>\n";
        $this->salida .= "          <td width=\"25%\"><select name=\"ah_ViaIngreso\" class=\"select\">";
    $this->salida .= "          <option value=\"\">-------SELECCIONE-------</option>";
        foreach($viasingreso as $viasI => $vias)
        {
            $this->salida .="     <option value=\"".$vias['via_ingreso_id']."\" selected>".substr($vias['via_ingreso_nombre'],0,50)."</option>";
        }
    $this->salida .= "          </select></td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td width=\"25%\">FECHA DE INGRESO:</td>\n";
        $this->salida .= "          <td width=\"25%\"><input type=\"text\" name=\"ah_fechaingreso\" value=\"\" size=\"10\" class=\"input-text\" readonly>\n";
        $this->salida .= "          ".ReturnOpenCalendario('datosadicionalesrips','ah_fechaingreso','/')."</td>\n";
        $this->salida .= "          <td width=\"25%\">HORA DE INGRESO:</td>\n";
        $this->salida .= "          <td width=\"25%\">\n";
        //pide hora:minutos
        $this->salida .= "      <select name=\"ah_horarioingreso\" class=\"select\">\n";
        $this->salida .= "      <option value=\"-1\">--</option>\n";
        for($i=0;$i<24;$i++)
        {
            if($i<10)
            {
                if($_POST['ah_horarioingreso']=="0$i")
                {
                    $this->salida .="<option value=\"0$i\" selected>0$i</option>\n";
                }
                else
                {
                    $this->salida .="<option value=\"0$i\">0$i</option>\n";
                }
            }
            else
            {
                if($_POST['ah_horarioingreso']=="$i")
                {
                    $this->salida .="<option value=\"$i\" selected>$i</option>\n";
                }
                else
                {
                    $this->salida .="<option value=\"$i\">$i</option>\n";
                }
            }
        }
        $this->salida .= "      </select>\n";
        $this->salida .= " : ";
        $this->salida .= "      <select name=\"ah_minuteroingreso\" class=\"select\">\n";
        $this->salida .= "      <option value=\"-1\">--</option>\n";
        for($i=0;$i<60;$i++)
        {
            if($i<10)
            {
                if($_POST['ah_minuteroingreso']=="0$i")
                {
                    $this->salida .="<option value=\"0$i\" selected>0$i</option>\n";
                }
                else
                {
                    $this->salida .="<option value=\"0$i\">0$i</option>\n";
                }
            }
            else
            {
                if($_POST['ah_minuteroingreso']=="$i")
                {
                    $this->salida .="<option value=\"$i\" selected>$i</option>\n";
                }
                else
                {
                    $this->salida .="<option value=\"$i\">$i</option>\n";
                }
            }
        }
        $this->salida .= "      </select>\n";
        //fin pide hora:minutos
        $this->salida .= "          </td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td width=\"25%\">NUMERO DE AUTORIZACION</td>\n";
        $this->salida .= "          <td width=\"25%\"><input type=\"text\" name=\"autorizacion\" value=\"1\" size=\"10\" class=\"input-text\"></td>\n";
        $this->salida .= "          <td width=\"25%\">CAUSA EXTERNA:</td>\n";
        $this->salida .= "          <td width=\"25%\"><select name=\"ah_causaexterna\" class=\"select\">";
    $this->salida .= "          <option value=\"\">-------SELECCIONE-------</option>";
        foreach($tiposcausaexterna as $tiposC => $tipos)
        {
            $this->salida .="     <option value=\"".$tipos['causa_externa_id']."\" selected>".substr($tipos['descripcion'],0,50)."</option>";
        }
    $this->salida .= "          </select></td>\n";
        $this->salida .= "      </tr>\n";
        //DIAGNOSTICO INGRESO ah_diagnosticoingreso
            $this->salida .= "          <td width=\"25%\">DIAGNOSTICO INGRESO:</td>\n";
            $this->salida .= "          <td width=\"20%\"  colspan=\"1\">";
            $this->salida .= "              <table width=\"100%\" align=\"center\" class=\"label\" >\n";
            $this->salida .= "                  <tr>\n";
            $this->salida .= "                      <td width=\"20%\"><input type=\"text\" name=\"ah_diagnosticoingreso\" value=\"\" size=\"10\" class=\"input-text\"></td>\n";
            $this->salida .= "                      <td width=\"75%\"><input type=\"text\" name=\"ah_diagnosticoingreso_descripcion\" value=\"\" size=\"30\" class=\"input-text\" readonly></td>\n";
            $this->salida .= "                      <td width=\"5%\"><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"BUSCAR\" onclick=abrirVentana('ah_diagnosticoingreso','ah_diagnosticoingreso_descripcion')></td>\n";
            $this->salida .= "                  </tr>\n";
            $this->salida .= "              </table>";
            $this->salida .= "          </td>";
            $this->salida .= "      </tr>\n";
            //FIN DIAGNOSTICO INGRESO
            //DIAGNOSTICO SALIDA ah_diagnosticosalida
            $this->salida .= "      <tr>\n";
            $this->salida .= "          <td width=\"25%\">DIAGNOSTICO SALIDA:</td>\n";
            $this->salida .= "          <td width=\"20%\"  colspan=\"1\">";
            $this->salida .= "              <table width=\"100%\" align=\"center\" class=\"label\" >\n";
            $this->salida .= "                  <tr>\n";
            $this->salida .= "                      <td width=\"20%\"><input type=\"text\" name=\"ah_diagnosticosalida\" value=\"\" size=\"10\" class=\"input-text\"></td>\n";
            $this->salida .= "                      <td width=\"75%\"><input type=\"text\" name=\"ah_diagnosticosalida_descripcion\" value=\"\" size=\"30\" class=\"input-text\" readonly></td>\n";
            $this->salida .= "                      <td width=\"5%\"><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"BUSCAR\" onclick=abrirVentana('ah_diagnosticosalida','ah_diagnosticosalida_descripcion')></td>\n";
            $this->salida .= "                  </tr>\n";
            $this->salida .= "              </table>";
            $this->salida .= "          </td>";
            //FIN DIAGNOSTICO SALIDA
        $this->salida .= "          <td width=\"25%\">ESTADO SALIDA:</td>\n";
        $this->salida .= "          <td width=\"25%\"><select name=\"ah_estadosalida\" class=\"select\">";
    $this->salida .= "              <option value=\"\">-------SELECCIONE-------</option>";
        $this->salida .= "              <option value=\"1\">VIVO(A)</option>";
        $this->salida .= "              <option value=\"2\">MUERTO(A)</option>";
        $this->salida .= "          </select></td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td width=\"25%\">FECHA SALIDA:</td>\n";
        $this->salida .= "          <td width=\"25%\"><input type=\"text\" name=\"ah_fechasalida\" value=\"\" size=\"10\" class=\"input-text\" readonly>\n";
        $this->salida .= "          ".ReturnOpenCalendario('datosadicionalesrips','ah_fechasalida','/')."</td>\n";
        $this->salida .= "          <td width=\"25%\">HORA SALIDA:</td>\n";
        $this->salida .= "          <td width=\"25%\">\n";
        //pide hora:minutos
        $this->salida .= "      <select name=\"ah_horariosalida\" class=\"select\">";
        $this->salida .= "      <option value=\"-1\">--</option>";
        for($i=0;$i<24;$i++)
        {
            if($i<10)
            {
                if($_POST['ah_horariosalida']=="0$i")
                {
                    $this->salida .="<option value=\"0$i\" selected>0$i</option>";
                }
                else
                {
                    $this->salida .="<option value=\"0$i\">0$i</option>";
                }
            }
            else
            {
                if($_POST['ah_horariosalida']=="$i")
                {
                    $this->salida .="<option value=\"$i\" selected>$i</option>";
                }
                else
                {
                    $this->salida .="<option value=\"$i\">$i</option>";
                }
            }
        }
        $this->salida .= "      </select>";
        $this->salida .= " : ";
        $this->salida .= "      <select name=\"ah_minuterosalida\" class=\"select\">";
        $this->salida .= "      <option value=\"-1\">--</option>";
        for($i=0;$i<60;$i++)
        {
            if($i<10)
            {
                if($_POST['ah_minuterosalida']=="0$i")
                {
                    $this->salida .="<option value=\"0$i\" selected>0$i</option>";
                }
                else
                {
                    $this->salida .="<option value=\"0$i\">0$i</option>";
                }
            }
            else
            {
                if($_POST['ah_minuterosalida']=="$i")
                {
                    $this->salida .="<option value=\"$i\" selected>$i</option>";
                }
                else
                {
                    $this->salida .="<option value=\"$i\">$i</option>";
                }
            }
        }
        $this->salida .= "      </select>";
    //fin pide hora:minutos
        $this->salida .= "</td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "  </table>\n";
        $this->salida .= "  <table align=\"center\">\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td>\n";
        $this->salida .= "                  <input type=\"submit\" class=\"input-bottom\" value=\"ACEPTAR\">\n";
        $this->salida .= "          </td>\n";
        $this->salida .= "</form>\n";
/*      $accionCancela = ModuloGetUrl("app","Facturacion","user","PideDatosAdicionalesRips");
        $this->salida .= "          <form name=\"frmCancelar\" action=\"$accionCancela\" method = \"post\">\n";
        $this->salida .= "          <input type=\"hidden\" name=\"datos\" value = 'cancela'>";
        $this->salida .= "              <td>\n";
        $this->salida .= "                  <input type=\"submit\" class=\"input-bottom\" value=\"CANCELAR\">\n";
        $this->salida .= "              </td>\n";
        $this->salida .= "          </form>\n";*/
        $this->salida .= "      <tr>\n";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }
    //FIN FormaPideDatosAdicionalesRipsAN

    /**
    *
    */
    function FormaPideDatosAdicionalesRipsAM($cargos_cups,$datos_cups)
    {
        $this->salida .= ThemeAbrirTabla("ADICION INFORMACION NECESARIA PARA RIPS PARA EL CARGO(AM): ".$cargos_cups);
        $this->EncabezadoEmpresa();
        $this->salida .= "<table align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "</table>";
        $accion = ModuloGeturl("app","Facturacion","user","PideDatosAdicionalesRips");
        $this->salida .= "<form name=\"datosadicionalesrips\" action=\"$accion\" method=\"post\">\n";
        $this->salida .= "  <input type=\"hidden\" name=\"datos\" value = 'adiciona'>";
        $this->salida .= "  <table width=\"50%\" align=\"center\" class=\"label\" >\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td>\n";
        $this->salida .= "          </td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "  </table>\n";
        $this->salida .= "  <table align=\"center\">\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td>\n";
        $this->salida .= "                  <input type=\"submit\" class=\"input-bottom\" value=\"ACEPTAR\">\n";
        $this->salida .= "          </td>\n";
        $this->salida .= "</form>\n";
        $accionCancela = ModuloGetUrl("app","Facturacion","user","PideDatosAdicionalesRips");
        $this->salida .= "          <form name=\"frmCancelar\" action=\"$accionCancela\" method = \"post\">\n";
        $this->salida .= "          <input type=\"hidden\" name=\"datos\" value = 'cancela'>";
        $this->salida .= "              <td>\n";
        $this->salida .= "                  <input type=\"submit\" class=\"input-bottom\" value=\"CANCELAR\">\n";
        $this->salida .= "              </td>\n";
        $this->salida .= "          </form>\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }
  /**
  * Forma para la impresion de hojas de cargos.
  * @access private
  * @return boolean
  * @param string mensaje
  * @param string nombre de la ventana
  * @param string accion de la forma
  * @param string nombre del boton
  */
  function FormaMensajeImprimirHojasCargos($mensaje,$titulo,$accion,$boton,$botonC,$arreglo)
  {
      //IncludeLib('funciones_facturacion');
            if($botonC)
            {
                    if($botonC=='reportes')
                    {
                        $this->salida .= ThemeAbrirTabla($titulo,"50%")."<br>";
                        $this->salida .= "<table width=\"68%\" align=\"center\" class=\"normal_10\" border='0'>\n";
                        $this->salida .= "    <form name=\"formaMensaje\" action=\"$accion\" method=\"post\">\n";
                        $this->salida .= "        <tr><td colspan=\"4\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>\n";
                        if(!empty($boton)){
                                $this->salida .= "    <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"<<$boton\"></td>\n";
                        }
                        else{
                                $this->salida .= "    <tr><td colspan=\"2\" align=\"center\">&nbsp;</td></tr>\n";
                        }
                        $this->salida .= "    </form>\n";
                        $reporte = explode('/',$arreglo['ruta_hoja']);

                        $RUTA = $_ROOT ."cache/".$reporte[1].$arreglo['cuenta'].".pdf";
                        $mostrar = "<script>\n";
                        $mostrar.="     var rem=\"\";\n";
                        $mostrar.="     function abreVentanaHC()\n";
                        $mostrar.="     {\n";
                        $mostrar.="         var nombre=\"\"\n";
                        $mostrar.="         var url2=\"\"\n";
                        $mostrar.="         var str=\"\"\n";
                        $mostrar.="         var ALTO=screen.height\n";
                        $mostrar.="         var ANCHO=screen.width\n";
                        $mostrar.="         var nombre=\"REPORTE\";\n";
                        $mostrar.="         var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
                        $mostrar.="         var url2 ='$RUTA';\n";
                        $mostrar.="         rem = window.open(url2, nombre, str);\n";
                        $mostrar.="     }\n";
                        $mostrar.="</script>\n";
                        $this->salida.="$mostrar";

                        IncludeLib($arreglo['ruta_hoja']);
                        $funcion = 'Generar'.$reporte[1];
            $funcion (array('numerodecuenta'=>$arreglo['cuenta']));
            $this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"Vista Preliminar\" onclick=\"javascript:abreVentanaHC()\"></td>";
                    }
                $this->salida .= "</table>\n";
                $this->salida .= themeCerrarTabla();
            }
            else
            {
        $this->salida .= ThemeAbrirTabla($titulo);
        $this->salida .= "            <table width=\"60%\" align=\"center\" >";
        $this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "               <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
        $this->salida .= "               <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"$boton\"></td></tr>";
        $this->salida .= "           </form>";
        $this->salida .= "           </table>";
        $this->salida .= ThemeCerrarTabla();
            }
        return true;
  }
	
	function FrmGeneracionReportes()
	{
		$accion1=ModuloGetURL('app','Facturacion','user','FrmMenuCenso');
		$accion2=ModuloGetURL('app','Facturacion','user','FrmListadoCenso',array('opcion'=>1,'enlace'=>2));
		$accion3=ModuloGetURL('app','Facturacion','user','FrmListadoCenso',array('opcion'=>2,'enlace'=>2));
		$accion4=ModuloGetURL('app','Facturacion','user','FrmListadoCenso',array('opcion'=>3,'enlace'=>2));
		$accion5=ModuloGetURL('app','Facturacion','user','FrmConsultaPacientesTP');
		$accion6=ModuloGetURL('app','Facturacion','user','FrmTotalFacturaCredito');
		
		$this->salida .= ThemeAbrirTabla("REPORTES");
		$this->salida .= "<table width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "	<tr class=\"modulo_table_list_title\">";
		$this->salida .= "		<td  align=\"center\">REPORTES</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"label\" align=\"center\"><label class=\"label\"><a href=\"$accion1\">REPORTE CENSO</a></label></td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "		<td class=\"label\" align=\"center\"><label class=\"label\"><a href=\"$accion5\">REPORTE PACIENTES - CUENTAS</a></label></td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"label\" align=\"center\"><label class=\"label\"><a href=\"$accion2\">REPORTE CUENTAS ACTIVAS</a></label></td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "		<td class=\"label\" align=\"center\"><label class=\"label\"><a href=\"$accion3\">REPORTE CUENTAS INACTIVAS</a></label></td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"label\" align=\"center\"><label class=\"label\"><a href=\"$accion4\">REPORTE CUENTAS ACTIVAS E INACTIVAS</a></label></td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "		<td class=\"label\" align=\"center\"><label 		class=\"label\"><a href=\"$accion6\">REPORTE TOTAL FACTURAS CREDITO</a></label></td>";
		$this->salida .= "	</tr>";
		$this->salida .= "</table>";
		

		
		$accionV = ModuloGetURL('app','Facturacion','user','FormaMenus');
		$this->salida .= "<form name=\"formavolver\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"></p>";
		$this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		
		return true;
	}
	
	function DatosEmpresa()
  	{
      	$datos=$this->DatosEncabezadoEmpresa();
      	$this->salida .= "<br>\n";
      	$this->salida .= "	<table  border=\"0\" class=\"modulo_table_list\" width=\"80%\" align=\"center\" >\n";
      	$this->salida .= " 		<tr class=\"modulo_table_title\" height=\"21\">\n";
      	$this->salida .= " 			<td width=\"10%\">EMPRESA</td>\n";
      	$this->salida .= " 			<td class=\"modulo_list_claro\" >".$datos[razon_social]."</td>\n";
      	$this->salida .= " 		</tr>\n";
      	$this->salida .= " </table>\n";
        }
	
	

	function FrmTotalFacturaCredito ()
	{
	 $accion1= ModuloGetURL('app','Facturacion','user','FrmReporteFC');
	 $accion2=ModuloGetURL('app','Facturacion','user','FrmGeneracionReportes');
	 $planes=$this->GetPlanes();
	 $this->salida .= ThemeAbrirTabla("REPORTES TOTAL FACTURAS CREDITO");
	 $this->salida .= "            <form name=\"BUSCAR1\" action=\"".$accion1."\" method=\"post\">\n";
         $this->salida .= "                 <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";         
         $this->salida .= "			<tr align=\"left\" class=\"modulo_list_claro\">\n";
	 $this->salida .= "                       <td>";
         $this->salida .= "                         DIGITE EL PLAN A BUSCAR ";
         $this->salida .= "                       </td>\n";
	 $this->salida .= "			<td>";
	 $this->salida .= "		            <select width=\"40%\" name=\"planes\" class=\"select\">";
	 $this->salida .= "			    <option value=\"\">--PLAN--</option>\n";
						    foreach($planes as $plan)
						    {
						     $sel="";
						     if($plan['plan_id']==$_REQUEST['planes'])
						     $sel="selected";
						     $this->salida .= "					<option value=\"".$plan['plan_id']."\" $sel>".$plan['plan_descripcion']."</option>\n";
						    }
	$this->salida .= "			    </select>\n";
	$this->salida .= "			</td>\n";
	$this->salida .= "			</tr>\n";
        
	$this->salida .= "			<tr align=\"center\" class=\"modulo_list_claro\">\n";
	if(!empty($_REQUEST['FechaI']))
				{
							$f=explode('-',$_REQUEST['FechaI']);
							$i=$f[2].'/'.$f[1].'/'.$f[0];
				}
				$this->salida .= "                    <td class=\"".$this->SetStyle("FechaI")."\">DESDE: </td>";
				$this->salida .= "                    <td <input type=\"text\" class=\"input-text\" name=\"FechaI\" value=\"".$i."\">".ReturnOpenCalendario('BUSCAR1','FechaI','/')."</td>";
	$this->salida .= "			</tr>";	
		
	$this->salida .= "			<tr align=\"center\" class=\"modulo_list_claro\">\n";
	if(!empty($_REQUEST['FechaF']))
				{
							$f=explode('-',$_REQUEST['FechaF']);
							$fi=$f[2].'/'.$f[1].'/'.$f[0];
				}
				$this->salida .= "                    <td class=\"".$this->SetStyle("FechaF")."\">HASTA: </td>";
				$this->salida .= "                    <td <input type=\"text\" class=\"input-text\" name=\"FechaF\" value=\"".$fi."\">".ReturnOpenCalendario('BUSCAR1','FechaF','/')."</td>";
	$this->salida .= "			</tr>\n";
	$this->salida .= "                       <tr colspan=\"11\" class=\"modulo_list_claro\" align=\"center\">\n";
	$this->salida .= "                       <td>";
        $this->salida .= "                          <input type=\"submit\" class=\"input-submit\" value=\"BUSCAR\">\n";
        $this->salida .= "                       </td>";
	$this->salida .= "		</form>\n";
	$this->salida .= "		<form name=\"VOLVER\" action=\"".$accion2."\" method=\"post\">\n";
	$this->salida .= "                       <td>";
	$this->salida .= "                          <input type=\"submit\" class=\"input-submit\" value=\"VOLVER\">\n";
        $this->salida .= "                       </td>\n";
       	$this->salida .= "                     </tr>\n";
        $this->salida .= "                 </table>\n";         
        $this->salida .= "		</form>\n";
        $this->salida .= ThemeCerrarTabla();
	return true;
	}
	
	
	function FrmReporteFC ()
	{
	$consulta=new Facturacion();
      	$vector = $consulta->Totalfacturascredito($_REQUEST['planes'],$_REQUEST['FechaI'],$_REQUEST['FechaF']);
	$accion1= ModuloGetURL('app','Facturacion','user','FrmTotalFacturaCredito');
	$RUTA = "app_modules/Facturacion/reports/html/Total_FacturasCredito.report.php?plan=".$_REQUEST['planes']."&fechai=".$_REQUEST['FechaI']."&fechaf=".$_REQUEST['FechaF']."";
	$mostrar.="   <script>";
	$mostrar.="  function abreVentanaTotalFC(){\n";
        $mostrar.="    var nombre=\"\"\n";
        $mostrar.="    var url2=\"\"\n";
        $mostrar.="    var str=\"\"\n";
        $mostrar.="    var ALTO=screen.height\n";
        $mostrar.="    var ANCHO=screen.width\n";
        $mostrar.="    var nombre=\"REPORTE\";\n";
        $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes,toolbar=1\";\n";
        $mostrar.="    var url2 ='$RUTA';\n";
        $mostrar.="    window.open(url2, nombre, str)};\n";
	$mostrar.="   </script>";
	$this->salida .= $mostrar;
	$this->salida .= ThemeAbrirTabla("REPORTE TOTAL FACTURAS CREDITO");
	$this->salida .= "            <form name=\"VOLVER2\" action=\"".$accion1."\" method=\"post\">\n";
        $this->salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";         
        $this->DatosEmpresa();
	$this->salida .= " </table>";
	$this->salida .= "<br><table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
	$this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
	$this->salida .= "        <td>INGRESO No.</td>";
	$this->salida .= "        <td>PACIENTE</td>";
	$this->salida .= "        <td>IDENTIFICACION</td>";
	$this->salida .= "        <td>CUENTA No.</td>";
	$this->salida .= "        <td>FECHA INGRESO</td>";
	$this->salida .= "        <td>FECHA EGRESO</td>";
	$this->salida .= "        <td>FACTURA No.</td>";
	$this->salida .= "        <td>VALOR FACTURA</td>";
	$this->salida .= "        <td>VALOR PAGADO PACIENTE</td>";
	$this->salida .= "        <td>ESTADO FACTURA</td>";
	$this->salida .= "        <td>PLAN</td>";
	$this->salida .= "      </tr>";
	//var_dump($vector);
	for($i=0;$i<sizeof($vector);$i++)
				{
						if( $i % 2){ $estilo='modulo_list_claro';}
						else {$estilo='modulo_list_oscuro';}
						$pago= $vector[$i]['abono_efectivo']+$vector[$i]['abono_cheque']+$vector[$i]['abono_tarjetas']+$vector[$i]['abono_chequespf']+$vector[$i]['abono_letras']+$vector[$i]['valor_cuota_paciente'];
						$this->salida .= "      <tr class=\"$estilo\">";
						$this->salida .= "        <td align=\"center\">".$vector[$i]['ingreso']."</td>";
						$this->salida .= "        <td align=\"center\">".$vector[$i]['primer_nombre']."  ".  $vector[$i]['segundo_nombre']."  ".  $vector[$i]['primer_apellido']."  ".  $vector[$i]['segundo_apellido']."</td>";
						$this->salida .= "        <td align=\"center\">".$vector[$i]['paciente_id']."</td>";
						$this->salida .= "        <td align=\"center\">".$vector[$i]['numerodecuenta']."</td>";
						$this->salida .= "        <td align=\"center\">".$vector[$i]['fecha_ingreso']."</td>";
						$this->salida .= "        <td align=\"center\">".$vector[$i]['fecha_cierre']."</td>";
						$this->salida .= "        <td align=\"center\">".$vector[$i]['factura_fiscal']."</td>";
						$this->salida .= "        <td align=\"center\">".$vector[$i]['total_factura']."</td>";
						$this->salida .= "        <td align=\"center\">".$pago."</td>";
						$this->salida .= "        <td align=\"center\">".$vector[$i]['estado']."</td>";
						$this->salida .= "        <td align=\"center\">".$vector[$i]['plan_descripcion']."</td>";
						$this->salida .= "      </tr>";
				}
	
	$this->salida .= " </table><br>";
	$this->salida .= "		</form>\n";
        $direccion="app_modules/Facturacion/reports/html/Total_FacturasCredito.report.php";
	$this->salida.="		<center><img src=\"".GetThemePath()."/images/imprimir.png\"><label class=\"label\"><a href=\"javascript:abreVentanaTotalFC('$direccion');\"> IMPRIMIR </a></label></center><br>";
	$this->salida .= "            <form name=\"VOLVER2\" action=\"".$accion1."\" method=\"post\">\n";
	$this->salida .= "              <input type=\"submit\" class=\"input-submit\" value=\"VOLVER\">\n";
	$this->salida .= "            </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
	}
	
	function FrmMenuCenso()
	{
		$accion1=ModuloGetURL('app','Facturacion','user','FrmListadoCenso',array('opcion'=>0,'enlace'=>1));
		$accion2=ModuloGetURL('app','Facturacion','user','FrmListadoCenso',array('opcion'=>1,'enlace'=>1));
		
		$this->salida .= ThemeAbrirTabla("REPORTES MENU CENSO");
		$this->salida .= "<table width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "	<tr class=\"modulo_table_list_title\">";
		$this->salida .= "		<td  align=\"center\">REPORTES CENSO</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"label\" align=\"center\"><label class=\"label\"><a href=\"$accion1\">LISTADO DE PACIENTES HOSPITALIZADOS</a></label></td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "		<td class=\"label\" align=\"center\"><label class=\"label\"><a href=\"$accion2\">LISTADO DE PACIENTES EN OBSERVACION DE URGENCIAS</a></label></td>";
		$this->salida .= "	</tr>";
		$this->salida .= "</table>";
		
		$accionV = ModuloGetURL('app','Facturacion','user','FrmGeneracionReportes');
		$this->salida .= "<form name=\"formavolver\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"></p>";
		$this->salida .= "</form>";
		
		$this->salida .= ThemeCerrarTabla();
		
		return true;
	}
	
	function FrmListadoCenso()
	{
		if($_REQUEST['enlace']==1)
		{
			if(!$_REQUEST['opcion'])
			{
				$pacientes=$this->ListadoHospitalizados();
				$titulo="LISTADO DE PACIENTES HOSPITALIZADOS";
			}
			else
			{
				$pacientes=$this->ListadoObservacionUrgencias();
				$titulo="LISTADO DE PACIENTES EN OBSERVACION URGENCIAS";
			}
			$accionV = ModuloGetURL('app','Facturacion','user','FrmMenuCenso');
		}
		if($_REQUEST['enlace']==2)
		{
			switch($_REQUEST['opcion'])
			{
				case 1:
					$titulo="REPORTES CUENTAS ACTIVAS";
				break;
				case 2:
					$titulo="REPORTES CUENTAS INACTIVAS";
				break;
				case 3:
					$titulo="REPORTES CUENTAS ACTIVAS E INACTIVAS";
				break;
			}
			
			$pacientes=$this->ReportesCuentas($_REQUEST['opcion']);
			$accionV = ModuloGetURL('app','Facturacion','user','FrmGeneracionReportes');
		}
		
		$this->salida .= ThemeAbrirTabla($titulo);
		$cont=0;
		$entidad=array();
		$this->salida .= "<table align=\"center\" width=\"100%\" border=\"0\" class=\"modulo_table_list\">\n";
		foreach($pacientes as $key=>$valor)
		{
			$this->salida .= "	<tr class=\"modulo_table_list_title\" align=\"center\">\n";
			$this->salida .= "		<td align =\"center\" colspan=\"4\">DEPARTAMENTO  -  $key</td>";
			$this->salida .= "	</tr>\n";
			foreach($valor as $key1=>$valor1)
			{
				$this->salida .= "	<tr class=\"modulo_table_title\" align=\"center\">\n";
				$this->salida .= "		<td align =\"center\" colspan=\"4\">ESTACION  -  $key1</td>";
				$this->salida .= "	</tr>\n";
				$this->salida .= "	<tr>\n";
				$this->salida .= "		<td colspan=\"4\">\n";
				$this->salida .= "			<table align=\"center\" width=\"100%\" border=\"0\" class=\"modulo_table_list\">\n";
				$this->salida .= "				<tr class=\"modulo_table_list_title\" align=\"center\">\n";
				$this->salida .= "					<td width=\"5%\">CUENTA</td>\n";
				$this->salida .= "					<td width=\"10%\">ID</td>\n";
				$this->salida .= "					<td width=\"15%\">PACIENTE</td>\n";
				$this->salida .= "					<td width=\"10%\">AFILIACION</td>\n";
				$this->salida .= "					<td width=\"5%\">RANGO</td>\n";
				$this->salida .= "					<td width=\"5%\">HAB.</td>\n";
				$this->salida .= "					<td width=\"5%\">CAMA</td>\n";
				$this->salida .= "					<td width=\"10%\">FECHA INGRESO</td>\n";
				$this->salida .= "					<td width=\"5%\">TIEMPO<BR>HOSP (DIAS)</td>\n";
				$this->salida .= "					<td width=\"10%\">TERCERO</td>\n";
				$this->salida .= "					<td width=\"10%\">PLAN</td>\n";
				if($_REQUEST['enlace']==2 AND $_REQUEST['opcion']==3)
					$this->salida .= "					<td>ESTADO CUENTA</td>\n";
				$this->salida .= "					<td width=\"15%\">VALOR CUBIERTO + HAB</td>\n";
				$this->salida .= "				</tr>\n";
				
				$k=0;
				foreach($valor1 as $key2=>$valor2)
				{
					if($k%2==0)
					{
						$estilo="modulo_list_oscuro";
					}
					else
					{
						$estilo="modulo_list_claro";
					}

					$vc_pac=$valor2['valor_cubierto'];
					$vnc_pac=$this->GetEstancia($valor2['numerodecuenta']);
					
					$entidad[$valor2['nombre_tercero']]['valor_cuenta']+=$vc_pac+$vnc_pac;
					$entidad[$valor2['nombre_tercero']]['contador']+=1;
					
					$pacientes[$key][$key1][$key2]['t_vc_apc']=$vc_pac;
					$pacientes[$key][$key1][$key2]['t_vnc_apc']=$vnc_pac;

					$this->salida .= "				<tr class=\"$estilo\" align=\"center\">\n";
					$this->salida .= "					<td>".$valor2['numerodecuenta']."</td>\n";
					$this->salida .= "					<td>".$valor2['tipo_id_paciente']." - ".$valor2['paciente_id']."</td>\n";
					$this->salida .= "					<td>".$valor2['nombre_completo']."</td>\n";
					$this->salida .= "					<td>".strtoupper($valor2['tipo_afiliado_nombre'])."</td>\n";
					$this->salida .= "					<td>".$valor2['rango']."</td>\n";
					$this->salida .= "					<td>".$valor2['pieza']."</td>\n";
					$this->salida .= "					<td>".$valor2['cama']."</td>\n";
					$this->salida .= "					<td>".date('Y-m-d g:i a',strtotime($valor2['fecha_ingreso']))."</td>\n";
					$this->salida .= "					<td>".$this->GetDiasHospitalizacion($valor2['fecha_ingreso'])."</td>\n";
					$this->salida .= "					<td>".$valor2['nombre_tercero']."</td>\n";
					$this->salida .= "					<td>".$valor2['plan_descripcion']."</td>\n";
					if($_REQUEST['enlace']==2 AND $_REQUEST['opcion']==3)
						$this->salida .= "					<td>".$valor2['estado_cuenta']."</td>\n";
					$this->salida .= "					<td> $ ".FormatoValor($vc_pac+$vnc_pac)."</td>\n";
					$this->salida .= "				</tr>\n";
					$sum+=$vc_pac+$vnc_pac;
					$cont++;
					$k++;
				}
				if($_REQUEST['enlace']==1)
				{
					$co=$this->GetCamas($valor[$key1][$key2]['estacion_id'],'0');
					$cd=$this->GetCamas($valor[$key1][$key2]['estacion_id'],'1');
					
					$this->salida .= "				<tr class=\"hc_table_submodulo_list_title\">\n";
					$this->salida .= "					<td  colspan=\"12\" align=\"right\">CAMAS DISPONIBLES : &nbsp;&nbsp;&nbsp; <label class=\"label_error\">$cd</label> &nbsp;&nbsp;&nbsp; CAMAS OCUPADAS : &nbsp;&nbsp;&nbsp; <label class=\"label_error\">$co</label> &nbsp;&nbsp;&nbsp; CANTIDAD : &nbsp;&nbsp;&nbsp; <label class=\"label_error\">$k</label> </td>\n";
					$this->salida .= "				</tr>\n";
				}
				$this->salida .= "			</table>\n";
				$this->salida .= "		</td>\n";
				$this->salida .= "	</tr>\n";
			}
		}
		
		$this->salida .= "	<tr class=\"modulo_list_oscuro\" align=\"center\">\n";
		$this->salida .= "		<td class=\"label\">ENTIDAD</td>\n";
		$this->salida .= "		<td class=\"label\">CANTIDAD</td>\n";
		$this->salida .= "		<td class=\"label\">VALOR CUENTA</td>\n";
		$this->salida .= "	</tr>\n";
		foreach($entidad as $key=>$valor_ent)
		{
			$this->salida .= "	<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "		<td align=\"right\" class=\"label\">$key</td>\n";
			$this->salida .= "		<td align=\"right\" class=\"label\">".$valor_ent['contador']."</td>\n";
			$this->salida .= "		<td align=\"right\" class=\"label\"> $ ".FormatoValor($valor_ent['valor_cuenta'])."</td>\n";
			$this->salida .= "	</tr>\n";
		}
		
		$this->salida .= "	<tr class=\"hc_table_submodulo_list_title\">\n";
		$this->salida .= "		<td align=\"right\" class=\"label\">TOTAL : </td>\n";
		$this->salida .= "		<td align=\"right\" class=\"label\">$cont</td>";
		$this->salida .= "		<td align=\"right\" class=\"label\"> $ ".FormatoValor($sum)."</td>\n";
		$this->salida .= "	</tr>\n";
		
		$this->salida .= "</table>\n";
		
		$_SESSION['listado']=$pacientes;
		
		/*$reporte=new GetReports();
		$mostrarT=$reporte->GetJavaReport('app','Facturacion','ReporteImpCuentas',array('enlace'=>$_REQUEST['enlace'],'opcion'=>$_REQUEST['opcion'],'titulo'=>$titulo),array('rpt_name'=>'ReporteCuentas','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
		$funcionT=$reporte->GetJavaFunction();

		$this->salida.="		<br><center><img src=\"".GetThemePath()."/images/imprimir.png\"><label class=\"label\"><a href=\"javascript:$funcionT\"> IMPRIMIR </a></label></center>";	

		$this->salida .= "$mostrarT";
		*/
		$direccion="app_modules/Facturacion/reports/html/ReporteImpCuentas.php?";
		$this->salida.="		<br><center><img src=\"".GetThemePath()."/images/imprimir.png\"><label class=\"label\"><a href=\"javascript:reportecuentas('$direccion','".$_REQUEST['enlace']."','".$_REQUEST['opcion']."','$titulo');\"> IMPRIMIR </a></label></center>";	
		
		$this->salida .= "<form name=\"formavolver\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"></p>";
		$this->salida .= "</form>";
		
		$this->salida .= "<script>\n";
		$this->salida .= "	function reportecuentas(dir,enl,op,tit)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		var url=dir+'enlace='+enl+'&opcion='+op+'&titulo='+tit;\n";
		$this->salida .= "		window.open(url,'REPORTE CUENTAS','screen.width,screen.height,resizable=no,location=yes,toolbar=1,status=no,scrollbars=yes');\n";
		$this->salida .= "	}\n";
		$this->salida .= "</script>\n";
		
		$this->salida .= ThemeCerrarTabla();
		
		return true;
	}
	
	function FrmConsultaPacientesTP() 
	{
		$accionR=ModuloGetURL('app','Facturacion','user','FrmListadoPacientesUHA');
		$accionV=ModuloGetURL('app','Facturacion','user','FrmGeneracionReportes');
		
		$planes=$this->GetPlanes();
		
		if(!$_REQUEST['estado_plan'])
		{
			$check1="checked";
			$_REQUEST['estado_plan']=1;
		}
		else
		{
			switch($_REQUEST['estado_plan'])
			{
				case 1:
					$check1="checked";
				break;
				
				case 2:
					$check2="checked";
				break;
				
				case 3:
					$check3="checked";
				break;
			}
		
		}
		
		$this->SetXajax(array("GetEstadoPlanes"),"app_modules/Facturacion/RemoteXajax/CuentasPlanes.php");

		$this->salida .= "				<script>\n";
		$this->salida .= "					xajax_GetEstadoPlanes('".$_REQUEST['estado_plan']."');";
		$this->salida .= "				</script>\n";
		
		$this->salida .= ThemeAbrirTabla('BUSQUEDA PACIENTES - CUENTAS');
		$this->salida .= "<form name=\"forma_reporte\" action=\"$accionR\" method=\"post\">\n";
		$this->salida .= "	<table align=\"center\" width=\"70%\" border=\"0\" class=\"modulo_table_list\">\n";
		$this->salida .= "		<tr align=\"center\">\n";
		$this->salida .= "			<td align=\"center\" width=\"30%\" class=\"modulo_table_list_title\">FECHAS : </td>";
		$this->salida .= "			<td align=\"left\" colspan=\"3\" class=\"modulo_list_claro\">";
		$this->salida .= "				DE <input type=\"text\" name=\"fecha_ini\" size=\"10\" readonly value=\"".$_REQUEST['fecha_ini']."\" class=\"input-text\">";
		$this->salida .= "				<sub>".ReturnOpenCalendario("forma_reporte","fecha_ini","-")."</sub>";
		$this->salida .= "				A <input type=\"text\" name=\"fecha_fin\" size=\"10\" readonly value=\"".$_REQUEST['fecha_fin']."\" class=\"input-text\">";
		$this->salida .= "				<sub>".ReturnOpenCalendario("forma_reporte","fecha_fin","-")."</sub>";
		$this->salida .= "			</td>";
		$this->salida .= "		</tr>\n";
		$this->salida .= "		<tr align=\"center\">\n";
		$this->salida .= "			<td align=\"center\" class=\"modulo_table_list_title\" width=\"30%\">ESTADO PLAN : </td>";
		$this->salida .= "			<td align=\"center\" class=\"modulo_list_claro\" width=\"25%\"><input type=\"radio\" name=\"estado_plan\" value=\"1\" $check1 onclick=\"xajax_GetEstadoPlanes('1');\"> ACTIVOS </td>";
		$this->salida .= "			<td align=\"center\" class=\"modulo_list_claro\" width=\"25%\"><input type=\"radio\" name=\"estado_plan\" value=\"2\" $check2 onclick=\"xajax_GetEstadoPlanes('2');\"> INACTIVOS</td>";
		$this->salida .= "			<td align=\"center\" class=\"modulo_list_claro\" width=\"20%\"><input type=\"radio\" name=\"estado_plan\" value=\"3\" $check3 onclick=\"xajax_GetEstadoPlanes('3');\"> TODOS</td>";
		$this->salida .= "		</tr>\n";
		$this->salida .= "		<tr class=\"modulo_table_list_title\" align=\"center\">\n";
		$this->salida .= "			<td align=\"center\" width=\"30%\">PLAN : </td>";
		$this->salida .= "			<td align=\"left\" colspan=\"3\" class=\"modulo_list_claro\" id=\"capa_plan\">";

		$this->salida .= "			</td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= "		<tr class=\"modulo_list_claro\" align=\"center\">\n";
		$this->salida .= "			<td align=\"center\" colspan=\"5\"><input class=\"input-submit\" type=\"submit\" name=\"filtrar\" value=\"FILTRAR\">";
		$this->salida .= "			&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"input-submit\" type=\"button\" name=\"limpiar\" value=\"LIMPIAR\" onclick=\"this.form.fecha_ini.value='';this.form.fecha_fin.value='';this.form.planes.value='';this.form.estado_plan[0].checked=true;this.form.estado_plan[1].checked=false;this.form.estado_plan[2].checked=false;xajax_GetEstadoPlanes('1');\"></td>";
		$this->salida .= "		</tr>\n";
		$this->salida .= "	</table>\n";
		$this->salida .= "</form>\n";
		
		$this->salida .= "<form name=\"formavolver\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"></p>";
		$this->salida .= "</form>";
	
		$this->salida .= ThemeCerrarTabla();
		
		return true;
	}
	
	function GetEstancia($cuenta,$fecha_ini=null,$fecha_fin=null)
	{
		static $liq;
		
		if(!is_object($liq))
		{
			if(IncludeClass("LiquidacionHabitaciones")===false) 
				echo "Error al Incluir Clase LiquidacionHabitaciones";
			$liq=new LiquidacionHabitaciones;
		}
		
		$retorno=$liq->LiquidarCargosInternacion($cuenta,false,$fecha_ini,$fecha_fin);
		$valor=0;
		foreach($retorno as $k=>$ret)
		{
			$valor+=$ret['valor_cubierto'];
		}
		unset($liq);
		return $valor;
	}
	
	
	function FrmListadoPacientesUHA()
	{
		$this->salida .= ThemeAbrirTabla('LISTADO PACIENTES HOSPITALIZACION - URGENCIAS - AMBULATORIO');

		$accionV=ModuloGetURL('app','Facturacion','user','FrmConsultaPacientesTP',array('fecha_ini'=>$_REQUEST['fecha_ini'],'fecha_fin'=>$_REQUEST['fecha_fin'],'planes'=>$_REQUEST['planes'],'estado_plan'=>$_REQUEST['estado_plan']));
		
		$fecha_ini=str_replace("/","-",$this->FechaStamp($_REQUEST['fecha_ini']));
		$fecha_fin=str_replace("/","-",$this->FechaStamp($_REQUEST['fecha_fin']));

		$pacientes[0]=$this->ListadoPacientesAtendidos($fecha_ini,$fecha_fin,$_REQUEST['planes'],1);
		$pacientes[1]=$this->ListadoPacientesAtendidos($fecha_ini,$fecha_fin,$_REQUEST['planes'],2);
		$pacientes[2]=$this->ListadoPacientesAtendidos($fecha_ini,$fecha_fin,$_REQUEST['planes'],3);
		
		$this->salida .= "<table align=\"center\" width=\"100%\" border=\"0\" class=\"modulo_table_list\">\n";
		if($pacientes[0])
		{
			$this->salida .= "	<tr class=\"modulo_table_list_title\" align=\"center\">\n";
			$this->salida .= "		<td colspan=\"6\">PACIENTES HOSPITALIZADOS</td>";
			$this->salida .= "	</tr>\n";
			$this->salida .= "	<tr class=\"modulo_table_list_title\" align=\"center\">\n";
			$this->salida .= "		<td width=\"10%\">CUENTA</td>";
			$this->salida .= "		<td width=\"15%\">IDENTIFICACION</td>";
			$this->salida .= "		<td width=\"30%\">NOMBRE PACIENTE</td>";
			$this->salida .= "		<td width=\"15%\">VALOR CARGOS</td>";
			$this->salida .= "		<td width=\"15%\">CARGOS HABITACION</td>";
			$this->salida .= "		<td width=\"15%\">VALOR CARGO + HAB</td>";
			$this->salida .= "	</tr>\n";
			$a=0;
			
			foreach($pacientes[0] as $key=>$valor)
			{
				if($a%2==0)
					$estilo="modulo_list_oscuro";
				else
					$estilo="modulo_list_claro";
				

				$this->salida .= "	<tr class=\"$estilo\" align=\"center\">\n";
				$this->salida .= "		<td>".$valor['numerodecuenta']."</td>";
				$this->salida .= "		<td>".$valor['tipo_id_paciente']."-".$valor['paciente_id']."</td>";
				$this->salida .= "		<td>".$valor['nombre_completo']."</td>";
				
				$val1=$this->GetEstancia($valor['numerodecuenta'],$fecha_ini,$fecha_fin);
				
				$this->salida .= "		<td align=\"right\"> $ ".FormatoValor($valor['valor_cubierto'])."</td>";
				$this->salida .= "		<td align=\"right\"> $ ".FormatoValor($val1)."</td>";
				$this->salida .= "		<td align=\"right\"> $ ".FormatoValor($valor['valor_cubierto']+$val1)."</td>";
				
				$this->salida .= "	</tr>\n";
				
				$pacientes[0][$key]['sum_a']=$valor['valor_cubierto'];
				$pacientes[0][$key]['habitacion']=$val1;
				$pacientes[0][$key]['cargo_mas_hab']=$valor['valor_cubierto']+$val1;
				
				$sum_a+=$valor['valor_cubierto'];
				$s_per_a+=$val1;
				$cargo_mas_hab_a+=$valor['valor_cubierto']+$val1;
				$a++;
			}
			$this->salida .= "	<tr class=\"modulo_table_title\" align=\"center\">\n";
			$this->salida .= "		<td colspan=\"3\" align=\"right\">TOTAL PACIENTES HOSPITALIZADOS : $a</td>";
			$this->salida .= "		<td align=\"right\"> $ ".FormatoValor($sum_a)."</td>";
			$this->salida .= "		<td align=\"right\"> $ ".FormatoValor($s_per_a)."</td>";
			$this->salida .= "		<td align=\"right\"> $ ".FormatoValor($cargo_mas_hab_a)."</td>";
			$this->salida .= "	</tr>\n";
		}
		if($pacientes[1])
		{
			$this->salida .= "	<tr class=\"modulo_table_list_title\" align=\"center\">\n";
			$this->salida .= "		<td colspan=\"6\">PACIENTES EN URGENCIAS</td>";
			$this->salida .= "	</tr>\n";
			$this->salida .= "	<tr class=\"modulo_table_list_title\" align=\"center\">\n";
			$this->salida .= "		<td>CUENTA</td>";
			$this->salida .= "		<td>IDENTIFICACION</td>";
			$this->salida .= "		<td>NOMBRE PACIENTE</td>";
			$this->salida .= "		<td>VALOR CARGOS</td>";
			$this->salida .= "		<td>CARGOS HABITACION</td>";
			$this->salida .= "		<td>VALOR CARGO + HAB</td>";
			$this->salida .= "	</tr>\n";
			$b=0;
			
			foreach($pacientes[1] as $key=>$valor)
			{
				if($b%2==0)
					$estilo="modulo_list_oscuro";
				else
					$estilo="modulo_list_claro";
				
				$this->salida .= "	<tr class=\"$estilo\" align=\"center\">\n";
				$this->salida .= "		<td>".$valor['numerodecuenta']."</td>";
				$this->salida .= "		<td>".$valor['tipo_id_paciente']."-".$valor['paciente_id']."</td>";
				$this->salida .= "		<td>".$valor['nombre_completo']."</td>";
				
				$val2=$this->GetEstancia($valor['numerodecuenta'],$fecha_ini,$fecha_fin);

				$this->salida .= "		<td align=\"right\"> $ ".FormatoValor($valor['valor_cubierto'])."</td>";
				$this->salida .= "		<td align=\"right\"> $ ".FormatoValor($val2)."</td>";
				$this->salida .= "		<td align=\"right\"> $ ".FormatoValor($valor['valor_cubierto']+$val2)."</td>";
				
				$this->salida .= "	</tr>\n";

				$pacientes[1][$key]['sum_b']=$valor['valor_cubierto'];
				$pacientes[1][$key]['habitacion']=$val2;
				$pacientes[1][$key]['cargo_mas_hab']=$valor['valor_cubierto']+$val2;
				
				$sum_b+=$valor['valor_cubierto'];
				$s_per_b+=$val2;
				$cargo_mas_hab_b+=$valor['valor_cubierto']+$val2;
				$b++;
			}
			$this->salida .= "	<tr class=\"modulo_table_title\" align=\"center\">\n";
			$this->salida .= "		<td colspan=\"3\" align=\"right\">TOTAL PACIENTES EN URGENCIAS : $b </td>";
			$this->salida .= "		<td align=\"right\"> $ ".FormatoValor($sum_b)."</td>";
			$this->salida .= "		<td align=\"right\"> $ ".FormatoValor($s_per_b)."</td>";
			$this->salida .= "		<td align=\"right\"> $ ".FormatoValor($cargo_mas_hab_b)."</td>";
			$this->salida .= "	</tr>\n";
		}
		if($pacientes[2])
		{
			$this->salida .= "	<tr class=\"modulo_table_list_title\" align=\"center\">\n";
			$this->salida .= "		<td colspan=\"6\">PACIENTES AMBULATORIOS</td>";
			$this->salida .= "	</tr>\n";
			$this->salida .= "	<tr class=\"modulo_table_list_title\" align=\"center\">\n";
			$this->salida .= "		<td>CUENTA</td>";
			$this->salida .= "		<td>IDENTIFICACION</td>";
			$this->salida .= "		<td>NOMBRE PACIENTE</td>";
			$this->salida .= "		<td>VALOR CARGOS</td>";
			$this->salida .= "		<td>CARGOS HABITACION</td>";
			$this->salida .= "		<td>VALOR CARGO + HAB</td>";
			$this->salida .= "	</tr>\n";
			$c=0;
			
			foreach($pacientes[2] as $key=>$valor)
			{
				
				if($c%2==0)
					$estilo="modulo_list_oscuro";
				else
					$estilo="modulo_list_claro";
				
				$this->salida .= "	<tr class=\"$estilo\" align=\"center\">\n";
				$this->salida .= "		<td>".$valor['numerodecuenta']."</td>";
				$this->salida .= "		<td>".$valor['tipo_id_paciente']."-".$valor['paciente_id']."</td>";
				$this->salida .= "		<td>".$valor['nombre_completo']."</td>";
				
				$val3=$this->GetEstancia($valor['numerodecuenta'],$fecha_ini,$fecha_fin);
				
				$this->salida .= "		<td align=\"right\"> $ ".FormatoValor($valor['valor_cubierto'])."</td>";
				$this->salida .= "		<td align=\"right\"> $ ".FormatoValor($val3)."</td>";
				$this->salida .= "		<td align=\"right\"> $ ".FormatoValor($valor['valor_cubierto']+$val3)."</td>";
				$this->salida .= "	</tr>\n";

				$pacientes[2][$key]['sum_b']=$valor['valor_cubierto'];
				$pacientes[2][$key]['habitacion']=$val3;
				$pacientes[2][$key]['cargo_mas_hab']=$valor['valor_cubierto']+$val3;
				
				$sum_c+=$valor['valor_cubierto'];
				$s_per_c+=$val3;
				$cargo_mas_hab_c+=$valor['valor_cubierto']+$val3;
				$c++;
			}
			$this->salida .= "	<tr class=\"modulo_table_title\" align=\"center\">\n";
			$this->salida .= "		<td colspan=\"3\" align=\"right\">TOTAL PACIENTES EN AMBULATORIO : $c </td>";
			$this->salida .= "		<td align=\"right\"> $ ".FormatoValor($sum_c)."</td>";
			$this->salida .= "		<td align=\"right\"> $ ".FormatoValor($s_per_c)."</td>";
			$this->salida .= "		<td align=\"right\"> $ ".FormatoValor($cargo_mas_hab_c)."</td>";
			$this->salida .= "	</tr>\n";
			$n_total=$a+$b+$c;
			$suma_total=$sum_a+$sum_b+$sum_c;
			$s_per_total=$s_per_a+$s_per_b+$s_per_c;
			$car_hab_total=$cargo_mas_hab_a+$cargo_mas_hab_b+$cargo_mas_hab_c;
			$this->salida .= "	<tr class=\"hc_table_submodulo_list_title\" align=\"center\">\n";
			$this->salida .= "		<td colspan=\"3\" align=\"right\">TOTAL PACIENTES : $n_total </td>";
			$this->salida .= "		<td align=\"right\"> $ ".FormatoValor($suma_total)."</td>";
			$this->salida .= "		<td align=\"right\"> $ ".FormatoValor($s_per_total)."</td>";
			$this->salida .= "		<td align=\"right\"> $ ".FormatoValor($car_hab_total)."</td>";
			$this->salida .= "	</tr>\n";
		}
		
		$this->salida .= "</table>\n";

		if(empty($pacientes[0]) AND empty($pacientes[1]) AND empty($pacientes[2]))
			$this->salida .= "<p align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS</p>";
	
		$_SESSION['list_1']=$pacientes;

		$direccion="app_modules/Facturacion/reports/html/ReportePacientesCuentas.php";
		$this->salida.="		<br><center><img src=\"".GetThemePath()."/images/imprimir.png\"><label class=\"label\"><a href=\"javascript:reportecuentas('$direccion');\"> IMPRIMIR </a></label></center>";	
		
		$this->salida .= "<form name=\"formavolver\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"></p>";
		$this->salida .= "</form>";
		
		$this->salida .= "<script>\n";
		$this->salida .= "	function reportecuentas(url)\n";
		$this->salida .= "	{\n";
		$this->salida .= "		window.open(url,'REPORTE CUENTAS','screen.width,screen.height,resizable=no,location=yes,toolbar=1,status=no,scrollbars=yes');\n";
		$this->salida .= "	}\n";
		$this->salida .= "</script>\n";

		$this->salida .= ThemeCerrarTabla();
		return true;
	}
  
  //*****************************************PAQUETES************************ 
    function FrmRealizarPaquetesCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Fecha,$Ingreso,$Estado){
      
      IncludeClass('PaquetesCargosCtaHTML','','app','Facturacion');
      $html = new PaquetesCargosCtaHTML();
      $accionVolver=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));            
      $this->salida .= $html->CrearFormaPaquetesCargosCta($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Fecha,$Ingreso,$Estado,$this,$accionVolver);                                       
      return true;
      
    }  
    
    //**********************************detalle cuenta*********************
    
    /***********************************************************************
    * Forma para modificar los cargos de una cuenta
    * @access private
    * @return boolean
    */
    
    function FormaModificarCargo($Transaccion,$TipoId,$PacienteId,$Cuenta,$Nivel,$PlanId,$Fecha,$Ingreso,$D,$mensaje,$Apoyo)
    {
            
          IncludeLib("tarifario");   
          IncludeClass('ModificacionCargoHTML','','app','Facturacion');
          $html = new ModificacionCargoHTML();
          $accionCancelar=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
          $accionModificar=ModuloGetURL('app','Facturacion','user','ValidarModificarCargo',array('Datos'=>$D,'Transaccion'=>$Transaccion,'Consecutivo'=>$Consecutivo,'Cons'=>$Apoyo,'codigo'=>$_REQUEST['codigo'],'consecutivo'=>$consecutivo,'doc'=>$_REQUEST['doc'],'numeracion'=>$_REQUEST['numeracion'],'des'=>$des,'noFacturado'=>$noFacturado['facturado'],
          'Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));     
          $this->salida .= $html->CrearFormaModificacionCargo($Transaccion,$TipoId,$PacienteId,$Cuenta,$Nivel,$PlanId,$Fecha,$Ingreso,$D,$mensaje,$accionCancelar,$accionModificar,$_SESSION['CUENTAS']['EMPRESA'],$_SESSION['CUENTAS']['CENTROUTILIDAD']);
          return true;
    }
    
    function FormaEliminarCargo($transaccion,$Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Fecha,$Ingreso,$Datos,$des,$codigo,$doc,$numeracion,$noFacturado,$mensaje)
    {
      
      $accionC=ModuloGetURL('app','Facturacion','user','Cuenta',array('Transaccion'=>$transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
      $accionE=ModuloGetURL('app','Facturacion','user','ValidarEliminarCargo',array('Transaccion'=>$transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'codigo'=>$_REQUEST['codigo'],'consecutivo'=>$consecutivo,'doc'=>$_REQUEST['doc'],'numeracion'=>$_REQUEST['numeracion'],'des'=>$des,'noFacturado'=>$noFacturado['facturado']));
      IncludeClass('EliminaCargoHTML','','app','Facturacion');
      $html = new EliminaCargoHTML();            
      $this->salida .= $html->CrearFormaEliminaCargo($Cuenta,$accionE,$accionC,$mensaje);
      return true;
    }
    
    function FormaDevolverIYMCta($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Fecha,$Ingreso,$mensaje)
    {            
      $this->IncludeJS("CrossBrowser");
      $this->IncludeJS("CrossBrowserDrag");
      $this->IncludeJS("CrossBrowserEvent");
      IncludeClass('DevolucionCargosIyMCtaHTML','','app','Facturacion');      
      $accionDevolver=ModuloGetURL('app','Facturacion','user','RealizarVevolucionMedicamentos',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
      $accionSalir=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
      $html = new DevolucionCargosIyMCtaHTML();                 
      $this->salida .= $html->CrearFormaDevolucionCargosCta($Cuenta,$TipoId,$PacienteId,$accionDevolver,$accionSalir,$this,$mensaje);
      return true;
    }
   
    //*******************************FIN PAQUETES/********************************
}    //fin clase
?>
