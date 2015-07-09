$(document).ready(function() {  
    if ($('#Error').length){
        setTimeout(function () {
            history.back(1);
        }, 5000);
    }
});