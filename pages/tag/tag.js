$(document).ready(function()
{
    //добавление тега в БД
    $('#form_add_tag').submit(function(e){

        var name = $("#name-tag").val();
        if (name != '') {
            $.ajax({
                type: "POST",
                url: "/tag/ajaxAddTag/",
                data: "name="+name,
                success: function(data)
                {
                    if(data > 0) {
                        alert("Вы добавили новый дескриптор");
                        location.reload();
                    } else if (data == -1) {
                        alert("Дескриптор с таким именем уже существует");
                    }
                }
            });
        } else {
            $("#name-tag").css('border', '1px solid red');
        }
        e.preventDefault();
    });

    //изменение название дескриптора
    $('.edit-something').click(function() {
        var clickElement = $(this);
        var id = clickElement.attr('idd');
        var tag = clickElement.parent().parent().next();
        var tag_name = tag.html();
        var first_position = tag_name[0];
        console.log(tag_name);
        clickElement.hide();
        tag.empty();
        tag.append('<input class="standart-input ajax-edit-tag" idd="'+id+'" type="text" value="'+tag_name+'" />' +
                    '<a class="set-new-name click-button"><img src="/images/check.png"></a>' +
                    '<a nametag="'+tag_name+'" class="click-button cancel-edit"><img src="/images/close_red.png"></a>');
        //var search = clickElement.parent().parent().prev();
        //search.hide();

        $('.set-new-name').click(function(){
            var field_input = $(this).prev();
            var newName = field_input.val();
            if (newName != '') {
                $.ajax({
                    type: "POST",
                    url: "/tag/ajaxEditName/" + id,
                    data: "name="+newName+"&first_position=" + first_position,
                    dataType: "html",
                    cache: false,
                    success: function(data) {
                        if (data == 1) {
                            tag.empty();
                            tag.append(newName);
                            $('.edit-something[idd="'+id+'"]').show();
                            $('.search-this-tag[name_tag="'+tag_name+'"]').attr('name_tag', newName);
                            //search.show();
                        } else if (data == 0) {
                            clickElement.parents('.block-list-tags').parent().remove();
                        } else if (data == '-1') {
                            alert('Дескриптор с таким именем уже существует');
                        }
                    }
                })
            } else {
                field_input.css('border', '1px solid red');
                setTimeout(function(){
                    field_input.css('border', 'none');
                }, 2000);
            }
        });

        $("body").on('click', 'a.cancel-edit', function () {
            var name = $(this).attr('nametag');
            $(this).parent().prev().find('.edit-something').show();
            $(this).parent().text(name);
            //search.show();
        });
    });

    //поиск дескриптора
    $('.search-this-tag').click(function() {
        var name = $(this).attr('name_tag');
        $('input#input-tag').val(name);
        $('#hide_form_search').submit();
    });


});