var html="";

function LlenarVector(cadena)
{
    if(cadena!=-1)
    {
        jsrsExecute("app_modules/AuditoriaMedica/RemoteScripting/procesos.php", PintarTabla, "get_valores",  cadena  );
    }
}

function PintarTabla(cadena)
{
    if(cadena!='')
    {
        document.getElementById('tipo_auditoria').innerHTML=cadena;
    }
}

function Eliminar(cadena)
{
        jsrsExecute("app_modules/AuditoriaMedica/RemoteScripting/procesos.php", PintarTabla, "EliminarVector", cadena);

}
