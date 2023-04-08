DROP TABLE Player_Info cascade constraints;
DROP TABLE Player_Salary cascade constraints;
DROP TABLE Team cascade constraints;
DROP TABLE Fans cascade constraints;
DROP TABLE League cascade constraints;
DROP TABLE Referee cascade constraints;
DROP TABLE Staff_Info cascade constraints;
DROP TABLE Staff_Country cascade constraints;
DROP TABLE Medical cascade constraints;
DROP TABLE Coach cascade constraints;
DROP TABLE GM cascade constraints;
DROP TABLE Sponsor cascade constraints;
DROP TABLE Sponsors cascade constraints;
DROP TABLE Stadium cascade constraints;
DROP TABLE Training_team cascade constraints;
DROP TABLE Match cascade constraints;
DROP TABLE Supports cascade constraints;
DROP TABLE Referees cascade constraints;


CREATE TABLE League(
  name char(20),
  rank int,
  PRIMARY KEY (name)
);


INSERT INTO League VALUES ('Premier League', 1);
INSERT INTO League VALUES ('La Liga', 2);
INSERT INTO League VALUES ('Serie A', 3);
INSERT INTO League VALUES ('Bundesliga', 4);
INSERT INTO League VALUES ('Ligue 1', 5);

CREATE TABLE Referee(
  ID int,
  name char(20),
  age int,
  experience int,
  PRIMARY KEY (ID)
);


INSERT INTO Referee VALUES (1, 'a', 52, 10);
INSERT INTO Referee VALUES (2, 'b', 46, 12);
INSERT INTO Referee VALUES (3, 'c', 55, 8);
INSERT INTO Referee VALUES (4, 'd', 49, 14);
INSERT INTO Referee VALUES (5, 'e', 40, 6);

CREATE TABLE Referees(
  RID int NOT NULL, 
  Lname char(20), 
  PRIMARY KEY (RID,Lname),
  FOREIGN KEY (RID) REFERENCES Referee 
  ON DELETE CASCADE,
  FOREIGN KEY (Lname) REFERENCES LEAGUE 
  ON DELETE CASCADE
);


INSERT INTO Referees VALUES (1, 'Premier League');
INSERT INTO Referees VALUES (2, 'La Liga');
INSERT INTO Referees VALUES (3, 'Serie A');
INSERT INTO Referees VALUES (4, 'Premier League');
INSERT INTO Referees VALUES (5, 'Ligue 1');

CREATE TABLE Stadium(
  name char(20),
  age int,
  capacity int,
  PRIMARY KEY (name)
);


INSERT INTO Stadium VALUES ('Old Trafford',100, 20000);
INSERT INTO Stadium VALUES ('Camp Nou', 101, 99000);
INSERT INTO Stadium VALUES ('Anfield', 102, 54074);
INSERT INTO Stadium VALUES ('Allianz Arena', 103, 75000);
INSERT INTO Stadium VALUES ('Santiago Bernabeu', 104, 81044);

CREATE TABLE Team(
  rank int,
  age int,
  League_name char(20) NOT NULL,
  stadium_name  char(20) NOT NULL,
  points int,
  name char(20),
  PRIMARY KEY (name),
  FOREIGN KEY (League_name) REFERENCES LEAGUE 
  ON DELETE CASCADE,
  FOREIGN KEY (stadium_name) REFERENCES STADIUM 
  ON DELETE CASCADE
);


INSERT INTO Team VALUES (1,100, 'Premier League','Old Trafford',23,'Barcelona');
INSERT INTO Team VALUES (2,10, 'La Liga','Camp Nou',13,'Juventus');
INSERT INTO Team VALUES (11,99, 'Serie A','Anfield',22,'Arsenal');
INSERT INTO Team VALUES (3,20, 'Bundesliga','Allianz Arena',45,'Bayern Munich');
INSERT INTO Team VALUES (9,11, 'Ligue 1','Santiago Bernabeu',33,'Real Madrid');
INSERT INTO Team VALUES (15,20,'Premier League', 'Old Trafford', 38,'Manchester United');
INSERT INTO Team VALUES (18,37,'Premier League','Camp Nou',26,'Chelsea');
INSERT INTO Team VALUES (17, 26, 'Serie A', 'Anfield', 39,'AC Milan');
INSERT INTO Team VALUES (26, 43,'Bundesliga', 'Allianz Arena',20,'Borussia Dortmund');
INSERT INTO Team VALUES (37,40, 'Ligue 1', 'Anfield', 36,'Paris Saint-Germain');
INSERT INTO Team VALUES (13,21, 'Serie A', 'Camp Nou', 48,'Lille');
--INSERT INTO Team VALUES (36, 44, 'Bundesliga', 'Old Trafford', 25, 'Paris Saint-Germain');
INSERT INTO Team VALUES (50,75, 'Premier League', 'Camp Nou', 29, 'Liverpool');

