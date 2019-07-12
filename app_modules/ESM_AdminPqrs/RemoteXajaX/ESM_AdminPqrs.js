var responsable = "";
var buscador = new Buscador({
    llamado: "xajax_buscarProducto",
    mincaracteres: 3,
    prompt: ["descripcion", "codigo_producto"]
});

var areacodigo;

function respansableCambia(el) {
    areacodigo = el.options[el.selectedIndex].getAttribute("rel");
    var areaid = el.options[el.selectedIndex].value;

    document.getElementById("responsable_area").value = areacodigo;
    adicionarFormularioPorArea(areaid);
}

function adicionarFormularioPorAreaProducto(areaid) {
    var numeroForm=document.getElementById("numero").value;
    xajax_formularioLogistica(areaid,numeroForm,1);
}

function adicionarFormularioPorArea(areaid) {

    var contenedor = document.getElementById("contenedorFormularioCaso");
    contenedor.innerHTML = "";

    //cargar formulario por tipo de area
    //servicio al cliente
    document.getElementById("observaciontitulo").innerHTML = "OBSERVACION Y/O SEGUIMIENTO CASO";
    if (areacodigo === "SC002") {
        document.getElementById('seleccioncliente').style.display = 'none';
        document.getElementById('columna_tercero_seleccionado').style.display = 'none';
        document.getElementById('tercero_id_seleccionado').value = '';
        document.getElementById('tipo_tercero_id_seleccionado').value = '';
        xajax_formularioServicioAlCliente();
    } else if (areacodigo === "LO001") {
        //logistica
        document.getElementById("observaciontitulo").innerHTML = "NOVEDAD";
        xajax_formularioLogistica(areaid,1,0);
    }

    //cargar categoria del caso por area 
    xajax_obtenerCategoriaPorArea(areaid);
    xajax_obtenerPrioridadPorArea(areaid);

}

//callback para cuando se agregan los formularios
function formularioAgregado(tipo,numero_form,i) {
    if (tipo === "logistica") {
//        alert(numero_form);
        for(var i=1;i<=numero_form;i++){
        var producto = document.getElementById("nombreproducto"+i);
        buscador.setElement(producto);
        }
    }
}


/** logica servicio cliente**/
function buscarClientePorId(campo_documento, campo_tipo_documento, callback) {
    var id = document.getElementById(campo_documento).value;
    var tipo = document.getElementById(campo_tipo_documento).value;
    var msgerror = document.getElementById('error');

    msgerror.innerHTML = "";

    if (id === "") {
        msgerror.innerHTML = "SE DEBE INGRESAR NUMERO DEL DOCUMENTO";
        return;
    }

    if (tipo === "") {
        msgerror.innerHTML = "SE DEBE SELECCIONAR EL TIPO DE DOCUMENTO";
        return;
    }
    xajax_buscarCliente(id, tipo, callback);
}


function buscarClientePorIdLogistica(campo_documento, campo_tipo_documento, callback){
    var id = document.getElementById(campo_documento).value;
    var tipo = document.getElementById(campo_tipo_documento).value;
    var msgerror = document.getElementById('error');

    msgerror.innerHTML = "";

    if (id === "") {
        msgerror.innerHTML = "SE DEBE INGRESAR NUMERO DEL DOCUMENTO";
        return;
    }

    if (tipo === "") {
        msgerror.innerHTML = "SE DEBE SELECCIONAR EL TIPO DE DOCUMENTO";
        return;
    }
    xajax_buscarClienteLogistica(id, tipo, callback);
}



function asignarDatosClienteTipo(datos) {
    
    if(datos.tercero_id === undefined){
        return;
    }
    var tipo_documento = document.getElementById('tipo_id_tercero');
    var cliente_seleccionado = document.getElementById('nombre_tercero_seleccionado');
    document.getElementById('tercero_id_seleccionado').value = datos.tercero_id;
    document.getElementById('tipo_tercero_id_seleccionado').value = tipo_documento.value;
    cliente_seleccionado.innerHTML = datos.nombre_tercero + " --- " + tipo_documento.value + " " +datos.tercero_id;
    ;
}

//callback de buscar xajax_buscarCliente
function asignarDatosCliente(datos) {

    var nombres = document.getElementById("nombres");
    var apellidos = document.getElementById("apellidos");
    var fechaNacimiento = document.getElementById("fecha_naci");
    var direccion = document.getElementById("direccion");
    var telefono = document.getElementById("telefono");
    var celular = document.getElementById("celular");
    var email = document.getElementById("email");
    var sexo = document.getElementById("sexo");
    var cedula = document.getElementById("cedula");
    var cedulaencontrada = document.getElementById("cedulaencontrada");
    var tipoencontrado = document.getElementById("tipoencontrado");
    var tipo = document.getElementById("tipodocumento");


    //limpiar campos 
    nombres.value = "";
    apellidos.value = "";
    fechaNacimiento.value = "";
    direccion.value = "";
    telefono.value = "";
    celular.value = "";
    email.value = "";
    sexo.value = "";
    //   cedula.value = "";

    //validar objeto cliente
    if (datos.primer_nombre === undefined) {
        return false;
    }

    nombres.value = datos.primer_nombre + " " + datos.segundo_nombre;
    apellidos.value = datos.primer_apellido + " " + datos.segundo_apellido;
    fechaNacimiento.value = datos.fecha_nacimiento;
    direccion.value = datos.residencia_direccion;
    telefono.value = datos.residencia_telefono;
    celular.value = datos.celular_telefono;
    email.value = datos.email;
    sexo.value = datos.sexo_id;
    tipoencontrado.value = tipo.value;
    cedulaencontrada.value = cedula.value;

}


