DROP TABLE IF EXISTS `#__st_manager_profiles`;
DROP TABLE IF EXISTS `#__st_manager_projects`;
DROP TABLE IF EXISTS `#__st_manager_lock_keys`;
DROP TABLE IF EXISTS `#__st_manager_errors`;
DROP TABLE IF EXISTS `#__st_manager_events`;

CREATE TABLE `#__st_manager_profiles` (
    `id`                int(11)         NOT NULL    auto_increment comment 'Profile ID',
    `vendor`            varchar(255)    NULL        comment 'Vendor',
    `vendor_name`       varchar(255)    NULL        comment 'Vendor Name',
    `stages`            varchar(255)    NOT NULL    comment 'Stages',
    `source_lang`       TEXT            NOT NULL    comment 'Source Language',
    `target_lang`       TEXT            NOT NULL    comment 'Target Language',
    `name`              varchar(255)    NOT NULL    comment 'Profile Name',
    `component`         varchar(255)    NOT NULL    comment 'Component Name',
    `project_guid`      varchar(255)    NULL        comment 'Project id to send in',
    `created_at`        timestamp default current_timestamp() NOT NULL comment 'Created At',
    `updated_at`        timestamp default current_timestamp() NOT NULL ON UPDATE current_timestamp() comment 'Updated At',

    PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE `#__st_manager_projects` (
    `id`          int(11)           NOT NULL     auto_increment comment 'Project ID',
    `entity_id`   int unsigned      NOT NULL     comment 'Entity ID',
    `profile_id`  int unsigned      NOT NULL     comment 'Profile ID',
    `status`      varchar(50)       NOT NULL     comment 'Document status',
    `document_id` varchar(255)      NOT NULL     comment 'Smartcat Document ID',
    `task_id`     varchar(255)      NULL         comment 'Export Task ID',
    `target_lang` varchar(50)       NOT NULL     comment 'Document target language',
    `created_at`  timestamp default current_timestamp() NOT NULL comment 'Created At',
    `updated_at`  timestamp default current_timestamp() NOT NULL ON UPDATE current_timestamp() comment 'Updated At',

    PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE `#__st_manager_lock_keys` (
    `key_id`         varchar(64)  NOT NULL primary key,
    `key_token`      varchar(44)  NOT NULL,
    `key_expiration` int unsigned NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;