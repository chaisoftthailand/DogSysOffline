/*
 Navicat Premium Data Transfer

 Source Server         : POP
 Source Server Type    : MySQL
 Source Server Version : 50717 (5.7.17-log)
 Source Host           : localhost:3306
 Source Schema         : DogSys

 Target Server Type    : MySQL
 Target Server Version : 50717 (5.7.17-log)
 File Encoding         : 65001

 Date: 11/07/2025 09:07:25
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for appointments
-- ----------------------------
DROP TABLE IF EXISTS `appointments`;
CREATE TABLE `appointments`  (
  `appointment_id` int(11) NOT NULL AUTO_INCREMENT,
  `clinic_id` int(11) NOT NULL COMMENT 'อ้างถึง clinic_id จาก dogs',
  `dog_id` int(11) NOT NULL COMMENT 'อ้างถึง dog_id จาก dogs',
  `appointment_date` datetime NOT NULL COMMENT 'วันและเวลานัด',
  `description` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'เหตุผลที่นัด',
  `status` enum('รอพบแพทย์','เสร็จสิ้น','ยกเลิก') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'รอพบแพทย์',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`appointment_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of appointments
-- ----------------------------
INSERT INTO `appointments` VALUES (1, 1, 34, '2025-04-18 00:18:00', 'Off Off  Off  ', 'รอพบแพทย์', '2025-04-17 23:14:17');
INSERT INTO `appointments` VALUES (4, 1, 56, '2025-06-06 16:48:00', 'Online Online Online ', 'รอพบแพทย์', '2025-06-06 16:47:41');

-- ----------------------------
-- Table structure for clinics
-- ----------------------------
DROP TABLE IF EXISTS `clinics`;
CREATE TABLE `clinics`  (
  `clinic_id` int(11) NOT NULL AUTO_INCREMENT,
  `clinic_name` varchar(255) CHARACTER SET tis620 COLLATE tis620_thai_ci NOT NULL,
  `address` text CHARACTER SET tis620 COLLATE tis620_thai_ci NULL,
  `phone` varchar(20) CHARACTER SET tis620 COLLATE tis620_thai_ci NULL DEFAULT NULL,
  `email` varchar(100) CHARACTER SET tis620 COLLATE tis620_thai_ci NULL DEFAULT NULL,
  `owner_name` varchar(250) CHARACTER SET tis620 COLLATE tis620_thai_ci NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`clinic_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4 CHARACTER SET = tis620 COLLATE = tis620_thai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of clinics
-- ----------------------------
INSERT INTO `clinics` VALUES (1, 'หมอบุญ สาขา 2', '95/36 หมู่บ้านอู่ทองเพลส 1 ต.คูคต อ', '0809182280', 'chaisofts@gmail.com', 'นายทองดี มีสุข', '2025-04-17 07:55:48');
INSERT INTO `clinics` VALUES (2, 'หมอบุญ สาขา 1', '100/36 หมู่บ้านอู่ทองเพลส 11 ต.คูคต อ', '809182280', 'chaisoftthailand@gmail.com', 'นายทองดี มีสุข', '2025-04-17 07:57:36');
INSERT INTO `clinics` VALUES (3, 'หมอบุญ สาขา 3', '100/36 หมู่บ้านอู่ทองเพลส 11 ต.คูคต อ', '0809182280', 'chaisoftthailand@gmail.com', 'นายทองดี มีสุข', '2025-04-17 07:57:46');

-- ----------------------------
-- Table structure for dogs
-- ----------------------------
DROP TABLE IF EXISTS `dogs`;
CREATE TABLE `dogs`  (
  `user_id` int(11) NOT NULL COMMENT 'เจ้าของข้อมูลสุนัข',
  `clinic_id` int(11) NOT NULL COMMENT 'เจ้าของข้อมูลสุนัข',
  `dog_id` int(11) NOT NULL AUTO_INCREMENT,
  `dog_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'ชื่อ',
  `dog_breed` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'สายพันธุ์',
  `dog_age` int(3) NULL DEFAULT NULL COMMENT 'อายุ',
  `dog_weight` int(3) NULL DEFAULT NULL COMMENT 'น้ำหนัก',
  `dog_gender` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'เพศ',
  `dog_medical_history` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'ประวัติการรักษา',
  `created_at` datetime NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'วันที่ทำข้อมูล',
  `dog_image_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `xray_image_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`dog_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 61 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of dogs
-- ----------------------------
INSERT INTO `dogs` VALUES (0, 1, 34, 'แก้วตา', 'บางแก้ว', 4, 12, 'ตัวผู้', 'มีน้ำไหลออกที่ปากเป็นสีแดง และ เหลือง', '2025-04-14 05:50:20', 'uploads/2.jpg', 'uploads/100088.jpg');
INSERT INTO `dogs` VALUES (13, 1, 56, 'คุณพี่เจมส์', 'ขี้สุ-00', 10, 1, 'ตัวผู้', 'off off', '2025-06-06 05:47:27', 'uploads/01.png', '');
INSERT INTO `dogs` VALUES (0, 1, 29, 'แก้วใจ', 'บางแก้ว', 5, 5, 'ตัวผู้', 'ต่อมา สุนัขพันธุ์บางแก้ว จึงนำมาเลี้ยงทั่วไปตามบ้าน เนื่องจากมีลักษณะเฉพาะ สวยงาม เลี้ยงง่าย ฝึกง่าย จึงทำให้ สุนัข บางแก้ว นี้กลายเป็นที่นิยมเลี้ยงกันมากใน จ.พิษณุโลก ตลอดจนถึงปัจจุบัน ซึ่งปฏิเสธไม่ได้ว่า สุนัขพันธุ์บางแก้ว เป็นสัญลักษณ์ที่สำคัญอีกอย่างหนึ่งของจังหวัดพิษณุโลก', '2025-04-14 05:43:43', 'uploads/1.jpg', '');

-- ----------------------------
-- Table structure for treatments
-- ----------------------------
DROP TABLE IF EXISTS `treatments`;
CREATE TABLE `treatments`  (
  `treatment_id` int(11) NOT NULL AUTO_INCREMENT,
  `clinic_id` int(11) NOT NULL COMMENT 'รหัสคลินิก',
  `dog_id` int(11) NOT NULL COMMENT 'รหัสสุนัข',
  `treatment_date` date NOT NULL COMMENT 'วันที่รักษา',
  `symptoms` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'อาการที่พบ',
  `diagnosis` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'การวินิจฉัย',
  `treatment` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'รายละเอียดการรักษา',
  `medication` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'ยา/เวชภัณฑ์ที่ใช้',
  `doctor_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'ชื่อสัตวแพทย์ผู้รักษา',
  `user_id` int(11) NOT NULL COMMENT 'รหัสสุนัข',
  `next_appointment` date NULL DEFAULT NULL COMMENT 'วันนัดถัดไป',
  `created_at` datetime NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`treatment_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of treatments
-- ----------------------------
INSERT INTO `treatments` VALUES (1, 1, 34, '2025-04-18', 'น้องหมากินน้ำมากกว่าปกติ ', 'Off Off Off', 'Off Off Off', 'Off Off Off', 'หมอบุญ ใจดี', 14, '2025-04-19', '2025-04-18 00:00:00');
INSERT INTO `treatments` VALUES (2, 1, 34, '2025-05-06', 'หมาหายใจเร็วผิดปกติ', 'Off Off', 'Off Off', 'Off Off', 'หมอบุญ', 0, '2025-05-07', '2025-05-06 00:00:00');
INSERT INTO `treatments` VALUES (3, 1, 34, '2025-05-06', 'ถ่ายเป็นน้ำ ถ่ายบ่อยมากกว่า 1-2 ครั้งต่อวัน หรือท้องเสียเป็นมูกเลือด', '?', '?', '?', 'หมอบุญ', 0, '2025-05-06', '2025-05-06 00:00:00');
INSERT INTO `treatments` VALUES (4, 1, 34, '2025-05-07', 'น้องหมาไม่สบาย หมาไม่ยอมกินข้าว หมามีอาการซึมเศร้า', '?', '?', '?', 'หมอบุญ', 0, NULL, '2025-05-07 00:00:00');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `fullname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `clinic_id` int(11) NOT NULL COMMENT 'รหัสคลินิกที่ผู้ใช้นี้เป็นเจ้าของหรืออยู่ในคลินิกนี้',
  `role` int(11) NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 57 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (0, 'user01_Online', 'Q0RIRzNIekI2UnkyTjJaNkVEaTl6UT09', 'วินัย ทองดี', 1, 1, 'chaisofts@gmail.com', '2025-04-13 16:46:42');
INSERT INTO `user` VALUES (13, 'admin', 'Q0RIRzNIekI2UnkyTjJaNkVEaTl6UT09', 'นายหมอบุญ ใจดี', 1, 3, 'chaisofts@gmail.com', '2025-04-17 08:11:19');
INSERT INTO `user` VALUES (14, 'clinic01_Online', 'Q0RIRzNIekI2UnkyTjJaNkVEaTl6UT09', 'วิทยา รักสัตว์', 1, 2, 'chaisoftsnet@gmail.com', '2025-04-17 08:11:59');
INSERT INTO `user` VALUES (56, 'admin01_Off_Line', 'NURHYW5QajZWUWFMTEY1NkxITHNjQT09', 'นายหมอบุญ ใจดี', 1, 0, 'chaisofts@gmail.com', '2025-06-06 03:57:49');
INSERT INTO `user` VALUES (54, 'admin02_Online', 'Q0RIRzNIekI2UnkyTjJaNkVEaTl6UT09', 'วินัย ทองดี', 1, 3, 'chaisofts@gmail.com', '2025-05-30 10:57:00');

SET FOREIGN_KEY_CHECKS = 1;
