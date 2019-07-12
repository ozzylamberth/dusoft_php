<?php

/**
 * $Id: plan_2.php,v 1.5 2005/06/07 19:13:17 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

    class plan_2 extends BDAfiliadosMC
    {

        var $salida=array();

        function plan_2($tipoidpaciente,$paciente,$dbtype,$dbhost,$dbuser,$dbpass,$dbname,$dbtabla,$fecha_radicacion,$fecha_vencimiento,$datos)
        {
						$this->tipoidpaciente=$this->CovertirTipoDocumento($tipoidpaciente);
            //$this->tipoidpaciente=$tipoidpaciente;
            $this->paciente=$paciente;
						$this->dbtype=$dbtype;
						$this->dbhost=$dbhost;
						$this->dbuser=$dbuser;
						$this->dbpass=$dbpass;
						$this->dbname=$dbname;
						$this->campo_nombre_tabla=$dbtabla;
						$this->fecha_radicacion=$fecha_radicacion;
						$this->fecha_vencimiento=$fecha_vencimiento;
						$this->configurarBD(&$datos);
            return true;
        }

        function ConfigurarBD($datos)
        {
            $this->campo_Primer_nombre=$datos['campo_Primer_nombre'];
            $this->campo_Segundo_nombre=$datos['campo_Segundo_nombre'];
            $this->campo_Primer_apellido=$datos['campo_Primer_apellido'];
            $this->campo_Segundo_apellido=$datos['campo_Segundo_apellido'];
            $this->campo_tipo_afiliado=$datos['campo_tipo_afiliado'];
            $this->campo_activo=$datos['campo_activo'];
            $this->campo_urgencias=$datos['campo_urgencias'];
            $this->campo_fecha_urgencias=$datos['campo_fecha_urgencias'];
            $this->campo_tipodocumento=$datos['campo_tipodocumento'];
            $this->campo_documento=$datos['campo_documento'];
            $this->campo_empleador=$datos['campo_empleador'];
            $this->campo_edad=$datos['campo_edad'];
            $this->campo_sexo=$datos['campo_sexo'];
            $this->campo_nivel=$datos['campo_nivel'];
            $this->campo_nombre_completo=$datos['campo_nombre_completo'];
            $this->campo_fecha_nacimiento=$datos['campo_fecha_nacimiento'];
            $this->campo_semanas_cotizadas=$datos['campo_semanas_cotizadas'];
						$this->campo_estado_bd=$datos['campo_activo'];
						//cambio dar
            $this->campo_tipo_empleador=$datos['campo_tipo_empleador'];
            $this->campo_id_empleador=$datos['campo_id_empleador'];
						//fin cambio
						
						$this->campo_vigencia_inicial=$datos['campo_vigencia_inicial'];
						$this->campo_fecha_retiro=$datos['campo_fecha_retiro'];
						$this->campo_proteccion_laboral=$datos['campo_proteccion_laboral'];
						$this->campo_direccion_afiliado=$datos['campo_direccion_afiliado'];
						$this->campo_telefono_afiliado=$datos['campo_telefono_afiliado'];
						$this->campo_ciudad_afiliado=$datos['campo_ciudad_afiliado'];
						$this->campo_identificacion_cotizante=$datos['campo_identificacion_cotizante'];
						$this->campo_tipo_contrato=$datos['campo_tipo_contrato'];
						$this->campo_direccion_empresa=$datos['campo_direccion_empresa'];
						$this->campo_telefono_empresa=$datos['campo_telefono_empresa'];
						$this->campo_codigo_presta_medico=$datos['campo_codigo_presta_medico'];
						$this->campo_tipo_afiliacion=$datos['campo_tipo_afiliacion'];


            $this->formato_fechas='';//Ejemplo MM/DD/AAAA or DD/MM/AA
            return true;
        }

        function RetornarDatosCompletos()
        {
					if($this->BuscarDatos()===false)
					{
						return false;
					}
					if(empty($this->datosCompletos))
					{
						$this->error="NO EXISTE EL USUARIO";
						$this->mensajeDeError = "EL USUARIO TIPO IDENTIFICACION=$this->tipoidpaciente Y IDENTIFICACION=$this->paciente NO EXISTE";
						return false;
					}
					$this->RetornarDatos();
					return $this->salida;
        }
				
				
				function ConvertirResult($result)
				{
					while(!$result->EOF)
					{
						$x=$result->GetRowAssoc(false);
						$prueba[]=array('nombre'=>$x['nombre'],'tipodocumento'=>$x['tipodocumento'],'documento'=>$x['documento'],'estado'=>$x['estado']);
						$result->MoveNext();
					}
					$result->close();
					return $prueba;
				}
				
				//CAMBIO DAR
				//convierte el tipo q viene del combo nuestro al de la bd
				function CovertirTipoDocumento($tipoidpaciente)
				{
						switch($tipoidpaciente)
						{
									case 'CC':
														{
																$tipoidpaciente='CC';
																break;
														}
									case 'CE':
														{
																$tipoidpaciente='CE';
																break;
														}
									case 'TI':
														{
																$tipoidpaciente='TI';
																break;
														}
									case 'RC':
														{
																$tipoidpaciente='RC';
																break;
														}
									case 'PA':
														{
																$tipoidpaciente='PA';
																break;
														}
									case 'MS':
														{
																$tipoidpaciente='MS';
																break;
														}
									case 'NU':
														{
																$tipoidpaciente='NU';
																break;
														}
									case 'AS':
														{
																$tipoidpaciente='AS';
																break;
														}
									default:
														{
																$tipoidpaciente='CC';
																break;
														}
						}
						return $tipoidpaciente;
				}

				function TipoDocumento($tipoidpaciente)
				{
						switch($tipoidpaciente)
						{
									case 'CC':
														{
																$tipoidpaciente='CC';
																break;
														}
									case 'CE':
														{
																$tipoidpaciente='CE';
																break;
														}
									case 'TI':
														{
																$tipoidpaciente='TI';
																break;
														}
									case 'RC':
														{
																$tipoidpaciente='RC';
																break;
														}
									case 'PA':
														{
																$tipoidpaciente='PA';
																break;
														}
									case 'MS':
														{
																$tipoidpaciente='MS';
																break;
														}
									case 'NU':
														{
																$tipoidpaciente='NU';
																break;
														}
									case 'AS':
														{
																$tipoidpaciente='AS';
																break;
														}
									default:
														{
																$tipoidpaciente='CC';
																break;
														}
						}
						return $tipoidpaciente;
				}
				//FIN CAMBIO DAR

        function ProgramaActividad($act)
        {
            return 1;
        }

        function ProgramaUrgencias($urg)
        {
            switch($urg){
                                    case 'Activo':
                                                                {
                                                                    $urgencias=1;
                                                                }
                                    }
            return $urgencias;
        }

        function ProgramaFechaUrgencias($fecha)
        {
            if(empty($this->formato_fechas))
            {
                if($fecha<=4)
                {
                    $urgencias=1;
                }
                else
                {
                    $urgencias=0;
                }
            }
            else
            {
                $formato=explode('/',$this->formato_fechas);
                $datos=explode('/',$fecha);
                switch($formato[0]){
                                                        case 'AA':
                                                                            $ano=$datos[0];
                                                                            break;
                                                        case 'DD':
                                                                            $dia=$datos[0];
                                                                            break;
                                                        case 'AAAA':
                                                                                $ano=$datos[0];
                                                                                break;
                                                        case 'MM':
                                                                            $mes=$datos[0];
                                                                            break;
                                                    }
                switch($formato[1]){
                                                        case 'AA':
                                                                            $ano=$datos[1];
                                                                            break;
                                                        case 'DD':
                                                                            $dia=$datos[1];
                                                                            break;
                                                        case 'AAAA':
                                                                                $ano=$datos[1];
                                                                                break;
                                                        case 'MM':
                                                                            $mes=$datos[1];
                                                                            break;
                                                    }
                switch($formato[2]){
                                                        case 'AA':
                                                                            $ano=$datos[2];
                                                                            break;
                                                        case 'DD':
                                                                            $dia=$datos[2];
                                                                            break;
                                                        case 'AAAA':
                                                                                $ano=$datos[2];
                                                                                break;
                                                        case 'MM':
                                                                            $mes=$datos[2];
                                                                            break;
                                                    }
                if(date("Y-m-d",mktime(1,1,1,$mes,$dia,$ayo))<=date("Y-m-d"))
                {
                    $urgencias=1;
                }
                else
                {
                    $urgencias=0;
                }
            }
            return $urgencias;
        }

        function ProgramaNivel($niv)
        {
            /*switch($niv){
                                        case 'A':
                                                        $niveles=1;
                                                        break;
                                        case 'B':
                                                        $niveles=2;
                                                        break;
                                        case 'C':
                                                        $niveles=3;
                                                        break;
                                    }*/
            return $niv;
        }

        function ProgramaTipoAfiliado($tipo)
        {
            switch($tipo){
                                        case '1 Cotizante';
                                                                                $tipoafiliado=2;
                                                                                break;
                                        case '2 Hijo':
                                                                    $tipoafiliado=1;
                                                                    break;
                                        case '4 Conyuge':
                                                                            $tipoafiliado=1;
                                                                            break;
                                        case '6 Padre Beneficiario':
                                                                                                $tipoafiliado=1;
                                                                                                break;
                                        case '7 Compa�ro':
                                                                            $tipoafiliado=1;
                                                                            break;

                                        }
            return $tipoafiliado;
        }




				function SqlGetPacientesConNombres($nombres,$apellidos)
				{
					if($this->campo_nombre_completo)
					{
						if($nombres)
						{
							$busqueda=$this->campo_nombre_completo." LIKE '%$nombres%' ";
							if($apellidos)
							{
								$busqueda.="AND ".$this->campo_nombre_completo." LIKE '%$apellidos%';";
							}
						}
						else
						{
							if($apellidos)
							{
								$busqueda=$this->campo_nombre_completo." LIKE '%$apellidos%';";
							}
						}
						$nombre=$this->campo_nombre_completo;
					}
					else
					{
						if($nombres)
						{
							$busqueda=$this->campo_Primer_nombre." || ' ' || ".$this->campo_Segundo_nombre." LIKE '%$nombres%' ";
							if($apellidos)
							{
								$busqueda.='AND '.$this->campo_Primer_apellido. " || ' ' || ".$this->campo_Segundo_apellido." LIKE '%$apellidos%'";
							}
						}
						else
						{
							if($apellidos)
							{
								$busqueda=$this->campo_Primer_apellido." || ' ' || ".$this->campo_Segundo_apellido." LIKE '%$apellidos%'";
							}
						}
						$nombre=$this->campo_Primer_nombre." || ' ' || ".$this->campo_Segundo_nombre." || ' ' || ".$this->campo_Primer_apellido. " || ' ' || ".$this->campo_Segundo_apellido;
					}
						$sql="select ".$this->campo_tipodocumento." as tipodocumento, ".$this->campo_documento." as documento, $nombre as nombre  from \"".$this->campo_nombre_tabla."\"
						 			where $busqueda LIMIT 100 OFFSET 0;";
					return $sql;
				}


    }//fin clase

?>
