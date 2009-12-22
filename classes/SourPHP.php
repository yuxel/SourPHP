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


    /**
     * fetches entry details by id
     *
     * @param int $entryId
     * @return mixed array(entryId,title, content, order, author, dateCreated, dateEdited)
     */ 
    public function getEntryById($entryId){
        $data = $this->fetchId($entryId);
        $doc = $this->createDomDocumentFromData($data);
        $details = $this->getContentOfEntriesFromDoc($doc);

        $entryDetail = $details[0];
        return $entryDetail;
    }

    /**
     * fetches entry details by entry title
     *
     * @param string $entryTitle
     * @return mixed array(entryId,title, content, order, author, dateCreated, dateEdited)
     */ 
    public function getEntryByTitle($entryTitle, $page=1){
        $data = $this->fetchEntry($entryTitle, $page);
        $doc = $this->createDomDocumentFromData($data);
        $details = $this->getContentOfEntriesFromDoc($doc);

        return $details;
    }


    /**
     * fetches all entries by entry title
     * 
     * @param string $entryTitle
     * @return mixed array(entryId,title, content, order, author, dateCreated, dateEdited)
     */
    public function getAllEntriesByTitle($entryTitle){
        $data = $this->fetchEntry($entryTitle, $page);
        $doc = $this->createDomDocumentFromData($data);
        $numOfPages = $this->getNumberOfPages($doc); 
        $details = array();

        for($i=1;$i<=$numOfPages;$i++){
            $detailsOfPage =  $this->getEntryByTitle($entryTitle, $i);
            $details = array_merge($details, $detailsOfPage);
        }

        return $details;
    }


    /**
     * fetch entries, which are created after $timestamp
     * this could be useful on such thing like feed aggregator
     *
     * @param string $entryTitle
     * @param int $timestamp entries created after this time
     * @return mixed array(entryId,title, content, order, author, dateCreated, dateEdited)
     */
    public function getEntriesByTitleAfterGivenTime($entryTitle, $timestamp){
        $data = $this->fetchEntry($entryTitle, -1);
        $doc = $this->createDomDocumentFromData($data);
        $numOfPages = $this->getNumberOfPages($doc); 
        $details = array();

        for($i=$numOfPages;$i>=0;$i--){
            $detailsOfPage =  $this->getEntryByTitle($entryTitle, $i);

            $detailsOfPage = array_reverse((array)$detailsOfPage);
            $itemCount = count ( $detailsOfPage );

            foreach($detailsOfPage as $key=>$value){
                if($value['dateCreated'] < $timestamp) {
                    $foundKey = ($itemCount - $key ) + 1; 
                    $i = 0;
                    break;
                }
            }
            $details = array_merge($details, $detailsOfPage);
        }

        $details = array_reverse($details);

        $foundEntries = array_slice($details, $foundKey);    
        $return = empty($foundEntries)?false:$foundEntries;

        return $return;
    }




        

}
