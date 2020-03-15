<?php

namespace App\Modules;

use App\Repository\LexaniVideosRepository;

class CsvSaver
{
    public function saveToCsv(LexaniVideosRepository $repository)
    {
        $newVideoData = $repository->findNewVideoData();

        $fp = fopen('result.csv', 'w');
        fputcsv($fp, ['Link', 'Title', 'Description', 'Thumbnail']);

        foreach ($newVideoData as $newData) {
            fputcsv($fp, [
                $newData->getYoutubeLink(),
                $newData->getTitle(),
                $newData->getDescription(),
                $newData->getThumbnail(),
            ]);
        }
        fclose($fp);
    }

    public function saveToCsvWithCompare(LexaniVideosRepository $repository)
    {
        $newVideoData = $repository->findNewVideoData();
        $oldVideoData = $repository->findOldVideoData();

        $fp = fopen('result_with_compare.csv', 'w');
        fputcsv($fp, [
            'Link_old',
            'Link',
            'Title_old',
            'Title',
            'Description_old',
            'Description',
            'Thumbnail_old',
            'Thumbnail'
        ]);

        foreach ($newVideoData as $newData) {
            foreach ($oldVideoData as $oldData) {
                if ($newData->getYoutubeLink() === $oldData->getYoutubeLink()) {
                    fputcsv($fp, [
                        $oldData->getYoutubeLink(),
                        $newData->getYoutubeLink(),
                        $oldData->getTitle(),
                        $newData->getTitle(),
                        $oldData->getDescription(),
                        $newData->getDescription(),
                        $oldData->getThumbnail(),
                        $newData->getThumbnail(),
                    ]);
                }
            }
        }
        foreach ($newVideoData as $newData) {
            $newDataYoutubeLink[] = $newData->getYoutubeLink();
        }
        foreach ($oldVideoData as $oldData) {
            $oldDataYoutubeLink[] = $oldData->getYoutubeLink();
        }

        $youtubeLinksExistOnlyInNewData = array_diff($newDataYoutubeLink, $oldDataYoutubeLink);
        $youtubeLinksExistOnlyInOldData = array_diff($oldDataYoutubeLink, $newDataYoutubeLink);

        if (!empty($youtubeLinksExistOnlyInNewData)) {
            foreach ($youtubeLinksExistOnlyInNewData as $onlyNewLink) {
                foreach ($newVideoData as $newData) {
                    if ($onlyNewLink === $newData->getYoutubeLink()) {
                        fputcsv($fp, [
                            '',
                            $newData->getYoutubeLink(),
                            '',
                            $newData->getTitle(),
                            '',
                            $newData->getDescription(),
                            '',
                            $newData->getThumbnail(),
                        ]);
                    }
                }
            }
        }

        if (!empty($youtubeLinksExistOnlyInOldData)) {
            foreach ($youtubeLinksExistOnlyInOldData as $onlyOldLink) {
                foreach ($oldVideoData as $oldData) {
                    if ($onlyOldLink === $oldData->getYoutubeLink()) {
                        fputcsv($fp, [
                            $oldData->getYoutubeLink(),
                            '',
                            $oldData->getTitle(),
                            '',
                            $oldData->getDescription(),
                            '',
                            $oldData->getThumbnail(),
                            '',
                        ]);
                    }
                }
            }
        }

        fclose($fp);
    }
}