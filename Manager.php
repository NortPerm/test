<?php

class Manager {

    /**
     * Две сервисные функции для конвертации туда-сюда форматов даты для MySQL и для русского человека
     * @param type $date строка с датой
     * @return type
     */
    public static function convertDateFromSQL($date) {
        return date("d.m.Y", strtotime($date));
    }

    public static function convertDateToSQL($date) {
        return date("Y-m-d", strtotime($date));
    }

    public static function getRow($row_id = 0) {
        $rs = new Recordset();
        $rs->setQuery("SELECT * FROM `t_appeal`where id in (" . $row_id . ")");
        $data = $rs->fetch();
        foreach ($data as $k => $row) {
            $data[$k]['appeal_date'] = self::convertDateFromSQL($row['appeal_date']);
        }
        return $data[0];
    }

    public static function getTable() {
        $rs = new Recordset();
        $rs->setQuery("SELECT * FROM `t_appeal`");
        $data = $rs->fetch();
        foreach ($data as $k => $row) {
            $data[$k]['appeal_date'] = self::convertDateFromSQL($row['appeal_date']);
        }
        return $data;
    }

    public static function addRecord() {
        $rs = new Recordset();
        $rs->setQuery("insert into `t_appeal` (`id`, `person_name`, `appeal_date`, `appeal_text`) VALUES (null,'" . Params::getString('name') . "','" . self::convertDateToSQL(Params::getString('date')) . "','" . Params::getString('text') . "')");
        return $rs->execute();
    }

    public static function updateRecord() {

        if (self::getRow(Params::getString('id'))) { //записть есть
            $rs = new Recordset();
            $rs->setQuery("update `t_appeal` set `person_name` = '" . Params::getString('name') . "', `appeal_date` = '" . self::convertDateToSQL(Params::getString('date')) . "', `appeal_text` = '" . Params::getString('text') . "' where id in (" . Params::getString('id') . ")");
            return $rs->execute();
        } else {
            return self::addRecord(); // за время редактирования запись кто-то мог удалить - логика не прописана в таске, допустим создадим новую
        }
    }

    public static function deleteRecord() {
        $rs = new Recordset();
        $rs->setQuery("delete from `t_appeal` where id in (" . Params::getString('id') . ")");
        return $rs->execute();
    }

    public static function go() {
        DbConfig::connect();
        $action = Params::getString('action');
        switch ($action) {
            case 'edit':
                $row = self::getRow(Params::getString('id'));
                echo json_encode(array('id' => $row['id'], 'appeal_date' => $row['appeal_date'], 'person_name' => $row['person_name'], 'appeal_text' => $row['appeal_text']));
                break;

            case 'send':
                if (Params::getString('id')) {
                    self::updateRecord();
                } else {
                    self::addRecord();
                }
                echo Template::process('tpl\table.php', array('data' => self::getTable()));
                break;
            case 'delete':
                self::deleteRecord();
                echo Template::process('tpl\table.php', array('data' => self::getTable()));
                break;

            default :
                echo Template::process('tpl\main.php', array('data' => self::getTable()));
        }
    }

}
