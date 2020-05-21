<?php

class Manager {
    
    public static function getTable() {
         $rs = new Recordset();
         $rs->setQuery("SELECT * FROM `t_appeal`");
         return $rs->fetch();
    }
    
    public static function addRecord() {
         $rs = new Recordset();
         $rs->setQuery("insert into `t_appeal` (`id`, `person_name`, `appeal_date`, `appeal_text`) VALUES (null,'".Params::getString('name')."','".Params::getString('date')."','".Params::getString('text')."')");
         return $rs->execute();
    }
    
    
    public static function go() {
         DbConfig::connect();
         $action = Params::getString('action');
         switch ($action)  {
             case 'send':
                 self::addRecord();
                 echo Template::process('tpl\table.php', array('data'=>self::getTable()));
                 break;
             default :
                 echo Template::process('tpl\main.php', array('data'=>self::getTable()));
         }
         

    }
    
}
