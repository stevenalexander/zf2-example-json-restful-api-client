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

        # controller is responsible for handling exceptions thrown by client, i.e. auth/not found
        $jsonResponse = $this->getHttpRestJsonClient()->get($config['getListUrl']);

        return new ViewModel(array("objects" => $jsonResponse['data']));
    }

    public function getAction()
    {
        $id = $this->getIdFromRouteOrRedirectHome();

        return new ViewModel();
    }

    public function addAction()
    {
        $config = $this->getServiceLocator()->get('config');
        $jsonResponse = $this->getHttpRestJsonClient()->post($config['getListUrl'], array('active' => true,'training' => true));

        return new ViewModel();
    }

    public function editAction()
    {
        $id = $this->getIdFromRouteOrRedirectHome();

        return new ViewModel();
    }

    public function deleteAction()
    {
        $id = $this->getIdFromRouteOrRedirectHome();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $config = $this->getServiceLocator()->get('config');
                $jsonResponse = $this->getHttpRestJsonClient()->delete($config['getListUrl']."/".$id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('home');
        }

        return new ViewModel(array('id' => $id));
    }

    protected function getHttpRestJsonClient()
    {
        if (!$this->httpRestJsonClient) {
            $this->httpRestJsonClient =
                $this->getServiceLocator()->get('Application\HttpRestJson\Client');
        }
        return $this->httpRestJsonClient;
    }

    protected function getIdFromRouteOrRedirectHome()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('home');
        }
        return $id;
    }
}
