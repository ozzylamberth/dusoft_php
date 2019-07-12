<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: GenerarReporte.class.php,
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */
  /**
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */ 
  class GenerarReporte extends ConexionBD
  {
    /**
    * Contructor
    */
    function GenerarReporte(){}
    /**
    *
    */
    function Listar_Pacientes($FechaInicial,$FechaFinal,$EmpresaId,$offset)
		{
				
        //$this->debug=true;

        $sql = "
		select 									hc.historia_prefijo || '' || hc.historia_numero as historia_clinica,
							paci.tipo_id_paciente,
							paci.paciente_id,
							paci.primer_nombre || ' ' || paci.segundo_nombre || ' ' || paci.primer_apellido || ' ' || paci.segundo_apellido as nombre_paciente,
							paci.sexo_id,
							paci.fecha_nacimiento,
							ingre.ingreso,
							to_char(ingre.fecha_ingreso, 'YYYY-MM-DD') as  fecha_ingreso,
							ingre.fecha_cierre,
							paci.usuario_id,
							hcevol.evolucion_id

							from
							ingresos ingre,
							pacientes paci,
							historias_clinicas hc,
							hc_evoluciones hcevol
							

							where
							date(fecha_ingreso) >= '".$FechaInicial."'
							and
							date(fecha_ingreso) <= '".$FechaFinal."'
							and
							ingre.paciente_id = paci.paciente_id
							and
							ingre.tipo_id_paciente = 								paci.tipo_id_paciente
							and
							paci.paciente_id = hc.paciente_id
							and
							paci.tipo_id_paciente = hc.tipo_id_paciente
							and
							ingre.ingreso = hcevol.ingreso
		"; 
      $sql .= "         ";

//print_r($sql);
/*        $sql = "
		select
							emp.razon_social,
							depto.departamento,
							depto.descripcion,
							hcevol.departamento,
							hcevol.evolucion_id,
							pro.tipo_id_tercero,
							pro.tercero_id,
							pro.nombre,
							esp.especialidad,
							esp.descripcion,
							cue.numerodecuenta,
							serv.descripcion as servicio,
							cue.total_cuenta,
							cue.fecha_registro,
							cue.autorizacion_int,
							hc.historia_prefijo || '' || hc.historia_numero as historia_clinica,
							paci.tipo_id_paciente,
							paci.paciente_id,
							paci.primer_nombre || ' ' || paci.segundo_nombre || ' ' || paci.primer_apellido || ' ' || paci.segundo_apellido as nombre_paciente,
							paci.sexo_id,
							paci.fecha_nacimiento,
							ingre.ingreso,
							to_char(ingre.fecha_ingreso, 'YYYY-MM-DD') as  fecha_ingreso,
							ingre.fecha_cierre,
							paci.usuario_id,
							pla.plan_descripcion,
							tafi.tipo_afiliado_nombre

							from
							empresas emp,
							departamentos depto,
							hc_evoluciones hcevol,
							centros_utilidad centro,
							profesionales_usuarios profu,
							profesionales pro,
							profesionales_especialidades proesp,
							especialidades esp,
							cuentas cue,
							cuentas_detalle cued,
							servicios serv,
							ingresos ingre,
							pacientes paci,
							historias_clinicas hc,
							planes pla,
							tipos_afiliado tafi

							where
							hcevol.departamento = depto.departamento
							and
							depto.centro_utilidad = centro.centro_utilidad
							and
							depto.empresa_id = centro.empresa_id
							and
							centro.empresa_id = emp.empresa_id
							and
							hcevol.usuario_id = profu.usuario_id
							and
							profu.tipo_tercero_id = pro.tipo_id_tercero
							and
							profu.tercero_id = pro.tercero_id
							and  
							pro.tipo_id_tercero = proesp.tipo_id_tercero
							and
							pro.tercero_id = proesp.tercero_id
							and
							proesp.especialidad = esp.especialidad
							and
							hcevol.numerodecuenta = cue.numerodecuenta
							and
							cue.numerodecuenta = cued.numerodecuenta
							and
							cued.servicio_cargo = serv.servicio
							and
							hcevol.ingreso = ingre.ingreso
							and
							date(fecha_ingreso) >= '".$FechaInicial."'
							and
							date(fecha_ingreso) <= '".$FechaFinal."'
							and
							ingre.paciente_id = paci.paciente_id
							and
							ingre.tipo_id_paciente = paci.tipo_id_paciente
							and
							paci.paciente_id = hc.paciente_id
							and
							paci.tipo_id_paciente = hc.tipo_id_paciente
							and
							cue.plan_id = pla.plan_id
							and
							cue.tipo_afiliado_id = tafi.tipo_afiliado_id
		"; 
      $sql .= "         ";                */
			
     if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
       
      $sql .= " Order By ingre.ingreso ";
      $sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset." ";

      


			
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.
			
			while(!$rst->EOF) //Recorriendo el Vector;
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
  

  
  function InfoXPaciente($tipo_id_paciente,$paciente_id,$ingreso,$evolucion_id)
		{
				
        //$this->debug=true;
        $sql = "
		select
							emp.razon_social,
							depto.departamento,
							depto.descripcion as nombre_depto,
							hcevol.departamento,
							hcevol.evolucion_id,
							pro.tipo_id_tercero,
							pro.tercero_id,
							pro.nombre,
							pro.sw_adscrito_planta,
							esp.especialidad,
							esp.descripcion,
							cue.numerodecuenta,
							serv.descripcion as servicio,
							cue.total_cuenta,
							cue.fecha_registro,
							cue.autorizacion_int,
							hc.historia_prefijo || '' || hc.historia_numero as historia_clinica,
							paci.tipo_id_paciente,
							paci.paciente_id,
							paci.primer_nombre || ' ' || paci.segundo_nombre || ' ' || paci.primer_apellido || ' ' || paci.segundo_apellido as nombre_paciente,
							paci.sexo_id,
							paci.fecha_nacimiento,
							ingre.ingreso,
							to_char(ingre.fecha_ingreso, 'YYYY-MM-DD') as  fecha_ingreso,
							ingre.fecha_cierre,
							paci.usuario_id,
							pla.plan_descripcion,
							tafi.tipo_afiliado_nombre

							from
							empresas emp,
							departamentos depto,
							hc_evoluciones hcevol,
							centros_utilidad centro,
							profesionales_usuarios profu,
							profesionales pro,
							profesionales_especialidades proesp,
							especialidades esp,
							cuentas cue,
							cuentas_detalle cued,
							servicios serv,
							ingresos ingre,
							pacientes paci,
							historias_clinicas hc,
							planes pla,
							tipos_afiliado tafi
							where
							hcevol.ingreso = ".$ingreso."
							and
							hcevol.evolucion_id = ".$evolucion_id."
							and
							hcevol.departamento = depto.departamento
							and
							depto.centro_utilidad = centro.centro_utilidad
							and
							depto.empresa_id = centro.empresa_id
							and
							centro.empresa_id = emp.empresa_id
							and
							hcevol.usuario_id = profu.usuario_id
							and
							profu.tipo_tercero_id = pro.tipo_id_tercero
							and
							profu.tercero_id = pro.tercero_id
							and  
							pro.tipo_id_tercero = proesp.tipo_id_tercero
							and
							pro.tercero_id = proesp.tercero_id
							and
							proesp.especialidad = esp.especialidad
							and
							hcevol.numerodecuenta = cue.numerodecuenta
							and
							cue.numerodecuenta = cued.numerodecuenta
							and
							cued.servicio_cargo = serv.servicio
							and
							hcevol.ingreso = ingre.ingreso
							and
							ingre.paciente_id = paci.paciente_id
							and
							ingre.tipo_id_paciente = paci.tipo_id_paciente
							and
							paci.paciente_id = hc.paciente_id
							and
							paci.tipo_id_paciente = hc.tipo_id_paciente
							and
							cue.plan_id = pla.plan_id
							and
							cue.tipo_afiliado_id = tafi.tipo_afiliado_id
		";
      			
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.
			
			while(!$rst->EOF) //Recorriendo el Vector;
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}


		/**********************************************************************************
		*  Ver el movimiento de un documento de Devolucion de productos de farmacia!!
		* 
		* @return token
		************************************************************************************/
	
	function VerDiagnosticoIngreso($EvolucionId)
		{
				
        //$this->debug=true;
        $sql = "SELECT	
					diag.diagnostico_nombre,
					diag.diagnostico_id
			 FROM		
					diagnosticos diag,
					hc_diagnosticos_ingreso hcdiagin
                WHERE
					hcdiagin.evolucion_id = ".$EvolucionId."
					and
					hcdiagin.tipo_diagnostico_id = diag.diagnostico_id
				    ";
      
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.
			
			while(!$rst->EOF) //Recorriendo el Vector;
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
	
	function VerDiagnosticoEgreso($EvolucionId)
		{
				
        //$this->debug=true;
        $sql = "SELECT	
					diag.diagnostico_nombre,
					diag.diagnostico_id
			 FROM		
					diagnosticos diag,
					hc_diagnosticos_egreso hcdiageg
                WHERE
					hcdiageg.evolucion_id = ".$EvolucionId."
					and
					hcdiageg.tipo_diagnostico_id = diag.diagnostico_id
				    ";
      
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.
			
			while(!$rst->EOF) //Recorriendo el Vector;
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
	
    function EdadPaciente($fecha_nacimiento)
		{
				
        //$this->debug=true;
        $sql = "SELECT	edad('".$fecha_nacimiento."') as edad_completa;  ";
      
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.
			
			while(!$rst->EOF) //Recorriendo el Vector;
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
    /**
    *
    */
    function ObtenerReportes($empresa,$datos)
    {
      $sql  = "SELECT reporte_empresa_id,";
      $sql .= "       empresa_id,";
      $sql .= "       nombre_archivo,";
      $sql .= "       titulo_reporte,";
      $sql .= "       modulo,";
      $sql .= "       modulo_tipo ";
      $sql .= "FROM   reportes_empresa ";
      $sql .= "WHERE  empresa_id = '".$empresa."' ";
      $sql .= "AND    modulo = '".$datos['modulo']."' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) 
        return false;

			$datos = array();
			
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
    }
    /**
    *
    */
    function ObtenerReporteSabana($datos)
    {
      $ruta_archivo =  GetVarConfigAplication('DIR_SIIS')."/reportes_sql/".$datos['nombre_archivo'].".sql";
      $lines = file($ruta_archivo);
        
      $sql = "";
      foreach ($lines as $line_num => $line) 
        $sql .= $line;
      
      $sql = str_replace("_1","'".$this->DividirFecha($datos['fecha_inicial'],"-")."'::date",$sql);
      $sql = str_replace("_2","'".$this->DividirFecha($datos['fecha_final'],"-")."'::date",$sql);
      //$this->debug = true;
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
        return $rst;
    }
	}
?>