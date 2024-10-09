<?php

$data = file_get_contents('php://input');
$event = json_decode($data, true);
include('config.php');

if (isset($event['type']) && $event['type'] == 'url_verification') {
    $challenge = $event['challenge'];
    echo json_encode(['challenge' => $challenge]);
}






if (isset($event['event']['type']) && $event['event']['type'] == 'message') {
    $text = $event['event']['text'];
    
   $user = $event['event']['user'];
    
    $channel = $event['event']['channel'];
    
    
    
    $message_ts = $event['event']['thread_ts'];




 $directory = './data';
$tgidcode = '';

if ($handle = opendir($directory)) {
    while (false !== ($file = readdir($handle))) {
        if ($file != '.' && $file != '..') {
            $filePath = $directory . '/' . $file;

            if (is_file($filePath)) {
                $content = file_get_contents($filePath);

                if (strpos($content, $message_ts) !== false) {
                    $tgidcode = $file; 
                    $file_found = true;
                    break; 
                }
            }
        }
    }
    closedir($handle); 
}



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



