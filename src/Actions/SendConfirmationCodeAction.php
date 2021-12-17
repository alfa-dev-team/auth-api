<?php

namespace AlfaDevTeam\AuthApi\Actions;

use AlfaDevTeam\AuthApi\Models\UserConfirmation;
use Illuminate\Support\Facades\Notification;
use AlfaDevTeam\AuthApi\Notifications\ConfirmationNotification;

class SendConfirmationCodeAction
{
    /**
     * Contains email address or phone number
     * @var
     */
    private $receiver;

    /**
     * May contain the strings of receiver type "email" or "phone"
     * @var
     */
    private $receiverType;

    private $user;

    private UserConfirmation $userConfirmation;

    private $notificationChannels = [
        'email' => 'mail',
        'phone' => 'nexmo'
    ];

    public function getUserConfirmation(): UserConfirmation
    {
        return $this->userConfirmation;
    }

    /**
     * Defines the recipient's email or phone number and the channel type mail or phone
     *
     * @param $typeReceiver
     * @param $receiver
     * @return $this
     */
    public function setReceiver($typeReceiver, $receiver)
    {
        $this->receiverType = $typeReceiver;
        $this->receiver = $receiver;
        return $this;
    }

    public function setUser($user): SendConfirmationCodeAction
    {
        $this->user = $user;
        return $this;
    }

    public function execute(): bool
    {
        $this->setUserConfirmation();
        return $this->sendGeneratedCode();
    }

    protected function setUserConfirmation(): void
    {
        $this->userConfirmation = UserConfirmation::firstOrNew(['user_id' => $this->user->id]);
    }

    protected function sendGeneratedCode(): bool
    {
        if ($this->userConfirmation->canSendCode()) {
            $code = $this->getGeneratedCode();
            $this->userConfirmation->code = $code;
            $this->userConfirmation->save();
            $this->sendConfirmationNotification($code);
            return true;
        }
        return false;
    }

    /**
     * Get generated code
     *
     * @return string
     */
    protected function getGeneratedCode(): string
    {
        return mt_rand(100000, 999999);
    }

    protected function sendConfirmationNotification($code): void
    {
        if ($this->hasReceiver()) {
            Notification::route($this->notificationChannels[$this->receiverType], $this->receiver)
                ->notify(new ConfirmationNotification($code));
            return;
        }
        $this->user->notify(new ConfirmationNotification($code));
    }

    protected function hasReceiver()
    {
        return !is_null($this->receiver);
    }
}
