<?php
/**
 * @access basic class 
 */
namespace backend\controllers;
use yii\web\Controller;

class ConsoleController extends Controller {

    private function cmd ($cmd, $output = '') {
        $handler = popen($cmd, 'r');
        
        while (! feof($handler))
            $output .= fgets($handler);
        
        $output = trim($output);
        $status = pclose($handler);
        return $status;
    }
}