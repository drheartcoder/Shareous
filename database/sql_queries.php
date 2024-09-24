27-02-2018

ALTER TABLE `user_bank_account` CHANGE `id` `id` INT(50) NOT NULL AUTO_INCREMENT, CHANGE `account_type` `account_type` ENUM('1','2','3','4','5') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1' COMMENT '1=saving 2=current 3=recurring 4=demat 5=nri', CHANGE `updated_at` `updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00';

28-02-2018
ALTER TABLE `user_bank_account` ADD `selected` ENUM('0','1') NOT NULL DEFAULT '0' AFTER `account_type`;



----------01-Mar-2018 by Sagar Sainkar for mailchimp api ------------

ALTER TABLE `api_details` ADD `mailchimp_api_key` VARCHAR(200) NOT NULL AFTER `onesignal_api_mode`, ADD `mailchimp_list_id` VARCHAR(100) NOT NULL AFTER `mailchimp_api_key`;


ALTER TABLE `api_details` CHANGE `payment_api` `payment_api` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `payment_secret` `payment_secret` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `payment_mode` `payment_mode` ENUM('1','0') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT '1= live mode, 0=sandbox mode', CHANGE `onesignal_api_key` `onesignal_api_key` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `onesignal_app_id` `onesignal_app_id` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `onesignal_api_mode` `onesignal_api_mode` ENUM('1','0') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT '1= live mode, 0=sandbox mode', CHANGE `mailchimp_api_key` `mailchimp_api_key` VARCHAR(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `mailchimp_list_id` `mailchimp_list_id` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `api_details` CHANGE `payment_mode` `payment_mode` ENUM('1','2') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '2' COMMENT '1= live mode, 2=sandbox mode', CHANGE `onesignal_api_mode` `onesignal_api_mode` ENUM('1','2') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '2' COMMENT '1= live mode, 2=sandbox mode';