<?php

namespace App\Enum;

class Constants
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_DELETED = -1;
    const STATUS_ARRAY = [
        self::STATUS_ACTIVE => 'active',
        self::STATUS_INACTIVE => 'inactive',
        self::STATUS_DELETED => 'deleted'
    ];

    const ACCT_TYPE_EMAIL = 1;
    const ACCT_TYPE_FB = 2;
    const ACCT_TYPE_GMAIL = 3;
    const ACCT_TYPE_ARRAY = [
        self::ACCT_TYPE_EMAIL => 'email',
        self::ACCT_TYPE_FB => 'facebook',
        self::ACCT_TYPE_GMAIL => 'gmail'
    ];

    const CAROUSEL_BANNER_SLUG = 'carousel-banner';

    const CAROUSEL_BANNER = 1;
    const LEFT_SUB_BANNER_SLUG = 'left-small-banner';

    const LEFT_SUB_BANNER = 2;
    const RIGHT_SUB_BANNER_SLUG = 'right-small-banner';
    const RIGHT_SUB_BANNER = 3;

    const PAYMENT_TYPE_CARD = 'Card';
    const PAYMENT_TYPE_GCASH = 'GCash';
    const PAYMENT_TYPE_COD = 'COD';
    const PAYMENT_TYPE_PAYMAYA = 'Paymaya';

    const PAYMENT_TYPE_ARRAY = [
        'Card' => 'Card',
        'GCash' => 'GCash',
        'COD' => 'COD',
        'Paymaya' => 'Paymaya'
    ];

    const ORDER_STATUS_PENDING = 0;
    const ORDER_STATUS_PAYMENT_FAILED = -1;
    const ORDER_STATUS_COMPLETED = 1;
    const ORDER_STATUS_FOR_SHIPPING = 2;

    const ORDER_STATUS_ARRAY = [
        '-1' => 'payment failed',
        0 => 'pending',
        1 => 'completed',
        2 => 'for shipping'
    ];

    const TICKET_STATUS_EXPIRED = -1;
    const TICKET_STATUS_PENDING = 0;
    const TICKET_STATUS_WON = 1;

    const TICKET_STATUS_ARRAY = [
        'pending',
        'won',
        '-1' => 'expired'
    ];

    const CONTACT_US_EMAIL = "admin@admin.com";

    const SHIPPING_WEIGHT = [
        'oversized' => 20000,
        'box' => 6000,
        'big-pouch' => 10000,
        'medium-pouch' => 5000,
        'small-pouch' => 3000
    ];
}
