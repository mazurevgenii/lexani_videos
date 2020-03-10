<?php


namespace App\Modules;


use App\Entity\LexaniVideos;
use App\Repository\LexaniVideosRepository;
use Doctrine\ORM\EntityManagerInterface;

class CsvSaver
{
    public function saveToCsv($array) {

        $fp = fopen('result.csv', 'w');

        fputcsv($fp, ['Link','Title','Description','Thumbnail']);

        foreach ($array as $fields) {
            $newArray =  (array) $fields;
            fputcsv($fp, $newArray);
        }
    }


    // TODO хуйню сохраняет, переделать логику
    public function saveToCsvWithCompare (EntityManagerInterface $em) {

        $repository = $em->getRepository(LexaniVideos::class);

        $newVideoData = $repository->findBy(['parseType' => 'new']);
        $oldVideoData = $repository->findBy(['parseType' => 'old']);

        $fp = fopen('result_with_compare.csv', 'w');
        fputcsv($fp, ['Link_old','Link','Title_old','Title','Description_old','Description','Thumbnail_old','Thumbnail']);

        foreach ($newVideoData as $newData) {
            foreach ($oldVideoData as $oldData) {
                if ($newData->getYoutubeLink() === $oldData->getYoutubeLink()){
                    fputcsv($fp, [$oldData->getYoutubeLink(),
                        $newData->getYoutubeLink(),
                        $oldData->getTitle(),
                        $newData->getTitle(),
                        $oldData->getDescription(),
                        $newData->getDescription(),
                        $oldData->getThumbnail(),
                        $newData->getThumbnail()]);
                }
            }

            fputcsv($fp, ['', $newData->getYoutubeLink(),'',$newData->getTitle(),'',$newData->getDescription(),'',$newData->getThumbnail()]);
        }

        foreach ($oldVideoData as $oldData){
            foreach ($newVideoData as $newData) {
                if ($newData->getYoutubeLink() === $oldData->getYoutubeLink()){
                    break;
                }
            }

            fputcsv($fp, [$oldData->getYoutubeLink(),'', $oldData->getTitle(),'',$oldData->getDescription(),'',$oldData->getThumbnail(),'']);
        }

        fclose($fp);

    }
}