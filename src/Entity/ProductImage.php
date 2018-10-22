<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="product_image")
 * @Vich\Uploadable
 */
class ProductImage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;


    /**
     * @var File
     *
     * @Vich\UploadableField(
     *     mapping="products",
     *     fileNameProperty="fileName",
     *     size="size",
     *     mimeType="mimeType",
     *     originalName="originalName",
     *     dimensions="dimensions")
     *
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fileName;

    /**
     * @ORM\Column(type="integer")
     */
    private $size;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mimeType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $originalName;

    /**
     * @var array
     *
     * @ORM\Column(type="simple_array", length=255, nullable=true)
     */
    private $dimensions;

    /**
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updateAt;

    public function __construct()
    {
        $this->updateAt = new \DateTime();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return File
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File $imageFile
     */
    public function setImageFile(?File $imageFile): void
    {
        $this->imageFile = $imageFile;

        if ($imageFile !== null)
        {
            $this->updateAt = new \DateTime();
        }
    }



    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(?string $originalName): self
    {
        $this->originalName = $originalName;

        return $this;
    }

    public function getDimensions(): ?array
    {
        return $this->dimensions;
    }

    public function setDimensions(?array $dimensions): self
    {
        $this->dimensions = $dimensions;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getUpdateAt(): ?\dateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(int $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }


    /**
     * @Assert\Callback()
     */
    public function validateFile(ExecutionContextInterface $context)
    {
        $constrain = new Assert\NotBlank();
        $context
            ->getValidator()
            ->inContext($context)
            ->atPath('imageFile')
            ->validate($this->fileName ?: $this->imageFile, $constrain, [Constraint::DEFAULT_GROUP]);
    }
}
