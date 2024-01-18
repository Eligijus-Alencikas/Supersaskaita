<?php

require_once(Config::$get["path"] . "includes/view.php");
require_once(Config::$get["path"] . "includes/db.php");
require_once(Config::$get["path"] . "includes/userAuth.php");
require_once(Config::$get["path"] . "includes/validator.php");
require_once(Config::$get["path"] . "includes/email.php");
require_once(Config::$get["path"] . "includes/userBills.php");

class Controller
{
    public array $logged_in_pages;
    public array $logged_out_pages;
    public array $pages_for_all;
    public array $uri;
    public array $page_url;
    public bool $logged_in;
    public int $user_id;

    public Create_page $not_found;

    private Database $db;
    private UserAuth $ua;
    private Validator $va;
    private Email $em;
    private UserBills $ub;

    public function __construct()
    {
        // create classes
        $this->va = new Validator();
        $this->db = new Database(Config::$get["db_host"], Config::$get["db_user"], Config::$get["db_password"], Config::$get["db_name"]);
        $this->ua = new UserAuth($this->db, $this->va);
        $this->em = new Email();
        $this->ub = new UserBills($this->db);

        $logged_in_nav = ['home' => 'home', 'Išankstinė sąskaita serija' => 'Isankstine-saskaita-serija', 'mano Išankstinės sąskaitos serijos' => 'mano-saskaitos-serijos'];

        // create pages
        $this->logged_out_pages["login"] = new Create_page("login", js_filenames: ["login.js"]);
        $this->logged_out_pages["signup"] = new Create_page("signup", js_filenames: ["signup.js"]);
        $this->logged_out_pages["submit-signup"] = new Create_page("submit_signup");
        $this->logged_out_pages["new-password"] = new Create_page("reset-pass", js_filenames: ["new_pass.js"]);
        $this->logged_out_pages["password-reset-sent"] = new Create_page("pass-re-sent");
        $this->logged_out_pages["reset-password"] = new Create_page("enter-new-pass", js_filenames: ["reset_pass.js"]);
        $this->logged_out_pages["password-changed"] = new Create_page("pass-has-changed");
        $this->logged_in_pages["home"] = new Create_page("home", js_filenames: ["home.js"], use_nav: true, nav_elem: $logged_in_nav);
        $this->logged_in_pages["Isankstine-saskaita-serija"] = new Create_page("bill_1", js_filenames: ["bill_1.js"], use_nav: true, nav_elem: $logged_in_nav);
        $this->logged_in_pages["mano-saskaitos-serijos"] = new Create_page("my_bill_1", js_filenames: ["my_bill_1.js"], use_nav: true, nav_elem: $logged_in_nav);
        $this->logged_in_pages["edit-bill"] = new Create_page("bill_1", js_filenames: ["edit_bill.js"], use_nav: true, nav_elem: $logged_in_nav);
        $this->pages_for_all["about"] = new Create_page("about");
        $this->pages_for_all["confirmed-email"] = new Create_page("confirmed");
        $this->pages_for_all["failed-confirmed-email"] = new Create_page("failedConfirm");
        $this->pages_for_all["show-msg"] = new Create_page("showMessages");
        $this->not_found = new Create_page("404");

        // check if logged in
        $this->logged_in = $_SESSION["logged_in"] ?? false;

        // parse url and get path
        $this->page_url = parse_url((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']
            === 'on' ? "https" : "http") .
            "://" . $_SERVER['HTTP_HOST'] .
            $_SERVER['REQUEST_URI']);

        $this->uri = explode('/', $_SERVER['REQUEST_URI']);

        if (isset($_SESSION["user_id"])) {
            $this->user_id = $_SESSION["user_id"];
        } else {
            $_SESSION["logged_in"] = false;
        }
    }

    public function handleGETAction()
    {
        $key = $this->uri[2];

        if (isset($this->pages_for_all[$key])) {
            $this->pages_for_all[$key]->showPage();
            return;
        }
        if ($this->logged_in) {
            if ($key == "") {
                $this->logged_in_pages["home"]->showPage();
            } else {
                ($this->logged_in_pages[$key] ?? $this->not_found)->showPage();
            }
        } else {
            if ($key == "") {
                $this->logged_out_pages["login"]->showPage();
            } else {
                ($this->logged_out_pages[$key] ?? $this->not_found)->showPage();
            }
        }
    }

