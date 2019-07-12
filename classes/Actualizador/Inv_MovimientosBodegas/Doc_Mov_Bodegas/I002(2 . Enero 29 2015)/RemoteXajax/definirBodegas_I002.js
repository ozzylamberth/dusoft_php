function ActuEstado(bodegas_doc_id, doc_tmp_id, estados, tipo_documento)
{
    //alert(bodegas_doc_id);
    xajax_Actualizartmp(bodegas_doc_id, doc_tmp_id, document.getElementById('estados').value, tipo_documento);
}
function BuscarProductos(pagina, orden, param1, param2, doc_tmp_id, bodegas_doc_id)
{
    xajax_ListadoProductos(pagina, orden, param1, param2, doc_tmp_id, bodegas_doc_id);
}

function Paginador(codigo, descripcion, doc_tmp_id, bodegas_doc_id, proveedor, orden, offset)
{
    xajax_BuscarProductos(codigo, descripcion, doc_tmp_id, bodegas_doc_id, proveedor, orden, offset);
}

function recogerTeclaBus(evt)
{
    var keyCode = document.layers ? evt.which : document.all ? evt.keyCode : evt.keyCode;
    var keyChar = String.fromCharCode(keyCode);

    if (keyCode == 13)
    {
        BuscarProductos('1', xGetElementById('orden').value, xGetElementById('descripcion').value, xGetElementById('codigo_barras').value, xGetElementById('doc_tmp_id').value, xGetElementById('bodegas_doc_id').value);
    }
}


function acceptm(evt)
{
    var nav4 = window.Event ? true : false;
    var key = nav4 ? evt.which : evt.keyCode;
    return (key != 13);
}

function Aplicar(busqueda)
{

    if (busqueda == 1)
    {
        cad = "DESCRIPCION <input type=\"text\" class=\"input-text\" id=\"criterio\" name=\"criterio\" size=\"50\" onkeypress=\"return acceptm(event);\" onkeydown=\"recogerTeclaBus(event)\" value=\"\">\n";
    }
    else
    {
        cad = "CODIGO <input type=\"text\" class=\"input-text\" id=\"criterio\" name=\"criterio\" size=\"30\" onkeypress=\"return acceptNum(event);\" onkeydown=\"recogerTeclaBus(event)\" value=\"\">\n";
    }

    document.getElementById('ventanatabla').innerHTML = cad;
}

function NewDocumentoTmp(observacion, orden, bodegas_doc_id, tipo_doc_bodega_id)
{
    if (!orden)
    {
        document.getElementById('mensaje_error').innerHTML = "SELECCIONE LA ORDEN DE COMPRA";
    }
    else
    {
        xajax_NewDocumentoTmp(observacion, orden, bodegas_doc_id, tipo_doc_bodega_id);
    }
}

/*function AgregarItemFOC(doc_tmp_id,codigo,cantidad,valor,iva,bodegas_doc_id,lote,fecha_vencimiento,localizacion,ItemId,ValorUnitarioFactura)
 {
 xajax_AgregarItem(doc_tmp_id,codigo,can,valor,iva,bodegas_doc_id,lote,fecha_vencimiento,localizacion,ItemId,ValorUnitarioFactura);
 }*/

function EliminarItem(item_id, doc_tmp_id, bodegas_doc_id)
{
    xajax_EliminarItem(item_id, doc_tmp_id, bodegas_doc_id);
}

function FormaConfirm(item_id, doc_tmp_id, bodegas_doc_id)
{
    xajax_FormaConfirm(item_id, doc_tmp_id, bodegas_doc_id);
}

function RegresarDEliminar()
{
    document.FormaE.submit();
}

function Imprimir(direccion, empresa_id, prefijo, numero)
{
    var url = direccion + "?empresa_id=" + empresa_id + "&prefijo=" + prefijo + "&numero=" + numero;
    window.open(url, '', 'width=700,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no,menubar=yes,toolbar=yes');
}

function RegresarCrear()
{
    document.formaC.submit();
}

function cargar()
{
    document.forma1.action += "&DATOS[accion]=EDITAR&DATOS[bodegas_doc_id]=" + document.forma1.bodegas_doc_id.value + "&DATOS[tipo_doc_bodega_id]=" + document.forma1.tipo_doc_bodega_id.value + "&DATOS[doc_tmp_id]=" + document.forma1.doc_tmp_id.value;

    document.forma1.submit();
}

function acceptNum(evt)
{
    var nav4 = window.Event ? true : false;
    var key = nav4 ? evt.which : evt.keyCode;
    return (key < 13 || (key >= 48 && key <= 57) || key == 45 || key == 46);
}

function Ingreso_Factura(numero_factura, fecha_factura, observaciones, orden, proveedor, valor_factura)
{
    if (numero_factura == "" || fecha_factura == "" || observaciones == "")
    {
        alert("Faltan Datos por Diligenciar!!!");
        alert(valor_factura);
        return(false);
    }
    else
    {
        //alert(proveedor);
        xajax_Ingreso_factura(numero_factura, fecha_factura, observaciones, orden, proveedor, valor_factura);
        return(true);
    }
}

function activar_todos(valor)
{


    for (i = 1; i < document.forma_checks.elements.length; i++)
    {
        if (document.forma_checks.elements[i].type == "checkbox")
        {
            if (document.getElementById('activar').checked == 1)
            {
                document.forma_checks.elements[i].checked = 1;
            }
            else
            {
                document.forma_checks.elements[i].checked = 0;
            }
        }
    }

}