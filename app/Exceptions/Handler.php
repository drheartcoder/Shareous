<?php

namespace App\Exceptions;

use Mail;
use Session;
use Exception;

use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException)
        {
            return response()->view('errors.404page', [], 404);
        }
        if($e instanceof \Illuminate\Session\TokenMismatchException)
        {
            return redirect()->back();
        }
        if($e instanceof \InvalidArgumentException)
        {
            return redirect(url('/'));
        }
        return parent::render($request, $e);
        $exception_message = $file = $line = $status_code = '';
        $users_arr = ['api'];
        $environment    = config('app.env');
        $message        = $e->getMessage();
        $file           = $e->getFile();
        $line           = $e->getLine();
        $status_code    = $e->getCode();
        $time_stamp     = date("Y-m-d H:i:s");
		
        if($environment != 'local'){
            if(true === in_array($request->segment(1), $users_arr)){
                $exception_message = "Oops! Something went wrong. Please try again.";

                /*Mail::raw($string_html,function($message) {
                                $message->to('webwingt@gmail.com')
                                ->from('admin@wideek.com')
                                ->subject(config('app.project.name').' :Exception caught');
                            });*/
                return response()->json(['status' => 'error', 'message' => $exception_message]);
            }else{
                parent::report($e);
                return response()->view('errors.500', ['message' => $exception_message], 500);
            }
        }else
        {
            if(true === in_array($request->segment(1), $users_arr)){
                $exception_message = 'Oops! Error '.$status_code.': '.$message.' in a file'.$file.' on line '.$line;
                return response()->json(['status' => 'error', 'message' => $exception_message]);
            }
            if ($e instanceof \Illuminate\Session\TokenMismatchException) {
                // Session::flash('error', 'Sorry, Please refresh your link properly and try again.');
				$exception_message = 'Sorry, Please refresh your link properly and try again.';
                // return redirect()->back();
				return response()->view('errors.500', ['message' => $exception_message], 500);
            }else
            if ($e instanceof \Cartalyst\Sentinel\Checkpoints\ThrottlingException) {
				$exception_message = 'Sorry, You have reached maximum login attempts. Please try after some time.';
                // return redirect()->back();
				return response()->view('errors.500', ['message' => $exception_message], 500);
                // Session::flash('error', 'You have reached maximum login attempts. Please try after some time.');
                // return redirect()->back();
            }else
            if ($e instanceof NotFoundHttpException) {
                parent::report($e);
                return response()->view('errors.404page', [], 404);
            }else
            if ($e instanceof \ErrorException) {
                $exception_message = 'Oops! Error '.$status_code.': '.$message.' in a file'.$file.' on line '.$line;
                return response()->view('errors.500', ['message' => $exception_message], 500);
            }else
            if ($e instanceof \UnexpectedValueException) {
                $exception_message = 'Oops! Error '.$status_code.': '.$message.' in a file'.$file.' on line '.$line;
                return response()->view('errors.500', ['message' => $exception_message], 500);
            }else
            if ($e instanceof \Exception) {
                $exception_message = 'Oops! Error '.$status_code.': '.$message.' in a file'.$file.' on line '.$line;
                return response()->view('errors.500', ['message' => $exception_message], 500);
            }
            $exception_message = "Oops! Something went wrong. Please try again.";

            return response()->json(['status' => 'error', 'message' => $exception_message]);
        }
        if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException)
        {
            return response()->view('errors.404page', [], 404);
        }
        if ($e instanceof \Illuminate\Session\TokenMismatchException)
        {
            return redirect()->back();
        }
        return parent::render($request, $e);
    }
}
