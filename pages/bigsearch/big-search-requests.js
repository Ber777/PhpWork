
//Запрос на поиск
function sendQuery() {

    if (!JsonDataIsEmpty()) {
        $('#json-template').val(JSON.stringify(JsonData));
        $.ajax({
            type: "POST", //метод запроса, POST или GET (если опустить, то по умолчанию GET)
            url: "/bigsearch/ajaxShowResults", //серверный скрипт принимающий запрос

            data: {
                request: JSON.stringify(JsonData)
            }, //можно передать переменную с json в одном из параметре запроса
            //data: {request:["message #A", "message #B"],request2:"message2"},  //можно передать массив в одном из параметре запроса
            beforeSend: function() {
                //alert("Выполнение начато") ;
                disableItems();
                showOverlay();
                $(".loader").show();
            },
            complete: function() {
                //alert("Выполнение закончено") ;
                hideOverlay();
                enableItems();
                $(".loader").hide();
            },
            success: function(data) { //функция выполняется при удачном заверщение
                $("#results").html(data);
                //alert("Запрос выполнен") ;
                //alert(data) ;

            }
        });
    } else {
        alert("Задайте условия для поиска");
    }

}

// Cохранение шаблонов со страницы расширенного поиска
function saveTemplate(e) {
    e.preventDefault();

    var ajax_url = $(this).data('ajax');
    var name = $('#name-template').val();
    var id = $('#id-template').val();
    //var json_data = saveAsTemplate();

    var json_data = JSON.stringify(JsonData);

    if (name == '') {
        alert("Пустое имя шаблона!");
        $('#name-template').css('border-color', 'red');
    } else {
        $('#name-template').css('border-color', 'none');
        if (json_data == '') {
            alert("Пустая строка запроса!");
        } else {
            console.log(name, id, ajax_url);
            $.ajax({
                type: "POST",
                data: "name="+name+"&id="+id+"&sql="+json_data,
                url: ajax_url+id,
                success: function (data) {
                    if (data > 0) {
                        alert('Шаблон успешн сохранен');
                    }
                    else if (data == -1) {
                        alert('Шаблон с таким именем уже сохранен');
                    }
                    else {
                        console.log('Ошибка' + data);
                    }
                }
            })
        }
    }


}
