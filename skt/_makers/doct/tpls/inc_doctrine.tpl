<?php

/**
 * Doctrine Extention
 * @autor REnan Abreu
 */
class Ext_Doctrine {

    private static $enable = FALSE;

    static public function enable($enable = TRUE) {

        if ($enable) {
            $file_exists = file_exists('_maps/doctrine/Doctrine.php');
            $enable = !(self::$enable);

            if ($file_exists) {
                if ($enable) {
                    require_once '_maps/doctrine/Doctrine.php';
                    spl_autoload_register(array('Doctrine', 'autoload'));
                    spl_autoload_register(array('Doctrine', 'modelsAutoload'));
                    self::$enable = TRUE;

                    $manager = Doctrine_Manager::getInstance();
                    $manager->setAttribute(Doctrine::ATTR_MODEL_LOADING, Doctrine::MODEL_LOADING_CONSERVATIVE);
                    $manager->setAttribute(Doctrine::ATTR_AUTOLOAD_TABLE_CLASSES, TRUE);
                    $manager->setAttribute(Doctrine::ATTR_VALIDATE, Doctrine::VALIDATE_ALL);
                    $manager->setAttribute(Doctrine::ATTR_EXPORT, Doctrine::EXPORT_ALL);
                    $manager->setAttribute(Doctrine::ATTR_AUTO_ACCESSOR_OVERRIDE, true);

                    Doctrine_Manager::connection(Ibe_Load::configure()->getDataBaseHost());
                    Doctrine::loadModels('_maps/models');
                }
            } else {
                throw new Ibe_Exception('Doctrine librarie not found');
            }

            $configure = Ibe_Load::configure();
            Doctrine_Manager::connection(Ibe_Load::configure()->getDataBaseHost());
        } else if (self::$enable) {
            self::$enable = FALSE;
            spl_autoload_unregister(array('Doctrine', 'autoload'));
        }
    }

}
