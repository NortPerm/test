$(document).ready(function () { // зaпускaем скрипт пoсле зaгрузки всех элементoв
    MyManager.afterLoad();
});


function Manager_F() {

    this.map = {
        id: 'id',
        appeal_date: 'date',
        person_name: 'name',
        appeal_text: 'text'
    };

    this.sendAjax = function (tar, action, data, callback) {
        data.action = action;
        MyManager.lockScreen();
        $.ajax({
            url: "index.php", //url страницы (action_ajax_form.php)
            type: "POST", //метод отправки
            dataType: "html", //формат данных
            data: data,
            success: function (html) { //Данные отправлены успешно
                if (tar) {
                    $('#' + tar).html(html);
                }
                MyManager.unlockScreen();
                if (callback) {
                    callback(html);
                }
            },
            error: function (response) { // Данные не отправлены
                alert('Ошибка. Данные не отправлены.');
                MyManager.unlockScreen();
            }
        });
    }


    /***
     * Проверка полей перед отправкой, дата проверяется примитивно и в лоб, полноценный валидатор строго говоря и не требуется
     * При попытке выставить 31 февраля сервер приводит дату к "адеквату"
     * @returns {Boolean}
     */
    this.checkFields = function () {
        var err = '';
        if (!$('#_name').val().trim()) {
            err += 'Заполните поле имя<br/>'
        }
        var regex = new RegExp("^([0-9]{2})\\.([0-9]{2})\\.([1-2][0-9]{3})$");
        if (regex.test($('#_date').val().trim()) === false) {
            err += 'Выберите корректную дату<br/>'
        }
        if (!$('#_text').val().trim()) {
            err += 'Заполните обращение<br/>'
        }
        if (err) {
            smoke.alert(err);
        }
        return err ? false : true;
    }

    /**
     * Функция добавления и апдейта
     * @returns {undefined}
     */
    this.sendAppeal = function () {
        if (this.checkFields()) {
            this.sendAjax('tableContainer', 'send', {id: $('#_id').val().trim(), name: $('#_name').val().trim(), text: $('#_text').val().trim(), date: $('#_date').val().trim()}, function(param) { return MyManager.afterAjaxTableLoad()});
            this.closeModal('#modal');
            this.lockScreen();
        }
    }

/**
 * Считаем сколько строк выделено и на основе этого играемся с доступностью кнопок
 * @returns {undefined}
 */
    this.calcChecked = function () {
        this.checked = [];
        $('input:checkbox:checked').each(function () {
            MyManager.checked.push(this.id.split('_')[1]);
        });
        var count = this.checked.length;
        if (count>0) {
          $('#btn_delete').removeClass('disabled');  
        } else {
          $('#btn_delete').addClass('disabled');    
        }
        if (count==1) {
          $('#btn_edit').removeClass('disabled');  
        } else {
          $('#btn_edit').addClass('disabled');    
        }
        
    }

    /**
     * Проверки больше для успокоения совести
     * @returns {undefined}
     */
    this.deleteAppeal = function () {
        this.calcChecked();
        if (this.checked.length == 0) {
            smoke.alert("Выберите записи для удаления!")
        } else {

            smoke.confirm("Выбранные записи будут безвозвратно удалены. Вы уверены?", function (result) {
                if (result === false)
                    return;

                MyManager.sendAjax('tableContainer', 'delete', {id: MyManager.checked.join()}, function(param) { return MyManager.afterAjaxTableLoad()});
            })
        }

    }

    this.update = function (response) {
        var jsonData = JSON.parse(response);
        console.log(jsonData);
        this.applyFields(jsonData);
        $('#btn_send').html('Обновить заявку');
        this.openModal('#modal');
    }

    /**
     * заполняем окошко теми данными что вернулись с сервера
     * @param {type} json
     * @returns {undefined}
     */
    this.applyFields = function (json) {
        for (var i in json) {
            if ($('#_' + this.map[i]).length) {
                $('#_' + this.map[i]).val(json[i]);
            }
        }
    }

    this.editAppeal = function () {
        this.calcChecked();
        if (this.checked.length == 0) {
            smoke.alert("Выберите запись для редактирования!")
        } else
        if (this.checked.length > 1) {
            smoke.alert("Для редактирования выберите одну запись!")
        } else {
            this.sendAjax(null, 'edit', {id: MyManager.checked.join()}, function (response) {
                return MyManager.update(response);
            });

        }
    }



    this.lockScreen = function () {
        $('#ajaxoverlay').fadeIn(400);
    }

    this.unlockScreen = function () {
        $('#ajaxoverlay').fadeOut(400);
    }

    this.openModal = function (tar) { //#modal
        $('#overlay').fadeIn(400,
                function () {
                    $(tar).css('display', 'block')
                            .animate({opacity: 1, top: '50%'}, 200);
                });
    }

    this.closeModal = function (tar) {
        $(tar).animate({opacity: 0, top: '45%'}, 200,
                function () {
                    $(this).css('display', 'none');
                    $('#overlay').fadeOut(400);
                }
        );
    }
    
    /**
     * После перестройки таблицы переиничиваем события на чекбоксы 
     * @returns {undefined}
     */
    this.afterAjaxTableLoad = function() {
        $('input:checkbox').click(function (event) {
            MyManager.calcChecked();
        })
        MyManager.calcChecked();
    }

    /**
     * Провешиваем клики на нужные на элементы- выполняется после загрузки
     * @returns {undefined}
     */
    this.afterLoad = function () {
        MyManager.calcChecked(); 

        $('#btn_add').click(function (event) {
            if (!$(this).hasClass('disabled')) {
                var div = $(this).attr('href');
                $('#_id').val(0); //чтобы повторное открытие было нормальныи - здесь можно и остальные поля чистить
                $('#btn_send').html('Coздать заявку');
                MyManager.openModal(div);
            }
        });

        $('#btn_close').click(function () {
            if (!$(this).hasClass('disabled')) {
                MyManager.closeModal('#modal');
            }
        });

        $('#btn_edit').click(function (event) {
            if (!$(this).hasClass('disabled')) {
                MyManager.editAppeal();
            }
        })

        $('#btn_delete').click(function (event) {
            if (!$(this).hasClass('disabled')) {
                MyManager.deleteAppeal();
            }
        })

        $('#btn_send').click(function (event) {
            if (!$(this).hasClass('disabled')) {
                MyManager.sendAppeal();
            }
        })

        $('input:checkbox').click(function (event) {
            MyManager.calcChecked();
        })
        

    }
}

var MyManager = new Manager_F();