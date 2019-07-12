<?php

/**
 * $Id: security.inc.php,v 1.2 2005/06/07 19:03:54 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * API para el Manejo de  los Modulos de la aplicacion
 */

	function PermisosAcceso($TipoAcceso,$Seleccion)
	{

	}

	function ReturnMenuAcceso($TipoAcceso)
	{
			switch($TipoAcceso)
			{
					case '';
					break;
			}



			$SystemId=UserGetUID();
			list($dbconn) = GetDBconn();
			$query = "select b.tipo_admision_id, b.descripcion as descadmon, c.empresa_id,
								c.centro_utilidad, b.sw_todos_cu, d.razon_social, e.descripcion,
								b.punto_admision_id, b.sw_triage, b.departamento, c.descripcion as descdpto,
								f.unidad_funcional, f.descripcion as decunid, b.sw_soat
								from puntos_admisiones_usuarios as a, puntos_admisiones as b,
								departamentos as c, empresas as d, centros_utilidad as e,
								unidades_funcionales as f
								where a.usuario_id=$SystemId and b.tipo_admision_id='UR'
								and a.punto_admision_id=b.punto_admision_id and b.departamento=c.departamento
								and d.empresa_id=c.empresa_id and c.empresa_id=e.empresa_id
								and c.centro_utilidad=e.centro_utilidad and e.empresa_id=f.empresa_id
								and e.centro_utilidad=f.centro_utilidad and c.unidad_funcional=f.unidad_funcional
								order by f.empresa_id, f.centro_utilidad, f.unidad_funcional";
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					return false;
			}
			while($data = $resulta->FetchRow())
			{
							$vect[$data[5]][$data[6]][$data[12]][$data[10]][$data[1]]=1;//cant de admisiones
							$emp[$data[5]]+=1; //cant de empresas
							$cu[$data[5]][$data[6]]+=1; //cant de centros utilidad
							$dpto[$data[5]][$data[6]][$data[10]][$data[12]]+=1; //cant de deptos
							$unid[$data[5]][$data[6]][$data[12]]+=1; //cant de unidades
			}
			$resulta=$dbconn->Execute($query);
			$resulta
			$i=0;
			while(!$resulta->EOF)
			{
					$arreglo[$i]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
					$i++;
			}

			$resulta->Close();
	}


?>
