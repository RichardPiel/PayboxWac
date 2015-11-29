<?php

namespace Paybox\Request;

use Paybox\Card;
use Paybox\Request;
use Brick\Money\Money;

/**
 * Authorization only.
 */
class Authorize implements Request
{
    /**
     * @var Card
     */
    private $card;

    /**
     * @var Money
     */
    private $amount;

    /**
     * @var string
     */
    private $reference;

    /**
     * Authorize constructor.
     *
     * @param Card   $card      The payment card.
     * @param Money  $amount    The amount to authorize.
     * @param string $reference The merchant reference, free field from 1 to 250 characters.
     */
    public function __construct(Card $card, Money $amount, $reference)
    {
        $this->amount    = $amount;
        $this->card      = $card;
        $this->reference = $reference;
    }

    /**
     * @inheritdoc
     */
    public function getValues()
    {
        return [
            'TYPE'      => '00001',
            'MONTANT'   => $this->amount->getAmount()->unscaledValue(),
            'DEVISE'    => $this->amount->getCurrency()->getNumericCode(),
            'REFERENCE' => $this->reference,
            'PORTEUR'   => $this->card->getNumber(),
            'DATEVAL'   => $this->card->getValidity(),
            'CVV'       => $this->card->getCvv(),

            // optional
//            'AUTORISATION' => '',
        ];
    }
}