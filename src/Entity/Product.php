<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
//"put"={"denormalization_context"={"groups"={cc}}},
//*     denormalizationContext={"product_write"}},
/**
 * @ApiResource(
 *  attributes={
 *      "pagination_enabled"=true,
 *      "pagination_items_per_page"=20,
 *      "order": {"price":"desc"}
 *  },
 *  normalizationContext={"groups"={"product_read"}},
 *  collectionOperations={
 *          "get"={},
 *          "post"={}
 *     },
 *     itemOperations={
 *          "get"={},
 *          "delete"={},
 *          "put"={},
 *     },
 *  subresourceOperations={
 *  "api_categories_products_get_subresource"={
 *          "normalization_context"={"groups"={"product_subresource"}}
 *  }
 * },
 * )
 * @ApiFilter(OrderFilter::class, properties={"price","reference"})
 * @ApiFilter(SearchFilter::class, properties={"name":"partial","category.name":"partial"})
 * @ApiFilter(SearchFilter::class, properties={"name":"partial","name":"partial"})
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @Groups({"product_read" ,"category_read" ,"user_read" ,"comment_read", "product_subresource"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"product_put"})
     * @Groups({"card_read"})
     * @Groups({"product_read" ,"category_read" ,"user_read" ,"comment_read", "product_subresource"})
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom est obligatoire")
     * @Assert\Length(min=3, minMessage="Le nom doit faire entre 3 et 255 caractères", max=255, maxMessage="Le nom doit faire entre 3 et 255 caractères")
     */
    private $name;

    /**
     * @Groups({"product_put"})
     * @Groups({"product_read" ,"category_read" ,"user_read" ,"comment_read", "product_subresource"})
     * @ORM\Column(type="float")
     * @Groups({"product_read" ,"category_read" ,"user_read" ,"comment_read", "product_subresource"})
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Le prix est obligatoire")
     * @Assert\Type(type="float", message="List price must be a numeric value")
     */
    private $price;

    /**
     * @Groups({"product_read" ,"category_read" ,"user_read" ,"comment_read", "product_subresource"})
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="La référence est obligatoire")
     * @Assert\Length(min=3, minMessage="La référence doit faire entre 3 et 255 caractères", max=255, maxMessage="La référence doit faire entre 3 et 255 caractères")
     */
    private $reference;

    /**
     * @Groups({"product_put"})
    private $description;

    /**
     * @Groups({"product_read"})
     * @Groups({"product_put"})
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="La description est obligatoire")
     * @Assert\Length(min=3, minMessage="La description doit faire entre 3 et 255 caractères", max=1024, maxMessage="La description doit faire entre 3 et 1024 caractères")
     */
    private $category;

    /**
     * @Groups({"product_read", "product_subresource"})
     * @ApiSubresource
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="product", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity=Card::class, mappedBy="product")
     * @Groups({"product_read"})
     */
    private $cards;

    /**
     * @Groups({"product_read", "product_subresource"})
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user_id;


    // GEDMO

    /**
     * @var \DateTime $created
     * @Groups({"product_read" ,"category_read" ,"user_read" ,"comment_read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", options={"default":"CURRENT_TIMESTAMP"})
     */
    private $created;

    /**
     * @var \DateTime $updated
     * @Groups({"product_read","category_read" ,"user_read" ,"comment_read"})
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;



    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"product_read" ,"category_read" ,"user_read" ,"comment_read"})
     */
    private $image;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Groups({"product_read" ,"category_read" ,"user_read" ,"comment_read"})
     */
    private $countInStock;


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
        $this->comments = new ArrayCollection();
        $this->cards = new ArrayCollection();
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setProduct($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getProduct() === $this) {
                $comment->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Card[]
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(Card $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->cards[] = $card;
            $card->addProduct($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->cards->contains($card)) {
            $this->cards->removeElement($card);
            $card->removeProduct($this);
        }

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCountInStock(): ?int
    {
        return $this->countInStock;
    }

    public function setCountInStock(int $countInStock): self
    {
        $this->countInStock = $countInStock;

        return $this;
    }
}
