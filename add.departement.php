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
		"ajax": "list.departement.php",
		"columnDefs": [
			{ 
				"targets": 4,
				"searchable": false,
				"render": function(data, type, row, meta){
				   return '<a href="add.departement.php?delete=true&id=' + row[0] + '"><span class="glyphicon glyphicon-trash"></span></a>';  
				}
			},
			{ 
				"targets": 5,
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

   $title = 'Départements';

   include_once("template.php");

if($_SESSION['user_id']){

	function add_departement($nom,$fac){
			$db_connection = new dbConnection();
			$link = $db_connection->connect();
			$query = $link->prepare("INSERT INTO departement (nom_dep, faculty_id) VALUES(?,?)");
			$values = array ($nom,$fac);
			$query->execute($values);
			$count = $query->rowCount();
			return $count;
		}

	if(isset($_POST['submit']))
	{
			$count= add_departement($_POST['nom'],$_POST['faculty']);
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
				<legend><span id='modal-title'>Ajouter</span> une Département</legend>
				<div class="form-group">
				  <label class="col-md-4 control-label" for="nom">Nom</label>
				  <div class="col-md-8">
				  	<input id="nom" name="nom" type="text" placeholder="" class="form-control input-md" required="">
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-md-4 control-label" for="faculty">Faculté</label>
				  <div class="col-md-8">
					  <div class="input-field col s12">
						<select class="select" id="faculty" name="faculty">
							
						</select>
					</div>
				  </div>
				</div>

				<!-- Button -->
				<div class="form-group">
				  <label class="col-md-4 control-label" for="submit"></label>
				  <div class="col-md-4">
					<button id="submit" name="submit" class="btn btn-success">Ajouter Departement</button>
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

					function delete_dep($id){
						$db_connection = new dbConnection();
						$link = $db_connection->connect();
						$link->query("DELETE FROM departement WHERE id = $id");
					}
					if(isset($_GET['delete'])){
						 delete_dep($_GET['id']);
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
			
			<h2>Liste des Départements</h2><br/>
			<table id="list_table" class="display" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>ID</th>
						<th>Nom</th>
						<th>Faculty</th>
						<th>Faculté</th>
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
				getFac: '*'
			},
			success: function(result){
				if(!result.trim()) return;
				var rows = result.split("<br/>");
				for(var i = 0;i<rows.length;i++){
					var r = rows[i].split('|');
					if(r.length != 2)
						continue;
					$('.select#faculty').append('<option value="'+r[0]+'">'+r[1]+'</option>');
				}
				$('.select#faculty').material_select();
				$('.select#faculty').change();
			}
		});
	});


	var editedRowID;
	$(document).on('click','#edit-row', function(){
		editedRowID = $(this).parent().parent().find('td:nth-child(1)').text();
		$('#nom').val($(this).parent().parent().find('td:nth-child(2)').text());
		$('.select#faculty').val($(this).parent().parent().find('td:nth-child(3)').text());
		$('.select#faculty').material_select('destroy');
		$('.select#faculty').material_select();
		$('.select#faculty').change();

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
					nom: $('#nom').val(),
					faculty: $('.select#faculty').val(),
					updateDep: 'true'
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