<?php
/**
 * Created by PhpStorm.
 * User: ccochran
 * Date: 5/23/14
 * Time: 2:26 PM
 */

require_once ('lib/OAuth.php');
require_once ('YelpAPI.php');
require_once ('simple_html_dom.php');

class Yelp extends YelpAPI {

    /**
     * getObjArray function reduces repetitive code
     * @param $data - retrieves the YelpAPI JSON data
     * @param $obj - decodes the JSON from the $data var
     * @return multiple - JSON decoded array
     */

    public static function getObjArray($unsigned_url) {

        $data = parent::getYelpAPI($unsigned_url);

        $obj = json_decode($data, TRUE);

        return $obj;
    }

    /**
     * getYelpReviewCount returns the number of reviews from the JSON received from getObjArray function
     */
    public static function getYelpReviewCount($unsigned_url) {

        $obj = self::getObjArray($unsigned_url);

        return $obj['review_count'];

    }

    /**
     * getYelpAverageRating returns the average rating from the JSON received from getObjArray function
     */
    public static function getYelpAverageRating($unsigned_url) {

        $obj = self::getObjArray($unsigned_url);

        return $obj['rating'];
    }

    /**
     * getYelpBusinessName returns the name of the local business from the JSON received from getObjArray function
     */
    public static function getYelpBusinessName($unsigned_url) {

        $obj = self::getObjArray($unsigned_url);

        return $obj['name'];
    }

    /**
     * getYelpPublicURL returns the user friendly URL from the JSON received from getObjArray function
     * this is used in scraper.php to scrape data from the user friendly Yelp URL data.
     */
    public static function getYelpPublicURL($unsigned_url) {

        $obj = self::getObjArray($unsigned_url);

        return $obj['url'];
    }


} 