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

/**
 * SourPHPTest
 *
 * Test Cases for SourPHP
 * @author    Osman Yuksel <yuxel |AT| sonsuzdongu |DOT| com>
 */
class SourPHPTest extends PHPUnit_Framework_TestCase
{
   
    /**
     * init sourPHP object for each test
     */ 
    function setUp(){
        $this->obj = new SourPHP();
    }


    /**
     * Test for fetching url
     *
     * @test
     */
    function ifDataReturnedOnFetch() {
        $result = $this->obj->fetchUrl("http://sozluk.sourtimes.org/show.asp?t=madde+97%2F%2317410156&pad=1");
        $this->assertNotNull ($result);
    }

        
}

