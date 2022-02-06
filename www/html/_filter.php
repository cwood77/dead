<?php

//require '/var/www/html/util.php';
//require '/var/www/html/db.php';

leaveIfNoSession();

class FilterSettingsMethods {
   private $db;
   private $jsonObj;
   private $filterCnt;

   function __construct($db, $jsonObj)
   {
      $this->db = $db;
      $this->jsonObj = $jsonObj;
      $this->filterCnt = 0;
   }

   function computeWhereClause($alreadyStarted)
   {
      return "";
   }

   function computeHavingClause()
   {
      $queryText = " HAVING impliedGoalState NOT IN (";
      $any = false;

      $this->_computeHavingClausePart($this->jsonObj->hideIdeas,0,$any,$queryText);
      $this->_computeHavingClausePart($this->jsonObj->hideBlocked,3,$any,$queryText);
      $this->_computeHavingClausePart($this->jsonObj->hideCompleted,4,$any,$queryText);
      $this->_computeHavingClausePart($this->jsonObj->hideCancelled,5,$any,$queryText);

      $queryText .= ")";
      if ($any)
      {
         return $queryText;
      }
      else
      {
         return "";
      }
   }

   function _computeHavingClausePart($flag, $intVal, &$any, &$queryText)
   {
      if ($flag)
      {
         $this->filterCnt++;
         if ($any)
         {
            $queryText .= ",";
         }
         $queryText .= "'" . $intVal . "'";
         $any = true;
      }
   }

   function computeFilterButtonLabel()
   {
      if($this->filterCnt == 0)
      {
         return "Not filtering";
      }
      else
      {
         return "Filtering " . $this->filterCnt . " class(es)";
      }
   }
}

function fetchSortSettings($db)
{
   $sortMode = '{ "sortMode": "Sort&#32;by&#32;state" }';
   if(array_key_exists('sort-stash',$_SESSION))
   {
      $sortMode = $_SESSION['sort-stash'];
   }
   $sortMode = json_decode($sortMode);
   $sortMode = $sortMode->{ 'sortMode' };
   return $sortMode;
}

function fetchFilterSettings($db)
{
   $filterMode =
      '{'
      . ' "hideIdeas": false,'
      . ' "hideBlocked": false,'
      . ' "hideCompleted": false,'
      . ' "hideCancelled": false,'
      . ' "hideLaterMilestones": false'
   .' }';
   if(array_key_exists('filter-stash',$_SESSION))
   {
      $filterMode = $_SESSION['filter-stash'];
   }
   $filterMode = json_decode($filterMode);

   // attach the class
   $fmArray = (array)$filterMode;
   $fmArray['methods'] = new FilterSettingsMethods($db,$filterMode);
   $filterMode = (object)$fmArray;

   return $filterMode;
}

?>
