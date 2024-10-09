<?php
$data = file_get_contents('php://input');
$data = json_decode($data, true);



include('config.php');






if (isset($data['message'])) {
    $chatType = $data['message']['chat']['type'];
    if ($chatType == 'private' || $chatType == 'channel') {
        exit();
    }
}




//////////////////////////
//FOR NEW MESSAGES
//////////////////////////
function slack($message, $channel, $type_chat)
{
    $ch = curl_init("https://slack.com/api/chat.postMessage");
    $data = http_build_query([
        "token" => SLACK_TOKEN,
    	"channel" => $channel, //"#mychannel",
    	"text" => $message, //"Hello, Foo-Bar channel message.",
    	"username" => "ChatGroup",
    ]);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);
    
    
    $data = json_decode($result, true);
    $message_ts = $data['message']['ts'];
    $file_path = './data/' . $type_chat;
    file_put_contents($file_path, $message_ts);
    return $result;
    
}




//////////////////////////
//FOR THREAD MESSAGES
//////////////////////////
function slack_thread($message, $channel, $thread)
{
    $ch = curl_init("https://slack.com/api/chat.postMessage");
    $data = http_build_query([
        "token" => SLACK_TOKEN,
    	"channel" => $channel, //"#mychannel",
    	"thread_ts" => $thread,
    	"text" => $message, //"Hello, Foo-Bar channel message.",
    	"username" => "ChatGroup111",
    ]);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    $data = json_decode($result, true);
    $message_ts = $data['message']['ts'];
    $file_path = './data/' . $type_chat;
    //file_put_contents($file_path, $message_ts);
    curl_close($ch);
    print_r($result);
    return $result;
}









//////////////////////////
//////CHECK CHAT/////////
//////////////////////////
$type_chat = ''. preg_replace("/[^,.0-9]/", '', @$data['message']['chat']['id']) .'';

if (!empty($data['message']['text'])) {
    $message_text = '[' . $data['message']['chat']['title'] . '] 👤*' . $data['message']['from']['first_name'] . '*: 
    
' . @$data['message']['text'] .'';
    $file_path = './data/' . $type_chat;
    if (!file_exists($file_path)) {
        file_put_contents($file_path, $message_ts);
        slack($message_text, SLACK_CHANNNEL, $type_chat);
    } else {
        $msg_ts_slack = file_get_contents($file_path);
        slack_thread($message_text, SLACK_CHANNNEL, $msg_ts_slack);
    }
}




//PHOTO
if (!empty($data['message']['photo'])) {
    
    if (!file_exists('./uploads/'. $data['message']['chat']['id'] . '')) {
    mkdir('./uploads/' . $data['message']['chat']['id'] . '', 0777, true);
}
    
        $photo = array_pop($data['message']['photo']);
        $res = sendTelegram(
                'getFile',
                array(
                        'file_id' => $photo['file_id']
                )
        );
        $res = json_decode($res, true);
        if ($res['ok']) {
                $src = 'https://api.telegram.org/file/bot' . TG_TOKEN . '/' . $res['result']['file_path'];
                $name_file = $data['message']['chat']['id'] . '/' . time() . '-' . basename($src);
                $dest = './uploads/' . $name_file;
                 if (copy($src, $dest)) { }
                 
                     $file_path = './data/' . $type_chat;
                        if (!file_exists($file_path)) {
                            file_put_contents($file_path, $message_ts);
                            slack($message_text, SLACK_CHANNNEL, $type_chat);
                        } else {
                            $msg_ts_slack = file_get_contents($file_path);
                            
                            if (empty($data['message']['caption'])) {
                                $msg = '[' . $data['message']['chat']['title'] . '] 👤*' . $data['message']['from']['first_name'] . '*:';
                                slack_thread("$msg \n\n 💾 File: " . WEB_SITE . "/uploads/" . $name_file, SLACK_CHANNNEL, $msg_ts_slack);
                            } else {
                                $msg = '[' . $data['message']['chat']['title'] . '] 👤*' . $data['message']['from']['first_name'] . '*: 
    
' . @$data['message']['caption'] .'';
                                slack_thread("$msg \n\n 💾 File: " . WEB_SITE . "/uploads/" . $name_file, SLACK_CHANNNEL, $msg_ts_slack);
                            }
                        }
                 
                 
        }
}







//FILE
if (!empty($data['message']['document'])) {
    
    
           $defile =  $data['message']['document'];

    
    if (!file_exists('./uploads/'. $data['message']['chat']['id'] . '')) {
    mkdir('./uploads/' . $data['message']['chat']['id'] . '', 0777, true);
}
    
        
        $res = sendTelegram(
                'getFile',
                array(
                        'file_id' => $defile['file_id']
                )
        );
        $res = json_decode($res, true);
        if ($res['ok']) {
                $src = 'https://api.telegram.org/file/bot' . TG_TOKEN . '/' . $res['result']['file_path'];
                $name_file = $data['message']['chat']['id'] . '/' . $defile["file_name"];
                $dest = './uploads/' . $name_file;
                 if (copy($src, $dest)) { }
                     $file_path = './data/' . $type_chat;
                        if (!file_exists($file_path)) {
                            file_put_contents($file_path, $message_ts);
                            slack($message_text, SLACK_CHANNNEL, $type_chat);
                        } else {
                            $msg_ts_slack = file_get_contents($file_path);
                            
                            if (empty($data['message']['caption'])) {
                                 $msg = '[' . $data['message']['chat']['title'] . '] 👤*' . $data['message']['from']['first_name'] . '*:';
                                slack_thread("$msg \n\n 💾 File: " . WEB_SITE . "/uploads/" . $name_file, SLACK_CHANNNEL, $msg_ts_slack);
                            } else {
                                $msg = '[' . $data['message']['chat']['title'] . '] 👤*' . $data['message']['from']['first_name'] . '*: 
    
' . @$data['message']['caption'] .'';
                                slack_thread("$msg \n\n 💾 File: " . WEB_SITE . "/uploads/" . $name_file, SLACK_CHANNNEL, $msg_ts_slack);
                            }
                        }
                 
                 
        }
}
