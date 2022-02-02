<?php

require 'util.php';
require 'db.php';
require '_timeline.php';

leaveIfNoSession();

?>

<html>
<head>
<link rel="stylesheet" href="main.css"/>
<title>Dead</title>
<?php require 'api.php'; includeJsApis(array("toggleDashboard","showAll","timeline")); ?>
<script>

function load()
{
   var good = function(json)
   {
      jsonToTable(json['events']);
   }
   api.loadMilestones(good);
   handleAgeChange();
}

function handleAgeChange()
{
   updateTable();
   calculateDeath();
}

function updateTable()
{
   var age = parseInt(document.getElementById("age").value);

   var entries = document.getElementsByClassName("tableAge");
   for (var i=0;i<entries.length;i++)
   {
      var deadline = entries[i].parentElement.getElementsByTagName("td")[1].firstChild.value;
      var ageAtDeadline = age + (parseInt(deadline) - new Date().getFullYear());
      if (isNaN(ageAtDeadline))
      {
         entries[i].innerHTML = "";
      }
      else
      {
         entries[i].innerHTML = ageAtDeadline;
      }
   }
}

function calculateDeath()
{
   var age = document.getElementById("age").value;
   var expectancy = document.getElementById("expectancy").value;
   var ansCtrl = document.getElementById("death");

   var ans = new Date().getFullYear() + parseInt(expectancy) - parseInt(age);
   if (isNaN(ans))
   {
      ansCtrl.value = "";
   }
   else
   {
      ansCtrl.value = ans;
   }
}

function handleExpectancyPicker(sel)
{
   var ctrl = document.getElementById("expectancy");
   if(sel.value == "custom")
   {
      ctrl.disabled = false;
   }
   else
   {
      // as of 2022, google says they're identical for both sexes
      ctrl.value = "79";
      ctrl.disabled = true;
      calculateDeath();
   }
}

function tableToJson()
{
   var data = [];
   var rows = document.getElementById("milestoneTable").getElementsByTagName("tr");
   for (var i=1;i<rows.length; i++)
   {
      var pair = [
         rows[i].getElementsByTagName("td")[1].firstChild.value,
         rows[i].getElementsByTagName("td")[2].firstChild.value
      ];
      data.push(pair);
   }
   return data;
}

function jsonToTable(json)
{
   var html = "<tr><th>Age</th><th>Deadline</th><th>Name</th><th></th></tr>";
   for (var i=0;i<json.length; i++)
   {
      html += "<tr>";
      html += "<td class='tableAge'></td>";
      html += "<td><input type='text' value='" + json[i][0] + "' onkeyup='updateTable()'></td>";
      html += "<td><input type='text' value='" + json[i][1] + "'></td>";
      html += "<td><button class='deleteBtn' onclick='deleteRow(" + i + ")'>Delete</button></td>";
      html += "</tr>";
   }
   document.getElementById("milestoneTable").innerHTML = html;
   updateTable();
}

function prepend()
{
   var json = tableToJson();
   json.unshift(['','']);
   jsonToTable(json);
}

function append()
{
   var json = tableToJson();
   json.push(['','']);
   jsonToTable(json);
}

function deleteRow(index)
{
   var json = tableToJson();
   json.splice(index,1);
   jsonToTable(json);
}

function save()
{
   var bigString = JSON.stringify(tableToJson());
   api.saveMilestones(bigString,function(){});
}

</script>
</head>
<body onload="load()">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<button onclick="prepend()">Prepend milestone</button>  <button onclick="append()">Append milestone</button>  <button onclick="save()">Save</button> <br/>
<br/>
<table id="milestoneTable"></table>
<br/>
Age Calculator:</br>
Current metabolic age <input id="age" type="text" onkeyup="handleAgeChange()"></br> 
Life expectancy <select onchange="handleExpectancyPicker(this)">
   <option>average USA male</option>
   <option>average USA female</option>
   <option>custom</option>
</select>   <input id="expectancy" type="text" value="79" onkeyup="calculateDeath()" disabled></br>
Estimated death: <input id="death" type="text" disabled>
</body>
</html>
