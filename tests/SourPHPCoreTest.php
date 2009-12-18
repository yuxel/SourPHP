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
     * init sourPHP object for each test
     */ 
    function setUp(){
        $this->obj = new SourPHPCore();
    }




    function testIfDataReturnedOnFetch() {
        $result = $this->obj->fetchUrl("http://sozluk.sourtimes.org/show.asp?t=madde+97%2F%2317410156&pad=1");
        $this->assertNotNull ($result);
    }

    function testIfNoUrlSentToFetch(){
        $result = $this->obj->fetchUrl("invalid-url");
        $this->assertFalse ($result);

    }


    function testFetchingIntegerId() {
        $result = $this->obj->fetchId(17410156);
        $this->assertNotNull ($result);
    }

    /**
     * test if paramter is a string but can be casted as integer
     */
    function testFetchingStringButCanBeCastToAnIntegerId() {
        $result = $this->obj->fetchId("17410156");
        $this->assertNotNull ($result);
    }

    function testFetchingNonInteger() {
        $result = $this->obj->fetchId("this_is_not_valid");
        $this->assertFalse ($result);
    }

    function testFetchEntry() {
        $result = $this->obj->fetchEntry("php");
        $this->assertNotNull ($result);
    }

    function testFetchEntryOnSecondPage() {
        $result = $this->obj->fetchEntry("php",2);
        $this->assertNotNull ($result);
    }



    function testFetchNullEntry(){
        $result = $this->obj->fetchEntry(null);
        $this->assertFalse ($result);
    }

    function testFetchEntryWhichHasTurkishChars() {
        $result = $this->obj->fetchEntry("neşet ertaş");
        $this->assertNotNull ($result);
    }




    /**
     * test if document created successfully
     */
    function testToCreateDomDocumentFromData() {
        $data = $this->obj->fetchId(17410156);
        $returned = $this->obj->createDomDocumentFromData($data);

        $result =($returned instanceof DomDocument)?true:false;
        $this->assertTrue($result);
    }

    /**
     * test if document created successfully
     */
    function testToCreateDomDocumentFromDataIfDataIsNull() {
        $returned = $this->obj->createDomDocumentFromData(null);
        $this->assertFalse($returned);
    }





    function testXpathQuery(){
        $contentOfFirstDiv="hello";
        $data = "<div id=\"test\">$contentOfFirstDiv</div><div>Second content</div>";
        $doc = $this->obj->createDomDocumentFromData($data);

        $returned = $this->obj->XPathQueryToDoc($doc, "*//div");

        $result =($returned instanceof DomNodeList)?true:false;

        $this->assertTrue($result);
    }


    /**
     * this should return "php" as title
     */
    function testGetEntryTitleFromDoc() {
        $entryId = 8102; //entry for "php" 
        $data = $this->obj->fetchId(8102);
        $doc = $this->obj->createDomDocumentFromData($data);

        $title = $this->obj->getEntryTitleFromDoc($doc);

        $this->assertEquals($title, "php");

    }


    function testGetEntryTitleFromADocWhichDoesntHaveTitleField(){
        $data = "<span>Foobar</span>";

        $doc = $this->obj->createDomDocumentFromData($data);
        $title = $this->obj->getEntryTitleFromDoc($doc);

        $this->assertFalse($title);


    }


    /**
     * "php" entry has 7 pages for now
     * @fixme this could change later
     */
    function testGetNumberOfPages(){
        $data = $this->obj->fetchEntry("php");
        $doc = $this->obj->createDomDocumentFromData($data);

        $numOfPages = $this->obj->getNumberOfPages($doc);

        $this->assertEquals($numOfPages, 7);
        
    }

    /**
     * there's no such php_thats_not_exists entry so page num should be 0
     */
    function testGetNumberOfPagesForAnEntryWhichNotExists(){
        $data = $this->obj->fetchEntry("php_thats_not_exist");
        $doc = $this->obj->createDomDocumentFromData($data);

        $numOfPages = $this->obj->getNumberOfPages($doc);

        $this->assertEquals($numOfPages, 0);
        
    }

    /**
     * 'neşet ertaş' page (turkish chars and white space tested)
     */
    function testGetNumberOfPagesForAnEntryWhichContainsTurkishCharsAndWhitespace(){
        $data = $this->obj->fetchEntry("neşet ertaş");
        $doc = $this->obj->createDomDocumentFromData($data);

        $numOfPages = $this->obj->getNumberOfPages($doc);

        $this->assertEquals($numOfPages, 10);
        
    }


    function testGetContentsOfEntriesFromDoc() {
        $data = $this->obj->fetchEntry("neşet ertaş");
        $doc = $this->obj->createDomDocumentFromData($data);

        $entries = $this->obj->getContentOfEntriesFromDoc($doc);

        $this->assertArrayHasKey('0', $entries);
        $this->assertArrayHasKey('entryId', $entries[0]);

    }


    function testGetContentsOfEntriesFromDocWhichDoesntHaveAnIdField() {
        $data = $this->obj->fetchEntry("this entry will never exists on eksisozluk foobar");
        $doc = $this->obj->createDomDocumentFromData($data);
        $entries = $this->obj->getContentOfEntriesFromDoc($doc);
        $this->assertFalse($entries);


    }

    function testGetContentsOfEntriesFromNullDoc() {
        $entries = $this->obj->getContentOfEntriesFromDoc(null);
        $this->assertFalse($entries);
    }


}

