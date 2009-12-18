<?
/**
 * SourPHPInterface
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



/**
 * SourPHPInterface
 *
 * @author    Osman Yuksel <yuxel |AT| sonsuzdongu |DOT| com>
 */
interface SourPHPInterface{

    //@todo comments
    public function getEntryById($entryId);

    
    public function getEntryByTitle($entryTitle, $page=1); 

    public function getAllEntriesByTitle($entryTitle);

}
