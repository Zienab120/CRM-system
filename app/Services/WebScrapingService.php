<?php

namespace App\Services;

use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use SymfonyComponentBrowserKitHttpBrowser;
use SymfonyComponentHttpClientHttpClient;

class WebScrapingService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function scrape($url)
    {

        $browser = new HttpBrowser(HttpClient::create());
        $crawler = $browser->request('GET', 'https://quotes.toscrape.com/');
        $html = $crawler->outerHtml();

        return $html;
    }
}
