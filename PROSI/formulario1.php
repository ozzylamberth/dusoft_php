<table id="laTabla">
    <tr>
        <td>TEXTO A</td>
        <td><input type="checkbox" name="Grupo_1A" value="XXX"></td>
        <td><input type="checkbox" name="Grupo_2A" value="YYY"></td>
    </tr>
    <tr>
        <td>TEXTO B</td>
        <td><input type="checkbox" name="Grupo_1B" value="XXX"></td>
        <td><input type="checkbox" name="Grupo_2B" value="YYY"></td>
    </tr>
    <tr>
        <td>TEXTO C</td>
        <td><input type="checkbox" name="Grupo_1C" value="XXX"></td>
        <td><input type="checkbox" name="Grupo_2C" value="YYY"></td>
    </tr>
</table>

<input type="button" onclick="todosCheck('laTabla','1')" value="marca sólo grupo 1" />
<input type="button" onclick="todosCheck('laTabla','2')" value="marca sólo grupo 2" />

<script>

function todosCheck(tabla,grupo) {
    var checks=document.getElementById(tabla).getElementsByTagName("input");
    for(var i in checks) {
        if( checks[i].type=="checkbox" && checks[i].name.indexOf("Grupo_"+grupo)!=-1 )
            checks[i].checked="checked";
        else
            checks[i].checked="";
    }
}

</script> 