<?php

namespace App\Entity;

use App\Repository\ConfigRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConfigRepository::class)]
class Config
{
    const Names = [
        'name' => 'Website name',
        'description' => 'Website description',
        'keywords' => 'Website keywords',
        'author' => 'Website author',
        'email' => 'Website email',
        'phone' => 'Website phone',
        'address' => 'Website address',
        'facebook'  => 'Website facebook',
        'twitter'  => 'Website twitter',
        'instagram'  => 'Website instagram',
        'youtube'  => 'Website youtube',
        'linkedin'  => 'Website linkedin',
        'google'  => 'Website google',
        'pinterest'  => 'Website pinterest',
        'github'  => 'Website github',
        'google_analytics'  => 'Website google analytics',
        'google_tag_manager'  => 'Website google tag manager',
        'google_adsense'  => 'Website google adsense',
    ];
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private $name;

    #[ORM\Column(type: 'text', nullable: true)]
    private $value;

    #[ORM\Column(type: 'string', length: 255)]
    private $label;

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

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }
}
