



function valores_resultado_insercion(html){
  document.getElementById('MostrarCargosOtraCuenta').innerHTML=html;  
}

function CargoCuentaInicial(valor,plan,Cuenta,pagina){  
    var cadena=new Array();   
    cadena[0]=valor;   
    cadena[1]=plan;   
    cadena[2]=Cuenta;                                       
    cadena[3]=pagina;                                       
    jsrsExecute("app_modules/Facturacion/ScriptRemoting/divisionCuentas.php", valores_resultado_insercion, "InsertarDatosDivisionCuentaCargosInicial",cadena);          
}

function AbonoCuentaInicial(valor,plan,Cuenta,pagina){  
    var cadena=new Array();   
    cadena[0]=valor;   
    cadena[1]=plan;   
    cadena[2]=Cuenta;                                       
    cadena[3]=pagina;                                       
    jsrsExecute("app_modules/Facturacion/ScriptRemoting/divisionCuentas.php", valores_resultado_insercion, "InsertarDatosDivisionCuentaAbonosInicial",cadena);          
}

function CrearVariables(cadena){
  
  jsrsExecute("app_modules/Facturacion/ScriptRemoting/divisionCuentas.php", valores_resultado_insercion, "ActualizarBarraNavegador",cadena);          
}




