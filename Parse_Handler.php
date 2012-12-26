<?php

abstract class Parse_Handler
{
	abstract public function end($tag_tree);

	abstract public function start($tag_tree, $attributes);

	const NO_ACTION = null;

	public function character_data($char)
	{
		if (!ctype_space($char)) {
			$this->element_data .= $char;
		}
	}

	public function clear_element_data()
	{
		$this->element_data = '';
	}

	public function get_element_data()
	{
		return $this->element_data;
	}

	public function make_event_object($tag_tree, $data, $type = Event_Data::INNER_DATA)
	{
		return new Event_Data($tag_tree, $data, $type);
	}

	private $element_data;
}