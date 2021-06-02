<?php
declare(strict_types=1);

namespace App\Error;

use Cake\Core\Configure;
use Cake\Core\Exception\CakeException;
use Cake\Error\Debugger;
use Cake\Error\ExceptionRenderer;
use Cake\Http\Exception\HttpException;
use Cake\Http\Response;
use Exception;
use Psr\Http\Message\ResponseInterface;

class AppExceptionRenderer extends ExceptionRenderer
{
    public function render(): ResponseInterface
    {
        $exception = $this->error;
        $code = 200;
        // $code = $this->getHttpCode($exception);
        $method = $this->_method($exception);
        $template = $this->_template($exception, $method, $code);
        $this->clearOutput();

        if (method_exists($this, $method)) {
            return $this->_customMethod($method, $exception);
        }

        $message = $this->_message($exception, $code);
        $url = $this->controller->getRequest()->getRequestTarget();
        $response = $this->controller->getResponse();

        if ($exception instanceof CakeException) {
            /** @psalm-suppress DeprecatedMethod */
            foreach ((array)$exception->responseHeader() as $key => $value) {
                $response = $response->withHeader($key, $value);
            }
        }
        if ($exception instanceof HttpException) {
            foreach ($exception->getHeaders() as $name => $value) {
                $response = $response->withHeader($name, $value);
            }
        }
        $response = $response->withStatus($code);

        $autorization = null;
        $viewVars = [
            'code' => $this->getHttpCode($exception),
            'data' => null,
            'message' => $message,
            'autorization' => $autorization,
        ];

        $isDebug = Configure::read('debug');
        if ($isDebug) {
            $trace = (array)Debugger::formatTrace($exception->getTrace(), [
                'format' => 'array',
                'args' => false,
            ]);
            $origin = [
                'file' => $exception->getFile() ?: 'null',
                'line' => $exception->getLine() ?: 'null',
            ];
            // Traces don't include the origin file/line.
            array_unshift($trace, $origin);
            $viewVars['trace'] = $trace;
            $viewVars += $origin;
            $serialize[] = 'file';
            $serialize[] = 'line';
        }

        $this->controller->set($viewVars);
        $this->controller->viewBuilder()->setOption('serialize', true);
        // $this->controller->viewBuilder()->setOption('serialize', $serialize);
        $this->controller->RequestHandler->renderAs($this->controller, 'json');

        if ($exception instanceof CakeException && $isDebug) {
            $this->controller->set($exception->getAttributes());
        }
        $this->controller->setResponse($response);

        return $this->_outputMessage($template);
    }
}