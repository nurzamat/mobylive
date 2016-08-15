<?php

require_once '../include/DbHandler.php';
require_once '../include/PassHash.php';
require '../libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

// User id from db - Global Variable
$user_id = NULL;

/**
 * Adding Middle Layer to authenticate every request
 * Checking if the request has valid api key in the 'Authorization' header
 */
function authenticate(\Slim\Route $route) {
    // Getting request headers
    $headers = apache_request_headers();
    $response = array();
    $app = \Slim\Slim::getInstance();

    // Verifying Authorization Header
    if (isset($headers['Authorization'])) {
        $db = new DbHandler();

        // get the api key
        $api_key = $headers['Authorization'];
        // validating api key
        if (!$db->isValidApiKey($api_key)) {
            // api key is not present in users table
            $response["error"] = true;
            $response["message"] = "Access Denied. Invalid Api key";
            echoRespnse(401, $response);
            $app->stop();
        } else {
            global $user_id;
            // get user primary key id
            $user_id = $db->getUserId($api_key);
        }
    } else {
        // api key is missing in header
        $response["error"] = true;
        $response["message"] = "Api key is misssing";
        echoRespnse(400, $response);
        $app->stop();
    }
}

/**
 * ----------- METHODS WITHOUT AUTHENTICATION ---------------------------------
 */
/**
 * User Registration
 * url - /register
 * method - POST
 * params - name, email, password
 */
$app->post('/register', function() use ($app) {
    // check for required params
    //verifyRequiredParams(array('name', 'email', 'phone', 'password'));

    $req = $app->request();
    $body = json_decode($req->getBody());
    // reading post params
    /*
    $name = $app->request()->post('name');
    $email = $app->request()->post('email');
    $phone = $app->request()->post('phone');
    $password = $app->request()->post('password');
    */
    $username = $body->username;
    $email = $body->email;
    $password = $body->password;

    // validating email address
    validateEmail($email);
    //todo validate username

    $db = new DbHandler();
    $response = $db->createUser($username, $email, $password);

    if ($response["message"] == USER_CREATED_SUCCESSFULLY) {
        $response["error"] = false;
        $response["message"] = "You are successfully registered";
    } else if ($response["message"] == USER_CREATE_FAILED) {
        $response["error"] = true;
        $response["message"] = "Oops! An error occurred while registering";
    } else if ($response["message"] == USER_ALREADY_EXISTED) {
        $response["error"] = true;
        $response["message"] = "Sorry, this email already existed";
    }
    // echo json response
    echoRespnse(201, $response);
});

/**
 * User Login
 * url - /login
 * method - POST
 * params - email, password
 */
$app->post('/login', function() use ($app) {
    // check for required params
    //verifyRequiredParams(array('email', 'password'));
    $req = $app->request();
    $body = json_decode($req->getBody());
    // reading post params
    $email = $body->email;
    $password = $body->password;
    //$email = $app->request()->post('email');
    //$password = $app->request()->post('password');
    $response = array();

    $db = new DbHandler();
    // check for correct email and password
    if ($db->checkLoginByEmail($email, $password)) {
        // get the user by email
        $user = $db->getUserByEmail($email);

        if ($user != NULL) {
            $response["error"] = false;
            $response['name'] = $user['name'];
            $response['email'] = $user['email'];
            $response['phone'] = $user['phone'];
            $response['apiKey'] = $user['api_key'];
            $response['created_at'] = $user['created_at'];
        } else {
            // unknown error occurred
            $response['error'] = true;
            $response['message'] = "An error occurred. Please try again";
        }
    } else {
        // user credentials are wrong
        $response['error'] = true;
        //$response['message'] = 'Login failed. Incorrect credentials';
        $response['message'] = 'Login failed. Incorrect credentials';
    }

    echoRespnse(200, $response);
});

/*
 * ------------------------ METHODS WITH AUTHENTICATION ------------------------
 */

/**
 * Listing all posts of particual user
 * method GET
 * url /posts
 */
