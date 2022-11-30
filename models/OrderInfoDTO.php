<?php
    include_once "BasicDTO.php";
    include_once dirname(__DIR__, 1)."/utils/Token.php";
    class OrderInfoDTO extends BasicDTO {
        private $id;
        private $deliveryTime;
        private $orderTime;
        private $status;
        private $price;

        public function __construct($id) {
            $order = $GLOBALS["LINK"]->query(
                "SELECT deliveryTime, orderTime, status, price
                FROM ORDERS
                WHERE id=?",
                $id
            )->fetch_assoc();

            $this->id = $id;
            $this->deliveryTime = $order['deliveryTime'];
            $this->orderTime = $order['orderTime'];
            $this->status = $order['status'];
            $this->price = intval($order['price']);
        }
    }
?>