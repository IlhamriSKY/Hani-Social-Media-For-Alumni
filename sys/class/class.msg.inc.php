<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

class msg extends db_connect
{

	private $requestFrom = 0;
    private $language = 'en';

    // add spam words to this array list

    private $SPAM_LIST_ARRAY = array(
        "069sex",
        "069sex.com",
        "sex.com",
        "I will fulfill your seхual desires");

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

        $spam = new spam($this->db);
        $spam->setRequestFrom($this->getRequestFrom());

        if ($spam->getChatsCount() > 10) {

            return 0;
        }

        unset($spam);

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

    public function create($toUserId, $chatId,  $message = "", $imgUrl = "", $chatFromUserId = 0, $chatToUserId = 0, $listId = 0, $stickerId = 0, $stickerImgUrl = "")
    {
        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN
        );

        if (strlen($imgUrl) == 0 && strlen($message) == 0 && strlen($stickerImgUrl) == 0) {

            return $result;
        }

        if (strlen($stickerImgUrl) > 0) {

            $imgUrl = $stickerImgUrl;
        }

        if (strlen($imgUrl) != 0 && strpos($imgUrl, APP_HOST) === false) {

            return $result;
        }

        if ($this->checkSpam($message, $this->SPAM_LIST_ARRAY)) {

            return $result;
        }

        if ($chatId == 0) {

            $chatId = $this->getChatId($this->getRequestFrom(), $toUserId);

            if ($chatId == 0) {

                $chatId = $this->createChat($this->getRequestFrom(), $toUserId);
            }
        }

        $result['chatId'] = $chatId;

        $currentTime = time();
        $ip_addr = helper::ip_addr();
        $u_agent = helper::u_agent();

        $stmt = $this->db->prepare("INSERT INTO messages (chatId, fromUserId, toUserId, message, imgUrl, stickerId, stickerImgUrl, createAt, ip_addr, u_agent) value (:chatId, :fromUserId, :toUserId, :message, :imgUrl, :stickerId, :stickerImgUrl, :createAt, :ip_addr, :u_agent)");
        $stmt->bindParam(":chatId", $chatId, PDO::PARAM_INT);
        $stmt->bindParam(":fromUserId", $this->requestFrom, PDO::PARAM_INT);
        $stmt->bindParam(":toUserId", $toUserId, PDO::PARAM_INT);
        $stmt->bindParam(":message", $message, PDO::PARAM_STR);
        $stmt->bindParam(":imgUrl", $imgUrl, PDO::PARAM_STR);
        $stmt->bindParam(":stickerId", $stickerId, PDO::PARAM_INT);
        $stmt->bindParam(":stickerImgUrl", $stickerImgUrl, PDO::PARAM_STR);
        $stmt->bindParam(":createAt", $currentTime, PDO::PARAM_INT);
        $stmt->bindParam(":ip_addr", $ip_addr, PDO::PARAM_STR);
        $stmt->bindParam(":u_agent", $u_agent, PDO::PARAM_STR);

        if ($stmt->execute()) {

            $msgId = $this->db->lastInsertId();

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS,
                            "chatId" => $chatId,
                            "listId" => $listId,
                            "msgId" => $msgId,
                            "message" => array());

            $time = new language($this->db, $this->language);

            $profileInfo = array(
                "fromUserId" => $this->requestFrom,
                "state" => 0,
                "verify" => 0,
                "verified" => 0,
                "online" => true,
                "username" => "",
                "fullname" => "",
                "lowPhotoUrl" => "");

            $msgInfo = array(
                "error" => false,
                "error_code" => ERROR_SUCCESS,
                "id" => $msgId,
                "fromUserId" => $this->requestFrom,
                "fromUserState" => $profileInfo['state'],
                "fromUserVerify" => $profileInfo['verify'],
                "fromUserOnline" => $profileInfo['online'],
                "fromUserUsername" => $profileInfo['username'],
                "fromUserFullname" => $profileInfo['fullname'],
                "fromUserPhotoUrl" => $profileInfo['lowPhotoUrl'],
                "message" => htmlspecialchars_decode(stripslashes($message)),
                "imgUrl" => $imgUrl,
                "stickerId" => $stickerId,
                "stickerImgUrl" => $stickerImgUrl,
                "createAt" => $currentTime,
                "seenAt" => 0,
                "date" => date("Y-m-d H:i:s", $currentTime),
                "timeAgo" => $time->timeAgo($currentTime),
                "removeAt" => 0);

            $result['message'] = $msgInfo;

