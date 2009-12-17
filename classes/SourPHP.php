<?
/**
 * SourPHP
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


require_once("SourPHPInterface.php");
require_once("SourPHPCore.php");

/**
 * SourPHP
 *
 * @implements SourPHPInterface
 * @author    Osman Yuksel <yuxel |AT| sonsuzdongu |DOT| com>
 */
class SourPHP extends SourPHPCore implements SourPHPInterface{
    public function getEntryById($entryId){
        return array("title"=>"pena");
    }

    
    public function getEntryByTitle($entryTitle){
    return true;
    }

}
