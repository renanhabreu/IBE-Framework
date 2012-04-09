<?php

include "_core/inc_autoload.php";

if(!isset($argv)){ $argv = array(); }

Skt_Core_Autoload::appDirectoryRegister();
Skt_Core_Maker::run($argv);

