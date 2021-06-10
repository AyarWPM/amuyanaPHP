<?php
class LogicSystem
{
	var $id_logic_system;
	var $label;
	var $description;
	var $creation_date;
	var $PRIMARY_KEY_id_logic_system_ENGINE_=_InnoDB_AUTO_INCREMENT_=_2_DEFAULT_CHARACTER;

	function LogicSystem(
$_id_logic_system,$_label,$_description,$_creation_date,$_PRIMARY_KEY_id_logic_system_ENGINE_=_InnoDB_AUTO_INCREMENT_=_2_DEFAULT_CHARACTER){
		$this->id_logic_system = $_id_logic_system;
		$this->label = $_label;
		$this->description = $_description;
		$this->creation_date = $_creation_date;
		$this->PRIMARY_KEY_id_logic_system_ENGINE_=_InnoDB_AUTO_INCREMENT_=_2_DEFAULT_CHARACTER = $_PRIMARY_KEY_id_logic_system_ENGINE_=_InnoDB_AUTO_INCREMENT_=_2_DEFAULT_CHARACTER;
	}
}
 ?>
