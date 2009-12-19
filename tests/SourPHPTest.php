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


include_once ("../classes/SourPHP.php");
include_once ("SourPHPCoreTest.php");

/**
 * SourPHPTest
 *
 * Test Cases for SourPHP
 * @author    Osman Yuksel <yuxel |AT| sonsuzdongu |DOT| com>
 */
class SourPHPTest extends SourPHPCoreTest
{

    /**
     * init sourPHP object for each test
     */ 
    function setUp(){
        parent::setUp();
        $this->obj = new SourPHP();
    }

    /**
     * @test
     */
    function getEntryOfFirstId(){
        $result = $this->obj->getEntryById(1);
        
        $this->assertArrayHasKey("title", $result);
        $this->assertArrayHasKey("entryId", $result);
        $this->assertArrayHasKey("order", $result);
        $this->assertArrayHasKey("author", $result);
        $this->assertArrayHasKey("dateCreated", $result);
        $this->assertArrayHasKey("dateEdited", $result);

    }


    /**
     * @test
     */
    function checkDataOfFirstId(){
        $result = $this->obj->getEntryById(1);

        $this->assertEquals ( $result['entryId'], 1);    
        $this->assertEquals ( $result['title'], "pena");    
        $this->assertEquals ( $result['order'], 1);    
        $this->assertEquals ( $result['author'], "ssg");    
        $this->assertEquals ( $result['dateCreated'], strtotime("15.02.1999"));    
        $this->assertEquals ( $result['dateEdited'], null);    
        $this->assertEquals ( $result['content'], "gitar calmak icin kullanilan minik plastik garip nesne.");    
    }


    /**
     * @test
     */
    function checkEntriesOfEntryByTitleOnFirstPage(){
        $result = $this->obj->getEntryByTitle("php",1);

        //it has 25 entries
        $this->assertEquals( count($result), 25); 

        //last entry on first page is 'ks. people hate perl.'
        $this->assertEquals ( $result[24]['content'], "ks. people hate perl.");    

    }


    /**
     * @test
     */
    function checkFetchingAllEntryiesForTitle(){
        $result = $this->obj->getAllEntriesByTitle("php");

        //160th  entry is 'daha hizlanmasi, performansinin daha da artmasi gerekiyor. olur in$allah.'
        $entryText = 'daha hizlanmasi, performansinin daha da artmasi gerekiyor. olur in$allah.';
        $this->assertEquals ( $result[159]['content'], "$entryText");    

    }

} 
