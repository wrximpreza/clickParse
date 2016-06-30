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
        $this->url = explode("\n", $urls);
    }

    /**
     * @return array
     */
    public function load()
    {
        $urls = array();
        foreach ($this->url as $item) {
            if($item!='')
                $urls[] = $item;
        }
       
        return $urls;
    }


}