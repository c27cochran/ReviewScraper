<?php
/**
 * Created by PhpStorm.
 * User: ccochran
 * Date: 5/28/14
 * Time: 5:25 PM
 */

class YelpAPI {

    /**
     * @param $unsigned_url - Yelp API requires an unsigned URL, we pass this variable to other functions
     *      that fetch Yelp API data in order to have the URL go through the OAuthRequest process to get signed
     * @return mixed - the array returned by curling the Yelp signed URL
     */

    public static function getYelpAPI($unsigned_url) {

        /**
        * Note: these keys were set up by Carter Cochran. We'll need to set up keys for SaleAMP
        */
        $consumer_key = "uvkT5Y3A07GlH8apiBS_ug";
        $consumer_secret = "I5GSUe-AoJ59UxTQtnry7Yv0kBg";
        $token = "Y4-tmHSqAGm-hoxLKBaOjRJXGyvkEhMO";
        $token_secret = "aFnZxuEY4tpc9_5p8ppEnj3G9wU";

        // Token object built using the OAuth library
        $token = new OAuthToken($token, $token_secret);

        // Consumer object built using the OAuth library
        $consumer = new OAuthConsumer($consumer_key, $consumer_secret);

        // Yelp uses HMAC SHA1 encoding
        $signature_method = new OAuthSignatureMethod_HMAC_SHA1();

        // Build OAuth Request using the OAuth PHP library. Uses the consumer and token object created above.
        $oauthrequest = OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $unsigned_url);

        // Sign the request
        $oauthrequest->sign_request($signature_method, $consumer, $token);

        // Get the signed URL
        $signed_url = $oauthrequest->to_url();

        // Send Yelp API Call
        $ch = curl_init($signed_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch); // Yelp response
        curl_close($ch);

        return $data;
    }

} 