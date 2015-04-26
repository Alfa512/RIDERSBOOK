<?php 
require_once "modules_class.php";

class FrontPageContent extends Modules
{
	private $articles;
	private $people;	
	private $page;
	
	public function __construct($db)
	{
		parent::__construct($db);
		//$this->articles = $this->article->getAllSortDate();
		$this->people = $this->user->getAllUsers();
		//echo $this->user->getAllUsers();
		$this->page = (isset($this->data["page"]))? $this->data["page"]: 1;
	}
	
	protected function getTitle()
	{
		if ($_SESSION["login"])
		{
			if ($this->page > 1) return "Новости друзей. Страница ".$this->page;
			else return "Новости друзей";
		}
		else return "Добро пожаловать на Ridersbook!";
	}
	
	protected function getDescription()
	{
		if ($_SESSION["login"]) return "Ridersbook. Новости";
		else return "Ridersbook";
	}
	
	protected function getKeyWords()
	{
		return "ridersbook";
	}
	
	protected function getTop()
	{
		if ($_SESSION["login"]) return "<h1>Последние новости</h1>";
	}
	
	protected function getMiddle()
	{
		//echo $this->articles;
		//echo $this->people;
		//return $this->getBlogArticles($this->articles, $this->page);
		//return $this->getPeople($this->$people, $this->$page);
		//var_dump($this->people);
		return $this->getPeople($this->people, $this->page);
	}
	
	protected function getBottom()
	{
		return $this->getPagination(count($this->people), $this->config->count_blog, $this->config->address);
	}
}
?>