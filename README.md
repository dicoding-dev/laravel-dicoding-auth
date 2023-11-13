# Dicoding Auth for Laravel

This package aims to simplify authentication process required by campaign sites runned by Dicoding. 

- Register custom Socialite provider for dicoding.com.
- Implements custom user provider for Laravel authentication system.
- Provide abstraction to handle oAuth callback easily.

## Usage

1. Install the package using composer:
    ```shell
    $ composer require dicoding-dev/laravel-dicoding-auth
    ```
2. Add required configurations to `config/services.php`:
    ```php
    'dicoding' => [
        'base_uri' => env('DICODING_BASE_URI'),
        'client_id' => env('DICODING_CLIENT_ID'),
        'client_secret' => env('DICODING_CLIENT_SECRET'),
        'redirect' => '/auth/dicoding/callback'
    ],
    ```
    Don't forget to add and adjust the values in the `.env` file as well. The base URI should be the base domain without ending slash.
3. Replace the default authentication guard configuration to `dicoding`:
    ```php
    'defaults' => [
        'guard' => 'dicoding',
        ...
    ],
    ```
4. Create a route and action to handle oAuth redirection. Use the following code to redirect user to Dicoding oAuth:
    ```php
    return Socialite::driver('dicoding')->redirect();
    ```
    To redirect user directly to Dicoding's registration page, use `redirect_to_register` query parameter:
    ```php
    return Socialite::driver('dicoding')->with(['redirect_to_register' => 1])->redirect();
    ```
5. Create another route and action to handle oAuth callback. Use `DicodingDev\LaravelDicodingAuth\OAuthCallbackHandler` trait and implement its abstract methods. e.g.
    ```php
    <?php
    
    namespace App\Http\Controllers;
    
    use DicodingDev\LaravelDicodingAuth\OAuthCallbackHandler;
    use Exception;
    
    class OAuthCallbackController
    {
        use OAuthCallbackHandler;
    
        protected function handleSuccessfulAuth() {
            return redirect('/')->with('msg', 'sukses bro');
        }
    
        protected function handleAccessDenied()
        {
            return redirect('/')->with('msg', 'access denied');
        }
    
        protected function handleFailedAuth(Exception $exception) {
            return redirect('/')->with('msg', 'error bro');
        }
    }
    ```
