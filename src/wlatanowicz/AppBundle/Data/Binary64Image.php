<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Data;

class Binary64Image
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

    /**
     * @return string
     */
    public function getMimetype(): string
    {
        return $this->mimetype;
    }

    public static function fromBinaryImage(BinaryImage $binaryImage): self
    {
        return new self(
            base64_encode($binaryImage->getData()),
            $binaryImage->getMimetype()
        );
    }
}
