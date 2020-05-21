<?php

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
