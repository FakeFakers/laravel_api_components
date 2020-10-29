# Laravel Api Components

## Components list

### Middleware
1. `PreferJson` - a simple middleware that can be used in api routes, to prefer JSON-responses instead of default HTML.
Also has an option to force JSON output even if Accept header is present. 

### Helper functions
1. `formatValidationErrors` - transforms ValidationException to array.
1. `escapeStringForSqlLike` - escape string to use it in SQL LIKE queries.

### Exceptions handler
Provides a simple way to define application exception handlers.  
Usage:
1. Inherit `app/Exception/Handler.php` class from exception handler from this package:
    ```php
    use Brainex\ApiComponents\Exceptions\BaseHandler;
    
    class Handler extends BaseHandler
    {
       // write your code here
    }
 
    ```
2. Define your own exception handlers:
    ```php
        /**
         * A list of custom exception handlers
         *
         * @return array
         */
        protected function getCustomHandlers(): array
        {
            return [
                OAuthServerException::class => [$this, 'oauthServerJson']
            ];
        }
    
        /**
         * Get JsonResponse for OAuthServerException exception
         *
         * @param OAuthServerException $exception
         * @return JsonResponse
         */
        protected function oauthServerJson(OAuthServerException $exception): JsonResponse
        {
            return \response()->json([
                'message' => $exception->getErrorType(),
                'data' => [
                    'description' => $exception->getMessage(),
                    'hint' => $exception->getHint()
                ]
            ], $exception->getHttpStatusCode(), $exception->getHttpHeaders());
        }
    ```

### Service provider
1. `RoutingServiceProvider` - overriding Laravel's default Router and ResponseFactory to return JSON responses with `JSON_UNESCAPED_UNICODE`.  
Usage:  
    1. Create `Application` class in your app folder:
        ```php
        declare(strict_types=1);
        
        namespace App;
        
        use Illuminate\Log\LogServiceProvider;
        use Illuminate\Events\EventServiceProvider;
 
        /**
         * Class Application
         * @package App
         *
         * Overriding original application to hook router
         */
        class Application extends \Illuminate\Foundation\Application
        {
            /**
             * Register all of the base service providers.
             *
             * @return void
             */
            protected function registerBaseServiceProviders(): void
            {
                $this->register(new EventServiceProvider($this));
        
                $this->register(new LogServiceProvider($this));
        
                // hook responses to make them JSON_UNESCAPED_UNICODE
                $this->register(new \Brainex\ApiComponents\Routing\RoutingServiceProvider($this));
            }
        }
        ```
    1. Patch `bootstrap/app.php` file to create new `Application` class instead of Laravel's default:
        ```php
        $app = new \App\Application(
            realpath(__DIR__.'/../')
        );
        
        ``` 
