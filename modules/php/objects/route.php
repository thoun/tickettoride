<?php

class Route {
    public /*int*/ $from;
    public /*int*/ $to;
    public /*int*/ $number;
    public /*int*/ $color; // see Color constants (0 is gray)

    public function __construct(int $from, int $to, int $number, int $color = 0) {
        $this->from = $from;
        $this->to = $to;
        $this->number = $number;
        $this->color = $color;
    } 
}
?>