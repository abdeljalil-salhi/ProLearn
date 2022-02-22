<?php
require_once('includes/variables.php');

if(isset($_SESSION['teacher_username'])){
    header('Location: teacher/index.php');
}else if(isset($_SESSION['parents_username'])){
    header('Location: parents/index.php');
}else if(isset($_SESSION['student_username'])){
    header('Location: student/index.php');
}else{
    if(!isset($_SESSION['I_COUNT'])){
        $select = $db->prepare("SELECT * FROM analytics WHERE title='visits'");
        $select->execute();
        $data = $select->get_result();
        $select->close();
        if(!$data) exit('No rows');
        while($row = $data->fetch_assoc()){
            $visitors[] = $row['value'];
        }
        $count = intval($visitors[0]);
        $count++;
        $insert = $db->prepare("UPDATE analytics SET value=? WHERE title='visits'");
        $insert->bind_param("i", $count);
        $insert->execute();
        $insert->close();
        $_SESSION['I_COUNT'] = 1;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $SCHOOLNAME; ?> - Professeurs</title>
    <link rel="stylesheet" href="styles/style-parents.css"/>
    <link rel="icon" type="image/png" href="ressources/favicon_192x192.png" sizes="192x192"/>
</head>
<body>
    <noscript>
        Java script non activé. Veuillez le réactiver.
    </noscript>
    <!--BACKGROUND and HEADER-->
    <img src="ressources/background_1920x1200.jpg" class="background" draggable="false"/>
    <header>
        <img src="ressources/logo_500x250.png" alt="plateforme proLearn" class="logo" draggable="false"/>
        <h1 class="name"><?php echo $SCHOOLNAME; ?></h1>
    </header>
    <!------------------------->
    <div class="parent">
        <div class="box">
            <img src="ressources/card_350x350.png" class="homecard" draggable="false"/>
            <div class="mobile">
                <img src="ressources/logo_500x250.png" alt="plateforme proLearn" class="logomobile" draggable="false"/>
                <h1 class="namemobile"><?php echo $SCHOOLNAME; ?></h1>
            </div>
        </div>
        <div class="box boxcenter">
            <div class="spaces">
                <div class="spacespadding">
                    <div class="header-parents hr">
                        <center>
                            <b class="header-parents-b">Espace Parents</b><br>
                            <img src="ressources/icons/hd/parents.png" height=150 draggable="false" alt="Espace Professeurs"/>
                        </center>
                    </div>
                    <form method="POST" action="parents/login.php" id="parentslogin">
                        <center>
                            <input type="text" name="username" class="input-parents pr" placeholder="Identifiant proLearn" autocomplete="false" required/><br>
                            <input type="password" name="password" class="input-parents" id="password" placeholder="Mot de passe proLearn" autocomplete="false" required/><br>
                            <input type="checkbox" class="checkbox-parents pr" onclick="toggleVisibility()"><small class="showpassword"> Montrer le mot de passe</small><br>
                        </center>
                    </form>
                </div>
                <button type="submit" form="parentslogin" class="submit-parents" value="Se connecter">Se connecter</button>
            </div>
        </div>
    </div>
    <!--RESSOURCES-->
    <script src="js/functions.js"></script>
</body>
</html>
<?php
}
?>