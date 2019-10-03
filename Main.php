<?php

    
    namespace IdnoPlugins\Akismet {
	
        class Main extends \Idno\Common\Plugin {
	    
	    function registerEventHooks() {
		parent::registerEventHooks();
		
		\Idno\Core\site()->addEventHook('annotation/save', function(\Idno\Core\Event $event) {
		    
		    try {
			if (!empty(\Idno\Core\Idno::site()->config()->akismet)) {

			    if ($key = \Idno\Core\Idno::site()->config()->akismet['wordpress_key']) {

				$akismet = new Akismet(\Idno\Core\Idno::site()->config()->getURL(), $key);

				$annotation = $event->data()['annotation'];
				$author_email = "";
				if ($loggedinuser = \Idno\Core\Idno::site()->session()->currentUser()) {
				    // This is a logged in user, so we have an email address.
				    $author_email = $loggedinuser->email;
				}

				if ($akismet->isCommentSpam($annotation['owner_name'], $author_email, $annotation['owner_url'], $annotation['content'], $annotation['permalink'])) {
				    $event->setResponse(false); // It's spam, prevent annotation.

				    \Idno\Core\Idno::site()->logging()->debug("AKISMET: Annotation from {$annotation['owner_name']} <{$annotation['owner_url']}> was blocked as SPAM");
				} else {
				    \Idno\Core\Idno::site()->logging()->debug("AKISMET: Annotation from {$annotation['owner_name']} <{$annotation['owner_url']}> seems OK");
				}

			    }
			}
		    } catch (\Exception $e) {
			\Idno\Core\Idno::site()->logging()->error($e->getMessage());
		    }
		});
	    }
	    
            function registerPages() {     

                 // Register admin settings
		\Idno\Core\site()->addPageHandler('admin/akismet', '\IdnoPlugins\Akismet\Pages\Admin');
		
		\Idno\Core\site()->template()->extendTemplate('admin/menu/items', 'admin/akismet/menu');
            }
	    
        }
    }
