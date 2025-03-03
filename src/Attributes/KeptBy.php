<?php

namespace EduLazaro\Larakeep\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class KeptBy
{
    /** @var string The fully qualified class name of the keeper. */
    public string $keeperClass;

    /**
     * Create a new KeptBy attribute instance.
     *
     * @param string $keeperClass The class name of the keeper that manages the model.
     */
    public function __construct(string $keeperClass)
    {
        $this->keeperClass = $keeperClass;
    }
}
