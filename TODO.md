TO-DO
=====

General
-------

Add a namespace...ugh.



Views
-----

Decide which way to go:

	ClientListView {
		render('list', $parameters) {}
	}

	ClientListView {
		list($parameters) {}
	}

	ClientListView {  // The current method.
		__construct($parameters) {}
		render() {} // Specified by the interface.
	}

	ClientListView {
		render($parameters) {}
	}

	
	
Dispatcher
----------

Try to include the basic index functionality:

	try {
		/*
		 * Dispatch the request to the appropriate controller and action, if they
		 * exist. This also sets up the user session and does some other work.
		 */
		Dispatch::service('/url/prefix', 'default_controller', 'default_action');
	} catch (RedirectException $e) {
		/*
		 * We received a RedirectException, we need to take the appropriate
		 * action.
		 */
		if ($e->getMessage() !== NULL && strlen($e->getMessage()) > 0) {
			/*
			 * Append this message to the feedback information. The code
			 * variable is used by RedirectException to pass along the
			 * type/priority of this redirect.
			 */
			$_SESSION['messages'][] = new Message($e->getCode(), $e->getMessage());
		}

		/*
		 * If this is a bad redirect, log the message and stack trace.
		 */
		if ($e->getCode() == Message_Priority::ERROR) {
			error_log('Caught in Dispatch: RedirectException[message="'.$e->getMessage().'", priority=ERROR, URL='.$e->getURL().', trace='. $e->getTraceAsString().']');
		} else if ($e->getCode() == Message_Priority::WARNING) {
			error_log('Caught in Dispatch: RedirectException[message="'.$e->getMessage().'", priority=WARNING, URL='.$e->getURL().', trace='. $e->getTraceAsString().']');
		}
		
		/*
		 * Finally, send an HTTP redirect code to the client.
		 */
		header("HTTP/1.1 302 Moved Temporarily");
		header("Location: ".$e->getURL());
		die();
	} catch (Exception $e) {
		/*
		 * Log the error.
		 */
		error_log('Caught in Dispatch: Exception[code='.$e->getCode().', message="'.$e->getMessage().'", trace='. $e->getTraceAsString().']');
		
		/*
		 * Create a generic, useless error message. The real message may
		 * contain sensitive information, so we give this to the user.
		 */
		$publicMessage = "An unforseen error occurred, please try again. If this continues to occur, please contact us.";
		
		/*
		 * If the request has a referer, send the user back whence it came.
		 */
		if (array_key_exists('HTTP_REFERER', $_SERVER)) {
			/*
			 * Add a session message to the user knows something broke.
			 */
			$_SESSION['messages'][] = new Message(Message_Priority::ERROR, $publicMessage);
			
			/*
			 * We have a referer, redirect the client.
			 */
			header("HTTP/1.1 500 {$publicMessage}");
			header("Location: ".$_SERVER['HTTP_REFERER']);
			die();
		} else {
			/*
			 * There isn't much we can do at this point.
			 * 
			 * TODO: Add a nice error page.
			 */
			header("HTTP/1.1 500 {$publicMessage}");
			die($publicMessage);
		}
	}