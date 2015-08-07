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

    public function createUser($name, $email, $phone, $password) {
        require_once 'PassHash.php';
        $response = array();

        // First check if user already existed in db
        if (!$this->isUserExistsByEmail($email)) {
            // Generating password hash
            $password_hash = PassHash::hash($password);

            // Generating API key
            $api_key = $this->generateApiKey();

            // insert query
            $query = "INSERT INTO users VALUES(NULL, '$name', '$email', '$phone', '$password_hash', '$api_key', 1, now())";
            $result = $this->queryMysql($query);

            // Check for successful insertion
            if ($result) {
                // User successfully inserted
                return USER_CREATED_SUCCESSFULLY;
            } else {
                // Failed to create user
                return USER_CREATE_FAILED;
            }
        } else {
            // User with same email already existed in the db
            return USER_ALREADY_EXISTED;
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
            $pass = mysql_fetch_object($this->queryMysql($query));
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

    public function checkLoginByPhone($phone, $password) {
        //todo
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

    private function isUserExistsByPhone($phone) {
        $stmt = $this->conn->prepare("SELECT id from users WHERE phone = ?");
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
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
            $q = mysql_fetch_object($this->queryMysql($query));
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

    public function getUserByPhone($phone) {
      //todo

    }

    /**
     * Fetching user api key
     * @param String $user_id user id primary key in user table
     */
    public function getApiKeyById($user_id) {
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
            $user_id = mysql_fetch_object($this->queryMysql($query))->id;
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
    public function createPost($user_id, $title, $content, $price, $price_currency, $idCategory, $idSubcategory, $city, $country) {

        $query = "INSERT INTO posts VALUES(NULL, '$title', '$content', '$price', '$price_currency', now(), 0, now(), '$idCategory', '$idSubcategory', 0, '$city', '$country', '$user_id')";
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

        $query = "SELECT p.ID as id, p.title, p.content, p.price, p.pricecurrency, p.created_at, p.status as post_status, p.statusChangeDate, p.idCategory, p.idSubCategory, p.hitcount, p.city, p.country, p.idUser, u.name, u.email, u.phone,
u.api_key, u.status as user_status, u.created_at FROM posts AS p LEFT JOIN users as u ON p.idUser = u.ID WHERE p.ID='$post_id'";
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

    public function getPostsByCategory($category_id, $page) {

        $query = "SELECT p.ID as id, p.title, p.content, p.price, p.pricecurrency, p.created_at, p.status as post_status, p.statusChangeDate, p.idCategory, p.idSubCategory, p.hitcount, p.city, p.country, p.idUser, u.ID as user_id, u.name, u.email, u.phone, u.api_key, u.status as user_status, u.created_at as user_created_at FROM posts AS p LEFT JOIN users as u ON p.idUser = u.ID WHERE p.idCategory='$category_id'";
        //paging
        $num_rec_per_page = NUM_REC_PER_PAGE;
        //$advsQ = queryMysql($query);
        //$total_records = mysql_num_rows($advsQ);  //count number of records
        //$total_pages = ceil($total_records / $num_rec_per_page);
        $start_from = ($page-1) * $num_rec_per_page;
        $query = $query." LIMIT  $start_from, $num_rec_per_page";
        //end of paging
        $result = $this->queryMysql($query);

        return $result;
    }
    public function getPostsBySubCategory($subcategory_id, $page) {

        $query = "SELECT p.ID as id, p.title, p.content, p.price, p.pricecurrency, p.created_at, p.status as post_status, p.statusChangeDate, p.idCategory, p.idSubCategory, p.hitcount, p.city, p.country, p.idUser, u.ID as user_id, u.name, u.email, u.phone, u.api_key, u.status as user_status, u.created_at as user_created_at FROM posts AS p LEFT JOIN users as u ON p.idUser = u.ID WHERE p.idSubCategory='$subcategory_id'";
        //paging
        $num_rec_per_page = NUM_REC_PER_PAGE;
        $start_from = ($page-1) * $num_rec_per_page;
        $query = $query." LIMIT  $start_from, $num_rec_per_page";
        //end of paging
        $result = $this->queryMysql($query);

        return $result;
    }

    public function getImagesByPost($post_id) {

        $query = "SELECT * FROM images WHERE idPost='$post_id'";
        $result = $this->queryMysql($query);

        return $result;
    }

    public function getImages() {

        $query = "SELECT * FROM images";
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

    public function updatePost($user_id, $post_id, $status)
    {
        $query = "UPDATE posts SET status = '$status' WHERE id = '$post_id'";
        $result = $this->queryMysql($query);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function deletePost($post_id) {

        $query = "DELETE FROM posts WHERE id = '$post_id'";
        $result = $this->queryMysql($query);

        if ($result) {
            return true;
        } else {
            return false;
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
        $result = mysql_query($query) or die(mysql_error());
        return $result;
    }
}

?>
