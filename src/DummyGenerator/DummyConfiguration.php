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
     * @var array
     */
    protected $attributes;

    /**
     * DummyConfiguration constructor.
     *
     * @param string $filePath
     * @param array $attributes
     * @param string $type
     * @throws \Exception
     * @internal param int $width
     */
    public function __construct(string $filePath, array $attributes, string $type = null)
    {
        if (!empty($filePath) && !is_string($filePath)) {
            throw new \Exception('File path must be of type string in ' . static::class);
        }

        $pathInfo = pathinfo($filePath);
        $this->directory = $pathInfo['dirname'];
        $this->fileName = $pathInfo['filename'];
        $this->extension = $pathInfo['extension'] ?? null;
        $this->type = $type ?: $this->extension;
        $this->attributes = $attributes;
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
     * @param string $attribute
     * @return mixed
     */
    public function getAttribute(string $attribute)
    {
        return $this->attributes[$attribute];
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