CREATE TABLE Training_team(
  t_name char(20),
  team_name  char(20) NOT NULL,
  average_age  int,
  PRIMARY KEY (t_name, team_name),
  FOREIGN KEY (team_name) REFERENCES Team 
  ON DELETE CASCADE
);


INSERT INTO Training_team VALUES ('Barca U17','Barcelona', 17);
INSERT INTO Training_team VALUES ('Juventus U14', 'Juventus', 14);
INSERT INTO Training_team VALUES ('Arsenal U16', 'Arsenal', 16);
INSERT INTO Training_team VALUES ('Bayern Munich U18', 'Bayern Munich', 18);
INSERT INTO Training_team VALUES ('Real Madrid U19', 'Real Madrid', 19);


CREATE TABLE Match(
  ID int,
  winner_score int,
  loser_score int,
  Match_date date,
  home_team_name char(20) NOT NULL,
  away_team_name char(20) NOT NULL,
  PRIMARY KEY (ID, home_team_name, away_team_name),
  FOREIGN KEY (home_team_name) REFERENCES Team 
  ON DELETE CASCADE,
  FOREIGN KEY (away_team_name) REFERENCES Team 
  ON DELETE CASCADE
);


INSERT INTO Match VALUES (1, 5, 2, to_date('2002-12-23','yyyy-mm-dd'), 'Barcelona', 'Real Madrid');
INSERT INTO Match VALUES (2, 3, 1, to_date('2022-01-15','yyyy-mm-dd'), 'Manchester United', 'Chelsea');
INSERT INTO Match VALUES (3, 2, 2, to_date('2021-11-21','yyyy-mm-dd'), 'Juventus', 'AC Milan');
INSERT INTO Match VALUES (4, 4, 0, to_date('2022-05-07','yyyy-mm-dd'), 'Bayern Munich', 'Borussia Dortmund');
INSERT INTO Match VALUES (5, 1, 0, to_date('2022-03-19','yyyy-mm-dd'), 'Paris Saint-Germain', 'Lille');




CREATE TABLE Player_Salary(
  game_played int,
  goals int,
  age int,
  salary int,
  PRIMARY KEY (game_played, goals, age)
);

INSERT INTO Player_Salary VALUES (100, 1000, 35, 1000000);
INSERT INTO Player_Salary VALUES (90, 800, 36, 200000);
INSERT INTO Player_Salary VALUES (80, 700, 29, 12000000);
INSERT INTO Player_Salary VALUES (95, 600, 23, 2000000);
INSERT INTO Player_Salary VALUES (90, 500, 29, 2000000);
INSERT INTO Player_Salary VALUES (100, 400, 20, 800000);
INSERT INTO Player_Salary VALUES (30, 300, 20, 500000);
INSERT INTO Player_Salary VALUES (235, 80, 28, 650000);
INSERT INTO Player_Salary VALUES (231, 57, 30, 700000);
INSERT INTO Player_Salary VALUES (87, 29, 29, 550000);
INSERT INTO Player_Salary VALUES (68, 47, 21, 660000);
INSERT INTO Player_Salary VALUES (36, 10, 30, 666666);
INSERT INTO Player_Salary VALUES (28, 10, 32, 777777);
INSERT INTO Player_Salary VALUES (28, 12, 22, 888888);
INSERT INTO Player_Salary VALUES (19, 15, 40, 230000);

CREATE TABLE Player_Info(
  PID int,
  name char(20),
  goals int,
  age int,
  country char(20),
  game_played int,
  team_name char(20) NOT NULL,
  PRIMARY KEY (PID),
  FOREIGN KEY (game_played, goals, age) REFERENCES Player_Salary 
  ON DELETE CASCADE,
  FOREIGN KEY (team_name) REFERENCES Team 
  ON DELETE CASCADE
);


