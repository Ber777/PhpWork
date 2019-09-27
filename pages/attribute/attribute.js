$(document).ready(function()
{
    $('#form-add-attribute').submit(function(e){
        var name = $('#name-attribute').val();
        var i_type = $('#select-type').val();
        if (name != '') {
            $.ajax({
                type: "POST",
                url: "/attribute/ajaxAddAttribute/",
                data: "name="+name+"&type="+i_type,
                success: function(data)
                { 
                    if (data > 0) {
                        alert("Вы добавили новый новый атрибут");
                        location.reload();
                    } else if (data == '-1') {
                        alert("Атрибут с таким именем уже существует");
                    }
                }
            });
        } else {
            $("#name-attribute").css('border', '1px solid red');
        }
        e.preventDefault();
    });

    $('.edit-something').click(function() {
        var id = $(this).attr('idd');
        var popup = ShowPopUp();
        popup.load(
            "/attribute/ajaxBlockEditAttribute/",
            {
                id: id
            }
        )
    });

    $(document).on('submit', '#form-edit-attribute', function(e){
        e.preventDefault();
        var id = $('#id-attribute').val();
        var type = $('#type-attribute').val();
        var name = $('#name-attribute').val();

        $.ajax({
            type: "POST",
            url: "/attribute/ajaxEditAttribute/" + id,
            data: "name=" + name + "&type=" + type,
            cache: false,
            success: function(data) {
                if (data > 0) {
                    alert('Атрибут успешно изменен');
                    location.reload();
                } else if (data == '-1') {
                    alert('Атрибут с таким именем уже существует');
                    //console.log(data);
                    //return false;
                } else {
                    alert('Произошла ошибка при редактировании атрибута');
                }
            }
        });
    });

    $('.search-this-attribute').click(function() {
        var id_attr = $(this).attr('id_attr');
        $('input#list-attrs').val(id_attr);
        $('#hide_form_search').submit();
    });


});