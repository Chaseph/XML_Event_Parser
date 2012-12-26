<?

class Handler_Family_End extends Parse_Handler
{
	public function __construct($tag_tree)
	{
		$this->family_tag = $tag_tree;
		$this->family = array();
	}

	public function end($tag_tree)
	{
		if ($this->family_tag === $tag_tree) {
			return $this->make_event_object($tag_tree, $this->family, Event_Data::INNER_DATA);
		} else {
			$element_data = $this->get_element_data();
			if ($element_data{0}) {
				$this->family[$tag_tree] = $element_data;
			}
			return self::NO_ACTION;
		}
	}

	public function start($tag_tree, $attributes)
	{
		return self::NO_ACTION;
	}

	private $family;
	private $family_tag;
}