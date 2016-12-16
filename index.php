<?php
session_start();
require("functions.php");
?>
<!doctype html>
<html lang="en">
<head>
   <link rel="stylesheet" href="lib/style.css" />
   <script src="lib/manipulate.js"></script>
   <meta charset="UTF-8" />
   <title>DataBaseLab</title>
</head>
<body>
<?php
if (!$_SESSION['dbl'])
{
   echo "<form method='post' action='' autocomplete='off' id='formsign'>
   <input type='text' placeholder='Логин' name='log' />
   <input type='password' placeholder='Пароль' name='pas' />
   <input type='submit' value='Войти' name='sub' />
   </form>";
   if (isset($_POST['sub']))
   {
      connectDataBase();
      $comparelp = mysql_query("SELECT * FROM `USERS` WHERE LOG='$_POST[log]'");
      $clp = mysql_fetch_array($comparelp);
      //if (md5($_POST["pas"]) == $clp["PAS"])
      if ($_POST["pas"] == $clp["PAS"])
      {
         if ($clp["PER"] == 1) {
            $_SESSION["dbl"] = "admin";
         }
         else {
            $_SESSION["dbl"] = "user";
         }
         reload();
      }
      else
      {
         echo "Sooo Sorry!";
      }
   }  
}
else
{
   echo "<form method='post' action='' id='logout'>
   <input type='submit' name='unset' value='Выйти' />
   </form>";
   if ($_GET["que"]) {
      echo  "<a href='./dblex.php?expdf=$_GET[que]' target='_blank'><span id='pwt'>Экспорт</span></a>";
   }
   if (isset($_POST['unset']))
   {
      unset($_SESSION['dbl']);
      echo "<script>location.href='YOUR SITE NAME'</script>";
   }
   connectDataBase();
   global $flagpeople;
   switch ($_SESSION['dbl']) {
      case "admin":
         $flagpeople = true;
         break;
      case "user":
         $flagpeople = false;
         break;
   }
   if ($flagpeople) $you = "Вы вошли как администратор:";
   else $you = "Вы вошли как польователь:";
   echo "<p id='whou'><span id='whoin'>$you</span></p>";
   $listtabs = mysql_query("SELECT SUBSTR(VALUE, 1, 3), NAME, SUBSTR(MASK, 1, 1) FROM `LISTTABS`");
   $lt = mysql_fetch_array($listtabs);
   echo "<p>";
   do
   {
      $ltmas = $lt["SUBSTR(MASK, 1, 1)"];
      $ltval = $lt["SUBSTR(VALUE, 1, 3)"];
      if (!$flagpeople and $ltmas == "1") {}
      else
      {
         if ($_GET["que"] == $ltval) $stylet = "whoincnow";
         else $stylet = "whoinc";
         echo "<a href='?que=$ltval'><span class='$stylet'>$lt[NAME]</span></a>";
      }
   } while ($lt = mysql_fetch_array($listtabs));
   echo "</p><div id='mainwrap'>";
   $whatTableNow;
   $tabmask;
   global $ios;
   $listtabs = mysql_query("SELECT VALUE, NAME, MASK FROM LISTTABS");
   $lt = mysql_fetch_array($listtabs);
   if ($_GET["que"])
   {
      do
      {
         if ($_GET["que"] == substr($lt["VALUE"], 0, 3))
         {
            $whatTableNow = $lt["VALUE"];
            $ios = oneQueryForAll($lt["VALUE"], $flagpeople);
            $tabmask = $lt["MASK"];
         }
      } while ($lt = mysql_fetch_array($listtabs));
   }
   else echo "<img src='lib/findme.png' id='findme' />";
   if ($flagpeople)
   {
      echo "<div id='windowbg'><div onclick='windopen();' id='windowbgin'></div>
      <div id='popup'>
      <p id='h'>Внесите изменения:<span onclick='windopen();' id='close'>+</span></p>
      <form method='post' action='' id='change'>
      <input name='hid' id='hid' type='hidden' value='$tabmask' /></form>
      </div>
      </div>";
      if (isset($_POST["add"]))
      {
         $getQuery = soa("add", $whatTableNow, $ios);
         mysql_query("$getQuery") or die(mysql_error());
         reload();
      }
      else if (isset($_POST["save"]))
      {
         $getQuery = soa("save", $whatTableNow, $ios);
         mysql_query("$getQuery") or die(mysql_error());
         reload();
      }
      else if (isset($_POST["del"]))
      {
         mysql_query("DELETE FROM $whatTableNow WHERE ID=$_POST[ID];") or die(mysql_error());
         reload();
      }
   }
   echo "</div>";
}
?>
</body>
</html>
