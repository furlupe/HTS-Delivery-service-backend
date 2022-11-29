<?php
    class ESQL {
        private $link;
        private $stmt;
        public function __construct($id, $username, $password, $db) {
            $this->link = new mysqli($id, $username, $password, $db);
        }

        public function query($request, $params) {
            $this->stmt = $this->link->prepare($request);
            $types = "";
            foreach($params as $key => $value) {
                $type = "s";
                if(is_int($value)) $type = "i";
                $types = $types.$type;
            }
            
            if (count($params) > 0) {
                $this->stmt->bind_param($types, ...$params);
            }
            
            $this->stmt->execute();

            return $this->stmt->get_result();
        }

        public function fetch_assoc() {
            return $this->stmt->get_result()->fetch_assoc();
        }
        public function fetch_all() {
            return $this->stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }
        
    }
?>