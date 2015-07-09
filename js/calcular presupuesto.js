$(document).ready(function() {
    
     $('.pem').change(function(){
        var pem = $('.pem').val();
        $('.coeficiente').val(0);
        $('.coeficienteSeguridad').val(0);
        $('.cantidad').val(pem);
        $('.cantidad#can4').val('');
        $('.descuento').val('');
        $('.total').val('');
                
        $('.coeficiente').change(function(){
            var coef = $('.coeficiente').val();
            
            var honorarios = (pem*coef)/100;
            var basico = (honorarios*40)/100;
            var ejecucion = (honorarios*30)/100;
            var obra = (honorarios*30)/100;
            var direccion = (honorarios*30)/100;
            
            $('.descuento').val('');
            $('.cantidad#can0').val(basico);
            $('.total#to0').val(basico);
            $('.cantidad#can1').val(ejecucion);
            $('.total#to1').val(ejecucion);
            $('.cantidad#can2').val(obra);
            $('.total#to2').val(obra);
            $('.cantidad#can3').val(direccion);
            $('.total#to3').val(direccion);
            
            var t0 = $('.total#to0').val();
            var t1 = $('.total#to1').val();
            var t2 = $('.total#to2').val();
            var t3 = $('.total#to3').val();            
            
            var imp = parseFloat(t0) + parseFloat(t1) + parseFloat(t2) + parseFloat(t3);
            $('.importe').val(imp);
            
            
            $('.coeficienteSeguridad').change(function(){
                 var coefseg = $('.coeficienteSeguridad').val();
                 var seguridad = (((honorarios*coefseg)/100)*70)/100;  
                 
                 $('.descuento#desc4').val(''); 
                 $('.cantidad#can4').val(seguridad); 
                 $('.total#to4').val(seguridad); 
                 var aux =  $('.importe').val();
                 imp = parseFloat(aux) + parseFloat($('.total#to4').val()); 
                 $('.importe').val(imp);                  
            });
            
            $('.descuento#desc0').change(function(){
                      var desc0 = $('.descuento#desc0').val();
                      var c0 = $('.cantidad#can0').val();
                      var t0 = c0 -((c0*desc0)/100); 
                      $('.total#to0').val(t0); 
                      
                      t0 = $('.total#to0').val();
                      var t1 = $('.total#to1').val();
                      var t2 = $('.total#to2').val();
                      var t3 = $('.total#to3').val();            
                      var t4 = $('.total#to4').val();
                      if(t4 != '')
                        var imp = parseFloat(t0) + parseFloat(t1) + parseFloat(t2) + parseFloat(t3) + parseFloat(t4);
                      else
                        var imp = parseFloat(t0) + parseFloat(t1) + parseFloat(t2) + parseFloat(t3);
                    
                      $('.importe').val(imp);
            });
            
            $('.descuento#desc1').change(function(){
                      var desc1 = $('.descuento#desc1').val();
                      var c1 = $('.cantidad#can1').val();
                      var t1 = c1 -((c1*desc1)/100); 
                      $('.total#to1').val(t1);
                      
                      var t0 = $('.total#to0').val();
                      t1 = $('.total#to1').val();
                      var t2 = $('.total#to2').val();
                      var t3 = $('.total#to3').val();            
                      var t4 = $('.total#to4').val();            
                     if(t4 != '')
                        var imp = parseFloat(t0) + parseFloat(t1) + parseFloat(t2) + parseFloat(t3) + parseFloat(t4);
                      else
                        var imp = parseFloat(t0) + parseFloat(t1) + parseFloat(t2) + parseFloat(t3);
                      $('.importe').val(imp);
            });
            
            $('.descuento#desc2').change(function(){
                      var desc2 = $('.descuento#desc2').val();
                      var c2 = $('.cantidad#can2').val();
                      var t2 = c2 -((c2*desc2)/100); 
                      $('.total#to2').val(t2); 
                      
                      var t0 = $('.total#to0').val();
                      var t1 = $('.total#to1').val();
                      t2 = $('.total#to2').val();
                      var t3 = $('.total#to3').val();            
                      var t4 = $('.total#to4').val();            
                      if(t4 != '')
                        var imp = parseFloat(t0) + parseFloat(t1) + parseFloat(t2) + parseFloat(t3) + parseFloat(t4);
                      else
                        var imp = parseFloat(t0) + parseFloat(t1) + parseFloat(t2) + parseFloat(t3);
                      $('.importe').val(imp);
            });
            
            
            $('.descuento#desc3').change(function(){
                      var desc3 = $('.descuento#desc3').val();
                      var c3 = $('.cantidad#can3').val();
                      var t3 = c3 -((c3*desc3)/100); 
                      $('.total#to3').val(t3);                     
                      
                      var t0 = $('.total#to0').val();
                      var t1 = $('.total#to1').val();
                      var t2 = $('.total#to2').val();
                      t3 = $('.total#to3').val();            
                      var t4 = $('.total#to4').val();            
                      if(t4 != '')
                        var imp = parseFloat(t0) + parseFloat(t1) + parseFloat(t2) + parseFloat(t3) + parseFloat(t4);
                      else
                        var imp = parseFloat(t0) + parseFloat(t1) + parseFloat(t2) + parseFloat(t3);
                      $('.importe').val(imp);
            });
            
            $('.descuento#desc4').change(function(){
                      var desc4 = $('.descuento#desc4').val();
                      var c4 = $('.cantidad#can4').val();
                      var t4 = c4 -((c4*desc4)/100); 
                      $('.total#to4').val(t4);                     
                      
                      t0 = $('.total#to0').val();
                      var t1 = $('.total#to1').val();
                      var t2 = $('.total#to2').val();
                      var t3 = $('.total#to3').val();            
                      t4 = $('.total#to3').val();            
                      var imp = parseFloat(t0) + parseFloat(t1) + parseFloat(t2) + parseFloat(t3) + parseFloat(t4);
                      $('.importe').val(imp);
            });

            
        });
    });
});