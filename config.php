<?php

class DbConfig
{
    
    public static $host = 'localhost'; 
    public static $database = 'test_base'; 
    public static $user = 'root'; 
    public static $pswd = ''; 
    public static $link = false; 

    
    public static function connect() {
       self::$link = mysql_connect(self::$host, self::$user, self::$pswd) or die('Ошибка соединения: ' . mysql_error());
       mysql_select_db(self::$database) or die('Не могу подключиться к базе: '.mysql_error());
    }
    
    
}
