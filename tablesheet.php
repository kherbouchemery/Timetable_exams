<?php
	session_start();
	include_once('class.database.php');

	$db_connection = new dbConnection();
    $link = $db_connection->connect();
	$user_id= $_SESSION['user_id'];
	$timetable_id= $_POST['id'];

	$query = $link->query("SELECT * FROM timetable,course,groupe WHERE timetable_id = $timetable_id AND timetable.course_id = course.course_id AND groupe_id = groupe.id AND groupe.course_id = course.course_id");
  	$query->setFetchMode(PDO::FETCH_ASSOC);
   
   	if($result = $query->fetch()){
   		$course = $result['course_id'];
   		$section = $result['section'];
   		$groupe = $result['id'];
   	}
	
	// INSERT / UPDATE
   	if($_SESSION['name'] && !empty($_POST['cellid']) && !empty($_POST['subjectID']) && empty($_POST['deleteCell']))
	{
		// insertion d'un cours dans une cellule qui est remplis par un TD dans la meme section/promo mais dans une autre table (erreur!)
		if($_POST['type_seance'] == 'Cours'){
			$query = $link->query("SELECT * FROM tablesheet,timetable,groupe WHERE cell = ".$_POST['cellid']." AND tablesheet.timetable_id = timetable.timetable_id AND timetable.course_id = $course AND timetable.groupe_id = groupe.id AND groupe.section = '$section' AND timetable.user_id = tablesheet.user_id AND timetable.user_id = '$user_id'");
		   	$query->setFetchMode(PDO::FETCH_ASSOC);
		   	if($query->fetch()){
		   		echo 'error:Il y a un TD à ce moment là';
			   	return;
		   	}
	   	}

	   	$query = $link->query("SELECT * FROM tablesheet WHERE timetable_id =  '$timetable_id' AND cell ='".$_POST['cellid']."' AND user_id = '$user_id'");
	   	$query->setFetchMode(PDO::FETCH_ASSOC);
	   	$tdExists = $query->fetch()?1:0;

   		$query = $link->query("SELECT * FROM cours_communs WHERE course_id =  $course AND section = '$section' AND cell ='".$_POST['cellid']."' AND user_id = '$user_id'");
	   	$query->setFetchMode(PDO::FETCH_ASSOC);
	   	$coursExists = $query->fetch()?1:0;

	   // update
	   if($tdExists + $coursExists){

	   		// checking for ambiguity...
			// Teacher...
		   	$query = $link->query("SELECT * FROM tablesheet WHERE cell = ".$_POST['cellid']." AND teacher_id = ".$_POST['teacherID']." AND timetable_id <> '$timetable_id'");
		  	$query->setFetchMode(PDO::FETCH_ASSOC);
		   
		   	if($result = $query->fetch()){
		   		echo 'error:Enseignant a déja une séance a ce moment là';
		   		return;
		   	}

		   	$query = $link->query("SELECT * FROM cours_communs WHERE cell = ".$_POST['cellid']." AND teacher_id = ".$_POST['teacherID']." AND course_id <> $course AND section <> '$timetable_id'");
		  	$query->setFetchMode(PDO::FETCH_ASSOC);
		   
		   	if($result = $query->fetch()){
		   		echo 'error:Enseignant a déja une séance a ce moment là';
		   		return;
		   	}

		   	// Classroom...
		   	$query = $link->query("SELECT * FROM tablesheet WHERE cell = ".$_POST['cellid']." AND classroom_id = ".$_POST['classroomID']." AND timetable_id <> '$timetable_id'");
		  	$query->setFetchMode(PDO::FETCH_ASSOC);
		   
		   	if($result = $query->fetch()){
		   		echo 'error:Salle Occupé';
		   		return;
		   	}

		   	$query = $link->query("SELECT * FROM cours_communs WHERE cell = ".$_POST['cellid']." AND classroom_id = ".$_POST['classroomID']." AND course_id <> $course AND section <> '$timetable_id'");
		  	$query->setFetchMode(PDO::FETCH_ASSOC);
		   
		   	if($result = $query->fetch()){
		   		echo 'error:Salle Occupé';
		   		return;
		   	}



		   	if($_POST['type_seance'] != 'Cours'){
		   		// verification d'ambiguité avec un cours (insertion d'un cours dans un groupe, et em meme temps un td dans l'autre groupe)
		   		$query = $link->query("SELECT * FROM cours_communs WHERE cell = ".$_POST['cellid']." AND course_id = $course AND section = '$section'");
			  	$query->setFetchMode(PDO::FETCH_ASSOC);

			   	if($result = $query->fetch()){
			   		echo 'error:Il y a un cours à ce moment là';
			   		return;
			   	}


				$query = $link->prepare("UPDATE tablesheet SET subject_id = ?, teacher_id = ?, classroom_id = ?, type_seance = ? WHERE timetable_id = '$timetable_id' AND cell ='".$_POST['cellid']."' AND user_id = '$user_id'");
				$values = array ($_POST['subjectID'], empty($_POST['teacherID'])?"":$_POST['teacherID'], empty($_POST['classroomID'])?"":$_POST['classroomID'], empty($_POST['type_seance'])?"":$_POST['type_seance']);
			}else{
				$query = $link->prepare("UPDATE cours_communs SET subject_id = ?, teacher_id = ?, classroom_id = ? WHERE course_id = $course AND section = '$section' AND cell ='".$_POST['cellid']."' AND user_id = '$user_id'");
				$values = array ($_POST['subjectID'], empty($_POST['teacherID'])?"":$_POST['teacherID'], empty($_POST['classroomID'])?"":$_POST['classroomID']);
			}
			if($query->execute($values)){
				echo "Successful";
			}else
				echo "error";
		}
		// insert
		else if(!empty($_POST['teacherID']) && !empty($_POST['subjectID']) && !empty($_POST['classroomID'])){
			// checking for ambiguity...
			// Teacher...
		   	$query = $link->query("SELECT * FROM tablesheet WHERE cell = ".$_POST['cellid']." AND teacher_id = ".$_POST['teacherID']);
		  	$query->setFetchMode(PDO::FETCH_ASSOC);
		   
		   	if($result = $query->fetch()){
		   		echo 'error:Enseignant a déja une séance a ce moment là';
		   		return;
		   	}

		   	$query = $link->query("SELECT * FROM cours_communs WHERE cell = ".$_POST['cellid']." AND teacher_id = ".$_POST['teacherID']);
		  	$query->setFetchMode(PDO::FETCH_ASSOC);
		   
		   	if($result = $query->fetch()){
		   		echo 'error:Enseignant a déja une séance a ce moment là';
		   		return;
		   	}

		   	// Classroom...
		   	$query = $link->query("SELECT * FROM tablesheet WHERE cell = ".$_POST['cellid']." AND classroom_id = ".$_POST['classroomID']);
		  	$query->setFetchMode(PDO::FETCH_ASSOC);
		   
		   	if($result = $query->fetch()){
		   		echo 'error:Salle Occupé';
		   		return;
		   	}

		   	$query = $link->query("SELECT * FROM cours_communs WHERE cell = ".$_POST['cellid']." AND classroom_id = ".$_POST['classroomID']);
		  	$query->setFetchMode(PDO::FETCH_ASSOC);
		   
		   	if($result = $query->fetch()){
		   		echo 'error:Salle Occupé';
		   		return;
		   	}


		   	

		   	if($_POST['type_seance'] != 'Cours'){
				$query = $link->prepare("INSERT INTO tablesheet(`cell`,`subject_id`, `timetable_id`, `user_id`, teacher_id, classroom_id, type_seance) VALUES(?,?,?,?,?,?,?)");
				$values = array ($_POST['cellid'],$_POST['subjectID'], $timetable_id, $user_id, $_POST['teacherID'], $_POST['classroomID'], $_POST['type_seance']);
			}else{
				$query = $link->prepare("INSERT INTO cours_communs(course_id, section, cell,subject_id, user_id, teacher_id, classroom_id) VALUES(?,?,?,?,?,?,?)");
				$values = array ($course, $section, $_POST['cellid'], $_POST['subjectID'], $user_id, $_POST['teacherID'], $_POST['classroomID']);
			}

			if($query->execute($values)){
				echo "Successful";
			}
	   }
	}
	// RESET ALL
	else if($_SESSION['name'] && !empty($_POST['deleteAll'])){
		$query = $link->prepare("DELETE FROM tablesheet WHERE timetable_id =  '$timetable_id' AND user_id = '$user_id'");
		$query->execute();

		$query = $link->prepare("DELETE FROM cours_communs WHERE course_id = $course AND section = '$section' AND user_id = '$user_id'");
		$query->execute();

		$rowCount = $query->rowCount();
		if($rowCount){
			echo "Successful";
		}
	}
	// DELETE
	else if($_SESSION['name'] && !empty($_POST['cellid']) && (empty($_POST['teacherID']) && empty($_POST['subjectID']) && empty($_POST['classroomID']))){
		$query = $link->prepare("DELETE FROM tablesheet WHERE timetable_id =  '$timetable_id' AND cell ='".$_POST['cellid']."' AND user_id = '$user_id'");
		$query->execute();

		$query = $link->prepare("DELETE FROM cours_communs WHERE course_id = $course AND section = '$section' AND cell ='".$_POST['cellid']."' AND user_id = '$user_id'");
		$query->execute();

		$rowCount = $query->rowCount();
		if($rowCount){
			echo "Successful";
		}
	}

	


?>




















































