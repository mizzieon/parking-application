-- Current time
SELECT NOW();

-- Database Reset

-- Table structure
CREATE TABLE drivers (
	first_name TEXT,
	last_name TEXT,
	year TEXT,
	make TEXT,
	model TEXT,
	color TEXT,
	plate VARCHAR(25) UNIQUE PRIMARY KEY,
	department TEXT,
	sub_department TEXT,
	supervisor TEXT

);
CREATE TABLE violations (
	num INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL UNIQUE,
	plate TEXT,
	violation_date DATETIME,
	comment TEXT	
);

CREATE TABLE permits (
	id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL UNIQUE,
	num TEXT,
	color TEXT,
	date_assigned DATE,
	plate TEXT
);

CREATE TABLE users (
	id int(11) AUTO_INCREMENT UNIQUE NOT NULL,
	username VARCHAR(255) PRIMARY KEY NOT NULL,
	first_name TEXT,
	last_name	TEXT,
	access_level int(1),
	active BOOLEAN,
	login_attempts int(1),
	password LONGTEXT
);

CREATE TABLE action_types (
	action_type VARCHAR(255) PRIMARY KEY
);

CREATE TABLE actions (
	id int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
	user_username TEXT,
	target VARCHAR(255),
	action_type VARCHAR(255),
	FOREIGN KEY (target) REFERENCES drivers(plate),
	time_stamp DATETIME,
	FOREIGN KEY (action_type) REFERENCES action_types(action_type),
	details TEXT
);

-- Inserting a user into the database
INSERT INTO users (username, first_name, last_name, access_level, active, login_attempts, password) VALUES ('dwilson','derrian','wilson',3,true,0,null );

-- Inserting a action into the database
INSERT INTO actions (user_username, target, time_stamp, action_type, details) VALUES ('dwilson', 'PLATE',(SELECT NOW()) ,'actiontype','details');

-- Inserting action types
INSERT INTO action_types VALUES ("signed in");
INSERT INTO action_types VALUES ("signed out");
INSERT INTO action_types VALUES ("search");
INSERT INTO action_types VALUES ("new profile");
INSERT INTO action_types VALUES ("profile edit");
INSERT INTO action_types VALUES ("decal edit");
INSERT INTO action_types VALUES ("decal remove");
INSERT INTO action_types VALUES ("violation remove");
INSERT INTO action_types VALUES ("decal add");
INSERT INTO action_types VALUES ("violation add");
INSERT INTO action_types VALUES ("record view");





-- updated the violation_date coulumn to an appropriate data type
ALTER TABLE violations
MODIFY COLUMN violation_date DATETIME;

-- sample data
INSERT INTO drivers VALUES ('derrian','wilson','2015','chevrolet','camaro','yellow','EZNL65','security','none','janice');
INSERT INTO drivers VALUES ('carlos','vega','2017','dodge','charger','black','ZLR354','cigna','customer service','michael sewell');
INSERT INTO drivers VALUES ('ivry','smith','2013','mitsubishi','lancer','black','XYZ655','cigna','customer service','michael sewell');
INSERT INTO drivers VALUES ('antonio','martin','2018','honda','civic','white','D83JF7','envision','customer service','cheryl marcellus');

INSERT INTO permits (num, color, date_assigned, plate) VALUES ('A-1234','green',NULL,'EZNL65');
INSERT INTO permits (num, color, date_assigned, plate) VALUES ('A-1235','green',NULL,'ZLR354');
INSERT INTO permits (num, color, date_assigned, plate) VALUES ('A-1236','green',NULL,'XYZ655');
INSERT INTO permits (num, color, date_assigned, plate) VALUES ('A-1237','green',NULL,'D83JF7');

INSERT INTO violations (plate, violation_date, comment) VALUES ('EZNL65',NULL,'neighboors parking');
INSERT INTO violations (plate, violation_date, comment) VALUES ('ZLR354',NULL,'none');
INSERT INTO violations (plate, violation_date, comment) VALUES ('XYZ655',NULL,'');
INSERT INTO violations (plate, violation_date, comment) VALUES ('D83JF7',NULL,'no decal');

-- search queries
SELECT d.first_name as first_name,
d.last_name as last_name,
d.plate as plate,
d.make as make,
d.model as model,
p.num as decals,
v.comment as vcomment
FROM drivers as d
JOIN permits as p
ON d.plate = p.plate
JOIN violations as v
ON d.plate = v.plate;

-- searching by plate
SELECT d.first_name as first_name,
d.last_name as last_name,
d.plate as plate,
d.make as make,
d.model as model,
p.num as decals,
v.comment as vcomment
FROM drivers as d
JOIN permits as p
ON d.plate = 'EZNL65' AND p.plate = 'EZNL65'
JOIN violations as v
ON d.plate = 'EZNL65' AND v.plate = 'EZNL65';

-- searching by permits
SELECT * FROM permits WHERE num LIKE '%num%';

SELECT p.plate as permit_plate,
d.first_name as first_name,
d.last_name as last_name,
d.plate as plate,
d.make as make,
d.model as model
FROM permits as p
JOIN drivers as d  
WHERE p.num LIKE '%num%' AND p.plate = d.plate;

-- viewing queries
SELECT * FROM drivers WHERE plate = 'plate';
SELECT * FROM drivers WHERE plate LIKE '%plate%';

-- updating data
UPDATE drivers SET
first_name = '{$first_name}',
last_name = '{$last_name}',
year = '{$year}',
make = '{$make}',
model = '{$model}',
color = '{$color}',
department = '{$department}',
sub_department = '{$sub_department}',
supervisor = '{$supervisor}'
WHERE plate = '{$plate}';

-- Getting the departments and supervisor list
-- for the input list
SELECT DISTINCT department FROM drivers WHERE department != "" ORDER BY department ASC;
SELECT DISTINCT sub_department FROM drivers WHERE sub_department != '' ORDER BY sub_department ASC;
SELECT DISTINCT supervisor FROM drivers WHERE supervisor != '' ORDER BY supervisor ASC;