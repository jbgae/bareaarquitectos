function seleccionar_checkbox(check){ 
    for (i=0;i<document.f1.elements.length;i++){
        if(document.f1.elements[i].type == "checkbox"){
            document.f1.elements[i].checked = check;            
        }
    }  
} 



