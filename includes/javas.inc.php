<?php
  /**
  * $Id: javas.inc.php,v 1.19 2009/11/06 14:39:22 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  * API para el Manejo de  los Modulos de la aplicacion
  */
   
  /**
  * Vector para la inclusion de archivos JS
  */
  global $_JavaFiles;
  $_JavaFiles = array
  (
    'RemoteScripting'=>'javascripts/jsrsClient.js',
    'CrossBrowser'=>'javascripts/cross-browser/x/x_core.js',
    'CrossBrowserDrag'=>'javascripts/cross-browser/x/x_drag.js',
		'CrossBrowserEvent'=>'javascripts/cross-browser/x/x_event.js',
		'TabPaneLayout'=>'javascripts/tabpane/local/webfxlayout.js',
		'TabPaneApi'=>'javascripts/tabpane/local/webfxapi.js',
		'TabPane'=>'javascripts/tabpane/js/tabpane.js',
    'Calendario'=>'javascripts/Calendario/calendario.js',
    'Jsgraphics'=>'javascripts/Draw2D/jsDraw2D.js',
    'phpjs'=>'javascripts/phpjs/php.default.min.js'
  );

  function ReturnJava($Java)
  {
    global $VISTA;
    switch($Java)
    {
      case 'DatosPaciente':
      {
        $salida ="<script>\n";
        $salida .="function DatosPaciente(tipoid,pacienteid)\n";
        $salida .="{\n";
        $salida .="var url='reports/$VISTA/datospaciente.php?TipoId='+tipoid+'&PacienteId='+pacienteid;\n";
        $salida .="window.open(url,'','width=600,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no')\n";
        $salida .="}\n";
        $salida .="</script>\n";
        return $salida;
        break;
      }
      case 'DatosProfesional':
      {
          $salida ="<script>\n";
          $salida .="function DatosProfesional(tipoid,profesionalid)\n";
          $salida .="{\n";
          $salida .="var url='reports/$VISTA/datosprofesional.php?TipoId='+tipoid+'&ProfesionalId='+profesionalid;\n";
          $salida .="window.open(url,'','width=600,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no')\n";
          $salida .="}\n";
          $salida .="</script>\n";
          return $salida;
          break;
      }
      case 'DatosEvolucionInactiva':
      {
          $salida ="<script>\n";
          $salida .="function DatosEvolucionInactiva(evolucion)\n";
          $salida .="{\n";
          $salida .="var url='reports/$VISTA/datosprofesional.php?evolucion='+evolucion;\n";
          $salida .="window.open(url,'','status=no,scrollbars=yes,location=no')\n";
          $salida .="}\n";
          $salida .="</script>\n";
          return $salida;
          break;
      }
      case 'DatosAutorizacion':
      {
          $salida ="<script>\n";
          $salida .="function DatosAutorizacion1(aint,aext)\n";
          $salida .="{\n";
          $salida .="var url='reports/$VISTA/datosautorizacion.php?autorizacion_int='+aint+'&autorizacion_ext='+aext;\n";
          $salida .="window.open(url,'','width=600,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no');\n";
          $salida .="}\n";
          $salida .="</script>\n";
          return $salida;
          break;
      }
      case 'DatosBD':
      {
          $salida ="<script>\n";
          $salida .="function DatosBD(tipoid,paciente,plan)\n";
          $salida .="{\n";
          $salida .="var url='reports/$VISTA/datosbd.php?tipoid='+tipoid+'&paciente='+paciente+'&plan='+plan;\n";
          $salida .="window.open(url,'','width=600,height=500,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no');\n";
          $salida .="}\n";
          $salida .="</script>\n";
          return $salida;
          break;
      }
      case 'DatosBDAnteriores':
      {
          $salida ="<script>\n";
          $salida .="function DatosBDAnteriores(tipoid,paciente,plan,cantidad)\n";
          $salida .="{\n";
          $salida .="var url='reports/$VISTA/datosbdanteriores.php?tipoid='+tipoid+'&paciente='+paciente+'&plan='+plan+'&cantidad='+cantidad;\n";
          $salida .="window.open(url,'','width=600,height=500,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no');\n";
          $salida .="}\n";
          $salida .="</script>\n";
          return $salida;
          break;
      }
      case 'BuscadorBD':
      {
          $salida ="<script>\n";
          $salida .="function BuscadorBD(departamento,forma)\n";
          $salida .="{\n";
          $salida .="var url='reports/$VISTA/busquedaporcampos.php?departamento='+departamento+'&forma='+forma;\n";
          $salida .="window.open(url,'','width=600,height=500,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no');\n";
          $salida .="}\n";
          $salida .="</script>\n";
          return $salida;
          break;
      }
      case 'Ocupaciones':
      {
          $salida ="<script>\n";
          $salida .="function Ocupaciones(forma,prefijo)\n";
          $salida .="{\n";
          $salida .="var url='reports/$VISTA/ocupaciones.php?forma=' + forma +'&prefijo=' + prefijo;\n";
          $salida .="window.open(url,'','width=600,height=500,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no');\n";
          $salida .="}\n";
          $salida .="</script>\n";
          return $salida;
          break;
      }
      case 'EmpleadorSOS':
      {
          $salida ="<script>\n";
          $salida .="function EmpleadorSOS(i)\n";
          $salida .="{\n";
          $salida .="var url='reports/$VISTA/empleadorSOS.php?val='+i;\n";
          $salida .="window.open(url,'','width=600,height=350,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no');\n";
          $salida .="}\n";
          $salida .="</script>\n";
          return $salida;
          break;
      }
      case 'PagosPaciente':
      {
          $salida ="<script>\n";
          $salida .="function PagosPaciente(Cuenta)\n";
          $salida .="{\n";
          $salida .="var url='reports/$VISTA/pagospaciente.php?cuenta='+Cuenta;\n";
          $salida .="window.open(url,'','width=600,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no')\n";
          $salida .="}\n";
          $salida .="</script>\n";
          return $salida;
          break;
      }
      case 'DescuentosPaciente':
      {
          $salida ="<script>\n";
          $salida .="function DescuentosPaciente(Cuenta)\n";
          $salida .="{\n";
          $salida .="var url='reports/$VISTA/descuentospaciente.php?cuenta='+Cuenta;\n";
          $salida .="window.open(url,'','width=600,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no')\n";
          $salida .="}\n";
          $salida .="</script>\n";
          return $salida;
          break;
      }
      case 'DescuentosEmpresa':
      {
          $salida ="<script>\n";
          $salida .="function DescuentosEmpresa(Cuenta)\n";
          $salida .="{\n";
          $salida .="var url='reports/$VISTA/descuentosempresa.php?cuenta='+Cuenta;\n";
          $salida .="window.open(url,'','width=600,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no')\n";
          $salida .="}\n";
          $salida .="</script>\n";
          return $salida;
          break;
      }
      case 'TotalPaciente':
      {
          $salida ="<script>\n";
          $salida .="function TotalPaciente(Cuenta)\n";
          $salida .="{\n";
          $salida .="var url='reports/$VISTA/totalpaciente.php?cuenta='+Cuenta;\n";
          $salida .="window.open(url,'','width=600,height=250,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no')\n";
          $salida .="}\n";
          $salida .="</script>\n";
          return $salida;
          break;
      }
      case 'DetalleCamas':
      {
          $salida ="<script>\n";
          $salida .="function DetalleCamas(Ingreso,Cuenta)\n";
          $salida .="{\n";
          $salida .="var url='reports/$VISTA/movimientoscamas.php?ingreso='+Ingreso+'&cuenta='+Cuenta;\n";
          $salida .="window.open(url,'','width=900,height=550,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no')\n";
          $salida .="}\n";
          $salida .="</script>\n";
          return $salida;
          break;
      }
      case 'DatosSolicitudApoyo':
      {
          $salida ="<script>\n";
          $salida .="function DatosSolicitudApoyo(Solicitud, TipoID, PacienteID, Nombre, Cargo, Titulo)\n";
          $salida .="{\n";
          $salida .="var url='reports/$VISTA/datos_solicitud_apoyo.php?solicitud='+Solicitud+'&tipoid='+TipoID+'&pacienteid='+PacienteID  +'&nombre='+Nombre  +'&cargo='+Cargo +'&titulo='+Titulo;\n";
          $salida .="window.open(url,'','width=600,height=250,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no')\n";
          $salida .="}\n";
          $salida .="</script>\n";
          return $salida;
          break;
      }
      case 'ConsultaDatosSolicitudApoyo':
      {
          $salida ="<script>\n";
          $salida .="function ConsultaDatosSolicitudApoyo(Resultado_id,Sw_Modo_Resultado,paciente_id,tipo_id_paciente,depto,nomemp,fecha_realizado,laboratorio,profesional)\n";
          $salida .="{\n";
          $salida .="var url='reports/$VISTA/consulta_datos_solicitud_apoyo.php?resultado_id='+Resultado_id+'&sw_modo_resultado='+Sw_Modo_Resultado+'&paciente_id='+paciente_id+'&tipo_id_paciente='+tipo_id_paciente+'&depto='+depto+'&nomemp='+nomemp+'&fecha_realizado='+fecha_realizado+'&laboratorio='+laboratorio+'&profesional='+profesional;\n";
          $salida .="window.open(url,'','width=850,height=500,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no,menubar=yes')\n";
          $salida .="}\n";
          $salida .="</script>\n";                                 
          return $salida;
          break;
      }
      //Función javascript para consultar un reporte del MIGE-RAS
      case 'ConsultarReporteMige':
      {
        $salida = "<script language=\"javascript\">\n
                      function ConsultarReporteMige(reporte,params)\n
                      {\n
                          var url='reports/$VISTA/ReporteMige.php?reporte='+reporte+'&'+params;\n
                          window.open(url,'WinRas','width=800,height=600,X=50,Y=50,resizable=no,status=no,scrollbars=yes');\n
                      }\n
                      </script>";
        return $salida;
        break;
      }
      case 'facporlap':
      {
          $salida ="<script>\n";
          $salida .="function facporlap(empresa_id,prefijo,lapso,actualizar)\n";
          $salida .="{\n";
          $salida .="var url='reports/$VISTA/facporlap.php?Empresa_id='+empresa_id+'&Prefijo='+prefijo+'&Lapso='+lapso+'&Actualizar='+actualizar;\n";
          $salida .="window.open(url,'','width=600,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no')\n";
          $salida .="}\n";
          $salida .="</script>\n";
          return $salida;
          break;
      }
  
      case 'facporlap1':
      {
          $salida ="<script>\n";
          $salida .="function facporlap1(empresa_id,prefijo,lapso,actualizar)\n";
          $salida .="{\n";
          $salida .="var url='reports/$VISTA/facporlap1.php?Empresa_id='+empresa_id+'&Prefijo='+prefijo+'&Lapso='+lapso+'&Actualizar='+actualizar;\n";
          $salida .="window.open(url,'','width=600,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no')\n";
          $salida .="LlamarRevi(empresa_id,lapso);";
          $salida .="}\n";
          $salida .="</script>\n";
          return $salida;
          break;
      }
      default:
      {
          return "";
      }
    }
  }
?>