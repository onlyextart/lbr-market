var editUser = {
    initCalendar : function() {
        $.datepicker.regional['ru'] = {
            closeText: 'Закрыть',
            prevText: '&#x3c;Пред',
            nextText: 'След&#x3e;',
            currentText: 'Сегодня',
            monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
            monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн','Июл','Авг','Сен','Окт','Ноя','Дек'],
            dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
            dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
            dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
            dateFormat: 'dd.mm.yy',
            firstDay: 1,
            isRTL: false,
        };
        $.datepicker.setDefaults($.datepicker.regional['ru']);
	
        $( "#User_block_date" ).datetimepicker({
            dateFormat: 'dd-mm-yy',
            timeFormat: 'HH:mm',
        });
    },
    editStatus : function() {
        $('#User_status').change(function() {
            var status = $(this).val();
            if(status == editUser.data.userActive||status == editUser.data.userNotConfirmed||status == editUser.data.userNotActivated) {
                $('#User_block_reason').parent().addClass('hide');
                $('#User_block_date').parent().addClass('hide');
            } else {
                $('#User_block_reason').parent().removeClass('hide');
                if(status != editUser.data.userWarning&&status != editUser.data.userBlocked) $('#User_block_date').parent().removeClass('hide');
                else $('#User_block_date').parent().addClass('hide');
            }
        });
    }
}
    