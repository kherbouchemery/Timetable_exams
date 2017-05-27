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
		"ajax": "list.timetable.php",
		"columnDefs": [
			{ 
				"targets": 10,
				"searchable": false,
				"render": function(data, type, row, meta){
				   return '<a id="delete-row"><span class="glyphicon glyphicon-trash"></span></a>';  
				}
			} ,
			{ 
				"targets": 11,
				"searchable": false,
				"render": function(data, type, row, meta){
				   return '<a id="edit-row"><span class="glyphicon glyphicon-pencil"></span></a>';  
				}
			} ,
			{ 
				"targets": 12,
				"searchable": false,
				"render": function(data, type, row, meta){
				   return '<a id="edit-timetable"><span class="glyphicon glyphicon-cog"></span></a>';  
				}
			}           
		]  
	} );
} );

</script>
</head>



<?php
	ob_start();
   session_start();
   include_once('../header.php');

   $title = 'Emplois du Temps';

   include_once("template.php");

   include_once('../class.database.php');
   if(@$_SESSION['name']){
		   $db_connection = new dbConnection();
		   $link = $db_connection->connect();

		   session_start();
		   $user_id = $_SESSION['user_id'];

		   if(!empty($_POST['generate'])){
			   $course = $_POST['course'];
			   $groupe = $_POST['groupe'];
			   $year = $_POST['year'];
			   $semester = $_POST['semester'];
			   $timing = $_POST['timing'];

			   $query = $link->prepare("INSERT INTO timetable (user_id, course_id, groupe_id, year, semester, timing_course_id) VALUES(?,?,?,?,?,?)");
			   $values = array ($user_id, $course, $groupe, $year, $semester, $timing);

			   $query->execute($values);

			   $timetable_id = $link->lastInsertId();
			   
			   
			   header("Location: ../edit.table.php?edit=true&id=".$timetable_id."&timing=".$timing);
			   exit();
		   }
   }
   else{
	   echo "Vous n'êtes pas encore connecté. Veuillez revenir et se connecter de nouveau!";
	   exit();
   }

?>

<body>


<button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect" id="add">
  <i class="material-icons">add</i>
</button>

<div class='form-modal-close'></div>
<div class='form-modal' style="margin-top:1% !important;">
	<div class="jumbotron">
		<form class="form-horizontal" method="post" action="">
		<fieldset>

		<!-- Form Name -->
		<legend><span id='modal-title'>Créer un</span> Emploi du Temps</legend>

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
			  <div class="input-field col s12">
				<select class="select" id="section" name="section">
					
				</select>
			</div>
		  </div>
		</div>

		<div class="form-group">
		  <label class="col-md-4 control-label" for="groupe">Groupe</label>
		  <div class="col-md-8">
			  <div class="input-field col s12">
				<select class="select" id="groupe" name="groupe">
					
				</select>
			</div>
		  </div>
		</div>

		<!-- Text input-->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="year">Année</label>
		  <div class="col-md-8">
		  <input id="year" name="year" type="text" placeholder="e.g 2015-2016" class="form-control input-md" required="">
		  </div>
		</div>

		<div class="form-group">
		  <label class="col-md-4 control-label" for="semester">Semèstre</label>
		  <div class="col-md-8">
		  <input id="semester" name="semester" type="text" placeholder="e.g 1, 2" class="form-control input-md" required="">
		  </div>
		</div>

		<div class="form-group">
		  <label class="col-md-4 control-label" for="timing">Séances du Promo</label>
		  <div class="col-md-8">
			  <div class="input-field col s12">
				<select class="select" id="timing" name="timing">
					
				</select>
			</div>
		  </div>
		</div>

		<!-- Button -->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="generate"></label>
		  <div class="col-md-4">
		  	<input type="submit" id="submit" name="generate" class="btn btn-success" value="Créer Emploi du Temps">
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
			<h2>Liste des Emplois du Temps</h2><br/>
			<table id="list_table" class="display" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>ID</th>
						<th>PromoID</th>
						<th>Promo</th>
						<th>Section</th>
						<th>GroupeID</th>
						<th>Groupe</th>
						<th>Année</th>
						<th>Semèstre</th>
						<th>TimingCourseID</th>
						<th>Séances du Promo</th>
						<th></th>
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
	#list_table th:nth-child(2), #list_table td:nth-child(2),#list_table th:nth-child(5), #list_table td:nth-child(5),
	#list_table th:nth-child(9), #list_table td:nth-child(9){display: none !important;}
</style>

<link rel="stylesheet" type="text/css" href="../css/styles.forms.css"/>
<script type="text/javascript" src="../js/forms.js"></script>


<link rel="stylesheet" type="text/css" href="../css/materialize.min-select.css"/>
<script type="text/javascript" src="../js/materialize.min.js"></script>


