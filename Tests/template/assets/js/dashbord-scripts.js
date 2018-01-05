/* global langFront */

(function ($) {
    
    $('.btnservice').on('click',function() {
       var id = $(this).val() ;
       if(id!==-1){
           $.ajax({
                type: 'POST',
                url:  'interfaces/interface.dashbord.php',
                data: 'idservice='+id+'&choixservice=ok',
                dataType: 'json',
                cache: false,
                success: function (code, textStatus) {
                    //Montant  total
                    $("#montantTotal").html(code.montant[0].montant+' '+langFront['label'][0]);
                    
                    var stat ='';
                    //Montant par tranche
                    for (var i in code.montantTranche) {
                            stat += code.montantTranche[i].montant+' => '+code.montantTranche[i].somme+' '+langFront['label'][0]+'<br />';
                            //newhtml +=code.montant[i].montant;
                            //output+="<li>" + code.montant[i].montant + " " + data.users[i].lastName + "--" + data.users[i].joined.month+"</li>";
                        }
                     $("#montantTotalTranche").html(stat);
                     
                    stat ='';
                    //Montant par tranche
                    for (var i in code.nombreTotalTranche) {
                            stat += code.nombreTotalTranche[i].label+' => '+code.nombreTotalTranche[i].value+'<br />';
                            
                        }
                     $("#nombreTotalTranche").html(stat);
                     
            
                    //Total inscrit
                    $("#nombreTotalInscrit").html(code.nombreTotalInscrit[0].total);
                    
                    $('#pay-non-pay-donut').load('ajax/ajax.donut.effectif.php?idservice='+id);
                    
                    $('#filiere-chart').load('ajax/ajax.line.filiere.php?idservice='+id);
                    
                    $('#niveau-chart').load('ajax/ajax.line.niveau.php?idservice='+id);
                    
                    $('#day-chart').load('ajax/ajax.line.day.payement.php?idservice='+id);
                    
                    $('#table-filiere').load('ajax/ajax.tab.filiere.php?idservice='+id);
                    
                    $('#table-filiere-niveau').load('ajax/ajax.tab.filiere.niveau.php?idservice='+id);
                    
                    $('#table-payement-mois').load('ajax/ajax.tab.payement.mois.php?idservice='+id);
                    
                    $('#table-payement-annee').load('ajax/ajax.tab.payement.annee.php?idservice='+id);
                },
                error:  function (jqXHR, textStatus, errorThrown) {
                    alert(jqXHR.status);
                    alert(errorThrown);
                    alert(textStatus);
                }

            });
       }
       
    });
    
    
}(jQuery));


