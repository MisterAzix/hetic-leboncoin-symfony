<?php

namespace App\service;
//créer un service
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Symfony\Contracts\Cache\CacheInterface;

//Le service fait appelle à d'autres services
class MarkdownHelper
{
    private $markdownParser;
    private $cache;
    private $isDebug;

    public function __construct(MarkdownParserInterface $markdownParser, CacheInterface $cache, $isDebug)
    {
        $this->markdownParser = $markdownParser;
        $this->cache = $cache;
        $this->isDebug =$isDebug;
    }
    public function parse(string $source) : string {
        //En debug, pas de cache
        if ($this->isDebug) {
            return $this->markdownParser->transformMarkdown($source);
        }
        //Sinon : cache
        return $this->cache-get('markdown_'. md5($source), function () use ($source) {
            return $this->markdownParser->transformMakrdown($source);
            });
    }
    public function getStrong($source): string {
        return '<strong>'.$source.'</strong>';
    }
}