todo
----

0.8.*

* add interface for caller DONE
* commits count DONE

0.9.*
* isolate objects like grit, clean constructor of Commit, Log, Tag, Tree, Diff by accepting the repository as mandatory argument DONE
* find a way to populate object props from the sha inside the objects DONE
* inject the caller and the command to the objects to populate props DONE
* use sha (default to HEAD) whenever it's possible inside constructors DONE
* remove the dependency-injection and config dependency DONE
* rewrite the tree implementation to not use recursion on every request DONE
* git pull

1.0.0
* remotes DONE
* better status handling with --porcelain DONE
* named exceptions DONE
* unstage DONE

next
* git blame
* blobs management
* submodules management
* signed tags
* SSH to execute command on remote server