$app->get('/posts', 'authenticate', function() {
    global $user_id;
    $response = array();
    $db = new DbHandler();

    // fetching all user posts
    $result = $db->getAllUserPosts($user_id);

    $response["error"] = false;
    $response["posts"] = array();

    // looping through result and preparing posts array
    while ($post = mysql_fetch_object($result)) {
        $tmp = array();
        $tmp["id"] = $post->ID;
        $tmp["title"] = $post->title;
        $tmp["content"] = $post->content;
        $tmp["price"] = $post->price;
        array_push($response["posts"], $tmp);
    }

    echoRespnse(200, $response);
});
/**
 * Updating particular post's hitcount
 * method GET
 * url /posts/:id/hitcount
 */
$app->get('/posts/:id/hitcount/:user_id', function($post_id, $user_id) {

    $db = new DbHandler();

    // updating post hitcount
    $response = $db->updatePostsHitcount($post_id, $user_id);

    echoRespnse(200, $response);
});

/**
 * Like Job
 * method GET
 * url /posts/:id/:user_id/like/:like
 */
$app->get('/posts/:id/:user_id/like/:like', function($post_id, $user_id, $like) {

    $db = new DbHandler();

    // updating like
    $result = $db->updateLikes($post_id, $user_id, $like);

    if ($result) {
        // likes updated successfully
        $response["error"] = false;
        $response["message"] = "Likes updated successfully";
    } else {
        // task failed to update
        $response["error"] = true;
        $response["message"] = "Likes failed to update. Please try again!";
    }

    echoRespnse(200, $response);
});

/**
 * Listing posts of particual user by page
 * method GET
 * url /posts/page/:page
 */
$app->get('/posts/user/:id/:page', function($user_id, $page) {

    $db = new DbHandler();

    // fetching all user posts
    $result_posts = $db->getUserPostsByPage($user_id, $page);
    $result_images = $db->getImages();

    $response = getPosts($result_posts, $result_images);

    echoRespnse(200, $response);
});

/**
 * Listing liked posts of particual user by page
 * method GET
 */
$app->get('/posts/user/:id/likes/:page', function($user_id, $page) {

    $db = new DbHandler();

    $response = array();
    $response["error"] = false;
    $response["posts"] = array();

    $result_posts = $db->getUserLikedPostsByPage($user_id, $page);
    $result_images = $db->getImages();

    if($result_posts != null)
    $response = getPosts($result_posts, $result_images);

    echoRespnse(200, $response);
});

/**
 * Listing all posts
 * method GET
 * url /posts/
 */
$app->get('/posts/:page/:params', function($page, $params) {

    $db = new DbHandler();

    // fetching all category posts
    $result_posts = $db->getPosts($page, sanitizeString($params));
    $result_images = $db->getImages();

    $response = getPosts($result_posts, $result_images);

    echoRespnse(200, $response);
});
/**
 * Listing all posts of particual category
 * method GET
 * url /posts/category/:id
 */
$app->get('/posts/category/:id/:page/:params', function($category_id, $page, $params) {

    $db = new DbHandler();

    // fetching all category posts
    $result_posts = $db->getPostsByCategory($category_id, $page, sanitizeString($params));
    $result_images = $db->getImages();

    $response = getPosts($result_posts, $result_images);

    echoRespnse(200, $response);
});

/**
 * Listing all posts of particual subcategory
 * method GET
 * url /posts/subcategory/:id
 */
$app->get('/posts/subcategory/:id/:page/:params', function($subcategory_id, $page, $params) {

    $db = new DbHandler();

    // fetching subcategory posts
    $result_posts = $db->getPostsBySubCategory($subcategory_id, $page, sanitizeString($params));
    $result_images = $db->getImages();

    $response = getPosts($result_posts, $result_images);

    echoRespnse(200, $response);
});

/**
 * Listing single post of particual user
 * method GET
 * url /posts/:id
 * Will return 404 if the post doesn't belongs to user
 */
