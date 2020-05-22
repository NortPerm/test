<?php

/**
 * Класс для подклюяения к БД
 */
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

/**
 * Класс для максимально безопасного получения параметров запроса
 */
class Params
{
    public static function getString($param) {
       $input = strip_tags($_REQUEST[$param]);
       $input = htmlspecialchars($input);
       return mysql_escape_string($input);
    }
    
    public static function getNumber($param) {
      return intval(self::getString($param));
    }
}

/**
 * Простой шаблон, чтобы развести логику и верстку
 */
class Template 
{
    public static function process($file, $args) {
    if ( !file_exists( $file ) ) {
        return '';
    }
    if ( is_array( $args ) ){
     extract( $args );
    }
    ob_start();
    include $file;
    return ob_get_clean();
    }
    
}
