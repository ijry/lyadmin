<?php

namespace Joli\Jane\Normalizer;

use Joli\Jane\Runtime\Normalizer\ArrayDenormalizer;

@trigger_error('NormalizerArray is deprecated since 1.4 and will be removed in 2.0, please use Joli\Jane\Runtime\Normalizer\ArrayDenormalizer instead.', E_USER_DEPRECATED);

/**
 * @deprecated NormalizerArray is deprecated since 1.4 and will be removed in 2.0, please use Joli\Jane\Runtime\Normalizer\ArrayDenormalizer instead.
 */
class NormalizerArray extends ArrayDenormalizer
{
}
