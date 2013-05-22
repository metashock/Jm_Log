# J@m; Log

Jm_Log provides a flexible and extensible framework for logginf. It implements the subject/observer pattern and allows any observer you might     think of. The package contains observers for file, console, syslog, firephp and PDO. Jm_Log allows multiple observers registered at the same time


## Installation

To install Jm_Console you can use the PEAR installer or get a tarball and install the files manually.

___
### Using the PEAR installer

If you haven't discovered the metashock pear channel yet you'll have to do it. Also you should issue a channel update:

    pear channel-discover metashock.de/pear
    pear channel-update metashock

After this you can install Jm_Console. The following command will install the lastest stable version with its dependencies:

    pear install -a metashock/Jm_Log

If you want to install a specific version or a beta version you'll have to specify this version on the command line. For example:

    pear install -a metashock/Jm_Log-0.1.0

___
### Manually download and install files

Alternatively, you can just download the package from http://www.metashock.de/pear and put into a folder listed in your include_path. Please refer to the php.net documentation of the include_path directive.


