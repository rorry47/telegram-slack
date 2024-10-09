<?php

// Получаем данные из POST-запроса
$data = file_get_contents('php://input');
$event = json_decode($data, true);
include('config.php');

if (isset($event['type']) && $event['type'] == 'url_verification') {
    $challenge = $event['challenge'];
    echo json_encode(['challenge' => $challenge]);
}






if (isset($event['event']['type']) && $event['event']['type'] == 'message') {
    // Извлекаем текст сообщения
    $text = $event['event']['text'];
    
    // Извлекаем пользователя, отправившего сообщение
    $user = $event['event']['user'];
    
    // Извлекаем ID канала
    $channel = $event['event']['channel'];
    
    
    
    $message_ts = $event['event']['thread_ts'];




 $directory = './data';
$tgidcode = '';

// Открываем директорию
if ($handle = opendir($directory)) {
    // Проходим по всем файлам в директории
    while (false !== ($file = readdir($handle))) {
        // Пропускаем текущую и родительскую директорию
        if ($file != '.' && $file != '..') {
            // Полный путь к файлу
            $filePath = $directory . '/' . $file;

            // Проверяем, является ли файл обычным файлом
            if (is_file($filePath)) {
                // Читаем содержимое файла
                $content = file_get_contents($filePath);

                // Проверяем, содержит ли файл нужное значение
                if (strpos($content, $message_ts) !== false) {
                    $tgidcode = $file; // Запоминаем название файла
                    $file_found = true; // Устанавливаем флаг в true
                    break; // Выходим из цикла
                }
            }
        }
    }
    closedir($handle); // Закрываем директорию
}


// Проверяем, найден ли файл
if ($tgidcode) {
    echo "Найден файл: $tgidcode";
} else {
    echo "Файл не найден.";
}
    
    
    
   
   
   
if (empty($event['event']['bot_id']) || $event['event']['bot_id'] == null || $event['event']['bot_id'] == false) {
            sendTelegram(
    'sendMessage',
    array(
        'chat_id' => '-' . $tgidcode,
        'parse_mode' => 'HTML',
        'text' => $text
    )
    );

}
    
}



