function mOvr(src,clrOver) {;
    src.style.background = clrOver;
}

function mOut(src,clrIn) {
    src.style.background = clrIn;
}

function Redire(ingreso, numerodecuenta, ingresosestado, cuentaestado, paciente_id, rutica){
    xajax_Activar_IngCue(ingreso, numerodecuenta, ingresosestado, cuentaestado, paciente_id);
//    window.location.href = rutica;
}

