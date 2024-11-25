<?php
class City {
    public int $id;

    public function __construct(        
        public string $name,
        public int $x,
        public int $y,
    ) {
    } 
}

class BigCity {
    public function __construct(
        public int $x,
        public int $y,
        public int $width,
    ) {
    } 
}
?>