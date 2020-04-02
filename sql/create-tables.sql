create table Users (
	id int unsigned not null unique auto_increment,
	email varchar(50) not null unique,
	password char(30) not null,
	primary key (id)
)

create table Labels (
	id int unsigned not null unique auto_increment,
	user_id int unsigned not null,
	name varchar(20),
	color char(7),
	primary key (id),
	foreign key (user_id) references Users(id) on update cascade on delete cascade
)

create table Projects (

	id int unsigned not null unique auto_increment,
	user_id int unsigned not null,
	name varchar(50) not null,
	description text,
	date_created datetime not null,
	date_due datetime,
	display_index int unsigned not null,
	primary key (id),
	foreign key (user_id) references Users (id) on update cascade on delete cascade,
	unique key (user_id, display_index)
)

create table Project_Notes (
	id int unsigned not null unique auto_increment,
	project_id int unsigned not null,
	content text,
	display_index int unsigned not null,
	primary key (id),
	foreign key (project_id) references Projects (id) on update cascade on delete cascade,
	unique key (user_id, display_index)
)

create table Project_Checklists (
	id int unsigned not null unique auto_increment,
	project_id int unsigned not null,
	name varchar(30) not null,
	display_index int unsigned not null,
	primary key (id),
	foreign key (project_id) references Projects(id) on update cascade on delete cascade,
	unique key (project_id, display_index)
)

create table Project_Checklist_Items (
	id int unsigned not null unique auto_increment,
	project_checklist_id int unsigned not null,
	content text,
	completed enum('n', 'y') default 'n' not null,
	display_index int unsigned not null,
	primary key (id),
	foreign key (project_checklist_id) references Project_Checklists(id) on update cascade on delete cascade,
	unique key (project_checklist_id, display_index)
)

create table Items (
	id int unsigned not null unique auto_increment,
	project_id int unsigned not null,
	name varchar(50) not null,
	description text,
	date_created datetime not null,
	date_due datetime,
	completed enum('n', 'y') default 'n' not null,
	display_index int unsigned not null,
	primary key (id),
	foreign key (project_id) references Projects(id) on update cascade on delete cascade,
	unique key (project_id, display_index)
)

create table Item_Labels (
	item_id int unsigned not null,
	label_id int unsigned not null,
	primary key (item_id, label_id),
	foreign key (item_id) references Items(id) on update cascade on delete cascade,
	foreign key (label_id) references Labels(id) on update cascade on delete cascade
)

create table Item_Notes (
	id int unsigned not null unique auto_increment,
	item_id int unsigned not null,
	content text,
	display_index int unsigned not null,
	primary key (id),
	foreign key (item_id) references Items(id) on update cascade on delete cascade,
	unique key (item_id, display_index)
)

create table Item_Checklists (
	id int unsigned not null unique auto_increment,
	item_id int unsigned not null,
	name varchar(50) not null,
	display_index int unsigned not null,
	primary key (id),
	foreign key (item_id) references Items(id) on update cascade on delete cascade,
	unique key (item_id, display_index)
)

create table Item_Checklist_Items (
	id int unsigned not null unique auto_increment,
	item_checklist_id int unsigned not null,
	content text,
	completed enum('n', 'y') default 'n' not null,
	display_index int unsigned not null,
	primary key (id),
	foreign key (item_checklist_id) references Item_Checklists(id) on update cascade on delete cascade,
	unique key (item_id, display_index)
)