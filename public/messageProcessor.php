<?php

$message = trim(str_replace('[CQ:at,qq='.config('bot').']', '', $Event['message']));
$length = strpos($message, "\n");
if(false===$length){
    $length = strlen($message);
}

if(preg_match('/^('.config('prefix', '#').')/', $message, $prefix)
    || preg_match('/^('.config('prefix2', '.').')/', $message, $prefix) && config('enablePrefix2', false)){
    $Command = parseCommand(substr($message, strlen($prefix[1])-1, $length));
    $Text = substr($message, $length+1);
    $module = substr(nextArg(), strlen($prefix[1]));
    try{
        if(config('alias',false) == true
            && $alias = json_decode(getData('alias/'.$Event['user_id'].'.json'),true)[$module]){
            loadModule($alias);
        }
        else
            loadModule($module);
    }catch(\Exception $e){
        throw $e;
    }
}else{ //不是命令
    $Message = $message;
    $Command = parseCommand(substr($message, 0, $length));
    $Text = substr($message, $length+1);
    require('../middleWare/Chain.php');
}

?>
