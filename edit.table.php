<?php
	session_start();
	include_once 'header.php';

	$title = 'Génération d\'un Emploi du Temps { ';

	include_once('class.database.php');

	if($_SESSION['name']){
		if (isset($_GET['edit'])) {
			$timetable_id= $_GET['id'];
			$user_id= $_SESSION['user_id'];

			$db_connection = new dbConnection();
			$link = $db_connection->connect();

			$query = $link->query("SELECT * FROM timetable,course,groupe WHERE timetable_id = $timetable_id AND timetable.course_id = course.course_id AND groupe_id = groupe.id AND groupe.course_id = course.course_id");
		  	$query->setFetchMode(PDO::FETCH_ASSOC);
		   
		   	if($result = $query->fetch())
				$title .= '<span id="toPrint">Promo: <b>'.$result['course_name'].'</b> | Section: <b>'.$result['section'].'</b> | Groupe: <b>'.$result['name'].'</b></span> | Semèstre: <b>'.$result['semester'].'</b> | Année: <b>'.$result['year'].'</b> }';
		}
	}
	else{
		header("location: login.php");
	}

	include_once("template-edit-table.php");
?>



<body>

<style>
	#lists-menu{
		position:fixed;
		width:30px;
		z-index: 3;
		top:170px;
	}
	#lists-menu td{
		width:100%;
		background-color:#666666;
		color:white;
		border-bottom:solid 1px white;
		padding:5px;
		cursor:pointer;
	}

	.fixed-table{
		position: fixed;
		max-width: 20%;
		left: 35px;
		z-index: 3;
		display:none;
	}
	.fixed-table tbody tr{
		background-color:#eee;
	}
	.fixed-table tbody td{
		padding-left:15px !important;
	}
	.fixed-table tbody > tr:nth-of-type(2n+1){
		background-color:white;
	}
	.fixed-table th{
		padding: 6px !important;
		background-color: #666;
		color: white;
		font-size:18px;
		text-align: center;
	}
	.table-close{
		float:right;
		color:#aaa;
		cursor:pointer;
	}
	#table-subjects{top: 170px;}
	#table-teachers{top: 210px;}
	#table-classrooms{top: 250px;}
</style>

<table id="lists-menu">
	<tr><td id='btn-subjects'><i class="material-icons" role="presentation">school</i></td></tr>
	<tr><td id='btn-teachers'><i class="material-icons" role="presentation">person</i></td></tr>
	<tr><td id='btn-classrooms'><i class="material-icons" role="presentation">map</i></td></tr>
</table>


<!-- Context Menu -->
        
<div class="contextMenu">
	<div id="mDelete"><img src="images/remove.png"/>Supprimer</div>
</div>

<div class="time-context-menu">
	<div id="mShiftUp"><img src="images/shift_up.png"/>Décaler les cellules vers le haut</div>
	<div id="mShiftDown"><img src="images/shift_down.png"/>Décaler les cellules vers le bas</div>
</div>


<main class="mdl-layout__content">

<table id="type_sel" style="width: 30%;">
	<td style="display: none;">
		<div class="input-field col s12">
			<span style="font-size:10px; color:gray;">Type de Séance</span>
			<select class="select" id="type_seance">
				<option value="Cours">Cours</option>
				<option value="TD" selected>TD</option>
				<option value="TP">TP</option>
			</select>
		</div>
	</td>
	<td>
		<div class="input-field col s12">
			<span style="font-size:10px; color:gray;">Type de Salle</span>
			<select class="select" id="type_salle">
				<option value="Emphi">Emphi</option>
				<option value="Salle TD" selected>Salle TD</option>
				<option value="Labo">Labo</option>
			</select>
		</div>
	</td>
</table>


