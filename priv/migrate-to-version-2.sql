USE Dead;

CREATE TABLE UserPrefs (
   id INT(11) UNSIGNED NOT NULL,
   goalPriority INT(8) UNSIGNED NOT NULL DEFAULT 3,
   stepPriority INT(8) UNSIGNED NOT NULL DEFAULT 3,
   stepState ENUM('inwork', 'ready', 'blocked', 'complete') DEFAULT 'ready',
   PRIMARY KEY (id),
   FOREIGN KEY (id) REFERENCES Users(id) ON DELETE CASCADE
);

ALTER TABLE Goals DROP COLUMN brokenDown;

# seed the prefs with default values
INSERT INTO UserPrefs SELECT id, '3', '3', 'ready' FROM Users;
