<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

class messages extends db_connect
{

	private $requestFrom = 0;
    private $language = 'en';

	public function __construct($dbo = NULL)
    {
		parent::__construct($dbo);
	}

    public function activeChatsCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM chats WHERE removeAt = 0");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function myActiveChatsCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM chats WHERE (fromUserId = (:userId) OR toUserId = (:userId)) AND removeAt = 0");
        $stmt->bindParam(":userId", $this->requestFrom, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function messagesCountByChat($chatId)
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM messages WHERE chatId = (:chatId) AND removeAt = 0");
        $stmt->bindParam(":chatId", $chatId, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getMessagesCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM messages WHERE removeAt = 0");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getMaxChatId()
    {
        $stmt = $this->db->prepare("SELECT MAX(id) FROM chats");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getMaxMessageId()
    {
        $stmt = $this->db->prepare("SELECT MAX(id) FROM messages");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function createChat($fromUserId, $toUserId) {

        $chatId = 0;

        $currentTime = time();

        $stmt = $this->db->prepare("INSERT INTO chats (fromUserId, toUserId, fromUserId_lastView, createAt) value (:fromUserId, :toUserId, :fromUserId_lastView, :createAt)");
        $stmt->bindParam(":fromUserId", $fromUserId, PDO::PARAM_INT);
        $stmt->bindParam(":toUserId", $toUserId, PDO::PARAM_INT);
        $stmt->bindParam(":fromUserId_lastView", $currentTime, PDO::PARAM_INT);
        $stmt->bindParam(":createAt", $currentTime, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $chatId = $this->db->lastInsertId();
        }

        return $chatId;
    }

    public function setChatLastView_FromId($chatId) {

        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $currentTime = time();

        $stmt = $this->db->prepare("UPDATE chats SET fromUserId_lastView = (:fromUserId_lastView) WHERE id = (:chatId)");
        $stmt->bindParam(":chatId", $chatId, PDO::PARAM_INT);
        $stmt->bindParam(":fromUserId_lastView", $currentTime, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS);
        }

        return $result;
    }

    public function setChatLastView_ToId($chatId) {

        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $currentTime = time();

        $stmt = $this->db->prepare("UPDATE chats SET toUserId_lastView = (:toUserId_lastView) WHERE id = (:chatId)");
        $stmt->bindParam(":chatId", $chatId, PDO::PARAM_INT);
        $stmt->bindParam(":toUserId_lastView", $currentTime, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS);
        }

        return $result;
    }

    public function getChatId($fromUserId, $toUserId) {

        $chatId = 0;

        $stmt = $this->db->prepare("SELECT id FROM chats WHERE removeAt = 0 AND ((fromUserId = (:fromUserId) AND toUserId = (:toUserId)) OR (fromUserId = (:toUserId) AND toUserId = (:fromUserId))) LIMIT 1");
        $stmt->bindParam(":fromUserId", $fromUserId, PDO::PARAM_INT);
        $stmt->bindParam(":toUserId", $toUserId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                $chatId = $row['id'];
            }
        }

        return $chatId;
    }

    public function create($toUserId, $chatId,  $message = "", $imgUrl = "")
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        if (strlen($imgUrl) == 0 && strlen($message) == 0) {

            return $result;
        }

        if ($chatId == 0) {

            $chatId = $this->getChatId($this->getRequestFrom(), $toUserId);

            if ($chatId == 0) {

                $chatId = $this->createChat($this->getRequestFrom(), $toUserId);
            }
        }

        $currentTime = time();
        $ip_addr = helper::ip_addr();
        $u_agent = helper::u_agent();

        $stmt = $this->db->prepare("INSERT INTO messages (chatId, fromUserId, toUserId, message, imgUrl, createAt, ip_addr, u_agent) value (:chatId, :fromUserId, :toUserId, :message, :imgUrl, :createAt, :ip_addr, :u_agent)");
        $stmt->bindParam(":chatId", $chatId, PDO::PARAM_INT);
        $stmt->bindParam(":fromUserId", $this->requestFrom, PDO::PARAM_INT);
        $stmt->bindParam(":toUserId", $toUserId, PDO::PARAM_INT);
        $stmt->bindParam(":message", $message, PDO::PARAM_STR);
        $stmt->bindParam(":imgUrl", $imgUrl, PDO::PARAM_STR);
        $stmt->bindParam(":createAt", $currentTime, PDO::PARAM_INT);
        $stmt->bindParam(":ip_addr", $ip_addr, PDO::PARAM_STR);
        $stmt->bindParam(":u_agent", $u_agent, PDO::PARAM_STR);

        if ($stmt->execute()) {

            $msgId = $this->db->lastInsertId();

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS,
                            "chatId" => $chatId,
                            "msgId" => $msgId,
                            "message" => $this->info($msgId));

            $fcm = new fcm($this->db);
            $fcm->setRequestFrom($this->getRequestFrom());
            $fcm->setRequestTo($toUserId);
            $fcm->setType(GCM_NOTIFY_MESSAGE);
            $fcm->setTitle("You have new message");
            $fcm->setItemId($chatId);
            $fcm->prepare();
            $fcm->send();
            unset($fcm);
        }

        return $result;
    }

    public function removeChat($chatId) {

        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $currentTime = time();

        $stmt = $this->db->prepare("UPDATE chats SET removeAt = (:removeAt) WHERE id = (:chatId)");
        $stmt->bindParam(":chatId", $chatId, PDO::PARAM_INT);
        $stmt->bindParam(":removeAt", $currentTime, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $stmt2 = $this->db->prepare("UPDATE messages SET removeAt = (:removeAt) WHERE chatId = (:chatId)");
            $stmt2->bindParam(":chatId", $chatId, PDO::PARAM_INT);
            $stmt2->bindParam(":removeAt", $currentTime, PDO::PARAM_INT);
            $stmt2->execute();

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS);
        }

        return $result;
    }


    public function remove($itemId)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $currentTime = time();

        $stmt = $this->db->prepare("UPDATE messages SET removeAt = (:removeAt) WHERE id = (:itemId)");
        $stmt->bindParam(":itemId", $itemId, PDO::PARAM_INT);
        $stmt->bindParam(":removeAt", $currentTime, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS);
        }

        return $result;
    }

    public function getLastMessageInChat($chatId) {

        $stmt = $this->db->prepare("SELECT id FROM messages WHERE chatId = (:chatId) AND removeAt = 0 ORDER BY id DESC LIMIT 1");
        $stmt->bindParam(':chatId', $chatId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $row = $stmt->fetch();

            return $row['id'];
        }

        return 0;
    }

    public function getNewMessagesInChat($chatId) {

        $chatInfo = $this->chatInfoShort($chatId);

        $profileId = $chatInfo['fromUserId'];

        if ($profileId == $this->getRequestFrom()) {

            $fromUserId_lastView = $chatInfo['fromUserId_lastView'];

            $stmt = $this->db->prepare("SELECT count(*) FROM messages WHERE chatId = (:chatId) AND fromUserId <> (:fromUserId) AND createAt > (:fromUserId_lastView) AND removeAt = 0 ORDER BY id DESC LIMIT 1");
            $stmt->bindParam(':chatId', $chatId, PDO::PARAM_INT);
            $stmt->bindParam(':fromUserId', $this->requestFrom, PDO::PARAM_INT);
            $stmt->bindParam(':fromUserId_lastView', $fromUserId_lastView, PDO::PARAM_INT);

        } else {

            $toUserId_lastView = $chatInfo['toUserId_lastView'];

            $stmt = $this->db->prepare("SELECT count(*) FROM messages WHERE chatId = (:chatId) AND fromUserId <> (:fromUserId) AND createAt > (:toUserId_lastView) AND removeAt = 0 ORDER BY id DESC LIMIT 1");
            $stmt->bindParam(':chatId', $chatId, PDO::PARAM_INT);
            $stmt->bindParam(':fromUserId', $this->requestFrom, PDO::PARAM_INT);
            $stmt->bindParam(':toUserId_lastView', $toUserId_lastView, PDO::PARAM_INT);
        }


        if ($stmt->execute()) {

            return $number_of_rows = $stmt->fetchColumn();
        }

        return 0;
    }

    public function chatInfo($chatId)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("SELECT * FROM chats WHERE id = (:chatId) LIMIT 1");
        $stmt->bindParam(":chatId", $chatId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                $time = new language($this->db, $this->language);

                $profileId = $row['fromUserId'];

                if ($profileId == $this->getRequestFrom()) {

                    $profileId = $row['toUserId'];
                }

                $profile = new profile($this->db, $profileId);
                $profileInfo = $profile->getVeryShort();
                unset($profile);

                $result = array("error" => false,
                                "error_code" => ERROR_SUCCESS,
                                "id" => $row['id'],
                                "fromUserId" => $row['fromUserId'],
                                "toUserId" => $row['toUserId'],
                                "fromUserId_lastView" => $row['fromUserId_lastView'],
                                "toUserId_lastView" => $row['toUserId_lastView'],
                                "withUserId" => $profileInfo['id'],
                                "fromGender" => $profileInfo['sex'],
                                "withUserVerify" => $profileInfo['verify'],
                                "fromUserStaff" => $profileInfo['staffaccount'],
                                "fromUserBot" => $profileInfo['botaccount'],
                                "withUserState" => $profileInfo['state'],
                                "withUserOnline" => $profileInfo['online'],
                                "withUserUsername" => $profileInfo['username'],
                                "withUserFullname" => $profileInfo['fullname'],
                                "withUserPhotoUrl" => $profileInfo['lowPhotoUrl'],
                                "lastMessage" => $row['message'],
                                "lastMessageAgo" => $time->timeAgo($row['createAt']),
                                "lastMessageCreateAt" => $row['createAt'],
                                "newMessagesCount" => $this->getNewMessagesInChat($chatId),
                                "createAt" => $row['createAt'],
                                "date" => date("Y-m-d H:i:s", $row['createAt']),
                                "timeAgo" => $time->timeAgo($row['createAt']),
                                "removeAt" => $row['removeAt']);

                unset($profileInfo);
            }
        }

        return $result;
    }

    public function chatInfoShort($chatId)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("SELECT * FROM chats WHERE id = (:chatId) LIMIT 1");
        $stmt->bindParam(":chatId", $chatId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                $result = array("error" => false,
                                "error_code" => ERROR_SUCCESS,
                                "id" => $row['id'],
                                "fromUserId" => $row['fromUserId'],
                                "toUserId" => $row['toUserId'],
                                "fromUserId_lastView" => $row['fromUserId_lastView'],
                                "toUserId_lastView" => $row['toUserId_lastView'],
                                "createAt" => $row['createAt'],
                                "removeAt" => $row['removeAt']);
            }
        }

        return $result;
    }

    public function info($msgId)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("SELECT * FROM messages WHERE id = (:msgId) LIMIT 1");
        $stmt->bindParam(":msgId", $msgId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                $time = new language($this->db, $this->language);

                $profile = new profile($this->db, $row['fromUserId']);
                $profileInfo = $profile->getVeryShort();
                unset($profile);

                $result = array("error" => false,
                                "error_code" => ERROR_SUCCESS,
                                "id" => $row['id'],
                                "fromUserId" => $row['fromUserId'],
                                "fromUserState" => $profileInfo['state'],
                                "fromUserUsername" => $profileInfo['username'],
                                "fromUserFullname" => $profileInfo['fullname'],
                                "fromUserPhotoUrl" => $profileInfo['lowPhotoUrl'],
                                "message" => htmlspecialchars_decode(stripslashes($row['message'])),
                                "imgUrl" => $row['imgUrl'],
                                "createAt" => $row['createAt'],
                                "seenAt" => $row['seenAt'],
                                "date" => date("Y-m-d H:i:s", $row['createAt']),
                                "timeAgo" => $time->timeAgo($row['createAt']),
                                "removeAt" => $row['removeAt']);
            }
        }

        return $result;
    }

    public function info2($row)
    {
        $time = new language($this->db, $this->language);

        $profile = new profile($this->db, $row['fromUserId']);
        $profileInfo = $profile->getVeryShort();
        unset($profile);

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "id" => $row['id'],
                        "fromUserId" => $row['fromUserId'],
                        "fromUserState" => $profileInfo['state'],
                        "fromUserUsername" => $profileInfo['username'],
                        "fromUserFullname" => $profileInfo['fullname'],
                        "fromUserPhotoUrl" => $profileInfo['lowPhotoUrl'],
                        "fromUserOnline" => $profileInfo['online'],
                        "message" => htmlspecialchars_decode(stripslashes($row['message'])),
                        "imgUrl" => $row['imgUrl'],
                        "stickerId" => $row['stickerId'],
                        "stickerImgUrl" => $row['stickerImgUrl'],
                        "createAt" => $row['createAt'],
                        "seenAt" => $row['seenAt'],
                        "date" => date("Y-m-d H:i:s", $row['createAt']),
                        "timeAgo" => $time->timeAgo($row['createAt']),
                        "removeAt" => $row['removeAt']);

        return $result;
    }

    public function getChats($messageCreateAt = 0)
    {
        if ($messageCreateAt == 0) {

            $messageCreateAt = time() + 10;
        }

        $chats = array("error" => false,
                       "error_code" => ERROR_SUCCESS,
                       "messageCreateAt" => $messageCreateAt,
                       "chats" => array());

        $stmt = $this->db->prepare("SELECT * FROM chats WHERE (fromUserId = (:userId) OR toUserId = (:userId)) AND messageCreateAt < (:messageCreateAt) AND removeAt = 0 ORDER BY messageCreateAt DESC LIMIT 20");
        $stmt->bindParam(':messageCreateAt', $messageCreateAt, PDO::PARAM_INT);
        $stmt->bindParam(':userId', $this->requestFrom, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $chatInfo = $this->chatInfo($row['id']);

                array_push($chats['chats'], $chatInfo);

                $chats['messageCreateAt'] = $row['messageCreateAt'];

                unset($chatInfo);
            }
        }

        return $chats;
    }

    public function getNewMessagesCount()
    {
        $count = 0;

        $stmt = $this->db->prepare("SELECT id FROM chats WHERE (fromUserId = (:userId) OR toUserId = (:userId)) AND removeAt = 0 ORDER BY id DESC");
        $stmt->bindParam(':userId', $this->requestFrom, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $chatInfo = $this->chatInfo($row['id']);

                if ($chatInfo['newMessagesCount'] != 0) {

                    $count++;
                }

                unset($chatInfo);
            }
        }

        return $count;
    }

    public function getPreviousMessages($chatId, $msgId = 0)
    {
        $messages = array("error" => false,
                          "error_code" => ERROR_SUCCESS,
                          "chatId" => $chatId,
                          "msgId" => $msgId,
                          "messages" => array());

        $stmt = $this->db->prepare("SELECT * FROM messages WHERE chatId = (:chatId) AND id < (:msgId) AND removeAt = 0 ORDER BY id DESC LIMIT 20");
        $stmt->bindParam(':chatId', $chatId, PDO::PARAM_INT);
        $stmt->bindParam(':msgId', $msgId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $msgInfo = $this->info2($row);

                array_push($messages['messages'], $msgInfo);

                $messages['msgId'] = $msgInfo['id'];

                unset($msgInfo);
            }

            $chatInfo = $this->chatInfo($chatId);
        }

        return $messages;
    }

    public function getNextMessages($chatId, $msgId = 0)
    {
        $messages = array("error" => false,
                          "error_code" => ERROR_SUCCESS,
                          "chatId" => $chatId,
                          "msgId" => $msgId,
                          "messages" => array());

        $stmt = $this->db->prepare("SELECT * FROM messages WHERE chatId = (:chatId) AND id > (:msgId) AND removeAt = 0 ORDER BY id ASC");
        $stmt->bindParam(':chatId', $chatId, PDO::PARAM_INT);
        $stmt->bindParam(':msgId', $msgId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $msgInfo = $this->info2($row);

                array_push($messages['messages'], $msgInfo);

                $messages['msgId'] = $msgInfo['id'];

                unset($msgInfo);
            }

            if ($stmt->rowCount() > 0) {

                $chatInfo = $this->chatInfo($chatId);

                $profileId = $chatInfo['fromUserId'];

                if ($profileId == $this->getRequestFrom()) {

                    $this->setChatLastView_FromId($chatId);

                    $msg = new msg($this->db);
                    $msg->setSeen($chatId, $chatInfo['toUserId']);
                    unset($msg);

                    // GCM_MESSAGE_ONLY_FOR_PERSONAL_USER = 2
                    // GCM_NOTIFY_SEEN= 15
                    // GCM_NOTIFY_TYPING= 16
                    // GCM_NOTIFY_TYPING_START = 27
                    // GCM_NOTIFY_TYPING_END = 28

                    $fcm = new fcm($this->db);
                    $fcm->setRequestFrom($this->getRequestFrom());
                    $fcm->setRequestTo($chatInfo['toUserId']);
                    $fcm->setType(15);
                    $fcm->setTitle("Seen");
                    $fcm->setItemId($chatId);
                    $fcm->prepare();
                    $fcm->send();
                    unset($fcm);

                } else {

                    $this->setChatLastView_ToId($chatId);

                    $msg = new msg($this->db);
                    $msg->setSeen($chatId, $chatInfo['fromUserId']);
                    unset($msg);

                    // GCM_MESSAGE_ONLY_FOR_PERSONAL_USER = 2
                    // GCM_NOTIFY_SEEN= 15
                    // GCM_NOTIFY_TYPING= 16
                    // GCM_NOTIFY_TYPING_START = 27
                    // GCM_NOTIFY_TYPING_END = 28

                    $fcm = new fcm($this->db);
                    $fcm->setRequestFrom($this->getRequestFrom());
                    $fcm->setRequestTo($chatInfo['fromUserId']);
                    $fcm->setType(15);
                    $fcm->setTitle("Seen");
                    $fcm->setItemId($chatId);
                    $fcm->prepare();
                    $fcm->send();
                    unset($fcm);
                }
            }
        }

        return $messages;
    }

    public function get($chatId, $msgId = 0)
    {
        if ($msgId == 0) {

            $msgId = $this->getMaxMessageId();
            $msgId++;
        }

        $messages = array("error" => false,
                          "error_code" => ERROR_SUCCESS,
                          "chatId" => $chatId,
                          "messagesCount" => $this->messagesCountByChat($chatId),
                          "msgId" => $msgId,
                          "newMessagesCount" => 0,
                          "messages" => array());

        $stmt = $this->db->prepare("SELECT * FROM messages WHERE chatId = (:chatId) AND id < (:msgId) AND removeAt = 0 ORDER BY id DESC LIMIT 20");
        $stmt->bindParam(':chatId', $chatId, PDO::PARAM_INT);
        $stmt->bindParam(':msgId', $msgId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $msgInfo = $this->info2($row);

                array_push($messages['messages'], $msgInfo);

                $messages['msgId'] = $msgInfo['id'];

                unset($msgInfo);
            }

            $chatInfo = $this->chatInfo($chatId);

            $profileId = $chatInfo['fromUserId'];

            if ($profileId == $this->getRequestFrom()) {

                $this->setChatLastView_FromId($chatId);

                $msg = new msg($this->db);
                $msg->setSeen($chatId, $chatInfo['toUserId']);
                unset($msg);

                // GCM_MESSAGE_ONLY_FOR_PERSONAL_USER = 2
                // GCM_NOTIFY_SEEN= 15
                // GCM_NOTIFY_TYPING= 16
                // GCM_NOTIFY_TYPING_START = 27
                // GCM_NOTIFY_TYPING_END = 28

                $fcm = new fcm($this->db);
                $fcm->setRequestFrom($this->getRequestFrom());
                $fcm->setRequestTo($chatInfo['toUserId']);
                $fcm->setType(15);
                $fcm->setTitle("Seen");
                $fcm->setItemId($chatId);
                $fcm->prepare();
                $fcm->send();
                unset($fcm);

            } else {

                $this->setChatLastView_ToId($chatId);

                $msg = new msg($this->db);
                $msg->setSeen($chatId, $chatInfo['fromUserId']);
                unset($msg);

                // GCM_MESSAGE_ONLY_FOR_PERSONAL_USER = 2
                // GCM_NOTIFY_SEEN= 15
                // GCM_NOTIFY_TYPING= 16
                // GCM_NOTIFY_TYPING_START = 27
                // GCM_NOTIFY_TYPING_END = 28

                $fcm = new fcm($this->db);
                $fcm->setRequestFrom($this->getRequestFrom());
                $fcm->setRequestTo($chatInfo['fromUserId']);
                $fcm->setType(15);
                $fcm->setTitle("Seen");
                $fcm->setItemId($chatId);
                $fcm->prepare();
                $fcm->send();
                unset($fcm);
            }
        }

        $messages_count = 0;

        $msg = new messages($this->db);
        $msg->setRequestFrom($this->getRequestFrom());

        $messages_count = $msg->getNewMessagesCount();

        unset($msg);


        $messages['newMessagesCount'] = $messages_count;

        return $messages;
    }

    public function getFull($chatId)
    {
        $messages = array("error" => false,
                          "error_code" => ERROR_SUCCESS,
                          "chatId" => $chatId,
                          "messagesCount" => $this->messagesCountByChat($chatId),
                          "messages" => array());

        $stmt = $this->db->prepare("SELECT id FROM messages WHERE chatId = (:chatId) AND removeAt = 0 ORDER BY id ASC");
        $stmt->bindParam(':chatId', $chatId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $msgInfo = $this->info($row['id']);

                array_push($messages['messages'], $msgInfo);

                unset($msgInfo);
            }
        }

        return $messages;
    }

    public function getStream($msgId = 0, $language = 'en')
    {
        if ($msgId == 0) {

            $msgId = $this->getMaxMessageId();
            $msgId++;
        }

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "msgId" => $msgId,
                        "messages" => array());

        $stmt = $this->db->prepare("SELECT id FROM messages WHERE id < (:msgId) AND removeAt = 0 ORDER BY id DESC LIMIT 20");
        $stmt->bindParam(':msgId', $msgId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                while ($row = $stmt->fetch()) {

                    $msgInfo = $this->info($row['id']);

                    array_push($result['messages'], $msgInfo);

                    $result['msgId'] = $row['id'];

                    unset($msgInfo);
                }
            }
        }

        return $result;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setRequestFrom($requestFrom)
    {
        $this->requestFrom = $requestFrom;
    }

    public function getRequestFrom()
    {
        return $this->requestFrom;
    }
}
