#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------


#------------------------------------------------------------
# Table: users
#------------------------------------------------------------

CREATE TABLE users(
        Id       Int  Auto_increment  NOT NULL ,
        Type     Varchar (50) NOT NULL ,
        Login    Varchar (50) NOT NULL ,
        Password Varchar (50) NOT NULL
	,CONSTRAINT users_PK PRIMARY KEY (Id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Post
#------------------------------------------------------------

CREATE TABLE Post(
        Id       Int  Auto_increment  NOT NULL ,
        Title    Varchar (50) NOT NULL ,
        Post     Varchar (50) NOT NULL ,
        Id_users Int NOT NULL
	,CONSTRAINT Post_PK PRIMARY KEY (Id)

	,CONSTRAINT Post_users_FK FOREIGN KEY (Id_users) REFERENCES users(Id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Commentary
#------------------------------------------------------------

CREATE TABLE Commentary(
        Id         Int  Auto_increment  NOT NULL ,
        Title      Varchar (50) NOT NULL ,
        Commentary Varchar (50) NOT NULL ,
        Id_users   Int NOT NULL ,
        Id_Post    Int NOT NULL
	,CONSTRAINT Commentary_PK PRIMARY KEY (Id)

	,CONSTRAINT Commentary_users_FK FOREIGN KEY (Id_users) REFERENCES users(Id)
	,CONSTRAINT Commentary_Post0_FK FOREIGN KEY (Id_Post) REFERENCES Post(Id)
)ENGINE=InnoDB;

