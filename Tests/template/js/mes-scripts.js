/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


(function($) {

    $('#formnewmerchant').submit(function(event) {
        event.preventDefault();
        dataString = $(this).serialize();
        $(".alert-success").css('display', 'none');
        $(".alert-danger").css('display', 'none');
        $.ajax({
            type: 'POST',
            url: $(this).attr("action"),
            data: dataString,
            dataType: 'json',
            cache: false,
            success: function(code, textStatus) {
                if (code.type == -1) {
                    $(".alert-success").css('display', 'none');
                    $(".alert-danger").removeClass('hide');
                    $(".alert-danger").css('display', 'block');
                    $(".alert-danger").html(" <p>" + code.message + "</p>");

                }
                else {

                    $(".alert-danger").css('display', 'none');
                    $(".alert-success").removeClass('hide');
                    $(".alert-success").css('display', 'block');
                    $(".alert-success").html(" <p>" + code.message + "</p>");
                    $(".form-control").val("");
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(jqXHR.status);
                alert(errorThrown);
                alert(textStatus);
            }

        });
    });
    
    $('#formconnexion').submit(function (event){
        event.preventDefault();
        dataString = $(this).serialize();
        $(".alert-success").css('display','none');
        $(".alert-danger").css('display','none');
        $.ajax({
            type: 'POST',
            url:  $(this).attr("action"),
            data: dataString,
            dataType: 'json',
            cache: false,
            success: function (code, textStatus) {
                if(code.type ==-1){
                    $(".alert-success").css('display','none');
                    $(".alert-danger").removeClass('hide');
                    $(".alert-danger").css('display','block');
                    $(".alert-danger").html(" <p>"+code.message+"</p>");
                }
                else{
                    
                    $(".alert-danger").css('display','none');
                    $(".alert-success").removeClass('hide');
                    $(".alert-success").css('display','block');
                    $(".alert-success").html(" <p>"+code.message+"</p>");
                    //$(".alert-success").fadeOut(10000);
                    window.location.replace("merchants.php"); 
                }
                    
            },
            error:  function (jqXHR, textStatus, errorThrown) {
                alert(jqXHR.status);
                alert(errorThrown);
                alert(textStatus);
            }
            
        });
    });
    
}(jQuery));