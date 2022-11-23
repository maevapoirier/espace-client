<?php

namespace App\Zoho;

use com\zoho\api\authenticator\Token;

use com\zoho\crm\api\exception\SDKException;

use com\zoho\crm\api\UserSignature;

use com\zoho\api\authenticator\store\TokenStore;

require 'vendor/autoload.php';

class ZohoCrmApiManager implements TokenStore
{
    
    /**
      * @param user A UserSignature class instance.
      * @param token A Token (com\zoho\api\authenticator\OAuthToken) class instance.
      * @return A Token class instance representing the user token details.
      * @throws SDKException if any problem occurs.
    */
    public function getToken($user, $token)
    {
      // Add code to get the token
      return null;
    }

    /**
      * @param user A UserSignature class instance.
      * @param token A Token (com\zoho\api\authenticator\OAuthToken) class instance.
      * @throws SDKException if any problem occurs.
    */
    public function saveToken($user, $token)
    {
      // Add code to save the token
    }

    /**
      * @param token A Token (com\zoho\api\authenticator\OAuthToken) class instance.
      * @throws SDKException if any problem occurs.
    */
    public function deleteToken($token)
    {
      // Add code to delete the token
    }

    /**
      * @return array  An array of Token (com\zoho\api\authenticator\OAuthToken) class instances
    */
    public function getTokens()
    {
      //Add code to retrieve all the stored tokens
    }

    public function deleteTokens()
    {
      //Add code to delete all the stored tokens.
    }

    /**
      * @param id A string.
      * @param token A Token (com\zoho\api\authenticator\OAuthToken) class instance.
      * @return A Token class instance representing the user token details.
      * @throws SDKException if any problem occurs.
    */
    public function getTokenById($id, $token)
    {
      // Add code to get the token using unique id
      return null;
    }
    
}
