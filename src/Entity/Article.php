<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Veuillez entrer un nom!")
     *
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @Assert\NotBlank(message="Veuillez décrire votre produit!")
     *
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @Assert\NotBlank(message="Veuillez entrer un prix!")
     *
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="user_article")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $City;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $Zip;

    /**
     * @ORM\Column(type="datetime")
     */
    private $DateCreated;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="articles")
     */
    private $article_category;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="favorite")
     */
    private $users;

    public function __construct()
    {
        $this->setDateCreated(new \DateTime());
        $this->users = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->City;
    }

    public function setCity(string $City): self
    {
        $this->City = $City;

        return $this;
    }

    public function getZip(): ?string
    {
        return $this->Zip;
    }

    public function setZip(string $Zip): self
    {
        $this->Zip = $Zip;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->DateCreated;
    }

    public function setDateCreated(\DateTimeInterface $DateCreated): self
    {
        $this->DateCreated = $DateCreated;

        return $this;
    }

    public function getArticleCategory(): ?Category
    {
        return $this->article_category;
    }

    public function setArticleCategory(?Category $article_category): self
    {
        $this->article_category = $article_category;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addFavorite($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeFavorite($this);
        }

        return $this;
    }
}
