<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\PublicationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
#[ORM\Table(name: "publication")]
#[ORM\Index(name: "fk_pub_page_idpp", columns: ["idp"])]
#[ORM\Entity(repositoryClass: PublicationRepository::class)]
class Publication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_p", type: "integer")]
    private ?int $id_p = null;
    

    #[ORM\Column(length: 65535)]
    #[Assert\NotBlank(message: "Veuillez entrer la description")]
    #[Assert\Length(min: 20, max: 500, minMessage: "La description est trop courte", maxMessage: "La description est trop longue")]
    private ?string $description = null;

    #[ORM\Column(length: 500)]
   // #[Assert\NotBlank(message: "Veuillez sélectionner une image")]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez entrer le nom")]
    #[Assert\Length(min: 1, minMessage: "Veuillez entrer le nom")]
    private ?string $nomp = null;

    #[ORM\ManyToOne(targetEntity: Page::class, inversedBy: 'publications')]
    #[ORM\JoinColumn(name: 'idp', referencedColumnName: 'idp', nullable: false)]
    private ?Page $pageRelation = null;
    

    public function getId_p(): ?int
    {
        return $this->id_p;
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getNomp(): ?string
    {
        return $this->nomp;
    }

    public function setNomp(string $nomp): static
    {
        $this->nomp = $nomp;
        return $this;
    }

    public function getPageRelation(): ?Page
    {
        return $this->pageRelation;
    }

    public function setPageRelation(?Page $pageRelation): static
    {
        $this->pageRelation = $pageRelation;
        return $this;
    }
}