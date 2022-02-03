<?php

function pickColor($i)
{
   $colors = array(
      "04aa6d", // green
      "aa6704", // orange
      "aa0404", // red
      "5304aa", // purple
      "0411aa", // blue
      "047caa", // cyan
   );

   return $colors[$i % count($colors)];
}

// pick a color based on milestone index
// maintain an array to make it a little faster
class EventColorer {
   private $nameArray;

   function __construct($timeline)
   {
      $nameArray = array();
      $list = $timeline->listEvents();
      foreach($list as $event)
      {
         array_push($nameArray,$event->name);
      }
      $this->nameArray = $nameArray;
   }

   function getColorOf($eventName)
   {
      $index = array_search($eventName, $this->nameArray);
      if ($index === false)
      {
         return "FFFFFF";
      }
      else
      {
         return pickColor($index);
      }
   }
}

function renderTimeline()
{
   $db = new Db();
   $user = $db->findUser($_SESSION['username']);
   $tl = $user->timeline();
   $events = $tl->listEvents();
   if(count($events) == 0)
   {
      $html = "<button onclick=" . '"document.location.href=' . "'timeline.php'" . '">Configure milestones</button><br/>';
   }
   else
   {
      $iterator = new EventIterator();
      foreach($events as $event)
      {
         $iterator->advance($event);
      }

      $html = "<table><tr onclick=" . '"document.location.href=' . "'timeline.php'" . '">';
      $pairs = $iterator->compute();
      $i = 0;
      foreach($pairs as $pair)
      {
         $html .= '<th class="timeline" style="background-color: #' . pickColor($i) . '" width="' . number_format($pair[0]) . '%">' . $pair[1] . '</th>';
         $i++;
      }
      $html .= '</tr></table><br/>';
   }

   return $html;
}

function generateTimelineJsEvents()
{
   $html = "[";

   $db = new Db();
   $user = $db->findUser($_SESSION['username']);
   $tl = $user->timeline();
   $events = $tl->listEvents();
   foreach($events as $event)
   {
      $html .= " '" . $event->name . "',";
   }

   $html .= "]";
   return $html;
}

?>
