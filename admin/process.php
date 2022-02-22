<?php
require_once('../includes/variables.php');

if(isset($_POST['addClass'])){
    $nameClass = strtoupper($_POST['nameClass']);
    $select = $db->prepare("SELECT * FROM classes WHERE name=?");
    $select->bind_param("s", $nameClass);
    $select->execute();
    $data = $select->get_result();
    if($data->num_rows == 0){
        $insert = $db->prepare("INSERT INTO classes (id, name) VALUES ('', ?)");
        $insert->bind_param("s", $nameClass);
        $insert->execute();
        $insert->close();
    }
    $select->close();
}else if(isset($_POST['addClassGrp'])){
    $nameClassGrp = $_POST['nameClassGrp'];
    $classClassGrp = $_POST['classClassGrp'];
    $select = $db->prepare("SELECT * FROM class_grps WHERE name=?");
    $select->bind_param("s", $nameClassGrp);
    $select->execute();
    $data = $select->get_result();
    if($data->num_rows == 0){
        $insert = $db->prepare("INSERT INTO class_grps (id, class_id, name) VALUES ('', ?, ?)");
        $insert->bind_param("ss", $classClassGrp, $nameClassGrp);
        $insert->execute();
        $insert->close();
        $insert = $db->prepare("INSERT INTO classes_subjects (grp_id) VALUES (?)");
        $insert->bind_param("s", $nameClassGrp);
        $insert->execute();
        $insert->close();
    }
    $select->close();
}else if(isset($_POST['addStudent'])){
    $firstnameStudent = ucfirst($_POST['firstnameStudent']);
    $lastnameStudent = ucfirst($_POST['lastnameStudent']);
    $genderStudent = $_POST['genderStudent'];
    $classGrpStudent = $_POST['classStudent'];
    $timestampStudent = strtotime($_POST['birthdateStudent']);
    $dayStudent = date("d", $timestampStudent);
    $monthStudent = date("m", $timestampStudent);
    $yearStudent = date("Y", $timestampStudent);
    $birthdateStudent = $dayStudent .'-'. $monthStudent .'-'. $yearStudent;
    $usernameStudent = strtolower($lastnameStudent . $firstnameStudent);
    $passwordStudent = $usernameStudent .'@'. $yearStudent;
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
    $select = $db->prepare("SELECT * FROM class_grps WHERE id=?");
    $select->bind_param("i", $classGrpStudent);
    $select->execute();
    $data = $select->get_result();
    $select->close();
    if(!$data) exit('No rows');
    while($row = $data->fetch_assoc()){
        $class_ids[] = $row['class_id'];
    }
    $classStudent = $class_ids[0];
    $select = $db->prepare("SELECT * FROM students WHERE username=?");
    $select->bind_param("s", $usernameStudent);
    $select->execute();
    $data = $select->get_result();
    if($data->num_rows == 0){
        $insert = $db->prepare("INSERT INTO students (id, username, firstname, lastname, gender, class, class_grp, birthdate, password) VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?)");
        $insert->bind_param("ssssiiss", $usernameStudent, $firstnameStudent, $lastnameStudent, $genderStudent, $classStudent, $classGrpStudent, $birthdateStudent, $passwordStudent);
        $insert->execute();
        $insert->close();
    }
    $select->close();
    $select = $db->prepare("SELECT * FROM students WHERE username=?");
    $select->bind_param("s", $usernameStudent);
    $select->execute();
    $data = $select->get_result();
    $select->close();
    if(!$data) exit('No rows');
    while($row = $data->fetch_assoc()){
        $idsStudent[] = $row['id'];
    }
    $idStudent = $idsStudent[0];
    $imgStudent = $_FILES['imgStudent']['name'];
    $imgStudent_tmp = $_FILES['imgStudent']['tmp_name'];
    if(!empty($imgStudent_tmp)){
        $imgStudent_name = explode('.', $imgStudent);
        $imgStudent_ext = end($imgStudent_name);
        if(in_array(strtolower($imgStudent_ext), array('png', 'jpg', 'jpeg')) === false){
            echo 'error';
        }else{
            $imgStudent_size = getimagesize($imgStudent_tmp);
            if($imgStudent_size['mime'] == 'image/jpeg'){
                $imgStudent_src = imagecreatefromjpeg($imgStudent_tmp);
            }else if($imgStudent_size['mime'] == 'image/png'){
                $imgStudent_src = imagecreatefrompng($imgStudent_tmp);
            }else{
                $imgStudent_src = false;
            }
            if($imgStudent_src !== false){
                $imgStudent_width = 300;
                if($imgStudent_size[0] == $imgStudent_width){
                    $imgStudent_final = $imgStudent_src;
                }else{
                    $new_width[0] = $imgStudent_width;
                    $new_height[1] = 300;
                    $imgStudent_final = imagecreatetruecolor($new_width[0], $new_height[1]);
                    imagecopyresampled($imgStudent_final, $imgStudent_src, 0, 0, 0, 0, $new_width[0], $new_height[1], $imgStudent_size[0], $imgStudent_size[1]);
                }
                imagejpeg($imgStudent_final, 'imgs/'. $idStudent .'.jpg');
            }
        }
    }
}else if(isset($_POST['addTeacher'])){
    $firstnameTeacher = ucfirst($_POST['firstnameTeacher']);
    $lastnameTeacher = ucfirst($_POST['lastnameTeacher']);
    $genderTeacher = $_POST['genderTeacher'];
    $timestampTeacher = strtotime($_POST['birthdateTeacher']);
    $dayTeacher = date("d", $timestampTeacher);
    $monthTeacher = date("m", $timestampTeacher);
    $yearTeacher = date("Y", $timestampTeacher);
    $birthdateTeacher = $dayTeacher .'-'. $monthTeacher .'-'. $yearTeacher;
    $subjectTeacher = $_POST['subjectTeacher'];
    $usernameTeacher = strtolower($lastnameTeacher . $firstnameTeacher);
    $passwordTeacher = $usernameTeacher .'.prof@prolearn2020';
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
    $select = $db->prepare("SELECT * FROM teachers WHERE username=?");
    $select->bind_param("s", $usernameTeacher);
    $select->execute();
    $data = $select->get_result();
    if($data->num_rows == 0){
        $insert = $db->prepare("INSERT INTO teachers (id, username, firstname, lastname, gender, birthdate, subject, password) VALUES ('', ?, ?, ?, ?, ?, ?, ?)");
        $insert->bind_param("sssssis", $usernameTeacher, $firstnameTeacher, $lastnameTeacher, $genderTeacher, $birthdateTeacher, $subjectTeacher, $passwordTeacher);
        $insert->execute();
        $insert->close();
    }
    $select->close();
}else if(isset($_POST['addSubject'])){
    $nameSubject = ucfirst($_POST['nameSubject']);
    $select = $db->prepare("SELECT * FROM subjects WHERE name=?");
    $select->bind_param("s", $nameSubject);
    $select->execute();
    $data = $select->get_result();
    if($data->num_rows == 0){
        $insert = $db->prepare("INSERT INTO subjects (id, name) VALUES ('', ?)");
        $insert->bind_param("s", $nameSubject);
        $insert->execute();
        $insert->close();
    }
    $select->close();
}else if(isset($_POST['addParents'])){
    $firstnameParents = ucfirst($_POST['firstnameParents']);
    $lastnameParents = ucfirst($_POST['lastnameParents']);
    $usernameParents = strtolower($lastnameParents);
    $student_idParents = $_POST['studentParents'];
    $select = $db->prepare("SELECT * FROM students WHERE id=?");
    $select->bind_param("i", $student_idParents);
    $select->execute();
    $data = $select->get_result();
    $select->close();
    if(!$data) exit('No rows');
    while($row = $data->fetch_assoc()){
        $usernamesStudents[] = $row['username'];
    }
    $student_usernameParents = $usernamesStudents[0];
    $passwordParents = $student_usernameParents .'.parents@prolearn2020';
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
    $select = $db->prepare("SELECT * FROM parents WHERE student_id=?");
    $select->bind_param("s", $student_idParents);
    $select->execute();
    $data = $select->get_result();
    if($data->num_rows == 0){
        $insert = $db->prepare("INSERT INTO parents (id, username, firstname, lastname, student_id, password) VALUES ('', ?, ?, ?, ?, ?)");
        $insert->bind_param("sssis", $usernameParents, $firstnameParents, $lastnameParents, $student_idParents, $passwordParents);
        $insert->execute();
        $insert->close();
    }
    $select->close();
}else if(isset($_POST['addStaff'])){
    $usernameStaff = strtolower($_POST['usernameStaff']);
    $rankStaff = $_POST['rankStaff'];
    $passwordStaff = $_POST['passwordStaff'];
    $timeTarget = 0.05; //50ms
    $cost = 8; //hash option
    do{
        $cost++;
        $start = microtime(true);
        password_hash($passwordStaff, PASSWORD_BCRYPT, ["cost"=>$cost]);
        $end = microtime(true);
    }while(($end - $start) < $timeTarget);
    $options = [
        "cost"=>$cost,
    ];
    $passwordStaff = password_hash($passwordStaff, PASSWORD_BCRYPT, $options);
    $select = $db->prepare("SELECT * FROM staff WHERE username=?");
    $select->bind_param("s", $usernameStaff);
    $select->execute();
    $data = $select->get_result();
    if($data->num_rows == 0){
        $insert = $db->prepare("INSERT INTO staff (id, username, password, rank) VALUES ('', ?, ?, ?)");
        $insert->bind_param("ssi", $usernameStaff, $passwordStaff, $rankStaff);
        $insert->execute();
        $insert->close();
    }
    $select->close();
}else if(isset($_POST['editClasssubmit'])){
    $editClass_id = $_POST['editClassSelect'];
    $editClass_newname = $_POST['editClassName'];
    $update = $db->prepare("UPDATE classes SET name=? WHERE id=?");
    $update->bind_param("si", $editClass_newname, $editClass_id);
    $update->execute();
    $update->close();
}
?>