<?php

namespace App\Constants;

class ComplaintTypes
{
    const SPAM = 'spam';
    const HARASSMENT = 'harassment';
    const FRAUD = 'fraud';
    const INAPPROPRIATE_CONTENT = 'inappropriate_content';
    const VIOLATION_OF_TERMS = 'violation_of_terms';
    const COPYRIGHT_INFRINGEMENT = 'copyright_infringement';
    const PRIVACY_VIOLATION = 'privacy_violation';
    const STEALING = 'stealing';
    const OTHER = 'other';

    /**
     * Get all complaint types.
     *
     * @return array
     */
    public static function all()
    {
        return [
            self::SPAM,
            self::HARASSMENT,
            self::FRAUD,
            self::INAPPROPRIATE_CONTENT,
            self::VIOLATION_OF_TERMS,
            self::COPYRIGHT_INFRINGEMENT,
            self::PRIVACY_VIOLATION,
            self::OTHER,
        ];
    }
}
