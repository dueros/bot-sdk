<?php

	require "Bot.php";
	ini_set("display_errors", "On");
	ini_set('track_errors', true);
	ini_set('error_reporting', E_ALL & ~E_NOTICE);

	$tax = new Bot('sample_personal_income_tax');

	//记录整体执行时间
	$tax->log->markStart('all_t');
	$ret = $tax->run();
	$tax->log->markEnd('all_t');

	//打印日志
	//or 在register_shutdown_function增加一个执行函数
	$tax->log->notice($tax->log->getField('url_t'));
	$tax->log->notice();

	print $ret;
