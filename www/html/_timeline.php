<?php

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
      foreach($pairs as $pair)
      {
         $html .= '<th class="timeline" width="' . number_format($pair[0]) . '%">' . $pair[1] . '</th>';
      }
      $html .= '</tr></table><br/>';
   }

   return $html;
}

?>
