<?php


namespace App\Modules;


use App\Entity\LexaniVideos;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class YoutubeGrabber
{

    public function getContentsFromYouTube(EntityManagerInterface $em)
    {

        $client = HttpClient::create();
        $response = $client->request('GET', 'https://lexani.com/videos');
        $content = $response->getContent();

        $crawler = new Crawler($content);

        $videoId = $crawler
            ->filterXpath('//div[@class="gallery thumb_list"]/div[@data-src]')
            ->each(function (Crawler $node, $i) {
                return $node->attr('data-src');
            });

        foreach ($videoId as $key=>$id) {
            /*$youtubeLink = "https://www.googleapis.com/youtube/v3/videos?id=" . $videoId . "&key=AIzaSyAyH7TxTT-Mq0qmEvJ-NSMiOmohZ53JOl4&part=snippet&fields=items(snippet(title,description))";*/

            /*$youtubeLinkResponse = $client->request('GET', $youtubeLink);
            $data = $youtubeLinkResponse->getContent();

            $json = json_decode($data, true);

            $title = $json[items][0][snippet][title];
            $description = $json[items][0][snippet][description];
            $thumbnails = $json[items][0][snippet][thumbnails][high][url];*/

            if ($key>4){
                break;
            }

            $youtubeLink = "https://www.youtube.com/watch?v=" . $id;
            $title = "Video Title".rand(1, 20);
            $description = "Video Description" . rand(21, 40);
            $thumbnails = "http://img.youtube.com/vi/" . $id . "/0.jpg";
            $parseType = "new";

            $lexaniVideos = new LexaniVideos();
            $lexaniVideos->setYoutubeLink($youtubeLink)
                ->setTitle($title)
                ->setDescription($description)
                ->setThumbnail($thumbnails)
                ->setParseType($parseType);

            $em->persist($lexaniVideos);
        }
        $em->flush();
    }
}