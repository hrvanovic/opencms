function getTemplatePart(div, file) {  
    var divIn = document.getElementsByClassName(div);
    $(divIn).load(file, function(response, status, xhr) {
        if(status == "error") {
            window.location.replace("/opencms/Error/error_505");
        } 
    });
};