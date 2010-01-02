<?php

require_once("SourPHPUrlCacheInterface.php");

class SourPHPUrlCacheToFile implements SourPHPUrlCacheInterface
{

    /**
     * hash url to save file name
     *
     * @param url
     */
    function hashUrl($url, $prefix=null){
        $md5 = md5($url);

        $hash = $prefix . $md5;

        return $hash;
    }

    /**
     * checks if data is expired
     *
     * @param string $hashed hashed url string
     * @param int $cacheLifetime 
     */
    function isExpired($hashed, $cacheLifetime){
        if( file_exists ( $hashed ) ) {
            
            $now = time();
            $lastModificationTime = filemtime($hashed);

            $cacheDuration = $now - $lastModificationTime;

            if( $cacheLifetime > $cacheDuration ) {
                return false;
            }
            else{
                return true;
            }
        }
        else{
            return true;
        }

    } 



    /**
     * regenerates file with $data
     * 
     * @param string $hashed
     * @param string $data fetched data from url
     */
    function regenerateData($hashed, $data){
        file_put_contents($hashed, $data);
    }


    /**
     * returns current data on $hashed url
     *
     * @param string $hashed
     * @return string fetched url data
     */
    function getCurrentData($hashed){
        return file_get_contents($hashed);
    }
}
