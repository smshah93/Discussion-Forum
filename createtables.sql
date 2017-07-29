use db_name; 

DROP TABLE IF EXISTS Messages;
DROP TABLE IF EXISTS Chatroom; 
DROP TABLE IF EXISTS chat_messages;
DROP TABLE IF EXISTS ChatUser;
DROP TABLE IF EXISTS Members; 
DROP TABLE IF EXISTS Forum;
DROP TABLE IF EXISTS Thread; 
DROP TABLE IF EXISTS Post;
DROP TABLE IF EXISTS Ban; 
DROP TABLE IF EXISTS Rank;


CREATE TABLE IF NOT EXISTS Messages (
MsgID numeric(15) NOT NULL, 
MsgTime datetime, 
Subject varchar(50), 
MsgText varchar(500), 
Sender varchar(32) Not NULL, 
Receiver varchar(32) NOT NULL, 
MsgStatus enum("Read", "Deleted", "Unread", "Trash"),  
SentStatus enum("Deleted", "Sent"), 
PRIMARY KEY(MsgID)
);

CREATE TABLE IF NOT EXISTS Members (
UserName varchar(32) NOT NULL,
Password varchar(32) Not Null,
FullName varchar(32) NOT NULL,
Status enum("Deleted", "Active", "Banned", "Admin"),
PRIMARY KEY (UserName)
);

CREATE TABLE IF NOT EXISTS Chatroom (
RoomNo int(11) NOT NULL AUTO_INCREMENT,
Content LONGTEXT,
ChatStartUser varchar(32) NOT NULL,
ChatName varchar(32) NOT NULL,
PRIMARY KEY (RoomNo)
);

CREATE TABLE IF NOT EXISTS `chat_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `sender` varchar(30) NOT NULL,
  `Roomno` decimal(3,0) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

CREATE TABLE IF NOT EXISTS ChatUser (
RNo numeric(3) NOT NULL,
ChatUser varchar(32),
FOREIGN KEY (RNo) REFERENCES Chatroom(RoomNo), 
FOREIGN KEY (ChatUser) REFERENCES Members(UserName)
);

CREATE TABLE IF NOT EXISTS Forum (
ForumName varchar(32) NOT NULL,
Description varchar(250),
ForumStatus enum("Deleted", "Active", "Need"),
Moderator varchar(32) NOT NULL,
PRIMARY KEY (ForumName)
);

CREATE TABLE IF NOT EXISTS Thread (
Forum varchar(32) NOT NULL,
ThreadNo numeric(10) NOT NULL,
Description varchar(800), 
Title varchar(32), 
TimeCreated datetime,
ThreadStatus enum("Deleted", "Active"),
ThreadStartUser varchar(32) NOT NULL,
Picture varchar(100), 
FOREIGN KEY (Forum) REFERENCES Forum(ForumName),
PRIMARY KEY (ThreadNo)
);

CREATE TABLE IF NOT EXISTS Post (
FormName varchar(32) NOT NULL,
ThrNo numeric(10) NOT NULL,
PostNo numeric(20) NOT NULL, 
TimePosted datetime,
PostText varchar(500),
PostUser varchar(32) NOT NULL,
FOREIGN KEY (FormName) REFERENCES Thread(Forum),
FOREIGN KEY (ThrNo) REFERENCES Thread(ThreadNo),
PRIMARY KEY (PostNo)
);

CREATE TABLE IF NOT EXISTS Ban (
ForumID varchar(32) NOT NULL,
BannedUser varchar(32) NOT NULL,
FOREIGN KEY (ForumID) REFERENCES Forum(ForumName),
FOREIGN KEY (BannedUser) REFERENCES Members(UserName)
);

CREATE TABLE IF NOT EXISTS Rank (
User varchar(32) NOT NULL,
FNo varchar(32) NOT NULL,
TNo numeric(10) NOT NULL,
Ranking numeric(2),
FOREIGN KEY (User) REFERENCES Members(UserName),
FOREIGN KEY (TNo) REFERENCES Thread(ThreadNo),
FOREIGN KEY (FNo) REFERENCES Thread(Forum)
);

Insert into Members values('username',MD5("password"), 'Full Name', 'Admin');

show columns from Messages;
show columns from Chatroom; 
show columns from ChatUser;
show columns from Members; 
show columns from Forum;
show columns from Thread; 
show columns from Post;
show columns from Ban; 
show columns from Rank; 

