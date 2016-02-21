<?php

    /**
     * Akismet pages
     */

    namespace IdnoPlugins\Akismet\Pages {

        /**
         * Default class to serve Akismet settings in administration
         */
        class Admin extends \Idno\Common\Page
        {

            function getContent()
            {
                $this->adminGatekeeper(); // Admins only
                $t = \Idno\Core\site()->template();
                $body = $t->draw('admin/akismet');
                $t->__(['title' => 'Akismet settings', 'body' => $body])->drawPage();
            }

            function postContent() {
                $this->adminGatekeeper(); // Admins only
                $key = $this->getInput('wordpress_key');
                
		// Verify key
		try {
		    
		    $akismet = new \IdnoPlugins\Akismet\Akismet(\Idno\Core\Idno::site()->config()->getURL(), $key);
		    
		    if ($akismet->verifyKey()) {
		    
			\Idno\Core\site()->config->config['akismet'] = [
			    'wordpress_key' => $key
			];

			\Idno\Core\site()->config()->save();
			\Idno\Core\site()->session()->addMessage('Your akismet application details were saved.');
		    }
		} catch (\Exception $ex) {
		    \Idno\Core\site()->session()->addErrorMessage($ex->getMessage());
		}
		
                
                $this->forward('/admin/akismet/');
            }

        }

    }