$(document).ready(function(){
   
    // Reiniciar el tamaño de la fuente
    //var tamOriginal = $('html').css('font-size');
    //$(".reiFuente").click(function(){
    //    $('html').css('font-size', tamOriginal);
    //});
    // 
    // Incrementar el tamaño de la fuente
    $(".aumentar").click(function(){
        var tamActual = $('ul>li>a').css('font-size');
        var tamActualNum = parseFloat(tamActual, 10);
        var nuevaFuente = tamActualNum+0.5;
        $('ul>li>a').css('font-size', nuevaFuente);
        
        var tamActual = $('.text').css('font-size');
        var tamActualNum = parseFloat(tamActual, 10);
        var nuevaFuente = tamActualNum+0.5;
        $('.text').css('font-size', nuevaFuente);
        
        return false;
    });
    
    // Disminuir el tamaño de la fuente
    $(".disminuir").click(function(){
        var tamActual = $('ul>li>a').css('font-size');        
        var tamActualNum = parseFloat(tamActual, 10);
        var nuevaFuente = tamActualNum-0.5;
        $('ul>li>a').css('font-size', nuevaFuente);
        
        var tamActual = $('.text').css('font-size');
        var tamActualNum = parseFloat(tamActual, 10);
        var nuevaFuente = tamActualNum-0.5;
        $('.text').css('font-size', nuevaFuente);
        
        return false;
    });
});