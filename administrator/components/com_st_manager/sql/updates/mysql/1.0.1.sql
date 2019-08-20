CREATE TABLE `#__st_manager_errors` (
    `id`            int(11)         NOT NULL     auto_increment comment 'Error ID',
    `type`          varchar(50)     NOT NULL     comment 'Error type',
    `short_message` varchar(255)    NOT NULL     comment 'Error short message',
    `message`       text            NOT NULL     comment 'Error full message',
    `created_at`    timestamp default current_timestamp() NOT NULL comment 'Created At',
    `updated_at`    timestamp default current_timestamp() NOT NULL ON UPDATE current_timestamp() comment 'Updated At',

    PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE `#__st_manager_events` (
    `id`            int(11)         NOT NULL     auto_increment comment 'Events ID',
    `type`          varchar(50)     NOT NULL     comment 'Events type',
    `message`       text            NOT NULL     comment 'Events message',
    `created_at`    timestamp default current_timestamp() NOT NULL comment 'Created At',
    `updated_at`    timestamp default current_timestamp() NOT NULL ON UPDATE current_timestamp() comment 'Updated At',

    PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;