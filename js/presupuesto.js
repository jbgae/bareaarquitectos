$(document).ready(function() { 
    $('select[name=tipo]').change(function(){ //select

        var $selectedOption = $(this).find('option:selected','select[name=tipo]');
        var i = 0;
        
        if($selectedOption.val() != 0){ 
            $('.control-group').remove();
            
            if(($selectedOption.val() == 1 || $selectedOption.val() == 3 || $selectedOption.val() == 4) ){               
                 $('<div class="control-group">\n\
                        <span class="control-label span3">Redacción de proyecto básico:</span>\n\
                        <div class="controls form-inline">\n\
                            <div class="span3">\n\
                                <label for="can' + i + '">Cantidad:</label>\n\
                                <input type="text" class="cantidad input-small" name="cantidad[]" id="can' + i + '" placeholder="Cantidad">\n\
                            </div>\n\
                            <div class="span3">\n\
                            <label for="desc' + i + '">Descuento:</label>\n\
                            <input type="text" class="descuento input-small" name="descuento[]" id="desc' + i + '" placeholder="Descuento">\n\
                            </div>\n\
                            <div class="span3">\n\
                                <label for="to' + i + '">Total:</label>\
                                <input type="text" class="total input-small" name="total[]" id="to' + i + '" placeholder="Total">\n\
                            </div>\n\
                        </div>\n\
                    </div>').fadeIn("slow").appendTo('#extender');
                
                i++;
                
                 $('<div class="control-group">\n\
                        <span class="control-label span3"> Redacción de p. de ejecución:</span>\n\
                        <div class="controls form-inline">\n\
                            <div class="span3">\n\
                                <label for="can' + i + '">Cantidad:</label>\n\
                                <input type="text" class="cantidad input-small" name="cantidad[]" id="can' + i + '" placeholder="Cantidad">\n\
                            </div>\n\
                            <div class="span3">\n\
                            <label for="desc' + i + '">Descuento:</label>\n\
                            <input type="text" class="descuento input-small" name="descuento[]" id="desc' + i + '" placeholder="Descuento">\n\
                            </div>\n\
                            <div class="span3">\n\
                                <label for="to' + i + '">Total:</label>\
                                <input type="text" class="total input-small" name="total[]" id="to' + i + '" placeholder="Total">\n\
                            </div>\n\
                        </div>\n\
                    </div>').fadeIn("slow").appendTo('#extender');
                
                i++;
                
                 $('<div class="control-group">\n\
                        <span class="control-label span3"> Dirección de obras:</span>\n\
                        <div class="controls form-inline">\n\
                            <div class="span3">\n\
                                <label for="can' + i + '">Cantidad:</label>\n\
                                <input type="text" class="cantidad input-small" name="cantidad[]" id="can' + i + '" placeholder="Cantidad">\n\
                            </div>\n\
                            <div class="span3">\n\
                            <label for="desc' + i + '">Descuento:</label>\n\
                            <input type="text" class="descuento input-small" name="descuento[]" id="desc' + i + '" placeholder="Descuento">\n\
                            </div>\n\
                            <div class="span3">\n\
                                <label for="to' + i + '">Total:</label>\
                                <input type="text" class="total input-small" name="total[]" id="to' + i + '" placeholder="Total">\n\
                            </div>\n\
                        </div>\n\
                    </div>').fadeIn("slow").appendTo('#extender');
                 i++;
                 
                 $('<div class="control-group">\n\
                        <span class="control-label span3"> Dirección de ejecución de obras:</span>\n\
                        <div class="controls form-inline">\n\
                            <div class="span3">\n\
                                <label for="can' + i + '">Cantidad:</label>\n\
                                <input type="text" class="cantidad input-small" name="cantidad[]" id="can' + i + '" placeholder="Cantidad">\n\
                            </div>\n\
                            <div class="span3">\n\
                            <label for="desc' + i + '">Descuento:</label>\n\
                            <input type="text" class="descuento input-small" name="descuento[]" id="desc' + i + '" placeholder="Descuento">\n\
                            </div>\n\
                            <div class="span3">\n\
                                <label for="to' + i + '">Total:</label>\
                                <input type="text" class="total input-small" name="total[]" id="to' + i + '" placeholder="Total">\n\
                            </div>\n\
                        </div>\n\
                    </div>').fadeIn("slow").appendTo('#extender');
                 
                 i++;
                 
                 $('<div class="control-group">\n\
                        <span class="control-label span3"> Coordinación de seguridad y salud:</span>\n\
                        <div class="controls form-inline">\n\
                            <div class="span3">\n\
                                <label for="can' + i + '">Cantidad:</label>\n\
                                <input type="text" class="cantidad input-small" name="cantidad[]" id="can' + i + '" placeholder="Cantidad">\n\
                            </div>\n\
                            <div class="span3">\n\
                            <label for="desc' + i + '">Descuento:</label>\n\
                            <input type="text" class="descuento input-small" name="descuento[]" id="desc' + i + '" placeholder="Descuento">\n\
                            </div>\n\
                            <div class="span3">\n\
                                <label for="to' + i + '">Total:</label>\
                                <input type="text" class="total input-small" name="total[]" id="to' + i + '" placeholder="Total">\n\
                            </div>\n\
                        </div>\n\
                    </div>').fadeIn("slow").appendTo('#extender');
                
                $('<div class="control-group"> \n\
                        <span class="control-label span3"> Importe total:</span>\n\
                        <div class="controls form-inline">\n\
                            <div class="span3"></div>\n\
                            <div class="span3"></div>\n\
                            <div class="span3">\n\
                                <label for="importe">Total:</label>\n\
                                <input type="text" class="importe input-small" name="importe" id="importe" placeholder="Importe total">\n\
                            </div>\n\
                        </div>\n\
                   </div>').fadeIn("slow").appendTo('#extender');
                
                 
                 return false;
            }
            else{
                 $('<div class="control-group">\n\
                        <span class="control-label span3">Redacción de proyecto básico:</span>\n\
                        <div class="controls form-inline">\n\
                            <div class="span3">\n\
                                <label for="can' + i + '">Cantidad:</label>\n\
                                <input type="text" class="cantidad input-small" name="cantidad[]" id="can' + i + '" placeholder="Cantidad">\n\
                            </div>\n\
                            <div class="span3">\n\
                            <label for="desc' + i + '">Descuento:</label>\n\
                            <input type="text" class="descuento input-small" name="descuento[]" id="desc' + i + '" placeholder="Descuento">\n\
                            </div>\n\
                            <div class="span3">\n\
                                <label for="to' + i + '">Total:</label>\
                                <input type="text" class="total input-small" name="total[]" id="to' + i + '" placeholder="Total">\n\
                            </div>\n\
                        </div>\n\
                    </div>').fadeIn("slow").appendTo('#extender');

 
                 return false;
            }
        }
    });   
    
    
    
    
    
    
    var select = $('select[name=tipo]');
    var op = select.find('option:selected');
    var i = 0; 
    if(op.val() != 0){ 
        if(op.val() == 1 || op.val() == 3 || op.val() == 4){               
             $('<div class="control-group">\n\
                    <span class="control-label span3">Redacción de proyecto básico:</span>\n\
                    <div class="controls form-inline">\n\
                        <div class="span3">\n\
                            <label for="can' + i + '">Cantidad:</label>\n\
                            <input type="text" class="cantidad input-small" name="cantidad[]" id="can' + i + '" placeholder="Cantidad">\n\
                        </div>\n\
                        <div class="span3">\n\
                        <label for="desc' + i + '">Descuento:</label>\n\
                        <input type="text" class="descuento input-small" name="descuento[]" id="desc' + i + '" placeholder="Descuento">\n\
                        </div>\n\
                        <div class="span3">\n\
                            <label for="to' + i + '">Total:</label>\
                            <input type="text" class="total input-small" name="total[]" id="to' + i + '" placeholder="Total">\n\
                        </div>\n\
                    </div>\n\
                </div>').fadeIn("slow").appendTo('#extender');

            i++;

             $('<div class="control-group">\n\
                    <span class="control-label span3"> Redacción de p. de ejecución:</span>\n\
                    <div class="controls form-inline">\n\
                        <div class="span3">\n\
                            <label for="can' + i + '">Cantidad:</label>\n\
                            <input type="text" class="cantidad input-small" name="cantidad[]" id="can' + i + '" placeholder="Cantidad">\n\
                        </div>\n\
                        <div class="span3">\n\
                        <label for="desc' + i + '">Descuento:</label>\n\
                        <input type="text" class="descuento input-small" name="descuento[]" id="desc' + i + '" placeholder="Descuento">\n\
                        </div>\n\
                        <div class="span3">\n\
                            <label for="to' + i + '">Total:</label>\
                            <input type="text" class="total input-small" name="total[]" id="to' + i + '" placeholder="Total">\n\
                        </div>\n\
                    </div>\n\
                </div>').fadeIn("slow").appendTo('#extender');

            i++;

             $('<div class="control-group">\n\
                    <span class="control-label span3"> Dirección de obras:</span>\n\
                    <div class="controls form-inline">\n\
                        <div class="span3">\n\
                            <label for="can' + i + '">Cantidad:</label>\n\
                            <input type="text" class="cantidad input-small" name="cantidad[]" id="can' + i + '" placeholder="Cantidad">\n\
                        </div>\n\
                        <div class="span3">\n\
                        <label for="desc' + i + '">Descuento:</label>\n\
                        <input type="text" class="descuento input-small" name="descuento[]" id="desc' + i + '" placeholder="Descuento">\n\
                        </div>\n\
                        <div class="span3">\n\
                            <label for="to' + i + '">Total:</label>\
                            <input type="text" class="total input-small" name="total[]" id="to' + i + '" placeholder="Total">\n\
                        </div>\n\
                    </div>\n\
                </div>').fadeIn("slow").appendTo('#extender');
             i++;

             $('<div class="control-group">\n\
                    <span class="control-label span3"> Dirección de ejecución de obras:</span>\n\
                    <div class="controls form-inline">\n\
                        <div class="span3">\n\
                            <label for="can' + i + '">Cantidad:</label>\n\
                            <input type="text" class="cantidad input-small" name="cantidad[]" id="can' + i + '" placeholder="Cantidad">\n\
                        </div>\n\
                        <div class="span3">\n\
                        <label for="desc' + i + '">Descuento:</label>\n\
                        <input type="text" class="descuento input-small" name="descuento[]" id="desc' + i + '" placeholder="Descuento">\n\
                        </div>\n\
                        <div class="span3">\n\
                            <label for="to' + i + '">Total:</label>\
                            <input type="text" class="total input-small" name="total[]" id="to' + i + '" placeholder="Total">\n\
                        </div>\n\
                    </div>\n\
                </div>').fadeIn("slow").appendTo('#extender');
                 
                 i++;
                 
             $('<div class="control-group">\n\
                    <span class="control-label span3"> Coordinación de seguridad y salud:</span>\n\
                    <div class="controls form-inline">\n\
                        <div class="span3">\n\
                            <label for="can' + i + '">Cantidad:</label>\n\
                            <input type="text" class="cantidad input-small" name="cantidad[]" id="can' + i + '" placeholder="Cantidad">\n\
                        </div>\n\
                        <div class="span3">\n\
                        <label for="desc' + i + '">Descuento:</label>\n\
                        <input type="text" class="descuento input-small" name="descuento[]" id="desc' + i + '" placeholder="Descuento">\n\
                        </div>\n\
                        <div class="span3">\n\
                            <label for="to' + i + '">Total:</label>\
                            <input type="text" class="total input-small" name="total[]" id="to' + i + '" placeholder="Total">\n\
                        </div>\n\
                    </div>\n\
                </div>').fadeIn("slow").appendTo('#extender');
                 

             return false;
        }
        else{
             $('<div class="control-group">\n\
                        <span class="control-label span3">Redacción de proyecto básico:</span>\n\
                        <div class="controls form-inline">\n\
                            <div class="span3">\n\
                                <label for="can' + i + '">Cantidad:</label>\n\
                                <input type="text" class="cantidad input-small" name="cantidad[]" id="can' + i + '" placeholder="Cantidad">\n\
                            </div>\n\
                            <div class="span3">\n\
                            <label for="desc' + i + '">Descuento:</label>\n\
                            <input type="text" class="descuento input-small" name="descuento[]" id="desc' + i + '" placeholder="Descuento">\n\
                            </div>\n\
                            <div class="span3">\n\
                                <label for="to' + i + '">Total:</label>\
                                <input type="text" class="total input-small" name="total[]" id="to' + i + '" placeholder="Total">\n\
                            </div>\n\
                        </div>\n\
                    </div>').fadeIn("slow").appendTo('#extender');

             return false;
        }
    }
    
});


