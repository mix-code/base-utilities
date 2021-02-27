<?php

namespace MixCode\BaseUtilities\Utilities;

use Illuminate\Database\Eloquent\Builder;

trait UsingPaymentMethodsStatus
{
    public function hasPaymentMethod($payment_method)
    {
        return $this->payment_method == $payment_method;
    }

    public function hasPaypalPaymentMethod()
    {
        return $this->hasPaymentMethod(static::PAYPAL_PAYMENT_METHOD);
    }

    public function hasTapPaymentMethod()
    {
        return $this->hasPaymentMethod(static::TAP_PAYMENT_METHOD);
    }

    public function hasCashPaymentMethod()
    {
        return $this->hasPaymentMethod(static::CASH_PAYMENT_METHOD);
    }

    public function scopePaypalPaymentMethod(Builder $q)
    {
        return $q->where('payment_method', static::PAYPAL_PAYMENT_METHOD);
    }
    
    public function scopeTapPaymentMethod(Builder $q)
    {
        return $q->where('payment_method', static::TAP_PAYMENT_METHOD);
    }
    
    public function scopeCashPaymentMethod(Builder $q)
    {
        return $q->where('payment_method', static::CASH_PAYMENT_METHOD);
    }

    public function payWith($payment_method)
    {
        $this->update(['payment_method' => $payment_method]);

        return $this;
    }
    
    public function payWithPaypal()
    {
        return $this->payWith(static::PAYPAL_PAYMENT_METHOD);
    }
    
    public function payWithTap()
    {
        return $this->payWith(static::TAP_PAYMENT_METHOD);
    }
    
    public function payWithCash()
    {
        return $this->payWith(static::CASH_PAYMENT_METHOD);
    }
}