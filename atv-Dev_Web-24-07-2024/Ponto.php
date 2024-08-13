<?php
class Ponto {
    private $x;
    private $y;
    private $quadrado_id; // Adicione esta linha

    public function __construct($x, $y, $quadrado_id = null) {
        $this->x = $x;
        $this->y = $y;
        $this->quadrado_id = $quadrado_id; // Adicione esta linha
    }

    public function getX() {
        return $this->x;
    }

    public function getY() {
        return $this->y;
    }

    public function getQuadradoId() { // Adicione este método
        return $this->quadrado_id;
    }
}
?>
