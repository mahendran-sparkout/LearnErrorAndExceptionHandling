<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Request;
use Exception;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Auth\AuthenticationException;
use League\OAuth2\Server\Exception\OAuthServerException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\QueryException;
use BadMethodCallException;
use TypeError;
use OutOfBoundsException;
use Auth;
use DB;
use Error;
use ParseError;
use Symfony\Component\ErrorHandler\Error\FatalError;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
      /*
      * types error in php: Warning, Notice, Parse and Fatal
      */
      $this->renderable(function (Error $e, $request) {
        
         $responseMessage = array(
            "URL"=>$request->url(),
            "Method"=>$request->method(),
            "Type" => "Genral Error",
            "Message"=>$e->getMessage(),
            "Line"=>$e->getLine()
         );

         return self::__responseMessage($responseMessage, 405);
      });

      $this->renderable(function (TypeError $e, $request) {
            
         $responseMessage = array(
            "URL"=>$request->url(),
            "Method"=>$request->method(),
            "Type" => "Type Error",
            "Message"=> $e->getMessage(),
            "Line"=>$e->getLine()
         );
         return self::__responseMessage($responseMessage, 403);
      });

      $this->renderable(function (ParseError $e, $request) {

         $responseMessage = array(
             "URL"=>$request->url(),
             "Method"=>$request->method(),
             "Type" => "Parse/Syntax Error",
             "Message"=>$e->getMessage(),
             "Line"=>$e->getLine()
         );
         return self::__responseMessage($responseMessage, 403);
      });

      $this->renderable(function (FatalError $e, $request) {
         $responseMessage = array(
            "URL"=>$request->url(),
            "Method"=>$request->method(),
            "Type" => "Fatal Error",
            "Message"=>$e->getMessage(),
            "Line"=>$e->getLine()
         );
         
         return self::__responseMessage($responseMessage, 403);
      });

        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            $responseMessage = array(
               "URL"=>$request->url(),
               "Method"=>$request->method(),
               "Type" => "Method Not Allowed",
               "Message"=>$e->getMessage(),
               "Line"=>$e->getLine()
            );

            return self::__responseMessage($responseMessage, 405);
         });
      

         $this->renderable(function (NotFoundHttpException $e, $request) {
            $responseMessage = array(
               "URL"=>$request->url(),
               "Method"=>$request->method(),
               "Type" => "Not Found HTTP Exception",
               "File"=>$e->getFile(),
               "Line"=>$e->getLine()
            );
            return self::__responseMessage($responseMessage, 404);
         });

         $this->renderable(function (ModelNotFoundException $e, $request) {
            $responseMessage = array(
               "URL"=>$request->url(),
               "Method"=>$request->method(),
               "Type" => "Model Not Found Exception",
               "File"=>$e->getFile(),
               "Line"=>$e->getLine()
            );
            return self::__responseMessage($responseMessage, 404);
         });
         

         $this->renderable(function (HttpException $e, $request) {
            $responseMessage = array(
               "URL"=>$request->url(),
               "Method"=>$request->method(),
               "Type" => "HTTP Exception",
               "File"=>$e->getFile(),
               "Line"=>$e->getLine()
            );
            return self::__responseMessage($responseMessage, 404);
         });
 
         $this->renderable(function (InvalidArgumentException $e, $request) {
            $responseMessage = array(
               "URL"=>$request->url(),
               "Method"=>$request->method(),
               "Type" => "Invalid Argument Exception",
               "File"=>$e->getFile(),
               "Line"=>$e->getLine()
            );
            return self::__responseMessage($responseMessage, 404);
            });

         $this->renderable(function (QueryException $e, $request) {
            $responseMessage = array(
               "URL"=>$request->url(),
               "Method"=>$request->method(),
               "Type" => "Query Exception",
               "File"=>$e->getFile(),
               "Line"=>$e->getLine()
            );
            return self::__responseMessage($responseMessage, 403);
         });


         $this->renderable(function (BadMethodCallException $e, $request) {
            $responseMessage = array(
               "URL"=>$request->url(),
               "Method"=>$request->method(),
               "Type" => "Bad Method Call Exception",
               "File"=>$e->getFile(),
               "Line"=>$e->getLine()
            );
            return self::__responseMessage($responseMessage, 404);
         });

         
         $this->renderable(function (OutOfBoundsException $e, $request) {
            $responseMessage = array(
               "URL"=>$request->url(),
               "Method"=>$request->method(),
               "Type" => "Out of Bounds Exception",
               "File"=>$e->getFile(),
               "Line"=>$e->getLine()
            );
            return self::__responseMessage($responseMessage, 403);
         });

         $this->renderable(function (BindingResolutionException $e, $request) {
            $responseMessage = array(
               "URL"=>$request->url(),
               "Method"=>$request->method(),
               "Type" => "BindingResolutionException",
               "File"=>$e->getFile(),
               "Line"=>$e->getLine()
            );
           return self::__responseMessage($responseMessage, 403);
         });
 
    }

    public function __responseMessage($responseMessage, $responseCode)
    {
      Log::warning($responseMessage);
      return response()->json([
         "status" => false,
         "error" => $responseMessage,
         "result" => " "], $responseCode);
    }
}


      /*$this->renderable(function (Exception $e, $request) {
         $responseMessage = array(
            "URL"=>$request->url(),
            "Method"=>$request->method(),
            "Type" => "General Exception",
            "Message"=>$e->getMessage(),
            "File"=>$e->getFile(),
            "Line"=>$e->getLine()
         );

        return self::__responseMessage($responseMessage, 405);
      });
   */


