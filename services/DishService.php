<?php
    include_once dirname(__DIR__, 1)."/models/DishPagedListDTO.php";
    include_once dirname(__DIR__, 1)."/exceptions/URLParametersException.php";
    include_once dirname(__DIR__, 1)."/exceptions/AuthException.php";
    include_once dirname(__DIR__, 1)."/helpers/Token.php";
    class DishService {
        public static function getDishList($filters) : array {
            if (!isset($filters["page"]) || empty($filters["page"])) {
                throw new URLParametersException(
                    extras: array("errors" => array(
                        "page" => "Page must be specified"
                    ))
                );
            }

            $list = new DishPagedListDTO($filters);
            return $list->getData();
        }

        public static function getDish($id) : array {
            $dish = new DishDTO($id);
            return $dish->getData();
        }

        public static function setRating($id, $rating) : array {
            $email = Token::getEmailFromToken($GLOBALS["USER_TOKEN"]);
            if (is_null($email)) {
                throw new AuthException();
            }
            $userId = $GLOBALS["LINK"]->query(
                "SELECT id
                FROM USERS
                WHERE email='$email'"
            )->fetch_assoc()['id'];

            $exists = $GLOBALS["LINK"]->query(
                "SELECT 1
                FROM RATING
                WHERE userId='$userId' AND dishId='$id'
                LIMIT 1"
            );

            if($exists->num_rows) {
                $GLOBALS["LINK"]->query(
                    "UPDATE RATING
                    SET value='$rating'
                    WHERE userId='$userId' AND dishId='$id'"
                );

            } else {
                $GLOBALS["LINK"]->query(
                    "INSERT INTO RATING(userId, dishId, value)
                    VALUES (
                        '$userId',
                        '$id',
                        '$rating'
                    )"
                );
            }

            return array(
                "status" => "HTTP/1.0 200 OK",
                "message" => "Rating set: '$rating'"
            );
        }
    }
?>