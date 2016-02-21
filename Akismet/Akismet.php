<?php

namespace IdnoPlugins\Akismet {

    /**
     * Basic Akismet class.
     * 
     * TODO: Submit ham/spam
     */
    class Akismet {

	private $key;
	private $blogurl;
	
	private $endpoint = "";
	
	/**
	 * Construct a new Akismet object
	 * @param string $blogURL Url of your blog
	 * @param string $developerkey WordPress API key.
	 */
	public function __construct($blogURL, $developerkey) {
	    
	    $this->key = trim($developerkey, ' .');
	    $this->blogurl = $blogURL;
	    
	    $this->endpoint = "https://{$this->key}.rest.akismet.com/1.1/";
	    
	}
	
	/**
	 * Verify your key
	 * @param type $key Key to verify, if different from the one passed to the constructor.
	 */
	public function verifyKey($key = null) {
	    
	    if (empty($key)) $key = $this->key;
	    
	    return $this->call('verify-key', [
		'blog' => $this->blogurl,
		'key' => $key
	    ]);
	}
	
	/**
	 * Ask Akismet if a comment is spam.
	 * @param type $author Name submitted with the comment.
	 * @param type $author_email Email address submitted with the comment.
	 * @param type $author_url URL submitted with comment.
	 * @param type $comment The content that was submitted.
	 * @param type $permalink The permanent location of the entry the comment was submitted to.
	 */
	public function isCommentSpam(
		$author,
		$author_email,
		$author_url, 
		$comment, 
		$permalink 
	) {
	    
	    $userip = $_SERVER['REMOTE_ADDR'];
	    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$proxies = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']); // We are behind a proxy
		$userip      = trim($proxies[0]);
	    }
	    
	    return $this->call('comment-check', [
		'blog' => $this->blogurl,
		'user_ip' => $userip,
		'user_agent' => $_SERVER['HTTP_USER_AGENT'],
		'referrer' => $_SERVER['HTTP_REFERER'],
		'permalink' => $permalink,
		'comment_type' => 'comment',
		'comment_author' => $author,
		'comment_author_email' => $author_email,
		'comment_author_url' => $author_url,
		'comment_content' => $comment
	    ]);
	}
	
	protected function call($method, array $params = null) {
	    
	    $result = \Idno\Core\Webservice::post($this->endpoint . $method, $params);
	   
	    if (trim($result['content']) == 'valid') {
		return true; // Key check call
	    } else if (trim($result['content']) == 'true') {
		return true;
	    } else if (trim($result['content']) == 'false') {
		return false;
	    } else if (trim($result['content']) == 'invalid') {
		$error = "";
		if (preg_match('/X-akismet-debug-help: (.*)/', $result['header'], $matches)) {
		    $error = $matches[1];
		}
		if (!$error) {
		    $error = "Sorry, your key or query was invalid";
		}
		
		throw new \Exception($error);
	    }
	    
	    throw new \Exception('There was a problem communicating with Akismet');
	    
	}

    }

}
