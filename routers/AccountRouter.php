<?php
    include_once "RouterInterface.php";
    include_once dirname(__DIR__, 1)."/controllers/AccountController.php";
    class AccountRouter implements IRouter {
        
        public function route($method, $urlList, $requestData) {
            echo (new AccountController())->getResponse(
                $method,
                $urlList,
                $requestData
            );
        }
    }
?>