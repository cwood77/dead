# workaround MySQL bug where ORDER BY works on enums but MIN does not

CREATE TABLE Dead.StepStateEnumValues (
   stateInt INT(6) UNSIGNED NOT NULL,
   state ENUM('inwork', 'ready', 'blocked', 'complete', 'cancelled'),
   PRIMARY KEY(stateInt)
);

INSERT INTO Dead.StepStateEnumValues (stateInt, state) VALUES ('1', 'inwork');
INSERT INTO Dead.StepStateEnumValues (stateInt, state) VALUES ('2', 'ready');
INSERT INTO Dead.StepStateEnumValues (stateInt, state) VALUES ('3', 'blocked');
INSERT INTO Dead.StepStateEnumValues (stateInt, state) VALUES ('4', 'complete');
INSERT INTO Dead.StepStateEnumValues (stateInt, state) VALUES ('5', 'cancelled');
