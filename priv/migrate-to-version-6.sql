# double lengths to 100 chars

USE Dead;

ALTER TABLE Goals MODIFY COLUMN 
   title VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
;

ALTER TABLE Steps MODIFY COLUMN 
   title VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
;
