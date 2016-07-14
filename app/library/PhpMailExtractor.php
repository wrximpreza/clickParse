<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
/**
 * Class PhpMailExtractor
 */
class PhpMailExtractor
{

    /**
     * Scan your disk and store all found email-addresses to a file.
     *
     * Recursivly scans all subfolders of the given start dir.
     *
     * Example of use from command prompt:
     * php email-extract.php "c:/myfolder" "c:/mail-list.txt"
     *
     * @param string $dir Directory where to start the scan
     * @param string $targetFile Filename where to store the result.
     * @param boolean $flushTargetFile Clear/empty the target file [Optional]
     * @param array $ignoreEmails If you wish to exclude some email addresses [Optional]
     */

    /*
     * @var file with black list of email
     */
    public static $blackListFile = 'file/blacklist.txt';

    /**
     * @var list of emails
     */
    public static $blackList;

    public function __construct()
    {
      

    }

    /**
     * @param $html
     * @return array
     */
    public static function extract($html)
    {


        $regex = '/[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})/i';
        //$regex = '/[a-z0-9_\-\+]+@[a-z0-9\-]+\.([a-z]{2,3})(?:\.[a-z]{2})?/i';
        //$regex ='/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i';
        $emails = array();
        $matches = array(); //create array
        preg_match_all($regex, $html, $matches);
        $blackList =  self::getBlackList();

        if (count($matches[0])>0) {
            foreach ($matches[0] as $email) {
                if(!in_array($email, $blackList)) {
                    $emails[] = strtolower($email);
                }
            }
        }


        return array_unique($emails);

    }

    /**
     * @param $html
     * @return mixed|string
     */
    public static function pageTitle($html)
    {
        $matches = array();
        if (preg_match('/<title>(.*)<\/title>/iU', $html, $matches)) {

            return $matches[1];
        }
        return '';
    }

    /**
     * Get black list of email
     */
    public static function getBlackList()
    {

        $list = file(self::$blackListFile);
        $listData = array();
        foreach ($list as $value) {
            $listData[] = trim($value);
        }
        return $listData;
    }
}