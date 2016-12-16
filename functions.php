<?php
//Reload Page For Veiw Changes
function reload()
{
	echo "<script>location.href='YOUR SITE NAME$_SERVER[REQUEST_URI]'</script>";
}

//Connect To Database With JSON
function connectDataBase()
{
   $input = file_get_contents('lib/config.json', 200);
   $obj = json_decode($input);
   $dbin = [];
   $i = 0;
   foreach ($obj as $key => $value) {
      $dbin[$i] = $value;
      $i += 1;
   }
   mysql_connect("$dbin[0]", "$dbin[1]", "$dbin[2]") OR DIE("ConnectError!");
   mysql_select_db("$dbin[3]") or die("WorngDataBase");
   mysql_query("SET NAMES `utf8`");
}

//Dynmic Query For DataBase Well
function oneQueryForAll($where, $flagpeople)
{
   $returnAll = "";
   $dynamicQuery = mysql_query("SELECT * FROM `$where`") or die(mysql_error());
   $dq = mysql_fetch_array($dynamicQuery);
   $returnAll .= "<table><tr>";
   $len = 0;
   $intorstring = [];
   foreach($dq as $key => $value)
   {
      if ($len % 2 == 1)
      {
         $asot[($len-1)/2] = $key;
         $op = $key;
         $returnAll .= "<th>$key</th>";
      }
      $len += 1;
   }
   $returnAll .= "</tr>";
   $j = 0;
   do
   {
      $j++;
      if ($flagpeople)
      {
         $returnAll .= "<tr onclick='windopen($j);'>";
      }
      else
      {
         $returnAll .= "<tr>";
      }
      for ($i = 0; $i < count($asot); $i++)
      {
         $op = $asot[$i];
         if (is_numeric($dq[$op]))
         {
            $intorstring[$i] = 1;
         }            
         else
         {
            $intorstring[$i] = 0;
         }
         $returnAll .= "<td>$dq[$op]</td>";
      }
      $returnAll .= "</tr>";
   } while ($dq = mysql_fetch_array($dynamicQuery));
   echo $returnAll."</table>";
   if ($flagpeople) echo "<p id='addbut' onclick='windopen(0);'>+</p>";
   return $intorstring;
}

//Save Or Add Data In Database
function soa($sa, $whatTableNow, $ios)
{
   $ic = count($_POST);
   $i = 0;
   switch ($sa) {
      case "add":
         $strsaar = "INSERT INTO $whatTableNow VALUES(";
         foreach ($_POST as $key => $value) {
            if ($i > 0 and $i < $ic - 2)
            {
               if ($ios[$i-1] == 1)
               {
                  $strsaar .= $value.", ";
               }
               else
               {
                  $strsaar .= "'".$value."', ";
               }
            }
            else if ($i == $ic - 2)
            {
               if ($ios[$i-1] == 1)
               {
                  $strsaar .= $value;
               }
               else
               {
                  $strsaar .= "'".$value."'";
               }
            }
            $i += 1;
         }
         $strsaar .= ")";
         break;
      case "save":
         $strsaar = "UPDATE $whatTableNow SET ";
         foreach ($_POST as $key => $value) {
            if ($i > 0 and $i < $ic - 2)
            {
               if ($ios[$i-1] == 1)
               {
                  $strsaar .= $key."=".$value.", ";
               }
               else
               {
                  $strsaar .= $key."='".$value."', ";
               }
            }
            else if ($i == $ic - 2)
            {
               if ($ios[$i-1] == 1)
               {
                  $strsaar .= $key."=".$value;
               }
               else
               {
                  $strsaar .= $key."='".$value."'";
               }
            }
            $i += 1;
         }
         $strsaar .= " WHERE ID=$_POST[ID]";
         break;
   }
   return $strsaar;
}
?>