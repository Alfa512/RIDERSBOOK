<?php 
	require_once "lib/config_class.php";
	require_once "lib/database_class.php";
	require_once "lib/manage_class.php";
	require_once "lib/modules_class.php";
	require_once "lib/user_image_class.php";
	require_once "lib/posts_class.php";
	require_once "lib/post_images_class.php";
	require_once "lib/user_class.php";
	require_once "lib/friends_class.php";
	
	$db = new DataBase();
	$manage = new Manage($db);
	$user_image = new UserImage($db);
	$posts = new Posts($db);
	$post_images = new PostImages($db);
	$user = new User($db);
	$user_info = $user->getUserOnLogin($_SESSION["login"]);
	$config = new Config();
	$user_friends = new UserFriends($db);
	
	
	//var_dump($_POST);
	//echo "<br />";
	//var_dump($_SESSION);
	//echo "<br />";
	
	if ($_POST["reg"])
	{
		$r = $manage->regUser();
	}
	elseif ($_POST["auth"])
	{
		$r = $manage->login();
	}
	elseif ($_GET["logout"])
	{
		$r = $manage->logout();
	}
	elseif ($_POST["changeok"])
	{
		$r = $manage->changeProfileInfo($_POST);
	}
	elseif ($_POST["loadprofilephoto"])
	{
		foreach($_FILES as $key => $value)
		{
			//var_dump($value);
			// ��������� ������ �� ����
			if( !empty($value['name'] ) ) {
					// ���������, ��� ��� �������� �� ��������� ������
					if ( $value['error'] == 0 ) 
					{
						//var_dump($value);
						$manage->loadFile($value, "userpofile_file");
					}
				}
		}
		$r = "/?view=profile";
	}
	elseif ($_POST["loadphoto"])
	{
		// ��������� ������ �� ����
		if( !empty( $_FILES['image']['name'] ) ) {
			// ���������, ��� ��� �������� �� ��������� �����
			if ( $_FILES['image']['error'] == 0 ) 
		    {
				// ���� ���� �������� �������, �� ��������� - ����������� �� ��
				if( substr($_FILES['image']['type'], 0, 5)=='image' ) 
				{
					// ������ ���������� �����
				    $image = file_get_contents( $_FILES['image']['tmp_name'] );
				    // ���������� ����������� ������� � ���������� �����
				    $image = mysql_escape_string( $image );
				    // ��������� ������ �� ���������� ����� � ���� ������
				    $query="INSERT INTO `images` VALUES(NULL, '".$image."')";
				    // ����� ���� �������� ������ ��������� ������ ������ � ���� ������
				    //mysql_query( $query );
				    $this->db->query($query);
				    $this->manage->user->getUserOnLogin($_SESSION["login"]);
				}
		    }
		}
		$r = $manage->changeProfileInfo($_POST);
	}
	elseif ($_POST["txtArea"])
	{
		//var_dump($_FILES);
		// ��������� ������ �� ����
		//foreach($)
		foreach($_FILES as $key => $value)
		{
			//var_dump($value);
			if( !empty($value['name'] ) ) {
				// ���������, ��� ��� �������� �� ��������� ������
				if ( $value['error'] == 0 ) 
				{
					//var_dump($value);
					$postImage_id = $manage->loadFile($value, "post_file");
					$currPost_id = $posts->addPost($user_info["id"], $_POST["txtArea"]);
					$post_images->addPostImage($currPost_id, $postImage_id);
				}
			}
			else
			{
				
				$postID = $posts->addPost($user_info["id"], $_POST["txtArea"]);
			}
		}
		//$r = $manage->changeProfileInfo($_POST);
		$r = "/?view=profile";
	}
	elseif ($_POST["addtofriend"])
	{
		//var_dump($_POST);
		$friend = $user->getUserOnLogin($_POST["login"]);
		$user_friends->addFriend($user_info["id"], $friend["id"]);
		$r = "/?user=".$_POST["login"];
	}
	else exit;
	
	$manage->redirect($r);
?>