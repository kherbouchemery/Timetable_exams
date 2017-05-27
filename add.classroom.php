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
		"ajax": "list.classroom.php",
		"columnDefs": [
			{ 
				"targets": 2,
				"searchable": false,
				"render": function(data, type, row, meta){
				   return '<a href="add.classroom.php?delete=true&num=' + row[0] + '&type=' + row[1] + '"><span class="glyphicon glyphicon-trash"></span></a>';  
				}
			},
			{ 
				"targets": 3,
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

   $title = 'Salles';

   include_once("template.php");

if($_SESSION['user_id']){

	function add_classroom($num,$type){
			$db_connection = new dbConnection();
			$link = $db_connection->connect();
			$query = $link->prepare("INSERT INTO classroom (num,type_cls) VALUES(?,?)");
			$values = array ($num,$type);
			$query->execute($values);
			$count = $query->rowCount();
			return $count;
		}

	if(isset($_POST['submit']))
	{
			$count= add_classroom($_POST['num'],$_POST['type']);
			if($count){

			echo 	'<div class="alert alert-success">
					<a class="close" data-dismiss="alert">X</a>
					<strong>Success!</strong><br/>Ajouté avec Succès.
					</div>';
			}
			else{
				echo '<div class="alert alert-block">
					<a class="close" data-dismiss="alert">X</a>
					<strong>Opps Error!</strong><br/>N\'est pas ajouté.
					</div>';
			}

	}


	// UPDATE
	function udpate_classroom($num,$type){
			$db_connection = new dbConnection();
			$link = $db_connection->connect();
			$query = $link->prepare("UPDATE classroom SET type_cls = '$type' WHERE num = $num");
			$query->execute();
			$count = $query->rowCount();
			return $count;
		}

	if(isset($_POST['update']))
	{
			$count= udpate_classroom($_POST['num'],$_POST['type']);
			if($count){

			echo 	'<div class="alert alert-success">
					<a class="close" data-dismiss="alert">X</a>
					<strong>Success!</strong><br/>Modifié avec Succès.
					</div>';
			}
			else{
				echo '<div class="alert alert-block">
					<a class="close" data-dismiss="alert">X</a>
					<strong>Opps Error!</strong><br/>N\'est pas modifié.
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
		<form class="form-horizontal" method= "post" action = "add.classroom.php">
			<fieldset>
				<legend><span id='modal-title'>Ajouter</span> une Salle</legend>
				<div class="form-group">
				  <label class="col-md-4 control-label" for="num">N°</label>
				  <div class="col-md-8">
				  	<input id="num" name="num" type="text" placeholder="" class="form-control input-md" required="">
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-md-4 control-label" for="type">Type</label>
				  <div class="col-md-8">
					  <div class="input-field col s12">
						<select class="select" id="type" name="type">
							<option value="Emphi">Emphi</option>
							<option value="Salle TD">Salle TD</option>
							<option value="Labo">Labo</option>
						</select>
					</div>
				  </div>
				</div>

				<!-- Button -->
				<div class="form-group">
				  <label class="col-md-4 control-label" for="submit"></label>
				  <div class="col-md-4">
					<button id="submit" name="submit" class="btn btn-success">Ajouter Salle</button>
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

					function deleteclassroom($classroom_num,$type){
						$db_connection = new dbConnection();
						$link = $db_connection->connect();
						$link->query("DELETE FROM `timetable`.`classroom` WHERE `classroom`.`num` = '$classroom_num' AND type_cls = '$type'");
					}
					if(isset($_GET['delete'])){
						 deleteclassroom($_GET['num'], $_GET['type']);
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
			
			<h2>Liste des Salles</h2><br/>
			<table id="list_table" class="display tbl-cls" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>N°</th>
						<th>Type</th>
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

<link rel="stylesheet" type="text/css" href="../css/materialize.min-select.css"/>
<script type="text/javascript" src="../js/materialize.min.js"></script>

<script>
	$(document).ready(function() {
		$('.select').material_select();
	});

	var editedRowID;
	$(document).on('click','#edit-row', function(){
		editedRowID = $(this).parent().parent().find('td:nth-child(1)').text();
		$('#num').val(editedRowID);
		$('.select').val($(this).parent().parent().find('td:nth-child(2)').text());
		$('.select').material_select('destroy');
		$('.select').material_select();

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
					type: $('#type').val(),
					updateClassroom: 'true'
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


<link rel="stylesheet" type="text/css" href="../css/styles.forms.css"/>
<script type="text/javascript" src="../js/forms.js"></script>
