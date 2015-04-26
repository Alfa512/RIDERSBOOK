<?php 
	
	require_once "config_class.php";
	require_once "article_class.php";
	require_once "section_class.php";
	require_once "user_class.php";
	require_once "menu_class.php";
	require_once "banner_class.php";
	require_once "message_class.php";
	require_once "image_class.php";
	require_once "user_image_class.php";
	require_once "posts_class.php";
	require_once "post_images_class.php";
	require_once "friends_class.php";
	
	abstract class Modules
	{
		protected $config;
		protected $article;
		protected $section;
		protected $user;
		protected $menu;
		protected $banner;
		protected $message;
		protected $data;
		protected $user_info;
		protected $image;
		protected $user_image;
		protected $posts;
		protected $post_images;
		protected $friend;
		
		
		public function __construct($db)
		{
			session_start();
			$this->config = new Config($db);
			$this->article = new Article($db);
			$this->section = new Section($db);
			$this->user = new User($db);
			$this->menu = new Menu($db);
			$this->banner = new Banner($db);
			$this->message = new Message($db);
			
			$this->data = $this->secureData($_GET);
			$this->user_info = $this->getUser();
			$this->image = new Image($db);
			$this->user_image = new UserImage($db);
			$this->posts = new Posts($db);
			$this->post_images = new PostImages($db);
			$this->friend = new UserFriends($db);
		}
		
		private function getUser()
		{
			$login = $_SESSION["login"];
			$password = $_SESSION["password"];
			if ($this->user->checkUser($login, $password)) return $this->user->getUserOnLogin($login);
			else return false;
		}
		
		//Получение контента со страниц
		public function getContent()
		{
			$sr["title"] = $this->getTitle();
			//echo $sr["title"];
			$sr["meta_desc"] = $this->getDescription();
			$sr["meta_key"] = $this->getKeyWords(); 
			$sr["menu"] = $this->getMenu(); //Общий
			//echo $sr["menu"];
			$sr["auth_user"] = $this->getAuthUser(); //Общий
			$sr["banners"] = $this->getBanners(); //Общий
			$sr["top"] = $this->getTop(); //Общий
			$sr["middle"] = $this->getMiddle();
			$sr["bottom"] = $this->getBottom();  //Общий
			return $this->getReplaceTemplate($sr, "main");
		}
		
		abstract protected function getTitle();
		abstract protected function getDescription();
		abstract protected function getKeyWords();
		abstract protected function getMiddle();
		
		protected function getMenu()
		{
			$menu = $this->menu->getAll();
			$text = "";
			for ($i = 0; $i < count($menu); $i++)
			{
				$sr["title"] = $menu[$i]["title"];
				$sr["link"] = $menu[$i]["link"];
				$text .= $this->getReplaceTemplate($sr, "menu_item");
			}
			return $text;
		}
		
		protected function getAuthUser()
		{
			if ($this->user_info)
			{
				$sr["username"] = $this->user_info["login"];
				return $this->getReplaceTemplate($sr, "user_panel");
			}
			if ($_SESSION["error_auth"] == 1)
			{
				$sr["message_auth"] = $this->getMessage("ERROR_AUTH");
				unset($_SESSION["error_auth"]);
			}
			else $sr["message_auth"] = "";
			return $this->getReplaceTemplate($sr, "form_auth");
		}
		
		protected function getBanners()
		{
			$banners = $this->banner->getAll();
			$text = "";
			for ($i = 0; $i < count($banners); $i++)
			{
				$sr["code"] = $banners[$i]["code"];
				$text .= $this->getReplaceTemplate($sr, "banner");
			}
			return $text;
		}
		
		protected function getTop()
		{
			return "";
		}
		
		protected function getBottom()
		{
			return "";
		}
		
		private function secureData($data)
		{
			foreach($data as $key => $value)
			{
				if (is_array($value)) $this->secureData($value);
				else $data[$key] = htmlspecialchars($value);
			}
			return $data;
		}
		
		protected function getBlogArticles($articles, $page) //Получение статей для страниц
		{
			//echo $articles;
			//echo $page;
			$start = ($page - 1) * $this->config->count_blog;
			$end = (count($articles) > $start + $this->config->count_blog)? $start + $this->config->count_blog: count($articles);
			$text = "";
			for ($i = $start; $i < $end; $i++)
			{
				$sr["title"] = $articles[$i]["title"];
				$sr["intro_text"] = $articles[$i]["intro_text"];
				//$sr["date"] = $this->formatDate($articles[$i]["date"]);
				$sr["date"] = $articles[$i]["date"];
				//echo $articles[$i]["date"]."<br />";
				//$temp = $this->formatDate($articles[$i]["date"]);
				//echo $temp;
				$sr["link_article"] = $this->config->address."?view=article&amp;id=".$articles[$i]["id"];
				$text .= $this->getReplaceTemplate($sr, "article_intro");
				//echo $text;
			}
			return $text;
		}
		
		protected function getPeople($people, $page)
		{
			//var_dump ($people);
			$start = ($page - 1) * $this->config->count_blog;
			$end = (count($people) > $start + $this->config->count_blog)? $start + $this->config->count_blog: count($people);
			$text = "";
			for ($i = $start; $i < $end; $i++)
			{
				$sr["name"] = $people[$i]["name"];
				$sr["userlink"] = "?user=".$people[$i]["login"];
				$sr["last_name"] = $people[$i]["last_name"];
				$sr["gender"] = $people[$i]["gender"];
				$sr["DOB"] = $people[$i]["DOB"];
				$sr["e_mail"] = $people[$i]["e_mail"];
				$sr["changelink"] = $this->getReplaceTemplate("", "void");
				$imgid["imagelink"] = $this->config->dir_usersimg.$this->user_image->getUserProfileImage($people[$i]["id"]);
				$imgid["alt"] = "";
				//echo $this->getReplaceTemplate($imgid, "user_profileimage");
				$sr["profile_img"] = $this->getReplaceTemplate($imgid, "user_profileimage");
				//var_dump($user);
				//return $this->getReplaceTemplate($temp, "user_profiletitle").$this->getReplaceTemplate($user, "user_profilepage");
				$text .= $this->getReplaceTemplate($sr, "search_item_people");
			}
			//echo $text;
			
			$new_sr["search_items"] = $text;
			//return $this->getReplaceTemplate($new_sr, "search_result");
			return $text;
		}
		
		protected function formatDate($date)
		{
			//echo "f".date('Y' , $date)."<br />";
			//echo "wf".$date."<br />";
			//return date('Y-m-d H:i:s' , $date);
			return date("Y-m-d H:i:s", $date);
		}
		
		protected function getMessage($message = "")
		{
			if ($message == "")
			{
				$message = $_SESSION["message"];
				unset($_SESSION["message"]);
			}
			$sr["message"] = $this->message->getText($message);
			return $this->getReplaceTemplate($sr, "message_string");
		}
		
		protected function getPagination($count, $count_on_page, $link) //Распределение по страницам
		{
			$count_pages = ceil($count / $count_on_page);
			$sr["number"] = 1;
			$sr["link"] = $link;
			$sym = (strpos($link, "?") !== false)? "&amp;": "?";
			$pages = $this->getReplaceTemplate($sr, "number_page");
			for ($i = 2; $i <= $count_pages; $i++)
			{
				$sr["number"] = $i;
				$sr["link"] = $link.$sym."page=$i";
				$pages .= $this->getReplaceTemplate($sr, "number_page");
			}
			
			$els["number_pages"] = $pages;
			return $this->getReplaceTemplate($els, "pagination");
		}
		
		protected function getImagesByUserID($id)
		{
			$images_id = $this->db->select("userphoto", "id_photo", "id_user = $id");
			//return $this->db->select("images", "content", "id = $image_id");
			return $images_id;
		}
		
		protected function getUserProfileImageID($id)
		{
			//$image_id = $this->db->select("userphoto", "id_photo", "id_user = $id AND (is_profile != 0 OR is_profile NOT NULL)");
			$image_id = $this->user_image->getUserProfileImage($id);
			//return $this->db->select("images", "content", "id = $image_id");
			return $image_id;
		}
		
		protected function getTemplate($name)
		{
			$text = file_get_contents($this->config->dir_tmpl.$name.".tpl");
			return str_replace("%address%", $this->config->address, $text); //
		}
		
		protected function getReplaceTemplate($sr, $template)
		{
			return $this->getReplaceContent($sr, $this->getTemplate($template));
		}
		
		private function getReplaceContent($sr, $content)
		{
			$search = array();
			$replace = array();
			$i = 0;
			foreach ($sr as $key => $value)
			{
				$search[$i] = "%$key%";
				//echo $search[$i]."<br />";
				$replace[$i] = $value;
				//echo $search[$i]."<br />";
				//echo $replace[$i]."<br />";
				
				$i++;
			}
			return str_replace($search, $replace, $content);
		}
	}

?>