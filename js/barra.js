$(document).ready(function(){
    $('.js-loading-bar').hide();    

    $('#restaurar').click(function() {   
          
          var $modal = $('.js-loading-bar');
          $modal.modal('show');          

           $.ajax({
                type: "POST",
                url: "http://localhost/bareaarquitectos/backup/restaurar",
                data: $("#formdata").serialize(),
                datatype: "text",
                
                success:function(res){
                    $modal.modal('hide');    
                    $("#mensaje").html(res);                        
                }
          });    

          return false;
    });

});

