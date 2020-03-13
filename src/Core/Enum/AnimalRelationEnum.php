<?php

namespace Ciliatus\Core\Enum;

use Ciliatus\Common\Enum\Enum;

/**
 * @method static AnimalRelationEnum RELATION_PARENT
 * @method static AnimalRelationEnum RELATION_FATHER
 * @method static AnimalRelationEnum RELATION_MOTHER
 * @method static AnimalRelationEnum RELATION_SIBLING
 * @method static AnimalRelationEnum RELATION_CHILD
 */
class AnimalRelationEnum extends Enum
{

    private const RELATION_PARENT = 'parent';
    private const RELATION_FATHER = 'father';
    private const RELATION_MOTHER = 'mother';
    private const RELATION_SIBLING = 'sibling';
    private const RELATION_CHILD = 'child';

}
