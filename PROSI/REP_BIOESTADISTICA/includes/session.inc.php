<?php

/**
 * $Id: session.inc.php,v 1.4 2005/07/12 22:13:14 claudia Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

	function SessionConfig()
	{
	  global $NEWSIIS_ID_SESSION;

		ini_set('session.use_trans_sid', 1);
		ini_set('session.use_cookies', 1);
    if($SESSION_ID_EMERGENTE)
		{
		  ini_set('session.name',$SESSION_ID_EMERGENTE);
		}
		else
		{
		  ini_set('session.name','SIIS_SID');
		}
		ini_set('session.cookie_lifetime', 0);
		ini_set('session.gc_probability', 100);
    ini_set('session.gc_maxlifetime', 100 * 60);

    return true;
	}

  function SesionStart()
  {
		session_start();
		Header('Cache-Control: no-cache');
		Header('Pragma: no-cache');
  }


  function SessionInit()
	{
		$sessid = session_id();

		$ipaddr=GetIPAddress();

		list($dbconn) = GetDBconn();

		$query = "SELECT ip_address
              FROM system_session
              WHERE session_id = '$sessid'";

		$result = $dbconn->Execute($query);

		if($dbconn->ErrorNo() != 0) {
			return false;
		}


		if (!$result->EOF) {
			$result->Close();
			if(SessionCurrent($sessid)){
         return false;
			}
		} else {
			if(SessionNew($sessid, $ipaddr)){
         return false;
			}
			srand((double)microtime()*1000000);
			SessionSetVar('rand', rand());
		}

		return true;
	}


	function SessionCurrent($sessid)
	{

		list($dbconn) = GetDBconn();


		$query = "UPDATE system_session SET ultimo_acceso_session = " . time() . "
				      WHERE session_id = '$sessid'";


		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			return false;
		}

		return true;
	}


	function SessionNew($sessid, $ipaddr)
	{
		list($dbconn) = GetDBconn();

	  $query = "INSERT INTO system_session
              (session_id,ip_address,usuario_id,inicio_session,ultimo_acceso_session)
              VALUES ('$sessid','$ipaddr',0," . time() . "," . time() . ")";

		$dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			return false;
		}
		return true;
	}



?>
