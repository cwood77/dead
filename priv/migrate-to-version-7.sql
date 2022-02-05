# add description field to goals

USE Dead;

ALTER TABLE Goals ADD
   descr VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci
;
