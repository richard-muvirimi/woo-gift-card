<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of dimension
 *
 * @author Richard
 */
class Dimension {

    private $id;
    private $name;
    private $value1;
    private $value2;
    private $unit;

    public function __construct($id, $name, $value1, $value2, $unit = "mm") {
	$this->id = $id;
	$this->name = $name;
	$this->value1 = $value1;
	$this->value2 = $value2;
	$this->unit = $unit;
    }

    public function get_id() {
	return $this->id;
    }

    public function get_name() {
	return $this->name;
    }

    public function get_fullname() {
	$min = min($this->get_value1(), $this->get_value2());
	$max = max($this->get_value1(), $this->get_value2());

	return $this->name . " (" . $min . " * " . $max . " " . $this->get_unit() . ")";
    }

    public function get_value1() {
	return $this->value1;
    }

    public function get_value2() {
	return $this->value2;
    }

    public function set_value1($value) {
	$this->value1 = $value;
    }

    public function set_value2($value) {
	$this->value2 = $value;
    }

    public function get_unit() {
	return $this->unit;
    }

    public function getSizeInPoints() {
	$value1 = $this->get_value1();
	$value2 = $this->get_value2();

	switch ($this->get_unit()) {
	    case "mm":
		$value1 *= 2.835;
		$value2 *= 2.835;
		break;
	    case "in":
		$value1 *= 75;
		$value2 *= 75;
		break;
	    case "pt":
	    default :
	}

	return array(0, 0, min($value1, $value2), max($value1, $value2));
    }

}
