<?php

include "_core/inc_autoload.php";

if(!isset($argv)){ $argv = array(); }

Skt_Core_Autoload::appDirectoryRegister("../../ibe-apps");
Skt_Core_Maker::run($argv);

