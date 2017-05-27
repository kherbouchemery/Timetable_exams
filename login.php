<?php
   session_start();
   //$path = $_SERVER['DOCUMENT_ROOT'];
  // $path .= "/timetable/header.php";
   include_once('header.php');

//   $path = $_SERVER['DOCUMENT_ROOT'];
  // $path .= "/timetable/class.ManageUsers.php";
   include_once('class.ManageUsers.php');

   $users = new ManageUsers();

   

   if(isset($_SESSION['name']) && isset($_SESSION['user_id']) && !empty($_SESSION['name']) && !empty($_SESSION['user_id']))
	{
		header("location: dashboard/dashboard.php");
	}


   if(isset($_POST['lgn']))
	{
		$username = $_POST['username'];
		$password = $_POST['password'];

		$count = $users->LoginUsers($username, $password);
		if($count ==0)
		{
			echo "Vous n'ête pas inscrit!";
		}
		else if($count == 1)
		{
			$make_sessions = $users->GetUserInfo($username);
				foreach($make_sessions as $userSessions)
				{
					$_SESSION['name'] = $userSessions['username'];
					$_SESSION['user_id'] = $userSessions['user_id'];
					if(isset($_SESSION['name']))
					{
						header("location: dashboard/dashboard.php");
					}
				}
		}

	}


?>
<body>
	<nav class="navbar navbar-default navbar-static-top">
	  <div class="container">
	  <h3>Gestion des Emplois du Temps</h3>
	  </div>
	</nav>

	<div id="content">
		<div id="form">
		<form class="form-horizontal" method="post" action="">
			<fieldset>

			<!-- Form Name -->
			<legend>Authentification</legend>

			<!-- Text input-->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="username">Nom d'utilisateur</label>
			  <div class="col-md-4">
			  <input id="username" name="username" type="text" placeholder="" class="form-control input-md" required="">

			  </div>
			</div>

			<!-- Password input-->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="password">Mot de Passe</label>
			  <div class="col-md-4">
				<input id="password" name="password" type="password" placeholder="" class="form-control input-md" required="">

			  </div>
			</div>

			<!-- Button -->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="login"></label>
			  <div class="col-md-4">
				<input type="submit" name="lgn" class="btn btn-success" value="Se Connecter">
			  </div>
			</div>

			</fieldset>
		</form>
		</div>
	</div>

</body>
<?php
   //$path = $_SERVER['DOCUMENT_ROOT'];
   //$path .= "/timetable/footer.php";
   include_once('footer.php');
?>
