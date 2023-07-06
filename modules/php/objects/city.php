<?php
class City {
    public int $id;
    public string $name;
    public int $x;
    public int $y;

    public function __construct(string $name, int $x, int $y) {
        $this->name = $name;
        $this->x = $x;
        $this->y = $y;
    } 
}

class BigCity {
    public int $x;
    public int $y;
    public int $width;

    public function __construct(int $x, int $y, int $width) {
        $this->x = $x;
        $this->y = $y;
        $this->width = $width;
    } 
}
?>