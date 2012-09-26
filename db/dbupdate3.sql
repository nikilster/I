#We are switching start_time and end_time from datetime to timestamps
ALTER TABLE  `events` CHANGE  `start_time`  `start_time` TIMESTAMP NULL DEFAULT NULL;
ALTER TABLE  `events` CHANGE  `end_time`  `end_time` TIMESTAMP NULL DEFAULT NULL;