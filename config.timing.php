<?php
	session_start();
	include_once('../class.database.php');

	$db_connection = new dbConnection();
    $link = $db_connection->connect();
	$user_id= $_SESSION['user_id'];
	
	// INSERT...
   if(!empty($_POST['addSession']) && !empty($_POST['course']))
	{
		// check for ambiguity
	   	$query = $link->query("SELECT * FROM timing WHERE seance =  '".$_POST['addSession']."' AND user_id = '$user_id' AND course_id = ".$_POST['course']);

	   	$query->setFetchMode(PDO::FETCH_ASSOC);
	   
	   	if($result = $query->fetch()){
		  	echo "error:Séance existe déja";
		  	return;
	   	}

	   	$query = $link->prepare("INSERT INTO timing(user_id, seance, course_id) VALUES(?,?,?)");
		$values = array ($user_id, $_POST['addSession'], $_POST['course']);
		if($query->execute($values)){
			echo $link->lastInsertId();
		}
	}

	// UPDATE...
	else if(!empty($_POST['editSession']))
	{
	   	$query = $link->prepare("UPDATE timing SET seance = ? WHERE id = ? AND user_id = '$user_id'");
		$values = array ($_POST['editSession'], $_POST['sID']);
		
		echo $query->execute($values)?'success':'error';
	}

	// DELETE...
	else if(!empty($_POST['deleteSession']))
	{
	   	$query = $link->prepare("DELETE FROM timing WHERE id = ? AND user_id = '$user_id'");
		$values = array ($_POST['deleteSession']);
		
		echo $query->execute($values)?'success':'error';
	}

	// SELECT...
	else if(!empty($_POST['getSessions']))
	{
	   	$query = $link->query("SELECT * FROM timing WHERE user_id = '$user_id' AND course_id = ".$_POST['course']." ORDER BY id");
		
		$query->setFetchMode(PDO::FETCH_ASSOC);
	   	
		$s = '';

	   	while($result = $query->fetch())
	   		$s .= $result['id'].'|'.$result['seance'].'\n'; // 1|08:30 - 10:00\n2|10:30 - 10:00\n
	   	echo $s;
	}

	// SAVE SORT...
	else if(!empty($_POST['saveSort']) && !empty($_POST['course']))
	{
		// 1. DELETE EXISTING RECORDS...
		$query = $link->prepare("DELETE FROM timing WHERE user_id = '$user_id' AND course_id = ".$_POST['course']);
		
		if(!$query->execute($values)){
			echo 'error';
			return;
		}

		// 2. RE-INSERT NEW RECORDS...
		$rows = explode('<br/>', $_POST['saveSort']);
		$state = 'success';
		foreach($rows as $s){
			if(!$s)
				continue;
			$query = $link->prepare("INSERT INTO timing(user_id, seance, course_id) VALUES(?,?,?)");
			$values = array ($user_id, $s, $_POST['course']);
			if(!$query->execute($values))
				$state = 'error';
		}
		echo $state;
	}



	// DELETE Timing....
	else if(!empty($_POST['deleteTiming']) && !empty($_POST['course']))
	{
	   	$query = $link->prepare("DELETE FROM timing WHERE course_id = ? AND user_id = '$user_id'");
		$values = array ($_POST['course']);
		
		echo $query->execute($values)?'success':'error';
	}
?>