$app->get('/posts/:id', 'authenticate', function($post_id) {
    global $user_id;
    $response = array();
    $db = new DbHandler();

    // fetch post
    $result = $db->getPost($post_id);

    if ($result != NULL) {
        $response["error"] = false;
        $response["id"] = $result["id"];
        $response["title"] = $result["title"];
        $response["content"] = $result["content"];
        $response["price"] = $result["price"];
        echoRespnse(200, $response);
    } else {
        $response["error"] = true;
        $response["message"] = "The requested resource doesn't exists";
        echoRespnse(404, $response);
    }
});

/**
 * Creating new post in db
 * method POST
 * params - name
 * url - /posts/
 */
$app->post('/posts', 'authenticate', function() use ($app) {
    // check for required params
    //verifyRequiredParams(array('post'));
    global $user_id;
    $response = array();

    $req = $app->request();
    $body = json_decode($req->getBody());
    // reading post params

    $title = $body->title;
    $content = $body->content;
    $price = $body->price;
    $price_currency = $body->price_currency;
    $idCategory = $body->idCategory;
    $idSubcategory = $body->idSubcategory;
    $idSubSubcategory = 0;
    $city = $body->city;
    $country = $body->country;
    $actionType = $body->actionType;
    $sex = $body->sex;
    $birth_year = $body->birth_year;
    $displayed_name = $body->displayed_name;

    $db = new DbHandler();
    // creating new task
    $post_id = $db->createPost($user_id, $title, $content, $price, $price_currency, $idCategory, $idSubcategory, $idSubSubcategory, $city, $country, $actionType, $sex, $birth_year, $displayed_name);

    if ($post_id != NULL) {
        $response["error"] = false;
        $response["message"] = "Post created successfully";
        $response["post_id"] = $post_id;
        echoRespnse(201, $response);
    } else {
        $response["error"] = true;
        $response["message"] = "Failed to create advertisement. Please try again";
        echoRespnse(200, $response);
    }
});
/**
 * Sending images
 * method POST
 * params - name
 * url - /posts/:id/images
 */
$app->post('/posts/:id/images', 'authenticate', function($post_id) use($app) {
    // check for required params
    //verifyRequiredParams(array('post'));
    global $user_id;

    $response = array();

    if (isset($_FILES['image']['name']) && $_FILES["image"]["size"] < 5000000)
    {
        $name = uniqid('img-'.date('Ymd').'-').".jpg";
        $saveto = "../media/"."$name";

        try {
            // Throws exception incase file is not being moved
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $saveto)) {
                // make error flag true
                $response['error'] = true;
                $response['message'] = 'Could not move the file!';
            }
            else
            {
                $typeok = TRUE;
                $filename = stripslashes($_FILES['image']['name']);
                $extension = getExtension($filename);

                if ($extension == "jpg" || $extension == "jpeg")
                {
                    $src = imagecreatefromjpeg($saveto);
                }
                else if ($extension == "gif" )
                {
                    $src = imagecreatefromgif($saveto);
                }
                else if ($extension == "png" )
                {
                    $src = imagecreatefrompng($saveto);
                }
                else $typeok = FALSE;

                if ($typeok)
                {
                    list($w, $h) = getimagesize($saveto);
                    $max = MAX_IMAGE_SIZE;
                    $tw  = $w;
                    $th  = $h;

                    if ($w > $h && $max < $w)
                    {
                        $th = $max / $w * $h;
                        $tw = $max;
                    }
                    elseif ($h > $w && $max < $h)
                    {
                        $tw = $max / $h * $w;
                        $th = $max;
                    }
                    elseif ($max < $w)
                    {
                        $tw = $th = $max;
                    }

                    $tmp = imagecreatetruecolor($tw, $th);
                    imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
                    imageconvolution($tmp, array( // Sharpen image
                        array(-1, -1, -1),
                        array(-1, 16, -1),
                        array(-1, -1, -1)
                    ), 8, 0);
                    imagejpeg($tmp, $saveto);
                    imagedestroy($tmp);
                    imagedestroy($src);
                }
                //write to db
                $db = new DbHandler();
                $image_id = $db->createImage($post_id, $name);
                if($image_id != NULL)
                {
                    $response['message'] = 'File uploaded successfully!';
                    $response['error'] = false;
                    $response['image_id'] = $image_id;
                    //$response['file_path'] = $saveto;
                    //$response['image'] = basename($_FILES['image']['name']);
                }
                else
                {
                    $response["error"] = true;
                    $response["message"] = "Failed to create image in db. Please try again";
                }
            }
        } catch (Exception $e) {
            // Exception occurred. Make error flag true
            $response['error'] = true;
            $response['message'] = $e->getMessage();
        }
    } else {
        // File parameter is missing
        $response['error'] = true;
        $response['message'] = 'Not received any file!F';
    }

    echo json_encode($response);
});

