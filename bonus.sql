CREATE TABLE `bonus` (
  `bonus_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `trigger` enum('login','deposite') NOT NULL,
  `value` int(11) NOT NULL,
  `value_type` enum('fixed','percent') NOT NULL,
  `multiplier` int(11) NOT NULL,
  `status` enum('0','1','2') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`bonus_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 AUTO_INCREMENT=1 ;

INSERT INTO `bonus`
(`bonus_id`, `name`, `trigger`, `value`, `value_type`, `multiplier`, `status`, `created_at`, `updated_at`)
VALUES
(NULL, 'Login bonus', 'login', '10', 'fixed', '0', '1', CURRENT_TIMESTAMP, '0000-00-00 00:00:00.000000'),
(NULL, 'Second login bonus', 'login', '5', 'fixed', '5', '1', CURRENT_TIMESTAMP, '0000-00-00 00:00:00.000000'),
(NULL, '10 login bonus', 'login', '10', 'fixed', '10', '1', CURRENT_TIMESTAMP, '0000-00-00 00:00:00.000000'),
(NULL, '20 login bonus', 'login', '10', 'fixed', '20', '1', CURRENT_TIMESTAMP, '0000-00-00 00:00:00.000000'),
(NULL, '50$ deposit bonus', 'deposite', '5', 'percent', '0', '1', CURRENT_TIMESTAMP, '0000-00-00 00:00:00.000000'),
(NULL, '100$ deposit bonus', 'deposite', '10', 'percent', '0', '1', CURRENT_TIMESTAMP, '0000-00-00 00:00:00.000000');