<?php

include "_core/inc_autoload.php";

if(!isset($argv)){ $argv = array(); }

define('APP_DIR', 'D:\\WebEstruturada'.DIRECTORY_SEPARATOR);
Skt_Core_Autoload::appDirectoryRegister();
Skt_Core_Maker::run($argv);

