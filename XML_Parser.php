<?php

include_once(dirname(__FILE__) . '/Parse_Handler.php');
include_once(dirname(__FILE__) . '/Event_Data.php');

class XML_Parser
{
	const PARSER_END = 'Handler_End';
	const PARSER_FAMILY_END = 'Handler_Family_End';
	const PARSER_FAMILY_FULL = 'Handler_Family_Full';
	const PARSER_FULL = 'Handler_Full';
	const PARSER_START = 'Handler_Start';

	public function __construct(&$event_handler_object, $handler_object_function)
	{
		$this->set_handler(self::PARSER_FULL);
		$this->event_handler_object = $event_handler_object;
		$this->handler_object_function = $handler_object_function;
	}

	public function __destruct()
	{
		unset($this->parse_handler);
	}

	public function character_data($parser, $char)
	{
		$this->parse_handler->character_data($char);
	}

	public function end($parser, $tag_name)
	{
		$data = $this->parse_handler->end($this->get_end_tag_tree());
		if ($data != Parse_Handler::NO_ACTION) {
			$this->event_handler_object->{$this->handler_object_function}($data);
		}

		$this->parse_handler->clear_element_data();
		$this->tag_tree_pop();
	}

	public function parse($input_file, &$progress_reporter = null, $progress_reporter_func = null)
	{
		$report = $this->verify_progress_reporter($progress_reporter, $progress_reporter_func);
		$xml_parser = $this->make_xml_parser();
		$parse_file = fopen($input_file, 'r');

		if ($report) {
			$bytes_to_process = filesize($input_file);
			$progress_reporter->$progress_reporter_func(0, $bytes_to_process);
		}

		while ($data = fread($parse_file, $this->parse_chunk_size())) {
			xml_parse($xml_parser, $data, feof($parse_file));

			if ($report) {
				$progress = ftell($parse_file);
				$progress_reporter->$progress_reporter_func($progress, $bytes_to_process);
			}
		}
		xml_parser_free($xml_parser);
		return true;
	}

	public function set_handler($handler_class)
	{
		if (class_exists($handler_class)) {
			$this->parse_handler = new $handler_class();
		} else {
			$class_file = dirname(__FILE__).'/class.'.$handler_class.'.php';
			if (file_exists($class_file)) {
				include($class_file);
				$this->parse_handler = new $handler_class();
			} else {
				throw new Exception(__LINE__.':'.__CLASS__." :: Handler ".$handler_class." does not exist.");
			}
		}
	}

	public function start($parser, $tag_name, $attributes)
	{
		$this->tag_tree_push($tag_name);

		$data = $this->parse_handler->start($this->get_tag_tree(), $attributes);
		if ($data != Parse_Handler::NO_ACTION) {
			$this->event_handler_object->{$this->handler_object_function}($data);
		}
	}

	private function get_end_tag_tree()
	{
		return $this->get_tag_tree().'.';
	}

	private function get_tag_tree()
	{
		return implode(' ', $this->tag_tree);
	}

	private function make_xml_parser()
	{
		$xml_parser = xml_parser_create();
		xml_set_object($xml_parser, $this);
		xml_set_element_handler($xml_parser, 'start', 'end');
		xml_set_character_data_handler($xml_parser, 'character_data');
		return $xml_parser;
	}

	private function parse_chunk_size()
	{
		$megabyte = 1024 * 1024;
		return 4*$megabyte;
	}

	private function tag_tree_pop()
	{
		return array_pop($this->tag_tree);
	}

	private function tag_tree_push($tag)
	{
		$this->tag_tree[] = strtolower($tag);
	}

	private function verify_progress_reporter($progress_reporter, $progress_reporter_func)
	{
		if (is_object($progress_reporter) && method_exists($progress_reporter, $progress_reporter_func)) {
			return true;
		} else {
			return false;
		}
	}

	private $event_handler_object;
	private $handler_object_function;
	private $parse_handler;
	private $tag_tree;
}