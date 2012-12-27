XML_Event_Parser
================

An easily extensible wrapper to the SAX XML Parser. Quickly parses xml without creating a DOM and triggers events based on tags run into. This wrapper allows all events to be handled by 1 function, so you don't need to clutter your object with event handlers.


#How To Use It

**Include the XML_Parser.php file.**

**Instantiate an XML_Parser object, passing it the object you're going to use for event handling, and the function within that object that will be called and passed data at each event.**


##Each of the parse handlers create different kinds of filters for the events:


* **Full** - Triggers an event for every tag, opening or closing.
* **Start** - Only triggers an event for an opening tag.
* **End** - Only triggers an event for a closing tag.
* **Family** - Creates an associative array of data for each child element, and triggers an event only when the closing tag of the parent element is seen.


You can create your own handlers by extending Parse_Handler and implementing the start and end functions that are called by the underlying SAX Parser.
