<?php

namespace App;

use Nette,
	Model;


/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter
{

    public function renderDefault()
    {
        $this->template->user = $this->user;
    }
        
    public function actionGoogleLogin()
    {
        $url = $this->context->googleAuth->getLoginUrl(array(
            'scope' => $this->context->parameters['googleAuth']['scope'],
            'redirect_uri' => $this->link('//googleAuth'),
        ));
        $this->redirectUrl($url);
    }


    public function actionGoogleAuth($code, $error = NULL)
    {
        if ($error) {
            $this->flashMessage('Please allow this application access to your Google account in order to log in.');
            $this->redirect('default');
        }

        $g = $this->context->googleAuth;
        try {
            $token = $g->getToken($code, $this->link('//googleAuth'));
        } catch (\Fmlabs\GoogleAuth\GoogleException $ge) {
            $this->flashMessage('Error: '.$ge->getMessage(), 'error');
            $this->redirect('default');
        }
        $this->user->login((array) $g->getInfo($token));
        $this->redirect('default');
    }
    
    public function actionLogout()
    {
        $this->user->logout();
        $this->redirect('default');
    }

}
