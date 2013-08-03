<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Application\HttpRestJson\Client as HttpRestJsonClient;

class IndexController extends AbstractActionController
{
    protected $httpRestJsonClient;

    public function indexAction()
    {
        $config = $this->getServiceLocator()->get('config');
        return new ViewModel(array("json" =>
            $this->getHttpRestJsonClient()->get($config['getListUrl'])));
    }

    protected function getHttpRestJsonClient()
    {
        if (!$this->httpRestJsonClient) {
            $this->httpRestJsonClient =
                $this->getServiceLocator()->get('Application\HttpRestJson\Client');
        }
        return $this->httpRestJsonClient;
    }
}
