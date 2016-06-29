<?php

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
    public static function extract($html)
    {
        $regex = '/[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})/i';
        //$regex = '/[a-z0-9_\-\+]+@[a-z0-9\-]+\.([a-z]{2,3})(?:\.[a-z]{2})?/i';
        //$regex ='/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i';
        $emails = array();
        $matches = array(); //create array
        preg_match_all($regex, $html, $matches);
  
        if (count($matches[0])) {
            foreach ($matches[0] as $email) {
                $emails[] = strtolower($email);
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
}