<?php
	session_start();
	include_once('../class.database.php');

	$db_connection = new dbConnection();
    $link = $db_connection->connect();
	$user_id= $_SESSION['user_id'];

	if(!empty($_POST['id'])){
		$timetable_id= $_POST['id'];

		$query = $link->query("SELECT * FROM timetable,course,groupe WHERE timetable_id = $timetable_id AND timetable.course_id = course.course_id AND groupe_id = groupe.id AND groupe.course_id = course.course_id");
	  	$query->setFetchMode(PDO::FETCH_ASSOC);
	   
	   	if($result = $query->fetch()){
	   		$course = $result['course_id'];
	   		$section = $result['section'];
	   		$groupe = $result['id'];
	   	}
   	}
	
	// recuperation des salles
   if(!empty($_POST['getSalles']))
	{
	   $query = $link->query("SELECT * FROM classroom WHERE type_cls =  '".$_POST['getSalles']."'");

	   $query->setFetchMode(PDO::FETCH_ASSOC);
	   
	   while($result = $query->fetch())
		  echo "<tr><td><div class='classroom draggable-item' id='".$result['num']."'>".$result['num']."</div></td></tr>";
	}

	// verification des conflits
	else 
	if(!empty($_POST['getUnavailCells_cls']) || !empty($_POST['getUnavailCells_teacher']))
	{
		// selectionné salle = true , sel prof = true
		$clsQuery = !empty($_POST['getUnavailCells_cls']) && !empty($_POST['getUnavailCells_teacher'])?" OR ":"";
		// $clsQuery = " OR classroom_id = 2001"
		$clsQuery .= !empty($_POST['getUnavailCells_cls'])?"classroom_id =  ".$_POST['getUnavailCells_cls']:"";
		// $teacherQuery = "teacher_id = 2"
		$teacherQuery = !empty($_POST['getUnavailCells_teacher'])?"teacher_id =  ".$_POST['getUnavailCells_teacher']:"";

		// recuperer les cellules depuis 'edit.table.php' qui ont des profs/salles de 'getUnavailCells_cls'/'getUnavailCells_teacher'

		// $teacherQuery = teacher_id = $_POST['getUnavailCells_teacher']
		// SELECT * FROM tablesheet WHERE classroom_id = $_POST['getUnavailCells_cls']
		// SELECT * FROM tablesheet WHERE teacher_id = $_POST['getUnavailCells_teacher'] OR 
		// classroom_id = $_POST['getUnavailCells_cls']
	   	//$query = $link->query("SELECT * FROM timetable WHERE ".$teacherQuery.$clsQuery);
	   	$query = $link->query("(SELECT teacher_id,cell FROM tablesheet WHERE ".$teacherQuery.$clsQuery.") UNION (SELECT teacher_id,cell FROM cours_communs WHERE ".$teacherQuery.$clsQuery.")");
	  	$query->setFetchMode(PDO::FETCH_ASSOC);
	   
	   	while($result = $query->fetch())
			echo $result['cell'].'|'; // 5|20|
	}

	else if (!empty($_POST['getTablesheet']) && !empty($_POST['id'])) {
		// récupération des cellules qui ont type_seance = 'TD' ou 'TP'
		$query = $link->query("SELECT * FROM tablesheet,subject,teacher,classroom WHERE timetable_id = $timetable_id AND tablesheet.user_id = '$user_id' AND tablesheet.subject_id = subject.subject_id AND teacher_id = teacher.id AND classroom_id = classroom.num GROUP BY timetable_id");
		$query->setFetchMode(PDO::FETCH_ASSOC);
		$i=0;
		$cellid = array();
		$subject = array();
		$subjectID = array();
		$typeSeance = array();
		$teacher = array();
		$teacherID = array();
		$classroom = array();
		$typeCls = array();
		while($result = $query->fetch()){
			$cellid[$i] = $result['cell'];
			$subject[$i] = $result['subject_name'];
			$subjectID[$i] = $result['subject_id'];
			$typeSeance[$i] = $result['type_seance'];
			$teacher[$i] = $result['last_name']." ".$result['first_name'];
			$teacherID[$i] = $result['teacher_id'];
			$classroom[$i] = $result['num'];
			$typeCls[$i] = $result['type_cls'];
			$i++;
		}

		// récupération des cellules qui ont type_seance = 'Cours'
		$query = $link->query("SELECT * FROM cours_communs,subject,teacher,classroom WHERE cours_communs.course_id = $course AND section = '$section' AND cours_communs.user_id = '$user_id' AND cours_communs.subject_id = subject.subject_id AND teacher_id = teacher.id AND classroom_id = classroom.num GROUP BY cours_communs.id");
		$query->setFetchMode(PDO::FETCH_ASSOC);
		while($result = $query->fetch()){
			$cellid[$i] = $result['cell'];
			$subject[$i] = $result['subject_name'];
			$subjectID[$i] = $result['subject_id'];
			$typeSeance[$i] = 'Cours';
			$teacher[$i] = $result['last_name']." ".$result['first_name'];
			$teacherID[$i] = $result['teacher_id'];
			$classroom[$i] = $result['num'];
			$typeCls[$i] = $result['type_cls'];
			$i++;
		}

		$rows = array($cellid, $subject, $subjectID, $typeSeance, $teacher, $teacherID, $classroom, $typeCls);

		echo json_encode($rows);
	}

	if(isset($_POST['getTeacherCount'])){
		$query = $link->query("SELECT COUNT(*) as tCount FROM tablesheet WHERE timetable_id <> ".$_POST['timetable']." AND teacher_id = ".$_POST['getTeacherCount']);
	  	$query->setFetchMode(PDO::FETCH_ASSOC);
	   
	   	if($result = $query->fetch()){
	   		echo $result["tCount"];
	   	}
	}
?>

