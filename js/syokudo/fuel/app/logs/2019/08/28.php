<?php defined('COREPATH') or exit('No direct script access allowed'); ?>

WARNING - 2019-08-28 10:44:18 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2019-08-28 10:44:19 --> 42000 - SQLSTATE[42000]: Syntax error or access violation: 1066 Not unique table/alias: 'picture' with query: "SELECT node.nid,nd.uid,nd.title,body.body_value AS body,image.field_image_target_id AS image, user_name.field_user_name_value AS user_name, user_site_url.field_user_site_url_value AS user_site_url, picture.user_picture_target_id  AS picture FROM `node`INNER JOIN node_field_data nd ON node.nid = nd.nid INNER JOIN node__field_image image ON node.nid = image.entity_id INNER JOIN node__body body ON node.nid = body.entity_id INNER JOIN user__field_user_name user_name ON nd.uid = user_name.entity_id INNER JOIN user__field_user_site_url user_site_url ON nd.uid = user_site_url.entity_id INNER JOIN user__user_picture picture ON nd.uid = picture.entity_id INNER JOIN paragraphs_item_field_data picture ON node.nid = paragraphs_item_field_data.parent_id ORDER BY node.nid ASC" in /home/saiyuuki-syokudo/www/syokudo/fuel/core/classes/database/pdo/connection.php on line 253
WARNING - 2019-08-28 13:57:33 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2019-08-28 13:57:34 --> 42000 - SQLSTATE[42000]: Syntax error or access violation: 1066 Not unique table/alias: 'picture' with query: "SELECT node.nid,nd.uid,nd.title,body.body_value AS body,image.field_image_target_id AS image, user_name.field_user_name_value AS user_name, user_site_url.field_user_site_url_value AS user_site_url, picture.user_picture_target_id  AS picture FROM `node`INNER JOIN node_field_data nd ON node.nid = nd.nid INNER JOIN node__field_image image ON node.nid = image.entity_id INNER JOIN node__body body ON node.nid = body.entity_id INNER JOIN user__field_user_name user_name ON nd.uid = user_name.entity_id INNER JOIN user__field_user_site_url user_site_url ON nd.uid = user_site_url.entity_id INNER JOIN user__user_picture picture ON nd.uid = picture.entity_id INNER JOIN paragraphs_item_field_data picture ON node.nid = paragraphs_item_field_data.parent_id ORDER BY node.nid ASC" in /home/saiyuuki-syokudo/www/syokudo/fuel/core/classes/database/pdo/connection.php on line 253
WARNING - 2019-08-28 14:20:14 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2019-08-28 14:20:14 --> 42000 - SQLSTATE[42000]: Syntax error or access violation: 1066 Not unique table/alias: 'picture' with query: "SELECT node.nid,nd.uid,nd.title,body.body_value AS body,image.field_image_target_id AS image, user_name.field_user_name_value AS user_name, user_site_url.field_user_site_url_value AS user_site_url, picture.user_picture_target_id  AS picture FROM `node`INNER JOIN node_field_data nd ON node.nid = nd.nid INNER JOIN node__field_image image ON node.nid = image.entity_id INNER JOIN node__body body ON node.nid = body.entity_id INNER JOIN user__field_user_name user_name ON nd.uid = user_name.entity_id INNER JOIN user__field_user_site_url user_site_url ON nd.uid = user_site_url.entity_id INNER JOIN user__user_picture picture ON nd.uid = picture.entity_id INNER JOIN paragraphs_item_field_data picture ON node.nid = paragraphs_item_field_data.parent_id ORDER BY node.nid ASC" in /home/saiyuuki-syokudo/www/syokudo/fuel/core/classes/database/pdo/connection.php on line 253