<?

class Handler_Full extends Parse_Handler
{
	public function end($tag_tree)
	{
		return $this->make_event_object($tag_tree, $this->get_element_data(), Event_Data::INNER_DATA);
	}

	public function start($tag_tree, $attributes)
	{
		return $this->make_event_object($tag_tree, $attributes, Event_Data::ATTRIBUTES);
	}
}
