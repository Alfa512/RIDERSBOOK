<?php 
require_once "modules_class.php";

class FriendsPageContent extends Modules
{
	private $articles;
	private $friends;	
	private $page;
	
	public function __construct($db)
	{
		parent::__construct($db);
		//$this->articles = $this->article->getAllSortDate();
		$this->friends = $this->friend->getAllMyFriends($this->user_info["id"]);
		//echo $this->user->getAllUsers();
		$this->page = (isset($this->data["page"]))? $this->data["page"]: 1;
	}
	
	protected function getTitle()
	{
		if ($_SESSION["login"])
		{
			if ($this->page > 1) return "Мои друзья. Страница ".$this->page;
			else return "Мои друзья";
		}
		else return "Добро пожаловать на Ridersbook!";
	}
	
	protected function getDescription()
	{
		if ($_SESSION["login"]) return "Ridersbook. Мои друзья";
		else return "Ridersbook";
	}
	
	protected function getKeyWords()
	{
		return "ridersbook";
	}
	
	protected function getTop()
	{
		if ($_SESSION["login"]) return "<h1>Друзья</h1>";
	}
	
	protected function getMiddle()
	{
		//echo $this->articles;
		//echo $this->people;
		//return $this->getBlogArticles($this->articles, $this->page);
		//return $this->getPeople($this->$people, $this->$page);
		//var_dump($this->people);
		return $this->getPeople($this->friends, $this->page);
	}
	
	protected function getBottom()
	{
		return $this->getPagination(count($this->friends), $this->config->count_blog, $this->config->address);
	}
}
?>