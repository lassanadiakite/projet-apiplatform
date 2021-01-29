<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"category_read"}},
 *     denormalizationContext={"groups"={"category_write"}},
 *     paginationItemsPerPage=20,
 *     collectionOperations={
 *          "get"={},
 *          "post"={}
 *     },
 *     itemOperations={
 *          "get"={},
 *          "put"={},
 *          "delete"={},
 *     }
 * )
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @UniqueEntity("name", message="Cette catégorie existe déjà")
 */
class Category
{
    /**
     * @Groups({"category_read","product_read"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *
     * @Groups({"category_read", "category_write", "product_read"})
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom de la catégorie est obligatoire")
     * @Assert\Length(min=3, minMessage="Le nom de la catégorie doit faire entre 3 et 255 caractères", max=255, maxMessage="Le nom de la catégorie doit faire entre 3 et 255 caractères")
     */
    private $name;

    /**
     *  @Groups({"category_read"})
     * @ApiSubresource
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="category")
     * @Assert\NotBlank(message="Le nom de la catégorie est obligatoire")
     * @Assert\Length(min=3, minMessage="Le nom de la catégorie doit faire entre 3 et 255 caractères", max=255, maxMessage="Le nom de la catégorie doit faire entre 3 et 255 caractères")
     */
    private $products;
        // GEDMO

    /**
     * @var \DateTime $created
     * @Groups({"comment_read" ,"product_read" ,"user_read" ,"comment_subresource"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", options={"default":"CURRENT_TIMESTAMP"})
     */
    private $created;

    /**
     * @var \DateTime $updated
     * @Groups({"comment_read" , "product_read" , "user_read" , "comment_subresource"})
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated(\DateTime $created): void
    {
        $this->created = $created;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated(): \DateTime
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     */
    public function setUpdated(\DateTime $updated): void
    {
        $this->updated = $updated;
    }



    // END GEDMO

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

        return $this;
    }
}
