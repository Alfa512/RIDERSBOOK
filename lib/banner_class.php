<?php 
require_once "global_class.php";

class Banner extends GlobalClass {
	public function __construct($db)
	{
		parent::__construct("banners", $db);
	}
	
	/*public function getAllSortDate()
	{
		return  $this->getAll("date", false);
	}
	
	public function getAllOnSectionID($section_id)
	{
		return $this->getAllOnField("section_id", $section_id, "date", false);
	}*/
}

?>