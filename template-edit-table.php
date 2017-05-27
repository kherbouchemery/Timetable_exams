<?php
  if(isset($_GET['logout']))
  {
    session_start();
    ob_start();
    $_SESSION['name'] = null;
    $_SESSION['user_id'] = null;
    unset($_SESSION['name']);
    unset($_SESSION['user_id']);
    echo 'sdfsdf';
    header("location: login.php");
    exit();
  }
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="A front-end template that helps you build fast, modern mobile web apps.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>Material Design Lite</title>

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="192x192" href="images/android-desktop.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Material Design Lite">
    <link rel="apple-touch-icon-precomposed" href="images/ios-desktop.png">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="images/touch/ms-touch-icon-144x144-precomposed.png">
    <meta name="msapplication-TileColor" content="#3372DF">

    <link rel="shortcut icon" href="images/favicon.png">

    <!-- SEO: If your mobile URL is different from the desktop URL, add a canonical link to the desktop page https://developers.google.com/webmasters/smartphone-sites/feature-phones -->
    <!--
    <link rel="canonical" href="http://www.example.com/">
    -->

    <link rel="stylesheet" href="css/Material_icons.css"/>
    <link rel="stylesheet" href="css/Material_fonts.css"/>
    <link rel="stylesheet" href="css/material.cyan-light_blue.min.css"/>
    <link rel="stylesheet" href="styles.css"/>
  </head>
  <body>
    <div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
      <header class="demo-header mdl-layout__header mdl-color--grey-100 mdl-color-text--grey-600">
        <div class="mdl-layout__header-row">
          <img src="images/logo.png" style="width:120px; margin-left:-15px; margin-right:2%;">
          <span class="mdl-layout-title"><?php echo $title;?></span>
          <div class="mdl-layout-spacer"></div>
          <div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable">
            <label class="mdl-button mdl-js-button mdl-button--icon" for="search">
              <i class="material-icons">search</i>
            </label>
            <div class="mdl-textfield__expandable-holder">
              <input class="mdl-textfield__input" type="text" id="search">
              <label class="mdl-textfield__label" for="search">Enter your query...</label>
            </div>
          </div>
          <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="hdrbtn">
            <i class="material-icons">more_vert</i>
          </button>
          <ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right" for="hdrbtn">
            <li class="mdl-menu__item">A Propos</li>
            <li class="mdl-menu__item">Contactez-Nous</li>
            <li class="mdl-menu__item">Documentation</li>
          </ul>
        </div>
      </header>
      <div class="demo-drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50 menu-list" style="width:260px; position:fixed; left:-300px;">
        <header class="demo-drawer-header">
          <img src="images/user.jpg" class="demo-avatar">
          <div class="demo-avatar-dropdown">
            <span>Bienvenue <b><?php if(@$_SESSION['name']) echo $_SESSION['name']; ?></b></span>
            <div class="mdl-layout-spacer"></div>
            <button id="accbtn" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon">
              <i class="material-icons" role="presentation">arrow_drop_down</i>
              <span class="visuallyhidden">Accounts</span>
            </button>
            <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="accbtn">
              <li class="mdl-menu__item" onclick="window.location = window.location.href+'&logout=true';"><i class="material-icons">input</i> &nbsp;&nbsp;&nbsp;Se Déconnecter</li>
            </ul>
          </div>
        </header>
        <nav class="demo-navigation mdl-navigation mdl-color--blue-grey-800">
          <a class="mdl-navigation__link" href="dashboard/dashboard.php"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">home</i>Acceuil</a>
          <a class="mdl-navigation__link" href="dashboard/add.timetable.php"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">view_module</i>Emplois Temps</a>
          <a class="mdl-navigation__link" href="dashboard/add.faculty.php"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">domain</i>Facultés</a>
          <a class="mdl-navigation__link" href="dashboard/add.departement.php"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">account_balance</i>Départements</a>
          <a class="mdl-navigation__link" href="dashboard/add.subject.php"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">school</i>Modules</a>
          <a class="mdl-navigation__link" href="dashboard/add.course.php"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">people</i>Promos</a>
          <a class="mdl-navigation__link" href="dashboard/add.groupe.php"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">person_pin</i>Groupes</a>
          <a class="mdl-navigation__link" href="dashboard/add.teacher.php"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">person</i>Enseignants</a>
          <a class="mdl-navigation__link" href="dashboard/add.classroom.php"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">map</i>Salles</a>
          <a class="mdl-navigation__link" href="dashboard/edit.timing.php"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">alarm</i>Séances</a>
          <div class="mdl-layout-spacer"></div>
        </nav>
      </div>


    </div>
      
    <script src="js/material.min.js"></script>
    <script>
      $(document).ready(function(){
          $(document).on('click','.mdl-layout__drawer-button',function(){
              $('.menu-list').css({
                'left':'0px',
                'transition':'all .1s ease-in'
              });
          });

          $(document).on('click','.mdl-layout__obfuscator',function(){
              $('.menu-list').css({
                'left':'-300px',
                'transition':'all .1s ease-in'
              });
          });

          $('.mdl-layout__header-row').css('margin-left','65px');
          $('.demo-navigation.mdl-navigation.mdl-color--blue-grey-800 a').css({
            'font-weight': 'bold', 
            'color': '#78909c'
          });
      });
    </script>
  </body>
</html>

<style>
  main{
    width:100%;
    position: relative;
    top: 60px;
    z-index: 0;
    padding-top:20px;
  }
  .demo-header.mdl-layout__header.mdl-color--grey-100.mdl-color-text--grey-600.is-casting-shadow{
    position: absolute;
    left: 0px;
    margin-left: 0;
    width:100%;
  }
  .container{
    width:100%;
    margin-top:100px;
    margin-left:200px;
  }

  .alert{
    position:fixed;
    top:16%;
    left:18%;
    z-index: 2;
    width:210px;
  }

  .demo-avatar{
    margin-left: 25%;
    margin-bottom: 10%;
  }
</style>