INSERT INTO Player_Info VALUES (12345, 'Leo Messi', 1000, 35, 'Argentina', 100, 'Paris Saint-Germain');
INSERT INTO Player_Info VALUES (23456, 'Cristiano Ronaldo', 800, 36, 'Portugal', 90, 'Manchester United');
INSERT INTO Player_Info VALUES (34567, 'Neymar Jr.', 700, 29, 'Brazil', 80, 'Paris Saint-Germain');
INSERT INTO Player_Info VALUES (45678, 'Kylian Mbappe', 600, 23, 'France', 95, 'Paris Saint-Germain');
INSERT INTO Player_Info VALUES (56789, 'Mohamed Salah', 500, 29, 'Egypt', 90, 'Liverpool');
INSERT INTO Player_Info VALUES (11111, 'Marcos Rojo', 400, 20, 'Argentina', 100, 'Manchester United');
INSERT INTO Player_Info VALUES (22222, 'Franco Cervi', 300, 20, 'Argentina', 30, 'Real Madrid');
INSERT INTO Player_Info VALUES (33333, 'Paulo Dybala', 80, 28, 'Argentina', 235, 'Juventus');
INSERT INTO Player_Info VALUES (44444, 'Roberto Firmino', 57, 30, 'Brazil', 231, 'Liverpool');
INSERT INTO Player_Info VALUES (55555, 'Casemiro', 29, 29, 'Brazil', 87, 'Real Madrid');
INSERT INTO Player_Info VALUES (66666, 'Erling Haaland', 47, 21, 'Norway', 68, 'Borussia Dortmund');
INSERT INTO Player_Info VALUES (77777, 'N Golo Kante', 10, 30, 'France', 36, 'Chelsea');
INSERT INTO Player_Info VALUES (88888, 'Pierre-Emerick', 10, 32, 'Gabon', 28, 'Arsenal');
INSERT INTO Player_Info VALUES (99999, 'Jonathan David', 12, 22, 'Canada', 28, 'Lille');
INSERT INTO Player_Info VALUES (98765, 'Zlatan Ibrahimovic', 15, 40, 'Sweden', 19, 'AC Milan');

CREATE TABLE Staff_Country(
  team_name char(20), 
  country char(10),
  PRIMARY KEY (team_name),
  FOREIGN KEY (team_name) REFERENCES Team 
  ON DELETE CASCADE
);


INSERT INTO Staff_Country VALUES ('Barcelona', 'Spain');
INSERT INTO Staff_Country VALUES ('Real Madrid', 'Spain');
INSERT INTO Staff_Country VALUES ('Manchester United', 'England');
INSERT INTO Staff_Country VALUES ('Juventus', 'Italy');
INSERT INTO Staff_Country VALUES ('Bayern Munich', 'Germany');


CREATE TABLE Staff_Info(
  ID int,
  name char(20),
  age int,
  team_name char(20) NOT NULL,
  salary int,
  PRIMARY KEY (ID),
  FOREIGN KEY (team_name) REFERENCES Team 
  ON DELETE CASCADE,
  FOREIGN KEY (team_name) REFERENCES Staff_Country 
  ON DELETE CASCADE
);


INSERT INTO Staff_Info VALUES (1, 'a', 23, 'Barcelona', 100);
INSERT INTO Staff_Info VALUES (2, 'b', 28, 'Real Madrid', 200);
INSERT INTO Staff_Info VALUES (3, 'c', 32, 'Manchester United', 300);
INSERT INTO Staff_Info VALUES (4, 'd', 25, 'Juventus', 400);
INSERT INTO Staff_Info VALUES (5, 'e', 30, 'Bayern Munich', 500);
INSERT INTO Staff_Info VALUES (6, 'f', 43, 'Barcelona', 100);
INSERT INTO Staff_Info VALUES (7, 'g', 48, 'Real Madrid', 200);
INSERT INTO Staff_Info VALUES (8, 'h', 42, 'Manchester United', 300);
INSERT INTO Staff_Info VALUES (9, 'i', 39, 'Juventus', 400);
INSERT INTO Staff_Info VALUES (10, 'j', 55, 'Bayern Munich', 500);
INSERT INTO Staff_Info VALUES (11, 'k', 53, 'Barcelona', 100);
INSERT INTO Staff_Info VALUES (12, 'l', 48, 'Real Madrid', 200);
INSERT INTO Staff_Info VALUES (13, 'm', 36, 'Manchester United', 300);
INSERT INTO Staff_Info VALUES (14, 'n', 34, 'Juventus', 400);
INSERT INTO Staff_Info VALUES (15, 'o', 41, 'Bayern Munich', 500);


