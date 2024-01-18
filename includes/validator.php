<?php

class Validator
{
    public function validateEmail(string $_email)
    {
        return filter_var($_email, FILTER_VALIDATE_EMAIL);
    }

    public function validatePassword(string $_password)
    {
        return (strlen($_password) > 7);
    }
}
