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

    $response = array();
    $req = $app->request();
    $body = json_decode($req->getBody());

    // reading post params
    /*
    $name = $app->request()->post('name');
    $email = $app->request()->post('email');
    $phone = $app->request()->post('phone');
    $password = $app->request()->post('password');
    */
    $name = $body->name;
    $email = $body->email;
    $phone = $body->phone;
    $password = $body->password;

    // validating email address
    validateEmail($email);

    $db = new DbHandler();
    $res = $db->createUser($name, $email, $phone, $password);

    if ($res == USER_CREATED_SUCCESSFULLY) {
        $response["error"] = false;
        $response["message"] = "You are successfully registered";
    } else if ($res == USER_CREATE_FAILED) {
        $response["error"] = true;
        $response["message"] = "Oops! An error occurred while registering";
    } else if ($res == USER_ALREADY_EXISTED) {
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
 * Listing all posts of particual category
 * method GET
 * url /posts/category/:id
 */
$app->get('/posts/category/:id/:page', function($category_id, $page) {

    $response = array();
    $db = new DbHandler();

    // fetching all user posts
    $result = $db->getPostsByCategory($category_id, $page);

    $response["error"] = false;
    $response["posts"] = array();

    // looping through result and preparing posts array
    if (mysql_num_rows($result) > 0)
    {
        while ($post = mysql_fetch_object($result)) {
            $tmp = array();
            $tmp["id"] = $post->id;
            $tmp["title"] = $post->title;
            $tmp["content"] = $post->content;
            $tmp["price"] = $post->price;
            $tmp["pricecurrency"] = $post->pricecurrency;
            $tmp["created_at"] = $post->created_at;
            $tmp["post_status"] = $post->post_status;
            $tmp["hitcount"] = $post->hitcount;
            $tmp["city"] = $post->city;
            $tmp["country"] = $post->country;
            $tmp["user_id"] = $post->user_id;
            $tmp["user_name"] = $post->name;
            $tmp["user_phone"] = $post->phone;
            $tmp["user_status"] = $post->user_status;
            array_push($response["posts"], $tmp);
        }
    }
    echoRespnse(200, $response);
});

/**
 * Listing all posts of particual subcategory
 * method GET
 * url /posts/subcategory/:id
 */
$app->get('/posts/subcategory/:id/:page', function($subcategory_id, $page) {

    $response = array();
    $db = new DbHandler();

    // fetching all user posts
    $result = $db->getPostsBySubCategory($subcategory_id, $page);

    $response["error"] = false;
    $response["posts"] = array();

    // looping through result and preparing posts array
    if (mysql_num_rows($result) > 0)
    {
        while ($post = mysql_fetch_object($result)) {
            $tmp = array();
            $tmp["id"] = $post->ID;
            $tmp["title"] = $post->title;
            $tmp["content"] = $post->content;
            $tmp["price"] = $post->price;
            $tmp["pricecurrency"] = $post->pricecurrency;
            $tmp["created_at"] = $post->created_at;
            $tmp["post_status"] = $post->post_status;
            $tmp["hitcount"] = $post->hitcount;
            $tmp["city"] = $post->city;
            $tmp["country"] = $post->country;
            $tmp["user_id"] = $post->user_id;
            $tmp["user_name"] = $post->name;
            $tmp["user_phone"] = $post->phone;
            $tmp["user_status"] = $post->user_status;
            array_push($response["posts"], $tmp);
        }
    }

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
    $city = $body->city;
    $country = $body->country;

    global $user_id;
    $db = new DbHandler();

    // creating new task
    $post_id = $db->createPost($user_id, $title, $content, $price, $price_currency, $idCategory, $idSubcategory, $city, $country);

    if ($post_id != NULL) {
        $response["error"] = false;
        $response["message"] = "Post created successfully";
        $response["post_id"] = $post_id;
        echoRespnse(201, $response);
    } else {
        $response["error"] = true;
        $response["message"] = "Failed to create post. Please try again";
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
        $name = uniqid('img-'.date('Ymd').'-');
        $saveto = "../media/"."$name.jpg";

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
                // File successfully uploaded
                $response['message'] = 'File uploaded successfully!';
                $response['error'] = false;
                $response['file_path'] = $saveto;
                $response['image'] = basename($_FILES['image']['name']);
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
    //end ravi

    //$db = new DbHandler();

    // creating new task
    //$post_id = 1;//$db->createPost($user_id, $title, $content, $price, $price_currency, $idCategory, $idSubcategory, $city, $country);

   // if ($post_id != NULL) {
   //     $response["error"] = false;
   //     $response["message"] = "Post created successfully";
   //     $response["post_id"] = $post_id;
   //     echoRespnse(201, $response);
   // } else {
   //     $response["error"] = true;
   //     $response["message"] = "Failed to create post. Please try again";
   //     echoRespnse(200, $response);
   // }
});

/**
 * Updating existing post
 * method PUT
 * params task, status
 * url - /posts/:id
 */
$app->put('/posts/:id', 'authenticate', function($post_id) use($app) {
    // check for required params
    //verifyRequiredParams(array('post', 'status'));

    global $user_id;

    $req = $app->request();
    $body = json_decode($req->getBody());
    $status = $body->status;

    $db = new DbHandler();
    $response = array();

    // updating task
    $result = $db->updatePost($user_id, $post_id, $status);
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
 * Deleting task. Users can delete only their tasks
 * method DELETE
 * url /tasks
 */
$app->delete('/posts/:id', 'authenticate', function($post_id) use($app) {
    global $user_id;

    $db = new DbHandler();
    $response = array();
    $result = $db->deletePost($post_id);
    if ($result) {
        // task deleted successfully
        $response["error"] = false;
        $response["message"] = "Post deleted succesfully";
    } else {
        // task failed to delete
        $response["error"] = true;
        $response["message"] = "Post failed to delete. Please try again!";
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

    $response["error"] = false;
    $response["categories"] = array();

    // looping through result and preparing posts array
    while ($cat = mysql_fetch_object($result_cat)) {

        $subcat_tmp = array();
        $tmp = array();

        while ($subcat = mysql_fetch_object($result_subcat))
        {
            $tmp_sub = array();
         if($subcat->idCategory == $cat->ID)
         {
             $tmp_sub["id"] = $subcat->ID;
             $tmp_sub["name"] = $subcat->name;
             $tmp_sub["idCategory"] = $subcat->idCategory;
             array_push($subcat_tmp, $tmp_sub);
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

$app->run();
?>