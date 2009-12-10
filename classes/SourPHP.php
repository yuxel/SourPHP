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



/**
 * SourPHP 
 *
 * PHP Client to fetch data from http://sozluk.sourtimes.org
 * @author    Osman Yuksel <yuxel |AT| sonsuzdongu |DOT| com>
 */
class sourPHP{

    /**
     * fetchs data from Url
     *
     * @param  string $url
     * @return string Content of url
     */
    function fetchUrl($url) {
        $data = file_get_contents($url);
        return $data;
    }
 
}
