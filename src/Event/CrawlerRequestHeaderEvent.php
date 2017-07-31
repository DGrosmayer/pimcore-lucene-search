<?php

namespace LuceneSearchBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class CrawlerRequestHeaderEvent extends Event
{
    /**
     * @var Resource
     */
    private $headers = [];

    /**
     * @param $header
     *
     * @throws \Exception
     */
    public function addHeader($header)
    {
        if (!isset($header['name'])) {
            throw new \Exception('lucene search crawler header property "name" missing');
        } else if (!isset($header['value'])) {
            throw new \Exception('lucene search crawler header property "value" missing');
        } else if (!isset($header['identifier'])) {
            throw new \Exception('lucene search crawler header property "identifier" missing');
        }

        $this->headers[] = $header;
    }

    /**
     * @return Resource
     */
    public function getHeaders()
    {
        return $this->headers;
    }

}