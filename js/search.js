$(document).ready(function () {

    /********************************* ФИЧА С ПОИСКОМ ************************************/
    $('#input-tags-for-search').keyup(function (e) {
        var nameTag = $(this).val();
        var lengthVal = nameTag.length;
        if (lengthVal != 0) {
            $(this).next().css('display', 'inline-block').addClass('more-tags-srch').addClass('click-button');
            $.ajax({
                type: "POST",
                url: "/tag/searchTagsByName",
                data: "name=" + nameTag,
                dataType: "json",
                success: function (data) {
                    $('#list-result-search-tags').empty();
                    if (data.length > 0) {
                        $('#list-result-search-tags').append('<p>Возможно, вы хотели набрать:</p>');
                        for (var i = 0; i < 5; i++) {
                            $('#list-result-search-tags').css('display', 'block');
                            $('#list-result-search-tags').append('<li><div class="block-list-tags">' + data[i].name + '</div></li>');
                        }
                    }
                    else {
                        $('#list-result-search-tags').css('display', 'none');
                    }
                }
            })
        }
        else {
            $(this).next().hide();
            $("#list-result-search-tags").css('display', 'none');
        }
    })

    //добавление нового тега при поиске и занесение в инпут
    $('body').on('click', '.more-tags-srch', function () {
        var valueResult = $("#result-list-tags").val();
        var valTag = $('#input-tags-for-search').val();
        if (valTag != '') {
            $('#list-search-tags').css('display', 'inline-block').append('<li class="block-list-tags">' + valTag + '<a class="delete-li-field delete-from-search pointer">×</a></li>');
            $('#input-tags-for-search').val('');
            //$(this).hide();
            $("#list-result-search-tags").css('display', 'none');
            $("#result-list-tags").val(valueResult + valTag + '/');
        }
    })

    // удалить из подобранных и изменить в конечном инпуте
    $('body').on('click', '.delete-from-search', function () {
        var value_input = $("#result-list-tags").val();
        var tag = $(this).parent().text().replace('×', '');
        $(this).parent().remove();
        $("#result-list-tags").val(value_input.replace(tag + '/', ''));
        var count = $('#list-search-tags > li').size();
        if (count == 0) $('#list-search-tags').hide();
    });


    //выбор тегов из выпадающего списка совпадений
    $("body").on('click', '#list-result-search-tags > li > div', function () {
        var text = $(this).html();
        $('#input-tags-for-search').val(text);
        //$(this).parent().parent().hide();

    });

    /*********************************************************************/
});