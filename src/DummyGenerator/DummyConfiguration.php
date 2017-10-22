<?php

namespace RozbehSharahi\Meedia\DummyGenerator;

class DummyConfiguration
{

    /**
     * @var string
     */
    protected $directory;

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @var string
     */
    protected $extension;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;

    /**
     * DummyConfiguration constructor.
     * @param int $width
     * @param int $height
     * @param string $filePath
     * @param string $type
     * @throws \Exception
     */
    public function __construct(int $width, int $height, string $filePath, string $type = null)
    {
        if (!empty($filePath) && !is_string($filePath)) {
            throw new \Exception('File path must be of type string in ' . static::class);
        }

        $this->width = $width;
        $this->height = $height;

        $pathInfo = pathinfo($filePath);
        $this->directory = $pathInfo['dirname'];
        $this->fileName = $pathInfo['filename'];
        $this->extension = $pathInfo['extension'] ?? null;
        $this->type = $type ?: $this->extension;
    }

    /**
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @param string $directory
     * @return DummyConfiguration
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
        return $this;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     * @return DummyConfiguration
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param string $extension
     * @return DummyConfiguration
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return DummyConfiguration
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     * @return DummyConfiguration
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     * @return DummyConfiguration
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        $directory = !empty($this->directory) ? $this->directory . '/' : '';
        $extension = !empty($this->extension) ? '.' . $this->extension : '';

        return $directory . $this->fileName . $extension;
    }

}