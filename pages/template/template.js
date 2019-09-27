$(document).ready(function() {
    /* равномерная высота для списка шаблонов */
    $(function() {
        $('.list-templates').each(function() {
            var maxHeight = 0;
            $(this).find('.block-list-items').each(function() {
                if ($(this).height() > maxHeight) {
                    maxHeight = $(this).height();
                }
            });
            $(this).find('.block-list-items').height(maxHeight);
        });
    });

    $('#form_add_rubricator').submit(function(e){
        var name = $('#name_rubricator').val();
        if (name != "") {
            $.ajax({
                type: "POST",
                url: "/template/ajaxAddTemplateCatalog/",
                data: "name="+name,
                success: function(data) {
                    if (data > 0) {
                        alert("Рубрикатор успешно создан");
                        location.reload();
                    }
                    else if (data == -1) {
                        alert('Рубрикатор с таким именем уже существует');
                    }
                    else {
                        alert('Ошибка!'+ data);
                    }
                }
            });
        }
        else {
            $('#name_rubricator').css('border', '1px solid red');
            setTimeout(function(){
                $('#name_rubricator').css('border', 'none');
            }, 2000);
        }
        e.preventDefault();
    });

    //поиск шаблонов по имени
    $('#form-search-template').submit(function(e) {
        var id_type_template = $('#id-type-template').val();
        var name = $('#input-name-template').val();
        $.ajax({
            type: "POST",
            url: "/template/ajaxSearchTemplateByName/",
            data: "name=" + name + "&id_type=" + id_type_template,
            success: function(data) {
                $('#search').append('<div class="result-search-template" style="display: none" result="'+name+'">'+ data +'</div>');
                $('.result-search-template[result="'+name+'"]').css('border-top', '1px solid black');
                $('.result-search-template[result="'+name+'"]').css('border-right', '1px solid black');
                $('.result-search-template[result="'+name+'"]').css('border-left', '1px solid black');
                $('.result-search-template[result="'+name+'"]').slideDown(300);
            }
        });
        e.preventDefault();
    });

    //скртыть результат поиска шаблонов по имени
    $(document).on('click', '.close-result-search-template', function(){
        $(this).parent().slideUp(300, function () {
            $(this).remove();
        });

    });

    //копирование шаблонов
    //копирование шаблона к себе
    $('.copy-this-template').click(function () {
        var id_template = $(this).attr('id_template');
        var id_type = $(this).attr('id_type');
        $.ajax({
            type: "POST",
            data: "id="+id_template+"&id_type="+id_type,
            dataType: "html",
            url: "/template/ajaxCopy/",
            success: function (data) {
                if (data > 0) {
                    alert('Успешно добавлено к вашим шаблонам');
                    location.reload();
                }
                else if (data == -1) {
                    alert('Такой шаблон уже существует')
                }
                else {
                    alert('Ошибка: '+data);
                }
            }
        })
    });
    
});
