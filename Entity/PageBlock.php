<?php

namespace Arkounay\Bundle\BlockBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Page Block entity. Represents an HTML block that will be displayed in a page.
 * @ORM\Entity()
 * @ORM\Table(name="page_block")
 * @UniqueEntity(fields = {"id"})
 */
class PageBlock
{

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(type="string", length=40)
     * @Assert\NotBlank();
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

}
