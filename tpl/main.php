<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
    "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" type="text/css" href="css\style.css">
        
        <script src="http://code.jquery.com/jquery-1.8.3.js"></script>
        <script src="js\project.js"></script>
        <link href="css\datepicker.css" rel="stylesheet" type="text/css">
        <script src="js\datepicker.js"></script>
        <link href="css\smoke-pure.css" rel="stylesheet" type="text/css">
        <script src="js\smoke-pure.js"></script>
    </head>
    <body>




        <div class="ui-buttons-container">
            <span id="btn_add" class="ui-button open_modal" href="#modal">Добавить</span>
            <span id="btn_edit" class="ui-button">Редактировать</span>
            <span id="btn_delete" class="ui-button">Удалить</span>
        </div>


        <div id="tableContainer" style = "width:90%">    
            <table id="myTable" class="smt" width=100%>
                <tr>
                    <th width="30px"></th>  
                    <th width="30px">№</th>  
                    <th width="120px">Дата обращения</th>  
                    <th width="200px">ФИО заявителя</th>  
                    <th>Описание обращения</th>  
                </tr>
                <?php foreach ($data as $row) { ?>
                    <tr id="row<?php echo $row['id']; ?>">
                        <td><input id="check_<?php echo $row['id']; ?>" type="checkbox"/></td>  
                        <td><?php echo $row['id']; ?></td>  
                        <td><?php echo $row['appeal_date']; ?></td>  
                        <td><?php echo $row['person_name']; ?></td>  
                        <td><?php echo $row['appeal_text']; ?></td>  
                    </tr>
                <?php } ?>
            </table>
        </div>

        <div id="modal" class="modal_div">
            <form method="post" id="ajax_form" action="" >
              <input type="hidden" name="id" id="_id" value="0" />
              <input type="text" name="name" id="_name" placeholder="Имя" /><br>
              <input class="datepicker-here" type="text" id="_date" name="date" placeholder="Дата" /><br>
              <textarea rows="10" cols="45" id="_text" name="text" placeholder="Введите текст обращения"></textarea><br>
              <div>
              <span id="btn_send" class="ui-button">Создать заявку</span>
              <span id="btn_close" class="ui-button">Отменить</span>
              </div>
            </form>
        </div>
        <div class="locker" id="overlay"></div>
        <div class="locker" id="ajaxoverlay"></div>
    </body></html>


