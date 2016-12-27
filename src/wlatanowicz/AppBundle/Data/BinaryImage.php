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
     * @var string|null
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
    public function getMimetype()
    {
        return $this->mimetype;
    }

    public static function fromBinary64Image(Binary64Image $binary64Image): self
    {
        return new self(
            base64_decode($binary64Image->getData()),
            $binary64Image->getMimetype()
        );
    }
}
