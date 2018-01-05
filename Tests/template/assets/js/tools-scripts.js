(function($) {

    $('#formservices').submit(function(event) {
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
    var formid = $('#formId').val();

    $('#' + formid + '').submit(function(event) {
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
                    reloadCaptcha();
                }
                else {

                    $(".alert-danger").css('display', 'none');
                    $(".alert-success").removeClass('hide');
                    $(".alert-success").css('display', 'block');
                    $(".form-control").val("");
                    $(".alert-success").html(" <p>" + code.message + "</p>" + " <p><a href=\"etat/print." + code.ins + ".php?val=" + code.val + "&ser=" + code.serv + "\"><i class=\"fa fa-check\"></i>" + langFront['label'][9] + "</a></p>");

                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(jqXHR.status);
                alert(errorThrown);
                alert(textStatus);
            }

        });
    });

//    $('.mod-pays-del').on('click', function() {
//        var id = $(this).val();
//        if (id !== -1) {
//            if (confirm(langFront['label'][1])) {
//                $.ajax({
//                    type: 'POST',
//                    url: 'interfaces/interface.etablissement.php',
//                    data: 'id=' + id + '&pays=del',
//                    dataType: 'json',
//                    cache: false,
//                    success: function(code, textStatus) {
//                        if (code.type == -1) {
//                            $(".alert-success").css('display', 'none');
//                            $(".alert-danger").removeClass('hide');
//                            $(".alert-danger").css('display', 'block');
//                            $(".alert-danger").html(" <p>" + code.message + "</p>");
//                        }
//                        else {
//
//                            $(".alert-danger").css('display', 'none');
//                            $(".alert-success").removeClass('hide');
//                            $(".alert-success").css('display', 'block');
//                            $(".alert-success").html(" <p>" + code.message + "</p>");
//                            window.location.replace('pays.php');
//                        }
//
//                    },
//                    error: function(jqXHR, textStatus, errorThrown) {
//                        alert(jqXHR.status);
//                        alert(errorThrown);
//                        alert(textStatus);
//                    }
//
//                });
//                return false;
//            }
//        }
//
//    });
    $('#formetabli').submit(function(event) {
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


    $('#formuniver').submit(function(event) {
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

    $('.mod-etab').on('click', function() {
        var id = $(this).val();
        if (id !== -1) {
            if (confirm(langFront['label'][1])) {
                $.ajax({
                    type: 'POST',
                    url: 'interfaces/interface.etablissement.php',
                    data: 'id=' + id + '&auniv=del',
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
                            window.location.replace('universite.php');
                        }

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert(jqXHR.status);
                        alert(errorThrown);
                        alert(textStatus);
                    }

                });
                return false;
            }
        }

    });


    $('.mod-etab-mod').on('click', function() {
        var id = $(this).val();
        if (id !== -1) {
            if (confirm(langFront['label'][1])) {
                $.ajax({
                    type: 'POST',
                    url: 'interfaces/interface.etablissement.php',
                    data: 'id=' + id + '&aetab=del',
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
                            window.location.replace('etablissement.php');
                        }

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert(jqXHR.status);
                        alert(errorThrown);
                        alert(textStatus);
                    }

                });
                return false;
            }
        }

    });


    $('.mod-fil-del').on('click', function() {
        var id = $(this).val();
        if (id !== -1) {
            if (confirm(langFront['label'][1])) {
                $.ajax({
                    type: 'POST',
                    url: 'interfaces/interface.etablissement.php',
                    data: 'id=' + id + '&fil=del',
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
                            window.location.replace('filiere.php');
                        }

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert(jqXHR.status);
                        alert(errorThrown);
                        alert(textStatus);
                    }

                });
                return false;
            }
        }

    });

    $('.mod-niv-del').on('click', function() {
        var id = $(this).val();
        if (id !== -1) {
            if (confirm(langFront['label'][1])) {
                $.ajax({
                    type: 'POST',
                    url: 'interfaces/interface.etablissement.php',
                    data: 'id=' + id + '&niv=del',
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
                            window.location.replace('niveau.php');
                        }

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert(jqXHR.status);
                        alert(errorThrown);
                        alert(textStatus);
                    }

                });
                return false;
            }
        }

    });



    $('#chargerinstance').on('click', function(event) {
        event.preventDefault();
        var elem = $("input[type=radio]:checked").val();
        var id = $("#choixservice").val();
        $('#candidat').empty();
        $.ajax({
            type: 'POST',
            url: 'interfaces/interface.operation.php',
            data: 'service=' + id + '&optionsChamp=' + elem + '&fich=1',
            dataType: 'json',
            cache: false,
            success: function(code, textStatus) {
                for (var i in code.candidat) {

                    $('#candidat').append('<option value="' + code.candidat[i].index + '">' + code.candidat[i].value + '</option>');
                }
                if (code.type == -1) {
                    $('#candidat').append('<option value="-1">' + code.message + '</option>');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(jqXHR.status);
                alert(errorThrown);
                alert(textStatus);
            }

        });

    });


    $('#formoperation').submit(function(event) {
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

    $('#submitbuttonoperationlot').on('click', function(event) {
        event.preventDefault();
        if ($('#fichierspecification').val() !== "") {
            $('#formoperationlot').submit();
        }
        else {
            alert(langFront['label'][2]);
        }

    });

    $('#choixservicelistop').on('change', function() {
        var id = $(this).val();
        if (id !== -1) {
            $('.operation-list').load('ajax/ajax.list.operation.php?service=' + id);
        }

    });



    $('.valideoperation').on('click', function() {
        var id = $(this).val();
        if (id !== -1) {
            if (confirm(langFront['label'][14])) {
                $.ajax({
                    type: 'POST',
                    url: 'interfaces/interface.operation.php',
                    data: 'id=' + id + '&a=val',
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
                            window.location.replace('operation.php');
                        }

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert(jqXHR.status);
                        alert(errorThrown);
                        alert(textStatus);
                    }

                });
                return false;
            }
        }

    });

    $('.invalideoperation').on('click', function() {
        var id = $(this).val();
        if (id !== -1) {
            if (confirm(langFront['label'][13])) {
                $.ajax({
                    type: 'POST',
                    url: 'interfaces/interface.operation.php',
                    data: 'id=' + id + '&a=inval',
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
                            window.location.replace('operation.php');
                        }

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert(jqXHR.status);
                        alert(errorThrown);
                        alert(textStatus);
                    }

                });
                return false;
            }
        }

    });



    $('#formconnexion').submit(function(event) {
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
                    window.location.replace('espace.register.php?instance=' + code.instance);
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(jqXHR.status);
                alert(errorThrown);
                alert(textStatus);
            }

        });
    });


    $('#add-user').on('click', function() {
        $("#ajout-plat-user").removeClass('hide');
        $("#ajout-plat-user").css('display', 'block');

        $("#liste_user").addClass('hide');
        $("#liste_user").css('display', 'none');
    });

    $('#add_group').on('click', function() {
        $("#ajout-plat-groupe").removeClass('hide');
        $("#ajout-plat-groupe").css('display', 'block');

        $("#liste_groupe").addClass('hide');
        $("#liste_groupe").css('display', 'none');
    });

    $('#add_privilege').on('click', function() {
        $("#ajout-plat-privilege").removeClass('hide');
        $("#ajout-plat-privilege").css('display', 'block');

        $("#liste_privilege").addClass('hide');
        $("#liste_privilege").css('display', 'none');
    });

    $('#add_prgrp').on('click', function() {
        $("#ajout-plat-group-privilege").removeClass('hide');
        $("#ajout-plat-group-privilege").css('display', 'block');

        $("#list_group_privilege").addClass('hide');
        $("#list_group_privilege").css('display', 'none');
    });

    $('.reloadlist_user').on('click', function() {

        $("#ajout-plat-user").addClass('hide');
        $("#ajout-plat-user").css('display', 'none');
        $("#mod-user").addClass('hide');
        $("#mod-user").css('display', 'none');

        $("#liste_user").removeClass('hide');
        $("#liste_user").css('display', 'block');
    });

    $('.reloadlist_group').on('click', function() {

        $("#ajout-plat-groupe").addClass('hide');
        $("#ajout-plat-groupe").css('display', 'none');

        $("#mod-groupe").addClass('hide');
        $("#mod-groupe").css('display', 'none');

        $("#liste_groupe").removeClass('hide');
        $("#liste_groupe").css('display', 'block');
    });

    $('.reloadlist_privilege').on('click', function() {

        $("#ajout-plat-privilege").addClass('hide');
        $("#ajout-plat-privilege").css('display', 'none');

        $("#mod-privilege").addClass('hide');
        $("#mod-privilege").css('display', 'none');

        $("#liste_privilege").removeClass('hide');
        $("#liste_privilege").css('display', 'block');
    });

    $('.reloadlist_groupprivil').on('click', function() {

        $("#ajout-plat-group-privilege").addClass('hide');
        $("#ajout-plat-group-privilege").css('display', 'none');

        $("#list_group_privilege").removeClass('hide');
        $("#list_group_privilege").css('display', 'block');
    });


    $('.formuser_add').submit(function(event) {
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
                    window.location.replace('utilisateur.php');
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(jqXHR.status);
                alert(errorThrown);
                alert(textStatus);
            }

        });
    });



    $('.form-signin').submit(function(event) {
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
                    //$(".alert-success").fadeOut(10000);
                    window.location.replace("etablissement.php");
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(jqXHR.status);
                alert(errorThrown);
                alert(textStatus);
            }

        });
    });

    function reloadCaptcha()
    {
        jQuery('#siimage').prop('src', 'ressources/library/securimage/securimage_show.php?sid=' + Math.random());
    }

    $('.stat-univer').on('click', function() {
        var iduniver = $("#iduniversite-stat");
        ///var tab = 
        alert("test val");
        alert(iduniver);
    });


    $('.upgrade_user_sup').on('click', function() {
        var id = $(this).val();
        if (id !== -1) {
            if (confirm(langFront['label'][1])) {
                $.ajax({
                    type: 'POST',
                    url: 'interfaces/interface.user.php',
                    data: 'id=' + id + '&a=deluser',
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
                            window.location.replace('utilisateur.php');
                        }

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert(jqXHR.status);
                        alert(errorThrown);
                        alert(textStatus);
                    }

                });
                return false;
            }
        }

    });

    $('.upgrade_group_sup').on('click', function() {
        var id = $(this).val();
        if (id !== -1) {
            if (confirm(langFront['label'][1])) {
                $.ajax({
                    type: 'POST',
                    url: 'interfaces/interface.user.php',
                    data: 'id=' + id + '&a=delgroupe',
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
                            window.location.replace('utilisateur.php');
                        }

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert(jqXHR.status);
                        alert(errorThrown);
                        alert(textStatus);
                    }

                });
                return false;
            }
        }

    });

    $('.upgrade_priv_sup').on('click', function() {
        var id = $(this).val();
        if (id !== -1) {
            if (confirm(langFront['label'][1])) {
                $.ajax({
                    type: 'POST',
                    url: 'interfaces/interface.user.php',
                    data: 'id=' + id + '&a=delpriv',
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
                            window.location.replace('utilisateur.php');
                        }

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert(jqXHR.status);
                        alert(errorThrown);
                        alert(textStatus);
                    }

                });
                return false;
            }
        }

    });

    $('.upgrade_priv_grp_sup').on('click', function() {
        var id = $(this).val();
        if (id !== -1) {
            if (confirm(langFront['label'][1])) {
                $.ajax({
                    type: 'POST',
                    url: 'interfaces/interface.user.php',
                    data: 'id=' + id + '&a=delgrpp',
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
                            window.location.replace('utilisateur.php');
                        }

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert(jqXHR.status);
                        alert(errorThrown);
                        alert(textStatus);
                    }

                });
                return false;
            }
        }

    });

    $("#check_all").on('click', function() {

        if (this.checked) { // check select status
            $('.checkboxelement').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"               
            });
        } else {
            $('.checkboxelement').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                       
            });
        }
    });

}(jQuery));


