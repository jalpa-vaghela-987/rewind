<?php

namespace App\Services;


use App\Models\User;

class StripeHelper
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createStripeCustomer()
    {
        if (!$this->user->stripe_id) {
            $customer = \Stripe\Customer::create(['name' => $this->user->name, 'email' => $this->user->email]);
            $this->user->stripe_id = data_get($customer, 'id');
            $this->user->save();
        } else {
            $customer = \Stripe\Customer::Retrieve($this->user->stripe_id);

            $this->user->save();
        }

        return $customer;
    }

    public function createStripeAccount()
    {
        if (!$this->user->stripe_account_id) {
            $account = \Stripe\Account::create([
                'type' => 'custom',
                'country' => 'US',
                'email' => $this->user->email,
                'capabilities' => [
                    'card_payments' => ['requested' => true],
                    'transfers' => ['requested' => true],
                ],
            ]);
            $this->user->stripe_account_id = data_get($account, 'id');
            $this->user->save();
        } else {
            $account = \Stripe\Account::Retrieve($this->user->stripe_account_id);

            $this->user->save();
        }

        return $account;
    }

    public function createStripeSetupIntent()
    {
        $intent = \Stripe\SetupIntent::create([
            'customer' => $this->user->stripe_id
        ]);

        return $intent;
    }

    public function getStripePaymentMethods($card)
    {
        $paymentMethod = \Stripe\PaymentMethod::all([
            'customer' => $this->user->stripe_id,
            'type' => 'card',
        ]);

        //ToDo: check $card is exist with this customer via stripe
        if(!count(data_get($paymentMethod, 'data'))){
            $paymentMethod = $this->createStripePaymentMethod($card);
            return data_get($paymentMethod, 'id');
        }

        return data_get($paymentMethod,'data.id');
    }

    public function createStripePaymentMethod($card)
    {
        $paymentMethod = \Stripe\PaymentMethod::create([
            'type' => 'card',
            'card' => [
                'number' => data_get($card,'number'),
                'exp_month' => data_get($card,'exp_month'),
                'exp_year' => data_get($card,'exp_year'),
                'cvc' => data_get($card,'cvc'),
            ],
        ]);

        \Stripe\PaymentMethod::attach(
            data_get($paymentMethod,'id'),
            ['customer' => $this->user->stripe_id]
        );

        return $paymentMethod;
    }

    public function useCard($receiver, $amount, $card)
    {
        $paymentMethodId = $this->getStripePaymentMethods($card);
        try {
            \Stripe\PaymentIntent::create([
                'amount' => $amount * 100,
                'currency' => 'usd',
                'customer' => $this->user->stripe_id,
                'payment_method' => $paymentMethodId,
                'off_session' => true,
                'confirm' => true
            ], [
                'stripe_account' => $receiver->stripe_account_id
            ]);
        } catch (\Stripe\Exception\CardException $e) {
            return response()->back()->with([
                'error' => 'Something went wrong. Error code: ' . $e->getMessage()
            ]);
        }
    }
}