            $fcm = new fcm($this->db);
            $fcm->setRequestFrom($this->getRequestFrom());
            $fcm->setRequestTo($toUserId);
            $fcm->setType(GCM_NOTIFY_MESSAGE);
            $fcm->setTitle("You have new message");
            $fcm->setItemId($chatId);
            $fcm->setMessage($msgInfo);
            $fcm->prepare();
            $fcm->send();
            unset($fcm);

            // Update Chat Data

            if ($chatFromUserId != 0 && $chatToUserId != 0) {

                $profileId = $chatFromUserId;

                if ($profileId == $this->getRequestFrom()) {

                    $this->setLastMessageInChat_FromId($chatId, $currentTime, $msgInfo['message'], $msgInfo['imgUrl'], $msgInfo['stickerImgUrl']);

                } else {

                    $this->setLastMessageInChat_ToId($chatId, $currentTime, $msgInfo['message'], $msgInfo['imgUrl'], $msgInfo['stickerImgUrl']);
                }


            } else {

                $chatInfo = $this->chatInfo($chatId);

                $profileId = $chatInfo['fromUserId'];

                if ($profileId == $this->getRequestFrom()) {

                    $this->setLastMessageInChat_FromId($chatId, $currentTime, $msgInfo['message'], $msgInfo['imgUrl'], $msgInfo['stickerImgUrl']);

                } else {

                    $this->setLastMessageInChat_ToId($chatId, $currentTime, $msgInfo['message'], $msgInfo['imgUrl'], $msgInfo['stickerImgUrl']);
                }
            }
        }

        return $result;
    }

    private function checkSpam($str, array $arr)
    {
        foreach($arr as $a) {

            if (stripos($str, $a) !== false) return true;
        }

        return false;
    }

    public function setLastMessageInChat_FromId($chatId, $time, $message, $image, $sticker = "") {

        if (strlen($image) > 0 && strlen($message) == 0) {

            $message = "Image";
        }

        if (strlen($sticker) > 0) {

            $message = "Sticker";
        }

        $stmt = $this->db->prepare("UPDATE chats SET message = (:message), messageCreateAt = (:messageCreateAt), fromUserId_lastView = (:fromUserId_lastView) WHERE id = (:chatId)");
        $stmt->bindParam(":messageCreateAt", $time, PDO::PARAM_INT);
        $stmt->bindParam(":message", $message, PDO::PARAM_STR);
        $stmt->bindParam(':chatId', $chatId, PDO::PARAM_INT);
        $stmt->bindParam(":fromUserId_lastView", $time, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function setLastMessageInChat_ToId($chatId, $time, $message, $image, $sticker = "") {

        if (strlen($image) > 0 && strlen($message) == 0) {

            $message = "Image";
        }

        if (strlen($sticker) > 0) {

            $message = "Sticker";
        }

        $stmt = $this->db->prepare("UPDATE chats SET message = (:message), messageCreateAt = (:messageCreateAt), toUserId_lastView = (:toUserId_lastView) WHERE id = (:chatId)");
        $stmt->bindParam(":messageCreateAt", $time, PDO::PARAM_INT);
        $stmt->bindParam(":message", $message, PDO::PARAM_STR);
        $stmt->bindParam(':chatId', $chatId, PDO::PARAM_INT);
        $stmt->bindParam(":toUserId_lastView", $time, PDO::PARAM_INT);
        $stmt->execute();
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

    public function getNewMessagesInChat($chatId, $fromUserId, $fromUserId_lastView, $toUserId_lastView) {

        $profileId = $fromUserId;

        if ($profileId == $this->getRequestFrom()) {

            $stmt = $this->db->prepare("SELECT count(*) FROM messages WHERE chatId = (:chatId) AND fromUserId <> (:fromUserId) AND createAt > (:fromUserId_lastView) AND removeAt = 0");
            $stmt->bindParam(':chatId', $chatId, PDO::PARAM_INT);
            $stmt->bindParam(':fromUserId', $this->requestFrom, PDO::PARAM_INT);
            $stmt->bindParam(':fromUserId_lastView', $fromUserId_lastView, PDO::PARAM_INT);

        } else {

            $stmt = $this->db->prepare("SELECT count(*) FROM messages WHERE chatId = (:chatId) AND fromUserId <> (:fromUserId) AND createAt > (:toUserId_lastView) AND removeAt = 0");
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

                $new_messages_count = 0;

                if (APP_MESSAGES_COUNTERS) {

                    $new_messages_count = $this->getNewMessagesInChat($chatId, $row['fromUserId'], $row['fromUserId_lastView'], $row['toUserId_lastView']);
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
                                "withUserUsername" => $profileInfo['username'],
                                "withUserFullname" => $profileInfo['fullname'],
                                "withUserPhotoUrl" => $profileInfo['lowPhotoUrl'],
                                "lastMessage" => $row['message'],
                                "lastMessageAgo" => $time->timeAgo($row['messageCreateAt']),
                                "lastMessageCreateAt" => $row['messageCreateAt'],
                                "newMessagesCount" => $new_messages_count,
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

                unset($profileInfo);
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
                                "fromUserVerify" => $profileInfo['verify'],
                                "fromUserUsername" => $profileInfo['username'],
                                "fromUserFullname" => $profileInfo['fullname'],
                                "fromUserPhotoUrl" => $profileInfo['lowPhotoUrl'],
                                "message" => htmlspecialchars_decode(stripslashes($row['message'])),
                                "imgUrl" => $row['imgUrl'],
                                "stickerId" => $row['stickerId'],
                                "stickerImgUrl" => $row['stickerImgUrl'],
                                "seenAt" => $row['seenAt'],
                                "createAt" => $row['createAt'],
                                "date" => date("Y-m-d H:i:s", $row['createAt']),
                                "timeAgo" => $time->timeAgo($row['createAt']),
                                "removeAt" => $row['removeAt']);
            }
        }

        return $result;
    }

    public function getDialogs_new($messageCreateAt = 0)
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

                $time = new language($this->db, $this->language);

                $profileId = $row['fromUserId'];

                if ($profileId == $this->getRequestFrom()) {

                    $profileId = $row['toUserId'];
                }

                $profile = new profile($this->db, $profileId);
                $profile->setRequestFrom($this->requestFrom);
                $profileInfo = $profile->getVeryShort();
                unset($profile);

                $new_messages_count = 0;

                if (APP_MESSAGES_COUNTERS) {

                    $new_messages_count = $this->getNewMessagesInChat($row['id'], $row['fromUserId'], $row['fromUserId_lastView'], $row['toUserId_lastView']);
                }

                $chatInfo = array("error" => false,
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
                                  "withUserUsername" => $profileInfo['username'],
                                  "withUserFullname" => $profileInfo['fullname'],
                                  "withUserPhotoUrl" => $profileInfo['normalPhotoUrl'],
                                  "lastMessage" => $row['message'],
                                  "lastMessageAgo" => $time->timeAgo($row['messageCreateAt']),
                                  "lastMessageCreateAt" => $row['messageCreateAt'],
                                  "newMessagesCount" => $new_messages_count,
                                  "createAt" => $row['createAt'],
                                  "date" => date("Y-m-d H:i:s", $row['createAt']),
                                  "timeAgo" => $time->timeAgo($row['createAt']),
                                  "removeAt" => $row['removeAt']);

                unset($profileInfo);

                array_push($chats['chats'], $chatInfo);

                $chats['messageCreateAt'] = $chatInfo['lastMessageCreateAt'];

                unset($chatInfo);
            }
        }

        return $chats;
    }

    public function getDialogs($itemId = 0)
    {
        if ($itemId == 0) {

            $itemId = $this->getMaxChatId();
            $itemId++;
        }

        $chats = array("error" => false,
                       "error_code" => ERROR_SUCCESS,
                       "itemId" => $itemId,
                       "chats" => array());

        $stmt = $this->db->prepare("SELECT * FROM chats WHERE (fromUserId = (:userId) OR toUserId = (:userId)) AND id < (:itemId) AND removeAt = 0 ORDER BY id DESC LIMIT 20");
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
        $stmt->bindParam(':userId', $this->requestFrom, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $time = new language($this->db, $this->language);

                $profileId = $row['fromUserId'];

                if ($profileId == $this->getRequestFrom()) {

                    $profileId = $row['toUserId'];
                }

                $profile = new profile($this->db, $profileId);
                $profile->setRequestFrom($this->requestFrom);
                $profileInfo = $profile->getVeryShort();
                unset($profile);

                $new_messages_count = 0;

                if (APP_MESSAGES_COUNTERS) {

                    $new_messages_count = $this->getNewMessagesInChat($row['id'], $row['fromUserId'], $row['fromUserId_lastView'], $row['toUserId_lastView']);
                }

                $chatInfo = array("error" => false,
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
                                  "withUserUsername" => $profileInfo['username'],
                                  "withUserFullname" => $profileInfo['fullname'],
                                  "withUserPhotoUrl" => $profileInfo['normalPhotoUrl'],
                                  "lastMessage" => $row['message'],
                                  "lastMessageAgo" => $time->timeAgo($row['messageCreateAt']),
                                  "lastMessageCreateAt" => $row['messageCreateAt'],
                                  "newMessagesCount" => $new_messages_count,
                                  "createAt" => $row['createAt'],
                                  "date" => date("Y-m-d H:i:s", $row['createAt']),
                                  "timeAgo" => $time->timeAgo($row['createAt']),
                                  "removeAt" => $row['removeAt']);

                unset($profileInfo);

                array_push($chats['chats'], $chatInfo);

                $chats['itemId'] = $chatInfo['id'];

                unset($chatInfo);
            }
        }

        return $chats;
    }

    public function getChats($itemId = 0)
    {
        if ($itemId == 0) {

            $itemId = $this->getMaxChatId();
            $itemId++;
        }

        $chats = array("error" => false,
                       "error_code" => ERROR_SUCCESS,
                       "itemId" => $itemId,
                       "chats" => array());

        $stmt = $this->db->prepare("SELECT id FROM chats WHERE (fromUserId = (:userId) OR toUserId = (:userId)) AND id < (:itemId) AND removeAt = 0 ORDER BY id DESC LIMIT 20");
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
        $stmt->bindParam(':userId', $this->requestFrom, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $chatInfo = $this->chatInfo($row['id']);

                array_push($chats['chats'], $chatInfo);

                $chats['itemId'] = $chatInfo['id'];

                unset($chatInfo);
            }
        }

        return $chats;
    }

    public function getNewMessagesCount()
    {
        $count = 0;

        $stmt = $this->db->prepare("SELECT id, fromUserId, fromUserId_lastView, toUserId_lastView FROM chats WHERE (fromUserId = (:userId) OR toUserId = (:userId)) AND removeAt = 0");
        $stmt->bindParam(':userId', $this->requestFrom, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $new_messages = $this->getNewMessagesInChat($row['id'], $row['fromUserId'], $row['fromUserId_lastView'], $row['toUserId_lastView']);

                if ($new_messages != 0) {

                    $count++;
                }
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

                $time = new language($this->db, $this->language);

                $profile = new profile($this->db, $row['fromUserId']);
                $profileInfo = $profile->getVeryShort();
                unset($profile);

                $msgInfo = array("error" => false,
                                 "error_code" => ERROR_SUCCESS,
                                 "id" => $row['id'],
                                 "fromUserId" => $row['fromUserId'],
                                 "fromUserState" => $profileInfo['state'],     //$profileInfo['state'],
                                 "fromUserVerify" => $profileInfo['verify'],     //$profileInfo['verify'],
                                 "fromUserUsername" => $profileInfo['username'], //$profileInfo['username']
                                 "fromUserFullname" => $profileInfo['fullname'], //$profileInfo['fullname']
                                 "fromUserPhotoUrl" => $profileInfo['lowPhotoUrl'], //$profileInfo['lowPhotoUrl']
                                 "message" => htmlspecialchars_decode(stripslashes($row['message'])),
                                 "imgUrl" => $row['imgUrl'],
                                 "stickerId" => $row['stickerId'],
                                 "stickerImgUrl" => $row['stickerImgUrl'],
                                 "seenAt" => $row['seenAt'],
                                 "createAt" => $row['createAt'],
                                 "date" => date("Y-m-d H:i:s", $row['createAt']),
                                 "timeAgo" => $time->timeAgo($row['createAt']),
                                 "removeAt" => $row['removeAt']);

                array_push($messages['messages'], $msgInfo);

                $messages['msgId'] = $msgInfo['id'];

                unset($msgInfo);
            }
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

                $time = new language($this->db, $this->language);

                $profile = new profile($this->db, $row['fromUserId']);
                $profileInfo = $profile->getVeryShort();
                unset($profile);

                $msgInfo = array("error" => false,
                                 "error_code" => ERROR_SUCCESS,
                                 "id" => $row['id'],
                                 "fromUserId" => $row['fromUserId'],
                                 "fromUserState" => $profileInfo['state'],     //$profileInfo['state'],
                                 "fromUserVerify" => $profileInfo['verify'],     //$profileInfo['verify'],
                                 "fromUserUsername" => $profileInfo['username'], //$profileInfo['username']
                                 "fromUserFullname" => $profileInfo['fullname'], //$profileInfo['fullname']
                                 "fromUserPhotoUrl" => $profileInfo['lowPhotoUrl'], //$profileInfo['lowPhotoUrl']
                                 "message" => htmlspecialchars_decode(stripslashes($row['message'])),
                                 "imgUrl" => $row['imgUrl'],
                                 "stickerId" => $row['stickerId'],
                                 "stickerImgUrl" => $row['stickerImgUrl'],
                                 "createAt" => $row['createAt'],
                                 "seenAt" => $row['seenAt'],
                                 "date" => date("Y-m-d H:i:s", $row['createAt']),
                                 "timeAgo" => $time->timeAgo($row['createAt']),
                                 "removeAt" => $row['removeAt']);

                array_push($messages['messages'], $msgInfo);

                $messages['msgId'] = $msgInfo['id'];

                unset($msgInfo);
            }
        }

        return $messages;
    }

    public function setSeen($chatId, $fromUser) {

        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $currentTime = time();

        $stmt = $this->db->prepare("UPDATE messages SET seenAt = (:seenAt) WHERE chatId = (:chatId) AND fromUserId = (:fromUserId) AND removeAt = 0 AND seenAt = 0");
        $stmt->bindParam(":seenAt", $currentTime, PDO::PARAM_INT);
        $stmt->bindParam(":chatId", $chatId, PDO::PARAM_INT);
        $stmt->bindParam(":fromUserId", $fromUser, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS);
        }

        return $result;
    }

    public function get($chatId, $msgId = 0, $chatFromUserId = 0, $chatToUserId = 0)
    {
        if ($msgId == 0) {

            $msgId = $this->getMaxMessageId();
            $msgId++;
        }

        if ($chatFromUserId == 0 && $chatToUserId == 0) {

            $chatInfo = $this->chatInfo($chatId);

            $chatFromUserId = $chatInfo['fromUserId'];
            $chatToUserId = $chatInfo['toUserId'];
        }

        $messages = array("error" => false,
                          "error_code" => ERROR_SUCCESS,
                          "chatId" => $chatId,
                          "messagesCount" => $this->messagesCountByChat($chatId),
                          "msgId" => $msgId,
                          "chatFromUserId" => $chatFromUserId,
                          "chatToUserId" => $chatToUserId,
                          "newMessagesCount" => 0,
                          "messages" => array());

        $stmt = $this->db->prepare("SELECT * FROM messages WHERE chatId = (:chatId) AND id < (:msgId) AND removeAt = 0 ORDER BY id DESC LIMIT 20");
        $stmt->bindParam(':chatId', $chatId, PDO::PARAM_INT);
        $stmt->bindParam(':msgId', $msgId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $profile_from = new profile($this->db, $chatFromUserId);
            $profileInfo_from = $profile_from->getVeryShort();
            unset($profile_from);

            $profile_to = new profile($this->db, $chatToUserId);
            $profileInfo_to = $profile_to->getVeryShort();
            unset($profile_to);

            while ($row = $stmt->fetch()) {

                $time = new language($this->db, $this->language);

                $profileInfo = array();

                if ($row['fromUserId'] == $profileInfo_to['id']) {

                    $profileInfo = $profileInfo_to;

                }

                if ($row['fromUserId'] == $profileInfo_from['id']) {

                    $profileInfo = $profileInfo_from;

                }

//                $profile = new profile($this->db, $row['fromUserId']);
//                $profileInfo = $profile->getVeryShort();
//                unset($profile);

                $msgInfo = array("error" => false,
                                 "error_code" => ERROR_SUCCESS,
                                 "id" => $row['id'],
                                 "fromUserId" => $profileInfo['id'],
                                 "fromUserState" => $profileInfo['state'],
                                 "fromUserVerify" => $profileInfo['verify'],
                                 "fromUserUsername" => $profileInfo['username'],
                                 "fromUserFullname" => $profileInfo['fullname'],
                                 "fromUserPhotoUrl" => $profileInfo['lowPhotoUrl'],
                                 "message" => htmlspecialchars_decode(stripslashes($row['message'])),
                                 "imgUrl" => $row['imgUrl'],
                                 "stickerId" => $row['stickerId'],
                                 "stickerImgUrl" => $row['stickerImgUrl'],
                                 "seenAt" => $row['seenAt'],
                                 "createAt" => $row['createAt'],
                                 "date" => date("Y-m-d H:i:s", $row['createAt']),
                                 "timeAgo" => $time->timeAgo($row['createAt']),
                                 "removeAt" => $row['removeAt']);

                array_push($messages['messages'], $msgInfo);

                $messages['msgId'] = $msgInfo['id'];

                unset($msgInfo);
                unset($profileInfo);
            }
        }

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
