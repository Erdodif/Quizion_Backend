<?php

require_once "src/companion/responseCodes.php";

return function (Slim\App $app) {
    $quizRoutes = require_once "src/routes/quizRoutes.php";
    $quizRoutes($app);
    $questionRoutes = require_once "src/routes/questionRoutes.php";
    $questionRoutes($app);
    $answerRoutes = require_once "src/routes/answerRoutes.php";
    $answerRoutes($app);
    $userRoutes = require_once "src/routes/userRoutes.php";
    $userRoutes($app);
    $resultRoutes = require_once "src/routes/resultRoutes.php";
    $resultRoutes($app);
    $gameRoutes = require_once "src/routes/gameRoutes.php";
    $gameRoutes($app);
};
