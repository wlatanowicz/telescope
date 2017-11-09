<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

use JMS\Serializer\Annotation\Type;

class BinaryImages
{
    /**
     * @var BinaryImage[]
     * @Type("array<wlatanowicz\AppBundle\Data\BinaryImage>")
     */
    private $images;

    /**
     * BinaryImages constructor.
     * @param BinaryImage[] $images
     */
    public function __construct(array $images)
    {
        if (count($images) < 1) {
            throw new \Exception('No images given');
        }
        $this->images = $images;
    }

    /**
     * @return BinaryImage[]
     */
    public function getImages(): array
    {
        return $this->images;
    }

    public function getImageByMimetype(string $mimetype): BinaryImage
    {
        foreach($this->images as $image) {
            if ($image->getMimetype() === $mimetype) {
                return $image;
            }
        }
        throw new \Exception("No image for mimetype {$mimetype}");
    }
}
