TO-DO
=====

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
