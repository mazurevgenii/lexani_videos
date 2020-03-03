<?php


namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class SaveToCsv
{
    public function new(EntityManagerInterface $em) {

        function getContentsFromHttpClient($url)
        {
            $client = HttpClient::create();
            $response = $client->request('GET', $url);
            $content = $response->getContent();

            return $content;
        }

        $url = 'https://lexani.com/videos';

        function getVideoId($url)
        {
            $html = getContentsFromHttpClient($url);

            $crawler = new Crawler($html);

            $videoId = $crawler
                ->filterXpath('//div[@class="gallery thumb_list"]/div[@data-src]')
                ->each(function (Crawler $node, $i) {
                    return $node->attr('data-src');
                });

            return $videoId;
        }


        if (isset($_POST['save']))
        {
            // TODO сохранение в CSV из базы
        }
    }
}