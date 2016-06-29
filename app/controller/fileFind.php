<?php
/**
 * Ivan Dyachuk
 * Website: #
 * Social profiles
 * Email: wrximpreza1987@gmail.com
 * Copyright (c) 2016. All rights
 */

class fileFind implements parserInterface
{


    /**
     * fileFind constructor.
     * @param $urls
     */
    public function __construct($urls)
    {
        $this->url = explode(PHP_EOL, $urls);
    }

    /**
     * @return array
     */
    public function load()
    {

        return $this->url;
    }

    /**
     * @return array
     */
    private function loadFile()
    {
        $file = file("file/url.txt");
        return $file;
    }

}