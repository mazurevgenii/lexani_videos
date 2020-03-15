<?php

namespace App\Modules;

use App\Entity\LexaniVideos;
use App\Entity\UserParameters;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class YoutubeGrabber
{

    public function getContentsFromYouTube(EntityManagerInterface $em)
    {
        $parseUrl = 'https://lexani.com/videos';
        $client = HttpClient::create();
        $response = $client->request('GET', $parseUrl);

        try {
            $code = $response->getStatusCode();

            if ($code !== 200) {
                throw new TransportException('Problems with getting data from ' . $parseUrl . ', Status code: ' . $code);
            }
        } catch (TransportExceptionInterface $e) {
            echo $e->getMessage();
            die;
        }

        $content = $response->getContent();

        $crawler = new Crawler($content);

        $videoId = $crawler
            ->filterXpath('//div[@class="gallery thumb_list"]/div[@data-src]')
            ->each(function (Crawler $node) {
                return $node->attr('data-src');
            });

        $fp = fopen('Broken Links.csv', 'w');
        fputcsv($fp, ['Link', 'Error message']);


        $userParameters = new UserParameters();
        $userParameters->setIp('IP');
        $userParameters->setBrowser('Browser');
        $em->persist($userParameters);

        foreach ($videoId as $key => $id) {

            if (strlen($id) < 11) {
                fputcsv($fp, ["https://www.youtube.com/watch?v=" . $id, 'incorrect Video ID length']);
                continue;
            }

            $youtubeLink = "https://www.googleapis.com/youtube/v3/videos?id=" . $id . "&key=" . $_ENV['API_KEY'] . "&part=snippet&fields=items(snippet(title,description))";

            $youtubeLinkResponse = $client->request('GET', $youtubeLink);

            try {
                $statusCode = $youtubeLinkResponse->getStatusCode();

                if ($statusCode !== 200) {
                    throw new TransportException($youtubeLinkResponse);
                }
            } catch (TransportExceptionInterface $e) {
                fputcsv($fp, ["https://www.youtube.com/watch?v=" . $id, $statusCode]);

                continue;
            }

            $data = $youtubeLinkResponse->getContent();

            $json = json_decode($data, true);

            $description = $json['items'][0]['snippet']['description'];
            $title = $json['items'][0]['snippet']['title'];
            $youtubeLink = "https://www.youtube.com/watch?v=" . $id;
            $thumbnails = "http://img.youtube.com/vi/" . $id . "/0.jpg";
            $parseType = "new";


            $lexaniVideos = new LexaniVideos();
            $lexaniVideos
                ->setYoutubeLink($youtubeLink)
                ->setTitle($title)
                ->setDescription($description)
                ->setThumbnail($thumbnails)
                ->setParseType($parseType)
                ->setUserParameters($userParameters);

            $em->persist($lexaniVideos);
            if ($key > 5){
                break;
            }
        }
        fclose($fp);
        $em->flush();
    }
}