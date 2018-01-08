<?php namespace Zotyo\AjaxFileUploader;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Session;

class FileDescriptor
{

    private $path;

    public function create(UploadedFile $uploaded_file)
    {
        file_put_contents($this->path, json_encode([
            'client' => [
                'mime' => $uploaded_file->getClientMimeType(),
                'ext' => $uploaded_file->getClientOriginalExtension(),
                'name' => $uploaded_file->getClientOriginalName(),
                'size' => $uploaded_file->getClientSize()
            ],
            'session' => [
                'id' => Session::getId()
            ]
        ]));
    }

    public function read()
    {
        return @\json_decode(file_get_contents($this->path), true);
    }

    public function __construct(File $file)
    {
        $this->path = $file->path() . '.json';
    }
}
