<?PHP

class inventarios_vencimientos_report
{
	var $datos;

	function inventarios_vencimientos_report($dat=array())
	{ $this->datos=$dat;
	}
	//=================================================================
	
	function CrearReporte()
	{
		$query=""; $htm="";
		$estilo1="text-indent:10pt;";
		$estilo2="border:1px solid #000000;";
		$estilo3="border-top:1px solid #000000";
		
		//---------------Tabla 1-------------------------------------------------------------
		$htm.="<table>";
		$htm.="  <tr>";
		$htm.="    <td class=\"label\" > EMPRESA: </td>";
		$htm.="    <td class=\"normal_10\"> ".$this->datos['empresa_nombre']." </td>";		
		$htm.="  </tr>";
		$htm.="  <tr>";
		$htm.="    <td class=\"label\" > NIT: </td>";
		$htm.="    <td class=\"normal_10\"> ".$this->datos['empresa_nit']." </td>";		
		$htm.="  </tr>";
		$htm.="  <tr>";
		$htm.="    <td class=\"label\" > TELEFONO: </td>";
		$htm.="    <td class=\"normal_10\"> ".$this->datos['empresa_telefono']." </td>";		
		$htm.="  </tr>";
		$htm.="  <tr>";
		$htm.="    <td class=\"label\" > DIRECCION: </td>";
		$htm.="    <td class=\"normal_10\"> ".$this->datos['empresa_direccion']." </td>";		
		$htm.="  </tr>";
		$htm.="</table> <br>";

		//---------------------Tabla con los datos------------------------------------------
		$matrix=$this->consultaProductosVencimientos();
		
		$htm.="<table style=\"$estilo2\" align=\"center\" width=\"90%\" rules=\"all\">";
		$htm.="  <tr>";
		$htm.="    <td class=\"label\" align=\"center\">  <b>BODEGA</b>  </td>";
		$htm.="    <td class=\"label\" align=\"center\">  <b>PRODUCTO</b> </td>";
		//$htm.="    <td class=\"label\" align=\"center\" width=\"10%\">  <b>EXISTENCIA</b> </td>";		
		$htm.="    <td class=\"label\" align=\"center\" width=\"10%\">  <b>LOTE</b> </td>";
		$htm.="    <td class=\"label\" align=\"center\" width=\"8%\">  <b>CANTIDAD EN LOTE</b> </td>";
		$htm.="    <td class=\"label\" align=\"center\" width=\"7%\">  <b>FECHA VENCIMIENTO</b> </td>";
		$htm.="  </tr>";
		
		//--------------------------------------------
		foreach($matrix as $key=> $val)
		{
			$htm.="<tr>";
			$htm.="  <td class=\"normal_10\">".$val["b_desc"]."</td>";
			$htm.="  <td class=\"normal_10\">".$val["descripcion"]."</td>";
			//$htm.="  <td class=\"normal_10\">".$val["existencia"]."</td>";
			$htm.="  <td class=\"normal_10\">".$val["lote"]."</td>";
			$htm.="  <td class=\"normal_10\">".$val["cantidad"]."</td>";
			$htm.="  <td class=\"normal_10\">".$val["fecha_vencimiento"]."</td>";
			$htm.="</tr>";
		}
		
		$htm.="</table>";
		
		//---------------------------------
		$htm .= "	<br><table border='0' width=\"100%\">\n";
		$htm .= "		<tr>\n";
		$htm .= "			<td align=\"justify\" width=\"50%\">\n";
		$htm .= "				<font size='1' face='arial'>\n";
		$htm .= "					Imprimio: ".$this->getNombreUsuario(UserGetUID())."\n";
		$htm .= "				</font>\n";
		$htm .= "			</td>\n";
		$htm .= "			<td align=\"right\" width=\"50%\">\n";
		$htm .= "				<font size='1' face='arial'>\n";
		$htm .= "					Fecha Impresion: ".date("d/m/Y - h:i a")."\n";
		$htm .= "				</font>\n";
		$htm .= "			</td>\n";
		$htm .= "		</tr>\n";
		$htm .= "	</table>\n";
		
		return $htm;
	}
	
