<?php

namespace App\Entity;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Repository\PageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Table(name: "page")]
#[ORM\Index(name: "fk_page_utilisateur_idu", columns: ["idu"])]
#[ORM\Entity(repositoryClass: PageRepository::class)]
class Page
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idP', type: 'integer')]
    private ?int $idP = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Veuillez entrer le nom")]
    #[Assert\Length(min: 1, minMessage: "Veuillez entrer le nom")]
    private ?string $nom = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Veuillez entrer le contact")]
    #[Assert\Length(min: 8, max: 8, exactMessage: "Le contact doit contenir {{ limit }} chiffres")]
    private ?int $contact = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez choisir parmi la liste des catégories")]
    private ?string $categoriep = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez entrer la localisation")]
    private ?string $localisation = null;

    #[ORM\Column(length: 50000)]
    #[Assert\NotBlank(message: "Veuillez entrer la description")]
    #[Assert\Length(min: 20, max: 500, minMessage: "La description est trop courte", maxMessage: "La description est trop longue")]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\NotBlank(message: "Veuillez choisir une date d'ouverture")]
    private ?\DateTime $ouverture = null;

    #[ORM\Column(length: 500)]
   // #[Assert\NotBlank(message: "Veuillez sélectionner une image")]
    private ?string $image = null;

    

    #[ORM\Column(length: 500)]
    //#[Assert\NotBlank(message: "Veuillez sélectionner un logo")]
    private ?string $logo = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'pages')]
    #[ORM\JoinColumn(name: 'idu', referencedColumnName: 'idu', nullable: false)]
    private ?Utilisateur $userRelation = null;

    #[ORM\OneToMany(mappedBy: 'pageRelation', targetEntity: Publication::class)]
    private ?Collection $publications;

    public function __construct()
    {
        $this->publications = new ArrayCollection();
    }

    public function getIdP(): ?int
    {
        return $this->idP;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getContact(): ?int
    {
        return $this->contact;
    }

    public function setContact(int $contact): static
    {
        $this->contact = $contact;
        return $this;
    }

    public function getCategoriep(): ?string
    {
        return $this->categoriep;
    }

    public function setCategoriep(string $categoriep): static
    {
        $this->categoriep = $categoriep;
        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): static
    {
        $this->localisation = $localisation;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getOuverture(): ?\DateTimeInterface
    {
        return $this->ouverture;
    }

    public function setOuverture(\DateTimeInterface $ouverture): static
    {
        $this->ouverture = $ouverture;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): static
    {
        $this->logo = $logo;
        return $this;
    }

    public function getUserRelation(): ?Utilisateur
    {
        return $this->userRelation;
    }

    public function setUserRelation(?Utilisateur $userRelation): static
    {
        $this->userRelation = $userRelation;
        return $this;
    }

    public function getPublications(): Collection
    {
        return $this->publications;
    }



public function addPublication(Publication $publication): static
{
    if (!$this->publications->contains($publication)) {
        $this->publications->add($publication);
        $publication->setPageRelation($this);
    }
    return $this;
}

public function removePublication(Publication $publication): static
{
    if ($this->publications->removeElement($publication)) {
        if ($publication->getPageRelation() === $this) {
            $publication->setPageRelation(null);
        }
    }
    return $this;
}

}
