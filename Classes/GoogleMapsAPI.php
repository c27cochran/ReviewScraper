<?php

class GoogleMapsAPI {

    public $api_ID = "a407k888";
    public $apiKey = "600156588d713eea07fc393b8d38f71f";

    public $searchTerm = "";
    public $geocode = "";
    public $data = "";

    /**
     * executeAPICall - Executes the custom Kimono API call specified by this class's members and returns the results as JSON
     * this function takes in the $request which gets the Kimono URL format.
     * To learn more about Kimono API, go here: http://www.kimonolabs.com/
     * Example URL structure for Kimono API - https://www.google.com/ maps / place / Panera+Bread / @30.223218,-97.844147,17z / data=!3m1!4b1!4m2!3m1!1s0x0:0x751955786067b3e3 /
     * @param $searchTerm - The third URL parameter that can be changed with this specific Kimono API
     * @param $geocode - The fourth URL parameter that can be changed with this specific Kimono API
     * @param $data - The fifth and final URL parameter that can be changed with this specific Kimono API
     * @return mixed - the JSON resulting from the Kimono API call specified by the members of this class
     */
    protected function executeAPICall($unsigned_url) {

        $params = explode('/',$unsigned_url);

        $searchTerm = $params[5];
        $geocode = $params[6];
        $data = $params[7];

        $request = "http://www.kimonolabs.com/api/".$this->api_ID."?apikey=".$this->apiKey."&kimpath3=".$searchTerm."&kimpath4=".$geocode."&kimpath5=".$data;

        $response = file_get_contents($request);
        $results = json_decode($response, TRUE);

        return $results;
    }

    /**
     * getGoogleRating fetches the JSON from the executeAPICall and returns the average Google rating
     * @param $apiResults - calls the executeAPICall function to get the JSON results
     * @param $rating - fetches the rating value from the returned JSON results
     * @return - Google places average ratings
     */
    public function getGoogleRating($unsigned_url) {

        $apiResults = $this->executeAPICall($unsigned_url);

        $rating = $apiResults['results']['collection1'][0]['rating'];

        return $rating;
    }

    /**
     * getGoogleBusiness fetches the JSON from the executeAPICall and returns the formatted name of the local business
     * @param $apiResults - calls the executeAPICall function to get the JSON results
     * @param $business - fetches the business name value from the returned JSON results
     * @return - Google places formatted business name
     */
    public function getGoogleBusiness($unsigned_url) {

        $apiResults = $this->executeAPICall($unsigned_url);

        $business = $apiResults['results']['collection1'][0]['business-name'];

        return $business;
    }

    /**
     * getGoogleReviewCount fetches the JSON from the executeAPICall and returns the total number of Google reviews
     * @param $apiResults - calls the executeAPICall function to get the JSON results
     * @param $reviews - fetches the review count value from the returned JSON results, however this comes with
     *      appeneded text
     * @param $numReviews - strips out the appended text from the JSON value
     * @return - Google places average ratings
     */
    public function getGoogleReviewCount($unsigned_url) {

        $apiResults = $this->executeAPICall($unsigned_url);

        $reviews = $apiResults['results']['collection1'][0]['num-rating']['text'];

        // Strip the text from the JSON result
        $numReviews = preg_replace("/[^0-9]+/", "", $reviews);

        return $numReviews;
    }
}