<table class="lists_table" >
	<tr>
		<td>

			<table id="table-subjects" class="table table-striped table-hover fixed-table">
			  <thead>
				<tr>
				  <th>Modules<i class="material-icons table-close" role="presentation">highlight_off</i></th> 
				</tr>
			  </thead>
			  <tbody>
				<?php
					if($_SESSION['name']){
						$query = $link->query("SELECT * FROM subject WHERE user_id = '$user_id'");
						$query->setFetchMode(PDO::FETCH_ASSOC);
						$i=0;
						while($result = $query->fetch()){
							echo "<tr><td><div class='subject-name draggable-item' id='".$result['subject_id']."'>".$result['subject_name']."</div></td></tr>";
						}
					}else{
						header("location: login.php");
					}
				?>
				</tbody>
			</table>

		</td>
		<td>

			<table id='table-teachers' class="table table-striped table-hover fixed-table">
			  <thead>
				<tr>
				  <th>Enseignants<i class="material-icons table-close" role="presentation">highlight_off</i></th> 
				</tr>
			  </thead>
			  <tbody id="teachers_list">
				<?php
					if($_SESSION['name']){
						$query = $link->query("SELECT * FROM teacher");
						$query->setFetchMode(PDO::FETCH_ASSOC);
						$i=0;
						while($result = $query->fetch()){
							echo "<tr><td><div class='teacher draggable-item' id='".$result['id']."'>".$result['last_name']." ".$result['first_name']."</div></td></tr>";
						}
					}else{
						header("location: login.php");
					}
				?>
				</tbody>
			</table>

		</td>
		<td>

			<table id="table-classrooms" class="table table-striped table-hover fixed-table">
			  <thead>
				<tr>
				  <th>Salles<i class="material-icons table-close" role="presentation">highlight_off</i></th> 
				</tr>
			  </thead>
			  <tbody id="classes_list">
				<?php
					if($_SESSION['name']){
						$query = $link->query("SELECT * FROM classroom WHERE type_cls = 'Emphi'");
						$query->setFetchMode(PDO::FETCH_ASSOC);
						$i=0;
						while($result = $query->fetch()){
							echo "<tr><td><div class='classroom draggable-item' id='".$result['num']."'>".$result['num']."</div></td></tr>";
						}
					}else{
						header("location: login.php");
					}
				?>
				</tbody>
			</table>

		</td>
	</tr>
</table>


<link rel="stylesheet" type="text/css" href="css/material.min.css"/>
<script type="text/javascript" src="js/material.min.js"></script>

<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="checkAvail" style="margin-left:2%; margin-bottom:20px; display: none;">
  <input type="checkbox" id="checkAvail" class="mdl-switch__input" checked>
  <span class="mdl-switch__label">Vérifier la disponibilité</span>
</label>

<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="erasedByEmptyCell" style="margin-left:2%; margin-bottom:20px; display: none;">
  <input type="checkbox" id="erasedByEmptyCell" class="mdl-switch__input" checked>
  <span class="mdl-switch__label">Ecraser par les cellules vides dans le décalage</span>
</label>



<table class="table table-striped table-hover" style="margin-left:.5%; width:99%;" id="tbl">
  <thead>
    <tr class="info days-header">
      <th style="width:50px;">Heurs/Jours</th>
      <th>Dimanche</th>
      <th>Lundi</th>
	  <th>Mardi</th>
	  <th>Mercredi</th>
	  <th>Jeudi</th>
    </tr>
  </thead>
  <tbody id="tablesheet-tbody">
    	
  </tbody>
</table>


<div class="fixed-action-btn horizontal">
	<a class="btn-floating btn-large red">
		<i class="large material-icons">mode_edit</i>
	</a>
	<ul>
		<li><a class="btn-floating green" id="print"><i class="material-icons">print</i></a></li>
		<li><a class="btn-floating green" id="reset"><i class="material-icons">delete_sweep</i></a></li>
		<li><a class="btn-floating yellow darken-1" id="revert"><i class="material-icons">undo</i></a></li>
		<li><a class="btn-floating red" id="save"><i class="material-icons">save</i></a></li>
	</ul>
</div>

<style>
	.btns-tooltip{
		position: fixed;
		padding:5px;
		background-color:#444;
		color:white;
		display: none;
		border-radius: 5px;
		font-size: 10px;
		z-index: 1200;
	}
	.days-header th,#tablesheet-tbody td:nth-child(1){
		background-color:rgb(55, 71, 79) !important;
		color:white;
	}

	.days-header th:nth-child(even),#tablesheet-tbody tr:nth-child(odd) td:nth-child(1){
		background-color:rgb(65, 84, 93) !important;
	}

</style>

<div class='btns-tooltip'></div>


<!--<a class="btn btn-danger" id="showErrors">Afficher les Erreurs</a>-->
<div id="err-log" style="display:none">
	<ul style="list-style-type: none">
		
	</ul>
</div>

</main>


<link rel="stylesheet" type="text/css" href="css/materialize.min-select.css"/>
<link rel="stylesheet" type="text/css" href="css/materialize.min-btn.css"/>
<script type="text/javascript" src="js/materialize.min.js"></script>
<style>
	#selForUnavail_cls, #selForUnavail_teacher{
		background-color:#3399ff;
	}
	.availableCell{
		background-color:#6BFD63;
	}
	.unavailableCell{
		background-color:#FF636E;
	}

	#selForUnavail_cls, #selForUnavail_teacher, .availableCell, .unavailableCell{
		transition: background-color .2s linear;
	}
	.time-th{
		min-width: 105px;
	}

