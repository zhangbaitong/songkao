USE songkao;
DROP TABLE IF EXISTS `users`;
create table t_user(
 id int primary key auto_increment,
 openid varchar(64) NOT NULL UNIQUE,
 join_time datetime,
 order_num int,
 r_name varchar(64)
);