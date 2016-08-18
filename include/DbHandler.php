<?php

class DbHandler {

    private $conn;

    function __construct() {
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }

    /* ------------- `users` table method ------------------ */

    public function createUser($username, $email, $password) {
        require_once 'PassHash.php';
        $response = array();

        // First check if user already existed in db
        if (!$this->isUserExistsByEmail($email)) {
            // Generating password hash
            $password_hash = PassHash::hash($password);

            // Generating API key
            $api_key = $this->generateApiKey();

            // insert query
            $query = "INSERT INTO users VALUES(NULL, '', '$username', '$email', '', '$password_hash', '$api_key', 1, now(), '')";
            $result = $this->queryMysql($query);
            $id = mysql_insert_id();

            // Check for successful insertion
            if ($result) {
                // User successfully inserted
                $response["id"] = $id;
                $response["username"] = $username;
                $response["email"] = $email;
                $response["password"] = $password;
                $response["api_key"] = $api_key;
                $response["message"] = USER_CREATED_SUCCESSFULLY;
            } else {
                // Failed to create user
                $response["message"] = USER_CREATE_FAILED;
            }
        } else {
            // User with same email already existed in the db
            $response["message"] = USER_ALREADY_EXISTED;
        }

        return $response;
    }

    /**
     * Checking user login
     * @param String $email User login email id
     * @param String $password User login password
     * @return boolean User login status success/fail
     */
    public function checkLoginByEmail($email, $password) {
        // fetching user by email
        $query = "SELECT * FROM users WHERE email='$email'";

        if (mysql_num_rows($this->queryMysql($query)) > 0) {
            // Found user with the email
            // Now verify the password
            $pass = mysqli_fetch_object($this->queryMysql($query));
            $pass = substr($pass->password_hash, 0, 29);
            $password_hash = substr(PassHash::hash($password), 0, 29);

            if ($pass == $password_hash) {
                // User password is correct
                return TRUE;
            } else {
                // user password is incorrect
                return FALSE;
            }

        } else {
            // user not existed with the email
            return FALSE;
        }
    }

    /**
     * Checking for duplicate user by email address
     * @param String $email email to check in db
     * @return boolean
     */
    private function isUserExistsByEmail($email)
    {
        $query = "SELECT id from users WHERE email = '$email'";
        $num_rows = mysql_num_rows($this->queryMysql($query));
        if($num_rows > 0)
            return true;
        else return false;
    }

    /**
     * Fetching user by email
     * @param String $email User email id
     */
    public function getUserByEmail($email) {
        $query = "SELECT * FROM users WHERE email='$email'";

        if (mysql_num_rows($this->queryMysql($query)) > 0) {
            // Found user with the email
            // Now verify the password
            $q = mysqli_fetch_object($this->queryMysql($query));
            $user = array();
            $user["name"] = $q->name;
            $user["email"] = $q->email;
            $user["phone"] = $q->phone;
            $user["api_key"] = $q->api_key;
            $user["status"] = $q->status;
            $user["created_at"] = $q->created_at;
            return $user;

        } else {
            // user not existed with the email
            return NULL;
        }
    }

    /**
     * Fetching user api key
     * @param String $user_id user id primary key in user table
     */
    public function getUserKeyById($user_id) {
       //todo
    }

    /**
     * Fetching user id by api key
     * @param String $api_key user api key
     */
    public function getUserId($api_key) {
        $query = "SELECT id from users WHERE api_key = '$api_key'";
        $num_rows = mysql_num_rows($this->queryMysql($query));
        if ($num_rows > 0) {
            // TODO
            $user_id = mysqli_fetch_object($this->queryMysql($query))->id;
            return $user_id;
        } else {
            return NULL;
        }
    }

    /**
     * Validating user api key
     * If the api key is there in db, it is a valid key
     * @param String $api_key user api key
     * @return boolean
     */
    public function isValidApiKey($api_key) {
        $query = "SELECT id from users WHERE api_key = '$api_key'";
        $num_rows = mysql_num_rows($this->queryMysql($query));
        if($num_rows > 0)
            return true;
        else return false;
    }

    /**
     * Generating random Unique MD5 String for user Api key
     */
    private function generateApiKey() {
        return md5(uniqid(rand(), true));
    }

