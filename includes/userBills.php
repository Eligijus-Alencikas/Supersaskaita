<?php

class UserBills
{
    private Database $db;

    public function __construct($_db)
    {
        $this->db = $_db;
    }

    public function addBill(int $_userId, array $_billData)
    {
        $doc_name = $_billData["doc_name"];
        $doc_num = $_billData["doc_num"];
        $customer = $_billData["customer"];
        $sender = $_billData["sender"];
        $date_of_issue = $_billData["date_of_issue"];
        $PVM = $_billData["PVM"];
        $doc_img = $_billData["doc_img"];

        $sql = "INSERT INTO bill_1 (user_id, doc_name, doc_num, customer, sender, date_of_issue, PVM, doc_img) 
        VALUES ('$_userId', '$doc_name', '$doc_num', '$customer', '$sender', '$date_of_issue', '$PVM', '$doc_img')";

        $this->db->query($sql);

        $lastInsertedId = $this->db->get_lats_id();

        foreach ($_billData['products'] as $product) {
            $name = $product['name'];
            $amount = $product['ammount'];
            $price = $product['price'];

            $sql = "INSERT INTO goods_and_services (bill_id, name, amount, price) VALUES ('$lastInsertedId', '$name', '$amount', '$price')";

            $this->db->query($sql);
        }
    }

    public function get_user_bills(int $_userId)
    {
        $sql = "SELECT id, doc_num, doc_name, date_of_issue, customer FROM bill_1 WHERE user_id = '$_userId'";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function get_all_bill(int $_userId, int $_billId)
    {
        $sql = "SELECT id, doc_num, doc_name, date_of_issue, customer, sender, PVM, doc_img FROM bill_1 WHERE user_id = '$_userId' AND id = '$_billId'";
        $result = $this->db->query($sql)->fetch_assoc();
        $result["products"] = $this->get_goods_and_services($result["id"]);
        return $result;
    }

    public function get_goods_and_services($_billId)
    {
        $sql = "SELECT name, amount, price FROM goods_and_services WHERE bill_id = '$_billId'";
        return $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function delete_bill($_userId, $_billId)
    {
        $sql = "DELETE FROM bill_1 WHERE user_id = '$_userId' AND id = '$_billId'";
        $this->db->query($sql);
        $sql = "DELETE FROM goods_and_services WHERE bill_id = '$_billId'";
        $this->db->query($sql);
    }

    public function get_bill_picture($_userId, $_billId)
    {
        $sql = "SELECT id, doc_img FROM bill_1 WHERE user_id = '$_userId' AND id = '$_billId'";
        return $this->db->query($sql)->fetch_assoc();
    }
}
