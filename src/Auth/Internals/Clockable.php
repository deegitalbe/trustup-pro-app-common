<?php
namespace Deegitalbe\TrustupProAppCommon\Auth\Internals;

use DateTimeImmutable;
use Lcobucci\Clock\Clock;

/** 
 * Entity able to return a immutable date representing now. 
 */
class Clockable implements Clock
{
    /**
     * Getting now immutable representation.
     * 
     * @return DateTimeImmutable
     */
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}