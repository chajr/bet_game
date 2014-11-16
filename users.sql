CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` enum('1','0') NOT NULL,
  `remember_token` varchar(100) NOT NULL,
  `log` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin2 AUTO_INCREMENT=2 ;

INSERT INTO `users`
(`user_id`, `email`, `password`, `name`, `last_name`, `age`, `gender`, `remember_token`, `log`, `updated_at`, `created_at`)
VALUES
(1, 'user1@bluetree.pl', '$2y$10$1IxvACswioWe0TIWmqhTO.jpkPJGYmK/uoGBJvTGSXfgI7yLKi1J6', 'Jan', 'Kowalski', 22, '1', 'bQeaTlAx1pC4l49EXnn8herFeHxqLbgaohwmufRaPt4kquECCdaRg6w80V8X', 0, '0000-00-00 00:00:00', CURRENT_TIMESTAMP);