/**
 * Updating existing post
 * method PUT
 * url - /posts/:id
 */
$app->put('/posts/:id', 'authenticate', function($post_id) use($app) {
    // check for required params
    //verifyRequiredParams(array('post', 'status'));
    $response = array();
    $req = $app->request();
    $body = json_decode($req->getBody());
    // reading put params
    $title = $body->title;
    $content = $body->content;
    $price = $body->price;
    $pricecurrency = $body->price_currency;
    $idCategory = $body->idCategory;
    $idSubcategory = $body->idSubcategory;
    $idSubSubcategory = 0;
    //$city = $body->city;
    //$country = $body->country;

    global $user_id;
    $db = new DbHandler();
    // updating post
    $result = $db->updatePost($post_id, $title, $content, $price, $pricecurrency, $idCategory, $idSubcategory, $idSubSubcategory);
    if ($result) {
        // task updated successfully
        $response["error"] = false;
        $response["message"] = "Post updated successfully";
    } else {
        // task failed to update
        $response["error"] = true;
        $response["message"] = "Post failed to update. Please try again!";
    }
    echoRespnse(200, $response);
});
/**
 * Deleting post. Users can delete only their posts
 * method DELETE
 * url /posts
 */
$app->delete('/posts/:id', 'authenticate', function($post_id) use($app) {
    global $user_id;

    $db = new DbHandler();
    $response = array();
    $result = $db->deletePost($post_id);
    if ($result) {
        //deleting images
        $result_images = $db->deleteImagesByPost($post_id);
        while ($image = mysql_fetch_object($result_images))
        {
            $target = "../media/".$image->name;
            if (file_exists($target))
            {
                unlink($target);
            }
        }

        // post deleted successfully
        $response["error"] = false;
        $response["message"] = "Post deleted succesfully";
    } else {
        // post failed to delete
        $response["error"] = true;
        $response["message"] = "Post failed to delete. Please try again!";
    }
    echoRespnse(200, $response);
});
/**
 * Deleting post image. Users can delete only their images
 * method DELETE
 * url /images
 */
$app->delete('/images/:id', 'authenticate', function($image_id) use($app) {
    global $user_id;

    $db = new DbHandler();
    $image_result = $db->getImage($image_id);
    $image = mysql_fetch_object($image_result);

    $target = "../media/".$image->name;
    $result = false;
    if (file_exists($target)) {
        unlink($target); // Delete now
        $result = $db->deleteImage($image_id);
    }

    $response = array();
    if ($result) {
        // post deleted successfully
        $response["error"] = false;
        $response["message"] = "Image deleted succesfully";
    } else {
        // post failed to delete
        $response["error"] = true;
        $response["message"] = "Image failed to delete. Please try again!";
    }
    echoRespnse(200, $response);
});

/**
 * Listing user info
 * method GET
 * url /users/:id
 * Will return 404 if the post doesn't belongs to user
 */
