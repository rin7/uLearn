-- ULearn Database Schema
-- Reverse-engineered from PHP source code (2005 APTECH project)

CREATE DATABASE IF NOT EXISTS `ulearn`;
USE `ulearn`;

-- ============================================
-- Table: students
-- ============================================
CREATE TABLE IF NOT EXISTS `students` (
  `SUserName` VARCHAR(50) NOT NULL,
  `password` VARCHAR(50) NOT NULL,
  `FName` VARCHAR(100) DEFAULT NULL,
  `LName` VARCHAR(100) DEFAULT NULL,
  `Gender` VARCHAR(10) DEFAULT NULL,
  `SSN` VARCHAR(20) DEFAULT NULL,
  `LAddress` TEXT DEFAULT NULL,
  `PAddress` TEXT DEFAULT NULL,
  `EMail` VARCHAR(100) DEFAULT NULL,
  `Tel` VARCHAR(20) DEFAULT NULL,
  `IME` VARCHAR(100) DEFAULT NULL,
  `FSEU` VARCHAR(100) DEFAULT NULL,
  `Ethnicity` VARCHAR(50) DEFAULT NULL,
  `SATScore` VARCHAR(20) DEFAULT NULL,
  PRIMARY KEY (`SUserName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ============================================
-- Table: faculty
-- ============================================
CREATE TABLE IF NOT EXISTS `faculty` (
  `FUserName` VARCHAR(50) NOT NULL,
  `Password` VARCHAR(50) NOT NULL,
  `FName` VARCHAR(100) DEFAULT NULL,
  `LName` VARCHAR(100) DEFAULT NULL,
  `Gender` VARCHAR(10) DEFAULT NULL,
  `SSN` VARCHAR(20) DEFAULT NULL,
  `Address` TEXT DEFAULT NULL,
  `Tel` VARCHAR(20) DEFAULT NULL,
  `EMail` VARCHAR(100) DEFAULT NULL,
  `authorization` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`FUserName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ============================================
-- Table: course
-- ============================================
CREATE TABLE IF NOT EXISTS `course` (
  `CCode` VARCHAR(20) NOT NULL,
  `CName` VARCHAR(200) DEFAULT NULL,
  `FUserName` VARCHAR(50) DEFAULT NULL,
  `CDetails` TEXT DEFAULT NULL,
  PRIMARY KEY (`CCode`),
  KEY `FK_course_faculty` (`FUserName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ============================================
-- Table: studentscourse (junction table)
-- ============================================
CREATE TABLE IF NOT EXISTS `studentscourse` (
  `SUserName` VARCHAR(50) NOT NULL,
  `CCode` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`SUserName`, `CCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ============================================
-- Table: studenthistory
-- ============================================
CREATE TABLE IF NOT EXISTS `studenthistory` (
  `SUserName` VARCHAR(50) NOT NULL,
  `UME` VARCHAR(100) DEFAULT NULL,
  `DegreeSought` VARCHAR(100) DEFAULT NULL,
  `AdditionalDegree` VARCHAR(100) DEFAULT NULL,
  `CurrentClassification` VARCHAR(100) DEFAULT NULL,
  `Catalog` VARCHAR(100) DEFAULT NULL,
  `CertificationSought` VARCHAR(100) DEFAULT NULL,
  `FSE` VARCHAR(100) DEFAULT NULL,
  `LSE` VARCHAR(100) DEFAULT NULL,
  `LReason` TEXT DEFAULT NULL,
  PRIMARY KEY (`SUserName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ============================================
-- Seed Data: Admin Faculty User
-- ============================================
INSERT INTO `faculty` (`FUserName`, `Password`, `FName`, `LName`, `Gender`, `SSN`, `Address`, `Tel`, `EMail`, `authorization`)
VALUES ('admin', 'admin123', 'Admin', 'Faculty', 'male', '000-00-0000', 'APTECH Campus', '555-0100', 'admin@ulearn.edu', 'admin');

-- ============================================
-- Seed Data: Sample Faculty
-- ============================================
INSERT INTO `faculty` (`FUserName`, `Password`, `FName`, `LName`, `Gender`, `SSN`, `Address`, `Tel`, `EMail`, `authorization`)
VALUES ('jsmith', 'pass123', 'John', 'Smith', 'male', '111-11-1111', '123 Faculty Lane', '555-0101', 'jsmith@ulearn.edu', 'admin');

-- ============================================
-- Seed Data: Sample Student
-- ============================================
INSERT INTO `students` (`SUserName`, `password`, `FName`, `LName`, `Gender`, `SSN`, `LAddress`, `PAddress`, `EMail`, `Tel`, `IME`, `FSEU`, `Ethnicity`, `SATScore`)
VALUES ('student1', 'pass123', 'Jane', 'Doe', 'femaile', '222-22-2222', '456 Student Ave', '456 Student Ave', 'jane@ulearn.edu', '555-0201', 'Direct', 'Fall 2005', 'Asian', '1200');

-- ============================================
-- Seed Data: Sample Courses
-- ============================================
INSERT INTO `course` (`CCode`, `CName`, `FUserName`, `CDetails`)
VALUES ('CS101', 'Introduction to Computer Science', 'jsmith', 'Fundamentals of computing, programming logic, and problem solving.');

INSERT INTO `course` (`CCode`, `CName`, `FUserName`, `CDetails`)
VALUES ('CS201', 'Data Structures and Algorithms', 'jsmith', 'Arrays, linked lists, trees, sorting and searching algorithms.');

INSERT INTO `course` (`CCode`, `CName`, `FUserName`, `CDetails`)
VALUES ('CS301', 'Web Development', 'admin', 'HTML, CSS, JavaScript, PHP and MySQL for building web applications.');

-- ============================================
-- Seed Data: Sample Enrollment
-- ============================================
INSERT INTO `studentscourse` (`SUserName`, `CCode`)
VALUES ('student1', 'CS101');

-- ============================================
-- Seed Data: Sample Student History
-- ============================================
INSERT INTO `studenthistory` (`SUserName`, `UME`, `DegreeSought`, `AdditionalDegree`, `CurrentClassification`, `Catalog`, `CertificationSought`, `FSE`, `LSE`, `LReason`)
VALUES ('student1', 'Direct', 'BSc Computer Science', 'None', 'Freshman', '2005-2006', 'ACCP', 'Fall 2005', '', '');
