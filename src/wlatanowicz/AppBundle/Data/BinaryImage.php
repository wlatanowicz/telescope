<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

class BinaryImage
{
    /**
     * @var string
     */
    private $data;

    /**
     * @var string
     */
    private $mimetype;

    public function __construct(string $data, string $mimetype = null)
    {
        $this->data = $data;
        $this->mimetype = $mimetype;
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    public static function fromGdImage(GdImage $image, string $mimetype): self
    {
        ob_start();
        imagejpeg($image->getResource());
        $imageData = ob_get_clean();

        return new self(
            $imageData,
            $mimetype
        );
    }
}
