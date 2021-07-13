/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : ventas

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2021-07-13 12:00:20
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for cliente
-- ----------------------------
DROP TABLE IF EXISTS `cliente`;
CREATE TABLE `cliente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente` varchar(100) DEFAULT NULL,
  `telefono` varchar(10) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `estado` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cliente
-- ----------------------------
INSERT INTO `cliente` VALUES ('1', 'roberto santana', '3007912525', 'frutas@ql.com', '0');
INSERT INTO `cliente` VALUES ('2', 'roberto santana', '2500', 'frutas', '1');

-- ----------------------------
-- Table structure for detalle_factura
-- ----------------------------
DROP TABLE IF EXISTS `detalle_factura`;
CREATE TABLE `detalle_factura` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura_id` int(11) DEFAULT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `valor` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_producto` (`producto_id`),
  KEY `fk_factura` (`factura_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of detalle_factura
-- ----------------------------
INSERT INTO `detalle_factura` VALUES ('1', '1', '1', '2', '3222');
INSERT INTO `detalle_factura` VALUES ('2', '1', '2', '1', '1200');
INSERT INTO `detalle_factura` VALUES ('3', '11', '2', '2', '3000');
INSERT INTO `detalle_factura` VALUES ('4', '11', '1', '1', '120');
INSERT INTO `detalle_factura` VALUES ('5', '12', '2', '2', '3000');
INSERT INTO `detalle_factura` VALUES ('6', '12', '1', '1', '1320');

-- ----------------------------
-- Table structure for factura
-- ----------------------------
DROP TABLE IF EXISTS `factura`;
CREATE TABLE `factura` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `valor_total` double DEFAULT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cliente` (`cliente_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of factura
-- ----------------------------
INSERT INTO `factura` VALUES ('1', '2555', '1');
INSERT INTO `factura` VALUES ('11', '120', '1');
INSERT INTO `factura` VALUES ('12', '1320', '1');

-- ----------------------------
-- Table structure for producto
-- ----------------------------
DROP TABLE IF EXISTS `producto`;
CREATE TABLE `producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producto` varchar(100) DEFAULT NULL,
  `precio` double DEFAULT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `foto` varchar(200) DEFAULT NULL,
  `iva` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT '1' COMMENT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of producto
-- ----------------------------
INSERT INTO `producto` VALUES ('2', 'manzana', '1500', 'futas', '../images/5def8e7f7a46a8c3ee218e470198b5fb', '0', '6', '1');
INSERT INTO `producto` VALUES ('4', 'sandia', '2500', 'frutas', '../images/5def8e7f7a46a8c3ee218e470198b5fb', '10', '5', '1');
INSERT INTO `producto` VALUES ('5', 'salchichon', '2500', 'frias', '../images/3db86c98f9d27fcbec7540973f893c3f', '10', '5', '1');
INSERT INTO `producto` VALUES ('6', 'salchichon ranchero', '2500', 'frias', '../images/3db86c98f9d27fcbec7540973f893c3f', '10', '5', '1');
