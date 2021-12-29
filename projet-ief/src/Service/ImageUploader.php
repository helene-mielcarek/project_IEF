<?php

namespace App\Service;

use App\Entity\Event;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\String\Slugger\SluggerInterface;

class ImageUploader
{
    private $targetDirectory;

    public function __construct(string $targetDirectory, string $targetDirectoryCategory, string $targetDirectoryEvent, SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
        $this->targetDirectory = $targetDirectory;
        $this->targetDirectoryCategory = $targetDirectoryCategory;
        $this->targetDirectoryEvent = $targetDirectoryEvent;

    }

    public function createFileName($extension)
    {
        return uniqid() . '.' . $extension;
    }
    public function upload(File $file, string $targetDirectory)
    {
        // dd($image);
        // $originaleImageName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // $safeImageName = $this->slugger->slug($originaleImageName);
        // $newImageName = $safeImageName . '-' . uniqid().'.'.$file->guessExtension();
        
        $newFileName = $this->createFileName($file->guessExtension());

        try {
            $file->move(
                $targetDirectory, $newFileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $newFileName;
    }

    public function uploadEventImage(Event $event, ?File $image)
    {
        $this->targetDirectory = $this->targetDirectory . "/img_event";
        // dd($this);
        if ($image !==null){
            $fileName = $this->upload($image, $this->targetDirectory);
            $event->setImg($fileName);
        } else {
            $event->setImg(null);
        }
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}