function validarFormularioServicioAlCliente(forma) {
    var cedulaencontrada = document.getElementById("cedulaencontrada");
    var msgerror = document.getElementById('error');

    if (cedulaencontrada.value.length === 0) {
        msgerror.innerHTML = "DEBE DIGITAR DOCUMENTO VALIDO";
        return false;
    }

    if (forma.farmacia.value === "0") {
        msgerror.innerHTML = "SE DEBE SELECCIONAR UNA FARMACIA";
        return;
    }
    asignarBodegaCentroUtilidad(forma);

    return true;
}

function asignarBodegaCentroUtilidad(forma) {
    if(forma.farmacia.selectedIndex < 0){
        return;
    }
    
    if(forma.farmacia.options[forma.farmacia.selectedIndex].getAttribute("rel")){
        
        var datos = forma.farmacia.options[forma.farmacia.selectedIndex].getAttribute("rel").split('_');
        var empresa = datos[0];
        var utilidad = datos[1];

        forma.centro_utilidad.value = utilidad;
        forma.empresa_id.value = empresa;
    }
}
/****/

/***Logica logistica*/

function validarFormularioLogistica(forma) {
    var msgerror = document.getElementById('error');

    var tercero_seleccionado = document.getElementById('tercero_id_seleccionado');
    var tipo_cliente = document.getElementById('tipo_cliente');

    if (tipo_cliente.value === 'CL' && tercero_seleccionado.value.length === 0) {
        msgerror.innerHTML = "EL TERCERO NO ES VALIDO";
        return false;
    } else if (tipo_cliente.value === 'FM') {

        if (forma.farmacia.value === "0") {
            msgerror.innerHTML = "SE DEBE SELECCIONAR UNA FARMACIA";
            return false;
        }
    }


    if (forma.fecharecepcion.value.length === 0) {
        msgerror.innerHTML = "DEBE DIGITAR LA FECHA DE RECEPCION";
        return false;
    }

    if (forma.tipodocumento.value.length === 0) {
        msgerror.innerHTML = "DEBE DIGITAR EL TIPO DE DOCUMENTO";
        return false;
    }

    if (forma.numerodocumento.value.length === 0) {
        msgerror.innerHTML = "DEBE DIGITAR EL NUMERO DEL DOCUMENTO";
        return false;
    }

    if(forma.numerocasos.value>0){        
        for(var i=1;forma.numerocasos.value>=i;i++){
            if(document.getElementById('productoid'+i).value==''){
                msgerror.innerHTML = "DEBE DIGITAR UN PRODUCTO Y SELECCIONARLO DE LA LISTA";
                return false;
            }
            if(!validarNumero(document.getElementById('cantidaddespachada'+i).value)){
                msgerror.innerHTML = "LA CANTIDAD DESPACHADA DEBE SER UN NUMERO VALIDO";
                return false;
            }
            if(document.getElementById('cantidadrecibida'+i).value.length === 0){
                msgerror.innerHTML = "DEBE DIGITAR LA CANTIDAD RECIBIDA";
                return false;
            }
            
            if(document.getElementById('novedad'+i).value  === '-1'){
                msgerror.innerHTML =  "DEBE DIGITAR LA NOVEDAD";
                return false;
            }
        }
    }



//    if (forma.cantidaddespachada.value.length === 0) {
//        msgerror.innerHTML = "DEBE DIGITAR LA CANTIDAD DESPACHADA";
//        return false;
//    }

//    if (!validarNumero(forma.cantidaddespachada.value)) {
//        msgerror.innerHTML = "LA CANTIDAD DESPACHADA DEBE SER UN NUMERO VALIDO";
//        return false;
//    }

//    if (forma.cantidadrecibida.value.length === 0) {
//        msgerror.innerHTML = "DEBE DIGITAR LA CANTIDAD RECIBIDA";
//        return false;
//    }

//    if (!validarNumero(forma.cantidadrecibida.value)) {
//        msgerror.innerHTML = "LA CANTIDAD RECIBIDA DEBE SER UN NUMERO VALIDO";
//        return false;
//    }
    
    asignarBodegaCentroUtilidad(forma);
    /* if(forma.novedad.value.length === 0){
     msgerror.innerHTML =  "DEBE DIGITAR LA NOVEDAD";
     return false;
     }*/

    return true;

}


/***/


function validarFormularioCaso(forma) {
    if (areacodigo === "SC002") {
        return  validarFormularioServicioAlCliente(forma);
    } else if (areacodigo === "LO001") {
        return  validarFormularioLogistica(forma);
    }
}

function validarNumero(n) {
    n = n.toString();
    var n1 = Math.abs(n),
            n2 = parseInt(n, 10);
    return !isNaN(n1) && n2 === n1 && n1.toString() === n;
}



function actualizarcaso() {
    var check = document.getElementById("cerrar_caso");
    var form = document.getElementById("actualizar_caso");
    var respuesta = document.getElementById("observacionAct");
    var codigocaso = document.getElementById("codigocaso");

    if (codigocaso.value.match(/^SC/)) {
        codigocaso.value = 2;
    } else if (codigocaso.value.match(/^LO/)) {
        codigocaso.value = 1;
    }

    if (respuesta.value.length == 0) {
        alert("Se debe ingresar la respuesta");
        return false;
    }


    if (check && check.checked) {
        var calificacion = document.getElementById("calificacion");

        if (calificacion.value == "0") {
            alert("Favor califique el caso para cerrarlo");
            return false;
        }
    }

    form.submit();

}


function imprimirseguimiento() {
    window.print();
    return;
}
