CREATE TABLE `wallets` (
  `wallet_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `currency` enum('1','0') NOT NULL,
  `init_val` double NOT NULL,
  `value` double NOT NULL DEFAULT '0',
  `status` enum('1','0') NOT NULL,
  `origin` enum('1','0') NOT NULL,
  `bonus_id` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`wallet_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 AUTO_INCREMENT=1 ;

INSERT INTO `wallets`
(`wallet_id`, `user_id`, `currency`, `init_val`, `value`, `status`, `origin`, `bonus_id`, `updated_at`, `created_at`)
VALUES
(NULL, '1', '1', '0', '0', '1', '1', NULL, CURRENT_TIMESTAMP, '0000-00-00 00:00:00.000000');