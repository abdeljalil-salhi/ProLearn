<?php
require_once('../includes/variables.php');

$users = 0;
if(isset($_SESSION['staff_id'])){
    if(isset($_GET['action'])){
        if($_GET['action'] == 'logout'){
            unset($_SESSION['staff_id']);
            unset($_SESSION['staff_username']);
            unset($_SESSION['staff_rank']);
            header('Location: index.php');
        }else if($_GET['action'] == 'deleted'){
            if(isset($_GET['do'])){
                if($_GET['do'] == 'restore' && $_GET['type'] == 'students'){
                    $idUser = intval($_GET['id']);
                    $select = $db->prepare("SELECT * FROM deleted_students WHERE id=?");
                    $select->bind_param("i", $idUser);
                    $select->execute();
                    $data = $select->get_result();
                    $select->close();
                    if(!$data) exit('No rows');
                    while($row = $data->fetch_assoc()){
                        $insert = $db->prepare("INSERT INTO students (id, username, firstname, lastname, gender, class, class_grp, birthdate, password) VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?)");
                        $insert->bind_param("ssssiiss", $row['username'], $row['firstname'], $row['lastname'], $row['gender'], $row['class'], $row['class_grp'], $row['birthdate'], $row['password']);
                        $insert->execute();
                        $insert->close();
                    }
                    $delete = $db->prepare("DELETE FROM deleted_students WHERE id=?");
                    $delete->bind_param("i", $_GET['id']);
                    $delete->execute();
                    $delete->close();
                    header('Location: admin.php?action=deleted&success=y');
                }else if($_GET['do'] == 'restore' && $_GET['type'] == 'teachers'){
                    $idUser = intval($_GET['id']);
                    $select = $db->prepare("SELECT * FROM deleted_teachers WHERE id=?");
                    $select->bind_param("i", $idUser);
                    $select->execute();
                    $data = $select->get_result();
                    $select->close();
                    if(!$data) exit('No rows');
                    while($row = $data->fetch_assoc()){
                        $insert = $db->prepare("INSERT INTO teachers (id, username, firstname, lastname, gender, birthdate, subject, password) VALUES ('', ?, ?, ?, ?, ?, ?, ?)");
                        $insert->bind_param("sssssis", $row['username'], $row['firstname'], $row['lastname'], $row['gender'], $row['birthdate'], $row['subject'], $row['password']);
                        $insert->execute();
                        $insert->close();
                    }
                    $delete = $db->prepare("DELETE FROM deleted_teachers WHERE id=?");
                    $delete->bind_param("i", $_GET['id']);
                    $delete->execute();
                    $delete->close();
                    header('Location: admin.php?action=deleted&success=y');
                }else if($_GET['do'] == 'restore' && $_GET['type'] == 'parents'){
                    $idUser = intval($_GET['id']);
                    $select = $db->prepare("SELECT * FROM deleted_parents WHERE id=?");
                    $select->bind_param("i", $idUser);
                    $select->execute();
                    $data = $select->get_result();
                    $select->close();
                    if(!$data) exit('No rows');
                    while($row = $data->fetch_assoc()){
                        $insert = $db->prepare("INSERT INTO parents (id, username, firstname, lastname, student_id, password) VALUES ('', ?, ?, ?, ?, ?)");
                        $insert->bind_param("sssis", $row['username'], $row['firstname'], $row['lastname'], $row['student_id'], $row['password']);
                        $insert->execute();
                        $insert->close();
                    }
                    $delete = $db->prepare("DELETE FROM deleted_parents WHERE id=?");
                    $delete->bind_param("i", $_GET['id']);
                    $delete->execute();
                    $delete->close();
                    header('Location: admin.php?action=deleted&success=y');
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
    <link rel="stylesheet" href="styles/style-admin.css"/>
    <link rel="icon" type="image/png" href="../ressources/favicon_192x192.png" sizes="192x192"/>
    <script>
    function searchTable(){
        var search, filter, table, tr, td, i;
        search = document.getElementById('searchSearch');
        filter = search.value.toUpperCase();
        table = document.getElementById('tableSearch');
        tr = table.getElementsByTagName('tr');
        for(i = 0; i < tr.length; i++){
            td0 = tr[i].getElementsByTagName('td')[0];
            td1 = tr[i].getElementsByTagName('td')[1];
            td2 = tr[i].getElementsByTagName('td')[2];
            if(td0 || td1 || td2){
                if(td0.innerHTML.toUpperCase().indexOf(filter) > -1 || td1.innerHTML.toUpperCase().indexOf(filter) > -1 || td2.innerHTML.toUpperCase().indexOf(filter) > -1){
                    tr[i].style.display = '';
                }else{
                    tr[i].style.display = 'none';
                }
            }
        }
    }
    </script>
</head>
<body>
    <noscript>
        Java script non activé. Veuillez le réactiver.
    </noscript>
    <div class="parent">
        <header class="header-side">
            <?php echo $SCHOOLNAME; ?>
        </header>
        <div class="left-side side-hidden"></div>
        <div class="main-side">
<?php
            if(isset($_GET['success'])){
                if($_GET['success'] == 'y'){
?>
            <div id="successmodal" class="modal-section" style="display:block;">
                <div class="modal-content">
                    <header class="modal-header">
                        <span class="modal-close" onclick="document.getElementById('successmodal').style.display='none';">&times;</span>
                        <h2>Restaur&eacute;!</h2>
                    </header>
                    <div class="modal-body">
                        <p>
                            <svg width="3em" height="3em" viewBox="0 0 16 16" fill="green" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M15.354 2.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L8 9.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                <path fill-rule="evenodd" d="M8 2.5A5.5 5.5 0 1 0 13.5 8a.5.5 0 0 1 1 0 6.5 6.5 0 1 1-3.25-5.63.5.5 0 1 1-.5.865A5.472 5.472 0 0 0 8 2.5z"/>
                            </svg><br>
                            Restaur&eacute;.
                            <br><br>
                        </p>
                    </div>
                    <footer class="modal-footer">
                        <a href="?action=deleted"><button class="submit-footer">OK</button></a>
                    </footer>
                </div>
            </div>
<?php
                }
            }
?>
            <a href="admin.php" style="text-decoration:none;color:#22282c;">
                <svg width="2em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
                </svg>
            </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <h2 style="display:inline-block">Liste des utilisateurs supprim&eacute;s</h2><br>
            <svg width="1.4em" height="1.4em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"/>
                <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
            </svg>
            <input class="search-input" type="text" placeholder="Chercher un tuteur..." id="searchSearch" onkeyup="searchTable()"/>
            <table class="search-table" id="tableSearch">
                <tr>
                    <th style="width:auto">ID</th>
                    <th style="width:30%">Nom complet</th>
                    <th style="width:40%">Type de compte</th>
                    <th style="width:30%">Actions</th>
                </tr>
<?php
                $select = $db->prepare("SELECT * FROM deleted_students");
                $select->execute();
                $data = $select->get_result();
                $select->close();
                if(!$data) exit('No rows');
                while($row = $data->fetch_assoc()){
?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo strtoupper($row['lastname']) .' '. $row['firstname']; ?></td>
                    <td>&Eacute;l&egrave;ve</td>
                    <td>
<?php
                if($_SESSION['staff_rank'] == 9){
?>
                        <a href="?action=deleted&do=restore&type=students&id=<?php echo $row['id']; ?>" class="ahref-button">Restaurer</a>
<?php
                }
?>
                    </td>
                </tr>
<?php
                }
                $select = $db->prepare("SELECT * FROM deleted_teachers");
                $select->execute();
                $data = $select->get_result();
                $select->close();
                if(!$data) exit('No rows');
                while($row = $data->fetch_assoc()){
?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo strtoupper($row['lastname']) .' '. $row['firstname']; ?></td>
                    <td>Professeur</td>
                    <td>
<?php
                if($_SESSION['staff_rank'] == 9){
?>
                        <a href="?action=deleted&do=restore&type=teachers&id=<?php echo $row['id']; ?>" class="ahref-button">Restaurer</a>
<?php
                }
?>
                    </td>
                </tr>
<?php
                }
                $select = $db->prepare("SELECT * FROM deleted_parents");
                $select->execute();
                $data = $select->get_result();
                $select->close();
                if(!$data) exit('No rows');
                while($row = $data->fetch_assoc()){
?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo strtoupper($row['lastname']) .' '. $row['firstname']; ?></td>
                    <td>Parents</td>
                    <td>
<?php
                if($_SESSION['staff_rank'] == 9){
?>
                        <a href="?action=deleted&do=restore&type=parents&id=<?php echo $row['id']; ?>" class="ahref-button">Restaurer</a>
<?php
                }
?>
                    </td>
                </tr>
<?php
                }
?>
            </table>
        </div>
        <footer class="footer-side">
            @proLearn&trade; 2020
        </footer>
    </div>
</body>
</html>
<?php
        }else if($_GET['action'] == 'students'){
            if(isset($_GET['do'])){
                if($_GET['do'] == 'reset'){
                    $idStudent = intval($_GET['id']);
                    $select = $db->prepare("SELECT * FROM students WHERE id=?");
                    $select->bind_param("i", $idStudent);
                    $select->execute();
                    $data = $select->get_result();
                    $select->close();
                    if(!$data) exit('No rows');
                    while($row = $data->fetch_assoc()){
                        $birthdatesROW[] = $row['birthdate'];
                        $usernamesROW[] = $row['username'];
                    }
                    $usernameStudent = $usernamesROW[0];
                    $birthdateStudent = $birthdatesROW[0];
                    $passwordStudent = $usernameStudent .'@'. $birthdateStudent;
                    $timeTarget = 0.05; //50ms
                    $cost = 8; //hash option
                    do{
                        $cost++;
                        $start = microtime(true);
                        password_hash($passwordStudent, PASSWORD_BCRYPT, ["cost"=>$cost]);
                        $end = microtime(true);
                    }while(($end - $start) < $timeTarget);
                    $options = [
                        "cost"=>$cost,
                    ];
                    $passwordStudent = password_hash($passwordStudent, PASSWORD_BCRYPT, $options);
                    $update = $db->prepare("UPDATE students SET password=? WHERE id=?");
                    $update->bind_param("si", $passwordStudent, $idStudent);
                    $update->execute();
                    $update->close();
                    header('Location: admin.php?action=students&success=y');
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
    <link rel="stylesheet" href="styles/style-admin.css"/>
    <link rel="icon" type="image/png" href="../ressources/favicon_192x192.png" sizes="192x192"/>
    <script>
    function searchTable(){
        var search, filter, table, tr, td, i;
        search = document.getElementById('searchSearch');
        filter = search.value.toUpperCase();
        table = document.getElementById('tableSearch');
        tr = table.getElementsByTagName('tr');
        for(i = 0; i < tr.length; i++){
            td0 = tr[i].getElementsByTagName('td')[0];
            td1 = tr[i].getElementsByTagName('td')[1];
            td2 = tr[i].getElementsByTagName('td')[2];
            if(td0 || td1 || td2){
                if(td0.innerHTML.toUpperCase().indexOf(filter) > -1 || td1.innerHTML.toUpperCase().indexOf(filter) > -1 || td2.innerHTML.toUpperCase().indexOf(filter) > -1){
                    tr[i].style.display = '';
                }else{
                    tr[i].style.display = 'none';
                }
            }
        }
    }
    </script>
</head>
<body>
    <noscript>
        Java script non activé. Veuillez le réactiver.
    </noscript>
    <div class="parent">
        <header class="header-side">
            <?php echo $SCHOOLNAME; ?>
        </header>
        <div class="left-side side-hidden"></div>
        <div class="main-side">
<?php
            if(isset($_GET['success'])){
                if($_GET['success'] == 'y'){
?>
            <div id="successmodal" class="modal-section" style="display:block;">
                <div class="modal-content">
                    <header class="modal-header">
                        <span class="modal-close" onclick="document.getElementById('successmodal').style.display='none';">&times;</span>
                        <h2>R&eacute;initialis&eacute;!</h2>
                    </header>
                    <div class="modal-body">
                        <p>
                            <svg width="3em" height="3em" viewBox="0 0 16 16" fill="green" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M15.354 2.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L8 9.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                <path fill-rule="evenodd" d="M8 2.5A5.5 5.5 0 1 0 13.5 8a.5.5 0 0 1 1 0 6.5 6.5 0 1 1-3.25-5.63.5.5 0 1 1-.5.865A5.472 5.472 0 0 0 8 2.5z"/>
                            </svg><br>
                            Mot de passe mis &egrave; jour!
                            <br><br><i>format:</i><br>
                            <b><i>nomprenom@jj-mm-aaaa</i></b>
                        </p>
                    </div>
                    <footer class="modal-footer">
                        <a href="?action=students"><button class="submit-footer">OK</button></a>
                    </footer>
                </div>
            </div>
<?php
                }
            }
            if(isset($_GET['do'])){
                if($_GET['do'] == 'edit'){
                    if(isset($_GET['process'])){
                        if(isset($_POST['editformsubmit'])){
                            $idStudent = intval($_GET['id']);
                            $newClassGrpStudent = $_POST['editClassValue'];
                            $select = $db->prepare("SELECT * FROM class_grps WHERE id=?");
                            $select->bind_param("i", $newClassGrpStudent);
                            $select->execute();
                            $data = $select->get_result();
                            $select->close();
                            if(!$data) exit('No rows');
                            while($row = $data->fetch_assoc()){
                                $valuesNewClassGrp[] = $row['class_id'];
                            }
                            $newClassStudent = $valuesNewClassGrp[0];
                            $update = $db->prepare("UPDATE students SET class=? WHERE id=?");
                            $update->bind_param("ii", $newClassStudent, $idStudent);
                            $update->execute();
                            $update->close();
                            $update = $db->prepare("UPDATE students SET class_grp=? WHERE id=?");
                            $update->bind_param("ii", $newClassGrpStudent, $idStudent);
                            $update->execute();
                            $update->close();
                        }
                        header('Location: admin.php?action=students');
                    }else{
                        $actualGroup = '';
                        $actualName = '';
                        $idStudent = $_GET['id'];
                        $select = $db->prepare("SELECT * FROM students WHERE id=?");
                        $select->bind_param("i", $idStudent);
                        $select->execute();
                        $data = $select->get_result();
                        $select->close();
                        if(!$data) exit('No rows');
                        while($row = $data->fetch_assoc()){
                            $GLOBALS['actualName'] = strtoupper($row['lastname']) .' '. $row['firstname'];
                            $select2 = $db->prepare("SELECT * FROM class_grps WHERE id=?");
                            $select2->bind_param("i", $row['class_grp']);
                            $select2->execute();
                            $data2 = $select2->get_result();
                            $select2->close();
                            if(!$data2) exit('No rows');
                            while($row2 = $data2->fetch_assoc()){
                                $select3 = $db->prepare("SELECT * FROM classes WHERE id=?");
                                $select3->bind_param("i", $row2['class_id']);
                                $select3->execute();
                                $data3 = $select3->get_result();
                                $select3->close();
                                if(!$data3) exit('No rows');
                                while($row3 = $data3->fetch_assoc()){
                                    $GLOBALS['actualGroup'] = $row3['name'] . $row2['name'];
                                }
                            }
                        }
?>
            <div id="editmodal" class="modal-section" style="display:block;">
                <div class="modal-content">
                    <header class="modal-header">
                        <span class="modal-close" onclick="document.getElementById('editmodal').style.display='none';">&times;</span>
                        <h2>Modifier le groupe</h2>
                    </header>
                    <div class="modal-body">
                        <b>&Eacute;l&egrave;ve:</b>&nbsp;<?php echo $actualName; ?><br>
                        <b>Groupe actuel:</b>&nbsp;<?php echo $actualGroup; ?><br><br><br>
                        <form action="?action=students&do=edit&id=<?php echo $idStudent; ?>&process=ok" method="POST" id="editformmodal">
                            <label>Changer &agrave;<span class="red">*</span>&nbsp;&nbsp;&nbsp;</label><br>
                            <select name="editClassValue" class="form-input" required>
                                <option value="">--Niveau/Groupe--</option>
<?php
                        $select = $db->prepare("SELECT * FROM classes");
                        $select->execute();
                        $data = $select->get_result();
                        $select->close();
                        if(!$data) exit('No rows');
                        while($row = $data->fetch_assoc()){
?>
                                <optgroup label="<?php echo $row['name']; ?>">
<?php
                            $select2 = $db->prepare("SELECT * FROM class_grps WHERE class_id=?");
                            $select2->bind_param("i", $row['id']);
                            $select2->execute();
                            $data2 = $select2->get_result();
                            $select2->close();
                            if(!$data2) exit('No rows');
                            while($row2 = $data2->fetch_assoc()){
?>
                                    <option value="<?php echo $row2['id']; ?>"><?php echo $row2['name']; ?></option>
<?php
                            }
?>
                                </optgroup>
<?php
                        }
?>
                            </select>
                        </form>
                    </div>
                    <footer class="modal-footer">
                        <button form="editformmodal" name="editformsubmit" class="submit-footer">OK</button>
                    </footer>
                </div>
            </div>
<?php
                    }
                }else if($_GET['do'] == 'delete'){
                    if($_SESSION['staff_rank'] == 9){
                        $select = $db->prepare("SELECT * FROM students WHERE id=?");
                        $select->bind_param("i", $_GET['id']);
                        $select->execute();
                        $data = $select->get_result();
                        $select->close();
                        if(!$data) exit('No rows');
                        while($row = $data->fetch_assoc()){
                            $insert = $db->prepare("INSERT INTO deleted_students (id, username, firstname, lastname, gender, class, class_grp, birthdate, last_seen, last_ip, last_device, password) VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                            $insert->bind_param("ssssiisssss", $row['username'], $row['firstname'], $row['lastname'], $row['gender'], $row['class'], $row['class_grp'], $row['birthdate'], $row['last_seen'], $row['last_ip'], $row['last_device'], $row['password']);
                            $insert->execute();
                            $insert->close();
                        }
                        $delete = $db->prepare("DELETE FROM students WHERE id=?");
                        $delete->bind_param("i", $_GET['id']);
                        $delete->execute();
                        $delete->close();
                        header('Location: admin.php?action=students');
                    }
                }
            }
?>
            <a href="admin.php" style="text-decoration:none;color:#22282c;">
                <svg width="2em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
                </svg>
            </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <h2 style="display:inline-block">Liste des &eacute;l&egrave;ves</h2><br>
            <svg width="1.4em" height="1.4em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"/>
                <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
            </svg>
            <input class="search-input" type="text" placeholder="Chercher un &eacute;l&egrave;ve..." id="searchSearch" onkeyup="searchTable()"/>
            <table class="search-table" id="tableSearch">
                <tr>
                    <th style="width:auto">ID</th>
                    <th style="width:35%">Nom complet</th>
                    <th style="width:20%">Groupe</th>
                    <th style="width:45%">Actions</th>
                </tr>
<?php
                $select = $db->prepare("SELECT * FROM students");
                $select->execute();
                $data = $select->get_result();
                $select->close();
                if(!$data) exit('No rows');
                while($row = $data->fetch_assoc()){
?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td>
                        <img src="imgs/<?php echo $row['id']; ?>.jpg" width=23 style="display:inline-block;margin-right:10px;border-radius:50%;"/>
                        <?php echo strtoupper($row['lastname']) .' '. $row['firstname']; ?></td>
                    <td>
<?php
                    $select2 = $db->prepare("SELECT * FROM class_grps WHERE id=?");
                    $select2->bind_param("i", $row['class_grp']);
                    $select2->execute();
                    $data2 = $select2->get_result();
                    $select2->close();
                    if(!$data2) exit('No rows');
                    while($row2 = $data2->fetch_assoc()){
                        $select3 = $db->prepare("SELECT * FROM classes WHERE id=?");
                        $select3->bind_param("i", $row2['class_id']);
                        $select3->execute();
                        $data3 = $select3->get_result();
                        $select3->close();
                        if(!$data3) exit('No rows');
                        while($row3 = $data3->fetch_assoc()){
                            echo $row3['name'] . $row2['name'];
                    }
                }
?>
                    </td>
                    <td>
                        <a href="?action=students&do=reset&id=<?php echo $row['id']; ?>" class="ahref-button">R&eacute;initialiser MDP</a>
                        <a href="?action=students&do=edit&id=<?php echo $row['id']; ?>" class="ahref-button2">Changer le groupe</a>
<?php
                if($_SESSION['staff_rank'] == 9){
?>
                        <a href="?action=students&do=delete&id=<?php echo $row['id']; ?>" class="ahref-button3">Supprimer</a>
<?php
                }
?>
                    </td>
                </tr>
<?php
            }
?>
            </table>
        </div>
        <footer class="footer-side">
            @proLearn&trade; 2020
        </footer>
    </div>
</body>
</html>
<?php
        }else if($_GET['action'] == 'teachers'){
            if(isset($_GET['do'])){
                if($_GET['do'] == 'reset'){
                    $idTeacher = intval($_GET['id']);
                    $select = $db->prepare("SELECT * FROM teachers WHERE id=?");
                    $select->bind_param("i", $idTeacher);
                    $select->execute();
                    $data = $select->get_result();
                    $select->close();
                    if(!$data) exit('No rows');
                    while($row = $data->fetch_assoc()){
                        $birthdatesROW1[] = $row['birthdate'];
                        $usernamesROW1[] = $row['username'];
                    }
                    $usernameTeacher = $usernamesROW1[0];
                    $birthdateTeacher = $birthdatesROW1[0];
                    $passwordTeacher = $usernameTeacher .'.prof@'. $birthdateTeacher;
                    $timeTarget = 0.05; //50ms
                    $cost = 8; //hash option
                    do{
                        $cost++;
                        $start = microtime(true);
                        password_hash($passwordTeacher, PASSWORD_BCRYPT, ["cost"=>$cost]);
                        $end = microtime(true);
                    }while(($end - $start) < $timeTarget);
                    $options = [
                        "cost"=>$cost,
                    ];
                    $passwordTeacher = password_hash($passwordTeacher, PASSWORD_BCRYPT, $options);
                    $update = $db->prepare("UPDATE teachers SET password=? WHERE id=?");
                    $update->bind_param("si", $passwordTeacher, $idTeacher);
                    $update->execute();
                    $update->close();
                    header('Location: admin.php?action=teachers&success=y');
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
    <link rel="stylesheet" href="styles/style-admin.css"/>
    <link rel="icon" type="image/png" href="../ressources/favicon_192x192.png" sizes="192x192"/>
    <script>
    function searchTable(){
        var search, filter, table, tr, td, i;
        search = document.getElementById('searchSearch');
        filter = search.value.toUpperCase();
        table = document.getElementById('tableSearch');
        tr = table.getElementsByTagName('tr');
        for(i = 0; i < tr.length; i++){
            td0 = tr[i].getElementsByTagName('td')[0];
            td1 = tr[i].getElementsByTagName('td')[1];
            td2 = tr[i].getElementsByTagName('td')[2];
            if(td0 || td1 || td2){
                if(td0.innerHTML.toUpperCase().indexOf(filter) > -1 || td1.innerHTML.toUpperCase().indexOf(filter) > -1 || td2.innerHTML.toUpperCase().indexOf(filter) > -1){
                    tr[i].style.display = '';
                }else{
                    tr[i].style.display = 'none';
                }
            }
        }
    }
    </script>
</head>
<body>
    <noscript>
        Java script non activé. Veuillez le réactiver.
    </noscript>
    <div class="parent">
        <header class="header-side">
            <?php echo $SCHOOLNAME; ?>
        </header>
        <div class="left-side side-hidden"></div>
        <div class="main-side">
<?php
            if(isset($_GET['success'])){
                if($_GET['success'] == 'y'){
?>
            <div id="successmodal" class="modal-section" style="display:block;">
                <div class="modal-content">
                    <header class="modal-header">
                        <span class="modal-close" onclick="document.getElementById('successmodal').style.display='none';">&times;</span>
                        <h2>R&eacute;initialis&eacute;!</h2>
                    </header>
                    <div class="modal-body">
                        <p>
                            <svg width="3em" height="3em" viewBox="0 0 16 16" fill="green" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M15.354 2.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L8 9.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                <path fill-rule="evenodd" d="M8 2.5A5.5 5.5 0 1 0 13.5 8a.5.5 0 0 1 1 0 6.5 6.5 0 1 1-3.25-5.63.5.5 0 1 1-.5.865A5.472 5.472 0 0 0 8 2.5z"/>
                            </svg><br>
                            Mot de passe mis &egrave; jour!
                            <br><br><i>format:</i><br>
                            <b><i>nomprenom.prof@jj-mm-aaaa</i></b>
                        </p>
                    </div>
                    <footer class="modal-footer">
                        <a href="?action=teachers"><button class="submit-footer">OK</button></a>
                    </footer>
                </div>
            </div>
<?php
                }
            }
            if(isset($_GET['do'])){
                if($_GET['do'] == 'edit'){
                    if(isset($_GET['process'])){
                        if(isset($_POST['editformsubmit'])){
                            $idTeacher = intval($_GET['id']);
                            $newSubjectTeacher = $_POST['editSubjectValue'];
                            $update = $db->prepare("UPDATE teachers SET subject=? WHERE id=?");
                            $update->bind_param("ii", $newSubjectTeacher, $idTeacher);
                            $update->execute();
                            $update->close();
                        }
                        header('Location: admin.php?action=teachers');
                    }else{
                        $actualGroup = '';
                        $actualSubject = '';
                        $idTeacher = $_GET['id'];
                        $select = $db->prepare("SELECT * FROM teachers WHERE id=?");
                        $select->bind_param("i", $idTeacher);
                        $select->execute();
                        $data = $select->get_result();
                        $select->close();
                        if(!$data) exit('No rows');
                        while($row = $data->fetch_assoc()){
                            $GLOBALS['actualName'] = strtoupper($row['lastname']) .' '. $row['firstname'];
                            $select2 = $db->prepare("SELECT * FROM subjects WHERE id=?");
                            $select2->bind_param("i", $row['subject']);
                            $select2->execute();
                            $data2 = $select2->get_result();
                            $select2->close();
                            if(!$data2) exit('No rows');
                            while($row2 = $data2->fetch_assoc()){
                                $GLOBALS['actualSubject'] = $row2['name'];
                            }
                        }
?>
            <div id="editmodal" class="modal-section" style="display:block;">
                <div class="modal-content">
                    <header class="modal-header">
                        <span class="modal-close" onclick="document.getElementById('editmodal').style.display='none';">&times;</span>
                        <h2>Changer la mati&egrave;re</h2>
                    </header>
                    <div class="modal-body">
                        <b>Professeur:</b>&nbsp;<?php echo $actualName; ?><br>
                        <b>Mati&egrave;re actuelle:</b>&nbsp;<?php echo $actualSubject; ?><br><br><br>
                        <form action="?action=teachers&do=edit&id=<?php echo $idTeacher; ?>&process=ok" method="POST" id="editformmodal">
                            <label>Changer &agrave;<span class="red">*</span>&nbsp;&nbsp;&nbsp;</label><br>
                            <select name="editSubjectValue" class="form-input" required>
                                <option value="">--Mati&egrave;re--</option>
<?php
                        $select = $db->prepare("SELECT * FROM subjects");
                        $select->execute();
                        $data = $select->get_result();
                        $select->close();
                        if(!$data) exit('No rows');
                        while($row = $data->fetch_assoc()){
?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
<?php
                        }
?>
                            </select>
                        </form>
                    </div>
                    <footer class="modal-footer">
                        <button form="editformmodal" name="editformsubmit" class="submit-footer">OK</button>
                    </footer>
                </div>
            </div>
<?php
                    }
                }else if($_GET['do'] == 'delete'){
                    if($_SESSION['staff_rank'] == 9){
                        $select = $db->prepare("SELECT * FROM teachers WHERE id=?");
                        $select->bind_param("i", $_GET['id']);
                        $select->execute();
                        $data = $select->get_result();
                        $select->close();
                        if(!$data) exit('No rows');
                        while($row = $data->fetch_assoc()){
                            $insert = $db->prepare("INSERT INTO deleted_teachers (id, username, firstname, lastname, gender, birthdate, subject, last_seen, last_ip, last_device, password) VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                            $insert->bind_param("sssssissss", $row['username'], $row['firstname'], $row['lastname'], $row['gender'], $row['birthdate'], $row['subject'], $row['last_seen'], $row['last_ip'], $row['last_device'], $row['password']);
                            $insert->execute();
                            $insert->close();
                        }
                        $delete = $db->prepare("DELETE FROM teachers WHERE id=?");
                        $delete->bind_param("i", $_GET['id']);
                        $delete->execute();
                        $delete->close();
                        header('Location: admin.php?action=teachers');
                    }
                }
            }
?>
            <a href="admin.php" style="text-decoration:none;color:#22282c;">
                <svg width="2em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
                </svg>
            </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <h2  style="display:inline-block">Liste des professeurs</h2><br>
            <svg width="1.4em" height="1.4em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"/>
                <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
            </svg>
            <input class="search-input" type="text" placeholder="Chercher un professeur..." id="searchSearch" onkeyup="searchTable()"/>
            <table class="search-table" id="tableSearch">
                <tr>
                    <th style="width:auto">ID</th>
                    <th style="width:35%">Nom complet</th>
                    <th style="width:20%">Mati&egrave;re</th>
                    <th style="width:45%">Actions</th>
                </tr>
<?php
                $select = $db->prepare("SELECT * FROM teachers");
                $select->execute();
                $data = $select->get_result();
                $select->close();
                if(!$data) exit('No rows');
                while($row = $data->fetch_assoc()){
?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo strtoupper($row['lastname']) .' '. $row['firstname']; ?></td>
                    <td>
<?php
                    $select2 = $db->prepare("SELECT * FROM subjects WHERE id=?");
                    $select2->bind_param("i", $row['subject']);
                    $select2->execute();
                    $data2 = $select2->get_result();
                    $select2->close();
                    if(!$data2) exit('No rows');
                    while($row2 = $data2->fetch_assoc()){
                        echo $row2['name'];
                    }
?>
                    </td>
                    <td>
                        <a href="?action=teachers&do=reset&id=<?php echo $row['id']; ?>" class="ahref-button">R&eacute;initialiser MDP</a>
                        <a href="?action=teachers&do=edit&id=<?php echo $row['id']; ?>" class="ahref-button2">Changer de mati&egrave;re</a>
<?php
                if($_SESSION['staff_rank'] == 9){
?>
                        <a href="?action=teachers&do=delete&id=<?php echo $row['id']; ?>" class="ahref-button3">Supprimer</a>
<?php
                }
?>
                    </td>
                </tr>
<?php
                }
?>
            </table>
        </div>
        <footer class="footer-side">
            @proLearn&trade; 2020
        </footer>
    </div>
</body>
</html>
<?php
        }else if($_GET['action'] == 'parents'){
            if(isset($_GET['do'])){
                if($_GET['do'] == 'reset'){
                    $idParents = intval($_GET['id']);
                    $select = $db->prepare("SELECT * FROM parents WHERE id=?");
                    $select->bind_param("i", $idParents);
                    $select->execute();
                    $data = $select->get_result();
                    $select->close();
                    if(!$data) exit('No rows');
                    while($row = $data->fetch_assoc()){
                        $usernamesROW2[] = $row['username'];
                    }
                    $usernameParents = $usernamesROW2[0];
                    $passwordParents = $usernameParents .'.parents@prolearn2020';
                    $timeTarget = 0.05; //50ms
                    $cost = 8; //hash option
                    do{
                        $cost++;
                        $start = microtime(true);
                        password_hash($passwordParents, PASSWORD_BCRYPT, ["cost"=>$cost]);
                        $end = microtime(true);
                    }while(($end - $start) < $timeTarget);
                    $options = [
                        "cost"=>$cost,
                    ];
                    $passwordParents = password_hash($passwordParents, PASSWORD_BCRYPT, $options);
                    $update = $db->prepare("UPDATE parents SET password=? WHERE id=?");
                    $update->bind_param("si", $passwordParents, $idParents);
                    $update->execute();
                    $update->close();
                    header('Location: admin.php?action=parents&success=y');
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
    <link rel="stylesheet" href="styles/style-admin.css"/>
    <link rel="icon" type="image/png" href="../ressources/favicon_192x192.png" sizes="192x192"/>
    <script>
    function searchTable(){
        var search, filter, table, tr, td, i;
        search = document.getElementById('searchSearch');
        filter = search.value.toUpperCase();
        table = document.getElementById('tableSearch');
        tr = table.getElementsByTagName('tr');
        for(i = 0; i < tr.length; i++){
            td0 = tr[i].getElementsByTagName('td')[0];
            td1 = tr[i].getElementsByTagName('td')[1];
            td2 = tr[i].getElementsByTagName('td')[2];
            if(td0 || td1 || td2){
                if(td0.innerHTML.toUpperCase().indexOf(filter) > -1 || td1.innerHTML.toUpperCase().indexOf(filter) > -1 || td2.innerHTML.toUpperCase().indexOf(filter) > -1){
                    tr[i].style.display = '';
                }else{
                    tr[i].style.display = 'none';
                }
            }
        }
    }
    </script>
</head>
<body>
    <noscript>
        Java script non activé. Veuillez le réactiver.
    </noscript>
    <div class="parent">
        <header class="header-side">
            <?php echo $SCHOOLNAME; ?>
        </header>
        <div class="left-side side-hidden"></div>
        <div class="main-side">
<?php
            if(isset($_GET['success'])){
                if($_GET['success'] == 'y'){
?>
            <div id="successmodal" class="modal-section" style="display:block;">
                <div class="modal-content">
                    <header class="modal-header">
                        <span class="modal-close" onclick="document.getElementById('successmodal').style.display='none';">&times;</span>
                        <h2>R&eacute;initialis&eacute;!</h2>
                    </header>
                    <div class="modal-body">
                        <p>
                            <svg width="3em" height="3em" viewBox="0 0 16 16" fill="green" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M15.354 2.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L8 9.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                <path fill-rule="evenodd" d="M8 2.5A5.5 5.5 0 1 0 13.5 8a.5.5 0 0 1 1 0 6.5 6.5 0 1 1-3.25-5.63.5.5 0 1 1-.5.865A5.472 5.472 0 0 0 8 2.5z"/>
                            </svg><br>
                            Mot de passe mis &egrave; jour!
                            <br><br><i>format:</i><br>
                            <b><i>nom.parents@prolearn2020</i></b>
                        </p>
                    </div>
                    <footer class="modal-footer">
                        <a href="?action=parents"><button class="submit-footer">OK</button></a>
                    </footer>
                </div>
            </div>
<?php
                }
            }
            if(isset($_GET['do'])){
                if($_GET['do'] == 'delete'){
                    if($_SESSION['staff_rank'] == 9){
                        $select = $db->prepare("SELECT * FROM parents WHERE id=?");
                        $select->bind_param("i", $_GET['id']);
                        $select->execute();
                        $data = $select->get_result();
                        $select->close();
                        if(!$data) exit('No rows');
                        while($row = $data->fetch_assoc()){
                            $insert = $db->prepare("INSERT INTO deleted_parents (id, username, firstname, lastname, student_id, last_seen, last_ip, last_device, password) VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?)");
                            $insert->bind_param("sssissss", $row['username'], $row['firstname'], $row['lastname'], $row['student_id'], $row['last_seen'], $row['last_ip'], $row['last_device'], $row['password']);
                            $insert->execute();
                            $insert->close();
                        }
                        $delete = $db->prepare("DELETE FROM parents WHERE id=?");
                        $delete->bind_param("i", $_GET['id']);
                        $delete->execute();
                        $delete->close();
                        header('Location: admin.php?action=parents');
                    }
                }
            }
?>
            <a href="admin.php" style="text-decoration:none;color:#22282c;">
                <svg width="2em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
                </svg>
            </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <h2 style="display:inline-block">Liste des parents</h2><br>
            <svg width="1.4em" height="1.4em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"/>
                <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
            </svg>
            <input class="search-input" type="text" placeholder="Chercher un tuteur..." id="searchSearch" onkeyup="searchTable()"/>
            <table class="search-table" id="tableSearch">
                <tr>
                    <th style="width:auto">ID</th>
                    <th style="width:30%">Nom complet</th>
                    <th style="width:40%">Tuteur de</th>
                    <th style="width:30%">Actions</th>
                </tr>
<?php
                $select = $db->prepare("SELECT * FROM parents");
                $select->execute();
                $data = $select->get_result();
                $select->close();
                if(!$data) exit('No rows');
                while($row = $data->fetch_assoc()){
?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo strtoupper($row['lastname']) .' '. $row['firstname']; ?></td>
                    <td>
<?php
                    $select2 = $db->prepare("SELECT * FROM students WHERE id=?");
                    $select2->bind_param("i", $row['student_id']);
                    $select2->execute();
                    $data2 = $select2->get_result();
                    $select2->close();
                    if(!$data2) exit('No rows');
                    while($row2 = $data2->fetch_assoc()){
                        echo strtoupper($row2['lastname']) .' '. $row2['firstname'];
                    }
?>
                    </td>
                    <td>
                        <a href="?action=parents&do=reset&id=<?php echo $row['id']; ?>" class="ahref-button">R&eacute;initialiser MDP</a>
<?php
                if($_SESSION['staff_rank'] == 9){
?>
                        <a href="?action=parents&do=delete&id=<?php echo $row['id']; ?>" class="ahref-button3">Supprimer</a>
<?php
                }
?>
                    </td>
                </tr>
<?php
                }
?>
            </table>
        </div>
        <footer class="footer-side">
            @proLearn&trade; 2020
        </footer>
    </div>
</body>
</html>
<?php
        }
    }else if(isset($_GET['page'])){
        if($_GET['page'] == 'subjects'){
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $SCHOOLNAME; ?> - Admin</title>
    <link rel="stylesheet" href="styles/style-admin.css"/>
    <link rel="icon" type="image/png" href="../ressources/favicon_192x192.png" sizes="192x192"/>
</head>
<body>
    <noscript>
        Java script non activé. Veuillez le réactiver.
    </noscript>
    <div class="parent">
        <header class="header-side">
            <?php echo $SCHOOLNAME; ?>
        </header>
        <div class="left-side side-hidden"></div>
        <div class="main-side">
<?php
            if(isset($_GET['class'])){
                $select = $db->prepare("SELECT * FROM class_grps WHERE id=?");
                $select->bind_param("i", $_GET['class']);
                $select->execute();
                $data = $select->get_result();
                $select->close();
                if(!$data) exit('No rows');
                while($row = $data->fetch_assoc()){
                    $select2 = $db->prepare("SELECT * FROM classes WHERE id=?");
                    $select2->bind_param("i", $row['class_id']);
                    $select2->execute();
                    $data2 = $select2->get_result();
                    $select2->close();
                    if(!$data2) exit('No rows');
                    while($row2 = $data2->fetch_assoc()){
?>      
            <a href="admin.php?page=subjects" style="text-decoration:none;color:#22282c;">
                <svg width="2em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
                </svg>
            </a>&nbsp;&nbsp;&nbsp;
            <h1 style="display:inline-block;"><?php echo $row2['name'] . $row['name']; ?></h1><br>
            <div class="floatright" id="divY" ondrop="dropY(event)" ondragover="allowDrop(event)">
<?php
                        $select4 = $db->prepare("SELECT * FROM classes_subjects WHERE grp_id=?");
                        $select4->bind_param("s", $_GET['class']);
                        $select4->execute();
                        $data4 = $select4->get_result();
                        $select4->close();
                        if(!$data4) exit('No rows');
                        while($row4 = $data4->fetch_assoc()){
                            $listSubjects = explode(" ", $row4['subjects']);
                            for($i = 0; $i < count($listSubjects); $i++){
                                $subjectsY[] = $listSubjects[$i];
                                $selectY = $db->prepare("SELECT * FROM subjects WHERE id=?");
                                $selectY->bind_param("s", $listSubjects[$i]);
                                $selectY->execute();
                                $dataY = $selectY->get_result();
                                $selectY->close();
                                while($rowY = $dataY->fetch_assoc()){                                
?>
                <div class="ticket" draggable="true" ondragstart="drag(event)" id="<?php echo $listSubjects[$i]; ?>"><?php echo $rowY['name']; ?></div>

<?php
                                }
                            }
                        }
?>
            </div>
            <div class="floatleft" id="divX" ondrop="dropX(event)" ondragover="allowDrop(event)">
<?php
                        $select3 = $db->prepare("SELECT * FROM subjects");
                        $select3->execute();
                        $data3 = $select3->get_result();
                        $select3->close();
                        if(!$data3) exit('No rows');
                        while($row3 = $data3->fetch_assoc()){
                            $select5 = $db->prepare("SELECT * FROM classes_subjects WHERE grp_id=?");
                            $select5->bind_param("s", $_GET['class']);
                            $select5->execute();
                            $data5 = $select5->get_result();
                            $select5->close();
                            while($row5 = $data5->fetch_assoc()){
                                $listSubjects2 = explode(" ", $row5['subjects']);
                            }
                            if(!in_array($row3['id'], $listSubjects2)){
?>
                <div class="ticket" draggable="true" ondragstart="drag(event)" id="<?php echo $row3['id']; ?>"><?php echo $row3['name']; ?></div>
<?php
                            }
                        }
?>
            </div>
            <script>
            function allowDrop(ev){
                ev.preventDefault();
            }
            function drag(ev){
                ev.dataTransfer.setData("text", ev.target.id);
            }
            function dropX(ev){
                ev.preventDefault();
                var data = ev.dataTransfer.getData("text");
                ev.target.appendChild(document.getElementById(data));
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.open("GET", 'ajax.php?class=<?php echo $_GET['class']; ?>&action=n&subject=' + data, true);
                xmlhttp.send();
            }
            function dropY(ev){
                ev.preventDefault();
                var data = ev.dataTransfer.getData("text");
                ev.target.appendChild(document.getElementById(data));
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.open("GET", 'ajax.php?class=<?php echo $_GET['class']; ?>&action=y&subject=' + data, true);
                xmlhttp.send();
            }
            </script>
<?php
                    }
                }
            }else{
?>
            <br><a href="admin.php" style="text-decoration:none;color:#22282c;">
                <svg width="2em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
                </svg>
            </a><h1 style="display:inline-block;">&nbsp;&nbsp;Groupes & Mati&egrave;res</h1>
<?php
                $select = $db->prepare("SELECT * FROM classes");
                $select->execute();
                $data = $select->get_result();
                $select->close();
                if(!$data) exit('No rows');
                while($row = $data->fetch_assoc()){
?>
            <h2><?php echo $row['name']; ?></h2>
<?php
                    $select2 = $db->prepare("SELECT * FROM class_grps WHERE class_id=?");
                    $select2->bind_param("i", $row['id']);
                    $select2->execute();
                    $data2 = $select2->get_result();
                    $select2->close();
                    if(!$data2) exit('No rows');
                    while($row2 = $data2->fetch_assoc()){
?>
            <a href="?page=subjects&class=<?php echo $row2['id']; ?>" class="ahref-button"><?php echo $row2['name']; ?></a></h5>
<?php
                    }
                }
            }
?>
        </div>
        <footer class="footer-side">
            @proLearn&trade; 2020
        </footer>
<?php
        }else if($_GET['page'] == 'teachers'){
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $SCHOOLNAME; ?> - Admin</title>
    <link rel="stylesheet" href="styles/style-admin.css"/>
    <link rel="icon" type="image/png" href="../ressources/favicon_192x192.png" sizes="192x192"/>
</head>
<body>
    <noscript>
        Java script non activé. Veuillez le réactiver.
    </noscript>
    <div class="parent">
        <header class="header-side">
            <?php echo $SCHOOLNAME; ?>
        </header>
        <div class="left-side side-hidden"></div>
        <div class="main-side">
<?php
            if(isset($_GET['class'])){
                $select = $db->prepare("SELECT * FROM class_grps WHERE id=?");
                $select->bind_param("i", $_GET['class']);
                $select->execute();
                $data = $select->get_result();
                $select->close();
                if(!$data) exit('No rows');
                while($row = $data->fetch_assoc()){
                    $select2 = $db->prepare("SELECT * FROM classes WHERE id=?");
                    $select2->bind_param("i", $row['class_id']);
                    $select2->execute();
                    $data2 = $select2->get_result();
                    $select2->close();
                    if(!$data2) exit('No rows');
                    while($row2 = $data2->fetch_assoc()){
?>      
            <a href="admin.php?page=teachers" style="text-decoration:none;color:#22282c;">
                <svg width="2em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
                </svg>
            </a>&nbsp;&nbsp;&nbsp;
            <h1 style="display:inline-block;"><?php echo $row2['name'] . $row['name']; ?></h1><br>
            <div style="margin:auto;width:50%;">
            <form method="POST" action="admin.php?page=teachers&class=<?php echo $row['id']; ?>&do=y">
<?php
                        $select3 = $db->prepare("SELECT * FROM classes_subjects WHERE grp_id=?");
                        $select3->bind_param("i", $_GET['class']);
                        $select3->execute();
                        $data3 = $select3->get_result();
                        $select3->close();
                        if(!$data3) exit('No rows');
                        while($row3 = $data3->fetch_assoc()){
                            $extract1 = $row3['subjects'];
                            $extract1 = explode(" ", $extract1);
                        }
                        for($i = 0; $i < count($extract1); $i++){
                            $select4 = $db->prepare("SELECT * FROM subjects WHERE id=?");
                            $select4->bind_param("i", $extract1[$i]);
                            $select4->execute();
                            $data4 = $select4->get_result();
                            $select4->close();
                            if(!$data4) exit('No rows');
                            while($row4 = $data4->fetch_assoc()){
?>
                <span style="display:flex;flex-wrap: wrap;justify-content: space-between;align-items: stretch;flex-direction: row;"><label><?php echo $row4['name']; ?></label>&nbsp;&nbsp;
                <select name="subject<?php echo $extract1[$i]; ?>" class="form-input">
                    <option value="">--Professeur--</option>
<?php
                                $select5 = $db->prepare("SELECT * FROM teachers WHERE subject=?");
                                $select5->bind_param("i", $extract1[$i]);
                                $select5->execute();
                                $data5 = $select5->get_result();
                                $select5->close();
                                if(!$data5) exit('No rows');
                                while($row5 = $data5->fetch_assoc()){
?>
                    <option value="<?php echo $row5['id']; ?>"><?php echo strtoupper($row5['lastname']) ." ". $row5['firstname']; ?></option>
<?php
                                }
?>
                </select></span><br>
<?php
                            }
                        }
?>
                <center>
                    <input style="padding:5px 50px;" type="submit" name="editClassTeachers" class="form_submit" value="Actualiser"/><br><br>
                </center>
            </form>
            </div>
<?php
                    }
                }
            }else{
?>
            <br><a href="admin.php" style="text-decoration:none;color:#22282c;">
                <svg width="2em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
                </svg>
            </a><h1 style="display:inline-block;">&nbsp;&nbsp;Groupes & Professeurs</h1>
<?php
                $select = $db->prepare("SELECT * FROM classes");
                $select->execute();
                $data = $select->get_result();
                $select->close();
                if(!$data) exit('No rows');
                while($row = $data->fetch_assoc()){
?>
            <h2><?php echo $row['name']; ?></h2>
<?php
                    $select2 = $db->prepare("SELECT * FROM class_grps WHERE class_id=?");
                    $select2->bind_param("i", $row['id']);
                    $select2->execute();
                    $data2 = $select2->get_result();
                    $select2->close();
                    if(!$data2) exit('No rows');
                    while($row2 = $data2->fetch_assoc()){
?>
            <a href="?page=teachers&class=<?php echo $row2['id']; ?>" class="ahref-button"><?php echo $row2['name']; ?></a></h5>
<?php
                    }
                }
            }
?>
        </div>
        <footer class="footer-side">
            @proLearn&trade; 2020
        </footer>
<?php
        }
    }else{
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $SCHOOLNAME; ?> - Admin</title>
    <link rel="stylesheet" href="styles/style-admin.css"/>
    <link rel="icon" type="image/png" href="../ressources/favicon_192x192.png" sizes="192x192"/>
</head>
<body onload="<?php
        if(!isset($_GET['page'])){
            echo "openPage('main-page')";
        }
?>">
    <noscript>
        Java script non activé. Veuillez le réactiver.
    </noscript>
    <div class="parent">
        <header class="header-side">
            <?php echo $SCHOOLNAME; ?>
        </header>
        <div class="left-side">
            <a class="side-item" id="item0" onclick="openPage('main-page')">
                <svg width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M8 3.293l6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6zm5-.793V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z"/>
                    <path fill-rule="evenodd" d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z"/>
                </svg>&nbsp;
                Acceuil
            </a>
            <a class="side-item adduser" id="item1" onclick="toggleSub('side-addUser', 'item1')">
                <svg width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm7.5-3a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
                </svg>&nbsp;
                Ajouter un utilisateur
            </a>
            <div id="side-addUser" class="side-hidden">
                <a class="sub-side-item" id="side-addStudent" onclick="openPage('addStudent')">
                    Ajouter un &eacute;l&egrave;ve
                </a>
                <a class="sub-side-item" id="side-addTeacher" onclick="openPage('addTeacher')">
                    Ajouter un professeur
                </a>
                <a class="sub-side-item" id="side-addParents" onclick="openPage('addParents')">
                    Ajouter des parents
                </a>
<?php
        if($_SESSION['staff_rank'] >= 8){
?>
                <a class="sub-side-item" id="side-addStaff" onclick="openPage('addStaff')">
                    Ajouter un staff
                </a>
<?php
        }
?>
            </div>
            <a class="side-item adduser" id="item2" onclick="toggleSub('side-configSchool', 'item2')">
                <svg width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 0 0-5.86 2.929 2.929 0 0 0 0 5.858z"/>
                </svg>&nbsp;
                Configuration de l'&eacute;cole
            </a>
            <div id="side-configSchool" class="side-hidden">
                <a class="sub-side-item" id="side-editClass" onclick="openPage('editClass')">
                    Modifier les niveaux
                </a>
                <a class="sub-side-item" id="side-editClassGrp" onclick="openPage('editClassGrp')">
                    Modifier les groupes
                </a>
                <a class="sub-side-item" id="side-editSubject" onclick="openPage('editSubject')">
                    Modifier les mati&egrave;res
                </a>
            </div>
            <a class="side-item userslist" id="item3" onclick="toggleSub('side-userslist', 'item3')">
                <svg width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm7 1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5zm2 9a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5z"/>
                </svg>&nbsp;
                Liste des utilisateurs
            </a>
            <div id="side-userslist" class="side-hidden">
                <a class="sub-side-item" id="side-studentsList" href="?action=students">
                    Liste des &eacute;l&egrave;ves
                </a>
                <a class="sub-side-item" id="side-teachersList" href="?action=teachers">
                    Liste des professeurs
                </a>
                <a class="sub-side-item" id="side-parentsList" href="?action=parents">
                    Liste des parents
                </a>
                <a class="sub-side-item" id="side-deletedList" href="?action=deleted">
                    Utilisateurs supprim&eacute;s
                </a>
            </div>
            <a class="side-item" id="item4" onclick="openPage('default-passwords')">
                <svg width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2.5 9a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-7a2 2 0 0 1-2-2V9z"/>
                    <path fill-rule="evenodd" d="M4.5 4a3.5 3.5 0 1 1 7 0v3h-1V4a2.5 2.5 0 0 0-5 0v3h-1V4z"/>
                </svg>&nbsp;
                Mots de passe (d&eacute;faut)
            </a>
            <a class="side-item configurations" id="item5" onclick="toggleSub('side-configurations', 'item5')">
                <svg width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M11.5 2a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM9.05 3a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0V3h9.05zM4.5 7a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM2.05 8a2.5 2.5 0 0 1 4.9 0H16v1H6.95a2.5 2.5 0 0 1-4.9 0H0V8h2.05zm9.45 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm-2.45 1a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0v-1h9.05z"/>
                </svg>&nbsp;
                Configurations
            </a>
            <div id="side-configurations" class="side-hidden">
                <a class="sub-side-item" href="?page=subjects">
                    Groupes & Mati&egrave;res
                </a>
                <a class="sub-side-item" href="?page=teachers">
                    Groupes & Professeurs
                </a>
            </div>
            <a class="side-item" id="item6" onclick="openPage('graphics')">
                <svg width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M0 0h1v15h15v1H0V0zm10 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V4.9l-3.613 4.417a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61L13.445 4H10.5a.5.5 0 0 1-.5-.5z"/>
                </svg>&nbsp;
                Graphiques
            </a>
            <a href="?action=logout" class="side-item">
                <svg width="1em" height="1.2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                    <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                </svg>&nbsp;
                Se d&eacute;connecter
            </a>
        </div>
        <div class="main-side" id="main">
            <iframe name="process" class="side-hidden"></iframe>
            <div id="main-page" class="side-hidden">
                <h2>Tableau de bord</h2>
                <div class="sections">
                    <div class="section">
                        <header class="section-header">
                            Utilisateurs
                        </header>
                        <div class="section-body">
                            <b>&Eacute;l&egrave;ves:</b>&nbsp;&nbsp;&nbsp;
<?php
        $select = $db->prepare("SELECT COUNT(id) FROM students");
        $select->execute();
        $data = $select->get_result();
        $select->close();
        if(!$data) exit('No rows');
        $row = $data->fetch_row();
        echo $row[0];
        $users += $row[0];
?>
                            <br>
                            <b>Professeurs:</b>&nbsp;&nbsp;&nbsp;
<?php
        $select = $db->prepare("SELECT COUNT(id) FROM teachers");
        $select->execute();
        $data = $select->get_result();
        $select->close();
        if(!$data) exit('No rows');
        $row = $data->fetch_row();
        echo $row[0];
        $users += $row[0];
?>
                            <br>
                            <b>Parents:</b>&nbsp;&nbsp;&nbsp;
<?php
        $select = $db->prepare("SELECT COUNT(id) FROM parents");
        $select->execute();
        $data = $select->get_result();
        $select->close();
        if(!$data) exit('No rows');
        $row = $data->fetch_row();
        echo $row[0];
        $users += $row[0];
?>
                        </div>
                        <footer class="section-footer">
                            <a href="admin.php?action=students">
                                Voir la liste des &eacute;l&egrave;ves
                                <svg width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z"/>
                                    <path fill-rule="evenodd" d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z"/>
                                </svg>
                            </a>
                        </footer>
                    </div>
                    <div class="section">
                        <header class="section-header">
                            Statistiques
                        </header>
                        <div class="section-body">
                            <b>Visites uniques:</b>&nbsp;&nbsp;&nbsp;
<?php
        $select = $db->prepare("SELECT value FROM analytics WHERE title='visits'");
        $select->execute();
        $data = $select->get_result();
        $select->close();
        if(!$data) exit('No rows');
        $row = $data->fetch_row();
        echo $row[0];
?>
                            <br><br>
                            <b>Utilisateurs:</b>&nbsp;&nbsp;&nbsp;<?php echo $users; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div id="addStudent" class="side-hidden">
                <h2>Ajouter un &eacute;l&egrave;ve</h2>
                <div class="section-add">
                    <header class="section-header">
                        Formulaire d'ajout (&Eacute;l&egrave;ve)
                    </header>
                    <div class="section-body">
                        <div id="addStudentmodal" class="modal-section">
                            <div class="modal-content">
                                <header class="modal-header">
                                    <span class="modal-close" onclick="document.getElementById('addStudentmodal').style.display='none';">&times;</span>
                                    <h2>&Eacute;l&egrave;ve ajout&eacute;!</h2>
                                </header>
                                <div class="modal-body">
                                    <p>
                                        <svg width="3em" height="3em" viewBox="0 0 16 16" fill="green" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M15.354 2.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L8 9.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                            <path fill-rule="evenodd" d="M8 2.5A5.5 5.5 0 1 0 13.5 8a.5.5 0 0 1 1 0 6.5 6.5 0 1 1-3.25-5.63.5.5 0 1 1-.5.865A5.472 5.472 0 0 0 8 2.5z"/>
                                        </svg><br>
                                        Base de donn&eacute;es mise à jour!
                                        <br>
                                        &Eacute;l&egrave;ve ajout&eacute;.
                                    </p>
                                </div>
                                <footer class="modal-footer">
                                    <button class="submit-footer" onclick="document.getElementById('addStudentmodal').style.display='none';">OK</button>
                                </footer>
                            </div>
                        </div>
                        <form method="POST" id="addStudentform" enctype="multipart/form-data" action="process.php" target="process" onsubmit="document.getElementById('addStudentmodal').style.display='block';">
                            <div class="formflex1">
                                <div class="formsection flexleft">
                                    <label>Pr&eacute;nom&nbsp;<span class="red">*</span></label><br>
                                    <input type="text" name="firstnameStudent" placeholder="Pr&eacute;nom de l'&eacute;l&egrave;ve" class="form-input" autocomplete="off" required/><br>
                                </div>
                                <div class="formsection flexright flexleft">
                                    <label>Nom&nbsp;<span class="red">*</span></label><br>
                                    <input type="text" name="lastnameStudent" placeholder="Nom de l'&eacute;l&egrave;ve" class="form-input" autocomplete="off" required/><br>
                                </div>
                            </div>
                            <div class="formflex1">
                                <div class="formsection flexleft">
                                    <label>Niveau/Groupe&nbsp;<span class="red">*</span></label><br>
                                    <select name="classStudent" class="form-input" required>
                                        <option value="">--Niveau/Groupe--</option>
<?php
        $select = $db->prepare("SELECT * FROM classes");
        $select->execute();
        $data = $select->get_result();
        $select->close();
        if(!$data) exit('No rows');
        while($row = $data->fetch_assoc()){
?>
                                        <optgroup label="<?php echo $row['name']; ?>">
<?php
            $select2 = $db->prepare("SELECT * FROM class_grps WHERE class_id=?");
            $select2->bind_param("i", $row['id']);
            $select2->execute();
            $data2 = $select2->get_result();
            $select2->close();
            if(!$data2) exit('No rows');
            while($row2 = $data2->fetch_assoc()){
?>
                                            <option value="<?php echo $row2['id']; ?>"><?php echo $row2['name']; ?></option>
<?php
            }
?>
                                        </optgroup>
<?php
        }
?>
                                    </select>
                                </div>
                                <div class="formsection flexright flexleft">
                                    <label>Date de naissance&nbsp;<span class="red">*</span></label><br>
                                    <input type="date" name="birthdateStudent" class="form-input" required/>
                                </div>
                            </div>
                            <div class="formflex1">
                                <div class="formsection flexleft">
                                    <label>Sexe&nbsp;<span class="red">*</span></label><br>
                                    <input type="radio" name="genderStudent" value="male" required/>Masculin<br>
                                    <input type="radio" name="genderStudent" value= "female" required/>F&eacute;minin
                                </div>
                                <div class="formsection flexright flexleft">
                                    <label>Photo de profil&nbsp;<span class="red">*</span></label><br><br>
                                    <label for="imgStudent"><span class="labelimg">Choisir une image</span></label>
                                    <input type="file" name="imgStudent" id="imgStudent" style="display:none;" required/>
                                </div>
                            </div>
                        </form>
                    </div>
                    <footer>
                        <button type="submit" name="addStudent" form="addStudentform" id="addStudentbutton" class="submit-footer" value="Ajouter">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                </svg>&nbsp;Ajouter
                            </button>
                    </footer>
                </div>
            </div>
            <div id="addTeacher" class="side-hidden">
                <h2>Ajouter un professeur</h2>
                <div class="section-add">
                    <header class="section-header">
                        Formulaire d'ajout (Professeur)
                    </header>
                    <div class="section-body">
                        <div id="addTeachermodal" class="modal-section">
                            <div class="modal-content">
                                <header class="modal-header">
                                    <span class="modal-close" onclick="document.getElementById('addTeachermodal').style.display='none';">&times;</span>
                                    <h2>Professeur ajout&eacute;!</h2>
                                </header>
                                <div class="modal-body">
                                    <p>
                                        <svg width="3em" height="3em" viewBox="0 0 16 16" fill="green" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M15.354 2.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L8 9.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                            <path fill-rule="evenodd" d="M8 2.5A5.5 5.5 0 1 0 13.5 8a.5.5 0 0 1 1 0 6.5 6.5 0 1 1-3.25-5.63.5.5 0 1 1-.5.865A5.472 5.472 0 0 0 8 2.5z"/>
                                        </svg><br>
                                        Base de donn&eacute;es mise à jour!
                                        <br>
                                        Professeur ajout&eacute;.
                                    </p>
                                </div>
                                <footer class="modal-footer">
                                    <button class="submit-footer" onclick="document.getElementById('addTeachermodal').style.display='none';">OK</button>
                                </footer>
                            </div>
                        </div>
                        <form method="POST" id="addTeacherform" action="process.php" target="process" onsubmit="document.getElementById('addTeachermodal').style.display='block';">
                            <div class="formflex1">
                                <div class="formsection flexleft">
                                    <label>Pr&eacute;nom&nbsp;<span class="red">*</span></label><br>
                                    <input type="text" name="firstnameTeacher" placeholder="Pr&eacute;nom du professeur" class="form-input" autocomplete="off" required/><br>
                                </div>
                                <div class="formsection flexright flexleft">
                                    <label>Nom&nbsp;<span class="red">*</span></label><br>
                                    <input type="text" name="lastnameTeacher" placeholder="Nom du professeur" class="form-input" autocomplete="off" required/><br>
                                </div>
                            </div>
                            <div class="formflex1">
                                <div class="formsection flexleft">
                                    <label>Date de naissance&nbsp;<span class="red">*</span></label><br>
                                    <input type="date" name="birthdateTeacher" class="form-input" required/>
                                </div>
                                <div class="formsection flexright flexleft">
                                    <label>Mati&egrave;re&nbsp;<span class="red">*</span></label><br>
                                    <select name="subjectTeacher" class="form-input" required>
                                        <option value="">--Mati&egrave;re--</option>
<?php
        $select = $db->prepare("SELECT * FROM subjects");
        $select->execute();
        $data = $select->get_result();
        $select->close();
        if(!$data) exit('No rows');
        while($row = $data->fetch_assoc()){
?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
<?php
        }
?>
                                    </select><br>
                                </div>
                            </div>
                            <div class="formflex1">
                                <div class="formsection flexleft">
                                    <label>Sexe&nbsp;<span class="red">*</span></label><br>
                                    <input type="radio" name="genderTeacher" value="male" required/>Masculin<br>
                                    <input type="radio" name="genderTeacher" value= "female" required/>F&eacute;minin
                                </div>
                            </div>
                        </form>
                    </div>
                    <footer>
                        <button type="submit" name="addTeacher" form="addTeacherform" id="addTeacherbutton" class="submit-footer" value="Ajouter">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                </svg>&nbsp;Ajouter
                            </button>
                    </footer>
                </div>
            </div>
            <div id="addParents" class="side-hidden">
                <h2>Ajouter des parents</h2>
                <div class="section-add">
                    <header class="section-header">
                        Formulaire d'ajout (Parents)
                    </header>
                    <div class="section-body">
                        <div id="addParentsmodal" class="modal-section">
                            <div class="modal-content">
                                <header class="modal-header">
                                    <span class="modal-close" onclick="document.getElementById('addParentsmodal').style.display='none';">&times;</span>
                                    <h2>Parents ajout&eacute;s!</h2>
                                </header>
                                <div class="modal-body">
                                    <p>
                                        <svg width="3em" height="3em" viewBox="0 0 16 16" fill="green" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M15.354 2.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L8 9.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                            <path fill-rule="evenodd" d="M8 2.5A5.5 5.5 0 1 0 13.5 8a.5.5 0 0 1 1 0 6.5 6.5 0 1 1-3.25-5.63.5.5 0 1 1-.5.865A5.472 5.472 0 0 0 8 2.5z"/>
                                        </svg><br>
                                        Base de donn&eacute;es mise à jour!
                                        <br>
                                        Parents ajout&eacute;s.
                                    </p>
                                </div>
                                <footer class="modal-footer">
                                    <button class="submit-footer" onclick="document.getElementById('addParentsmodal').style.display='none';">OK</button>
                                </footer>
                            </div>
                        </div>
                        <form method="POST" id="addParentsform" action="process.php" target="process" onsubmit="document.getElementById('addParentsmodal').style.display='block';">
                            <div class="formflex1">
                                <div class="formsection flexleft">
                                    <label>Pr&eacute;nom&nbsp;<span class="red">*</span></label><br>
                                    <input type="text" name="firstnameParents" placeholder="Pr&eacute;nom du tuteur" class="form-input" autocomplete="off" required/><br>
                                </div>
                                <div class="formsection flexright flexleft">
                                    <label>Nom&nbsp;<span class="red">*</span></label><br>
                                    <input type="text" name="lastnameParents" placeholder="Nom du tuteur" class="form-input" autocomplete="off" required/><br>
                                </div>
                            </div>
                            <div class="formflex1">
                                <div class="formsection flexleft">
                                    <label>Tuteur de&nbsp;<span class="red">*</span></label><br>
                                    <select name="studentParents" class="form-input" required>
                                        <option value="">--&Eacute;l&egrave;ve--</option>
<?php
        $select = $db->prepare("SELECT * FROM students");
        $select->execute();
        $data = $select->get_result();
        $select->close();
        if(!$data) exit('No rows');
        while($row = $data->fetch_assoc()){
?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['firstname'] .' '. $row['lastname']; ?></option>
<?php
        }
?>
                                    </select><br>
                                </div>
                            </div>
                        </form>
                    </div>
                    <footer>
                        <button type="submit" name="addParents" form="addParentsform" id="addParentsbutton" class="submit-footer" value="Ajouter">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                </svg>&nbsp;Ajouter
                            </button>
                    </footer>
                </div>
            </div>
<?php
        if($_SESSION['staff_rank'] >= 8){
?>
            <div id="addStaff" class="side-hidden">
                <h2>Ajouter un staff</h2>
                <div class="section-add">
                    <header class="section-header">
                        Formulaire d'ajout (Staff)
                    </header>
                    <div class="section-body">
                        <div id="addStaffmodal" class="modal-section">
                            <div class="modal-content">
                                <header class="modal-header">
                                    <span class="modal-close" onclick="document.getElementById('addStaffmodal').style.display='none';">&times;</span>
                                    <h2>Staff ajout&eacute;!</h2>
                                </header>
                                <div class="modal-body">
                                    <p>
                                        <svg width="3em" height="3em" viewBox="0 0 16 16" fill="green" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M15.354 2.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L8 9.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                            <path fill-rule="evenodd" d="M8 2.5A5.5 5.5 0 1 0 13.5 8a.5.5 0 0 1 1 0 6.5 6.5 0 1 1-3.25-5.63.5.5 0 1 1-.5.865A5.472 5.472 0 0 0 8 2.5z"/>
                                        </svg><br>
                                        Base de donn&eacute;es mise à jour!
                                        <br>
                                        Staff ajout&eacute;.
                                    </p>
                                </div>
                                <footer class="modal-footer">
                                    <button class="submit-footer" onclick="document.getElementById('addStaffmodal').style.display='none';">OK</button>
                                </footer>
                            </div>
                        </div>
                        <form method="POST" id="addStaffform" action="process.php" target="process" onsubmit="document.getElementById('addStaffmodal').style.display='block';">
                            <div class="formflex1">
                                <div class="formsection flexleft">
                                    <label>Identifiant&nbsp;<span class="red">*</span></label><br>
                                    <input type="text" name="usernameStaff" placeholder="Identifiant Staff" class="form-input" autocomplete="off" required/><br>
                                </div>
                                <div class="formsection flexright flexleft">
                                    <label>Mot de passe&nbsp;<span class="red">*</span></label><br>
                                    <input type="text" name="passwordStaff" placeholder="Mot de passe Staff" class="form-input" autocomplete="off" required/><br>
                                </div>
                            </div>
                            <div class="formflex1">
                                <div class="formsection flexleft">
                                    <label>Rank&nbsp;<span class="red">*</span></label><br>
                                    <input type="number" name="rankStaff" placeholder="Rank Staff" class="form-input" autocomplete="off" required/><br>
                                    <input type="checkbox" required/>&nbsp;<small>Je suis sûr de vouloir cr&eacute;er ce compte Staff.</small>
                                </div>
                            </div>
                        </form>
                    </div>
                    <footer>
                        <button type="submit" name="addStaff" form="addStaffform" id="addStaffbutton" class="submit-footer" value="Ajouter">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                </svg>&nbsp;Ajouter
                            </button>
                    </footer>
                </div>
            </div>
<?php
        }
?>
            <div id="editClass" class="side-hidden">
                <h2>Modifier les niveaux</h2>
                <div class="sections">
                    <div class="section">
                        <header class="section-header">
                            Ajouter un niveau
                        </header>
                        <div class="section-body">
                            <div id="addClassmodal" class="modal-section">
                                <div class="modal-content">
                                    <header class="modal-header">
                                        <span class="modal-close" onclick="document.getElementById('addClassmodal').style.display='none';">&times;</span>
                                        <h2>Niveau ajout&eacute;!</h2>
                                    </header>
                                    <div class="modal-body">
                                        <p>
                                            <svg width="3em" height="3em" viewBox="0 0 16 16" fill="green" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M15.354 2.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L8 9.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                                <path fill-rule="evenodd" d="M8 2.5A5.5 5.5 0 1 0 13.5 8a.5.5 0 0 1 1 0 6.5 6.5 0 1 1-3.25-5.63.5.5 0 1 1-.5.865A5.472 5.472 0 0 0 8 2.5z"/>
                                            </svg><br>
                                            Base de donn&eacute;es mise à jour!
                                            <br>
                                            Niveau ajout&eacute;.
                                        </p>
                                    </div>
                                    <footer class="modal-footer">
                                        <button class="submit-footer" onclick="document.getElementById('addClassmodal').style.display='none';">OK</button>
                                    </footer>
                                </div>
                            </div>
                            <form method="POST" action="process.php" id="addClassform" target="process" onsubmit="document.getElementById('addClassmodal').style.display='block';">
                                <label>Nom du niveau &agrave; ajouter&nbsp;<span class="red">*</span></label><br>
                                <input type="text" name="nameClass" placeholder="Nom du niveau" class="form-input" autocomplete="off" required/><br>
                                <input type="checkbox" required/>&nbsp;<small>Je suis sûr de vouloir cr&eacute;er ce niveau.</small>
                            </form>
                        </div>
                        <footer>
                            <button type="submit" name="addClass" form="addClassform" class="submit-footer" value="Ajouter">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                </svg>&nbsp;Ajouter
                            </button>
                        </footer>
                    </div>
                    <div class="section">
                        <header class="section-header">
                            Niveaux disponibles
                        </header>
                        <div class="section-body">
<?php
        $select = $db->prepare("SELECT * FROM classes");
        $select->execute();
        $data = $select->get_result();
        $select->close();
        if(!$data) exit('No rows');
        while($row = $data->fetch_assoc()){
?>
                            <b><?php echo $row['name']; ?>:</b>&nbsp;&nbsp;&nbsp;
<?php
            $select2 = $db->prepare("SELECT COUNT(id) FROM class_grps WHERE class_id=?");
            $select2->bind_param("s", $row['id']);
            $select2->execute();
            $data2 = $select2->get_result();
            $select2->close();
            if(!$data2) exit('No rows');
            $row2 = $data2->fetch_row();
            echo $row2[0];
?>
                            groupes<br>
<?php
        }
?>
                        </div>
                    </div>
<?php
        if($_SESSION['staff_rank'] >= 8){
?>
                    <div class="section">
                        <header class="section-header">
                            Modifier un niveau
                        </header>
                        <div class="section-body">
                            <div id="editClassmodal" class="modal-section">
                                <div class="modal-content">
                                    <header class="modal-header">
                                        <span class="modal-close" onclick="document.getElementById('editClassmodal').style.display='none';">&times;</span>
                                        <h2>Niveau modifi&eacute;!</h2>
                                    </header>
                                    <div class="modal-body">
                                        <p>
                                            <svg width="3em" height="3em" viewBox="0 0 16 16" fill="green" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M15.354 2.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L8 9.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                                <path fill-rule="evenodd" d="M8 2.5A5.5 5.5 0 1 0 13.5 8a.5.5 0 0 1 1 0 6.5 6.5 0 1 1-3.25-5.63.5.5 0 1 1-.5.865A5.472 5.472 0 0 0 8 2.5z"/>
                                            </svg><br>
                                            Base de donn&eacute;es mise à jour!
                                            <br>
                                            Niveau modifi&eacute;.
                                        </p>
                                    </div>
                                    <footer class="modal-footer">
                                        <button class="submit-footer" onclick="document.getElementById('editClassmodal').style.display='none';">OK</button>
                                    </footer>
                                </div>
                            </div>
                            <form method="POST" action="process.php" id="editClassform" target="process" onsubmit="document.getElementById('editClassmodal').style.display='block';">
                                <div class="formflex1">
                                    <div class="formsection flexleft">
                                        <label>S&eacute;lectionnez un niveau &agrave; modifier&nbsp;<span class="red">*</span></label><br>
                                        <select name="editClassSelect" class="form-input" required>
                                            <option value="">--Niveau--</option>
<?php
            $select = $db->prepare("SELECT * FROM classes");
            $select->execute();
            $data = $select->get_result();
            $select->close();
            while($row = $data->fetch_assoc()){
?>
                                            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
<?php
            }
?>
                                        </select>
                                    </div>
                                    <div class="formsection flexleft flexright">
                                        <label>Modifier &agrave;&nbsp;<span class="red">*</span></label><br>
                                        <input type="text" name="editClassName" placeholder="Nom apr&egrave;s modification" class="form-input" autocomplete="off" required/><br>
                                        <input type="checkbox" required/>&nbsp;<small>Je suis sûr de vouloir modifier ce niveau.</small>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <footer>
                            <button type="submit" name="editClasssubmit" form="editClassform" class="submit-footer" value="Modifier">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                </svg>&nbsp;Modifier
                            </button>
                        </footer>
                    </div>
<?php
        }
?>
                </div>
            </div>
            <div id="editClassGrp" class="side-hidden">
                <h2>Modifier les groupes</h2>
                <div class="sections">
                    <div class="section">
                        <header class="section-header">
                            Ajouter un groupe
                        </header>
                        <div class="section-body">
                            <div id="addClassGrpmodal" class="modal-section">
                                <div class="modal-content">
                                    <header class="modal-header">
                                        <span class="modal-close" onclick="document.getElementById('addClassGrpmodal').style.display='none';">&times;</span>
                                        <h2>Groupe ajout&eacute;!</h2>
                                    </header>
                                    <div class="modal-body">
                                        <p>
                                            <svg width="3em" height="3em" viewBox="0 0 16 16" fill="green" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M15.354 2.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L8 9.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                                <path fill-rule="evenodd" d="M8 2.5A5.5 5.5 0 1 0 13.5 8a.5.5 0 0 1 1 0 6.5 6.5 0 1 1-3.25-5.63.5.5 0 1 1-.5.865A5.472 5.472 0 0 0 8 2.5z"/>
                                            </svg><br>
                                            Base de donn&eacute;es mise à jour!
                                            <br>
                                            Groupe ajout&eacute;.
                                        </p>
                                    </div>
                                    <footer class="modal-footer">
                                        <button class="submit-footer" onclick="document.getElementById('addClassGrpmodal').style.display='none';">OK</button>
                                    </footer>
                                </div>
                            </div>
                            <form method="POST" action="process.php" id="addClassGrpform" target="process" onsubmit="document.getElementById('addClassGrpmodal').style.display='block';">
                                <label>Nom du groupe &agrave; ajouter&nbsp;<span class="red">*</span></label><br>
                                <input type="text" name="nameClassGrp" placeholder="Nom du groupe" class="form-input" autocomplete="off" required/><br>
                                <label>Niveau du groupe&nbsp;<span class="red">*</span></label>
                                <select name="classClassGrp" class="form-input" required>
                                    <option value="">--Niveau--</option>
<?php
        $select = $db->prepare("SELECT * FROM classes");
        $select->execute();
        $data = $select->get_result();
        $select->close();
        if(!$data) exit('No rows');
        while($row = $data->fetch_assoc()){
?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
<?php
        }
?>
                                </select><br>
                                <input type="checkbox" required/>&nbsp;<small>Je suis sûr de vouloir cr&eacute;er ce groupe.</small>
                            </form>
                        </div>
                        <footer>
                            <button type="submit" name="addClassGrp" form="addClassGrpform" class="submit-footer" value="Ajouter">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                </svg>&nbsp;Ajouter
                            </button>
                        </footer>
                    </div>
                    <div class="section">
                        <header class="section-header">
                            Niveaux disponibles
                        </header>
                        <div class="section-body">
<?php
        $select = $db->prepare("SELECT * FROM classes");
        $select->execute();
        $data = $select->get_result();
        $select->close();
        if(!$data) exit('No rows');
        while($row = $data->fetch_assoc()){
?>
                            <b><?php echo $row['name']; ?>:</b>&nbsp;&nbsp;&nbsp;
<?php
            $select2 = $db->prepare("SELECT COUNT(id) FROM class_grps WHERE class_id=?");
            $select2->bind_param("s", $row['id']);
            $select2->execute();
            $data2 = $select2->get_result();
            $select2->close();
            if(!$data2) exit('No rows');
            $row2 = $data2->fetch_row();
            echo $row2[0];
?>
                            groupes<br>
<?php
        }
?>
                        </div>
                    </div>
                </div>
            </div>
            <div id="editSubject" class="side-hidden">
                <h2>Modifier les mati&egrave;res</h2>
                <div class="sections">
                    <div class="section">
                        <header class="section-header">
                            Ajouter une mati&egrave;re
                        </header>
                        <div class="section-body">
                            <div id="addSubjectmodal" class="modal-section">
                                <div class="modal-content">
                                    <header class="modal-header">
                                        <span class="modal-close" onclick="document.getElementById('addSubjectmodal').style.display='none';">&times;</span>
                                        <h2>Mati&egrave; ajout&eacute;e!</h2>
                                    </header>
                                    <div class="modal-body">
                                        <p>
                                            <svg width="3em" height="3em" viewBox="0 0 16 16" fill="green" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M15.354 2.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L8 9.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                                <path fill-rule="evenodd" d="M8 2.5A5.5 5.5 0 1 0 13.5 8a.5.5 0 0 1 1 0 6.5 6.5 0 1 1-3.25-5.63.5.5 0 1 1-.5.865A5.472 5.472 0 0 0 8 2.5z"/>
                                            </svg><br>
                                            Base de donn&eacute;es mise à jour!
                                            <br>
                                            Mati&egrave;re ajout&eacute;e.
                                        </p>
                                    </div>
                                    <footer class="modal-footer">
                                        <button class="submit-footer" onclick="document.getElementById('addSubjectmodal').style.display='none';">OK</button>
                                    </footer>
                                </div>
                            </div>
                            <form method="POST" action="process.php" id="addSubjectform" target="process" onsubmit="document.getElementById('addSubjectmodal').style.display='block';">
                                <label>Nom de la mati&egrave;re &agrave; ajouter&nbsp;<span class="red">*</span></label><br>
                                <input type="text" name="nameSubject" placeholder="Nom de la mati&egrave;re" class="form-input" autocomplete="off" required/><br>
                                <input type="checkbox" required/>&nbsp;<small>Je suis sûr de vouloir cr&eacute;er cette mati&egrave;re.</small>
                            </form>
                        </div>
                        <footer>
                            <button type="submit" name="addSubject" form="addSubjectform" class="submit-footer" value="Ajouter">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                </svg>&nbsp;Ajouter
                            </button>
                        </footer>
                    </div>
                    <div class="section">
                        <header class="section-header">
                            Mati&egrave;res disponibles
                        </header>
                        <div class="section-body">
<?php
        $select = $db->prepare("SELECT * FROM subjects");
        $select->execute();
        $data = $select->get_result();
        $select->close();
        if(!$data) exit('No rows');
        while($row = $data->fetch_assoc()){
?>
                            <b><?php echo $row['name']; ?></b><br>
<?php
        }
?>
                        </div>
                    </div>
                </div>
            </div>
            <div id="default-passwords" class="side-hidden">
                <h2>Mots de passe (par d&eacute;faut)</h2>
                <div class="sections">
                    <div class="section">
                        <header class="section-header">
                            &Agrave; la cr&eacute;ation
                        </header>
                        <div class="section-body">
                            <i>aaaa: année de naissance</i><br>
                            <br>
                            <b>&Eacute;l&egrave;ves</b><br>
                            nomprenom@aaaa<br>
                            <hr width="60%" align="left">
                            <b>Professeurs</b><br>
                            nomprenom.prof@prolearn2020<br>
                            <hr width="60%" align="left">
                            <b>Parents</b><br>
                            nomprenom.parents@prolearn2020<br>
                            <span class="red"><i><b>PS:&nbsp;</b>pr&eacute;nom de l'&eacute;l&egrave;ve</i></span>
                        </div>
                    </div>
                    <div class="section">
                        <header class="section-header">
                            &Agrave; la r&eacute;initialisation
                        </header>
                        <div class="section-body">
                            <i>aaaa: année de naissance</i><br>
                            <i>mm: mois de naissance</i><br>
                            <i>jj: jour de naissance</i><br>
                            <br>
                            <b>&Eacute;l&egrave;ves</b><br>
                            nomprenom@jj-mm-aaaa<br>
                            <hr width="60%" align="left">
                            <b>Professeurs</b><br>
                            nomprenom.prof@jj-mm-aaaa
                            <hr width="60%" align="left">
                            <b>Parents</b><br>
                            nom.parents@prolearn2020
                        </div>
                    </div>
                </div>
            </div>
            <div id="graphics" class="side-hidden">
                <h2>Graphiques</h2>
                <div class="sections">
                    <div class="section">
                        <header class="section-header">
                            Genres
                        </header>
                        <div class="section-body"></div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer-side">
            @proLearn&trade; 2020
        </footer>
    </div>
    <script src="main.js"></script>
</body>
</html>
<?php
    }
}else{
    header('Location: ../index.php');
}
?>