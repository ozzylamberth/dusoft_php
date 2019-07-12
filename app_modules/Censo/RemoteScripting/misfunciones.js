/**
 * Funcion llamada desde el HTML como evento, ejecuta el remoteScripting jsrsExecute()
 */
function GetEstaciones( departamento )
{
	jsrsExecute("app_modules/Censo/RemoteScripting/procesos.php", MostrarEstaciones, "GetEstaciones",  departamento);
}

/**
 * Llama la funcion GetPlanes de php la cual retorna el objeto select con los planes
 */
function GetPlanes( tercero )
{
	jsrsExecute("app_modules/Censo/RemoteScripting/procesos.php", MostrarPlanes, "GetPlanes", tercero);
}

/**
 * Muestra el select retornado por GetEstaciones
 */
function MostrarEstaciones( cadena )
{
	document.getElementById('Estaciones').innerHTML = cadena;
}

/**
 * Muestra el select retornado por GetPlanes
 */
function MostrarPlanes( cadena )
{
	document.getElementById('Planes').innerHTML = cadena
}