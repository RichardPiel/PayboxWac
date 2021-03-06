<?php

namespace Paybox\PayboxDirectRequest;

use Paybox\Card;
use Paybox\PayboxDirectRequest;
use Brick\Money\Money;

/**
 * Transaction without authorization request.
 */
class TransactionWithoutAuthorization implements PayboxDirectRequest
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
     * TransactionWithoutAuthorization constructor.
     *
     * @param Card   $card      The payment card.
     * @param Money  $amount    The amount to debit.
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
            'TYPE'      => '00012',
            'MONTANT'   => $this->amount->getMinorAmount()->toInt(),
            'DEVISE'    => $this->amount->getCurrency()->getNumericCode(),
            'REFERENCE' => $this->reference,
            'PORTEUR'   => $this->card->getNumber(),
            'DATEVAL'   => $this->card->getValidity(),
            'CVV'       => $this->card->getCvv(),
        ];
    }
}