</style>


<script>
	var seances = [];
	$(document).ready(function(){
		$(document).GetSessions(function(){
            $(document).GetTablesheet();
        });
	});


	function urlParam(name){
		var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
		return decodeURIComponent(results[1] || 0);
	}

	 // recuperations de tout les séances (dans le array seances)
	(function($){
        $.fn.GetSessions = function(callback){
            $.ajax({
				type:'POST',
				url:'dashboard/config.timing.php',
				data: {
					course: urlParam('timing'), // <=> $_GET['timing']
					getSessions: '*'
				},
				success: function(result){
					var rows = result.trim().split('\\n');
					for(var i = 0;i<rows.length;i++){
						var r = rows[i].split('|');
						if(r.length != 2)
							continue;
						seances.push(r[1]);
					}
					callback(); // appel de la fct GetTablesheet()
				}
			});
        }
    })(jQuery);


    (function($){
        $.fn.GetTablesheet = function(){
            var cellid = []; var subject = []; var subjectID = []; var typeSeance = []; var teacher = []; var teacherID = []; var classroom = []; var typeCls = [];
			
			$.ajax({
				type:'POST',
				url:'dashboard/config.tablesheet.php',
				data: {
					id: urlParam('id'), // <=> $_GET['id']
					getTablesheet: '*'
				},
				//recuper avec json_encode et decoder avec jQuery.parseJSON()
				success: function(result){
					var data = jQuery.parseJSON(result);
					$.each(data, function(i, value){ // parcourir tous les lignes 'tab', value <=> cellid i=0, <=> sub i = 1....
						switch(i){
							case 0: cellid = value; break;
							case 1: subject = value; break;
							case 2: subjectID = value; break;
							case 3: typeSeance = value; break;
							case 4: teacher = value; break;
							case 5: teacherID = value; break;
							case 6: classroom = value; break;
							case 7: typeCls = value; break;
						}
					});

					// affichage des valeurs 
					var s = "";
					for(var i = 0;i<seances.length;i++){
						s += "<tr id='"+(i+1)+"'><td class='info time-th' style='vertical-align: middle;'>"+seances[i]+"</td>";//id tr 
						for(var j = 1;j<=5;j++){
							s += "<td class='internal-cell' id='"+((i*5)+j)+"'>";
							s += "<p class='cell-subject'></p>";
							s += "<p class='cell-teacher'></p>";
							s += "<p class='cell-clsroom'></p>";
							s += "</td>";
						}
						s += "</tr>";
					}
					$('#tablesheet-tbody').html(s);
					// cellid[0] = 1, [1] = 5
					// remplir les valeurs du BDD $('.internal-cell#1')
					for(var i = 0;i<cellid.length;i++){
						//recuperer la cel avec id=cellid[i]
						//afficher les valeurs de chaque cel -module salle prof-
						$('.internal-cell#'+cellid[i]+' .cell-subject').append("<div class='subject-name deletable-item' id='"+subjectID[i]+"'>"+subject[i]+"</div>");
						$('.internal-cell#'+cellid[i]+' .cell-teacher').append("<div class='teacher deletable-item' id='"+teacherID[i]+"'>"+teacher[i]+"</div>");
						$('.internal-cell#'+cellid[i]+' .cell-clsroom').append("<div class='classroom deletable-item' id='"+classroom[i]+"'><span id='type_cls'>"+typeCls[i]+"</span> "+classroom[i]+"</div>");
					}

					dragDropSetup();
				}
			});
        };
    })(jQuery);
	
</script>


