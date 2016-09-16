<?php
/**
 * Created by PhpStorm.
 * User: ccochran
 * Date: 5/22/14
 * Time: 4:38 PM
 */

require_once dirname(__FILE__) . '/PHPExcel/IOFactory.php';

define("UPLOAD_DIR", "uploads/");

class Scraper extends PHPExcel
{
    public static function getFileName() {
        if (!empty($_FILES["myFile"])) {
            $myFile = $_FILES["myFile"];

            if ($myFile["error"] !== UPLOAD_ERR_OK) {
                echo "<p>An error occurred.</p>";
                exit;
            }

            // ensure a safe filename
            $name = preg_replace("/[^A-Z0-9._-]/i", "_", $myFile["name"]);

            // don't overwrite an existing file
            $i = 0;
            $parts = pathinfo($name);
            while (file_exists(UPLOAD_DIR . $name)) {
                $i++;
                $name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
            }

            // preserve file from temporary directory
            $success = move_uploaded_file($myFile["tmp_name"],
                UPLOAD_DIR . $name);
            if (!$success) {
                echo "<p>Unable to save file.</p>";
                exit;
            } else {
                $fileName =  UPLOAD_DIR . $name;
            }
        }

        return $fileName;
    }

}