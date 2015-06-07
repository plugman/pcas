<?php

if(!in_array('MagicToolboxMakeThumb', get_declared_classes())) {

    class MagicToolboxMakeThumb {
        var $img;
        var $w;
        var $h;
        var $max;
        var $thumb;
        var $type;
        var $info;
        var $data;
        var $imagick;
        var $imagickPath;
        var $imagickObj;
    
        function MagicToolboxMakeThumb($img = null, $w = -1, $h = -1, $thumb = null, $max = 'both', $imagickPath = null) {
            if($img == null || $this->file_exists($img) && !is_file($img)) return false;
            
            if($w < 0 && $h < 0) return $img;
            
            clearstatcache();
            if($thumb && $thumb !== null && $this->file_exists($thumb)) unlink($thumb);
            
            $this->img = $img;
            $this->w = $w;
            $this->h = $h;
            $this->max = $max;
            $this->thumb = $thumb;
            
            if($imagickPath !== null && !empty($imagickPath)) {
                $this->imagickPath = $imagickPath;
            } else {
                $this->imagickPath = null;
            }
            
            $this->checkImageMagick();
            if($this->imagick == 1) {
                // we should also check does we can run imagick bin file
                @exec(escapeshellarg($this->imagickPath) . ' logo: /tmp/logo.png', $ret, $exitCode);
                if($exitCode > 0) {
                    // got error, disable imagick
                    $this->imagick = 0;
                }
            }

            // check imagick version (for some reason, resize option dosn't working in imagick 5.x)
            if($this->imagick == 1) {
                @exec(escapeshellarg($this->imagickPath) . ' --version', $ret);
                foreach($ret as $line) {
                    if(preg_match('/version:/is', $line)) {
                        $v = preg_replace('/^.*?\s((?:[0-9]+\.){2}[0-9]+)(\-\d+)?\s.*$/is', '$1', $line);
                        if(version_compare($v, '6.0.0', '<')) {
                            $this->imagick = 0;
                        }
                        break;
                    }
                }
            }

            $this->getType();
            if(!$this->type) {
                return $img;
            }
            $this->load();
            $this->resize();
            
            if($this->thumb == null) {
                return $this->data;
            }
            
            $this->save();
            
            if(!$this->imagick) {
                imagedestroy($this->data);
            }
            
            clearstatcache();
            
            return $this->thumb;
        }

        function file_exists($f) {
            if(@file_exists($f)) {
                return true;
            } elseif(@exec('ls -l ' . escapeshellarg($f) . ' | grep ' . escapeshellarg($f))) {
                return true;
            } else {
                return false;
            }
        }

        function checkImageMagick() {
            $execDisabled = preg_match('/exec/is', ini_get('disable_functions'));

            // check if imagick path is set up 
            if(!$execDisabled && $this->imagickPath !== null) {
                if(!preg_match('/convert$/s', $this->imagickPath)) {
                    if(!preg_match('/\/$/s', $this->imagickPath)) {
                        $this->imagickPath .= '/';
                    }
                    $this->imagickPath .= 'convert';
                    if(@$this->file_exists($this->imagickPath)) {
                        $this->imagick = 1;
                        return;
                    } else {
                        $this->imagickPath = null;
                    }
                }
            }

            if(in_array('Imagick', get_declared_classes())) {
                // PHP Imagick extension is available
                $this->imagick = 2;
            } elseif(!$execDisabled) {
                if(@$this->file_exists('/usr/bin/convert')) {
                    // found UNIX imagick tools in /usr/bin
                    $this->imagickPath = '/usr/bin/convert';
                }
                if(@$this->file_exists('/usr/local/bin/convert')) {
                    // found UNIX imagick tools in /usr/local/bin
                    $this->imagickPath = '/usr/local/bin/convert';
                }
                if($this->imagickPath !== null) {
                    $this->imagick = 1;
                } else {
                    @exec('compgen -ac', $a);
                    if($a && is_array($a) && in_array('convert', $a) && in_array('identify', $a)) {
                        // UNIX imagick command line tools is available
                        $this->imagickPath = 'convert';
                        $this->imagick = 1;
                    } else {
                        // no imagick
                        $this->imagick = 0;
                    }
                }
            } else {
                $this->imagick = 0;
            }
        }

        function getType() {
            if($this->imagick > 0) {
                $this->type = true;
                return true;
            }
            $this->info = getimagesize($this->img);
            
            /*  1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF, 5 = PSD, 6 = BMP, 7 = TIFF(intel byte order), 8 = TIFF(motorola byte order), 9 = JPC, 10 = JP2, 11 = JPX, 12 = JB2, 13 = SWC, 14 = IFF */
            
            switch($this->info[2]) {
                case 1: $this->type = "gif"; break;
                case 2: $this->type =  "jpg"; break;
                case 3: $this->type =  "png"; break;
                // GD doesn't support BMP format
                //case 6: $this->type =  "bmp"; break;
                default: $this->type =  false;
            }
            
            return $this->type;
        }

        function load() {
            if($this->imagick == 2) {
                $this->imagickObj = new Imagick($this->img);
                return true;
            } elseif($this->imagick) {
                return true;
            }
            switch($this->type) {
                case "gif":
                    // unfortunately this function does not work on windows
                    // via the precompiled php installation :(
                    // it should work on all other systems however.
                    if( function_exists("imagecreatefromgif") ) {
                        $this->data = imagecreatefromgif($this->img);
                    } else {
                        error_log('Sorry, this server doesn\'t support <b>imagecreatefromgif()</b>');
                        return;
                    }
                    break;
                case "jpg": $this->data = imagecreatefromjpeg($this->img); break;
                case "png": $this->data = imagecreatefrompng($this->img); break;
            }
        }
        
        function resize() {
            if($this->imagick == 2) {
                $this->imagickObj->thumbnailImage($this->max!='height' ? $this->w : 0, $this->max != 'width' ? $this->h : 0, $this->max == 'both' ? true : false);
                return;
            } elseif($this->imagick == 1) {
                $s = $this->w . 'x' . $this->h;
                if($this->max == 'width') {
                    $s = $this->w;
                } elseif($this->max == 'height') {
                    $s = 'x' . $this->h;
                }
                @exec(escapeshellarg($this->imagickPath) . ' ' . escapeshellarg($this->img) . ' -quality 100 -thumbnail ' . escapeshellarg($s) . ' ' . escapeshellarg($this->thumb));
                touch($this->thumb, @filemtime($this->img));
                return;
            }
            
            if(str_replace("%", "", $this->w) != $this->w) $this->w = $this->info[0] * str_replace("%", "", $this->w) / 100;
            if(str_replace("%", "", $this->h) != $this->h) $this->h = $this->info[1] * str_replace("%", "", $this->h) / 100;
            
            switch($this->max) {
                case 'both':
                case 'all':
                    if($this->info[0]/$this->info[1] < $this->w/$this->h) {
                        $this->w = ($this->info[0] * $this->h) / $this->info[1];
                    } else {
                        $this->h = ($this->info[1] * $this->w) / $this->info[0];
                    }
                    break;
                case 'w':
                case 'width':
                    $this->w = ($this->info[0] * $this->h) / $this->info[1];
                    break;
                case 'h':
                case 'height':
                    $this->h = ($this->info[1] * $this->w) / $this->info[0];
                    break;
            }
            
            $out = null;
            if(function_exists("imagecreatetruecolor")) {
                $out = imagecreatetruecolor($this->w,$this->h);
            } else {
                $out = imagecreate($this->w,$this->h);
            }
            
            if(function_exists("imageantialias")) {
                imageantialias($out, true);
            }
            
            imagealphablending($out, false);
            
            if(function_exists("imagesavealpha")) {
                imagesavealpha($out, true);
            }
            
            if(function_exists("imagecolorallocatealpha")) {
                imagecolorallocatealpha($out, 255, 255, 255, 127);
            }
            
            if(function_exists("imagecopyresampled")) {
                imagecopyresampled($out, $this->data, 0, 0, 0, 0, $this->w, $this->h, $this->info[0], $this->info[1]);
            } else {
                imagecopyresized($out, $this->data, 0, 0, 0, 0, $this->w, $this->h, $this->info[0], $this->info[1]);
            }
    
            imagedestroy($this->data);
            $this->data = & $out;
        }
        
        function save() {
            if($this->imagick == 2) {
                $this->imagickObj->writeImage($this->thumb); 
                return;
            } elseif($this->imagick == 1) {
                return;
            }
            switch($this->type) {
                case "gif":
                    if(!function_exists("imagegif")) {
                        imagepng($this->data, $this->thumb);
                    } else {
                        imagegif($this->data, $this->thumb);
                    }
                    touch($this->thumb, @filemtime($this->img));
                    break;
                case "jpg": imagejpeg($this->data, $this->thumb, 100); touch($this->thumb, @filemtime($this->img)); break;
                case "png": imagepng($this->data, $this->thumb); touch($this->thumb, @filemtime($this->img)); break;
            }
        }
    
    }

}
?>
