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
		"ajax": "list.timing.php",
		"columnDefs": [
			{ 
				"targets": 4,
				"searchable": false,
				"render": function(data, type, row, meta){
				   return '<a id="delete-row"><span class="glyphicon glyphicon-trash"></span></a>';  
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

if(@$_SESSION['user_id']){

   include_once('../header.php');

   include_once('../class.database.php');

   $title = 'Séances';

   include_once("template.php");
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
<div class='form-modal'>
	<div class="jumbotron">
		<div class="form-group">
			<style>
				.col-lg-6{width:640px;}
				#add-timing td{
					padding:5px;
				}
				#sessions-list-table{
					width:100%;
					margin-left: 60px;
					margin-top:20px;
				}
				#sessions-list td{
					padding:5px;
				}
				#session-edit{
					width:20%;
				}
				#session-edit a, #session-delete a{cursor:pointer;}
				.session-time{
					cursor:move;
					margin-right:20px;
					width: 195px;
				}
				#session-input{display:none;}
			</style>

			<div class="form-group">
			  <label class="col-md-4 control-label" for="course" style="width:10%; margin-top:15px;">Promo</label>
			  <div class="col-md-8">
				  <div class="input-field col s12">
					<select class="select" id="course" name="course">
						
					</select>
				</div>
			  </div>
			</div>

			<table id="add-timing">
				<tr>
					<td><label for="session">Séance</label></td>
					<td><input id="session" type="text" placeholder="e.g 08:30 - 10:00" class="form-control input-md"></td>
					<td><button id="addSession" class="btn btn-primary">Ajouter</button></td>
					<td><button id="saveSort" class="btn btn-primary">Sauvgarder l'Ordre</button></td>
				</tr>
			</table>
			<table id="sessions-list-table">
				<tbody id="sessions-list">
					
				</tbody>
			</table>
		</div>
	</div>
</div>


<main class="mdl-layout__content mdl-color--grey-100">
	<div class="container">
	  	<div class="row">
	  		<div style="max-width: 60%;margin-left: 15%">
				<div class="jumbotron">
			    	<h2>Liste des Heurs des Séances</h2><br/>
					<table id="list_table" class="display" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>ID</th>
								<th>PromoID</th>
								<th>Promo</th>
								<th>Heurs</th>
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
	#list_table th:nth-child(2), #list_table td:nth-child(2){display: none !important;}
	.sweet-alert{z-index: 9999999 !important; }
	.sweet-overlay{z-index: 9999998 !important; }
</style>

<link rel="stylesheet" type="text/css" href="../css/sweetalert.css"/>
<script type="text/javascript" src="../js/sweetalert.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui.js"></script>

