<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]

class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idu', type: 'integer')]
    private ?int $idu = null;
   

    #[ORM\Column(length:50)]
    private ?string $nom =  null;

    #[ORM\Column(length:50)]
    private ?string $prenom =  null;


    #[ORM\Column(name:"password", type:"text", length:0, nullable:false)]                                                                                                                                                                                                        
    private ?string $password;

 
    #[ORM\Column(name:"dateNaissance", type:"date", nullable:false)]
    private \DateTime $datenaissance;

 
    #[ORM\Column(name:"email", type:"string", length:50, nullable:false)]
    private ?string $email;


    #[ORM\Column(name:"num_tel", type:"integer", nullable:false)]
    private ?int $numTel;


    #[ORM\Column(name:"localisation", type:"string", length:50, nullable:false)]
    private ?string $localisation;


    #[ORM\Column(name:"role", type:"string", length:32, nullable:false, options:["default" => "Simple user"])]
    private ?string $role = 'Simple user';

 
 
 /*#[ORM\Column(name:"img", type:"blob", length:0, nullable:false)]
private ?string $img = '';*/

#[ORM\OneToMany(mappedBy: 'page', targetEntity: Page::class)]
private ?Collection $pages;

public function __construct()
{
    $this->pages = new ArrayCollection();
}

public function getIdu(): ?int
{
    return $this->idu;
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

public function getPrenom(): ?string
{
    return $this->prenom;
}

public function setPrenom(string $prenom): static
{
    $this->prenom = $prenom;

    return $this;
}

public function getPassword(): ?string
{
    return $this->password;
}

public function setPassword(string $password): static
{
    $this->password = $password;

    return $this;
}

public function getDatenaissance(): ?\DateTimeInterface
{
    return $this->datenaissance;
}

public function setDatenaissance(\DateTimeInterface $datenaissance): static
{
    $this->datenaissance = $datenaissance;

    return $this;
}

public function getEmail(): ?string
{
    return $this->email;
}

public function setEmail(string $email): static
{
    $this->email = $email;

    return $this;
}

public function getNumTel(): ?int
{
    return $this->numTel;
}

public function setNumTel(int $numTel): static
{
    $this->numTel = $numTel;

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

public function getRole(): ?string
{
    return $this->role;
}

public function setRole(string $role): static
{
    $this->role = $role;

    return $this;
}

/**
 * @return Collection<int, Page>
 */
public function getPages(): Collection
{
    return $this->pages;
}

public function addPageRelation(Page $page): static
{
    if (!$this->pages->contains($page)) {
        $this->pages->add($page);
        $page->setUserRelation($this);
    }

    return $this;
}

public function removePage(Page $page): static
{
    if ($this->pages->removeElement($page)) {
        // set the owning side to null (unless already changed)
        if ($page->getUserRelation() === $this) {
            $page->setUserRelation(null);
        }
    }

    return $this;
}


  
}