CREATE TABLE Medical(
  ID int,
  speciality char(20),
  PRIMARY KEY (ID),
  FOREIGN KEY (ID) REFERENCES Staff_Info 
  ON DELETE CASCADE
);


INSERT INTO Medical VALUES (1, 'Eye surgent');
INSERT INTO Medical VALUES (2, 'Orthopedic surgeon');
INSERT INTO Medical VALUES (3, 'Dentist');
INSERT INTO Medical VALUES (4, 'Neurosurgeon');
INSERT INTO Medical VALUES (5, 'Cardiologist');


CREATE TABLE Coach(
  ID int,
  experience int,
  PRIMARY KEY (ID),
  FOREIGN KEY (ID) REFERENCES Staff_Info 
  ON DELETE CASCADE
);


INSERT INTO Coach VALUES (6, 5);
INSERT INTO Coach VALUES (7, 10);
INSERT INTO Coach VALUES (8, 8);
INSERT INTO Coach VALUES (9, 6);
INSERT INTO Coach VALUES (10, 4);


CREATE TABLE GM(
  ID int,
  rating int,
  PRIMARY KEY (ID),
  FOREIGN KEY (ID) REFERENCES Staff_Info 
  ON DELETE CASCADE
);


INSERT INTO GM VALUES (11, 9);
INSERT INTO GM VALUES (12, 8);
INSERT INTO GM VALUES (13, 7);
INSERT INTO GM VALUES (14, 6);
INSERT INTO GM VALUES (15, 8);


CREATE TABLE Fans(
  count int,
  country char(20),
  average_age int,
  platform char(20),
  PRIMARY KEY (country,average_age,platform)
);


INSERT INTO Fans VALUES (101, 'USA',35, 'Twitter');
INSERT INTO Fans VALUES (102, 'India',25, 'Facebook');
INSERT INTO Fans VALUES (103, 'Brazil',45, 'Instagram');
INSERT INTO Fans VALUES (104, 'Russia',35, 'Instagram');
INSERT INTO Fans VALUES (105, 'Nigeria',25, 'Twitter');


CREATE TABLE Supports(
  country char(20),
  average_age int,
  platform char(20),
  team_name char(20),
  PRIMARY KEY (country,average_age,platform,team_name),
  FOREIGN KEY (country,average_age,platform) REFERENCES Fans
  ON DELETE CASCADE,
  FOREIGN KEY (team_name) REFERENCES Team 
  ON DELETE CASCADE
);


INSERT INTO Supports VALUES ('USA',35, 'Twitter', 'Manchester United');
INSERT INTO Supports VALUES ('India',25, 'Facebook', 'Paris Saint-Germain');
INSERT INTO Supports VALUES ('Brazil',45, 'Instagram', 'Liverpool');
INSERT INTO Supports VALUES ('Russia',35, 'Instagram', 'Paris Saint-Germain');
INSERT INTO Supports VALUES ('Nigeria',25, 'Twitter','Paris Saint-Germain');


CREATE TABLE Sponsor(
  brand char(20),
  contract int,
  PRIMARY KEY (brand)
);


INSERT INTO Sponsor VALUES ('NIKE', 2);
INSERT INTO Sponsor VALUES ('Adidas', 1);
INSERT INTO Sponsor VALUES ('Puma', 3);
INSERT INTO Sponsor VALUES ('Under Armour', 4);
INSERT INTO Sponsor VALUES ('New Balance', 5);


CREATE TABLE Sponsors(
  brand char(20),
  team_name char(20),
  amount int,
  PRIMARY KEY (brand, team_name),
  FOREIGN KEY (brand) REFERENCES SPONSOR 
  ON DELETE CASCADE,
  FOREIGN KEY (team_name) REFERENCES Team 
  ON DELETE CASCADE
);


INSERT INTO Sponsors VALUES ('NIKE', 'Barcelona',1000000);
INSERT INTO Sponsors VALUES ('Adidas', 'Real Madrid', 800000);
INSERT INTO Sponsors VALUES ('Puma', 'Manchester United', 700000);
INSERT INTO Sponsors VALUES ('Under Armour', 'Chelsea', 600000);
INSERT INTO Sponsors VALUES ('New Balance', 'Liverpool', 500000);




























































