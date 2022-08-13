<?php

declare(strict_types=1);

namespace Dokobit\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dokobit\Repository\UploadFileStatisticsRepository;
use Symfony\Component\HttpFoundation\Request;

#[ORM\Entity(repositoryClass: UploadFileStatisticsRepository::class)]
class UploadFileStatistics
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 45)]
    private ?string $ipAddress;

    #[ORM\Column(length: 255, nullable: true)]
    private ?int $usageCountPerDay = null;

    public function __construct()
    {
        $this->ipAddress = (new Request())->getClientIp();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(?string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function getUsageCountPerDay(): ?int
    {
        return $this->usageCountPerDay;
    }

    public function setUsageCountPerDay(int $usageCountPerDay): self
    {
        $this->usageCountPerDay = $usageCountPerDay;

        return $this;
    }
}
