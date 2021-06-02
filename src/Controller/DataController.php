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
use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\View\Exception\MissingTemplateException;
use Exception;

class DataController extends AppController
{
    public function service($serviceName, $methodName): void
    {
        $autorization = null;

        $response = [
            'code' => 200,
            'data' => null,
            'autorization' => $autorization,
        ];

        try {
            $fullServiceName = 'App\Services\\'. $serviceName;
            $service = new $fullServiceName();
            $autorization = null;

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
        }

        $this->set($response);
        $this->viewBuilder()->setOption('serialize', true);
        $this->RequestHandler->renderAs($this, 'json');
    }

    private function _getCleanParams(): array
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