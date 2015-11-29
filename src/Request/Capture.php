<?php

namespace Paybox\Request;

use Paybox\Request;
use Brick\Money\Money;

/**
 * Debit (Capture).
 */
class Capture implements Request
{
    /**
     * @var Money
     */
    private $amount;

    /**
     * @var string
     */
    private $reference;

    /**
     * @var string
     */
    private $numappel;

    /**
     * @var string
     */
    private $numtrans;

    /**
     * Capture constructor.
     *
     * @param Money  $amount
     * @param string $reference
     * @param string $numappel
     * @param string $numtrans
     */
    public function __construct(Money $amount, $reference, $numappel, $numtrans)
    {
        $this->amount    = $amount;
        $this->reference = $reference;
        $this->numappel  = $numappel;
        $this->numtrans  = $numtrans;
    }

    /**
     * @inheritdoc
     */
    public function getValues()
    {
        return [
            'TYPE'      => '00002',
            'MONTANT'   => $this->amount->getAmount()->unscaledValue(),
            'DEVISE'    => $this->amount->getCurrency()->getNumericCode(),
            'NUMAPPEL'  => $this->numappel,
            'NUMTRANS'  => $this->numtrans,
            'REFERENCE' => $this->reference,
        ];
    }
}