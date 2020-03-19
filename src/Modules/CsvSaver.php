<?php

namespace App\Modules;

use App\Repository\LexaniVideosRepository;
use Symfony\Component\Filesystem\Filesystem;

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
                $newData->getThumbnails(),
            ]);
        }
        fclose($fp);

        $file = 'result.csv';
        header("Content-Length: ".filesize($file));
        header("Content-Disposition: attachment; filename=".$file);
        header("Content-Type: application/x-force-download; name=\"".$file."\"");
        ob_clean();
        flush();
        readfile($file, true);
        exit;
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
            'Thumbnails_old',
            'Thumbnails'
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
                        $oldData->getThumbnails(),
                        $newData->getThumbnails(),
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
                            $newData->getThumbnails(),
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
                            $oldData->getThumbnails(),
                            '',
                        ]);
                    }
                }
            }
        }

        fclose($fp);

        $file = 'result_with_compare.csv';
        header("Content-Length: ".filesize($file));
        header("Content-Disposition: attachment; filename=".$file);
        header("Content-Type: application/x-force-download; name=\"".$file."\"");
        ob_clean();
        flush();
        readfile($file, true);
        exit;
    }
}