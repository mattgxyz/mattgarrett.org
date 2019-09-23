<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Documentation", mappedBy="category", orphanRemoval=true)
     */
    private $documentations;

    /**
     * @ORM\Column(type="string", length=512)
     */
    private $path;



    public function __construct()
    {
        $this->documentations = new ArrayCollection();
        $this->parentCategories = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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


    public function getSubcategories(): ?self
    {
        return $this->subcategories;
    }

    public function setSubcategories(?self $subcategories): self
    {
        $this->subcategories = $subcategories;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getParentCategories(): Collection
    {
        return $this->parentCategories;
    }

    public function addParentCategory(self $parentCategory): self
    {
        if (!$this->parentCategories->contains($parentCategory)) {
            $this->parentCategories[] = $parentCategory;
            $parentCategory->setSubcategories($this);
        }

        return $this;
    }

    public function removeParentCategory(self $parentCategory): self
    {
        if ($this->parentCategories->contains($parentCategory)) {
            $this->parentCategories->removeElement($parentCategory);
            // set the owning side to null (unless already changed)
            if ($parentCategory->getSubcategories() === $this) {
                $parentCategory->setSubcategories(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Documentation[]
     */
    public function getDocumentations(): Collection
    {
        return $this->documentations;
    }

    public function addDocumentation(Documentation $documentation): self
    {
        if (!$this->documentations->contains($documentation)) {
            $this->documentations[] = $documentation;
            $documentation->setCategory($this);
        }

        return $this;
    }

    public function removeDocumentation(Documentation $documentation): self
    {
        if ($this->documentations->contains($documentation)) {
            $this->documentations->removeElement($documentation);
            // set the owning side to null (unless already changed)
            if ($documentation->getCategory() === $this) {
                $documentation->setCategory(null);
            }
        }

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }
}
