<?php

namespace App\Exceptions;

class UserSaveException extends DomainException
{
    protected $message = 'Could not save user';
    protected $code = 'checkout-back_user_save_exception';
    protected $description = 'Не смог сохранить пользователя';
}
