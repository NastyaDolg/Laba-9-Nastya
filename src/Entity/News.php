<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\NewsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: ['get'],
)]

#[ORM\Entity(repositoryClass: NewsRepository::class)]
class News
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;


    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'datetime', options:["default", "CURRENT_TIMESTAMP"])]
    private $dateLoad;


    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $viewsNum;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'News')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;


    #[ORM\Column(type: 'text')]
    private $content;

    #[ORM\OneToMany(mappedBy: 'new', targetEntity: Comments::class, orphanRemoval: true)]
    private $comments;

    #[ORM\Column(type: 'string', length: 255)]
    private $fotopath;

    #[ORM\Column(type: 'boolean')]
    private $active = false;

    public function __construct()
    {
        $this->comments = new ArrayCollection();

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

    public function getDateLoad(): ?\DateTimeInterface
    {
        return $this->dateLoad;
    }

    public function setDateLoad(\DateTimeInterface $dateLoad): self
    {
        $this->dateLoad = $dateLoad;

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

    public function getViewsNum(): ?int
    {
        return $this->viewsNum;
    }

    public function setViewsNum(?int $viewsNum): self
    {
        $this->viewsNum = $viewsNum;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getComments(): Collection
    {
        $commentss = $this->comments->toArray();
        $result = array_filter($commentss, function($v, $k) {
            return $v->getActive() == 1;
        }, ARRAY_FILTER_USE_BOTH);

        return new ArrayCollection($result);
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setNew($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            if ($comment->getNew() === $this) {
                $comment->setNew(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->name;
    }

    public function getFotopath(): ?string
    {
        return $this->fotopath;
    }

    public function setFotopath(string $fotopath): self
    {
        $this->fotopath = $fotopath;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
