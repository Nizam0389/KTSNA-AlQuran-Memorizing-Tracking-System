database


-- Create the database
CREATE DATABASE ktsna_quran;

-- Use the database
USE ktsna_quran;

-- Create the 'class' table
CREATE TABLE class (
    class_id VARCHAR(5) PRIMARY KEY,
    class_name VARCHAR(30),
    year INT,
    class_type VARCHAR(30)
);

-- Create the 'staff' table
CREATE TABLE staff (
    staff_id VARCHAR(5) PRIMARY KEY,
    staff_name VARCHAR(50),
    staff_username VARCHAR(20),
    staff_pass VARCHAR(20),
    staff_type VARCHAR(30)
);

-- Create the 'student' table
CREATE TABLE student (
    student_id VARCHAR(5) PRIMARY KEY,
    student_name VARCHAR(50),
    student_username VARCHAR(20),
    student_pass VARCHAR(20),
    class_id VARCHAR(5),
    FOREIGN KEY (class_id) REFERENCES class(class_id)
);

-- Create the 'memorizing_record' table
CREATE TABLE memorizing_record (
    memo_id VARCHAR(5) PRIMARY KEY,
    page INT,
    juzu INT,
    surah INT,
    date DATE,
    session CHAR(1),
    status CHAR(1),
    student_id VARCHAR(5),
    staff_id VARCHAR(5),
    FOREIGN KEY (student_id) REFERENCES student(student_id),
    FOREIGN KEY (staff_id) REFERENCES staff(staff_id)
);
