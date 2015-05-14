# fill the empty directory of Git

## show usage.
```sh
$ php git_empty_dir.php --help

Usage: /PATH_TO/php git_empty_dir.php [OPTION]

-h, --help                show usage.
-l, --list                empty directory lists.
-a, --add                 add keeper file
-r, --remove              remove keeper file
-d, --directory=DIRECTORY default is current directory.
-k, --keeper=KEEPER       keeper file name. default is ".gitkeep".
```

## list up the empty directory.
```sh
$ php git_empty_dir.php -d target_dir --list
```
## add ".gitkeep" to the empty directory. 
```sh
$ php git_empty_dir.php -d target_dir --add
```
## remove ".gitkeep" from the empty directory. 
```sh
$ php git_empty_dir.php -d target_dir --remove
```