	//==============================================================
	function GetMembrete()
	{
		$est  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:10pt\"";
		//$est = " style=\"font-family: sans_serif, Verdana, helvetica, Arial;\"";
		$titulo .= "<b $est >".$this->datos['razon_social']."<br>";
		$titulo .= "PRODUCTOS VENCIMIENTOS";
		
		$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo, 'subtitulo'=>' ','logo'=>'logocliente.png','align'=>'left'));
		return $Membrete;
	}
	
	//======================================================================
	function consultaProductosVencimientos()
	{
		$emp_id=$this->datos['empresa_id'];
		$prod_cod=$this->datos['producto'];
		$bod_cod=$this->datos['bodega'];
		$fecha=$this->datos['fecha'];
		$periodo=$this->datos['periodo'];
	
		$filtro_producto="";    $filtro_bodega="";    $filtro_fecha="";  $filtro_periodo="";
		//------------------Realiza los filtros de la busqueda---------------------
		if($prod_cod=='-1' && $bod_cod=='-1' && strlen($fecha)==0) //Busca todos
		{ $filtro_producto="";    $filtro_bodega="";    $filtro_fecha=""; $filtro_periodo="";
		}
		else
		{
			if($prod_cod!='-1')   $filtro_producto="and q.codigo_producto='".$prod_cod."'";
			if($bod_cod!='-1')    $filtro_bodega="and q.bodega='".$bod_cod."'";
		  
		  	if(strlen($fecha)>0)
			{
				$fechaResult=$this->validarFecha($fecha);
				
				if($fechaResult) //Si la fecha es valida
				{	switch($periodo)
					{	case "antes"; $filtro_periodo="<"; break;
						case "igual"; $filtro_periodo="="; break;
						case "despues"; $filtro_periodo=">"; break;
					}
					
					$filtro_fecha="and f.fecha_vencimiento".$filtro_periodo."'".$fechaResult."' ";
				}
				else
					$filtro_fecha="and f.fecha_vencimiento='1111-11-11' "; //Para que la consulta no arroje un error
			}
			else
				$filtro_fecha="";
		}
		
		$query="SELECT q.b_desc, q.descripcion, q.existencia,  f.lote, f.cantidad, f.fecha_vencimiento
				FROM
				(
					SELECT e.codigo_producto, e.existencia, b.bodega, b.descripcion as b_desc, e.centro_utilidad,ip.descripcion, i.empresa_id 
        			FROM existencias_bodegas e, bodegas b, inventarios_productos ip, inventarios i 
					WHERE 
					b.bodega=e.bodega and e.centro_utilidad=b.centro_utilidad and ip.codigo_producto=e.codigo_producto 
        			and i.codigo_producto=e.codigo_producto and i.empresa_id=e.empresa_id
				) as q
				LEFT JOIN bodegas_documentos_d_fvencimiento_lotes f ON 
				f.codigo_producto=q.codigo_producto and f.centro_utilidad=q.centro_utilidad and q.bodega=f.bodega 
				WHERE q.empresa_id='".$emp_id."' ".$filtro_producto." ".$filtro_bodega." ".$filtro_fecha." 
				ORDER BY q.b_desc";
		
		
		$vec=$this->consultaBD($query,2);
			
		return $vec;
	}
	
	//===================================================================
	function getNombreUsuario($usuarioId)
	{
		$sql="SELECT nombre FROM system_usuarios WHERE usuario_id = ".$usuarioId." ";
		
		if(!$result = $this->consultaBD($sql,1))
			return false;

		$nombre=$result->fields[0];
		
		$result->Close();
		return $nombre;
	}
	
	//==================================================================
	function consultaBD($query, $tipoRetorno)
	{
		list($dbconn)=GetDBConn();
		//$dbconn->debug = true;
		//$dbconn->SetFetchMode(ADODB_FETCH_ASSOC);
		$result=$dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0 || !$result)
		{
			$this->error = "Error en la Consulta";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			echo "<br>ERROR EN LA CONEXION O CONSULTA";
			$result->close();
			
			return false;
		}
		else
		{
			if($tipoRetorno==2) //Retorna la matriz con los datos
			{
				while(!$result->EOF)
				{  
					$matriz[]=$result->GetRowAssoc($ToUpper=false);
					$result->MoveNext();
				}
				
				$result->close();
				return $matriz;
			}
			else  //Retorna el Recordset
			{ return $result; }
		}
	}
	
	//========================================================================
	function validarFecha($fecha)
	{
		$vec=explode("/",$fecha);
	
		if(count($vec)!=3)
		{
			$vec=explode("-",$fecha);
			
			if(count($vec)!=3) return false;
		}
		
		if( (strlen($vec[0])==4 && strlen($vec[1])==2 && strlen($vec[2])==2) || (strlen($vec[0])==2 && strlen($vec[1])==2 && strlen($vec[2])==4) )
		{
			if(strlen($vec[2])==4)
				$fecha2=$vec[2]."-".$vec[1]."-".$vec[0];
			else
				$fecha2=$vec[0]."-".$vec[1]."-".$vec[2];
				
			return $fecha2;
		}
		else
			return false;
	}
}

?>