<?php
/**
 * Created by PhpStorm.
 * User: Nasolo RANDIANINA
 * Date: 27/02/2018
 * Time: 16:10
 */
namespace AppBundle\Entity\Constants;

final class Constant
{
    const LOCKED = 1;
    const NOT_LOCKED = 0;

    const NOT_SHARED = 0;
    const SHARED = 1;

    const CRYPTED = 1;
    const NOT_CRYPTED = 0;
    const FOLDER_STATUS_CREATED = 1;
    const FOLDER_STATUS_DELETED = 10;

    const FILE_STATUS_DELETED = 10;
    const STATUS_REPLACED = 2;
    const STATUS_CREATED = 1;

    const FOLDER_LOG_ACTION_CREATE = 1;
    const FOLDER_LOG_ACTION_DOWNLOAD = 2;
    const FOLDER_LOG_ACTION_DELETE = 3;
    const FOLDER_LOG_ACTION_MOVE = 4;
    const FOLDER_LOG_ACTION_RENAME = 5;
    const FOLDER_LOG_ACTION_IN_TRASH = 6;
    const FOLDER_LOG_ACTION_OUT_TRASH = 7;
    const FOLDER_LOG_ACTION_SHARE = 8;
    const FOLDER_LOG_ACTION_NOT_SHARE = 9;
    const FOLDER_LOG_ACTION_SHARE_PASSWORD = 10;
    const FOLDER_LOG_ACTION_SHARE_NOT_PASSWORD = 11;
    const FOLDER_LOG_ACTION_CRYPT = 12;
    const FOLDER_LOG_ACTION_NOT_CRYPT = 13;
    const FOLDER_LOG_ACTION_CRYPT_PASSWORD = 14;
    const FOLDER_LOG_ACTION_NOT_CRYPT_PASSWORD = 15;
    const FOLDER_LOG_ACTION_LOCKED = 16;
    const FOLDER_LOG_ACTION_NOT_UNLOCKED = 17;
    const FOLDER_LOG_ACTION_CHANGE_OWNER = 18;
    const FOLDER_LOG_ACTION_COPY = 19;


    const FILE_LOG_ACTION_ADD = 1;
    const FILE_LOG_ACTION_DOWNLOAD = 2;
    const FILE_LOG_ACTION_DELETE = 3;
    const FILE_LOG_ACTION_MOVE = 4;
    const FILE_LOG_ACTION_RENAME = 5;
    const FILE_LOG_ACTION_IN_TRASH = 6;
    const FILE_LOG_ACTION_OUT_TRASH = 7;
    const FILE_LOG_ACTION_SHARE = 8;
    const FILE_LOG_ACTION_NOT_SHARE = 9;
    const FILE_LOG_ACTION_SHARE_PASSWORD = 10;
    const FILE_LOG_ACTION_SHARE_NOT_PASSWORD = 11;
    const FILE_LOG_ACTION_VIEW_FILE = 12;
    const FILE_LOG_ACTION_CHANGE_OWNER = 13;
    const FILE_LOG_ACTION_COPY = 14;

    const LOG_TYPE_USER = 1;
    const LOG_TYPE_CONTACT = 2;
    const LOG_TYPE_SENDING = 3;
    const LOG_TYPE_FILE = 4;
    const LOG_TYPE_FOLDER = 5;
    const LOG_TYPE_PROJECT = 6;

    const LOG_ACTION_USER_ADD = 4;
    const LOG_ACTION_USER_EDIT = 5;
    const LOG_ACTION_USER_DELETE = 6;
    const LOG_ACTION_SENDING_ADD = 7;
    const LOG_ACTION_USER_LOGIN = 10;

    const RIGHT_MANAGER = 1;
    const RIGHT_CONTRIBUTOR = 2;
    const RIGHT_READER = 3;
    const RIGHT_OWNER = 4;

    const USER_DELETED = 1;
    const USER_NOT_DELETED = 0;

    // Email
    const CREATE_USER = 1;
    const SEND_INVITATION = 2;
    const MAIL_COMMENT_NOTIFICATION = 3;
    const SEND_CODE_CRYPT = 4;
    const SEND_SMS = 6;

    const NEWS_TYPE_COMMENT = 1;
    const NEWS_TYPE_ADD_USER = 2;
    const NEWS_TYPE_UPLOAD_FILE = 3;
    const NEWS_TYPE_CREATE_FOLDER = 4;
    const NEWS_TYPE_CREATE_PROJECT = 5;
    const NEWS_TYPE_PUT_FORWARD_COMMENT = 6;

    const SIZE_UNIT_KO = 'Ko';
    const SIZE_UNIT_MO = 'Mo';
    const SIZE_UNIT_GO = 'Go';


    public static $declenchement =
        [
            self::CREATE_USER => 'mail.create_user',
            self::SEND_INVITATION => 'mail.send_invitation',
            self::SEND_CODE_CRYPT => 'mail.send_url_crypt',
            self::MAIL_COMMENT_NOTIFICATION => 'mail.comment.notification',
            self::SEND_SMS => 'mail.send_keyby_sms'
        ];
}
