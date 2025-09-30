<?php 
    session_start(); 
    if (!isset($_SESSION['user'])) $_SESSION['user']="guest";
    elseif (isset($_POST['logout'])) {
        unset($_SESSION);
        $_SESSION['user']="guest";
    }
    if (isset($_POST['login']) or isset($_POST['reg'])) {
        $_POST['nick'] = strip_tags($_POST['nick']);
        $_POST['nick'] = htmlspecialchars($_POST['nick'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $_POST['nick'] = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['nick']);
        $_POST['passwd'] = strip_tags($_POST['passwd']);
        $_POST['passwd'] = htmlspecialchars($_POST['passwd'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly by Hawk</title>
    <link rel="stylesheet" href="inc/weekly.css" />
    <script src="inc/weekly.js"></script> 
</head>
<body onload="fadeBackground()">
    <div id="bg" aria-hidden="true"></div>

    <div id="content">
        <!-- Your page content goes here -->
        <img src="inc/weekly_logo.png" class="logo" alt="">
        <div id="ops">
<?php  
            echo extension_loaded('sqlite3') ? 'SQLite enabled' : 'SQLite not enabled';
            echo ("<br />\n");
            $db = new SQLite3('inc/weekly.db');
            if ($db) {
                echo "Connected successfully";
            } else {
                echo "Connection failed";
            } 
            echo ("<br />\n");
            $db->exec("CREATE TABLE IF NOT EXISTS User (idUser INTEGER PRIMARY KEY, Nick TEXT, Email TEXT, UserPW TEXT)");
            if (isset($_POST['login'])) {
                $sql = "SELECT * FROM User WHERE Nick = '" . $_POST['nick'] . "';";
                $ref = $db->query($sql);
                if ($row = $ref->fetchArray(SQLITE3_ASSOC)) {
                    var_dump($row);
                }
                else {
                    $_SESSION['user'] = "new";
                }
            }
            if (isset($_POST['reg'])) {
                if ($_SESSION['oldpw'] == $_POST['passwd']) {
                    $_SESSION['user'] = $_POST['nick'];
                    $passwd = crypt($_POST['passwd'], "Weekly");
                    $sql = "INSERT INTO User (Nick, Email, UserPW) VALUES ('" . $_POST['nick'] . "', '" . $_POST['email'] . "', '" . $passwd . "');";
                    echo ($sql);
                    echo ("<br />\n");
                    $ref = $db->exec($sql);
                    if(!$ref) {
                        echo $db->lastErrorMsg();
                    } else {
                      echo "Records created successfully\n";
                    }
                }
            }

?>
        </div>

        <div class="title">Weekly by Hawk</div><br clear="all" />
        <div style="text-align: right; margin-top: 2px;">
<?php
            echo ($_SESSION['user'] . " ");
            if ($_SESSION['user'] != "guest") {
?>
                <form action="index.php" method="post" style="display :inline;">
                    <input type="submit" value="Log out" name="logout" />
                </form>
<?php
            }            
?>
        </div>

        <div>
<?php
            if ($_SESSION['user'] == "guest") {
?>
                <form action="index.php" method="post">
                    <input type="text" name="nick" size="20" placeholder="Username" /><br />
                    <input type="password" name="passwd" size="20" placeholder="Password" /><br />
                    <input type="submit" value="Log in" name="login" />
                </form>
<?php
            }
            elseif ($_SESSION['user'] == "new") {
                $_SESSION['oldpw'] = $_POST['passwd'];
?>
                <form action="index.php" method="post">
                    It does not look like I have you registered, want to register?<br />
                    <input type="text" name="nick" size="20" value="<?php echo ($_POST['nick']); ?>" readonly /><br />
                    <input type="email" name="email" size="20" placeholder="Email address" required /><br />
                    <input type="password" name="passwd" size="20" placeholder="Confirm password" /><br />
                    <input type="submit" value="Register" name="reg" />
                </form>
<?php
            }
?>
        </div>
    </div>
    
</body>
</html>