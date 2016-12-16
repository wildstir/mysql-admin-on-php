var formin, valHid;
var queryar = {"PEO": 6 , "SUB" : 2, "GRO" : 2, "MAR" : 5};

function windopen(num)
{
   if (num >= 0)
   {
      document.getElementById('windowbg').style.display = 'block';
      valHid = document.getElementById("hid").value;
      manipulateQuery(num, valHid.slice(1, 2));
      /*for (var val in queryar)
      {
         if (valHid == val)
         {
            manipulateQuery(num, queryar[val]);
         }
      }*/
   }
   else
   {
      document.getElementById('windowbg').style.display = 'none';
      document.getElementById("change").innerHTML = "<input name='hid' id='hid' type='hidden' value='" + valHid + "' /></form>";
   }
}

function manipulateQuery(num, iters)
{
   if (num == 0)
   {
      for (var i = 0; i < iters; i++)
      {
         formth = document.getElementsByTagName("th")[i].innerHTML;
         document.getElementById("change").innerHTML += "<input class='inpwithd' type='text' value='' placeholder='" + formth + "' name='" + formth + "' required />";
      }
      document.getElementById("change").innerHTML += "<input name='add' type='submit' value='Добавить' id='add'>";
   }
   else
   {
      var readonlySet;
      for (var i = 0; i < iters; i++)
      {
         formth = document.getElementsByTagName("th")[i].innerHTML;
         formin = document.getElementsByTagName("td")[((num-1)*iters) + i].innerHTML;
         if (i == 0)
         {
            readonlySet = " readonly";
            readonlySetId = "id='ireadonly'";
         }
         document.getElementById("change").innerHTML += "<input class='inpwithd'" + readonlySetId + " type='text' value='" + formin + "' name='" + formth + "'" + readonlySet + " required />";
         readonlySet = "";
         readonlySetId = "";
      }
      document.getElementById("change").innerHTML += "<input name='save' type='submit' value='Сохранить' id='save'>";
      document.getElementById("change").innerHTML += "<input name='del' type='submit' value='Удалить' id='del'>";
   }
}