<?php 

require_once "config_class.php";
require_once "user_class.php";
require_once "image_class.php";
require_once "user_image_class.php";
require_once "posts_class.php";
require_once "post_images_class.php";

class Manage {
	
	private $config;
	private $user;
	private $data;
	protected $image;
	protected $user_image;
	protected $posts;
	protected $post_images;
	protected $user_info;
	
	
	public function __construct($db)
	{
		session_start();
		$this->config = new Config();
		$this->user = new User($db);
		$this->data = $this->secureData(array_merge($_POST, $_GET));
		$this->image = new Image($db);
		$this->user_image = new UserImage($db);
		$this->posts = new Posts($db);
		$this->post_images = new PostImages($db);
		$this->user_info = $this->user->getUserOnLogin($_SESSION["login"]);
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
	
	public function redirect($link)
	{
		header("Location: $link");
		exit;
	}
	
	public function regUser()
	{
		$link_reg = $this->config->address."?view=reg";
		$captcha = $this->data["captcha"];
		if (($_SESSION["rand"] != $captcha) && ($_SESSION["rand"] != ""))
		{
			return $this->returnMessage("ERROR_CAPTCHA", $link_reg);
		}
		$login = $this->data["login"];
		if ($this->user->isExistsUser($login)) return $this->returnMessage("EXISTS_LOGIN", $link_reg);
		$password = $this->data["password"];
		if ($password == "") return $this->unknownMessage($link_reg);
		$password = $this->hashPassword($password);
		$result = $this->user->addUser($login, $password, time(), "");
		if ($result) return $this->returnPageMessage("SUCCESS_REG", $this->config->address."?view=message");
		else return $this->unknownMessage($link_reg);
	}
	
	public function login()
	{
		$login = $this->data["login"];
		$password = $this->data["password"];
		$password = $this->hashPassword($password);
		$r = $_SERVER["HTTP_REFERER"];
		if ($this->user->checkUser($login, $password))
		{
			//echo "I'm here!";
			$_SESSION["login"] = $login;
			$_SESSION["password"] = $password;
			return $r;
		}
		else 
		{
			//echo "I'm there!";
			$_SESSION["error_auth"] = 1;
			return $r;
		}
	}
	
	public function logout()
	{
		unset($_SESSION["login"]);
		unset($_SESSION["password"]);
		return $_SERVER["HTTP_REFERER"];
	}
	
	private function hashPassword($password)
	{
		return md5($password.$this->config->secret);
	}
	
	private function unknownMessage($r)
	{
		return $this->returnMessage("UNKNOWN_ERROR", $r);
	}
	
	private function returnMessage($message, $r)
	{
		$_SESSION["message"] = $message;
		return $r;
	}
	
	private function returnPageMessage($message, $r)
	{
		$_SESSION["page_message"] = $message;
		return $r;
	}
	
	public function changeProfileInfo($data)
	{
		$temp["name"] = $data["name"];
		$temp["last_name"] = $data["last_name"];
		$temp["gender"] = $data["gender"];
		$temp["DOB"] = $data["DOB"];
		$temp["e_mail"] = $data["e_mail"];
		$currUser = $this->user->getUserOnLogin($_SESSION["login"]);
		$this->user->editUserProfile($currUser["id"], $temp);
		return $this->config->address."?view=profile";
	}
	
	public function loadFile($_file, $type = NULL)
	{
		if (!$type) return false;
		//var_dump($_file);
		//echo "<br />";
		// Если файл загружен успешно, то проверяем - графический ли он
		//var_dump($_file);
		//echo "<br />";
		if( substr($_file['type'], 0, 5)=='image' ) 
		{
			//var_dump($_file);
			// Читаем содержимое файла
			
			$file_name = "";
			// Экранируем специальные символы в содержимом файла
			//$image = mysql_escape_string( $image );
			// Формируем запрос на добавление файла в базу данных
			//$query="INSERT INTO `images` VALUES(NULL, '".$image."')";
			// После чего остается только выполнить данный запрос к базе данных
			//mysql_query( $query );
			//var_dump($file_name);
			if ($type == "post_file")
			{
				$file_name = $this->post_images->getLastUserPostImage($this->posts->getLastUserPost($this->user_info["id"]));
				//var_dump($file_name);

				if (!$file_name) $file_name = $this->user_info["login"]."post".(int)($this->posts->getLastUserPost($this->user_info["id"]) + 1);
				$file_name = $file_name.".jpg";
				$_file['name'] = $file_name;
				//$image = file_get_contents( $_file['tmp_name'] );
				$image = $_file['name'];
				$newImage_id = $this->image->addImage($file_name);
				//$this->writeFileOnServer($image, $this->config->dir_postsimg.$file_name.".jpg");
				$this->writeFileOnServer($_file['tmp_name'], $this->config->dir_postsimg.$_file['name']);
				return $newImage_id;
			}
			elseif ($type == "userpofile_file")
			{
				$file_name = $this->user_image->getLastUserImage($this->user_info["id"]) + 1;
				var_dump($file_name);
				if (!$file_name) $file_name = $this->user_info["login"]."photo"."1";
				else
				{
					$query["is_profile"] = 0;
					$where["is_profile"] = 1;
					/*var_dump($this->user_info["id"]);
					echo "<br />";
					var_dump($query);
					echo "<br />";
					var_dump($where);
					echo "<br />";*/
					$this->user_image->__editUserImage($this->user_info["id"], $query, $where);
					$file_name = $this->user_info["login"]."photo".$file_name;
				}
				$file_name = $file_name.".jpg";
				//$this->user_image->addUserImage($this->user_info["id"], $file_name, 1);
				$_file['name'] = $file_name;
				//$image = $_file['name'];
				$this->user_image->addUserImage($this->user_info["id"], $file_name, 1);
				$this->writeFileOnServer($_file['tmp_name'], $this->config->dir_usersimg.$_file['name']);
				return true;
			}
		}
		return false;
		
	}
	
	public function writeFileOnServer($file, $path)
	{
		copy($file, $path);
	}
}
?>