<?php

if(!in_array('MagicToolboxImageHelper', get_declared_classes())) {

    require_once('magictoolbox.params.class.php');

    class MagicToolboxImageHelper {

        // link to original image file
        var $src;

        // original file extension
        var $ext;

        // file or dirrectory where should be saved resized file
        var $_out;

        // destination file (without sufix and extension)
        var $out;

        // full path for webdir
        var $path;

        // web address for wesite
        var $url;

        // destination file sufix
        var $sufix;

        // full destination file
        var $file;

        // options (imagick path, resize params, etc)
        var $options;


        function MagicToolboxImageHelper($src = null, $out = null, $options = null, $path = null, $url = null) {
            clearstatcache();

            $this->base($path, $url);

            $this->src($src);

            $out = $this->path . $out;
            // TODO recursively check/create subdirs
            if(!$out || !@is_dir($out) && !@is_dir(dirname($out))) {
                $this->_out = null;
                $this->out = null;
            } else {
                if(@is_dir($out) || !@is_dir($out) && @is_dir(dirname($out)) && preg_match('/\/[^\.]+$/is', $out) && @mkdir($out) && @chmod($out, 0777)) {
                    $this->_out = 'dir';
                    $this->out = preg_replace('/\/$/is', '', $out) . '/' . md5($this->src);
                } else {
                    $this->_out = 'file';
                    //$this->out = preg_replace('/\.[^\/\.]+$/is', '', $out);
                    $this->out = $out;
                }
            }

            if(!$options || !is_a($options, 'MagicToolboxParams')) {
                error_log('MagicToolbox ImageHelper :: Invalid options');
                $this->options = new MagicToolboxParams();
                $this->options->appendArray(array("size-depends"=>array("id"=>"size-depends","default"=>"both","label"=>"Images size depends","type"=>"array","subType"=>"select","values"=>array("width","height","both")),"square-images"=>array("id"=>"square-images","default"=>"disable","label"=>"Create square images","description"=>"If enabled then the white/transparent padding will be added around the image","type"=>"array","subType"=>"radio","values"=>array("enable","disable")),"imagemagick"=>array("id"=>"imagemagick","default"=>"auto","label"=>"Path to Imagemagick binaries (convert tool)","description"=>"You can set 'auto' to automatically detect imagemagick location or 'off' to disable imagemagick and use php GD lib instead","type"=>"text"),"watermark"=>array("id"=>"watermark","default"=>"","label"=>"Path to watermark image","description"=>"Relative for site base path. Use empty to disable watermark","type"=>"text"),"watermark-opacity"=>array("id"=>"watermark-opacity","default"=>"50","label"=>"Opacity of the watermark image","description"=>"0-100","type"=>"num"),"watermark-size"=>array("id"=>"watermark-size","default"=>"50%","label"=>"Watermark image size","description"=>"pixels (fixed size) or percent (relative for image size)","type"=>"text"),"watermark-size-depends"=>array("id"=>"watermark-size-depends","default"=>"both","label"=>"Watermark size depends","type"=>"array","subType"=>"select","values"=>array("width","height","both")),"watermark-position"=>array("id"=>"watermark-position","default"=>"center","label"=>"Position of the watermark","description"=>"'watermark-size' will ignore when 'watermark-position' sets to 'stretch'","type"=>"array","subType"=>"select","values"=>array("top","right","bottom","left","top-left","bottom-left","top-right","bottom-right","center","stretch")),"watermark-offset-x"=>array("id"=>"watermark-offset-x","default"=>"0","label"=>"Watermark horizontal offset","description"=>"Offset from left and/or right image borders. Pixels (fixed size) or percent (relative for image size)","type"=>"text"),"watermark-offset-y"=>array("id"=>"watermark-offset-y","default"=>"0","label"=>"Watermark vertical offset","description"=>"Offset from top and/or bottom image borders. Pixels (fixed size) or percent (relative for image size)","type"=>"text")));
            } else {
                $this->options = $options;
            }
        }

        function src($src = null) {
            if($src === null || !(file_exists($this->path . $src) && is_file($this->path . $src))) {
                error_log('MagicToolbox ImageHelper :: Invalid image file (' . ($src == null ? 'null' : ($this>path . $src)) . ')');
                return null;
            } else {
                $this->src = $this->path . $src;
                $this->ext = explode('.', $src);
                $this->ext = '.' . $this->ext[count($this->ext) - 1];
            }
        }

        function base($path = null, $url = null) {
            if($path) { $path = preg_replace('/\/$/is', '', $path); $this->path = $path; }
            if($url) { $url = preg_replace('/\/$/is', '', $url); $this->url = $url; }
        }

        function _link($link) {
            return $this->path ? str_replace($this->path, $this->url, $link) : ($this->url . $link);
        }

        function create($type, $force = false) {
            $size = is_int($type) ? $type : ($type == 'original' ? 'original' : $this->options->getValue($type . '-size'));
            if($size) {
                if($this->_out == 'file') {
                    $this->file = $this->out;
                } else {
                    $this->sufix = (is_int($type) ? '' : $type) . $size . $this->options->getValue('size-depends');
                    $this->file = $this->out . $this->sufix . $this->ext;
                }
                if(!$force && (!(file_exists($this->file) && is_file($this->file)) || (@filemtime($this->file) - @filemtime($this->src)) < 0)) {
                    $force = true;
                }
                if($force) {
                    $this->resize($size);
                }
                if(file_exists($this->file) && is_file($this->file)) {
                    @chmod($this->file, 0755);
                    return $this->_link($this->file);
                }
            }
            return $this->_link($this->src);
        }

        function resize($w = null, $h = null, $depends = null, $square = null) {
            $imagick = $this->options->getValue('imagemagick');
            $watermark = $this->options->getValue('watermark');
            if($watermark) {
                $watermark = $this->path . preg_replace('/\/$/is', '', $watermark);
                if(!(file_exists($watermark) && is_file($watermark))) {
                    $watermark = false;
                } else {
                    $wpos = $this->options->getValue('watermark-position');
                    $wopacity = $this->options->getValue('watermark-opacity');
                    $woffsetx = $this->options->getValue('watermark-offset-x');
                    $woffsety = $this->options->getValue('watermark-offset-y');
                }
            } elseif($w == 'original') {
                return;
            }
            if($depends == null) {
                $depends = $this->options->getValue('size-depends');
            }
            if($square == null) {
                $square = $this->options->getValue('square-images');
            }
            $square = $square == 'enable' ? true : false;
            if($imagick = $this->_checkImagick($imagick)) {
                // use imagemagick
                if($imagick == 'native') {
                    $imagick = new Imagick($this->img);
                    if($h === null) {
                        $imagick->thumbnailImage($depends != 'height' ? $w : 0, $depends != 'width' ? $w : 0, $depends == 'both' ? true : false);
                    } else {
                        $imagick->thumbnailImage($w, $h, false);
                    }
                    $imagick->writeImage($this->file);
                    // TODO implement watermark
                    // TODO implement square
                } else {
                    $imagickComposite = str_replace('convert', 'composite', $imagick);
                    $size = explode('::', exec(escapeshellarg($imagick) . ' ' . escapeshellarg($this->src) . ' -format \'%w::%h::%[depth]::%e\' info:'));
                    if($w === 'original') { $w = $size[0]; $h = $size[1]; }
                    if($h === null) { list($w, $h) = $this->_size($w, $size[0], $size[1], $depends); }

                    exec(escapeshellarg($imagick) . ' ' . escapeshellarg($this->src) . ' -quality 100 -resize ' . $w . 'x' . $h . '! ' . escapeshellarg($this->file));

                    if($watermark) {
                        $wsize = explode('::', exec(escapeshellarg($imagick) . ' ' . escapeshellarg($watermark) . ' -format \'%w::%h::%[depth]::%e\' info:'));

                        $ws = $this->options->getValue('watermark-size');
                        $ws = $this->_percent($ws, min($w, $h));
                        list($ww, $wh) = $this->_size($ws, $wsize[0], $wsize[1], $this->options->getValue('watermark-size-depends'));

                        $woffsetx = $this->_percent($woffsetx, $w);
                        $woffsety = $this->_percent($woffsety, $h);

                        if($wpos == 'stretch') {
                            $wcmd = '-size ' . $w . 'x' . $h . ' -depth ' . $wsize[2] . ' NULL: -write mpr:watermarkblank +delete '
                                  . escapeshellarg($watermark) . ' -quality 100 -resize ' . ($w - 2 * $woffsetx) . 'x' . ($h - 2 * $woffsety) . '! -write mpr:watermark +delete '
                                  . 'mpr:watermarkblank -gravity Center mpr:watermark -composite -write mpr:watermark +delete ';
                        } else {
                            $wcmd = '-size ' . ($ww + 2 * $woffsetx) . 'x' . ($wh + 2 * $woffsety) . ' -depth ' . $wsize[2] . ' NULL: -write mpr:watermarkblank +delete '
                                  . escapeshellarg($watermark) . '  -quality 100 -resize ' . $ww . 'x' . $wh . '! -write mpr:watermark +delete '
                                  . 'mpr:watermarkblank -gravity Center mpr:watermark -composite -write mpr:watermark +delete ';
                        }

                        exec(escapeshellarg($imagick) . ' ' . $wcmd . ' mpr:watermark -quality 100 ' . escapeshellarg($this->file . '.png'));

                        switch($wpos) {
                            case 'stretch':
                            case 'center':
                                $wcmd = 'Center';
                                break;
                            case 'tile':
                                // TODO implement
                                // we can use -tile option here
                                break;
                            case 'top-right':
                                $wcmd = 'NorthEast';
                                break;
                            case 'top-left':
                                $wcmd = 'NorthWest';
                                break;
                            case 'bottom-right':
                                $wcmd = 'SouthEast';
                                break;
                            case 'bottom-left':
                                $wcmd = 'SouthWest';
                                break;
                            case 'top':
                                $wcmd = 'North';
                                break;
                            case 'bottom':
                                $wcmd = 'South';
                                break;
                            case 'left':
                                $wcmd = 'West';
                                break;
                            case 'right':
                                $wcmd = 'East';
                                break;
                            default: break;
                        }
                        exec(escapeshellarg($imagickComposite) . ' ' . escapeshellarg($this->file . '.png') . ' -dissolve ' . $wopacity . ' -gravity ' . $wcmd . ' ' . escapeshellarg($this->file) . ' ' . escapeshellarg($this->file));
                        @unlink($this->file . '.png');
                    }

                    if($square) {
                        $s = max($w, $h);
                        if($size[3] == 'png' || $size == 'gif') {
                            // null for transparent images
                            $wrapper = 'NULL:';
                        } else {
                            // white background for opaque images
                            $wrapper = 'xc:white';
                        }
                        //$wrapper = 'NULL:';
                        $cmd = ' -size ' . $s . 'x' . $s . ' -depth ' . $size[2] . ' ' . $wrapper . ' -write mpr:resultblank +delete '
                              . 'mpr:resultblank -gravity Center ' . escapeshellarg($this->file) . ' -compose src-over -composite ' . escapeshellarg($this->file);
                        exec(escapeshellarg($imagick) . $cmd);
                    }


                }
            } else {
                // use GD library
                list($data, $size) = $this->_load($this->src);
                if(!$data) { return false; }
                if($w === 'original') { $w = $size[0]; $h = $size[1]; }
                if($h === null) { list($w, $h) = $this->_size($w, $size[0], $size[1], $depends); }
                $rw = $square ? max($w, $h) : $w;
                $rh = $square ? max($w, $h) : $h;

                $out = $this->_create($rw,  $rh);

                $fCopy = function_exists('imagecopyresampled') ? 'imagecopyresampled' : 'imagecopyresized';
                call_user_func($fCopy, $out, $data, ($rw-$w)/2, ($rh-$h)/2, 0, 0, $w, $h, $size[0], $size[1]);

                // include watermark
                if($watermark) {
                    list($wdata, $wsize) = $this->_load($watermark);

                    $ws = $this->options->getValue('watermark-size');
                    $ws = $this->_percent($ws, min($w, $h));
                    list($ww, $wh) = $this->_size($ws, $wsize[0], $wsize[1], $this->options->getValue('watermark-size-depends'));

                    $wpos = $this->options->getValue('watermark-position');
                    $wopacity = $this->options->getValue('watermark-opacity');
                    $woffsetx = $this->options->getValue('watermark-offset-x');
                    $woffsety = $this->options->getValue('watermark-offset-y');

                    $woffsetx = $this->_percent($woffsetx, $w);
                    $woffsety = $this->_percent($woffsety, $h);

                    if($wpos == 'stretch') {
                        $wdatanew = $this->_create($w - 2 * $woffsetx, $h - 2 * $woffsety, 0);
                        call_user_func($fCopy, $wdatanew, $wdata, 0, 0, 0, 0, $w - 2 * $woffsetx, $h - 2 * $woffsety, $wsize[0], $wsize[1]);
                    } else {
                        $wdatanew = $this->_create($ww,  $wh, 0);
                        call_user_func($fCopy, $wdatanew, $wdata, 0, 0, 0, 0, $ww, $wh, $wsize[0], $wsize[1]);
                    }
                    //imagealphablending($wdatanew, true);

                    switch($wpos) {
                        case 'center':
                            //call_user_func($fCopy, $out, $wdata, ($rw-$ww)/2, ($rh-$wh)/2, 0, 0, $ww, $wh, $wsize[0], $wsize[1]);
                            imagecopymerge($out, $wdatanew, ($rw-$ww)/2, ($rh-$wh)/2, 0, 0, $ww, $wh, $wopacity);
                            break;
                        case 'tile':
                            // TODO implement
                            break;
                        case 'stretch':
                            imagecopymerge($out, $wdatanew, $woffsetx + ($rw - $w) / 2, $woffsety + ($rh - $h) / 2, 0, 0, $w - 2 * $woffsetx, $h - 2 * $woffsety, $wopacity);
                            break;
                        case 'top-right':
                            imagecopymerge($out, $wdatanew, $rw - $woffsetx - $ww - ($rw - $w) / 2, $woffsety + ($rh - $h) / 2, 0, 0, $ww, $wh, $wopacity);
                            break;
                        case 'top-left':
                            imagecopymerge($out, $wdatanew, $woffsetx + ($rw - $w) / 2, $woffsety + ($rh - $h) / 2, 0, 0, $ww, $wh, $wopacity);
                            break;
                        case 'bottom-right':
                            imagecopymerge($out, $wdatanew, $rw - $woffsetx - $ww - ($rw - $w) / 2, $rh - $woffsety - $wh - ($rh - $h) / 2, 0, 0, $ww, $wh, $wopacity);
                            break;
                        case 'bottom-left':
                            imagecopymerge($out, $wdatanew, $woffsetx + ($rw - $w) / 2, $rh - $woffsety - $wh - ($rh - $h) / 2, 0, 0, $ww, $wh, $wopacity);
                            break;
                        case 'top':
                            imagecopymerge($out, $wdatanew, ($rw - $ww) / 2, $woffsety + ($rh - $h) / 2, 0, 0, $ww, $wh, $wopacity);
                            break;
                        case 'bottom':
                            imagecopymerge($out, $wdatanew, ($rw - $ww) / 2, $rh - $woffsety - $wh - ($rh - $h) / 2, 0, 0, $ww, $wh, $wopacity);
                            break;
                        case 'left':
                            imagecopymerge($out, $wdatanew, $woffsetx + ($rw - $w) / 2, ($rh-$wh)/2, 0, 0, $ww, $wh, $wopacity);
                            break;
                        case 'right':
                            imagecopymerge($out, $wdatanew, $rw - $woffsetx - $ww - ($rw - $w) / 2, ($rh-$wh)/2, 0, 0, $ww, $wh, $wopacity);
                            break;
                        default: break;
                    }

                }

                switch($size[2]) {
                    case 1: function_exists('imagegif') && imagegif($out, $this->file);
                    case 3: imagepng($out, $this->file); break;
                    case 2: imagejpeg($out, $this->file, 100); break;
                }
                imagedestroy($data);
                imagedestroy($out);
            }
        }

        function _size($w, $originalW, $originalH, $depends) {
            $h = $w * $originalH/$originalW;
            if($depends == 'height' || $depends == 'both' && $h > $w) {
                $h = $w;
                $w = $h * $originalW/$originalH;
            }
            return array(round($w), round($h));
        }

        function _percent($p, $s) {
            preg_match('/^([0-9]+)(%|px)?$/is', $p, $matches);
            if(isset($matches[2]) && $matches[2] == '%') {
                $p = round($s * $matches[1] / 100);
            } else {
                $p = $matches[1];
            }
            return $p;
        }

        function _create($w, $h, $op = 127) {
            $fCreate = function_exists('imagecreatetruecolor') ? 'imagecreatetruecolor' : 'imagecreate';
            $out = call_user_func($fCreate, $w,  $h);

            if(function_exists('imageantialias')) { imageantialias($out, true); }
            if(function_exists('imagealphablending')) { imagealphablending($out, false); }
            if(function_exists('imagecolorallocatealpha')) {
                // white transparent BG
                $clr = imagecolorallocatealpha($out, 255, 255, 255, $op);
                imagefill($out, 0, 0, $clr);
            }
            if(function_exists('imagesavealpha')) { imagesavealpha($out, true); }
            if(function_exists('imagealphablending')) { imagealphablending($out, true); }

            return $out;
        }

        function _load($src, $size = null) {
            if($size === null) {
                $size = getimagesize($src);
            }
            /*
                1 GIF
                2 JPG
                3 PNG
                4 SWF
                5 PSD
                6 BMP
                7 TIFF (intel byte order)
                8 TIFF (motorola byte order)
                9 JPC
               10 JP2
               11 JPX
               12 JB2
               13 SWC
               14 IFF
            */
            switch($size[2]) {
                case 1:
                    // unfortunately this function does not work on windows
                    // via the precompiled php installation :(
                    // it should work on all other systems however.
                    if(function_exists('imagecreatefromgif')) {
                        $data = imagecreatefromgif($src);
                    } else {
                        $data = false;
                        error_log('MagicToolbox ImageHelper :: Sorry, this server doesn\'t support <b>imagecreatefromgif()</b> function');
                    }
                    break;
                case 2: $data = imagecreatefromjpeg($src); break;
                case 3: $data = imagecreatefrompng($src); break;
                // GD doesn't support other formats
                default:
                    $data = false;
                    error_log('MagicToolbox ImageHelper :: Unsupported image type (' . $size[2] . ')');
            }
            return array($data, $size);
        }

        function _file_exists($f, $check = false) {
            if(@file_exists($f) && (!$check || $check && @is_file($f))) {
                return true;
            } elseif(@exec('ls -l ' . escapeshellarg($f) . ' | grep ' . escapeshellarg($f))) {
                return true;
            } else {
                return false;
            }
        }

        function _checkImagick($imagick) {
            $execDisabled = preg_match('/exec/is', ini_get('disable_functions'));

            if(!$execDisabled && $imagick != 'off') {
                if($imagick == 'auto' || empty($imagick)) {
                    $imagick = false;
                    // auto detect
                    if(@$this->_file_exists('/usr/bin/convert')) {
                        // found UNIX imagick tools in /usr/bin
                        $imagick = '/usr/bin/convert';
                    } else if(@$this->_file_exists('/usr/local/bin/convert')) {
                        // found UNIX imagick tools in /usr/local/bin
                        $imagick = '/usr/local/bin/convert';
                    }
                    if(!$imagick) {
                        @exec('compgen -ac', $a);
                        if($a && is_array($a) && in_array('convert', $a) && in_array('identify', $a)) {
                            // UNIX imagick command line tools is available
                            $imagick = 'convert';
                        }
                    }
                } else {
                    if(!preg_match('/convert$/s', $imagick)) {
                       if(!preg_match('/\/$/s', $imagick)) {
                            $imagick .= '/';
                        }
                        $imagick .= 'convert';
                    }
                    if(!@$this->_file_exists($imagick)) {
                        $imagick = false;
                    }
                }
            } else {
                $imagick = false;
            }

            if($imagick) {
                // we should also check does we can run imagick bin file
                @exec(escapeshellarg($imagick) . ' logo: /tmp/logo.png', $ret, $exitCode);
                if($exitCode > 0) {
                    // got error, disable imagick
                    $imagick = false;
                }
            }

            // check imagick version (for some reason, resize option dosn't working in imagick 5.x)
            if($imagick) {
                @exec(escapeshellarg($imagick) . ' --version', $ret);
                foreach($ret as $line) {
                    if(preg_match('/version:/is', $line)) {
                        $v = preg_replace('/^.*?\s((?:[0-9]+\.){2}[0-9]+)(\-\d+)?\s.*$/is', '$1', $line);
                        if(version_compare($v, '6.0.0', '<')) {
                            $imagick = false;
                        }
                        break;
                    }
                }
            }

            // temporary disabled
            /*if(!$imagick && (in_array('Imagick', get_declared_classes()) || in_array('imagick', get_declared_classes()))) {
                $imagick = 'native';
            }*/

            return $imagick;
        }

    }

}
