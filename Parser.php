<?php

namespace Parser;

require_once 'Curl.php';

/**
 * Simple content parser
 */
class Parser implements \IteratorAggregate
{

    /**
     * Parsed rows
     *
     * @var array
     */
    protected $parsedRows = [];

    /**
     * Constructor
     * 
     * @param string $url URL to parse
     */
    public function __construct(string $url)
    {
        $this->doParse($url);
    }

    /**
     * Iterator handler
     * 
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->parsedRows);
    }

    /**
     * Content parsing
     * 
     * @var string $url Url to parse
     * @throws \Exception
     */
    protected function doParse(string $url)
    {
        list($content, $error, $info) = Curl::request($url);

        if ($error) {
            throw new \Exception('Parse error: ' . $error);
        }

        if (!$content) {
            throw new \Exception('Couldn\'t get any content');
        }

        if (!$data = @json_decode($content, true)) {
            throw new \Exception('API JSON format error');
        }

        foreach ($data['stories'] as $story) {
            $this->parsedRows[] = [
                'title' => $this->escape($story['story_title']),
                'author' => $this->escape($story['story_authors']),
                'url' => $this->escape($story['story_permalink']),
                'created' => date('d.m.Y H:i:s', strtotime($story['story_date'])),
                'image' => $this->escape(!empty($story['image_urls'][0]) ? $story['image_urls'][0] : ''),
                'content' => $this->escape($story['story_content'])
            ];
        }
    }

    /**
     * Escapes all non-required chars and sanitizes the string
     * 
     * @param string $str Input string
     * @return string
     */
    protected function escape(string $str): string
    {
        return htmlspecialchars(strip_tags($str));
    }

}
