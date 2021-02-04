<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

class stats extends db_connect
{
    private $requestFrom = 0;

    public function __construct($dbo = NULL)
    {
        parent::__construct($dbo);

    }

    public function getChatsCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM chats WHERE removeAt = 0");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getChatsTotal()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM chats");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getMessagesCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM messages WHERE removeAt = 0");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getMessagesTotal()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM messages");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getPhotosCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM gallery");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getActivePhotosCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM gallery WHERE removeAt = 0");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getItemsCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM posts WHERE removeAt = 0");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getItemsTotal()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM posts");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getCommentsCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM comments WHERE removeAt = 0");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getCommentsTotal()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM comments");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getLikesCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM likes WHERE removeAt = 0");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getLikesTotal()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM likes");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getUserItemsCount($accountId)
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM posts WHERE removeAt = 0 AND fromUserId = (:accountId)");
        $stmt->bindParam(":accountId", $accountId, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getUserItemsTotal($accountId)
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM posts WHERE fromUserId = (:accountId)");
        $stmt->bindParam(":accountId", $accountId, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getCommunityItemsCount($communityId)
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM posts WHERE removeAt = 0 AND groupId = (:communityId)");
        $stmt->bindParam(":communityId", $communityId, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getCommunityItemsTotal($communityId)
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM posts WHERE groupId = (:communityId)");
        $stmt->bindParam(":communityId", $communityId, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getGroupsCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM users WHERE account_type = 1 OR account_type = 2");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getGroupsCountByState($accountState)
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM users WHERE state = (:state) AND account_type = 1 OR account_type = 2");
        $stmt->bindParam(":state", $accountState, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getUsersCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM users WHERE account_type = 0");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getUsersCountByState($accountState)
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM users WHERE state = (:state) AND account_type = 0");
        $stmt->bindParam(":state", $accountState, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    private function getMaxAccountId()
    {
        $stmt = $this->db->prepare("SELECT MAX(id) FROM users");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getAccountsCountByAdmob($value)
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM users WHERE admob = (:admob)");
        $stmt->bindParam(":admob", $value, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getGiftsCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM gifts");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getActiveGiftsCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM gifts WHERE removeAt = 0");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    private function getMaxAuthId()
    {
        $stmt = $this->db->prepare("SELECT MAX(id) FROM access_data");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getAccounts($userId = 0)
    {
        if ($userId == 0) {

            $userId = $this->getMaxAccountId();
            $userId++;
        }

        $users = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "userId" => $userId,
                        "users" => array());

        $stmt = $this->db->prepare("SELECT id FROM users WHERE id < (:userId) AND account_type = 0 ORDER BY id DESC LIMIT 20");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $account = new account($this->db, $row['id']);

                $accountInfo = $account->get();

                array_push($users['users'], $accountInfo);

                $users['userId'] = $accountInfo['id'];

                unset($accountInfo);
            }
        }

        return $users;
    }

    public function searchAccounts($userId = 0, $query = "")
    {
        if ($userId == 0) {

            $userId = $this->getMaxAccountId();
            $userId++;
        }

        $users = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "userId" => $userId,
                        "query" => $query,
                        "users" => array());

        $searchText = '%'.$query.'%';

        $stmt = $this->db->prepare("SELECT id FROM users WHERE id < (:userId) AND login LIKE (:query) OR email LIKE (:query) OR fullname LIKE (:query) ORDER BY id DESC LIMIT 100");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':query', $searchText, PDO::PARAM_STR);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $account = new account($this->db, $row['id']);

                $accountInfo = $account->get();

                array_push($users['users'], $accountInfo);

                $users['userId'] = $accountInfo['id'];

                unset($accountInfo);
            }
        }

        return $users;
    }

    public function getAuthData($accountId, $authId = 0)
    {
        if ($authId == 0) {

            $authId = $this->getMaxAuthId();
            $authId++;
        }

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "authId" => $authId,
                        "data" => array());

        $stmt = $this->db->prepare("SELECT * FROM access_data WHERE accountId = (:accountId) AND id < (:authId) ORDER BY id DESC LIMIT 200");
        $stmt->bindParam(':authId', $authId, PDO::PARAM_INT);
        $stmt->bindParam(':accountId', $accountId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {;

                $dataInfo = array("id" => $row['id'],
                                  "accountId" => $row['accountId'],
                                  "accessToken" => $row['accessToken'],
                                  "clientId" => $row['clientId'],
                                  "createAt" => $row['createAt'],
                                  "removeAt" => $row['removeAt'],
                                  "u_agent" => $row['u_agent'],
                                  "ip_addr" => $row['ip_addr']);

                array_push($result['data'], $dataInfo);

                $result['authId'] = $row['id'];

                unset($dataInfo);
            }
        }

        return $result;
    }

    public function getAccountGcmHistory($accountId)
    {
        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "data" => array());

        $stmt = $this->db->prepare("SELECT * FROM gcm_history WHERE accountId = (:accountId) ORDER BY id DESC LIMIT 20");
        $stmt->bindParam(':accountId', $accountId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {;

                $dataInfo = array("id" => $row['id'],
                                  "msg" => $row['msg'],
                                  "msgType" => $row['msgType'],
                                  "status" => $row['status'],
                                  "success" => $row['success'],
                                  "createAt" => $row['createAt']);

                array_push($result['data'], $dataInfo);

                unset($dataInfo);
            }
        }

        return $result;
    }

    public function getGcmHistory()
    {
        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "data" => array());

        $stmt = $this->db->prepare("SELECT * FROM gcm_history WHERE accountId = 0 ORDER BY id DESC LIMIT 20");

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {;

                $dataInfo = array("id" => $row['id'],
                                  "msg" => $row['msg'],
                                  "msgType" => $row['msgType'],
                                  "status" => $row['status'],
                                  "success" => $row['success'],
                                  "createAt" => $row['createAt']);

                array_push($result['data'], $dataInfo);

                unset($dataInfo);
            }
        }

        return $result;
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

