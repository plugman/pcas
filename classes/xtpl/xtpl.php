<?php


/* in cubecart v 3 this defines does not present */
if(!defined("CC_ROOT_DIR")) define("CC_ROOT_DIR", $glob['rootDir']);
if(!defined("CC_DS")) {
    define("CC_DS", DIRECTORY_SEPARATOR);
    define("CC_MTV", 3);
} else {
    define("CC_MTV", 4);        
}

// load original class
require_once(dirname(__FILE__) . '/xtpl_original.php');

class XTemplate extends XTemplate_original {

    function XTemplate() {
        // cubecart 3
        //   $file,  $tpldir = '', $files = null, $mainblock = 'main', $autosetup = true
        // cubecart 4
        //$options,  $tpldir = '', $files = null, $mainblock = 'main', $autosetup = true, $skipPath=FALSE

        $args = func_get_args();
        $defaults = array('', null, 'main', true, false);
        foreach($defaults as $key => $value) {
            if(CC_MTV == 3 && $key == 4) {
                continue;
            }
            if(!isset($args[$key + 1])) {
                $args[$key + 1] = $value;
            }
        }


        // init original construct function
        if(CC_MTV == 3) {
            @parent::XTemplate($args[0], $args[1], $args[2], $args[3], $args[4]);
        } else {
            // commented line should be used for same cc 4.x versions
            //@parent::XTemplate($args[0], $args[1], $args[2], $args[3], $args[4], $args[5]);
            @parent::__construct($args[0], $args[1], $args[2], $args[3], $args[4], $args[5]);
        }
    }

    function text ($bname = '') {
        // we need only $glob variable into this function..
        // BUT we need all this variables into included file (magiczoomplus.php)
        global $glob, $config, $db, $lang;
       
        // load original data
        $text = parent::text($bname);
        
        // parse text in our module function
        require_once(CC_ROOT_DIR . '/modules/magictoolbox/core/magiczoomplus.php');
        $text = MagicZoomPlus($text, $bname);

        // return =)
        return $text;
    }

}

?>
