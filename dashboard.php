<?php
   session_start();
   include_once('../header.php');

   $title = 'Acceuil';

   include_once("template.php");

   include_once('../class.database.php');

   if(!@$_SESSION['name']){
	   echo "Vous n'êtes pas encore connecté. Veuillez revenir et se connecter de nouveau!";
	   exit();
   }

?>

<body>
<main class="mdl-layout__content mdl-color--grey-100">
	<div class="container">
	  <div class="row">
	    <div style="max-width: 60%;margin-left: 15%">
    	<div class="jumbotron">
    		<table id="dashboard_table">
    			<tr>
					<td><div class="demo-card-square mdl-card mdl-shadow--2dp">
					  <div class="mdl-card__title mdl-card--expand" id="timetable">
					  	<div class='card_title'>
					  		<h2 class="mdl-card__title-text">Emplois du Temps</h2>
					  	</div>
					  </div>
					</div></td>
					<td><div class="demo-card-square mdl-card mdl-shadow--2dp">
					  <div class="mdl-card__title mdl-card--expand" id="faculty">
					  	<div class='card_title'>
					  		<h2 class="mdl-card__title-text">Facultés</h2>
					  	</div>
					  </div>
					</div></td>
					<td><div class="demo-card-square mdl-card mdl-shadow--2dp">
					  <div class="mdl-card__title mdl-card--expand" id="departement">
					  	<div class='card_title'>
					  		<h2 class="mdl-card__title-text">Départements</h2>
					  	</div>
					  </div>
					</div></td>
				</tr>
				<tr>
					<td><div class="demo-card-square mdl-card mdl-shadow--2dp">
					  <div class="mdl-card__title mdl-card--expand" id="course">
					  	<div class='card_title'>
					  		<h2 class="mdl-card__title-text">Promos</h2>
					  	</div>
					  </div>
					</div></td>
					<td><div class="demo-card-square mdl-card mdl-shadow--2dp">
					  <div class="mdl-card__title mdl-card--expand" id="subject">
					  	<div class='card_title'>
					  		<h2 class="mdl-card__title-text">Modules</h2>
					  	</div>
					  </div>
					</div></td>
					<td><div class="demo-card-square mdl-card mdl-shadow--2dp">
					  <div class="mdl-card__title mdl-card--expand" id="groupe">
					  	<div class='card_title'>
					  		<h2 class="mdl-card__title-text">Groupes</h2>
					  	</div>
					  </div>
					</div></td>
				</tr>
				<tr>
					<td><div class="demo-card-square mdl-card mdl-shadow--2dp">
					  <div class="mdl-card__title mdl-card--expand" id="teacher">
					  	<div class='card_title'>
					  		<h2 class="mdl-card__title-text">Enseignants</h2>
					  	</div>
					  </div>
					</div></td>
					<td><div class="demo-card-square mdl-card mdl-shadow--2dp">
					  <div class="mdl-card__title mdl-card--expand" id="classroom">
					  	<div class='card_title'>
					  		<h2 class="mdl-card__title-text">Salles</h2>
					  	</div>
					  </div>
					</div></td>
					<td><div class="demo-card-square mdl-card mdl-shadow--2dp">
					  <div class="mdl-card__title mdl-card--expand" id="timing">
					  	<div class='card_title'>
					  		<h2 class="mdl-card__title-text">Séances</h2>
					  	</div>
					  </div>
					</div></td>
				</tr>
			</table>
		</div>
    </div>
  </div>
</div>
</main>

<style>
.demo-card-square.mdl-card {
  width: 100%;
  height: 100%;
  border-radius: 10px;
  cursor:pointer;
  transition:all .1s ease-in;
}

.demo-card-square.mdl-card:hover{
	box-shadow: 1px 5px 2px #00cccc;
}

#timetable{background-image: url('../images/timetable.png');}
#faculty{background-image: url('../images/faculty.png');}
#course{background-image: url('../images/course.png');}
#subject{background-image: url('../images/subject.png');}
#groupe{background-image: url('../images/groupe.png');}
#teacher{background-image: url('../images/teacher.png');}
#classroom{background-image: url('../images/classroom.png');}
#timing{background-image: url('../images/timing.png');}
#departement{background-image: url('../images/departement.png');}

.demo-card-square > .mdl-card__title {
  	background-size: 100% 100%;
	background-repeat: no-repeat;
	background-position: 50% 50%;
}
.mdl-card__title-text{
	 font:20px arial, sans-serif;
}
.card_title{
	background-color:white;
	width:100%;
	padding: 7px;
	float: left;
	position: absolute;
	left: 0;
	bottom: 0;
	opacity:.8;
	color: #46B6AC;
  	font-size: 16px;
  	font-weight: bold;
}
#dashboard_table{width:100%;}
#dashboard_table td{
	padding:20px;
	width:33%;
}
</style>

<script type="text/javascript" src="../js/jquery-2.1.4.js"></script>
<script>
	$(".mdl-card__title.mdl-card--expand").on('click',function(){
		if($(this).attr('id') != 'timing')
			window.location = 'add.'+$(this).attr('id')+'.php';
		else
			window.location = 'edit.timing.php';
	});
</script>