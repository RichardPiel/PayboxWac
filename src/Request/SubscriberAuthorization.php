<?php

namespace Paybox\Request;

use Paybox\Request;
use Brick\Money\Money;

/**
 * Authorization only on a subscriber.
 */
class SubscriberAuthorization implements Request
{
    /**
     * @var Money
     */
    private $amount;

    /**
     * @var string
     */
    private $paymentReference;

    /**
     * @var string
     */
    private $subscriberReference;

    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $validity;

    /**
     * @var string|null
     */
    private $cvv;

    /**
     * SubscriberAuthorization constructor.
     *
     * @param Money  $amount              The amount to authorize.
     * @param string $paymentReference    The reference of the payment, free field from 1 to 250 characters
     * @param string $subscriberReference The reference of the subscriber.
     *                                    This is the free field reference used when creating the subscriber.
     * @param string $token               The token returned in response to SubscriberRegister.
     * @param string $validity            The card validity date, in MMYY format.
     */
    public function __construct(Money $amount, $paymentReference, $subscriberReference, $token, $validity)
    {
        $this->amount              = $amount;
        $this->paymentReference    = $paymentReference;
        $this->subscriberReference = $subscriberReference;
        $this->token               = $token;
        $this->validity            = $validity;
    }

    /**
     * Sets the CVV of the card for validation.
     *
     * This is not strictly required by the Paybox platform itself,
     * but some banks refuse the transaction if the CVV is not set.
     *
     * @param string $cvv
     *
     * @return void
     */
    public function setCvv($cvv)
    {
        $this->cvv = $cvv;
    }

    /**
     * @inheritdoc
     */
    public function getValues()
    {
        $values = [
            'TYPE'      => '00051',
            'MONTANT'   => $this->amount->getAmount()->unscaledValue(),
            'DEVISE'    => $this->amount->getCurrency()->getNumericCode(),
            'REFERENCE' => $this->paymentReference,
            'REFABONNE' => $this->subscriberReference,
            'PORTEUR'   => $this->token,
            'DATEVAL'   => $this->validity,
        ];

        if ($this->cvv !== null) {
            $values['CVV'] = $this->cvv;
        }

        return $values;
    }
}
