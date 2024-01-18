<?php

class UserAuth
{
    private Database $db;
    private Validator $va;

    public function __construct($_db, $_va)
    {
        $this->db = $_db;
        $this->va = $_va;
    }

    public function signup(string $_email, string $_password, string $_confirmation_code)
    {
        if (!$this->va->validateEmail($_email)) {
            return ["success" => false, "status" => 400, "msg" => "Email is invalid"];
        }

        $response = $this->deleteExpiredUserVerification($_email);
        // if unsuccessfull check if an unverified user exists if does then user has not verified email yet

        if (!$response["success"]) {
            $user_exists = $response["unverified_user_exists"] ?? false;

            if ($user_exists) {
                return ["success" => false, "status" => 400, "msg" => "User must verify email"];
            }
        }

        // check if a verified user exists (function checks both verified and unverified but deleteExpiredUserVerfication() already acounted for the unverified)
        if ($this->doesUserExist($_email)) {
            return ["success" => false, "status" => 409, "msg" => "User already exists"];
        }

        if (!$this->va->validatePassword($_password)) {
            return ["success" => false, "status" => 400, "msg" => "Password must be at least 8 characters long"];
        }

        // Hash the password securely
        $hashedPassword = password_hash($_password, PASSWORD_BCRYPT);

        // Insert the user into the database
        $sql = "INSERT INTO users (email, password, confirmation_code) VALUES ('$_email', '$hashedPassword', '$_confirmation_code')";
        $this->db->query($sql);

        return ["success" => true, "status" => 200, "msg" => ""];
    }

    public function login(string $_email, string $_password)
    {
        if (!$this->va->validateEmail($_email)) {
            return ["success" => false, "status" => 401, "msg" => "Email is invalid"];
        }

        // Fetch the user's hashed password from the database
        $sql = "SELECT id, password, verified FROM users WHERE email = '$_email'";
        $result = $this->db->query($sql);
        if ($result->num_rows < 1) {
            return ["success" => false, "status" => 401, "msg" => "User doesn't exist"];
        }

        $row = $result->fetch_assoc();
        $user_id = $row['id'];
        $hashedPassword = $row['password'];
        $verified = $row['verified'];

        // Verify the password
        if (!password_verify($_password, $hashedPassword)) {
            // Password is correct
            return ["success" => false, "status" => 401, "msg" => "Incorrect password"];
        }

        if (!$verified) {
            return ["success" => false, "status" => 401, "msg" => "User did not verify their email"];
        }

        return ["success" => true, "status" => 200, "user_id" => $user_id];
    }

    public function confirmUserEmail($_confirmation_code)
    {
        // get user
        $sql = "SELECT id, creation_time, verified FROM users WHERE confirmation_code = '$_confirmation_code'";
        $result = $this->db->query($sql);

        // user does not exist with that confirmation code
        if ($result->num_rows == 0) {
            return ["success" => false, "msg" => "No user"];
        }

        $result = $result->fetch_assoc();

        $id = $result["id"];
        $verified = $result["verified"];

        $creation_time = new DateTime($result["creation_time"]);
        $current_time = new DateTime();

        if ($verified) {
            return ["success" => false, "msg" => "User already verified"];
        }

        $interval = ($current_time->diff($creation_time, true));
        $hoursDifference = $interval->h + ($interval->days * 24);

        if ($hoursDifference >= 24) {
            return ["success" => false, "msg" => "User verification has expired"];
        }

        // verify user
        $sql = "UPDATE users SET verified = 1 WHERE id = '$id'";
        $this->db->query($sql);

        return ["success" => true];
    }

    public function doesUserExist($_email)
    {
        $sql = "SELECT id FROM users WHERE email = '$_email'";
        $result = $this->db->query($sql);
        return $result->num_rows > 0;
    }

    public function deleteExpiredUserVerification($_email)
    {
        $sql = "SELECT id, creation_time FROM users WHERE email = '$_email' AND verified = 0";
        $result = $this->db->query($sql);

        // check if user exists
        if ($result->num_rows == 0) {
            return ["success" => false, "unverified_user_exists" => false];
        }

        $result = $result->fetch_assoc();

        $id = $result["id"];

        $creation_time = new DateTime($result["creation_time"]);
        $current_time = new DateTime();

        $interval = ($current_time->diff($creation_time, true));
        // get difference
        $hoursDifference = $interval->h + ($interval->days * 24);

        // if difference is larger or equal to 24
        if ($hoursDifference >= 24) {
            $sql = ("DELETE FROM users WHERE id = $id");
            $this->db->query($sql);
            return ["success" => true];
        }

        return ["success" => false, "unverified_user_exists" => true];
    }

    public function createPasswordReset($_email, $_confirmation_code)
    {
        if (!$this->va->validateEmail($_email)) {
            return ["success" => false, "status" => 400, "msg" => "Email is invalid"];
        }

        $sql = "UPDATE users SET change_password_code = '$_confirmation_code', password_code_creation_time = NOW(), password_code_used = FALSE WHERE email = '$_email'";
        $this->db->query($sql);

        return ["success" => true];
    }

    public function reset_password($_confirmation_code, $_new_password)
    {
        if (!$this->va->validatePassword($_new_password)) {
            return ["success" =>  false, "status" => 400, "msg" => "Password must be at least 8 characters long"];
        }
        $hashedPassword = password_hash($_new_password, PASSWORD_BCRYPT);
        $sql = "UPDATE users SET password = '$hashedPassword', password_code_used = TRUE WHERE change_password_code = '$_confirmation_code' AND password_code_creation_time + INTERVAL 1 DAY > NOW() AND password_code_used = FALSE";
        $this->db->query($sql);
        $affected_rows = $this->db->affected_rows();
        if ($affected_rows <= 0) {
            return ["success" => false, "status" => 400, "msg" => "Invalid request"];
        }
        return ["success" => true, "status" => 200, "msg" => "success"];
    }

    public function user_has_valid_pass_reset($_email){
        $sql = "SELECT id FROM `users` WHERE email = '$_email' AND password_code_creation_time + INTERVAL 1 DAY > NOW() AND password_code_used = FALSE";
        $result = $this->db->query($sql);
        if ($result->num_rows < 1) {
            return false;
        }
        return true;
    }

    public function has_user_verified_email($_email){
        $sql = "SELECT verified FROM users WHERE email = '$_email'";
        $result = $this->db->query($sql);

        $row = $result->fetch_assoc();
        return $row['verified'];
    }
}
