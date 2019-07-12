<?php
  /**
  * Submodulo de Diagnosticos Ingreso.
  *
  * Submodulo para manejar los Diagnosticos de ingreso (rips) en un paciente en una evolución.
  * @author Jaime Andres Valencia Salazar <jaimeandresvalencia@telesat.com.co

  * Modificado por
  * @author Claudia Liliana Zuñiga Cañon <claudia_zc@hotmail.com
  * Jun/02/2004

  * @version 1.0
  * @package SIIS
  * $Id: hc_DiagnosticoI.php,v 1.10 2008/12/15 18:08:53 hugo Exp $
  */
  /**
  * DiagnosticoI
  *
  * Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
  * en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
  * submodulo del diagnostico de ingreso .
  */
  include_once "hc_modules/DiagnosticosdeVIH/hc_DiagnosticosdeVIH.php";
  include_once "hc_modules/DiagnosticosdeVIH/hc_DiagnosticosdeVIH_HTML.php";
  class DiagnosticoI extends hc_classModules
  {
  	var $limit;
  	var $conteo;
  	var $capitulo='';
  	var $grupo='';
  	var $categoria='';
    /**
    * Esta función Inicializa las variable de la clase
    *
    * @access public
    * @return boolean Para identificar que se realizo.
    */
  	function DiagnosticoI()
  	{
  		if(!empty($_REQUEST['capitulo']))
  		{
  			$this->capitulo=$_REQUEST['capitulo'];
  		}
  		if(!empty($_REQUEST['grupo']))
  		{
  			$this->grupo=$_REQUEST['grupo'];
  		}
  		if(!empty($_REQUEST['categoria']))
  		{
  			$this->categoria=$_REQUEST['categoria'];
  		}

  		$this->limit=GetLimitBrowser();
  		$this->salida = '';
  		return true;
  	}
    /**
    * Esta función retorna los datos de la impresión de la consulta del submodulo.
    *
    * @access private
    * @return text Datos HTML de la pantalla.
    */
  	function GetConsulta()
  	{
  		$pfj=$this->frmPrefijo;
  		$accion='accion'.$pfj;
  		if(empty($_REQUEST[$accion]))
  		{
  			$this->frmConsulta();
  		}
  		return $this->salida;
  	}
    /**
    * Esta metodo captura los datos de la impresión de la Historia Clinica.
    * @access private
    * @return text Datos HTML de la pantalla.
    */
  	function GetReporte_Html()
  	{
  		$imprimir=$this->frmHistoria();
  		if($imprimir==false)
  		{
  			return true;
  		}
  		return $imprimir;
  	}
    /**
    * Esta función verifica si este submodulo fue utilizado para la atencion de un paciente.
    *
    * @access private
    * @return text Datos HTML de la pantalla.
    */
  	function GetEstado()
  	{
  		$pfj=$this->frmPrefijo;
      list($dbconn) = GetDBconn();
  		$query="SELECT count(*)
  				FROM hc_diagnosticos_ingreso AS A,
  					 diagnosticos AS B, hc_evoluciones AS C
  				WHERE A.tipo_diagnostico_id=B.diagnostico_id
  				AND A.evolucion_id = C.evolucion_id
  				AND C.ingreso = ".$this->ingreso.";";
  		$resulta = $dbconn->Execute($query);
  		if ($dbconn->ErrorNo() != 0)
  		{
  			$this->error = "Error al Cargar el Modulo";
  			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  			return false;
  		}
  		$i=0;
  		while(!$resulta->EOF)
  		{
  			$estado=$resulta->GetRowAssoc($ToUpper = false);
  			$resulta->MoveNext();
  			$i++;
  		}

  		if ($estado[count] == 0)
  			return false;
  		else
  		{
  		 	$datos = $this->ObtenerFichas();
        if(sizeof($datos) >= 1) return false;
        
        return true;
  		}
  	}
    /**
    * Esta función retorna la presentación del submodulo (consulta o inserción).
    *
    * @access public
    * @return text Datos HTML de la pantalla.
    * @param text Determina la acción a realizar.
    */
  	function GetForma()
  	{
  		$pfj=$this->frmPrefijo;
  		if(empty($_REQUEST['accion'.$pfj]) && empty($_REQUEST['accion']))
  		{
  		    $this->frmForma();
  		}
  		else
  		{
  			if($_REQUEST['accion'.$pfj]=='cambiar_diagnostico')
        {
          $this->CambiarDiagnosticos();
          $this-> frmForma();
        }
  			else if($_REQUEST['accion'.$pfj]=='Busqueda_Avanzada_Diagnosticos')
        {
          $vectorD= $this->Busqueda_Avanzada_Diagnosticos();
          $this-> frmForma($vectorD);
        }
  			else if($_REQUEST['accion'.$pfj]=='insertar_varios_diagnosticos')
        {
          $this->Insertar_Varios_Diagnosticos();
          $this-> frmForma();
        }
  			else if($_REQUEST['accion'.$pfj]=='eliminar_diagnostico')
        {
          $this->Eliminar_Diagnostico_Solicitado($_REQUEST['diagnostico_id'.$pfj]);
          $this->frmForma();
        }
  			else if($_REQUEST['accion'.$pfj]=='cambiar_descripcion')
        {
          $this->CambiarDescripcion();
        }
  			else if($_REQUEST['accion'.$pfj]=='Insertar_Descripcion')
        {
          $this->InsertarDescripcion();
          $this-> frmForma();
        }
  			else if($_REQUEST['accion'.$pfj]=='Volver_Original')
        {
          $this-> frmForma();
        }
        else
        {
          $this->SetJavaScripts("Ocupaciones");
          $htm = new DiagnosticosdeVIH_HTML();
          $htm->datosPaciente = $this->datosPaciente;
          $htm->evolucion = $this->evolucion;
          $htm->empresa_id = $this->empresa_id;
          $htm->paso = $this->paso;
          $htm->datosResponsable = $this->datosResponsable;
            
          $htm->GetForma();
          $this->salida = $htm->salida;
        }
  		}
  		return $this->salida;
  	}



	function CambiarDiagnosticos()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
          $query="SELECT a.tipo_diagnostico_id,
          			a.evolucion_id 
                  FROM hc_diagnosticos_ingreso AS a, hc_evoluciones AS b 
                  WHERE b.ingreso=".$this->ingreso." AND a.evolucion_id = b.evolucion_id 
                  AND a.sw_principal = '1';";
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al insertar en hc_diagnosticos_ingreso";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[0]." YA FUE ASIGNADO.";
			return false;
		}
          $diagnosticos = $resulta->FetchRow();
          
          if(!empty($diagnosticos))
          {
               $sql="UPDATE hc_diagnosticos_ingreso 
               	 SET sw_principal='0' 
               	 WHERE evolucion_id=".$diagnosticos[1]."
               	 AND tipo_diagnostico_id='".$diagnosticos[0]."';";
               $resulta=$dbconn->Execute($sql);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al insertar en hc_diagnosticos_ingreso";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[0]." YA FUE ASIGNADO.";
                    return false;
               }
               $sql="update hc_diagnosticos_ingreso set sw_principal='1' where evolucion_id=".$_REQUEST['evolucion'.$pfj]." and tipo_diagnostico_id='".$_REQUEST['diagnostico_id'.$pfj]."';";
               $resulta=$dbconn->Execute($sql);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al insertar en hc_diagnosticos_ingreso";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[0]." YA FUE ASIGNADO.";
                    return false;
               }
          }
		$this->RegistrarSubmodulo($this->GetVersion());
    return true;
	}

	/**
	* Esta función inserta los datos del submodulo.
	*
	* @access private
	* @return boolean Informa si lo logro o no.
	*/
	//cor - clzc - ads
	function Insertar_Varios_Diagnosticos()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		foreach($_REQUEST['opD'.$pfj] as $index=>$codigo)
		{
			$tipo_dx = $_REQUEST['dx'.$index.$pfj];
			if($tipo_dx == '')
				$tipo_dx = '1';
			
      $arreglo=explode(",",$codigo);

      //BUSQUEDA DE DX REPETIDO EN INGRESO
      $query="SELECT count(*) 
           FROM hc_diagnosticos_ingreso AS a,
             hc_evoluciones AS b
           WHERE a.evolucion_id = b.evolucion_id
           AND b.ingreso=".$this->ingreso."
           AND tipo_diagnostico_id = '".$arreglo[0]."';";

      $resulta=$dbconn->Execute($query);
			if ($resulta->fields[0]==0)
      { 
        $llenar = "0";
        if($tipo_dx == '2')
        {
          if($this->datosPaciente['sw_ficha'] == '1')
          {
            $llenar = "2";
          }
          else
          {
            $sql  = "SELECT grupo_ficha_id ";
            $sql .= "FROM   diagnosticos ";
            $sql .= "WHERE  diagnostico_id = '".$arreglo[0]."' ";

            $rst = $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0)
            {
              $this->error = "Error al insertar en hc_diagnosticos_ingreso";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              $this->frmError["MensajeError"] = " ERROR EN EL SELECT ".$sql;
              return false;
            }
            
            if($rst->fields[0]) $llenar = "1";
          }
        }
        //BUSQUEDA DE DX PRINCIPAL EN INGRESO
        $sql="SELECT count(*) 
              FROM  hc_diagnosticos_ingreso AS a,
                    hc_evoluciones AS b
              WHERE a.evolucion_id = b.evolucion_id
              AND   b.ingreso=".$this->ingreso.";";
        $resulta=$dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al insertar en hc_diagnosticos_ingreso";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[0]." YA FUE ASIGNADO.";
          return false;
        }
                    //INSERCION DE 1 DX PRINCIPAL
                    if($resulta->fields[0]==0)
                    {
                         $query="INSERT into hc_diagnosticos_ingreso
                                        (usuario_id,tipo_diagnostico_id,evolucion_id,sw_principal,descripcion,tipo_diagnostico, sw_ficha_llena)
                                 VALUES('".$this->usuario_id."','".$arreglo[0]."'
                                        ,'$this->evolucion','1',NULL,'$tipo_dx','".$llenar."');";
                    }
                    //INSERCION DE LOS DEMAS DX'S (NO PRINCIPALES)
                    else
                    {
                         $query="INSERT into hc_diagnosticos_ingreso
                                        (usuario_id,tipo_diagnostico_id,evolucion_id,sw_principal,descripcion,tipo_diagnostico, sw_ficha_llena)
                                 VALUES('".$_SESSION['SYSTEM_USUARIO_ID']."','".$arreglo[0]."'
                                        ,'$this->evolucion','0',NULL,'$tipo_dx','".$llenar."');";
                    }
                    $resulta=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al insertar en hc_diagnosticos_ingreso";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         $this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[0]." YA FUE ASIGNADO.";
                         return false;
                    }
                    else
                    {
                         $this->RegistrarSubmodulo($this->GetVersion());
                         $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
                    }
      }
      //FIN BUSQUEDA DE DX REPETIDO EN INGRESO
      else
      {
        $this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[0]." YA FUE ASIGNADO.";
      }
		}// Fin foreach
		return true;
	}
	/**
	* Esta función borra los datos del submodulo.
	*
	* @access private
	* @return boolean Informa si lo logro o no.
	*/

	//cor - clzc - ads
	function Eliminar_Diagnostico_Solicitado($diagnostico_id)
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query="delete from hc_diagnosticos_ingreso where tipo_diagnostico_id = '$diagnostico_id'	and evolucion_id=".$this->evolucion.";";
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR EL DIAGNOSTICO";
			return false;
		}
		else
		{
			$sql="SELECT a.tipo_diagnostico_id, a.sw_principal, a.evolucion_id
               	 FROM hc_diagnosticos_ingreso AS a,
	                     hc_evoluciones AS b
                     WHERE a.evolucion_id = b.evolucion_id
                     AND b.ingreso=".$this->ingreso." LIMIT 1 OFFSET 0;";
			$resulta=$dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "NO HAY DIAGNOSTICOS DISPONIBLES";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
			}
			else
			{
				$vector=$resulta->GetRowAssoc($ToUpper = false);
			}
   
			if ($_REQUEST['principal'.$pfj]=='1')
			{
				$sql2="update hc_diagnosticos_ingreso set sw_principal='1' where evolucion_id=".$vector['evolucion_id']." and tipo_diagnostico_id='".$vector['tipo_diagnostico_id']."';";
				$resulta=$dbconn->Execute($sql2);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al insertar en hc_diagnosticos_ingreso";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				$this->RegistrarSubmodulo($this->GetVersion());
        return true;
			}
		}
		$this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[0]." FUE ELIMINADO SATISFACTORIAMENTE.";
		$this->RegistrarSubmodulo($this->GetVersion());
    return true;
	}
  /**
  *
  */
  function ObtenerFichas()
	{
    $sql  = "SELECT GF.descripcion, ";
    $sql .= "       GF.grupo_ficha_id,";
    $sql .= "       HI.tipo_diagnostico_id,";
    $sql .= "       HI.evolucion_id, ";
    $sql .= "       DG.diagnostico_id ";
    $sql .= "FROM   hc_diagnosticos_ingreso HI, ";
    $sql .= "       diagnosticos DG, ";
    $sql .= "       hc_evoluciones HE, ";
    $sql .= "       grupos_fichas GF ";
    $sql .= "WHERE  HI.tipo_diagnostico_id = DG.diagnostico_id ";
    $sql .= "AND    HI.evolucion_id = HE.evolucion_id ";
    $sql .= "AND    HE.ingreso = ".$this->ingreso." ";
    $sql .= "AND    DG.grupo_ficha_id = GF.grupo_ficha_id ";
    $sql .= "AND    HI.sw_ficha_llena = '1' ";
    $sql .= "ORDER BY GF.descripcion ";

    list($dbconn) = GetDBconn();
    $rst = $dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0)
    {
      $this->error = "Error al buscar en la consulta de diagnosticos de ingreso";
      echo $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }
    
    $datos = array(); 
    while (!$rst->EOF)
    {
      $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();
    }
    
    $rst->Close();
    
		return $datos;
	}
  /**
  *
  */
  function VerificarFicha()
	{
    $sql  = "SELECT sw_ficha ";
    $sql .= "FROM   pacientes ";
    $sql .= "WHERE  paciente_id = '".$this->datosPaciente['paciente_id']."' ";
    $sql .= "AND    tipo_id_paciente = '".$this->datosPaciente['tipo_id_paciente']."' ";
    
    list($dbconn) = GetDBconn();
    $rst = $dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0)
    {
      $this->error = "Error al buscar en la consulta de diagnosticos de ingreso";
      echo $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }
    
    $datos = array(); 
    if (!$rst->EOF)
    {
      $datos = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();
    }
    
    $rst->Close();
    
		return $datos['sw_ficha'];
	}
  /**
  *
  */
  function ConsultaDiagnosticoI()
	{
          $pfj=$this->frmPrefijo;
          list($dbconnect) = GetDBconn();
          $query = "select  b.diagnostico_id,
                            b.diagnostico_nombre, 
                            a.evolucion_id, 
                            a.sw_principal,
                            a.descripcion, 
                            a.tipo_diagnostico 
                    from    hc_diagnosticos_ingreso as a, 
                            diagnosticos as b,
                            hc_evoluciones c
                    where   a.tipo_diagnostico_id=b.diagnostico_id
                    and     a.evolucion_id = c.evolucion_id
                    and     c.ingreso = ".$this->ingreso."
                    order by b.diagnostico_id ";

          $result = $dbconnect->Execute($query);

          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de diagnosticos de ingreso";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
		     return false;
          }
          else
          { 
          	$i=0;
               while (!$result->EOF)
               {
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
               }
          }
		return $vector;
	}


     function ConsultaDiagnosticoEspecifico($diagnostico)
     {
          $pfj=$this->frmPrefijo;
          list($dbconnect) = GetDBconn();
          $query = "select diagnostico_id,diagnostico_nombre,sw_principal, descripcion from hc_diagnosticos_ingreso,diagnosticos where hc_diagnosticos_ingreso.tipo_diagnostico_id=diagnosticos.diagnostico_id
                    and diagnostico_id=".$diagnostico." order by diagnostico_id;";
     
          $result = $dbconnect->Execute($query);
     
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de diagnosticos de ingreso";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          { 
               $i=0;
               while (!$result->EOF)
               {
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
               }
          }
          return $vector;
     }




	//cor - clzc-jea - ads
	function Busqueda_Avanzada_Diagnosticos()
	{
		$pfj=$this->frmPrefijo;

		$FechaInicio = $this->datosPaciente[fecha_nacimiento];
		$FechaFin = date("Y-m-d");
		$edad_paciente = CalcularEdad($FechaInicio,$FechaFin);

		list($dbconn) = GetDBconn();
          $codigo = STRTOUPPER ($_REQUEST['codigo'.$pfj]);
		$diagnostico  =STRTOUPPER($_REQUEST['diagnostico'.$pfj]);

		$busqueda1 = '';
		$busqueda2 = '';

		if ($codigo != '')
		{
			$busqueda1 =" WHERE diagnostico_id LIKE '$codigo%'";
		}

		if (($diagnostico != '') AND ($codigo != ''))
		{
               if (eregi('%',$diagnostico))
               {
			    $busqueda2 ="AND diagnostico_nombre LIKE '$diagnostico'";
               }
               else
               {
                    $busqueda2 ="AND diagnostico_nombre LIKE '%$diagnostico%'";
               }
		}

		if (($diagnostico != '') AND ($codigo == ''))
		{
               if (eregi('%',$diagnostico))
               {
                    $busqueda2 ="WHERE diagnostico_nombre LIKE '$diagnostico'";
               }
               else
               {
                    $busqueda2 ="WHERE diagnostico_nombre LIKE '%$diagnostico%'";
               }
		}

		if(empty($_REQUEST['conteo'.$pfj]))
		{
			$query = "SELECT count(*)
                         FROM diagnosticos
                         $busqueda1 $busqueda2";

			$resulta = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}
		else
		{
			$this->conteo=$_REQUEST['conteo'.$pfj];
		}
		if(!$_REQUEST['Of'.$pfj])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of'.$pfj];
			if($Of > $this->conteo)
			{
				$Of=0;
				$_REQUEST['Of'.$pfj]=0;
				$_REQUEST['paso1'.$pfj]=1;
			}
		}


		//filtro por clasificacion de diagnosticos
		$filtro='';
		if(empty($busqueda1) AND empty($busqueda2))
		{
			$filtro = "WHERE (sexo_id='".$this->datosPaciente['sexo_id']."' OR sexo_id is null)
					 AND   (edad_max>=".$edad_paciente[edad_en_dias]." OR edad_max is null)
					 AND   (edad_min<=".$edad_paciente[edad_en_dias]." OR edad_min is null)";
		}
		else
		{
			$filtro = "AND (sexo_id='".$this->datosPaciente['sexo_id']."' OR sexo_id is null)
					 AND (edad_max>=".$edad_paciente[edad_en_dias]." OR edad_max is null)
					 AND (edad_min<=".$edad_paciente[edad_en_dias]." OR edad_min is null)";
		}

		$filtro1='';
		if(!empty($this->capitulo))
		{
			$filtro1 = " AND (B.capitulo='".$this->capitulo."' OR B.capitulo is null)";
		}
		if(!empty($this->grupo))
		{
			$filtro1 .= " AND (B.grupo='".$this->grupo."' OR B.grupo is null)";
		}
		if(!empty($this->categoria))
		{
			$filtro1 .= " AND (B.categoria='".$this->categoria."' OR B.categoria is null)";
		}

		$query = "SELECT diagnostico_id, diagnostico_nombre
                    FROM diagnosticos
                    $busqueda1 $busqueda2
                    $filtro $filtro1
                    order by diagnostico_id
                    LIMIT ".$this->limit." OFFSET $Of;";
		$resulta = $dbconn->Execute($query);
		//$this->conteo=$resulta->RecordCount();
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}

		if($this->conteo==='0')
		{
			$this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
			return false;
		}
		return $var;
	}

     function InsertarDescripcion()
     {
          $pfj=$this->frmPrefijo;
          list($dbconnect) = GetDBconn();
          $query = "UPDATE hc_diagnosticos_ingreso
                    SET descripcion = '".$_REQUEST['descripcion_diag'.$pfj]."'
                    WHERE evolucion_id=".$this->evolucion." AND
                    tipo_diagnostico_id='".$_REQUEST['codigo'.$pfj]."';";
          $result = $dbconnect->Execute($query);
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de diagnosticos de ingreso";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          {
               $this->RegistrarSubmodulo($this->GetVersion());
               return $_REQUEST['descripcion_diag'.$pfj];
               
          }
     }

}

?>
