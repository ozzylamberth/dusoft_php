

function CambioSuministro(valor){
  jsrsExecute("app_modules/DatosLiquidacionQX/ScriptRemoting/gases.php", valores_resultado, "TiposFrecuenciasSuministrosGases", valor);  
}

function valores_resultado(html){
  document.getElementById('frecuencia').innerHTML=html;
}

function valores_resultado_insercion(html){
  document.getElementById('MostrarDatosGases').innerHTML=html;
  Cerrar('d2Container');
}

function EliminarGasAnestesico(vectorContador){
  jsrsExecute("app_modules/DatosLiquidacionQX/ScriptRemoting/gases.php", valores_resultado_insercion, "EliminarGasAnestesicoVector", vectorContador);  
}