<?php 
require_once "modules_class.php";

class FrontPageContent extends Modules
{
	private $articles;
	private $page;
	
	public function __construct($db)
	{
		parent::__construct($db);
		$this->articles = $this->article->getAllSortDate();
		$this->page = (isset($this->data["page"]))? $this->data["page"]: 1;
	}
	
	protected function getTitle()
	{
		if ($this->page > 1) return "Мои друзья. Страница ".$this->page;
		else return "Мои друзья";
	}
	
	protected function getDescription()
	{
		return "Ridersbook. Мои друзья";
	}
	
	protected function getKeyWords()
	{
		return "ridersbook, friends";
	}
	
	protected function getTop()
	{
		//return $this->getTemplate("main_article");
	}
	
	protected function getMiddle()
	{
		//echo $this->articles;
		//echo $this->page;
		return $this->getBlogArticles($this->articles, $this->page);
	}
	
	protected function getBottom()
	{
		return $this->getPagination(count($this->articles), $this->config->count_blog, $this->config->address);
	}
}
?>