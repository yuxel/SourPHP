<?
/**
 * SourPHPTest
 *
 * LICENSE
 *
 * This file can be distributed under terms of LGPL
 *
 * @package    SourPHP
 * @copyright  Osman Yuksel
 * @LICENSE    http://www.gnu.org/licenses/lgpl-3.0.txt
 * @version    0.1
 */


include_once ("../classes/SourPHPCore.php");

/**
 * SourPHPTest
 *
 * Test Cases for SourPHP
 * @author    Osman Yuksel <yuxel |AT| sonsuzdongu |DOT| com>
 */
class SourPHPCoreTest extends PHPUnit_Framework_TestCase
{

    /**
     * this is now executed from SourPHPTest class
     *
    function setUp(){
        $this->obj = new SourPHPCore();
    }
    */



    /**
     * @test
     */
    function IfDataReturnedOnFetch() {
        $result = $this->obj->fetchUrl("http://sozluk.sourtimes.org/show.asp?t=madde+97%2F%2317410156&pad=1");
        $this->assertNotNull ($result);
    }

    /**
     * @test
     */
    function IfNoUrlSentToFetch(){
        $result = $this->obj->fetchUrl("invalid-url");
        $this->assertFalse ($result);

    }


    /**
     * @test
     */
    function FetchingIntegerId() {
        $result = $this->obj->fetchId(17410156);
        $this->assertNotNull ($result);
    }

    /**
     * @test
     * test if paramter is a string but can be casted as integer
     */
    function FetchingStringButCanBeCastToAnIntegerId() {
        $result = $this->obj->fetchId("17410156");
        $this->assertNotNull ($result);
    }

    /**
     * @test
     */
    function FetchingNonInteger() {
        $result = $this->obj->fetchId("this_is_not_valid");
        $this->assertFalse ($result);
    }

    /**
     * @test
     */
    function FetchEntry() {
        $result = $this->obj->fetchEntry("php");
        $this->assertNotNull ($result);
    }

    /**
     * @test
     */
    function FetchEntryOnSecondPage() {
        $result = $this->obj->fetchEntry("php",2);
        $this->assertNotNull ($result);
    }



    /**
     * @test
     */
    function FetchNullEntry(){
        $result = $this->obj->fetchEntry(null);
        $this->assertFalse ($result);
    }

    /**
     * @test
     */
    function FetchEntryWhichHasTurkishChars() {
        $result = $this->obj->fetchEntry("neşet ertaş");
        $this->assertNotNull ($result);
    }




    /**
     * @test
     * test if document created successfully
     */
    function ToCreateDomDocumentFromData() {
        $data = $this->obj->fetchId(17410156);
        $returned = $this->obj->createDomDocumentFromData($data);

        $result =($returned instanceof DomDocument)?true:false;
        $this->assertTrue($result);
    }

    /**
     * @test
     * test if document created successfully
     */
    function ToCreateDomDocumentFromDataIfDataIsNull() {
        $returned = $this->obj->createDomDocumentFromData(null);
        $this->assertFalse($returned);
    }





    /**
     * @test
     */
    function XpathQuery(){
        $contentOfFirstDiv="hello";
        $data = "<div id=\"test\">$contentOfFirstDiv</div><div>Second content</div>";
        $doc = $this->obj->createDomDocumentFromData($data);

        $returned = $this->obj->XPathQueryToDoc($doc, "*//div");

        $result =($returned instanceof DomNodeList)?true:false;

        $this->assertTrue($result);
    }


    /**
     * @test
     * this should return "php" as title
     */
    function GetEntryTitleFromDoc() {
        $entryId = 8102; //entry for "php" 
        $data = $this->obj->fetchId(8102);
        $doc = $this->obj->createDomDocumentFromData($data);

        $title = $this->obj->getEntryTitleFromDoc($doc);

        $this->assertEquals($title, "php");

    }


    /**
     * @test
     */
    function GetEntryTitleFromADocWhichDoesntHaveTitleField(){
        $data = "<span>Foobar</span>";

        $doc = $this->obj->createDomDocumentFromData($data);
        $title = $this->obj->getEntryTitleFromDoc($doc);

        $this->assertFalse($title);


    }


    /**
     * @test
     * "php" entry has 7 pages for now
     * @fixme this could change later
     */
    function GetNumberOfPages(){
        $data = $this->obj->fetchEntry("php");
        $doc = $this->obj->createDomDocumentFromData($data);

        $numOfPages = $this->obj->getNumberOfPages($doc);

        $this->assertEquals($numOfPages, 7);

    }

    /**
     * @test
     * there's no such php_thats_not_exists entry so page num should be 0
     */
    function GetNumberOfPagesForAnEntryWhichNotExists(){
        $data = $this->obj->fetchEntry("php_thats_not_exist");
        $doc = $this->obj->createDomDocumentFromData($data);

        $numOfPages = $this->obj->getNumberOfPages($doc);

        $this->assertEquals($numOfPages, 0);

    }

    /**
     * @test
     * 'neşet ertaş' page (turkish chars and white space tested)
     */
    function GetNumberOfPagesForAnEntryWhichContainsTurkishCharsAndWhitespace(){
        $data = $this->obj->fetchEntry("neşet ertaş");
        $doc = $this->obj->createDomDocumentFromData($data);

        $numOfPages = $this->obj->getNumberOfPages($doc);

        $this->assertEquals($numOfPages, 10);

    }


    /**
     * @test
     */
    function GetContentsOfEntriesFromDoc() {
        $data = $this->obj->fetchEntry("neşet ertaş");
        $doc = $this->obj->createDomDocumentFromData($data);

        $entries = $this->obj->getContentOfEntriesFromDoc($doc);

        $this->assertArrayHasKey('0', $entries);
        $this->assertArrayHasKey('entryId', $entries[0]);

    }


    /**
     * @test
     */
    function GetContentsOfEntriesFromDocWhichDoesntHaveAnIdField() {
        $data = $this->obj->fetchEntry("this entry will never exists on eksisozluk foobar");
        $doc = $this->obj->createDomDocumentFromData($data);
        $entries = $this->obj->getContentOfEntriesFromDoc($doc);
        $this->assertFalse($entries);


    }

    /**
     * @test
     */
    function GetContentsOfEntriesFromNullDoc() {
        $entries = $this->obj->getContentOfEntriesFromDoc(null);
        $this->assertFalse($entries);
    }


}

