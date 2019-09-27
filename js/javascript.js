$(document).ready(function() {

    // вернуться назад на 1 страницу
    $('.btn-comeback').click(function(){
        console.log($(this).attr('href'));
        if (($(this).attr('href')) == '') {
            history.go(-1);
        }
    });

    // датапикер, формат даты
    $('body').on('focus', '.its-datepicker', function () {
        $('.its-datepicker').datepicker({
            dateFormat: 'yy-mm-dd',
            dayNames: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда','Четверг','Пятница','Суббота'],
            dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
            prevText: 'пред.',
            nextText: 'след.',
            monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            monthNamesShort: ['Янв', 'Фев', 'Март', 'Апр', 'Май', 'Июнь', 'Июль', 'Авг', 'Сент', 'Окт', 'Ноя', 'Дек']
        });
    });

    // кнопка отмены popup-а
    $(document).on('click', '.cancel-popup', function (){
        $('.b-popup-content').removeClass().empty();
        $('.b-popup').hide().children('div').addClass('b-popup-content');
    });

    /* равномерная высота для списка шаблонов */
    $(function() {
        $('.list-templates').each(function() {
            var maxHeight = 0;
            $(this).children('li').each(function() {
                if ($(this).height() > maxHeight) {
                    maxHeight = $(this).height();
                }
            });
            $(this).children('li').children('a').height(maxHeight);
        });
    });

});