$app->get('/users/:id', 'authenticate', function($post_id) {

    //todo
    /*
    global $user_id;
    $response = array();
    $db = new DbHandler();

    // fetch post
    $result = $db->getPost($post_id);

    if ($result != NULL) {
        $response["error"] = false;
        $response["id"] = $result["id"];
        $response["title"] = $result["title"];
        $response["content"] = $result["content"];
        $response["price"] = $result["price"];
        echoRespnse(200, $response);
    } else {
        $response["error"] = true;
        $response["message"] = "The requested resource doesn't exists";
        echoRespnse(404, $response);
    }
    */
});

/**
 * Listing all categories
 * method GET
 * url /categories
 */
$app->get('/categories', function() {

    $response = array();
    $db = new DbHandler();

    // fetching all user posts
    $result_cat = $db->getCategories();
    $result_subcat = $db->getSubCategories();
    $subcat_arr = array();
    while ($subcat = mysqli_fetch_object($result_subcat))
    {
        $tmp_sub = array();
        $tmp_sub["id"] = $subcat->ID;
        $tmp_sub["name"] = $subcat->name;
        $tmp_sub["idCategory"] = $subcat->idCategory;
        array_push($subcat_arr, $tmp_sub);
    }

    $response["error"] = false;
    $response["categories"] = array();

    // looping through result and preparing posts array
    while ($cat = mysqli_fetch_object($result_cat)) {

        $subcat_tmp = array();
        $tmp = array();

        for ($i = 0; $i < count($subcat_arr); $i++)
        {
            if($subcat_arr[$i]["idCategory"] == $cat->ID)
            {
                array_push($subcat_tmp, $subcat_arr[$i]);
            }
        }

        $tmp["id"] = $cat->ID;
        $tmp["name"] = $cat->name;
        $tmp["subcategories"] = $subcat_tmp;
        array_push($response["categories"], $tmp);
    }

    echoRespnse(200, $response);
});

/**
 * Verifying required params posted or not
 */
function verifyRequiredParams($required_fields) {
    $error = false;
    $error_fields = "";
    $request_params = array('name','email','phone','password');
    $request_params = $_REQUEST;
    // Handling PUT request params
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $app = \Slim\Slim::getInstance();
        parse_str($app->request()->getBody(), $request_params);
    }
    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }

    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["error"] = true;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        echoRespnse(400, $response);
        $app->stop();
    }
}

/**
 * Validating email address
 */
function validateEmail($email) {
    $app = \Slim\Slim::getInstance();
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["error"] = true;
        $response["message"] = 'Email address is not valid';
        echoRespnse(400, $response);
        $app->stop();
    }
}
/**
 * get extension
 */
function getExtension($str) {

    $i = strrpos($str,".");
    if (!$i) { return ""; }

    $l = strlen($str) - $i;
    $ext = substr($str,$i+1,$l);
    $ext = strtolower($ext);
    return $ext;
}

/**
 * Echoing json response to client
 * @param String $status_code Http response code
 * @param Int $response Json response
 */
function echoRespnse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);

    // setting response content type to json
    $app->contentType('application/json');

    echo json_encode($response);
}

function sanitizeString($var)
{
    /*
    $var = strip_tags($var);
    //$var = htmlentities($var);
    $var = htmlentities($var, ENT_QUOTES, "UTF-8");
    $var = stripslashes($var);
    return mysql_real_escape_string($var);
    */
    return $var;
}


