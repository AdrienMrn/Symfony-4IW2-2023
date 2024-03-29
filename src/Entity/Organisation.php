<?php

namespace App\Entity;

use App\Repository\OrganisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: OrganisationRepository::class)]
#[Vich\Uploadable]
class Organisation
{
    use Traits\Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Ce champ est obligatoire.')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Ce champ doit contenir au moins {{ limit }} caractères, vous avez saisie {{ value_length }}.',
        maxMessage: 'Ce champ ne doit pas dépasser {{ limit }} caractères.'
    )]
    private ?string $name = null;

    #[ORM\Column(length: 128, unique: true)]
    #[Gedmo\Slug(fields: ['name'])]
    private $slug;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[Vich\UploadableField(mapping: 'organisationLogo', fileNameProperty: 'imageName', size: 'imageSize')]
    #[Assert\Image(
        maxSize: '500k',
        minWidth: 500,
        maxSizeMessage: 'L\'image est trop lourde ({{ size }} {{ suffix }}). La taille maximale autorisée est de {{ limit }} {{ suffix }}.',
        minWidthMessage: 'La largeur de l\'image est trop petite ({{ width }}px). La largeur minimale attendue est de {{ min_width }}px.',
    )]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
    private ?int $imageSize = null;

    #[ORM\ManyToOne(inversedBy: 'organisations')]
    private ?Category $category = null;

    #[ORM\OneToMany(mappedBy: 'organisation', targetEntity: Session::class, orphanRemoval: true)]
    private Collection $sessions;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'organisations')]
    private Collection $members;

    public function __construct()
    {
        $this->sessions = new ArrayCollection();
        $this->members = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     * @return Organisation
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            $this->updatedAt = new \DateTime();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Session>
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    public function addSession(Session $session): static
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions->add($session);
            $session->setOrganisation($this);
        }

        return $this;
    }

    public function removeSession(Session $session): static
    {
        if ($this->sessions->removeElement($session)) {
            // set the owning side to null (unless already changed)
            if ($session->getOrganisation() === $this) {
                $session->setOrganisation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function isMember(User $user): bool
    {
        return $this->members->contains($user);
    }

    public function addMember(User $member): static
    {
        if (!$this->members->contains($member)) {
            $this->members->add($member);
        }

        return $this;
    }

    public function removeMember(User $member): static
    {
        $this->members->removeElement($member);

        return $this;
    }
}
