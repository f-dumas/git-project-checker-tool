# Useful unix shell scripts

Here are some useful scripts that helps my developer daily routine. 
Hev a look and help yourself!

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->


- [:computer: Setup the project](#computer-setup-the-project)
- [Commands](#commands)
  - [:rainbow: Print messages in color](#rainbow-print-messages-in-color)
- [Scripts](#scripts)
  - [:blue_book: Examples](#blue_book-examples)
  - [Composer](#composer)
  - [Curl](#curl)
  - [Git](#git)
  - [PHP/PHP-FPM](#phpphp-fpm)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## :computer: Setup the project

- Clone this repository in your computer
- Open a terminal and go to the root folder
- Execute the setup script (_that will install the [commands](#Commands) in your ~/bin folder_)
```
./setup.sh
```
- Execute the tests script to check if everything works fine
```
./test.sh
```
 
You are now ready to use the commands and scripts :metal: 

- Check [how to use a script in your own environment](https://github.com/f-dumas/documentation/blob/master/Unix-Shell.md#how-to-execute-scripts)
- If you want to make your own scripts, have a look at this 
 [Unix/Shell documentation](https://github.com/f-dumas/documentation/blob/master/Unix-Shell.md) 
 and at the [examples below](#blue_book-examples)
 
> :warning: Do not copy those files elsewhere. They have references to other files that would be broken.

## Commands

### :rainbow: Print messages in color

> Check the file: [cmd/printColorMsg.sh](cmd/printColorMsg.sh)

This command makes a colored `echo`. A lot of different colors are available.
Check the command file for further details.
```
printColorMsg [type] [message]
# You can use functionnal types
printColorMsg info "My info message"
# or directly the color that you want
printColorMsg red "My info message"
```

> :pray: You can make a pull request if you want to add missing colors.

## Scripts

All the scripts are prefixed with a `domain name`, corresponding to the associated technology/purpose.

> :bulb: Check the help of any command for further details with `-h` option.

---

### :blue_book: Examples

####  Some examples variables and parameters usages
 
> Check the file: [scripts/example_script.sh](scripts/example_script.sh) 

List several helpful cases if you want to create a new script. 

```
$ ./scripts/example_script.sh -h
```

#### Examples about how to install a script

> Check the file: [scripts/example_setup.sh](scripts/example_setup.sh) 

List several helpful cases if you want to create a new script. 

```
$ ./scripts/example_setup.sh -h
```

#### The skeleton I'm using when I make new scripts

> Check the file: [scripts/example_skeleton.sh](scripts/example_skeleton.sh)

This script is a boilerplate to make a new script faster.

```
$ ./scripts/example_skeleton.sh -h 
```

---

### Composer

#### Recreate a symlink from a vendor package to a local project

> Check the file: [scripts/composer_bundle-symlink.sh](scripts/composer_bundle-symlink.sh)

This script creates a symbolic link between a vendor package and a local project. 
 
```
$ ./scripts/composer_bundle-symlink.sh -p my-package-in-vendor -d ~/MyProjectFolder 
```

> :bulb: It is useful when you are working on a package, or a symfony bundle, in order to test your changes in a real project.

---

### Curl

#### Execute multiple request to simulate traffic

> Check the file: [scripts/curl_execute-multiple-requests.sh](scripts/curl_execute-multiple-requests.sh)

This script executes multiple requests in order to simulate traffic.
 
```
$ ./scripts/curl_execute-multiple-requests.sh -u http://my-website.lol/home -c 10 
```

> :bulb: You can use the `-r` option to execute a random number of hits

---

### Git

#### Check local changes in projects

> Check the file: [scripts/git_check-local-projects.sh](scripts/git_check-local-projects.sh) 

This script will list your git projects and check if there are uncommitted changes.

```
$ ./scripts/git_check-local-projects.sh -f '/home/my-home/www/*'
```

> :bulb: It can also check if your are in a branch other than master, by adding the `-m` option.

#### Remove untracked files in a project

> Check the file: [scripts/git_remove-untracked-files.sh](scripts/git_remove-untracked-files.sh)

This script will remove the untracked and ignore files in your current project folder.
```
$ ./scripts/git_remove-untracked-files.sh
```

> :bulb: It can also remove untracked directories, by adding the `-a` option.

---

### PHP/PHP-FPM

#### Change php alias if there are multiple versions

> Check the file: [scripts/php_change-current-version.sh](scripts/php_change-current-version.sh)

This script will change the default `php` alias if you are using multiple versions of PHP.

```
scripts/php_change-current-version.sh$ ./php_change-current-version -V 7.4
```

#### Enable and disable xDebug in your php-fpm config

> Check the file: [scripts/php_toggle-xdebug-activation.sh](scripts/php_toggle-xdebug-activation.sh) 

This script will toggle the activation of your php-fpm xdebug.

```
$ ./php_toggle-xdebug-activation.sh -p '/etc/php/7.2/mods-available/xdebug.ini'
```

> :warning: Your xdebug configuration **MUST** be in a stand-alone file, otherwise the script will disable **all your config**.

---
