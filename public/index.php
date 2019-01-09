<?php

if(function_exists('fastcgi_finish_request'))fastcgi_finish_request();

require('init.php');

use kjBot\Frame\Message;

try{
    //if(strpos($_SERVER->HTTP_USER_AGENT,0,6) != "CQHttp")exit("Error! Accept POST requests from CQHTTP only!");
    $Queue[]= sendMaster(strpos($_SERVER->HTTP_USER_AGENT,0,6));

    $listen = config('Listen');
    if($listen !== NULL && ($Event['group_id'] == $listen || $listen == $Event['user_id'])){
        $Queue[]= sendMaster('['.date('Y-m-d H:i:s', $Event['time']-86400)."] {$Event['user_id']} say:\n{$Event['message']}", false, true);
    }

    switch($Event['post_type']){
        case 'message':
        case 'notice':
        case 'request':
        case 'meta_event':
            require($Event['post_type'].'Processor.php');
            break;
        default:
            $Queue[]= sendMaster('Unknown post type '.$Event['post_type'].', Event:'."\n".var_export($_SERVER, true));
    }

    //调试
    if($Debug && $Event['user_id'] == $DebugListen){
        $Queue[]= sendMaster(var_export($Event, true)."\n\n".var_export($Queue, true));
    }

}catch(\Exception $e){
    $Queue[]= sendBack($e->getMessage(), false, true);
}

try{
    //将队列中的消息发出
    foreach($Queue as $msg){
        $MsgSender->send($msg);
    }
}catch(\Exception $e){
    setData('error.log', var_dump($Event).$e.$e->getCode()."\n", true);
}



?>