<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use App\Error\JsonValidationException;
use Authentication\Controller\Component\AuthenticationComponent;
use Cake\Core\Configure;
use Cake\Database\Exception\DatabaseException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\View\Exception\MissingTemplateException;
use Exception;
use Firebase\JWT\JWT;

class DataController extends AppController
{
    public function service($serviceName, $methodName, $validationErrors = []): void
    {
        $autorization = [
            // 'token' => $this->Authentication->getIdentity()->__debugInfo(),
            'user' => $this->Authentication->getIdentity()->getOriginalData(),
        ];

        $response = [
            'code' => 200,
            'data' => null,
            'message' => 'Operation is completed.',
            'autorization' => $autorization,
        ];

        try {
            if (count($validationErrors) >= 1) {
                throw new JsonValidationException(
                    'There is invalid data.',
                    $validationErrors
                );
            }

            $fullServiceName = 'App\Services\\'. $serviceName;
            $service = new $fullServiceName(
                $this->request,
                $this->Authentication,
                $autorization
            );

            $response['data'] = $service->$methodName(
                array_merge($this->_getCleanParams(), $this->request->getData())
            );
        } catch (Exception $exception) {
            $response['code'] = $exception->getCode();
            $response['message'] = $exception->getMessage();
            if ($exception instanceof JsonValidationException) {
                /* @var JsonValidationException **/
                $response['data'] = $exception->getErrors();
            }

            if (!is_numeric($exception->getCode())) {
                $response['code'] = 500;
                $response['message'] = 'Internal error!';
            }

            if (isset($service) && isset($service::$models)) {
                foreach ($service::$models as $model) {
                    $model['handler']->delete($model['entity']);
                }
            }
        }

        $this->set($response);
        $this->viewBuilder()->setOption('serialize', true);
        $this->RequestHandler->renderAs($this, 'json');
    }

    protected function _getCleanParams(): array
    {
        $params = $this->request->getAttribute('params');

        unset($params['pass']);
        unset($params['controller']);
        unset($params['action']);
        unset($params['_method']);
        unset($params['plugin']);
        unset($params['_matchedRoute']);
        unset($params['_ext']);
        unset($params['?']['q']);
        $params = array_merge($params, $params['?']);
        unset($params['?']);

        return $params;
    }
}