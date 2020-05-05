-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema music_lesson_service
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema music_lesson_service
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `music_lesson_service` DEFAULT CHARACTER SET utf8 ;
USE `music_lesson_service` ;

-- -----------------------------------------------------
-- Table `music_lesson_service`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `music_lesson_service`.`users` ;

CREATE TABLE IF NOT EXISTS `music_lesson_service`.`users` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `fname` VARCHAR(45) NOT NULL,
  `lname` VARCHAR(45) NOT NULL,
  `email_address` VARCHAR(45) UNIQUE NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  `is_teacher` TINYINT NOT NULL,
  `is_student` TINYINT NOT NULL,
  `charge_rate` DECIMAL NULL,
  PRIMARY KEY (`user_id`))
ENGINE = InnoDB;

CREATE UNIQUE INDEX `user_id_UNIQUE` ON `music_lesson_service`.`users` (`user_id` ASC);


-- -----------------------------------------------------
-- Table `music_lesson_service`.`instruments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `music_lesson_service`.`instruments` ;

CREATE TABLE IF NOT EXISTS `music_lesson_service`.`instruments` (
  `instrument_id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`instrument_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `music_lesson_service`.`teaches_r`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `music_lesson_service`.`teaches_r` ;

CREATE TABLE IF NOT EXISTS `music_lesson_service`.`teaches_r` (
  `teacher_id` INT NOT NULL,
  `instrument_id` INT NOT NULL,
  CONSTRAINT `Teacher`
    FOREIGN KEY (`teacher_id`)
    REFERENCES `music_lesson_service`.`users` (`user_id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `Instrument`
    FOREIGN KEY (`instrument_id`)
    REFERENCES `music_lesson_service`.`instruments` (`instrument_id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;

CREATE INDEX `Teacher_idx` ON `music_lesson_service`.`teaches_r` (`teacher_id` ASC);

CREATE INDEX `Instrument_idx` ON `music_lesson_service`.`teaches_r` (`instrument_id` ASC);


-- -----------------------------------------------------
-- Table `music_lesson_service`.`lessons`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `music_lesson_service`.`lessons` ;

CREATE TABLE IF NOT EXISTS `music_lesson_service`.`lessons` (
  `lesson_id` INT NOT NULL AUTO_INCREMENT,
  `date` DATE NOT NULL,
  `start_time` TIME NOT NULL,
  `end_time` TIME NOT NULL,
  `teacher_id` INT NOT NULL,
  `student_id` INT NULL,
  PRIMARY KEY (`lesson_id`),
  CONSTRAINT `Teacher 2`
    FOREIGN KEY (`teacher_id`)
    REFERENCES `music_lesson_service`.`users` (`user_id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `Student 2`
    FOREIGN KEY (`student_id`)
    REFERENCES `music_lesson_service`.`users` (`user_id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;

CREATE INDEX `Teacher 2_idx` ON `music_lesson_service`.`lessons` (`teacher_id` ASC);

CREATE INDEX `Student 2_idx` ON `music_lesson_service`.`lessons` (`student_id` ASC);


-- -----------------------------------------------------
-- Table `music_lesson_service`.`reviews`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `music_lesson_service`.`reviews` ;

CREATE TABLE IF NOT EXISTS `music_lesson_service`.`reviews` (
  `review_id` INT NOT NULL AUTO_INCREMENT,
  `date` DATE NOT NULL,
  `time` TIME NOT NULL,
  `rating_score` DECIMAL NOT NULL,
  `review_body` LONGTEXT NOT NULL,
  `teacher_id` INT NOT NULL,
  `student_id` INT NOT NULL,
  PRIMARY KEY (`review_id`),
  CONSTRAINT `Teacher 3`
    FOREIGN KEY (`teacher_id`)
    REFERENCES `music_lesson_service`.`users` (`user_id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `Student 3`
    FOREIGN KEY (`student_id`)
    REFERENCES `music_lesson_service`.`users` (`user_id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;

CREATE INDEX `Teacher 3_idx` ON `music_lesson_service`.`reviews` (`teacher_id` ASC);

CREATE INDEX `Student 3_idx` ON `music_lesson_service`.`reviews` (`student_id` ASC);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
