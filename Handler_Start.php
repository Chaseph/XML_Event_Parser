<?

class Handler_Start extends Parse_Handler
{
	public function end($tag_tree)
	{
		return self::NO_ACTION;
	}

	public function start($tag_tree, $attributes)
	{
		return $this->make_event_object($tag_tree, $attributes, Event_Data::ATTRIBUTES);
	}
}
