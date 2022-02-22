<?php
require_once('../includes/variables.php');

if($_GET['action'] == 'y'){
    $select = $db->prepare("SELECT * FROM classes_subjects WHERE grp_id=?");
    $select->bind_param("s", $_GET['class']);
    $select->execute();
    $data = $select->get_result();
    $select->close();
    while($row = $data->fetch_assoc()){
        if($row['subjects'] == ""){
            $update = $db->prepare("UPDATE classes_subjects SET subjects=? WHERE grp_id=?");
            $update->bind_param("ss", $_GET['subject'], $_GET['class']);
            $update->execute();
            $update->close();
        }else{
            $select2 = $db->prepare("SELECT * FROM classes_subjects WHERE grp_id=?");
            $select2->bind_param("s", $_GET['class']);
            $select2->execute();
            $data2 = $select2->get_result();
            $select2->close();
            while($row2 = $data2->fetch_assoc()){
                $subjects = $row2['subjects'];
                $check = explode(" ", $subjects);
                $keyCheck = array_search($_GET['subject'], $check);
                if($keyCheck === false){
                    $subjects = $subjects .' '. $_GET['subject'];
                }
                $update = $db->prepare("UPDATE classes_subjects SET subjects=? WHERE grp_id=?");
                $update->bind_param("ss", $subjects, $_GET['class']);
                $update->execute();
                $update->close();
            }
        }
    }
}else if($_GET['action'] == 'n'){
    $select = $db->prepare("SELECT * FROM classes_subjects WHERE grp_id=?");
    $select->bind_param("s", $_GET['class']);
    $select->execute();
    $data = $select->get_result();
    $select->close();
    while($row = $data->fetch_assoc()){
        $subjects = $row['subjects'];
        $subjects = explode(" ", $subjects);
        $key = array_search($_GET['subject'], $subjects);
        if($key !== false){
            unset($subjects[$key]);
            $subjects = array_values($subjects);
        }
        $subjects = implode(" ", $subjects);
        $update = $db->prepare("UPDATE classes_subjects SET subjects=? WHERE grp_id=?");
        $update->bind_param("ss", $subjects, $_GET['class']);
        $update->execute();
        $update->close();
    }
}
?>