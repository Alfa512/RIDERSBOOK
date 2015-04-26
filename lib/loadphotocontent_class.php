<?php 
require_once "modules_class.php";

class LoadPhoto extends Modules
{
	private $words;
	//private $page;
	
	public function __construct($db)
	{
		parent::__construct($db);
		//$this->words = $this->data["words"];
	}
	
	protected function getTitle()
	{
		return "Загрузка фото";
	}
	
	protected function getDescription()
	{
		return "Загрузка фото";
	}
	
	protected function getKeyWords()
	{
		return "";
	}
	
	protected function getTop()
	{
		//return $this->getTemplate("main_article");
	}
	
	protected function getMiddle()
	{
		
	}
	
	protected function getBottom()
	{
		//return $this->getPagination(count($this->articles), $this->config->count_blog, $this->config->address);
	}
}
?>