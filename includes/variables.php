<?php
require_once('db.php');

$select = $db->prepare("SELECT * FROM variables WHERE title='schoolname'");
$select->execute();
if(!$select){
    echo "Prepare failed: (". $db->errno .") ". $db->error;
}
$result = $select->get_result();
while($row = $result->fetch_assoc()){
    $values[] = $row['value'];
}
if(!$result) exit('No rows');
$select->close();
$SCHOOLNAME = $values[0]; 
?>