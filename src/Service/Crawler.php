<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\CrawlerResponse;
use App\DTO\CrawlerResponseElement;
use App\Exception\CrawlerRequestException;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use Symfony\Component\HttpFoundation\Request;

final class Crawler
{
    public function __construct(
        private readonly HttpBrowser $browser
    ) {
    }

    /**
     * @throws CrawlerRequestException
     */
    public function getHtmlElementsByClassFromWebsite(string $websiteUrl, string $findClass): CrawlerResponse
    {
        try {
            $site = $this->browser->request(Request::METHOD_GET, $websiteUrl);
        } catch (\Throwable $e) {
            throw new CrawlerRequestException((string) $e);
        }
        $response = $this->browser->getResponse();

        if (!($response instanceof Response)) {
            throw new CrawlerRequestException(\sprintf('Invalid response class (should return object with class %s instead of %s).', Response::class, \get_class($response)));
        }

        $elements = $site->filterXPath(\sprintf('//*[@class="%s"]', $findClass))->each(function (DomCrawler $parentCrawler) {
            $loopElements = [];
            foreach ($parentCrawler->getIterator() as $item) {
                if (isset($item->tagName) && isset($item->textContent)) {
                    $loopElements[] = new CrawlerResponseElement($item->tagName, $item->textContent);
                }
            }

            return $loopElements;
        });

        return new CrawlerResponse(
            $websiteUrl,
            $response->getStatusCode(),
            $elements
        );
    }
}
