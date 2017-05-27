<head>
<meta charset="utf-8">

<meta name="viewport" content="initial-scale=1.0, maximum-scale=2.0">
<link rel="stylesheet" type="text/css" href="../css/jquery.dataTables.css">
<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" class="init">

$(document).ready(function() {
	$('#list_table').dataTable( {
		"aProcessing": true,
		"aServerSide": true,
		"aLengthMenu": [[5, 10, 20, 50, 100, -1], [5, 10, 20, 50, 100, "All"]],
		"iDisplayLength": 5,
		"ajax": "list.faculty.php",
		"columnDefs": [
			{ 
				"targets": 3,
				"searchable": false,
				"render": function(data, type, row, meta){
				   return '<a href="add.faculty.php?delete=true&id=' + row[0] + '"><span class="glyphicon glyphicon-trash"></span></a>';  
				}
			},
			{ 
				"targets": 4,
				"searchable": false,
				"render": function(data, type, row, meta){
				   return '<a id="edit-row"><span class="glyphicon glyphicon-pencil"></span></a>';  
				}
			}  
		]  
	} );
} );

</script>
</head>



<?php
   session_start();

if(@$_SESSION['user_id']){
   //$path = $_SERVER['DOCUMENT_ROOT'];
   //$path .= "/timetable/header.php";
   include_once('../header.php');

   //$path = $_SERVER['DOCUMENT_ROOT'];
   //$path .= "/timetable/class.database.php";
   include_once('../class.database.php');

   $title = 'Factultés';

   include_once("template.php");


	function GetFacultyInfo($user_id,$facultycode){
			$db_connection = new dbConnection();
			$link = $db_connection->connect();
			$query = $link->query("SELECT * FROM faculty WHERE faculty_code = '$facultycode' AND user_id='$user_id'");
			$rowCount = $query->rowCount();
			if($rowCount ==1)
			{
				$result = $query->fetchAll();
				return $result;
			}
			else
			{
				return $rowCount;
			}
		}

	function add_faculty($user_id,$code,$name){
			$db_connection = new dbConnection();
			$link = $db_connection->connect();
			$query = $link->prepare("INSERT INTO faculty (user_id,faculty_code, faculty_name) VALUES(?,?,?)");
			$values = array ($user_id,$code,$name);
			$query->execute($values);
			$count = $query->rowCount();
			return $count;
		}

	if(isset($_POST['submit']))
	{
			$check_faculty = GetFacultyInfo($_SESSION['user_id'], $_POST['fcode']);
		if($check_faculty === 0){
			$count= add_faculty($_SESSION['user_id'],$_POST['fcode'],$_POST['name'],$_POST['d'],$_POST['q']);
			if($count){

			echo 	'<div class="alert alert-success">
					<a class="close" data-dismiss="alert">X</a>
					<strong>Success! </strong><br/>Ajouté avec Succès.
					</div>';
			}
			else{
				echo '<div class="alert alert-block">
					<a class="close" data-dismiss="alert">X</a>
					<strong>Opps Error!</strong><br/>N\'est pas ajouté.
					</div>';
			}
		}
		else{
			echo '<div class="alert alert-block">
					<a class="close" data-dismiss="alert">X</a>
					<strong>Opps Error!</strong><br/>Faculté existe déja.
					</div>';
		}

	}
}
else{
	echo "Vous n'êtes pas encore connecté. Veuillez revenir et se connecter de nouveau!";
	exit();
}
?>

<button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect" id="add">
  <i class="material-icons">add</i>
</button>

<div class='form-modal-close'></div>
<div class='form-modal'>
	<div class="jumbotron">
			<form class="form-horizontal" method= "post" action="">
			<fieldset>

			<legend><span id='modal-title'>Ajouter</span> une Faculté</legend>

			<!-- Text input-->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="name">Code</label>
			  <div class="col-md-8">
			  <input id="fcode" name="fcode" type="text" placeholder="" class="form-control input-md" required="">

			  </div>
			</div>

			<!-- Text input-->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="name">Nom</label>
			  <div class="col-md-8">
			  <input id="name" name="name" type="text" placeholder="" class="form-control input-md" required="">

			  </div>
			</div>

			<!-- Button -->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="submit"></label>
			  <div class="col-md-8">
				<button id="submit" name="submit" class="btn btn-primary">Ajouter Faculté</button>
				<button id="update" class="btn btn-success">Mettre à Jour</button>
			  </div>
			</div>

			</fieldset>
			</form>
	</div>
</div>

<main class="mdl-layout__content mdl-color--grey-100">
	<div class="container">
	  <div class="row">
	    
    <div style="max-width: 60%;margin-left: 15%">
		<div class="jumbotron">
		<?php
			if($_SESSION['user_id']){

				function deletefac($faculty_id, $user_id){
					$db_connection = new dbConnection();
					$link = $db_connection->connect();
					$link->query("DELETE FROM  `timetable`.`faculty` WHERE  `faculty`.`faculty_id` ='$faculty_id' AND `faculty`.`user_id`='$user_id'");
				}
				if(isset($_GET['delete'])){
					 deletefac($_GET['id'],$_SESSION['user_id']);
					 echo 	'<div class="alert alert-success">
							<a class="close" data-dismiss="alert">X</a>
							<strong>Success!</strong><br/>Supprimé avec Succès.
							</div>';
				}
			}
			else{
				echo "Vous n'êtes pas encore connecté. Veuillez revenir et se connecter de nouveau!";
			}
		?>
		
		<h2>Liste des Facultés</h2><br/>
		<table id="list_table" class="display" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>ID</th>
					<th>Code</th>
					<th>Nom</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
		</table>
		</div>
    </div>
  </div>
</div>
</main>

<link rel="stylesheet" type="text/css" href="../css/styles.forms.css"/>
<script type="text/javascript" src="../js/forms.js"></script>

<script>
	var editedRowID;
	$(document).on('click','#edit-row', function(){
		editedRowID = $(this).parent().parent().find('td:nth-child(1)').text();
		$('#fcode').val($(this).parent().parent().find('td:nth-child(2)').text());
		$('#name').val($(this).parent().parent().find('td:nth-child(3)').text());

		$('#submit').css('display','none');
		$('#update').css('display','block');		
		$('#modal-title').text('Modifier');
		$('.form-modal').css('left','0%');
		$('.form-modal-close').css('display','block');
	});


	$('#update').on('click', function(e){
		e.preventDefault();
		$.ajax({
			type:'POST',
				url:'forms.update.php',
				data: {
					id: editedRowID,
					code: $('#fcode').val(),
					name: $('#name').val(),
					updateFaculty: 'true'
				},
				success: function(result){
					$('.form-modal-close').click();
					$('#list_table').DataTable().ajax.reload();
					
					if(result.trim() == 'success')
						$('body').append('<div class="alert alert-success"><a class="close" data-dismiss="alert">X</a><strong>Succès! </strong><br/>Modifié avec Succès.</div>');
					else if(result.trim() == 'error')
						$('body').append('<div class="alert alert-block"><a class="close" data-dismiss="alert">X</a><strong>Opps Erreur!</strong><br/>N\'est pas modifié.</div>');
				}
		});
	});
</script>