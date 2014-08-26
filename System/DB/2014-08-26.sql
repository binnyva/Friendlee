ALTER TABLE `User` ADD `settings` VARCHAR(255) NOT NULL AFTER `added_on`;
ALTER TABLE `Person` ADD `automanaged` INT(3) NOT NULL DEFAULT '0' AFTER `autocomplete`;
