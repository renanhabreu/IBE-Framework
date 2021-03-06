<?php

class Maker {

    static private $ids = array();
    private $name = "";
    private $description = "";
    private $params = "";
    private $example = "";
    private $id = NULL;
    private $path = NULL;

    static public function registerId($id) {

        if (array_key_exists($id, self::$ids)) {
            $id = sizeof(self::$ids) + 2;            
        }
        self::$ids[$id] = true;
        ksort(self::$ids);
        
        return $id;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function getName() {
        return $this->name;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setParams($params) {
        $this->params = $params;
        return $this;
    }

    public function getParams() {
        return $this->params;
    }

    public function setExample($example) {
        $this->example = $example;
        return $this;
    }

    public function getExample() {
        return $this->example;
    }
    
    public function setId($id){
        $this->id = $id;
        return $this;
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function setPath($path){
        $this->path = $path;
        return $this;
    }
    
    public function getPath(){
        return $this->path;
    }
    
    public function getHtml() {
        $html = '<h3><a href="#">' . $this->name . '</a></h3>';
        $html .= '<div class="skt-maker" id="maker-' . $this->id . '">';
        $html .= '<span class="role" role="'.$this->path.'" />';

        foreach ($this->params as $param_name => $param_description) {
            $html .= "<p><label>$param_description :<input type='text' name='$param_name'/></label></p>";
        }
        $html .= "<p class='skt-description'>$this->description</p>";
        $html .= "<button id='".$this->id."'>Executar</button>";
        $html .= "<span class='skt-alert'></span>";
        $html .= "</div>";

        return $html;
    }

}

?>
