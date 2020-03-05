<?php


namespace App\Modules;


use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class YoutubeGrabber
{

    public function getContentsFromHttpClient($url)
    {
        $client = HttpClient::create();
        $response = $client->request('GET', $url);
        $content = $response->getContent();

        return $content;
    }

    public function getVideoId($html)
    {
        $crawler = new Crawler($html);

        $videoId = $crawler
            ->filterXpath('//div[@class="gallery thumb_list"]/div[@data-src]')
            ->each(function (Crawler $node, $i) {
                return $node->attr('data-src');
            });

        return $videoId;
    }

    public function getFieldsFromYoutube ($videoId) // You tube API AIzaSyCgK1s0ZG8rQB2I9sJ-YmvrYrkQ16Qg7eE
    {
        $url = "https://www.googleapis.com/youtube/v3/videos?id=" . $videoId . "&key=AIzaSyCgK1s0ZG8rQB2I9sJ-YmvrYrkQ16Qg7eE&part=snippet&fields=items(id,snippet(title,description,thumbnails(high(url))))";

        $data = file_get_contents($url);
        $json = json_decode($data, true);

        $title = $json[items][0][snippet][title];
        $description = $json[items][0][snippet][description];
        $thumbnails = $json[items][0][snippet][thumbnails][high][url];

        return  $title;

    }
}