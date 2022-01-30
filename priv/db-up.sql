CREATE DATABASE Dead;

CREATE TABLE Dead.Users (
   id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
   userName VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
   password VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
   superuser BOOLEAN DEFAULT FALSE,
   PRIMARY KEY (id)
);

CREATE TABLE Dead.Goals (
   id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
   userID INT(11) UNSIGNED NOT NULL,
   title VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
   priority INT(8) UNSIGNED NOT NULL DEFAULT 4,
   brokenDown BOOLEAN DEFAULT FALSE,
   PRIMARY KEY (id),
   FOREIGN KEY (userID) REFERENCES Dead.Users(id) ON DELETE CASCADE
);

CREATE TABLE Dead.GoalsVisibleToUser (
   looker INT(11) UNSIGNED NOT NULL,
   lookee INT(11) UNSIGNED NOT NULL,
   PRIMARY KEY(looker, lookee),
   FOREIGN KEY (looker) REFERENCES Dead.Users(id) ON DELETE CASCADE,
   FOREIGN KEY (lookee) REFERENCES Dead.Users(id) ON DELETE CASCADE
);

CREATE TABLE Dead.Steps (
   id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
   goalID INT(11) UNSIGNED NOT NULL,   
   title VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
   priority INT(8) UNSIGNED NOT NULL DEFAULT 4,
   state ENUM('blocked', 'ready', 'inwork', 'complete'),
   PRIMARY KEY(id),
   FOREIGN KEY(goalID) REFERENCES Dead.Goals(id) ON DELETE CASCADE
);

CREATE TABLE Dead.GoalHistory (
   id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
   goalID INT(11) UNSIGNED NOT NULL,
   kind ENUM('comment', 'auto'),
   userID INT(11) UNSIGNED,
   text VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
   ts TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (id),
   FOREIGN KEY (goalID) REFERENCES Dead.Goals(id) ON DELETE CASCADE,
   FOREIGN KEY (userID) REFERENCES Dead.Users(id)
);
