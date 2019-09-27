$(document).ready(function() {

    //назначение ролей
    $(".edit-access").click(function () {
        var popup = ShowPopUp();
        var id = $(this).attr('idd');
        var object = $(this).attr('its');
        console.log(id);
        popup.load('/'+object+'/ajaxAccess/', {id : id});

    });


    /************************************************************************************************************/

//авточекирование ролей
    function set_check_flag(id_user, mask) {
        var box = document.getElementById("way[" + id_user + "][" + mask + "]");
        if(box.checked == true) {
            for (var i = 0; i < mask; i++) {
                var flag = document.getElementById("way[" + id_user + "][" + i + "]");
                if (flag) {
                    flag.checked=true;
                    //$('#' + id_user).css("background-color", "#D1D3D3");
                }
            }
        } else {
            for (var i = mask; i < 11112; i++) {
                var flag = document.getElementById("way[" + id_user + "][" + i + "]");
                if (flag) {
                    flag.checked=false;
                }
            }
        }
    }

    $(document).on('click', '.flag', function () {
        var id_user = $(this).attr('user');
        var mask = $(this).attr('mask');
        set_check_flag(id_user, mask);
    });

    /************************************************************************************************************/

//назначение роли юзеру

    function new_user(count) {
        var idUser = document.getElementById("name").value;
        var nameUser = $("#name option:selected").text();
        var max_role = $("#name option:selected").data("max-role");
        var a = 0;
        //var b = '0000';
        var elem = document.getElementById(idUser);
        //console.log(elem);
        if (nameUser!='') {
            if (!elem) {
                var tr = $('<tr id=' + idUser + '>');
                $('<td style="min-width: 150px;">' + nameUser + '</td>').appendTo(tr);
                while (a < count) {
                    if (a==0) {
                        b='1'
                    }
                    if (a==1) {
                        b='11'
                    }
                    if (a==2) {
                        b='111'
                    }
                    if (a==3) {
                        b='11111'
                    }
                    if (max_role >= b) {
                        $('<td align = \"center\"><input type=\"checkbox\" class=\"flag\" mask=\"' + b + '\" user=\"'+ idUser +'\" access=\"'+ b +'\" id=\"way[' + idUser + '][' + b + ']\" name=\"way[' + idUser + '][' + b + ']\"/></td>').appendTo(tr);
                    } else {
                        $('<td align = \"center\"><input type=\"checkbox\" class=\"flag\" mask=\"' + b + '\" user=\"'+ idUser +'\" access=\"'+ b +'\" id=\"way[' + idUser + '][' + b + ']\" name=\"way[' + idUser + '][' + b + ']\" disabled /></td>').appendTo(tr);
                    }
                    a++;
                }
                $('<td><span class=\"pointer1 click-button\" data-nameUser=' + idUser + '>удалить</span></td>').appendTo(tr);
                $('#name_user').append(tr);
                $('#err_user').fadeOut('fast');
            }
            else {
                $("#err_user").fadeIn('fast');
            }
        }
    }

    $(document).on('click', '.pointer_add_new', function () {
        var count = $(this).attr('data-count');
        new_user(count);
    });

    /************************************************************************************************************/

//удаление пользовтеля в назначении прав
    var del = [];
    function del_user(nameUserdel) {
        $('select#name option[value="' + nameUserdel + '"] ').removeAttr('hidden');
        $('#' + nameUserdel).css("background-color", "#D1D3D3");
        $('#' + nameUserdel).fadeOut('slow', function () {
            $('#' + nameUserdel).remove();
        });
        var pushed = del.push(nameUserdel);
        var json = JSON.stringify(del);
        /*$.ajax({
         url:'/include/ajax/delete_user_access.php',
         type:'POST',
         data:'jsonData=' + json,
         success: function() {
         alert(res);
         }
         });*/
        console.log(json);
    }

    $(document).on('click', '.pointer1', function () {
        var nameUserdel = $(this).attr('data-nameUser');
        del_user(nameUserdel);
    });
    /************************************************************************************************************/

    $(document).on('submit', '#add_access_window', function () {
        var arrayVal = $('form#add_access_window').serializeArray();
        var object = 'database';
        arrayVal.forEach(function(item, i, arrayVal){
            var access = $('input[name="'+arrayVal[i].name+'"]').attr('access');
            var user = $('input[name="'+arrayVal[i].name+'"]').attr('user');
            //var nameUs = $('input[name="'+arrayVal[i].name+'"]').attr('nameUser');
            arrayVal[i].access = access;
            arrayVal[i].user = user;
            //arrayName[i].nameUser = nameUs;
        });
        arrayValJSON = JSON.stringify(arrayVal);
        delUser = JSON.stringify(del);
        console.table(arrayVal);
        $.ajax({
            url:'/'+ object +'/ajaxSetAccess/',
            type:'POST',
            dataType: "html",
            data:'jsonData='+arrayValJSON+"&delUser="+delUser,
            success: function(data) {
                console.log(data);
                if (data == '') {
                    alert('Права успешно настроены');
                    $('.b-popup-content').removeClass().empty();
                    $('.b-popup').hide().children('div').addClass('b-popup-content');
                    var del = new Array;
                }
                else {
                    $('#addrightmsg').html(data);
                }
            }
        });
        return false;
    });
});



