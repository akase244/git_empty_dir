<?php

// var_dump($argc);
$args = $argv;
array_shift($args);
// check include help option.
foreach ($args as $index => $option) {
    switch ($option) {
        case '-h':
        case '--help':
            echo ' 
Usage: /PATH_TO/php git_empty_dir.php [OPTION]

-h, --help                show usage.
-l, --list                empty directory lists.
-a, --add                 add keeper file
-r, --remove              remove keeper file
-d, --directory=DIRECTORY default is current directory.
-k, --keeper=KEEPER       keeper file name. default is ".gitkeep".' . PHP_EOL;
            exit;
    }
}
$mode = '';
$directory = getcwd();
$specifyDirectory = '';
$keeper = '.gitkeep';
$specifyKeeper = '';
$validOptions = [
    '-h',
    '--help',
    '-l',
    '--list',
    '-a',
    '--add',
    '-r',
    '--remove',
    '-d',
    '-k',
];
foreach ($args as $index => $option) {
    switch ($option) {
        case '-l':
        case '--list':
            if ($mode) {
                echo 'mode duplicated.' . PHP_EOL;
                exit;
            }
            $mode = 'list';
            break;
        case '-a':
        case '--add':
            if ($mode) {
                echo 'mode duplicated.' . PHP_EOL;
                exit;
            }
            $mode = 'add';
            break;
        case '-r':
        case '--remove':
            if ($mode) {
                echo 'mode duplicated.' . PHP_EOL;
                exit;
            }
            $mode = 'remove';
            break;
        case '-d':
            $suboption = $args[$index + 1]; 
            if (isset($suboption)) {
                if (
                    array_search($suboption, $validOptions) === false &&
                    strpos($suboption, '--directory=') === false
                ) {
                    if ($specifyDirectory) {
                        echo 'directory duplicated.' . PHP_EOL;
                        exit;
                    }
                    $specifyDirectory = $suboption;
                    $directory = $suboption;
                }
            }
            break;
        case '-k':
            $suboption = $args[$index + 1]; 
            if (isset($suboption)) {
                if (
                    array_search($suboption, $validOptions) === false &&
                    strpos($suboption, '--keeper=') === false
                ) {
                    if ($specifyKeeper) {
                        echo 'keeper duplicated.' . PHP_EOL;
                        exit;
                    }
                    $specifyKeeper = $suboption;
                    $keeper = $suboption;
                }
            }
            break;
    }

    if (strpos($option, '--directory=') === 0) {
        $options = explode('=', $option);
        $suboption = $options[1];
        if ($suboption) {
            if ($specifyDirectory) {
                echo 'directory duplicated.' . PHP_EOL;
                exit;
            }
            $directory = $suboption;
            $specifyDirectory = $suboption;
        }
    }
    if (strpos($option, '--keeper=') === 0) {
        $options = explode('=', $option);
        $suboption = $options[1];
        if ($suboption) {
            if ($specifyKeeper) {
                echo 'directory duplicated.' . PHP_EOL;
                exit;
            }
            $keeper = $suboption;
            $specifyKeeper = $suboption;
        }
    }
}
if (!$mode) {
    echo 'please specify "-h" or "-l" or "-a" or "-r".' . PHP_EOL;
    exit;
}
echo 'mode: ' . $mode . PHP_EOL;
echo 'target directory: ' . $directory . PHP_EOL;
echo 'keeper file name: ' . $keeper . PHP_EOL;
echo PHP_EOL;

if (!file_exists($directory . '/.git')) {
    echo $directory . ' is not git managed directory' . PHP_EOL;
    exit;
}

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator(
        $directory,
        FilesystemIterator::SKIP_DOTS
    ),
    RecursiveIteratorIterator::SELF_FIRST
);
while ($iterator->valid()) {
    if ($iterator->isDir() && $iterator->getPathname() != $directory . '/.git') {
        switch ($mode) {
            case 'list':
                // is empty dir
                if (count(scandir($iterator->getPathname())) == 2) {
                    echo 'target: ' . $iterator->getPathname() . PHP_EOL;
                }
                break;
            case 'add':
                // is empty dir
                if (count(scandir($iterator->getPathname())) == 2) {
                    touch($iterator->getPathname() . '/' . $keeper);
                    echo 'add: ' . $iterator->getPathname() . '/' . $keeper . PHP_EOL;
                }
                break;
            case 'remove':
                if (file_exists($iterator->getPathname() . '/' . $keeper)) {
                    unlink($iterator->getPathname() . '/' . $keeper);
                    echo 'remove: ' . $iterator->getPathname() . '/' . $keeper . PHP_EOL;
                }
                break;
        }
    }
    $iterator->next();
}
