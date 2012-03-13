<?php

include_once 'inc_application.php';
include_once 'inc_controller.php';
include_once 'inc_form.php';
include_once 'inc_map.php';

$host = $_POST['host'];
$user = $_POST['user'];
$pass = $_POST['pass'];
$schema = $_POST['schema'];


$application = new Model_Application();
$application->criar();

$forms = new Model_Form($host, $user, $pass, $schema);
$forms->criar();

$maps = new Model_Map($host, $user, $pass, $schema);
$maps->criar();



echo '<a href="../index.php">Voltar</a> Para ir ao aplicativo click <a href="../../../application/index.php">aqui</a>';


