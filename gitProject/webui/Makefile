VERSION = 1.5b7
HTMLFILES = *.html
JAVAFILES = *.js
PHPFILES = *.php
OTHERFILES = *.css images Makefile COPYRIGHTS Credits *.sql README ReleaseNotes
dist: $(HTMLFILES) $(PHPFILES) $(JAVAFILES) $(OTHERFILES)
	tar czf webui_v$(VERSION).tar.gz $(HTMLFILES) $(PHPFILES) $(JAVAFILES) $(OTHERFILES)

