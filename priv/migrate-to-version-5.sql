USE Dead;

ALTER TABLE Steps CHANGE state
   state ENUM('inwork', 'ready', 'blocked', 'complete', 'cancelled')
;
