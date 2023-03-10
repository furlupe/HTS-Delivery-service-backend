<?php
    include_once dirname(__DIR__, 1)."/enums/DishCategory.php";
    include_once dirname(__DIR__, 1)."/queries/DishQueries.php";
    include_once dirname(__DIR__, 1)."/exceptions/InvalidDataException.php";
    class DishDTO {
        private $id;
        private $name;
        private $description;
        private $price;
        private $image;
        private $vegeterian;
        private $category;
        private $rating;

        public function __construct($id) {
            $data = DishQueries::getDish($id);

            if (is_null($data)) {
                throw new InvalidDataException("No such dish with id = ".$id." exists");
            }

            $this->id = $id;
            $this->name = $data['name'];
            $this->description = $data['description'];
            $this->price = intval($data['price']);
            $this->image = $data['image'];
            $this->vegeterian = boolval($data['vegeterian']);
            $this->rating = $this->countRating();
            $this->category = DishCategory::getCategory($data['category']);
        }

        public function getData() {
            $r = array();
            foreach(get_object_vars($this) as $key => $value) {
                $r[$key] = $value;
            }

            return $r;
        }

        private function countRating() {
            return floatval(
                $GLOBALS["LINK"]->query(
                    "SELECT AVG(value) as rating FROM RATING WHERE dishId = ?",
                    $this->id
                )->fetch_assoc()["rating"]
            );
        }
    }
?>