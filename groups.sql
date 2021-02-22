INSERT INTO auth_groups (name)
VALUES ('admins'), ('lecturers'), ('students'), ('users');

INSERT INTO auth_groups (name, description)
VALUES ('admins', ''), ('lecturers', ''), ('students', ''), ('users', '');

UPDATE highschool.auth_groups_users
SET group_id = 1
WHERE user_id = 1; 


INSERT INTO `classes` (`id`, `name`, `internal_id`, `begin`, `end`, `enrolled_students`) VALUES (NULL, 'Fachinformatiker AE Winter 2019', 'faaewi2019', '2019-02-04 13:42:30', '2021-04-05 13:42:30', '12');


INSERT INTO highschool.auth_groups (name, despription)
VALUES ('admins', ''), ('lecturers', ''), ('students', ''), ('users', '');

INSERT INTO `auth_groups` (`id`, `name`, `description`) VALUES (NULL, 'admins', ''), (NULL, 'lecturers', ''), (NULL, 'students', ''), (NULL, 'users', '');