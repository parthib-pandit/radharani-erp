# Auth config additions needed

Add to `config/auth.php` (don't overwrite the whole file — merge these in):

```php
'guards' => [
    // ...existing 'web' guard stays...

    'customer' => [
        'driver' => 'session',
        'provider' => 'customers',
    ],
],

'providers' => [
    // ...existing 'users' provider stays...

    'customers' => [
        'driver' => 'eloquent',
        'model' => App\Models\Customer\Customer::class,
    ],
],

'passwords' => [
    // ...existing 'users' entry stays...

    'customers' => [
        'provider' => 'customers',
        'table' => 'password_reset_tokens', // fine to share, keyed by email
        'expire' => 60,
        'throttle' => 60,
    ],
],
```

This keeps staff (`users`/`web` guard) and customers (`customers`/`customer` guard) completely
separate — a customer login can never touch staff-only routes, and vice versa.