function getPosts($result_posts, $result_images)
{
    $response = array();

    $images_arr = array();

    $response["error"] = false;
    $response["posts"] = array();

    try
    {
        while ($image = mysql_fetch_object($result_images))
        {
            $tmp_sub = array();
            $tmp_sub["id"] = $image->ID;
            $tmp_sub["original_image"] = $image->name;
            $tmp_sub["idPost"] = $image->idPost;
            array_push($images_arr, $tmp_sub);
        }

        // looping through result and preparing posts array
        while ($post = mysql_fetch_object($result_posts))
        {
            $tmp = array();
            $images_tmp = array();

            for ($i = 0; $i < count($images_arr); $i++)
            {
                if($images_arr[$i]["idPost"] == $post->id)
                {
                    array_push($images_tmp, $images_arr[$i]);
                }
            }

            $tmp["id"] = $post->id;
            $tmp["title"] = $post->title;
            $tmp["content"] = $post->content;
            $tmp["price"] = $post->price;
            $tmp["price_currency"] = $post->pricecurrency;
            $tmp["created_at"] = $post->created_at;
            $tmp["id_category"] = $post->idCategory;
            $tmp["id_subcategory"] = $post->idSubCategory;
            $tmp["post_status"] = $post->post_status;
            $tmp["hitcount"] = $post->hitcount;
            $tmp["city"] = $post->city;
            $tmp["country"] = $post->country;
            $tmp["sex"] = $post->sex;
            $tmp["birth_year"] = $post->birth_year;
            $tmp["displayed_name"] = $post->displayed_name;
            $tmp["user_id"] = $post->user_id;
            $tmp["user_name"] = $post->name;
            $tmp["user_username"] = $post->username;
            $tmp["user_email"] = $post->email;
            $tmp["user_phone"] = $post->phone;
            $tmp["user_status"] = $post->user_status;
            $tmp["images"] = $images_tmp;

            array_push($response["posts"], $tmp);
        }
    }
    catch (Exception $e) {
        // Exception occurred. Make error flag true
        $response['error'] = true;
        $response['message'] = $e->getMessage();
    }

    return $response;
}


///////////////////////////////////////////////////
//START CHAT
///////////////////////////////////////////////////
// User login
$app->post('/user/login', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('name', 'email', 'password'));

    // reading post params
    $name = $app->request->post('name');
    $email = $app->request->post('email');
    $password = $app->request->post('password');

    // validating email address
    validateEmail($email);

    $db = new DbHandler();
    $response = $db->createUser($name, $email, $password);

    // echo json response
    echoRespnse(200, $response);
});


/* * *
 * Updating user
 *  we use this url to update user's gcm registration id
 */
$app->put('/user/:id', function($user_id) use ($app) {
    global $app;

    verifyRequiredParams(array('gcm_registration_id'));

    $gcm_registration_id = $app->request->put('gcm_registration_id');

    $db = new DbHandler();
    $response = $db->updateGcmID($user_id, $gcm_registration_id);

    echoRespnse(200, $response);
});

/* * *
 * fetching all chat rooms
 */
$app->get('/chat_rooms', function() {
    $response = array();
    $db = new DbHandler();

    // fetching all user tasks
    $result = $db->getAllChatrooms();

    $response["error"] = false;
    $response["chat_rooms"] = array();

    // pushing single chat room into array
    while ($chat_room = $result->fetch_assoc()) {
        $tmp = array();
        $tmp["chat_room_id"] = $chat_room["chat_room_id"];
        $tmp["name"] = $chat_room["name"];
        $tmp["created_at"] = $chat_room["created_at"];
        array_push($response["chat_rooms"], $tmp);
    }

    echoRespnse(200, $response);
});

/* * *
 * fetching user chats //nur1
 */
$app->get('/users/:id/chats', function($user_id) {

    $db = new DbHandler();
    // fetching all user chats
    //$response = $db->getUserChatsWithPost($user_id);
    $response = $db->getUserChats($user_id);

    echoRespnse(200, $response);
});

/**
 * Messaging in a chat room
 * Will send push notification using Topic Messaging
 *  */
$app->post('/chat_rooms/:id/message', function($chat_room_id) {
    global $app;
    $db = new DbHandler();

    verifyRequiredParams(array('user_id', 'message'));

    $user_id = $app->request->post('user_id');
    $message = $app->request->post('message');

    $response = $db->addMessage($user_id, $chat_room_id, $message);

    if ($response['error'] == false) {
        require_once '../libs/gcm/gcm.php';
        require_once '../libs/gcm/push.php';
        $gcm = new GCM();
        $push = new Push();

        // get the user using userid
        $user = $db->getUser($user_id);

        $data = array();
        $data['user'] = $user;
        $data['message'] = $response['message'];
        $data['chat_room_id'] = $chat_room_id;

        $push->setTitle("Arzymo");
        $push->setIsBackground(FALSE);
        $push->setFlag(PUSH_FLAG_CHATROOM);
        $push->setData($data);

        // echo json_encode($push->getPush());exit;
        // sending push message to a topic
        $gcm->sendToTopic('topic_' . $chat_room_id, $push->getPush());

        $response['user'] = $user;
        $response['error'] = false;
    }

    echoRespnse(200, $response);
});


