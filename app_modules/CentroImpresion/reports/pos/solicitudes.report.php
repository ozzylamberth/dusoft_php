<?php
//Reporte de ordenservicio para impresora pos

//Un reporte es una clase con el nombre de reporte y el sufijo '_report'
class solicitudes_report extends pos_reports_class
{

    //constructor por default
    function solicitudes_report()
    {
        $this->pos_reports_class();
        return true;
    }

		/**
		*
		*/
    function CrearReporte()
    {

        $reporte=&$this->driver; //obtener el driver
        $datos=&$this->datos; //obtener los datos enviados al reporte.
				$reporte->PrintFTexto($datos[0][razon_social],true,$align='center',false,true);
				$reporte->SaltoDeLinea();
				$reporte->PrintFTexto($datos[0][tipo_id_tercero].' '.$datos[0][id],false,'center',false,false);
				$reporte->PrintFTexto($datos[0][direccion].' '.$datos[0][municipio].' '.$datos[0][departamento],false,'center',false,false);
				$reporte->SaltoDeLinea();
      	$reporte->PrintFTexto('Fecha    : '.date('d/m/Y h:m'),false,'left',false,false);
				$var=$this->NombreUsuario();
				$reporte->PrintFTexto('Atendio  : '.$datos[0][usuario_id].' - '.$datos[0][usuario],false,'left',false,false);
				$reporte->SaltoDeLinea();
				$reporte->PrintFTexto('Identifi : '.$datos[0][tipo_id_paciente].' '.$datos[0][paciente_id],false,'left',false,false);
				$reporte->PrintFTexto('Paciente : '.$datos[0][nombre],false,'left',false,false);
				$reporte->PrintFTexto('Cliente  : '.$datos[0][nombre_tercero],false,'left',false,false);
				$reporte->PrintFTexto('Plan     : '.$datos[0][plan_descripcion],false,'left',false,false);
				$reporte->PrintFTexto('Tipo Afi : '.$datos[0][tipo_afiliado_nombre].'     Rango: '.$datos[0][rango],false,'left',false,false);
				$fech=explode(".",$datos[0][fecha]);

				for($i=1; $i<sizeof($datos);$i++)
				{
						    $reporte->SaltoDeLinea();
								$reporte->PrintFTexto($datos[$i][hc_os_solicitud_id].' - '.$datos[$i][cargos].' - ( '.$datos[$i][cantidad].' )'.$datos[$i][descar],false,'left',false,false);
                if(!empty($datos[$i][trap]))
								{  $reporte->PrintFTexto($datos[$i][trap].' días de Tramite.',false,'left',false,false);  }
								elseif(!empty($datos[$i][tra]))
								{  $reporte->PrintFTexto($datos[$i][tra].' días de Tramite.',false,'left',false,false);  }
    		}
				$reporte->SaltoDeLinea();
				//verifica si el proveedor es interno
				$reporte->PrintEnd();
				//$reporte->OpenCajaMonedera();
				$reporte->PrintCutPaper();
        return true;
    }

		/**
		*
		*/
		function NombreUsuario()
		{
					list($dbconn) = GetDBconn();
					$querys = "select usuario_id, usuario,nombre
											from system_usuarios
											where usuario_id=".UserGetUID()."";
					$result = $dbconn->Execute($querys);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
					}

					$var=$result->GetRowAssoc($ToUpper = false);
					return $var;
		}

		/**
		*
		*/
		function BuscarRecomendaciones($cargo)
		{
					list($dbconn) = GetDBconn();
					$querys = "select *
											from hc_apoyod_requisitos
											where cargo=$cargo";
					$result = $dbconn->Execute($querys);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
					}
					if(!$result->EOF)
 					{  $var=$result->GetRowAssoc($ToUpper = false);  }
					return $var;
		}

}
?>
