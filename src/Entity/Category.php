<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/category/{id}', 
            requirements: ['id' => '\d+'],
            normalizationContext: ['groups' => 'category:item']),
        new GetCollection(
            uriTemplate: '/category',
            normalizationContext: ['groups' => 'category:list']),
        new Post(
            uriTemplate:'/cat',
        ),
        new Delete(
            uriTemplate:'/cat/delete',
        ),
    ],
    order: ['id' => 'ASC', 'firstname' => 'ASC'],
    paginationEnabled: true
)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["unique"])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(["toutes","unique",'article:item','article:list'])]
    private ?string $name = null;

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
    public function __toString(): string{
        return (string) $this->name;
    }
}
