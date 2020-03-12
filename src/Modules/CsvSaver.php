<?php

namespace App\Modules;

use App\Repository\LexaniVideosRepository;

class CsvSaver
{
    public function saveToCsv(LexaniVideosRepository $repository)
    {
        $newVideoData = $repository->findVideoDataByParseType('new');

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
    }

    public function saveToCsvWithCompare(LexaniVideosRepository $repository)
    {
        $newVideoData = $repository->findVideoDataByParseType('new');
        $oldVideoData = $repository->findVideoDataByParseType('old');

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

        $yuotubeLinksExistOnlyInNewData = array_diff($newDataYoutubeLink, $oldDataYoutubeLink);
        $yuotubeLinksExistOnlyInOldData = array_diff($oldDataYoutubeLink, $newDataYoutubeLink);

        if (!empty($yuotubeLinksExistOnlyInNewData)) {
            foreach ($yuotubeLinksExistOnlyInNewData as $onlyNewLink) {
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

        if (!empty($yuotubeLinksExistOnlyInOldData)) {
            foreach ($yuotubeLinksExistOnlyInOldData as $onlyOldLink) {
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