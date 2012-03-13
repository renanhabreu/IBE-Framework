<?php

class Model_Application {

    public function criar() {
        echo '<pre>';
        $this->readTemplate('tpl', '../../..','application');
    }


    private function readTemplate($source, $dest, $diffDir = '') {
        $sourceHandle = opendir($source);
        if (!$diffDir)
            $diffDir = $source;

        mkdir($dest . '/' . $diffDir);

        while ($res = readdir($sourceHandle)) {
            if ($res == '.' || $res == '..')
                continue;

            if (is_dir($source . '/' . $res)) {
                $this->readTemplate($source . '/' . $res, $dest, $diffDir . '/' . $res);
            } else {
                copy($source . '/' . $res, $dest . '/' . $diffDir . '/' . $res);
                self::show($dest . '/' . $diffDir . '/' . $res, 'building template');
            }
        }
    }

    static public function show($str, $label) {

        echo '<b style="color:green">' . strtoupper($label) . '</b>:' . $str . '<br />';
    }

}
