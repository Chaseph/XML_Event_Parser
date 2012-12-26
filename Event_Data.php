<?

class Event_Data
{
	const ATTRIBUTES = '4';
	const FULL_DATA = '6';
	const INNER_DATA = '2';

	public function __construct($tag_tree, $data, $type = self::INNER_DATA)
	{
		$this->tag_tree = $tag_tree;
		$this->data = $data;
		$this->type = $type;
	}

	public function data()
	{
		return $this->data;
	}

	public function tag_tree()
	{
		return $this->tag_tree;
	}

	public function type()
	{
		return $this->type;
	}

	private $data;
	private $tag_tree;
	private $type;
}
