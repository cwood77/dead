CREATE TABLE Dead.Milestones (
   id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
   userID INT(11) UNSIGNED NOT NULL,
   deadline VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
   name VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
   PRIMARY KEY (id),
   FOREIGN KEY (userID) REFERENCES Dead.Users(id) ON DELETE CASCADE
);

ALTER TABLE Dead.UserPrefs ADD
   age INT(11) UNSIGNED DEFAULT 0
;
