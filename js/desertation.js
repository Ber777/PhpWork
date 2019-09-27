
$(document).ready(function()

{
    /*---------------------------------   AJAX   ---------------------------------*/



    //изменение название дескриптора
    $('.edit-tag').click(function() {
        var clickElement = $(this);
        var tag = clickElement.parent().parent().prev().prev();
        var search = clickElement.parent().parent().prev();
        var name = tag.text();
        var id = clickElement.attr('idd');
        clickElement.hide();
        search.hide();
        tag.text('');
        tag.append('<input class="standart-input ajax-edit-tag" idd="'+id+'" type="text" value="'+name+'" /><a class="set-new-name">Изменить</a><a nametag="'+name+'" class="cancel-edit">Отменить</a>');
        $('.set-new-name').click(function(){
            newName = $(this).prev().val();
            $.ajax({
                type: "POST",
                url: "/include/ajax/edit_tag_in_bd.php",
                data: "id="+id+"&name="+newName,
                dataType: "html",
                cache: false,
                success: function(data) {
                    tag.empty();
                    tag.append(newName);
                    clickElement.show();
                    search.show();
                }
            })
        })
        $('.ajax-edit-tag[idd="'+id+'"]').keydown(function(e) {
            if (e.keyCode == 13) {
                newName = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "/include/ajax/edit_tag_in_bd.php",
                    data: "id="+id+"&name="+newName,
                    dataType: "html",
                    cache: false,
                    success: function(data) {
                        tag.empty();
                        tag.append(newName);
                        clickElement.show();
                        search.show();
                    }
                })

            };
        })
        $("body").on('click', 'a.cancel-edit', function () {
            name = $(this).attr('nametag');
            console.log(name);
            $(this).parent().text(name);
            $(this).parent().empty();
            clickElement.show();
            search.show();
        })
    })

    //изменение название атрибута
    $('.edit-attribute').click(function() {
        var clickElement = $(this);
        var attr = clickElement.parent().parent().prev().prev();
        var search = clickElement.parent().parent().prev();
        var name = attr.html();
        var id = clickElement.attr('idd');
        clickElement.hide();
        search.hide();
        attr.text('');
        attr.append('<input class="standart-input ajax-edit-attribute" idd="'+id+'" type="text" value="'+name+'" /><a class="set-new-name">Изменить</a><a nameattribute="'+name+'" class="cancel-edit">Отменить</a>');
        $('.set-new-name').click(function(){
            newName = $(this).prev().val();
            if (newName != '') {
                $.ajax({
                    type: "POST",
                    url: "/include/ajax/edit_attribute_in_bd.php",
                    data: "id="+id+"&name="+newName,
                    dataType: "html",
                    cache: false,
                    success: function(data) {
                        attr.empty();
                        attr.append(newName);
                        clickElement.show();
                        search.show();
                        console.log(data);
                    }
                })
            } else $(this).prev().css('border', '1px solid red');

        })
        $('.ajax-edit-attribute[idd="'+id+'"]').keydown(function(e) {
            if (e.keyCode == 13) {
                newName = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "/include/ajax/edit_attribute_in_bd.php",
                    data: "id="+id+"&name="+newName,
                    dataType: "html",
                    cache: false,
                    success: function(data) {
                        attr.empty();
                        attr.append(newName);
                        clickElement.show();
                        search.show();
                        console.log(data);
                    }
                })

            };
        })
        $("body").on('click', 'a.cancel-edit', function () {
            name = $(this).attr('nameattribute');
            console.log(name);
            $(this).parent().text(name);
            $(this).parent().empty();
            clickElement.show();
            search.show();
        })
    })

    //добавление тега в БД
    $('#add-tag-in-bd').click(function(){
        var name = $("#name-tag").val();
        if (name != '') {
            $.ajax({
                type: "POST",
                url: "/include/ajax/add_teg_in_bd.php",
                data: "name="+name,
                dataType: "html",
                cache: false,
                success: function(data)
                {
                    //if( data>0 )
                    alert("Вы добавили новый дескриптор");
                    location.reload();
                    //else
                    //alert("Ошибка добавления");
                }
            });
        } else {
            $("#name-tag").css('border', '1px solid red');
            return false;
        }
    })

    //добавление атрибута в БД
    $('#add-attribute-in-bd').click(function(){
        var name = $("#name-attribute").val();
        var type = $("#select-type").val();
        console.log(type);
        if (name != '') {
            $.ajax({
                type: "POST",
                url: "/include/ajax/add_attr_in_bd.php",
                data: "name="+name+"&type="+type,
                dataType: "html",
                cache: false,
                success: function(data)
                {
                    alert("Вы добавили новый атрибут");
                    location.reload();
                }
            });
        }
        else {
            $("#name-attribute").css('border', '1px solid red');
            return false;
        }
        return false;
    })


    //добавление каталога в БД
    $('#button-add-folder').click(function(){
        var dataForm = $('#form-add-folder').serializeArray();
        var dataFormJSON = JSON.stringify(dataForm);

        dataForm.forEach(function(item, i, dataForm){
            var iType = $('input[name="'+dataForm[i].name+'"]').attr('itype');
            dataForm[i].itype = iType;
        })
        //получение списка тегов
        var listTags = new Array;
        $('ul#list-tegs li.block-list-tags').each(function (){
            var element = $(this).text();
            listTags.push(element.slice(0, -1));
        })
        var dataFormJSON = JSON.stringify(dataForm);
        var listTagsJSON = JSON.stringify(listTags);

        console.log(dataFormJSON);
        console.log(listTagsJSON);
        $.ajax({
            type: "POST",
            url: "/include/ajax/add_catalog_in_bd.php",
            data: "dataForm="+dataFormJSON+"&listTags="+listTagsJSON,
            dataType: "html",
            cache: false,
            success: function(data) {
                if (data == -1) {
                    $('div.area-messages > p').text('Ошибка! Такая рубрика уже существует');
                    $('div.area-messages').css('background-color', 'rgb(254, 91, 91)').slideDown(400);
                }
                else if (data > -1) {
                    $('div.area-messages > p').text('Рубрика успешно создана!');
                    $('div.area-messages').css('background-color', 'rgb(113, 232, 122)').slideDown(400);
                }
                else  {
                    $('div.area-messages > p').text('Ошибка! '+data);
                    $('div.area-messages').css('background-color', 'rgb(254, 91, 91)').slideDown(400);
                }
                //alert(data);
            }
        });
    })

    //
    //добавление шаблонов
    $('#form-add-template').submit(function(){
        var object_template = $('#name_object').val();
        var dataForm = $('#form-add-template').serializeArray();

        //получение типов
        dataForm.forEach(function(item, i, dataForm){
            var iType = $('input[name="'+dataForm[i].name+'"]').attr('itype');
            dataForm[i].itype = iType;
        })
        //получение списка тегов
        var listTags = new Array;
        $('ul#list-tegs li.block-list-tags').each(function (){
            var element = $(this).text();
            listTags.push(element.slice(0, -1));
        })

        var dataFormJSON = JSON.stringify(dataForm);
        var listTagsJSON = JSON.stringify(listTags);
        console.log(dataFormJSON);
        console.log(listTagsJSON);
        if (object_template == 'document') {
            //получение пцти
            var path = $('#doc').attr('pathFile');
            $.ajax({
                type: "POST",
                url: "/include/ajax/add_template_doc_in_bd.php",
                dataType: "",
                data: "dataForm="+dataFormJSON+"&listTags="+listTagsJSON+"&path="+path,
                cache: false,
                success: function(data) {
                    if (data > 0) {
                        alert('Шаблон успешно сохранен!');
                        //console.log(data);
                        location.reload();
                    }
                    else {
                        alert('Ошибка!'+data);
                    }
                    console.log(data);
                    return false;
                }
            })
        }
        if (object_template == 'mapknowledge') {
            $.ajax({
                type: "POST",
                url: "/include/ajax/add_template_mk_in_bd.php",
                dataType: "",
                data: "dataForm="+dataFormJSON+"&listTags="+listTagsJSON,
                cache: false,
                success: function(data) {
                    if (data > 0) {
                        alert('Шаблон успешно сохранен!');
                        //console.log(data);
                        location.reload();
                    }
                    else {
                        alert('Ошибка!'+data);
                    }
                    console.log(data);
                    return false;
                }
            })
        }
        return false;
    })
    //изменение шаблонов
    $('#form-edit-template').submit(function(){
        var object_template = $('#name_object').val();
        var id = $('#id_object').val();
        var dataForm = $('#form-edit-template').serializeArray();

        //получение типов
        dataForm.forEach(function(item, i, dataForm){
            var iType = $('input[name="'+dataForm[i].name+'"]').attr('itype');
            dataForm[i].itype = iType;
        })
        //получение списка тегов
        var listTags = new Array;
        $('ul#list-tegs li.block-list-tags').each(function (){
            var element = $(this).text();
            listTags.push(element.slice(0, -1));
        })

        var dataFormJSON = JSON.stringify(dataForm);
        var listTagsJSON = JSON.stringify(listTags);
        console.log(dataFormJSON);
        console.log(listTagsJSON);
        if (object_template == 'document') {

            //получение пцти
            var path = $('#doc').attr('pathFile');
            $.ajax({
                type: "POST",
                url: "/include/ajax/edit_template_doc_in_bd.php",
                dataType: "",
                data: "dataForm="+dataFormJSON+"&listTags="+listTagsJSON+"&id="+id+"&path="+path,
                cache: false,
                success: function(data) {
                    if (data > 0) {
                        //console.log(data);
                        alert('Шаблон успешно сохранен!');
                        //location.reload();
                        return false;
                    }
                    else {
                        alert('Ошибка!'+data);
                        return false;
                    }
                }
            })
        }
        if (object_template == 'mapknowledge') {
            $.ajax({
                type: "POST",
                url: "/include/ajax/edit_template_mk_in_bd.php",
                dataType: "",
                data: "dataForm="+dataFormJSON+"&listTags="+listTagsJSON+"&id="+id,
                cache: false,
                success: function(data) {
                    if (data > 0) {
                        //console.log(data);
                        alert('Шаблон успешно сохранен!');
                        //location.reload();
                        return false;
                    }
                    else {
                        alert('Ошибка!'+data);
                        return false;
                    }
                }
            })
        }
        /*else if (object_template == 'search') {
         console.log(object_template);
         }*/
        else if (object_template == 'catalog') {
            $.ajax({
                type: "POST",
                url: "/include/ajax/edit_template_catalog_in_bd.php",
                dataType: "",
                data: "dataForm="+dataFormJSON+"&listTags="+listTagsJSON+"&id="+id,
                cache: false,
                success: function(data) {
                    if (data > 0) {
                        //console.log(data);
                        alert('Шаблон успешно сохранен!');
                    }
                    else if (data == -1) {
                        alert('Ошибка! Такое имя уже существует!');
                    }
                    else {
                        alert('Ошибка!'+data);
                    }
                    //alert(data);
                }
            })
        }
        return false;
    })


    //редактирование каталога в БД
    $('#button-edit-folder').click(function(){
        var dataForm = $('#form-edit-folder').serializeArray();

        dataForm.forEach(function(item, i, dataForm){
            var iType = $('input[name="'+dataForm[i].name+'"]').attr('itype');
            dataForm[i].itype = iType;
        })
        //получение списка тегов
        var listTags = new Array;
        $('ul#list-tegs li.block-list-tags').each(function (){
            var element = $(this).text();
            listTags.push(element.slice(0, -1));
        })
        var dataFormJSON = JSON.stringify(dataForm);
        var listTagsJSON = JSON.stringify(listTags);

        $.ajax({
            type: "POST",
            url: "/include/ajax/edit_catalog_in_bd.php",
            data: "dataForm="+dataFormJSON+"&listTags="+listTagsJSON,
            dataType: "html",
            cache: false,
            success: function(data) {
                console.log(data);
                if (data == -1) {
                    $('div.area-messages > p').text('Ошибка! Такая рубрика уже существует');
                    $('div.area-messages').css('background-color', 'rgb(254, 91, 91)').slideDown(400);
                }
                else if (data > -1) {
                    $('div.area-messages > p').text('Рубрика успешно сохранена!');
                    $('div.area-messages').css('background-color', 'rgb(113, 232, 122)').slideDown(400);
                }
                else  {
                    $('div.area-messages > p').text('Ошибка! '+data);
                    $('div.area-messages').css('background-color', 'rgb(254, 91, 91)').slideDown(400);
                }
            }
        });
    })

    $('#doc').click(function() {
        var nameFile = $(this).val();//.replace(/.+[\\\/]/, "");
        var folder = $(this).attr('its');
        console.log('получили текущее имя'+nameFile);
        $('#doc').change( function() {
            $('#doc').parent().next().empty().append('<img height="30px" src="/images/466.gif" /> Загрузка файла...').css('color', 'black');
            if (nameFile != '') {
                $.ajax({
                    type: 'POST',
                    url: '/include/ajax/delete_file_from_server.php',
                    data: 'nameFile='+nameFile+"&folder="+folder,
                    success: function (data) {
                        console.log(data);
                    }
                })
            }
            var $input = $("#doc");
            var fd = new FormData;
            fd.append('doc', $input.prop('files')[0]);
            console.log('получили новый'+$input);
            if (folder == 'docs') {
                $.ajax({
                    url: '/include/ajax/add_file_in_bd.php',
                    data: fd,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    success: function (data) {
                        //console.log('вернули новый адрес'+data);
                        $('#doc').attr('pathFile', data);
                        $('#doc').parent().next().empty().append('Файл успешно загружен!').css('color', 'green');
                        $('#doc').parent().next().append('<p><a path="'+data+'" class="click-button auto_check_file">Автозаполнение</a></p>');
                    }
                });
            }
            else if (folder == 'templates_docs') {
                $.ajax({
                    url: '/include/ajax/add_template_doc_in_bd.php',
                    data: fd,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    success: function (data) {
                        //console.log('вернули новый адрес'+data);
                        $('#doc').attr('pathFile', data);
                        $('#doc').parent().next().empty().append('Файл успешно загружен!').css('color', 'green');
                        $('#doc').parent().next().append('<a path="'+data+'" class="click-button auto_check_file">Автозаполнение</a>');
                    }
                });
            }

        })
    })

    //добавление документа
    $('#form-add-file').submit(function(){
        var dataForm = $('#form-add-file').serializeArray();
        if ($("#saveTempl").prop("checked")) {
            var nameTempl = $("#nameTempl").val();
        }
        //получение типов
        dataForm.forEach(function(item, i, dataForm){
            var iType = $('input[name="'+dataForm[i].name+'"]').attr('itype');
            dataForm[i].itype = iType;
        })
        //получение списка тегов
        var listTags = new Array;
        $('ul#list-tegs li.block-list-tags').each(function (){
            var element = $(this).text();
            listTags.push(element.slice(0, -1));
        })

        //получение пцти
        var path = $('#doc').attr('pathFile');

        var dataFormJSON = JSON.stringify(dataForm);
        var listTagsJSON = JSON.stringify(listTags);
        console.log(dataFormJSON);
        console.log(listTagsJSON);
        $.ajax({
            type: "POST",
            url: "/include/ajax/add_file_in_bd.php",
            dataType: "html",
            data: "dataForm="+dataFormJSON+"&listTags="+listTagsJSON+"&nameTempl="+nameTempl+"&path="+path,
            cache: false,
            success: function(data) {
                if (data > 0) {
                    console.log(data);
                    alert('Документ добавлен');
                    //location.reload();
                }
                else {
                    alert('Ошибка!'+data);
                }
                return false;
            }
        })
        return false;
    })

    //добавление КЗ
    $('#button-add-mapknowledge').click(function(){

        var dataForm = $('#form-add-mapknowledge').serializeArray();
        if ($("#saveTempl").prop("checked")) {
            var nameTempl = $("#nameTempl").val();
        }
        //получение типов
        dataForm.forEach(function(item, i, dataForm){
            var iType = $('input[name="'+dataForm[i].name+'"]').attr('itype');
            dataForm[i].itype = iType;
        })
        //получение списка тегов
        var listTags = new Array;
        $('ul#list-tegs li.block-list-tags').each(function (){
            var element = $(this).text();
            listTags.push(element.slice(0, -1));
        })
        var dataFormJSON = JSON.stringify(dataForm);
        var listTagsJSON = JSON.stringify(listTags);
        $.ajax({
            type: "POST",
            url: "/include/ajax/add_mapknowledge_in_bd.php",
            dataType: "",
            data: "dataForm="+dataFormJSON+"&listTags="+listTagsJSON+"&nameTempl="+nameTempl,
            cache: false,
            success: function(data) {
                if (data > 0) {
                    alert('Карта знаний успешно добавлена');
                    location.reload();
                }
                else {
                    alert('Ошибка!'+data);
                }
                console.log(data);
            }

        })
    })

    //изменение документа
    $('#button-edit-file').click(function(){
        var dataForm = $('#form-edit-file').serializeArray();
        if ($("#saveTempl").prop("checked")) {
            var nameTempl = $("#nameTempl").val();
        }
        //получение типов
        dataForm.forEach(function(item, i, dataForm){
            var iType = $('input[name="'+dataForm[i].name+'"]').attr('itype');
            dataForm[i].itype = iType;
        })
        //получение списка тегов
        var listTags = new Array;
        $('ul#list-tegs li.block-list-tags').each(function (){
            var element = $(this).text();
            listTags.push(element.slice(0, -1));
        })

        //получение пцти
        var path = $('#doc').attr('pathFile');

        var dataFormJSON = JSON.stringify(dataForm);
        var listTagsJSON = JSON.stringify(listTags);
        console.log(dataFormJSON);
        console.log(listTagsJSON);
        $.ajax({
            type: "POST",
            url: "/include/ajax/edit_file_in_bd.php",
            dataType: "html",
            data: "dataForm="+dataFormJSON+"&listTags="+listTagsJSON+"&nameTempl="+nameTempl+"&path="+path,
            cache: false,
            success: function(data) {
                if (data > 0) {
                    console.log(data);
                    alert('Документ успешно изменен!');
                    //location.reload();
                }
                else {
                    alert('Ошибка!'+data);
                }
                console.log(data);
            }
        })
    })

    //изменение КЗ
    $('#button-edit-mapknowledge').click(function(){
        var dataForm = $('#form-edit-mapknowledge').serializeArray();
        if ($("#saveTempl").prop("checked")) {
            var nameTempl = $("#nameTempl").val();
        }
        //получение типов
        dataForm.forEach(function(item, i, dataForm){
            var iType = $('input[name="'+dataForm[i].name+'"]').attr('itype');
            dataForm[i].itype = iType;
        })
        //получение списка тегов
        var listTags = new Array;
        $('ul#list-tegs li.block-list-tags').each(function (){
            var element = $(this).text();
            listTags.push(element.slice(0, -1));
        })
        var dataFormJSON = JSON.stringify(dataForm);
        var listTagsJSON = JSON.stringify(listTags);
        $.ajax({
            type: "POST",
            url: "/include/ajax/edit_mapknowledge_in_bd.php",
            dataType: "html",
            data: "dataForm="+dataFormJSON+"&listTags="+listTagsJSON+"&nameTempl="+nameTempl,
            cache: false,
            success: function(data) {
                alert("Карта знаний успешно изменена!");
                console.log(data);
                //location.reload();
            }

        })
    })

    //добавление шаблона из рубрики----------------------------------------------------------
    $('#create-template-from-catalog').click(function(){
        var div = $(this).next();
        div.slideDown();
    })

    $('#create-template').click(function(){
        var id_catalog = $('#id_catalog').val();
        var name = $('#name_template').val();
        if (name != '') {
            $.ajax({
                type: "POST",
                url: "/include/ajax/add_template_from_doc_in_bd.php",
                dataType: "html",
                data: "id="+id_catalog+"&name="+name,
                success: (function(data){
                    if (data > 0) {
                        $('#insert-template-from-catalog > p').html('Шаблон успешно создан').css('background-color', 'rgb(17, 221, 17)').slideDown();
                    }
                    else if (data == -1) {
                        $('#insert-template-from-catalog > p').html('Шаблон с таким именем уже есть').css('background-color', 'red').slideDown();
                    }
                    else $('#insert-template-from-catalog > p').html('Ошибка!'+data).css('background-color', 'red').slideDown();

                })
            })
        }
        else $('#name_template').css('border', '1px solid red');
    })

    $(document).on('click', '#insert-template-from-catalog .cancel', function () {
        $('#insert-template-from-catalog').hide();
        $('#insert-template-from-catalog > p').empty().hide();
    })
    //---------------------------------------------------------------------------------------

    //добавдение рубрики из шаблона----------------------------------------------------------
    $('#link-insert-catalog-from-template').click(function(){
        var div = $(this).next();
        div.load('./include/load/add_catalog_from_template.php');
        div.slideDown();
    })

    $(document).on('click', '#insert-catalog-from-template .cancel', function () {
        $('#insert-catalog-from-template').hide().empty();
    })



    $(document).on('click', '#button-add-cat-from-temp', function () {
        var id_parent = $('#insert-catalog-from-template').attr('idparent');
        var name = $('#input-name-catalog').val();
        var id_templ = $('#select-templ').val();
        if ((name != '') && (id_templ != 0))
        {
            $('#input-name-catalog').css('border', '1px solid green');
            $('#select-templ').css('border', '1px solid green');
            $.ajax({
                type: "POST",
                url: "/include/ajax/add_doc_from_template_in_bd.php",
                dataType: "html",
                data: "idparent="+id_parent+"&name="+name+"&idtempl="+id_templ,
                success: (function(data){
                    if (data > 0) {
                        location.reload()
                    }
                    else alert('Ошибка'+data);

                })
            })
        }
        else if (name == '') $('#input-name-catalog').css('border', '1px solid red');
        else if (id_templ == 0) $('#select-templ').css('border', '1px solid red');

    })
    //--------------------------------------------------------------------------------------
    //список пользователей и их ролей доступа в систему
    $('.edit-access-user').click(function() {
        var id = $(this).attr('idd');
        $('#add_access_user').fadeIn('normal');
        $('#block-for-role-user').load('./views/block/access_to_user.php', {idobj:id});
    })
    //закрыть
    $(document).on('click', '#back', function () {
        $('#add_access_user').fadeOut('normal');
        var del = new Array;
    })
    //назначение ролей
    $('.edit-access').click(function() {
        var id = $(this).attr('idd');
        $('#add_access').fadeIn('normal');
        $('#block-for-role').load('./views/block/access_to_object.php', {idobj:id});
    })
    //закрыть окно назначения ролей
    $(document).on('click', '#back1', function () {
        $('#add_access').fadeOut('normal');
        var del = new Array;
    })

    // $(document).mouseup(function (e){ // событие клика по веб-документу
    //  var div = $("#add_access"); // тут указываем ID элемента
    //  if (!div.is(e.target) // если клик был не по нашему блоку
    //     && div.has(e.target).length === 0) { // и не по его дочерним элементам
    //   div.fadeOut('normal'); // скрываем его
    //  var del = new Array;
    // }
    // });
    //функция добавления нового пользователя в назначения ролей
    function new_user(count) {
        var idUser = document.getElementById("name").value;
        var nameUser = $("#name option:selected").text();
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
                    $('<td align = \"center\"><input type=\"checkbox\" class=\"flag\" mask=\"' + b + '\" user=\"'+ idUser +'\" access=\"'+ b +'\" id=\"way[' + idUser + '][' + b + ']\" name=\"way[' + idUser + '][' + b + ']\"/></td>').appendTo(tr);
                    a++;
                }
                $('<td class=\"pointer1\" data-nameUser=' + idUser + '>удалить</td>').appendTo(tr);
                $('#name_user').append(tr);
                $('#err_user').fadeOut('fast');
            }
            else {
                $("#err_user").fadeIn('fast');
            }
        }
    }

    //удаление пользовтеля в назначении прав
    var del = [];
    function del_user(nameUserdel) {
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
    $(document).on('click', '.pointer_add_new', function () {
        var count = $(this).attr('data-count');
        new_user(count);
    });
    $(document).on('click', '.pointer1', function () {
        var nameUserdel = $(this).attr('data-nameUser');
        del_user(nameUserdel);
    });

    //---------------------------Обработка прав на объекты-----------------------------
    $(document).on('submit', '#add_access_window', function () {
        var arrayVal = $('form#add_access_window').serializeArray();
        arrayVal.forEach(function(item, i, arrayVal){
            var access = $('input[name="'+arrayVal[i].name+'"]').attr('access');
            var user = $('input[name="'+arrayVal[i].name+'"]').attr('user');
            //var nameUs = $('input[name="'+arrayVal[i].name+'"]').attr('nameUser');
            arrayVal[i].access = access;
            arrayVal[i].user = user;
            //arrayName[i].nameUser = nameUs;
        })
        arrayValJSON = JSON.stringify(arrayVal);
        delUser = JSON.stringify(del);
        console.log(arrayVal);
        $.ajax({
            url:'/include/ajax/set_user_access.php',
            type:'POST',
            dataType: "html",
            data:'jsonData='+arrayValJSON+"&delUser="+delUser,
            success: function(data) {
                console.log(data)
                if (data == '') {
                    $('#add_access').fadeOut('normal');
                }
                else {
                    $('#addrightmsg').html(data);
                }
            }
        })
        return false;
    });

    //---------------------------------------------------------------------------------
    
    //добалвение базы данных
    $('#button-add-db').click(function(){
        var dataForm = $('#form-add-db').serializeArray();
        //получение типов
        dataForm.forEach(function(item, i, dataForm){
            var iType = $('input[name="'+dataForm[i].name+'"]').attr('itype');
            dataForm[i].itype = iType;
        })
        //получение списка тегов
        var listTags = new Array;
        $('ul#list-tegs li.block-list-tags').each(function (){
            var element = $(this).text();
            listTags.push(element.slice(0, -1));
        })
        var dataFormJSON = JSON.stringify(dataForm);
        var listTagsJSON = JSON.stringify(listTags);
        $.ajax({
            type: "POST",
            url: "/include/ajax/add_db_in_bd.php",
            dataType: "",
            data: "dataForm="+dataFormJSON+"&listTags="+listTagsJSON,
            cache: false,
            success: function(data) {
                if (data > 0) {
                    alert('База данных успешно добавлена');
                    location.reload();
                }
                else {
                    alert('Ошибка добавления!');
                    console.log(data);
                }

            }

        })
        return false;
    })


    //изменение БД
    $('#button-edit-db').click(function(){
        var dataForm = $('#form-edit-db').serializeArray();
        /*if ($("#saveTempl").prop("checked")) {
         var nameTempl = $("#nameTempl").val();
         }*/
        //получение типов
        dataForm.forEach(function(item, i, dataForm){
            var iType = $('input[name="'+dataForm[i].name+'"]').attr('itype');
            dataForm[i].itype = iType;
        })
        //получение списка тегов
        var listTags = new Array;
        $('ul#list-tegs li.block-list-tags').each(function (){
            var element = $(this).text();
            listTags.push(element.slice(0, -1));
        })
        var dataFormJSON = JSON.stringify(dataForm);
        var listTagsJSON = JSON.stringify(listTags);
        $.ajax({
            type: "POST",
            url: "/include/ajax/edit_db_in_bd.php",
            dataType: "html",
            data: "dataForm="+dataFormJSON+"&listTags="+listTagsJSON,
            cache: false,
            success: function(data) {
                if (data > 0) {
                    alert("База данных успешно изменена!");
                }
                else {
                    alert('Ошибка:'+data);
                }
                //console.log(data);
                //location.reload();
            }

        })
    })

    //добавление комментариев
    $('#button-add-message').click(function(){
        var txt = $('#message').val();
        var id_kz = $('#idkz').val();
        var user_name = $('#nameuser').val();
        console.log(txt);
        console.log(id_kz);
        console.log(user_name);
        $.ajax({
            type: "POST",
            url: "/include/ajax/add-comment.php",
            data: "txt="+txt+"&idkz="+id_kz+"&userName="+user_name,
            dataType: "html",
            cache: false,
            success: function(data) {
                //			alert(data);
                console.log(data);
                location.reload();
            }
        });
        return false;
    });

    //поиск по тегам из страницы тегов
    $('.search-this-tag').click(function(){
        var listTags = new Array;
        var place = $(this).attr('place');
        var tag = $(this).attr('nametag');
        listTags.push(tag);
        var listTagsJSON = JSON.stringify(listTags);
        $.ajax({
            type: "POST",
            url: "/include/ajax/search_file_by_tag.php",
            data: "listTags="+listTagsJSON+"&id="+place,
            dataType: "html",
            cache: false,
            success: function(data) {
                document.location.href = 'index.php?view=search';
                console.log(data);
            }
        });
    })

    //поиск по атрибуту из страницы атрибутов
    $('.search-this-attributes').click(function(){
        var place = $(this).attr('place');
        var attribute = $(this).attr('idattribute');
        $.ajax({
            type: "POST",
            url: "/include/ajax/search_file_by_attr.php",
            data: "attribute="+attribute+"&id="+place,
            dataType: "html",
            cache: false,
            success: function(data) {
                document.location.href = 'index.php?view=search';
                console.log(data);
            }
        });
    })

    $('#form-search-template #start-search').click(function(){
        var idTypeTemplate = $('#id-type-template').val();
        var name = $('#input-name-template').val();
        $('#block-items').prepend('<div class="result-search-template" result="'+name+'"></div>');
        $('.result-search-template[result="'+name+'"]').load(
            './include/load/result_search_template.php',
            {
                idtype: idTypeTemplate,
                name: name
            }
        );
        $('.result-search-template[result="'+name+'"]').css('border-bottom', '1px solid black');
    })

    $(document).on('click', '.close-result-search-template', function(){
        $(this).parent().remove();
    })

    $('#start-search-like').click(function(){
        var idTypeTemplate = $('#id-type-template').val();
        var name = $('#input-name-template').val();
        $('#block-items').prepend('<div class="result-search-template" result="'+name+'"></div>');
        $('.result-search-template[result="'+name+'"]').load(
            './include/load/result_search_template.php',
            {
                idtype: idTypeTemplate,
                name: name
            }
        );
        $('.result-search-template[result="'+name+'"]').css('border-bottom', '1px solid black');
    })

    $(document).on('click', '.close-result-search-template', function(){
        $(this).parent().remove();
    })


    //кнопка логаута
    $('#logout').click(function(){
        $.ajax({
            type: "POST",
            url: "/include/ajax/logout.php",
            dataType: "html",
            cache: false,
            success: function(data) {
                document.location.href = 'index.php?view=main';
            }
        });
    })


    $('a.modal-up').on('click', function(){
        $.ajax({
            type : "POST",
            url : "/views/cattemplate/tree.php",
            data: {
                id : $(this).parent().attr("template"),
            },
            dataType: "html",
            success: function(data){
                $('.modal-window-tree').html(data);
                $('#terms-of-service').modal().open();
            }
        })
    })


    $('.export_data').click(function(){
        $.ajax({
            type: "POST",
            url: "/include/ajax/export_data.php", //url обработчика
            //data: -- подача данных в обработчик
            dataType: "html", //html - формат ответа сервера, если вы возвращаете json то нужно написать json
            success: function (data) { //в переменной data, содержится ответ сервера
                alert(data);
            }
        })
    })

    $('#select_place_tree').on('click', function(){
        $.ajax({
            type: "POST",
            url: "/views/search/select_tree.php",
            dataType: "html",
            success: function(data){
                $('.modal-window-tree').html(data);
                $('#terms-of-service').modal().open();
                $('#multi-derevo li:has("ul")').find('a:first').prepend('<em class="marker"></em>');
                $('#select-id').on('click',function(){
                    var x = {
                        'id' : $('#multi-derevo').find('a.current').parent().parent().attr('id')

                    };
                    JsonData['Place']=x;
                })

            }
        })
    })


    $('.add_rubricator').click(function(){
        var input_text = $(this).prev();
        var name = input_text.val();
        if (name != "") {
            $.ajax({
                type: "POST",
                url: "/include/ajax/add_rubricator.php",
                data: "name="+name,
                dataType: "html",
                success: function(data) {
                    if (data > 0) {
                        alert("Рубрикатор успешно создан"+data);
                        location.reload();
                    }
                    else if (data == -1) {
                        alert('Рубрикатор с таким именем уже существует');
                    }
                    else {
                        alert('Ошибка!'+data);
                    }
                }
            })
        }
        else {
            input_text.css('border', '1px solid red');
            setTimeout(function(){
                input_text.css('border', 'none');
            }, 2000);
        }
    })

    //автозаполнение тегов и атрибутов при проверке физического файла
    $(document).on('click', '.auto_check_file', function(){
        var path = $(this).attr('path');
        console.log(path);
        $.ajax({
            type: "POST",
            url: "/include/ajax/auto_check_file.php",
            data: "path="+path,
            dataType: "json",
            success: function (data) {
                //alert(data);
                $("#list-fields").append('<li num-li="null" option-data="myattribute"><label>Дата создания файла[дата]</label><input name="Дата создания файла" itype="3" class="standart-input its-datepicker" type="text" placeholder="" value="'+data['date']+'" /><a>Удалить поле</a></li>');
                $("#list-fields li > a").addClass('delete-li-field').addClass('pointer');

                $("#list-fields").append('<li num-li="null" option-data="myattribute"><label>Владелец файла[текст]</label><input name="Владелец файла" itype="2" class="standart-input" type="text" placeholder="" value="'+data['user']+'"/><a>Удалить поле</a></li>');
                $("#list-fields li > a").addClass('delete-li-field').addClass('pointer');

                if (data['mime'] != 'null') {
                    $("#list-fields").append('<li num-li="null" option-data="myattribute"><label>MIME Type [текст]</label><input name="MIME Type" itype="2" class="standart-input" type="text" placeholder="" value="'+data['mime']+'" /><a>Удалить поле</a></li>');
                    $("#list-fields li > a").addClass('delete-li-field').addClass('pointer');
                }

                $("#list-fields").append('<li num-li="null" option-data="myattribute"><label>Расширение [текст]</label><input name="Расширение" itype="2" class="standart-input"  type="text" placeholder="" value="'+data['type']+'" /><a>Удалить поле</a></li>');
                $("#list-fields li > a").addClass('delete-li-field').addClass('pointer');
            }
        })
    })

	$('a.modal-up').on('click', function(){
		$.ajax({
		type : "POST",
		url : "../_fw/templates/primary_blocks/bigsearch/tree.php",
		data: {
		id : $(this).parent().attr("template"),
		},
		dataType: "html",
		success: function(data){
		$('.modal-window-tree').html(data);
		$('#terms-of-service').modal().open();
		}
		})
	})




})
