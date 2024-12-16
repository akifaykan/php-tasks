<?php

namespace Core\Helpers;

use IntlDateFormatter;
use DateTime;

class FormatHelpers
{
    public static function formatDateTR(): string
    {
        /**
         * Date returns in Turkish format.
         * @param string $format
         * @return string
         */
        $formatter = new IntlDateFormatter(
            'tr_TR',
            IntlDateFormatter::LONG,
            IntlDateFormatter::NONE
        );

        return $formatter->format(new DateTime());
    }
}
