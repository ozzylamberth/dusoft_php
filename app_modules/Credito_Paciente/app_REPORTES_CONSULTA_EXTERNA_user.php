<?php
class app_REPORTES_CONSULTA_EXTERNA_user extends classModulo
{

	function app_REPORTES_CONSULTA_EXTERNA_user()
	{
		return true;
	}

	function main()
	{
		$this->PantallaInicial();
		return true;
	}

	function UsuariosRepconsultaExterna()//Función de permisos
	{
		list($dbconn) = GetDBconn();
		$usuario=UserGetUID();
		$query = "SELECT A.empresa_id,
				B.razon_social AS descripcion1
				FROM userpermisos_RepconsultaExterna AS A,
				empresas AS B
				WHERE A.usuario_id=".$usuario."
				AND A.empresa_id=B.empresa_id
				ORDER BY descripcion1;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var1[$resulta->fields[1]]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		$mtz[0]='EMPRESAS';
		$url[0]='app';
		$url[1]='Reportes Consulta Externa';
		$url[2]='user';
		$url[3]='PantallaInicial';
		$url[4]='permisocredpaci';
		$this->salida .=gui_theme_menu_acceso('REPORTES CONSULTA EXTERNA', $mtz, $var1, $url, ModuloGetURL('system','Menu'));
		return true;
	}

//LlamaFormaSeleccion
	function LlamaFormaSeleccion()
	{
		$this->FormaSeleccion();
		return true;
	}

//BuscarDepartamento()
	function BuscarDepartamento()
	{
		list($dbconn) = GetDBconn();
         $query = "SELECT empresa_id,
				             centro_utilidad,
				             unidad_funcional,
				             departamento,
				             descripcion,
				             servicio
				      FROM departamentos;";
        $resulta = $dbconn->Execute($query);
       if ($dbconn->ErrorNo() != 0)
       {
	 	   $this->error = "Error al Cargar el Modulo";
		   $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
	       return false;
       }
      if(!$resulta->EOF)
       {
	  	 while(!$resulta->EOF)
	  	  {
		 	  	 $dpt[]=$resulta->GetRowAssoc($ToUpper = false);
			  	 $resulta->MoveNext();
	  	  }
	   }

      return $dpt;
	}

//BuscarTipoConsultas
	function BuscarTipoConsultas()
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT tipo_consulta_id,
				         departamento,
				         especialidad,
				         descripcion,
				         hc_modulo
				  FROM tipos_consulta;";
        $resulta = $dbconn->Execute($query);
       if ($dbconn->ErrorNo() != 0)
       {
	 	   $this->error = "Error al Cargar el Modulo";
		   $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
	       return false;
       }
      if(!$resulta->EOF)
       {
	  	 while(!$resulta->EOF)
	  	  {
		 	  	 $Tipo_con[]=$resulta->GetRowAssoc($ToUpper = false);
			  	 $resulta->MoveNext();
	  	  }
	   }

      return $Tipo_con;
	}

	function BuscarProf()
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT A.tipo_id_tercero,
				         A.tercero_id,
				         A.empresa_id,
						 B.nombre
				  FROM profesionales_empresas A, profesionales B
				  WHERE A.tercero_id=B.tercero_id AND A.tipo_id_tercero=B.tipo_id_tercero;";
        $resulta = $dbconn->Execute($query);
       if ($dbconn->ErrorNo() != 0)
       {
	 	   $this->error = "Error al Cargar el Modulo";
		   $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
	       return false;
       }
      if(!$resulta->EOF)
       {
	  	 while(!$resulta->EOF)
	  	  {
		 	  	 $Tipo_con[]=$resulta->GetRowAssoc($ToUpper = false);
			  	 $resulta->MoveNext();
	  	  }
	   }

      return $Tipo_con;
	}
}
?>
