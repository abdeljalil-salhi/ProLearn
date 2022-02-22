<?php
require_once('../includes/variables.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $SCHOOLNAME; ?> - Hash</title>
    <link rel="icon" type="image/png" href="../ressources/favicon_192x192.png" sizes="192x192"/>
    <style>
    /* IMPORTS */
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300&display=swap');
    *{
        font-family: "Montserrat", Arial, Verdana, Geneva, Helvetica, sans-serif;
    }
    </style>
</head>
<body>
    <center>
        <h1>ADMIN - Hash</h1>
        <form method="POST">
            <label for="password"><h4>Password to hash</h4></label><br>
            <input type="text" name="password"/><br>
            <input type="submit" name="submit" value="Hash"/>
        </form><br><br>
        <input id="pswdhash" value="
<?php
if(isset($_POST['submit'])){
    $password = $_POST['password'];
    $timetarget = 0.05; //50ms
    $cost = 8; //hash option
    do{
        $cost++;
        $start = microtime(true);
        password_hash($password, PASSWORD_BCRYPT, ['cost'=>$cost]);
        $end = microtime(true);
    }while(($end - $start) < $timetarget);
    $options = [
        'cost'=>$cost,
    ];
    $password = password_hash($password, PASSWORD_BCRYPT, $options);
    echo $password;
}
?>"/>
        <button onclick="copyhash()">Copy to clipboard</button>
        <script>
        function copyhash(){
            var hash = document.getElementById('pswdhash');
            hash.select();
            hash.setSelectionRange(0, 99999);
            document.execCommand('copy');
            alert('Copied to clipboard!');
        }
        </script>
    </center>
    <script>
    var c = 0; var a = '<?php $config = parse_ini_file('variables.ini'); echo $config['hash']; ?>';
    do{
        if(c > 0) alert("Acces denied.");
        var b = prompt("Secure Password:");
        c++;
    }
    while(a != b);
    alert("Acces granted.");
    </script>
</body>
</html>