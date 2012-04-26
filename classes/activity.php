<?php

class Activity
{
	public $id;
	public $name;


function __construct($dbData)
{
	$this->id = $dbData["id"];
	$this->name = $dbData["name"];
	$this->goal = $dbData["goal"];
}

}
?>