<script>
	$(document).ready(function() {
		$('.select').material_select(); // appliquer le theme de Material design sure chaque <select>
		$('#type_salle').change();
	});

	// changer la liste des salles dynamiquement par type..
	$('#type_salle').on('change', function(){
		$('#classes_list').html(''); // vider la liste a chaque fois
		// recuperer la liste des salles depuis le fichier 'dashboard/config.tablesheet.php'
		// par une requete ajax
		$.ajax({
	        type:'POST',
	        url:'dashboard/config.tablesheet.php',
	        data: {
	            getSalles: $(this).val() // type salle: Emphi / Salle TD/ Labo
	        },
	        success: function(result){
	            if(!result.trim()) return;
	            $('#classes_list').append(result); // ajouter le resultat a la liste
	            dragDropSetup(); // initialiser la nouvelle liste (drag/drop)
	        }
	    });
	    $('#checkAvail').change(); // initialiser la table
	});

	// selectionner une salle
	$(document).on('click', '#classes_list tr', function(){
		// soit selectionner ou déselectionner
		var unSelect = $(this).attr('id') == 'selForUnavail_cls';
		// déselectionner l'element courant (qui est déja selectionné)
		$('#selForUnavail_cls').removeAttr('id');
		// selectionner la nouvelle ligne (salle)
		if(!unSelect)
			$(this).attr('id', 'selForUnavail_cls');
		$('#checkAvail').change(); // verifier la disponibilité/initialiser la table
	});

	// meme pour les profs...
	$(document).on('click', '#teachers_list tr', function(){
		var unSelect = $(this).attr('id') == 'selForUnavail_teacher';
		$('#selForUnavail_teacher').removeAttr('id');
		if(!unSelect)
			$(this).attr('id', 'selForUnavail_teacher');
		$('#checkAvail').change();
	});


	// verifier la disponibilité
	$('#checkAvail').on('change', function(){
		// intitialisation des cellules
		$('.internal-cell').each(function(){
			$(this).removeClass('unavailableCell');
			$(this).removeClass('availableCell');
		});

		// disponibilité off ou aucun selection d'un prof/salle (quitter)
		if(!$(this).is(':checked') || (!$('#selForUnavail_cls').length && !$('#selForUnavail_teacher').length)){
			return;
		}


		$.ajax({
	        type:'POST',
	        url:'dashboard/config.tablesheet.php',
	        data: {
	            getUnavailCells_cls: $('#selForUnavail_cls .classroom').attr('id'), // id <=> 'num' dans bdd
	            getUnavailCells_teacher: $('#selForUnavail_teacher .teacher').attr('id') // same..
	        },
	        success: function(result){
	            if(!result.trim()) return;

	            // separer une chaine de characters par un charcter, ex: 5|20 => array(5,20);
	            var r = result.trim().split('|'); // r = array(5,20); r.forEach <=> i=1: unavailCellID = 20

	            // parcourir tous les cellules...
	            $('.internal-cell').each(function(){
	            	var cell = $(this);
	            	var isUnavailCell = false;
	            	r.forEach(function(unavailCellID){
	                    if(cell.attr('id') == unavailCellID){ // si la cellule courrant = ID (5)
							isUnavailCell = true; // cette cellule est occupé
							return;
	                    }
					});
					if(!isUnavailCell)
						$(this).addClass('availableCell'); // couleur vert
					else
						$(this).addClass('unavailableCell'); // couleur rouge
				});
	        }
	    });
	});

</script>

<script>
	$('#btn-subjects').on('mouseover', function(){
		$('#table-subjects').css('display','block');
		$('#table-teachers').css('display','none');
		$('#table-classrooms').css('display','none');
	});
	$('#btn-teachers').on('mouseover', function(){
		$('#table-teachers').css('display', 'block');
		$('#table-subjects').css('display','none');
		$('#table-classrooms').css('display','none');
	});
	$('#btn-classrooms').on('mouseover', function(){
		$('#table-classrooms').css('display', 'block');
		$('#table-subjects').css('display','none');
		$('#table-teachers').css('display','none');
	});

	$('.table-close').on('click', function(){
		$(this).parent().parent().parent().parent().css('display','none');
	});


	$('#save,#revert,#reset,#print').on('mouseover', function(){
		var pos = $(this).offset();
		$('.btns-tooltip').css({
			'display':'block',
			'left':(pos.left-20)+'px',
			'top':(pos.top-40)+'px'
		});

		switch($(this).attr('id')){
			case 'save':
				$('.btns-tooltip').text('Sauvgarder');
				break;
			case 'revert':
				$('.btns-tooltip').text('Rétablir les Modifications');
				break;
			case 'reset':
				$('.btns-tooltip').text('Vider la Table');
				break;
			case 'print':
				$('.btns-tooltip').text('Imprimer');
				break;
		}
	});

	$('#save,#revert,#reset,#print').on('mouseleave', function(){
		$('.btns-tooltip').css({
			'display':'none'
		});
	});



	function printData()
	{
	   var divToPrint=document.getElementById("tbl");
	   newWin= window.open("");
	   var title = "<h2 style='text-align:center;'>"+$('#toPrint').html()+"</h2><br/>";
	   var s = "<style>";
	   s += "table {border-collapse: collapse; text-align:center;} table, th, td {border: 1px solid black;}</style>";
	   newWin.document.write(title+divToPrint.outerHTML+s);
	   newWin.print();
	   newWin.close();
	}

	$('#print').on('click',function(){
		printData();
	})
</script>