/**
 * Sending push notification to a single user
 * We use user's gcm registration id to send the message
 * * */
$app->post('/users/:id/message', function($to_user_id) {
    global $app;
    $db = new DbHandler();

    verifyRequiredParams(array('message'));

    $from_user_id = $app->request->post('user_id');
    $message = $app->request->post('message');

    require_once '../libs/gcm/gcm.php';
    require_once '../libs/gcm/push.php';
    $gcm = new GCM();
    $push = new Push();

    $fromuser = $db->getUser($from_user_id);
    $user = $db->getUser($to_user_id);

    $msg = array();
    $msg['message'] = $message;
    $msg['message_id'] = '';
    $msg['chat_room_id'] = '';
    $msg['created_at'] = date('Y-m-d G:i:s');

    $data = array();
    $data['user'] = $fromuser;
    $data['message'] = $msg;
    $data['image'] = '';

    $push->setTitle("Google Cloud Messaging");
    $push->setIsBackground(FALSE);
    $push->setFlag(PUSH_FLAG_USER);
    $push->setData($data);

    // sending push message to single user
    $gcm->send($user['gcm_registration_id'], $push->getPush());

    $response['user'] = $user;
    $response['error'] = false;


    echoRespnse(200, $response);
});

$app->post('/users/push_test', function() {
    global $app;

    verifyRequiredParams(array('message', 'api_key', 'token'));

    $message = $app->request->post('message');
    $apiKey = $app->request->post('api_key');
    $token = $app->request->post('token');
    $image = $app->request->post('include_image');

    $data = array();
    $data['title'] = 'Google Cloud Messaging';
    $data['message'] = $message;
    if ($image == 'true') {
        $data['image'] = 'http://api.androidhive.info/gcm/panda.jpg';
    } else {
        $data['image'] = '';
    }
    $data['created_at'] = date('Y-m-d G:i:s');

    $fields = array(
        'to' => $token,
        'data' => $data,
    );

    // Set POST variables
    $url = 'https://fcm.googleapis.com/fcm/send';

    $headers = array(
        'Authorization: key=' . $apiKey,
        'Content-Type: application/json'
    );
    // Open connection
    $ch = curl_init();

    // Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Disabling SSL Certificate support temporarly
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    $response = array();

    // Execute post
    $result = curl_exec($ch);
    if ($result === FALSE) {
        $response['error'] = TRUE;
        $response['message'] = 'Unable to send test push notification';
        echoRespnse(200, $response);
        exit;
    }

    // Close connection
    curl_close($ch);

    $response['error'] = FALSE;
    $response['message'] = 'Test push message sent successfully!';

    echoRespnse(200, $response);
});


/**
 * Sending push notification to multiple users
 * We use gcm registration ids to send notification message
 * At max you can send message to 1000 recipients
 * * */
$app->post('/users/message', function() use ($app) {

    $response = array();
    verifyRequiredParams(array('user_id', 'to', 'message'));

    require_once '../libs/gcm/gcm.php';
    require_once '../libs/gcm/push.php';

    $db = new DbHandler();

    $user_id = $app->request->post('user_id');
    $to_user_ids = array_filter(explode(',', $app->request->post('to')));
    $message = $app->request->post('message');

    $user = $db->getUser($user_id);
    $users = $db->getUsers($to_user_ids);

    $registration_ids = array();

    // preparing gcm registration ids array
    foreach ($users as $u) {
        array_push($registration_ids, $u['gcm_registration_id']);
    }

    // insert messages in db
    // send push to multiple users
    $gcm = new GCM();
    $push = new Push();

    // creating tmp message, skipping database insertion
    $msg = array();
    $msg['message'] = $message;
    $msg['message_id'] = '';
    $msg['chat_room_id'] = '';
    $msg['created_at'] = date('Y-m-d G:i:s');

    $data = array();
    $data['user'] = $user;
    $data['message'] = $msg;
    $data['image'] = '';

    $push->setTitle("Google Cloud Messaging");
    $push->setIsBackground(FALSE);
    $push->setFlag(PUSH_FLAG_USER);
    $push->setData($data);

    // sending push message to multiple users
    $gcm->sendMultiple($registration_ids, $push->getPush());

    $response['error'] = false;

    echoRespnse(200, $response);
});

