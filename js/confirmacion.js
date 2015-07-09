$(document).ready(function() {
	$('a[data-confirm]').click(function() {
		var href = $(this).attr('href');
		if (!$('#dataConfirmModal').length) {
			$('body').append('<div id="dataConfirmModal" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">\n\
                                            <div class="modal-header">\n\
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>\n\
                                                <h4 id="dataConfirmLabel">Confirmación</h4>\n\
                                            </div>\n\
                                            <div class="modal-body"></div>\n\
                                            <div class="modal-footer">\n\
                                                <button class="btn" data-dismiss="modal" aria-hidden="true">\n\
                                                    Cancelar\n\
                                                </button>\n\
                                                <a class="btn btn-primary" id="dataConfirmOK">\n\
                                                    OK\n\
                                                </a>\n\
                                            </div>\n\
                                           </div>');
		} 
		$('#dataConfirmModal').find('.modal-body').text($(this).attr('data-confirm'));
		$('#dataConfirmOK').attr('href', href);
		$('#dataConfirmModal').modal({show:true});
		return false;
	});
        
        $('input[data-confirm]').click(function() { //alert();
               /*aux = false;
            
		if (!$('#dataConfirmModal').length) { 
			$('body').append('<div id="dataConfirmModal" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">\n\
                                            <div class="modal-header">\n\
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>\n\
                                                <h3 id="dataConfirmLabel">Confirmación</h3>\n\
                                            </div>\n\
                                            <div class="modal-body"></div>\n\
                                            <div class="modal-footer">\n\
                                                <button class="btn" data-dismiss="modal" aria-hidden="true">\n\
                                                    Cancelar\n\
                                                </button>\n\
                                                <a class="btn btn-primary" id="dataConfirmOK"  href="empleados/borrar" data-id="empleados/borrar">\n\
                                                    OK\n\
                                                </a>\n\
                                            </div>\n\
                                           </div>');
		} 
                
                
		$('#dataConfirmModal').find('.modal-body').text($(this).attr('data-confirm'));
                
                $('#dataConfirmModal').on('show', function() { 
                    var $submit = $(this).find('.btn-primary'),
                        href = $submit.attr('href');
                        alert(href);
                        $submit.attr('href', href);
                });
               // $('#dataConfirmOK').click(function(e) {
                 //     e.preventDefault();
                     //alert($submit);
                   //   $('#dataConfirmOK').data('id', $(this).data('id')).modal('show');
                //});*/
            
           $('#dataConfirmModal').dialog({
            autoOpen: false,
            width: 400,
            modal: true,
            resizable: false,
            buttons: {
                "Submit Form": function() {
                    document.testconfirmJQ.submit();
                },
                "Cancel": function() {
                    $(this).dialog("close");
                }
            }
        });
         
        $('#formBorrar').submit(function(e){
            e.preventDefault();
 
            $("p#dialog-email").html($("input#emailJQ").val());
            $('#dataConfirmModal').dialog('open');
        });
            
                $('#dataConfirmModal').modal({show:true});
                return false;
            
       
          
             var c = confirm("¿Desea continuar?");
    return c;
            
            
    });
});

