<?php namespace Zotyo\AjaxFileUploader;

use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Zotyo\AjaxFileUploader\Exceptions\FileGeneratorException;
use Zotyo\AjaxFileUploader\Exceptions\FileNotFoundException;
use Session;

class File
{

    private $name;

    public function exists()
    {
        $path = $this->path();
        return file_exists($path) && is_file($path);
    }

    public function path()
    {
        return public_path($this->getRelativePath());
    }

    public function url()
    {
        return asset($this->getRelativePath());
    }

    public function file()
    {
        return $this->name;
    }

    public function relPath()
    {
        return $this->getRelativePath();
    }    

    public function toArray()
    {
        $descriptor = new FileDescriptor($this);

        return [
            'url'    => $this->url(),
            'file'   => $this->file(),
            'client' => $descriptor->read()['client']
        ];
    }

    public function getDescription()
    {
        $desc = new FileDescriptor($this);
        return $desc->read();
    }

    public function wasUploadedInCurrentSession()
    {
        return array_get($this->getDescription(), 'session.id') === Session::getId();
    }

    /**
     * Saves an uploaded file
     * @param UploadedFile $file
     * @return static
     * @throws FileGeneratorException
     */
    public static function store(UploadedFile $file)
    {
        $instance = self::generateNew();
        $path     = $instance->path();
        $file->move(dirname($path), basename($path));

        $desc = new FileDescriptor($instance);
        $desc->create($file);

        return $instance;
    }

    /**
     * Finds an already uploaded file
     * @param string $name
     * @return \static
     * @throws FileNotFoundException
     */
    public static function findOrFail($name)
    {
        $instance       = new static;
        $instance->name = $name;
        if (!$instance->exists()) {
            throw new FileNotFoundException;
        }

//        touch($instance->path());

        return $instance;
    }

    /**
     * Finds an already uploaded file. If no match, will try to use $default as fallback
     * @param string $name
     * @return \static
     */
    public static function find($name, $default = 'default.jpeg')
    {
        $instance       = new static;
        $instance->name = $name;

        if (!$instance->exists()) {
            $instance->name = $default;
        }

//        touch($instance->path());

        return $instance;
    }

    private function __construct()
    {
        ;
    }

    private function getRelativePath()
    {
        return config('file-uploader.relative_path') . '/' . $this->name;
    }

    /**
     * 
     * @return \static
     * @throws FileGeneratorException
     */
    private static function generateNew()
    {
        $attemts_left = 3;
        do {
            $instance       = new static;
            $instance->name = $instance->uuid();
            if (!$attemts_left--) {
                throw new FileGeneratorException();
            }
        } while ($instance->exists());

        return $instance;
    }

    private function uuid()
    {
        return Uuid::uuid4();
    }

    public function __toString()
    {
        return $this->file();
    }
}