$app->post('/users/send_to_all', function() use ($app) {

    $response = array();
    verifyRequiredParams(array('user_id', 'message'));

    require_once '../libs/gcm/gcm.php';
    require_once '../libs/gcm/push.php';

    $db = new DbHandler();

    $user_id = $app->request->post('user_id');
    $message = $app->request->post('message');

    require_once '../libs/gcm/gcm.php';
    require_once '../libs/gcm/push.php';
    $gcm = new GCM();
    $push = new Push();

    // get the user using userid
    $user = $db->getUser($user_id);

    // creating tmp message, skipping database insertion
    $msg = array();
    $msg['message'] = $message;
    $msg['message_id'] = '';
    $msg['chat_room_id'] = '';
    $msg['created_at'] = date('Y-m-d G:i:s');

    $data = array();
    $data['user'] = $user;
    $data['message'] = $msg;
    $data['image'] = 'http://api.androidhive.info/gcm/panda.jpg';

    $push->setTitle("Google Cloud Messaging");
    $push->setIsBackground(FALSE);
    $push->setFlag(PUSH_FLAG_USER);
    $push->setData($data);

    // sending message to topic `global`
    // On the device every user should subscribe to `global` topic
    $gcm->sendToTopic('global', $push->getPush());

    $response['user'] = $user;
    $response['error'] = false;

    echoRespnse(200, $response);
});

/**
 * Fetching single chat room including all the chat messages
 *  */
$app->get('/chat_rooms/:id', function($chat_room_id) {
    global $app;
    $db = new DbHandler();

    $result = $db->getChatRoom($chat_room_id);

    $response["error"] = false;
    $response["messages"] = array();
    $response['chat_room'] = array();

    $i = 0;
    // looping through result and preparing tasks array
    while ($chat_room = $result->fetch_assoc()) {
        // adding chat room node
        if ($i == 0) {
            $tmp = array();
            $tmp["chat_room_id"] = $chat_room["chat_room_id"];
            $tmp["name"] = $chat_room["name"];
            $tmp["created_at"] = $chat_room["chat_room_created_at"];
            $response['chat_room'] = $tmp;
        }

        if ($chat_room['user_id'] != NULL) {
            // message node
            $cmt = array();
            $cmt["message"] = $chat_room["message"];
            $cmt["message_id"] = $chat_room["message_id"];
            $cmt["created_at"] = $chat_room["created_at"];

            // user node
            $user = array();
            $user['user_id'] = $chat_room['user_id'];
            $user['username'] = $chat_room['username'];
            $cmt['user'] = $user;

            array_push($response["messages"], $cmt);
        }
    }

    echoRespnse(200, $response);
});

/**
 * Verifying required params posted or not
 */
/*
function verifyRequiredParams($required_fields) {
    $error = false;
    $error_fields = "";
    $request_params = array();
    $request_params = $_REQUEST;
    // Handling PUT request params
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $app = \Slim\Slim::getInstance();
        parse_str($app->request()->getBody(), $request_params);
    }
    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }

    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["error"] = true;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        echoRespnse(400, $response);
        $app->stop();
    }
}
*/

function IsNullOrEmptyString($str) {
    return (!isset($str) || trim($str) === '');
}

///////////////////////////////////////////////////
//END CHAT
///////////////////////////////////////////////////
$app->run();
?>