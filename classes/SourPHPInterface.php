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

    /**
     * fetches entries properties by id
     *
     * @param int $entryId
     * @return mixed array(entryId,title, content, order, author, dateCreated, dateEdited)
     */
    public function getEntryById($entryId);

     /**
     * fetches entries properties by title and $page
     *
     * @param int $entryId entry id
     * @param int $page page number
     * @return mixed array(entryId,title, content, order, author, dateCreated, dateEdited)
     */
    public function getEntryByTitle($entryTitle, $page=1); 


     /**
     * fetches all entries on title
     *
     * @param int $entryId entry id
     * @return mixed array(entryId,title, content, order, author, dateCreated, dateEdited)
     */
    public function getAllEntriesByTitle($entryTitle);

}
