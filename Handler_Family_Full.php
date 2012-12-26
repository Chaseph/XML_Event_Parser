<?

class Handler_Family_Full extends Parse_Handler
{
	public function end($tag_tree)
	{
		if ($this->family_tag === $tag_tree) {
			return $this->make_event_object($tag_tree, $this->family, Event_Data::FULL_DATA);
		} else {
			$element_data = $this->get_element_data();
			if (!empty($element_data)) {
				$this->family[$tag_tree] = $element_data;
			}
			return self::NO_ACTION;
		}
	}

	public function start($tag_tree, $attributes)
	{
		$element_data = $this->get_element_data();
		if (!empty($element_data)) {
			$this->family[$tag_tree] = $element_data;
		}
		return self::NO_ACTION;
	}

	private $family;
	private $family_tag;
}
