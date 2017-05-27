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
		"ajax": "list.groupe.php",
		"columnDefs": [
			{ 
				"targets": 5,
				"searchable": false,
				"render": function(data, type, row, meta){
				   return '<a href="add.groupe.php?delete=true&id=' + row[0] + '"><span class="glyphicon glyphicon-trash"></span></a>';  
				}
			} ,
			{ 
				"targets": 6,
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
   include_once('../header.php');

   include_once('../class.database.php');

   $title = "Groupes";

   include_once("template.php");

if($_SESSION['user_id']){

	function add_course($name,$course_id,$section){
			$db_connection = new dbConnection();
			$link = $db_connection->connect();
			$query = $link->prepare("INSERT INTO groupe (name,course_id,section) VALUES(?,?,?)");
			$values = array ($name, $course_id, $section);
			$query->execute($values);
			$count = $query->rowCount();
			return $count;
		}

	if(isset($_POST['submit']))
	{
			$count= add_course($_POST['name'],$_POST['course'],$_POST['section']);
			if($count){

			echo 	'<div class="alert alert-success">
					<a class="close" data-dismiss="alert">X</a>
					<strong>Succès!</strong><br/>Ajouté avec Succès.
					</div>';
			}
			else{
				echo '<div class="alert alert-block">
					<a class="close" data-dismiss="alert">X</a>
					<strong>Erreur!</strong><br/>N\'est pas ajouté.
					</div>';
			}

	}

	

}
?>

<button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect" id="add">
  <i class="material-icons">add</i>
</button>

<div class='form-modal-close'></div>
<div class='form-modal'>
	<div class="jumbotron">
		<form class="form-horizontal" method= "post" action = "">
		<fieldset>

		<!-- Form Name -->
		<legend><span id='modal-title'>Ajouter</span> une Groupe</legend>


		<!-- Text input-->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="name">Nom</label>
		  <div class="col-md-8">
		  	<input id="name" name="name" type="text" placeholder="e.g Groupe 1" class="form-control input-md" required="">
		  </div>
		</div>

		<div class="form-group">
		  <label class="col-md-4 control-label" for="course">Promo</label>
		  <div class="col-md-8">
			  <div class="input-field col s12">
				<select class="select" id="course" name="course">
					
				</select>
			</div>
		  </div>
		</div>

		<div class="form-group">
		  <label class="col-md-4 control-label" for="section">Section</label>
		  <div class="col-md-8">
		  	<input id="section" name="section" type="text" placeholder="e.g A, B, C" class="form-control input-md" required="">
		  </div>
		</div>

		<!-- Button -->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="submit"></label>
		  <div class="col-md-4">
			<button id="submit" name="submit" class="btn btn-success">Ajouter Groupe</button>
			<button id="update" name="update" class="btn btn-success">Mettre à Jour</button>
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

						function deletegroupe($id){
							$db_connection = new dbConnection();
							$link = $db_connection->connect();
							$link->query("DELETE FROM `timetable`.`groupe` WHERE `groupe`.`id` = $id");
						}
						if(isset($_GET['delete'])){
							 deletegroupe($_GET['id']);
							 echo 	'<div class="alert alert-success">
									<a class="close" data-dismiss="alert">X</a>
									<strong>Succès!</strong><br/>Supprimé avec Succés.
									</div>';
						}
					}
					else{
						echo "Vous n'êtes pas encore connecté. Veuillez revenir et se connecter de nouveau!";
					}
				?>
				
				<h2>Liste des Groupes</h2><br/>
				<table id="list_table" class="display" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>ID</th>
							<th>Nom</th>
							<th>PromoID</th>
							<th>Promo</th>
							<th>Section</th>
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

<style>
	#list_table th:nth-child(3), #list_table td:nth-child(3){display: none !important;}
</style>

<link rel="stylesheet" type="text/css" href="../css/styles.forms.css"/>
<script type="text/javascript" src="../js/forms.js"></script>


<link rel="stylesheet" type="text/css" href="../css/materialize.min-select.css"/>
<script type="text/javascript" src="../js/materialize.min.js"></script>

<script>
	$(document).ready(function(){
		$.ajax({
			type:'POST',
			url:'forms.update.php',
			data: {
				getCourses: '*'
			},
			success: function(result){
				if(!result.trim()) return;
				var rows = result.split("<br/>");
				for(var i = 0;i<rows.length;i++){
					var r = rows[i].split('|');
					if(r.length != 2)
						continue;
					$('#course').append('<option value="'+r[0]+'">'+r[1]+'</option>');
				}
				$('.select#course').material_select();
				$('.select#course').change();
			}
		});
	});

	var editedRowID;
	$(document).on('click','#edit-row', function(){
		editedRowID = $(this).parent().parent().find('td:nth-child(1)').text();
		$('#name').val($(this).parent().parent().find('td:nth-child(2)').text());
		$('.select#course').val($(this).parent().parent().find('td:nth-child(3)').text());
		$('.select#course').material_select('destroy');
		$('.select#course').material_select();
		$('.select#course').change();
		$('#section').val($(this).parent().parent().find('td:nth-child(5)').text());

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
				name: $('#name').val(),
				course: $('.select#course').val(),
				section: $('#section').val(),
				updateGroupe: 'true'
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