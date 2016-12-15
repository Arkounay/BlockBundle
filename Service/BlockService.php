<?php


namespace Arkounay\BlockBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class BlockService extends \Twig_Extension
{
    private $em;
    private $authorizationChecker;
    private $roles;

    public function __construct(EntityManager $em, AuthorizationCheckerInterface $authorizationChecker, array $roles)
    {
        $this->em = $em;
        $this->authorizationChecker = $authorizationChecker;
        $this->roles = $roles['roles'];
    }

    /**
     * @param $blockId
     * @param bool $isEditable
     * @param string $dom The HTML element. Div by default.
     * @return string The HTML inside the block.
     * Will be surrounded by a special div if has the permissions to edit.
     * Will be empty if not found in the database.
     */
    public function renderBlock($blockId, $isEditable = true, $dom = 'div')
    {
        $pageBlock = $this->em->getRepository('ArkounayBlockBundle:PageBlock')->find($blockId);
        $res = '';
        if ($pageBlock !== null) {
            $res = $pageBlock->getContent();
        }
        if ($this->hasInlineEditPermissions() && $isEditable) {
            $res = '<' . $dom . ' class="js-arkounay-block-bundle-editable js-arkounay-block-bundle-block" data-id="' . $blockId . '">' . $res . '</' . $dom . '>';
        }
        return $res;
    }

    /**
     * @param $blockId
     * @param bool $isEditable
     * @return string The HTML inside the block.
     * Will be surrounded by a special span if has the permissions to edit.
     * Will be empty if not found in the database.
     */
    public function renderSpanBlack($blockId, $isEditable = true)
    {
        return $this->renderBlock($blockId, $isEditable, 'span');
    }


    /**
     * @param $entity object The Entity object that owns the field which will be edited
     * @param $field string The field name of the entity to edit
     * @param $isPlain bool If true, the edition will have very few available options in TinyMCE
     * @return string The HTML inside the block.
     * Will be surrounded by a special div if has the permissions to edit.
     * Will be empty if not found in the database.
     */
    private function renderEntityField($entity, $field, $isPlain)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $res = $accessor->getValue($entity, $field);
        $class = 'js-arkounay-block-bundle-entity';
        $tree = 'div';

        if ($isPlain) {
            $class .= '-plain';
            $tree = 'span';
        }

        if ($this->hasInlineEditPermissions()) {
            $res = '<' . $tree . ' class="js-arkounay-block-bundle-editable ' . $class . '" data-field="' . $field . '" data-entity="' . get_class($entity) . '" data-id="' . $entity->getId() . '">' . $res . '</' . $tree . '>';
        }

        return $res;
    }

    public function renderEntityFieldTwig($entity, $field)
    {
        return $this->renderEntityField($entity, $field, false);
    }

    public function renderEntityFieldPlainTextTwig($entity, $field)
    {
        return $this->renderEntityField($entity, $field, true);
    }

    /**
     * @return bool True if the current user has permissions to edit HTML inline.
     */
    public function hasInlineEditPermissions()
    {
        try {
            return $this->authorizationChecker->isGranted($this->roles);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getName()
    {
        return 'arkounay_block_bundle';
    }

    public function getFunctions()
    {
        return [
            'render_block' => new \Twig_SimpleFunction('render_block', [$this, 'renderBlock'], ['is_safe' => ['html']]),
            'render_entity_field' => new \Twig_SimpleFunction('render_entity_field', [$this, 'renderEntityFieldTwig'], ['is_safe' => ['html']]),
            'render_plain_entity_field' => new \Twig_SimpleFunction('render_plain_entity_field', [$this, 'renderEntityFieldPlainTextTwig'], ['is_safe' => ['html']]),
            'has_inline_edit_permissions' => new \Twig_SimpleFunction('has_inline_edit_permissions', [$this, 'hasInlineEditPermissions'])
        ];
    }
}