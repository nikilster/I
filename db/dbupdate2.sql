CREATE TABLE  `time`.`motivation` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`user_id` INT NOT NULL ,
`time` DATETIME NOT NULL ,
`activity_id` INT NOT NULL ,
`first_name` VARCHAR( 256 ) NOT NULL ,
`last_name` VARCHAR( 256 ) NOT NULL ,
`duration` INT NOT NULL ,
`goal` INT NOT NULL ,
`percentage` FLOAT NOT NULL ,
`activity_name` VARCHAR( 256 ) NOT NULL ,
`message` VARCHAR( 500 ) NOT NULL
) ENGINE = MYISAM ;