<script>
	$(document).ready(function(){
		$('#sessions-list').sortable({  
		 	helper: fixHelper
		}).disableSelection();


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
			}
		});


		getSessions();

	});

	function getSessions(){
		$('#sessions-list').html('');
		$.ajax({
			type:'POST',
			url:'config.timing.php',
			data: {
				course: $('.select#course').val(),
				getSessions: "*"
			},
			success: function(result){
				var rows = result.trim().split('\\n');
				for(var i = 0;i<rows.length;i++){
					var r = rows[i].split('|');
					if(r.length != 2)
						continue;
					var s = "<tr>";
						s += "<td class='session-time' id='"+r[0]+"'>"
						s += "<span id='session-lbl'>"+r[1]+"</span>"
						s += "<input id='session-input' type='text' class='form-control input-md'/>";
						s += "</td>";
						s += "<td id='session-edit'><a>Modifier</a></td>";
						s += "<td id='session-delete'><a>Supprimer</a></td>";
						s += "</tr>";
					$('#sessions-list').append(s); 
				}
			}
		});
	}

	$('#add').on('click', function(){
		$('#sessions-list').html('');
		$('.select#course').change();
	});

	$('.select#course').on('change', function(){
		getSessions();
	});

	$('#addSession').on('click', function(){
		$.ajax({
			type:'POST',
			url:'config.timing.php',
			data: {
				course: $('.select#course').val(),
				addSession: $('#session').val()
			},
			success: function(result){
				if(result.trim().indexOf('error') > -1){
				//	swal("Erreur", "Séance existe déja", "error");
					alert("Séance existe déja", "error");
					return;
				}
			//	swal("Succès!", "Enregistré avec succès", "success");
			//	alert("Enregistré avec succès");
				var s = "<tr>";
					s += "<td class='session-time' id='"+result+"'>"
					s += "<span id='session-lbl'>"+$('#session').val()+"</span>"
					s += "<input id='session-input' type='text' class='form-control input-md'/>";
					s += "</td>";
					s += "<td id='session-edit'><a>Modifier</a></td>";
					s += "<td id='session-delete'><a>Supprimer</a></td>";
					s += "</tr>";
				$('#sessions-list').append(s); 
				$('#session').val('');
				$('#list_table').DataTable().ajax.reload();
			}
		});
	});

	$('#saveSort').on('click', function(){
		var s = '';
		$('#sessions-list tr').each(function(){
			s += $(this).find('#session-lbl').text()+'<br/>';
		});
		$.ajax({
			type:'POST',
			url:'config.timing.php',
			data: {
				course: $('.select#course').val(),
				saveSort: s
			},
			success: function(result){
				if(result.trim().indexOf('error') > -1){
			//		swal("Erreur", "Il y a eu quelques erreurs", "error");
					alert("Il y a eu quelques erreurs");
					return;
				}
				getSessions();
			//	swal("Succès!", "Enregistré avec succès", "success");
			//	alert("Enregistré avec succès");
				$('#list_table').DataTable().ajax.reload();
			}
		});
	});

	

	$(document).on('click', '#session-edit a', function(){
		if($(this).text() == 'Modifier'){
			$(this).text('Sauvgarder');
			$(this).parent().parent().find('#session-input').val($(this).parent().parent().find('#session-lbl').text());
			$(this).parent().parent().find('#session-lbl').css('display','none');
			$(this).parent().parent().find('#session-input').css('display','block');
			$(this).parent().parent().find('#session-input').focus();
			$('#sessions-list').enableSelection();
		}else{
			if($(this).parent().parent().find('#session-input').val().trim() == "")
				return;

			var this_ = $(this);

			$.ajax({
				type:'POST',
				url:'config.timing.php',
				data: {
					editSession: $(this).parent().parent().find('#session-input').val(),
					sID: $(this).parent().parent().find('.session-time').attr('id')
				},
				success: function(result){
					if(result.trim().indexOf('error') > -1){
					//	swal("Erreur", "Une erreur se produit, veuillez réessayer", "error");
						alert("Une erreur se produit, veuillez réessayer");
						return;
					}
				//	swal("Succès!", "Enregistré avec succès", "success");
				//	alert("Enregistré avec succès");
					this_.text('Modifier');
					this_.parent().parent().find('#session-lbl').text(this_.parent().parent().find('#session-input').val());
					this_.parent().parent().find('#session-lbl').css('display','block');
					this_.parent().parent().find('#session-input').css('display','none');
					$('#sessions-list').disableSelection();
					$('#list_table').DataTable().ajax.reload();
				}
			});
		}
	});


	$(document).on('click', '#session-delete a', function(){
		var this_ = $(this);

		$.ajax({
			type:'POST',
			url:'config.timing.php',
			data: {
				deleteSession: $(this).parent().parent().find('.session-time').attr('id')
			},
			success: function(result){
				if(result.trim().indexOf('error') > -1){
				//	swal("Erreur", "Une erreur se produit, veuillez réessayer", "error");
					alert("Une erreur se produit, veuillez réessayer");
					return;
				}
			//	swal("Succès!", "Supprimé avec succès", "success");
			//	alert("Supprimé avec succès");
				this_.parent().parent().remove();
				$('#list_table').DataTable().ajax.reload();
			}
		});
	});

	
	var fixHelper = function(e, ui) {  
	  	ui.children().each(function() {  
	    	$(this).width($(this).width());  
	  	});  
	  	return ui;  
	};
</script>



<link rel="stylesheet" type="text/css" href="../css/styles.forms.css"/>
<script type="text/javascript" src="../js/forms.js"></script>

<link rel="stylesheet" type="text/css" href="../css/materialize.min-select.css"/>
<script type="text/javascript" src="../js/materialize.min.js"></script>

<script>
	$(document).on('click', '#delete-row', function(){
		$.ajax({
			type:'POST',
			url:'config.timing.php',
			data: {
				course: $(this).parent().parent().find('td:nth-child(2)').text(),
				deleteTiming: 'true'
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

	$(document).on('click','#edit-row', function(){
		$('.select#course').val($(this).parent().parent().find('td:nth-child(2)').text());
		$('.select#course').material_select('destroy');
		$('.select#course').material_select();
		$('.select#course').change();

		$('#submit').css('display','none');
		$('#update').css('display','block');		
		$('#modal-title').text('Modifier');
		$('.form-modal').css('left','0%');
		$('.form-modal-close').css('display','block');
	});

</script>