<?php

require_once("../classes/SourPHPUrlCacheToFile.php");

class SourPHPUrlCacheToFileTest extends PHPUnit_Framework_Testcase
{

    function setUp(){
        $this->cacheHandler = new SourPHPUrlCacheToFile();
        $this->tmpFile = tempnam("/tmp", "cacheToFile");
    }


    /** 
     * @test 
     */
    function  urlHashed(){
        $url1 = "http://foo.bar.com/?q=foobar";
        $url2 = "http://foo.bar.com/?q=barbar";

        $hashed1 = $this->cacheHandler->hashUrl($url1);
        $hashed2 = $this->cacheHandler->hashUrl($url2);

        $this->assertNotEquals($hashed1, $hashed2);
    }      


    /**
     * @test
     */
    function returnsTrueIfExpired(){
        $cacheLifetTime = -1; //should always be expired

        $isExpired = $this->cacheHandler->isExpired($this->tmpFile, $cacheLifetTime );

        $this->assertTrue ( $isExpired );
    }

    
    /**
     * @test
     */
    function returnsFalseIfNotExpiredYet(){
        $cacheLifetTime = 1000; 

        $isExpired = $this->cacheHandler->isExpired($this->tmpFile, $cacheLifetTime );

        $this->assertFalse ( $isExpired );
    }


    /**
     * @test
     */
    function returnsFalseIfFileNotExists(){
        $cacheLifetTime = 1000; 

        $isExpired = $this->cacheHandler->isExpired("/tmp/this/file/not/exists", $cacheLifetTime );

        $this->assertTrue ( $isExpired );
    }



    /**
     * @test
     */
    function generateData(){
        $dataToWrite = "foobar";
        $this->cacheHandler->regenerateData($this->tmpFile, $dataToWrite);
        $dataRead = file_get_contents($this->tmpFile); 

        $this->assertEquals( $dataToWrite, $dataRead );
    }

    /**
     * @test
     */
    function getData(){
        $dataWritten = "hedehodo";
        file_put_contents($this->tmpFile , $dataWritten);

        $getData = $this->cacheHandler->getCurrentData($this->tmpFile);

        $this->assertEquals ( $getData, $dataWritten );
    }



}
