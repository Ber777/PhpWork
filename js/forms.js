// функция создания popup-a
function ShowPopUp(class_popup, class_popup_content) {
    var popup = $('.b-popup');
    if (class_popup != undefined) {
        popup.addClass(class_popup);
    }
    popup.show();
    var popup_content = popup.children('.b-popup-content');
    if (class_popup_content != undefined) {
        popup_content.addClass(class_popup_content);
    }
    return popup_content;
}

$(document).ready(function () {

    /************************************************************************************************************/

    // форма добавления каталога из списка шаблонов
    $('#link-insert-catalog-from-template').click(function () {
        var id_parent = $(this).attr('idparent');
        var popup = ShowPopUp();
        popup.load(
            '/catalog/ajaxTemplates/',
            {
                id_parent: id_parent
            }
        );
    });

    // добавление каталога из списка шаблонов
    $(document).on('submit', '#form_add_catalog_from_template', function () {
        var name_catalog = $('#input-name-catalog').val();
        var template = $('#select-templ').val();
        var id_parent = $('#id_parent_hidden').val();

        if (name_catalog == '') {
            $('#input-name-catalog').css('border-color', 'red');
            return false;
        } else {
            $('#input-name-catalog').css('border-color', 'green');
        }

        if (template == 0) {
            $('#select-templ').css('border-color', 'red');
            return false;
        } else {
            $('#select-templ').css('border-color', 'green');
        }

        $.ajax({
            method: 'POST',
            url: '/catalog/ajaxAddCatalogFromTemplate/' + id_parent,
            data: $(this).serialize(),
            success: function (data) {
                console.log(data);
                if (data == 1) {
                    alert('Каталог успешно создан');
                    location.reload();
                }
                else {
                    $('#form_add_catalog_from_template p.block-error').empty();
                    $('#form_add_catalog_from_template p.block-error').append(data).slideDown();
                }
            }
        });
        return false;
    });

    /************************************************************************************************************/

    //удаление чего-либо через админ-панель
    $('.delete-something').click(function () {
        if (confirm('Вы точно хотите удалить?')) {
            var its = $(this).attr('its');
            var id = $(this).attr('idd');
            var arrayAjaxUrl = {
                'document': '/document/ajaxDeleteDocument/' + id,
                'catalog': '/catalog/ajaxDeleteCatalog/' + id,
                'database' : '/database/ajaxDeleteDatabase/' + id,
                'mapknowledge' : '/mapknowledge/ajaxDeleteMapknowledge/' + id,
                'message' : '/mapknowledge/ajaxDeleteMessage/' + id,
                'template_catalog' : '/template/ajaxDeleteTemplateCatalog/' + id,
                'template_document' : '/template/ajaxDeleteTemplateDocument/' + id,
                'template_mapknowledge' : '/template/ajaxDeleteTemplateMapknowledge/' + id,
                'template_search' : '/template/ajaxDeleteTemplateSearch/' + id,
                'template_alert' : '/template/ajaxDeleteTemplateAlert/' + id,
                'tag' : '/tag/ajaxDeleteTag/' + id,
                'attribute' : '/attribute/ajaxDeleteAttribute/' + id 
            };
            //console.log(arrayAjaxUrl);
            $.ajax({
                type: "POST",
                url: arrayAjaxUrl[its],
                success: function (json_data) {
                    var data = JSON.parse(json_data);
                    console.log(data);
                    console.log(data.result_delete);
                    if (data.result_delete) {
                        alert('Удаление прошло успешно');
                        if (data.reload_page == true) {
                            location.reload();
                        } else {
                            $(location).attr('href', data.link_back);
                        }
                    } else {
                        alert('Произошла ошибка при удалении');
                    }
                }
            });
        }
    });

    /************************************************************************************************************/
    //добавление шабона из рубрики
    $('#create-template-from-catalog').click(function () {
        var popup = ShowPopUp();
        //popup.append($('#insert-template-from-catalog'));
        $('#insert-template-from-catalog')
            .clone()
            .appendTo(popup)
            .show();
    });

    $(document).on('submit', '#form-add-template-from-template', function () {
        var id_catalog = $('#id_catalog').val();
        var name = $('#name_template').val();
        if (name != '') {
            $.ajax({
                type: "POST",
                url: "/catalog/ajaxCloneCatalogToTemplate/",
                data: "id=" + id_catalog + "&name=" + name,
                success: (function (data) {
                    if (data > 0) {
                        $('#insert-template-from-catalog > p.msg').html('Шаблон успешно создан').css('background-color', 'rgb(17, 221, 17)').slideDown();
                    }
                    else if (data == -1) {
                        $('#insert-template-from-catalog > p.msg').html('Шаблон с таким именем уже есть').css('background-color', 'rgb(208, 105, 105);').slideDown();
                    }
                    else {
                        $('#insert-template-from-catalog > p.msg').html('Ошибка!' + data).css('background-color', 'rgb(208, 105, 105);').slideDown();
                    }
                })
            })
        }
        else $('#name_template').css('border', '1px solid red');
        return false;
    });


    /************************************************************************************************************/
    //копирование документа или рубрики
    $('.copy-to').click(function(){
        var object = $(this).attr('object');
        var id = $(this).attr('idd');
        var array_id = new Array; // массив всех ID
        var json_array_id = ''; // json для передачи в кук
        array_id['document'] = new Array;
        array_id['catalog'] = new Array;
        if ($.cookie('list_copy') == null) {
            array_id[object].push(id);
            json_array_id = JSON.stringify({'document' : array_id['document'], 'catalog' : array_id['catalog']});
            $.cookie('list_copy', null);
            $.cookie('list_copy', json_array_id, { path: '/'});
            alert('Буфер активирован');
            location.reload();
        } else {
            json_array_id = $.cookie('list_copy');
            array_id = JSON.parse(json_array_id);
            if ((($.inArray(id, array_id['document'])) >= 0) || (($.inArray(id, array_id['catalog'])) >= 0)) {
                alert('Данный файл (рубрика) уже находится в буфере');
            } else {
                array_id[object].push(id);
                json_array_id = JSON.stringify({'document' : array_id['document'], 'catalog' : array_id['catalog']});
                $.cookie('list_copy', null);
                $.cookie('list_copy', json_array_id, { path: '/'});
                var popup = ShowPopUp('popup-to-copy-buffer', 'popup-content-to-copy-buffer');
                if (object == 'document') {
                    popup.append('<h3 style="padding: 5px">Документ скопирован в буфер</h3>');
                } else if (object == 'catalog') {
                    popup.append('<h3 style="padding: 5px">Рубрика скопирована в буфер</h3>');
                }
                setTimeout(function() {
                    $('.b-popup').hide(300);
                    $('.b-popup-content').removeClass().empty();
                    $('.b-popup').children('div').addClass('b-popup-content');
                }, 1000);
            }
        }
        //console.log($.cookie('list_copy'));
    });

    //очитска буфера
    $("body").on('click', 'a.clear-buffer', function () {
        $.cookie('list_copy', null, { path: '/'});
        location.reload();
    });

    //удаление из буфера
    $("body").on('click', 'a.delete-from-buffer', function () {
        var id = $(this).attr('idd');
        var json_array_id = $.cookie('list_copy');
        var array_id = JSON.parse(json_array_id);
        var index_array_catalog = $.inArray(id, array_id['catalog']);
        var index_array_document = $.inArray(id, array_id['document']);

        if (index_array_catalog >= 0) {
            array_id['catalog'].splice(index_array_catalog, 1);
            $(this).parents('tr').remove();
        }
        else if (index_array_document >= 0) {
            array_id['document'].splice(index_array_document, 1);
            $(this).parents('tr').remove();
        }

        if ((array_id['document'].length == 0) && (array_id['catalog'].length == 0)) {
            $.cookie('list_copy', null, { path: '/'});
            alert('Буфер пуст');
            location.reload();
        } else {
            json_array_id = JSON.stringify({'document' : array_id['document'], 'catalog' : array_id['catalog']});
            $.cookie('list_copy', null);
            $.cookie('list_copy', json_array_id, { path: '/'});
        }
    });

    //вставка из буфера

    $("body").on('submit', '#copy-from-buffer', function (e) {
        $('.display_errors').empty().slideUp(500);
        var dataForm = $(this).serializeArray();
        var object = dataForm.shift();
        var id_parent = dataForm.shift();
        var json_form_data = JSON.stringify(dataForm);
        if (dataForm.length > 0) {
            $.ajax({
                type: 'POST',
                url: '/'+object['value']+'/ajaxInsertFromBuffer/'+id_parent['value']+'/',
                data: 'array-for-insert='+json_form_data,
                success: function (data) {
                    var array_answer = JSON.parse(data);
                    if (array_answer['code'] == 'success') {
                        alert('Количество скопированных документов: ' + array_answer['count_documents']);
                        location.reload();
                    } else {
                        array_answer.pop();
                        array_answer.forEach(function (item, i, array_answer) {
                            $('.display_errors').append('<li>'+item+'</li>').slideDown(500);
                        });
                    }
                }
            });
        } else {
            alert('Вы не выбрали файлы (рубрики) для копирования');
        }
        e.preventDefault();
    });

    /************************************************************************************************************/

    //окно буфера
    $('.insert-from-buffer').click(function () {
        var id_parent = $(this).attr('id_parent');
        var object = $(this).attr('object');
        var popup = ShowPopUp('', 'popup-buffer');
        popup.append('<p><img src="/images/466.gif"></p>');
        popup.load(
            '/'+object+'/ajaxListBuffer/',
            {
                id_parent: id_parent,
                object: object
            }
        );
    });

    /************************************************************************************************************/

    //добавление комментариев
    $('#button-add-message').click(function(){
        var txt = $('#message').val();
        var id_kz = $('#idkz').val();
        var user_name = $('#nameuser').val();
        $.ajax({
            type: "POST",
            url: "/mapknowledge/ajaxAddMessage/",
            data: "txt="+txt+"&idkz="+id_kz+"&userName="+user_name,
            success: function(data) {
                if (data > 0) {
                    alert('Сообщение успено добавлено');
                    location.reload();
                }
                else {
                    alert('Произошла ошибка!' + data)
                }
            }
        });
    });

    /************************************************************************************************************/

    //змнение комментария
    $('.field-text .edit-something').click(function() {
        var id = $(this).attr('idd');
        var paragraph_message = $(this).parent().parent().next();
        var text = paragraph_message.text();
        console.log(id+paragraph_message+text);
        paragraph_message.empty().append('<textarea idd="'+id+'" class="edit-area-message">'+text+'</textarea>');
        paragraph_message.append('<a idd="'+id+'" style="color: green" class="click-button edit-msg">Сохранить</a>');
        paragraph_message.append('<a style="color: darkred" class="click-button cancel-edit-msg">Отменить</a><input type="hidden" value="'+text+'" >');
        $(this).hide();

        $("body").on('click', 'a.edit-msg', function () {
            var newText = $('.edit-area-message[idd="'+id+'"]').val();
            $.ajax ({
                type: "POST",
                data: "text="+newText,
                url: "/mapknowledge/ajaxEditMessage/"+id,
                success: function (data) {
                    location.reload();
                }
            })
        });

        $("body").on('click', 'a.cancel-edit-msg', function () {
            var old_text = $(this).next().val();
            $(this).parent().prev().find($('.edit-something')).show();
            $(this).parent().empty().html(old_text);
        })
    });

    /************************************************************************************************************/
    //сохранение шаблонов со страницы расширенного поиска
    // $('.save-templates-from-bigsearch').click(function (e) {
    //
    //     var ajax_url = $(this).data('ajax');
    //     var name = $('#name-template').val();
    //     var id = $('#id-template').val();
    //     var json_data = $('#json-template').val();
    //     if (name == '') {
    //         // alert("Пустое имя шаблона!");
    //         $('#name-template').css('border-color', 'red');
    //     } else {
    //         $('#name-template').css('border-color', 'none');
    //         if (json_data == '') {
    //             alert("Пустая строка запроса!");
    //         } else {
    //             console.log(name, id, ajax_url);
    //             $.ajax({
    //                 type: "POST",
    //                 data: "name="+name+"&id="+id+"&sql="+json_data,
    //                 url: ajax_url+id,
    //                 success: function (data) {
    //                     if (data > 0) {
    //                         alert('Шаблон успешн сохранен');
    //                     }
    //                     else if (data == -1) {
    //                         alert('Шаблон с таким именем уже сохранен');
    //                     }
    //                     else {
    //                         alert('Ошибка' + data);
    //                     }
    //                 }
    //             })
    //         }
    //     }
    //
    //
    // });
   
    /************************************************************************************************************/
    // получение списка пользователей в модальном окне
    $('.all-users').click(function () {
        var popup = ShowPopUp();
        popup.load('/profile/ajaxListUsers/'); //Ne vli9et na raboty
        console.log('click list-users');
    });
    /************************************************************************************************************/


    /************************************************************************************************************/
    // сзоранение профиля в БД
    /*$('#set-theme-profile').submit(function (e) {
        e.preventDefault();
        var dataForm = $(this).serialize();
        $.ajax ({
            type: "POST",
            data: dataForm,
            url: profilechange_function.php,
            success: function (data) {
                if (data == 1) {
                    alert('Настройки успешно сохранены');
                    location.reload();
                }
            }
        });
    });*/

    /************************************************************************************************************/
    // авторизация в системе
    $('#auth-form').submit(function (e) {
        e.preventDefault();
        var dataForm = $(this).serialize();
        $.ajax ({
            type: "POST",
            data: dataForm,
            url: $(this).attr('action'),
            dataType : 'text',
            success: function (data) {
                if (data == 1) {
                    alert('Вы успешно авторизовались в системе');
                    location.reload();
                } else if (data == 2) {
                    $('#auth-form .block-error').empty().show().append('Такого пользователя не существует в системе');
                } else if (data == 3) {
                    $('#auth-form .block-error').empty().show().append('Неправильно введен логин или пароль');
                }
                console.log(data);
            }
        });

    });
    /************************************************************************************************************/
    // поиск документов по шаблонам и оповещениям
    $('.search-this-template').click(function () {
        $('#hide_form_search input#id-template').val($(this).data('id'));
        $('#hide_form_search').submit();
    })
    /************************************************************************************************************/
    /************************************************************************************************************/
    /************************************************************************************************************/
    /************************************************************************************************************/
    /************************************************************************************************************/
    /************************************************************************************************************/
    /************************************************************************************************************/
    /************************************************************************************************************/
    /************************************************************************************************************/
    /************************************************************************************************************/
    /************************************************************************************************************/
    /************************************************************************************************************/
    /************************************************************************************************************/
    /************************************************************************************************************/

});

