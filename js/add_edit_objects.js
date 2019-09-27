$(document).ready(function() {

    //работа с именем шаблона
    $("#saveTempl").change(function(){
        if ($("#saveTempl").prop("checked")) {
            $("#nameTempl").prop('disabled', false);
            $("#nameTempl").prop('required', true);
            $("#nameTempl").css('background', '#fcfcfc');
        }
        else {
            $("#nameTempl").prop('disabled', true);
            $("#nameTempl").prop('required', false);
            $("#nameTempl").css('background', '#eaeaea');
        }
    })

    //функция проверки уже имеющегося атрибута
    function CheckRepeatAttribute(name, type) {
        var error;
        var selects = $('#sel-attr');
        $(selects).children('option').each(function (i, element) {
            if ( (name.toLowerCase() == $(element).text().toLowerCase()) && (type == $(element).data('type')) ) {
                $(element).attr('selected', 'selected');
                alert('Данный атрибут уже хранится в базе данных');
                selects.stop().animate({
                    scrollTop: $(element).offset().top - selects.offset().top + selects.scrollTop()
                });
                error = 1;
            }
        });

        var result_selects = $('#result-attrs');
        $(result_selects).children('option').each(function (i, element) {
            if ( (name.toLowerCase() == $(element).text().toLowerCase()) && (type == $(element).data('type')) ) {
                $(element).attr('selected', 'selected');
                alert('Данный атрибут уже выбран');
                result_selects.stop().animate({
                    scrollTop: $(element).offset().top - result_selects.offset().top + result_selects.scrollTop()
                });
                error = 1;
            }
        });

        if (error) {
            return false;
        } else {
            return true;
        }
    }

    //функция проверки уже имеющегося тега
    function CheckRepeatTags (name)
    {
        var error;
        var selects = $('#sel-tag');
        $(selects).children('option').each(function (i, element) {
            if (name.toLowerCase() == $(element).text().toLowerCase()) {
                $(element).attr('selected', 'selected');
                alert('Данный дескриптор уже хранится в базе данных');
                selects.stop().animate({
                    scrollTop: $(element).offset().top - selects.offset().top + selects.scrollTop()
                });
                error = 1;
            }
        });

        var result_selects = $('#result-tags');
        $(result_selects).children('option').each(function (i, element) {
            if (name.toLowerCase() == $(element).text().toLowerCase()) {
                $(element).attr('selected', 'selected');
                alert('Данный дескриптор уже выбран');
                result_selects.stop().animate({
                    scrollTop: $(element).offset().top - result_selects.offset().top + result_selects.scrollTop()
                });
                error = 1;
            }
        });

        if (error) {
            return false;
        } else {
            return true;
        }
    }

    //функция отделения добавленных тегов/аттрибутов от уже имеющихся
    function CheckAttributesAndTegsFromUser ()
    {
        var a = $("#sel2 option[option-type='myattribute']");
        var b = $("#sel2 option[option-type='mytag']");
        var c = $("#result-attrs option[option-type='myattribute']");
        var d = $("#result-tags option[option-type='mytag']");
        b.css('color', 'red');
        a.css('color', 'red');
        c.css('color', 'red');
        d.css('color', 'red');
    }

    //функция добавления картинги "тег"
    function AddImageTag () {
        var a = $("#sel2 option[option-type='mytag']");
        var b = $("#sel2 option[option-type='tag']");
        a.addClass("mini-img-for-option");
        b.addClass("mini-img-for-option");
    }

    //функция скрытия списка шагов
    $(".title-step > span.click-button").click(function(){
        var a = $(this).parent();
        var action = a.attr("action");
        if (action == "hide")
        {
            a.children("span").text("показать");
            a.next().slideUp(300);
            a.attr("action", "show");
        }
        else
        {
            a.children("span").text("скрыть");
            a.next().slideDown(300);
            a.attr("action", "hide");
        }
    })



    //возврат полей обратно в селекты
    $("body").on('click','.delete-li-field', function () {
        var option_data = $(this).parent().attr('option-data');
        var num_li = $(this).parent().attr('num-li');
        if (option_data == 'attribute')
        {
            $(this).text('');
            var type_data = $(this).prev().attr('type');
            var itype = $(this).prev().attr('itype');
            var text = $(this).parent().text();

            var idattr = $(this).parent().attr("num-li");

            $('#sel-attr').append('<option option-type="attribute" data-type="'+type_data+'" idattr="'+idattr+'" selected>'+text.replace(/\[(.*?)\]/, '')+'</option>');
            $(this).parent().remove();
        }
        else if (option_data == 'tag')
        {
            $(this).text('');
            var text = $(this).parent().text();
            var idtag = $(this).parent().attr("idtag");
            $('#sel-tag').append('<option option-type="tag" idtag="'+idtag+'" selected>'+text+'</option>');
            $(this).parent().remove();
            if ($('.block-list-tags').length == 0) {
                $('#list-tegs > p').html('Отсутствуют');
            }
        }
        else if (option_data == 'mytag' || option_data == 'myattribute')
        {
            $(this).parent().remove();
        }
    })

    //перемещение из колонки в колонки
    $("body").on('click','.move', function(){
        var i = $(this).attr("in");
        var o = $(this).attr("out");
        if (i == "result-tags" && o == "sel-tag")
        {
            $("#sel-tag option:selected").clone().appendTo("#sel2");
            $("#sel-tag option:selected").appendTo("#result-tags");
            AddImageTag();
        }
        else if (i == "sel-tag" && o == "result-tags")
        {
            $("#"+o+" option[option-type='tag']:selected").each(function (){
                var textSetTag = this.text;
                $("#sel2 option").each(function (){
                    if (textSetTag == this.text) this.remove();
                });
            })
            $('#sel-tag').append($('#result-tags option[option-type="tag"]:selected'));
        }
        else if (i == "result-attrs" && o == "sel-attr")
        {
            $("#sel-attr option:selected").clone().appendTo("#sel2");
            $("#sel-attr option:selected").appendTo("#result-attrs");
        }
        else if (i == "sel-attr" && o == "result-attrs")
        {
            $("#"+o+" option[option-type='attribute']:selected").each(function (){
                var textSetTag = this.text;
                $("#sel2 option").each(function (){
                    if (textSetTag == this.text) this.remove();
                });
            })
            $('#sel-attr').append($('#result-attrs option[option-type="attribute"]:selected'));
        }
    })

    //добпаление своих тегов и аттрибутов
    $(".button-add-in-list-result").click(function () {
        if ($(this).attr('id') == "button-add-in-list-sel2-attribute")
        {
            var name = $("#val-attr").val();
            var type = $("#type-attr").val();
            if ((name != "") && (type != "none"))
            {
                if (CheckRepeatAttribute(name, type)) {
                    $("#result-attrs").append($('<option option-type="myattribute" data-type="'+type+'" >'+name+'</option>'));
                    $("#sel2").append($('<option option-type="myattribute" data-type="'+type+'" >'+name+'</option>'));
                    $("input#val-attr").val('');
                    CheckAttributesAndTegsFromUser();
                }
            }
            else alert('Вы забыли ввести имя аттрибута или тип!!!');

        }
        else
        {
            var name = $("input#value-tag").val();
            if (name != "")
            {
                var checked = CheckRepeatTags(name);
                console.log(checked);
                if (checked == false) {

                }
                else
                {
                    $("#result-tags").append($('<option option-type="mytag">'+name+'</option>'));
                    $("#sel2").append($('<option option-type="mytag">'+name+'</option>'));
                    $("input#value-tag").val('');
                    CheckAttributesAndTegsFromUser();
                    AddImageTag();
                }
            }
            else alert('Вы не ввели тег!');
        }
    });

    //удаление своих аттрибутов и тегов
    $(".delete-from-result").click(function () {
        $('#'+$(this).data('from')+' option:selected').each(function(){
            attribute1 = $(this).attr("option-type");
            if (attribute1 == 'mytag' || attribute1 == 'myattribute')
            {
                text1 = $(this).text();
                $(this).remove();
                $("#sel2 option").each(function(){
                    if (text1 == $(this).text() && (attribute1 == 'mytag' || attribute1 == 'myattribute')) $(this).remove();
                })
            }
            //else message = "Удалить возможно только созданные вами элементы!";

        })
        //alert(message);
    })


    //отменение выделения при выборе из другой колонки
    $("#sel-tag").change(function() {
        $('#result-tags option:selected').each(function() {
            this.selected = false;
        });
    })
    $("#sel-attr").change(function() {
        $('#result-attrs option:selected').each(function() {
            this.selected = false;
        });
    })



    //кнопка подтверждения аттрибутов и тегов при создании файла
    $("#confirm-attr-tegs").click(function (){
        var optionsType = new Array;
        var optionsData = new Array;
        var optionsText = new Array;
        var optionsArrIdTags = new Array;
        var optionsAttIdAttr = new Array;
        IdSelect = document.getElementById("sel2");
        for (var i = 0; i < IdSelect.options.length; i++)
        {
            if (!(IdSelect.options[i].selected)) IdSelect.options[i].selected = true;
            optionsArrIdTags[i] = IdSelect.options[i].getAttribute('idtag');
            optionsAttIdAttr[i] = IdSelect.options[i].getAttribute('idattr');
            optionsType[i] = IdSelect.options[i].getAttribute('data-type');
            optionsData[i] = IdSelect.options[i].getAttribute('option-type');
            optionsText[i] = IdSelect.options[i].text;
            if (optionsData[i] == 'tag' || optionsData[i] == 'mytag')
            {
                $("#list-tegs").append('<li class="block-list-tags" idtag="'+optionsArrIdTags[i]+'" option-data="'+optionsData[i]+'">'+optionsText[i]+'<a>&#215;</a></li>');
                $("#list-tegs li > a").addClass('delete-li-field').addClass('pointer');
            }
            if (optionsData[i] == 'attribute' || optionsData[i] == 'myattribute')
            {
                if ( optionsType[i] == 'date')
                {
                    var idType = 3;
                    var rusType = 'дата';
                    $("#list-fields").append('<li num-li="'+optionsAttIdAttr[i]+'" option-data="'+optionsData[i]+'"><label>'+optionsText[i]+' ['+rusType+']</label><input name="'+optionsText[i]+'" itype="'+idType+'" class="standart-input its-datepicker" type="text" placeholder="введите '+optionsText[i]+'" /><a>Удалить поле</a></li>');
                    $("#list-fields li > a").addClass('delete-li-field').addClass('pointer');
                }
                else
                {
                    if (optionsType[i] == 'text') {
                        idType = 2;
                        rusType = 'текст';
                    }
                    if (optionsType[i] == 'number') {
                        idType = 1;
                        rusType = 'число';
                    }
                    $("#list-fields").append('<li num-li="'+optionsAttIdAttr[i]+'" option-data="'+optionsData[i]+'"><label>'+optionsText[i]+' ['+rusType+']</label><input name="'+optionsText[i]+'" itype="'+idType+'" class="standart-input" type="'+optionsType[i]+'" placeholder="введите '+optionsText[i]+'" /><a>Удалить поле</a></li>');
                    $("#list-fields li > a").addClass('delete-li-field').addClass('pointer');
                }
            }

        }
        $('html, body').animate({scrollTop: 1500},500);
        $('#list-tegs > p').empty();
        IdSelectTag = document.getElementById("result-tags");
        IdSelectAttr = document.getElementById("result-attrs");
        IdSelect.options.length = 0;
        IdSelectTag.options.length = 0;
        IdSelectAttr.options.length = 0;
    });

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * A J A X * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  */

    $('#main-form').submit(function () {
        var ajax_url = $(this).attr('ajax');
        var id = $(this).attr('id_object');
        var form_data = CreateDataForm('main-form');

        console.log(form_data);
        $.ajax({
            url: ajax_url+id,
            type: 'POST',
            data: form_data,
            processData: false,
            contentType: false,
            success: function(data) {
                if (data == 1) {
                    alert('Изменения были успешно сохранены');
                    var name_file = $('#doc').val().replace(/.+[\\\/]/, "");
                    if (name_file != '') {
                        var element_old_file = $('a.name-file');
                        var old_href = element_old_file.attr('href');
                        element_old_file.attr('href', old_href.replace(element_old_file.text(), id + '_' + name_file));
                        element_old_file.empty().text(id + '_' + name_file);
                    }
                }
                else {
                    $('#main-form p.block-error').empty();
                    $('#main-form p.block-error').append(data).slideDown();
                }
            }
        });
        return false;
    });

    function CreateDataForm(id_Form) {
        var form_data = new FormData();  // создание объекта для передачи контроллеру
        // чекбокс сохранения шаблона
        if ($("#saveTempl").prop("checked")) {
            var nameTempl = $("#nameTempl").val();
            form_data.append('template', nameTempl);
        }

        //получение файла
        //console.log($('#'+id_Form).find('#doc'));
        var is_set_file = $('#'+id_Form).find('#doc').length;
        if (is_set_file) {
            var name_file = $('#doc').val().replace(/.+[\\\/]/, "");
            if (name_file != '') {
                form_data.append('doc_file', $("#doc").prop('files')[0]);
                if ($('#doc').attr('delete') != '') {
                    form_data.append('delete_file', $('#doc').attr('delete'));
                }
            }
        }


        // получение всех полей атрибутов
        var form_data_serialize = $('#'+id_Form+'').serializeArray();
        form_data_serialize.forEach(function(item, i, form_data_serialize) {
            var type_integer = $('input[name="'+form_data_serialize[i].name+'"]').attr('itype');
            form_data_serialize[i].type = type_integer;
            form_data.append('attributes[]', JSON.stringify(form_data_serialize[i]));
        });

        //получение списка тегов
        var form_data_tags = new Array;
        $('ul#list-tegs li.block-list-tags').each(function (){
            var element = $(this).text();
            form_data_tags.push(element.slice(0, -1));
        });
        form_data.append('tags', JSON.stringify(form_data_tags));
        return form_data;
    }


});