    /* ------------- `posts` table method ------------------ */
    /**
     * Creating new task
     * @param String $user_id user id to whom task belongs to
     * @param String $task task text
     */
    public function createPost($user_id, $title, $content, $price, $price_currency, $idCategory, $idSubcategory, $idSubSubcategory, $city, $country, $actionType, $sex, $birth_year, $displayed_name)
    {

        $query = "INSERT INTO posts VALUES(NULL, '$title', '$content', '$price', '$price_currency', now(), 0, now(), '$idCategory', '$idSubcategory', '$idSubSubcategory', 0, '$city', '$country', '$user_id', '$actionType', '$sex', '$birth_year', '$displayed_name')";
        $result = $this->queryMysql($query);

        if ($result) {
            // post row created
            $new_post_id = mysql_insert_id();
            return $new_post_id;
        } else {
            // post failed to create
            return NULL;
        }
    }
    public function createImage($post_id, $name) {

        $query = "INSERT INTO images VALUES(NULL, '$name', '$post_id')";
        $result = $this->queryMysql($query);

        if ($result) {
            // post row created
            $image_id = mysql_insert_id();
            return $image_id;
        } else {
            // post failed to create
            return NULL;
        }
    }

    public function getPost($post_id) {

        $query = $this->queryPosts()." WHERE p.ID='$post_id'";

        $result = mysql_fetch_object($this->queryMysql($query));

        if ($result != null) {
            $res = array();

            $res["id"] = $result->id;
            $res["title"] = $result->title;
            $res["content"] = $result->content;
            $res["price"] = $result->price;
            $res["pricecurrency"] = $result->pricecurrency;
            $res["created_at"] = $result->created_at;
            $res["post_status"] = $result->post_status;
            $res["idCategory"] = $result->idCategory;
            $res["idSubCategory"] = $result->idSubCategory;
            $res["idSubSubCategory"] = $result->idSubSubCategory;
            $res["hitcount"] = $result->hitcount;
            $res["city"] = $result->city;
            $res["country"] = $result->country;
            $res["idUser"] = $result->idUser;
            $res["name"] = $result->name;
            $res["email"] = $result->email;
            $res["phone"] = $result->phone;
            $res["api_key"] = $result->api_key;
            $res["user_status"] = $result->user_status;

            return $res;
        } else {
            return NULL;
        }
    }

    public function getAllUserPosts($user_id) {

        $query = "SELECT * FROM posts WHERE idUser = '$user_id'";
        $result = $this->queryMysql($query);

        return $result;
    }
    public function getUserPostsByPage($user_id, $page) {

        $query = $this->queryPosts()." WHERE idUser = '$user_id'";
        //paging
        $num_rec_per_page = NUM_REC_PER_PAGE;
        //$advsQ = queryMysql($query);
        //$total_records = mysql_num_rows($advsQ);  //count number of records
        //$total_pages = ceil($total_records / $num_rec_per_page);
        $start_from = ($page-1) * $num_rec_per_page;
        $query = $query." LIMIT  $start_from, $num_rec_per_page";
        //end of paging
        $result_posts = $this->queryMysql($query);
        //response
        $response = $this->ResultPosts($result_posts);

        return $response;
    }

    public function getUserLikedPostsByPage($user_id, $page) {

        $queryLikes = "SELECT * FROM likes WHERE idUser = '$user_id'";

        $resultLikes = $this->queryMysql($queryLikes);
        $num = mysqli_num_rows($resultLikes);

        $query = $this->queryPosts();

        $result_posts = null;

        if($num > 0)
        {
            $first = true;
            while ($like = mysqli_fetch_assoc($resultLikes))
            {
                $id = $like['idPost'];

                if($first)
                {
                    $query = $query." WHERE p.ID = '$id'";
                    $first = false;
                }
                else $query = $query." OR p.ID = '$id'";
            }
            //paging
            $num_rec_per_page = NUM_REC_PER_PAGE;
            //$advsQ = queryMysql($query);
            //$total_records = mysql_num_rows($advsQ);  //count number of records
            //$total_pages = ceil($total_records / $num_rec_per_page);
            $start_from = ($page-1) * $num_rec_per_page;
            $query = $query." LIMIT  $start_from, $num_rec_per_page";
            //end of paging
            $result_posts = $this->queryMysql($query);
            //response
            $response = $this->ResultPosts($result_posts);

            return $response;
        }

            return null;;
    }

    public function updatePostsHitcount($post_id, $user_id) {

        //hitcount job
        $query = "SELECT * FROM posts WHERE ID = '$post_id'";
        $post = mysqli_fetch_object($this->queryMysql($query));
        $count = $post->hitcount + 1;

        $query_update = "UPDATE posts SET hitcount = '$count' WHERE ID = '$post_id'";
        $result = $this->queryMysql($query_update);
        $res = array();

        if ($result)
        {
            $res["error"] = false;
            $res["message"] = "Post's hitcount updated successfully";
            //like job
            $query = "SELECT * FROM likes WHERE idUser = '$user_id' AND idPost = '$post_id'";

            if(mysql_num_rows($this->queryMysql($query)) > 0)
            {
                $res["like"] = true;
            }
            else
            {
                $res["like"] = false;
            }
        }
        else
        {
            $res["error"] = true;
            $res["message"] = "Post's hitcount failed to update. Please try again!";
        }

        return $res;
    }

