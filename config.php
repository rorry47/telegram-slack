<?php
//////////////////////////
////////VARIABLES///////
//////////////////////////
define('TG_TOKEN', '123123123123:iwuhefiuwhefiwuhefiuhw'); //TOCKEN TELEGRAM BOT
define('SLACK_TOKEN', 'xoxb-21e12e12-12e12e23-12r12r1r1r2'); //TOCKEN SLACK BOT
define('SLACK_CHANNNEL', 'C07QS357211'); // ID CHANNEL IN SLACK
define('WEB_SITE', 'https://nkotov.net/api'); // URL: https://example.com or https://example.com/slack (not add "/")
//////////////////////////
//////////////////////////
//////////////////////////



//////////////////////////
// TELEGRAM FUNCTION FOR API.
//////////////////////////
function sendTelegram($method, $response)
{
    $ch = curl_init('https://api.telegram.org/bot' . TG_TOKEN . '/' . $method);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
        
}








//CHECKS
$directory = './uploads/';
$daysOld = 30;
$now = time();

// Проверка наличия директории
if (!is_dir($directory)) {
    die("NOT DIRECTORY: $directory");
}

// Открываем директорию
if ($handle = opendir($directory)) {
    // Проходим через каждый файл в папке
    while (false !== ($file = readdir($handle))) {
        $filePath = $directory . $file;
        
        // Пропускаем текущую и родительскую директории ('.', '..')
        if ($file == '.' || $file == '..') {
            continue;
        }

        // Проверяем, является ли это файлом
        if (is_file($filePath)) {
            // Получаем время последнего изменения файла
            $fileModifiedTime = filemtime($filePath);

            // Если файл старше заданного количества дней, удаляем его
            if (($now - $fileModifiedTime) > ($daysOld * 24 * 60 * 60)) {
                unlink($filePath);
                echo "Удален файл: $filePath\n";
            }
        }
    }
    closedir($handle);
}
