var nextinput = 0;
function AgregarCampos(){
    nextinput++;
    if(nextinput === 1){
        campo = '<dt><h6>Proveedor:</h6></dt> <dd><h6>Material:</h6></dd>';
        $("#titulo").append(campo);
    }
    campo = '<dt><select name="proveedor[]" class="check input-small"></select></dt><dd><input type="text" name="material[]" class="input-small"></dd>';
    $.post("http://localhost/bareaarquitectos/proveedor/proveedores",{}, function(data) {
                $(".check").html(data);
    });
    $("#campos").append(campo);
}