    public function updateLikes($post_id, $user_id, $like)
    {
        $query = "SELECT * FROM likes WHERE idUser = '$user_id' AND idPost = '$post_id'";
        $result = false;

        if(mysql_num_rows($this->queryMysql($query)) > 0)
        {
            if($like == "0")
            {
                $query = "DELETE FROM likes WHERE idUser = '$user_id' AND idPost = '$post_id'";
                $result = $this->queryMysql($query);
            }
        }
        else
        {
            if($like == "1")
            {
                $query = "INSERT INTO likes VALUES('$user_id', '$post_id')";
                $result = $this->queryMysql($query);
            }
        }

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function getPosts($page, $params) {

        $query = $this->queryPosts();

        //params
        $query = $this->queryParams($query, $params);

        //paging
        $num_rec_per_page = NUM_REC_PER_PAGE;
        //$advsQ = queryMysql($query);
        //$total_records = mysql_num_rows($advsQ);  //count number of records
        //$total_pages = ceil($total_records / $num_rec_per_page);
        $start_from = ($page-1) * $num_rec_per_page;
        $query = $query." LIMIT  $start_from, $num_rec_per_page";
        //end of paging
        $result_posts = $this->queryMysql($query);
        //response
        $response = $this->ResultPosts($result_posts);

        return $response;
    }

    public function getPostsByCategory($category_id, $page, $params) {

        $query = $this->queryPosts()." WHERE p.idCategory='$category_id'";
        //params
        $query = $this->queryParams($query, $params);

        //paging
        $num_rec_per_page = NUM_REC_PER_PAGE;
        //$advsQ = queryMysql($query);
        //$total_records = mysql_num_rows($advsQ);  //count number of records
        //$total_pages = ceil($total_records / $num_rec_per_page);
        $start_from = ($page-1) * $num_rec_per_page;
        $query = $query." LIMIT  $start_from, $num_rec_per_page";
        //end of paging
        $result_posts = $this->queryMysql($query);
        //response
        $response = $this->ResultPosts($result_posts);

        return $response;
    }
    public function getPostsBySubCategory($subcategory_id, $page, $params) {

        $query = $this->queryPosts()." WHERE p.idSubCategory='$subcategory_id'";
        //params
        $query = $this->queryParams($query, $params);

        //paging
        $num_rec_per_page = NUM_REC_PER_PAGE;
        $start_from = ($page-1) * $num_rec_per_page;
        $query = $query." LIMIT  $start_from, $num_rec_per_page";
        //end of paging
        $result_posts = $this->queryMysql($query);

        //response
        $response = $this->ResultPosts($result_posts);

        return $response;
    }

    public function getImagesByPost($post_id) {

        $query = "SELECT * FROM images WHERE idPost='$post_id'";
        $result = $this->queryMysql($query);

        return $result;
    }

    public function getImages($post_ids) {

        $query = "SELECT * FROM images WHERE idPost IN (";

        foreach ($post_ids as $post_id) {
            $query .= $post_id . ',';
        }

        $query = substr($query, 0, strlen($query) - 1);
        $query .= ')';

        $result = $this->queryMysql($query);

        return $result;
    }

    public function getImage($image_id) {

        $query = "SELECT * FROM images WHERE ID='$image_id'";
        $result = $this->queryMysql($query);

        return $result;
    }

    public function getCategories() {
        $query = "SELECT * FROM category";
        $result = $this->queryMysql($query);

        return $result;
    }

    public function getSubCategories() {
        $query = "SELECT * FROM subcategory";
        $result = $this->queryMysql($query);

        return $result;
    }

    public function updatePost($post_id, $title, $content, $price, $pricecurrency, $idCategory, $idSubcategory, $idSubSubcategory)
    {
        $query = "UPDATE posts SET title = '$title', content = '$content', price = '$price', pricecurrency = '$pricecurrency', idCategory = '$idCategory', idSubcategory = '$idSubcategory', idSubSubcategory = '$idSubSubcategory' WHERE ID = '$post_id'";
        $result = $this->queryMysql($query);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function deletePost($post_id) {

        $query = "DELETE FROM posts WHERE ID = '$post_id'";
        $result = $this->queryMysql($query);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteImage($image_id) {

        $query = "DELETE FROM images WHERE ID = '$image_id'";
        $result = $this->queryMysql($query);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    public function deleteImagesByPost($post_id) {

        $query = "SELECT * FROM images WHERE idPost='$post_id'";
        $result = $this->queryMysql($query);

        $delete_query = "DELETE FROM images WHERE idPost = '$post_id'";
        $delete_result = $this->queryMysql($delete_query);

        if ($delete_result) {
            return $result;
        } else {
            return NULL;
        }
    }

    /* ------------- `tasks` table method ------------------ */

    /*
    public function createTask($user_id, $task) {
        $stmt = $this->conn->prepare("INSERT INTO tasks(task) VALUES(?)");
        $stmt->bind_param("s", $task);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            // task row created
            // now assign the task to user
            $new_task_id = $this->conn->insert_id;
            $res = $this->createUserTask($user_id, $new_task_id);
            if ($res) {
                // task created successfully
                return $new_task_id;
            } else {
                // task failed to create
                return NULL;
            }
        } else {
            // task failed to create
            return NULL;
        }
    }

    public function getTask($task_id, $user_id) {
        $stmt = $this->conn->prepare("SELECT t.id, t.task, t.status, t.created_at from tasks t, user_tasks ut WHERE t.id = ? AND ut.task_id = t.id AND ut.user_id = ?");
        $stmt->bind_param("ii", $task_id, $user_id);
        if ($stmt->execute()) {
            $res = array();
            $stmt->bind_result($id, $task, $status, $created_at);
            // TODO
            // $task = $stmt->get_result()->fetch_assoc();
            $stmt->fetch();
            $res["id"] = $id;
            $res["task"] = $task;
            $res["status"] = $status;
            $res["created_at"] = $created_at;
            $stmt->close();
            return $res;
        } else {
            return NULL;
        }
    }

    public function getAllUserTasks($user_id) {
        $stmt = $this->conn->prepare("SELECT t.* FROM tasks t, user_tasks ut WHERE t.id = ut.task_id AND ut.user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $tasks = $stmt->get_result();
        $stmt->close();
        return $tasks;
    }

    public function updateTask($user_id, $task_id, $task, $status) {
        $stmt = $this->conn->prepare("UPDATE tasks t, user_tasks ut set t.task = ?, t.status = ? WHERE t.id = ? AND t.id = ut.task_id AND ut.user_id = ?");
        $stmt->bind_param("siii", $task, $status, $task_id, $user_id);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }

    public function deleteTask($user_id, $task_id) {
        $stmt = $this->conn->prepare("DELETE t FROM tasks t, user_tasks ut WHERE t.id = ? AND ut.task_id = t.id AND ut.user_id = ?");
        $stmt->bind_param("ii", $task_id, $user_id);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }

    public function createUserTask($user_id, $task_id) {
        $stmt = $this->conn->prepare("INSERT INTO user_tasks(user_id, task_id) values(?, ?)");
        $stmt->bind_param("ii", $user_id, $task_id);
        $result = $stmt->execute();

        if (false === $result) {
            die('execute() failed: ' . htmlspecialchars($stmt->error));
        }
        $stmt->close();
        return $result;
    }

    */

    function queryMysql($query)
    {
        $result = mysqli_query($this->conn, $query) or die(mysqli_error($this->conn));
        return $result;
    }

    function queryParams($query, $params)
    {
        $res = $query;
        $params = explode(";", $params);
        $q = $params[0];
        $actionType = $params[1];

        if($q != "" && $q != "0")
            $res = $res." WHERE p.title LIKE '%$q%'";
        if($actionType != "0")
            $res = $res." AND p.actionType='$actionType'";

        return $res;
    }

    function queryPosts()
    {
        return "SELECT p.ID as id, p.title, p.content, p.price, p.pricecurrency, p.created_at, p.status as post_status, p.statusChangeDate, p.idCategory, p.idSubCategory, p.idSubSubCategory, p.hitcount, p.city, p.country, p.idUser, p.actionType, p.sex, p.birth_year, p.displayed_name, u.ID as user_id, u.name, u.username, u.email, u.phone, u.api_key, u.status as user_status, u.created_at as user_created_at FROM posts AS p LEFT JOIN users as u ON p.idUser = u.ID";
    }

    function queryChatsWithPost()
    {
        return "SELECT ch.ID as chat_id, ch.idUser1, ch.idUser2, ch.idPost, ch.created_at as chat_created_at,
                       p.ID as post_id, p.title, p.content, p.price, p.pricecurrency, p.created_at, p.status as post_status, p.statusChangeDate, p.idCategory, p.idSubCategory, p.idSubSubCategory, p.hitcount, p.city, p.country, p.idUser, p.actionType, p.sex, p.birth_year, p.displayed_name,
                       u1.name as name1, u1.username as username1, u1.email as email1, u1.phone as phone1, u1.api_key as api_key1, u1.status as user_status1, u1.created_at as user_created_at1,
                       u2.name as name2, u2.username as username2, u2.email as email2, u2.phone as phone2, u2.api_key as api_key2, u2.status as user_status2, u2.created_at as user_created_at2
                       FROM chats AS ch LEFT JOIN posts as p ON ch.idPost = p.ID LEFT JOIN users as u1 ON ch.idUser1 = u1.ID LEFT JOIN users as u2 ON ch.idUser2 = u2.ID";
    }

    function queryChats()
    {
        return "SELECT ch.ID as chat_id, ch.idUser1, ch.idUser2, ch.idPost, ch.created_at as chat_created_at,
                       u1.name as name1, u1.username as username1, u1.email as email1, u1.phone as phone1, u1.api_key as api_key1, u1.status as user_status1, u1.created_at as user_created_at1,
                       u2.name as name2, u2.username as username2, u2.email as email2, u2.phone as phone2, u2.api_key as api_key2, u2.status as user_status2, u2.created_at as user_created_at2
                       FROM chats AS ch LEFT JOIN users as u1 ON ch.idUser1 = u1.ID LEFT JOIN users as u2 ON ch.idUser2 = u2.ID";
    }

    //CHAT
    //
    //
    // updating user GCM registration ID
    public function updateGcmID($user_id, $gcm_registration_id) {
        $response = array();
        $stmt = $this->conn->prepare("UPDATE users SET gcm_registration_id = ? WHERE ID = ?");
        $stmt->bind_param("si", $gcm_registration_id, $user_id);

        if ($stmt->execute()) {
            // User successfully updated
            $response["error"] = false;
            $response["message"] = 'GCM registration ID updated successfully';
        } else {
            // Failed to update user
            $response["error"] = true;
            $response["message"] = "Failed to update GCM registration ID";
            $stmt->error;
        }
        $stmt->close();

        return $response;
    }

    // fetching single user by id
    public function getUser($user_id) {
        $stmt = $this->conn->prepare("SELECT ID, name, username, email, gcm_registration_id, created_at FROM users WHERE ID = ?");
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            // $user = $stmt->get_result()->fetch_assoc();
            $stmt->bind_result($user_id, $name, $username, $email, $gcm_registration_id, $created_at);
            $stmt->fetch();
            $user = array();
            $user["user_id"] = $user_id;
            $user["name"] = $name;
            $user["username"] = $username;
            $user["email"] = $email;
            $user["gcm_registration_id"] = $gcm_registration_id;
            $user["created_at"] = $created_at;
            $stmt->close();
            return $user;
        } else {
            return NULL;
        }
    }

    public function getToUser($chat_id, $from_user_id) {

        $stmt = $this->conn->prepare("SELECT idUser1, idUser2 FROM chats WHERE ID = ?");
        $stmt->bind_param("i", $chat_id);
        if($stmt->execute())
        {
            $stmt->bind_result($idUser1, $idUser2);
            $stmt->fetch();
            $user_id = $idUser1;
            if($from_user_id == $idUser1)
            {
                $user_id = $idUser2;
            }
            $stmt->close();
            $stmt = $this->conn->prepare("SELECT ID, name, username, email, gcm_registration_id, created_at FROM users WHERE ID = '$user_id'");
            if ($stmt->execute())
            {
                $stmt->bind_result($user_id, $name, $username, $email, $gcm_registration_id, $created_at);
                $stmt->fetch();
                $user = array();
                $user["user_id"] = $user_id;
                $user["name"] = $name;
                $user["username"] = $username;
                $user["email"] = $email;
                $user["gcm_registration_id"] = $gcm_registration_id;
                $user["created_at"] = $created_at;
                $stmt->close();
                return $user;
            } else {
                return NULL;
            }

        } else {
            return NULL;
        }
    }

    // fetching multiple users by ids
    public function getUsers($user_ids) {

        $users = array();
        if (sizeof($user_ids) > 0) {
            $query = "SELECT ID, name, username, email, gcm_registration_id, created_at FROM users WHERE ID IN (";

            foreach ($user_ids as $user_id) {
                $query .= $user_id . ',';
            }

            $query = substr($query, 0, strlen($query) - 1);
            $query .= ')';

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($user = $result->fetch_assoc()) {
                $tmp = array();
                $tmp["user_id"] = $user['ID'];
                $tmp["name"] = $user['name'];
                $tmp["username"] = $user['username'];
                $tmp["email"] = $user['email'];
                $tmp["gcm_registration_id"] = $user['gcm_registration_id'];
                $tmp["created_at"] = $user['created_at'];
                array_push($users, $tmp);
            }
        }

        return $users;
    }

    // messaging in a chat room / to persional message
    public function addMessage($user_id, $chat_room_id, $message) {
        $response = array();

        $stmt = $this->conn->prepare("INSERT INTO messages (chat_room_id, user_id, message) values(?, ?, ?)");
        $stmt->bind_param("iis", $chat_room_id, $user_id, $message);

        if ($result = $stmt->execute()) {
            $response['error'] = false;

            // get the message
            $message_id = $this->conn->insert_id;
            $stmt = $this->conn->prepare("SELECT message_id, user_id, chat_room_id, message, created_at FROM messages WHERE message_id = ?");
            $stmt->bind_param("i", $message_id);
            if ($stmt->execute()) {
                $stmt->bind_result($message_id, $user_id, $chat_room_id, $message, $created_at);
                $stmt->fetch();
                $tmp = array();
                $tmp['message_id'] = $message_id;
                $tmp['chat_room_id'] = $chat_room_id;
                $tmp['message'] = $message;
                $tmp['created_at'] = $created_at;
                $response['message'] = $tmp;
            }
        } else {
            $response['error'] = true;
            $response['message'] = 'Failed send message ' . $stmt->error;
        }

        return $response;
    }

    public function addChatMessage($user_id, $chat_id, $message) {
        $response = array();

        $stmt = $this->conn->prepare("INSERT INTO messages (chat_id, user_id, message) values(?, ?, ?)");
        $stmt->bind_param("iis", $chat_id, $user_id, $message);

        if ($result = $stmt->execute()) {
            $response['error'] = false;

            // get the message
            $message_id = $this->conn->insert_id;
            $stmt = $this->conn->prepare("SELECT user_id, chat_id, message, created_at FROM messages WHERE message_id = '$message_id'");
            if ($stmt->execute()) {
                $stmt->bind_result($user_id, $chat_id, $message, $created_at);
                $stmt->fetch();
                $tmp = array();
                $tmp['message_id'] = $message_id;
                $tmp['chat_id'] = $chat_id;
                $tmp['message'] = $message;
                $tmp['created_at'] = $created_at;
                $response['message'] = $tmp;
            }
            else{
                $response['error'] = true;
            }
        } else {
            $response['error'] = true;
            $response['message'] = 'Failed send message ' . $stmt->error;
        }

        $stmt->close();
        return $response;
    }

    // fetching user chats with post
    public function getUserChatsWithPost($user_id) {

        $chat_query = $this->queryChatsWithPost()." WHERE idUser1 = ? OR idUser2 = ?";

        $stmt = $this->conn->prepare($chat_query);
        $stmt->bind_param("ii", $user_id, $user_id);
        $stmt->execute();

        $result_chats = $stmt->get_result();

        $response = array();
        $response["error"] = false;
        $response["chats"] = array();

        $post_ids = array();
        while ($chat = $result_chats->fetch_assoc()) {

            if($chat['idPost'] != null && $chat['idPost'] > 0)
            array_push($post_ids, $chat['idPost']);

            $tmp = array();
            $tmp["chat_id"] = $chat['chat_id'];
            $tmp["chat_created_at"] = $chat['chat_created_at'];

            if($user_id == $chat['idUser1'])
            {
                //собеседник
                $tmp["interlocutor_id"] = $chat['idUser2'];
                $tmp["interlocutor_name"] = $chat['name2'];
                $tmp["interlocutor_username"] = $chat['username2'];
                $tmp["interlocutor_email"] = $chat['email2'];
                $tmp["interlocutor_phone"] = $chat['phone2'];
                $tmp["interlocutor_status"] = $chat['user_status2'];
            } else {
                $tmp["interlocutor_id"] = $chat['idUser1'];
                $tmp["interlocutor_name"] = $chat['name1'];
                $tmp["interlocutor_username"] = $chat['username1'];
                $tmp["interlocutor_email"] = $chat['email1'];
                $tmp["interlocutor_phone"] = $chat['phone1'];
                $tmp["interlocutor_status"] = $chat['user_status1'];
            }

            if($chat['post_id'] != null && $chat['post_id'] > 0)
            {
                $tmp["post_id"] = $chat['post_id'];
                $tmp["post_title"] = $chat['title'];
                $tmp["post_content"] = $chat['content'];
                $tmp["post_price"] = $chat['price'];
                $tmp["post_price_currency"] = $chat['pricecurrency'];
                $tmp["post_created_at"] = $chat['created_at'];
                $tmp["post_id_category"] = $chat['idCategory'];
                $tmp["post_id_subcategory"] = $chat['idSubCategory'];
                $tmp["post_status"] = $chat['post_status'];
                $tmp["post_hitcount"] = $chat['hitcount'];
                $tmp["post_city"] = $chat['city'];
                $tmp["post_country"] = $chat['country'];
                $tmp["post_sex"] = $chat['sex'];
                $tmp["post_birth_year"] = $chat['birth_year'];
                $tmp["post_displayed_name"] = $chat['displayed_name'];
                $tmp["post_images"] = array();
            }

            array_push($response["chats"], $tmp);
        }

        if (count($post_ids) > 0)
        {
            $query = "SELECT * FROM images WHERE idPost IN (";

            foreach ($post_ids as $post_id) {
                $query .= $post_id . ',';
            }

            $query = substr($query, 0, strlen($query) - 1);
            $query .= ')';

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result_images = $stmt->get_result();

            $images_arr = array();

            while ($image = $result_images->fetch_assoc()) {
                $tmp_sub = array();
                $tmp_sub["id"] = $image['ID'];
                $tmp_sub["original_image"] = $image['name'];;
                $tmp_sub["idPost"] = $image['idPost'];;
                array_push($images_arr, $tmp_sub);
            }

            for ($i = 0; $i < count($response["chats"]); $i++)
            {
                $images_tmp = array();

                for ($j = 0; $j < count($images_arr); $j++)
                {
                    if($images_arr[$j]["idPost"] == $response["chats"][$i]["post_id"])
                    {
                        array_push($images_tmp, $images_arr[$j]);
                    }
                }

                $response["chats"][$i]["post_images"] = $images_tmp;
            }
        }

        $stmt->close();

        return $response;
    }

    // fetching user chats without post
    public function getUserChats($user_id) {

        $chat_query = $this->queryChats()." WHERE idUser1 = ? OR idUser2 = ?";

        $stmt = $this->conn->prepare($chat_query);
        $stmt->bind_param("ii", $user_id, $user_id);
        $stmt->execute();

        $result_chats = $stmt->get_result();

        $response = array();
        $response["error"] = false;
        $response["chats"] = array();

        $post_ids = array();
        while ($chat = $result_chats->fetch_assoc()) {
            if($chat['idPost'] != null && $chat['idPost'] > 0)
            array_push($post_ids, $chat['idPost']);

            $tmp = array();
            $tmp["chat_id"] = $chat['chat_id'];
            $tmp["chat_created_at"] = $chat['chat_created_at'];
            $tmp["post_id"] = $chat['idPost'];
            $tmp["post_images"] = array();

            if($user_id == $chat['idUser1'])
            {
                //собеседник
                $tmp["interlocutor_id"] = $chat['idUser2'];
                $tmp["interlocutor_name"] = $chat['name2'];
                $tmp["interlocutor_username"] = $chat['username2'];
                $tmp["interlocutor_email"] = $chat['email2'];
                $tmp["interlocutor_phone"] = $chat['phone2'];
                $tmp["interlocutor_status"] = $chat['user_status2'];
            } else {
                $tmp["interlocutor_id"] = $chat['idUser1'];
                $tmp["interlocutor_name"] = $chat['name1'];
                $tmp["interlocutor_username"] = $chat['username1'];
                $tmp["interlocutor_email"] = $chat['email1'];
                $tmp["interlocutor_phone"] = $chat['phone1'];
                $tmp["interlocutor_status"] = $chat['user_status1'];
            }

            array_push($response["chats"], $tmp);
        }

        if (count($post_ids) > 0)
        {
            $query = "SELECT * FROM images WHERE idPost IN (";

            foreach ($post_ids as $post_id) {
                $query .= $post_id . ',';
            }

            $query = substr($query, 0, strlen($query) - 1);
            $query .= ')';

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result_images = $stmt->get_result();

            $images_arr = array();

            while ($image = $result_images->fetch_assoc()) {
                $tmp_sub = array();
                $tmp_sub["id"] = $image['ID'];
                $tmp_sub["original_image"] = $image['name'];;
                $tmp_sub["idPost"] = $image['idPost'];;
                array_push($images_arr, $tmp_sub);
            }

            for ($i = 0; $i < count($response["chats"]); $i++)
            {
                $images_tmp = array();

                for ($j = 0; $j < count($images_arr); $j++)
                {
                    if($images_arr[$j]["idPost"] == $response["chats"][$i]["post_id"])
                    {
                        array_push($images_tmp, $images_arr[$j]);
                    }
                }

                $response["chats"][$i]["post_images"] = $images_tmp;
            }
        }


        $stmt->close();

        return $response;
    }

    // fetching all chat rooms
    public function getAllChatrooms() {
        $stmt = $this->conn->prepare("SELECT * FROM chat_rooms");
        $stmt->execute();
        $tasks = $stmt->get_result();
        $stmt->close();
        return $tasks;
    }

    // fetching single chat room by id
    function getChatRoom($chat_room_id) {
        $stmt = $this->conn->prepare("SELECT cr.chat_room_id, cr.name, cr.created_at as chat_room_created_at, u.name as username, c.* FROM chat_rooms cr LEFT JOIN messages c ON c.chat_room_id = cr.chat_room_id LEFT JOIN users u ON u.ID = c.user_id WHERE cr.chat_room_id = ?");
        $stmt->bind_param("i", $chat_room_id);
        $stmt->execute();
        $tasks = $stmt->get_result();
        $stmt->close();
        return $tasks;
    }

    function getChatMessages($chat_id) {

        $stmt = $this->conn->prepare("SELECT ch.ID as main_chat_id, ch.created_at as chat_created_at, u.name, u.username, c.* FROM chats ch LEFT JOIN messages c ON c.chat_id = ch.ID LEFT JOIN users u ON u.ID = c.user_id WHERE ch.ID = ?");
        $stmt->bind_param("i", $chat_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $stmt->close();
        return $result;
    }

    function getChatMessagesByIds($user_id, $interlocutor_id, $post_id) {

        $stmt = $this->conn->prepare("SELECT ID FROM chats WHERE (idUser1 = ? OR idUser1 = ?) AND (idUser2 = ? OR idUser2 = ?) AND idPost = ?");
        $stmt->bind_param("iiiii", $user_id, $interlocutor_id, $user_id, $interlocutor_id, $post_id);
        $stmt->execute();

        $num_of_rows = 0;

        $stmt->bind_result($chat_id);

        while ($stmt->fetch()) {
            $num_of_rows++;
        }

        $result = null;

        if($num_of_rows > 0)
        {
            $stmt = $this->conn->prepare("SELECT ch.ID as main_chat_id, ch.created_at as chat_created_at, u.name, u.username, c.* FROM chats ch LEFT JOIN messages c ON c.chat_id = ch.ID LEFT JOIN users u ON u.ID = c.user_id WHERE ch.ID = '$chat_id'");
            $stmt->execute();
            $result = $stmt->get_result();
        }
        else
        {
            $stmt = $this->conn->prepare("INSERT INTO chats(idUser1, idUser2, idPost) values(?, ?, ?)");
            $stmt->bind_param("iii", $interlocutor_id, $user_id, $post_id);
            $stmt->execute();
            $new_chat_id = $this->conn->insert_id;

            $stmt = $this->conn->prepare("SELECT ch.ID as main_chat_id, ch.created_at as chat_created_at, u.name as username, c.* FROM chats ch LEFT JOIN messages c ON c.chat_id = ch.ID LEFT JOIN users u ON u.ID = c.user_id WHERE ch.ID = '$new_chat_id'");
            $stmt->execute();
            $result = $stmt->get_result();
        }

        $stmt->close();
        return $result;
    }

    /**
     * @param $result_posts
     * @return array
     */
    public function ResultPosts($result_posts)
    {
        $response = array();
        $response["error"] = false;
        $response["posts"] = array();

        $post_ids = array();
        // looping through result and preparing posts array
        while ($post = mysqli_fetch_assoc($result_posts)) {
            array_push($post_ids, $post['id']);

            $tmp = array();
            $tmp["id"] = $post['id'];
            $tmp["title"] = $post['title'];
            $tmp["content"] = $post['content'];
            $tmp["price"] = $post['price'];
            $tmp["price_currency"] = $post['pricecurrency'];
            $tmp["created_at"] = $post['created_at'];
            $tmp["id_category"] = $post['idCategory'];
            $tmp["id_subcategory"] = $post['idSubCategory'];
            $tmp["post_status"] = $post['post_status'];
            $tmp["hitcount"] = $post['hitcount'];
            $tmp["city"] = $post['city'];
            $tmp["country"] = $post['country'];
            $tmp["sex"] = $post['sex'];
            $tmp["birth_year"] = $post['birth_year'];
            $tmp["displayed_name"] = $post['displayed_name'];
            $tmp["user_id"] = $post['user_id'];
            $tmp["user_name"] = $post['name'];
            $tmp["user_username"] = $post['username'];
            $tmp["user_email"] = $post['email'];
            $tmp["user_phone"] = $post['phone'];
            $tmp["user_status"] = $post['user_status'];
            $tmp["images"] = array();

            array_push($response["posts"], $tmp);
        }

        if (count($post_ids) > 0) {
            $result_images = $this->getImages($post_ids);

            $images_arr = array();

            while ($image = $result_images->fetch_assoc()) {
                $tmp_sub = array();
                $tmp_sub["id"] = $image['ID'];
                $tmp_sub["original_image"] = $image['name'];;
                $tmp_sub["idPost"] = $image['idPost'];;
                array_push($images_arr, $tmp_sub);
            }

            for ($i = 0; $i < count($response["posts"]); $i++) {
                $images_tmp = array();

                for ($j = 0; $j < count($images_arr); $j++) {
                    if ($images_arr[$j]["idPost"] == $response["posts"][$i]["id"]) {
                        array_push($images_tmp, $images_arr[$j]);
                    }
                }

                $response["posts"][$i]["images"] = $images_tmp;
            }

        }

        return $response;
    }

    /**
     * Checking for duplicate user by email address
     * @param String $email email to check in db
     * @return boolean
     */
    private function isUserExists($email) {
        $stmt = $this->conn->prepare("SELECT ID from users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;

        $stmt->free_result();
        $stmt->close();
        return $num_rows > 0;
    }
}

?>
