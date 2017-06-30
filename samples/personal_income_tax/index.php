<?php

	require "Bot.php";
	$tax = new Bot();

	//记录整体执行时间
	$tax->log->markStart('all_t');
	$ret = $tax->run();
	$tax->log->markEnd('all_t');

	//打印日志
	//or 在register_shutdown_function增加一个执行函数
	$tax->log->notice($tax->log->getField('url_t'));
	$tax->log->notice();

	print $ret;
