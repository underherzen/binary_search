<?php
function filesize64($file)
{
    static $iswin;
    if (!isset($iswin)) {
        $iswin = (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN');
    }

    static $exec_works;
    if (!isset($exec_works)) {
        $exec_works = (function_exists('exec') && !ini_get('safe_mode') && @exec('echo EXEC') == 'EXEC');
    }

    // try a shell command
    if ($exec_works) {
        $cmd = ($iswin) ? "for %F in (\"$file\") do @echo %~zF" : "stat -c%s \"$file\"";
        @exec($cmd, $output);
        if (is_array($output) && ctype_digit($size = trim(implode("\n", $output)))) {
            return $size;
        }
    }

    // try the Windows COM interface
    if ($iswin && class_exists("COM")) {
        try {
            $fsobj = new COM('Scripting.FileSystemObject');
            $f = $fsobj->GetFile( realpath($file) );
            $size = $f->Size;
        } catch (Exception $e) {
            $size = null;
        }
        if (ctype_digit($size)) {
            return $size;
        }
    }

    // if all else fails
    return filesize($file);
}
function intdiv($a, $b)
{
    return floor($a / $b);
}
function compString($string1, $string2)
{
    if($string2 == $string1)
    {
        return "Equal";
    }
    $arr = array(0=>$string1,
    1=>$string2);
    sort($arr);
    if($arr[0] == $string1)
    {
        return 1;
    }
    return 2;

}
function findKeyInProccessedBuf($key, $buf)
{
    array_pop($buf);
    foreach($buf as $cell)
    {
        $cell = explode("\t", $cell);
        if($cell[0] == $key)
        {
            if($cell[1] == '')
            {
                return 'NotFound';
            }
            return $cell[1];
        }
    }
    return 'NotFound';
}
function getVal($fileName, $key)
{
    $f = fopen($fileName, 'r');
    $fileSize = filesize64($fileName);
	if($fileSize > 1000)
    {
        $c = 1;
    }
    else{
        $c = 100;
    }
    $pointer = intdiv($fileSize, 2);
    $fileSize = intdiv($fileSize, 2);
    while($fileSize)
    {
        $fileSize=intdiv($fileSize,2);
        fseek($f, $pointer-$c, SEEK_SET);
        $buf = fread($f, 12000);
        $pos = strpos($buf, "\x0A");
        $buf = substr($buf, $pos, strlen($buf));
        $buf = explode("\x0A", $buf);
        $result = findKeyInProccessedBuf($key, $buf);
        if($result != "NotFound")
        {
            return $result;
        }
        $cell = $buf[1];
        $cellKey = explode("\t", $cell)[0];
        $result = compString($key, $cellKey);
        if($result == 'Equal')
        {
            return explode("\t", $cell)[1];
        }
        if($result == 1)
        {
            $pointer-=$fileSize;
        }
        if($result == 2)
        {
            $pointer+=$fileSize;
        }
    }
    fseek($f, 0, SEEK_SET);
    $buf = fread($f, 8001);
    $buf = explode("\x0A", $buf);
    $cell = $buf[0];
    $cellKey = explode("\t", $cell)[0];
    $result = compString($key, $cellKey);
    if($result == 'Equal')
    {
        return explode("\t", $cell)[1];
    }
    return 'undef';
}
?>