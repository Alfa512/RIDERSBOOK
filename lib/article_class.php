<?php 
require_once "global_class.php";

class Article extends GlobalClass {
	public function __construct($db)
	{
		parent::__construct("articles", $db);
	}
	
	public function getAllSortDate()
	{
		//echo $this->getAll("date", false);
		/*$temp = $this->getAll("date", false);
		echo $temp[1]["title"]."<br />";
		echo $temp[1]["date"]."<br />";
		echo $temp[0]["title"]."<br />";
		echo $temp[0]["date"]."<br />";*/
		
		return  $this->getAll("date", false);
	}
	
	public function getAllOnSectionID($section_id)
	{
		return $this->getAllOnField("section_id", $section_id, "date", false);
	}
}

?>