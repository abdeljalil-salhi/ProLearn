CREATE TABLE analytics
(
    title VARCHAR(255),
    value INT(11)
);
INSERT INTO analytics VALUES ('visits', 0);
CREATE TABLE classes
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255)
);
CREATE TABLE class_grps
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    class_id INT(3),
    name VARCHAR(255)
);
CREATE TABLE subjects
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255)
);
CREATE TABLE classes_subjects
(
    grp_id INT(10),
    subjects TEXT
);
CREATE TABLE variables
(
    title VARCHAR(255),
    value TEXT
);
CREATE TABLE staff
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255),
    password TEXT,
    rank INT(3)
);
INSERT INTO staff VALUES ('admin', '$2y$09$kOGpbrAEiGuSBuzNXtdcxeTjN05M5O9smuVUTcFaWdhZWhRxpZ5UW', 9);
/*admin:prolearn2020*/
CREATE TABLE teachers
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255),
    firstname VARCHAR(255),
    lastname VARCHAR(255),
    gender VARCHAR(10),
    birthdate VARCHAR(100),
    subject INT(11),
    last_seen VARCHAR(20),
    last_ip VARCHAR(100),
    last_device VARCHAR(255),
    password TEXT
);
CREATE TABLE deleted_teachers
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255),
    firstname VARCHAR(255),
    lastname VARCHAR(255),
    gender VARCHAR(10),
    birthdate VARCHAR(100),
    subject INT(11),
    last_seen VARCHAR(20),
    last_ip VARCHAR(100),
    last_device VARCHAR(255),
    password TEXT
);
CREATE TABLE students
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255),
    firstname VARCHAR(255),
    lastname VARCHAR(255),
    gender VARCHAR(10),
    class VARCHAR(100),
    class_grp VARCHAR(100),
    birthdate VARCHAR(100),
    last_seen VARCHAR(100),
    last_ip VARCHAR(100),
    last_device VARCHAR(255),
    password TEXT
);
CREATE TABLE deleted_students
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255),
    firstname VARCHAR(255),
    lastname VARCHAR(255),
    gender VARCHAR(10),
    class VARCHAR(100),
    class_grp VARCHAR(100),
    birthdate VARCHAR(100),
    last_seen VARCHAR(100),
    last_ip VARCHAR(100),
    last_device VARCHAR(255),
    password TEXT
);
CREATE TABLE parents
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255),
    firstname VARCHAR(255),
    lastname VARCHAR(255),
    student_id INT(11),
    last_seen VARCHAR(20),
    last_ip VARCHAR(100),
    last_device VARCHAR(255),
    password TEXT
);
CREATE TABLE deleted_parents
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255),
    firstname VARCHAR(255),
    lastname VARCHAR(255),
    student_id INT(11),
    last_seen VARCHAR(20),
    last_ip VARCHAR(100),
    last_device VARCHAR(255),
    password TEXT
);