<?php
	session_start();
	include_once('../class.database.php');

	$db_connection = new dbConnection();
    $link = $db_connection->connect();
	$user_id= $_SESSION['user_id'];

	// Faculty
	if(!empty($_POST['updateFaculty']))
	{
	   	$query = $link->prepare("UPDATE faculty SET faculty_code = ?, faculty_name = ? WHERE faculty_id = ? AND user_id = '$user_id'");
		$values = array ($_POST['code'], $_POST['name'], $_POST['id']);

		echo $query->execute($values)?'success':'error';
	}

	// Department
	else if(!empty($_POST['updateDep']))
	{
	   	$query = $link->prepare("UPDATE departement SET nom_dep = ?, faculty_id = ? WHERE id = ?");
		$values = array ($_POST['nom'], $_POST['faculty'], $_POST['id']);
		
		echo $query->execute($values)?'success':'error';
	}

	// Subject
	else if(!empty($_POST['updateSubject']))
	{
	   	$query = $link->prepare("UPDATE subject SET subject_code = ?, subject_name = ?, coeff = ?, credit = ? WHERE subject_id = ? AND user_id = '$user_id'");
		$values = array ($_POST['code'], $_POST['name'], $_POST['coeff'], $_POST['credit'], $_POST['id']);
		
		echo $query->execute($values)?'success':'error';
	}

	// Teacher
	else if(!empty($_POST['updateTeacher']))
	{
		$query = $link->query("SELECT * FROM departement WHERE nom_dep = '".$_POST['dep']."'");
        $query->setFetchMode(PDO::FETCH_ASSOC);
       
       	$dep = "";
        if($result = $query->fetch())
            $dep = $result['id'];

	   	$query = $link->prepare("UPDATE teacher SET last_name = ?, first_name = ?, degre = ?, email = ?, dep = ?, type = ?, tel = ? WHERE id = ?");
		$values = array ($_POST['lName'], $_POST['fName'], $_POST['degre'], $_POST['email'], $dep, $_POST['type'], $_POST['tel'], $_POST['id']);
		
		echo $query->execute($values)?'success':'error';
	}

	// Classroom
	else if(!empty($_POST['updateClassroom']))
	{
	   	$query = $link->prepare("UPDATE classroom SET type_cls = ? WHERE num = ?");
		$values = array ($_POST['type'], $_POST['id']);
		
		echo $query->execute($values)?'success':'error';
	}

	// Course
	else if(!empty($_POST['updateCourse']))
	{
	   	$query = $link->prepare("UPDATE course SET course_name = ?, course_full_name = ?, dep_id = ? WHERE course_id = ? AND user_id = '$user_id'");
		$values = array ($_POST['name'], $_POST['fullname'], $_POST['dep'], $_POST['id']);
		
		echo $query->execute($values)?'success':'error';
	}

	// Groupe
	else if(!empty($_POST['updateGroupe']))
	{
	   	$query = $link->prepare("UPDATE groupe SET name = ?, course_id = ?, section = ? WHERE id = ?");
		$values = array ($_POST['name'], $_POST['course'], $_POST['section'], $_POST['id']);
		
		echo $query->execute($values)?'success':'error';
	}

	// Timetable
	else if(!empty($_POST['updateTimetable']))
	{
	   	$query = $link->prepare("UPDATE timetable SET course_id = ?, groupe_id = ?, year = ?, semester = ?, timing_course_id = ? WHERE timetable_id = ?");
		$values = array ($_POST['course'], $_POST['groupe'], $_POST['year'], $_POST['semester'], $_POST['timing'], $_POST['id']);
		
		echo $query->execute($values)?'success':'error';
	}






	// Get Dep List...
	else if(!empty($_POST['getDep']))
	{
	   	$query = $link->query("SELECT * FROM departement");
	  	$query->setFetchMode(PDO::FETCH_ASSOC);
	   
	   	while($result = $query->fetch())
			echo $result['id'].'|'.$result['nom_dep'].'<br/>';
	}

	// Get Fac List...
	else if(!empty($_POST['getFac']))
	{
	   	$query = $link->query("SELECT * FROM faculty");
	  	$query->setFetchMode(PDO::FETCH_ASSOC);
	   
	   	while($result = $query->fetch())
			echo $result['faculty_id'].'|'.$result['faculty_name'].'<br/>';
	}

	// Get Courses List...
	else if(!empty($_POST['getCourses']))
	{
	   	$query = $link->query("SELECT * FROM course");
	  	$query->setFetchMode(PDO::FETCH_ASSOC);
	   
	   	while($result = $query->fetch())
			echo $result['course_id'].'|'.$result['course_name'].'<br/>';
	}

	// Get Sections List...
	else if(!empty($_POST['getSections']) && !empty($_POST['course']))
	{
	   	$query = $link->query("SELECT section FROM groupe WHERE course_id = ".$_POST['course']." GROUP BY section");
	  	$query->setFetchMode(PDO::FETCH_ASSOC);
	   
	   	while($result = $query->fetch())
			echo $result['section'].'<br/>';
	}

	// Get Groupes List...
	else if(!empty($_POST['getGroupes']) && !empty($_POST['course']) && !empty($_POST['section']))
	{
	   	$query = $link->query("SELECT * FROM groupe WHERE course_id = ".$_POST['course']." AND section = '".$_POST['section']."'");
	  	$query->setFetchMode(PDO::FETCH_ASSOC);
	   
	   	while($result = $query->fetch())
			echo $result['id'].'|'.$result['name'].'<br/>';
	}

	// Get timing (Séances) List...
	else if(!empty($_POST['getSessions']))
	{
	   	$query = $link->query("SELECT timing.course_id,course_name FROM timing,course WHERE timing.course_id = course.course_id GROUP BY timing.course_id");
	  	$query->setFetchMode(PDO::FETCH_ASSOC);
	   
	   	while($result = $query->fetch())
			echo $result['course_id'].'|'.$result['course_name'].'<br/>';
	}


	// Get Dep of Faculty...
	else if(!empty($_POST['getDeps']) && !empty($_POST['faculty']))
	{
	   	$query = $link->query("SELECT * FROM departement WHERE faculty_id = ".$_POST['faculty']);
	  	$query->setFetchMode(PDO::FETCH_ASSOC);
	   
	   	while($result = $query->fetch())
			echo $result['id'].'|'.$result['nom_dep'].'<br/>';
	}





	// DELETE TimeTable...
	else if(!empty($_POST['deleteTimetable']))
	{
	   	$query = $link->prepare("DELETE FROM timetable WHERE timetable_id = ?");
		$values = array ($_POST['id']);
		
		if($query->execute($values)){
			$query = $link->query("SELECT * FROM timetable,groupe WHERE timetable.course_id = ".$_POST['course']." AND timetable.course_id = groupe.course_id AND groupe_id = groupe.id AND groupe.section = '".$_POST['section']."' AND user_id = $user_id GROUP BY timetable_id");
		  	$query->setFetchMode(PDO::FETCH_ASSOC);

		  	if(!$query->fetch()){
		  		$query = $link->prepare("DELETE FROM cours_communs WHERE course_id = ".$_POST['course']." AND section = '".$_POST['section']."' AND user_id = $user_id");
				if($query->execute())
					echo 'success';
				else
					echo 'error';
		  	}
		}else
			echo 'error';
	}

?>














































































