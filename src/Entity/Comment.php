<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"comment_read"}},
 *     denormalizationContext={"groups"={"comment_write"}},
 *     subresourceOperations={
 *        "api_products_comments_get_subresource"={
 *          "normalization_context"={"groups"={"comment_subresource"}}
 *        }
 *     },
 *     paginationItemsPerPage=20,
 *     collectionOperations={
 *          "get"={},
 *          "post"={}
 *     },
 *     itemOperations={
 *          "get"={},
 *          "delete"={},
 *          "put"={},
 *     }
 * )
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @Groups({"comment_read","product_read" ,"user_read" ,"comment_subresource"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"comment_read", "comment_write", "product_read" ,"user_read" ,"comment_subresource"})
     * @ORM\Column(type="integer")
     */
    private $rating;

    /**
     * @Groups({"comment_read", "comment_write", "product_read" ,"user_read" ,"comment_subresource"})
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank(message="Une note est obligatoire")
     */
    private $content;

    /**
     * @Groups({"comment_read", "comment_write"})
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

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
     * @Groups({"comment_read", "comment_write", "comment_subresource"})
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;


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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
