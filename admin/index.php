<?php
require_once('../includes/variables.php');

if(isset($_SESSION['staff_id'])){
    header('Location: admin.php');
}else{
    if(isset($_POST['submit'])){
        $username = $_POST['username'];
        $password = htmlspecialchars($_POST['password']);
        if($username&&$password){
            $select = $db->prepare("SELECT * FROM staff WHERE username=?");
            $select->bind_param("s", $username);
            $select->execute();
            if($select){
                $select->close();
                $select = $db->prepare("SELECT * FROM staff WHERE username=?");
                $select->bind_param("s", $username);
                $select->execute();
                $data = $select->get_result();
                if(!$data) exit('No rows');
                while($row = $data->fetch_assoc()){
                    $ids[] = $row['id'];
                    $usernames[] = $row['username'];
                    $passwords[] = $row['password'];
                    $ranks[] = $row['rank'];
                    if(password_verify($password, $passwords[0])){
                        $_SESSION['staff_username'] = $username;
                        $_SESSION['staff_id'] = $ids[0];
                        $_SESSION['staff_rank'] = $ranks[0];
                        header('Location: admin.php');
                    }
                }
            }
            $select->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $SCHOOLNAME; ?> - Admin</title>
    <link rel="stylesheet" href="styles/style-index.css"/>
    <link rel="icon" type="image/png" href="../ressources/favicon_192x192.png" sizes="192x192"/>
    <!--
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    -->
</head>
<body>
    <noscript>
        Java script non activé. Veuillez le réactiver.
    </noscript>
    <div class="adminlogin">
        <div class="avatar">
            <h2>Espace ADMIN</h2>
            <img src="../ressources/icons/hd/secretary2.png" draggable="false"/>
            <br><small>@proLearn</small>
        </div>
        <form action="?action=login" method="POST" class="loginform" id="adminlogin">
            <img src="../ressources/logo_500x400.png" width=180 draggable="false"/><br>
            <input type="text" class="input-admin" id="username" name="username" placeholder="Identifiant proLearn" autocomplete="off" required/>
            <input type="password" class="input-admin" id="password" name="password" placeholder="Mot de passe proLearn" autocomplete="off" required/><br>
            <input type="checkbox" class="checkbox-admin" onclick="toggleVisibility()"><small class="showpassword"> Montrer le mot de passe</small> 
        </form>
        <button type="submit" name="submit" form="adminlogin" class="submit-admin" value="Se connecter">
            <svg width="40px" viewBox="0 0 16 16" class="bi bi-box-arrow-in-right" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0v-2z"/>
                <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
            </svg>
        </button>
    </div>
    <!--RESSOURCES-->
    <script src="../js/functions.js"></script>
</body>
</html>