<script>
	$(document).ready(function(){
		$('.select#course').material_select();
		$('.select#section').material_select();
		$('.select#groupe').material_select();
		$('.select#timing').material_select();

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
				$('.select#course').material_select('destroy');
				$('.select#course').material_select();
				$('.select#course').change();
			}
		});

		$.ajax({
			type:'POST',
			url:'forms.update.php',
			data: {
				getSessions: '*'
			},
			success: function(result){
				if(!result.trim()) return;
				var rows = result.split("<br/>");
				for(var i = 0;i<rows.length;i++){
					var r = rows[i].split('|');
					if(r.length != 2)
						continue;
					$('.select#timing').append('<option value="'+r[0]+'">'+r[1]+'</option>');
				}
				$('.select#timing').material_select('destroy');
				$('.select#timing').material_select();
				$('.select#timing').change();
			}
		});
	});

	$('.select#course').on('change',function(){
		$('.select#section').html('');
		$('.select#groupe').html('');
		$('.select#section').material_select('destroy');
		$('.select#section').material_select();
		$('.select#groupe').material_select('destroy');
		$('.select#groupe').material_select();
		$.ajax({
			type:'POST',
			url:'forms.update.php',
			data: {
				course: $('.select#course').val(),
				getSections: 'true'
			},
			success: function(result){
				if(!result.trim()) return;
				
				var rows = result.split("<br/>");
				for(var i = 0;i<rows.length;i++){
					if(!rows[i].trim().length)
						continue;
					$('.select#section').append('<option value="'+rows[i]+'">'+rows[i]+'</option>');
				}
				$('.select#section').material_select('destroy');
				$('.select#section').material_select();
				$('.select#section').change();
			}
		});
	});

	$('.select#section').on('change',function(){
		$('.select#groupe').html('');
		$.ajax({
			type:'POST',
			url:'forms.update.php',
			data: {
				course: $('.select#course').val(),
				section: $('.select#section').val(),
				getGroupes: 'true'
			},
			success: function(result){
				if(!result.trim()) return;
				var rows = result.split("<br/>");
				for(var i = 0;i<rows.length;i++){
					var r = rows[i].split('|');
					if(r.length != 2)
						continue;
					$('.select#groupe').append('<option value="'+r[0]+'">'+r[1]+'</option>');
				}
				$('.select#groupe').material_select('destroy');
				$('.select#groupe').material_select();
				$('.select#groupe').change();
			}
		});
	});





	$(document).on('click', '#delete-row', function(){
		$.ajax({
			type:'POST',
			url:'forms.update.php',
			data: {
				id: $(this).parent().parent().find('td:nth-child(1)').text(),
				course: $(this).parent().parent().find('td:nth-child(2)').text(),
				section: $(this).parent().parent().find('td:nth-child(4)').text(),
				deleteTimetable: 'true'
			},
			success: function(result){
				$('#list_table').DataTable().ajax.reload();
				
				if(result.trim() == 'success')
					$('body').append('<div class="alert alert-success"><a class="close" data-dismiss="alert">X</a><strong>Succès!</strong><br/>Supprimé avec Succès.</div>');
				else if(result.trim() == 'error')
					$('body').append('<div class="alert alert-block"><a class="close" data-dismiss="alert">X</a><strong>Opps Erreur!</strong><br/>N\'est pas supprimé.</div>');
			}
		});
	});

	var editedRowID;
	$(document).on('click','#edit-row', function(){
		var row = $(this).parent().parent();
		editedRowID = row.find('td:nth-child(1)').text();
		$('.select#course').val(row.find('td:nth-child(2)').text());
		$('.select#course').material_select('destroy');
		$('.select#course').material_select();
		$('.select#course').change();
		setTimeout(function(){
			$('.select#section').val(row.find('td:nth-child(4)').text());
			$('.select#section').material_select('destroy');
			$('.select#section').material_select();
			$('.select#section').change();
			setTimeout(function(){
				$('.select#groupe').val(row.find('td:nth-child(5)').text());
				$('.select#groupe').material_select('destroy');
				$('.select#groupe').material_select();
				$('.select#groupe').change();
			}, 300);
		}, 300);
		
		$('#year').val($(this).parent().parent().find('td:nth-child(7)').text());
		$('#semester').val($(this).parent().parent().find('td:nth-child(8)').text());
		$('.select#timing').val($(this).parent().parent().find('td:nth-child(9)').text());
		$('.select#timing').material_select('destroy');
		$('.select#timing').material_select();
		$('.select#timing').change();

		$('#submit').css('display','none');
		$('#update').css('display','block');		
		$('#modal-title').text('Modifier');
		$('.form-modal').css('left','0%');
		$('.form-modal-close').css('display','block');
	});


	$(document).on('click', '#edit-timetable', function(){
		var id = $(this).parent().parent().find('td:nth-child(1)').text();
		var timing = $(this).parent().parent().find('td:nth-child(9)').text();
		window.location = "../edit.table.php?edit=true&id="+id+"&timing="+timing;
	});


	$('#update').on('click', function(e){
		e.preventDefault();
		$.ajax({
			type:'POST',
			url:'forms.update.php',
			data: {
				id: editedRowID,
				course: $('.select#course').val(),
				groupe: $('.select#groupe').val(),
				year: $('#year').val(),
				semester: $('#semester').val(),
				timing: $('.select#timing').val(),
				updateTimetable: 'true'
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