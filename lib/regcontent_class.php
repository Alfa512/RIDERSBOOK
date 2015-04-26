<?php 
require_once "modules_class.php";

class RegContent extends Modules
{
	private $articles;
	private $page;
	
	public function __construct($db)
	{
		parent::__construct($db);
	}
	
	protected function getTitle()
	{
		return "Регистрация";
	}
	
	protected function getDescription()
	{
		return "Регистрация нового пользователя. Ridersbook";
	}
	
	protected function getKeyWords()
	{
		return "ridersbook, регистрация сайт, регистрация пользователь сайт";
	}
	
	protected function getTop()
	{
		//return $this->getTemplate("main_article");
	}
	
	protected function getMiddle()
	{
		$sr["message"] = $this->getMessage();
		$sr["login"] = $_SESSION["login"];
		return $this->getReplaceTemplate($sr, "form_reg");
	}
	
	protected function getBottom()
	{
		//return $this->getPagination(count($this->articles), $this->config->count_blog, $this->config->address);
	}
}
?>