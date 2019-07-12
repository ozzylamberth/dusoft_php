// Funcion para aceptar solamente numeros
function acceptNum(evt){ 
// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57, '.' = 46 
var key =  evt.keyCode; 
return (key <= 13 || (key >= 48 && key <= 57) || (key == 46));
}

