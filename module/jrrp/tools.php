<?php

function getRp($user, $timestamp = 0){
	date_default_timezone_set('Asia/Shanghai');
	if(!$timestamp) $timestamp = time();
	return intval(shell_exec('python3 ../module/jrrp/jrrp.py '.$user.' '.date('Y', $timestamp).' '.date('j', $timestamp).' '.(intval(date('z', $timestamp)) + 1)));
}

?>
