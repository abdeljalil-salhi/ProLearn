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
    <title><?php echo $SCHOOLNAME; ?> - Accueil</title>
    <link rel="stylesheet" href="styles/style-index.css"/>
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
    <!-- --------------------- -->
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
                <a href="teacher.php" class="space hr">
                    <img src="ressources/icons/hd/teacher.png" class="icon_space" draggable="false"/>
                    <div class="text_space">Espace professeurs</div>
                </a>
                <a href="parents.php" class="space hr">
                    <img src="ressources/icons/hd/parents.png" class="icon_space" draggable="false"/>
                    <div class="text_space">Espace parents</div>
                </a>
                <a href="student.php" class="space">
                    <img src="ressources/icons/hd/student.png" class="icon_space" draggable="false"/>
                    <div class="text_space">Espace élèves</div>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
<?php
}
?>