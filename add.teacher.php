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
		"ajax": "list.teacher.php",
		"columnDefs": [
			{ 
				"targets": 9,
				"searchable": false,
				"render": function(data, type, row, meta){
				   return '<a href="add.teacher.php?delete=true&id=' + row[0] + '"><span class="glyphicon glyphicon-trash"></span></a>';  
				}
			},
			{ 
				"targets": 10,
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

   $title = 'Enseignants';

   include_once("template.php");

if($_SESSION['user_id']){

	function add_teacher($teacher_fName,$teacher_lName, $degre, $email, $dep, $type, $tel){
			$db_connection = new dbConnection();
			$link = $db_connection->connect();

			$query = $link->query("SELECT * FROM departement WHERE nom_dep = '".$dep."'");
	        $query->setFetchMode(PDO::FETCH_ASSOC);
	       
	       	$dep_ = "";
	        if($result = $query->fetch())
	            $dep_ = $result['id'];


			$query = $link->prepare("INSERT INTO teacher (first_name, last_name, degre, email, dep, type, tel) VALUES(?,?,?,?,?,?,?)");
			$values = array ($teacher_fName, $teacher_lName, $degre, $email, $dep_, $type, $tel);
			$query->execute($values);
			$count = $query->rowCount();
			return $count;
		}

	if(isset($_POST['submit']))
	{
			$count= add_teacher($_POST['fName'],$_POST['lName'],$_POST['degre'],$_POST['email'],$_POST['dep'],$_POST['type'],$_POST['tel']);
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
<div class='form-modal' style="margin-top:5%;">
	<div class="jumbotron">
	<form class="form-horizontal" method= "post" action = "">
		<fieldset>

		<legend><span id='modal-title'>Ajouter</span> un Enseignant</legend>

		<!-- Text input-->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="lName">Nom</label>
		  <div class="col-md-8">
		  <input id="lName" name="lName" type="text" placeholder="" class="form-control input-md" required="">
		  </div>
		</div>

		<!-- Text input-->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="fName">Prénom</label>
		  <div class="col-md-8">
		  <input id="fName" name="fName" type="text" placeholder="" class="form-control input-md" required="">
		  </div>
		</div>

		<div class="form-group">
		  <label class="col-md-4 control-label" for="degre">Degré</label>
		  <div class="col-md-8">
			  <div class="input-field col s12">
				<select class="select" id="degre" name="degre">
					<option value="A">A</option>
					<option value="B">B</option>
					<option value="C">C</option>
				</select>
			</div>
		  </div>
		</div>

		<div class="form-group">
		  <label class="col-md-4 control-label" for="email">Email</label>
		  <div class="col-md-8">
		  <input id="email" name="email" type="email" placeholder="" class="form-control input-md" required="">
		  </div>
		</div>

		<div class="form-group">
		  <label class="col-md-4 control-label" for="dep">Département</label>
		  <div class="col-md-8">
			  <div class="input-field col s12">
				<select class="select" id="dep" name="dep">
					
				</select>
			</div>
		  </div>
		</div>

		<div class="form-group">
		  <label class="col-md-4 control-label" for="type">Type</label>
		  <div class="col-md-8">
			  <div class="input-field col s12">
				<select class="select" id="type" name="type">
					<option value="Titulaire">Titulaire</option>
					<option value="Contrat">Contrat</option>
				</select>
			</div>
		  </div>
		</div>

		<div class="form-group">
		  <label class="col-md-4 control-label" for="tel">Tel</label>
		  <div class="col-md-8">
		  <input id="tel" name="tel" type="text" placeholder="" class="form-control input-md" required="">
		  </div>
		</div>


		<!-- Button -->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="submit"></label>
		  <div class="col-md-4">
			<button id="submit" name="submit" class="btn btn-success">Ajouter Enseignant</button>
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
	    
    <div style="max-width: 70%;margin-left: 8%">
		<div class="jumbotron">
		<?php
			if($_SESSION['user_id']){

				function deleteteacher($teacher_id){
					$db_connection = new dbConnection();
					$link = $db_connection->connect();
					$link->query("DELETE FROM `timetable`.`teacher` WHERE `teacher`.`id` = '$teacher_id'");
				}
				if(isset($_GET['delete'])){
					 deleteteacher($_GET['id']);
					 echo 	'<div class="alert alert-success">
							<a class="close" data-dismiss="alert">X</a>
							<strong>Succès!</strong><br/>Supprimé avec Succès.
							</div>';
				}
			}
			else{
				echo "Vous n'êtes pas encore connecté. Veuillez revenir et se connecter de nouveau!";
			}
		?>
		
		<h2>Liste des Enseignants</h2><br/>
		<table id="list_table" class="display" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>ID</th>
					<th>Nom</th>
					<th>Prénom</th>
					<th>Degré</th>
					<th>Email</th>
					<th>Departement</th>
					<th>Type</th>
					<th>Tel</th>
					<th>Volume Horaire</th>
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

<link rel="stylesheet" type="text/css" href="../css/materialize.min-select.css"/>
<script type="text/javascript" src="../js/materialize.min.js"></script>

<script>
	$(document).ready(function(){
		$.ajax({
			type:'POST',
			url:'forms.update.php',
			data: {
				getDep: '*'
			},
			success: function(result){
				if(!result.trim()) return;
				var rows = result.split("<br/>");
				for(var i = 0;i<rows.length;i++){
					var r = rows[i].split('|');
					if(r.length != 2)
						continue;
					$('#dep').append('<option value="'+r[1]+'">'+r[1]+'</option>');
				}
				$('.select').material_select();
			}
		});
	});

	var editedRowID;
	$(document).on('click','#edit-row', function(){
		editedRowID = $(this).parent().parent().find('td:nth-child(1)').text();
		$('#lName').val($(this).parent().parent().find('td:nth-child(2)').text());
		$('#fName').val($(this).parent().parent().find('td:nth-child(3)').text());
		$('.select#degre').val($(this).parent().parent().find('td:nth-child(4)').text());
		$('.select').material_select('destroy');
		$('.select').material_select();
		$('#email').val($(this).parent().parent().find('td:nth-child(5)').text());
		$('#dep').val($(this).parent().parent().find('td:nth-child(6)').text());
		$('.select#dep').material_select('destroy');
		$('.select#dep').material_select();
		$('#type').val($(this).parent().parent().find('td:nth-child(7)').text());
		$('.select#type').material_select('destroy');
		$('.select#type').material_select();
		$('#tel').val($(this).parent().parent().find('td:nth-child(8)').text());

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
				lName: $('#lName').val(),
				fName: $('#fName').val(),
				degre: $('#degre').val(),
				email: $('#email').val(),
				dep: $('#dep').val(),
				type: $('#type').val(),
				tel: $('#tel').val(),
				updateTeacher: 'true'
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