    public function handlePOSTAction()
    {
        switch ($_POST["use"]) {
            case ("login"):
                $response = $this->ua->login($_POST["email"], $_POST["password"]);
                if ($response["success"]) {
                    $_SESSION["user_id"] = $response["user_id"];
                    $_SESSION["logged_in"] = true;
                    $this->generateResponse("text/plain", 204, $response["msg"]);
                } else {
                    $this->generateResponse("text/plain", 401, $response["msg"]);
                }
                break;
            case ("signup"):
                $email = $_POST["email"];
                $password = $_POST["password"];

                $confirmation_code = $this->em->generateConfirmationCode($email);
                $result = $this->ua->signup($email, $password, $confirmation_code);

                if ($result["success"]) {
                    $this->em->send($email, "Please confirm your email", Config::$get["url"] . "?confirmation=" . $confirmation_code);
                    $this->generateResponse("text/plain", 200);
                    break;
                }

                $this->generateResponse("text/plain", $result["status"], $result["msg"]);
                break;
            case ("logout"):
                session_destroy();
                $this->generateResponse("text/plain", 200);
                break;
            case ("add_bill_1"):
                // echo $_POST->doc_name;
                $this->ub->addBill($this->user_id, $_POST);
                $this->generateResponse("text/plain", 200);
                break;
            case ("get_my_bills"):
                $this->generateResponse("application/json", 200, json_encode($this->ub->get_user_bills($this->user_id)));
                break;
            case ("edit_bill"):
                $_SESSION["bill_id"] = $_POST["id"];
                break;
            case ("get_all_bill"):
                $response = $this->ub->get_all_bill($this->user_id, $_SESSION["bill_id"]);
                $this->generateResponse("application/json", 200, json_encode($response));
                break;
            case ("delete_bill"):
                $this->ub->delete_bill($this->user_id, $_POST["id"]);
                $this->generateResponse("text/plain", 200);
                break;
            case ("edit_bill_1"):
                $postData = $_POST;
                if (!is_null($postData["doc_img"])) {
                    $postData["doc_img"] = $this->ub->get_bill_picture($this->user_id, $_SESSION["bill_id"])["doc_img"];
                }
                $this->ub->delete_bill($this->user_id, $_SESSION["bill_id"]);
                $this->ub->addBill($this->user_id, $postData);
                $this->generateResponse("text/plain", 200);
                break;
            case ("create-reset-pass"):
                $email = $_POST["email"];
                if(!$this->va->validateEmail($email)){
                    $this->generateResponse("text/plain", 400, "Email is not valid");
                    break;
                };
                if(!$this->ua->has_user_verified_email($email)){
                    $this->generateResponse("text/plain", 400, "User has not verified email");
                    break;
                }
                if($this->ua->user_has_valid_pass_reset($email)){
                    $this->generateResponse("text/plain", 400, "A password reset verification email has already been sent");
                    break;
                }
                $passResetConfirmationCode = $this->em->generateConfirmationCode($email);
                $response = $this->ua->createPasswordReset($email, $passResetConfirmationCode);
                if (!$response["success"]) {
                    $this->generateResponse("text/plain", $response["status"], $response["msg"]);
                }
                $this->em->send($email, "Password reset link", Config::$get["url"] . "?passwordConfirmation=" . $passResetConfirmationCode);
                $this->generateResponse("text/plain", 200);
                break;
            case ("reset-pass"):
                $res = $this->ua->reset_password($_SESSION["passwordConfirmation"], $_POST["password"]);
                $this->generateResponse("text/plain", $res["status"], $res["msg"]);
                break;
            default:
                $this->generateResponse("text/plain", 400);
        }
    }

    public function handleQuery()
    {
        $query = [];
        parse_str($this->page_url["query"], $query);
        if (isset($query["confirmation"])) {
            $result = $this->ua->confirmUserEmail($query["confirmation"]);
            if ($result["success"]) {
                $this->pages_for_all["confirmed-email"]->showPage();
                return;
            }
            $this->pages_for_all["show-msg"]->showPage([$result["msg"]]);
            return;
        } elseif (isset($query["passwordConfirmation"])) {
            $_SESSION["passwordConfirmation"] = $query["passwordConfirmation"];
            $this->logged_out_pages["reset-password"]->showPage();
            return;
        }
        $this->handleGETAction();
    }

    public function handleAction()
    {
        $reqm = $_SERVER["REQUEST_METHOD"];

        if ($reqm == "GET") {
            if (isset($this->page_url["query"])) {
                $this->handleQuery();
            } else {
                $this->handleGETAction();
            }
        } elseif ($reqm == "POST") {
            $this->handlePOSTAction();
        }
    }

    public function generateResponse(string $_content_type, int $_http_response_code, $_content = null)
    {
        header('Content-Type: ' . $_content_type);
        http_response_code($_http_response_code);
        if (isset($_content)) {
            echo $_content;
        }
    }
}
