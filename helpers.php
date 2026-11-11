<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2021-06-29 08:15:05
 * @modify date 2021-06-29 08:15:05
 * @desc Herlpers
 */

function httpQuery(array $query = [])
{
    return http_build_query(array_merge($_GET, $query));
}

function isDirect()
{
    defined('INDEX_AUTH') OR die('Direct access not allowed!');
}

function pluginDirName()
{
    $dir = explode(DIRECTORY_SEPARATOR, trim(dirname(__FILE__), DIRECTORY_SEPARATOR));
    return $dir[array_key_last($dir)];
}

function commaToDot($string)
{
    return str_replace(',', '.', $string);
}

function loadFile($fileToLoad, $type = 'require')
{
    global $dbs,$max_print,$sysconf,$content,$settingsTemplate,$chunked_barcode_arrays,$title;

    if (file_exists(__DIR__ . '/' . $fileToLoad . '.php')) 
    {
        switch ($type) {
            case 'include':
                include __DIR__ . '/' . $fileToLoad . '.php';
                break;
            
            default:
                require __DIR__ . '/' . $fileToLoad . '.php';
                break;
        }
    }
    else
    {
        die('File tidak ada');
    }
}

function jsonProps($mix)
{
    return str_replace('"', '\'', json_encode($mix));
}

function jsonResponse($mix)
{
    header('Content-Type: application/json');
    echo json_encode($mix);
    exit;
}

function jsonPost($key = '')
{
    $post = json_decode(file_get_contents('php://input'), TRUE);

    return (!empty($key) && isset($post[$key])) ? $post[$key] : $post;
}

function sliceCallNumber($string)
{
    if (empty($string) || trim((string)$string) === '')
    {
        return '<b style="color: red">callnumbernya<br/>dimana<br/>udah dimasukin belum di data itemnya?</b>';
    }

    $string = trim((string)$string);
    $split = preg_split('/(?<=\w)\s+(?=[A-Za-z])/m', $string);
    $result = '';
    
    foreach ($split as $index => $stringSplit) {
        if (str_contains($stringSplit, ' '))
        {
            $Plus = explode(' ', $stringSplit);
            foreach ($Plus as $part) {
                if ($part !== '') {
                    $result .= $part . '<br/>';
                }
            }
        }
        else
        {
            $result .= $stringSplit . '<br/>';
        }
    }

    return preg_replace('/<br\/>$/', '', $result);
}

function callNumberColor($string, $arrayColor)
{
    $string = trim((string)($string ?? ''));
    if ($string === '') {
        return (isset($arrayColor['default'])) ? $arrayColor['default'] : '#ffffff';
    }

    $callNumber = substr($string, 0, 1);

    if (!preg_match('/[0-9]/i', $callNumber))
    {
        $explodeCallnumber = explode(' ', $string);
        $code = '?';
        for ($i = 1; $i < count($explodeCallnumber); $i++) {
            $firstChar = substr($explodeCallnumber[$i], 0, 1);
            if (preg_match('/[0-9]/', $firstChar)) {
                $code = $firstChar;
                break;
            }
        }
        if ($code === '?' && isset($explodeCallnumber[1])) {
            $code = substr($explodeCallnumber[1], 0, 1);
        }
    }
    else
    {
        $code = $callNumber;
    }

    return (isset($arrayColor[$code.'XX'])) ? $arrayColor[$code.'XX'] : '#ffffff';
}
