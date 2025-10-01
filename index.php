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
    <link rel="icon" href="inc/favicon.ico" type="image/x-icon"/>
    <link rel="stylesheet" href="inc/weekly.css" />
    <script src="inc/weekly.js"></script> 
</head>
<body onload="fadeBackground()">
    <div class="bg" id="bg" aria-hidden="true"></div>

    <div class="content" id="content">
        <!-- Your page content goes here -->
        <img src="inc/weekly_logo.png" class="logo" alt="">
        <div id="ops" class="ops">
<?php  

// *************************************************************************************************** ADMIN  *********************

            // echo extension_loaded('sqlite3') ? 'SQLite enabled' : 'SQLite not enabled';
            // echo ("<br />\n");
            $db = new SQLite3('inc/weekly.db');
            if ($db) {
                echo "DB connected successfully";
            } else {
                echo "Connection failed, contact admin";
            } 
            echo ("<br />\n");
            $db->exec("CREATE TABLE IF NOT EXISTS User (idUser INTEGER PRIMARY KEY, Nick TEXT, Email TEXT, UserPW TEXT)");
            if (isset($_POST['login'])) {
                $sql = "SELECT * FROM User WHERE Nick = '" . $_POST['nick'] . "';";
                $ref = $db->query($sql);
                if ($row = $ref->fetchArray(SQLITE3_ASSOC)) {
                    // var_dump($row);
                    $_SESSION['user'] = $row['Nick'];
                    $_SESSION['uid'] = $row['idUser'];
                    echo ("<br />" . $row['Nick'] . " logged in");
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
                      $_SESSION['new'] = true;
                    }
                }
            }

            if (isset($_POST['newnew'])) {
                var_dump($_POST);
                var_dump($_SESSION);
            }

?>
        </div>
<!--  *************************************************************************************  HEADER   ********************************* -->        
        <div class="title">
            Weekly by Hawk
            <br />
<?php
        $month = "<span style='font-size: 125%; font-weight: bolder;'>" . date('F') . "</span> ";
        if (date('d') > 15) {
            $nextMonth = "<span style='font-size: 65%'>" . date("F", strtotime("+1 month")) . "</span> ";
            echo ($month . $nextMonth . "<br />\n");
        } else {
            $prevMonth = "<span style='font-size: 65%'>" . date("F", strtotime("-1 month")) . "</span> ";
            echo ($prevMonth . $month . "<br />\n");
        }
        echo (date('l jS'));
?>
        </div>
        <br clear="all" />
<!--  *************************************************************************************  TOP BAR  ********************************* -->        
        <div style="text-align: right; margin-top: 2px;">
<?php
            if ($_SESSION['user'] != "guest") {
?>
                <form action="index.php" method="post" style="display :inline;">
                    <input type="submit" value="➕" name="new" class="new" />
                </form>
                <?php echo ("<span style='font-size: 1.2em'>" . $_SESSION['user'] . "</span> "); ?>
                <form action="index.php" method="post" style="display :inline;">
                    <input type="submit" value="⚙️" name="profile" class="profile" />
                </form>
                <form action="index.php" method="post" style="display :inline;">
                    <input type="submit" value="Log out" name="logout" style="margin: auto 2px;" />
                </form>
<?php
                // <a href="https://www.flaticon.com/free-icons/additional" title="additional icons">Additional icons created by meaicon - Flaticon</a>
            }            
            else  echo ($_SESSION['user'] . " ");

?>
        </div>
<!--  *************************************************************************************  LOGIN  ********************************* -->        

<?php
            if ($_SESSION['user'] == "guest") {
?>
                <form action="index.php" method="post" class="login">
                    <input type="text" name="nick" size="20" placeholder="Username" /><br />
                    <input type="password" name="passwd" size="20" placeholder="Password" /><br />
                    <input type="submit" value="Log in" name="login" />
                </form>
<?php
            }
            elseif ($_SESSION['user'] == "new") {
                $_SESSION['oldpw'] = $_POST['passwd'];
?>
                <form action="index.php" method="post" class="login">
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

<!--  *************************************************************************************  PLUS and PROFILE  ********************************* -->        

<?php

    if (isset($_POST['new'])) {

?>

        <form action="index.php" method="post" class="floatForm">
            <table class="week">
                <tr>
                    <td>mon</td>
                    <td>tue</td>
                    <td>wed</td>
                    <td>thu</td>
                    <td>fri</td>
                    <td>sat</td>
                    <td>sun</td>
                    <td>ALL</td>
                </tr>
                <tr>
                    <td><input type="checkbox" name="mo" id="mo"></td>
                    <td><input type="checkbox" name="tu" id="tu"></td>
                    <td><input type="checkbox" name="we" id="we"></td>
                    <td><input type="checkbox" name="th" id="th"></td>
                    <td><input type="checkbox" name="fr" id="fr"></td>
                    <td><input type="checkbox" name="sa" id="sa"></td>
                    <td><input type="checkbox" name="su" id="su"></td>
                    <td><input type="checkbox" name="all" onclick="fullWeek()"></td>
                </tr>
            </table><br />

<?php
// --- Get user's unit (minutes) from DB ---
$userId = $_SESSION['user_id'] ?? 0;

$pdo = new PDO('mysql:host=localhost;dbname=app;charset=utf8mb4', 'user', 'pass', [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$stmt = $pdo->prepare('SELECT unit FROM user_config WHERE user_id = ? LIMIT 1');
$stmt->execute([$userId]);
$unit = (int)($stmt->fetchColumn());

// Fallback + sanity (allow typical units only)
$allowed = [5, 10, 15, 20, 30, 60];
if (!in_array($unit, $allowed, true)) {
  $unit = 15; // default
}

// --- Generate options for a full day ---
function fmt_time($minutes) {
  $h = floor($minutes / 60);
  $m = $minutes % 60;
  return sprintf('%02d:%02d', $h, $m);
}

$default = '08:00';

// Optional: snap default to nearest step (if you ever allow odd units)
// $defMin = 8*60;
// $snap   = round($defMin / $unit) * $unit;
// $default = fmt_time($snap);
?>
<label for="start_time" class="label">Start time</label>
<select name="start_time" id="start_time" class="start-time">
  <?php
  for ($m = 0; $m < 24*60; $m += $unit) {
    $t = fmt_time($m);
    $sel = ($t === $default) ? ' selected' : '';
    echo "<option value=\"$t\"$sel>$t</option>";
  }
  ?>
</select>

<?php
// reuse $unit from above
$stepSeconds = $unit * 60; // HTML uses seconds for the step attr
?>
<label for="start_time" class="label">Start time</label>
<input
  type="time"
  id="start_time"
  name="start_time"
  class="start-time"
  value="08:00"
  step="<?= htmlspecialchars((string)$stepSeconds, ENT_QUOTES) ?>"
  min="00:00"
  max="23:59"
/>




            <input type="text" placeholder="text" name="text" /><br />
            <input type="submit" name="newnew" value="register" />
        </form>

<?php

    }
    elseif (isset($_POST['profile'])) {

?>

        <form action="index.php" action="post" class="floatForm">
            <input type="text" placeholder="profile"><br />
            <input type="submint">
        </form>

<?php

    }

?>
<!--  *************************************************************************************  WEEKLY  ********************************* -->        

    
</body>
</html>