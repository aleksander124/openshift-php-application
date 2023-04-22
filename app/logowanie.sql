CREATE DATABASE IF NOT EXISTS `logowanie`;
USE `logowanie`;

CREATE TABLE IF NOT EXISTS `uzytkownicy` (
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `user` varchar(255) NOT NULL,
    `pass` varchar(255) NOT NULL,
    `email` varchar(20),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

INSERT INTO `uzytkownicy` (`id`, `user`, `pass`, `email`) 
    VALUES (1, 'admin', 'admin', 'admin@admin.pl'),
        (2, 'olek', 'admin', 'olek@admin.pl'),
        (3, 'filip', 'admin', 'filip@admin.pl'),
        (4, 'marcin', 'admin', 'marcin@admin.pl'),
        (5, 'guest', '1234', 'guest@guest.pl');

-- CREATE USER 'Filip'@'%' IDENTIFIED BY 'Qwerty@1234';
-- CREATE USER 'Marcin'@'%' IDENTIFIED BY 'Qwerty@1234';
-- GRANT ALL PRIVILEGES ON * . * TO 'Filip'@'%';
-- GRANT ALL PRIVILEGES ON * . * TO 'Marcin'@'%';
-- FLUSH PRIVILEGES;

